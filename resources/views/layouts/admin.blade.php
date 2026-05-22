<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - Admin Dashboard</title>
    <link rel="icon" type="image/svg+xml" href="{{ asset('images/logo.svg') }}">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css">
    <style>
        :root {
            --sidebar-width: 260px;
            --primary: #6366f1;
            --primary-light: #eef2ff;
            --bg: #f8fafc;
            --text: #1e293b;
            --text-muted: #64748b;
            --border: #e2e8f0;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Inter', sans-serif; background: var(--bg); color: var(--text); }
        .app-wrapper { display: flex; min-height: 100vh; }

        /* Sidebar */
        .sidebar {
            width: var(--sidebar-width);
            background: white;
            height: 100vh;
            position: fixed;
            border-right: 1px solid var(--border);
            padding: 1.5rem;
            display: flex;
            flex-direction: column;
        }

        .sidebar-header {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--primary);
            margin-bottom: 2.5rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .nav-menu { list-style: none; flex-grow: 1; }
        .nav-item { margin-bottom: 0.5rem; }
        .nav-link {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 0.75rem 1rem;
            text-decoration: none;
            color: var(--text-muted);
            border-radius: 0.75rem;
            transition: all 0.2s;
            font-weight: 500;
        }
        .nav-link:hover, .nav-link.active {
            background: var(--primary-light);
            color: var(--primary);
        }

        /* Main Content */
        .main-content {
            margin-left: var(--sidebar-width);
            width: calc(100% - var(--sidebar-width));
            padding: 2rem;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2.5rem;
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .logout-btn {
            background: none;
            border: none;
            color: #ef4444;
            cursor: pointer;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        /* Cards */
        .card {
            background: white;
            border-radius: 1rem;
            padding: 1.5rem;
            border: 1px solid var(--border);
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }

        .btn {
            padding: 0.625rem 1.25rem;
            border-radius: 0.75rem;
            border: none;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            text-decoration: none;
        }
        .btn-primary { background: var(--primary); color: white; }
        .btn-danger { background: #fee2e2; color: #ef4444; }

        /* Tables */
        table { width: 100%; border-collapse: collapse; margin-top: 1rem; }
        th { text-align: left; padding: 1rem; border-bottom: 2px solid var(--border); color: var(--text-muted); font-size: 0.875rem; }
        td { padding: 1rem; border-bottom: 1px solid var(--border); }
        
        .badge {
            padding: 0.25rem 0.75rem;
            border-radius: 1rem;
            font-size: 0.75rem;
            font-weight: 600;
        }

        /* Modal Styles */
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.5);
            backdrop-filter: blur(4px);
            align-items: center;
            justify-content: center;
        }
        .modal.active { display: flex; }
        .modal-content {
            background: white;
            padding: 2rem;
            border-radius: 1.5rem;
            width: 100%;
            max-width: 500px;
            box-shadow: 0 25px 50px -12px rgba(0,0,0,0.25);
            animation: modalFadeIn 0.3s ease-out;
        }
        @keyframes modalFadeIn {
            from { opacity: 0; transform: scale(0.95); }
            to { opacity: 1; transform: scale(1); }
        }

        /* Toggle Switch */
        .switch {
            position: relative;
            display: inline-block;
            width: 44px;
            height: 24px;
        }
        .switch input { opacity: 0; width: 0; height: 0; }
        .slider {
            position: absolute;
            cursor: pointer;
            top: 0; left: 0; right: 0; bottom: 0;
            background-color: #e2e8f0;
            transition: .4s;
            border-radius: 24px;
        }
        .slider:before {
            position: absolute;
            content: "";
            height: 18px; width: 18px;
            left: 3px; bottom: 3px;
            background-color: white;
            transition: .4s;
            border-radius: 50%;
        }
        input:checked + .slider { background-color: var(--primary); }
        input:checked + .slider:before { transform: translateX(20px); }
    </style>
    <script>
        function toggleModal(id) {
            const modal = document.getElementById(id);
            modal.classList.toggle('active');
        }
    </script>
    @yield('styles')
</head>
<body>
    <div class="app-wrapper">
        <div class="sidebar">
        <div class="sidebar-header">
            <img src="{{ asset('images/logo.svg') }}" alt="Logo" style="height: 32px; width: 32px;">
            OPOD Agent
        </div>
        <ul class="nav-menu">
            <li class="nav-item">
                <a href="{{ route('settings.index') }}" class="nav-link {{ request()->routeIs('settings.*') ? 'active' : '' }}">
                    <i class="fas fa-cog"></i> Main Settings
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('users.index') }}" class="nav-link {{ request()->routeIs('users.*') ? 'active' : '' }}">
                    <i class="fas fa-users"></i> Users
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('admin.lookups.index') }}" class="nav-link {{ request()->routeIs('admin.lookups.*') ? 'active' : '' }}">
                    <i class="fas fa-search"></i> Lookups
                </a>
            </li>
        </ul>
        <div class="sidebar-footer">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="logout-btn">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </button>
            </form>
        </div>
    </div>

    <div class="main-content">
        <div class="header">
            <h1>@yield('header')</h1>
            <div class="user-info">
                <span>{{ auth()->user()->name }}</span>
                <div style="width: 40px; height: 40px; border-radius: 50%; background: var(--primary); color: white; display: flex; align-items: center; justify-content: center; font-weight: 600;">
                    {{ substr(auth()->user()->name, 0, 1) }}
                </div>
            </div>
        </div>

        @if(session('success'))
            <div style="background: #ecfdf5; color: #059669; padding: 1rem; border-radius: 0.75rem; margin-bottom: 1.5rem; border: 1px solid #d1fae5;">
                {{ session('success') }}
            </div>
        @endif

        @yield('content')
    </div>
</div>
@stack('modals')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
    @yield('scripts')
</body>
</html>
