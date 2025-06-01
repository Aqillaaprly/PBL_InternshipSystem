<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Perusahaan - {{ $company->nama_perusahaan }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    @include('admin.template.navbar')

    <main class="max-w-4xl mx-auto px-4 py-10 mt-16">
        <div class="bg-white p-8 rounded-lg shadow-md">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-semibold text-gray-800">Edit Perusahaan: {{ $company->nama_perusahaan }}</h1>
                <a href="{{ route('admin.perusahaan.index') }}" class="text-sm text-blue-600 hover:underline">&larr; Kembali ke Daftar Perusahaan</a>
            </div>

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

            <form method="POST" action="{{ route('admin.perusahaan.update', $company->id) }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <h2 class="text-lg font-medium text-gray-900 mb-3 border-b pb-2">Informasi Detail Perusahaan</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4">
                    <div>
                        <label for="nama_perusahaan" class="block text-sm font-medium text-gray-700 mb-1">Nama Perusahaan <span class="text-red-500">*</span></label>
                        <input type="text" name="nama_perusahaan" id="nama_perusahaan" value="{{ old('nama_perusahaan', $company->nama_perusahaan) }}" required class="mt-1 block w-full px-3 py-2 border @error('nama_perusahaan') border-red-500 @else border-gray-300 @enderror rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        @error('nama_perusahaan') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="email_perusahaan" class="block text-sm font-medium text-gray-700 mb-1">Email Resmi Perusahaan <span class="text-red-500">*</span></label>
                        <input type="email" name="email_perusahaan" id="email_perusahaan" value="{{ old('email_perusahaan', $company->email_perusahaan) }}" required class="mt-1 block w-full px-3 py-2 border @error('email_perusahaan') border-red-500 @else border-gray-300 @enderror rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        @error('email_perusahaan') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="telepon" class="block text-sm font-medium text-gray-700 mb-1">Telepon Perusahaan</label>
                        <input type="text" name="telepon" id="telepon" value="{{ old('telepon', $company->telepon) }}" class="mt-1 block w-full px-3 py-2 border @error('telepon') border-red-500 @else border-gray-300 @enderror rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        @error('telepon') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="website" class="block text-sm font-medium text-gray-700 mb-1">Website <span class="text-red-500">*</span></label>
                        <input type="url" name="website" id="website" value="{{ old('website', $company->website) }}" required placeholder="https://example.com" class="mt-1 block w-full px-3 py-2 border @error('website') border-red-500 @else border-gray-300 @enderror rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        @error('website') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="mt-4">
                    <label for="alamat" class="block text-sm font-medium text-gray-700 mb-1">Alamat Lengkap</label>
                    <textarea name="alamat" id="alamat" rows="3" class="mt-1 block w-full px-3 py-2 border @error('alamat') border-red-500 @else border-gray-300 @enderror rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">{{ old('alamat', $company->alamat) }}</textarea>
                    @error('alamat') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-x-6 gap-y-4 mt-4">
                    <div>
                        <label for="kota" class="block text-sm font-medium text-gray-700 mb-1">Kota</label>
                        <input type="text" name="kota" id="kota" value="{{ old('kota', $company->kota) }}" class="mt-1 block w-full px-3 py-2 border @error('kota') border-red-500 @else border-gray-300 @enderror rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        @error('kota') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="provinsi" class="block text-sm font-medium text-gray-700 mb-1">Provinsi</label>
                        <input type="text" name="provinsi" id="provinsi" value="{{ old('provinsi', $company->provinsi) }}" class="mt-1 block w-full px-3 py-2 border @error('provinsi') border-red-500 @else border-gray-300 @enderror rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        @error('provinsi') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="kode_pos" class="block text-sm font-medium text-gray-700 mb-1">Kode Pos</label>
                        <input type="text" name="kode_pos" id="kode_pos" value="{{ old('kode_pos', $company->kode_pos) }}" class="mt-1 block w-full px-3 py-2 border @error('kode_pos') border-red-500 @else border-gray-300 @enderror rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        @error('kode_pos') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="mt-4">
                    <label for="deskripsi" class="block text-sm font-medium text-gray-700 mb-1">Deskripsi Perusahaan</label>
                    <textarea name="deskripsi" id="deskripsi" rows="4" class="mt-1 block w-full px-3 py-2 border @error('deskripsi') border-red-500 @else border-gray-300 @enderror rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">{{ old('deskripsi', $company->deskripsi) }}</textarea>
                    @error('deskripsi') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="mt-4">
                    <label for="logo_path" class="block text-sm font-medium text-gray-700 mb-1">Logo Perusahaan (Kosongkan jika tidak ingin mengubah)</label>
                    @if($company->logo_path && Storage::disk('public')->exists($company->logo_path))
                        <div class="mb-2">
                            <img src="{{ asset('storage/' . $company->logo_path) }}" alt="Logo Saat Ini" class="h-20 w-auto rounded">
                        </div>
                    @elseif($company->logo_path) {{-- Jika path ada tapi file tidak ditemukan di storage --}}
                        <p class="text-xs text-red-500 mb-1">Logo saat ini ({{ $company->logo_path }}) tidak dapat ditemukan.</p>
                    @endif
                    <input type="file" name="logo_path" id="logo_path" accept="image/*" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 @error('logo_path') border-red-500 @enderror">
                    @error('logo_path') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="mt-4">
                    <label for="status_kerjasama" class="block text-sm font-medium text-gray-700 mb-1">Status Kerjasama <span class="text-red-500">*</span></label>
                    <select name="status_kerjasama" id="status_kerjasama" required class="mt-1 block w-full px-3 py-2 border @error('status_kerjasama') border-red-500 @else border-gray-300 @enderror bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        <option value="Aktif" {{ old('status_kerjasama', $company->status_kerjasama) == 'Aktif' ? 'selected' : '' }}>Aktif</option>
                        <option value="Non-Aktif" {{ old('status_kerjasama', $company->status_kerjasama) == 'Non-Aktif' ? 'selected' : '' }}>Non-Aktif</option>
                        <option value="Review" {{ old('status_kerjasama', $company->status_kerjasama) == 'Review' ? 'selected' : '' }}>Review</option>
                    </select>
                    @error('status_kerjasama') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- Informasi Akun Login Perusahaan --}}
                <h2 class="text-lg font-medium text-gray-900 mt-8 mb-3 border-t pt-4 pb-2">Informasi Akun Login Perusahaan</h2>
                @if ($company->user)
                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4">
                    <div>
                        <label for="username" class="block text-sm font-medium text-gray-700 mb-1">Username Akun</label>
                        <input type="text" name="username" id="username" value="{{ old('username', $company->user->username) }}" class="mt-1 block w-full px-3 py-2 border @error('username') border-red-500 @else border-gray-300 @enderror rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        {{-- Pesan dihilangkan karena username wajib diisi jika ada user, atau dibuat baru jika tidak ada --}}
                        @error('username') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        {{-- Email akun user biasanya tidak diubah dari sini untuk menghindari konflik dengan email utama perusahaan --}}
                        {{-- Jika ingin mengubah email login user, pastikan validasinya benar --}}
                        <label for="user_email_login" class="block text-sm font-medium text-gray-700 mb-1">Email Akun Login</label>
                        <input type="email" name="user_email_login" id="user_email_login" value="{{ old('user_email_login', $company->user->email) }}" class="mt-1 block w-full px-3 py-2 border @error('user_email_login') border-red-500 @else border-gray-300 @enderror rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        <p class="mt-1 text-xs text-gray-500">Email ini digunakan untuk login. Bisa berbeda dari email resmi perusahaan.</p>
                        @error('user_email_login') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password Baru Akun</label>
                        <input type="password" name="password" id="password" class="mt-1 block w-full px-3 py-2 border @error('password') border-red-500 @else border-gray-300 @enderror rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        <p class="mt-1 text-xs text-gray-500">Kosongkan jika tidak ingin mengubah password.</p>
                        @error('password') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">Konfirmasi Password Baru Akun</label>
                        <input type="password" name="password_confirmation" id="password_confirmation" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                    </div>
                </div>
                @else
                 <div class="mt-4">
                    <p class="text-sm text-yellow-600 mb-2">Perusahaan ini belum memiliki akun user terkait. Isi field di bawah untuk membuat akun login baru.</p>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4">
                        <div>
                            <label for="new_username" class="block text-sm font-medium text-gray-700 mb-1">Username Akun Baru <span class="text-red-500">*</span></label>
                            <input type="text" name="new_username" id="new_username" value="{{ old('new_username') }}" required class="mt-1 block w-full px-3 py-2 border @error('new_username') border-red-500 @else border-gray-300 @enderror rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                            @error('new_username') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                         <div>
                            <label for="new_user_email" class="block text-sm font-medium text-gray-700 mb-1">Email Akun Baru <span class="text-red-500">*</span></label>
                            <input type="email" name="new_user_email" id="new_user_email" value="{{ old('new_user_email', $company->email_perusahaan) }}" required placeholder="Biasanya sama dengan email perusahaan" class="mt-1 block w-full px-3 py-2 border @error('new_user_email') border-red-500 @else border-gray-300 @enderror rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                            @error('new_user_email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label for="new_password" class="block text-sm font-medium text-gray-700 mb-1">Password Akun Baru <span class="text-red-500">*</span></label>
                            <input type="password" name="new_password" id="new_password" required class="mt-1 block w-full px-3 py-2 border @error('new_password') border-red-500 @else border-gray-300 @enderror rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                            @error('new_password') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label for="new_password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">Konfirmasi Password Akun Baru <span class="text-red-500">*</span></label>
                            <input type="password" name="new_password_confirmation" id="new_password_confirmation" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        </div>
                    </div>
                 </div>
                @endif


                <div class="mt-8 flex justify-end">
                    <a href="{{ route('admin.perusahaan.index') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-medium py-2 px-4 rounded-md mr-2">Batal</a>
                    <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-2 px-4 rounded-md">
                        Update Perusahaan
                    </button>
                </div>
            </form>
        </div>
    </main>

    @include('admin.template.footer')
</body>
</html>
