@extends('layouts.app')
@section('title', 'Daftar — Seoul Serenity')

@push('styles')
<style>
body { background: var(--cream); display: flex; align-items: center; justify-content: center; min-height: 100vh; }
.login-page { display: flex; border-radius: 20px; overflow: hidden; box-shadow: var(--shadow-lg); width: 900px; }
</style>
@endpush

@section('content')
<div class="login-page">
  <div class="login-left">
    <div class="deco-circle c1"></div>
    <div class="deco-circle c2"></div>
    <div class="hangul-bg">가입</div>
    <div class="login-badge">🇰🇷 Bergabung Sekarang</div>
    <div class="login-heading">Mulai<br><em>Petualangan</em></div>
    <div class="login-line"></div>
    <div class="login-tagline">
      Daftar dan nikmati pengalaman memesan makanan Korea terbaik langsung dari dapur Seoul Serenity.
    </div>
  </div>
  <div class="login-right">
    <div class="login-form-box">
      <div class="lf-eyebrow">Buat Akun Baru</div>
      <div class="lf-title">Daftar Sekarang</div>

      @if($errors->any())
        <div class="alert alert-error">{{ $errors->first() }}</div>
      @endif

      <form method="POST" action="{{ route('register') }}">
        @csrf
        <div class="field-group">
          <label class="field-label">Nama Lengkap</label>
          <input class="field-input" type="text" name="name" value="{{ old('name') }}" placeholder="Nama Anda" required>
        </div>
        <div class="field-group">
          <label class="field-label">Email</label>
          <input class="field-input" type="email" name="email" value="{{ old('email') }}" placeholder="email@contoh.com" required>
        </div>
        <div class="field-group">
          <label class="field-label">No. Telepon</label>
          <input class="field-input" type="tel" name="phone" value="{{ old('phone') }}" placeholder="0812-xxxx-xxxx">
        </div>
        <div class="field-group">
          <label class="field-label">Kata Sandi</label>
          <input class="field-input" type="password" name="password" required>
        </div>
        <div class="field-group">
          <label class="field-label">Konfirmasi Kata Sandi</label>
          <input class="field-input" type="password" name="password_confirmation" required>
        </div>
        <button type="submit" class="btn btn-red" style="width:100%;justify-content:center;padding:14px;font-size:15px;">
          Daftar Sekarang →
        </button>
      </form>

      <div class="lf-footer">
        Sudah punya akun? <a href="{{ route('login') }}">Masuk</a>
      </div>
    </div>
  </div>
</div>
@endsection
