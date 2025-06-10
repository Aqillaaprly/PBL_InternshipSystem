<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Pembimbing - {{ $pembimbing->nama_lengkap ?? 'Tidak Ditemukan' }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f7f8fc;
        }
        .form-section {
            background-color: white;
            padding: 2rem;
            border-radius: 1rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .section-title {
            font-size: 1.5rem;
            font-weight: bold;
            color: #1a202c; /* Dark gray for titles */
            margin-bottom: 1.5rem;
            border-bottom: 2px solid #e2e8f0; /* Light gray border for separation */
            padding-bottom: 0.75rem;
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
        .checkbox-container {
            display: flex;
            align-items: center;
            margin-top: 0.5rem;
        }
        .checkbox-input {
            margin-right: 0.5rem;
            border-radius: 0.25rem; /* For rounded corners on checkbox */
        }
        .submit-button {
            background-image: linear-gradient(to right, #4f46e5, #7c3aed);
            color: white;
            padding: 0.625rem 1.25rem;
            font-size: 0.875rem;
            border-radius: 0.5rem;
            transition: all 0.2s ease-in-out;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
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
        /* Styles for the new table for bimbingan */
        .table-container {
            overflow-x: auto;
            border-radius: 0.5rem;
            border: 1px solid #e2e8f0;
        }
        .table-header {
            background-color: #f8fafc; /* Gray-50 */
            color: #4b5563; /* Gray-700 */
            text-transform: uppercase;
            font-size: 0.75rem;
            text-align: left;
        }
        .table-row:nth-child(even) {
            background-color: #f9fafb; /* Gray-50 alternative for zebra striping */
        }
        .table-row:hover {
            background-color: #f3f4f6; /* Gray-100 on hover */
        }
        .table-cell {
            padding: 1rem 1.25rem;
            white-space: nowrap; /* Prevent text wrapping */
        }
        .action-button-table {
            background-image: linear-gradient(to right, #4f46e5, #7c3aed); /* Use the same gradient as other buttons */
            color: white;
            padding: 0.375rem 0.75rem; /* Smaller padding for table buttons */
            font-size: 0.75rem; /* Smaller font size */
            border-radius: 0.375rem; /* Slightly smaller rounded corners */
            transition: all 0.2s ease-in-out;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
            box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05); /* Small shadow */
        }
        .action-button-table:hover {
            background-image: linear-gradient(to right, #4338ca, #6d28d9);
            transform: translateY(-1px);
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
        }
        .delete-button-table {
            background-color: #fef2f2; /* Red-50 */
            color: #dc2626; /* Red-600 */
            padding: 0.375rem 0.75rem;
            font-size: 0.75rem;
            border-radius: 0.375rem;
            transition: all 0.2s ease-in-out;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
        }
        .delete-button-table:hover {
            background-color: #fee2e2; /* Red-100 */
            transform: translateY(-1px);
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>
<body class="bg-blue-50 text-gray-800">
    {{-- Include the admin navigation bar --}}
    @include('admin.template.navbar')

    <main class="max-w-screen-xl mx-auto px-4 py-8 mt-20"> {{-- Increased max-w-3xl to max-w-screen-xl for more horizontal space --}}
        {{-- Display success message if available --}}
        @if (session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 px-4 py-3 rounded-md mb-4" role="alert">
                {{ session('success') }}
            </div>
        @endif
        {{-- Display error message if available --}}
        @if (session('error'))
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 px-4 py-3 rounded-md mb-4" role="alert">
                {{ session('error') }}
            </div>
        @endif

        @if(isset($pembimbing) && $pembimbing->id)
            <div class="flex flex-col md:flex-row gap-8"> {{-- Added flex container for two columns --}}
                <div class="md:w-1/2 w-full"> {{-- Left column for editing pembimbing details --}}
                    <div class="form-section mb-8">
                        <h1 class="section-title">Edit Detail Pembimbing</h1>

                        <form action="{{ route('admin.pembimbings.update', $pembimbing->id) }}" method="POST" class="space-y-6">
                            @csrf
                            @method('PUT')

                            {{-- Username Login Field --}}
                            <div>
                                <label for="username_login" class="block text-sm font-medium text-gray-700">Username Login:</label>
                                <input type="text" name="username_login" id="username_login" value="{{ old('username_login', $pembimbing->user->username ?? '') }}" class="input-field" required>
                                @error('username_login') <p class="error-message">{{ $message }}</p> @enderror
                            </div>

                            {{-- Email Login Field --}}
                            <div>
                                <label for="email_login" class="block text-sm font-medium text-gray-700">Email Login:</label>
                                <input type="email" name="email_login" id="email_login" value="{{ old('email_login', $pembimbing->user->email ?? '') }}" class="input-field" required>
                                @error('email_login') <p class="error-message">{{ $message }}</p> @enderror
                            </div>

                            {{-- Password Field --}}
                            <div>
                                <label for="password" class="block text-sm font-medium text-gray-700">Password (Biarkan kosong jika tidak ingin mengubah):</label>
                                <input type="password" name="password" id="password" class="input-field">
                                @error('password') <p class="error-message">{{ $message }}</p> @enderror
                            </div>

                            {{-- Confirm Password Field --}}
                            <div>
                                <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Konfirmasi Password:</label>
                                <input type="password" name="password_confirmation" id="password_confirmation" class="input-field">
                            </div>

                            {{-- NIP Field --}}
                            <div>
                                <label for="nip" class="block text-sm font-medium text-gray-700">NIP:</label>
                                <input type="text" name="nip" id="nip" value="{{ old('nip', $pembimbing->nip ?? '') }}" class="input-field" required>
                                @error('nip') <p class="error-message">{{ $message }}</p> @enderror
                            </div>

                            {{-- Nama Lengkap Field --}}
                            <div>
                                <label for="nama_lengkap" class="block text-sm font-medium text-gray-700">Nama Lengkap:</label>
                                <input type="text" name="nama_lengkap" id="nama_lengkap" value="{{ old('nama_lengkap', $pembimbing->nama_lengkap ?? '') }}" class="input-field" required>
                                @error('nama_lengkap') <p class="error-message">{{ $message }}</p> @enderror
                            </div>

                            {{-- Email Institusi Field --}}
                            <div>
                                <label for="email_institusi" class="block text-sm font-medium text-gray-700">Email Institusi:</label>
                                <input type="email" name="email_institusi" id="email_institusi" value="{{ old('email_institusi', $pembimbing->email_institusi ?? '') }}" class="input-field" required>
                                @error('email_institusi') <p class="error-message">{{ $message }}</p> @enderror
                            </div>

                            {{-- Nomor Telepon Field --}}
                            <div>
                                <label for="nomor_telepon" class="block text-sm font-medium text-gray-700">Nomor Telepon:</label>
                                <input type="text" name="nomor_telepon" id="nomor_telepon" value="{{ old('nomor_telepon', $pembimbing->nomor_telepon ?? '') }}" class="input-field">
                                @error('nomor_telepon') <p class="error-message">{{ $message }}</p> @enderror
                            </div>

                            {{-- Jabatan Fungsional Field --}}
                            <div>
                                <label for="jabatan_fungsional" class="block text-sm font-medium text-gray-700">Jabatan Fungsional:</label>
                                <input type="text" name="jabatan_fungsional" id="jabatan_fungsional" value="{{ old('jabatan_fungsional', $pembimbing->jabatan_fungsional ?? '') }}" class="input-field">
                                @error('jabatan_fungsional') <p class="error-message">{{ $message }}</p> @enderror
                            </div>

                            {{-- Program Studi Homebase Field --}}
                            <div>
                                <label for="program_studi_homebase" class="block text-sm font-medium text-gray-700">Program Studi Homebase:</label>
                                <input type="text" name="program_studi_homebase" id="program_studi_homebase" value="{{ old('program_studi_homebase', $pembimbing->program_studi_homebase ?? '') }}" class="input-field">
                                @error('program_studi_homebase') <p class="error-message">{{ $message }}</p> @enderror
                            </div>

                            {{-- Bidang Keahlian Utama Field --}}
                            <div>
                                <label for="bidang_keahlian_utama" class="block text-sm font-medium text-gray-700">Bidang Keahlian Utama:</label>
                                <textarea name="bidang_keahlian_utama" id="bidang_keahlian_utama" rows="3" class="input-field">{{ old('bidang_keahlian_utama', $pembimbing->bidang_keahlian_utama ?? '') }}</textarea>
                                @error('bidang_keahlian_utama') <p class="error-message">{{ $message }}</p> @enderror
                            </div>

                            {{-- Maksimal Kuota Bimbingan Field --}}
                            <div>
                                <label for="maks_kuota_bimbingan" class="block text-sm font-medium text-gray-700">Maksimal Kuota Bimbingan:</label>
                                <input type="number" name="maks_kuota_bimbingan" id="maks_kuota_bimbingan" value="{{ old('maks_kuota_bimbingan', $pembimbing->maks_kuota_bimbingan ?? '') }}" class="input-field" required min="0">
                                @error('maks_kuota_bimbingan') <p class="error-message">{{ $message }}</p> @enderror
                            </div>

                            {{-- Status Aktif Checkbox --}}
                            <div class="checkbox-container">
                                <input type="checkbox" name="status_aktif" id="status_aktif" value="1" {{ old('status_aktif', $pembimbing->status_aktif ?? false) ? 'checked' : '' }} class="checkbox-input">
                                <label for="status_aktif" class="text-sm font-medium text-gray-700">Aktif</label>
                                @error('status_aktif') <p class="error-message">{{ $message }}</p> @enderror
                            </div>

                            <div class="flex items-center justify-end mt-6">
                                <button type="submit" class="submit-button">
                                    Update Pembimbing
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="md:w-1/2 w-full"> {{-- Right column for Bimbingan info --}}
                    {{-- SECTION: Mahasiswa Bimbingan yang Sudah Ada --}}
                    <div class="form-section mb-8">
                        <div class="flex justify-between items-center mb-4">
                            <h2 class="section-title mb-0 border-none pb-0">Mahasiswa Bimbingan Saat Ini</h2>
                            {{-- Button to add new guidance --}}
                            <a href="{{ route('admin.bimbingan.create') }}" class="submit-button px-4 py-2">
                                Tambahkan Bimbingan
                            </a>
                        </div>
                        @if($pembimbing->bimbinganMagangs->isNotEmpty())
                            <div class="table-container">
                                <table class="min-w-full text-sm">
                                    <thead class="table-header">
                                        <tr>
                                            <th class="table-cell">No</th>
                                            <th class="table-cell">NIM</th>
                                            <th class="table-cell">Nama Mahasiswa</th>
                                            <th class="table-cell">Status Bimbingan</th>
                                            <th class="table-cell text-center">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody class="text-gray-600">
                                        @foreach($pembimbing->bimbinganMagangs as $index => $bimbingan)
                                            <tr class="table-row">
                                                <td class="table-cell">{{ $index + 1 }}</td>
                                                <td class="table-cell">{{ $bimbingan->mahasiswa->detailMahasiswa->nim ?? '-' }}</td>
                                                <td class="table-cell">{{ $bimbingan->mahasiswa->name ?? '-' }}</td>
                                                <td class="table-cell">
                                                    <span class="badge
                                                        @if($bimbingan->status_bimbingan == 'Aktif') bg-green-100 text-green-700
                                                        @elseif($bimbingan->status_bimbingan == 'Selesai') bg-blue-100 text-blue-700
                                                        @else bg-red-100 text-red-700 @endif">
                                                        {{ $bimbingan->status_bimbingan }}
                                                    </span>
                                                </td>
                                                <td class="table-cell text-center">
                                                    <div class="flex items-center justify-center space-x-2">
                                                        {{-- Tombol Edit Bimbingan (Ini akan mengarah ke form edit bimbingan spesifik) --}}
                                                        <a href="{{ route('admin.bimbingan.edit', $bimbingan->id) }}" class="action-button-table">Edit</a>

                                                        {{-- Tombol Hapus Bimbingan --}}
                                                        <form action="{{ route('admin.bimbingan.destroy', $bimbingan->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Yakin ingin menghapus bimbingan ini?');">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="delete-button-table">Hapus</button>
                                                        </form>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <p class="text-gray-500 mt-4">Pembimbing ini belum memiliki mahasiswa bimbingan yang tercatat.</p>
                        @endif
                    </div>

                    {{-- The "Tetapkan Bimbingan Magang Baru" form is removed from this page --}}
                    {{-- as requested, and replaced by the "Tambahkan Bimbingan" button --}}

                </div> {{-- End of right column --}}
            </div> {{-- End of flex container --}}
        @else
            <div class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-4 rounded-md mt-4" role="alert">
                <p class="font-bold">Data Pembimbing Tidak Ditemukan</p>
                <p>Data tidak valid atau tidak ditemukan.</p>
            </div>
        @endif
    </main>

    {{-- Include the admin footer --}}
    @include('admin.template.footer')
</body>
</html>
