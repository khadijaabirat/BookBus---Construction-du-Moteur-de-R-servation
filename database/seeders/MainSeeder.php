<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Ville;
use App\Models\Gare;
use App\Models\Bus;
use App\Models\Route;
use App\Models\Etape;
use App\Models\Programme;
use App\Models\Segment;
use App\Models\Employee;
use App\Models\Assignment;
use App\Models\PromoCode;
use App\Models\User;
use App\Models\Reservation;
use Carbon\Carbon;
use Illuminate\Support\Str;

class MainSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Create Villes and Gares (More cities for a dense network)
        $cityNames = [
            'Tanger', 'Oujda', 'Rabat', 'Casablanca', 'Fès', 'Marrakech', 'Agadir',
            'Settat', 'Kénitra', 'Meknès', 'Taza', 'El Jadida', 'Essaouira', 
            'Laâyoune', 'Dakhla', 'Safi', 'Ouarzazate', 'Errachidia', 'Nador', 'Al Hoceima'
        ];

        $villes = [];
        foreach ($cityNames as $cityName) {
            $ville = Ville::firstOrCreate(['name' => $cityName]);
            $villes[$cityName] = $ville;
            
            Gare::firstOrCreate(
                ['ville_id' => $ville->id],
                [
                    'nom' => "Gare Routière SATAS {$cityName}",
                    'adresse' => "Gare Principale, {$cityName}"
                ]
            );
        }

        // 2. Create Buses (Increased fleet size and varied types)
        $buses = [];
        
        // 25 Standard
        for ($i = 1; $i <= 25; $i++) {
            $buses[] = Bus::create([
                'matricule' => rand(10, 99) . '-B-' . rand(1000, 9999),
                'capacite' => 55,
                'statut' => 'disponible',
                'type' => 'standard',
                'amenities' => ['climatisation']
            ]);
        }
        // 15 Confort
        for ($i = 1; $i <= 15; $i++) {
            $buses[] = Bus::create([
                'matricule' => rand(10, 99) . '-A-' . rand(1000, 9999),
                'capacite' => 45,
                'statut' => 'disponible',
                'type' => 'confort',
                'amenities' => ['climatisation', 'prises', 'wifi']
            ]);
        }
        // 10 Premium
        for ($i = 1; $i <= 10; $i++) {
            $buses[] = Bus::create([
                'matricule' => rand(10, 99) . '-D-' . rand(1000, 9999),
                'capacite' => 35,
                'statut' => 'disponible',
                'type' => 'premium',
                'amenities' => ['wifi', 'prises', 'wc', 'climatisation', 'tablette']
            ]);
        }

        // 3. Create Chauffeurs (Employees)
        $firstNames = ['Ahmed', 'Mohammed', 'Youssef', 'Hassan', 'Omar', 'Karim', 'Said', 'Rachid', 'Tariq', 'Mounir', 'Brahim', 'Abdel', 'Jamal', 'Fouad', 'Kamal'];
        $lastNames = ['Benali', 'Alaoui', 'Mansouri', 'Chraibi', 'Tazi', 'Amrani', 'Idrissi', 'El Fassi', 'Berrada', 'Zniber', 'Tahiri', 'Malki', 'Radi', 'Filali', 'Zouhair'];
        $drivers = [];
        
        for ($i = 0; $i < 30; $i++) {
            $drivers[] = Employee::create([
                'first_name' => $firstNames[array_rand($firstNames)],
                'last_name' => $lastNames[array_rand($lastNames)],
                'license_number' => 'PC' . rand(100000, 999999),
                'role' => 'chauffeur',
                'phone' => '06' . rand(10000000, 99999999),
                'is_active' => true,
            ]);
        }

        // 4. Create Routes
        $routesData = [
            // Direct major routes
            ['nom' => 'L100', 'desc' => 'Casablanca - Marrakech (Direct)', 'stops' => ['Casablanca', 'Marrakech']],
            ['nom' => 'L101', 'desc' => 'Casablanca - Agadir (Direct)', 'stops' => ['Casablanca', 'Agadir']],
            ['nom' => 'L102', 'desc' => 'Rabat - Tanger (Direct)', 'stops' => ['Rabat', 'Tanger']],
            ['nom' => 'L103', 'desc' => 'Rabat - Fès (Direct)', 'stops' => ['Rabat', 'Fès']],
            
            // Multi-stop routes
            ['nom' => 'L201', 'desc' => 'Tanger - Agadir via Casa', 'stops' => ['Tanger', 'Rabat', 'Casablanca', 'Marrakech', 'Agadir']],
            ['nom' => 'L202', 'desc' => 'Oujda - Casablanca', 'stops' => ['Oujda', 'Taza', 'Fès', 'Meknès', 'Rabat', 'Casablanca']],
            ['nom' => 'L203', 'desc' => 'Fès - Marrakech', 'stops' => ['Fès', 'Meknès', 'Casablanca', 'Settat', 'Marrakech']],
            ['nom' => 'L204', 'desc' => 'Agadir - Laâyoune', 'stops' => ['Agadir', 'Laâyoune']],
            ['nom' => 'L205', 'desc' => 'Casablanca - Essaouira', 'stops' => ['Casablanca', 'El Jadida', 'Safi', 'Essaouira']],
            ['nom' => 'L206', 'desc' => 'Nador - Rabat', 'stops' => ['Nador', 'Al Hoceima', 'Taza', 'Fès', 'Rabat']],
            ['nom' => 'L207', 'desc' => 'Marrakech - Ouarzazate', 'stops' => ['Marrakech', 'Ouarzazate', 'Errachidia']],
        ];

        // 5. Generate Schedules for next 14 days
        $startDay = Carbon::today();
        
        foreach ($routesData as $rData) {
            $route = Route::create([
                'nom' => $rData['nom'],
                'description' => $rData['desc']
            ]);

            $etapes = [];
            foreach ($rData['stops'] as $index => $cityName) {
                $gare = Gare::where('ville_id', $villes[$cityName]->id)->first();
                $etapes[] = Etape::create([
                    'ordre' => $index + 1,
                    'heure_passage' => sprintf('%02d:00:00', 6 + ($index * 3)), // simplified step times
                    'route_id' => $route->id,
                    'gare_id' => $gare->id
                ]);
            }
            
            // Multiple departures per day (Morning, Afternoon, Night)
            $departureTimes = ['06:00', '09:30', '14:00', '18:30', '22:00'];
            
            for ($day = 0; $day < 14; $day++) {
                $date = $startDay->copy()->addDays($day);
                
                // Randomly pick 2 to 5 departures per day for this route to create variety
                $dailyDepartures = array_rand(array_flip($departureTimes), rand(2, 5));
                if (!is_array($dailyDepartures)) $dailyDepartures = [$dailyDepartures];

                foreach ($dailyDepartures as $time) {
                    $heureDepart = Carbon::parse($time);
                    $heureArrivee = $heureDepart->copy()->addHours((count($etapes) - 1) * 3);
                    
                    $prog = Programme::create([
                        'jour_depart' => $date,
                        'heure_depart' => $heureDepart->format('H:i:s'),
                        'heure_arrivee' => $heureArrivee->format('H:i:s'),
                        'route_id' => $route->id
                    ]);

                    // Assign a Bus randomly, but favor mixing types to test filters
                    // 50% Standard, 30% Confort, 20% Premium
                    $busTypeRand = rand(1, 100);
                    if ($busTypeRand <= 20) {
                        $busPool = collect($buses)->where('type', 'premium');
                    } elseif ($busTypeRand <= 50) {
                        $busPool = collect($buses)->where('type', 'confort');
                    } else {
                        $busPool = collect($buses)->where('type', 'standard');
                    }
                    $bus = $busPool->random();
                    
                    $driver = collect($drivers)->random();
                    
                    Assignment::create([
                        'programme_id' => $prog->id,
                        'bus_id' => $bus->id,
                        'employee_id' => $driver->id,
                        'date' => $date
                    ]);

                    // Base price calculation (cheaper for standard, higher base for confort/premium)
                    $baseTarif = ($bus->type === 'premium') ? 70 : (($bus->type === 'confort') ? 55 : 40);

                    // Create Segments for all possible combinations
                    for ($i = 0; $i < count($etapes); $i++) {
                        for ($j = $i + 1; $j < count($etapes); $j++) {
                            $steps = $j - $i;
                            
                            // Distance and Time
                            $dist = $steps * rand(80, 120); 
                            $dureeHours = $steps * 3;
                            
                            // Price: non-additive logic, slight discount for longer multi-stops
                            if ($steps == 1) {
                                $prix = $baseTarif;
                            } else {
                                $prix = ($baseTarif * $steps) * 0.85; 
                            }
                            
                            $segment = Segment::create([
                                'tarif' => round($prix, 2),
                                'duree_estimee' => sprintf('%02d:00:00', $dureeHours),
                                'distance_km' => $dist,
                                'bus_id' => $bus->id,
                                'programme_id' => $prog->id,
                                'etape_depart_id' => $etapes[$i]->id,
                                'etape_arrivee_id' => $etapes[$j]->id
                            ]);

                            // 6. Generate Some Random Fake Reservations for today and tomorrow to test occupancy
                            if ($day <= 2 && rand(1, 100) > 40) {
                                $numReservations = rand(5, $bus->capacite - 5);
                                $usedSeats = [];
                                
                                for ($k = 0; $k < $numReservations; $k++) {
                                    $seat = rand(1, $bus->capacite);
                                    while (in_array($seat, $usedSeats)) {
                                        $seat = rand(1, $bus->capacite);
                                    }
                                    $usedSeats[] = $seat;

                                    Reservation::create([
                                        'reference' => 'SATAS-' . strtoupper(Str::random(6)),
                                        'date_reservation' => now()->subDays(rand(1, 10)),
                                        'statut' => 'Confirmé',
                                        'siege_numero' => $seat,
                                        'user_id' => 2, // The client test user
                                        'segment_id' => $segment->id,
                                        'snack_box' => rand(0, 1),
                                        'insurance' => rand(0, 1),
                                        'base_price' => $segment->tarif,
                                        'extras_price' => 0,
                                        'total_price' => $segment->tarif,
                                        'payment_method' => 'card'
                                    ]);
                                }
                            }
                        }
                    }
                }
            }
        }

        // 8. Promo Codes
        PromoCode::create([
            'code' => 'SATAS10',
            'discount_percent' => 10,
            'max_uses' => 1000,
            'valid_from' => Carbon::today(),
            'valid_until' => Carbon::today()->addMonths(6),
            'is_active' => true
        ]);
        
        PromoCode::create([
            'code' => 'VACANCES2026',
            'discount_percent' => 25,
            'max_uses' => 500,
            'valid_from' => Carbon::today(),
            'valid_until' => Carbon::today()->addMonths(1),
            'is_active' => true
        ]);
    }
}