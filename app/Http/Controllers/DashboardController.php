<?php

namespace App\Http\Controllers;

use App\Models\Laboratory;
use App\Models\Equipment;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $selectedLabId = null;
        $selectedLab = null;

        if ($user->role === 'admin' && $user->laboratory_id) {
            $selectedLabId = $user->laboratory_id;
        } elseif ($user->role === 'dean' && $request->has('lab_id')) {
            $selectedLabId = $request->get('lab_id');
            $selectedLab = Laboratory::find($selectedLabId);
        }

        if ($user->role === 'dean' && !$selectedLabId) {
            $totalEquipment = Equipment::where('status', '!=', 'disposed')->count();
            $borrowedItems = \App\Models\BorrowRecord::where('status', 'borrowed')->count();
            $damagedItems = Equipment::where('status', 'damaged')->count();
            $maintenanceItems = Equipment::where('status', 'under_repair')->count();
            
            $borrowActivity = \App\Models\BorrowRecord::with('equipment')->latest()->take(5)->get();
            $labOverview = Laboratory::withCount(['equipments' => function ($query) {
                $query->where('status', '!=', 'disposed');
            }])->get();
            $recentInventory = Equipment::with('laboratory')->latest()->take(5)->get();
        } else {
            // Either Admin (with a laboratory) or Dean filtering by a specific lab
            $totalEquipment = Equipment::where('laboratory_id', $selectedLabId)->where('status', '!=', 'disposed')->count();
            $borrowedItems = \App\Models\BorrowRecord::whereHas('equipment', function($q) use ($selectedLabId) {
                $q->where('laboratory_id', $selectedLabId);
            })->where('status', 'borrowed')->count();
            $damagedItems = Equipment::where('laboratory_id', $selectedLabId)->where('status', 'damaged')->count();
            $maintenanceItems = Equipment::where('laboratory_id', $selectedLabId)->where('status', 'under_repair')->count();
            
            $borrowActivity = \App\Models\BorrowRecord::whereHas('equipment', function($q) use ($selectedLabId) {
                $q->where('laboratory_id', $selectedLabId);
            })->with('equipment')->latest()->take(5)->get();
            $labOverview = collect(); // Not needed when filtered
            $recentInventory = Equipment::where('laboratory_id', $selectedLabId)->with('laboratory')->latest()->take(5)->get();
        }

        return view('dashboard', compact(
            'totalEquipment', 
            'borrowedItems', 
            'damagedItems', 
            'maintenanceItems',
            'borrowActivity',
            'labOverview',
            'recentInventory',
            'selectedLab'
        ));
    }
}
