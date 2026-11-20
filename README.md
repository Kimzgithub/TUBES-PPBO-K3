# Social Media Backend & Web UI — Instagram/Threads Clone (Kelompok 3)

Proyek Tugas Besar **Pemrograman Berorientasi Objek (PBO) & Praktikum** — Program Studi S1 Sistem Informasi.

## 1. Spesifikasi Lingkungan & Tech Stack (Mandatory Poin 3)
- **Framework:** Laravel 13 (`^13.17` / `13.39.0`)
- **Bahasa Pemrograman:** PHP `8.5.12` (kompatibel minimal PHP `8.3.36` / `8.4.27`)
- **Database:** MySQL (`tubes_ppbo_k3`)
- **Authentication & Security:** Laravel Sanctum (`Bearer Token`) + Rate Limiting (`throttle:auth` 5x/menit)

## 2. Penerapan Prinsip OOP (Bobot 35%)
1. **Inheritance & Abstraction (`feature/post`):** `AbstractPost` diturunkan ke `TextPost`, `MediaPost`, dan `PollPost` melalui `PostTypeFactory`.
2. **Polymorphism & Interface (`feature/notification`):** `NotificationChannelInterface` diimplementasikan oleh `InAppChannel` (MySQL) dan `EmailChannel` (SMTP/Log) dengan pemanggilan seragam `$channel->send()`.
3. **Reusable Trait:** `ApiResponse` (format JSON seragam), `HandlesFileUpload` (manajemen file storage), dan `HasReactions` (relasi polimorfik + cascade delete otomatis saat Post/Comment dihapus).
4. **Encapsulation & Dependency Injection:** Seluruh Service menggunakan constructor property promotion `private readonly` terhadap kontrak `RepositoryInterface`.

## 3. Cara Instalasi & Menjalankan di Localhost
```bash
composer install
cp .env.example .env
php artisan key:generate
php artisan storage:link
php artisan migrate:fresh --seed
php artisan serve
```
Buka **`http://127.0.0.1:8000`** di browser untuk mencoba langsung antarmuka Web Instagram Clone yang terhubung ke database MySQL.

## 4. Konfigurasi Produksi Aman (Ketentuan Poin 3.6)
Pada server produksi, pastikan variabel berikut di `.env`:
```ini
APP_ENV=production
APP_DEBUG=false
```
Dan pastikan hasil `composer audit` menunjukkan **No security vulnerability advisories found**.