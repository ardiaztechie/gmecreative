# 🚀 Kahfi Engagement - Campaign & Social Media Analytics System

**Kahfi Engagement** adalah platform manajemen kampanye media sosial dan analisis *engagement rate*. Sistem ini dirancang untuk mengelola operasional konten, pengambilan dan pembaruan ulang (*scraping* dan *re-scraping*) data metrik TikTok & Instagram melalui Apify, visualisasi analytics, serta manajemen hak akses pengguna berbasis **Role-Based Access Control (RBAC)**.

---

## 📌 Fitur Utama

- 📊 **Dashboard Monitoring**: Card metrik ringkas (Tayangan/Views, Likes, Comments, Shares, Saves, dan Engagement Rate %), grafik tren, perbandingan platform, dan ranking konten teratas (berdasarkan views).
- 🔍 **Multi-Filtering System**: Filter interaktif berdasarkan **Platform** (TikTok/Instagram), **Campaign**, **Tahun**, **Bulan**, dan **Hari**.
- 🔄 **Update Campaign & Re-scraping**:
  - Tampilan grid compact 4-column dengan paginasi.
  - Indikator sisa waktu (*countdown timer*) masa aktif campaign.
  - Seleksi massal (Bulk Selection) link untuk re-scraping otomatis via **Apify**.
  - Perbandingan kenaikan/penurunan metrik pasca re-scraping.
- 🔐 **Role-Based Access Control (RBAC)**:
  - Fleksibilitas pengaturan hak akses menu & fitur per Role menggunakan `spatie/laravel-permission`.
  - Pembatasan khusus akses campaign per user Admin (`UserCampaignAccess`).
- 📁 **Operasional Konten & Link**: Input link secara Single, Bulk, atau unggah file CSV/Excel; satu URL hanya boleh terdaftar pada satu campaign; integrasi kategori creator & konten.
- 📄 **Export Laporan**: Fitur ekspor laporan metrik engagement ke format **PDF (Landscape)** & **Excel (CSV)**.
- 🎨 **Modern UI/UX**: Garis indikator loading otomatis (**NProgress**), Modal dialog & Toast Notification modern (**SweetAlert2**).

---

## 🛠️ Persyaratan Sistem (System Requirements)

- **PHP**: `>= 8.2`
- **Composer**: `>= 2.x`
- **Node.js**: `>= 18.x` & **NPM**
- **Database**: MySQL `>= 8.0` / MariaDB
- **Web Scraper API**: Akun **Apify** & API Token (*untuk fitur otomatisasi scraping data*)

---

## 🚀 Panduan Setup Awal (Clone to Run)

Ikuti langkah-langkah berikut untuk menjalankan proyek dari repositori baru (clone):

### 1. Clone Repositori

```bash
git clone https://github.com/ardiaztechie/gmecreative.git
cd gmecreative
```

### 2. Install Dependensi PHP (Composer)

```bash
composer install
```

### 3. Install Dependensi Frontend (Node.js / NPM)

```bash
npm install
```

### 4. Setup File Konfigurasi Environment (`.env`)

Salin file `.env.example` menjadi `.env`:

```bash
cp .env.example .env
```

*Catatan pada Windows PowerShell:*

```powershell
git add .env.example
git diff --cached .env.example
```

Buka file `.env` dan atur konfigurasi database serta Apify Token Anda:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=kahfi_engagement
DB_USERNAME=root
DB_PASSWORD=

