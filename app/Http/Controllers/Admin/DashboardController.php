<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\DeliveryException;
use App\Models\Shipment;
use App\Models\TransportLeg;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $totalShipments = Shipment::count();
        $inTransit = Shipment::whereIn('current_status', ['IN_TRANSIT', 'PICKED_UP', 'OUT_FOR_DELIVERY'])->count();
        $delivered = Shipment::where('current_status', 'DELIVERED')->count();
        $delayed = Shipment::where('current_status', 'DELAYED')
            ->orWhere(function ($q) {
                $q->whereIn('current_status', ['IN_TRANSIT', 'PICKED_UP'])
                  ->whereNotNull('eta')
                  ->where('eta', '<', now());
            })->count();
        $openExceptions = DeliveryException::where('is_resolved', false)->count();

        $stats = [
            'total_shipments' => $totalShipments,
            'in_transit' => $inTransit,
            'delivered' => $delivered,
            'delayed' => $delayed,
            'critical_exceptions' => $openExceptions,
            'open_exceptions' => $openExceptions,
            'total_co2e_kg' => Shipment::sum('total_estimated_co2e_kg'),
            'total_distance_km' => Shipment::sum('total_distance_km'),
            'total_users' => User::count(),
        ];

        // Chart 1: Status Distribution
        $statusCounts = Shipment::select('current_status', DB::raw('count(*) as count'))
            ->groupBy('current_status')
            ->pluck('count', 'current_status')
            ->toArray();

        // Chart 2: Transport Mode Distribution
        $modeCounts = TransportLeg::select('transport_mode', DB::raw('count(*) as count'))
            ->groupBy('transport_mode')
            ->pluck('count', 'transport_mode')
            ->toArray();

        // Chart 3: Emissions by Transport Mode
        $modeEmissions = TransportLeg::select('transport_mode', DB::raw('sum(leg_estimated_co2e_kg) as total_co2e'))
            ->groupBy('transport_mode')
            ->pluck('total_co2e', 'transport_mode')
            ->toArray();

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

        // Recent System Activity
        $recentAudits = AuditLog::with('user')->orderBy('created_at', 'desc')->take(8)->get();

        // Active Exceptions
        $activeExceptions = DeliveryException::with(['shipment', 'assignedOperator', 'transportLeg'])
            ->where('is_resolved', false)
            ->orderByRaw("CASE severity WHEN 'critical' THEN 1 WHEN 'high' THEN 2 WHEN 'medium' THEN 3 ELSE 4 END")
            ->take(6)
            ->get();

        // Recent Shipments for table
        $recentShipments = Shipment::with(['originLocation', 'destinationLocation', 'serviceLevel', 'transportLegs', 'customer'])
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();

        return view('admin.dashboard', compact(
            'stats',
            'statusCounts',
            'modeCounts',
            'modeEmissions',
            'carrierStats',
            'mapShipments',
            'recentAudits',
            'activeExceptions',
            'recentShipments'
        ));
    }
}
