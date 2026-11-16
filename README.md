# EcoHorizon Global
### Enterprise Multimodal Freight Visibility & Carbon Intelligence Platform
> *"Track Every Journey. Verify Every Milestone. Move Toward a Greener Future."*

[![Laravel](https://img.shields.io/badge/Laravel-11.x-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)](https://laravel.com)
[![PHP](https://img.shields.io/badge/PHP-8.3-777BB4?style=for-the-badge&logo=php&logoColor=white)](https://php.net)
[![Tailwind CSS](https://img.shields.io/badge/Tailwind_CSS-3.4-38B2AC?style=for-the-badge&logo=tailwind-css&logoColor=white)](https://tailwindcss.com)
[![Tests](https://img.shields.io/badge/Tests-39%20Passed%20(205%20Assertions)-10B981?style=for-the-badge&logo=checkmarx&logoColor=white)](tests)
[![Carbon Standard](https://img.shields.io/badge/Carbon_Standard-GLEC_v3.0%20(ISO_14083)-0284C7?style=for-the-badge)](docs/ARCHITECTURE.md)
[![Localization](https://img.shields.io/badge/i18n-English%20%7C%20Bahasa%20Indonesia-0F172A?style=for-the-badge)](lang)

---

## 📋 Daftar Isi (Table of Contents)

1. [Executive Summary & Project Identity](#1-executive-summary--project-identity)
2. [Key Platform Features & Innovations](#2-key-platform-features--innovations)
3. [Technology Stack & System Architecture](#3-technology-stack--system-architecture)
4. [Brand Design System & Color Palette](#4-brand-design-system--color-palette)
5. [Pre-Configured Demo Accounts & Roles](#5-pre-configured-demo-accounts--roles)
6. [Flagship Pre-Seeded Multimodal Consignments](#6-flagship-pre-seeded-multimodal-consignments)
7. [Installation & Execution Guide](#7-installation--execution-guide)
8. [Automated Test Suite (39 Tests / 205 Assertions)](#8-automated-test-suite)
9. [REST API Documentation & Postman](#9-rest-api-documentation--postman)
10. [Technical Documentation Index & Academic Authorship](#10-technical-documentation-index--academic-authorship)

---

## 1. Executive Summary & Project Identity

**EcoHorizon Global** adalah platform *enterprise-grade web application* untuk visibilitas kargo dan pelacakan pengiriman multimodal global (*global multimodal freight visibility and tracking platform*). Dikembangkan sebagai proyek akhir akademik (UAS), platform ini mengatasi titik buta (*visibility blindspots*) pada rantai pasok logistik internasional lintas koridor **Laut (*Ocean*)**, **Darat (*Overland*)**, dan **Udara (*Air*)** sekaligus menanamkan kalkulasi jejak karbon terakreditasi (**GLEC Framework v3.0 / ISO 14083**) secara *real-time*.

Berbeda dari aplikasi pelacakan kurir ritel biasa, EcoHorizon Global dirancang khusus untuk memodelkan realitas operasional kargo komersial internasional:

* **True Multimodal Journeys**: Mengelola rangkaian kaki rute pengiriman (*sequential transport legs*) dengan pergantian armada, operator pengangkut (*carriers*), dan terminal transit (*transshipment hubs*).
* **Contractual Service Levels**: Pembagian tegas antara cakupan layanan kargo komersial:
  * **Door-to-Port Delivery**: Penjemputan dari pintu gudang pengirim hingga penyerahan di terminal kedatangan pelabuhan/bandara tujuan.
  * **Port-to-Port Freight**: Pengangkutan terminal-ke-terminal langsung antarpelabuhan internasional.
  * **B2B / Warehouse Delivery**: Pengiriman langsung ke pusat distribusi/gudang enterprise dengan verifikasi tanda terima dok (*dock receipt*) dan tanda tangan petugas (*receiving officer*).
* **Terminal Handover Protection**: Mencegah klaim pengiriman prematur (*false delivery claims*) saat kargo baru tiba di pelabuhan/terminal perantara.
* **Auditable Carbon Intelligence**: Menghitung emisi gas rumah kaca ($\text{kg CO}_2\text{e}$) per leg pengiriman secara transparan berbasis metodologi **GLEC Framework v3.0**.
* **Proactive Exception Watch**: Mendeteksi dan mencatat anomali cuaca ekstrim, pengalihan rute maritim, pemeriksaan bea cukai (*customs hold*), dan estimasi deviasi waktu tiba (*ETA delays*).
* **Bilingual Localization (i18n)**: Dukungan penuh dua bahasa (**English & Bahasa Indonesia**) yang tersinkronisasi di seluruh portal publik dan internal.

---

## 2. Key Platform Features & Innovations

### 🛰️ Interactive Global Corridor & Shipment Radar (LogiSphere Edition)
* **Visual Satelit Permukaan Bumi Realistis**: Menggunakan citra satelit resolusi tinggi *Esri World Imagery* yang dipadukan dengan overlay batas wilayah (*World Reference Boundaries*).
* **Simpul Radar Bercahaya (*Glowing Concentric Radar Beacons*)**: Marker pelabuhan dan terminal transit berbentuk simpul radar melingkar dengan inti bercahaya dan animasi denyut gelombang (*pulsing radar rings*).
* **Jalur Berkelok Sesuai Koridor Nyata (*Catmull-Rom Spline Routing*)**: Saat diperbesar (*zoom in*), garis jalur tidak kaku memotong benua, melainkan meliuk halus mengikuti koridor transit pelayaran dan darat nyata:
  * **Jalur Maritim Asia–Eropa**: Melintasi Selat Malaka, Samudra Hindia, Teluk Aden, Bab-el-Mandeb, Laut Merah, Terusan Suez, Laut Mediterania, Selat Gibraltar, hingga Selat Inggris (*English Channel*).
  * **Jalur Darat Tol Trans-Jawa**: Mengikuti jalur arteri logistik Surabaya $\rightarrow$ Solo $\rightarrow$ Semarang $\rightarrow$ Cirebon $\rightarrow$ Cikampek $\rightarrow$ Cikarang $\rightarrow$ Jakarta.
  * **Jalur Interstate AS**: Mengikuti koridor I-80 dari Chicago melintasi Midwest dan Rocky Mountains menuju California.
* **Gaya Peta Dinamis**: Dilengkapi tombol pengalih instan antara **🛰️ Satellite Earth** dan **🗺️ Clean Vector**.

### 📊 Modal Share & Freight Lanes Analytics
* **Diagram Donut Distribusi Jalur**: Visualisasi proporsi kargo laut, udara, dan darat dengan palet warna lembut (*Sky Blue*, *Lavender*, *Sage Green*).
* **Metrik Segmen & Jarak**: Total segmen aktif, persentase pangsa moda, dan akumulasi jarak tempuh koridor logistik.
* **Pencarian Instan Konsinyasi**: Filter teks responsif di sisi klien (*client-side live filter*) untuk melacak referensi pengiriman, pengangkut, dan kota rute secara instan.

### 🌐 Dual-Language Support (EN ↔ ID)
* Peralihan bahasa instan antara **English** dan **Bahasa Indonesia**.
* Penyimpanan preferensi pengguna secara persisten melalui *session*, *encrypted cookie*, dan parameter URL `?lang=`.
* Cakupan kamus terjemahan 100% pada 24 halaman Blade (dashboard admin, konsol pelacakan, registri pengiriman, registrasi, formulir login, analitik keberlanjutan, dan portal publik).

---

## 3. Technology Stack & System Architecture

| Komponen | Teknologi | Keterangan |
| :--- | :--- | :--- |
| **Backend Framework** | [Laravel 11.x](https://laravel.com) | Arsitektur modern PHP 8.3 dengan Controller-Service Pattern |
| **Frontend UI Engine** | Blade + [Tailwind CSS 3.4](https://tailwindcss.com) | Antarmuka bersih, responsif, dan ringan tanpa dependensi SPA berat |
| **Asset Bundler** | [Vite 6.x](https://vitejs.dev) | Kompilasi aset frontend super cepat dengan tree-shaking |
| **Geospatial GIS** | [Leaflet.js](https://leafletjs.com) & Esri Tiles | Pemetaan koridor interaktif dengan spline interpolator |
| **Data Visualizations** | [Chart.js 4.4](https://www.chartjs.org) | Donut chart, bar chart kargo, dan analitik emisi GLEC |
| **Database Support** | MySQL 8.4+ / SQLite 3 | Kompatibel dengan Laragon/XAMPP dan in-memory SQLite untuk pengujian cepat |
| **API Authentication**| Laravel Sanctum | Pengamanan token Bearer untuk integrasi REST API pihak ketiga |
| **Weather Telemetry** | [Open-Meteo API](https://open-meteo.com) | Integrasi cuaca maritim & pelabuhan dengan mekanisme offline fallback |
| **Automated Testing** | PHPUnit 11 | 39 automated tests (Unit, Feature, Authorization, Lifecycle, State Machine) |

---

## 4. Brand Design System & Color Palette

Platform menggunakan palet warna laut dan lingkungan (*oceanic & eco-stewardship palette*) yang modern dan elegan:

* **Warna Utama Brand**:
  * 🌊 **Sky Blue** (`#0284C7` / `#38BDF8`): Tombol aksi utama, tab navigasi aktif, dan jalur pengangkutan laut.
  * 🌿 **Teal Oceanic** (`#14B8A6` / `#0D9488`): Elemen keberlanjutan, metrik jejak karbon, dan audit emisi GLEC.
  * 🍃 **Emerald Green** (`#10B981` / `#34D399`): Status delivered, validasi penerimaan kargo, dan tingkat kepatuhan on-time.
* **Warna Netral Modern**:
  * **Slate Page Background**: `#F8FAFC`
  * **Deep Space Navy**: `#08162b` (Latar belakang kanvas satelit Leaflet)
  * **Dark Slate Heading**: `#0F172A`
  * **Subtle Slate Gray**: `#64748B` & `#E2E8F0`
* **Warna Status & Peringatan**:
  * 🚚 **Amber Transport**: `#F59E0B` (Perjalanan darat, penundaan jadwal transit)
  * ✈️ **Radiant Pink**: `#EC4899` (Penerbangan kargo udara / air cargo)
  * ⚠️ **Alert Red**: `#EF4444` (Kendala kritis, rute dialihkan, pemeriksaan dokumen bea cukai)

---

## 5. Pre-Configured Demo Accounts & Roles

Halaman masuk dirancang sebagai **Portal Enterprise Dual-Mode** (Pelanggan vs Staf Internal) dengan tombol kembali langsung ke Landing Page:

| Peran (Role) | Alias | Email Akun | Password | Rute Dashboard | Cakupan Wewenang |
| :--- | :--- | :--- | :--- | :--- | :--- |
| **Administrator** | Admin | `admin@ecohorizon.com` | `password123` | `/admin/dashboard` | Akses penuh: radar peta satelit, konfigurasi faktor emisi GLEC, manajemen peran & pengguna, audit log trails. |
| **Logistics Operator** | Staff | `operator@ecohorizon.com` | `password123` | `/operator/dashboard` | Akses operasional: manajemen kargo (CRUD), pembaruan kaki rute (legs), pencatatan delay, resolusi exception, validasi POD. |
| **Customer** | Client | `customer@globalcargo.com` | `password123` | `/customer/dashboard` | Akses pemilik kargo: pelacakan status konsinyasi, riwayat milestone, dan rincian estimasi emisi karbon. |
| *B2B Consignee* | Client | `buyer@bavariaindustries.de` | `password123` | `/customer/dashboard` | Akses penerima kargo: pemantauan jadwal kedatangan dermaga industri & konfirmasi serah terima gudang. |

> **💡 Petunjuk Masuk (Login Steps)**:
> 1. Buka rute `/login`.
> 2. Masukkan salah satu alamat email dan kata sandi di atas (mendukung pengisian manual atau *browser autofill*).
> 3. Tautan sakelar di bawah form memungkinkan beralih mode antara **Customer Portal** dan **Staff & Enterprise**.
> 4. Sistem secara otomatis mengarahkan ke dashboard yang sesuai (*role-based server redirection*).

---

## 6. Flagship Pre-Seeded Multimodal Consignments

Gunakan nomor resi (*tracking numbers*) berikut untuk mencoba fitur pencarian pada homepage atau konsol pelacakan:

### 1. `EHG-20261029-JKTMUC` — Multimodal B2B Warehouse Journey
* **Rute Perjalanan**: Cikarang GIIC Industrial Park (Indonesia) $\rightarrow$ Tanjung Priok $\rightarrow$ Port of Rotterdam $\rightarrow$ Munich Distribution Center (Germany).
* **Moda Multimodal**: Darat (Truk Kontainer) $\rightarrow$ Laut (Kapal Kontainer) $\rightarrow$ Udara (Kargo Udara Changi ke Frankfurt) $\rightarrow$ Darat (Kereta Rel Listrik/Truk).
* **Status Berjalan**: `IN_TRANSIT` melintasi koridor laut Samudra Hindia dan transit bandara.
* **Sorotan Utama**: Akumulasi perhitungan jejak karbon GLEC di setiap segmen dan pergantian titik transfer antarmoda.

### 2. `EHG-20261029-SHARTM` — Port-to-Port Maritime Exception
* **Rute Perjalanan**: Pelabuhan Shanghai (China) $\rightarrow$ Pelabuhan Rotterdam (Belanda).
* **Moda Transportasi**: Laut (*Ultra Large Container Vessel*).
* **Status Berjalan**: `IN_TRANSIT` dengan status exception aktif tingkat tinggi (*Weather Disruption & Cape of Good Hope Rerouting* +120 jam ETA).
* **Sorotan Utama**: Simulasi pengalihan rute badai laut, pelacakan deviasi waktu tiba, dan konsol resolusi operator.

### 3. `EHG-20261029-SUBB2B` — Trans-Java B2B Delivery (Delivered)
* **Rute Perjalanan**: Kawasan Industri Rungkut Surabaya $\rightarrow$ Pusat Logistik Jababeka Cikarang Barat.
* **Moda Transportasi**: Darat (*Heavy Articulated Electric Truck*).
* **Status Berjalan**: `DELIVERED` dengan bukti tanda terima dok terverifikasi (`#DC-JKT-88291`) dan konfirmasi petugas penerima (`Agus Setiawan`).
* **Sorotan Utama**: Penegakan validasi dokumen serah terima gudang B2B wajib (*mandatory receiving officer confirmation*).

### 4. `EHG-20261029-HNDLAX` — Transpacific Air Cargo with Customs Hold
* **Rute Perjalanan**: Bandara Internasional Tokyo Haneda (Jepang) $\rightarrow$ Bandara Internasional Los Angeles (AS).
* **Moda Transportasi**: Udara (*Dedicated Cargo Jetliner B777F*).
* **Status Berjalan**: `CUSTOMS_CLEARANCE` dengan peringatan pemeriksaan kepabeanan (*Customs Documentation Audit* +36 jam).
* **Sorotan Utama**: Kalkulasi faktor emisi penerbangan udara dan alur siklus inspeksi terminal bandara.

---

## 7. Installation & Execution Guide

### Persyaratan Sistem (Prerequisites)
* **PHP**: `^8.2` atau `^8.3` (ekstensi: `pdo`, `mbstring`, `openssl`, `curl`, `zip` aktif)
* **Composer**: `^2.2`
* **Node.js & NPM**: Node `^18.x`, `^20.x`, atau `^22.x`
* **Database**: MySQL 8.x (Laragon / XAMPP) atau SQLite 3

---

### Metode A: Instalasi Cepat dengan SQLite (Direkomendasikan untuk Pengujian & Demo)

```bash
# 1. Masuk ke direktori proyek
cd "EcoHorizon Global"

# 2. Salin file environment
cp .env.example .env

# 3. Pasang dependensi PHP melalui Composer
composer install

# 4. Buat kunci enkripsi aplikasi
php artisan key:generate

# 5. Buat file database SQLite lokal
touch database/database.sqlite

# 6. Jalankan migrasi tabel dan seeding data demo
php artisan migrate:fresh --seed

# 7. Pasang dependensi Node dan kompilasi aset frontend
npm install
npm run build

# 8. Jalankan development server lokal
php artisan serve
```
Buka peramban Anda di **[http://localhost:8000](http://localhost:8000)**.

---

### Metode B: Konfigurasi dengan Laragon / MySQL

1. Pastikan servis **MySQL** telah berjalan di Laragon atau XAMPP.
2. Buat database baru melalui HeidiSQL, phpMyAdmin, atau terminal:
   ```sql
   CREATE DATABASE ecohorizon_global CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
   ```
3. Sesuaikan konfigurasi database pada file `.env`:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=ecohorizon_global
   DB_USERNAME=root
   DB_PASSWORD=
   ```
4. Eksekusi migrasi database dan pengisian data:
   ```bash
   php artisan migrate:fresh --seed
   ```
5. Kompilasi aset frontend dan aktifkan server:
   ```bash
   npm run build
   php artisan serve
   ```

---

## 8. Automated Test Suite

EcoHorizon Global dilengkapi rangkaian pengujian otomatis lengkap (**39 Automated Feature & Unit Tests / 205 Assertions**) untuk memverifikasi keandalan sistem tanpa kompromi:

* **Role & Security Authorization**: Penegakan hak akses antar peran pengguna (*Admin, Operator, Customer*).
* **Language Switcher (i18n)**: Pengujian perpindahan dwibahasa (EN $\leftrightarrow$ ID), persistensi sesi/cookie, dan integritas kamus terjemahan.
* **Shipment Lifecycle State Machine**: Validasi transisi status konsinyasi dan proteksi terminal kedatangan.
* **B2B Delivery Constraints**: Penegakan verifikasi dokumen dan nama penerima dok saat status `DELIVERED`.
* **Carbon Intelligence & Haversine Distance**: Akurasi kalkulasi jarak geodesik dan rumus emisi standar GLEC.
* **Interactive Map & Dashboard Cockpit**: Integritas data Leaflet radar, grafik distribusi jalur, dan peniadaan rute redundan.
* **Sanctum REST API**: Pengujian token Bearer publik dan terautentikasi.

Jalankan test suite menggunakan perintah Laravel Artisan:
```bash
php artisan test
```

Hasil eksekusi resmi:
```text
   PASS  Tests\Unit\ExampleTest
  ✓ that true is true                                                                                            0.01s  

   PASS  Tests\Feature\AdminDashboardMapTest
  ✓ admin dashboard renders with interactive map and shipment data                                               1.30s  
  ✓ fleet map page is completely removed                                                                         0.44s  

   PASS  Tests\Feature\ApiShipmentTest
  ✓ public api tracking endpoint                                                                                 0.13s  
  ✓ api sanctum login and authenticated shipment creation                                                        0.18s  
  ✓ customer cannot access unauthorized shipment via api                                                         0.14s  

   PASS  Tests\Feature\AuthenticationAndRoleTest
  ✓ unauthenticated user is redirected to login                                                                  0.14s  
  ✓ admin can access admin dashboard and user directory                                                          0.16s  
  ✓ operator cannot access admin portal but can access operator cockpit                                          0.15s  
  ✓ customer can only access customer workspace                                                                  0.14s  
  ✓ login validation and session authentication                                                                  0.14s  
  ✓ customer can register new account                                                                            0.13s  

   PASS  Tests\Feature\B2BWarehouseDeliveryTest
  ✓ b2b warehouse delivery requires receiving officer confirmation                                               0.15s  

   PASS  Tests\Feature\CarbonCalculationTest
  ✓ haversine distance calculation                                                                               0.11s  
  ✓ carbon emission formula accuracy                                                                             0.12s  

   PASS  Tests\Feature\ExampleTest
  ✓ the application returns a successful response                                                                0.06s  

   PASS  Tests\Feature\InternalTrackingConsoleTest
  ✓ unauthenticated user cannot access internal tracking                                                         0.34s  
  ✓ admin can access internal tracking console                                                                   1.52s  
  ✓ admin can track specific shipment by tracking number                                                         1.44s  
  ✓ customer can track their own shipment                                                                        1.42s  
  ✓ customer cannot track other customer shipment                                                                0.35s  
  ✓ sidebar tracking link points to internal console                                                             0.43s  
  ✓ door to door is completely removed and replaced with b2b                                                     1.45s  

   PASS  Tests\Feature\LanguageSwitchTest
  ✓ default locale is english                                                                                    0.36s  
  ✓ switch language to indonesian stores session and translates                                                  0.35s  
  ✓ switch language to english                                                                                   0.35s  
  ✓ invalid locale does not change session                                                                       0.33s  
  ✓ authenticated admin portal translates sidebar in indonesian                                                  0.42s  
  ✓ shipments registry translates table headers in indonesian                                                    0.38s  
  ✓ internal tracking console translates in indonesian                                                           1.45s  
  ✓ sustainability analytics translates in indonesian                                                            0.36s  
  ✓ public tracking translates in indonesian                                                                     1.42s  

   PASS  Tests\Feature\PublicTrackingViewTest
  ✓ public tracking page renders with multimodal journey and map data                                            1.43s  
  ✓ public tracking page renders exception details                                                               1.42s  
  ✓ public tracking search redirects to shipment                                                                 0.34s  
  ✓ sustainability dashboard renders for authenticated users                                                     0.36s  

   PASS  Tests\Feature\ShipmentLifecycleTest
  ✓ valid shipment status transitions succeed                                                                    0.13s  
  ✓ invalid status transition is rejected with exception                                                         0.12s  
  ✓ terminal arrival for port to port transitions to awaiting pickup not delivered                               0.12s  

  Tests:    39 passed (205 assertions)
  Duration: ~21.5s
```

---

## 9. REST API Documentation & Postman

Platform menyediakan antarmuka REST API berstandar OpenAPI yang diamankan oleh Laravel Sanctum:

### Endpoint Publik (Tanpa Autentikasi)
* `GET /api/v1/track/{tracking_number}`: Mengambil data pelacakan publik lengkap (koordinat terkini, lini masa milestone, exception aktif, dan jejak karbon).

### Endpoint Terautentikasi (Memerlukan Bearer Token)
* `POST /api/v1/auth/login`: Autentikasi akun dan penerbitan Sanctum API Token.
* `GET /api/v1/shipments`: Menampilkan daftar konsinyasi yang diizinkan (paginasi).
* `POST /api/v1/shipments`: Membuat pengiriman kargo baru beserta spesifikasi berat & volume.
* `GET /api/v1/shipments/{id}`: Menampilkan detail dossier pengiriman, daftar legs, dan riwayat event.
* `POST /api/v1/shipments/{id}/events`: Mencatat milestone perjalanan kargo terkini.
* `POST /api/v1/shipments/{id}/exceptions`: Mencatat insiden atau penundaan kargo (*exceptions*).

Koleksi Postman siap pakai telah disediakan pada direktori root proyek:
📄 `ecohorizon_postman_collection.json`

---

## 10. Technical Documentation Index & Academic Authorship

Dokumentasi arsitektur dan teknis mendalam tersedia pada folder `docs/`:

* 📊 **[docs/ERD.md](docs/ERD.md)**: Diagram Entity-Relationship berbasis Mermaid yang merinci relasi dan kardinalitas antar entitas basis data.
* 📖 **[docs/DATA_DICTIONARY.md](docs/DATA_DICTIONARY.md)**: Kamus data lengkap kolom per kolom untuk seluruh 10 tabel basis data.
* 🏛️ **[docs/ARCHITECTURE.md](docs/ARCHITECTURE.md)**: Diagram State Machine transisi status pengiriman, formula perhitungan emisi GLEC v3.0, dan rancangan arsitektur sistem.

---

### 🎓 Identitas Proyek Akademik
* **Institusi**: Tugas Akhir Mata Kuliah / Proyek UAS
* **Nama Platform**: EcoHorizon Global — Global Multimodal Freight Visibility & Carbon Intelligence Platform
* **Tahun Rilis**: 2026
* **Status Lisensi**: Academic & Educational Open Source (MIT)
