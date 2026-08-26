<h1 align="center">RSBA OFFICE</h1>

<p align="center">
  <b>Sistem Informasi Manajemen Sumber Daya Manusia (SIM-SDM) & Internal Office</b><br>
  Rumah Sakit Pertamina Bintang Amin (RSBA) Bandar Lampung
</p>

<p align="center">
  <img src="https://img.shields.io/badge/Laravel-12.x-FF2D20?style=for-the-badge&logo=laravel&logoColor=white" alt="Laravel 12">
  <img src="https://img.shields.io/badge/PHP-%5E8.2-777BB4?style=for-the-badge&logo=php&logoColor=white" alt="PHP 8.2">
  <img src="https://img.shields.io/badge/TailwindCSS-3.x-38B2AC?style=for-the-badge&logo=tailwind-css&logoColor=white" alt="Tailwind CSS">
  <img src="https://img.shields.io/badge/Livewire-3.x-FB70A9?style=for-the-badge&logo=livewire&logoColor=white" alt="Livewire">
  <img src="https://img.shields.io/badge/TallStackUI-v2-4F46E5?style=for-the-badge" alt="TallStackUI">
  <img src="https://img.shields.io/badge/License-MIT-green.svg?style=for-the-badge" alt="License">
</p>

---

## 📖 Tentang Aplikasi

**RSBA Office** adalah aplikasi web internal komprehensif yang dirancang untuk mendukung operasional perkantoran, tata kelola sumber daya manusia (SDM), presensi & penjadwalan dinas medis/non-medis, kalkulasi perpajakan dan penggajian (payroll), administrasi persuratan digital, serta pengelolaan aset dan inventaris logistik di lingkungan **Rumah Sakit Pertamina Bintang Amin Lampung**.

---

## 🚀 Fitur Utama & Modul Sistem

### 🎨 Modern UI & Component Architecture
- **Collapsible Sidebar**: Navigasi sidebar modern yang dapat dilipat (*collapsible*) melalui tombol hamburger di navbar desktop/mobile dengan scroll terpisah dan pencegahan auto-scroll (*scroll retention*).
- **Reusable Sidebar Menu Components**: Struktur komponen blade modular (`menu-item`, `menu-dropdown`, `badge`) yang bersih, terisolasi, dan mudah dipelihara.
- **Dynamic Header & 2-Tier Card Layout**: Layout header dua tingkat yang responsif untuk judul halaman, breadcrumb dinamis, serta tombol aksi (*action buttons*) agar tampilan tetap rapi tanpa overflow pada berbagai resolusi layar.
- **Dynamic Stats Grid Layout**: Tampilan grid statistik dan KPI cards yang fleksibel dan responsif mengisi lebar kontainer secara otomatis.
- **Notification System (Realtime)**: Sistem notifikasi interaktif dengan opsi tandai dibaca per notifikasi atau tandai semua dibaca, lengkap dengan *badge bell indicator* dinamis.

### 💼 Manajemen Kepegawaian & HRIS
- **Integrasi SATUSEHAT Practitioner (IHS Number, STR, SIP & Softcopy)**:
  - Pencatatan Practitioner IHS Number dari Kemenkes RI, Nomor STR, Jenis STR, Tanggal Terbit & Kadaluarsa STR, Jenis Profesi, serta Spesialisasi/Kompetensi pada data identitas karyawan.
  - Fitur unggah berkas softcopy STR (PDF/Gambar) terintegrasi langsung di form identitas & lisensi medis yang otomatis tersinkronisasi ke repositori dokumen pegawai (`sdm_kary_document`).
  - Kolom IHS Number, Nomor STR, dan badge status STR Expired (*Aktif*, *Warning ≤90 hari*, *Expired*) pada tabel data Dokter & Pegawai Medis.
- **Riwayat Kedinasan & Dokumen SK Pegawai**: Form edit kedinasan dengan input nomor & tanggal SK, pelacakan riwayat kedinasan (*history audit*), serta modal pratinjau dokumen SK secara terpusat.
- **Manajemen Karyawan, Dokter & Struktur Organisasi**: Pengelolaan profil terintegrasi, pencatatan nomor kepesertaan BPJS Kesehatan & BPJS Ketenagakerjaan, serta seeder komprehensif (`StrukturOrganisasiSeeder`, `DokterSeeder`).
- **Master Data SDM**: Pengelolaan Master Bagian, Jabatan, Ruangan, dan Spesialisasi Dokter.
- **Manajemen Izin & Cuti**: Pengajuan dan persetujuan izin/cuti karyawan, simulasi cuti bersama berbasis grouping karyawan dengan layout tabel HTML *rowspan*, serta command otomatis reset kuota cuti tahunan (`app:reset-cuti`).

