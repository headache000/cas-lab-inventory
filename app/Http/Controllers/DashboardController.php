<?php

namespace App\Http\Controllers;

use App\Models\Laboratory;
use App\Models\Equipment;
use App\Models\BorrowRecord;
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

            $borrowedItems = BorrowRecord::where('status', 'borrowed')->count();

            $damagedItems = Equipment::where('status', 'damaged')->count();

            $maintenanceItems = Equipment::where('status', 'under_repair')->count();

            $borrowActivity = BorrowRecord::with('equipment')
                ->latest()
                ->take(5)
                ->get();

            $labOverview = Laboratory::withCount([
                'equipments' => function ($query) {
                    $query->where('status', '!=', 'disposed');
                }
            ])->get();

            $recentInventory = Equipment::with('laboratory')
                ->latest()
                ->take(5)
                ->get();
        } else {
            // Admin dashboard or dean dashboard filtered by laboratory
            $totalEquipment = Equipment::where('laboratory_id', $selectedLabId)
                ->where('status', '!=', 'disposed')
                ->count();

            $borrowedItems = BorrowRecord::whereHas('equipment', function ($q) use ($selectedLabId) {
                $q->where('laboratory_id', $selectedLabId);
            })
                ->where('status', 'borrowed')
                ->count();

            $damagedItems = Equipment::where('laboratory_id', $selectedLabId)
                ->where('status', 'damaged')
                ->count();

            $maintenanceItems = Equipment::where('laboratory_id', $selectedLabId)
                ->where('status', 'under_repair')
                ->count();

            $borrowActivity = BorrowRecord::whereHas('equipment', function ($q) use ($selectedLabId) {
                $q->where('laboratory_id', $selectedLabId);
            })
                ->with('equipment')
                ->latest()
                ->take(5)
                ->get();

            $labOverview = collect();

            $recentInventory = Equipment::where('laboratory_id', $selectedLabId)
                ->with('laboratory')
                ->latest()
                ->take(5)
                ->get();
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

    public function stats(Request $request)
    {
        $user = $request->user();
        $selectedLabId = null;

        if ($user->role === 'admin' && $user->laboratory_id) {
            $selectedLabId = $user->laboratory_id;
        } elseif ($user->role === 'dean' && $request->has('lab_id')) {
            $selectedLabId = $request->get('lab_id');
        }

        if ($user->role === 'dean' && !$selectedLabId) {
            $totalEquipment = Equipment::where('status', '!=', 'disposed')->count();

            $borrowedItems = BorrowRecord::where('status', 'borrowed')->count();

            $damagedItems = Equipment::where('status', 'damaged')->count();

            $maintenanceItems = Equipment::where('status', 'under_repair')->count();
        } else {
            $totalEquipment = Equipment::where('laboratory_id', $selectedLabId)
                ->where('status', '!=', 'disposed')
                ->count();

            $borrowedItems = BorrowRecord::whereHas('equipment', function ($q) use ($selectedLabId) {
                $q->where('laboratory_id', $selectedLabId);
            })
                ->where('status', 'borrowed')
                ->count();

            $damagedItems = Equipment::where('laboratory_id', $selectedLabId)
                ->where('status', 'damaged')
                ->count();

            $maintenanceItems = Equipment::where('laboratory_id', $selectedLabId)
                ->where('status', 'under_repair')
                ->count();
        }

        return response()->json([
            'totalEquipment' => $totalEquipment,
            'borrowedItems' => $borrowedItems,
            'damagedItems' => $damagedItems,
            'maintenanceItems' => $maintenanceItems,
        ]);
    }
}