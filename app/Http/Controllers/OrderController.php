<?php

// app/Http/Controllers/OrderController.php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function createOrder(Request $request)
    {
        $order = Order::create([
            'order_code' => 'ORD-' . time(),
            'status' => 'pending',
            'total_price' => 0,
            'order_date' => Carbon::now(),
        ]);

        $totalPrice = 0;
        foreach ($request->items as $item) {
            $product = Product::find($item['product_id']);
            if (!$product) {
                return response()->json(['message' => 'Product not found'], 404);
            }

            $subtotal = $product->price * $item['quantity'];
            $totalPrice += $subtotal;

            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $item['product_id'],
                'quantity' => $item['quantity'],
                'price' => $product->price,
                'subtotal' => $subtotal,
            ]);
        }

        $order->update(['total_price' => $totalPrice]);

        return response()->json(['message' => 'Order created successfully', 'order' => $order]);
    }


    public function getTodayTransactions()
    {
        $today = Carbon::today(); // Mengambil tanggal hari ini

        $orders = Order::whereDate('order_date', $today)
            ->with(['orderItems.product']) // Memuat relasi dengan order_items dan product
            ->get();

        return response()->json([
            'message' => 'Transaksi hari ini',
            'date' => $today->toDateString(),
            'data' => $orders
        ]);
    }

    public function getTransactions()
    {


        $orders = Order::with(['orderItems.product'])
            ->get();

        return response()->json([
            'message' => 'Transaksi',
            'data' => $orders
        ]);
    }

    public function updateOrderStatus(Request $request, $id)
    {
        return DB::transaction(function () use ($request, $id) {
            $order = Order::find($id);
            if (!$order) {
                return response()->json(['message' => 'Order not found'], 404);
            }

            $order->update([
                'status' => $request->status
            ]);

            if ($request->status === 'completed') {
                $this->updateDailyReport($order);
            }

            return response()->json(['message' => 'Order updated successfully', 'order' => $order]);
        });
    }

    private function updateDailyReport(Order $order)
    {
        $today = Carbon::today();

        $totalCost = $order->orderItems->sum(function ($item) {
            return $item->product->cost_price * $item->quantity;
        });

        DB::table('profits_reports')->updateOrInsert(
            ['report_date' => $today],
            [
                'total_revenue' => DB::raw("COALESCE(total_revenue, 0) + {$order->total_price}"),
                'total_cost' => DB::raw("COALESCE(total_cost, 0) + {$totalCost}"),
                'total_profit' => DB::raw("COALESCE(total_profit, 0) + ({$order->total_price} - {$totalCost})"),
            ]
        );
    }
}
