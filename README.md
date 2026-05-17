# Portal Anggota PSHT Cabang Jember

Aplikasi portal anggota PSHT Cabang Jember — migrasi dari sistem Google Apps Script + Google Sheets ke aplikasi Laravel 12 dengan MySQL. Anggota dapat login menggunakan NIK + password untuk melihat data keanggotaan, sertifikat, tagihan, dan riwayat pembayaran. Admin mengelola data melalui panel Filament v3 di `/admin`.

## Tech Stack

| Komponen | Versi |
|---|---|
| PHP | 8.2.12 |
| Laravel | 12.x |
| Composer | 2.9.x |
| Database | MySQL 8.0 / MariaDB 10.4+ |
| CSS | Tailwind v4 (via Vite) |
| Admin | Filament v3 |
| PDF | spatie/browsershot + puppeteer/chromium |
| Ikon | lucide |
| Test | Pest v3 |

## Quickstart (Windows + XAMPP)

```bash
# 1. Clone & install dependencies
composer install
npm install

# 2. Pastikan Chromium puppeteer terinstall (untuk PDF sertifikat)
npx puppeteer browsers install chrome-headless-shell

# 3. Setup .env
cp .env.example .env
php artisan key:generate

# 4. Setup database (sesuaikan DB_* di .env terlebih dahulu)
# Buat database psht_jember terlebih dahulu di phpMyAdmin / MySQL CLI:
#   CREATE DATABASE psht_jember CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
php artisan migrate:fresh --seed

# 5. Symlink storage
php artisan storage:link

# 6. Build asset frontend
npm run build

# 7. Jalankan
php artisan serve
# Portal: http://localhost:8000
# Admin:  http://localhost:8000/admin
```

## Kredensial Seed

**Admin (panel Filament):**
- URL: `http://localhost:8000/admin`
- Email: `admin@psht-jember.local`
- Password: nilai dari `ADMIN_SEED_PASSWORD` di `.env` (default: `ChangeMe!Now2026`)

**Anggota sample (portal):**

| Nama | NIK | Password (= tanggal lahir ddmmyyyy) |
|---|---|---|
| Andi Wijaya | `3509291199500001` | `29111995` |
| Budi Santoso | `3509151088700002` | `15101987` |
| Citra Dewi | `3509050595500003` | `05051995` |

Semua anggota seed `is_active=true` dan `must_change_password=true`, sehingga login pertama otomatis redirect ke halaman ubah password.

## Arsitektur (Service Pattern)

```
app/
├── Http/
│   ├── Controllers/        — Thin controllers, delegate ke service
│   ├── Requests/           — Form Request validation
│   └── Middleware/         — Security headers, throttle, require password change
├── Services/
│   ├── Auth/               — MemberAuthService, LoginAttemptService
│   ├── Member/             — MemberProfileService
│   ├── Billing/            — BillService, PaymentHistoryService
│   ├── Certificate/        — CertificatePdfService (Browsershot wrapper)
│   └── Logging/            — KamusErrorLogger
├── Models/                 — Eloquent dengan typed relasi, casts, dan enums
├── Enums/                  — MemberStatus, BillStatus (PHP 8.2 native enums)
├── Support/                — NikEncryptor, NikValidator
├── Exceptions/             — InvalidCredentials, AccountInactive, TooManyAttempts, dll.
└── Filament/Resources/     — Admin CRUD (Member, Bill, PaymentHistory, Certificate)
```

Aturan: controller hanya delegate ke service. Semua business logic (auth flow, password rules, bill status recompute, PDF generation) di service. Setiap method service punya return type, parameter types, dan `@throws` annotation.

## Routes

### Public (guest:member)
- `GET /login` → form login
- `POST /login` → autentikasi (rate-limited via `throttle.login`)

### Authenticated (auth:member)
- `POST /logout` → keluar
- `GET /password/change` / `POST /password/change` → ubah password (wajib di login pertama)
- Berikut dilindungi `require.password.change` (block sampai password default diganti):
  - `GET /dashboard`
  - `GET /profile`
  - `GET /bills`
  - `GET /payment-history`
  - `GET /certificates`
  - `GET /certificates/{certificate}/download` — generate & unduh PDF (ownership check 403)

