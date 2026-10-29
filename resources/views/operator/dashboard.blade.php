<x-app-layout title="Logistics Operations Cockpit — EcoHorizon Global">
    <div class="space-y-8">
        
        <!-- Welcome & Quick Action Bar -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-4 border-b border-slate-200">
            <div>
                <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight">Logistics Operations Cockpit</h1>
                <p class="text-sm text-slate-500 mt-1">
                    Manage multimodal transport legs, record waypoint events, log schedule delays, and confirm terminal handovers.
                </p>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('shipments.create') }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl text-xs font-bold bg-brand-sky text-white hover:bg-sky-700 shadow-md shadow-sky-600/20 transition-all">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    Dispatch New Multimodal Shipment
                </a>
            </div>
        </div>

        <!-- 4 Actionable Queues -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <!-- Queue 1: Upcoming Arrivals -->
            <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-2xs">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold uppercase tracking-wider text-slate-500">Upcoming Arrivals (48h)</span>
                    <span class="w-2.5 h-2.5 rounded-full bg-brand-sky"></span>
                </div>
                <div class="text-2xl font-extrabold text-slate-900 mt-2">{{ $upcomingArrivals->count() }}</div>
                <div class="text-xs text-slate-500 mt-0.5">Shipments reaching destination</div>
            </div>

            <!-- Queue 2: Delayed Shipments -->
            <div class="bg-white p-5 rounded-2xl border border-amber-200 bg-amber-50/20 shadow-2xs">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold uppercase tracking-wider text-amber-800">Delayed Shipments</span>
                    <span class="w-2.5 h-2.5 rounded-full bg-amber-500 animate-ping"></span>
                </div>
                <div class="text-2xl font-extrabold text-amber-700 mt-2">{{ $delayedShipments->count() }}</div>
                <div class="text-xs text-amber-700/80 mt-0.5">Requires revised ETA notifications</div>
            </div>

            <!-- Queue 3: Awaiting Terminal Pickup -->
            <div class="bg-white p-5 rounded-2xl border border-teal-200 bg-teal-50/20 shadow-2xs">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold uppercase tracking-wider text-teal-800">Awaiting Terminal Pickup</span>
                    <span class="w-2.5 h-2.5 rounded-full bg-teal-500"></span>
                </div>
                <div class="text-2xl font-extrabold text-teal-700 mt-2">{{ $awaitingPickup->count() }}</div>
                <div class="text-xs text-teal-700/80 mt-0.5">Port / Terminal collection ready</div>
            </div>

            <!-- Queue 4: Active Exceptions -->
            <div class="bg-white p-5 rounded-2xl border border-red-200 bg-red-50/20 shadow-2xs">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold uppercase tracking-wider text-red-800">Unresolved Exceptions</span>
                    <span class="w-2.5 h-2.5 rounded-full bg-red-500 animate-pulse"></span>
                </div>
                <div class="text-2xl font-extrabold text-red-700 mt-2">{{ $activeExceptions->count() }}</div>
                <div class="text-xs text-red-700/80 mt-0.5">Customs, weather & damage holds</div>
            </div>
        </div>

        <!-- 2-Columns Grid: Active Shipments Queue & Recent Tracking Events -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            <!-- Column 1 & 2: Active Shipments Table -->
            <div class="lg:col-span-2 bg-white rounded-2xl border border-slate-200 overflow-hidden shadow-2xs">
                <div class="px-6 py-4 border-b border-slate-200 bg-slate-50 flex items-center justify-between">
                    <h2 class="text-xs font-bold text-slate-900 uppercase tracking-wider">Active Shipments In Transit</h2>
                    <a href="{{ route('shipments.index') }}" class="text-xs font-semibold text-brand-sky hover:underline">
                        View All ({{ $activeShipments->total() }}) &rarr;
                    </a>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse text-xs">
                        <thead>
                            <tr class="bg-slate-50/80 text-[11px] font-bold uppercase tracking-wider text-slate-500 border-b border-slate-200">
                                <th class="py-3 px-4">Tracking Code</th>
                                <th class="py-3 px-4">Route Corridors</th>
                                <th class="py-3 px-4">Service Level</th>
                                <th class="py-3 px-4">Current Status</th>
                                <th class="py-3 px-4 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($activeShipments as $shipment)
                                <tr class="hover:bg-slate-50/80 transition-colors">
                                    <td class="py-3 px-4 font-mono font-bold text-slate-900">
                                        <a href="{{ route('shipments.show', $shipment->id) }}" class="text-brand-sky hover:underline">
                                            {{ $shipment->tracking_number }}
                                        </a>
                                        <span class="block text-[10px] font-normal text-slate-400 font-sans">
                                            {{ number_format($shipment->cargo_weight_tonnes, 1) }}t &bull; {{ $shipment->package_type }}
                                        </span>
                                    </td>
                                    <td class="py-3 px-4">
                                        <div class="font-medium text-slate-800 truncate max-w-[200px]" title="{{ $shipment->originLocation->name }} -> {{ $shipment->destinationLocation->name }}">
                                            {{ $shipment->originLocation->name }} &rarr; {{ $shipment->destinationLocation->name }}
                                        </div>
                                        <span class="text-[10px] text-slate-400 capitalize">
                                            Destination: {{ str_replace('_', ' ', $shipment->destination_type) }}
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
                                    <td class="py-3 px-4 text-right">
                                        <a href="{{ route('shipments.show', $shipment->id) }}" 
                                           class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-xs font-semibold bg-brand-sky text-white hover:bg-sky-700 transition-colors">
                                            Manage Cockpit
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="py-8 text-center text-slate-400 text-xs">
                                        No active shipments requiring operations dispatch.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($activeShipments->hasPages())
                    <div class="px-6 py-3 border-t border-slate-200">
                        {{ $activeShipments->links() }}
                    </div>
                @endif
            </div>

            <!-- Column 3: Live Tracking Events Feed -->
            <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden shadow-2xs">
                <div class="px-6 py-4 border-b border-slate-200 bg-slate-50 flex items-center justify-between">
                    <h2 class="text-xs font-bold text-slate-900 uppercase tracking-wider">Recent Operational Events</h2>
                    <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                </div>

                <div class="p-4 space-y-4 max-h-[500px] overflow-y-auto">
                    @forelse($recentEvents as $event)
                        <div class="text-xs space-y-1 border-b border-slate-100 pb-3 last:border-b-0 last:pb-0">
                            <div class="flex items-center justify-between">
                                <a href="{{ route('shipments.show', $event->shipment_id) }}" class="font-mono font-bold text-brand-sky hover:underline">
                                    {{ $event->shipment->tracking_number }}
                                </a>
                                <span class="text-[10px] text-slate-400 font-mono">{{ $event->event_time->diffForHumans() }}</span>
                            </div>
                            <div class="font-semibold text-slate-800">{{ str_replace('_', ' ', $event->event_type) }}</div>
                            <p class="text-slate-600 text-[11px] leading-relaxed">{{ $event->description }}</p>
                            <div class="text-[10px] text-slate-400 flex items-center gap-2">
                                <span>Loc: {{ $event->location_name }}</span>
                                <span>&bull;</span>
                                <span>Logged by: {{ $event->recordedBy?->name ?? 'Operator' }}</span>
                            </div>
                        </div>
                    @empty
                        <div class="text-center text-xs text-slate-400 py-6">No events recorded recently.</div>
                    @endforelse
                </div>
            </div>

        </div>

    </div>
</x-app-layout>
