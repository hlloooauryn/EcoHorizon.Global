<x-app-layout :title="__('Create User — EcoHorizon Global')">
    <x-slot:header>
        {{ __('Add System User') }}
    </x-slot:header>

    <div class="max-w-2xl mx-auto space-y-6">
        <div class="pb-4 border-b border-slate-200">
            <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight">{{ __('Add System User') }}</h1>
            <p class="text-sm text-slate-500 mt-1">{{ __('Provision a new user account with role-based platform permissions.') }}</p>
        </div>

        <form action="{{ route('admin.users.store') }}" method="POST" class="bg-white p-6 sm:p-8 rounded-2xl border border-slate-200 shadow-2xs space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">{{ __('Full Name') }} *</label>
                <input type="text" name="name" required value="{{ old('name') }}" class="w-full px-3 py-2 text-xs rounded-xl border border-slate-300">
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">{{ __('Email Address') }} *</label>
                <input type="email" name="email" required value="{{ old('email') }}" class="w-full px-3 py-2 text-xs rounded-xl border border-slate-300">
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">{{ __('Password') }} *</label>
                <input type="password" name="password" required class="w-full px-3 py-2 text-xs rounded-xl border border-slate-300" placeholder="{{ __('Minimum 8 characters') }}">
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">{{ __('Role') }} *</label>
                    <select name="role" required class="w-full py-2 px-3 text-xs rounded-xl border border-slate-300 bg-white">
                        <option value="customer" {{ old('role') === 'customer' ? 'selected' : '' }}>{{ __('Customer') }}</option>
                        <option value="operator" {{ old('role') === 'operator' ? 'selected' : '' }}>{{ __('Logistics Operator') }}</option>
                        <option value="admin" {{ old('role') === 'admin' ? 'selected' : '' }}>{{ __('Administrator') }}</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">{{ __('Account Status') }} *</label>
                    <select name="status" required class="w-full py-2 px-3 text-xs rounded-xl border border-slate-300 bg-white">
                        <option value="active">{{ __('Active') }}</option>
                        <option value="suspended">{{ __('Suspended') }}</option>
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-medium text-slate-700 mb-1">{{ __('Company / Business Name') }}</label>
                    <input type="text" name="company_name" value="{{ old('company_name') }}" class="w-full px-3 py-2 text-xs rounded-xl border border-slate-300">
                </div>
                <div>
                    <label class="block text-xs font-medium text-slate-700 mb-1">{{ __('Phone Number') }}</label>
                    <input type="text" name="phone" value="{{ old('phone') }}" class="w-full px-3 py-2 text-xs rounded-xl border border-slate-300">
                </div>
            </div>

            <div class="flex justify-end gap-3 pt-4 border-t border-slate-100">
                <a href="{{ route('admin.users.index') }}" class="px-4 py-2 rounded-xl text-xs font-bold bg-slate-100 text-slate-700">{{ __('Cancel') }}</a>
                <button type="submit" class="px-5 py-2 rounded-xl text-xs font-bold bg-brand-sky text-white">{{ __('Create User') }}</button>
            </div>
        </form>
    </div>
</x-app-layout>
