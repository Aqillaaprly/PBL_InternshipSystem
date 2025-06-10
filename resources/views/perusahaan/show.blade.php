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
        }
        .info-card {
            background-color: white;
            border-radius: 1rem;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
            padding: 2rem;
            position: relative;
            z-index: 5;
            text-align: left;
        }
        .detail-item {
            padding: 0.5rem 0;
            display: flex;
            flex-direction: column;
        }
        .detail-label {
            color: #6b7280;
            font-size: 0.875rem;
            display: flex;
            align-items: center;
            margin-bottom: 0.25rem;
        }
        .detail-label i {
            margin-right: 0.5rem;
            color: #9ca3af;
        }
        .detail-value {
            color: #111827;
            font-weight: 500;
            font-size: 1rem;
        }
        .whitespace-pre-line {
            white-space: pre-line;
        }
        .action-button {
            transition: all 0.2s ease-in-out;
            padding: 0.625rem 1.25rem;
            font-size: 0.875rem;
            border-radius: 0.5rem;
        }
        .action-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        }
        .edit-button {
            background-image: linear-gradient(to right, #4f46e5, #7c3aed);
        }
        .edit-button:hover {
             background-image: linear-gradient(to right, #4338ca, #6d28d9);
        }
    </style>
</head>
<body class="bg-blue-50 text-gray-800">
    @include('perusahaan.template.navbar')

    <main class="max-w-4xl mx-auto px-4 py-10 mt-16">
        <div class="info-card">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-bold text-blue-800">Detail Lowongan: {{ $lowongan->judul ?? 'N/A' }}</h1>
                {{-- Tautan kembali ke daftar lowongan --}}
                <a href="{{ route('perusahaan.lowongan') }}" class="text-sm text-blue-600 hover:underline">&larr; Kembali ke Daftar Lowongan</a>
            </div>

            @if (session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif
            @if (session('error'))
                 <div class="bg-red-100 border-l-4 border-red-500 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <strong class="font-bold">Gagal!</strong>
                    <span class="block sm:inline">{{ session('error') }}</span>
                </div>
            @endif

            @if(isset($lowongan) && $lowongan->id)
                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4">

                    <div class="detail-item">
                        <span class="detail-label"><i class="fas fa-briefcase"></i>Judul Lowongan</span>
                        <span class="detail-value">{{ $lowongan->judul ?? '-' }}</span>
                    </div>

                    <div class="detail-item">
                        <span class="detail-label"><i class="fas fa-building"></i>Perusahaan</span>
                        <span class="detail-value">{{ $lowongan->company->nama_perusahaan ?? 'N/A' }}</span>
                    </div>

                    <div class="detail-item">
                        <span class="detail-label"><i class="fas fa-tag"></i>Tipe Lowongan</span>
                        <span class="detail-value">{{ $lowongan->tipe ?? '-' }}</span>
                    </div>

                    <div class="detail-item">
                        <span class="detail-label"><i class="fas fa-map-marker-alt"></i>Lokasi</span>
                        <span class="detail-value">{{ $lowongan->lokasi ?? '-' }}</span>
                    </div>

                    <div class="detail-item">
                        <span class="detail-label"><i class="fas fa-calendar-times"></i>Tanggal Tutup</span>
                        <span class="detail-value">{{ \Carbon\Carbon::parse($lowongan->tanggal_tutup)->isoFormat('D MMMM YYYY') ?? '-' }}</span>
                    </div>

                    <div class="detail-item">
                        <span class="detail-label"><i class="fas fa-info-circle"></i>Status Lowongan</span>
                        <span class="detail-value">
                            <span class="px-2 py-1 text-xs font-semibold leading-tight rounded-full
                                @if($lowongan->status == 'Aktif') bg-green-100 text-green-700
                                @elseif($lowongan->status == 'Nonaktif') bg-red-100 text-red-700
                                @else bg-yellow-100 text-yellow-700 @endif">
                                {{ $lowongan->status ?? '-' }}
                            </span>
                        </span>
                    </div>

                    @if($lowongan->gaji_min || $lowongan->gaji_max)
                        <div class="detail-item md:col-span-2">
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

                    <div class="detail-item md:col-span-2">
                        <span class="detail-label"><i class="fas fa-align-left"></i>Deskripsi Pekerjaan</span>
                        <p class="detail-value whitespace-pre-line">{{ $lowongan->deskripsi ?? 'Tidak ada deskripsi.' }}</p>
                    </div>

                    <div class="detail-item md:col-span-2">
                        <span class="detail-label"><i class="fas fa-list-alt"></i>Persyaratan</span>
                        <p class="detail-value whitespace-pre-line">{{ $lowongan->persyaratan ?? 'Tidak ada persyaratan.' }}</p>
                    </div>

                    <div class="detail-item md:col-span-2">
                        <span class="detail-label"><i class="fas fa-hand-holding"></i>Tanggung Jawab</span>
                        <p class="detail-value whitespace-pre-line">{{ $lowongan->tanggung_jawab ?? 'Tidak ada tanggung jawab.' }}</p>
                    </div>

                    <div class="detail-item md:col-span-2">
                        <span class="detail-label"><i class="fas fa-calendar-plus"></i>Lowongan Dibuat</span>
                        <span class="detail-value">{{ $lowongan->created_at ? $lowongan->created_at->isoFormat('D MMMM YYYY, HH:mm') : '-' }}</span>
                    </div>

                    <div class="detail-item md:col-span-2">
                        <span class="detail-label"><i class="fas fa-history"></i>Terakhir Diperbarui</span>
                        <span class="detail-value">{{ $lowongan->updated_at ? $lowongan->updated_at->diffForHumans() : '-' }}</span>
                    </div>

                </div>

                <div class="mt-8 flex justify-end space-x-3">
                    {{-- Tautan ke form edit lowongan --}}
                    <a href="{{ route('perusahaan.lowongan.edit', $lowongan->id) }}" class="action-button edit-button inline-flex items-center text-white shadow-lg">
                        <i class="fas fa-pencil-alt mr-2"></i>Edit Lowongan
                    </a>
                    {{-- Form untuk menghapus lowongan --}}
                    <form action="{{ route('perusahaan.lowongan.destroy', $lowongan->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus lowongan ini?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="action-button bg-red-600 hover:bg-red-700 text-white shadow-lg">
                            <i class="fas fa-trash-alt mr-2"></i>Hapus Lowongan
                        </button>
                    </form>
                </div>
            @else
                <div class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-4" role="alert">
                    <p class="font-bold">Data Lowongan Tidak Ditemukan</p>
                    <p>Tidak dapat menampilkan detail karena data lowongan tidak valid atau tidak ditemukan.</p>
                </div>
            @endif
        </div>
    </main>

    @include('perusahaan.template.footer')
</body>
</html>
