<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EmpresaSeeder extends Seeder
{
    public function run(): void
    {
        if (DB::table('empresas')->count() > 0) return;

        DB::table('empresas')->insert([
            [
                'nombre'          => 'MMYJ Drones SA',
                'slug'            => 'mmyj',
                'plan'            => 'enterprise',
                'activo'          => true,
                'email_contacto'  => 'admin@mmyj.com',
                'telefono'        => '3001234567',
                'created_at'      => now(),
                'updated_at'      => now(),
            ],
            [
                'nombre'          => 'Drones del Valle',
                'slug'            => 'drones-valle',
                'plan'            => 'pro',
                'activo'          => true,
                'email_contacto'  => 'admin@dronesvalle.com',
                'telefono'        => '3109876543',
                'created_at'      => now(),
                'updated_at'      => now(),
            ],
        ]);
    }
}