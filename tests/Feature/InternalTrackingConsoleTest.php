<?php

namespace Tests\Feature;

use App\Models\Shipment;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class InternalTrackingConsoleTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public function test_unauthenticated_user_cannot_access_internal_tracking(): void
    {
        $response = $this->get('/tracking');
        $response->assertRedirect('/login');
    }

    public function test_admin_can_access_internal_tracking_console(): void
    {
        $admin = User::where('role', 'admin')->first();

        $response = $this->actingAs($admin)->get('/tracking');

        $response->assertStatus(200);
        $response->assertViewIs('tracking.index');
        $response->assertSee('Geospatial Corridors & Waypoints', false);
        $response->assertSee('internalTrackingMap');
        $response->assertViewHas('shipment');
        $response->assertViewHas('recentShipments');
    }

    public function test_admin_can_track_specific_shipment_by_tracking_number(): void
    {
        $admin = User::where('role', 'admin')->first();
        $shipment = Shipment::first();

        $response = $this->actingAs($admin)->get('/tracking/' . $shipment->tracking_number);

        $response->assertStatus(200);
        $response->assertSee($shipment->tracking_number);
        $response->assertSee('Inspect Cargo Record');
    }

    public function test_customer_can_track_their_own_shipment(): void
    {
        $shipment = Shipment::whereNotNull('customer_user_id')->first();
        $customer = $shipment->customer;

        $response = $this->actingAs($customer)->get('/tracking/' . $shipment->tracking_number);
        $response->assertStatus(200);
        $response->assertSee($shipment->tracking_number);
    }

    public function test_customer_cannot_track_other_customer_shipment(): void
    {
        $shipment = Shipment::whereNotNull('customer_user_id')->first();
        $otherCustomer = User::where('role', 'customer')
            ->where('id', '!=', $shipment->customer_user_id)
            ->first();

        $this->assertNotNull($otherCustomer);

        $response = $this->actingAs($otherCustomer)->get('/tracking/' . $shipment->tracking_number);
        $response->assertStatus(403);
    }

    public function test_sidebar_tracking_link_points_to_internal_console(): void
    {
        $admin = User::where('role', 'admin')->first();

        $response = $this->actingAs($admin)->get('/admin/dashboard');

        $response->assertStatus(200);
        $response->assertSee('href="' . route('tracking.index') . '"', false);
        $response->assertDontSee('href="' . route('home') . '#track"', false);
    }

    public function test_door_to_door_is_completely_removed_and_replaced_with_b2b(): void
    {
        $admin = User::where('role', 'admin')->first();

        $response = $this->actingAs($admin)->get('/tracking');
        $response->assertStatus(200);
        $response->assertDontSee('DOOR_TO_DOOR');

        // Verify service level database state
        $this->assertDatabaseMissing('service_levels', ['code' => 'DOOR_TO_DOOR']);
        $this->assertDatabaseHas('service_levels', ['code' => 'B2B']);
    }
}