### 💰 Payroll & Kalkulasi Perpajakan (PPh 21)
- **Engine Kalkulasi Otomatis PPh 21 (TER & Pasal 17)**:
  - Kalkulasi otomatis PPh 21 bulanan menggunakan skema Tarif Efektif Rata-Rata (TER Kategori A, B, C).
  - Rekonsiliasi Pajak Akhir Tahun (Desember YTD) sesuai Tarif Pasal 17 UU HPP.
  - Pengelolaan Master Aturan Pajak dan PTKP yang fleksibel serta pembentukan rincian potongan pajak otomatis pada Slip Gaji.
- **Slip Gaji Digital & PDF Engine**:
  - Tampilan tabel slip gaji interaktif dengan layout grid solid dan garis pemisah tegas (*dark double-line separator*).
  - Fitur cetak langsung (*print layout*) dengan styling CSS mandiri (bebas latensi CDN).
  - Lampiran dokumen **PDF Slip Gaji** otomatis menggunakan library `barryvdh/laravel-dompdf`.
- **Mass Queue Mailer & Scheduler**:
  - Pengiriman massal slip gaji via email secara *non-blocking* menggunakan **Background Queue** (`SendPayrollSlipJob`).
  - Indikator status pengiriman email *real-time* (polling UI & progress bar).
  - Log audit pengiriman terintegrasi (`PayrollSendLog`) untuk memantau status sukses/gagal kirim.
  - Eksekusi otomatis via Laravel Scheduler (`app:send-scheduled-payroll-slips`).
- **Komponen & Matriks Payroll**:
  - Pengelolaan Master Tunjangan Jabatan, Tunjangan Fungsional, Denda Keterlambatan Flat, dan Rekening Bank Karyawan.
  - Fitur Ekspor & Impor Excel untuk slip gaji bulanan serta rincian komponen modal.
  - Matriks Golongan dinamis dan pencatatan Log Edit Payroll (*Audit Trail*) untuk transparansi perubahan nominal gaji.

### ⏱️ Presensi, Absensi & Jadwal Kerja
- **Export PDF Jadwal Kerja Dinamis dengan Header Logo RSBA**:
  - Ekspor jadwal kerja ruangan ke format PDF (*Landscape A4*) dengan tata letak presisi sesuai standar dokumen fisik rumah sakit.
  - Dilengkapi **Header Logo Resmi RSBA** (Base64 Data URI) dan kop instansi resmi.
  - Sinkronisasi dinamis 100% dengan antarmuka web: kode shift singkat (`REG`, `PAGI`, `SIANG`, `MALAM`), skema warna sel (*background & font contrast*), dan tabel legenda shift otomatis sesuai konfigurasi shift ruangan aktif.
  - Tabel kontak nomor telepon petugas bertugas 4-kolom berpasangan dan blok tanda tangan resmi 2-kolom (Koordinator & Wadir Medis/Keperawatan).
- **Audit Log Koreksi Absensi**: Pencatatan riwayat perubahan/koreksi absensi karyawan (`sdm_absensi_koreksi_log`) dengan modal interaktif, pencarian, dan paginasi pada Rekap Absensi.
- **Backfill & Optimasi Kinerja Absensi**: Perintah CLI `app:backfill-absensi-metrics` dan indeks database untuk kalkulasi cepat rekapitulasi data absensi.

### ✉️ Persuratan, SP3 & Digital Signature
- **Manajemen Surat & SP3**: Penerbitan Surat Peringatan (SP 1/2/3), Surat Izin/Cuti, dan dokumen persuratan internal rumah sakit.
- **Docstore & Tanda Tangan Digital**: Integrasi Docstore untuk repositori dan audit dokumen (`app:docstore-sync-all`, `app:docstore-resync`), penerbitan QR Header Sistem, enkripsi, serta verifikasi tanda tangan digital persuratan.

### 📦 Manajemen Umum, Aset & Inventaris
- **Master Barang & Konversi Satuan (UoM)**: Pengelolaan Supplier, Kategori, Lokasi Penyimpanan, dan Konversi Satuan Dasar ke Satuan Tambahan (misal: 1 Box = 16 Pcs) yang otomatis mengonversi stok dan nominal harga pada transaksi Pembelian Langsung.
- **Penjadwalan Maintenance Aset Berkala**: Modul penjadwalan dan pemeliharaan aset rumah sakit secara periodik (`app:process-scheduled-maintenance`) untuk memastikan keandalan sarana dan prasarana.
- **Integrasi Monitoring IoT**: Tab dan antarmuka monitoring perangkat IoT untuk pemantauan fasilitas secara terpadu.

### 🔐 Keamanan, Autorisasi & Arsitektur Sistem
- **Granular RBAC (Spatie Permissions)**:
  - Standarisasi otorisasi berbasis permission menggunakan helper `can()`.
  - Otomatisasi generate 4 CRUD route permission pada `MenuSeeder` dengan urutan ID sekuensial (1–61).
  - Seeder hak akses khusus (`SpecialPermissionSeeder`) dan Gate bypass untuk Super-Admin.
