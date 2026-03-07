<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin - Absensi</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
    </style>
</head>
<body class="min-h-screen bg-gradient-to-br from-gray-50 via-gray-100 to-gray-200 flex items-center justify-center p-4">
    
    <div class="w-full max-w-md">
        <div class="bg-white shadow-xl rounded-3xl p-8 border border-gray-100">
            <!-- Header -->
            <div class="text-center mb-8">
                <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                    </svg>
                </div>
                <h1 class="text-2xl font-bold text-gray-700">Login Admin</h1>
                <p class="text-gray-400 text-sm mt-1">Masukkan kredensial untuk mengakses</p>
            </div>

            <!-- Error Message -->
            <?php if (isset($_SESSION['error'])): ?>
                <div class="bg-red-100 text-red-800 p-4 rounded-xl mb-6 text-sm">
                    <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
                </div>
            <?php endif; ?>

            <!-- Login Form -->
            <form method="POST" action="login-process.php" class="space-y-4">
                <div>
                    <label class="block text-gray-600 text-sm font-medium mb-2">Username</label>
                    <input 
                        type="text" 
                        name="username"
                        class="w-full px-4 py-3 bg-gray-50 border-2 border-gray-200 rounded-xl focus:outline-none focus:border-gray-400 transition-all duration-200"
                        placeholder="Masukkan username"
                        required
                    >
                </div>

                <div>
                    <label class="block text-gray-600 text-sm font-medium mb-2">Password</label>
                    <input 
                        type="password" 
                        name="password"
                        class="w-full px-4 py-3 bg-gray-50 border-2 border-gray-200 rounded-xl focus:outline-none focus:border-gray-400 transition-all duration-200"
                        placeholder="Masukkan password"
                        required
                    >
                </div>

                <button 
                    type="submit"
                    class="w-full py-3 bg-gray-600 hover:bg-gray-700 text-white font-semibold rounded-xl transition-all duration-200 hover:scale-[1.02] active:scale-[0.98]"
                >
                    Login
                </button>
            </form>

            <!-- Back Link -->
            <div class="text-center mt-6">
                <a href="../index.php" class="text-gray-400 text-sm hover:text-gray-500 transition-colors">
                    ← Kembali ke Halaman Absensi
                </a>
            </div>
        </div>
    </div>

</body>
</html>

