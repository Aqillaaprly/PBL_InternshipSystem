<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Status Pendaftaran Magang</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .document-table {
            min-width: 100%;
            border-collapse: collapse;
        }
        .document-table th, .document-table td {
            padding: 0.75rem;
            text-align: left;
            border-bottom: 1px solid #e5e7eb;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .document-table th {
            background-color: #F0F8FF;
            color: #374151;
            font-weight: 600;
        }
        .document-table td {
            max-width: 0;
        }
        /* Specific column widths */
        .col-job { width: 20%; }
        .col-company { width: 18%; }
        .col-date { width: 12%; }
        .col-status { width: 12%; }
        .col-docs { width: 12%; }
        .col-notes { width: 15%; }
        .col-actions { width: 11%; }

        body {
            background-color: #F0F8FF;
        }

        /* Tooltip for truncated text */
        .truncate-text {
            max-width: 150px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            cursor: help;
        }

        .truncate-notes {
            max-width: 120px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            cursor: help;
        }
    </style>
</head>
@include('mahasiswa.template.navbar')

<body class="pt-20">
<div class="max-w-7xl mx-auto p-4 space-y-6">
    <!-- Status Table -->
    <div class="bg-white rounded-xl shadow-md p-6">
        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-2xl font-semibold">Status Lamaran Anda</h1>
                <p class="text-sm text-gray-500">Riwayat pendaftaran magang Anda</p>
            </div>
            <a href="{{ route('mahasiswa.pendaftar.form') }}"
               class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-md">
                + Daftar Magang Baru
            </a>
        </div>

        @if(session('recommended_job'))
        <div class="mb-4 p-4 bg-blue-100 border border-blue-400 text-blue-700 rounded">
            Anda sedang mengikuti rekomendasi sistem untuk: <strong>{{ session('recommended_job') }}</strong>
        </div>
        @endif

        @if(session('success'))
        <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
            {{ session('success') }}
        </div>
        @endif
        @if(session('error'))
        <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded">
            {{ session('error') }}
        </div>
        @endif

        @if(isset($pendaftarans) && count($pendaftarans) > 0)
        <div class="overflow-x-auto">
            <table class="w-full document-table">
                <thead>
                <tr>
                    <th class="col-job">Lowongan</th>
                    <th class="col-company">Perusahaan</th>
                    <th class="col-date">Tgl Daftar</th>
                    <th class="col-status">Status</th>
                    <th class="col-docs">Dokumen</th>
                    <th class="col-notes">Catatan</th>
                    <th class="col-actions">Aksi</th>
                </tr>
                </thead>
                <tbody>
                @foreach($pendaftarans as $pendaftar)
                <tr>
                    <td class="col-job">
                        <div class="truncate-text" title="{{ $pendaftar->lowongan->judul ?? '-' }}">
                            {{ $pendaftar->lowongan->judul ?? '-' }}
                        </div>
                    </td>
                    <td class="col-company">
                        <div class="truncate-text" title="{{ $pendaftar->lowongan->company->nama_perusahaan ?? '-' }}">
                            {{ $pendaftar->lowongan->company->nama_perusahaan ?? '-' }}
                        </div>
                    </td>
                    <td class="col-date">
                        {{ \Carbon\Carbon::parse($pendaftar->tanggal_daftar)->format('d/m/Y') }}
                    </td>
                    <td class="col-status">
                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium
                            @if($pendaftar->status_lamaran == 'Diterima') bg-green-100 text-green-800
                            @elseif($pendaftar->status_lamaran == 'Ditolak') bg-red-100 text-red-800
                            @else bg-yellow-100 text-yellow-800 @endif">
                            {{ $pendaftar->status_lamaran }}
                        </span>
                    </td>
                    <td class="col-docs">
                        @if($pendaftar->dokumenPendaftars->count() > 0)
                        @php
                        $validCount = $pendaftar->dokumenPendaftars->where('status_validasi', 'Valid')->count();
                        $totalCount = $pendaftar->dokumenPendaftars->count();
                        @endphp
                        <span class="text-sm font-medium
                            @if($validCount == $totalCount) text-green-600
                            @elseif($validCount > 0) text-yellow-600
                            @else text-red-600 @endif">
                            {{ $validCount }}/{{ $totalCount }}
                        </span>
                        @else
                        <span class="text-sm text-gray-500">-</span>
                        @endif
                    </td>
                    <td class="col-notes">
                        @if($pendaftar->catatan_admin)
                        <div class="truncate-notes" title="{{ $pendaftar->catatan_admin }}">
                            {{ $pendaftar->catatan_admin }}
                        </div>
                        @else
                        <span class="text-gray-400">-</span>
                        @endif
                    </td>
                    <td class="col-actions">
                        <div class="flex flex-col gap-1">
                            <a href="{{ route('mahasiswa.pendaftar.dokumen', $pendaftar->id) }}"
                               class="text-blue-600 hover:text-blue-800 text-xs font-medium">
                                Lihat Dokumen
                            </a>
                            @if($pendaftar->status_lamaran == 'Pending')
                            <form action="{{ route('mahasiswa.pendaftar.cancel', $pendaftar->id) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-800 text-xs font-medium"
                                        onclick="return confirm('Yakin batalkan?')">
                                    Batalkan
                                </button>
                            </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @endforeach
                </tbody>
            </table>
            {{ $pendaftarans->links() }}
        </div>
        @else
        <div class="text-center py-8">
            <p class="text-gray-500">Anda belum memiliki riwayat pendaftaran magang.</p>
            <a href="{{ route('mahasiswa.pendaftar.form') }}" class="mt-4 inline-block px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-md">
                Daftar Magang Sekarang
            </a>
        </div>
        @endif
    </div>

    <!-- Application Form Section (if this page includes a form) -->
    @if(isset($showForm) && $showForm)
    <div class="bg-white rounded-xl shadow-md p-6">
        <h2 class="text-xl font-semibold mb-4">Daftar Magang</h2>

        @if(session('recommended_job'))
        <div class="mb-4 p-4 bg-blue-100 border border-blue-400 text-blue-700 rounded">
            Anda sedang mengikuti rekomendasi sistem untuk: <strong>{{ session('recommended_job') }}</strong>
        </div>
        @endif

        <form action="{{ route('mahasiswa.pendaftar.store') }}" method="POST">
            @csrf
            <div class="mb-4">
                <label for="lowongan_id" class="block text-sm font-medium text-gray-700 mb-2">
                    Pilih Lowongan
                </label>
                <select name="lowongan_id"
                        id="lowongan_id"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @if(session('recommended_job')) bg-gray-100 cursor-not-allowed @endif"
                        @if(session('recommended_job')) disabled @endif
                required>
                <option value="">-- Pilih Lowongan --</option>
                @if(isset($lowongans))
                @foreach($lowongans as $lowongan)
                <option value="{{ $lowongan->id }}"
                        @if(session('recommended_job_id') == $lowongan->id) selected @endif>
                {{ $lowongan->judul }} - {{ $lowongan->company->nama_perusahaan ?? 'N/A' }}
                </option>
                @endforeach
                @endif
                </select>
                @if(session('recommended_job'))
                <input type="hidden" name="lowongan_id" value="{{ session('recommended_job_id') }}">
                <p class="text-xs text-gray-500 mt-1">Lowongan terkunci berdasarkan rekomendasi sistem</p>
                @endif
            </div>

            <div class="flex justify-end gap-2">
                <button type="button"
                        onclick="window.history.back()"
                        class="px-4 py-2 bg-gray-300 hover:bg-gray-400 text-gray-700 rounded-md">
                    Batal
                </button>
                <button type="submit"
                        class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-md">
                    Daftar
                </button>
            </div>
        </form>
    </div>
    @endif
</div>

@include('mahasiswa.template.footer')
</body>
</html>
