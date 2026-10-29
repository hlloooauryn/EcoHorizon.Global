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

            <!-- 1-Click Role Quick Logins (3 Role: Admin, Staff, User) -->
            <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200">
                <div class="text-xs font-bold text-slate-700 uppercase tracking-wider mb-2.5 flex items-center justify-between">
                    <span class="flex items-center gap-1.5">
                        <span class="w-2 h-2 rounded-full bg-brand-sky"></span>
                        Login 3 Role (Klik untuk Masuk)
                    </span>
                    <span class="text-[10px] text-slate-400 font-normal">Pilih Peran</span>
                </div>
                <div class="grid grid-cols-3 gap-2">
                    <!-- Role 1: Admin -->
                    <button type="button" 
                            id="btn-role-admin"
                            onclick="fillDemo('admin@ecohorizon.test', 'password123', 'admin')"
                            class="px-2.5 py-2.5 rounded-xl text-xs font-bold bg-white border border-purple-200 text-purple-700 hover:bg-purple-50 transition-all shadow-2xs text-center flex flex-col items-center gap-1">
                        <span class="w-2 h-2 rounded-full bg-purple-500"></span>
                        <span>Admin</span>
                        <span class="text-[9px] font-normal text-slate-400">Executive</span>
                    </button>
                    <!-- Role 2: Staff -->
                    <button type="button" 
                            id="btn-role-staff"
                            onclick="fillDemo('operator@ecohorizon.test', 'password123', 'staff')"
                            class="px-2.5 py-2.5 rounded-xl text-xs font-bold bg-white border border-sky-200 text-sky-700 hover:bg-sky-50 transition-all shadow-2xs text-center flex flex-col items-center gap-1">
                        <span class="w-2 h-2 rounded-full bg-sky-500"></span>
                        <span>Staff</span>
                        <span class="text-[9px] font-normal text-slate-400">Logistics</span>
                    </button>
                    <!-- Role 3: User -->
                    <button type="button" 
                            id="btn-role-user"
                            onclick="fillDemo('customer@globalcargo.test', 'password123', 'user')"
                            class="px-2.5 py-2.5 rounded-xl text-xs font-bold bg-white border border-emerald-200 text-emerald-700 hover:bg-emerald-50 transition-all shadow-2xs text-center flex flex-col items-center gap-1">
                        <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                        <span>User</span>
                        <span class="text-[9px] font-normal text-slate-400">Customer</span>
                    </button>
                </div>
                <div class="text-[11px] text-slate-400 mt-2.5 text-center flex items-center justify-center gap-1">
                    <span>Default Password:</span>
                    <span class="font-mono text-slate-700 font-semibold bg-white px-2 py-0.5 rounded border border-slate-200">password123</span>
                </div>
            </div>

            <!-- Login Form -->
            <form class="mt-6 space-y-5" action="{{ route('login') }}" method="POST">
                @csrf

                <div>
                    <label for="email" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">Email Address</label>
                    <input id="email" name="email" type="email" autocomplete="email" required 
                           value="{{ old('email', 'admin@ecohorizon.test') }}"
                           class="w-full px-3.5 py-2.5 rounded-xl border {{ $errors->has('email') ? 'border-red-300 ring-1 ring-red-300' : 'border-slate-300' }} text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-brand-sky focus:border-brand-sky bg-white"
                           placeholder="name@company.com">
                    @error('email')
                        <p class="mt-1 text-xs text-red-600 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">Password</label>
                    <input id="password" name="password" type="password" autocomplete="current-password" required
                           value="password123"
                           class="w-full px-3.5 py-2.5 rounded-xl border {{ $errors->has('password') ? 'border-red-300 ring-1 ring-red-300' : 'border-slate-300' }} text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-brand-sky focus:border-brand-sky bg-white"
                           placeholder="••••••••">
                    @error('password')
                        <p class="mt-1 text-xs text-red-600 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center justify-between text-xs">
                    <label class="flex items-center text-slate-600">
                        <input type="checkbox" name="remember" class="w-4 h-4 rounded border-slate-300 text-brand-sky focus:ring-brand-sky">
                        <span class="ml-2">Remember this device</span>
                    </label>
                    <span class="text-slate-400">Local University Demo Mode</span>
                </div>

                <div>
                    <button type="submit" 
                            class="w-full py-2.5 px-4 rounded-xl text-sm font-semibold text-white bg-gradient-to-r from-brand-sky via-brand-teal to-brand-emerald hover:opacity-95 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-brand-sky shadow-md shadow-sky-600/20 transition-all">
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

    <script>
        function fillDemo(email, password, role) {
            document.getElementById('email').value = email;
            document.getElementById('password').value = password;

            ['admin', 'staff', 'user'].forEach(function(r) {
                const btn = document.getElementById('btn-role-' + r);
                if (btn) {
                    if (r === role) {
                        btn.classList.add('ring-2', 'ring-brand-sky', 'bg-slate-100');
                    } else {
                        btn.classList.remove('ring-2', 'ring-brand-sky', 'bg-slate-100');
                    }
                }
            });
        }
    </script>
</x-guest-layout>

