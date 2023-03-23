<?php

namespace App\Imports;

use App\Models\MasukBibit;
use Maatwebsite\Excel\Concerns\ToModel;
use Illuminate\Support\Str;

class MasukBibitImport implements ToModel
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        
        $rows[] = Str::replace('T', ' ', $row[1]);

        $masukBibitCek = MasukBibit::where('masuk', $rows[0])->get();        
        if ($masukBibitCek->count() == 0) {
            if (in_array(null, $rows, true) == false) {                
                return new MasukBibit([
                    'masuk' => $rows[0],
                ]);
            }
        }
    }
}
