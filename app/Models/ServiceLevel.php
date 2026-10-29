<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ServiceLevel extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'description',
        'icon',
        'requires_pickup',
        'requires_recipient_delivery',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'requires_pickup' => 'boolean',
            'requires_recipient_delivery' => 'boolean',
            'is_active' => 'boolean',
        ];
    }

    public function shipments(): HasMany
    {
        return $this->hasMany(Shipment::class);
    }
}
