<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\CarbonEmissionFactor;
use Illuminate\Http\Request;

class EmissionFactorController extends Controller
{
    public function index()
    {
        $factors = CarbonEmissionFactor::orderBy('transport_mode', 'asc')->get();
        return view('admin.emission-factors.index', compact('factors'));
    }

    public function edit(CarbonEmissionFactor $emissionFactor)
    {
        return view('admin.emission-factors.edit', compact('emissionFactor'));
    }

    public function update(Request $request, CarbonEmissionFactor $emissionFactor)
    {
        $validated = $request->validate([
            'factor_g_per_tonne_km' => ['required', 'numeric', 'min:0.01'],
            'source_reference' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'is_default' => ['nullable', 'boolean'],
        ]);

        $old = $emissionFactor->toArray();

        $emissionFactor->factor_g_per_tonne_km = $validated['factor_g_per_tonne_km'];
        $emissionFactor->source_reference = $validated['source_reference'];
        $emissionFactor->description = $validated['description'] ?? null;
        $emissionFactor->is_default = $request->boolean('is_default');
        $emissionFactor->save();

        AuditLog::log('UPDATE', $emissionFactor, "Updated carbon emission factor for {$emissionFactor->subcategory}.", $old, $emissionFactor->toArray());

        return redirect()->route('admin.emission-factors.index')->with('success', "Emission factor updated.");
    }
}
