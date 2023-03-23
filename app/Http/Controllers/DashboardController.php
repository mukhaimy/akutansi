<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Auth;
use Hash;
use App\Models\User;
use App\Models\Bulan;
use App\Models\Tahun;
use App\Models\Hari;
use App\Models\Lokasi;
use App\Models\Nota;
use App\Models\Detail;
use App\Models\Satuan;
use App\Models\Barang;
use App\Models\Stok;
use App\Models\Varietas;
use App\Models\Inventory;
use App\Models\Jenis;
use App\Models\KeteranganBibit;
use App\Models\MasukBibit;
use App\Models\StokDetail;
use App\Models\Obat;
use DateTime;
use DatePeriod;
use DateInterval;

use Mockery\Undefined;
use stdClass;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // KEUANGAN
        if ($request->tahunId == null) {
            $dateNow = Date('Y');
        } else {
            $tahunId = $request->tahunId;
            $tahun = Tahun::where('id', $tahunId)->get();
            $dateNow = $tahun[0]->nama;
        }

        $pertahuns = Tahun::where('nama', $dateNow)->get();
        $tahunId = $pertahuns[0]->id;
        $tahunNama = $pertahuns[0]->nama;

        $tahuns = Tahun::get();
        $perbulans = Bulan::get();

        for ($j = 0; $j < sizeof($perbulans); $j++) {
            $pernotas = Nota::where('tahun_id', $tahunId)
                ->where('bulan_id', $perbulans[$j]->id)->get();

            $debitArr = $debitArr2 = array();
            $kreditArr = $kreditArr2 = array();
            $saldoArr = $saldoArr2 = array();

            for ($k = 0; $k < sizeof($pernotas); $k++) {
                for ($l = 0; $l < sizeof($pernotas[$k]->details); $l++) {
                    if ($pernotas[$k]->details[$l]->transaksies->nama == "debit") {
                        $pernotas[$k]->details[$l]->saldo = $pernotas[$k]->details[$l]->hargas->harga * $pernotas[$k]->details[$l]->kuantitas;
                        $pernotas[$k]->details[$l]->debit = $pernotas[$k]->details[$l]->hargas->harga * $pernotas[$k]->details[$l]->kuantitas;
                        $pernotas[$k]->details[$l]->kredit = null;
                    } else {
                        $pernotas[$k]->details[$l]->saldo = - ($pernotas[$k]->details[$l]->hargas->harga) * $pernotas[$k]->details[$l]->kuantitas;
                        $pernotas[$k]->details[$l]->debit = null;
                        $pernotas[$k]->details[$l]->kredit = - ($pernotas[$k]->details[$l]->hargas->harga) * $pernotas[$k]->details[$l]->kuantitas;
                    }
                    $debitArr[$k][$l] = $pernotas[$k]->details[$l]->debit;
                    $kreditArr[$k][$l] = $pernotas[$k]->details[$l]->kredit;
                    $saldoArr[$k][$l] = $pernotas[$k]->details[$l]->saldo;
                }
            }

            foreach ($debitArr as $key => $value) {
                $debitArr2[$key] = array_sum($value);
            }

            foreach ($kreditArr as $key => $value) {
                $kreditArr2[$key] = array_sum($value);
            }

            foreach ($saldoArr as $key => $value) {
                $saldoArr2[$key] = array_sum($value);
            }

            $perbulans[$j]->debit = array_sum($debitArr2);
            $perbulans[$j]->kredit = array_sum($kreditArr2);
            $perbulans[$j]->saldo = array_sum($saldoArr2);

            $debit[] = $perbulans[$j]->debit;
            $kredit[] = $perbulans[$j]->kredit;
            $saldo[] = $perbulans[$j]->saldo;
            unset($debitArr, $debitArr2, $kreditArr, $kreditArr2, $saldoArr, $saldoArr2,);
        }

        if ($debit == null) {
            $totalDebit = 0;
        } else {
            $totalDebit = array_sum($debit);
        }

        if ($kredit == null) {
            $totalKredit = 0;
        } else {
            $totalKredit = array_sum($kredit);
        }

        if ($saldo == null) {
            $totalSaldo = 0;
        } else {
            $totalSaldo = array_sum($saldo);
        }

        // STOK
        $lokasies =  Lokasi::get();
        $varietases = Varietas::get();
        $stokDetails = StokDetail::get();
        $stoks = Stok::get();
        $keteranganBibits = KeteranganBibit::get();

        $lokasiNama = array();
        foreach ($lokasies as $value) {
            $lokasiNama[$value->id] = $value->nama;
        }
        $varietasNama = array();
        foreach ($varietases as $value) {
            $varietasNama[$value->id] = $value->nama;
        }
        $keteranganBibitNama = array();
        foreach ($keteranganBibits as $value) {
            $keteranganBibitNama[$value->id] = $value->nama;
        }

        $bibitLabels = array();
        foreach ($varietases as $value) {
            $bibitLabels[] = $value->nama;
        }

        $colorBackgrounds = array();
        foreach ($keteranganBibitNama as $value) {
            $rgb1 = substr(md5($value), 0, 6);
            $rgb2 = substr(md5($value), 6, 6);
            $rgb3 = substr(md5($value), 12, 6);
            $rgbMd5Value1 = (int) substr(hexdec($rgb1), 0, 3);
            $rgbMd5Value2 = (int) substr(hexdec($rgb2), 0, 3);
            $rgbMd5Value3 = (int) substr(hexdec($rgb3), 0, 3);

            while ($rgbMd5Value1 > 255 || $rgbMd5Value2 > 255 || $rgbMd5Value3 > 255) {
                if ($rgbMd5Value1 > 255) {
                    $rgbMd5Value1 = $rgbMd5Value1 - 255;
                } else if ($rgbMd5Value2 > 255) {
                    $rgbMd5Value2 = $rgbMd5Value2 - 255;
                } else if ($rgbMd5Value3 > 255) {
                    $rgbMd5Value3 = $rgbMd5Value3 - 255;
                }
            }
            $colorBackgrounds[$value] = 'rgb(' . $rgbMd5Value1 . ',' . $rgbMd5Value2 . ',' . $rgbMd5Value3 . ')';
        }

        $keteranganArr = array();
        $varietasArr = array();
        $lokasiArr = array();

        foreach ($keteranganBibits as $value) {
            $keteranganArr[$value->nama] = 0;
        }

        foreach ($varietases as $value) {
            $varietasArr[$value->nama] = $keteranganArr;
        }

        foreach ($lokasies as $value) {
            $lokasiArr[$value->nama] = $varietasArr;
        }

        $keteranganArrNama = array();
        $dataKuantitas = array();
        foreach ($stoks as $value) {
            $objKet = new stdClass;
            foreach ($value->stok_details as $value2) {
                $objKet->x = $value->varietases->nama;

                foreach ($keteranganBibitNama as $value3) {
                    if ($value2->keterangan_bibits->nama == $value3) {
                        $objKet->$value3 = $value2->kuantitas;
                    }
                }
            }
            $dataKuantitas[$value->lokasies->nama][] = $objKet;
        }

        // random input for testing bibit bar stacked chart
        // $dataLokasiArr = array();
        // $objKeteranganArr = array();
        // foreach ($lokasiNama as $key => $value) {
        //     unset($objKeteranganArr);
        //     foreach ($varietasNama as $key2 => $value2) {
        //         $objKeterangan = new stdClass;
        //         $objKeterangan->x = $value2;
        //         $objKeterangan->Bagus = mt_rand(0, 100);
        //         $objKeterangan->Karantina = mt_rand(0, 100);
        //         $objKeterangan->Mati = mt_rand(0, 100);
        //         $objKeterangan->Lain = mt_rand(0, 100);
        //         $objKeteranganArr[] = $objKeterangan;
        //     }
        //     $dataLokasiArr[$value] = $objKeteranganArr;
        // }

        $dataBibitArr = array();
        foreach ($stoks as $value4) {
            $dataStokFinal = array();
            foreach ($keteranganBibitNama as $value) {
                $dataStoks = new stdClass;
                $dataStoks->label = $value;
                $dataStoks->data = $dataKuantitas[$value4->lokasies->nama];
                $dataStoks->parsing = new stdClass();
                $dataStoks->parsing->yAxisKey = $value;
                $dataStoks->backgroundColor = $colorBackgrounds[$value];
                $dataStoks->borderColor = $colorBackgrounds[$value];
                $dataStoks->borderWidth = 2;
                $dataStoks->stack = 1;

                $dataStokFinal[] = $dataStoks;
            }
            $dataBibitArr[$value4->lokasies->nama] = $dataStokFinal;
        }

        // INVENTORY
        $inventories = Inventory::get();
        $jenises = Jenis::get();
        $satuans = Satuan::get();

        $lokasiNama = array();
        foreach ($lokasies as $value) {
            $lokasiNama[$value->id] = $value->nama;
        }
        $jenisNama = array();
        foreach ($jenises as $value) {
            $jenisNama[$value->id] = $value->nama;
        }

        $inventoryArr = array();
        $jenisArr = array();
        $lokasiArr = array();
        foreach ($inventories as $value) {
            $lokasiArr[$lokasiNama[$value->lokasi_id]] = 0;
        }
        foreach ($inventories as $value) {
            $inventoryArr[$jenisNama[$value->jenis_id]] = $lokasiArr;
        }
        foreach ($inventories as $value) {
            $inventoryArr[$jenisNama[$value->jenis_id]][$lokasiNama[$value->lokasi_id]] =
                1 + $inventoryArr[$jenisNama[$value->jenis_id]][$lokasiNama[$value->lokasi_id]];
        }

        $inventoryLabels = array();
        foreach ($lokasies as $value) {
            $inventoryLabels[] = $value->nama;
        }

        unset($colorBackgrounds);
        $colorBackgrounds = array();
        foreach ($jenisNama as $value) {
            $rgb1 = substr(md5($value), 0, 6);
            $rgb2 = substr(md5($value), 6, 6);
            $rgb3 = substr(md5($value), 12, 6);
            $rgbMd5Value1 = (int) substr(hexdec($rgb1), 0, 3);
            $rgbMd5Value2 = (int) substr(hexdec($rgb2), 0, 3);
            $rgbMd5Value3 = (int) substr(hexdec($rgb3), 0, 3);

            while ($rgbMd5Value1 > 255 || $rgbMd5Value2 > 255 || $rgbMd5Value3 > 255) {
                if ($rgbMd5Value1 > 255) {
                    $rgbMd5Value1 = $rgbMd5Value1 - 255;
                } else if ($rgbMd5Value2 > 255) {
                    $rgbMd5Value2 = $rgbMd5Value2 - 255;
                } else if ($rgbMd5Value3 > 255) {
                    $rgbMd5Value3 = $rgbMd5Value3 - 255;
                }
            }
            $colorBackgrounds[$value] = 'rgb(' . $rgbMd5Value1 . ',' . $rgbMd5Value2 . ',' . $rgbMd5Value3 . ')';
        }

        $dataInventoryArr = array();
        if (count($inventoryArr) == 0) {
            $dataSets = new stdClass;
            $dataSets->label = '';
            $dataSets->data = [];
            $dataSets->backgroundColor = [];
            $dataSets->borderColor = [];
            $dataSets->hoverOffset = 4;
            $dataInventoryArr[] = $dataSets;
        } else {
            foreach ($inventoryArr as $key => $value) {
                $dataSets = new stdClass;
                $dataSets->label = $key;
                $dataSets->data = $value;
                $dataSets->backgroundColor = [$colorBackgrounds[$key]];
                $dataSets->borderColor = [];
                $dataSets->hoverOffset = 4;
                $dataInventoryArr[] = $dataSets;
            }
        }
        $lokasiLength = count($lokasiNama);

        // OBAT
        $obats = Obat::get();
        $satuanNamaObat = array();
        foreach ($satuans as $key => $value) {
            if ($value->nama == 'liter') {
                $satuanNamaObat[] = $value->nama;
            } else if ($value->nama == 'kilogram') {
                $satuanNamaObat[] = $value->nama;
            }
        }

        $satuanNamaObatLength = count($satuanNamaObat);

        $obatLabels = array();
        foreach ($obats as $value) {
            if ($value->satuans->nama == $satuanNamaObat[0]) {
                $obatLabels[$value->satuans->nama][$value->id] = $value->tahuns->nama . "-" . $value->bulans->id . "-" . $value->haries->nama;
            } else if ($value->satuans->nama == $satuanNamaObat[1]) {
                $obatLabels[$value->satuans->nama][$value->id] = $value->tahuns->nama . "-" . $value->bulans->id . "-" . $value->haries->nama;
            }
        }

        unset($colorBackgrounds);
        $colorBackgrounds = array();
        foreach ($satuanNamaObat as $value) {
            $rgb1 = substr(md5($value), 0, 6);
            $rgb2 = substr(md5($value), 6, 6);
            $rgb3 = substr(md5($value), 12, 6);
            $rgbMd5Value1 = (int) substr(hexdec($rgb1), 0, 3);
            $rgbMd5Value2 = (int) substr(hexdec($rgb2), 0, 3);
            $rgbMd5Value3 = (int) substr(hexdec($rgb3), 0, 3);

            while ($rgbMd5Value1 > 255 || $rgbMd5Value2 > 255 || $rgbMd5Value3 > 255) {
                if ($rgbMd5Value1 > 255) {
                    $rgbMd5Value1 = $rgbMd5Value1 - 255;
                } else if ($rgbMd5Value2 > 255) {
                    $rgbMd5Value2 = $rgbMd5Value2 - 255;
                } else if ($rgbMd5Value3 > 255) {
                    $rgbMd5Value3 = $rgbMd5Value3 - 255;
                }
            }
            $colorBackgrounds[$value] = 'rgb(' . $rgbMd5Value1 . ',' . $rgbMd5Value2 . ',' . $rgbMd5Value3 . ')';
        }

        // seperate liter's chart and kilogram's chart
        $obatSatuanCharts = array();
        foreach ($obats as $key => $value) {
            if ($value->satuans->nama == $satuanNamaObat[0]) {
                $obatSatuanCharts[$value->satuans->nama][] = $value;
            } else if ($value->satuans->nama == $satuanNamaObat[1]) {
                $obatSatuanCharts[$value->satuans->nama][] = $value;
            }
        }

        $obatArr = array();
        foreach ($obats as $key => $value) {
            if ($value->satuans->nama == $satuanNamaObat[0]) {
                $dataObat = new stdClass;
                $dataObat->x = $obatLabels[$satuanNamaObat[0]][$value->id];
                $dataObat->y = $value->kuantitas;
                $obatArr[$value->satuans->nama][$value->id] = $dataObat;
            } else if ($value->satuans->nama == $satuanNamaObat[1]) {
                $dataObat = new stdClass;
                $dataObat->x = $obatLabels[$satuanNamaObat[1]][$value->id];
                $dataObat->y = $value->kuantitas;
                $obatArr[$value->satuans->nama][$value->id] = $dataObat;
            }
        }

        $period = new DatePeriod(
            new DateTime('2023-1-1'),
            new DateInterval('P1D'),
            new DateTime('2023-3-23')
        );

        foreach ($period as $date) {
            $array[] = $date->format('Y-n-j');
        }

        $ts = new DateTime(now());

        dd(
            $ts->format('Y-n-j'),
            $period,
            $array
        );

        $dataObatArr = array();
        if (count($obats) == 0) {
            $dataSets = new stdClass;
            $dataSets->label = '';
            $dataSets->data = [];
            $dataSets->backgroundColor = [];
            $dataSets->borderColor = [];
            $dataSets->borderWidth = 4;
            $dataObatArr[] = $dataSets;
        } else {
            foreach ($obats as $key => $value) {
                if ($value->satuans->nama == $satuanNamaObat[0]) {
                    $dataSets = new stdClass;
                    $dataSets->label = $value->barangs->nama;
                    //foreach($obatArr) {
                    $dataSets->data = $obatArr[$value->satuans->nama][$value->id];
                    //}
                    $dataSets->backgroundColor = [$colorBackgrounds[$value->satuans->nama]];
                    $dataSets->borderColor = [$colorBackgrounds[$value->satuans->nama]];
                    $dataSets->borderWidth = 4;
                    $dataObatArr[$value->satuans->nama][] = $dataSets;
                } else if ($value->satuans->nama == $satuanNamaObat[1]) {
                    $dataSets = new stdClass;
                    $dataSets->label = $value->barangs->nama;
                    $dataSets->data = $obatArr[$value->satuans->nama][$value->id];
                    $dataSets->backgroundColor = [$colorBackgrounds[$value->satuans->nama]];
                    $dataSets->borderColor = [$colorBackgrounds[$value->satuans->nama]];
                    $dataSets->borderWidth = 4;
                    $dataObatArr[$value->satuans->nama][] = $dataSets;
                }
            }
        }

        unset($obatLabels);
        $obatLabels = array();
        foreach ($obats as $value) {
            if ($value->satuans->nama == $satuanNamaObat[0]) {
                $obatLabels[$value->satuans->nama][] = $value->tahuns->nama . "-" . $value->bulans->id . "-" . $value->haries->nama;
            } else if ($value->satuans->nama == $satuanNamaObat[1]) {
                $obatLabels[$value->satuans->nama][] = $value->tahuns->nama . "-" . $value->bulans->id . "-" . $value->haries->nama;
            }
        }

        dd(
            $obatLabels,
            $dataObatArr
        );

        // dd(
        //     $obatArr,
        //     $dataObatArr,            
        //     $dataObatArr[$satuanNamaObat[0]],
        //     $dataObatArr[$satuanNamaObat[0]][0]
        // );

        return view(
            'dashboard',
            compact(
                'pertahuns',
                'tahuns',

                'tahunId',
                'tahunNama',

                'debit',
                'kredit',
                'saldo',
                'totalDebit',
                'totalKredit',
                'totalSaldo',

                'lokasiNama',
                'lokasiLength',
                'varietasNama',
                'dataBibitArr',
                'bibitLabels',

                'inventoryLabels',
                'dataInventoryArr',

                'satuanNamaObat',
                'satuanNamaObatLength',
                'obatLabels',
                'dataObatArr',

            )
        );
    }

    public function login()
    {
        return view('login');
    }

    public function postLogin(Request $request)
    {

        if (Auth::attempt($request->only('email', 'password'))) {
            return redirect('/dashboard');
        }
        return redirect('/');
    }

    public function logout()
    {
        Auth::logout();
        return redirect('/');
    }
}
