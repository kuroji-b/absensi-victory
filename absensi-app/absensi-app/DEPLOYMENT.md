# 📋 PANDUAN DEPLOYMENT ABSENSI ONLINE

## 📁 Struktur Project

```
absensi-app/
├── backend/           # Node.js + Express API
│   ├── server.js
│   ├── db.js
│   ├── controllers/
│   ├── routes/
│   ├── middleware/
│   ├── .env
│   ├── package.json
│   └── schema.sql
│
└── frontend/          # HTML + TailwindCSS
    ├── index.html     # Halaman Absensi
    ├── admin.html     # Halaman Admin
    └── tailwind.config.js
```

---

## 🚀 LANGKAH 1: SETUP SUPABASE (DATABASE)

### 1.1 Buat Akun Supabase
1. Buka https://supabase.com
2. Klik "Start your project"
3. Buat project baru dengan nama `absensi-app`
4. Simpan password database yang diberikan

### 1.2 Setup Database
1. Buka **SQL Editor** di dashboard Supabase
2. Copy isi file `backend/schema.sql`
3. Paste dan execute
4. Jika berhasil, akan muncul pesan "Success"

### 1.3 Dapatkan Connection String
1. Buka **Settings** → **Database**
2. Cari **Connection string** (Node.js)
3. Format akan seperti:
   ```
   postgres://postgres:[password]@db.[project-ref].supabase.co:5432/postgres
   ```

---

## 🚀 LANGKAH 2: SETUP GOOGLE SHEETS (WEBHOOK)

### 2.1 Buat Google Sheet Baru
1. Buka https://sheets.google.com
2. Buat sheet baru
3. Rename sheet pertama menjadi "Absensi"
4. Tambahkan header di baris 1:
   - Kolom A: Tanggal
   - Kolom B: Jam
   - Kolom C: Nomor Absen
   - Kolom D: Nama

### 2.2 Buka Apps Script
1. Klik **Extensions** → **Apps Script**
2. Hapus kode yang ada dan paste kode berikut:

```javascript
const SHEET_NAME = "Absensi";

function doPost(e) {
  const sheet = SpreadsheetApp.getActiveSpreadsheet().getSheetByName(SHEET_NAME);
  
  try {
    const data = JSON.parse(e.postData.contents);
    
    sheet.appendRow([
      data.tanggal,
      data.jam,
      data.nomor,
      data.nama
    ]);
    
    return ContentService.createTextOutput(JSON.stringify({
      success: true,
      message: 'Data received'
    })).setMimeType(ContentService.MimeType.JSON);
    
  } catch (error) {
    return ContentService.createTextOutput(JSON.stringify({
      success: false,
      error: error.toString()
    })).setMimeType(ContentService.MimeType.JSON);
  }
}
```

3. Klik **Save** (icon floppy disk)
4. Klik **Deploy** → **New deployment**
5. Klik icon gear → **Web app**
6. Configure:
   - Description: Absensi Webhook
   - Execute as: Me
   - Who has access: Anyone
7. Klik **Deploy**
8. Copy **Web app URL**

---

## 🚀 LANGKAH 3: DEPLOY BACKEND KE RENDER

### 3.1 Setup GitHub Repository
1. Buat folder `absensi-app` di komputer lokal
2. Upload semua file backend ke GitHub:
   - Buat repository baru di GitHub
   - Push folder `backend/` saja

### 3.2 Deploy ke Render
1. Buka https://dashboard.render.com
2. Klik **New** → **Web Service**
3. Connect GitHub repository yang baru dibuat
4. Configure:
   - Name: `absensi-backend`
   - Environment: `Node`
   - Build Command: `npm install`
   - Start Command: `node server.js`
