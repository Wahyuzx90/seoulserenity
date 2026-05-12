@extends('layouts.dashboard')
@section('title', 'Owner — Laporan Penjualan')
@section('role', 'Owner')
@section('sidebar-role', '👑 Owner')

@section('sidebar-nav')
<div class="nav-section-label">Laporan</div>
<a href="{{ route('owner.reports') }}" class="nav-item active">
    <span class="ni">📊</span> Laporan Penjualan
</a>
<a href="{{ route('owner.orders') }}" class="nav-item">
    <span class="ni">📋</span> Semua Pesanan
</a>
<a href="{{ route('owner.menus') }}" class="nav-item">
    <span class="ni">🍽️</span> Manajemen Menu
</a>
@endsection

@section('page-title', 'Laporan Penjualan')
@section('page-sub', 'Statistik dan laporan penjualan restoran')

@push('styles')
<style>
.stats-row {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 20px;
    margin-bottom: 30px;
}

.stat-card {
    background: white;
    border-radius: 16px;
    padding: 20px;
    box-shadow: 0 1px 3px rgba(0,0,0,0.08);
    transition: all 0.2s;
}

.stat-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.12);
}

.stat-card.dark {
    background: linear-gradient(135deg, #c23b22, #a32e18);
    color: white;
}

.stat-card.dark .stat-label,
.stat-card.dark .stat-delta {
    color: rgba(255,255,255,0.7);
}

.stat-icon {
    font-size: 28px;
    margin-bottom: 8px;
}

.stat-label {
    font-size: 12px;
    color: #6c6c70;
    margin-bottom: 6px;
}

.stat-value {
    font-size: 24px;
    font-weight: 700;
    color: #1c1c1e;
}

.stat-card.dark .stat-value {
    color: white;
}

.filter-section {
    background: white;
    border-radius: 16px;
    padding: 16px 20px;
    margin-bottom: 24px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 12px;
    box-shadow: 0 1px 3px rgba(0,0,0,0.08);
}

.filter-group {
    display: flex;
    gap: 8px;
    flex-wrap: wrap;
}

.filter-btn {
    padding: 6px 18px;
    border-radius: 30px;
    text-decoration: none;
    font-size: 12px;
    font-weight: 500;
    transition: all 0.2s;
    background: #f2f2f7;
    color: #6c6c70;
}

.filter-btn.active {
    background: #c23b22;
    color: white;
}

.filter-btn:hover:not(.active) {
    background: #e5e5ea;
}

.export-buttons {
    display: flex;
    gap: 10px;
}

.btn-export {
    padding: 6px 18px;
    border-radius: 30px;
    font-size: 12px;
    font-weight: 500;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    text-decoration: none;
    border: none;
}

.btn-export-pdf {
    background: #c23b22;
    color: white;
}

.btn-export-pdf:hover {
    background: #a32e18;
}

.btn-export-excel {
    background: #4a6741;
    color: white;
}

.btn-export-excel:hover {
    background: #3a5232;
}

.charts-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 20px;
    margin-bottom: 24px;
}

.card {
    background: white;
    border-radius: 16px;
    box-shadow: 0 1px 3px rgba(0,0,0,0.08);
    overflow: hidden;
}

.section-head {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 16px 20px;
    border-bottom: 1px solid #e5e5ea;
}

.section-head .acl {
    font-size: 16px;
    font-weight: 600;
    color: #1c1c1e;
    display: flex;
    align-items: center;
    gap: 8px;
}

.chart-container {
    padding: 20px;
}

.simple-chart {
    display: flex;
    align-items: flex-end;
    gap: 12px;
    height: 200px;
    margin-top: 20px;
}

.chart-bar-wrapper {
    flex: 1;
    text-align: center;
}

