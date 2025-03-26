<?php

namespace App\Livewire\Profile;

use App\Enums\StatusCuti;
use App\Models\Sdm\Karyawan;
use App\Models\Surat\SuratCuti;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Lazy;
use Livewire\Component;

#[Lazy]
class Cuti extends Component implements HasTable, HasForms
{
    use InteractsWithTable, InteractsWithForms;

    public ?Karyawan $karyawan;
    public ?SuratCuti $surat;

    public function mount($id)
    {
        $this->karyawan = Karyawan::findOrFail($id);
    }

    public static function table(Table $tableCuti): Table
    {
        return $tableCuti
            ->query(
                SuratCuti::where('karyawan_id', Auth::user()->karyawan_id)
                    ->orderBy('id', 'desc')
            )
            ->deferLoading(false)
            ->columns([
                TextColumn::make('no_surat')
                    ->label('No Surat Cuti'),

                TextColumn::make('tgl_surat')
                    ->label('Tgl Surat'),

                TextColumn::make('lama_cuti')
                    ->label('Lama Cuti')
                    ->formatStateUsing(fn(SuratCuti $record) => $record->lama_cuti . " Hari")
                    ->action(
                        fn(SuratCuti $record, $livewire) => $livewire->detilTanggal(modal: 'view-detil-tanggal', id: $record->getKey())
                    )
                    ->tooltip('Click : untuk detil tanggal.'),

                TextColumn::make('tgl_mulai')
                    ->label('Mulai Cuti'),

                TextColumn::make('tgl_akhir')
                    ->label('Berakhir Cuti'),

                TextColumn::make('status')
                    ->label('Status')
                    ->formatStateUsing(
                        fn(StatusCuti $state) => $state->nama()
                    )
                    ->badge()
                    ->color(
                        fn(StatusCuti $state) => $state->color()
                    ),

                TextColumn::make('acc')
                    ->label('Disetujui Oleh')
            ]);
    }

    // modal detil tanggal cuti
    function detilTanggal($modal, $id)
    {
        $this->surat = SuratCuti::findOrFail($id);
        $this->dispatch('open-modal', id: $modal);
    }

    public function render()
    {
        return view('livewire.profile.cuti');
    }
}
