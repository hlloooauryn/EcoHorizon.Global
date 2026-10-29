<?php

namespace App\Http\Controllers;

use App\Models\CarbonEmissionFactor;
use App\Models\Shipment;
use App\Models\TransportLeg;
use Illuminate\Support\Facades\DB;

class SustainabilityController extends Controller
{
    public function index()
    {
        $totalEmissionsKg = (float) Shipment::sum('total_estimated_co2e_kg');
        $totalDistanceKm = (float) Shipment::sum('total_distance_km');
        $totalCargoTonnes = (float) Shipment::sum('cargo_weight_tonnes');

        // Emissions by transport mode
        $modeEmissions = TransportLeg::select('transport_mode', DB::raw('sum(leg_estimated_co2e_kg) as total_co2e'), DB::raw('sum(distance_km) as total_km'), DB::raw('count(*) as leg_count'))
            ->groupBy('transport_mode')
            ->get()
            ->keyBy('transport_mode');

        $activeFactors = CarbonEmissionFactor::orderBy('transport_mode', 'asc')->get();

        $shipments = Shipment::with(['originLocation', 'destinationLocation', 'serviceLevel', 'transportLegs.emissionFactor'])
            ->orderBy('total_estimated_co2e_kg', 'desc')
            ->paginate(10);

        return view('sustainability.index', compact(
            'totalEmissionsKg',
            'totalDistanceKm',
            'totalCargoTonnes',
            'modeEmissions',
            'activeFactors',
            'shipments'
        ));
    }
}

