# 🥖 Fersya Shop – Organic Elegance E-Commerce

[![Laravel](https://img.shields.io/badge/Laravel-13.x-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)](https://laravel.com)
[![Filament](https://img.shields.io/badge/Filament-3.x-FDAE4B?style=for-the-badge&logo=laravel&logoColor=black)](https://filamentphp.com)
[![Tailwind CSS](https://img.shields.io/badge/Tailwind_CSS-3.x-38B2AC?style=for-the-badge&logo=tailwind-css&logoColor=white)](https://tailwindcss.com)
[![License](https://img.shields.io/badge/License-MIT-blue.style=for-the-badge)](LICENSE)

**Fersya Shop** adalah aplikasi e-commerce modern berpenerapan desain *Organic Elegance* yang dibangun khusus untuk toko bakery artisan, kopi organik, dan teh herbal. Menggabungkan estetika visual kelas atas dengan alur belanja yang ringkas, cepat, dan aman.

---

## ✨ Fitur Utama

### 🛍️ Sisi Pembeli (Storefront)
- **Katalog Artisan & Filter Kategori**: Navigasi produk responsif untuk Roti Gandum, Kopi Organik, dan Teh Herbal.
- **Varian Produk & Stok Real-Time**: Pemilihan ukuran/varian dengan stok terintegrasi dan validasi otomatis saat checkout.
- **Keranjang Belanja Session**: Mengelola item belanja tanpa perlu login paksa (*guest checkout*).
- **Pembayaran Barcode QRIS & Konfirmasi WhatsApp**: Mendukung scan QRIS (GoPay, OVO, Dana, ShopeePay, Bank Transfer) + tombol otomatis chat WA Admin (`081321686115`).
- **Lacak & Cek Status Pesanan (`/cek-pesanan`)**: Pencarian instan riwayat pesanan menggunakan Nomor Pesanan (`FS-XXXXXXXX`) atau Nomor WhatsApp.
- **Kupon Diskon & Promo**: Input kode promo (`FERSYA10` diskon 10%, `HEBAT15K` potongan Rp 15rb) di keranjang & checkout.
- **Cetak / Download Invoice PDF**: Tombol otomatis untuk cetak/simpan struk belanja PDF resmi dari halaman detail pesanan.
- **Announcement Bar Header**: Banner promo & pengumuman toko di paling atas header.
- **Ulasan Produk Terverifikasi**: Pembeli yang sudah menyelesaikan pembayaran dapat mengirimkan rating bintang & komentar.
- **Gratis Ongkos Kirim**: Perhitungan ongkir otomatis Rp 0 untuk seluruh wilayah tujuan.

### 👑 Sisi Admin (Filament Dashboard)
- **Dashboard Analitik**: Ringkasan total pendapatan, jumlah pesanan, dan statistik bisnis real-time.
- **Export Laporan Penjualan (CSV)**: 1-klik download rekap data pesanan & penjualan ke file CSV/Excel.
- **Kelola Kupon Diskon**: Tambah/edit kupon promo (tipe persen/fixed, minimal belanja, status aktif).
- **Manajemen Pesanan**: Update status pembayaran (`Pending`, `Lunas`, `Gagal`) dan status pengiriman (`Diproses`, `Dikirim`, `Selesai`).
- **Input Nomor Resi Ekspedisi**: Mengirimkan nomor resi kurir kepada pembeli untuk pelacakan paket.
- **Moderasi Ulasan**: Menyetujui (*approve*) atau menolak ulasan pembeli sebelum tampil di katalog produk.
- **Manajemen Katalog & Stok**: Tambah/edit produk, varian harga, stok pcs, dan galeri foto produk.

### ✉️ Sistem Email & Notifikasi
- **Notifikasi Email Otomatis**: Template Blade HTML eksklusif untuk konfirmasi pesanan baru (*Order Created*) dan pemberitahuan pengiriman paket (*Order Shipped*).

---

## 🛠️ Teknologi & Stack

- **Framework**: Laravel 13.x
- **Admin Panel**: Filament v3 (Green Brand Theme `#2D4A3E`)
- **Frontend**: Blade Components, Tailwind CSS, Google Fonts (Cormorant Garamond & Plus Jakarta Sans)
- **Iconography**: Material Symbols Outlined
- **Database**: SQLite (Lokal) / MySQL (Produksi)
- **Testing**: PHPUnit / Pest Automated Tests

---

## 🚀 Panduan Instalasi Lokal

### 1. Clone Repository
```bash
git clone https://github.com/FerrelHD/Fersya-Shop.git
cd Fersya-Shop
```

### 2. Install Dependencies
```bash
composer install
npm install
```

### 3. Setup Environment File
```bash
cp .env.example .env
php artisan key:generate
```

### 4. Setup Database & Seeder
```bash
touch database/database.sqlite
php artisan migrate --seed
```

### 5. Jalankan Application Server
Buka 2 terminal terpisah:

**Terminal 1 (Laravel Server):**
```bash
php artisan serve
```

**Terminal 2 (Vite Assets Builder):**
```bash
npm run dev
```

Buka browser di `http://127.0.0.1:8000`.

---

## 🔑 Kredensial Admin Default

- **URL Dashboard Admin**: `http://127.0.0.1:8000/admin`
- **Email**: `admin@fersya.test`
- **Password**: `fersya2025`

---

## 🌐 Persiapan Deployment / Hosting

Saat siap diunggah ke server hosting (cPanel / VPS):

1. **Ubah `.env`**:
   - `APP_ENV=production`
   - `APP_DEBUG=false`
   - `APP_URL=https://domain-anda.com`
   - Konfigurasi `DB_HOST`, `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD` (MySQL).
2. **Jalankan Perintah Produksi**:
   ```bash
   php artisan storage:link
   php artisan config:cache
   php artisan route:cache
   php artisan view:cache
   ```

---

## 📄 Lisensi
Project ini dibuat di bawah lisensi [MIT License](LICENSE).
