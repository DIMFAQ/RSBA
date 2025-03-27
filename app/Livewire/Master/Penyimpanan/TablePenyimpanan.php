<?php

namespace App\Livewire\Master\Penyimpanan;

use App\Models\Master\BarangPenyimpanan;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Livewire\Component;

class TablePenyimpanan extends Component implements HasTable, HasForms
{
    use InteractsWithTable, InteractsWithForms;

    public static function table(Table $table): Table
    {
        return $table
            ->query(BarangPenyimpanan::query())
            ->columns([
                TextColumn::make('nama')
                    ->label('Tempat Penyimpanan')
                    ->searchable(),

                TextColumn::make('deskripsi')
                    ->label('Deskripsi')
            ])
            ->actions([
                Action::make('edit')
                    ->iconButton()
                    ->icon('tabler-edit'),

                Action::make('delete')
                    ->iconButton()
                    ->icon('tabler-trash')
                    ->color('danger')
            ]);
    }

    public function render()
    {
        return view('livewire.master.penyimpanan.table-penyimpanan');
    }
}
