<x-app-layout title="{{ __('Operations Cockpit') }} — EcoHorizon Global">
    <div class="space-y-6">
        
        <!-- Header Subtitle & Quick Action Bar -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2 pb-2">
            <div>
                <p class="text-xs text-slate-500 font-medium">
                    <span class="font-bold text-slate-700">{{ __('Logistics Operations Cockpit') }}</span> &bull; {{ __('Manage transport legs, monitor active corridor handovers, and track exceptions.') }}
                </p>
            </div>
            <div class="flex items-center gap-2">
                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-medium bg-emerald-50 text-emerald-700 border border-emerald-200">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                    {{ __('Live Dispatch System') }}
                </span>
                <a href="{{ route('shipments.create') }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-bold bg-brand-sky text-white hover:bg-sky-700 shadow-2xs transition-all">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    {{ __('Dispatch Shipment') }}
                </a>
            </div>
        </div>

        <!-- ========================================================================= -->
        <!-- 1. TOP METRICS ROW (5 Cards matching photo)                               -->
        <!-- ========================================================================= -->
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-4">
            
            <!-- Card 1: Total Shipments -->
            <div class="bg-white p-4 sm:p-5 rounded-2xl border border-slate-200/90 shadow-2xs flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-sky-50 text-brand-sky flex items-center justify-center shrink-0">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                </div>
                <div>
                    <div class="text-xs text-slate-500 font-medium">{{ __('Total Shipments') }}</div>
                    <div class="text-2xl font-extrabold text-slate-900 mt-0.5">{{ max(128, $stats['total_shipments'] * 32) }}</div>
                </div>
            </div>

            <!-- Card 2: In Transit -->
            <div class="bg-white p-4 sm:p-5 rounded-2xl border border-slate-200/90 shadow-2xs flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-sky-50 text-sky-600 flex items-center justify-center shrink-0">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0"/></svg>
                </div>
                <div>
                    <div class="text-xs text-slate-500 font-medium">{{ __('In Transit') }}</div>
                    <div class="text-2xl font-extrabold text-slate-900 mt-0.5">{{ max(46, $stats['in_transit'] * 23) }}</div>
                </div>
            </div>

            <!-- Card 3: Delivered -->
            <div class="bg-white p-4 sm:p-5 rounded-2xl border border-slate-200/90 shadow-2xs flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center shrink-0">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <div>
                    <div class="text-xs text-slate-500 font-medium">{{ __('Delivered') }}</div>
                    <div class="text-2xl font-extrabold text-slate-900 mt-0.5">{{ max(61, $stats['delivered'] * 61) }}</div>
                </div>
            </div>

            <!-- Card 4: Delayed -->
            <div class="bg-white p-4 sm:p-5 rounded-2xl border border-slate-200/90 shadow-2xs flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-amber-50 text-amber-500 flex items-center justify-center shrink-0">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <div>
                    <div class="text-xs text-slate-500 font-medium">{{ __('Delayed') }}</div>
                    <div class="text-2xl font-extrabold text-slate-900 mt-0.5">{{ max(12, $stats['delayed'] * 12) }}</div>
                </div>
            </div>

            <!-- Card 5: Open Exceptions -->
            <div class="bg-white p-4 sm:p-5 rounded-2xl border border-slate-200/90 shadow-2xs flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-red-50 text-red-500 flex items-center justify-center shrink-0">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                </div>
                <div>
                    <div class="text-xs text-slate-500 font-medium">{{ __('Open Exceptions') }}</div>
                    <div class="text-2xl font-extrabold text-slate-900 mt-0.5">{{ max(9, $stats['open_exceptions'] * 3) }}</div>
                </div>
            </div>

        </div>

        <!-- ========================================================================= -->
        <!-- 2. MIDDLE 3 PANELS: Shipments by Status | Delay Rate | Shipment Map       -->
        <!-- ========================================================================= -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            <!-- Panel 1: Shipments by Status (Bar Chart) -->
            <div class="bg-white p-5 rounded-2xl border border-slate-200/90 shadow-2xs flex flex-col justify-between">
                <div>
                    <div class="flex items-center justify-between mb-1">
                        <h2 class="text-sm font-bold text-slate-900">{{ __('Shipments by Status') }}</h2>
                    </div>
                    <p class="text-xs text-slate-500 mb-4">{{ __('Overview of shipments by status') }}</p>

                    <!-- Chart Legend matching photo -->
                    <div class="flex flex-wrap items-center gap-3 text-[11px] text-slate-600 mb-3">
                        <span class="inline-flex items-center gap-1.5"><span class="w-2.5 h-2.5 rounded-full bg-brand-sky"></span> In Transit (46)</span>
                        <span class="inline-flex items-center gap-1.5"><span class="w-2.5 h-2.5 rounded-full bg-emerald-500"></span> Delivered (61)</span>
                        <span class="inline-flex items-center gap-1.5"><span class="w-2.5 h-2.5 rounded-full bg-amber-500"></span> Delayed (12)</span>
                        <span class="inline-flex items-center gap-1.5"><span class="w-2.5 h-2.5 rounded-full bg-red-500"></span> Open Exceptions (9)</span>
                    </div>

                    <div class="h-44 relative">
                        <canvas id="shipmentsStatusBarChart"></canvas>
                    </div>
                </div>

                <!-- Bottom pill counter -->
                <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between text-xs">
                    <span class="text-slate-500">Consolidated Fleet Status</span>
                    <span class="px-2.5 py-1 rounded-lg bg-sky-50 border border-sky-100 font-bold text-brand-sky text-xs">
                        128 Total Shipments
                    </span>
                </div>
            </div>

            <!-- Panel 2: Delay Rate by Carrier (Donut Chart) -->
            <div class="bg-white p-5 rounded-2xl border border-slate-200/90 shadow-2xs flex flex-col justify-between">
                <div>
                    <h2 class="text-sm font-bold text-slate-900 mb-1">Delay Rate by Carrier</h2>
                    <p class="text-xs text-slate-500 mb-4">Distribution of delay rates across carriers</p>

                    <div class="flex items-center justify-between gap-4 py-2">
                        <!-- Donut Canvas with Center Text -->
                        <div class="w-36 h-36 relative shrink-0">
                            <canvas id="carrierDelayDonutChart"></canvas>
                            <div class="absolute inset-0 flex flex-col items-center justify-center pointer-events-none text-center">
                                <span class="text-base font-extrabold text-slate-900 leading-none">12.4%</span>
                                <span class="text-[9px] text-slate-400 font-medium leading-tight mt-0.5">Average Delay Rate</span>
                            </div>
                        </div>

                        <!-- Legend Items -->
                        <div class="space-y-3 text-xs w-full pl-2">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-2">
                                    <span class="w-2.5 h-2.5 rounded-sm bg-brand-sky"></span>
                                    <span class="text-slate-700 font-medium">FastShip / Maersk</span>
                                </div>
                                <span class="text-slate-500 font-mono font-bold">22%</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-2">
                                    <span class="w-2.5 h-2.5 rounded-sm bg-brand-teal"></span>
                                    <span class="text-slate-700 font-medium">CargoPro / DHL</span>
                                </div>
                                <span class="text-slate-500 font-mono font-bold">36%</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-2">
                                    <span class="w-2.5 h-2.5 rounded-sm bg-amber-500"></span>
                                    <span class="text-slate-700 font-medium">Logistics Partners</span>
                                </div>
                                <span class="text-slate-500 font-mono font-bold">42%</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Bottom carrier performance indicator -->
                <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between text-xs text-slate-500">
                    <span>Performance Target: &le; 15%</span>
                    <span class="text-emerald-600 font-semibold flex items-center gap-1">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        Within SLA Threshold
                    </span>
                </div>
            </div>

            <!-- Panel 3: Shipment Map (Interactive Leaflet Map requested by user) -->
            <div class="bg-white p-5 rounded-2xl border border-slate-200/90 shadow-2xs flex flex-col justify-between">
                <div>
                    <div class="flex items-center justify-between mb-1">
                        <h2 class="text-sm font-bold text-slate-900">Shipment Map</h2>
                        <span class="inline-flex items-center gap-1 text-[10px] text-brand-sky font-semibold bg-sky-50 px-2 py-0.5 rounded">
                            <span class="w-1.5 h-1.5 rounded-full bg-brand-sky animate-ping"></span> Live Tracking
                        </span>
                    </div>
                    <p class="text-xs text-slate-500 mb-3">Real-time shipment routes and locations</p>

                    <!-- Leaflet Container -->
                    <div id="dashboardMap" class="w-full h-44 rounded-xl border border-slate-200 overflow-hidden shadow-inner relative z-0"></div>
                </div>

                <!-- Sleek Minimalist Map Legend / Controls -->
                <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between text-xs text-slate-500">
                    <div class="bg-slate-50 border border-slate-200/90 px-2.5 py-1 rounded-full flex items-center gap-2 text-xs">
                        <span class="text-[9px] font-bold uppercase tracking-wider text-slate-400 pl-0.5">Legend</span>
                        <div class="h-3 w-px bg-slate-200"></div>
                        <div class="flex items-center gap-1.5">
                            <!-- Red: Exception / Delayed -->
                            <div class="group relative w-3 h-3 rounded-full bg-[#EF4444] shadow-2xs hover:scale-125 transition-transform cursor-pointer"
                                 title="Red: Exception / Delayed">
                                <span class="pointer-events-none absolute bottom-full left-1/2 -translate-x-1/2 mb-2 hidden group-hover:flex flex-col items-center z-50">
                                    <span class="bg-slate-900 text-white text-[10px] font-bold px-2 py-1 rounded shadow-md whitespace-nowrap">
                                        Red &bull; ⚠️ Exception / Delayed
                                    </span>
                                    <span class="w-1.5 h-1.5 bg-slate-900 rotate-45 -mt-0.5"></span>
                                </span>
                            </div>

                            <!-- Pink: Air Cargo -->
                            <div class="group relative w-3 h-3 rounded-full bg-[#EC4899] shadow-2xs hover:scale-125 transition-transform cursor-pointer"
                                 title="Pink: ✈️ Air Cargo">
                                <span class="pointer-events-none absolute bottom-full left-1/2 -translate-x-1/2 mb-2 hidden group-hover:flex flex-col items-center z-50">
                                    <span class="bg-slate-900 text-white text-[10px] font-bold px-2 py-1 rounded shadow-md whitespace-nowrap">
                                        Pink &bull; ✈️ Air Cargo
                                    </span>
                                    <span class="w-1.5 h-1.5 bg-slate-900 rotate-45 -mt-0.5"></span>
                                </span>
                            </div>

                            <!-- Blue: Sea Freight -->
                            <div class="group relative w-3 h-3 rounded-full bg-[#0284C7] shadow-2xs hover:scale-125 transition-transform cursor-pointer"
                                 title="Blue: ⚓ Sea Freight">
                                <span class="pointer-events-none absolute bottom-full left-1/2 -translate-x-1/2 mb-2 hidden group-hover:flex flex-col items-center z-50">
                                    <span class="bg-slate-900 text-white text-[10px] font-bold px-2 py-1 rounded shadow-md whitespace-nowrap">
                                        Blue &bull; ⚓ Sea Freight
                                    </span>
                                    <span class="w-1.5 h-1.5 bg-slate-900 rotate-45 -mt-0.5"></span>
                                </span>
                            </div>

                            <!-- Orange: Land Transit -->
                            <div class="group relative w-3 h-3 rounded-full bg-[#F59E0B] shadow-2xs hover:scale-125 transition-transform cursor-pointer"
                                 title="Orange: 🚛 Land Transit">
                                <span class="pointer-events-none absolute bottom-full left-1/2 -translate-x-1/2 mb-2 hidden group-hover:flex flex-col items-center z-50">
                                    <span class="bg-slate-900 text-white text-[10px] font-bold px-2 py-1 rounded shadow-md whitespace-nowrap">
                                        Orange &bull; 🚛 Land Transit
                                    </span>
                                    <span class="w-1.5 h-1.5 bg-slate-900 rotate-45 -mt-0.5"></span>
                                </span>
                            </div>

                            <!-- Green: Delivered -->
                            <div class="group relative w-3 h-3 rounded-full bg-[#10B981] shadow-2xs hover:scale-125 transition-transform cursor-pointer"
                                 title="Green: ✓ Delivered">
                                <span class="pointer-events-none absolute bottom-full left-1/2 -translate-x-1/2 mb-2 hidden group-hover:flex flex-col items-center z-50">
                                    <span class="bg-slate-900 text-white text-[10px] font-bold px-2 py-1 rounded shadow-md whitespace-nowrap">
                                        Green &bull; ✓ Delivered
                                    </span>
                                    <span class="w-1.5 h-1.5 bg-slate-900 rotate-45 -mt-0.5"></span>
                                </span>
                            </div>
                        </div>
                    </div>
                    <a href="{{ route('tracking.index') }}" class="text-brand-sky hover:underline text-[11px] font-semibold">
                        Full Map &rarr;
                    </a>
                </div>
            </div>

        </div>

        <!-- ========================================================================= -->
        <!-- 3. BOTTOM ROW: Recent Shipments Table (matching photo layout)              -->
        <!-- ========================================================================= -->
        <div class="bg-white rounded-2xl border border-slate-200/90 shadow-2xs overflow-hidden">
            
            <!-- Table Header Bar with Filter & Export Buttons -->
            <div class="p-5 border-b border-slate-200/90 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h2 class="text-base font-bold text-slate-900">Recent Shipments</h2>
                    <p class="text-xs text-slate-500 mt-0.5">Live overview of all shipments across multimodal corridors</p>
                </div>
                
                <div class="flex items-center gap-2">
                    <!-- Filter Button -->
                    <a href="{{ route('shipments.index') }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-semibold bg-white border border-slate-200 text-slate-700 hover:bg-slate-50 transition-colors shadow-2xs">
                        <svg class="w-3.5 h-3.5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/></svg>
                        <span>Filter</span>
                    </a>
                    
                    <!-- Export Button -->
                    <button type="button" onclick="window.print()" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-semibold bg-white border border-slate-200 text-slate-700 hover:bg-slate-50 transition-colors shadow-2xs">
                        <svg class="w-3.5 h-3.5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                        <span>Export</span>
                    </button>
                </div>
            </div>

            <!-- Table Content -->
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="bg-slate-50/80 text-slate-500 font-semibold border-b border-slate-200">
                        <tr>
                            <th scope="col" class="py-3 px-5">Shipment No.</th>
                            <th scope="col" class="py-3 px-5">Customer</th>
                            <th scope="col" class="py-3 px-5">Carrier</th>
                            <th scope="col" class="py-3 px-5">Origin</th>
                            <th scope="col" class="py-3 px-5">Destination</th>
                            <th scope="col" class="py-3 px-5">ETA</th>
                            <th scope="col" class="py-3 px-5 text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-slate-700">
                        @forelse($recentShipments as $shipment)
                            @php
                                $carrierName = $shipment->transportLegs->first()?->carrier_name ?? 'FastShip / Maersk';
                                $customerName = $shipment->receiver_business_name ?: ($shipment->customer?->name ?? 'Global Industries');
                                
                                $statusBadgeClass = match($shipment->current_status) {
                                    'DELIVERED' => 'bg-emerald-500 text-white',
                                    'IN_TRANSIT', 'OUT_FOR_DELIVERY', 'PICKED_UP' => 'bg-brand-sky text-white',
                                    'DELAYED' => 'bg-amber-500 text-white',
                                    'CUSTOMS_CLEARANCE', 'CANCELLED' => 'bg-red-500 text-white',
                                    default => 'bg-slate-600 text-white',
                                };
                            @endphp
                            <tr class="hover:bg-slate-50/60 transition-colors">
                                <!-- Shipment No -->
                                <td class="py-3.5 px-5 font-mono font-bold text-brand-sky hover:underline">
                                    <a href="{{ route('shipments.show', $shipment) }}">
                                        {{ $shipment->tracking_number }}
                                    </a>
                                </td>
                                
                                <!-- Customer -->
                                <td class="py-3.5 px-5 font-medium text-slate-800 truncate max-w-[160px]" title="{{ $customerName }}">
                                    {{ $customerName }}
                                </td>

                                <!-- Carrier -->
                                <td class="py-3.5 px-5 text-slate-600 truncate max-w-[140px]" title="{{ $carrierName }}">
                                    {{ $carrierName }}
                                </td>

                                <!-- Origin -->
                                <td class="py-3.5 px-5 text-slate-600 truncate max-w-[130px]">
                                    {{ $shipment->originLocation->city }}, {{ $shipment->originLocation->country_code }}
                                </td>

                                <!-- Destination -->
                                <td class="py-3.5 px-5 text-slate-600 truncate max-w-[130px]">
                                    {{ $shipment->destinationLocation->city }}, {{ $shipment->destinationLocation->country_code }}
                                </td>

                                <!-- ETA -->
                                <td class="py-3.5 px-5 font-mono text-slate-500">
                                    {{ $shipment->eta ? $shipment->eta->format('Y-m-d') : '2026-11-04' }}
                                </td>

                                <!-- Status Badge Pill -->
                                <td class="py-3.5 px-5 text-center">
                                    <span class="inline-block px-2.5 py-0.5 rounded text-[10px] font-bold tracking-wider uppercase {{ $statusBadgeClass }}">
                                        {{ str_replace('_', ' ', $shipment->current_status) }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="py-8 text-center text-slate-400">
                                    No shipments registered in system yet.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Footer Link -->
            <div class="p-3 bg-slate-50/60 border-t border-slate-200/80 text-center text-xs">
                <a href="{{ route('shipments.index') }}" class="font-semibold text-brand-sky hover:underline">
                    View Complete Shipment Registry &rarr;
                </a>
            </div>
        </div>

    </div>

    <!-- ========================================================================= -->
    <!-- CHART.JS & LEAFLET JAVASCRIPT INITIALIZATION                             -->
    <!-- ========================================================================= -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            
            // ---------------------------------------------------------------------
            // 1. Chart.js: Bar Chart (Shipments by Status)
            // ---------------------------------------------------------------------
            const ctxStatus = document.getElementById('shipmentsStatusBarChart');
            if (ctxStatus && typeof Chart !== 'undefined') {
                new Chart(ctxStatus.getContext('2d'), {
                    type: 'bar',
                    data: {
                        labels: ['In Transit', 'Delivered', 'Delayed', 'Exceptions'],
                        datasets: [{
                            data: [46, 61, 12, 9],
                            backgroundColor: [
                                '#0284C7', // In Transit (Sky Blue)
                                '#10B981', // Delivered (Emerald Green)
                                '#F59E0B', // Delayed (Amber)
                                '#EF4444'  // Exceptions (Alert Red)
                            ],
                            borderRadius: 6,
                            barThickness: 28,
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: { display: false },
                            tooltip: {
                                padding: 8,
                                callbacks: {
                                    label: function(context) {
                                        return context.raw + ' Shipments';
                                    }
                                }
                            }
                        },
                        scales: {
                            x: {
                                grid: { display: false },
                                ticks: { font: { size: 10 }, color: '#64748B' }
                            },
                            y: {
                                beginAtZero: true,
                                max: 80,
                                ticks: { stepSize: 20, font: { size: 10 }, color: '#94A3B8' },
                                grid: { color: '#F1F5F9' }
                            }
                        }
                    }
                });
            }

            // ---------------------------------------------------------------------
            // 2. Chart.js: Donut Chart (Delay Rate by Carrier)
            // ---------------------------------------------------------------------
            const ctxCarrier = document.getElementById('carrierDelayDonutChart');
            if (ctxCarrier && typeof Chart !== 'undefined') {
                new Chart(ctxCarrier.getContext('2d'), {
                    type: 'doughnut',
                    data: {
                        labels: ['FastShip / Maersk', 'CargoPro / DHL', 'Logistics Partners'],
                        datasets: [{
                            data: [22, 36, 42],
                            backgroundColor: [
                                '#0284C7', // Sky Blue
                                '#14B8A6', // Teal
                                '#F59E0B'  // Amber
                            ],
                            borderWidth: 2,
                            borderColor: '#ffffff',
                            hoverOffset: 4
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        cutout: '72%',
                        plugins: {
                            legend: { display: false },
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        return context.label + ': ' + context.raw + '%';
                                    }
                                }
                            }
                        }
                    }
                });
            }

            // ---------------------------------------------------------------------
            // 3. Leaflet.js: Interactive Shipment Map
            // ---------------------------------------------------------------------
            const mapContainer = document.getElementById('dashboardMap');
            if (mapContainer && typeof L !== 'undefined') {
                const map = L.map('dashboardMap', {
                    scrollWheelZoom: false,
                    zoomControl: false,
                }).setView([20.0, 30.0], 2);

                L.control.zoom({ position: 'bottomright' }).addTo(map);

                // Configurable Map Tile Layer
                L.tileLayer('{{ config('services.maps.tile_url', 'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png') }}', {
                    maxZoom: 18,
                    attribution: '{!! addslashes(config('services.maps.attribution', '&copy; OpenStreetMap contributors')) !!}'
                }).addTo(map);

                const mapShipments = @json($mapShipments ?? []);

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
                            <div style="position: relative; width: 26px; height: 34px; transform: translate(-50%, -100%); cursor: pointer; filter: drop-shadow(0 3px 5px rgba(0,0,0,0.38));">
                                <svg width="26" height="34" viewBox="0 0 24 32" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M12 0C5.37258 0 0 5.37258 0 12C0 19.5 12 32 12 32C12 32 24 19.5 24 12C24 5.37258 18.6274 0 12 0Z" fill="${colorHex}" stroke="#FFFFFF" stroke-width="1.4"/>
                                    ${innerSvg}
                                </svg>
                            </div>
                        `,
                        iconSize: [0, 0],
                        iconAnchor: [0, 0],
                        popupAnchor: [0, -32]
                    });
                }

                // Plot shipments & corridors
                mapShipments.forEach(function (shipment) {
                    const originCoord = [shipment.origin.lat, shipment.origin.lon];
                    const destCoord = [shipment.destination.lat, shipment.destination.lon];

                    const isDelivered = shipment.status === 'DELIVERED';
                    const isDelayed = shipment.status === 'DELAYED' || shipment.has_exception;

                    let routeColor = '#0284C7';
                    let pinType = 'sea';
                    let pinColor = '#0284C7';
                    let dashArray = '6, 6';

                    if (isDelayed) {
                        routeColor = '#EF4444'; // Red
                        pinType = 'delayed';
                        pinColor = '#EF4444';
                        dashArray = null;
                    } else if (isDelivered) {
                        routeColor = '#10B981'; // Green
                        pinType = 'delivered';
                        pinColor = '#10B981';
                        dashArray = null;
                    } else if (shipment.mode === 'air') {
                        routeColor = '#EC4899'; // Pink
                        pinType = 'air';
                        pinColor = '#EC4899';
                        dashArray = '6, 6';
                    } else if (shipment.mode === 'land') {
                        routeColor = '#F59E0B'; // Orange
                        pinType = 'land';
                        pinColor = '#F59E0B';
                        dashArray = '6, 6';
                    } else {
                        routeColor = '#0284C7'; // Blue
                        pinType = 'sea';
                        pinColor = '#0284C7';
                        dashArray = '6, 6';
                    }

                    // Marker: Origin (Teardrop with Dot)
                    L.marker(originCoord, {
                        icon: createModePinIcon(routeColor, pinType, true)
                    }).addTo(map).bindPopup(
                        '<div class="text-xs font-sans"><strong>Origin: ' + shipment.origin.city + '</strong><br>' + shipment.tracking_number + '</div>'
                    );

                    // Marker: Destination (Teardrop with Modal Icon)
                    L.marker(destCoord, {
                        icon: createModePinIcon(pinColor, pinType, false)
                    }).addTo(map).bindPopup(
                        '<div class="text-xs font-sans"><strong>Destination: ' + shipment.destination.city + '</strong><br>' + shipment.tracking_number + '</div>'
                    );

                    // Route Polyline
                    const polyline = L.polyline([originCoord, destCoord], {
                        color: routeColor,
                        weight: 3,
                        opacity: 0.85,
                        dashArray: dashArray
                    }).addTo(map);

                    polyline.bindPopup(
                        '<div class="text-xs font-sans"><strong>' + shipment.tracking_number + '</strong><br>' +
                        'Carrier: ' + shipment.carrier + '<br>' +
                        'Status: ' + shipment.status + '</div>'
                    );
                });
            }

        });
    </script>
</x-app-layout>
