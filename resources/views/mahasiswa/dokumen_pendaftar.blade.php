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
            font-weight: 600;
        }
        body {
            background-color: #F0F8FF;
        }
        .replace-form {
            display: none;
        }
        .replace-form.active {
            display: flex;
            gap: 0.5rem;
            margin-top: 0.5rem;
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
                    <th>Aksi</th>
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
                    <td>
                        @if($pendaftar->status_lamaran == 'Pending')
                        <button onclick="toggleReplaceForm('replace-form-{{ $doc->id }}')"
                                class="text-yellow-600 hover:text-yellow-800 text-sm font-medium">
                            Ganti File
                        </button>
                        <div id="replace-form-{{ $doc->id }}" class="replace-form">
                            <form action="{{ route('mahasiswa.pendaftar.dokumen.replace', ['pendaftar' => $pendaftar->id, 'document' => $doc->id]) }}"
                                  method="POST"
                                  enctype="multipart/form-data"
                                  class="items-center">
                                @csrf
                                <input type="file" name="replacement_file" required
                                       class="text-sm text-gray-500 file:mr-2 file:py-1 file:px-2
                                              file:rounded file:border-0
                                              file:text-xs file:font-medium
                                              file:bg-blue-50 file:text-blue-700
                                              hover:file:bg-blue-100 w-full mb-1">
                                <div class="flex gap-1">
                                    <button type="submit"
                                            class="px-2 py-1 bg-blue-600 hover:bg-blue-700 text-white text-xs rounded">
                                        Upload
                                    </button>
                                    <button type="button"
                                            onclick="toggleReplaceForm('replace-form-{{ $doc->id }}')"
                                            class="px-2 py-1 bg-gray-200 hover:bg-gray-300 text-gray-700 text-xs rounded">
                                        Batal
                                    </button>
                                </div>
                            </form>
                        </div>
                        @else
                        <span class="text-gray-400 text-sm">Tidak dapat diganti</span>
                        @endif
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

<script>
    function toggleReplaceForm(formId) {
        const form = document.getElementById(formId);
        form.classList.toggle('active');
    }
</script>

@include('mahasiswa.template.footer')
</body>
</html>
