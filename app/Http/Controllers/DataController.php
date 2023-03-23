<?php

namespace App\Http\Controllers;

use App\Imports\BarangImport;
use App\Imports\TahunImport;
use App\Imports\LokasiImport;
use App\Imports\TransaksiImport;
use App\Imports\SatuanImport;
use App\Imports\VarietasImport;
use App\Imports\KeteranganBibitImport;
use App\Imports\MasukBibitImport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\User;
use App\Models\Tahun;
use App\Models\Bulan;
use App\Models\Hari;
use App\Models\Lokasi;
use App\Models\Tempat;
use App\Models\Satuan;
use App\Models\Barang;
use App\Models\Nota;
use App\Models\Detail;
use App\Models\KeteranganBibit;
use App\Models\Varietas;
use App\Models\Stok;
use App\Models\StokDetail;
use App\Models\MasukBibit;
use App\Models\Transaksi;
use App\Models\Jenis;
use Arr;
use Date;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx\Rels;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use Illuminate\Support\Str;

class DataController extends Controller
{
    // menu
    public function index()
    {
        return view('data.menu');
    }

    // tahun
    public function tahun()
    {
        $tahuns = array();
        $pertahuns = Tahun::all();

        return view(
            'data.tahun.tahun',
            compact(
                'tahuns',
                'pertahuns',
            )
        );
    }

    public function tahunTambah()
    {
        $tahunNow = Date('Y');
        return view(
            'data.tahun.tahun-tambah',
            compact(
                'tahunNow',
            )
        );
    }

