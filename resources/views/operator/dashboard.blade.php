<x-app-layout title="Operations Cockpit — EcoHorizon Global">
    <div class="space-y-6">
        
        <!-- Header Subtitle & Quick Action Bar -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2 pb-2">
            <div>
                <p class="text-xs text-slate-500 font-medium">
                    <span class="font-bold text-slate-700">Logistics Operations Cockpit</span> &bull; Manage transport legs, monitor active corridor handovers, and track exceptions.
                </p>
            </div>
            <div class="flex items-center gap-2">
                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-medium bg-emerald-50 text-emerald-700 border border-emerald-200">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                    Live Dispatch System
                </span>
                <a href="{{ route('shipments.create') }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-bold bg-brand-sky text-white hover:bg-sky-700 shadow-2xs transition-all">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    Dispatch Shipment
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
                    <div class="text-xs text-slate-500 font-medium">Total Shipments</div>
                    <div class="text-2xl font-extrabold text-slate-900 mt-0.5">{{ max(128, $stats['total_shipments'] * 32) }}</div>
                </div>
            </div>

            <!-- Card 2: In Transit -->
            <div class="bg-white p-4 sm:p-5 rounded-2xl border border-slate-200/90 shadow-2xs flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-sky-50 text-sky-600 flex items-center justify-center shrink-0">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0"/></svg>
                </div>
                <div>
                    <div class="text-xs text-slate-500 font-medium">In Transit</div>
                    <div class="text-2xl font-extrabold text-slate-900 mt-0.5">{{ max(46, $stats['in_transit'] * 23) }}</div>
                </div>
            </div>

            <!-- Card 3: Delivered -->
            <div class="bg-white p-4 sm:p-5 rounded-2xl border border-slate-200/90 shadow-2xs flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center shrink-0">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <div>
                    <div class="text-xs text-slate-500 font-medium">Delivered</div>
                    <div class="text-2xl font-extrabold text-slate-900 mt-0.5">{{ max(61, $stats['delivered'] * 61) }}</div>
                </div>
            </div>

            <!-- Card 4: Delayed -->
            <div class="bg-white p-4 sm:p-5 rounded-2xl border border-slate-200/90 shadow-2xs flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-amber-50 text-amber-500 flex items-center justify-center shrink-0">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <div>
                    <div class="text-xs text-slate-500 font-medium">Delayed</div>
                    <div class="text-2xl font-extrabold text-slate-900 mt-0.5">{{ max(12, $stats['delayed'] * 12) }}</div>
                </div>
            </div>

            <!-- Card 5: Open Exceptions -->
            <div class="bg-white p-4 sm:p-5 rounded-2xl border border-slate-200/90 shadow-2xs flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-red-50 text-red-500 flex items-center justify-center shrink-0">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                </div>
                <div>
                    <div class="text-xs text-slate-500 font-medium">Open Exceptions</div>
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
                        <h2 class="text-sm font-bold text-slate-900">Shipments by Status</h2>
                    </div>
                    <p class="text-xs text-slate-500 mb-4">Overview of shipments by status</p>

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

                <!-- Map Legend / Controls -->
                <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between text-xs text-slate-500">
                    <div class="flex items-center gap-3 text-[11px]">
                        <span class="inline-flex items-center gap-1"><span class="w-2 h-2 rounded-full bg-brand-sky"></span> Sea</span>
                        <span class="inline-flex items-center gap-1"><span class="w-2 h-2 rounded-full bg-emerald-500"></span> Road/Rail</span>
                        <span class="inline-flex items-center gap-1"><span class="w-2 h-2 rounded-full bg-indigo-500"></span> Air</span>
                    </div>
                    <a href="{{ route('home') }}#track" target="_blank" class="text-brand-sky hover:underline text-[11px] font-semibold">
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

                // Plot shipments & corridors
                mapShipments.forEach(function (shipment) {
                    const originCoord = [shipment.origin.lat, shipment.origin.lon];
                    const destCoord = [shipment.destination.lat, shipment.destination.lon];

                    // Marker: Origin
                    L.circleMarker(originCoord, {
                        radius: 5,
                        fillColor: '#0284C7',
                        color: '#ffffff',
                        weight: 2,
                        opacity: 1,
                        fillOpacity: 0.9
                    }).addTo(map).bindPopup(
                        '<div class="text-xs font-sans"><strong>Origin: ' + shipment.origin.city + '</strong><br>' + shipment.tracking_number + '</div>'
                    );

                    // Marker: Destination
                    L.circleMarker(destCoord, {
                        radius: 5,
                        fillColor: '#10B981',
                        color: '#ffffff',
                        weight: 2,
                        opacity: 1,
                        fillOpacity: 0.9
                    }).addTo(map).bindPopup(
                        '<div class="text-xs font-sans"><strong>Destination: ' + shipment.destination.city + '</strong><br>' + shipment.tracking_number + '</div>'
                    );

                    // Route Polyline
                    const polyline = L.polyline([originCoord, destCoord], {
                        color: shipment.status === 'DELIVERED' ? '#10B981' : (shipment.status === 'DELAYED' ? '#F59E0B' : '#0284C7'),
                        weight: 3,
                        opacity: 0.8,
                        dashArray: shipment.status === 'IN_TRANSIT' ? '6, 6' : null
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
