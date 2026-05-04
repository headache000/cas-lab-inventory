<?php

namespace App\Http\Controllers;

use App\Models\BorrowRecord;
use App\Models\InventoryItem;
use Illuminate\Http\Request;

class BorrowReturnController extends Controller
{
    public function index()
    {
        $records = BorrowRecord::with(['inventoryItem', 'lab'])->latest()->paginate(15);
        return view('borrow.index', compact('records'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'inventory_item_id' => 'required|exists:inventory_items,id',
            'borrower_name' => 'required|string',
            'borrower_id_number' => 'required|string',
            'borrow_date' => 'required|date',
        ]);

        $item = InventoryItem::findOrFail($validated['inventory_item_id']);
        if ($item->status !== 'Available') {
            return back()->with('error', 'Item is not available for borrowing.');
        }

        $validated['lab_id'] = $item->lab_id;
        $validated['status'] = 'active';
        
        BorrowRecord::create($validated);

        $item->update(['status' => 'Borrowed']);

        return redirect()->route('borrow.index')->with('success', 'Item borrowed successfully.');
    }

    public function returnItem(Request $request, BorrowRecord $record)
    {
        $record->update([
            'status' => 'returned',
            'return_date' => now(),
        ]);

        $record->inventoryItem->update(['status' => 'Available']);

        return redirect()->route('borrow.index')->with('success', 'Item returned successfully.');
    }
}
