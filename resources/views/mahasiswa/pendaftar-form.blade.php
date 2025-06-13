<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Form Pendaftaran Magang</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" />
    <style>
        body {
            background-color: #f0f4f8;
        }
    </style>
</head>
<body class="pt-20">
@include('mahasiswa.template.navbar')

<main class="max-w-4xl mx-auto px-4 py-8">
    <div class="bg-white p-6 md:p-8 rounded-2xl shadow-lg">
        <div class="mb-8 text-center">
            <h1 class="text-3xl font-bold text-gray-800">Form Pendaftaran Magang</h1>
            <p class="text-gray-500 mt-2">Lengkapi form berikut untuk mendaftar magang. Pastikan semua dokumen yang diperlukan sudah siap.</p>
        </div>

        @if ($errors->any())
            <div class="mb-6 bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded-md" role="alert">
                <p class="font-bold">Gagal Mengirim Pendaftaran:</p>
                <ul class="mt-2 list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        
        @if (session('error'))
            <div class="mb-6 bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded-md" role="alert">
                <p>{{ session('error') }}</p>
            </div>
        @endif

        {{-- Pastikan tag form memiliki enctype="multipart/form-data" --}}
        <form action="{{ route('mahasiswa.pendaftar.submit') }}" method="POST" enctype="multipart/form-data">
        @csrf

            {{-- Pilih Lowongan --}}
            <div class="mb-6">
                <label for="lowongan_id" class="block text-sm font-medium text-gray-700 mb-2">Pilih Lowongan <span class="text-red-500">*</span></label>
                <select name="lowongan_id" id="lowongan_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                    <option value="" disabled {{ !$selectedLowonganId ? 'selected' : '' }}>-- Pilih Lowongan Magang --</option>
                    @foreach($lowongans as $lowongan)
                        <option value="{{ $lowongan->id }}" {{ $selectedLowonganId == $lowongan->id ? 'selected' : '' }}>
                            {{ $lowongan->judul }} - {{ $lowongan->company->nama_perusahaan ?? 'N/A' }} (Tutup: {{ \Carbon\Carbon::parse($lowongan->tanggal_tutup)->format('d/m/Y') }})
                        </option>
                    @endforeach
                </select>
                @error('lowongan_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>

            {{-- Unggah Dokumen --}}
            <div class="mb-6">
                <h2 class="text-xl font-semibold text-gray-700 mb-4">Unggah Dokumen Persyaratan</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    
                    {{-- Kolom Dokumen Wajib --}}
                    <div class="bg-gray-50 p-6 rounded-xl border border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4 border-b pb-2">Dokumen Wajib</h3>
                        <div class="space-y-4">

                            <div>
                                <label for="surat_lamaran" class="block text-sm font-medium text-gray-700">Surat Lamaran <span class="text-red-500">*</span></label>
                                <input type="file" name="surat_lamaran" id="surat_lamaran" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100" required>
                            </div>
                            
                            <div>
                                <label for="cv" class="block text-sm font-medium text-gray-700">CV (Curriculum Vitae) <span class="text-red-500">*</span></label>
                                <input type="file" name="cv" id="cv" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100" required>
                            </div>

                            <div>
                                <label for="riwayat_hidup" class="block text-sm font-medium text-gray-700">Daftar Riwayat Hidup <span class="text-red-500">*</span></label>
                                <input type="file" name="riwayat_hidup" id="riwayat_hidup" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100" required>
                            </div>
                            
                            <div>
                                <label for="portofolio" class="block text-sm font-medium text-gray-700">Portofolio <span class="text-red-500">*</span></label>
                                <input type="file" name="portofolio" id="portofolio" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100" required>
                            </div>

                            <div>
                                <label for="khs_transkrip" class="block text-sm font-medium text-gray-700">KHS / Transkrip Nilai <span class="text-red-500">*</span></label>
                                <input type="file" name="khs_transkrip" id="khs_transkrip" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100" required>
                            </div>
                            
                            <div>
                                <label for="ktp" class="block text-sm font-medium text-gray-700">KTP <span class="text-red-500">*</span></label>
                                <input type="file" name="ktp" id="ktp" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100" required>
                            </div>

                            <div>
                                <label for="ktm" class="block text-sm font-medium text-gray-700">KTM <span class="text-red-500">*</span></label>
                                <input type="file" name="ktm" id="ktm" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100" required>
                            </div>
                            
                            <div>
                                <label for="surat_izin_ortu" class="block text-sm font-medium text-gray-700">Surat Izin Orang Tua <span class="text-red-500">*</span></label>
                                <input type="file" name="surat_izin_ortu" id="surat_izin_ortu" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100" required>
                            </div>

                            <div>
                                <label for="pakta_integritas" class="block text-sm font-medium text-gray-700">Pakta Integritas <span class="text-red-500">*</span></label>
                                <input type="file" name="pakta_integritas" id="pakta_integritas" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100" required>
                            </div>
                        </div>
                    </div>

                    {{-- Kolom Dokumen Tambahan --}}
                    <div class="bg-gray-50 p-6 rounded-xl border border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4 border-b pb-2">Dokumen Tambahan</h3>
                        <div class="space-y-4">
                            <div>
                                <label for="sertifikat_kompetensi" class="block text-sm font-medium text-gray-700">Sertifikat Kompetensi</label>
                                <input type="file" name="sertifikat_kompetensi" id="sertifikat_kompetensi" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                            </div>

                            <div>
                                <label for="sktm_kip" class="block text-sm font-medium text-gray-700">SKTM atau KIP Kuliah</label>
                                <input type="file" name="sktm_kip" id="sktm_kip" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Catatan Tambahan --}}
            <div class="mb-6">
                <label for="catatan_pendaftar" class="block text-sm font-medium text-gray-700">Catatan Tambahan</label>
                <textarea name="catatan_pendaftar" id="catatan_pendaftar" rows="4" class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Tuliskan pesan singkat atau informasi tambahan untuk perusahaan (opsional)...">{{ old('catatan_pendaftar') }}</textarea>
            </div>

            {{-- Persetujuan --}}
            <div class="mb-6">
                <div class="flex items-start">
                    <div class="flex items-center h-5">
                        <input id="terms" name="terms" type="checkbox" class="focus:ring-blue-500 h-4 w-4 text-blue-600 border-gray-300 rounded" required>
                    </div>
                    <div class="ml-3 text-sm">
                        <label for="terms" class="font-medium text-gray-700">Saya menyatakan bahwa semua data dan dokumen yang saya unggah adalah benar dan dapat dipertanggungjawabkan <span class="text-red-500">*</span></label>
                    </div>
                </div>
                 @error('terms') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>

            {{-- Tombol Aksi --}}
            <div class="flex justify-end gap-4">
                <a href="{{ route('mahasiswa.pendaftar') }}" class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-100">
                    Batal
                </a>
                <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    Kirim Pendaftaran
                </button>
            </div>
        </form>
    </div>
</div>

</body>
</html>