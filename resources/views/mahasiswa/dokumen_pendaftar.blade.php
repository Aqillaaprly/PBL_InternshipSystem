<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Dokumen Pendaftaran</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .document-table {
            width: 100%;
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
    <div class="bg-white rounded-xl shadow-md p-6">
        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-2xl font-semibold">Dokumen Pendaftaran</h1>
                <p class="text-sm text-gray-500">
                    Lowongan: {{ $pendaftar->lowongan->judul ?? '-' }} |
                    Perusahaan: {{ $pendaftar->lowongan->company->nama_perusahaan ?? '-' }}
                </p>
            </div>
            <a href="{{ route('mahasiswa.pendaftar') }}"
               class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-md">
                Kembali
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

        @if($dokumen->count() > 0)
        <div class="overflow-x-auto">
            <table class="w-full document-table">
                <thead>
                <tr>
                    <th>Nama Dokumen</th>
                    <th>Status Validasi</th>
                    <th>File</th>
                </tr>
                </thead>
                <tbody>
                @foreach($dokumen as $doc)
                <tr>
                    <td>{{ $doc->nama_dokumen }}</td>
                    <td>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                            @if($doc->status_validasi == 'Valid') bg-green-100 text-green-800
                            @elseif($doc->status_validasi == 'Tidak Valid') bg-red-100 text-red-800
                            @else bg-yellow-100 text-yellow-800 @endif">
                            {{ $doc->status_validasi }}
                        </span>
                    </td>
                    <td>
                        <a href="{{ asset('storage/' . $doc->file_path) }}"
                           target="_blank"
                           class="text-blue-600 hover:text-blue-800 font-medium">
                            Lihat Dokumen
                        </a>
                    </td>
                </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        @else
        <div class="text-center py-8">
            <p class="text-gray-500">Belum ada dokumen yang diunggah untuk pendaftaran ini.</p>
        </div>
        @endif
    </div>
</div>

</body>
</html>
