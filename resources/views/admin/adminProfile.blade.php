<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Admin Profile</title>
    <script src="https://cdn.tailwindcss.com"></script>
    {{-- Jika Anda menggunakan Vite untuk asset (disarankan) --}}
    {{-- @vite(['resources/css/app.css', 'resources/js/app.js']) --}}
</head>
<body class="bg-[#f0f6ff]">

    {{-- Menggunakan sintaks Blade untuk include navbar --}}
    {{-- Pastikan path ke navbar_admin.blade.php benar --}}
    @include('admin.template.navbar_admin') {{-- Atau path yang sesuai seperti 'layouts.navbars.admin' --}}

<main class="max-w-xl mx-auto px-6 py-10 mt-16"> {{-- Tambahkan margin top jika navbar fixed --}}
    <div class="bg-white p-8 rounded-xl shadow">

        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif

        @if ($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                <strong class="font-bold">Oops! Ada beberapa masalah:</strong>
                <ul class="mt-2 list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="flex flex-col items-center mb-8">
            {{-- Tampilkan gambar profil dinamis jika ada, jika tidak, gunakan placeholder --}}
            <img src="{{ $user->profile_image_url ?? 'https://i.ibb.co/N1KzB1H/user-icon.png' }}" alt="Profile" class="w-24 h-24 rounded-full mb-4 object-cover" />
            <h1 class="text-xl font-bold">{{ $user->username ?? 'Nama Admin' }}</h1>
            <p class="text-sm text-gray-500">{{ $user->role->name ?? 'Role Admin' }}</p>
        </div>

        <form action="{{ route('admin.profile.update') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
            @csrf {{-- Token CSRF Laravel --}}

            <div>
                <label for="username" class="block text-sm font-medium text-gray-700">Username</label>
                <input type="text" name="username" id="username" value="{{ old('username', $user->username ?? '') }}" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" />
            </div>

            <div>
                <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                <input type="email" name="email" id="email" value="{{ old('email', $user->email ?? '') }}" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" />
            </div>

            <div>
                <label for="no_hp" class="block text-sm font-medium text-gray-700">No HP</label>
                <input type="text" name="no_hp" id="no_hp" value="{{ old('no_hp', $user->no_hp ?? '') }}" placeholder="08xxxxxxxxxx" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" />
            </div>

            <div>
                <label for="occupation" class="block text-sm font-medium text-gray-700">Jabatan (Occupation)</label>
                <input type="text" name="occupation" id="occupation" value="{{ old('occupation', $user->occupation ?? '') }}" placeholder="Jabatan Anda" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" />
            </div>
             {{-- Asumsi kolom 'khs_skada_path' ada di tabel users --}}
            {{-- <div>
                <label for="khs_skada" class="block text-sm font-medium text-gray-700">KHS / SKADA</label>
                <input type="file" name="khs_skada" id="khs_skada" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100"/>
                @if($user->khs_skada_path)
                    <a href="{{ Storage::url($user->khs_skada_path) }}" target="_blank" class="text-xs text-indigo-600 hover:text-indigo-900">Lihat file saat ini</a>
                @endif
            </div> --}}

            <div>
                <label for="bio" class="block text-sm font-medium text-gray-700">Bio</label>
                <textarea name="bio" id="bio" rows="3" placeholder="Sedikit tentang Anda" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">{{ old('bio', $user->bio ?? '') }}</textarea>
            </div>

            <hr class="my-6">
            <h2 class="text-lg font-semibold text-gray-700 mb-3">Ubah Password (Opsional)</h2>
             <div>
                <label for="password" class="block text-sm font-medium text-gray-700">Password Baru</label>
                <input type="password" name="password" id="password" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" />
            </div>
             <div>
                <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Konfirmasi Password Baru</label>
                <input type="password" name="password_confirmation" id="password_confirmation" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" />
            </div>

            <hr class="my-6">
            <h2 class="text-lg font-semibold text-gray-700 mb-3">Unggah Dokumen</h2>

            @php
                $documents = [
                    'cv' => 'CV',
                    'portfolio' => 'Portfolio',
                    'proposal' => 'Proposal',
                    // Tambahkan dokumen lain sesuai kebutuhan dengan format 'nama_field_db' => 'Label Tampilan'
                    // 'riwayat_penyakit' => 'Riwayat Penyakit',
                    // 'ktp' => 'KTP',
                    // 'ktm' => 'KTM',
                    // 'surat_ortu' => 'Surat Izin Orang Tua'
                ];
            @endphp

            @foreach ($documents as $field_name => $label)
                <div>
                    <label for="{{ $field_name }}" class="block text-sm font-medium text-gray-700 mb-1">{{ $label }}</label>
                    <input type="file" name="{{ $field_name }}" id="{{ $field_name }}" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100"/>
                    {{-- Tampilkan link ke file yang sudah ada --}}
                    @if ($user->{$field_name.'_path'} && Storage::disk('public')->exists($user->{$field_name.'_path'}))
                        <div class="mt-2">
                            <a href="{{ Storage::url($user->{$field_name.'_path'}) }}" target="_blank" class="text-xs text-indigo-60