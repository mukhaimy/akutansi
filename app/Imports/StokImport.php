<?php

namespace App\Imports;

use App\Models\Stok;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\DB;
use App\Models\Tahun;
use App\Models\Bulan;
use App\Models\Hari;
use App\Models\KeteranganBibit;
use App\Models\Lokasi;
use App\Models\MasukBibit;
use App\Models\StokDetail;
use App\Models\Varietas;
use App\Models\Transaksi;
use DateTime;

class StokImport implements ToModel, WithHeadingRow
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        /**
         * j => day of the month without leading zeros
         * d => day of the month, 2 digits with leading zeros
         * n => numeric representation of a month, without leading zeros
         * m => numeric representation of a month, with leading zeros
         * Y => a full numeric representation of a year, at least 4 digits, with for years BCE.
         * H => 24-hour format of an hour with leading zeros
         * i => minutes with leading zeros
         *       
         */

        $tanggalUTC = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row['tanggal']);
        $tanggal = $tanggalUTC->format('j-n-Y');
        $jamUTC = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row['jam']);
        $jam = $jamUTC->format('H:i');

        $tanggalMasuk = $tanggalUTC->format('Y-n-j');
        $masukUTC = new DateTime($tanggalMasuk . $jam);
        $masuk = $masukUTC->format('Y-m-d H:i');

        $tahun = substr($tanggal, 4, 7);
        $bulan = substr($tanggal, 2, 3);
        $hari = substr($tanggal, 0, 1);
        $lokasiNama = $row['lokasi'];
        $varietasNama = $row['varietas'];
        $bagusQuantity = $row['bagus'];
        $karantinaQuantity = $row['karantina'];
        $matiQuantity = $row['mati'];
        $lainQuantity = $row['lain'];

        if (
            $row['tanggal'] != null &&
            $row['jam'] != null &&
            $row['lokasi'] != null &&
            $row['varietas'] != null &&
            $row['bagus'] != null &&
            $row['karantina'] != null &&
            $row['mati'] != null &&
            $row['lain'] != null
        ) {
            $lokasiFind = Lokasi::where('nama', $lokasiNama)->get();
            if ($lokasiFind->count() == 0) {
                $lokasiLatest = Lokasi::create([
                    'nama' => $lokasiNama,
                ]);
                $lokasiId = $lokasiLatest->id;
            } else {
                $lokasiId = $lokasiFind[0]->id;
            }

            $varietasFind = Varietas::where('nama', $varietasNama)->get();
            if ($varietasFind->count() == 0) {
                $varietasLatest = Varietas::create([
                    'nama' => $varietasNama,
                ]);
                $varietasId = $varietasLatest->id;
            } else {
                $varietasId = $varietasFind[0]->id;
            }

            $masukBibitFind = MasukBibit::where('masuk', $masuk)->get();
            if ($masukBibitFind->count() == 0) {
                $masukBibitLatest = MasukBibit::create([
                    'masuk' => $masuk,
                ]);
                $masukBibitId = $masukBibitLatest->id;
            } else {
                $masukBibitId = $masukBibitFind[0]->id;
            }

            $stokFind = Stok::where('lokasi_id', $lokasiId)
                ->where('varietas_id', $varietasId)
                ->where('masuk_bibit_id', $masukBibitId)
                ->get();
            if ($stokFind->count() == 0) {
                $stokLatest = Stok::create([
                    'lokasi_id' => $lokasiId,
                    'varietas_id' => $varietasId,
                    'masuk_bibit_id' => $masukBibitId,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
                $stokId = $stokLatest->id;
            } else {
                $stokId = $stokFind[0]->id;
            }

            $ketBibits = KeteranganBibit::get();
            $ketBibits1 = $ketBibits->slice(0, -1);

            $stokDetailqueues = array();
            foreach ($ketBibits1 as $value) {
                $stokDetailFind = StokDetail::where('stok_id', $stokId)
                    ->where('keterangan_bibit_id', $value->id)
                    ->where('kuantitas', $row[strtolower($value->nama)])
                    ->get();
                if ($stokDetailFind->count() == 0) {
                    $stokDetailqueues[] = StokDetail::create([
                        'stok_id' => $stokId,
                        'keterangan_bibit_id' => $value->id,
                        'kuantitas' => $row[strtolower($value->nama)],
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }

            return new StokDetail([
                'stok_id' => $stokId,
                'keterangan_bibit_id' => $ketBibits->last()->id,
                'kuantitas' => $row[strtolower($ketBibits->last()->nama)],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
