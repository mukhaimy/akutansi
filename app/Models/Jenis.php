<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jenis extends Model
{
    protected $table = "jenises";
    protected $primaryKey = "id";
    protected $fillable =
    [
        "id",
        "nama",
        "created_at",
        "updated_at"
    ];

    public function inventories()
    {
        return $this->hasMany(Inventory::class, "inventory_id", "id");
    }
}
