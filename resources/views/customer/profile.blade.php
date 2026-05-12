@extends('layouts.app')
@section('title', 'Profil Saya — Seoul Serenity')

@section('content')
<div class="customer-layout">
    <!-- Top Navigation -->
    <div class="top-menu-bar">
        <a href="{{ route('customer.menu') }}" class="tmb-logo">🍲 Seoul<em>Serenity</em></a>
        <div class="tmb-nav">
            <a href="{{ route('customer.menu') }}" class="tmb-nav-item">Menu</a>
            <a href="{{ route('customer.orders') }}" class="tmb-nav-item">Pesanan Saya</a>
        </div>
        <div class="tmb-right">
            <button class="tmb-icon" onclick="showProfileModal()">👤</button>
        </div>
    </div>

    <!-- Hero Section -->
    <div class="customer-hero">
        <div>
            <div class="ch-greeting">안녕하세요 — Profil Saya 👋</div>
            <div class="ch-heading">Kelola <span>Akun</span><br>Anda</div>
            <div class="ch-stats">
                <div>
                    <div class="ch-stat-val">1</div>
                    <div class="ch-stat-lbl">Akun Aktif</div>
                </div>
                <div style="width:1px;background:rgba(255,255,255,0.1);"></div>
                <div>
                    <div class="ch-stat-val">✓</div>
                    <div class="ch-stat-lbl">Terverifikasi</div>
                </div>
            </div>
        </div>
        <div class="ch-food">👤</div>
    </div>

    <!-- Main Content -->
    <div style="max-width: 800px; margin: 60px auto; padding: 0 20px;">
        <div style="background: white; border-radius: 24px; padding: 40px; box-shadow: var(--shadow);">
            <div style="text-align: center; margin-bottom: 30px;">
                <div class="avatar" style="width: 100px; height: 100px; background: linear-gradient(135deg, var(--gochujang), #a32e18); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 20px;">
                    <span style="font-size: 48px; color: white;" id="avatarText">👤</span>
                </div>
                <h2 style="font-family: var(--font-display);" id="profileName">Profil Saya</h2>
                <p style="color: var(--ash);">Kelola informasi akun Anda</p>
            </div>

            @auth
            <div id="profileForm">
                <div class="info-group" style="margin-bottom: 20px; padding-bottom: 15px; border-bottom: 1px solid #eee;">
                    <label style="font-weight: 600; display: block; margin-bottom: 5px; font-size: 13px; color: var(--ash);">Nama Lengkap</label>
                    <div id="nameDisplay" style="font-size: 16px; font-weight: 600; padding: 8px 0;">{{ auth()->user()->name }}</div>
                    <input type="text" id="nameInput" class="info-input" style="display:none; width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 8px; margin-top: 5px;" value="{{ auth()->user()->name }}">
                    <button class="edit-btn" onclick="toggleEdit('name')" style="background: none; border: none; color: var(--gochujang); cursor: pointer; font-size: 12px; margin-top: 5px;">✏️ Edit</button>
                </div>
                
                <div class="info-group" style="margin-bottom: 20px; padding-bottom: 15px; border-bottom: 1px solid #eee;">
                    <label style="font-weight: 600; display: block; margin-bottom: 5px; font-size: 13px; color: var(--ash);">Email</label>
                    <div id="emailDisplay" style="font-size: 16px; font-weight: 600; padding: 8px 0;">{{ auth()->user()->email }}</div>
                    <input type="email" id="emailInput" class="info-input" style="display:none; width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 8px; margin-top: 5px;" value="{{ auth()->user()->email }}">
                    <button class="edit-btn" onclick="toggleEdit('email')" style="background: none; border: none; color: var(--gochujang); cursor: pointer; font-size: 12px; margin-top: 5px;">✏️ Edit</button>
                </div>
                
                <div class="info-group" style="margin-bottom: 20px; padding-bottom: 15px; border-bottom: 1px solid #eee;">
                    <label style="font-weight: 600; display: block; margin-bottom: 5px; font-size: 13px; color: var(--ash);">No. Telepon</label>
                    <div id="phoneDisplay" style="font-size: 16px; font-weight: 600; padding: 8px 0;">{{ auth()->user()->phone ?? 'Belum diisi' }}</div>
                    <input type="tel" id="phoneInput" class="info-input" style="display:none; width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 8px; margin-top: 5px;" value="{{ auth()->user()->phone ?? '' }}">
                    <button class="edit-btn" onclick="toggleEdit('phone')" style="background: none; border: none; color: var(--gochujang); cursor: pointer; font-size: 12px; margin-top: 5px;">✏️ Edit</button>
                </div>
                
                <div class="info-group" style="margin-bottom: 20px; padding-bottom: 15px; border-bottom: 1px solid #eee;">
                    <label style="font-weight: 600; display: block; margin-bottom: 5px; font-size: 13px; color: var(--ash);">Member Sejak</label>
                    <div style="font-size: 16px; font-weight: 600; padding: 8px 0;">
                        @if(auth()->user()->created_at)
                            {{ date('d F Y', strtotime(auth()->user()->created_at)) }}
                        @else
                            2024
                        @endif
                    </div>
                </div>
                
                <button class="save-btn" id="saveProfileBtn" onclick="saveProfileChanges()" style="display:none; background: var(--gochujang); color: white; border: none; padding: 12px; border-radius: 40px; width: 100%; font-weight: 600; cursor: pointer; margin-top: 20px;">💾 Simpan Perubahan</button>
                
                <form method="POST" action="{{ route('logout') }}" style="margin-top: 20px;">
                    @csrf
                    <button type="submit" style="background: #f44336; color: white; border: none; padding: 12px; border-radius: 40px; width: 100%; font-weight: 600; cursor: pointer;">🚪 Logout</button>
                </form>
                
                <a href="{{ route('customer.orders') }}" style="display: block; text-align: center; margin-top: 15px; color: var(--gochujang); text-decoration: none;">📋 Lihat Pesanan Saya →</a>
            </div>
            @else
            <div style="text-align: center; padding: 40px 20px;">
                <div style="font-size: 64px; margin-bottom: 20px;">🔐</div>
                <h3>Silakan Login</h3>
                <p style="margin-top: 10px; color: var(--ash);">Anda perlu login untuk melihat profil</p>
                <a href="{{ route('login') }}" class="btn-red" style="display: inline-block; margin-top: 20px;">Login Sekarang</a>
            </div>
            @endauth
        </div>
    </div>
