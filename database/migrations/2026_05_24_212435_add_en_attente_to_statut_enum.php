<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $driver = DB::getDriverName();

        if ($driver === 'mysql') {
            DB::statement("ALTER TABLE reservations MODIFY COLUMN statut ENUM('Confirmé','Annulé','Payé','En attente') NOT NULL DEFAULT 'Confirmé'");
        }
        // SQLite: no ENUM constraint, En attente already works
    }

    public function down(): void
    {
        if (DB::getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE reservations MODIFY COLUMN statut ENUM('Confirmé','Annulé','Payé') NOT NULL DEFAULT 'Confirmé'");
        }
    }
};
