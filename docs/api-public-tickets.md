# 📖 Dokumentasi Public Ticketing API

Dokumentasi ini ditujukan bagi tim pengembang Frontend (React + TypeScript) yang membangun aplikasi Form Pengaduan Publik terpisah.

---

## 🌐 Base URL & Headers

- **Base URL (Local/Development):** `https://office.test` (atau `http://localhost:8000`)
- **Headers yang Wajib Disertakan:**
  ```http
  Content-Type: application/json
  Accept: application/json
  ```
- **Autentikasi:** ❌ **Tidak Memerlukan Login / Token** (Public Access).

---

## 📌 Daftar Endpoint

| Method | Endpoint | Keterangan | Rate Limit |
|---|---|---|---|
| `GET` | `/api/ruangan` | Ambil daftar master ruangan untuk dropdown | - |
| `POST` | `/api/public/tickets` | Kirim laporan pengaduan kerusakan baru | 10 req / menit |
| `GET` | `/api/public/tickets/{trackingCode}` | Cek status laporan berdasarkan kode tracking | 30 req / menit |

---

## 1. Ambil Daftar Ruangan

Endpoint ini digunakan untuk mengisi dropdown pilihan lokasi/ruangan pada form pengaduan.

- **URL:** `GET /api/ruangan`
- **Response Success (200 OK):**
```json
[
  {
    "id": 1,
    "nama": "Poli Gigi"
  },
  {
    "id": 2,
    "nama": "IGD (Instalasi Gawat Darurat)"
  }
]
```

---

## 2. Kirim Laporan Pengaduan Baru

- **URL:** `POST /api/public/tickets`
- **Request Body (JSON):**

| Field | Tipe | Wajib? | Aturan & Keterangan |
|---|---|---|---|
| `ruangan_id` | `number` | **Wajib** | ID ruangan yang valid dari `/api/ruangan` |
| `jenis` | `string` | **Wajib** | Hanya boleh bernilai `"umum"` atau `"it"` |
| `deskripsi` | `string` | **Wajib** | Min. 10 karakter, maks. 1000 karakter |
| `pelapor_nama` | `string` | Opsional | Nama pelapor (maks. 100 karakter) |
| `pelapor_kontak` | `string` | Opsional | Nomor HP / WhatsApp / Email pelapor |

- **Contoh Request:**
```json
{
  "ruangan_id": 1,
  "jenis": "it",
  "deskripsi": "Komputer kasir poli gigi mendadak mati dan tidak bisa dinyalakan kembali.",
  "pelapor_nama": "Ahmad Fauzi",
  "pelapor_kontak": "081234567890"
}
```

- **Response Success (201 Created):**
```json
{
  "message": "Laporan pengaduan berhasil dikirim.",
  "tracking_code": "TKT-20260819-A1B2C3",
  "data": {
    "tracking_code": "TKT-20260819-A1B2C3",
    "jenis": "it",
    "jenis_label": "IT",
    "deskripsi": "Komputer kasir poli gigi mendadak mati dan tidak bisa dinyalakan kembali.",
    "status": "pending",
    "status_label": "Menunggu",
    "pelapor_nama": "Ahmad Fauzi",
    "ruangan": "Poli Gigi",
    "created_at": "2026-08-19T11:40:00+07:00",
    "updated_at": "2026-08-19T11:40:00+07:00"
  }
}
```

- **Response Validation Error (422 Unprocessable Entity):**
```json
{
  "message": "Validasi gagal.",
  "errors": {
    "ruangan_id": [
      "Silakan pilih ruangan terlebih dahulu."
    ],
    "jenis": [
      "Jenis kerusakan harus bernilai umum atau it."
    ],
    "deskripsi": [
      "Deskripsi minimal 10 karakter."
    ]
  }
}
```

---

## 3. Cek Status Pengaduan (Tracking)

- **URL:** `GET /api/public/tickets/{trackingCode}`
- **Parameter URL:** `trackingCode` (contoh: `TKT-20260819-A1B2C3`)

- **Response Success (200 OK):**
```json
{
  "message": "Data tiket ditemukan.",
  "data": {
    "tracking_code": "TKT-20260819-A1B2C3",
    "jenis": "it",
    "jenis_label": "IT",
    "deskripsi": "Komputer kasir poli gigi mendadak mati dan tidak bisa dinyalakan kembali.",
    "status": "proses",
    "status_label": "Diproses",
    "pelapor_nama": "Ahmad Fauzi",
    "ruangan": "Poli Gigi",
    "created_at": "2026-08-19T11:40:00+07:00",
    "updated_at": "2026-08-19T11:45:00+07:00"
  }
}
```

- **Response Not Found (404 Not Found):**
```json
{
  "message": "Tiket dengan kode tracking tersebut tidak ditemukan."
}
```

- **Arti Status Tiket:**
  - `pending` (`status_label`: "Menunggu") → Laporan baru masuk, menunggu ditinjau tim maintenance.
  - `proses` (`status_label`: "Diproses") → Laporan sedang ditangani oleh teknisi/staff.
  - `selesai` (`status_label`: "Selesai") → Perbaikan telah rampung dikerjakan.
