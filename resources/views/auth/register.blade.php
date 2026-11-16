<x-guest-layout title="{{ __('Create Customer Account — EcoHorizon Global') }}">
    <div class="min-h-[calc(100vh-140px)] flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-md w-full space-y-6 bg-white p-8 sm:p-10 rounded-2xl border border-slate-200/80 shadow-xl shadow-slate-200/50">
            <!-- Header -->
            <div class="text-center">
                <a href="{{ route('home') }}" class="inline-block mb-3">
                    <x-logo class="h-10 w-auto" />
                </a>
                <h2 class="text-2xl font-bold tracking-tight text-slate-900">{{ __('Create Customer Account') }}</h2>
                <p class="mt-2 text-sm text-slate-500">
                    {{ __('Register to track consignments, inspect telemetry, and manage multimodal shipments.') }}
                </p>
            </div>

            <!-- Registration Form -->
            <form class="space-y-4" action="{{ route('register') }}" method="POST">
                @csrf

                <div>
                    <label for="name" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">{{ __('Full Name') }} <span class="text-red-500">*</span></label>
                    <input id="name" 
                           name="name" 
                           type="text" 
                           required 
                           autofocus
                           value="{{ old('name') }}"
                           class="w-full px-3.5 py-2.5 rounded-xl border {{ $errors->has('name') ? 'border-red-300 ring-1 ring-red-300' : 'border-slate-300' }} text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-brand-sky focus:border-brand-sky bg-white"
                           placeholder="Alexander Vance">
                    @error('name')
                        <p class="mt-1 text-xs text-red-600 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="company_name" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">{{ __('Company / Business Name') }} <span class="text-red-500">*</span></label>
                    <input id="company_name" 
                           name="company_name" 
                           type="text" 
                           required 
                           value="{{ old('company_name') }}"
                           class="w-full px-3.5 py-2.5 rounded-xl border {{ $errors->has('company_name') ? 'border-red-300 ring-1 ring-red-300' : 'border-slate-300' }} text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-brand-sky focus:border-brand-sky bg-white"
                           placeholder="Global Cargo Logistics Ltd.">
                    @error('company_name')
                        <p class="mt-1 text-xs text-red-600 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="email" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">{{ __('Email Address') }} <span class="text-red-500">*</span></label>
                    <input id="email" 
                           name="email" 
                           type="email" 
                           autocomplete="email" 
                           required 
                           value="{{ old('email') }}"
                           class="w-full px-3.5 py-2.5 rounded-xl border {{ $errors->has('email') ? 'border-red-300 ring-1 ring-red-300' : 'border-slate-300' }} text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-brand-sky focus:border-brand-sky bg-white"
                           placeholder="alexander@globalcargo.com">
                    @error('email')
                        <p class="mt-1 text-xs text-red-600 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="phone" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">{{ __('Phone Number') }}</label>
                    <input id="phone" 
                           name="phone" 
                           type="tel" 
                           value="{{ old('phone') }}"
                           class="w-full px-3.5 py-2.5 rounded-xl border {{ $errors->has('phone') ? 'border-red-300 ring-1 ring-red-300' : 'border-slate-300' }} text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-brand-sky focus:border-brand-sky bg-white"
                           placeholder="+1 (555) 019-2834">
                    @error('phone')
                        <p class="mt-1 text-xs text-red-600 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label for="password" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">{{ __('Password') }} <span class="text-red-500">*</span></label>
                        <input id="password" 
                               name="password" 
                               type="password" 
                               required
                               class="w-full px-3.5 py-2.5 rounded-xl border {{ $errors->has('password') ? 'border-red-300 ring-1 ring-red-300' : 'border-slate-300' }} text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-brand-sky focus:border-brand-sky bg-white"
                               placeholder="••••••••">
                    </div>
                    <div>
                        <label for="password_confirmation" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">{{ __('Confirm Password') }} <span class="text-red-500">*</span></label>
                        <input id="password_confirmation" 
                               name="password_confirmation" 
                               type="password" 
                               required
                               class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-brand-sky focus:border-brand-sky bg-white"
                               placeholder="••••••••">
                    </div>
                </div>
                @error('password')
                    <p class="mt-1 text-xs text-red-600 font-medium">{{ $message }}</p>
                @enderror

                <div class="pt-2">
                    <button type="submit" 
                            class="w-full py-2.5 px-4 rounded-xl text-sm font-semibold text-white bg-gradient-to-r from-brand-sky via-brand-teal to-brand-emerald hover:opacity-95 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-brand-sky shadow-md shadow-sky-600/20 transition-all cursor-pointer">
                        {{ __('Create Account') }}
                    </button>
                </div>
            </form>

            <div class="text-center text-xs text-slate-500 pt-2 border-t border-slate-100">
                <span>{{ __('Already have an account?') }}</span>
                <a href="{{ route('login') }}" class="font-bold text-brand-sky hover:underline ml-1">
                    {{ __('Sign In') }}
                </a>
            </div>

            <div class="text-center pt-1">
                <a href="{{ route('home') }}" class="text-xs text-slate-500 hover:text-brand-sky transition-colors inline-flex items-center gap-1">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                    <span>{{ __('Back to Public Tracking & Landing Page') }}</span>
                </a>
            </div>
        </div>
    </div>
</x-guest-layout>
