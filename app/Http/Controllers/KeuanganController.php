<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Tahun;
use App\Models\Bulan;
use App\Models\Hari;
use App\Models\Lokasi;
use App\Models\Satuan;
use App\Models\Barang;
use App\Models\Nota;
use App\Models\Detail;
use App\Models\Harga;
use Auth;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Date;
use PhpParser\Node\Expr\Cast\Object_;
use App\Imports\NotaImport;
use App\Imports\DetailImport;
use Carbon\Carbon;
use Hamcrest\Arrays\IsArray;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Str;

class KeuanganController extends Controller
{
    // NOTA
    public function index()
    {
        $notas = array();
        $pernotas = Nota::orderby('tahun_id', 'desc')
            ->orderby('bulan_id', 'desc')
            ->orderby('hari_id', 'desc')
            ->orderby('lokasi_id', 'asc')
            ->orderby('nama', 'asc')
            ->paginate(25);

        for ($i = 0; $i < sizeof($pernotas); $i++) {
            for ($j = 0; $j < sizeof($pernotas[$i]->details); $j++) {
                if ($pernotas[$i]->details[$j]->transaksies->nama == "debit") {
                    $pernotas[$i]->details[$j]->saldo = $pernotas[$i]->details[$j]->hargas->harga * $pernotas[$i]->details[$j]->kuantitas;
                    $pernotas[$i]->details[$j]->debit = $pernotas[$i]->details[$j]->hargas->harga * $pernotas[$i]->details[$j]->kuantitas;
                    $pernotas[$i]->details[$j]->kredit = null;
                } else {
                    $pernotas[$i]->details[$j]->saldo = - ($pernotas[$i]->details[$j]->hargas->harga) * $pernotas[$i]->details[$j]->kuantitas;
                    $pernotas[$i]->details[$j]->debit = null;
                    $pernotas[$i]->details[$j]->kredit = - ($pernotas[$i]->details[$j]->hargas->harga) * $pernotas[$i]->details[$j]->kuantitas;
                }
            }
        }

        $debitArr = array();
        $kreditArr = array();
        $saldoArr = array();
        for ($i = 0; $i < sizeof($pernotas); $i++) {
            for ($j = 0; $j < sizeof($pernotas[$i]->details); $j++) {
                $debitArr[$i][$j] = $pernotas[$i]->details[$j]->debit;
                $kreditArr[$i][$j] = $pernotas[$i]->details[$j]->kredit;
                $saldoArr[$i][$j] = $pernotas[$i]->details[$j]->saldo;
            }
        }

        foreach ($debitArr as $key => $value) {
            $pernotas[$key]->debit = array_sum($value);
        }
        foreach ($kreditArr as $key => $value) {
            $pernotas[$key]->kredit = array_sum($value);
        }
        foreach ($saldoArr as $key => $value) {
            $pernotas[$key]->saldo = array_sum($value);
        }

        $saldo = array();
        foreach ($pernotas as $value) {
            $saldo[] = $value->saldo;
        }

        if ($saldo == null) {
            $totalSaldo = 0;
        } else {
            $totalSaldo = array_sum($saldo);
        }

        return view(
            'keuangan.nota',
            compact(
                'notas',

                'pernotas',

                'totalSaldo'
            )
        );
    }

    public function notaTambah()
    {
        $tahunNow = Date('Y');
        $bulanNow = Date('n');
        $hariNow = Date('j');

        $pertahuns = Tahun::where('nama', $tahunNow)->get();
        $perbulans = Bulan::where('id', $bulanNow)->get();
        $perharies = Hari::where('nama', $hariNow)->get();

        $tahunId = $pertahuns[0]->id;
        $bulanId = $perbulans[0]->id;
        $hariId = $perharies[0]->id;

        $tahun = Tahun::where('id', $tahunId)->select('nama')->get();
        $tahunNama = $tahun[0]->nama;

        $bulan = Bulan::where('id', $bulanId)->select('nama')->get();
        $bulanNama = $bulan[0]->nama;

        $hari = Hari::where('id', $hariId)->select('nama')->get();
        $hariNama = $hari[0]->nama;

        $tahuns = Tahun::get();
        $bulans = Bulan::get();
        $haries = Hari::get();
        $lokasies = Lokasi::get();

        return view(
            'keuangan.nota-tambah',
            compact(
                'tahunId',
                'bulanId',
                'hariId',
                'tahunNama',
                'bulanNama',
                'hariNama',

                'tahuns',
                'bulans',
                'haries',
                'lokasies',
            )
        );
    }

