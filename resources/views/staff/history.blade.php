@extends('layouts.dashboard')
@section('title', 'Staff — Riwayat Pesanan')
@section('role', 'Staff')
@section('sidebar-role', '👔 Karyawan')

@section('sidebar-nav')
<div class="nav-section-label">Utama</div>
<a href="{{ route('staff.orders') }}" class="nav-item">
  <span class="ni">📋</span> Daftar Pesanan
  <div class="badge">0</div>
</a>
<a href="{{ route('staff.dashboard') }}" class="nav-item">
  <span class="ni">📊</span> Ringkasan Hari Ini
</a>
<div class="nav-section-label">Lainnya</div>
<a href="{{ route('staff.history') }}" class="nav-item active">
  <span class="ni">📂</span> Riwayat Pesanan
</a>
@endsection

@section('page-title', 'Riwayat Pesanan')
@section('page-sub', 'Semua pesanan yang telah selesai')

@section('content')
<div class="stat-row">
    <div class="stat-card"><div class="stat-icon">📊</div><div class="stat-label">Total Pesanan</div><div class="stat-value">{{ $totalOrders ?? 0 }}</div></div>
    <div class="stat-card"><div class="stat-icon">💰</div><div class="stat-label">Total Pendapatan</div><div class="stat-value">Rp {{ number_format($totalRevenue ?? 0, 0, ',', '.') }}</div></div>
    <div class="stat-card"><div class="stat-icon">⭐</div><div class="stat-label">Rating</div><div class="stat-value">4.8 / 5</div></div>
    <div class="stat-card"><div class="stat-icon">✅</div><div class="stat-label">Pesanan Selesai</div><div class="stat-value">{{ $totalOrders ?? 0 }}</div></div>
</div>

<div class="card">
    <div class="section-head"><button class="sh-action" onclick="window.location.reload()">🔄 Refresh</button></div>
    <div class="table-wrapper">
        <table class="data-table">
            <thead><tr><th>ID PESANAN</th><th>PELANGGAN</th><th>ITEM</th><th>METODE</th><th>TANGGAL</th><th>TOTAL</th><th>STATUS</th><th>AKSI</th></tr></thead>
            <tbody id="ordersTableBody">
                @forelse($orders ?? [] as $order)
                <tr>
                    <td class="order-id">{{ $order->order_number }}</td>
                    <td><div class="customer-name">{{ $order->customer_name }}</div><div class="customer-phone">{{ $order->customer_phone }}</div></td>
                    <td class="items-list">@foreach($order->items as $item){{ $item->menu_name }} ×{{ $item->quantity }}<br>@endforeach</td>
                    <td><span class="chip {{ $order->delivery_type == 'delivery' ? 'deliver' : 'pickup' }}">{{ $order->delivery_type == 'delivery' ? '🚚 Antar' : '🏪 Ambil' }}</span></td>
                    <td>{{ $order->created_at->format('d M Y H:i') }}</td>
                    <td class="price">Rp {{ number_format($order->total, 0, ',', '.') }}</td>
                    <td><span class="chip done">✅ Selesai</span></td>
                    <td><a href="{{ route('staff.orders.show', $order->id) }}" class="btn-detail">Detail</a></td>
                </tr>
                @empty
                <tr><td colspan="8"><div class="empty-state"><div class="empty-state-icon">📭</div><div class="empty-state-title">Belum Ada Riwayat Pesanan</div></div></td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if(isset($orders) && $orders->hasPages())<div class="pagination">{{ $orders->links() }}</div>@endif
</div>
@endsection

