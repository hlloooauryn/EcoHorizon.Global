<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Administrator
        User::firstOrCreate(
            ['email' => 'admin@ecohorizon.test'],
            [
                'name' => 'Alexander Vance',
                'password' => Hash::make('password123'),
                'role' => 'admin',
                'company_name' => 'EcoHorizon Global HQ',
                'phone' => '+62 21 5550 1201',
                'status' => 'active',
            ]
        );

        // 2. Logistics Operator
        User::firstOrCreate(
            ['email' => 'operator@ecohorizon.test'],
            [
                'name' => 'Sarah Jenkins',
                'password' => Hash::make('password123'),
                'role' => 'operator',
                'company_name' => 'EcoHorizon Operations Hub',
                'phone' => '+62 21 5550 1202',
                'status' => 'active',
            ]
        );

        // 3. Customer 1 (Indonesian Exporter)
        User::firstOrCreate(
            ['email' => 'customer@globalcargo.test'],
            [
                'name' => 'Budi Pratama',
                'password' => Hash::make('password123'),
                'role' => 'customer',
                'company_name' => 'PT Nusantara Global Export',
                'phone' => '+62 812 3456 7890',
                'status' => 'active',
            ]
        );

        // 4. Customer 2 (European Importer)
        User::firstOrCreate(
            ['email' => 'buyer@bavariaindustries.de'],
            [
                'name' => 'Klaus Schneider',
                'password' => Hash::make('password123'),
                'role' => 'customer',
                'company_name' => 'Bavaria Automotive GmbH',
                'phone' => '+49 89 2345 6789',
                'status' => 'active',
            ]
        );
    }
}

