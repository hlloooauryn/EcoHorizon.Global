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
            <!-- 1. Dashboard -->
            @php
                $dashboardRoute = auth()->user()->isAdmin() ? route('admin.dashboard') : (auth()->user()->isOperator() ? route('operator.dashboard') : route('customer.dashboard'));
                $isDashboard = request()->routeIs('*.dashboard');
            @endphp
            <a href="{{ $dashboardRoute }}" 
               class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all {{ $isDashboard ? 'bg-brand-sky text-white font-semibold shadow-sm' : 'hover:bg-slate-800 text-slate-300' }}">
                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                <span>{{ __('Dashboard') }}</span>
            </a>

            <!-- 2. Shipments -->
            <a href="{{ route('shipments.index') }}" 
               class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all {{ request()->routeIs('shipments.index') && !request('exception_status') ? 'bg-brand-sky text-white font-semibold shadow-sm' : 'hover:bg-slate-800 text-slate-300' }}">
                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                <span>{{ __('Shipments') }}</span>
            </a>

            <!-- 3. Tracking Console -->
            <a href="{{ route('tracking.index') }}" 
               class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all {{ request()->routeIs('tracking.*') ? 'bg-brand-sky text-white font-semibold shadow-sm' : 'hover:bg-slate-800 text-slate-300' }}">
                <svg class="w-4 h-4 shrink-0 text-brand-teal" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                <span>{{ __('Tracking') }}</span>
            </a>

            <!-- 4. Exceptions -->
            <a href="{{ route('shipments.index', ['exception_status' => 'active']) }}" 
               class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all {{ request('exception_status') === 'active' ? 'bg-red-900/60 text-red-200 border border-red-800 font-semibold shadow-sm' : 'hover:bg-slate-800 text-slate-300' }}">
                <svg class="w-4 h-4 shrink-0 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                <span>{{ __('Exceptions') }}</span>
            </a>

            <!-- 5. Carriers & Sustainability -->
            <a href="{{ route('sustainability.index') }}" 
               class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all {{ request()->routeIs('sustainability.*') ? 'bg-brand-sky text-white font-semibold shadow-sm' : 'hover:bg-slate-800 text-slate-300' }}">
                <svg class="w-4 h-4 shrink-0 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0"/></svg>
                <span>{{ __('Carriers') }}</span>
            </a>

            <!-- 6. Reports / Audit Trail -->
            @if(auth()->user()->isAdmin())
                <a href="{{ route('admin.audit-logs.index') }}" 
                   class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all {{ request()->routeIs('admin.audit-logs.*') ? 'bg-brand-sky text-white font-semibold shadow-sm' : 'hover:bg-slate-800 text-slate-300' }}">
                    <svg class="w-4 h-4 shrink-0 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    <span>{{ __('Reports') }}</span>
                </a>

                <div class="pt-3 pb-1">
                    <div class="text-[10px] font-bold uppercase tracking-wider text-slate-500 px-3">{{ __('Administration') }}</div>
                </div>
                <a href="{{ route('admin.users.index') }}" 
                   class="flex items-center gap-3 px-3 py-2 rounded-lg text-xs transition-colors {{ request()->routeIs('admin.users.*') ? 'bg-slate-800 text-white font-semibold' : 'hover:bg-slate-800/60 text-slate-400' }}">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                    <span>{{ __('User Management') }}</span>
                </a>
                <a href="{{ route('admin.emission-factors.index') }}" 
                   class="flex items-center gap-3 px-3 py-2 rounded-lg text-xs transition-colors {{ request()->routeIs('admin.emission-factors.*') ? 'bg-slate-800 text-white font-semibold' : 'hover:bg-slate-800/60 text-slate-400' }}">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span>{{ __('Emission Factors') }}</span>
                </a>
            @else
                <a href="{{ route('shipments.index') }}" 
                   class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all hover:bg-slate-800 text-slate-300">
                    <svg class="w-4 h-4 shrink-0 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    <span>{{ __('Reports') }}</span>
                </a>
            @endif
        </nav>

        <!-- Sidebar Language Switcher & Logout -->
        <div class="p-4 border-t border-slate-800 space-y-3">
            <div class="flex items-center justify-between">
                <span class="text-[10px] font-bold uppercase tracking-wider text-slate-500">{{ __('Switch Language') }}</span>
                <x-language-switcher variant="dark" />
            </div>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" 
                        class="w-full flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium text-slate-400 hover:text-white hover:bg-slate-800 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                    <span>{{ __('Logs Out') }}</span>
                </button>
            </form>
        </div>
    </aside>

    <!-- Main Content Workspace -->
    <div class="flex-1 flex flex-col min-w-0 overflow-y-auto">
        <!-- Top Workspace Bar -->
        <header class="h-16 bg-white border-b border-slate-200 px-6 sm:px-8 flex items-center justify-between sticky top-0 z-30 shadow-2xs">
            <div class="flex items-center gap-4">
                <h1 class="text-xl font-extrabold text-slate-900 tracking-tight">{{ isset($header) ? __(trim((string) $header)) : __('Dashboard') }}</h1>
            </div>

            <div class="flex items-center gap-3 sm:gap-5">
                <!-- Back to Landing Page Shortcut -->
                <a href="{{ route('home') }}" 
                   class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-semibold text-slate-700 bg-slate-100 hover:bg-slate-200 border border-slate-200/90 transition-all shadow-2xs"
                   title="{{ __('Back to Landing Page') }}">
                    <svg class="w-3.5 h-3.5 text-brand-sky" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                    <span class="hidden sm:inline">{{ __('Landing Page') }}</span>
                </a>

                <!-- Topbar Language Switcher -->
                <x-language-switcher />

                <!-- Search Box -->
                <form action="{{ route('track.search') }}" method="GET" class="relative flex items-center">
                    <input type="text" name="tracking_number" placeholder="{{ __('Search...') }}" 
                           class="w-36 sm:w-60 pl-8 pr-3 py-1.5 text-xs rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-brand-sky focus:border-brand-sky text-slate-800 transition-all">
                    <svg class="w-3.5 h-3.5 text-slate-400 absolute left-2.5 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </form>

                <!-- Notifications Bell with Red Badge -->
                <div class="relative">
                    <a href="{{ route('shipments.index', ['exception_status' => 'active']) }}" class="p-2 rounded-xl text-slate-400 hover:text-slate-600 hover:bg-slate-100 transition-colors flex items-center justify-center" title="{{ __('Exceptions') }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                        <span class="absolute top-1 right-1 w-2.5 h-2.5 bg-red-500 rounded-full ring-2 ring-white"></span>
                    </a>
                </div>

                <!-- User Profile Pill -->
                <div class="flex items-center gap-2.5 pl-3 border-l border-slate-200">
                    <div class="w-8 h-8 rounded-full bg-brand-sky text-white flex items-center justify-center font-bold text-xs shadow-xs">
                        {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                    </div>
                    <div class="hidden md:block">
                        <div class="text-xs font-bold text-slate-800 leading-tight">{{ auth()->user()->name }}</div>
                        <div class="text-[10px] text-slate-400 capitalize">{{ auth()->user()->role }}</div>
                    </div>
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

