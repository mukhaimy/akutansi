<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stok extends Model
{
    protected $table = "stoks";
    protected $primaryKey = "id";
    protected $fillable =
    [
        "id",
        "lokasi_id",
        "varietas_id",
        "masuk_bibit_id",
        "created_at",
        "updated_at"
    ];

    public function lokasies()
    {
        return $this->belongsTo(Lokasi::class, "lokasi_id", "id");
    }

    public function varietases()
    {
        return $this->belongsTo(Varietas::class, "varietas_id", "id");
    }

    public function masukBibits()
    {
        return $this->belongsTo(MasukBibit::class, "masuk_bibit_id", "id");
    }

    public function stok_details()
    {
        return $this->hasMany(StokDetail::class, "stok_id", "id");
    }
}
