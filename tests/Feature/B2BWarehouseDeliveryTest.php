<?php

namespace Tests\Feature;

use App\Models\Location;
use App\Models\ServiceLevel;
use App\Models\Shipment;
use App\Models\TransportLeg;
use App\Models\User;
use App\Services\TrackingNumberGenerator;
use Database\Seeders\CarbonEmissionFactorSeeder;
use Database\Seeders\LocationSeeder;
use Database\Seeders\ServiceLevelSeeder;
use Database\Seeders\UserSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class B2BWarehouseDeliveryTest extends TestCase
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

    public function test_b2b_warehouse_delivery_requires_receiving_officer_confirmation()
    {
        $operator = User::where('role', 'operator')->first();
        $this->actingAs($operator);

        $b2bLevel = ServiceLevel::where('code', 'B2B')->first();
        $loc1 = Location::first();
        $loc2 = Location::skip(1)->first();

        $shipment = Shipment::create([
            'tracking_number' => TrackingNumberGenerator::generate(),
            'service_level_id' => $b2bLevel->id,
            'destination_type' => 'business_warehouse',
            'origin_location_id' => $loc1->id,
            'destination_location_id' => $loc2->id,
            'sender_business_name' => 'Supplier Co.',
            'sender_contact_name' => 'John',
            'sender_phone' => '111',
            'sender_address' => 'Factory Bay 1',
            'receiver_business_name' => 'Enterprise Buyer Warehouse',
            'receiver_contact_name' => 'Warehouse Supervisor',
            'receiver_phone' => '222',
            'receiver_address' => 'DC Logistics Dock 4',
            'cargo_description' => 'FMCG Inventory',
            'cargo_weight_tonnes' => 12.0,
            'current_status' => 'OUT_FOR_DELIVERY',
        ]);

        TransportLeg::create([
            'shipment_id' => $shipment->id,
            'sequence_number' => 1,
            'transport_mode' => 'land',
            'origin_location_id' => $loc1->id,
            'destination_location_id' => $loc2->id,
            'carrier_name' => 'Fleet Trucking',
            'status' => 'IN_TRANSIT',
        ]);

        // Attempt delivery confirmation without receiver name
        $response = $this->post(route('delivery.confirm', $shipment->id), [
            'receiver_name' => '',
            'proof_notes' => 'Some notes',
        ]);
        $response->assertSessionHasErrors('receiver_name');

        // Confirm delivery with valid receiving officer and dock note
        $validResponse = $this->post(route('delivery.confirm', $shipment->id), [
            'receiver_name' => 'Hendra Gunawan (Dock Manager)',
            'proof_notes' => 'Received 24 pallets in good order. Signed GRN #GRN-9821.',
        ]);

        $validResponse->assertRedirect();
        $shipment->refresh();

        $this->assertEquals('DELIVERED', $shipment->current_status);
        $this->assertEquals('Hendra Gunawan (Dock Manager)', $shipment->proof_of_delivery_receiver_name);
        $this->assertNotNull($shipment->actual_delivery_date);
        $this->assertEquals('COMPLETED', $shipment->transportLegs->first()->status);
    }
}
