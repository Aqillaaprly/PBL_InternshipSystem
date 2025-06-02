<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Manajemen Pendaftar - Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .status-badge-sm { padding: 0.125rem 0.5rem; font-size: 0.7rem; line-height: 1; border-radius: 9999px; font-weight: 600; white-space: nowrap;}
        .status-dokumen-valid { background-color: #d1fae5; color: #065f46; } /* green-100, green-800 */
        .status-dokumen-pending { background-color: #fef3c7; color: #92400e; } /* yellow-100, yellow-800 */
        .status-dokumen-tidak-lengkap { background-color: #fee2e2; color: #991b1b; } /* red-100, red-800 */
    </style>
</head>

<body class="bg-blue-50 text-gray-800">

    @include('admin.template.navbar')

    <main class="max-w-screen-xl mx-auto px-4 sm:px-6 lg:px-8 py-12 mt-16">
        <div class="bg-white p-6 sm:p-8 rounded-xl shadow-lg">
            <div class="flex flex-col sm:flex-row justify-between items-center mb-6">
                <h1 class="text-2xl sm:text-3xl font-bold text-blue-800 mb-2 sm:mb-0">Manajemen Pendaftar Magang</h1>
                <div class="flex items-center space-x-3 mt-3 sm:mt-0">
                    <form method="GET" action="{{ route('admin.pendaftar.index') }}" class="flex items-center space-x-2">
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari pendaftar..." class="border border-gray-300 rounded-md px-4 py-2 text-sm focus:ring-blue-500 focus:border-blue-500">
                        <button type="submit" class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-4 py-2 rounded-md text-sm">Cari</button>
                    </form>
                    <a href="{{ route('admin.pendaftar.create') }}" class="bg-blue-600 text-white px-5 py-2 rounded-md text-sm hover:bg-blue-700 whitespace-nowrap shadow-sm">+ Tambah Pendaftar</a>
                </div>
            </div>

            @if (session('success')) <div class="bg-green-100 border-l-4 border-green-500 text-green-700 px-4 py-3 rounded-md relative mb-4" role="alert"><span class="block sm:inline">{{ session('success') }}</span></div> @endif
            @if (session('error')) <div class="bg-red-100 border-l-4 border-red-500 text-red-700 px-4 py-3 rounded-md relative mb-4" role="alert"><span class="block sm:inline">{{ session('error') }}</span></div> @endif
            @if (session('info')) <div class="bg-blue-100 border-l-4 border-blue-500 text-blue-700 px-4 py-3 rounded-md relative mb-4" role="alert"><span class="block sm:inline">{{ session('info') }}</span></div> @endif
            @if (session('warning')) <div class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 px-4 py-3 rounded-md relative mb-4" role="alert"><span class="block sm:inline">{{ session('warning') }}</span></div> @endif

            <div class="overflow-x-auto rounded-lg border border-gray-200">
                <table class="min-w-full text-sm text-left">
                    <thead class="bg-gray-100 text-gray-700 uppercase text-xs">
                        <tr>
                            <th class="px-5 py-3">No</th>
                            <th class="px-5 py-3">Nama Mahasiswa</th>
                            <th class="px-5 py-3">Lowongan</th>
                            <th class="px-5 py-3">Perusahaan</th>
                            <th class="px-5 py-3">Tgl Daftar</th>
                            <th class="px-5 py-3 text-center">Status Lamaran</th>
                            <th class="px-5 py-3 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="text-gray-600 divide-y divide-gray-200">
                        @forelse ($pendaftars as $index => $pendaftar)
                            @php
                                // Pastikan $dokumenWajibGlobal dikirim dari controller AdminPendaftarController@index
                                $dokumenWajibUntukView = $dokumenWajibGlobal ?? [
                                    'Daftar Riwayat Hidup', 'KHS atau Transkrip Nilai', 'KTP',
                                    'KTM', 'Surat Izin Orang Tua', 'Pakta Integritas'
                                ];

                                $statusDokumenRingkas = '';
                                $kelasCssStatusDokumen = '';
                                $jumlahDokumenWajibYangAda = 0;
                                $semuaDokumenWajibYangAdaSudahValid = true; // Asumsikan valid sampai terbukti tidak
                                $adaDokumenWajibYangPerluVerifikasiAtauRevisi = false;

                                if (!$pendaftar->dokumenPendaftars || $pendaftar->dokumenPendaftars->isEmpty()) {
                                    $statusDokumenRingkas = 'Belum Unggah';
                                    $kelasCssStatusDokumen = 'status-dokumen-tidak-lengkap';
                                } else {
                                    foreach ($dokumenWajibUntukView as $namaDocWajib) {
                                        $doc = $pendaftar->dokumenPendaftars->firstWhere('nama_dokumen', $namaDocWajib);
                                        if ($doc) {
                                            $jumlahDokumenWajibYangAda++;
                                            if ($doc->status_validasi !== 'Valid') {
                                                $semuaDokumenWajibYangAdaSudahValid = false;
                                            }
                                            if (in_array($doc->status_validasi, ['Belum Diverifikasi', 'Perlu Revisi'])) {
                                                $adaYangBelumDiverifikasiAtauRevisi = true;
                                            }
                                        } else {
                                            // Jika dokumen wajib tidak ditemukan, maka tidak mungkin semua wajib ada dan valid
                                            $semuaDokumenWajibYangAdaSudahValid = false;
                                        }
                                    }

                                    if ($jumlahDokumenWajibYangAda < count($dokumenWajibUntukView)) {
                                        // Kurang dari jumlah dokumen wajib yang seharusnya ada
                                        $statusDokumenRingkas = 'Belum Lengkap';
                                        $kelasCssStatusDokumen = 'status-dokumen-tidak-lengkap';
                                    } elseif ($semuaDokumenWajibYangAdaSudahValid) {
                                        // Semua dokumen wajib ditemukan DAN semuanya 'Valid'
                                        $statusDokumenRingkas = 'Lengkap & Valid';
                                        $kelasCssStatusDokumen = 'status-dokumen-valid';
                                    } elseif ($adaYangBelumDiverifikasiAtauRevisi) {
                                        // Semua dokumen wajib ada, tapi ada yang perlu dicek/revisi
                                        $statusDokumenRingkas = 'Perlu Dicek/Revisi';
                                        $kelasCssStatusDokumen = 'status-dokumen-pending';
                                    } else {
                                        // Semua dokumen wajib ada, tidak ada yg perlu dicek/revisi, tapi tidak semua 'Valid'
                                        // (berarti ada yang 'Tidak Valid')
                                        $statusDokumenRingkas = 'Ada Yg Tidak Valid';
                                        $kelasCssStatusDokumen = 'status-dokumen-tidak-lengkap';
                                    }
                                }
                            @endphp
                            <tr class="hover:bg-gray-50">
                                <td class="px-5 py-4 text-center align-middle">{{ $pendaftars->firstItem() + $index }}</td>
                                <td class="px-5 py-4 align-middle font-medium text-gray-900">{{ $pendaftar->user->name ?? ($pendaftar->user->username ?? 'N/A') }}</td>
                                <td class="px-5 py-4 align-middle">{{ $pendaftar->lowongan->judul ?? 'N/A' }}</td>
                                <td class="px-5 py-4 align-middle">{{ $pendaftar->lowongan->company->nama_perusahaan ?? 'N/A' }}</td>
                                <td class="px-5 py-4 align-middle">{{ $pendaftar->tanggal_daftar ? \Carbon\Carbon::parse($pendaftar->tanggal_daftar)->isoFormat('D MMM YY') : 'N/A' }}</td>

                                <td class="px-5 py-4 text-center align-middle">
                                    <span class="status-badge-sm
                                        @if ($pendaftar->status_lamaran == 'Diterima') bg-green-100 text-green-700
                                        @elseif ($pendaftar->status_lamaran == 'Ditolak') bg-red-100 text-red-700
                                        @elseif ($pendaftar->status_lamaran == 'Pending') bg-yellow-100 text-yellow-700
                                        @elseif ($pendaftar->status_lamaran == 'Wawancara') bg-blue-100 text-blue-700
                                        @elseif ($pendaftar->status_lamaran == 'Ditinjau') bg-indigo-100 text-indigo-700
                                        @else bg-gray-200 text-gray-700 @endif">
                                        {{ $pendaftar->status_lamaran }}
                                    </span>
                                </td>
                                <td class="px-5 py-4 text-center align-middle">
                                    <div class="flex item-center justify-center space-x-1 sm:space-x-2">
                                        <a href="{{ route('admin.pendaftar.showDokumen', $pendaftar->id) }}" class="text-xs bg-blue-100 text-blue-600 hover:bg-blue-200 px-3 py-1.5 rounded-md font-medium">Dokumen</a>
                                        <a href="{{ route('admin.pendaftar.edit', $pendaftar->id) }}" class="text-xs bg-yellow-100 text-yellow-600 hover:bg-yellow-200 px-3 py-1.5 rounded-md font-medium">Ubah</a>
                                        <form action="{{ route('admin.pendaftar.destroy', $pendaftar->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data pendaftar ini? Semua dokumen terkait juga akan dihapus.');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-xs bg-red-100 text-red-600 hover:bg-red-200 px-3 py-1.5 rounded-md font-medium">Hapus</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-5 py-4 text-center text-gray-500">
                                    @if(request('search'))
                                        Tidak ada pendaftar ditemukan untuk pencarian "{{ request('search') }}".
                                    @else
                                        Belum ada data pendaftar.
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

    @include('admin.template.footer')

</body>
</html>