<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Admin - {{ Auth::user()->name ?? Auth::user()->username }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css" xintegrity="sha512-SzlrxWUlpfuzQ+pcUCosxcglQRNAq/DZjVsC0lE40xsADsfeQoEypE+enwcOiGjk/bSuGGKHEyjSoQ1zVisanQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    {{-- Toastify-JS CDN links for notifications --}}
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/toastify-js"></script>

    <style>
        body {
            background-color: #f7f8fc;
            font-family: 'Inter', sans-serif; /* Added for consistency */
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
        }
        .info-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
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
    @include('admin.template.navbar')

    <main class="max-w-lg mx-auto px-3 py-8 mt-20">

        <div class="profile-header text-center">
            <h1 class="text-2xl sm:text-3xl font-bold">Profile</h1>
        </div>

        <div class="info-card text-center">

            <div class="flex justify-center">
                @if (Auth::user()->profile_picture && Storage::disk('public')->exists(Auth::user()->profile_picture))
                    <img src="{{ asset('storage/' . Auth::user()->profile_picture) }}" alt="Foto Profil {{ Auth::user()->name }}"
                         class="profile-picture">
                @else
                    <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name ?? Auth::user()->username) }}&size=170&background=1D4ED8&color=fff&font-size=0.4&bold=true"
                         alt="Avatar {{ Auth::user()->name }}"
                         class="profile-picture">
                @endif
            </div>

            <h1 class="text-3xl font-bold text-gray-800 mt-4">{{ Auth::user()->name ?? 'Nama Admin' }}</h1>
            <p class="text-gray-500 text-sm">{{ '@'.(Auth::user()->username ?? 'username') }}</p>
            <p class="inline-block bg-blue-100 text-blue-700 text-xs font-semibold px-3 py-1 rounded-full mt-2">
                <i class="fas fa-user-shield mr-1"></i>{{ Str::ucfirst(Auth::user()->role->name ?? 'Administrator') }}
            </p>

            {{-- Removed the old success message div as it will be handled by Toastify --}}

            <div class="text-left mt-8">
                <div class="info-item">
                    <span class="info-label"><i class="fas fa-envelope"></i>Email</span>
                    <span class="info-value">{{ Auth::user()->email ?? '-' }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label"><i class="fas fa-user-check"></i>Email Terverifikasi</span>
                    <span class="info-value">
                        @if(Auth::user()->email_verified_at)
                            <span class="text-green-600 flex items-center justify-end">
                                <i class="fas fa-check-circle mr-1"></i> Terverifikasi
                            </span>
                            <span class="text-xs text-gray-400 block">({{ Auth::user()->email_verified_at->isoFormat('D MMM YYYY') }})</span>
                        @else
                            <span class="text-red-600 flex items-center justify-end">
                                <i class="fas fa-times-circle mr-1"></i> Belum
                            </span>
                        @endif
                    </span>
                </div>
                <div class="info-item">
                    <span class="info-label"><i class="fas fa-calendar-alt"></i>Bergabung Sejak</span>
                    <span class="info-value">{{ Auth::user()->created_at ? Auth::user()->created_at->isoFormat('D MMMM YYYY') : '-' }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label"><i class="fas fa-history"></i>Terakhir Diperbarui</span>
                    <span class="info-value">{{ Auth::user()->updated_at ? Auth::user()->updated_at->diffForHumans() : '-' }}</span>
                </div>
            </div>

            <div class="mt-10 flex justify-center">
                <a href="{{ route('admin.profile.edit') }}"
                   class="action-button edit-button inline-flex items-center text-white shadow-lg">
                    <i class="fas fa-pencil-alt mr-2"></i>Edit Profil
                </a>
            </div>
        </div>
    </main>

    {{-- Toastify-JS Integration --}}
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

        // Display validation errors (iterates through $errors->all())
        @if ($errors->any())
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
                        background: "linear-gradient(to right, #F59E0B, #D97706)", // Orange/Amber gradient for warnings/validation
                        borderRadius: "0.6rem",
                        boxShadow: "0 4px 12px rgba(0,0,0,0.15)",
                        padding: "1rem 1.5rem"
                    },
                    offset: {
                        x: 20,
                        y: 20 + {{ '$loop->index * 70' }} // Stagger multiple toasts if many errors
                    },
                    onClick: function(){}
                }).showToast();
            @endforeach
        @endif
    </script>
</body>
</html>
