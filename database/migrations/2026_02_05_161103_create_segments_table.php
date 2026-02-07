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
            $table->float('tarif', 8, 2)->index();
            $table->time('duree_estimee'); 
            $table->float('distance_km');
            $table->foreignId('bus_id')->constrained()->onDelete('cascade');
             $table->foreignId('programme_id')->constrained()->onDelete('cascade');
             $table->foreignId('etape_depart_id')->constrained('etapes')->onDelete('cascade');
    $table->foreignId('etape_arrivee_id')->constrained('etapes')->onDelete('cascade');
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
