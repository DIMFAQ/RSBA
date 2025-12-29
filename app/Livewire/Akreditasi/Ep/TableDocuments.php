<?php

namespace App\Livewire\Akreditasi\Ep;

use Livewire\Component;
use Filament\Tables\Table;
use Livewire\Attributes\Lazy;
use Livewire\WithFileUploads;
use Livewire\Attributes\Locked;
use Illuminate\Support\Facades\DB;
use Filament\Tables\Actions\Action;
use App\Models\Akreditasi\AkreFiles;
use TallStackUi\Traits\Interactions;
use App\Models\Akreditasi\AkreElement;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Contracts\HasTable;
use Illuminate\Support\Facades\Storage;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Tables\Concerns\InteractsWithTable;

class TableDocuments extends Component implements HasTable, HasForms
{
    use WithFileUploads;
    use Interactions;
    use InteractsWithTable, InteractsWithForms;

    // #[Locked]
    public ?int $element_id;

    public ?int $selectedDocId;

    public $pdf_file;
    public ?string $nama;

    public function rules(): array
    {
        return [
            'pdf_file' => "required|file|mimes:pdf|max:10240", //10MB
            'nama' => 'required|string|max:255'
        ];
    }

    public function messages(): array
    {
        return [
            'pdf_file.required' => 'File PDF wajib diunggah',
            'pdf_file.mimes' => 'File harus berformat PDF',
            'pdf_file.max' => 'Ukuran file maksimal 10MB',
            'nama.required' => 'Nama file wajib diisi.'
        ];
    }

    public function mount($elementId)
    {
        $this->element_id = $elementId;
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(
                AkreFiles::where('element_id', $this->element_id)
            )
            ->columns([
                TextColumn::make('nama')
                    ->label('Nama File')
                    ->searchable()
                    ->action(fn($record) => $this->viewDocument($record->getKey())),

                TextColumn::make('user_upload')
                    ->label('User Upload'),

                TextColumn::make('created_at')
                    ->label('Tanggal Upload')

            ])
            ->actions([
                Action::make('delete')
                    ->iconButton()
                    ->icon('tabler-trash')
                    ->color('danger')
            ])

        ;
    }

    public function viewDocument($id)
    {
        $this->selectedDocId = $id;
        $this->dispatch('open-modal', id: "modal-document-view");
    }

    public function submit(): void
    {
        $this->validate();

        try {
            DB::beginTransaction();
            // Cek apakah file valid
            if (!$this->pdf_file || !$this->pdf_file->isValid()) {
                $this->addError('pdf_file', 'File tidak valid atau gagal diupload');
                return;
            }

            $filename = $this->pdf_file->getClientOriginalName();

            // generate folder
            $strukturs = AkreElement::join('akre_bab_elements', 'akre_elements.akre_bab_id', '=', 'akre_bab_elements.id')
                ->join('akre_chapter', 'akre_bab_elements.chapter_id', '=', 'akre_chapter.id')
                ->join('akre_kegiatan', 'akre_chapter.kegiatan_id', '=', 'akre_kegiatan.id')
                ->where('akre_elements.id', $this->element_id)
                ->select('akre_kegiatan.folder_path', 'akre_chapter.singkatan', 'akre_bab_elements.nama', 'akre_elements.nomor')
                ->first();

            $folder = "{$strukturs->folder_path}/{$strukturs->singkatan}/{$strukturs->nama}/{$strukturs->nomor}";

            // check folder 
            if (!Storage::disk('public')->exists($folder)) {
                Storage::disk('public')->makeDirectory($folder);
            }

            // store file ke folder 
            $path = $this->pdf_file->storeAs($folder, $filename, 'public');

            // simpan data
            AkreFiles::create([
                'element_id' => $this->element_id,
                'nama' => $this->nama,
                'filename' => $filename,
                'path' => $path,
                'mime_type' => $this->pdf_file->getMimeType(),
                'uploaded_by' => auth()->user()->id,
            ]);
            DB::commit();
            $this->toast()
                ->success('Berhasil', 'File berhasil ditambahkan.')
                ->send();
        } catch (\Exception $e) {
            DB::rollback();

            $this->toast()
                ->error('Tidak Berhasil', $e->getMessage())
                ->send();
        }
    }

    public function render()
    {
        return view('livewire.akreditasi.ep.table-documents');
    }
}
