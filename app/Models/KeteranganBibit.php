<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KeteranganBibit extends Model
{
    protected $table = "keterangan_bibits";
    protected $primaryKey = "id";
    protected $fillable =
    [
        "id",
        "nama",
        "created_at",
        "updated_at"
    ];

    public function stoks()
    {
        return $this->hasMany(Stok::class, "keterangan_bibit_id", "id");
    }
}
