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
        .file-upload-container {
            transition: all 0.3s ease;
        }
        .file-upload-container:hover {
            transform: translateY(-2px);
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
            <p class="text-sm text-gray-500">Riwayat pendaftaran magang Anda</p>
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
            <p class="text-sm text-gray-500">Lengkapi form berikut untuk mendaftar magang. Pastikan semua dokumen yang diperlukan sudah siap.</p>
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
                    <label class="block text-sm font-medium text-gray-700 mb-1">Pilih Lowongan <span class="text-red-500">*</span></label>
                    <select name="lowongan_id" required
                            class="w-full px-4 py-2 border rounded-md focus:ring-blue-500 focus:border-blue-500">
                        <option value="">-- Pilih Lowongan --</option>
                        @foreach ($lowongans as $lowongan)
                        <option value="{{ $lowongan->id }}"
                                {{ (isset($selectedLowonganId) && $selectedLowonganId == $lowongan->id) || old('lowongan_id') == $lowongan->id ? 'selected' : '' }}>
                            {{ $lowongan->judul }} - {{ $lowongan->company->nama_perusahaan }}
                            (Tutup: {{ \Carbon\Carbon::parse($lowongan->tanggal_tutup)->format('d/m/Y') }})
                        </option>
                        @endforeach
                    </select>
                </div>

                <!-- Document Uploads -->
                <div class="col-span-2">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Unggah Dokumen Persyaratan</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Required Documents -->
                        <div class="file-upload-container p-4 border border-blue-100 rounded-lg bg-blue-50">
                            <h4 class="font-medium text-blue-800 mb-3">Dokumen Wajib</h4>
                            <div class="space-y-4">
                                <!-- Surat Lamaran -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">
                                        Surat Lamaran <span class="text-red-500">*</span>
                                    </label>
                                    <input type="file" name="surat_lamaran" required
                                           accept=".pdf,.doc,.docx"
                                           class="w-full px-3 py-2 border rounded-md file:mr-4 file:py-2 file:px-4
                                           file:rounded-md file:border-0 file:text-sm file:font-semibold
                                           file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100"/>
                                    <p class="mt-1 text-xs text-gray-500">Format: PDF/DOC/DOCX (Max: 5MB)</p>
                                </div>

                                <!-- CV -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">
                                        Daftar Riwayat Hidup (CV) <span class="text-red-500">*</span>
                                    </label>
                                    <input type="file" name="cv" required
                                           accept=".pdf,.doc,.docx"
                                           class="w-full px-3 py-2 border rounded-md file:mr-4 file:py-2 file:px-4
                                           file:rounded-md file:border-0 file:text-sm file:font-semibold
                                           file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100"/>
                                    <p class="mt-1 text-xs text-gray-500">Format: PDF/DOC/DOCX (Max: 5MB)</p>
                                </div>

                                <!-- KHS/Transkrip -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">
                                        KHS atau Transkrip Nilai <span class="text-red-500">*</span>
                                    </label>
                                    <input type="file" name="khs_transkrip" required
                                           accept=".pdf,.doc,.docx"
                                           class="w-full px-3 py-2 border rounded-md file:mr-4 file:py-2 file:px-4
                                           file:rounded-md file:border-0 file:text-sm file:font-semibold
                                           file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100"/>
                                    <p class="mt-1 text-xs text-gray-500">Format: PDF/DOC/DOCX (Max: 5MB)</p>
                                </div>

                                <!-- KTP -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">
                                        KTP <span class="text-red-500">*</span>
                                    </label>
                                    <input type="file" name="ktp" required
                                           accept="image/*"
                                           class="w-full px-3 py-2 border rounded-md file:mr-4 file:py-2 file:px-4
                                           file:rounded-md file:border-0 file:text-sm file:font-semibold
                                           file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100"/>
                                    <p class="mt-1 text-xs text-gray-500">Format: JPG/PNG (Max: 2MB)</p>
                                </div>

                                <!-- KTM -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">
                                        KTM <span class="text-red-500">*</span>
                                    </label>
                                    <input type="file" name="ktm" required
                                           accept="image/*"
                                           class="w-full px-3 py-2 border rounded-md file:mr-4 file:py-2 file:px-4
                                           file:rounded-md file:border-0 file:text-sm file:font-semibold
                                           file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100"/>
                                    <p class="mt-1 text-xs text-gray-500">Format: JPG/PNG (Max: 2MB)</p>
                                </div>

                                <!-- Surat Izin Orang Tua -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">
                                        Surat Izin Orang Tua <span class="text-red-500">*</span>
                                    </label>
                                    <input type="file" name="surat_izin_ortu" required
                                           accept=".pdf,.doc,.docx"
                                           class="w-full px-3 py-2 border rounded-md file:mr-4 file:py-2 file:px-4
                                           file:rounded-md file:border-0 file:text-sm file:font-semibold
                                           file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100"/>
                                    <p class="mt-1 text-xs text-gray-500">Format: PDF/DOC/DOCX (Max: 5MB)</p>
                                </div>

                                <!-- Pakta Integritas -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">
                                        Pakta Integritas <span class="text-red-500">*</span>
                                    </label>
                                    <input type="file" name="pakta_integritas" required
                                           accept=".pdf,.doc,.docx"
                                           class="w-full px-3 py-2 border rounded-md file:mr-4 file:py-2 file:px-4
                                           file:rounded-md file:border-0 file:text-sm file:font-semibold
                                           file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100"/>
                                    <p class="mt-1 text-xs text-gray-500">Format: PDF/DOC/DOCX (Max: 5MB)</p>
                                </div>
                            </div>
                        </div>

                        <!-- Optional Documents -->
                        <div class="file-upload-container p-4 border border-gray-200 rounded-lg bg-gray-50">
                            <h4 class="font-medium text-gray-800 mb-3">Dokumen Tambahan</h4>
                            <div class="space-y-4">
                                <!-- Portofolio -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">
                                        Portofolio
                                    </label>
                                    <input type="file" name="portofolio"
                                           accept=".pdf,.doc,.docx"
                                           class="w-full px-3 py-2 border rounded-md file:mr-4 file:py-2 file:px-4
                                           file:rounded-md file:border-0 file:text-sm file:font-semibold
                                           file:bg-gray-50 file:text-gray-700 hover:file:bg-gray-100"/>
                                    <p class="mt-1 text-xs text-gray-500">Format: PDF/DOC/DOCX (Max: 5MB)</p>
                                </div>

                                <!-- Sertifikat Kompetensi -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">
                                        Sertifikat Kompetensi
                                    </label>
                                    <input type="file" name="sertifikat_kompetensi"
                                           accept=".pdf,.doc,.docx"
                                           class="w-full px-3 py-2 border rounded-md file:mr-4 file:py-2 file:px-4
                                           file:rounded-md file:border-0 file:text-sm file:font-semibold
                                           file:bg-gray-50 file:text-gray-700 hover:file:bg-gray-100"/>
                                    <p class="mt-1 text-xs text-gray-500">Format: PDF/DOC/DOCX (Max: 5MB)</p>
                                </div>

                                <!-- SKTM/KIP -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">
                                        SKTM atau KIP Kuliah
                                    </label>
                                    <input type="file" name="sktm_kip"
                                           accept=".pdf,.doc,.docx"
                                           class="w-full px-3 py-2 border rounded-md file:mr-4 file:py-2 file:px-4
                                           file:rounded-md file:border-0 file:text-sm file:font-semibold
                                           file:bg-gray-50 file:text-gray-700 hover:file:bg-gray-100"/>
                                    <p class="mt-1 text-xs text-gray-500">Format: PDF/DOC/DOCX (Max: 5MB)</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Catatan Pendaftar -->
                <div class="col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Catatan Tambahan</label>
                    <textarea name="catatan_pendaftar" rows="3"
                              class="w-full px-3 py-2 border rounded-md focus:ring-blue-500 focus:border-blue-500"
                              placeholder="Tambahkan catatan tambahan jika ada...">{{ old('catatan_pendaftar') }}</textarea>
                    <p class="mt-1 text-xs text-gray-500">Maksimal 1000 karakter</p>
                </div>

                <!-- Terms Agreement -->
                <div class="col-span-2">
                    <div class="flex items-start">
                        <div class="flex items-center h-5">
                            <input id="terms" name="terms" type="checkbox" required
                                   class="focus:ring-blue-500 h-4 w-4 text-blue-600 border-gray-300 rounded">
                        </div>
                        <div class="ml-3 text-sm">
                            <label for="terms" class="font-medium text-gray-700">Dengan mengirim formulir ini, saya menyatakan bahwa semua dokumen yang saya unggah adalah benar dan sah.</label>
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex justify-end">
                <button type="submit"
                        class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-md shadow-sm transition duration-200">
                    Kirim Pendaftaran
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

    // Prevent form resubmission on page refresh
    if (window.history.replaceState) {
        window.history.replaceState(null, null, window.location.href);
    }
</script>

@include('mahasiswa.template.footer')
</body>
</html>
