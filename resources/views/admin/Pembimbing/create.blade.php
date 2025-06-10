<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Tambah Pembimbing Baru</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-blue-50 text-gray-800">
    @include('admin.template.navbar')

    <main class="max-w-3xl mx-auto px-4 py-8 mt-16">
        <div class="bg-white p-8 rounded-xl shadow">
            <h1 class="text-2xl font-bold text-blue-800 mb-6 text-center">Tambah Pembimbing Baru</h1>

            @if ($errors->any())
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4 rounded" role="alert">
                    <p class="font-bold">Terjadi Kesalahan:</p>
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('admin.pembimbings.store') }}" method="POST">
                @csrf {{-- Tambahkan token CSRF untuk keamanan --}}
                <div class="space-y-6">
                    <div class="border-b border-gray-200 pb-6">
                        <h2 class="text-lg font-semibold text-gray-700 mb-4">Informasi Akun Login</h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="username_login" class="block text-sm font-medium text-gray-700">Username Login (NIP)</label>
                                <input type="text" name="nip" id="username_login" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2 focus:ring-blue-500 focus:border-blue-500" value="{{ old('nip') }}" required>
                            </div>
                            <div>
                                <label for="email_login" class="block text-sm font-medium text-gray-700">Email Login</label>
                                <input type="email" name="email_login" id="email_login" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2 focus:ring-blue-500 focus:border-blue-500" value="{{ old('email_login') }}" required>
                            </div>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                            <div>
                                <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                                <input type="password" name="password" id="password" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2 focus:ring-blue-500 focus:border-blue-500" required>
                            </div>
                            <div>
                                <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Konfirmasi Password</label>
                                <input type="password" name="password_confirmation" id="password_confirmation" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2 focus:ring-blue-500 focus:border-blue-500" required>
                            </div>
                        </div>
                    </div>

                    <div class="border-b border-gray-200 pb-6">
                        <h2 class="text-lg font-semibold text-gray-700 mb-4">Informasi Pribadi Pembimbing</h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            {{-- NIP field ditangani oleh 'username_login' di atas dan diteruskan sebagai 'nip' dalam permintaan --}}
                            <div>
                                <label for="nama_lengkap" class="block text-sm font-medium text-gray-700">Nama Lengkap</label>
                                <input type="text" name="nama_lengkap" id="nama_lengkap" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2 focus:ring-blue-500 focus:border-blue-500" value="{{ old('nama_lengkap') }}" required>
                            </div>
                            <div>
                                <label for="email_institusi" class="block text-sm font-medium text-gray-700">Email Institusi</label>
                                <input type="email" name="email_institusi" id="email_institusi" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2 focus:ring-blue-500 focus:border-blue-500" value="{{ old('email_institusi') }}" required>
                            </div>
                            <div>
                                <label for="nomor_telepon" class="block text-sm font-medium text-gray-700">Nomor Telepon (Opsional)</label>
                                <input type="text" name="nomor_telepon" id="nomor_telepon" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2 focus:ring-blue-500 focus:border-blue-500" value="{{ old('nomor_telepon') }}">
                            </div>
                            <div>
                                <label for="jabatan_fungsional" class="block text-sm font-medium text-gray-700">Jabatan Fungsional (Opsional)</label>
                                <input type="text" name="jabatan_fungsional" id="jabatan_fungsional" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2 focus:ring-blue-500 focus:border-blue-500" value="{{ old('jabatan_fungsional') }}">
                            </div>
                            <div>
                                <label for="program_studi_homebase" class="block text-sm font-medium text-gray-700">Program Studi Homebase (Opsional)</label>
                                <input type="text" name="program_studi_homebase" id="program_studi_homebase" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2 focus:ring-blue-500 focus:border-blue-500" value="{{ old('program_studi_homebase') }}">
                            </div>
                            <div class="col-span-1 md:col-span-2">
                                <label for="bidang_keahlian_utama" class="block text-sm font-medium text-gray-700">Bidang Keahlian Utama (Opsional)</label>
                                <textarea name="bidang_keahlian_utama" id="bidang_keahlian_utama" rows="3" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2 focus:ring-blue-500 focus:border-blue-500">{{ old('bidang_keahlian_utama') }}</textarea>
                            </div>
                        </div>
                    </div>

                    <div>
                        <h2 class="text-lg font-semibold text-gray-700 mb-4">Pengaturan Bimbingan</h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="maks_kuota_bimbingan" class="block text-sm font-medium text-gray-700">Maksimal Kuota Bimbingan</label>
                                <input type="number" name="maks_kuota_bimbingan" id="maks_kuota_bimbingan" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2 focus:ring-blue-500 focus:border-blue-500" value="{{ old('maks_kuota_bimbingan', 0) }}" min="0" required>
                            </div>
                            <div>
                                <label for="status_aktif" class="block text-sm font-medium text-gray-700">Status Aktif</label>
                                <select name="status_aktif" id="status_aktif" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2 focus:ring-blue-500 focus:border-blue-500" required>
                                    <option value="1" {{ old('status_aktif', 1) == 1 ? 'selected' : '' }}>Aktif</option>
                                    <option value="0" {{ old('status_aktif', 1) == 0 ? 'selected' : '' }}>Tidak Aktif</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-8 flex justify-end space-x-3">
                    <a href="{{ route('admin.pembimbings.index') }}" class="bg-gray-300 text-gray-800 px-5 py-2 rounded-md hover:bg-gray-400">Batal</a>
                    <button type="submit" class="bg-blue-600 text-white px-5 py-2 rounded-md hover:bg-blue-700">Tambah Pembimbing</button>
                </div>
            </form>
        </div>
    </main>

    @include('admin.template.footer')
</body>
</html>
