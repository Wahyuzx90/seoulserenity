<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::with(['items.menu'])
            ->where('user_id', auth()->id())
            ->latest()->paginate(10);

        return view('customer.orders', compact('orders'));
    }

    public function checkout()
    {
        return view('customer.checkout');
    }

    public function store(Request $request)
    {
        $request->validate([
            'items'            => 'required|array|min:1',
            'items.*.menu_id'  => 'required|exists:menus,id',
            'items.*.qty'      => 'required|integer|min:1',
            'delivery_type'    => 'required|in:delivery,pickup',
            'recipient_name'   => 'required|string',
            'recipient_phone'  => 'required|string',
            'address'          => 'nullable|string',
            'payment_method'   => 'required|in:transfer,cash,qris',
        ]);

        $subtotal    = 0;
        $orderItems  = [];

        foreach ($request->items as $item) {
            $menu = Menu::findOrFail($item['menu_id']);
            $line = $menu->price * $item['qty'];
            $subtotal += $line;
            $orderItems[] = ['menu_id' => $menu->id, 'qty' => $item['qty'], 'price' => $menu->price];
        }

        $deliveryFee = $request->delivery_type === 'delivery' ? 10000 : 0;
        $total       = $subtotal + $deliveryFee;

        $order = Order::create([
            'user_id'         => auth()->id(),
            'status'          => 'pending',
            'delivery_type'   => $request->delivery_type,
            'recipient_name'  => $request->recipient_name,
            'recipient_phone' => $request->recipient_phone,
            'address'         => $request->address,
            'subtotal'        => $subtotal,
            'discount'        => 0,
            'delivery_fee'    => $deliveryFee,
            'total'           => $total,
            'payment_method'  => $request->payment_method,
        ]);

        foreach ($orderItems as $item) {
            OrderItem::create(array_merge($item, ['order_id' => $order->id]));
        }

        return redirect()->route('customer.orders')
                         ->with('success', 'Pesanan #SSR-' . str_pad($order->id, 4, '0', STR_PAD_LEFT) . ' berhasil dibuat! 🎉');
    }
}
