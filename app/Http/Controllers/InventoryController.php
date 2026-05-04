<?php

namespace App\Http\Controllers;

use App\Events\InventoryUpdated;
use App\Models\Equipment;
use Illuminate\Http\Request;

class InventoryController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        $query = Equipment::with('laboratory');

        // Ensure Admin only sees their lab's inventory
        if ($user->role === 'admin') {
            $query->where('laboratory_id', $user->laboratory_id);
        } elseif ($user->role === 'dean' && $request->filled('lab_id')) {
            $query->where('laboratory_id', $request->lab_id);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('item_name', 'like', "%{$search}%")
                    ->orWhere('par_number', 'like', "%{$search}%")
                    ->orWhere('property_number', 'like', "%{$search}%")
                    ->orWhere('model', 'like', "%{$search}%");
            });
        }

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        $inventory = $query->latest()->get();

        return view('inventory.index', compact('inventory'));
    }

    public function store(Request $request)
    {
        $user = $request->user();

        // Ensure user is admin and has a lab
        if ($user->role !== 'admin' || !$user->laboratory_id) {
            abort(403, 'Unauthorized action.');
        }

        $validated = $request->validate([
            'item_name' => 'required|string|max:255',
            'category' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'model' => 'nullable|string|max:255',
            'amount' => 'nullable|numeric|min:0',
            'fund' => 'nullable|string|max:255',
            'par_number' => 'required|string|unique:equipment,par_number',
            'property_number' => 'nullable|string|unique:equipment,property_number',
            'status' => 'required|in:working,damaged,under_repair,disposed',
            'acquired_date' => 'nullable|date',
        ]);

        $validated['laboratory_id'] = $user->laboratory_id;
        $validated['amount'] = $validated['amount'] ?? 0;

        Equipment::create($validated);

        InventoryUpdated::dispatch();

        return redirect()->route('inventory.index')->with('success', 'Equipment added successfully.');
    }

    public function export(Request $request)
    {
        $user = $request->user();

        if ($user->role !== 'admin' || !$user->laboratory_id) {
            abort(403, 'Unauthorized action.');
        }

        $query = Equipment::where('laboratory_id', $user->laboratory_id);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('item_name', 'like', "%{$search}%")
                    ->orWhere('par_number', 'like', "%{$search}%")
                    ->orWhere('property_number', 'like', "%{$search}%")
                    ->orWhere('model', 'like', "%{$search}%");
            });
        }

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        $inventory = $query->latest()->get();

        $filename = "equipment_inventory_" . date('Y-m-d_H-i-s') . ".csv";

        $headers = [
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=$filename",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0",
        ];

        $columns = [
            'PAR Number',
            'Property Number',
            'Item Name',
            'Category',
            'Model',
            'Status',
            'Date Acquired',
            'Amount',
            'Fund Source',
            'Description',
        ];

        $callback = function () use ($inventory, $columns) {
            $file = fopen('php://output', 'w');

            fputcsv($file, $columns);

            foreach ($inventory as $item) {
                $row = [
                    $item->par_number,
                    $item->property_number,
                    $item->item_name,
                    $item->category,
                    $item->model,
                    strtoupper($item->status),
                    $item->acquired_date,
                    $item->amount,
                    $item->fund,
                    $item->description,
                ];

                fputcsv($file, $row);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function update(Request $request, Equipment $equipment)
    {
        $user = $request->user();

        if ($user->role !== 'admin' || $equipment->laboratory_id !== $user->laboratory_id) {
            abort(403, 'Unauthorized action.');
        }

        $validated = $request->validate([
            'item_name' => 'required|string|max:255',
            'category' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'model' => 'nullable|string|max:255',
            'amount' => 'nullable|numeric|min:0',
            'fund' => 'nullable|string|max:255',
            'par_number' => 'required|string|unique:equipment,par_number,' . $equipment->id,
            'property_number' => 'nullable|string|unique:equipment,property_number,' . $equipment->id,
            'status' => 'required|in:working,damaged,under_repair,disposed',
            'acquired_date' => 'nullable|date',
        ]);

        $validated['amount'] = $validated['amount'] ?? 0;

        $equipment->update($validated);

        InventoryUpdated::dispatch();

        return redirect()->route('inventory.index')->with('success', 'Equipment updated successfully.');
    }

    public function destroy(Request $request, Equipment $equipment)
    {
        $user = $request->user();

        if ($user->role !== 'admin' || $equipment->laboratory_id !== $user->laboratory_id) {
            abort(403, 'Unauthorized action.');
        }

        $equipment->delete();

        InventoryUpdated::dispatch();

        return redirect()->route('inventory.index')->with('success', 'Equipment deleted successfully.');
    }
}