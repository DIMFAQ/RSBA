<?php

namespace App\Models\Assets;

use App\Models\Ruangan;
use App\Models\Master\Barang;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AssetBarang extends Model
{
    protected $table = 'asset_barang';
    protected $guarded = [];


    function barang(): BelongsTo
    {
        return $this->belongsTo(Barang::class, 'barang_id', 'id');
    }

    function ruangan(): BelongsTo
    {
        return $this->belongsTo(Ruangan::class, 'ruangan_id', 'id');
    }

    function parent(): BelongsTo
    {
        return $this->belongsTo(AssetBarang::class, 'main_asset_id', 'id');
    }

    function child(): HasMany
    {
        return $this->hasMany(AssetBarang::class, 'main_asset_id', 'id');
    }

    function logs(): HasMany
    {
        return $this->hasMany(AssetLogs::class, 'asset_id', 'id');
    }
}
