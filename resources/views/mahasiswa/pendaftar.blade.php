<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Form Pendaftaran Magang</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
@include('mahasiswa.template.navbar')
<body class="bg-gray-50 text-gray-800 pt-20">

<div class="max-w-5xl mx-auto p-4">
    <div class="bg-white rounded-xl shadow-md p-6">

        <!-- Header -->
        <div class="mb-6">
            <h1 class="text-2xl font-semibold">Form Pendaftaran Magang</h1>
            <p class="text-sm text-gray-500">Lengkapi form berikut untuk mendaftar magang.</p>
        </div>

        <!-- Flash Success Message -->
        @if(session('success'))
        <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
            {{ session('success') }}
        </div>
        @endif

        <!-- Form -->
        <form action="{{ route('mahasiswa.pendaftar.submit') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                <!-- Lowongan -->
                <div class="col-span-2">
                    <label class="block text-sm text-gray-600">Pilih Lowongan</label>
                    <select name="lowongan_id" required class="w-full px-4 py-2 border rounded-md mt-1">
                        @foreach($lowongans as $lowongan)
                        <option value="{{ $lowongan->id }}">{{ $lowongan->judul }} - {{ $lowongan->company->nama }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Uploads -->
                @php
                $files = [
                'surat_lamaran' => 'Surat Lamaran',
                'cv' => 'Daftar Riwayat Hidup (CV)',
                'portofolio' => 'Portofolio (Opsional)'
                ];
                @endphp

                @foreach($files as $name => $label)
                <div>
                    <label class="block text-sm text-gray-600">{{ $label }}</label>
                    <input type="file" name="{{ $name }}" accept=".pdf,.doc,.docx"
                           {{ $name !== 'portofolio' ? 'required' : '' }}
                    class="w-full mt-1"/>
                </div>
                @endforeach

                <!-- Catatan -->
                <div class="col-span-2">
                    <label class="block text-sm text-gray-600">Catatan Tambahan</label>
                    <textarea name="catatan_pendaftar" rows="3" class="w-full px-4 py-2 border rounded-md mt-1"
                              placeholder="Tambahkan catatan tambahan jika ada..."></textarea>
                </div>
            </div>

            <button type="submit"
                    class="mt-4 bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-2 rounded">
                Simpan Pendaftaran
            </button>
        </form>
    </div>

    <!-- Status Table -->
    @if(isset($pendaftarans) && count($pendaftarans) > 0)
    <div class="mt-10 bg-white rounded-xl shadow-md p-6">
        <h2 class="text-xl font-semibold mb-4">Status Lamaran Anda</h2>
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm border border-gray-200">
                <thead class="bg-gray-100 text-gray-700">
                <tr>
                    <th class="px-4 py-2 text-left">Judul Lowongan</th>
                    <th class="px-4 py-2 text-left">Perusahaan</th>
                    <th class="px-4 py-2 text-left">Tanggal Daftar</th>
                    <th class="px-4 py-2 text-left">Status Lamaran</th>
                    <th class="px-4 py-2 text-left">Catatan Admin</th>
                </tr>
                </thead>
                <tbody>
                @foreach($pendaftarans as $pendaftar)
                <tr class="border-t">
                    <td class="px-4 py-2">{{ $pendaftar->lowongan->judul ?? '-' }}</td>
                    <td class="px-4 py-2">{{ $pendaftar->lowongan->company->nama ?? '-' }}</td>
                    <td class="px-4 py-2">{{ $pendaftar->tanggal_daftar->format('d-m-Y') }}</td>
                    <td class="px-4 py-2">{{ $pendaftar->status_lamaran }}</td>
                    <td class="px-4 py-2">{{ $pendaftar->catatan_admin ?? '-' }}</td>
                </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif
</div>

@include('mahasiswa.template.footer')
</body>
</html>
