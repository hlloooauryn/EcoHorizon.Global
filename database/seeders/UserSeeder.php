<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $defaultPassword = Hash::make(config('auth.demo_passwords.admin', 'password123'));

        // 1. Administrator
        User::firstOrCreate(
            ['email' => 'admin@ecohorizon.com'],
            [
                'name' => 'Alexander Vance',
                'password' => $defaultPassword,
                'role' => 'admin',
                'company_name' => 'EcoHorizon Global HQ',
                'phone' => '+62 21 5550 1201',
                'status' => 'active',
            ]
        );

        // 2. Logistics Operator
        User::firstOrCreate(
            ['email' => 'operator@ecohorizon.com'],
            [
                'name' => 'Sarah Jenkins',
                'password' => $defaultPassword,
                'role' => 'operator',
                'company_name' => 'EcoHorizon Operations Hub',
                'phone' => '+62 21 5550 1202',
                'status' => 'active',
            ]
        );

        // 3. Customer 1 (Indonesian Exporter)
        User::firstOrCreate(
            ['email' => 'customer@globalcargo.com'],
            [
                'name' => 'Budi Pratama',
                'password' => $defaultPassword,
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
                'password' => $defaultPassword,
                'role' => 'customer',
                'company_name' => 'Bavaria Automotive GmbH',
                'phone' => '+49 89 2345 6789',
                'status' => 'active',
            ]
        );

        // 5. Global Customer 3: APAC Electronics
        User::firstOrCreate(
            ['email' => 'apac.logistics@samsung-logistics.com'],
            [
                'name' => 'Min-jun Kim',
                'password' => $defaultPassword,
                'role' => 'customer',
                'company_name' => 'Samsung High-Tech Logistics',
                'phone' => '+82 2 2255 0114',
                'status' => 'active',
            ]
        );

        // 6. Global Customer 4: Nordic CleanTech
        User::firstOrCreate(
            ['email' => 'freight@tesla-europe.com'],
            [
                'name' => 'Elena Richter',
                'password' => $defaultPassword,
                'role' => 'customer',
                'company_name' => 'Tesla Motors Gigafactory Berlin',
                'phone' => '+49 30 5566 7788',
                'status' => 'active',
            ]
        );

        // 7. Global Customer 5: Silicon Valley Tech
        User::firstOrCreate(
            ['email' => 'supply-chain@apple-ops.com'],
            [
                'name' => 'Marcus Sterling',
                'password' => $defaultPassword,
                'role' => 'customer',
                'company_name' => 'Apple Supply Chain Operations',
                'phone' => '+1 408 996 1010',
                'status' => 'active',
            ]
        );

        // 8. Global Customer 6: UK BioPharma
        User::firstOrCreate(
            ['email' => 'distribution@ukbiopharma.com'],
            [
                'name' => 'Arthur Pendelton',
                'password' => $defaultPassword,
                'role' => 'customer',
                'company_name' => 'UK BioPharma Global Distribution',
                'phone' => '+44 20 7946 0912',
                'status' => 'active',
            ]
        );

        // 9. Global Customer 7: Emirates Luxury Goods
        User::firstOrCreate(
            ['email' => 'procurement@emiratesluxury.com'],
            [
                'name' => 'Rashid Al-Maktoum',
                'password' => $defaultPassword,
                'role' => 'customer',
                'company_name' => 'Emirates Luxury Goods Ltd',
                'phone' => '+971 4 362 7700',
                'status' => 'active',
            ]
        );

        // 10. Global Customer 8: LatAm Agro Commodities
        User::firstOrCreate(
            ['email' => 'logistica@latam-commodities.com'],
            [
                'name' => 'Rodrigo Silva',
                'password' => $defaultPassword,
                'role' => 'customer',
                'company_name' => 'LatAm Agro-Industrial S.A.',
                'phone' => '+55 11 3844 5500',
                'status' => 'active',
            ]
        );
    }
}
