<x-app-layout :title="__('Shipment :number — EcoHorizon Global', ['number' => $shipment->tracking_number])">
    <x-slot:header>
        {{ __('Shipment Registry') }}
    </x-slot:header>

    <div class="space-y-8">

        <!-- Top Header & Status Summary -->
        <div class="bg-white p-6 sm:p-8 rounded-2xl border border-slate-200/90 shadow-2xs">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6 pb-6 border-b border-slate-200">
                <div class="space-y-1.5">
                    <div class="flex items-center gap-2 text-xs font-semibold uppercase tracking-wider text-slate-500">
                        <a href="{{ route('shipments.index') }}" class="hover:text-brand-sky">{{ __('Shipment Registry') }}</a>
                        <span>/</span>
                        <span class="font-mono text-brand-sky font-bold">{{ $shipment->tracking_number }}</span>
                    </div>

                    <div class="flex flex-wrap items-center gap-3">
                        <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 font-mono tracking-tight">
                            {{ $shipment->tracking_number }}
                        </h1>
                        <x-status-badge :status="$shipment->current_status" />
                        <span class="inline-flex px-2.5 py-1 rounded-full text-xs font-bold uppercase tracking-wider bg-slate-100 text-slate-800 border border-slate-200">
                            {{ $shipment->serviceLevel->code }} &bull; {{ __($shipment->serviceLevel->name) }}
                        </span>
                        @if($shipment->isDelayed())
                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-bold bg-amber-100 text-amber-800 border border-amber-300">
                                {{ __('Schedule Delayed') }}
                            </span>
                        @endif
                    </div>

                    <p class="text-xs sm:text-sm text-slate-500">
                        <span class="font-bold text-slate-700">{{ $shipment->originLocation->name }}</span> ({{ $shipment->originLocation->country }}) 
                        <span class="text-brand-sky font-bold mx-1.5">&rarr;</span> 
                        <span class="font-bold text-slate-700">{{ $shipment->destinationLocation->name }}</span> ({{ $shipment->destinationLocation->country }})
                        <span class="text-slate-400 ml-2">&bull; {{ __('Destination:') }} {{ __(str_replace('_', ' ', $shipment->destination_type)) }}</span>
                    </p>
                </div>

                <!-- Right Quick Links & Actions -->
                <div class="flex flex-wrap items-center gap-2.5">
                    <a href="{{ route('track.show', $shipment->tracking_number) }}" target="_blank"
                       class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl text-xs font-semibold bg-white border border-slate-300 text-slate-700 hover:bg-slate-50 transition-colors shadow-2xs">
                        <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                        {{ __('Public Tracking Page') }}
                    </a>
                    @if(auth()->user()->canManageShipments())
                        <a href="{{ route('shipments.edit', $shipment->id) }}"
                           class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl text-xs font-semibold bg-slate-100 text-slate-700 hover:bg-slate-200 transition-colors">
                            <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                            {{ __('Edit Cargo') }}
                        </a>
                    @endif
                </div>
            </div>

            <!-- Key Metrics Grid -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 pt-6">
                <div>
                    <span class="text-[10px] uppercase font-bold text-slate-400">{{ __('Target ETA') }}</span>
                    <div class="font-bold text-slate-900 text-sm mt-0.5">
                        {{ $shipment->eta ? $shipment->eta->format('M d, Y H:i') . ' UTC' : 'TBD' }}
                    </div>
                    @if($shipment->original_eta && $shipment->eta && $shipment->eta->gt($shipment->original_eta))
                        <span class="text-[11px] text-amber-600 font-medium">{{ __('Originally:') }} {{ $shipment->original_eta->format('M d, Y') }}</span>
                    @endif
                </div>

                <div>
                    <span class="text-[10px] uppercase font-bold text-slate-400">{{ __('Cargo Payload') }}</span>
                    <div class="font-bold text-slate-900 text-sm mt-0.5">
                        {{ number_format($shipment->cargo_weight_tonnes, 2) }} {{ __('tonnes') }}
                    </div>
                    <span class="text-[11px] text-slate-500">{{ $shipment->package_count }}x {{ $shipment->package_type }}</span>
                </div>

                <div>
                    <span class="text-[10px] uppercase font-bold text-slate-400">{{ __('Total Distance') }}</span>
                    <div class="font-bold text-slate-900 text-sm mt-0.5">
                        {{ number_format($shipment->total_distance_km) }} km
                    </div>
                    <span class="text-[11px] text-slate-500">{{ $shipment->transportLegs->count() }} {{ __('Multimodal Legs') }}</span>
                </div>

                <div>
                    <span class="text-[10px] uppercase font-bold text-emerald-700">{{ __('Estimated CO₂e') }}</span>
                    <div class="font-bold text-emerald-700 text-sm mt-0.5">
                        {{ number_format($shipment->total_estimated_co2e_kg, 1) }} kg CO₂e
                    </div>
                    <span class="text-[11px] text-slate-500">{{ __('GLEC Framework v3.0') }}</span>
                </div>
            </div>
        </div>

        <!-- Active Exceptions Alert Banner -->
        @if($shipment->activeExceptions->count() > 0)
            <div class="space-y-3">
                @foreach($shipment->activeExceptions as $exception)
                    <div class="p-4 rounded-xl border {{ $exception->severity === 'critical' ? 'bg-red-50 border-red-300 text-red-900' : 'bg-amber-50 border-amber-300 text-amber-900' }} shadow-xs flex flex-col sm:flex-row sm:items-start justify-between gap-4">
                        <div class="flex items-start gap-3">
                            <svg class="w-5 h-5 shrink-0 mt-0.5 {{ $exception->severity === 'critical' ? 'text-red-600' : 'text-amber-600' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                            <div>
                                <div class="flex items-center gap-2">
                                    <span class="text-xs font-bold uppercase tracking-wider">
                                        {{ __(ucfirst(str_replace('_', ' ', $exception->category))) }} &bull; {{ __('Severity:') }} {{ __(strtoupper($exception->severity)) }}
                                    </span>
                                    <span class="text-xs opacity-75">({{ $exception->created_at->diffForHumans() }})</span>
                                </div>
                                <p class="text-sm font-medium mt-1">{{ $exception->description }}</p>
                                @if($exception->action_taken)
                                    <div class="mt-1.5 text-xs bg-white/70 p-2 rounded border border-black/10">
                                        <span class="font-bold">{{ __('Mitigation:') }}</span> {{ $exception->action_taken }}
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Resolve Exception Trigger -->
                        @if(auth()->user()->canManageShipments())
                            <button type="button" 
                                    onclick="document.getElementById('resolveModal-{{ $exception->id }}').classList.remove('hidden')"
                                    class="shrink-0 px-3 py-1.5 rounded-lg text-xs font-bold bg-white text-slate-800 hover:bg-slate-100 border border-slate-300 shadow-2xs">
                                {{ __('Resolve Exception') }}
                            </button>

                            <!-- Resolve Modal -->
                            <div id="resolveModal-{{ $exception->id }}" class="hidden fixed inset-0 z-50 bg-slate-900/50 backdrop-blur-xs flex items-center justify-center p-4">
                                <div class="bg-white rounded-2xl max-w-lg w-full p-6 space-y-4 shadow-xl">
                                    <h3 class="text-base font-bold text-slate-900">{{ __('Resolve Exception') }} #{{ $exception->id }}</h3>
                                    <p class="text-xs text-slate-500">{{ $exception->description }}</p>

                                    <form action="{{ route('exceptions.resolve', $exception->id) }}" method="POST" class="space-y-4">
                                        @csrf
                                        <div>
                                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">{{ __('Resolution Outcome & Notes *') }}</label>
                                            <textarea name="resolution_notes" required rows="3" class="w-full px-3 py-2 text-xs rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-brand-sky" placeholder="Describe actions taken to resolve hold or bypass disruption..."></textarea>
                                        </div>

                                        <div>
                                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">{{ __('Resume Shipment Status To *') }}</label>
                                            <select name="resume_status" required class="w-full py-2 px-3 text-xs rounded-xl border border-slate-300 bg-white">
                                                <option value="IN_TRANSIT">IN_TRANSIT (Resume En Route)</option>
                                                <option value="CUSTOMS_CLEARANCE">CUSTOMS_CLEARANCE (Continue Clearance)</option>
                                                <option value="AWAITING_CUSTOMER_PICKUP">AWAITING_CUSTOMER_PICKUP (Ready for Collection)</option>
                                                <option value="OUT_FOR_DELIVERY">OUT_FOR_DELIVERY (Out for Final Mile)</option>
                                            </select>
                                        </div>

                                        <div class="flex items-center justify-end gap-3 pt-3 border-t border-slate-100">
                                            <button type="button" onclick="document.getElementById('resolveModal-{{ $exception->id }}').classList.add('hidden')" class="px-3.5 py-1.5 rounded-lg text-xs font-semibold bg-slate-100 text-slate-700">{{ __('Cancel') }}</button>
                                            <button type="submit" class="px-4 py-1.5 rounded-lg text-xs font-bold bg-emerald-600 text-white hover:bg-emerald-700">{{ __('Confirm Resolution') }}</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        @endif

        <!-- ========================================================================= -->
        <!-- 2. MIDDLE SECTION: Interactive Map (Left) + Multimodal Segments (Right)   -->
        <!-- ========================================================================= -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-stretch">
            
            <!-- Left Col: Interactive Multimodal Corridor Map (58% width for better aspect ratio) -->
            <div id="corridorMapCard" class="lg:col-span-7 bg-white rounded-xl border border-slate-200 overflow-hidden shadow-sm flex flex-col transition-all">
                <div class="px-5 py-3 border-b border-slate-200 bg-slate-50/80 flex flex-wrap items-center justify-between gap-3">
                    <div class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-brand-sky" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/></svg>
                        <h2 class="text-xs font-bold text-slate-900 uppercase tracking-wider">
                            {!! __('Interactive Multimodal Corridor Map') !!}
                        </h2>
                    </div>

                    <div class="flex items-center gap-2.5 flex-wrap">
                        <!-- Sleek Minimalist Legend (Hover Tooltips) -->
                        <div class="bg-slate-50 border border-slate-200/90 px-3 py-1.5 rounded-full flex items-center gap-2.5 text-xs shadow-2xs">
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

                        <!-- View Controls: Full World & Route Focus -->
                        <div class="flex items-center gap-1.5 pl-2 border-l border-slate-200">
                            <button type="button" onclick="fitWorldView()" class="px-2 py-1 rounded-md text-[11px] font-semibold bg-white hover:bg-slate-100 text-slate-700 border border-slate-200 shadow-2xs transition-colors flex items-center gap-1" title="{{ __('View entire world') }}">
                                <span>🌐 {{ __('Entire World') }}</span>
                            </button>
                            <button type="button" onclick="fitCockpitRoute()" class="px-2 py-1 rounded-md text-[11px] font-semibold bg-sky-50 hover:bg-sky-100 text-brand-sky border border-sky-100 shadow-2xs transition-colors flex items-center gap-1" title="{{ __('Focus on shipment route') }}">
                                <span>🎯 {{ __('Focus Route') }}</span>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Leaflet Map Container -->
                <div id="shipmentCockpitMap" class="w-full flex-1 h-[580px] min-h-[580px] bg-[#aad3df] relative z-0"></div>
            </div>

            <!-- Right Col: MULTIMODAL SEGMENTS Panel (42% width) -->
            <div class="lg:col-span-5 bg-white rounded-xl border border-slate-200 shadow-sm flex flex-col justify-between">
                
                <!-- Panel Top & List -->
                <div>
                    <!-- Header -->
                    <div class="p-4 border-b border-slate-200 flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <h3 class="text-xs font-bold text-slate-900 uppercase tracking-wider">
                                {{ __('Multimodal Segments') }}
                            </h3>
                            <span class="px-2 py-0.5 rounded-full text-[11px] font-bold bg-sky-50 text-brand-sky border border-sky-100">
                                {{ $shipment->transportLegs->count() }} {{ __('LEGS') }}
                            </span>
                        </div>
                        @if(auth()->user()->canManageShipments() && !$shipment->isDelivered())
                            <button type="button" 
                                    onclick="document.getElementById('addLegFormDrawer').classList.toggle('hidden')"
                                    class="text-xs font-bold text-brand-sky hover:underline flex items-center gap-1">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                {{ __('Add Leg') }}
                            </button>
                        @endif
                    </div>

                    <!-- Collapsible Add Leg Form -->
                    <div id="addLegFormDrawer" class="hidden p-4 bg-slate-50 border-b border-slate-200 text-xs">
                        <h4 class="font-bold text-slate-800 uppercase tracking-wider mb-2.5">{{ __('Add Multimodal Leg') }}</h4>
                        <form action="{{ route('transport-legs.store', $shipment->id) }}" method="POST" class="space-y-2.5">
                            @csrf
                            <input type="hidden" name="sequence_number" value="{{ $shipment->transportLegs->count() + 1 }}">
                            <div class="grid grid-cols-2 gap-2">
                                <div>
                                    <label class="block text-[10px] font-bold uppercase text-slate-500 mb-1">{{ __('Mode') }} *</label>
                                    <select name="transport_mode" required class="w-full py-1.5 px-2 text-xs rounded-lg border border-slate-300 bg-white">
                                        <option value="sea">{{ __('Sea Freight') }}</option>
                                        <option value="land">{{ __('Land (Road/Rail)') }}</option>
                                        <option value="air">{{ __('Air Freight') }}</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-[10px] font-bold uppercase text-slate-500 mb-1">{{ __('Carrier') }} *</label>
                                    <input type="text" name="carrier_name" required placeholder="{{ __('Carrier name') }}" class="w-full py-1.5 px-2 text-xs rounded-lg border border-slate-300">
                                </div>
                            </div>
                            <div>
                                <label class="block text-[10px] font-bold uppercase text-slate-500 mb-1">{{ __('Reference') }}</label>
                                <input type="text" name="transport_reference" placeholder="{{ __('Vessel / Flight / Truck') }}" class="w-full py-1.5 px-2 text-xs rounded-lg border border-slate-300">
                            </div>
                            <div class="grid grid-cols-2 gap-2">
                                <div>
                                    <label class="block text-[10px] font-bold uppercase text-slate-500 mb-1">{{ __('Origin') }} *</label>
                                    <select name="origin_location_id" required class="w-full py-1.5 px-2 text-xs rounded-lg border border-slate-300 bg-white">
                                        @foreach($locations as $loc)
                                            <option value="{{ $loc->id }}" {{ $shipment->transportLegs->last()?->destination_location_id == $loc->id ? 'selected' : '' }}>
                                                {{ $loc->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-[10px] font-bold uppercase text-slate-500 mb-1">{{ __('Destination') }} *</label>
                                    <select name="destination_location_id" required class="w-full py-1.5 px-2 text-xs rounded-lg border border-slate-300 bg-white">
                                        @foreach($locations as $loc)
                                            <option value="{{ $loc->id }}" {{ $shipment->destination_location_id == $loc->id ? 'selected' : '' }}>
                                                {{ $loc->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="flex justify-end gap-2 pt-1">
                                <button type="button" onclick="document.getElementById('addLegFormDrawer').classList.add('hidden')" class="px-2.5 py-1 text-xs font-semibold bg-slate-200 rounded">{{ __('Cancel') }}</button>
                                <button type="submit" class="px-3 py-1 text-xs font-bold bg-brand-sky text-white rounded">{{ __('Save Leg') }}</button>
                            </div>
                        </form>
                    </div>

                    <!-- Scrollable Segments List -->
                    <div class="p-4 space-y-3 max-h-[440px] overflow-y-auto">
                        @foreach($shipment->transportLegs as $leg)
                            <div class="p-3.5 rounded-xl border {{ $leg->status === 'IN_TRANSIT' ? 'border-sky-300 bg-sky-50/40' : ($leg->status === 'COMPLETED' ? 'border-emerald-200 bg-emerald-50/25' : 'border-slate-200 bg-white') }} transition-all">
                                <div class="flex items-center justify-between gap-2 mb-1.5">
                                    <div class="flex items-center gap-1.5">
                                        <span class="w-5 h-5 rounded-full bg-slate-900 text-white text-[10px] font-bold flex items-center justify-center font-mono">
                                            {{ $leg->sequence_number }}
                                        </span>
                                        <span class="inline-flex px-2 py-0.5 rounded text-[10px] font-extrabold uppercase tracking-wider
                                            {{ $leg->transport_mode === 'sea' ? 'bg-blue-100 text-blue-800' : ($leg->transport_mode === 'air' ? 'bg-indigo-100 text-indigo-800' : 'bg-emerald-100 text-emerald-800') }}">
                                            {{ __(strtoupper($leg->transport_mode)) }}
                                        </span>
                                    </div>
                                    <span class="text-[10px] font-bold px-2 py-0.5 rounded-full uppercase tracking-wider
                                        {{ $leg->status === 'COMPLETED' ? 'bg-emerald-100 text-emerald-800' : ($leg->status === 'IN_TRANSIT' ? 'bg-sky-100 text-sky-800 animate-pulse' : 'bg-slate-100 text-slate-600') }}">
                                        {{ __(str_replace('_', ' ', $leg->status)) }}
                                    </span>
                                </div>

                                <div class="text-xs font-semibold text-slate-800 line-clamp-2">
                                    {{ $leg->originLocation->name }}
                                    <span class="text-slate-400 font-normal mx-1">&darr;</span>
                                    <br>{{ $leg->destinationLocation->name }}
                                </div>

                                <div class="mt-2 pt-2 border-t border-slate-100 flex items-center justify-between text-[11px] text-slate-500">
                                    <span class="truncate max-w-[140px]" title="{{ $leg->carrier_name }}">{{ $leg->carrier_name }}</span>
                                    <span class="font-mono font-bold text-slate-700">{{ number_format($leg->distance_km) }} km</span>
                                </div>

                                @if(auth()->user()->canManageShipments() && $leg->status !== 'COMPLETED')
                                    <form action="{{ route('transport-legs.update', $leg->id) }}" method="POST" class="mt-2 text-right">
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="status" value="COMPLETED">
                                        <input type="hidden" name="actual_arrival" value="{{ now() }}">
                                        <button type="submit" class="text-[10px] font-bold px-2 py-0.5 rounded bg-emerald-600 text-white hover:bg-emerald-700">
                                            {{ __('Mark Completed') }}
                                        </button>
                                    </form>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Panel Footer Stats -->
                <div class="p-4 border-t border-slate-200 bg-slate-50/70 text-xs">
                    <div class="flex items-center justify-between mb-1.5">
                        <span class="text-slate-500 font-medium">{{ __('Total CO₂e:') }}</span>
                        <span class="font-bold text-emerald-700 font-mono">{{ number_format($shipment->total_estimated_co2e_kg, 1) }} kg</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-slate-500 font-medium">{{ __('Estimated Arrival:') }}</span>
                        <span class="font-bold text-slate-800 font-mono">{{ $shipment->eta ? $shipment->eta->format('M d, Y') : 'TBD' }}</span>
                    </div>
                </div>

            </div>

        </div>

        <!-- ========================================================================= -->
        <!-- 3. BOTTOM SECTION: Milestones Timeline (Left) + Operations Forms (Right)   -->
        <!-- ========================================================================= -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            
            <!-- Left 7 Cols: Tracking Milestones Log -->
            <div class="lg:col-span-7 bg-white p-6 rounded-2xl border border-slate-200 shadow-2xs">
                <h3 class="text-xs font-bold text-slate-900 uppercase tracking-wider mb-6 flex items-center gap-2">
                    <svg class="w-4 h-4 text-brand-sky" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    {{ __('Tracking Milestones Log') }}
                </h3>

                @if($shipment->trackingEvents->isEmpty())
                    <div class="p-8 text-center text-xs text-slate-400">{{ __('No events logged yet.') }}</div>
                @else
                    <div class="relative border-l-2 border-slate-200 ml-3 space-y-6">
                        @foreach($shipment->trackingEvents as $event)
                            @php
                                $isCompletion = in_array($event->event_type, ['DELIVERED', 'HANDOVER']) || ($shipment->isDelivered() && $loop->first);
                            @endphp
                            <div class="relative pl-6">
                                <div class="absolute -left-[9px] top-1 w-4 h-4 rounded-full border-2 border-white 
                                    {{ $isCompletion ? 'bg-emerald-500 ring-4 ring-emerald-100' : ($loop->first ? 'bg-[#2563EB] ring-4 ring-blue-100' : 'bg-slate-400') }}"></div>

                                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-1 mb-1">
                                    <div class="text-xs font-bold {{ $isCompletion ? 'text-emerald-700' : 'text-slate-900' }} flex items-center gap-2">
                                        <span class="{{ $isCompletion ? 'px-2 py-0.5 rounded-full bg-emerald-50 border border-emerald-200' : '' }}">
                                            {{ __(str_replace('_', ' ', $event->event_type)) }}
                                        </span>
                                        <span class="text-[11px] font-normal text-slate-500">{{ __('at') }} {{ $event->location_name }}</span>
                                    </div>
                                    <span class="text-[10px] font-mono text-slate-400">{{ $event->event_time->format('M d, Y H:i') }} UTC</span>
                                </div>
                                <p class="text-xs text-slate-600 leading-relaxed">{{ __($event->description) }}</p>
                                @if($event->proof_notes)
                                    <div class="mt-1 text-[11px] font-mono text-slate-700 bg-slate-50 p-2 rounded border border-slate-200">
                                        {{ __('Evidence:') }} {{ $event->proof_notes }}
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <!-- Right 5 Cols: Operations Actions (Record Event, Log Exception, Confirm Delivery, Weather) -->
            <div class="lg:col-span-5 space-y-6">

                <!-- Action Form 1: Record Tracking Event -->
                @if(auth()->user()->canManageShipments() && !$shipment->isDelivered())
                    <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-2xs space-y-4">
                        <h3 class="text-xs font-bold text-slate-900 uppercase tracking-wider flex items-center gap-1.5">
                            <span class="w-2 h-2 rounded-full bg-brand-sky"></span>
                            {{ __('Record Tracking Event & Transition') }}
                        </h3>

                        <form action="{{ route('tracking-events.store', $shipment->id) }}" method="POST" class="space-y-3">
                            @csrf
                            <div>
                                <label class="block text-[10px] font-bold uppercase text-slate-500 mb-1">{{ __('Event Type') }} *</label>
                                <select name="event_type" required class="w-full py-2 px-3 text-xs rounded-xl border border-slate-300 bg-white">
                                    <option value="PICKUP">PICKUP ({{ __('Cargo Collected at Origin') }})</option>
                                    <option value="DEPARTURE">DEPARTURE ({{ __('Vessel / Flight / Truck Departs') }})</option>
                                    <option value="WAYPOINT_CHECKPOINT">WAYPOINT_CHECKPOINT ({{ __('En Route Waypoint') }})</option>
                                    <option value="ARRIVED_HUB">ARRIVED_HUB ({{ __('Transit Port / Air Cargo Center') }})</option>
                                    <option value="CUSTOMS_INSPECTION">CUSTOMS_INSPECTION ({{ __('Inspection Underway') }})</option>
                                    <option value="CUSTOMS_CLEARED">CUSTOMS_CLEARED ({{ __('Import/Export Cleared') }})</option>
                                    <option value="ARRIVED_TERMINAL">ARRIVED_TERMINAL ({{ __('Destination Terminal Discharged') }})</option>
                                    <option value="AWAITING_PICKUP">AWAITING_PICKUP ({{ __('Ready for Consignee Collection') }})</option>
                                    <option value="OUT_FOR_DELIVERY">OUT_FOR_DELIVERY ({{ __('Out for Final Mile Delivery') }})</option>
                                    <option value="HANDOVER">HANDOVER ({{ __('Terminal / Warehouse Handover') }})</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-[10px] font-bold uppercase text-slate-500 mb-1">{{ __('New Shipment Status') }} *</label>
                                <select name="new_status" required class="w-full py-2 px-3 text-xs rounded-xl border border-slate-300 bg-white">
                                    <option value="IN_TRANSIT">IN_TRANSIT</option>
                                    <option value="ARRIVED_AT_TRANSIT_HUB">ARRIVED_AT_TRANSIT_HUB</option>
                                    <option value="ARRIVED_AT_DESTINATION_TERMINAL">ARRIVED_AT_DESTINATION_TERMINAL</option>
                                    <option value="CUSTOMS_CLEARANCE">CUSTOMS_CLEARANCE</option>
                                    <option value="AWAITING_CUSTOMER_PICKUP">AWAITING_CUSTOMER_PICKUP</option>
                                    <option value="OUT_FOR_DELIVERY">OUT_FOR_DELIVERY</option>
                                </select>
                            </div>

                            <div class="grid grid-cols-2 gap-2">
                                <div>
                                    <label class="block text-[10px] font-bold uppercase text-slate-500 mb-1">{{ __('Timestamp') }} *</label>
                                    <input type="datetime-local" name="event_time" required value="{{ now()->format('Y-m-d\TH:i') }}" class="w-full py-1.5 px-2 text-xs rounded-lg border border-slate-300">
                                </div>
                                <div>
                                    <label class="block text-[10px] font-bold uppercase text-slate-500 mb-1">{{ __('Location Name') }} *</label>
                                    <input type="text" name="location_name" required value="{{ $shipment->originLocation->name }}" class="w-full py-1.5 px-2 text-xs rounded-lg border border-slate-300">
                                </div>
                            </div>

                            <div>
                                <label class="block text-[10px] font-bold uppercase text-slate-500 mb-1">{{ __('Event Narrative Description') }} *</label>
                                <textarea name="description" required rows="2" class="w-full py-1.5 px-2 text-xs rounded-lg border border-slate-300" placeholder="{{ __('e.g. Vessel berthed at Quay 4, container discharged onto yard...') }}"></textarea>
                            </div>

                            <button type="submit" class="w-full py-2 rounded-xl text-xs font-bold text-white bg-brand-sky hover:bg-sky-700 transition-colors shadow-2xs">
                                {{ __('Log Tracking Milestone') }}
                            </button>
                        </form>
                    </div>
                @endif

                <!-- Action Form 2: Report Exception or Disruption -->
                @if(auth()->user()->canManageShipments() && !$shipment->isDelivered())
                    <div class="bg-white p-6 rounded-2xl border border-amber-200 bg-amber-50/10 shadow-2xs space-y-4">
                        <h3 class="text-xs font-bold text-amber-800 uppercase tracking-wider flex items-center gap-1.5">
                            <span class="w-2 h-2 rounded-full bg-amber-500"></span>
                            {{ __('Report Schedule Delay / Exception') }}
                        </h3>

                        <form action="{{ route('exceptions.store', $shipment->id) }}" method="POST" class="space-y-3">
                            @csrf
                            <div class="grid grid-cols-2 gap-2">
                                <div>
                                    <label class="block text-[10px] font-bold uppercase text-slate-500 mb-1">{{ __('Category') }} *</label>
                                    <select name="category" required class="w-full py-1.5 px-2 text-xs rounded-lg border border-slate-300 bg-white">
                                        <option value="weather_disruption">{{ __('Weather Disruption') }}</option>
                                        <option value="transport_delay">{{ __('Transport Delay') }}</option>
                                        <option value="damaged_cargo">{{ __('Damaged Cargo') }}</option>
                                        <option value="missing_documentation">{{ __('Missing Documentation') }}</option>
                                        <option value="customs_clearance_issue">{{ __('Customs Hold') }}</option>
                                        <option value="failed_delivery_attempt">{{ __('Failed Delivery Attempt') }}</option>
                                        <option value="other_operational_issue">{{ __('Other Operational Issue') }}</option>
                                    </select>
                                </div>

                                <div>
                                    <label class="block text-[10px] font-bold uppercase text-slate-500 mb-1">{{ __('Severity') }} *</label>
                                    <select name="severity" required class="w-full py-1.5 px-2 text-xs rounded-lg border border-slate-300 bg-white">
                                        <option value="low">{{ __('Low') }}</option>
                                        <option value="medium" selected>{{ __('Medium') }}</option>
                                        <option value="high">{{ __('High') }}</option>
                                        <option value="critical">{{ __('Critical') }}</option>
                                    </select>
                                </div>
                            </div>

                            <div>
                                <label class="block text-[10px] font-bold uppercase text-slate-500 mb-1">{{ __('ETA Impact (Hours to add)') }}</label>
                                <input type="number" name="impact_on_eta_hours" value="24" min="0" class="w-full py-1.5 px-2 text-xs rounded-lg border border-slate-300">
                            </div>

                            <div>
                                <label class="block text-[10px] font-bold uppercase text-slate-500 mb-1">{{ __('Description') }} *</label>
                                <textarea name="description" required rows="2" class="w-full py-1.5 px-2 text-xs rounded-lg border border-slate-300" placeholder="{{ __('e.g. Gale force winds in strait forcing anchor wait...') }}"></textarea>
                            </div>

                            <div>
                                <label class="block text-[10px] font-bold uppercase text-slate-500 mb-1">{{ __('Mitigation Action Taken') }}</label>
                                <textarea name="action_taken" rows="1" class="w-full py-1.5 px-2 text-xs rounded-lg border border-slate-300" placeholder="{{ __('e.g. Rerouting via alternate highway...') }}"></textarea>
                            </div>

                            <button type="submit" class="w-full py-2 rounded-xl text-xs font-bold text-white bg-amber-600 hover:bg-amber-700 transition-colors shadow-2xs">
                                {{ __('Log Exception & Update Status') }}
                            </button>
                        </form>
                    </div>
                @endif

                <!-- Action Form 3: Final Delivery Confirmation (Enforcing Business Rules) -->
                @if(auth()->user()->canManageShipments() && !$shipment->isDelivered())
                    <div class="bg-emerald-50 border border-emerald-300 p-6 rounded-2xl shadow-xs space-y-4">
                        <div class="flex items-center gap-2 text-emerald-800 font-bold text-xs uppercase tracking-wider">
                            <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            {{ __('Confirm Final Delivery Fulfillment') }}
                        </div>
                        <p class="text-[11px] text-emerald-900 leading-relaxed">
                            {{ __('Marks consignment as completed. Requires verified recipient signature or B2B warehouse dock receipt note.') }}
                        </p>

                        <form action="{{ route('delivery.confirm', $shipment->id) }}" method="POST" class="space-y-3">
                            @csrf
                            <div>
                                <label class="block text-[10px] font-bold uppercase text-emerald-900 mb-1">
                                    {{ __('Receiver / Authorized Officer Name') }} *
                                </label>
                                <input type="text" name="receiver_name" required value="{{ $shipment->receiver_contact_name }}"
                                       class="w-full py-1.5 px-2 text-xs rounded-lg border border-emerald-300 bg-white">
                            </div>

                            <div>
                                <label class="block text-[10px] font-bold uppercase text-emerald-900 mb-1">
                                    {{ __('Receipt Evidence / Proof Notes') }} *
                                </label>
                                <textarea name="proof_notes" required rows="2" class="w-full py-1.5 px-2 text-xs rounded-lg border border-emerald-300 bg-white"
                                          placeholder="{{ __('e.g. Stamped Goods Receipt Slip #GRN-99812. All seals inspected intact.') }}"></textarea>
                            </div>

                            <button type="submit" 
                                    class="w-full py-2.5 rounded-xl text-xs font-bold text-white bg-emerald-600 hover:bg-emerald-700 transition-colors shadow-sm">
                                {{ __('Verify & Fulfill Final Delivery') }}
                            </button>
                        </form>
                    </div>
                @endif

                <!-- Live Weather Card (Open-Meteo Integration) -->
                @if($weatherData)
                    <div class="bg-gradient-to-br from-slate-900 to-slate-800 text-white p-6 rounded-2xl shadow-md border border-slate-700">
                        <div class="flex items-center justify-between mb-3">
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
                        <div class="mt-3 pt-3 border-t border-slate-700/80 text-xs {{ $weatherData['is_adverse'] ? 'text-amber-300 font-semibold' : 'text-emerald-300' }}">
                            {{ __($weatherData['disruption_risk']) }}
                        </div>
                    </div>
                @endif

            </div>
        </div>

    </div>

    <!-- Leaflet Script for Integrated Corridor Map -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            if (typeof L === 'undefined') return;

            const mapData = @json($mapRoute);
            if (!mapData || mapData.length === 0) return;

            const map = L.map('shipmentCockpitMap', {
                dragging: true,
                scrollWheelZoom: false, // Natural page scroll continues when hovering over map unless Ctrl is pressed
                touchZoom: true,
                doubleClickZoom: true,
                boxZoom: true,
                zoomControl: true,
                minZoom: 1, // Allow full world view
                maxBounds: [[-85.06, -180], [85.06, 180]],
                maxBoundsViscosity: 0.1,
                worldCopyJump: false
            }).setView([mapData[0].origin.lat, mapData[0].origin.lon], 4);

            // Colorful Standard OpenStreetMap Tile Layer (minZoom: 1)
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 18,
                minZoom: 1,
                noWrap: true,
                bounds: [[-85.06, -180], [85.06, 180]],
                attribution: '&copy; OpenStreetMap contributors'
            }).addTo(map);

            // Ctrl + Scroll Wheel to zoom map, regular page scroll remains free
            const cockpitMapEl = document.getElementById('shipmentCockpitMap');
            if (cockpitMapEl) {
                cockpitMapEl.addEventListener('wheel', function (e) {
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

            window.fitCockpitRoute = function () {
                map.invalidateSize();
                if (bounds.length > 0) {
                    map.fitBounds(bounds, { padding: [45, 45], maxZoom: 6 });
                    if (map.getZoom() < 1) {
                        map.setZoom(1);
                    }
                }
            };

            window.fitWorldView = function () {
                map.invalidateSize();
                map.setView([20.0, 10.0], 1);
            };

            fitCockpitRoute();

            setTimeout(fitCockpitRoute, 150);
            setTimeout(fitCockpitRoute, 400);

            window.addEventListener('resize', fitCockpitRoute);
        });
    </script>
</x-app-layout>
