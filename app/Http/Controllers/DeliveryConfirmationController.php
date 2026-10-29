<?php

namespace App\Http\Controllers;

use App\Models\Shipment;
use App\Services\ShipmentLifecycleService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class DeliveryConfirmationController extends Controller
{
    public function confirm(Request $request, Shipment $shipment)
    {
        Gate::authorize('update', $shipment);

        $validated = $request->validate([
            'receiver_name' => ['required', 'string', 'max:255'],
            'proof_notes' => ['required', 'string'],
            'location_name' => ['nullable', 'string', 'max:255'],
        ]);

        $eventData = [
            'event_type' => 'DELIVERY_CONFIRMATION',
            'event_time' => now(),
            'location_name' => $validated['location_name'] ?? $shipment->destinationLocation->name,
            'description' => "Shipment verified and delivered to {$validated['receiver_name']}. Proof notes: {$validated['proof_notes']}",
            'receiver_name' => $validated['receiver_name'],
            'proof_notes' => $validated['proof_notes'],
            'is_customer_visible' => true,
        ];

        ShipmentLifecycleService::transition($shipment, 'DELIVERED', $eventData);

        // Mark remaining pending/in-transit legs as completed
        foreach ($shipment->transportLegs as $leg) {
            if ($leg->status !== 'COMPLETED') {
                $leg->status = 'COMPLETED';
                $leg->actual_arrival = now();
                $leg->save();
            }
        }

        return back()->with('success', "Final delivery confirmed and verified for shipment {$shipment->tracking_number}.");
    }
}