.chart-bar {
    background: linear-gradient(180deg, #c23b22 0%, #a32e18 100%);
    border-radius: 8px 8px 0 0;
    transition: height 0.3s;
    margin-bottom: 8px;
}

.chart-label {
    font-size: 10px;
    color: #6c6c70;
}

.chart-value {
    font-size: 9px;
    font-weight: 600;
    color: #c23b22;
    margin-top: 4px;
}

.chart-item {
    margin-bottom: 16px;
}

.chart-label-text {
    display: flex;
    justify-content: space-between;
    font-size: 12px;
    margin-bottom: 6px;
    color: #6c6c70;
}

.chart-bar-bg {
    background: #f0f0f0;
    border-radius: 10px;
    overflow: hidden;
    height: 30px;
}

.chart-bar-fill {
    background: linear-gradient(90deg, #c23b22, #a32e18);
    height: 100%;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: flex-end;
    padding-right: 10px;
    color: white;
    font-size: 11px;
    font-weight: 600;
}

.data-table {
    width: 100%;
    border-collapse: collapse;
}

.data-table th {
    text-align: left;
    padding: 14px 12px;
    font-size: 12px;
    font-weight: 600;
    color: #6c6c70;
    background: #f9f9fb;
    border-bottom: 1px solid #e5e5ea;
}

.data-table td {
    padding: 14px 12px;
    font-size: 13px;
    border-bottom: 1px solid #f0f0f0;
    vertical-align: middle;
}

.status-badge {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    padding: 4px 12px;
    border-radius: 30px;
    font-size: 11px;
    font-weight: 600;
}

.status-done {
    background: #e8f5e9;
    color: #2e7d32;
}

.status-pending {
    background: #fff3e0;
    color: #f57c00;
}

.chip {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    padding: 4px 10px;
    border-radius: 20px;
    font-size: 11px;
    font-weight: 500;
}

.chip.deliver {
    background: #e3f2fd;
    color: #1976d2;
}

.chip.pickup {
    background: #fff3e0;
    color: #f57c00;
}

.price {
    font-weight: 600;
    color: #4a6741;
}

.empty-state {
    text-align: center;
    padding: 60px 20px;
    color: #8e8e93;
}

.empty-state-icon {
    font-size: 64px;
    margin-bottom: 16px;
    opacity: 0.5;
}

.empty-state-text {
    font-size: 13px;
}

@media (max-width: 1024px) {
    .stats-row {
        grid-template-columns: repeat(2, 1fr);
        gap: 15px;
    }
    .charts-row {
        grid-template-columns: 1fr;
        gap: 16px;
    }
}

@media (max-width: 768px) {
    .stats-row {
        grid-template-columns: 1fr;
        gap: 12px;
    }
    .filter-section {
        flex-direction: column;
        align-items: stretch;
    }
    .filter-group {
        justify-content: center;
    }
    .export-buttons {
        justify-content: center;
    }
}
</style>
@endpush

@section('content')
<!-- Stats Row -->
<div class="stats-row">
    <div class="stat-card dark">
        <div class="stat-icon">💰</div>
        <div class="stat-label">Total Pendapatan</div>
        <div class="stat-value">Rp {{ number_format($totalRevenue ?? 0, 0, ',', '.') }}</div>
    </div>
    <div class="stat-card">
        <div class="stat-icon">📦</div>
        <div class="stat-label">Total Pesanan</div>
        <div class="stat-value">{{ $totalOrders ?? 0 }}</div>
    </div>
    <div class="stat-card">
        <div class="stat-icon">🛵</div>
        <div class="stat-label">Pesanan Antar</div>
        <div class="stat-value">{{ $deliveryOrders ?? 0 }}</div>
    </div>
    <div class="stat-card">
        <div class="stat-icon">⭐</div>
        <div class="stat-label">Rata-rata Pesanan</div>
        <div class="stat-value">Rp {{ number_format($averageOrder ?? 0, 0, ',', '.') }}</div>
    </div>
</div>

<!-- Filter Section -->
<div class="filter-section">
    
    <div class="export-buttons">
        <a href="{{ route('owner.export.pdf', ['period' => request('period', 'monthly')]) }}" class="btn-export btn-export-pdf">
            📄 Export PDF
        </a>
        
    </div>
</div>

<!-- Charts Row -->
<div class="charts-row">
    <!-- Revenue Chart -->
    <div class="card">
        <div class="section-head">
            <div class="acl">📈 Grafik Pendapatan</div>
        </div>
        <div class="chart-container">
            <div class="simple-chart">
                @foreach($chartData ?? [] as $data)
                <div class="chart-bar-wrapper">
                    @php
                        $maxTotal = max(array_column($chartData, 'total'));
                        $height = $maxTotal > 0 ? ($data['total'] / $maxTotal) * 140 : 5;
                    @endphp
                    <div class="chart-bar" style="height: {{ max($height, 5) }}px;"></div>
                    <div class="chart-label">{{ $data['date'] }}</div>
                    <div class="chart-value">Rp {{ number_format($data['total'], 0, ',', '.') }}</div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Top Menus -->
    <div class="card">
        <div class="section-head">
            <div class="acl">🔥 Menu Terlaris</div>
        </div>
        <div class="chart-container">
            @forelse($topMenus ?? [] as $menu)
            @php
                $maxSold = $topMenus->first()->total_sold ?? 1;
                $width = ($menu->total_sold / $maxSold) * 100;
            @endphp
            <div class="chart-item">
                <div class="chart-label-text">
                    <span>🍽️ {{ $menu->menu_name }}</span>
                    <span>{{ $menu->total_sold }} terjual</span>
                </div>
                <div class="chart-bar-bg">
                    <div class="chart-bar-fill" style="width: {{ $width }}%;">{{ round($width) }}%</div>
                </div>
            </div>
            @empty
            <div class="empty-state">
                <div class="empty-state-icon">📊</div>
                <div class="empty-state-text">Belum ada data menu terlaris</div>
            </div>
            @endforelse
        </div>
    </div>
</div>

<!-- Recent Transactions -->
<div class="card">
    <div class="section-head">
        <div class="acl">📋 Transaksi Terbaru</div>
        <a href="{{ route('owner.orders') }}" class="sh-action">Lihat semua →</a>
    </div>
    <table class="data-table">
        <thead>
            <tr>
                <th>ID Transaksi</th>
                <th>Pelanggan</th>
                <th>Item</th>
                <th>Metode</th>
                <th>Tanggal</th>
                <th>Total</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($recentTransactions ?? [] as $tx)
            <tr>
                <td style="font-weight:600;color:#c23b22;">{{ $tx->order_number }}</td>
                <td>{{ $tx->customer_name }} <br><small style="color:#8e8e93;">{{ $tx->customer_phone }}</small></td>
                <td style="font-size:12px;color:#6c6c70;">{{ $tx->items->count() }} item</td>
                <td>
                    <span class="chip {{ $tx->delivery_type == 'delivery' ? 'deliver' : 'pickup' }}">
                        {{ $tx->delivery_type == 'delivery' ? '🚚 Antar' : '🏪 Ambil' }}
                    </span>
                </td>
                <td style="font-size:12px;white-space:nowrap;">{{ $tx->created_at->format('d M Y H:i') }}</td>
                <td class="price">Rp {{ number_format($tx->total, 0, ',', '.') }}</td>
                <td>
                    <span class="status-badge {{ $tx->status == 'done' ? 'status-done' : 'status-pending' }}">
                        {{ $tx->status == 'done' ? '✔ Selesai' : '⏳ Proses' }}
                    </span>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="empty-state">
                    <div class="empty-state-icon">📭</div>
                    <div class="empty-state-text">Belum ada transaksi</div>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection