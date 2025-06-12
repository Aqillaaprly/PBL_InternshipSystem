<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Data Mahasiswa - Admin SIMMAGANG</title>
    <script src="https://cdn.tailwindcss.com"></script>
    {{-- Font Awesome for icons, if needed --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css" xintegrity="sha512-SzlrxWUlpfuzQ+pcUCosxcglQRNAq/DZjVsC0lE40xsADsfeQoEypE+enwcOiGjk/bSuGGKHEyjSoQ1zVisanQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    {{-- Toastify-JS CDN links for notifications --}}
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/toastify-js"></script>

    <style>
        /* Add any specific styles for this page here if needed */
        /* For example, for consistent font */
        body {
            font-family: 'Inter', sans-serif;
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
    @include('admin.template.navbar')

    <main class="max-w-screen-xl mx-auto px-8 py-12 mt-16">
        <div class="bg-white p-8 rounded-xl shadow">
            <div class="flex justify-between items-center pb-4">
                <h1 class="text-2xl font-bold text-blue-800">Data Mahasiswa</h1>
                <div class="flex space-x-3">
                    <form method="GET" action="{{ route('admin.datamahasiswa') }}" class="flex">
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama/NIM..." class="border border-gray-300 rounded-l px-4 py-2 text-sm focus:outline-none focus:ring-1 focus:ring-blue-500" aria-label="Cari mahasiswa">
                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-2 rounded-r text-sm -ml-px">Cari</button>
                    </form>
                    <a href="{{ route('admin.mahasiswa.create') }}" class="bg-blue-600 text-white px-5 py-2 rounded text-sm hover:bg-blue-700">+ Tambah</a>
                </div>
            </div>

            {{-- Removed the old success/error message divs as they will be handled by Toastify --}}

            <div class="overflow-x-auto rounded-lg border border-gray-200">
                <table class="min-w-full text-sm text-center">
                    <thead class="bg-gray-100 text-gray-700 uppercase text-xs">
                        <tr>
                            <th class="px-5 py-3">No</th>
                            <th class="px-5 py-3">NIM</th>
                            <th class="px-5 py-3">Nama Mahasiswa</th>
                            <th class="px-5 py-3">Email</th>
                            <th class="px-5 py-3">Program Studi</th>
                            <th class="px-5 py-3">Kelas</th>
                            <th class="px-5 py-3">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="text-gray-600 divide-y divide-gray-200">
                        @forelse ($mahasiswas as $index => $mahasiswa)
                            <tr class="border-b border-gray-200 hover:bg-gray-50">
                                <td class="px-5 py-4">{{ $mahasiswas->firstItem() + $index }}</td>
                                <td class="px-5 py-4">{{ $mahasiswa->username ?? ($mahasiswa->detailMahasiswa->nim ?? '-') }}</td>
                                <td class="px-5 py-4 text-left">{{ $mahasiswa->name ?? ($mahasiswa->detailMahasiswa->nama ?? '-') }}</td>
                                <td class="px-5 py-4 text-left">{{ $mahasiswa->email ?? ($mahasiswa->detailMahasiswa->email ?? '-') }}</td>
                                <td class="px-5 py-4">{{ $mahasiswa->detailMahasiswa->program_studi ?? '-' }}</td>
                                <td class="px-5 py-4">{{ $mahasiswa->detailMahasiswa->kelas ?? '-' }}</td>
                                <td class="px-5 py-4">
                                    <div class="flex item-center justify-center space-x-1">
                                        <a href="{{ route('admin.mahasiswa.show', $mahasiswa->id) }}" class="bg-blue-100 text-blue-600 text-xs font-medium px-3 py-1 rounded hover:bg-blue-200">
                                            Show
                                        </a>
                                        {{-- Mengarah ke AdminMahasiswaController@edit --}}
                                        <a href="{{ route('admin.mahasiswa.edit', $mahasiswa->id) }}" class="bg-yellow-100 text-yellow-600 text-xs font-medium px-3 py-1 rounded hover:bg-yellow-200">
                                            Edit
                                        </a>
                                        {{-- Mengarah ke AdminMahasiswaController@destroy --}}
                                        <form action="{{ route('admin.mahasiswa.destroy', $mahasiswa->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Yakin ingin menghapus mahasiswa ini? Menghapus user mahasiswa juga akan menghapus detail mahasiswa terkait.');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="bg-red-100 text-red-600 text-xs font-medium px-3 py-1 rounded hover:bg-red-200">
                                                Delete
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-5 py-4 text-center text-gray-500">
                                    @if(request('search'))
                                        Tidak ada mahasiswa ditemukan untuk pencarian "{{ request('search') }}".
                                    @else
                                        Belum ada data mahasiswa.
                                    @endif
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            @if ($mahasiswas->hasPages())
                <div class="mt-6">
                    {{ $mahasiswas->appends(request()->query())->links() }}
                </div>
            @endif
        </div>
    </main>
    @include('admin.template.footer')

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
