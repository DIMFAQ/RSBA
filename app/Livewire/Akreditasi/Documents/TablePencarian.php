<?php

namespace App\Livewire\Akreditasi\Documents;

use Livewire\Component;
use Filament\Tables\Table;
use Livewire\WithoutUrlPagination;
use Filament\Tables\Actions\Action;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Contracts\HasTable;
use App\Models\Akreditasi\AkreDocuments;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Tables\Concerns\InteractsWithTable;

class TablePencarian extends Component implements HasTable, HasForms
{
    use InteractsWithForms, InteractsWithTable;
    use WithoutUrlPagination;

    // Unique pagination name untuk menghindari conflict dengan table lain
    protected string $paginationPageName = 'documentsAkrePage';

    public ?int $chapter_id = null;
    public ?int $sub_id = null;
    public ?int $element_id = null;

    public $docSelectedId;

    public function mount($chapter_id = null, $sub_id = null, $element_id = null)
    {

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
                    ->searchable()
                    ->sortable(),

                TextColumn::make('filename')
                    ->label('Nama File')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('chapters')
                    ->label('Chapter')
                    ->getStateUsing(function (AkreDocuments $record) {
                        return $record->elements()
                            ->with('bab.chapter')
                            ->get()
                            ->pluck('bab.chapter.singkatan')
                            ->unique()
                            ->implode(', ');
                    })
                    ->wrap(),

                TextColumn::make('bab')
                    ->label('Bab')
                    ->getStateUsing(function (AkreDocuments $record) {
                        return $record->elements()
                            ->with('bab')
                            ->get()
                            ->pluck('bab.nama')
                            ->unique()
                            ->implode(', ');
                    })
                    ->wrap()
                    ->toggleable(),


                TextColumn::make('elements')
                    ->label('Element')
                    ->getStateUsing(function (AkreDocuments $record) {
                        return $record->elements()
                            ->get()
                            ->pluck('nomor')
                            ->unique()
                            ->implode(', ');
                    })
                    ->wrap()
                    ->toggleable(),

                TextColumn::make('mime_type')
                    ->label('Tipe File')
                    ->badge()
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('created_at')
                    ->label('Tanggal Upload')
                    ->dateTime('d M Y H:i')
                    ->sortable()
                    ->toggleable(),
            ])
            ->actions([
                Action::make('view')
                    ->iconButton()
                    ->icon('tabler-dual-screen')
                    ->color('success')
                    // ->url(fn(AkreDocuments $record) => route('documents.view', $record->id))
                    // ->openUrlInNewTab()
                    ->action(
                        function (AkreDocuments $record, $livewire) {
                            $livewire->modal(
                                id: $record->id,
                                modal: 'modal-view-document-search'
                            );
                        }
                    )
                    ->visible(fn(AkreDocuments $record) => in_array($record->mime_type, [
                        'application/pdf',
                        'image/jpeg',
                        'image/png',
                    ])),

                Action::make('attach')
                    ->iconButton()
                    ->icon('tabler-file-export')
            ])
            ->defaultSort('created_at', 'desc')
            ->striped()
            ->paginated([10, 25, 50, 100]);;
    }

    protected function getDocumentQuery(): Builder
    {
        return AkreDocuments::query()
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