@push('styles')
<style>
.stat-row { display: grid; grid-template-columns: repeat(4, 1fr); gap: 20px; margin-bottom: 30px; }
.stat-card { background: white; border-radius: 16px; padding: 20px; box-shadow: 0 1px 3px rgba(0,0,0,0.08); }
.stat-icon { font-size: 28px; margin-bottom: 8px; }
.stat-label { font-size: 12px; color: #6c6c70; margin-bottom: 6px; }
.stat-value { font-size: 24px; font-weight: 700; color: #1c1c1e; }
.filter-section { background: white; border-radius: 16px; padding: 16px 20px; margin-bottom: 24px; display: flex; justify-content: space-between; flex-wrap: wrap; gap: 12px; box-shadow: 0 1px 3px rgba(0,0,0,0.08); }
.filter-group { display: flex; gap: 8px; flex-wrap: wrap; }
.filter-btn { padding: 6px 18px; border-radius: 30px; text-decoration: none; font-size: 12px; font-weight: 500; background: #f2f2f7; color: #6c6c70; }
.filter-btn.active { background: #c23b22; color: white; }
.search-box { display: flex; align-items: center; background: #f2f2f7; border-radius: 30px; padding: 6px 16px; gap: 8px; }
.search-box input { border: none; background: none; padding: 6px 0; outline: none; font-size: 13px; width: 200px; }
.card { background: white; border-radius: 16px; box-shadow: 0 1px 3px rgba(0,0,0,0.08); overflow: hidden; }
.section-head { display: flex; justify-content: flex-end; padding: 16px 20px; border-bottom: 1px solid #e5e5ea; }
.sh-action { font-size: 12px; color: #c23b22; cursor: pointer; background: none; border: none; padding: 6px 12px; border-radius: 20px; }
.table-wrapper { overflow-x: auto; }
.data-table { width: 100%; min-width: 800px; border-collapse: collapse; }
.data-table th { text-align: left; padding: 14px 12px; font-size: 12px; font-weight: 600; color: #6c6c70; background: #f9f9fb; border-bottom: 1px solid #e5e5ea; }
.data-table td { padding: 14px 12px; font-size: 13px; border-bottom: 1px solid #f0f0f0; }
.order-id { font-weight: 700; color: #c23b22; }
.customer-name { font-weight: 600; }
.customer-phone { font-size: 11px; color: #8e8e93; }
.items-list { font-size: 12px; color: #6c6c70; }
.chip { display: inline-flex; align-items: center; gap: 4px; padding: 4px 12px; border-radius: 30px; font-size: 11px; font-weight: 600; }
.chip.deliver { background: #e3f2fd; color: #1976d2; }
.chip.pickup { background: #fff3e0; color: #f57c00; }
.chip.done { background: #e8f5e9; color: #4caf50; }
.price { font-weight: 600; color: #4a6741; }
.btn-detail { background: none; border: 1px solid #e5e5ea; padding: 6px 16px; border-radius: 20px; font-size: 11px; text-decoration: none; color: #6c6c70; display: inline-block; }
.btn-detail:hover { background: #c23b22; border-color: #c23b22; color: white; }
.empty-state { text-align: center; padding: 60px 20px; }
.empty-state-icon { font-size: 64px; margin-bottom: 16px; opacity: 0.5; }
.empty-state-title { font-size: 16px; font-weight: 600; margin-bottom: 8px; }
.pagination { display: flex; justify-content: center; gap: 8px; padding: 20px; border-top: 1px solid #e5e5ea; }
@media (max-width: 1024px) { .stat-row { grid-template-columns: repeat(2, 1fr); } }
@media (max-width: 768px) { .filter-section { flex-direction: column; } .stat-row { grid-template-columns: 1fr; } }
</style>
@endpush

@push('scripts')
<script>
const searchInput = document.getElementById('searchInput');
if (searchInput) {
    searchInput.addEventListener('keyup', function(e) {
        const searchTerm = e.target.value.toLowerCase();
        const rows = document.querySelectorAll('#ordersTableBody tr');
        rows.forEach(row => { row.style.display = row.textContent.toLowerCase().includes(searchTerm) ? '' : 'none'; });
    });
}
</script>
@endpush