<?php

namespace Tests\Feature;

use App\Models\Shipment;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminDashboardMapTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public function test_admin_dashboard_renders_with_interactive_map_and_shipment_data(): void
    {
        $admin = User::where('role', 'admin')->first();

        $response = $this->actingAs($admin)->get('/admin/dashboard');

        $response->assertStatus(200);
        $response->assertViewHas('mapShipments');
        $response->assertViewHas('mapFilterCounts');
        $response->assertSee('dashboardMap');
        $response->assertSee('Interactive Global Corridor & Shipment Radar', false);
        $response->assertSee('World View');
        $response->assertSee('focusShipmentOnDashboardMap');

        $mapShipments = $response->viewData('mapShipments');
        $this->assertNotEmpty($mapShipments);

        $filterCounts = $response->viewData('mapFilterCounts');
        $this->assertArrayHasKey('all', $filterCounts);
        $this->assertArrayHasKey('in_transit', $filterCounts);
        $this->assertArrayHasKey('delivered', $filterCounts);
        $this->assertArrayHasKey('delayed', $filterCounts);
        $this->assertArrayHasKey('history', $filterCounts);
        $this->assertEquals($mapShipments->filter(fn($s) => !$s['is_history'])->count(), $filterCounts['all']);
        $this->assertEquals($mapShipments->count(), $filterCounts['all'] + $filterCounts['history']);

        // Verify structure of map shipments
        $first = $mapShipments->first();
        $this->assertArrayHasKey('id', $first);
        $this->assertArrayHasKey('tracking_number', $first);
        $this->assertArrayHasKey('status', $first);
        $this->assertArrayHasKey('origin', $first);
        $this->assertArrayHasKey('destination', $first);
        $this->assertArrayHasKey('carrier', $first);
        $this->assertArrayHasKey('mode', $first);
        $this->assertArrayHasKey('lat', $first['origin']);
        $this->assertArrayHasKey('lon', $first['origin']);
        $this->assertArrayHasKey('lat', $first['destination']);
        $this->assertArrayHasKey('lon', $first['destination']);

        // Verify Modal Share Donut Chart and Recent Shipments Activity
        $response->assertSee('modalShareDonutChart');
        $response->assertSee('Modal Distribution & Freight Lanes');
        $response->assertSee('Recent Shipments Activity');
        $response->assertViewHas('modalDistribution');
        $response->assertViewHas('recentShipments');

        $modalDist = $response->viewData('modalDistribution');
        $this->assertArrayHasKey('sea', $modalDist);
        $this->assertArrayHasKey('air', $modalDist);
        $this->assertArrayHasKey('land', $modalDist);

        $recent = $response->viewData('recentShipments');
        $this->assertLessThanOrEqual(5, $recent->count());
    }

    public function test_fleet_map_page_is_completely_removed(): void
    {
        $admin = User::where('role', 'admin')->first();

        // Standalone fleet-map route should not exist (404)
        $response = $this->actingAs($admin)->get('/admin/fleet-map');
        $response->assertStatus(404);

        $responseDashboard = $this->actingAs($admin)->get('/admin/dashboard');
        $responseDashboard->assertStatus(200);
        $responseDashboard->assertDontSee('/admin/fleet-map');
    }
}
