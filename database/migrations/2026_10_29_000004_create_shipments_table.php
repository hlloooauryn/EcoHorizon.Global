<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('shipments', function (Blueprint $table) {
            $table->id();
            $table->string('tracking_number')->unique()->index();
            $table->foreignId('customer_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('assigned_operator_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('service_level_id')->constrained('service_levels');
            $table->string('destination_type'); // door_address, seaport_terminal, airport_terminal, business_warehouse, fulfillment_center
            $table->foreignId('origin_location_id')->constrained('locations');
            $table->foreignId('destination_location_id')->constrained('locations');

            // Sender Information
            $table->string('sender_business_name');
            $table->string('sender_contact_name');
            $table->string('sender_phone');
            $table->string('sender_email')->nullable();
            $table->text('sender_address');

            // Receiver Information
            $table->string('receiver_business_name');
            $table->string('receiver_contact_name');
            $table->string('receiver_phone');
            $table->string('receiver_email')->nullable();
            $table->text('receiver_address');

            // Cargo Details
            $table->string('cargo_description');
            $table->decimal('cargo_weight_tonnes', 10, 3);
            $table->decimal('cargo_volume_cbm', 10, 3)->nullable();
            $table->integer('package_count')->default(1);
            $table->string('package_type')->default('Standard Pallets');
            $table->text('special_instructions')->nullable();

            // Status & Schedule
            $table->string('current_status')->default('CREATED')->index();
            $table->dateTime('eta')->nullable();
            $table->dateTime('original_eta')->nullable();
            $table->dateTime('actual_delivery_date')->nullable();

            // Sustainability & Emission aggregates
            $table->decimal('total_distance_km', 10, 2)->default(0);
            $table->decimal('total_estimated_co2e_kg', 10, 2)->default(0);
            $table->text('carbon_calculation_notes')->nullable();

            // Delivery Proof / Handover
            $table->string('proof_of_delivery_receiver_name')->nullable();
            $table->text('proof_of_delivery_notes')->nullable();
            $table->dateTime('proof_of_delivery_at')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('shipments');
    }
};
