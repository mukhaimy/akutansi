<?php

namespace App\Http\Controllers;

use App\Imports\StokImport;
use App\Imports\StokDetailImport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Lokasi;
use App\Models\KeteranganBibit;
use App\Models\Varietas;
use App\Models\Stok;
use App\Models\StokDetail;
use App\Models\MasukBibit;
use Illuminate\Support\Collection;
use Arr;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Str;

class StokController extends Controller
{

    // STOK

    public function index()
    {
        $stoks = array();
        $perstoks = Stok::orderby('lokasi_id', 'desc')
            ->orderby('varietas_id', 'desc')
            ->orderby('masuk_bibit_id', 'desc')
            ->paginate(25);

        foreach ($perstoks as $key => $value) {
            $bagusArr1 = $karantinaArr1 = $matiArr1 = $lainArr1 = array();
            foreach ($value->stok_details as $item) {
                if ($item->keterangan_bibits->nama == "Bagus") {
                    $bagusArr1[] = $item->kuantitas;
                } else if ($item->keterangan_bibits->nama == "Karantina") {
                    $karantinaArr1[] = $item->kuantitas;
                } else if ($item->keterangan_bibits->nama == "Mati") {
                    $matiArr1[] = $item->kuantitas;
                } else if ($item->keterangan_bibits->nama == "Lain") {
                    $lainArr1[] = $item->kuantitas;
                }
            }

            $masuk = $value->masukBibits->masuk;
            $now = Carbon::now();
            $perstoks[$key]->masukBibits->selisih = $now->diff($masuk);

            $totalBagus1 = array_sum($bagusArr1);
            $totalKarantina1 = array_sum($karantinaArr1);
            $totalMati1 = array_sum($matiArr1);
            $totalLain1 = array_sum($lainArr1);
            $totalSemua2 = array_merge($bagusArr1, $karantinaArr1, $matiArr1, $lainArr1);
            $totalSemua3 = array_sum($totalSemua2);

            $perstoks[$key]->bagus = $totalBagus1;
            $perstoks[$key]->karantina = $totalKarantina1;
            $perstoks[$key]->mati = $totalMati1;
            $perstoks[$key]->lain = $totalLain1;
            $perstoks[$key]->jumlah = $totalSemua3;

            unset(
                $masuk,
                $now,

                $bagusArr1,
                $karantinaArr1,
                $matiArr1,
                $lainArr1,

                $totalBagus1,
                $totalKarantina1,
                $totalMati1,
                $totalLain1,
                $totalSemua2,
                $totalSemua3
            );
        }

        $bagusArr = $karantinaArr = $matiArr = $lainArr = array();
        foreach ($perstoks as $value) {
            foreach ($value->stok_details as $item) {
                if ($item != null) {
                    if ($item->keterangan_bibits->nama == "Bagus") {
                        $bagusArr[] = $item->kuantitas;
                    } else if ($item->keterangan_bibits->nama == "Karantina") {
                        $karantinaArr[] = $item->kuantitas;
                    } else if ($item->keterangan_bibits->nama == "Mati") {
                        $matiArr[] = $item->kuantitas;
                    } else if ($item->keterangan_bibits->nama == "Lain") {
                        $lainArr[] = $item->kuantitas;
                    }
                } else {
                    $bagusArr[] = 0;
                    $karantinaArr[] = 0;
                    $matiArr[] = 0;
                    $lainArr[] = 0;
                }
            }
        }

        $totalBagus = array_sum($bagusArr);
        $totalKarantina = array_sum($karantinaArr);
        $totalMati = array_sum($matiArr);
        $totalLain = array_sum($lainArr);

        $totalSemua1 = array_merge($bagusArr, $karantinaArr, $matiArr, $lainArr);
        $totalSemua = array_sum($totalSemua1);

        return view(
            'stok.stok',
            compact(
                'stoks',
                'perstoks',

                'totalBagus',
                'totalKarantina',
                'totalMati',
                'totalLain',
                'totalSemua',
            )
        );
    }

