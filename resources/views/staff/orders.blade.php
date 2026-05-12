@extends('layouts.dashboard')
@section('title', 'Staff — Daftar Pesanan')
@section('role', 'Staff')
@section('sidebar-role', '👔 Karyawan')

@section('sidebar-nav')
<div class="nav-section-label">Utama</div>
<a href="{{ route('staff.orders') }}" class="nav-item {{ request()->routeIs('staff.orders') ? 'active' : '' }}">
  <span class="ni">📋</span> Daftar Pesanan
  <div class="badge">{{ $pendingCount ?? 0 }}</div>
</a>
<a href="{{ route('staff.dashboard') }}" class="nav-item {{ request()->routeIs('staff.dashboard') ? 'active' : '' }}">
  <span class="ni">📊</span> Ringkasan Hari Ini
</a>
<div class="nav-section-label">Lainnya</div>
<a href="{{ route('staff.history') }}" class="nav-item {{ request()->routeIs('staff.history') ? 'active' : '' }}">
  <span class="ni">📂</span> Riwayat Pesanan
</a>
@endsection

@section('page-title', 'Daftar Pesanan')
@section('page-sub', 'Hari ini · ' . now()->format('d M Y'))

@section('topbar-actions')
<div class="filter-group">
  <a href="{{ route('staff.orders') }}" class="f-pill {{ !request('status') ? 'on' : 'off' }}">Semua</a>
  <a href="{{ route('staff.orders', ['status'=>'pending']) }}" class="f-pill {{ request('status')=='pending' ? 'on' : 'off' }}">⏳ Menunggu</a>
  <a href="{{ route('staff.orders', ['status'=>'process']) }}" class="f-pill {{ request('status')=='process' ? 'on' : 'off' }}">🍳 Diproses</a>
  <a href="{{ route('staff.orders', ['status'=>'ready']) }}" class="f-pill {{ request('status')=='ready' ? 'on' : 'off' }}">✅ Siap</a>
  <a href="{{ route('staff.orders', ['status'=>'done']) }}" class="f-pill {{ request('status')=='done' ? 'on' : 'off' }}">✔ Selesai</a>
</div>
@endsection

@section('content')
<!-- KPI Row -->
<div class="stat-row">
  <div class="stat-card dark">
    <div class="stat-icon">📋</div>
    <div class="stat-label">Total Pesanan Hari Ini</div>
    <div class="stat-value">{{ $todayOrders ?? 0 }}</div>
    <div class="stat-delta">Pesanan hari ini</div>
  </div>
  <div class="stat-card">
    <div class="stat-icon">⏳</div>
    <div class="stat-label">Menunggu Konfirmasi</div>
    <div class="stat-value">{{ $pendingCount ?? 0 }}</div>
    <div class="stat-delta down">Perlu diproses</div>
  </div>
  <div class="stat-card">
    <div class="stat-icon">🍳</div>
    <div class="stat-label">Sedang Diproses</div>
    <div class="stat-value">{{ $processCount ?? 0 }}</div>
    <div class="stat-delta up">Di dapur</div>
  </div>
  <div class="stat-card">
    <div class="stat-icon">✅</div>
    <div class="stat-label">Selesai Hari Ini</div>
    <div class="stat-value">{{ $doneCount ?? 0 }}</div>
    <div class="stat-delta up">Pesanan selesai</div>
  </div>
</div>

