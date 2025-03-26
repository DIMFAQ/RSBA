<?php

namespace App\Livewire\Karyawan\Dokter;

use Livewire\Component;
use Filament\Tables\Table;
use App\Models\Sdm\Karyawan;
use App\Enums\StatusKaryawan;
use App\Models\Dokter;
use App\Models\DokterSpesialisasi;
use Filament\Tables\Actions\Action;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Contracts\HasTable;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Filters\SelectFilter;

class TableDokter extends Component implements HasTable, HasForms
{
    use InteractsWithTable, InteractsWithForms;

    public static function table(Table $table): Table
    {
        return $table
            ->query(Dokter::query()->with('karyawan')->with('spesialis'))
            ->columns([
                TextColumn::make('karyawan.nama')
                    ->label('Nama')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('karyawan.status')
                    ->label('Status Pegawai')
                    ->formatStateUsing(fn(StatusKaryawan $state) => $state->nama())
                    ->badge()
                    ->color(fn(StatusKaryawan $state) => $state->color()),

                TextColumn::make('karyawan.latestJabatan.jabatan.nama')
                    ->label('Jabatan')
                    ->default('-'),

                TextColumn::make('spesialis.nama')
                    ->label('Sub Spesialis')
            ])
            // ->filters([
            //     SelectFilter::make('spesialis_id')
            //         ->label('Sub Spesialis')
            //         ->options(
            //             fn() => DokterSpesialisasi::pluck('nama', 'id')->toArray()
            //         )->searchable()

            // ])
            ->actions([
                Action::make('edit')
                    ->iconButton()
                    ->icon('tabler-edit'),

                Action::make('jadwal')
                    ->iconButton()
                    ->icon('tabler-calendar')
                    ->tooltip('Jadwal')
                    ->color('warning'),

                Action::make('delete')
                    ->iconButton()
                    ->icon('tabler-trash')
                    ->color('danger'),
            ]);
    }


    public function render()
    {
        return view('livewire.karyawan.dokter.table-dokter');
    }
}
