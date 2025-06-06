<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Perusahaan - {{ $company->nama_perusahaan ?? 'Perusahaan' }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            background-color: #f7f8fc;
        }
        .profile-header {
    background: linear-gradient(to right, #687EEA, #3B5998); 
    color: white;
    padding: 1rem 1rem; /* diperkecil dari 2.5rem */
    border-radius: 1rem 1rem 0 0; 
    margin-bottom: -1rem;
    position: relative;
    z-index: 10;
}
        .logo-picture {
            width: 8rem;
            height: 8rem;
            object-fit: cover;
            border-radius: 0.75rem;
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
    @include('admin.template.navbar')

    <main class="max-w-3xl mx-auto px-4 py-8 mt-20">
        <div class="profile-header text-center">
            <h1 class="text-2xl sm:text-3xl font-bold">Detail Perusahaan</h1>
        </div>

        <div class="info-card text-center">

            <div class="flex justify-center">
                @if($company->logo_path && Storage::disk('public')->exists($company->logo_path))
                    <img src="{{ asset('storage/' . $company->logo_path) }}" alt="Logo Perusahaan" class="logo-picture">
                @else
                    <img src="https://ui-avatars.com/api/?name={{ urlencode($company->nama_perusahaan) }}&size=170&background=2563EB&color=fff" alt="Logo Default" class="logo-picture">
                @endif
            </div>

            <h1 class="text-3xl font-bold mt-4">{{ $company->nama_perusahaan ?? 'Nama Perusahaan' }}</h1>
            <p class="text-gray-500 text-sm">{{ $company->website ? $company->website : 'Website tidak tersedia' }}</p>

            <div class="text-left mt-8 space-y-4">
                <div class="info-item">
                    <span class="info-label"><i class="fas fa-envelope"></i>Email</span>
                    <span class="info-value">{{ $company->email_perusahaan ?? '-' }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label"><i class="fas fa-phone"></i>Telepon</span>
                    <span class="info-value">{{ $company->telepon ?? '-' }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label"><i class="fas fa-map-marker-alt"></i>Alamat</span>
                    <span class="info-value text-right">{{ $company->alamat ?? '-' }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label"><i class="fas fa-city"></i>Kota / Provinsi</span>
                    <span class="info-value">{{ $company->kota ?? '-' }}, {{ $company->provinsi ?? '-' }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label"><i class="fas fa-mail-bulk"></i>Kode Pos</span>
                    <span class="info-value">{{ $company->kode_pos ?? '-' }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label"><i class="fas fa-briefcase"></i>Status Kerjasama</span>
                    <span class="info-value">
                        <span class="px-2 py-1 text-xs font-semibold leading-tight rounded-full
                            @if($company->status_kerjasama == 'Aktif') bg-green-100 text-green-700
                            @elseif($company->status_kerjasama == 'Non-Aktif') bg-red-100 text-red-700
                            @else bg-yellow-100 text-yellow-700 @endif">
                            {{ $company->status_kerjasama ?? '-' }}
                        </span>
                    </span>
                </div>
                <div class="info-item">
                    <span class="info-label"><i class="fas fa-align-left"></i>Deskripsi</span>
                    <span class="info-value text-right whitespace-pre-line">{{ $company->deskripsi ?? '-' }}</span>
                </div>
            </div>

            <div class="mt-10 flex justify-center">
                <a href="{{ route('admin.perusahaan.edit', $company->id) }}"
                   class="action-button edit-button inline-flex items-center shadow-lg">
                    <i class="fas fa-pencil-alt mr-2"></i>Edit Profil Perusahaan
                </a>
            </div>
        </div>
    </main>
</body>
</html>