<div>
    <form wire:submit.prevent='submit' class="flex flex-col gap-2" autocomplete="off">
        <div class="flex flex-col gap-2">
            <x-ts:select.styled wire:model.defer='tingkat' :options="$tingkatPendidikanOpts" select="label:label|value:value" />

            <x-ts:input wire:model.defer='nama' placeholder="Nama Pendidikan" />

            <x-ts:date wire:model.defer='tahun_lulus' placeholder="Tahun Lulus" />

            <x-ts:input wire:model.defer='instansi' placeholder="Nama Sekolah / Institusi " />


            <div class="flex flex-row gap-2">
                <div class="w-1/2">
                    <x-ts:input wire:model='gelar' placeholder="Gelar" />
                </div>
                <div>
                    <x-ts:radio wire:model="setting_gelar" id="preffix" value="preffix" label="Depan" />
                </div>
                <div>
                    <x-ts:radio wire:model='setting_gelar' id="suffix" value="suffix" label="Belakang" />
                </div>
            </div>
        </div>


        <div class="ml-auto mt-4 flex justify-end gap-2">
            <x-ts:button outline x-on:click="$dispatch('close-modal',{id:'new-pendidikan'})">Tutup</x-ts:button>
            <x-ts:button type="submit" icon="tabler.checks" loading="submit">Simpan</x-ts:button>
        </div>
    </form>
</div>
