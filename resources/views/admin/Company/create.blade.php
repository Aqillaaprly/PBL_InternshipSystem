<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Perusahaan Baru - Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    {{-- Font Awesome for icons --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css" xintegrity="sha512-SzlrxWUlpfuzQ+pcUCosxcglQRNAq/DZjVsC0lE40xsADsfeQoEypE+enwcOiGjk/bSuGGKHEyjSoQ1zVisanQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f7f8fc; /* Consistent background with other admin pages */
        }
        .form-container {
            background-color: white;
            padding: 2.5rem; /* Adjusted padding */
            border-radius: 1rem; /* Rounded corners */
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            max-width: 50rem; /* Increased max width for two columns */
            margin-left: auto;
            margin-right: auto;
        }
        .form-title {
            font-size: 2rem;
            font-weight: bold;
            color: #1a202c; /* Dark gray for titles */
            text-align: center;
            margin-bottom: 2rem;
        }
        .input-label {
            display: block;
            font-size: 0.875rem; /* text-sm */
            font-weight: 500; /* font-medium */
            color: #4b5563; /* gray-700 */
            margin-bottom: 0.25rem; /* Space between label and input */
        }
        .input-field {
            display: block;
            width: 100%;
            padding: 0.75rem 1rem;
            border: 1px solid #d1d5db; /* Gray-300 */
            border-radius: 0.5rem; /* Rounded-md */
            box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
            transition: all 0.2s ease-in-out;
        }
        .input-field:focus {
            border-color: #60a5fa; /* Blue-300 */
            outline: none;
            box-shadow: 0 0 0 3px rgba(96, 165, 250, 0.5);
        }
        /* Styling for submit button */
        .submit-button {
            background-image: linear-gradient(to right, #4f46e5, #7c3aed); /* Consistent gradient button */
            color: white;
            padding: 0.75rem 1.5rem;
            font-size: 1rem;
            border-radius: 0.5rem;
            transition: all 0.2s ease-in-out;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            width: auto; /* Allow button to size content */
        }
        .submit-button:hover {
            background-image: linear-gradient(to right, #4338ca, #6d28d9);
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }
        /* Styling for cancel button */
        .cancel-button {
            background-color: #e5e7eb; /* Gray-200 */
            color: #4b5563; /* Gray-700 */
            padding: 0.75rem 1.5rem;
            font-size: 1rem;
            border-radius: 0.5rem;
            transition: all 0.2s ease-in-out;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            width: auto;
        }
        .cancel-button:hover {
            background-color: #d1d5db; /* Gray-300 */
            transform: translateY(-1px);
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        }
        .error-message {
            color: #ef4444; /* Red-500 */
            font-size: 0.75rem; /* Text-xs */
            margin-top: 0.25rem;
        }
        .alert {
            padding: 1rem;
            border-radius: 0.5rem;
            margin-bottom: 1.5rem;
            border-left: 4px solid;
            font-size: 0.875rem; /* text-sm */
        }
        .alert-error {
            background-color: #fee2e2;
            border-color: #ef4444;
            color: #991b1b;
        }
        .alert-success {
            background-color: #d1fae5;
            border-color: #059669;
            color: #065f46;
        }
    </style>
</head>
<body class="bg-blue-50 text-gray-800"> {{-- Adjusted body background to match other admin pages --}}
    {{-- Include the admin navigation bar --}}
    @include('admin.template.navbar')

    <main class="min-h-screen flex items-center justify-center py-12 px-4 mt-20"> {{-- Centering form vertically --}}
        <div class="form-container">
            <h1 class="form-title">Tambah Perusahaan Baru</h1>

            {{-- Menampilkan error validasi dari $errors->any() --}}
            @if ($errors->any())
                <div class="alert alert-error mb-5" role="alert">
                    <strong class="font-bold">Oops! Ada beberapa masalah dengan input Anda:</strong>
                    <ul class="mt-2 list-disc list-inside text-sm">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            {{-- Menampilkan session error --}}
            @if (session('error'))
                <div class="alert alert-error mb-5" role="alert">
                    <strong class="font-bold">Error!</strong>
                    <span class="block sm:inline">{{ session('error') }}</span>
                </div>
            @endif
            {{-- Menampilkan session success --}}
            @if (session('success'))
                <div class="alert alert-success mb-5" role="alert">
                    <strong class="font-bold">Sukses!</strong>
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            <form method="POST" action="{{ route('admin.perusahaan.store') }}" enctype="multipart/form-data" class="space-y-6">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    {{-- Nama Perusahaan --}}
                    <div>
                        <label for="nama_perusahaan" class="input-label">Nama Perusahaan <span class="text-red-500">*</span></label>
                        <input type="text" name="nama_perusahaan" id="nama_perusahaan" value="{{ old('nama_perusahaan') }}" required
                               class="input-field @error('nama_perusahaan') border-red-500 @enderror">
                        @error('nama_perusahaan') <p class="error-message">{{ $message }}</p> @enderror
                    </div>

                    {{-- About --}}
                    {{-- IMPORTANT: Make sure $companyUsers is passed from the controller if needed --}}
                    {{-- This field was previously "User ID (Optional)", re-added "About" as per your database schema --}}
                    <div>
                        <label for="about" class="input-label">Link Tentang Kami (About Us URL):</label>
                        <input type="url" name="about" id="about" value="{{ old('about') }}"
                               class="input-field @error('about') border-red-500 @enderror">
                        @error('about') <p class="error-message">{{ $message }}</p> @enderror
                    </div>

                    {{-- Email Perusahaan --}}
                    <div>
                        <label for="email_perusahaan" class="input-label">Email Resmi Perusahaan <span class="text-red-500">*</span></label>
                        <input type="email" name="email_perusahaan" id="email_perusahaan" value="{{ old('email_perusahaan') }}" required
                               class="input-field @error('email_perusahaan') border-red-500 @enderror">
                        @error('email_perusahaan') <p class="error-message">{{ $message }}</p> @enderror
                    </div>

                    {{-- Telepon --}}
                    <div>
                        <label for="telepon" class="input-label">Telepon Perusahaan</label>
                        <input type="text" name="telepon" id="telepon" value="{{ old('telepon') }}"
                               class="input-field @error('telepon') border-red-500 @enderror">
                        @error('telepon') <p class="error-message">{{ $message }}</p> @enderror
                    </div>

                    {{-- Website --}}
                    <div>
                        <label for="website" class="input-label">Website (URL) <span class="text-red-500">*</span></label>
                        <input type="url" name="website" id="website" value="{{ old('website') }}" required placeholder="https://example.com"
                               class="input-field @error('website') border-red-500 @enderror">
                        @error('website') <p class="error-message">{{ $message }}</p> @enderror
                    </div>

                    {{-- Kode Pos --}}
                    <div>
                        <label for="kode_pos" class="input-label">Kode Pos</label>
                        <input type="text" name="kode_pos" id="kode_pos" value="{{ old('kode_pos') }}"
                               class="input-field @error('kode_pos') border-red-500 @enderror">
                        @error('kode_pos') <p class="error-message">{{ $message }}</p> @enderror
                    </div>

                    {{-- Alamat --}}
                    <div class="md:col-span-2">
                        <label for="alamat" class="input-label">Alamat Lengkap</label>
                        <textarea name="alamat" id="alamat" rows="3"
                                  class="input-field @error('alamat') border-red-500 @enderror">{{ old('alamat') }}</textarea>
                        @error('alamat') <p class="error-message">{{ $message }}</p> @enderror
                    </div>

                    {{-- Kota --}}
                    <div>
                        <label for="kota" class="input-label">Kota</label>
                        <input type="text" name="kota" id="kota" value="{{ old('kota') }}"
                               class="input-field @error('kota') border-red-500 @enderror">
                        @error('kota') <p class="error-message">{{ $message }}</p> @enderror
                    </div>

                    {{-- Provinsi --}}
                    <div>
                        <label for="provinsi" class="input-label">Provinsi</label>
                        <input type="text" name="provinsi" id="provinsi" value="{{ old('provinsi') }}"
                               class="input-field @error('provinsi') border-red-500 @enderror">
                        @error('provinsi') <p class="error-message">{{ $message }}</p> @enderror
                    </div>

                    {{-- Status Kerjasama --}}
                    <div class="md:col-span-2"> {{-- Changed to col-span-2 as it might look better centered across --}}
                        <label for="status_kerjasama" class="input-label">Status Kerjasama <span class="text-red-500">*</span></label>
                        <select name="status_kerjasama" id="status_kerjasama" required
                                class="input-field @error('status_kerjasama') border-red-500 @enderror">
                            <option value="Review" {{ old('status_kerjasama', 'Review') == 'Review' ? 'selected' : '' }}>Review</option>
                            <option value="Aktif" {{ old('status_kerjasama') == 'Aktif' ? 'selected' : '' }}>Aktif</option>
                            <option value="Non-Aktif" {{ old('status_kerjasama') == 'Non-Aktif' ? 'selected' : '' }}>Non-Aktif</option>
                        </select>
                        @error('status_kerjasama') <p class="error-message">{{ $message }}</p> @enderror
                    </div>

                    {{-- Logo Perusahaan --}}
                    <div class="md:col-span-2">
                        <label for="logo_path" class="input-label">Logo Perusahaan <span class="text-red-500">*</span></label>
                        <input type="file" name="logo_path" id="logo_path" accept="image/*" required
                               class="input-field p-2 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 @error('logo_path') border-red-500 @enderror">
                        @error('logo_path') <p class="error-message">{{ $message }}</p> @enderror
                    </div>

                    {{-- Deskripsi --}}
                    <div class="md:col-span-2">
                        <label for="deskripsi" class="input-label">Deskripsi Perusahaan</label>
                        <textarea name="deskripsi" id="deskripsi" rows="5"
                                  class="input-field @error('deskripsi') border-red-500 @enderror">{{ old('deskripsi') }}</textarea>
                        @error('deskripsi') <p class="error-message">{{ $message }}</p> @enderror
                    </div>
                </div>

                {{-- Informasi Akun User untuk Perusahaan --}}
                {{-- This section is now moved to the bottom of the main form fields, as requested --}}
                <div class="mt-8 border-t pt-6 space-y-6">
                    <h2 class="text-xl font-semibold text-gray-800 mb-4">Buat Akun Login untuk Perusahaan</h2>
                    <div>
                        <label for="username" class="input-label">Username Akun <span class="text-red-500">*</span></label>
                        <input type="text" name="username" id="username" value="{{ old('username') }}" required
                               class="input-field @error('username') border-red-500 @enderror">
                        @error('username') <p class="error-message">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="password" class="input-label">Password Akun <span class="text-red-500">*</span></label>
                        <input type="password" name="password" id="password" required
                               class="input-field @error('password') border-red-500 @enderror">
                        @error('password') <p class="error-message">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="password_confirmation" class="input-label">Konfirmasi Password Akun <span class="text-red-500">*</span></label>
                        <input type="password" name="password_confirmation" id="password_confirmation" required
                               class="input-field @error('password_confirmation') border-red-500 @enderror">
                        @error('password_confirmation') <p class="error-message">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="mt-8 flex justify-end space-x-3">
                    <a href="{{ route('admin.perusahaan.index') }}" class="cancel-button">Batal</a>
                    <button type="submit" class="submit-button">
                        <i class="fas fa-save mr-2"></i> Simpan Perusahaan
                    </button>
                </div>
            </form>
        </div>
    </main>

    @include('admin.template.footer')
</body>
</html>