### Admin (Filament, web guard, `is_admin=true`)
- `/admin` — dashboard
- `/admin/members` — Resource Anggota (input NIK, kolom NIK termasking, aksi Aktifkan/Reset Password)
- `/admin/bills` — Resource Tagihan
- `/admin/payment-histories` — Resource Riwayat Pembayaran (auto-recompute status tagihan)
- `/admin/certificates` — Resource Sertifikat (aksi Generate PDF)

## Database Schema

| Tabel | Catatan |
|---|---|
| `users` | Admin Filament (default Laravel auth + flag `is_admin`) |
| `members` | Anggota portal — `nik_hash` (SHA-256 unique) + `nik_encrypted` (Laravel Crypt), password bcrypt |
| `bills` | Tagihan — `sisa` adalah generated column `nominal - dibayar` |
| `payment_histories` | Riwayat bayar, opsional terhubung ke `bills` |
| `certificates` | Sertifikat — `nomor` unique |
| `login_attempts` | Audit login (hanya menyimpan `nik_hash`, tidak pernah NIK plaintext) |

Semua tabel pakai foreign key dengan `cascadeOnDelete` ke `members` (kecuali `login_attempts` yang independen).

## Testing

```bash
php artisan test
```

10 test Pest mencakup:
- **LoginTest** (6): valid login, NIK tidak dikenal, password salah, akun belum aktif, throttle 5×, paksa ubah password
- **BillTest** (2): isolasi tagihan per anggota, kalkulasi `sisa` via generated column
- **CertificateTest** (2): tolak download sertifikat anggota lain, listing milik sendiri

Database test: SQLite `:memory:` (konfigurasi di `phpunit.xml`). Trait `RefreshDatabase` dipakai default via `tests/Pest.php`.

## Konfigurasi Penting (`.env`)

```env
APP_LOCALE=id
APP_FAKER_LOCALE=id_ID

DB_CONNECTION=mysql
DB_DATABASE=psht_jember

# Session security (UU PDP)
SESSION_DRIVER=database
SESSION_LIFETIME=60
SESSION_ENCRYPT=true
SESSION_SECURE_COOKIE=false  # set true di produksi (HTTPS)
SESSION_HTTP_ONLY=true
SESSION_SAME_SITE=lax

# Custom guard
AUTH_GUARD=member
AUTH_MODEL=App\Models\Member

# Admin seed
ADMIN_SEED_EMAIL=admin@psht-jember.local
ADMIN_SEED_PASSWORD=...

# Logging kamus.zasha.online (opsional)
KAMUS_ERROR_ENDPOINT=
KAMUS_ERROR_TOKEN=
```

## Logging Kustom (`kamus_error`)

Semua exception otomatis di-route ke channel `kamus_error` (file harian di `storage/logs/kamus-error-YYYY-MM-DD.log`) **dan** opsional di-forward ke endpoint remote `KAMUS_ERROR_ENDPOINT` via Guzzle. Payload mencakup `timestamp`, `trace_id` (UUID), `exception_class`, `user_context` (member_id + NIK termasking), dan request metadata.

Jika forward gagal, akan jatuh ke `error_log()` PHP (tidak crash aplikasi). Konfigurasi: lihat `app/Services/Logging/KamusErrorLogger.php`.

## Catatan Implementasi

- **Validasi NIK:** spek menyebut "16-digit + checksum" namun NIK Indonesia tidak punya algoritma checksum publik (NIK = region + DOB + serial). Implementasi memvalidasi format 16 digit numerik via `NikValidator::isWellFormed()`. Lihat `app/Support/NikValidator.php`.
- **Filament guard:** admin pakai `users` table + guard `web` (Laravel default). Anggota portal pakai `members` table + guard `member` (custom).
- **Generated column `sisa`:** kompatibel MySQL/MariaDB/SQLite via Laravel `storedAs('nominal - dibayar')`.
- **Browsershot:** butuh Node + Chromium. Di Windows, pakai cache `~/.cache/puppeteer/`. Set `BROWSERSHOT_CHROME_PATH` di `.env` jika ingin override.

## Security

Lihat [SECURITY.md](SECURITY.md) untuk dokumentasi lengkap enkripsi NIK, kepatuhan UU PDP, dan kontrol keamanan lainnya.
