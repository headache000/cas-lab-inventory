<x-app-layout>
    <x-slot name="header">
        <h2 class="text-2xl font-bold text-gray-900 tracking-tight">
            Dashboard Overview
        </h2>
        <p class="text-sm font-semibold text-gray-500 tracking-widest uppercase mt-1">
            @if(Auth::user()->role === 'admin')
                System Status for {{ Auth::user()->laboratory->name ?? 'Laboratory' }}
            @else
                @if(isset($selectedLab) && $selectedLab)
                    System Status for {{ $selectedLab->name }}
                @else
                    System Status for CAS Laboratory
                @endif
            @endif
        </p>
    </x-slot>

    <!-- 4 Stat Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6 mb-8">
        <!-- Total Items -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 flex flex-col justify-between">
            <div class="flex justify-between items-start">
                <div class="h-10 w-10 rounded-lg bg-blue-50 text-blue-500 flex items-center justify-center">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                </div>
            </div>
            <div class="mt-4">
                <p class="text-xs font-bold text-gray-400 tracking-wider uppercase mb-1">Total Items</p>
                <h3 class="text-4xl font-bold text-blue-600">{{ $totalEquipment }}</h3>
            </div>
        </div>

        <!-- Borrowed -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 flex flex-col justify-between">
            <div class="flex justify-between items-start">
                <div class="h-10 w-10 rounded-lg bg-purple-50 text-purple-500 flex items-center justify-center">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path></svg>
                </div>
            </div>
            <div class="mt-4">
                <p class="text-xs font-bold text-gray-400 tracking-wider uppercase mb-1">Borrowed</p>
                <h3 class="text-4xl font-bold text-purple-600">{{ $borrowedItems }}</h3>
            </div>
        </div>

        <!-- Damaged -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 flex flex-col justify-between">
            <div class="flex justify-between items-start">
                <div class="h-10 w-10 rounded-lg bg-red-50 text-red-500 flex items-center justify-center">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                </div>
                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-bold bg-red-50 text-red-600 uppercase tracking-wider">Urgent</span>
            </div>
            <div class="mt-4">
                <p class="text-xs font-bold text-gray-400 tracking-wider uppercase mb-1">Damaged</p>
                <h3 class="text-4xl font-bold text-red-600">{{ $damagedItems }}</h3>
            </div>
        </div>

        <!-- Under Repair -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 flex flex-col justify-between">
            <div class="flex justify-between items-start">
                <div class="h-10 w-10 rounded-lg bg-orange-50 text-orange-500 flex items-center justify-center">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                </div>
            </div>
            <div class="mt-4">
                <p class="text-xs font-bold text-gray-400 tracking-wider uppercase mb-1">Under Repair</p>
                <h3 class="text-4xl font-bold text-orange-500">{{ $maintenanceItems }}</h3>
            </div>
        </div>
    </div>

    <!-- Middle Section: Borrow Activity & Right Column -->
    @php
        $showRightColumn = (Auth::user()->role === 'admin') || (Auth::user()->role === 'dean' && !isset($selectedLab));
    @endphp

    <div class="grid grid-cols-1 {{ $showRightColumn ? 'lg:grid-cols-3' : 'lg:grid-cols-1' }} gap-6 mb-8">
        
        <!-- Borrow Activity -->
        <div class="{{ $showRightColumn ? 'lg:col-span-2' : '' }} bg-white rounded-xl shadow-sm border border-gray-100 flex flex-col">
            <div class="px-6 py-5 border-b border-gray-50 flex justify-between items-center">
                <h3 class="text-base font-bold text-gray-900">Borrow Activity</h3>
                <a href="{{ route('borrow.index', request()->has('lab_id') ? ['lab_id' => request('lab_id')] : []) }}" class="text-xs font-bold text-indigo-500 hover:text-indigo-700 uppercase tracking-wider transition-colors">View Log &rarr;</a>
            </div>
            <div class="flex-1 p-6">
                <div class="space-y-6">
                    @forelse($borrowActivity as $activity)
                        <div class="flex items-center justify-between">
                            <div>
                                <div class="flex items-center space-x-2">
                                    <p class="text-sm font-bold text-gray-900">{{ $activity->equipment->item_name ?? 'Unknown Item' }}</p>
                                    @if(Auth::user()->role === 'dean' && !isset($selectedLab))
                                        <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[9px] font-black tracking-widest uppercase bg-indigo-50 text-indigo-600 border border-indigo-100">{{ $activity->equipment->laboratory->name ?? 'Unknown Lab' }}</span>
                                    @endif
                                </div>
                                <p class="text-xs font-bold text-gray-400 tracking-wide uppercase mt-0.5">By {{ $activity->borrower_name }}</p>
                            </div>
                            <div class="text-right">
                                <div class="mb-1">
                                    @if($activity->status === 'returned')
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-black bg-emerald-50 text-emerald-600 uppercase tracking-wider">
                                            Returned
                                        </span>
                                    @elseif($activity->status === 'borrowed')
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-black bg-orange-50 text-orange-500 uppercase tracking-wider">
                                            Active
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-black bg-red-50 text-red-600 uppercase tracking-wider">
                                            Overdue
                                        </span>
                                    @endif
                                </div>
                                <div class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mt-1">
                                    <span class="text-gray-500">B:</span> {{ $activity->borrowed_at->format('M d') }}
                                    @if($activity->status === 'returned' && $activity->returned_at)
                                        <span class="mx-1">|</span> <span class="text-emerald-500">R:</span> {{ $activity->returned_at->format('M d') }}
                                    @elseif($activity->expected_return_at)
                                        <span class="mx-1">|</span> <span class="text-gray-500">D:</span> {{ $activity->expected_return_at->format('M d') }}
                                    @endif
                                </div>
                            </div>
                        </div>
                    @empty
                        <p class="text-sm text-gray-500">No recent borrow activity.</p>
                    @endforelse
                </div>
            </div>
        </div>

        @if($showRightColumn)
            @if(Auth::user()->role === 'dean')
            <!-- Lab Overview (Dean Only - Global Overview) -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 flex flex-col">
                <div class="px-6 py-5 border-b border-gray-50">
                    <h3 class="text-xs font-bold text-gray-400 uppercase tracking-wider">Lab Overview</h3>
                </div>
                <div class="flex-1 p-6">
                    <div class="space-y-6">
                        @forelse($labOverview as $lab)
                            @if($lab->equipments_count > 0)
                            <div>
                                <div class="flex justify-between text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">
                                    <span>{{ $lab->name }}</span>
                                    <span class="text-gray-900">{{ $lab->equipments_count }}</span>
                                </div>
                                <div class="w-full bg-gray-100 rounded-full h-1.5">
                                    <div class="bg-indigo-100 h-1.5 rounded-full" style="width: {{ min(100, $lab->equipments_count * 10) }}%"></div>
                                </div>
                            </div>
                            @endif
                        @empty
                            <p class="text-sm text-gray-500">No lab data.</p>
                        @endforelse
                    </div>
                </div>
            </div>
            @else
            <!-- Quick Operations (Admin Only) -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 flex flex-col">
                <div class="px-6 py-5 border-b border-gray-50">
                    <h3 class="text-xs font-bold text-gray-400 uppercase tracking-wider">Quick Operations</h3>
                </div>
                <div class="flex-1 p-6 flex flex-col space-y-3">
                    <a href="{{ route('inventory.index', ['action' => 'add']) }}" class="flex items-center justify-between px-4 py-3 bg-gray-900 text-white rounded-lg shadow-sm hover:bg-gray-800 transition-colors">
                        <span class="text-sm font-bold">Add Equipment</span>
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                    </a>
                    <a href="{{ route('borrow.index', ['action' => 'borrow']) }}" class="flex items-center justify-between px-4 py-3 bg-indigo-50 border border-indigo-100 text-indigo-700 rounded-lg shadow-sm hover:bg-indigo-100 transition-colors">
                        <span class="text-sm font-bold">Borrow Equipment</span>
                        <svg class="w-4 h-4 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path></svg>
                    </a>
                    <a href="{{ route('reports.index') }}" class="flex items-center justify-between px-4 py-3 bg-white border border-gray-200 text-gray-800 rounded-lg shadow-sm hover:bg-gray-50 transition-colors">
                        <span class="text-sm font-bold">Generate Report</span>
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    </a>
                    
                    <div class="mt-4 pt-4 border-t border-gray-50">
                        <div class="bg-amber-50 rounded-lg p-4 border border-amber-100">
                            <div class="flex items-start">
                                <svg class="w-5 h-5 text-amber-500 mt-0.5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                <div>
                                    <h4 class="text-xs font-bold text-amber-800 uppercase tracking-wider mb-1">Tip</h4>
                                    <p class="text-xs text-amber-700 leading-relaxed">Ensure all equipment tags are scanned for exact equipment when performing return operations.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endif
        @endif
    </div>

    <!-- Recent Inventory -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 mb-8">
        <div class="px-6 py-5 border-b border-gray-50 flex justify-between items-center">
            <h3 class="text-base font-bold text-gray-900">Recent Inventory</h3>
            <a href="{{ route('inventory.index', request()->has('lab_id') ? ['lab_id' => request('lab_id')] : []) }}" class="text-xs font-bold text-indigo-500 hover:text-indigo-700 uppercase tracking-wider transition-colors">Full List &rarr;</a>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-100">
                <thead class="bg-white">
                    <tr>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">Item Model</th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">Category</th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">Prop / Part #</th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">Acquired</th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">Lab</th>
                        <th scope="col" class="px-6 py-4 text-right text-xs font-bold text-gray-400 uppercase tracking-wider">Status</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-50">
                    @forelse($recentInventory as $item)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-900">
                                {{ $item->item_name }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-500 uppercase">
                                {{ $item->model ?? 'N/A' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-xs font-semibold text-gray-500">
                                <span class="block text-gray-900">P: {{ substr($item->property_number ?? '-', 0, 10) }}</span>
                                <span class="block">S: {{ substr($item->par_number ?? '-', 0, 10) }}</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-500">
                                {{ $item->acquired_date ?? 'N/A' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded text-xs font-bold bg-gray-50 text-gray-500 uppercase tracking-wider border border-gray-100">
                                    {{ $item->laboratory->name ?? 'None' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right">
                                @if($item->status === 'working')
                                    <span class="inline-flex items-center text-xs font-bold text-emerald-500 uppercase tracking-wider">
                                        Available
                                    </span>
                                @elseif($item->status === 'disposed')
                                    <span class="inline-flex items-center text-xs font-bold text-orange-500 uppercase tracking-wider">
                                        Disposed
                                    </span>
                                @elseif($item->status === 'under_repair')
                                    <span class="inline-flex items-center text-xs font-bold text-yellow-500 uppercase tracking-wider">
                                        Under Repair
                                    </span>
                                @else
                                    <span class="inline-flex items-center text-xs font-bold text-red-500 uppercase tracking-wider">
                                        {{ $item->status }}
                                    </span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-8 text-center text-sm font-medium text-gray-500">
                                No inventory items found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>
