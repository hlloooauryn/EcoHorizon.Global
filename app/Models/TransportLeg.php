<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TransportLeg extends Model
{
    use HasFactory;

    protected $fillable = [
        'shipment_id',
        'sequence_number',
        'transport_mode',
        'origin_location_id',
        'destination_location_id',
        'carrier_name',
        'transport_reference',
        'planned_departure',
        'actual_departure',
        'estimated_arrival',
        'actual_arrival',
        'distance_km',
        'distance_source',
        'emission_factor_id',
        'leg_estimated_co2e_kg',
        'status',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'sequence_number' => 'integer',
            'planned_departure' => 'datetime',
            'actual_departure' => 'datetime',
            'estimated_arrival' => 'datetime',
            'actual_arrival' => 'datetime',
            'distance_km' => 'float',
            'leg_estimated_co2e_kg' => 'float',
        ];
    }

    public function shipment(): BelongsTo
    {
        return $this->belongsTo(Shipment::class);
    }

    public function originLocation(): BelongsTo
    {
        return $this->belongsTo(Location::class, 'origin_location_id');
    }

    public function destinationLocation(): BelongsTo
    {
        return $this->belongsTo(Location::class, 'destination_location_id');
    }

    public function emissionFactor(): BelongsTo
    {
        return $this->belongsTo(CarbonEmissionFactor::class, 'emission_factor_id');
    }

    public function trackingEvents(): HasMany
    {
        return $this->hasMany(TrackingEvent::class);
    }

    public function exceptions(): HasMany
    {
        return $this->hasMany(DeliveryException::class);
    }

    public function getModeIcon(): string
    {
        return match ($this->transport_mode) {
            'sea' => 'ship',
            'air' => 'plane',
            'land' => 'truck',
            default => 'navigation',
        };
    }
}
