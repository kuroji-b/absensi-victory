# 📅 Absensi Online

Sistem absensi online sederhana dengan maksimal 200 peserta, absensi 1x per hari, dan rekap otomatis ke Google Sheets.

## 🏗️ Arsitektur

```
absensi-app/
├── backend/           # Node.js + Express API (Deploy ke Render)
│   ├── server.js
│   ├── db.js
│   ├── controllers/
│   ├── routes/
│   ├── middleware/
│   ├── schema.sql     # Database schema
│   └── .env           # Environment variables
│
└── frontend/          # HTML + TailwindCSS (Deploy ke Vercel)
    ├── index.html     # Halaman Absensi
    ├── admin.html     # Halaman Admin
    └── tailwind.config.js
```

## ⚡ Fitur

- ✅ Absensi via nomor absen manual
- ✅ Scan QR menggunakan scanner fisik USB
- ✅ Pendaftaran peserta oleh admin
- ✅ Data absensi tersimpan harian (tanggal + jam)
- ✅ Rekap otomatis ke Google Sheets
- ✅ Dashboard admin dengan statistik
- ✅ Rate limiting untuk keamanan
- ✅ JWT Authentication

## 🛠️ Tech Stack

| Layer | Technology |
|-------|------------|
| Frontend | HTML, TailwindCSS, Vanilla JS |
| Backend | Node.js, Express.js |
| Database | PostgreSQL (Supabase) |
| Hosting | Vercel (Frontend), Render (Backend) |

## 🚀 Quick Start

### Prerequisites

- Node.js 18+
- npm atau yarn
- Akun Supabase
- Akun Render
- Akun Vercel

### Local Development

1. **Clone repositori**

2. **Setup Database**
   - Buka Supabase SQL Editor
   - Eksekusi `backend/schema.sql`

3. **Setup Backend**
   ```bash
   cd backend
   npm install
   cp .env.example .env
   # Edit .env dengan credentials Anda
   npm run dev
   ```

4. **Setup Frontend**
   - Buka `frontend/index.html` di browser
   - Atau gunakan Live Server

## 📝 API Endpoints

| Method | Endpoint | Description |
|--------|----------|-------------|
| POST | `/api/absen` | Submit absensi |
| GET | `/api/today/count` | Jumlah hadir hari ini |
| POST | `/api/admin/login` | Login admin |
| POST | `/api/admin/participant` | Tambah peserta |
| GET | `/api/admin/participants` | List semua peserta |
| GET | `/api/admin/attendance` | Rekap absensi |
| DELETE | `/api/admin/participant/:id` | Hapus peserta |
| GET | `/api/admin/stats/today` | Stats hari ini |

## 🔐 Default Credentials

- **Username:** admin
- **Password:** admin123

## 📄 License

MIT License

---

**Made with ❤️**

