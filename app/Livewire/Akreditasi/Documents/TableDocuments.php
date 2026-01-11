<?php

namespace App\Livewire\Akreditasi\Documents;

use Livewire\Component;
use Filament\Tables\Table;
use Livewire\Attributes\On;
use Livewire\WithFileUploads;
use TallStackUi\Traits\Interactions;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Contracts\HasTable;
use Illuminate\Support\Facades\Storage;
use Filament\Tables\Actions\DeleteAction;
use App\Models\Akreditasi\AkreElementDocuments;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Tables\Concerns\InteractsWithTable;

class TableDocuments extends Component implements HasTable, HasForms
{
    use WithFileUploads;
    use Interactions;
    use InteractsWithTable, InteractsWithForms;

    // #[Locked]
    public ?int $element_id;

    public ?int $selectedDocId;

    public function mount($elementId)
    {
        $this->element_id = $elementId;
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(
                AkreElementDocuments::with('document')
                    ->where('element_id', $this->element_id)
            )
            ->columns([
                TextColumn::make('document.nama')
                    ->label('Nama File')
                    ->searchable()
                    ->action(fn($record) => $this->viewDocument(
                        id: $record->document->id
                    )),

                TextColumn::make('document.user_upload')
                    ->label('User Upload'),

                TextColumn::make('document.created_at')
                    ->label('Tanggal Upload'),

                TextColumn::make('is_original')
                    ->label('Document Asli')
                    ->formatStateUsing(
                        function ($record) {
                            $record->is_original ?? $record->source_full_path;
                        }
                    )
            ])
            ->actions([
                DeleteAction::make()
                    ->iconButton()
                    ->modalHeading('Hapus File')
                    ->modalDescription('Are you sure? This will also delete associated files.')
                    ->modalSubmitActionLabel('Ya, Hapus')
                    ->before(function ($record) {
                        AkreElementDocuments::where('document_id', $record->document->id)
                            ->where('is_original', false)
                            ->update(
                                ['source_deleted' => true]
                            );

                        // Soft delete the document
                        $record->document->update([
                            'is_deleted' => true,
                            'deleted_at' => now(),
                            'deleted_by' => auth()->id(),
                        ]);
                    })
                    ->after(function () {
                        $this->dispatch('deleted-files_element');

                        $this->toast()
                            ->success('Berhasil', 'File berhasil dihapus.')
                            ->send();
                    })
            ])

        ;
    }

    #[On('uploaded-files-element')]
    public function refreshTable()
    {
        $this->resetTable();
    }

    public function viewDocument($id)
    {
        $this->selectedDocId = $id;
        $this->dispatch('open-modal', id: "modal-document-view");
    }

    public function render()
    {
        return view('livewire.akreditasi.documents.table-documents');
    }
}
