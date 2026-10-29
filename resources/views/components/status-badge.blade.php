@props(['status'])

@php
    $config = match($status) {
        'DELIVERED' => [
            'bg' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
            'dot' => 'bg-emerald-500',
            'label' => 'Delivered',
            'icon' => 'check-circle'
        ],
        'OUT_FOR_DELIVERY' => [
            'bg' => 'bg-sky-50 text-sky-700 border-sky-200',
            'dot' => 'bg-sky-500 animate-pulse',
            'label' => 'Out for Delivery',
            'icon' => 'truck'
        ],
        'IN_TRANSIT' => [
            'bg' => 'bg-sky-50 text-sky-700 border-sky-200',
            'dot' => 'bg-sky-500 animate-pulse',
            'label' => 'In Transit',
            'icon' => 'navigation'
        ],
        'PICKED_UP' => [
            'bg' => 'bg-blue-50 text-blue-700 border-blue-200',
            'dot' => 'bg-blue-500',
            'label' => 'Picked Up',
            'icon' => 'package'
        ],
        'ARRIVED_AT_TRANSIT_HUB' => [
            'bg' => 'bg-teal-50 text-teal-700 border-teal-200',
            'dot' => 'bg-teal-500',
            'label' => 'At Transit Hub',
            'icon' => 'map-pin'
        ],
        'ARRIVED_AT_DESTINATION_TERMINAL' => [
            'bg' => 'bg-teal-50 text-teal-700 border-teal-200',
            'dot' => 'bg-teal-500',
            'label' => 'At Destination Terminal',
            'icon' => 'anchor'
        ],
        'CUSTOMS_CLEARANCE' => [
            'bg' => 'bg-indigo-50 text-indigo-700 border-indigo-200',
            'dot' => 'bg-indigo-500',
            'label' => 'Customs Clearance',
            'icon' => 'shield'
        ],
        'AWAITING_CUSTOMER_PICKUP' => [
            'bg' => 'bg-amber-50 text-amber-800 border-amber-200',
            'dot' => 'bg-amber-500',
            'label' => 'Awaiting Pickup',
            'icon' => 'clock'
        ],
        'DELAYED' => [
            'bg' => 'bg-amber-50 text-amber-800 border-amber-300',
            'dot' => 'bg-amber-500 animate-ping',
            'label' => 'Delayed',
            'icon' => 'alert-triangle'
        ],
        'DELIVERY_EXCEPTION' => [
            'bg' => 'bg-red-50 text-red-800 border-red-300',
            'dot' => 'bg-red-500 animate-ping',
            'label' => 'Exception Active',
            'icon' => 'alert-octagon'
        ],
        'BOOKED' => [
            'bg' => 'bg-slate-100 text-slate-700 border-slate-200',
            'dot' => 'bg-slate-400',
            'label' => 'Booking Confirmed',
            'icon' => 'bookmark'
        ],
        'CANCELLED' => [
            'bg' => 'bg-gray-100 text-gray-600 border-gray-200',
            'dot' => 'bg-gray-400',
            'label' => 'Cancelled',
            'icon' => 'x-circle'
        ],
        default => [
            'bg' => 'bg-slate-100 text-slate-700 border-slate-200',
            'dot' => 'bg-slate-400',
            'label' => ucfirst(strtolower(str_replace('_', ' ', $status))),
            'icon' => 'circle'
        ],
    };
@endphp

<span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold border {{ $config['bg'] }}">
    <span class="w-1.5 h-1.5 rounded-full {{ $config['dot'] }}"></span>
    <span>{{ $config['label'] }}</span>
</span>

