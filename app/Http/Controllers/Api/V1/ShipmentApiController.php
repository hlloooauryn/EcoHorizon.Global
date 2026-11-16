<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\CarbonEmissionFactor;
use App\Models\DeliveryException;
use App\Models\Location;
use App\Models\ServiceLevel;
use App\Models\Shipment;
use App\Models\TrackingEvent;
use App\Models\TransportLeg;
use App\Services\CarbonEmissionCalculatorService;
use App\Services\DistanceCalculationService;
use App\Services\ShipmentLifecycleService;
use App\Services\TrackingNumberGenerator;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class ShipmentApiController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $user = $request->user();

        $query = Shipment::with(['originLocation', 'destinationLocation', 'serviceLevel']);

        if ($user->isCustomer()) {
            $query->where('customer_user_id', $user->id);
        }

        if ($request->filled('status')) {
            $query->where('current_status', $request->status);
        }

        $shipments = $query->orderBy('created_at', 'desc')->paginate(15);

        return response()->json([
            'success' => true,
            'data' => $shipments,
        ]);
    }

    public function show(Request $request, string $tracking_number): JsonResponse
    {
        $shipment = Shipment::where('tracking_number', strtoupper(trim($tracking_number)))
            ->with([
                'originLocation',
                'destinationLocation',
                'serviceLevel',
                'transportLegs.originLocation',
                'transportLegs.destinationLocation',
                'transportLegs.emissionFactor',
                'trackingEvents',
                'deliveryExceptions',
            ])
            ->first();

        if (!$shipment) {
            return response()->json([
                'success' => false,
                'message' => 'Shipment not found.',
            ], 404);
        }

        if (!Gate::forUser($request->user())->allows('view', $shipment)) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized to view this shipment.',
            ], 403);
        }

        return response()->json([
            'success' => true,
            'data' => $shipment,
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $user = $request->user();

        if (!$user->canManageShipments()) {
            return response()->json([
                'success' => false,
                'message' => 'Only logistics operators or administrators can dispatch shipments.',
            ], 403);
        }

        $validated = $request->validate([
            'customer_user_id' => ['nullable', 'exists:users,id'],
            'service_level_id' => ['required', 'exists:service_levels,id'],
            'destination_type' => ['required', 'string', 'in:seaport_terminal,airport_terminal,business_warehouse,fulfillment_center'],
            'origin_location_id' => ['required', 'exists:locations,id'],
            'destination_location_id' => ['required', 'exists:locations,id', 'different:origin_location_id'],
            'sender_business_name' => ['required', 'string', 'max:255'],
            'sender_contact_name' => ['required', 'string', 'max:255'],
            'sender_phone' => ['required', 'string', 'max:50'],
            'sender_address' => ['required', 'string'],
            'receiver_business_name' => ['required', 'string', 'max:255'],
            'receiver_contact_name' => ['required', 'string', 'max:255'],
            'receiver_phone' => ['required', 'string', 'max:50'],
            'receiver_address' => ['required', 'string'],
            'cargo_description' => ['required', 'string', 'max:255'],
            'cargo_weight_tonnes' => ['required', 'numeric', 'min:0.001'],
            'package_count' => ['required', 'integer', 'min:1'],
            'package_type' => ['required', 'string', 'max:100'],
            'initial_leg_mode' => ['required', 'string', 'in:sea,land,air'],
            'initial_carrier_name' => ['required', 'string', 'max:255'],
            'initial_distance_km' => ['nullable', 'numeric', 'min:0'],
        ]);

        $trackingNumber = TrackingNumberGenerator::generate();

        $shipment = Shipment::create([
            'tracking_number' => $trackingNumber,
            'customer_user_id' => $validated['customer_user_id'] ?? null,
            'assigned_operator_id' => $user->id,
            'service_level_id' => $validated['service_level_id'],
            'destination_type' => $validated['destination_type'],
            'origin_location_id' => $validated['origin_location_id'],
            'destination_location_id' => $validated['destination_location_id'],
            'sender_business_name' => $validated['sender_business_name'],
            'sender_contact_name' => $validated['sender_contact_name'],
            'sender_phone' => $validated['sender_phone'],
            'sender_address' => $validated['sender_address'],
            'receiver_business_name' => $validated['receiver_business_name'],
            'receiver_contact_name' => $validated['receiver_contact_name'],
            'receiver_phone' => $validated['receiver_phone'],
            'receiver_address' => $validated['receiver_address'],
            'cargo_description' => $validated['cargo_description'],
            'cargo_weight_tonnes' => $validated['cargo_weight_tonnes'],
            'package_count' => $validated['package_count'],
            'package_type' => $validated['package_type'],
            'current_status' => 'BOOKED',
        ]);

        // Create Leg 1
        $originLoc = Location::find($validated['origin_location_id']);
        $destLoc = Location::find($validated['destination_location_id']);

        $distanceKm = (float) ($validated['initial_distance_km'] ?? 0);
        if ($distanceKm <= 0 && $originLoc && $destLoc) {
            $distanceKm = DistanceCalculationService::calculate(
                $originLoc->latitude,
                $originLoc->longitude,
                $destLoc->latitude,
                $destLoc->longitude,
                $validated['initial_leg_mode']
            );
        }

        TransportLeg::create([
            'shipment_id' => $shipment->id,
            'sequence_number' => 1,
            'transport_mode' => $validated['initial_leg_mode'],
            'origin_location_id' => $validated['origin_location_id'],
            'destination_location_id' => $validated['destination_location_id'],
            'carrier_name' => $validated['initial_carrier_name'],
            'distance_km' => $distanceKm,
            'status' => 'PENDING',
        ]);

        CarbonEmissionCalculatorService::recalculateShipmentEmissions($shipment);

        TrackingEvent::create([
            'shipment_id' => $shipment->id,
            'event_type' => 'BOOKED',
            'status_after_event' => 'BOOKED',
            'event_time' => now(),
            'location_name' => $originLoc->name,
            'description' => "Booking registered via API by {$user->name}.",
            'recorded_by_user_id' => $user->id,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Shipment successfully created.',
            'data' => $shipment->load(['originLocation', 'destinationLocation', 'transportLegs']),
        ], 201);
    }

    public function events(Request $request, string $tracking_number): JsonResponse
    {
        $shipment = Shipment::where('tracking_number', strtoupper(trim($tracking_number)))->first();

        if (!$shipment) {
            return response()->json(['success' => false, 'message' => 'Shipment not found.'], 404);
        }

        if (!Gate::forUser($request->user())->allows('view', $shipment)) {
            return response()->json(['success' => false, 'message' => 'Unauthorized.'], 403);
        }

        $events = TrackingEvent::where('shipment_id', $shipment->id)->orderBy('event_time', 'asc')->get();

        return response()->json([
            'success' => true,
            'data' => $events,
        ]);
    }

    public function recordEvent(Request $request, string $tracking_number): JsonResponse
    {
        $user = $request->user();

        if (!$user->canManageShipments()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized.'], 403);
        }

        $shipment = Shipment::where('tracking_number', strtoupper(trim($tracking_number)))->firstOrFail();

        $validated = $request->validate([
            'event_type' => ['required', 'string'],
            'new_status' => ['required', 'string'],
            'location_name' => ['required', 'string'],
            'description' => ['required', 'string'],
            'receiver_name' => ['nullable', 'string'],
            'proof_notes' => ['nullable', 'string'],
        ]);

        $updated = ShipmentLifecycleService::transition(
            $shipment,
            $validated['new_status'],
            [
                'event_type' => $validated['event_type'],
                'event_time' => now(),
                'location_name' => $validated['location_name'],
                'description' => $validated['description'],
                'receiver_name' => $validated['receiver_name'] ?? null,
                'proof_notes' => $validated['proof_notes'] ?? null,
                'is_customer_visible' => true,
            ]
        );

        return response()->json([
            'success' => true,
            'message' => 'Event recorded successfully.',
            'data' => $updated,
        ], 200);
    }

    public function exceptions(Request $request, string $tracking_number): JsonResponse
    {
        $shipment = Shipment::where('tracking_number', strtoupper(trim($tracking_number)))->firstOrFail();

        if (!Gate::forUser($request->user())->allows('view', $shipment)) {
            return response()->json(['success' => false, 'message' => 'Unauthorized.'], 403);
        }

        return response()->json([
            'success' => true,
            'data' => $shipment->deliveryExceptions,
        ]);
    }

    public function emissions(Request $request, string $tracking_number): JsonResponse
    {
        $shipment = Shipment::where('tracking_number', strtoupper(trim($tracking_number)))
            ->with(['transportLegs.emissionFactor'])
            ->firstOrFail();

        if (!Gate::forUser($request->user())->allows('view', $shipment)) {
            return response()->json(['success' => false, 'message' => 'Unauthorized.'], 403);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'tracking_number' => $shipment->tracking_number,
                'cargo_weight_tonnes' => $shipment->cargo_weight_tonnes,
                'total_distance_km' => $shipment->total_distance_km,
                'total_estimated_co2e_kg' => $shipment->total_estimated_co2e_kg,
                'methodology' => 'GLEC Framework v3.0 / IMO MEPC.308',
                'formula' => 'Estimated CO2e (kg) = Weight (t) * Distance (km) * Factor (g/t-km) / 1000',
                'legs' => $shipment->transportLegs->map(fn($leg) => [
                    'sequence' => $leg->sequence_number,
                    'mode' => $leg->transport_mode,
                    'carrier' => $leg->carrier_name,
                    'distance_km' => $leg->distance_km,
                    'factor_g_per_tonne_km' => $leg->emissionFactor?->factor_g_per_tonne_km ?? 0,
                    'leg_co2e_kg' => $leg->leg_estimated_co2e_kg,
                ]),
            ],
        ]);
    }

    public function serviceLevels(): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => ServiceLevel::where('is_active', true)->get(),
        ]);
    }

    public function locations(): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => Location::all(),
        ]);
    }

    public function factors(): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => CarbonEmissionFactor::all(),
        ]);
    }

    public function publicTrack(string $tracking_number): JsonResponse
    {
        $shipment = Shipment::where('tracking_number', strtoupper(trim($tracking_number)))
            ->with([
                'originLocation',
                'destinationLocation',
                'serviceLevel',
                'transportLegs.originLocation',
                'transportLegs.destinationLocation',
                'trackingEvents' => fn($q) => $q->where('is_customer_visible', true)->orderBy('event_time', 'asc'),
                'deliveryExceptions' => fn($q) => $q->where('is_customer_visible', true),
            ])
            ->first();

        if (!$shipment) {
            return response()->json(['success' => false, 'message' => 'Shipment not found.'], 404);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'tracking_number' => $shipment->tracking_number,
                'status' => $shipment->current_status,
                'service_level' => $shipment->serviceLevel->name,
                'destination_type' => $shipment->destination_type,
                'origin' => $shipment->originLocation->name,
                'destination' => $shipment->destinationLocation->name,
                'eta' => $shipment->eta,
                'total_distance_km' => $shipment->total_distance_km,
                'total_co2e_kg' => $shipment->total_estimated_co2e_kg,
                'legs' => $shipment->transportLegs,
                'timeline' => $shipment->trackingEvents,
                'exceptions' => $shipment->deliveryExceptions,
            ],
        ]);
    }
}

