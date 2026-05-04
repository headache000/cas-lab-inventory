<?php

namespace App\Http\Controllers;

use App\Models\Equipment;
use App\Models\BorrowRecord;
use Illuminate\Http\Request;
use Carbon\Carbon;

class BorrowController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        $labBreakdown = collect();
        if ($user->role === 'dean') {
            $query = BorrowRecord::with('equipment.laboratory');
            $availableEquipment = collect();
            
            if ($request->filled('lab_id')) {
                $query->whereHas('equipment', function ($q) use ($request) {
                    $q->where('laboratory_id', $request->lab_id);
                });
            } else {
                $labBreakdown = \App\Models\Laboratory::withCount([
                    'borrowRecords as active_borrows' => function ($query) {
                        $query->where('borrow_records.status', 'borrowed');
                    },
                    'borrowRecords as total_borrows'
                ])->get();
            }
        } elseif ($user->role === 'admin' && $user->laboratory_id) {
            $query = BorrowRecord::with('equipment')
                ->whereHas('equipment', function ($q) use ($user) {
                    $q->where('laboratory_id', $user->laboratory_id);
                });
            $availableEquipment = Equipment::where('laboratory_id', $user->laboratory_id)
                ->where('status', 'working')
                ->get();
        } else {
            abort(403, 'Unauthorized action.');
        }

        // Calculate counts before applying search filter
        $activeCount = (clone $query)->where('status', 'borrowed')->count();
        $totalCount = (clone $query)->count();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('borrower_name', 'like', "%{$search}%")
                  ->orWhere('borrower_id_number', 'like', "%{$search}%")
                  ->orWhereHas('equipment', function ($eqQ) use ($search) {
                      $eqQ->where('item_name', 'like', "%{$search}%")
                          ->orWhere('par_number', 'like', "%{$search}%")
                          ->orWhere('property_number', 'like', "%{$search}%");
                  });
            });
        }

        $records = $query->latest('borrowed_at')->get();

        return view('borrow.index', compact('records', 'activeCount', 'totalCount', 'availableEquipment', 'labBreakdown'));
    }

    public function store(Request $request)
    {
        $user = $request->user();

        if ($user->role !== 'admin' || !$user->laboratory_id) {
            abort(403, 'Unauthorized action.');
        }

        $validated = $request->validate([
            'equipment_id' => 'required|exists:equipment,id',
            'borrower_name' => 'required|string|max:255',
            'borrower_id_number' => 'required|string|max:255',
            'borrower_role' => 'required|in:Student,Faculty',
            'expected_return_at' => 'required|date|after_or_equal:today',
        ]);

        $equipment = Equipment::findOrFail($validated['equipment_id']);
        
        if ($equipment->laboratory_id !== $user->laboratory_id) {
            abort(403, 'Unauthorized action.');
        }

        if ($equipment->status !== 'working') {
            return back()->withErrors(['equipment_id' => 'Equipment is not available for borrowing.'])->withInput();
        }

        BorrowRecord::create([
            'equipment_id' => $equipment->id,
            'borrower_name' => $validated['borrower_name'],
            'borrower_id_number' => $validated['borrower_id_number'],
            'borrower_role' => $validated['borrower_role'],
            'borrowed_at' => now(),
            'expected_return_at' => Carbon::parse($validated['expected_return_at'])->endOfDay(),
            'status' => 'borrowed',
        ]);

        $equipment->update(['status' => 'borrowed']);

        return redirect()->route('borrow.index')->with('success', 'Equipment borrowed successfully.');
    }

    public function returnItem(Request $request, BorrowRecord $record)
    {
        $user = $request->user();

        if ($user->role !== 'admin' || !$user->laboratory_id) {
            abort(403, 'Unauthorized action.');
        }

        if ($record->equipment->laboratory_id !== $user->laboratory_id) {
            abort(403, 'Unauthorized action.');
        }

        $validated = $request->validate([
            'condition' => 'required|in:working,damaged',
        ]);

        $record->update([
            'returned_at' => now(),
            'status' => 'returned',
        ]);

        $record->equipment->update(['status' => $validated['condition']]);

        return redirect()->route('borrow.index')->with('success', 'Equipment returned successfully.');
    }
}
