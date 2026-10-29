<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Shipment;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();

        $query = Shipment::where('customer_user_id', $user->id)
            ->with(['originLocation', 'destinationLocation', 'serviceLevel', 'transportLegs']);

        if ($request->filled('status')) {
            $query->where('current_status', $request->status);
        }

        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where(function ($q) use ($search) {
                $q->where('tracking_number', 'like', "%{$search}%")
                  ->orWhere('cargo_description', 'like', "%{$search}%");
            });
        }

        $shipments = $query->orderBy('created_at', 'desc')->paginate(10);

        // Customer aggregate stats
        $stats = [
            'total' => Shipment::where('customer_user_id', $user->id)->count(),
            'in_transit' => Shipment::where('customer_user_id', $user->id)->whereIn('current_status', ['IN_TRANSIT', 'OUT_FOR_DELIVERY', 'PICKED_UP'])->count(),
            'delivered' => Shipment::where('customer_user_id', $user->id)->where('current_status', 'DELIVERED')->count(),
            'delayed_or_exception' => Shipment::where('customer_user_id', $user->id)->whereIn('current_status', ['DELAYED', 'DELIVERY_EXCEPTION'])->count(),
            'total_co2e_kg' => Shipment::where('customer_user_id', $user->id)->sum('total_estimated_co2e_kg'),
        ];

        return view('customer.dashboard', compact('shipments', 'stats'));
    }
}
