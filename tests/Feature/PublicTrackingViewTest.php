<?php

namespace Tests\Feature;

use App\Models\Shipment;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PublicTrackingViewTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public function test_public_tracking_page_renders_with_multimodal_journey_and_map_data(): void
    {
        $response = $this->get('/track/EHG-20261029-JKTMUC');

        $response->assertStatus(200);
        $response->assertSee('EHG-20261029-JKTMUC');
        $response->assertSee('Door-to-Door');
        $response->assertSee('Tanjung Priok');
        $response->assertSee('Singapore');
        $response->assertSee('Frankfurt');
        $response->assertSee('Munich');
        $response->assertSee('Carbon Accounting Methodology');
    }

    public function test_public_tracking_page_renders_exception_details(): void
    {
        $response = $this->get('/track/EHG-20261029-SHARTM');

        $response->assertStatus(200);
        $response->assertSee('EHG-20261029-SHARTM');
        $response->assertSee('Weather disruption');
    }

    public function test_public_tracking_search_redirects_to_shipment(): void
    {
        $response = $this->get('/track-search?tracking_number=EHG-20261029-SUBB2B');

        $response->assertRedirect('/track/EHG-20261029-SUBB2B');
    }

    public function test_sustainability_dashboard_renders_for_authenticated_users(): void
    {
        $customer = User::where('email', 'customer@globalcargo.test')->first();

        $response = $this->actingAs($customer)->get('/sustainability');

        $response->assertStatus(200);
        $response->assertSee('Sustainability');
        $response->assertSee('GLEC Framework v3.0');
    }
}
