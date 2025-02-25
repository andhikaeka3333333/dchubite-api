<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ProfitsReport;
use App\Models\Order;
use Carbon\Carbon;

class ProfitsReportController extends Controller
{
    public function generateDailyReport()
    {
        $today = Carbon::today();

        // Hanya menghitung order yang statusnya 'completed'
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
            ['total_revenue' => $totalRevenue, 'total_cost' => $totalCost, 'total_profit' => $totalProfit]
        );

        return response()->json(['message' => 'Daily report generated successfully']);
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

        // Hanya mengambil order dengan status 'completed'
        $orders = Order::whereDate('order_date', $date)
            ->where('status', 'completed')
            ->whereHas('orderItems.product.category', function ($q) use ($categoryId) {
                $q->where('id', $categoryId);
            })
            ->with(['orderItems.product'])
            ->get();

        $totalRevenue = $orders->sum('total_price');
        $totalCost = $orders->sum(function ($order) {
            return $order->orderItems->sum(function ($item) {
                return $item->product->cost_price * $item->quantity;
            });
        });
        $totalProfit = $totalRevenue - $totalCost;

        return response()->json([
            'message' => 'Laporan kategori',
            'date' => $date,
            'category_id' => $categoryId,
            'category_name' => $categoryName, // Menampilkan nama kategori
            'total_revenue' => $totalRevenue,
            'total_cost' => $totalCost,
            'total_profit' => $totalProfit,
            'data' => $orders
        ]);
    }
}
