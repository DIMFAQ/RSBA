<?php

namespace App\Livewire\Master\Penyimpanan;

use Filament\Actions\Contracts\HasActions;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Action;
use App\Models\Master\BarangPenyimpanan;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Livewire\Component;

class TablePenyimpanan extends Component implements HasTable, HasForms, HasActions
{
    use InteractsWithActions;
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
            ->recordActions([
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
