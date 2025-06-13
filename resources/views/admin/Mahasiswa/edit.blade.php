<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Mahasiswa - {{ $mahasiswa->name ?? $mahasiswa->username }}</title>
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

    <main class="max-w-4xl mx-auto px-4 py-10 mt-20">
        <div class="page-header text-center">
            <h1 class="text-3xl font-bold">Edit Mahasiswa</h1>
            <p class="text-sm text-blue-100 mt-1">{{ $mahasiswa->name ?? $mahasiswa->username }}</p>
        </div>

        <div class="info-section">
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

            <form method="POST" action="{{ route('admin.mahasiswa.update', $mahasiswa->id) }}">
                @csrf
                @method('PUT')

                <h2 class="text-xl font-medium text-gray-900 mb-4 border-b pb-2">Informasi Akun Login</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4">
                    <div class="info-block">
                        <label for="name" class="info-label block mb-1">Nama Lengkap <span class="text-red-500">*</span></label>
                        <input type="text" name="name" id="name" value="{{ old('name', $mahasiswa->name) }}" required class="mt-1 block w-full px-3 py-2 border @error('name') border-red-500 @else border-gray-300 @enderror rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm info-value">
                        @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="info-block">
                        <label for="username" class="info-label block mb-1">NIM (Username) <span class="text-red-500">*</span></label>
                        <input type="text" name="username" id="username" value="{{ old('username', $mahasiswa->username) }}" required class="mt-1 block w-full px-3 py-2 border @error('username') border-red-500 @else border-gray-300 @enderror rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm info-value">
                        @error('username') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="info-block">
                        <label for="email" class="info-label block mb-1">Email <span class="text-red-500">*</span></label>
                        <input type="email" name="email" id="email" value="{{ old('email', $mahasiswa->email) }}" required class="mt-1 block w-full px-3 py-2 border @error('email') border-red-500 @else border-gray-300 @enderror rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm info-value">
                        @error('email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="info-block">
                        <label for="password" class="info-label block mb-1">Password Baru</label>
                        <input type="password" name="password" id="password" class="mt-1 block w-full px-3 py-2 border @error('password') border-red-500 @else border-gray-300 @enderror rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm info-value">
                        <p class="text-xs text-gray-500 mt-1">Kosongkan jika tidak ingin mengubah password.</p>
                        @error('password') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="info-block">
                        <label for="password_confirmation" class="info-label block mb-1">Konfirmasi Password Baru</label>
                        <input type="password" name="password_confirmation" id="password_confirmation" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm info-value">
                    </div>
                </div>

                <h2 class="text-xl font-medium text-gray-900 mt-8 mb-4 border-t pt-4 pb-2">Detail Mahasiswa</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4">
                    <div class="info-block">
                        <label for="kelas" class="info-label block mb-1">Kelas</label>
                        <input type="text" name="kelas" id="kelas" value="{{ old('kelas', $mahasiswa->detailMahasiswa->kelas ?? '') }}" class="mt-1 block w-full px-3 py-2 border @error('kelas') border-red-500 @else border-gray-300 @enderror rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm info-value">
                        @error('kelas') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div class="info-block">
                        <label for="program_studi" class="info-label block mb-1">Program Studi</label>
                        <select name="program_studi" id="program_studi" class="mt-1 block w-full px-3 py-2 border @error('program_studi') border-red-500 @else border-gray-300 @enderror bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm info-value">
                            <option value="">Pilih Program Studi</option>
                            <option value="Teknik Informatika" {{ old('program_studi', $mahasiswa->detailMahasiswa->program_studi ?? '') == 'Teknik Informatika' ? 'selected' : '' }}>Teknik Informatika</option>
                            <option value="Sistem Informasi Bisnis" {{ old('program_studi', $mahasiswa->detailMahasiswa->program_studi ?? '') == 'Sistem Informasi Bisnis' ? 'selected' : '' }}>Sistem Informasi Bisnis</option>
                        </select>
                        @error('program_studi') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div class="info-block">
                        <label for="nomor_hp" class="info-label block mb-1">Nomor HP</label>
                        <input type="tel" name="nomor_hp" id="nomor_hp" value="{{ old('nomor_hp', $mahasiswa->detailMahasiswa->nomor_hp ?? '') }}" class="mt-1 block w-full px-3 py-2 border @error('nomor_hp') border-red-500 @else border-gray-300 @enderror rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm info-value">
                        @error('nomor_hp') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div class="md:col-span-2 info-block">
                        <label for="alamat" class="info-label block mb-1">Alamat</label>
                        <textarea name="alamat" id="alamat" rows="3" class="mt-1 block w-full px-3 py-2 border @error('alamat') border-red-500 @else border-gray-300 @enderror rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm info-value">{{ old('alamat', $mahasiswa->detailMahasiswa->alamat ?? '') }}</textarea>
                        @error('alamat') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="mt-8 flex justify-end space-x-3">
                    <a href="{{ route('admin.datamahasiswa') }}" class="action-button bg-gray-200 hover:bg-gray-300 text-gray-800 font-medium">Batal</a>
                    <button type="submit" class="action-button edit-button font-medium">
                        Update Mahasiswa
                    </button>
                </div>
            </form>
        </div>
    </main>


</body>
</html>
