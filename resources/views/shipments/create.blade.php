<x-app-layout title="Dispatch New Multimodal Shipment — EcoHorizon Global">
    <div class="max-w-5xl mx-auto space-y-8">
        
        <!-- Header -->
        <div class="pb-4 border-b border-slate-200">
            <div class="flex items-center gap-2 text-xs font-semibold uppercase tracking-wider text-slate-500 mb-1">
                <a href="{{ route('shipments.index') }}" class="hover:text-brand-sky">Shipment Registry</a>
                <span>/</span>
                <span class="text-brand-sky">New Shipment Wizard</span>
            </div>
            <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight">Dispatch Multimodal Freight</h1>
            <p class="text-sm text-slate-500 mt-1">
                Configure origin, destination, service level agreement, cargo specifications, and initial carrier leg.
            </p>
        </div>

        <form action="{{ route('shipments.store') }}" method="POST" class="space-y-8">
            @csrf

            <!-- SECTION 1: SERVICE LEVEL & DESTINATION CONFIGURATION -->
            <div class="bg-white p-6 sm:p-8 rounded-2xl border border-slate-200 shadow-2xs space-y-6">
                <div class="border-b border-slate-100 pb-4">
                    <h2 class="text-base font-bold text-slate-900 flex items-center gap-2">
                        <span class="w-6 h-6 rounded-full bg-brand-sky text-white text-xs flex items-center justify-center font-mono">1</span>
                        Service Level & Destination Agreement
                    </h2>
                    <p class="text-xs text-slate-500 mt-1">
                        Select contractual transport arrangement and destination facility type.
                    </p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Service Level -->
                    <div>
                        <label for="service_level_id" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                            Agreed Service Level <span class="text-red-500">*</span>
                        </label>
                        <select id="service_level_id" name="service_level_id" required 
                                class="w-full py-2.5 px-3.5 text-xs rounded-xl border border-slate-300 bg-white focus:outline-none focus:ring-2 focus:ring-brand-sky">
                            @foreach($serviceLevels as $sl)
                                <option value="{{ $sl->id }}" {{ old('service_level_id') == $sl->id ? 'selected' : '' }}>
                                    {{ $sl->name }} ({{ $sl->code }})
                                </option>
                            @endforeach
                        </select>
                        <p class="text-[11px] text-slate-500 mt-1.5 leading-relaxed">
                            <strong>Note:</strong> Door-to-Port & Port-to-Port require terminal collection and are NOT marked delivered simply upon terminal arrival.
                        </p>
                    </div>

                    <!-- Destination Facility Type -->
                    <div>
                        <label for="destination_type" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                            Destination Type <span class="text-red-500">*</span>
                        </label>
                        <select id="destination_type" name="destination_type" required 
                                class="w-full py-2.5 px-3.5 text-xs rounded-xl border border-slate-300 bg-white focus:outline-none focus:ring-2 focus:ring-brand-sky">
                            <option value="door_address" {{ old('destination_type') === 'door_address' ? 'selected' : '' }}>Door Address / Consignee Premises</option>
                            <option value="seaport_terminal" {{ old('destination_type') === 'seaport_terminal' ? 'selected' : '' }}>Seaport Container Terminal</option>
                            <option value="airport_terminal" {{ old('destination_type') === 'airport_terminal' ? 'selected' : '' }}>Airport Cargo Terminal</option>
                            <option value="business_warehouse" {{ old('destination_type') === 'business_warehouse' ? 'selected' : '' }}>Business Warehouse (B2B)</option>
                            <option value="fulfillment_center" {{ old('destination_type') === 'fulfillment_center' ? 'selected' : '' }}>Fulfillment Center / Distribution Hub</option>
                        </select>
                        <p class="text-[11px] text-slate-500 mt-1.5">
                            Specifies physical unloading protocol and required handover documentation.
                        </p>
                    </div>
                </div>

                <!-- Locations Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pt-4 border-t border-slate-100">
                    <div>
                        <label for="origin_location_id" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                            Origin Facility / Port <span class="text-red-500">*</span>
                        </label>
                        <select id="origin_location_id" name="origin_location_id" required 
                                class="w-full py-2.5 px-3.5 text-xs rounded-xl border border-slate-300 bg-white focus:outline-none focus:ring-2 focus:ring-brand-sky">
                            @foreach($locations as $loc)
                                <option value="{{ $loc->id }}" {{ old('origin_location_id') == $loc->id ? 'selected' : '' }}>
                                    {{ $loc->name }} ({{ $loc->city }}, {{ $loc->country }}) - {{ strtoupper($loc->type) }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="destination_location_id" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                            Destination Facility / Port <span class="text-red-500">*</span>
                        </label>
                        <select id="destination_location_id" name="destination_location_id" required 
                                class="w-full py-2.5 px-3.5 text-xs rounded-xl border border-slate-300 bg-white focus:outline-none focus:ring-2 focus:ring-brand-sky">
                            @foreach($locations as $loc)
                                <option value="{{ $loc->id }}" {{ old('destination_location_id') == $loc->id ? 'selected' : '' }}>
                                    {{ $loc->name }} ({{ $loc->city }}, {{ $loc->country }}) - {{ strtoupper($loc->type) }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- Stakeholder User Assignment -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pt-4 border-t border-slate-100">
                    <div>
                        <label for="customer_user_id" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                            Assign to Customer Account (Optional)
                        </label>
                        <select id="customer_user_id" name="customer_user_id" 
                                class="w-full py-2.5 px-3.5 text-xs rounded-xl border border-slate-300 bg-white focus:outline-none focus:ring-2 focus:ring-brand-sky">
                            <option value="">-- Guest / Unassigned Customer --</option>
                            @foreach($customers as $c)
                                <option value="{{ $c->id }}" {{ old('customer_user_id') == $c->id ? 'selected' : '' }}>
                                    {{ $c->name }} ({{ $c->company_name ?? $c->email }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="assigned_operator_id" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                            Assigned Dispatch Operator
                        </label>
                        <select id="assigned_operator_id" name="assigned_operator_id" 
                                class="w-full py-2.5 px-3.5 text-xs rounded-xl border border-slate-300 bg-white focus:outline-none focus:ring-2 focus:ring-brand-sky">
                            @foreach($operators as $op)
                                <option value="{{ $op->id }}" {{ old('assigned_operator_id', auth()->id()) == $op->id ? 'selected' : '' }}>
                                    {{ $op->name }} ({{ $op->email }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <!-- SECTION 2: SHIPPER & CONSIGNEE DETAILS -->
            <div class="bg-white p-6 sm:p-8 rounded-2xl border border-slate-200 shadow-2xs space-y-6">
                <div class="border-b border-slate-100 pb-4">
                    <h2 class="text-base font-bold text-slate-900 flex items-center gap-2">
                        <span class="w-6 h-6 rounded-full bg-brand-sky text-white text-xs flex items-center justify-center font-mono">2</span>
                        Consignor (Shipper) & Consignee (Receiver)
                    </h2>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <!-- Sender -->
                    <div class="space-y-4">
                        <h3 class="text-xs font-bold uppercase tracking-wider text-brand-sky border-b border-slate-100 pb-2">Origin Shipper</h3>
                        <div>
                            <label class="block text-xs font-medium text-slate-700 mb-1">Company / Business Name *</label>
                            <input type="text" name="sender_business_name" required value="{{ old('sender_business_name', 'PT Nusantara Global Export') }}"
                                   class="w-full px-3 py-2 text-xs rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-brand-sky">
                        </div>
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-xs font-medium text-slate-700 mb-1">Contact Officer *</label>
                                <input type="text" name="sender_contact_name" required value="{{ old('sender_contact_name', 'Budi Pratama') }}"
                                       class="w-full px-3 py-2 text-xs rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-brand-sky">
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-slate-700 mb-1">Phone Number *</label>
                                <input type="text" name="sender_phone" required value="{{ old('sender_phone', '+62 812 3456 7890') }}"
                                       class="w-full px-3 py-2 text-xs rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-brand-sky">
                            </div>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-slate-700 mb-1">Physical Pickup Address *</label>
                            <textarea name="sender_address" required rows="2" class="w-full px-3 py-2 text-xs rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-brand-sky">{{ old('sender_address', 'Kawasan Industri GIIC Blok AA No. 5, Cikarang, Jawa Barat, Indonesia') }}</textarea>
                        </div>
                    </div>

                    <!-- Receiver -->
                    <div class="space-y-4">
                        <h3 class="text-xs font-bold uppercase tracking-wider text-brand-emerald border-b border-slate-100 pb-2">Destination Consignee</h3>
                        <div>
                            <label class="block text-xs font-medium text-slate-700 mb-1">Receiving Business Name *</label>
                            <input type="text" name="receiver_business_name" required value="{{ old('receiver_business_name', 'Bavaria Automotive GmbH') }}"
                                   class="w-full px-3 py-2 text-xs rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-brand-sky">
                        </div>
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-xs font-medium text-slate-700 mb-1">Receiving Contact *</label>
                                <input type="text" name="receiver_contact_name" required value="{{ old('receiver_contact_name', 'Klaus Schneider') }}"
                                       class="w-full px-3 py-2 text-xs rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-brand-sky">
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-slate-700 mb-1">Receiving Phone *</label>
                                <input type="text" name="receiver_phone" required value="{{ old('receiver_phone', '+49 89 2345 6789') }}"
                                       class="w-full px-3 py-2 text-xs rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-brand-sky">
                            </div>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-slate-700 mb-1">Delivery / Handover Address *</label>
                            <textarea name="receiver_address" required rows="2" class="w-full px-3 py-2 text-xs rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-brand-sky">{{ old('receiver_address', 'Gewerbepark Nord 14, 80331 Munich, Germany') }}</textarea>
                        </div>
                    </div>
                </div>
            </div>

            <!-- SECTION 3: CARGO MANIFEST & WEIGHT (For GLEC Carbon Accounting) -->
            <div class="bg-white p-6 sm:p-8 rounded-2xl border border-slate-200 shadow-2xs space-y-6">
                <div class="border-b border-slate-100 pb-4">
                    <h2 class="text-base font-bold text-slate-900 flex items-center gap-2">
                        <span class="w-6 h-6 rounded-full bg-brand-sky text-white text-xs flex items-center justify-center font-mono">3</span>
                        Cargo Specifications & Carbon Weight Base
                    </h2>
                    <p class="text-xs text-slate-500 mt-1">
                        Accurate cargo weight is mandatory for verified GLEC greenhouse gas emission calculations.
                    </p>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                    <div class="sm:col-span-2">
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Commodity Description *</label>
                        <input type="text" name="cargo_description" required value="{{ old('cargo_description', 'High-Precision Electronic Sensors & Controllers') }}"
                               class="w-full px-3 py-2.5 text-xs rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-brand-sky"
                               placeholder="e.g. Lithium-Ion Battery Packs, Solar PV Modules">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-emerald-800 uppercase tracking-wider mb-2">
                            Weight in Tonnes (t) <span class="text-red-500">*</span>
                        </label>
                        <input type="number" step="0.001" name="cargo_weight_tonnes" required value="{{ old('cargo_weight_tonnes', '14.500') }}"
                               class="w-full px-3 py-2.5 text-xs rounded-xl border border-emerald-300 bg-emerald-50/20 font-bold text-emerald-900 focus:outline-none focus:ring-2 focus:ring-brand-emerald"
                               placeholder="e.g. 14.500">
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 pt-4 border-t border-slate-100">
                    <div>
                        <label class="block text-xs font-medium text-slate-700 mb-1">Volume (CBM)</label>
                        <input type="number" step="0.01" name="cargo_volume_cbm" value="{{ old('cargo_volume_cbm', '32.00') }}"
                               class="w-full px-3 py-2 text-xs rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-brand-sky">
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-slate-700 mb-1">Package Count & Type *</label>
                        <div class="flex gap-2">
                            <input type="number" name="package_count" required value="{{ old('package_count', '20') }}"
                                   class="w-20 px-3 py-2 text-xs rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-brand-sky">
                            <input type="text" name="package_type" required value="{{ old('package_type', 'Standard Pallets') }}"
                                   class="flex-1 px-3 py-2 text-xs rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-brand-sky">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-slate-700 mb-1">Target Estimated Arrival (ETA)</label>
                        <input type="datetime-local" name="eta" value="{{ old('eta') }}"
                               class="w-full px-3 py-2 text-xs rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-brand-sky">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-medium text-slate-700 mb-1">Handling Notes / Special Instructions</label>
                    <textarea name="special_instructions" rows="2" class="w-full px-3 py-2 text-xs rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-brand-sky"
                              placeholder="e.g. Fragile optical equipment. Maintain temperature 18C - 24C.">{{ old('special_instructions') }}</textarea>
                </div>
            </div>

            <!-- SECTION 4: INITIAL TRANSPORT LEG (LEG 1) -->
            <div class="bg-white p-6 sm:p-8 rounded-2xl border border-slate-200 shadow-2xs space-y-6">
                <div class="border-b border-slate-100 pb-4">
                    <h2 class="text-base font-bold text-slate-900 flex items-center gap-2">
                        <span class="w-6 h-6 rounded-full bg-brand-sky text-white text-xs flex items-center justify-center font-mono">4</span>
                        Initial Transport Leg Dispatch (Leg #1)
                    </h2>
                    <p class="text-xs text-slate-500 mt-1">
                        Dispatch the first carrier segment. You can chain additional multimodal legs (sea, air, road) on the shipment cockpit after creation.
                    </p>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Transport Mode *</label>
                        <select name="initial_leg_mode" required class="w-full py-2.5 px-3.5 text-xs rounded-xl border border-slate-300 bg-white focus:outline-none focus:ring-2 focus:ring-brand-sky">
                            <option value="sea" {{ old('initial_leg_mode') === 'sea' ? 'selected' : '' }}>Sea Freight (Container Vessel)</option>
                            <option value="land" {{ old('initial_leg_mode', 'land') === 'land' ? 'selected' : '' }}>Land Freight (Truck / Rail)</option>
                            <option value="air" {{ old('initial_leg_mode') === 'air' ? 'selected' : '' }}>Air Freight (Cargo Aircraft)</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Carrier Name *</label>
                        <input type="text" name="initial_carrier_name" required value="{{ old('initial_carrier_name', 'Nusantara Heavy Freight Logistics') }}"
                               class="w-full px-3 py-2.5 text-xs rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-brand-sky"
                               placeholder="e.g. Samudera Shipping, Maersk, ANA Cargo">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Transport Identifier Reference</label>
                        <input type="text" name="initial_transport_reference" value="{{ old('initial_transport_reference', 'Truck Plate B 9182 UXT') }}"
                               class="w-full px-3 py-2.5 text-xs rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-brand-sky"
                               placeholder="e.g. Vessel MV Sinar Voy 12, Flight LH8402">
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 pt-4 border-t border-slate-100">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Distance in Kilometers (Optional Override)</label>
                        <input type="number" step="0.1" name="initial_distance_km" value="{{ old('initial_distance_km') }}"
                               class="w-full px-3 py-2 text-xs rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-brand-sky"
                               placeholder="Leave blank to auto-calculate Haversine Great-Circle distance">
                        <span class="text-[11px] text-slate-400 mt-1 block">
                            If empty, the system automatically computes distance between coordinates using realistic corridor curvature.
                        </span>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Emission Factor</label>
                        <select name="initial_emission_factor_id" class="w-full py-2 px-3 text-xs rounded-xl border border-slate-300 bg-white focus:outline-none focus:ring-2 focus:ring-brand-sky">
                            <option value="">-- Auto-Assign Default by Mode --</option>
                            @foreach($emissionFactors as $ef)
                                <option value="{{ $ef->id }}" {{ old('initial_emission_factor_id') == $ef->id ? 'selected' : '' }}>
                                    [{{ strtoupper($ef->transport_mode) }}] {{ $ef->subcategory }} ({{ $ef->factor_g_per_tonne_km }} g/t-km)
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <!-- Submit Button Bar -->
            <div class="flex items-center justify-end gap-4 pt-4">
                <a href="{{ route('shipments.index') }}" class="px-5 py-2.5 rounded-xl text-xs font-bold bg-white border border-slate-300 text-slate-700 hover:bg-slate-50 transition-colors">
                    Cancel
                </a>
                <button type="submit" 
                        class="px-6 py-2.5 rounded-xl text-xs font-bold text-white bg-gradient-to-r from-brand-sky via-brand-teal to-brand-emerald hover:opacity-95 shadow-md shadow-sky-600/20 transition-all flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    Dispatch Shipment & Generate Tracking
                </button>
            </div>
        </form>

    </div>
</x-app-layout>

