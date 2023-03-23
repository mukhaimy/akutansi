<?php

namespace App\Imports;

use App\Models\Varietas;
use Maatwebsite\Excel\Concerns\ToModel;

class VarietasImport implements ToModel
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        $rows[] = $row[1];
        $rows[] = $row[2];

        $varietasCek = Varietas::where('nama', $rows[0])->get();
        $penyediaCek = Varietas::where('penyedia', $rows[1])->get();

        if ($varietasCek->count() == 0 && $penyediaCek->count() == 0) {
            if (in_array(null, $rows, true) == false) {
                return new Varietas([
                    'nama' => $rows[0],
                    'penyedia' => $rows[1],
                ]);
            }
        } else if ($varietasCek->count() != 0 && $penyediaCek->count() == 0) {
            if (in_array(null, $rows, true) == false) {
                return new Varietas([
                    'nama' => $rows[0],
                    'penyedia' => $rows[1],
                ]);
            }
        } else if ($varietasCek->count() == 0 && $penyediaCek->count() != 0) {
            if (in_array(null, $rows, true) == false) {
                return new Varietas([
                    'nama' => $rows[0],
                    'penyedia' => $rows[1],
                ]);
            }
        } 
    }
}
