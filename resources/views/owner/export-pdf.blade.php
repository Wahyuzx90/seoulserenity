<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ $title }}</title>
    <style>
        @media print {
            body {
                margin: 0;
                padding: 0;
            }
            .no-print {
                display: none;
            }
            .page-break {
                page-break-before: always;
            }
        }
        
        body {
            font-family: 'Helvetica', Arial, sans-serif;
            font-size: 12px;
            margin: 0;
            padding: 20px;
            background: white;
        }
        
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #c23b22;
            padding-bottom: 10px;
        }
        
        .header h1 {
            color: #c23b22;
            margin: 0;
            font-size: 24px;
        }
        
        .header p {
            margin: 5px 0;
            color: #666;
        }
        
        .print-btn {
            background: #c23b22;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            margin-bottom: 20px;
            font-size: 14px;
        }
        
        .print-btn:hover {
            background: #a32e18;
        }
        
        .summary {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
            margin-bottom: 30px;
        }
        
        .summary-card {
            flex: 1;
            min-width: 150px;
            background: #f9f9f9;
            padding: 15px;
            border-radius: 8px;
            text-align: center;
            border-left: 4px solid #c23b22;
        }
        
        .summary-card .label {
            font-size: 11px;
            color: #666;
            margin-bottom: 5px;
        }
        
        .summary-card .value {
            font-size: 18px;
            font-weight: bold;
            color: #333;
        }
        
        .section-title {
            font-size: 16px;
            font-weight: bold;
            margin: 20px 0 10px;
            color: #c23b22;
            border-left: 4px solid #c23b22;
            padding-left: 10px;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        
        th {
            background: #c23b22;
            color: white;
            padding: 10px;
            text-align: left;
            font-size: 11px;
        }
        
        td {
            padding: 8px;
            border-bottom: 1px solid #ddd;
            font-size: 10px;
        }
        
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 10px;
            color: #999;
            border-top: 1px solid #ddd;
            padding-top: 10px;
        }
        
        .status-badge {
            display: inline-block;
            padding: 2px 8px;
            border-radius: 20px;
            font-size: 9px;
            font-weight: bold;
        }
        
        .status-done {
            background: #e8f5e9;
            color: #2e7d32;
        }
        
        .status-pending {
            background: #fff3e0;
            color: #f57c00;
        }
        
        .text-right {
            text-align: right;
        }
        
        .text-center {
            text-align: center;
        }
    </style>
</head>
<body>
    <button class="print-btn no-print" onclick="window.print()">🖨️ Cetak / Simpan PDF</button>
    
    <div class="header">
        <h1>{{ $title }}</h1>
        <p>Periode: {{ ucfirst($period) }} | Tanggal Cetak: {{ $date }}</p>
    </div>

    <div class="summary">
        <div class="summary-card">
            <div class="label">💰 Total Pendapatan</div>
            <div class="value">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</div>
        </div>
        <div class="summary-card">
            <div class="label">📦 Total Pesanan</div>
            <div class="value">{{ $totalOrders }}</div>
        </div>
        <div class="summary-card">
            <div class="label">🛵 Pesanan Antar</div>
            <div class="value">{{ $deliveryOrders }}</div>
        </div>
        <div class="summary-card">
            <div class="label">⭐ Rata-rata Pesanan</div>
            <div class="value">Rp {{ number_format($averageOrder, 0, ',', '.') }}</div>
        </div>
    </div>

    <div class="section-title">📊 Statistik Pesanan</div>
    <table>
        <thead>
            <tr>
                <th>Status</th>
                <th class="text-center">Jumlah</th>
                <th class="text-center">Persentase</th>
            </tr>
        </thead>
        <tbody>
            <tr style="color: #2e7d32;">
                <td>✅ Selesai</td>
                <td class="text-center">{{ $completedOrders }}</td>
                <td class="text-center">{{ $totalOrders > 0 ? round(($completedOrders / $totalOrders) * 100, 1) : 0 }}%</td>
            </tr>
            <tr style="color: #f57c00;">
                <td>⏳ Menunggu</td>
                <td class="text-center">{{ $pendingOrders }}</td>
                <td class="text-center">{{ $totalOrders > 0 ? round(($pendingOrders / $totalOrders) * 100, 1) : 0 }}%</td>
            </tr>
            <tr style="color: #1976d2;">
                <td>🍳 Diproses</td>
                <td class="text-center">{{ $processOrders }}</td>
                <td class="text-center">{{ $totalOrders > 0 ? round(($processOrders / $totalOrders) * 100, 1) : 0 }}%</td>
            </tr>
            <tr style="color: #d32f2f;">
                <td>❌ Dibatalkan</td>
                <td class="text-center">{{ $cancelledOrders }}</td>
                <td class="text-center">{{ $totalOrders > 0 ? round(($cancelledOrders / $totalOrders) * 100, 1) : 0 }}%</td>
            </tr>
        </tbody>
    </table>

    <div class="section-title">🔥 Menu Terlaris</div>
    <table>
        <thead>
            <tr>
                <th>Menu</th>
                <th class="text-center">Terjual</th>
            </tr>
        </thead>
        <tbody>
            @foreach($topMenus as $menu)
            <tr>
                <td>{{ $menu->menu_name }}</td>
                <td class="text-center">{{ $menu->total_sold }} porsi</td>
            </tr>
            @endforeach
            @if($topMenus->count() == 0)
            <tr><td colspan="2" class="text-center">Belum ada data</td></tr>
            @endif
        </tbody>
    </table>

    <div class="section-title">📋 Pesanan Terbaru</div>
    <table>
        <thead>
            <tr>
                <th>No. Pesanan</th>
                <th>Pelanggan</th>
                <th class="text-right">Total</th>
                <th class="text-center">Status</th>
                <th class="text-center">Tanggal</th>
            </tr>
        </thead>
        <tbody>
            @foreach($recentOrders as $order)
            <tr>
                <td>{{ $order->order_number }}</td>
                <td>{{ $order->customer_name }}</td>
                <td class="text-right">Rp {{ number_format($order->total, 0, ',', '.') }}</td>
                <td class="text-center">
                    <span class="status-badge {{ $order->status == 'done' ? 'status-done' : 'status-pending' }}">
                        {{ ucfirst($order->status) }}
                    </span>
                </td>
                <td class="text-center">{{ $order->created_at->format('d/m/Y H:i') }}</td>
            </tr>
            @endforeach
            @if($recentOrders->count() == 0)
            <tr><td colspan="5" class="text-center">Belum ada pesanan</td></tr>
            @endif
        </tbody>
    </table>

    <div class="footer">
        <p>Laporan ini dibuat secara otomatis oleh sistem Seoul Serenity</p>
    </div>
</body>
</html>