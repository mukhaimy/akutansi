<?php

namespace App\Imports;

use App\Models\Nota;
use App\Models\Detail;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\DB;
use App\Models\Tahun;
use App\Models\Bulan;
use App\Models\Hari;
use App\Models\Lokasi;
use App\Models\Satuan;
use App\Models\Barang;
use App\Models\Harga;
use App\Models\Transaksi;

class NotaImport implements ToModel, WithHeadingRow
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        $tanggalUTC = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row['tanggal']);
        $tanggal = $tanggalUTC->format('j/n/Y');
        $tahun = substr($tanggal, 4, 7);
        $bulan = substr($tanggal, 2, 3);
        $hari = substr($tanggal, 0, 1);
        $lokasiNama = $row['lokasi'];
        $notaNama = $row['nota'];
        $barangNama = $row['barang'];
        $satuanNama = $row['satuan'];
        $harga = $row['harga'];
        $transaksiNama = $row['transaksi'];
        $kuantitas = $row['kuantitas'];
        $keterangan = $row['keterangan'];

        if (
            $row['tanggal'] != null &&
            $row['lokasi'] != null &&
            $row['nota'] != null &&
            $row['barang'] != null &&
            $row['satuan'] != null &&
            $row['harga'] != null &&
            $row['transaksi'] != null &&
            $row['kuantitas'] != null &&
            $row['keterangan'] != null
        ) {
            $tahunFind = Tahun::where('nama', $tahun)->get();
            $tahunId = $tahunFind[0]->id;
            $bulanFind = Bulan::where('id', $bulan)->get();
            $bulanId = $bulanFind[0]->id;
            $hariFind = Hari::where('nama', $hari)->get();
            $hariId = $hariFind[0]->id;

            $lokasiFind = Lokasi::where('nama', $lokasiNama)->get();
            if ($lokasiFind->count() == 0) {
                $lokasiLatest = Lokasi::create([
                    'nama' => $lokasiNama,
                ]);
                $lokasiId = $lokasiLatest->id;
            } else {
                $lokasiId = $lokasiFind[0]->id;
            }

            $notaFind = Nota::where('tahun_id', $tahunId)
                ->where('bulan_id', $bulanId)
                ->where('hari_id', $hariId)
                ->where('lokasi_id', $lokasiId)
                ->where('nama', $notaNama)
                ->get();
            if ($notaFind->count() == 0) {
                $notaLatest = Nota::create([
                    'tahun_id' => $tahunId,
                    'bulan_id' => $bulanId,
                    'hari_id' => $hariId,
                    'lokasi_id' => $lokasiId,
                    'nama' => $notaNama,
                    'keterangan' => $keterangan,
                ]);
                $notaId = $notaLatest->id;
            } else {
                $notaId = $notaFind[0]->id;
            }

            $barangFind = Barang::where('nama', $barangNama)->get();
            if ($barangFind->count() == 0) {
                $barangLatest = Barang::create([
                    'nama' => $barangNama,
                ]);
                $barangId = $barangLatest->id;
            } else {
                $barangId = $barangFind[0]->id;
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

            $hargaFind = Harga::where('harga', $harga)->get();
            if ($hargaFind->count() == 0) {
                $hargaLatest = Harga::create([
                    'harga' => $harga,
                ]);
                $hargaId = $hargaLatest->id;
            } else {
                $hargaId = $hargaFind[0]->id;
            }

            $transaksiFind = Transaksi::where('nama', $transaksiNama)->get();
            if ($transaksiFind->count() == 0) {
                $transaksiLatest = Transaksi::create([
                    'nama' => $transaksiNama,
                ]);
                $transaksiId = $transaksiLatest->id;
            } else {
                $transaksiId = $transaksiFind[0]->id;
            }

            $detailFind = Detail::where('nota_id', $notaId)
                ->where('barang_id', $barangId)
                ->where('satuan_id', $satuanId)
                ->where('harga_id', $hargaId)
                ->where('transaksi_id', $transaksiId)
                ->get();
            if ($detailFind->count() == 0) {
                return new Detail([
                    'nota_id' => $notaId,
                    'barang_id' => $barangId,
                    'satuan_id' => $satuanId,
                    'harga_id' => $hargaId,
                    'transaksi_id' => $transaksiId,
                    'kuantitas' => $kuantitas,
                    'keterangan' => $keterangan,
                ]);
            }
        }

        // $tanggalUTC = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row['tanggal']);
        // $tanggal = $tanggalUTC->format('j/n/Y');
        // $tahun = substr($tanggal, 4, 7);
        // $bulan = substr($tanggal, 2, 3);
        // $hari = substr($tanggal, 0, 1);
        // $lokasi = $row['lokasi'];
        // $nama = $row['nama'];
        // $keterangan = $row['keterangan'];
        // if (
        //     $row['tanggal'] != null &&
        //     $row['lokasi'] != null &&
        //     $row['nama'] != null &&
        //     $row['keterangan'] != null
        // ) {
        //     $tahunFind = Tahun::where('nama', $tahun)->get();
        //     $tahunId = $tahunFind[0]->id;
        //     $bulanFind = Bulan::where('id', $bulan)->get();
        //     $bulanId = $bulanFind[0]->id;
        //     $hariFind = Hari::where('nama', $hari)->get();
        //     $hariId = $hariFind[0]->id;
        //     $lokasiFind = Lokasi::where('nama', $lokasi)->get();
        //     if ($lokasiFind->count() == 0) {
        //         $lokasiLatest = Lokasi::create([
        //             'nama' => $lokasi,
        //         ]);
        //         $lokasiId = $lokasiLatest->id;
        //     } else {
        //         $lokasiId = $lokasiFind[0]->id;
        //     }

        //     $notaFind = Nota::where('tahun_id', $tahunId)
        //         ->where('bulan_id', $bulanId)
        //         ->where('hari_id', $hariId)
        //         ->where('lokasi_id', $lokasiId)
        //         ->where('nama', $nama)
        //         ->get();
        //     if ($notaFind->count() == 0) {
        //         return new Nota([
        //             'tahun_id' => $tahunId,
        //             'bulan_id' => $bulanId,
        //             'hari_id' => $hariId,
        //             'lokasi_id' => $lokasiId,
        //             'nama' => $nama,
        //             'keterangan' => $keterangan,
        //         ]);
        //     }
        // }
    }
}
