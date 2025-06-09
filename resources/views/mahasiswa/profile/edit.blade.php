<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profil - {{ $user->name ?? $user->username }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        #profile_picture_hidden_input {
            display: none;
        }
        .profile-picture-container {
            position: relative;
            width: 128px;
            height: 128px;
        }
        .profile-picture-overlay {
            opacity: 0;
            transition: opacity 0.3s ease;
        }
        .profile-picture-container:hover .profile-picture-overlay {
            opacity: 1;
        }
    </style>
</head>
<body class="bg-gray-100">
@include('mahasiswa.template.navbar')

<main class="max-w-3xl mx-auto px-4 py-10 mt-20">
    <div class="bg-white p-8 rounded-lg shadow-lg">
        {{-- Profile Header --}}
        <div class="flex flex-col items-center mb-6">
            {{-- Profile Picture with Upload Button --}}
            <div class="profile-picture-container mb-3">
                <label for="profile_picture_hidden_input" class="cursor-pointer">
                    @if ($user->profile_picture && Storage::disk('public')->exists($user->profile_picture))
                    <img src="{{ asset('storage/' . $user->profile_picture) }}"
                         alt="Foto Profil Saat Ini"
                         class="w-32 h-32 rounded-full border-4 border-blue-200 object-cover"
                         id="profile_image_preview">
                    @else
                    <img src="https://ui-avatars.com/api/?name={{ urlencode($user->name ?? $user->username) }}&background=random&color=fff&size=128"
                         alt="Avatar"
                         class="w-32 h-32 rounded-full border-4 border-blue-200 object-cover"
                         id="profile_image_preview">
                    @endif
                    <div class="profile-picture-overlay absolute inset-0 flex items-center justify-center bg-black bg-opacity-40 rounded-full">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-white" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M4 2a2 2 0 00-2 2v12a2 2 0 002 2h12a2 2 0 002-2V4a2 2 0 00-2-2H4zm10.5 4.5a.5.5 0 00-.5-.5H6a.5.5 0 000 1h8a.5.5 0 00.5-.5zM4.5 8a.5.5 0 00-.5.5v5a.5.5 0 00.5.5h11a.5.5 0 00.5-.5v-5a.5.5 0 00-.5-.5h-11zM11 11a1 1 0 11-2 0 1 1 0 012 0z" clip-rule="evenodd" />
                        </svg>
                    </div>
                </label>
                <p class="text-sm text-blue-600 hover:text-blue-800 text-center mt-1">Ubah foto profil</p>
            </div>

            @error('profile_picture')
            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror

            <h1 class="text-2xl font-bold text-gray-800 mt-4">{{ $user->name ?? 'Nama Belum Diatur' }}</h1>
            <p class="text-sm text-gray-500">{{ '@' . ($user->username ?? 'N/A') }}</p>
            @if($user->role)
            <span class="mt-1 px-2 py-0.5 text-xs font-semibold bg-blue-100 text-blue-700 rounded-full">
                        {{ ucfirst($user->role->name) }}
                    </span>
            @endif
        </div>

        <h2 class="text-xl font-semibold text-gray-700 mb-4">Edit Profil</h2>

        {{-- Error Messages --}}
        @if ($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-5" role="alert">
            <strong class="font-bold">Oops! Ada beberapa masalah:</strong>
            <ul class="mt-3 list-disc list-inside text-sm text-red-600">
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        @if (session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 px-4 py-3 rounded relative mb-6" role="alert">
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
        @endif

        <form method="POST" action="{{ route('mahasiswa.profile.update') }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            {{-- Hidden file input --}}
            <input type="file" name="profile_picture" id="profile_picture_hidden_input"
                   accept="image/png, image/jpeg, image/jpg" class="hidden">

            <div class="space-y-4">
                {{-- Name Field --}}
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Nama <span class="text-red-500">*</span></label>
                    <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" required
                           class="mt-1 block w-full px-3 py-2 border @error('name') border-red-500 @else border-gray-300 @enderror rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                    @error('name')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Username Field --}}
                <div>
                    <label for="username" class="block text-sm font-medium text-gray-700 mb-1">Nama pengguna <span class="text-red-500">*</span></label>
                    <input type="text" name="username" id="username" value="{{ old('username', $user->username) }}" required
                           class="mt-1 block w-full px-3 py-2 border @error('username') border-red-500 @else border-gray-300 @enderror rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                    @error('username')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Email Field --}}
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email <span class="text-red-500">*</span></label>
                    <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" required
                           class="mt-1 block w-full px-3 py-2 border @error('email') border-red-500 @else border-gray-300 @enderror rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                    @error('email')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Password Change Section --}}
                <div class="border-t pt-4 mt-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-1">Ubah Kata Sandi</h3>
                    <p class="text-xs text-gray-500 mb-3">Kosongkan jika Anda tidak ingin mengubahnya.</p>

                    {{-- Current Password --}}
                    <div class="relative">
                        <input type="password" name="current_password" id="current_password"
                               class="mt-1 block w-full px-3 py-2 border @error('current_password') border-red-500 @else border-gray-300 @enderror rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        <button type="button" class="absolute inset-y-0 right-0 flex items-center pr-3 toggle-password">
                            <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                        </button>
                        @error('current_password')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- New Password --}}
                    <div class="relative mt-3">
                        <input type="password" name="new_password" id="new_password"
                               class="mt-1 block w-full px-3 py-2 border @error('new_password') border-red-500 @else border-gray-300 @enderror rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        <button type="button" class="absolute inset-y-0 right-0 flex items-center pr-3 toggle-password">
                            <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                        </button>
                        @error('new_password')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Confirm New Password --}}
                    <div class="relative mt-3">
                        <input type="password" name="new_password_confirmation" id="new_password_confirmation"
                               class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        <button type="button" class="absolute inset-y-0 right-0 flex items-center pr-3 toggle-password">
                            <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>

            {{-- Form Actions --}}
            <div class="mt-8 flex justify-end space-x-3">
                <a href="{{ route('mahasiswa.profile') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-medium py-2 px-4 rounded-md transition duration-150 ease-in-out">
                    Batal
                </a>
                <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-2 px-4 rounded-md shadow-sm transition duration-150 ease-in-out">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</main>

@include('mahasiswa.template.footer')

<script>
    // Profile picture preview functionality
    document.getElementById('profile_picture_hidden_input').addEventListener('change', function(event) {
        const preview = document.getElementById('profile_image_preview');
        if (this.files && this.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
            }
            reader.readAsDataURL(this.files[0]);
        }
    });

    // Show password toggle functionality
    document.querySelectorAll('.toggle-password').forEach(button => {
        button.addEventListener('click', function() {
            const input = this.parentElement.querySelector('input');
            const icon = this.querySelector('svg');
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.add('text-indigo-600');
            } else {
                input.type = 'password';
                icon.classList.remove('text-indigo-600');
            }
        });
    });
</script>
</body>
</html>
