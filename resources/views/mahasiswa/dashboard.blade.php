<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIMMAGANG Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<?php include('../navbar_mahasiswa.php')?>
<body class="bg-blue-50 text-gray-800 pt-20">

<!-- Hero Section -->
<div class="bg-blue-100 py-12 px-6 md:px-20">
    <div class="grid md:grid-cols-2 gap-10 items-center">
        <div>
            <h1 class="text-3xl md:text-4xl font-bold leading-tight mb-4">Lorem ipsum dolor sit amet consectetur. Augue aliquam quis sodales tempor donec.</h1>
            <p class="text-gray-700 mb-6">Nulla malesuada dui fermentum rhoncus feugiat ac aliquam.</p>
            <div class="flex flex-col sm:flex-row gap-4 items-center">
                <select class="px-4 py-2 rounded-md border border-gray-300 w-full sm:w-auto">
                    <option>Regional</option>
                </select>
                <input type="text" placeholder="Sektor bidang" class="px-4 py-2 rounded-md border border-gray-300 w-full sm:w-auto" />
                <button class="bg-blue-600 text-white px-6 py-2 rounded-md">Show Jobs</button>
            </div>
        </div>
        <div class="w-full h-48 bg-gray-300 rounded-xl"></div>
    </div>
</div>

<!-- Job Cards Section -->
<div class="px-6 md:px-20">
    <?php include('jobcard.php'); ?>
</div>

<!-- Statistics Section -->
<div class="grid grid-cols-3 gap-4 text-center my-12 px-6 md:px-20">
    <div class="bg-white rounded-xl py-6 shadow">
        <p class="text-3xl font-bold text-blue-600">10</p>
        <p class="text-sm">Perusahaan</p>
    </div>
    <div class="bg-white rounded-xl py-6 shadow">
        <p class="text-3xl font-bold text-blue-600">15</p>
        <p class="text-sm">Lowongan</p>
    </div>
    <div class="bg-white rounded-xl py-6 shadow">
        <p class="text-3xl font-bold text-blue-600">10</p>
        <p class="text-sm">Pendaftar</p>
    </div>
</div>

<?php include '../footer.php'; ?>
</body>
</html>