<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profil Admin - {{ $admin->name ?? $admin->username }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        /* Menyembunyikan input file asli */
        #profile_picture_hidden_input {
            display: none;
        }
    </style>
</head>
<body class="bg-gray-100">
    @include('admin.template.navbar')

    <main class="max-w-3xl mx-auto px-4 py-10 mt-20">
        <div class="bg-white p-8 rounded-lg shadow-lg">

            {{-- Bagian Header Profil --}}
            <div class="flex flex-col items-center mb-6"> {{-- Mengurangi mb-8 menjadi mb-6 --}}
                {{-- Tampilan Foto Profil Saat Ini & Tombol Ubah Foto --}}
                <label for="profile_picture_hidden_input" class="cursor-pointer group">
                    <div class="relative">
                        @if ($admin->profile_picture && Storage::disk('public')->exists($admin->profile_picture))
                            <img src="{{ asset('storage/' . $admin->profile_picture) }}" alt="Foto Profil Saat Ini" class="w-32 h-32 rounded-full mb-2 border-4 border-blue-200 object-cover group-hover:opacity-75 transition-opacity duration-300">
                        @else
                            <img src="https://ui-avatars.com/api/?name={{ urlencode($admin->name ?? $admin->username) }}&background=random&color=fff&size=128" alt="Avatar Admin" class="w-32 h-32 rounded-full mb-2 border-4 border-blue-200 object-cover group-hover:opacity-75 transition-opacity duration-300">
                        @endif
                        {{-- Overlay ikon kamera saat hover (opsional, untuk estetika) --}}
                        <div class="absolute inset-0 flex items-center justify-center bg-black bg-opacity-0 group-hover:bg-opacity-40 rounded-full transition-opacity duration-300">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-white opacity-0 group-hover:opacity-100 transition-opacity duration-300" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M4 2a2 2 0 00-2 2v12a2 2 0 002 2h12a2 2 0 002-2V4a2 2 0 00-2-2H4zm10.5 4.5a.5.5 0 00-.5-.5H6a.5.5 0 000 1h8a.5.5 0 00.5-.5zM4.5 8a.5.5 0 00-.5.5v5a.5.5 0 00.5.5h11a.5.5 0 00.5-.5v-5a.5.5 0 00-.5-.5h-11zM11 11a1 1 0 11-2 0 1 1 0 012 0z" clip-rule="evenodd" />
                                <path d="M2 6a2 2 0 012-2h12a2 2 0 012 2v2a2 2 0 01-2 2H4a2 2 0 01-2-2V6zm2-2a1 1 0 011-1h10a1 1 0 011 1v1H5V5h1zM4 16.5A1.5 1.5 0 015.5 15h9a1.5 1.5 0 011.5 1.5v.5a1.5 1.5 0 01-1.5 1.5h-9A1.5 1.5 0 014 17v-.5z" />
                            </svg>
                        </div>
                    </div>
                    <p class="text-sm text-blue-600 hover:text-blue-800 text-center mt-1">Ubah foto profil</p>
                </label>
        
                 @error('profile_picture')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror

                <h1 class="text-2xl font-bold text-gray-800 mt-4">{{ $admin->name ?? 'Nama Admin Belum Diatur' }}</h1>
                <p class="text-sm text-gray-500">{{ '@' . ($admin->username ?? 'N/A') }}</p>
            </div>

            <h2 class="text-xl font-semibold text-gray-700 mb-4">Edit Profil</h2> 


            @if ($errors->any() && !$errors->has('profile_picture')) {{-- Hanya tampilkan jika error bukan dari profile_picture --}}
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-5" role="alert">
                    <strong class="font-bold">Oops! Ada beberapa masalah:</strong>
                    <ul class="mt-3 list-disc list-inside text-sm text-red-600">
                        @foreach ($errors->all() as $error)
                            @if ($error !== $errors->first('profile_picture')) {{-- Jangan tampilkan error profile_picture di sini --}}
                                <li>{{ $error }}</li>
                            @endif
                        @endforeach
                    </ul>
                </div>
            @endif
             @if (session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 px-4 py-3 rounded relative mb-6" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            <form method="POST" action="{{ route('admin.profile.update') }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                {{-- Input file yang sebenarnya, tersembunyi --}}
                <input type="file" name="profile_picture" id="profile_picture_hidden_input" accept="image/png, image/jpeg, image/jpg, image/gif, image/svg+xml" class="hidden">

                <div class="space-y-4"> {{-- Mengurangi space-y-6 menjadi space-y-4 --}}
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Nama <span class="text-red-500">*</span></label>
                        <input type="text" name="name" id="name" value="{{ old('name', $admin->name) }}" required
                               class="mt-1 block w-full px-3 py-2 border @error('name') border-red-500 @else border-gray-300 @enderror rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="username" class="block text-sm font-medium text-gray-700 mb-1">Nama pengguna <span class="text-red-500">*</span></label>
                        <input type="text" name="username" id="username" value="{{ old('username', $admin->username) }}" required
                               class="mt-1 block w-full px-3 py-2 border @error('username') border-red-500 @else border-gray-300 @enderror rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        @error('username') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <input type="hidden" name="email" value="{{ $admin->email }}">


                    <div class="border-t pt-4 mt-6"> {{-- Menambah mt-6 untuk jarak sebelum bagian password --}}
                        <h3 class="text-lg font-medium text-gray-900 mb-1">Ubah Kata ganti (Password)</h3>
                        <p class="text-xs text-gray-500 mb-3">Kosongkan jika Anda tidak ingin mengubahnya.</p>
                        <div>
                            <label for="current_password" class="block text-sm font-medium text-gray-700 mb-1">Password Saat Ini</label>
                            <input type="password" name="current_password" id="current_password"
                                   class="mt-1 block w-full px-3 py-2 border @error('current_password') border-red-500 @else border-gray-300 @enderror rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                            @error('current_password') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div class="mt-3"> {{-- Mengurangi mt-4 menjadi mt-3 --}}
                            <label for="new_password" class="block text-sm font-medium text-gray-700 mb-1">Password Baru</label>
                            <input type="password" name="new_password" id="new_password"
                                   class="mt-1 block w-full px-3 py-2 border @error('new_password') border-red-500 @else border-gray-300 @enderror rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                            @error('new_password') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div class="mt-3"> {{-- Mengurangi mt-4 menjadi mt-3 --}}
                            <label for="new_password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">Konfirmasi Password Baru</label>
                            <input type="password" name="new_password_confirmation" id="new_password_confirmation"
                                   class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        </div>
                    </div>
                </div>

                <div class="mt-8 flex justify-end space-x-3">
                    <a href="{{ route('admin.profile') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-medium py-2 px-4 rounded-md">
                        Batal
                    </a>
                    <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-2 px-4 rounded-md shadow-sm">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </main>

    @include('admin.template.footer')

    <script>
        const actualFileInput = document.getElementById('profile_picture_hidden_input');
        const fileChosenDisplay = document.getElementById('file-chosen');
        // Label yang berfungsi sebagai tombol klik (elemen yang membungkus gambar dan teks "Ubah foto profil")
        // const customUploadButton = document.querySelector('label[for="profile_picture_hidden_input"]'); 
        // Tidak perlu variabel customUploadButton jika label sudah benar mengarah ke input file

        if (actualFileInput && fileChosenDisplay) {
            actualFileInput.addEventListener('change', function() {
                if (this.files && this.files.length > 0) {
                    fileChosenDisplay.textContent = this.files[0].name;
                    // Opsional: Preview gambar yang baru dipilih
                    // const reader = new FileReader();
                    // reader.onload = function(e) {
                    //     document.querySelector('label[for="profile_picture_hidden_input"] img').src = e.target.result;
                    // }
                    // reader.readAsDataURL(this.files[0]);
                } else {
                    fileChosenDisplay.textContent = 'Tidak ada file dipilih';
                }
            });
        }
    </script>
</body>
</html>