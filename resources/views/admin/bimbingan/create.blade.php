<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tetapkan Bimbingan Magang Baru</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f7f8fc;
        }
        .form-container {
            background-color: white;
            padding: 2.5rem;
            border-radius: 1rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            max-width: 28rem; /* Max width similar to auth forms */
            margin-left: auto;
            margin-right: auto;
        }
        .form-title {
            font-size: 2rem;
            font-weight: bold;
            color: #1a202c; /* Dark gray for titles */
            text-align: center;
            margin-bottom: 2rem;
        }
        .input-label {
            display: block;
            text-sm;
            font-medium;
            text-gray-700;
        }
        .input-field {
            display: block;
            width: 100%;
            padding: 0.75rem 1rem;
            margin-top: 0.25rem;
            border: 1px solid #d1d5db; /* Gray-300 */
            border-radius: 0.5rem; /* Rounded-md */
            box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05); /* Shadow-sm */
            transition: all 0.2s ease-in-out;
        }
        .input-field:focus {
            border-color: #60a5fa; /* Blue-300 */
            ring: 2px;
            ring-color: #bfdbfe; /* Blue-200 */
            ring-opacity: 0.5;
            outline: none;
        }
        .submit-button {
            background-image: linear-gradient(to right, #4f46e5, #7c3aed);
            color: white;
            padding: 0.75rem 1.5rem;
            font-size: 1rem;
            border-radius: 0.5rem;
            transition: all 0.2s ease-in-out;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            width: 100%; /* Full width button */
            margin-top: 1.5rem;
        }
        .submit-button:hover {
            background-image: linear-gradient(to right, #4338ca, #6d28d9);
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }
        .error-message {
            color: #ef4444; /* Red-500 */
            font-size: 0.75rem; /* Text-xs */
            margin-top: 0.25rem;
        }
        .alert {
            padding: 1rem;
            border-radius: 0.5rem;
            margin-bottom: 1.5rem;
            border-left: 4px solid;
        }
        .alert-success {
            background-color: #d1fae5;
            border-color: #059669;
            color: #065f46;
        }
        .alert-error {
            background-color: #fee2e2;
            border-color: #ef4444;
            color: #991b1b;
        }
    </style>
</head>
<body class="bg-blue-50 text-gray-800">
    {{-- Include the admin navigation bar --}}
    @include('admin.template.navbar')

    <main class="min-h-screen flex items-center justify-center py-12 px-4 mt-20">
        <div class="form-container">
            <h1 class="form-title">Tetapkan Bimbingan Magang Baru</h1>

            {{-- Display success message if available --}}
            @if (session('success'))
                <div class="alert alert-success" role="alert">
                    {{ session('success') }}
                </div>
            @endif
            {{-- Display error message if available --}}
            @if (session('error'))
                <div class="alert alert-error" role="alert">
                    {{ session('error') }}
                </div>
            @endif

            <form action="{{ route('admin.bimbingan.store') }}" method="POST" class="space-y-6">
                @csrf

                <div>
                    <label for="mahasiswa_user_id" class="input-label">Mahasiswa:</label>
                    <select name="mahasiswa_user_id" id="mahasiswa_user_id" class="input-field" required>
                        <option value="">Pilih Mahasiswa</option>
                        @forelse($mahasiswaUsers as $mahasiswa)
                            <option value="{{ $mahasiswa->id }}" {{ old('mahasiswa_user_id') == $mahasiswa->id ? 'selected' : '' }}>
                                {{ $mahasiswa->name }} (NIM: {{ $mahasiswa->detailMahasiswa->nim ?? '-' }})
                            </option>
                        @empty
                            <option value="" disabled>Tidak ada mahasiswa tersedia</option>
                        @endforelse
                    </select>
                    @error('mahasiswa_user_id') <p class="error-message">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="pembimbing_id" class="input-label">Pembimbing:</label>
                    <select name="pembimbing_id" id="pembimbing_id" class="input-field" required>
                        <option value="">Pilih Pembimbing</option>
                        @forelse($pembimbings as $pembimbing)
                            <option value="{{ $pembimbing->id }}" {{ old('pembimbing_id') == $pembimbing->id ? 'selected' : '' }}>
                                {{ $pembimbing->nama_lengkap }} (Kuota Aktif: {{ $pembimbing->kuota_aktif }} / {{ $pembimbing->maks_kuota_bimbingan }})
                            </option>
                        @empty
                            <option value="" disabled>Tidak ada pembimbing tersedia</option>
                        @endforelse
                    </select>
                    @error('pembimbing_id') <p class="error-message">{{ $message }}</p> @enderror
                </div>

                <div class="flex items-center justify-end">
                    <button type="submit" class="submit-button">
                        Tetapkan Bimbingan
                    </button>
                </div>
            </form>
        </div>
    </main>

    {{-- Include the admin footer --}}
    @include('admin.template.footer')
</body>
</html>
