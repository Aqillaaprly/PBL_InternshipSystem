<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Data Log Bimbingan - Dosen STRIDEUP</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Toastify CSS -->
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
</head>
<body class="bg-blue-50 text-gray-800">
    @include('dosen.template.navbar')

    <main class="max-w-screen-xl mx-auto px-8 py-12 mt-16">
        <div class="bg-white p-8 rounded-xl shadow">
            <div class="flex justify-between items-center pb-4">
                <h1 class="text-2xl font-bold text-blue-800 ml-8">Data Log Bimbingan</h1>
                <div class="flex space-x-3">
                    <form method="GET" action="{{ route('dosen.data_log') }}" class="flex">
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama mahasiswa..." class="border border-gray-300 rounded-l px-4 py-2 text-sm focus:outline-none focus:ring-1 focus:ring-blue-500">
                    </form>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full text-sm text-center">
                    <thead class="bg-gray-100 text-gray-700 uppercase text-xs">
                        <tr>
                            <th class="px-5 py-3">No</th>
                            <th class="px-5 py-3">Nama Mahasiswa</th>
                            <th class="px-5 py-3">Periode Magang</th>
                            <th class="px-5 py-3">Status Bimbingan</th>
                            <th class="px-5 py-3">Pembimbing</th>
                            <th class="px-5 py-3">Perusahaan</th>
                            <th class="px-5 py-3">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="text-gray-600">
                        @forelse ($bimbingans as $index => $bimbingan)
                            <tr class="border-b border-gray-200 hover:bg-gray-50">
                                <td class="px-5 py-4">{{ $bimbingans->firstItem() + $index }}</td>
                                <td class="px-5 py-4">
                                    {{ $bimbingan->mahasiswa->name ?? '-' }}
                                </td>
                                <td class="px-5 py-4">{{ $bimbingan->periode_magang ?? '-' }}</td>
                                <td class="px-5 py-4">
                                    <span class="bg-green-100 text-green-600 text-xs font-medium px-3 py-1 rounded">
                                        {{ $bimbingan->status_bimbingan ?? 'Aktif' }}
                                    </span>
                                </td>
                                <td class="px-5 py-4">{{ $bimbingan->pembimbing->nama_lengkap ?? '-' }}</td>
                                <td class="px-5 py-4">{{ $bimbingan->company->nama_perusahaan ?? '-' }}</td>
                                <td class="px-5 py-4">
                                    <div class="flex item-center justify-center space-x-1">
                                        <a href="{{ route('dosen.data_log.show', $bimbingan->mahasiswa_user_id) }}" class="bg-blue-100 text-blue-600 text-xs font-medium px-3 py-1 rounded hover:bg-blue-200">
                                            Show Log
                                        </a>
                                        <a href="{{ route('dosen.log_bimbingan.create', $bimbingan->id) }}" class="bg-blue-100 text-blue-600 text-xs font-medium px-3 py-1 rounded hover:bg-blue-200">
                                            Add Log
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-5 py-4 text-center text-gray-500">
                                     @if(request('search'))
                                        Tidak ada bimbingan ditemukan untuk pencarian "{{ request('search') }}".
                                    @else
                                        Belum ada data log bimbingan.
                                    @endif
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($bimbingans->hasPages())
                <div class="mt-6">
                    {{ $bimbingans->appends(request()->query())->links() }}
                </div>
            @endif
        </div>
    </main>
    @include('dosen.template.footer')

    <!-- Toastify JS -->
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
    <script>
        // Display success message
        @if (session('success'))
            Toastify({
                text: "{{ session('success') }}",
                duration: 3000, // 3 seconds
                newWindow: true,
                close: true,
                gravity: "top", // `top` or `bottom`
                position: "right", // `left`, `center` or `right`
                stopOnFocus: true, // Prevents dismissing on hover
                style: {
                    background: "linear-gradient(to right, #4CAF50, #66BB6A)", // Green gradient
                    borderRadius: "0.6rem",
                    boxShadow: "0 4px 12px rgba(0,0,0,0.15)",
                    padding: "1rem 1.5rem"
                },
                offset: {
                    x: 20,
                    y: 20
                },
                onClick: function(){}
            }).showToast();
        @endif

        // Display error message (e.g., from controller catches)
        @if (session('error'))
            Toastify({
                text: "{{ session('error') }}",
                duration: 5000, // Longer duration for errors
                newWindow: true,
                close: true,
                gravity: "top",
                position: "right",
                stopOnFocus: true,
                style: {
                    background: "linear-gradient(to right, #EF4444, #DC2626)", // Red gradient
                    borderRadius: "0.6rem",
                    boxShadow: "0 4px 12px rgba(0,0,0,0.15)",
                    padding: "1rem 1.5rem"
                },
                offset: {
                    x: 20,
                    y: 20
                },
                onClick: function(){}
            }).showToast();
        @endif

        // Display validation errors (iterates through $errors->all())
        @if ($errors->any())
            @foreach ($errors->all() as $error)
                Toastify({
                    text: "{{ $error }}",
                    duration: 5000,
                    newWindow: true,
                    close: true,
                    gravity: "top",
                    position: "right",
                    stopOnFocus: true,
                    style: {
                        background: "linear-gradient(to right, #F59E0B, #D97706)", // Orange/Amber gradient for warnings/validation
                        borderRadius: "0.6rem",
                        boxShadow: "0 4px 12px rgba(0,0,0,0.15)",
                        padding: "1rem 1.5rem"
                    },
                    offset: {
                        x: 20,
                        y: 20 + {{ $loop->index * 70 }} // Stagger multiple toasts if many errors
                    },
                    onClick: function(){}
                }).showToast();
            @endforeach
        @endif
    </script>
</body>
</html>
