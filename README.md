# EcoHorizon Global
### Global Multimodal Freight Visibility Platform
> **"Track Every Journey. Move Toward a Greener Future."**

[![Laravel](https://img.shields.io/badge/Laravel-11.x-FF2D20?style=flat&logo=laravel&logoColor=white)](https://laravel.com)
[![PHP](https://img.shields.io/badge/PHP-8.3-777BB4?style=flat&logo=php&logoColor=white)](https://php.net)
[![Tailwind CSS](https://img.shields.io/badge/Tailwind_CSS-3.4-38B2AC?style=flat&logo=tailwind-css&logoColor=white)](https://tailwindcss.com)
[![Tests](https://img.shields.io/badge/Tests-16%20Passed%20(50%20Assertions)-10B981?style=flat&logo=checkmarx&logoColor=white)](tests)
[![Standard](https://img.shields.io/badge/Carbon_Standard-GLEC_Framework_v3.0-0284C7?style=flat)](docs/ARCHITECTURE.md)

---

## 1. Executive Summary & Project Identity

**EcoHorizon Global** is an enterprise-grade, web-based freight visibility and shipment tracking platform engineered for global multimodal logistics operations. Designed as a university final project (UAS), the application solves critical visibility blindspots across **Sea**, **Land**, and **Air** transportation corridors while embedding certified **carbon emissions accounting** directly into operational tracking workflows.

Unlike domestic courier tracking tools, EcoHorizon Global addresses the nuanced realities of international freight:
- **True Multimodal Journeys**: Managing sequential transport legs across diverse carriers, transport modes, and transshipment hubs.
- **Contractual Service Levels**: Rigid distinction between **Door-to-Door**, **Door-to-Port**, **Port-to-Port**, and **B2B** delivery scopes.
- **Port Handover vs Final Delivery**: Preventing premature delivery claims when cargo arrives at intermediate terminals or seaports.
- **Auditable Carbon Footprint**: Calculating greenhouse gas emissions ($\text{kg CO}_2\text{e}$) per leg using the **GLEC Framework v3.0** (ISO 14083 compliant).
- **Proactive Exception & Delay Watch**: Logging weather anomalies, maritime rerouting, customs holds, and equipment disruptions with quantified ETA impacts.

---

## 2. Technology Stack

- **Backend Framework**: [Laravel 11](https://laravel.com) (PHP 8.3)
- **Frontend & UI**: Laravel Blade Templates, [Tailwind CSS 3.4](https://tailwindcss.com), [Vite](https://vitejs.dev)
- **Geospatial Mapping**: [Leaflet.js](https://leafletjs.com) with [OpenStreetMap](https://www.openstreetmap.org)
- **Data Visualization**: [Chart.js 4.4](https://www.chartjs.org) (Emission breakdowns, status distribution, modal share)
- **Database**: MySQL 8.4+ (Laragon / Production) with native SQLite fallback for rapid testing
- **API Security**: Laravel Sanctum (Token-based REST API authentication)
- **Weather Intelligence**: [Open-Meteo REST API](https://open-meteo.com) with intelligent offline resilience
- **Testing Harness**: PHPUnit 11 (Unit, Feature, Authorization, Lifecycle, and State Machine tests)

---

## 3. Brand Personality & Design System

The platform features a **calm, clean, and modern blue-green palette** evoking oceanic shipping lanes and sustainable environmental stewardship:

- **Primary Brand Colors**:
  - Sky Blue: `#0284C7` (Primary buttons, active navigation, active legs)
  - Teal: `#14B8A6` (Secondary brand elements, oceanic accents)
  - Emerald Green: `#10B981` (Completed legs, delivery confirmation, carbon efficiency)
- **Supporting Neutral Colors**:
  - Soft White Page Background: `#F8FAFC`
  - Dark Navy Heading & Text: `#0F172A`
  - Slate Gray Secondary Text: `#64748B`
  - Border Gray: `#E2E8F0`
- **Functional Alert Accents**:
  - Amber Warning: `#F59E0B` (Transport delays, awaiting handover)
  - Alert Red: `#EF4444` (Critical exceptions, severe weather holds, cancelled legs)

---

## 4. Pre-Configured Demo Accounts (3 Roles)

Halaman login dirancang sebagai **Enterprise Portal Login manual penuh** (tanpa tombol demo publik di antarmuka). Gunakan kredensial berikut untuk masuk sesuai dengan 3 peran pengguna:

| Role | Alias | Email Address | Password | Landing Dashboard | Deskripsi Wewenang |
| :--- | :--- | :--- | :--- | :--- | :--- |
| **Administrator** | Admin | `admin@ecohorizon.test` | `password123` | `/admin/dashboard` | Akses penuh: KPI metrik global, analitik Chart.js, manajemen user & peran, GLEC carbon factors, audit trails. |
| **Logistics Operator** | Staff | `operator@ecohorizon.test` | `password123` | `/operator/dashboard` | Akses staf operasional: Konsinyasi CRUD, update kaki rute (legs), pencatatan event & delay, resolusi exception, validasi POD. |
| **Customer** | User | `customer@globalcargo.test` | `password123` | `/customer/dashboard` | Akses pengguna/pengirim: Pelacakan kiriman resmi, transparansi status leg, estimasi jejak karbon (CO₂e). |
| *B2B Consignee* | User | `buyer@bavariaindustries.de` | `password123` | `/customer/dashboard` | Akses penerima kargo B2B: Pemantauan ETA gudang tujuan & konfirmasi serah terima dok. |

> **Catatan Penggunaan Login**:
> 1. Buka rute `/login`.
> 2. Ketikkan **Email Address** dan **Password** secara manual sesuai tabel di atas.
> 3. Browser mendukung *autofill*, riwayat peramban, dan opsi centang **"Remember this device"**.
> 4. Sistem secara otomatis melakukan pengalihan server-side (*server-side role redirection*) ke dashboard masing-masing peran.

---

## 5. Flagship Pre-Seeded Multimodal Journeys

Search any of these tracking numbers on the homepage or public tracking bar:

### 1. `EHG-20261029-JKTMUC` — Multimodal Door-to-Door Journey
- **Route**: Cikarang Industrial Park (Indonesia) $\rightarrow$ Tanjung Priok $\rightarrow$ Port of Rotterdam $\rightarrow$ Munich Distribution Center (Germany).
- **Modes**: Land (Truck) $\rightarrow$ Sea (Container Vessel) $\rightarrow$ Land (Electric Freight Train).
- **Current Status**: `IN_TRANSIT` on the maritime leg across the Indian Ocean / Suez Canal.
- **Key Highlight**: Demonstrates leg-by-leg GLEC emission accumulation and transshipment hub tracking.

### 2. `EHG-20261029-SHARTM` — Port-to-Port Maritime Exception
- **Route**: Port of Shanghai (China) $\rightarrow$ Port of Rotterdam (Netherlands).
- **Modes**: Sea (Ultra Large Container Vessel).
- **Current Status**: `IN_TRANSIT` with an active `HIGH` severity **Weather Disruption & Maritime Rerouting** exception (+120 hours ETA impact around Cape of Good Hope).
- **Key Highlight**: Demonstrates delay tracking, customer-visible exception alerts, and operator resolution workflows.

### 3. `EHG-20261029-SUBB2B` — B2B Warehouse Delivery (Completed)
- **Route**: Rungkut Industrial Estate Surabaya $\rightarrow$ Jababeka Cikarang Logistics Center.
- **Modes**: Land (Heavy Articulated Truck).
- **Current Status**: `DELIVERED` with verified dock receipt `#DC-JKT-88291` and receiving officer confirmation (`Agus Setiawan`).
- **Key Highlight**: Demonstrates mandatory Proof-of-Delivery constraints and B2B dock handover validation.

### 4. `EHG-20261029-HNDLAX` — Transpacific Air Freight with Customs Hold
- **Route**: Tokyo Haneda Airport (Japan) $\rightarrow$ Los Angeles International Airport (USA).
- **Modes**: Air (Dedicated Cargo Aircraft B777F).
- **Current Status**: `CUSTOMS_CLEARANCE` with an active Customs Documentation Review exception (+36 hours).
- **Key Highlight**: Demonstrates air freight emission calculations and customs inspection lifecycle status.

---

## 6. Installation & Execution Guide

### Prerequisites
- **PHP**: `^8.2` or `^8.3` (with `pdo`, `mbstring`, `openssl`, `curl`, `zip` extensions enabled)
- **Composer**: `^2.2`
- **Node.js & NPM**: Node `^18.x` or `^20.x` / `^22.x`
- **Database**: MySQL 8+ (e.g. Laragon / XAMPP) or SQLite

---

### Method A: Quick Start with SQLite (Recommended for Testing & Demo)

```bash
# 1. Clone or navigate to the project directory
cd "c:\Rizka\Project UAS\Global Multimodal Freight Visibility Platform\EcoHorizon Global"

# 2. Copy the environment configuration
cp .env.example .env

# 3. Install PHP and Composer dependencies
composer install

# 4. Generate the application encryption key
php artisan key:generate

# 5. Create the SQLite database file
touch database/database.sqlite

# 6. Run database migrations and seed all demo data
php artisan migrate:fresh --seed

# 7. Install NPM dependencies and compile frontend assets
npm install
npm run build

# 8. Start the local development server
php artisan serve
```
Open **[http://localhost:8000](http://localhost:8000)** in your browser.

---

### Method B: Setup with Laragon / MySQL

1. Start **Apache** and **MySQL** in your Laragon or XAMPP control panel.
2. Open HeidiSQL, phpMyAdmin, or your MySQL CLI and create a database:
   ```sql
   CREATE DATABASE ecohorizon_global CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
   ```
3. Edit your `.env` file to enable MySQL:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=ecohorizon_global
   DB_USERNAME=root
   DB_PASSWORD=
   ```
4. Run migrations and seeders:
   ```bash
   php artisan migrate:fresh --seed
   ```
5. Compile assets and start the application:
   ```bash
   npm run build
   php artisan serve
   ```

---

## 7. Automated Test Suite Execution

The application comes with an automated test suite verifying role authorization, shipment state transitions, terminal handover protections, B2B proof-of-delivery mandates, Haversine calculations, GLEC emission formulas, and Sanctum REST APIs.

Run tests using the Laravel test runner:
```bash
php artisan test
```

Expected output:
```text
   PASS  Tests\Unit\ExampleTest
  ✓ that true is true

   PASS  Tests\Feature\ApiShipmentTest
  ✓ public api tracking endpoint
  ✓ api sanctum login and authenticated shipment creation
  ✓ customer cannot access unauthorized shipment via api

   PASS  Tests\Feature\AuthenticationAndRoleTest
  ✓ unauthenticated user is redirected to login
  ✓ admin can access admin dashboard and user directory
  ✓ operator cannot access admin portal but can access operator cockpit
  ✓ customer can only access customer workspace
  ✓ login validation and session authentication

   PASS  Tests\Feature\B2BWarehouseDeliveryTest
  ✓ b2b warehouse delivery requires receiving officer confirmation

   PASS  Tests\Feature\CarbonCalculationTest
  ✓ haversine distance calculation
  ✓ carbon emission formula accuracy

   PASS  Tests\Feature\ExampleTest
  ✓ the application returns a successful response

   PASS  Tests\Feature\ShipmentLifecycleTest
  ✓ valid shipment status transitions succeed
  ✓ invalid status transition is rejected with exception
  ✓ terminal arrival for port to port transitions to awaiting pickup not delivered

  Tests:    16 passed (50 assertions)
  Duration: ~2.4s
```

---

## 8. REST API & Postman Collection

EcoHorizon Global includes a documented REST API powered by Laravel Sanctum:

- **Public Endpoints**:
  - `GET /api/v1/track/{tracking_number}`: Public tracking data including current coordinates, milestone timeline, active exceptions, and carbon estimates.
- **Authenticated Endpoints** (Bearer Token required):
  - `POST /api/v1/auth/login`: Authenticate and obtain Sanctum bearer token.
  - `GET /api/v1/shipments`: List authorized shipments (paginated).
  - `POST /api/v1/shipments`: Create new multimodal shipment with cargo specifications.
  - `GET /api/v1/shipments/{id}`: Detailed shipment dossier with transport legs and events.
  - `POST /api/v1/shipments/{id}/events`: Record tracking milestone event.
  - `POST /api/v1/shipments/{id}/exceptions`: Log operational exception.

A complete Postman collection is ready for import at:
`ecohorizon_postman_collection.json`

---

## 9. Comprehensive Technical Documentation

Explore the detailed technical documentation in the `docs/` directory:
- [Entity-Relationship Diagram (docs/ERD.md)](docs/ERD.md): Visual Mermaid ERD with relationships and cardinalities.
- [Data Dictionary (docs/DATA_DICTIONARY.md)](docs/DATA_DICTIONARY.md): Column-by-column breakdown of all 10 database tables.
- [System Architecture (docs/ARCHITECTURE.md)](docs/ARCHITECTURE.md): State machine diagrams, GLEC emission formulas, and service architecture.

---

## 10. Academic Project Authorship

- **Project**: University Final Project (UAS)
- **Platform**: EcoHorizon Global — Global Multimodal Freight Visibility Platform
- **Year**: 2026
