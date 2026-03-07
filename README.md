# PersonalDevHub

**PersonalDevHub** adalah platform portfolio personal minimalis yang dirancang untuk mengelola jejak digital pengembang. Web ini berfungsi sebagai repositori terpusat untuk menyimpan tautan proyek yang telah diselesaikan serta dokumentasi potongan kode (code snippets) penting agar mudah di akses kembali.

Built with:
 - **PHP** (Core Logic)
 - **Tailwind CSS** *(Modern Styling)*

## Fitur
 - **Showcase Proyek**: Menampilkan daftar proyek lengkap dengan gambar preview, tautan GitHub, dan URL demo langsung.
 - **Gudang Snippet**: Menyimpan potongan kode penting dengan fitur Copy-to-Clipboard sekali klik.
 - **Kategori Dinamis**: Admin dapat mengelola kategori dan tech stack secara kustom.
 - **Dashboard Admin**: Sistem manajemen untuk menambah, mengubah, atau menghapus data melalui formulir yang intuitif.
 - **Desain Responsif**: Antarmuka modern dan bersih yang optimal di semua ukuran layar berkat Tailwind CSS.

## Tech Stack
 - **Bahasa Pemrograman**: PHP 8.1.10
 - **Database**: MySQL
 - **Styling**: Tailwind CSS
 - **Dependencies**: NPM (untuk manajemen aset Tailwind)

## Panduan Instalasi
Ikuti langkah langkah berikut untuk menjalankan proyek di lingkungan lokal: <br>

1. **Unduh & Ekstrak**  
    Unduh repositori ini dalam format ZIP dan ekstrak ke direktori server lokal Anda (contoh: `htdocs` atau `www`)

2. **Instalasi & Dependensi**  
    Jalankan perintah berikut untuk menginstal Tailwind CSS dan aset pendukung lainnya:

```bash  
npm install
```

3. Konfigurasi Database
    1. Buka file `config/database.php`.
    2. Sesuaikan konfigurasi `host`, `username`, `password`, dan `nama_database` dengan server lokal Anda.

