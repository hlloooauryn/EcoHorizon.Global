<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? 'EcoHorizon Global — Global Multimodal Freight Visibility Platform' }}</title>
    <meta name="description" content="Track sea, land, and air shipments with verified delivery stages, terminal handovers, and estimated carbon emissions.">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Styles & Scripts via Vite -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Pre-load SVG & Layout Constraints (FOUC Prevention) -->
    <style>
        svg { max-width: 100%; display: inline-block; vertical-align: middle; }
        .h-9 { height: 2.25rem; }
        .w-6 { width: 1.5rem; }
        .h-6 { height: 1.5rem; }
        .w-4 { width: 1rem; }
        .h-4 { height: 1rem; }
    </style>
</head>
<body class="min-h-full flex flex-col bg-[#F8FAFC] text-[#0F172A] font-sans antialiased selection:bg-brand-teal selection:text-white">

    <!-- Top Navigation -->
    <header class="sticky top-0 z-50 bg-white/90 backdrop-blur-md border-b border-slate-200/80 transition-all">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <!-- Brand Logo -->
                <a href="{{ route('home') }}" class="group transition-transform hover:scale-[1.01]">
                    <x-logo class="h-9 w-auto" />
                </a>

                <!-- Nav Links -->
                <nav class="hidden md:flex items-center gap-7 text-sm font-medium text-slate-600">
                    <a href="{{ route('home') }}#track" onclick="setTimeout(() => { const el = document.getElementById('tracking-search-input'); if(el) el.focus(); }, 150)" class="hover:text-brand-sky transition-colors flex items-center gap-1.5">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                        {{ __('Track Shipment') }}
                    </a>
                    <a href="{{ route('home') }}#multimodal" class="hover:text-brand-sky transition-colors">{{ __('Multimodal Modes') }}</a>
                    <a href="{{ route('home') }}#services" class="hover:text-brand-sky transition-colors">{{ __('Service Levels') }}</a>
                    <a href="{{ route('home') }}#sustainability" class="hover:text-brand-emerald transition-colors flex items-center gap-1">
                        <span class="w-2 h-2 rounded-full bg-brand-emerald"></span>
                        {{ __('Sustainability (CO₂e)') }}
                    </a>
                </nav>

                <!-- Auth / Portal CTA & Language Switcher -->
                <div class="flex items-center gap-3">
                    <x-language-switcher />

                    @auth
                        <a href="{{ auth()->user()->isAdmin() ? route('admin.dashboard') : (auth()->user()->isOperator() ? route('operator.dashboard') : route('customer.dashboard')) }}" 
                           class="inline-flex items-center gap-2 px-4 py-2 rounded-lg text-sm font-semibold bg-brand-navy text-white hover:bg-slate-800 transition-colors shadow-sm">
                            <span>{{ __('Portal Dashboard') }}</span>
                            <span class="px-1.5 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider bg-brand-sky text-white">
                                {{ auth()->user()->role }}
                            </span>
                        </a>
                    @else
                        <a href="{{ route('login') }}" 
                           class="inline-flex items-center gap-2 px-4 py-2 rounded-lg text-sm font-semibold bg-brand-sky text-white hover:bg-sky-700 transition-all shadow-sm shadow-sky-600/20 hover:shadow-sky-600/30">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/></svg>
                            {{ __('Log In / Demo Portal') }}
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </header>

    <!-- Flash Messages -->
    @if(session('success'))
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4">
            <div class="p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 flex items-start gap-3 shadow-sm">
                <svg class="w-5 h-5 text-emerald-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                <div class="text-sm font-medium">{{ session('success') }}</div>
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4">
            <div class="p-4 rounded-xl bg-red-50 border border-red-200 text-red-800 flex items-start gap-3 shadow-sm">
                <svg class="w-5 h-5 text-red-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <div class="text-sm font-medium">{{ session('error') }}</div>
            </div>
        </div>
    @endif

    <!-- Main Content Area -->
    <main class="flex-grow">
        {{ $slot }}
    </main>

    <!-- Footer -->
    <footer class="bg-[#0F172A] text-slate-400 border-t border-slate-800 text-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 lg:py-16">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8 mb-12">
                <!-- Col 1: Identity -->
                <div class="md:col-span-2 space-y-4">
                    <x-logo class="h-9 w-auto" :light="true" />
                    <p class="text-sm text-slate-400 max-w-md leading-relaxed">
                        {{ __('Global Multimodal Freight Visibility Platform designed to track sea, land, and air cargo across international corridors. Combining real-time transport leg status, delivery service levels, exception handling, and auditable carbon emission estimates.') }}
                    </p>
                    <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-lg bg-slate-800/80 border border-slate-700 text-xs text-brand-teal font-medium">
                        <svg class="w-4 h-4 text-brand-emerald" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                        {{ __('Tagline: Track Every Journey. Move Toward a Greener Future.') }}
                    </div>
                </div>

                <!-- Col 2: Service Levels -->
                <div>
                    <h4 class="text-xs font-semibold text-white uppercase tracking-wider mb-4">{{ __('Service Levels') }}</h4>
                    <ul class="space-y-2 text-sm text-slate-400">
                        <li><a href="{{ route('home') }}#services" class="hover:text-white transition-colors">{{ __('B2B / Warehouse Delivery') }}</a></li>
                        <li><a href="{{ route('home') }}#services" class="hover:text-white transition-colors">{{ __('Door-to-Port (Terminal Discharged)') }}</a></li>
                        <li><a href="{{ route('home') }}#services" class="hover:text-white transition-colors">{{ __('Port-to-Port (Ocean/Air Terminal)') }}</a></li>
                        <li><a href="{{ route('home') }}#services" class="hover:text-white transition-colors">{{ __('B2B Warehouse Fulfillment') }}</a></li>
                    </ul>
                </div>

                <!-- Col 3: Academic & Compliance -->
                <div>
                    <h4 class="text-xs font-semibold text-white uppercase tracking-wider mb-4">{{ __('Academic Project Deliverable') }}</h4>
                    <p class="text-xs text-slate-400 leading-relaxed mb-3">
                        {{ __('EcoHorizon Global is engineered as a realistic, functional, and secure university final project (UAS). Built with Laravel 11, Tailwind CSS, Leaflet Maps, and GLEC Framework carbon accounting.') }}
                    </p>
                    <div class="text-[11px] text-slate-500">
                        {{ __('Default Demo Accounts available on Login screen.') }}
                    </div>
                </div>
            </div>

            <!-- Bottom Bar -->
            <div class="pt-8 border-t border-slate-800/80 flex flex-col sm:flex-row items-center justify-between gap-4 text-xs text-slate-500">
                <p>&copy; {{ date('Y') }} EcoHorizon Global. {{ __('All rights reserved.') }}</p>
                <div class="flex items-center gap-4">
                    <span class="inline-flex items-center gap-1.5 text-emerald-400">
                        <span class="w-2 h-2 rounded-full bg-emerald-400"></span>
                        {{ __('System Operational & Emissions Engine Online') }}
                    </span>
                </div>
            </div>
        </div>
    </footer>

    <!-- Auto-focus tracking search bar if navigation targets #track -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            if (window.location.hash === '#track') {
                setTimeout(() => {
                    const searchInput = document.getElementById('tracking-search-input');
                    if (searchInput) {
                        searchInput.focus();
                    }
                }, 200);
            }
        });
    </script>
</body>
</html>

