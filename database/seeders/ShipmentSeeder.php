<?php

namespace Database\Seeders;

use App\Models\CarbonEmissionFactor;
use App\Models\DeliveryException;
use App\Models\Location;
use App\Models\ServiceLevel;
use App\Models\Shipment;
use App\Models\TrackingEvent;
use App\Models\TransportLeg;
use App\Models\User;
use App\Services\CarbonEmissionCalculatorService;
use App\Services\DistanceCalculationService;
use Illuminate\Database\Seeder;

class ShipmentSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::where('email', 'admin@ecohorizon.test')->first();
        $operator = User::where('email', 'operator@ecohorizon.test')->first();
        $customer1 = User::where('email', 'customer@globalcargo.test')->first();
        $customer2 = User::where('email', 'buyer@bavariaindustries.de')->first();

        $doorToDoor = ServiceLevel::where('code', 'DOOR_TO_DOOR')->first();
        $doorToPort = ServiceLevel::where('code', 'DOOR_TO_PORT')->first();
        $portToPort = ServiceLevel::where('code', 'PORT_TO_PORT')->first();
        $b2bLevel = ServiceLevel::where('code', 'B2B')->first();

        // Locations
        $locCikarang = Location::where('code', 'IDCKR')->first();
        $locPriok = Location::where('code', 'IDTPP')->first();
        $locSingaporePort = Location::where('code', 'SGSIN')->first();
        $locChangiAir = Location::where('code', 'SIN')->first();
        $locFrankfurtAir = Location::where('code', 'FRA')->first();
        $locMunichDC = Location::where('code', 'DEMUC')->first();
        $locShanghai = Location::where('code', 'CNSHA')->first();
        $locRotterdam = Location::where('code', 'NLRTM')->first();
        $locSurabaya = Location::where('code', 'IDSUB')->first();
        $locWestJavaDC = Location::where('code', 'IDBKS')->first();
        $locTokyoHaneda = Location::where('code', 'HND')->first();
        $locLAX = Location::where('code', 'USLAX')->first();

        // Factors
        $seaFactor = CarbonEmissionFactor::where('transport_mode', 'sea')->where('is_default', true)->first();
        $seaFeeder = CarbonEmissionFactor::where('transport_mode', 'sea')->where('is_default', false)->first();
        $roadHeavy = CarbonEmissionFactor::where('transport_mode', 'land')->where('is_default', true)->first();
        $airFreighter = CarbonEmissionFactor::where('transport_mode', 'air')->where('is_default', true)->first();

        // =========================================================================
        // 1. SHIPMENT 1: FLAGSHIP MULTIMODAL (Road -> Sea -> Air -> Road)
        // =========================================================================
        $s1 = Shipment::create([
            'tracking_number' => 'EHG-20261029-JKTMUC',
            'customer_user_id' => $customer1->id,
            'assigned_operator_id' => $operator->id,
            'service_level_id' => $doorToDoor->id,
            'destination_type' => 'business_warehouse',
            'origin_location_id' => $locCikarang->id,
            'destination_location_id' => $locMunichDC->id,
            'sender_business_name' => 'PT Nusantara Global Export',
            'sender_contact_name' => 'Budi Pratama',
            'sender_phone' => '+62 812 3456 7890',
            'sender_email' => 'customer@globalcargo.test',
            'sender_address' => 'Kawasan Industri GIIC Blok AA No. 5, Cikarang, Jawa Barat, Indonesia',
            'receiver_business_name' => 'Bavaria Automotive GmbH',
            'receiver_contact_name' => 'Klaus Schneider',
            'receiver_phone' => '+49 89 2345 6789',
            'receiver_email' => 'buyer@bavariaindustries.de',
            'receiver_address' => 'Gewerbepark Nord 14, 80331 Munich, Germany',
            'cargo_description' => 'Precision Semiconductor Microchips & Sensor Controllers',
            'cargo_weight_tonnes' => 12.500,
            'cargo_volume_cbm' => 28.400,
            'package_count' => 16,
            'package_type' => 'Climate-Controlled Standard Pallets',
            'special_instructions' => 'Maintain temperature between 18C - 24C. Strict shock and tilt monitoring required.',
            'current_status' => 'IN_TRANSIT',
            'eta' => now()->addDays(2)->setHour(14)->setMinute(0),
            'original_eta' => now()->addDays(2)->setHour(14)->setMinute(0),
            'total_distance_km' => 0,
            'total_estimated_co2e_kg' => 0,
        ]);

        // Leg 1: Road Cikarang to Priok
        $leg1 = TransportLeg::create([
            'shipment_id' => $s1->id,
            'sequence_number' => 1,
            'transport_mode' => 'land',
            'origin_location_id' => $locCikarang->id,
            'destination_location_id' => $locPriok->id,
            'carrier_name' => 'Nusantara Express Trucking',
            'transport_reference' => 'Truck Plate B 9812 UXT',
            'planned_departure' => now()->subDays(4)->setHour(8)->setMinute(0),
            'actual_departure' => now()->subDays(4)->setHour(8)->setMinute(30),
            'estimated_arrival' => now()->subDays(4)->setHour(11)->setMinute(0),
            'actual_arrival' => now()->subDays(4)->setHour(11)->setMinute(15),
            'distance_km' => 58.00,
            'distance_source' => 'manual_operator_override',
            'emission_factor_id' => $roadHeavy->id,
            'status' => 'COMPLETED',
            'notes' => 'Container sealed at origin: Seal #IDN-992182.',
        ]);

        // Leg 2: Sea Priok to Singapore
        $leg2 = TransportLeg::create([
            'shipment_id' => $s1->id,
            'sequence_number' => 2,
            'transport_mode' => 'sea',
            'origin_location_id' => $locPriok->id,
            'destination_location_id' => $locSingaporePort->id,
            'carrier_name' => 'Samudera Shipping Line',
            'transport_reference' => 'Vessel MV Sinar Bima Voy 214E',
            'planned_departure' => now()->subDays(3)->setHour(14)->setMinute(0),
            'actual_departure' => now()->subDays(3)->setHour(15)->setMinute(10),
            'estimated_arrival' => now()->subDays(2)->setHour(18)->setMinute(0),
            'actual_arrival' => now()->subDays(2)->setHour(17)->setMinute(45),
            'distance_km' => 985.00,
            'distance_source' => 'standard_corridor',
            'emission_factor_id' => $seaFeeder->id,
            'status' => 'COMPLETED',
            'notes' => 'Smooth Malacca Strait passage. Transshipment connection met on schedule.',
        ]);

        // Leg 3: Air Singapore to Frankfurt
        $leg3 = TransportLeg::create([
            'shipment_id' => $s1->id,
            'sequence_number' => 3,
            'transport_mode' => 'air',
            'origin_location_id' => $locChangiAir->id,
            'destination_location_id' => $locFrankfurtAir->id,
            'carrier_name' => 'Lufthansa Cargo AG',
            'transport_reference' => 'Flight LH8402 (Boeing 777F)',
            'planned_departure' => now()->subDays(1)->setHour(22)->setMinute(0),
            'actual_departure' => now()->subDays(1)->setHour(22)->setMinute(40),
            'estimated_arrival' => now()->subHours(6)->setHour(6)->setMinute(30),
            'actual_arrival' => now()->subHours(6)->setHour(6)->setMinute(15),
            'distance_km' => 10280.00,
            'distance_source' => 'standard_corridor',
            'emission_factor_id' => $airFreighter->id,
            'status' => 'COMPLETED',
            'notes' => 'Direct intercontinental cargo flight. Cargo temperature maintained at 20.5C throughout flight.',
        ]);

        // Leg 4: Road Frankfurt to Munich
        $leg4 = TransportLeg::create([
            'shipment_id' => $s1->id,
            'sequence_number' => 4,
            'transport_mode' => 'land',
            'origin_location_id' => $locFrankfurtAir->id,
            'destination_location_id' => $locMunichDC->id,
            'carrier_name' => 'Dachser European Logistics',
            'transport_reference' => 'Truck Plate F-DX 7731 (Euro 6)',
            'planned_departure' => now()->subHours(2),
            'actual_departure' => now()->subHours(1)->setMinute(30),
            'estimated_arrival' => now()->addHours(4),
            'distance_km' => 395.00,
            'distance_source' => 'calculated_haversine',
            'emission_factor_id' => $roadHeavy->id,
            'status' => 'IN_TRANSIT',
            'notes' => 'Final road delivery leg on Autobahn A3/A9 towards Munich Logistics Park.',
        ]);

        // Events for Shipment 1
        TrackingEvent::create([
            'shipment_id' => $s1->id,
            'transport_leg_id' => $leg1->id,
            'event_type' => 'PICKUP',
            'status_after_event' => 'PICKED_UP',
            'event_time' => now()->subDays(4)->setHour(8)->setMinute(30),
            'location_name' => 'Cikarang Green Logistics Park',
            'latitude' => -6.3263,
            'longitude' => 107.1642,
            'description' => 'Cargo inspected, palletized, and secured onto feeder truck. Temperature data loggers activated.',
            'recorded_by_user_id' => $operator->id,
        ]);

        TrackingEvent::create([
            'shipment_id' => $s1->id,
            'transport_leg_id' => $leg1->id,
            'event_type' => 'ARRIVED_HUB',
            'status_after_event' => 'ARRIVED_AT_TRANSIT_HUB',
            'event_time' => now()->subDays(4)->setHour(11)->setMinute(15),
            'location_name' => 'Port of Tanjung Priok (Jakarta)',
            'latitude' => -6.1018,
            'longitude' => 106.8833,
            'description' => 'Container received at Tanjung Priok International Terminal Gate 3. Indonesian Export Customs inspection cleared.',
            'recorded_by_user_id' => $operator->id,
        ]);

        TrackingEvent::create([
            'shipment_id' => $s1->id,
            'transport_leg_id' => $leg2->id,
            'event_type' => 'DEPARTURE',
            'status_after_event' => 'IN_TRANSIT',
            'event_time' => now()->subDays(3)->setHour(15)->setMinute(10),
            'location_name' => 'Port of Tanjung Priok (Jakarta)',
            'latitude' => -6.1018,
            'longitude' => 106.8833,
            'description' => 'Loaded onto MV Sinar Bima Voy 214E. Vessel unmoored and departed Jakarta for Singapore transshipment hub.',
            'recorded_by_user_id' => $operator->id,
        ]);

        TrackingEvent::create([
            'shipment_id' => $s1->id,
            'transport_leg_id' => $leg2->id,
            'event_type' => 'ARRIVED_HUB',
            'status_after_event' => 'ARRIVED_AT_TRANSIT_HUB',
            'event_time' => now()->subDays(2)->setHour(17)->setMinute(45),
            'location_name' => 'PSA Singapore Terminals',
            'latitude' => 1.2644,
            'longitude' => 103.8228,
            'description' => 'Vessel berthed at Tanjong Pagar. Container discharged and bonded truck transfer initiated to Changi Airport.',
            'recorded_by_user_id' => $operator->id,
        ]);

        TrackingEvent::create([
            'shipment_id' => $s1->id,
            'transport_leg_id' => $leg3->id,
            'event_type' => 'DEPARTURE',
            'status_after_event' => 'IN_TRANSIT',
            'event_time' => now()->subDays(1)->setHour(22)->setMinute(40),
            'location_name' => 'Singapore Changi Airfreight Centre',
            'latitude' => 1.3644,
            'longitude' => 103.9915,
            'description' => 'Cargo pallet built onto aircraft ULD. Flight LH8402 took off from Changi Runway 02L bound for Frankfurt.',
            'recorded_by_user_id' => $operator->id,
        ]);

        TrackingEvent::create([
            'shipment_id' => $s1->id,
            'transport_leg_id' => $leg3->id,
            'event_type' => 'CUSTOMS_CLEARED',
            'status_after_event' => 'ARRIVED_AT_TRANSIT_HUB',
            'event_time' => now()->subHours(6)->setHour(6)->setMinute(15),
            'location_name' => 'Frankfurt CargoCity South',
            'latitude' => 50.0379,
            'longitude' => 8.5622,
            'description' => 'Flight touched down Frankfurt Main. German Zoll (Customs) EU import documentation released.',
            'recorded_by_user_id' => $operator->id,
        ]);

        TrackingEvent::create([
            'shipment_id' => $s1->id,
            'transport_leg_id' => $leg4->id,
            'event_type' => 'OUT_FOR_DELIVERY',
            'status_after_event' => 'IN_TRANSIT',
            'event_time' => now()->subHours(1)->setMinute(30),
            'location_name' => 'Frankfurt CargoCity South',
            'latitude' => 50.0379,
            'longitude' => 8.5622,
            'description' => 'Transferred to road transport. Truck F-DX 7731 en route to Munich destination facility.',
            'recorded_by_user_id' => $operator->id,
        ]);

        CarbonEmissionCalculatorService::recalculateShipmentEmissions($s1);

        // =========================================================================
        // 2. SHIPMENT 2: SEA FREIGHT WITH WEATHER DISRUPTION & DELAY
        // =========================================================================
        $s2 = Shipment::create([
            'tracking_number' => 'EHG-20261029-SHARTM',
            'customer_user_id' => $customer2->id,
            'assigned_operator_id' => $operator->id,
            'service_level_id' => $portToPort->id,
            'destination_type' => 'seaport_terminal',
            'origin_location_id' => $locShanghai->id,
            'destination_location_id' => $locRotterdam->id,
            'sender_business_name' => 'Shanghai Solar New Energy Co., Ltd.',
            'sender_contact_name' => 'Chen Wei',
            'sender_phone' => '+86 21 8877 6655',
            'sender_email' => 'export@shanghaisolar.cn',
            'sender_address' => 'Pudong High-Tech Free Trade Zone, Shanghai, China',
            'receiver_business_name' => 'Bavaria Automotive GmbH (Clean Energy Division)',
            'receiver_contact_name' => 'Klaus Schneider',
            'receiver_phone' => '+49 89 2345 6789',
            'receiver_email' => 'buyer@bavariaindustries.de',
            'receiver_address' => 'Port of Rotterdam Maasvlakte II Terminal, Netherlands',
            'cargo_description' => 'Photovoltaic Solar Modules & High-Capacity Inverters',
            'cargo_weight_tonnes' => 48.000,
            'cargo_volume_cbm' => 140.000,
            'package_count' => 4,
            'package_type' => '40ft High Cube Ocean Containers',
            'special_instructions' => 'Heavy industrial cargo. Port-to-Port collection by consignee logistics broker upon berthing.',
            'current_status' => 'DELAYED',
            'eta' => now()->addDays(5)->setHour(16)->setMinute(0),
            'original_eta' => now()->addDays(3)->setHour(12)->setMinute(0),
            'total_distance_km' => 0,
            'total_estimated_co2e_kg' => 0,
        ]);

        $legS2 = TransportLeg::create([
            'shipment_id' => $s2->id,
            'sequence_number' => 1,
            'transport_mode' => 'sea',
            'origin_location_id' => $locShanghai->id,
            'destination_location_id' => $locRotterdam->id,
            'carrier_name' => 'Maersk Line / Ocean Alliance',
            'transport_reference' => 'Vessel MV Horizon Grandeur Voy 092W',
            'planned_departure' => now()->subDays(18),
            'actual_departure' => now()->subDays(18)->addHours(4),
            'estimated_arrival' => now()->addDays(5),
            'distance_km' => 19450.00,
            'distance_source' => 'standard_corridor',
            'emission_factor_id' => $seaFactor->id,
            'status' => 'IN_TRANSIT',
            'notes' => 'Deep-sea container transit via Malacca Strait, Indian Ocean, and Suez Canal.',
        ]);

        TrackingEvent::create([
            'shipment_id' => $s2->id,
            'transport_leg_id' => $legS2->id,
            'event_type' => 'DEPARTURE',
            'status_after_event' => 'IN_TRANSIT',
            'event_time' => now()->subDays(18)->addHours(4),
            'location_name' => 'Port of Shanghai (Yangshan Deepwater Port)',
            'latitude' => 30.6278,
            'longitude' => 122.0628,
            'description' => 'Containers loaded on MV Horizon Grandeur. Departure cleared from Yangshan Berth #4.',
            'recorded_by_user_id' => $operator->id,
        ]);

        TrackingEvent::create([
            'shipment_id' => $s2->id,
            'transport_leg_id' => $legS2->id,
            'event_type' => 'DELAY_ANNOUNCED',
            'status_after_event' => 'DELAYED',
            'event_time' => now()->subHours(14),
            'location_name' => 'Arabian Sea / Gulf of Aden Corridor',
            'latitude' => 13.5000,
            'longitude' => 50.2000,
            'description' => 'Adverse severe tropical storm force winds (Force 9 Beaufort scale). Master initiated defensive slow steaming. ETA revised by +48 hours.',
            'recorded_by_user_id' => $operator->id,
        ]);

        // Active Delivery Exception
        DeliveryException::create([
            'shipment_id' => $s2->id,
            'transport_leg_id' => $legS2->id,
            'category' => 'weather_disruption',
            'severity' => 'high',
            'description' => 'Tropical depression over northern Arabian Sea causing 6-meter swell waves. Maritime safety protocols require reduced vessel engine speed to prevent cargo stack collapse.',
            'action_taken' => 'Vessel operator reported safe transit speed of 11 knots. Revised container discharge window communicated to Rotterdam terminal operators.',
            'assigned_operator_id' => $operator->id,
            'is_resolved' => false,
            'impact_on_eta_hours' => 48,
            'is_customer_visible' => true,
        ]);

        CarbonEmissionCalculatorService::recalculateShipmentEmissions($s2);

        // =========================================================================
        // 3. SHIPMENT 3: B2B WAREHOUSE DELIVERY WITH VERIFIED RECEIPT
        // =========================================================================
        $s3 = Shipment::create([
            'tracking_number' => 'EHG-20261029-SUBB2B',
            'customer_user_id' => $customer1->id,
            'assigned_operator_id' => $operator->id,
            'service_level_id' => $b2bLevel->id,
            'destination_type' => 'business_warehouse',
            'origin_location_id' => $locSurabaya->id,
            'destination_location_id' => $locWestJavaDC->id,
            'sender_business_name' => 'PT Rungkut Sejahtera Abadi',
            'sender_contact_name' => 'Hendra Gunawan',
            'sender_phone' => '+62 813 4455 6677',
            'sender_email' => 'logistics@rungkutabadi.co.id',
            'sender_address' => 'SIER Rungkut Industri IV No. 22, Surabaya, Jawa Timur, Indonesia',
            'receiver_business_name' => 'PT Mitra Distribusi Nusantara (B2B Fulfilment)',
            'receiver_contact_name' => 'Rina Marlina',
            'receiver_phone' => '+62 812 9988 7766',
            'receiver_email' => 'receiving@mitradistribusi.id',
            'receiver_address' => 'Kawasan Industri MM2100 Blok C-3, Cikarang Barat, Jawa Barat, Indonesia',
            'cargo_description' => 'Fast-Moving Consumer Goods (Sanitary & Personal Care)',
            'cargo_weight_tonnes' => 18.200,
            'cargo_volume_cbm' => 52.000,
            'package_count' => 24,
            'package_type' => 'Standard Industrial Pallets',
            'special_instructions' => 'B2B scheduled delivery window: 09:00 - 12:00. Requires dock receipt verification.',
            'current_status' => 'DELIVERED',
            'eta' => now()->subDays(1)->setHour(10)->setMinute(0),
            'original_eta' => now()->subDays(1)->setHour(10)->setMinute(0),
            'actual_delivery_date' => now()->subDays(1)->setHour(10)->setMinute(25),
            'proof_of_delivery_receiver_name' => 'Rina Marlina (Dock Receiving Officer)',
            'proof_of_delivery_notes' => 'All 24 pallets received in pristine condition. Security bolt seals verified intact (#ID-993821). Signed Goods Receipt Note GRN-2026-8819.',
            'proof_of_delivery_at' => now()->subDays(1)->setHour(10)->setMinute(25),
            'total_distance_km' => 0,
            'total_estimated_co2e_kg' => 0,
        ]);

        $legS3 = TransportLeg::create([
            'shipment_id' => $s3->id,
            'sequence_number' => 1,
            'transport_mode' => 'land',
            'origin_location_id' => $locSurabaya->id,
            'destination_location_id' => $locWestJavaDC->id,
            'carrier_name' => 'Jawa Trans Kargo Logistics',
            'transport_reference' => 'Truck Plate L 8821 AB (Wingbox Euro 5)',
            'planned_departure' => now()->subDays(2)->setHour(16)->setMinute(0),
            'actual_departure' => now()->subDays(2)->setHour(16)->setMinute(45),
            'estimated_arrival' => now()->subDays(1)->setHour(9)->setMinute(30),
            'actual_arrival' => now()->subDays(1)->setHour(9)->setMinute(50),
            'distance_km' => 762.00,
            'distance_source' => 'manual_operator_override',
            'emission_factor_id' => $roadHeavy->id,
            'status' => 'COMPLETED',
            'notes' => 'Traversed Trans-Java Toll Road corridor without delays.',
        ]);

        TrackingEvent::create([
            'shipment_id' => $s3->id,
            'transport_leg_id' => $legS3->id,
            'event_type' => 'PICKUP',
            'status_after_event' => 'PICKED_UP',
            'event_time' => now()->subDays(2)->setHour(16)->setMinute(45),
            'location_name' => 'Surabaya Rungkut Industrial Warehouse',
            'latitude' => -7.3292,
            'longitude' => 112.7564,
            'description' => 'Loaded at Surabaya manufacturing bay. Goods tally verified and security seal affixed.',
            'recorded_by_user_id' => $operator->id,
        ]);

        TrackingEvent::create([
            'shipment_id' => $s3->id,
            'transport_leg_id' => $legS3->id,
            'event_type' => 'HANDOVER',
            'status_after_event' => 'DELIVERED',
            'event_time' => now()->subDays(1)->setHour(10)->setMinute(25),
            'location_name' => 'West Java B2B Regional DC',
            'latitude' => -6.2941,
            'longitude' => 107.0872,
            'description' => 'Unloaded at Inbound Dock #4. Official receiving confirmation signed by Rina Marlina. Shipment fulfilled.',
            'recorded_by_user_id' => $operator->id,
            'proof_notes' => 'Signed GRN-2026-8819.',
        ]);

        CarbonEmissionCalculatorService::recalculateShipmentEmissions($s3);

        // =========================================================================
        // 4. SHIPMENT 4: AIR FREIGHT DOOR-TO-PORT WITH CRITICAL CUSTOMS EXCEPTION
        // =========================================================================
        $s4 = Shipment::create([
            'tracking_number' => 'EHG-20261029-HNDLAX',
            'customer_user_id' => $customer2->id,
            'assigned_operator_id' => $operator->id,
            'service_level_id' => $doorToPort->id,
            'destination_type' => 'airport_terminal',
            'origin_location_id' => $locTokyoHaneda->id,
            'destination_location_id' => $locLAX->id,
            'sender_business_name' => 'Kanto Precision Robotics Ltd.',
            'sender_contact_name' => 'Kenji Sato',
            'sender_phone' => '+81 3 3344 5566',
            'sender_email' => 'shipping@kantorobotics.jp',
            'sender_address' => 'Ota-ku Industrial Technology Center, Tokyo, Japan',
            'receiver_business_name' => 'Pacific BioMed Instruments Corp.',
            'receiver_contact_name' => 'Dr. Robert Miller',
            'receiver_phone' => '+1 310 555 4321',
            'receiver_email' => 'imports@pacificbiomed.com',
            'receiver_address' => 'Los Angeles Cargo Terminal, Pier 400 / Airport Yard, USA',
            'cargo_description' => 'Medical Diagnostic Imaging Lasers & Optics',
            'cargo_weight_tonnes' => 4.200,
            'cargo_volume_cbm' => 12.000,
            'package_count' => 8,
            'package_type' => 'Reinforced Wooden Crates',
            'special_instructions' => 'Door-to-Port delivery. Consignee customs broker must clear FDA declaration at terminal.',
            'current_status' => 'DELIVERY_EXCEPTION',
            'eta' => now()->addDays(3)->setHour(18)->setMinute(0),
            'original_eta' => now()->addHours(12)->setHour(18)->setMinute(0),
            'total_distance_km' => 0,
            'total_estimated_co2e_kg' => 0,
        ]);

        $legS4 = TransportLeg::create([
            'shipment_id' => $s4->id,
            'sequence_number' => 1,
            'transport_mode' => 'air',
            'origin_location_id' => $locTokyoHaneda->id,
            'destination_location_id' => $locLAX->id,
            'carrier_name' => 'All Nippon Airways Cargo (ANA)',
            'transport_reference' => 'Flight NH8472 (Boeing 777F)',
            'planned_departure' => now()->subHours(20),
            'actual_departure' => now()->subHours(19)->addMinutes(15),
            'estimated_arrival' => now()->subHours(8),
            'actual_arrival' => now()->subHours(7)->addMinutes(45),
            'distance_km' => 8810.00,
            'distance_source' => 'standard_corridor',
            'emission_factor_id' => $airFreighter->id,
            'status' => 'COMPLETED',
            'notes' => 'Trans-Pacific cargo flight arrived on schedule.',
        ]);

        TrackingEvent::create([
            'shipment_id' => $s4->id,
            'transport_leg_id' => $legS4->id,
            'event_type' => 'DEPARTURE',
            'status_after_event' => 'IN_TRANSIT',
            'event_time' => now()->subHours(19)->addMinutes(15),
            'location_name' => 'Tokyo Haneda Cargo Terminal',
            'latitude' => 35.5494,
            'longitude' => 139.7798,
            'description' => 'Flight NH8472 departed Haneda for Los Angeles International Cargo Terminal.',
            'recorded_by_user_id' => $operator->id,
        ]);

        TrackingEvent::create([
            'shipment_id' => $s4->id,
            'transport_leg_id' => $legS4->id,
            'event_type' => 'ARRIVED_TERMINAL',
            'status_after_event' => 'ARRIVED_AT_DESTINATION_TERMINAL',
            'event_time' => now()->subHours(7)->addMinutes(45),
            'location_name' => 'Port of Los Angeles (Pier 400)',
            'latitude' => 33.7432,
            'longitude' => -118.2673,
            'description' => 'Consignment discharged at destination terminal. Transferred to bonded customs warehouse for regulatory inspection.',
            'recorded_by_user_id' => $operator->id,
        ]);

        TrackingEvent::create([
            'shipment_id' => $s4->id,
            'transport_leg_id' => $legS4->id,
            'event_type' => 'EXCEPTION_LOGGED',
            'status_after_event' => 'DELIVERY_EXCEPTION',
            'event_time' => now()->subHours(4),
            'location_name' => 'Port of Los Angeles (Pier 400)',
            'latitude' => 33.7432,
            'longitude' => -118.2673,
            'description' => 'US Customs and Border Protection (CBP) issued a hold notice. Discrepancy detected in FDA Device Listing documentation.',
            'recorded_by_user_id' => $operator->id,
        ]);

        // Critical Exception
        DeliveryException::create([
            'shipment_id' => $s4->id,
            'transport_leg_id' => $legS4->id,
            'category' => 'customs_clearance_issue',
            'severity' => 'critical',
            'description' => 'US Customs and FDA hold placed on consignment due to missing 510(k) clearance number on medical laser customs entry invoice.',
            'action_taken' => 'Operator contacted shipper in Tokyo and US consignee broker. Awaiting revised technical compliance certificate from manufacturer.',
            'assigned_operator_id' => $operator->id,
            'is_resolved' => false,
            'impact_on_eta_hours' => 72,
            'is_customer_visible' => true,
        ]);

        CarbonEmissionCalculatorService::recalculateShipmentEmissions($s4);
    }
}

