<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Nota extends Model
{
    protected $table = "notas";
    protected $primaryKey = "id";
    protected $fillable =
    [
        "id",
        "tahun_id",
        "bulan_id",
        "hari_id",
        "lokasi_id",
        "nama",
        "keterangan",
        "created_at",
        "updated_at"
    ];

    public function tahuns()
    {
        return $this->belongsTo(Tahun::class, "tahun_id", "id");
    }

    public function bulans()
    {
        return $this->belongsTo(Bulan::class, "bulan_id", "id");
    }

    public function haries()
    {
        return $this->belongsTo(Hari::class, "hari_id", "id");
    }

    public function lokasies()
    {
        return $this->belongsTo(Lokasi::class, "lokasi_id", "id");
    }

    public function details()
    {
        return $this->hasMany(Detail::class, "nota_id", "id");
    }
}
