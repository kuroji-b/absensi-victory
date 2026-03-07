<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Absensi Hadir</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
        .glass {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
        }
        .status-success {
            animation: successPulse 0.5s ease-out;
        }
        .status-error {
            animation: errorShake 0.4s ease-out;
        }
        .status-warning {
            animation: warningPulse 0.5s ease-out;
        }
        @keyframes successPulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.02); }
            100% { transform: scale(1); }
        }
        @keyframes errorShake {
            0%, 100% { transform: translateX(0); }
            20%, 60% { transform: translateX(-5px); }
            40%, 80% { transform: translateX(5px); }
        }
        @keyframes warningPulse {
            0%, 100% { transform: scale(1); opacity: 1; }
            50% { transform: scale(1.01); opacity: 0.9; }
        }
    </style>
</head>
<body class="min-h-screen bg-gradient-to-br from-gray-50 via-gray-100 to-gray-200 flex items-center justify-center p-4">
    
    <!-- Main Card -->
    <div class="w-full max-w-md">
        <div class="glass shadow-xl rounded-3xl p-8 border border-white/50">
            
            <!-- Header -->
            <div class="text-center mb-8">
                <h1 class="text-3xl font-bold text-gray-700 mb-2">ABSENSI HADIR</h1>
                <p class="text-gray-400 text-sm" id="currentDate"></p>
            </div>

            <!-- Counter -->
            <div class="flex justify-center mb-6">
                <div class="bg-gray-100 rounded-full px-6 py-2 flex items-center gap-2">
                    <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                    <span class="text-gray-600 font-medium" id="attendanceCounter">0 / 0</span>
                    <span class="text-gray-400 text-sm">Hadir</span>
                </div>
            </div>

            <!-- Input Section -->
            <div class="space-y-4">
                <div class="relative">
                    <input 
                        type="text" 
                        id="absenInput" 
                        class="w-full px-6 py-4 text-center text-2xl font-semibold bg-gray-50 border-2 border-gray-200 rounded-2xl focus:outline-none focus:border-gray-400 focus:ring-4 focus:ring-gray-100 transition-all duration-200 placeholder-gray-300"
                        placeholder="Scan QR / Nomor Absen"
                        autocomplete="off"
                        autofocus
                    >
                </div>

                <button 
                    id="submitBtn"
                    class="w-full py-4 bg-gray-600 hover:bg-gray-700 text-white font-semibold rounded-2xl transition-all duration-200 hover:scale-[1.02] active:scale-[0.98] disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center gap-2"
                >
                    <span id="btnText">ABSEN</span>
                    <svg id="btnIcon" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </button>
            </div>

            <!-- Status Message -->
            <div id="statusMessage" class="mt-6 p-4 rounded-2xl text-center hidden transition-all duration-300">
                <div class="flex items-center justify-center gap-2 mb-1">
                    <svg id="statusIcon" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    <span id="statusTitle" class="font-semibold"></span>
                </div>
                <p id="statusDesc" class="text-sm opacity-90"></p>
            </div>

        </div>

        <!-- Footer -->
        <div class="text-center mt-6">
            <a href="admin/login.php" class="text-gray-400 text-sm hover:text-gray-500 transition-colors">
                Login Admin
            </a>
        </div>
    </div>

    <!-- Audio for success sound -->
    <audio id="successSound" preload="auto">
        <source src="data:audio/wav;base64,UklGRnoGAABXQVZFZm10IBAAAAABAAEAQB8AAEAfAAABAAgAZGF0YQoGAACBhYqFbF1fdJivrJBhNjVgodDbq2EcBj+a2teleQYAKaLk5pF0DQAqoujnnX1JAByW5eyeflIAEZjt7ZmBTQAZl/P0m4VRABuW8vOZhU0AHZjx8piHSwAgl/LymYVJACGW8fGZhUkAIJbw8JeGSQAllu/wlYZIACom7+8UhkgAKibv7hSGSAAoJu/t1IZIACgm7+2UhkgAKCbvrRSGSAAoJq+s1IZIACgmryxUhkgAKCZu69SGSAAoJi5rlIZIACgl7iuUhkgAKCXuK1SGSAAoJe3rFIZ" type="audio/wav">
    </audio>

    <script>
        const absenInput = document.getElementById('absenInput');
        const submitBtn = document.getElementById('submitBtn');
        const statusMessage = document.getElementById('statusMessage');
        const statusIcon = document.getElementById('statusIcon');
        const statusTitle = document.getElementById('statusTitle');
        const statusDesc = document.getElementById('statusDesc');
        const attendanceCounter = document.getElementById('attendanceCounter');
        const currentDate = document.getElementById('currentDate');
        const successSound = document.getElementById('successSound');

        // Set current date
        const now = new Date();
        const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
        currentDate.textContent = now.toLocaleDateString('id-ID', options);

        // Load today's attendance count
        async function loadAttendanceCount() {
            try {
                const today = new Date().toISOString().split('T')[0];
                const response = await fetch('get-count.php');
                const result = await response.json();
                if (result.success) {
                    attendanceCounter.textContent = `${result.data.hadir} / ${result.data.total}`;
                }
            } catch (error) {
                console.error('Failed to load count:', error);
            }
        }

        // Show status message
        function showStatus(type, title, desc) {
            statusMessage.classList.remove('hidden', 'bg-green-100', 'text-green-800', 'bg-red-100', 'text-red-800', 'bg-yellow-100', 'text-yellow-800');
            statusMessage.classList.remove('status-success', 'status-error', 'status-warning');
            
            void statusMessage.offsetWidth;
            
            if (type === 'success') {
                statusMessage.classList.add('bg-green-100', 'text-green-800', 'status-success');
                statusIcon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>';
            } else if (type === 'error') {
                statusMessage.classList.add('bg-red-100', 'text-red-800', 'status-error');
                statusIcon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>';
            } else if (type === 'warning') {
                statusMessage.classList.add('bg-yellow-100', 'text-yellow-800', 'status-warning');
                statusIcon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>';
            }
            
            statusTitle.textContent = title;
            statusDesc.textContent = desc;
            statusMessage.classList.remove('hidden');
        }

        // Submit attendance
        async function submitAttendance() {
            const nomorAbsen = absenInput.value.trim();
            
            if (!nomorAbsen) {
                showStatus('error', 'Error', 'Masukkan nomor absen terlebih dahulu');
                absenInput.focus();
                return;
            }

            submitBtn.disabled = true;
            document.getElementById('btnText').textContent = 'Memproses...';
            statusMessage.classList.add('hidden');

            try {
                const formData = new FormData();
                formData.append('nomor_absen', nomorAbsen);

                const response = await fetch('absen.php', {
                    method: 'POST',
                    body: formData
                });

                const result = await response.json();

                if (result.success) {
                    successSound.play().catch(() => {});
                    showStatus('success', 'Berhasil!', `Absensi berhasil atas nama ${result.data.nama}`);
                    loadAttendanceCount();
                } else if (result.data && result.data.status === 'not_found') {
                    showStatus('error', 'Tidak Ditemukan', 'Nomor absen tidak terdaftar');
                } else if (result.data && result.data.status === 'already') {
                    showStatus('warning', 'Sudah Absen', 'Anda sudah absen hari ini');
                } else {
                    showStatus('error', 'Error', result.message || 'Terjadi kesalahan');
                }
            } catch (error) {
                console.error('Error:', error);
                showStatus('error', 'Error', 'Tidak dapat terhubung ke server');
            } finally {
                submitBtn.disabled = false;
                document.getElementById('btnText').textContent = 'ABSEN';
                absenInput.value = '';
                absenInput.focus();
            }
        }

        // Event listeners
        submitBtn.addEventListener('click', submitAttendance);

        absenInput.addEventListener('keypress', (e) => {
            if (e.key === 'Enter') {
                submitAttendance();
            }
        });

        window.addEventListener('load', () => {
            absenInput.focus();
            loadAttendanceCount();
        });

        document.addEventListener('click', () => {
            absenInput.focus();
        });
    </script>
</body>
</html>

