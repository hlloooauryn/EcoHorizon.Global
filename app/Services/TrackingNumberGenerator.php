<?php

namespace App\Services;

use App\Models\Shipment;
use Illuminate\Support\Str;

class TrackingNumberGenerator
{
    /**
     * Generate a unique tracking number in the format: EHG-YYYYMMDD-XXXX
     */
    public static function generate(): string
    {
        $datePrefix = 'EHG-' . now()->format('Ymd') . '-';

        do {
            $randomSuffix = strtoupper(Str::random(4));
            $trackingNumber = $datePrefix . $randomSuffix;
        } while (Shipment::where('tracking_number', $trackingNumber)->exists());

        return $trackingNumber;
    }
}
