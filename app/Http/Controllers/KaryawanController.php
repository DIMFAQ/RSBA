<?php

namespace App\Http\Controllers;

use App\Models\Sdm\Karyawan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Query\Builder;

class KaryawanController extends Controller
{
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
}
