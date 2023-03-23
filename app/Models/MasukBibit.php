<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasukBibit extends Model
{
    protected $table = "masuk_bibits";
    protected $primaryKey = "id";
    protected $fillable =
    [
        "id",
        "masuk",
        "created_at",
        "updated_at"
    ];

    public function stoks()
    {
        return $this->hasMany(Stok::class, "masuk_bibit_id", "id");
    }
}
