<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StokDetail extends Model
{
    protected $table = "stok_details";
    protected $primaryKey = "id";
    protected $fillable =
    [
        "id",
        "stok_id",
        "keterangan_bibit_id",
        "kuantitas",
        "created_at",
        "updated_at"
    ];

    public function stoks()
    {
        return $this->belongsTo(Stok::class, "stok_id", "id");
    }

    public function keterangan_bibits()
    {
        return $this->belongsTo(KeteranganBibit::class, "keterangan_bibit_id", "id");
    }
}
