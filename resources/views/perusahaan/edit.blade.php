<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Lowongan - {{ $lowongan->judul ?? 'Lowongan' }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css" xintegrity="sha512-SzlrxWUlpfuzQ+pcUCosxcglQRNAq/DZjVsC0lE40xsADsfeQoEypE+enwcOiGjk/bSuGGKHEyjSoQ1zVisanQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    
    {{-- Toastify-JS CDN links --}}
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/toastify-js"></script>

    <style>
        body {
            background-color: #f7f8fc;
            font-family: 'Inter', sans-serif; /* Consistent font */
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

            {{-- Validation errors via Toastify-JS are handled at the bottom, so we remove the large block here --}}
            {{-- @if ($errors->any())
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
            @endif --}}

            <form method="POST" action="{{ route('perusahaan.lowongan.update', $lowongan->id) }}" class="space-y-8">
                @csrf
                @method('PUT')

                <div>
                    <div class="form-section-title">Detail Lowongan</div>
                    <div class="input-group">
                        <label for="judul" class="input-label">Judul Lowongan <span class="text-red-500">*</span></label>
                        {{-- Changed to text input for direct editing, similar to your original edit page --}}
                        <input type="text" name="judul" id="judul" value="{{ old('judul', $lowongan->judul) }}" required class="form-input @error('judul') border-red-500 @enderror">
                        @error('judul') <p class="error-message">{{ $message }}</p> @enderror
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6">
                        <div class="input-group">
                            <label for="provinsi" class="input-label">Provinsi <span class="text-red-500">*</span></label>
                            <select name="provinsi" id="provinsi" required class="form-select @error('provinsi') border-red-500 @enderror">
                                <option value="">Pilih Provinsi</option>
                                <option value="DKI Jakarta" {{ old('provinsi', $lowongan->provinsi) == 'DKI Jakarta' ? 'selected' : '' }}>DKI Jakarta</option>
                                <option value="Jawa Barat" {{ old('provinsi', $lowongan->provinsi) == 'Jawa Barat' ? 'selected' : '' }}>Jawa Barat</option>
                                <option value="Jawa Tengah" {{ old('provinsi', $lowongan->provinsi) == 'Jawa Tengah' ? 'selected' : '' }}>Jawa Tengah</option>
                                <option value="Jawa Timur" {{ old('provinsi', $lowongan->provinsi) == 'Jawa Timur' ? 'selected' : '' }}>Jawa Timur</option>
                                <option value="Banten" {{ old('provinsi', $lowongan->provinsi) == 'Banten' ? 'selected' : '' }}>Banten</option>
                                <option value="Yogyakarta" {{ old('provinsi', $lowongan->provinsi) == 'Yogyakarta' ? 'selected' : '' }}>Yogyakarta</option>
                            </select>
                            @error('provinsi') <p class="error-message">{{ $message }}</p> @enderror
                        </div>
                        <div class="input-group">
                            <label for="kota" class="input-label">Kota <span class="text-red-500">*</span></label>
                            <select name="kota" id="kota" required class="form-select @error('kota') border-red-500 @enderror">
                                <option value="">Pilih Kota</option>
                                {{-- These options should ideally be dynamically loaded based on selected provinsi via JavaScript --}}
                                <option value="Jakarta Selatan" {{ old('kota', $lowongan->kota) == 'Jakarta Selatan' ? 'selected' : '' }}>Jakarta Selatan</option>
                                <option value="Bandung" {{ old('kota', $lowongan->kota) == 'Bandung' ? 'selected' : '' }}>Bandung</option>
                                <option value="Surabaya" {{ old('kota', $lowongan->kota) == 'Surabaya' ? 'selected' : '' }}>Surabaya</option>
                                <option value="Semarang" {{ old('kota', $lowongan->kota) == 'Semarang' ? 'selected' : '' }}>Semarang</option>
                                <option value="Tangerang" {{ old('kota', $lowongan->kota) == 'Tangerang' ? 'selected' : '' }}>Tangerang</option>
                                <option value="Yogyakarta" {{ old('kota', $lowongan->kota) == 'Yogyakarta' ? 'selected' : '' }}>Yogyakarta</option>
                            </select>
                            @error('kota') <p class="error-message">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="input-group">
                        <label for="alamat" class="input-label">Alamat Lengkap <span class="text-gray-500 text-xs">(Opsional)</span></label>
                        <textarea name="alamat" id="alamat" rows="2" class="form-textarea @error('alamat') border-red-500 @enderror" placeholder="Contoh: Jl. Sudirman No. 123">{{ old('alamat', $lowongan->alamat) }}</textarea>
                        @error('alamat') <p class="error-message">{{ $message }}</p> @enderror
                    </div>

                    <div class="input-group">
                        <label for="kode_pos" class="input-label">Kode Pos <span class="text-gray-500 text-xs">(Opsional)</span></label>
                        <input type="text" name="kode_pos" id="kode_pos" value="{{ old('kode_pos', $lowongan->kode_pos) }}" class="form-input @error('kode_pos') border-red-500 @enderror" placeholder="Contoh: 12345">
                        @error('kode_pos') <p class="error-message">{{ $message }}</p> @enderror
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

                    <div class="input-group">
                        <label for="gaji_min" class="input-label">Gaji (Estimasi/Bulan) <span class="text-gray-500 text-xs">(Opsional)</span></label>
                        {{-- Assuming 'gaji_min' in your database for this field --}}
                        <input type="number" name="gaji_min" id="gaji_min" value="{{ old('gaji_min', $lowongan->gaji_min) }}" class="form-input @error('gaji_min') border-red-500 @enderror" placeholder="Contoh: 5000000">
                        @error('gaji_min') <p class="error-message">{{ $message }}</p> @enderror
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6">
                        <div class="input-group">
                            <label for="tanggal_buka" class="input-label">Tanggal Buka <span class="text-red-500">*</span></label>
                            <input type="date" name="tanggal_buka" id="tanggal_buka" value="{{ old('tanggal_buka', \Carbon\Carbon::parse($lowongan->tanggal_buka)->format('Y-m-d')) }}" required class="form-input @error('tanggal_buka') border-red-500 @enderror" readonly>
                            @error('tanggal_buka') <p class="error-message">{{ $message }}</p> @enderror
                        </div>
                        <div class="input-group">
                            <label for="tanggal_tutup" class="input-label">Tanggal Tutup <span class="text-red-500">*</span></label>
                            <input type="date" name="tanggal_tutup" id="tanggal_tutup" value="{{ old('tanggal_tutup', \Carbon\Carbon::parse($lowongan->tanggal_tutup)->format('Y-m-d')) }}" required class="form-input @error('tanggal_tutup') border-red-500 @enderror">
                            @error('tanggal_tutup') <p class="error-message">{{ $message }}</p> @enderror
                        </div>
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

                <div class="mt-10 flex flex-col-reverse sm:flex-row sm:justify-end sm:space-x-3">
                    {{-- Changed href to go back to the management page --}}
                    <a href="{{ route('perusahaan.lowongan') }}" class="action-button cancel-button mt-3 sm:mt-0 w-full sm:w-auto">
                        Batal
                    </a>
                    <button type="submit" class="action-button save-button w-full sm:w-auto">
                        <i class="fas fa-save mr-2"></i>Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </main>


    {{-- Toastify-JS Integration --}}
    <script>
        // Display success message
        @if (session('success'))
            Toastify({
                text: "{{ session('success') }}",
                duration: 3000, // 3 seconds
                newWindow: true,
                close: true,
                gravity: "top", // `top` or `bottom`
                position: "right", // `left`, `center` or `right`
                stopOnFocus: true, // Prevents dismissing on hover
                style: {
                    background: "linear-gradient(to right, #4CAF50, #66BB6A)", // Green gradient
                    borderRadius: "0.6rem", // Tailored to your form-card rounded-lg
                    boxShadow: "0 4px 12px rgba(0,0,0,0.15)", // A subtle shadow
                    padding: "1rem 1.5rem" // Good padding
                },
                offset: { // Offset from the corner
                    x: 20, // horizontal axis - can be a number or a string indicating unity. eg: "2em"
                    y: 20 // vertical axis - can be a number or a string indicating unity. eg: "2em"
                },
                onClick: function(){} // Callback after click
            }).showToast();
        @endif

        // Display error message (e.g., from controller catches)
        @if (session('error'))
            Toastify({
                text: "{{ session('error') }}",
                duration: 5000, // Longer duration for errors
                newWindow: true,
                close: true,
                gravity: "top",
                position: "right",
                stopOnFocus: true,
                style: {
                    background: "linear-gradient(to right, #EF4444, #DC2626)", // Red gradient
                    borderRadius: "0.6rem",
                    boxShadow: "0 4px 12px rgba(0,0,0,0.15)",
                    padding: "1rem 1.5rem"
                },
                offset: {
                    x: 20,
                    y: 20
                },
                onClick: function(){}
            }).showToast();
        @endif

        // Display validation errors (iterates through $errors->all())
        @if ($errors->any())
            @foreach ($errors->all() as $error)
                Toastify({
                    text: "{{ $error }}",
                    duration: 5000,
                    newWindow: true,
                    close: true,
                    gravity: "top",
                    position: "right",
                    stopOnFocus: true,
                    style: {
                        background: "linear-gradient(to right, #F59E0B, #D97706)", // Orange/Amber gradient for warnings/validation
                        borderRadius: "0.6rem",
                        boxShadow: "0 4px 12px rgba(0,0,0,0.15)",
                        padding: "1rem 1.5rem"
                    },
                    offset: {
                        x: 20,
                        y: 20 + {{ $loop->index * 70 }} // Stagger multiple toasts if many errors
                    },
                    onClick: function(){}
                }).showToast();
            @endforeach
        @endif
    </script>
</body>
</html>