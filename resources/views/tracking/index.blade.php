<x-app-layout :title="__('Freight Tracking & Telemetry Console — EcoHorizon Global')">
    <x-slot:header>
        {{ __('Freight Tracking & Telemetry Console') }}
    </x-slot:header>

    <div class="space-y-6">

        @if($shipment)
            <!-- ===================================================================== -->
            <!-- CONSIGNMENT OVERVIEW, ACTION SHORTCUTS & SEARCH                      -->
            <!-- ===================================================================== -->
            <div class="bg-white p-6 rounded-2xl border border-slate-200/90 shadow-2xs space-y-6">
                <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4 pb-6 border-b border-slate-100">
                    <div class="space-y-1.5">
                        <div class="flex flex-wrap items-center gap-2.5">
                            <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-900 font-mono tracking-tight">
                                {{ $shipment->tracking_number }}
                            </h2>
                            <x-status-badge :status="$shipment->current_status" />
                            <span class="inline-flex px-2.5 py-0.5 rounded-full text-xs font-bold uppercase tracking-wider bg-slate-100 text-slate-800 border border-slate-200">
                                {{ $shipment->serviceLevel->code ?? 'STD' }} &bull; {{ __($shipment->serviceLevel->name ?? 'Standard Freight') }}
                            </span>

                            @if($shipment->isDelayed())
                                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-bold bg-amber-50 text-amber-800 border border-amber-300">
                                    <svg class="w-3.5 h-3.5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    {{ __('Schedule Delayed') }}
                                </span>
                            @endif
                        </div>

                        <p class="text-xs sm:text-sm text-slate-600">
                            <span class="font-bold text-slate-800">{{ $shipment->originLocation->name ?? __('Origin Hub') }}</span>
                            <span class="text-slate-400">({{ $shipment->originLocation->country ?? '' }})</span>
                            <span class="text-brand-sky font-bold mx-1.5">&rarr;</span>
                            <span class="font-bold text-slate-800">{{ $shipment->destinationLocation->name ?? __('Destination Hub') }}</span>
                            <span class="text-slate-400">({{ $shipment->destinationLocation->country ?? '' }})</span>
                            @if(auth()->user()->isAdmin() || auth()->user()->isOperator())
                                <span class="text-slate-400 ml-2">&bull; {{ __('Client:') }} <strong class="text-slate-700">{{ $shipment->customer->name ?? __('Enterprise Shipper') }}</strong></span>
                            @endif
                        </p>
                    </div>

                    <!-- Action Shortcut Buttons & Instant Consignment Search -->
                    <div class="flex flex-wrap items-center gap-2.5">
                        <!-- Instant Search Box -->
                        <form action="{{ route('tracking.index') }}" method="GET" class="flex items-center gap-1.5">
                            <div class="relative w-48 sm:w-64">
                                <input type="text" name="tracking_number" 
                                       value="{{ request('tracking_number', $shipment->tracking_number ?? '') }}"
                                       placeholder="{{ __('Tracking No. (EHG-...)') }}"
                                       class="w-full pl-8 pr-2.5 py-1.5 text-xs font-mono rounded-xl border border-slate-200 bg-slate-50/70 focus:bg-white focus:outline-none focus:ring-2 focus:ring-brand-sky text-slate-800 transition-all uppercase placeholder:normal-case">
                                <svg class="w-3.5 h-3.5 text-slate-400 absolute left-2.5 top-2 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                            </div>
                            <button type="submit" 
                                     class="inline-flex items-center gap-1 px-3 py-1.5 rounded-xl text-xs font-bold bg-brand-sky hover:bg-sky-500 text-white shadow-2xs transition-all shrink-0">
                                <span>{{ __('Locate') }}</span>
                            </button>
                        </form>

                        <div class="h-6 w-px bg-slate-200 mx-0.5 hidden sm:block"></div>

                        <a href="{{ route('shipments.show', $shipment->id) }}" 
                           class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl text-xs font-bold bg-slate-900 hover:bg-slate-800 text-white transition-colors shadow-2xs">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                            <span>{{ __('Inspect Cargo Record') }}</span>
                        </a>

                        <a href="{{ route('track.show', $shipment->tracking_number) }}" target="_blank"
                           class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl text-xs font-semibold bg-white border border-slate-300 text-slate-700 hover:bg-slate-50 transition-colors shadow-2xs"
                           title="{{ __('Open public tracking link in new tab') }}">
                            <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                            <span>{{ __('Public View') }}</span>
                        </a>

                        <button type="button" onclick="window.print()"
                                class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl text-xs font-semibold bg-white border border-slate-300 text-slate-700 hover:bg-slate-50 transition-colors shadow-2xs">
                            <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                            <span>{{ __('Print Waybill') }}</span>
                        </button>
                    </div>
                </div>

                <!-- Operational Metrics Strip -->
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                    <div class="p-3 bg-slate-50/70 rounded-xl border border-slate-100">
                        <span class="text-[10px] uppercase font-bold text-slate-400 tracking-wider">{{ __('Estimated Delivery') }}</span>
                        <div class="font-bold text-slate-900 text-sm mt-0.5">
                            {{ $shipment->eta ? $shipment->eta->format('M d, Y H:i') . ' UTC' : __('Pending Scheduling') }}
                        </div>
                        @if($shipment->original_eta && $shipment->eta && $shipment->eta->gt($shipment->original_eta))
                            <span class="text-[10px] text-amber-600 font-medium">{{ __('Was:') }} {{ $shipment->original_eta->format('M d, Y') }}</span>
                        @else
                            <span class="text-[10px] text-slate-400 font-mono">{{ __('On-Time Target') }}</span>
                        @endif
                    </div>

                    <div class="p-3 bg-slate-50/70 rounded-xl border border-slate-100">
                        <span class="text-[10px] uppercase font-bold text-slate-400 tracking-wider">{{ __('Consignment Mass') }}</span>
                        <div class="font-bold text-slate-900 text-sm mt-0.5">
                            {{ number_format($shipment->cargo_weight_tonnes, 2) }} {{ __('tonnes') }}
                        </div>
                        <span class="text-[10px] text-slate-500">{{ $shipment->package_count }}x {{ $shipment->package_type }}</span>
                    </div>

                    <div class="p-3 bg-slate-50/70 rounded-xl border border-slate-100">
                        <span class="text-[10px] uppercase font-bold text-slate-400 tracking-wider">{{ __('Transit Distance') }}</span>
                        <div class="font-bold text-slate-900 text-sm mt-0.5">
                            {{ number_format($shipment->total_distance_km) }} km
                        </div>
                        <span class="text-[10px] text-slate-500">{{ $shipment->transportLegs->count() }} {{ __('Multimodal Legs') }}</span>
                    </div>

                    <div class="p-3 bg-slate-50/70 rounded-xl border border-slate-100">
                        <span class="text-[10px] uppercase font-bold text-teal-700 tracking-wider">{{ __('Carbon Footprint') }}</span>
                        <div class="font-bold text-teal-700 text-sm mt-0.5">
                            {{ number_format($shipment->total_estimated_co2e_kg, 1) }} kg CO₂e
                        </div>
                        <span class="text-[10px] text-slate-500 font-mono">{{ __('GLEC v3.0 Certified') }}</span>
                    </div>
                </div>

                <!-- Step-by-Step Journey Milestones -->
                @php
                    $stages = [
                        ['id' => 'BOOKED', 'label' => __('Order Booked'), 'done' => true],
                        ['id' => 'PICKED_UP', 'label' => __('Origin Pickup'), 'done' => in_array($shipment->current_status, ['PICKED_UP', 'IN_TRANSIT', 'OUT_FOR_DELIVERY', 'ARRIVED_AT_DESTINATION', 'DELIVERED_TO_WAREHOUSE', 'DELIVERED', 'COMPLETED'])],
                        ['id' => 'IN_TRANSIT', 'label' => __('Corridor Transit'), 'done' => in_array($shipment->current_status, ['IN_TRANSIT', 'OUT_FOR_DELIVERY', 'ARRIVED_AT_DESTINATION', 'DELIVERED_TO_WAREHOUSE', 'DELIVERED', 'COMPLETED'])],
                        ['id' => 'CUSTOMS', 'label' => __('Port / Customs Clearance'), 'done' => in_array($shipment->current_status, ['ARRIVED_AT_DESTINATION', 'DELIVERED_TO_WAREHOUSE', 'DELIVERED', 'COMPLETED'])],
                        ['id' => 'DELIVERED', 'label' => __('Final Handover'), 'done' => in_array($shipment->current_status, ['DELIVERED', 'COMPLETED'])],
                    ];
                @endphp
                <div class="pt-2">
                    <div class="relative flex items-center justify-between w-full">
                        <div class="absolute left-0 top-1/2 -translate-y-1/2 w-full h-1 bg-slate-200 z-0"></div>
                        @foreach($stages as $idx => $stg)
                            <div class="relative z-10 flex flex-col items-center group">
                                <div class="w-8 h-8 rounded-full flex items-center justify-center font-bold text-xs transition-transform group-hover:scale-110 shadow-xs
                                    {{ $stg['done'] ? 'bg-emerald-600 text-white ring-4 ring-emerald-50' : 'bg-white border-2 border-slate-300 text-slate-400' }}">
                                    @if($stg['done'])
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                    @else
                                        <span>{{ $idx + 1 }}</span>
                                    @endif
                                </div>
                                <span class="text-[11px] font-semibold mt-2 text-center max-w-[90px] leading-tight {{ $stg['done'] ? 'text-slate-900 font-bold' : 'text-slate-400' }}">
                                    {{ $stg['label'] }}
                                </span>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- ===================================================================== -->
            <!-- 3. MAIN CONSOLE GRID (Leaflet Map, Telemetry, Weather, Timeline)      -->
            <!-- ===================================================================== -->
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-stretch">

                <!-- Left Column (lg:col-span-7): Leaflet Route Map & Event Timeline -->
                <div class="lg:col-span-7 space-y-6">

                    <!-- Interactive Leaflet Corridor Map Card -->
                    <div class="bg-white rounded-2xl border border-slate-200/90 shadow-2xs overflow-hidden flex flex-col">
                        <div class="p-4 border-b border-slate-100 flex items-center justify-between">
                            <div class="flex items-center gap-2">
                                <span class="w-2.5 h-2.5 rounded-full bg-brand-sky animate-ping"></span>
                                <h3 class="text-sm font-bold text-slate-900">{!! __('Geospatial Corridors & Waypoints') !!}</h3>
                            </div>
                            <button type="button" onclick="resetInternalTrackingMap()" 
                                    class="text-xs font-semibold text-brand-sky hover:text-sky-700 flex items-center gap-1">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4"/></svg>
                                <span>{{ __('Reset Map Bounds') }}</span>
                            </button>
                        </div>

                        <!-- Map Canvas Container with ocean background style -->
                        <div class="relative w-full h-[460px] bg-[#aad3df] isolate">
                            <div id="internalTrackingMap" class="absolute inset-0 w-full h-full bg-[#aad3df] z-0"></div>

                            <!-- Sleek Minimalist Floating Legend (Hover Tooltips) -->
                            <div class="absolute bottom-4 left-4 z-20 bg-white/95 backdrop-blur-md px-3 py-1.5 rounded-full border border-slate-200/90 shadow-md flex items-center gap-2.5 text-xs">
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

                        <div class="p-3 bg-slate-50/80 border-t border-slate-100 flex items-center justify-between text-xs text-slate-500">
                            <span>{{ __('Origin:') }} <strong class="text-slate-700">{{ $shipment->originLocation->name ?? '-' }}</strong></span>
                            <span>{{ __('Destination:') }} <strong class="text-slate-700">{{ $shipment->destinationLocation->name ?? '-' }}</strong></span>
                        </div>
                    </div>

                    <!-- Milestones & Telemetry Events Timeline -->
                    <div class="bg-white p-6 rounded-2xl border border-slate-200/90 shadow-2xs">
                        <div class="flex items-center justify-between mb-4 pb-3 border-b border-slate-100">
                            <div>
                                <h3 class="text-sm font-bold text-slate-900">{!! __('Telemetry & Checkpoint Log') !!}</h3>
                                <p class="text-xs text-slate-500">{{ __('Verified sensor checkpoints and custody handover events') }}</p>
                            </div>
                            <span class="text-xs font-mono font-bold text-slate-500 bg-slate-100 px-2 py-0.5 rounded-md">
                                {{ $shipment->trackingEvents->count() }} {{ __('Events') }}
                            </span>
                        </div>

                        @if($shipment->trackingEvents->count() > 0)
                            <div class="relative pl-6 space-y-6 before:absolute before:left-2 before:top-2 before:bottom-2 before:w-0.5 before:bg-slate-200">
                                @foreach($shipment->trackingEvents as $event)
                                    <div class="relative group">
                                        <!-- Dot Indicator -->
                                        <div class="absolute -left-6 top-1 w-3.5 h-3.5 rounded-full border-2 border-white bg-brand-sky shadow-xs group-hover:scale-125 transition-transform"></div>

                                        <div class="p-3 bg-slate-50/70 hover:bg-slate-50 rounded-xl border border-slate-100 transition-colors">
                                            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-1 mb-1">
                                                <div class="flex items-center gap-2">
                                                    <span class="text-xs font-bold text-slate-900">{{ __($event->event_type_name ?? str_replace('_', ' ', $event->event_type)) }}</span>
                                                    @if($event->is_customer_visible)
                                                        <span class="text-[9px] font-bold text-sky-700 bg-sky-50 px-1.5 py-0.5 rounded">{{ __('Customer Visible') }}</span>
                                                    @endif
                                                </div>
                                                <span class="text-[11px] font-mono text-slate-500">
                                                    {{ $event->event_time ? $event->event_time->format('M d, Y H:i') . ' UTC' : '-' }}
                                                </span>
                                            </div>

                                            <p class="text-xs text-slate-600">{{ __($event->description ?? 'Checkpoint verification logged by carrier telemetry.') }}</p>

                                            <div class="mt-2 pt-2 border-t border-slate-200/50 flex flex-wrap items-center justify-between text-[11px] text-slate-500">
                                                <span>{{ __('Location:') }} <strong class="text-slate-700">{{ $event->location->name ?? $event->location_name ?? __('Terminal Relay') }}</strong></span>
                                                @if($event->carrier_name)
                                                    <span>{{ __('Carrier:') }} <strong class="text-slate-700">{{ $event->carrier_name }}</strong></span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="py-8 text-center text-slate-400 text-xs">
                                {{ __('No telemetry checkpoint events recorded for this consignment yet.') }}
                            </div>
                        @endif
                    </div>

                </div>

                <!-- Right Column (lg:col-span-5): Weather, Legs, Carbon Footprint -->
                <div class="lg:col-span-5 space-y-6">

                    <!-- Live Destination Weather Telemetry Widget -->
                    <div class="bg-gradient-to-br from-slate-900 via-slate-800 to-sky-950 text-white p-6 rounded-2xl shadow-md border border-slate-700/80 relative overflow-hidden">
                        <div class="absolute -right-12 -top-12 w-48 h-48 bg-sky-500/10 rounded-full blur-2xl pointer-events-none"></div>

                        <div class="relative z-10 space-y-4">
                            <div class="flex items-center justify-between pb-3 border-b border-slate-700/80">
                                <div>
                                    <div class="text-[10px] font-bold uppercase tracking-wider text-sky-400">{{ __('Terminal Atmospheric Sensor') }}</div>
                                    <h3 class="text-sm font-extrabold text-white">
                                        {{ $weatherData['city'] ?? $shipment->destinationLocation->city ?? __('Destination Hub') }}
                                        <span class="text-slate-400 font-normal">({{ $weatherData['country'] ?? $shipment->destinationLocation->country ?? '' }})</span>
                                    </h3>
                                </div>
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-bold font-mono {{ ($weatherData['is_adverse'] ?? false) ? 'bg-amber-500/20 text-amber-300 border border-amber-500/40' : 'bg-emerald-500/20 text-emerald-300 border border-emerald-500/40' }}">
                                    {{ ($weatherData['is_adverse'] ?? false) ? __('Adverse Risk') : __('Operational') }}
                                </span>
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div class="p-3 bg-slate-800/60 rounded-xl border border-slate-700/60">
                                    <span class="text-[10px] uppercase font-bold text-slate-400">{{ __('Surface Temp') }}</span>
                                    <div class="text-2xl font-black text-white mt-0.5">
                                        {{ isset($weatherData['temperature_c']) ? round($weatherData['temperature_c'], 1) . '°C' : '23.5°C' }}
                                    </div>
                                    <span class="text-[10px] text-sky-300">{{ __($weatherData['condition'] ?? 'Clear Skies') }}</span>
                                </div>

                                <div class="p-3 bg-slate-800/60 rounded-xl border border-slate-700/60">
                                    <span class="text-[10px] uppercase font-bold text-slate-400">{{ __('Wind Velocity') }}</span>
                                    <div class="text-2xl font-black text-white mt-0.5">
                                        {{ isset($weatherData['wind_speed_kmh']) ? round($weatherData['wind_speed_kmh'], 1) : '14' }}
                                        <span class="text-xs font-normal text-slate-400">km/h</span>
                                    </div>
                                    <span class="text-[10px] text-slate-400 font-mono">{{ __('Atmospheric Flow') }}</span>
                                </div>
                            </div>

                            <div class="p-3 bg-slate-800/40 rounded-xl border border-slate-700/50 text-[11px] text-slate-300 flex items-start gap-2">
                                <svg class="w-4 h-4 text-sky-400 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                <span>{{ __($weatherData['disruption_risk'] ?? 'Normal meteorological conditions — Flight and harbor transits proceeding on scheduled corridors.') }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Multimodal Transport Leg Manifest -->
                    <div class="bg-white p-5 rounded-2xl border border-slate-200/90 shadow-2xs">
                        <div class="flex items-center justify-between mb-3 pb-2 border-b border-slate-100">
                            <div>
                                <h3 class="text-sm font-bold text-slate-900">{{ __('Multimodal Transit Segments') }}</h3>
                                <p class="text-[11px] text-slate-500">{{ __('Transshipment legs and assigned commercial carriers') }}</p>
                            </div>
                            <span class="text-[10px] font-bold text-slate-700 bg-slate-100 px-2 py-0.5 rounded-md">
                                {{ $shipment->transportLegs->count() }} {{ __('Legs') }}
                            </span>
                        </div>

                        <div class="space-y-2.5">
                            @forelse($shipment->transportLegs as $leg)
                                @php
                                    $modeIcon = match($leg->transport_mode) {
                                        'air' => '✈️',
                                        'land' => '🚛',
                                        default => '🚢'
                                    };
                                    $legStatusClass = match($leg->status) {
                                        'COMPLETED' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                        'IN_TRANSIT' => 'bg-sky-50 text-sky-700 border-sky-200',
                                        default => 'bg-slate-50 text-slate-600 border-slate-200'
                                    };
                                @endphp
                                <div class="p-3 rounded-xl border border-slate-100 hover:border-slate-200 bg-slate-50/50 transition-colors">
                                    <div class="flex items-center justify-between mb-1">
                                        <div class="flex items-center gap-1.5">
                                            <span class="text-sm">{{ $modeIcon }}</span>
                                            <span class="text-xs font-bold text-slate-900">{{ __('Leg') }} {{ $leg->sequence_number }}: {{ __(ucfirst($leg->transport_mode)) }}</span>
                                        </div>
                                        <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[10px] font-bold border {{ $legStatusClass }}">
                                            {{ __(str_replace('_', ' ', $leg->status)) }}
                                        </span>
                                    </div>
                                    <div class="text-[11px] text-slate-600 font-medium truncate">
                                        {{ $leg->originLocation->city ?? $leg->originLocation->name ?? '-' }} &rarr; {{ $leg->destinationLocation->city ?? $leg->destinationLocation->name ?? '-' }}
                                    </div>
                                    <div class="mt-1 flex items-center justify-between text-[10px] text-slate-500 font-mono">
                                        <span>{{ __('Carrier:') }} <strong class="text-slate-700">{{ $leg->carrier_name ?? __('Designated Fleet') }}</strong></span>
                                        <span>{{ number_format($leg->distance_km ?? 0) }} km</span>
                                    </div>
                                </div>
                            @empty
                                <div class="py-4 text-center text-slate-400 text-xs">
                                    {{ __('No transit legs defined for this shipment.') }}
                                </div>
                            @endforelse
                        </div>
                    </div>

                    <!-- Shipper & Consignee Summary -->
                    <div class="bg-white p-5 rounded-2xl border border-slate-200/90 shadow-2xs text-xs space-y-3.5">
                        <div class="pb-2 border-b border-slate-100">
                            <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400">{{ __('Commercial Parties') }}</h3>
                        </div>
                        <div>
                            <span class="text-[10px] font-bold uppercase text-slate-400">{{ __('Shipper / Originator') }}</span>
                            <div class="font-bold text-slate-900 mt-0.5">{{ $shipment->sender_business_name ?? __('Consignor Entity') }}</div>
                            <div class="text-slate-500 text-[11px]">{{ $shipment->originLocation->name ?? '-' }} ({{ $shipment->originLocation->country ?? '' }})</div>
                        </div>
                        <div class="pt-2 border-t border-slate-100">
                            <span class="text-[10px] font-bold uppercase text-slate-400">{{ __('Consignee / Receiver') }}</span>
                            <div class="font-bold text-slate-900 mt-0.5">{{ $shipment->receiver_business_name ?? __('Consignee Entity') }}</div>
                            <div class="text-slate-500 text-[11px]">{{ $shipment->destinationLocation->name ?? '-' }} ({{ $shipment->destinationLocation->country ?? '' }})</div>
                        </div>
                    </div>

                </div>

            </div>
        @else
            <!-- Empty State when no shipment is found -->
            <div class="bg-white p-12 rounded-2xl border border-slate-200 text-center max-w-lg mx-auto my-12 space-y-4">
                <div class="w-16 h-16 bg-sky-50 text-brand-sky rounded-2xl flex items-center justify-center mx-auto shadow-xs">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                </div>
                <div>
                    <h3 class="text-base font-bold text-slate-900">{{ __('No Consignment Record Selected') }}</h3>
                    <p class="text-xs text-slate-500 mt-1">
                        {{ __('Please enter a valid tracking reference number to inspect live telemetry.') }}
                    </p>
                </div>
                <form action="{{ route('tracking.index') }}" method="GET" class="flex items-center justify-center gap-2 max-w-xs mx-auto pt-2">
                    <input type="text" name="tracking_number" 
                           placeholder="{{ __('Enter Consignment No...') }}"
                           class="w-full px-3 py-2 text-xs font-mono rounded-xl border border-slate-200 bg-slate-50/70 focus:bg-white focus:outline-none focus:ring-2 focus:ring-brand-sky text-slate-800 uppercase placeholder:normal-case">
                    <button type="submit" 
                            class="px-4 py-2 rounded-xl text-xs font-bold bg-brand-sky text-white hover:bg-sky-500 transition-colors shrink-0 shadow-2xs">
                        {{ __('Locate') }}
                    </button>
                </form>
                <div class="pt-2">
                    <a href="{{ route('shipments.index') }}" 
                       class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl text-xs font-bold bg-slate-100 text-slate-700 hover:bg-slate-200 transition-colors">
                        <span>{{ __('Browse All Consignments') }}</span>
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                    </a>
                </div>
            </div>
        @endif

    </div>

    <!-- Map Tile Water Background Style (Ensures zero white borders on any screen resolution) -->
    <style>
        #internalTrackingMap, #internalTrackingMap .leaflet-container, #internalTrackingMap .leaflet-tile-pane {
            background-color: #aad3df !important;
        }
    </style>

    <!-- ========================================================================= -->
    <!-- LEAFLET.JS ROUTE MAP INITIALIZATION                                       -->
    <!-- ========================================================================= -->
    @if($shipment && !empty($mapRoute))
        <script>
            let internalTrackingMap = null;
            let internalMapBounds = null;

            document.addEventListener('DOMContentLoaded', function () {
                if (typeof L === 'undefined') return;

                const mapData = @json($mapRoute);
                if (!mapData || mapData.length === 0) return;

                const mapContainer = document.getElementById('internalTrackingMap');
                if (!mapContainer) return;

                internalTrackingMap = L.map('internalTrackingMap', {
                    scrollWheelZoom: false,
                    zoomControl: false,
                    minZoom: 1,
                    maxZoom: 18,
                    maxBounds: [[-85.06, -180], [85.06, 180]],
                    maxBoundsViscosity: 0.2,
                });

                L.control.zoom({ position: 'topright' }).addTo(internalTrackingMap);

                // Colorful Standard OpenStreetMap Tile Layer
                L.tileLayer('{{ config('services.maps.tile_url', 'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png') }}', {
                    maxZoom: 18,
                    minZoom: 1,
                    noWrap: true,
                    bounds: [[-85.06, -180], [85.06, 180]],
                    attribution: '{!! addslashes(config('services.maps.attribution', '&copy; OpenStreetMap contributors')) !!}'
                }).addTo(internalTrackingMap);

                // Ctrl+Wheel Zoom Handler
                mapContainer.addEventListener('wheel', function (e) {
                    if (e.ctrlKey || e.metaKey) {
                        e.preventDefault();
                        if (e.deltaY < 0) {
                            internalTrackingMap.zoomIn();
                        } else {
                            internalTrackingMap.zoomOut();
                        }
                    }
                }, { passive: false });

                const isShipmentDelayed = @json($shipment->isDelayed() || $shipment->hasCriticalException());
                const isShipmentDelivered = @json($shipment->isDelivered());

                // Custom SVG Teardrop Pin with Mode Icon (📍)
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

                const latLngs = [];

                mapData.forEach(function (leg, idx) {
                    const originCoord = [leg.origin.lat, leg.origin.lon];
                    const destCoord = [leg.destination.lat, leg.destination.lon];

                    latLngs.push(originCoord);
                    latLngs.push(destCoord);

                    const isFinalLeg = (idx === mapData.length - 1);
                    const isLegCompleted = leg.status === 'COMPLETED' || isShipmentDelivered;

                    let routeColor = '#0284C7'; // Blue default
                    let pinType = 'sea';
                    let pinColor = '#0284C7';
                    let dashArray = '6, 6';

                    if (isShipmentDelayed && isFinalLeg) {
                        routeColor = '#EF4444'; // Red for delay
                        pinType = 'delayed';
                        pinColor = '#EF4444';
                        dashArray = null;
                    } else if (isLegCompleted) {
                        routeColor = '#10B981'; // Green for delivered
                        pinType = 'delivered';
                        pinColor = '#10B981';
                        dashArray = null;
                    } else if (leg.mode === 'air') {
                        routeColor = '#EC4899'; // Pink for air
                        pinType = 'air';
                        pinColor = '#EC4899';
                        dashArray = '6, 6';
                    } else if (leg.mode === 'land') {
                        routeColor = '#F59E0B'; // Orange for land
                        pinType = 'land';
                        pinColor = '#F59E0B';
                        dashArray = '6, 6';
                    } else {
                        routeColor = '#0284C7'; // Blue for sea
                        pinType = 'sea';
                        pinColor = '#0284C7';
                        dashArray = '6, 6';
                    }

                    // Origin Pin (Teardrop with Dot)
                    L.marker(originCoord, {
                        icon: createModePinIcon(routeColor, pinType, true)
                    }).bindPopup('<b>' + leg.origin.name + '</b><br><span class="text-xs text-slate-500">' + (leg.origin.city || '') + ' (' + (leg.origin.type || '') + ')</span>')
                      .addTo(internalTrackingMap);

                    // Destination Pin (Teardrop with Modal Icon)
                    L.marker(destCoord, {
                        icon: createModePinIcon(pinColor, pinType, false)
                    }).bindPopup('<b>' + leg.destination.name + '</b><br><span class="text-xs text-slate-500">' + (leg.destination.city || '') + ' (' + (leg.destination.type || '') + ')</span>')
                      .addTo(internalTrackingMap);

                    // Route Polyline
                    L.polyline([originCoord, destCoord], {
                        color: routeColor,
                        weight: 3.5,
                        opacity: 0.88,
                        dashArray: dashArray
                    }).bindPopup('<b>Leg ' + leg.sequence + ': ' + leg.mode.toUpperCase() + '</b><br>' + leg.carrier + '<br>' + Number(leg.distance_km || 0).toFixed(0) + ' km')
                      .addTo(internalTrackingMap);
                });

                if (latLngs.length > 0) {
                    internalMapBounds = L.latLngBounds(latLngs);
                    internalTrackingMap.fitBounds(internalMapBounds, { padding: [50, 50], maxZoom: 8 });
                } else {
                    internalTrackingMap.setView([20.0, 15.0], 2);
                }

                window.resetInternalTrackingMap = function () {
                    if (internalTrackingMap && internalMapBounds) {
                        internalTrackingMap.invalidateSize();
                        internalTrackingMap.fitBounds(internalMapBounds, { padding: [50, 50], maxZoom: 8 });
                    }
                };

                setTimeout(function () {
                    if (internalTrackingMap) {
                        internalTrackingMap.invalidateSize();
                    }
                }, 200);

                window.addEventListener('resize', function () {
                    if (internalTrackingMap) internalTrackingMap.invalidateSize();
                });
            });
        </script>
    @endif
</x-app-layout>
