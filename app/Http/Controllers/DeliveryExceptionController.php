<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Models\DeliveryException;
use App\Models\Shipment;
use App\Models\TrackingEvent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class DeliveryExceptionController extends Controller
{
    public function store(Request $request, Shipment $shipment)
    {
        Gate::authorize('manageExceptions', $shipment);

        $validated = $request->validate([
            'category' => ['required', 'string', 'in:weather_disruption,transport_delay,damaged_cargo,missing_documentation,customs_clearance_issue,failed_delivery_attempt,other_operational_issue'],
            'severity' => ['required', 'string', 'in:low,medium,high,critical'],
            'description' => ['required', 'string'],
            'action_taken' => ['nullable', 'string'],
            'assigned_operator_id' => ['nullable', 'exists:users,id'],
            'impact_on_eta_hours' => ['nullable', 'integer', 'min:0'],
            'is_customer_visible' => ['nullable', 'boolean'],
            'transport_leg_id' => ['nullable', 'exists:transport_legs,id'],
        ]);

        $exception = DeliveryException::create([
            'shipment_id' => $shipment->id,
            'transport_leg_id' => $validated['transport_leg_id'] ?? null,
            'category' => $validated['category'],
            'severity' => $validated['severity'],
            'description' => $validated['description'],
            'action_taken' => $validated['action_taken'] ?? null,
            'assigned_operator_id' => $validated['assigned_operator_id'] ?? auth()->id(),
            'impact_on_eta_hours' => $validated['impact_on_eta_hours'] ?? 0,
            'is_customer_visible' => $request->boolean('is_customer_visible', true),
            'is_resolved' => false,
        ]);

        // If high or critical, update shipment status
        $impactHours = (int) ($validated['impact_on_eta_hours'] ?? 0);
        if ($impactHours > 0 && $shipment->eta) {
            $shipment->eta = $shipment->eta->copy()->addHours($impactHours);
        }

        $oldStatus = $shipment->current_status;
        $newStatus = ($validated['category'] === 'transport_delay' || $impactHours > 0) ? 'DELAYED' : 'DELIVERY_EXCEPTION';

        if (!in_array($shipment->current_status, ['DELIVERED', 'CANCELLED'], true)) {
            $shipment->current_status = $newStatus;
        }

        $shipment->save();

        // Record tracking event
        TrackingEvent::create([
            'shipment_id' => $shipment->id,
            'transport_leg_id' => $validated['transport_leg_id'] ?? null,
            'event_type' => 'EXCEPTION_LOGGED',
            'status_after_event' => $newStatus,
            'event_time' => now(),
            'location_name' => $shipment->originLocation->name,
            'description' => "[{$validated['severity']}] Exception: {$validated['description']}",
            'recorded_by_user_id' => auth()->id(),
            'is_customer_visible' => $request->boolean('is_customer_visible', true),
        ]);

        AuditLog::log(
            'EXCEPTION_LOGGED',
            $shipment,
            "Logged {$validated['severity']} exception ({$validated['category']}) on shipment {$shipment->tracking_number}.",
            ['status' => $oldStatus],
            ['status' => $newStatus, 'exception_id' => $exception->id]
        );

        return back()->with('success', "Delivery exception recorded. Status updated to {$newStatus}.");
    }

    public function resolve(Request $request, DeliveryException $exception)
    {
        Gate::authorize('manageExceptions', $exception->shipment);

        $validated = $request->validate([
            'resolution_notes' => ['required', 'string'],
            'resume_status' => ['required', 'string', 'in:IN_TRANSIT,CUSTOMS_CLEARANCE,AWAITING_CUSTOMER_PICKUP,OUT_FOR_DELIVERY'],
        ]);

        $exception->update([
            'is_resolved' => true,
            'resolved_at' => now(),
            'resolution_notes' => $validated['resolution_notes'],
        ]);

        $shipment = $exception->shipment;
        $oldStatus = $shipment->current_status;

        // If no more active exceptions, resume status
        if ($shipment->activeExceptions()->count() === 0) {
            $shipment->current_status = $validated['resume_status'];
            $shipment->save();

            TrackingEvent::create([
                'shipment_id' => $shipment->id,
                'transport_leg_id' => $exception->transport_leg_id,
                'event_type' => 'EXCEPTION_RESOLVED',
                'status_after_event' => $validated['resume_status'],
                'event_time' => now(),
                'location_name' => 'Operations Hub',
                'description' => "Exception resolved: {$validated['resolution_notes']}. Operations resumed.",
                'recorded_by_user_id' => auth()->id(),
                'is_customer_visible' => true,
            ]);
        }

        AuditLog::log(
            'EXCEPTION_RESOLVED',
            $shipment,
            "Resolved exception #{$exception->id} on shipment {$shipment->tracking_number}. Notes: {$validated['resolution_notes']}",
            ['status' => $oldStatus],
            ['status' => $shipment->current_status, 'resolved_notes' => $validated['resolution_notes']]
        );

        return back()->with('success', "Exception resolved successfully.");
    }
}
