<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Profil Mahasiswa - {{ $mahasiswa->name ?? $mahasiswa->username }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    {{-- <link rel="stylesheet" href="{{ asset('css/admin_style.css') }}"> --}}
</head>
<body class="bg-blue-50 text-gray-800">
@include('mahasiswa.template.navbar')

<main class="max-w-3xl mx-auto px-4 py-10 mt-20">
    <div class="bg-white p-8 rounded-xl shadow-lg">
        <div class="flex flex-col items-center mb-8">
            {{-- Foto Profil --}}
            @if ($mahasiswa->profile_picture && Storage::disk('public')->exists($mahasiswa->profile_picture))
            <img src="{{ asset('storage/' . $mahasiswa->profile_picture) }}" alt="Avatar Mahasiswa" class="w-32 h-32 rounded-full mb-4 border-4 border-blue-200 object-cover">
            @else
            <img src="https://ui-avatars.com/api/?name={{ urlencode($mahasiswa->name ?? $mahasiswa->username) }}&background=random&color=fff&size=128" alt="Avatar Mahasiswa" class="w-32 h-32 rounded-full mb-4 border-4 border-blue-200 object-cover">
            @endif

            {{-- Logo BEM (jika ada dan ingin ditampilkan di sini) --}}
            {{-- Contoh: --}}
            {{-- <img src="{{ asset('images/logo-bem-malang-raya.png') }}" alt="Logo BEM Malang Raya" class="h-16 mx-auto mb-3"> --}}

            <h1 class="text-3xl font-bold text-gray-800">{{ $mahasiswa->name ?? 'Nama Mahasiswa Belum Diatur' }}</h1>

            {{-- Username dan Badge Mahasiswa dalam satu baris, terpusat --}}
            <div class="flex items-center justify-center mt-1 space-x-2">
                <p class="text-md text-gray-500">{{ '@' . ($mahasiswa->username ?? 'N/A') }}</p>
            </div>
        </div>

        @if (session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 px-4 py-3 rounded relative mb-6" role="alert">
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
        @endif

        <div class="border-t border-gray-200 pt-6">
            <dl class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-4">
                <div class="sm:col-span-1">
                    <dt class="text-sm font-medium text-gray-500">Nama Lengkap</dt>
                    <dd class="mt-1 text-md text-gray-900">{{ $mahasiswa->name ?? '-' }}</dd>
                </div>
                <div class="sm:col-span-1">
                    <dt class="text-sm font-medium text-gray-500">Username</dt>
                    <dd class="mt-1 text-md text-gray-900">{{ $mahasiswa->username ?? '-' }}
                        @if($mahasiswa->role)
                        <span class="px-2 py-0.5 text-xs font-semibold bg-blue-100 text-blue-700 rounded-full">
                        {{ ucfirst($mahasiswa->role->name) }}
                    </span>
                        @endif
                    </dd>
                </div>
                <div class="sm:col-span-1">
                    <dt class="text-sm font-medium text-gray-500">Alamat Email</dt>
                    <dd class="mt-1 text-md text-gray-900">{{ $mahasiswa->email ?? '-' }}</dd>
                </div>
                <div class="sm:col-span-1">
                    <dt class="text-sm font-medium text-gray-500">Terdaftar Sejak</dt>
                    <dd class="mt-1 text-md text-gray-900">{{ $mahasiswa->created_at ? $mahasiswa->created_at->isoFormat('D MMMM YYYY, HH:mm') : '-' }}</dd>
                </div>
                @if($mahasiswa->email_verified_at)
                <div class="sm:col-span-2">
                    <dt class="text-sm font-medium text-gray-500">Email Terverifikasi</dt>
                    <dd class="mt-1 text-md text-green-600">Ya, pada {{ $mahasiswa->email_verified_at->isoFormat('D MMMM YYYY, HH:mm') }}</dd>
                </div>
                @else
                <div class="sm:col-span-2">
                    <dt class="text-sm font-medium text-gray-500">Email Terverifikasi</dt>
                    <dd class="mt-1 text-md text-red-600">Belum</dd>
                </div>
                @endif
            </dl>
        </div>

        <div class="mt-8 flex justify-end">
            <a href="{{ route('mahasiswa.profile.edit') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-2 px-4 rounded-md shadow-sm">
                Edit Profil
            </a>
        </div>
    </div>
</main>

@include('mahasiswa.template.footer')
</body>
</html>