    public function notaSimpan(Request $request)
    {
        $tahunId = $request->tahunId;
        $bulanId = $request->bulanId;
        $hariId = $request->hariId;
        $lokasiId = $request->lokasiId;
        $nama = $request->nama;
        $keterangan = $request->keterangan;

        if ($tahunId != null && $bulanId != null && $hariId != null && $lokasiId != null && $nama != null) {
            DB::table('notas')->insert([
                "tahun_id" => $tahunId,
                "bulan_id" => $bulanId,
                "hari_id" => $hariId,
                "lokasi_id" => $lokasiId,
                "nama" => $nama,
                "keterangan" => $keterangan,
                "created_at" => now(),
                "updated_at" => now(),
            ]);
        }

        $notas = array();

        $pernotas = Nota::orderby('tahun_id', 'desc')
            ->orderby('bulan_id', 'desc')
            ->orderby('hari_id', 'desc')
            ->orderby('lokasi_id', 'asc')
            ->orderby('nama', 'asc')
            ->paginate(25);

        for ($i = 0; $i < sizeof($pernotas); $i++) {
            for ($j = 0; $j < sizeof($pernotas[$i]->details); $j++) {
                if ($pernotas[$i]->details[$j]->transaksies->nama == "debit") {
                    $pernotas[$i]->details[$j]->saldo = $pernotas[$i]->details[$j]->hargas->harga * $pernotas[$i]->details[$j]->kuantitas;
                    $pernotas[$i]->details[$j]->debit = $pernotas[$i]->details[$j]->hargas->harga * $pernotas[$i]->details[$j]->kuantitas;
                    $pernotas[$i]->details[$j]->kredit = null;
                } else {
                    $pernotas[$i]->details[$j]->saldo = - ($pernotas[$i]->details[$j]->hargas->harga) * $pernotas[$i]->details[$j]->kuantitas;
                    $pernotas[$i]->details[$j]->debit = null;
                    $pernotas[$i]->details[$j]->kredit = - ($pernotas[$i]->details[$j]->hargas->harga) * $pernotas[$i]->details[$j]->kuantitas;
                }
            }
        }

        $debitArr = array();
        $kreditArr = array();
        $saldoArr = array();
        for ($i = 0; $i < sizeof($pernotas); $i++) {
            for ($j = 0; $j < sizeof($pernotas[$i]->details); $j++) {
                $debitArr[$i][$j] = $pernotas[$i]->details[$j]->debit;
                $kreditArr[$i][$j] = $pernotas[$i]->details[$j]->kredit;
                $saldoArr[$i][$j] = $pernotas[$i]->details[$j]->saldo;
            }
        }

        foreach ($debitArr as $key => $value) {
            $pernotas[$key]->debit = array_sum($value);
        }
        foreach ($kreditArr as $key => $value) {
            $pernotas[$key]->kredit = array_sum($value);
        }
        foreach ($saldoArr as $key => $value) {
            $pernotas[$key]->saldo = array_sum($value);
        }

        $saldo = array();
        foreach ($pernotas as $value) {
            $saldo[] = $value->saldo;
        }

        if ($saldo == null) {
            $totalSaldo = 0;
        } else {
            $totalSaldo = array_sum($saldo);
        }

        return view(
            'keuangan.nota',
            compact(
                'notas',

                'pernotas',

                'totalSaldo'
            )
        );
    }

    public function notaEdit($id)
    {
        $notaId = $id;
        $notas = Nota::where('id', $notaId)->get();

        $pernotas = Nota::orderby('tahun_id', 'desc')
            ->orderby('bulan_id', 'desc')
            ->orderby('hari_id', 'desc')
            ->orderby('lokasi_id', 'asc')
            ->orderby('nama', 'asc')
            ->paginate(25);

        for ($i = 0; $i < sizeof($pernotas); $i++) {
            for ($j = 0; $j < sizeof($pernotas[$i]->details); $j++) {
                if ($pernotas[$i]->details[$j]->transaksies->nama == "debit") {
                    $pernotas[$i]->details[$j]->saldo = $pernotas[$i]->details[$j]->hargas->harga * $pernotas[$i]->details[$j]->kuantitas;
                    $pernotas[$i]->details[$j]->debit = $pernotas[$i]->details[$j]->hargas->harga * $pernotas[$i]->details[$j]->kuantitas;
                    $pernotas[$i]->details[$j]->kredit = null;
                } else {
                    $pernotas[$i]->details[$j]->saldo = - ($pernotas[$i]->details[$j]->hargas->harga) * $pernotas[$i]->details[$j]->kuantitas;
                    $pernotas[$i]->details[$j]->debit = null;
                    $pernotas[$i]->details[$j]->kredit = - ($pernotas[$i]->details[$j]->hargas->harga) * $pernotas[$i]->details[$j]->kuantitas;
                }
            }
        }

        $debitArr = array();
        $kreditArr = array();
        $saldoArr = array();
        for ($i = 0; $i < sizeof($pernotas); $i++) {
            for ($j = 0; $j < sizeof($pernotas[$i]->details); $j++) {
                $debitArr[$i][$j] = $pernotas[$i]->details[$j]->debit;
                $kreditArr[$i][$j] = $pernotas[$i]->details[$j]->kredit;
                $saldoArr[$i][$j] = $pernotas[$i]->details[$j]->saldo;
            }
        }

        foreach ($debitArr as $key => $value) {
            $pernotas[$key]->debit = array_sum($value);
        }
        foreach ($kreditArr as $key => $value) {
            $pernotas[$key]->kredit = array_sum($value);
        }
        foreach ($saldoArr as $key => $value) {
            $pernotas[$key]->saldo = array_sum($value);
        }

        $saldo = array();
        foreach ($pernotas as $value) {
            $saldo[] = $value->saldo;
        }

        if ($saldo == null) {
            $totalSaldo = 0;
        } else {
            $totalSaldo = array_sum($saldo);
        }

        return view(
            'keuangan.nota',
            compact(
                'notas',

                'pernotas',

                'totalSaldo'
            )
        );
    }

