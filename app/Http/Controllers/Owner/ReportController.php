<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Menu;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        // Cek role owner
        if (Auth::user()->role !== 'owner') {
            abort(403, 'Unauthorized access. Owner only.');
        }
        
        $period = $request->get('period', 'monthly');
        
        // Hitung statistik
        $totalRevenue = Order::where('status', 'done')->sum('total');
        $totalOrders = Order::count();
        $deliveryOrders = Order::where('delivery_type', 'delivery')->count();
        $pickupOrders = Order::where('delivery_type', 'pickup')->count();
        $pendingOrders = Order::where('status', 'pending')->count();
        $processOrders = Order::where('status', 'process')->count();
        $completedOrders = Order::where('status', 'done')->count();
        $cancelledOrders = Order::where('status', 'cancelled')->count();
        
        // Hitung rata-rata pesanan
        $averageOrder = $totalOrders > 0 ? $totalRevenue / $totalOrders : 0;
        
        // Data untuk grafik (7 hari terakhir)
        $chartData = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $chartData[] = [
                'date' => $date->format('D'),
                'total' => Order::whereDate('created_at', $date)->sum('total'),
                'count' => Order::whereDate('created_at', $date)->count()
            ];
        }
        
        // Menu terlaris
        $topMenus = DB::table('order_items')
            ->select('menu_name', DB::raw('SUM(quantity) as total_sold'))
            ->groupBy('menu_name')
            ->orderBy('total_sold', 'desc')
            ->limit(5)
            ->get();
        
        // Transaksi terbaru
        $recentTransactions = Order::with('user')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();
        
        return view('owner.reports', compact(
            'totalRevenue', 'totalOrders', 'deliveryOrders', 'pickupOrders',
            'pendingOrders', 'processOrders', 'completedOrders', 'cancelledOrders',
            'averageOrder', 'chartData', 'topMenus', 'recentTransactions', 'period'
        ));
    }
    
    public function orders(Request $request)
    {
        // Cek role owner
        if (Auth::user()->role !== 'owner') {
            abort(403, 'Unauthorized access. Owner only.');
        }
        
        $query = Order::with('user', 'items')->orderBy('created_at', 'desc');
        
        if ($request->status && $request->status != 'all') {
            $query->where('status', $request->status);
        }
        
        $orders = $query->paginate(10);
        
        $totalOrders = Order::count();
        $totalRevenue = Order::where('status', 'done')->sum('total');
        $pendingCount = Order::where('status', 'pending')->count();
        $completedCount = Order::where('status', 'done')->count();
        
        return view('owner.orders', compact('orders', 'totalOrders', 'totalRevenue', 'pendingCount', 'completedCount'));
    }
    
    public function exportPDF(Request $request)
    {
        // Cek role owner
        if (Auth::user()->role !== 'owner') {
            abort(403, 'Unauthorized access. Owner only.');
        }
        
        $period = $request->get('period', 'monthly');
        
        // Filter periode
        $query = Order::with('items');
        
        if ($period == 'daily') {
            $query->whereDate('created_at', today());
        } elseif ($period == 'weekly') {
            $query->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()]);
        } elseif ($period == 'yearly') {
            $query->whereYear('created_at', now()->year);
        }
        
        $recentOrders = $query->orderBy('created_at', 'desc')->limit(20)->get();
        
        // Data untuk laporan
        $totalRevenue = Order::where('status', 'done')->sum('total');
        $totalOrders = Order::count();
        $deliveryOrders = Order::where('delivery_type', 'delivery')->count();
        $completedOrders = Order::where('status', 'done')->count();
        $pendingOrders = Order::where('status', 'pending')->count();
        $processOrders = Order::where('status', 'process')->count();
        $cancelledOrders = Order::where('status', 'cancelled')->count();
        $averageOrder = $totalOrders > 0 ? $totalRevenue / $totalOrders : 0;
        
        // Data menu terlaris
        $topMenus = DB::table('order_items')
            ->select('menu_name', DB::raw('SUM(quantity) as total_sold'))
            ->groupBy('menu_name')
            ->orderBy('total_sold', 'desc')
            ->limit(10)
            ->get();
        
        $data = [
            'title' => 'Laporan Penjualan Seoul Serenity',
            'date' => now()->format('d F Y'),
            'period' => $period,
            'totalRevenue' => $totalRevenue,
            'totalOrders' => $totalOrders,
            'deliveryOrders' => $deliveryOrders,
            'completedOrders' => $completedOrders,
            'pendingOrders' => $pendingOrders,
            'processOrders' => $processOrders,
            'cancelledOrders' => $cancelledOrders,
            'averageOrder' => $averageOrder,
            'recentOrders' => $recentOrders,
            'topMenus' => $topMenus,
        ];
        
        // Gunakan view untuk HTML yang bisa di-print
        $html = view('owner.export-pdf', $data)->render();
        
        // Kirim response HTML yang bisa di-print ke PDF
        return response($html)
            ->header('Content-Type', 'text/html')
            ->header('Content-Disposition', 'inline; filename="laporan-penjualan-' . now()->format('Y-m-d') . '.html"');
    }
    
    public function exportExcel(Request $request)
    {
        // Cek role owner
        if (Auth::user()->role !== 'owner') {
            abort(403, 'Unauthorized access. Owner only.');
        }
        
        $period = $request->get('period', 'monthly');
        
        // Filter periode
        $query = Order::with('items');
        
        if ($period == 'daily') {
            $query->whereDate('created_at', today());
        } elseif ($period == 'weekly') {
            $query->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()]);
        } elseif ($period == 'yearly') {
            $query->whereYear('created_at', now()->year);
        }
        
        $orders = $query->orderBy('created_at', 'desc')->get();
        
        $filename = 'laporan-penjualan-' . now()->format('Y-m-d') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];
        
        $callback = function() use ($orders) {
            $handle = fopen('php://output', 'w');
            
            // Header CSV
            fputcsv($handle, [
                'No. Pesanan', 
                'Pelanggan', 
                'Telepon', 
                'Alamat', 
                'Metode Bayar',
                'Subtotal', 
                'Pajak', 
                'Ongkir', 
                'Total', 
                'Status', 
                'Tanggal'
            ], ';');
            
            // Data CSV
            foreach ($orders as $order) {
                fputcsv($handle, [
                    $order->order_number,
                    $order->customer_name,
                    $order->customer_phone,
                    $order->customer_address,
                    $order->payment_method,
                    $order->subtotal,
                    $order->tax,
                    $order->delivery_fee,
                    $order->total,
                    $order->status,
                    $order->created_at->format('Y-m-d H:i:s')
                ], ';');
            }
            
            fclose($handle);
        };
        
        return response()->stream($callback, 200, $headers);
    }
}