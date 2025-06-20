<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Manajemen Pengguna - Admin SIMMAGANG</title>
    <script src="https://cdn.tailwindcss.com"></script>
    {{-- <link rel="stylesheet" href="{{ asset('css/admin_style.css') }}"> --}}
</head>
<body class="bg-blue-50 text-gray-800">

    @include('admin.template.navbar')

    <main class="max-w-screen-xl mx-auto px-8 py-12 mt-16">
        <div class="bg-white p-8 rounded-xl shadow">
            <div class="flex justify-between items-center pb-6">
                <h1 class="text-2xl font-bold text-blue-800">Manajemen Pengguna</h1>
                
            </div>

            @if (session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <strong class="font-bold">Berhasil!</strong>
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif
            @if (session('error'))
                 <div class="bg-red-100 border-l-4 border-red-500 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <strong class="font-bold">Gagal!</strong>
                    <span class="block sm:inline">{{ session('error') }}</span>
                </div>
            @endif

             <div class="overflow-x-auto rounded-lg border border-gray-200">
                <table class="min-w-full text-sm">
                    <thead class="bg-gray-100 text-gray-700 uppercase text-xs text-left">
                        <tr>
                            <th class="px-5 py-3">No</th>
                            <th class="px-5 py-3">Nama</th>
                            <th class="px-5 py-3">Username</th>
                            <th class="px-5 py-3">Email</th>
                            <th class="px-5 py-3">Role</th>
                           
                        </tr>
                    </thead>
                    <tbody class="text-gray-600 text-left">
                        @forelse ($users as $index => $user)
                            <tr class="border-b border-gray-200 hover:bg-gray-50">
                                <td class="px-5 py-4 text-center">{{ $users->firstItem() + $index }}</td>
                                <td class="px-5 py-4">{{ $user->name }}</td>
                                <td class="px-5 py-4">{{ $user->username }}</td>
                                <td class="px-5 py-4">{{ $user->email }}</td>
                                <td class="px-5 py-4">
                                    <span class="px-2 py-1 font-semibold leading-tight rounded-full text-xs
                                        @if($user->role->name == 'admin') bg-red-100 text-red-700
                                        @elseif($user->role->name == 'mahasiswa') bg-green-100 text-green-700
                                        @elseif($user->role->name == 'dosen') bg-blue-100 text-blue-700
                                        @elseif($user->role->name == 'perusahaan') bg-purple-100 text-purple-700
                                        @else bg-gray-100 text-gray-700 @endif">
                                        {{ ucfirst($user->role->name) }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-5 py-4 text-center text-gray-500">
                                     @if(request('search'))
                                        Tidak ada pengguna ditemukan untuk pencarian "{{ request('search') }}".
                                    @else
                                        Belum ada data pengguna.
                                    @endif
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($users->hasPages())
                <div class="mt-6">
                    {{ $users->appends(request()->query())->links() }}
                </div>
            @endif
        </div>
    </main>

</body>
</html>