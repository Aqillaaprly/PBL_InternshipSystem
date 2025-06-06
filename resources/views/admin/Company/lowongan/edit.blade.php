<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Lowongan - {{ $lowongan->judul }}</title>
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
        .save-button {
            background-image: linear-gradient(to right, #4f46e5, #7c3aed); /* Use save-button class */
            color: white;
        }
        .save-button:hover {
            background-image: linear-gradient(to right, #4338ca, #6d28d9);
        }
    </style>
</head>
<body class="text-gray-800">
    @include('admin.template.navbar') {{-- Sesuaikan path navbar admin Anda --}}

    <main class="max-w-4xl mx-auto px-4 py-10 mt-20"> {{-- Adjusted max-width and mt for consistency --}}
        <div class="page-header text-center">
            <h1 class="text-3xl font-bold">Edit Lowongan</h1>
            <p class="text-sm text-blue-100 mt-1">{{ $lowongan->judul }}</p>
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

            <form method="POST" action="{{ route('admin.lowongan.update', $lowongan->id) }}">
                @csrf
                @method('PUT')

                <div class="info-block">
                    <label for="company_id" class="info-label block mb-1">Perusahaan <span class="text-red-500">*</span></label>
                    <select name="company_id" id="company_id" required class="mt-1 block w-full px-3 py-2 border @error('company_id') border-red-500 @else border-gray-300 @enderror bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm info-value">
                        <option value="">Pilih Perusahaan</option>
                        @foreach ($companies as $company)
                            <option value="{{ $company->id }}" {{ old('company_id', $lowongan->company_id) == $company->id ? 'selected' : '' }}>
                                {{ $company->nama_perusahaan }}
                            </option>
                        @endforeach
                    </select>
                    @error('company_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="info-block">
                    <label for="judul" class="info-label block mb-1">Judul Lowongan <span class="text-red-500">*</span></label>
                    <input type="text" name="judul" id="judul" value="{{ old('judul', $lowongan->judul) }}" required class="mt-1 block w-full px-3 py-2 border @error('judul') border-red-500 @else border-gray-300 @enderror rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm info-value">
                    @error('judul') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="info-block">
                    <label for="deskripsi" class="info-label block mb-1">Deskripsi <span class="text-red-500">*</span></label>
                    <textarea name="deskripsi" id="deskripsi" rows="5" required class="mt-1 block w-full px-3 py-2 border @error('deskripsi') border-red-500 @else border-gray-300 @enderror rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm info-value">{{ old('deskripsi', $lowongan->deskripsi) }}</textarea>
                    @error('deskripsi') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="info-block">
                    <label for="kualifikasi" class="info-label block mb-1">Kualifikasi <span class="text-red-500">*</span></label>
                    <textarea name="kualifikasi" id="kualifikasi" rows="5" required class="mt-1 block w-full px-3 py-2 border @error('kualifikasi') border-red-500 @else border-gray-300 @enderror rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm info-value">{{ old('kualifikasi', $lowongan->kualifikasi) }}</textarea>
                    @error('kualifikasi') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4"> {{-- Changed gap to gap-x-6 gap-y-4 for consistency --}}
                    <div class="info-block">
                        <label for="tipe" class="info-label block mb-1">Tipe <span class="text-red-500">*</span></label>
                        <select name="tipe" id="tipe" required class="mt-1 block w-full px-3 py-2 border @error('tipe') border-red-500 @else border-gray-300 @enderror bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm info-value">
                            <option value="Internship" {{ old('tipe', $lowongan->tipe) == 'Internship' ? 'selected' : '' }}>Internship</option>
                            <option value="Penuh Waktu" {{ old('tipe', $lowongan->tipe) == 'Penuh Waktu' ? 'selected' : '' }}>Penuh Waktu</option>
                            <option value="Paruh Waktu" {{ old('tipe', $lowongan->tipe) == 'Paruh Waktu' ? 'selected' : '' }}>Paruh Waktu</option>
                            <option value="Kontrak" {{ old('tipe', $lowongan->tipe) == 'Kontrak' ? 'selected' : '' }}>Kontrak</option>
                        </select>
                        @error('tipe') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div class="info-block">
                        <label for="lokasi" class="info-label block mb-1">Lokasi <span class="text-red-500">*</span></label>
                        <input type="text" name="lokasi" id="lokasi" value="{{ old('lokasi', $lowongan->lokasi) }}" required class="mt-1 block w-full px-3 py-2 border @error('lokasi') border-red-500 @else border-gray-300 @enderror rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm info-value">
                        @error('lokasi') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4"> {{-- Changed gap to gap-x-6 gap-y-4 for consistency --}}
                    <div class="info-block">
                        <label for="gaji_min" class="info-label block mb-1">Gaji Minimum (Opsional)</label>
                        <input type="number" name="gaji_min" id="gaji_min" value="{{ old('gaji_min', $lowongan->gaji_min) }}" class="mt-1 block w-full px-3 py-2 border @error('gaji_min') border-red-500 @else border-gray-300 @enderror rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm info-value" placeholder="Contoh: 3000000">
                        @error('gaji_min') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div class="info-block">
                        <label for="gaji_max" class="info-label block mb-1">Gaji Maksimum (Opsional)</label>
                        <input type="number" name="gaji_max" id="gaji_max" value="{{ old('gaji_max', $lowongan->gaji_max) }}" class="mt-1 block w-full px-3 py-2 border @error('gaji_max') border-red-500 @else border-gray-300 @enderror rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm info-value" placeholder="Contoh: 5000000">
                        @error('gaji_max') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4"> {{-- Changed gap to gap-x-6 gap-y-4 for consistency --}}
                    <div class="info-block">
                        <label for="tanggal_buka" class="info-label block mb-1">Tanggal Buka <span class="text-red-500">*</span></label>
                        <input type="date" name="tanggal_buka" id="tanggal_buka" value="{{ old('tanggal_buka', $lowongan->tanggal_buka ? \Carbon\Carbon::parse($lowongan->tanggal_buka)->format('Y-m-d') : '') }}" required class="mt-1 block w-full px-3 py-2 border @error('tanggal_buka') border-red-500 @else border-gray-300 @enderror rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm info-value">
                        @error('tanggal_buka') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div class="info-block">
                        <label for="tanggal_tutup" class="info-label block mb-1">Tanggal Tutup <span class="text-red-500">*</span></label>
                        <input type="date" name="tanggal_tutup" id="tanggal_tutup" value="{{ old('tanggal_tutup', $lowongan->tanggal_tutup ? \Carbon\Carbon::parse($lowongan->tanggal_tutup)->format('Y-m-d') : '') }}" required class="mt-1 block w-full px-3 py-2 border @error('tanggal_tutup') border-red-500 @else border-gray-300 @enderror rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm info-value">
                        @error('tanggal_tutup') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="info-block"> {{-- Wrapped in info-block --}}
                    <label for="status" class="info-label block mb-1">Status <span class="text-red-500">*</span></label>
                    <select name="status" id="status" required class="mt-1 block w-full px-3 py-2 border @error('status') border-red-500 @else border-gray-300 @enderror bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm info-value">
                        <option value="Aktif" {{ old('status', $lowongan->status) == 'Aktif' ? 'selected' : '' }}>Aktif</option>
                        <option value="Non-Aktif" {{ old('status', $lowongan->status) == 'Non-Aktif' ? 'selected' : '' }}>Non-Aktif</option>
                    </select>
                    @error('status') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                
                <div class="mt-8 flex justify-end space-x-3">
                    <a href="{{ route('admin.lowongan.index') }}" class="action-button bg-gray-200 hover:bg-gray-300 text-gray-800 font-medium">
                        Batal
                    </a>
                    <button type="submit" class="action-button save-button font-medium"> {{-- Changed to save-button class --}}
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </main>

    @include('admin.template.footer') {{-- Sesuaikan path footer admin Anda --}}
</body>
</html>
