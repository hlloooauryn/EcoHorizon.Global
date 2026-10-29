<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-slate-50">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? 'EcoHorizon Global Cockpit' }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Styles & Scripts via Vite -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Pre-load SVG & Layout Constraints (FOUC Prevention) -->
    <style>
        svg { max-width: 100%; display: inline-block; vertical-align: middle; }
        .h-8 { height: 2rem; }
        .h-9 { height: 2.25rem; }
        .w-6 { width: 1.5rem; }
        .h-6 { height: 1.5rem; }
        .w-4 { width: 1rem; }
        .h-4 { height: 1rem; }
    </style>
</head>
<body class="h-full flex antialiased text-[#0F172A] bg-[#F8FAFC]">
    
    <!-- Sidebar -->
    <aside class="w-64 bg-[#0F172A] text-slate-300 flex flex-col shrink-0 border-r border-slate-800">
        <!-- Brand Header -->
        <div class="h-16 flex items-center px-6 border-b border-slate-800">
            <a href="{{ route('home') }}" class="flex items-center gap-2">
                <x-logo class="h-8 w-auto" :light="true" />
            </a>
        </div>

        <!-- Role Badge Panel -->
        <div class="px-6 py-4 border-b border-slate-800/80 bg-slate-900/60">
            <div class="text-[11px] uppercase tracking-wider font-semibold text-slate-400">Signed in as</div>
            <div class="font-bold text-white truncate">{{ auth()->user()->name }}</div>
            <div class="mt-1 flex items-center justify-between">
                <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider 
                    {{ auth()->user()->isAdmin() ? 'bg-purple-900/80 text-purple-200 border border-purple-700' : (auth()->user()->isOperator() ? 'bg-sky-900/80 text-sky-200 border border-sky-700' : 'bg-emerald-900/80 text-emerald-200 border border-emerald-700') }}">
                    {{ ucfirst(auth()->user()->role) }}
                </span>
                <span class="text-[11px] text-slate-400 truncate max-w-[110px]" title="{{ auth()->user()->company_name }}">
                    {{ auth()->user()->company_name ?? 'EcoHorizon Network' }}
                </span>
            </div>
        </div>

        <!-- Navigation Menu -->
        <nav class="flex-1 px-4 py-4 space-y-1.5 overflow-y-auto text-sm font-medium">
            @if(auth()->user()->isAdmin())
                <div class="text-[10px] font-bold uppercase tracking-wider text-slate-400 px-3 py-1">Administrator</div>
                <a href="{{ route('admin.dashboard') }}" 
                   class="flex items-center gap-3 px-3 py-2 rounded-lg transition-colors {{ request()->routeIs('admin.dashboard') ? 'bg-brand-sky text-white font-semibold' : 'hover:bg-slate-800 text-slate-300' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>
                    <span>System Overview</span>
                </a>
                <a href="{{ route('admin.users.index') }}" 
                   class="flex items-center gap-3 px-3 py-2 rounded-lg transition-colors {{ request()->routeIs('admin.users.*') ? 'bg-brand-sky text-white font-semibold' : 'hover:bg-slate-800 text-slate-300' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                    <span>User Management</span>
                </a>
                <a href="{{ route('admin.emission-factors.index') }}" 
                   class="flex items-center gap-3 px-3 py-2 rounded-lg transition-colors {{ request()->routeIs('admin.emission-factors.*') ? 'bg-brand-sky text-white font-semibold' : 'hover:bg-slate-800 text-slate-300' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span>Carbon Emission Factors</span>
                </a>
                <a href="{{ route('admin.audit-logs.index') }}" 
                   class="flex items-center gap-3 px-3 py-2 rounded-lg transition-colors {{ request()->routeIs('admin.audit-logs.*') ? 'bg-brand-sky text-white font-semibold' : 'hover:bg-slate-800 text-slate-300' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    <span>Audit Trail Logs</span>
                </a>
            @endif

            @if(auth()->user()->isOperator())
                <div class="text-[10px] font-bold uppercase tracking-wider text-slate-400 px-3 py-1">Operations Control</div>
                <a href="{{ route('operator.dashboard') }}" 
                   class="flex items-center gap-3 px-3 py-2 rounded-lg transition-colors {{ request()->routeIs('operator.dashboard') ? 'bg-brand-sky text-white font-semibold' : 'hover:bg-slate-800 text-slate-300' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                    <span>Operator Cockpit</span>
                </a>
                <a href="{{ route('shipments.create') }}" 
                   class="flex items-center gap-3 px-3 py-2 rounded-lg transition-colors {{ request()->routeIs('shipments.create') ? 'bg-brand-sky text-white font-semibold' : 'hover:bg-slate-800 text-slate-300' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                    <span>Create New Shipment</span>
                </a>
                <a href="{{ route('shipments.index', ['exception_status' => 'active']) }}" 
                   class="flex items-center gap-3 px-3 py-2 rounded-lg transition-colors {{ request('exception_status') === 'active' ? 'bg-red-900/60 text-red-200 border border-red-800 font-semibold' : 'hover:bg-slate-800 text-slate-300' }}">
                    <svg class="w-4 h-4 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                    <span>Exception Watchlist</span>
                </a>
            @endif

            @if(auth()->user()->isCustomer())
                <div class="text-[10px] font-bold uppercase tracking-wider text-slate-400 px-3 py-1">Customer Workspace</div>
                <a href="{{ route('customer.dashboard') }}" 
                   class="flex items-center gap-3 px-3 py-2 rounded-lg transition-colors {{ request()->routeIs('customer.dashboard') ? 'bg-brand-emerald text-white font-semibold' : 'hover:bg-slate-800 text-slate-300' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                    <span>My Shipments Dashboard</span>
                </a>
            @endif

            <!-- Common Section -->
            <div class="text-[10px] font-bold uppercase tracking-wider text-slate-400 px-3 py-1 pt-3">Freight Records</div>
            <a href="{{ route('shipments.index') }}" 
               class="flex items-center gap-3 px-3 py-2 rounded-lg transition-colors {{ request()->routeIs('shipments.index') && !request('exception_status') ? 'bg-brand-sky text-white font-semibold' : 'hover:bg-slate-800 text-slate-300' }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                <span>Shipment Registry</span>
            </a>
            <a href="{{ route('sustainability.index') }}" 
               class="flex items-center gap-3 px-3 py-2 rounded-lg transition-colors {{ request()->routeIs('sustainability.*') ? 'bg-brand-emerald text-white font-semibold' : 'hover:bg-slate-800 text-slate-300' }}">
                <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                <span>Carbon Emissions (CO₂e)</span>
            </a>
            <a href="{{ route('home') }}#track" target="_blank"
               class="flex items-center gap-3 px-3 py-2 rounded-lg transition-colors hover:bg-slate-800 text-slate-400">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                <span>Public Tracking View</span>
            </a>
        </nav>

        <!-- User Logout Bottom -->
        <div class="p-4 border-t border-slate-800">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" 
                        class="w-full flex items-center justify-center gap-2 px-3 py-2 rounded-lg text-sm font-medium text-slate-300 hover:text-white bg-slate-800 hover:bg-red-900/60 hover:text-red-200 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                    <span>Sign Out</span>
                </button>
            </form>
        </div>
    </aside>

    <!-- Main Content Workspace -->
    <div class="flex-1 flex flex-col min-w-0 overflow-y-auto">
        <!-- Top Workspace Bar -->
        <header class="h-16 bg-white border-b border-slate-200 px-6 sm:px-8 flex items-center justify-between sticky top-0 z-30 shadow-sm">
            <div class="flex items-center gap-4">
                <h1 class="text-lg font-bold text-slate-900">{{ $header ?? 'EcoHorizon Global' }}</h1>
            </div>

            <div class="flex items-center gap-4">
                <!-- Tracking Fast Search -->
                <form action="{{ route('track.search') }}" method="GET" class="hidden sm:flex items-center relative">
                    <input type="text" name="tracking_number" placeholder="Quick Track (e.g. EHG-...)" 
                           class="w-56 pl-8 pr-3 py-1.5 text-xs rounded-lg border border-slate-300 focus:outline-none focus:ring-2 focus:ring-brand-sky focus:border-brand-sky bg-slate-50">
                    <svg class="w-4 h-4 text-slate-400 absolute left-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </form>

                <!-- Current Environment Time -->
                <div class="hidden md:flex items-center gap-1.5 text-xs text-slate-500 font-mono">
                    <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                    <span>UTC {{ now()->format('Y-m-d H:i') }}</span>
                </div>
            </div>
        </header>

        <!-- Flash Messages -->
        @if(session('success'))
            <div class="px-6 sm:px-8 pt-4">
                <div class="p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 flex items-start gap-3 shadow-sm">
                    <svg class="w-5 h-5 text-emerald-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    <div class="text-sm font-medium">{{ session('success') }}</div>
                </div>
            </div>
        @endif

        @if(session('error'))
            <div class="px-6 sm:px-8 pt-4">
                <div class="p-4 rounded-xl bg-red-50 border border-red-200 text-red-800 flex items-start gap-3 shadow-sm">
                    <svg class="w-5 h-5 text-red-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <div class="text-sm font-medium">{{ session('error') }}</div>
                </div>
            </div>
        @endif

        @if($errors->any())
            <div class="px-6 sm:px-8 pt-4">
                <div class="p-4 rounded-xl bg-red-50 border border-red-200 text-red-800 shadow-sm">
                    <div class="font-semibold text-sm mb-1">Please correct the following errors:</div>
                    <ul class="list-disc list-inside text-xs space-y-1">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif

        <!-- Content Area -->
        <main class="p-6 sm:p-8 flex-1">
            {{ $slot }}
        </main>
    </div>

</body>
</html>

