<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('service_levels', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique(); // B2B, DOOR_TO_PORT, PORT_TO_PORT
            $table->string('name');
            $table->text('description');
            $table->string('icon')->nullable();
            $table->boolean('requires_pickup')->default(true);
            $table->boolean('requires_recipient_delivery')->default(true);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('service_levels');
    }
};

