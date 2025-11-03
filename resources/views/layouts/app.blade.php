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
            transition: all 0.3s ease;
        }

        /* === SIDEBAR === */
        .sidebar {
            width: 250px;
            background-color: #0d6efd;
            color: white;
            padding: 20px;
            display: flex;
            flex-direction: column;
            transition: all 0.3s ease;
            position: relative;
        }

        .sidebar.collapsed {
            width: 70px;
            text-align: center;
        }

        .sidebar-header {
            font-size: 1.5em;
            font-weight: bold;
            text-align: center;
            margin-bottom: 30px;
            transition: all 0.3s ease;
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
            display: flex;
            align-items: center;
            padding: 12px 15px;
            border-radius: 6px;
            font-size: 1em;
            transition: background-color 0.2s, padding 0.3s ease;
        }

        .sidebar.collapsed a {
            justify-content: center;
        }

        .sidebar.collapsed a span {
            display: none;
        }

        .sidebar-nav a:hover,
        .sidebar-nav a.active {
            background-color: #0b5ed7;
        }

        .sidebar-nav a i {
            margin-right: 10px;
            transition: margin 0.3s ease;
        }

        .sidebar.collapsed a i {
            margin-right: 0;
        }

        .sidebar-footer {
            text-align: center;
            font-size: 0.9em;
        }

        .sidebar-footer a {
            color: #ffc107;
            text-decoration: none;
            font-weight: bold;
        }

        /* === TOGGLE BUTTON === */
        .toggle-btn {
            position: absolute;
            top: 15px;
            right: -15px;
            background: white;
            color: #0d6efd;
            border: none;
            cursor: pointer;
            font-size: 18px;
            padding: 6px 8px;
            border-radius: 50%;
            box-shadow: 0 2px 5px rgba(0,0,0,0.2);
            transition: transform 0.3s ease;
        }

        .toggle-btn:hover {
            transform: scale(1.1);
        }

        /* === MAIN CONTENT === */
        .main-content {
            flex-grow: 1;
            padding: 25px;
            overflow: auto;
            transition: all 0.3s ease;
        }

        .content-card {
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        }
    </style>
</head>
<body>
    <div class="app-layout">
        <aside class="sidebar" id="sidebar">
            <div class="sidebar-header">
                SIDAC
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
                    <a href="{{ route('user.index') }}" class="{{ Request::is('user*') ? 'active' : '' }}">
                        <i class="fa fa-user-shield"></i> <span>Kelola User</span>
                    </a>
                </li>
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

        <main class="main-content">
            @yield('content')
        </main>
    </div>

    <script>
        const sidebar = document.getElementById('sidebar');
        const toggleBtn = document.getElementById('toggle-btn');
        const icon = toggleBtn.querySelector('i');

        toggleBtn.addEventListener('click', () => {
            sidebar.classList.toggle('collapsed');
            
            // Ganti ikon sesuai status sidebar
            if (sidebar.classList.contains('collapsed')) {
                icon.classList.remove('fa-chevron-left');
                icon.classList.add('fa-chevron-right');
            } else {
                icon.classList.remove('fa-chevron-right');
                icon.classList.add('fa-chevron-left');
            }
        });
    </script>
</body>
</html>
