<?php

namespace App\Livewire\Surat\Cuti;

use App\Enums\StatusCuti;
use App\Models\Sdm\Karyawan;
use App\Models\Surat\SuratCuti;
use Livewire\Component;
use Filament\Tables\Table;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Contracts\HasTable;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;

class TableCuti extends Component implements HasTable, HasForms
{
    use InteractsWithTable, InteractsWithForms;
    public ?SuratCuti $surat;

    public static function table(Table $tableCuti): Table
    {
        return $tableCuti
            ->query(SuratCuti::query()->orderBy('id', 'desc'))
            ->columns([
                TextColumn::make('no_surat')
                    ->label('No Surat')
                    ->searchable()
                    ->sortable()
                    ->copyable(),
                TextColumn::make('karyawan.nama')
                    ->label('Nama Karyawan')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('lama_cuti')
                    ->label('Lama Cuti')
                    ->formatStateUsing(fn(SuratCuti $record) => $record->lama_cuti . " Hari")
                    ->action(
                        fn(SuratCuti $record, $livewire) => $livewire->modal(
                            modal: 'detil-surat-cuti',
                            id: $record->getKey()
                        )
                    ),
                TextColumn::make('tgl_mulai')
                    ->label('Tgl Mulai'),

                TextColumn::make('tgl_akhir')
                    ->label('Tgl Akhir'),

                TextColumn::make('status')
                    ->label('Status')
                    ->formatStateUsing(
                        fn(StatusCuti $state) => $state->nama()
                    )
                    ->badge()
                    ->color(fn(StatusCuti $state) => $state->color()),

                TextColumn::make('acc')
                    ->label('Disetujui')
                    ->formatStateUsing(
                        function (SuratCuti $record) {
                            $accItems = json_decode($record->acc, true);

                            if (!$accItems) {
                                return '';
                            }

                            // TODO Chekclist atasan yang sudah setujui cuti
                            $return = '<ul class="list-none">';
                            foreach ($accItems as $acc) {
                                $karyawan = Karyawan::find($acc);
                                $namaKaryawan = $karyawan ? e($karyawan->nama) : 'Unknown';

                                $return .= '<li class="flex flex-row items-center gap-2">' . $namaKaryawan . '</li>';
                            }
                            $return .= '</ul>';

                            return $return;
                        }
                    )->html()
            ])
            ->actions([
                Action::make('print')
                    ->label('Print')
                    ->iconButton()
                    ->icon('tabler-printer'),

                Action::make('approval')
                    ->iconButton()
                    ->icon('tabler-file-check')
                    ->color('success')
                    ->action(
                        fn(SuratCuti $record, $livewire) => $livewire->modal(
                            modal: 'modal-approval-cuti',
                            id: $record->getKey()
                        )
                    )
            ]);
    }

    public function modal($modal, $id)
    {
        $this->surat = SuratCuti::findOrFail($id);
        $this->dispatch('open-modal', id: $modal);
    }

    public function render()
    {
        return view('livewire.surat.cuti.table-cuti');
    }
}
