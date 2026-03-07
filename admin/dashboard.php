<?php
/**
 * Admin Dashboard
 */

require_once '../config.php';

// Check if logged in
if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit;
}

$tanggal = date('Y-m-d');

// Get today's attendance count
$stmt = $conn->prepare("SELECT COUNT(*) as total FROM absensi WHERE tanggal = ?");
$stmt->bind_param("s", $tanggal);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$hadir = $row['total'];
$stmt->close();

// Get total participants
$result = $conn->query("SELECT COUNT(*) as total FROM peserta");
$row = $result->fetch_assoc();
$totalPeserta = $row['total'];
$stmt->close();

// Get today's attendance list
$stmt = $conn->prepare("
    SELECT a.nomor_absen, p.nama, a.jam 
    FROM absensi a 
    LEFT JOIN peserta p ON a.nomor_absen = p.nomor_absen 
    WHERE a.tanggal = ? 
    ORDER BY a.jam DESC
");
$stmt->bind_param("s", $tanggal);
$stmt->execute();
$absensiList = $stmt->get_result();
$stmt->close();

?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - Absensi</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="min-h-screen bg-gradient-to-br from-gray-50 via-gray-100 to-gray-200">
    
    <!-- Sidebar -->
    <div class="flex min-h-screen">
        <aside class="w-64 bg-white shadow-lg hidden md:block">
            <div class="p-6">
                <h2 class="text-xl font-bold text-gray-700">Admin Panel</h2>
                <p class="text-gray-400 text-sm">Absensi Online</p>
            </div>
            <nav class="mt-6">
                <a href="dashboard.php" class="flex items-center gap-3 px-6 py-3 bg-gray-100 text-gray-700 border-r-4 border-gray-600">
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
                <a href="list-peserta.php" class="flex items-center gap-3 px-6 py-3 text-gray-600 hover:bg-gray-50 transition-colors">
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
                <h1 class="text-2xl font-bold text-gray-700 mb-6">Dashboard</h1>

                <!-- Stats Cards -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                    <div class="bg-white rounded-2xl shadow-lg p-6">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center">
                                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <div>
                                <p class="text-gray-400 text-sm">Hadir Hari Ini</p>
                                <p class="text-2xl font-bold text-gray-700"><?php echo $hadir; ?></p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-2xl shadow-lg p-6">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center">
                                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                                </svg>
                            </div>
                            <div>
                                <p class="text-gray-400 text-sm">Total Peserta</p>
                                <p class="text-2xl font-bold text-gray-700"><?php echo $totalPeserta; ?></p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-2xl shadow-lg p-6">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center">
                                <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                            <div>
                                <p class="text-gray-400 text-sm">Tanggal</p>
                                <p class="text-2xl font-bold text-gray-700"><?php echo date('d/m/Y'); ?></p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Today's Attendance List -->
                <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
                    <div class="p-6 border-b border-gray-100">
                        <h2 class="text-lg font-semibold text-gray-700">Absensi Hari Ini</h2>
                        <p class="text-gray-400 text-sm"><?php echo date('l, d F Y', strtotime($tanggal)); ?></p>
                    </div>
                    
                    <?php if ($absensiList->num_rows > 0): ?>
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">No</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nomor Absen</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Jam</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                <?php $no = 1; while ($row = $absensiList->fetch_assoc()): ?>
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-6 py-4 text-sm text-gray-600"><?php echo $no++; ?></td>
                                    <td class="px-6 py-4 text-sm font-medium text-gray-700"><?php echo htmlspecialchars($row['nomor_absen']); ?></td>
                                    <td class="px-6 py-4 text-sm text-gray-700"><?php echo htmlspecialchars($row['nama'] ?? 'Unknown'); ?></td>
                                    <td class="px-6 py-4 text-sm text-gray-600"><?php echo date('H:i', strtotime($row['jam'])); ?></td>
                                </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                    <?php else: ?>
                    <div class="p-6 text-center text-gray-400">
                        <p>Belum ada absensi hari ini</p>
                    </div>
                    <?php endif; ?>
                </div>
            </main>
        </div>
    </div>

</body>
</html>

