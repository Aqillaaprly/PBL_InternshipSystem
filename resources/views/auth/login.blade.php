<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Simagang</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<style>
        body {
            background: linear-gradient(to right, #638ECB, #B1C9EF);
        }
    </style>
<body class="min-h-screen flex items-center justify-center">

    <div class="bg-white p-8 rounded-2xl shadow-lg w-full max-w-md">
        <div class="text-center mb-6">
            <h1 class="text-3xl font-bold text-gray-800">STRIDEUP</h1>
            <p class="text-gray-500">SYSTEM MANAGEMENT INTERSHIP</p>
        </div>

        <?php if (!empty($error)) : ?>
            <div class="bg-red-100 text-red-700 px-4 py-2 rounded mb-4 text-sm"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

       <form class="space-y-4 md:space-y-5" method="POST" action="{{ url('/login') }}">
            <div>
                        <label class="block text-gray-600 mb-1">Username</label>
                        <input type="text" name="username" id="username" value="{{ old('username') }}" required
                         class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:outline-none" />
                    </div>

            <div>
                <label class="block text-gray-600 mb-1">Password</label>
                <input type="password" name="password" required
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:outline-none" />
            </div>

            <button type="submit"
                    class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-2 rounded-lg transition">
                Login
            </button>
        </form>
    </div>

</body>
</html>
