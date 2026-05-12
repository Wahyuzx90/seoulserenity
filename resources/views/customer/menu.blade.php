@extends('layouts.app')
@section('title', 'Menu — Seoul Serenity')

@push('styles')
<style>
.customer-layout { display: flex; flex-direction: column; min-height: 100vh; }
.customer-body { display: flex; flex: 1; }
.menu-area { flex: 1; padding: 28px 32px; background: var(--parchment); overflow-y: auto; }

.category-filter {
    display: flex;
    gap: 12px;
    margin-bottom: 32px;
    flex-wrap: wrap;
    background: white;
    padding: 12px 20px;
    border-radius: 50px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.05);
}
.filter-btn {
    padding: 8px 24px;
    border-radius: 40px;
    text-decoration: none;
    font-weight: 600;
    font-size: 14px;
    transition: 0.2s;
    background: #f5f5f5;
    color: var(--ink);
}
.filter-btn.active { background: var(--gochujang); color: white; }

.menu-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
    gap: 24px;
    margin-top: 20px;
}
.menu-card {
    background: white;
    border-radius: 16px;
    overflow: hidden;
    transition: all 0.3s;
    box-shadow: 0 2px 8px rgba(0,0,0,0.05);
}
.menu-card:hover { transform: translateY(-4px); box-shadow: 0 8px 24px rgba(0,0,0,0.1); }
.menu-card-img {
    height: 180px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 64px;
    background-size: cover;
    background-position: center;
    background-repeat: no-repeat;
}
.bg-sup { background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); }
.bg-bbq { background: linear-gradient(135deg, #ff6b6b 0%, #ee5a24 100%); }
.bg-nasi { background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); }
.bg-mie { background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%); }
.bg-minuman { background: linear-gradient(135deg, #a8edea 0%, #fed6e3 100%); }
.bg-snack { background: linear-gradient(135deg, #fa709a 0%, #fee140 100%); }
.menu-card-body { padding: 16px; }
.mc-cat { font-size: 11px; text-transform: uppercase; letter-spacing: 1px; color: var(--ash); margin-bottom: 8px; }
.mc-name { font-size: 18px; font-weight: 700; margin-bottom: 4px; }
.mc-ko { font-size: 12px; color: var(--ash); margin-bottom: 8px; }
.mc-desc { font-size: 12px; color: #666; line-height: 1.4; margin-bottom: 12px; }
.mc-footer { display: flex; justify-content: space-between; align-items: center; margin-top: 12px; }
.mc-price { font-size: 20px; font-weight: 700; color: var(--gochujang); }
.mc-add-btn {
    width: 36px;
    height: 36px;
    border-radius: 50%;
    background: var(--gochujang);
    border: none;
    color: white;
    font-size: 20px;
    cursor: pointer;
}
.mc-add-btn:hover { transform: scale(1.1); }

.cart-panel {
    width: 380px;
    background: white;
    border-left: 1px solid rgba(0,0,0,0.05);
    display: flex;
    flex-direction: column;
    position: sticky;
    top: 0;
    height: 100vh;
    overflow-y: auto;
}
.cart-head {
    padding: 24px;
    border-bottom: 1px solid rgba(0,0,0,0.05);
    display: flex;
    justify-content: space-between;
    align-items: center;
}
.cart-title { font-size: 20px; font-weight: 700; font-family: var(--font-display); }
.cart-count {
    background: var(--gochujang);
    color: white;
    width: 28px;
    height: 28px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
}
.cart-items { flex: 1; padding: 20px; overflow-y: auto; }
.ci-row {
    display: flex;
    gap: 12px;
    margin-bottom: 20px;
    padding-bottom: 20px;
    border-bottom: 1px solid rgba(0,0,0,0.05);
    align-items: center;
}
.ci-thumb {
    width: 60px;
    height: 60px;
    background: var(--parchment);
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    overflow: hidden;
}
.ci-thumb img { width: 100%; height: 100%; object-fit: cover; }
.ci-info { flex: 1; }
.ci-name { font-weight: 600; margin-bottom: 4px; }
.ci-price { font-size: 14px; color: var(--gochujang); font-weight: 600; }
.ci-qty-control { display: flex; align-items: center; gap: 8px; margin-top: 8px; }
.qty-btn {
    width: 28px;
    height: 28px;
    border-radius: 6px;
    border: 1px solid #ddd;
    background: white;
    cursor: pointer;
}
.qty-btn:hover { background: var(--gochujang); color: white; }
.cart-footer {
    padding: 20px;
    border-top: 1px solid rgba(0,0,0,0.1);
    background: white;
    position: sticky;
    bottom: 0;
}
.cart-total-row { display: flex; justify-content: space-between; margin-bottom: 12px; font-size: 14px; }
.cart-total-final { display: flex; justify-content: space-between; font-size: 18px; font-weight: 700; margin: 15px 0 20px; }
.cart-total-amount { color: var(--gochujang); font-size: 22px; }
.cart-checkout-btn {
    width: 100%;
    background: var(--gochujang);
    color: white;
    border: none;
    padding: 14px;
    border-radius: 12px;
    font-size: 16px;
    font-weight: 600;
    cursor: pointer;
}
#toast {
    position: fixed;
    bottom: 24px;
    left: 50%;
    transform: translateX(-50%);
    background: var(--ink);
    color: white;
    padding: 12px 24px;
    border-radius: 8px;
    font-size: 14px;
    opacity: 0;
    transition: opacity 0.3s;
    z-index: 999;
}

@media (max-width: 768px) {
    .customer-body { flex-direction: column; }
    .cart-panel {
        width: 100%;
        height: auto;
        position: fixed;
        bottom: 0;
        max-height: 50vh;
        z-index: 100;
    }
    .menu-area { margin-bottom: 50vh; }
    .menu-grid { grid-template-columns: 1fr; }
}
</style>
@endpush

@section('content')
<div class="customer-layout">
    <div class="top-menu-bar">
        <a href="{{ route('customer.menu') }}" class="tmb-logo">🍲 Seoul<em>Serenity</em></a>
        <div class="tmb-nav">
            
            <a href="{{ route('customer.menu') }}" class="tmb-nav-item active">Menu</a>
            <a href="{{ route('customer.orders') }}" class="tmb-nav-item">Pesanan Saya</a>
        </div>
        <div class="tmb-right">
            <a href="{{ route('customer.profile') }}" class="tmb-icon">👤</a>
        </div>
    </div>

    <div class="customer-hero">
        <div>
            <div class="ch-greeting">안녕하세요 — Selamat Datang, {{ auth()->user()->name ?? 'Pelanggan' }} 👋</div>
            <div class="ch-heading">Mau makan <span>apa</span><br>hari ini?</div>
            <div class="ch-stats">
                <div><div class="ch-stat-val">{{ isset($menus) ? $menus->count() : 0 }}</div><div class="ch-stat-lbl">Menu tersedia</div></div>
                <div><div class="ch-stat-val">15–30 mnt</div><div class="ch-stat-lbl">Estimasi siap</div></div>
                <div><div class="ch-stat-val">Rp 0</div><div class="ch-stat-lbl">Minimum order</div></div>
            </div>
        </div>
        <div class="ch-food">🍲</div>
    </div>

    <div class="customer-body">
        <div class="menu-area">
            <div class="category-filter">
                <a href="{{ route('customer.menu') }}" class="filter-btn {{ !request('kategori') ? 'active' : '' }}">🍽️ Semua</a>
                <a href="{{ route('customer.menu', ['kategori'=>'Sup & Jjigae']) }}" class="filter-btn {{ request('kategori')=='Sup & Jjigae' ? 'active' : '' }}">🍲 Sup & Jjigae</a>
                <a href="{{ route('customer.menu', ['kategori'=>'BBQ & Grill']) }}" class="filter-btn {{ request('kategori')=='BBQ & Grill' ? 'active' : '' }}">🥩 BBQ & Grill</a>
                <a href="{{ route('customer.menu', ['kategori'=>'Nasi & Bento']) }}" class="filter-btn {{ request('kategori')=='Nasi & Bento' ? 'active' : '' }}">🍚 Nasi & Bento</a>
                <a href="{{ route('customer.menu', ['kategori'=>'Mie & Ramyeon']) }}" class="filter-btn {{ request('kategori')=='Mie & Ramyeon' ? 'active' : '' }}">🍜 Mie & Ramyeon</a>
                <a href="{{ route('customer.menu', ['kategori'=>'Minuman']) }}" class="filter-btn {{ request('kategori')=='Minuman' ? 'active' : '' }}">🥤 Minuman</a>
                <a href="{{ route('customer.menu', ['kategori'=>'Snack']) }}" class="filter-btn {{ request('kategori')=='Snack' ? 'active' : '' }}">🍢 Snack</a>
            </div>

            <div class="section-head">🍕 Semua Menu</div>

            @if(isset($menus) && $menus->count() > 0)
                <div class="menu-grid">
                    @foreach($menus as $menu)
                    @php
                        $imageUrl = $menu->image_url;
                        $hasImage = $imageUrl !== null;
                    @endphp
                    <div class="menu-card" data-name="{{ strtolower($menu->name) }}">
                        <div class="menu-card-img {{ !$hasImage ? ($menu->bg_class ?? 'bg-sup') : '' }}"
                             @if($hasImage) style="background-image: url('{{ $imageUrl }}');" @endif>
                            @if(!$hasImage)
                                {{ $menu->emoji ?? '🍽️' }}
                            @endif
                        </div>
                        <div class="menu-card-body">
                            <div class="mc-cat">{{ $menu->category }}</div>
                            <div class="mc-name">{{ $menu->name }}</div>
                            <div class="mc-ko">{{ $menu->name_ko }}</div>
                            <div class="mc-desc">{{ Str::limit($menu->description ?? '', 60) }}</div>
                            <div class="mc-footer">
                                <div class="mc-price">Rp {{ number_format($menu->price, 0, ',', '.') }}</div>
                                <button class="mc-add-btn" onclick="addToCart({
                                    id: {{ $menu->id }},
                                    name: '{{ addslashes($menu->name) }}',
                                    price: {{ $menu->price }},
                                    emoji: '{{ $menu->emoji }}',
                                    image: '{{ $menu->image ?? '' }}'
                                })">+</button>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            @else
                <div style="text-align:center;padding:60px;background:white;border-radius:20px;">
                    <div style="font-size:64px;">🍽️</div>
                    <h3>Menu tidak ditemukan</h3>
                </div>
            @endif
        </div>

        <div class="cart-panel">
            <div class="cart-head">
                <div class="cart-title">🛒 Keranjang</div>
                <div class="cart-count" id="cart-count">0</div>
            </div>
            <div class="cart-items" id="cart-items">
                <div style="text-align:center;padding:40px 20px;"><div style="font-size:48px;">🛒</div><div>Keranjang kosong</div></div>
            </div>
            <div class="cart-footer" id="cart-footer" style="display:none;">
                <div class="cart-total-row"><span>Subtotal</span><span id="cart-subtotal">Rp 0</span></div>
                <div class="cart-total-row"><span>Pajak (10%)</span><span id="cart-tax">Rp 0</span></div>
                <div class="cart-total-row"><span>Ongkir</span><span>Rp 10.000</span></div>
                <div class="cart-total-final"><span>Total</span><span class="cart-total-amount" id="cart-total">Rp 0</span></div>
                <button class="cart-checkout-btn" onclick="goToCheckout()">🎉 Checkout</button>
            </div>
        </div>
    </div>
</div>
<div id="toast"></div>

@push('scripts')
<script>
// ========== CART STATE ==========
let cart = {};

// ========== LOAD & SAVE ==========
function loadCart() {
    const saved = localStorage.getItem('seoul_cart');
    if (saved) {
        cart = JSON.parse(saved);
        console.log('Cart loaded:', cart);
    }
    updateCart();
}

function saveCart() {
    localStorage.setItem('seoul_cart', JSON.stringify(cart));
    updateCart();
}

// ========== ADD TO CART ==========
function addToCart(item) {
    console.log('Adding to cart:', item);
    
    if (cart[item.id]) {
        cart[item.id].qty++;
    } else {
        cart[item.id] = { 
            id: item.id,
            name: item.name,
            price: item.price,
            emoji: item.emoji,
            image: item.image || null,
            qty: 1 
        };
    }
    saveCart();
    showToast(`${item.name} ditambahkan ke keranjang! 🎉`);
}

// ========== UPDATE CART UI ==========
function updateCart() {
    const itemsDiv = document.getElementById('cart-items');
    const footer = document.getElementById('cart-footer');
    const countSpan = document.getElementById('cart-count');

    const items = Object.values(cart).filter(i => i.qty > 0);
    const totalQty = items.reduce((s, i) => s + i.qty, 0);
    const subtotal = items.reduce((s, i) => s + (i.price * i.qty), 0);
    const tax = subtotal * 0.1;
    const delivery = 10000;
    const total = subtotal + delivery + tax;

    countSpan.textContent = totalQty;

    if (items.length === 0) {
        itemsDiv.innerHTML = `
            <div style="text-align:center;padding:40px 20px;color:var(--ash);">
                <div style="font-size:48px;">🛒</div>
                <div>Keranjang kosong</div>
                <div style="font-size:12px;margin-top:6px;">Pilih menu untuk memulai pesanan</div>
            </div>`;
        footer.style.display = 'none';
        return;
    }

    let html = '';
    items.forEach(item => {
        const hasImage = item.image && item.image !== '';
        const imageUrl = hasImage ? `/images/${item.image}` : null;
        
        html += `
            <div class="ci-row">
                <div class="ci-thumb">
                    ${hasImage ? `<img src="${imageUrl}" 
                        style="width:100%; height:100%; object-fit:cover; border-radius:12px;"
                        onerror="this.style.display='none'; this.parentElement.querySelector('.emoji-cart').style.display='flex';">` : ''}
                    <div class="emoji-cart" style="display: ${hasImage ? 'none' : 'flex'}; font-size:28px;">
                        ${item.emoji}
                    </div>
                </div>
                <div class="ci-info">
                    <div class="ci-name">${item.name}</div>
                    <div class="ci-price">Rp ${item.price.toLocaleString('id-ID')}</div>
                    <div class="ci-qty-control">
                        <button class="qty-btn" onclick="updateQty(${item.id}, -1)">-</button>
                        <span class="qty-value">${item.qty}</span>
                        <button class="qty-btn" onclick="updateQty(${item.id}, 1)">+</button>
                        <button class="remove-btn" onclick="removeItem(${item.id})">Hapus</button>
                    </div>
                </div>
                <div style="font-weight:600;color:var(--gochujang);">
                    Rp ${(item.price * item.qty).toLocaleString('id-ID')}
                </div>
            </div>
        `;
    });

    itemsDiv.innerHTML = html;
    footer.style.display = 'block';
    document.getElementById('cart-subtotal').innerHTML = `Rp ${subtotal.toLocaleString('id-ID')}`;
    document.getElementById('cart-tax').innerHTML = `Rp ${tax.toLocaleString('id-ID')}`;
    document.getElementById('cart-total').innerHTML = `Rp ${total.toLocaleString('id-ID')}`;
}

// ========== QTY CONTROLS ==========
function updateQty(id, delta) {
    if (cart[id]) {
        cart[id].qty += delta;
        if (cart[id].qty <= 0) {
            delete cart[id];
        }
        saveCart();
    }
}

function removeItem(id) {
    delete cart[id];
    saveCart();
    showToast('Item dihapus dari keranjang');
}

// ========== CHECKOUT ==========
function goToCheckout() {
    const items = Object.values(cart).filter(i => i.qty > 0);
    if (items.length === 0) {
        showToast('Keranjang masih kosong!');
        return;
    }
    sessionStorage.setItem('checkout_cart', JSON.stringify(cart));
    window.location.href = '{{ route("customer.checkout") }}';
}

// ========== TOAST ==========
function showToast(msg) {
    const t = document.getElementById('toast');
    t.textContent = msg;
    t.style.opacity = '1';
    setTimeout(() => {
        t.style.opacity = '0';
    }, 2000);
}

// ========== INIT ==========
loadCart();
</script>
@endpush
@endsection