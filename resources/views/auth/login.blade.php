<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login Simagang</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            background: linear-gradient(to right, #638ECB, #B1C9EF);
        }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center">

    <div class="bg-white p-8 rounded-2xl shadow-lg w-full max-w-md">
        <div class="text-center mb-6">
            <h1 class="text-3xl font-bold text-gray-800">STRIDEUP</h1>
            <p class="text-gray-500">SYSTEM MANAGEMENT INTERNSHIP</p>
        </div>

        <?php if (!empty($error)) : ?>
            <div class="bg-red-100 text-red-700 px-4 py-2 rounded mb-4 text-sm"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form class="space-y-4 md:space-y-5" method="POST" action="{{ route('log-in') }}">
            @csrf
            <div>
                <label class="block text-gray-600 mb-1">Username</label>
                <input type="text" name="username" id="username" value="{{ old('username') }}" required
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:outline-none" />
            </div>

            <div class="relative">
                <label class="block text-gray-600 mb-1">Password</label>
                <input type="password" name="password" id="password" required
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:outline-none" />
                <span id="togglePassword" class="absolute right-3 top-9 cursor-pointer text-xl">🙈</span>
            </div>

            <button type="submit"
                    class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-2 rounded-lg transition">
                Login
            </button>
        </form>
    </div>

    <script>
        const passwordInput = document.getElementById('password');
        const togglePassword = document.getElementById('togglePassword');

        togglePassword.addEventListener('click', () => {
            const type = passwordInput.type === 'password' ? 'text' : 'password';
            passwordInput.type = type;
            togglePassword.textContent = type === 'password' ? '🙈' : '🙉';
        });
    </script>

</body>
</html>
