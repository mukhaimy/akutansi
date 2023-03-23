<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Obat extends Model
{
    protected $table = "obats";
    protected $primaryKey = "id";
    protected $fillable =
    [
        "id",
        "tahun_id",
        "bulan_id",
        "hari_id",
        "lokasi_id",
        "barang_id",
        "satuan_id",
        "kuantitas",
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

    public function barangs()
    {
        return $this->belongsTo(Barang::class, "barang_id", "id");
    }

    public function satuans()
    {
        return $this->belongsTo(Satuan::class, "satuan_id", "id");
    }
}
