<?php

namespace App\Livewire\Dashboard;

use App\Models\Ruangan;
use App\Models\Sdm\Dokter as MasterDokter;
use App\Services\DmsMiddlewareClient;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithFileUploads;

#[Layout('components.layouts.app')]
class PoliAdmin extends Component
{
    use WithFileUploads;

    // Active tab: poli | doctors | queue
    public string $activeTab = 'poli';

    // Success/error alerts
    public string $successMessage = '';
    public string $errorMessage = '';

    // Cached data from middleware & master
    public array $polyclinics = [];
    public array $doctors = [];
    public array $queueItems = [];
    public array $queueAvailableDoctors = [];
    public array $masterDoctors = [];
    public array $masterRuangans = [];

    // Poli form (Selection from Master Ruangan)
    public string $editingPoliId = '';
    public string $selectedRuanganId = '';
    public string $poliCode = '';
    public string $poliName = '';

    // Doctor form (Selection from Master Dokter)
    public string $selectedPoliId = '';
    public string $editingDoctorId = '';
    public string $selectedMasterDoctorId = '';
    public string $doctorName = '';
    public $doctorPhoto = null; // Livewire file upload
    public string $doctorSpecialty = '';
    public string $doctorCode = '';
    public int $doctorSortOrder = 0;
    public bool $doctorIsActive = true;

    // Queue form
    public string $queuePoliId = '';
    public string $queueDoctorId = '';
    public string $patientName = '';

    /**
     * Load initial data from Master tables and Middleware Client.
     */
    public function mount(DmsMiddlewareClient $client): void
    {
        $this->loadMasterData();
        $this->polyclinics = $client->getPolyclinics();
    }

    public function render()
    {
        return view('livewire.dashboard.poli-admin')->title('Manajemen Poliklinik');
    }

    public function loadMasterData(): void
    {
        // Load Master Dokter from SDM
        $this->masterDoctors = MasterDokter::with(['karyawan', 'spesialis'])
            ->get()
            ->map(function ($doc) {
                $karyawanName = $doc->karyawan?->full_nama ?? $doc->karyawan?->nama ?? 'Dokter tanpa nama';
                $spesialisName = $doc->spesialis?->nama ?? $doc->spesialis?->spesialisasi ?? 'Dokter Umum';
                $nip = $doc->karyawan?->nip ?? $doc->karyawan?->nik ?? 'DOC-' . $doc->id;

                return [
                    'id' => (string) $doc->id,
                    'name' => $karyawanName,
                    'specialty' => $spesialisName,
                    'code' => $nip,
                ];
            })->toArray();

        // Load Master Ruangan (Poliklinik)
        $this->masterRuangans = Ruangan::where('is_active', 1)
            ->get()
            ->map(function ($r) {
                return [
                    'id' => (string) $r->id,
                    'name' => $r->nama,
                    'code' => 'POLI-' . strtoupper(substr(preg_replace('/[^A-Za-z0-9]/', '', $r->nama), 0, 8)),
                ];
            })->toArray();
    }

    public function setTab(string $tab): void
    {
        $this->activeTab = $tab;
        $this->resetErrorBag();
        $this->successMessage = '';
        $this->errorMessage = '';
    }

    // ──── Master Ruangan Selection ──────────────────────────────────────────

    public function updatedSelectedRuanganId(string $value): void
    {
        $ruangan = collect($this->masterRuangans)->firstWhere('id', $value);
        if ($ruangan) {
            $this->poliName = $ruangan['name'];
            $this->poliCode = $ruangan['code'];
        }
    }

    // ──── Master Doctor Selection ───────────────────────────────────────────

    public function updatedSelectedMasterDoctorId(string $value): void
    {
        $doc = collect($this->masterDoctors)->firstWhere('id', $value);
        if ($doc) {
            $this->doctorName = $doc['name'];
            $this->doctorSpecialty = $doc['specialty'];
            $this->doctorCode = $doc['code'];
        }
    }

    // ──── Poli CRUD ─────────────────────────────────────────────────────────────

    public function savePoli(DmsMiddlewareClient $client): void
    {
        $this->validate([
            'poliCode' => 'required|string|max:50',
            'poliName' => 'required|string|max:255',
        ]);

        try {
            $data = [
                'code' => strtoupper($this->poliCode),
                'name' => $this->poliName,
                'ruangan_code' => $this->selectedRuanganId ?: null,
            ];

            if ($this->editingPoliId) {
                $client->updatePolyclinic($this->editingPoliId, $data);
                $this->successMessage = "Poliklinik {$this->poliName} berhasil diperbarui!";
            } else {
                $client->createPolyclinic($data);
                $this->successMessage = "Poliklinik {$this->poliName} berhasil ditambahkan dari Master Ruangan!";
            }

            $this->resetPoliForm();
            $this->polyclinics = $client->getPolyclinics();
        } catch (\Exception $e) {
            $this->errorMessage = $e->getMessage();
        }
    }

