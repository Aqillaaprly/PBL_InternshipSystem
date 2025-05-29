{{-- resources/views/admin/template/navbar.blade.php --}}
<header class="bg-white shadow-md fixed top-0 left-0 right-0 z-30">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16 items-center">

            <div class="flex items-center">
                <button id="toggleSidebar" class="md:hidden text-gray-700 hover:text-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 mr-3" aria-label="Toggle sidebar">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="3" y1="6" x2="21" y2="6" />
                        <line x1="3" y1="12" x2="21" y2="12" />
                        <line x1="3" y1="18" x2="21" y2="18" />
                    </svg>
                </button>

                <a href="{{ route('admin.dashboard') }}" class="text-blue-700 font-extrabold text-xl tracking-tight hover:text-blue-800">
                    SIMMAGANG
                </a>
            </div>

             <nav class="hidden md:flex space-x-6 font-medium text-gray-700">
                {{-- Menggunakan nama route yang sudah diperbaiki dan konsisten --}}
                <a href="{{ route('admin.datamahasiswa') }}" class="hover:text-blue-600 border-b-2 border-transparent hover:border-blue-600 transition {{ request()->routeIs('admin.datamahasiswa') ? 'border-blue-600 text-blue-600' : '' }}">Data Mahasiswa</a>
                <a href="{{ route('admin.data_pembimbing') }}" class="hover:text-blue-600 border-b-2 border-transparent hover:border-blue-600 transition {{ request()->routeIs('admin.data_pembimbing') ? 'border-blue-600 text-blue-600' : '' }}">Pembimbing</a>
                <a href="{{ route('admin.perusahaan.index') }}" class="hover:text-blue-600 border-b-2 border-transparent hover:border-blue-600 transition {{ request()->routeIs('admin.perusahaan.index') ? 'border-blue-600 text-blue-600' : '' }}">Data Perusahaan</a>
                <a href="{{ route('admin.laporan') }}" class="hover:text-blue-600 border-b-2 border-transparent hover:border-blue-600 transition {{ request()->routeIs('admin.laporan') ? 'border-blue-600 text-blue-600' : '' }}">Laporan</a>
            </nav>

            <div class="flex items-center space-x-4">
                <div class="flex items-center space-x-2 mr-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 4v1m0 14v1m8-8h1M3 12H2m16.95-4.95l.707.707M5.343 5.343l-.707.707M16.95 16.95l.707-.707M5.343 18.657l-.707-.707M12 6a6 6 0 100 12a6 6 0 000-12z"/>
                    </svg>
                    <div id="google_translate_element" class="ml-1"></div>
                </div>

                {{-- Profile admin --}}
                <div class="relative">
                    <button id="profileBtn" class="flex items-center space-x-2 focus:outline-none focus:ring-2 focus:ring-blue-500 rounded-full" aria-haspopup="true" aria-expanded="false">
                        <img src="https://i.pravatar.cc/40" alt="User avatar" class="w-10 h-10 rounded-full border border-gray-300" />
                        <span class="hidden sm:block font-medium text-gray-700">{{ Auth::user()->username ?? 'Admin' }}</span>
                    </button>
                    <div id="profileDropdown" class="origin-top-right absolute right-0 mt-2 w-44 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 hidden">
                        <a href="{{ route('admin.profile') }}" class="block px-4 py-2 text-gray-700 hover:bg-gray-100 rounded-t-md">Profil</a>
                        {{-- <a href="#" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">Pengaturan</a> --}}
                        <div class="border-t border-gray-200"></div>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            @csrf
                        </form>
                        <a href="{{ route('logout') }}"
                           onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                           class="block px-4 py-2 text-red-600 hover:bg-red-100 rounded-b-md">
                            Keluar
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>
<style>
    /* Hide Google's default logo and branding */
    .goog-logo-link { display: none !important; }
    .goog-te-gadget span { display: none !important; }
    .goog-te-gadget { color: transparent !important; font-size: 0 !important; }
    /* Style the select dropdown */
    #google_translate_element select {
        background-color: white; color: #4B5563;
        border: 1px solid #D1D5DB; border-radius: 4px;
        font-size: 0.875rem; padding: 0.25rem 0.5rem;
    }
    /* Adjust vertical alignment of the widget if needed */
    .goog-te-gadget { vertical-align: middle; }
</style>

<script type="text/javascript">
    function googleTranslateElementInit() {
        new google.translate.TranslateElement({
            pageLanguage: 'id', // Your site's default language
            includedLanguages: 'id,en', // Languages to offer
            layout: google.translate.TranslateElement.InlineLayout.SIMPLE,
            autoDisplay: false // Prevents the banner on mobile
        }, 'google_translate_element');

        // Attempt to hide the Google logo and extra text elements periodically
        // as they might be re-added by the widget dynamically.
        function hideGoogleElements() {
            var googleWidget = document.getElementById('google_translate_element');
            if (googleWidget) {
                var logoLink = googleWidget.querySelector('.goog-logo-link');
                if (logoLink) logoLink.style.display = 'none';
                
                var spans = googleWidget.querySelectorAll('.goog-te-gadget > span');
                spans.forEach(function(span) {
                    if (!span.querySelector('select')) { // Don't hide the span containing the select
                        span.style.display = 'none';
                    }
                });

                // Ensure the select element itself is visible
                var selectElement = googleWidget.querySelector('select.goog-te-combo');
                if (selectElement) {
                    selectElement.style.display = 'inline-block'; // Or 'block' as needed
                    selectElement.style.color = '#4B5563'; // Ensure text color is visible
                    selectElement.style.fontSize = '0.875rem'; // Ensure font size is appropriate
                }
            }
        }
        setInterval(hideGoogleElements, 200); // Check periodically
    }

    // Profile Dropdown
    const profileBtn = document.getElementById('profileBtn');
    const profileDropdown = document.getElementById('profileDropdown');

    if (profileBtn && profileDropdown) {
        profileBtn.addEventListener('click', () => {
            const isHidden = profileDropdown.classList.toggle('hidden');
            profileBtn.setAttribute('aria-expanded', !isHidden);
        });

        document.addEventListener('click', function(e) {
            if (!profileBtn.contains(e.target) && !profileDropdown.contains(e.target)) {
                profileDropdown.classList.add('hidden');
                profileBtn.setAttribute('aria-expanded', false);
            }
        });
    }
</script>
<script type="text/javascript" src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>