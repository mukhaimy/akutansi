<?php

namespace App\Imports;

use App\Models\Tahun;
use Maatwebsite\Excel\Concerns\ToModel;

class TahunImport implements ToModel
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        $rows[] = $row[1];

        $tahunCek = Tahun::where('nama', $rows[0])->get();
        if ($tahunCek->count() == 0) {
            if (in_array(null, $rows, true) == false) {
                return new Tahun([
                    'nama' => $rows[0],
                ]);
            }
        }
    }
}
