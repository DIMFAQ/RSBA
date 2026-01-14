<?php

namespace App\Livewire\Akreditasi\Documents;

use App\Models\User;
use Livewire\Component;
use Filament\Tables\Table;
use Livewire\Attributes\On;
use Livewire\Attributes\Locked;
use Illuminate\Support\Facades\DB;
use Livewire\WithoutUrlPagination;
use Filament\Tables\Actions\Action;
use Illuminate\Support\Facades\Log;
use TallStackUi\Traits\Interactions;
use App\Models\Akreditasi\AkreElement;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Contracts\HasTable;
use Illuminate\Support\Facades\Storage;
use App\Models\Akreditasi\AkreDocuments;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use App\Models\Akreditasi\AkreElementDocuments;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Tables\Concerns\InteractsWithTable;

class TablePencarian extends Component implements HasTable, HasForms
{
    use Interactions;
    use InteractsWithForms, InteractsWithTable;
    use WithoutUrlPagination;

    // Unique pagination name untuk menghindari conflict dengan table lain
    protected string $paginationPageName = 'documentsAkrePage';

    #[Locked]
    public ?int $kegiatan_id;

    public ?int $chapter_id = null;
    public ?int $sub_id = null;
    public ?int $element_id = null;

    public $docSelectedId;

    public function mount($kegiatan_id, $chapter_id = null, $sub_id = null, $element_id = null)
    {
        $this->kegiatan_id = $kegiatan_id;
        $this->chapter_id = $chapter_id;
        $this->sub_id = $sub_id;
        $this->element_id = $element_id;
    }


