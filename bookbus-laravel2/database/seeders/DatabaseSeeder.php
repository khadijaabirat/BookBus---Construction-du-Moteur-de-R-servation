<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
       $villes = [
            ['name' => 'Casablanca'],
            ['name' => 'Marrakech'],
            ['name' => 'Agadir'],
            ['name' => 'Settat']
        ];
        foreach ($villes as $v) {
            DB::table('villes')->insert(array_merge($v, ['created_at' => now()]));
        }

         $casaId = DB::table('villes')->where('name', 'Casablanca')->first()->id;
        $kechId = DB::table('villes')->where('name', 'Marrakech')->first()->id;
        $settatId = DB::table('villes')->where('name', 'Settat')->first()->id;

        DB::table('gares')->insert([
            ['nom' => 'Gare Oulad Ziane', 'adresse' => 'Casablanca Center', 'ville_id' => $casaId],
            ['nom' => 'Gare Casa Voyageurs', 'adresse' => 'Belvédère', 'ville_id' => $casaId], 
            ['nom' => 'Gare Marrakech (Sidi Mimoun)', 'adresse' => 'Marrakech Center', 'ville_id' => $kechId],
            ['nom' => 'Gare Settat Centrale', 'adresse' => 'Settat Center', 'ville_id' => $settatId],
        ]);

         DB::table('users')->insert([
            'name' => 'Admin SATAS',
            'email' => 'admin@satas.ma',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'phone' => '0522000000',
            'created_at' => now()
        ]);

        for ($i = 1; $i <= 15; $i++) {
            DB::table('users')->insert([
                'name' => "Chauffeur Satas $i",
                'email' => "driver$i@satas.ma",
                'password' => Hash::make('password'),
                'role' => 'driver',
                'phone' => "06610000$i",
                'created_at' => now()
            ]);
        }

         for ($i = 1; $i <= 20; $i++) {
            DB::table('buses')->insert([
                'matricule' => "SATAS-BUS-$i",
                'capacite' => 50,
                'type' => $i <= 10 ? 'Premium' : 'Standard',
                'statut' => 'enservice',
                'created_at' => now()
            ]);
        }

         $routeId = DB::table('routes')->insertGetId([
            'nom' => 'L101: Casa -> Settat -> Marrakech',
            'description' => '  '
        ]);
 for ($i = 1; $i <= 20; $i++) {
    DB::table('buses')->insert([
        'matricule' => "SATAS-BUS-$i",
        'capacite' => 50,
        'type' => $i <= 10 ? 'Premium' : 'Standard',
        'statut' => 'enservice',
         'amenities' => json_encode([
            'wifi' => $i <= 10, 
            'usb' => true, 
            'wc' => $i <= 10
        ]),
        'created_at' => now()
    ]);
}
        $gCasa = DB::table('gares')->where('nom', 'Gare Oulad Ziane')->first()->id;
        $gSettat = DB::table('gares')->where('nom', 'Gare Settat Centrale')->first()->id;
        $gKech = DB::table('gares')->where('nom', 'Gare Marrakech (Sidi Mimoun)')->first()->id;

        $e1 = DB::table('etapes')->insertGetId(['route_id' => $routeId, 'gare_id' => $gCasa, 'ordre' => 1, 'heure_passage' => '08:00:00']);
        $e2 = DB::table('etapes')->insertGetId(['route_id' => $routeId, 'gare_id' => $gSettat, 'ordre' => 2, 'heure_passage' => '09:30:00']);
        $e3 = DB::table('etapes')->insertGetId(['route_id' => $routeId, 'gare_id' => $gKech, 'ordre' => 3, 'heure_passage' => '12:00:00']);
 
        $busId = DB::table('buses')->first()->id;

        DB::table('segments')->insert([
          
            ['tarif' => 120.00, 'duree_estimee' => '04:00:00', 'distance_km' => 240, 'bus_id' => $busId, 'depart_etape_id' => $e1, 'arrivee_etape_id' => $e3],
             ['tarif' => 40.00, 'duree_estimee' => '01:30:00', 'distance_km' => 80, 'bus_id' => $busId, 'depart_etape_id' => $e1, 'arrivee_etape_id' => $e2],
            
            ['tarif' => 90.00, 'duree_estimee' => '02:30:00', 'distance_km' => 160, 'bus_id' => $busId, 'depart_etape_id' => $e2, 'arrivee_etape_id' => $e3],
        ]);

        echo "SATAS Database is ready with multiple stations per city!";
    }
    }

