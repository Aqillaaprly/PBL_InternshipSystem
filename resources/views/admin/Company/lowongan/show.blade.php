<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Detail Lowongan - {{ $lowongan->judul ?? 'Informasi Lowongan' }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css" xintegrity="sha512-SzlrxWUlpfuzQ+pcUCosxcglQRNAq/DZjVsC0lE40xsADsfeQoEypE+enwcOiGjk/bSuGGKHEyjSoQ1zVisanQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>
        body {
            background-color: #f7f8fc;
            font-family: 'Inter', sans-serif; /* Using Inter font */
        }
        .info-card {
            background-color: white;
            border-radius: 1rem;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
            padding: 2.5rem; /* Increased padding for more space */
            position: relative;
            z-index: 5;
            text-align: left;
        }
        .detail-item {
            display: flex;
            flex-direction: column;
        }
        .detail-label {
            color: #6b7280; /* Gray-500 */
            font-size: 0.9rem; /* Slightly larger for readability */
            font-weight: 600; /* Semi-bold */
            display: flex;
            align-items: center;
            margin-bottom: 0.35rem; /* Adjusted margin */
        }
        .detail-label i {
            margin-right: 0.6rem; /* More space for icon */
            color: #4f46e5; /* Indigo-600 for icons */
            font-size: 1.1em; /* Slightly larger icon */
        }
        .detail-value {
            color: #1f2937; /* Gray-800 */
            font-weight: 500;
            font-size: 1rem;
            line-height: 1.5; /* Better line spacing */
        }
        .whitespace-pre-line {
            white-space: pre-line;
        }
        .action-button {
            transition: all 0.2s ease-in-out;
            padding: 0.7rem 1.5rem; /* Increased padding */
            font-size: 0.9rem; /* Slightly larger font */
            font-weight: 600; /* Semi-bold */
            border-radius: 0.6rem; /* More rounded */
            display: inline-flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        .action-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        }
        .edit-button {
            background-image: linear-gradient(to right, #4f46e5, #7c3aed);
            color: white;
        }
        .edit-button:hover {
             background-image: linear-gradient(to right, #4338ca, #6d28d9);
        }
        .delete-button {
            background-color: #ef4444; /* Red-500 */
            color: white;
        }
        .delete-button:hover {
            background-color: #dc2626; /* Red-600 */
        }
        .section-header {
            font-size: 1.5rem; /* Larger section titles */
            font-weight: 700; /* Bold */
            color: #1f2937; /* Gray-800 */
            margin-bottom: 1.5rem;
            padding-bottom: 0.75rem;
            border-bottom: 1px solid #e5e7eb; /* Subtle separator */
        }
        .pill-badge {
            padding: 0.25rem 0.75rem; /* Slightly larger badge */
            border-radius: 9999px; /* Fully rounded */
        }
    </style>
</head>
<body class="bg-blue-50 text-gray-800">
    @include('admin.template.navbar')

    <main class="max-w-4xl mx-auto px-4 py-10 mt-16">
        <div class="info-card">
            <!-- Header Section -->
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8 pb-4 border-b border-gray-100">
                <div>
                    <h1 class="text-3xl font-extrabold text-gray-900 mb-1 leading-tight">{{ $lowongan->judul ?? 'Informasi Lowongan' }}</h1>
                    <p class="text-lg text-gray-600"><span class="font-semibold text-indigo-700">{{ $lowongan->company->nama_perusahaan ?? 'N/A' }}</span></p>
                </div>
                <a href="{{ route('perusahaan.lowongan') }}" class="mt-4 sm:mt-0 text-sm text-indigo-600 hover:underline flex items-center">
                    <i class="fas fa-arrow-left mr-2"></i> Kembali ke Daftar Lowongan
                </a>
            </div>

            <!-- Session Messages -->
            @if (session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 px-4 py-3 rounded-lg relative mb-6" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif
            @if (session('error'))
                 <div class="bg-red-100 border-l-4 border-red-500 text-red-700 px-4 py-3 rounded-lg relative mb-6" role="alert">
                    <strong class="font-bold">Gagal!</strong>
                    <span class="block sm:inline">{{ session('error') }}</span>
                </div>
            @endif

            @if(isset($lowongan) && $lowongan->id)
                <div class="space-y-10"> <!-- Increased space between sections -->

                    <!-- Ringkasan Lowongan Section -->
                    <div class="bg-indigo-50 bg-opacity-20 p-6 rounded-xl shadow-inner">
                        <h2 class="section-header text-indigo-800">Ringkasan Lowongan</h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-y-6 gap-x-8">
                            <div class="detail-item">
                                <span class="detail-label"><i class="fas fa-tag"></i>Tipe Pekerjaan</span>
                                <span class="detail-value">{{ $lowongan->tipe ?? '-' }}</span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-label"><i class="fas fa-calendar-alt"></i>Tanggal Buka</span>
                                <span class="detail-value">{{ $lowongan->tanggal_buka ? \Carbon\Carbon::parse($lowongan->tanggal_buka)->isoFormat('D MMMM YYYY') : '-' }}</span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-label"><i class="fas fa-calendar-times"></i>Tanggal Tutup</span>
                                <span class="detail-value">{{ $lowongan->tanggal_tutup ? \Carbon\Carbon::parse($lowongan->tanggal_tutup)->isoFormat('D MMMM YYYY') : '-' }}</span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-label"><i class="fas fa-info-circle"></i>Status Lowongan</span>
                                <span class="detail-value">
                                    <span class="pill-badge
                                        @if($lowongan->status == 'Aktif') bg-green-200 text-green-800
                                        @elseif($lowongan->status == 'Nonaktif') bg-red-200 text-red-800
                                        @else bg-yellow-200 text-yellow-800 @endif">
                                        {{ $lowongan->status ?? '-' }}
                                    </span>
                                </span>
                            </div>
                            @if($lowongan->gaji_min || $lowongan->gaji_max)
                                <div class="detail-item md:col-span-2 lg:col-span-2">
                                    <span class="detail-label"><i class="fas fa-money-bill-wave"></i>Estimasi Gaji</span>
                                    <span class="detail-value">
                                        @if($lowongan->gaji_min && $lowongan->gaji_max)
                                            Rp. {{ number_format($lowongan->gaji_min, 0, ',', '.') }} - Rp. {{ number_format($lowongan->gaji_max, 0, ',', '.') }}
                                        @elseif($lowongan->gaji_min)
                                            Mulai dari Rp. {{ number_format($lowongan->gaji_min, 0, ',', '.') }}
                                        @elseif($lowongan->gaji_max)
                                            Hingga Rp. {{ number_format($lowongan->gaji_max, 0, ',', '.') }}
                                        @else
                                            -
                                        @endif
                                    </span>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Lokasi Pekerjaan Section -->
                    <div class="bg-indigo-50 bg-opacity-20 p-6 rounded-xl shadow-inner">
                        <h2 class="section-header text-indigo-800">Lokasi Pekerjaan</h2>
                        <div class="detail-item">
                            <span class="detail-label"><i class="fas fa-map-marker-alt"></i>Alamat Lengkap</span>
                            <span class="detail-value">
                                @if($lowongan->alamat) {{ $lowongan->alamat }}<br> @endif
                                @if($lowongan->kota && $lowongan->provinsi) {{ $lowongan->kota }}, {{ $lowongan->provinsi }}<br> @endif
                                @if($lowongan->kode_pos) Kode Pos: {{ $lowongan->kode_pos }} @endif
                                @if(empty($lowongan->alamat) && empty($lowongan->kota) && empty($lowongan->provinsi) && empty($lowongan->kode_pos))
                                    Tidak ditentukan.
                                @endif
                            </span>
                        </div>
                    </div>

                    <!-- Detail Konten Section -->
                    <div class="bg-white p-6 rounded-xl shadow-lg">
                        <h2 class="section-header text-indigo-800 mb-6">Detail Konten</h2>
                        <div class="space-y-8"> <!-- More space between content blocks -->
                            <div class="detail-item">
                                <span class="detail-label text-xl font-semibold"><i class="fas fa-file-alt"></i>Deskripsi Pekerjaan</span>
                                <p class="detail-value whitespace-pre-line text-base leading-relaxed">{{ $lowongan->deskripsi ?? 'Tidak ada deskripsi pekerjaan.' }}</p>
                            </div>

                            <div class="detail-item">
                                <span class="detail-label text-xl font-semibold"><i class="fas fa-user-graduate"></i>Kualifikasi</span>
                                <p class="detail-value whitespace-pre-line text-base leading-relaxed">{{ $lowongan->kualifikasi ?? 'Tidak ada kualifikasi spesifik yang dicantumkan.' }}</p>
                            </div>

                            <div class="detail-item">
                                <span class="detail-label text-xl font-semibold"><i class="fas fa-tasks"></i>Tanggung Jawab</span>
                                <p class="detail-value whitespace-pre-line text-base leading-relaxed">{{ $lowongan->tanggung_jawab ?? 'Tidak ada tanggung jawab spesifik yang dicantumkan.' }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Informasi Waktu Section -->
                    <div class="bg-indigo-50 bg-opacity-20 p-6 rounded-xl shadow-inner">
                        <h2 class="section-header text-indigo-800">Informasi Waktu</h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-y-6 gap-x-8">
                            <div class="detail-item">
                                <span class="detail-label"><i class="fas fa-calendar-plus"></i>Lowongan Dibuat</span>
                                <span class="detail-value">{{ $lowongan->created_at ? $lowongan->created_at->isoFormat('D MMMM YYYY, HH:mm') : '-' }}</span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-label"><i class="fas fa-history"></i>Terakhir Diperbarui</span>
                                <span class="detail-value">{{ $lowongan->updated_at ? $lowongan->updated_at->diffForHumans() : '-' }}</span>
                            </div>
                        </div>
                    </div>

                </div>

                <!-- Action Buttons -->
                <div class="mt-10 flex flex-col sm:flex-row justify-end space-y-4 sm:space-y-0 sm:space-x-4">
                    <a href="{{ route('perusahaan.lowongan.edit', $lowongan->id) }}" class="action-button edit-button">
                        <i class="fas fa-pencil-alt mr-2"></i>Edit Lowongan
                    </a>
                    <form action="{{ route('perusahaan.lowongan.destroy', $lowongan->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus lowongan ini? Tindakan ini tidak dapat dibatalkan.');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="action-button delete-button">
                            <i class="fas fa-trash-alt mr-2"></i>Hapus Lowongan
                        </button>
                    </form>
                </div>
            @else
                <div class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-4 rounded-lg" role="alert">
                    <p class="font-bold">Data Lowongan Tidak Ditemukan</p>
                    <p>Tidak dapat menampilkan detail karena data lowongan tidak valid atau tidak ditemukan.</p>
                </div>
            @endif
        </div>
    </main>

    @include('admin.template.footer')
</body>
</html>
