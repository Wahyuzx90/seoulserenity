<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        // Cek role
        if (!in_array(Auth::user()->role, ['staff', 'owner'])) {
            abort(403, 'Unauthorized access.');
        }
        
        // Ambil semua pesanan dari database
        $query = Order::with('items')->orderBy('created_at', 'desc');
        
        // Filter berdasarkan status
        if ($request->status && $request->status != 'all') {
            $query->where('status', $request->status);
        }
        
        $orders = $query->paginate(10);
        
        // Hitung statistik
        $todayOrders = Order::whereDate('created_at', today())->count();
        $pendingCount = Order::where('status', 'pending')->count();
        $processCount = Order::where('status', 'process')->count();
        $doneCount = Order::where('status', 'done')->count();
        
        return view('staff.orders', compact('orders', 'todayOrders', 'pendingCount', 'processCount', 'doneCount'));
    }
    
    public function show($id)
    {
        // Cek role
        if (!in_array(Auth::user()->role, ['staff', 'owner'])) {
            abort(403, 'Unauthorized access.');
        }
        
        $order = Order::with('items')->findOrFail($id);
        
        return view('staff.order-detail', compact('order'));
    }
    
    public function update(Request $request, $id)
    {
        // Cek role
        if (!in_array(Auth::user()->role, ['staff', 'owner'])) {
            abort(403, 'Unauthorized access.');
        }
        
        $order = Order::findOrFail($id);
        $newStatus = $request->input('status');
        
        // Validasi status yang valid
        $validStatuses = ['pending', 'process', 'ready', 'done', 'cancelled'];
        if (!in_array($newStatus, $validStatuses)) {
            return redirect()->back()->with('error', 'Status tidak valid!');
        }
        
        // Update status
        $order->status = $newStatus;
        $order->save();
        
        // Pesan sukses berdasarkan status
        $messages = [
            'process' => '✅ Pesanan sedang diproses!',
            'ready' => '✅ Pesanan sudah siap!',
            'done' => '✅ Pesanan selesai!',
            'cancelled' => '❌ Pesanan dibatalkan!',
            'pending' => '⏳ Pesanan menunggu konfirmasi!'
        ];
        
        $message = $messages[$newStatus] ?? 'Status pesanan berhasil diperbarui!';
        
        return redirect()->route('staff.orders.show', $id)->with('success', $message);
    }
    
    public function history(Request $request)
    {
        // Cek role
        if (!in_array(Auth::user()->role, ['staff', 'owner'])) {
            abort(403, 'Unauthorized access.');
        }
        
        $query = Order::with('items')
            ->whereIn('status', ['done', 'cancelled'])
            ->orderBy('created_at', 'desc');
        
        // Filter berdasarkan periode
        if ($request->period == 'today') {
            $query->whereDate('created_at', today());
        } elseif ($request->period == 'week') {
            $query->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()]);
        } elseif ($request->period == 'month') {
            $query->whereMonth('created_at', now()->month);
        }
        
        $orders = $query->paginate(10);
        
        $totalOrders = Order::whereIn('status', ['done', 'cancelled'])->count();
        $totalRevenue = Order::where('status', 'done')->sum('total');
        
        return view('staff.history', compact('orders', 'totalOrders', 'totalRevenue'));
    }
    
    public function dashboard()
    {
        // Cek role
        if (!in_array(Auth::user()->role, ['staff', 'owner'])) {
            abort(403, 'Unauthorized access.');
        }
        
        $todayOrders = Order::whereDate('created_at', today())->count();
        $pendingCount = Order::where('status', 'pending')->count();
        $processCount = Order::where('status', 'process')->count();
        $doneCount = Order::where('status', 'done')->count();
        
        return view('staff.dashboard', compact('todayOrders', 'pendingCount', 'processCount', 'doneCount'));
    }
}