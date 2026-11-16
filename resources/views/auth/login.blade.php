<x-guest-layout title="{{ __('Sign In — EcoHorizon Global') }}">
    <div class="min-h-[calc(100vh-140px)] flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-md w-full space-y-6 bg-white p-8 sm:p-10 rounded-2xl border border-slate-200/80 shadow-xl shadow-slate-200/50">
            <!-- Header (Dynamic Title & Subtitle toggled via bottom link) -->
            <div class="text-center">
                <a href="{{ route('home') }}" class="inline-block mb-3">
                    <x-logo class="h-10 w-auto" />
                </a>
                <h2 id="login-title" class="text-2xl font-bold tracking-tight text-slate-900">{{ __('Customer Portal Sign In') }}</h2>
                <p id="login-desc" class="mt-1.5 text-xs text-slate-500">
                    {{ __('Access real-time cargo visibility, milestone alerts, and consignment receipts.') }}
                </p>
            </div>

            <!-- Login Form -->
            <form class="space-y-4" action="{{ route('login') }}" method="POST">
                @csrf

                <div>
                    <label for="email" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">{{ __('Email Address') }}</label>
                    <input id="email" 
                           name="email" 
                           type="email" 
                           autocomplete="email" 
                           required 
                           autofocus
                           value="{{ old('email', 'customer@globalcargo.com') }}"
                           class="w-full px-3.5 py-2.5 rounded-xl border {{ $errors->has('email') ? 'border-red-300 ring-1 ring-red-300' : 'border-slate-300' }} text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-brand-sky focus:border-brand-sky bg-white"
                           placeholder="customer@globalcargo.com">
                    @error('email')
                        <p class="mt-1 text-xs text-red-600 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">{{ __('Password') }}</label>
                    <input id="password" 
                           name="password" 
                           type="password" 
                           autocomplete="current-password" 
                           required
                           value="password123"
                           class="w-full px-3.5 py-2.5 rounded-xl border {{ $errors->has('password') ? 'border-red-300 ring-1 ring-red-300' : 'border-slate-300' }} text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-brand-sky focus:border-brand-sky bg-white"
                           placeholder="••••••••">
                    @error('password')
                        <p class="mt-1 text-xs text-red-600 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center justify-between text-xs">
                    <label class="flex items-center text-slate-600 cursor-pointer">
                        <input type="checkbox" name="remember" class="w-4 h-4 rounded border-slate-300 text-brand-sky focus:ring-brand-sky" checked>
                        <span class="ml-2 font-medium">{{ __('Remember this device') }}</span>
                    </label>
                    <span id="badge-portal-type" class="text-[11px] font-bold text-slate-500 uppercase tracking-wider">
                        {{ __('Customer Access') }}
                    </span>
                </div>

                <div>
                    <button type="submit" 
                            class="w-full py-2.5 px-4 rounded-xl text-sm font-semibold text-white bg-gradient-to-r from-brand-sky via-brand-teal to-brand-emerald hover:opacity-95 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-brand-sky shadow-md shadow-sky-600/20 transition-all cursor-pointer">
                        {{ __('Sign In to Dashboard') }}
                    </button>
                </div>
            </form>

            <!-- Quick 1-Click Evaluation Credentials Helper -->
            <div id="demo-credentials-box" class="pt-2 border-t border-slate-100 text-xs">
                <div class="flex items-center justify-between mb-1.5">
                    <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400">
                        {{ __('Quick Evaluation Credentials (1-Click Fill)') }}:
                    </span>
                </div>
                <!-- Customer Quick Credential (Default) -->
                <div id="customer-cred-row" class="grid grid-cols-1 gap-2">
                    <button type="button" onclick="fillCredentials('customer@globalcargo.com', 'password123')"
                            class="p-2 rounded-lg border border-slate-200 hover:bg-slate-50 text-left transition-colors cursor-pointer flex items-center justify-between">
                        <div>
                            <span class="font-bold text-slate-800 block">👤 {{ __('Customer Account') }}</span>
                            <span class="text-[10px] text-slate-400 font-mono">customer@globalcargo.com</span>
                        </div>
                        <span class="text-[10px] text-brand-sky font-semibold bg-sky-50 px-2 py-0.5 rounded">{{ __('Fill') }}</span>
                    </button>
                </div>
                <!-- Staff Quick Credentials (Shown when in Staff Mode) -->
                <div id="staff-cred-row" class="hidden grid-cols-2 gap-2">
                    <button type="button" onclick="fillCredentials('admin@ecohorizon.com', 'password123')"
                            class="p-2 rounded-lg border border-slate-200 hover:bg-slate-50 text-left transition-colors cursor-pointer">
                        <span class="font-bold text-slate-800 block">🏢 Administrator</span>
                        <span class="text-[10px] text-slate-400 font-mono">admin@ecohorizon.com</span>
                    </button>
                    <button type="button" onclick="fillCredentials('operator@ecohorizon.com', 'password123')"
                            class="p-2 rounded-lg border border-slate-200 hover:bg-slate-50 text-left transition-colors cursor-pointer">
                        <span class="font-bold text-slate-800 block">🚚 Operator</span>
                        <span class="text-[10px] text-slate-400 font-mono">operator@ecohorizon.com</span>
                    </button>
                </div>
            </div>

            <!-- Create Account Box (For Customers) -->
            <div id="customer-register-hint" class="text-center text-xs text-slate-600 pt-2 border-t border-slate-100">
                <span>{{ __("Don't have a customer account?") }}</span>
                <a href="{{ route('register') }}" class="font-bold text-brand-sky hover:underline ml-1">
                    {{ __('Create an Account') }} &rarr;
                </a>
            </div>

            <!-- Bottom Navigation Bar (Back to Tracking & Staff Toggle) -->
            <div class="flex items-center justify-between text-xs text-slate-500 pt-3 border-t border-slate-100">
                <a href="{{ route('home') }}" class="hover:text-brand-sky transition-colors inline-flex items-center gap-1">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                    <span>{{ __('Back to Tracking') }}</span>
                </a>

                <button type="button" id="toggle-staff-link" onclick="togglePortalMode()" class="text-brand-sky hover:underline font-semibold cursor-pointer">
                    {{ __('Login as Staff / Enterprise') }} &rarr;
                </button>
            </div>
        </div>
    </div>

    <script>
        let currentMode = 'customer';

        function switchPortalMode(mode) {
            currentMode = mode;
            const title = document.getElementById('login-title');
            const desc = document.getElementById('login-desc');
            const badge = document.getElementById('badge-portal-type');
            const registerHint = document.getElementById('customer-register-hint');
            const customerCredRow = document.getElementById('customer-cred-row');
            const staffCredRow = document.getElementById('staff-cred-row');
            const toggleLink = document.getElementById('toggle-staff-link');
            const emailInput = document.getElementById('email');

            if (mode === 'customer') {
                title.innerText = @json(__('Customer Portal Sign In'));
                desc.innerText = @json(__('Access real-time cargo visibility, milestone alerts, and consignment receipts.'));
                badge.innerText = @json(__('Customer Access'));
                registerHint.classList.remove('hidden');
                customerCredRow.classList.remove('hidden');
                customerCredRow.classList.add('grid');
                staffCredRow.classList.add('hidden');
                staffCredRow.classList.remove('grid');
                toggleLink.innerText = @json(__('Login as Staff / Enterprise')) + ' →';
                emailInput.placeholder = 'customer@globalcargo.com';
                fillCredentials('customer@globalcargo.com', 'password123');
            } else {
                title.innerText = @json(__('Staff & Enterprise Sign In'));
                desc.innerText = @json(__('Manage logistics corridors, update telemetry checkpoints, and resolve exceptions.'));
                badge.innerText = @json(__('Enterprise Staff Access'));
                registerHint.classList.add('hidden');
                customerCredRow.classList.add('hidden');
                customerCredRow.classList.remove('grid');
                staffCredRow.classList.remove('hidden');
                staffCredRow.classList.add('grid');
                toggleLink.innerText = '← ' + @json(__('Login as Customer'));
                emailInput.placeholder = 'admin@ecohorizon.com';
                fillCredentials('admin@ecohorizon.com', 'password123');
            }
        }

        function togglePortalMode() {
            switchPortalMode(currentMode === 'customer' ? 'staff' : 'customer');
        }

        function fillCredentials(email, pwd) {
            document.getElementById('email').value = email;
            document.getElementById('password').value = pwd;
        }
    </script>
</x-guest-layout>
