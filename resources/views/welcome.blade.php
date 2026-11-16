<x-guest-layout :title="__('EcoHorizon Global — Global Multimodal Freight Visibility Platform')">

    <!-- HERO SECTION (Blue-Green Gradient) -->
    <section id="track" class="relative overflow-hidden bg-gradient-to-br from-brand-sky via-brand-teal to-brand-emerald text-white py-16 sm:py-24 lg:py-28 scroll-mt-16">
        <!-- Background subtle decorative network lines -->
        <div class="absolute inset-0 opacity-10 bg-[radial-gradient(#ffffff_1px,transparent_1px)] [background-size:24px_24px] pointer-events-none"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="text-center max-w-3xl mx-auto">
                <!-- Tagline Badge -->
                <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-white/15 backdrop-blur-md border border-white/20 text-xs font-semibold uppercase tracking-wider text-white mb-6">
                    <span class="w-2 h-2 rounded-full bg-emerald-300 animate-pulse"></span>
                    {{ __('Track Every Journey. Move Toward a Greener Future.') }}
                </div>

                <!-- Main Headline -->
                <h1 class="text-4xl sm:text-5xl lg:text-6xl font-extrabold tracking-tight leading-[1.15] text-white">
                    {{ __('Your Cargo Journey,') }} <br class="hidden sm:inline">{{ __('Clearly Connected.') }}
                </h1>

                <!-- Supporting Paragraph -->
                <p class="mt-6 text-base sm:text-lg text-sky-50 leading-relaxed max-w-2xl mx-auto">
                    {{ __('Track sea, land, and air shipments in one unified platform. Monitor delivery milestones, manage logistics exceptions, and understand estimated transport carbon emissions with transparency.') }}
                </p>

                <!-- Prominent Tracking Search Bar -->
                <div class="mt-10 max-w-xl mx-auto">
                    <form action="{{ route('track.search') }}" method="GET" class="relative flex items-center">
                        <div class="relative w-full shadow-2xl rounded-2xl">
                            <input id="tracking-search-input" type="text" name="tracking_number" required
                                   placeholder="{{ __('Enter shipment tracking number (e.g. EHG-20261029-JKTMUC)') }}" 
                                   class="w-full pl-5 pr-36 py-4 rounded-2xl bg-white text-slate-900 placeholder-slate-400 text-sm sm:text-base font-mono focus:outline-none focus:ring-4 focus:ring-white/40 shadow-inner">
                            <button type="submit" 
                                    class="absolute right-2 top-2 bottom-2 px-6 rounded-xl font-semibold text-sm bg-gradient-to-r from-brand-sky to-brand-teal text-white hover:opacity-95 shadow-md transition-all flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                                <span>{{ __('Track') }}</span>
                            </button>
                        </div>
                    </form>
                </div>

                <!-- 1-Click Flagship Demo Scenario Buttons -->
                <div class="mt-6 flex flex-wrap items-center justify-center gap-2 text-xs">
                    <span class="text-sky-100 font-semibold uppercase tracking-wider mr-1">{{ __('Evaluation Flagship Journeys:') }}</span>
                    <a href="{{ route('track.show', 'EHG-20261029-JKTMUC') }}" class="px-3 py-1.5 rounded-lg bg-white/20 hover:bg-white text-white hover:text-slate-900 backdrop-blur-md transition-all font-mono font-medium border border-white/20">
                        &bull; {{ __('Multimodal Electronics (Sea-Air-Road)') }}
                    </a>
                    <a href="{{ route('track.show', 'EHG-20261029-SHARTM') }}" class="px-3 py-1.5 rounded-lg bg-white/20 hover:bg-white text-white hover:text-slate-900 backdrop-blur-md transition-all font-mono font-medium border border-white/20">
                        &bull; {{ __('Ocean Delay (Shanghai → Rotterdam)') }}
                    </a>
                    <a href="{{ route('track.show', 'EHG-20261029-SUBB2B') }}" class="px-3 py-1.5 rounded-lg bg-white/20 hover:bg-white text-white hover:text-slate-900 backdrop-blur-md transition-all font-mono font-medium border border-white/20">
                        &bull; {{ __('B2B Warehouse Delivered (Surabaya)') }}
                    </a>
                    <a href="{{ route('track.show', 'EHG-20261029-HNDLAX') }}" class="px-3 py-1.5 rounded-lg bg-white/20 hover:bg-white text-white hover:text-slate-900 backdrop-blur-md transition-all font-mono font-medium border border-white/20">
                        &bull; {{ __('Air Cargo Customs Exception') }}
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- LIVE METRICS COUNTER BAR -->
    <div class="bg-white border-b border-slate-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-6 text-center">
                <div>
                    <div class="text-2xl sm:text-3xl font-extrabold text-slate-900">{{ $stats['total_shipments'] }}</div>
                    <div class="text-xs font-semibold uppercase tracking-wider text-slate-500 mt-1">{{ __('Monitored Shipments') }}</div>
                </div>
                <div>
                    <div class="text-2xl sm:text-3xl font-extrabold text-brand-sky">{{ number_format($stats['total_distance_km']) }} km</div>
                    <div class="text-xs font-semibold uppercase tracking-wider text-slate-500 mt-1">{{ __('Verified Corridor Distance') }}</div>
                </div>
                <div>
                    <div class="text-2xl sm:text-3xl font-extrabold text-brand-emerald">{{ number_format($stats['total_co2e_kg']) }} kg</div>
                    <div class="text-xs font-semibold uppercase tracking-wider text-slate-500 mt-1">{{ __('Estimated Transport CO₂e') }}</div>
                </div>
                <div>
                    <div class="text-2xl sm:text-3xl font-extrabold text-brand-teal">{{ $stats['active_legs'] }} {{ __('Active') }}</div>
                    <div class="text-xs font-semibold uppercase tracking-wider text-slate-500 mt-1">{{ __('Multimodal Transport Legs') }}</div>
                </div>
            </div>
        </div>
    </div>

    <!-- SECTION: MULTIMODAL TRANSPORTATION MODES (#multimodal) -->
    <section id="multimodal" class="py-16 sm:py-20 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center max-w-2xl mx-auto mb-12">
            <span class="text-xs font-bold uppercase tracking-wider text-brand-sky">{{ __('Connected Across All Dimensions') }}</span>
            <h2 class="text-3xl font-bold tracking-tight text-slate-900 mt-1">{{ __('Multimodal Freight Infrastructure') }}</h2>
            <p class="text-sm text-slate-500 mt-2">
                {{ __('A single cargo journey often transitions across ports, airports, highways, and rail lines. EcoHorizon maintains end-to-end accountability at every handover.') }}
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <!-- Mode 1: Sea Freight -->
            <div class="bg-white p-8 rounded-2xl border border-slate-200 shadow-2xs hover:shadow-md transition-shadow">
                <div class="w-12 h-12 rounded-xl bg-sky-100 text-sky-700 flex items-center justify-center mb-6">
                    <svg width="24" height="24" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0"/></svg>
                </div>
                <h3 class="text-lg font-bold text-slate-900">{{ __('Maritime Sea Freight') }}</h3>
                <p class="text-sm text-slate-600 mt-2 leading-relaxed">
                    {{ __('Ultra Large Container Vessels (ULCV) and regional feeder lines. Lowest carbon footprint per tonne-kilometer (~15 g CO₂e/t-km). Monitored with port berthing and terminal discharges.') }}
                </p>
                <div class="mt-4 pt-4 border-t border-slate-100 flex items-center justify-between text-xs text-slate-500">
                    <span>{{ __('Average Factor:') }}</span>
                    <span class="font-bold text-slate-800">15 - 28 g CO₂e/t-km</span>
                </div>
            </div>

            <!-- Mode 2: Land Freight -->
            <div class="bg-white p-8 rounded-2xl border border-slate-200 shadow-2xs hover:shadow-md transition-shadow">
                <div class="w-12 h-12 rounded-xl bg-emerald-100 text-emerald-700 flex items-center justify-center mb-6">
                    <svg width="24" height="24" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0"/></svg>
                </div>
                <h3 class="text-lg font-bold text-slate-900">{{ __('Overland Road & Rail') }}</h3>
                <p class="text-sm text-slate-600 mt-2 leading-relaxed">
                    {{ __('First-mile pickup and final-mile delivery. Heavy Euro-6 articulated trucks and electric freight trains connecting manufacturing clusters directly to port container terminals.') }}
                </p>
                <div class="mt-4 pt-4 border-t border-slate-100 flex items-center justify-between text-xs text-slate-500">
                    <span>{{ __('Average Factor:') }}</span>
                    <span class="font-bold text-slate-800">18 - 62 g CO₂e/t-km</span>
                </div>
            </div>

            <!-- Mode 3: Air Freight -->
            <div class="bg-white p-8 rounded-2xl border border-slate-200 shadow-2xs hover:shadow-md transition-shadow">
                <div class="w-12 h-12 rounded-xl bg-indigo-100 text-indigo-700 flex items-center justify-center mb-6">
                    <svg width="24" height="24" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                </div>
                <h3 class="text-lg font-bold text-slate-900">{{ __('Intercontinental Air Cargo') }}</h3>
                <p class="text-sm text-slate-600 mt-2 leading-relaxed">
                    {{ __('Dedicated freighters and wide-body belly cargo for high-value electronics and urgent medical equipment. High speed with transparently accounted carbon impact.') }}
                </p>
                <div class="mt-4 pt-4 border-t border-slate-100 flex items-center justify-between text-xs text-slate-500">
                    <span>{{ __('Average Factor:') }}</span>
                    <span class="font-bold text-slate-800">430 - 512 g CO₂e/t-km</span>
                </div>
            </div>
        </div>
    </section>

    <!-- SECTION: DELIVERY SERVICE LEVELS (#services) -->
    <section id="services" class="py-16 sm:py-20 bg-slate-100/70 border-y border-slate-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-2xl mx-auto mb-12">
                <span class="text-xs font-bold uppercase tracking-wider text-brand-emerald">{{ __('Structured Delivery Conditions') }}</span>
                <h2 class="text-3xl font-bold tracking-tight text-slate-900 mt-1">{{ __('Delivery Service Levels & Handover Rules') }}</h2>
                <p class="text-sm text-slate-500 mt-2">
                    {{ __('Clear contractual handover scopes modeling international multimodal transit tiers.') }}
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                @foreach($serviceLevels as $sl)
                    @php
                        $badgeStyle = match($sl->code) {
                            'DOOR_TO_PORT' => 'bg-sky-50 text-sky-700 border-sky-200',
                            'PORT_TO_PORT' => 'bg-slate-100 text-slate-700 border-slate-200',
                            'B2B' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                            default => 'bg-slate-100 text-slate-700 border-slate-200'
                        };
                        $scopeText = match($sl->code) {
                            'DOOR_TO_PORT' => __('Origin Pickup → Destination Terminal Handover'),
                            'PORT_TO_PORT' => __('Origin Port Intake → Destination Terminal Release'),
                            'B2B' => __('Origin Pickup → Consignee Warehouse Dock Verification'),
                            default => __('Terminal to Terminal Transit')
                        };
                        $shortDesc = match($sl->code) {
                            'DOOR_TO_PORT' => __('Collection from shipper facility and carriage to destination seaport, airport, or customs terminal.'),
                            'PORT_TO_PORT' => __('Direct terminal-to-terminal transport between origin and destination discharge facilities.'),
                            'B2B' => __('Commercial cargo delivered directly to enterprise distribution centers with receiving dock receipt verification.'),
                            default => __($sl->description)
                        };
                    @endphp
                    <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-2xs hover:shadow-md transition-all flex flex-col justify-between">
                        <div>
                            <div class="flex items-center justify-between mb-4">
                                <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-bold uppercase tracking-wider border {{ $badgeStyle }}">
                                    {{ $sl->code }}
                                </span>
                                <span class="text-[10px] font-semibold text-slate-400 font-mono">
                                    {{ $sl->requires_recipient_delivery ? __('Mandatory POD') : __('Terminal Release') }}
                                </span>
                            </div>
                            <h3 class="text-base font-bold text-slate-900">{{ __($sl->name) }}</h3>
                            <p class="text-xs text-slate-600 mt-2 leading-relaxed">
                                {{ $shortDesc }}
                            </p>
                        </div>
                        <div class="mt-6 pt-4 border-t border-slate-100">
                            <div class="flex items-center gap-1.5 text-[11px] font-medium text-slate-600 bg-slate-50 p-2.5 rounded-xl border border-slate-100">
                                <svg class="w-3.5 h-3.5 text-brand-sky shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                                <span class="truncate">{{ $scopeText }}</span>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- SECTION: SUSTAINABILITY & EMISSIONS CALCULATOR (#sustainability) -->
    <section id="sustainability" class="py-16 sm:py-20 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-gradient-to-br from-slate-900 via-slate-900 to-emerald-950 text-white rounded-3xl p-8 sm:p-12 lg:p-16 border border-slate-800 shadow-xl overflow-hidden relative">
            <div class="max-w-3xl">
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider bg-emerald-900/60 text-emerald-300 border border-emerald-700/60 mb-4">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-ping"></span>
                    {{ __('Sustainability Accounting Core') }}
                </span>

                <h2 class="text-3xl sm:text-4xl font-extrabold tracking-tight">
                    {{ __('Auditable Carbon Emissions Calculator') }}
                </h2>

                <p class="mt-4 text-sm sm:text-base text-slate-300 leading-relaxed">
                    {{ __('Unlike platforms that claim unverified "carbon neutrality", EcoHorizon implements rigorous multimodal greenhouse gas (GHG) calculations conforming to the Global Logistics Emissions Council (GLEC) Framework v3.0 and IMO MEPC.308 guidelines.') }}
                </p>

                <!-- Documented Formula Box -->
                <div class="mt-8 p-6 rounded-2xl bg-slate-800/80 border border-slate-700 backdrop-blur-md">
                    <div class="text-xs uppercase font-bold text-emerald-400 tracking-wider mb-2">{{ __('Governing Emission Formula:') }}</div>
                    <div class="text-base sm:text-lg font-mono font-bold text-white tracking-wide">
                        {{ __('Estimated CO₂e (kg) = Cargo Weight (tonnes) × Distance (km) × Factor (g CO₂e / tonne-km) ÷ 1,000') }}
                    </div>
                    <p class="text-xs text-slate-400 mt-3 leading-relaxed">
                        {{ __('Every transport leg is calculated individually according to its specific mode, vehicle class, and corridor distance before being aggregated into total consignment footprints.') }}
                    </p>
                </div>

                <!-- Reference Factors Grid -->
                <div class="mt-8 grid grid-cols-1 sm:grid-cols-3 gap-4">
                    @foreach($emissionFactors as $factor)
                        <div class="p-4 rounded-xl bg-slate-800/50 border border-slate-700/60">
                            <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400">{{ strtoupper($factor->transport_mode) }} {{ __('DEFAULT') }}</span>
                            <div class="text-xl font-extrabold text-emerald-400 mt-1">{{ $factor->factor_g_per_tonne_km }} <span class="text-xs text-slate-400">g/t-km</span></div>
                            <div class="text-xs text-slate-300 font-medium truncate mt-1">{{ $factor->subcategory }}</div>
                            <div class="text-[10px] text-slate-500 mt-1 truncate">{{ $factor->source_reference }}</div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    <!-- SECTION: CALL TO ACTION FOR EVALUATORS -->
    <section class="py-16 bg-white border-t border-slate-200">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-900">
                {{ __('Ready to explore the operations cockpit?') }}
            </h2>
            <p class="text-sm text-slate-500 mt-2 max-w-xl mx-auto">
                {{ __('Sign in with the built-in local demo accounts to test shipment creation, multimodal route leg dispatch, exception management, and delivery verification.') }}
            </p>
            <div class="mt-8 flex flex-wrap items-center justify-center gap-4">
                <a href="{{ route('login') }}" class="px-6 py-3 rounded-xl font-bold text-sm bg-brand-sky text-white hover:bg-sky-700 shadow-md shadow-sky-600/20 transition-all">
                    {{ __('Open Demo Login Portal') }}
                </a>
                <a href="{{ route('track.show', 'EHG-20261029-JKTMUC') }}" class="px-6 py-3 rounded-xl font-bold text-sm bg-slate-100 text-slate-800 hover:bg-slate-200 transition-colors">
                    {{ __('View Flagship Multimodal Journey') }}
                </a>
            </div>
        </div>
    </section>

</x-guest-layout>
