<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Tahun;
use App\Models\Bulan;
use App\Models\Hari;
use App\Models\Barang;
use App\Models\Lokasi;
use App\Models\Satuan;
use App\Models\Obat;

class ObatController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $tahuns = Tahun::get();
        $bulans = Bulan::get();
        $haries = Hari::get();
        $satuans = Satuan::get();
        $lokasies = Lokasi::get();
        $barangs = Barang::get();

        $obats = array();
        $perobats = Obat::paginate(25);

        return view(
            'obat.obat',
            compact(
                'tahuns',
                'bulans',
                'haries',
                'satuans',
                'lokasies',
                'barangs',

                'obats',
                'perobats',
            )
        );
    }

    public function obatTambah()
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
        $satuans = Satuan::get();
        $lokasies = Lokasi::get();
        $barangs = Barang::get();

        return view(
            'obat.obat-tambah',
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
                'satuans',
                'lokasies',
                'barangs',
            )
        );
    }

    public function obatSimpan(Request $request)
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

        $tahunId = $request->tahunId;
        $bulanId = $request->bulanId;
        $hariId = $request->hariId;
        $lokasiId = $request->lokasiId;
        $satuanId = $request->satuanId;

        DB::table('obats')->insert([
            "tahun_id" => $tahunId,
            "bulan_id" => $bulanId,
            "hari_id" => $hariId,
            "lokasi_id" => $lokasiId,
            "barang_id" => $barangId,
            "satuan_id" => $satuanId,
            "created_at" => now(),
            "updated_at" => now(),
        ]);

        $tahuns = Tahun::get();
        $bulans = Bulan::get();
        $haries = Hari::get();
        $satuans = Satuan::get();
        $lokasies = Lokasi::get();
        $barangs = Barang::get();

        $obats = array();
        $perobats = Obat::paginate(25);

        return view(
            'obat.obat',
            compact(
                'tahuns',
                'bulans',
                'haries',
                'satuans',
                'lokasies',
                'barangs',

                'obats',
                'perobats',
            )
        );
    }

    public function obatEdit($id)
    {
        $obatId = $id;
        $tahuns = Tahun::get();
        $bulans = Bulan::get();
        $haries = Hari::get();
        $satuans = Satuan::get();
        $lokasies = Lokasi::get();
        $barangs = Barang::get();

        $obats = Obat::where('id', $obatId)->get();
        $tahunId = $obats[0]->tahun_id;
        $bulanId = $obats[0]->bulan_id;
        $hariId = $obats[0]->hari_id;
        $lokasiId = $obats[0]->lokasi_id;
        $satuanId = $obats[0]->satuan_id;
        $barangId = $obats[0]->barang_id;

        $tahunNama = Tahun::find($tahunId)->nama;
        $bulanNama = Bulan::find($bulanId)->nama;
        $hariNama = Hari::find($hariId)->nama;
        $lokasiNama = Lokasi::find($lokasiId)->nama;
        $satuanNama = Satuan::find($satuanId)->nama;
        $barangNama = Barang::find($barangId)->nama;

        $perobats = Obat::paginate(25);

        return view(
            'obat.obat',
            compact(
                'tahuns',
                'bulans',
                'haries',
                'satuans',
                'lokasies',
                'barangs',

                'obats',
                'perobats',

                'tahunId',
                'bulanId',
                'hariId',
                'lokasiId',
                'satuanId',
                'barangId',

                'tahunNama',
                'bulanNama',
                'hariNama',
                'lokasiNama',
                'satuanNama',
                'barangNama',
            )
        );
    }

    public function obatUpdate(Request $request, $id)
    {
        $obatId = $id;
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

        $tahunId = $request->tahunId;
        $bulanId = $request->bulanId;
        $hariId = $request->hariId;
        $lokasiId = $request->lokasiId;
        $satuanId = $request->satuanId;

        DB::table('obats')->where('id', $obatId)->update([
            "tahun_id" => $tahunId,
            "bulan_id" => $bulanId,
            "hari_id" => $hariId,
            "lokasi_id" => $lokasiId,
            "barang_id" => $barangId,
            "satuan_id" => $satuanId,
            "updated_at" => now(),
        ]);

        $tahuns = Tahun::get();
        $bulans = Bulan::get();
        $haries = Hari::get();
        $satuans = Satuan::get();
        $lokasies = Lokasi::get();
        $barangs = Barang::get();

        $obats = array();
        $perobats = Obat::paginate(25);

        return view(
            'obat.obat',
            compact(
                'tahuns',
                'bulans',
                'haries',
                'satuans',
                'lokasies',
                'barangs',

                'obats',
                'perobats',
            )
        );
    }

    public function obatHapus($id)
    {
        $obatId = $id;
        DB::table('obats')->where('id', $obatId)->delete();

        $tahuns = Tahun::get();
        $bulans = Bulan::get();
        $haries = Hari::get();
        $satuans = Satuan::get();
        $lokasies = Lokasi::get();
        $barangs = Barang::get();

        $obats = array();
        $perobats = Obat::paginate(25);

        return view(
            'obat.obat',
            compact(
                'tahuns',
                'bulans',
                'haries',
                'satuans',
                'lokasies',
                'barangs',

                'obats',
                'perobats',
            )
        );
    }
}
