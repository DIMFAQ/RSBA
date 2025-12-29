<?php

namespace App\Livewire\Akreditasi\Element;

use App\Models\Akreditasi\AkreBabElement;
use App\Models\Akreditasi\AkreChapter;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Lazy;
use Livewire\Component;
use Livewire\WithPagination;

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

    public function render()
    {
        return view('livewire.akreditasi.element.index');
    }
}
