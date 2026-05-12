@extends('layouts.dashboard')
@section('title', 'Manajemen Menu')
@section('role', 'Owner')
@section('sidebar-role', '👑 Owner')

@section('sidebar-nav')
<div class="nav-section-label">Laporan</div>
<a href="{{ route('owner.reports') }}" class="nav-item">
    <span class="ni">📊</span> Laporan Penjualan
</a>
<a href="{{ route('owner.orders') }}" class="nav-item">
    <span class="ni">📋</span> Semua Pesanan
</a>
<a href="{{ route('owner.menus') }}" class="nav-item active">
    <span class="ni">🍽️</span> Manajemen Menu
</a>
@endsection

@section('page-title', 'Manajemen Menu')
@section('page-sub', 'Kelola daftar menu Seoul Serenity')

@section('topbar-actions')
<a href="{{ route('owner.menus.create') }}" class="btn-red" style="padding:9px 18px;font-size:13px;">+ Tambah Menu</a>
@endsection

@push('styles')
<style>
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
.menu-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
    gap: 24px;
    margin-top: 20px;
}
.menu-card {
    background: white;
    border-radius: 16px;
    overflow: hidden;
    transition: all 0.3s;
    box-shadow: 0 2px 8px rgba(0,0,0,0.08);
    position: relative;
}
.menu-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 8px 24px rgba(0,0,0,0.12);
}
.featured-badge {
    position: absolute;
    top: 12px;
    left: 12px;
    background: #d4af37;
    color: #1a1a1a;
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 11px;
    font-weight: 600;
    z-index: 10;
}
.menu-card-img {
    height: 200px;
    background-size: cover;
    background-position: center;
    background-repeat: no-repeat;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 64px;
}
.bg-sup { background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); }
.bg-bbq { background: linear-gradient(135deg, #ff6b6b 0%, #ee5a24 100%); }
.bg-nasi { background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); }
.bg-mie { background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%); }
.bg-minuman { background: linear-gradient(135deg, #a8edea 0%, #fed6e3 100%); }
.bg-snack { background: linear-gradient(135deg, #fa709a 0%, #fee140 100%); }
.menu-card-body {
    padding: 16px;
}
.mc-cat {
    font-size: 11px;
    text-transform: uppercase;
    letter-spacing: 1px;
    color: #6c6c70;
    margin-bottom: 8px;
}
.mc-name {
    font-size: 18px;
    font-weight: 700;
    margin-bottom: 4px;
}
.mc-ko {
    font-size: 12px;
    color: #8e8e93;
    margin-bottom: 8px;
}
.mc-footer {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-top: 12px;
}
.mc-price {
    font-size: 18px;
    font-weight: 700;
    color: #c23b22;
}
.status-badge {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    padding: 4px 10px;
    border-radius: 20px;
    font-size: 10px;
    font-weight: 600;
}
.status-available {
    background: #e8f5e9;
    color: #4caf50;
}
.status-unavailable {
    background: #ffebee;
    color: #f44336;
}
.menu-actions {
    display: flex;
    gap: 8px;
    margin-top: 16px;
    padding-top: 12px;
    border-top: 1px solid #f0f0f0;
}
.btn-edit {
    flex: 1;
    background: transparent;
    border: 1px solid #c23b22;
    padding: 8px 12px;
    border-radius: 30px;
    font-size: 12px;
    font-weight: 600;
    cursor: pointer;
    text-decoration: none;
    text-align: center;
    color: #c23b22;
    display: inline-block;
}
.btn-edit:hover {
    background: #c23b22;
    color: white;
}
.btn-delete {
    background: transparent;
    border: 1px solid #f44336;
    padding: 8px 12px;
    border-radius: 30px;
    font-size: 12px;
    font-weight: 600;
    cursor: pointer;
    color: #f44336;
}
.btn-delete:hover {
    background: #f44336;
    color: white;
}
.empty-state {
    text-align: center;
    padding: 60px;
    background: white;
    border-radius: 20px;
    grid-column: 1 / -1;
}
.empty-state-icon {
    font-size: 64px;
    margin-bottom: 16px;
    opacity: 0.5;
}
@media (max-width: 768px) {
    .filter-section {
        flex-direction: column;
        align-items: stretch;
    }
    .menu-grid {
        grid-template-columns: 1fr;
        gap: 16px;
    }
}
</style>
@endpush

@section('content')
<div class="filter-section">
    <div class="filter-group">
        <a href="{{ route('owner.menus') }}" class="filter-btn {{ !request('category') ? 'active' : '' }}">🍽️ Semua</a>
        <a href="{{ route('owner.menus', ['category' => 'Sup & Jjigae']) }}" class="filter-btn {{ request('category') == 'Sup & Jjigae' ? 'active' : '' }}">🍲 Sup & Jjigae</a>
        <a href="{{ route('owner.menus', ['category' => 'BBQ & Grill']) }}" class="filter-btn {{ request('category') == 'BBQ & Grill' ? 'active' : '' }}">🥩 BBQ & Grill</a>
        <a href="{{ route('owner.menus', ['category' => 'Nasi & Bento']) }}" class="filter-btn {{ request('category') == 'Nasi & Bento' ? 'active' : '' }}">🍚 Nasi & Bento</a>
        <a href="{{ route('owner.menus', ['category' => 'Mie & Ramyeon']) }}" class="filter-btn {{ request('category') == 'Mie & Ramyeon' ? 'active' : '' }}">🍜 Mie & Ramyeon</a>
        <a href="{{ route('owner.menus', ['category' => 'Minuman']) }}" class="filter-btn {{ request('category') == 'Minuman' ? 'active' : '' }}">🥤 Minuman</a>
        <a href="{{ route('owner.menus', ['category' => 'Snack']) }}" class="filter-btn {{ request('category') == 'Snack' ? 'active' : '' }}">🍢 Snack</a>
    </div>
    <div class="search-box">
        <span>🔍</span>
        <input type="text" id="searchInput" placeholder="Cari menu...">
    </div>
</div>

<div class="menu-grid" id="menuGrid">
    @forelse($menus ?? [] as $menu)
    @php
        $imageUrl = $menu->image_url;
        $hasImage = $imageUrl !== null;
    @endphp
    <div class="menu-card" data-name="{{ strtolower($menu->name) }}" data-category="{{ $menu->category }}">
        @if($menu->is_featured)
        <div class="featured-badge">⭐ Unggulan</div>
        @endif
        
        <div class="menu-card-img {{ !$hasImage ? ($menu->bg_class ?? 'bg-k1') : '' }}"
             @if($hasImage) style="background-image: url('{{ $imageUrl }}');" @endif>
            @if(!$hasImage)
                {{ $menu->emoji ?? '🍽️' }}
            @endif
        </div>
        
        <div class="menu-card-body">
            <div class="mc-cat">{{ $menu->category }}</div>
            <div class="mc-name">{{ $menu->name }}</div>
            <div class="mc-ko">{{ $menu->name_ko }}</div>
            <div class="mc-footer">
                <div class="mc-price">Rp {{ number_format($menu->price, 0, ',', '.') }}</div>
                <div class="status-badge {{ $menu->is_available ? 'status-available' : 'status-unavailable' }}">
                    {{ $menu->is_available ? '✅ Tersedia' : '❌ Tidak Tersedia' }}
                </div>
            </div>
            <div class="menu-actions">
                <a href="{{ route('owner.menus.edit', $menu) }}" class="btn-edit">✏️ Edit</a>
                <form method="POST" action="{{ route('owner.menus.destroy', $menu) }}" style="display: inline-block; flex: 1;">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn-delete" onclick="return confirm('Hapus menu {{ $menu->name }}?')">
                        🗑️ Hapus
                    </button>
                </form>
            </div>
        </div>
    </div>
    @empty
    <div class="empty-state">
        <div class="empty-state-icon">🍽️</div>
        <h3>Belum ada menu</h3>
        <p>Silakan tambah menu baru</p>
        <a href="{{ route('owner.menus.create') }}" class="btn-red" style="display: inline-block; margin-top: 16px;">+ Tambah Menu</a>
    </div>
    @endforelse
</div>
@endsection

@push('scripts')
<script>
const searchInput = document.getElementById('searchInput');
if (searchInput) {
    searchInput.addEventListener('keyup', function() {
        const term = this.value.toLowerCase();
        const cards = document.querySelectorAll('.menu-card');
        cards.forEach(card => {
            const name = card.getAttribute('data-name');
            card.style.display = name && name.includes(term) ? '' : 'none';
        });
    });
}
</script>
@endpush