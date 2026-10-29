<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CarbonEmissionFactor extends Model
{
    use HasFactory;

    protected $fillable = [
        'transport_mode',
        'subcategory',
        'factor_g_per_tonne_km',
        'source_reference',
        'description',
        'effective_year',
        'is_default',
    ];

    protected function casts(): array
    {
        return [
            'factor_g_per_tonne_km' => 'float',
            'effective_year' => 'integer',
            'is_default' => 'boolean',
        ];
    }

    public function transportLegs(): HasMany
    {
        return $this->hasMany(TransportLeg::class, 'emission_factor_id');
    }
}
