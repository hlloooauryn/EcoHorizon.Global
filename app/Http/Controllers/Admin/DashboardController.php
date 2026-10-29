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
        $stats = [
            'total_shipments' => Shipment::count(),
            'in_transit' => Shipment::whereIn('current_status', ['IN_TRANSIT', 'PICKED_UP', 'OUT_FOR_DELIVERY'])->count(),
            'delivered' => Shipment::where('current_status', 'DELIVERED')->count(),
            'delayed' => Shipment::where('current_status', 'DELAYED')->count(),
            'critical_exceptions' => DeliveryException::where('severity', 'critical')->where('is_resolved', false)->count(),
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

        // Recent System Activity
        $recentAudits = AuditLog::with('user')->orderBy('created_at', 'desc')->take(8)->get();

        // Active Exceptions
        $activeExceptions = DeliveryException::with(['shipment', 'assignedOperator', 'transportLeg'])
            ->where('is_resolved', false)
            ->orderByRaw("CASE severity WHEN 'critical' THEN 1 WHEN 'high' THEN 2 WHEN 'medium' THEN 3 ELSE 4 END")
            ->take(6)
            ->get();

        // Recent Shipments
        $recentShipments = Shipment::with(['originLocation', 'destinationLocation', 'serviceLevel'])
            ->orderBy('created_at', 'desc')
            ->take(6)
            ->get();

        return view('admin.dashboard', compact(
            'stats',
            'statusCounts',
            'modeCounts',
            'modeEmissions',
            'recentAudits',
            'activeExceptions',
            'recentShipments'
        ));
    }
}
