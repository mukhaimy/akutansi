<?php

namespace App\Imports;

use App\Models\Satuan;
use Maatwebsite\Excel\Concerns\ToModel;

class SatuanImport implements ToModel
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        $rows[] = $row[1];

        $satuanCek = Satuan::where('nama', $rows[0])->get();
        if ($satuanCek->count() == 0) {
            if (in_array(null, $rows, true) == false) {
                return new Satuan([
                    'nama' => $rows[0],
                ]);
            }
        }
    }
}
