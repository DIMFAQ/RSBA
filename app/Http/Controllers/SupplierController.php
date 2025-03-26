<?php

namespace App\Http\Controllers;

use App\Models\Master\Supplier;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    public function list(Request $request): JsonResponse
    {
        $inputSearch = $request->search;

        $data = Supplier::select('id', 'nama')
            ->when(
                $inputSearch,
                function ($query, $inputSearch) {
                    return $query->where('nama', 'like', "%$inputSearch%");
                }
            )
            ->orderBy('nama', 'asc')
            ->limit(10)
            ->get();

        return response()->json($data);
    }
}
