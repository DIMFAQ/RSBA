<div>
    @if ($this->getCertificate)
        <div class="flex flex-col gap-2">
            <div class="bg-{{ $this->getCertificate['is_active'] ? 'green' : 'red' }}-100 w-full rounded-md p-4 lg:w-1/4">
                <span class="flex flex-row items-center font-semibold text-green-500">
                    <x-ts:icon name="tabler.certificate" class="w-10" />
                    Certifacate Digital {{ $this->getCertificate['is_active'] ? 'Valid' : 'Kadaluarsa' }}
                </span>
                <div class="mt-2 flex flex-col rounded-md border border-green-400 p-4">
                    <span class="font-semibold text-green-500">Issuer : </span>
                    @foreach ($this->getCertificate['cert_info']->subject as $subject)
                        <span>{{ $subject }}</span>
                    @endforeach
                    <span>
                        <span class="text-red-500"> Valid Until : </span>{{ $this->getCertificate['expired_at'] }}
                    </span>
                </div>
            </div>


            @if (!$this->getCertificate['is_active'])
                <div class="w-[50px]">
                    <x-ts:button sm icon="tabler.refresh" x-on:click="$dispatch('open-modal',{id:'modal-regenerate-certificate'})">Regenerate</x-ts:button>

                    {{-- modal regenerate certificate p12 --}}
                    <x-filament::modal id="modal-regenerate-certificate">
                        <livewire:Profile.SignatureCerts.Regenerate :$users :key="Str::random()" />
                    </x-filament::modal>

                </div>
            @endif
        </div>
    @else
        <div class="flex flex-row items-center gap-2">
            <x-ts:icon name="tabler.info-circle" color='orange' class="h-6" />
            <span>Anda belum memiliki certificate tanda tangan.</span>
        </div>
        <x-ts:button xs icon="tabler.plus" x-on:click="$dispatch('open-modal',{id:'modal-new-certificate'})">Buat Certificate</x-ts:button>

        {{-- modal tambah certificate baru --}}
        <x-filament::modal id="modal-new-certificate">
            <livewire:Profile.SignatureCerts.Add :$users :key="Str::random()" />
        </x-filament::modal>
    @endif

    <x-ts:modal title="Password" wire="modalPassw" x-on:open="$focusOn('pkcs12_password')" center persistent blur>
        <form wire:submit.prevent='parseCertificate' class="flex flex-col gap-2">
            <x-ts:password id="pkcs12_password" wire:model.defer='pkcs12_password' placeholder="Input passsword anda." />

            <x-ts:button sm type="submit" icon="tabler.corner-down-left" loading="parseCertificate">Submit</x-ts:button>
        </form>
    </x-ts:modal>

</div>