    public function editPoli(string $id): void
    {
        $poli = collect($this->polyclinics)->firstWhere('id', $id);
        if ($poli) {
            $this->editingPoliId = $poli['id'];
            $this->poliCode = $poli['code'];
            $this->poliName = $poli['name'];
            $this->selectedRuanganId = $poli['ruangan_code'] ?? '';
        }
    }

    public function deletePoli(DmsMiddlewareClient $client, string $id): void
    {
        try {
            $client->deletePolyclinic($id);
            $this->successMessage = 'Poliklinik berhasil dihapus!';
            $this->polyclinics = $client->getPolyclinics();
        } catch (\Exception $e) {
            $this->errorMessage = $e->getMessage();
        }
    }

    public function resetPoliForm(): void
    {
        $this->reset(['editingPoliId', 'selectedRuanganId', 'poliCode', 'poliName']);
    }

    // ──── Doctor CRUD ───────────────────────────────────────────────────────────

    public function loadDoctors(DmsMiddlewareClient $client): void
    {
        if (!$this->selectedPoliId) {
            $this->doctors = [];
            return;
        }

        $this->doctors = $client->getPolyclinicDoctors($this->selectedPoliId);
    }

    public function saveDoctor(DmsMiddlewareClient $client): void
    {
        $this->validate([
            'selectedPoliId' => 'required|string',
            'selectedMasterDoctorId' => 'required|string',
            'doctorSortOrder' => 'required|integer|min:0',
        ]);

        try {
            $masterDoc = collect($this->masterDoctors)->firstWhere('id', $this->selectedMasterDoctorId);
            $name = $masterDoc['name'] ?? $this->doctorName;
            $specialty = $masterDoc['specialty'] ?? $this->doctorSpecialty;
            $code = $masterDoc['code'] ?? $this->doctorCode;

            $data = [
                'name' => $name,
                'specialty' => $specialty,
                'doctor_code' => $code,
                'master_doctor_uuid' => $this->selectedMasterDoctorId,
                'is_active' => $this->doctorIsActive,
                'sort_order' => $this->doctorSortOrder,
            ];

            if ($this->doctorPhoto) {
                $data['photo'] = $this->doctorPhoto;
            }

            if ($this->editingDoctorId) {
                $client->updatePolyclinicDoctor($this->selectedPoliId, $this->editingDoctorId, $data);
                $this->successMessage = "Dokter {$name} berhasil diperbarui!";
            } else {
                $client->createPolyclinicDoctor($this->selectedPoliId, $data);
                $this->successMessage = "Dokter {$name} berhasil ditambahkan dari Master SDM!";
            }

            $this->resetDoctorForm();
            $this->polyclinics = $client->getPolyclinics();
            $this->loadDoctors($client);

            if ($this->queuePoliId) {
                $this->loadQueue($client);
            }
        } catch (\Exception $e) {
            $this->errorMessage = $e->getMessage();
        }
    }

    public function editDoctor(string $id): void
    {
        $doctor = collect($this->doctors)->firstWhere('id', $id);
        if ($doctor) {
            $this->editingDoctorId = $doctor['id'];
            $this->doctorName = $doctor['name'];
            $this->doctorSpecialty = $doctor['specialty'] ?? '';
            $this->doctorSortOrder = $doctor['sort_order'] ?? 0;
            $this->doctorIsActive = $doctor['is_active'] ?? true;
            $this->selectedMasterDoctorId = $doctor['master_doctor_uuid'] ?? '';
        }
    }

    public function toggleDoctorActive(DmsMiddlewareClient $client, string $id): void
    {
        $doctor = collect($this->doctors)->firstWhere('id', $id);
        if ($doctor) {
            try {
                $client->updatePolyclinicDoctor($this->selectedPoliId, $id, [
                    'is_active' => !$doctor['is_active'],
                ]);
                $this->polyclinics = $client->getPolyclinics();
                $this->loadDoctors($client);

                if ($this->queuePoliId) {
                    $this->loadQueue($client);
                }
            } catch (\Exception $e) {
                $this->errorMessage = $e->getMessage();
            }
        }
    }

