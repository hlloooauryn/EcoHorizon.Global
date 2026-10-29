<x-guest-layout title="Tracking {{ $shipment->tracking_number }} — EcoHorizon Global">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-12">

        <!-- Top Header & Breadcrumbs -->
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 pb-6 border-b border-slate-200">
            <div>
                <div class="flex items-center gap-2 text-xs font-semibold uppercase tracking-wider text-slate-500 mb-1">
                    <a href="{{ route('home') }}" class="hover:text-brand-sky">Home</a>
                    <span>/</span>
                    <span>Tracking Registry</span>
                    <span>/</span>
                    <span class="text-brand-sky font-mono font-bold">{{ $shipment->tracking_number }}</span>
                </div>
                <div class="flex flex-wrap items-center gap-3">
                    <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 font-mono tracking-tight">
                        {{ $shipment->tracking_number }}
                    </h1>
                    <x-status-badge :status="$shipment->current_status" />
                    
                    @if($shipment->isDelayed())
                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-bold bg-amber-100 text-amber-800 border border-amber-300">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            Schedule Delayed
                        </span>
                    @endif

                    @if($shipment->hasCriticalException())
                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-bold bg-red-100 text-red-800 border border-red-300 animate-pulse">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                            Critical Exception
                        </span>
                    @endif
                </div>
                <p class="text-sm text-slate-500 mt-1">
                    {{ $shipment->originLocation->name }} ({{ $shipment->originLocation->country }}) 
                    <span class="text-brand-sky font-bold mx-1">&rarr;</span> 
                    {{ $shipment->destinationLocation->name }} ({{ $shipment->destinationLocation->country }})
                </p>
            </div>

            <!-- Fast Action / Refresh -->
            <div class="flex items-center gap-3">
                <button onclick="window.print()" class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl text-xs font-semibold bg-white border border-slate-300 text-slate-700 hover:bg-slate-50 transition-colors shadow-2xs">
                    <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                    Print Consignment
                </button>
                <a href="{{ route('track.show', $shipment->tracking_number) }}" class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl text-xs font-semibold bg-brand-sky text-white hover:bg-sky-700 transition-colors shadow-2xs">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                    Refresh Milestones
                </a>
            </div>
        </div>

        <!-- Exception Alert Banner (If Any Active Exceptions) -->
        @if($shipment->activeExceptions->count() > 0)
            <div class="mt-6 space-y-3">
                @foreach($shipment->activeExceptions as $exception)
                    <div class="p-4 rounded-xl border {{ $exception->severity === 'critical' ? 'bg-red-50 border-red-300 text-red-900' : 'bg-amber-50 border-amber-300 text-amber-900' }} flex items-start gap-3 shadow-xs">
                        <svg class="w-5 h-5 shrink-0 mt-0.5 {{ $exception->severity === 'critical' ? 'text-red-600' : 'text-amber-600' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                        <div class="flex-1">
                            <div class="flex items-center justify-between">
                                <span class="text-xs font-bold uppercase tracking-wider">
                                    {{ ucfirst(str_replace('_', ' ', $exception->category)) }} &bull; Severity: {{ strtoupper($exception->severity) }}
                                </span>
                                <span class="text-xs opacity-75">{{ $exception->created_at->diffForHumans() }}</span>
                            </div>
                            <p class="text-sm font-medium mt-1">{{ $exception->description }}</p>
                            @if($exception->action_taken)
                                <div class="mt-2 text-xs bg-white/60 p-2.5 rounded-lg border border-black/10">
                                    <span class="font-bold">Mitigation Action:</span> {{ $exception->action_taken }}
                                </div>
                            @endif
                            @if($exception->impact_on_eta_hours > 0)
                                <div class="mt-1.5 text-xs font-semibold text-amber-800">
                                    Estimated Schedule Impact: +{{ $exception->impact_on_eta_hours }} hours
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

        <!-- Key Metrics Cards Grid -->
        <div class="mt-6 grid grid-cols-2 md:grid-cols-4 gap-4">
            <!-- Card 1: Estimated Delivery -->
            <div class="bg-white p-4 sm:p-5 rounded-2xl border border-slate-200/90 shadow-2xs">
                <div class="text-[11px] font-bold uppercase tracking-wider text-slate-500 mb-1">Estimated Arrival (ETA)</div>
                <div class="text-base sm:text-lg font-bold text-slate-900">
                    {{ $shipment->eta ? $shipment->eta->format('M d, Y H:i') . ' UTC' : 'Pending Schedule' }}
                </div>
                @if($shipment->original_eta && $shipment->eta && $shipment->eta->gt($shipment->original_eta))
                    <div class="text-xs text-amber-600 font-medium mt-0.5">
                        Originally: {{ $shipment->original_eta->format('M d, Y') }}
                    </div>
                @else
                    <div class="text-xs text-emerald-600 font-medium mt-0.5">On Scheduled Timeline</div>
                @endif
            </div>

            <!-- Card 2: Service Level & Destination -->
            <div class="bg-white p-4 sm:p-5 rounded-2xl border border-slate-200/90 shadow-2xs">
                <div class="text-[11px] font-bold uppercase tracking-wider text-slate-500 mb-1">Service Level Agreement</div>
                <div class="text-base sm:text-lg font-bold text-brand-sky">
                    {{ $shipment->serviceLevel->name }}
                </div>
                <div class="text-xs text-slate-500 mt-0.5 capitalize">
                    Destination: {{ str_replace('_', ' ', $shipment->destination_type) }}
                </div>
            </div>

            <!-- Card 3: Cargo Payload -->
            <div class="bg-white p-4 sm:p-5 rounded-2xl border border-slate-200/90 shadow-2xs">
                <div class="text-[11px] font-bold uppercase tracking-wider text-slate-500 mb-1">Cargo Specifications</div>
                <div class="text-base sm:text-lg font-bold text-slate-900">
                    {{ number_format($shipment->cargo_weight_tonnes, 2) }} <span class="text-xs font-normal text-slate-500">Tonnes</span>
                </div>
                <div class="text-xs text-slate-500 mt-0.5 truncate" title="{{ $shipment->cargo_description }}">
                    {{ $shipment->package_count }}x {{ $shipment->package_type }}
                </div>
            </div>

            <!-- Card 4: Estimated Carbon Footprint -->
            <div class="bg-white p-4 sm:p-5 rounded-2xl border border-emerald-200/90 bg-gradient-to-br from-white to-emerald-50/40 shadow-2xs">
                <div class="text-[11px] font-bold uppercase tracking-wider text-emerald-800 mb-1 flex items-center gap-1">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                    Estimated Carbon (CO₂e)
                </div>
                <div class="text-base sm:text-lg font-bold text-emerald-700">
                    {{ number_format($shipment->total_estimated_co2e_kg, 1) }} <span class="text-xs font-normal text-slate-600">kg CO₂e</span>
                </div>
                <div class="text-xs text-slate-500 mt-0.5">
                    Total: {{ number_format($shipment->total_distance_km) }} km (GLEC Framework)
                </div>
            </div>
        </div>

        <!-- Interactive Map Section -->
        <div class="mt-8 bg-white rounded-2xl border border-slate-200/90 overflow-hidden shadow-2xs">
            <div class="px-6 py-4 border-b border-slate-200 bg-slate-50/70 flex flex-wrap items-center justify-between gap-3">
                <div class="flex items-center gap-2">
                    <svg class="w-5 h-5 text-brand-sky" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/></svg>
                    <h2 class="text-sm font-bold text-slate-900 uppercase tracking-wider">Multimodal Journey Route Map</h2>
                </div>
                <!-- Route Legend -->
                <div class="flex items-center gap-4 text-xs font-medium text-slate-600">
                    <span class="flex items-center gap-1.5">
                        <span class="w-3 h-1 bg-[#0284C7] rounded"></span> Sea Maritime
                    </span>
                    <span class="flex items-center gap-1.5">
                        <span class="w-3 h-1 bg-[#10B981] rounded"></span> Land / Road
                    </span>
                    <span class="flex items-center gap-1.5">
                        <span class="w-3 h-1 bg-[#6366F1] border-b-2 border-dashed border-[#6366F1]"></span> Airfreight
                    </span>
                </div>
            </div>

            <!-- Leaflet Container -->
            <div id="shipmentMap" class="w-full h-96 sm:h-[460px] bg-slate-100 relative"></div>
            <div class="px-6 py-2.5 bg-slate-50 border-t border-slate-200 text-[11px] text-slate-500 flex items-center justify-between">
                <span>Map Data &copy; OpenStreetMap contributors &bull; Interactive Leaflet Navigation</span>
                <span>Coordinates & corridor paths are estimated based on recorded port waypoints and hubs</span>
            </div>
        </div>

        <!-- 2-Columns Grid: Journey Milestones & Live Weather / Proof -->
        <div class="mt-8 grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            <!-- Column 1 & 2: Multimodal Legs & Event Timeline -->
            <div class="lg:col-span-2 space-y-8">
                
                <!-- Section A: Transport Legs Journey -->
                <div class="bg-white p-6 rounded-2xl border border-slate-200/90 shadow-2xs">
                    <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wider mb-4 flex items-center gap-2">
                        <svg class="w-4 h-4 text-brand-sky" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                        Transport Legs Sequence ({{ $shipment->transportLegs->count() }} Segments)
                    </h3>

                    <div class="space-y-4">
                        @foreach($shipment->transportLegs as $leg)
                            <div class="p-4 rounded-xl border {{ $leg->status === 'IN_TRANSIT' ? 'border-sky-300 bg-sky-50/40' : ($leg->status === 'COMPLETED' ? 'border-emerald-200 bg-emerald-50/20' : 'border-slate-200 bg-white') }}">
                                <div class="flex flex-wrap items-center justify-between gap-2 mb-2">
                                    <div class="flex items-center gap-2">
                                        <span class="w-6 h-6 rounded-full bg-slate-900 text-white text-xs font-bold flex items-center justify-center">
                                            {{ $leg->sequence_number }}
                                        </span>
                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-xs font-bold uppercase tracking-wider
                                            {{ $leg->transport_mode === 'sea' ? 'bg-blue-100 text-blue-800' : ($leg->transport_mode === 'air' ? 'bg-indigo-100 text-indigo-800' : 'bg-emerald-100 text-emerald-800') }}">
                                            {{ strtoupper($leg->transport_mode) }} FREIGHT
                                        </span>
                                        <span class="text-xs font-semibold text-slate-800">{{ $leg->carrier_name }}</span>
                                    </div>
                                    <span class="text-xs font-semibold px-2 py-0.5 rounded-full 
                                        {{ $leg->status === 'COMPLETED' ? 'bg-emerald-100 text-emerald-800' : ($leg->status === 'IN_TRANSIT' ? 'bg-sky-100 text-sky-800 animate-pulse' : 'bg-slate-100 text-slate-600') }}">
                                        {{ $leg->status }}
                                    </span>
                                </div>

                                <div class="text-sm font-medium text-slate-900 flex items-center gap-2">
                                    <span>{{ $leg->originLocation->name }}</span>
                                    <span class="text-slate-400">&rarr;</span>
                                    <span>{{ $leg->destinationLocation->name }}</span>
                                </div>

                                <div class="mt-2 grid grid-cols-2 sm:grid-cols-4 gap-2 text-xs text-slate-500 pt-2 border-t border-slate-100">
                                    <div>
                                        <span class="block text-[10px] text-slate-400 uppercase">Reference</span>
                                        <span class="font-medium text-slate-700">{{ $leg->transport_reference ?? 'Scheduled' }}</span>
                                    </div>
                                    <div>
                                        <span class="block text-[10px] text-slate-400 uppercase">Distance</span>
                                        <span class="font-medium text-slate-700">{{ number_format($leg->distance_km) }} km</span>
                                    </div>
                                    <div>
                                        <span class="block text-[10px] text-slate-400 uppercase">Leg Emission</span>
                                        <span class="font-medium text-emerald-700">{{ number_format($leg->leg_estimated_co2e_kg, 1) }} kg CO₂e</span>
                                    </div>
                                    <div>
                                        <span class="block text-[10px] text-slate-400 uppercase">Schedule</span>
                                        <span class="font-medium text-slate-700">{{ $leg->estimated_arrival ? $leg->estimated_arrival->format('M d, H:i') : 'TBD' }}</span>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Section B: Tracking Events Timeline -->
                <div class="bg-white p-6 rounded-2xl border border-slate-200/90 shadow-2xs">
                    <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wider mb-6 flex items-center gap-2">
                        <svg class="w-4 h-4 text-brand-sky" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        Milestone Chronology & Tracking History
                    </h3>

                    @if($shipment->trackingEvents->isEmpty())
                        <div class="p-8 text-center text-sm text-slate-400">
                            No tracking events recorded yet for this journey.
                        </div>
                    @else
                        <div class="relative border-l-2 border-slate-200 ml-3 sm:ml-4 space-y-6">
                            @foreach($shipment->trackingEvents as $event)
                                <div class="relative pl-6 sm:pl-8">
                                    <!-- Event Node Marker -->
                                    <div class="absolute -left-[9px] top-1 w-4 h-4 rounded-full border-2 border-white 
                                        {{ $loop->first ? 'bg-brand-sky ring-4 ring-sky-100' : 'bg-slate-400' }}"></div>

                                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-1 mb-1">
                                        <div class="text-sm font-bold text-slate-900 flex items-center gap-2">
                                            <span>{{ str_replace('_', ' ', $event->event_type) }}</span>
                                            <span class="text-xs font-normal text-slate-500">at {{ $event->location_name }}</span>
                                        </div>
                                        <div class="text-xs font-mono text-slate-400">
                                            {{ $event->event_time->format('M d, Y &bull; H:i') }} UTC
                                        </div>
                                    </div>

                                    <p class="text-xs text-slate-600 leading-relaxed">{{ $event->description }}</p>

                                    @if($event->proof_notes)
                                        <div class="mt-1.5 text-[11px] font-mono text-slate-700 bg-slate-50 p-2 rounded border border-slate-200">
                                            Proof / Notes: {{ $event->proof_notes }}
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>

            <!-- Column 3: Weather, Handover/Delivery Confirmation & Footprint Info -->
            <div class="space-y-6">

                <!-- Live Weather Card (Open-Meteo Integration) -->
                @if($weatherData)
                    <div class="bg-gradient-to-br from-slate-900 to-slate-800 text-white p-6 rounded-2xl shadow-md border border-slate-700">
                        <div class="flex items-center justify-between mb-4">
                            <span class="text-xs font-semibold text-brand-teal uppercase tracking-wider flex items-center gap-1.5">
                                <span class="w-2 h-2 rounded-full bg-brand-emerald animate-pulse"></span>
                                Live Weather Radar
                            </span>
                            <span class="text-[10px] text-slate-400 font-mono">{{ $weatherData['source'] }}</span>
                        </div>

                        <div class="flex items-center justify-between">
                            <div>
                                <div class="text-2xl font-bold">{{ $weatherData['temperature_c'] }}&deg;C</div>
                                <div class="text-xs text-slate-300 font-medium">{{ $weatherData['condition'] }}</div>
                            </div>
                            <div class="text-right text-xs text-slate-300">
                                <div>Wind: {{ $weatherData['wind_speed_kmh'] }} km/h</div>
                                <div class="mt-0.5 text-[11px] text-slate-400">{{ $weatherData['location_name'] }}</div>
                            </div>
                        </div>

                        <div class="mt-4 pt-3 border-t border-slate-700/80 text-xs {{ $weatherData['is_adverse'] ? 'text-amber-300 font-semibold' : 'text-emerald-300' }}">
                            {{ $weatherData['disruption_risk'] }}
                        </div>
                    </div>
                @endif

                <!-- Delivery Proof & Handover Verification Card (If Delivered) -->
                @if($shipment->isDelivered())
                    <div class="bg-emerald-50 border border-emerald-300 p-6 rounded-2xl shadow-xs">
                        <div class="flex items-center gap-2 text-emerald-800 font-bold text-sm uppercase tracking-wider mb-3">
                            <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            Official Delivery Fulfilled
                        </div>
                        <div class="space-y-2 text-xs text-emerald-900">
                            <div>
                                <span class="text-slate-500 block">Received / Signed By:</span>
                                <span class="font-bold text-slate-900">{{ $shipment->proof_of_delivery_receiver_name ?? 'Authorized Consignee' }}</span>
                            </div>
                            <div>
                                <span class="text-slate-500 block">Fulfilled Timestamp:</span>
                                <span class="font-mono text-slate-800">{{ $shipment->actual_delivery_date?->format('M d, Y H:i') ?? 'Completed' }}</span>
                            </div>
                            @if($shipment->proof_of_delivery_notes)
                                <div>
                                    <span class="text-slate-500 block">Handover / Receiving Note:</span>
                                    <p class="font-sans italic bg-white/70 p-2.5 rounded-lg border border-emerald-200 mt-1">
                                        "{{ $shipment->proof_of_delivery_notes }}"
                                    </p>
                                </div>
                            @endif
                        </div>
                    </div>
                @endif

                <!-- Carbon Accounting Methodology Card -->
                <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-2xs">
                    <h4 class="text-xs font-bold text-slate-900 uppercase tracking-wider mb-2 flex items-center gap-1.5">
                        <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        Carbon Accounting Methodology
                    </h4>
                    <p class="text-xs text-slate-500 leading-relaxed mb-3">
                        Estimated greenhouse gas emissions are calculated in accordance with the <strong>Global Logistics Emissions Council (GLEC) Framework v3.0</strong> and IMO MEPC.308 guidelines.
                    </p>
                    <div class="p-3 bg-slate-50 rounded-xl border border-slate-200 text-xs font-mono text-slate-700 leading-normal">
                        CO₂e (kg) = Weight (t) &times; Distance (km) &times; Factor (g CO₂e/t-km) &divide; 1,000
                    </div>
                    <div class="mt-3 text-[11px] text-slate-400">
                        *Estimates provide baseline operational comparability across modal choices and do not substitute certified third-party lifecycle audits.
                    </div>
                </div>

                <!-- Shipper & Consignee Summary -->
                <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-2xs text-xs space-y-4">
                    <div>
                        <div class="text-[10px] uppercase font-bold text-slate-400 tracking-wider">Shipper / Originator</div>
                        <div class="font-bold text-slate-900 text-sm mt-0.5">{{ $shipment->sender_business_name }}</div>
                        <div class="text-slate-500 mt-0.5">{{ $shipment->originLocation->city }}, {{ $shipment->originLocation->country }}</div>
                    </div>
                    <div class="pt-3 border-t border-slate-100">
                        <div class="text-[10px] uppercase font-bold text-slate-400 tracking-wider">Consignee / Receiver</div>
                        <div class="font-bold text-slate-900 text-sm mt-0.5">{{ $shipment->receiver_business_name }}</div>
                        <div class="text-slate-500 mt-0.5">{{ $shipment->destinationLocation->city }}, {{ $shipment->destinationLocation->country }}</div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- Leaflet Map Script -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            if (typeof L === 'undefined') return;

            const mapData = @json($mapRoute);
            if (!mapData || mapData.length === 0) return;

            const map = L.map('shipmentMap', {
                scrollWheelZoom: false,
            }).setView([mapData[0].origin.lat, mapData[0].origin.lon], 4);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 18,
                attribution: '&copy; OpenStreetMap contributors'
            }).addTo(map);

            const bounds = [];

            // Draw route segments
            mapData.forEach(function (leg) {
                const originCoords = [leg.origin.lat, leg.origin.lon];
                const destCoords = [leg.destination.lat, leg.destination.lon];

                bounds.push(originCoords);
                bounds.push(destCoords);

                // Origin Marker
                L.circleMarker(originCoords, {
                    radius: 7,
                    fillColor: '#0284C7',
                    color: '#ffffff',
                    weight: 2,
                    opacity: 1,
                    fillOpacity: 0.9
                }).bindPopup('<b>' + leg.origin.name + '</b><br><span style="text-transform:capitalize;">' + leg.origin.type + '</span>').addTo(map);

                // Destination Marker
                L.circleMarker(destCoords, {
                    radius: 7,
                    fillColor: '#10B981',
                    color: '#ffffff',
                    weight: 2,
                    opacity: 1,
                    fillOpacity: 0.9
                }).bindPopup('<b>' + leg.destination.name + '</b><br><span style="text-transform:capitalize;">' + leg.destination.type + '</span>').addTo(map);

                // Polyline between origin and destination
                let lineColor = '#0284C7';
                let dashArray = null;

                if (leg.mode === 'air') {
                    lineColor = '#6366F1';
                    dashArray = '6, 8';
                } else if (leg.mode === 'land') {
                    lineColor = '#10B981';
                }

                const polyline = L.polyline([originCoords, destCoords], {
                    color: lineColor,
                    weight: 4,
                    opacity: 0.85,
                    dashArray: dashArray
                }).addTo(map);

                polyline.bindPopup('<b>Leg ' + leg.sequence + ' (' + leg.mode.toUpperCase() + ')</b><br>' +
                    'Carrier: ' + leg.carrier + '<br>' +
                    'Ref: ' + (leg.reference || '-') + '<br>' +
                    'Distance: ' + leg.distance_km + ' km<br>' +
                    'Status: ' + leg.status);
            });

            if (bounds.length > 0) {
                map.fitBounds(bounds, { padding: [40, 40] });
            }
        });
    </script>
</x-guest-layout>

