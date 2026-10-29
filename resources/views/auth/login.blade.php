<x-guest-layout title="Sign In — EcoHorizon Global">
    <div class="min-h-[calc(100vh-140px)] flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-md w-full space-y-8 bg-white p-8 sm:p-10 rounded-2xl border border-slate-200/80 shadow-xl shadow-slate-200/50">
            <!-- Header -->
            <div class="text-center">
                <a href="{{ route('home') }}" class="inline-block mb-3">
                    <x-logo class="h-10 w-auto" />
                </a>
                <h2 class="text-2xl font-bold tracking-tight text-slate-900">Sign in to your portal</h2>
                <p class="mt-2 text-sm text-slate-500">
                    Access freight visibility, shipment milestones, and emission analytics.
                </p>
            </div>

            <!-- Login Form -->
            <form class="mt-6 space-y-5" action="{{ route('login') }}" method="POST">
                @csrf

                <div>
                    <label for="email" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">Email Address</label>
                    <input id="email" 
                           name="email" 
                           type="email" 
                           autocomplete="email" 
                           required 
                           autofocus
                           value="{{ old('email') }}"
                           class="w-full px-3.5 py-2.5 rounded-xl border {{ $errors->has('email') ? 'border-red-300 ring-1 ring-red-300' : 'border-slate-300' }} text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-brand-sky focus:border-brand-sky bg-white"
                           placeholder="name@company.com">
                    @error('email')
                        <p class="mt-1 text-xs text-red-600 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">Password</label>
                    <input id="password" 
                           name="password" 
                           type="password" 
                           autocomplete="current-password" 
                           required
                           class="w-full px-3.5 py-2.5 rounded-xl border {{ $errors->has('password') ? 'border-red-300 ring-1 ring-red-300' : 'border-slate-300' }} text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-brand-sky focus:border-brand-sky bg-white"
                           placeholder="••••••••">
                    @error('password')
                        <p class="mt-1 text-xs text-red-600 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center justify-between text-xs">
                    <label class="flex items-center text-slate-600 cursor-pointer">
                        <input type="checkbox" name="remember" class="w-4 h-4 rounded border-slate-300 text-brand-sky focus:ring-brand-sky">
                        <span class="ml-2 font-medium">Remember this device</span>
                    </label>
                    <span class="text-slate-400">Enterprise Access</span>
                </div>

                <div>
                    <button type="submit" 
                            class="w-full py-2.5 px-4 rounded-xl text-sm font-semibold text-white bg-gradient-to-r from-brand-sky via-brand-teal to-brand-emerald hover:opacity-95 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-brand-sky shadow-md shadow-sky-600/20 transition-all cursor-pointer">
                        Sign In to Dashboard
                    </button>
                </div>
            </form>

            <div class="text-center pt-2">
                <a href="{{ route('home') }}" class="text-xs text-slate-500 hover:text-brand-sky transition-colors flex items-center justify-center gap-1">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                    Back to Public Tracking & Landing Page
                </a>
            </div>
        </div>
    </div>
</x-guest-layout>
