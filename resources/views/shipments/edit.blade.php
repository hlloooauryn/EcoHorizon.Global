<x-app-layout :title="__('Edit Shipment :number — EcoHorizon Global', ['number' => $shipment->tracking_number])">
    <x-slot:header>
        {{ __('Modify Shipment Parameters') }}
    </x-slot:header>

    <div class="max-w-4xl mx-auto space-y-6">

        <div class="pb-4 border-b border-slate-200">
            <div class="flex items-center gap-2 text-xs font-semibold uppercase tracking-wider text-slate-500 mb-1">
                <a href="{{ route('shipments.show', $shipment->id) }}" class="hover:text-brand-sky">{{ $shipment->tracking_number }}</a>
                <span>/</span>
                <span class="text-brand-sky">{{ __('Edit Cargo & Consignee Details') }}</span>
            </div>
            <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight">{{ __('Modify Shipment Parameters') }}</h1>
        </div>

        <form action="{{ route('shipments.update', $shipment->id) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <div class="bg-white p-6 sm:p-8 rounded-2xl border border-slate-200 shadow-2xs space-y-6">
                <!-- Cargo Details -->
                <div>
                    <h2 class="text-xs font-bold uppercase tracking-wider text-slate-700 border-b border-slate-100 pb-2 mb-4">
                        {{ __('Cargo Specifications') }}
                    </h2>
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <div class="sm:col-span-2">
                            <label class="block text-xs font-medium text-slate-700 mb-1">{{ __('Commodity Description') }} *</label>
                            <input type="text" name="cargo_description" required value="{{ old('cargo_description', $shipment->cargo_description) }}"
                                   class="w-full px-3 py-2 text-xs rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-brand-sky">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-emerald-800 mb-1">{{ __('Weight in Tonnes (t)') }} *</label>
                            <input type="number" step="0.001" name="cargo_weight_tonnes" required value="{{ old('cargo_weight_tonnes', $shipment->cargo_weight_tonnes) }}"
                                   class="w-full px-3 py-2 text-xs rounded-xl border border-emerald-300 font-bold text-emerald-900 bg-emerald-50/20">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mt-4">
                        <div>
                            <label class="block text-xs font-medium text-slate-700 mb-1">{{ __('Package Count & Type') }} *</label>
                            <div class="flex gap-2">
                                <input type="number" name="package_count" required value="{{ old('package_count', $shipment->package_count) }}" class="w-20 px-3 py-2 text-xs rounded-xl border border-slate-300">
                                <input type="text" name="package_type" required value="{{ old('package_type', $shipment->package_type) }}" class="flex-1 px-3 py-2 text-xs rounded-xl border border-slate-300">
                            </div>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-slate-700 mb-1">{{ __('Target Estimated Arrival (ETA)') }}</label>
                            <input type="datetime-local" name="eta" value="{{ old('eta', $shipment->eta ? $shipment->eta->format('Y-m-d\TH:i') : '') }}"
                                   class="w-full px-3 py-2 text-xs rounded-xl border border-slate-300">
                        </div>
                    </div>
                </div>

                <!-- Consignee Updates -->
                <div class="pt-4 border-t border-slate-100">
                    <h2 class="text-xs font-bold uppercase tracking-wider text-slate-700 border-b border-slate-100 pb-2 mb-4">
                        {{ __('Consignee Contact Details') }}
                    </h2>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-medium text-slate-700 mb-1">{{ __('Receiving Business Name') }} *</label>
                            <input type="text" name="receiver_business_name" required value="{{ old('receiver_business_name', $shipment->receiver_business_name) }}"
                                   class="w-full px-3 py-2 text-xs rounded-xl border border-slate-300">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-slate-700 mb-1">{{ __('Contact Officer') }} *</label>
                            <input type="text" name="receiver_contact_name" required value="{{ old('receiver_contact_name', $shipment->receiver_contact_name) }}"
                                   class="w-full px-3 py-2 text-xs rounded-xl border border-slate-300">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-slate-700 mb-1">{{ __('Phone Number') }} *</label>
                            <input type="text" name="receiver_phone" required value="{{ old('receiver_phone', $shipment->receiver_phone) }}"
                                   class="w-full px-3 py-2 text-xs rounded-xl border border-slate-300">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-slate-700 mb-1">{{ __('Email Address') }}</label>
                            <input type="email" name="receiver_email" value="{{ old('receiver_email', $shipment->receiver_email) }}"
                                   class="w-full px-3 py-2 text-xs rounded-xl border border-slate-300">
                        </div>
                    </div>
                    <div class="mt-4">
                        <label class="block text-xs font-medium text-slate-700 mb-1">{{ __('Delivery / Handover Address') }} *</label>
                        <textarea name="receiver_address" required rows="2" class="w-full px-3 py-2 text-xs rounded-xl border border-slate-300">{{ old('receiver_address', $shipment->receiver_address) }}</textarea>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-medium text-slate-700 mb-1">{{ __('Handling Notes / Special Instructions') }}</label>
                    <textarea name="special_instructions" rows="2" class="w-full px-3 py-2 text-xs rounded-xl border border-slate-300">{{ old('special_instructions', $shipment->special_instructions) }}</textarea>
                </div>
            </div>

            <div class="flex items-center justify-end gap-3">
                <a href="{{ route('shipments.show', $shipment->id) }}" class="px-4 py-2 rounded-xl text-xs font-bold bg-white border border-slate-300 text-slate-700 hover:bg-slate-50">
                    {{ __('Cancel') }}
                </a>
                <button type="submit" class="px-5 py-2 rounded-xl text-xs font-bold bg-brand-sky text-white hover:bg-sky-700 shadow-sm">
                    {{ __('Save Changes & Recalculate') }}
                </button>
            </div>
        </form>

    </div>
</x-app-layout>
