<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Pendaftar Lowongan - Perusahaan SIMMAGANG</title>
    <script src="https://cdn.tailwindcss.com"></script>
    {{-- <link rel="stylesheet" href="{{ asset('css/perusahaan_style.css') }}"> --}}
</head>
<body class="bg-blue-50 text-gray-800">
    @include('perusahaan.template.navbar') {{-- Pastikan path benar --}}

    <main class="max-w-screen-xl mx-auto px-8 py-12 mt-16">
        <div class="bg-white p-8 rounded-xl shadow">
            <div class="flex justify-between items-center pb-4">
                <h1 class="text-2xl font-bold text-blue-800 ml-8">Daftar Pendaftar Lowongan</h1>
                <div class="flex space-x-3">
                    {{-- Action diubah ke perusahaan.pendaftar.index --}}
                    <form method="GET" action="{{ route('perusahaan.pendaftar.index') }}" class="flex">
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama mahasiswa/posisi..." class="border border-gray-300 rounded-l px-4 py-2 text-sm focus:outline-none focus:ring-1 focus:ring-blue-500">
                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-2 rounded-r text-sm -ml-px">Cari</button>
                    </form>
                    {{-- Tambahkan filter lowongan --}}
                    <form method="GET" action="{{ route('perusahaan.pendaftar.index') }}" class="flex">
                        <select name="filter_lowongan_id" onchange="this.form.submit()" class="border border-gray-300 rounded px-4 py-2 text-sm focus:outline-none focus:ring-1 focus:ring-blue-500">
                            <option value="">Semua Lowongan</option>
                            @foreach($lowonganPerusahaan as $lowonganItem)
                                <option value="{{ $lowonganItem->id }}" {{ request('filter_lowongan_id') == $lowonganItem->id ? 'selected' : '' }}>
                                    {{ $lowonganItem->judul }}
                                </option>
                            @endforeach
                        </select>
                    </form>
                </div>
            </div>

            @if (session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <strong class="font-bold">Berhasil!</strong>
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif
            @if (session('error'))
                 <div class="bg-red-100 border-l-4 border-red-500 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <strong class="font-bold">Gagal!</strong>
                    <span class="block sm:inline">{{ session('error') }}</span>
                </div>
            @endif

                 <div class="overflow-x-auto rounded-lg border border-gray-200">
                    <table class="min-w-full text-sm text-center">
                        <thead class="bg-gray-100 text-gray-700 uppercase text-xs">
                            <tr>
                                <th class="px-5 py-3">No</th>
                                <th class="px-5 py-3">Nama Mahasiswa</th>
                                <th class="px-5 py-3">Posisi Magang</th>
                                <th class="px-5 py-3">Tanggal Daftar</th>
                                <th class="px-5 py-3">Status Lamaran</th>
                                <th class="px-5 py-3">Dokumen</th>
                                <th class="px-5 py-3">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="text-gray-600">
                            @forelse ($pendaftars as $index => $pendaftar)
                                <tr class="border-b border-gray-200 hover:bg-gray-50">
                                    <td class="px-5 py-4">{{ $pendaftars->firstItem() + $index }}</td>
                                    {{-- Mengambil nama dari Mahasiswa model --}}
                                    <td class="px-5 py-4 text-left">{{ $pendaftar->mahasiswa->nama ?? 'N/A' }}</td>
                                    <td class="px-5 py-4">{{ $pendaftar->lowongan->judul ?? 'N/A' }}</td> {{-- Menggunakan 'judul' bukan 'posisi' --}}
                                    <td class="px-5 py-4">{{ \Carbon\Carbon::parse($pendaftar->created_at)->isoFormat('D MMMM BBBB') }}</td>
                                    <td class="px-5 py-4">
                                        <span class="px-2 py-1 rounded-full text-xs font-semibold
                                            @if ($pendaftar->status_lamaran == 'Diterima') bg-green-100 text-green-800
                                            @elseif ($pendaftar->status_lamaran == 'Ditolak') bg-red-100 text-red-800
                                            @elseif ($pendaftar->status_lamaran == 'Ditinjau') bg-blue-100 text-blue-800
                                            @elseif ($pendaftar->status_lamaran == 'Wawancara') bg-yellow-100 text-yellow-800
                                            @else bg-gray-100 text-gray-800 @endif">
                                            {{ $pendaftar->status_lamaran }}
                                        </span>
                                    </td>
                                    <td class="px-5 py-4 text-center">
                                        <div class="flex flex-col items-center justify-center space-y-1">
                                            {{-- Menggunakan dokumenPendaftars (plural) --}}
                                            @forelse ($pendaftar->dokumenPendaftars as $dokumen)
                                                <a href="{{ route('perusahaan.pendaftar.showDokumen', $dokumen->id) }}" target="_blank" class="text-blue-600 hover:underline text-xs">
                                                    {{ $dokumen->jenis_dokumen }}
                                                    @if ($dokumen->status_validasi === 'Valid')
                                                        <span class="text-green-500">(Valid)</span>
                                                    @elseif ($dokumen->status_validasi === 'Belum Diverifikasi')
                                                        <span class="text-yellow-500">(Belum Diverifikasi)</span>
                                                    @elseif ($dokumen->status_validasi === 'Tidak Valid')
                                                        <span class="text-red-500">(Tidak Valid)</span>
                                                    @elseif ($dokumen->status_validasi === 'Perlu Revisi')
                                                        <span class="text-orange-500">(Perlu Revisi)</span>
                                                    @else
                                                        <span class="text-gray-500">({{ $dokumen->status_validasi ?? 'Unknown' }})</span>
                                                    @endif
                                                </a>
                                            @empty
                                                <span class="text-gray-500 text-xs">Tidak ada dokumen</span>
                                            @endforelse
                                        </div>
                                    </td>
                                    <td class="px-5 py-4">
                                        <div class="flex item-center justify-center space-x-1">
                                            <form action="{{ route('perusahaan.pendaftar.updateStatus', $pendaftar->id) }}" method="POST">
                                                @csrf
                                                <select name="status_lamaran" onchange="this.form.submit()" class="border border-gray-300 rounded-md px-2 py-1 text-xs focus:ring-blue-500 focus:border-blue-500">
                                                    <option value="Ditinjau" {{ $pendaftar->status_lamaran == 'Ditinjau' ? 'selected' : '' }}>Ditinjau</option>
                                                    <option value="Wawancara" {{ $pendaftar->status_lamaran == 'Wawancara' ? 'selected' : '' }}>Wawancara</option>
                                                    <option value="Diterima" {{ $pendaftar->status_lamaran == 'Diterima' ? 'selected' : '' }}>Diterima</option>
                                                    <option value="Ditolak" {{ $pendaftar->status_lamaran == 'Ditolak' ? 'selected' : '' }}>Ditolak</option>
                                                </select>
                                            </form>
                                            {{-- Tambahkan tombol Detail jika halaman show() sudah dibuat terpisah --}}
                                            <a href="{{ route('perusahaan.pendaftar.show', $pendaftar->id) }}"
                                               class="text-xs bg-sky-100 text-sky-600 hover:bg-sky-200 px-3 py-1 rounded transition-colors duration-200 flex items-center space-x-1">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                                </svg>
                                                <span>Detail</span>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-5 py-4 text-center text-gray-500">
                                         @if(request('search'))
                                            Tidak ada pendaftar ditemukan untuk pencarian "{{ request('search') }}".
                                        @else
                                            Belum ada pendaftar dengan dokumen valid dan status 'Ditinjau'.
                                        @endif
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if ($pendaftars->hasPages())
                    <div class="mt-6">
                        {{ $pendaftars->appends(request()->query())->links() }} {{-- Menampilkan link paginasi dan menjaga parameter query --}}
                    </div>
                @endif
            </div>
        </main>
        @include('perusahaan.template.footer') {{-- Pastikan path benar --}}
    </body>
    </html>