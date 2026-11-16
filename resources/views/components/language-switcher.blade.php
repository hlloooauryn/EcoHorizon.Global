@props(['variant' => 'light'])

@php
    $currentLocale = app()->getLocale();
    $isDark = $variant === 'dark';
@endphp

<div class="inline-flex items-center p-1 rounded-xl border text-xs font-semibold transition-all {{ $isDark ? 'bg-slate-800/90 border-slate-700 shadow-inner' : 'bg-slate-100/90 border-slate-200/90 shadow-2xs' }}" title="{{ __('Switch Language') }}">
    <!-- English Switcher -->
    <a href="{{ route('lang.switch', 'en') }}" 
       class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg transition-all duration-200 {{ $currentLocale === 'en' ? 'bg-brand-sky text-white font-bold shadow-xs' : ($isDark ? 'text-slate-400 hover:text-white hover:bg-slate-700/50' : 'text-slate-600 hover:text-slate-900 hover:bg-white/70') }}"
       aria-label="Switch to English">
        <span class="text-xs">🇬🇧</span>
        <span class="text-[11px] uppercase tracking-wider font-mono font-bold">EN</span>
    </a>

    <!-- Indonesian Switcher -->
    <a href="{{ route('lang.switch', 'id') }}" 
       class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg transition-all duration-200 {{ $currentLocale === 'id' ? 'bg-brand-sky text-white font-bold shadow-xs' : ($isDark ? 'text-slate-400 hover:text-white hover:bg-slate-700/50' : 'text-slate-600 hover:text-slate-900 hover:bg-white/70') }}"
       aria-label="Switch to Indonesian">
        <span class="text-xs">🇮🇩</span>
        <span class="text-[11px] uppercase tracking-wider font-mono font-bold">ID</span>
    </a>
</div>
