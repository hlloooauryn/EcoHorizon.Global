@props(['class' => 'h-9 w-auto', 'showText' => true, 'light' => false])

<div class="inline-flex items-center gap-2.5">
    <!-- EcoHorizon Original Logo Symbol -->
    <svg class="{{ $class }}" viewBox="0 0 100 100" fill="none" xmlns="http://www.w3.org/2000/svg">
        <defs>
            <linearGradient id="ehg_grad_sky" x1="0%" y1="0%" x2="100%" y2="100%">
                <stop offset="0%" stop-color="#0284C7" />
                <stop offset="60%" stop-color="#14B8A6" />
                <stop offset="100%" stop-color="#10B981" />
            </linearGradient>
            <linearGradient id="ehg_grad_leaf" x1="0%" y1="100%" x2="100%" y2="0%">
                <stop offset="0%" stop-color="#10B981" />
                <stop offset="100%" stop-color="#34D399" />
            </linearGradient>
        </defs>

        <!-- Outer Precision Ring (Global Connectivity) -->
        <circle cx="50" cy="50" r="44" stroke="url(#ehg_grad_sky)" stroke-width="4.5" stroke-dasharray="14 4" opacity="0.45" />

        <!-- Horizon Curved Arc (The Earth's Horizon) -->
        <path d="M 16 58 Q 50 42 84 58" stroke="url(#ehg_grad_sky)" stroke-width="5" stroke-linecap="round" fill="none" />

        <!-- Dynamic Transit Route Line (Ascending Path) -->
        <path d="M 22 72 Q 46 64 62 38 T 78 22" stroke="#0284C7" stroke-width="4" stroke-linecap="round" fill="none" />

        <!-- Waypoint Node Dot -->
        <circle cx="62" cy="38" r="4.5" fill="#0284C7" />

        <!-- Subtle Sustainability Leaf / Navigation Beacon at Crest -->
        <path d="M 78 22 C 84 14, 88 18, 86 28 C 84 34, 76 34, 78 22 Z" fill="url(#ehg_grad_leaf)" />
        <path d="M 78 22 L 83 27" stroke="#FFFFFF" stroke-width="1.2" stroke-linecap="round" />

        <!-- Compass Cardinal Waypoint -->
        <circle cx="50" cy="14" r="2.5" fill="#14B8A6" />
    </svg>

    @if($showText)
        <div class="flex flex-col leading-none">
            <span class="text-lg font-bold tracking-tight {{ $light ? 'text-white' : 'text-[#0F172A]' }}">
                EcoHorizon<span class="text-brand-teal">.</span><span class="text-xs font-semibold uppercase tracking-wider text-brand-emerald ml-1">Global</span>
            </span>
            <span class="text-[10px] tracking-wider uppercase font-medium {{ $light ? 'text-slate-300' : 'text-slate-500' }}">
                Multimodal Freight Visibility
            </span>
        </div>
    @endif
</div>

