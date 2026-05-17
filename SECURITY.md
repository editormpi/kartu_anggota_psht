# Security & Kepatuhan UU PDP

Dokumen ini menjelaskan pendekatan keamanan Portal Anggota PSHT Cabang Jember, khususnya terkait perlindungan NIK sebagai data pribadi spesifik berdasarkan UU No. 27 Tahun 2022 tentang Perlindungan Data Pribadi (UU PDP).

## Klasifikasi Data

| Field | Klasifikasi | Penanganan |
|---|---|---|
| NIK | Data Pribadi Spesifik | Enkripsi at-rest + hash lookup, tidak pernah di-log plaintext |
| Password | Kredensial | bcrypt (Hash facade), salt otomatis, tidak pernah dikembalikan ke client |
| Tanggal lahir | Data Pribadi Umum | Plain (digunakan sebagai default password kebijakan) |
| Alamat, HP, Foto | Data Pribadi Umum | Plain, hanya bisa diakses oleh anggota sendiri & admin |

## Penyimpanan NIK

NIK **tidak pernah** disimpan dalam bentuk plaintext. Setiap record `members` punya dua kolom:

1. **`nik_hash`** (`char(64)`, indexed unique) — `hash('sha256', $nik)`. Hanya digunakan untuk lookup saat login. Deterministik (NIK yang sama menghasilkan hash yang sama), sehingga login tetap O(1) tanpa membaca ciphertext.
2. **`nik_encrypted`** (`text`) — `Illuminate\Support\Facades\Crypt::encryptString($nik)`. Memakai `APP_KEY` (AES-256-CBC dengan HMAC). Hanya didekripsi saat anggota perlu melihat NIK-nya sendiri di profil (`Member::getNik()`) atau saat admin perlu memverifikasi.

```php
// app/Support/NikEncryptor.php
public function hash(string $nik): string
{
    return hash('sha256', $nik);
}

public function encrypt(string $nik): string
{
    return Crypt::encryptString($nik);
}
```

**Trade-off:** SHA-256 deterministik berarti dictionary attack pada `nik_hash` masih mungkin (rentang NIK Indonesia ~10¹⁶, tidak feasible brute-force tapi dictionary terbatas mungkin). Mitigasi: kontrol akses ketat ke database. Untuk kepatuhan tingkat lebih tinggi, pertimbangkan HMAC dengan secret key terpisah.

### NIK Tidak Pernah Di-Log

- `login_attempts` hanya menyimpan `nik_hash`, bukan NIK plaintext, untuk audit forensik tanpa mengekspos PII.
- `KamusErrorLogger` (`app/Services/Logging/KamusErrorLogger.php`) memask NIK di `user_context.masked_nik` menjadi `3509xxxxxxxx0001` (4 awal + 8 mask + 4 akhir) via `NikEncryptor::mask()`.
- Tidak ada controller yang `Log::info($request->all())` — semua input lewat Form Request.

Verifikasi: `grep -r "\\d\{16\}" storage/logs/` harus kosong setelah run.

## Autentikasi & Sesi

### Login Flow (`MemberAuthService::attempt`)

1. Validasi format NIK (`NikValidator::isWellFormed` → regex `/^\d{16}$/`).
2. Cek throttle (`LoginAttemptService::isThrottled`):
   - **5 kegagalan per IP per 15 menit** → block.
   - **10 kegagalan per `nik_hash` per 1 jam** → block.
3. Cari `Member::where('nik_hash', $hash)->first()`. Jika tidak ada → log failure (`unknown_nik`) + throw `InvalidCredentialsException`.
4. Verifikasi password dengan `Hash::check`. Salah → log failure (`wrong_password`) + throw `InvalidCredentialsException`.
5. Cek `is_active`. False → log failure (`inactive_account`) + throw `AccountInactiveException`.
6. Sukses → `Auth::guard('member')->login($member)`, update `last_login_at`, regenerate session ID.

### Aturan Pesan Error Generik

Pesan untuk NIK tidak dikenal **dan** password salah sama-sama `"NIK atau password salah"`. Ini mencegah username enumeration. Hanya `AccountInactiveException` yang punya pesan spesifik `"Akun belum aktif, hubungi admin"` — pesan ini bocor sedikit info tapi disengaja untuk UX (anggota baru tahu harus menghubungi admin).

### Defense in Depth — Throttling

Dua lapis rate limit:
1. **`ThrottleLoginByIp` middleware** (`app/Http/Middleware/ThrottleLoginByIp.php`): 10 hit per IP per 60 detik via `RateLimiter`. Pre-controller, mencegah brute force fast-path.
2. **`LoginAttemptService` audit log**: 5 gagal/IP/15min + 10 gagal/NIK/jam, dicek di dalam service. Survive memory cache restart (persistent di DB).

### Sesi

- **Lifetime:** 60 menit idle (`SESSION_LIFETIME=60`).
- **Encryption:** `SESSION_ENCRYPT=true` — payload sesi terenkripsi.
- **Cookie flags:** `HttpOnly=true`, `SameSite=lax`. `Secure=true` di produksi (HTTPS).
- **Regenerasi:** session ID di-regenerate setiap login sukses (mencegah session fixation).

