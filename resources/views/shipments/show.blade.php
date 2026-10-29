<x-app-layout title="Shipment {{ $shipment->tracking_number }} — EcoHorizon Global">
    <div class="space-y-8">

        <!-- Top Header & Status Summary -->
        <div class="bg-white p-6 sm:p-8 rounded-2xl border border-slate-200/90 shadow-2xs">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6 pb-6 border-b border-slate-200">
                <div class="space-y-1.5">
                    <div class="flex items-center gap-2 text-xs font-semibold uppercase tracking-wider text-slate-500">
                        <a href="{{ route('shipments.index') }}" class="hover:text-brand-sky">Shipment Registry</a>
                        <span>/</span>
                        <span class="font-mono text-brand-sky font-bold">{{ $shipment->tracking_number }}</span>
                    </div>

                    <div class="flex flex-wrap items-center gap-3">
                        <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 font-mono tracking-tight">
                            {{ $shipment->tracking_number }}
                        </h1>
                        <x-status-badge :status="$shipment->current_status" />
                        <span class="inline-flex px-2.5 py-1 rounded-full text-xs font-bold uppercase tracking-wider bg-slate-100 text-slate-800 border border-slate-200">
                            {{ $shipment->serviceLevel->code }} &bull; {{ $shipment->serviceLevel->name }}
                        </span>
                        @if($shipment->isDelayed())
                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-bold bg-amber-100 text-amber-800 border border-amber-300">
                                Schedule Delayed
                            </span>
                        @endif
                    </div>

                    <p class="text-xs sm:text-sm text-slate-500">
                        <span class="font-bold text-slate-700">{{ $shipment->originLocation->name }}</span> ({{ $shipment->originLocation->country }}) 
                        <span class="text-brand-sky font-bold mx-1.5">&rarr;</span> 
                        <span class="font-bold text-slate-700">{{ $shipment->destinationLocation->name }}</span> ({{ $shipment->destinationLocation->country }})
                        <span class="text-slate-400 ml-2">&bull; Destination: {{ str_replace('_', ' ', $shipment->destination_type) }}</span>
                    </p>
                </div>

                <!-- Right Quick Links & Actions -->
                <div class="flex flex-wrap items-center gap-2.5">
                    <a href="{{ route('track.show', $shipment->tracking_number) }}" target="_blank"
                       class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl text-xs font-semibold bg-white border border-slate-300 text-slate-700 hover:bg-slate-50 transition-colors shadow-2xs">
                        <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                        Public Tracking Page
                    </a>
                    @if(auth()->user()->canManageShipments())
                        <a href="{{ route('shipments.edit', $shipment->id) }}"
                           class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl text-xs font-semibold bg-slate-100 text-slate-700 hover:bg-slate-200 transition-colors">
                            <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                            Edit Cargo
                        </a>
                    @endif
                </div>
            </div>

            <!-- Key Metrics Grid -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 pt-6">
                <div>
                    <span class="text-[10px] uppercase font-bold text-slate-400">Target ETA</span>
                    <div class="font-bold text-slate-900 text-sm mt-0.5">
                        {{ $shipment->eta ? $shipment->eta->format('M d, Y H:i') . ' UTC' : 'TBD' }}
                    </div>
                    @if($shipment->original_eta && $shipment->eta && $shipment->eta->gt($shipment->original_eta))
                        <span class="text-[11px] text-amber-600 font-medium">Originally: {{ $shipment->original_eta->format('M d, Y') }}</span>
                    @endif
                </div>

                <div>
                    <span class="text-[10px] uppercase font-bold text-slate-400">Cargo Payload</span>
                    <div class="font-bold text-slate-900 text-sm mt-0.5">
                        {{ number_format($shipment->cargo_weight_tonnes, 2) }} tonnes
                    </div>
                    <span class="text-[11px] text-slate-500">{{ $shipment->package_count }}x {{ $shipment->package_type }}</span>
                </div>

                <div>
                    <span class="text-[10px] uppercase font-bold text-slate-400">Total Distance</span>
                    <div class="font-bold text-slate-900 text-sm mt-0.5">
                        {{ number_format($shipment->total_distance_km) }} km
                    </div>
                    <span class="text-[11px] text-slate-500">{{ $shipment->transportLegs->count() }} Multimodal Legs</span>
                </div>

                <div>
                    <span class="text-[10px] uppercase font-bold text-emerald-700">Estimated CO₂e</span>
                    <div class="font-bold text-emerald-700 text-sm mt-0.5">
                        {{ number_format($shipment->total_estimated_co2e_kg, 1) }} kg CO₂e
                    </div>
                    <span class="text-[11px] text-slate-500">GLEC Framework v3.0</span>
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
                                        {{ ucfirst(str_replace('_', ' ', $exception->category)) }} &bull; Severity: {{ strtoupper($exception->severity) }}
                                    </span>
                                    <span class="text-xs opacity-75">({{ $exception->created_at->diffForHumans() }})</span>
                                </div>
                                <p class="text-sm font-medium mt-1">{{ $exception->description }}</p>
                                @if($exception->action_taken)
                                    <div class="mt-1.5 text-xs bg-white/70 p-2 rounded border border-black/10">
                                        <span class="font-bold">Mitigation:</span> {{ $exception->action_taken }}
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Resolve Exception Trigger -->
                        @if(auth()->user()->canManageShipments())
                            <button type="button" 
                                    onclick="document.getElementById('resolveModal-{{ $exception->id }}').classList.remove('hidden')"
                                    class="shrink-0 px-3 py-1.5 rounded-lg text-xs font-bold bg-white text-slate-800 hover:bg-slate-100 border border-slate-300 shadow-2xs">
                                Resolve Exception
                            </button>

                            <!-- Resolve Modal -->
                            <div id="resolveModal-{{ $exception->id }}" class="hidden fixed inset-0 z-50 bg-slate-900/50 backdrop-blur-xs flex items-center justify-center p-4">
                                <div class="bg-white rounded-2xl max-w-lg w-full p-6 space-y-4 shadow-xl">
                                    <h3 class="text-base font-bold text-slate-900">Resolve Exception #{{ $exception->id }}</h3>
                                    <p class="text-xs text-slate-500">{{ $exception->description }}</p>

                                    <form action="{{ route('exceptions.resolve', $exception->id) }}" method="POST" class="space-y-4">
                                        @csrf
                                        <div>
                                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Resolution Outcome & Notes *</label>
                                            <textarea name="resolution_notes" required rows="3" class="w-full px-3 py-2 text-xs rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-brand-sky" placeholder="Describe actions taken to resolve hold or bypass disruption..."></textarea>
                                        </div>

                                        <div>
                                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Resume Shipment Status To *</label>
                                            <select name="resume_status" required class="w-full py-2 px-3 text-xs rounded-xl border border-slate-300 bg-white">
                                                <option value="IN_TRANSIT">IN_TRANSIT (Resume En Route)</option>
                                                <option value="CUSTOMS_CLEARANCE">CUSTOMS_CLEARANCE (Continue Clearance)</option>
                                                <option value="AWAITING_CUSTOMER_PICKUP">AWAITING_CUSTOMER_PICKUP (Ready for Collection)</option>
                                                <option value="OUT_FOR_DELIVERY">OUT_FOR_DELIVERY (Out for Final Mile)</option>
                                            </select>
                                        </div>

                                        <div class="flex items-center justify-end gap-3 pt-3 border-t border-slate-100">
                                            <button type="button" onclick="document.getElementById('resolveModal-{{ $exception->id }}').classList.add('hidden')" class="px-3.5 py-1.5 rounded-lg text-xs font-semibold bg-slate-100 text-slate-700">Cancel</button>
                                            <button type="submit" class="px-4 py-1.5 rounded-lg text-xs font-bold bg-emerald-600 text-white hover:bg-emerald-700">Confirm Resolution</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        @endif

        <!-- Interactive Map Section -->
        <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden shadow-2xs">
            <div class="px-6 py-4 border-b border-slate-200 bg-slate-50 flex items-center justify-between">
                <h2 class="text-xs font-bold text-slate-900 uppercase tracking-wider flex items-center gap-2">
                    <svg class="w-4 h-4 text-brand-sky" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/></svg>
                    Interactive Multimodal Corridor Map
                </h2>
                <div class="flex items-center gap-3 text-xs font-medium text-slate-600">
                    <span class="flex items-center gap-1"><span class="w-2.5 h-1 bg-[#0284C7] rounded"></span> Sea</span>
                    <span class="flex items-center gap-1"><span class="w-2.5 h-1 bg-[#10B981] rounded"></span> Land</span>
                    <span class="flex items-center gap-1"><span class="w-2.5 h-1 bg-[#6366F1] border-b border-dashed border-[#6366F1]"></span> Air</span>
                </div>
            </div>
            <div id="shipmentCockpitMap" class="w-full h-80 sm:h-96 bg-slate-100"></div>
        </div>

        <!-- 2-Columns Grid: Multimodal Legs & Operations Action Controls -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            <!-- Left 2 Cols: Multimodal Transport Legs & Waypoint Events Timeline -->
            <div class="lg:col-span-2 space-y-8">
                
                <!-- Component A: Transport Legs -->
                <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-2xs">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-xs font-bold text-slate-900 uppercase tracking-wider flex items-center gap-2">
                            <svg class="w-4 h-4 text-brand-sky" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                            Transport Legs Breakdown ({{ $shipment->transportLegs->count() }})
                        </h3>
                        @if(auth()->user()->canManageShipments() && !$shipment->isDelivered())
                            <button type="button" 
                                    onclick="document.getElementById('addLegForm').classList.toggle('hidden')"
                                    class="text-xs font-bold text-brand-sky hover:underline flex items-center gap-1">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                Add Chained Leg
                            </button>
                        @endif
                    </div>

                    <!-- Add Leg Form (Collapsible) -->
                    <div id="addLegForm" class="hidden mb-6 p-4 rounded-xl bg-slate-50 border border-slate-200">
                        <h4 class="text-xs font-bold text-slate-800 uppercase tracking-wider mb-3">Add Multimodal Transport Leg</h4>
                        <form action="{{ route('transport-legs.store', $shipment->id) }}" method="POST" class="space-y-3">
                            @csrf
                            <input type="hidden" name="sequence_number" value="{{ $shipment->transportLegs->count() + 1 }}">
                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                                <div>
                                    <label class="block text-[10px] font-bold uppercase text-slate-500 mb-1">Mode *</label>
                                    <select name="transport_mode" required class="w-full py-1.5 px-2 text-xs rounded-lg border border-slate-300 bg-white">
                                        <option value="sea">Sea Freight</option>
                                        <option value="land">Land (Road / Rail)</option>
                                        <option value="air">Air Freight</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-[10px] font-bold uppercase text-slate-500 mb-1">Carrier Name *</label>
                                    <input type="text" name="carrier_name" required placeholder="e.g. Samudera, Lufthansa" class="w-full py-1.5 px-2 text-xs rounded-lg border border-slate-300">
                                </div>
                                <div>
                                    <label class="block text-[10px] font-bold uppercase text-slate-500 mb-1">Transport Reference</label>
                                    <input type="text" name="transport_reference" placeholder="Vessel Voy #, Flight #, Plate" class="w-full py-1.5 px-2 text-xs rounded-lg border border-slate-300">
                                </div>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                <div>
                                    <label class="block text-[10px] font-bold uppercase text-slate-500 mb-1">Leg Origin *</label>
                                    <select name="origin_location_id" required class="w-full py-1.5 px-2 text-xs rounded-lg border border-slate-300 bg-white">
                                        @foreach($locations as $loc)
                                            <option value="{{ $loc->id }}" {{ $shipment->transportLegs->last()?->destination_location_id == $loc->id ? 'selected' : '' }}>
                                                {{ $loc->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-[10px] font-bold uppercase text-slate-500 mb-1">Leg Destination *</label>
                                    <select name="destination_location_id" required class="w-full py-1.5 px-2 text-xs rounded-lg border border-slate-300 bg-white">
                                        @foreach($locations as $loc)
                                            <option value="{{ $loc->id }}" {{ $shipment->destination_location_id == $loc->id ? 'selected' : '' }}>
                                                {{ $loc->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                <div>
                                    <label class="block text-[10px] font-bold uppercase text-slate-500 mb-1">Distance (km, optional override)</label>
                                    <input type="number" step="0.1" name="distance_km" placeholder="Leave blank to auto-calculate" class="w-full py-1.5 px-2 text-xs rounded-lg border border-slate-300">
                                </div>
                                <div>
                                    <label class="block text-[10px] font-bold uppercase text-slate-500 mb-1">Emission Factor</label>
                                    <select name="emission_factor_id" class="w-full py-1.5 px-2 text-xs rounded-lg border border-slate-300 bg-white">
                                        <option value="">-- Default by Mode --</option>
                                        @foreach($emissionFactors as $ef)
                                            <option value="{{ $ef->id }}">[{{ strtoupper($ef->transport_mode) }}] {{ $ef->subcategory }} ({{ $ef->factor_g_per_tonne_km }} g/t-km)</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="flex justify-end gap-2 pt-2">
                                <button type="button" onclick="document.getElementById('addLegForm').classList.add('hidden')" class="px-3 py-1 text-xs font-semibold bg-slate-200 rounded">Cancel</button>
                                <button type="submit" class="px-4 py-1 text-xs font-bold bg-brand-sky text-white rounded">Add Leg to Journey</button>
                            </div>
                        </form>
                    </div>

                    <!-- Legs List -->
                    <div class="space-y-3">
                        @foreach($shipment->transportLegs as $leg)
                            <div class="p-4 rounded-xl border {{ $leg->status === 'IN_TRANSIT' ? 'border-sky-300 bg-sky-50/30' : ($leg->status === 'COMPLETED' ? 'border-emerald-200 bg-emerald-50/20' : 'border-slate-200') }}">
                                <div class="flex flex-wrap items-center justify-between gap-2">
                                    <div class="flex items-center gap-2">
                                        <span class="w-5 h-5 rounded-full bg-slate-900 text-white text-[11px] font-bold flex items-center justify-center font-mono">
                                            {{ $leg->sequence_number }}
                                        </span>
                                        <span class="inline-flex px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider
                                            {{ $leg->transport_mode === 'sea' ? 'bg-blue-100 text-blue-800' : ($leg->transport_mode === 'air' ? 'bg-indigo-100 text-indigo-800' : 'bg-emerald-100 text-emerald-800') }}">
                                            {{ $leg->transport_mode }}
                                        </span>
                                        <span class="text-xs font-bold text-slate-800">{{ $leg->carrier_name }}</span>
                                        <span class="text-xs text-slate-500 font-mono">({{ $leg->transport_reference ?? 'No Ref' }})</span>
                                    </div>

                                    <div class="flex items-center gap-2">
                                        <span class="text-xs font-semibold px-2 py-0.5 rounded-full 
                                            {{ $leg->status === 'COMPLETED' ? 'bg-emerald-100 text-emerald-800' : ($leg->status === 'IN_TRANSIT' ? 'bg-sky-100 text-sky-800 animate-pulse' : 'bg-slate-100 text-slate-600') }}">
                                            {{ $leg->status }}
                                        </span>
                                        @if(auth()->user()->canManageShipments() && $leg->status !== 'COMPLETED')
                                            <form action="{{ route('transport-legs.update', $leg->id) }}" method="POST" class="inline">
                                                @csrf
                                                @method('PATCH')
                                                <input type="hidden" name="status" value="COMPLETED">
                                                <input type="hidden" name="actual_arrival" value="{{ now() }}">
                                                <button type="submit" class="text-[10px] font-bold px-2 py-0.5 rounded bg-emerald-600 text-white hover:bg-emerald-700">
                                                    Mark Completed
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </div>

                                <div class="text-xs font-medium text-slate-800 mt-2 flex items-center gap-2">
                                    <span>{{ $leg->originLocation->name }}</span>
                                    <span class="text-slate-400">&rarr;</span>
                                    <span>{{ $leg->destinationLocation->name }}</span>
                                </div>

                                <div class="mt-2 pt-2 border-t border-slate-100 grid grid-cols-2 sm:grid-cols-3 gap-2 text-[11px] text-slate-500">
                                    <div>Distance: <span class="font-bold text-slate-700">{{ number_format($leg->distance_km) }} km</span> ({{ $leg->distance_source }})</div>
                                    <div>Emissions: <span class="font-bold text-emerald-700">{{ number_format($leg->leg_estimated_co2e_kg, 1) }} kg CO₂e</span></div>
                                    <div>ETA: <span class="font-medium text-slate-700">{{ $leg->estimated_arrival ? $leg->estimated_arrival->format('M d, H:i') : 'TBD' }}</span></div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Component B: Milestone Events Timeline -->
                <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-2xs">
                    <h3 class="text-xs font-bold text-slate-900 uppercase tracking-wider mb-6 flex items-center gap-2">
                        <svg class="w-4 h-4 text-brand-sky" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        Tracking Milestones Log
                    </h3>

                    @if($shipment->trackingEvents->isEmpty())
                        <div class="p-8 text-center text-xs text-slate-400">No events logged yet.</div>
                    @else
                        <div class="relative border-l-2 border-slate-200 ml-3 space-y-6">
                            @foreach($shipment->trackingEvents as $event)
                                <div class="relative pl-6">
                                    <div class="absolute -left-[9px] top-1 w-4 h-4 rounded-full border-2 border-white 
                                        {{ $loop->first ? 'bg-brand-sky ring-4 ring-sky-100' : 'bg-slate-400' }}"></div>

                                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-1 mb-1">
                                        <div class="text-xs font-bold text-slate-900 flex items-center gap-2">
                                            <span>{{ str_replace('_', ' ', $event->event_type) }}</span>
                                            <span class="text-[11px] font-normal text-slate-500">at {{ $event->location_name }}</span>
                                        </div>
                                        <span class="text-[10px] font-mono text-slate-400">{{ $event->event_time->format('M d, Y H:i') }} UTC</span>
                                    </div>
                                    <p class="text-xs text-slate-600 leading-relaxed">{{ $event->description }}</p>
                                    @if($event->proof_notes)
                                        <div class="mt-1 text-[11px] font-mono text-slate-700 bg-slate-50 p-2 rounded border border-slate-200">
                                            Evidence: {{ $event->proof_notes }}
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>

            </div>

            <!-- Right Col: Operations Actions (Record Event, Log Exception, Confirm Delivery, Weather) -->
            <div class="space-y-6">

                <!-- Action Form 1: Record Tracking Event -->
                @if(auth()->user()->canManageShipments() && !$shipment->isDelivered())
                    <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-2xs space-y-4">
                        <h3 class="text-xs font-bold text-slate-900 uppercase tracking-wider flex items-center gap-1.5">
                            <span class="w-2 h-2 rounded-full bg-brand-sky"></span>
                            Record Tracking Event & Transition
                        </h3>

                        <form action="{{ route('tracking-events.store', $shipment->id) }}" method="POST" class="space-y-3">
                            @csrf
                            <div>
                                <label class="block text-[10px] font-bold uppercase text-slate-500 mb-1">Event Type *</label>
                                <select name="event_type" required class="w-full py-2 px-3 text-xs rounded-xl border border-slate-300 bg-white">
                                    <option value="PICKUP">PICKUP (Cargo Collected at Origin)</option>
                                    <option value="DEPARTURE">DEPARTURE (Vessel / Flight / Truck Departs)</option>
                                    <option value="WAYPOINT_CHECKPOINT">WAYPOINT_CHECKPOINT (En Route Waypoint)</option>
                                    <option value="ARRIVED_HUB">ARRIVED_HUB (Transit Port / Air Cargo Center)</option>
                                    <option value="CUSTOMS_INSPECTION">CUSTOMS_INSPECTION (Inspection Underway)</option>
                                    <option value="CUSTOMS_CLEARED">CUSTOMS_CLEARED (Import/Export Cleared)</option>
                                    <option value="ARRIVED_TERMINAL">ARRIVED_TERMINAL (Destination Terminal Discharged)</option>
                                    <option value="AWAITING_PICKUP">AWAITING_PICKUP (Ready for Consignee Collection)</option>
                                    <option value="OUT_FOR_DELIVERY">OUT_FOR_DELIVERY (Out for Final Mile Delivery)</option>
                                    <option value="HANDOVER">HANDOVER (Terminal / Warehouse Handover)</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-[10px] font-bold uppercase text-slate-500 mb-1">New Shipment Status *</label>
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
                                    <label class="block text-[10px] font-bold uppercase text-slate-500 mb-1">Timestamp *</label>
                                    <input type="datetime-local" name="event_time" required value="{{ now()->format('Y-m-d\TH:i') }}" class="w-full py-1.5 px-2 text-xs rounded-lg border border-slate-300">
                                </div>
                                <div>
                                    <label class="block text-[10px] font-bold uppercase text-slate-500 mb-1">Location Name *</label>
                                    <input type="text" name="location_name" required value="{{ $shipment->originLocation->name }}" class="w-full py-1.5 px-2 text-xs rounded-lg border border-slate-300">
                                </div>
                            </div>

                            <div>
                                <label class="block text-[10px] font-bold uppercase text-slate-500 mb-1">Event Narrative Description *</label>
                                <textarea name="description" required rows="2" class="w-full py-1.5 px-2 text-xs rounded-lg border border-slate-300" placeholder="e.g. Vessel berthed at Quay 4, container discharged onto yard..."></textarea>
                            </div>

                            <button type="submit" class="w-full py-2 rounded-xl text-xs font-bold text-white bg-brand-sky hover:bg-sky-700 transition-colors shadow-2xs">
                                Log Tracking Milestone
                            </button>
                        </form>
                    </div>
                @endif

                <!-- Action Form 2: Report Exception or Disruption -->
                @if(auth()->user()->canManageShipments() && !$shipment->isDelivered())
                    <div class="bg-white p-6 rounded-2xl border border-amber-200 bg-amber-50/10 shadow-2xs space-y-4">
                        <h3 class="text-xs font-bold text-amber-800 uppercase tracking-wider flex items-center gap-1.5">
                            <span class="w-2 h-2 rounded-full bg-amber-500"></span>
                            Report Schedule Delay / Exception
                        </h3>

                        <form action="{{ route('exceptions.store', $shipment->id) }}" method="POST" class="space-y-3">
                            @csrf
                            <div class="grid grid-cols-2 gap-2">
                                <div>
                                    <label class="block text-[10px] font-bold uppercase text-slate-500 mb-1">Category *</label>
                                    <select name="category" required class="w-full py-1.5 px-2 text-xs rounded-lg border border-slate-300 bg-white">
                                        <option value="weather_disruption">Weather Disruption</option>
                                        <option value="transport_delay">Transport Delay</option>
                                        <option value="damaged_cargo">Damaged Cargo</option>
                                        <option value="missing_documentation">Missing Documentation</option>
                                        <option value="customs_clearance_issue">Customs Hold</option>
                                        <option value="failed_delivery_attempt">Failed Delivery Attempt</option>
                                        <option value="other_operational_issue">Other Operational Issue</option>
                                    </select>
                                </div>

                                <div>
                                    <label class="block text-[10px] font-bold uppercase text-slate-500 mb-1">Severity *</label>
                                    <select name="severity" required class="w-full py-1.5 px-2 text-xs rounded-lg border border-slate-300 bg-white">
                                        <option value="low">Low</option>
                                        <option value="medium" selected>Medium</option>
                                        <option value="high">High</option>
                                        <option value="critical">Critical</option>
                                    </select>
                                </div>
                            </div>

                            <div>
                                <label class="block text-[10px] font-bold uppercase text-slate-500 mb-1">ETA Impact (Hours to add)</label>
                                <input type="number" name="impact_on_eta_hours" value="24" min="0" class="w-full py-1.5 px-2 text-xs rounded-lg border border-slate-300">
                            </div>

                            <div>
                                <label class="block text-[10px] font-bold uppercase text-slate-500 mb-1">Description *</label>
                                <textarea name="description" required rows="2" class="w-full py-1.5 px-2 text-xs rounded-lg border border-slate-300" placeholder="e.g. Gale force winds in strait forcing anchor wait..."></textarea>
                            </div>

                            <div>
                                <label class="block text-[10px] font-bold uppercase text-slate-500 mb-1">Mitigation Action Taken</label>
                                <textarea name="action_taken" rows="1" class="w-full py-1.5 px-2 text-xs rounded-lg border border-slate-300" placeholder="e.g. Rerouting via alternate highway..."></textarea>
                            </div>

                            <button type="submit" class="w-full py-2 rounded-xl text-xs font-bold text-white bg-amber-600 hover:bg-amber-700 transition-colors shadow-2xs">
                                Log Exception & Update Status
                            </button>
                        </form>
                    </div>
                @endif

                <!-- Action Form 3: Final Delivery Confirmation (Enforcing Business Rules) -->
                @if(auth()->user()->canManageShipments() && !$shipment->isDelivered())
                    <div class="bg-emerald-50 border border-emerald-300 p-6 rounded-2xl shadow-xs space-y-4">
                        <div class="flex items-center gap-2 text-emerald-800 font-bold text-xs uppercase tracking-wider">
                            <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            Confirm Final Delivery Fulfillment
                        </div>
                        <p class="text-[11px] text-emerald-900 leading-relaxed">
                            Marks consignment as completed. Requires verified recipient signature or B2B warehouse dock receipt note.
                        </p>

                        <form action="{{ route('delivery.confirm', $shipment->id) }}" method="POST" class="space-y-3">
                            @csrf
                            <div>
                                <label class="block text-[10px] font-bold uppercase text-emerald-900 mb-1">
                                    Receiver / Authorized Officer Name *
                                </label>
                                <input type="text" name="receiver_name" required value="{{ $shipment->receiver_contact_name }}"
                                       class="w-full py-1.5 px-2 text-xs rounded-lg border border-emerald-300 bg-white">
                            </div>

                            <div>
                                <label class="block text-[10px] font-bold uppercase text-emerald-900 mb-1">
                                    Receipt Evidence / Proof Notes *
                                </label>
                                <textarea name="proof_notes" required rows="2" class="w-full py-1.5 px-2 text-xs rounded-lg border border-emerald-300 bg-white"
                                          placeholder="e.g. Stamped Goods Receipt Slip #GRN-99812. All seals inspected intact."></textarea>
                            </div>

                            <button type="submit" 
                                    class="w-full py-2.5 rounded-xl text-xs font-bold text-white bg-emerald-600 hover:bg-emerald-700 transition-colors shadow-sm">
                                Verify & Fulfill Final Delivery
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
                        <div class="mt-3 pt-3 border-t border-slate-700/80 text-xs {{ $weatherData['is_adverse'] ? 'text-amber-300 font-semibold' : 'text-emerald-300' }}">
                            {{ $weatherData['disruption_risk'] }}
                        </div>
                    </div>
                @endif

            </div>
        </div>

    </div>

    <!-- Leaflet Script for Cockpit Map -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            if (typeof L === 'undefined') return;

            const mapData = @json($mapRoute);
            if (!mapData || mapData.length === 0) return;

            const map = L.map('shipmentCockpitMap', {
                scrollWheelZoom: false,
            }).setView([mapData[0].origin.lat, mapData[0].origin.lon], 4);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 18,
                attribution: '&copy; OpenStreetMap contributors'
            }).addTo(map);

            const bounds = [];

            mapData.forEach(function (leg) {
                const originCoords = [leg.origin.lat, leg.origin.lon];
                const destCoords = [leg.destination.lat, leg.destination.lon];

                bounds.push(originCoords);
                bounds.push(destCoords);

                L.circleMarker(originCoords, { radius: 6, fillColor: '#0284C7', color: '#fff', weight: 2, fillOpacity: 0.9 })
                    .bindPopup('<b>' + leg.origin.name + '</b>').addTo(map);

                L.circleMarker(destCoords, { radius: 6, fillColor: '#10B981', color: '#fff', weight: 2, fillOpacity: 0.9 })
                    .bindPopup('<b>' + leg.destination.name + '</b>').addTo(map);

                let lineColor = '#0284C7';
                let dashArray = null;

                if (leg.mode === 'air') {
                    lineColor = '#6366F1';
                    dashArray = '6, 8';
                } else if (leg.mode === 'land') {
                    lineColor = '#10B981';
                }

                L.polyline([originCoords, destCoords], { color: lineColor, weight: 3.5, opacity: 0.85, dashArray: dashArray })
                    .bindPopup('<b>Leg ' + leg.sequence + ' (' + leg.mode.toUpperCase() + ')</b><br>' + leg.carrier + ' &bull; ' + leg.distance_km + ' km')
                    .addTo(map);
            });

            if (bounds.length > 0) {
                map.fitBounds(bounds, { padding: [30, 30] });
            }
        });
    </script>
</x-app-layout>
