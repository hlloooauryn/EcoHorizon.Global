<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('delivery_exceptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('shipment_id')->constrained('shipments')->cascadeOnDelete();
            $table->foreignId('transport_leg_id')->nullable()->constrained('transport_legs')->nullOnDelete();
            $table->string('category')->index(); // weather_disruption, transport_delay, damaged_cargo, missing_documentation, customs_clearance_issue, failed_delivery_attempt, other_operational_issue
            $table->string('severity')->default('medium')->index(); // low, medium, high, critical
            $table->text('description');
            $table->text('action_taken')->nullable();
            $table->foreignId('assigned_operator_id')->nullable()->constrained('users')->nullOnDelete();
            $table->boolean('is_resolved')->default(false)->index();
            $table->dateTime('resolved_at')->nullable();
            $table->text('resolution_notes')->nullable();
            $table->integer('impact_on_eta_hours')->default(0);
            $table->boolean('is_customer_visible')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('delivery_exceptions');
    }
};
