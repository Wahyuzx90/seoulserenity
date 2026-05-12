@extends('layouts.dashboard')
@section('title', 'Staff — Detail Pesanan')
@section('role', 'Staff')
@section('sidebar-role', '👔 Karyawan')

@section('sidebar-nav')
<div class="nav-section-label">Utama</div>
<a href="{{ route('staff.orders') }}" class="nav-item">
  <span class="ni">📋</span> Daftar Pesanan
  <div class="badge">{{ $pendingCount ?? 0 }}</div>
</a>
<a href="{{ route('staff.dashboard') }}" class="nav-item">
  <span class="ni">📊</span> Ringkasan Hari Ini
</a>
<div class="nav-section-label">Lainnya</div>
<a href="{{ route('staff.history') }}" class="nav-item">
  <span class="ni">📂</span> Riwayat Pesanan
</a>
@endsection

@section('page-title', 'Detail Pesanan')
@section('page-sub', 'Informasi lengkap pesanan')

@push('styles')
<style>
.back-button {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    background: none;
    border: none;
    color: var(--gochujang);
    font-size: 14px;
    font-weight: 500;
    cursor: pointer;
    margin-bottom: 20px;
    padding: 8px 0;
    transition: 0.2s;
}

.back-button:hover {
    transform: translateX(-4px);
}

.detail-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 24px;
    margin-bottom: 24px;
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
    color: #1c1c1e;
    margin-bottom: 16px;
    padding-bottom: 12px;
    border-bottom: 1px solid #e5e5ea;
    display: flex;
    align-items: center;
    gap: 8px;
}

.info-row {
    display: flex;
    margin-bottom: 14px;
    padding-bottom: 10px;
    border-bottom: 1px solid #f0f0f0;
}

.info-label {
    width: 120px;
    font-size: 13px;
    font-weight: 600;
    color: #6c6c70;
}

.info-value {
    flex: 1;
    font-size: 13px;
    color: #1c1c1e;
}

.status-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 6px 14px;
    border-radius: 30px;
    font-size: 12px;
    font-weight: 600;
}

