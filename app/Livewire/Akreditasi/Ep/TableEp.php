<?php

namespace App\Livewire\Akreditasi\Ep;

use Filament\Forms\Set;
use Livewire\Component;
use Filament\Tables\Table;
use Filament\Tables\Actions\Action;
use TallStackUi\Traits\Interactions;
use Filament\Forms\Components\Select;
use App\Models\Akreditasi\AkreElement;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ViewColumn;
use Filament\Tables\Contracts\HasTable;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Get;
use Filament\Tables\Concerns\InteractsWithTable;

class TableEp extends Component implements HasTable, HasForms
{
    use Interactions;
    use InteractsWithTable, InteractsWithForms;

    public ?int $akre_bab_id;

    public ?int $docSelectedId;

    public ?int $elementSelectedId;

    public function mount($akre_bab_id)
    {
        $this->akre_bab_id = $akre_bab_id;
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(
                AkreElement::with('files')->where('akre_bab_id', $this->akre_bab_id)
            )
            ->columns([
                TextColumn::make('element')
                    ->label('Element Penilaian')
                    ->prefix(
                        fn($record) => $record->nomor . ") "
                    )
                    ->wrap()
                    ->searchable(),

                TextColumn::make('methode')
                    ->label('Methode')
                    ->badge(),

                TextColumn::make('kelengkapan')
                    ->label('Kelengkapan Penilaian')
                    ->wrap(),

                TextColumn::make('nilai')
                    ->label('Nilai')
                    ->badge()
                    ->state(function ($record) {
                        if ($record->tdd) {
                            return 'TDD';
                        }
                        return $record->nilai ?? 'Belum Dinilai';
                    })
                    ->color(function ($record) {
                        if ($record->tdd) {
                            return 'primary';
                        }

                        return match ($record->nilai) {
                            null => 'gray',
                            0 => 'danger',
                            5 => 'warning',
                            10 => 'success',
                            default => 'gray'
                        };
                    })
                    ->default('Belum Dinilai')
                    ->action(
                        Action::make('penilaian')
                            ->form([
                                Checkbox::make('tdd')
                                    ->label('Tidak Dapat Dinilai (TDD)')
                                    ->live()
                                    ->afterStateUpdated(function ($state, Set $set) {
                                        if ($state) {
                                            $set('nilai', null);
                                        }
                                    }),

                                Select::make('nilai')
                                    ->label('Nilai')
                                    ->options([
                                        0 => '0 - Tidak Ada',
                                        5 => '5 - Sebagian',
                                        10 => '10 - Lengkap'
                                    ])
                                    ->required(fn(Get $get) => !$get('tdd'))
                                    ->disabled(fn(Get $get) => $get('tdd'))
                                    ->dehydrated(),

                                Textarea::make('catatan')
                                    ->label('Catatan')
                                    ->rows(3)
                            ])
                            ->fillForm(fn($record) => [
                                'nilai' => $record->nilai,
                                'catatan' => $record->catatan
                            ])
                            ->action(function ($record, array $data) {
                                $record->update($data);
                                $this->toast()
                                    ->success('Berhasil', 'Penilaian berhasil disimpan.')
                                    ->send();
                            })
                            ->visible(fn() => auth()->user()->can('assesor-akreditasi'))
                    ),

                TextColumn::make('catatan')
                    ->label('Catatan'),

                // Custom column untuk detail
                ViewColumn::make('details')
                    ->label('Documents')
                    ->view('livewire.akreditasi.ep.list-documents'),
            ])
            ->actions([
                Action::make('upload')
                    ->iconButton()
                    ->icon('tabler-book-upload')
                    ->action(
                        fn($record, $livewire) => $livewire->modal(
                            modal: 'modal-manage-document-ep',
                            id: $record->getKey()
                        )
                    )
                    ->visible(
                        fn() =>
                        auth()->user()->hasRole('Super-Admin') or
                            !auth()->user()->can('assesor-akreditasi')

                    )
            ]);
    }

    public function modal($modal, $id)
    {
        $this->elementSelectedId = $id;
        $this->dispatch('open-modal', id: $modal);
    }

    public function modalViewDocument($id, $modal)
    {
        $this->docSelectedId = $id;
        $this->dispatch('open-modal', id: $modal);
    }

    public function render()
    {
        return view('livewire.akreditasi.ep.table-ep');
    }
}
