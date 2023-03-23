<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\KeuanganController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DataController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\ObatController;
use App\Http\Controllers\StokController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// ---------------------- STARTING POINT -------------------- //

Route::get('/', function () {
    return view('login');
});

// ---------------------- LOGIN & LOGOUT -------------------- //

Route::get('/login', [
    DashboardController::class, 'login'
])->name('login');

Route::post('/post-login', [
    DashboardController::class, 'postLogin'
])->name('post-login');

Route::get('logout', [
    DashboardController::class, 'logout'
])->name('logout');

// ---------------------- APPROVER & ADMIN -------------------- //

Route::group(['middleware' => ['auth', 'ceklevel:approver,admin']], function () {

    // ---------------------- DASHBOARD -------------------- //

    Route::get('/dashboard', [
        DashboardController::class, 'index'
    ])->middleware(['auth', 'verified'])->name('dashboard');

    // ---------------------- KEUANGAN -------------------- //

    // ---------------------- NOTA -------------------- //

    Route::get('/nota', [
        KeuanganController::class, 'index'
    ])->middleware(['auth', 'verified'])->name('nota');

    // ---------------------- DETAIL -------------------- //

    Route::get('/detail/{id}', [
        KeuanganController::class, 'detail'
    ])->middleware(['auth', 'verified'])->name('detail');

    // ---------------------- PERTAHUN -------------------- //

    Route::get('/pertahun', [
        KeuanganController::class, 'pertahun'
    ])->middleware(['auth', 'verified'])->name('pertahun');

    // ---------------------- PERBULAN -------------------- //

    Route::get('/perbulan', [
        KeuanganController::class, 'perbulan'
    ])->middleware(['auth', 'verified'])->name('perbulan');

    // ---------------------- PERHARI FILE -------------------- //

    Route::get('/perhari', [
        KeuanganController::class, 'perhari'
    ])->middleware(['auth', 'verified'])->name('perhari');

    // ---------------------- PERLOKASI FOLDER -------------------- //

    Route::get('/perlokasi', [
        KeuanganController::class, 'perlokasi'
    ])->middleware(['auth', 'verified'])->name('perlokasi');

    // ---------------------- REKAP FOLDER -------------------- //

    Route::get('/rekap', [
        KeuanganController::class, 'rekap'
    ])->middleware(['auth', 'verified'])->name('rekap');

    // ---------------------- STOK CONTROLLER -------------------- //

    // ---------------------- STOK FOLDER -------------------- //

    Route::get('/stok', [
        StokController::class, 'index'
    ])->middleware(['auth', 'verified'])->name('stok');

    Route::get('/stok/cari', [
        StokController::class, 'stokCari'
    ])->middleware(['auth', 'verified'])->name('stok-cari');

    // ---------------------- STOK DETAIL FOLDER -------------------- //

    Route::get('/stok-detail/{id}', [
        StokController::class, 'stokDetail'
    ])->middleware(['auth', 'verified'])->name('stok-detail');

    // ---------------------- INVENTORY CONTROLLER -------------------- //

    // ---------------------- INVENTORY FOLDER -------------------- //

    Route::get('/inventory', [
        InventoryController::class, 'index'
    ])->middleware(['auth', 'verified'])->name('inventory');

    // ---------------------- OBAT CONTROLLER -------------------- //

    // ---------------------- OBAT FOLDER -------------------- //

    Route::get('/obat', [
        ObatController::class, 'index'
    ])->middleware(['auth', 'verified'])->name('obat');

    // ---------------------- DATA CONTROLLER -------------------- //

    // ---------------------- MENU FILE -------------------- //
    Route::get('/data', [
        DataController::class, 'index'
    ])->middleware(['auth', 'verified'])->name('data');

    // ---------------------- TAHUN FOLDER -------------------- //

    Route::get('/tahun', [
        DataController::class, 'tahun'
    ])->middleware(['auth', 'verified'])->name('tahun');

    // ---------------------- BARANG FOLDER -------------------- //

    Route::get('/barang', [
        DataController::class, 'barang'
    ])->middleware(['auth', 'verified'])->name('barang');

    // ---------------------- LOKASI FOLDER -------------------- //

    Route::get('/lokasi', [
        DataController::class, 'lokasi'
    ])->middleware(['auth', 'verified'])->name('lokasi');

    // ---------------------- TRANSAKSI FOLDEr -------------------- //

    Route::get('/transaksi', [
        DataController::class, 'transaksi'
    ])->middleware(['auth', 'verified'])->name('transaksi');


    // ---------------------- SATUAN FOLDER -------------------- //

    Route::get('/satuan', [
        DataController::class, 'satuan'
    ])->middleware(['auth', 'verified'])->name('satuan');

    // ---------------------- VARIETAS FOLDER -------------------- //

    Route::get('/varietas', [
        DataController::class, 'varietas'
    ])->middleware(['auth', 'verified'])->name('varietas');

    // ---------------------- KETERANGAN BIBIT FOLDER -------------------- //

    Route::get('/keterangan-bibit', [
        DataController::class, 'keteranganBibit'
    ])->middleware(['auth', 'verified'])->name('keterangan-bibit');

    // ---------------------- MASUK BIBIT FOLDER -------------------- //

    Route::get('/masuk-bibit', [
        DataController::class, 'masukBibit'
    ])->middleware(['auth', 'verified'])->name('masuk-bibit');

    // ---------------------- JENIS FOLDER -------------------- //

    Route::get('/jenis', [
        DataController::class, 'jenis'
    ])->middleware(['auth', 'verified'])->name('jenis');

    // ---------------------- USER FOLDER -------------------- //

    Route::get('/user', [
        DataController::class, 'user'
    ])->middleware(['auth', 'verified'])->name('user');
});

