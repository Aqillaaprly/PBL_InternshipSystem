<?php
session_start();
// if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'perusahaan') {
//     header('Location: ../index.php');
//     exit;
// }

// require '../koneksi.php';

// $keyword = $_GET['search'] ?? '';
// $query = "SELECT * FROM lowongan WHERE 1";

// // Tambahkan filter pencarian jika keyword ada
// if (!empty($keyword)) {
//     $keyword = mysqli_real_escape_string($conn, $keyword);
//     $query .= " AND (judul LIKE '%$keyword%' OR deskripsi LIKE '%$keyword%' OR syarat LIKE '%$keyword%')";
// }

// $result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Data Lowongan - SIMMAGANG</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-blue-50 text-gray-800">

  <!-- Navbar -->
  <?php include('template/navbar_perusahaan.php'); ?>

  <!-- Main Content -->
  <main class="max-w-screen-xl mx-auto px-8 py-12 mt-6">
    <div class="bg-white p-8 rounded-xl shadow">
      <div class="flex justify-between items-center pb-4">
        <h1 class="text-2xl font-bold text-blue-800 ml-8">Data Lowongan</h1>
        <form method="GET" class="flex space-x-2">
          <input type="text" name="search" value="<?= htmlspecialchars($keyword) ?>" placeholder="Cari lowongan..." class="border border-gray-300 rounded px-4 py-2" />
          <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Filter</button>
          <a href="tambah_lowongan.php" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">+ Tambah</a>
        </form>
      </div>

      <div class="overflow-x-auto">
        <table class="min-w-full text-sm text-center">
          <thead class="bg-gray-100">
            <tr>
              <th class="px-5 py-3">No</th>
              <th class="px-5 py-3">Judul</th>
              <th class="px-5 py-3">Deskripsi</th>
              <th class="px-5 py-3">Syarat</th>
              <th class="px-5 py-3">Batas Pendaftaran</th>
              <th class="px-5 py-3">Action</th>
            </tr>
          </thead>
          <tbody>
            <?php
            if (mysqli_num_rows($result) > 0):
              $no = 1;
              while ($row = mysqli_fetch_assoc($result)):
            ?>
              <tr class="border-b">
                <td class="px-5 py-3"><?= $no++ ?></td>
                <td class="px-5 py-3"><?= htmlspecialchars($row['judul']) ?></td>
                <td class="px-5 py-3"><?= htmlspecialchars(substr($row['deskripsi'], 0, 50)) ?>...</td>
                <td class="px-5 py-3"><?= htmlspecialchars($row['syarat']) ?></td>
                <td class="px-5 py-3"><?= htmlspecialchars($row['batas_pendaftaran']) ?></td>
                <td class="px-5 py-3 space-x-1">
                  <a href="#" class="bg-blue-100 text-blue-600 text-xs font-medium px-3 py-1 rounded hover:bg-blue-200">Show</a>
                  <a href="#" class="bg-yellow-100 text-yellow-600 text-xs font-medium px-3 py-1 rounded hover:bg-yellow-200">Edit</a>
                  <a href="#" class="bg-red-100 text-red-600 text-xs font-medium px-3 py-1 rounded hover:bg-red-200">Delete</a>
                </td>
              </tr>
            <?php
              endwhile;
            else:
            ?>
              <tr>
                <td colspan="6" class="py-4 text-gray-500">Tidak ada data ditemukan.</td>
              </tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>
            </main>

    <!-- Footer -->
  <?php include('template/footer.php'); ?>
    <!-- Dropdown JS -->
    <script>
        const profileBtn = document.getElementById('profileBtn');
        const profileDropdown = document.getElementById('profileDropdown');

        profileBtn?.addEventListener('click', () => {
            profileDropdown.classList.toggle('hidden');
        });

        document.addEventListener('click', (e) => {
            if (!profileBtn.contains(e.target) && !profileDropdown.contains(e.target)) {
                profileDropdown.classList.add('hidden');
            }
        });
    </script>
</body>

</html>