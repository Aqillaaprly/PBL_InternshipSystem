<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .chart-container {
            position: relative;
            margin: auto;
            /* Anda bisa mengatur tinggi dan lebar default di sini atau membiarkannya responsif */
            /* height: 300px; */
            /* width: 100%; */ /* Biarkan lebar responsif */
        }
        /* Style untuk kualifikasi list jika Dosen.job di-include dan membutuhkannya */
        .kualifikasi-list ul {
            list-style-type: disc;
            margin-left: 1.5rem;
            padding-left: 0;
        }
        .kualifikasi-list li {
            margin-bottom: 0.25rem;
        }
    </style>
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
            <h1 class="text-3xl font-bold text-blue-900">Selamat Datang, {{ Auth::user()->name ?? 'Admin' }}!</h1>
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
            <a href="{{ route('mahasiswa.perusahaan.index') }}">
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

            <a href="{{ route('mahasiswa.pendaftar') }}">
                <div class="bg-white p-6 rounded shadow text-center hover:bg-blue-50 transition cursor-pointer">
                    <p class="text-2xl font-bold text-blue-600">{{ $jumlahPendaftar ?? 0 }}</p>
                    <p class="text-sm text-gray-700 mt-1">Pendaftar</p>
                </div>
            </a>
        </div>

        <!-- Recommended Job Section -->
        @if($recommendedLowongan)
        <div class="bg-white rounded-xl shadow-md p-6 mt-6">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-xl font-bold text-blue-800">Rekomendasi Lowongan Untuk Anda</h2>
                <span class="px-3 py-1 bg-blue-100 text-blue-800 text-sm rounded-full">
                    <i class="fas fa-star mr-1"></i> Rekomendasi Sistem
                </span>
            </div>

            <div class="border border-blue-200 rounded-lg p-4">
                <h3 class="text-lg font-semibold">{{ $recommendedLowongan->judul }}</h3>
                <p class="text-sm text-gray-600">{{ $recommendedLowongan->company->nama_perusahaan }}</p>

                <div class="mt-3 grid grid-cols-2 gap-4 text-sm">
                    <div>
                        <p class="text-gray-500"><i class="fas fa-map-marker-alt mr-2"></i> Lokasi</p>
                        <p>{{ $recommendedLowongan->kota }}, {{ $recommendedLowongan->provinsi }}</p>
                    </div>
                    <div>
                        <p class="text-gray-500"><i class="fas fa-calendar-alt mr-2"></i> Batas Pendaftaran</p>
                        <p>{{ \Carbon\Carbon::parse($recommendedLowongan->tanggal_tutup)->format('d M Y') }}</p>
                    </div>
                </div>

                <div class="mt-4 flex justify-end">
                    <a href="{{ route('mahasiswa.lowongan.show', $recommendedLowongan->id) }}"
                       class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-md mr-2">
                        <i class="fas fa-eye mr-1"></i> Detail
                    </a>
                    <a href="{{ route('mahasiswa.job') }}" class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-md">
                        <i class="fas fa-list mr-1"></i> Lihat Semua Rekomendasi
                    </a>
                </div>
            </div>
        </div>
        @endif

        <!-- Job Cards Section -->
        {{-- Bagian ini akan di-include dari resources/views/dosen/Job.blade.php --}}
        {{-- Pastikan controller mengirimkan variabel $companies yang berisi Collection perusahaan --}}
        @if(view()->exists('mahasiswa.job'))
        @include('mahasiswa.job')
        @else
        <div class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-4 mb-6" role="alert">
            <p class="font-bold">Perhatian:</p>
            <p>View <code>mahasiswa.job</code> tidak ditemukan. Bagian rekomendasi lowongan tidak dapat ditampilkan.</p>
        </div>
        @endif

        <!-- Add this right after the Job Cards Section -->
        <div class="bg-white rounded-xl shadow-md p-6 mt-10">
            <h2 class="text-xl font-bold text-blue-800 mb-6">Dokumen Tambahan</h2>
            <div class="space-y-4">
                <div class="flex justify-between items-center p-4 bg-blue-50 rounded-lg hover:bg-blue-100 transition">
                    <span class="font-medium">Template Pakta Integritas Magang Pusat 2023</span>
                    <a href="https://drive.google.com/uc?export=download&id=1L5dW2IC8nY5lRI4Qiz7pZapQE5EoXnsS" class="text-blue-600 hover:text-blue-800 font-medium px-4 py-2 bg-white rounded-md shadow-sm transition">Download</a>
                </div>
                <div class="flex justify-between items-center p-4 bg-blue-50 rounded-lg hover:bg-blue-100 transition">
                    <span class="font-medium">Format Daftar Riwayat Hidup 2023</span>
                    <a href="https://drive.google.com/uc?export=download&id=1b5mgDlF_YbR9btr-eyLKPfWaY7tsXo4i" class="text-blue-600 hover:text-blue-800 font-medium px-4 py-2 bg-white rounded-md shadow-sm transition">Download</a>
                </div>
                <div class="flex justify-between items-center p-4 bg-blue-50 rounded-lg hover:bg-blue-100 transition">
                    <span class="font-medium">Template Izin Ortu 2023</span>
                    <a href="https://drive.google.com/uc?export=download&id=1gNeyZ2J1-xV_RFeCbB8bqnW3nf8-VNQf" class="text-blue-600 hover:text-blue-800 font-medium px-4 py-2 bg-white rounded-md shadow-sm transition">Download</a>
                </div>
            </div>
        </div>
    </main>
</div>

@include('mahasiswa.template.footer')
</body>
</html>
