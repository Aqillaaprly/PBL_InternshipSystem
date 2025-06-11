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
        }
        .document-table th {
            background-color: #F0F8FF;
            color: #374151;
        }
        body {
            background-color: #F0F8FF;
        }
    </style>
</head>
@include('mahasiswa.template.navbar')

<body class="pt-20">
<div class="max-w-5xl mx-auto p-4 space-y-6">
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
                    <th>Judul Lowongan</th>
                    <th>Perusahaan</th>
                    <th>Tanggal Daftar</th>
                    <th>Status Lamaran</th>
                    <th>Status Dokumen</th>
                    <th>Catatan Admin</th>
                    <th>Aksi</th>
                </tr>
                </thead>
                <tbody>
                @foreach($pendaftarans as $pendaftar)
                <tr>
                    <td>{{ $pendaftar->lowongan->judul ?? '-' }}</td>
                    <td>{{ $pendaftar->lowongan->company->nama_perusahaan ?? '-' }}</td>
                    <td>{{ \Carbon\Carbon::parse($pendaftar->tanggal_daftar)->format('d-m-Y') }}</td>
                    <td>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                            @if($pendaftar->status_lamaran == 'Diterima') bg-green-100 text-green-800
                            @elseif($pendaftar->status_lamaran == 'Ditolak') bg-red-100 text-red-800
                            @else bg-yellow-100 text-yellow-800 @endif">
                            {{ $pendaftar->status_lamaran }}
                        </span>
                    </td>
                    <td>
                        @if($pendaftar->dokumenPendaftars->count() > 0)
                        @php
                        $validCount = $pendaftar->dokumenPendaftars->where('status_validasi', 'Valid')->count();
                        $totalCount = $pendaftar->dokumenPendaftars->count();
                        @endphp
                        <span class="text-sm">{{ $validCount }}/{{ $totalCount }} dokumen valid</span>
                        @else
                        <span class="text-sm text-gray-500">Belum ada dokumen</span>
                        @endif
                    </td>
                    <td>{{ $pendaftar->catatan_admin ?? '-' }}</td>
                    <td class="flex gap-2">
                        <button onclick="showDocuments('{{ $pendaftar->id }}')"
                                class="text-blue-600 hover:text-blue-800 font-medium">
                            Lihat Dokumen
                        </button>
                        @if($pendaftar->status_lamaran == 'Pending')
                        <form action="{{ route('mahasiswa.pendaftar.cancel', $pendaftar->id) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-800 font-medium"
                                    onclick="return confirm('Apakah Anda yakin ingin membatalkan pendaftaran ini?')">
                                Batalkan
                            </button>
                        </form>
                        @endif
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
</div>

<!-- Document Modal -->
<div id="documentModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center hidden z-50">
    <div class="bg-white rounded-lg p-6 max-w-3xl w-full max-h-[80vh] overflow-y-auto">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-xl font-semibold">Dokumen Pendaftaran</h3>
            <button onclick="hideModal()" class="text-gray-500 hover:text-gray-700">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
        <div id="documentContent">
            <!-- Content will be loaded here via AJAX -->
        </div>
    </div>
</div>

<script>
    function showDocuments(pendaftarId) {
        fetch(`/mahasiswa/pendaftar/dokumen/${pendaftarId}`)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Dokumen tidak ditemukan');
                }
                return response.text();
            })
            .then(html => {
                document.getElementById('documentContent').innerHTML = html;
                document.getElementById('documentModal').classList.remove('hidden');
            })
            .catch(error => {
                document.getElementById('documentContent').innerHTML = `
                    <div class="p-4 bg-red-100 border border-red-400 text-red-700 rounded">
                        ${error.message}
                    </div>
                `;
                document.getElementById('documentModal').classList.remove('hidden');
            });
    }

    function hideModal() {
        document.getElementById('documentModal').classList.add('hidden');
    }
</script>

@include('mahasiswa.template.footer')
</body>
</html>
