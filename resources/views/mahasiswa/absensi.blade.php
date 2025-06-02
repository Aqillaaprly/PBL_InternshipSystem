<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Form Absensi</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
@include('mahasiswa.template.navbar')
<body class="bg-gray-50 text-gray-800 pt-20">

<div class="max-w-5xl mx-auto p-4">
    <div class="bg-white rounded-xl shadow-md p-6">
        <!-- Header -->
        <div class="mb-6">
            <h1 class="text-2xl font-semibold">Form Absensi</h1>
            <p class="text-sm text-gray-500">Silakan isi form dan submit absensi.</p>
        </div>

        <!-- Alert Box -->
        <div id="alertBox" class="hidden mb-4 p-4 rounded-lg text-white font-semibold"></div>

        <!-- Form -->
        <form id="absensiForm" class="space-y-4 mb-6">
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm text-gray-600">Email</label>
                    <input type="email" name="email" class="w-full px-4 py-2 border rounded-md mt-1" />
                </div>

                <div>
                    <label class="block text-sm text-gray-600">Nama</label>
                    <input type="text" name="nama" class="w-full px-4 py-2 border rounded-md mt-1" />
                </div>

                <div>
                    <label class="block text-sm text-gray-600">Kelas</label>
                    <input type="text" name="kelas" class="w-full px-4 py-2 border rounded-md mt-1" />
                </div>

                <div>
                    <label class="block text-sm text-gray-600">Perusahaan</label>
                    <input type="text" name="perusahaan" class="w-full px-4 py-2 border rounded-md mt-1" />
                </div>

                <div class="col-span-2">
                    <label class="block text-sm text-gray-600">Kegiatan</label>
                    <input type="text" name="kegiatan" class="w-full px-4 py-2 border rounded-md mt-1" />
                </div>

                <div class="col-span-2">
                    <label class="block text-sm text-gray-600">Foto</label>
                    <input type="file" name="foto" class="block mt-1" />
                </div>
            </div>

            <button type="submit" class="mt-4 bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-2 rounded">
                Submit Absensi
            </button>
        </form>

    </div>
</div>

@include('mahasiswa.template.footer')

<!-- Script -->
<script>
    const absensiForm = document.getElementById('absensiForm');
    const tableBody = document.getElementById('tableBody');
    const alertBox = document.getElementById('alertBox');

    function showAlert(message, type = 'success') {
        alertBox.className = `mb-4 p-4 rounded-lg font-semibold ${
            type === 'success' ? 'bg-green-500' : 'bg-red-500'
        } text-white`;
        alertBox.textContent = message;
        alertBox.classList.remove('hidden');
        setTimeout(() => alertBox.classList.add('hidden'), 3000);
    }

    absensiForm.addEventListener('submit', (e) => {
        e.preventDefault();
        const formData = new FormData(absensiForm);
        const email = formData.get('email');
        const nama = formData.get('nama');
        const kelas = formData.get('kelas');
        const perusahaan = formData.get('perusahaan');
        const kegiatan = formData.get('kegiatan');
        const fotoInput = absensiForm.querySelector('input[name="foto"]');
        const foto = fotoInput.files.length ? fotoInput.files[0].name : null;

        // Validation
        if (!email || !nama || !kelas || !perusahaan || !kegiatan || !foto) {
            showAlert('Semua field wajib diisi!', 'error');
            return;
        }

        // Reset and show success
        absensiForm.reset();
        showAlert('Absensi berhasil disubmit!', 'success');
    });
</script>

</body>
</html>
