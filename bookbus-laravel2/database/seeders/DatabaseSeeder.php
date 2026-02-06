<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
         $adminId = DB::table('users')->insertGetId([
            'name' => 'Admin SATAS',
            'email' => 'admin@satas.ma',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        for ($i = 1; $i <= 10; $i++) {
            DB::table('users')->insert([
                'name' => "Driver $i",
                'email' => "driver$i@satas.ma",
                'password' => Hash::make('password'),
                'role' => 'driver',
                'phone' => '066100000'.$i,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

         $casaId = DB::table('villes')->insertGetId(['name' => 'Casablanca', 'created_at' => now()]);
        $marId = DB::table('villes')->insertGetId(['name' => 'Marrakech', 'created_at' => now()]);

        $g1 = DB::table('gares')->insertGetId(['nom' => 'Oulad Ziane', 'adresse' => 'Casa', 'ville_id' => $casaId]);
        $g2 = DB::table('gares')->insertGetId(['nom' => 'Gare Marrakech', 'adresse' => 'Marrakech', 'ville_id' => $marId]);

         $busId = DB::table('buses')->insertGetId([
            'matricule' => 'SATAS-123',
            'capacite' => 50,
            'type' => 'Premium',
            'statut' => 'enservice',
            'created_at' => now(),
        ]);

         $routeId = DB::table('routes')->insertGetId([
            'nom' => 'Casa -> Marrakech',
            'description' => 'Direct Line',
            'created_at' => now(),
        ]);

        $e1 = DB::table('etapes')->insertGetId(['route_id' => $routeId, 'gare_id' => $g1, 'ordre' => 1, 'heure_passage' => '08:00:00']);
        $e2 = DB::table('etapes')->insertGetId(['route_id' => $routeId, 'gare_id' => $g2, 'ordre' => 2, 'heure_passage' => '11:00:00']);

        DB::table('segments')->insert([
            'tarif' => 100.00,
            'duree_estimee' => '03:00:00',
            'distance_km' => 240,
            'bus_id' => $busId,
            'depart_etape_id' => $e1,
            'arrivee_etape_id' => $e2,
            'created_at' => now(),
        ]);

        $this->command->info('Database seeded successfully for SATAS!');
    }
}