5. Klik **Advanced**
6. Tambahkan Environment Variables:
   - `DB_HOST` = dari Supabase (tanpa port)
   - `DB_PORT` = 5432
   - `DB_NAME` = postgres
   - `DB_USER` = postgres
   - `DB_PASSWORD` = password Supabase
   - `JWT_SECRET` = buat random string
   - `GOOGLE_SHEETS_WEBHOOK_URL` = URL dari Apps Script
   - `ADMIN_USERNAME` = admin
   - `ADMIN_PASSWORD_HASH` = $2a$10$8fKr5qKxQjKxQjKxQjKxQO5vXjKxQjKxQjKxQjKxQjKxQjKxQjK (default: admin123)
7. Klik **Create Web Service**
8. Tunggu sampai deploy selesai (biasanya 2-5 menit)
9. Copy URL backend, contoh: `https://absensi-backend.onrender.com`

---

## 🚀 LANGKAH 4: DEPLOY FRONTEND KE VERCEL

### 4.1 Cara Termudah (Tanpa GitHub)

1. Buat folder `frontend` terpisah:
   ```
   absensi-frontend/
   ├── index.html
   ├── admin.html
   └── tailwind.config.js
   ```

2. Buka https://vercel.com
3. Login dengan GitHub
4. Klik **Add New** → **Project**
5. Drag & drop folder `frontend` ke browser
6. Klik **Deploy**
7. Selesai! URL akan diberikan

### 4.2 Cara Alternatif (Dengan GitHub)

1. Upload folder `frontend/` ke GitHub (repository baru)
2. Di Vercel, import repository tersebut
3. Configure:
   - Framework Preset: Other
   - Build Command: (kosongkan)
   - Output Directory: .
4. Klik **Deploy**

---

## 🔧 LANGKAH 5: KONFIGURASI FRONTEND

### 5.1 Edit API URL
Di file `frontend/index.html`, cari baris:

```javascript
const API_BASE = window.location.hostname === 'localhost' || window.location.hostname === '127.0.0.1' 
  ? 'http://localhost:3000/api' 
  : 'https://absensi-backend.onrender.com/api';
```

Ganti `https://absensi-backend.onrender.com` dengan URL backend Anda.

Lakukan hal yang sama di `frontend/admin.html`.

---

## 🔐 DEFAULT CREDENTIALS

| Field | Value |
|-------|-------|
| Username | admin |
| Password | admin123 |

**Untuk keamanan, segera ubah password setelah login pertama!**

---

## ✅ TESTING

### Test Absensi
1. Buka halaman absensi (URL frontend)
2. Masukkan nomor absen (contoh: ABS001)
3. Tekan Enter
4. Status hijau = berhasil

### Test Admin
1. Buka `admin.html`
2. Login dengan credentials di atas
3. Tambah peserta baru
4. Coba absen dengan nomor yang baru ditambahkan

---

## 📝 ENVIRONMENT VARIABLES LENGKAP

### Backend (.env)
```
# Database (Supabase)
DB_HOST=your-supabase-host.supabase.co
DB_PORT=5432
DB_NAME=postgres
DB_USER=postgres
DB_PASSWORD=your-database-password

# JWT
JWT_SECRET=your-random-secret-key

# Google Sheets
GOOGLE_SHEETS_WEBHOOK_URL=your-google-apps-script-url

# Admin
ADMIN_USERNAME=admin
ADMIN_PASSWORD_HASH=$2a$10$8fKr5qKxQjKxQjKxQjKxQO5vXjKxQjKxQjKxQjKxQjKxQjKxQjK

# Server
PORT=3000
```

---

## 🆘 TROUBLESHOOTING

### Error: "Cannot connect to database"
- Periksa credentials DB di Render
- Pastikan Supabase允许外部连接

### Error: "Google Sheets webhook failed"
- Cek URL webhook sudah benar
- Cek Apps Script sudah di-deploy sebagai "Anyone"

### Error: "Invalid token"
- Login ulang di admin panel
- Token JWT expired setelah 24 jam

---

## 📞 SUPPORT

Jika ada pertanyaan, silakan buat issue di GitHub repository.

---

**Generated with ❤️ for Absensi Online Project**