    public function stokTambah()
    {
        $lokasies = Lokasi::get();
        $varietases = Varietas::get();

        return view(
            'stok.stok-tambah',
            compact(
                'lokasies',
                'varietases',
            )
        );
    }

    public function stokSimpan(Request $request)
    {
        $lokasiId = $request->lokasiId;
        $varietasId = $request->varietasId;
        $masukValue1 = $request->masuk;
        $masukValue = Str::replace('T', ' ', $masukValue1);

        if ($lokasiId != null && $varietasId != null && $masukValue1 != null) {
            DB::table('masuk_bibits')->insert([
                'masuk' => $masukValue,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $masukNew = MasukBibit::latest()->first();

            DB::table('stoks')->insert([
                "lokasi_id" => $lokasiId,
                "varietas_id" => $varietasId,
                "masuk_bibit_id" => $masukNew->id,
                "created_at" => now(),
                "updated_at" => now(),
            ]);

            $stokLast = DB::table('stoks')->latest()->first();
            $kb = KeteranganBibit::get();

            foreach ($kb as $value) {
                DB::table('stok_details')->insert([
                    'stok_id' => $stokLast->id,
                    'keterangan_bibit_id' => $value->id,
                    'kuantitas' => 0,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        $stoks = array();
        $perstoks = Stok::paginate(25);

        foreach ($perstoks as $key => $value) {
            $bagusArr1 = $karantinaArr1 = $matiArr1 = $lainArr1 = array();
            foreach ($value->stok_details as $item) {
                if ($item->keterangan_bibits->nama == "Bagus") {
                    $bagusArr1[] = $item->kuantitas;
                } else if ($item->keterangan_bibits->nama == "Karantina") {
                    $karantinaArr1[] = $item->kuantitas;
                } else if ($item->keterangan_bibits->nama == "Mati") {
                    $matiArr1[] = $item->kuantitas;
                } else if ($item->keterangan_bibits->nama == "Lain") {
                    $lainArr1[] = $item->kuantitas;
                }
            }
            $masuk = $value->masukBibits->masuk;
            $now = Carbon::now();
            $perstoks[$key]->masukBibits->selisih = $now->diff($masuk);

            $totalBagus1 = array_sum($bagusArr1);
            $totalKarantina1 = array_sum($karantinaArr1);
            $totalMati1 = array_sum($matiArr1);
            $totalLain1 = array_sum($lainArr1);
            $totalSemua2 = array_merge($bagusArr1, $karantinaArr1, $matiArr1, $lainArr1);
            $totalSemua3 = array_sum($totalSemua2);

            $perstoks[$key]->bagus = $totalBagus1;
            $perstoks[$key]->karantina = $totalKarantina1;
            $perstoks[$key]->mati = $totalMati1;
            $perstoks[$key]->lain = $totalLain1;
            $perstoks[$key]->jumlah = $totalSemua3;

            unset(
                $masuk,
                $now,

                $bagusArr1,
                $karantinaArr1,
                $matiArr1,
                $lainArr1,

                $totalBagus1,
                $totalKarantina1,
                $totalMati1,
                $totalLain1,
                $totalSemua2,
                $totalSemua3
            );
        }

        $bagusArr = $karantinaArr = $matiArr = $lainArr = array();
        foreach ($perstoks as $value) {
            foreach ($value->stok_details as $item) {
                if ($item != null) {
                    if ($item->keterangan_bibits->nama == "Bagus") {
                        $bagusArr[] = $item->kuantitas;
                    } else if ($item->keterangan_bibits->nama == "Karantina") {
                        $karantinaArr[] = $item->kuantitas;
                    } else if ($item->keterangan_bibits->nama == "Mati") {
                        $matiArr[] = $item->kuantitas;
                    } else if ($item->keterangan_bibits->nama == "Lain") {
                        $lainArr[] = $item->kuantitas;
                    }
                } else {
                    $bagusArr[] = 0;
                    $karantinaArr[] = 0;
                    $matiArr[] = 0;
                    $lainArr[] = 0;
                }
            }
        }

        $totalBagus = array_sum($bagusArr);
        $totalKarantina = array_sum($karantinaArr);
        $totalMati = array_sum($matiArr);
        $totalLain = array_sum($lainArr);

        $totalSemua1 = array_merge($bagusArr, $karantinaArr, $matiArr, $lainArr);
        $totalSemua = array_sum($totalSemua1);

        return view(
            'stok.stok',
            compact(
                'stoks',
                'perstoks',

                'totalBagus',
                'totalKarantina',
                'totalMati',
                'totalLain',
                'totalSemua',
            )
        );
    }

    public function stokEdit($id)
    {
        $stokId = $id;

        $lokasies = Lokasi::get();
        $varietases = Varietas::get();
        $keterangan_bibits = KeteranganBibit::get();

        $stoks = Stok::where('id', $stokId)->get();
        $perstoks = Stok::paginate(25);

        foreach ($perstoks as $key => $value) {
            $bagusArr1 = $karantinaArr1 = $matiArr1 = $lainArr1 = array();
            foreach ($value->stok_details as $item) {
                if ($item->keterangan_bibits->nama == "Bagus") {
                    $bagusArr1[] = $item->kuantitas;
                } else if ($item->keterangan_bibits->nama == "Karantina") {
                    $karantinaArr1[] = $item->kuantitas;
                } else if ($item->keterangan_bibits->nama == "Mati") {
                    $matiArr1[] = $item->kuantitas;
                } else if ($item->keterangan_bibits->nama == "Lain") {
                    $lainArr1[] = $item->kuantitas;
                }
            }

            $masuk = $value->masukBibits->masuk;
            $now = Carbon::now();
            $perstoks[$key]->masukBibits->selisih = $now->diff($masuk);

            $totalBagus1 = array_sum($bagusArr1);
            $totalKarantina1 = array_sum($karantinaArr1);
            $totalMati1 = array_sum($matiArr1);
            $totalLain1 = array_sum($lainArr1);
            $totalSemua2 = array_merge($bagusArr1, $karantinaArr1, $matiArr1, $lainArr1);
            $totalSemua3 = array_sum($totalSemua2);

            $perstoks[$key]->bagus = $totalBagus1;
            $perstoks[$key]->karantina = $totalKarantina1;
            $perstoks[$key]->mati = $totalMati1;
            $perstoks[$key]->lain = $totalLain1;
            $perstoks[$key]->jumlah = $totalSemua3;

            unset(
                $stokId,

                $masuk,
                $now,

                $bagusArr1,
                $karantinaArr1,
                $matiArr1,
                $lainArr1,

                $totalBagus1,
                $totalKarantina1,
                $totalMati1,
                $totalLain1,
                $totalSemua2,
                $totalSemua3
            );
        }

        $bagusArr = $karantinaArr = $matiArr = $lainArr = array();
        foreach ($perstoks as $value) {
            foreach ($value->stok_details as $item) {
                if ($item != null) {
                    if ($item->keterangan_bibits->nama == "Bagus") {
                        $bagusArr[] = $item->kuantitas;
                    } else if ($item->keterangan_bibits->nama == "Karantina") {
                        $karantinaArr[] = $item->kuantitas;
                    } else if ($item->keterangan_bibits->nama == "Mati") {
                        $matiArr[] = $item->kuantitas;
                    } else if ($item->keterangan_bibits->nama == "Lain") {
                        $lainArr[] = $item->kuantitas;
                    }
                } else {
                    $bagusArr[] = 0;
                    $karantinaArr[] = 0;
                    $matiArr[] = 0;
                    $lainArr[] = 0;
                }
            }
        }

        $totalBagus = array_sum($bagusArr);
        $totalKarantina = array_sum($karantinaArr);
        $totalMati = array_sum($matiArr);
        $totalLain = array_sum($lainArr);

        $totalSemua1 = array_merge($bagusArr, $karantinaArr, $matiArr, $lainArr);
        $totalSemua = array_sum($totalSemua1);

        return view(
            'stok.stok',
            compact(
                'stoks',
                'perstoks',

                'lokasies',
                'varietases',
                'keterangan_bibits',

                'totalBagus',
                'totalKarantina',
                'totalMati',
                'totalLain',
                'totalSemua',
            )
        );
    }

    public function stokUpdate(Request $request, $id)
    {
        $stokId = $id;
        $lokasiId = $request->lokasiId;
        $varietasId = $request->varietasId;
        $masukValue1 = $request->masuk;
        $keteranganBibitId = $request->keteranganBibitId;

        $masukValue = Str::replace('T', ' ', $masukValue1);
        $masukDB = MasukBibit::get();
        foreach ($masukDB as $value) {
            if ($masukValue == $value->masuk) {
                if ($lokasiId != null && $varietasId != null && $masukValue != null) {
                    $masukOld = MasukBibit::where('masuk', $masukValue)->get();

                    DB::table('stoks')->where('id', $stokId)->update([
                        "lokasi_id" => $lokasiId,
                        "varietas_id" => $varietasId,
                        "masuk_bibit_id" => $masukOld[0]->id,
                        "updated_at" => now(),
                    ]);

                    $stoks = array();
                    $perstoks = Stok::paginate(25);

                    foreach ($perstoks as $key => $value) {
                        $bagusArr1 = $karantinaArr1 = $matiArr1 = $lainArr1 = array();
                        foreach ($value->stok_details as $item) {
                            if ($item->keterangan_bibits->nama == "Bagus") {
                                $bagusArr1[] = $item->kuantitas;
                            } else if ($item->keterangan_bibits->nama == "Karantina") {
                                $karantinaArr1[] = $item->kuantitas;
                            } else if ($item->keterangan_bibits->nama == "Mati") {
                                $matiArr1[] = $item->kuantitas;
                            } else if ($item->keterangan_bibits->nama == "Lain") {
                                $lainArr1[] = $item->kuantitas;
                            }
                        }

                        $masuk = $value->masukBibits->masuk;
                        $now = Carbon::now();
                        $perstoks[$key]->masukBibits->selisih = $now->diff($masuk);

                        $totalBagus1 = array_sum($bagusArr1);
                        $totalKarantina1 = array_sum($karantinaArr1);
                        $totalMati1 = array_sum($matiArr1);
                        $totalLain1 = array_sum($lainArr1);
                        $totalSemua2 = array_merge($bagusArr1, $karantinaArr1, $matiArr1, $lainArr1);
                        $totalSemua3 = array_sum($totalSemua2);

                        $perstoks[$key]->bagus = $totalBagus1;
                        $perstoks[$key]->karantina = $totalKarantina1;
                        $perstoks[$key]->mati = $totalMati1;
                        $perstoks[$key]->lain = $totalLain1;
                        $perstoks[$key]->jumlah = $totalSemua3;

                        unset(
                            $masuk,
                            $now,

                            $bagusArr1,
                            $karantinaArr1,
                            $matiArr1,
                            $lainArr1,

                            $totalBagus1,
                            $totalKarantina1,
                            $totalMati1,
                            $totalLain1,
                            $totalSemua2,
                            $totalSemua3
                        );
                    }

                    $bagusArr = $karantinaArr = $matiArr = $lainArr = array();
                    foreach ($perstoks as $value) {
                        foreach ($value->stok_details as $item) {
                            if ($item != null) {
                                if ($item->keterangan_bibits->nama == "Bagus") {
                                    $bagusArr[] = $item->kuantitas;
                                } else if ($item->keterangan_bibits->nama == "Karantina") {
                                    $karantinaArr[] = $item->kuantitas;
                                } else if ($item->keterangan_bibits->nama == "Mati") {
                                    $matiArr[] = $item->kuantitas;
                                } else if ($item->keterangan_bibits->nama == "Lain") {
                                    $lainArr[] = $item->kuantitas;
                                }
                            } else {
                                $bagusArr[] = 0;
                                $karantinaArr[] = 0;
                                $matiArr[] = 0;
                                $lainArr[] = 0;
                            }
                        }
                    }

                    $totalBagus = array_sum($bagusArr);
                    $totalKarantina = array_sum($karantinaArr);
                    $totalMati = array_sum($matiArr);
                    $totalLain = array_sum($lainArr);

                    $totalSemua1 = array_merge($bagusArr, $karantinaArr, $matiArr, $lainArr);
                    $totalSemua = array_sum($totalSemua1);

                    return view(
                        'stok.stok',
                        compact(
                            'stoks',
                            'perstoks',

                            'totalBagus',
                            'totalKarantina',
                            'totalMati',
                            'totalLain',
                            'totalSemua',
                        )
                    );
                }
            } else {
                $masukTest = 1;
            }
        }

        if ($masukTest == 1) {
            DB::table('masuk_bibits')->insert([
                "masuk" => $masukValue,
                "created_at" => now(),
                "updated_at" => now(),
            ]);

            $masukNew = MasukBibit::latest()->first();

            if ($lokasiId != null && $varietasId != null && $masukValue) {
                DB::table('stoks')->where('id', $stokId)->update([
                    "lokasi_id" => $lokasiId,
                    "varietas_id" => $varietasId,
                    "masuk_bibit_id" => $masukNew->id,
                    "updated_at" => now(),
                ]);
            }
        }

        $stoks = array();
        $perstoks = Stok::paginate(25);

        foreach ($perstoks as $key => $value) {
            $bagusArr1 = $karantinaArr1 = $matiArr1 = $lainArr1 = array();
            foreach ($value->stok_details as $item) {
                if ($item->keterangan_bibits->nama == "Bagus") {
                    $bagusArr1[] = $item->kuantitas;
                } else if ($item->keterangan_bibits->nama == "Karantina") {
                    $karantinaArr1[] = $item->kuantitas;
                } else if ($item->keterangan_bibits->nama == "Mati") {
                    $matiArr1[] = $item->kuantitas;
                } else if ($item->keterangan_bibits->nama == "Lain") {
                    $lainArr1[] = $item->kuantitas;
                }
            }

            $masuk = $value->masukBibits->masuk;
            $now = Carbon::now();
            $perstoks[$key]->masukBibits->selisih = $now->diff($masuk);

            $totalBagus1 = array_sum($bagusArr1);
            $totalKarantina1 = array_sum($karantinaArr1);
            $totalMati1 = array_sum($matiArr1);
            $totalLain1 = array_sum($lainArr1);
            $totalSemua2 = array_merge($bagusArr1, $karantinaArr1, $matiArr1, $lainArr1);
            $totalSemua3 = array_sum($totalSemua2);

            $perstoks[$key]->bagus = $totalBagus1;
            $perstoks[$key]->karantina = $totalKarantina1;
            $perstoks[$key]->mati = $totalMati1;
            $perstoks[$key]->lain = $totalLain1;
            $perstoks[$key]->jumlah = $totalSemua3;

            unset(
                $masuk,
                $now,

                $bagusArr1,
                $karantinaArr1,
                $matiArr1,
                $lainArr1,

                $totalBagus1,
                $totalKarantina1,
                $totalMati1,
                $totalLain1,
                $totalSemua2,
                $totalSemua3
            );
        }

        $bagusArr = $karantinaArr = $matiArr = $lainArr = array();
        foreach ($perstoks as $value) {
            foreach ($value->stok_details as $item) {
                if ($item != null) {
                    if ($item->keterangan_bibits->nama == "Bagus") {
                        $bagusArr[] = $item->kuantitas;
                    } else if ($item->keterangan_bibits->nama == "Karantina") {
                        $karantinaArr[] = $item->kuantitas;
                    } else if ($item->keterangan_bibits->nama == "Mati") {
                        $matiArr[] = $item->kuantitas;
                    } else if ($item->keterangan_bibits->nama == "Lain") {
                        $lainArr[] = $item->kuantitas;
                    }
                } else {
                    $bagusArr[] = 0;
                    $karantinaArr[] = 0;
                    $matiArr[] = 0;
                    $lainArr[] = 0;
                }
            }
        }

        $totalBagus = array_sum($bagusArr);
        $totalKarantina = array_sum($karantinaArr);
        $totalMati = array_sum($matiArr);
        $totalLain = array_sum($lainArr);

        $totalSemua1 = array_merge($bagusArr, $karantinaArr, $matiArr, $lainArr);
        $totalSemua = array_sum($totalSemua1);

        return view(
            'stok.stok',
            compact(
                'stoks',
                'perstoks',

                'totalBagus',
                'totalKarantina',
                'totalMati',
                'totalLain',
                'totalSemua',
            )
        );
    }

    public function stokHapus($id)
    {
        $stokId = $id;
        DB::table('stoks')->where('id', $stokId)->delete();

        $stoks = array();
        $perstoks = Stok::paginate(25);

        foreach ($perstoks as $key => $value) {
            $bagusArr1 = $karantinaArr1 = $matiArr1 = $lainArr1 = array();
            foreach ($value->stok_details as $item) {
                if ($item->keterangan_bibits->nama == "Bagus") {
                    $bagusArr1[] = $item->kuantitas;
                } else if ($item->keterangan_bibits->nama == "Karantina") {
                    $karantinaArr1[] = $item->kuantitas;
                } else if ($item->keterangan_bibits->nama == "Mati") {
                    $matiArr1[] = $item->kuantitas;
                } else if ($item->keterangan_bibits->nama == "Lain") {
                    $lainArr1[] = $item->kuantitas;
                }
            }

            $masuk = $value->masukBibits->masuk;
            $now = Carbon::now();
            $perstoks[$key]->masukBibits->selisih = $now->diff($masuk);

            $totalBagus1 = array_sum($bagusArr1);
            $totalKarantina1 = array_sum($karantinaArr1);
            $totalMati1 = array_sum($matiArr1);
            $totalLain1 = array_sum($lainArr1);
            $totalSemua2 = array_merge($bagusArr1, $karantinaArr1, $matiArr1, $lainArr1);
            $totalSemua3 = array_sum($totalSemua2);

            $perstoks[$key]->bagus = $totalBagus1;
            $perstoks[$key]->karantina = $totalKarantina1;
            $perstoks[$key]->mati = $totalMati1;
            $perstoks[$key]->lain = $totalLain1;
            $perstoks[$key]->jumlah = $totalSemua3;

            unset(
                $masuk,
                $now,

                $bagusArr1,
                $karantinaArr1,
                $matiArr1,
                $lainArr1,

                $totalBagus1,
                $totalKarantina1,
                $totalMati1,
                $totalLain1,
                $totalSemua2,
                $totalSemua3
            );
        }

        $bagusArr = $karantinaArr = $matiArr = $lainArr = array();
        foreach ($perstoks as $value) {
            foreach ($value->stok_details as $item) {
                if ($item != null) {
                    if ($item->keterangan_bibits->nama == "Bagus") {
                        $bagusArr[] = $item->kuantitas;
                    } else if ($item->keterangan_bibits->nama == "Karantina") {
                        $karantinaArr[] = $item->kuantitas;
                    } else if ($item->keterangan_bibits->nama == "Mati") {
                        $matiArr[] = $item->kuantitas;
                    } else if ($item->keterangan_bibits->nama == "Lain") {
                        $lainArr[] = $item->kuantitas;
                    }
                } else {
                    $bagusArr[] = 0;
                    $karantinaArr[] = 0;
                    $matiArr[] = 0;
                    $lainArr[] = 0;
                }
            }
        }

        $totalBagus = array_sum($bagusArr);
        $totalKarantina = array_sum($karantinaArr);
        $totalMati = array_sum($matiArr);
        $totalLain = array_sum($lainArr);

        $totalSemua1 = array_merge($bagusArr, $karantinaArr, $matiArr, $lainArr);
        $totalSemua = array_sum($totalSemua1);

        return view(
            'stok.stok',
            compact(
                'stoks',
                'perstoks',

                'totalBagus',
                'totalKarantina',
                'totalMati',
                'totalLain',
                'totalSemua',
            )
        );
    }

    public function stokCari(Request $request)
    {
        $cari = $request->cari;
        $lokasies = Lokasi::where('nama', 'like', "%" . $cari . "%")->get();

        foreach ($lokasies as $value) {
            $perstoks2[] = Stok::where('lokasi_id', $value->id)->get();
        }

        foreach ($perstoks2 as $value) {
            $bagusArr = $karantinaArr = $matiArr = $lainArr = array();
            foreach ($value as $item) {
                foreach ($item->stok_details as $point) {
                    if ($point != null) {
                        if ($point->keterangan_bibits->nama == "Bagus") {
                            $bagusArr[] = $point->kuantitas;
                        } else if ($point->keterangan_bibits->nama == "Karantina") {
                            $karantinaArr[] = $point->kuantitas;
                        } else if ($point->keterangan_bibits->nama == "Mati") {
                            $matiArr[] = $point->kuantitas;
                        } else if ($point->keterangan_bibits->nama == "Lain") {
                            $lainArr[] = $point->kuantitas;
                        }
                    } else {
                        $bagusArr[] = 0;
                        $karantinaArr[] = 0;
                        $matiArr[] = 0;
                        $lainArr[] = 0;
                    }
                }
            }
        }

        $totalBagus = array_sum($bagusArr);
        $totalKarantina = array_sum($karantinaArr);
        $totalMati = array_sum($matiArr);
        $totalLain = array_sum($lainArr);

        $totalSemua1 = array_merge($bagusArr, $karantinaArr, $matiArr, $lainArr);
        $totalSemua = array_sum($totalSemua1);

        $test = array();
        foreach ($perstoks2 as $value) {
            foreach ($value as $item) {
                $test[] = $item;
            }
        }
        $stoks = array();
        $perstoks = collect($test)->paginate(25);


        foreach ($perstoks as $key => $value) {
            $bagusArr1 = $karantinaArr1 = $matiArr1 = $lainArr1 = array();
            foreach ($value->stok_details as $item) {
                if ($item->keterangan_bibits->nama == "Bagus") {
                    $bagusArr1[] = $item->kuantitas;
                } else if ($item->keterangan_bibits->nama == "Karantina") {
                    $karantinaArr1[] = $item->kuantitas;
                } else if ($item->keterangan_bibits->nama == "Mati") {
                    $matiArr1[] = $item->kuantitas;
                } else if ($item->keterangan_bibits->nama == "Lain") {
                    $lainArr1[] = $item->kuantitas;
                }
            }

            $masuk = $value->masukBibits->masuk;
            $now = Carbon::now();
            $perstoks[$key]->masukBibits->selisih = $now->diff($masuk);

            $totalBagus1 = array_sum($bagusArr1);
            $totalKarantina1 = array_sum($karantinaArr1);
            $totalMati1 = array_sum($matiArr1);
            $totalLain1 = array_sum($lainArr1);
            $totalSemua2 = array_merge($bagusArr1, $karantinaArr1, $matiArr1, $lainArr1);
            $totalSemua3 = array_sum($totalSemua2);

            $perstoks[$key]->bagus = $totalBagus1;
            $perstoks[$key]->karantina = $totalKarantina1;
            $perstoks[$key]->mati = $totalMati1;
            $perstoks[$key]->lain = $totalLain1;
            $perstoks[$key]->jumlah = $totalSemua3;

            unset(
                $masuk,
                $now,

                $bagusArr1,
                $karantinaArr1,
                $matiArr1,
                $lainArr1,

                $totalBagus1,
                $totalKarantina1,
                $totalMati1,
                $totalLain1,
                $totalSemua2,
                $totalSemua3
            );
        }

        return view(
            'stok.stok',
            compact(
                'stoks',
                'perstoks',

                'totalBagus',
                'totalKarantina',
                'totalMati',
                'totalLain',
                'totalSemua',
            )
        );
    }

    public function stokImport()
    {
        Excel::import(new StokImport, request()->file('file'));
        return redirect()->back();
    }

    // DETAIL STOK
    public function stokDetail($id)
    {
        $stokId = $id;

        $details = array();
        $perdetails = StokDetail::where('stok_id', $stokId)->get();

        return view(
            'stok.stok-detail',
            compact(
                'details',
                'perdetails',

                'stokId',
            )
        );
    }

    public function stokDetailTambah(Request $request)
    {
        $stokId = $request->stokId;
        $keteranganBibits = KeteranganBibit::get();

        return view(
            'stok.stok-detail-tambah',
            compact(
                'keteranganBibits',

                'stokId',
            )
        );
    }

    public function stokDetailSimpan(Request $request)
    {
        $stokId = $request->stokId;

        $keteranganBibitId = $request->keteranganBibitId;
        $kuantitas = $request->kuantitas;

        if ($keteranganBibitId != null && $kuantitas != null) {
            DB::table('stok_details')->insert([
                "stok_id" => $stokId,
                "keterangan_bibit_id" => $keteranganBibitId,
                "kuantitas" => $kuantitas,
                "created_at" => now(),
                "updated_at" => now(),
            ]);
        }

        $details = array();
        $perdetails = StokDetail::where('stok_id', $stokId)->get();

        return view(
            'stok.stok-detail',
            compact(
                'details',
                'perdetails',

                'stokId',
            )
        );
    }

    public function stokDetailEdit($id)
    {
        $stokDetailId = $id;
        $keteranganBibits = KeteranganBibit::get();

        $details = StokDetail::where('id', $stokDetailId)->get();
        $stokId = $details[0]->stok_id;

        $perdetails = StokDetail::where('stok_id', $stokId)->get();

        return view(
            'stok.stok-detail',
            compact(
                'keteranganBibits',

                'details',
                'perdetails',

                'stokId',
            )
        );
    }

    public function stokDetailUpdate(Request $request, $id)
    {
        $stokDetailId = $id;
        $stokId = $request->stokId;
        $keteranganBibitId = $request->keteranganBibitId;
        $kuantitas = $request->kuantitas;

        if ($keteranganBibitId != null && $kuantitas != null) {
            DB::table('stok_details')->where('id', $stokDetailId)->update([
                "stok_id" => $stokId,
                "keterangan_bibit_id" => $keteranganBibitId,
                "kuantitas" => $kuantitas,
                "updated_at" => now(),
            ]);
        }

        $details = array();
        $perdetails = StokDetail::where('stok_id', $stokId)->get();

        return view(
            'stok.stok-detail',
            compact(
                'details',
                'perdetails',

                'stokId',
            )
        );
    }

    public function stokDetailHapus($id)
    {
        $stokDetailId = $id;
        $stokId1 = StokDetail::where('id', $stokDetailId)->get();
        $stokId = $stokId1[0]->stok_id;

        DB::table('stok_details')->where('id', $stokDetailId)->delete();

        $details = array();
        $perdetails = StokDetail::where('stok_id', $stokId)->get();

        return view(
            'stok.stok-detail',
            compact(
                'details',
                'perdetails',

                'stokId',
            )
        );
    }

    public function stokDetailImport()
    {
        Excel::import(new StokDetailImport, request()->file('file'));
        return redirect()->back();
    }
}
