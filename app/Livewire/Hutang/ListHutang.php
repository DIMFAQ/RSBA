<?php

namespace App\Livewire\Hutang;

use App\Models\Gudang\Pembelian;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Livewire\Attributes\Lazy;
use Livewire\Attributes\Locked;
use Livewire\Component;

#[Lazy]
class ListHutang extends Component implements HasTable, HasForms
{
    use InteractsWithTable, InteractsWithForms;

    #[Locked]
    public $periode;
    public $jenis, $supplier;

    public function mount($periode, $supplier)
    {
        $this->periode = $periode;
        $this->supplier  = $supplier;
        // $this->jenis = $jenis;
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Pembelian::with('supplier')
                    ->when($this->periode, function ($query) {
                        $query->whereMonth('tgl', date('m', strtotime($this->periode)))
                            ->whereYear('tgl', date('Y', strtotime($this->periode)));
                    })
                    ->when($this->supplier, function ($query) {
                        $query->where('supplier_id', $this->supplier);
                    })
            )
            ->striped()
            ->columns([
                TextColumn::make('index')
                    ->label('No.')
                    ->getStateUsing(static function ($rowLoop): string {
                        return (string) $rowLoop->iteration;
                    }),

                TextColumn::make('no')
                    ->label('No. Transaksi')
                    ->searchable(),

                TextColumn::make('supplier.nama')
                    ->label('Vendor / Supplier'),

                TextColumn::make('tgl')
                    ->label('Tanggal Pembelian'),


                TextColumn::make('status')
                    ->label('Status Pembelian')
                    ->badge()
                    ->color(
                        fn(string $state): string => match ($state) {
                            'Waiting' => 'warning',
                            'Selesai' => 'success',
                            'Dibatalkan' => 'danger',
                            default => 'secondary'
                        }
                    )
                    ->getStateUsing(
                        function ($record) {
                            return $record->status ? ucfirst($record->status) : 'Waiting';
                        }
                    ),

                TextColumn::make('status_pembayaran')
                    ->label('Pembayaran')
                    ->badge()
                    ->getStateUsing(function (Pembelian $pembelian) {
                        return $pembelian->status_pembayaran ? ucfirst($pembelian->status_pembayaran) : 'Belum Dibayar';
                    })
                    ->color(
                        fn(Pembelian $pembelian) => match ($pembelian->status_pembayaran) {
                            'lunas' => 'success',
                            'tempo' => 'warning',
                            default => 'danger'
                        }
                    ),

                TextColumn::make('tgl_pembayaran')
                    ->label('Tanggal')
                    ->getStateUsing(
                        fn($record) =>
                        $record->tgl_pembayaran ?
                            \Carbon\Carbon::parse($record->tgl_pembayaran)->diffForHumans() :
                            ''
                    ),

                TextColumn::make('total')
                    ->label('Total')
                    ->money('idr')
            ])
            ->actions([
                Action::make('bayar')
                    ->label('Pembayaran')
                    ->iconButton()
                    ->icon('tabler-file-invoice')
                    ->action(
                        fn($record, $livewire) => $livewire->openModal(
                            modal: 'modal-pembayaran-hutang',
                            id: $record->getKey()
                        )
                    )
            ]);
    }

    public function openModal($modal, $id)
    {

        $this->dispatch('open-modal', id: $modal);
    }



    public function render()
    {
        return view('livewire.hutang.list-hutang');
    }
}
