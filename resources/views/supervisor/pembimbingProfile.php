<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Pembimbing Profile</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-[#f0f6ff]">
<?php include 'template/navbar_pembimbing.php'; ?>

<main class="max-w-xl mx-auto px-6 py-10">
    <div class="bg-white p-8 rounded-xl shadow">
        <div class="flex flex-col items-center mb-8">
            <img src="https://i.ibb.co/N1KzB1H/user-icon.png" alt="Profile" class="w-24 h-24 rounded-full mb-4" />
            <h1 class="text-xl font-bold">Pembimbing TI</h1>
            <p class="text-sm text-gray-500">Nomor Template 1 Pembimbing</p>
        </div>

        <form class="space-y-5">
            <input type="text" placeholder="Email" class="w-full border rounded px-4 py-2" />
            <input type="text" placeholder="No HP" class="w-full border rounded px-4 py-2" />
            <input type="text" placeholder="Occupation" class="w-full border rounded px-4 py-2" />
            <input type="text" placeholder="KHS / SKADA" class="w-full border rounded px-4 py-2" />
            <textarea placeholder="Bio" rows="3" class="w-full border rounded px-4 py-2"></textarea>

            <!-- File Uploads -->
            <?php
            $documents = ['CV', 'PORTFOLIO', 'PROPOSAL', 'RIWAYAT PENYAKIT', 'KTP', 'KTM', 'SURAT ORTU'];
            foreach ($documents as $doc): ?>
                <div>
                    <label class="block text-sm font-semibold mb-1"><?= $doc ?></label>
                    <div class="border-2 border-dashed border-gray-300 rounded p-4 text-center text-sm text-gray-500">
                        Drag and drop or <span class="text-blue-500 cursor-pointer">browse files</span><br/>
                        <span class="text-xs">Size limit: 2MB</span>
                    </div>
                </div>
            <?php endforeach; ?>

            <button type="submit" class="bg-green-500 hover:bg-green-600 text-white font-semibold px-6 py-2 rounded w-full">
                Save & Publish
            </button>
        </form>
    </div>
</main>

</body>
</html>
