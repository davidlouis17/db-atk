# Sistem Manajemen Barang ATK

Sistem informasi manajemen stok Alat Tulis Kantor (ATK) berbasis web yang dibangun menggunakan **Laravel** dan **Filament PHP** untuk tampilan dashboard yang modern, responsif, dan kaya fitur.

## Fitur Utama

- **Authentication:** Login aman untuk Admin.
- **Dashboard Pintar:** Menampilkan statistik stok secara *real-time* (Total Barang, Total Stok, Stok Menipis, Stok Habis).
- **Manajemen Data Barang (CRUD):** Kelola nama, kategori, stok awal, dan batas minimum stok dengan antarmuka yang sangat mudah.
- **Manajemen & Riwayat Stok:** Fitur khusus untuk mencatat barang masuk dan keluar, yang secara **otomatis** menambah/mengurangi total stok barang.
- **Indikator Stok Cerdas:** Label otomatis (Aman, Menipis, Habis) berdasarkan kondisi pergerakan stok harian.
- **Laporan Lanjutan:** Laporan lalu lintas masuk-keluar stok yang bisa disaring (*filter*) berdasarkan tanggal.

---

## 🚀 Cara Menjalankan Aplikasi Secara Lokal

Ikuti langkah-langkah berikut untuk menjalankan sistem di komputer Anda menggunakan **Laragon** atau server lokal lainnya.

### 1. Persyaratan Sistem
Pastikan Anda sudah menginstal:
- PHP >= 8.2
- Composer
- MySQL/MariaDB (Sangat direkomendasikan melalui Laragon)
- Node.js & NPM

### 2. Konfigurasi Proyek

Jika baru pertama kali melakukan clone, pastikan untuk menginstal dependensi:
```bash
composer install
npm install
npm run build
```

*(Catatan: Jika Anda meneruskan project ini tanpa clone, Anda bisa melewati tahap di atas)*

### 3. Konfigurasi Database (Environment)

Buat database baru di MySQL/phpMyAdmin (misalnya dengan nama `abp-main` atau sesuai preferensi). Lalu konfigurasi file `.env`:

1. *Copy* file `.env.example` dan ubah namanya menjadi `.env` (jika belum ada).
2. Generate *Application Key*:
   ```bash
   php artisan key:generate
   ```
3. Sesuaikan koneksi database di `.env`:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=nama_database_anda
   DB_USERNAME=root
   DB_PASSWORD=
   ```

### 4. Jalankan Migrasi Data

Jalankan perintah berikut untuk membuat struktur tabel database yang dibutuhkan:
```bash
php artisan migrate
```

### 5. Buat Akun Admin

Untuk dapat login ke sistem, buat akun admin baru dengan menjalankan perintah:
```bash
php artisan make:filament-user
```
*(Lalu ikuti instruksi pengisian nama, email, dan password di terminal).*

**Akun Demo Bawaan:**
Jika Anda tidak ingin membuat akun baru, aplikasi ini mungkin sudah memiliki akun admin:
- **Email:** `admin@admin.com`
- **Password:** `password`

### 6. Jalankan Server

Jalankan server pengembangan Laravel:
```bash
php artisan serve
```

### 7. Akses Aplikasi

Buka browser dan akses alamat berikut:
👉 **[http://127.0.0.1:8000](http://127.0.0.1:8000)**

*(Sistem akan otomatis mengarahkan Anda ke halaman `/admin/login`)*. Selamat menggunakan!
