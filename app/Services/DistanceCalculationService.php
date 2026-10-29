<?php

namespace App\Services;

class DistanceCalculationService
{
    const EARTH_RADIUS_KM = 6371;

    /**
     * Calculate Great-Circle distance using Haversine formula in kilometers.
     * Optionally applies transport corridor tortuosity factors:
     * - Land (road network): ~1.25x
     * - Sea (navigational maritime shipping lanes): ~1.35x
     * - Air (flight paths/airways): ~1.05x
     */
    public static function calculate(
        float $lat1,
        float $lon1,
        float $lat2,
        float $lon2,
        string $mode = 'land'
    ): float {
        $latFrom = deg2rad($lat1);
        $lonFrom = deg2rad($lon1);
        $latTo = deg2rad($lat2);
        $lonTo = deg2rad($lon2);

        $latDelta = $latTo - $latFrom;
        $lonDelta = $lonTo - $lonFrom;

        $angle = 2 * asin(sqrt(
            pow(sin($latDelta / 2), 2) +
            cos($latFrom) * cos($latTo) * pow(sin($lonDelta / 2), 2)
        ));

        $straightDistance = $angle * self::EARTH_RADIUS_KM;

        // Apply realistic routing curvature factors based on mode
        $multiplier = match ($mode) {
            'sea' => 1.35, // Nautical waterways bypass land masses
            'land' => 1.25, // Road/rail topography and highway networks
            'air' => 1.05, // Direct airways
            default => 1.0,
        };

        return round($straightDistance * $multiplier, 2);
    }
}

