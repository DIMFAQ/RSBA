<?php

namespace App\Http\Controllers;

use App\Models\Wilayah;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class WilayahController extends Controller
{

    // Get Provinsi
    public function prov()
    {
        $provinsi = Wilayah::select('kode', 'nama')
            ->whereRaw('CHAR_LENGTH(kode) = 2')
            ->orderBy('nama', 'ASC')
            ->get();
        return response()->json($provinsi);
    }


    // Get Kabupaten
    public function kab($id, Request $request): JsonResponse
    {
        $prov_id = $id;

        $kabupaten = Wilayah::select('kode', 'nama')
            ->whereRaw('CHAR_LENGTH(kode) = 5')
            ->whereRaw("LEFT(kode, 2) = '$prov_id'")
            ->orderBy('nama', 'ASC')
            ->get();

        return response()->json($kabupaten);
    }

    // Get Kecamatan
    public function kec($id): JsonResponse
    {
        $kab_id = $id;

        $kabupaten = Wilayah::select('kode', 'nama')
            ->whereRaw('CHAR_LENGTH(kode) = 8')
            ->whereRaw("LEFT(kode, 5) = '$kab_id'")
            ->orderBy('nama', 'ASC')
            ->get();
        return response()->json($kabupaten);
    }

    // Get Desa
    public function desa($id): JsonResponse
    {
        $kec_id = $id;

        $kabupaten = Wilayah::select('kode', 'nama')
            ->whereRaw('CHAR_LENGTH(kode) = 13')
            ->whereRaw("LEFT(kode, 8) = '$kec_id'")
            ->orderBy('nama', 'ASC')
            ->get();
        return response()->json($kabupaten);
    }
}
