<x-app-layout title="System Audit Trail — EcoHorizon Global">
    <div class="space-y-6">

        <div class="pb-4 border-b border-slate-200">
            <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight">System Audit Trail Logs</h1>
            <p class="text-sm text-slate-500 mt-1">
                Tamper-evident chronological audit logs of all status transitions, exception reports, and freight dispatches.
            </p>
        </div>

        <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-2xs">
            <form action="{{ route('admin.audit-logs.index') }}" method="GET" class="flex flex-wrap items-center gap-3">
                <div class="relative flex-1 min-w-[200px]">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search description or tracking #..." 
                           class="w-full pl-9 pr-3 py-2 text-xs rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-brand-sky bg-slate-50">
                    <svg class="w-4 h-4 text-slate-400 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </div>

                <select name="action" class="py-2 px-3 text-xs rounded-xl border border-slate-300 bg-slate-50">
                    <option value="">All Actions</option>
                    @foreach($actions as $act)
                        <option value="{{ $act }}" {{ request('action') === $act ? 'selected' : '' }}>{{ $act }}</option>
                    @endforeach
                </select>

                <button type="submit" class="px-4 py-2 rounded-xl text-xs font-bold bg-brand-sky text-white">Filter</button>
            </form>
        </div>

        <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden shadow-2xs">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse text-xs">
                    <thead>
                        <tr class="bg-slate-50 text-[10px] font-bold uppercase tracking-wider text-slate-500 border-b border-slate-200">
                            <th class="py-3 px-4">Timestamp (UTC)</th>
                            <th class="py-3 px-4">Action</th>
                            <th class="py-3 px-4">Responsible User</th>
                            <th class="py-3 px-4">Description</th>
                            <th class="py-3 px-4">IP Address</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($logs as $log)
                            <tr class="hover:bg-slate-50/80 transition-colors">
                                <td class="py-3 px-4 font-mono text-slate-500 text-[11px] whitespace-nowrap">
                                    {{ $log->created_at->format('Y-m-d H:i:s') }}
                                </td>
                                <td class="py-3 px-4">
                                    <span class="inline-flex px-2 py-0.5 rounded text-[10px] font-bold uppercase font-mono
                                        {{ in_array($log->action, ['STATUS_CHANGE', 'EXCEPTION_LOGGED']) ? 'bg-amber-100 text-amber-800' : 'bg-slate-100 text-slate-700' }}">
                                        {{ $log->action }}
                                    </span>
                                </td>
                                <td class="py-3 px-4 font-medium text-slate-800">
                                    {{ $log->user?->name ?? 'System Guest' }}
                                    <span class="block text-[10px] text-slate-400 font-mono">{{ $log->user?->email }}</span>
                                </td>
                                <td class="py-3 px-4 text-slate-700 leading-relaxed">{{ $log->description }}</td>
                                <td class="py-3 px-4 font-mono text-slate-400 text-[11px]">{{ $log->ip_address ?? '127.0.0.1' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-8 text-center text-xs text-slate-400">No audit records match criteria.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($logs->hasPages())
                <div class="px-6 py-3 border-t border-slate-200">
                    {{ $logs->links() }}
                </div>
            @endif
        </div>

    </div>
</x-app-layout>

