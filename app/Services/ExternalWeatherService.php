<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ExternalWeatherService
{
    /**
     * Fetch current weather conditions for given coordinates using Open-Meteo.
     * Open-Meteo is free, requires no API key, and provides global marine and land weather.
     */
    public static function getWeather(float $lat, float $lon): array
    {
        try {
            $response = Http::timeout(3)->get('https://api.open-meteo.com/v1/forecast', [
                'latitude' => round($lat, 4),
                'longitude' => round($lon, 4),
                'current_weather' => 'true',
            ]);

            if ($response->successful()) {
                $data = $response->json();
                $current = $data['current_weather'] ?? [];

                $weatherCode = $current['weathercode'] ?? 0;
                $windSpeed = $current['windspeed'] ?? 0; // km/h
                $condition = self::interpretWeatherCode($weatherCode);
                $isAdverse = self::isDisruptiveCondition($weatherCode, $windSpeed);

                return [
                    'source' => 'Open-Meteo Live API',
                    'temperature_c' => $current['temperature'] ?? 25.0,
                    'wind_speed_kmh' => $windSpeed,
                    'weather_code' => $weatherCode,
                    'condition' => $condition,
                    'is_adverse' => $isAdverse,
                    'disruption_risk' => $isAdverse ? 'High — Adverse weather may impact flight/vessel operations' : 'Normal — Operations running smoothly',
                ];
            }
        } catch (\Throwable $e) {
            Log::info("Open-Meteo API unreachable, using resilient offline fallback: {$e->getMessage()}");
        }

        // Resilient fallback for offline testing and reliable university defense presentations
        return [
            'source' => 'EcoHorizon Weather Observation (Simulated / Fallback)',
            'temperature_c' => 28.5,
            'wind_speed_kmh' => 18.2,
            'weather_code' => 1,
            'condition' => 'Mainly clear / Good visibility',
            'is_adverse' => false,
            'disruption_risk' => 'Normal — Operations running smoothly',
        ];
    }

    protected static function interpretWeatherCode(int $code): string
    {
        return match (true) {
            $code === 0 => 'Clear Sky',
            in_array($code, [1, 2, 3], true) => 'Partly Cloudy to Overcast',
            in_array($code, [45, 48], true) => 'Fog / Reduced Visibility',
            in_array($code, [51, 53, 55], true) => 'Light Drizzle',
            in_array($code, [61, 63, 65], true) => 'Rain / Wet Conditions',
            in_array($code, [71, 73, 75], true) => 'Snow / Freezing Conditions',
            in_array($code, [80, 81, 82], true) => 'Heavy Rain Showers',
            in_array($code, [95, 96, 99], true) => 'Severe Thunderstorm & High Waves',
            default => 'Moderate Atmospheric Conditions',
        };
    }

    protected static function isDisruptiveCondition(int $code, float $windSpeed): bool
    {
        // Wind speed over 55 km/h or thunderstorm/gale/heavy snow
        return $windSpeed >= 55 || in_array($code, [45, 48, 65, 75, 82, 95, 96, 99], true);
    }
}

