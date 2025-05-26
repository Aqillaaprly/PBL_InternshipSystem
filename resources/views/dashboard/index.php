<?php
session_start();
require 'koneksi.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user'] = [
            'id'    => $user['id'],
            'email' => $user['email'],
            'name'  => $user['name'],
            'role'  => $user['role'],
        ];

        // Redirect berdasarkan role
        switch ($user['role']) {
            case 'admin':
                header('Location: dashboard/admin.php');
                break;
            case 'dosen':
                header('Location: dashboard/dosen.php');
                break;
            case 'perusahaan':
                header('Location: dashboard/perusahaan.php');
                break;
            default:
                header('Location: dashboard/mahasiswa.php');
        }
    } else {
        $error = "Email atau password salah!";
    }
}
?>

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

        <form method="POST" class="space-y-5">
            <div>
                <label class="block text-gray-600 mb-1">Email</label>
                <input type="email" name="email" required
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
