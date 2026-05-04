<x-guest-layout>
    <div class="mb-6">
        <h2 class="text-xl font-bold text-gray-900">Create Account</h2>
        <p class="text-sm text-slate-500 mt-1">Join the lab management network.</p>
    </div>

    <form method="POST" action="{{ route('register') }}">
        @csrf

        <!-- Name -->
        <div>
            <label for="name" class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Full
                Name</label>
            <input id="name"
                class="block w-full bg-slate-50 border-transparent focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 rounded-lg sm:text-sm px-4 py-3"
                type="text" name="name" :value="old('name')" placeholder="Juan De La Cruz" required autofocus
                autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Email Address -->
        <div class="mt-5">
            <label for="email"
                class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Email</label>
            <input id="email"
                class="block w-full bg-slate-50 border-transparent focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 rounded-lg sm:text-sm px-4 py-3"
                type="email" name="email" :value="old('email')" placeholder="name@university.edu" required
                autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-5">
            <label for="password"
                class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Password</label>
            <input id="password"
                class="block w-full bg-slate-50 border-transparent focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 rounded-lg sm:text-sm px-4 py-3"
                type="password" name="password" placeholder="••••••••" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-5">
            <label for="password_confirmation"
                class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Confirm Password</label>
            <input id="password_confirmation"
                class="block w-full bg-slate-50 border-transparent focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 rounded-lg sm:text-sm px-4 py-3"
                type="password" name="password_confirmation" placeholder="••••••••" required
                autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <!-- Assigned Role / Laboratory -->
        <div class="mt-5">
            <label for="assigned_role"
                class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Assigned Role</label>
            <select id="assigned_role" name="assigned_role"
                class="block w-full bg-slate-50 border-transparent focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 rounded-lg sm:text-sm px-4 py-3"
                required>
                <option value="" disabled selected>Select role...</option>
                <option value="dean" {{ old('assigned_role') == 'dean' ? 'selected' : '' }}>College Dean</option>
                <optgroup label="Laboratories">
                    @foreach($laboratories as $lab)
                        <option value="{{ $lab->id }}" {{ old('assigned_role') == $lab->id ? 'selected' : '' }}>
                            {{ $lab->name }}
                        </option>
                    @endforeach
                </optgroup>
            </select>
            <x-input-error :messages="$errors->get('assigned_role')" class="mt-2" />
        </div>

        <div class="mt-8">
            <button type="submit" style="background-color: #0f172a; color: white;"
                class="w-full flex justify-center py-3 px-4 border border-transparent rounded-lg shadow-sm text-sm font-medium hover:bg-slate-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-slate-900 transition-colors">
                Register Account
            </button>
        </div>
    </form>

    <x-slot name="footer">
        <a class="text-xs font-bold text-slate-400 hover:text-slate-600 uppercase tracking-widest transition-colors"
            href="{{ route('login') }}">
            Already registered? Sign in
        </a>
    </x-slot>
</x-guest-layout>