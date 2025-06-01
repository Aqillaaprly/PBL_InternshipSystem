<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Perusahaan - SIMMAGANG</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-blue-50 text-gray-800">
    {{-- Pastikan path include navbar ini benar --}}
    @include('perusahaan.template.navbar') 

    <main class="flex flex-col min-h-screen">
        <div class="p-6 max-w-7xl mx-auto w-full mt-16"> {{-- Tambahkan margin top jika navbar fixed --}}
            <div class="w-full mb-6">
                <img src="https://www.pixelstalk.net/wp-content/uploads/2016/05/Images-New-York-City-Backgrounds.jpg"
                     alt="Header"
                     class="w-full h-48 object-cover rounded-b-lg shadow" />
            </div>

            <div class="text-center mb-10">
                <h1 class="text-3xl font-bold text-blue-900">Selamat Datang, {{ Auth::user()->company->nama_perusahaan ?? Auth::user()->username ?? 'Perusahaan' }}!</h1>
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

            {{-- Statistik --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6 mb-6"> {{-- Mengubah sm:grid-cols-3 menjadi sm:grid-cols-2 karena hanya ada 2 statistik --}}
                <a href="{{ route('perusahaan.lowongan') }}" class="cursor-pointer block">
                    <div class="bg-white p-6 rounded shadow text-center hover:bg-blue-50 transition">
                        {{-- Menggunakan nama variabel yang dikirim dari controller --}}
                        <p class="text-2xl font-bold text-blue-600">{{ $jumlahLowonganAktif ?? 0 }}</p>
                        <p class="text-sm text-gray-700 mt-1">Lowongan Aktif</p>
                    </div>
                </a>
                <a href="{{ route('perusahaan.pendaftar') }}" class="cursor-pointer block">
                    <div class="bg-white p-6 rounded shadow text-center hover:bg-blue-50 transition">
                        {{-- Menggunakan nama variabel yang dikirim dari controller --}}
                        <p class="text-2xl font-bold text-blue-600">{{ $jumlahTotalPendaftar ?? 0 }}</p>
                        <p class="text-sm text-gray-700 mt-1">Total Pendaftar</p>
                    </div>
                </a>
            </div>
        </div>
    </main>
    {{-- Pastikan path include footer ini benar --}}
    @include('perusahaan.template.footer') 
</body>
</html>