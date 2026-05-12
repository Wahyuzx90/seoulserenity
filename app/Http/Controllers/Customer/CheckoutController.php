<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{
    public function store(Request $request)
    {
        try {
            DB::beginTransaction();

            // Generate order number
            $orderNumber = $request->order_number ?? 'SSR-' . date('Ymd') . '-' . str_pad(mt_rand(1, 9999), 4, '0', STR_PAD_LEFT);

            // Buat order baru
            $order = Order::create([
                'order_number' => $orderNumber,
                'user_id' => Auth::id(),
                'customer_name' => $request->fullname,
                'customer_phone' => $request->phone,
                'customer_address' => $request->address,
                'payment_method' => $request->payment_method,
                'notes' => $request->notes,
                'subtotal' => $request->subtotal,
                'tax' => $request->tax,
                'delivery_fee' => $request->delivery ?? 10000,
                'total' => $request->total,
                'status' => 'pending',
                'delivery_type' => 'delivery',
            ]);

            // Simpan item pesanan
            $items = $request->items;
            foreach ($items as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'menu_id' => $item['id'],
                    'menu_name' => $item['name'],
                    'menu_emoji' => $item['emoji'],
                    'menu_image' => $item['image'] ?? null,
                    'quantity' => $item['qty'],
                    'price' => $item['price'],
                ]);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'order' => $order,
                'message' => 'Pesanan berhasil dibuat!'
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}