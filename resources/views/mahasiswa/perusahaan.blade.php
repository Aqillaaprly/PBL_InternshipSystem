<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIMMAGANG Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

@include('mahasiswa.template.navbar')
<body class="bg-blue-50 text-gray-800 pt-20">

@include('mahasiswa.jobcardCompany')

<div class="pt-20 pb-10 px-4 md:px-10 max-w-7xl mx-auto">
    <?php
    $companies = [
        [
            'name' => 'Payfazz',
            'site' => 'https://www.payfazz.com/',
            'image' => 'https://www.pixelstalk.net/wp-content/uploads/2016/05/Images-New-York-City-Backgrounds.jpg',
            'description' => 'Payfazz adalah platform keuangan digital yang berfokus pada inklusi keuangan di Indonesia...',
            'jobs' => [
                [
                    'title' => 'Backend Developer Intern',
                    'location' => 'Jakarta',
                    'closeDate' => '10 Aug 2025',
                    'startDate' => '1 Sep 2025',
                    'qualifications' => ['Computer Science', 'Software Engineering']
                ]
            ]
        ],
        [
            'name' => 'Astra',
            'site' => 'https://www.astra.co.id/',
            'image' => 'https://www.pixelstalk.net/wp-content/uploads/2016/05/Images-New-York-City-Backgrounds.jpg',
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
            'name' => 'Mayora',
            'site' => 'https://www.mayora.com/',
            'image' => 'https://www.pixelstalk.net/wp-content/uploads/2016/05/Images-New-York-City-Backgrounds.jpg',
            'description' => 'Mayora adalah perusahaan FMCG yang memproduksi berbagai makanan dan minuman terkenal...',
            'jobs' => [
                [
                    'title' => 'Marketing Analyst',
                    'location' => 'Tangerang',
                    'closeDate' => '12 Aug 2025',
                    'startDate' => '1 Sep 2025',
                    'qualifications' => ['Marketing', 'Statistics']
                ]
            ]
        ],
        [
            'name' => 'Bank Mandiri',
            'site' => 'https://www.bankmandiri.co.id/',
            'image' => 'https://www.pixelstalk.net/wp-content/uploads/2016/05/Images-New-York-City-Backgrounds.jpg',
            'description' => 'Bank Mandiri adalah salah satu bank terbesar di Indonesia...',
            'jobs' => [
                [
                    'title' => 'Finance Intern',
                    'location' => 'Jakarta Pusat',
                    'closeDate' => '8 Aug 2025',
                    'startDate' => '15 Aug 2025',
                    'qualifications' => ['Finance', 'Accounting']
                ]
            ]
        ],
        [
            'name' => 'Indofood',
            'site' => 'https://www.indofood.com/',
            'image' => 'https://www.pixelstalk.net/wp-content/uploads/2016/05/Images-New-York-City-Backgrounds.jpg',
            'description' => 'Indofood adalah produsen makanan dan minuman ternama di Indonesia...',
            'jobs' => [
                [
                    'title' => 'Supply Chain Intern',
                    'location' => 'Bekasi',
                    'closeDate' => '20 Aug 2025',
                    'startDate' => '1 Sep 2025',
                    'qualifications' => ['Logistics', 'Industrial Engineering']
                ]
            ]
        ],
        [
            'name' => 'Paragon Technology',
            'site' => 'https://www.paragon-innovation.com/',
            'image' => 'https://www.pixelstalk.net/wp-content/uploads/2016/05/Images-New-York-City-Backgrounds.jpg',
            'description' => 'Paragon Technology memproduksi brand kosmetik seperti Wardah dan Emina...',
            'jobs' => [
                [
                    'title' => 'R&D Intern',
                    'location' => 'Jakarta',
                    'closeDate' => '25 Aug 2025',
                    'startDate' => '10 Sep 2025',
                    'qualifications' => ['Pharmacy', 'Chemical Engineering']
                ]
            ]
        ],
        [
            'name' => 'Kawan Lama Sejahtera',
            'site' => 'https://www.kawanlamagroup.com/',
            'image' => 'https://www.pixelstalk.net/wp-content/uploads/2016/05/Images-New-York-City-Backgrounds.jpg',
            'description' => 'Kawan Lama Sejahtera adalah perusahaan ritel dan distribusi peralatan teknik dan industri...',
            'jobs' => [
                [
                    'title' => 'Sales Engineering Intern',
                    'location' => 'Surabaya',
                    'closeDate' => '30 Aug 2025',
                    'startDate' => '10 Sep 2025',
                    'qualifications' => ['Mechanical Engineering', 'Marketing']
                ]
            ]
        ],
        [
            'name' => 'Indomaret',
            'site' => 'https://www.indomaret.co.id/',
            'image' => 'https://www.pixelstalk.net/wp-content/uploads/2016/05/Images-New-York-City-Backgrounds.jpg',
            'description' => 'Indomaret adalah jaringan minimarket yang tersebar luas di seluruh Indonesia...',
            'jobs' => [
                [
                    'title' => 'Retail Management Trainee',
                    'location' => 'Depok',
                    'closeDate' => '2 Sep 2025',
                    'startDate' => '15 Sep 2025',
                    'qualifications' => ['Business Administration', 'Retail Management']
                ]
            ]
        ]
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
                        $job['qualifications'],
                        $company['site']
                    );
                    ?>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endforeach; ?>
</div>

@include('mahasiswa.template.footer')
</body>
</html>
