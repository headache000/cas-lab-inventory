<aside class="w-64 bg-white border-r border-gray-100 flex flex-col h-full flex-shrink-0 shadow-sm z-10">
    <!-- Header / Logo -->
    <div class="pt-8 pb-6 px-6 flex flex-col items-center border-b border-gray-50">
        <div class="flex items-center space-x-3 mb-4">
            <!-- College Logo (CAS) -->
            <div class="w-16 h-16 bg-white rounded-full border border-gray-200 shadow-sm overflow-hidden flex items-center justify-center p-1">
                <img src="{{ asset('images/cas.jpg') }}" alt="CAS Logo" class="w-full h-full object-contain rounded-full" onerror="this.style.display='none'; this.parentElement.innerHTML='<span class=\'text-xs font-bold\'>CAS</span>';"/>
            </div>
            <!-- Main System Logo -->
            <div class="w-16 h-16 bg-white rounded-full border border-gray-200 shadow-sm overflow-hidden flex items-center justify-center p-1">
                <img src="{{ asset('images/lab-inventory.png') }}" alt="System Logo" class="w-full h-full object-contain rounded-full" onerror="this.style.display='none'; this.parentElement.innerHTML='<span class=\'text-xs font-bold\'>SYS</span>';"/>
            </div>
        </div>
        <h1 class="text-sm font-bold text-gray-800 tracking-wide text-center mt-2">
            @if(Auth::user()->role === 'admin')
                {{ Auth::user()->laboratory->name ?? 'Laboratory' }}
            @else
                @if(isset($selectedLab) && $selectedLab)
                    {{ $selectedLab->name }}
                @else
                    Lab Inventory Management System
                @endif
            @endif
        </h1>
        <p class="text-xs font-semibold text-gray-400 mt-1 uppercase tracking-wider">
            {{ Auth::user()->role === 'dean' ? 'DEAN PORTAL' : 'ADMIN HUB' }}
        </p>
    </div>

    <!-- Navigation Links -->
    <div class="flex-1 flex flex-col overflow-hidden py-6 px-4">
        <!-- Control Laboratory Section (Dean Only) -->
        @if(Auth::user()->role === 'dean')
        <div class="mb-8 flex-1 flex flex-col min-h-0">
            <h2 class="text-xs font-bold text-gray-400 tracking-wider uppercase mb-3 px-3 flex-shrink-0">Control Laboratory</h2>
            <nav class="space-y-1 flex-1 overflow-y-auto no-scrollbar pb-2">
                <a href="{{ route('dashboard') }}" class="flex items-center px-3 py-2.5 {{ !request()->has('lab_id') && request()->routeIs('dashboard') ? 'bg-indigo-600 text-white shadow-sm' : 'text-gray-500 hover:text-gray-900 hover:bg-gray-50' }} rounded-lg text-sm font-medium transition-colors flex-shrink-0 mb-1">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
                    Global Overview
                </a>
                
                @php
                    $labs = \App\Models\Laboratory::all();
                @endphp
                
                @foreach($labs as $lab)
                <a href="{{ route('dashboard', ['lab_id' => $lab->id]) }}" class="flex items-center px-3 py-2 {{ request('lab_id') == $lab->id ? 'bg-indigo-50 text-indigo-700' : 'text-gray-500 hover:text-gray-900 hover:bg-gray-50' }} rounded-lg text-sm font-medium transition-colors flex-shrink-0">
                    <svg class="w-4 h-4 mr-3 {{ request('lab_id') == $lab->id ? 'text-indigo-600' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path></svg>
                    {{ $lab->name }}
                </a>
                @endforeach
            </nav>
        </div>
        @endif

        <!-- Management Section -->
        <div class="flex-shrink-0">
            <h2 class="text-xs font-bold text-gray-400 tracking-wider uppercase mb-3 px-3">Management</h2>
            <nav class="space-y-1">
                <a href="{{ route('dashboard', request()->has('lab_id') ? ['lab_id' => request('lab_id')] : []) }}" class="flex items-center px-3 py-2.5 {{ request()->routeIs('dashboard') ? 'bg-gray-900 text-white shadow-sm' : 'text-gray-500 hover:text-gray-900 hover:bg-gray-50' }} rounded-lg text-sm font-medium transition-colors">
                    <svg class="w-5 h-5 mr-3 {{ request()->routeIs('dashboard') ? 'text-gray-300' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
                    Dashboard
                </a>
                <a href="{{ route('inventory.index', request()->has('lab_id') ? ['lab_id' => request('lab_id')] : []) }}" class="flex items-center px-3 py-2.5 {{ request()->routeIs('inventory.*') ? 'bg-gray-900 text-white shadow-sm' : 'text-gray-500 hover:text-gray-900 hover:bg-gray-50' }} rounded-lg text-sm font-medium transition-colors">
                    <svg class="w-5 h-5 mr-3 {{ request()->routeIs('inventory.*') ? 'text-gray-300' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                    Inventory
                </a>
                <a href="{{ route('borrow.index', request()->has('lab_id') ? ['lab_id' => request('lab_id')] : []) }}" class="flex items-center px-3 py-2.5 {{ request()->routeIs('borrow.*') ? 'bg-gray-900 text-white shadow-sm' : 'text-gray-500 hover:text-gray-900 hover:bg-gray-50' }} rounded-lg text-sm font-medium transition-colors">
                    <svg class="w-5 h-5 mr-3 {{ request()->routeIs('borrow.*') ? 'text-gray-300' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path></svg>
                    Borrow / Return
                </a>
                <a href="{{ route('reports.index', request()->has('lab_id') ? ['lab_id' => request('lab_id')] : []) }}" class="flex items-center px-3 py-2.5 {{ request()->routeIs('reports.*') ? 'bg-gray-900 text-white shadow-sm' : 'text-gray-500 hover:text-gray-900 hover:bg-gray-50' }} rounded-lg text-sm font-medium transition-colors">
                    <svg class="w-5 h-5 mr-3 {{ request()->routeIs('reports.*') ? 'text-gray-300' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                    Reports
                </a>
            </nav>
        </div>
    </div>

    <!-- User Profile & Sign Out -->
    <div class="p-4 border-t border-gray-100">
        <div class="flex items-center px-3 py-2">
            <div class="h-10 w-10 rounded-lg bg-gray-100 flex items-center justify-center text-gray-600 font-bold uppercase text-sm border border-gray-200">
                {{ substr(Auth::user()->name, 0, 1) }}
            </div>
            <div class="ml-3">
                <p class="text-sm font-bold text-gray-900">{{ Auth::user()->name }}</p>
                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wide">{{ Auth::user()->role }}</p>
            </div>
        </div>
        <div class="mt-3">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full flex items-center justify-center px-4 py-2 text-sm font-bold text-gray-500 hover:text-gray-800 transition-colors uppercase tracking-wider">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                    Sign Out
                </button>
            </form>
        </div>
    </div>
</aside>
<style>
/* Hide scrollbar for Chrome, Safari and Opera */
.no-scrollbar::-webkit-scrollbar {
    display: none;
}
/* Hide scrollbar for IE, Edge and Firefox */
.no-scrollbar {
    -ms-overflow-style: none;  /* IE and Edge */
    scrollbar-width: none;  /* Firefox */
}
</style>
