<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profil - {{ $user->name ?? $user->username }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css" integrity="sha512-SzlrxWUlpfuzQ+pcUCosxcglQRNAq/DZjVsC0lE40xsADsfeQoEypE+enwcOiGjk/bSuGGKHEyjSoQ1zVisanQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>
        body {
            background-color: #f7f8fc;
        }
        .form-card {
            background-color: white;
            border-radius: 1rem;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
            padding: 2rem;
        }
        .form-section-title {
            font-size: 1.25rem;
            font-weight: 600;
            color: #1f2937;
            margin-bottom: 1.5rem;
            padding-bottom: 0.75rem;
            border-bottom: 1px solid #e5e7eb;
        }
        .input-group {
            margin-bottom: 1.5rem;
        }
        .input-label {
            display: block;
            font-size: 0.875rem;
            font-weight: 500;
            color: #4b5563;
            margin-bottom: 0.5rem;
        }
        .form-input {
            display: block;
            width: 100%;
            padding: 0.75rem 1rem;
            font-size: 0.875rem;
            color: #1f2937;
            background-color: #f9fafb;
            border: 1px solid #d1d5db;
            border-radius: 0.5rem;
            box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
            transition: border-color 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
        }
        .form-input:focus {
            outline: none;
            border-color: #4f46e5;
            background-color: white;
            box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.2);
        }
        .error-message {
            color: #ef4444;
            font-size: 0.75rem;
            margin-top: 0.25rem;
        }
        .profile-picture-edit-area {
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-bottom: 2rem;
        }
        .profile-picture-preview-edit {
            width: 9rem;
            height: 9rem;
            border-radius: 9999px;
            object-fit: cover;
            border: 4px solid white;
            box-shadow: 0 6px 12px rgba(0,0,0,0.15);
            margin-bottom: 1rem;
        }
        .file-input-styled-btn {
            display: inline-flex;
            align-items: center;
            padding: 0.625rem 1.25rem;
            background-color: #e0e7ff;
            color: #3730a3;
            border-radius: 0.5rem;
            font-size: 0.875rem;
            font-weight: 500;
            cursor: pointer;
            transition: background-color 0.2s ease;
        }
        .file-input-styled-btn:hover {
            background-color: #c7d2fe;
        }
        .action-button {
            transition: all 0.2s ease-in-out;
            padding: 0.625rem 1.5rem;
            font-size: 0.875rem;
            font-weight: 500;
            border-radius: 0.5rem;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }
        .action-button:hover {
            transform: translateY(-1px);
        }
        .save-button {
            background-image: linear-gradient(to right, #4f46e5, #7c3aed);
            color: white;
            box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1), 0 2px 4px -1px rgba(0,0,0,0.06);
        }
        .save-button:hover {
            background-image: linear-gradient(to right, #4338ca, #6d28d9);
            box-shadow: 0 10px 15px -3px rgba(0,0,0,0.1), 0 4px 6px -2px rgba(0,0,0,0.05);
        }
        .cancel-button {
            background-color: #e5e7eb;
            color: #374151;
        }
        .cancel-button:hover {
            background-color: #d1d5db;
        }
    </style>
</head>
<body class="text-gray-800">
@include('mahasiswa.template.navbar')

<main class="max-w-2xl mx-auto px-4 py-10 mt-20 mb-10">
    <div class="form-card">
        <div class="text-center mb-8">
            <h1 class="text-2xl font-bold text-gray-800">Edit Profil Anda</h1>
            <p class="text-sm text-gray-500">Perbarui informasi dan pengaturan akun Anda.</p>
        </div>

        @if ($errors->any() && !$errors->has('profile_picture'))
        <div class="bg-red-50 border-l-4 border-red-400 text-red-700 p-4 rounded-md mb-6" role="alert">
            <div class="flex">
                <div class="py-1"><i class="fas fa-exclamation-triangle fa-lg mr-3 text-red-500"></i></div>
                <div>
                    <p class="font-bold">Oops! Terdapat Kesalahan Validasi:</p>
                    <ul class="mt-1 list-disc list-inside text-sm">
                        @foreach ($errors->all() as $error)
                        @if ($error !== $errors->first('profile_picture'))
                        <li>{{ $error }}</li>
                        @endif
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
        @endif

        @if (session('success'))
        <div class="bg-green-50 border-l-4 border-green-400 text-green-700 p-4 rounded-md mb-6" role="alert">
            <div class="flex">
                <div class="py-1"><i class="fas fa-check-circle fa-lg mr-3 text-green-500"></i></div>
                <div>
                    <p class="font-bold">Sukses!</p>
                    <p class="text-sm">{{ session('success') }}</p>
                </div>
            </div>
        </div>
        @endif

        <form method="POST" action="{{ route('mahasiswa.profile.update') }}" enctype="multipart/form-data" class="space-y-8">
            @csrf
            @method('PUT')

            {{-- Foto Profil --}}
            <div class="profile-picture-edit-area">
                <img id="profile_picture_preview_edit"
                     src="{{ $user->profile_picture && Storage::disk('public')->exists($user->profile_picture) ? asset('storage/' . $user->profile_picture) : 'https://ui-avatars.com/api/?name=' . urlencode($user->name ?? $user->username) . '&size=144&background=1D4ED8&color=fff&font-size=0.4&bold=true' }}"
                     alt="Foto Profil Saat Ini" class="profile-picture-preview-edit">
                <label for="profile_picture_input" class="file-input-styled-btn">
                    <i class="fas fa-upload mr-2"></i> Unggah Foto Baru
                </label>
                <input type="file" name="profile_picture" id="profile_picture_input" accept="image/png, image/jpeg, image/jpg, image/gif, image/svg+xml" class="hidden">
                @error('profile_picture') <p class="error-message text-center">{{ $message }}</p> @enderror
                <p class="text-xs text-gray-500 mt-2">Format: JPG, PNG, GIF, SVG. Maks: 2MB.</p>
            </div>

            <div>
                <div class="form-section-title">Informasi Personal & Akun</div>
                <div class="input-group">
                    <label for="name" class="input-label">Nama Lengkap <span class="text-red-500">*</span></label>
                    <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" required class="form-input @error('name') border-red-500 @enderror">
                    @error('name') <p class="error-message">{{ $message }}</p> @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6">
                    <div class="input-group">
                        <label for="username" class="input-label">Username <span class="text-red-500">*</span></label>
                        <input type="text" name="username" id="username" value="{{ old('username', $user->username) }}" required class="form-input @error('username') border-red-500 @enderror">
                        @error('username') <p class="error-message">{{ $message }}</p> @enderror
                    </div>
                    <div class="input-group">
                        <label for="email" class="input-label">Email <span class="text-red-500">*</span></label>
                        <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" required class="form-input @error('email') border-red-500 @enderror">
                        @error('email') <p class="error-message">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            <div>
                <div class="form-section-title">Ubah Kata Sandi</div>
                <p class="text-xs text-gray-500 mb-4 -mt-4">Kosongkan semua field kata sandi jika tidak ingin mengubahnya.</p>
                <div class="input-group">
                    <label for="current_password" class="input-label">Kata Sandi Saat Ini</label>
                    <input type="password" name="current_password" id="current_password" class="form-input @error('current_password') border-red-500 @enderror" placeholder="Wajib diisi jika mengubah kata sandi">
                    @error('current_password') <p class="error-message">{{ $message }}</p> @enderror
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6">
                    <div class="input-group">
                        <label for="new_password" class="input-label">Kata Sandi Baru</label>
                        <input type="password" name="new_password" id="new_password" class="form-input @error('new_password') border-red-500 @enderror" placeholder="Minimal 8 karakter">
                        @error('new_password') <p class="error-message">{{ $message }}</p> @enderror
                    </div>
                    <div class="input-group">
                        <label for="new_password_confirmation" class="input-label">Konfirmasi Kata Sandi Baru</label>
                        <input type="password" name="new_password_confirmation" id="new_password_confirmation" class="form-input" placeholder="Ulangi kata sandi baru">
                    </div>
                </div>
            </div>

            <div class="mt-10 flex flex-col-reverse sm:flex-row sm:justify-end sm:space-x-3">
                <a href="{{ route('mahasiswa.profile') }}" class="action-button cancel-button mt-3 sm:mt-0 w-full sm:w-auto">
                    Batal
                </a>
                <button type="submit" class="action-button save-button w-full sm:w-auto">
                    <i class="fas fa-save mr-2"></i>Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</main>

@include('mahasiswa.template.footer')

<script>
    const profilePictureInput = document.getElementById('profile_picture_input');
    const profilePicturePreview = document.getElementById('profile_picture_preview_edit');

    if (profilePictureInput && profilePicturePreview) {
        profilePictureInput.addEventListener('change', function(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    profilePicturePreview.src = e.target.result;
                }
                reader.readAsDataURL(file);
            }
        });
    }
</script>
</body>
</html>
