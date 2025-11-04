<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'SIDAC Admin')</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            background-color: #f4f7f6;
        }
        .app-layout {
            display: flex;
            min-height: 100vh;
        }
        
        /* Sidebar (Sesuai Desain DPPL) */
        .sidebar {
            width: 250px;
            background-color: #0d6efd; /* Biru */
            color: white;
            padding: 20px;
            display: flex;
            flex-direction: column;
        }
        .sidebar-header {
            font-size: 1.5em;
            font-weight: bold;
            text-align: center;
            margin-bottom: 30px;
        }
        .sidebar-nav {
            list-style: none;
            padding: 0;
            margin: 0;
            flex-grow: 1;
        }
        .sidebar-nav li {
            margin-bottom: 10px;
        }
        .sidebar-nav a {
            color: white;
            text-decoration: none;
            display: block;
            padding: 12px 15px;
            border-radius: 6px;
            font-size: 1em;
            transition: background-color 0.2s;
        }
        .sidebar-nav a:hover,
        .sidebar-nav a.active { /* 'active' class untuk halaman saat ini */
            background-color: #0b5ed7; /* Biru lebih gelap */
        }
        .sidebar-nav a i {
            margin-right: 10px;
        }
        .sidebar-footer {
            text-align: center;
            font-size: 0.9em;
        }
        .sidebar-footer a {
            color: #ffc107; /* Kuning */
            text-decoration: none;
            font-weight: bold;
        }
        
        /* Konten Utama */
        .main-content {
            flex-grow: 1;
            padding: 25px;
            overflow: auto;
        }
        .main-content .content-card {
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        }
    </style>
</head>
<body>
    <div class="app-layout">
        <aside class="sidebar">
            <div class="sidebar-header">
                SIDAC
            </div>
            <ul class="sidebar-nav">
                <li>
                    <a href="{{ route('dashboard') }}" class="{{ Request::is('/') ? 'active' : '' }}">
                        <i class="fa fa-chart-bar"></i> Dashboard
                    </a>
                </li>
                <li>
                    <a href="{{ route('produk.index') }}" class="{{ Request::is('produk*') ? 'active' : '' }}">
                        <i class="fa fa-box"></i> Kelola Produk
                    </a>
                </li>
                <li>
                    <a href="{{ route('pelanggan.index') }}" class="{{ Request::is('pelanggan*') ? 'active' : '' }}">
                        <i class="fa fa-users"></i> Kelola Pelanggan
                    </a>
                </li>
                <li>
                    <a href="{{ route('transaksi.index') }}" class="{{ Request::is('transaksi*') ? 'active' : '' }}">
                        <i class="fa fa-receipt"></i> Kelola Transaksi
                    </a>
                </li>
                <li>
                    <a href="{{ route('user.index') }}" class="{{ Request::is('user*') ? 'active' : '' }}">
                        <i class="fa fa-user-shield"></i> Kelola User
                    </a>
                </li>
            </ul>
            <div class="sidebar-footer">
                <form method="POST" action="{{ route('logout') }}" id="logout-form">
                    @csrf
                    <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <i class="fa fa-sign-out-alt"></i> Logout
                    </a>
                </form>
            </div>
        </aside>

        <main class="main-content">
            <div class="content-card">
                @yield('content')
            </div>
        </main>
    </div>
</body>
</html>