    public function notaUpdate(Request $request, $id)
    {
        $notaId = $id;

        $nota = Nota::where('id', $notaId)->select('tahun_id', 'bulan_id', 'hari_id', 'lokasi_id')->get();
        $tahunId = $nota[0]->tahun_id;
        $bulanId = $nota[0]->bulan_id;
        $hariId = $nota[0]->hari_id;
        $lokasiId = $nota[0]->lokasi_id;

        $nama = $request->nama;
        $keterangan = $request->keterangan;

        DB::table('notas')->where('id', $notaId)->update([
            "lokasi_id" => $lokasiId,
            "hari_id" => $hariId,
            "bulan_id" => $bulanId,
            "tahun_id" => $tahunId,
            "nama" => $nama,
            "keterangan" => $keterangan,
            "updated_at" => now(),
        ]);

        $notas = array();

        $pernotas = Nota::orderby('tahun_id', 'desc')
            ->orderby('bulan_id', 'desc')
            ->orderby('hari_id', 'desc')
            ->orderby('lokasi_id', 'asc')
            ->orderby('nama', 'asc')
            ->paginate(25);

        for ($i = 0; $i < sizeof($pernotas); $i++) {
            for ($j = 0; $j < sizeof($pernotas[$i]->details); $j++) {
                if ($pernotas[$i]->details[$j]->transaksies->nama == "debit") {
                    $pernotas[$i]->details[$j]->saldo = $pernotas[$i]->details[$j]->hargas->harga * $pernotas[$i]->details[$j]->kuantitas;
                    $pernotas[$i]->details[$j]->debit = $pernotas[$i]->details[$j]->hargas->harga * $pernotas[$i]->details[$j]->kuantitas;
                    $pernotas[$i]->details[$j]->kredit = null;
                } else {
                    $pernotas[$i]->details[$j]->saldo = - ($pernotas[$i]->details[$j]->hargas->harga) * $pernotas[$i]->details[$j]->kuantitas;
                    $pernotas[$i]->details[$j]->debit = null;
                    $pernotas[$i]->details[$j]->kredit = - ($pernotas[$i]->details[$j]->hargas->harga) * $pernotas[$i]->details[$j]->kuantitas;
                }
            }
        }

        $debitArr = array();
        $kreditArr = array();
        $saldoArr = array();
        for ($i = 0; $i < sizeof($pernotas); $i++) {
            for ($j = 0; $j < sizeof($pernotas[$i]->details); $j++) {
                $debitArr[$i][$j] = $pernotas[$i]->details[$j]->debit;
                $kreditArr[$i][$j] = $pernotas[$i]->details[$j]->kredit;
                $saldoArr[$i][$j] = $pernotas[$i]->details[$j]->saldo;
            }
        }

        foreach ($debitArr as $key => $value) {
            $pernotas[$key]->debit = array_sum($value);
        }
        foreach ($kreditArr as $key => $value) {
            $pernotas[$key]->kredit = array_sum($value);
        }
        foreach ($saldoArr as $key => $value) {
            $pernotas[$key]->saldo = array_sum($value);
        }

        $saldo = array();
        foreach ($pernotas as $value) {
            $saldo[] = $value->saldo;
        }

        if ($saldo == null) {
            $totalSaldo = 0;
        } else {
            $totalSaldo = array_sum($saldo);
        }

        return view(
            'keuangan.nota',
            compact(
                'notas',

                'pernotas',

                'totalSaldo'
            )
        );
    }

    public function notaHapus($id)
    {
        $notaId = $id;
        DB::table('notas')->where('id', $notaId)->delete();

        $notas = array();

        $pernotas = Nota::orderby('tahun_id', 'desc')
            ->orderby('bulan_id', 'desc')
            ->orderby('hari_id', 'desc')
            ->orderby('lokasi_id', 'asc')
            ->orderby('nama', 'asc')
            ->paginate(25);

        for ($i = 0; $i < sizeof($pernotas); $i++) {
            for ($j = 0; $j < sizeof($pernotas[$i]->details); $j++) {
                if ($pernotas[$i]->details[$j]->transaksies->nama == "debit") {
                    $pernotas[$i]->details[$j]->saldo = $pernotas[$i]->details[$j]->hargas->harga * $pernotas[$i]->details[$j]->kuantitas;
                    $pernotas[$i]->details[$j]->debit = $pernotas[$i]->details[$j]->hargas->harga * $pernotas[$i]->details[$j]->kuantitas;
                    $pernotas[$i]->details[$j]->kredit = null;
                } else {
                    $pernotas[$i]->details[$j]->saldo = - ($pernotas[$i]->details[$j]->hargas->harga) * $pernotas[$i]->details[$j]->kuantitas;
                    $pernotas[$i]->details[$j]->debit = null;
                    $pernotas[$i]->details[$j]->kredit = - ($pernotas[$i]->details[$j]->hargas->harga) * $pernotas[$i]->details[$j]->kuantitas;
                }
            }
        }

        $debitArr = array();
        $kreditArr = array();
        $saldoArr = array();
        for ($i = 0; $i < sizeof($pernotas); $i++) {
            for ($j = 0; $j < sizeof($pernotas[$i]->details); $j++) {
                $debitArr[$i][$j] = $pernotas[$i]->details[$j]->debit;
                $kreditArr[$i][$j] = $pernotas[$i]->details[$j]->kredit;
                $saldoArr[$i][$j] = $pernotas[$i]->details[$j]->saldo;
            }
        }

        foreach ($debitArr as $key => $value) {
            $pernotas[$key]->debit = array_sum($value);
        }
        foreach ($kreditArr as $key => $value) {
            $pernotas[$key]->kredit = array_sum($value);
        }
        foreach ($saldoArr as $key => $value) {
            $pernotas[$key]->saldo = array_sum($value);
        }

        $saldo = array();
        foreach ($pernotas as $value) {
            $saldo[] = $value->saldo;
        }

        if ($saldo == null) {
            $totalSaldo = 0;
        } else {
            $totalSaldo = array_sum($saldo);
        }

        return view(
            'keuangan.nota',
            compact(
                'notas',

                'pernotas',

                'totalSaldo'
            )
        );
    }

    // DETAIL 

    public function detail($id)
    {
        $notaId = $id;

        $details = array();
        $perdetails = Detail::where('nota_id', $notaId)->get();

        if ($perdetails->count() == 0) {
            $notas = Nota::where('id', $notaId)->get();

            $tahunNama = $notas[0]->tahuns->nama;
            $bulanNama = $notas[0]->bulans->nama;
            $hariNama = $notas[0]->haries->nama;
            $lokasiNama = $notas[0]->lokasies->nama;
            $notaNama = $notas[0]->nama;
        } else {
            $tahunNama = $perdetails[0]->notas->tahuns->nama;
            $bulanNama = $perdetails[0]->notas->bulans->nama;
            $hariNama = $perdetails[0]->notas->haries->nama;
            $lokasiNama = $perdetails[0]->notas->lokasies->nama;
            $notaNama = $perdetails[0]->notas->nama;
        }

        for ($i = 0; $i < sizeof($perdetails); $i++) {
            if ($perdetails[$i]->transaksies->nama == "debit") {
                $perdetails[$i]->saldo = $perdetails[$i]->hargas->harga * $perdetails[$i]->kuantitas;
                $perdetails[$i]->debit = $perdetails[$i]->hargas->harga * $perdetails[$i]->kuantitas;
                $perdetails[$i]->kredit = null;
            } else {
                $perdetails[$i]->saldo = - ($perdetails[$i]->hargas->harga) * $perdetails[$i]->kuantitas;
                $perdetails[$i]->debit = null;
                $perdetails[$i]->kredit = - ($perdetails[$i]->hargas->harga) * $perdetails[$i]->kuantitas;
            }
        }

        $saldo = array();
        foreach ($perdetails as $value) {
            $saldo[] = $value->saldo;
        }

        if ($saldo == null) {
            $totalSaldo = 0;
        } else {
            $totalSaldo = array_sum($saldo);
        }

        return view(
            'keuangan.detail',
            compact(
                'notaId',

                'tahunNama',
                'bulanNama',
                'hariNama',
                'lokasiNama',
                'notaNama',

                'details',
                'perdetails',

                'totalSaldo'
            )
        );
    }

