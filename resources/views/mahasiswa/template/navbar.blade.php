@include('mahasiswa.inc.lang')

<!-- Spacer to prevent navbar overlap when banner appears -->
<div id="google-translate-banner-spacer"></div>

<header class="bg-white shadow-md fixed top-0 left-0 right-0 z-30">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16 items-center">

            <!-- Left: Logo & Hamburger -->
            <div class="flex items-center">
                <button id="toggleSidebar" class="md:hidden text-gray-700 hover:text-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 mr-3" aria-label="Toggle sidebar">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="3" y1="6" x2="21" y2="6" />
                        <line x1="3" y1="12" x2="21" y2="12" />
                        <line x1="3" y1="18" x2="21" y2="18" />
                    </svg>
                </button>

                <a href="dashboard.php" class="text-blue-700 font-extrabold text-xl tracking-tight hover:text-blue-800">
                    SIMMAGANG
                </a>
            </div>

            <!-- Center: Navigation Menu -->
            <nav class="hidden md:flex space-x-6 font-medium text-gray-700">
<<<<<<<< HEAD:resources/views/mahasiswa/template/navbar.blade.php
                <a href="{{ route('mahasiswa.perusahaan') }}" class="hover:text-blue-600 border-b-2 border-transparent hover:border-blue-600 transition {{ request()->routeIs('mahasiswa.perusahaan') ? 'border-blue-600 text-blue-600' : '' }}">Perusahaan</a>
                <a href="{{ route('mahasiswa.absensi') }}" class="hover:text-blue-600 border-b-2 border-transparent hover:border-blue-600 transition {{ request()->routeIs('mahasiswa.absensi') ? 'border-blue-600 text-blue-600' : '' }}">Absensi</a>
                <class="hover:text-blue-600 border-b-2 border-transparent hover:border-blue-600 transition ">Report</a>
========
                <a href="perusahaan.php" class="hover:text-blue-600 border-b-2 border-transparent hover:border-blue-600 transition"><?= $lang['perusahaan']; ?></a>
                <a href="absensi.php" class="hover:text-blue-600 border-b-2 border-transparent hover:border-blue-600 transition"><?= $lang['absensi']; ?></a>
>>>>>>>> origin/Branch-fix-frontend-Krisna:resources/views/mahasiswa/template/navbar_mahasiswa.php
            </nav>

            <!-- Right: Google Translate + Profile -->
            <div class="flex items-center space-x-4">
                <!-- Google Translate with Globe Icon -->
                <div class="flex items-center space-x-2 mr-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M12 4v1m0 14v1m8-8h1M3 12H2m16.95-4.95l.707.707M5.343 5.343l-.707.707M16.95 16.95l.707-.707M5.343 18.657l-.707-.707M12 6a6 6 0 100 12a6 6 0 000-12z"/>
                    </svg>
                    <div id="google_translate_element" class="ml-1"></div>
                </div>

                <!-- Profile Dropdown -->
                <div class="relative">
                    <button id="profileBtn" class="flex items-center space-x-2 focus:outline-none focus:ring-2 focus:ring-blue-500 rounded-full" aria-haspopup="true" aria-expanded="false">
                        <img src="https://i.pravatar.cc/40" alt="User avatar" class="w-10 h-10 rounded-full border border-gray-300" />
                        <span class="hidden sm:block font-medium text-gray-700">Mahasiswa TI</span>
                    </button>

                    <div id="profileDropdown" class="origin-top-right absolute right-0 mt-2 w-44 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 hidden">
                        <a href="mahasiswaProfile.php" class="block px-4 py-2 text-gray-700 hover:bg-gray-100 rounded-t-md"></a>
                        <a href="#" class="block px-4 py-2 text-gray-700 hover:bg-gray-100"></a>
                        <div class="border-t border-gray-200"></div>
                        <a href="#" class="block px-4 py-2 text-red-600 hover:bg-red-100 rounded-b-md"></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>

<!-- Custom Styles for Google Translate -->
<style>
    .goog-logo-link, .goog-te-gadget span {
        display: none !important;
    }

    .goog-te-gadget {
        color: transparent !important;
        font-size: 0 !important;
    }

    #google_translate_element select {
        background-color: white;
        color: #4B5563;
        border: 1px solid #D1D5DB;
        border-radius: 4px;
        font-size: 0.875rem;
        padding: 0.25rem 0.5rem;
    }

    .goog-tooltip,
    .goog-tooltip:hover,
    .goog-te-balloon-frame {
        display: none !important;
    }

    .goog-text-highlight {
        background: none !important;
        box-shadow: none !important;
    }

    iframe.goog-te-banner-frame {
        position: absolute !important;
        top: 0 !important;
        z-index: 9999 !important;
        width: 100% !important;
        height: 40px !important;
    }

    #google-translate-banner-spacer {
        height: 0px;
        transition: height 0.3s ease;
    }
</style>

<script type="text/javascript">
    function googleTranslateElementInit() {
        new google.translate.TranslateElement({
            pageLanguage: 'id',
            includedLanguages: 'id,en',
            layout: google.translate.TranslateElement.InlineLayout.SIMPLE
        }, 'google_translate_element');
    }

    // Handle profile dropdown toggle
    const profileBtn = document.getElementById('profileBtn');
    const profileDropdown = document.getElementById('profileDropdown');

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

    // Observe for Google Translate banner and adjust layout
    function repositionTranslateBanner() {
        const iframe = document.querySelector("iframe.goog-te-banner-frame");
        const spacer = document.getElementById("google-translate-banner-spacer");

        if (iframe && spacer) {
            spacer.style.height = "40px";
        }
    }

    const observer = new MutationObserver(() => {
        repositionTranslateBanner();
    });

    observer.observe(document.body, {
        childList: true,
        subtree: true
    });
</script>

<!-- Google Translate API -->
<script type="text/javascript" src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>


<!-- Google Translate Element Positioned at Bottom -->
<div id="google_translate_element" style="position: fixed !important; bottom: 0 !important; left: 0 !important; z-index: 9999 !important; background: white; padding: 5px;"></div>

<script type="text/javascript">
    function googleTranslateElementInit() {
        new google.translate.TranslateElement(
            {pageLanguage: 'id', layout: google.translate.TranslateElement.InlineLayout.SIMPLE},
            'google_translate_element'
        );
    }
</script>
<script type="text/javascript" src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>
