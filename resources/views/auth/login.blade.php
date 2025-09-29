<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- FontAwesome -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/js/all.min.js" crossorigin="anonymous"></script>
</head>

<body class="min-h-screen flex items-center justify-center bg-gradient-to-br from-purple-500 via-pink-500 to-red-500">
    <div class="bg-white p-8 rounded-2xl shadow-2xl w-full max-w-md">
        <h1 class="text-3xl font-bold text-center text-gray-800 mb-6">
            <i class="fas fa-user-circle mr-2"></i> Login
        </h1>

        @if(session('success'))
        <div
            class="p-3 mb-4 rounded-lg bg-teal-50 text-teal-900 border border-teal-200">
            {{ session('success') }}
        </div>
        @endif

        @if(session('error'))
        <div
            class="p-3 mb-4 rounded-lg bg-red-100 text-red-700 border border-red-300">
            {{ session('error') }}
        </div>
        @endif

        @if($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <form id="login-form" method="POST" action="{{ route('login') }}" class="space-y-4">
            @csrf
            <div>
                <input type="email" id="email" name="email" placeholder="Enter your email"
                    value="{{ old('email') }}"
                    class="w-full p-3 border-2 border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-300">
            </div>

            <div>
                <input type="password" id="password" name="password" placeholder="Enter your password"
                    class="w-full p-3 border-2 border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-300">
            </div>

            <button type="submit" id="login-button"
                class="w-full bg-gradient-to-r from-pink-500 via-purple-500 to-red-500 text-white font-bold py-3 rounded-lg hover:opacity-90 transition">
                <i class="fas fa-sign-in-alt mr-2"></i> Login
            </button>
        </form>

        <div class="text-center text-gray-500 mt-4 space-y-2">
            <p>Don't have an account?
                <a href="/register" class="text-pink-600 font-semibold hover:underline">Register here</a>
            </p>
            <p>Forgot your password?
                <a href="/reset-password" class="text-pink-600 font-semibold hover:underline">Reset it here</a>
            </p>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            $('#login-form').on('submit', function(event) {
                $('.error-msg').remove();
                $('input').removeClass('border-red-500');

                let hasError = false;
                const email = $('#email').val();
                const password = $('#password').val();

                if (!email) {
                    $('#email').after('<p class="error-msg text-red-500 text-sm mt-1">Email is required</p>');
                    $('#email').addClass('border-red-500');
                    hasError = true;
                } else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
                    $('#email').after('<p class="error-msg text-red-500 text-sm mt-1">Invalid email format</p>');
                    $('#email').addClass('border-red-500');
                    hasError = true;
                }

                if (!password) {
                    $('#password').after('<p class="error-msg text-red-500 text-sm mt-1">Password is required</p>');
                    $('#password').addClass('border-red-500');
                    hasError = true;
                }

                if (hasError) {
                    event.preventDefault();
                    return;
                }

                $('#login-button').html('<i class="fas fa-spinner fa-spin mr-2"></i> Logging in...')
                                  .prop('disabled', true);
            });
        });
    </script>
</body>
</html>
