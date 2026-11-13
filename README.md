# 📁 Sistem Manajemen Arsip Digital

Aplikasi web modern berbasis **Laravel 12** untuk pengelolaan, pengkategorian, dan digitalisasi arsip dokumen (Surat Masuk, Surat Keluar, dan Dokumen resmi) secara terpusat, aman, dan terstruktur.

---

## 🌟 Fitur Utama

### 1. 📂 Manajemen Arsip Digital (CRUD Berkas)
- **Pencatatan Lengkap**: Nomor arsip unik, judul, kategori jenis berkas, kode klasifikasi, tanggal arsip, dan keterangan tambahan.
- **Pengelolaan Berkas Fisik**: Unggah dokumen digital berformat PDF, DOC, dan DOCX (hingga 10 MB).
- **Pratinjau & Pengunduhan**: Fitur *preview* dokumen di tab baru serta tombol *download* langsung berkas arsip.
- **Pembersihan Otomatis**: Berkas lama di direktori penyimpanan (*storage*) secara otomatis dibersihkan saat dokumen diperbarui atau dihapus.

### 2. 🏷️ Integrasi Klasifikasi Arsip
- Terintegrasi dengan model `KlasifikasiArsip` (kode klasifikasi dan uraian jenis arsip sesuai pedoman tata kearsipan).
- Dropdown dinamis pada formulir tambah dan edit arsip.
- Filter pencarian khusus berdasarkan klasifikasi arsip.

### 3. 📊 Dashboard & Visualisasi Data Interaktif
- **Ringkasan Kartu Statistik**: Menampilkan total seluruh arsip, surat masuk, surat keluar, dan dokumen.
- **Peringatan Berkas Kosong**: Notifikasi khusus (*alert*) yang mendeteksi arsip yang belum memiliki lampiran berkas fisik.
- **Grafik Interaktif (Chart.js)**: Diagram batang (*bar chart*) yang menampilkan persebaran arsip secara visual dengan label angka langsung di atas batang grafik.
- **Tabel Arsip Terbaru**: Akses cepat ke 5 dokumen yang baru saja ditambahkan.

### 4. 📈 Export & Import Excel
- **Export Data ke Excel**: Mengunduh seluruh daftar arsip ke dalam berkas `.xlsx` dengan format rapi dan terstruktur (No, Nomor Arsip, Judul, Jenis, Klasifikasi, Tanggal, Keterangan, Status Berkas).
- **Import Arsip Massal**: Mengunggah berkas Excel/CSV untuk memasukkan banyak data arsip sekaligus dengan mekanisme *upsert* aman.
- **Unduh Template Excel**: Disediakan format template acuan impor yang dapat langsung diunduh dari sistem.

### 5. 👥 Manajemen Pengguna & Hak Akses (Role-Based Access)
- **Multi-Role**:
  - **Admin**: Memiliki akses penuh (tambah, edit, hapus arsip, import berkas, manajemen user, dan melihat log aktivitas).
  - **User**: Dapat mengakses dashboard, melihat daftar arsip, melihat detail, dan mengunduh berkas arsip.
- **Manajemen Akun**: Pengaturan role (jadikan Admin / User) serta proteksi agar akun aktif tidak dapat menghapus atau menurunkan rolenya sendiri.

### 6. 📜 Log Aktivitas (Audit Trail)
- Pencatatan riwayat setiap aksi penting dalam sistem (Tambah, Edit, Hapus, Unduh berkas, Export, dan Import).
- Merekam waktu (*timestamp*), identitas pengguna, jenis aksi, keterangan, dan alamat IP klien.

### 7. 🔎 Pencarian & Filter Canggih
- Pencarian cerdas multi-kolom (nomor arsip, judul, jenis, keterangan, kode klasifikasi, maupun nama klasifikasi).
- Filter berdasarkan jenis arsip (Surat Masuk, Surat Keluar, Dokumen).
- Filter berdasarkan klasifikasi arsip.
- Opsi filter cepat khusus dokumen tanpa lampiran berkas fisik.

---

## 🛠️ Tumpukan Teknologi (Tech Stack)

