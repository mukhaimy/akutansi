<?php

namespace App\Imports;

use App\Models\Barang;
use App\Models\Inventory;
use App\Models\Jenis;
use App\Models\Lokasi;
use App\Models\Satuan;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\DB;

class InventoryImport implements ToModel, WithHeadingRow
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        $lokasiNama = $row['lokasi'];
        $nama = $row['nama'];
        $jenisNama = $row['jenis'];
        $satuanNama = $row['satuan'];
        $kuantitas = $row['kuantitas'];
        $keterangan = $row['keterangan'];

        $lokasiFind = Lokasi::where('nama', $lokasiNama)->get();
        if ($lokasiFind->count() == 0) {
            $lokasiLatest = Lokasi::create([
                'nama' => $lokasiNama,
            ]);
            $lokasiId = $lokasiLatest->id;
        } else {
            $lokasiId = $lokasiFind[0]->id;
        }

        $jenisFind = Jenis::where('nama', $jenisNama)->get();
        if ($jenisFind->count() == 0) {
            $jenisLatest = Jenis::create([
                'nama' => $jenisNama,
            ]);
            $jenisId = $jenisLatest->id;
        } else {
            $jenisId = $jenisFind[0]->id;
        }

        $satuanFind = Satuan::where('nama', $satuanNama)->get();
        if ($satuanFind->count() == 0) {
            $satuanLatest = Satuan::create([
                'nama' => $satuanNama,
            ]);
            $satuanId = $satuanLatest->id;
        } else {
            $satuanId = $satuanFind[0]->id;
        }

        $inventoryFind = Inventory::where('lokasi_id', $lokasiId)
            ->where('jenis_id', $jenisId)
            ->where('satuan_id', $satuanId)
            ->where('nama', $nama)
            ->get();
        if ($inventoryFind->count() == 0) {
            return new Inventory([
                'lokasi_id' => $lokasiId,
                'nama' => $nama,
                'jenis_id' => $jenisId,
                'satuan_id' => $satuanId,
                'kuantitas' => $kuantitas,
                'keterangan' => $keterangan,
            ]);
        }
    }
}
