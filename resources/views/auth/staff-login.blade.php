@extends('layouts.app')
@section('title', 'Staff Login — Seoul Serenity')

@push('styles')
<style>
body { background: var(--cream); display: flex; align-items: center; justify-content: center; min-height: 100vh; }
.login-page { display: flex; border-radius: 20px; overflow: hidden; box-shadow: var(--shadow-lg); width: 900px; }
</style>
@endpush

@section('content')
<div class="login-page">
  <div class="login-left" style="background:linear-gradient(160deg,#0D1F12,#1A3D22);">
    <div class="deco-circle c1" style="border-color:rgba(74,103,65,0.15);"></div>
    <div class="deco-circle c2" style="border-color:rgba(201,168,76,0.08);"></div>
    <div class="hangul-bg" style="color:rgba(74,103,65,0.15);">직원</div>
    <div class="login-badge" style="background:rgba(74,103,65,0.2);color:rgba(130,200,130,0.8);">👔 Staff Portal · Seoul Serenity</div>
    <div class="login-heading">Manajemen<br><em style="color:#4CAF50;">Pesanan</em></div>
    <div class="login-line" style="background:var(--sage);"></div>
    <div class="login-tagline">Portal khusus karyawan untuk mengelola, memproses, dan memantau semua pesanan yang masuk ke dapur Seoul Serenity.</div>
  </div>
  <div class="login-right">
    <div class="login-form-box">
      <div class="lf-eyebrow">Staff Portal</div>
      <div class="lf-title">Masuk sebagai Karyawan</div>

      @if($errors->any())
        <div class="alert alert-error">{{ $errors->first() }}</div>
      @endif

      <form method="POST" action="{{ route('staff.login.post') }}">
        @csrf
        <div class="field-group">
          <label class="field-label">ID Karyawan / Email</label>
          <input class="field-input" type="email" name="email" value="{{ old('email') }}" placeholder="staff@seoulserenity.id" required>
        </div>
        <div class="field-group">
          <label class="field-label">Kata Sandi</label>
          <input class="field-input" type="password" name="password" required>
        </div>
        <button type="submit" class="btn btn-green" style="width:100%;justify-content:center;padding:14px;font-size:15px;">
          Masuk →
        </button>
      </form>

      <div class="lf-footer">
        Butuh akses? <a href="#" style="color:var(--sage);">Hubungi manajer</a>
      </div>
      <div class="lf-footer" style="margin-top:10px;">
        <a href="{{ route('login') }}" style="color:var(--ash);">← Kembali ke Login Customer</a>
      </div>
    </div>
  </div>
</div>
@endsection
