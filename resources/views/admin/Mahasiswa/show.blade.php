<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Detail Mahasiswa - {{ $mahasiswa->name ?? $mahasiswa->username }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    {{-- <link rel="stylesheet" href="{{ asset('css/admin_style.css') }}"> --}}
</head>
<body class="bg-blue-50 text-gray-800">
    @include('admin.template.navbar')

    <main class="max-w-4xl mx-auto px-4 py-10 mt-16">
        <div class="bg-white p-8 rounded-xl shadow-md">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-bold text-blue-800">Detail Mahasiswa</h1>
                <a href="{{ route('admin.datamahasiswa') }}" class="text-sm text-blue-600 hover:underline">&larr; Kembali ke Daftar Mahasiswa</a>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <strong class="text-gray-700">Nama Lengkap:</strong>
                    <p class="text-gray-800">{{ $mahasiswa->name ?? '-' }}</p>
                </div>
                <div>
                    <strong class="text-gray-700">NIM (Username):</strong>
                    <p class="text-gray-800">{{ $mahasiswa->username ?? '-' }}</p>
                </div>
                <div>
                    <strong class="text-gray-700">Email:</strong>
                    <p class="text-gray-800">{{ $mahasiswa->email ?? '-' }}</p>
                </div>
                @if($mahasiswa->detailMahasiswa)
                    <div>
                        <strong class="text-gray-700">Kelas:</strong>
                        <p class="text-gray-800">{{ $mahasiswa->detailMahasiswa->kelas ?? '-' }}</p>
                    </div>
                    <div>
                        <strong class="text-gray-700">Program Studi:</strong>
                        <p class="text-gray-800">{{ $mahasiswa->detailMahasiswa->program_studi ?? '-' }}</p>
                    </div>
                    <div>
                        <strong class="text-gray-700">Nomor HP:</strong>
                        <p class="text-gray-800">{{ $mahasiswa->detailMahasiswa->nomor_hp ?? '-' }}</p>
                    </div>
                    <div class="md:col-span-2">
                        <strong class="text-gray-700">Alamat:</strong>
                        <p class="text-gray-800">{{ $mahasiswa->detailMahasiswa->alamat ?? '-' }}</p>
                    </div>
                @else
                    <p class="text-gray-500 md:col-span-2">Detail mahasiswa tidak ditemukan.</p>
                @endif
                 <div>
                    <strong class="text-gray-700">Role:</strong>
                    <p class="text-gray-800">{{ $mahasiswa->role->name ?? '-' }}</p>
                </div>
                 <div>
                    <strong class="text-gray-700">Akun Dibuat:</strong>
                    <p class="text-gray-800">{{ $mahasiswa->created_at ? $mahasiswa->created_at->format('d M Y, H:i') : '-' }}</p>
                </div>
            </div>

            <div class="mt-6 flex justify-end space-x-3">
                <a href="{{ route('admin.users.edit', $mahasiswa->id) }}" class="bg-yellow-500 hover:bg-yellow-600 text-white font-medium py-2 px-4 rounded-md">
                    Edit User
                </a>
                {{-- Anda bisa menambahkan tombol lain jika perlu, misal "Edit Detail Mahasiswa" jika ada controller terpisah --}}
            </div>
        </div>
    </main>

    @include('admin.template.footer')
</body>
</html>