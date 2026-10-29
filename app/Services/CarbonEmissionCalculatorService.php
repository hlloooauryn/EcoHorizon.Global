<?php

namespace App\Services;

use App\Models\CarbonEmissionFactor;
use App\Models\Shipment;
use App\Models\TransportLeg;

class CarbonEmissionCalculatorService
{
    /**
     * Calculate carbon emissions for an individual transport leg.
     * Formula:
     * Leg CO2e (kg) = Cargo Weight (tonnes) * Distance (km) * Factor (g CO2e / tonne-km) / 1000
     */
    public static function calculateLegEmission(TransportLeg $leg, float $cargoWeightTonnes): ?float
    {
        if ($cargoWeightTonnes <= 0 || $leg->distance_km <= 0) {
            return null;
        }

        $factor = $leg->emissionFactor ?? CarbonEmissionFactor::where('transport_mode', $leg->transport_mode)
            ->where('is_default', true)
            ->first();

        if (!$factor) {
            $factor = CarbonEmissionFactor::where('transport_mode', $leg->transport_mode)->first();
        }

        if (!$factor) {
            return null;
        }

        // Weight in tonnes * distance in km * factor in g CO2e/t-km / 1000 = kg CO2e
        $co2eKg = ($cargoWeightTonnes * $leg->distance_km * $factor->factor_g_per_tonne_km) / 1000;

        return round($co2eKg, 2);
    }

    /**
     * Recalculate all leg emissions and the aggregate shipment emission.
     */
    public static function recalculateShipmentEmissions(Shipment $shipment): void
    {
        $weight = (float) $shipment->cargo_weight_tonnes;
        $totalDistance = 0.0;
        $totalCo2e = 0.0;
        $allLegsCalculated = true;
        $legNotes = [];

        foreach ($shipment->transportLegs as $leg) {
            // If distance is missing or 0, attempt Haversine calculation from origin and destination locations
            if ($leg->distance_km <= 0 && $leg->originLocation && $leg->destinationLocation) {
                $calcDistance = DistanceCalculationService::calculate(
                    $leg->originLocation->latitude,
                    $leg->originLocation->longitude,
                    $leg->destinationLocation->latitude,
                    $leg->destinationLocation->longitude,
                    $leg->transport_mode
                );
                $leg->distance_km = $calcDistance;
                $leg->distance_source = 'calculated_haversine';
            }

            // If no emission factor assigned, assign default for that mode
            if (!$leg->emission_factor_id) {
                $defaultFactor = CarbonEmissionFactor::where('transport_mode', $leg->transport_mode)
                    ->where('is_default', true)
                    ->first();
                if ($defaultFactor) {
                    $leg->emission_factor_id = $defaultFactor->id;
                }
            }

            $legCo2e = self::calculateLegEmission($leg, $weight);

            if ($legCo2e !== null) {
                $leg->leg_estimated_co2e_kg = $legCo2e;
                $totalCo2e += $legCo2e;
                $totalDistance += (float) $leg->distance_km;
                $factorName = $leg->emissionFactor ? $leg->emissionFactor->subcategory : 'Default';
                $legNotes[] = "Leg {$leg->sequence_number} ({$leg->transport_mode}): {$leg->distance_km} km @ {$factorName} = {$legCo2e} kg CO2e";
            } else {
                $allLegsCalculated = false;
                $leg->leg_estimated_co2e_kg = 0;
            }

            $leg->save();
        }

        $notes = implode(" | ", $legNotes);
        if (!$allLegsCalculated) {
            $notes .= " (Note: Some legs have incomplete distance or emission factors)";
        }

        $shipment->total_distance_km = round($totalDistance, 2);
        $shipment->total_estimated_co2e_kg = round($totalCo2e, 2);
        $shipment->carbon_calculation_notes = $notes;
        $shipment->save();
    }
}
