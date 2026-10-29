<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transport_legs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('shipment_id')->constrained('shipments')->cascadeOnDelete();
            $table->integer('sequence_number')->default(1);
            $table->string('transport_mode')->index(); // sea, land, air
            $table->foreignId('origin_location_id')->constrained('locations');
            $table->foreignId('destination_location_id')->constrained('locations');
            $table->string('carrier_name');
            $table->string('transport_reference')->nullable(); // Vessel/Voyage, Flight #, Truck Plate
            $table->dateTime('planned_departure')->nullable();
            $table->dateTime('actual_departure')->nullable();
            $table->dateTime('estimated_arrival')->nullable();
            $table->dateTime('actual_arrival')->nullable();
            $table->decimal('distance_km', 10, 2)->default(0);
            $table->string('distance_source')->default('calculated_haversine'); // calculated_haversine, manual_operator_override, standard_corridor
            $table->foreignId('emission_factor_id')->nullable()->constrained('carbon_emission_factors')->nullOnDelete();
            $table->decimal('leg_estimated_co2e_kg', 10, 2)->default(0);
            $table->string('status')->default('PENDING')->index(); // PENDING, IN_TRANSIT, COMPLETED, CANCELLED
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transport_legs');
    }
};

