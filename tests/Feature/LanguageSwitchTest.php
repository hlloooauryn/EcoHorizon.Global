<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LanguageSwitchTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public function test_default_locale_is_english(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $this->assertEquals('en', app()->getLocale());
        $response->assertSee('Track Shipment');
    }

    public function test_switch_language_to_indonesian_stores_session_and_translates(): void
    {
        // 1. Switch to Indonesian
        $switchResponse = $this->get('/lang/id');
        $switchResponse->assertSessionHas('locale', 'id');

        // 2. Request Home in Indonesian
        $response = $this->withSession(['locale' => 'id'])->get('/');
        $response->assertStatus(200);
        $this->assertEquals('id', app()->getLocale());
        $response->assertSee('Lacak Kargo');
        $response->assertSee('Moda Multimoda');
        $response->assertSee('Perjalanan Kargo Anda,');
        $response->assertSee('Terhubung Nyata.');
        $response->assertSee('Pengiriman Terpantau');
        $response->assertSee('Infrastruktur Kargo Multimoda');
    }

    public function test_switch_language_to_english(): void
    {
        // Switch to Indonesian then back to English
        $this->get('/lang/id');
        $switchResponse = $this->get('/lang/en');
        $switchResponse->assertSessionHas('locale', 'en');

        $response = $this->withSession(['locale' => 'en'])->get('/');
        $response->assertStatus(200);
        $this->assertEquals('en', app()->getLocale());
        $response->assertSee('Track Shipment');
    }

    public function test_invalid_locale_does_not_change_session(): void
    {
        $response = $this->withSession(['locale' => 'en'])->get('/lang/invalid_lang');

        $response->assertSessionHas('locale', 'en');
        $this->assertEquals('en', app()->getLocale());
    }

    public function test_authenticated_admin_portal_translates_sidebar_in_indonesian(): void
    {
        $admin = User::where('role', 'admin')->first();

        // With Indonesian session
        $response = $this->actingAs($admin)
            ->withSession(['locale' => 'id'])
            ->get('/admin/dashboard');

        $response->assertStatus(200);
        $this->assertEquals('id', app()->getLocale());
        $response->assertSee('Dasbor');
        $response->assertSee('Pengiriman');
        $response->assertSee('Pelacakan');
        $response->assertSee('Kendala &amp; Pengecualian', false);
        $response->assertSee('Faktor Emisi');
        $response->assertSee('Keluar');
        $response->assertSee('Pusat Komando Visibilitas Global');
        $response->assertSee('Total Kargo');
        $response->assertSee('Dalam Perjalanan');
        $response->assertSee('Terkirim');
    }

    public function test_shipments_registry_translates_table_headers_in_indonesian(): void
    {
        $admin = User::where('role', 'admin')->first();

        $response = $this->actingAs($admin)
            ->withSession(['locale' => 'id'])
            ->get('/shipments');

        $response->assertStatus(200);
        $this->assertEquals('id', app()->getLocale());
        $response->assertSee('Registri Pengiriman');
        $response->assertSee('Kode Pelacakan');
        $response->assertSee('Pengirim &amp; Penerima', false);
        $response->assertSee('Tingkat Layanan');
        $response->assertSee('Status');
        $response->assertSee('Saring');
    }

    public function test_internal_tracking_console_translates_in_indonesian(): void
    {
        $admin = User::where('role', 'admin')->first();

        $response = $this->actingAs($admin)
            ->withSession(['locale' => 'id'])
            ->get('/tracking');

        $response->assertStatus(200);
        $this->assertEquals('id', app()->getLocale());
        $response->assertSee('Konsol Pelacakan &amp; Telemetri Kargo', false);
        $response->assertSee('Estimasi Tiba');
        $response->assertSee('Massa Kargo');
        $response->assertSee('Jarak Transit');
        $response->assertSee('Jejak Karbon');
        $response->assertSee('Koridor Geospasial & Titik Koordinat', false);
        $response->assertSee('Sensor Atmosfer Terminal');
        $response->assertSee('Segmen Transit Multimoda');
        $response->assertSee('Pihak Komersial');
        $response->assertSee('Pesanan Dipesan');
        $response->assertSee('Penjemputan di Asal');
        $response->assertSee('Transit Koridor');
        $response->assertSee('Pemeriksaan Pelabuhan / Bea Cukai');
        $response->assertSee('Serah Terima Akhir');
    }

    public function test_sustainability_analytics_translates_in_indonesian(): void
    {
        $admin = User::where('role', 'admin')->first();

        $response = $this->actingAs($admin)
            ->withSession(['locale' => 'id'])
            ->get('/sustainability');

        $response->assertStatus(200);
        $this->assertEquals('id', app()->getLocale());
        $response->assertSee('Analisis Emisi Karbon Multimoda');
        $response->assertSee('Total Emisi Transportasi CO₂e');
        $response->assertSee('Total Jarak Terpantau');
        $response->assertSee('Proporsi Moda Transportasi &amp; Emisi', false);
        $response->assertSee('Katalog Faktor Emisi Aktif');
    }

    public function test_public_tracking_translates_in_indonesian(): void
    {
        $response = $this->withSession(['locale' => 'id'])
            ->get('/track/EHG-20261029-JKTMUC');

        $response->assertStatus(200);
        $this->assertEquals('id', app()->getLocale());
        $response->assertSee('Registri Pelacakan');
        $response->assertSee('Estimasi Tiba (ETA)');
        $response->assertSee('Spesifikasi Kargo');
        $response->assertSee('Estimasi Karbon (CO₂e)');
        $response->assertSee('Peta Rute Perjalanan Multimoda');
    }
}
