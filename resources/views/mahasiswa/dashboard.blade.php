<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIMMAGANG Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

@include('mahasiswa.template.navbar')

<body class="bg-blue-50 text-gray-800 pt-20">

<!-- Hero Section -->
<div class="flex flex-col min-h-screen">
    <main class="max-w-[1300px] mx-auto w-full px-6 md:px-10 mt-4">
        <div class="w-full mb-6">
            <img src="https://www.pixelstalk.net/wp-content/uploads/2016/05/Images-New-York-City-Backgrounds.jpg"
                 alt="Header"
                 class="w-full h-48 object-cover rounded-b-lg shadow" />
        </div>
        <div class="text-center mb-10">
            <h1 class="text-3xl font-bold text-blue-900">Selamat Datang, {{ Auth::user()->username ?? 'Admin' }}!</h1>
            <p class="text-sm text-gray-600">Dashboard Sistem Informasi Manajemen Magang Mahasiswa</p>
        </div>

        <!-- Descriptions -->
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

        <!-- Statistics Section -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 mb-6">
            <a href="{{ route('mahasiswa.perusahaan') }}">
                <div class="bg-white p-6 rounded shadow text-center hover:bg-blue-50 transition cursor-pointer">
                    <p class="text-2xl font-bold text-blue-600">{{ $jumlahPerusahaan ?? 0 }}</p>
                    <p class="text-sm text-gray-700 mt-1">Perusahaan</p>
                </div>
            </a>

            <a href="{{ route('mahasiswa.lowongan.index') }}">
                <div class="bg-white p-6 rounded shadow text-center hover:bg-blue-50 transition cursor-pointer">
                    <p class="text-2xl font-bold text-blue-600">{{ $jumlahLowongan ?? 0 }}</p>
                    <p class="text-sm text-gray-700 mt-1">Lowongan</p>
                </div>
            </a>

            <a href="#" onclick="alert('Pendaftar route coming soon!')" class="block">
                <div class="bg-white p-6 rounded shadow text-center hover:bg-blue-50 transition cursor-pointer">
                    <p class="text-2xl font-bold text-blue-600">{{ $jumlahPendaftar ?? 0 }}</p>
                    <p class="text-sm text-gray-700 mt-1">Pendaftar</p>
                </div>
            </a>
        </div>

        <!-- Job Cards Section -->
        <div>
            @include('mahasiswa.job')
        </div>
    </main>
</div>

@include('mahasiswa.template.footer')
</body>
</html>