// ---------------------- ADMIN AUTHORITY -------------------- //

Route::group(['middleware' => ['auth', 'ceklevel:admin']], function () {

    // ---------------------- NOTA  -------------------- //

    Route::get('/nota-tambah', [
        KeuanganController::class, 'notaTambah'
    ])->middleware(['auth', 'verified'])->name('nota-tambah');

    Route::get('/nota-simpan', [
        KeuanganController::class, 'notaSimpan'
    ])->middleware(['auth', 'verified'])->name('nota-simpan');

    Route::get('/nota-edit/{id}', [
        KeuanganController::class, 'notaEdit'
    ])->middleware(['auth', 'verified'])->name('nota-edit');

    Route::get('/nota-update/{id}', [
        KeuanganController::class, 'notaUpdate'
    ])->middleware(['auth', 'verified'])->name('nota-update');

    Route::get('/nota-hapus/{id}', [
        KeuanganController::class, 'notaHapus'
    ])->middleware(['auth', 'verified'])->name('nota-hapus');

    // ---------------------- DETAIL -------------------- //

    Route::get('/detail-tambah', [
        KeuanganController::class, 'detailTambah'
    ])->middleware(['auth', 'verified'])->name('detail-tambah');

    Route::get('/detail-simpan', [
        KeuanganController::class, 'detailSimpan'
    ])->middleware(['auth', 'verified'])->name('detail-simpan');

    Route::get('/detail-edit/{id}', [
        KeuanganController::class, 'detailEdit'
    ])->middleware(['auth', 'verified'])->name('detail-edit');

    Route::get('/detail-update/{id}', [
        KeuanganController::class, 'detailUpdate'
    ])->middleware(['auth', 'verified'])->name('detail-update');

    Route::get('/detail-hapus/{id}', [
        KeuanganController::class, 'detailHapus'
    ])->middleware(['auth', 'verified'])->name('detail-hapus');

    Route::get('/autocomplete-search', [KeuanganController::class, 'autocompleteSearch']);

    // ---------------------- STOK -------------------- //

    Route::get('/stok-tambah', [
        StokController::class, 'stokTambah'
    ])->middleware(['auth', 'verified'])->name('stok-tambah');

    Route::get('/stok-simpan', [
        StokController::class, 'stokSimpan'
    ])->middleware(['auth', 'verified'])->name('stok-simpan');

    Route::get('/stok-edit/{id}', [
        StokController::class, 'stokEdit'
    ])->middleware(['auth', 'verified'])->name('stok-edit');

    Route::get('/stok-update/{id}', [
        StokController::class, 'stokUpdate'
    ])->middleware(['auth', 'verified'])->name('stok-update');

    Route::get('/stok-hapus/{id}', [
        StokController::class, 'stokHapus'
    ])->middleware(['auth', 'verified'])->name('stok-hapus');

    // ---------------------- STOK DETAIL -------------------- //

    Route::get('/stok-detail-tambah', [
        StokController::class, 'stokDetailTambah'
    ])->middleware(['auth', 'verified'])->name('stok-detail-tambah');

    Route::get('/stok-detail-simpan', [
        StokController::class, 'stokDetailSimpan'
    ])->middleware(['auth', 'verified'])->name('stok-detail-simpan');

    Route::get('/stok-detail-edit/{id}', [
        StokController::class, 'stokDetailEdit'
    ])->middleware(['auth', 'verified'])->name('stok-detail-edit');

    Route::get('/stok-detail-update/{id}', [
        StokController::class, 'stokDetailUpdate'
    ])->middleware(['auth', 'verified'])->name('stok-detail-update');

    Route::get('/stok-detail-hapus/{id}', [
        StokController::class, 'stokDetailHapus'
    ])->middleware(['auth', 'verified'])->name('stok-detail-hapus');

    // ---------------------- INVENTORY -------------------- //

    Route::get('/inventory-tambah', [
        InventoryController::class, 'inventoryTambah'
    ])->middleware(['auth', 'verified'])->name('inventory-tambah');

    Route::get('/inventory-simpan', [
        InventoryController::class, 'inventorySimpan'
    ])->middleware(['auth', 'verified'])->name('inventory-simpan');

    Route::get('/inventory-edit/{id}', [
        InventoryController::class, 'inventoryEdit'
    ])->middleware(['auth', 'verified'])->name('inventory-edit');

    Route::get('/inventory-update/{id}', [
        InventoryController::class, 'inventoryUpdate'
    ])->middleware(['auth', 'verified'])->name('inventory-update');

    Route::get('/inventory-hapus/{id}', [
        InventoryController::class, 'inventoryHapus'
    ])->middleware(['auth', 'verified'])->name('inventory-hapus');

    // ---------------------- OBAT -------------------- //

    Route::get('/obat-tambah', [
        ObatController::class, 'obatTambah'
    ])->middleware(['auth', 'verified'])->name('obat-tambah');

    Route::get('/obat-simpan', [
        ObatController::class, 'obatSimpan'
    ])->middleware(['auth', 'verified'])->name('obat-simpan');

    Route::get('/obat-edit/{id}', [
        ObatController::class, 'obatEdit'
    ])->middleware(['auth', 'verified'])->name('obat-edit');

    Route::get('/obat-update/{id}', [
        ObatController::class, 'obatUpdate'
    ])->middleware(['auth', 'verified'])->name('obat-update');

    Route::get('/obat-hapus/{id}', [
        ObatController::class, 'obatHapus'
    ])->middleware(['auth', 'verified'])->name('obat-hapus');

    // ---------------------- IMPORT -------------------- //

    // ---------------------- NOTA -------------------- //
    Route::POST('/nota-import', [
        KeuanganController::class, 'notaImport'
    ])->middleware(['auth', 'verified'])->name('nota-import');

    // ---------------------- DETAIL -------------------- //
    Route::POST('/detail-import', [
        KeuanganController::class, 'detailImport'
    ])->middleware(['auth', 'verified'])->name('detail-import');

    // ---------------------- STOK -------------------- //
    Route::POST('/stok-import', [
        StokController::class, 'stokImport'
    ])->middleware(['auth', 'verified'])->name('stok-import');

    // ---------------------- STOK DETAIL -------------------- //
    Route::POST('/stokDetail-import', [
        StokController::class, 'stokDetailImport'
    ])->middleware(['auth', 'verified'])->name('stokDetail-import');

    // ---------------------- INVENTORY -------------------- //
    Route::POST('/inventory-import', [
        InventoryController::class, 'inventoryImport'
    ])->middleware(['auth', 'verified'])->name('inventory-import');

    // ---------------------- OBAT -------------------- //
    Route::POST('/obat-import', [
        ObatController::class, 'obatImport'
    ])->middleware(['auth', 'verified'])->name('obat-import');

    // ---------------------- TAHUN -------------------- //
    Route::POST('/tahun-import', [
        DataController::class, 'tahunImport'
    ])->middleware(['auth', 'verified'])->name('tahun-import');

    // ---------------------- LOKASI -------------------- //
    Route::POST('/lokasi-import', [
        DataController::class, 'lokasiImport'
    ])->middleware(['auth', 'verified'])->name('lokasi-import');

    // ---------------------- BARANG -------------------- //
    Route::POST('/barang-import', [
        DataController::class, 'barangImport'
    ])->middleware(['auth', 'verified'])->name('barang-import');

    // ---------------------- VARIETAS -------------------- //
    Route::POST('/transaksi-import', [
        DataController::class, 'transaksiImport'
    ])->middleware(['auth', 'verified'])->name('transaksi-import');

    // ---------------------- SATUAN -------------------- //
    Route::POST('/satuan-import', [
        DataController::class, 'satuanImport'
    ])->middleware(['auth', 'verified'])->name('satuan-import');

    // ---------------------- VARIETAS -------------------- //
    Route::POST('/varietas-import', [
        DataController::class, 'varietasImport'
    ])->middleware(['auth', 'verified'])->name('varietas-import');

    // ---------------------- KETERANGAN BIBIT -------------------- //
    Route::POST('/keteranganBibit-import', [
        DataController::class, 'keteranganBibitImport'
    ])->middleware(['auth', 'verified'])->name('keteranganBibit-import');

    // ---------------------- MASUK BIBIT -------------------- //
    Route::POST('/masukBibit-import', [
        DataController::class, 'masukBibitImport'
    ])->middleware(['auth', 'verified'])->name('masukBibit-import');

    // ---------------------- JENIS -------------------- //
    Route::POST('/jenis-import', [
        DataController::class, 'jenisImport'
    ])->middleware(['auth', 'verified'])->name('jenis-import');

    // ---------------------- DATA CONTROLLER -------------------- //

    // ---------------------- TAHUN FOLDER -------------------- //

    Route::get('/tahun-tambah', [
        DataController::class, 'tahunTambah'
    ])->middleware(['auth', 'verified'])->name('tahun-tambah');

    Route::get('/tahun-simpan', [
        DataController::class, 'tahunSimpan'
    ])->middleware(['auth', 'verified'])->name('tahun-simpan');

    Route::get('/tahun-edit/{id}', [
        DataController::class, 'tahunEdit'
    ])->middleware(['auth', 'verified'])->name('tahun-edit');

    Route::get('/tahun-update/{id}', [
        DataController::class, 'tahunUpdate'
    ])->middleware(['auth', 'verified'])->name('tahun-update');

    Route::get('/tahun-hapus/{id}', [
        DataController::class, 'tahunHapus'
    ])->middleware(['auth', 'verified'])->name('tahun-hapus');

    // ---------------------- LOKASI FOLDER -------------------- //

    Route::get('/lokasi-tambah', [
        DataController::class, 'lokasiTambah'
    ])->middleware(['auth', 'verified'])->name('lokasi-tambah');

    Route::get('/lokasi-simpan', [
        DataController::class, 'lokasiSimpan'
    ])->middleware(['auth', 'verified'])->name('lokasi-simpan');

    Route::get('/lokasi-edit/{id}', [
        DataController::class, 'lokasiEdit'
    ])->middleware(['auth', 'verified'])->name('lokasi-edit');

    Route::get('/lokasi-update/{id}', [
        DataController::class, 'lokasiUpdate'
    ])->middleware(['auth', 'verified'])->name('lokasi-update');

    Route::get('/lokasi-hapus/{id}', [
        DataController::class, 'lokasiHapus'
    ])->middleware(['auth', 'verified'])->name('lokasi-hapus');

    // ---------------------- BARANG FOLDER -------------------- //

    Route::get('/barang-tambah', [
        DataController::class, 'barangTambah'
    ])->middleware(['auth', 'verified'])->name('barang-tambah');

    Route::get('/barang-simpan', [
        DataController::class, 'barangSimpan'
    ])->middleware(['auth', 'verified'])->name('barang-simpan');

    Route::get('/barang-edit/{id}', [
        DataController::class, 'barangEdit'
    ])->middleware(['auth', 'verified'])->name('barang-edit');

    Route::get('/barang-update/{id}', [
        DataController::class, 'barangUpdate'
    ])->middleware(['auth', 'verified'])->name('barang-update');

    Route::get('/barang-hapus/{id}', [
        DataController::class, 'barangHapus'
    ])->middleware(['auth', 'verified'])->name('barang-hapus');

    // ---------------------- TRANSAKSI FOLDER -------------------- //

    Route::get('/transaksi-tambah', [
        DataController::class, 'transaksiTambah'
    ])->middleware(['auth', 'verified'])->name('transaksi-tambah');

    Route::get('/transaksi-simpan', [
        DataController::class, 'transaksiSimpan'
    ])->middleware(['auth', 'verified'])->name('transaksi-simpan');

    Route::get('/transaksi-edit/{id}', [
        DataController::class, 'transaksiEdit'
    ])->middleware(['auth', 'verified'])->name('transaksi-edit');

    Route::get('/transaksi-update/{id}', [
        DataController::class, 'transaksiUpdate'
    ])->middleware(['auth', 'verified'])->name('transaksi-update');

    Route::get('/transaksi-hapus/{id}', [
        DataController::class, 'transaksiHapus'
    ])->middleware(['auth', 'verified'])->name('transaksi-hapus');

    // ---------------------- SATUAN FOLDER -------------------- //

    Route::get('/satuan-tambah', [
        DataController::class, 'satuanTambah'
    ])->middleware(['auth', 'verified'])->name('satuan-tambah');

    Route::get('/satuan-simpan', [
        DataController::class, 'satuanSimpan'
    ])->middleware(['auth', 'verified'])->name('satuan-simpan');

    Route::get('/satuan-edit/{id}', [
        DataController::class, 'satuanEdit'
    ])->middleware(['auth', 'verified'])->name('satuan-edit');

    Route::get('/satuan-update/{id}', [
        DataController::class, 'satuanUpdate'
    ])->middleware(['auth', 'verified'])->name('satuan-update');

    Route::get('/satuan-hapus/{id}', [
        DataController::class, 'satuanHapus'
    ])->middleware(['auth', 'verified'])->name('satuan-hapus');

    // ---------------------- VARIETAS FOLDER -------------------- //

    Route::get('/varietas-tambah', [
        DataController::class, 'varietasTambah'
    ])->middleware(['auth', 'verified'])->name('varietas-tambah');

    Route::get('/varietas-simpan', [
        DataController::class, 'varietasSimpan'
    ])->middleware(['auth', 'verified'])->name('varietas-simpan');

    Route::get('/varietas-edit/{id}', [
        DataController::class, 'varietasEdit'
    ])->middleware(['auth', 'verified'])->name('varietas-edit');

    Route::get('/varietas-update/{id}', [
        DataController::class, 'varietasUpdate'
    ])->middleware(['auth', 'verified'])->name('varietas-update');

    Route::get('/varietas-hapus/{id}', [
        DataController::class, 'varietasHapus'
    ])->middleware(['auth', 'verified'])->name('varietas-hapus');

    // ---------------------- KETERANGAN BIBIT FOLDER -------------------- //

    Route::get('/keteranganBibit-tambah', [
        DataController::class, 'keteranganBibitTambah'
    ])->middleware(['auth', 'verified'])->name('keteranganBibit-tambah');

    Route::get('/keteranganBibit-simpan', [
        DataController::class, 'keteranganBibitSimpan'
    ])->middleware(['auth', 'verified'])->name('keteranganBibit-simpan');

    Route::get('/keteranganBibit-edit/{id}', [
        DataController::class, 'keteranganBibitEdit'
    ])->middleware(['auth', 'verified'])->name('keteranganBibit-edit');

    Route::get('/keteranganBibit-update/{id}', [
        DataController::class, 'keteranganBibitUpdate'
    ])->middleware(['auth', 'verified'])->name('keteranganBibit-update');

    Route::get('/keteranganBibit-hapus/{id}', [
        DataController::class, 'keteranganBibitHapus'
    ])->middleware(['auth', 'verified'])->name('keteranganBibit-hapus');

    // ---------------------- MASUK BIBIT FOLDER -------------------- //

    Route::get('/masukBibit-tambah', [
        DataController::class, 'masukBibitTambah'
    ])->middleware(['auth', 'verified'])->name('masukBibit-tambah');

    Route::get('/masukBibit-simpan', [
        DataController::class, 'masukBibitSimpan'
    ])->middleware(['auth', 'verified'])->name('masukBibit-simpan');

    Route::get('/masukBibit-edit/{id}', [
        DataController::class, 'masukBibitEdit'
    ])->middleware(['auth', 'verified'])->name('masukBibit-edit');

    Route::get('/masukBibit-update/{id}', [
        DataController::class, 'masukBibitUpdate'
    ])->middleware(['auth', 'verified'])->name('masukBibit-update');

    Route::get('/masukBibit-hapus/{id}', [
        DataController::class, 'masukBibitHapus'
    ])->middleware(['auth', 'verified'])->name('masukBibit-hapus');

    // ---------------------- JENIS FOLDER -------------------- //

    Route::get('/jenis-tambah', [
        DataController::class, 'jenisTambah'
    ])->middleware(['auth', 'verified'])->name('jenis-tambah');

    Route::get('/jenis-simpan', [
        DataController::class, 'jenisSimpan'
    ])->middleware(['auth', 'verified'])->name('jenis-simpan');

    Route::get('/jenis-edit/{id}', [
        DataController::class, 'jenisEdit'
    ])->middleware(['auth', 'verified'])->name('jenis-edit');

    Route::get('/jenis-update/{id}', [
        DataController::class, 'jenisUpdate'
    ])->middleware(['auth', 'verified'])->name('jenis-update');

    Route::get('/jenis-hapus/{id}', [
        DataController::class, 'jenisHapus'
    ])->middleware(['auth', 'verified'])->name('jenis-hapus');

    // ---------------------- USER -------------------- //

    Route::get('/user-edit/{id}', [
        DataController::class, 'userEdit'
    ])->middleware(['auth', 'verified'])->name('user-edit');

    Route::get('/user-update/{id}', [
        DataController::class, 'userUpdate'
    ])->middleware(['auth', 'verified'])->name('user-update');
});
