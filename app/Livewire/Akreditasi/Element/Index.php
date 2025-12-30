<?php

namespace App\Livewire\Akreditasi\Element;

use ZipArchive;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Lazy;
use Livewire\Attributes\Computed;
use App\Models\Akreditasi\AkreChapter;
use App\Models\Akreditasi\AkreKegiatan;
use Illuminate\Support\Facades\Storage;
use App\Models\Akreditasi\AkreBabElement;

#[Lazy]
class Index extends Component
{
    use WithPagination;

    public ?int $babIdSelected;

    public ?AkreChapter $chapter;

    public function mount(AkreChapter $chapter)
    {
        $this->chapter = $chapter;
    }

    // #[Computed]
    // public function babs()
    // {
    //     $items =  AkreBabElement::query()
    //         // Eager load relasi untuk menghindari N+1 problem
    //         ->with([
    //             'elements' => function ($query) {
    //                 $query->select('id', 'akre_bab_id', 'target_nilai', 'nilai', 'element')
    //                     ->orderBy('nomor');
    //             },
    //             'elements.files' => function ($query) {
    //                 $query->select('id', 'element_id', 'path', 'filename');
    //             }
    //         ])
    //         // Count elements
    //         ->withCount([
    //             'elements',
    //             'elements as elements_with_files_count' => function ($query) {
    //                 $query->whereHas('files');
    //             }
    //         ])
    //         // Sum nilai untuk menghitung total
    //         ->withSum('elements', 'nilai')
    //         ->withSum('elements', 'target_nilai')
    //         // Filter by chapter
    //         ->where('chapter_id', $this->chapter->id)
    //         // ->orderByRaw('CAST(SUBSTRING_INDEX(no, ".", 1) AS UNSIGNED), CAST(SUBSTRING_INDEX(no, ".", -1) AS UNSIGNED)')
    //         // Order by nomor bab
    //         // ->orderBy('no')
    //         // Paginate
    //         ->paginate(10);



    //     // Organisir data: Bab diikuti langsung dengan Sub Bab nya
    //     $organized = collect();

    //     foreach ($items as $item) {
    //         if ($item->bab === 'bab') {
    //             // Tambahkan bab
    //             $organized->push($item);

    //             // Tambahkan semua sub bab yang parent_id nya sama dengan bab ini
    //             $subBabs = $items->where('parent_id', $item->id)
    //                 ->sortBy('no');

    //             foreach ($subBabs as $subBab) {
    //                 $organized->push($subBab);
    //             }
    //         }
    //     }

    //     return $organized;
    // }

    #[Computed]
    public function babs()
    {
        // Ambil BAB saja dengan pagination
        $parentBabs = AkreBabElement::query()
            ->where('chapter_id', $this->chapter->id)
            ->where('bab', 'bab')
            ->whereNull('parent_id')
            ->orderBy('no')
            ->paginate(1);

        // Ambil semua SUB BAB untuk BAB yang ter-paginate
        $babIds = $parentBabs->pluck('id');

        $subBabs = AkreBabElement::query()
            ->with([
                'elements' => function ($query) {
                    $query->select('id', 'akre_bab_id', 'target_nilai', 'nilai', 'element')
                        ->orderBy('nomor');
                },
                'elements.files' => function ($query) {
                    $query->select('id', 'element_id', 'path', 'filename');
                }
            ])
            ->withCount([
                'elements',
                'elements as elements_with_files_count' => function ($query) {
                    $query->whereHas('files');
                }
            ])
            ->withSum('elements', 'nilai')
            ->withSum('elements', 'target_nilai')
            ->whereIn('parent_id', $babIds)
            ->orderBy('no')
            ->get()
            ->groupBy('parent_id');

        // Load relasi untuk parent babs juga
        $parentBabs->getCollection()->load([
            'elements' => function ($query) {
                $query->select('id', 'akre_bab_id', 'target_nilai', 'nilai', 'element')
                    ->orderBy('nomor');
            },
            'elements.files' => function ($query) {
                $query->select('id', 'element_id', 'path', 'filename');
            }
        ]);

        // Organisir: BAB → SUB BAB nyas
        $organized = collect();

        foreach ($parentBabs->items() as $bab) {
            // Tambahkan BAB
            $organized->push($bab);

            // Tambahkan SUB BAB yang punya parent_id sama dengan BAB ini
            if (isset($subBabs[$bab->id])) {
                foreach ($subBabs[$bab->id] as $subBab) {
                    $organized->push($subBab);
                }
            }
        }

        // Return dengan pagination info
        return new \Illuminate\Pagination\LengthAwarePaginator(
            $organized,
            $parentBabs->total(),
            $parentBabs->perPage(),
            $parentBabs->currentPage(),
            ['path' => \Illuminate\Pagination\Paginator::resolveCurrentPath()]
        );
    }

