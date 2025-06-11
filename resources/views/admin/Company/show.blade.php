<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Perusahaan - {{ $company->nama_perusahaan ?? 'Perusahaan' }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    {{-- Font Awesome for icons --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css" xintegrity="sha512-SzlrxWUlpfuzQ+pcUCosxcglQRNAq/DZjVsC0lE40xsADsfeQoEypE+enwcOiGjk/bSuGGKHEyjSoQ1zVisanQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>
        body {
            font-family: 'Inter', sans-serif; /* Menggunakan font Inter */
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
        .logo-picture {
            width: 8rem;
            height: 8rem;
            object-fit: cover;
            border-radius: 0.75rem; /* Rounded corners for the logo */
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
            padding-top: 6rem; /* Adjusted padding-top to accommodate logo */
            position: relative;
            z-index: 5;
            /* Removed text-align: center here, individual items will handle alignment */
        }
        /* Adjusted info-label for consistent styling */
        .info-label {
            color: #6b7280;
            font-size: 0.875rem;
            display: flex;
            align-items: center;
            margin-bottom: 0.25rem;
            white-space: nowrap; /* Prevent label text from wrapping */
        }
        .info-label i {
            margin-right: 0.5rem;
            color: #9ca3af;
        }
        /* Adjusted info-value for left alignment and overflow */
        .info-value {
            color: #111827;
            font-weight: 500;
            font-size: 1rem;
            white-space: nowrap; /* Prevent value text from wrapping by default */
            overflow: hidden;
            text-overflow: ellipsis;
            text-align: left; /* Values now align left by default */
            width: 100%; /* Ensure it takes full width for text-align to apply */
        }
        /* Specific override for address/description where wrapping is allowed */
        .info-value.whitespace-pre-line {
            white-space: pre-line; /* Allow pre-line for description */
            overflow: visible; /* Override hidden to show full description */
            text-overflow: clip; /* Override ellipsis for full description */
            text-align: left; /* Ensure it's left-aligned */
        }

        /* Adjusted info-grid-item to stack label and value, and align content to start */
        .info-grid-item {
            padding: 0.5rem 0;
            display: flex;
            flex-direction: column; /* Stack label on top of value */
            align-items: flex-start; /* Align label and value to the left */
            /* Removed justify-content and flex-wrap as they're not needed for column direction */
        }
        /* No special full-width styles needed for .info-grid-item itself, as it's already column-based. */


        .action-button {
            transition: all 0.2s ease-in-out;
            padding: 0.625rem 1.25rem;
            font-size: 0.875rem;
            border-radius: 0.5rem;
            color: white; /* Ensure text color is white */
            display: inline-flex;
            align-items: center;
            justify-content: center;
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
    @include('admin.template.navbar') {{-- Changed to admin navbar as it's admin/Company/show --}}

    <main class="max-w-lg mx-auto px-3 py-8 mt-20"> {{-- max-w-lg for content width --}}

        <div class="profile-header text-center">
            <h1 class="text-2xl sm:text-3xl font-bold">Detail Perusahaan</h1>
        </div>

        <div class="info-card">

            <div class="flex justify-center mb-8">
                @if($company->logo_path && Storage::disk('public')->exists($company->logo_path))
                    <img src="{{ asset('storage/' . $company->logo_path) }}" alt="Logo Perusahaan" class="logo-picture">
                @else
                    <img src="https://ui-avatars.com/api/?name={{ urlencode($company->nama_perusahaan) }}&size=170&background=2563EB&color=fff" alt="Logo Default" class="logo-picture">
                @endif
            </div>

            {{-- Centered Company Name and About link --}}
            <div class="text-center">
                <h1 class="text-3xl font-bold mt-4">{{ $company->nama_perusahaan ?? 'Nama Perusahaan' }}</h1>
                <p class="text-gray-500 text-sm">
                    {{-- Displaying 'about' here instead of 'website' --}}
                    @if ($company->about)
                        <a href="{{ $company->about }}" target="_blank" rel="noopener noreferrer" class="text-blue-500 hover:underline">
                            {{ $company->about }}
                        </a>
                    @else
                        About us tidak tersedia
                    @endif
                </p>
            </div>

            {{-- Company Description - Placed outside the grid for full width --}}
            @if(isset($company) && $company->deskripsi)
                <div class="my-8 p-4 bg-gray-50 rounded-lg text-left">
                    <h3 class="text-sm font-semibold text-gray-600 mb-2 flex items-center"><i class="fas fa-info-circle mr-1"></i> Deskripsi Perusahaan</h3>
                    <p class="text-gray-800 whitespace-pre-line text-sm leading-relaxed">{{ $company->deskripsi }}</p>
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

            {{-- Consolidated Details Section - All items in a two-column grid with left alignment --}}
            <div class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-2 text-left">

                {{-- Email Perusahaan --}}
                <div class="info-grid-item md:col-span-1">
                    <span class="info-label"><i class="fas fa-envelope"></i>Email Perusahaan</span>
                    <span class="info-value">{{ $company->email_perusahaan ?? '-' }}</span>
                </div>

                {{-- Telepon --}}
                <div class="info-grid-item md:col-span-1">
                    <span class="info-label"><i class="fas fa-phone"></i>Telepon</span>
                    <span class="info-value">{{ $company->telepon ?? '-' }}</span>
                </div>

                {{-- Website --}}
                {{-- Keeping Website field in the grid as a separate item if needed. If not, remove this div. --}}
                <div class="info-grid-item md:col-span-1">
                    <span class="info-label"><i class="fas fa-globe"></i>Website</span>
                    <span class="info-value">
                        @if ($company->website)
                            <a href="{{ $company->website }}" target="_blank" rel="noopener noreferrer"
                               class="inline-flex items-center text-blue-500 hover:underline">
                                {{ $company->website }}
                            </a>
                        @else
                            -
                        @endif
                    </span>
                </div>

                {{-- Kode Pos --}}
                <div class="info-grid-item md:col-span-1">
                    <span class="info-label"><i class="fas fa-mail-bulk"></i>Kode Pos</span>
                    <span class="info-value">{{ $company->kode_pos ?? '-' }}</span>
                </div>

                {{-- Alamat (always full width in this layout) --}}
                <div class="info-grid-item md:col-span-2">
                    <span class="info-label"><i class="fas fa-map-marker-alt"></i>Alamat</span>
                    <span class="info-value whitespace-pre-line">{{ $company->alamat ?? '-' }}</span> {{-- Allowed to wrap --}}
                </div>

                {{-- Kota --}}
                <div class="info-grid-item md:col-span-1">
                    <span class="info-label"><i class="fas fa-city"></i>Kota</span>
                    <span class="info-value">{{ $company->kota ?? '-' }}</span>
                </div>

                {{-- Provinsi --}}
                <div class="info-grid-item md:col-span-1">
                    <span class="info-label"><i class="fas fa-map-marked-alt"></i>Provinsi</span>
                    <span class="info-value">{{ $company->provinsi ?? '-' }}</span>
                </div>

                {{-- Status Kerjasama (full width to keep badge from wrapping) --}}
                <div class="info-grid-item md:col-span-2">
                    <span class="info-label"><i class="fas fa-handshake"></i>Status Kerjasama</span>
                    <span class="info-value">
                        <span class="px-2 py-1 text-xs font-semibold leading-tight rounded-full
                            @if($company->status_kerjasama == 'Aktif') bg-green-100 text-green-700
                            @elseif($company->status_kerjasama == 'Non-Aktif') bg-red-100 text-red-700
                            @else bg-yellow-100 text-yellow-700 @endif">
                            {{ $company->status_kerjasama ?? '-' }}
                        </span>
                    </span>
                </div>
            </div>

            <div class="mt-10 flex justify-center">
                <a href="{{ route('admin.perusahaan.edit', $company->id) }}" {{-- Corrected route for company edit --}}
                   class="action-button edit-button inline-flex items-center shadow-lg">
                    <i class="fas fa-pencil-alt mr-2"></i>Edit Profil Perusahaan
                </a>
            </div>
        </div>
    </main>
    @include('admin.template.footer') {{-- Assuming admin footer for admin view --}}
</body>
</html>
