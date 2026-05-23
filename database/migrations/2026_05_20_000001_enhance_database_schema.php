<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // ── Enhance users table ──
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'email_verified_at')) {
                $table->timestamp('email_verified_at')->nullable()->after('email');
            }
            if (!Schema::hasColumn('users', 'remember_token')) {
                $table->rememberToken()->after('password');
            }
        });

        // ── Enhance buses table ──
        Schema::table('buses', function (Blueprint $table) {
            $table->string('type')->default('standard')->after('capacite'); // standard, confort, premium
            $table->json('amenities')->nullable()->after('type'); // WiFi, prises, WC, climatisation
        });

        // ── Create employees table ──
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->string('first_name');
            $table->string('last_name');
            $table->string('license_number')->unique();
            $table->string('role')->default('chauffeur'); // chauffeur, administrateur
            $table->string('phone');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // ── Create passengers table ──
        Schema::create('passengers', function (Blueprint $table) {
            $table->id();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('cin')->nullable(); // Carte d'identité nationale
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->timestamps();
        });

        // ── Create assignments table ──
        Schema::create('assignments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('programme_id')->constrained()->onDelete('cascade');
            $table->foreignId('bus_id')->constrained()->onDelete('cascade');
            $table->foreignId('employee_id')->constrained()->onDelete('cascade');
            $table->date('date');
            $table->timestamps();

            $table->unique(['programme_id', 'date'], 'unique_trip_assignment');
            $table->index(['bus_id', 'date']);
            $table->index(['employee_id', 'date']);
        });

        // ── Enhance reservations table ──
        Schema::table('reservations', function (Blueprint $table) {
            $table->string('reference')->unique()->nullable()->after('id');
            $table->foreignId('passenger_id')->nullable()->after('user_id');
            $table->boolean('snack_box')->default(false)->after('segment_id');
            $table->boolean('insurance')->default(false)->after('snack_box');
            $table->string('promo_code')->nullable()->after('insurance');
            $table->decimal('base_price', 8, 2)->default(0)->after('promo_code');
            $table->decimal('extras_price', 8, 2)->default(0)->after('base_price');
            $table->decimal('total_price', 8, 2)->default(0)->after('extras_price');
            $table->timestamp('cancelled_at')->nullable()->after('total_price');
            $table->decimal('refund_amount', 8, 2)->nullable()->after('cancelled_at');
            $table->string('payment_method')->default('cash')->after('refund_amount'); // cash, card, virement
        });

        // ── Create promo_codes table ──
        Schema::create('promo_codes', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->integer('discount_percent');
            $table->integer('max_uses')->default(100);
            $table->integer('used_count')->default(0);
            $table->date('valid_from');
            $table->date('valid_until');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('promo_codes');
        Schema::dropIfExists('assignments');
        Schema::dropIfExists('passengers');
        Schema::dropIfExists('employees');

        Schema::table('reservations', function (Blueprint $table) {
            $table->dropColumn([
                'reference', 'passenger_id', 'snack_box', 'insurance',
                'promo_code', 'base_price', 'extras_price', 'total_price',
                'cancelled_at', 'refund_amount', 'payment_method'
            ]);
        });

        Schema::table('buses', function (Blueprint $table) {
            $table->dropColumn(['type', 'amenities']);
        });

        Schema::table('users', function (Blueprint $table) {
            // Don't drop these as they might be from original migration
        });
    }
};
