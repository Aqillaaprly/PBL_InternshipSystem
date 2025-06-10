<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Mahasiswa - {{ $pendaftar->name ?? $pendaftar->username }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
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
        .avatar-picture {
            width: 8rem;
            height: 8rem;
            object-fit: cover;
            border-radius: 9999px;
            background-color: white;
            padding: 0.25rem;
            border: 4px solid white;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
            margin-top: -4rem;
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
        }
        .info-item {
            display: flex;
            justify-content: space-between;
            padding: 1rem 0;
            border-bottom: 1px solid #f3f4f6;
        }
        .info-item:last-child {
            border-bottom: none;
        }
        .info-label {
            color: #6b7280;
            font-size: 0.875rem;
            display: flex;
            align-items: center;
        }
        .info-label i {
            margin-right: 0.5rem;
            color: #9ca3af;
        }
        .info-value {
            color: #111827;
            font-weight: 500;
            text-align: right;
        }
        .action-button {
            transition: all 0.2s ease-in-out;
            padding: 0.625rem 1.25rem;
            font-size: 0.875rem;
            border-radius: 0.5rem;
        }
        .action-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }
        .edit-button {
            background-image: linear-gradient(to right, #4f46e5, #7c3aed);
            color: white;
        }
        .edit-button:hover {
            background-image: linear-gradient(to right, #4338ca, #6d28d9);
        }
    </style>
</head>
<body class="text-gray-800">
    @include('perusahaan.template.navbar')

    <main class="max-w-3xl mx-auto px-4 py-8 mt-20">
        <div class="profile-header text-center">
            <h1 class="text-2xl sm:text-3xl font-bold">Detail Mahasiswa</h1>
        </div>

        <div class="info-card text-center">
            <div class="flex justify-center">
                <img src="https://ui-avatars.com/api/?name={{ urlencode($pendaftar->name ?? $pendaftar->username) }}&size=170&background=2563EB&color=fff" alt="Avatar" class="avatar-picture">
            </div>

            <h1 class="text-3xl font-bold mt-4">{{ $pendaftar->user->name ?? '-' }}</h1>
            <p class="text-gray-500 text-sm">{{ $pendaftar->user->username ?? '-' }}</p>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
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
                @endif

                <div class="md:col-span-2">
                    <strong class="text-gray-700">Role:</strong>
                    <p class="text-gray-800">{{ $pendaftar->user->role->name ?? '-' }}</p>
                </div>
            </div>
            <div class="info-item">
                <span class="info-label"><i class="fas fa-clock"></i>Akun Dibuat</span>
                <span class="info-value">{{ $pendaftar->created_at ? $pendaftar->created_at->format('d M Y, H:i') : '-' }}</span>
            </div>
            </div>
        </div>
    </main>
</body>
</html>
ƒ