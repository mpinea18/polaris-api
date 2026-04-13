<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class SuperAdminSeeder extends Seeder
{
    public function run(): void
    {
        if (DB::table('users')->where('email', 'super@mmyj.com')->exists()) return;

        DB::table('users')->insert([
            'empresa_id'  => null,
            'name'        => 'Super Admin',
            'email'       => 'super@mmyj.com',
            'password'    => Hash::make('123'),
            'role'        => 'superadmin',
            'created_at'  => now(),
            'updated_at'  => now(),
        ]);

        if (DB::table('users')->where('email', 'admin@mmyj.com')->exists()) return;

        DB::table('users')->insert([
            [
                'empresa_id'  => 1,
                'name'        => 'Admin MMYJ',
                'email'       => 'admin@mmyj.com',
                'password'    => Hash::make('123'),
                'role'        => 'admin',
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
            [
                'empresa_id'  => 1,
                'name'        => 'Técnico Juan',
                'email'       => 'tecnico@mmyj.com',
                'password'    => Hash::make('123'),
                'role'        => 'tech',
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
            [
                'empresa_id'  => null,
                'name'        => 'Cliente Demo',
                'email'       => 'cliente@mmyj.com',
                'password'    => Hash::make('123'),
                'role'        => 'client',
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
        ]);
    }
}