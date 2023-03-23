<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lokasi extends Model
{
    protected $table = "lokasies";
    protected $primaryKey = "id";
    protected $fillable =
    [
        "id",
        "nama",
        "created_at",
        "updated_at"
    ];

    public function notas()
    {
        return $this->hasMany(Nota::class, "lokasi_id", "id");
    }

    public function stoks()
    {
        return $this->hasMany(Stok::class, "lokasi_id", "id");
    }

    public function inventories()
    {
        return $this->hasMany(Inventory::class, "lokasi_id", "id");
    }
}
