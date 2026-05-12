@extends('layouts.dashboard')
@section('title', isset($menu) ? 'Edit Menu' : 'Tambah Menu')
@section('role', 'Owner')
@section('sidebar-role', '👑 Owner')

@section('sidebar-nav')
<div class="nav-section-label">Laporan</div>
<a href="{{ route('owner.reports') }}" class="nav-item"><span class="ni">📊</span> Laporan Penjualan</a>
<a href="{{ route('owner.orders') }}" class="nav-item"><span class="ni">📋</span> Semua Pesanan</a>
<a href="{{ route('owner.menus') }}" class="nav-item active"><span class="ni">🍽️</span> Manajemen Menu</a>
@endsection

@section('page-title', isset($menu) ? 'Edit Menu' : 'Tambah Menu Baru')
@section('page-sub', 'Manajemen Menu · Seoul Serenity')

@push('styles')
<style>
.form-img-preview {
  width: 100%;
  height: 200px;
  object-fit: cover;
  border-radius: 12px;
  border: 2px solid #eee;
  display: block;
  margin-bottom: 10px;
}
.form-img-placeholder {
  width: 100%;
  height: 200px;
  border-radius: 12px;
  border: 2px dashed #ddd;
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  font-size: 48px;
  margin-bottom: 10px;
  background: #fafafa;
  cursor: pointer;
  transition: border-color 0.2s;
}
.form-img-placeholder:hover {
  border-color: var(--gochujang, #C23B22);
}
.form-img-placeholder small {
  font-size: 12px;
  color: #aaa;
  margin-top: 6px;
}
</style>
@endpush

@section('content')
<div class="two-col" style="max-width:900px;">
  <div class="card">

    @if($errors->any())
    <div style="background:#fce4ec;color:#c62828;padding:12px 16px;border-radius:10px;margin-bottom:16px;">
      <ul style="margin:0;padding-left:16px;">
        @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
      </ul>
    </div>
    @endif

    <form method="POST"
          action="{{ isset($menu) ? route('owner.menus.update', $menu) : route('owner.menus.store') }}"
          enctype="multipart/form-data">
      @csrf
      @if(isset($menu)) @method('PUT') @endif

      <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;">

        {{-- ===== FOTO MENU (full width) ===== --}}
        <div class="field-group" style="grid-column:span 2;">
          <label class="field-label">📸 Foto Menu</label>

          {{-- Preview foto --}}
          @if(isset($menu) && $menu->image && file_exists(public_path('images/' . $menu->image)))
            <img id="imgPreview"
                 class="form-img-preview"
                 src="{{ asset('images/' . $menu->image) }}"
                 alt="{{ $menu->name }}">
          @else
            <div class="form-img-placeholder" id="imgPlaceholder" onclick="document.getElementById('imageInput').click()">
              <span>{{ isset($menu) ? ($menu->emoji ?? '🍽️') : '🍽️' }}</span>
              <small>Klik untuk pilih foto</small>
            </div>
            <img id="imgPreview" class="form-img-preview" src="" alt="Preview" style="display:none;">
          @endif

          <input type="file"
                 name="image"
                 id="imageInput"
                 accept="image/*"
                 style="width:100%;"
                 onchange="previewFoto(this)">
          <small style="color:#999;font-size:12px;">Format: jpg, png, webp, gif, avif. Maks 5MB. Kosongkan jika tidak ingin mengubah foto.</small>
        </div>

        {{-- ===== NAMA INDONESIA ===== --}}
        <div class="field-group" style="grid-column:span 2;">
          <label class="field-label">Nama Menu (Indonesia) <span style="color:red;">*</span></label>
          <input class="field-input" type="text" name="name"
                 value="{{ old('name', $menu->name ?? '') }}" required>
        </div>

        {{-- ===== NAMA KOREA ===== --}}
        <div class="field-group">
          <label class="field-label">Nama Korea (한국어)</label>
          <input class="field-input" type="text" name="name_ko"
                 value="{{ old('name_ko', $menu->name_ko ?? '') }}">
        </div>

        {{-- ===== KATEGORI ===== --}}
        <div class="field-group">
          <label class="field-label">Kategori <span style="color:red;">*</span></label>
          <select class="field-select" name="category" required>
            @foreach(['Sup & Jjigae','BBQ & Grill','Nasi & Bento','Mie & Ramyeon','Minuman','Snack','Lain-lain'] as $cat)
            <option value="{{ $cat }}"
              {{ old('category', $menu->category ?? '') == $cat ? 'selected' : '' }}>
              {{ $cat }}
            </option>
            @endforeach
          </select>
        </div>

        {{-- ===== DESKRIPSI ===== --}}
        <div class="field-group" style="grid-column:span 2;">
          <label class="field-label">Deskripsi</label>
          <textarea class="field-input" name="description" rows="2">{{ old('description', $menu->description ?? '') }}</textarea>
        </div>

        {{-- ===== HARGA ===== --}}
        <div class="field-group">
          <label class="field-label">Harga (Rp) <span style="color:red;">*</span></label>
          <input class="field-input" type="number" name="price"
                 value="{{ old('price', $menu->price ?? 0) }}" min="0" required>
        </div>

        {{-- ===== DISKON ===== --}}
        <div class="field-group">
          <label class="field-label">Diskon (%)</label>
          <input class="field-input" type="number" name="discount"
                 value="{{ old('discount', $menu->discount ?? 0) }}" min="0" max="100">
        </div>

        {{-- ===== STOK ===== --}}
        <div class="field-group">
          <label class="field-label">Stok</label>
          <input class="field-input" type="number" name="stock"
                 value="{{ old('stock', $menu->stock ?? 99) }}" min="0">
        </div>

        {{-- ===== EMOJI ===== --}}
        <div class="field-group">
          <label class="field-label">Emoji (opsional)</label>
          <input class="field-input" type="text" name="emoji"
                 value="{{ old('emoji', $menu->emoji ?? '🍽️') }}" maxlength="10">
        </div>

        {{-- ===== BG CLASS ===== --}}
        <div class="field-group">
          <label class="field-label">Warna Kartu (jika tanpa foto)</label>
          <select class="field-select" name="bg_class">
            @foreach([
              'bg-sup'     => '🟣 Sup & Jjigae (Ungu)',
              'bg-bbq'     => '🔴 BBQ & Grill (Merah)',
              'bg-nasi'    => '🔵 Nasi & Bento (Biru)',
              'bg-mie'     => '🟢 Mie & Ramyeon (Hijau)',
              'bg-minuman' => '🩵 Minuman (Biru Muda)',
              'bg-snack'   => '🌸 Snack (Pink)',
              'bg-k1'      => 'K1 (Default)',
              'bg-k2'      => 'K2', 'bg-k3' => 'K3',
              'bg-k4'      => 'K4', 'bg-k5' => 'K5',
            ] as $val => $label)
            <option value="{{ $val }}" {{ old('bg_class', $menu->bg_class ?? 'bg-k1') == $val ? 'selected' : '' }}>{{ $label }}</option>
            @endforeach
          </select>
        </div>

        {{-- ===== CHECKBOX ===== --}}
        <div class="field-group" style="grid-column:span 2;">
          <label class="field-label" style="margin-bottom:12px;">Opsi Tampilan</label>
          <div style="display:flex;gap:24px;">
            <label style="display:flex;align-items:center;gap:8px;font-size:14px;cursor:pointer;">
              <input type="checkbox" name="is_available" value="1"
                     {{ old('is_available', $menu->is_available ?? true) ? 'checked' : '' }}
                     style="accent-color:var(--gochujang);">
              ✅ Tersedia (tampil di menu pelanggan)
            </label>
            <label style="display:flex;align-items:center;gap:8px;font-size:14px;cursor:pointer;">
              <input type="checkbox" name="is_featured" value="1"
                     {{ old('is_featured', $menu->is_featured ?? false) ? 'checked' : '' }}
                     style="accent-color:#f57f17;">
              ⭐ Unggulan (tampil di bagian atas)
            </label>
          </div>
        </div>

      </div>{{-- end grid --}}

      <div style="display:flex;gap:10px;margin-top:16px;">
        <button type="submit" class="btn btn-red">
          {{ isset($menu) ? '💾 Simpan Perubahan' : '+ Tambah Menu' }}
        </button>
        <a href="{{ route('owner.menus') }}" class="btn btn-ghost">Batal</a>
      </div>
    </form>
  </div>
</div>

@push('scripts')
<script>
function previewFoto(input) {
  const preview   = document.getElementById('imgPreview');
  const placeholder = document.getElementById('imgPlaceholder');
  if (input.files && input.files[0]) {
    const reader = new FileReader();
    reader.onload = function(e) {
      preview.src = e.target.result;
      preview.style.display = 'block';
      if (placeholder) placeholder.style.display = 'none';
    };
    reader.readAsDataURL(input.files[0]);
  }
}
</script>
@endpush

@endsection