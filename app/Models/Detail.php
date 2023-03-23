<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Detail extends Model
{
    protected $table = "details";
    protected $primaryKey = "id";
    protected $fillable =
    [
        "id",
        "nota_id",
        "barang_id",
        "satuan_id",
        "harga_id",
        "transaksi_id",
        "kuantitas",
        "keterangan",
        "created_at",
        "updated_at"
    ];

    public function notas()
    {
        return $this->belongsTo(Nota::class, "nota_id", "id");
    }

    public function barangs()
    {
        return $this->belongsTo(Barang::class, "barang_id", "id");
    }

    public function satuans()
    {
        return $this->belongsTo(Satuan::class, "satuan_id", "id");
    }

    public function hargas()
    {
        return $this->belongsTo(Harga::class, "harga_id", "id");
    }

    public function Transaksies()
    {
        return $this->belongsTo(Transaksi::class, "transaksi_id", "id");
    }
}
