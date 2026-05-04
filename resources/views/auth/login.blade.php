<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <div class="mb-6">
        <h2 class="text-xl font-bold text-gray-900">Sign In</h2>
        <p class="text-sm text-slate-500 mt-1">Welcome back. Please enter your details.</p>
    </div>

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email Address -->
        <div>
            <label for="email"
                class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Email</label>
            <input id="email"
                class="block w-full bg-slate-50 border-transparent focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 rounded-lg sm:text-sm px-4 py-3"
                type="email" name="email" :value="old('email')" placeholder="name@university.edu" required autofocus
                autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-5">
            <label for="password"
                class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Password</label>
            <input id="password"
                class="block w-full bg-slate-50 border-transparent focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 rounded-lg sm:text-sm px-4 py-3"
                type="password" name="password" placeholder="••••••••" required autocomplete="current-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div class="mt-8">
            <button type="submit" style="background-color: #0f172a; color: white;"
                class="w-full flex justify-center py-3 px-4 border border-transparent rounded-lg shadow-sm text-sm font-medium hover:bg-slate-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-slate-900 transition-colors">
                Sign In
            </button>
        </div>
    </form>

    <x-slot name="footer">
        <a class="text-xs font-bold text-slate-400 hover:text-slate-600 uppercase tracking-widest transition-colors"
            href="{{ route('register') }}">
            Need an account? Sign up
        </a>
    </x-slot>
</x-guest-layout>