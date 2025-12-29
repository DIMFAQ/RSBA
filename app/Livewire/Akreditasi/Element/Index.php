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

    #[Computed]
    public function babs()
    {
        return AkreBabElement::with(['elements.files'])
            ->withCount([
                'elements',
                'elements as elements_with_files_count' => function ($query) {
                    $query->whereHas('files');
                }
            ])
            ->where('chapter_id', $this->chapter->id)
            ->paginate(10);
    }

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