# Apify Scraping Configuration
APIFY_TOKEN=your_apify_api_token_here
# Opsional: nama actor Apify (sudah ada nilai bawaan)
APIFY_TIKTOK_ACTOR=clockworks/free-tiktok-scraper
APIFY_IG_ACTOR=apify/instagram-scraper
```

### 5. Generate Application Key

```bash
php artisan key:generate
```

### 6. Migrasi Database & Seeding RBAC (PENTING)

Jalankan perintah berikut untuk membuat struktur tabel dan mengisi data awal Role, Permission, serta akun default:

```bash
php artisan migrate:fresh --seed
```

> ⚠️ **Peringatan:** `migrate:fresh` menghapus SELURUH tabel beserta isinya. Pakai hanya pada instalasi baru atau lingkungan pengembangan. Pada database yang sudah berisi data, cukup jalankan `php artisan migrate`.

### 7. Buat Symbolic Link Storage

```bash
php artisan storage:link
```

### 8. Jalankan Server & Asset Bundler

Buka dua terminal terpisah:

**Terminal 1 (Laravel Dev Server):**

```bash
php artisan serve
```

**Terminal 2 (Vite Asset Bundler):**

```bash
npm run dev
```

Buka browser dan akses aplikasi di: `http://127.0.0.1:8000`

---

## 🔑 Akun Default Hasil Seeding Database

Setelah menjalankan `php artisan db:seed` / `migrate:fresh --seed`, akun berikut siap digunakan:

Login menggunakan **username** dan password (bukan email).

| Role             | Username        | Email               | Password   | Hak Akses Utama                                                                   |
| :--------------- | :-------------- | :------------------ | :--------- | :-------------------------------------------------------------------------------- |
| **Admin Master** | `adminmaster`   | `admin@master.com`  | `password` | Full akses ke seluruh sistem, RBAC, dan User Access                               |
| **Admin**        | `admin123`      | `admin@admin.com`   | `password` | Dashboard, Operasional Konten (input link), Laporan, hanya pada campaign yang ditugaskan |
| **Client**       | `client123`     | `client@client.com` | `password` | Dashboard, Campaign & Laporan milik sendiri                                       |

> ⚠️ Akun di atas hanya untuk pengembangan. Karena repositori ini publik, **ganti password semua akun sebelum dipakai di server nyata**.

---

## 🛡️ Konfigurasi RBAC (Role-Based Access Control)

Sistem menggunakan paket **Spatie Laravel-Permission**.

### List Permission Bawaan:

- `dashboard.view`
- `users.view`, `users.create`, `users.edit`, `users.delete`
- `roles.view`, `roles.create`, `roles.edit`, `roles.delete`
- `campaigns.view`, `campaigns.create`, `campaigns.edit`, `campaigns.delete`
- `operasional-konten.view`, `operasional-konten.create`, `operasional-konten.delete`
- `update-saw.view`, `update-saw.process` (halaman Update Campaign / re-scraping; nama permission masih memakai penamaan awal)
- `master-data.view`
- `laporan.view`
- `profile.edit`

### Cara Mengatur Hak Akses via UI:

1. Login sebagai **Admin Master**.
2. Buka menu **Master Data** -> **Roles & Hak Akses**.
3. Pilih role yang ingin diubah lalu klik **Edit**.
4. Centang / Hilangkan centang permission yang diinginkan (misal: `laporan.view`).
5. Simpan perubahan. Menu & halaman yang tidak di-centang otomatis disembunyikan dan diuji dengan HTTP 403 Forbidden Middleware.

---

## 📁 Struktur Direktori Utama

```
gmecreative/
├── app/
│   ├── Http/Controllers/    # Dashboard, Link (input & scraping Apify), Update Campaign, Campaign, User, Role, Laporan, Export
│   └── Models/              # Eloquent Models (Campaign, Link, User, dll)
├── database/
│   ├── migrations/          # Tabel DB & Schema
│   └── seeders/             # PermissionSeeder & DatabaseSeeder
├── resources/
│   ├── css/                 # Styling Tailwind & Custom Theme
│   ├── js/                  # Alpine.js & Custom Logic
│   └── views/               # Blade Templates (Dashboard, Update Campaign, Laporan, Layouts)
├── routes/
│   └── web.php              # Definisi Route & Middleware Permission
└── README.md
```

---

## 📄 Lisensi

Hak Cipta © 2026 **Kahfi Engagement Team**. All Rights Reserved.
