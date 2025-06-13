<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Manajemen Pembimbing - Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>

    {{-- Font Awesome for icons, if needed --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css" xintegrity="sha512-SzlrxWUlpfuzQ+pcUCosxcglQRNAq/DZjVsC0lE40xsADsfeQoEypE+enwcOiGjk/bSuGGKHEyjSoQ1zVisanQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    {{-- Toastify-JS CDN links for notifications --}}
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/toastify-js"></script>

    <style>
        body {
            font-family: 'Inter', sans-serif;
            /* Using Inter font as per instructions */
        }

        /* Custom styles for badges, alerts, and buttons if needed, consistent with previous immersives */
        .badge {
            padding: 0.25rem 0.75rem;
            font-size: 0.75rem;
            font-weight: 600;
            border-radius: 9999px;
            display: inline-block;
        }

        /* Ensure table cells do not wrap text */
        .min-w-full th,
        .min-w-full td {
            white-space: nowrap;
        }

        /* Add horizontal scroll if content overflows */
        .overflow-x-auto {
            overflow-x: auto;
        }
    </style>
</head>
<body class="bg-blue-50 text-gray-800">
    {{-- Include the admin navigation bar --}}
    @include('admin.template.navbar')

    <main class="max-w-screen-xl mx-auto px-8 py-12 mt-16">
        <div class="bg-white p-8 rounded-xl shadow">
            <div class="flex flex-col sm:flex-row justify-between items-center pb-6">
                <h1 class="text-2xl font-bold text-blue-800 mb-4 sm:mb-0">Manajemen Dosen Pembimbing</h1>
                <div class="flex flex-col sm:flex-row space-y-2 sm:space-y-0 sm:space-x-3 w-full sm:w-auto">
                    <form method="GET" action="{{ route('admin.pembimbings.index') }}" class="flex flex-1 sm:flex-none">
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari NIP, Nama, Email..." class="border border-gray-300 rounded-l px-4 py-2 text-sm focus:outline-none focus:ring-1 focus:ring-blue-500 w-full">
                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-r text-sm">Cari</button>
                    </form>
                    <a href="{{ route('admin.pembimbings.create') }}" class="bg-blue-600 text-white px-5 py-2 rounded text-sm hover:bg-blue-700 whitespace-nowrap">+ Tambah Pembimbing</a>
                </div>
            </div>

            {{-- Removed the old success/error message divs as they will be handled by Toastify --}}

            <div class="overflow-x-auto rounded-lg border border-gray-200">
                <table class="min-w-full text-sm">
                    <thead class="bg-gray-100 text-gray-700 uppercase text-xs text-left">
                        <tr>
                            <th class="px-5 py-3">No</th>
                            <th class="px-5 py-3">NIP</th>
                            <th class="px-5 py-3">Nama Lengkap</th>
                            <th class="px-5 py-3">Email Institusi</th>
                            <th class="px-5 py-3">Program Studi</th>
                            <th class="px-5 py-3 text-center">Kuota (Aktif/Maks)</th>
                            <th class="px-5 py-3 text-center">Status</th>
                            <th class="px-5 py-3 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="text-gray-600 text-left">
                        @forelse ($pembimbings as $index => $pembimbing)
                            <tr class="border-b border-gray-200 hover:bg-gray-50">
                                <td class="px-5 py-4 text-center">{{ $pembimbings->firstItem() + $index }}</td>
                                <td class="px-5 py-4">{{ $pembimbing->nip }}</td>
                                <td class="px-5 py-4">{{ $pembimbing->nama_lengkap }}</td>
                                <td class="px-5 py-4">{{ $pembimbing->email_institusi }}</td>
                                <td class="px-5 py-4">{{ $pembimbing->program_studi_homebase ?? '-' }}</td>
                                <td class="px-5 py-4 text-center">{{ $pembimbing->kuota_aktif }}/{{ $pembimbing->maks_kuota_bimbingan }}</td>
                                <td class="px-5 py-4 text-center">
                                    @if($pembimbing->status_aktif)
                                        <span class="badge bg-green-100 text-green-700">Aktif</span>
                                    @else
                                        <span class="badge bg-red-100 text-red-700">Tidak Aktif</span>
                                    @endif
                                </td>
                                <td class="px-5 py-4 text-center">
                                    <div class="flex item-center justify-center space-x-1">
                                        {{-- Ensure this section uses the correct route names --}}
                                        <a href="{{ route('admin.pembimbings.show', $pembimbing->id) }}" class="bg-sky-100 text-sky-600 text-xs font-medium px-3 py-1 rounded hover:bg-sky-200">Detail</a>
                                        <a href="{{ route('admin.pembimbings.edit', $pembimbing->id) }}" class="bg-yellow-100 text-yellow-600 text-xs font-medium px-3 py-1 rounded hover:bg-yellow-200">Edit</a>
                                        <form action="{{ route('admin.pembimbings.destroy', $pembimbing->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Yakin ingin menghapus pembimbing ini beserta akun login terkait?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="bg-red-100 text-red-600 text-xs font-medium px-3 py-1 rounded hover:bg-red-200">Hapus</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-5 py-4 text-center text-gray-500">
                                    @if(request('search'))
                                        Tidak ada pembimbing ditemukan untuk pencarian "{{ request('search') }}".
                                    @else
                                        Belum ada data pembimbing.
                                    @endif
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if ($pembimbings->hasPages())
                <div class="mt-6">
                    {{ $pembimbings->appends(request()->query())->links() }}
                </div>
            @endif

            {{-- The "Tetapkan Bimbingan Magang Form" has been removed from this page as per your request --}}

        </div>
    </main>


    {{-- Toastify-JS Integration --}}
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
                    borderRadius: "0.6rem", // Tailored to your form-card rounded-lg
                    boxShadow: "0 4px 12px rgba(0,0,0,0.15)", // A subtle shadow
                    padding: "1rem 1.5rem" // Good padding
                },
                offset: { // Offset from the corner
                    x: 20, // horizontal axis - can be a number or a string indicating unity. eg: "2em"
                    y: 20 // vertical axis - can be a number or a string indicating unity. eg: "2em"
                },
                onClick: function(){} // Callback after click
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
                        y: 20 + {{ '$loop->index * 70' }} // Stagger multiple toasts if many errors
                    },
                    onClick: function(){}
                }).showToast();
            @endforeach
        @endif
    </script>
</body>
</html>
