<?php

namespace App\Livewire\Surat\Sp3;

use App\Models\Surat\SuratSp3;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Livewire\Attributes\Locked;
use Livewire\Component;

class TableSp3 extends Component implements HasTable, HasForms
{

    use InteractsWithTable, InteractsWithForms;

    #[Locked]
    public ?SuratSp3 $suratSp3;

    public function table(Table $table): Table
    {
        return $table
            ->query(SuratSp3::withSum('details', 'nominal'))
            ->columns([
                TextColumn::make('no')
                    ->label('Nomor')
                    ->searchable(),

                TextColumn::make('tgl')
                    ->label('Tanggal')
                    ->sortable(),

                TextColumn::make('rekanan')
                    ->label('Rekanan'),

                TextColumn::make('keterangan')
                    ->label('Subject / Berita'),

                TextColumn::make('details_sum_nominal')
                    ->label('Jumlah')
                    ->money('IDR')

            ])
            ->actions([
                Action::make('view')
                    ->iconButton()
                    ->icon('tabler-file-description')
                    ->action(
                        fn($record, $livewire) => $livewire->openModal(
                            modal: 'modal-detail-sp3',
                            id: $record->getKey()
                        )
                    )
            ]);
    }


    function openModal($modal, $id)
    {
        $this->suratSp3 = SuratSp3::findOrFail($id);
        return $this->dispatch('open-modal', id: $modal);
    }

    public function render()
    {
        return view('livewire.surat.sp3.table-sp3');
    }
}
