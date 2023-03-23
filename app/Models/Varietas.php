<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Varietas extends Model
{
    protected $table = "varietases";
    protected $primaryKey = "id";
    protected $fillable =
    [
        "id",
        "nama",
        "penyedia",
        "created_at",
        "updated_at"
    ];

    public function stoks()
    {
        return $this->hasMany(Stok::class, "varietas_id", "id");
    }
}
