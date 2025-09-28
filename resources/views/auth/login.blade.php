<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

    <!-- FontAwesome -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/js/all.min.js" crossorigin="anonymous"></script>

    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #6a11cb, #2575fc);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            margin: 0;
            padding: 20px;
        }

        .login-card {
            background: #fff;
            padding: 40px 30px;
            border-radius: 20px;
            box-shadow: 0 12px 30px rgba(0,0,0,0.2);
            width: 100%;
            max-width: 380px;
            text-align: center;
            animation: fadeIn 1s ease-in-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .login-card h1 {
            font-weight: 600;
            margin-bottom: 25px;
            color: #333;
        }

        .form-control {
            border-radius: 10px;
            padding: 12px 15px;
            font-size: 15px;
            margin-bottom: 20px;
            transition: 0.3s;
        }

        .form-control:focus {
            border-color: #2575fc;
            box-shadow: 0 0 6px rgba(37,117,252,0.4);
        }

        .btn-login {
            width: 100%;
            padding: 12px;
            background: linear-gradient(to right, #6a11cb, #2575fc);
            color: #fff;
            font-weight: 500;
            border: none;
            border-radius: 10px;
            cursor: pointer;
            transition: 0.3s;
        }

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 18px rgba(37,117,252,0.4);
        }

        .alert-error {
            background: #f8d7da;
            border: 1px solid #f5c6cb;
            color: #721c24;
            padding: 12px;
            border-radius: 8px;
            margin-bottom: 20px;
            text-align: left;
            font-size: 14px;
        }

        .login-footer {
            margin-top: 15px;
            font-size: 14px;
            color: #555;
        }

        .login-footer a {
            color: #2575fc;
            text-decoration: none;
            font-weight: 500;
        }

        .login-footer a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="login-card">
        <h1><i class="fas fa-user-circle"></i> Login</h1>

        @if($errors->any())
            <div class="alert-error">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="/login">
            @csrf
            <input type="email" name="email" class="form-control" placeholder="Email" required>
            <input type="password" name="password" class="form-control" placeholder="Password" required>
            <button type="submit" class="btn-login"><i class="fas fa-sign-in-alt mr-2"></i> Login</button>
        </form>

        <div class="login-footer">
            <p>Don't have an account? <a href="/register">Register here</a></p>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
</body>
</html>
