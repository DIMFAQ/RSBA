<?php

namespace App\Livewire\Jasmed\Verify;

use App\Models\JmJasa;
use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Tables\Columns\Summarizers\Sum;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Attributes\Lazy;
use Livewire\Component;

#[Lazy]
class ListDokterJasa extends Component implements HasActions, HasSchemas, HasTable
{
    use InteractsWithActions;
    use InteractsWithTable;
    use InteractsWithSchemas;

    public $periode, $layanan, $cabar, $kelompok, $batch;

    public function mount($periode, $layanan, $cabar, $kelompok, $batch)
    {
        $this->periode = $periode;
        $this->layanan = $layanan;
        $this->cabar = $cabar;
        $this->kelompok = $kelompok;
        $this->batch = $batch;
    }

    function baseJasaQuery(): Builder
    {
        return JmJasa::whereHas(
            'prosentase.pasien',
            function ($query) {
                $query
                    ->where('tgl_checkout', 'like', "$this->periode%")
                    ->when($this->layanan, fn($q) => $q->where('layanan', $this->layanan))
                    ->when($this->cabar,   fn($q) => $q->where('cabar',   $this->cabar))
                    ->when($this->kelompok,   fn($q) => $q->where('kelompok',   $this->kelompok))
                    ->when($this->batch,   fn($q) => $q->where('batch',   $this->batch));
            }
        );
    }

    protected ?array $dokterOptions = null;

    public function getDokterOptions(): array
    {
        return $this->dokterOptions ??= $this->baseJasaQuery()
            ->distinct()
            ->orderBy('dokter')
            ->pluck('dokter', 'dokter')
            ->toArray();
    }

    public function table(Table $table): Table
    {

        return $table
            ->query(
                $this->baseJasaQuery()
            )
            ->columns([
                TextColumn::make('dokter'),
                TextColumn::make('prosentase.pasien.nama_pasien')->searchable(),
                TextColumn::make('prosentase.pasien.no_rekmedis')->searchable(),
                TextColumn::make('prosentase.pasien.sep')->searchable(),
                TextColumn::make('prosentase.pasien.tgl_checkout'),
                TextColumn::make('status'),
                TextColumn::make('jasa')
                    ->numeric(
                        decimalPlaces: 0,
                        thousandsSeparator: '.'
                    )
                    ->summarize(
                        Sum::make()
                            ->label('Total Jasa')
                            ->numeric(
                                decimalPlaces: 0,
                                thousandsSeparator: '.'
                            )
                    )
                    ->sortable()

            ])
            ->filters([
                SelectFilter::make('dokter')
                    ->label('Dokter')
                    ->multiple()
                    ->searchable()
                    ->options(
                        fn() => $this->getDokterOptions()
                    )
                    ->query(function (Builder $query, array $data) {
                        $query->when(
                            !empty($data['values']),
                            fn($q) => $q->whereIn('dokter', $data['values'])
                        );
                    }),

                Filter::make('jasa')
                    ->schema([
                        TextInput::make('nilai')
                            ->type('number')
                            ->label('Jasa Kurang Dari')
                            ->placeholder('Nilai')
                    ])
                    ->query(
                        function (Builder $query, array $data) {
                            return $query->when(
                                filled($data['nilai']),
                                fn(Builder $query, $nilai): Builder => $query->where('jasa', '<=', $nilai)
                            );
                        }
                    )

            ])
            ->deferFilters(false)
            ->recordActions([
                Action::make('edit')
                    ->modalHeading(
                        fn($record) => "Dokter: {$record->dokter} | Pasien : {$record->prosentase->pasien->nama_pasien}"
                    )
                    ->schema([
                        TextInput::make('jasa')
                            ->label('Nilai Jasa')
                            ->required()
                    ])
                    ->fillForm(fn($record) => [
                        'jasa' => $record->jasa
                    ])
                    ->action(function ($record, array $data) {
                        $record->update([
                            'jasa' => $data['jasa']
                        ]);
                    })
                    ->modalFooterActions(fn() => [
                        Action::make('submit')
                            ->label('Simpan')
                            ->submit('submit')
                    ])
                    ->modalFooterActionsAlignment('right')
            ]);
    }

    public function render()
    {
        return view('livewire.jasmed.verify.list-dokter-jasa');
    }
}
