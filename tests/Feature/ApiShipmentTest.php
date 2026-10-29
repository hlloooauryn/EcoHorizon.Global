<?php

namespace Tests\Feature;

use App\Models\Location;
use App\Models\ServiceLevel;
use App\Models\Shipment;
use App\Models\User;
use App\Services\TrackingNumberGenerator;
use Database\Seeders\CarbonEmissionFactorSeeder;
use Database\Seeders\LocationSeeder;
use Database\Seeders\ServiceLevelSeeder;
use Database\Seeders\UserSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ApiShipmentTest extends TestCase
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

    public function test_public_api_tracking_endpoint()
    {
        $doorToDoor = ServiceLevel::where('code', 'DOOR_TO_DOOR')->first();
        $loc1 = Location::first();
        $loc2 = Location::skip(1)->first();

        $shipment = Shipment::create([
            'tracking_number' => 'EHG-TEST-PUBLIC-01',
            'service_level_id' => $doorToDoor->id,
            'destination_type' => 'door_address',
            'origin_location_id' => $loc1->id,
            'destination_location_id' => $loc2->id,
            'sender_business_name' => 'Public Shipper',
            'sender_contact_name' => 'Alice',
            'sender_phone' => '111',
            'sender_address' => 'Origin Address',
            'receiver_business_name' => 'Public Receiver',
            'receiver_contact_name' => 'Bob',
            'receiver_phone' => '222',
            'receiver_address' => 'Dest Address',
            'cargo_description' => 'Solar Equipment',
            'cargo_weight_tonnes' => 8.5,
            'current_status' => 'IN_TRANSIT',
        ]);

        $response = $this->getJson('/api/v1/track/' . $shipment->tracking_number);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'data' => [
                    'tracking_number' => 'EHG-TEST-PUBLIC-01',
                    'status' => 'IN_TRANSIT',
                ],
            ]);
    }

    public function test_api_sanctum_login_and_authenticated_shipment_creation()
    {
        // 1. API Login
        $loginResponse = $this->postJson('/api/v1/auth/login', [
            'email' => 'operator@ecohorizon.test',
            'password' => 'password123',
        ]);

        $loginResponse->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'data' => [
                    'user',
                    'token',
                    'token_type',
                ],
            ]);

        $token = $loginResponse->json('data.token');

        // 2. Dispatch Shipment via API with Bearer token
        $doorToDoor = ServiceLevel::where('code', 'DOOR_TO_DOOR')->first();
        $loc1 = Location::first();
        $loc2 = Location::skip(1)->first();

        $createResponse = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->postJson('/api/v1/shipments', [
                'service_level_id' => $doorToDoor->id,
                'destination_type' => 'door_address',
                'origin_location_id' => $loc1->id,
                'destination_location_id' => $loc2->id,
                'sender_business_name' => 'API Exporter Ltd',
                'sender_contact_name' => 'David',
                'sender_phone' => '999',
                'sender_address' => 'Jakarta Export Terminal',
                'receiver_business_name' => 'API Importer GmbH',
                'receiver_contact_name' => 'Elena',
                'receiver_phone' => '888',
                'receiver_address' => 'Hamburg Logistics Hub',
                'cargo_description' => 'Automotive Sensors',
                'cargo_weight_tonnes' => 4.5,
                'package_count' => 8,
                'package_type' => 'Euro Pallets',
                'initial_leg_mode' => 'air',
                'initial_carrier_name' => 'Lufthansa Cargo',
            ]);

        $createResponse->assertStatus(201)
            ->assertJson([
                'success' => true,
            ]);

        $this->assertDatabaseHas('shipments', [
            'cargo_description' => 'Automotive Sensors',
        ]);
    }

    public function test_customer_cannot_access_unauthorized_shipment_via_api()
    {
        $customer1 = User::where('email', 'customer@globalcargo.test')->first();
        $customer2 = User::where('email', 'buyer@bavariaindustries.de')->first();

        $doorToDoor = ServiceLevel::where('code', 'DOOR_TO_DOOR')->first();
        $loc1 = Location::first();
        $loc2 = Location::skip(1)->first();

        // Shipment belonging to Customer 2
        $shipmentCustomer2 = Shipment::create([
            'tracking_number' => 'EHG-CUST2-SECRET-01',
            'customer_user_id' => $customer2->id,
            'service_level_id' => $doorToDoor->id,
            'destination_type' => 'door_address',
            'origin_location_id' => $loc1->id,
            'destination_location_id' => $loc2->id,
            'sender_business_name' => 'Sender C2',
            'sender_contact_name' => 'Klaus',
            'sender_phone' => '111',
            'sender_address' => 'Germany',
            'receiver_business_name' => 'Receiver C2',
            'receiver_contact_name' => 'Hans',
            'receiver_phone' => '222',
            'receiver_address' => 'Munich',
            'cargo_description' => 'Proprietary Industrial Robotics',
            'cargo_weight_tonnes' => 10.0,
            'current_status' => 'IN_TRANSIT',
        ]);

        // Login as Customer 1
        $loginResponse = $this->postJson('/api/v1/auth/login', [
            'email' => 'customer@globalcargo.test',
            'password' => 'password123',
        ]);
        $token = $loginResponse->json('data.token');

        // Customer 1 tries to access Customer 2's shipment
        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->getJson('/api/v1/shipments/' . $shipmentCustomer2->tracking_number);

        $response->assertStatus(403);
    }
}
