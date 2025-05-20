<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ProfitsReport;
use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ProfitsReportController extends Controller
{
    // public function generateDailyReport()
    // {
    //     $today = Carbon::today();

    //     // Hanya menghitung order yang statusnya 'completed'
    //     $orders = Order::whereDate('order_date', $today)
    //         ->where('status', 'completed')
    //         ->get();

    //     $totalRevenue = $orders->sum('total_price');
    //     $totalCost = $orders->sum(function ($order) {
    //         return $order->orderItems->sum(function ($item) {
    //             return $item->product->cost_price * $item->quantity;
    //         });
    //     });

    //     $totalProfit = $totalRevenue - $totalCost;

    //     ProfitsReport::updateOrCreate(
    //         ['report_date' => $today],
    //         ['total_revenue' => $totalRevenue, 'total_cost' => $totalCost, 'total_profit' => $totalProfit]
    //     );

    //     return response()->json(['message' => 'Daily report generated successfully']);
    // }

    public function generateDailyReport()
    {
        $today = Carbon::today();

        
        $orders = Order::whereDate('order_date', $today)
            ->where('status', 'completed')
            ->get();

        $totalRevenue = $orders->sum('total_price');
        $totalCost = $orders->sum(function ($order) {
            return $order->orderItems->sum(function ($item) {
                return $item->product->cost_price * $item->quantity;
            });
        });

        $totalProfit = $totalRevenue - $totalCost;


        ProfitsReport::updateOrCreate(
            ['report_date' => $today],
            [
                'total_revenue' => DB::raw("total_revenue + $totalRevenue"),
                'total_cost' => DB::raw("total_cost + $totalCost"),
                'total_profit' => DB::raw("total_profit + $totalProfit")
            ]
        );

        return response()->json(['message' => 'Daily report generated successfully']);
    }



    public function getWeeklyReport()
    {
        $reports = ProfitsReport::where('report_date', '>=', Carbon::today()->subDays(7))
            ->orderBy('report_date', 'asc')
            ->get(['report_date', 'total_revenue', 'total_profit', 'total_cost']);

        return response()->json($reports);
    }

    public function getMonthlyReport()
    {
        $reports = ProfitsReport::where('report_date', '>=', Carbon::today()->subDays(30))
            ->orderBy('report_date', 'asc')
            ->get(['report_date', 'total_revenue', 'total_profit', 'total_cost']);

        return response()->json($reports);
    }

    public function getAllReport()
    {
        $reports = ProfitsReport::orderBy('report_date', 'asc')
            ->get(['report_date', 'total_revenue', 'total_profit', 'total_cost']);

        return response()->json($reports);
    }


    public function getReport(Request $request)
    {
        $date = $request->query('date', Carbon::today()->toDateString());
        $report = ProfitsReport::where('report_date', $date)->first();

        if (!$report) {
            return response()->json(['message' => 'No report found for this date'], 404);
        }

        return response()->json($report);
    }

    public function getCategoryReport(Request $request)
    {
        $categoryId = $request->query('category_id');
        $date = $request->query('date', Carbon::today()->toDateString());

        if (!$categoryId) {
            return response()->json(['message' => 'category_id is required'], 400);
        }

        // Ambil nama kategori
        $categoryName = \App\Models\Category::where('id', $categoryId)->value('name');

        // Ambil semua order yang sudah completed pada tanggal tertentu
        $orders = Order::whereDate('order_date', $date)
            ->where('status', 'completed')
            ->with(['orderItems.product'])
            ->get();

        // Filter hanya order item yang berasal dari kategori yang dipilih
        $filteredOrderItems = collect(); // Koleksi kosong untuk menampung item

        foreach ($orders as $order) {
            foreach ($order->orderItems as $item) {
                if ($item->product->category_id == $categoryId) {
                    $filteredOrderItems->push($item);
                }
            }
        }

        // Hitung total revenue, cost, dan profit hanya dari item yang sesuai kategori
        $totalRevenue = $filteredOrderItems->sum('subtotal');

        $totalCost = $filteredOrderItems->sum(function ($item) {
            return $item->product->cost_price * $item->quantity;
        });

        $totalProfit = $totalRevenue - $totalCost;

        return response()->json([
            'message' => 'Laporan kategori',
            'date' => $date,
            'category_id' => $categoryId,
            'category_name' => $categoryName,
            'total_revenue' => $totalRevenue,
            'total_cost' => $totalCost,
            'total_profit' => $totalProfit,
            'data' => $filteredOrderItems->values(),
        ]);
    }


    public function getCategoryReportbyDate(Request $request)
    {
        $categoryId = $request->query('category_id');
        $date = $request->query('date', Carbon::today()->toDateString());

        if (!$categoryId) {
            return response()->json(['message' => 'category_id is required'], 400);
        }

        // Ambil nama kategori
        $category = \App\Models\Category::find($categoryId);
        if (!$category) {
            return response()->json(['message' => 'Category not found'], 404);
        }

        // Ambil semua order yang sudah completed pada tanggal tertentu, beserta item dan produk
        $orders = Order::whereDate('order_date', $date)
            ->where('status', 'completed')
            ->with(['orderItems.product' => function ($query) use ($categoryId) {
                $query->where('category_id', $categoryId);
            }])
            ->get();

        $filteredItems = collect();

        // Ambil item yang sesuai kategori dari setiap order
        foreach ($orders as $order) {
            foreach ($order->orderItems as $item) {
                if ($item->product && $item->product->category_id == $categoryId) {
                    $filteredItems->push($item);
                }
            }
        }

        // Hitung total
        $totalRevenue = $filteredItems->sum('subtotal');
        $totalCost = $filteredItems->sum(function ($item) {
            return $item->product->cost_price * $item->quantity;
        });
        $totalProfit = $totalRevenue - $totalCost;

        return response()->json([
            'message' => 'Laporan kategori berdasarkan tanggal',
            'date' => $date,
            'category_id' => $categoryId,
            'category_name' => $category->name,
            'total_revenue' => $totalRevenue,
            'total_cost' => $totalCost,
            'total_profit' => $totalProfit,
            'data' => $filteredItems->values(),
        ]);
    }
}
