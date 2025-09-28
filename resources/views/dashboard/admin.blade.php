<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <title>Admin Dashboard</title>

    <!-- Google Fonts for modern look -->
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

    <!-- FontAwesome -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/js/all.min.js" crossorigin="anonymous"></script>

    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #f4f6f9;
        }

        /* Sidebar */
        .sb-sidenav-dark {
            background: linear-gradient(180deg, #4b6cb7 0%, #182848 100%);
            color: #fff;
        }
        .sb-sidenav a.nav-link {
            color: #fff;
            transition: all 0.3s ease;
        }
        .sb-sidenav a.nav-link:hover {
            background-color: rgba(255,255,255,0.1);
            border-radius: 5px;
        }
        .sb-sidenav .sb-sidenav-menu-heading {
            color: #c1c1c1;
            font-size: 0.85rem;
            text-transform: uppercase;
        }
        .sb-sidenav-footer {
            background-color: #1b2a47;
            color: #fff;
            padding: 15px;
            font-size: 0.85rem;
        }

        /* Navbar */
        .sb-topnav {
            background: linear-gradient(90deg, #4b6cb7 0%, #182848 100%);
        }
        .sb-topnav .navbar-brand {
            font-weight: 700;
        }
        .sb-topnav .btn-link {
            color: #fff !important;
        }

        /* Cards */
        .dashboard-card {
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 8px 20px rgba(0,0,0,0.1);
            transition: transform 0.3s, box-shadow 0.3s;
        }
        .dashboard-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 25px rgba(0,0,0,0.15);
        }

        /* Footer */
        footer {
            background-color: #fff;
            box-shadow: 0 -2px 5px rgba(0,0,0,0.05);
        }
    </style>
</head>
<body class="sb-nav-fixed">
    <!-- Navbar -->
    <nav class="sb-topnav navbar navbar-expand navbar-dark">
        <a class="navbar-brand" href="{{ route('admin.dashboard') }}">Admin Panel</a>
        <ul class="navbar-nav ml-auto">
            <li class="nav-item">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="btn btn-link nav-link">Logout</button>
                </form>
            </li>
        </ul>
    </nav>

    <div id="layoutSidenav">
        <!-- Sidebar -->
        <div id="layoutSidenav_nav">
            <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
                <div class="sb-sidenav-menu">
                    <div class="nav">


                        <a class="nav-link" href="{{ route('users.index') }}">
                            <div class="sb-nav-link-icon"><i class="fas fa-users"></i></div>
                            User Management
                        </a>
                        <a class="nav-link" href="{{ route('analytics.index') }}">
                            <div class="sb-nav-link-icon"><i class="fas fa-chart-area"></i></div>
                            Analytics
                        </a>
                    </div>
                </div>
                <div class="sb-sidenav-footer">
                    <div class="small">Logged in as:</div>
                    {{ Auth::user()->name }}
                </div>
            </nav>
        </div>

        <!-- Content -->
        <div id="layoutSidenav_content">
            <main class="p-4">
                <!-- Example Dashboard Cards -->
               
                @yield('content')
            </main>

            <!-- Footer -->
            <footer class="py-4 mt-auto">
                <div class="container-fluid">
                    <div class="d-flex align-items-center justify-content-between small">
                        <div class="text-muted">Copyright &copy; Your Website 2024</div>
                    </div>
                </div>
            </footer>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
</body>
</html>
