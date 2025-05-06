# Aplikasi Web To-Do List

![PHP Version](https://img.shields.io/badge/PHP-7.4%2B-blue.svg)
![MySQL Version](https://img.shields.io/badge/MySQL-5.7%2B-orange.svg)
![XAMPP](https://img.shields.io/badge/XAMPP-8.2.4-green.svg)
![License](https://img.shields.io/badge/License-MIT-yellow.svg)

Aplikasi manajemen tugas modern berbasis web dengan autentikasi pengguna dan antarmuka responsif.

## 🚀 Fitur

- ✅ Autentikasi pengguna (Registrasi/Masuk/Keluar)
- ✅ Manajemen tugas dengan operasi CRUD via AJAX
- ✅ Antarmuka responsif untuk semua perangkat
- ✅ Animasi interaksi halus dengan Animate.css
- ✅ Dukungan multi-bahasa (Indonesia & Inggris)
- ✅ Sistem prioritas dan tenggat waktu tugas
- ✅ Penyaringan dan pencarian tugas

## 📋 Persyaratan

- [XAMPP](https://www.apachefriends.org/) (Apache & MySQL)
- PHP 7.4 atau lebih baru
- MySQL 5.7 atau lebih baru
- Browser modern (Chrome, Firefox, Edge)

## 🛠️ Panduan Instalasi

### 1. Persiapan Server
1. Install XAMPP dan jalankan layanan Apache + MySQL
2. Clone repositori ke folder `htdocs`:
```bash
git clone https://github.com/namapengguna/todo-list.git c:/xampp/htdocs/todo-list
```

### 2. Setup Database
1. Buka phpMyAdmin di `http://localhost/phpmyadmin`
2. Buat database baru:
```sql
CREATE DATABASE todo_app_db;
```
3. Buat tabel menggunakan query dari file `memory-bank/database-setup.sql`

### 3. Konfigurasi Aplikasi
1. Salin file konfigurasi:
```bash
cp includes/config.example.php includes/config.php
```
2. Edit `includes/config.php` dengan kredensial database Anda

## 🗂️ Struktur Proyek
```
📦To-Do-List-main
├── 📂api - Endpoint API
│   ├── 📂auth - Autentikasi
│   └── 📂tasks - Manajemen tugas
├── 📂assets - Aset frontend
│   ├── 📂css - Stylesheet
│   └── 📂js - Skrip JavaScript
├── 📂includes - Utilitas PHP
├── 📂lang - Terjemahan
└── 📂memory-bank - Dokumentasi
```

## 🖥️ Cara Penggunaan
1. Akses `http://localhost/todo-list`
2. Daftar akun baru atau masuk
3. Kelola tugas dengan:
   - 📥 Tambah tugas baru
   - ✏️ Edit detail tugas
   - ✅ Tandai sebagai selesai
   - 🗑️ Hapus tugas
   - 🔍 Cari & filter tugas

## 🔒 Keamanan
- Password di-hash menggunakan bcrypt
- Proteksi SQL injection dengan prepared statements
- Validasi input server-side
- Session management aman

## ⚠️ Batasan
- Hanya berjalan di lingkungan lokal
- Ukuran file upload terbatas oleh konfigurasi PHP
- Membutuhkan XAMPP untuk operasional

## 🤝 Kontribusi
1. Fork repositori
2. Buat branch fitur: `git checkout -b fitur-baru`
3. Commit perubahan: `git commit -m 'Tambahkan fitur'`
4. Push ke branch: `git push origin fitur-baru`
5. Buat Pull Request

## 📝 Lisensi
MIT License - Lihat [LICENSE](LICENSE) untuk detail

---

Dikembangkan dengan ❤️ oleh Adrian menggunakan:
- [XAMPP](https://www.apachefriends.org/) - Server lokal
- [Animate.css](https://animate.style/) - Animasi
- [Font Awesome](https://fontawesome.com/) - Ikon