<!-- Orders Table -->
<div class="card">
  <div class="section-head">
    <div class="acl">📋 Pesanan Masuk</div>
    <span class="sh-action" onclick="location.reload()">🔄 Refresh</span>
  </div>
  <table class="data-table">
    <thead>
      <tr>
        <th>ID Pesanan</th>
        <th>Pelanggan</th>
        <th>Item</th>
        <th>Metode</th>
        <th>Waktu Masuk</th>
        <th>Total</th>
        <th>Status</th>
        <th>Aksi</th>
      </tr>
    </thead>
    <tbody>
      @forelse($orders ?? [] as $order)
      <tr>
        <td style="font-weight: 700; color: #c23b22;">{{ $order->order_number }}</td>
        <td>
          <div style="font-weight: 500;">{{ $order->customer_name }}</div>
          <div style="font-size: 11px; color: #8e8e93;">{{ $order->customer_phone }}</div>
        </td>
        <td style="font-size: 12px; color: #6c6c70;">
          @foreach($order->items as $item)
            {{ $item->menu_name }} ×{{ $item->quantity }}<br>
          @endforeach
        </td>
        <td>
          <span class="chip {{ $order->delivery_type == 'delivery' ? 'deliver' : 'pickup' }}">
            {{ $order->delivery_type == 'delivery' ? '🚚 Antar' : '🏪 Ambil' }}
          </span>
        </td>
        <td style="font-size: 12px; color: #6c6c70;">{{ $order->created_at->format('d M Y H:i') }}</td>
        <td class="price">Rp {{ number_format($order->total, 0, ',', '.') }}</td>
        <td>
          <span class="status-badge status-{{ $order->status }}">
            @switch($order->status)
              @case('pending') ⏳ Menunggu @break
              @case('process') 🍳 Diproses @break
              @case('ready') ✅ Siap @break
              @case('done') ✔ Selesai @break
              @case('cancelled') ❌ Dibatalkan @break
              @default ⏳ Menunggu
            @endswitch
          </span>
        </td>
        <td>
          <a href="{{ route('staff.orders.show', $order->id) }}" class="btn-detail">Detail</a>
        </td>
      </tr>
      @empty
      <tr>
        <td colspan="8" style="text-align:center; padding:60px;">
          <div style="font-size:64px; margin-bottom:16px;">📭</div>
          <div style="font-size:16px; font-weight:500; margin-bottom:8px;">Tidak Ada Pesanan Masuk</div>
          <div style="font-size:13px; color:var(--ash);">Belum ada pesanan yang masuk saat ini</div>
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
.stat-row {
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
    font-size: 28px;
    font-weight: 700;
    color: #1c1c1e;
}

.stat-card.dark .stat-value {
    color: white;
}

.stat-delta {
    font-size: 11px;
    margin-top: 6px;
}

.filter-group {
    display: flex;
    gap: 8px;
    flex-wrap: wrap;
}

.f-pill {
    padding: 6px 18px;
    border-radius: 30px;
    text-decoration: none;
    font-size: 12px;
    font-weight: 500;
    background: #f2f2f7;
    color: #6c6c70;
}

.f-pill.on {
    background: #c23b22;
    color: white;
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
    vertical-align: middle;
}

.price {
    font-weight: 600;
    color: #4a6741;
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

.status-pending { background: #fff3e0; color: #f57c00; }
.status-process { background: #e3f2fd; color: #1976d2; }
.status-ready { background: #e8f5e9; color: #4caf50; }
.status-done { background: #e8f5e9; color: #2e7d32; }
.status-cancelled { background: #ffebee; color: #d32f2f; }

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

.btn-detail {
    background: none;
    border: 1px solid #e5e5ea;
    padding: 6px 16px;
    border-radius: 20px;
    font-size: 11px;
    font-weight: 500;
    cursor: pointer;
    text-decoration: none;
    color: #6c6c70;
    display: inline-block;
}

.btn-detail:hover {
    background: #c23b22;
    border-color: #c23b22;
    color: white;
}

.pagination {
    display: flex;
    justify-content: center;
    gap: 8px;
    padding: 20px;
    border-top: 1px solid #e5e5ea;
}

@media (max-width: 1024px) {
    .stat-row {
        grid-template-columns: repeat(2, 1fr);
        gap: 15px;
    }
}

@media (max-width: 768px) {
    .stat-row {
        grid-template-columns: 1fr;
        gap: 12px;
    }
    
    .filter-group {
        justify-content: center;
    }
    
    .data-table {
        font-size: 12px;
    }
}
</style>
@endpush