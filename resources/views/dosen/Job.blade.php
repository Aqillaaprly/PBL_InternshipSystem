<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Lowongan Tersedia </title>
  <script src="https://cdn.tailwindcss.com"></script>
  <style>
    .kualifikasi-list ul {
        list-style-type: disc;
        margin-left: 1.5rem;
        padding-left: 0;
    }
    .kualifikasi-list li {
        margin-bottom: 0.25rem; 
    }
  </style>
</head>

<body class="bg-blue-50 text-gray-900">

  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">

    <h1 class="text-3xl md:text-4xl font-extrabold leading-tight mb-8 text-blue-900 text-center">
      Daftar Perusahaan Magang
    </h1>
        
    {{-- Pengecekan diubah agar sesuai dengan Collection --}}
    @if(isset($companies) && $companies instanceof \Illuminate\Support\Collection && $companies->count() > 0)
      <div class="space-y-8">
        @foreach($companies as $company)
          <div class="bg-white rounded-lg shadow-lg overflow-hidden">
            <div class="p-6">
                <div class="flex items-center mb-4">
                    
                    <div>
                        <h2 class="text-2xl font-bold text-blue-800" title="{{ $company->nama_perusahaan }}">{{ $company->nama_perusahaan }}</h2>
                        <p class="text-sm text-gray-600">{{ $company->kota ?? 'N/A' }}</p>
                        @if($company->website)
                        <a href="{{ $company->website }}" target="_blank" rel="noopener noreferrer" class="text-xs text-blue-600 hover:underline">
                            Kunjungi Website
                        </a>
                        @endif
                    </div>
                </div>

                @if($company->lowongan && $company->lowongan->count() > 0)
                  <div class="space-y-6 mt-4">
                    @foreach($company->lowongan as $lowongan)
                      <div class="border border-gray-200 rounded-md p-4 hover:shadow-md transition-shadow">
                        <h3 class="text-lg font-semibold text-blue-700">{{ $lowongan->judul }}</h3>
                        <p class="text-xs text-gray-500 mb-1">Lokasi: {{ $lowongan->lokasi }} | Tipe: {{ $lowongan->tipe }}</p>
                        @if($lowongan->tanggal_tutup)
                        <p class="text-xs text-gray-500 mb-2">Batas Akhir: {{ \Carbon\Carbon::parse($lowongan->tanggal_tutup)->isoFormat('D MMMM YYYY') }}</p>
                        @endif
                        
                        <div class="mt-2 text-sm text-gray-700">
                            <strong class="block mb-1">Deskripsi Singkat:</strong>
                            <p class="text-gray-600 text-xs leading-relaxed line-clamp-3">{{ Str::limit(strip_tags($lowongan->deskripsi), 200) }}</p>
                        </div>

                        <div class="mt-3 text-sm text-gray-700 kualifikasi-list">
                            <strong class="block mb-1">Kriteria/Kualifikasi yang Dicari:</strong>
                            @if($lowongan->kualifikasi)
                                <ul class="text-xs text-gray-600">
                                    @foreach(explode("\n", $lowongan->kualifikasi) as $kriteria)
                                        @if(trim($kriteria) !== '')
                                            <li>{{ trim(str_replace(['-', '*'], '', $kriteria)) }}</li>
                                        @endif
                                    @endforeach
                                </ul>
                            @else
                                <p class="text-xs text-gray-500">Kualifikasi tidak disebutkan.</p>
                            @endif
                        </div>
                      </div>
                    @endforeach
                  </div>
                @else
                  <p class="text-sm text-gray-500 mt-4">Saat ini belum ada lowongan tersedia dari perusahaan ini.</p>
                @endif
            </div>
          </div>
        @endforeach
      </div>
      
    @elseif(isset($companies) && $companies->isEmpty())
      <p class="text-gray-600 text-center">Tidak ada data perusahaan yang ditemukan.</p>
    @else
      <p class="text-gray-600 text-center">Data perusahaan tidak tersedia saat ini.</p>
    @endif
  </div>

</body>
</html>