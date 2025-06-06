<?php
// session_start();
// if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'perusahaan') {
//     header('Location: ../index.php');
//     exit;
// }

// require '../koneksi.php';

// // Contoh query, sesuaikan dengan tabel Anda
// $jumlahLowongan   = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM lowongan WHERE perusahaan_id = 1"))['total'];
// $jumlahPendaftar  = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM pendaftaran WHERE perusahaan_id = 1"))['total'];
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Perusahaan - SIMMAGANG</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-blue-50 text-gray-800">

    <?php include('template/navbar_perusahaan.php'); ?>

    <main class="p-6 max-w-7xl mx-auto">
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-blue-900">Selamat Datang, Perusahaan</h1>
            <p class="text-sm text-gray-600">Dashboard Perusahaan | SIMMAGANG</p>
        </div>

        <!-- Statistik -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 mb-6">
            <div class="bg-white p-6 rounded shadow text-center hover:bg-blue-50 transition">
                <p class="text-2xl font-bold text-blue-600"><?= $jumlahLowongan ?></p>
                <p class="text-sm text-gray-700 mt-1">Total Lowongan</p>
            </div>
            <div class="bg-white p-6 rounded shadow text-center hover:bg-blue-50 transition">
                <p class="text-2xl font-bold text-blue-600"><?= $jumlahPendaftar ?></p>
                <p class="text-sm text-gray-700 mt-1">Total Pendaftar</p>
            </div>
        </div>

        <!-- Tombol Aksi -->
        <div class="flex justify-end mb-4">
            <a href="tambah_lowongan.php" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">+ Tambah Lowongan</a>
        </div>

        <!-- Tabel Lowongan -->
        <div class="bg-white p-6 rounded shadow mb-6">
            <h2 class="font-semibold text-gray-700 mb-4">Lowongan Anda</h2>
            <table class="w-full text-sm text-left text-gray-600">
                <thead class="text-xs text-gray-500 uppercase border-b">
                    <tr>
                        <th class="px-4 py-2">Judul</th>
                        <th class="px-4 py-2">Deadline</th>
                        <th class="px-4 py-2">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $query = mysqli_query($conn, "SELECT * FROM lowongan WHERE perusahaan_id = 1");
                    while ($row = mysqli_fetch_assoc($query)) {
                        echo "<tr class='border-b'>
                            <td class='px-4 py-2'>{$row['judul']}</td>
                            <td class='px-4 py-2'>{$row['batas_akhir']}</td>
                            <td class='px-4 py-2'>
                                <a href='pendaftar.php?id={$row['id']}' class='text-blue-600 hover:underline'>Lihat Pendaftar</a>
                            </td>
                        </tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </main>

    <?php include('template/footer.php'); ?>

</body>
</html>
