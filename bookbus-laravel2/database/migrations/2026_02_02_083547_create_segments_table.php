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
        Schema::create('segments', function (Blueprint $table) {
            $table->id();
            $table->decimal('tarif', 8, 2);
    $table->time('duree_estimee');
    $table->float('distance_km');
    $table->foreignId('bus_id')->constrained();
    $table->unsignedBigInteger('depart_etape_id');
    $table->unsignedBigInteger('arrivee_etape_id');
    
    $table->foreign('depart_etape_id')->references('id')->on('etapes');
    $table->foreign('arrivee_etape_id')->references('id')->on('etapes');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('segments');
    }
};
