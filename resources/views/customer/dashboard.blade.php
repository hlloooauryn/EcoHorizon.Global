<x-app-layout title="{{ __('My Shipments') }} — EcoHorizon Global">
    <div class="space-y-8">
        
        <!-- Welcome Header -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-4 border-b border-slate-200">
            <div>
                <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight">{{ __('Customer Freight Workspace') }}</h1>
                <p class="text-sm text-slate-500 mt-1">
                    {{ __('Authorized shipments for') }} <span class="font-bold text-slate-800">{{ auth()->user()->company_name ?? auth()->user()->name }}</span>.
                </p>
            </div>
            <div>
                <a href="{{ route('tracking.index') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-xs font-bold bg-white border border-slate-300 text-slate-700 hover:bg-slate-50 shadow-2xs transition-all">
                    <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    {{ __('Public Quick Search') }}
                </a>
            </div>
        </div>

        <!-- 4 Customer Metrics -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-2xs">
                <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400">{{ __('Total Consignments') }}</span>
                <div class="text-2xl font-extrabold text-slate-900 mt-1">{{ $stats['total'] }}</div>
                <div class="text-xs text-slate-500 mt-0.5">{{ __('All time bookings') }}</div>
            </div>

            <div class="bg-white p-5 rounded-2xl border border-sky-200 bg-sky-50/20 shadow-2xs">
                <span class="text-[10px] font-bold uppercase tracking-wider text-brand-sky">{{ __('Currently In Transit') }}</span>
                <div class="text-2xl font-extrabold text-brand-sky mt-1">{{ $stats['in_transit'] }}</div>
                <div class="text-xs text-sky-600 mt-0.5">{{ __('On active routes') }}</div>
            </div>

            <div class="bg-white p-5 rounded-2xl border border-emerald-200 bg-emerald-50/20 shadow-2xs">
                <span class="text-[10px] font-bold uppercase tracking-wider text-emerald-700">{{ __('Delivered') }}</span>
                <div class="text-2xl font-extrabold text-emerald-600 mt-1">{{ $stats['delivered'] }}</div>
                <div class="text-xs text-emerald-600 mt-0.5">{{ __('Verified receipts') }}</div>
            </div>

            <div class="bg-white p-5 rounded-2xl border border-teal-200 bg-teal-50/20 shadow-2xs">
                <span class="text-[10px] font-bold uppercase tracking-wider text-teal-800">{{ __('Carbon Footprint') }}</span>
                <div class="text-2xl font-extrabold text-teal-600 mt-1">{{ number_format($stats['total_co2e_kg'], 1) }} <span class="text-xs font-normal">kg</span></div>
                <div class="text-xs text-teal-600 mt-0.5">{{ __('GLEC Framework CO₂e') }}</div>
            </div>
        </div>

        <!-- Search & Filter Bar -->
        <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-2xs flex flex-wrap items-center justify-between gap-4">
            <form action="{{ route('customer.dashboard') }}" method="GET" class="flex flex-wrap items-center gap-3 w-full sm:w-auto">
                <div class="relative w-full sm:w-64">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="{{ __('Search tracking # or cargo...') }}" 
                           class="w-full pl-9 pr-3 py-2 text-xs rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-brand-sky bg-slate-50">
                    <svg class="w-4 h-4 text-slate-400 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </div>

                <select name="status" class="py-2 px-3 text-xs rounded-xl border border-slate-300 bg-slate-50 focus:outline-none focus:ring-2 focus:ring-brand-sky">
                    <option value="">{{ __('All Statuses') }}</option>
                    <option value="IN_TRANSIT" {{ request('status') === 'IN_TRANSIT' ? 'selected' : '' }}>{{ __('In Transit') }}</option>
                    <option value="DELIVERED" {{ request('status') === 'DELIVERED' ? 'selected' : '' }}>{{ __('Delivered') }}</option>
                    <option value="DELAYED" {{ request('status') === 'DELAYED' ? 'selected' : '' }}>{{ __('Delayed') }}</option>
                    <option value="DELIVERY_EXCEPTION" {{ request('status') === 'DELIVERY_EXCEPTION' ? 'selected' : '' }}>{{ __('Exception') }}</option>
                </select>

                <button type="submit" class="px-4 py-2 rounded-xl text-xs font-bold bg-brand-sky text-white hover:bg-sky-700 transition-colors">
                    {{ __('Filter') }}
                </button>

                @if(request()->hasAny(['search', 'status']))
                    <a href="{{ route('customer.dashboard') }}" class="text-xs text-slate-500 hover:text-slate-800 underline">
                        {{ __('Clear Filters') }}
                    </a>
                @endif
            </form>
        </div>

        <!-- Shipments Table -->
        <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden shadow-2xs">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse text-xs">
                    <thead>
                        <tr class="bg-slate-50/80 text-[11px] font-bold uppercase tracking-wider text-slate-500 border-b border-slate-200">
                            <th class="py-3 px-4">{{ __('Tracking Number') }}</th>
                            <th class="py-3 px-4">{{ __('Route Corridors') }}</th>
                            <th class="py-3 px-4">{{ __('Service Level') }}</th>
                            <th class="py-3 px-4">{{ __('Current Status') }}</th>
                            <th class="py-3 px-4">{{ __('Estimated Delivery (ETA)') }}</th>
                            <th class="py-3 px-4">{{ __('Carbon (CO₂e)') }}</th>
                            <th class="py-3 px-4 text-right">{{ __('Actions') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($shipments as $shipment)
                            <tr class="hover:bg-slate-50/80 transition-colors">
                                <td class="py-3 px-4 font-mono font-bold text-slate-900">
                                    <a href="{{ route('track.show', $shipment->tracking_number) }}" class="text-brand-sky hover:underline">
                                        {{ $shipment->tracking_number }}
                                    </a>
                                    <span class="block text-[10px] font-normal text-slate-400 font-sans truncate max-w-[180px]">
                                        {{ $shipment->cargo_description }}
                                    </span>
                                </td>
                                <td class="py-3 px-4">
                                    <div class="font-medium text-slate-800">
                                        {{ $shipment->originLocation->name }} &rarr; {{ $shipment->destinationLocation->name }}
                                    </div>
                                    <span class="text-[10px] text-slate-400 capitalize">
                                        {{ __('Dest') }}: {{ __(str_replace('_', ' ', $shipment->destination_type)) }}
                                    </span>
                                </td>
                                <td class="py-3 px-4">
                                    <span class="inline-flex px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider bg-slate-100 text-slate-700">
                                        {{ $shipment->serviceLevel->code }}
                                    </span>
                                </td>
                                <td class="py-3 px-4">
                                    <x-status-badge :status="$shipment->current_status" />
                                </td>
                                <td class="py-3 px-4 font-mono text-slate-700">
                                    {{ $shipment->eta ? $shipment->eta->format('M d, Y H:i') : __('Pending') }}
                                </td>
                                <td class="py-3 px-4 font-mono text-emerald-700 font-medium">
                                    {{ number_format($shipment->total_estimated_co2e_kg, 1) }} kg
                                </td>
                                <td class="py-3 px-4 text-right">
                                    <a href="{{ route('track.show', $shipment->tracking_number) }}" 
                                       class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg text-xs font-semibold bg-brand-sky text-white hover:bg-sky-700 transition-colors shadow-2xs">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                        {{ __('Live Track') }}
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="py-8 text-center text-slate-400 text-xs">
                                    {{ __('No shipments assigned to your customer account matching current filters.') }}
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($shipments->hasPages())
                <div class="px-6 py-3 border-t border-slate-200">
                    {{ $shipments->links() }}
                </div>
            @endif
        </div>

    </div>
</x-app-layout>
