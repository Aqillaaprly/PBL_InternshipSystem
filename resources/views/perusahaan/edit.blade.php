<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Lowongan - {{ $lowongan->judul ?? 'Lowongan' }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css" xintegrity="sha512-SzlrxWUlpfuzQ+pcUCosxcglQRNAq/DZjVsC0lE40xsADsfeQoEypE+enwcOiGjk/bSuGGKHEyjSoQ1zVisanQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>
        body {
            background-color: #f7f8fc;
        }
        .form-card {
            background-color: white;
            border-radius: 1rem; /* rounded-2xl */
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04); /* shadow-xl */
            padding: 2rem; /* p-8 */
        }
        .form-section-title {
            font-size: 1.25rem; /* text-xl */
            font-weight: 600; /* font-semibold */
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
            font-size: 0.875rem; /* text-sm */
            font-weight: 500; /* font-medium */
            color: #4b5563; /* gray-600 */
            margin-bottom: 0.5rem; /* mb-2 */
        }
        .form-input, .form-textarea, .form-select {
            display: block;
            width: 100%;
            padding: 0.75rem 1rem; /* py-3 px-4 */
            font-size: 0.875rem; /* text-sm */
            color: #1f2937; /* text-gray-800 */
            background-color: #f9fafb; /* bg-gray-50 */
            border: 1px solid #d1d5db; /* border-gray-300 */
            border-radius: 0.5rem; /* rounded-lg */
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
        .action-button {
            transition: all 0.2s ease-in-out;
            padding: 0.625rem 1.5rem; /* py-2.5 px-6 */
            font-size: 0.875rem; /* text-sm */
            font-weight: 500; /* font-medium */
            border-radius: 0.5rem; /* rounded-lg */
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
                <h1 class="text-2xl font-bold text-gray-800">Edit Lowongan: {{ $lowongan->judul ?? 'N/A' }}</h1>
                <p class="text-sm text-gray-500">Perbarui detail lowongan pekerjaan ini.</p>
            </div>

            @if ($errors->any())
                <div class="bg-red-50 border-l-4 border-red-400 text-red-700 p-4 rounded-md mb-6" role="alert">
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
            @endif

            <form method="POST" action="{{ route('perusahaan.lowongan.update', $lowongan->id) }}" class="space-y-8">
                @csrf
                @method('PUT')

                <div>
                    <div class="form-section-title">Detail Lowongan</div>
                    <div class="input-group">
                        <label for="judul" class="input-label">Judul Lowongan <span class="text-red-500">*</span></label>
                        <input type="text" name="judul" id="judul" value="{{ old('judul', $lowongan->judul) }}" required class="form-input @error('judul') border-red-500 @enderror">
                        @error('judul') <p class="error-message">{{ $message }}</p> @enderror
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6">
                        <div class="input-group">
                            <label for="lokasi" class="input-label">Lokasi <span class="text-red-500">*</span></label>
                            <input type="text" name="lokasi" id="lokasi" value="{{ old('lokasi', $lowongan->lokasi) }}" required class="form-input @error('lokasi') border-red-500 @enderror">
                            @error('lokasi') <p class="error-message">{{ $message }}</p> @enderror
                        </div>
                        <div class="input-group">
                            <label for="tipe_pekerjaan" class="input-label">Tipe Pekerjaan <span class="text-red-500">*</span></label>
                            <select name="tipe_pekerjaan" id="tipe_pekerjaan" required class="form-select @error('tipe_pekerjaan') border-red-500 @enderror">
                                <option value="">Pilih Tipe</option>
                                <option value="Full-time" {{ old('tipe_pekerjaan', $lowongan->tipe) == 'Full-time' ? 'selected' : '' }}>Full-time</option>
                                <option value="Part-time" {{ old('tipe_pekerjaan', $lowongan->tipe) == 'Part-time' ? 'selected' : '' }}>Part-time</option>
                                <option value="Magang" {{ old('tipe_pekerjaan', $lowongan->tipe) == 'Magang' ? 'selected' : '' }}>Magang</option>
                                <option value="Kontrak" {{ old('tipe_pekerjaan', $lowongan->tipe) == 'Kontrak' ? 'selected' : '' }}>Kontrak</option>
                            </select>
                            @error('tipe_pekerjaan') <p class="error-message">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="input-group">
                        <label for="gaji" class="input-label">Gaji (Estimasi/Bulan) <span class="text-gray-500 text-xs">(Opsional)</span></label>
                        <input type="number" name="gaji" id="gaji" value="{{ old('gaji', $lowongan->gaji_min) }}" class="form-input @error('gaji') border-red-500 @enderror" placeholder="Contoh: 5000000">
                        @error('gaji') <p class="error-message">{{ $message }}</p> @enderror
                    </div>

                    <div class="input-group">
                        <label for="tanggal_tutup" class="input-label">Tanggal Tutup <span class="text-red-500">*</span></label>
                        <input type="date" name="tanggal_tutup" id="tanggal_tutup" value="{{ old('tanggal_tutup', \Carbon\Carbon::parse($lowongan->tanggal_tutup)->format('Y-m-d')) }}" required class="form-input @error('tanggal_tutup') border-red-500 @enderror">
                        @error('tanggal_tutup') <p class="error-message">{{ $message }}</p> @enderror
                    </div>

                    <div class="input-group">
                        <label for="deskripsi_lowongan" class="input-label">Deskripsi Lowongan <span class="text-red-500">*</span></label>
                        <textarea name="deskripsi_lowongan" id="deskripsi_lowongan" rows="6" required class="form-textarea @error('deskripsi_lowongan') border-red-500 @enderror" placeholder="Jelaskan detail pekerjaan, tugas utama, dll.">{{ old('deskripsi_lowongan', $lowongan->deskripsi) }}</textarea>
                        @error('deskripsi_lowongan') <p class="error-message">{{ $message }}</p> @enderror
                    </div>

                    <div class="input-group">
                        <label for="kualifikasi" class="input-label">Kualifikasi <span class="text-gray-500 text-xs">(Opsional)</span></label>
                        <textarea name="kualifikasi" id="kualifikasi" rows="4" class="form-textarea @error('kualifikasi') border-red-500 @enderror" placeholder="Contoh: Pendidikan min. S1, Pengalaman 2 tahun, dll.">{{ old('kualifikasi', $lowongan->kualifikasi) }}</textarea>
                        @error('kualifikasi') <p class="error-message">{{ $message }}</p> @enderror
                    </div>

                    <div class="input-group">
                        <label for="tanggung_jawab" class="input-label">Tanggung Jawab <span class="text-gray-500 text-xs">(Opsional)</span></label>
                        <textarea name="tanggung_jawab" id="tanggung_jawab" rows="4" class="form-textarea @error('tanggung_jawab') border-red-500 @enderror" placeholder="Contoh: Melakukan riset pasar, Membuat laporan bulanan, dll.">{{ old('tanggung_jawab', $lowongan->tanggung_jawab) }}</textarea>
                        @error('tanggung_jawab') <p class="error-message">{{ $message }}</p> @enderror
                    </div>

                    <div class="input-group">
                        <label for="status" class="input-label">Status Lowongan <span class="text-red-500">*</span></label>
                        <select name="status" id="status" required class="form-select @error('status') border-red-500 @enderror">
                            <option value="Aktif" {{ old('status', $lowongan->status) == 'Aktif' ? 'selected' : '' }}>Aktif</option>
                            <option value="Nonaktif" {{ old('status', $lowongan->status) == 'Nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                            <option value="Ditutup" {{ old('status', $lowongan->status) == 'Ditutup' ? 'selected' : '' }}>Ditutup</option>
                        </select>
                        @error('status') <p class="error-message">{{ $message }}</p> @enderror
                    </div>

                </div>

                {{-- <div class="mt-10 flex flex-col-reverse sm:flex-row sm:justify-end sm:space-x-3">
                    <a href="{{ route('perusahaan.show', $lowongan->id) }}" class="action-button cancel-button mt-3 sm:mt-0 w-full sm:w-auto">
                        Batal
                    </a> --}}
                    <button type="submit" class="action-button save-button w-full sm:w-auto">
                        <i class="fas fa-save mr-2"></i>Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </main>

    @include('perusahaan.template.footer')
</body>
</html>
