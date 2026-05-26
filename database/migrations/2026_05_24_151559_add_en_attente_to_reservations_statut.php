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

        if ($driver === 'sqlite') {
            DB::statement('PRAGMA foreign_keys=OFF');
            DB::statement('DROP TABLE IF EXISTS "reservations_new"');

            DB::statement('
                CREATE TABLE "reservations_new" (
                    "id" integer primary key autoincrement not null,
                    "reference" varchar,
                    "date_reservation" date not null,
                    "statut" varchar not null default \'Confirmé\',
                    "siege_numero" integer not null,
                    "user_id" integer not null,
                    "passenger_id" integer,
                    "segment_id" integer not null,
                    "snack_box" tinyint(1) not null default 0,
                    "insurance" tinyint(1) not null default 0,
                    "promo_code" varchar,
                    "base_price" numeric not null default 0,
                    "extras_price" numeric not null default 0,
                    "total_price" numeric not null default 0,
                    "cancelled_at" datetime,
                    "refund_amount" numeric,
                    "payment_method" varchar not null default \'cash\',
                    "payment_proof" varchar,
                    "created_at" datetime,
                    "updated_at" datetime,
                    foreign key("user_id") references "users"("id") on delete cascade,
                    foreign key("segment_id") references "segments"("id") on delete cascade
                )
            ');

            DB::statement('
                INSERT INTO "reservations_new"
                SELECT "id","reference","date_reservation","statut","siege_numero","user_id","passenger_id","segment_id",
                COALESCE("snack_box",0),COALESCE("insurance",0),"promo_code",
                COALESCE("base_price",0),COALESCE("extras_price",0),COALESCE("total_price",0),
                "cancelled_at","refund_amount",COALESCE("payment_method",\'cash\'),"payment_proof","created_at","updated_at"
                FROM "reservations"
            ');

            DB::statement('DROP TABLE "reservations"');
            DB::statement('ALTER TABLE "reservations_new" RENAME TO "reservations"');
            DB::statement('PRAGMA foreign_keys=ON');
        }
        // MySQL: statut column has no CHECK constraint, so En attente works already
    }

    public function down(): void {}
};
