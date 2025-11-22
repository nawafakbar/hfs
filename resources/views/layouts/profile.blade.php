<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>@yield('title') - Akun Saya</title>
  <link rel="icon" href="{{ asset('user-assets/Images/logo.png') }}" type="image/x-icon">
  <link rel="shortcut icon" href="{{ asset('user-assets/Images/logo.png') }}" type="image/x-icon">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
  <style>
    body {
      background-color: #f8f9fa;
    }

    /* ======== SIDEBAR UTAMA ======== */
    .profile-layout {
      display: flex;
      min-height: 100vh;
    }

    .profile-sidebar {
      width: 280px;
      background: #f1f3f5;
      padding: 2rem 1.5rem;
      transition: transform 0.3s ease;
      z-index: 1051;
    }

    .sidebar-user {
      text-align: center;
      margin-bottom: 2rem;
    }

    .sidebar-user .avatar {
      width: 80px;
      height: 80px;
      border-radius: 50%;
      object-fit: cover;
      margin-bottom: 0.75rem;
    }

    .sidebar-user h5 {
      margin-bottom: 0;
    }

    .sidebar-user small {
      color: #6c757d;
      font-size: 0.9rem;
      display: block;
    }

    .sidebar-menu .nav-link {
      color: #495057;
      font-weight: 500;
      margin-bottom: 0.5rem;
      border-radius: 0.5rem;
    }

    .sidebar-menu .nav-link.active,
    .sidebar-menu .nav-link:hover {
      background-color: #e9ecef;
      color: #000;
    }

    .sidebar-menu .nav-link i {
      margin-right: 10px;
    }

    .profile-content {
      flex: 1;
      padding: 2rem 3rem;
      z-index: 1;
    }

    /* ======== OVERLAY UNTUK MOBILE ======== */
    .sidebar-overlay {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: rgba(0, 0, 0, 0.4);
      opacity: 0;
      visibility: hidden;
      transition: opacity 0.3s ease;
      z-index: 1050;
    }

    .sidebar-overlay.show {
      opacity: 1;
      visibility: visible;
    }

    /* ======== RESPONSIVE MOBILE ======== */
    @media (max-width: 991.98px) {
      .profile-layout {
        flex-direction: column;
      }

      .profile-sidebar {
        position: fixed;
        top: 0;
        left: 0;
        height: 100vh;
        transform: translateX(-100%);
        box-shadow: 5px 0 15px rgba(0,0,0,0.2);
      }

      .profile-sidebar.show {
        transform: translateX(0);
      }

      .sidebar-toggle {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 1rem;
        background: #f1f3f5;
        border-bottom: 1px solid #dee2e6;
      }

      /* Avatar + text sejajar rapi */
      .sidebar-user {
        text-align: center;
      }

      .sidebar-user .avatar {
        width: 70px;
        height: 70px;
      }

      .profile-content {
        padding: 1.5rem;
      }
    }
  </style>
</head>
<body>

  <!-- Tombol Toggle (Muncul di Mobile) -->
  <div class="sidebar-toggle d-lg-none">
    <button class="btn btn-outline-dark" id="sidebarToggle"><i class="bi bi-list"></i></button>
    <h6 class="mb-0">Akun Saya</h6>
  </div>

  <!-- Overlay hitam transparan -->
  <div class="sidebar-overlay" id="sidebarOverlay"></div>

  <div class="profile-layout">
    <!-- Sidebar -->
    <aside class="profile-sidebar" id="sidebarMenu">
      <div class="sidebar-user">
        <img src="{{ auth()->user()->profile_photo_path ? asset('storage/' . auth()->user()->profile_photo_path) : 'https://ui-avatars.com/api/?name='.urlencode(auth()->user()->name) }}" alt="Avatar" class="avatar">
        <h5 class="mb-0">{{ auth()->user()->name }}</h5>
        <small>{{ auth()->user()->email }}</small>
      </div>
      <ul class="nav flex-column sidebar-menu">
        <li class="nav-item">
          <a class="nav-link {{ request()->routeIs('profile.edit') ? 'active' : '' }}" href="{{ route('profile.edit') }}">
            <i class="bi bi-person"></i> Profil Saya
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link {{ request()->routeIs('orders.*') ? 'active' : '' }}" href="{{ route('orders.index') }}">
            <i class="bi bi-receipt"></i> Pesanan
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="{{ route('home') }}">
            <i class="bi bi-house"></i> Home
          </a>
        </li>
        <li class="nav-item mt-1">
          <form method="POST" action="{{ route('logout') }}">
            @csrf
            <a class="nav-link" href="{{ route('logout') }}" onclick="event.preventDefault(); this.closest('form').submit();">
              <i class="bi bi-box-arrow-left"></i> Keluar
            </a>
          </form>
        </li>
      </ul>
    </aside>

    <!-- Konten -->
    <main class="profile-content">
      @yield('content')
    </main>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    const sidebarToggle = document.getElementById('sidebarToggle');
    const sidebarMenu = document.getElementById('sidebarMenu');
    const sidebarOverlay = document.getElementById('sidebarOverlay');

    // Toggle sidebar
    sidebarToggle.addEventListener('click', () => {
      sidebarMenu.classList.toggle('show');
      sidebarOverlay.classList.toggle('show');
    });

    // Klik luar sidebar (overlay) = tutup
    sidebarOverlay.addEventListener('click', () => {
      sidebarMenu.classList.remove('show');
      sidebarOverlay.classList.remove('show');
    });
  </script>
</body>
</html>
