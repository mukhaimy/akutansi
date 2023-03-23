<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inventory extends Model
{
    protected $table = "inventories";
    protected $primaryKey = "id";
    protected $fillable =
    [
        "id",
        "lokasi_id",
        "nama",
        "jenis_id",
        "satuan_id",
        "kuantitas",
        "keterangan",
        "created_at",
        "updated_at"
    ];

    public function lokasies()
    {
        return $this->belongsTo(Lokasi::class, "lokasi_id", "id");
    }

    public function jenises()
    {
        return $this->belongsTo(Jenis::class, "jenis_id", "id");
    }

    public function satuans()
    {
        return $this->belongsTo(Satuan::class, "satuan_id", "id");
    }
    
}
