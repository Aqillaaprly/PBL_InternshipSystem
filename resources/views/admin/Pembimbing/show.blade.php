<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    {{-- Pastikan variabel $pembimbing ada dan merupakan objek sebelum mencoba mengakses propertinya --}}
    <title>Detail Pembimbing - {{ $pembimbing->nama_lengkap ?? ($pembimbing->user->name ?? 'Informasi Pembimbing') }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    {{-- <link rel="stylesheet" href="{{ asset('css/admin_style.css') }}"> --}}
</head>
<body class="bg-blue-50 text-gray-800">
    @include('admin.template.navbar')

    <main class="max-w-4xl mx-auto px-4 py-10 mt-16">
        <div class="bg-white p-8 rounded-xl shadow-md">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-bold text-blue-800">Detail Pembimbing</h1>
                {{-- Mengarahkan kembali ke daftar pembimbing --}}
                <a href="{{ route('admin.data_pembimbing') }}" class="text-sm text-blue-600 hover:underline">&larr; Kembali ke Daftar Pembimbing</a>
            </div>

            {{-- Menampilkan pesan sukses/error jika ada dari redirect --}}
            @if (session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif
            @if (session('error'))
                 <div class="bg-red-100 border-l-4 border-red-500 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <strong class="font-bold">Gagal!</strong>
                    <span class="block sm:inline">{{ session('error') }}</span>
                </div>
            @endif

            {{-- Cek apakah $pembimbing valid dan memiliki ID sebelum menampilkan detail --}}
            @if(isset($pembimbing) && $pembimbing->id)
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <strong class="text-gray-700">Nama Lengkap:</strong>
                        <p class="text-gray-800">{{ $pembimbing->nama_lengkap ?? '-' }}</p>
                    </div>
                    <div>
                        <strong class="text-gray-700">NIP:</strong>
                        <p class="text-gray-800">{{ $pembimbing->nip ?? '-' }}</p>
                    </div>
                    <div>
                        <strong class="text-gray-700">Email Institusi:</strong>
                        <p class="text-gray-800">{{ $pembimbing->email_institusi ?? '-' }}</p>
                    </div>
                    <div>
                        <strong class="text-gray-700">Nomor Telepon:</strong>
                        <p class="text-gray-800">{{ $pembimbing->nomor_telepon ?? '-' }}</p>
                    </div>
                    <div>
                        <strong class="text-gray-700">Jabatan Fungsional:</strong>
                        <p class="text-gray-800">{{ $pembimbing->jabatan_fungsional ?? '-' }}</p>
                    </div>
                    <div>
                        <strong class="text-gray-700">Program Studi Homebase:</strong>
                        <p class="text-gray-800">{{ $pembimbing->program_studi_homebase ?? '-' }}</p>
                    </div>
                    <div class="md:col-span-2">
                        <strong class="text-gray-700">Bidang Keahlian Utama:</strong>
                        <p class="text-gray-800">{{ $pembimbing->bidang_keahlian_utama ?? '-' }}</p>
                    </div>
                    <div>
                        <strong class="text-gray-700">Kuota Bimbingan Aktif:</strong>
                        <p class="text-gray-800">{{ $pembimbing->kuota_bimbingan_aktif ?? 0 }}</p>
                    </div>
                    <div>
                        <strong class="text-gray-700">Maksimal Kuota Bimbingan:</strong>
                        <p class="text-gray-800">{{ $pembimbing->maks_kuota_bimbingan ?? 0 }}</p>
                    </div>
                    <div>
                        <strong class="text-gray-700">Status Aktif Dosen:</strong>
                        <p class="text-gray-800">
                            @if($pembimbing->status_aktif)
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Aktif</span>
                            @else
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">Tidak Aktif</span>
                            @endif
                        </p>
                    </div>
                </div>

                {{-- Informasi Akun Login Terkait (jika ada) --}}
                @if($pembimbing->user)
                    <div class="border-t pt-6 mt-6">
                        <h2 class="text-xl font-semibold text-gray-700 mb-4">Informasi Akun Login Terkait</h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <div>
                                <strong class="text-gray-700">Username Akun:</strong>
                                <p class="text-gray-800">{{ $pembimbing->user->username ?? '-' }}</p>
                            </div>
                            <div>
                                <strong class="text-gray-700">Email Akun:</strong>
                                <p class="text-gray-800">{{ $pembimbing->user->email ?? '-' }}</p>
                            </div>
                            <div>
                                <strong class="text-gray-700">Nama pada Akun:</strong>
                                <p class="text-gray-800">{{ $pembimbing->user->name ?? '-' }}</p>
                            </div>
                             <div>
                                <strong class="text-gray-700">Role Akun:</strong>
                                <p class="text-gray-800">{{ $pembimbing->user->role->name ?? '-' }}</p>
                            </div>
                            <div>
                                <strong class="text-gray-700">Akun Dibuat:</strong>
                                <p class="text-gray-800">{{ $pembimbing->user->created_at ? $pembimbing->user->created_at->format('d M Y, H:i') : '-' }}</p>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="border-t pt-6 mt-6">
                        <h2 class="text-xl font-semibold text-gray-700 mb-4">Informasi Akun Login Terkait</h2>
                        <p class="text-gray-500">Pembimbing ini belum memiliki akun login sistem.</p>
                    </div>
                @endif


                <div class="mt-6 flex justify-end space-x-3">
                    {{-- Jika pembimbing memiliki user_id, arahkan ke edit user --}}
                    @if($pembimbing->user_id)
                        <a href="{{ route('admin.users.edit', $pembimbing->user_id) }}" class="bg-yellow-500 hover:bg-yellow-600 text-white font-medium py-2 px-4 rounded-md">
                            Edit Akun Login Pembimbing
                        </a>
                    @else
                         {{-- Atau tombol untuk membuat akun user baru untuk pembimbing ini, jika ada fungsionalitasnya --}}
                         {{-- <a href="{{ route('admin.pembimbing.create_user', $pembimbing->id) }}" class="bg-blue-500 hover:bg-blue-600 text-white font-medium py-2 px-4 rounded-md">
                            Buat Akun Login
                        </a> --}}
                    @endif
                    {{-- Tombol untuk mengedit detail pembimbing itu sendiri (jika ada form terpisah) --}}
                    {{-- <a href="{{ route('admin.pembimbing.edit', $pembimbing->id) }}" class="bg-blue-500 hover:bg-blue-600 text-white font-medium py-2 px-4 rounded-md">
                        Edit Detail Pembimbing
                    </a> --}}
                </div>
            @else
                {{-- Pesan jika $pembimbing tidak valid atau tidak ditemukan --}}
                <div class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-4" role="alert">
                    <p class="font-bold">Data Pembimbing Tidak Ditemukan</p>
                    <p>Tidak dapat menampilkan detail karena data pembimbing tidak valid atau tidak ditemukan.</p>
                </div>
            @endif
        </div>
    </main>

    @include('admin.template.footer')
</body>
</html>