</div>

<style>
.customer-layout {
    display: flex;
    flex-direction: column;
    min-height: 100vh;
}
.customer-hero {
    background: linear-gradient(135deg, #2c2c2c 0%, #1a1a1a 100%);
    padding: 48px 32px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 30px;
    color: white;
}
.ch-heading {
    font-size: 42px;
    font-family: var(--font-display);
    line-height: 1.2;
    margin: 16px 0 24px;
}
.ch-heading span { color: var(--gold); }
.ch-stats {
    display: flex;
    gap: 32px;
    margin-top: 20px;
}
.ch-stat-val {
    font-size: 28px;
    font-weight: 700;
}
.ch-stat-lbl {
    font-size: 12px;
    opacity: 0.7;
}
.ch-food {
    font-size: 120px;
    opacity: 0.8;
}
.btn-red {
    background: var(--gochujang);
    color: white;
    border: none;
    padding: 10px 24px;
    border-radius: 40px;
    font-weight: 600;
    text-decoration: none;
    display: inline-block;
    cursor: pointer;
}
.btn-red:hover {
    background: #a32e18;
}
.top-menu-bar {
    background: var(--ink);
    padding: 16px 32px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 20px;
}
.tmb-logo {
    font-size: 20px;
    font-weight: 700;
    color: white;
    text-decoration: none;
    font-family: var(--font-display);
}
.tmb-logo em { font-style: normal; color: var(--gold); }
.tmb-nav {
    display: flex;
    gap: 28px;
}
.tmb-nav-item {
    color: rgba(255,255,255,0.7);
    text-decoration: none;
    font-size: 14px;
    font-weight: 500;
    transition: 0.2s;
}
.tmb-nav-item:hover {
    color: var(--gold);
}
.tmb-icon {
    color: white;
    background: none;
    border: none;
    font-size: 20px;
    cursor: pointer;
}
@media (max-width: 768px) {
    .top-menu-bar { flex-direction: column; align-items: stretch; }
    .tmb-nav { justify-content: center; }
    .ch-heading { font-size: 32px; }
    .ch-food { font-size: 60px; }
}
</style>

@push('scripts')
<script>
let editingField = null;

function toggleEdit(field) {
    editingField = field;
    
    document.getElementById(`${field}Display`).style.display = 'none';
    document.getElementById(`${field}Input`).style.display = 'block';
    document.getElementById('saveProfileBtn').style.display = 'block';
    
    document.querySelectorAll('.edit-btn').forEach(btn => {
        btn.style.display = 'none';
    });
}

function saveProfileChanges() {
    if (editingField) {
        const input = document.getElementById(`${editingField}Input`);
        const newValue = input.value;
        
        if (newValue.trim()) {
            // Update display
            document.getElementById(`${editingField}Display`).innerText = newValue;
            document.getElementById(`${editingField}Display`).style.display = 'block';
            document.getElementById(`${editingField}Input`).style.display = 'none';
            
            if (editingField === 'name') {
                document.getElementById('profileName').innerText = newValue;
                const firstLetter = newValue.charAt(0).toUpperCase();
                document.getElementById('avatarText').innerHTML = firstLetter;
            }
            
            showToast('Profil berhasil diperbarui!');
            
            // Send to server
            fetch('{{ route("customer.profile.update") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ field: editingField, value: newValue })
            }).catch(error => console.log('Error:', error));
        }
        
        resetEditMode();
    }
}

function resetEditMode() {
    editingField = null;
    document.getElementById('saveProfileBtn').style.display = 'none';
    document.querySelectorAll('.edit-btn').forEach(btn => {
        btn.style.display = 'block';
    });
    document.querySelectorAll('.info-input').forEach(input => {
        input.style.display = 'none';
    });
    document.querySelectorAll('#nameDisplay, #emailDisplay, #phoneDisplay').forEach(display => {
        display.style.display = 'block';
    });
}

function showToast(message) {
    let toast = document.getElementById('toastMessage');
    if (!toast) {
        toast = document.createElement('div');
        toast.id = 'toastMessage';
        toast.style.cssText = 'position:fixed;bottom:24px;left:50%;transform:translateX(-50%);background:#1a1a1a;color:#fff;padding:12px 24px;border-radius:8px;font-size:14px;opacity:0;transition:opacity 0.3s;z-index:9999;pointer-events:none;';
        document.body.appendChild(toast);
    }
    toast.textContent = message;
    toast.style.opacity = '1';
    setTimeout(() => toast.style.opacity = '0', 2000);
}

// Set avatar
@auth
const name = '{{ auth()->user()->name }}';
if (name) {
    document.getElementById('avatarText').innerHTML = name.charAt(0).toUpperCase();
    document.getElementById('profileName').innerHTML = name;
}
@endauth
</script>
@endpush
@endsection