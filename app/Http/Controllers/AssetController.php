<?php

namespace App\Http\Controllers;

use App\Models\Master\Barang;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class AssetController extends Controller
{
    function mainItem(Request $request): JsonResponse
    {
        $search = $request->input('search');

        $data = Barang::with(['barang'])
            ->select('id', 'kode', 'barang_id')
            ->whereNotNull('kode')
            ->when($search, function ($query) use ($search) {
                $query->where('kode', 'like', '%' . $search . '%')
                    ->orWhereHas('barang', function ($q) use ($search) {
                        $q->where('nama', 'like', '%' . $search . '%');
                    });
            })
            ->orderBy('id', 'asc')
            ->limit(10)
            ->get()
            ->map(
                fn($item) => [
                    'value' => $item->id,
                    'label' => $item->kode,
                    'description' => "Barang: {$item->barang->nama}",
                ]
            );

        return response()->json($data);
    }
}
