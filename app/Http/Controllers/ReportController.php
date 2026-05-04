<?php

namespace App\Http\Controllers;

use App\Models\Equipment;
use App\Models\BorrowRecord;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        // Get equipment scope based on role
        if ($user->role === 'dean') {
            $equipmentQuery = Equipment::query();
            $borrowQuery = BorrowRecord::with('equipment');

            if ($request->filled('lab_id')) {
                $equipmentQuery->where('laboratory_id', $request->lab_id);
                $borrowQuery->whereHas('equipment', function($q) use ($request) {
                    $q->where('laboratory_id', $request->lab_id);
                });
            }
        } else {
            $equipmentQuery = Equipment::where('laboratory_id', $user->laboratory_id);
            $borrowQuery = BorrowRecord::with('equipment')
                ->whereHas('equipment', function($q) use ($user) {
                    $q->where('laboratory_id', $user->laboratory_id);
                });
        }

        // Stats
        $damageCount = (clone $equipmentQuery)->where('status', 'damaged')->count();
        $underRepairCount = (clone $equipmentQuery)->where('status', 'under_repair')->count();
        $disposedCount = (clone $equipmentQuery)->where('status', 'disposed')->count();

        // Status Distribution
        $statuses = ['working' => 0, 'borrowed' => 0, 'under_repair' => 0, 'disposed' => 0, 'damaged' => 0];
        $statusCounts = (clone $equipmentQuery)
            ->selectRaw('status, count(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();
            
        foreach ($statusCounts as $status => $count) {
            $statuses[$status] = $count;
        }

        // Category Distribution
        $categoryDistribution = (clone $equipmentQuery)
            ->selectRaw('category, count(*) as count')
            ->groupBy('category')
            ->pluck('count', 'category')
            ->toArray();

        // Borrow History
        $borrowHistory = $borrowQuery->latest('borrowed_at')->take(10)->get();

        return view('reports.index', compact(
            'damageCount',
            'underRepairCount',
            'disposedCount',
            'statuses',
            'categoryDistribution',
            'borrowHistory'
        ));
    }

    public function export(Request $request)
    {
        $user = $request->user();
        
        $query = Equipment::with('laboratory');
        if ($user->role !== 'dean') {
            $query->where('laboratory_id', $user->laboratory_id);
        } elseif ($request->filled('lab_id')) {
            $query->where('laboratory_id', $request->lab_id);
        }

        $items = $query->get();

        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=laboratory_report.csv",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $columns = ['ID', 'Laboratory', 'Item Name', 'Category', 'Description', 'Model', 'Amount', 'Fund', 'PAR Number', 'Property Number', 'Status', 'Acquired Date'];

        $callback = function() use($items, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($items as $item) {
                fputcsv($file, [
                    $item->id,
                    $item->laboratory->name ?? 'N/A',
                    $item->item_name,
                    $item->category,
                    $item->description,
                    $item->model,
                    $item->amount,
                    $item->fund,
                    $item->par_number,
                    $item->property_number,
                    strtoupper($item->status),
                    $item->acquired_date
                ]);
            }
            fclose($file);
        };

        return Response::stream($callback, 200, $headers);
    }
}
