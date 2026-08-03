<img {{ $attributes }} src="{{ asset($rs?->logo ? 'storage/' . $rs->logo : 'img/logo.png') }}" alt="logo-{{ $rs?->singkatan ?? 'RSBA' }}" />
