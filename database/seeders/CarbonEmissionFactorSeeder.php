<?php

namespace Database\Seeders;

use App\Models\CarbonEmissionFactor;
use Illuminate\Database\Seeder;

class CarbonEmissionFactorSeeder extends Seeder
{
    public function run(): void
    {
        $factors = [
            // Sea Freight
            [
                'transport_mode' => 'sea',
                'subcategory' => 'Ultra Large Container Vessel (8,000+ TEU)',
                'factor_g_per_tonne_km' => 15.20,
                'source_reference' => 'GLEC Framework v3.0 / IMO 4th GHG Study',
                'description' => 'Ocean container transit utilizing slow-steaming operational profiles with scrubbers and energy-efficient hull design.',
                'effective_year' => 2024,
                'is_default' => true,
            ],
            [
                'transport_mode' => 'sea',
                'subcategory' => 'Feeder Container Vessel (<3,000 TEU)',
                'factor_g_per_tonne_km' => 28.50,
                'source_reference' => 'Clean Cargo Working Group / GLEC',
                'description' => 'Regional feeder maritime routes connecting transshipment hubs with island or shallow draft ports.',
                'effective_year' => 2024,
                'is_default' => false,
            ],

            // Land Freight
            [
                'transport_mode' => 'land',
                'subcategory' => 'Heavy Articulated Truck (>32 tonnes, Euro 6)',
                'factor_g_per_tonne_km' => 62.40,
                'source_reference' => 'UK Government GHG Conversion Factors (DEFRA 2024)',
                'description' => 'Long-haul interstate road transport under standard payload capacity with low-emission diesel powertrain.',
                'effective_year' => 2024,
                'is_default' => true,
            ],
            [
                'transport_mode' => 'land',
                'subcategory' => 'Electric Freight Rail Transit',
                'factor_g_per_tonne_km' => 18.10,
                'source_reference' => 'International Union of Railways (UIC) / EcoTransIT',
                'description' => 'Electrified rail container transit with high gross payload efficiency.',
                'effective_year' => 2024,
                'is_default' => false,
            ],
            [
                'transport_mode' => 'land',
                'subcategory' => 'Rigid Urban Delivery Truck (7.5 - 12 tonnes)',
                'factor_g_per_tonne_km' => 114.80,
                'source_reference' => 'DEFRA 2024 / Smart Freight Centre',
                'description' => 'Final-mile city distribution trucks with frequent stop-and-go driving conditions.',
                'effective_year' => 2024,
                'is_default' => false,
            ],

            // Air Freight
            [
                'transport_mode' => 'air',
                'subcategory' => 'Dedicated Freighter Aircraft (Boeing 777F / Long-Haul)',
                'factor_g_per_tonne_km' => 512.00,
                'source_reference' => 'ICAO Carbon Emissions Calculator / IATA 2024',
                'description' => 'Main-deck dedicated air freighter on intercontinental long-distance routes.',
                'effective_year' => 2024,
                'is_default' => true,
            ],
            [
                'transport_mode' => 'air',
                'subcategory' => 'Wide-body Passenger Belly Cargo',
                'factor_g_per_tonne_km' => 430.00,
                'source_reference' => 'GLEC Framework v3.0 / European Environment Agency',
                'description' => 'Lower-deck cargo transport on commercial passenger flights utilizing spare lift capacity.',
                'effective_year' => 2024,
                'is_default' => false,
            ],
        ];

        foreach ($factors as $factor) {
            CarbonEmissionFactor::firstOrCreate(
                [
                    'transport_mode' => $factor['transport_mode'],
                    'subcategory' => $factor['subcategory']
                ],
                $factor
            );
        }
    }
}
