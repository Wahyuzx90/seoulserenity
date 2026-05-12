@extends('layouts.app')
@section('title', 'Pesanan Saya — Seoul Serenity')

@push('styles')
<style>
.orders-container {
    max-width: 900px;
    margin: 0 auto;
    padding: 40px 20px;
}
.orders-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 30px;
    flex-wrap: wrap;
    gap: 15px;
}
.orders-header h2 {
    font-family: var(--font-display);
}
.order-card {
    background: var(--parchment);
    border-radius: 20px;
    margin-bottom: 20px;
    overflow: hidden;
    transition: transform 0.3s;
}
.order-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 20px rgba(0,0,0,0.1);
}
.order-header {
    padding: 20px;
    background: rgba(0,0,0,0.02);
    border-bottom: 1px solid rgba(0,0,0,0.05);
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 10px;
}
.order-header-left {
    flex: 1;
}
.order-number {
    font-weight: 700;
    font-size: 16px;
    color: var(--gochujang);
}
.order-date {
    font-size: 12px;
    color: var(--ash);
    margin-top: 4px;
}
.order-body {
    padding: 20px;
}
.order-items {
    margin-bottom: 20px;
}
.order-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 12px 0;
    border-bottom: 1px solid rgba(0,0,0,0.05);
}
.order-item-left {
    display: flex;
    gap: 12px;
    align-items: center;
    flex: 1;
}
.order-item-img {
    width: 60px;
    height: 60px;
    background: #f0f0f0;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    overflow: hidden;
    flex-shrink: 0;
}
.order-item-img img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}
.order-item-img .emoji-fallback {
    font-size: 32px;
    display: flex;
    align-items: center;
    justify-content: center;
    width: 100%;
    height: 100%;
}
.order-item-details {
    flex: 1;
}
.order-item-name {
    font-weight: 600;
    margin-bottom: 4px;
}
.order-item-qty {
    font-size: 12px;
    color: var(--ash);
}
.order-item-price {
    font-weight: 600;
    color: var(--gochujang);
    white-space: nowrap;
    margin-left: 10px;
}
.order-summary {
    background: rgba(0,0,0,0.02);
    padding: 16px;
    border-radius: 12px;
    margin-top: 16px;
}
.summary-row {
    display: flex;
    justify-content: space-between;
    margin-bottom: 8px;
    font-size: 14px;
}
.summary-row.total {
    font-size: 16px;
    font-weight: 700;
    margin-top: 8px;
    padding-top: 8px;
    border-top: 1px solid rgba(0,0,0,0.1);
    color: var(--gochujang);
}
.order-footer {
    padding: 20px;
    background: rgba(0,0,0,0.02);
    border-top: 1px solid rgba(0,0,0,0.05);
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 15px;
}
.payment-info {
    font-size: 12px;
    color: var(--ash);
}
.payment-info span {
    display: inline-block;
    margin-right: 12px;
}
.status-badge {
    display: inline-block;
    padding: 4px 14px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
}
.status-pending { background: #ff980020; color: #ff9800; }
.status-process { background: #2196f320; color: #2196f3; }
.status-ready { background: #4caf5020; color: #4caf50; }
.status-done { background: #00c85320; color: #00c853; }
.status-cancelled { background: #f4433620; color: #f44336; }

.btn-order-again {
    background: var(--gochujang);
    color: white;
    border: none;
    padding: 8px 20px;
    border-radius: 30px;
    font-size: 13px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.2s;
}
.btn-order-again:hover {
    background: #a32e18;
    transform: translateY(-2px);
}
.btn-pay {
    background: #4caf50;
    color: white;
    border: none;
    padding: 8px 20px;
    border-radius: 30px;
    font-size: 13px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.2s;
}
.btn-pay:hover {
    background: #43a047;
    transform: translateY(-2px);
}
.empty-state {
    text-align: center;
    padding: 60px;
    background: var(--parchment);
    border-radius: 20px;
}
.empty-state-icon {
    font-size: 64px;
    margin-bottom: 20px;
}
.empty-state h3 {
    margin-bottom: 10px;
}
.empty-state p {
    color: var(--ash);
    margin-bottom: 20px;
}
@media (max-width: 768px) {
    .orders-container {
        padding: 30px 16px;
    }
    .order-header {
        flex-direction: column;
        align-items: flex-start;
    }
    .order-footer {
        flex-direction: column;
        align-items: stretch;
    }
    .btn-order-again, .btn-pay {
        text-align: center;
    }
}
@media (max-width: 480px) {
    .order-item {
        flex-direction: column;
        align-items: flex-start;
        gap: 10px;
    }
    .order-item-left {
        width: 100%;
    }
    .order-item-price {
        align-self: flex-end;
    }
    .empty-state {
        padding: 40px 20px;
    }
}
</style>
@endpush

@section('content')
<div class="orders-container">
    <div class="orders-header">
        <h2>📋 Pesanan Saya</h2>
        <a href="{{ route('customer.menu') }}" class="btn-red" style="padding: 10px 20px;">+ Pesan Lagi</a>
    </div>

    @if(isset($orders) && $orders->count() > 0)
        @foreach($orders as $order)
        <div class="order-card">
            <div class="order-header">
                <div class="order-header-left">
                    <div class="order-number">{{ $order->order_number }}</div>
                    <div class="order-date">{{ $order->created_at->format('d F Y H:i') }}</div>
                </div>
                <div>
                    <span class="status-badge status-{{ $order->status }}">
                        @if($order->status == 'pending') 💳 Menunggu Pembayaran
                        @elseif($order->status == 'process') 🍳 Diproses
                        @elseif($order->status == 'ready') ✅ Siap
                        @elseif($order->status == 'done') ✔ Selesai
                        @elseif($order->status == 'cancelled') ❌ Dibatalkan
                        @else ⏳ Menunggu
                        @endif
                    </span>
                </div>
            </div>

            <div class="order-body">
                <div class="order-items">
                    @foreach($order->items as $item)
                    @php
                        // Cek apakah gambar ada
                        $hasImage = false;
                        $imageUrl = null;
                        if ($item->menu_image && !empty($item->menu_image)) {
                            $fullPath = public_path('images/' . $item->menu_image);
                            if (file_exists($fullPath)) {
                                $hasImage = true;
                                $imageUrl = asset('images/' . $item->menu_image);
                            }
                        }
                    @endphp
                    <div class="order-item">
                        <div class="order-item-left">
                            <div class="order-item-img">
                                @if($hasImage && $imageUrl)
                                    <img src="{{ $imageUrl }}" 
                                         alt="{{ $item->menu_name }}"
                                         onerror="this.style.display='none'; this.parentElement.querySelector('.emoji-fallback').style.display='flex';">
                                    <div class="emoji-fallback" style="display: none;">
                                        {{ $item->menu_emoji ?? '🍽️' }}
                                    </div>
                                @else
                                    <div class="emoji-fallback">
                                        {{ $item->menu_emoji ?? '🍽️' }}
                                    </div>
                                @endif
                            </div>
                            <div class="order-item-details">
                                <div class="order-item-name">{{ $item->menu_name }}</div>
                                <div class="order-item-qty">{{ $item->quantity }} x Rp {{ number_format($item->price, 0, ',', '.') }}</div>
                            </div>
                        </div>
                        <div class="order-item-price">Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}</div>
                    </div>
                    @endforeach
                </div>

                <div class="order-summary">
                    <div class="summary-row">
                        <span>Subtotal</span>
                        <span>Rp {{ number_format($order->subtotal, 0, ',', '.') }}</span>
                    </div>
                    <div class="summary-row">
                        <span>Pajak (10%)</span>
                        <span>Rp {{ number_format($order->tax, 0, ',', '.') }}</span>
                    </div>
                    <div class="summary-row">
                        <span>Biaya Pengiriman</span>
                        <span>Rp {{ number_format($order->delivery_fee, 0, ',', '.') }}</span>
                    </div>
                    <div class="summary-row total">
                        <span>Total</span>
                        <span>Rp {{ number_format($order->total, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>

            <div class="order-footer">
                <div class="payment-info">
                    <span>💳 {{ ucfirst(str_replace('_', ' ', $order->payment_method)) }}</span>
                    <span>📍 {{ $order->delivery_type == 'delivery' ? 'Pengiriman' : 'Ambil di Tempat' }}</span>
                    @if($order->notes)
                    <span>📝 {{ \Illuminate\Support\Str::limit($order->notes, 50) }}</span>
                    @endif
                </div>
                
                @if($order->status == 'pending')
                    <button class="btn-pay" onclick="payNow('{{ $order->order_number }}')">💳 Bayar Sekarang</button>
                @elseif($order->status == 'done')
                    <button class="btn-order-again" onclick="orderAgain('{{ $order->order_number }}')">🔄 Pesan Lagi</button>
                @elseif($order->status == 'cancelled')
                    <button class="btn-order-again" onclick="orderAgain('{{ $order->order_number }}')">🔄 Pesan Lagi</button>
                @endif
            </div>
        </div>
        @endforeach
    @else
        <div class="empty-state">
            <div class="empty-state-icon">📭</div>
            <h3>Belum ada pesanan</h3>
            <p>Mulai pesan menu favorit Anda sekarang!</p>
            <a href="{{ route('customer.menu') }}" class="btn-red" style="display: inline-block;">Lihat Menu</a>
        </div>
    @endif
</div>

@push('scripts')
<script>
function payNow(orderNumber) {
    // Fetch order details and redirect to checkout
    fetch('/order/details/' + orderNumber)
        .then(response => response.json())
        .then(data => {
            if (data.success && data.cart) {
                sessionStorage.setItem('checkout_cart', JSON.stringify(data.cart));
                window.location.href = '{{ route("customer.checkout") }}';
            } else {
                alert('Gagal memuat pesanan');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan');
        });
}

function orderAgain(orderNumber) {
    fetch('/order/again/' + orderNumber, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            localStorage.setItem('seoul_cart', JSON.stringify(data.cart));
            window.location.href = '{{ route("customer.menu") }}';
        } else {
            alert('Gagal memesan ulang');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Terjadi kesalahan');
    });
}
</script>
@endpush
@endsection