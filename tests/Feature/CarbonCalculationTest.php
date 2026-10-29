<?php

namespace Tests\Feature;

use App\Models\CarbonEmissionFactor;
use App\Models\Location;
use App\Models\ServiceLevel;
use App\Models\Shipment;
use App\Models\TransportLeg;
use App\Services\CarbonEmissionCalculatorService;
use App\Services\DistanceCalculationService;
use App\Services\TrackingNumberGenerator;
use Database\Seeders\CarbonEmissionFactorSeeder;
use Database\Seeders\LocationSeeder;
use Database\Seeders\ServiceLevelSeeder;
use Database\Seeders\UserSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CarbonCalculationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed([
            UserSeeder::class,
            ServiceLevelSeeder::class,
            LocationSeeder::class,
            CarbonEmissionFactorSeeder::class,
        ]);
    }

    public function test_haversine_distance_calculation()
    {
        // Jakarta Tanjung Priok (-6.1018, 106.8833) to Singapore (1.2644, 103.8228)
        $distance = DistanceCalculationService::calculate(-6.1018, 106.8833, 1.2644, 103.8228, 'sea');

        // Great circle straight-line is approx 890 km, with sea corridor multiplier (~1.35x) it's approx 1200 km
        $this->assertGreaterThan(800, $distance);
        $this->assertLessThan(1600, $distance);
    }

    public function test_carbon_emission_formula_accuracy()
    {
        $factorSea = CarbonEmissionFactor::where('transport_mode', 'sea')->where('is_default', true)->first();
        $this->assertNotNull($factorSea);

        $doorToDoor = ServiceLevel::where('code', 'DOOR_TO_DOOR')->first();
        $loc1 = Location::first();
        $loc2 = Location::skip(1)->first();

        // 10 tonnes of cargo over 1000 km
        $weight = 10.0;
        $distance = 1000.0;

        $shipment = Shipment::create([
            'tracking_number' => TrackingNumberGenerator::generate(),
            'service_level_id' => $doorToDoor->id,
            'destination_type' => 'door_address',
            'origin_location_id' => $loc1->id,
            'destination_location_id' => $loc2->id,
            'sender_business_name' => 'Shipper A',
            'sender_contact_name' => 'Alice',
            'sender_phone' => '111',
            'sender_address' => 'Origin',
            'receiver_business_name' => 'Receiver B',
            'receiver_contact_name' => 'Bob',
            'receiver_phone' => '222',
            'receiver_address' => 'Dest',
            'cargo_description' => 'Precision Machinery',
            'cargo_weight_tonnes' => $weight,
            'current_status' => 'IN_TRANSIT',
        ]);

        $leg = TransportLeg::create([
            'shipment_id' => $shipment->id,
            'sequence_number' => 1,
            'transport_mode' => 'sea',
            'origin_location_id' => $loc1->id,
            'destination_location_id' => $loc2->id,
            'carrier_name' => 'Ocean Lines',
            'distance_km' => $distance,
            'emission_factor_id' => $factorSea->id,
            'status' => 'IN_TRANSIT',
        ]);

        // Expected CO2e: 10 tonnes * 1000 km * factor (g/t-km) / 1000 = 10 * factor
        $expectedLegCo2e = round(($weight * $distance * $factorSea->factor_g_per_tonne_km) / 1000, 2);

        $calculated = CarbonEmissionCalculatorService::calculateLegEmission($leg, $weight);
        $this->assertEquals($expectedLegCo2e, $calculated);

        // Recalculate whole shipment
        CarbonEmissionCalculatorService::recalculateShipmentEmissions($shipment);
        $shipment->refresh();

        $this->assertEquals($distance, $shipment->total_distance_km);
        $this->assertEquals($expectedLegCo2e, $shipment->total_estimated_co2e_kg);
    }
}

