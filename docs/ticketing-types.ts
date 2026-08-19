/**
 * TypeScript Type Definitions for Public Ticketing API
 * File ini dapat disalin langsung ke codebase React + TypeScript Anda.
 */

export interface RuanganItem {
  id: number;
  nama: string;
}

export type TicketJenis = 'umum' | 'it';
export type TicketStatus = 'pending' | 'proses' | 'selesai';

export interface TicketStoreRequest {
  /** ID Ruangan yang valid dari GET /api/ruangan */
  ruangan_id: number;
  /** Jenis kerusakan */
  jenis: TicketJenis;
  /** Deskripsi kerusakan (min 10, max 1000 karakter) */
  deskripsi: string;
  /** Nama pelapor (opsional, max 100 karakter) */
  pelapor_nama?: string | null;
  /** Kontak HP/WA pelapor (opsional, max 100 karakter) */
  pelapor_kontak?: string | null;
}

export interface TicketData {
  tracking_code: string;
  jenis: TicketJenis;
  jenis_label: 'Umum' | 'IT';
  deskripsi: string;
  status: TicketStatus;
  status_label: 'Menunggu' | 'Diproses' | 'Selesai';
  pelapor_nama: string | null;
  ruangan: string | null;
  created_at: string;
  updated_at: string;
}

export interface TicketStoreResponse {
  message: string;
  tracking_code: string;
  data: TicketData;
}

export interface TicketShowResponse {
  message: string;
  data: TicketData;
}

export interface ValidationErrorResponse {
  message: string;
  errors: {
    [field in keyof TicketStoreRequest]?: string[];
  };
}

export interface ErrorResponse {
  message: string;
}
