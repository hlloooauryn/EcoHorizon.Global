<x-app-layout title="{{ __('Global Freight Visibility Dashboard') }} — EcoHorizon Global">
    <div class="space-y-6">
        
        <!-- ========================================================================= -->
        <!-- 1. EXECUTIVE COMMAND HEADER & LIVE TELEMETRY BAR                          -->
        <!-- ========================================================================= -->
        <div class="bg-gradient-to-r from-slate-900 via-slate-800 to-sky-950 rounded-2xl p-6 text-white shadow-lg border border-slate-700/60 relative overflow-hidden">
            <!-- Subtle background accent glow -->
            <div class="absolute -right-20 -top-20 w-80 h-80 bg-brand-sky/20 rounded-full blur-3xl pointer-events-none"></div>
            <div class="absolute right-1/3 -bottom-20 w-64 h-64 bg-emerald-500/10 rounded-full blur-3xl pointer-events-none"></div>

            <div class="relative z-10 flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
                <div>
                    <div class="flex items-center gap-2.5 mb-2">
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-emerald-500/20 text-emerald-300 border border-emerald-500/30">
                            <span class="w-2 h-2 rounded-full bg-emerald-400 animate-ping"></span>
                            <span class="w-2 h-2 rounded-full bg-emerald-400 absolute"></span>
                            {{ __('Live Global Corridors Active') }}
                        </span>
                        <span class="text-xs font-mono text-slate-400 border-l border-slate-700 pl-2.5">
                            {{ __('UTC Synchronized • 100% Operational') }}
                        </span>
                    </div>
                    <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-white">
                        {{ __('Global Visibility Command Center') }}
                    </h1>
                    <p class="text-xs sm:text-sm text-slate-300 mt-1 max-w-2xl leading-relaxed">
                        <span class="font-bold text-white">{{ __('System Executive Overview') }}</span> &bull; {{ __('Continuous telemetry and multimodal freight tracking across international air, ocean, and overland logistics corridors.') }}
                    </p>
                </div>

                <div class="flex flex-wrap items-center gap-3">
                    <a href="{{ route('shipments.index') }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl text-xs font-bold bg-slate-800/80 hover:bg-slate-700 text-slate-200 border border-slate-600 transition-all shadow-sm">
                        <svg class="w-4 h-4 text-sky-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/></svg>
                        <span>{{ __('Filter All Shipments') }}</span>
                    </a>
                    <button type="button" onclick="window.print()" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl text-xs font-bold bg-slate-800/80 hover:bg-slate-700 text-slate-200 border border-slate-600 transition-all shadow-sm">
                        <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                        <span>{{ __('Export Report') }}</span>
                    </button>
                    <a href="{{ route('shipments.create') }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl text-xs font-bold bg-brand-sky hover:bg-sky-500 text-white shadow-md transition-all">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        <span>{{ __('New Freight Booking') }}</span>
                    </a>
                </div>
            </div>

            <!-- Quick Telemetry Counters Ribbon -->
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mt-6 pt-5 border-t border-slate-700/60 text-xs">
                <div>
                    <span class="text-slate-400 font-medium block">{{ __('Total Active Tonnage') }}</span>
                    <span class="text-base font-extrabold text-white font-mono mt-0.5 block">{{ number_format($stats['total_cargo_tonnes'], 1) }} Tonnes</span>
                </div>
                <div>
                    <span class="text-slate-400 font-medium block">{{ __('Global Transit Distance') }}</span>
                    <span class="text-base font-extrabold text-sky-400 font-mono mt-0.5 block">{{ number_format($stats['total_distance_km'], 0) }} km</span>
                </div>
                <div>
                    <span class="text-slate-400 font-medium block">{{ __('On-Time SLA Rate') }}</span>
                    <span class="text-base font-extrabold text-emerald-400 font-mono mt-0.5 block">{{ $stats['on_time_rate'] }}%</span>
                </div>
                <div>
                    <span class="text-slate-400 font-medium block">{{ __('Total Calculated CO2e') }}</span>
                    <span class="text-base font-extrabold text-amber-300 font-mono mt-0.5 block">{{ number_format($stats['total_co2e_tonnes'], 2) }} t CO2e</span>
                </div>
            </div>
        </div>

        <!-- ========================================================================= -->
        <!-- 2. HIGH-IMPACT EXECUTIVE KPI METRICS                                      -->
        <!-- ========================================================================= -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
            
            <!-- KPI 1: Total Global Shipments -->
            <div class="bg-white p-5 rounded-2xl border border-slate-200/90 shadow-xs hover:shadow-md transition-shadow">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider">{{ __('Total Freight') }}</span>
                    <div class="w-10 h-10 rounded-xl bg-sky-50 text-brand-sky flex items-center justify-center shrink-0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                    </div>
                </div>
                <div class="text-3xl font-extrabold text-slate-900 font-mono">{{ $stats['total_shipments'] }}</div>
                <div class="flex items-center justify-between text-xs text-slate-500 mt-2 pt-2 border-t border-slate-100">
                    <span>{{ __('Worldwide Registry') }}</span>
                    <span class="text-emerald-600 font-bold flex items-center gap-0.5">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"/></svg>
                        {{ __('100% Tracked') }}
                    </span>
                </div>
            </div>

            <!-- KPI 2: Active In Transit -->
            <div class="bg-white p-5 rounded-2xl border border-slate-200/90 shadow-xs hover:shadow-md transition-shadow">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider">{{ __('In Transit') }}</span>
                    <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center shrink-0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                    </div>
                </div>
                <div class="text-3xl font-extrabold text-blue-600 font-mono flex items-center gap-2">
                    <span>{{ $stats['in_transit'] }}</span>
                    <span class="w-2.5 h-2.5 rounded-full bg-blue-500 animate-pulse"></span>
                </div>
                <div class="flex items-center justify-between text-xs text-slate-500 mt-2 pt-2 border-t border-slate-100">
                    <span>{{ __('Active Corridors') }}</span>
                    <span class="font-mono text-slate-700 font-bold">
                        {{ $stats['sea_legs'] }} 🚢 {{ $stats['air_legs'] }} ✈️ {{ $stats['land_legs'] }} 🚛
                    </span>
                </div>
            </div>

            <!-- KPI 3: Delivered Successfully -->
            <div class="bg-white p-5 rounded-2xl border border-slate-200/90 shadow-xs hover:shadow-md transition-shadow">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider">{{ __('Delivered') }}</span>
                    <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center shrink-0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                </div>
                <div class="text-3xl font-extrabold text-emerald-600 font-mono">{{ $stats['delivered'] }}</div>
                <div class="flex items-center justify-between text-xs text-slate-500 mt-2 pt-2 border-t border-slate-100">
                    <span>{{ __('Verified Proof of Delivery') }}</span>
                    <span class="text-emerald-700 font-bold bg-emerald-50 px-2 py-0.5 rounded">{{ __('Completed') }}</span>
                </div>
            </div>

            <!-- KPI 4: Exceptions & Delays -->
            <div class="bg-white p-5 rounded-2xl border border-slate-200/90 shadow-xs hover:shadow-md transition-shadow">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider">{{ __('Disruptions & Delays') }}</span>
                    <div class="w-10 h-10 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center shrink-0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                    </div>
                </div>
                <div class="text-3xl font-extrabold text-amber-600 font-mono flex items-center gap-2">
                    <span>{{ $stats['delayed'] + $stats['open_exceptions'] }}</span>
                    @if($stats['open_exceptions'] > 0)
                        <span class="text-xs font-bold text-red-600 bg-red-50 border border-red-200 px-1.5 py-0.5 rounded-full">
                            {{ $stats['open_exceptions'] }} {{ __('Critical') }}
                        </span>
                    @endif
                </div>
                <div class="flex items-center justify-between text-xs text-slate-500 mt-2 pt-2 border-t border-slate-100">
                    <span>{{ __('Requires Operator Action') }}</span>
                    <a href="{{ route('shipments.index', ['exception_status' => 'active']) }}" class="text-amber-700 font-bold hover:underline">
                        {{ __('Review') }} &rarr;
                    </a>
                </div>
            </div>

            <!-- KPI 5: Carbon Footprint Aggregation -->
            <div class="bg-white p-5 rounded-2xl border border-slate-200/90 shadow-xs hover:shadow-md transition-shadow">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider">{{ __('Carbon Footprint') }}</span>
                    <div class="w-10 h-10 rounded-xl bg-teal-50 text-teal-600 flex items-center justify-center shrink-0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                </div>
                <div class="text-3xl font-extrabold text-teal-700 font-mono">{{ number_format($stats['total_co2e_tonnes'], 1) }}<span class="text-sm font-sans text-slate-500 ml-1 font-semibold">t CO2e</span></div>
                <div class="flex items-center justify-between text-xs text-slate-500 mt-2 pt-2 border-t border-slate-100">
                    <span>GLEC / ISO 14083</span>
                    <a href="{{ route('sustainability.index') }}" class="text-teal-700 font-bold hover:underline">
                        {{ __('Audit') }} &rarr;
                    </a>
                </div>
            </div>

        </div>

        <!-- ========================================================================= -->
        <!-- 3. DUAL-COLUMN LOGISTICS RADAR & CORRIDOR COMMAND (58% Map / 42% Panel)    -->
        <!-- ========================================================================= -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-stretch">

            <!-- Left Column (~58% width): Bounded Single World Map Radar -->
            <div id="dashboardMapCard" class="lg:col-span-7 bg-white rounded-2xl border border-slate-200/90 shadow-xs overflow-hidden scroll-mt-20 flex flex-col relative isolate">
                <!-- Header bar with Title, Status Pills, and Map View Controls -->
                <div class="p-5 border-b border-slate-200/80 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 shrink-0">
                    <div>
                        <div class="flex items-center gap-2 mb-1">
                            <span class="inline-flex items-center gap-1.5 text-xs font-bold text-brand-sky bg-sky-50 px-2.5 py-1 rounded-lg border border-sky-100">
                                <span class="w-2 h-2 rounded-full bg-brand-sky animate-ping"></span>
                                {{ __('Live Worldwide Operations') }}
                            </span>
                            <span class="text-xs text-slate-400 font-mono hidden sm:inline">&bull; {{ __('Satellite Telemetry') }}</span>
                        </div>
                        <h2 class="text-base sm:text-lg font-extrabold text-slate-900 tracking-tight">
                            {!! __('Interactive Global Corridor & Shipment Radar') !!}
                        </h2>
                        <p class="text-xs text-slate-500 mt-0.5">
                            {{ __('Real-time geospatial tracking across international sea lanes, air corridors, and overland transit relays.') }}
                        </p>
                    </div>

                    <!-- Interactive Filters & Zoom Controls -->
                    <div class="flex flex-wrap items-center gap-2 shrink-0">
                        <!-- Status Filter Tabs for Map -->
                        <div class="inline-flex rounded-xl border border-slate-200 bg-slate-50/80 p-1 text-xs font-semibold">
                            <button type="button" onclick="filterMapByStatus('ALL')" id="map-filter-ALL"
                                    class="map-filter-btn active px-2.5 py-1.5 rounded-lg bg-white text-brand-sky border border-slate-200 shadow-2xs transition-all font-bold">
                                {{ __('All') }} ({{ $mapFilterCounts['all'] ?? count($mapShipments) }})
                            </button>
                            <button type="button" onclick="filterMapByStatus('IN_TRANSIT')" id="map-filter-IN_TRANSIT"
                                    class="map-filter-btn px-2.5 py-1.5 rounded-lg text-slate-600 hover:text-slate-900 transition-all font-medium">
                                {{ __('In Transit') }} ({{ $mapFilterCounts['in_transit'] ?? $statusCounts['in_transit'] }})
                            </button>
                            <button type="button" onclick="filterMapByStatus('DELIVERED')" id="map-filter-DELIVERED"
                                    class="map-filter-btn px-2.5 py-1.5 rounded-lg text-slate-600 hover:text-slate-900 transition-all font-medium">
                                {{ __('Delivered') }} ({{ $mapFilterCounts['delivered'] ?? $statusCounts['delivered'] }})
                            </button>
                            <button type="button" onclick="filterMapByStatus('DELAYED')" id="map-filter-DELAYED"
                                    class="map-filter-btn px-2.5 py-1.5 rounded-lg text-slate-600 hover:text-slate-900 transition-all font-medium">
                                {{ __('Delayed') }} ({{ $mapFilterCounts['delayed'] ?? ($statusCounts['delayed'] + $statusCounts['open_exceptions']) }})
                            </button>
                            <button type="button" onclick="filterMapByStatus('HISTORY')" id="map-filter-HISTORY"
                                    class="map-filter-btn px-2.5 py-1.5 rounded-lg text-slate-600 hover:text-slate-900 transition-all font-medium">
                                {{ __('History') }} ({{ $mapFilterCounts['history'] ?? 0 }})
                            </button>
                        </div>

                        <!-- Globe Map Style Toggle (Satellite Earth vs Clean Vector) -->
                        <div class="inline-flex rounded-xl border border-slate-200 bg-slate-50/80 p-0.5 text-xs font-semibold">
                            <button type="button" onclick="switchMapStyle('satellite')" id="btn-map-satellite"
                                    class="map-style-btn active px-2.5 py-1 rounded-lg bg-white text-brand-sky border border-slate-200 shadow-2xs transition-all font-bold flex items-center gap-1.5"
                                    title="{{ __('Satellite Earth Globe (Photo Reference Style)') }}">
                                <span>🛰️</span>
                                <span class="hidden sm:inline">{{ __('Satellite') }}</span>
                            </button>
                            <button type="button" onclick="switchMapStyle('vector')" id="btn-map-vector"
                                    class="map-style-btn px-2.5 py-1 rounded-lg text-slate-600 hover:text-slate-900 transition-all font-medium flex items-center gap-1.5"
                                    title="{{ __('Clean Vector Map') }}">
                                <span>🗺️</span>
                                <span class="hidden sm:inline">{{ __('Vector') }}</span>
                            </button>
                        </div>

                        <!-- Reset World View Button -->
                        <button type="button" onclick="resetDashboardMapWorld()" 
                                class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-bold bg-white hover:bg-slate-50 text-slate-700 border border-slate-200 shadow-2xs transition-colors"
                                title="{{ __('Reset view to whole world') }}">
                            <svg class="w-3.5 h-3.5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <span>{{ __('World View') }}</span>
                        </button>
                    </div>
                </div>

                <!-- Leaflet Map Canvas (100% Full Height Fill, Deep Space / Oceanic Background) -->
                <div class="relative flex-1 w-full min-h-[520px]">
                    <div id="dashboardMap" class="absolute inset-0 w-full h-full bg-[#08162b] z-0" style="background-color: #08162b !important;"></div>

                    <!-- Sleek Minimalist Floating Legend (Hover Tooltips + Click Filter) -->
                    <div class="absolute bottom-4 left-4 z-20 bg-white/95 backdrop-blur-md px-3 py-1.5 rounded-full border border-slate-200/90 shadow-md flex items-center gap-2.5 text-xs">
                        <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 pl-0.5">{{ __('Legend') }}</span>
                        <div class="h-3.5 w-px bg-slate-200"></div>
                        <div class="flex items-center gap-2">
                            <!-- Red: Exception / Delayed -->
                            <button type="button" onclick="filterMapByStatus('DELAYED')" 
                                    class="group relative w-3.5 h-3.5 rounded-full bg-[#EF4444] shadow-2xs hover:scale-125 transition-transform cursor-pointer focus:outline-none"
                                    title="{{ __('Red: Exception / Delayed (Click to filter)') }}">
                                <span class="pointer-events-none absolute bottom-full left-1/2 -translate-x-1/2 mb-2 hidden group-hover:flex flex-col items-center z-50">
                                    <span class="bg-slate-900 text-white text-[10px] font-bold px-2 py-1 rounded shadow-md whitespace-nowrap">
                                        {{ __('Red • ⚠️ Exception / Delayed') }}
                                    </span>
                                    <span class="w-1.5 h-1.5 bg-slate-900 rotate-45 -mt-0.5"></span>
                                </span>
                            </button>

                            <!-- Pink: Air Cargo -->
                            <button type="button" onclick="filterMapByStatus('AIR')" 
                                    class="group relative w-3.5 h-3.5 rounded-full bg-[#EC4899] shadow-2xs hover:scale-125 transition-transform cursor-pointer focus:outline-none"
                                    title="{{ __('Pink: ✈️ Air Cargo (Click to filter)') }}">
                                <span class="pointer-events-none absolute bottom-full left-1/2 -translate-x-1/2 mb-2 hidden group-hover:flex flex-col items-center z-50">
                                    <span class="bg-slate-900 text-white text-[10px] font-bold px-2 py-1 rounded shadow-md whitespace-nowrap">
                                        {{ __('Pink • ✈️ Air Cargo In-Transit') }}
                                    </span>
                                    <span class="w-1.5 h-1.5 bg-slate-900 rotate-45 -mt-0.5"></span>
                                </span>
                            </button>

                            <!-- Blue: Sea Freight -->
                            <button type="button" onclick="filterMapByStatus('SEA')" 
                                    class="group relative w-3.5 h-3.5 rounded-full bg-[#0284C7] shadow-2xs hover:scale-125 transition-transform cursor-pointer focus:outline-none"
                                    title="{{ __('Blue: ⚓ Sea Freight (Click to filter)') }}">
                                <span class="pointer-events-none absolute bottom-full left-1/2 -translate-x-1/2 mb-2 hidden group-hover:flex flex-col items-center z-50">
                                    <span class="bg-slate-900 text-white text-[10px] font-bold px-2 py-1 rounded shadow-md whitespace-nowrap">
                                        {{ __('Blue • ⚓ Sea Freight In-Transit') }}
                                    </span>
                                    <span class="w-1.5 h-1.5 bg-slate-900 rotate-45 -mt-0.5"></span>
                                </span>
                            </button>

                            <!-- Orange: Land Transit -->
                            <button type="button" onclick="filterMapByStatus('LAND')" 
                                    class="group relative w-3.5 h-3.5 rounded-full bg-[#F59E0B] shadow-2xs hover:scale-125 transition-transform cursor-pointer focus:outline-none"
                                    title="{{ __('Orange: 🚛 Land Transit (Click to filter)') }}">
                                <span class="pointer-events-none absolute bottom-full left-1/2 -translate-x-1/2 mb-2 hidden group-hover:flex flex-col items-center z-50">
                                    <span class="bg-slate-900 text-white text-[10px] font-bold px-2 py-1 rounded shadow-md whitespace-nowrap">
                                        {{ __('Orange • 🚛 Land Transit In-Transit') }}
                                    </span>
                                    <span class="w-1.5 h-1.5 bg-slate-900 rotate-45 -mt-0.5"></span>
                                </span>
                            </button>

                            <!-- Green: Delivered -->
                            <button type="button" onclick="filterMapByStatus('DELIVERED')" 
                                    class="group relative w-3.5 h-3.5 rounded-full bg-[#10B981] shadow-2xs hover:scale-125 transition-transform cursor-pointer focus:outline-none"
                                    title="{{ __('Green: ✓ Delivered (≤ 7 Days) (Click to filter)') }}">
                                <span class="pointer-events-none absolute bottom-full left-1/2 -translate-x-1/2 mb-2 hidden group-hover:flex flex-col items-center z-50">
                                    <span class="bg-slate-900 text-white text-[10px] font-bold px-2 py-1 rounded shadow-md whitespace-nowrap">
                                        {{ __('Green • ✓ Delivered (≤ 7d)') }}
                                    </span>
                                    <span class="w-1.5 h-1.5 bg-slate-900 rotate-45 -mt-0.5"></span>
                                </span>
                            </button>
                        </div>
                    </div>

                    <!-- Floating Helper (Bottom Right) -->
                    <div class="hidden sm:block absolute bottom-4 right-14 z-20 bg-slate-900/80 backdrop-blur-md text-white text-[11px] px-3 py-1.5 rounded-lg shadow-sm font-mono pointer-events-none">
                        {{ __('Click route for cargo • Ctrl + Scroll to zoom') }}
                    </div>
                </div>

                <!-- Map Footer Stats Strip -->
                <div class="p-3.5 bg-slate-50/80 border-t border-slate-200/80 flex flex-wrap items-center justify-between gap-2 text-xs text-slate-600 shrink-0">
                    <div class="flex items-center gap-3">
                        <span>{{ __('Coverage') }}: <strong class="text-slate-800">5 {{ __('Continents') }}</strong></span>
                        <span class="text-slate-300">|</span>
                        <span>{{ __('Terminals') }}: <strong class="text-slate-800">20+ {{ __('International Hubs') }}</strong></span>
                    </div>
                    <div class="flex items-center gap-1.5 font-mono">
                        <span class="text-slate-500">{{ __('Corridors Active') }}:</span>
                        <span class="font-bold text-brand-sky">{{ count($mapShipments) }} {{ __('Shipments') }}</span>
                    </div>
                </div>
            </div>

            <!-- Right Column (~42% width): Strategic Trade Corridors & Real-Time Operations -->
            <div class="lg:col-span-5 flex flex-col gap-6">

                <!-- Card 1: Strategic Trade Corridors -->
                <div class="bg-white p-5 rounded-2xl border border-slate-200/90 shadow-xs flex flex-col justify-between">
                    <div>
                        <div class="flex items-center justify-between mb-1">
                            <h2 class="text-sm font-bold text-slate-900">{{ __('Strategic Trade Corridors') }}</h2>
                            <span class="text-[10px] font-bold text-slate-700 bg-slate-100 px-2 py-0.5 rounded-md">
                                5 {{ __('Active Relays') }}
                            </span>
                        </div>
                        <p class="text-xs text-slate-500 mb-3">{{ __('Traffic velocity & conditions across global lanes') }}</p>

                        <div class="space-y-2.5">
                            @foreach($corridors as $corridor)
                                <div class="p-2.5 rounded-xl border border-slate-100 hover:border-slate-200 transition-colors bg-slate-50/50">
                                    <div class="flex items-center justify-between mb-0.5">
                                        <span class="text-xs font-bold text-slate-900 truncate max-w-[240px]">{{ $corridor['name'] }}</span>
                                        <span class="inline-flex items-center gap-1 px-1.5 py-0.5 rounded text-[10px] font-bold {{ $corridor['status_color'] === 'emerald' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : ($corridor['status_color'] === 'amber' ? 'bg-amber-50 text-amber-700 border border-amber-200' : 'bg-sky-50 text-sky-700 border border-sky-200') }}">
                                            <span class="w-1.5 h-1.5 rounded-full {{ $corridor['status_color'] === 'emerald' ? 'bg-emerald-500' : ($corridor['status_color'] === 'amber' ? 'bg-amber-500' : 'bg-sky-500') }}"></span>
                                            {{ $corridor['status'] }}
                                        </span>
                                    </div>
                                    <div class="text-[11px] text-slate-500 truncate">{{ $corridor['origin'] }} &rarr; {{ $corridor['destination'] }}</div>
                                    <div class="flex items-center justify-between text-[10px] text-slate-600 font-mono mt-1">
                                        <span>{{ $corridor['active_shipments'] }} {{ __('Active Shipments') }}</span>
                                        <span class="font-bold text-slate-800">Avg: {{ $corridor['avg_transit'] }}</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Card 2: Live Operations Stream -->
                <div class="bg-white p-5 rounded-2xl border border-slate-200/90 shadow-xs flex-1 flex flex-col justify-between">
                    <div>
                        <div class="flex items-center justify-between mb-1 pb-2 border-b border-slate-100">
                            <div class="flex items-center gap-2">
                                <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 animate-ping"></span>
                                <h2 class="text-sm font-bold text-slate-900">{{ __('Live Operations Stream') }}</h2>
                            </div>
                            <span class="text-[10px] text-slate-400 font-mono">{{ __('Real-Time') }}</span>
                        </div>

                        <div class="space-y-3 mt-3">
                            @forelse($recentTelemetry->take(3) as $event)
                                <div class="text-xs p-2.5 rounded-xl border border-slate-100 bg-slate-50/40">
                                    <div class="flex items-center justify-between mb-1">
                                        <a href="{{ route('shipments.show', $event->shipment_id) }}" class="font-mono font-bold text-brand-sky hover:underline">
                                            {{ $event->shipment?->tracking_number ?? 'EHG-CARGO' }}
                                        </a>
                                        <span class="text-[10px] text-slate-400 font-mono">
                                            {{ $event->event_time ? $event->event_time->diffForHumans() : __('Just now') }}
                                        </span>
                                    </div>
                                    <div class="text-[11px] font-semibold text-slate-800 truncate mb-0.5">
                                        {{ $event->location_name }}
                                    </div>
                                    <p class="text-[11px] text-slate-600 line-clamp-1">
                                        {{ $event->description }}
                                    </p>
                                </div>
                            @empty
                                <div class="text-center py-4 text-xs text-slate-400">
                                    {{ __('No recent tracking telemetry events available.') }}
                                </div>
                            @endforelse
                        </div>
                    </div>

                    <div class="pt-3 border-t border-slate-100 text-right">
                        <a href="{{ route('shipments.index') }}" class="text-[11px] font-bold text-brand-sky hover:underline">
                            {{ __('View All Telemetry') }} &rarr;
                        </a>
                    </div>
                </div>

            </div>

        </div>

        <!-- ========================================================================= -->
        <!-- 4. OPERATIONAL ANALYTICS: 2-COLUMN BALANCED FLEET & CARRIER COCKPIT       -->
        <!-- ========================================================================= -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

            <!-- Panel 1: Fleet Status & Modal Share Distribution -->
            <div class="bg-white p-6 rounded-2xl border border-slate-200/90 shadow-xs flex flex-col justify-between">
                <div>
                    <div class="flex items-center justify-between mb-1">
                        <h2 class="text-base font-bold text-slate-900">Fleet Status & Modal Share</h2>
                        <span class="text-[11px] font-bold text-brand-sky bg-sky-50 px-2.5 py-1 rounded-lg border border-sky-100">
                            {{ $stats['total_shipments'] }} Total
                        </span>
                    </div>
                    <p class="text-xs text-slate-500 mb-4">Distribution of shipments across operational stages and transit modes</p>

                    <!-- Status Quick Badges -->
                    <div class="grid grid-cols-2 gap-2 text-xs mb-4">
                        <div class="p-2.5 rounded-xl bg-blue-50/70 border border-blue-100 flex items-center justify-between">
                            <span class="text-blue-700 font-medium">In Transit</span>
                            <span class="font-mono font-bold text-blue-900">{{ $statusCounts['in_transit'] }}</span>
                        </div>
                        <div class="p-2.5 rounded-xl bg-emerald-50/70 border border-emerald-100 flex items-center justify-between">
                            <span class="text-emerald-700 font-medium">Delivered</span>
                            <span class="font-mono font-bold text-emerald-900">{{ $statusCounts['delivered'] }}</span>
                        </div>
                        <div class="p-2.5 rounded-xl bg-amber-50/70 border border-amber-100 flex items-center justify-between">
                            <span class="text-amber-700 font-medium">Delayed</span>
                            <span class="font-mono font-bold text-amber-900">{{ $statusCounts['delayed'] }}</span>
                        </div>
                        <div class="p-2.5 rounded-xl bg-red-50/70 border border-red-100 flex items-center justify-between">
                            <span class="text-red-700 font-medium">Exceptions</span>
                            <span class="font-mono font-bold text-red-900">{{ $statusCounts['open_exceptions'] }}</span>
                        </div>
                    </div>

                    <!-- Chart Container -->
                    <div class="h-44 relative">
                        <canvas id="shipmentsStatusBarChart"></canvas>
                    </div>
                </div>

                <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between text-xs text-slate-500">
                    <span class="flex items-center gap-1.5">
                        <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                        On-Time Adherence: <strong class="text-slate-800 font-mono">{{ $stats['on_time_rate'] }}%</strong>
                    </span>
                    <span class="text-slate-400 font-mono">SLA &ge; 90%</span>
                </div>
            </div>

            <!-- Panel 2: Preferred Carrier SLA & Reliability Index -->
            <div class="bg-white p-6 rounded-2xl border border-slate-200/90 shadow-xs flex flex-col justify-between">
                <div>
                    <div class="flex items-center justify-between mb-1">
                        <h2 class="text-base font-bold text-slate-900">Carrier SLA & Reliability</h2>
                        <span class="text-[11px] font-bold text-emerald-700 bg-emerald-50 px-2.5 py-1 rounded-lg border border-emerald-100">
                            Global Partners
                        </span>
                    </div>
                    <p class="text-xs text-slate-500 mb-4">On-time adherence and active freight volume across premier carriers</p>

                    <div class="space-y-3.5">
                        @foreach($carrierStats as $carrier)
                            <div class="space-y-1.5">
                                <div class="flex items-center justify-between text-xs">
                                    <div class="flex items-center gap-2">
                                        <span class="w-2.5 h-2.5 rounded-full" style="background-color: {{ $carrier['color'] }};"></span>
                                        <span class="font-semibold text-slate-800 truncate max-w-[160px]" title="{{ $carrier['name'] }}">
                                            {{ $carrier['name'] }}
                                        </span>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <span class="text-[11px] text-slate-400 font-mono">{{ $carrier['count'] }} active</span>
                                        <span class="font-mono font-bold text-slate-900">{{ $carrier['sla'] }}% SLA</span>
                                    </div>
                                </div>
                                <div class="w-full bg-slate-100 h-2 rounded-full overflow-hidden">
                                    <div class="h-full rounded-full transition-all duration-500" style="width: {{ $carrier['sla'] }}%; background-color: {{ $carrier['color'] }};"></div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between text-xs text-slate-500">
                    <span>Performance Metric: On-Time Departure & Discharge</span>
                    <span class="text-emerald-600 font-semibold flex items-center gap-1">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        Optimal Routing
                    </span>
                </div>
            </div>

        </div>

        <!-- ========================================================================= -->
        <!-- 5. MODAL DISTRIBUTION (DONUT) & RECENT SHIPMENTS ACTIVITY (2-COL GRID)    -->
        <!-- ========================================================================= -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-stretch">

            <!-- Panel Kiri (lg:col-span-5): Modern Donut Chart Distribusi Jalur (Warna Soft) -->
            <div class="lg:col-span-5 bg-white rounded-2xl border border-slate-200/90 shadow-xs p-6 flex flex-col justify-between">
                <div>
                    <div class="flex items-center justify-between mb-1">
                        <div class="flex items-center gap-2">
                            <span class="w-2.5 h-2.5 rounded-full bg-sky-400"></span>
                            <h2 class="text-base font-extrabold text-slate-900">{{ __('Modal Distribution & Freight Lanes') }}</h2>
                        </div>
                        <span class="text-[11px] font-bold text-sky-700 bg-sky-50 px-2.5 py-1 rounded-lg border border-sky-100 font-mono">
                            {{ $totalModalLegs }} {{ __('Segments') }}
                        </span>
                    </div>
                    <p class="text-xs text-slate-500 mb-4">{{ __('Breakdown of cargo movements across international transit corridors') }}</p>

                    <!-- Donut Chart Canvas with Centered Overlay -->
                    <div class="relative h-56 flex items-center justify-center my-2">
                        <canvas id="modalShareDonutChart"></canvas>
                        <div class="absolute inset-0 flex flex-col items-center justify-center pointer-events-none">
                            <span class="text-2xl font-black text-slate-800 font-mono leading-none">{{ $totalModalLegs }}</span>
                            <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 mt-1">{{ __('Total Segments') }}</span>
                        </div>
                    </div>

                    <!-- Soft-Colored Metric Cards Strip -->
                    <div class="grid grid-cols-3 gap-2.5 mt-5">
                        <!-- 1. Sea Freight (Soft Sky Blue) -->
                        <div class="p-3 rounded-xl bg-sky-50/70 border border-sky-100 flex flex-col justify-between">
                            <div class="flex items-center gap-1.5 mb-1.5">
                                <span class="w-2.5 h-2.5 rounded-full bg-[#7DD3FC]"></span>
                                <span class="text-[11px] font-bold text-slate-700 truncate">{{ __('Sea Freight') }}</span>
                            </div>
                            <div class="text-lg font-black text-sky-800 font-mono">{{ $modalDistribution['sea']['count'] }}</div>
                            <div class="text-[10px] text-slate-500 mt-0.5 font-medium flex items-center justify-between">
                                <span>{{ $modalDistribution['sea']['percentage'] }}%</span>
                                <span class="font-mono text-slate-400">{{ number_format($modalDistribution['sea']['distance_km']) }} km</span>
                            </div>
                        </div>

                        <!-- 2. Air Cargo (Soft Lavender) -->
                        <div class="p-3 rounded-xl bg-purple-50/70 border border-purple-100 flex flex-col justify-between">
                            <div class="flex items-center gap-1.5 mb-1.5">
                                <span class="w-2.5 h-2.5 rounded-full bg-[#C4B5FD]"></span>
                                <span class="text-[11px] font-bold text-slate-700 truncate">{{ __('Air Cargo') }}</span>
                            </div>
                            <div class="text-lg font-black text-purple-800 font-mono">{{ $modalDistribution['air']['count'] }}</div>
                            <div class="text-[10px] text-slate-500 mt-0.5 font-medium flex items-center justify-between">
                                <span>{{ $modalDistribution['air']['percentage'] }}%</span>
                                <span class="font-mono text-slate-400">{{ number_format($modalDistribution['air']['distance_km']) }} km</span>
                            </div>
                        </div>

                        <!-- 3. Land Transport (Soft Sage Green) -->
                        <div class="p-3 rounded-xl bg-emerald-50/70 border border-emerald-100 flex flex-col justify-between">
                            <div class="flex items-center gap-1.5 mb-1.5">
                                <span class="w-2.5 h-2.5 rounded-full bg-[#86EFAC]"></span>
                                <span class="text-[11px] font-bold text-slate-700 truncate">{{ __('Land Transport') }}</span>
                            </div>
                            <div class="text-lg font-black text-emerald-800 font-mono">{{ $modalDistribution['land']['count'] }}</div>
                            <div class="text-[10px] text-slate-500 mt-0.5 font-medium flex items-center justify-between">
                                <span>{{ $modalDistribution['land']['percentage'] }}%</span>
                                <span class="font-mono text-slate-400">{{ number_format($modalDistribution['land']['distance_km']) }} km</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between text-xs text-slate-500">
                    <span class="text-slate-400">{{ __('Multimodal corridor tracking') }}</span>
                    <a href="{{ route('sustainability.index') }}" class="font-bold text-brand-sky hover:underline flex items-center gap-1">
                        <span>{{ __('Corridor Analytics') }}</span>
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </a>
                </div>
            </div>

            <!-- Panel Kanan (lg:col-span-7): Tabel Pengiriman Minimalis & Ringkas -->
            <div class="lg:col-span-7 bg-white rounded-2xl border border-slate-200/90 shadow-xs overflow-hidden flex flex-col justify-between">
                <div>
                    <!-- Header Bar -->
                    <div class="p-5 border-b border-slate-200/80 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                        <div>
                            <div class="flex items-center gap-2">
                                <h2 class="text-base font-extrabold text-slate-900">{{ __('Recent Shipments Activity') }}</h2>
                                <span class="px-2 py-0.5 rounded-full text-xs font-mono font-bold bg-sky-50 text-brand-sky border border-sky-200">
                                    {{ __('5 Latest Records') }}
                                </span>
                            </div>
                            <p class="text-xs text-slate-500 mt-0.5">{{ __('Real-time tracking highlights across active international consignments') }}</p>
                        </div>

                        <div class="flex items-center gap-2">
                            <div class="relative w-44 sm:w-52">
                                <input type="text" id="shipmentSearchInput" placeholder="{{ __('Search consignment...') }}" 
                                       class="w-full text-xs bg-slate-50 border border-slate-200 rounded-xl pl-8 pr-2.5 py-1.5 text-slate-800 placeholder-slate-400 focus:bg-white focus:outline-none focus:ring-2 focus:ring-brand-sky/20 focus:border-brand-sky transition-all">
                                <svg class="w-3.5 h-3.5 text-slate-400 absolute left-2.5 top-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                            </div>
                        </div>
                    </div>

                    <!-- Minimal Table -->
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-xs" id="shipmentsTable">
                            <thead class="bg-slate-50 text-slate-500 font-semibold border-b border-slate-200/80">
                                <tr>
                                    <th scope="col" class="py-2.5 px-4">{{ __('Consignment & Route') }}</th>
                                    <th scope="col" class="py-2.5 px-3">{{ __('Carrier & Mode') }}</th>
                                    <th scope="col" class="py-2.5 px-3 text-center">{{ __('Live Status') }}</th>
                                    <th scope="col" class="py-2.5 px-4 text-right">{{ __('Action') }}</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 text-slate-700" id="shipmentTableBody">
                                @forelse($recentShipments as $shipment)
                                    @php
                                        $carrierName = $shipment->transportLegs->first()?->carrier_name ?? 'EcoHorizon Alliance';
                                        $mainMode = $shipment->transportLegs->first()?->transport_mode ?? 'sea';
                                        
                                        $modePill = match($mainMode) {
                                            'air' => ['icon' => '✈️', 'label' => __('Air Cargo'), 'class' => 'bg-purple-50 text-purple-700 border-purple-200'],
                                            'land' => ['icon' => '🚛', 'label' => __('Land Transport'), 'class' => 'bg-emerald-50 text-emerald-700 border-emerald-200'],
                                            default => ['icon' => '🚢', 'label' => __('Ocean Freight'), 'class' => 'bg-sky-50 text-sky-700 border-sky-200'],
                                        };
                                    @endphp
                                    <tr class="hover:bg-slate-50/70 transition-colors shipment-row"
                                        data-status="{{ $shipment->current_status }}"
                                        data-search="{{ strtolower($shipment->tracking_number . ' ' . $carrierName . ' ' . $shipment->originLocation?->city . ' ' . $shipment->destinationLocation?->city) }}">
                                        
                                        <!-- Consignment & Route -->
                                        <td class="py-3 px-4">
                                            <div class="flex items-center gap-2">
                                                <a href="{{ route('shipments.show', $shipment) }}" class="font-mono font-bold text-brand-sky hover:underline text-xs">
                                                    {{ $shipment->tracking_number }}
                                                </a>
                                                <span class="text-[10px] text-slate-400 font-sans">
                                                    &bull; {{ __($shipment->serviceLevel?->name ?? 'Standard Freight') }}
                                                </span>
                                            </div>
                                            <div class="flex items-center gap-1.5 text-[11px] text-slate-600 mt-0.5">
                                                <span class="font-semibold text-slate-800">{{ $shipment->originLocation?->city }}</span>
                                                <span class="text-slate-400 font-mono">({{ $shipment->originLocation?->country_code }})</span>
                                                <span class="text-brand-sky font-bold">&rarr;</span>
                                                <span class="font-semibold text-slate-800">{{ $shipment->destinationLocation?->city }}</span>
                                                <span class="text-slate-400 font-mono">({{ $shipment->destinationLocation?->country_code }})</span>
                                            </div>
                                        </td>

                                        <!-- Carrier & Mode -->
                                        <td class="py-3 px-3">
                                            <div class="font-medium text-slate-800 text-xs truncate max-w-[140px]" title="{{ $carrierName }}">
                                                {{ $carrierName }}
                                            </div>
                                            <span class="inline-flex items-center gap-1 text-[10px] px-1.5 py-0.5 rounded border mt-0.5 {{ $modePill['class'] }}">
                                                <span>{{ $modePill['icon'] }}</span>
                                                <span>{{ $modePill['label'] }}</span>
                                            </span>
                                        </td>

                                        <!-- Status Badge -->
                                        <td class="py-3 px-3 text-center">
                                            <x-status-badge :status="$shipment->current_status" />
                                        </td>

                                        <!-- Actions -->
                                        <td class="py-3 px-4 text-right">
                                            <div class="flex items-center justify-end gap-1.5">
                                                <button type="button" onclick="focusShipmentOnDashboardMap({{ $shipment->id }})" title="{{ __('Locate corridor on map') }}" 
                                                        class="p-1.5 rounded-lg text-slate-500 hover:text-brand-sky hover:bg-sky-50 transition-colors border border-slate-200 bg-white shadow-2xs">
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                                </button>
                                                <a href="{{ route('shipments.show', $shipment) }}" 
                                                   class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-xs font-bold text-slate-700 bg-slate-100 hover:bg-brand-sky hover:text-white transition-all shadow-2xs">
                                                    <span>{{ __('Inspect') }}</span>
                                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="py-8 text-center text-slate-400 text-xs">
                                            {{ __('No active consignments found.') }}
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Footer Bar with View All Link -->
                <div class="p-4 bg-slate-50/80 border-t border-slate-200/80 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2 text-xs">
                    <span class="text-slate-500">
                        {{ __('Displaying') }} <strong>{{ count($recentShipments) }}</strong> {{ __('most recent records of') }} <strong>{{ $stats['total_shipments'] }}</strong> {{ __('shipments') }}
                    </span>
                    <a href="{{ route('shipments.index') }}" class="font-bold text-brand-sky hover:text-sky-700 flex items-center gap-1 transition-colors">
                        <span>{{ __('View All Shipments Registry') }} ({{ $stats['total_shipments'] }})</span>
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                    </a>
                </div>
            </div>

        </div>

    </div>

    <!-- Map Styling: LogiSphere Realistic Satellite Earth & Glowing Corridors -->
    <style>
        #dashboardMap, #dashboardMap .leaflet-container, #dashboardMap .leaflet-tile-pane {
            background-color: #08162b !important;
        }
        /* Glowing Radar Beacon Nodes (Reference Photo 2 Style) */
        .logi-radar-hub-node {
            position: relative;
            width: 30px;
            height: 30px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transform: translate(-50%, -50%);
            pointer-events: auto;
        }
        .logi-radar-pulse {
            position: absolute;
            inset: -3px;
            border-radius: 9999px;
            border: 2px solid currentColor;
            opacity: 0.85;
            animation: logiRadarPulse 2.4s cubic-bezier(0.1, 0.2, 0.7, 1) infinite;
            pointer-events: none;
        }
        .logi-radar-core {
            position: relative;
            width: 13px;
            height: 13px;
            border-radius: 9999px;
            border: 2px solid #ffffff;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: transform 0.2s cubic-bezier(0.34, 1.56, 0.64, 1);
            pointer-events: none;
        }
        .logi-radar-dot {
            width: 4px;
            height: 4px;
            border-radius: 9999px;
            background-color: #ffffff;
        }
        .logi-radar-hub-node:hover .logi-radar-core {
            transform: scale(1.45);
        }
        @keyframes logiRadarPulse {
            0% { transform: scale(0.5); opacity: 0.95; }
            70% { transform: scale(2.2); opacity: 0.1; }
            100% { transform: scale(2.4); opacity: 0; }
        }
        /* Luminous Glowing Route Halo */
        .logi-glowing-route {
            stroke-linecap: round;
            stroke-linejoin: round;
            filter: drop-shadow(0 0 5px rgba(56, 189, 248, 0.85));
        }
        /* Modern Leaflet popup card */
        #dashboardMap .leaflet-popup-content-wrapper {
            border-radius: 14px !important;
            padding: 0 !important;
            overflow: hidden;
            box-shadow: 0 14px 28px -4px rgba(0, 0, 0, 0.35), 0 8px 12px -6px rgba(0, 0, 0, 0.2) !important;
            border: 1px solid rgba(226, 232, 240, 0.95);
        }
        #dashboardMap .leaflet-popup-content {
            margin: 12px 14px !important;
            line-height: 1.4;
        }
        #dashboardMap .leaflet-popup-tip-container {
            margin-top: -1px;
        }
    </style>

    <!-- ========================================================================= -->
    <!-- CHART.JS INITIALIZATION & CLIENT-SIDE FILTERING JAVASCRIPT               -->
    <!-- ========================================================================= -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            
            // ---------------------------------------------------------------------
            // 1. Chart.js: Bar Chart (Shipments by Status & Modal Share)
            // ---------------------------------------------------------------------
            // ---------------------------------------------------------------------
            // 2. Chart.js: Donut Chart (Modal Share & Freight Lanes - Soft Colors)
            // ---------------------------------------------------------------------
            const ctxModal = document.getElementById('modalShareDonutChart');
            if (ctxModal && typeof Chart !== 'undefined') {
                new Chart(ctxModal.getContext('2d'), {
                    type: 'doughnut',
                    data: {
                        labels: [
                            '{{ __('Sea Freight') }}',
                            '{{ __('Air Cargo') }}',
                            '{{ __('Land Transport') }}'
                        ],
                        datasets: [{
                            data: [
                                {{ $modalDistribution['sea']['count'] }},
                                {{ $modalDistribution['air']['count'] }},
                                {{ $modalDistribution['land']['count'] }}
                            ],
                            backgroundColor: [
                                '#7DD3FC', // Soft Sky Blue
                                '#C4B5FD', // Soft Lavender
                                '#86EFAC'  // Soft Sage Green
                            ],
                            hoverBackgroundColor: [
                                '#38BDF8',
                                '#A78BFA',
                                '#4ADE80'
                            ],
                            borderWidth: 3,
                            borderColor: '#ffffff',
                            hoverOffset: 6
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        cutout: '72%',
                        plugins: {
                            legend: {
                                display: false
                            },
                            tooltip: {
                                padding: 12,
                                cornerRadius: 10,
                                callbacks: {
                                    label: function(context) {
                                        const total = {{ $totalModalLegs }};
                                        const count = context.raw;
                                        const pct = total > 0 ? ((count / total) * 100).toFixed(1) : 0;
                                        return ' ' + context.label + ': ' + count + ' {{ __('segments') }} (' + pct + '%)';
                                    }
                                }
                            }
                        }
                    }
                });
            }

            const ctxStatus = document.getElementById('shipmentsStatusBarChart');
            if (ctxStatus && typeof Chart !== 'undefined') {
                new Chart(ctxStatus.getContext('2d'), {
                    type: 'bar',
                    data: {
                        labels: ['In Transit', 'Delivered', 'Delayed', 'Exceptions'],
                        datasets: [{
                            data: [
                                {{ $statusCounts['in_transit'] }}, 
                                {{ $statusCounts['delivered'] }}, 
                                {{ $statusCounts['delayed'] }}, 
                                {{ $statusCounts['open_exceptions'] }}
                            ],
                            backgroundColor: [
                                '#2563EB', // In Transit (Electric Royal Blue)
                                '#10B981', // Delivered (Emerald Green)
                                '#F59E0B', // Delayed (Deep Amber)
                                '#EF4444'  // Exceptions (Crimson Red)
                            ],
                            borderRadius: 8,
                            barThickness: 32,
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: { display: false },
                            tooltip: {
                                padding: 10,
                                cornerRadius: 8,
                                callbacks: {
                                    label: function(context) {
                                        return context.raw + ' Shipments Active';
                                    }
                                }
                            }
                        },
                        scales: {
                            x: {
                                grid: { display: false },
                                ticks: { font: { size: 11, weight: 'bold' }, color: '#64748B' }
                            },
                            y: {
                                beginAtZero: true,
                                ticks: { stepSize: 5, font: { size: 10 }, color: '#94A3B8' },
                                grid: { color: '#F1F5F9' }
                            }
                        }
                    }
                });
            }

            // ---------------------------------------------------------------------
            // 2. Client-Side Instant Search Filter
            // ---------------------------------------------------------------------
            const searchInput = document.getElementById('shipmentSearchInput');
            if (searchInput) {
                searchInput.addEventListener('input', function () {
                    const query = this.value.toLowerCase().trim();
                    const rows = document.querySelectorAll('.shipment-row');
                    
                    rows.forEach(function (row) {
                        const searchContent = row.getAttribute('data-search') || '';
                        if (query === '' || searchContent.includes(query)) {
                            row.style.display = '';
                        } else {
                            row.style.display = 'none';
                        }
                    });
                });
            }

            // ---------------------------------------------------------------------
            // 3. Leaflet.js: Interactive Worldwide Corridor & Shipment Radar (LogiSphere Satellite Edition)
            // ---------------------------------------------------------------------
            let dashboardMap = null;
            let shipmentsLayerGroup = null;
            let currentTileLayer = null;
            let currentReferenceLayer = null;
            const shipmentLayerEntries = {};
            const mapShipments = @json($mapShipments ?? []);

            // Safe immediate window stubs
            window.filterMapByStatus = window.filterMapByStatus || function () {};
            window.resetDashboardMapWorld = window.resetDashboardMapWorld || function () {};
            window.focusShipmentOnDashboardMap = window.focusShipmentOnDashboardMap || function () {};
            window.switchMapStyle = window.switchMapStyle || function () {};

            // -----------------------------------------------------------------
            // Geodesic & Catmull-Rom Spline Curve Interpolation Helpers
            // -----------------------------------------------------------------
            function catmullRom(p0, p1, p2, p3, t) {
                const t2 = t * t;
                const t3 = t2 * t;
                const lat = 0.5 * ((2 * p1[0]) + (-p0[0] + p2[0]) * t + (2 * p0[0] - 5 * p1[0] + 4 * p2[0] - p3[0]) * t2 + (-p0[0] + 3 * p1[0] - 3 * p2[0] + p3[0]) * t3);
                const lon = 0.5 * ((2 * p1[1]) + (-p0[1] + p2[1]) * t + (2 * p0[1] - 5 * p1[1] + 4 * p2[1] - p3[1]) * t2 + (-p0[1] + 3 * p1[1] - 3 * p2[1] + p3[1]) * t3);
                return [lat, lon];
            }

            function smoothPath(waypoints, samples = 4) {
                if (!waypoints || waypoints.length < 2) return waypoints || [];
                const result = [];
                for (let i = 0; i < waypoints.length - 1; i++) {
                    const p0 = i > 0 ? waypoints[i - 1] : waypoints[i];
                    const p1 = waypoints[i];
                    const p2 = waypoints[i + 1];
                    const p3 = i + 2 < waypoints.length ? waypoints[i + 2] : p2;
                    for (let s = 0; s < samples; s++) {
                        result.push(catmullRom(p0, p1, p2, p3, s / samples));
                    }
                }
                result.push(waypoints[waypoints.length - 1]);
                return result;
            }

            function generateGreatCirclePoints(start, end, numPoints = 12) {
                const lat1 = start[0] * Math.PI / 180;
                const lon1 = start[1] * Math.PI / 180;
                const lat2 = end[0] * Math.PI / 180;
                const lon2 = end[1] * Math.PI / 180;

                const d = 2 * Math.asin(Math.sqrt(
                    Math.pow(Math.sin((lat1 - lat2) / 2), 2) +
                    Math.cos(lat1) * Math.cos(lat2) * Math.pow(Math.sin((lon1 - lon2) / 2), 2)
                ));

                if (d < 0.001 || isNaN(d)) return [start, end];

                const points = [];
                for (let i = 0; i <= numPoints; i++) {
                    const f = i / numPoints;
                    const A = Math.sin((1 - f) * d) / Math.sin(d);
                    const B = Math.sin(f * d) / Math.sin(d);
                    const x = A * Math.cos(lat1) * Math.cos(lon1) + B * Math.cos(lat2) * Math.cos(lon2);
                    const y = A * Math.cos(lat1) * Math.sin(lon1) + B * Math.cos(lat2) * Math.sin(lon2);
                    const z = A * Math.sin(lat1) + B * Math.sin(lat2);
                    const lat = Math.atan2(z, Math.sqrt(x * x + y * y)) * 180 / Math.PI;
                    const lon = Math.atan2(y, x) * 180 / Math.PI;
                    points.push([lat, lon]);
                }
                return points;
            }

            // Realistic corridor routing with realistic chokepoints and highway curves
            function routeCorridorSegment(start, end, mode) {
                const lat1 = Number(start[0]);
                const lon1 = Number(start[1]);
                const lat2 = Number(end[0]);
                const lon2 = Number(end[1]);

                // 1. Trans-Java Overland Corridor (Surabaya <-> Cikarang / Bekasi / Jakarta / Tangerang)
                if (mode === 'land' && lat1 >= -8.5 && lat1 <= -5.5 && lat2 >= -8.5 && lat2 <= -5.5 && lon1 >= 106 && lon1 <= 114 && lon2 >= 106 && lon2 <= 114) {
                    const javaWaypoints = [
                        [-7.329, 112.756], // Surabaya
                        [-7.464, 112.433], // Mojokerto
                        [-7.556, 110.828], // Solo
                        [-7.005, 110.428], // Semarang
                        [-6.889, 109.675], // Pekalongan
                        [-6.732, 108.552], // Cirebon
                        [-6.402, 107.452], // Cikampek
                        [-6.294, 107.087], // Cikarang
                        [-6.238, 106.975], // Bekasi
                        [-6.102, 106.883], // Tanjung Priok
                        [-6.175, 106.827], // Jakarta
                        [-6.128, 106.654]  // Tangerang
                    ];
                    if (lon1 > lon2) {
                        const intermediate = javaWaypoints.filter(p => p[1] < lon1 - 0.15 && p[1] > lon2 + 0.15);
                        intermediate.sort((a, b) => b[1] - a[1]);
                        return smoothPath([start, ...intermediate, end], 4);
                    } else {
                        const intermediate = javaWaypoints.filter(p => p[1] > lon1 + 0.15 && p[1] < lon2 - 0.15);
                        intermediate.sort((a, b) => a[1] - b[1]);
                        return smoothPath([start, ...intermediate, end], 4);
                    }
                }

                // 2. US Interstate Corridor (Chicago to West Coast)
                if (mode === 'land' && lon1 < -80 && lon1 > -95 && lon2 < -115 && lat1 > 35 && lat2 > 35) {
                    const i80 = [
                        [41.97, -87.90],  // Chicago
                        [41.60, -93.60],  // Des Moines
                        [41.25, -95.93],  // Omaha
                        [41.14, -104.82], // Cheyenne
                        [40.76, -111.89], // Salt Lake City
                        [39.53, -119.81], // Reno
                        [38.58, -121.49], // Sacramento
                        [37.38, -122.08]  // San Jose
                    ];
                    return smoothPath([start, ...i80.slice(1, -1), end], 4);
                }

                // 3. Northern Europe overland corridor (Hamburg <-> Rotterdam)
                if (mode === 'land' && lat1 > 51 && lat2 > 51 && lon1 > 3 && lon1 < 12 && lon2 > 3 && lon2 < 12) {
                    return smoothPath([start, [53.07, 8.80], [52.28, 8.05], [52.09, 5.12], end], 4);
                }

                // 4. Germany South corridor (Frankfurt <-> Munich)
                if (mode === 'land' && lat1 > 47 && lat2 > 47 && lat1 < 51 && lat2 < 51 && lon1 > 7 && lon2 > 7) {
                    return smoothPath([start, [49.79, 9.95], [49.45, 11.08], end], 4);
                }

                // 5. UK Gateway corridor (Felixstowe <-> London)
                if (mode === 'land' && lon1 > -1 && lon2 > -1 && lon1 < 2 && lon2 < 2 && lat1 > 51 && lat2 > 51) {
                    return smoothPath([start, [52.05, 1.15], [51.73, 0.47], end], 4);
                }

                // 6. Brazil overland corridor (Sao Paulo <-> Santos)
                if (mode === 'land' && lat1 < -23 && lat2 < -23 && lon1 < -46 && lon2 < -46) {
                    return smoothPath([start, [-23.82, -46.46], end], 4);
                }

                // 7. Sea: Asia (China, Singapore, Indonesia) <-> Europe (Rotterdam, Hamburg, Felixstowe, Antwerp)
                const isAsiaOrigin = (lon1 > 95 && lat1 > -10 && lat1 < 40);
                const isEuropeDest = (lon2 < 15 && lon2 > -10 && lat2 > 45 && lat2 < 60);
                if (mode === 'sea' && isAsiaOrigin && isEuropeDest) {
                    const maritimeAsiaToEurope = [
                        start,
                        ...(lat1 > 15 ? [[24.0, 119.5], [14.0, 113.0]] : []),
                        ...(lat1 < 0 ? [[-2.5, 105.5]] : []),
                        [1.26, 103.82],  // Singapore Strait
                        [2.50, 101.50],  // Malacca Strait
                        [5.50, 95.50],   // Banda Aceh
                        [5.80, 80.50],   // Sri Lanka south
                        [11.50, 53.00],  // Arabian Sea south
                        [12.50, 44.50],  // Gulf of Aden
                        [12.80, 43.30],  // Bab-el-Mandeb
                        [22.00, 38.00],  // Red Sea
                        [29.97, 32.55],  // Suez Canal
                        [34.50, 24.50],  // South of Crete
                        [37.00, 12.00],  // Strait of Sicily
                        [35.95, -5.60],  // Strait of Gibraltar
                        [38.75, -9.60],  // Portugal coast
                        [44.50, -8.50],  // Bay of Biscay
                        [50.00, -1.50],  // English Channel
                        end
                    ];
                    return smoothPath(maritimeAsiaToEurope, 4);
                }

                // 8. Sea: South America (Santos) <-> Europe (Hamburg / Rotterdam)
                if (mode === 'sea' && lat1 < -15 && lon1 < -40 && lat2 > 45) {
                    const maritimeBrazilToEurope = [
                        start,
                        [-13.50, -37.50], // Salvador offing
                        [-5.00, -34.00],  // Cape San Roque
                        [5.00, -28.00],   // Equatorial Atlantic
                        [28.50, -18.00],  // Canary Islands
                        [38.00, -11.00],  // Lisbon offing
                        [44.50, -8.50],   // Bay of Biscay
                        [50.00, -1.50],   // English Channel
                        end
                    ];
                    return smoothPath(maritimeBrazilToEurope, 4);
                }

                // 9. Sea: North Atlantic (Newark / NY <-> Northern Europe)
                if (mode === 'sea' && lon1 < -70 && lat1 > 38 && lon2 > -10 && lat2 > 48) {
                    const transAtlantic = [
                        start,
                        [42.50, -60.00], // Nova Scotia south
                        [48.00, -30.00], // Mid Atlantic
                        [49.50, -11.00], // Celtic Sea
                        [50.20, -2.00],  // English Channel
                        end
                    ];
                    return smoothPath(transAtlantic, 4);
                }

                // 10. Sea: Trans-Pacific (Asia to US West Coast)
                if (mode === 'sea' && lon1 > 115 && lon2 < -115 && lat1 > 25 && lat2 > 25) {
                    return smoothPath([
                        start,
                        [35.0, 145.0],
                        [40.0, 175.0],
                        [39.0, -150.0],
                        [35.5, -130.0],
                        end
                    ], 4);
                }

                // 11. Sea: South China Sea Channel (Shanghai <-> Singapore)
                if (mode === 'sea' && lat1 > 28 && lon1 > 120 && lat2 < 3 && lon2 < 105) {
                    return smoothPath([
                        start,
                        [25.0, 120.0],
                        [16.0, 114.0],
                        [6.0, 108.0],
                        end
                    ], 4);
                }

                // 12. Air or generic smooth curved geodesic arc
                return generateGreatCirclePoints(start, end, 14);
            }

            function buildShipmentCorridorPath(shipment) {
                const originCoord = [Number(shipment.origin.lat), Number(shipment.origin.lon)];
                const destCoord = [Number(shipment.destination.lat), Number(shipment.destination.lon)];

                if (Array.isArray(shipment.legs) && shipment.legs.length > 0) {
                    const validLegs = shipment.legs.filter(l => l.origin && l.destination && l.origin.lat !== null && l.destination.lat !== null);
                    if (validLegs.length > 0) {
                        let combinedPath = [];
                        validLegs.forEach((leg, idx) => {
                            const legStart = [Number(leg.origin.lat), Number(leg.origin.lon)];
                            const legEnd = [Number(leg.destination.lat), Number(leg.destination.lon)];
                            const legPath = routeCorridorSegment(legStart, legEnd, leg.mode || shipment.mode || 'sea');
                            if (idx > 0) {
                                combinedPath = combinedPath.concat(legPath.slice(1));
                            } else {
                                combinedPath = combinedPath.concat(legPath);
                            }
                        });
                        return combinedPath;
                    }
                }

                return routeCorridorSegment(originCoord, destCoord, shipment.mode || 'sea');
            }

            function initDashboardMap() {
                const mapContainer = document.getElementById('dashboardMap');
                if (!mapContainer) return;

                if (typeof L === 'undefined') {
                    setTimeout(initDashboardMap, 100);
                    return;
                }

                if (dashboardMap) return;

                dashboardMap = L.map('dashboardMap', {
                    scrollWheelZoom: false,
                    zoomControl: false,
                    minZoom: 2,
                    maxZoom: 18,
                    maxBounds: [[-85.06, -180], [85.06, 180]],
                    maxBoundsViscosity: 1.0,
                    worldCopyJump: false,
                }).setView([20.0, 15.0], 2);

                L.control.zoom({ position: 'bottomright' }).addTo(dashboardMap);

                // Available Tile Layer Providers
                const tileProviders = {
                    satellite: {
                        base: L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
                            maxZoom: 18,
                            noWrap: true,
                            bounds: [[-85.06, -180], [85.06, 180]],
                            attribution: '&copy; Esri &mdash; Earthstar Geographics'
                        }),
                        ref: L.tileLayer('https://services.arcgisonline.com/ArcGIS/rest/services/Reference/World_Boundaries_and_Places/MapServer/tile/{z}/{y}/{x}', {
                            maxZoom: 18,
                            noWrap: true,
                            bounds: [[-85.06, -180], [85.06, 180]],
                            opacity: 0.65
                        }),
                        bgColor: '#08162b'
                    },
                    vector: {
                        base: L.tileLayer('https://{s}.basemaps.cartocdn.com/rastertiles/voyager/{z}/{x}/{y}{r}.png', {
                            maxZoom: 18,
                            noWrap: true,
                            bounds: [[-85.06, -180], [85.06, 180]],
                            attribution: '&copy; <a href="https://carto.com/">CARTO</a> &copy; OpenStreetMap'
                        }),
                        ref: null,
                        bgColor: '#e2edf3'
                    }
                };

                let activeStyle = 'satellite';
                currentTileLayer = tileProviders.satellite.base.addTo(dashboardMap);
                currentReferenceLayer = tileProviders.satellite.ref.addTo(dashboardMap);

                window.switchMapStyle = function (style) {
                    if (!tileProviders[style] || style === activeStyle) return;
                    activeStyle = style;

                    // Remove existing layers
                    if (currentTileLayer) dashboardMap.removeLayer(currentTileLayer);
                    if (currentReferenceLayer) dashboardMap.removeLayer(currentReferenceLayer);

                    // Add new layers
                    currentTileLayer = tileProviders[style].base.addTo(dashboardMap);
                    if (tileProviders[style].ref) {
                        currentReferenceLayer = tileProviders[style].ref.addTo(dashboardMap);
                    } else {
                        currentReferenceLayer = null;
                    }

                    // Update background color
                    mapContainer.style.backgroundColor = tileProviders[style].bgColor;

                    // Update button active states
                    const btnSatellite = document.getElementById('btn-map-satellite');
                    const btnVector = document.getElementById('btn-map-vector');
                    if (btnSatellite && btnVector) {
                        if (style === 'satellite') {
                            btnSatellite.classList.add('bg-white', 'text-brand-sky', 'border', 'border-slate-200', 'font-bold', 'shadow-2xs');
                            btnSatellite.classList.remove('text-slate-600', 'font-medium');
                            btnVector.classList.remove('bg-white', 'text-brand-sky', 'border', 'border-slate-200', 'font-bold', 'shadow-2xs');
                            btnVector.classList.add('text-slate-600', 'font-medium');
                        } else {
                            btnVector.classList.add('bg-white', 'text-brand-sky', 'border', 'border-slate-200', 'font-bold', 'shadow-2xs');
                            btnVector.classList.remove('text-slate-600', 'font-medium');
                            btnSatellite.classList.remove('bg-white', 'text-brand-sky', 'border', 'border-slate-200', 'font-bold', 'shadow-2xs');
                            btnSatellite.classList.add('text-slate-600', 'font-medium');
                        }
                    }
                };

                // Ctrl+Wheel Zoom Handler
                mapContainer.addEventListener('wheel', function (e) {
                    if (e.ctrlKey || e.metaKey) {
                        e.preventDefault();
                        if (e.deltaY < 0) {
                            dashboardMap.zoomIn();
                        } else {
                            dashboardMap.zoomOut();
                        }
                    }
                }, { passive: false });

                shipmentsLayerGroup = L.layerGroup().addTo(dashboardMap);

                // Custom Glowing Concentric Circular Radar Node (LogiSphere Reference Photo 2 Style)
                function createRadarHubIcon(coreColor, glowColor) {
                    return L.divIcon({
                        className: 'custom-radar-hub-icon',
                        html: `
                            <div class="logi-radar-hub-node">
                                <div class="logi-radar-pulse" style="color: ${glowColor};"></div>
                                <div class="logi-radar-core" style="background-color: ${coreColor}; box-shadow: 0 0 10px ${glowColor};">
                                    <div class="logi-radar-dot"></div>
                                </div>
                            </div>
                        `,
                        iconSize: [30, 30],
                        iconAnchor: [15, 15],
                        popupAnchor: [0, -14]
                    });
                }

                function renderMapRoutes(filterStatus) {
                    shipmentsLayerGroup.clearLayers();
                    for (const key in shipmentLayerEntries) {
                        delete shipmentLayerEntries[key];
                    }

                    const filtered = mapShipments.filter(function (s) {
                        if (!s.origin || !s.destination || s.origin.lat === null || s.origin.lon === null || s.destination.lat === null || s.destination.lon === null) return false;
                        if (isNaN(s.origin.lat) || isNaN(s.origin.lon) || isNaN(s.destination.lat) || isNaN(s.destination.lon)) return false;

                        if (!filterStatus || filterStatus === 'ALL') {
                            return !s.is_history;
                        }
                        if (filterStatus === 'IN_TRANSIT') {
                            return !s.is_history && ['IN_TRANSIT', 'OUT_FOR_DELIVERY', 'PICKED_UP', 'CUSTOMS_CLEARANCE'].includes(s.status);
                        }
                        if (filterStatus === 'DELIVERED') {
                            return !s.is_history && ['DELIVERED', 'ARRIVED_AT_DESTINATION', 'DELIVERED_TO_WAREHOUSE'].includes(s.status);
                        }
                        if (filterStatus === 'DELAYED') {
                            return !s.is_history && (s.is_delayed || s.has_exception || ['DELAYED', 'CRITICAL_EXCEPTION', 'DELIVERY_EXCEPTION'].includes(s.status));
                        }
                        if (filterStatus === 'AIR') {
                            return !s.is_history && s.mode === 'air';
                        }
                        if (filterStatus === 'SEA') {
                            return !s.is_history && s.mode === 'sea';
                        }
                        if (filterStatus === 'LAND') {
                            return !s.is_history && s.mode === 'land';
                        }
                        if (filterStatus === 'HISTORY') {
                            return Boolean(s.is_history);
                        }
                        return true;
                    });

                    filtered.forEach(function (shipment) {
                        const originCoord = [Number(shipment.origin.lat), Number(shipment.origin.lon)];
                        const destCoord = [Number(shipment.destination.lat), Number(shipment.destination.lon)];

                        const isDelivered = ['DELIVERED', 'ARRIVED_AT_DESTINATION', 'DELIVERED_TO_WAREHOUSE'].includes(shipment.status);
                        const isDelayed = shipment.is_delayed || shipment.has_exception || ['DELAYED', 'CRITICAL_EXCEPTION', 'DELIVERY_EXCEPTION'].includes(shipment.status);
                        const isHistory = Boolean(shipment.is_history);

                        let routeColor = '#06B6D4'; // Luminous Cyan (Sea default)
                        let glowColor = '#38BDF8';
                        let dashArray = '8, 8';

                        if (isHistory) {
                            routeColor = '#64748B'; // Muted Slate for Archived
                            glowColor = '#94A3B8';
                            dashArray = '4, 6';
                        } else if (isDelayed) {
                            routeColor = '#EF4444'; // Vivid Red/Coral for Alert
                            glowColor = '#F87171';
                            dashArray = null; // Solid alert line
                        } else if (isDelivered) {
                            routeColor = '#10B981'; // Luminous Emerald for Completed
                            glowColor = '#34D399';
                            dashArray = null; // Solid completed line
                        } else if (shipment.mode === 'air') {
                            routeColor = '#EC4899'; // Luminous Pink for Air Cargo
                            glowColor = '#F472B6';
                            dashArray = '8, 8';
                        } else if (shipment.mode === 'land') {
                            routeColor = '#F59E0B'; // Warm Amber/Gold for Overland
                            glowColor = '#FBBF24';
                            dashArray = '8, 8';
                        } else {
                            routeColor = '#06B6D4'; // Vibrant Cyan for Ocean
                            glowColor = '#38BDF8';
                            dashArray = '8, 8';
                        }

                        // Generate realistic winding corridor path coordinates
                        const corridorCoords = buildShipmentCorridorPath(shipment);

                        // Origin Hub Marker (Glowing Concentric Radar Beacon)
                        const originMarker = L.marker(originCoord, {
                            icon: createRadarHubIcon('#FFFFFF', glowColor)
                        });

                        // Destination Hub Marker (Glowing Concentric Radar Beacon)
                        const destMarker = L.marker(destCoord, {
                            icon: createRadarHubIcon(routeColor, glowColor)
                        });

                        // Double-Layered Polyline:
                        // 1. Glowing outer halo (thick & translucent)
                        const glowPolyline = L.polyline(corridorCoords, {
                            color: glowColor,
                            weight: 6,
                            opacity: 0.38,
                            className: 'logi-glowing-route'
                        });

                        // 2. Crisp luminous inner route
                        const corePolyline = L.polyline(corridorCoords, {
                            color: routeColor,
                            weight: 2.8,
                            opacity: 0.95,
                            dashArray: dashArray
                        });

                        const statusBadgeClass = isHistory ? 'bg-slate-100 text-slate-700 border border-slate-300'
                            : (isDelayed ? 'bg-red-50 text-red-700 border border-red-200' 
                            : (isDelivered ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' 
                            : (shipment.mode === 'air' ? 'bg-pink-50 text-pink-700 border border-pink-200' 
                            : (shipment.mode === 'land' ? 'bg-amber-50 text-amber-700 border border-amber-200' : 'bg-sky-50 text-sky-700 border border-sky-200'))));

                        const modeBadge = shipment.mode === 'air' ? '✈️ Air Cargo' : (shipment.mode === 'land' ? '🚛 Overland' : '🚢 Ocean Freight');

                        const popupHtml = `
                            <div class="p-1 font-sans text-xs min-w-[230px]">
                                <div class="flex items-center justify-between pb-1.5 mb-2 border-b border-slate-200">
                                    <a href="/shipments/${shipment.id}" class="font-mono font-bold text-sky-600 hover:underline">
                                        ${shipment.tracking_number}
                                    </a>
                                    <span class="px-2 py-0.5 rounded text-[10px] font-bold ${statusBadgeClass}">
                                        ${isHistory ? 'ARCHIVED (>7D)' : shipment.status.replace(/_/g, ' ')}
                                    </span>
                                </div>
                                <div class="space-y-1 text-[11px] text-slate-700">
                                    <div><span class="text-slate-400 font-semibold">Origin:</span> <strong class="text-slate-800">${shipment.origin.city}</strong> (${shipment.origin.country || ''})</div>
                                    <div><span class="text-slate-400 font-semibold">Destination:</span> <strong class="text-slate-800">${shipment.destination.city}</strong> (${shipment.destination.country || ''})</div>
                                    <div><span class="text-slate-400 font-semibold">Carrier:</span> ${shipment.carrier} &bull; <span class="font-semibold text-slate-600">${modeBadge}</span></div>
                                    <div><span class="text-slate-400 font-semibold">ETA:</span> <span class="font-mono">${shipment.eta}</span> (${shipment.service_level})</div>
                                    ${isHistory && shipment.delivered_at ? `<div class="text-[10px] text-emerald-700 font-medium">Delivered: ${shipment.delivered_at}</div>` : ''}
                                    <div class="text-[10px] text-slate-500 font-mono pt-1 border-t border-slate-100 flex items-center justify-between">
                                        <span>Cargo: ${Number(shipment.weight || 0).toFixed(1)} t</span>
                                        <span class="text-teal-700 font-bold">${Math.round(shipment.co2e || 0)} kg CO2e</span>
                                    </div>
                                </div>
                                <div class="mt-2 pt-1.5 border-t border-slate-100 flex items-center justify-between text-[11px]">
                                    <span class="text-slate-400 text-[10px] truncate max-w-[140px]">${shipment.cargo_description || 'Freight Consignment'}</span>
                                    <a href="/shipments/${shipment.id}" class="font-bold text-sky-600 hover:underline inline-flex items-center gap-0.5">
                                        Inspect &rarr;
                                    </a>
                                </div>
                            </div>
                        `;

                        originMarker.bindPopup(popupHtml);
                        destMarker.bindPopup(popupHtml);
                        corePolyline.bindPopup(popupHtml);
                        glowPolyline.bindPopup(popupHtml);

                        glowPolyline.addTo(shipmentsLayerGroup);
                        corePolyline.addTo(shipmentsLayerGroup);
                        originMarker.addTo(shipmentsLayerGroup);
                        destMarker.addTo(shipmentsLayerGroup);

                        // If shipment has multimodal intermediate transfer hubs, render subtle intermediate radar nodes
                        if (Array.isArray(shipment.legs) && shipment.legs.length > 1) {
                            shipment.legs.slice(0, -1).forEach(leg => {
                                if (leg.destination && leg.destination.lat && leg.destination.lon) {
                                    const transitCoord = [Number(leg.destination.lat), Number(leg.destination.lon)];
                                    const transitMarker = L.marker(transitCoord, {
                                        icon: createRadarHubIcon(glowColor, glowColor)
                                    });
                                    transitMarker.bindPopup(popupHtml);
                                    transitMarker.addTo(shipmentsLayerGroup);
                                }
                            });
                        }

                        shipmentLayerEntries[shipment.id] = {
                            originMarker: originMarker,
                            destMarker: destMarker,
                            polyline: corePolyline,
                            glowPolyline: glowPolyline,
                            bounds: L.latLngBounds(corridorCoords),
                            status: shipment.status
                        };
                    });
                }

                // Initial render of all routes
                renderMapRoutes('ALL');

                // Global Filter Function for Map
                window.filterMapByStatus = function (status) {
                    const buttons = document.querySelectorAll('.map-filter-btn');
                    buttons.forEach(btn => {
                        if (btn.id === 'map-filter-' + status) {
                            btn.classList.add('bg-white', 'text-brand-sky', 'border', 'border-slate-200', 'font-bold', 'shadow-2xs');
                            btn.classList.remove('text-slate-600', 'font-medium');
                        } else {
                            btn.classList.remove('bg-white', 'text-brand-sky', 'border', 'border-slate-200', 'font-bold', 'shadow-2xs');
                            btn.classList.add('text-slate-600', 'font-medium');
                        }
                    });

                    renderMapRoutes(status);
                };

                window.resetDashboardMapWorld = function () {
                    if (dashboardMap) {
                        dashboardMap.invalidateSize();
                        dashboardMap.setView([20.0, 15.0], 2);
                    }
                };

                window.focusShipmentOnDashboardMap = function (shipmentId) {
                    const target = mapShipments.find(s => s.id === shipmentId);
                    if (!target) return;

                    window.filterMapByStatus('ALL');

                    const entry = shipmentLayerEntries[shipmentId];
                    if (entry && dashboardMap) {
                        dashboardMap.invalidateSize();
                        dashboardMap.fitBounds(entry.bounds, { padding: [60, 60], maxZoom: 6 });
                        entry.polyline.openPopup();

                        const mapCard = document.getElementById('dashboardMapCard');
                        if (mapCard) {
                            mapCard.scrollIntoView({ behavior: 'smooth', block: 'center' });
                        }
                    }
                };

                setTimeout(function () {
                    if (dashboardMap) {
                        dashboardMap.invalidateSize();
                        dashboardMap.setView([20.0, 15.0], 2);
                    }
                }, 100);

                setTimeout(function () {
                    if (dashboardMap) {
                        dashboardMap.invalidateSize();
                    }
                }, 400);

                window.addEventListener('resize', function () {
                    if (dashboardMap) dashboardMap.invalidateSize();
                });
            }

            initDashboardMap();

        });

        // -------------------------------------------------------------------------
        // 3. Tab Filter Function
        // -------------------------------------------------------------------------
        function filterShipmentsByStatus(status) {
            const rows = document.querySelectorAll('.shipment-row');
            const tabs = document.querySelectorAll('.status-tab-btn');

            tabs.forEach(tab => {
                if (tab.getAttribute('data-filter') === status) {
                    tab.classList.add('bg-white', 'text-brand-sky', 'border', 'border-slate-200', 'font-bold', 'shadow-2xs');
                    tab.classList.remove('text-slate-600', 'font-medium');
                } else {
                    tab.classList.remove('bg-white', 'text-brand-sky', 'border', 'border-slate-200', 'font-bold', 'shadow-2xs');
                    tab.classList.add('text-slate-600', 'font-medium');
                }
            });

            rows.forEach(row => {
                const rowStatus = row.getAttribute('data-status');
                if (status === 'ALL') {
                    row.style.display = '';
                } else if (status === 'IN_TRANSIT') {
                    if (['IN_TRANSIT', 'OUT_FOR_DELIVERY', 'PICKED_UP', 'CUSTOMS_CLEARANCE'].includes(rowStatus)) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                } else if (status === 'DELIVERED') {
                    if (['DELIVERED', 'ARRIVED_AT_DESTINATION', 'DELIVERED_TO_WAREHOUSE'].includes(rowStatus)) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                } else if (status === 'DELAYED') {
                    if (['DELAYED', 'CRITICAL_EXCEPTION', 'DELIVERY_EXCEPTION'].includes(rowStatus)) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                }
            });
        }
    </script>
</x-app-layout>
