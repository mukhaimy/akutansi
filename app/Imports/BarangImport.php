<?php

namespace App\Imports;

use App\Models\Barang;
use Maatwebsite\Excel\Concerns\ToModel;

class BarangImport implements ToModel
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
        $rows[] = $row[3];

        $barangCek = Barang::where('nama', $rows[0])->get();
        $satuanCek = Barang::where('satuan_id', $rows[1])->get();
        $hargaCek = Barang::where('harga', $rows[2])->get();

        if ($barangCek->count() != 0 && $satuanCek->count() != 0 && $hargaCek->count() != 0) {
            //
        } else {
            if (in_array(null, $rows, true) == false) {
                return new Barang([
                    'satuan_id' => $rows[1],
                    'nama' => $rows[0],
                    'harga' => $rows[2],
                ]);
            }
        }
    }
}
