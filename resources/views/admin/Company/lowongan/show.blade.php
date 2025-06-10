<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Detail Lowongan - {{ $lowongan->judul }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            background-color: #f7f8fc;
        }
        .page-header {
            background: linear-gradient(to right, #687EEA, #3B5998);
            color: white;
            padding: 1.5rem;
            border-radius: 1rem 1rem 0 0;
            margin-bottom: -1rem;
            position: relative;
            z-index: 10;
        }
        .info-section {
            background-color: white;
            border-radius: 1rem;
            box-shadow: 0 20px 25px -5px rgba(0,0,0,0.1), 0 10px 10px -5px rgba(0,0,0,0.04);
            padding: 2rem;
            padding-top: 2rem;
            position: relative;
            z-index: 5;
        }
        .info-block {
            border-bottom: 1px solid #f3f4f6;
            padding: 1rem 0;
        }
        .info-label {
            font-weight: 500;
            color: #6b7280;
        }
        .info-value {
            color: #1f2937;
        }
        .status-badge {
            padding: 0.375rem 0.75rem;
            font-size: 0.875rem;
            font-weight: 600;
            border-radius: 9999px;
            display: inline-block;
        }
        .action-button {
            transition: all 0.2s ease-in-out;
            padding: 0.625rem 1.25rem;
            font-size: 0.875rem;
            border-radius: 0.5rem;
        }
        .action-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }
        .edit-button {
            background-image: linear-gradient(to right, #4f46e5, #7c3aed);
            color: white;
        }
        .edit-button:hover {
            background-image: linear-gradient(to right, #4338ca, #6d28d9);
        }
    </style>
</head>
<body class="text-gray-800">
    @include('admin.template.navbar')

    <main class="max-w-4xl mx-auto px-4 py-10 mt-20">
        <div class="page-header text-center">
            <h1 class="text-3xl font-bold">Detail Lowongan</h1>
            <p class="text-sm text-blue-100 mt-1">{{ $lowongan->judul }}</p>
        </div>

        <div class="info-section">
            @if (session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 px-4 py-3 rounded mb-6" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            <div class="space-y-4">
                <div class="info-block">
                    <span class="info-label block">Judul Lowongan:</span>
                    <p class="info-value text-lg">{{ $lowongan->judul }}</p>
                </div>

                <div class="info-block">
                    <span class="info-label block">Perusahaan:</span>
                    <p class="info-value">{{ $lowongan->company->nama_perusahaan ?? 'N/A' }}</p>
                </div>

                <div class="info-block">
                    <span class="info-label block">Deskripsi:</span>
                    <div class="prose prose-sm max-w-none text-gray-800 mt-1">{!! nl2br(e($lowongan->deskripsi)) !!}</div>
                </div>

                <div class="info-block">
                    <span class="info-label block">Kualifikasi:</span>
                    <div class="prose prose-sm max-w-none text-gray-800 mt-1">{!! nl2br(e($lowongan->kualifikasi)) !!}</div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 pt-2">
                    <div>
                        <span class="info-label block">Tipe:</span>
                        <p class="info-value">{{ $lowongan->tipe }}</p>
                    </div>
                    <div>
                        <span class="info-label block">Lokasi:</span>
                        <p class="info-value">{{ $lowongan->lokasi }}</p>
                    </div>
                    <div>
                        <span class="info-label block">Gaji Minimum:</span>
                        <p class="info-value">{{ $lowongan->gaji_min ? 'Rp ' . number_format($lowongan->gaji_min, 0, ',', '.') : '-' }}</p>
                    </div>
                    <div>
                        <span class="info-label block">Gaji Maksimum:</span>
                        <p class="info-value">{{ $lowongan->gaji_max ? 'Rp ' . number_format($lowongan->gaji_max, 0, ',', '.') : '-' }}</p>
                    </div>
                    <div>
                        <span class="info-label block">Tanggal Buka:</span>
                        <p class="info-value">{{ $lowongan->tanggal_buka ? \Carbon\Carbon::parse($lowongan->tanggal_buka)->isoFormat('D MMMM YYYY') : '-' }}</p>
                    </div>
                    <div>
                        <span class="info-label block">Tanggal Tutup:</span>
                        <p class="info-value">{{ $lowongan->tanggal_tutup ? \Carbon\Carbon::parse($lowongan->tanggal_tutup)->isoFormat('D MMMM YYYY') : '-' }}</p>
                    </div>
                    <div>
                        <span class="info-label block">Status:</span>
                        <span class="status-badge
                            @if($lowongan->status == 'Aktif') bg-green-100 text-green-700
                            @else bg-red-100 text-red-700 @endif">
                            {{ $lowongan->status }}
                        </span>
                    </div>
                    <div>
                        <span class="info-label block">Dibuat pada:</span>
                        <p class="info-value text-sm">{{ $lowongan->created_at->isoFormat('D MMMM YYYY, HH:mm') }}</p>
                    </div>
                    <div>
                        <span class="info-label block">Diperbarui pada:</span>
                        <p class="info-value text-sm">{{ $lowongan->updated_at->isoFormat('D MMMM YYYY, HH:mm') }}</p>
                    </div>
                </div>
            </div>

            <div class="mt-10 flex justify-end">
                <a href="{{ route('admin.lowongan.edit', $lowongan->id) }}"
                   class="action-button edit-button inline-flex items-center shadow-lg">
                    <i class="fas fa-pencil-alt mr-2"></i>Edit Lowongan
                </a>
            </div>
        </div>
    </main>

    @include('admin.template.footer')
</body>
</html>
