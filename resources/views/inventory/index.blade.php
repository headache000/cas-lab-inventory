<x-app-layout>
    <!-- Page Title & Actions -->
    <div class="flex justify-between items-start mb-8">
        <div class="flex flex-col">
            <h2 class="text-3xl font-extrabold text-[#0f172a] tracking-tight">
                Equipment Inventory
            </h2>
            <p class="text-[11px] font-bold text-gray-400 tracking-widest uppercase mt-2">
                Active Records for {{ Auth::user()->laboratory->name ?? 'Laboratory' }}
            </p>
        </div>
        
        <div class="flex items-center space-x-3">
            <a href="{{ route('inventory.export', request()->query()) }}" class="flex items-center px-4 py-2 bg-white border border-gray-200 rounded-lg shadow-sm text-xs font-bold text-gray-700 hover:bg-gray-50 transition-colors tracking-wide">
                <svg class="w-3.5 h-3.5 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                EXPORT
            </a>
            @if(Auth::user()->role === 'admin')
            <button x-data @click="$dispatch('open-add-modal')" class="flex items-center px-4 py-2 bg-[#0f172a] border border-transparent rounded-lg shadow-sm text-xs font-bold text-white hover:bg-gray-800 transition-colors tracking-wide">
                + ADD EQUIPMENT
            </button>
            @endif
        </div>
    </div>

    <!-- Search & Filter Bar -->
    <form action="{{ route('inventory.index') }}" method="GET" class="bg-white rounded-xl shadow-sm border border-gray-100 p-2 mb-8 flex flex-col sm:flex-row items-center justify-between">
        <div class="flex-1 w-full relative flex items-center">
            <svg class="w-5 h-5 text-gray-400 absolute left-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search PAR, Property, or Model..." class="w-full pl-12 pr-4 py-2.5 border-0 focus:ring-0 text-sm text-gray-900 placeholder-gray-400 bg-transparent" />
        </div>
        <div class="flex items-center border-t sm:border-t-0 sm:border-l border-gray-100 pl-4 pr-2 py-2 mt-2 sm:mt-0 w-full sm:w-auto">
            <select name="category" onchange="this.form.submit()" class="border-0 focus:ring-0 text-sm font-bold text-gray-600 bg-transparent uppercase tracking-wider cursor-pointer pr-8">
                <option value="">Categories</option>
                <option value="Computer" @selected(request('category') == 'Computer')>Computer</option>
                <option value="Monitor" @selected(request('category') == 'Monitor')>Monitor</option>
                <option value="Printer" @selected(request('category') == 'Printer')>Printer</option>
                <option value="Microscope" @selected(request('category') == 'Microscope')>Microscope</option>
                <option value="Lab Kit" @selected(request('category') == 'Lab Kit')>Lab Kit</option>
                <option value="Chemical Container" @selected(request('category') == 'Chemical Container')>Chemical Container</option>
                <option value="Furniture" @selected(request('category') == 'Furniture')>Furniture</option>
                <option value="Network Device" @selected(request('category') == 'Network Device')>Network Device</option>
                <option value="Others" @selected(request('category') == 'Others')>Others</option>
            </select>
            <button type="submit" class="ml-3 p-2 text-gray-400 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition-colors border border-gray-200">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path></svg>
            </button>
            @if(request()->hasAny(['search', 'category']))
                <a href="{{ route('inventory.index') }}" class="ml-2 p-2 text-red-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors border border-red-200" title="Clear Filters">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </a>
            @endif
        </div>
    </form>

    <!-- Inventory Table -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 flex flex-col">
        <div class="px-6 py-5 border-b border-gray-50 flex justify-between items-center">
            <h3 class="text-xs font-bold text-gray-400 uppercase tracking-wider">Inventory List</h3>
            <span class="inline-flex items-center px-2.5 py-0.5 rounded text-xs font-bold bg-gray-50 text-gray-500 border border-gray-200">
                {{ $inventory->count() }} items
            </span>
        </div>
        
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-100">
                <thead class="bg-white">
                    <tr>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">Identification</th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">Item & Category</th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">Acquisition</th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">Laboratory</th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">Status</th>
                        @if(Auth::user()->role === 'admin')
                        <th scope="col" class="px-6 py-4 text-right text-xs font-bold text-gray-400 uppercase tracking-wider">Actions</th>
                        @endif
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-50">
                    @forelse($inventory as $item)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="block text-sm font-bold text-gray-900">{{ $item->par_number ?? 'N/A' }}</span>
                                <span class="block text-xs font-semibold text-gray-400 uppercase mt-0.5">PROP: {{ $item->property_number ?? 'N/A' }}</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="block text-sm font-bold text-gray-900">{{ $item->item_name }}</span>
                                <span class="block text-xs font-semibold text-gray-400 uppercase mt-0.5">{{ $item->category ?? 'UNCATEGORIZED' }}</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-600">
                                {{ $item->acquired_date ? \Carbon\Carbon::parse($item->acquired_date)->format('M d, Y') : 'N/A' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="text-xs font-bold text-gray-600 uppercase tracking-wider">
                                    {{ $item->laboratory->name ?? 'None' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($item->status === 'working')
                                    <span class="inline-flex items-center px-2.5 py-1 rounded text-xs font-bold bg-emerald-50 text-emerald-600 uppercase tracking-wider">
                                        Available
                                    </span>
                                @elseif($item->status === 'disposed')
                                    <span class="inline-flex items-center px-2.5 py-1 rounded text-xs font-bold bg-gray-100 text-gray-600 uppercase tracking-wider">
                                        Disposed
                                    </span>
                                @elseif($item->status === 'under_repair')
                                    <span class="inline-flex items-center px-2.5 py-1 rounded text-xs font-bold bg-orange-50 text-orange-500 uppercase tracking-wider">
                                        Under Repair
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-1 rounded text-xs font-bold bg-red-50 text-red-600 uppercase tracking-wider">
                                        {{ $item->status }}
                                    </span>
                                @endif
                            </td>
                            @if(Auth::user()->role === 'admin')
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex items-center justify-end space-x-3">
                                    <button x-data type="button" data-item="{{ $item }}" @click="$dispatch('open-edit-modal', JSON.parse($el.dataset.item))" class="text-indigo-500 hover:text-indigo-900 p-1 hover:bg-indigo-50 rounded transition-colors" title="Edit">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                    </button>
                                    <form action="{{ route('inventory.destroy', $item) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this equipment record?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-500 hover:text-red-900 p-1 hover:bg-red-50 rounded transition-colors" title="Delete">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                            @endif
                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ Auth::user()->role === 'admin' ? 6 : 5 }}" class="px-6 py-16 whitespace-nowrap text-center">
                                <div class="flex flex-col items-center justify-center">
                                    <div class="bg-gray-50 rounded-full p-4 mb-4">
                                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>
                                    </div>
                                    <p class="text-gray-900 font-bold text-lg mb-1">No equipment found</p>
                                    <p class="text-gray-500 text-sm mb-6">Get started by adding a new record.</p>
                                    @if(Auth::user()->role === 'admin')
                                    <button x-data @click="$dispatch('open-add-modal')" class="inline-flex items-center px-5 py-2.5 bg-[#0f172a] hover:bg-gray-800 text-white text-sm font-bold rounded-xl transition-colors tracking-wide shadow-sm">
                                        + ADD EQUIPMENT
                                    </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    </div>

    <!-- Add/Edit Equipment Modal -->
    <div x-data="{ 
            showModal: {{ $errors->any() ? 'true' : 'false' }},
            isEdit: false,
            actionUrl: '{{ route('inventory.store') }}',
            form: { par_number: '', property_number: '', item_name: '', category: '', model: '', acquired_date: '', amount: '', fund: '', status: 'working', description: '' },
            init() {
                const urlParams = new URLSearchParams(window.location.search);
                if(urlParams.get('action') === 'add' && !this.showModal) {
                    setTimeout(() => { $dispatch('open-add-modal'); }, 100);
                    // Clear the parameter to avoid reopening on refresh
                    window.history.replaceState({}, document.title, window.location.pathname);
                }
            }
        }" 
        @open-add-modal.window="
            showModal = true; 
            isEdit = false; 
            actionUrl = '{{ route('inventory.store') }}';
            form = { par_number: '', property_number: '', item_name: '', category: '', model: '', acquired_date: '', amount: '', fund: '', status: 'working', description: '' };
        " 
        @open-edit-modal.window="
            showModal = true; 
            isEdit = true; 
            actionUrl = '/inventory/' + $event.detail.id;
            form = { ...$event.detail };
            if(form.acquired_date) {
                form.acquired_date = form.acquired_date.split(' ')[0];
            }
        "
        x-show="showModal" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <!-- Background overlay -->
            <div x-show="showModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-gray-900 bg-opacity-50 backdrop-blur-sm transition-opacity" aria-hidden="true"></div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <!-- Modal panel -->
            <div x-show="showModal" @click.away="showModal = false" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full">
                
                <div class="bg-white px-8 pt-6 pb-2">
                    <div class="flex justify-between items-center">
                        <h3 class="text-xl font-bold text-gray-900" id="modal-title" x-text="isEdit ? 'Edit Equipment' : 'Add New Equipment'">
                        </h3>
                        <button type="button" @click="showModal = false" class="text-gray-400 hover:text-gray-600 transition-colors">
                            <span class="sr-only">Close</span>
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                        </button>
                    </div>
                </div>

                <form :action="actionUrl" method="POST">
                    @csrf
                    <input type="hidden" name="_method" value="PUT" x-bind:disabled="!isEdit">
                    <div class="bg-white px-8 pt-4 pb-8">
                        @if ($errors->any())
                            <div class="mb-6 bg-red-50 p-4 rounded-xl border border-red-100">
                                <ul class="list-disc list-inside text-sm font-medium text-red-600">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-5">
                            
                            <!-- Item Model -->
                            <div>
                                <label for="item_name" class="block text-[11px] font-bold text-gray-500 uppercase tracking-wider mb-2">Item Model</label>
                                <input type="text" name="item_name" id="item_name" x-model="form.item_name" placeholder="e.g. Dell Latitude 5420" required class="block w-full px-4 py-3 bg-[#f8fafc] border-transparent focus:bg-white focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 rounded-xl text-sm text-gray-900 transition-colors placeholder-gray-400">
                            </div>

                            <!-- Category -->
                            <div>
                                <label for="category" class="block text-[11px] font-bold text-gray-500 uppercase tracking-wider mb-2">Category</label>
                                <select name="category" id="category" x-model="form.category" class="block w-full px-4 py-3 bg-[#f8fafc] border-transparent focus:bg-white focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 rounded-xl text-sm text-gray-900 transition-colors appearance-none font-medium">
                                    <option value="Computer">Computer</option>
                                    <option value="Monitor">Monitor</option>
                                    <option value="Printer">Printer</option>
                                    <option value="Microscope">Microscope</option>
                                    <option value="Lab Kit">Lab Kit</option>
                                    <option value="Chemical Container">Chemical Container</option>
                                    <option value="Furniture">Furniture</option>
                                    <option value="Network Device">Network Device</option>
                                    <option value="Others">Others</option>
                                </select>
                            </div>

                            <!-- Property # -->
                            <div>
                                <label for="property_number" class="block text-[11px] font-bold text-gray-500 uppercase tracking-wider mb-2">Property #</label>
                                <input type="text" name="property_number" id="property_number" x-model="form.property_number" placeholder="PRO-2026-001" class="block w-full px-4 py-3 bg-[#f8fafc] border-transparent focus:bg-white focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 rounded-xl text-sm text-gray-900 transition-colors placeholder-gray-400">
                            </div>

                            <!-- PAR # -->
                            <div>
                                <label for="par_number" class="block text-[11px] font-bold text-gray-500 uppercase tracking-wider mb-2">PAR #</label>
                                <input type="text" name="par_number" id="par_number" x-model="form.par_number" required placeholder="PAR-0021" class="block w-full px-4 py-3 bg-[#f8fafc] border-transparent focus:bg-white focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 rounded-xl text-sm text-gray-900 transition-colors placeholder-gray-400">
                            </div>

                            <!-- Amount -->
                            <div>
                                <label for="amount" class="block text-[11px] font-bold text-gray-500 uppercase tracking-wider mb-2">Amount</label>
                                <input type="number" step="0.01" name="amount" id="amount" x-model="form.amount" placeholder="1" class="block w-full px-4 py-3 bg-[#f8fafc] border-transparent focus:bg-white focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 rounded-xl text-sm text-gray-900 transition-colors placeholder-gray-400">
                            </div>

                            <!-- Date Acquired -->
                            <div>
                                <label for="acquired_date" class="block text-[11px] font-bold text-gray-500 uppercase tracking-wider mb-2">Date Acquired</label>
                                <input type="date" name="acquired_date" id="acquired_date" x-model="form.acquired_date" class="block w-full px-4 py-3 bg-[#f8fafc] border-transparent focus:bg-white focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 rounded-xl text-sm text-gray-900 transition-colors text-gray-600">
                            </div>

                            <!-- Fund Source -->
                            <div>
                                <label for="fund" class="block text-[11px] font-bold text-gray-500 uppercase tracking-wider mb-2">Fund Source</label>
                                <input type="text" name="fund" id="fund" x-model="form.fund" placeholder="General" class="block w-full px-4 py-3 bg-[#f8fafc] border-transparent focus:bg-white focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 rounded-xl text-sm text-gray-900 transition-colors placeholder-gray-400">
                            </div>

                            <!-- Initial Status -->
                            <div>
                                <label for="status" class="block text-[11px] font-bold text-gray-500 uppercase tracking-wider mb-2">Initial Status</label>
                                <select name="status" id="status" x-model="form.status" required class="block w-full px-4 py-3 bg-[#f8fafc] border-transparent focus:bg-white focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 rounded-xl text-sm text-gray-900 transition-colors appearance-none">
                                    <option value="working">Available</option>
                                    <option value="borrowed">Borrowed</option>
                                    <option value="damaged">Damaged</option>
                                    <option value="under_repair">Under Repair</option>
                                    <option value="disposed">Disposed</option>
                                </select>
                            </div>

                            <!-- Notes / Description -->
                            <div class="md:col-span-2">
                                <label for="description" class="block text-[11px] font-bold text-gray-500 uppercase tracking-wider mb-2">Notes / Description</label>
                                <textarea name="description" id="description" x-model="form.description" rows="3" placeholder="Additional details about the item..." class="block w-full px-4 py-3 bg-[#f8fafc] border-transparent focus:bg-white focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 rounded-xl text-sm text-gray-900 transition-colors placeholder-gray-400 resize-none"></textarea>
                            </div>
                        </div>

                        <!-- Buttons -->
                        <div class="mt-8 flex items-center justify-between space-x-4">
                            <button type="button" @click="showModal = false" class="flex-1 py-3.5 px-4 bg-[#f8fafc] hover:bg-gray-100 text-gray-700 text-sm font-bold rounded-xl transition-colors">
                                Cancel
                            </button>
                            <button type="submit" x-text="isEdit ? 'Update Equipment' : 'Add Equipment'" class="flex-[2] py-3.5 px-4 bg-[#503fe8] hover:bg-[#4030d0] text-white text-sm font-bold rounded-xl transition-colors shadow-sm">
                            </button>
                        </div>

                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
