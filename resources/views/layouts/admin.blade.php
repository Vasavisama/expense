<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Panel')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            display: flex;
            min-height: 100vh;
            flex-direction: column;
        }
        .main-container {
            display: flex;
            flex: 1;
        }
        .sidebar {
            width: 250px;
            background: #343a40;
            color: white;
        }
        .sidebar a {
            color: white;
            text-decoration: none;
            display: block;
            padding: 15px;
        }
        .sidebar a:hover {
            background: #495057;
        }
        .sidebar a.active {
            background: #0d6efd;
        }
        .content {
            flex: 1;
            padding: 20px;
        }
    </style>
</head>
<body>
    <div class="main-container">
        <div class="sidebar">
            <h4 class="text-center p-3">Admin Panel</h4>
            <nav class="nav flex-column">
                <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">Dashboard</a>
                <a class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}" href="{{ route('admin.users.index') }}">User Management</a>
                <a class="nav-link {{ request()->routeIs('admin.expenses.*') ? 'active' : '' }}" href="{{ route('admin.expenses.index') }}">Expense Management</a>
            </nav>
        </div>
        <div class="content">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2>@yield('title')</h2>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="btn btn-secondary">Logout</button>
                </form>
            </div>
            @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            @endif
            @yield('content')
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>