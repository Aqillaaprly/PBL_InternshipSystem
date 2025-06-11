<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    {{-- Fallback title jika $company tidak valid --}}
    <title>Edit Perusahaan - {{ $company->nama_perusahaan ?? 'Data Perusahaan' }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            background-color: #f7f8fc;
        }
        .page-header {
            background: linear-gradient(to right, #687EEA, #3B5998);
            color: white;
            padding: 1.5rem;
            border-radius: 1rem 1rem 0 0;
            margin-bottom: -1rem; /* Overlaps with info-section slightly */
            position: relative;
            z-index: 10;
        }
        .info-section {
            background-color: white;
            border-radius: 1rem;
            box-shadow: 0 20px 25px -5px rgba(0,0,0,0.1), 0 10px 10px -5px rgba(0,0,0,0.04);
            padding: 2rem;
            padding-top: 2rem;
            position: relative;
            z-index: 5;
        }
        .info-block {
            border-bottom: 1px solid #f3f4f6;
            padding: 1rem 0;
        }
        .info-block:last-of-type {
            border-bottom: none; /* No border for the last block in a section */
        }
        .info-label {
            font-weight: 500;
            color: #6b7280;
        }
        .info-value {
            color: #1f2937;
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

    <main class="max-w-4xl mx-auto px-4 py-10 mt-20"> {{-- Adjusted mt for consistency --}}
        <div class="page-header text-center">
            <h1 class="text-3xl font-bold">Edit Perusahaan</h1>
            <p class="text-sm text-blue-100 mt-1">{{ $company->nama_perusahaan ?? 'N/A' }}</p>
        </div>

        <div class="info-section">
            @if ($errors->any())
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-5" role="alert">
                    <strong class="font-bold">Oops! Ada beberapa masalah dengan input Anda:</strong>
                    <ul class="mt-3 list-disc list-inside text-sm">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            @if (session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif
            @if (session('error'))
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <strong class="font-bold">Gagal!</strong>
                    <span class="block sm:inline">{{ session('error') }}</span>
                </div>
            @endif

            {{-- Memastikan $company adalah instance yang valid dan memiliki ID sebelum merender form --}}
            @if(isset($company) && $company instanceof \App\Models\Company && $company->id)
                <form method="POST" action="{{ route('admin.perusahaan.update', $company->id) }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <h2 class="text-xl font-medium text-gray-900 mb-4 border-b pb-2">Informasi Detail Perusahaan</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4"> {{-- Consolidated gap classes --}}
                        <div class="info-block">
                            <label for="nama_perusahaan" class="info-label block mb-1">Nama Perusahaan <span class="text-red-500">*</span></label>
                            <input type="text" name="nama_perusahaan" id="nama_perusahaan" value="{{ old('nama_perusahaan', $company->nama_perusahaan) }}" required class="mt-1 block w-full px-3 py-2 border @error('nama_perusahaan') border-red-500 @else border-gray-300 @enderror rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm info-value">
                            @error('nama_perusahaan') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div class="info-block">
                            <label for="email_perusahaan" class="info-label block mb-1">Email Resmi Perusahaan <span class="text-red-500">*</span></label>
                            <input type="email" name="email_perusahaan" id="email_perusahaan" value="{{ old('email_perusahaan', $company->email_perusahaan) }}" required class="mt-1 block w-full px-3 py-2 border @error('email_perusahaan') border-red-500 @else border-gray-300 @enderror rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm info-value">
                            @error('email_perusahaan') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div class="info-block">
                            <label for="telepon" class="info-label block mb-1">Telepon Perusahaan</label>
                            <input type="text" name="telepon" id="telepon" value="{{ old('telepon', $company->telepon) }}" class="mt-1 block w-full px-3 py-2 border @error('telepon') border-red-500 @else border-gray-300 @enderror rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm info-value">
                            @error('telepon') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div class="info-block">
                            <label for="website" class="info-label block mb-1">Website <span class="text-red-500">*</span></label>
                            <input type="url" name="website" id="website" value="{{ old('website', $company->website) }}" required placeholder="https://example.com" class="mt-1 block w-full px-3 py-2 border @error('website') border-red-500 @else border-gray-300 @enderror rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm info-value">
                            @error('website') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div class="info-block">
                            <label for="about" class="info-label block mb-1">About<span class="text-red-500">*</span></label>
                            <input type="url" name="about" id="about" value="{{ old('about', $company->about) }}" required placeholder="https://example.com" class="mt-1 block w-full px-3 py-2 border @error('about') border-red-500 @else border-gray-300 @enderror rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm info-value">
                            @error('about') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="mt-4 info-block"> {{-- Wrapped in info-block --}}
                        <label for="alamat" class="info-label block mb-1">Alamat Lengkap</label>
                        <textarea name="alamat" id="alamat" rows="3" class="mt-1 block w-full px-3 py-2 border @error('alamat') border-red-500 @else border-gray-300 @enderror rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm info-value">{{ old('alamat', $company->alamat) }}</textarea>
                        @error('alamat') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-x-6 gap-y-4 mt-4">
                        <div class="info-block">
                            <label for="kota" class="info-label block mb-1">Kota</label>
                            <input type="text" name="kota" id="kota" value="{{ old('kota', $company->kota) }}" class="mt-1 block w-full px-3 py-2 border @error('kota') border-red-500 @else border-gray-300 @enderror rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm info-value">
                            @error('kota') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div class="info-block">
                            <label for="provinsi" class="info-label block mb-1">Provinsi</label>
                            <input type="text" name="provinsi" id="provinsi" value="{{ old('provinsi', $company->provinsi) }}" class="mt-1 block w-full px-3 py-2 border @error('provinsi') border-red-500 @else border-gray-300 @enderror rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm info-value">
                            @error('provinsi') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div class="info-block">
                            <label for="kode_pos" class="info-label block mb-1">Kode Pos</label>
                            <input type="text" name="kode_pos" id="kode_pos" value="{{ old('kode_pos', $company->kode_pos) }}" class="mt-1 block w-full px-3 py-2 border @error('kode_pos') border-red-500 @else border-gray-300 @enderror rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm info-value">
                            @error('kode_pos') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="mt-4 info-block"> {{-- Wrapped in info-block --}}
                        <label for="deskripsi" class="info-label block mb-1">Deskripsi Perusahaan</label>
                        <textarea name="deskripsi" id="deskripsi" rows="4" class="mt-1 block w-full px-3 py-2 border @error('deskripsi') border-red-500 @else border-gray-300 @enderror rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm info-value">{{ old('deskripsi', $company->deskripsi) }}</textarea>
                        @error('deskripsi') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="mt-4 info-block"> {{-- Wrapped in info-block --}}
                        <label for="logo_path" class="info-label block mb-1">Logo Perusahaan (Kosongkan jika tidak ingin mengubah)</label>
                        @if($company->logo_path && Storage::disk('public')->exists($company->logo_path))
                            <div class="mb-2">
                                <img src="{{ asset('storage/' . $company->logo_path) }}" alt="Logo Saat Ini" class="h-20 w-auto rounded">
                            </div>
                        @elseif($company->logo_path)
                            <p class="text-xs text-red-500 mb-1">Logo saat ini ({{ $company->logo_path }}) tidak dapat ditemukan di penyimpanan.</p>
                        @else
                            <p class="text-xs text-gray-500 mb-1">Belum ada logo.</p>
                        @endif
                        <input type="file" name="logo_path" id="logo_path" accept="image/*" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 @error('logo_path') border-red-500 @enderror info-value">
                        @error('logo_path') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="mt-4 info-block"> {{-- Wrapped in info-block --}}
                        <label for="status_kerjasama" class="info-label block mb-1">Status Kerjasama <span class="text-red-500">*</span></label>
                        <select name="status_kerjasama" id="status_kerjasama" required class="mt-1 block w-full px-3 py-2 border @error('status_kerjasama') border-red-500 @else border-gray-300 @enderror bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm info-value">
                            <option value="Aktif" {{ old('status_kerjasama', $company->status_kerjasama) == 'Aktif' ? 'selected' : '' }}>Aktif</option>
                            <option value="Non-Aktif" {{ old('status_kerjasama', $company->status_kerjasama) == 'Non-Aktif' ? 'selected' : '' }}>Non-Aktif</option>
                            <option value="Review" {{ old('status_kerjasama', $company->status_kerjasama) == 'Review' ? 'selected' : '' }}>Review</option>
                        </select>
                        @error('status_kerjasama') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <h2 class="text-xl font-medium text-gray-900 mt-8 mb-4 border-t pt-4 pb-2">Informasi Akun Login Perusahaan</h2> {{-- Adjusted mb for consistency --}}
                    {{-- Cek apakah $company->user ada sebelum mencoba mengakses propertinya --}}
                    @if (isset($company->user) && $company->user)
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4"> {{-- Consolidated gap classes --}}
                            <div class="info-block">
                                <label for="username" class="info-label block mb-1">Username Akun</label>
                                <input type="text" name="username" id="username" value="{{ old('username', $company->user->username) }}" class="mt-1 block w-full px-3 py-2 border @error('username') border-red-500 @else border-gray-300 @enderror rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm info-value">
                                @error('username') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                            <div class="info-block">
                                <label for="user_email_login" class="info-label block mb-1">Email Akun Login</label>
                                <input type="email" name="user_email_login" id="user_email_login" value="{{ old('user_email_login', $company->user->email) }}" class="mt-1 block w-full px-3 py-2 border @error('user_email_login') border-red-500 @else border-gray-300 @enderror rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm info-value">
                                <p class="mt-1 text-xs text-gray-500">Email ini digunakan untuk login.</p>
                                @error('user_email_login') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                            <div class="info-block">
                                <label for="password" class="info-label block mb-1">Password Baru Akun</label>
                                <input type="password" name="password" id="password" class="mt-1 block w-full px-3 py-2 border @error('password') border-red-500 @else border-gray-300 @enderror rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm info-value">
                                <p class="mt-1 text-xs text-gray-500">Kosongkan jika tidak ingin mengubah password.</p>
                                @error('password') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                            <div class="info-block">
                                <label for="password_confirmation" class="info-label block mb-1">Konfirmasi Password Baru Akun</label>
                                <input type="password" name="password_confirmation" id="password_confirmation" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm info-value">
                            </div>
                        </div>
                    @else
                        <div class="mt-4 info-block"> {{-- Wrapped in info-block --}}
                            <p class="text-sm text-yellow-700 bg-yellow-100 p-3 rounded-md mb-3">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline mr-2" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 3.001-1.742 3.001H4.42c-1.53 0-2.493-1.667-1.743-3.001l5.58-9.92zM10 13a1 1 0 110-2 1 1 0 010 2zm-1.75-5.5a1.75 1.75 0 00-3.5 0v.255a1.75 1.75 0 003.5 0v-.255z" clip-rule="evenodd" />
                                </svg>
                                Perusahaan ini belum memiliki akun user terkait. Isi field di bawah untuk membuat akun login baru.
                            </p>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4"> {{-- Consolidated gap classes --}}
                                <div class="info-block">
                                    <label for="new_username" class="info-label block mb-1">Username Akun Baru <span class="text-red-500">*</span></label>
                                    <input type="text" name="new_username" id="new_username" value="{{ old('new_username') }}" class="mt-1 block w-full px-3 py-2 border @error('new_username') border-red-500 @else border-gray-300 @enderror rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm info-value">
                                    @error('new_username') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                                </div>
                                <div class="info-block">
                                    <label for="new_user_email" class="info-label block mb-1">Email Akun Baru <span class="text-red-500">*</span></label>
                                    {{-- Menggunakan $company->email_perusahaan jika ada, atau string kosong --}}
                                    <input type="email" name="new_user_email" id="new_user_email" value="{{ old('new_user_email', $company->email_perusahaan ?? '') }}" placeholder="Biasanya sama dengan email perusahaan" class="mt-1 block w-full px-3 py-2 border @error('new_user_email') border-red-500 @else border-gray-300 @enderror rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm info-value">
                                    @error('new_user_email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                                </div>
                                <div class="info-block">
                                    <label for="new_password" class="info-label block mb-1">Password Akun Baru <span class="text-red-500">*</span></label>
                                    <input type="password" name="new_password" id="new_password" class="mt-1 block w-full px-3 py-2 border @error('new_password') border-red-500 @else border-gray-300 @enderror rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm info-value">
                                    @error('new_password') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                                </div>
                                <div class="info-block">
                                    <label for="new_password_confirmation" class="info-label block mb-1">Konfirmasi Password Akun Baru <span class="text-red-500">*</span></label>
                                    <input type="password" name="new_password_confirmation" id="new_password_confirmation" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm info-value">
                                </div>
                            </div>
                        </div>
                    @endif
                    <div class="mt-8 flex justify-end space-x-3"> {{-- Adjusted spacing for buttons --}}
                        <a href="{{ route('admin.perusahaan.index') }}" class="action-button bg-gray-200 hover:bg-gray-300 text-gray-800 font-medium">Batal</a>
                        <button type="submit" class="action-button edit-button font-medium">
                            Update Perusahaan
                        </button>
                    </div>
                </form>
            @else
                <div class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-4 info-section" role="alert"> {{-- Wrapped in info-section for consistent styling --}}
                    <p class="font-bold">Data Perusahaan Tidak Dapat Diedit</p>
                    <p>Variabel <code>$company</code> tidak valid atau tidak memiliki ID. Tidak dapat membuat URL untuk update.</p>
                </div>
            @endif
        </div>
    </main>

    @include('admin.template.footer')
</body>
</html>
