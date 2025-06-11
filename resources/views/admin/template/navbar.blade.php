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
                    STRIDE UP    
                </a>
            </div>

             <nav class="hidden md:flex space-x-6 font-medium text-gray-700">
                <a href="{{ route('admin.datamahasiswa') }}" class="hover:text-blue-600 border-b-2 border-transparent hover:border-blue-600 transition {{ request()->routeIs('admin.datamahasiswa') || request()->is('admin/data-mahasiswa*') ? 'border-blue-600 text-blue-600' : '' }}">Data Mahasiswa</a>
                <a href="{{ route('admin.pembimbings.index') }}" class="hover:text-blue-600 border-b-2 border-transparent hover:border-blue-600 transition {{ request()->routeIs('admin.pembimbings.index') || request()->is('admin/pembimbings*') ? 'border-blue-600 text-blue-600' : '' }}">Pembimbing</a>
                <a href="{{ route('admin.aktivitas-mahasiswa.index') }}" class="hover:text-blue-600 border-b-2 border-transparent hover:border-blue-600 transition {{ request()->routeIs('admin.aktivitas-mahasiswa.index') || request()->is('admin/aktivitas-mahasiswa*') ? 'border-blue-600 text-blue-600' : '' }}">Aktivitas Mahasiswa Magang</a>
                <a href="{{ route('admin.users.index') }}" class="hover:text-blue-600 border-b-2 border-transparent hover:border-blue-600 transition {{ request()->routeIs('admin.users.index') || request()->is('admin/users*') ? 'border-blue-600 text-blue-600' : '' }}">Manajemen Users</a>
            </nav>

            <div class="flex items-center space-x-4">
                <div class="flex items-center space-x-1">
                    <div id="google_translate_element"></div>
                </div>
                
               <div class="relative">
                    <button id="profileBtn" class="flex items-center space-x-2 focus:outline-none focus:ring-2 focus:ring-blue-500 rounded-full" aria-haspopup="true" aria-expanded="false">
                        @if (Auth::user()->profile_picture && Storage::disk('public')->exists(Auth::user()->profile_picture))
                            <img src="{{ asset('storage/' . Auth::user()->profile_picture) }}" alt="User avatar" class="w-10 h-10 rounded-full border border-gray-300 object-cover" />
                        @else
                            <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name ?? Auth::user()->username) }}&background=random&color=fff&size=40" alt="User avatar" class="w-10 h-10 rounded-full border border-gray-300 object-cover" />
                        @endif
                        <span class="hidden sm:block font-medium text-gray-700">{{ Auth::user()->username ?? 'Admin' }}</span>
                    </button>
                    <div id="profileDropdown" class="origin-top-right absolute right-0 mt-2 w-44 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 hidden">
                        <a href="{{ route('admin.profile') }}" class="block px-4 py-2 text-gray-700 hover:bg-gray-100 rounded-t-md">Profil</a>
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
    .goog-logo-link, .goog-te-gadget span, iframe.goog-te-banner-frame { display: none !important; }
    .goog-te-gadget { color: transparent !important; font-size: 0 !important; line-height: normal !important; display: inline-block; vertical-align: middle; }
    body { top: 0px !important; }
    #google_translate_element select.goog-te-combo { background-color: white; color: #4B5563; border: 1px solid #D1D5DB; border-radius: 0.375rem; padding: 0.25rem 0.5rem; font-size: 0.875rem; line-height: 1.25rem; height: 2rem; appearance: none; -webkit-appearance: none; -moz-appearance: none; cursor: pointer; margin-left: 0.25rem; min-width: auto; }
</style>
<script type="text/javascript">
    function googleTranslateElementInit() {
        new google.translate.TranslateElement({ pageLanguage: 'id', includedLanguages: 'id,en,es,fr,de,ja,ko,ar', layout: google.translate.TranslateElement.InlineLayout.SIMPLE, autoDisplay: false }, 'google_translate_element');
        function styleGoogleTranslateWidget() {
            var bannerFrame = document.querySelector('iframe.goog-te-banner-frame'); if (bannerFrame) bannerFrame.style.display = 'none';
            document.body.style.top = '0px';
            var gtElement = document.getElementById('google_translate_element');
            if (gtElement) {
                var spans = gtElement.querySelectorAll('.goog-te-gadget > span');
                spans.forEach(function(span) { if (!span.querySelector('select')) span.style.display = 'none'; });
                var logoLink = gtElement.querySelector('.goog-logo-link'); if (logoLink) logoLink.style.display = 'none';
            }
        }
        setTimeout(styleGoogleTranslateWidget, 500); setTimeout(styleGoogleTranslateWidget, 1500);
    }
    const profileBtn = document.getElementById('profileBtn');
    const profileDropdown = document.getElementById('profileDropdown');
    if (profileBtn && profileDropdown) {
        profileBtn.addEventListener('click', () => { const isHidden = profileDropdown.classList.toggle('hidden'); profileBtn.setAttribute('aria-expanded', !isHidden); });
        document.addEventListener('click', function(e) { if (!profileBtn.contains(e.target) && !profileDropdown.contains(e.target)) { profileDropdown.classList.add('hidden'); profileBtn.setAttribute('aria-expanded', false); } });
    }
</script>
<script type="text/javascript" src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>