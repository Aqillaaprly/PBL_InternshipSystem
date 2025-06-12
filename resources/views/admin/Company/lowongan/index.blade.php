<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Manajemen Lowongan - perusahaan</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css" xintegrity="sha512-SzlrxWUlpfuzQ+pcUCosxcglQRNAq/DZjVsC0lE40xsADsfeQoEypE+enwcOiGjk/bSuGGKHEyjSoQ1zVisanQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    
    {{-- Toastify-JS CDN links --}}
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/toastify-js"></script>

    <style>
        /* Add any specific styles for this page here if needed */
        /* For example, for consistent font */
        body {
            font-family: 'Inter', sans-serif;
        }
    </style>
</head>
<body class="bg-[#f0f6ff]">
    @include('admin.template.navbar')

<main class="max-w-screen-xl mx-auto px-8 py-12 mt-16">
    <div class="bg-white p-8 rounded-xl shadow">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-semibold">Manajemen Lowongan</h1>
            <div class="flex space-x-3">
                <input type="text" placeholder="Search" class="border border-gray-300 rounded px-4 py-2" />
                <button class="border border-gray-300 px-4 py-2 rounded">Filter</button>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm text-center">
                <thead class="bg-gray-100">
                <tr>
                    <th class="px-5 py-3">No</th>
                    <th class="px-5 py-3">Judul Lowongan</th>
                    <th class="px-5 py-3">Perusahaan</th>
                    <th class="px-5 py-3">Tipe</th>
                    <th class="px-5 py-3">Lokasi</th>
                    <th class="px-5 py-3">Tanggal Tutup</th>
                    <th class="px-5 py-3">Status</th>
                    <th class="px-5 py-3">Action</th>
                </tr>
                </thead>
                <tbody>
                @forelse($lowongans as $index => $lowongan)
                    <tr class="border-b">
                        <td class="px-5 py-3">{{ $lowongans->firstItem() + $index }}</td>
                        <td class="px-5 py-3">{{ $lowongan->judul }}</td>
                        <td class="px-5 py-3">{{ $lowongan->company->nama_perusahaan ?? 'N/A' }}</td>
                        <td class="px-5 py-3">{{ $lowongan->tipe }}</td>
                        <td class="px-5 py-3">{{ $lowongan->provinsi }}</td>
                        <td class="px-5 py-3">{{ \Carbon\Carbon::parse($lowongan->tanggal_tutup)->format('d M Y') }}</td>
                        <td class="px-5 py-3 text-center align-middle">
                            <span class="text-xs font-medium w-20 block mx-auto py-2 px-2 py-1 rounded-full
                                @if($lowongan->status == 'Aktif') bg-green-100 text-green-600
                                @elseif($lowongan->status == 'Nonaktif') bg-red-100 text-red-700
                                @else bg-gray-100 text-gray-600 @endif">
                                {{ $lowongan->status }}
                            </span>
                        </td>
                       <td class="px-5 py-3">
                         <div class="flex space-x-1 justify-center">
                            {{-- Tautan ke detail lowongan --}}
                            <a href="{{ route('admin.lowongan.show', $lowongan->id) }}" class="bg-blue-100 text-blue-600 text-xs font-medium px-3 py-1 rounded hover:bg-blue-200">Show</a>
                            {{-- Tautan ke form edit lowongan --}}
                            <a href="{{ route('admin.lowongan.edit', $lowongan->id) }}" class="bg-yellow-100 text-yellow-600 text-xs font-medium px-3 py-1 rounded hover:bg-yellow-200">Edit</a>
                            {{-- Form untuk menghapus lowongan --}}
                            <form action="{{ route('admin.lowongan.destroy', $lowongan->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus lowongan ini?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="bg-red-100 text-red-600 text-xs font-medium px-3 py-1 rounded hover:bg-red-200">Delete</button>
                            </form>
                        </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="px-5 py-3 text-center text-gray-500">Tidak ada data lowongan.</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>

        @if($lowongans->hasPages())
            <div class="mt-6">
                {{ $lowongans->links() }} {{-- Menampilkan pagination links --}}
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

    // Display validation errors (iterates through $errors->all()) - less common on tables after redirect
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