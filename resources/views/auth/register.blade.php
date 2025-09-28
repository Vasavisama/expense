<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

    <!-- FontAwesome -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/js/all.min.js" crossorigin="anonymous"></script>

    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background: linear-gradient(135deg, #667eea, #764ba2);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            margin: 0;
            padding: 20px;
        }

        .register-card {
            background: #fff;
            padding: 40px 30px;
            border-radius: 15px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.15);
            width: 100%;
            max-width: 400px;
            box-sizing: border-box;
        }

        .register-card h1 {
            text-align: center;
            margin-bottom: 25px;
            font-weight: 600;
            color: #333;
            word-wrap: break-word;
        }

        .form-group label {
            font-weight: 500;
            color: #555;
        }

        /* General form control */
        .form-control {
            border-radius: 8px;
            transition: all 0.3s;
        }

        /* Inputs + textarea */
        input.form-control,
        textarea.form-control {
            padding: 12px 15px;
        }

        /* Select dropdown fix */
        select.form-control {
            padding: 8px 12px;
            line-height: 1.5;
            height: auto; /* fixes text cut */
        }

        .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 5px rgba(102, 126, 234, 0.5);
        }

        .btn-register {
            width: 100%;
            padding: 12px;
            background: #667eea;
            color: #fff;
            font-weight: 500;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s;
        }

        .btn-register:hover {
            background: #556cd6;
            transform: translateY(-2px);
        }

        .alert-success, .alert-error {
            border-radius: 5px;
            padding: 10px;
            margin-bottom: 15px;
            word-wrap: break-word;
        }

        .alert-success {
            background: #d4edda;
            border: 1px solid #c3e6cb;
            color: #155724;
        }

        .alert-error {
            background: #f8d7da;
            border: 1px solid #f5c6cb;
            color: #721c24;
        }

        /* Make the form scrollable on very small screens */
        @media (max-height: 600px) {
            body {
                align-items: flex-start;
                padding-top: 20px;
                padding-bottom: 20px;
            }
        }
    </style>
</head>
<body>
    <div class="register-card">
        <h1>Register</h1>

        @if(session('success'))
            <div class="alert-success">{{ session('success') }}</div>
        @endif

        @if($errors->any())
            <div class="alert-error">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="/register">
            @csrf

            <div class="form-group">
                <label for="name">Name</label>
                <input type="text" class="form-control" name="name" id="name" placeholder="Enter your name" value="{{ old('name') }}" required>
            </div>

            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" class="form-control" name="email" id="email" placeholder="Enter your email" value="{{ old('email') }}" required>
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" class="form-control" name="password" id="password" placeholder="Enter your password" required>
            </div>

            <div class="form-group">
                <label for="password_confirmation">Confirm Password</label>
                <input type="password" class="form-control" name="password_confirmation" id="password_confirmation" placeholder="Confirm your password" required>
            </div>

            <div class="form-group">
                <label for="role">Role</label>
                <select class="form-control" name="role" id="role">
                    <option value="user" {{ old('role') == 'user' ? 'selected' : '' }}>User</option>
                    <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                </select>
            </div>

            <button type="submit" class="btn-register"><i class="fas fa-user-plus mr-2"></i> Register</button>
        </form>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
</body>
</html>
