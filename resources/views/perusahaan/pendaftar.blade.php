{{-- resources/views/perusahaan/pendaftar.blade.php --}}

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Daftar Pendaftar - Perusahaan</title> {{-- Judul diubah --}}
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .status-badge-sm { padding: 0.125rem 0.5rem; font-size: 0.7rem; line-height: 1; border-radius: 9999px; font-weight: 600; white-space: nowrap;}
        .status-dokumen-overall-validate { background-color: #d1fae5; color: #065f46; } /* bg-green-100 text-green-700 */
        .status-dokumen-overall-invalidate { background-color: #fee2e2; color: #991b1b; } /* bg-red-100 text-red-700 */
        .status-dokumen-valid { background-color: #d1fae5; color: #065f46; }
        .status-dokumen-pending { background-color: #fef3c7; color: #92400e; }
        .status-dokumen-tidak-lengkap { background-color: #fee2e2; color: #991b1b; }
    </style>
</head>

<body class="bg-blue-50 text-gray-800">

    @include('perusahaan.template.navbar') {{-- Navbar diubah ke perusahaan --}}

    <main class="max-w-screen-xl mx-auto px-4 sm:px-6 lg:px-8 py-12 mt-16">
        <div class="bg-white p-6 sm:p-8 rounded-xl shadow-lg">
            @if (session('success')) <div class="bg-green-100 border-l-4 border-green-500 text-green-700 px-4 py-3 rounded-md relative mb-4" role="alert"><span class="block sm:inline">{{ session('success') }}</span></div> @endif
            @if (session('error')) <div class="bg-red-100 border-l-4 border-red-500 text-red-700 px-4 py-3 rounded-md relative mb-4" role="alert"><span class="block sm:inline">{{ session('error') }}</span></div> @endif
            @if (session('info')) <div class="bg-blue-100 border-l-4 border-blue-500 text-blue-700 px-4 py-3 rounded-md relative mb-4" role="alert"><span class="block sm:inline">{{ session('info') }}</span></div> @endif
            @if (session('warning')) <div class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 px-4 py-3 rounded-md relative mb-4" role="alert"><span class="block sm:inline">{{ session('warning') }}</span></div> @endif

            <div class="flex flex-col sm:flex-row justify-between items-center mb-6">
                <h1 class="text-2xl sm:text-3xl font-bold text-blue-800 mb-4 sm:mb-0">Daftar Pendaftar Magang</h1> {{-- Judul diubah --}}
            </div>

            {{-- Filter Forms (from previous `perusahaan/pendaftar.blade.php` and Company PendaftarController) --}}
            <form action="{{ route('perusahaan.pendaftar.index') }}" method="GET" class="mb-4">
                <div class="row grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="lowongan_id" class="block text-sm font-medium text-gray-700">Filter Lowongan</label>
                            <select name="lowongan_id" id="lowongan_id" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md">
                                <option value="">Semua Lowongan</option>
                                @foreach ($lowonganPerusahaan as $lowongan)
                                    <option value="{{ $lowongan->id }}" {{ ($selectedLowonganId == $lowongan->id) ? 'selected' : '' }}>
                                        {{ $lowongan->judul }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="status_lamaran" class="block text-sm font-medium text-gray-700">Status Lamaran</label>
                            <select name="status_lamaran" id="status_lamaran" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md">
                                <option value="">Semua Status</option>
                
                                <option value="Ditinjau" {{ ($selectedStatusLamaran == 'Ditinjau') ? 'selected' : '' }}>Ditinjau</option>
                                <option value="Diterima" {{ ($selectedStatusLamaran == 'Diterima') ? 'selected' : '' }}>Diterima</option>
                                <option value="Ditolak" {{ ($selectedStatusLamaran == 'Ditolak') ? 'selected' : '' }}>Ditolak</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="mt-4 text-right">
                    <button type="submit" class="btn btn-primary bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm">Filter</button>
                </div>
            </form>
            {{-- End Filter Forms --}}

            <div class="overflow-x-auto rounded-lg border border-gray-200 mt-6">
                <table class="min-w-full text-sm text-left">
                    <thead class="bg-gray-100 text-gray-700 uppercase text-xs">
                        <tr>
                            <th class="px-5 py-3">No</th>
                            <th class="px-5 py-3">Nama Mahasiswa</th>
                            <th class="px-5 py-3">Lowongan</th>
                            {{-- Hapus kolom Perusahaan --}}
                            <th class="px-5 py-3">Tgl Daftar</th>
                            <th class="px-5 py-3 text-center">Status Lamaran</th>
                            <th class="px-5 py-3 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="text-gray-600 divide-y divide-gray-200">
                        @forelse ($pendaftars as $index => $pendaftar)
                            <tr class="hover:bg-gray-50">
                                <td class="px-5 py-4 text-center align-middle">{{ $pendaftars->firstItem() + $index }}</td>
                                <td class="px-5 py-4 align-middle font-medium text-gray-900">{{ $pendaftar->user->name ?? ($pendaftar->user->username ?? 'N/A') }}</td>
                                <td class="px-5 py-4 align-middle">{{ $pendaftar->lowongan->judul ?? 'N/A' }}</td>
                                {{-- Hapus kolom Perusahaan --}}
                                <td class="px-5 py-4 align-middle">{{ $pendaftar->tanggal_daftar ? \Carbon\Carbon::parse($pendaftar->tanggal_daftar)->isoFormat('D MMM YY') : 'N/A' }}</td>
                                <td class="px-5 py-4 text-center align-middle">
                                    <span class="status-badge-sm
                                        @if ($pendaftar->status_lamaran == 'Diterima') bg-green-100 text-green-700
                                        @elseif ($pendaftar->status_lamaran == 'Ditolak') bg-red-100 text-red-700
                                        @elseif ($pendaftar->status_lamaran == 'Ditinjau') bg-indigo-100 text-indigo-700
                                        @else bg-gray-200 text-gray-700 @endif">
                                        {{ $pendaftar->status_lamaran }}
                                    </span>
                                </td>
                                <td class="px-5 py-4 text-center align-middle">
    <div class="flex item-center justify-center space-x-1 sm:space-x-2">
        {{-- THIS IS THE FORM FOR UPDATING STATUS LAMARAN --}}
        <form action="{{ route('perusahaan.pendaftar.updateStatusLamaran', $pendaftar->id) }}" method="POST">
    @csrf
    @method('PATCH') {{-- This line is critical for PATCH requests from HTML forms --}}
    <select name="status_lamaran" onchange="this.form.submit()" class="border border-gray-300 rounded-md px-2 py-1 text-xs focus:ring-blue-500 focus:border-blue-500">
        <option value="Ditinjau" {{ $pendaftar->status_lamaran == 'Ditinjau' ? 'selected' : '' }}>Ditinjau</option>
        <option value="Diterima" {{ $pendaftar->status_lamaran == 'Diterima' ? 'selected' : '' }}>Diterima</option>
        <option value="Ditolak" {{ $pendaftar->status_lamaran == 'Ditolak' ? 'selected' : '' }}>Ditolak</option>
    </select>
</form>
        {{-- Link to view documents --}}
        <a href="{{ route('perusahaan.pendaftar.showDokumen', $pendaftar->id) }}" class="text-xs bg-blue-100 text-blue-600 hover:bg-blue-200 px-3 py-1.5 rounded-md font-medium">Dokumen</a>
    </div>
</td>
                            </tr>
                        @empty
                            <tr>
                                {{-- colspan diubah menjadi 7 karena satu kolom dihilangkan --}}
                                <td colspan="7" class="px-5 py-4 text-center text-gray-500">
                                    @if(request('search') || request('lowongan_id') || request('status_lamaran') || request('document_status_filter'))
                                        Tidak ada pendaftar ditemukan sesuai kriteria filter.
                                    @else
                                        Belum ada data pendaftar untuk perusahaan Anda.
                                    @endif
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($pendaftars->hasPages())
                <div class="mt-6">
                    {{ $pendaftars->appends(request()->query())->links() }}
                </div>
            @endif

        </div>
    </main>

    @include('perusahaan.template.footer') {{-- Footer diubah ke perusahaan --}}

</body>
</html>