{{-- resources/views/admin/Company/pendaftar.blade.php --}}

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Manajemen Pendaftar - Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .status-badge-sm { padding: 0.125rem 0.5rem; font-size: 0.7rem; line-height: 1; border-radius: 9999px; font-weight: 600; white-space: nowrap;}
        /* Tambahkan style untuk status Validate / Invalidate */
        .status-dokumen-overall-validate { background-color: #d1fae5; color: #065f46; } /* bg-green-100 text-green-700 */
        .status-dokumen-overall-invalidate { background-color: #fee2e2; color: #991b1b; } /* bg-red-100 text-red-700 */

        /* Style yang sudah ada sebelumnya */
        .status-dokumen-valid { background-color: #d1fae5; color: #065f46; }
        .status-dokumen-pending { background-color: #fef3c7; color: #92400e; }
        .status-dokumen-tidak-lengkap { background-color: #fee2e2; color: #991b1b; }
    </style>
</head>

<body class="bg-blue-50 text-gray-800">

    @include('admin.template.navbar')

    <main class="max-w-screen-xl mx-auto px-4 sm:px-6 lg:px-8 py-12 mt-16">
        <div class="bg-white p-6 sm:p-8 rounded-xl shadow-lg">
            {{-- ... (bagian header dan notifikasi tetap sama) ... --}}
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
                            <th class="px-5 py-3 text-center">Status Dokumen Wajib</th> {{-- Kolom Baru --}}
                            <th class="px-5 py-3 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="text-gray-600 divide-y divide-gray-200">
                        @forelse ($pendaftars as $index => $pendaftar)
                            {{-- ... (logika @php untuk $statusDokumenRingkas dan $kelasCssStatusDokumen bisa dihapus jika tidak digunakan lagi atau disesuaikan) ... --}}
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
                                {{-- Menampilkan Status Dokumen Wajib (Validate/Invalidate) --}}
                                <td class="px-5 py-4 text-center align-middle">
                                    <span class="status-badge-sm
                                        @if ($pendaftar->status_kelengkapan_dokumen == 'Validate') status-dokumen-overall-validate
                                        @else status-dokumen-overall-invalidate @endif">
                                        {{ $pendaftar->status_kelengkapan_dokumen }}
                                    </span>
                                </td>
                                <td class="px-5 py-4 text-center align-middle">
                                    <div class="flex item-center justify-center space-x-1 sm:space-x-2">
                                        <a href="{{ route('admin.pendaftar.showDokumen', $pendaftar->id) }}" class="text-xs bg-blue-100 text-blue-600 hover:bg-blue-200 px-3 py-1.5 rounded-md font-medium">Dokumen</a>
                                        <a href="{{ route('admin.pendaftar.edit', $pendaftar->id) }}" class="text-xs bg-yellow-100 text-yellow-600 hover:bg-yellow-200 px-3 py-1.5 rounded-md font-medium">Ubah Status</a>
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
                                <td colspan="8" class="px-5 py-4 text-center text-gray-500"> {{-- Colspan disesuaikan menjadi 8 --}}
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