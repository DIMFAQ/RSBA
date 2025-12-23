 <div class="h-48 w-full p-4">
     <div class="grid w-full grid-cols-3 border-4 border-black">
         {{-- Logo RS --}}
         <div class="flex items-center justify-center p-2">
             {{-- Barcode label --}}
             <img src="data:image/png;base64,{{ $this->generateBarcode }}" alt="Barcode Label" class="h-full w-auto">
         </div>

         {{-- Keterangan --}}
         <div class="col-span-2 flex flex-col items-start space-y-2 border-l-4 border-black p-2">
             {{-- Header --}}
             <div class="flex w-full items-center space-x-2 border-b-2 border-double border-black">
                 <img src="{{ asset('storage/' . $rs->logo) }}" class="h-14 w-auto" />
                 <div class="flex flex-col">
                     <h1 class="font-bold uppercase">{{ $rs->nama }}</h1>
                     <span class="text-xs text-gray-600">{{ $rs->alamat }}</span>
                 </div>
             </div>

             {{-- Data Assets --}}
             <div class="flex w-full flex-col pl-2">
                 <span class="font-semibold">{{ $assetBarang->kode }}</span>
                 <span class="flex flex-col text-sm text-gray-600">
                     <span>{{ $assetBarang->barang->nama }}</span>
                     <span>{{ $assetBarang->ruangan->nama }}</span>
                     <span>{{ Carbon\Carbon::parse($assetBarang->tanggal_catat)->locale('ID')->translatedFormat('d M Y') }}</span>
                 </span>
             </div>
         </div>

     </div>
 </div>
