<?php

namespace App\Services;

use App\Models\AuditLog;
use App\Models\Shipment;
use App\Models\TrackingEvent;
use Illuminate\Validation\ValidationException;

class ShipmentLifecycleService
{
    /**
     * Map of allowed status transitions.
     * Note: DELAYED and DELIVERY_EXCEPTION can be entered from any active non-terminal state
     * and resumed back to IN_TRANSIT, CUSTOMS_CLEARANCE, etc.
     */
    protected static array $allowedTransitions = [
        'CREATED' => ['BOOKED', 'PICKED_UP', 'CANCELLED'],
        'BOOKED' => ['PICKED_UP', 'IN_TRANSIT', 'CANCELLED'],
        'PICKED_UP' => ['IN_TRANSIT', 'ARRIVED_AT_TRANSIT_HUB', 'DELAYED', 'DELIVERY_EXCEPTION', 'CANCELLED'],
        'IN_TRANSIT' => ['ARRIVED_AT_TRANSIT_HUB', 'ARRIVED_AT_DESTINATION_TERMINAL', 'CUSTOMS_CLEARANCE', 'OUT_FOR_DELIVERY', 'DELAYED', 'DELIVERY_EXCEPTION', 'CANCELLED'],
        'ARRIVED_AT_TRANSIT_HUB' => ['IN_TRANSIT', 'CUSTOMS_CLEARANCE', 'DELAYED', 'DELIVERY_EXCEPTION', 'CANCELLED'],
        'CUSTOMS_CLEARANCE' => ['IN_TRANSIT', 'ARRIVED_AT_DESTINATION_TERMINAL', 'AWAITING_CUSTOMER_PICKUP', 'OUT_FOR_DELIVERY', 'DELAYED', 'DELIVERY_EXCEPTION', 'CANCELLED'],
        'ARRIVED_AT_DESTINATION_TERMINAL' => ['CUSTOMS_CLEARANCE', 'AWAITING_CUSTOMER_PICKUP', 'OUT_FOR_DELIVERY', 'DELAYED', 'DELIVERY_EXCEPTION', 'CANCELLED'],
        'AWAITING_CUSTOMER_PICKUP' => ['DELIVERED', 'DELAYED', 'DELIVERY_EXCEPTION', 'CANCELLED'],
        'OUT_FOR_DELIVERY' => ['DELIVERED', 'DELAYED', 'DELIVERY_EXCEPTION', 'CANCELLED'],
        'DELAYED' => ['IN_TRANSIT', 'ARRIVED_AT_TRANSIT_HUB', 'ARRIVED_AT_DESTINATION_TERMINAL', 'AWAITING_CUSTOMER_PICKUP', 'OUT_FOR_DELIVERY', 'DELIVERED', 'DELIVERY_EXCEPTION', 'CANCELLED'],
        'DELIVERY_EXCEPTION' => ['IN_TRANSIT', 'ARRIVED_AT_TRANSIT_HUB', 'ARRIVED_AT_DESTINATION_TERMINAL', 'AWAITING_CUSTOMER_PICKUP', 'OUT_FOR_DELIVERY', 'DELIVERED', 'DELAYED', 'CANCELLED'],
        'DELIVERED' => [], // Final terminal state
        'CANCELLED' => [], // Final terminal state
    ];

    /**
     * Validate whether a status transition is permitted.
     */
    public static function canTransition(Shipment $shipment, string $newStatus): bool
    {
        $current = $shipment->current_status;

        if ($current === $newStatus) {
            return true;
        }

        // Terminal states cannot transition further
        if (in_array($current, ['DELIVERED', 'CANCELLED'], true)) {
            return false;
        }

        $allowed = self::$allowedTransitions[$current] ?? [];

        return in_array($newStatus, $allowed, true);
    }