| Komponen | Teknologi |
| :--- | :--- |
| **Backend Framework** | [Laravel 12](https://laravel.com/) |
| **Bahasa Pemrograman** | [PHP ^8.2](https://www.php.net/) |
| **Autentikasi** | [Laravel Breeze](https://laravel.com/docs/starter-kits#laravel-breeze) |
| **Spreadsheet Engine** | [Maatwebsite Excel 3.1](https://laravel-excel.com/) |
| **Basis Data** | MySQL / MariaDB |
| **Frontend UI** | Bootstrap 5, Tailwind CSS, & Vite |
| **Visualisasi Grafik** | [Chart.js](https://www.chartjs.org/) |

---

## 🚀 Panduan Instalasi & Penggunaan

### 1. Prasyarat Sistem
Pastikan perangkat Anda telah terpasang:
- PHP >= 8.2 (dengan ekstensi: `pdo_mysql`, `mbstring`, `fileinfo`, `gd`, `zip`, `xml`)
- Composer
- Node.js & NPM
- MySQL / MariaDB Server

### 2. Kloning & Pengaturan Dependensi
Buka terminal dan jalankan perintah berikut di direktori proyek:

```bash
# 1. Pasang dependensi PHP via Composer
composer install

# 2. Pasang dependensi JavaScript via NPM
npm install
```

### 3. Konfigurasi Lingkungan (`.env`)
Salin berkas konfigurasi lingkungan:

```bash
cp .env.example .env
```

Buka berkas `.env` dan sesuaikan koneksi database Anda:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306        # Sesuaikan dengan port MySQL Anda (default 3306 atau 5151)
DB_DATABASE=db_arsip_digital
DB_USERNAME=root
DB_PASSWORD=
```

### 4. Generate Key & Migrasi Database
Jalankan pembuatan application key, migrasi tabel database, serta seeder awal:

```bash
# Generate application encryption key
php artisan key:generate

# Jalankan migrasi dan seeder klasifikasi arsip
php artisan migrate --seed

# Buat symbolic link untuk folder storage arsip publik
php artisan storage:link
```

### 5. Kompilasi Aset Frontend
```bash
npm run build
```

### 6. Menjalankan Server Lokal
Jalankan development server Laravel:

```bash
php artisan serve
```

Aplikasi siap diakses melalui peramban web di:  
👉 **[http://127.0.0.1:8000](http://127.0.0.1:8000)**

---

## 📂 Struktur Direktori Utama

```text
arsip-digital/
├── app/
│   ├── Exports/               # Class ekspor data & template Excel
│   │   ├── ArsipExport.php
│   │   └── ArsipTemplateExport.php
│   ├── Imports/               # Class pemrosesan impor berkas Excel
│   │   └── ArsipImport.php
│   ├── Http/
│   │   ├── Controllers/       # Controller logika bisnis (Arsip, User, Log, dsb.)
│   │   └── Middleware/        # AdminMiddleware proteksi hak akses
│   └── Models/                # Eloquent Models (Arsip, User, Klasifikasi, Log)
├── database/
│   ├── migrations/            # Skema tabel database
│   └── seeders/               # Data awal klasifikasi & pengguna
├── resources/
│   └── views/
│       ├── activity_logs/     # Halaman riwayat aktivitas audit
│       ├── arsip/             # Tampilan index, create, edit, show arsip
│       ├── layouts/           # Layout utama Bootstrap (arsip) & Breeze
│       └── users/             # Halaman manajemen role pengguna
├── routes/
│   ├── web.php                # Rute aplikasi web
│   └── auth.php               # Rute autentikasi
└── storage/app/public/arsip/  # Lokasi penyimpanan fisik berkas dokumen
```

---

## 🔒 Hak Akses & Peran Pengguna

| Fitur | User Biasa | Administrator |
| :--- | :---: | :---: |
| Lihat Dashboard & Statistik | ✅ | ✅ |
| Jelajahi & Cari Daftar Arsip | ✅ | ✅ |
| Lihat Detail & Unduh Berkas | ✅ | ✅ |
| Export Data ke Excel | ✅ | ✅ |
| Tambah Arsip Baru | ❌ | ✅ |
| Edit & Hapus Arsip | ❌ | ✅ |
| Import Arsip dari Excel | ❌ | ✅ |
| Manajemen User & Ubah Role | ❌ | ✅ |
| Lihat Log Aktivitas Audit | ❌ | ✅ |

---

## 📄 Lisensi
Proyek ini dikembangkan menggunakan kerangka kerja [Laravel](https://laravel.com) yang berlisensi [MIT license](https://opensource.org/licenses/MIT).
