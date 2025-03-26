<?php

namespace App\Http\Controllers;

use App\Models\Sdm\Karyawan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Query\Builder;

class KaryawanController extends Controller
{
    // get all
    public function list(Request $request)
    {
        $input = $request->input('search');
        $karyawan = Karyawan::select('id', 'nama')
            ->when($input, function ($query, $input) {
                $query->where('nama', 'like', "%$input%");
            })
            ->orderBy('nama')
            ->limit(10)
            ->get();

        return response()->json($karyawan);
    }

    // Cari Karyawan Untuk Registrasi
    public function register(Request $request)
    {
        // Parameter: search
        $inputSearch = $request->input('search');

        // get data
        $karyawans = Karyawan::select('id', 'nama')
            ->when($inputSearch, function ($query, $inputSearch) {
                $query->where('nama', 'like', "%$inputSearch%");
            })
            ->whereNotExists(function (Builder $query) {
                $query->select(DB::raw(1))
                    ->from('users')
                    ->whereColumn('users.karyawan_id', 'sdm_karyawan.id');
            })
            ->orderBy('nama')
            ->limit(10)
            ->get();

        // return data
        return response()->json($karyawans);
    }


    // To Register Dokter
    public function registerDokter(Request $request)
    {
        $input = $request->input('search');

        $data = Karyawan::select('id', 'nama')
            ->when($input, function ($query, $input) {
                $query->where('nama', 'like', "%$input%");
            })
            ->whereNotExists(function (Builder $query) {
                $query->select(DB::raw(1))
                    ->from('dokter')
                    ->whereColumn('dokter.karyawan_id', 'sdm_karyawan.id');
            })
            ->orderBy('nama')
            ->limit(10)
            ->get();

        return response()->json($data);
    }
}
