<?php

namespace App\Http\Controllers;

use App\Models\Inventory;
use App\Models\Jenis;
use App\Models\Lokasi;
use App\Models\Satuan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Str;
use App\Imports\InventoryImport;

class InventoryController extends Controller
{
    public function index()
    {
        $inventories = array();
        $perinventories = Inventory::orderby('id', 'desc')
            ->paginate(25);

        return view(
            'inventory.inventory',
            compact(
                'inventories',
                'perinventories'
            )
        );
    }

    public function inventoryTambah()
    {
        $lokasies = Lokasi::get();
        $satuans = Satuan::get();
        $jenises = Jenis::get();

        return view(
            'inventory.inventory-tambah',
            compact(
                'lokasies',
                'satuans',
                'jenises',
            )
        );
    }

    public function inventorySimpan(Request $request)
    {
        $lokasiId = $request->lokasiId;
        $barangNama = $request->nama;
        $satuanId = $request->satuanId;
        $jenisId = $request->jenisId;
        $kuantitas = $request->kuantitas;
        $keterangan = $request->keterangan;

        if ($keterangan == null) {
            $keterangan = '';
        }

        if ($lokasiId != null && $satuanId != null && $jenisId != null) {
            DB::table('inventories')->insert([
                "lokasi_id" => $lokasiId,
                "nama" => $barangNama,
                "satuan_id" => $satuanId,
                "jenis_id" => $jenisId,
                "kuantitas" => $kuantitas,
                "keterangan" => $keterangan,
                "created_at" => now(),
                "updated_at" => now(),
            ]);
        }

        $inventories = array();
        $perinventories = Inventory::orderby('id', 'desc')
            ->paginate(25);

        return view(
            'inventory.inventory',
            compact(
                'inventories',
                'perinventories',
            )
        );
    }

    public function inventoryEdit($id)
    {
        $inventoryId = $id;
        $lokasies = Lokasi::get();
        $satuans = Satuan::get();
        $jenises = Jenis::get();

        $inventories = Inventory::where('id', $inventoryId)->get();
        $perinventories = Inventory::orderby('id', 'desc')
            ->paginate(25);

        return view(
            'inventory.inventory',
            compact(
                'inventories',
                'perinventories',

                'lokasies',
                'satuans',
                'jenises',
            )
        );
    }

    public function inventoryUpdate(Request $request, $id)
    {
        $inventoryId = $id;

        $inventories = Inventory::where('id', $inventoryId)->get();
        $lokasiId = $inventories[0]->lokasi_id;
        $satuanId = $inventories[0]->satuan_id;
        $jenisId = $inventories[0]->jenis_id;
        $kuantitas = $request->kuantitas;
        $barangNama = $request->nama;
        $keterangan = $request->keterangan;

        DB::table('inventories')->where('id', $inventoryId)->update([
            "nama" => $barangNama,
            "lokasi_id" => $lokasiId,
            "jenis_id" => $jenisId,
            "satuan_id" => $satuanId,
            "kuantitas" => $kuantitas,
            "keterangan" => $keterangan,
            "updated_at" => now(),
        ]);

        $inventories = array();
        $perinventories = Inventory::orderby('id', 'desc')
            ->paginate(25);

        return view(
            'inventory.inventory',
            compact(
                'inventories',
                'perinventories',
            )
        );
    }

    public function inventoryHapus($id)
    {
        $inventoryId = $id;
        DB::table('inventories')->where('id', $inventoryId)->delete();

        $inventories = array();
        $perinventories = Inventory::orderby('id', 'desc')
            ->paginate('25');

        return view(
            'inventory.inventory',
            compact(
                'inventories',
                'perinventories',
            )
        );
    }

    public function inventoryImport()
    {
        Excel::import(new inventoryImport, request()->file('file'));
        return redirect()->back();
    }
}
