<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tahun extends Model
{
    protected $table = "tahuns";
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
        return $this->hasMany(Nota::class, "tahun_id", "id");
    }
}
