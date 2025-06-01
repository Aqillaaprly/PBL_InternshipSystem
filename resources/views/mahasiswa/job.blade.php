<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Job Listing Dashboard</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-blue-50 text-gray-900">
  <!-- Header/Navbar -->

  @include('mahasiswa.template.navbar')
  <div class="max-w-7xl mx-auto px-6 py-12">

    <!-- Header Section -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-8 mb-12">

      <!-- Text Content -->
      <div class="md:w-1/2">
        <h1 class="text-2xl md:text-3xl font-extrabold leading-tight mb-4 text-blue-900">
          List Perusahaan Penerima Magang
        </h1>

        <!-- Filters
        <form class="flex flex-wrap gap-3 items-center">
          <select class="border border-blue-500 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" aria-label="Regional">
            <option value="">Regional</option>
            <option value="jakarta">Jakarta</option>
            <option value="bandung">Bandung</option>
            <option value="surabaya">Surabaya</option>
          </select>
          <input
            type="text"
            placeholder="Sektor bidang (Leave blank for all jobs)"
            class="border border-gray-300 rounded-md px-3 py-2 text-sm w-64 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500"
            aria-label="Job sector" />
          <button
            type="submit"
            class="bg-blue-900 text-white px-5 py-2 rounded-md text-sm font-semibold hover:bg-blue-800 transition">
            Show Jobs
          </button>
        </form> -->
      </div>

    </div>

    <!-- Job Cards Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-6">

      <!-- Card 1: Payfazz -->
      <a href="https://fazz.com/about/" class="block no-underline text-inherit">
        <div class="bg-white rounded-lg shadow-md p-4 flex flex-col hover:bg-blue-50 transition cursor-pointer">
          <div class="mb-3">
            <img src="https://logovectorseek.com/wp-content/uploads/2020/10/payfazz-logo-vector.png" alt="Payfazz logo" class="h-8 object-contain" />
          </div>
          <h2 class="font-semibold mb-1">Payfazz</h2>
          <div class="flex items-center space-x-1 mb-3">
            <span class="text-xs font-bold bg-blue-100 text-blue-700 px-2 rounded">4.1</span>
            <div class="flex text-yellow-400 text-sm">★★★★☆</div>
          </div>
          <div class="mt-auto flex justify-between items-center">
            <button class="text-blue-900 border border-blue-900 px-3 py-1 rounded text-xs hover:bg-blue-900 hover:text-white transition">Apply now</button>
            <button class="flex items-center space-x-1 text-gray-600 text-xs border border-gray-300 rounded px-2 py-1 hover:bg-gray-100 transition">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5l14 14M19 5l-14 14" />
              </svg>
              <span>Save</span>
            </button>
          </div>
        </div>
      </a>

      <!-- Card 2: Astra -->
      <a href="https://www.astra-otoparts.com/about" class="block no-underline text-inherit">
        <div class="bg-white rounded-lg shadow-md p-4 flex flex-col hover:bg-blue-50 transition cursor-pointer">
          <div class="mb-3">
            <img src="https://images.seeklogo.com/logo-png/31/1/astra-international-logo-png_seeklogo-319240.png" alt="Astra logo" class="h-8 object-contain" />
          </div>
          <h2 class="font-semibold mb-1">Astra</h2>
          <div class="flex items-center space-x-1 mb-3">
            <span class="text-xs font-bold bg-blue-100 text-blue-700 px-2 rounded">4.4</span>
            <div class="flex text-yellow-400 text-sm">★★★★☆</div>
          </div>
          <div class="mt-auto flex justify-between items-center">
            <button class="text-blue-900 border border-blue-900 px-3 py-1 rounded text-xs hover:bg-blue-900 hover:text-white transition">Apply now</button>
            <button class="flex items-center space-x-1 text-gray-600 text-xs border border-gray-300 rounded px-2 py-1 hover:bg-gray-100 transition">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5l14 14M19 5l-14 14" />
              </svg>
              <span>Save</span>
            </button>
          </div>
        </div>
      </a>

      <!-- Card 3: Mayora -->
      <a href="https://www.mayoraindah.co.id/content/Riwayat-Singkat-Perusahaan-33" class="block no-underline text-inherit">
        <div class="bg-white rounded-lg shadow-md p-4 flex flex-col hover:bg-blue-50 transition cursor-pointer">
          <div class="mb-3">
            <img src="https://images.seeklogo.com/logo-png/20/1/mayora-logo-png_seeklogo-203730.png" alt="Mayora logo" class="h-8 object-contain" />
          </div>
          <h2 class="font-semibold mb-1">Mayora</h2>
          <div class="flex items-center space-x-1 mb-3">
            <span class="text-xs font-bold bg-blue-100 text-blue-700 px-2 rounded">4.9</span>
            <div class="flex text-yellow-400 text-sm">★★★★★</div>
          </div>
          <div class="mt-auto flex justify-between items-center">
            <button class="text-blue-900 border border-blue-900 px-3 py-1 rounded text-xs hover:bg-blue-900 hover:text-white transition">Apply now</button>
            <button class="flex items-center space-x-1 text-gray-600 text-xs border border-gray-300 rounded px-2 py-1 hover:bg-gray-100 transition">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5l14 14M19 5l-14 14" />
              </svg>
              <span>Save</span>
            </button>
          </div>
        </div>
      </a>

      <!-- Card 4: Bank Mandiri -->
      <a href="https://www.bankmandiri.co.id/profil-perusahaan" class="block no-underline text-inherit">
        <div class="bg-white rounded-lg shadow-md p-4 flex flex-col hover:bg-blue-50 transition cursor-pointer">
          <div class="mb-3">
            <img src="https://bankmandiri.co.id/favicon.ico" alt="Bank Mandiri logo" class="h-8 object-contain" />
          </div>
          <h2 class="font-semibold mb-1">Bank Mandiri</h2>
          <div class="flex items-center space-x-1 mb-3">
            <span class="text-xs font-bold bg-blue-100 text-blue-700 px-2 rounded">5.0</span>
            <div class="flex text-yellow-400 text-sm">★★★★★</div>
          </div>
          <div class="mt-auto flex justify-between items-center">
            <button class="text-blue-900 border border-blue-900 px-3 py-1 rounded text-xs hover:bg-blue-900 hover:text-white transition">Apply now</button>
            <button class="flex items-center space-x-1 text-gray-600 text-xs border border-gray-300 rounded px-2 py-1 hover:bg-gray-100 transition">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5l14 14M19 5l-14 14" />
              </svg>
              <span>Save</span>
            </button>
          </div>
        </div>
      </a>

      <!-- Card 5: Indofood -->
      <a href="https://www.indofood.com/company/history" class="block no-underline text-inherit">
        <div class="bg-white rounded-lg shadow-md p-4 flex flex-col hover:bg-blue-50 transition cursor-pointer">
          <div class="mb-3">
            <img src="https://images.seeklogo.com/logo-png/29/1/indofood-logo-png_seeklogo-298884.png" alt="Indofood logo" class="h-8 object-contain" />
          </div>
          <h2 class="font-semibold mb-1">Indofood</h2>
          <div class="flex items-center space-x-1 mb-3">
            <span class="text-xs font-bold bg-blue-100 text-blue-700 px-2 rounded">3.9</span>
            <div class="flex text-yellow-400 text-sm">★★★★☆</div>
          </div>
          <div class="mt-auto flex justify-between items-center">
            <button class="text-blue-900 border border-blue-900 px-3 py-1 rounded text-xs hover:bg-blue-900 hover:text-white transition">Apply now</button>
            <button class="flex items-center space-x-1 text-gray-600 text-xs border border-gray-300 rounded px-2 py-1 hover:bg-gray-100 transition">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5l14 14M19 5l-14 14" />
              </svg>
              <span>Save</span>
            </button>
          </div>
        </div>
      </a>

      <!-- Card 6: Paragon -->
      <a href="https://www.paragon-innovation.com/about-us" class="block no-underline text-inherit">
        <div class="bg-white rounded-lg shadow-md p-4 flex flex-col hover:bg-blue-50 transition cursor-pointer">
          <div class="mb-3">
            <img src="https://images.seeklogo.com/logo-png/24/1/paragon-logo-png_seeklogo-247277.png" alt="Paragon Technology logo" class="h-8 object-contain" />
          </div>
          <h2 class="font-semibold mb-1">Paragon Technology</h2>
          <div class="flex items-center space-x-1 mb-3">
            <span class="text-xs font-bold bg-blue-100 text-blue-700 px-2 rounded">4.0</span>
            <div class="flex text-yellow-400 text-sm">★★★★☆</div>
          </div>
          <div class="mt-auto flex justify-between items-center">
            <button class="text-blue-900 border border-blue-900 px-3 py-1 rounded text-xs hover:bg-blue-900 hover:text-white transition">Apply now</button>
            <button class="flex items-center space-x-1 text-gray-600 text-xs border border-gray-300 rounded px-2 py-1 hover:bg-gray-100 transition">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5l14 14M19 5l-14 14" />
              </svg>
              <span>Save</span>
            </button>
          </div>
        </div>
      </a>


      <!-- Card 7: Kawan Lama -->
      <a href="https://www.kawanlama.com/about-us" class="block no-underline text-inherit">
        <div class="bg-white rounded-lg shadow-md p-4 flex flex-col hover:bg-blue-50 transition cursor-pointer">
          <div class="mb-3">
            <img src="https://kawanlama.com/favicon.ico" alt="Kawan Lama Sejahtera logo" class="h-8 object-contain" />
          </div>
          <h2 class="font-semibold mb-1">Kawan Lama Sejahtera</h2>
          <div class="flex items-center space-x-1 mb-3">
            <span class="text-xs font-bold bg-blue-100 text-blue-700 px-2 rounded">4.3</span>
            <div class="flex text-yellow-400 text-sm">★★★★☆</div>
          </div>
          <div class="mt-auto flex justify-between items-center">
            <button class="text-blue-900 border border-blue-900 px-3 py-1 rounded text-xs hover:bg-blue-900 hover:text-white transition">Apply now</button>
            <button class="flex items-center space-x-1 text-gray-600 text-xs border border-gray-300 rounded px-2 py-1 hover:bg-gray-100 transition">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5l14 14M19 5l-14 14" />
              </svg>
              <span>Save</span>
            </button>
          </div>
        </div>
      </a>

      <!-- Card 8: Indomaret -->
      <a href="https://www.indomaret.co.id/home/index/sejarah-visi" class="block no-underline text-inherit">
        <div class="bg-white rounded-lg shadow-md p-4 flex flex-col hover:bg-blue-50 transition cursor-pointer">
          <div class="mb-3">
            <img src="https://images.seeklogo.com/logo-png/50/1/indomaret-logo-png_seeklogo-504056.png" alt="Indomaret logo" class="h-8 object-contain" />
          </div>
          <h2 class="font-semibold mb-1">Indomaret</h2>
          <div class="flex items-center space-x-1 mb-3">
            <span class="text-xs font-bold bg-blue-100 text-blue-700 px-2 rounded">3.2</span>
            <div class="flex text-yellow-400 text-sm">★★★☆☆</div>
          </div>
          <div class="mt-auto flex justify-between items-center">
            <button class="text-blue-900 border border-blue-900 px-3 py-1 rounded text-xs hover:bg-blue-900 hover:text-white transition">Apply now</button>
            <button class="flex items-center space-x-1 text-gray-600 text-xs border border-gray-300 rounded px-2 py-1 hover:bg-gray-100 transition">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5l14 14M19 5l-14 14" />
              </svg>
              <span>Save</span>
            </button>
          </div>
        </div>
      </a>

    </div>

  </div>

</body>

</html>
