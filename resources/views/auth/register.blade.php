<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- FontAwesome -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/js/all.min.js" crossorigin="anonymous"></script>
</head>
<body class="min-h-screen flex items-center justify-center bg-gradient-to-br from-purple-500 via-pink-500 to-red-500">
    <div class="bg-white p-8 rounded-2xl shadow-2xl w-full max-w-md">
        <h1 class="text-3xl font-bold text-center text-gray-800 mb-6">Register</h1>

        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                {{ session('success') }}
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

        <form id="register-form" method="POST" action="/register" class="space-y-4">
            @csrf
            <div>
                <input type="text" id="name" name="name" placeholder="Enter your name" value="{{ old('name') }}"
                       class="w-full p-3 border-2 border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-300">
            </div>

            <div>
                <input type="email" id="email" name="email" placeholder="Enter your email" value="{{ old('email') }}"
                       class="w-full p-3 border-2 border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-300">
            </div>

            <div>
                <input type="password" id="password" name="password" placeholder="Enter your password"
                       class="w-full p-3 border-2 border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-300">
            </div>

            <div>
                <input type="password" id="password_confirmation" name="password_confirmation" placeholder="Confirm your password"
                       class="w-full p-3 border-2 border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-yellow-300">
            </div>

            <div>
                <select id="role" name="role"
                        class="w-full p-3 border-2 border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-300">
                    <option value="" disabled {{ old('role') ? '' : 'selected' }}>Select Role</option>
                    <option value="user" {{ old('role') == 'user' ? 'selected' : '' }}>User</option>
                    <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                </select>
            </div>

            <button type="submit" id="register-button"
                    class="w-full bg-gradient-to-r from-pink-500 via-purple-500 to-red-500 text-white font-bold py-3 rounded-lg hover:opacity-90 transition">
                <i class="fas fa-user-plus mr-2"></i> Register
            </button>
        </form>

        <p class="text-center text-gray-500 mt-4">
            Already have an account?
            <a href="{{ route('login') }}" class="text-pink-600 font-semibold hover:underline">Login</a>
        </p>
    </div>

    <script>
        $(document).ready(function() {
            $('#register-form').on('submit', function(event) {
                // Remove old error messages and reset borders
                $('.error-msg').remove();
                $('input, select').removeClass('border-red-500');

                let hasError = false;

                const name = $('#name').val();
                const email = $('#email').val();
                const password = $('#password').val();
                const password_confirmation = $('#password_confirmation').val();
                const role = $('#role').val();

                if (!name) {
                    $('#name').after('<p class="error-msg text-red-500 text-sm mt-1">Name is required</p>');
                    $('#name').addClass('border-red-500');
                    hasError = true;
                }

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
                } else if (password.length < 8) {
                    $('#password').after('<p class="error-msg text-red-500 text-sm mt-1">Password must be at least 8 characters</p>');
                    $('#password').addClass('border-red-500');
                    hasError = true;
                }

                if (!password_confirmation) {
                    $('#password_confirmation').after('<p class="error-msg text-red-500 text-sm mt-1">Confirm password is required</p>');
                    $('#password_confirmation').addClass('border-red-500');
                    hasError = true;
                } else if (password !== password_confirmation) {
                    $('#password_confirmation').after('<p class="error-msg text-red-500 text-sm mt-1">Passwords do not match</p>');
                    $('#password_confirmation').addClass('border-red-500');
                    hasError = true;
                }

                if (!role) {
                    $('#role').after('<p class="error-msg text-red-500 text-sm mt-1">Role is required</p>');
                    $('#role').addClass('border-red-500');
                    hasError = true;
                }

                if (hasError) {
                    event.preventDefault();
                    return;
                }

                // Update button to show loading state
                $('#register-button').html('<i class="fas fa-spinner fa-spin mr-2"></i> Registering...').prop('disabled', true);
            });
        });
    </script>
</body>
</html>