    public function deleteDoctor(DmsMiddlewareClient $client, string $id): void
    {
        try {
            $client->deletePolyclinicDoctor($this->selectedPoliId, $id);
            $this->successMessage = 'Dokter berhasil dihapus!';
            $this->polyclinics = $client->getPolyclinics();
            $this->loadDoctors($client);

            if ($this->queuePoliId) {
                $this->loadQueue($client);
            }
        } catch (\Exception $e) {
            $this->errorMessage = $e->getMessage();
        }
    }

    public function resetDoctorForm(): void
    {
        $this->reset(['editingDoctorId', 'selectedMasterDoctorId', 'doctorName', 'doctorPhoto', 'doctorSpecialty', 'doctorCode', 'doctorSortOrder']);
        $this->doctorIsActive = true;
    }

    // ──── Queue Management ──────────────────────────────────────────────────────

    public function updatedQueuePoliId(DmsMiddlewareClient $client): void
    {
        $this->queueDoctorId = '';
        $this->loadQueue($client);
    }

    public function loadQueue(DmsMiddlewareClient $client): void
    {
        if (!$this->queuePoliId) {
            $this->queueItems = [];
            $this->queueAvailableDoctors = [];
            return;
        }

        // Directly fetch fresh list of doctors for the selected polyclinic
        $this->queueAvailableDoctors = $client->getPolyclinicDoctors($this->queuePoliId);
        
        $doctorId = $this->queueDoctorId ?: null;
        $this->queueItems = $client->getPolyclinicQueue($this->queuePoliId, $doctorId);
    }

    public function addPatient(DmsMiddlewareClient $client): void
    {
        $this->validate([
            'queuePoliId' => 'required|string',
            'queueDoctorId' => 'required|string',
            'patientName' => 'required|string|max:255',
        ]);

        try {
            $client->addPatientToQueue($this->queuePoliId, [
                'doctor_id' => $this->queueDoctorId,
                'patient_name' => $this->patientName,
            ]);
            $this->successMessage = "Pasien {$this->patientName} berhasil ditambahkan ke antrian!";
            $this->patientName = '';
            $this->loadQueue($client);
        } catch (\Exception $e) {
            $this->errorMessage = $e->getMessage();
        }
    }

    public function changeQueueStatus(DmsMiddlewareClient $client, string $id, string $newStatus): void
    {
        $item = collect($this->queueItems)->firstWhere('id', $id);
        if (!$item) return;

        $currentStatus = $item['status'];
        if ($currentStatus === $newStatus) return;

        try {
            $this->successMessage = '';
            $this->errorMessage = '';

            $client->updateQueueStatus($this->queuePoliId, $id, $newStatus);
            $statusLabels = [
                'menunggu' => 'menunggu',
                'dilayani' => 'sedang dilayani',
                'selesai' => 'selesai dilayani',
                'terlewat' => 'dilewati',
            ];
            $label = $statusLabels[$newStatus] ?? $newStatus;
            $this->successMessage = "Status pasien berhasil diubah menjadi '{$label}'!";
            $this->loadQueue($client);
        } catch (\Exception $e) {
            $this->errorMessage = $e->getMessage();
        }
    }

    public function deleteQueueItem(DmsMiddlewareClient $client, string $id): void
    {
        try {
            $client->deleteQueueEntry($this->queuePoliId, $id);
            $this->successMessage = 'Antrian berhasil dihapus!';
            $this->loadQueue($client);
        } catch (\Exception $e) {
            $this->errorMessage = $e->getMessage();
        }
    }

    /**
     * Manual Sync Master Data on-demand.
     */
    public function syncMasterData(DmsMiddlewareClient $client): void
    {
        try {
            $this->loadMasterData();
            $this->polyclinics = $client->getPolyclinics();
            if ($this->selectedPoliId) {
                $this->loadDoctors($client);
            }
            if ($this->queuePoliId) {
                $this->loadQueue($client);
            }
            $this->successMessage = 'Master Data SDM Dokter & Ruangan Poliklinik berhasil disinkronisasi!';
        } catch (\Exception $e) {
            $this->errorMessage = 'Gagal sinkronisasi: ' . $e->getMessage();
        }
    }

    #[On('poli-data-changed')]
    public function refreshData(DmsMiddlewareClient $client): void
    {
        $this->polyclinics = $client->getPolyclinics();
        if ($this->selectedPoliId) {
            $this->loadDoctors($client);
        }
        if ($this->queuePoliId) {
            $this->loadQueue($client);
        }
    }
}
