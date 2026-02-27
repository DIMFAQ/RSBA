<?php

namespace App\Livewire\Surat\Sp3;

use Livewire\Component;
use Filament\Tables\Table;
use App\Enums\StatusApproval;
use App\Models\Surat\SuratSp3;
use Livewire\Attributes\Locked;
use Filament\Tables\Actions\Action;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Filters\SelectFilter;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Tables\Concerns\InteractsWithTable;
use Livewire\Attributes\On;

class TableSp3 extends Component implements HasTable, HasForms
{

    use InteractsWithTable, InteractsWithForms;

    #[Locked]
    public ?SuratSp3 $suratSp3;

    public function table(Table $table): Table
    {
        $userLogin = auth()->user(); // user login

        return $table
            ->query(
                SuratSp3::withSum('details', 'nominal')->with(['approvals', 'createdBy'])
                    ->when(!$userLogin->hasRole('Super-Admin'), function ($query) use ($userLogin) {
                        $jabatanId = $userLogin->karyawan?->jabatan?->first()?->id;
                        $userId = $userLogin->id;

                        $query->where(function ($q) use ($jabatanId, $userId) {
                            $q->orWhere('surat_sp3.created_by', $userId); // where dibuat oleh user login

                            if ($jabatanId) {
                                $q->orWhere('surat_sp3.jabatan_id', $jabatanId); //atau where mengetahui user login
                            }
                        });
                    })
                    ->latest()
            )
            ->columns([
                TextColumn::make('no')
                    ->label('Nomor')
                    ->searchable()
                    ->copyable(),

                TextColumn::make('tgl')
                    ->label('Tanggal')
                    ->sortable(),

                TextColumn::make('rekanan')
                    ->label('Rekanan'),

                TextColumn::make('keterangan')
                    ->label('Subject / Berita')
                    ->limit(72)
                    ->wrap()
                    ->searchable(),

                TextColumn::make('dibuatOleh')
                    ->label('Dibuat Oleh'),


                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->formatStateUsing(fn(StatusApproval $state) => $state->nama())
                    ->color(fn(StatusApproval $state) => $state->color())
                    ->sortable(),

                TextColumn::make('details_sum_nominal')
                    ->label('Jumlah')
                    ->money('IDR')

            ])
            ->filters([
                SelectFilter::make('status')
                    ->label('Status')
                    ->searchable()
                    ->options(
                        fn(): array => collect(StatusApproval::options())
                            ->pluck('label', 'value')
                            ->toArray()
                    )
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
                    ),

                Action::make('approval')
                    ->icon('tabler-file-check')
                    ->iconButton()
                    ->color('success')
                    ->action(
                        fn($record, $livewire) => $livewire->openModal(
                            modal: 'modal-approval-sp3',
                            id: $record->getKey()
                        )
                    )
                    ->visible(
                        fn($record) => (
                            (
                                auth()->user()->hasRole('Super-Admin') //super admin
                                or
                                $record->jabatan_id === auth()->user()?->karyawan?->jabatan?->first()?->id //jabatan yg mengetahui
                            ) and
                            $record->approvals->count() === 0
                        )

                    )

            ]);
    }


    function openModal($modal, $id)
    {
        $this->suratSp3 = SuratSp3::findOrFail($id);
        return $this->dispatch('open-modal', id: $modal);
    }

    #[On('update-approval')]
    public function refreshTable()
    {
        $this->resetTable();
    }

    public function render()
    {
        return view('livewire.surat.sp3.table-sp3');
    }
}
