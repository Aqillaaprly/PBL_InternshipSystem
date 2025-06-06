<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Job Listing Dashboard</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-blue-50 text-gray-900">

  <div class="max-w-7xl mx-auto px-6 py-12">

    <!-- Header Section -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-8 mb-12">
      
      <!-- Text Content -->
      <div class="md:w-1/2">
        <h1 class="text-2xl md:text-3xl font-extrabold leading-tight mb-4 text-blue-900">
          Lorem ipsum dolor sit amet consectetur. Augue aliquam quis sodales tempor donec. Nulla malesuada dui fermentum rhoncus feugiat ac aliquam.
        </h1>
        <p class="text-sm text-blue-800 font-semibold mb-6">
          Lorem ipsum dolor sit amet consectetur.
        </p>

        <!-- Filters -->
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
            aria-label="Job sector"
          />
          <button
            type="submit"
            class="bg-blue-900 text-white px-5 py-2 rounded-md text-sm font-semibold hover:bg-blue-800 transition"
          >
            Show Jobs
          </button>
        </form>
      </div>

      <!-- Placeholder image -->
      <div class="md:w-1/3 h-36 bg-gray-300 rounded-md"></div>
    </div>

    <!-- Job Cards Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-6">

      <!-- Card 1: Payfazz -->
      <div class="bg-white rounded-lg shadow-md p-4 flex flex-col">
        <div class="mb-3">
          <img src="https://logovectorseek.com/wp-content/uploads/2021/06/PAYFAZZ-Logo-Vector.svg" alt="Payfazz logo" class="h-8 object-contain" />
        </div>
        <h2 class="font-semibold mb-1">Payfazz</h2>
        <div class="flex items-center space-x-1 mb-3">
          <span class="text-xs font-bold bg-blue-100 text-blue-700 px-2 rounded">4.1</span>
          <div class="flex text-yellow-400 text-sm">★★★★☆</div>
        </div>
        <div class="mt-auto flex justify-between items-center">
          <button class="text-blue-900 border border-blue-900 px-3 py-1 rounded text-xs hover:bg-blue-900 hover:text-white transition">Apply now</button>
          <button class="flex items-center space-x-1 text-gray-600 text-xs border border-gray-300 rounded px-2 py-1 hover:bg-gray-100 transition">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5l14 14M19 5l-14 14" /></svg>
            <span>Save</span>
          </button>
        </div>
      </div>

      <!-- Card 2: Astra -->
      <div class="bg-white rounded-lg shadow-md p-4 flex flex-col">
        <div class="mb-3">
          <img src="https://seeklogo.com/images/A/astra-logo-7B7B7C8E4D-seeklogo.com.png" alt="Astra logo" class="h-8 object-contain" />
        </div>
        <h2 class="font-semibold mb-1">Astra</h2>
        <div class="flex items-center space-x-1 mb-3"></div>
        <span class="text-xs font-bold bg-blue-100 text-blue-700 px-2 rounded">4.1</span>
          <div class="flex text-yellow-400 text-sm">★★★★☆</div>
       
          <div class="mt-auto flex justify-between items-center">
          <button class="text-blue-900 border border-blue-900 px-3 py-1 rounded text-xs hover:bg-blue-900 hover:text-white transition">Apply now</button>
          <button class="flex items-center space-x-1 text-gray-600 text-xs border border-gray-300 rounded px-2 py-1 hover:bg-gray-100 transition">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5l14 14M19 5l-14 14" /></svg>
            <span>Save</span>
          </button>
        </div>
      </div>

      <!-- Card 3: Mayora -->
      <div class="bg-white rounded-lg shadow-md p-4 flex flex-col">
        <div class="mb-3">
          <img src="https://mayora.com/favicon.ico" alt="Mayora logo" class="h-8 object-contain" />
        </div>
        <h2 class="font-semibold mb-1">Mayora</h2>
        <div class="flex items-center space-x-1 mb-3">
          <span class="text-xs font-bold bg-blue-100 text-blue-700 px-2 rounded">4.2</span>
          <div class="flex text-yellow-400 text-sm">★★★★☆</div>
        </div>
        <div class="mt-auto flex justify-between items-center">
          <button class="text-blue-900 border border-blue-900 px-3 py-1 rounded text-xs hover:bg-blue-900 hover:text-white transition">Apply now</button>
          <button class="flex items-center space-x-1 text-gray-600 text-xs border border-gray-300 rounded px-2 py-1 hover:bg-gray-100 transition">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5l14 14M19 5l-14 14" /></svg>
            <span>Save</span>
          </button>
        </div>
      </div>

      <!-- Card 4: Bank Mandiri -->
      <div class="bg-white rounded-lg shadow-md p-4 flex flex-col">
        <div class="mb-3">
          <img src="https://bankmandiri.co.id/favicon.ico" alt="Bank Mandiri logo" class="h-8 object-contain" />
        </div>
        <h2 class="font-semibold mb-1">Bank Mandiri</h2>
        <div class="mt-auto flex justify-between items-center">
          <button class="text-blue-900 border border-blue-900 px-3 py-1 rounded text-xs hover:bg-blue-900 hover:text-white transition">Apply now</button>
          <button class="flex items-center space-x-1 text-gray-600 text-xs border border-gray-300 rounded px-2 py-1 hover:bg-gray-100 transition">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5l14 14M19 5l-14 14" /></svg>
            <span>Save</span>
          </button>
        </div>
      </div>

      <!-- Card 5: Indofood -->
      <div class="bg-white rounded-lg shadow-md p-4 flex flex-col">
        <div class="mb-3">
          <img src="https://seeklogo.com/images/I/indofood-logo-298884-seeklogo.com.png" alt="Indofood logo" class="h-8 object-contain" />
        </div>
        <h2 class="font-semibold mb-1">Indofood</h2>
        <div class="mt-auto flex justify-between items-center">
          <button class="text-blue-900 border border-blue-900 px-3 py-1 rounded text-xs hover:bg-blue-900 hover:text-white transition">Apply now</button>
          <button class="flex items-center space-x-1 text-gray-600 text-xs border border-gray-300 rounded px-2 py-1 hover:bg-gray-100 transition">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5l14 14M19 5l-14 14" /></svg>
            <span>Save</span>
          </button>
        </div>
      </div>

      <!-- Card 6: Paragon -->
      <div class="bg-white rounded-lg shadow-md p-4 flex flex-col">
        <div class="mb-3">
          <img src="https://brandfetch.com/paragon-innovation.com/logo.svg" alt="Paragon Technology logo" class="h-8 object-contain" />
        </div>
        <h2 class="font-semibold mb-1">Paragon Technology And Innovation</h2>
        <div class="flex items-center space-x-1 mb-3">
          <span class="text-xs font-bold bg-blue-100 text-blue-700 px-2 rounded">4.7</span>
          <div class="flex text-yellow-400 text-sm">★★★★★</div>
        </div>
        <div class="mt-auto flex justify-between items-center">
          <button class="text-blue-900 border border-blue-900 px-3 py-1 rounded text-xs hover:bg-blue-900 hover:text-white transition">Apply now</button>
          <button class="flex items-center space-x-1 text-gray-600 text-xs border border-gray-300 rounded px-2 py-1 hover:bg-gray-100 transition">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5l14 14M19 5l-14 14" /></svg>
            <span>Save</span>
          </button>
        </div>
      </div>

      <!-- Card 7: Kawan Lama -->
      <div class="bg-white rounded-lg shadow-md p-4 flex flex-col">
        <div class="mb-3">
          <img src="https://kawanlama.com/favicon.ico" alt="Kawan Lama Sejahtera logo" class="h-8 object-contain" />
        </div>
        <h2 class="font-semibold mb-1">Kawan Lama Sejahtera</h2>
        <div class="mt-auto flex justify-between items-center">
          <button class="text-blue-900 border border-blue-900 px-3 py-1 rounded text-xs hover:bg-blue-900 hover:text-white transition">Apply now</button>
          <button class="flex items-center space-x-1 text-gray-600 text-xs border border-gray-300 rounded px-2 py-1 hover:bg-gray-100 transition">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5l14 14M19 5l-14 14" /></svg>
            <span>Save</span>
          </button>
        </div>
      </div>

      <!-- Card 8: Indomaret -->
      <div class="bg-white rounded-lg shadow-md p-4 flex flex-col">
        <div class="mb-3">
          <img src="https://seeklogo.com/images/I/indomaret-logo-339890-seeklogo.com.png" alt="Indomaret logo" class="h-8 object-contain" />
        </div>
        <h2 class="font-semibold mb-1">Indomaret</h2>
        <div class="mt-auto flex justify-between items-center">
          <button class="text-blue-900 border border-blue-900 px-3 py-1 rounded text-xs hover:bg-blue-900 hover:text-white transition">Apply now</button>
          <button class="flex items-center space-x-1 text-gray-600 text-xs border border-gray-300 rounded px-2 py-1 hover:bg-gray-100 transition">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5l14 14M19 5l-14 14" /></svg>
            <span>Save</span>
          </button>
        </div>
      </div>

    </div>
  </div>

</body>
</html>
