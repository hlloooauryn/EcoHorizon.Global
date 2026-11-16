<x-app-layout :title="__('Carbon Emission Factors — EcoHorizon Global')">
    <x-slot:header>
        {{ __('Emission Factors') }}
    </x-slot:header>

    <div class="space-y-6">

        <div class="pb-4 border-b border-slate-200">
            <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight">{{ __('Carbon Emission Factors Master') }}</h1>
            <p class="text-sm text-slate-500 mt-1">
                {{ __('Configure baseline emission factors (g CO₂e / tonne-km) and official scientific literature sources.') }}
            </p>
        </div>

        <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden shadow-2xs">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse text-xs">
                    <thead>
                        <tr class="bg-slate-50 text-[10px] font-bold uppercase tracking-wider text-slate-500 border-b border-slate-200">
                            <th class="py-3 px-4">{{ __('Mode') }}</th>
                            <th class="py-3 px-4">{{ __('Subcategory Specification') }}</th>
                            <th class="py-3 px-4">{{ __('Factor (g CO₂e / t-km)') }}</th>
                            <th class="py-3 px-4">{{ __('Standard Reference Source') }}</th>
                            <th class="py-3 px-4">{{ __('Default') }}</th>
                            <th class="py-3 px-4 text-right">{{ __('Actions') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($factors as $factor)
                            <tr class="hover:bg-slate-50/80 transition-colors">
                                <td class="py-3 px-4 font-bold uppercase text-slate-800">{{ __(ucfirst($factor->transport_mode)) }}</td>
                                <td class="py-3 px-4 font-semibold text-slate-900">{{ $factor->subcategory }}</td>
                                <td class="py-3 px-4 font-mono font-bold text-emerald-700 text-sm">
                                    {{ number_format($factor->factor_g_per_tonne_km, 2) }}
                                </td>
                                <td class="py-3 px-4 text-slate-600">{{ $factor->source_reference }}</td>
                                <td class="py-3 px-4">
                                    @if($factor->is_default)
                                        <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-100 text-emerald-800">{{ __('Default') }}</span>
                                    @else
                                        <span class="text-slate-400 text-[10px]">{{ __('Alternative') }}</span>
                                    @endif
                                </td>
                                <td class="py-3 px-4 text-right">
                                    <a href="{{ route('admin.emission-factors.edit', $factor->id) }}" class="px-2.5 py-1 rounded bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold text-xs">
                                        {{ __('Edit Factor') }}
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</x-app-layout>
