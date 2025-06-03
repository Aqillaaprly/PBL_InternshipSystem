<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Pembimbing - {{ $pembimbing->nama_lengkap }} - Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    @include('admin.template.navbar')

    <main class="max-w-3xl mx-auto px-4 py-10 mt-16">
        <div class="bg-white p-8 rounded-lg shadow-md">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-semibold text-gray-800">Edit Dosen Pembimbing: {{ $pembimbing->nama_lengkap }}</h1>
                <a href="{{ route('admin.pembimbings.index') }}" class="text-sm text-blue-600 hover:underline">&larr; Kembali ke Daftar Pembimbing</a>
            </div>


            @if ($errors->any())
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-5" role="alert">
                    <strong class="font-bold">Oops! Ada beberapa masalah:</strong>
                    <ul class="mt-3 list-disc list-inside text-sm">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
             @if (session('error'))
                 <div class="bg-red-100 border-l-4 border-red-500 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <strong class="font-bold">Gagal!</strong>
                    <span class="block sm:inline">{{ session('error') }}</span>
                </div>
            @endif

            <form method="POST" action="{{ route('admin.pembimbings.update', $pembimbing->id) }}">
                @csrf
                @method('PUT')

                <h2 class="text-lg font-semibold text-gray-700 mb-4 border-b pb-2">Informasi Akun Login</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label for="username_login" class="block text-sm font-medium text-gray-700 mb-1">Username (untuk Login) <span class="text-red-500">*</span></label>
                        <input type="text" name="username_login" id="username_login" value="{{ old('username_login', $pembimbing->user->username ?? '') }}" required class="mt-1 block w-full px-3 py-2 border @error('username_login') border-red-500 @else border-gray-300 @enderror rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        @error('username_login') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="email_login" class="block text-sm font-medium text-gray-700 mb-1">Email (untuk Login) <span class="text-red-500">*</span></label>
                        <input type="email" name="email_login" id="email_login" value="{{ old('email_login', $pembimbing->user->email ?? '') }}" required class="mt-1 block w-full px-3 py-2 border @error('email_login') border-red-500 @else border-gray-300 @enderror rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        @error('email_login') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password Baru</label>
                        <input type="password" name="password" id="password" class="mt-1 block w-full px-3 py-2 border @error('password') border-red-500 @else border-gray-300 @enderror rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        <p class="text-xs text-gray-500 mt-1">Kosongkan jika tidak ingin mengubah password.</p>
                        @error('password') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">Konfirmasi Password Baru</label>
                        <input type="password" name="password_confirmation" id="password_confirmation" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                    </div>
                </div>

                <h2 class="text-lg font-semibold text-gray-700 mb-4 border-b pb-2 pt-4">Detail Pembimbing</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="nip" class="block text-sm font-medium text-gray-700 mb-1">NIP <span class="text-red-500">*</span></label>
                        <input type="text" name="nip" id="nip" value="{{ old('nip', $pembimbing->nip) }}" required class="mt-1 block w-full px-3 py-2 border @error('nip') border-red-500 @else border-gray-300 @enderror rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        @error('nip') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="nama_lengkap" class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap (dengan gelar) <span class="text-red-500">*</span></label>
                        <input type="text" name="nama_lengkap" id="nama_lengkap" value="{{ old('nama_lengkap', $pembimbing->nama_lengkap) }}" required class="mt-1 block w-full px-3 py-2 border @error('nama_lengkap') border-red-500 @else border-gray-300 @enderror rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        @error('nama_lengkap') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                     <div>
                        <label for="email_institusi" class="block text-sm font-medium text-gray-700 mb-1">Email Institusi <span class="text-red-500">*</span></label>
                        <input type="email" name="email_institusi" id="email_institusi" value="{{ old('email_institusi', $pembimbing->email_institusi) }}" required class="mt-1 block w-full px-3 py-2 border @error('email_institusi') border-red-500 @else border-gray-300 @enderror rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        @error('email_institusi') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="nomor_telepon" class="block text-sm font-medium text-gray-700 mb-1">Nomor Telepon</label>
                        <input type="tel" name="nomor_telepon" id="nomor_telepon" value="{{ old('nomor_telepon', $pembimbing->nomor_telepon) }}" class="mt-1 block w-full px-3 py-2 border @error('nomor_telepon') border-red-500 @else border-gray-300 @enderror rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        @error('nomor_telepon') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="jabatan_fungsional" class="block text-sm font-medium text-gray-700 mb-1">Jabatan Fungsional</label>
                        <input type="text" name="jabatan_fungsional" id="jabatan_fungsional" value="{{ old('jabatan_fungsional', $pembimbing->jabatan_fungsional) }}" class="mt-1 block w-full px-3 py-2 border @error('jabatan_fungsional') border-red-500 @else border-gray-300 @enderror rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        @error('jabatan_fungsional') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="program_studi_homebase" class="block text-sm font-medium text-gray-700 mb-1">Program Studi Homebase</label>
                        <input type="text" name="program_studi_homebase" id="program_studi_homebase" value="{{ old('program_studi_homebase', $pembimbing->program_studi_homebase) }}" class="mt-1 block w-full px-3 py-2 border @error('program_studi_homebase') border-red-500 @else border-gray-300 @enderror rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        @error('program_studi_homebase') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div class="md:col-span-2">
                        <label for="bidang_keahlian_utama" class="block text-sm font-medium text-gray-700 mb-1">Bidang Keahlian Utama</label>
                        <textarea name="bidang_keahlian_utama" id="bidang_keahlian_utama" rows="3" class="mt-1 block w-full px-3 py-2 border @error('bidang_keahlian_utama') border-red-500 @else border-gray-300 @enderror rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">{{ old('bidang_keahlian_utama', $pembimbing->bidang_keahlian_utama) }}</textarea>
                        @error('bidang_keahlian_utama') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                     <div>
                        <label for="maks_kuota_bimbingan" class="block text-sm font-medium text-gray-700 mb-1">Maksimal Kuota Bimbingan <span class="text-red-500">*</span></label>
                        <input type="number" name="maks_kuota_bimbingan" id="maks_kuota_bimbingan" value="{{ old('maks_kuota_bimbingan', $pembimbing->maks_kuota_bimbingan) }}" required min="0" class="mt-1 block w-full px-3 py-2 border @error('maks_kuota_bimbingan') border-red-500 @else border-gray-300 @enderror rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        @error('maks_kuota_bimbingan') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                     <div>
                        <label for="status_aktif" class="block text-sm font-medium text-gray-700 mb-1">Status Aktif <span class="text-red-500">*</span></label>
                        <select name="status_aktif" id="status_aktif" required class="mt-1 block w-full px-3 py-2 border @error('status_aktif') border-red-500 @else border-gray-300 @enderror bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                            <option value="1" {{ old('status_aktif', $pembimbing->status_aktif) == '1' ? 'selected' : '' }}>Aktif</option>
                            <option value="0" {{ old('status_aktif', $pembimbing->status_aktif) == '0' ? 'selected' : '' }}>Tidak Aktif</option>
                        </select>
                        @error('status_aktif') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="mt-8 flex justify-end">
                    <a href="{{ route('admin.pembimbings.index') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-medium py-2 px-4 rounded-md mr-2">Batal</a>
                    <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-2 px-4 rounded-md">
                        Update Pembimbing
                    </button>
                </div>
            </form>
        </div>
    </main>

    @include('admin.template.footer')
</body>
</html>