<h1 align="center">RSBA OFFICE</h1>

<p align="center">
  Aplikasi perkantoran (SIM-SDM & Internal Office) pada <b>Rumah Sakit Bintang Amin Lampung</b>.
</p>

## Fitur Utama & Pembaruan

### 🎨 Modern UI & Experience
- **Collapsible Sidebar**: Navigasi sidebar modern yang dapat dilipat (*collapsible*) melalui tombol hamburger di navbar desktop/mobile dengan scroll terpisah dan auto-scroll prevention.
- **Dynamic Header & 2-Tier Card Layout**: Layout header dua tingkat yang responsif untuk judul halaman, breadcrumb, serta tombol aksi (*action buttons*) tanpa overflow.
- **Dynamic Stats Grid Layout**: Tampilan grid statistik dashboard yang responsif dan fleksibel mengisi lebar layar secara dinamis.
- **Notification System (Realtime)**: Fitur notifikasi interaktif dengan opsi tandai dibaca per notif/semua dibaca dan *badge bell indicator* dinamis.

### 💼 Manajemen Kepegawaian & HRIS
- **Manajemen Karyawan & Dokter**: Pengelolaan profil karyawan terintegrasi, nomor BPJS Kesehatan & Ketenagakerjaan, serta seeder spesialisasi dan struktur organisasi (`StrukturOrganisasiSeeder`, `DokterSeeder`).
- **Master Data SDM**: Pengelolaan Master Bagian, Jabatan, Ruangan, dan Spesialisasi Dokter.
- **Izin & Cuti & Cuti Bersama**: Pengajuan dan persetujuan izin/cuti karyawan, simulasi cuti bersama berbasis grouping karyawan dengan layout tabel HTML *rowspan*, serta command otomatis reset kuota cuti tahunan (`app:reset-cuti`).

### 💰 Payroll & Kalkulasi PPh 21
- **Engine Kalkulasi PPh 21 (TER & Pasal 17)**: Kalkulasi otomatis skema TER (A, B, C) untuk bulanan dan Tarif Pasal 17 UU HPP untuk Rekonsiliasi Akhir Tahun (Desember YTD).
- **Slip Gaji Digital & PDF Engine**: Tampilan slip gaji interaktif dengan grid solid, cetak langsung (*print layout*), serta lampiran PDF otomatis menggunakan `barryvdh/laravel-dompdf`.
- **Mass Queue Mailer & Scheduler**: Pengiriman massal slip gaji via email secara *non-blocking* via Background Queue (`SendPayrollSlipJob`), progress bar real-time, audit log pengiriman (`PayrollSendLog`), dan eksekusi otomatis via Laravel Scheduler (`app:send-scheduled-payroll-slips`).
- **Komponen & Matriks Payroll**: Pengelolaan Master Tunjangan, Denda Keterlambatan Flat, Matriks Golongan dinamis, Ekspor/Impor Excel, serta Log Edit Payroll (*Audit Trail*).

### ⏱️ Presensi & Absensi
- **Audit Log Koreksi Absensi**: Recording riwayat koreksi absensi (`sdm_absensi_koreksi_log`) dengan modal audit log interaktif, fitur pencarian, dan paginasi pada Rekap Absensi.
- **Backfill & Optimasi Kinerja**: CLI Command `app:backfill-absensi-metrics` dan indeks database untuk mempercepat kalkulasi rekapitulasi absensi.

### ✉️ Persuratan & Digital Signature
- **Manajemen Surat & SP3**: Penerbitan Surat Peringatan (SP3), Surat Izin/Cuti, dan dokumen persuratan internal.
- **Docstore & Tanda Tangan Digital**: Integrasi Docstore untuk audit dokumen (`app:docstore-sync-all`, `app:docstore-resync`), QR Header Sistem, enkripsi, serta verifikasi tanda tangan digital.

### 📦 Manajemen Umum & Inventaris
- **Master Barang & Satuan (UoM)**: Pengelolaan Supplier, Kategori, Penyimpanan, dan Konversi Satuan Dasar ke Satuan Tambahan (misal: 1 Box = 16 Pcs) yang mengkalkulasi stok dan nominal harga otomatis.

### 📊 Laporan & Administrasi Sistem
- **Laporan Kepegawaian**: Modul laporan komprehensif berbasis tab interaktif dengan filter pencarian, ekspor Excel/CSV, dan tampilan cetak.
- **Role & Permission & Sequential Menu**: Otorisasi granular menggunakan Spatie Permission dan Seeder Menu terstruktur dengan urutan ID sekuensial (1–61) pada `MenuSeeder`.
- **Terstruktur & Clean Architecture**: Pemisahan namespace model domain yang rapi (`App\Models\Gaji`, `App\Models\Sdm\Payroll`).

---

## Prerequisite
- **PHP**: `^8.2`
- **Framework**: [Laravel 12](https://laravel.com/docs/12.x)
- **Database**: MySQL / MariaDB
- **UI Components**: [TallStackUI](https://tallstackui.com/docs/v2)
- **Reactive Engine**: [Livewire](https://livewire.laravel.com/docs/quickstart)
- **Table Component**: [Filament Table](https://filamentphp.com/docs/3.x/tables/installation)
- **Styling**: [Tailwind CSS 3](https://v3.tailwindcss.com/docs/installation)
- **Icons**: [Tabler Icons](https://tabler.io/icons) (`secondnetwork/blade-tabler-icons`)
- **PDF Engine**: [Laravel DomPDF](https://github.com/barryvdh/laravel-dompdf) (`barryvdh/laravel-dompdf`)

---

## Langkah Instalasi & Konfigurasi

### 1. Clone Repository & Install Dependencies
```bash
composer install
npm install
npm run dev
```

### 2. Konfigurasi Environment (`.env`)
Salin file `.env.example` ke `.env` dan sesuaikan konfigurasi database serta mailer SMTP:
```ini
APP_NAME="RS Bintang Amin"

# Database Configuration
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=nama_database_anda
DB_USERNAME=root
DB_PASSWORD=

# Queue Driver (Disarankan 'database' untuk async job)
QUEUE_CONNECTION=database

# Mail SMTP Configuration
MAIL_MAILER=smtp
MAIL_SCHEME=null
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=email_anda@gmail.com
MAIL_PASSWORD=sandi_aplikasi_16_karakter
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="email_anda@gmail.com"
MAIL_FROM_NAME="${APP_NAME}"
```

### 3. Migrasi & Seeding Database
Jalankan migrasi database dan seeder awal:
```bash
php artisan migrate:fresh --seed
```

### 4. Jalankan Queue Worker & Scheduler
Penting untuk pemrosesan antrean pengiriman email slip gaji dan jadwal otomatis:
```bash
# Worker untuk memproses queue
php artisan queue:work

# Scheduler untuk lingkungan pengembangan
php artisan schedule:work
```

---

## Perintah Artisan Kustom

```bash
# Pengiriman slip gaji massal terjadwal
php artisan app:send-scheduled-payroll-slips

# Backfill metrik absensi
php artisan app:backfill-absensi-metrics

# Reset kuota cuti tahunan karyawan
php artisan app:reset-cuti

# Sinkronisasi & Resync Docstore
php artisan app:docstore-sync-all
php artisan app:docstore-resync
```

---

## Clear Cache Command

Jika melakukan perubahan konfigurasi pada file `.env` atau template blade:
```bash
php artisan config:clear
php artisan view:clear
php artisan cache:clear
```

---

## Lisensi
Aplikasi ini berlisensi di bawah [MIT license](https://opensource.org/licenses/MIT).


