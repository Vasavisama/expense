<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- FontAwesome -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/js/all.min.js" crossorigin="anonymous"></script>
</head>
<body class="min-h-screen flex items-center justify-center bg-gradient-to-br from-purple-500 via-pink-500 to-red-500">
    <div class="bg-white p-8 rounded-2xl shadow-2xl w-full max-w-md">
        <!-- Title -->
        <h1 class="text-3xl font-bold text-center text-gray-800 mb-6">
            <i class="fas fa-user-plus mr-2"></i> Register
        </h1>

        <!-- Success Message -->
        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        <!-- Validation Errors -->
        @if($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                <ul class="mb-0 space-y-1">
                    @foreach($errors->all() as $error)
                        <li>• {{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Register Form -->
        <form method="POST" action="{{ route('register') }}" class="space-y-4">
            @csrf

            <!-- Name -->
            <div>
                <input type="text" id="name" name="name" placeholder="Enter your name"
                       value="{{ old('name') }}"
                       class="w-full p-3 border-2 @error('name') border-red-500 @else border-gray-200 @enderror rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-300">
                @error('name')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Email -->
            <div>
                <input type="email" id="email" name="email" placeholder="Enter your email"
                       value="{{ old('email') }}"
                       class="w-full p-3 border-2 @error('email') border-red-500 @else border-gray-200 @enderror rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-300">
                @error('email')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Password -->
            <div>
                <input type="password" id="password" name="password" placeholder="Enter your password"
                       class="w-full p-3 border-2 @error('password') border-red-500 @else border-gray-200 @enderror rounded-lg focus:outline-none focus:ring-2 focus:ring-green-300">
                @error('password')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Confirm Password -->
            <div>
                <input type="password" id="password_confirmation" name="password_confirmation" placeholder="Confirm your password"
                       class="w-full p-3 border-2 border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-yellow-300">
            </div>

            <!-- Role -->
            <div>
                <select id="role" name="role"
                        class="w-full p-3 border-2 @error('role') border-red-500 @else border-gray-200 @enderror rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-300">
                    <option value="" disabled {{ old('role') ? '' : 'selected' }}>Select Role</option>
                    <option value="user" {{ old('role') == 'user' ? 'selected' : '' }}>User</option>
                    <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                </select>
                @error('role')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Submit -->
            <button type="submit"
                    class="w-full bg-gradient-to-r from-pink-500 via-purple-500 to-red-500 text-white font-bold py-3 rounded-lg hover:opacity-90 transition">
                <i class="fas fa-user-plus mr-2"></i> Register
            </button>
        </form>

        <!-- Login Link -->
        <p class="text-center text-gray-500 mt-4">
            Already have an account?
            <a href="{{ route('login') }}" class="text-pink-600 font-semibold hover:underline">Login</a>
        </p>
    </div>
</body>
</html>
