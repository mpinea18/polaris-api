<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Drone;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Solo crea usuarios si no existen
        if (User::count() === 0) {
            $client = User::create([
                'name'     => 'Juan García',
                'email'    => 'cliente@mmyj.com',
                'password' => Hash::make('123'),
                'role'     => 'client',
                'status'   => 'active',
            ]);

            User::create([
                'name'     => 'Carlos Méndez',
                'email'    => 'tecnico@mmyj.com',
                'password' => Hash::make('123'),
                'role'     => 'tech',
                'status'   => 'active',
            ]);

            User::create([
                'name'     => 'María López',
                'email'    => 'admin@mmyj.com',
                'password' => Hash::make('123'),
                'role'     => 'admin',
                'status'   => 'active',
            ]);

            User::create([
                'name'     => 'Director General',
                'email'    => 'super@mmyj.com',
                'password' => Hash::make('123'),
                'role'     => 'superadmin',
                'status'   => 'active',
            ]);

            // Drones iniciales del cliente
            Drone::create([
                'user_id' => $client->id,
                'plate'   => 'POL-001',
                'model'   => 'DJI M30T',
                'hours'   => 0,
                'status'  => 'operational',
            ]);

            Drone::create([
                'user_id' => $client->id,
                'plate'   => 'POL-002',
                'model'   => 'Autel EVO MAX 4T',
                'hours'   => 0,
                'status'  => 'operational',
            ]);
        }

        // Catálogo de modelos UAS
        $this->call(DroneModelsSeeder::class);
    }
}