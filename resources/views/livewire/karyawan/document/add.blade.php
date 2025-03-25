<div>
    <form wire:submit.prevent='submit' class="flex flex-col gap-2" autocomplete="off">
        <div class="flex flex-col gap-2">
            <x-ts:upload wire:model='fileTmp' />


            <x-ts:input placeholder="Nama Document" wire:model.defer='nama' />

            <x-ts:select.styled placeholder="Nama Document" wire:model.defer='jenis' :options="$jenisDocsOpt" select="label:label|value:value" />
        </div>


        <div class="ml-auto flex justify-end gap-2">
            <x-ts:button outline x-on:click="$dispatch('close-modal',{id:'add-document-karyawan'})">Tutup</x-ts:button>
            <x-ts:button type="submit" icon="tabler.checks" loading="submit">Simpan</x-ts:button>
        </div>
    </form>
</div>
