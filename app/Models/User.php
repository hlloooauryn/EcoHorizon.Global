<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'company_name',
        'phone',
        'status',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isOperator(): bool
    {
        return in_array($this->role, ['operator', 'staff'], true);
    }

    public function isStaff(): bool
    {
        return $this->isOperator();
    }

    public function isCustomer(): bool
    {
        return in_array($this->role, ['customer', 'user'], true);
    }

    public function isUser(): bool
    {
        return $this->isCustomer();
    }

    public function getRoleDisplayNameAttribute(): string
    {
        return match ($this->role) {
            'admin' => 'Admin',
            'operator', 'staff' => 'Staff',
            'customer', 'user' => 'User',
            default => ucfirst($this->role),
        };
    }

    public function canManageShipments(): bool
    {
        return in_array($this->role, ['admin', 'operator', 'staff'], true);
    }

    public function shipments(): HasMany
    {
        return $this->hasMany(Shipment::class, 'customer_user_id');
    }

    public function assignedShipments(): HasMany
    {
        return $this->hasMany(Shipment::class, 'assigned_operator_id');
    }

    public function recordedEvents(): HasMany
    {
        return $this->hasMany(TrackingEvent::class, 'recorded_by_user_id');
    }

    public function assignedExceptions(): HasMany
    {
        return $this->hasMany(DeliveryException::class, 'assigned_operator_id');
    }

    public function auditLogs(): HasMany
    {
        return $this->hasMany(AuditLog::class, 'user_id');
    }
}
