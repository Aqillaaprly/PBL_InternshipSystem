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

                <a href="{{ route('perusahaan.dashboard') }}" class="text-blue-700 font-extrabold text-xl tracking-tight hover:text-blue-800">
                    SIMMAGANG
                </a>
            </div>

            <div class="flex items-center space-x-4">
                {{-- Google Translate dengan Ikon Globe --}}
                <div class="flex items-center space-x-1">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9V3m0 9a9 9 0 016.364 2.636M12 12V3m-3.364 9.364A9 9 0 0112 3m0 18v-9m-3.364-6.364A9 9 0 005.636 7.364M12 12h9m-9 0H3"/>
                    </svg>
                    <div id="google_translate_element"></div>

                </div>
                
               <div class="relative">
                    <button id="profileBtn" class="flex items-center space-x-2 focus:outline-none focus:ring-2 focus:ring-blue-500 rounded-full" aria-haspopup="true" aria-expanded="false">
                        @if (Auth::user()->profile_picture && Storage::disk('public')->exists(Auth::user()->profile_picture))
                            <img src="{{ asset('storage/' . Auth::user()->profile_picture) }}" alt="User avatar" class="w-10 h-10 rounded-full border border-gray-300 object-cover" />
                        @else
                             {{-- Fallback ke UI Avatars jika tidak ada foto --}}
                            <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name ?? Auth::user()->username) }}&background=random&color=fff&size=40" alt="User avatar" class="w-10 h-10 rounded-full border border-gray-300 object-cover" />
                        @endif
                        <span class="hidden sm:block font-medium text-gray-700">{{ Auth::user()->username ?? 'Admin' }}</span>
                    </button>
                    <div id="profileDropdown" class="origin-top-right absolute right-0 mt-2 w-44 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 hidden">
                        {{-- <a href="{{ route('perusahaan.profile') }}" class="block px-4 py-2 text-gray-700 hover:bg-gray-100 rounded-t-md">Profil</a> --}}
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
    /* Sembunyikan elemen branding Google Translate dan banner */
    .goog-logo-link,
    .goog-te-gadget span, /* Ini akan menyembunyikan teks "Powered by Google Translate" */
    iframe.goog-te-banner-frame {
        display: none !important;
    }

    .goog-te-gadget {
        color: transparent !important; /* Membuat teks default transparan */
        font-size: 0 !important; /* Membuat font size 0 agar tidak memakan tempat */
        line-height: normal !important;
        display: inline-block; /* Agar widget tidak terlalu lebar */
        vertical-align: middle; /* Menjaga kesejajaran vertikal dengan ikon globe */
    }

    /* Mencegah Google menambahkan margin/padding ke body saat banner muncul */
    body {
        top: 0px !important;
    }

    /* Styling untuk dropdown bahasa dari Google Translate */
    #google_translate_element select.goog-te-combo {
        background-color: white;
        color: #4B5563; /* text-gray-700 */
        border: 1px solid #D1D5DB; /* border-gray-300 */
        border-radius: 0.375rem; /* rounded-md */
        padding: 0.25rem 0.5rem; /* py-1 px-2 */
        font-size: 0.875rem; /* text-sm */
        line-height: 1.25rem; /* leading-tight */
        height: 2rem; /* Sesuaikan tinggi jika perlu, misal h-8 */
        appearance: none;
        -webkit-appearance: none;
        -moz-appearance: none;
        cursor: pointer;
        margin-left: 0.25rem; /* ml-1 */
        min-width: auto; /* Biarkan lebarnya menyesuaikan konten atau atur sesuai kebutuhan */
    }
</style>

<script type="text/javascript">
    function googleTranslateElementInit() {
        new google.translate.TranslateElement({
            pageLanguage: 'id',
            includedLanguages: 'id,en,es,fr,de,ja,ko,ar', // Sesuaikan daftar bahasa
            layout: google.translate.TranslateElement.InlineLayout.SIMPLE,
            autoDisplay: false
        }, 'google_translate_element');

        // Fungsi untuk mencoba menyembunyikan elemen yang tidak diinginkan
        // dan memastikan body tidak bergeser
        function styleGoogleTranslateWidget() {
            var bannerFrame = document.querySelector('iframe.goog-te-banner-frame');
            if (bannerFrame) {
                bannerFrame.style.display = 'none';
            }
            // Paksa body kembali ke posisi atas jika Google Translate mengubahnya
            document.body.style.top = '0px';

            var gtElement = document.getElementById('google_translate_element');
            if (gtElement) {
                var spans = gtElement.querySelectorAll('.goog-te-gadget > span');
                spans.forEach(function(span) {
                    if (!span.querySelector('select')) { // Jangan sembunyikan span yang berisi select
                        span.style.display = 'none';
                    }
                });
                var logoLink = gtElement.querySelector('.goog-logo-link');
                if (logoLink) logoLink.style.display = 'none';
            }
        }

        // Panggil fungsi styling beberapa kali setelah jeda untuk menangani pemuatan dinamis widget
        setTimeout(styleGoogleTranslateWidget, 500);
        setTimeout(styleGoogleTranslateWidget, 1500);
        // Anda bisa juga menggunakan MutationObserver jika ingin solusi yang lebih robus
        // untuk mendeteksi perubahan DOM oleh widget Google.
    }

    // JavaScript untuk Profile Dropdown (tetap sama)
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