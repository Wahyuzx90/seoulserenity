@extends('layouts.dashboard')
@section('title', 'Staff — Dashboard')
@section('role', 'Staff')
@section('sidebar-role', '👔 Karyawan')

@section('sidebar-nav')
<div class="nav-section-label">Utama</div>
<a href="{{ route('staff.orders') }}" class="nav-item">
  <span class="ni">📋</span> Daftar Pesanan
  <div class="badge">{{ $pendingCount ?? 0 }}</div>
</a>
<a href="{{ route('staff.dashboard') }}" class="nav-item active">
  <span class="ni">📊</span> Ringkasan Hari Ini
</a>
<div class="nav-section-label">Lainnya</div>
<a href="{{ route('staff.history') }}" class="nav-item">
  <span class="ni">📂</span> Riwayat Pesanan
</a>
@endsection

@section('page-title', 'Dashboard')
@section('page-sub', 'Ringkasan aktivitas hari ini')

@section('content')
<div class="stats-row">
    <div class="stat-card">
        <div class="stat-icon">📋</div>
        <div class="stat-label">Total Pesanan Hari Ini</div>
        <div class="stat-value">{{ $todayOrders ?? 0 }}</div>
    </div>
    <div class="stat-card">
        <div class="stat-icon">⏳</div>
        <div class="stat-label">Menunggu Konfirmasi</div>
        <div class="stat-value">{{ $pendingCount ?? 0 }}</div>
    </div>
    <div class="stat-card">
        <div class="stat-icon">🍳</div>
        <div class="stat-label">Sedang Diproses</div>
        <div class="stat-value">{{ $processCount ?? 0 }}</div>
    </div>
    <div class="stat-card">
        <div class="stat-icon">✅</div>
        <div class="stat-label">Selesai Hari Ini</div>
        <div class="stat-value">{{ $doneCount ?? 0 }}</div>
    </div>
</div>

<div class="card">
    <div class="card-title">
        <span>🕐</span> Pesanan Terbaru
        <a href="{{ route('staff.orders') }}" class="view-link">Lihat semua →</a>
    </div>
    <table class="data-table">
        <thead>
            <tr>
                <th>No. Pesanan</th>
                <th>Pelanggan</th>
                <th>Total</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @php
                $recentOrders = \App\Models\Order::orderBy('created_at', 'desc')->limit(5)->get();
            @endphp
            @forelse($recentOrders as $order)
            <tr>
                <td>{{ $order->order_number }}</td>
                <td>{{ $order->customer_name }}</td>
                <td>Rp {{ number_format($order->total, 0, ',', '.') }}</td>
                <td>
                    @if($order->status == 'pending')
                        <span class="status-badge status-pending">⏳ Menunggu</span>
                    @elseif($order->status == 'process')
                        <span class="status-badge status-process">🍳 Diproses</span>
                    @elseif($order->status == 'ready')
                        <span class="status-badge status-ready">✅ Siap</span>
                    @elseif($order->status == 'done')
                        <span class="status-badge status-done">✔ Selesai</span>
                    @elseif($order->status == 'cancelled')
                        <span class="status-badge status-cancelled">❌ Dibatalkan</span>
                    @else
                        <span class="status-badge status-pending">⏳ Menunggu</span>
                    @endif
                </td>
                <td>
                    <a href="{{ route('staff.orders.show', $order->id) }}" class="btn-detail">Detail</a>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" style="text-align: center; padding: 40px;">
                    <div class="empty-state">
                        <div class="empty-state-icon">📭</div>
                        <div>Belum ada pesanan</div>
                    </div>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection

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
    font-size: 28px;
    font-weight: 700;
    color: #1c1c1e;
}

.card {
    background: white;
    border-radius: 16px;
    padding: 20px;
    box-shadow: 0 1px 3px rgba(0,0,0,0.08);
}

.card-title {
    font-size: 16px;
    font-weight: 600;
    margin-bottom: 16px;
    padding-bottom: 12px;
    border-bottom: 1px solid #e5e5ea;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.view-link {
    font-size: 12px;
    color: #c23b22;
    text-decoration: none;
}

.data-table {
    width: 100%;
    border-collapse: collapse;
}

.data-table th {
    text-align: left;
    padding: 12px;
    font-size: 12px;
    font-weight: 600;
    color: #6c6c70;
    border-bottom: 1px solid #e5e5ea;
}

.data-table td {
    padding: 12px;
    font-size: 13px;
    border-bottom: 1px solid #f0f0f0;
}

.status-badge {
    display: inline-flex;
    align-items: center;
    padding: 4px 12px;
    border-radius: 30px;
    font-size: 11px;
    font-weight: 600;
}

.status-pending {
    background: #fff3e0;
    color: #f57c00;
}

.status-process {
    background: #e3f2fd;
    color: #1976d2;
}

.status-ready {
    background: #e8f5e9;
    color: #4caf50;
}

.status-done {
    background: #e8f5e9;
    color: #2e7d32;
}

.status-cancelled {
    background: #ffebee;
    color: #d32f2f;
}

.btn-detail {
    background: none;
    border: 1px solid #e5e5ea;
    padding: 6px 16px;
    border-radius: 20px;
    font-size: 11px;
    text-decoration: none;
    color: #6c6c70;
    display: inline-block;
}

.btn-detail:hover {
    background: #c23b22;
    border-color: #c23b22;
    color: white;
}

.empty-state {
    text-align: center;
    padding: 20px;
}

.empty-state-icon {
    font-size: 48px;
    opacity: 0.5;
    margin-bottom: 10px;
}

@media (max-width: 768px) {
    .stats-row {
        grid-template-columns: repeat(2, 1fr);
        gap: 12px;
    }
    
    .stat-value {
        font-size: 20px;
    }
}
</style>
@endpush