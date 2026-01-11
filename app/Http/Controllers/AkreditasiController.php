<?php

namespace App\Http\Controllers;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Models\Akreditasi\AkreChapter;
use App\Models\Akreditasi\AkreBabElement;

class AkreditasiController extends Controller
{
    public function chapters($kegiatan, Request $request): JsonResponse
    {
        $chapters = AkreChapter::where('kegiatan_id', $kegiatan)
            ->limit(5)
            ->get()
            ->map(function ($item) {
                return [
                    'id' => $item->id,
                    'singkatan' => $item->singkatan,
                    'description' => $item->nama
                ];
            });

        return response()->json($chapters);
    }

    public function babs($type = null, $chapter_id): JsonResponse
    {
        $search = '';

        $babs = AkreBabElement::where('chapter_id', $chapter_id)
            ->when(
                $type,
                function ($query, $type) {
                    $query->where('bab', $type);
                }
            )
            ->when(
                $search,
                function ($query, $search) {
                    $query->where('nama', 'like', "%$search%");
                }
            )
            ->orderBy('no')
            ->limit(10)
            ->get()
            ->map(function ($item) {
                return [
                    'id' => $item->id,
                    'nama' => $item->nama,
                    'description' => Str::limit($item->deskripsi, 25, '...')
                ];
            });

        return response()->json($babs);
    }

    public function elements($sub, Request $request): JsonResponse
    {
        $elements = '';

        return response()->json($elements);
    }

    public function documents($element, Request $request): JsonResponse
    {
        $documents = '';

        return response()->json($documents);
    }
}
