<?php

use Illuminate\Support\Facades\Route;

// Karyawan
Route::prefix('karyawan')
    ->name('karyawan.')
    ->group(function () {

        Route::get('/', App\Livewire\Karyawan\Index::class)->name('index');
        Route::get('/edit/{id}', App\Livewire\Karyawan\Edit::class)->name('edit');
    });

<<<<<<< HEAD
=======
// Jadwal Kerja
Route::prefix('jadwal-kerja')
    ->name('jadwal-kerja.')
    ->group(function () {
        Route::get('/', App\Livewire\Kepegawaian\JadwalKerja\Index::class)->name('index');
        Route::get('/tukar-dokter', App\Livewire\Kepegawaian\JadwalKerja\TukarJadwal::class)->name('tukar-dokter');
        Route::get('/kelola/{id}', App\Livewire\Kepegawaian\JadwalKerja\Kelola::class)->name('kelola');
    });

// Absensi
Route::prefix('absensi')
    ->name('absensi.')
    ->group(function () {
        Route::get('/', App\Livewire\Kepegawaian\AbsensiContainer::class)->name('index');
        Route::get('/import', App\Livewire\Kepegawaian\Absensi\Import::class)->name('import');
        Route::get('/duplicate-report/{logId?}', App\Livewire\Kepegawaian\Absensi\DuplicateTapReport::class)->name('duplicate-report');
        Route::get('/rekonsiliasi/{batchId}', App\Livewire\Kepegawaian\Absensi\Rekonsiliasi::class)->name('rekonsiliasi');
        Route::get('/rekap', App\Livewire\Kepegawaian\Absensi\Rekap::class)->name('rekap');
    });

// Konfigurasi Jadwal
Route::prefix('konfigurasi-jadwal')
    ->name('konfigurasi-jadwal.')
    ->group(function () {
        Route::get('/', App\Livewire\Kepegawaian\KonfigurasiJadwal::class)->name('index');
    });
>>>>>>> 253fd8c (feat(absensi): add duplicate tap report page and csv export functionality)

// Master data
Route::prefix('master')
    ->name('master.')
    ->group(function () {


        Route::prefix('jabatan')
            ->name('jabatan.')
            ->group(function () {
                Route::get('/', App\Livewire\Master\Jabatan\Index::class)->name('index');
            });

        Route::prefix('ruangan')
            ->name('ruangan.')
            ->group(function () {
                Route::get('/', App\Livewire\Master\Ruangan\Index::class)->name('index');
            });

        Route::prefix('bagian')
            ->name('bagian.')
            ->group(function () {
                Route::get('/', App\Livewire\Master\Bagian\Index::class)->name('index');
            });

        Route::prefix('spesialisasi')
            ->name('spesialisasi.')
            ->group(function () {

                Route::get('/', App\Livewire\Master\Spesialisasi\Index::class)->name('index');
            });

        Route::prefix('cuti')
            ->name('cuti.')
            ->group(function () {

                Route::get('/', App\Livewire\Master\Cuti\Index::class)->name('index');
            });
    });


// Surat Surat
Route::prefix('surat')
    ->name('surat.')
    ->group(function () {
        Route::get('cuti', App\Livewire\Surat\Cuti\Index::class)->name('cuti');
        // Route::get('cuti/approval/{id?}', App\Livewire\Surat\Cuti\Approval::class)->name('cuti.approval');

        Route::get('sp3', App\Livewire\Surat\Sp3\Index::class)->name('sp3');
    });


// Jasa Medis
Route::prefix('jasmed')
    ->name('jasmed.')
    ->group(function () {
        Route::get('/', App\Livewire\Jasmed\Index::class)->name('index');
    });

Route::prefix('laporan')
    ->name('laporan.')
    ->group(function () {
        Route::get('/', App\Livewire\Laporan\Kepegawaian\Index::class)->name('index');
    });

Route::prefix('akreditasi')
    ->name('akreditasi.')
    ->group(
        function () {
            Route::get('/', App\Livewire\Akreditasi\Index::class)->name('index');

            Route::get('/{uuid}', App\Livewire\Akreditasi\Chapters\Index::class)->name('chapters');

            Route::get('/chapter/{chapter:id}/elements', App\Livewire\Akreditasi\Element\Index::class)->name('chapter.elements');

            // download Akreditasi controller
            Route::prefix('download')
                ->name('download.')
                ->group(function () {
                    // Single file download
                    Route::get('/file/{document}', [App\Http\Controllers\AkreDownloadDocsController::class, 'downloadFile'])
                        ->name('download.file');

                    // Element download
                    Route::get('/element/{element}', [App\Http\Controllers\AkreDownloadDocsController::class, 'downloadElement'])
                        ->name('download.element');

                    // Sub Bab download
                    Route::get('/sub-bab/{subBab}', [App\Http\Controllers\AkreDownloadDocsController::class, 'downloadSubBab'])
                        ->name('download.subbab');

                    // Bab download
                    Route::get('/bab/{bab}', [App\Http\Controllers\AkreDownloadDocsController::class, 'downloadBab'])
                        ->name('download.bab');

                    // Chapter download
                    Route::get('/chapter/{chapter}', [App\Http\Controllers\AkreDownloadDocsController::class, 'downloadChapter'])
                        ->name('download.chapter');
                });
        }

    );
