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
    <title>Dashboard Pembimbing - STRIDEUP</title>
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

            <div class="grid grid-cols-1 lg:grid-cols-1">
                <div class="bg-white p-6 rounded-xl shadow hover:bg-blue-50 transition">
                    <h2 class="font-semibold text-gray-700 mb-4">Statistik Program Studi</h2>
                    {{-- Konten statistik prodi --}}
                </div>
            </div>
        @include('dosen.Job')

{{--Tabel mahasiswa bimbingan--}}
      <div class="bg-white p-6 rounded-xl shadow mb-6 hover:bg-blue-50 transition">
                <h2 class="font-semibold text-gray-700 mb-4">Mahasiswa</h2>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left text-gray-600">
                        <thead class="text-xs text-gray-500 uppercase border-b">
                            <tr>
                                <th class="px-4 py-2">No</th>
                                <th class="px-4 py-2">Nama</th>
                                <th class="px-4 py-2">Perusahaan</th>
                                <th class="px-4 py-2">Posisi</th>
                                <th class="px-4 py-2">Tanggal Masuk Magang</th>
                                <th class="px-4 py-2">Tanggal Selesai Magang</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="border-b">
                                <td class="px-4 py-2">1</td>
                                <td class="px-4 py-2">Andi Pratama</td>
                                <td class="px-4 py-2">Astra</td>
                                <td class="px-4 py-2">Web Dev</td>
                                <td class="px-4 py-2">12 Mei 2025</td>
                                <td class="px-4 py-2">12 November 2025</td>
                            </tr>
                            {{-- Data lainnya --}}
                        </tbody>
                    </table>
                </div>
            </div>

{{--Tabel Absensi mahasiswa bimbingan--}}
      <div class="bg-white p-6 rounded-xl shadow mb-6 hover:bg-blue-50 transition">
                <h2 class="font-semibold text-gray-700 mb-4">Absensi Mahasiswa</h2>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left text-gray-600">
                        <thead class="text-xs text-gray-500 uppercase border-b">
                            <tr>
                                <th class="px-4 py-2">No</th>
                                <th class="px-4 py-2">Nama</th>
                                <th class="px-4 py-2">Perusahaan</th>
                                <th class="px-4 py-2">Jam Masuk</th>
                                <th class="px-4 py-2">Keterangan</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="border-b">
                                <td class="px-4 py-2">1</td>
                                <td class="px-4 py-2">Andi Pratama</td>
                                <td class="px-4 py-2">Astra</td>
                                <td class="px-4 py-2">08.55</td>
                                <td class="px-4 py-2">Hadir</td>
                            </tr>
                            {{-- Data lainnya --}}
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
        @include('dosen.template.footer')
        </body>
</html>