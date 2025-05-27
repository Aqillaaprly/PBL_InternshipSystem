<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Daftar Mahasiswa Bimbingan</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <style>
        .status-badge {
            padding: 4px 8px;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 600;
        }
        .status-aktif { background-color: #d1f7c4; color: #2e7d32; }
        .status-skripsi { background-color: #ffe7a9; color: #ff8f00; }
        .status-tidak-aktif { background-color: #ffcccc; color: #c62828; }
    </style>
</head>
<body class="bg-blue-50 text-gray-800">
<?php include 'template/navbar_admin.php'; ?>

<main class="pt-24 px-6 max-w-7xl mx-auto">
    <div class="bg-white rounded-xl shadow p-6">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-lg font-bold text-gray-800">Mahasiswa Bimbingan</h2>
            <div class="flex gap-2">
                <input type="text" placeholder="Search" class="px-4 py-2 border rounded-md">
                <button class="bg-gray-200 px-4 py-2 rounded-md">Filter</button>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full text-sm border-collapse">
                <thead class="bg-blue-100 text-blue-800">
                <tr>
                    <th class="px-4 py-2">#</th>
                    <th class="px-4 py-2">Nama</th>
                    <th class="px-4 py-2">Pelaksanaan</th>
                    <th class="px-4 py-2">Perusahaan</th>
                    <th class="px-4 py-2">Tanggal</th>
                    <th class="px-4 py-2">Status</th>
                    <th class="px-4 py-2">Aksi</th>
                </tr>
                </thead>
                <tbody class="text-gray-700">
                <?php for ($i = 1; $i <= 10; $i++): ?>
                    <tr class="border-t text-center">
                        <td class="px-4 py-2"><?= $i ?></td>
                        <td class="px-4 py-2">Mhs<?= $i ?></td>
                        <td class="px-4 py-2">Tahun Pelaksanaan 202<?= $i % 3 + 1 ?></td>
                        <td class="px-4 py-2">PT. Sample</td>
                        <td class="px-4 py-2">2024-06-0<?= $i ?></td>
                        <td class="px-4 py-2">
                            <?php
                            $statusClass = ($i % 3 == 0) ? 'status-tidak-aktif' : (($i % 2 == 0) ? 'status-skripsi' : 'status-aktif');
                            $statusText = ($i % 3 == 0) ? 'Tidak Aktif' : (($i % 2 == 0) ? 'Skripsi' : 'Aktif');
                            ?>
                            <span class="status-badge <?= $statusClass ?>"><?= $statusText ?></span>
                        </td>
                        <td class="px-4 py-2">
                            <div class="dropdown">
                                <button class="btn btn-link p-0 dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                    <strong>⋯</strong>
                                </button>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#reportModal">View Report Log</a></li>
                                    <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#feedbackModal">Give Feedback</a></li>
                                </ul>
                            </div>
                        </td>
                    </tr>
                <?php endfor; ?>
                </tbody>
            </table>
        </div>

        <div class="mt-4 flex justify-between items-center">
            <span class="text-sm text-gray-600">Showing 1 to 10 of 10 entries</span>
            <div class="flex space-x-2">
                <button class="px-3 py-1 bg-blue-600 text-white rounded-md">1</button>
                <button class="px-3 py-1 bg-gray-200 rounded-md">2</button>
            </div>
        </div>
    </div>
</main>

<!-- Feedback Modal -->
<div class="modal fade" id="feedbackModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Give Feedback to MHS Magang</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <textarea class="form-control" rows="6" placeholder="Enter your feedback..."></textarea>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button class="btn btn-primary">Post Feedback</button>
            </div>
        </div>
    </div>
</div>

<!-- Report Log Modal -->
<div class="modal fade" id="reportModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">MHS Magang Report Log</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <table class="table table-bordered">
                    <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>Report</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td>2025/01/12</td>
                        <td>
                            <a href="#">My Horrible Day</a>
                            <img src="assets/pdf-icon.png" alt="PDF" class="ms-2" style="width: 20px; height: 20px;">
                        </td>
                    </tr>
                    <!-- More rows can be added -->
                    </tbody>
                </table>
                <nav>
                    <ul class="pagination justify-content-center">
                        <li class="page-item"><a class="page-link" href="#">Prev</a></li>
                        <li class="page-item active"><a class="page-link" href="#">1</a></li>
                        <li class="page-item"><a class="page-link" href="#">2</a></li>
                        <li class="page-item"><a class="page-link" href="#">3</a></li>
                        <li class="page-item"><a class="page-link" href="#">Next</a></li>
                    </ul>
                </nav>
            </div>
        </div>
    </div>
</div>
</body>
</html>
