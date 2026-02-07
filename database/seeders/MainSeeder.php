<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Bus;
use App\Models\Gare;
use App\Models\Route;
use App\Models\Ville;
use App\Models\Etape;
use App\Models\Segment;
use App\Models\Programme;
use Carbon\Carbon;

class MainSeeder extends Seeder
{
    public function run(): void
    { 
        $villesConfig = [
            'Tanger'     => ['x' => 5,  'y' => 12],
            'Oujda'      => ['x' => 12, 'y' => 10],
            'Rabat'      => ['x' => 4,  'y' => 9],
            'Casablanca' => ['x' => 3,  'y' => 8],
            'Fès'        => ['x' => 7,  'y' => 9],
            'Marrakech'  => ['x' => 4,  'y' => 5],
            'Agadir'     => ['x' => 3,  'y' => 2],
        ];

         foreach ($villesConfig as $name => $coords) {
            $ville = Ville::firstOrCreate(['name' => $name]);

            Gare::firstOrCreate([
                'nom' => "Gare Routière {$name}",
                'adresse' => "Quartier Populaire, {$name}",
                'ville_id' => $ville->id
            ]);
        }

         for ($i = 1; $i <= 10; $i++) {
            Bus::create([
                'matricule' => rand(1, 99) . "-B-" . rand(1000, 9999),
                'capacite' => 55,
                'statut' => 'Disponible'
            ]);
        }
 
        $v_nord = ['Tanger', 'Rabat', 'Casablanca'];
        $route = Route::create([
            'nom' => 'Ligne Nord-Centre',
            'description' => 'Trajet via l\'autoroute A1'
        ]);

        $etapes = [];
        foreach ($v_nord as $index => $vName) {
            $gare = Gare::whereHas('ville', function ($q) use ($vName) {
                $q->where('name', $vName);
            })->first();

            $etapes[] = Etape::create([
                'ordre' => $index + 1,
                'heure_passage' => (8 + ($index * 2)) . ':00:00',  
                'route_id' => $route->id,
                'gare_id' => $gare->id
            ]);
        }
 
        for ($i = 0; $i < count($etapes); $i++) {
            for ($j = $i + 1; $j < count($etapes); $j++) {
                
                
                $prix = ($j - $i) * 45; 
                $dist = ($j - $i) * 90;

                 $prog = Programme::create([
                    'jour_depart' => Carbon::today(),
                    'heure_depart' => $etapes[$i]->heure_passage,
                    'heure_arrivee' => $etapes[$j]->heure_passage,
                    'route_id' => $route->id
                ]);

                
                Segment::create([
                    'tarif' => $prix,
                    'duree_estimee' => '02:00:00',
                    'distance_km' => $dist,
                    'bus_id' => Bus::inRandomOrder()->first()->id,
                    'programme_id' => $prog->id,
                    'etape_depart_id' => $etapes[$i]->id,
                    'etape_arrivee_id' => $etapes[$j]->id
                ]);
            }
        }
    }
}