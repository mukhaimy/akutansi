<?php

namespace App\Imports;

use App\Models\KeteranganBibit;
use Maatwebsite\Excel\Concerns\ToModel;

class KeteranganBibitImport implements ToModel
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        $rows[] = $row[1];
        
        $keteranganBibitCek = KeteranganBibit::where('nama', $rows[0])->get();
        if ($keteranganBibitCek->count() == 0) {
            if (in_array(null, $rows, true) == false) {
                return new KeteranganBibit([
                    'nama' => $rows[0],
                ]);
            }
        }
    }
}
