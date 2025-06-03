<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Lowongan Baru - Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    @include('admin.template.navbar') {{-- Sesuaikan path navbar admin Anda --}}

    <main class="max-w-3xl mx-auto px-4 py-10 mt-16">
        <div class="bg-white p-8 rounded-lg shadow-md">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-semibold text-gray-800">Tambah Lowongan Baru</h1>
                <a href="{{ route('admin.lowongan.index') }}" class="text-sm text-blue-600 hover:underline">&larr; Kembali ke Daftar Lowongan</a>
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


            <form method="POST" action="{{ route('admin.lowongan.store') }}">
                @csrf

                <div class="mb-4">
                    <label for="company_id" class="block text-sm font-medium text-gray-700 mb-1">Perusahaan <span class="text-red-500">*</span></label>
                    <select name="company_id" id="company_id" required class="mt-1 block w-full px-3 py-2 border @error('company_id') border-red-500 @else border-gray-300 @enderror bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        <option value="">Pilih Perusahaan</option>
                        @foreach ($companies as $company)
                            <option value="{{ $company->id }}" {{ old('company_id') == $company->id ? 'selected' : '' }}>
                                {{ $company->nama_perusahaan }}
                            </option>
                        @endforeach
                    </select>
                    @error('company_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="mb-4">
                    <label for="judul" class="block text-sm font-medium text-gray-700 mb-1">Judul Lowongan <span class="text-red-500">*</span></label>
                    <input type="text" name="judul" id="judul" value="{{ old('judul') }}" required class="mt-1 block w-full px-3 py-2 border @error('judul') border-red-500 @else border-gray-300 @enderror rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" placeholder="Contoh: Web Developer Intern">
                    @error('judul') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="mb-4">
                    <label for="deskripsi" class="block text-sm font-medium text-gray-700 mb-1">Deskripsi <span class="text-red-500">*</span></label>
                    <textarea name="deskripsi" id="deskripsi" rows="5" required class="mt-1 block w-full px-3 py-2 border @error('deskripsi') border-red-500 @else border-gray-300 @enderror rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" placeholder="Jelaskan tentang pekerjaan, tanggung jawab, dll.">{{ old('deskripsi') }}</textarea>
                    @error('deskripsi') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="mb-4">
                    <label for="kualifikasi" class="block text-sm font-medium text-gray-700 mb-1">Kualifikasi <span class="text-red-500">*</span></label>
                    <textarea name="kualifikasi" id="kualifikasi" rows="5" required class="mt-1 block w-full px-3 py-2 border @error('kualifikasi') border-red-500 @else border-gray-300 @enderror rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" placeholder="Sebutkan kualifikasi yang dibutuhkan, pisahkan dengan baris baru jika perlu.">{{ old('kualifikasi') }}</textarea>
                    @error('kualifikasi') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label for="tipe" class="block text-sm font-medium text-gray-700 mb-1">Tipe <span class="text-red-500">*</span></label>
                        <select name="tipe" id="tipe" required class="mt-1 block w-full px-3 py-2 border @error('tipe') border-red-500 @else border-gray-300 @enderror bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                            <option value="Internship" {{ old('tipe', 'Internship') == 'Internship' ? 'selected' : '' }}>Internship</option>
                            <option value="Penuh Waktu" {{ old('tipe') == 'Penuh Waktu' ? 'selected' : '' }}>Penuh Waktu</option>
                            <option value="Paruh Waktu" {{ old('tipe') == 'Paruh Waktu' ? 'selected' : '' }}>Paruh Waktu</option>
                            <option value="Kontrak" {{ old('tipe') == 'Kontrak' ? 'selected' : '' }}>Kontrak</option>
                        </select>
                        @error('tipe') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="lokasi" class="block text-sm font-medium text-gray-700 mb-1">Lokasi <span class="text-red-500">*</span></label>
                        <input type="text" name="lokasi" id="lokasi" value="{{ old('lokasi') }}" required class="mt-1 block w-full px-3 py-2 border @error('lokasi') border-red-500 @else border-gray-300 @enderror rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" placeholder="Contoh: Jakarta Selatan, DKI Jakarta">
                        @error('lokasi') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                 <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label for="gaji_min" class="block text-sm font-medium text-gray-700 mb-1">Gaji Minimum (Opsional)</label>
                        <input type="number" name="gaji_min" id="gaji_min" value="{{ old('gaji_min') }}" class="mt-1 block w-full px-3 py-2 border @error('gaji_min') border-red-500 @else border-gray-300 @enderror rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" placeholder="Contoh: 3000000">
                        @error('gaji_min') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="gaji_max" class="block text-sm font-medium text-gray-700 mb-1">Gaji Maksimum (Opsional)</label>
                        <input type="number" name="gaji_max" id="gaji_max" value="{{ old('gaji_max') }}" class="mt-1 block w-full px-3 py-2 border @error('gaji_max') border-red-500 @else border-gray-300 @enderror rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" placeholder="Contoh: 5000000">
                        @error('gaji_max') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label for="tanggal_buka" class="block text-sm font-medium text-gray-700 mb-1">Tanggal Buka <span class="text-red-500">*</span></label>
                        <input type="date" name="tanggal_buka" id="tanggal_buka" value="{{ old('tanggal_buka', date('Y-m-d')) }}" required class="mt-1 block w-full px-3 py-2 border @error('tanggal_buka') border-red-500 @else border-gray-300 @enderror rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        @error('tanggal_buka') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="tanggal_tutup" class="block text-sm font-medium text-gray-700 mb-1">Tanggal Tutup <span class="text-red-500">*</span></label>
                        <input type="date" name="tanggal_tutup" id="tanggal_tutup" value="{{ old('tanggal_tutup') }}" required class="mt-1 block w-full px-3 py-2 border @error('tanggal_tutup') border-red-500 @else border-gray-300 @enderror rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        @error('tanggal_tutup') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="mb-6">
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status <span class="text-red-500">*</span></label>
                    <select name="status" id="status" required class="mt-1 block w-full px-3 py-2 border @error('status') border-red-500 @else border-gray-300 @enderror bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        <option value="Aktif" {{ old('status', 'Aktif') == 'Aktif' ? 'selected' : '' }}>Aktif</option>
                        <option value="Ditutup" {{ old('status') == 'Ditutup' ? 'selected' : '' }}>Ditutup</option>
                        {{-- Jika Anda memutuskan untuk menggunakan 'Non-Aktif', tambahkan di sini dan sesuaikan validator --}}
                        {{-- <option value="Non-Aktif" {{ old('status') == 'Non-Aktif' ? 'selected' : '' }}>Non-Aktif</option> --}}
                    </select>
                    @error('status') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="mt-8 flex justify-end space-x-3">
                    <a href="{{ route('admin.lowongan.index') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-medium py-2 px-4 rounded-md">
                        Batal
                    </a>
                    <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-2 px-4 rounded-md shadow-sm">
                        Simpan Lowongan
                    </button>
                </div>
            </form>
        </div>
    </main>

    @include('admin.template.footer') {{-- Sesuaikan path footer admin Anda --}}
</body>
</html>