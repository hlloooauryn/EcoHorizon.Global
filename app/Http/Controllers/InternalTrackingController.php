<?php

namespace App\Http\Controllers;

use App\Models\Shipment;
use App\Services\ExternalWeatherService;
use Illuminate\Http\Request;

class InternalTrackingController extends Controller
{
    /**
     * Display the internal interactive freight tracking console.
     */
    public function index(Request $request, ?string $tracking_number = null)
    {
        $user = auth()->user();

        // Base query with all relationships eager loaded
        $query = Shipment::query()->with([
            'originLocation',
            'destinationLocation',
            'serviceLevel',
            'customer',
            'transportLegs' => function ($q) {
                $q->orderBy('sequence_number', 'asc')->with(['originLocation', 'destinationLocation', 'emissionFactor']);
            },
            'trackingEvents' => function ($q) {
                $q->orderBy('event_time', 'desc');
            },
            'deliveryExceptions' => function ($q) {
                $q->orderBy('created_at', 'desc');
            },
        ]);

        // Scope to customer's own shipments if user is customer role
        if ($user->isCustomer()) {
            $query->where('customer_user_id', $user->id);
        }

        // Quick pills of recent active shipments
        $recentQuery = Shipment::query()->with(['originLocation', 'destinationLocation']);
        if ($user->isCustomer()) {
            $recentQuery->where('customer_user_id', $user->id);
        }
        $recentShipments = $recentQuery->orderByRaw("
            CASE 
                WHEN current_status IN ('IN_TRANSIT', 'OUT_FOR_DELIVERY', 'PICKED_UP', 'CUSTOMS_CLEARANCE', 'DELAYED', 'CRITICAL_EXCEPTION') THEN 1 
                ELSE 2 
            END ASC
        ")->latest('updated_at')->take(8)->get();

        $searchCode = $tracking_number ?: $request->input('tracking_number');
        $shipment = null;

        if (!empty($searchCode)) {
            $cleanCode = strtoupper(trim($searchCode));
            $shipment = (clone $query)->where('tracking_number', $cleanCode)->first();

            if (!$shipment) {
                // If customer tried accessing someone else's consignment
                if ($user->isCustomer() && Shipment::where('tracking_number', $cleanCode)->exists()) {
                    abort(403, 'Unauthorized access to this consignment record.');
                }

                return redirect()->route('tracking.index')
                    ->with('error', "No consignment found matching tracking reference '{$cleanCode}'.");
            }
        } else {
            // Default: pick the most relevant active shipment
            $shipment = (clone $query)->orderByRaw("
                CASE 
                    WHEN current_status IN ('IN_TRANSIT', 'OUT_FOR_DELIVERY', 'PICKED_UP', 'CUSTOMS_CLEARANCE', 'DELAYED', 'CRITICAL_EXCEPTION') THEN 1 
                    ELSE 2 
                END ASC
            ")->latest('updated_at')->first();
        }

        // Build telemetry and route payload if a shipment is found
        $weatherData = null;
        $mapRoute = [];

        if ($shipment) {
            $activeLeg = $shipment->transportLegs->where('status', 'IN_TRANSIT')->first()
                ?? $shipment->transportLegs->where('status', 'PENDING')->first()
                ?? $shipment->transportLegs->last();

            $weatherLocation = $activeLeg?->destinationLocation ?? $shipment->destinationLocation;
            if ($weatherLocation) {
                $weatherData = ExternalWeatherService::getWeather(
                    $weatherLocation->latitude,
                    $weatherLocation->longitude
                );
                $weatherData['location_name'] = $weatherLocation->name;
                $weatherData['city'] = $weatherLocation->city;
                $weatherData['country'] = $weatherLocation->country;
            }

            foreach ($shipment->transportLegs as $leg) {
                if ($leg->originLocation && $leg->destinationLocation) {
                    $mapRoute[] = [
                        'sequence' => $leg->sequence_number,
                        'mode' => $leg->transport_mode,
                        'carrier' => $leg->carrier_name,
                        'reference' => $leg->transport_reference,
                        'status' => $leg->status,
                        'distance_km' => $leg->distance_km,
                        'origin' => [
                            'name' => $leg->originLocation->name,
                            'city' => $leg->originLocation->city,
                            'country' => $leg->originLocation->country,
                            'type' => $leg->originLocation->type,
                            'lat' => (float) $leg->originLocation->latitude,
                            'lon' => (float) $leg->originLocation->longitude,
                        ],
                        'destination' => [
                            'name' => $leg->destinationLocation->name,
                            'city' => $leg->destinationLocation->city,
                            'country' => $leg->destinationLocation->country,
                            'type' => $leg->destinationLocation->type,
                            'lat' => (float) $leg->destinationLocation->latitude,
                            'lon' => (float) $leg->destinationLocation->longitude,
                        ],
                    ];
                }
            }
        }

        return view('tracking.index', compact('shipment', 'recentShipments', 'weatherData', 'mapRoute'));
    }
}
