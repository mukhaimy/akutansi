<?php

namespace App\Imports;

use App\Models\Lokasi;
use Maatwebsite\Excel\Concerns\ToModel;

class LokasiImport implements ToModel
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        $rows[] = $row[1];

        $lokasiCek = Lokasi::where('nama', $rows[0])->get();
        if ($lokasiCek->count() == 0) {
            if (in_array(null, $rows, true) == false) {
                return new Lokasi([
                    'nama' => $rows[0],
                ]);
            }
        }
    }
}
