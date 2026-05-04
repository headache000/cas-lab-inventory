<x-app-layout>
<div class="space-y-8" x-data="reportsDashboard()">
    <!-- Page Header -->
    <div class="flex justify-between items-start">
        <div class="flex flex-col">
            <h2 class="text-3xl font-extrabold text-[#0f172a] tracking-tight">
                System Reports
            </h2>
            <p class="text-[11px] font-bold text-gray-400 tracking-widest uppercase mt-2">
                Lifecycle Monitoring & Reliability Metrics
            </p>
        </div>
        
        <div class="flex items-center space-x-3">
            <a href="{{ route('reports.export') }}" class="flex items-center px-5 py-2.5 bg-white border border-gray-200 rounded-lg shadow-sm text-xs font-bold text-gray-700 hover:bg-gray-50 transition-colors tracking-wide">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                EXPORT
            </a>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
        <!-- Damage Reports -->
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 flex flex-col justify-between">
            <div class="flex items-center mb-3">
                <div class="flex-shrink-0 w-8 h-8 rounded-xl bg-red-50 border border-red-100 flex items-center justify-center text-red-500 mr-3">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                </div>
                <h3 class="text-[10px] font-bold text-gray-400 tracking-widest uppercase">Damage Reports</h3>
            </div>
            <p class="text-2xl font-black text-gray-900">{{ $damageCount }}</p>
        </div>

        <!-- Under Repair -->
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 flex flex-col justify-between">
            <div class="flex items-center mb-3">
                <div class="flex-shrink-0 w-8 h-8 rounded-xl bg-amber-50 border border-amber-100 flex items-center justify-center text-amber-500 mr-3">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                </div>
                <h3 class="text-[10px] font-bold text-gray-400 tracking-widest uppercase">Under Repair</h3>
            </div>
            <p class="text-2xl font-black text-gray-900">{{ $underRepairCount }}</p>
        </div>

        <!-- Disposed Items -->
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 flex flex-col justify-between">
            <div class="flex items-center mb-3">
                <div class="flex-shrink-0 w-8 h-8 rounded-xl bg-slate-100 border border-slate-200 flex items-center justify-center text-slate-500 mr-3">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                </div>
                <h3 class="text-[10px] font-bold text-gray-400 tracking-widest uppercase">Disposed Items</h3>
            </div>
            <p class="text-2xl font-black text-gray-900">{{ $disposedCount }}</p>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-5">
        <!-- Asset Status Chart -->
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 flex flex-col">
            <h3 class="text-[11px] font-bold text-gray-800 tracking-widest uppercase mb-6">Asset Status</h3>
            <div class="relative w-full flex items-center justify-center flex-1">
                <div class="relative w-48 h-48">
                    <canvas id="statusChart"></canvas>
                </div>
            </div>
            <!-- Custom Legend matching the screenshot -->
            <div class="mt-8 grid grid-cols-2 gap-4">
                <div class="flex items-center justify-between">
                    <div class="flex items-center text-[10px] font-bold text-gray-500 tracking-widest uppercase">
                        <span class="w-2 h-2 rounded-full bg-[#503fe8] mr-2"></span> Available
                    </div>
                    <span class="text-xs font-black text-gray-900">{{ $statuses['working'] ?? 0 }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <div class="flex items-center text-[10px] font-bold text-gray-500 tracking-widest uppercase">
                        <span class="w-2 h-2 rounded-full bg-[#10b981] mr-2"></span> Borrowed
                    </div>
                    <span class="text-xs font-black text-gray-900">{{ $statuses['borrowed'] ?? 0 }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <div class="flex items-center text-[10px] font-bold text-gray-500 tracking-widest uppercase">
                        <span class="w-2 h-2 rounded-full bg-[#f59e0b] mr-2"></span> Under Repair
                    </div>
                    <span class="text-xs font-black text-gray-900">{{ $statuses['under_repair'] ?? 0 }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <div class="flex items-center text-[10px] font-bold text-gray-500 tracking-widest uppercase">
                        <span class="w-2 h-2 rounded-full bg-[#ef4444] mr-2"></span> Disposed
                    </div>
                    <span class="text-xs font-black text-gray-900">{{ $statuses['disposed'] ?? 0 }}</span>
                </div>
            </div>
        </div>

        <!-- Category Distribution Chart -->
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 flex flex-col">
            <h3 class="text-[11px] font-bold text-gray-800 tracking-widest uppercase mb-6">Category Distribution</h3>
            <div class="relative w-full flex-1 flex flex-col justify-end">
                <div class="relative w-full h-48">
                    <canvas id="categoryChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Borrow History -->
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="p-6 border-b border-gray-50">
            <h3 class="text-sm font-bold text-gray-900">Borrow History</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr>
                        <th class="px-6 py-4 border-b border-gray-50 text-[10px] font-bold text-gray-400 uppercase tracking-widest bg-gray-50/50">Date Borrowed</th>
                        <th class="px-6 py-4 border-b border-gray-50 text-[10px] font-bold text-gray-400 uppercase tracking-widest bg-gray-50/50">Borrower</th>
                        <th class="px-6 py-4 border-b border-gray-50 text-[10px] font-bold text-gray-400 uppercase tracking-widest bg-gray-50/50">Item Name</th>
                        @if(Auth::user()->role === 'dean' && !isset($selectedLab))
                        <th class="px-6 py-4 border-b border-gray-50 text-[10px] font-bold text-gray-400 uppercase tracking-widest bg-gray-50/50">Lab</th>
                        @endif
                        <th class="px-6 py-4 border-b border-gray-50 text-[10px] font-bold text-gray-400 uppercase tracking-widest bg-gray-50/50">Status</th>
                        <th class="px-6 py-4 border-b border-gray-50 text-[10px] font-bold text-gray-400 uppercase tracking-widest bg-gray-50/50">Return Date</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($borrowHistory as $record)
                    <tr class="hover:bg-gray-50 transition-colors group">
                        <td class="px-6 py-4 text-xs text-gray-600 font-medium">
                            {{ $record->borrowed_at->format('n/j/Y') }}
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex flex-col">
                                <span class="text-xs font-bold text-gray-900">{{ $record->borrower_name }}</span>
                                <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">{{ $record->borrower_id_number }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="block text-xs font-bold text-gray-900 lowercase">{{ $record->equipment->item_name }}</span>
                        </td>
                        @if(Auth::user()->role === 'dean' && !isset($selectedLab))
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-[9px] font-black tracking-widest uppercase bg-indigo-50 text-indigo-600 border border-indigo-100">{{ $record->equipment->laboratory->name ?? 'Unknown Lab' }}</span>
                        </td>
                        @endif
                        <td class="px-6 py-4">
                            @if($record->status === 'returned')
                                <span class="inline-flex items-center px-2.5 py-1 rounded text-[9px] font-black tracking-widest bg-emerald-50 text-emerald-600 uppercase border border-emerald-100">
                                    RETURNED
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-1 rounded text-[9px] font-black tracking-widest bg-amber-50 text-amber-600 uppercase border border-amber-100">
                                    ACTIVE
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-xs text-gray-600 font-medium">
                            {{ $record->returned_at ? $record->returned_at->format('n/j/Y') : '—' }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="{{ (Auth::user()->role === 'dean' && !isset($selectedLab)) ? '6' : '5' }}" class="px-6 py-8 text-center text-sm text-gray-500 font-medium">
                            No borrow history found.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Chart.js via CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('reportsDashboard', () => ({
            init() {
                this.initStatusChart();
                this.initCategoryChart();
            },
            initStatusChart() {
                const ctx = document.getElementById('statusChart').getContext('2d');
                const data = {
                    labels: ['Available', 'Borrowed', 'Under Repair', 'Disposed'],
                    datasets: [{
                        data: [
                            {{ $statuses['working'] ?? 0 }},
                            {{ $statuses['borrowed'] ?? 0 }},
                            {{ $statuses['under_repair'] ?? 0 }},
                            {{ $statuses['disposed'] ?? 0 }}
                        ],
                        backgroundColor: [
                            '#503fe8', // Indigo
                            '#10b981', // Emerald
                            '#f59e0b', // Amber
                            '#ef4444'  // Red
                        ],
                        borderWidth: 0,
                        hoverOffset: 4
                    }]
                };

                new Chart(ctx, {
                    type: 'doughnut',
                    data: data,
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        cutout: '75%',
                        plugins: {
                            legend: {
                                display: false // We built a custom legend
                            },
                            tooltip: {
                                backgroundColor: '#0f172a',
                                titleFont: { family: 'Inter', size: 12 },
                                bodyFont: { family: 'Inter', size: 14, weight: 'bold' },
                                padding: 12,
                                cornerRadius: 8,
                                displayColors: false
                            }
                        }
                    }
                });
            },
            initCategoryChart() {
                const ctx = document.getElementById('categoryChart').getContext('2d');
                
                const categories = {!! json_encode(array_keys($categoryDistribution)) !!};
                const counts = {!! json_encode(array_values($categoryDistribution)) !!};
                
                // Formatter for capitalization
                const capitalize = s => s && s[0].toUpperCase() + s.slice(1);

                new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: categories.map(capitalize),
                        datasets: [{
                            data: counts,
                            backgroundColor: '#503fe8',
                            borderRadius: 4,
                            barThickness: 16, // Thin bars like in the mockup
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                display: false
                            },
                            tooltip: {
                                backgroundColor: '#0f172a',
                                titleFont: { family: 'Inter', size: 12 },
                                bodyFont: { family: 'Inter', size: 14, weight: 'bold' },
                                padding: 12,
                                cornerRadius: 8,
                                displayColors: false
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                grid: {
                                    color: '#f8fafc',
                                    drawBorder: false,
                                },
                                border: { display: false },
                                ticks: {
                                    font: { family: 'Inter', size: 10, weight: 'bold' },
                                    color: '#94a3b8',
                                    stepSize: 1
                                }
                            },
                            x: {
                                grid: {
                                    display: false,
                                    drawBorder: false,
                                },
                                border: { display: false },
                                ticks: {
                                    font: { family: 'Inter', size: 10, weight: 'bold' },
                                    color: '#94a3b8'
                                }
                            }
                        }
                    }
                });
            }
        }));
    });
</script>
</x-app-layout>
