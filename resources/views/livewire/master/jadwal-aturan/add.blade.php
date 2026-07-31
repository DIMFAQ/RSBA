<div>
    <form wire:submit.prevent="submit" class="flex flex-col gap-2" autocomplete="off">
        @csrf

        <div class="flex w-full flex-col gap-2">
            <div class="p-2.5 bg-emerald-50 border border-emerald-200 rounded-lg text-xs text-emerald-800 flex items-center gap-2">
                🏛️ <span>Tingkat Aturan: <strong>Aturan Umum RSBA</strong> (Berlaku untuk seluruh pegawai)</span>
            </div>
            
            <x-ts:select.styled wire:model.defer="kode" label="Kode Aturan" placeholder="Pilih Aturan" :options="$kodeOptions" select="label:label|value:value" searchable />

            <x-ts:input wire:model.defer="nilai" label="Nilai" placeholder="Contoh: 14 (untuk hari) atau true/false" />

            <div class="flex flex-col gap-3 mt-2">
                <x-ts:toggle wire:model.defer="aktif" label="Aktif" />
            </div>
        </div>

        <div class="flex justify-end gap-2 pt-4">
            <x-ts:button md outline @click="$dispatch('close-modal',{id:'new-jadwal-aturan'})">Tutup</x-ts:button>
            <x-ts:button loading="submit" md type="submit">Simpan</x-ts:button>
        </div>
    </form>
</div>
