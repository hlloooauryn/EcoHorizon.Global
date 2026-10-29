<x-app-layout title="System Executive Dashboard — EcoHorizon Global">
    <div class="space-y-8">
        
        <!-- Welcome Banner -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-4 border-b border-slate-200">
            <div>
                <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight">System Executive Overview</h1>
                <p class="text-sm text-slate-500 mt-1">
                    System-wide multimodal metrics, carbon accounting summary, and operational exception monitoring.
                </p>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('shipments.create') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-xs font-bold bg-brand-sky text-white hover:bg-sky-700 shadow-sm transition-all">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    Dispatch Shipment
                </a>
                <a href="{{ route('admin.audit-logs.index') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-xs font-bold bg-white border border-slate-300 text-slate-700 hover:bg-slate-50 shadow-2xs transition-all">
                    <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    Audit Trail
                </a>
            </div>
        </div>

        <!-- 6 Key Metrics Cards -->
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">
            <div class="bg-white p-4 rounded-2xl border border-slate-200/90 shadow-2xs">
                <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Total Shipments</span>
                <div class="text-2xl font-extrabold text-slate-900 mt-1">{{ $stats['total_shipments'] }}</div>
                <div class="text-[11px] text-slate-500 mt-0.5">All monitored journeys</div>
            </div>

            <div class="bg-white p-4 rounded-2xl border border-sky-200/90 bg-sky-50/20 shadow-2xs">
                <span class="text-[10px] font-bold uppercase tracking-wider text-sky-700">In Transit</span>
                <div class="text-2xl font-extrabold text-brand-sky mt-1">{{ $stats['in_transit'] }}</div>
                <div class="text-[11px] text-sky-600 mt-0.5">En route corridors</div>
            </div>

            <div class="bg-white p-4 rounded-2xl border border-emerald-200/90 bg-emerald-50/20 shadow-2xs">
                <span class="text-[10px] font-bold uppercase tracking-wider text-emerald-700">Delivered</span>
                <div class="text-2xl font-extrabold text-emerald-600 mt-1">{{ $stats['delivered'] }}</div>
                <div class="text-[11px] text-emerald-600 mt-0.5">Verified handovers</div>
            </div>

            <div class="bg-white p-4 rounded-2xl border border-amber-200/90 bg-amber-50/20 shadow-2xs">
                <span class="text-[10px] font-bold uppercase tracking-wider text-amber-700">Delayed</span>
                <div class="text-2xl font-extrabold text-amber-600 mt-1">{{ $stats['delayed'] }}</div>
                <div class="text-[11px] text-amber-600 mt-0.5">ETA rescheduled</div>
            </div>

            <div class="bg-white p-4 rounded-2xl border border-red-200/90 bg-red-50/20 shadow-2xs">
                <span class="text-[10px] font-bold uppercase tracking-wider text-red-700">Critical Exceptions</span>
                <div class="text-2xl font-extrabold text-red-600 mt-1">{{ $stats['critical_exceptions'] }}</div>
                <div class="text-[11px] text-red-600 mt-0.5">Require urgent action</div>
            </div>

            <div class="bg-white p-4 rounded-2xl border border-teal-200/90 bg-teal-50/20 shadow-2xs">
                <span class="text-[10px] font-bold uppercase tracking-wider text-teal-700">Total Emissions</span>
                <div class="text-2xl font-extrabold text-teal-600 mt-1">{{ number_format($stats['total_co2e_kg'] / 1000, 1) }} <span class="text-xs font-normal">t</span></div>
                <div class="text-[11px] text-teal-600 mt-0.5">{{ number_format($stats['total_co2e_kg']) }} kg CO₂e</div>
            </div>
        </div>

        <!-- Charts Grid (3 Chart.js Visualizers) -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Chart 1: Status Distribution -->
            <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-2xs">
                <h2 class="text-xs font-bold text-slate-900 uppercase tracking-wider mb-4">Shipment Status Breakdown</h2>
                <div class="h-64 relative">
                    <canvas id="statusChart"></canvas>
                </div>
            </div>

            <!-- Chart 2: Modal Legs Share -->
            <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-2xs">
                <h2 class="text-xs font-bold text-slate-900 uppercase tracking-wider mb-4">Multimodal Leg Distribution</h2>
                <div class="h-64 relative">
                    <canvas id="modeChart"></canvas>
                </div>
            </div>

            <!-- Chart 3: Emissions by Transport Mode -->
            <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-2xs">
                <h2 class="text-xs font-bold text-slate-900 uppercase tracking-wider mb-4">Emissions by Transport Mode (kg CO₂e)</h2>
                <div class="h-64 relative">
                    <canvas id="emissionsChart"></canvas>
                </div>
            </div>
        </div>

        <!-- 2-Columns Grid: Active Exceptions & Recent System Audit Activity -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            <!-- Column 1 & 2: Active Exceptions Watchlist -->
            <div class="lg:col-span-2 bg-white rounded-2xl border border-slate-200 overflow-hidden shadow-2xs">
                <div class="px-6 py-4 border-b border-slate-200 bg-slate-50 flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                        <h2 class="text-xs font-bold text-slate-900 uppercase tracking-wider">Active Operational Exceptions</h2>
                    </div>
                    <a href="{{ route('shipments.index', ['exception_status' => 'active']) }}" class="text-xs font-semibold text-brand-sky hover:underline">
                        View All
                    </a>
                </div>

                @if($activeExceptions->isEmpty())
                    <div class="p-8 text-center text-xs text-slate-400">
                        No active exceptions currently reported across the freight network.
                    </div>
                @else
                    <div class="divide-y divide-slate-100">
                        @foreach($activeExceptions as $exception)
                            <div class="p-4 hover:bg-slate-50/60 transition-colors flex items-start justify-between gap-4">
                                <div class="space-y-1">
                                    <div class="flex items-center gap-2">
                                        <a href="{{ route('shipments.show', $exception->shipment_id) }}" class="font-mono font-bold text-xs text-brand-sky hover:underline">
                                            {{ $exception->shipment->tracking_number }}
                                        </a>
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider {{ $exception->getSeverityBadgeClass() }}">
                                            {{ $exception->severity }}
                                        </span>
                                        <span class="text-xs font-medium text-slate-600">
                                            {{ ucfirst(str_replace('_', ' ', $exception->category)) }}
                                        </span>
                                    </div>
                                    <p class="text-xs text-slate-600 line-clamp-2">{{ $exception->description }}</p>
                                    <div class="text-[11px] text-slate-400 flex items-center gap-3 pt-1">
                                        <span>Reported {{ $exception->created_at->diffForHumans() }}</span>
                                        @if($exception->impact_on_eta_hours > 0)
                                            <span class="text-amber-700 font-semibold">+{{ $exception->impact_on_eta_hours }}h schedule impact</span>
                                        @endif
                                    </div>
                                </div>
                                <a href="{{ route('shipments.show', $exception->shipment_id) }}#exceptions" class="shrink-0 px-3 py-1.5 rounded-lg text-xs font-semibold bg-slate-100 hover:bg-slate-200 text-slate-700 transition-colors">
                                    Manage
                                </a>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <!-- Column 3: Recent Audit Trail Logs -->
            <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden shadow-2xs">
                <div class="px-6 py-4 border-b border-slate-200 bg-slate-50 flex items-center justify-between">
                    <h2 class="text-xs font-bold text-slate-900 uppercase tracking-wider">System Audit Trail</h2>
                    <a href="{{ route('admin.audit-logs.index') }}" class="text-xs font-semibold text-brand-sky hover:underline">
                        Audit Log &rarr;
                    </a>
                </div>

                <div class="p-4 space-y-4">
                    @forelse($recentAudits as $audit)
                        <div class="text-xs space-y-0.5 border-b border-slate-100 pb-3 last:border-b-0 last:pb-0">
                            <div class="flex items-center justify-between">
                                <span class="font-bold text-slate-800">{{ $audit->action }}</span>
                                <span class="text-[10px] text-slate-400 font-mono">{{ $audit->created_at->diffForHumans() }}</span>
                            </div>
                            <p class="text-slate-600 text-[11px] truncate" title="{{ $audit->description }}">
                                {{ $audit->description }}
                            </p>
                            <div class="text-[10px] text-slate-400">
                                By: <span class="font-medium text-slate-700">{{ $audit->user?->name ?? 'System Guest' }}</span>
                            </div>
                        </div>
                    @empty
                        <div class="text-center text-xs text-slate-400 py-6">No audit records yet.</div>
                    @endforelse
                </div>
            </div>

        </div>

    </div>

    <!-- Chart.js Initialization -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            if (typeof Chart === 'undefined') return;

            // 1. Status Chart
            const statusLabels = @json(array_keys($statusCounts));
            const statusData = @json(array_values($statusCounts));
            const statusCtx = document.getElementById('statusChart').getContext('2d');

            new Chart(statusCtx, {
                type: 'doughnut',
                data: {
                    labels: statusLabels.map(s => s.replace(/_/g, ' ')),
                    datasets: [{
                        data: statusData,
                        backgroundColor: ['#0284C7', '#10B981', '#14B8A6', '#F59E0B', '#EF4444', '#64748B', '#0F172A'],
                        borderWidth: 2,
                        borderColor: '#ffffff'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { position: 'bottom', labels: { boxWidth: 10, font: { size: 10 } } }
                    }
                }
            });

            // 2. Mode Distribution Chart
            const modeLabels = @json(array_keys($modeCounts));
            const modeData = @json(array_values($modeCounts));
            const modeCtx = document.getElementById('modeChart').getContext('2d');

            new Chart(modeCtx, {
                type: 'pie',
                data: {
                    labels: modeLabels.map(m => m.toUpperCase()),
                    datasets: [{
                        data: modeData,
                        backgroundColor: ['#0284C7', '#10B981', '#6366F1'],
                        borderWidth: 2,
                        borderColor: '#ffffff'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { position: 'bottom', labels: { boxWidth: 10, font: { size: 10 } } }
                    }
                }
            });

            // 3. Emissions Chart
            const emissionsLabels = @json(array_keys($modeEmissions));
            const emissionsData = @json(array_values($modeEmissions));
            const emissionsCtx = document.getElementById('emissionsChart').getContext('2d');

            new Chart(emissionsCtx, {
                type: 'bar',
                data: {
                    labels: emissionsLabels.map(m => m.toUpperCase()),
                    datasets: [{
                        label: 'kg CO₂e',
                        data: emissionsData,
                        backgroundColor: ['#0284C7', '#10B981', '#6366F1'],
                        borderRadius: 6
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false }
                    },
                    scales: {
                        y: { beginAtZero: true, grid: { color: '#f1f5f9' }, ticks: { font: { size: 10 } } },
                        x: { grid: { display: false }, ticks: { font: { size: 10 } } }
                    }
                }
            });
        });
    </script>
</x-app-layout>
