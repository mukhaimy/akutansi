<?php

namespace App\Imports;

use App\Models\Transaksi;
use Maatwebsite\Excel\Concerns\ToModel;

class TransaksiImport implements ToModel
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        $rows[] = $row[1];

        $transaksiCek = Transaksi::where('nama', $rows[0])->get();
        if ($transaksiCek->count() == 0) {
            if (in_array(null, $rows, true) == false) {
                return new Transaksi([
                    'nama' => $rows[0],
                ]);
            }
        }
    }
}
