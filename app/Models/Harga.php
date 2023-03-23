<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Harga extends Model
{
    protected $table = "hargas";
    protected $primaryKey = "id";
    protected $fillable =
    [
        "id",
        "harga",
        "created_at",
        "updated_at"
    ];

    public function details()
    {
        return $this->hasMany(Detail::class, "harga_id", "id");
    }
}
