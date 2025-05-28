<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Job Listing Dashboard - Admin</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-blue-50 text-gray-900">
  {{-- This should be the correct include for your admin navbar --}}
  {{-- Assuming it's located at resources/views/admin/template/navbar.blade.php --}}
  @include('admin.template.navbar') 

  <div class="max-w-7xl mx-auto px-6 py-12 mt-16"> {{-- Added mt-16 for fixed navbar --}}

    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-8 mb-12">
      <div class="md:w-1/2">
        <h1 class="text-2xl md:text-3xl font-extrabold leading-tight mb-4 text-blue-900">
          List Perusahaan Penerima Magang
        </h1>

        {{-- This form would ideally submit to a controller method that filters companies --}}
        <form method="GET" action="{{ route('admin.perusahaan.index') }}"> {{-- Or your specific route for this page --}}
          <div class="flex flex-wrap gap-3 items-center">
            <select name="regional" class="border border-blue-500 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" aria-label="Regional">
              <option value="">Semua Regional</option>
              <option value="jakarta">Jakarta</option>
              <option value="bandung">Bandung</option>
              <option value="surabaya">Surabaya</option>
              {{-- Add more regions as needed --}}
            </select>
            <input
              name="sektor"
              type="text"
              placeholder="Sektor bidang"
              class="border border-gray-300 rounded-md px-3 py-2 text-sm w-64 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500"
              aria-label="Job sector" />
            <button
              type="submit"
              class="bg-blue-900 text-white px-5 py-2 rounded-md text-sm font-semibold hover:bg-blue-800 transition">
              Tampilkan
            </button>
          </div>
        </form>
      </div>
    </div>

    @if(isset($companies) && $companies->count() > 0)
      <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
        @foreach($companies as $company)
          <a href="{{ $company->website ? $company->website : '#' }}" target="_blank" rel="noopener noreferrer" class="block no-underline text-inherit">
            <div class="bg-white rounded-lg shadow-md p-4 flex flex-col hover:bg-blue-50 transition cursor-pointer h-full">
              <div class="mb-3 flex justify-center items-center h-20">
                @if($company->logo_path)
                  <img src="{{ asset('storage/' . $company->logo_path) }}" alt="{{ $company->nama_perusahaan }} logo" class="h-16 max-h-full object-contain" />
                @else
                  {{-- Placeholder Icon --}}
                  <div class="w-16 h-16 bg-gray-200 flex items-center justify-center rounded">
                    <svg class="w-8 h-8 text-gray-400" fill="currentColor" viewBox="0 0 24 24"><path d="M19 5H5c-1.103 0-2 .897-2 2v10c0 1.103.897 2 2 2h14c1.103 0 2-.897 2-2V7c0-1.103-.897-2-2-2zM5 17V7h14l.002 10H5z"/><path d="M10 9h4v6h-4z"/></svg>
                  </div>
                @endif
              </div>
              <h2 class="font-semibold mb-1 text-center truncate" title="{{ $company->nama_perusahaan }}">{{ $company->nama_perusahaan }}</h2>
              {{-- Example: Displaying company city --}}
              <p class="text-xs text-gray-500 text-center mb-2">{{ $company->kota ?? 'N/A' }}</p>
              
              {{-- Placeholder for rating if you add it later --}}
              {{-- <div class="flex items-center space-x-1 mb-3 justify-center">
                <span class="text-xs font-bold bg-blue-100 text-blue-700 px-2 rounded">N/A</span>
                <div class="flex text-yellow-400 text-sm">☆☆☆☆☆</div>
              </div> --}}
              
              <div class="mt-auto flex justify-between items-center pt-3">
                <span class="text-blue-900 border border-blue-900 px-3 py-1 rounded text-xs hover:bg-blue-900 hover:text-white transition">
                  Kunjungi Website
                </span>
                {{-- <button class="flex items-center space-x-1 text-gray-600 text-xs border border-gray-300 rounded px-2 py-1 hover:bg-gray-100 transition">
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5l14 14M19 5l-14 14" />
                  </svg>
                  <span>Simpan</span>
                </button> --}}
              </div>
            </div>
          </a>
        @endforeach
      </div>
    @else
      <p class="text-gray-600">Tidak ada data perusahaan yang ditemukan.</p>
    @endif
  </div>

  {{-- Assuming footer is in admin/template/footer.blade.php --}}
  @include('admin.template.footer') 
</body>
</html>