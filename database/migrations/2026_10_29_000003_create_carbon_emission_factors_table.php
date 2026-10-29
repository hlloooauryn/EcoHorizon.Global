<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('carbon_emission_factors', function (Blueprint $table) {
            $table->id();
            $table->string('transport_mode')->index(); // sea, land, air
            $table->string('subcategory'); // e.g. Container Vessel (8000+ TEU), Heavy Articulated Truck, Cargo Aircraft
            $table->decimal('factor_g_per_tonne_km', 10, 4); // in g CO2e / tonne-km
            $table->string('source_reference'); // GLEC Framework v3.0, IMO MEPC.308(73), DEFRA 2024
            $table->text('description')->nullable();
            $table->integer('effective_year')->default(2024);
            $table->boolean('is_default')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('carbon_emission_factors');
    }
};

