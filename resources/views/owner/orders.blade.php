@extends('layouts.dashboard')
@section('title', 'Owner — Semua Pesanan')
@section('role', 'Owner')
@section('sidebar-role', '👑 Owner')

@section('sidebar-nav')
<div class="nav-section-label">Laporan</div>
<a href="{{ route('owner.reports') }}" class="nav-item">
  <span class="ni">📊</span> Laporan Penjualan
</a>
<a href="{{ route('owner.orders') }}" class="nav-item active">
  <span class="ni">📋</span> Semua Pesanan
</a>
<a href="{{ route('owner.menus') }}" class="nav-item">
  <span class="ni">🍽️</span> Manajemen Menu
</a>

@endsection

@section('page-title', 'Semua Pesanan')
@section('page-sub', 'Kelola dan pantau semua pesanan')

@section('content')
<div class="stats-row">
    <div class="stat-card">
        <div class="stat-icon">📋</div>
        <div class="stat-label">Total Pesanan</div>
        <div class="stat-value">{{ $totalOrders ?? 0 }}</div>
    </div>
    <div class="stat-card">
        <div class="stat-icon">💰</div>
        <div class="stat-label">Total Pendapatan</div>
        <div class="stat-value">Rp {{ number_format($totalRevenue ?? 0, 0, ',', '.') }}</div>
    </div>
    <div class="stat-card">
        <div class="stat-icon">⏳</div>
        <div class="stat-label">Menunggu</div>
        <div class="stat-value">{{ $pendingCount ?? 0 }}</div>
    </div>
    <div class="stat-card">
        <div class="stat-icon">✅</div>
        <div class="stat-label">Selesai</div>
        <div class="stat-value">{{ $completedCount ?? 0 }}</div>
    </div>
</div>

<div class="filter-section">
    <div class="filter-group">
        <a href="{{ route('owner.orders') }}" class="filter-btn {{ !request('status') ? 'active' : '' }}">Semua</a>
        <a href="{{ route('owner.orders', ['status'=>'pending']) }}" class="filter-btn {{ request('status')=='pending' ? 'active' : '' }}">⏳ Menunggu</a>
        <a href="{{ route('owner.orders', ['status'=>'process']) }}" class="filter-btn {{ request('status')=='process' ? 'active' : '' }}">🍳 Diproses</a>
        <a href="{{ route('owner.orders', ['status'=>'ready']) }}" class="filter-btn {{ request('status')=='ready' ? 'active' : '' }}">✅ Siap</a>
        <a href="{{ route('owner.orders', ['status'=>'done']) }}" class="filter-btn {{ request('status')=='done' ? 'active' : '' }}">✔ Selesai</a>
        <a href="{{ route('owner.orders', ['status'=>'cancelled']) }}" class="filter-btn {{ request('status')=='cancelled' ? 'active' : '' }}">❌ Dibatalkan</a>
    </div>
    <div class="search-box">
        <span>🔍</span>
        <input type="text" id="searchInput" placeholder="Cari pesanan...">
    </div>
</div>

<div class="card">
    <div class="section-head">
        <div class="acl">📋 Daftar Pesanan</div>
        <span class="sh-action" onclick="location.reload()">🔄 Refresh</span>
    </div>
    <table class="data-table">
        <thead>
            <tr>
                <th>ID Pesanan</th>
                <th>Pelanggan</th>
                <th>Item</th>
                <th>Metode</th>
                <th>Tanggal</th>
                <th>Total</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody id="ordersTableBody">
            @forelse($orders ?? [] as $order)
            <tr>
                <td style="font-weight:600;color:#c23b22;">{{ $order->order_number }}</td>
                <td>
                    <div class="customer-name">{{ $order->customer_name }}</div>
                    <div class="customer-phone">{{ $order->customer_phone }}</div>
                </td>
                <td class="items-list">
                    @foreach($order->items as $item)
                        {{ $item->menu_name }} ×{{ $item->quantity }}<br>
                    @endforeach
                </td>
                <td>
                    <span class="chip {{ $order->delivery_type == 'delivery' ? 'deliver' : 'pickup' }}">
                        {{ $order->delivery_type == 'delivery' ? '🚚 Antar' : '🏪 Ambil' }}
                    </span>
                </td>
                <td style="font-size:12px;white-space:nowrap;">{{ $order->created_at->format('d M Y H:i') }}</td>
                <td class="price">Rp {{ number_format($order->total, 0, ',', '.') }}</td>
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
                <td colspan="8" class="empty-state">
                    <div class="empty-state-icon">📭</div>
                    <div>Belum ada pesanan</div>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
    @if(isset($orders) && $orders->hasPages())
    <div class="pagination">
        {{ $orders->links() }}
    </div>
    @endif
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
    font-size: 24px;
    font-weight: 700;
    color: #1c1c1e;
}

.filter-section {
    background: white;
    border-radius: 16px;
    padding: 16px 20px;
    margin-bottom: 24px;
    display: flex;
    justify-content: space-between;
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
    background: #f2f2f7;
    color: #6c6c70;
}

.filter-btn.active {
    background: #c23b22;
    color: white;
}

.search-box {
    display: flex;
    align-items: center;
    background: #f2f2f7;
    border-radius: 30px;
    padding: 6px 16px;
    gap: 8px;
}

.search-box input {
    border: none;
    background: none;
    padding: 6px 0;
    outline: none;
    font-size: 13px;
    width: 200px;
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
}

.sh-action {
    font-size: 12px;
    color: #c23b22;
    cursor: pointer;
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
}

.customer-name {
    font-weight: 500;
}

.customer-phone {
    font-size: 11px;
    color: #8e8e93;
}

.items-list {
    font-size: 12px;
    color: #6c6c70;
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
    padding: 60px;
}

.empty-state-icon {
    font-size: 64px;
    margin-bottom: 16px;
    opacity: 0.5;
}

.pagination {
    display: flex;
    justify-content: center;
    gap: 8px;
    padding: 20px;
    border-top: 1px solid #e5e5ea;
}

.page-btn {
    padding: 6px 12px;
    border-radius: 8px;
    text-decoration: none;
    font-size: 13px;
    color: #6c6c70;
}

.page-btn.active {
    background: #c23b22;
    color: white;
}

@media (max-width: 1024px) {
    .stats-row {
        grid-template-columns: repeat(2, 1fr);
        gap: 15px;
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
    
    .search-box {
        justify-content: center;
    }
    
    .data-table {
        font-size: 12px;
    }
    
    .data-table th, 
    .data-table td {
        padding: 10px 6px;
    }
}
</style>
@endpush

@push('scripts')
<script>
const searchInput = document.getElementById('searchInput');
if (searchInput) {
    searchInput.addEventListener('keyup', function(e) {
        const searchTerm = e.target.value.toLowerCase();
        const rows = document.querySelectorAll('#ordersTableBody tr');
        rows.forEach(row => {
            const text = row.textContent.toLowerCase();
            row.style.display = text.includes(searchTerm) ? '' : 'none';
        });
    });
}
</script>
@endpush