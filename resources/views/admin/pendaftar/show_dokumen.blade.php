<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dokumen Pendaftar - {{ $pendaftar->user->name ?? ($pendaftar->user->username ?? 'Pendaftar Tidak Ditemukan') }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .status-badge {
            padding: 0.25em 0.6em; /* py-1 px-2.5 */
            font-size: 0.75rem; /* text-xs */
            font-weight: 600;
            line-height: 1;
            text-align: center;
            white-space: nowrap;
            vertical-align: baseline;
            border-radius: 0.375rem; /* rounded-md */
        }
        .status-belum-diverifikasi { background-color: #fef3c7; color: #92400e; } /* bg-yellow-100 text-yellow-800 */
        .status-valid { background-color: #d1fae5; color: #065f46; } /* bg-green-100 text-green-800 */
        .status-tidak-valid { background-color: #fee2e2; color: #991b1b; } /* bg-red-100 text-red-800 */
        .status-perlu-revisi { background-color: #e0e7ff; color: #3730a3; } /* bg-indigo-100 text-indigo-800 */
    </style>
</head>
<body class="bg-gray-100 text-gray-800">
    @include('admin.template.navbar')

    <main class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-10 mt-20">
        <div class="bg-white p-6 sm:p-8 rounded-xl shadow-lg">
            {{-- Pastikan variabel $pendaftar ada sebelum digunakan --}}
            @if(isset($pendaftar) && $pendaftar)
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6">
                    <div>
                        <h1 class="text-2xl sm:text-3xl font-bold text-gray-800">
                            Dokumen untuk Pendaftar: <span class="text-blue-600">{{ $pendaftar->user->name ?? $pendaftar->user->username }}</span>
                        </h1>
                        <p class="text-sm text-gray-600">
                            Melamar Sebagai: {{ $pendaftar->lowongan->judul ?? 'N/A' }}
                            @if($pendaftar->lowongan && $pendaftar->lowongan->company)
                                di {{ $pendaftar->lowongan->company->nama_perusahaan ?? 'N/A' }}
                            @endif
                        </p>
                        <p class="text-sm text-gray-600 mt-1">
                            Status Lamaran Saat Ini:
                            <span class="font-semibold
                                @if ($pendaftar->status_lamaran == 'Diterima') text-green-600 @elseif ($pendaftar->status_lamaran == 'Ditolak') text-red-600 @elseif ($pendaftar->status_lamaran == 'Pending') text-yellow-600 @elseif ($pendaftar->status_lamaran == 'Wawancara') text-blue-600 @elseif ($pendaftar->status_lamaran == 'Ditinjau') text-indigo-600 @else text-gray-600 @endif">
                                {{ $pendaftar->status_lamaran }}
                            </span>
                        </p>
                    </div>
                    <a href="{{ route('admin.pendaftar.index') }}" class="text-sm text-blue-600 hover:underline mt-2 sm:mt-0">&larr; Kembali ke Daftar Pendaftar</a>
                </div>

                {{-- Notifikasi --}}
                @if (session('success')) <div class="bg-green-100 border-l-4 border-green-500 text-green-700 px-4 py-3 rounded relative mb-4" role="alert"><span class="block sm:inline">{{ session('success') }}</span></div> @endif
                @if (session('info')) <div class="bg-blue-100 border-l-4 border-blue-500 text-blue-700 px-4 py-3 rounded relative mb-4" role="alert"><span class="block sm:inline">{{ session('info') }}</span></div> @endif
                @if (session('error')) <div class="bg-red-100 border-l-4 border-red-500 text-red-700 px-4 py-3 rounded relative mb-4" role="alert"><span class="block sm:inline">{{ session('error') }}</span></div> @endif
                @if ($errors->any())
                    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 px-4 py-3 rounded relative mb-5" role="alert">
                        <strong class="font-bold">Oops! Ada beberapa masalah:</strong>
                        <ul class="mt-2 list-disc list-inside text-sm">@foreach ($errors->all() as $error) <li>{{ $error }}</li> @endforeach</ul>
                    </div>
                @endif

                {{-- Bagian ini sekarang hanya tabel validasi dokumen --}}
                <div>
                    <h2 class="text-xl font-semibold text-gray-700 mb-4">Validasi Dokumen Terunggah</h2>
                    @if($pendaftar->dokumenPendaftars && $pendaftar->dokumenPendaftars->count() > 0)
                        <div class="overflow-x-auto rounded-lg border border-gray-200">
                            <table class="min-w-full text-sm">
                                <thead class="bg-gray-100 text-gray-600 uppercase text-xs">
                                    <tr>
                                        <th class="px-4 py-3 text-left">Nama Dokumen</th>
                                        <th class="px-4 py-3 text-left">File</th>
                                        <th class="px-4 py-3 text-center">Status Validasi</th>
                                        <th class="px-4 py-3 text-center">Ubah Status & Hapus</th> {{-- Judul kolom disesuaikan --}}
                                    </tr>
                                </thead>
                                <tbody class="text-gray-700 divide-y divide-gray-200">
                                    @foreach($pendaftar->dokumenPendaftars->sortBy('nama_dokumen') as $dokumen)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-4 py-3 align-middle">{{ $dokumen->nama_dokumen }}</td>
                                        <td class="px-4 py-3 align-middle">
                                            @if($dokumen->file_path && Storage::disk('public')->exists($dokumen->file_path))
                                                <a href="{{ asset('storage/' . $dokumen->file_path) }}" target="_blank" class="text-blue-600 hover:underline">{{ basename($dokumen->file_path) }} ({{ strtoupper($dokumen->tipe_file ?? 'N/A') }})</a>
                                            @else
                                                <span class="text-red-500">File tidak ditemukan</span>
                                            @endif
                                        </td>
                                        <td class="px-4 py-3 text-center align-middle">
                                            <span class="status-badge
                                                @if($dokumen->status_validasi == 'Valid') status-valid
                                                @elseif($dokumen->status_validasi == 'Tidak Valid') status-tidak-valid @endif">
                                                {{ $dokumen->status_validasi }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-3 text-center align-middle">
                                            {{-- Form untuk Update Status Validasi --}}
                                            <form action="{{ route('admin.pendaftar.dokumen.updateStatus', [$pendaftar->id, $dokumen->id]) }}" method="POST" class="inline-flex items-center space-x-2">
                                                @csrf
                                                @method('PATCH')
                                                <select name="status_validasi" class="text-xs border-gray-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 py-1.5 px-2">
                                                    <option value="Valid" {{ $dokumen->status_validasi == 'Valid' ? 'selected' : '' }}>Valid</option>
                                                    <option value="Tidak Valid" {{ $dokumen->status_validasi == 'Tidak Valid' ? 'selected' : '' }}>Tidak Valid</option>
                                                </select>
                                                <button type="submit" class="text-xs bg-indigo-500 hover:bg-indigo-600 text-white px-3 py-1.5 rounded-md font-medium">Update</button>
                                            </form>
                                            {{-- Tombol Hapus menjadi form terpisah --}}
                                            <form action="{{ route('admin.pendaftar.dokumen.destroy', [$pendaftar->id, $dokumen->id]) }}" method="POST" class="inline-block ml-1" onsubmit="return confirm('Yakin ingin menghapus dokumen {{ $dokumen->nama_dokumen }} ini?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-xs bg-red-100 text-red-600 hover:bg-red-200 px-3 py-1.5 rounded-md font-medium">Hapus</button>
                                            </form>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-center text-gray-500 py-4">Belum ada dokumen yang diunggah untuk pendaftar ini.</p>
                    @endif
                </div>
            @else
                <div class="text-center py-10">
                    <h2 class="text-xl font-semibold text-red-600 mb-4">Data Pendaftar Tidak Ditemukan</h2>
                    <p class="text-gray-600">Tidak dapat menampilkan detail dan dokumen pendaftar.</p>
                    <a href="{{ route('admin.pendaftar.index') }}" class="mt-6 inline-block bg-blue-600 text-white px-6 py-2 rounded text-sm hover:bg-blue-700">
                        Kembali ke Daftar Pendaftar
                    </a>
                </div>
            @endif
        </div>
    </main>

    @include('admin.template.footer')
</body>
</html>