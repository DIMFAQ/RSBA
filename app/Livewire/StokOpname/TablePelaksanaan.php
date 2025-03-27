<?php

namespace App\Livewire\StokOpname;

use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Livewire\Component;

class TablePelaksanaan extends Component implements HasTable, HasForms
{
    use InteractsWithTable, InteractsWithForms;

    public function table(Table $table): Table
    {
        return $table

            ->striped();
    }

    public function render()
    {
        return view('livewire.stok-opname.table-pelaksanaan');
    }
}
