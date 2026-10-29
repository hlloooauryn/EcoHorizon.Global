<x-app-layout title="Edit Factor — EcoHorizon Global">
    <div class="max-w-xl mx-auto space-y-6">
        <div class="pb-4 border-b border-slate-200">
            <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight">Edit Emission Factor</h1>
            <p class="text-sm text-slate-500 mt-1">{{ $emissionFactor->subcategory }} ({{ strtoupper($emissionFactor->transport_mode) }})</p>
        </div>

        <form action="{{ route('admin.emission-factors.update', $emissionFactor->id) }}" method="POST" class="bg-white p-6 rounded-2xl border border-slate-200 shadow-2xs space-y-4">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Factor in g CO₂e / tonne-km *</label>
                <input type="number" step="0.01" name="factor_g_per_tonne_km" required value="{{ old('factor_g_per_tonne_km', $emissionFactor->factor_g_per_tonne_km) }}"
                       class="w-full px-3 py-2 text-xs rounded-xl border border-slate-300 font-bold text-emerald-800">
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Source Standard Reference *</label>
                <input type="text" name="source_reference" required value="{{ old('source_reference', $emissionFactor->source_reference) }}"
                       class="w-full px-3 py-2 text-xs rounded-xl border border-slate-300">
            </div>

            <div>
                <label class="block text-xs font-medium text-slate-700 mb-1">Description / Methodology</label>
                <textarea name="description" rows="3" class="w-full px-3 py-2 text-xs rounded-xl border border-slate-300">{{ old('description', $emissionFactor->description) }}</textarea>
            </div>

            <div class="flex items-center gap-2 pt-2">
                <input type="checkbox" id="is_default" name="is_default" value="1" {{ old('is_default', $emissionFactor->is_default) ? 'checked' : '' }} class="rounded border-slate-300 text-brand-sky">
                <label for="is_default" class="text-xs text-slate-700 font-medium">Set as mode default factor</label>
            </div>

            <div class="flex justify-end gap-3 pt-4 border-t border-slate-100">
                <a href="{{ route('admin.emission-factors.index') }}" class="px-4 py-2 rounded-xl text-xs font-bold bg-slate-100 text-slate-700">Cancel</a>
                <button type="submit" class="px-5 py-2 rounded-xl text-xs font-bold bg-brand-sky text-white">Save Factor</button>
            </div>
        </form>
    </div>
</x-app-layout>
