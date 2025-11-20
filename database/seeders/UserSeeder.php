<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Super Admin (PRM)
        User::create([
            'name' => 'Admin PRM Surabaya',
            'email' => 'prm@sikemas.com',
            'password' => Hash::make('password'),
            'role' => 'superadmin',
            'organization' => 'PRM Surabaya',

        ]);

        // Create Admin for Masjid Al-Ikhlas
        User::create([
            'name' => 'Bendahara Masjid Al-Ikhlas',
            'email' => 'alikhlas@sikemas.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'organization' => 'Masjid Al-Ikhlas',

        ]);

        // Create Admin for Masjid Al-Falah
        User::create([
            'name' => 'Bendahara Masjid Al-Falah',
            'email' => 'alfalah@sikemas.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'organization' => 'Masjid Al-Falah',
        ]);

        // Create Admin for Masjid Nurul Huda
        User::create([
            'name' => 'Bendahara Masjid Nurul Huda',
            'email' => 'nurulhuda@sikemas.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'organization' => 'Masjid Nurul Huda',

        ]);
    }
}
