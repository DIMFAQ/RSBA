<?php

namespace App\Livewire\Asset;

use App\Models\Ruangan;
use Livewire\Component;
use Filament\Tables\Table;
use App\Models\Assets\AssetBarang;
use Filament\Tables\Actions\Action;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Filters\SelectFilter;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Tables\Concerns\InteractsWithTable;
use Livewire\Attributes\Locked;

class TableAsset extends Component implements HasTable, HasForms
{
    use InteractsWithTable, InteractsWithForms;

    #[Locked]
    public $selectedId;

    public function table(Table $table): Table
    {
        return $table
            ->query(
                AssetBarang::with(['barang', 'ruangan', 'barang.kategori'])
            )
            ->columns([
                TextColumn::make('kode')
                    ->label('Asset Kode')
                    ->getStateUsing(
                        fn($record) => $record->kode ?? 'Belum didaftarkan'
                    )
                    ->color(
                        fn($record) => $record->kode ? '' : 'danger'
                    )
                    ->searchable(),

                TextColumn::make('barang.nama')
                    ->label('Item')
                    ->searchable()
                    ->getStateUsing(
                        fn($record) => $record->main_asset_id
                            ? "<span class='ms-2'>" . $record->barang->nama . "</span>"
                            : $record->barang->nama
                    )
                    ->html(),

                TextColumn::make('barang.kategori.nama')
                    ->label('Kategori'),

                TextColumn::make('ruangan.nama')
                    ->label('Di Ruangan'),

                TextColumn::make('tanggal_catat')
                    ->label('Tanggal Pencatatan'),

                TextColumn::make('status')
                    ->label('Status')
            ])
            ->filters([
                SelectFilter::make('ruangan_id')
                    ->label('Ruangan')
                    ->searchable()
                    ->options(
                        fn() => Ruangan::pluck('nama', 'id')->toArray()
                    )
            ])
            ->actions([
                Action::make('catat')
                    ->iconButton()
                    ->icon('tabler-library-plus')
                    ->color('success')
                    ->action(
                        fn($record, $livewire) => $livewire->modalAsset(
                            modal: 'modal-catat-asset',
                            id: $record->getKey()
                        )
                    )
                    ->visible(
                        fn($record) => !$record->kode
                    ),

                // edit asset, dan edit spesiikasi asset
                Action::make('edit')
                    ->iconButton()
                    ->icon('tabler-edit')
                    ->action(
                        fn($record) => $this->modalAsset(
                            modal: 'modal-asset-biodata',
                            id: $record->getKey()
                        )
                    ),

                // input maintenance
                Action::make('maintenance')
                    ->iconButton()
                    ->icon('tabler-device-imac-cog')
                    ->color('danger')
                    ->action(
                        fn($record) => $this->modalAsset(
                            modal: 'modal-catat-maintenance',
                            id: $record->getKey()
                        )
                    ),

                // logs
                Action::make('logs')
                    ->iconButton()
                    ->icon('tabler-history')
                    ->color('danger')
                    ->action(
                        fn($record) => $this->modalAsset(
                            modal: 'modal-logs-asset',
                            id: $record->getKey()
                        )
                    ),


            ]);
    }

    function modalAsset($modal, $id)
    {
        $this->selectedId = $id;
        $this->dispatch('open-modal', id: $modal);
    }

    public function render()
    {
        return view('livewire.asset.table-asset');
    }
}
