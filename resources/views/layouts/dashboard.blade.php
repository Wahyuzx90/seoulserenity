<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>@yield('title', 'Seoul Serenity') — @yield('role', 'Dashboard')</title>
<link rel="stylesheet" href="{{ asset('css/app.css') }}">
@stack('styles')
</head>
<body>
<div class="app-shell">
  <!-- Sidebar -->
  <div class="sidebar">
    <div class="sidebar-logo">
      <div class="logo-ko">서울 세레니티</div>
      <div class="logo-brand">Seoul<em>Serenity</em></div>
      <div class="logo-role">@yield('sidebar-role', '👔 Karyawan')</div>
    </div>

    @yield('sidebar-nav')

    <div class="sidebar-foot">
      <div class="user-avatar-row">
        <div class="avatar" style="background:linear-gradient(135deg,var(--gochujang),var(--gold));">
          {{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 2)) }}
        </div>
        <div>
          <div class="avatar-name">{{ auth()->user()->name ?? 'User' }}</div>
          <div class="avatar-role">{{ auth()->user()->role ?? 'Staff' }}</div>
        </div>
      </div>
      <div style="margin-top:14px;">
        <form method="POST" action="{{ route('logout') }}">
          @csrf
          <button type="submit" class="nav-item" style="width:100%;background:none;border:none;cursor:pointer;padding:8px 0;">
            <span class="ni">🚪</span> Keluar
          </button>
        </form>
      </div>
    </div>
  </div>

  <!-- Main Content -->
  <div class="main-content">
    <!-- Topbar -->
    <div class="topbar">
      <div>
        <div class="topbar-title">@yield('page-title')</div>
        <div class="topbar-sub">@yield('page-sub')</div>
      </div>
      <div class="topbar-spacer"></div>
      @yield('topbar-actions')
    </div>

    <!-- Page Body -->
    <div class="page-body">
      @if(session('success'))
        <div class="alert alert-success">✅ {{ session('success') }}</div>
      @endif
      @if(session('error'))
        <div class="alert alert-error">❌ {{ session('error') }}</div>
      @endif

      @yield('content')
    </div>
  </div>
</div>
@stack('scripts')
</body>
</html>
