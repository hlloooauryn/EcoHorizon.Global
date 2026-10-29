<?php

namespace App\Http\Controllers\Operator;

use App\Http\Controllers\Controller;
use App\Models\DeliveryException;
use App\Models\Shipment;
use App\Models\TrackingEvent;

class DashboardController extends Controller
{
    public function index()
    {
        // 1. Actionable Queues
        $upcomingArrivals = Shipment::with(['originLocation', 'destinationLocation', 'serviceLevel'])
            ->whereIn('current_status', ['IN_TRANSIT', 'OUT_FOR_DELIVERY'])
            ->whereNotNull('eta')
            ->where('eta', '<=', now()->addHours(48))
            ->orderBy('eta', 'asc')
            ->get();

        $delayedShipments = Shipment::with(['originLocation', 'destinationLocation', 'serviceLevel'])
            ->where('current_status', 'DELAYED')
            ->orWhere(function ($q) {
                $q->whereIn('current_status', ['IN_TRANSIT', 'PICKED_UP'])
                  ->whereNotNull('eta')
                  ->where('eta', '<', now());
            })
            ->get();

        $awaitingPickup = Shipment::with(['originLocation', 'destinationLocation', 'serviceLevel'])
            ->whereIn('current_status', ['AWAITING_CUSTOMER_PICKUP', 'ARRIVED_AT_DESTINATION_TERMINAL'])
            ->get();

        $activeExceptions = DeliveryException::with(['shipment', 'transportLeg'])
            ->where('is_resolved', false)
            ->orderByRaw("CASE severity WHEN 'critical' THEN 1 WHEN 'high' THEN 2 WHEN 'medium' THEN 3 ELSE 4 END")
            ->take(8)
            ->get();

        $recentEvents = TrackingEvent::with(['shipment', 'recordedBy'])
            ->orderBy('event_time', 'desc')
            ->take(10)
            ->get();

        $activeShipments = Shipment::with(['originLocation', 'destinationLocation', 'serviceLevel'])
            ->whereNotIn('current_status', ['DELIVERED', 'CANCELLED'])
            ->orderBy('updated_at', 'desc')
            ->paginate(10);

        return view('operator.dashboard', compact(
            'upcomingArrivals',
            'delayedShipments',
            'awaitingPickup',
            'activeExceptions',
            'recentEvents',
            'activeShipments'
        ));
    }
}
