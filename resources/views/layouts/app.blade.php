<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'SIDAC Admin')</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            background-color: #f4f7f6;
            overflow: hidden; 
        }

        .app-layout {
            display: flex;
            height: 100vh; 
            width: 100vw;
            overflow: hidden;
        }

        /* === SIDEBAR === */
        .sidebar {
            z-index: 1000;
            width: 250px;
            background-color: #0d6efd;
            color: white;
            display: flex;
            flex-direction: column;
            transition: all 0.3s ease;
            position: relative;
            flex-shrink: 0; /* Sidebar tidak boleh mengecil */
            height: 100%; /* Full tinggi layar */
        }

        .sidebar.collapsed {
            width: 70px;
        }

        .sidebar-header {
            font-size: 1.5em;
            font-weight: bold;
            text-align: center;
            padding: 20px; 
            margin-bottom: 10px;
            transition: all 0.3s ease;
            border-bottom: 1px solid rgba(255,255,255,0.1); /* Garis pemisah tipis */
        }

        .sidebar.collapsed .sidebar-header span.logo-text {
            display: none;
        }

        .sidebar-nav {
            list-style: none;
            padding: 0 10px; /* Padding kiri kanan */
            margin: 0;
            flex-grow: 1; 
            overflow-y: auto; 
        }
        
        /* Sembunyikan scrollbar sidebar agar cantik */
        .sidebar-nav::-webkit-scrollbar {
            width: 0px;
            background: transparent;
        }

        .sidebar-nav li {
            margin-bottom: 5px;
        }

        .sidebar-nav a {
            color: white;
            text-decoration: none;
            display: flex;
            align-items: center;
            padding: 12px 15px;
            border-radius: 6px;
            font-size: 1em;
            transition: background-color 0.2s, padding 0.3s ease;
            white-space: nowrap; /* Mencegah teks turun baris */
        }

        .sidebar.collapsed a {
            justify-content: center;
            padding: 12px 0;
        }

        .sidebar.collapsed a span {
            display: none;
        }

        .sidebar-nav a:hover,
        .sidebar-nav a.active {
            background-color: #0b5ed7;
            box-shadow: 0 2px 4px rgba(0,0,0,0.2);
        }

        .sidebar-nav a i {
            margin-right: 10px;
            min-width: 20px; /* Pastikan icon center saat collapsed */
            text-align: center;
            transition: margin 0.3s ease;
        }

        .sidebar.collapsed a i {
            margin-right: 0;
        }

        .sidebar-footer {
            padding: 20px;
            border-top: 1px solid rgba(255,255,255,0.1);
            background-color: #0d6efd; /* Samakan warna agar seamless */
        }

        .sidebar-footer a {
            color: #ffc107; /* Warna kuning untuk logout agar mencolok */
            text-decoration: none;
            font-weight: bold;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s;
            padding: 10px;
            border-radius: 5px;
        }

        .sidebar-footer a:hover {
            background-color: rgba(255,255,255,0.1);
        }

        .sidebar.collapsed .sidebar-footer a span {
            display: none;
        }

        /* === TOGGLE BUTTON === */
        .toggle-btn {
            position: absolute;
            top: 20px;
            right: -15px; /* Keluar sedikit dari sidebar */
            background: white;
            color: #0d6efd;
            border: 1px solid #e0e0e0;
            cursor: pointer;
            font-size: 14px;
            width: 30px;
            height: 30px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 2px 5px rgba(0,0,0,0.2);
            z-index: 1001; /* Pastikan di atas konten */
            transition: all 0.3s ease;
        }

        .toggle-btn:hover {
            background-color: #f8f9fa;
            transform: scale(1.1);
        }

        /* === MAIN CONTENT === */
        .main-content {
            flex-grow: 1;
            padding: 25px;
            /* KUNCI 4: Scrollbar pindah ke sini */
            height: 100%; 
            overflow-y: auto; 
            background-color: #f4f7f6;
            position: relative;
        }

        .content-card {
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
            position: relative;
            z-index: 1;
        }
    </style>
