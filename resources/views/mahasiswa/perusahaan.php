<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIMMAGANG Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<?php include('template/navbar_mahasiswa.php')?>
<body class="bg-blue-50 text-gray-800 pt-20">

<?php include 'jobcardCompany.php'; ?>

<div class="pt-20 pb-10 px-4 md:px-10 max-w-7xl mx-auto">
    <?php
    $companies = [
        [
            'name' => 'Astra',
            'image' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/f/fd/Astra_International_Tbk.svg/2560px-Astra_International_Tbk.svg.png',
            'description' => 'Dikenal sebagai salah satu perusahaan otomotif terkemuka di Indonesia...',
            'jobs' => [
                [
                    'title' => 'Graduate Program (Start ASAP)',
                    'location' => 'Jakarta Selatan',
                    'closeDate' => '4 Aug 2025',
                    'startDate' => 'ASAP',
                    'qualifications' => ['Business & Management', 'Engineering & Mathematics']
                ],
                [
                    'title' => 'Software Engineer Internship',
                    'location' => 'Jakarta Barat',
                    'closeDate' => '15 Aug 2025',
                    'startDate' => '1 Sep 2025',
                    'qualifications' => ['Computer Science', 'Information Systems']
                ]
            ]
        ],
        [
            'name' => 'Telkom Indonesia',
            'image' => 'https://www.telkom.co.id/data/image_upload/page/1593581633096_compress_Logo-Telkom-Indonesia.png',
            'description' => 'Telkom Indonesia adalah perusahaan informasi dan komunikasi serta penyedia jasa dan jaringan telekomunikasi...',
            'jobs' => [
                [
                    'title' => 'Data Analyst Intern',
                    'location' => 'Bandung',
                    'closeDate' => '1 Sep 2025',
                    'startDate' => '15 Sep 2025',
                    'qualifications' => ['Statistics', 'Data Science']
                ],
                [
                    'title' => 'Product Manager Trainee',
                    'location' => 'Surabaya',
                    'closeDate' => '20 Aug 2025',
                    'startDate' => '1 Oct 2025',
                    'qualifications' => ['Business', 'IT Management']
                ]
            ]
        ],
        // Add more companies as needed
    ];

    foreach ($companies as $company): ?>
        <div class="bg-white rounded-lg shadow-md p-6 mb-10">
            <!-- Company Header -->
            <div class="mb-6">
                <img src="<?= htmlspecialchars($company['image']); ?>" alt="<?= htmlspecialchars($company['name']); ?>" class="w-full max-h-64 object-cover rounded-md mb-4">
                <h2 class="text-2xl font-bold text-blue-800 mb-2"><?= htmlspecialchars($company['name']); ?></h2>
                <p class="text-gray-700"><?= htmlspecialchars($company['description']); ?></p>
            </div>

            <!-- Jobs Section -->
            <div>
                <h3 class="text-xl font-semibold text-gray-800 mb-4"><?= $company['name']; ?> Graduate Jobs & Opportunities</h3>
                <?php foreach ($company['jobs'] as $job): ?>
                    <?php
                    renderJobCard(
                        $job['title'],
                        $job['location'],
                        $job['closeDate'],
                        $job['startDate'],
                        $job['qualifications']
                    );
                    ?>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endforeach; ?>
</div>

<?php include 'template/footer.php'; ?>
</body>
</html>
