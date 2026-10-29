<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('locations', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code')->nullable()->index(); // UN/LOCODE or IATA like IDTPP, SGSIN, USLAX
            $table->string('type'); // seaport, airport, warehouse, terminal, door_address
            $table->string('address');
            $table->string('city');
            $table->string('state')->nullable();
            $table->string('country');
            $table->string('country_code', 3)->index();
            $table->decimal('latitude', 10, 7);
            $table->decimal('longitude', 10, 7);
            $table->string('contact_person')->nullable();
            $table->string('contact_phone')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('locations');
    }
};

