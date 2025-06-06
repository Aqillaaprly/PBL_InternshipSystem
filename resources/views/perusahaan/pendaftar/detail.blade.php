<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Detail Perusahaan - {{ $company->nama_perusahaan ?? 'Informasi Perusahaan' }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-blue-50 text-gray-800">
    @include('perusahaan.template.navbar')

    <main class="max-w-4xl mx-auto px-4 py-10 mt-16">
        <div class="bg-white p-8 rounded-xl shadow-md">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-bold text-blue-800">Detail Mahasiswa</h1>
                <a href="{{ route('dosen.data_mahasiswabim', $pendaftar->user-> id) }}" class="text-sm text-blue-600 hover:underline">&larr; Kembali ke Daftar Mahasiswa</a>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <strong class="text-gray-700">Nama Lengkap:</strong>
                    <p class="text-gray-800">{{ $pendaftar->user->name ?? '-' }}</p>
                </div>
                <div>
                    <strong class="text-gray-700">NIM (Username):</strong>
                    <p class="text-gray-800">{{ $pendaftar->user->username ?? '-' }}</p>
                </div>
                <div>
                    <strong class="text-gray-700">Email:</strong>
                    <p class="text-gray-800">{{ $pendaftar->user->email ?? '-' }}</p>
                </div>
                @if($pendaftar->user->detailMahasiswa)
                    <div>
                        <strong class="text-gray-700">Kelas:</strong>
                        <p class="text-gray-800">{{ $pendaftar->user->detailMahasiswa->kelas ?? '-' }}</p>
                    </div>
                    <div>
                        <strong class="text-gray-700">Program Studi:</strong>
                        <p class="text-gray-800">{{ $pendaftar->user->detailMahasiswa->program_studi ?? '-' }}</p>
                    </div>
                    <div>
                        <strong class="text-gray-700">Nomor HP:</strong>
                        <p class="text-gray-800">{{ $pendaftar->user->detailMahasiswa->nomor_hp ?? '-' }}</p>
                    </div>
                    <div class="md:col-span-2">
                        <strong class="text-gray-700">Alamat:</strong>
                        <p class="text-gray-800">{{ $pendaftar->user->detailMahasiswa->alamat ?? '-' }}</p>
                    </div>
                @else
                    <p class="text-gray-500 md:col-span-2">Detail mahasiswa tidak ditemukan.</p>
                @endif
                 <div>
                    <strong class="text-gray-700">Role:</strong>
                    <p class="text-gray-800">{{ $pendaftar->user->role->name ?? '-' }}</p>
                </div>
                 <div>
                    <strong class="text-gray-700">Akun Dibuat:</strong>
                    <p class="text-gray-800">{{ $pendaftar->user->created_at ? $pendaftar->user->created_at->format('d M Y, H:i') : '-' }}</p>
                </div>
            </div>
        </div>
    </main>

    @include('perusahaan.template.footer')
</body>
</html>