<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DeliveryException extends Model
{
    use HasFactory;

    protected $fillable = [
        'shipment_id',
        'transport_leg_id',
        'category',
        'severity',
        'description',
        'action_taken',
        'assigned_operator_id',
        'is_resolved',
        'resolved_at',
        'resolution_notes',
        'impact_on_eta_hours',
        'is_customer_visible',
    ];

    protected function casts(): array
    {
        return [
            'is_resolved' => 'boolean',
            'resolved_at' => 'datetime',
            'impact_on_eta_hours' => 'integer',
            'is_customer_visible' => 'boolean',
        ];
    }

    public function shipment(): BelongsTo
    {
        return $this->belongsTo(Shipment::class);
    }

    public function transportLeg(): BelongsTo
    {
        return $this->belongsTo(TransportLeg::class);
    }

    public function assignedOperator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_operator_id');
    }

    public function getSeverityBadgeClass(): string
    {
        return match ($this->severity) {
            'critical' => 'bg-red-100 text-red-800 border-red-300 font-semibold animate-pulse',
            'high' => 'bg-amber-100 text-amber-800 border-amber-300 font-medium',
            'medium' => 'bg-yellow-50 text-yellow-800 border-yellow-200',
            default => 'bg-slate-100 text-slate-700 border-slate-200',
        };
    }
}
