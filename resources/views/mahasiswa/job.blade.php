<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Job Listing Dashboard - Admin</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-blue-50 text-gray-900">

  <div class="max-w-7xl mx-auto px-6 py-12 mt-16"> {{-- Added mt-16 for fixed navbar --}}

        <h1 class="text-2xl md:text-3xl font-extrabold leading-tight mb-4 text-blue-900">
          Rekomendasi Lowongan Magang
        </h1>
        
    @if(isset($companies) && $companies->count() > 0)
  <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
    @foreach($companies as $company)
      <a href="{{ $company->website ? $company->website : '#' }}" target="_blank" rel="noopener noreferrer" class="block no-underline text-inherit">
        <div class="bg-white rounded-lg shadow-md p-4 flex flex-col hover:bg-blue-50 transition cursor-pointer h-full">
          <div class="mb-3 flex justify-center items-center h-20">
            @if($company->logo_path)
              @if(Str::startsWith($company->logo_path, ['http://', 'https://']))
                <img src="{{ $company->logo_path }}" alt="{{ $company->nama_perusahaan }} logo" class="h-16 max-h-full object-contain" />
              @else
                <img src="{{ asset('storage/' . $company->logo_path) }}" alt="{{ $company->nama_perusahaan }} logo" class="h-16 max-h-full object-contain" />
              @endif
            @else
              {{-- Placeholder Icon --}}
              <div class="w-16 h-16 bg-gray-200 flex items-center justify-center rounded">
                <svg class="w-8 h-8 text-gray-400" fill="currentColor" viewBox="0 0 24 24"><path d="M19 5H5c-1.103 0-2 .897-2 2v10c0 1.103.897 2 2 2h14c1.103 0 2-.897 2-2V7c0-1.103-.897-2-2-2zM5 17V7h14l.002 10H5z"/><path d="M10 9h4v6h-4z"/></svg>
              </div>
            @endif
          </div>
          <h2 class="font-semibold mb-1 text-center truncate" title="{{ $company->nama_perusahaan }}">{{ $company->nama_perusahaan }}</h2>
          <p class="text-xs text-gray-500 text-center mb-2">{{ $company->kota ?? 'N/A' }}</p>

          <div class="mt-auto flex justify-between items-center pt-3">
            <span class="text-blue-900 border border-blue-900 px-3 py-1 rounded text-xs hover:bg-blue-900 hover:text-white transition">
              Kunjungi Website
            </span>
          </div>
        </div>
      </a>
    @endforeach
  </div>
@else
  <p class="text-gray-600">Tidak ada data perusahaan yang ditemukan.</p>
@endif
  </div>
</body>
</html>