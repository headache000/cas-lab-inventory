@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-end">
        <div>
            <h2 class="text-3xl font-bold text-slate-900 tracking-tight">Add Equipment</h2>
            <p class="text-xs text-slate-400 uppercase tracking-widest font-bold mt-1">Register new inventory</p>
        </div>
        <a href="{{ route('inventory.index') }}" class="px-4 py-2 bg-white border border-slate-200 text-slate-700 rounded-lg text-sm font-bold shadow-sm hover:bg-slate-50 transition-colors">
            Cancel
        </a>
    </div>

    <div class="bg-white rounded-2xl border border-slate-200/60 shadow-sm p-6 max-w-3xl">
        <form action="{{ route('inventory.store') }}" method="POST" class="space-y-6">
            @csrf
            
            <div class="grid grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-semibold text-slate-900 mb-2">Model</label>
                    <input type="text" name="model" required class="w-full rounded-lg border border-slate-200 px-4 py-2 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
                </div>
                
                <div>
                    <label class="block text-sm font-semibold text-slate-900 mb-2">Category</label>
                    <input type="text" name="category" required class="w-full rounded-lg border border-slate-200 px-4 py-2 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
                </div>
                
                <div>
                    <label class="block text-sm font-semibold text-slate-900 mb-2">Property Number</label>
                    <input type="text" name="property_number" required class="w-full rounded-lg border border-slate-200 px-4 py-2 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
                </div>
                
                <div>
                    <label class="block text-sm font-semibold text-slate-900 mb-2">PAR Number</label>
                    <input type="text" name="par_number" required class="w-full rounded-lg border border-slate-200 px-4 py-2 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
                </div>
                
                <div>
                    <label class="block text-sm font-semibold text-slate-900 mb-2">Amount</label>
                    <input type="number" name="amount" required min="1" class="w-full rounded-lg border border-slate-200 px-4 py-2 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
                </div>
                
                <div>
                    <label class="block text-sm font-semibold text-slate-900 mb-2">Date Acquired</label>
                    <input type="date" name="date_acquired" required class="w-full rounded-lg border border-slate-200 px-4 py-2 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
                </div>
                
                <div>
                    <label class="block text-sm font-semibold text-slate-900 mb-2">Fund</label>
                    <input type="text" name="fund" required class="w-full rounded-lg border border-slate-200 px-4 py-2 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
                </div>
                
                <div>
                    <label class="block text-sm font-semibold text-slate-900 mb-2">Status</label>
                    <select name="status" class="w-full rounded-lg border border-slate-200 px-4 py-2 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
                        <option value="Available">Available</option>
                        <option value="Damaged">Damaged</option>
                    </select>
                </div>
                
                <div class="col-span-2">
                    <label class="block text-sm font-semibold text-slate-900 mb-2">Laboratory</label>
                    <select name="lab_id" required class="w-full rounded-lg border border-slate-200 px-4 py-2 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
                        @foreach($labs as $lab)
                        <option value="{{ $lab->id }}">{{ $lab->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-span-2">
                    <label class="block text-sm font-semibold text-slate-900 mb-2">Description</label>
                    <textarea name="description" rows="3" class="w-full rounded-lg border border-slate-200 px-4 py-2 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500"></textarea>
                </div>
            </div>

            <div class="pt-4 flex justify-end">
                <button type="submit" class="px-6 py-2 bg-indigo-600 text-white rounded-lg text-sm font-bold shadow-sm hover:bg-indigo-700 transition-colors">
                    Save Equipment
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
