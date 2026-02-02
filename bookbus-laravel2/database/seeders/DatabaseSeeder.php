<?php

namespace Database\Seeders;

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
     
         $adminId = DB::table('users')->insertGetId([
            'name' => 'Admin SATAS',
            'email' => 'admin@satas.ma',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $driverIds = [];
        for ($i = 1; $i <= 10; $i++) {
            $driverIds[] = DB::table('users')->insertGetId([
                'name' => "Driver $i",
                'email' => "driver$i@satas.ma",
                'password' => Hash::make('password'),
                'role' => 'driver',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        $clientIds = [];
        for ($i = 1; $i <= 20; $i++) {
            $clientIds[] = DB::table('users')->insertGetId([
                'name' => "Client $i",
                'email' => "client$i@satas.ma",
                'password' => Hash::make('password'),
                'role' => 'client',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

         $employeeIds = [];
        foreach ($driverIds as $index => $uid) {
            $employeeIds[] = DB::table('employees')->insertGetId([
                'user_id' => $uid,
                'license_number' => "LIC-DRIVER-$index",
                'role' => 'driver',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

         $busIds = [];
        for ($i = 1; $i <= 10; $i++) {
            $busIds[] = DB::table('buses')->insertGetId([
                'plate_number' => "BUS-$i",
                'capacity' => 50,
                'type' => $i <= 5 ? 'Premium' : 'Standard',
                'status' => 'active',
                'amenities' => json_encode(['wifi' => true, 'usb' => true, 'wc' => true]),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

         $routes = [];
        $routes[] = DB::table('routes')->insertGetId([
            'route_code' => 'R101',
            'name' => 'Casablanca -> Settat -> Marrakech',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $routes[] = DB::table('routes')->insertGetId([
            'route_code' => 'R102',
            'name' => 'Agadir -> Marrakech -> Casablanca',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

         $stations = [
            ['name' => 'Casablanca Center'], 
            ['name' => 'Settat Central'], 
            ['name' => 'Marrakech Center'], 
            ['name' => 'Agadir Terminal']
        ];

        $stationIds = [];
        foreach ($stations as $station) {
            $stationIds[] = DB::table('stops')->insertGetId([
                'route_id' => $routes[0],
                'station_id' => null,  
                'order' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

         $segmentIds = [];
         $segmentIds[] = DB::table('segments')->insertGetId([
            'route_id' => $routes[0],
            'departure_stop_id' => $stationIds[0],
            'arrival_stop_id' => $stationIds[1],
            'price' => 50,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $segmentIds[] = DB::table('segments')->insertGetId([
            'route_id' => $routes[0],
            'departure_stop_id' => $stationIds[1],
            'arrival_stop_id' => $stationIds[2],
            'price' => 70,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

         $scheduleIds = [];
        $scheduleIds[] = DB::table('schedules')->insertGetId([
            'route_id' => $routes[0],
            'departure_time' => '08:00:00',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $scheduleIds[] = DB::table('schedules')->insertGetId([
            'route_id' => $routes[1],
            'departure_time' => '09:00:00',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

         $tripIds = [];
        $tripIds[] = DB::table('trips')->insertGetId([
            'schedule_id' => $scheduleIds[0],
            'trip_date' => Carbon::today(),
            'status' => 'scheduled',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $tripIds[] = DB::table('trips')->insertGetId([
            'schedule_id' => $scheduleIds[1],
            'trip_date' => Carbon::tomorrow(),
            'status' => 'scheduled',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

         foreach ($tripIds as $index => $tripId) {
            DB::table('assignments')->insert([
                'trip_id' => $tripId,
                'bus_id' => $busIds[$index],
                'driver_id' => $employeeIds[$index],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

         $seatNumber = 1;
        foreach ($tripIds as $tripId) {
            foreach ($clientIds as $clientId) {
                DB::table('bookings')->insert([
                    'user_id' => $clientId,
                    'trip_id' => $tripId,
                    'segment_id' => $segmentIds[array_rand($segmentIds)],
                    'seat_number' => $seatNumber,
                    'final_price' => rand(40, 100),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
                $seatNumber++;
            }
        }

        $this->command->info(' database seeded with Users, Employees, Routes, Stops, Segments, Buses, Schedules, Trips, Assignments, and Bookings!');
    }
}
