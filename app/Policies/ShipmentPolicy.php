<?php

namespace App\Policies;

use App\Models\Shipment;
use App\Models\User;

class ShipmentPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return in_array($user->role, ['admin', 'operator', 'customer'], true);
    }

    /**
     * Determine whether the user can view the model.
     * Admin and Operator can view any shipment.
     * Customers can ONLY view shipments assigned to their account.
     */
    public function view(User $user, Shipment $shipment): bool
    {
        if (in_array($user->role, ['admin', 'operator'], true)) {
            return true;
        }

        return $user->role === 'customer' && $shipment->customer_user_id === $user->id;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return in_array($user->role, ['admin', 'operator'], true);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Shipment $shipment): bool
    {
        return in_array($user->role, ['admin', 'operator'], true);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Shipment $shipment): bool
    {
        return $user->role === 'admin';
    }

    /**
     * Determine whether the user can record tracking events.
     */
    public function recordEvent(User $user, Shipment $shipment): bool
    {
        return in_array($user->role, ['admin', 'operator'], true);
    }

    /**
     * Determine whether the user can create or resolve exceptions.
     */
    public function manageExceptions(User $user, Shipment $shipment): bool
    {
        return in_array($user->role, ['admin', 'operator'], true);
    }
}

