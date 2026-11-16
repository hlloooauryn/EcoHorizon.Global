<x-app-layout title="{{ __('Shipment Registry') }} — EcoHorizon Global">
    <div class="space-y-6">

        <!-- Top Header -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-4 border-b border-slate-200">
            <div>
                <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight">{{ __('Shipment Registry') }}</h1>
                <p class="text-sm text-slate-500 mt-1">
                    {{ __('Multimodal freight manifests, tracking references, and journey status across sea, land, and air corridors.') }}
                </p>
            </div>
            @if(auth()->user()->canManageShipments())
                <div class="flex items-center gap-3">
                    <a href="{{ route('shipments.create') }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl text-xs font-bold bg-brand-sky text-white hover:bg-sky-700 shadow-md shadow-sky-600/20 transition-all">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        {{ __('Create New Shipment') }}
                    </a>
                </div>
            @endif
        </div>

        <!-- Filter & Search Panel -->
        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-2xs space-y-4">
            <form action="{{ route('shipments.index') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-5 gap-3">
                <!-- Search -->
                <div class="md:col-span-2 relative">
                    <input type="text" name="search" value="{{ request('search') }}" 
                           placeholder="{{ __('Search tracking #, cargo, shipper, or consignee...') }}" 
                           class="w-full pl-9 pr-3 py-2 text-xs rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-brand-sky bg-slate-50">
                    <svg class="w-4 h-4 text-slate-400 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </div>

                <!-- Status Filter -->
                <div>
                    <select name="status" class="w-full py-2 px-3 text-xs rounded-xl border border-slate-300 bg-slate-50 focus:outline-none focus:ring-2 focus:ring-brand-sky">
                        <option value="">{{ __('All Statuses') }}</option>
                        <option value="BOOKED" {{ request('status') === 'BOOKED' ? 'selected' : '' }}>{{ __('Booked') }}</option>
                        <option value="PICKED_UP" {{ request('status') === 'PICKED_UP' ? 'selected' : '' }}>{{ __('Picked Up') }}</option>
                        <option value="IN_TRANSIT" {{ request('status') === 'IN_TRANSIT' ? 'selected' : '' }}>{{ __('In Transit') }}</option>
                        <option value="ARRIVED_AT_TRANSIT_HUB" {{ request('status') === 'ARRIVED_AT_TRANSIT_HUB' ? 'selected' : '' }}>{{ __('At Transit Hub') }}</option>
                        <option value="ARRIVED_AT_DESTINATION_TERMINAL" {{ request('status') === 'ARRIVED_AT_DESTINATION_TERMINAL' ? 'selected' : '' }}>{{ __('At Destination Terminal') }}</option>
                        <option value="CUSTOMS_CLEARANCE" {{ request('status') === 'CUSTOMS_CLEARANCE' ? 'selected' : '' }}>{{ __('Customs Clearance') }}</option>
                        <option value="AWAITING_CUSTOMER_PICKUP" {{ request('status') === 'AWAITING_CUSTOMER_PICKUP' ? 'selected' : '' }}>{{ __('Awaiting Pickup') }}</option>
                        <option value="OUT_FOR_DELIVERY" {{ request('status') === 'OUT_FOR_DELIVERY' ? 'selected' : '' }}>{{ __('Out for Delivery') }}</option>
                        <option value="DELIVERED" {{ request('status') === 'DELIVERED' ? 'selected' : '' }}>{{ __('Delivered') }}</option>
                        <option value="DELAYED" {{ request('status') === 'DELAYED' ? 'selected' : '' }}>{{ __('Delayed') }}</option>
                        <option value="DELIVERY_EXCEPTION" {{ request('status') === 'DELIVERY_EXCEPTION' ? 'selected' : '' }}>{{ __('Exception Active') }}</option>
                    </select>
                </div>

                <!-- Service Level Filter -->
                <div>
                    <select name="service_level_id" class="w-full py-2 px-3 text-xs rounded-xl border border-slate-300 bg-slate-50 focus:outline-none focus:ring-2 focus:ring-brand-sky">
                        <option value="">{{ __('All Service Levels') }}</option>
                        @foreach($serviceLevels as $sl)
                            <option value="{{ $sl->id }}" {{ request('service_level_id') == $sl->id ? 'selected' : '' }}>
                                {{ $sl->code }} ({{ __($sl->name) }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Submit & Clear -->
                <div class="flex items-center gap-2">
                    <button type="submit" class="flex-1 px-4 py-2 rounded-xl text-xs font-bold bg-brand-sky text-white hover:bg-sky-700 transition-colors shadow-2xs">
                        {{ __('Filter') }}
                    </button>
                    @if(request()->hasAny(['search', 'status', 'service_level_id', 'destination_type', 'exception_status']))
                        <a href="{{ route('shipments.index') }}" class="px-3 py-2 rounded-xl text-xs font-semibold bg-slate-100 hover:bg-slate-200 text-slate-700 transition-colors">
                            {{ __('Reset') }}
                        </a>
                    @endif
                </div>
            </form>
        </div>

        <!-- Shipments Data Table -->
        <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden shadow-2xs">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse text-xs">
                    <thead>
                        <tr class="bg-slate-50/80 text-[11px] font-bold uppercase tracking-wider text-slate-500 border-b border-slate-200">
                            <th class="py-3.5 px-4">{{ __('Tracking Code') }}</th>
                            <th class="py-3.5 px-4">{{ __('Shipper & Consignee') }}</th>
                            <th class="py-3.5 px-4">{{ __('Corridor Origin & Destination') }}</th>
                            <th class="py-3.5 px-4">{{ __('Service Level') }}</th>
                            <th class="py-3.5 px-4">{{ __('Status') }}</th>
                            <th class="py-3.5 px-4">{{ __('Schedule (ETA)') }}</th>
                            <th class="py-3.5 px-4">{{ __('Carbon (CO₂e)') }}</th>
                            <th class="py-3.5 px-4 text-right">{{ __('Actions') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($shipments as $shipment)
                            <tr class="hover:bg-slate-50/80 transition-colors">
                                <!-- Tracking Code -->
                                <td class="py-3.5 px-4 font-mono font-bold text-slate-900">
                                    <a href="{{ route('shipments.show', $shipment->id) }}" class="text-brand-sky hover:underline">
                                        {{ $shipment->tracking_number }}
                                    </a>
                                    <span class="block text-[10px] font-normal text-slate-400 font-sans truncate max-w-[170px]" title="{{ $shipment->cargo_description }}">
                                        {{ $shipment->cargo_description }}
                                    </span>
                                </td>

                                <!-- Parties -->
                                <td class="py-3.5 px-4">
                                    <div class="font-semibold text-slate-900 truncate max-w-[160px]" title="{{ $shipment->sender_business_name }}">
                                        {{ $shipment->sender_business_name }}
                                    </div>
                                    <div class="text-[11px] text-slate-500 truncate max-w-[160px]" title="{{ $shipment->receiver_business_name }}">
                                        &darr; {{ $shipment->receiver_business_name }}
                                    </div>
                                </td>

                                <!-- Corridor -->
                                <td class="py-3.5 px-4">
                                    <div class="font-medium text-slate-800 truncate max-w-[180px]">
                                        {{ $shipment->originLocation->name }} &rarr; {{ $shipment->destinationLocation->name }}
                                    </div>
                                    <div class="text-[10px] text-slate-400 capitalize">
                                        {{ __(str_replace('_', ' ', $shipment->destination_type)) }}
                                    </div>
                                </td>

                                <!-- Service Level -->
                                <td class="py-3.5 px-4">
                                    <span class="inline-flex px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider bg-slate-100 text-slate-800">
                                        {{ $shipment->serviceLevel->code }}
                                    </span>
                                </td>

                                <!-- Status -->
                                <td class="py-3.5 px-4">
                                    <x-status-badge :status="$shipment->current_status" />
                                </td>

                                <!-- ETA -->
                                <td class="py-3.5 px-4 font-mono text-slate-700">
                                    <div>{{ $shipment->eta ? $shipment->eta->format('M d, Y H:i') : __('Pending') }}</div>
                                    @if($shipment->isDelayed())
                                        <span class="text-[10px] text-amber-600 font-bold block">{{ __('Delayed') }}</span>
                                    @endif
                                </td>

                                <!-- Carbon -->
                                <td class="py-3.5 px-4 font-mono font-medium text-emerald-700">
                                    {{ number_format($shipment->total_estimated_co2e_kg, 1) }} kg
                                    <span class="block text-[10px] text-slate-400 font-sans">
                                        {{ number_format($shipment->total_distance_km) }} km
                                    </span>
                                </td>

                                <!-- Action Buttons -->
                                <td class="py-3.5 px-4 text-right">
                                    <div class="flex items-center justify-end gap-1.5">
                                        <a href="{{ route('shipments.show', $shipment->id) }}" 
                                            class="px-2.5 py-1 rounded-lg text-xs font-semibold bg-brand-sky text-white hover:bg-sky-700 transition-colors shadow-2xs">
                                            {{ __('Cockpit') }}
                                        </a>
                                        <a href="{{ route('track.show', $shipment->tracking_number) }}" target="_blank"
                                            class="p-1 rounded-lg text-slate-400 hover:text-brand-sky hover:bg-slate-100 transition-colors" title="{{ __('Public Tracking Page') }}">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="py-12 text-center text-slate-400 text-xs">
                                    {{ __('No shipments match your current criteria.') }}
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($shipments->hasPages())
                <div class="px-6 py-4 border-t border-slate-200">
                    {{ $shipments->links() }}
                </div>
            @endif
        </div>

    </div>
</x-app-layout>
