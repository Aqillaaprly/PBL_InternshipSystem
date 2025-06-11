<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Profil Mahasiswa - {{ $user->name ?? $user->username }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css" integrity="sha512-SzlrxWUlpfuzQ+pcUCosxcglQRNAq/DZjVsC0lE40xsADsfeQoEypE+enwcOiGjk/bSuGGKHEyjSoQ1zVisanQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>
        body {
            background-color: #f7f8fc;
        }
        .profile-header {
            background: linear-gradient(to right, #687EEA, #3B5998);
            color: white;
            padding: 1rem 1rem;
            border-radius: 1rem 1rem 0 0;
            margin-bottom: -1rem;
            position: relative;
            z-index: 10;
        }
        .profile-picture {
            width: 10rem;
            height: 10rem;
            border-radius: 9999px;
            object-fit: cover;
            border: 5px solid white;
            box-shadow: 0 10px 20px rgba(0,0,0,0.15);
            margin-top: -5rem;
            position: relative;
            z-index: 20;
        }
        .info-card {
            background-color: white;
            border-radius: 1rem;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
            padding: 2rem;
            padding-top: 6rem;
            position: relative;
            z-index: 5;
            text-align: left;
        }
        .info-label {
            color: #6b7280;
            font-size: 0.875rem;
            display: flex;
            align-items: center;
            margin-bottom: 0.25rem;
        }
        .info-label i {
            margin-right: 0.5rem;
            color: #9ca3af;
        }
        .info-value {
            color: #111827;
            font-weight: 500;
            font-size: 1rem;
        }
        .info-grid-item {
            padding: 0.5rem 0;
            display: flex;
            flex-direction: column;
        }
        .whitespace-pre-line {
            white-space: pre-line;
        }
        .action-button {
            transition: all 0.2s ease-in-out;
            padding: 0.625rem 1.25rem;
            font-size: 0.875rem;
            border-radius: 0.5rem;
        }
        .action-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        }
        .edit-button {
            background-image: linear-gradient(to right, #4f46e5, #7c3aed);
        }
        .edit-button:hover {
            background-image: linear-gradient(to right, #4338ca, #6d28d9);
        }
    </style>
</head>
<body class="text-gray-800">
@include('mahasiswa.template.navbar')

<main class="max-w-lg mx-auto px-3 py-8 mt-20">

    <div class="profile-header text-center">
        <h1 class="text-2xl sm:text-3xl font-bold">Profil Mahasiswa</h1>
    </div>

    <div class="info-card">

        <div class="flex justify-center mb-8">
            @if ($user->profile_picture && Storage::disk('public')->exists($user->profile_picture))
            <img src="{{ asset('storage/' . $user->profile_picture) }}" alt="Foto Profil {{ $user->name }}"
                 class="profile-picture">
            @else
            <img src="https://ui-avatars.com/api/?name={{ urlencode($user->name ?? $user->username) }}&size=170&background=1D4ED8&color=fff&font-size=0.4&bold=true"
                 alt="Avatar {{ $user->name }}"
                 class="profile-picture">
            @endif
        </div>

        {{-- Centered Name and Username --}}
        <div class="text-center">
            <h1 class="text-3xl font-bold text-gray-800 mt-4">{{ $user->name ?? 'Nama Mahasiswa' }}</h1>
            <p class="text-gray-500 text-sm">{{ '@'.($user->username ?? 'username') }}</p>
        </div>

        @if ($user->role)
        <div class="flex items-center justify-center space-x-2 mt-4 mb-4">
            {{-- Student Role Tag --}}
            <span class="inline-flex items-center bg-blue-100 text-blue-700 text-xs font-semibold px-3 py-1 rounded-full">
                        <i class="fas fa-graduation-cap mr-1"></i>
                        {{ Str::ucfirst($user->role->name ?? 'Mahasiswa') }}
                    </span>
        </div>
        @endif

        @if (session('success'))
        <div class="bg-green-50 border-l-4 border-green-400 text-green-700 p-4 rounded-md my-6" role="alert">
            <div class="flex">
                <div class="py-1"><i class="fas fa-check-circle fa-lg mr-3 text-green-500"></i></div>
                <div>
                    <p class="font-bold">Sukses!</p>
                    <p class="text-sm">{{ session('success') }}</p>
                </div>
            </div>
        </div>
        @endif

        {{-- Consolidated Details Section --}}
        <div class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-2">

            {{-- Name --}}
            <div class="info-grid-item md:col-span-1">
                <span class="info-label"><i class="fas fa-user"></i>Nama Lengkap</span>
                <span class="info-value">{{ $user->name ?? '-' }}</span>
            </div>

            {{-- Username --}}
            <div class="info-grid-item md:col-span-1">
                <span class="info-label"><i class="fas fa-at"></i>Username</span>
                <span class="info-value">{{ $user->username ?? '-' }}</span>
            </div>

            {{-- Email --}}
            <div class="info-grid-item md:col-span-2">
                <span class="info-label"><i class="fas fa-envelope"></i>Alamat Email</span>
                <span class="info-value">{{ $user->email ?? '-' }}</span>
            </div>

            {{-- Email Verified --}}
            <div class="info-grid-item md:col-span-1">
                <span class="info-label"><i class="fas fa-user-check"></i>Email Terverifikasi</span>
                <span class="info-value">
                        @if($user->email_verified_at)
                            <span class="text-green-600 flex items-center">
                                <i class="fas fa-check-circle mr-1"></i> Ya
                            </span>
                            <span class="text-xs text-gray-400 block ml-6">({{ $user->email_verified_at->isoFormat('D MMMM YYYY') }})</span>
                        @else
                            <span class="text-red-600 flex items-center">
                                <i class="fas fa-times-circle mr-1"></i> Belum
                            </span>
                        @endif
                    </span>
            </div>

            {{-- Registration Date --}}
            <div class="info-grid-item md:col-span-1">
                <span class="info-label"><i class="fas fa-calendar-plus"></i>Terdaftar Sejak</span>
                <span class="info-value">{{ $user->created_at ? $user->created_at->isoFormat('D MMMM YYYY') : '-' }}</span>
            </div>

            {{-- Student Details --}}
            @if($user->detailMahasiswa)
            {{-- NIM --}}
            <div class="info-grid-item md:col-span-1">
                <span class="info-label"><i class="fas fa-id-card"></i>NIM</span>
                <span class="info-value">{{ $user->detailMahasiswa->nim ?? '-' }}</span>
            </div>

            {{-- Program Studi --}}
            <div class="info-grid-item md:col-span-1">
                <span class="info-label"><i class="fas fa-book"></i>Program Studi</span>
                <span class="info-value">{{ $user->detailMahasiswa->program_studi ?? '-' }}</span>
            </div>

            {{-- Kelas --}}
            <div class="info-grid-item md:col-span-1">
                <span class="info-label"><i class="fas fa-users"></i>Kelas</span>
                <span class="info-value">{{ $user->detailMahasiswa->kelas ?? '-' }}</span>
            </div>

            {{-- Nomor HP --}}
            <div class="info-grid-item md:col-span-1">
                <span class="info-label"><i class="fas fa-phone"></i>Nomor HP</span>
                <span class="info-value">{{ $user->detailMahasiswa->nomor_hp ?? '-' }}</span>
            </div>

            {{-- Alamat --}}
            <div class="info-grid-item md:col-span-2">
                <span class="info-label"><i class="fas fa-map-marker-alt"></i>Alamat</span>
                <span class="info-value">{{ $user->detailMahasiswa->alamat ?? '-' }}</span>
            </div>
            @endif

        </div>

        <div class="mt-10 flex justify-center">
            <a href="{{ route('mahasiswa.profile.edit') }}"
               class="action-button edit-button inline-flex items-center text-white shadow-lg">
                <i class="fas fa-pencil-alt mr-2"></i>Edit Profil
            </a>
        </div>
    </div>
</main>

@include('mahasiswa.template.footer')
</body>
</html>