    public function detailTambah(Request $request)
    {
        $notaId = $request->notaId;

        $satuans = DB::table('satuans')->get();
        $transaksies = DB::table('transaksies')->get();

        return view(
            'keuangan.detail-tambah',
            compact(
                'notaId',

                'satuans',
                'transaksies',
            )
        );
    }

    public function detailSimpan(Request $request)
    {
        $barangNama = $request->search;
        $barangFind = Barang::where('nama', $barangNama)->get();
        if ($barangFind->count() == 0) {
            DB::table('barangs')->insert([
                "nama" => $barangNama,
                "created_at" => now(),
                "updated_at" => now(),
            ]);
            $barangLatest = DB::table('barangs')->latest()->get();
            $barangId = $barangLatest[0]->id;
        } else {
            $barangId = $barangFind[0]->id;
        }

        $harga = $request->harga;
        $hargaFind = Harga::where('harga', $harga)->get();
        if ($hargaFind->count() == 0) {
            DB::table('hargas')->insert([
                "harga" => $harga,
                "created_at" => now(),
                "updated_at" => now(),
            ]);
            $hargaLatest = DB::table('hargas')->latest()->get();
            $hargaId = $hargaLatest[0]->id;
        } else {
            $hargaId = $hargaFind[0]->id;
        }

        $notaId = $request->notaId;
        $satuanId = $request->satuanId;
        $transaksiId = $request->transaksiId;
        $kuantitas = $request->kuantitas;
        $keterangan = $request->keterangan;

        DB::table('details')->insert([
            "nota_id" => $notaId,
            "barang_id" => $barangId,
            "satuan_id" => $satuanId,
            "harga_id" => $hargaId,
            "transaksi_id" => $transaksiId,
            "kuantitas" => $kuantitas,
            "keterangan" => $keterangan,
            "created_at" => now(),
            "updated_at" => now(),
        ]);

        $details = array();
        $perdetails = Detail::where('nota_id', $notaId)->get();

        if ($perdetails->count() == 0) {
            $notas = Nota::where('id', $notaId)->get();

            $tahunNama = $notas[0]->tahuns->nama;
            $bulanNama = $notas[0]->bulans->nama;
            $hariNama = $notas[0]->haries->nama;
            $lokasiNama = $notas[0]->lokasies->nama;
            $notaNama = $notas[0]->nama;
        } else {
            $tahunNama = $perdetails[0]->notas->tahuns->nama;
            $bulanNama = $perdetails[0]->notas->bulans->nama;
            $hariNama = $perdetails[0]->notas->haries->nama;
            $lokasiNama = $perdetails[0]->notas->lokasies->nama;
            $notaNama = $perdetails[0]->notas->nama;
        }

        for ($i = 0; $i < sizeof($perdetails); $i++) {
            if ($perdetails[$i]->transaksies->nama == "debit") {
                $perdetails[$i]->saldo = $perdetails[$i]->hargas->harga * $perdetails[$i]->kuantitas;
                $perdetails[$i]->debit = $perdetails[$i]->hargas->harga * $perdetails[$i]->kuantitas;
                $perdetails[$i]->kredit = null;
            } else {
                $perdetails[$i]->saldo = - ($perdetails[$i]->hargas->harga) * $perdetails[$i]->kuantitas;
                $perdetails[$i]->debit = null;
                $perdetails[$i]->kredit = - ($perdetails[$i]->hargas->harga) * $perdetails[$i]->kuantitas;
            }
        }

        $saldo = array();
        foreach ($perdetails as $value) {
            $saldo[] = $value->saldo;
        }

        if ($saldo == null) {
            $totalSaldo = 0;
        } else {
            $totalSaldo = array_sum($saldo);
        }

        return view(
            'keuangan.detail',
            compact(
                'notaId',

                'tahunNama',
                'bulanNama',
                'hariNama',
                'lokasiNama',
                'notaNama',

                'details',
                'perdetails',

                'totalSaldo'
            )
        );
    }

    public function detailEdit($id)
    {
        $detailId = $id;

        $barangs = DB::table('barangs')->get();
        $transaksies = DB::table('transaksies')->get();

        $details = Detail::where('id', $detailId)->get();
        $notaId = $details[0]->nota_id;

        $pernotas = Nota::where('id', $notaId)->select('tahun_id', 'bulan_id', 'hari_id', 'lokasi_id')->get();
        $tahunId = $pernotas[0]->tahun_id;
        $bulanId = $pernotas[0]->bulan_id;
        $hariId = $pernotas[0]->hari_id;
        $lokasiId = $pernotas[0]->lokasi_id;

        $perdetails = Detail::where('nota_id', $notaId)->get();

        for ($i = 0; $i < sizeof($perdetails); $i++) {
            if ($perdetails[$i]->transaksies->nama == "debit") {
                $perdetails[$i]->saldo = $perdetails[$i]->hargas->harga * $perdetails[$i]->kuantitas;
            } else {
                $perdetails[$i]->saldo = - ($perdetails[$i]->hargas->harga) * $perdetails[$i]->kuantitas;
            }
        }

        $saldo = array();
        foreach ($perdetails as $value) {
            $saldo[] = $value->saldo;
        }

        if ($saldo == null) {
            $totalSaldo = 0;
        } else {
            $totalSaldo = array_sum($saldo);
        }

        $tahunNama = Tahun::find($tahunId)->nama;
        $bulanNama = Bulan::find($bulanId)->nama;
        $hariNama = Hari::find($hariId)->nama;
        $lokasiNama = Lokasi::find($lokasiId)->nama;
        $notaNama = Nota::find($notaId)->nama;

        return view(
            'keuangan.detail',
            compact(
                'tahunId',
                'bulanId',
                'hariId',
                'lokasiId',
                'notaId',
                'tahunNama',
                'bulanNama',
                'hariNama',
                'lokasiNama',
                'notaNama',

                'barangs',
                'transaksies',

                'details',
                'perdetails',

                'totalSaldo',
            )
        );
    }

