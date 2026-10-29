<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Models\CarbonEmissionFactor;
use App\Models\Location;
use App\Models\ServiceLevel;
use App\Models\Shipment;
use App\Models\TrackingEvent;
use App\Models\TransportLeg;
use App\Models\User;
use App\Services\CarbonEmissionCalculatorService;
use App\Services\DistanceCalculationService;
use App\Services\ExternalWeatherService;
use App\Services\TrackingNumberGenerator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class ShipmentController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();

        $query = Shipment::with(['originLocation', 'destinationLocation', 'serviceLevel', 'operator', 'customer']);

        // Authorization filter: Customers only see their own shipments
        if ($user->isCustomer()) {
            $query->where('customer_user_id', $user->id);
        }

        // Filters
        if ($request->filled('status')) {
            $query->where('current_status', $request->status);
        }

        if ($request->filled('service_level_id')) {
            $query->where('service_level_id', $request->service_level_id);
        }

        if ($request->filled('destination_type')) {
            $query->where('destination_type', $request->destination_type);
        }

        if ($request->filled('exception_status') && $request->exception_status === 'active') {
            $query->whereHas('activeExceptions');
        }

        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where(function ($q) use ($search) {
                $q->where('tracking_number', 'like', "%{$search}%")
                  ->orWhere('cargo_description', 'like', "%{$search}%")
                  ->orWhere('sender_business_name', 'like', "%{$search}%")
                  ->orWhere('receiver_business_name', 'like', "%{$search}%");
            });
        }

        $shipments = $query->orderBy('created_at', 'desc')->paginate(12)->withQueryString();

        $serviceLevels = ServiceLevel::where('is_active', true)->get();

        return view('shipments.index', compact('shipments', 'serviceLevels'));
    }

    public function create()
    {
        Gate::authorize('create', Shipment::class);

        $serviceLevels = ServiceLevel::where('is_active', true)->get();
        $locations = Location::orderBy('name', 'asc')->get();
        $customers = User::where('role', 'customer')->orderBy('name', 'asc')->get();
        $operators = User::where('role', 'operator')->orderBy('name', 'asc')->get();
        $emissionFactors = CarbonEmissionFactor::orderBy('transport_mode', 'asc')->get();

        return view('shipments.create', compact('serviceLevels', 'locations', 'customers', 'operators', 'emissionFactors'));
    }

    public function store(Request $request)
    {
        Gate::authorize('create', Shipment::class);

        $validated = $request->validate([
            'customer_user_id' => ['nullable', 'exists:users,id'],
            'assigned_operator_id' => ['nullable', 'exists:users,id'],
            'service_level_id' => ['required', 'exists:service_levels,id'],
            'destination_type' => ['required', 'string', 'in:door_address,seaport_terminal,airport_terminal,business_warehouse,fulfillment_center'],
            'origin_location_id' => ['required', 'exists:locations,id'],
            'destination_location_id' => ['required', 'exists:locations,id', 'different:origin_location_id'],
            'sender_business_name' => ['required', 'string', 'max:255'],
            'sender_contact_name' => ['required', 'string', 'max:255'],
            'sender_phone' => ['required', 'string', 'max:50'],
            'sender_email' => ['nullable', 'email', 'max:255'],
            'sender_address' => ['required', 'string'],
            'receiver_business_name' => ['required', 'string', 'max:255'],
            'receiver_contact_name' => ['required', 'string', 'max:255'],
            'receiver_phone' => ['required', 'string', 'max:50'],
            'receiver_email' => ['nullable', 'email', 'max:255'],
            'receiver_address' => ['required', 'string'],
            'cargo_description' => ['required', 'string', 'max:255'],
            'cargo_weight_tonnes' => ['required', 'numeric', 'min:0.001'],
            'cargo_volume_cbm' => ['nullable', 'numeric', 'min:0'],
            'package_count' => ['required', 'integer', 'min:1'],
            'package_type' => ['required', 'string', 'max:100'],
            'special_instructions' => ['nullable', 'string'],
            'eta' => ['nullable', 'date'],

            // Initial Leg definition
            'initial_leg_mode' => ['required', 'string', 'in:sea,land,air'],
            'initial_carrier_name' => ['required', 'string', 'max:255'],
            'initial_transport_reference' => ['nullable', 'string', 'max:255'],
            'initial_distance_km' => ['nullable', 'numeric', 'min:0'],
            'initial_emission_factor_id' => ['nullable', 'exists:carbon_emission_factors,id'],
        ]);

        $trackingNumber = TrackingNumberGenerator::generate();

        $shipment = Shipment::create([
            'tracking_number' => $trackingNumber,
            'customer_user_id' => $validated['customer_user_id'] ?? null,
            'assigned_operator_id' => $validated['assigned_operator_id'] ?? auth()->id(),
            'service_level_id' => $validated['service_level_id'],
            'destination_type' => $validated['destination_type'],
            'origin_location_id' => $validated['origin_location_id'],
            'destination_location_id' => $validated['destination_location_id'],
            'sender_business_name' => $validated['sender_business_name'],
            'sender_contact_name' => $validated['sender_contact_name'],
            'sender_phone' => $validated['sender_phone'],
            'sender_email' => $validated['sender_email'] ?? null,
            'sender_address' => $validated['sender_address'],
            'receiver_business_name' => $validated['receiver_business_name'],
            'receiver_contact_name' => $validated['receiver_contact_name'],
            'receiver_phone' => $validated['receiver_phone'],
            'receiver_email' => $validated['receiver_email'] ?? null,
            'receiver_address' => $validated['receiver_address'],
            'cargo_description' => $validated['cargo_description'],
            'cargo_weight_tonnes' => $validated['cargo_weight_tonnes'],
            'cargo_volume_cbm' => $validated['cargo_volume_cbm'] ?? null,
            'package_count' => $validated['package_count'],
            'package_type' => $validated['package_type'],
            'special_instructions' => $validated['special_instructions'] ?? null,
            'current_status' => 'BOOKED',
            'eta' => $validated['eta'] ?? null,
            'original_eta' => $validated['eta'] ?? null,
        ]);

        // Determine distance for initial leg
        $originLoc = Location::find($validated['origin_location_id']);
        $destLoc = Location::find($validated['destination_location_id']);

        $distanceKm = (float) ($validated['initial_distance_km'] ?? 0);
        $distanceSource = 'manual_operator_override';

        if ($distanceKm <= 0 && $originLoc && $destLoc) {
            $distanceKm = DistanceCalculationService::calculate(
                $originLoc->latitude,
                $originLoc->longitude,
                $destLoc->latitude,
                $destLoc->longitude,
                $validated['initial_leg_mode']
            );
            $distanceSource = 'calculated_haversine';
        }

        // Create Leg 1
        $leg = TransportLeg::create([
            'shipment_id' => $shipment->id,
            'sequence_number' => 1,
            'transport_mode' => $validated['initial_leg_mode'],
            'origin_location_id' => $validated['origin_location_id'],
            'destination_location_id' => $validated['destination_location_id'],
            'carrier_name' => $validated['initial_carrier_name'],
            'transport_reference' => $validated['initial_transport_reference'] ?? null,
            'distance_km' => $distanceKm,
            'distance_source' => $distanceSource,
            'emission_factor_id' => $validated['initial_emission_factor_id'] ?? null,
            'status' => 'PENDING',
        ]);

        // Recalculate emissions
        CarbonEmissionCalculatorService::recalculateShipmentEmissions($shipment);

        // Record initial event
        TrackingEvent::create([
            'shipment_id' => $shipment->id,
            'transport_leg_id' => $leg->id,
            'event_type' => 'BOOKED',
            'status_after_event' => 'BOOKED',
            'event_time' => now(),
            'location_name' => $originLoc->name,
            'latitude' => $originLoc->latitude,
            'longitude' => $originLoc->longitude,
            'description' => "Shipment booking confirmed with {$shipment->serviceLevel->name}. Carrier {$leg->carrier_name} assigned.",
            'recorded_by_user_id' => auth()->id(),
        ]);

        // Audit log
        AuditLog::log(
            'CREATE',
            $shipment,
            "Created shipment {$shipment->tracking_number} ({$shipment->serviceLevel->name}) with Leg 1 ({$leg->transport_mode}).",
            null,
            $shipment->toArray()
        );

        return redirect()->route('shipments.show', $shipment->id)
            ->with('success', "Shipment {$shipment->tracking_number} successfully registered and ready for dispatch.");
    }

    public function show(Shipment $shipment)
    {
        Gate::authorize('view', $shipment);

        $shipment->load([
            'originLocation',
            'destinationLocation',
            'serviceLevel',
            'operator',
            'customer',
            'transportLegs.originLocation',
            'transportLegs.destinationLocation',
            'transportLegs.emissionFactor',
            'trackingEvents.recordedBy',
            'deliveryExceptions.assignedOperator',
        ]);

        $locations = Location::orderBy('name', 'asc')->get();
        $emissionFactors = CarbonEmissionFactor::all();
        $operators = User::where('role', 'operator')->orderBy('name', 'asc')->get();

        // Weather data
        $activeLeg = $shipment->transportLegs->where('status', 'IN_TRANSIT')->first()
            ?? $shipment->transportLegs->where('status', 'PENDING')->first()
            ?? $shipment->transportLegs->last();

        $weatherLocation = $activeLeg?->destinationLocation ?? $shipment->destinationLocation;
        $weatherData = null;

        if ($weatherLocation) {
            $weatherData = ExternalWeatherService::getWeather(
                $weatherLocation->latitude,
                $weatherLocation->longitude
            );
            $weatherData['location_name'] = $weatherLocation->name;
            $weatherData['city'] = $weatherLocation->city;
        }

        // Map payload
        $mapRoute = [];
        foreach ($shipment->transportLegs as $leg) {
            if ($leg->originLocation && $leg->destinationLocation) {
                $mapRoute[] = [
                    'sequence' => $leg->sequence_number,
                    'mode' => $leg->transport_mode,
                    'carrier' => $leg->carrier_name,
                    'reference' => $leg->transport_reference,
                    'status' => $leg->status,
                    'distance_km' => $leg->distance_km,
                    'origin' => [
                        'name' => $leg->originLocation->name,
                        'type' => $leg->originLocation->type,
                        'lat' => $leg->originLocation->latitude,
                        'lon' => $leg->originLocation->longitude,
                    ],
                    'destination' => [
                        'name' => $leg->destinationLocation->name,
                        'type' => $leg->destinationLocation->type,
                        'lat' => $leg->destinationLocation->latitude,
                        'lon' => $leg->destinationLocation->longitude,
                    ],
                ];
            }
        }

        return view('shipments.show', compact('shipment', 'locations', 'emissionFactors', 'operators', 'weatherData', 'mapRoute'));
    }

    public function edit(Shipment $shipment)
    {
        Gate::authorize('update', $shipment);

        $serviceLevels = ServiceLevel::where('is_active', true)->get();
        $locations = Location::orderBy('name', 'asc')->get();
        $customers = User::where('role', 'customer')->get();
        $operators = User::where('role', 'operator')->get();

        return view('shipments.edit', compact('shipment', 'serviceLevels', 'locations', 'customers', 'operators'));
    }

    public function update(Request $request, Shipment $shipment)
    {
        Gate::authorize('update', $shipment);

        $validated = $request->validate([
            'customer_user_id' => ['nullable', 'exists:users,id'],
            'assigned_operator_id' => ['nullable', 'exists:users,id'],
            'receiver_business_name' => ['required', 'string', 'max:255'],
            'receiver_contact_name' => ['required', 'string', 'max:255'],
            'receiver_phone' => ['required', 'string', 'max:50'],
            'receiver_email' => ['nullable', 'email', 'max:255'],
            'receiver_address' => ['required', 'string'],
            'cargo_description' => ['required', 'string', 'max:255'],
            'cargo_weight_tonnes' => ['required', 'numeric', 'min:0.001'],
            'package_count' => ['required', 'integer', 'min:1'],
            'package_type' => ['required', 'string', 'max:100'],
            'special_instructions' => ['nullable', 'string'],
            'eta' => ['nullable', 'date'],
        ]);

        $old = $shipment->toArray();
        $shipment->update($validated);

        // Recalculate carbon emissions in case weight changed
        CarbonEmissionCalculatorService::recalculateShipmentEmissions($shipment);

        AuditLog::log(
            'UPDATE',
            $shipment,
            "Updated shipment details for {$shipment->tracking_number}.",
            $old,
            $shipment->toArray()
        );

        return redirect()->route('shipments.show', $shipment->id)
            ->with('success', "Shipment {$shipment->tracking_number} updated successfully.");
    }

    public function destroy(Shipment $shipment)
    {
        Gate::authorize('delete', $shipment);

        $tracking = $shipment->tracking_number;
        $shipment->delete();

        AuditLog::create([
            'user_id' => auth()->id(),
            'action' => 'DELETE',
            'auditable_type' => Shipment::class,
            'auditable_id' => $shipment->id,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'description' => "Soft-deleted shipment {$tracking}.",
            'created_at' => now(),
        ]);

        return redirect()->route('shipments.index')
            ->with('success', "Shipment {$tracking} was soft-deleted.");
    }
}
