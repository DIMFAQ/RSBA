<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\StorePublicTicketRequest;
use App\Http\Resources\PublicTicketResource;
use App\Models\Maintenance\PublicReport;
use Illuminate\Http\JsonResponse;

class PublicTicketController extends Controller
{
    /**
     * Simpan pengaduan baru dari publik.
     */
    public function store(StorePublicTicketRequest $request): JsonResponse
    {
        $report = PublicReport::create([
            'ruangan_id'     => $request->validated('ruangan_id'),
            'jenis'          => $request->validated('jenis'),
            'deskripsi'      => $request->validated('deskripsi'),
            'pelapor_nama'   => $request->validated('pelapor_nama'),
            'pelapor_kontak' => $request->validated('pelapor_kontak'),
            'status'         => 'pending',
        ]);

        return response()->json([
            'message'       => 'Laporan pengaduan berhasil dikirim.',
            'tracking_code' => $report->tracking_code,
            'data'          => new PublicTicketResource($report->load('ruangan')),
        ], 201);
    }

    /**
     * Ambil data status tiket berdasarkan tracking_code.
     */
    public function show(string $trackingCode): JsonResponse
    {
        $report = PublicReport::with('ruangan')
            ->where('tracking_code', $trackingCode)
            ->first();

        if (!$report) {
            return response()->json([
                'message' => 'Tiket dengan kode tracking tersebut tidak ditemukan.',
            ], 404);
        }

        return response()->json([
            'message' => 'Data tiket ditemukan.',
            'data'    => new PublicTicketResource($report),
        ], 200);
    }
}