    public function detailUpdate(Request $request, $id)
    {
        $detailId = $id;

        $barangs = DB::table('barangs')->get();
        $transaksies = DB::table('transaksies')->get();

        $details = Detail::where('id', $detailId)->get();
        $notaId = $details[0]->nota_id;

        $pernotas = Nota::where('id', $notaId)->select('tahun_id', 'bulan_id', 'hari_id', 'lokasi_id')->get();
        $tahunId = $pernotas[0]->tahun_id;
        $bulanId = $pernotas[0]->bulan_id;
        $hariId = $pernotas[0]->hari_id;
        $lokasiId = $pernotas[0]->lokasi_id;

        $perdetails = Detail::where('nota_id', $notaId)->get();

        $barangId = $request->barangId;
        $transaksiId = $request->transaksiId;
        $kuantitas = $request->kuantitas;
        $keterangan = $request->keterangan;

        DB::table('details')->where('id', $detailId)->update([
            "nota_id" => $notaId,
            "barang_id" => $barangId,
            "transaksi_id" => $transaksiId,
            "kuantitas" => $kuantitas,
            "keterangan" => $keterangan,
            "updated_at" => now(),
        ]);

        $details = array();
        $perdetails = Detail::where('nota_id', $notaId)->get();

        if ($perdetails->count() == 0) {
            $notas = Nota::where('id', $notaId)->get();

            $tahunNama = $notas[0]->tahuns->nama;
            $bulanNama = $notas[0]->bulans->nama;
            $hariNama = $notas[0]->haries->nama;
            $lokasiNama = $notas[0]->lokasies->nama;
            $notaNama = $notas[0]->nama;
        } else {
            $tahunNama = $perdetails[0]->notas->tahuns->nama;
            $bulanNama = $perdetails[0]->notas->bulans->nama;
            $hariNama = $perdetails[0]->notas->haries->nama;
            $lokasiNama = $perdetails[0]->notas->lokasies->nama;
            $notaNama = $perdetails[0]->notas->nama;
        }

        for ($i = 0; $i < sizeof($perdetails); $i++) {
            if ($perdetails[$i]->transaksies->nama == "debit") {
                $perdetails[$i]->saldo = $perdetails[$i]->hargas->harga * $perdetails[$i]->kuantitas;
                $perdetails[$i]->debit = $perdetails[$i]->hargas->harga * $perdetails[$i]->kuantitas;
                $perdetails[$i]->kredit = null;
            } else {
                $perdetails[$i]->saldo = - ($perdetails[$i]->hargas->harga) * $perdetails[$i]->kuantitas;
                $perdetails[$i]->debit = null;
                $perdetails[$i]->kredit = - ($perdetails[$i]->hargas->harga) * $perdetails[$i]->kuantitas;
            }
        }

        $saldo = array();
        foreach ($perdetails as $value) {
            $saldo[] = $value->saldo;
        }

        if ($saldo == null) {
            $totalSaldo = 0;
        } else {
            $totalSaldo = array_sum($saldo);
        }

        return view(
            'keuangan.detail',
            compact(
                'notaId',

                'tahunNama',
                'bulanNama',
                'hariNama',
                'lokasiNama',
                'notaNama',

                'details',
                'perdetails',

                'totalSaldo'
            )
        );
    }

    public function detailHapus($id)
    {
        $detailId = $id;

        $detail = Detail::where('id', $detailId)->get();
        $notaId = $detail[0]->nota_id;

        $pernotas = Nota::where('id', $notaId)->select('tahun_id', 'bulan_id', 'hari_id', 'lokasi_id')->get();
        $tahunId = $pernotas[0]->tahun_id;
        $bulanId = $pernotas[0]->bulan_id;
        $hariId = $pernotas[0]->hari_id;
        $lokasiId = $pernotas[0]->lokasi_id;

        DB::table('details')->where('id', $detailId)->delete();

        $details = array();

        $perdetails = Detail::where('nota_id', $notaId)->get();

        for ($i = 0; $i < sizeof($perdetails); $i++) {
            if ($perdetails[$i]->transaksies->nama == "debit") {
                $perdetails[$i]->saldo = $perdetails[$i]->hargas->harga * $perdetails[$i]->kuantitas;
            } else {
                $perdetails[$i]->saldo = - ($perdetails[$i]->hargas->harga) * $perdetails[$i]->kuantitas;
            }
        }

        $saldo = array();
        foreach ($perdetails as $value) {
            $saldo[] = $value->saldo;
        }

        if ($saldo == null) {
            $totalSaldo = 0;
        } else {
            $totalSaldo = array_sum($saldo);
        }

        $tahunNama = Tahun::find($tahunId)->nama;
        $bulanNama = Bulan::find($bulanId)->nama;
        $hariNama = Hari::find($hariId)->nama;
        $lokasiNama = Lokasi::find($lokasiId)->nama;
        $notaNama = Nota::find($notaId)->nama;

        return view(
            'keuangan.detail',
            compact(
                'tahunId',
                'bulanId',
                'hariId',
                'lokasiId',
                'notaId',

                'tahunNama',
                'bulanNama',
                'hariNama',
                'lokasiNama',
                'notaNama',

                'details',
                'perdetails',

                'totalSaldo',
            )
        );
    }

    // PERTAHUN

