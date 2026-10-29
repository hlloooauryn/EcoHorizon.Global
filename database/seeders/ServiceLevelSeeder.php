<?php

namespace Database\Seeders;

use App\Models\ServiceLevel;
use Illuminate\Database\Seeder;

class ServiceLevelSeeder extends Seeder
{
    public function run(): void
    {
        $levels = [
            [
                'code' => 'DOOR_TO_DOOR',
                'name' => 'Door-to-Door Delivery',
                'description' => 'Complete end-to-end multimodal logistics from shipper doorstep to recipient premises, including first-mile collection, international transit, and final-mile proof of delivery.',
                'icon' => 'home',
                'requires_pickup' => true,
                'requires_recipient_delivery' => true,
                'is_active' => true,
            ],
            [
                'code' => 'DOOR_TO_PORT',
                'name' => 'Door-to-Port Delivery',
                'description' => 'Collection from shipper premises and transportation to the agreed destination seaport, airport, or inland customs terminal. Handover or pickup instructions provided upon arrival.',
                'icon' => 'anchor',
                'requires_pickup' => true,
                'requires_recipient_delivery' => false,
                'is_active' => true,
            ],
            [
                'code' => 'PORT_TO_PORT',
                'name' => 'Port-to-Port Freight',
                'description' => 'Direct terminal-to-terminal carriage between origin port and destination discharge facility. Shipper arranges origin delivery and consignee arranges terminal customs collection.',
                'icon' => 'ship',
                'requires_pickup' => false,
                'requires_recipient_delivery' => false,
                'is_active' => true,
            ],
            [
                'code' => 'B2B',
                'name' => 'B2B / Warehouse Delivery',
                'description' => 'Commercial shipment directly delivered to enterprise distribution centers, fulfillment warehouses, or business yards with mandatory receiving dock verification and goods receipt slip.',
                'icon' => 'building',
                'requires_pickup' => true,
                'requires_recipient_delivery' => true,
                'is_active' => true,
            ],
        ];

        foreach ($levels as $level) {
            ServiceLevel::firstOrCreate(['code' => $level['code']], $level);
        }
    }
}
