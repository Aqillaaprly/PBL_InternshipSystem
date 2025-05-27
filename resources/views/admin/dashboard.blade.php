<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Dashboard SIMMAGANG - Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    {{-- Jika Anda menggunakan Vite untuk CSS, uncomment baris di bawah dan pastikan path benar --}}
    {{-- @vite(['resources/css/app.css', 'resources/js/app.js']) --}}
    {{-- Jika style.css spesifik untuk admin dan ada di public/css/admin_style.css --}}
    {{-- <link rel="stylesheet" href="{{ asset('css/admin_style.css') }}"> --}}
</head>

<body class="bg-blue-50 text-gray-800">
    {{-- Pastikan path ini benar: resources/views/admin/template/navbar.blade.php --}}
    @include('admin.template.navbar')

    <div class="flex flex-col min-h-screen">
        <main class="p-6 max-w-7xl mx-auto w-full mt-16"> {{-- Tambahkan margin top jika navbar fixed --}}
            <div class="w-full mb-6">
                <img src="https://www.pixelstalk.net/wp-content/uploads/2016/05/Images-New-York-City-Backgrounds.jpg"
                     alt="Header"
                     class="w-full h-48 object-cover rounded-b-lg shadow" />
            </div>

            <div class="text-center mb-10">
                {{-- Menampilkan nama pengguna yang login (Admin) --}}
                <h1 class="text-3xl font-bold text-blue-900">Selamat Datang, {{ Auth::user()->username ?? 'Admin' }}!</h1>
                <p class="text-sm text-gray-600">Dashboard Sistem Informasi Manajemen Magang Mahasiswa</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-10">
                <div class="bg-white rounded-xl shadow-md p-6 hover:shadow-lg transition">
                    <h2 class="text-xl font-bold text-blue-800 mb-3">Magang Pusat</h2>
                    <p class="text-gray-700 mb-3">Magang yang sudah terorganisir oleh kampus ke mitra industri tertentu.</p>
                    <p class="text-gray-700">Membantu mahasiswa memahami dunia kerja nyata secara profesional.</p>
                </div>
                <div class="bg-white rounded-xl shadow-md p-6 hover:shadow-lg transition">
                    <h2 class="text-xl font-bold text-blue-800 mb-3">Magang Mandiri</h2>
                    <p class="text-gray-700 mb-3">Mahasiswa mencari tempat magang secara mandiri ke perusahaan sendiri.</p>
                    <p class="text-gray-700">Tetap memfokuskan pada penerapan keilmuan di dunia industri.</p>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 mb-6">
                {{-- Ganti href ke route yang sesuai --}}
                {{-- Asumsi ada route bernama 'admin.companies.index' atau sesuaikan --}}
                <a href="{{ route('admin.perusahaan.index') }}" class="cursor-pointer block">
                    <div class="bg-white p-6 rounded shadow text-center hover:bg-blue-50 transition">
                        <p class="text-2xl font-bold text-blue-600">{{ $jumlahPerusahaan ?? 0 }}</p>
                        <p class="text-sm text-gray-700 mt-1">Perusahaan</p>
                    </div>
                </a>
                {{-- Asumsi ada route bernama 'admin.vacancies.index' atau sesuaikan --}}
                <a href="{{ route('admin.lowongan.index') }}" class="cursor-pointer block">
                    <div class="bg-white p-6 rounded shadow text-center hover:bg-blue-50 transition">
                        <p class="text-2xl font-bold text-blue-600">{{ $jumlahLowongan ?? 0 }}</p>
                        <p class="text-sm text-gray-700 mt-1">Lowongan</p>
                    </div>
                </a>
                {{-- Asumsi ada route bernama 'admin.applicants.index' atau sesuaikan --}}
                <a href="{{ route('admin.pendaftar.index') }}" class="cursor-pointer block">
                    <div class="bg-white p-6 rounded shadow text-center hover:bg-blue-50 transition">
                         <p class="text-2xl font-bold text-blue-600">{{ $jumlahPendaftar ?? 0 }}</p>
                        <p class="text-sm text-gray-700 mt-1">Pendaftar</p>
                    </div>
                </a>
            </div>

            {{-- Pastikan path ini benar: resources/views/admin/jobcard.blade.php --}}
            @include('admin.jobcard')

            <div class="bg-white p-6 rounded-xl shadow mb-6 hover:bg-blue-50 transition">
                <h2 class="font-semibold text-gray-700 mb-4">Absensi Terkini (Contoh)</h2>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left text-gray-600">
                        <thead class="text-xs text-gray-500 uppercase border-b">
                            <tr>
                                <th class="px-4 py-2">No</th>
                                <th class="px-4 py-2">Nama</th>
                                <th class="px-4 py-2">Tanggal</th>
                                <th class="px-4 py-2">Waktu</th>
                                <th class="px-4 py-2">Deskripsi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="border-b">
                                <td class="px-4 py-2">1</td>
                                <td class="px-4 py-2">Andi Pratama</td>
                                <td class="px-4 py-2">2025-05-18</td>
                                <td class="px-4 py-2">08:03</td>
                                <td class="px-4 py-2">Masuk</td>
                            </tr>
                            {{-- Data lainnya --}}
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-10">
                <div class="bg-white p-6 rounded-xl shadow hover:bg-blue-50 transition">
                    <h2 class="font-semibold text-gray-700 mb-4">Statistik Program Studi (Contoh)</h2>
                    {{-- Konten statistik prodi --}}
                </div>
                <div class="bg-white p-6 rounded-xl shadow hover:bg-blue-50 transition">
                    <h2 class="font-semibold text-gray-700 mb-4">Pengguna Aktif Terkini (Contoh)</h2>
                    {{-- Konten pengguna aktif --}}
                </div>
            </div>

        </main>

        {{-- Pastikan path ini benar: resources/views/admin/template/footer.blade.php --}}
        @include('admin.template.footer')
    </div>

    {{-- JavaScript untuk dropdown profile, idealnya ini bagian dari navbar atau layout utama --}}
    {{-- Jika sudah ada di navbar, tidak perlu diulang di sini --}}
    @if (isset($profileBtn) && isset($profileDropdown)) {{-- Hanya jika variabel ini ada --}}
    <script>
        const profileBtn = document.getElementById('{{ $profileBtn }}'); // ID dari tombol profil di navbar
        const profileDropdown = document.getElementById('{{ $profileDropdown }}'); // ID dari dropdown di navbar

        profileBtn?.addEventListener('click', () => {
            profileDropdown.classList.toggle('hidden');
        });

        document.addEventListener('click', (e) => {
            if (profileBtn && profileDropdown && !profileBtn.contains(e.target) && !profileDropdown.contains(e.target)) {
                profileDropdown.classList.add('hidden');
            }
        });
    </script>
    @endif
</body>
</html>