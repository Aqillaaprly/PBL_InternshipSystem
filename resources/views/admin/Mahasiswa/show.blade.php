    <!DOCTYPE html>
    <html lang="id">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Profil Mahasiswa - {{ $mahasiswa->name ?? $mahasiswa->username }}</title>
        <script src="https://cdn.tailwindcss.com"></script>
        {{-- Font Awesome for icons --}}
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css" xintegrity="sha512-SzlrxWUlpfuzQ+pcUCosxcglQRNAq/DZjVsC0lE40xsADsfeQoEypE+enwcOiGjk/bSuGGKHEyjSoQ1zVisanQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
        <style>
            body {
                font-family: 'Inter', sans-serif;
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
            /* Adjusted info-label for consistent styling */
            .info-label {
                color: #6b7280;
                font-size: 0.875rem;
                display: flex; /* Use flex to align icon and text */
                align-items: center;
                margin-bottom: 0.25rem; /* Space between label and value */
                white-space: nowrap; /* Prevent label text from wrapping */
            }
            .info-label i {
                margin-right: 0.5rem;
                color: #9ca3af;
            }
            /* Adjusted info-value for left alignment and allowing wrap */
            .info-value {
                color: #111827;
                font-weight: 500;
                font-size: 1rem; /* Default font size for values */
                text-align: left; /* Values now align left */
                white-space: nowrap; /* Default: no wrap */
                overflow: hidden; /* Hide overflow */
                text-overflow: ellipsis; /* Add ellipsis */
            }
            /* Smaller font size for email to prevent overlap */
            .info-value.email-value {
                font-size: 0.9rem; /* Slightly smaller font size for email */
            }
            /* Specific override for elements that should explicitly wrap */
            .info-value.whitespace-pre-line {
                white-space: pre-line; /* Allow pre-line for description */
                overflow: visible; /* Override hidden to show full description */
                text-overflow: clip; /* Override ellipsis for full description */
            }

            /* Adjusted info-grid-item to stack label and value, and align content to start */
            .info-grid-item {
                padding: 0.5rem 0;
                display: flex;
                flex-direction: column; /* Stack label on top of value */
                align-items: flex-start; /* Align label and value to the left */
            }

            .action-button {
                transition: all 0.2s ease-in-out;
                padding: 0.625rem 1.25rem;
                font-size: 0.875rem;
                border-radius: 0.5rem;
                color: white;
            }
            .action-button:hover {
                transform: translateY(-2px);
                box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
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
        @include('admin.template.navbar')

        <main class="max-w-lg mx-auto px-3 py-8 mt-20">
            <div class="profile-header text-center">
                <h1 class="text-2xl sm:text-3xl font-bold">Detail Mahasiswa</h1>
            </div>

            <div class="info-card">
                <div class="flex justify-center">
                    {{-- Display profile picture if available, else fallback to UI-Avatar --}}
                    @if($mahasiswa->profile_picture && Storage::disk('public')->exists($mahasiswa->profile_picture))
                        <img src="{{ asset('storage/' . $mahasiswa->profile_picture) }}" alt="Foto Profil {{ $mahasiswa->name }}" class="avatar-picture">
                    @else
                        <img src="https://ui-avatars.com/api/?name={{ urlencode($mahasiswa->name ?? $mahasiswa->username) }}&size=170&background=2563EB&color=fff" alt="Avatar" class="avatar-picture">
                    @endif
                </div>

                <h1 class="text-3xl font-bold mt-4 text-center">{{ $mahasiswa->name ?? '-' }}</h1>
                <p class="text-gray-500 text-sm text-center">{{ '@'.($mahasiswa->username ?? '-') }}</p>

                <div class="mt-8 grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-2"> {{-- Using grid for consistent layout --}}
                    {{-- Email --}}
                    <div class="info-grid-item md:col-span-1">
                        <span class="info-label"><i class="fas fa-envelope"></i>Email</span>
                        <span class="info-value email-value">{{ $mahasiswa->email ?? '-' }}</span> {{-- Added email-value class --}}
                    </div>
                    {{-- NIM --}}
                    <div class="info-grid-item md:col-span-1">
                        <span class="info-label"><i class="fas fa-id-card"></i>NIM</span>
                        <span class="info-value">{{ $mahasiswa->detailMahasiswa->nim ?? '-' }}</span>
                    </div>

                    @if($mahasiswa->detailMahasiswa)
                        {{-- Kelas --}}
                        <div class="info-grid-item md:col-span-1">
                            <span class="info-label"><i class="fas fa-user-graduate"></i>Kelas</span>
                            <span class="info-value">{{ $mahasiswa->detailMahasiswa->kelas ?? '-' }}</span>
                        </div>
                        {{-- Program Studi --}}
                        <div class="info-grid-item md:col-span-1">
                            <span class="info-label"><i class="fas fa-school"></i>Program Studi</span>
                            <span class="info-value">{{ $mahasiswa->detailMahasiswa->program_studi ?? '-' }}</span>
                        </div>
                        {{-- Nomor HP --}}
                        <div class="info-grid-item md:col-span-1">
                            <span class="info-label"><i class="fas fa-phone"></i>Nomor HP</span>
                            <span class="info-value">{{ $mahasiswa->detailMahasiswa->nomor_hp ?? '-' }}</span>
                        </div>
                        {{-- Alamat --}}
                        <div class="info-grid-item md:col-span-2"> {{-- Full width --}}
                            <span class="info-label"><i class="fas fa-map-marker-alt"></i>Alamat</span>
                            <span class="info-value whitespace-pre-line">{{ $mahasiswa->detailMahasiswa->alamat ?? '-' }}</span> {{-- Allowed to wrap --}}
                        </div>
                    @endif
                    {{-- Role --}}
                    <div class="info-grid-item md:col-span-1">
                        <span class="info-label"><i class="fas fa-user-tag"></i>Role</span>
                        <span class="info-value">{{ $mahasiswa->role->name ?? '-' }}</span>
                    </div>
                    {{-- Akun Dibuat --}}
                    <div class="info-grid-item md:col-span-1">
                        <span class="info-label"><i class="fas fa-clock"></i>Akun Dibuat</span>
                        <span class="info-value">{{ $mahasiswa->created_at ? $mahasiswa->created_at->format('d M Y, H:i') : '-' }}</span>
                    </div>
                </div>

                <div class="mt-10 flex justify-center">
                    <a href="{{ route('admin.mahasiswa.edit', $mahasiswa->id) }}"
                    class="action-button edit-button inline-flex items-center shadow-lg">
                        <i class="fas fa-pencil-alt mr-2"></i>Edit Profil Mahasiswa
                    </a>
                </div>
            </div>
        </main>

        {{-- Include the admin footer --}}
        @include('admin.template.footer')
    </body>
    </html>
