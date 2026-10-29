<?php

namespace App\Http\Controllers;

use App\Models\Shipment;
use App\Services\ExternalWeatherService;
use Illuminate\Http\Request;

class PublicTrackingController extends Controller
{
    public function search(Request $request)
    {
        $request->validate([
            'tracking_number' => ['required', 'string', 'max:50'],
        ]);

        $trackingNumber = strtoupper(trim($request->input('tracking_number')));

        $shipment = Shipment::where('tracking_number', $trackingNumber)->first();

        if (!$shipment) {
            return back()->with('error', "No shipment found matching tracking reference '{$trackingNumber}'. Please verify your code and try again.")
                         ->withInput();
        }

        return redirect()->route('track.show', $shipment->tracking_number);
    }

    public function show(string $tracking_number)
    {
        $shipment = Shipment::where('tracking_number', strtoupper(trim($tracking_number)))
            ->with([
                'originLocation',
                'destinationLocation',
                'serviceLevel',
                'transportLegs' => function ($query) {
                    $query->orderBy('sequence_number', 'asc')->with(['originLocation', 'destinationLocation', 'emissionFactor']);
                },
                'trackingEvents' => function ($query) {
                    $query->where('is_customer_visible', true)->orderBy('event_time', 'desc');
                },
                'deliveryExceptions' => function ($query) {
                    $query->where('is_customer_visible', true)->orderBy('created_at', 'desc');
                },
            ])
            ->firstOrFail();

        // Get coordinates for weather observation:
        // Prioritize destination or current active leg
        $activeLeg = $shipment->transportLegs->where('status', 'IN_TRANSIT')->first() 
            ?? $shipment->transportLegs->where('status', 'PENDING')->first()
            ?? $shipment->transportLegs->last();

        $weatherLocation = $activeLeg?->destinationLocation ?? $shipment->destinationLocation;
        $weatherData = null;

        if ($weatherLocation) {
            $weatherData = ExternalWeatherService::getWeather(
                $weatherLocation->latitude,
                $weatherLocation->longitude
            );
            $weatherData['location_name'] = $weatherLocation->name;
            $weatherData['city'] = $weatherLocation->city;
            $weatherData['country'] = $weatherLocation->country;
        }

        // Map payload for Leaflet
        $mapRoute = [];
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
                        'type' => $leg->originLocation->type,
                        'lat' => $leg->originLocation->latitude,
                        'lon' => $leg->originLocation->longitude,
                    ],
                    'destination' => [
                        'name' => $leg->destinationLocation->name,
                        'type' => $leg->destinationLocation->type,
                        'lat' => $leg->destinationLocation->latitude,
                        'lon' => $leg->destinationLocation->longitude,
                    ],
                ];
            }
        }

        return view('tracking.show', compact('shipment', 'weatherData', 'mapRoute'));
    }
}
