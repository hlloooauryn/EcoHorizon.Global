<?php

namespace App\Http\Controllers;

use App\Models\Shipment;
use App\Services\ShipmentLifecycleService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class TrackingEventController extends Controller
{
    public function store(Request $request, Shipment $shipment)
    {
        Gate::authorize('recordEvent', $shipment);

        $validated = $request->validate([
            'event_type' => ['required', 'string'],
            'new_status' => ['required', 'string'],
            'transport_leg_id' => ['nullable', 'exists:transport_legs,id'],
            'event_time' => ['required', 'date'],
            'location_name' => ['required', 'string', 'max:255'],
            'latitude' => ['nullable', 'numeric'],
            'longitude' => ['nullable', 'numeric'],
            'description' => ['required', 'string'],
            'is_customer_visible' => ['nullable', 'boolean'],
            'proof_notes' => ['nullable', 'string'],
            'receiver_name' => ['nullable', 'string', 'max:255'],
        ]);

        $eventData = [
            'transport_leg_id' => $validated['transport_leg_id'] ?? null,
            'event_type' => $validated['event_type'],
            'event_time' => $validated['event_time'],
            'location_name' => $validated['location_name'],
            'latitude' => $validated['latitude'] ?? null,
            'longitude' => $validated['longitude'] ?? null,
            'description' => $validated['description'],
            'is_customer_visible' => $request->boolean('is_customer_visible', true),
            'proof_notes' => $validated['proof_notes'] ?? null,
            'receiver_name' => $validated['receiver_name'] ?? null,
        ];

        ShipmentLifecycleService::transition(
            $shipment,
            $validated['new_status'],
            $eventData
        );

        return back()->with('success', "Tracking event recorded. Status updated to {$validated['new_status']}.");
    }
}
