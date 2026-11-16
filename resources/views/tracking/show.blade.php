<x-guest-layout :title="__('Tracking :number — EcoHorizon Global', ['number' => $shipment->tracking_number])">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-12">

        <!-- Top Header & Breadcrumbs -->
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 pb-6 border-b border-slate-200">
            <div>
                <div class="flex items-center gap-2 text-xs font-semibold uppercase tracking-wider text-slate-500 mb-1">
                    <a href="{{ route('home') }}" class="hover:text-brand-sky">{{ __('Home') }}</a>
                    <span>/</span>
                    <span>{{ __('Tracking Registry') }}</span>
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
                            {{ __('Schedule Delayed') }}
                        </span>
                    @endif

                    @if($shipment->hasCriticalException())
                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-bold bg-red-100 text-red-800 border border-red-300 animate-pulse">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                            {{ __('Critical Exception') }}
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
                    {{ __('Print Consignment') }}
                </button>
                <a href="{{ route('track.show', $shipment->tracking_number) }}" class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl text-xs font-semibold bg-brand-sky text-white hover:bg-sky-700 transition-colors shadow-2xs">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                    {{ __('Refresh Milestones') }}
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
                                    {{ __(ucfirst(str_replace('_', ' ', $exception->category))) }} &bull; {{ __('Severity:') }} {{ __(strtoupper($exception->severity)) }}
                                </span>
                                <span class="text-xs opacity-75">{{ $exception->created_at->diffForHumans() }}</span>
                            </div>
                            <p class="text-sm font-medium mt-1">{{ $exception->description }}</p>
                            @if($exception->action_taken)
                                <div class="mt-2 text-xs bg-white/60 p-2.5 rounded-lg border border-black/10">
                                    <span class="font-bold">{{ __('Mitigation Action:') }}</span> {{ $exception->action_taken }}
                                </div>
                            @endif
                            @if($exception->impact_on_eta_hours > 0)
                                <div class="mt-1.5 text-xs font-semibold text-amber-800">
                                    {{ __('Estimated Schedule Impact: +:hours hours', ['hours' => $exception->impact_on_eta_hours]) }}
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
                <div class="text-[11px] font-bold uppercase tracking-wider text-slate-500 mb-1">{{ __('Estimated Arrival (ETA)') }}</div>
                <div class="text-base sm:text-lg font-bold text-slate-900">
                    {{ $shipment->eta ? $shipment->eta->format('M d, Y H:i') . ' UTC' : __('Pending Schedule') }}
                </div>
                @if($shipment->original_eta && $shipment->eta && $shipment->eta->gt($shipment->original_eta))
                    <div class="text-xs text-amber-600 font-medium mt-0.5">
                        {{ __('Originally:') }} {{ $shipment->original_eta->format('M d, Y') }}
                    </div>
                @else
                    <div class="text-xs text-emerald-600 font-medium mt-0.5">{{ __('On Scheduled Timeline') }}</div>
                @endif
            </div>

            <!-- Card 2: Service Level & Destination -->
            <div class="bg-white p-4 sm:p-5 rounded-2xl border border-slate-200/90 shadow-2xs">
                <div class="text-[11px] font-bold uppercase tracking-wider text-slate-500 mb-1">{{ __('Service Level Agreement') }}</div>
                <div class="text-base sm:text-lg font-bold text-brand-sky">
                    {{ __($shipment->serviceLevel->name) }}
                </div>
                <div class="text-xs text-slate-500 mt-0.5 capitalize">
                    {{ __('Destination:') }} {{ __(str_replace('_', ' ', $shipment->destination_type)) }}
                </div>
            </div>

            <!-- Card 3: Cargo Payload -->
            <div class="bg-white p-4 sm:p-5 rounded-2xl border border-slate-200/90 shadow-2xs">
                <div class="text-[11px] font-bold uppercase tracking-wider text-slate-500 mb-1">{{ __('Cargo Specifications') }}</div>
                <div class="text-base sm:text-lg font-bold text-slate-900">
                    {{ number_format($shipment->cargo_weight_tonnes, 2) }} <span class="text-xs font-normal text-slate-500">{{ __('Tonnes') }}</span>
                </div>
                <div class="text-xs text-slate-500 mt-0.5 truncate" title="{{ $shipment->cargo_description }}">
                    {{ $shipment->package_count }}x {{ $shipment->package_type }}
                </div>
            </div>

            <!-- Card 4: Estimated Carbon Footprint -->
            <div class="bg-white p-4 sm:p-5 rounded-2xl border border-emerald-200/90 bg-gradient-to-br from-white to-emerald-50/40 shadow-2xs">
                <div class="text-[11px] font-bold uppercase tracking-wider text-emerald-800 mb-1 flex items-center gap-1">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                    {{ __('Estimated Carbon (CO₂e)') }}
                </div>
                <div class="text-base sm:text-lg font-bold text-emerald-700">
                    {{ number_format($shipment->total_estimated_co2e_kg, 1) }} <span class="text-xs font-normal text-slate-600">kg CO₂e</span>
                </div>
                <div class="text-xs text-slate-500 mt-0.5">
                    {{ __('Total:') }} {{ number_format($shipment->total_distance_km) }} km ({{ __('GLEC Framework') }})
                </div>
            </div>
        </div>

        <!-- Interactive Map Section -->
        <div class="mt-8 bg-white rounded-2xl border border-slate-200/90 overflow-hidden shadow-2xs">
            <div class="px-6 py-4 border-b border-slate-200 bg-slate-50/70 flex flex-wrap items-center justify-between gap-3">
                <div class="flex items-center gap-2">
                    <svg class="w-5 h-5 text-brand-sky" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/></svg>
                    <h2 class="text-sm font-bold text-slate-900 uppercase tracking-wider">{{ __('Multimodal Journey Route Map') }}</h2>
                </div>
                <!-- Sleek Minimalist Legend (Hover Tooltips) -->
                <div class="bg-white border border-slate-200/90 px-3 py-1.5 rounded-full flex items-center gap-2.5 text-xs shadow-2xs">
                    <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 pl-0.5">{{ __('Legend') }}</span>
                    <div class="h-3.5 w-px bg-slate-200"></div>
                    <div class="flex items-center gap-2">
                        <!-- Red: Exception / Delayed -->
                        <div class="group relative w-3.5 h-3.5 rounded-full bg-[#EF4444] shadow-2xs hover:scale-125 transition-transform cursor-pointer"
                             title="{{ __('Exception / Delayed') }}">
                            <span class="pointer-events-none absolute bottom-full left-1/2 -translate-x-1/2 mb-2 hidden group-hover:flex flex-col items-center z-50">
                                <span class="bg-slate-900 text-white text-[10px] font-bold px-2 py-1 rounded shadow-md whitespace-nowrap">
                                    {!! __('Red • ⚠️ Exception / Delayed') !!}
                                </span>
                                <span class="w-1.5 h-1.5 bg-slate-900 rotate-45 -mt-0.5"></span>
                            </span>
                        </div>

                        <!-- Pink: Air Cargo -->
                        <div class="group relative w-3.5 h-3.5 rounded-full bg-[#EC4899] shadow-2xs hover:scale-125 transition-transform cursor-pointer"
                             title="{{ __('Air Cargo') }}">
                            <span class="pointer-events-none absolute bottom-full left-1/2 -translate-x-1/2 mb-2 hidden group-hover:flex flex-col items-center z-50">
                                <span class="bg-slate-900 text-white text-[10px] font-bold px-2 py-1 rounded shadow-md whitespace-nowrap">
                                    {!! __('Pink • ✈️ Air Cargo') !!}
                                </span>
                                <span class="w-1.5 h-1.5 bg-slate-900 rotate-45 -mt-0.5"></span>
                            </span>
                        </div>

                        <!-- Blue: Sea Freight -->
                        <div class="group relative w-3.5 h-3.5 rounded-full bg-[#0284C7] shadow-2xs hover:scale-125 transition-transform cursor-pointer"
                             title="{{ __('Sea Freight') }}">
                            <span class="pointer-events-none absolute bottom-full left-1/2 -translate-x-1/2 mb-2 hidden group-hover:flex flex-col items-center z-50">
                                <span class="bg-slate-900 text-white text-[10px] font-bold px-2 py-1 rounded shadow-md whitespace-nowrap">
                                    {!! __('Blue • ⚓ Sea Freight') !!}
                                </span>
                                <span class="w-1.5 h-1.5 bg-slate-900 rotate-45 -mt-0.5"></span>
                            </span>
                        </div>

                        <!-- Orange: Land Transit -->
                        <div class="group relative w-3.5 h-3.5 rounded-full bg-[#F59E0B] shadow-2xs hover:scale-125 transition-transform cursor-pointer"
                             title="{{ __('Land Transit') }}">
                            <span class="pointer-events-none absolute bottom-full left-1/2 -translate-x-1/2 mb-2 hidden group-hover:flex flex-col items-center z-50">
                                <span class="bg-slate-900 text-white text-[10px] font-bold px-2 py-1 rounded shadow-md whitespace-nowrap">
                                    {!! __('Orange • 🚛 Land Transit') !!}
                                </span>
                                <span class="w-1.5 h-1.5 bg-slate-900 rotate-45 -mt-0.5"></span>
                            </span>
                        </div>

                        <!-- Green: Delivered -->
                        <div class="group relative w-3.5 h-3.5 rounded-full bg-[#10B981] shadow-2xs hover:scale-125 transition-transform cursor-pointer"
                             title="{{ __('Delivered') }}">
                            <span class="pointer-events-none absolute bottom-full left-1/2 -translate-x-1/2 mb-2 hidden group-hover:flex flex-col items-center z-50">
                                <span class="bg-slate-900 text-white text-[10px] font-bold px-2 py-1 rounded shadow-md whitespace-nowrap">
                                    {!! __('Green • ✓ Delivered') !!}
                                </span>
                                <span class="w-1.5 h-1.5 bg-slate-900 rotate-45 -mt-0.5"></span>
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Leaflet Container -->
            <div id="shipmentMap" class="w-full h-96 sm:h-[460px] bg-slate-100 relative"></div>
            <div class="px-6 py-2.5 bg-slate-50 border-t border-slate-200 text-[11px] text-slate-500 flex items-center justify-between">
                <span>{!! __('Map Data © OpenStreetMap contributors • Interactive Leaflet Navigation') !!}</span>
                <span>{{ __('Coordinates & corridor paths are estimated based on recorded port waypoints and hubs') }}</span>
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
                        {{ __('Transport Legs Sequence (:count Segments)', ['count' => $shipment->transportLegs->count()]) }}
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
                                            {{ __(strtoupper($leg->transport_mode)) }} {{ __('FREIGHT') }}
                                        </span>
                                        <span class="text-xs font-semibold text-slate-800">{{ $leg->carrier_name }}</span>
                                    </div>
                                    <span class="text-xs font-semibold px-2 py-0.5 rounded-full 
                                        {{ $leg->status === 'COMPLETED' ? 'bg-emerald-100 text-emerald-800' : ($leg->status === 'IN_TRANSIT' ? 'bg-sky-100 text-sky-800 animate-pulse' : 'bg-slate-100 text-slate-600') }}">
                                        {{ __(str_replace('_', ' ', $leg->status)) }}
                                    </span>
                                </div>

                                <div class="text-sm font-medium text-slate-900 flex items-center gap-2">
                                    <span>{{ $leg->originLocation->name }}</span>
                                    <span class="text-slate-400">&rarr;</span>
                                    <span>{{ $leg->destinationLocation->name }}</span>
                                </div>

                                <div class="mt-2 grid grid-cols-2 sm:grid-cols-4 gap-2 text-xs text-slate-500 pt-2 border-t border-slate-100">
                                    <div>
                                        <span class="block text-[10px] text-slate-400 uppercase">{{ __('Reference') }}</span>
                                        <span class="font-medium text-slate-700">{{ $leg->transport_reference ?? __('Scheduled') }}</span>
                                    </div>
                                    <div>
                                        <span class="block text-[10px] text-slate-400 uppercase">{{ __('Distance') }}</span>
                                        <span class="font-medium text-slate-700">{{ number_format($leg->distance_km) }} km</span>
                                    </div>
                                    <div>
                                        <span class="block text-[10px] text-slate-400 uppercase">{{ __('Leg Emission') }}</span>
                                        <span class="font-medium text-emerald-700">{{ number_format($leg->leg_estimated_co2e_kg, 1) }} kg CO₂e</span>
                                    </div>
                                    <div>
                                        <span class="block text-[10px] text-slate-400 uppercase">{{ __('Schedule') }}</span>
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
                        {{ __('Milestone Chronology & Tracking History') }}
                    </h3>

                    @if($shipment->trackingEvents->isEmpty())
                        <div class="p-8 text-center text-sm text-slate-400">
                            {{ __('No tracking events recorded yet for this journey.') }}
                        </div>
                    @else
                        <div class="relative border-l-2 border-slate-200 ml-3 sm:ml-4 space-y-6">
                            @foreach($shipment->trackingEvents as $event)
                                @php
                                    $isDeliveryCompletion = in_array($event->event_type, ['DELIVERED', 'HANDOVER']) || ($shipment->isDelivered() && $loop->first);
                                @endphp
                                <div class="relative pl-6 sm:pl-8">
                                    <!-- Event Node Marker -->
                                    <div class="absolute -left-[9px] top-1 w-4 h-4 rounded-full border-2 border-white 
                                        {{ $isDeliveryCompletion ? 'bg-emerald-500 ring-4 ring-emerald-100' : ($loop->first ? 'bg-[#2563EB] ring-4 ring-blue-100' : 'bg-slate-400') }}"></div>

                                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-1 mb-1">
                                        <div class="text-sm font-bold {{ $isDeliveryCompletion ? 'text-emerald-700' : 'text-slate-900' }} flex items-center gap-2">
                                            <span class="{{ $isDeliveryCompletion ? 'px-2.5 py-0.5 rounded-full bg-emerald-50 border border-emerald-200' : '' }}">
                                                {{ __(str_replace('_', ' ', $event->event_type)) }}
                                            </span>
                                            <span class="text-xs font-normal text-slate-500">{{ __('at') }} {{ $event->location_name }}</span>
                                        </div>
                                        <div class="text-xs font-mono text-slate-400">
                                            {{ $event->event_time->format('M d, Y &bull; H:i') }} UTC
                                        </div>
                                    </div>

                                    <p class="text-xs text-slate-600 leading-relaxed">{{ __($event->description) }}</p>

                                    @if($event->proof_notes)
                                        <div class="mt-1.5 text-[11px] font-mono text-slate-700 bg-slate-50 p-2 rounded border border-slate-200">
                                            {{ __('Proof / Notes:') }} {{ $event->proof_notes }}
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
                                {{ __('Live Weather Radar') }}
                            </span>
                            <span class="text-[10px] text-slate-400 font-mono">{{ $weatherData['source'] }}</span>
                        </div>

                        <div class="flex items-center justify-between">
                            <div>
                                <div class="text-2xl font-bold">{{ $weatherData['temperature_c'] }}&deg;C</div>
                                <div class="text-xs text-slate-300 font-medium">{{ __($weatherData['condition']) }}</div>
                            </div>
                            <div class="text-right text-xs text-slate-300">
                                <div>{{ __('Wind:') }} {{ $weatherData['wind_speed_kmh'] }} km/h</div>
                                <div class="mt-0.5 text-[11px] text-slate-400">{{ $weatherData['location_name'] }}</div>
                            </div>
                        </div>

                        <div class="mt-4 pt-3 border-t border-slate-700/80 text-xs {{ $weatherData['is_adverse'] ? 'text-amber-300 font-semibold' : 'text-emerald-300' }}">
                            {{ __($weatherData['disruption_risk']) }}
                        </div>
                    </div>
                @endif

                <!-- Delivery Proof & Handover Verification Card (If Delivered) -->
                @if($shipment->isDelivered())
                    <div class="bg-emerald-50 border border-emerald-300 p-6 rounded-2xl shadow-xs">
                        <div class="flex items-center gap-2 text-emerald-800 font-bold text-sm uppercase tracking-wider mb-3">
                            <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            {{ __('Official Delivery Fulfilled') }}
                        </div>
                        <div class="space-y-2 text-xs text-emerald-900">
                            <div>
                                <span class="text-slate-500 block">{{ __('Received / Signed By:') }}</span>
                                <span class="font-bold text-slate-900">{{ $shipment->proof_of_delivery_receiver_name ?? __('Authorized Consignee') }}</span>
                            </div>
                            <div>
                                <span class="text-slate-500 block">{{ __('Fulfilled Timestamp:') }}</span>
                                <span class="font-mono text-slate-800">{{ $shipment->actual_delivery_date?->format('M d, Y H:i') ?? __('Completed') }}</span>
                            </div>
                            @if($shipment->proof_of_delivery_notes)
                                <div>
                                    <span class="text-slate-500 block">{{ __('Handover / Receiving Note:') }}</span>
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
                        {{ __('Carbon Accounting Methodology') }}
                    </h4>
                    <p class="text-xs text-slate-500 leading-relaxed mb-3">
                        {!! __('Estimated greenhouse gas emissions are calculated in accordance with the <strong>Global Logistics Emissions Council (GLEC) Framework v3.0</strong> and IMO MEPC.308 guidelines.') !!}
                    </p>
                    <div class="p-3 bg-slate-50 rounded-xl border border-slate-200 text-xs font-mono text-slate-700 leading-normal">
                        CO₂e (kg) = Weight (t) &times; Distance (km) &times; Factor (g CO₂e/t-km) &divide; 1,000
                    </div>
                    <div class="mt-3 text-[11px] text-slate-400">
                        {{ __('*Estimates provide baseline operational comparability across modal choices and do not substitute certified third-party lifecycle audits.') }}
                    </div>
                </div>

                <!-- Shipper & Consignee Summary -->
                <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-2xs text-xs space-y-4">
                    <div>
                        <div class="text-[10px] uppercase font-bold text-slate-400 tracking-wider">{{ __('Shipper / Originator') }}</div>
                        <div class="font-bold text-slate-900 text-sm mt-0.5">{{ $shipment->sender_business_name }}</div>
                        <div class="text-slate-500 mt-0.5">{{ $shipment->originLocation->city }}, {{ $shipment->originLocation->country }}</div>
                    </div>
                    <div class="pt-3 border-t border-slate-100">
                        <div class="text-[10px] uppercase font-bold text-slate-400 tracking-wider">{{ __('Consignee / Receiver') }}</div>
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
                dragging: true,
                scrollWheelZoom: false, // Page scrolls freely when mouse is over map
                touchZoom: true,
                doubleClickZoom: true,
                boxZoom: true,
                zoomControl: true,
                minZoom: 1, // Allow full world zoom out
                maxBounds: [[-85.06, -180], [85.06, 180]],
                maxBoundsViscosity: 0.1,
            }).setView([mapData[0].origin.lat, mapData[0].origin.lon], 4);

            // Colorful Standard OpenStreetMap Tile Layer
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 18,
                minZoom: 1,
                noWrap: true,
                bounds: [[-85.06, -180], [85.06, 180]],
                attribution: '&copy; OpenStreetMap contributors'
            }).addTo(map);

            // Ctrl + Scroll Wheel to zoom; without Ctrl page scroll remains natural
            const trackingMapEl = document.getElementById('shipmentMap');
            if (trackingMapEl) {
                trackingMapEl.addEventListener('wheel', function (e) {
                    if (e.ctrlKey || e.metaKey) {
                        e.preventDefault();
                        if (e.deltaY < 0) {
                            map.zoomIn();
                        } else {
                            map.zoomOut();
                        }
                    }
                }, { passive: false });
            }

            const isShipmentDelayed = @json($shipment->isDelayed() || $shipment->hasCriticalException());
            const isShipmentDelivered = @json($shipment->isDelivered());

            // Custom SVG Teardrop Pin Marker Generator with Modal Icon (📍)
            function createModePinIcon(colorHex, type, isOrigin) {
                let innerSvg = '';
                if (isOrigin) {
                    innerSvg = `<circle cx="12" cy="11" r="4.2" fill="#FFFFFF"/>`;
                } else if (type === 'delayed' || type === 'exception') {
                    innerSvg = `
                        <path d="M12 6V11.5" stroke="#FFFFFF" stroke-width="2.4" stroke-linecap="round"/>
                        <circle cx="12" cy="15.2" r="1.3" fill="#FFFFFF"/>
                    `;
                } else if (type === 'delivered') {
                    innerSvg = `
                        <path d="M7.5 11.5L10.5 14.5L16.5 8" stroke="#FFFFFF" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"/>
                    `;
                } else if (type === 'air') {
                    innerSvg = `
                        <path d="M12 3.8C11.4 3.8 11.1 4.2 11.1 4.8V8.6L3.8 12.2V13.8L11.1 12.4V15.6L8.4 17.3V18.6L12 17.7L15.6 18.6V17.3L12.9 15.6V12.4L20.2 13.8V12.2L12.9 8.6V4.8C12.9 4.2 12.6 3.8 12 3.8Z" fill="#FFFFFF"/>
                    `;
                } else if (type === 'land') {
                    innerSvg = `
                        <path d="M6.5 9H13V14H6.5V9ZM13 10.5H15.5L17.5 12.5V14H13V10.5Z" fill="#FFFFFF"/>
                        <circle cx="8.5" cy="14.5" r="1.4" fill="#FFFFFF"/>
                        <circle cx="15.5" cy="14.5" r="1.4" fill="#FFFFFF"/>
                    `;
                } else {
                    // Sea / Anchor
                    innerSvg = `
                        <path d="M12 6A1.5 1.5 0 1012 9A1.5 1.5 0 0012 6ZM12 9V15M9 11H15M7 13C7.5 15.5 9.5 16.5 12 16.5C14.5 16.5 16.5 15.5 17 13" stroke="#FFFFFF" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" fill="none"/>
                    `;
                }

                return L.divIcon({
                    className: 'custom-mode-pin',
                    html: `
                        <div style="position: relative; width: 28px; height: 36px; transform: translate(-50%, -100%); cursor: pointer; filter: drop-shadow(0 3px 5px rgba(0,0,0,0.38));">
                            <svg width="28" height="36" viewBox="0 0 24 32" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M12 0C5.37258 0 0 5.37258 0 12C0 19.5 12 32 12 32C12 32 24 19.5 24 12C24 5.37258 18.6274 0 12 0Z" fill="${colorHex}" stroke="#FFFFFF" stroke-width="1.4"/>
                                ${innerSvg}
                            </svg>
                        </div>
                    `,
                    iconSize: [0, 0],
                    iconAnchor: [0, 0],
                    popupAnchor: [0, -34]
                });
            }

            const bounds = [];

            mapData.forEach(function (leg, idx) {
                const originCoords = [leg.origin.lat, leg.origin.lon];
                const destCoords = [leg.destination.lat, leg.destination.lon];
                const isLastLeg = (idx === mapData.length - 1);
                const isLegCompleted = leg.status === 'COMPLETED' || isShipmentDelivered;

                bounds.push(originCoords);
                bounds.push(destCoords);

                let lineColor = '#0284C7';
                let pinType = 'sea';
                let pinColor = '#0284C7';
                let dashArray = '6, 6';

                if (isShipmentDelayed && isLastLeg) {
                    lineColor = '#EF4444'; // Red
                    pinType = 'delayed';
                    pinColor = '#EF4444';
                    dashArray = null;
                } else if (isLegCompleted) {
                    lineColor = '#10B981'; // Green
                    pinType = 'delivered';
                    pinColor = '#10B981';
                    dashArray = null;
                } else if (leg.mode === 'air') {
                    lineColor = '#EC4899'; // Pink
                    pinType = 'air';
                    pinColor = '#EC4899';
                    dashArray = '6, 6';
                } else if (leg.mode === 'land') {
                    lineColor = '#F59E0B'; // Orange
                    pinType = 'land';
                    pinColor = '#F59E0B';
                    dashArray = '6, 6';
                } else {
                    lineColor = '#0284C7'; // Blue
                    pinType = 'sea';
                    pinColor = '#0284C7';
                    dashArray = '6, 6';
                }

                // Origin Marker (Teardrop with Dot)
                L.marker(originCoords, {
                    icon: createModePinIcon(lineColor, pinType, true)
                }).bindPopup('<b>' + leg.origin.name + '</b><br>Origin: ' + leg.origin.type).addTo(map);

                // Destination Marker (Teardrop with Modal Icon)
                L.marker(destCoords, {
                    icon: createModePinIcon(pinColor, pinType, false)
                }).bindPopup('<b>' + leg.destination.name + '</b><br>Destination: ' + leg.destination.type + (isLastLeg && isShipmentDelivered ? '<br><span style="color:#10B981;font-weight:bold;">Delivered</span>' : '')).addTo(map);

                L.polyline([originCoords, destCoords], { color: lineColor, weight: 3.5, opacity: 0.88, dashArray: dashArray })
                    .bindPopup('<b>Leg ' + leg.sequence + ' (' + leg.mode.toUpperCase() + ')</b><br>' + leg.carrier + ' &bull; ' + leg.distance_km + ' km')
                    .addTo(map);
            });

            if (bounds.length > 0) {
                map.fitBounds(bounds, { padding: [50, 50], maxZoom: 6 });
                if (map.getZoom() < 1) {
                    map.setZoom(1);
                }
            }
        });
    </script>
</x-guest-layout>
