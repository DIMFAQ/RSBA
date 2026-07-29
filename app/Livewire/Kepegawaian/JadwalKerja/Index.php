<?php

namespace App\Livewire\Kepegawaian\JadwalKerja;

use Filament\Actions\Contracts\HasActions;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Action;
use Throwable;
use Livewire\Component;
use App\Models\Sdm\JadwalKerja;
use App\Traits\AuthorizesFromRoute;
use Filament\Tables\Table;
use Livewire\Attributes\Lazy;
use Livewire\Attributes\Title;
use TallStackUi\Traits\Interactions;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Contracts\HasTable;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Tables\Concerns\InteractsWithTable;
use Illuminate\Support\Facades\Auth;

#[Lazy]
#[Title('Jadwal Kerja Pegawai')]
class Index extends Component implements HasForms, HasTable, HasActions
{
    use InteractsWithActions;
    use AuthorizesFromRoute;
    use InteractsWithForms, InteractsWithTable;
    use Interactions;

    protected $listeners = ['jadwal-kerja-generated' => '$refresh'];

    public function table(Table $table): Table
    {
        $query = JadwalKerja::query()
            ->with(['ruangan', 'pembuat', 'diketahuiOleh', 'disetujuiOleh'])
            ->orderBy('tahun', 'desc')
            ->orderBy('bulan', 'desc')
            ->orderBy('id', 'desc');

<<<<<<< HEAD
        // Jika Anda ingin mempertahankan hak akses, Anda bisa menambah logika pengecekan di sini
        // Misalnya mengecek apakah user punya wewenang untuk ruangan ini.
        // Untuk sementara, kita abaikan pengecekan spesifik (semua user yang bisa masuk menu bisa lihat semua jadwal).
=======
        $user = Auth::user();
        if ($user) {
<<<<<<< HEAD
            $ruanganIds = $user->getRuanganKoordinatorIds();
            // null = Super-Admin/Staff-SDM, akses semua ruangan
            // [] kosong = tidak punya akses ruangan sama sekali
            if ($ruanganIds !== null) {
=======
            if ($user->hasRole(['Super-Admin', 'Staff-SDM'])) {
                // Super-Admin & Staff-SDM dapat melihat semua ruangan
            } elseif ($user->isKoordinatorDokter()) {
                $ruanganIds = $user->getRuanganKoordinatorIds() ?? [];
                if (empty($ruanganIds)) {
                    $query->whereRaw('0 = 1');
                } else {
                    $query->whereIn('ruangan_id', $ruanganIds)->where('tipe', 'dokter');
                }
            } elseif ($user->isKoordinatorKaryawan()) {
                $ruanganIds = $user->getRuanganKoordinatorIds() ?? [];
                $ownRuanganId = $user->karyawan?->ruangan_id;
                if ($ownRuanganId && !in_array($ownRuanganId, $ruanganIds)) {
                    $ruanganIds[] = $ownRuanganId;
                }
                
>>>>>>> 8685ac3 (feat(sdm): pemisahan sdm_jadwal_kerja tipe karyawan dan dokter)
                if (empty($ruanganIds)) {
                    $query->whereRaw('0 = 1'); // tidak ada ruangan yg bisa diakses
                } else {
                    $query->whereIn('ruangan_id', $ruanganIds)->where('tipe', 'karyawan');
                }
<<<<<<< HEAD
=======
            } else {
                // User biasa: hanya melihat ruangan tempat dia ditugaskan (teman seruangan)
                $ownRuanganId = $user->karyawan?->ruangan_id;
                $isDokter = $user->isDokter();
                if ($ownRuanganId) {
                    $query->where('ruangan_id', $ownRuanganId)
                        ->where('tipe', $isDokter ? 'dokter' : 'karyawan');
                } else {
                    $query->whereRaw('0 = 1');
                }
>>>>>>> 8685ac3 (feat(sdm): pemisahan sdm_jadwal_kerja tipe karyawan dan dokter)
            }
        }
>>>>>>> aad182c (feat: implement koordinator as supplementary assignment/task instead of role)

        return $table
            ->query($query)
            ->columns([
                TextColumn::make('ruangan.nama')->label('Ruangan (Tim)')->searchable()->sortable(),
                TextColumn::make('tipe')
                    ->label('Tipe')
                    ->badge()
                    ->formatStateUsing(fn ($state) => ucfirst($state))
                    ->color(fn ($state) => $state === 'dokter' ? 'info' : 'success'),
                TextColumn::make('bulan')->label('Bulan')->formatStateUsing(fn ($state) => date('F', mktime(0, 0, 0, $state, 1)))->sortable(),
                TextColumn::make('tahun')->label('Tahun')->sortable(),
                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn ($state) => $state->color())
                    ->formatStateUsing(fn ($state) => $state->nama()),
                TextColumn::make('diketahuiOleh.nama')->label('Diketahui (Kabid)')->placeholder('—')->toggleable(),
                TextColumn::make('disetujuiOleh.nama')->label('Disetujui (Wadir)')->placeholder('—')->toggleable(),
                TextColumn::make('pembuat.nama')->label('Dibuat Oleh')->toggleable(isToggledHiddenByDefault: true),
            ])
            ->recordActions([
                Action::make('kelola')
                    ->label('Kelola')
                    ->iconButton()
                    ->icon('tabler-list-details')
                    ->color('primary')
                    ->url(fn (JadwalKerja $record): string => route('kepegawaian.jadwal-kerja.kelola', ['id' => $record->id])),
                Action::make('delete')
                    ->label('Hapus')
                    ->iconButton()
                    ->icon('tabler-trash')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->action(fn (JadwalKerja $record) => $record->delete())
                    ->successNotificationTitle('Jadwal berhasil dihapus')
<<<<<<< HEAD
                    ->visible(fn (JadwalKerja $record): bool => $record->status === \App\Enums\StatusJadwalKerja::DRAFT),
=======
                    ->visible(fn (JadwalKerja $record): bool => 
                        in_array($record->status, [\App\Enums\StatusJadwalKerja::DRAFT, \App\Enums\StatusJadwalKerja::DITOLAK]) && 
                        (Auth::user()?->hasRole(['Super-Admin', 'Staff-SDM']) || 
                         (Auth::user()?->isKoordinator() && in_array($record->ruangan_id, Auth::user()->getRuanganKoordinatorIds() ?? [])))
                    ),
>>>>>>> 339c4c4 (feat(jadwal-kerja): implementasi UI dan Livewire multi-tier approval dengan stepper dinamis)
            ]);
    }

    public function render()
    {
        $this->authorizeFromRoute();
        return view('livewire.kepegawaian.jadwal-kerja.index');
    }
}