    public function pertahun()
    {
        $pertahuns = Tahun::get();
        $pernotas = array();

        for ($h = 0; $h < sizeof($pertahuns); $h++) {
            $pernotas = Nota::where('tahun_id', $pertahuns[$h]->id)->get();

            $debitArr = $debitArr2 = array();
            $kreditArr = $kreditArr2 = array();
            $saldoArr = $saldoArr2 = array();

            for ($i = 0; $i < sizeof($pernotas); $i++) {
                for ($j = 0; $j < sizeof($pernotas[$i]->details); $j++) {
                    if ($pernotas[$i]->details[$j]->transaksies->nama == "debit") {
                        $pernotas[$i]->details[$j]->saldo = $pernotas[$i]->details[$j]->hargas->harga * $pernotas[$i]->details[$j]->kuantitas;
                        $pernotas[$i]->details[$j]->debit = $pernotas[$i]->details[$j]->hargas->harga * $pernotas[$i]->details[$j]->kuantitas;
                        $pernotas[$i]->details[$j]->kredit = null;
                    } else {
                        $pernotas[$i]->details[$j]->saldo = - ($pernotas[$i]->details[$j]->hargas->harga) * $pernotas[$i]->details[$j]->kuantitas;
                        $pernotas[$i]->details[$j]->debit = null;
                        $pernotas[$i]->details[$j]->kredit = - ($pernotas[$i]->details[$j]->hargas->harga) * $pernotas[$i]->details[$j]->kuantitas;
                    }
                    $debitArr[$i][$j] = $pernotas[$i]->details[$j]->debit;
                    $kreditArr[$i][$j] = $pernotas[$i]->details[$j]->kredit;
                    $saldoArr[$i][$j] = $pernotas[$i]->details[$j]->saldo;
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

            $pertahuns[$h]->debit = array_sum($debitArr2);
            $pertahuns[$h]->kredit = array_sum($kreditArr2);
            $pertahuns[$h]->saldo = array_sum($saldoArr2);
            unset($debitArr, $debitArr2, $kreditArr, $kreditArr2, $saldoArr, $saldoArr2,);
        }

        $saldo = array();
        foreach ($pertahuns as $value) {
            $saldo[] = $value->saldo;
        }

        if ($saldo == null) {
            $totalSaldo = 0;
        } else {
            $totalSaldo = array_sum($saldo);
        }

        return view(
            'keuangan.pertahun',
            compact(
                'pertahuns',

                'totalSaldo',
            )
        );
    }

    // PERBULAN

    public function perbulan(Request $request)
    {
        $pertahuns = Tahun::get();
        $perbulans = Bulan::get();

        if ($request->tahunId == null) {
            $tahunNow = Date('Y');
            $tahun = Tahun::where('nama', $tahunNow)->get();
            $tahunId = $tahun[0]->id;
        } else {
            $tahunId = $request->tahunId;
        }

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
            unset($debitArr, $debitArr2, $kreditArr, $kreditArr2, $saldoArr, $saldoArr2,);
        }

        $saldo = array();
        foreach ($perbulans as $value) {
            $saldo[] = $value->saldo;
        }

        if ($saldo == null) {
            $totalSaldo = 0;
        } else {
            $totalSaldo = array_sum($saldo);
        }

        $tahun = Tahun::where('id', $tahunId)->select('nama')->get();
        $tahunNama = $tahun[0]->nama;

        return view(
            'keuangan.perbulan',
            compact(
                'pertahuns',
                'perbulans',

                'totalSaldo',

                'tahunId',
                'tahunNama',
            )
        );
    }

    // PERHARI

    public function perhari(Request $request)
    {
        $pertahuns = Tahun::get();
        $perbulans = Bulan::get();
        $perharies = Hari::get();

        if ($request->tahunId == null) {
            $tahunNow = Date('Y');
            $tahun = Tahun::where('nama', $tahunNow)->get();
            $tahunId = $tahun[0]->id;
        } else {
            $tahunId = $request->tahunId;
        }

        if ($request->bulanId == null) {
            $bulanNow = Date('n');
            $bulan = Bulan::where('id', $bulanNow)->get();
            $bulanId = $bulan[0]->id;
        } else {
            $bulanId = $request->bulanId;
        }

        for ($j = 0; $j < sizeof($perharies); $j++) {
            $pernotas = Nota::where('tahun_id', $tahunId)
                ->where('bulan_id', $bulanId)
                ->where('hari_id', $perharies[$j]->id)->get();

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

            $perharies[$j]->debit = array_sum($debitArr2);
            $perharies[$j]->kredit = array_sum($kreditArr2);
            $perharies[$j]->saldo = array_sum($saldoArr2);
            unset($debitArr, $debitArr2, $kreditArr, $kreditArr2, $saldoArr, $saldoArr2,);
        }

        $saldo = array();
        foreach ($perharies as $value) {
            $saldo[] = $value->saldo;
        }

        if ($saldo == null) {
            $totalSaldo = 0;
        } else {
            $totalSaldo = array_sum($saldo);
        }

        $tahun = Tahun::where('id', $tahunId)->select('nama')->get();
        $tahunNama = $tahun[0]->nama;

        $bulan = Bulan::where('id', $bulanId)->select('nama')->get();
        $bulanNama = $bulan[0]->nama;

        return view(
            'keuangan.perhari',
            compact(
                'pertahuns',
                'perbulans',
                'perharies',

                'totalSaldo',

                'tahunId',
                'bulanId',

                'tahunNama',
                'bulanNama',
            )
        );
    }

    // LOKASI

    public function perlokasi(Request $request)
    {
        $pertahuns = Tahun::get();
        $perbulans = Bulan::get();
        $perharies = Hari::get();
        $perlokasies = Lokasi::get();

        if ($request->tahunId == null) {
            $tahunId = null;
        } else {
            $tahunId = $request->tahunId;
        }

        if ($request->bulanId == null) {
            $bulanId = null;
        } else {
            $bulanId = $request->bulanId;
        }

        if ($request->hariId == null) {
            $hariId = null;
        } else {
            $hariId = $request->hariId;
        }

        if ($tahunId != null && $bulanId != null && $hariId != null) {
            for ($j = 0; $j < sizeof($perlokasies); $j++) {
                $pernotas = Nota::where('tahun_id', $tahunId)
                    ->where('bulan_id', $bulanId)
                    ->where('hari_id', $hariId)
                    ->where('lokasi_id', $perlokasies[$j]->id)->get();

                $debitArr = $debitArr2 = array();
                $kreditArr = $kreditArr2 = array();
                $saldoArr = $saldoArr2 = array();

                for ($k = 0; $k < sizeof($pernotas); $k++) {
                    for ($l = 0; $l < sizeof($pernotas[$k]->details); $l++) {
                        if ($pernotas[$k]->details[$l]->transaksies->nama == "debit") {
                            $pernotas[$k]->details[$l]->saldo = $pernotas[$k]->details[$l]->hargas->harga * $pernotas[$k]->details[$l]->kuantitas;
                            $pernotas[$k]->details[$l]->debit = $pernotas[$k]->details[$l]->hargas->harga * $pernotas[$k]->details[$l]->kuantitas;
                            $pernotas[$k]->details[$l]->kredit = 0;
                        } else if ($pernotas[$k]->details[$l]->transaksies->nama == "kredit") {
                            $pernotas[$k]->details[$l]->saldo = - ($pernotas[$k]->details[$l]->hargas->harga) * $pernotas[$k]->details[$l]->kuantitas;
                            $pernotas[$k]->details[$l]->debit = 0;
                            $pernotas[$k]->details[$l]->kredit = - ($pernotas[$k]->details[$l]->hargas->harga) * $pernotas[$k]->details[$l]->kuantitas;
                        } else if ($pernotas[$k]->details == null) {
                            $pernotas[$k]->details[$l]->saldo = 0;
                            $pernotas[$k]->details[$l]->debit = 0;
                            $pernotas[$k]->details[$l]->kredit = 0;
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

                $perlokasies[$j]->debit = array_sum($debitArr2);
                $perlokasies[$j]->kredit = array_sum($kreditArr2);
                $perlokasies[$j]->saldo = array_sum($saldoArr2);

                unset($debitArr, $debitArr2, $kreditArr, $kreditArr2, $saldoArr, $saldoArr2,);
            }
        } else if ($tahunId != null && $bulanId != null && $hariId == null) {
            for ($j = 0; $j < sizeof($perlokasies); $j++) {
                $pernotas = Nota::where('tahun_id', $tahunId)
                    ->where('bulan_id', $bulanId)
                    ->where('lokasi_id', $perlokasies[$j]->id)->get();

                $debitArr = $debitArr2 = array();
                $kreditArr = $kreditArr2 = array();
                $saldoArr = $saldoArr2 = array();

                for ($k = 0; $k < sizeof($pernotas); $k++) {
                    for ($l = 0; $l < sizeof($pernotas[$k]->details); $l++) {
                        if ($pernotas[$k]->details[$l]->transaksies->nama == "debit") {
                            $pernotas[$k]->details[$l]->saldo = $pernotas[$k]->details[$l]->hargas->harga * $pernotas[$k]->details[$l]->kuantitas;
                            $pernotas[$k]->details[$l]->debit = $pernotas[$k]->details[$l]->hargas->harga * $pernotas[$k]->details[$l]->kuantitas;
                            $pernotas[$k]->details[$l]->kredit = 0;
                        } else {
                            $pernotas[$k]->details[$l]->saldo = - ($pernotas[$k]->details[$l]->hargas->harga) * $pernotas[$k]->details[$l]->kuantitas;
                            $pernotas[$k]->details[$l]->debit = 0;
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

                $perlokasies[$j]->debit = array_sum($debitArr2);
                $perlokasies[$j]->kredit = array_sum($kreditArr2);
                $perlokasies[$j]->saldo = array_sum($saldoArr2);

                unset($debitArr, $debitArr2, $kreditArr, $kreditArr2, $saldoArr, $saldoArr2,);
            }
        } else if ($tahunId != null && $bulanId == null && $hariId == null) {
            for ($j = 0; $j < sizeof($perlokasies); $j++) {
                $pernotas = Nota::where('tahun_id', $tahunId)
                    ->where('lokasi_id', $perlokasies[$j]->id)->get();

                if (sizeof($pernotas) == 0) {
                    $pernotas = collect();
                }

                $debitArr = $debitArr2 = array();
                $kreditArr = $kreditArr2 = array();
                $saldoArr = $saldoArr2 = array();

                for ($k = 0; $k < sizeof($pernotas); $k++) {
                    for ($l = 0; $l < sizeof($pernotas[$k]->details); $l++) {
                        if ($pernotas[$k]->details[$l]->transaksies->nama == "debit") {
                            $pernotas[$k]->details[$l]->saldo = $pernotas[$k]->details[$l]->hargas->harga * $pernotas[$k]->details[$l]->kuantitas;
                            $pernotas[$k]->details[$l]->debit = $pernotas[$k]->details[$l]->hargas->harga * $pernotas[$k]->details[$l]->kuantitas;
                            $pernotas[$k]->details[$l]->kredit = 0;
                        } else {
                            $pernotas[$k]->details[$l]->saldo = - ($pernotas[$k]->details[$l]->hargas->harga) * $pernotas[$k]->details[$l]->kuantitas;
                            $pernotas[$k]->details[$l]->debit = 0;
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

                $perlokasies[$j]->debit = array_sum($debitArr2);
                $perlokasies[$j]->kredit = array_sum($kreditArr2);
                $perlokasies[$j]->saldo = array_sum($saldoArr2);

                unset($debitArr, $debitArr2, $kreditArr, $kreditArr2, $saldoArr, $saldoArr2,);
            }
        } else if ($tahunId == null && $bulanId != null && $hariId != null) {
            for ($j = 0; $j < sizeof($perlokasies); $j++) {
                $pernotas = Nota::where('bulan_id', $bulanId)
                    ->where('hari_id', $hariId)
                    ->where('lokasi_id', $perlokasies[$j]->id)->get();

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

                $perlokasies[$j]->debit = array_sum($debitArr2);
                $perlokasies[$j]->kredit = array_sum($kreditArr2);
                $perlokasies[$j]->saldo = array_sum($saldoArr2);

                unset($debitArr, $debitArr2, $kreditArr, $kreditArr2, $saldoArr, $saldoArr2,);
            }
        } else if ($tahunId == null && $bulanId == null && $hariId != null) {
            for ($j = 0; $j < sizeof($perlokasies); $j++) {
                $pernotas = Nota::where('hari_id', $hariId)
                    ->where('lokasi_id', $perlokasies[$j]->id)->get();

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

                $perlokasies[$j]->debit = array_sum($debitArr2);
                $perlokasies[$j]->kredit = array_sum($kreditArr2);
                $perlokasies[$j]->saldo = array_sum($saldoArr2);

                unset($debitArr, $debitArr2, $kreditArr, $kreditArr2, $saldoArr, $saldoArr2,);
            }
        } else if ($tahunId == null && $bulanId != null && $hariId == null) {
            for ($j = 0; $j < sizeof($perlokasies); $j++) {
                $pernotas = Nota::where('bulan_id', $bulanId)
                    ->where('lokasi_id', $perlokasies[$j]->id)->get();

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

                $perlokasies[$j]->debit = array_sum($debitArr2);
                $perlokasies[$j]->kredit = array_sum($kreditArr2);
                $perlokasies[$j]->saldo = array_sum($saldoArr2);

                unset($debitArr, $debitArr2, $kreditArr, $kreditArr2, $saldoArr, $saldoArr2,);
            }
        } else if ($tahunId != null && $bulanId == null && $hariId != null) {
            for ($j = 0; $j < sizeof($perlokasies); $j++) {
                $pernotas = Nota::where('tahun_id', $tahunId)
                    ->where('hari_id', $hariId)
                    ->where('lokasi_id', $perlokasies[$j]->id)->get();

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

                $perlokasies[$j]->debit = array_sum($debitArr2);
                $perlokasies[$j]->kredit = array_sum($kreditArr2);
                $perlokasies[$j]->saldo = array_sum($saldoArr2);

                unset($debitArr, $debitArr2, $kreditArr, $kreditArr2, $saldoArr, $saldoArr2,);
            }
        } else {
            $perlokasies[0]->debit = 0;
            $perlokasies[0]->kredit = 0;
            $perlokasies[0]->saldo = 0;
        }

        $saldo = array();
        foreach ($perlokasies as $value) {
            $saldo[] = $value->saldo;
        }

        if ($saldo == null) {
            $totalSaldo = 0;
        } else {
            $totalSaldo = array_sum($saldo);
        }

        if ($tahunId == null) {
            $tahunNama = "kosong";
        } else {
            $tahun = Tahun::where('id', $tahunId)->select('nama')->get();
            $tahunNama = $tahun[0]->nama;
        }

        if ($bulanId == null) {
            $bulanNama = "kosong";
        } else {
            $bulan = Bulan::where('id', $bulanId)->select('nama')->get();
            $bulanNama = $bulan[0]->nama;
        }

        if ($hariId == null) {
            $hariNama = "kosong";
        } else {
            $hari = Hari::where('id', $hariId)->select('nama')->get();
            $hariNama = $hari[0]->nama;
        }

        return view(
            'keuangan.perlokasi',
            compact(
                'pertahuns',
                'perbulans',
                'perharies',
                'perlokasies',

                'totalSaldo',

                'tahunId',
                'bulanId',
                'hariId',

                'tahunNama',
                'bulanNama',
                'hariNama',
            )
        );
    }

    // REKAP
    public function rekap(Request $request)
    {
        $perlokasies = Lokasi::get();
        $pertahuns = Tahun::get();
        $perbulans = Bulan::get();
        $perharies = Hari::get();

        if ($request->lokasiId == null) {
            $lokasiId = 1;
        } else {
            $lokasiId = $request->lokasiId;
        }

        if ($request->tahunId == null) {
            $tahunNow = Date('Y');
            $tahun = Tahun::where('nama', $tahunNow)->get();
            $tahunId = $tahun[0]->id;
        } else {
            $tahunId = $request->tahunId;
        }

        if ($request->bulanId == null) {
            $bulanNow = Date('n');
            $bulan = Bulan::where('id', $bulanNow)->get();
            $bulanId = $bulan[0]->id;
        } else {
            $bulanId = $request->bulanId;
        }

        for ($j = 0; $j < sizeof($perharies); $j++) {
            $pernotas = Nota::where('lokasi_id', $lokasiId)
                ->where('tahun_id', $tahunId)
                ->where('bulan_id', $bulanId)
                ->where('hari_id', $perharies[$j]->id)->get();

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

            $perharies[$j]->debit = array_sum($debitArr2);
            $perharies[$j]->kredit = array_sum($kreditArr2);
            $perharies[$j]->saldo = array_sum($saldoArr2);
            unset($debitArr, $debitArr2, $kreditArr, $kreditArr2, $saldoArr, $saldoArr2,);
        }

        $saldo = array();
        foreach ($perharies as $value) {
            $saldo[] = $value->saldo;
        }

        if ($saldo == null) {
            $totalSaldo = 0;
        } else {
            $totalSaldo = array_sum($saldo);
        }

        $lokasi = Lokasi::where('id', $lokasiId)->select('nama')->get();
        $lokasiNama = $lokasi[0]->nama;

        $tahun = Tahun::where('id', $tahunId)->select('nama')->get();
        $tahunNama = $tahun[0]->nama;

        $bulan = Bulan::where('id', $bulanId)->select('nama')->get();
        $bulanNama = $bulan[0]->nama;

        return view(
            'keuangan.rekap',
            compact(
                'perlokasies',
                'pertahuns',
                'perbulans',
                'perharies',

                'totalSaldo',

                'lokasiId',
                'tahunId',
                'bulanId',

                'lokasiNama',
                'tahunNama',
                'bulanNama',
            )
        );
    }

    // IMPORT EXCEL
    public function notaImport()
    {
        Excel::import(new NotaImport, request()->file('file'));
        return redirect()->back();
    }

    public function detailImport()
    {
        Excel::import(new DetailImport, request()->file('file'));
        return redirect()->back();
    }

    // AUTOCOMPLETE SEARCH TAMBAH NAMA BARANG
    public function autocompleteSearch(Request $request)
    {
        $query = $request->get('query');
        $filterResult = Barang::where('nama', 'LIKE', '%' . $query . '%')->get();
        $data = array();
        foreach ($filterResult as $value) {
            $data[] = $value->nama;
        }
        return response()->json($data);
    }
}