    /**
     * Perform validated state transition with delivery condition checks.
     */
    public static function transition(
        Shipment $shipment,
        string $newStatus,
        array $eventData = []
    ): Shipment {
        if (!self::canTransition($shipment, $newStatus)) {
            throw ValidationException::withMessages([
                'status' => "Invalid shipment transition from {$shipment->current_status} to {$newStatus}.",
            ]);
        }

        // Business Rule: Delivery conditions validation
        if ($newStatus === 'DELIVERED') {
            self::validateDeliveryConditions($shipment, $eventData);
        }

        // Business Rule: Terminal arrival distinction
        if (in_array($newStatus, ['ARRIVED_AT_TRANSIT_HUB', 'ARRIVED_AT_DESTINATION_TERMINAL'], true)) {
            // Cannot mark as DELIVERED upon terminal arrival
            if ($newStatus === 'ARRIVED_AT_DESTINATION_TERMINAL') {
                $serviceCode = $shipment->serviceLevel?->code;
                if (in_array($serviceCode, ['DOOR_TO_PORT', 'PORT_TO_PORT'], true)) {
                    // For Door-to-Port / Port-to-Port, cargo is at terminal, awaiting collection/handover
                    $newStatus = 'AWAITING_CUSTOMER_PICKUP';
                }
            }
        }

        $oldStatus = $shipment->current_status;
        $shipment->current_status = $newStatus;

        if ($newStatus === 'DELIVERED') {
            $shipment->actual_delivery_date = now();
            if (!empty($eventData['receiver_name'])) {
                $shipment->proof_of_delivery_receiver_name = $eventData['receiver_name'];
            }
            if (!empty($eventData['proof_notes'])) {
                $shipment->proof_of_delivery_notes = $eventData['proof_notes'];
            }
            $shipment->proof_of_delivery_at = now();
        }

        $shipment->save();

        // Create Tracking Event
        $event = TrackingEvent::create([
            'shipment_id' => $shipment->id,
            'transport_leg_id' => $eventData['transport_leg_id'] ?? null,
            'event_type' => $eventData['event_type'] ?? $newStatus,
            'status_after_event' => $newStatus,
            'event_time' => $eventData['event_time'] ?? now(),
            'location_name' => $eventData['location_name'] ?? ($shipment->destinationLocation->name ?? 'Hub/Facility'),
            'latitude' => $eventData['latitude'] ?? null,
            'longitude' => $eventData['longitude'] ?? null,
            'description' => $eventData['description'] ?? "Shipment status updated to {$newStatus}.",
            'recorded_by_user_id' => auth()->id(),
            'is_customer_visible' => $eventData['is_customer_visible'] ?? true,
            'proof_notes' => $eventData['proof_notes'] ?? null,
        ]);

        // Create Audit Log
        AuditLog::log(
            'STATUS_CHANGE',
            $shipment,
            "Shipment {$shipment->tracking_number} changed from {$oldStatus} to {$newStatus}.",
            ['status' => $oldStatus],
            ['status' => $newStatus, 'tracking_event_id' => $event->id]
        );

        return $shipment;
    }

    /**
     * Validate delivery requirements for specific service levels & destination types.
     */
    protected static function validateDeliveryConditions(Shipment $shipment, array $eventData): void
    {
        $serviceCode = $shipment->serviceLevel?->code;
        $destType = $shipment->destination_type;

        // 1. Port/Terminal destination check
        if (in_array($destType, ['seaport_terminal', 'airport_terminal'], true)) {
            // Must have handover/collection confirmation
            if (empty($eventData['receiver_name']) && empty($eventData['proof_notes'])) {
                throw ValidationException::withMessages([
                    'delivery_confirmation' => 'Terminal/Port delivery requires collection or handover confirmation notes or authorized consignee name.',
                ]);
            }
        }

        // 2. B2B Warehouse delivery check
        if ($serviceCode === 'B2B' || in_array($destType, ['business_warehouse', 'fulfillment_center'], true)) {
            if (empty($eventData['receiver_name'])) {
                throw ValidationException::withMessages([
                    'receiver_name' => 'B2B warehouse receiving requires the name of the warehouse receiving officer or dock manager.',
                ]);
            }
        }

        // 3. Door-to-Door check
        if ($serviceCode === 'DOOR_TO_DOOR') {
            if (empty($eventData['receiver_name']) && empty($shipment->proof_of_delivery_receiver_name)) {
                throw ValidationException::withMessages([
                    'receiver_name' => 'Door-to-door delivery confirmation requires the recipient or authorized agent name.',
                ]);
            }
        }
    }
}

