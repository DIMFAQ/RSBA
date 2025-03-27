<?php

namespace App\Models\Gudang;

use App\Models\Master\Supplier;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Pembelian extends Model
{
    protected $table = 'um_pembelian';
    protected $guarded = [];

    protected function jenis(): Attribute
    {
        return Attribute::make(
            get: fn($value) => match ($value) {
                'langsung' => 'Pembelian Langsung',
                'pre_order' => 'Pre Order',
                default => ucfirst(str_replace('_', ' ', $value)),
            }
        );
    }

    function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class, 'supplier_id', 'id');
    }

    function details(): HasMany
    {
        return $this->hasMany(PembelianDetail::class, 'pembelian_id', 'id');
    }

    function pembelians(): HasMany
    {
        return $this->hasMany(PembelianDetail::class, 'pembelian_id', 'id');
    }
}
