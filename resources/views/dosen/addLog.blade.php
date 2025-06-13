<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Tambah Log Bimbingan - {{ $bimbingan->mahasiswa->name ?? $bimbingan->mahasiswa->username }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css" integrity="sha512-SzlrxWUlpfuzQ+pcUCosxcglQRNAq/DZjVsC0lE40xsADsfeQoEypE+enwcOiGjk/bSuGGKHEyjSoQ1zVisanQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
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
            text-decoration: none; /* Menghilangkan garis bawah pada link */
        }
        .action-button:hover {
            transform: translateY(-1px);
        }
        .save-button {
             background-image: linear-gradient(to right, #4f46e5, #7c3aed);
             color: white;
             box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1), 0 2px 4px -1px rgba(0,0,0,0.06);
             border: none;
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
<body class="flex flex-col min-h-screen">

    @include('dosen.template.navbar')

    <main class="flex-grow max-w-3xl mx-auto px-4 py-10 w-full">
        <div class="form-card">
            <div class="flex justify-between items-center mb-6">
                <h1 class="form-section-title" style="margin-bottom: 0; border-bottom: none;">Tambah Log Bimbingan</h1>
                <a href="{{ route('dosen.data_log') }}" class="text-sm text-indigo-600 hover:underline">&larr; Kembali ke Daftar Log</a>
            </div>

            <form action="{{ route('dosen.log_bimbingan.store', $bimbingan->id) }}" method="POST">
                @csrf

                <div class="input-group">
                    <label for="metode_bimbingan" class="input-label">Metode Bimbingan</label>
                    <input type="text" id="metode_bimbingan" name="metode_bimbingan" required class="form-input" placeholder="Contoh: WhatsApp / Zoom / Tatap Muka" value="{{ old('metode_bimbingan') }}">
                    @error('metode_bimbingan')
                        <p class="error-message">{{ $message }}</p>
                    @enderror
                </div>

                <div class="input-group">
                    <label for="waktu_bimbingan" class="input-label">Waktu Bimbingan</label>
                    <input type="datetime-local" id="waktu_bimbingan" name="waktu_bimbingan" required class="form-input" value="{{ old('waktu_bimbingan') }}">
                    @error('waktu_bimbingan')
                        <p class="error-message">{{ $message }}</p>
                    @enderror
                </div>

                <div class="input-group">
                    <label for="topik_bimbingan" class="input-label">Topik Bimbingan</label>
                    <textarea id="topik_bimbingan" name="topik_bimbingan" required class="form-textarea" rows="3">{{ old('topik_bimbingan') }}</textarea>
                    @error('topik_bimbingan')
                        <p class="error-message">{{ $message }}</p>
                    @enderror
                </div>

                <div class="input-group">
                    <label for="deskripsi" class="input-label">Deskripsi</label>
                    <textarea id="deskripsi" name="deskripsi" required class="form-textarea" rows="4">{{ old('deskripsi') }}</textarea>
                    @error('deskripsi')
                        <p class="error-message">{{ $message }}</p>
                    @enderror
                </div>

                <div class="input-group">
                    <label for="nilai" class="input-label">Nilai</label>
                    <input type="number" id="nilai" name="nilai" min="0" max="100" required class="form-input" placeholder="Masukkan nilai antara 0 - 100" value="{{ old('nilai') }}">
                    @error('nilai')
                        <p class="error-message">{{ $message }}</p>
                    @enderror
                </div>

                <div class="input-group">
                    <label for="komentar" class="input-label">Komentar (Opsional)</label>
                    <textarea id="komentar" name="komentar" class="form-textarea" rows="3">{{ old('komentar') }}</textarea>
                    @error('komentar')
                        <p class="error-message">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex justify-end items-center mt-8 gap-4">
                    <a href="{{ route('dosen.data_log') }}" class="action-button cancel-button">Batal</a>
                    <button type="submit" class="action-button save-button">Simpan Log</button>
                </div>
            </form>
        </div>
    </main>

</body>
</html>