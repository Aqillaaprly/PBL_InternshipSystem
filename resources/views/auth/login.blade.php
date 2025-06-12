<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login Simagang</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        /* Menggunakan font Inter untuk konsistensi */
        body {
            font-family: 'Inter', sans-serif;
            background-color: #414b97;   /* Warna biru gelap untuk container utama */
        }
        .login-container {
            background-color: #334dac77; /* Warna latar belakang biru muda dari gambar */
            background-image: url('data:image/svg+xml,%3Csvg width="100%25" height="100%25" viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg"%3E%3CradialGradient id="g1" cx="50%25" cy="50%25" r="50%25"%3E%3Cstop offset="0%25" stop-color="%232C355E" /%3E%3Cstop offset="100%25" stop-color="%231A1F3C" /%3E%3C/radialGradient%3E%3Ccircle cx="70" cy="30" r="40" fill="url(%23g1)" opacity="0.6" /%3E%3Ccircle cx="30" cy="70" r="30" fill="url(%23g1)" opacity="0.4" /%3E%3C/svg%3E'); /* Efek gradien lingkaran */
            background-size: cover;
            background-position: center;
        }
        .input-field {
            background-color: white; /* Warna latar belakang input diubah menjadi putih */
            border: 1px solid #D1D5DB; /* Warna border input disesuaikan agar terlihat di latar putih */
            color: #1F2937; /* Warna teks input diubah menjadi lebih gelap agar kontras dengan latar putih */
        }
        .input-field::placeholder {
            color: #6B7280; /* Warna placeholder disesuaikan agar terlihat di latar putih */
        }
        .login-button {
             background-image: linear-gradient(to right, #4f46e5, #7c3aed);
        }
        .login-button:hover {
            background-image: linear-gradient(to right, #4338ca, #6d28d9);
        }
        .link-text {
            color: #4C6EF5; /* Warna teks link */
        }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center">

   <div class="login-container relative w-full max-w-4xl h-[500px] rounded-3xl shadow-2xl flex overflow-hidden">
        <div class="w-1/2 p-12 flex flex-col justify-center items-start text-white">
            <h1 class="text-4xl font-bold mb-4">Hello <br> Striders!<span class="text-6xl inline-block origin-bottom-right animate-wave">👋🏻</span></h1>
            <p class="text-lg opacity-80">Progress starts with a single stride!</p>
            <p class="text-xs absolute bottom-12 left-12 opacity-60">&copy; 2025 StrideUp.</p>
        </div>

        <div class="w-1/2 bg-white p-12 rounded-l-3xl shadow-lg flex flex-col justify-center items-center">
            <div class="w-full max-w-sm">
                <div class="text-center mb-8">
                    {{-- Logo 'S' yang dibuat menggunakan SVG inline --}}
                    <svg class="mx-auto mb-2 w-16 h-16" viewBox="0 0 100 100" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <circle cx="50" cy="50" r="45" stroke="#4C6EF5" stroke-width="8"/>
                        <path d="M68 25C68 22 65 20 62 20H38C35 20 32 22 32 25V38C32 41 35 43 38 43H62C65 43 68 45 68 48V75C68 78 65 80 62 80H38C35 80 32 78 32 75V62C32 59 35 57 38 57H62C65 57 68 55 68 52V25Z" fill="#4C6EF5"/>
                    </svg>
                    <h2 class="text-3xl font-bold text-gray-800">STRIDEUP</h2>
                </div>
                
        <?php if (!empty($error)) : ?>
            <div class="bg-red-100 text-red-700 px-4 py-2 rounded mb-4 text-sm"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form class="space-y-4 md:space-y-5" method="POST" action="{{ route('log-in') }}">
            @csrf
            <div>
                <label class="sr-only"></label>
                <input type="text" name="username" id="username" value="{{ old('username') }}" placeholder="2341720207" required
                       class="input-field w-full px-4 py-3 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none" />
            </div>

            <div class="relative">
                <label class="sr-only">Password</label>
                <input type="password" name="password" id="password" placeholder="Your password" required
                       class="input-field w-full px-4 py-3 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none"/>
                <span id="togglePassword" class="absolute right-3 top-1/2 transform -translate-y-1/2 cursor-pointer text-xl">🙈</span>
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

        if (passwordInput && togglePassword) {
            togglePassword.addEventListener('click', () => {
                const type = passwordInput.type === 'password' ? 'text' : 'password';
                passwordInput.type = type;
                // Mengubah emoji berdasarkan status terlihat atau tersembunyi
                togglePassword.textContent = type === 'password' ? '🙈' : '🙉';
            });
        }
    </script>

</body>
</html>