    public function getBerkasColor($item)
    {
        return $item->elements_with_files_count === $item->elements_count ? 'green' : 'red';
    }

    public function getNilaiColor($item)
    {
        $totalTarget = $item->elements_sum_target_nilai ?? 0;
        $totalNilai = $item->elements_sum_nilai ?? 0;
        $persentase = $totalTarget > 0 ? ($totalNilai / $totalTarget) * 100 : 0;

        if ($persentase < 50) {
            return 'red';
        } elseif ($persentase >= 50 && $persentase < 100) {
            return 'yellow';
        } else {
            return 'green';
        }
    }

    public function getPersentaseNilai($item)
    {
        $totalTarget = $item->elements_sum_target_nilai ?? 0;
        $totalNilai = $item->elements_sum_nilai ?? 0;

        return $totalTarget > 0 ? round(($totalNilai / $totalTarget) * 100, 1) : 0;
    }

    // #[Computed]
    // public function babs()
    // {
    //     return AkreBabElement::with(['elements.files'])
    //         ->withCount([
    //             'elements',
    //             'elements as elements_with_files_count' => function ($query) {
    //                 $query->whereHas('files');
    //             }
    //         ])
    //         ->where('chapter_id', $this->chapter->id)
    //         ->paginate(10);
    // }

    public function placeholder()
    {
        // Ambil parameter dari route
        $chapter = request()->route('chapter');
        return view('components.skeleton')
            ->title($chapter ? "{$chapter->nama} ({$chapter->singkatan})" : 'Loading...');
    }


    public array $expandedItems = [];

    public function toggleDetail($itemId)
    {
        if (in_array($itemId, $this->expandedItems)) {
            // Remove from array (collapse)
            $this->expandedItems = array_diff($this->expandedItems, [$itemId]);
        } else {
            // Add to array (expand)
            $this->expandedItems[] = $itemId;
        }
        // dd($itemId, $this->expandedItems);
    }


    // download document per chapter
    public function downloadZip()
    {
        // $zip = new ZipArchive;
        // $filename = $this->chapter->singkatan . time() . '.zip';
        // $zipPath = storage_path('app/temp/' . $filename);

        // if ($zip->open($zipPath, ZipArchive::CREATE) === TRUE) {

        //     // $folderKegiatan = AkreChapter::with('kegiatan')
        //     //     ->where('id', $this->chapter->id);

        //     $folderKegiatan = AkreChapter::join('akre_kegiatan', 'akre_chapter.kegiatan_id', '=', 'akre_kegiatan.id')
        //         ->where('akre_chapter.id', $this->chapter->id)
        //         ->select('akre_kegiatan.folder_path')
        //         ->first();


        //     $files = Storage::files($folderKegiatan);


        // }
        $chapter =  AkreChapter::join('akre_kegiatan', 'akre_chapter.kegiatan_id', '=', 'akre_kegiatan.id')
            ->where('akre_chapter.id', $this->chapter->id)
            ->select(
                'akre_kegiatan.standard',
                'akre_kegiatan.tanggal',
                'akre_kegiatan.folder_path',
                'akre_chapter.singkatan'
            )
            ->first();

        $basePath = $chapter->folder_path;
        $chapterName = $chapter->singkatan;

        if (!Storage::disk('public')->exists($basePath)) {
            abort(404, "Folder Not Found");
        }

        // 
        $zipFileName = str_replace(' ', '_', $chapterName) .
            '_' . str_replace(' ', '_', $chapter->standard) .
            '_' . str_replace('-', '_', $chapter->tanggal) .
            '.zip';

        return response()->streamDownload(
            function () use ($basePath) {

                $zip = new ZipArchive;
                $tempFile = tempnam(sys_get_temp_dir(), 'zip');

                if ($zip->open($tempFile, ZipArchive::CREATE | ZipArchive::OVERWRITE) === TRUE) {
                    $files = Storage::disk('public')->allFiles($basePath);

                    foreach ($files as $file) {
                        $relativePath = str_replace($basePath . '/', '', $file);
                        $zip->addFile(Storage::disk('public')->path($file), $relativePath);
                    }

                    $zip->close();
                }

                readfile($tempFile);
                unlink($tempFile);
            },
            $zipFileName
        );
    }

    public function render()
    {
        return view('livewire.akreditasi.element.index');
    }
}
