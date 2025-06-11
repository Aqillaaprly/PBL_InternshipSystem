<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profil Perusahaan - {{ Auth::user()->name ?? Auth::user()->username }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css" xintegrity="sha512-SzlrxWUlpfuzQ+pcUCosxcglQRNAq/DZjVsC0lE40xsADsfeQoEypE+enwcOiGjk/bSuGGKHEyjSoQ1zVisanQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>
        body {
            background-color: #f7f8fc;
            font-family: 'Inter', sans-serif; /* Consistent font */
        }
        .form-card {
            background-color: white;
            border-radius: 1rem; /* rounded-2xl */
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04); /* shadow-xl */
            padding: 2.5rem; /* Increased padding */
        }
        .form-section-title {
            font-size: 1.5rem; /* text-xl */
            font-weight: 700; /* font-semibold */
            color: #1f2937; /* gray-800 */
            margin-bottom: 1.5rem; /* mb-6 */
            padding-bottom: 0.75rem; /* pb-3 */
            border-bottom: 1px solid #e5e7eb; /* border-gray-200 */
        }
        .input-group {
            margin-bottom: 1.5rem; /* mb-6 */
        }
        .input-label {
            display: block;
            font-size: 0.9rem; /* text-sm */
            font-weight: 600; /* font-medium */
            color: #4b5563; /* gray-600 */
            margin-bottom: 0.5rem; /* mb-2 */
        }
        .form-input, .form-textarea, .form-select {
            display: block;
            width: 100%;
            padding: 0.75rem 1rem; /* py-3 px-4 */
            font-size: 0.9rem; /* text-sm */
            color: #1f2937; /* text-gray-800 */
            background-color: #f9fafb; /* bg-gray-50 */
            border: 1px solid #d1d5db; /* border-gray-300 */
            border-radius: 0.6rem; /* rounded-lg */
            box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05); /* shadow-sm */
            transition: border-color 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
        }
        .form-input:focus, .form-textarea:focus, .form-select:focus {
            outline: none;
            border-color: #4f46e5; /* focus:border-indigo-500 */
            background-color: white;
            box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.2); /* focus:ring-indigo-500/20 */
        }
        .error-message {
            color: #ef4444; /* text-red-500 */
            font-size: 0.75rem; /* text-xs */
            margin-top: 0.25rem; /* mt-1 */
        }
        .profile-picture-edit-area {
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-bottom: 2rem; /* mb-8 */
            padding-bottom: 1.5rem;
            border-bottom: 1px solid #e5e7eb;
        }
        .profile-picture-preview-edit {
            width: 9rem; /* w-36 */
            height: 9rem; /* h-36 */
            border-radius: 9999px; /* rounded-full */
            object-fit: cover;
            border: 4px solid white;
            box-shadow: 0 6px 12px rgba(0,0,0,0.15);
            margin-bottom: 1rem; /* mb-4 */
        }
        .file-input-styled-btn {
            display: inline-flex;
            align-items: center;
            padding: 0.7rem 1.4rem; /* py-2.5 px-5 */
            background-color: #e0e7ff; /* indigo-100 */
            color: #3730a3; /* indigo-800 */
            border-radius: 0.6rem; /* rounded-lg */
            font-size: 0.9rem; /* text-sm */
            font-weight: 600; /* font-medium */
            cursor: pointer;
            transition: background-color 0.2s ease;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .file-input-styled-btn:hover {
            background-color: #c7d2fe; /* indigo-200 */
        }
        .action-button {
            transition: all 0.2s ease-in-out;
            padding: 0.7rem 1.5rem; /* py-2.5 px-6 */
            font-size: 0.9rem; /* text-sm */
            font-weight: 600; /* font-medium */
            border-radius: 0.6rem; /* rounded-lg */
            display: inline-flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        .action-button:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        }
        .save-button {
             background-image: linear-gradient(to right, #4f46e5, #7c3aed);
             color: white;
        }
        .save-button:hover {
            background-image: linear-gradient(to right, #4338ca, #6d28d9);
        }
        .cancel-button {
            background-color: #e5e7eb; /* bg-gray-200 */
            color: #374151; /* text-gray-700 */
        }
        .cancel-button:hover {
            background-color: #d1d5db; /* bg-gray-300 */
        }
    </style>
</head>
<body class="text-gray-800">
    @include('perusahaan.template.navbar')

    <main class="max-w-2xl mx-auto px-4 py-10 mt-20 mb-10">
        <div class="form-card">
            <div class="text-center mb-8">
                <h1 class="text-2xl font-bold text-gray-800">Edit Profil Perusahaan</h1>
                <p class="text-sm text-gray-500">Perbarui informasi dan pengaturan akun perusahaan Anda.</p>
            </div>

            {{-- Validation errors handled by Toastify-JS below --}}
            {{-- @if ($errors->any())
                <div class="bg-red-50 border-l-4 border-red-400 text-red-700 p-4 rounded-lg mb-6" role="alert">
                    <div class="flex">
                        <div class="py-1"><i class="fas fa-exclamation-triangle fa-lg mr-3 text-red-500"></i></div>
                        <div>
                            <p class="font-bold">Oops! Terdapat Kesalahan Validasi:</p>
                            <ul class="mt-1 list-disc list-inside text-sm">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            @endif --}}

            <form method="POST" action="{{ route('perusahaan.profile.update2') }}" enctype="multipart/form-data" class="space-y-8">
                @csrf
                @method('PUT')

                {{-- Foto Profil (Logo Perusahaan) --}}
                <div class="profile-picture-edit-area">
                    <img id="profile_picture_preview_edit"
                         src="{{ Auth::user()->company && Auth::user()->company->logo_path && Storage::disk('public')->exists(Auth::user()->company->logo_path) ? asset('storage/' . Auth::user()->company->logo_path) : 'https://placehold.co/144x144/f0f0f0/333333?text=Logo+Perusahaan' }}"
                         alt="Logo Perusahaan Saat Ini" class="profile-picture-preview-edit">
                    <label for="logo_input" class="file-input-styled-btn">
                        <i class="fas fa-upload mr-2"></i> Unggah Logo Baru
                    </label>
                    <input type="file" name="logo" id="logo_input" accept="image/png, image/jpeg, image/jpg, image/gif, image/svg+xml" class="hidden">
                    @error('logo') <p class="error-message text-center">{{ $message }}</p> @enderror
                    <p class="text-xs text-gray-500 mt-2">Format: JPG, PNG, GIF, SVG. Maks: 2MB. Dimensi optimal: 144x144px.</p>
                </div>

                <div>
                    <div class="form-section-title">Informasi Perusahaan</div>
                    <div class="input-group">
                        <label for="nama_perusahaan" class="input-label">Nama Perusahaan <span class="text-red-500">*</span></label>
                        <input type="text" name="nama_perusahaan" id="nama_perusahaan" value="{{ old('nama_perusahaan', $company->nama_perusahaan ?? '') }}" required class="form-input @error('nama_perusahaan') border-red-500 @enderror">
                        @error('nama_perusahaan') <p class="error-message">{{ $message }}</p> @enderror
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6">
                        <div class="input-group">
                            <label for="telepon" class="input-label">Telepon Perusahaan <span class="text-red-500">*</span></label>
                            <input type="text" name="telepon" id="telepon" value="{{ old('telepon', $company->telepon ?? '') }}" required class="form-input @error('telepon') border-red-500 @enderror" placeholder="Contoh: +6281234567890">
                            @error('telepon') <p class="error-message">{{ $message }}</p> @enderror
                        </div>
                        <div class="input-group">
                            <label for="email_perusahaan" class="input-label">Email Perusahaan <span class="text-red-500">*</span></label>
                            <input type="email" name="email_perusahaan" id="email_perusahaan" value="{{ old('email_perusahaan', $company->email_perusahaan ?? '') }}" required class="form-input @error('email_perusahaan') border-red-500 @enderror" placeholder="Contoh: info@perusahaan.com">
                            @error('email_perusahaan') <p class="error-message">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="input-group">
                        <label for="website" class="input-label">Website Perusahaan <span class="text-gray-500 text-xs">(Opsional)</span></label>
                        <input type="url" name="website" id="website" value="{{ old('website', $company->website ?? '') }}" class="form-input @error('website') border-red-500 @enderror" placeholder="Contoh: https://www.perusahaan.com">
                        @error('website') <p class="error-message">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div>
                    <div class="form-section-title">Detail & Alamat Perusahaan</div>
                    <div class="input-group">
                        <label for="deskripsi" class="input-label">Deskripsi Perusahaan <span class="text-gray-500 text-xs">(Opsional)</span></label>
                        <textarea name="deskripsi" id="deskripsi" rows="4" class="form-textarea @error('deskripsi') border-red-500 @enderror" placeholder="Ceritakan tentang perusahaan Anda, visi, misi, dan nilai-nilai.">{{ old('deskripsi', $company->deskripsi ?? '') }}</textarea>
                        @error('deskripsi') <p class="error-message">{{ $message }}</p> @enderror
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6">
                        <div class="input-group">
                            <label for="industri" class="input-label">Industri <span class="text-gray-500 text-xs">(Opsional)</span></label>
                            <input type="text" name="industri" id="industri" value="{{ old('industri', $company->industri ?? '') }}" class="form-input @error('industri') border-red-500 @enderror" placeholder="Contoh: Teknologi, Manufaktur, Jasa">
                            @error('industri') <p class="error-message">{{ $message }}</p> @enderror
                        </div>
                        <div class="input-group">
                            <label for="ukuran_perusahaan" class="input-label">Ukuran Perusahaan <span class="text-gray-500 text-xs">(Opsional)</span></label>
                            <input type="text" name="ukuran_perusahaan" id="ukuran_perusahaan" value="{{ old('ukuran_perusahaan', $company->ukuran_perusahaan ?? '') }}" class="form-input @error('ukuran_perusahaan') border-red-500 @enderror" placeholder="Contoh: 1-50 karyawan, 51-200 karyawan">
                            @error('ukuran_perusahaan') <p class="error-message">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="input-group">
                        <label for="alamat" class="input-label">Alamat Lengkap <span class="text-gray-500 text-xs">(Opsional)</span></label>
                        <textarea name="alamat" id="alamat" rows="2" class="form-textarea @error('alamat') border-red-500 @enderror" placeholder="Contoh: Jl. Sudirman No. 123">{{ old('alamat', $company->alamat ?? '') }}</textarea>
                        @error('alamat') <p class="error-message">{{ $message }}</p> @enderror
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6">
                        <div class="input-group">
                            <label for="kota" class="input-label">Kota <span class="text-gray-500 text-xs">(Opsional)</span></label>
                            <input type="text" name="kota" id="kota" value="{{ old('kota', $company->kota ?? '') }}" class="form-input @error('kota') border-red-500 @enderror" placeholder="Contoh: Jakarta">
                            @error('kota') <p class="error-message">{{ $message }}</p> @enderror
                        </div>
                        <div class="input-group">
                            <label for="provinsi" class="input-label">Provinsi <span class="text-gray-500 text-xs">(Opsional)</span></label>
                            <input type="text" name="provinsi" id="provinsi" value="{{ old('provinsi', $company->provinsi ?? '') }}" class="form-input @error('provinsi') border-red-500 @enderror" placeholder="Contoh: DKI Jakarta">
                            @error('provinsi') <p class="error-message">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="input-group">
                        <label for="kode_pos" class="input-label">Kode Pos <span class="text-gray-500 text-xs">(Opsional)</span></label>
                        <input type="text" name="kode_pos" id="kode_pos" value="{{ old('kode_pos', $company->kode_pos ?? '') }}" class="form-input @error('kode_pos') border-red-500 @enderror" placeholder="Contoh: 12345">
                        @error('kode_pos') <p class="error-message">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="mt-10 flex flex-col-reverse sm:flex-row sm:justify-end sm:space-x-3">
                    <a href="{{ route('perusahaan.profile.perusahaanProfile2') }}" class="action-button cancel-button mt-3 sm:mt-0 w-full sm:w-auto">
                        Batal
                    </a>
                    <button type="submit" class="action-button save-button w-full sm:w-auto">
                        <i class="fas fa-save mr-2"></i>Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </main>

    @include('perusahaan.template.footer')

    <script>
        const logoInput = document.getElementById('logo_input');
        const logoPreview = document.getElementById('profile_picture_preview_edit'); // Reusing ID for consistency

        if (logoInput && logoPreview) {
            logoInput.addEventListener('change', function(event) {
                const file = event.target.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        logoPreview.src = e.target.result;
                    }
                    reader.readAsDataURL(file);
                }
            });
        }

        // --- Toastify-JS Integration ---
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
                        y: 20 + {{ $loop->index * 70 }} // Stagger multiple toasts if many errors
                    },
                    onClick: function(){}
                }).showToast();
            @endforeach
        @endif
        // --- End Toastify-JS Integration ---

    </script>
</body>
</html>