<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Penugasan Pembimbing - Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    @include('admin.template.navbar')

    <main class="max-w-2xl mx-auto px-4 py-10 mt-16">
        <div class="bg-white p-8 rounded-lg shadow-md">
            <h1 class="text-2xl font-semibold text-gray-800 mb-6">Edit Penugasan Pembimbing</h1>

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

            <form method="POST" action="{{ route('admin.penugasan-pembimbing.update', $penugasan_pembimbing->id) }}">
                @csrf
                @method('PUT')

                <div class="mb-4">
                    <label for="mahasiswa_user_id_display" class="block text-sm font-medium text-gray-700 mb-1">Mahasiswa</label>
                    <input type="text" id="mahasiswa_user_id_display" value="{{ $penugasan_pembimbing->mahasiswa->name }} ({{ $penugasan_pembimbing->mahasiswa->username }})" readonly class="mt-1 block w-full px-3 py-2 border border-gray-300 bg-gray-100 rounded-md shadow-sm sm:text-sm">
                    {{-- mahasiswa_user_id tidak diubah saat edit --}}
                </div>

                <div class="mb-4">
                    <label for="pembimbing_id" class="block text-sm font-medium text-gray-700 mb-1">Dosen Pembimbing <span class="text-red-500">*</span></label>
                    <select name="pembimbing_id" id="pembimbing_id" required class="mt-1 block w-full px-3 py-2 border @error('pembimbing_id') border-red-500 @else border-gray-300 @enderror bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        <option value="">Pilih Dosen Pembimbing</option>
                        @foreach ($pembimbings as $pembimbing)
                            <option value="{{ $pembimbing->id }}" {{ old('pembimbing_id', $penugasan_pembimbing->pembimbing_id) == $pembimbing->id ? 'selected' : '' }}>
                                 {{ $pembimbing->user->name ?? $pembimbing->nama_lengkap }} ({{ $pembimbing->nip }}) - Sisa Kuota: {{ $pembimbing->id == $penugasan_pembimbing->pembimbing_id ? ($pembimbing->maks_kuota_bimbingan - $pembimbing->kuota_bimbingan_aktif) : ($pembimbing->maks_kuota_bimbingan - $pembimbing->kuota_bimbingan_aktif) }}
                            </option>
                        @endforeach
                    </select>
                    @error('pembimbing_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="mb-4">
                    <label for="company_id" class="block text-sm font-medium text-gray-700 mb-1">Perusahaan Tempat Magang</label>
                    <select name="company_id" id="company_id" class="mt-1 block w-full px-3 py-2 border @error('company_id') border-red-500 @else border-gray-300 @enderror bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        <option value="">Pilih Perusahaan (Jika Ada)</option>
                        @foreach ($companies as $company)
                            <option value="{{ $company->id }}" {{ old('company_id', $penugasan_pembimbing->company_id) == $company->id ? 'selected' : '' }}>
                                {{ $company->nama_perusahaan }}
                            </option>
                        @endforeach
                    </select>
                     <p class="text-xs text-gray-500 mt-1">Kosongkan jika belum ditentukan atau tidak relevan.</p>
                    @error('company_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                 <input type="hidden" name="lowongan_id" value="{{ $penugasan_pembimbing->lowongan_id }}">


                <div class="mb-4">
                    <label for="periode_magang" class="block text-sm font-medium text-gray-700 mb-1">Periode Magang <span class="text-red-500">*</span></label>
                    <input type="text" name="periode_magang" id="periode_magang" value="{{ old('periode_magang', $penugasan_pembimbing->periode_magang) }}" required class="mt-1 block w-full px-3 py-2 border @error('periode_magang') border-red-500 @else border-gray-300 @enderror rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                    @error('periode_magang') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                 <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label for="tanggal_mulai" class="block text-sm font-medium text-gray-700 mb-1">Tanggal Mulai</label>
                        <input type="date" name="tanggal_mulai" id="tanggal_mulai" value="{{ old('tanggal_mulai', $penugasan_pembimbing->tanggal_mulai ? $penugasan_pembimbing->tanggal_mulai->format('Y-m-d') : '') }}" class="mt-1 block w-full px-3 py-2 border @error('tanggal_mulai') border-red-500 @else border-gray-300 @enderror rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        @error('tanggal_mulai') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="tanggal_selesai" class="block text-sm font-medium text-gray-700 mb-1">Tanggal Selesai</label>
                        <input type="date" name="tanggal_selesai" id="tanggal_selesai" value="{{ old('tanggal_selesai', $penugasan_pembimbing->tanggal_selesai ? $penugasan_pembimbing->tanggal_selesai->format('Y-m-d') : '') }}" class="mt-1 block w-full px-3 py-2 border @error('tanggal_selesai') border-red-500 @else border-gray-300 @enderror rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        @error('tanggal_selesai') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="mb-4">
                    <label for="status_bimbingan" class="block text-sm font-medium text-gray-700 mb-1">Status Bimbingan <span class="text-red-500">*</span></label>
                    <select name="status_bimbingan" id="status_bimbingan" required class="mt-1 block w-full px-3 py-2 border @error('status_bimbingan') border-red-500 @else border-gray-300 @enderror bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        <option value="Aktif" {{ old('status_bimbingan', $penugasan_pembimbing->status_bimbingan) == 'Aktif' ? 'selected' : '' }}>Aktif</option>
                        <option value="Selesai" {{ old('status_bimbingan', $penugasan_pembimbing->status_bimbingan) == 'Selesai' ? 'selected' : '' }}>Selesai</option>
                        <option value="Dibatalkan" {{ old('status_bimbingan', $penugasan_pembimbing->status_bimbingan) == 'Dibatalkan' ? 'selected' : '' }}>Dibatalkan</option>
                    </select>
                    @error('status_bimbingan') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="mb-6">
                    <label for="catatan_koordinator" class="block text-sm font-medium text-gray-700 mb-1">Catatan Koordinator</label>
                    <textarea name="catatan_koordinator" id="catatan_koordinator" rows="3" class="mt-1 block w-full px-3 py-2 border @error('catatan_koordinator') border-red-500 @else border-gray-300 @enderror rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">{{ old('catatan_koordinator', $penugasan_pembimbing->catatan_koordinator) }}</textarea>
                    @error('catatan_koordinator') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>


                <div class="mt-8 flex justify-end">
                    <a href="{{ route('admin.penugasan-pembimbing.index') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-medium py-2 px-4 rounded-md mr-2">Batal</a>
                    <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-2 px-4 rounded-md">
                        Update Penugasan
                    </button>
                </div>
            </form>
        </div>
    </main>

    @include('admin.template.footer')
</body>
</html>
