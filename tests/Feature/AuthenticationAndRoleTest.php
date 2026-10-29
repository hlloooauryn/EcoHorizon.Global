<?php

namespace Tests\Feature;

use App\Models\Shipment;
use App\Models\User;
use Database\Seeders\CarbonEmissionFactorSeeder;
use Database\Seeders\LocationSeeder;
use Database\Seeders\ServiceLevelSeeder;
use Database\Seeders\UserSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthenticationAndRoleTest extends TestCase
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

    public function test_unauthenticated_user_is_redirected_to_login()
    {
        $response = $this->get('/admin/dashboard');
        $response->assertRedirect('/login');

        $response2 = $this->get('/shipments');
        $response2->assertRedirect('/login');
    }

    public function test_admin_can_access_admin_dashboard_and_user_directory()
    {
        $admin = User::where('role', 'admin')->first();

        $response = $this->actingAs($admin)->get('/admin/dashboard');
        $response->assertStatus(200);
        $response->assertSee('System Executive Overview');

        $userDirectory = $this->actingAs($admin)->get('/admin/users');
        $userDirectory->assertStatus(200);
    }

    public function test_operator_cannot_access_admin_portal_but_can_access_operator_cockpit()
    {
        $operator = User::where('role', 'operator')->first();

        $response = $this->actingAs($operator)->get('/admin/dashboard');
        $response->assertStatus(403);

        $cockpit = $this->actingAs($operator)->get('/operator/dashboard');
        $cockpit->assertStatus(200);
        $cockpit->assertSee('Logistics Operations Cockpit');
    }

    public function test_customer_can_only_access_customer_workspace()
    {
        $customer = User::where('role', 'customer')->first();

        $responseAdmin = $this->actingAs($customer)->get('/admin/dashboard');
        $responseAdmin->assertStatus(403);

        $responseOperator = $this->actingAs($customer)->get('/operator/dashboard');
        $responseOperator->assertStatus(403);

        $workspace = $this->actingAs($customer)->get('/customer/dashboard');
        $workspace->assertStatus(200);
    }

    public function test_login_validation_and_session_authentication()
    {
        $response = $this->post('/login', [
            'email' => 'admin@ecohorizon.test',
            'password' => 'password123',
        ]);

        $response->assertRedirect(route('admin.dashboard'));
        $this->assertAuthenticated();
    }
}

