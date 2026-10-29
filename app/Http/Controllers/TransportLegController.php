<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Models\Location;
use App\Models\Shipment;
use App\Models\TransportLeg;
use App\Services\CarbonEmissionCalculatorService;
use App\Services\DistanceCalculationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class TransportLegController extends Controller
{
    public function store(Request $request, Shipment $shipment)
    {
        Gate::authorize('update', $shipment);

        $validated = $request->validate([
            'sequence_number' => ['required', 'integer', 'min:1'],
            'transport_mode' => ['required', 'string', 'in:sea,land,air'],
            'origin_location_id' => ['required', 'exists:locations,id'],
            'destination_location_id' => ['required', 'exists:locations,id', 'different:origin_location_id'],
            'carrier_name' => ['required', 'string', 'max:255'],
            'transport_reference' => ['nullable', 'string', 'max:255'],
            'planned_departure' => ['nullable', 'date'],
            'estimated_arrival' => ['nullable', 'date'],
            'distance_km' => ['nullable', 'numeric', 'min:0'],
            'emission_factor_id' => ['nullable', 'exists:carbon_emission_factors,id'],
            'notes' => ['nullable', 'string'],
        ]);

        $originLoc = Location::find($validated['origin_location_id']);
        $destLoc = Location::find($validated['destination_location_id']);

        $distanceKm = (float) ($validated['distance_km'] ?? 0);
        $distanceSource = 'manual_operator_override';

        if ($distanceKm <= 0 && $originLoc && $destLoc) {
            $distanceKm = DistanceCalculationService::calculate(
                $originLoc->latitude,
                $originLoc->longitude,
                $destLoc->latitude,
                $destLoc->longitude,
                $validated['transport_mode']
            );
            $distanceSource = 'calculated_haversine';
        }

        $leg = TransportLeg::create([
            'shipment_id' => $shipment->id,
            'sequence_number' => $validated['sequence_number'],
            'transport_mode' => $validated['transport_mode'],
            'origin_location_id' => $validated['origin_location_id'],
            'destination_location_id' => $validated['destination_location_id'],
            'carrier_name' => $validated['carrier_name'],
            'transport_reference' => $validated['transport_reference'] ?? null,
            'planned_departure' => $validated['planned_departure'] ?? null,
            'estimated_arrival' => $validated['estimated_arrival'] ?? null,
            'distance_km' => $distanceKm,
            'distance_source' => $distanceSource,
            'emission_factor_id' => $validated['emission_factor_id'] ?? null,
            'status' => 'PENDING',
            'notes' => $validated['notes'] ?? null,
        ]);

        CarbonEmissionCalculatorService::recalculateShipmentEmissions($shipment);

        AuditLog::log(
            'LEG_UPDATE',
            $shipment,
            "Added Transport Leg {$leg->sequence_number} ({$leg->transport_mode}) via {$leg->carrier_name} to shipment {$shipment->tracking_number}.",
            null,
            $leg->toArray()
        );

        return back()->with('success', "Transport Leg {$leg->sequence_number} successfully added to journey.");
    }

    public function update(Request $request, TransportLeg $leg)
    {
        Gate::authorize('update', $leg->shipment);

        $validated = $request->validate([
            'status' => ['required', 'string', 'in:PENDING,IN_TRANSIT,COMPLETED,CANCELLED'],
            'actual_departure' => ['nullable', 'date'],
            'actual_arrival' => ['nullable', 'date'],
            'distance_km' => ['nullable', 'numeric', 'min:0'],
            'notes' => ['nullable', 'string'],
        ]);

        $old = $leg->toArray();
        $leg->update($validated);

        CarbonEmissionCalculatorService::recalculateShipmentEmissions($leg->shipment);

        AuditLog::log(
            'LEG_UPDATE',
            $leg->shipment,
            "Updated Transport Leg {$leg->sequence_number} status to {$leg->status}.",
            $old,
            $leg->toArray()
        );

        return back()->with('success', "Transport Leg {$leg->sequence_number} updated.");
    }
}
