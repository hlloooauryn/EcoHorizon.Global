<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Location extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'type',
        'address',
        'city',
        'state',
        'country',
        'country_code',
        'latitude',
        'longitude',
        'contact_person',
        'contact_phone',
    ];

    protected function casts(): array
    {
        return [
            'latitude' => 'float',
            'longitude' => 'float',
        ];
    }

    public function outboundShipments(): HasMany
    {
        return $this->hasMany(Shipment::class, 'origin_location_id');
    }

    public function inboundShipments(): HasMany
    {
        return $this->hasMany(Shipment::class, 'destination_location_id');
    }

    public function outboundLegs(): HasMany
    {
        return $this->hasMany(TransportLeg::class, 'origin_location_id');
    }

    public function inboundLegs(): HasMany
    {
        return $this->hasMany(TransportLeg::class, 'destination_location_id');
    }
}
