<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bulan extends Model
{
    protected $table = "bulans";
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
        return $this->hasMany(Nota::class, "bulan_id", "id");
    }
}