    public function table(Table $table): Table
    {
        return $table
            ->query(
                $this->getDocumentQuery()
            )
            ->columns([
                TextColumn::make('nama')
                    ->label('Nama Dokumen')
                    ->limit(25)
                    ->searchable()
                    ->sortable()
                    ->wrap(),

                TextColumn::make('filename')
                    ->label('Nama File')
                    ->limit(35)
                    ->searchable()
                    ->sortable()
                    ->wrap(),

                TextColumn::make('chapters')
                    ->label('Chapter')
                    ->getStateUsing(function ($record) {
                        return $record->elements
                            ->map(function ($element) {
                                $singkatan = $element->bab->chapter->singkatan ?? '';
                                $namaBab = $element->bab->nama ?? '';
                                $nomor = $element->nomor ?? '';

                                // return "{$singkatan}, {$namaBab}, {$nomor}";
                                return "<div>{$singkatan} 
                                <span class='text-xs text-gray-400'>></span> 
                                {$namaBab} 
                                <span class='text-xs text-gray-400'>></span> 
                                {$nomor}</div>";
                            })
                            ->filter()
                            ->unique()
                            ->implode('');
                    })
                    ->wrap()
                    ->html(),


                // FIXME tampilkan sumber element upload dan status exist atau hapus
                // TextColumn::make('original_element')
                //     ->label('Sumber Document')
                //     ->getStateUsing(function ($record) {
                //         if (!$record->originalElementWithSource || $record->originalElementWithSource->isEmpty()) {
                //             return "<span class='text-gray-500'>Tidak ada sumber</span>";
                //             Log::info('sumber', ['status' => 'Tidak Ada']);
                //         }

                //         // Ambil original element pertama
                //         $originalElement = $record->originalElementWithSource->first();

                //         Log::info('sumber', [
                //             'original' => $originalElement,
                //         ]);

                //         // Cek pivot data
                //         $sourceDeleted = $originalElement->pivot->source_deleted;
                //         $sourceElementId = $originalElement->pivot->source_element_id;
                //         Log::info('sumber', [
                //             'status' => $sourceDeleted,
                //             'sourceElementId' => $sourceElementId
                //         ]);

                //         // Jika source_element_id kosong/null
                //         if (!$sourceElementId) {
                //             return $sourceDeleted
                //                 ? "<span class='text-red-500'>Sumber Dihapus</span>"
                //                 : "<span class='text-gray-500'>Tidak ada sumber</span>";
                //         }

                //         // Ambil source element
                //         $sumber = AkreElement::with('bab.chapter')
                //             ->find($sourceElementId);

                //         if (!$sumber) {
                //             return $sourceDeleted
                //                 ? "<span class='text-red-500'>Sumber Dihapus</span>"
                //                 : "<span class='text-gray-500'>Sumber tidak ditemukan</span>";
                //         }

                //         $deletedLabel = $sourceDeleted
                //             ? "<span class='inline-flex items-center px-1.5 py-0.5 rounded text-xs font-medium bg-red-100 text-red-600'>Dihapus</span>"
                //             : "";

                //         return "<div class='flex flex-col'>
                //         <span class='text-sm'>
                //         {$sumber->bab->chapter->singkatan}
                //         <span class='text-xs text-gray-400'>></span>
                //         {$sumber->bab->nama}
                //         <span class='text-xs text-gray-400'>></span>
                //         {$sumber->nomor}
                //         </span>
                //         <span class='text-xs italic'>{$deletedLabel}</span>
                //     </div>";
                //     })
                //     ->html(),

                TextColumn::make('original_element_document')
                    ->label('Sumber Document')
                    ->getStateUsing(function ($record) {
                        // Ambil original element pertama (karena harusnya hanya 1)
                        // $originalElement = $record->original_element_document;
                        if (!$record->originalElement->first()) {
                            return "<span class='inline-flex items-center px-1.5 py-0.5 rounded text-xs font-medium bg-red-100 text-red-600'>Dihapus</span>";
                        }
                    })
                    ->html()
                    ->wrap(),

                TextColumn::make('mime_type')
                    ->label('Tipe File')
                    ->badge()
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('upload')
                    ->label('Upload By')
                    ->getStateUsing(function (AkreDocuments $record) {
                        $formattedDate = $record->created_at->format('Y-m-d H:i:s');
                        return
                            "<div class='flex flex-col'>
                                <span class='text-sm'>{$record->user_upload}</span>
                                <span class='text-xs text-gray-500 italic'>{$formattedDate}</span>
                            </div>";
                    })
                    ->html()
                    ->sortable(query: function ($query, string $direction): void {
                        $query->orderBy('created_at', $direction);
                    })
                    ->toggleable()
            ])
            ->filters([
                SelectFilter::make('uploaded_by')
                    ->label('Upload By')
                    ->options(
                        AkreDocuments::query()
                            ->select('uploaded_by')
                            ->groupBy('uploaded_by')
                            ->get()
                            ->mapWithKeys(fn($doc) => [
                                $doc->uploaded_by => $doc->user_upload
                            ])
                            ->toArray()
                    )
                    ->searchable()
                    ->preload()
            ])
            ->actions([
                Action::make('view')
                    ->iconButton()
                    ->icon('tabler-dual-screen')
                    ->color('success')
                    ->action(
                        function (AkreDocuments $record, $livewire) {
                            $livewire->modal(
                                id: $record->id,
                                modal: 'modal-view-document-search'
                            );
                        }
                    ),
                Action::make('attach')
                    ->color('primary')
                    ->iconButton()
                    ->icon('tabler-file-export')
                    ->action(
                        fn(AkreDocuments $record, $livewire) => $livewire->modal(
                            id: $record->id,
                            modal: 'modal-attach-file-search'
                        )
                    ),

                DeleteAction::make('delete')
                    ->iconButton()
                    ->requiresConfirmation()
                    ->modalHeading('Hapus Document')
                    ->modalDescription('File akan dihapus permanent dari aplikasi. Apakah anda yakin?')
                    ->before(function ($record) {
                        // 1. Hapus file jika ada
                        if ($record->path && Storage::disk('public')->exists($record->path)) {
                            Storage::disk('public')->delete($record->path);
                        }
                        // 2. Hapus record DB
                        $record->delete();
                    })
                    ->after(
                        function () {
                            $this->toast()
                                ->success('Berhasil', 'File permanent dihapus.')
                                ->send();
                        }
                    )
                    ->visible(
                        fn($record) => !is_null($record->deleted_at) and auth()->user()->hasRole('Super-Admin')
                    )

            ])
            ->defaultSort('created_at', 'desc')
            ->striped()
            ->paginated([10, 25, 50, 100]);;
    }

    protected function getDocumentQuery(): Builder
    {
        return AkreDocuments::query()
            ->with([
                'originalElement',
                'elements.bab.chapter',
                // 'originalElementWithSource',
            ])
            ->when($this->chapter_id, function (Builder $query, $chapterId) {
                $query->whereHas('elements.bab.chapter', function (Builder $q) use ($chapterId) {
                    $q->where('akre_chapter.id', $chapterId);
                });
            })
            ->when($this->sub_id, function (Builder $query, $babId) {
                $query->whereHas('elements.bab', function (Builder $q) use ($babId) {
                    $q->where('akre_bab_elements.id', $babId)
                        ->where('akre_bab_elements.bab', 'sub');
                });
            })
            ->when($this->element_id, function (Builder $query, $elementId) {
                $query->whereHas('elements', function (Builder $q) use ($elementId) {
                    $q->where('akre_elements.id', $elementId);
                });
            });
    }

    #[On('document-attached')]
    public function refreshTable()
    {
        $this->resetTable();
    }


    public function modal($id, $modal)
    {
        $this->docSelectedId = $id;
        $this->dispatch('open-modal', id: $modal);
    }


    public function render()
    {
        return view('livewire.akreditasi.documents.table-pencarian');
    }
}
