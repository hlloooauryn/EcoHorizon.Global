<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Shipment extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'tracking_number',
        'customer_user_id',
        'assigned_operator_id',
        'service_level_id',
        'destination_type',
        'origin_location_id',
        'destination_location_id',
        'sender_business_name',
        'sender_contact_name',
        'sender_phone',
        'sender_email',
        'sender_address',
        'receiver_business_name',
        'receiver_contact_name',
        'receiver_phone',
        'receiver_email',
        'receiver_address',
        'cargo_description',
        'cargo_weight_tonnes',
        'cargo_volume_cbm',
        'package_count',
        'package_type',
        'special_instructions',
        'current_status',
        'eta',
        'original_eta',
        'actual_delivery_date',
        'total_distance_km',
        'total_estimated_co2e_kg',
        'carbon_calculation_notes',
        'proof_of_delivery_receiver_name',
        'proof_of_delivery_notes',
        'proof_of_delivery_at',
    ];

    protected function casts(): array
    {
        return [
            'cargo_weight_tonnes' => 'float',
            'cargo_volume_cbm' => 'float',
            'package_count' => 'integer',
            'eta' => 'datetime',
            'original_eta' => 'datetime',
            'actual_delivery_date' => 'datetime',
            'total_distance_km' => 'float',
            'total_estimated_co2e_kg' => 'float',
            'proof_of_delivery_at' => 'datetime',
        ];
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'customer_user_id');
    }

    public function operator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_operator_id');
    }

    public function serviceLevel(): BelongsTo
    {
        return $this->belongsTo(ServiceLevel::class);
    }

    public function originLocation(): BelongsTo
    {
        return $this->belongsTo(Location::class, 'origin_location_id');
    }

    public function destinationLocation(): BelongsTo
    {
        return $this->belongsTo(Location::class, 'destination_location_id');
    }

    public function transportLegs(): HasMany
    {
        return $this->hasMany(TransportLeg::class)->orderBy('sequence_number', 'asc');
    }

    public function trackingEvents(): HasMany
    {
        return $this->hasMany(TrackingEvent::class)->orderBy('event_time', 'asc');
    }

    public function recentTrackingEvents(): HasMany
    {
        return $this->hasMany(TrackingEvent::class)->orderBy('event_time', 'desc');
    }

    public function deliveryExceptions(): HasMany
    {
        return $this->hasMany(DeliveryException::class)->orderBy('created_at', 'desc');
    }

    public function activeExceptions(): HasMany
    {
        return $this->hasMany(DeliveryException::class)->where('is_resolved', false);
    }

    public function hasActiveException(): bool
    {
        return $this->activeExceptions()->count() > 0;
    }

    public function hasCriticalException(): bool
    {
        return $this->activeExceptions()->where('severity', 'critical')->count() > 0;
    }

    public function isDelayed(): bool
    {
        if ($this->current_status === 'DELAYED') {
            return true;
        }

        if ($this->eta && !in_array($this->current_status, ['DELIVERED', 'CANCELLED'], true)) {
            return now()->gt($this->eta);
        }

        return false;
    }

    public function isDelivered(): bool
    {
        return $this->current_status === 'DELIVERED';
    }

    public function isCancelled(): bool
    {
        return $this->current_status === 'CANCELLED';
    }

    public function getStatusColorClass(): string
    {
        return match ($this->current_status) {
            'DELIVERED' => 'bg-emerald-100 text-emerald-800 border-emerald-200',
            'OUT_FOR_DELIVERY', 'IN_TRANSIT', 'PICKED_UP' => 'bg-sky-100 text-sky-800 border-sky-200',
            'ARRIVED_AT_TRANSIT_HUB', 'ARRIVED_AT_DESTINATION_TERMINAL', 'CUSTOMS_CLEARANCE', 'AWAITING_CUSTOMER_PICKUP' => 'bg-teal-100 text-teal-800 border-teal-200',
            'DELAYED' => 'bg-amber-100 text-amber-800 border-amber-200',
            'DELIVERY_EXCEPTION' => 'bg-red-100 text-red-800 border-red-200',
            'CANCELLED' => 'bg-slate-100 text-slate-700 border-slate-200',
            default => 'bg-blue-50 text-blue-700 border-blue-200',
        };
    }
}

