<x-app-layout>
    <!-- Page Title & Actions -->
    <div class="flex justify-between items-start mb-8">
        <div class="flex flex-col">
            <h2 class="text-3xl font-extrabold text-[#0f172a] tracking-tight">
                Circulation Control
            </h2>
            <p class="text-[11px] font-bold text-gray-400 tracking-widest uppercase mt-2">
                @if(Auth::user()->role === 'dean')
                    Transaction Monitor for CAS College
                @else
                    Transaction Monitor for {{ Auth::user()->laboratory->name ?? 'Laboratory' }}
                @endif
            </p>
        </div>
        
        <div class="flex items-center space-x-3">
            @if(Auth::user()->role === 'admin')
            <button x-data @click="$dispatch('open-borrow-modal')" class="flex items-center px-5 py-2.5 bg-[#0f172a] border border-transparent rounded-lg shadow-sm text-xs font-bold text-white hover:bg-gray-800 transition-colors tracking-wide">
                + BORROW EQUIPMENT
            </button>
            @endif
        </div>
    </div>

    <!-- Main Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <!-- Left Column: Summary & Policy -->
        <div class="lg:col-span-1 space-y-6">
            <!-- Summary Card -->
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
                <h3 class="text-[11px] font-bold text-gray-400 tracking-widest uppercase mb-6">Summary</h3>
                
                <div class="flex justify-between items-center mb-6">
                    <span class="text-sm font-bold text-gray-500 uppercase tracking-wide">Active</span>
                    <span class="text-2xl font-black text-gray-900">{{ $activeCount }}</span>
                </div>
                
                <div class="flex justify-between items-center pt-6 border-t border-gray-50">
                    <span class="text-sm font-bold text-gray-500 uppercase tracking-wide">Total</span>
                    <span class="text-2xl font-black text-gray-900">{{ $totalCount }}</span>
                </div>

                @if(Auth::user()->role === 'dean' && isset($labBreakdown))
                    <div class="mt-6 pt-6 border-t border-gray-50 space-y-4">
                        <h4 class="text-[10px] font-bold text-gray-400 tracking-widest uppercase mb-4">By Laboratory</h4>
                        @foreach($labBreakdown as $lab)
                            @if($lab->total_borrows > 0)
                                <div class="flex justify-between items-center">
                                    <span class="text-xs font-semibold text-gray-600">{{ $lab->name }}</span>
                                    <div class="text-xs">
                                        <span class="font-bold text-amber-500">{{ $lab->active_borrows }} active</span>
                                        <span class="mx-1 text-gray-300">/</span>
                                        <span class="font-bold text-gray-500">{{ $lab->total_borrows }} total</span>
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    </div>
                @endif
            </div>

            <!-- Asset Policy Card -->
            <div class="bg-[#0f172a] rounded-2xl border border-gray-800 shadow-lg p-6 relative overflow-hidden">
                <div class="flex items-center text-yellow-400 mb-4">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                    <h3 class="text-[11px] font-bold tracking-widest uppercase">Asset Policy</h3>
                </div>
                <p class="text-sm text-gray-300 font-medium leading-relaxed mb-6 relative z-10">
                    Returns due within 48 hours. Valid property tags required for all items.
                </p>
                <button class="w-full py-2.5 bg-gray-800 hover:bg-gray-700 text-gray-300 text-xs font-bold uppercase tracking-widest rounded-lg transition-colors border border-gray-700 relative z-10">
                    System Policy
                </button>
            </div>
        </div>

        <!-- Right Column: Activity Log -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden flex flex-col h-full min-h-[500px]">
                
                <!-- Card Header -->
                <div class="p-6 border-b border-gray-50 flex flex-col sm:flex-row justify-between items-center gap-4">
                    <h3 class="text-sm font-bold text-gray-900">Activity Log</h3>
                    
                    <form action="{{ route('borrow.index') }}" method="GET" class="w-full sm:w-auto relative flex items-center">
                        <svg class="w-4 h-4 text-gray-400 absolute left-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Filter logs... (Press Enter)" class="w-full sm:w-64 pl-9 pr-4 py-2 border border-gray-100 rounded-xl text-xs text-gray-900 placeholder-gray-400 bg-gray-50 focus:bg-white focus:ring-2 focus:ring-indigo-100 focus:border-indigo-300 transition-colors" {{ request('search') ? 'autofocus onfocus=this.setSelectionRange(this.value.length,this.value.length)' : '' }} />
                    </form>
                </div>

                <!-- Transaction List -->
                <div class="flex-1 overflow-y-auto">
                    @if($records->isEmpty())
                        <div class="flex flex-col items-center justify-center h-full p-12">
                            <div class="bg-gray-50 rounded-full p-4 mb-4">
                                <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
                            </div>
                            <p class="text-gray-900 font-bold text-base mb-1">No transactions found</p>
                            <p class="text-gray-500 text-sm">Borrow some equipment to see the log.</p>
                        </div>
                    @else
                        <ul class="divide-y divide-gray-50">
                            @foreach($records as $record)
                                <li class="p-6 hover:bg-gray-50 transition-colors flex flex-col sm:flex-row gap-4 sm:items-center sm:justify-between group">
                                    <div class="flex items-start">
                                        <!-- Icon -->
                                        @if($record->status === 'borrowed')
                                            <div class="flex-shrink-0 w-10 h-10 rounded-xl bg-amber-50 border border-amber-100 flex items-center justify-center text-amber-500 mt-1">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                            </div>
                                        @else
                                            <div class="flex-shrink-0 w-10 h-10 rounded-xl bg-emerald-50 border border-emerald-100 flex items-center justify-center text-emerald-500 mt-1">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                            </div>
                                        @endif

                                        <!-- Details -->
                                        <div class="ml-4">
                                            <div class="flex items-center space-x-3 mb-1">
                                                <h4 class="text-sm font-bold text-gray-900">{{ $record->equipment->item_name }}</h4>
                                                @if($record->status === 'borrowed')
                                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-black tracking-widest bg-amber-100 text-amber-700 uppercase">
                                                        Active
                                                    </span>
                                                @else
                                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-black tracking-widest bg-emerald-100 text-emerald-700 uppercase">
                                                        Returned
                                                    </span>
                                                @endif
                                            </div>
                                            
                                            <div class="flex items-center text-xs font-medium text-gray-500 mb-1.5 uppercase tracking-wide">
                                                @if(Auth::user()->role === 'dean')
                                                    <span class="text-indigo-600 font-bold bg-indigo-50 px-2 py-0.5 rounded mr-2">{{ $record->equipment->laboratory->name ?? 'Unknown Lab' }}</span>
                                                @endif
                                                <span>{{ $record->equipment->category }}</span>
                                                <span class="mx-2 text-gray-300">|</span>
                                                <span>P: {{ $record->equipment->property_number ?? 'N/A' }}</span>
                                                <span class="mx-2 text-gray-300">|</span>
                                                <span>S: {{ $record->equipment->par_number }}</span>
                                            </div>

                                            <div class="flex items-center text-xs font-semibold text-gray-400">
                                                <span class="text-gray-600">{{ $record->borrower_name }}</span>
                                                <span class="mx-2">/</span>
                                                <span class="px-1.5 py-0.5 rounded-md bg-gray-100 text-[10px] font-bold text-gray-500 uppercase tracking-wider">{{ $record->borrower_role ?? 'Unknown' }}</span>
                                                <span class="mx-2">/</span>
                                                <span>{{ $record->borrower_id_number }}</span>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Action / Date -->
                                    <div class="flex flex-col sm:items-end ml-14 sm:ml-0 gap-1 mt-2 sm:mt-0 text-right">
                                        <div class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">
                                            <span class="text-gray-400">Borrowed:</span> {{ $record->borrowed_at->format('M d, Y g:i A') }}
                                        </div>
                                        
                                        @if($record->expected_return_at && $record->status !== 'returned')
                                            <div class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">
                                                <span class="text-gray-400">Due:</span> {{ $record->expected_return_at->format('M d, Y') }}
                                            </div>
                                        @endif

                                        @if($record->returned_at)
                                            <div class="text-[11px] font-bold text-emerald-600 uppercase tracking-wider">
                                                <span class="text-emerald-500">Returned:</span> {{ $record->returned_at->format('M d, Y g:i A') }}
                                            </div>
                                        @endif
                                        
                                        @if($record->status === 'borrowed' && Auth::user()->role === 'admin')
                                            <div class="mt-2">
                                                <form action="{{ route('borrow.return', $record) }}" method="POST" class="flex flex-col sm:flex-row items-end sm:items-center gap-2">
                                                    @csrf
                                                    @method('PATCH')
                                                    <select name="condition" required class="py-1.5 pl-2 pr-8 border border-gray-200 rounded-lg text-[10px] font-bold text-gray-600 uppercase tracking-wider focus:ring-2 focus:ring-indigo-100 focus:border-indigo-300 appearance-none bg-white">
                                                        <option value="working" selected>Good Condition</option>
                                                        <option value="damaged">Damaged</option>
                                                    </select>
                                                    <button type="submit" class="inline-flex items-center px-3 py-1.5 bg-white border border-gray-200 hover:border-gray-300 hover:bg-gray-50 text-gray-700 text-[10px] font-bold tracking-widest uppercase rounded-lg transition-colors shadow-sm">
                                                        Return
                                                    </button>
                                                </form>
                                            </div>
                                        @endif
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            </div>
        </div>
    </div>

    @if(Auth::user()->role === 'admin')
    <!-- Borrow Modal -->
    <div x-data="{ 
            showModal: {{ $errors->any() ? 'true' : 'false' }},
            selectedEquipmentId: '{{ old('equipment_id', '') }}',
            equipmentList: @js($availableEquipment),
            get selectedEquipment() {
                if (!this.selectedEquipmentId) return null;
                return this.equipmentList.find(item => item.id == this.selectedEquipmentId);
            },
            init() {
                const urlParams = new URLSearchParams(window.location.search);
                if(urlParams.get('action') === 'borrow' && !this.showModal) {
                    setTimeout(() => { $dispatch('open-borrow-modal'); }, 100);
                    // Clear the parameter to avoid reopening on refresh
                    window.history.replaceState({}, document.title, window.location.pathname);
                }
            }
        }" 
         @open-borrow-modal.window="showModal = true" 
         x-show="showModal" 
         class="fixed inset-0 z-50 overflow-y-auto" style="display: none;" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div x-show="showModal" x-transition.opacity class="fixed inset-0 bg-gray-900 bg-opacity-50 backdrop-blur-sm" aria-hidden="true"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <div x-show="showModal" @click.away="showModal = false" x-transition class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-xl sm:w-full">
                
                <div class="bg-white px-8 pt-6 pb-2">
                    <div class="flex justify-between items-center">
                        <h3 class="text-xl font-bold text-gray-900">Borrow Equipment</h3>
                        <button type="button" @click="showModal = false" class="text-gray-400 hover:text-gray-600 transition-colors">
                            <span class="sr-only">Close</span>
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                        </button>
                    </div>
                </div>

                <form action="{{ route('borrow.store') }}" method="POST">
                    @csrf
                    <div class="bg-white px-8 pt-4 pb-8">
                        @if ($errors->any())
                            <div class="mb-6 bg-red-50 p-4 rounded-xl border border-red-100">
                                <ul class="text-sm text-red-600 list-disc list-inside font-medium">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <div class="space-y-5">
                            
                            <!-- Select Equipment -->
                            <div>
                                <label for="equipment_id" class="block text-[11px] font-bold text-gray-500 uppercase tracking-wider mb-2">Select Item</label>
                                <select name="equipment_id" id="equipment_id" x-model="selectedEquipmentId" required class="block w-full px-4 py-3 bg-[#f8fafc] border-transparent focus:bg-white focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 rounded-xl text-sm text-gray-900 transition-colors appearance-none font-medium">
                                    <option value="" disabled selected>Select an item to borrow...</option>
                                    <template x-for="item in equipmentList" :key="item.id">
                                        <option :value="item.id" x-text="`${item.item_name} (Prop: ${item.property_number || 'N/A'}) | Ser: ${item.par_number}`"></option>
                                    </template>
                                </select>
                            </div>

                            <!-- Asset Details Card -->
                            <div x-show="selectedEquipment" style="display: none;" x-transition class="bg-[#0f172a] rounded-xl p-6 shadow-xl border border-gray-800">
                                <div class="flex justify-between items-start mb-6">
                                    <h4 class="text-[11px] font-bold text-gray-400 tracking-widest uppercase">Asset Details</h4>
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-black tracking-widest bg-emerald-900/30 text-emerald-400 uppercase border border-emerald-800/50">
                                        Ready for Release
                                    </span>
                                </div>
                                
                                <div class="grid grid-cols-2 gap-y-5 gap-x-4">
                                    <div class="col-span-2">
                                        <span class="block text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-1">Model</span>
                                        <span class="block text-sm font-bold text-white uppercase" x-text="selectedEquipment.item_name"></span>
                                    </div>
                                    
                                    <div>
                                        <span class="block text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-1">Category</span>
                                        <span class="block text-sm font-bold text-white uppercase" x-text="selectedEquipment.category || 'N/A'"></span>
                                    </div>
                                    <div>
                                        <span class="block text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-1">Acquired</span>
                                        <span class="block text-sm font-bold text-white uppercase" x-text="selectedEquipment.acquired_date ? selectedEquipment.acquired_date.split(' ')[0] : 'N/A'"></span>
                                    </div>
                                    
                                    <div>
                                        <span class="block text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-1">Property #</span>
                                        <span class="block text-sm font-bold text-white" x-text="selectedEquipment.property_number || 'N/A'"></span>
                                    </div>
                                    <div>
                                        <span class="block text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-1">Serial/Part #</span>
                                        <span class="block text-sm font-bold text-white" x-text="selectedEquipment.par_number || 'N/A'"></span>
                                    </div>
                                    
                                    <div class="col-span-2">
                                        <span class="block text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-1">Initial Status</span>
                                        <span class="block text-sm font-bold text-emerald-400 uppercase" x-text="selectedEquipment.status === 'working' ? 'Available' : selectedEquipment.status"></span>
                                    </div>
                                </div>
                            </div>

                            <!-- Borrower Name -->
                            <div>
                                <label for="borrower_name" class="block text-[11px] font-bold text-gray-500 uppercase tracking-wider mb-2">Borrower Name</label>
                                <input type="text" name="borrower_name" id="borrower_name" value="{{ old('borrower_name') }}" placeholder="e.g. Ella Smith" required class="block w-full px-4 py-3 bg-[#f8fafc] border-transparent focus:bg-white focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 rounded-xl text-sm text-gray-900 transition-colors placeholder-gray-400">
                            </div>

                            <!-- Borrower Role -->
                            <div>
                                <label for="borrower_role" class="block text-[11px] font-bold text-gray-500 uppercase tracking-wider mb-2">Role</label>
                                <select name="borrower_role" id="borrower_role" required class="block w-full px-4 py-3 bg-[#f8fafc] border-transparent focus:bg-white focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 rounded-xl text-sm text-gray-900 transition-colors appearance-none font-medium">
                                    <option value="" disabled selected>Select Role...</option>
                                    <option value="Student" @selected(old('borrower_role') == 'Student')>Student</option>
                                    <option value="Faculty" @selected(old('borrower_role') == 'Faculty')>Faculty</option>
                                </select>
                            </div>

                            <!-- Borrower ID -->
                            <div>
                                <label for="borrower_id_number" class="block text-[11px] font-bold text-gray-500 uppercase tracking-wider mb-2">Borrower ID Number</label>
                                <input type="text" name="borrower_id_number" id="borrower_id_number" value="{{ old('borrower_id_number') }}" placeholder="Student ID / Faculty ID" required class="block w-full px-4 py-3 bg-[#f8fafc] border-transparent focus:bg-white focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 rounded-xl text-sm text-gray-900 transition-colors placeholder-gray-400">
                            </div>

                            <!-- Expected Return Date -->
                            <div>
                                <label for="expected_return_at" class="block text-[11px] font-bold text-gray-500 uppercase tracking-wider mb-2">Expected Return Date</label>
                                <input type="date" name="expected_return_at" id="expected_return_at" value="{{ old('expected_return_at') }}" required class="block w-full px-4 py-3 bg-[#f8fafc] border-transparent focus:bg-white focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 rounded-xl text-sm text-gray-900 transition-colors text-gray-600">
                            </div>
                            
                        </div>

                        <!-- Buttons -->
                        <div class="mt-8 flex items-center justify-between space-x-4">
                            <button type="button" @click="showModal = false" class="flex-1 py-3.5 px-4 bg-[#f8fafc] hover:bg-gray-100 text-gray-700 text-sm font-bold rounded-xl transition-colors">
                                Cancel
                            </button>
                            <button type="submit" class="flex-[2] py-3.5 px-4 bg-[#503fe8] hover:bg-[#4030d0] text-white text-sm font-bold rounded-xl transition-colors shadow-sm">
                                Confirm Borrow
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif
</x-app-layout>
