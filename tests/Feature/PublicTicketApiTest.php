<?php

namespace Tests\Feature;

use App\Models\Maintenance\PublicReport;
use App\Models\Ruangan;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PublicTicketApiTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
    }

    public function test_can_create_ticket_via_public_api(): void
    {
        $ruangan = Ruangan::first() ?? Ruangan::create(['nama' => 'Poli Test']);

        $payload = [
            'ruangan_id'     => $ruangan->id,
            'jenis'          => 'umum',
            'deskripsi'      => 'Kran air di wastafel bocor dan menetes terus.',
            'pelapor_nama'   => 'Ahmad',
            'pelapor_kontak' => '081234567890',
        ];

        $response = $this->postJson('/api/public/tickets', $payload);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'message',
                'tracking_code',
                'data' => [
                    'tracking_code',
                    'jenis',
                    'jenis_label',
                    'deskripsi',
                    'status',
                    'status_label',
                    'pelapor_nama',
                    'ruangan',
                    'created_at',
                    'updated_at',
                ],
            ]);

        $trackingCode = $response->json('tracking_code');
        $this->assertNotNull($trackingCode);
        $this->assertStringStartsWith('TKT-', $trackingCode);

        $this->assertDatabaseHas('public_maintc_reports', [
            'tracking_code' => $trackingCode,
            'ruangan_id'    => $ruangan->id,
            'jenis'         => 'umum',
            'pelapor_nama'  => 'Ahmad',
            'status'        => 'pending',
        ]);
    }

    public function test_validation_fails_when_fields_are_missing(): void
    {
        $response = $this->postJson('/api/public/tickets', []);

        $response->assertStatus(422)
            ->assertJsonStructure([
                'message',
                'errors' => [
                    'ruangan_id',
                    'jenis',
                    'deskripsi',
                ],
            ]);
    }

    public function test_validation_fails_for_short_description_or_invalid_jenis(): void
    {
        $ruangan = Ruangan::first() ?? Ruangan::create(['nama' => 'Poli Test']);

        $response = $this->postJson('/api/public/tickets', [
            'ruangan_id' => $ruangan->id,
            'jenis'      => 'invalid_type',
            'deskripsi'  => 'Pendek',
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['jenis', 'deskripsi']);
    }

    public function test_can_retrieve_ticket_by_tracking_code(): void
    {
        $ruangan = Ruangan::first() ?? Ruangan::create(['nama' => 'Poli Test']);

        $report = PublicReport::create([
            'ruangan_id'     => $ruangan->id,
            'jenis'          => 'it',
            'deskripsi'      => 'Komputer kasir mati mendadak saat transaksi.',
            'pelapor_nama'   => 'Siti',
            'pelapor_kontak' => '08987654321',
            'status'         => 'proses',
        ]);

        $response = $this->getJson('/api/public/tickets/' . $report->tracking_code);

        $response->assertStatus(200)
            ->assertJson([
                'message' => 'Data tiket ditemukan.',
                'data'    => [
                    'tracking_code' => $report->tracking_code,
                    'jenis'         => 'it',
                    'jenis_label'   => 'IT',
                    'deskripsi'     => 'Komputer kasir mati mendadak saat transaksi.',
                    'status'        => 'proses',
                    'status_label'  => 'Diproses',
                    'pelapor_nama'  => 'Siti',
                    'ruangan'       => $ruangan->nama,
                ],
            ]);

        // Pastikan internal fields tidak bocor
        $data = $response->json('data');
        $this->assertArrayNotHasKey('id', $data);
        $this->assertArrayNotHasKey('handled_by', $data);
        $this->assertArrayNotHasKey('catatan_handler', $data);
    }

    public function test_returns_404_for_non_existent_tracking_code(): void
    {
        $response = $this->getJson('/api/public/tickets/TKT-99999999-NOTFOUND');

        $response->assertStatus(404)
            ->assertJson([
                'message' => 'Tiket dengan kode tracking tersebut tidak ditemukan.',
            ]);
    }
}
