<!-- navbar.php -->
<header class="bg-white shadow-md fixed top-0 left-0 right-0 z-30">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="flex justify-between h-16 items-center">
      
      <!-- Left: Logo & Hamburger -->
      <div class="flex items-center">
        <!-- Hamburger Button (mobile) -->
        <button id="toggleSidebar" class="md:hidden text-gray-700 hover:text-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 mr-3" aria-label="Toggle sidebar">
          <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round">
            <line x1="3" y1="6" x2="21" y2="6" />
            <line x1="3" y1="12" x2="21" y2="12" />
            <line x1="3" y1="18" x2="21" y2="18" />
          </svg>
        </button>

        <!-- Logo -->
        <a href="index.php" class="text-blue-700 font-extrabold text-xl tracking-tight hover:text-blue-800">
          SIMMAGANG
        </a>
      </div>

      <!-- Center: Navigation Menu (hidden on mobile) -->
      <nav class="hidden md:flex space-x-6 font-medium text-gray-700">
        <a href="mahasiswa.php" class="hover:text-blue-600 border-b-2 border-transparent hover:border-blue-600 transition">Data Mahasiswa</a>
        <a href="pembimbing.php" class="hover:text-blue-600 border-b-2 border-transparent hover:border-blue-600 transition">Pembimbing</a>
        <a href="laporan.php" class="hover:text-blue-600 border-b-2 border-transparent hover:border-blue-600 transition">Laporan</a>
        <a href="pengaturan.php" class="hover:text-blue-600 border-b-2 border-transparent hover:border-blue-600 transition">Pengaturan</a>
      </nav>    

      <!-- Right: Profile dropdown -->
      <div class="relative">
        <button id="profileBtn" class="flex items-center space-x-3 focus:outline-none focus:ring-2 focus:ring-blue-500 rounded-full" aria-haspopup="true" aria-expanded="false">
          <img src="https://i.pravatar.cc/40" alt="User avatar" class="w-10 h-10 rounded-full border border-gray-300" />
          <span class="hidden sm:block font-medium text-gray-700">Admin TI</span>
          <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round">
            <polyline points="6 9 12 15 18 9"></polyline>
              <a class="dropdown-item" href="Profile.php">Profil</a>
          </svg>
        </button>

        <!-- Dropdown menu -->
        <div id="profileDropdown" class="origin-top-right absolute right-0 mt-2 w-44 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 hidden">
          <a href="#" class="block px-4 py-2 text-gray-700 hover:bg-gray-100 rounded-t-md">Profil</a>
          <a href="#" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">Pengaturan</a>
          <div class="border-t border-gray-200"></div>
          <a href="#" class="block px-4 py-2 text-red-600 hover:bg-red-100 rounded-b-md">Keluar</a>
        </div>
      </div>
    </div>
  </div>
</header>
