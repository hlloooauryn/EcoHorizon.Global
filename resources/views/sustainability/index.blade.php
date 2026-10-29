<x-app-layout title="Carbon Emissions & Sustainability Engine — EcoHorizon Global">
    <div class="space-y-8">

        <!-- Welcome Banner -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-4 border-b border-slate-200">
            <div>
                <div class="flex items-center gap-2 text-xs font-semibold uppercase tracking-wider text-emerald-700 mb-1">
                    <span class="w-2 h-2 rounded-full bg-brand-emerald animate-pulse"></span>
                    <span>GLEC Framework v3.0 &bull; IMO MEPC.308 Compliant</span>
                </div>
                <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight">Multimodal Carbon Emission Analytics</h1>
                <p class="text-sm text-slate-500 mt-1">
                    Auditable greenhouse gas footprint accounting across maritime container shipping, road logistics, and air cargo.
                </p>
            </div>
            <div>
                <a href="{{ route('home') }}#sustainability" target="_blank"
                   class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl text-xs font-bold bg-white border border-slate-300 text-slate-700 hover:bg-slate-50 shadow-2xs">
                    <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    Public Methodology
                </a>
            </div>
        </div>

        <!-- 4 Sustainability Cards -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <div class="bg-white p-5 rounded-2xl border border-emerald-200 bg-emerald-50/20 shadow-2xs">
                <span class="text-[10px] font-bold uppercase tracking-wider text-emerald-800">Total Transport CO₂e</span>
                <div class="text-2xl font-extrabold text-emerald-700 mt-1">
                    {{ number_format($totalEmissionsKg / 1000, 2) }} <span class="text-xs font-normal">tonnes</span>
                </div>
                <div class="text-xs text-slate-500 mt-0.5">{{ number_format($totalEmissionsKg, 1) }} kg CO₂e</div>
            </div>

            <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-2xs">
                <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Total Tracked Distance</span>
                <div class="text-2xl font-extrabold text-slate-900 mt-1">
                    {{ number_format($totalDistanceKm) }} <span class="text-xs font-normal">km</span>
                </div>
                <div class="text-xs text-slate-500 mt-0.5">Corridor routes</div>
            </div>

            <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-2xs">
                <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Total Freight Payload</span>
                <div class="text-2xl font-extrabold text-slate-900 mt-1">
                    {{ number_format($totalCargoTonnes, 1) }} <span class="text-xs font-normal">tonnes</span>
                </div>
                <div class="text-xs text-slate-500 mt-0.5">Industrial goods</div>
            </div>

            <div class="bg-white p-5 rounded-2xl border border-sky-200 bg-sky-50/20 shadow-2xs">
                <span class="text-[10px] font-bold uppercase tracking-wider text-sky-800">Average Freight Intensity</span>
                @php
                    $intensity = ($totalCargoTonnes > 0 && $totalDistanceKm > 0) ? ($totalEmissionsKg * 1000) / ($totalCargoTonnes * $totalDistanceKm) : 0;
                @endphp
                <div class="text-2xl font-extrabold text-brand-sky mt-1">
                    {{ number_format($intensity, 1) }} <span class="text-xs font-normal">g/t-km</span>
                </div>
                <div class="text-xs text-slate-500 mt-0.5">Fleet intensity average</div>
            </div>
        </div>

        <!-- Mode Comparison Breakdown -->
        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-2xs space-y-4">
            <h2 class="text-xs font-bold text-slate-900 uppercase tracking-wider">Transport Mode Share & Emissions</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                @foreach(['sea' => 'Maritime Shipping', 'land' => 'Overland Road & Rail', 'air' => 'Intercontinental Airfreight'] as $modeKey => $modeName)
                    @php
                        $data = $modeEmissions->get($modeKey);
                        $co2 = $data ? (float) $data->total_co2e : 0;
                        $km = $data ? (float) $data->total_km : 0;
                        $legs = $data ? (int) $data->leg_count : 0;
                    @endphp
                    <div class="p-5 rounded-xl border border-slate-200 bg-slate-50/50 space-y-3">
                        <div class="flex items-center justify-between">
                            <span class="inline-flex px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider 
                                {{ $modeKey === 'sea' ? 'bg-blue-100 text-blue-800' : ($modeKey === 'air' ? 'bg-indigo-100 text-indigo-800' : 'bg-emerald-100 text-emerald-800') }}">
                                {{ $modeKey }}
                            </span>
                            <span class="text-xs text-slate-500">{{ $legs }} Legs</span>
                        </div>
                        <div>
                            <div class="text-sm font-bold text-slate-900">{{ $modeName }}</div>
                            <div class="text-xl font-extrabold text-emerald-600 mt-1">{{ number_format($co2, 1) }} <span class="text-xs font-normal text-slate-500">kg CO₂e</span></div>
                        </div>
                        <div class="text-xs text-slate-500 pt-2 border-t border-slate-200 flex justify-between">
                            <span>Distance:</span>
                            <span class="font-medium text-slate-800">{{ number_format($km) }} km</span>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Documented Formula & Factor Reference -->
        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-2xs space-y-4">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-xs font-bold text-slate-900 uppercase tracking-wider">Active Emission Factor Catalogs</h3>
                    <p class="text-xs text-slate-500 mt-0.5">Parameters stored and utilized for each modal segment.</p>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs border-collapse">
                    <thead>
                        <tr class="bg-slate-50 text-[10px] font-bold uppercase text-slate-500 border-b border-slate-200">
                            <th class="py-2.5 px-3">Mode</th>
                            <th class="py-2.5 px-3">Subcategory Specification</th>
                            <th class="py-2.5 px-3">Factor (g CO₂e / t-km)</th>
                            <th class="py-2.5 px-3">Standard Reference Source</th>
                            <th class="py-2.5 px-3">Default</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($activeFactors as $f)
                            <tr>
                                <td class="py-2.5 px-3 font-bold uppercase text-slate-700">{{ $f->transport_mode }}</td>
                                <td class="py-2.5 px-3 font-medium text-slate-900">{{ $f->subcategory }}</td>
                                <td class="py-2.5 px-3 font-mono font-bold text-emerald-700">{{ number_format($f->factor_g_per_tonne_km, 2) }}</td>
                                <td class="py-2.5 px-3 text-slate-500">{{ $f->source_reference }}</td>
                                <td class="py-2.5 px-3">
                                    @if($f->is_default)
                                        <span class="px-1.5 py-0.5 rounded text-[10px] font-bold bg-emerald-100 text-emerald-800">Default</span>
                                    @else
                                        <span class="text-slate-400 text-[10px]">Alternative</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Consignments Emissions Table -->
        <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden shadow-2xs">
            <div class="px-6 py-4 border-b border-slate-200 bg-slate-50 flex items-center justify-between">
                <h3 class="text-xs font-bold text-slate-900 uppercase tracking-wider">Consignment Carbon Footprint Registry</h3>
                <span class="text-xs text-slate-500">Sorted by Highest Carbon Footprint</span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs border-collapse">
                    <thead>
                        <tr class="bg-slate-50/80 text-[10px] font-bold uppercase text-slate-500 border-b border-slate-200">
                            <th class="py-3 px-4">Tracking Code</th>
                            <th class="py-3 px-4">Commodity Payload</th>
                            <th class="py-3 px-4">Distance (km)</th>
                            <th class="py-3 px-4">Legs</th>
                            <th class="py-3 px-4">Total Estimated CO₂e</th>
                            <th class="py-3 px-4 text-right">Details</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($shipments as $s)
                            <tr class="hover:bg-slate-50/80 transition-colors">
                                <td class="py-3 px-4 font-mono font-bold text-brand-sky">
                                    <a href="{{ route('shipments.show', $s->id) }}" class="hover:underline">{{ $s->tracking_number }}</a>
                                </td>
                                <td class="py-3 px-4">
                                    <div class="font-medium text-slate-800">{{ $s->cargo_description }}</div>
                                    <div class="text-[10px] text-slate-400">{{ number_format($s->cargo_weight_tonnes, 2) }} tonnes</div>
                                </td>
                                <td class="py-3 px-4 font-mono text-slate-700">{{ number_format($s->total_distance_km) }} km</td>
                                <td class="py-3 px-4">
                                    <div class="flex gap-1">
                                        @foreach($s->transportLegs as $leg)
                                            <span class="px-1.5 py-0.5 rounded text-[9px] font-bold uppercase 
                                                {{ $leg->transport_mode === 'sea' ? 'bg-blue-100 text-blue-800' : ($leg->transport_mode === 'air' ? 'bg-indigo-100 text-indigo-800' : 'bg-emerald-100 text-emerald-800') }}">
                                                {{ substr($leg->transport_mode, 0, 1) }}
                                            </span>
                                        @endforeach
                                    </div>
                                </td>
                                <td class="py-3 px-4 font-mono font-bold text-emerald-700">
                                    {{ number_format($s->total_estimated_co2e_kg, 1) }} kg
                                </td>
                                <td class="py-3 px-4 text-right">
                                    <a href="{{ route('shipments.show', $s->id) }}" class="text-xs text-brand-sky font-semibold hover:underline">
                                        View Breakdown &rarr;
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="py-8 text-center text-xs text-slate-400">No consignment emissions calculated yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($shipments->hasPages())
                <div class="px-6 py-3 border-t border-slate-200">
                    {{ $shipments->links() }}
                </div>
            @endif
        </div>

    </div>
</x-app-layout>