    public function tahunSimpan(Request $request)
    {
        $tahun = $request->tahun;
        $tahunCek = Tahun::where('nama', $tahun)->get();

        if ($tahunCek->count() == 0) {
            DB::table('tahuns')->insert([
                'nama' => $tahun,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        } else {
            $tahunNow = Date('Y');
            return view(
                'data.tahun.tahun-tambah',
                compact(
                    'tahunNow',
                )
            );
        }

        $tahuns = array();
        $pertahuns = Tahun::all();

        return view(
            'data.tahun.tahun',
            compact(
                'tahuns',
                'pertahuns',
            )
        );
    }

    public function tahunEdit($id)
    {
        $tahunId = $id;

        $tahuns = Tahun::where('id', $tahunId)->get();
        $pertahuns = Tahun::all();

        return view(
            'data.tahun.tahun',
            compact(
                'tahuns',
                'pertahuns',
            )
        );
    }

    public function tahunUpdate(Request $request, $id)
    {
        $tahunId = $id;
        $tahun = $request->tahun;

        $tahunCek = Tahun::where('nama', $tahun)->get();

        if ($tahunCek->count() == 0) {
            DB::table('tahuns')->where('id', $tahunId)->update([
                'nama' => $tahun,
                'updated_at' => now(),
            ]);
        } else {
            $tahuns = Tahun::where('id', $tahunId)->get();
            $pertahuns = Tahun::all();

            return view(
                'data.tahun.tahun',
                compact(
                    'tahuns',
                    'pertahuns',
                )
            );
        }

        $tahuns = array();
        $pertahuns = Tahun::all();

        return view(
            'data.tahun.tahun',
            compact(
                'tahuns',
                'pertahuns',
            )
        );
    }

    public function tahunHapus($id)
    {
        $tahunId = $id;
        DB::table('tahuns')->where('id', $tahunId)->delete();

        $tahuns = array();
        $pertahuns = Tahun::all();

        return view(
            'data.tahun.tahun',
            compact(
                'tahuns',
                'pertahuns',
            )
        );
    }

    public function tahunImport()
    {
        Excel::import(new TahunImport, request()->file('file'));
        return redirect()->back();
    }

    // lokasi
    public function lokasi()
    {
        $lokasies = array();
        $perlokasies = Lokasi::all();

        return view(
            'data.lokasi.lokasi',
            compact(
                'lokasies',
                'perlokasies',
            )
        );
    }

    public function lokasiTambah()
    {
        return view('data.lokasi.lokasi-tambah');
    }

    public function lokasiSimpan(Request $request)
    {
        $lokasi = $request->lokasi;
        $lokasiCek = Lokasi::where('nama', $lokasi)->get();

        if ($lokasiCek->count() == 0) {
            DB::table('lokasies')->insert([
                'nama' => $lokasi,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        } else {
            return view('data.lokasi.lokasi-tambah');
        }

        $lokasies = array();
        $perlokasies = Lokasi::all();

        return view(
            'data.lokasi.lokasi',
            compact(
                'lokasies',
                'perlokasies',
            )
        );
    }

    public function lokasiEdit($id)
    {
        $lokasiId = $id;

        $lokasies = Lokasi::where('id', $lokasiId)->get();
        $perlokasies = Lokasi::all();

        return view(
            'data.lokasi.lokasi',
            compact(
                'lokasies',
                'perlokasies',
            )
        );
    }

    public function lokasiUpdate(Request $request, $id)
    {
        $lokasiId = $id;
        $lokasi = $request->lokasi;
        $lokasiCek = Lokasi::where('nama', $lokasi)->get();

        if ($lokasiCek->count() == 0) {
            DB::table('lokasies')->where('id', $lokasiId)->update([
                'nama' => $lokasi,
                'updated_at' => now(),
            ]);
        } else {
            $lokasies = Lokasi::where('id', $lokasiId)->get();
            $perlokasies = Lokasi::all();

            return view(
                'data.lokasi.lokasi',
                compact(
                    'lokasies',
                    'perlokasies',
                )
            );
        }

        $lokasies = array();
        $perlokasies = Lokasi::all();

        return view(
            'data.lokasi.lokasi',
            compact(
                'lokasies',
                'perlokasies',
            )
        );
    }

    public function lokasiHapus($id)
    {
        $lokasiId = $id;
        DB::table('lokasies')->where('id', $lokasiId)->delete();

        $lokasies = array();
        $perlokasies = Lokasi::all();

        return view(
            'data.lokasi.lokasi',
            compact(
                'lokasies',
                'perlokasies',
            )
        );
    }

    public function lokasiImport()
    {
        Excel::import(new LokasiImport, request()->file('file'));
        return redirect()->back();
    }

    // barang
    public function barang()
    {
        $barang = array();
        $perbarangs = Barang::get();

        return view(
            'data.barang.barang',
            compact(
                'barang',
                'perbarangs',
            )
        );
    }

    public function barangTambah()
    {
        return view('data.barang.barang-tambah');
    }

    public function barangSimpan(Request $request)
    {
        $nama = $request->nama;

        DB::table('barangs')->insert([
            'nama' => $nama,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $barang = array();
        $perbarangs = Barang::get();

        return view(
            'data.barang.barang',
            compact(
                'barang',
                'perbarangs',
            )
        );
    }

    public function barangEdit($id)
    {
        $barangId = $id;

        $barang = Barang::where('id', $barangId)->get();
        $perbarangs = Barang::get();

        return view(
            'data.barang.barang',
            compact(
                'barang',
                'perbarangs',
            )
        );
    }

    public function barangUpdate(Request $request, $id)
    {
        $barangId = $id;
        $nama = $request->nama;

        DB::table('barangs')->where('id', $barangId)->update([
            'nama' => $nama,
            'updated_at' => now(),
        ]);

        $barang = array();
        $perbarangs = Barang::get();

        return view(
            'data.barang.barang',
            compact(
                'barang',
                'perbarangs',
            )
        );
    }

    public function barangHapus($id)
    {
        $barangId = $id;
        DB::table('barangs')->where('id', $barangId)->delete();

        $barang = array();
        $perbarangs = Barang::get();

        return view(
            'data.barang.barang',
            compact(
                'barang',
                'perbarangs',
            )
        );
    }

    public function barangImport()
    {
        Excel::import(new BarangImport, request()->file('file'));
        return redirect()->back();
    }

    // transaksi
    public function transaksi()
    {
        $transaksies = array();
        $pertransaksies = Transaksi::get();

        return view(
            'data.transaksi.transaksi',
            compact(
                'transaksies',
                'pertransaksies',
            )
        );
    }

    public function transaksiTambah()
    {
        return view('data.transaksi.transaksi-tambah');
    }

    public function transaksiSimpan(Request $request)
    {
        $transaksi = $request->transaksi;

        DB::table('transaksies')->insert([
            'nama' => $transaksi,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $transaksies = array();
        $pertransaksies = Transaksi::get();

        return view(
            'data.transaksi.transaksi',
            compact(
                'transaksies',
                'pertransaksies',
            )
        );
    }

    public function transaksiEdit($id)
    {
        $transaksiId = $id;
        $transaksies = Transaksi::where('id', $transaksiId)->get();

        return view(
            'data.transaksi.transaksi',
            compact(
                'transaksies',
            )
        );
    }

    public function transaksiUpdate(Request $request, $id)
    {
        $transaksiId = $id;
        $transaksi = $request->transaksi;
        $transaksiCek = Transaksi::where('nama', $transaksi)->get();

        if ($transaksiCek->count() == 0) {
            DB::table('transaksies')->where('id', $transaksiId)->update([
                'nama' => $transaksi,
                'updated_at' => now(),
            ]);
        } else {
            $transaksies = Transaksi::where('id', $transaksiId)->get();
            $pertransaksies = Transaksi::all();

            return view(
                'data.transaksi.transaksi',
                compact(
                    'transaksies',
                    'pertransaksies',
                )
            );
        }

        $transaksies = array();
        $pertransaksies = Transaksi::get();

        return view(
            'data.transaksi.transkasi',
            compact(
                'transaksies',
                'pertransaksies',
            )
        );
    }

    public function transaksiHapus($id)
    {
        $transaksiId = $id;
        DB::table('transaksies')->where('id', $transaksiId)->delete();

        $transaksies = array();
        $pertransaksies = Transaksi::all();

        return view(
            'data.transaksi.transaksi',
            compact(
                'transaksies',
                'pertransaksies',
            )
        );
    }

    public function transaksiImport()
    {
        Excel::import(new TransaksiImport, request()->file('file'));
        return redirect()->back();
    }

    // satuan
    public function satuan()
    {
        $satuans = array();
        $persatuans = Satuan::all();

        return view(
            'data.satuan.satuan',
            compact(
                'satuans',
                'persatuans',
            )
        );
    }

    public function satuanTambah()
    {
        return view('data.satuan.satuan-tambah');
    }

    public function satuanSimpan(Request $request)
    {
        $satuan = $request->satuan;
        $satuanCek = Satuan::where('nama', $satuan)->get();

        if ($satuanCek->count() == 0) {
            DB::table('satuans')->insert([
                'nama' => $satuan,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        } else {
            return view('data.satuan.satuan-tambah');
        }

        $satuans = array();
        $persatuans = Satuan::all();

        return view(
            'data.satuan.satuan',
            compact(
                'satuans',
                'persatuans',
            )
        );
    }

    public function satuanEdit($id)
    {
        $satuanId = $id;

        $satuans = Satuan::where('id', $satuanId)->get();
        $persatuans = Satuan::all();

        return view(
            'data.satuan.satuan',
            compact(
                'satuans',
                'persatuans',
            )
        );
    }

    public function satuanUpdate(Request $request, $id)
    {
        $satuanId = $id;
        $satuan = $request->satuan;
        $satuanCek = Satuan::where('nama', $satuan)->get();

        if ($satuanCek->count() == 0) {
            DB::table('satuans')->where('id', $satuanId)->update([
                'nama' => $satuan,
                'updated_at' => now(),
            ]);
        } else {
            $satuans = Satuan::where('id', $satuanId)->get();
            $persatuans = Satuan::all();

            return view(
                'data.satuan.satuan',
                compact(
                    'satuans',
                    'persatuans',
                )
            );
        }

        $satuans = array();
        $persatuans = Satuan::all();

        return view(
            'data.satuan.satuan',
            compact(
                'satuans',
                'persatuans',
            )
        );
    }

    public function satuanHapus($id)
    {
        $satuanId = $id;
        DB::table('satuans')->where('id', $satuanId)->delete();

        $satuans = array();
        $persatuans = Satuan::all();

        return view(
            'data.satuan.satuan',
            compact(
                'satuans',
                'persatuans',
            )
        );
    }

    public function satuanImport()
    {
        Excel::import(new SatuanImport, request()->file('file'));
        return redirect()->back();
    }

    // varietas
    public function varietas()
    {
        $varietases = array();
        $pervarietases = Varietas::all();

        return view(
            'data.varietas.varietas',
            compact(
                'varietases',
                'pervarietases',
            )
        );
    }

    public function varietasTambah()
    {
        return view('data.varietas.varietas-tambah',);
    }

    public function varietasSimpan(Request $request)
    {
        $varietas = $request->varietas;
        $penyedia = $request->penyedia;
        $varietasCek = Varietas::where('nama', $varietas)->get();

        if ($varietasCek->count() == 0) {
            DB::table('varietases')->insert([
                'nama' => $varietas,
                'penyedia' => $penyedia,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        } else {
            return view('data.varietas.varietas-tambah');
        }

        $varietases = array();
        $pervarietases = Varietas::all();

        return view(
            'data.varietas.varietas',
            compact(
                'varietases',
                'pervarietases',
            )
        );
    }

    public function varietasEdit($id)
    {
        $varietasId = $id;

        $varietases = Varietas::where('id', $varietasId)->get();
        $pervarietases = Varietas::all();

        return view(
            'data.varietas.varietas',
            compact(
                'varietases',
                'pervarietases',
            )
        );
    }

    public function varietasUpdate(Request $request, $id)
    {
        $varietasId = $id;
        $varietas = $request->varietas;
        $penyedia = $request->penyedia;

        $varietasCek = Varietas::where('nama', $varietas)->get();
        $penyediaCek = Varietas::where('penyedia', $penyedia)->get();

        if ($varietasCek->count() == 0 && $penyediaCek->count() == 0) {
            DB::table('varietases')->where('id', $varietasId)->update([
                'nama' => $varietas,
                'penyedia' => $penyedia,
                'updated_at' => now(),
            ]);
        } else if ($varietasCek->count() != 0 && $penyediaCek->count() == 0) {
            DB::table('varietases')->where('id', $varietasId)->update([
                'nama' => $varietas,
                'penyedia' => $penyedia,
                'updated_at' => now(),
            ]);
        } else if ($varietasCek->count() == 0 && $penyediaCek->count() != 0) {
            DB::table('varietases')->where('id', $varietasId)->update([
                'nama' => $varietas,
                'penyedia' => $penyedia,
                'updated_at' => now(),
            ]);
        } else {
            $varietases = Varietas::where('id', $varietasId)->get();
            $pervarietases = Varietas::all();

            return view(
                'data.varietas.varietas',
                compact(
                    'varietases',
                    'pervarietases'
                )
            );
        }

        $varietases = array();
        $pervarietases = Varietas::all();

        return view(
            'data.varietas.varietas',
            compact(
                'varietases',
                'pervarietases',
            )
        );
    }

    public function varietasHapus($id)
    {
        $varietasId = $id;
        DB::table('varietases')->where('id', $varietasId)->delete();

        $varietases = array();
        $pervarietases = Varietas::all();

        return view(
            'data.varietas.varietas',
            compact(
                'varietases',
                'pervarietases'
            )
        );
    }

    public function varietasImport()
    {
        Excel::import(new VarietasImport, request()->file('file'));
        return redirect()->back();
    }

    // keterangan bibit
    public function keteranganBibit()
    {
        $keteranganBibits = array();
        $perketeranganBibits = KeteranganBibit::all();

        return view(
            'data.keteranganBibit.keteranganBibit',
            compact(
                'keteranganBibits',
                'perketeranganBibits',
            )
        );
    }

    public function keteranganBibitTambah()
    {
        return view('data.keteranganBibit.keteranganBibit-tambah',);
    }

    public function keteranganBibitSimpan(Request $request)
    {
        $keteranganBibit = $request->keteranganBibit;
        $keteranganBibitCek = KeteranganBibit::where('nama', $keteranganBibit)->get();

        if ($keteranganBibitCek->count() == 0) {
            DB::table('keterangan_bibits')->insert([
                'nama' => $keteranganBibit,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        } else {
            return view('data.keteranganBibit.keteranganBibit-tambah');
        }

        $keteranganBibits = array();
        $perketeranganBibits = KeteranganBibit::all();

        return view(
            'data.keteranganBibit.keteranganBibit',
            compact(
                'keteranganBibits',
                'perketeranganBibits',
            )
        );
    }

    public function keteranganBibitEdit($id)
    {
        $keteranganBibitId = $id;

        $keteranganBibits = KeteranganBibit::where('id', $keteranganBibitId)->get();
        $perketeranganBibits = KeteranganBibit::all();

        return view(
            'data.keteranganBibit.keteranganBibit',
            compact(
                'keteranganBibits',
                'perketeranganBibits',
            )
        );
    }

    public function keteranganBibitUpdate(Request $request, $id)
    {
        $keteranganBibitId = $id;
        $keteranganBibit = $request->keteranganBibit;

        $keteranganBibitCek = KeteranganBibit::where('nama', $keteranganBibit)->get();

        if ($keteranganBibitCek->count() == 0) {
            DB::table('keterangan_bibits')->where('id', $keteranganBibitId)->update([
                'nama' => $keteranganBibit,
                'updated_at' => now(),
            ]);
        } else {
            $keteranganBibits = KeteranganBibit::where('id', $keteranganBibitId)->get();
            $perketeranganBibits = KeteranganBibit::all();

            return view(
                'data.keteranganBibit.keteranganBibit',
                compact(
                    'keteranganBibits',
                    'perketeranganBibits',
                )
            );
        }

        $keteranganBibits = array();
        $perketeranganBibits = KeteranganBibit::all();

        return view(
            'data.keteranganBibit.keteranganBibit',
            compact(
                'keteranganBibits',
                'perketeranganBibits',
            )
        );
    }

    public function keteranganBibitHapus($id)
    {
        $keteranganBibitId = $id;
        DB::table('keterangan_bibits')->where('id', $keteranganBibitId)->delete();

        $keteranganBibits = array();
        $perketeranganBibits = KeteranganBibit::all();

        return view(
            'data.keteranganBibit.keteranganBibit',
            compact(
                'keteranganBibits',
                'perketeranganBibits',
            )
        );
    }

    public function keteranganBibitImport()
    {
        Excel::import(new KeteranganBibitImport, request()->file('file'));
        return redirect()->back();
    }

    // masuk bibit
    public function masukBibit()
    {
        $masukBibits = array();
        $permasukanBibits = MasukBibit::get();

        return view(
            'data.masukBibit.masukBibit',
            compact(
                'masukBibits',
                'permasukanBibits',
            )
        );
    }

    public function masukBibitTambah()
    {
        return view('data.masukBibit.masukBibit-tambah',);
    }

    public function masukBibitSimpan(Request $request)
    {
        $masukValue1 = $request->masuk;
        $masukValue = Str::replace('T', ' ', $masukValue1);
        $masukBibitCek = MasukBibit::where('masuk', $masukValue)->get();

        if ($masukBibitCek->count() == 0) {
            DB::table('masuk_bibits')->insert([
                'masuk' => $masukValue,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        } else {
            return view('data.keteranganBibit.keteranganBibit-tambah');
        }

        $masukBibits = array();
        $permasukanBibits = MasukBibit::get();

        return view(
            'data.masukBibit.masukBibit',
            compact(
                'masukBibits',
                'permasukanBibits',
            )
        );
    }

    public function masukBibitEdit($id)
    {
        $masukBibitId = $id;

        $masukBibits = MasukBibit::where('id', $masukBibitId)->get();
        $permasukanBibits = MasukBibit::get();

        return view(
            'data.masukBibit.masukBibit',
            compact(
                'masukBibits',
                'permasukanBibits',
            )
        );
    }

    public function masukBibitUpdate(Request $request, $id)
    {
        $masukBibitId = $id;
        $masukValue1 = $request->masuk;
        $masukValue = Str::replace('T', ' ', $masukValue1);
        $masukBibitCek = MasukBibit::where('masuk', $masukValue)->get();

        if ($masukBibitCek->count() == 0) {
            DB::table('masuk_bibits')->where('id', $masukBibitId)->update([
                'masuk' => $masukValue,
                'updated_at' => now(),
            ]);
        } else {
            $masukBibits = MasukBibit::where('id', $masukBibitId)->get();
            $permasukanBibits = MasukBibit::get();

            return view(
                'data.masukBibit.masukBibit',
                compact(
                    'masukBibits',
                    'permasukanBibits',
                )
            );
        }

        $masukBibits = array();
        $permasukanBibits = MasukBibit::get();

        return view(
            'data.masukBibit.masukBibit',
            compact(
                'masukBibits',
                'permasukanBibits',
            )
        );
    }

    public function masukBibitHapus($id)
    {
        $masukBibitId = $id;
        DB::table('masuk_bibits')->where('id', $masukBibitId)->delete();

        $masukBibits = array();
        $permasukanBibits = MasukBibit::get();

        return view(
            'data.masukBibit.masukBibit',
            compact(
                'masukBibits',
                'permasukanBibits',
            )
        );
    }

    public function masukBibitImport()
    {
        Excel::import(new MasukBibitImport, request()->file('file'));
        return redirect()->back();
    }

    // user
    public function user()
    {
        $users = array();
        $perusers = User::all();

        return view(
            'data.user.user',
            compact(
                'users',
                'perusers',
            )
        );
    }

    public function userEdit($id)
    {
        $userId = $id;

        $users = User::where('id', $userId)->get();
        $perusers = User::all();

        return view(
            'data.user.user',
            compact(
                'users',
                'perusers',
            )
        );
    }

    public function userUpdate(Request $request, $id)
    {
        $userId = $id;
        $name = $request->name;
        $role = $request->role;
        $password = $request->password;

        $nameCek = User::where('name', $name)->get();

        if ($nameCek->count() == 0) {
            DB::table('users')->where('id', $userId)->update([
                'name' => $name,
                'role' => $role,
                'password' => hash::make($password),
                'updated_at' => now(),
            ]);
        } else {
            $users = User::where('id', $userId)->get();
            $perusers = User::all();

            return view(
                'data.user.user',
                compact(
                    'users',
                    'perusers',
                )
            );
        }

        $users = array();
        $perusers = User::all();

        return view(
            'data.user.user',
            compact(
                'users',
                'perusers',
            )
        );
    }

    public function jenis()
    {
        $jenises = array();
        $perjenises = Jenis::get();

        return view(
            'data.jenis.jenis',
            compact(
                'jenises',
                'perjenises',
            )
        );
    }

    public function jenisTambah()
    {
        return view('data.jenis.jenis-tambah',);
    }

    public function jenisSimpan(Request $request)
    {
        $jenisNama = $request->nama;
        $jenisFind = Jenis::where('jenis', $jenisNama)->get();

        if ($jenisFind->count() == 0) {
            DB::table('jenises')->insert([
                'nama' => $jenisNama,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        } else {
            return view('data.jenis.jenis-tambah');
        }

        $jenises = array();
        $perjenises = Jenis::get();

        return view(
            'data.jenis.jenis',
            compact(
                'jenises',
                'perjenises',
            )
        );
    }

    public function jenisEdit($id)
    {
        $jenisId = $id;

        $jenises = Jenis::where('id', $jenisId)->get();
        $perjenises = Jenis::get();

        return view(
            'data.jenis.jenis',
            compact(
                'jenises',
                'perjenises',
            )
        );
    }

    public function jenisUpdate(Request $request, $id)
    {
        $jenisId = $id;
        $jenisNama = $request->nama;
        $jenisFind = Jenis::where('nama', $jenisNama)->get();

        if ($jenisFind->count() == 0) {
            DB::table('jenises')->where('id', $jenisId)->update([
                'masuk' => $jenisNama,
                'updated_at' => now(),
            ]);
        } else {
            $jenises = Jenis::where('id', $jenisId)->get();
            $perjenises = Jenis::get();

            return view(
                'data.jenis.jenis',
                compact(
                    'jenises',
                    'perjenises',
                )
            );
        }

        $jenises = array();
        $perjenises = Jenis::get();

        return view(
            'data.jenis.jenis',
            compact(
                'jenises',
                'perjenises',
            )
        );
    }

    public function jenisHapus($id)
    {
        $jenisId = $id;
        DB::table('jenises')->where('id', $jenisId)->delete();

        $jenises = array();
        $perjenises = Jenis::get();

        return view(
            'data.jenis.jenis',
            compact(
                'jenises',
                'perjenises',
            )
        );
    }

    public function jenisImport()
    {
        Excel::import(new MasukBibitImport, request()->file('file'));
        return redirect()->back();
    }
}
