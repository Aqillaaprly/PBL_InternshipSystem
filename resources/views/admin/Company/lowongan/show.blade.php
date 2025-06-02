<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Detail Lowongan - {{ $lowongan->judul }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-blue-50 text-gray-800">
    @include('admin.template.navbar') {{-- Sesuaikan dengan path navbar admin Anda --}}

    <main class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12 mt-16">
        <div class="bg-white p-6 sm:p-8 rounded-xl shadow-lg">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6">
                <h1 class="text-2xl sm:text-3xl font-bold text-blue-800 mb-2 sm:mb-0">Detail Lowongan</h1>
                <a href="{{ route('admin.lowongan.index') }}" class="text-sm text-blue-600 hover:underline">&larr; Kembali ke Daftar Lowongan</a>
            </div>

            @if (session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            <div class="space-y-4">
                <div>
                    <strong class="text-gray-700 block">Judul Lowongan:</strong>
                    <p class="text-gray-900 text-lg">{{ $lowongan->judul }}</p>
                </div>
                <div>
                    <strong class="text-gray-700 block">Perusahaan:</strong>
                    <p class="text-gray-900">{{ $lowongan->company->nama_perusahaan ?? 'N/A' }}</p>
                </div>
                <div class="mt-2">
                    <strong class="text-gray-700 block">Deskripsi:</strong>
                    <div class="prose prose-sm max-w-none text-gray-800 mt-1">{!! nl2br(e($lowongan->deskripsi)) !!}</div>
                </div>
                <div class="mt-2">
                    <strong class="text-gray-700 block">Kualifikasi:</strong>
                    <div class="prose prose-sm max-w-none text-gray-800 mt-1">{!! nl2br(e($lowongan->kualifikasi)) !!}</div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 pt-4 border-t mt-4">
                    <div>
                        <strong class="text-gray-700 block">Tipe:</strong>
                        <p class="text-gray-900">{{ $lowongan->tipe }}</p>
                    </div>
                    <div>
                        <strong class="text-gray-700 block">Lokasi:</strong>
                        <p class="text-gray-900">{{ $lowongan->lokasi }}</p>
                    </div>
                    <div>
                        <strong class="text-gray-700 block">Gaji Minimum:</strong>
                        <p class="text-gray-900">{{ $lowongan->gaji_min ? 'Rp ' . number_format($lowongan->gaji_min, 0, ',', '.') : '-' }}</p>
                    </div>
                    <div>
                        <strong class="text-gray-700 block">Gaji Maksimum:</strong>
                        <p class="text-gray-900">{{ $lowongan->gaji_max ? 'Rp ' . number_format($lowongan->gaji_max, 0, ',', '.') : '-' }}</p>
                    </div>
                    <div>
                        <strong class="text-gray-700 block">Tanggal Buka:</strong>
                        <p class="text-gray-900">{{ $lowongan->tanggal_buka ? \Carbon\Carbon::parse($lowongan->tanggal_buka)->isoFormat('D MMMM YYYY') : '-' }}</p>
                    </div>
                    <div>
                        <strong class="text-gray-700 block">Tanggal Tutup:</strong>
                        <p class="text-gray-900">{{ $lowongan->tanggal_tutup ? \Carbon\Carbon::parse($lowongan->tanggal_tutup)->isoFormat('D MMMM YYYY') : '-' }}</p>
                    </div>
                    <div>
                        <strong class="text-gray-700 block">Status:</strong>
                        <span class="px-3 py-1 text-sm font-semibold leading-tight rounded-full
                            @if($lowongan->status == 'Aktif') bg-green-100 text-green-700
                            @else bg-red-100 text-red-700 @endif">
                            {{ $lowongan->status }}
                        </span>
                    </div>
                     <div>
                        <strong class="text-gray-700 block">Dibuat pada:</strong>
                        <p class="text-gray-900 text-sm">{{ $lowongan->created_at->isoFormat('D MMMM YYYY, HH:mm') }}</p>
                    </div>
                    <div>
                        <strong class="text-gray-700 block">Diperbarui pada:</strong>
                        <p class="text-gray-900 text-sm">{{ $lowongan->updated_at->isoFormat('D MMMM YYYY, HH:mm') }}</p>
                    </div>
                </div>
            </div>

            <div class="mt-8 flex flex-col sm:flex-row justify-end space-y-2 sm:space-y-0 sm:space-x-3">
                <a href="{{ route('admin.lowongan.edit', $lowongan->id) }}" class="w-full sm:w-auto text-center bg-yellow-500 hover:bg-yellow-600 text-white font-medium py-2 px-4 rounded-md shadow-sm">
                    Edit Lowongan
                </a>
                <form action="{{ route('admin.lowongan.destroy', $lowongan->id) }}" method="POST" class="w-full sm:w-auto" onsubmit="return confirm('Apakah Anda yakin ingin menghapus lowongan ini?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="w-full text-center bg-red-600 hover:bg-red-700 text-white font-medium py-2 px-4 rounded-md shadow-sm">
                        Hapus Lowongan
                    </button>
                </form>
            </div>
        </div>
    </main>

    @include('admin.template.footer') {{-- Sesuaikan path footer admin Anda --}}
</body>
</html>