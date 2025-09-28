<!DOCTYPE html>
<html>
<head>
    <title>Register</title>
</head>
<body>
    <h1>Register</h1>

    @if(session('success'))
        <p style="color:green;">{{ session('success') }}</p>
    @endif

    @if($errors->any())
        <ul style="color:red;">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    @endif

    <form method="POST" action="/register">
        @csrf

        <input type="text" name="name" placeholder="Name" value="{{ old('name') }}" required><br><br>

        <input type="email" name="email" placeholder="Email" value="{{ old('email') }}" required><br><br>

        <input type="password" name="password" placeholder="Password" required><br><br>

        <input type="password" name="password_confirmation" placeholder="Confirm Password" required><br><br>

        <!-- Role Selection -->
        <label for="role">Role:</label>
        <select name="role" id="role">
            <option value="user" {{ old('role') == 'user' ? 'selected' : '' }}>User</option>
            <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
        </select>
        <br><br>

        <button type="submit">Register</button>
    </form>
</body>
</html>