- **Clean Architecture & Modular Domain**: Pemisahan namespace model dan domain yang rapi (`App\Models\Gaji`, `App\Models\Sdm\Payroll`, `App\Models\Umum`).
- **Laporan Kepegawaian & Ekspor Data**: Modul laporan komprehensif berbasis tab interaktif dengan filter pencarian, ekspor Excel/CSV, dan tampilan cetak (*print view*).

---

## 🛠️ Prerequisite & Tech Stack

| Komponen | Spesifikasi / Library |
| :--- | :--- |
| **PHP** | `^8.2` (disarankan PHP 8.2 atau 8.3) |
| **Framework** | [Laravel 12](https://laravel.com/docs/12.x) |
| **Database** | MySQL `^8.0` / MariaDB `^10.4` |
| **UI Components** | [TallStackUI](https://tallstackui.com/docs/v2) |
| **Reactive Engine** | [Livewire 3](https://livewire.laravel.com/docs/quickstart) |
| **Table Component**| [Filament Table 3.x](https://filamentphp.com/docs/3.x/tables/installation) |
| **Styling** | [Tailwind CSS 3](https://v3.tailwindcss.com/docs/installation) |
| **Icons** | [Tabler Icons](https://tabler.io/icons) (`secondnetwork/blade-tabler-icons`) |
| **PDF Engine** | [Laravel DomPDF](https://github.com/barryvdh/laravel-dompdf) (`barryvdh/laravel-dompdf`) |
| **Excel Handler** | [Laravel Excel](https://laravel-excel.com/) (`maatwebsite/excel`) |

---

## 📥 Langkah Instalasi & Konfigurasi

### 1. Clone Repository & Install Dependencies
```bash
# Clone repository
git clone https://gitlab.com/fn-febri/office.git
cd office

# Install PHP dependencies
composer install

# Install NPM dependencies & build assets
npm install
npm run dev
```

### 2. Konfigurasi Environment (`.env`)
Salin file `.env.example` ke `.env` lalu sesuaikan kredensial database dan SMTP mailer:
```bash
cp .env.example .env
php artisan key:generate
```

Contoh konfigurasi penting pada `.env`:
```ini
APP_NAME="RS Bintang Amin"
APP_ENV=local
APP_KEY=base64:...
APP_DEBUG=true
APP_TIMEZONE="Asia/Jakarta"
APP_URL=http://localhost:8000

# Database Configuration
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=db_rsba_office
DB_USERNAME=root
DB_PASSWORD=

# Queue Driver (Disarankan 'database' untuk async background jobs)
QUEUE_CONNECTION=database

# Mail SMTP Configuration (Contoh Google SMTP / Mailtrap)
MAIL_MAILER=smtp
MAIL_SCHEME=null
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=email_anda@gmail.com
MAIL_PASSWORD=sandi_aplikasi_16_karakter
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="no-reply@rsbintangamin.com"
MAIL_FROM_NAME="${APP_NAME}"
```

### 3. Migrasi & Seeding Database
Jalankan migrasi database dan pemuatan data master/seeder awal:
```bash
php artisan migrate:fresh --seed
```

### 4. Storage Link
Buat symlink untuk akses berkas publik / dokumen:
```bash
php artisan storage:link
```

### 5. Jalankan Queue Worker & Scheduler
Diperlukan untuk memproses antrean email slip gaji, sinkronisasi dokumen, dan penjadwalan tugas:
```bash
# Terminal 1: Worker untuk memproses queue
php artisan queue:work

# Terminal 2: Scheduler untuk lingkungan lokal
php artisan schedule:work

# Terminal 3: Laravel Web Server
php artisan serve
```

---

## ⚡ Perintah Artisan Kustom

| Perintah | Deskripsi |
| :--- | :--- |
| `php artisan app:send-scheduled-payroll-slips` | Memproses pengiriman slip gaji massal terjadwal via email. |
| `php artisan app:backfill-absensi-metrics` | Melakukan kalkulasi ulang dan rekapitulasi data metrik absensi. |
| `php artisan app:reset-cuti` | Mereset kuota cuti tahunan pegawai secara otomatis. |
| `php artisan app:process-scheduled-maintenance` | Memeriksa dan memproses jadwal maintenance aset berkala. |
| `php artisan app:docstore-sync-all` | Melakukan sinkronisasi seluruh dokumen ke repositori Docstore. |
| `php artisan app:docstore-resync` | Melakukan resinkronisasi dokumen yang belum terverifikasi. |
| `php artisan app:sync-documents-to-docstore` | Menyinkronkan dokumen SK dan berkas ke Docstore. |

---

## 🧹 Perintah Pembersihan Cache

Gunakan perintah berikut jika terjadi perubahan konfigurasi `.env`, routes, atau blade views:
```bash
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan cache:clear
php artisan optimize:clear
```

---

## 📄 Lisensi
Aplikasi ini dilisensikan di bawah [MIT License](https://opensource.org/licenses/MIT).
