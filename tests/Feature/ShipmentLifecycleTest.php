<?php

namespace Tests\Feature;

use App\Models\Location;
use App\Models\ServiceLevel;
use App\Models\Shipment;
use App\Models\User;
use App\Services\ShipmentLifecycleService;
use App\Services\TrackingNumberGenerator;
use Database\Seeders\CarbonEmissionFactorSeeder;
use Database\Seeders\LocationSeeder;
use Database\Seeders\ServiceLevelSeeder;
use Database\Seeders\UserSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class ShipmentLifecycleTest extends TestCase
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

    public function test_valid_shipment_status_transitions_succeed()
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
            'sender_business_name' => 'Sender Test',
            'sender_contact_name' => 'Alice',
            'sender_phone' => '123',
            'sender_address' => 'Origin Address',
            'receiver_business_name' => 'Receiver Test',
            'receiver_contact_name' => 'Bob',
            'receiver_phone' => '456',
            'receiver_address' => 'Dest Address',
            'cargo_description' => 'Electronic Parts',
            'cargo_weight_tonnes' => 5.0,
            'package_count' => 10,
            'package_type' => 'Boxes',
            'current_status' => 'CREATED',
        ]);

        // Transition 1: CREATED -> BOOKED
        $shipment = ShipmentLifecycleService::transition($shipment, 'BOOKED', [
            'event_type' => 'BOOKED',
            'description' => 'Booking placed',
        ]);
        $this->assertEquals('BOOKED', $shipment->current_status);

        // Transition 2: BOOKED -> PICKED_UP
        $shipment = ShipmentLifecycleService::transition($shipment, 'PICKED_UP', [
            'event_type' => 'PICKUP',
            'description' => 'Cargo picked up from shipper dock',
        ]);
        $this->assertEquals('PICKED_UP', $shipment->current_status);

        // Transition 3: PICKED_UP -> IN_TRANSIT
        $shipment = ShipmentLifecycleService::transition($shipment, 'IN_TRANSIT', [
            'event_type' => 'DEPARTURE',
            'description' => 'Departed origin hub',
        ]);
        $this->assertEquals('IN_TRANSIT', $shipment->current_status);

        $this->assertDatabaseHas('tracking_events', [
            'shipment_id' => $shipment->id,
            'event_type' => 'DEPARTURE',
        ]);
    }

    public function test_invalid_status_transition_is_rejected_with_exception()
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
            'sender_business_name' => 'Sender Test',
            'sender_contact_name' => 'Alice',
            'sender_phone' => '123',
            'sender_address' => 'Origin Address',
            'receiver_business_name' => 'Receiver Test',
            'receiver_contact_name' => 'Bob',
            'receiver_phone' => '456',
            'receiver_address' => 'Dest Address',
            'cargo_description' => 'Electronic Parts',
            'cargo_weight_tonnes' => 5.0,
            'current_status' => 'CREATED',
        ]);

        // Cannot skip directly from CREATED to DELIVERED
        $this->expectException(ValidationException::class);
        ShipmentLifecycleService::transition($shipment, 'DELIVERED', []);
    }

    public function test_terminal_arrival_for_port_to_port_transitions_to_awaiting_pickup_not_delivered()
    {
        $operator = User::where('role', 'operator')->first();
        $this->actingAs($operator);

        $portToPort = ServiceLevel::where('code', 'PORT_TO_PORT')->first();
        $loc1 = Location::first();
        $loc2 = Location::skip(1)->first();

        $shipment = Shipment::create([
            'tracking_number' => TrackingNumberGenerator::generate(),
            'service_level_id' => $portToPort->id,
            'destination_type' => 'seaport_terminal',
            'origin_location_id' => $loc1->id,
            'destination_location_id' => $loc2->id,
            'sender_business_name' => 'Port Shipper',
            'sender_contact_name' => 'Alice',
            'sender_phone' => '123',
            'sender_address' => 'Origin Port',
            'receiver_business_name' => 'Port Consignee',
            'receiver_contact_name' => 'Bob',
            'receiver_phone' => '456',
            'receiver_address' => 'Dest Port',
            'cargo_description' => 'Container Cargo',
            'cargo_weight_tonnes' => 20.0,
            'current_status' => 'IN_TRANSIT',
        ]);

        // Transition: ARRIVED_AT_DESTINATION_TERMINAL
        $shipment = ShipmentLifecycleService::transition($shipment, 'ARRIVED_AT_DESTINATION_TERMINAL', [
            'event_type' => 'ARRIVED_TERMINAL',
            'description' => 'Vessel berthed at destination quay',
        ]);

        // Critical Rule: For Port-to-Port, destination terminal arrival must become AWAITING_CUSTOMER_PICKUP, NOT DELIVERED
        $this->assertEquals('AWAITING_CUSTOMER_PICKUP', $shipment->current_status);
        $this->assertFalse($shipment->isDelivered());
    }
}

