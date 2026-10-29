<?php

namespace App\Http\Controllers;

use App\Models\CarbonEmissionFactor;
use App\Models\Location;
use App\Models\ServiceLevel;
use App\Models\Shipment;
use App\Models\TransportLeg;

class HomeController extends Controller
{
    public function index()
    {
        $stats = [
            'total_shipments' => Shipment::count(),
            'total_distance_km' => Shipment::sum('total_distance_km'),
            'total_co2e_kg' => Shipment::sum('total_estimated_co2e_kg'),
            'active_legs' => TransportLeg::whereIn('status', ['IN_TRANSIT', 'PENDING'])->count(),
        ];

        $demoShipments = Shipment::with(['originLocation', 'destinationLocation', 'serviceLevel', 'transportLegs'])
            ->orderBy('created_at', 'desc')
            ->take(4)
            ->get();

        $serviceLevels = ServiceLevel::where('is_active', true)->get();
        $emissionFactors = CarbonEmissionFactor::where('is_default', true)->get();
        $majorHubs = Location::whereIn('type', ['seaport', 'airport'])->take(8)->get();

        return view('welcome', compact('stats', 'demoShipments', 'serviceLevels', 'emissionFactors', 'majorHubs'));
    }
}
