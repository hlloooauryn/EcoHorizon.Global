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

        $totalShipments = Shipment::count();
        $inTransit = Shipment::whereIn('current_status', ['IN_TRANSIT', 'PICKED_UP', 'OUT_FOR_DELIVERY'])->count();
        $delivered = Shipment::where('current_status', 'DELIVERED')->count();
        $delayedCount = $delayedShipments->count();
        $openExceptions = $activeExceptions->count();

        $stats = [
            'total_shipments' => $totalShipments,
            'in_transit' => $inTransit,
            'delivered' => $delivered,
            'delayed' => $delayedCount,
            'critical_exceptions' => $openExceptions,
            'open_exceptions' => $openExceptions,
        ];

        // Carrier Delay Rates
        $carrierStats = [
            ['name' => 'FastShip / Maersk', 'rate' => 22, 'color' => '#0284C7'],
            ['name' => 'CargoPro / DHL', 'rate' => 36, 'color' => '#14B8A6'],
            ['name' => 'Logistics Partners', 'rate' => 42, 'color' => '#F59E0B'],
        ];

        // Map Routes Data
        $mapShipments = Shipment::with(['originLocation', 'destinationLocation', 'transportLegs.originLocation', 'transportLegs.destinationLocation'])
            ->whereNotIn('current_status', ['CANCELLED'])
            ->get()
            ->map(function ($s) {
                return [
                    'tracking_number' => $s->tracking_number,
                    'status' => $s->current_status,
                    'origin' => [
                        'name' => $s->originLocation->name,
                        'city' => $s->originLocation->city,
                        'lat' => (float) $s->originLocation->latitude,
                        'lon' => (float) $s->originLocation->longitude,
                    ],
                    'destination' => [
                        'name' => $s->destinationLocation->name,
                        'city' => $s->destinationLocation->city,
                        'lat' => (float) $s->destinationLocation->latitude,
                        'lon' => (float) $s->destinationLocation->longitude,
                    ],
                    'carrier' => $s->transportLegs->first()?->carrier_name ?? 'EcoHorizon Logistics',
                    'legs' => $s->transportLegs->map(function ($leg) {
                        return [
                            'mode' => $leg->transport_mode,
                            'carrier' => $leg->carrier_name,
                            'origin' => [
                                'name' => $leg->originLocation->name,
                                'city' => $leg->originLocation->city,
                                'lat' => (float) $leg->originLocation->latitude,
                                'lon' => (float) $leg->originLocation->longitude,
                            ],
                            'destination' => [
                                'name' => $leg->destinationLocation->name,
                                'city' => $leg->destinationLocation->city,
                                'lat' => (float) $leg->destinationLocation->latitude,
                                'lon' => (float) $leg->destinationLocation->longitude,
                            ],
                        ];
                    }),
                ];
            });

        $recentShipments = Shipment::with(['originLocation', 'destinationLocation', 'serviceLevel', 'transportLegs', 'customerUser'])
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();

        return view('operator.dashboard', compact(
            'upcomingArrivals',
            'delayedShipments',
            'awaitingPickup',
            'activeExceptions',
            'recentEvents',
            'activeShipments',
            'stats',
            'carrierStats',
            'mapShipments',
            'recentShipments'
        ));
    }
}
