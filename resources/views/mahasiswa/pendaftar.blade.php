<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Form Pendaftaran Magang</title>
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
            background-color: #f3f4f6;
            color: #374151;
        }
    </style>
</head>
@include('mahasiswa.template.navbar')

<body class="bg-gray-50 text-gray-800 pt-20">
<div class="max-w-5xl mx-auto p-4 space-y-6">
    <!-- Status Table -->
    @if(isset($pendaftarans) && count($pendaftarans) > 0)
    <div class="bg-white rounded-xl shadow-md p-6">
        <div class="mb-6">
            <h1 class="text-2xl font-semibold">Status Lamaran Anda</h1>
            <p class="text-sm text-gray-500">Semoga anda diterima</p>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full document-table">
                <thead>
                <tr>
                    <th>Judul Lowongan</th>
                    <th>Perusahaan</th>
                    <th>Tanggal Daftar</th>
                    <th>Status Lamaran</th>
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
                    <td>{{ $pendaftar->catatan_admin ?? '-' }}</td>
                    <td>
                        <button onclick="showDocuments('{{ $pendaftar->id }}')"
                                class="text-blue-600 hover:text-blue-800 font-medium">
                            Lihat Dokumen
                        </button>
                    </td>
                </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif

    <!-- Registration Form -->
    <div class="bg-white rounded-xl shadow-md p-6">
        <div class="mb-6">
            <h1 class="text-2xl font-semibold">Form Pendaftaran Magang</h1>
            <p class="text-sm text-gray-500">Lengkapi form berikut untuk mendaftar magang.</p>
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
        @if($errors->any())
        <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded">
            <ul class="list-disc list-inside">
                @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <form action="{{ route('mahasiswa.pendaftar.submit') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Lowongan Selection -->
                <div class="col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Pilih Lowongan</label>
                    <select name="lowongan_id" class="w-full px-4 py-2 border rounded-md focus:ring-blue-500 focus:border-blue-500">
                        @foreach ($lowongans as $lowongan)
                        <option value="{{ $lowongan->id }}"
                                {{ isset($selectedLowonganId) && $selectedLowonganId == $lowongan->id ? 'selected' : '' }}>
                            {{ $lowongan->judul }} - {{ $lowongan->company->nama_perusahaan }}
                        </option>
                        @endforeach
                    </select>
                </div>

                <!-- Document Uploads -->
                @php
                $files = [
                'surat_lamaran' => ['label' => 'Surat Lamaran', 'required' => true],
                'cv' => ['label' => 'Daftar Riwayat Hidup (CV)', 'required' => true],
                'portofolio' => ['label' => 'Portofolio', 'required' => false],
                'khs_transkrip' => ['label' => 'KHS atau Transkrip Nilai', 'required' => true],
                'ktp' => ['label' => 'KTP', 'required' => true],
                'ktm' => ['label' => 'KTM', 'required' => true],
                'surat_izin_ortu' => ['label' => 'Surat Izin Orang Tua', 'required' => true],
                'pakta_integritas' => ['label' => 'Pakta Integritas', 'required' => true],
                'sertifikat_kompetensi' => ['label' => 'Sertifikat Kompetensi', 'required' => false],
                'sktm_kip' => ['label' => 'SKTM atau KIP Kuliah', 'required' => false]
                ];
                @endphp

                @foreach($files as $name => $file)
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        {{ $file['label'] }}
                        @if($file['required'])
                        <span class="text-red-500">*</span>
                        @endif
                    </label>
                    <input type="file" name="{{ $name }}"
                           accept="{{ in_array($name, ['ktp', 'ktm']) ? 'image/*' : '.pdf,.doc,.docx' }}"
                           {{ $file['required'] ? 'required' : '' }}
                    class="w-full px-3 py-2 border rounded-md file:mr-4 file:py-2 file:px-4
                    file:rounded-md file:border-0 file:text-sm file:font-semibold
                    file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100"/>
                    @error($name)
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                @endforeach

                <!-- Catatan Pendaftar -->
                <div class="col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Catatan Pendaftar</label>
                    <textarea name="catatan_pendaftar" rows="3"
                              class="w-full px-3 py-2 border rounded-md focus:ring-blue-500 focus:border-blue-500"
                              placeholder="Tambahkan catatan tambahan jika ada...">{{ old('catatan_pendaftar') }}</textarea>
                </div>
            </div>

            <div class="flex justify-end">
                <button type="submit"
                        class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-md shadow-sm">
                    Simpan Pendaftaran
                </button>
            </div>
        </form>
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
