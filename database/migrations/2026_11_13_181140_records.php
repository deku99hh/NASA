<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('location_id')->constrained('locations')->cascadeOnDelete();
            
            $table->unsignedSmallInteger('year');
            $table->unsignedTinyInteger('month');

            $table->float('t2m')->nullable();
            $table->float('max')->nullable();
            $table->float('precipitation_mm')->nullable();
            $table->float('ET_mm')->nullable();
            $table->float('RH2M')->nullable();
            $table->float('ALLSKY_SFC_SW_DWN')->nullable();
            $table->float('soil_moisture')->nullable();
            $table->float('NDVI')->nullable();
            $table->float('elevation_m')->nullable();
            $table->float('slope_deg')->nullable();
            $table->float('clay')->nullable();
            $table->float('sand')->nullable();
            $table->float('silt')->nullable();
            $table->float('ph')->nullable();
            $table->float('om')->nullable();
            $table->float('ksat')->nullable();

            $table->timestamps();
            $table->unique(['location_id', 'year', 'month']);
        });
    }

    /**
     * 0137
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('records');
    }
};
