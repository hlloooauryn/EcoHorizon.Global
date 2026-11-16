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
use Illuminate\Database\Seeder;

class ShipmentSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::where('email', 'admin@ecohorizon.com')->first();
        $operator = User::where('email', 'operator@ecohorizon.com')->first();

        // Customer accounts
        $custBudi = User::where('email', 'customer@globalcargo.com')->first();
        $custKlaus = User::where('email', 'buyer@bavariaindustries.de')->first();
        $custSamsung = User::where('email', 'apac.logistics@samsung-logistics.com')->first() ?? $custBudi;
        $custTesla = User::where('email', 'freight@tesla-europe.com')->first() ?? $custKlaus;
        $custApple = User::where('email', 'supply-chain@apple-ops.com')->first() ?? $custBudi;
        $custBioPharma = User::where('email', 'distribution@ukbiopharma.com')->first() ?? $custKlaus;
        $custEmirates = User::where('email', 'procurement@emiratesluxury.com')->first() ?? $custBudi;
        $custLatAm = User::where('email', 'logistica@latam-commodities.com')->first() ?? $custKlaus;

        // Service Levels
        $b2bLevel = ServiceLevel::where('code', 'B2B')->first();
        $doorToPort = ServiceLevel::where('code', 'DOOR_TO_PORT')->first();
        $portToPort = ServiceLevel::where('code', 'PORT_TO_PORT')->first();

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
        $locHamburg = Location::where('code', 'DEHAM')->first();
        $locAntwerp = Location::where('code', 'BEANR')->first();
        $locBusan = Location::where('code', 'KRPUS')->first();
        $locJebelAli = Location::where('code', 'AEJEA')->first();
        $locSantos = Location::where('code', 'BRSSZ')->first();
        $locSydneyPort = Location::where('code', 'AUSYD')->first();
        $locNewYorkPort = Location::where('code', 'USNYC')->first();
        $locFelixstowe = Location::where('code', 'GBFXT')->first();
        $locHeathrow = Location::where('code', 'LHR')->first();
        $locDubaiAir = Location::where('code', 'DXB')->first();
        $locORD = Location::where('code', 'ORD')->first();
        $locIncheon = Location::where('code', 'ICN')->first();
        $locAMS = Location::where('code', 'AMS')->first();
        $locJFK = Location::where('code', 'JFK')->first();
        $locSydneyAir = Location::where('code', 'SYD')->first();
        $locHongKongAir = Location::where('code', 'HKG')->first();
        $locLondonGateway = Location::where('code', 'GBLON')->first();
        $locSiliconValley = Location::where('code', 'USPAO')->first();
        $locDubaiSouth = Location::where('code', 'AEDXB')->first();
        $locTokyoKoto = Location::where('code', 'JPKTO')->first();
        $locSaoPauloDC = Location::where('code', 'BRSAO')->first();
        $locCGK = Location::where('code', 'CGK')->first();

        // Emission Factors
        $seaDefault = CarbonEmissionFactor::where('transport_mode', 'sea')->where('is_default', true)->first();
        $roadDefault = CarbonEmissionFactor::where('transport_mode', 'land')->where('is_default', true)->first();
        $airDefault = CarbonEmissionFactor::where('transport_mode', 'air')->where('is_default', true)->first();

        // =========================================================================
        // 1. SHIPMENT 1: FLAGSHIP MULTIMODAL (Jakarta -> Munich)
        // =========================================================================
        $s1 = Shipment::create([
            'tracking_number' => 'EHG-20261029-JKTMUC',
            'customer_user_id' => $custBudi->id,
            'assigned_operator_id' => $operator->id,
            'service_level_id' => $b2bLevel->id,
            'destination_type' => 'business_warehouse',
            'origin_location_id' => $locCikarang->id,
            'destination_location_id' => $locMunichDC->id,
            'sender_business_name' => 'PT Nusantara Global Export',
            'sender_contact_name' => 'Budi Pratama',
            'sender_phone' => '+62 812 3456 7890',
            'sender_email' => 'customer@globalcargo.com',
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
            'special_instructions' => 'Maintain temperature between 18C - 24C. Strict shock monitoring.',
            'current_status' => 'IN_TRANSIT',
            'eta' => now()->addDays(2)->setHour(14)->setMinute(0),
            'original_eta' => now()->addDays(2)->setHour(14)->setMinute(0),
            'total_distance_km' => 0,
            'total_estimated_co2e_kg' => 0,
        ]);

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
            'emission_factor_id' => $roadDefault->id,
            'status' => 'COMPLETED',
        ]);

        $leg2 = TransportLeg::create([
            'shipment_id' => $s1->id,
            'sequence_number' => 2,
            'transport_mode' => 'sea',
            'origin_location_id' => $locPriok->id,
            'destination_location_id' => $locSingaporePort->id,
            'carrier_name' => 'Samudera Ocean Feeder',
            'transport_reference' => 'Vessel Sinar Sunda Voy 412N',
            'planned_departure' => now()->subDays(3)->setHour(16)->setMinute(0),
            'actual_departure' => now()->subDays(3)->setHour(17)->setMinute(10),
            'estimated_arrival' => now()->subDays(2)->setHour(6)->setMinute(0),
            'actual_arrival' => now()->subDays(2)->setHour(5)->setMinute(45),
            'distance_km' => 960.00,
            'emission_factor_id' => $seaDefault->id,
            'status' => 'COMPLETED',
        ]);

        $leg3 = TransportLeg::create([
            'shipment_id' => $s1->id,
            'sequence_number' => 3,
            'transport_mode' => 'air',
            'origin_location_id' => $locChangiAir->id,
            'destination_location_id' => $locFrankfurtAir->id,
            'carrier_name' => 'Singapore Airlines Cargo',
            'transport_reference' => 'Flight SQ 7398 (B747-400F)',
            'planned_departure' => now()->subDays(1)->setHour(22)->setMinute(0),
            'actual_departure' => now()->subDays(1)->setHour(22)->setMinute(35),
            'estimated_arrival' => now()->subHours(4)->setHour(6)->setMinute(0),
            'actual_arrival' => now()->subHours(4)->setHour(6)->setMinute(20),
            'distance_km' => 10280.00,
            'emission_factor_id' => $airDefault->id,
            'status' => 'COMPLETED',
        ]);

        $leg4 = TransportLeg::create([
            'shipment_id' => $s1->id,
            'sequence_number' => 4,
            'transport_mode' => 'land',
            'origin_location_id' => $locFrankfurtAir->id,
            'destination_location_id' => $locMunichDC->id,
            'carrier_name' => 'DB Schenker Logistics Germany',
            'transport_reference' => 'Freight Truck F-DX 7731 (Euro 6)',
            'planned_departure' => now()->subHours(2)->setHour(9)->setMinute(0),
            'actual_departure' => now()->subHours(2)->setHour(9)->setMinute(15),
            'estimated_arrival' => now()->addHours(3)->setHour(15)->setMinute(0),
            'distance_km' => 392.00,
            'emission_factor_id' => $roadDefault->id,
            'status' => 'IN_TRANSIT',
        ]);

        TrackingEvent::create([
            'shipment_id' => $s1->id,
            'transport_leg_id' => $leg4->id,
            'event_type' => 'in_transit',
            'status_after_event' => 'IN_TRANSIT',
            'event_time' => now()->subHours(1),
            'location_name' => 'Autobahn A3 / A9 Intermodal Hub Nuremberg, Germany',
            'latitude' => 49.4521,
            'longitude' => 11.0767,
            'description' => 'Heavy road transit progressing on schedule toward Munich Fulfillment Center.',
            'recorded_by_user_id' => $operator->id,
        ]);

        CarbonEmissionCalculatorService::recalculateShipmentEmissions($s1);

        // =========================================================================
        // 2. SHIPMENT 2: SEA FREIGHT (Shanghai -> Rotterdam)
        // =========================================================================
        $s2 = Shipment::create([
            'tracking_number' => 'EHG-20261029-SHARTM',
            'customer_user_id' => $custKlaus->id,
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
            'special_instructions' => 'Heavy industrial cargo. Port-to-Port collection by consignee.',
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
            'distance_km' => 19500.00,
            'emission_factor_id' => $seaDefault->id,
            'status' => 'IN_TRANSIT',
        ]);

        DeliveryException::create([
            'shipment_id' => $s2->id,
            'transport_leg_id' => $legS2->id,
            'category' => 'weather_disruption',
            'severity' => 'medium',
            'description' => 'Severe swell and North Sea tidal advisory near English Channel causing precautionary speed reduction.',
            'action_taken' => 'Carrier slowed cruising velocity from 19 knots to 13.5 knots for container stack stability.',
            'assigned_operator_id' => $operator->id,
            'is_resolved' => false,
            'impact_on_eta_hours' => 48,
        ]);

        TrackingEvent::create([
            'shipment_id' => $s2->id,
            'transport_leg_id' => $legS2->id,
            'event_type' => 'exception_occurred',
            'status_after_event' => 'DELAYED',
            'event_time' => now()->subHours(6),
            'location_name' => 'Bay of Biscay / Western Approaches',
            'latitude' => 46.5000,
            'longitude' => -5.2000,
            'description' => 'Vessel navigating heavy seas; ETA updated to Maasvlakte II berthing window.',
            'recorded_by_user_id' => $operator->id,
        ]);

        CarbonEmissionCalculatorService::recalculateShipmentEmissions($s2);

        // =========================================================================
        // 3. SHIPMENT 3: DELIVERED (Surabaya -> Cikarang)
        // =========================================================================
        $s3 = Shipment::create([
            'tracking_number' => 'EHG-20261029-SUBB2B',
            'customer_user_id' => $custBudi->id,
            'assigned_operator_id' => $operator->id,
            'service_level_id' => $b2bLevel->id,
            'destination_type' => 'business_warehouse',
            'origin_location_id' => $locSurabaya->id,
            'destination_location_id' => $locWestJavaDC->id,
            'sender_business_name' => 'PT Petro Kimia Jawa Timur',
            'sender_contact_name' => 'Hendra Gunawan',
            'sender_phone' => '+62 31 843 9000',
            'sender_email' => 'sales@petrokimia-jatim.co.id',
            'sender_address' => 'SIER Rungkut Industri IV No. 22, Surabaya',
            'receiver_business_name' => 'PT Mitra Distribusi Nusantara',
            'receiver_contact_name' => 'Rina Marlina',
            'receiver_phone' => '+62 21 898 3344',
            'receiver_email' => 'dc-receipts@mitradistribusi.co.id',
            'receiver_address' => 'Kawasan Industri MM2100 Blok C-3, Cikarang Barat',
            'cargo_description' => 'Specialty Food-Grade Biodegradable Packaging Granules',
            'cargo_weight_tonnes' => 24.000,
            'cargo_volume_cbm' => 52.000,
            'package_count' => 960,
            'package_type' => '25kg Poly Bags palletized',
            'current_status' => 'DELIVERED',
            'eta' => now()->subDays(2),
            'original_eta' => now()->subDays(2),
            'actual_delivery_date' => now()->subDays(2),
            'proof_of_delivery_receiver_name' => 'Rina Marlina (Dock Supv)',
            'proof_of_delivery_at' => now()->subDays(2),
            'total_distance_km' => 0,
            'total_estimated_co2e_kg' => 0,
        ]);

        TransportLeg::create([
            'shipment_id' => $s3->id,
            'sequence_number' => 1,
            'transport_mode' => 'land',
            'origin_location_id' => $locSurabaya->id,
            'destination_location_id' => $locWestJavaDC->id,
            'carrier_name' => 'Jawa Trans Kargo Logistics',
            'transport_reference' => 'Wingbox Truck L 8821 UK',
            'planned_departure' => now()->subDays(3),
            'actual_departure' => now()->subDays(3),
            'estimated_arrival' => now()->subDays(2),
            'actual_arrival' => now()->subDays(2),
            'distance_km' => 745.00,
            'emission_factor_id' => $roadDefault->id,
            'status' => 'COMPLETED',
        ]);

        CarbonEmissionCalculatorService::recalculateShipmentEmissions($s3);

        // =========================================================================
        // 4. SHIPMENT 4: AIR FREIGHT (Tokyo -> Los Angeles) - CRITICAL EXCEPTION
        // =========================================================================
        $s4 = Shipment::create([
            'tracking_number' => 'EHG-20261029-HNDLAX',
            'customer_user_id' => $custApple->id,
            'assigned_operator_id' => $operator->id,
            'service_level_id' => $doorToPort->id,
            'destination_type' => 'airport_terminal',
            'origin_location_id' => $locTokyoHaneda->id,
            'destination_location_id' => $locLAX->id,
            'sender_business_name' => 'Pacific BioMed Instruments Corp.',
            'sender_contact_name' => 'Takeshi Sato',
            'sender_phone' => '+81 3 5555 4321',
            'sender_email' => 't.sato@pacificbiomed.jp',
            'sender_address' => 'Ota-ku Industrial Technology Center, Tokyo',
            'receiver_business_name' => 'California BioTech Labs Inc.',
            'receiver_contact_name' => 'Dr. Robert Hawkins',
            'receiver_phone' => '+1 310 555 8899',
            'receiver_email' => 'receiving@californiabiotech.com',
            'receiver_address' => 'San Pedro Cargo Gate 3, Los Angeles',
            'cargo_description' => 'Liquid Nitrogen Cryo-Dewars Containing Live Stem Cell Lines',
            'cargo_weight_tonnes' => 1.850,
            'cargo_volume_cbm' => 4.200,
            'package_count' => 6,
            'package_type' => 'Cryogenic Vapor Dry Shippers',
            'special_instructions' => 'URGENT: Temperature must not exceed -150C.',
            'current_status' => 'DELIVERY_EXCEPTION',
            'eta' => now()->addHours(8),
            'original_eta' => now()->subHours(2),
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
            'transport_reference' => 'Flight NH 8412 (B777F)',
            'planned_departure' => now()->subHours(14),
            'actual_departure' => now()->subHours(13),
            'estimated_arrival' => now()->subHours(3),
            'actual_arrival' => now()->subHours(2),
            'distance_km' => 8815.00,
            'emission_factor_id' => $airDefault->id,
            'status' => 'COMPLETED',
        ]);

        DeliveryException::create([
            'shipment_id' => $s4->id,
            'transport_leg_id' => $legS4->id,
            'category' => 'customs_clearance_issue',
            'severity' => 'critical',
            'description' => 'US Fish and Wildlife Service / FDA holds biomedical consignment for verification of import biological transfer permit.',
            'action_taken' => 'Expedited brokerage agent dispatched to LAX cargo terminal with digital USDA CITES permits.',
            'assigned_operator_id' => $operator->id,
            'is_resolved' => false,
            'impact_on_eta_hours' => 10,
        ]);

        TrackingEvent::create([
            'shipment_id' => $s4->id,
            'transport_leg_id' => $legS4->id,
            'event_type' => 'exception_occurred',
            'status_after_event' => 'DELIVERY_EXCEPTION',
            'event_time' => now()->subHours(2),
            'location_name' => 'US Customs Examination Station, LAX Terminal 2',
            'latitude' => 33.9425,
            'longitude' => -118.4081,
            'description' => 'FDA Priority Hold placed on biological freight; dry ice topped up by handler.',
            'recorded_by_user_id' => $operator->id,
        ]);

        CarbonEmissionCalculatorService::recalculateShipmentEmissions($s4);

        // =========================================================================
        // 5. SHIPMENT 5: TRANS-PACIFIC MARITIME (Shanghai -> Los Angeles)
        // =========================================================================
        $s5 = Shipment::create([
            'tracking_number' => 'EHG-20261101-SHALAX',
            'customer_user_id' => $custTesla->id,
            'assigned_operator_id' => $operator->id,
            'service_level_id' => $portToPort->id,
            'destination_type' => 'seaport_terminal',
            'origin_location_id' => $locShanghai->id,
            'destination_location_id' => $locLAX->id,
            'sender_business_name' => 'CATL Battery Technology Co.',
            'sender_contact_name' => 'Liu Xiang',
            'sender_phone' => '+86 21 6889 1234',
            'sender_email' => 'shipping@catl-power.cn',
            'sender_address' => 'Lingang Industrial Zone, Shanghai, China',
            'receiver_business_name' => 'Tesla Motors Gigafactory California',
            'receiver_contact_name' => 'Elena Richter',
            'receiver_phone' => '+1 510 555 9000',
            'receiver_email' => 'freight@tesla-europe.com',
            'receiver_address' => 'Pier 400 Container Yard, Port of Los Angeles, USA',
            'cargo_description' => 'High-Density Prismatic LFP Battery Cells (UN3480 Class 9)',
            'cargo_weight_tonnes' => 64.500,
            'cargo_volume_cbm' => 165.000,
            'package_count' => 6,
            'package_type' => '40ft Heavy Duty ISO Containers',
            'special_instructions' => 'Dangerous goods Class 9 compliant. Continuous fire-suppression monitoring.',
            'current_status' => 'IN_TRANSIT',
            'eta' => now()->addDays(6)->setHour(18)->setMinute(0),
            'original_eta' => now()->addDays(6)->setHour(18)->setMinute(0),
        ]);

        TransportLeg::create([
            'shipment_id' => $s5->id,
            'sequence_number' => 1,
            'transport_mode' => 'sea',
            'origin_location_id' => $locShanghai->id,
            'destination_location_id' => $locLAX->id,
            'carrier_name' => 'Maersk Line / Ocean 3',
            'transport_reference' => 'Vessel MV Maersk Mc-Kinney Moller Voy 124E',
            'planned_departure' => now()->subDays(6),
            'actual_departure' => now()->subDays(6)->addHours(2),
            'estimated_arrival' => now()->addDays(6),
            'distance_km' => 11200.00,
            'emission_factor_id' => $seaDefault->id,
            'status' => 'IN_TRANSIT',
        ]);

        TrackingEvent::create([
            'shipment_id' => $s5->id,
            'event_type' => 'in_transit',
            'status_after_event' => 'IN_TRANSIT',
            'event_time' => now()->subHours(8),
            'location_name' => 'Mid-Pacific Great Circle Waypoint 38N 170W',
            'latitude' => 38.0000,
            'longitude' => -170.0000,
            'description' => 'Vessel cruising at 18.2 knots; container telemetry normal.',
            'recorded_by_user_id' => $operator->id,
        ]);

        CarbonEmissionCalculatorService::recalculateShipmentEmissions($s5);

        // =========================================================================
        // 6. SHIPMENT 6: AIR CARGO & DOOR COLD CHAIN (Dubai -> London)
        // =========================================================================
        $s6 = Shipment::create([
            'tracking_number' => 'EHG-20261102-DXBLHR',
            'customer_user_id' => $custBioPharma->id,
            'assigned_operator_id' => $operator->id,
            'service_level_id' => $b2bLevel->id,
            'destination_type' => 'fulfillment_center',
            'origin_location_id' => $locDubaiAir->id,
            'destination_location_id' => $locLondonGateway->id,
            'sender_business_name' => 'Gulf Life Sciences BioTech FZ-LLC',
            'sender_contact_name' => 'Dr. Zaid Al-Fayez',
            'sender_phone' => '+971 4 429 8800',
            'sender_email' => 'dispatch@gulflifesciences.ae',
            'sender_address' => 'Dubai Science Park, Al Barsha South, Dubai, UAE',
            'receiver_business_name' => 'UK BioPharma Distribution Centre',
            'receiver_contact_name' => 'Arthur Pendelton',
            'receiver_phone' => '+44 20 7946 0912',
            'receiver_email' => 'distribution@ukbiopharma.com',
            'receiver_address' => 'London Gateway Logistics Park, Unit 4B, Stanford-le-Hope, UK',
            'cargo_description' => 'Temperature-Sensitive Lyophilized Monoclonal Antibodies',
            'cargo_weight_tonnes' => 4.200,
            'cargo_volume_cbm' => 12.000,
            'package_count' => 8,
            'package_type' => 'Envirotainer RKN e1 Active Cold Containers',
            'special_instructions' => 'Strict 2C - 8C cold-chain maintenance with dual dataloggers.',
            'current_status' => 'IN_TRANSIT',
            'eta' => now()->addHours(14),
            'original_eta' => now()->addHours(14),
        ]);

        TransportLeg::create([
            'shipment_id' => $s6->id,
            'sequence_number' => 1,
            'transport_mode' => 'air',
            'origin_location_id' => $locDubaiAir->id,
            'destination_location_id' => $locHeathrow->id,
            'carrier_name' => 'Emirates SkyCargo',
            'transport_reference' => 'Flight EK 009 (B777-300ER)',
            'planned_departure' => now()->subHours(6),
            'actual_departure' => now()->subHours(5)->addMinutes(40),
            'estimated_arrival' => now()->addHours(2),
            'distance_km' => 5500.00,
            'emission_factor_id' => $airDefault->id,
            'status' => 'IN_TRANSIT',
        ]);

        TransportLeg::create([
            'shipment_id' => $s6->id,
            'sequence_number' => 2,
            'transport_mode' => 'land',
            'origin_location_id' => $locHeathrow->id,
            'destination_location_id' => $locLondonGateway->id,
            'carrier_name' => 'DHL Supply Chain UK Cold-Transport',
            'transport_reference' => 'Reefer Van LX73 EKY',
            'planned_departure' => now()->addHours(4),
            'estimated_arrival' => now()->addHours(8),
            'distance_km' => 78.00,
            'emission_factor_id' => $roadDefault->id,
            'status' => 'PENDING',
        ]);

        TrackingEvent::create([
            'shipment_id' => $s6->id,
            'event_type' => 'in_transit',
            'status_after_event' => 'IN_TRANSIT',
            'event_time' => now()->subHours(2),
            'location_name' => 'Overhead Central Europe Air Corridor FL360',
            'latitude' => 48.2082,
            'longitude' => 16.3738,
            'description' => 'Flight en route; temperature dataloggers holding steady at +4.2C.',
            'recorded_by_user_id' => $operator->id,
        ]);

        CarbonEmissionCalculatorService::recalculateShipmentEmissions($s6);

        // =========================================================================
        // 7. SHIPMENT 7: HIGH-TECH OUT FOR DELIVERY (Tokyo -> Frankfurt -> Munich)
        // =========================================================================
        $s7 = Shipment::create([
            'tracking_number' => 'EHG-20261103-TYOFRA',
            'customer_user_id' => $custKlaus->id,
            'assigned_operator_id' => $operator->id,
            'service_level_id' => $b2bLevel->id,
            'destination_type' => 'business_warehouse',
            'origin_location_id' => $locTokyoHaneda->id,
            'destination_location_id' => $locMunichDC->id,
            'sender_business_name' => 'Sony Semiconductor Solutions Corp.',
            'sender_contact_name' => 'Keiichi Morita',
            'sender_phone' => '+81 3 6714 0001',
            'sender_email' => 'sensors@sony-semi.co.jp',
            'sender_address' => 'Atsugi Technology Center, Kanagawa, Japan',
            'receiver_business_name' => 'Bavaria Automotive GmbH',
            'receiver_contact_name' => 'Klaus Schneider',
            'receiver_phone' => '+49 89 2345 6789',
            'receiver_email' => 'buyer@bavariaindustries.de',
            'receiver_address' => 'Gewerbepark Nord 14, 80331 Munich, Germany',
            'cargo_description' => 'Next-Generation Automotive CMOS Vision Sensor Chips',
            'cargo_weight_tonnes' => 6.800,
            'cargo_volume_cbm' => 18.000,
            'package_count' => 12,
            'package_type' => 'Anti-Static ESD Sealed Euro-Pallets',
            'current_status' => 'OUT_FOR_DELIVERY',
            'eta' => now()->addHours(3),
            'original_eta' => now()->addHours(4),
        ]);

        TransportLeg::create([
            'shipment_id' => $s7->id,
            'sequence_number' => 1,
            'transport_mode' => 'air',
            'origin_location_id' => $locTokyoHaneda->id,
            'destination_location_id' => $locFrankfurtAir->id,
            'carrier_name' => 'Lufthansa Cargo',
            'transport_reference' => 'Flight LH 8401 (B777F)',
            'planned_departure' => now()->subHours(20),
            'actual_departure' => now()->subHours(20),
            'estimated_arrival' => now()->subHours(6),
            'actual_arrival' => now()->subHours(6),
            'distance_km' => 9350.00,
            'emission_factor_id' => $airDefault->id,
            'status' => 'COMPLETED',
        ]);

        TransportLeg::create([
            'shipment_id' => $s7->id,
            'sequence_number' => 2,
            'transport_mode' => 'land',
            'origin_location_id' => $locFrankfurtAir->id,
            'destination_location_id' => $locMunichDC->id,
            'carrier_name' => 'DB Schenker Direct Express',
            'transport_reference' => 'Electric Heavy Truck M-SC 9100',
            'planned_departure' => now()->subHours(3),
            'actual_departure' => now()->subHours(3),
            'estimated_arrival' => now()->addHours(3),
            'distance_km' => 390.00,
            'emission_factor_id' => $roadDefault->id,
            'status' => 'IN_TRANSIT',
        ]);

        TrackingEvent::create([
            'shipment_id' => $s7->id,
            'event_type' => 'out_for_delivery',
            'status_after_event' => 'OUT_FOR_DELIVERY',
            'event_time' => now()->subMinutes(45),
            'location_name' => 'Munich North Outer Ring A9 Exit 74',
            'latitude' => 48.2100,
            'longitude' => 11.6000,
            'description' => 'Final mile electric transport en route to dock receipt gate.',
            'recorded_by_user_id' => $operator->id,
        ]);

        CarbonEmissionCalculatorService::recalculateShipmentEmissions($s7);

        // =========================================================================
        // 8. SHIPMENT 8: MARITIME SILK ROAD (Singapore -> Rotterdam)
        // =========================================================================
        $s8 = Shipment::create([
            'tracking_number' => 'EHG-20261104-SINRTM',
            'customer_user_id' => $custBudi->id,
            'assigned_operator_id' => $operator->id,
            'service_level_id' => $portToPort->id,
            'destination_type' => 'seaport_terminal',
            'origin_location_id' => $locSingaporePort->id,
            'destination_location_id' => $locRotterdam->id,
            'sender_business_name' => 'Olam Agri International Pte Ltd',
            'sender_contact_name' => 'Tan Wei Meng',
            'sender_phone' => '+65 6789 2200',
            'sender_email' => 'singapore.ops@olamagri.com',
            'sender_address' => '7B Keppel Road, Tanjong Pagar, Singapore',
            'receiver_business_name' => 'Barry Callebaut Europe N.V.',
            'receiver_contact_name' => 'Hendrik Van Der Meer',
            'receiver_phone' => '+31 10 445 7700',
            'receiver_email' => 'procurement@callebaut.nl',
            'receiver_address' => 'Maasvlakte II Bulk Pier, Rotterdam, Netherlands',
            'cargo_description' => 'Certified Sustainable Fair-Trade Cocoa Beans in Vented Containers',
            'cargo_weight_tonnes' => 84.000,
            'cargo_volume_cbm' => 210.000,
            'package_count' => 8,
            'package_type' => '40ft Ventilated Containers (Food Grade)',
            'current_status' => 'IN_TRANSIT',
            'eta' => now()->addDays(9),
            'original_eta' => now()->addDays(9),
        ]);

        TransportLeg::create([
            'shipment_id' => $s8->id,
            'sequence_number' => 1,
            'transport_mode' => 'sea',
            'origin_location_id' => $locSingaporePort->id,
            'destination_location_id' => $locRotterdam->id,
            'carrier_name' => 'CMA CGM',
            'transport_reference' => 'Vessel CMA CGM Jacques Saadé (LNG Powered)',
            'planned_departure' => now()->subDays(10),
            'actual_departure' => now()->subDays(10),
            'estimated_arrival' => now()->addDays(9),
            'distance_km' => 15600.00,
            'emission_factor_id' => $seaDefault->id,
            'status' => 'IN_TRANSIT',
        ]);

        TrackingEvent::create([
            'shipment_id' => $s8->id,
            'event_type' => 'in_transit',
            'status_after_event' => 'IN_TRANSIT',
            'event_time' => now()->subHours(12),
            'location_name' => 'Red Sea Bab el-Mandeb Safe Corridor',
            'latitude' => 12.5852,
            'longitude' => 43.3308,
            'description' => 'LNG ultra-large container ship navigating smoothly with escorts.',
            'recorded_by_user_id' => $operator->id,
        ]);

        CarbonEmissionCalculatorService::recalculateShipmentEmissions($s8);

        // =========================================================================
        // 9. SHIPMENT 9: SOUTH AMERICAN AGRO TO EUROPE (Santos -> Hamburg)
        // =========================================================================
        $s9 = Shipment::create([
            'tracking_number' => 'EHG-20261105-STSHAM',
            'customer_user_id' => $custLatAm->id,
            'assigned_operator_id' => $operator->id,
            'service_level_id' => $portToPort->id,
            'destination_type' => 'seaport_terminal',
            'origin_location_id' => $locSantos->id,
            'destination_location_id' => $locHamburg->id,
            'sender_business_name' => 'LatAm Agro-Industrial S.A.',
            'sender_contact_name' => 'Rodrigo Silva',
            'sender_phone' => '+55 11 3844 5500',
            'sender_email' => 'logistica@latam-commodities.com',
            'sender_address' => 'Rodovia Anhanguera km 26, Sao Paulo, Brazil',
            'receiver_business_name' => 'Tchibo Coffee Trading GmbH',
            'receiver_contact_name' => 'Markus Becker',
            'receiver_phone' => '+49 40 6387 0',
            'receiver_email' => 'green-coffee@tchibo.de',
            'receiver_address' => 'Burchardkai Terminal, Port of Hamburg, Germany',
            'cargo_description' => 'Specialty Single-Origin Bourbon Arabica Raw Green Coffee',
            'cargo_weight_tonnes' => 110.000,
            'cargo_volume_cbm' => 260.000,
            'package_count' => 10,
            'package_type' => '40ft Grain-Lined ISO Containers',
            'current_status' => 'IN_TRANSIT',
            'eta' => now()->addDays(7),
            'original_eta' => now()->addDays(7),
        ]);

        TransportLeg::create([
            'shipment_id' => $s9->id,
            'sequence_number' => 1,
            'transport_mode' => 'sea',
            'origin_location_id' => $locSantos->id,
            'destination_location_id' => $locHamburg->id,
            'carrier_name' => 'Hapag-Lloyd',
            'transport_reference' => 'Vessel MV Santos Express Voy 44N',
            'planned_departure' => now()->subDays(8),
            'actual_departure' => now()->subDays(8),
            'estimated_arrival' => now()->addDays(7),
            'distance_km' => 10300.00,
            'emission_factor_id' => $seaDefault->id,
            'status' => 'IN_TRANSIT',
        ]);

        TrackingEvent::create([
            'shipment_id' => $s9->id,
            'event_type' => 'in_transit',
            'status_after_event' => 'IN_TRANSIT',
            'event_time' => now()->subHours(18),
            'location_name' => 'Equatorial Atlantic Ocean 02N 28W',
            'latitude' => 2.0000,
            'longitude' => -28.0000,
            'description' => 'Vessel maintaining 17.5 knots cruise speed; cargo moisture sensors nominal.',
            'recorded_by_user_id' => $operator->id,
        ]);

        CarbonEmissionCalculatorService::recalculateShipmentEmissions($s9);

        // =========================================================================
        // 10. SHIPMENT 10: SILICON VALLEY TECH CORRIDOR (Incheon -> Chicago -> San Jose)
        // =========================================================================
        $s10 = Shipment::create([
            'tracking_number' => 'EHG-20261106-ICNORD',
            'customer_user_id' => $custSamsung->id,
            'assigned_operator_id' => $operator->id,
            'service_level_id' => $b2bLevel->id,
            'destination_type' => 'fulfillment_center',
            'origin_location_id' => $locIncheon->id,
            'destination_location_id' => $locSiliconValley->id,
            'sender_business_name' => 'Samsung High-Tech Logistics',
            'sender_contact_name' => 'Min-jun Kim',
            'sender_phone' => '+82 2 2255 0114',
            'sender_email' => 'apac.logistics@samsung-logistics.com',
            'sender_address' => 'Gonghang-ro, Jung-gu, Incheon, South Korea',
            'receiver_business_name' => 'Apple Supply Chain Operations',
            'receiver_contact_name' => 'Marcus Sterling',
            'receiver_phone' => '+1 408 996 1010',
            'receiver_email' => 'supply-chain@apple-ops.com',
            'receiver_address' => '3000 Innovation Way, Silicon Valley, San Jose, CA, USA',
            'cargo_description' => 'Ultra-Thin Flexible OLED Glass Displays & Sensor Arrays',
            'cargo_weight_tonnes' => 15.600,
            'cargo_volume_cbm' => 38.000,
            'package_count' => 22,
            'package_type' => 'Shock-Absorbing Composite Air Cargo Crates',
            'current_status' => 'IN_TRANSIT',
            'eta' => now()->addDays(1)->setHour(20),
            'original_eta' => now()->addDays(1)->setHour(20),
        ]);

        TransportLeg::create([
            'shipment_id' => $s10->id,
            'sequence_number' => 1,
            'transport_mode' => 'air',
            'origin_location_id' => $locIncheon->id,
            'destination_location_id' => $locORD->id,
            'carrier_name' => 'Korean Air Cargo',
            'transport_reference' => 'Flight KE 0279 (B747-8F)',
            'planned_departure' => now()->subHours(18),
            'actual_departure' => now()->subHours(17)->addMinutes(30),
            'estimated_arrival' => now()->subHours(4),
            'actual_arrival' => now()->subHours(4),
            'distance_km' => 10550.00,
            'emission_factor_id' => $airDefault->id,
            'status' => 'COMPLETED',
        ]);

        TransportLeg::create([
            'shipment_id' => $s10->id,
            'sequence_number' => 2,
            'transport_mode' => 'land',
            'origin_location_id' => $locORD->id,
            'destination_location_id' => $locSiliconValley->id,
            'carrier_name' => 'FedEx Custom Critical',
            'transport_reference' => 'Interstate Air-Ride Rig FX-8891',
            'planned_departure' => now()->subHours(2),
            'actual_departure' => now()->subHours(1)->addMinutes(45),
            'estimated_arrival' => now()->addDays(1)->setHour(20),
            'distance_km' => 3450.00,
            'emission_factor_id' => $roadDefault->id,
            'status' => 'IN_TRANSIT',
        ]);

        TrackingEvent::create([
            'shipment_id' => $s10->id,
            'event_type' => 'in_transit',
            'status_after_event' => 'IN_TRANSIT',
            'event_time' => now()->subMinutes(30),
            'location_name' => 'I-80 Westbound Transit Hub, Iowa',
            'latitude' => 41.5868,
            'longitude' => -93.6250,
            'description' => 'Cross-country air-ride truck en route with dual drivers.',
            'recorded_by_user_id' => $operator->id,
        ]);

        CarbonEmissionCalculatorService::recalculateShipmentEmissions($s10);

        // =========================================================================
        // 11. SHIPMENT 11: DELIVERED TRANS-ATLANTIC (Antwerp -> New York)
        // =========================================================================
        $s11 = Shipment::create([
            'tracking_number' => 'EHG-20261107-ANRNYC',
            'customer_user_id' => $custKlaus->id,
            'assigned_operator_id' => $operator->id,
            'service_level_id' => $portToPort->id,
            'destination_type' => 'seaport_terminal',
            'origin_location_id' => $locAntwerp->id,
            'destination_location_id' => $locNewYorkPort->id,
            'sender_business_name' => 'Atlas Copco Airpower N.V.',
            'sender_contact_name' => 'Jan Peeters',
            'sender_phone' => '+32 3 870 2111',
            'sender_email' => 'exports@atlascopco.be',
            'sender_address' => 'Boomsesteenweg 957, Wilrijk, Antwerp, Belgium',
            'receiver_business_name' => 'Hudson Industrial Equipment Corp.',
            'receiver_contact_name' => 'Daniel Miller',
            'receiver_phone' => '+1 201 555 4321',
            'receiver_email' => 'import@hudson-industrial.com',
            'receiver_address' => 'Port Newark Container Terminal, Corbin St, NJ, USA',
            'cargo_description' => 'Industrial Heavy-Duty Oil-Free Screw Compressors',
            'cargo_weight_tonnes' => 38.500,
            'cargo_volume_cbm' => 96.000,
            'package_count' => 4,
            'package_type' => '40ft Open Top Containers',
            'current_status' => 'DELIVERED',
            'eta' => now()->subDays(14),
            'original_eta' => now()->subDays(14),
            'actual_delivery_date' => now()->subDays(14),
            'proof_of_delivery_receiver_name' => 'Daniel Miller (Port Terminal Agent)',
            'proof_of_delivery_at' => now()->subDays(14),
        ]);

        TransportLeg::create([
            'shipment_id' => $s11->id,
            'sequence_number' => 1,
            'transport_mode' => 'sea',
            'origin_location_id' => $locAntwerp->id,
            'destination_location_id' => $locNewYorkPort->id,
            'carrier_name' => 'MSC (Mediterranean Shipping Co.)',
            'transport_reference' => 'Vessel MSC Oscar Voy 011W',
            'planned_departure' => now()->subDays(12),
            'actual_departure' => now()->subDays(12),
            'estimated_arrival' => now()->subDays(1),
            'actual_arrival' => now()->subDays(1),
            'distance_km' => 6250.00,
            'emission_factor_id' => $seaDefault->id,
            'status' => 'COMPLETED',
        ]);

        CarbonEmissionCalculatorService::recalculateShipmentEmissions($s11);

        // =========================================================================
        // 12. SHIPMENT 12: DELIVERED PACIFIC AIR (Sydney -> Singapore)
        // =========================================================================
        $s12 = Shipment::create([
            'tracking_number' => 'EHG-20261108-SYDSIN',
            'customer_user_id' => $custEmirates->id,
            'assigned_operator_id' => $operator->id,
            'service_level_id' => $doorToPort->id,
            'destination_type' => 'airport_terminal',
            'origin_location_id' => $locSydneyAir->id,
            'destination_location_id' => $locChangiAir->id,
            'sender_business_name' => 'Australian Premium Wagyu Co.',
            'sender_contact_name' => 'Liam Henderson',
            'sender_phone' => '+61 2 9876 5432',
            'sender_email' => 'export@wagyu-australia.com.au',
            'sender_address' => 'Keith Smith Ave, Sydney Kingsford Smith Airport, Australia',
            'receiver_business_name' => 'Marina Bay Gourmet Food Services',
            'receiver_contact_name' => 'Chloe Tan',
            'receiver_phone' => '+65 6222 8900',
            'receiver_email' => 'receiving@marinagourmet.sg',
            'receiver_address' => 'Changi Airfreight Centre SATS Cold-Store, Singapore',
            'cargo_description' => 'Chilled Vacuum-Sealed Marble Score 9+ Wagyu Cuts',
            'cargo_weight_tonnes' => 5.200,
            'cargo_volume_cbm' => 14.500,
            'package_count' => 180,
            'package_type' => 'Insulated Cold-Boxes with Real-Time Thermal Loggers',
            'current_status' => 'DELIVERED',
            'eta' => now()->subHours(10),
            'original_eta' => now()->subHours(10),
            'actual_delivery_date' => now()->subHours(9),
            'proof_of_delivery_receiver_name' => 'Chloe Tan (Cold-Store Manager)',
            'proof_of_delivery_at' => now()->subHours(9),
        ]);

        TransportLeg::create([
            'shipment_id' => $s12->id,
            'sequence_number' => 1,
            'transport_mode' => 'air',
            'origin_location_id' => $locSydneyAir->id,
            'destination_location_id' => $locChangiAir->id,
            'carrier_name' => 'Singapore Airlines Cargo',
            'transport_reference' => 'Flight SQ 7288 (B747-400F)',
            'planned_departure' => now()->subHours(18),
            'actual_departure' => now()->subHours(18),
            'estimated_arrival' => now()->subHours(10),
            'actual_arrival' => now()->subHours(10),
            'distance_km' => 6300.00,
            'emission_factor_id' => $airDefault->id,
            'status' => 'COMPLETED',
        ]);

        CarbonEmissionCalculatorService::recalculateShipmentEmissions($s12);

        // =========================================================================
        // 13. SHIPMENT 13: DELIVERED HEAVY EV COMPONENTS (Busan -> Los Angeles)
        // =========================================================================
        $s13 = Shipment::create([
            'tracking_number' => 'EHG-20261109-PUSLAX',
            'customer_user_id' => $custTesla->id,
            'assigned_operator_id' => $operator->id,
            'service_level_id' => $portToPort->id,
            'destination_type' => 'seaport_terminal',
            'origin_location_id' => $locBusan->id,
            'destination_location_id' => $locLAX->id,
            'sender_business_name' => 'Hyundai Mobis Global Electrification',
            'sender_contact_name' => 'Park Jae-sung',
            'sender_phone' => '+82 51 888 7766',
            'sender_email' => 'mobis-freight@hyundai.co.kr',
            'sender_address' => 'Busan New Port South Pier, Gangseo-gu, Busan, South Korea',
            'receiver_business_name' => 'Tesla Motors Gigafactory California',
            'receiver_contact_name' => 'Elena Richter',
            'receiver_phone' => '+1 510 555 9000',
            'receiver_email' => 'freight@tesla-europe.com',
            'receiver_address' => 'Port of Los Angeles Pier 400, San Pedro, CA, USA',
            'cargo_description' => 'Drive Inverter Power Electronics & Permanent Magnet Rotors',
            'cargo_weight_tonnes' => 88.000,
            'cargo_volume_cbm' => 220.000,
            'package_count' => 8,
            'package_type' => '40ft Heavy-Duty High-Cube Containers',
            'current_status' => 'DELIVERED',
            'eta' => now()->subHours(16),
            'original_eta' => now()->subHours(16),
            'actual_delivery_date' => now()->subHours(14),
            'proof_of_delivery_receiver_name' => 'POLA Pier 400 Stevedore Receiving',
            'proof_of_delivery_at' => now()->subHours(14),
        ]);

        TransportLeg::create([
            'shipment_id' => $s13->id,
            'sequence_number' => 1,
            'transport_mode' => 'sea',
            'origin_location_id' => $locBusan->id,
            'destination_location_id' => $locLAX->id,
            'carrier_name' => 'HMM (Hyundai Merchant Marine)',
            'transport_reference' => 'Vessel HMM Algeciras Voy 002E',
            'planned_departure' => now()->subDays(14),
            'actual_departure' => now()->subDays(14),
            'estimated_arrival' => now()->subHours(16),
            'actual_arrival' => now()->subHours(16),
            'distance_km' => 9650.00,
            'emission_factor_id' => $seaDefault->id,
            'status' => 'COMPLETED',
        ]);

        CarbonEmissionCalculatorService::recalculateShipmentEmissions($s13);

        // =========================================================================
        // 14. SHIPMENT 14: CUSTOMS CLEARANCE (Amsterdam -> New York)
        // =========================================================================
        $s14 = Shipment::create([
            'tracking_number' => 'EHG-20261110-AMSJFK',
            'customer_user_id' => $custBioPharma->id,
            'assigned_operator_id' => $operator->id,
            'service_level_id' => $doorToPort->id,
            'destination_type' => 'airport_terminal',
            'origin_location_id' => $locAMS->id,
            'destination_location_id' => $locJFK->id,
            'sender_business_name' => 'Philips Healthcare Medical Imaging',
            'sender_contact_name' => 'Bram Van Dijk',
            'sender_phone' => '+31 20 597 7777',
            'sender_email' => 'logistics@philips-healthcare.nl',
            'sender_address' => 'Anchoragelaan 38, Schiphol-Zuidoost, Netherlands',
            'receiver_business_name' => 'Mount Sinai Health System Logistics Hub',
            'receiver_contact_name' => 'Sarah Goldman',
            'receiver_phone' => '+1 212 555 7800',
            'receiver_email' => 'procurement@mountsinai.org',
            'receiver_address' => 'JFK Building 77, North Boundary Road, NY, USA',
            'cargo_description' => 'Precision Superconducting Magnets for MRI Scanning Suites',
            'cargo_weight_tonnes' => 8.400,
            'cargo_volume_cbm' => 22.000,
            'package_count' => 2,
            'package_type' => 'Cradled Heavy Shock-Isolated Air Crates',
            'current_status' => 'CUSTOMS_CLEARANCE',
            'eta' => now()->addHours(6),
            'original_eta' => now()->addHours(6),
        ]);

        TransportLeg::create([
            'shipment_id' => $s14->id,
            'sequence_number' => 1,
            'transport_mode' => 'air',
            'origin_location_id' => $locAMS->id,
            'destination_location_id' => $locJFK->id,
            'carrier_name' => 'KLM Cargo / Martinair',
            'transport_reference' => 'Flight KL 641F (B747-400ERF)',
            'planned_departure' => now()->subHours(10),
            'actual_departure' => now()->subHours(9)->addMinutes(30),
            'estimated_arrival' => now()->subHours(2),
            'actual_arrival' => now()->subHours(2),
            'distance_km' => 5850.00,
            'emission_factor_id' => $airDefault->id,
            'status' => 'COMPLETED',
        ]);

        TrackingEvent::create([
            'shipment_id' => $s14->id,
            'event_type' => 'customs_clearance',
            'status_after_event' => 'CUSTOMS_CLEARANCE',
            'event_time' => now()->subHours(1),
            'location_name' => 'JFK Air Cargo Import Customs Facility Building 77',
            'latitude' => 40.6413,
            'longitude' => -73.7781,
            'description' => 'Under CBP document verification and electronic EDI clearance processing.',
            'recorded_by_user_id' => $operator->id,
        ]);

        CarbonEmissionCalculatorService::recalculateShipmentEmissions($s14);

        // =========================================================================
        // 15. SHIPMENT 15: HIGH-DENSITY AIR FREIGHT (Hong Kong -> London)
        // =========================================================================
        $s15 = Shipment::create([
            'tracking_number' => 'EHG-20261111-HKGLHR',
            'customer_user_id' => $custApple->id,
            'assigned_operator_id' => $operator->id,
            'service_level_id' => $b2bLevel->id,
            'destination_type' => 'fulfillment_center',
            'origin_location_id' => $locHongKongAir->id,
            'destination_location_id' => $locLondonGateway->id,
            'sender_business_name' => 'Foxconn Global Logistics HK',
            'sender_contact_name' => 'Wong Ka-fai',
            'sender_phone' => '+852 2988 1234',
            'sender_email' => 'wong.kf@foxconn-logistics.hk',
            'sender_address' => 'SuperTerminal 1, Chek Lap Kok, Hong Kong',
            'receiver_business_name' => 'Apple European Distribution Logistics',
            'receiver_contact_name' => 'Marcus Sterling',
            'receiver_phone' => '+44 20 7123 4567',
            'receiver_email' => 'supply-chain@apple-ops.com',
            'receiver_address' => 'London Gateway Logistics Park Unit 1A, Stanford-le-Hope, UK',
            'cargo_description' => 'Flagship Smart Telematics Devices & Neural Processing Units',
            'cargo_weight_tonnes' => 12.800,
            'cargo_volume_cbm' => 34.000,
            'package_count' => 14,
            'package_type' => 'Secure RFID-Sealed Main-Deck Aircraft Pallets',
            'current_status' => 'IN_TRANSIT',
            'eta' => now()->addHours(11),
            'original_eta' => now()->addHours(11),
        ]);

        TransportLeg::create([
            'shipment_id' => $s15->id,
            'sequence_number' => 1,
            'transport_mode' => 'air',
            'origin_location_id' => $locHongKongAir->id,
            'destination_location_id' => $locHeathrow->id,
            'carrier_name' => 'Cathay Cargo',
            'transport_reference' => 'Flight CX 006 (B747-8F)',
            'planned_departure' => now()->subHours(6),
            'actual_departure' => now()->subHours(5)->addMinutes(50),
            'estimated_arrival' => now()->addHours(7),
            'distance_km' => 9620.00,
            'emission_factor_id' => $airDefault->id,
            'status' => 'IN_TRANSIT',
        ]);

        TransportLeg::create([
            'shipment_id' => $s15->id,
            'sequence_number' => 2,
            'transport_mode' => 'land',
            'origin_location_id' => $locHeathrow->id,
            'destination_location_id' => $locLondonGateway->id,
            'carrier_name' => 'Maritime Transport UK',
            'transport_reference' => 'Euro 6 EcoTruck BF23 WXT',
            'planned_departure' => now()->addHours(8),
            'estimated_arrival' => now()->addHours(11),
            'distance_km' => 78.00,
            'emission_factor_id' => $roadDefault->id,
            'status' => 'PENDING',
        ]);

        CarbonEmissionCalculatorService::recalculateShipmentEmissions($s15);

        // =========================================================================
        // 16. SHIPMENT 16: CONTINENTAL GREEN CORRIDOR (Hamburg -> Rotterdam)
        // =========================================================================
        $s16 = Shipment::create([
            'tracking_number' => 'EHG-20261112-HAMRTM',
            'customer_user_id' => $custKlaus->id,
            'assigned_operator_id' => $operator->id,
            'service_level_id' => $b2bLevel->id,
            'destination_type' => 'seaport_terminal',
            'origin_location_id' => $locHamburg->id,
            'destination_location_id' => $locRotterdam->id,
            'sender_business_name' => 'Siemens Energy AG Turbine Works',
            'sender_contact_name' => 'Wolfgang Fischer',
            'sender_phone' => '+49 40 2889 0',
            'sender_email' => 'turbines@siemens-energy.de',
            'sender_address' => 'Burchardkai Logistics Yard, Hamburg, Germany',
            'receiver_business_name' => 'Maasvlakte Offshore Wind Staging Facility',
            'receiver_contact_name' => 'Gerrit De Jong',
            'receiver_phone' => '+31 10 998 1200',
            'receiver_email' => 'staging@maasvlakte-wind.nl',
            'receiver_address' => 'Europaweg 902, Maasvlakte II, Rotterdam, Netherlands',
            'cargo_description' => 'Precision CNC Ceramic Bearings for 14MW Offshore Wind Turbines',
            'cargo_weight_tonnes' => 28.000,
            'cargo_volume_cbm' => 45.000,
            'package_count' => 6,
            'package_type' => 'Heavy Steel Pallet Skid Bases',
            'current_status' => 'DELIVERED',
            'eta' => now()->subDays(18),
            'original_eta' => now()->subDays(18),
            'actual_delivery_date' => now()->subDays(18),
            'proof_of_delivery_receiver_name' => 'Gerrit De Jong (Dock Supv)',
            'proof_of_delivery_at' => now()->subDays(18),
        ]);

        TransportLeg::create([
            'shipment_id' => $s16->id,
            'sequence_number' => 1,
            'transport_mode' => 'land',
            'origin_location_id' => $locHamburg->id,
            'destination_location_id' => $locRotterdam->id,
            'carrier_name' => 'DB Schenker EuroConnect',
            'transport_reference' => 'Heavy Truck HH-SE 4022',
            'planned_departure' => now()->subDays(3),
            'actual_departure' => now()->subDays(3),
            'estimated_arrival' => now()->subDays(2),
            'actual_arrival' => now()->subDays(2),
            'distance_km' => 485.00,
            'emission_factor_id' => $roadDefault->id,
            'status' => 'COMPLETED',
        ]);

        CarbonEmissionCalculatorService::recalculateShipmentEmissions($s16);

        // =========================================================================
        // 17. SHIPMENT 17: MIDDLE EAST SOLAR LOGISTICS (Dubai South -> Jebel Ali)
        // =========================================================================
        $s17 = Shipment::create([
            'tracking_number' => 'EHG-20261113-DXBJED',
            'customer_user_id' => $custEmirates->id,
            'assigned_operator_id' => $operator->id,
            'service_level_id' => $b2bLevel->id,
            'destination_type' => 'seaport_terminal',
            'origin_location_id' => $locDubaiSouth->id,
            'destination_location_id' => $locJebelAli->id,
            'sender_business_name' => 'Dubai Clean Energy Distribution FZE',
            'sender_contact_name' => 'Tariq Mansoor',
            'sender_phone' => '+971 4 814 1111',
            'sender_email' => 'tariq@dubaisouth-energy.ae',
            'sender_address' => 'Logistics District Phase 1, DWC, Dubai, UAE',
            'receiver_business_name' => 'DP World Jebel Ali Free Zone Hub',
            'receiver_contact_name' => 'Rashid Al-Maktoum',
            'receiver_phone' => '+971 4 362 7700',
            'receiver_email' => 'procurement@emiratesluxury.com',
            'receiver_address' => 'DP World Jebel Ali Terminal 1, Dubai, UAE',
            'cargo_description' => 'Grid-Scale Photovoltaic Battery Inverters & Transformers',
            'cargo_weight_tonnes' => 36.000,
            'cargo_volume_cbm' => 84.000,
            'package_count' => 12,
            'package_type' => 'Standard Heavy Wooden Crates',
            'current_status' => 'DELIVERED',
            'eta' => now()->subHours(22),
            'original_eta' => now()->subHours(22),
            'actual_delivery_date' => now()->subHours(20),
            'proof_of_delivery_receiver_name' => 'DP World Receiving Officer',
            'proof_of_delivery_at' => now()->subHours(20),
        ]);

        TransportLeg::create([
            'shipment_id' => $s17->id,
            'sequence_number' => 1,
            'transport_mode' => 'land',
            'origin_location_id' => $locDubaiSouth->id,
            'destination_location_id' => $locJebelAli->id,
            'carrier_name' => 'Aramex Freight Heavy Fleet',
            'transport_reference' => 'Truck Plate DXB-44190',
            'planned_departure' => now()->subDays(1),
            'actual_departure' => now()->subDays(1),
            'estimated_arrival' => now()->subHours(22),
            'actual_arrival' => now()->subHours(20),
            'distance_km' => 35.00,
            'emission_factor_id' => $roadDefault->id,
            'status' => 'COMPLETED',
        ]);

        CarbonEmissionCalculatorService::recalculateShipmentEmissions($s17);

        // =========================================================================
        // 18. SHIPMENT 18: SOUTHEAST ASIA VACCINE BRIDGE (Jakarta -> Singapore)
        // =========================================================================
        $s18 = Shipment::create([
            'tracking_number' => 'EHG-20261114-JKTSIN',
            'customer_user_id' => $custBudi->id,
            'assigned_operator_id' => $operator->id,
            'service_level_id' => $doorToPort->id,
            'destination_type' => 'airport_terminal',
            'origin_location_id' => $locCGK->id,
            'destination_location_id' => $locChangiAir->id,
            'sender_business_name' => 'PT Bio Farma (Persero) Bandung',
            'sender_contact_name' => 'Ahmad Suhendra',
            'sender_phone' => '+62 22 203 3755',
            'sender_email' => 'export@biofarma.co.id',
            'sender_address' => 'Soekarno-Hatta Int\'l Cargo Village, Tangerang, Indonesia',
            'receiver_business_name' => 'ASEAN Center for Disease Control Hub',
            'receiver_contact_name' => 'Wong Li Mei',
            'receiver_phone' => '+65 6477 1200',
            'receiver_email' => 'vaccines@asean-cdc.org',
            'receiver_address' => 'Changi Airfreight Centre, Airport Blvd, Singapore',
            'cargo_description' => 'Rotavirus & Polio Oral Vaccines (Cold Chain -20C)',
            'cargo_weight_tonnes' => 3.400,
            'cargo_volume_cbm' => 9.200,
            'package_count' => 64,
            'package_type' => 'Dry Ice Sub-Zero Shipper Cartons',
            'current_status' => 'DELIVERED',
            'eta' => now()->subHours(5),
            'original_eta' => now()->subHours(5),
            'actual_delivery_date' => now()->subHours(5),
            'proof_of_delivery_receiver_name' => 'Wong Li Mei (SATS Cold-Chain)',
            'proof_of_delivery_at' => now()->subHours(5),
        ]);

        TransportLeg::create([
            'shipment_id' => $s18->id,
            'sequence_number' => 1,
            'transport_mode' => 'air',
            'origin_location_id' => $locCGK->id,
            'destination_location_id' => $locChangiAir->id,
            'carrier_name' => 'Garuda Indonesia Cargo',
            'transport_reference' => 'Flight GA 822 (A330-300)',
            'planned_departure' => now()->subHours(8),
            'actual_departure' => now()->subHours(8),
            'estimated_arrival' => now()->subHours(5),
            'actual_arrival' => now()->subHours(5),
            'distance_km' => 890.00,
            'emission_factor_id' => $airDefault->id,
            'status' => 'COMPLETED',
        ]);

        CarbonEmissionCalculatorService::recalculateShipmentEmissions($s18);

        // =========================================================================
        // 19. SHIPMENT 19: SHORT-SEA CIRCULAR METALS (Felixstowe -> Rotterdam)
        // =========================================================================
        $s19 = Shipment::create([
            'tracking_number' => 'EHG-20261115-FXTAMS',
            'customer_user_id' => $custKlaus->id,
            'assigned_operator_id' => $operator->id,
            'service_level_id' => $portToPort->id,
            'destination_type' => 'seaport_terminal',
            'origin_location_id' => $locFelixstowe->id,
            'destination_location_id' => $locRotterdam->id,
            'sender_business_name' => 'British Steel & Circular Alloys Ltd',
            'sender_contact_name' => 'Geoffrey Davies',
            'sender_phone' => '+44 1394 670100',
            'sender_email' => 'sales@british-alloys.co.uk',
            'sender_address' => 'Trinity Terminal, The Docks, Felixstowe, UK',
            'receiver_business_name' => 'Rotterdam Circular Metals Smelter',
            'receiver_contact_name' => 'Karel Visser',
            'receiver_phone' => '+31 10 299 8811',
            'receiver_email' => 'scrap-receipts@rotterdam-metals.nl',
            'receiver_address' => 'Maasvlakte II Deepwater Berth, Rotterdam, Netherlands',
            'cargo_description' => 'Recycled High-Purity Aluminum Extrusions & Ingots',
            'cargo_weight_tonnes' => 52.000,
            'cargo_volume_cbm' => 110.000,
            'package_count' => 5,
            'package_type' => '40ft Standard ISO Sea Containers',
            'current_status' => 'DELIVERED',
            'eta' => now()->subDays(10),
            'original_eta' => now()->subDays(10),
            'actual_delivery_date' => now()->subDays(10),
            'proof_of_delivery_receiver_name' => 'Karel Visser (Berth Controller)',
            'proof_of_delivery_at' => now()->subDays(10),
        ]);

        TransportLeg::create([
            'shipment_id' => $s19->id,
            'sequence_number' => 1,
            'transport_mode' => 'sea',
            'origin_location_id' => $locFelixstowe->id,
            'destination_location_id' => $locRotterdam->id,
            'carrier_name' => 'DFDS Short-Sea Shipping',
            'transport_reference' => 'Vessel MV Flandria Seaways Voy 209',
            'planned_departure' => now()->subHours(24),
            'actual_departure' => now()->subHours(24),
            'estimated_arrival' => now()->subHours(12),
            'actual_arrival' => now()->subHours(11),
            'distance_km' => 220.00,
            'emission_factor_id' => $seaDefault->id,
            'status' => 'COMPLETED',
        ]);

        CarbonEmissionCalculatorService::recalculateShipmentEmissions($s19);

        // =========================================================================
        // 20. SHIPMENT 20: US INTERMODAL DATA CENTER TECH (Chicago -> San Jose)
        // =========================================================================
        $s20 = Shipment::create([
            'tracking_number' => 'EHG-20261116-ORDLAX',
            'customer_user_id' => $custApple->id,
            'assigned_operator_id' => $operator->id,
            'service_level_id' => $b2bLevel->id,
            'destination_type' => 'fulfillment_center',
            'origin_location_id' => $locORD->id,
            'destination_location_id' => $locSiliconValley->id,
            'sender_business_name' => 'Midwest Server Racks & Thermal Tech',
            'sender_contact_name' => 'Brett O\'Connor',
            'sender_phone' => '+1 312 555 8100',
            'sender_email' => 'orders@midwest-racks.com',
            'sender_address' => 'South Airfield Cargo Complex, 10000 W O\'Hare, Chicago, IL, USA',
            'receiver_business_name' => 'Apple Supply Chain Operations',
            'receiver_contact_name' => 'Marcus Sterling',
            'receiver_phone' => '+1 408 996 1010',
            'receiver_email' => 'supply-chain@apple-ops.com',
            'receiver_address' => '3000 Innovation Way, Silicon Valley, San Jose, CA, USA',
            'cargo_description' => 'High-Density Liquid-Cooled AI GPU Server Enclosures',
            'cargo_weight_tonnes' => 19.400,
            'cargo_volume_cbm' => 48.000,
            'package_count' => 10,
            'package_type' => 'Custom Padded Wooden Server Crates',
            'current_status' => 'IN_TRANSIT',
            'eta' => now()->addDays(2)->setHour(10),
            'original_eta' => now()->addDays(2)->setHour(10),
        ]);

        TransportLeg::create([
            'shipment_id' => $s20->id,
            'sequence_number' => 1,
            'transport_mode' => 'land',
            'origin_location_id' => $locORD->id,
            'destination_location_id' => $locSiliconValley->id,
            'carrier_name' => 'Swift Transportation Intermodal',
            'transport_reference' => 'Semi-Truck Plate IL-9901-T',
            'planned_departure' => now()->subHours(14),
            'actual_departure' => now()->subHours(13)->addMinutes(30),
            'estimated_arrival' => now()->addDays(2)->setHour(10),
            'distance_km' => 3450.00,
            'emission_factor_id' => $roadDefault->id,
            'status' => 'IN_TRANSIT',
        ]);

        TrackingEvent::create([
            'shipment_id' => $s20->id,
            'event_type' => 'in_transit',
            'status_after_event' => 'IN_TRANSIT',
            'event_time' => now()->subHours(1),
            'location_name' => 'Nebraska I-80 Logistics Waypoint',
            'latitude' => 40.8136,
            'longitude' => -96.7026,
            'description' => 'Highway transport moving smoothly across central corridor.',
            'recorded_by_user_id' => $operator->id,
        ]);

        CarbonEmissionCalculatorService::recalculateShipmentEmissions($s20);

        // =========================================================================
        // 21. SHIPMENT 21: INTRA-ASIA MARITIME EXPRESS (Shanghai -> Singapore)
        // =========================================================================
        $s21 = Shipment::create([
            'tracking_number' => 'EHG-20261117-SHASIN',
            'customer_user_id' => $custSamsung->id,
            'assigned_operator_id' => $operator->id,
            'service_level_id' => $portToPort->id,
            'destination_type' => 'seaport_terminal',
            'origin_location_id' => $locShanghai->id,
            'destination_location_id' => $locSingaporePort->id,
            'sender_business_name' => 'Shanghai Electric Heavy Machineries',
            'sender_contact_name' => 'Zhang Tao',
            'sender_phone' => '+86 21 5432 9900',
            'sender_email' => 'heavy-cargo@shanghai-electric.com',
            'sender_address' => 'Yangshan Island Phase 4 Automated Terminal, Shanghai, China',
            'receiver_business_name' => 'Jurong Port Offshore Energy Hub',
            'receiver_contact_name' => 'Alvin Lee',
            'receiver_phone' => '+65 6265 0666',
            'receiver_email' => 'offshore@jurongport.com',
            'receiver_address' => 'PSA Singapore Terminals, 7B Keppel Rd, Singapore',
            'cargo_description' => 'Offshore Wind Generation Planetary Gearboxes',
            'cargo_weight_tonnes' => 76.000,
            'cargo_volume_cbm' => 180.000,
            'package_count' => 6,
            'package_type' => '40ft Heavy Duty Flat Rack Containers',
            'current_status' => 'IN_TRANSIT',
            'eta' => now()->addDays(3)->setHour(8),
            'original_eta' => now()->addDays(3)->setHour(8),
        ]);

        TransportLeg::create([
            'shipment_id' => $s21->id,
            'sequence_number' => 1,
            'transport_mode' => 'sea',
            'origin_location_id' => $locShanghai->id,
            'destination_location_id' => $locSingaporePort->id,
            'carrier_name' => 'COSCO Shipping Lines',
            'transport_reference' => 'Vessel COSCO Glory Voy 044S',
            'planned_departure' => now()->subDays(3),
            'actual_departure' => now()->subDays(3),
            'estimated_arrival' => now()->addDays(3),
            'distance_km' => 4150.00,
            'emission_factor_id' => $seaDefault->id,
            'status' => 'IN_TRANSIT',
        ]);

        TrackingEvent::create([
            'shipment_id' => $s21->id,
            'event_type' => 'in_transit',
            'status_after_event' => 'IN_TRANSIT',
            'event_time' => now()->subHours(4),
            'location_name' => 'South China Sea Shipping Lane, 16N 114E',
            'latitude' => 16.0000,
            'longitude' => 114.0000,
            'description' => 'Vessel navigating at 19 knots; sea conditions calm.',
            'recorded_by_user_id' => $operator->id,
        ]);

        CarbonEmissionCalculatorService::recalculateShipmentEmissions($s21);

        // =========================================================================
        // 22. SHIPMENT 22: AIR CARGO AEROSPACE (Frankfurt -> Dubai)
        // =========================================================================
        $s22 = Shipment::create([
            'tracking_number' => 'EHG-20261118-FRADXB',
            'customer_user_id' => $custEmirates->id,
            'assigned_operator_id' => $operator->id,
            'service_level_id' => $doorToPort->id,
            'destination_type' => 'airport_terminal',
            'origin_location_id' => $locFrankfurtAir->id,
            'destination_location_id' => $locDubaiAir->id,
            'sender_business_name' => 'MTU Aero Engines AG Munich/Frankfurt',
            'sender_contact_name' => 'Stefan Huber',
            'sender_phone' => '+49 69 690 7000',
            'sender_email' => 'logistics@mtu.de',
            'sender_address' => 'CargoCity Sued Gebaeude 543, Frankfurt, Germany',
            'receiver_business_name' => 'Emirates Engineering Line Maintenance Center',
            'receiver_contact_name' => 'Rashid Al-Maktoum',
            'receiver_phone' => '+971 4 362 7700',
            'receiver_email' => 'procurement@emiratesluxury.com',
            'receiver_address' => 'Dubai Int\'l Cargo Mega Terminal, Dubai, UAE',
            'cargo_description' => 'GE9X Commercial Jet Engine High-Pressure Turbine Discs',
            'cargo_weight_tonnes' => 7.800,
            'cargo_volume_cbm' => 20.000,
            'package_count' => 3,
            'package_type' => 'Shock-Watch Monitored Steel Engine Crates',
            'current_status' => 'IN_TRANSIT',
            'eta' => now()->addHours(7),
            'original_eta' => now()->addHours(7),
        ]);

        TransportLeg::create([
            'shipment_id' => $s22->id,
            'sequence_number' => 1,
            'transport_mode' => 'air',
            'origin_location_id' => $locFrankfurtAir->id,
            'destination_location_id' => $locDubaiAir->id,
            'carrier_name' => 'Lufthansa Cargo',
            'transport_reference' => 'Flight LH 8220 (B777F)',
            'planned_departure' => now()->subHours(2),
            'actual_departure' => now()->subHours(1)->addMinutes(50),
            'estimated_arrival' => now()->addHours(7),
            'distance_km' => 4850.00,
            'emission_factor_id' => $airDefault->id,
            'status' => 'IN_TRANSIT',
        ]);

        CarbonEmissionCalculatorService::recalculateShipmentEmissions($s22);

        // =========================================================================
        // 23. SHIPMENT 23: BIO-ETHANOL OVERSEAS (Sao Paulo -> Hamburg) - DELAYED
        // =========================================================================
        $s23 = Shipment::create([
            'tracking_number' => 'EHG-20261119-SAOHAM',
            'customer_user_id' => $custLatAm->id,
            'assigned_operator_id' => $operator->id,
            'service_level_id' => $doorToPort->id,
            'destination_type' => 'seaport_terminal',
            'origin_location_id' => $locSaoPauloDC->id,
            'destination_location_id' => $locHamburg->id,
            'sender_business_name' => 'Raizen Bioenergia S.A.',
            'sender_contact_name' => 'Fernanda Lima',
            'sender_phone' => '+55 11 3890 2200',
            'sender_email' => 'exports@raizen.com.br',
            'sender_address' => 'Rodovia Anhanguera km 26, Sao Paulo, Brazil',
            'receiver_business_name' => 'Nordic Biofuels Terminal Hamburg',
            'receiver_contact_name' => 'Markus Becker',
            'receiver_phone' => '+49 40 3344 1100',
            'receiver_email' => 'terminal@nordic-biofuels.de',
            'receiver_address' => 'Burchardkai Liquid Berth, Hamburg, Germany',
            'cargo_description' => 'Certified Second-Generation Sugarcane Bio-Ethanol (ISO T11)',
            'cargo_weight_tonnes' => 62.000,
            'cargo_volume_cbm' => 78.000,
            'package_count' => 3,
            'package_type' => '20ft Stainless Steel ISO Tank Containers',
            'current_status' => 'DELAYED',
            'eta' => now()->addDays(8)->setHour(14),
            'original_eta' => now()->addDays(6)->setHour(10),
        ]);

        TransportLeg::create([
            'shipment_id' => $s23->id,
            'sequence_number' => 1,
            'transport_mode' => 'land',
            'origin_location_id' => $locSaoPauloDC->id,
            'destination_location_id' => $locSantos->id,
            'carrier_name' => 'Trans-Latam Road Logistics',
            'transport_reference' => 'Tanker Truck SP-88102',
            'planned_departure' => now()->subDays(6),
            'actual_departure' => now()->subDays(6),
            'estimated_arrival' => now()->subDays(5),
            'actual_arrival' => now()->subDays(5),
            'distance_km' => 85.00,
            'emission_factor_id' => $roadDefault->id,
            'status' => 'COMPLETED',
        ]);

        $legS23Sea = TransportLeg::create([
            'shipment_id' => $s23->id,
            'sequence_number' => 2,
            'transport_mode' => 'sea',
            'origin_location_id' => $locSantos->id,
            'destination_location_id' => $locHamburg->id,
            'carrier_name' => 'Hapag-Lloyd',
            'transport_reference' => 'Vessel MV Santos Star Voy 12N',
            'planned_departure' => now()->subDays(4),
            'actual_departure' => now()->subDays(3)->addHours(8),
            'estimated_arrival' => now()->addDays(8),
            'distance_km' => 10300.00,
            'emission_factor_id' => $seaDefault->id,
            'status' => 'IN_TRANSIT',
        ]);

        DeliveryException::create([
            'shipment_id' => $s23->id,
            'transport_leg_id' => $legS23Sea->id,
            'category' => 'transport_delay',
            'severity' => 'medium',
            'description' => 'Port of Santos berth crane congestion caused 32-hour delay in container vessel departure.',
            'action_taken' => 'Vessel master adjusted Atlantic throttle to optimize fuel consumption while maintaining revised ETA.',
            'assigned_operator_id' => $operator->id,
            'is_resolved' => false,
            'impact_on_eta_hours' => 48,
        ]);

        TrackingEvent::create([
            'shipment_id' => $s23->id,
            'event_type' => 'exception_occurred',
            'status_after_event' => 'DELAYED',
            'event_time' => now()->subDays(3),
            'location_name' => 'Port of Santos Container Terminal 2 Berth',
            'latitude' => -23.9535,
            'longitude' => -46.3008,
            'description' => 'Vessel unberthed behind schedule due to quay crane maintenance.',
            'recorded_by_user_id' => $operator->id,
        ]);

        CarbonEmissionCalculatorService::recalculateShipmentEmissions($s23);

        // =========================================================================
        // 24. SHIPMENT 24: JAPANESE ROBOTICS (Tokyo -> Frankfurt -> Munich)
        // =========================================================================
        $s24 = Shipment::create([
            'tracking_number' => 'EHG-20261120-JPTMUC',
            'customer_user_id' => $custKlaus->id,
            'assigned_operator_id' => $operator->id,
            'service_level_id' => $b2bLevel->id,
            'destination_type' => 'business_warehouse',
            'origin_location_id' => $locTokyoKoto->id,
            'destination_location_id' => $locMunichDC->id,
            'sender_business_name' => 'Fanuc Robotics Japan Corporation',
            'sender_contact_name' => 'Kenji Takahashi',
            'sender_phone' => '+81 3 3522 8000',
            'sender_email' => 'takahashi@fanuc-global.jp',
            'sender_address' => 'Shin-Kiba 3-Chome, Koto-ku, Tokyo, Japan',
            'receiver_business_name' => 'Bavaria Automotive GmbH',
            'receiver_contact_name' => 'Klaus Schneider',
            'receiver_phone' => '+49 89 2345 6789',
            'receiver_email' => 'buyer@bavariaindustries.de',
            'receiver_address' => 'Gewerbepark Nord 14, 80331 Munich, Germany',
            'cargo_description' => 'Precision 6-Axis Welding Automation Articulated Robots',
            'cargo_weight_tonnes' => 11.200,
            'cargo_volume_cbm' => 31.000,
            'package_count' => 6,
            'package_type' => 'Heavy Impact-Resistant Aluminum Crates',
            'current_status' => 'IN_TRANSIT',
            'eta' => now()->addDays(1)->setHour(16),
            'original_eta' => now()->addDays(1)->setHour(16),
        ]);

        TransportLeg::create([
            'shipment_id' => $s24->id,
            'sequence_number' => 1,
            'transport_mode' => 'air',
            'origin_location_id' => $locTokyoHaneda->id,
            'destination_location_id' => $locFrankfurtAir->id,
            'carrier_name' => 'All Nippon Airways Cargo (ANA)',
            'transport_reference' => 'Flight NH 8410 (B777F)',
            'planned_departure' => now()->subHours(16),
            'actual_departure' => now()->subHours(16),
            'estimated_arrival' => now()->subHours(2),
            'actual_arrival' => now()->subHours(2),
            'distance_km' => 9350.00,
            'emission_factor_id' => $airDefault->id,
            'status' => 'COMPLETED',
        ]);

        TransportLeg::create([
            'shipment_id' => $s24->id,
            'sequence_number' => 2,
            'transport_mode' => 'land',
            'origin_location_id' => $locFrankfurtAir->id,
            'destination_location_id' => $locMunichDC->id,
            'carrier_name' => 'DHL Freight Germany',
            'transport_reference' => 'Euro 6 Truck F-DH 501',
            'planned_departure' => now()->addHours(2),
            'estimated_arrival' => now()->addDays(1)->setHour(16),
            'distance_km' => 392.00,
            'emission_factor_id' => $roadDefault->id,
            'status' => 'PENDING',
        ]);

        CarbonEmissionCalculatorService::recalculateShipmentEmissions($s24);

        // =========================================================================
        // 25. SHIPMENT 25: CLEAN TECH CRITICAL MINERALS (Sydney -> Los Angeles)
        // =========================================================================
        $s25 = Shipment::create([
            'tracking_number' => 'EHG-20261122-SYDLAX',
            'customer_user_id' => $custTesla->id,
            'assigned_operator_id' => $operator->id,
            'service_level_id' => $portToPort->id,
            'destination_type' => 'seaport_terminal',
            'origin_location_id' => $locSydneyPort->id,
            'destination_location_id' => $locLAX->id,
            'sender_business_name' => 'Pilbara Minerals Ltd Australia',
            'sender_contact_name' => 'Callum Mitchell',
            'sender_phone' => '+61 2 9296 4999',
            'sender_email' => 'export@pilbaraminerals.com.au',
            'sender_address' => 'Brotherson Dock, Port Botany, Sydney, Australia',
            'receiver_business_name' => 'Tesla Motors Gigafactory California',
            'receiver_contact_name' => 'Elena Richter',
            'receiver_phone' => '+1 510 555 9000',
            'receiver_email' => 'freight@tesla-europe.com',
            'receiver_address' => 'Pier 400 Container Berth, Port of Los Angeles, USA',
            'cargo_description' => 'Battery-Grade Purified Lithium Hydroxide Monohydrate',
            'cargo_weight_tonnes' => 72.000,
            'cargo_volume_cbm' => 140.000,
            'package_count' => 6,
            'package_type' => '40ft Heavy Duty Sealed Bulk Containers',
            'current_status' => 'IN_TRANSIT',
            'eta' => now()->addDays(11),
            'original_eta' => now()->addDays(11),
        ]);

        TransportLeg::create([
            'shipment_id' => $s25->id,
            'sequence_number' => 1,
            'transport_mode' => 'sea',
            'origin_location_id' => $locSydneyPort->id,
            'destination_location_id' => $locLAX->id,
            'carrier_name' => 'ANL Container Line / CMA CGM',
            'transport_reference' => 'Vessel ANL Warragul Voy 018E',
            'planned_departure' => now()->subDays(5),
            'actual_departure' => now()->subDays(5),
            'estimated_arrival' => now()->addDays(11),
            'distance_km' => 12100.00,
            'emission_factor_id' => $seaDefault->id,
            'status' => 'IN_TRANSIT',
        ]);

        TrackingEvent::create([
            'shipment_id' => $s25->id,
            'event_type' => 'in_transit',
            'status_after_event' => 'IN_TRANSIT',
            'event_time' => now()->subHours(9),
            'location_name' => 'South Pacific Ocean Coral Sea Transit Corridor',
            'latitude' => -18.0000,
            'longitude' => 165.0000,
            'description' => 'Vessel making good speed at 18.0 knots; container seals intact.',
            'recorded_by_user_id' => $operator->id,
        ]);

        CarbonEmissionCalculatorService::recalculateShipmentEmissions($s25);

        // =========================================================================
        // 26. SHIPMENT 26: DELIVERED MULTIMODAL (Cikarang -> Priok -> Singapore)
        // =========================================================================
        $s26 = Shipment::create([
            'tracking_number' => 'EHG-20261123-CKRSIN',
            'customer_user_id' => $custBudi->id,
            'assigned_operator_id' => $operator->id,
            'service_level_id' => $doorToPort->id,
            'destination_type' => 'seaport_terminal',
            'origin_location_id' => $locCikarang->id,
            'destination_location_id' => $locSingaporePort->id,
            'sender_business_name' => 'PT Nusantara Global Export',
            'sender_contact_name' => 'Budi Pratama',
            'sender_phone' => '+62 812 3456 7890',
            'sender_email' => 'customer@globalcargo.com',
            'sender_address' => 'Kawasan Industri GIIC Blok AA No. 5, Cikarang, Indonesia',
            'receiver_business_name' => 'Panasonic Industrial Devices Singapore',
            'receiver_contact_name' => 'Kenneth Koh',
            'receiver_phone' => '+65 6299 1100',
            'receiver_email' => 'koh.kenneth@panasonic.com.sg',
            'receiver_address' => 'PSA Singapore Terminals, 7B Keppel Rd, Singapore',
            'cargo_description' => 'Precision Automotive Micro-Relays & Copper Harnesses',
            'cargo_weight_tonnes' => 22.000,
            'cargo_volume_cbm' => 48.000,
            'package_count' => 840,
            'package_type' => 'Export Corrugated Boxes on Heat-Treated Pallets',
            'current_status' => 'DELIVERED',
            'eta' => now()->subDays(1)->setHour(12),
            'original_eta' => now()->subDays(1)->setHour(12),
            'actual_delivery_date' => now()->subDays(1)->setHour(11),
            'proof_of_delivery_receiver_name' => 'Kenneth Koh (Receiving Supervisor)',
            'proof_of_delivery_at' => now()->subDays(1)->setHour(11),
        ]);

        TransportLeg::create([
            'shipment_id' => $s26->id,
            'sequence_number' => 1,
            'transport_mode' => 'land',
            'origin_location_id' => $locCikarang->id,
            'destination_location_id' => $locPriok->id,
            'carrier_name' => 'Nusantara Express Trucking',
            'transport_reference' => 'Truck B 9012 KFX',
            'planned_departure' => now()->subDays(3),
            'actual_departure' => now()->subDays(3),
            'estimated_arrival' => now()->subDays(3)->addHours(3),
            'actual_arrival' => now()->subDays(3)->addHours(3),
            'distance_km' => 58.00,
            'emission_factor_id' => $roadDefault->id,
            'status' => 'COMPLETED',
        ]);

        TransportLeg::create([
            'shipment_id' => $s26->id,
            'sequence_number' => 2,
            'transport_mode' => 'sea',
            'origin_location_id' => $locPriok->id,
            'destination_location_id' => $locSingaporePort->id,
            'carrier_name' => 'Samudera Ocean Feeder',
            'transport_reference' => 'Vessel Sinar Batam Voy 104',
            'planned_departure' => now()->subDays(2),
            'actual_departure' => now()->subDays(2),
            'estimated_arrival' => now()->subDays(1),
            'actual_arrival' => now()->subDays(1),
            'distance_km' => 960.00,
            'emission_factor_id' => $seaDefault->id,
            'status' => 'COMPLETED',
        ]);

        CarbonEmissionCalculatorService::recalculateShipmentEmissions($s26);

        // =========================================================================
        // 27. SHIPMENT 27: TRANS-ATLANTIC HIGH VALUE (New York -> London)
        // =========================================================================
        $s27 = Shipment::create([
            'tracking_number' => 'EHG-20261124-NYCLHR',
            'customer_user_id' => $custBioPharma->id,
            'assigned_operator_id' => $operator->id,
            'service_level_id' => $b2bLevel->id,
            'destination_type' => 'fulfillment_center',
            'origin_location_id' => $locNewYorkPort->id,
            'destination_location_id' => $locLondonGateway->id,
            'sender_business_name' => 'General Electric Aerospace Supply',
            'sender_contact_name' => 'Thomas Bradley',
            'sender_phone' => '+1 212 435 7000',
            'sender_email' => 't.bradley@ge-aerospace.com',
            'sender_address' => 'Port Newark Terminal, Corbin St, Newark, NJ, USA',
            'receiver_business_name' => 'Rolls-Royce Aerospace Civil Engines',
            'receiver_contact_name' => 'Arthur Pendelton',
            'receiver_phone' => '+44 20 7946 0912',
            'receiver_email' => 'distribution@ukbiopharma.com',
            'receiver_address' => 'London Gateway Logistics Park Unit 3, Essex, UK',
            'cargo_description' => 'Ceramic Matrix Composite Turbofan Shroud Panels',
            'cargo_weight_tonnes' => 31.500,
            'cargo_volume_cbm' => 74.000,
            'package_count' => 6,
            'package_type' => '40ft High-Cube Climate-Monitored Containers',
            'current_status' => 'IN_TRANSIT',
            'eta' => now()->addDays(4)->setHour(12),
            'original_eta' => now()->addDays(4)->setHour(12),
        ]);

        TransportLeg::create([
            'shipment_id' => $s27->id,
            'sequence_number' => 1,
            'transport_mode' => 'sea',
            'origin_location_id' => $locNewYorkPort->id,
            'destination_location_id' => $locFelixstowe->id,
            'carrier_name' => 'Ocean Network Express (ONE)',
            'transport_reference' => 'Vessel ONE Apus Voy 091E',
            'planned_departure' => now()->subDays(5),
            'actual_departure' => now()->subDays(5),
            'estimated_arrival' => now()->addDays(3),
            'distance_km' => 5950.00,
            'emission_factor_id' => $seaDefault->id,
            'status' => 'IN_TRANSIT',
        ]);

        TransportLeg::create([
            'shipment_id' => $s27->id,
            'sequence_number' => 2,
            'transport_mode' => 'land',
            'origin_location_id' => $locFelixstowe->id,
            'destination_location_id' => $locLondonGateway->id,
            'carrier_name' => 'Maritime Transport Ltd UK',
            'transport_reference' => 'Heavy Truck FX-8812',
            'planned_departure' => now()->addDays(3)->addHours(4),
            'estimated_arrival' => now()->addDays(4)->setHour(12),
            'distance_km' => 110.00,
            'emission_factor_id' => $roadDefault->id,
            'status' => 'PENDING',
        ]);

        CarbonEmissionCalculatorService::recalculateShipmentEmissions($s27);
    }
}