</head>
<body>
    <div class="app-layout">
        <!-- Sidebar -->
        <aside class="sidebar" id="sidebar">
            <div class="sidebar-header">
                <span class="logo-text">SIDAC</span>
                <button class="toggle-btn" id="toggle-btn">
                    <i class="fa fa-chevron-left"></i>
                </button>
            </div>

            <ul class="sidebar-nav">
                <li>
                    <a href="{{ route('dashboard') }}" class="{{ Request::is('/') ? 'active' : '' }}">
                        <i class="fa fa-chart-bar"></i> <span>Dashboard</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('produk.index') }}" class="{{ Request::is('produk*') ? 'active' : '' }}">
                        <i class="fa fa-box"></i> <span>Kelola Produk</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('pelanggan.index') }}" class="{{ Request::is('pelanggan*') ? 'active' : '' }}">
                        <i class="fa fa-users"></i> <span>Kelola Pelanggan</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('transaksi.index') }}" class="{{ Request::is('transaksi*') ? 'active' : '' }}">
                        <i class="fa fa-receipt"></i> <span>Kelola Transaksi</span>
                    </a>
                </li>
                @if (auth()->check() && auth()->user()->Role === 'Manajer Operasional')
                    <li>
                        <a href="{{ route('user.index') }}" class="{{ Request::is('user*') ? 'active' : '' }}">
                            <i class="fa fa-user-shield"></i> <span>Kelola User</span>
                        </a>
                    </li>
                @endif
            </ul>

            <div class="sidebar-footer">
                <form method="POST" action="{{ route('logout') }}" id="logout-form">
                    @csrf
                    <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <i class="fa fa-sign-out-alt"></i> <span>Logout</span>
                    </a>
                </form>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="main-content">
            @yield('content')
        </main>
    </div>

    <script>
        const sidebar = document.getElementById('sidebar');
        const toggleBtn = document.getElementById('toggle-btn');
        const icon = toggleBtn.querySelector('i');

        // Cek local storage saat load halaman agar status sidebar tersimpan
        const isCollapsed = localStorage.getItem('sidebar-collapsed') === 'true';
        if (isCollapsed) {
            sidebar.classList.add('collapsed');
            icon.classList.remove('fa-chevron-left');
            icon.classList.add('fa-chevron-right');
        }

        toggleBtn.addEventListener('click', () => {
            sidebar.classList.toggle('collapsed');
            
            // Ganti ikon
            if (sidebar.classList.contains('collapsed')) {
                icon.classList.remove('fa-chevron-left');
                icon.classList.add('fa-chevron-right');
                localStorage.setItem('sidebar-collapsed', 'true');
            } else {
                icon.classList.remove('fa-chevron-right');
                icon.classList.add('fa-chevron-left');
                localStorage.setItem('sidebar-collapsed', 'false');
            }
        });
    </script>

    <!-- Notifikasi Flash Message -->
    @if(session('success'))
    <div 
        x-data="{ show: true }" 
        x-show="show" 
        x-transition.duration.300ms
        x-init="setTimeout(() => show = false, 3000)" 
        class="fixed top-5 right-5 z-[9999]"
    >
        <div class="flex items-center justify-between bg-green-500 text-white font-medium px-4 py-3 rounded-lg shadow-lg w-80">
            <span>{{ session('success') }}</span>
            <button @click="show = false" class="text-white text-lg leading-none hover:text-gray-200">&times;</button>
        </div>
    </div>
    @endif

    @if(session('error'))
    <div 
        x-data="{ show: true }" 
        x-show="show" 
        x-transition.duration.300ms
        x-init="setTimeout(() => show = false, 3000)" 
        class="fixed top-5 right-5 z-[9999]"
    >
        <div class="flex items-center justify-between bg-red-500 text-white font-medium px-4 py-3 rounded-lg shadow-lg w-80">
            <span>{{ session('error') }}</span>
            <button @click="show = false" class="text-white text-lg leading-none hover:text-gray-200">&times;</button>
        </div>
    </div>
    @endif
</body>
</html>