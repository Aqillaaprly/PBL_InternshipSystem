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

    @include('dosen.template.navbar') 

    <main class="flex flex-col min-h-screen">
        <div class="p-6 max-w-7xl mx-auto w-full mt-16"> {{-- Tambahkan margin top jika navbar fixed --}}
            <div class="w-full mb-6">
                <img src="https://www.pixelstalk.net/wp-content/uploads/2016/05/Images-New-York-City-Backgrounds.jpg"
                     alt="Header"
                     class="w-full h-48 object-cover rounded-b-lg shadow" />
            </div>

             <div class="text-center mb-10">
                <h1 class="text-3xl font-bold text-blue-900">Selamat Datang, {{ Auth::user()->dosen->nama_dosen ?? Auth::user()->name ?? 'Bapak' }}!</h1>
                <p class="text-sm text-gray-600">Dashboard Sistem Informasi Manajemen Magang Mahasiswa</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-10">
                <div class="bg-white rounded-xl shadow-md p-6 hover:shadow-lg transition">
                    <h2 class="text-xl font-bold text-blue-800 mb-3">Magang Pusat</h2>
                    <p class="text-gray-700 mb-3">Magang Pusat adalah proses untuk menerapkan keilmuan atau kompetensi yang didapat selama menjalani masa pendidikan, di dunia kerja secara langsung. Pemagang jadi bisa memahami sistem kerja yang profesional di industri sebenarnya.</p>
                    <p class="text-gray-700">Perusahaan-perusahaan yang akan di jadikan tempat magang sudah terorganisir oleh kampus</p>
                </div>
                <div class="bg-white rounded-xl shadow-md p-6 hover:shadow-lg transition">
                    <h2 class="text-xl font-bold text-blue-800 mb-3">Magang Mandiri</h2>
                    <p class="text-gray-700 mb-3">Magang Mandiri adalah proses untuk menerapkan keilmuan atau kompetensi yang didapat selama menjalani masa pendidikan, di dunia kerja secara langsung. Pemagang jadi bisa memahami sistem kerja yang profesional di industri sebenarnya.</p>
                    <p class="text-gray-700">Mahasiswa mencari sendiri perusahaan-perusahan yang akan dijadikan untuk penerapan keilmuan atau kompetensi.</p>
                </div>
            </div>

        {{-- <!-- Statistik -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 mb-6">
            <div class="bg-white p-6 rounded shadow text-center hover:bg-blue-50 transition">
                <p class="text-2xl font-bold text-blue-600"><?= $jumlahUserMahasiswaYangAda ?></p>
                <p class="text-sm text-gray-700 mt-1">Total Mahasiswa </p>
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
    </main> --}} 

    {{-- <?php include('template/footer.php'); ?> --}}

</body>
</html>