.status-pending { background: #fff3e0; color: #f57c00; }
.status-process { background: #e3f2fd; color: #1976d2; }
.status-ready { background: #e8f5e9; color: #4caf50; }
.status-done { background: #e8f5e9; color: #2e7d32; }
.status-cancelled { background: #ffebee; color: #d32f2f; }

.chip.deliver {
    background: #e3f2fd;
    color: #1976d2;
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 11px;
    display: inline-block;
}

.chip.pickup {
    background: #fff3e0;
    color: #f57c00;
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 11px;
    display: inline-block;
}

.items-table {
    width: 100%;
    border-collapse: collapse;
}

.items-table th {
    text-align: left;
    padding: 12px 8px;
    font-size: 12px;
    font-weight: 600;
    color: #6c6c70;
    background: #f9f9fb;
    border-bottom: 1px solid #e5e5ea;
}

.items-table td {
    padding: 12px 8px;
    font-size: 13px;
    border-bottom: 1px solid #f0f0f0;
}

.total-row {
    display: flex;
    justify-content: flex-end;
    margin-top: 16px;
    padding-top: 16px;
    border-top: 2px solid #e5e5ea;
}

.total-label {
    font-size: 16px;
    font-weight: 600;
    color: #1c1c1e;
    margin-right: 16px;
}

.total-amount {
    font-size: 20px;
    font-weight: 700;
    color: #c23b22;
}

.action-buttons {
    display: flex;
    gap: 12px;
    margin-top: 24px;
    flex-wrap: wrap;
    justify-content: center;
}

.btn {
    padding: 10px 24px;
    border-radius: 30px;
    font-size: 13px;
    font-weight: 600;
    cursor: pointer;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    transition: all 0.2s;
    border: none;
}

.btn-primary { background: #1976d2; color: white; }
.btn-primary:hover { background: #1565c0; transform: translateY(-1px); }
.btn-success { background: #4caf50; color: white; }
.btn-success:hover { background: #43a047; transform: translateY(-1px); }
.btn-warning { background: #ff9800; color: white; }
.btn-warning:hover { background: #fb8c00; transform: translateY(-1px); }
.btn-danger { background: #f44336; color: white; }
.btn-danger:hover { background: #d32f2f; transform: translateY(-1px); }
.btn-secondary { background: #6c6c70; color: white; }
.btn-secondary:hover { background: #5c5c60; transform: translateY(-1px); }

.note-box {
    background: #f9f9fb;
    padding: 12px 16px;
    border-radius: 12px;
    margin-top: 12px;
    font-size: 13px;
    color: #6c6c70;
    font-style: italic;
}

.alert-success {
    background: #e8f5e9;
    color: #2e7d32;
    padding: 12px 16px;
    border-radius: 12px;
    margin-bottom: 20px;
    border-left: 4px solid #4caf50;
}

@media (max-width: 768px) {
    .detail-grid { grid-template-columns: 1fr; gap: 16px; }
    .info-row { flex-direction: column; }
    .info-label { width: 100%; margin-bottom: 4px; }
    .action-buttons { justify-content: center; }
    .btn { padding: 8px 16px; font-size: 12px; }
}
</style>
@endpush

@section('content')
<button class="back-button" onclick="window.history.back()">← Kembali</button>

@if(session('success'))
<div class="alert-success">✅ {{ session('success') }}</div>
@endif

<div class="detail-grid">
    <div class="card">
        <div class="card-title"><span>👤</span> Informasi Pelanggan</div>
        <div class="info-row"><div class="info-label">Nama Lengkap</div><div class="info-value">{{ $order->customer_name }}</div></div>
        <div class="info-row"><div class="info-label">Nomor Telepon</div><div class="info-value">{{ $order->customer_phone }}</div></div>
        <div class="info-row"><div class="info-label">Alamat</div><div class="info-value">{{ $order->customer_address }}</div></div>
        <div class="info-row"><div class="info-label">Metode Pesanan</div><div class="info-value"><span class="chip {{ $order->delivery_type == 'delivery' ? 'deliver' : 'pickup' }}">{{ $order->delivery_type == 'delivery' ? '🚚 Delivery (Antar)' : '🏪 Pickup (Ambil)' }}</span></div></div>
    </div>

    <div class="card">
        <div class="card-title"><span>📋</span> Informasi Pesanan</div>
        <div class="info-row"><div class="info-label">No. Pesanan</div><div class="info-value">{{ $order->order_number }}</div></div>
        <div class="info-row"><div class="info-label">Tanggal Pemesanan</div><div class="info-value">{{ $order->created_at->format('d F Y H:i') }}</div></div>
        <div class="info-row"><div class="info-label">Status Pesanan</div>
            <div class="info-value">
                <span class="status-badge status-{{ $order->status }}">
                    @if($order->status == 'pending') ⏳ Menunggu
                    @elseif($order->status == 'process') 🍳 Diproses
                    @elseif($order->status == 'ready') ✅ Siap
                    @elseif($order->status == 'done') ✔ Selesai
                    @elseif($order->status == 'cancelled') ❌ Dibatalkan
                    @endif
                </span>
            </div>
        </div>
        <div class="info-row"><div class="info-label">Metode Pembayaran</div>
            <div class="info-value">
                @if($order->payment_method == 'qris') 📱 QRIS
                @elseif($order->payment_method == 'transfer_bca') 🏦 Transfer BCA
                @elseif($order->payment_method == 'transfer_mandiri') 🏦 Transfer Mandiri
                @elseif($order->payment_method == 'cod') 💵 Cash on Delivery
                @endif
            </div>
        </div>
        @if($order->notes)<div class="note-box"><strong>📝 Catatan:</strong> {{ $order->notes }}</div>@endif
    </div>
</div>

<div class="card" style="margin-bottom: 24px;">
    <div class="card-title"><span>🍽️</span> Daftar Item Pesanan</div>
    <div style="overflow-x: auto;">
        <table class="items-table">
            <thead><tr><th>No</th><th>Menu</th><th>Harga</th><th>Qty</th><th>Subtotal</th></tr></thead>
            <tbody>
                @foreach($order->items as $index => $item)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $item->menu_emoji }} {{ $item->menu_name }}</td>
                    <td>Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                    <td>{{ $item->quantity }}</td>
                    <td>Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="total-row"><div class="total-label">Subtotal</div><div class="total-amount">Rp {{ number_format($order->subtotal, 0, ',', '.') }}</div></div>
    <div class="total-row" style="margin-top: 8px; padding-top: 8px; border-top: none;"><div class="total-label">Pajak (10%)</div><div class="total-amount" style="font-size: 16px;">Rp {{ number_format($order->tax, 0, ',', '.') }}</div></div>
    <div class="total-row" style="margin-top: 8px; padding-top: 8px; border-top: none;"><div class="total-label">Biaya Kirim</div><div class="total-amount" style="font-size: 16px;">Rp {{ number_format($order->delivery_fee, 0, ',', '.') }}</div></div>
    <div class="total-row"><div class="total-label">Total</div><div class="total-amount">Rp {{ number_format($order->total, 0, ',', '.') }}</div></div>
</div>

<div class="action-buttons">
    @if($order->status == 'pending')
    <form method="POST" action="{{ route('staff.orders.update', $order->id) }}" style="display: inline;">
        @csrf
        @method('PATCH')
        <input type="hidden" name="status" value="process">
        <button type="submit" class="btn btn-primary">🍳 Proses Pesanan</button>
    </form>
    <form method="POST" action="{{ route('staff.orders.update', $order->id) }}" style="display: inline;">
        @csrf
        @method('PATCH')
        <input type="hidden" name="status" value="cancelled">
        <button type="submit" class="btn btn-danger" onclick="return confirm('Yakin ingin membatalkan pesanan ini?')">❌ Batalkan Pesanan</button>
    </form>
    @endif
    
    @if($order->status == 'process')
    <form method="POST" action="{{ route('staff.orders.update', $order->id) }}" style="display: inline;">
        @csrf
        @method('PATCH')
        <input type="hidden" name="status" value="ready">
        <button type="submit" class="btn btn-warning">✅ Pesanan Siap</button>
    </form>
    <form method="POST" action="{{ route('staff.orders.update', $order->id) }}" style="display: inline;">
        @csrf
        @method('PATCH')
        <input type="hidden" name="status" value="cancelled">
        <button type="submit" class="btn btn-danger" onclick="return confirm('Yakin ingin membatalkan pesanan ini?')">❌ Batalkan Pesanan</button>
    </form>
    @endif
    
    @if($order->status == 'ready')
    <form method="POST" action="{{ route('staff.orders.update', $order->id) }}" style="display: inline;">
        @csrf
        @method('PATCH')
        <input type="hidden" name="status" value="done">
        <button type="submit" class="btn btn-success">✔ Selesai</button>
    </form>
    @endif
    
    <a href="{{ route('staff.orders') }}" class="btn btn-secondary">← Kembali ke Daftar</a>
</div>
@endsection

@push('scripts')
<script>
// Optional: Auto refresh setiap 30 detik
// setTimeout(() => location.reload(), 30000);
</script>
@endpush