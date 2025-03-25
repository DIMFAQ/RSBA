<?php

namespace App\Livewire\Karyawan;

use Livewire\Component;
use Filament\Tables\Table;
use App\Models\Sdm\Karyawan;
use App\Enums\StatusKaryawan;
use App\Models\Sdm\Jabatan;
use Filament\Forms\Components\Select;
use Filament\Tables\Actions\Action;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Filters\SelectFilter;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Filters\Filter;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Attributes\Locked;

class TableKaryawan extends Component implements HasForms, HasTable
{
    use InteractsWithTable, InteractsWithForms;

    #[Locked]
    public $karyawanId = null; //default

    public static function table(Table $table): Table
    {
        return $table
            ->query(Karyawan::query()->with('latestJabatan.jabatan'))
            ->deferLoading(false)
            ->striped()
            ->columns([
                TextColumn::make('nip')
                    ->label('NIP')
                    ->copyable()
                    ->sortable()
                    ->searchable(),

                TextColumn::make('nama')
                    ->label('Nama')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('status')
                    ->label('Status')
                    ->formatStateUsing(fn(StatusKaryawan $state) => $state->nama())
                    ->badge()
                    ->color(fn(StatusKaryawan $state) => $state->color()),

                TextColumn::make('latestJabatan.jabatan.nama')
                    ->label('Jabatan')
                    ->default('-')
                    ->action(function (Karyawan $record, $livewire): void {
                        // dispatch event to livewire
                        $livewire->historyJabatan('history-jabatan', $record->getKey());
                    })
                    ->tooltip('History Jabatan'),

                TextColumn::make('masakerja')
                    ->label('Masa Kerja'),

                TextColumn::make('status_dinas')
                    ->badge()
                    ->getStateUsing(function (Karyawan $record) {
                        return $record->resign ?? 'Aktif';
                    })
                    ->color(fn(Karyawan $record) => (!empty($record->resign)) ? 'danger' : 'primary'),
            ])
            ->filters([
                // Filter status
                SelectFilter::make('status')
                    ->label('Status Karyawan')
                    ->options(
                        // options dari enum StatusKaryawan
                        fn(): array => collect(StatusKaryawan::options())
                            ->pluck('label', 'value')
                            ->toArray()
                    ),

                // Filter Jabatan
                // FIXME tidak dapat filter jabatan saat ini saja
                Filter::make('Jabatan')
                    ->form([
                        Select::make('Jabatan')
                            ->options(
                                fn() => Jabatan::pluck('nama', 'id')->toArray()
                            )->searchable()
                    ])->modifyQueryUsing(function (Builder $query, $data) {
                        return $query
                            ->when(
                                $data['Jabatan'],
                                fn(Builder $query, $state): Builder =>  $query->whereHas('latestJabatan', function ($query) use ($state) {
                                    $query->where('jabatan_id', $state);
                                })
                            );
                    })
                    ->indicateUsing(function ($data): ?string {
                        if (! $data['Jabatan']) {
                            return null;
                        }

                        $jabatanNama = Jabatan::find($data['Jabatan'])->nama ?? null;

                        return $jabatanNama ? 'Jabatan : ' . $jabatanNama : null;
                    })
            ])
            ->actions([
                Action::make('view-profile')
                    ->iconButton()
                    ->icon('tabler-printer')
                    ->color('primary')
                    ->action(function (Karyawan $record, $livewire): void {
                        $livewire->profileKaryawan('modal-print-cv', $record->getKey());
                    }),

                Action::make('edit')
                    ->iconButton()
                    ->icon('tabler-user-edit')
                    ->color('danger')
                    ->url(fn(Karyawan $record): string => route('kepegawaian.karyawan.edit', $record))
                    ->visible(
                        fn() => auth()->user()->can('edit-karyawan')
                    )
            ]);
    }

    function profileKaryawan($id, $karyawan)
    {
        $this->karyawanId = $karyawan;
        $this->dispatch('open-modal', id: $id);
    }

    // #[On('open-history-jabatan')]
    public function historyJabatan($id, $karyawan)
    {
        $this->karyawanId = $karyawan;
        $this->dispatch('open-modal', id: $id);
    }

    public function render()
    {
        return view('livewire.karyawan.table-karyawan');
    }
}
