<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Perusahaan - {{ Auth::user()->name ?? Auth::user()->username }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css" xintegrity="sha512-SzlrxWUlpfuzQ+pcUCosxcglQRNAq/DZjVsC0lE40xsADsfeQoEypE+enwcOiGjk/bSuGGKHEyjSoQ1zVisanQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    
    {{-- Toastify-JS CDN links --}}
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/toastify-js"></script>

    <style>
        body {
            background-color: #f7f8fc;
            font-family: 'Inter', sans-serif; /* Consistent font */
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
        .info-label { /* Common style for all labels */
            color: #6b7280; /* Gray-500 */
            font-size: 0.9rem; /* Adjusted for better hierarchy */
            font-weight: 600; /* Semi-bold */
            display: flex;
            align-items: center;
            margin-bottom: 0.25rem; /* Space between label and value */
        }
        .info-label i {
            margin-right: 0.6rem; /* More space for icon */
            color: #4f46e5; /* Indigo-600 for icons */
            font-size: 1.1em; /* Slightly larger icon */
        }
        .info-value { /* Common style for all values */
            color: #111827;
            font-weight: 500;
            font-size: 1rem; /* Standard font size for content */
            line-height: 1.5; /* Better line spacing */
        }
        .info-grid-item { /* Unified class for all detail items */
            padding: 0.5rem 0; /* Vertical spacing between items */
            display: flex;
            flex-direction: column; /* Label on top, value below */
        }

        .whitespace-pre-line {
            white-space: pre-line;
        }

        .action-button {
            transition: all 0.2s ease-in-out;
            padding: 0.7rem 1.5rem; /* Increased padding */
            font-size: 0.9rem; /* Slightly larger font */
            font-weight: 600; /* Semi-bold */
            border-radius: 0.6rem; /* More rounded */
            display: inline-flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        .action-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
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

    <main class="max-w-lg mx-auto px-3 py-8 mt-20">

        <div class="profile-header text-center">
            <h1 class="text-2xl sm:text-3xl font-bold">Profil Perusahaan</h1>
        </div>

        <div class="info-card">

            <div class="flex justify-center mb-8">
                @if (Auth::user()->company && Auth::user()->company->logo_path && Storage::disk('public')->exists(Auth::user()->company->logo_path))
                    <img src="{{ asset('storage/' . Auth::user()->company->logo_path) }}" alt="Logo Perusahaan {{ Auth::user()->company->nama_perusahaan }}"
                         class="profile-picture">
                @else
                    <img src="https://placehold.co/160x160/f0f0f0/333333?text=Logo+Perusahaan"
                         alt="Logo Perusahaan Placeholder"
                         class="profile-picture">
                @endif
            </div>

            {{-- Centered Name and Username --}}
            <div class="text-center">
                <h1 class="text-3xl font-bold text-gray-800 mt-4">{{ $company->nama_perusahaan ?? 'Nama Perusahaan' }}</h1>
                <p class="text-gray-500 text-sm">{{ '@'.(Auth::user()->username ?? 'username') }}</p>
            </div>

            @if (Auth::user())
                <div class="flex items-center justify-center space-x-2 mt-4 mb-4">
                    {{-- Company Role Tag --}}
                    <span class="inline-flex items-center bg-blue-100 text-blue-700 text-xs font-semibold px-3 py-1 rounded-full">
                        <i class="fas fa-user-shield mr-1"></i>
                        {{ Str::ucfirst(Auth::user()->role->name ?? 'Perusahaan') }}
                    </span>

                    {{-- Website Link (if available and not already displayed) --}}
                    @if (Auth::user()->company && Auth::user()->company->website)
                        <a href="{{ Auth::user()->company->website }}" target="_blank" rel="noopener noreferrer"
                           class="inline-flex items-center bg-blue-600 text-white text-xs font-semibold px-3 py-1 rounded-full hover:bg-blue-700 transition duration-300 ease-in-out shadow-md">
                            <i class="fas fa-globe mr-1"></i>
                            Kunjungi Website
                        </a>
                    @endif
                </div>
            @endif

            {{-- Company Description - Moved to a more prominent spot --}}
            @if(isset($company) && $company->deskripsi)
                <div class="mb-8 p-4 bg-gray-50 rounded-lg">
                    <h3 class="text-sm font-semibold text-gray-600 mb-2 flex items-center"><i class="fas fa-info-circle mr-1"></i> Deskripsi Perusahaan</h3>
                    <p class="text-gray-800 whitespace-pre-line text-sm leading-relaxed">{{ $company->deskripsi }}</p>
                </div>
            @endif

            {{-- REMOVED THE OLD ALERT HERE --}}
            {{-- @if (session('success'))
                <div class="bg-green-50 border-l-4 border-green-400 text-green-700 p-4 rounded-md my-6" role="alert">
                    <div class="flex">
                        <div class="py-1"><i class="fas fa-check-circle fa-lg mr-3 text-green-500"></i></div>
                        <div>
                            <p class="font-bold">Sukses!</p>
                            <p class="text-sm">{{ session('success') }}</p>
                        </div>
                    </div>
                </div>
            @endif --}}

            {{-- Consolidated Details Section - All items in one grid, no internal headings or borders --}}
            <div class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-2">

                {{-- Email Perusahaan --}}
                <div class="info-grid-item md:col-span-1">
                    <span class="info-label"><i class="fas fa-envelope"></i>Email Perusahaan</span>
                    <span class="info-value">
                        @if(Auth::user()->company && Auth::user()->company->email_perusahaan)
                            {{ Auth::user()->company->email_perusahaan }}
                        @else
                            -
                        @endif
                    </span>
                </div>

                {{-- Telepon --}}
                @if(isset($company) && $company->id)
                    <div class="info-grid-item md:col-span-1">
                        <span class="info-label"><i class="fas fa-phone"></i>Telepon</span>
                        <span class="info-value">{{ $company->telepon ?? '-' }}</span>
                    </div>
                @endif

                {{-- Industri --}}
                @if(isset($company) && $company->industri)
                    <div class="info-grid-item md:col-span-1">
                        <span class="info-label"><i class="fas fa-industry"></i>Industri</span>
                        <span class="info-value">{{ $company->industri }}</span>
                    </div>
                @endif

                {{-- Ukuran Perusahaan --}}
                @if(isset($company) && $company->ukuran_perusahaan)
                    <div class="info-grid-item md:col-span-1">
                        <span class="info-label"><i class="fas fa-users"></i>Ukuran Perusahaan</span>
                        <span class="info-value">{{ $company->ukuran_perusahaan }}</span>
                    </div>
                @endif

                {{-- Alamat (always full width) --}}
                @if(isset($company) && $company->id)
                    <div class="info-grid-item md:col-span-2">
                        <span class="info-label"><i class="fas fa-map-marker-alt"></i>Alamat</span>
                        <span class="info-value">{{ $company->alamat ?? '-' }}</span>
                    </div>
                @endif

                {{-- Kota --}}
                @if(isset($company) && $company->id)
                    <div class="info-grid-item md:col-span-1">
                        <span class="info-label"><i class="fas fa-city"></i>Kota</span>
                        <span class="info-value">{{ $company->kota ?? '-' }}</span>
                    </div>
                @endif

                {{-- Provinsi --}}
                @if(isset($company) && $company->id)
                    <div class="info-grid-item md:col-span-1">
                        <span class="info-label"><i class="fas fa-map-marked-alt"></i>Provinsi</span>
                        <span class="info-value">{{ $company->provinsi ?? '-' }}</span>
                    </div>
                @endif

                {{-- Kode Pos --}}
                @if(isset($company) && $company->id)
                    <div class="info-grid-item md:col-span-1">
                        <span class="info-label"><i class="fas fa-mail-bulk"></i>Kode Pos</span>
                        <span class="info-value">{{ $company->kode_pos ?? '-' }}</span>
                    </div>
                @endif

                {{-- Status Kerjasama --}}
                @if(isset($company) && $company->id)
                    <div class="info-grid-item md:col-span-1">
                        <span class="info-label"><i class="fas fa-handshake"></i>Status Kerjasama</span>
                        <span class="info-value px-2 py-1 text-xs font-semibold leading-tight rounded-full
                            @if($company->status_kerjasama == 'Aktif') bg-green-100 text-green-700
                            @elseif($company->status_kerjasama == 'Non-Aktif') bg-red-100 text-red-700
                            @else bg-yellow-100 text-yellow-700 @endif">
                            {{ $company->status_kerjasama ?? '-' }}
                        </span>
                    </div>
                @endif

                {{-- Email Terverifikasi Akun (Relates to User, not Company data directly) --}}
                <div class="info-grid-item md:col-span-1">
                    <span class="info-label"><i class="fas fa-user-check"></i>Email Terverifikasi Akun</span>
                    <span class="info-value">
                        @if(Auth::user()->email_verified_at)
                            <span class="text-green-600 flex items-center">
                                <i class="fas fa-check-circle mr-1"></i> Terverifikasi
                            </span>
                            <span class="text-xs text-gray-400 block ml-6">({{ Auth::user()->email_verified_at->isoFormat('D MMMM WWWW') }})</span>
                        @else
                            <span class="text-red-600 flex items-center">
                                <i class="fas fa-times-circle mr-1"></i> Belum
                            </span>
                        @endif
                    </span>
                </div>

                {{-- Terakhir Diperbarui Akun (Relates to User, not Company data directly) --}}
                <div class="info-grid-item md:col-span-1">
                    <span class="info-label"><i class="fas fa-history"></i>Terakhir Diperbarui Akun</span>
                    <span class="info-value">{{ Auth::user()->updated_at ? Auth::user()->updated_at->diffForHumans() : '-' }}</span>
                </div>

            </div>

            <div class="mt-10 flex justify-end">
                <a href="{{ route('perusahaan.profile.edit2') }}"
                   class="action-button edit-button inline-flex items-center text-white shadow-lg">
                    <i class="fas fa-pencil-alt mr-2"></i>Edit Profil
                </a>
            </div>
        </div>
    </main>

    {{-- Toastify-JS Integration (Paste this script block here, just before </body>) --}}
    <script>
        // Display success message
        @if (session('success'))
            Toastify({
                text: "{{ session('success') }}",
                duration: 3000, // 3 seconds
                newWindow: true,
                close: true,
                gravity: "top", // `top` or `bottom`
                position: "right", // `left`, `center` or `right`
                stopOnFocus: true, // Prevents dismissing on hover
                style: {
                    background: "linear-gradient(to right, #4CAF50, #66BB6A)", // Green gradient
                    borderRadius: "0.6rem", // Tailored to your form-card rounded-lg
                    boxShadow: "0 4px 12px rgba(0,0,0,0.15)", // A subtle shadow
                    padding: "1rem 1.5rem" // Good padding
                },
                offset: { // Offset from the corner
                    x: 20, // horizontal axis - can be a number or a string indicating unity. eg: "2em"
                    y: 20 // vertical axis - can be a number or a string indicating unity. eg: "2em"
                },
                onClick: function(){} // Callback after click
            }).showToast();
        @endif

        // Display error message (e.g., from controller catches)
        @if (session('error'))
            Toastify({
                text: "{{ session('error') }}",
                duration: 5000, // Longer duration for errors
                newWindow: true,
                close: true,
                gravity: "top",
                position: "right",
                stopOnFocus: true,
                style: {
                    background: "linear-gradient(to right, #EF4444, #DC2626)", // Red gradient
                    borderRadius: "0.6rem",
                    boxShadow: "0 4px 12px rgba(0,0,0,0.15)",
                    padding: "1rem 1.5rem"
                },
                offset: {
                    x: 20,
                    y: 20
                },
                onClick: function(){}
            }).showToast();
        @endif

        {{-- Validation errors typically appear on the form itself, not after redirect.
             If you have a scenario where validation errors are redirected here,
             you'd uncomment and use the following, but it's less common. --}}
        {{-- @if ($errors->any())
            @foreach ($errors->all() as $error)
                Toastify({
                    text: "{{ $error }}",
                    duration: 5000,
                    newWindow: true,
                    close: true,
                    gravity: "top",
                    position: "right",
                    stopOnFocus: true,
                    style: {
                        background: "linear-gradient(to right, #F59E0B, #D97706)", // Orange/Amber gradient
                        borderRadius: "0.6rem",
                        boxShadow: "0 4px 12px rgba(0,0,0,0.15)",
                        padding: "1rem 1.5rem"
                    },
                    offset: {
                        x: 20,
                        y: 20 + {{ $loop->index * 70 }}
                    },
                    onClick: function(){}
                }).showToast();
            @endforeach
        @endif --}}
    </script>
</body>
</html>