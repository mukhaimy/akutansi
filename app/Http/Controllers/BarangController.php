<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Support\Facades\Crypt;
use Auth;
use Hash;
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

class BarangController extends Controller
{
    // ---------------------- BARANG -------------------- //

    public function barang()
    {
        $barang = array();
        $perbarangs = Barang::get();

        return view(
            'barang.barang',
            compact(
                'barang',
                'perbarangs',
            )
        );
    }

    public function barangTambah()
    {
        $satuans = Satuan::get();

        return view(
            'barang.barang-tambah',
            compact(
                'satuans',
            )
        );
    }

    public function barangSimpan(Request $request)
    {
        $nama = $request->nama;
        $satuanId = $request->satuanId;
        $harga = $request->harga;

        DB::table('barangs')->insert([
            'nama' => $nama,
            'satuan_id' => $satuanId,
            'harga' => $harga,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $barang = array();
        $perbarangs = Barang::get();

        return view(
            'barang.barang',
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
        $satuans = Satuan::get();

        return view(
            'barang.barang',
            compact(
                'barang',
                'perbarangs',
                'satuans',
            )
        );
    }

    public function barangUpdate(Request $request, $id)
    {
        $barangId = $id;
        $nama = $request->nama;
        $satuanId = $request->satuanId;
        $harga = $request->harga;

        DB::table('barangs')->where('id', $barangId)->update([
            'nama' => $nama,
            'satuan_id' => $satuanId,
            'harga' => $harga,
            'updated_at' => now(),
        ]);

        $barang = array();
        $perbarangs = Barang::get();

        return view(
            'barang.barang',
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
            'barang.barang',
            compact(
                'barang',
                'perbarangs',
            )
        );
    }
}