## CSRF, XSS, dan Injeksi

- **CSRF:** semua form (`POST /login`, `POST /password/change`, `POST /logout`) di-protect oleh middleware `web` Laravel + `@csrf` directive.
- **XSS:** semua output Blade pakai `{{ }}` (auto-escape via `htmlspecialchars`). Tidak ada `{!! !!}` pada user-supplied content.
- **SQL Injection:** semua query lewat Eloquent / Query Builder yang parameter-binding. Tidak ada raw SQL dengan interpolasi user input.
- **Mass assignment:** semua model punya `$fillable` eksplisit; admin Filament memakai mutator `applyNikAndPassword` untuk field sensitif.

## Security Headers

Middleware `AddSecurityHeaders` (`app/Http/Middleware/AddSecurityHeaders.php`) menambahkan ke setiap response:

- `X-Content-Type-Options: nosniff`
- `X-Frame-Options: DENY` (mencegah clickjacking)
- `Referrer-Policy: strict-origin-when-cross-origin`
- `Permissions-Policy: camera=(), microphone=(), geolocation=()`
- `Strict-Transport-Security: max-age=31536000; includeSubDomains` (hanya di `APP_ENV=production`)

## Otorisasi

- **Portal:** route group `auth:member` mewajibkan login. `require.password.change` middleware memblok semua route lain kecuali halaman ubah password sampai password default diganti.
- **Sertifikat:** `CertificateController::download` cek `abort_if($certificate->member_id !== $member->id, 403)` — anggota tidak bisa unduh sertifikat orang lain (tested).
- **Admin:** Filament panel cek `User::canAccessPanel()` → `is_admin === true`. Anggota tanpa akses admin akan diredirect.

## Upload File (Foto Anggota)

Konfigurasi yang direkomendasikan untuk field `photo_path` di Filament:
- Max 2MB
- MIME: jpg, jpeg, png
- Disimpan ke `storage/app/public/photos/{member_id}/...`
- Diakses via symlink `public/storage` (sudah disetup via `php artisan storage:link`)

Implementasi upload field foto di Filament saat ini menggunakan TextInput plain — perluasan ke FileUpload field dapat ditambahkan sesuai kebutuhan.

## PDF Generation (Browsershot)

- Browsershot menjalankan Chromium headless via puppeteer. Di produksi (`APP_ENV=production`), `noSandbox()` diaktifkan karena Chromium di container biasanya butuh ini.
- Timeout 30 detik untuk mencegah hang/DoS.
- Output disimpan ke `storage/app/certificates/{member_id}/{cert_id}.pdf` (di luar `public/`, tidak directly accessible).
- Akses lewat controller yang melakukan ownership check.

## Audit & Monitoring

- **Login attempts** (`login_attempts` table): timestamp, IP, user-agent, `nik_hash`, success/failure, failure_reason. Bisa di-query untuk deteksi brute force atau anomali.
- **Exception logging** (`kamus_error` channel): semua exception ditangkap di `bootstrap/app.php` → `KamusErrorLogger::report()`. Mencakup `trace_id` UUID untuk korelasi cross-system, masked user context, request metadata.
- **Opsional remote sink**: set `KAMUS_ERROR_ENDPOINT` ke endpoint internal (mis. `kamus.zasha.online`) — failure forward tidak meng-crash aplikasi (try/catch ke `error_log()`).

## Kepatuhan UU PDP — Checklist

| Pasal | Penerapan |
|---|---|
| Ps. 16 (Pemrosesan terbatas pada tujuan) | NIK hanya digunakan untuk autentikasi & identifikasi anggota, tidak dishare ke pihak ketiga |
| Ps. 35 (Keamanan teknis) | Enkripsi at-rest (AES-256), hashing password (bcrypt), HTTPS-ready (HSTS) |
| Ps. 37 (Integritas) | Foreign key cascade, generated column, audit log |
| Ps. 39 (Penghapusan) | `cascadeOnDelete` pada relasi member; admin bisa hapus member di Filament |
| Ps. 46 (Notifikasi pelanggaran) | `kamus_error` channel dapat diintegrasikan ke pipeline notifikasi insiden |

## Yang Belum Diimplementasikan (Future Work)

- **HMAC-based NIK index** dengan secret key terpisah dari `APP_KEY` untuk mempersulit dictionary attack pada `nik_hash`.
- **Two-Factor Authentication** (TOTP) untuk admin Filament.
- **Content Security Policy (CSP)** header — saat ini disengaja tidak ditambahkan karena Vite dev mode butuh `unsafe-eval` di local. Di produksi disarankan: `default-src 'self'; script-src 'self' 'sha256-...'`.
- **Backup terenkripsi** untuk database — di-handle di level infrastruktur.
- **Anonimisasi** untuk anggota non-aktif setelah retensi tertentu.

## Pelaporan Kerentanan

Jika menemukan kerentanan keamanan, **jangan** buat issue publik. Hubungi langsung admin cabang melalui kanal internal.
