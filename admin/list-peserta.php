<?php
/**
 * List Peserta Page
 */

require_once '../config.php';

// Check if logged in
if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit;
}

// Handle delete
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $stmt = $conn->prepare("DELETE FROM peserta WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
    header('Location: list-peserta.php');
    exit;
}

// Get all participants
$result = $conn->query("SELECT * FROM peserta ORDER BY nomor_absen ASC");
$pesertaList = $result;
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>List Peserta - Absensi</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="min-h-screen bg-gradient-to-br from-gray-50 via-gray-100 to-gray-200">
    
    <div class="flex min-h-screen">
        <!-- Sidebar -->
        <aside class="w-64 bg-white shadow-lg hidden md:block">
            <div class="p-6">
                <h2 class="text-xl font-bold text-gray-700">Admin Panel</h2>
                <p class="text-gray-400 text-sm">Absensi Online</p>
            </div>
            <nav class="mt-6">
                <a href="dashboard.php" class="flex items-center gap-3 px-6 py-3 text-gray-600 hover:bg-gray-50 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                    </svg>
                    Dashboard
                </a>
                <a href="tambah-peserta.php" class="flex items-center gap-3 px-6 py-3 text-gray-600 hover:bg-gray-50 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                    </svg>
                    Tambah Peserta
                </a>
                <a href="list-peserta.php" class="flex items-center gap-3 px-6 py-3 bg-gray-100 text-gray-700 border-r-4 border-gray-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                    </svg>
                    List Peserta
                </a>
                <a href="rekap.php" class="flex items-center gap-3 px-6 py-3 text-gray-600 hover:bg-gray-50 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    Rekap
                </a>
            </nav>
            <div class="absolute bottom-0 w-64 p-6">
                <a href="logout.php" class="flex items-center gap-3 px-4 py-2 text-red-600 hover:bg-red-50 rounded-xl transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                    </svg>
                    Logout
                </a>
            </div>
        </aside>

        <!-- Mobile Header -->
        <div class="flex-1 md:ml-64">
            <header class="bg-white shadow-sm p-4 md:hidden flex justify-between items-center">
                <span class="font-bold text-gray-700">Admin Absensi</span>
                <a href="logout.php" class="text-red-600 text-sm">Logout</a>
            </header>

            <!-- Main Content -->
            <main class="p-6">
                <div class="flex justify-between items-center mb-6">
                    <h1 class="text-2xl font-bold text-gray-700">List Peserta</h1>
                    <a href="tambah-peserta.php" class="px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-xl transition-colors">
                        + Tambah Peserta
                    </a>
                </div>

                <!-- Table -->
                <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
                    <?php if ($pesertaList->num_rows > 0): ?>
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">No</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nomor Absen</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                <?php $no = 1; while ($row = $pesertaList->fetch_assoc()): ?>
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-6 py-4 text-sm text-gray-600"><?php echo $no++; ?></td>
                                    <td class="px-6 py-4 text-sm font-medium text-gray-700"><?php echo htmlspecialchars($row['nomor_absen']); ?></td>
                                    <td class="px-6 py-4 text-sm text-gray-700"><?php echo htmlspecialchars($row['nama']); ?></td>
                                    <td class="px-6 py-4">
                                        <a href="?delete=<?php echo $row['id']; ?>" 
                                           class="text-red-600 hover:text-red-800 text-sm"
                                           onclick="return confirm('Yakin ingin menghapus peserta ini?')">
                                            Hapus
                                        </a>
                                    </td>
                                </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                    <?php else: ?>
                    <div class="p-6 text-center text-gray-400">
                        <p>Belum ada peserta</p>
                        <a href="tambah-peserta.php" class="text-gray-600 hover:underline mt-2 inline-block">Tambah peserta pertama</a>
                    </div>
                    <?php endif; ?>
                </div>

                <!-- Back Button -->
                <div class="mt-6">
                    <a href="dashboard.php" class="text-gray-500 hover:text-gray-700 transition-colors">
                        ← Kembali ke Dashboard
                    </a>
                </div>
            </main>
        </div>
    </div>

</body>
</html>

