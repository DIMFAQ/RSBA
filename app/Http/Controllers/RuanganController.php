<?php

namespace App\Http\Controllers;

use App\Models\Ruangan;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class RuanganController extends Controller
{
    static function list(Request $request): JsonResponse
    {
        $inputSearch = $request->input('search');

        $ruangan = Ruangan::select('id', 'nama')
            ->when($inputSearch, function ($query, $inputSearch) {
                return $query->where('nama', 'like', "%$inputSearch%");
            })
            ->orderBy('nama', 'asc')
            ->limit(10)
            ->get();


        return response()->json($ruangan);
    }
}
