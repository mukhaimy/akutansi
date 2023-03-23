@include('layouts.header')
<title>Inventory - Tambah</title>

<!-- Navbar -->
@include('layouts.nav')

<!-- Main Sidebar Container -->
@include('layouts.sidebar')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-12">
                    <h3>Inventory</h3>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header border-0">
                            <form method="GET" action="/inventory" class="m-3">
                                @csrf
                                <button type="submit" class="btn btn-warning text-dark">Kembali</button>
                            </form>
                        </div>
                        <div class="card-body">
                            <div class="position-relative mb-4">
                                <form method="GET" action="/inventory-simpan" class="m-5">
                                    @csrf
                                    <div class="mb-3">
                                        <label for="lokasi" class="form-label">Lokasi</label>
                                        <select class="form-control" id="lokasi" name="lokasiId" aria-describedby="lokasiItem">
                                            @foreach ($lokasies as $valueLokasi)
                                            <option value="{{$valueLokasi->id}}">{{$valueLokasi->nama}}</option>
                                            @endforeach
                                        </select>
                                        <div id="satuanItem" class="form-text">Pilih lokasi item</div>
                                    </div>
                                    <div class="mb-3">
                                        <label for="nama" class="form-label">Nama</label>
                                        <input type="text" class="form-control" id="nama" name="nama" aria-describedby="namaItem">
                                        <div id="namaItem" class="form-text">Pilih Nama item</div>
                                    </div>
                                    <div class="mb-3">
                                        <label for="jenis" class="form-label">Jenis</label>
                                        <select class="form-control" id="jenis" name="jenisId" aria-describedby="jenisItem">
                                            @foreach ($jenises as $valueJenis)
                                            <option value="{{$valueJenis->id}}">{{$valueJenis->nama}}</option>
                                            @endforeach
                                        </select>
                                        <div id="satuanItem" class="form-text">Pilih jenis item</div>
                                    </div>
                                    <div class="mb-3">
                                        <label for="satuan" class="form-label">Satuan</label>
                                        <select class="form-control" id="satuan" name="satuanId" aria-describedby="satuanItem">
                                            @foreach ($satuans as $valueSatuan)
                                            <option value="{{$valueSatuan->id}}">{{$valueSatuan->nama}}</option>
                                            @endforeach
                                        </select>
                                        <div id="satuanItem" class="form-text">Pilih satuan item</div>
                                    </div>
                                    <div class="mb-3">
                                        <label for="kuantitas" class="form-label">Kuantitas</label>
                                        <input type="number" class="form-control" id="kuantitas" name="kuantitas" aria-describedby="kuantitasItem">
                                        <div id="kuantitasItem" class="form-text">Masukan kuantitas item</div>
                                    </div>
                                    <div class="mb-3">
                                        <label for="keterangan" class="form-label">Keterangan</label>
                                        <input type="text" class="form-control" id="keterangan" name="keterangan" aria-describedby="keteranganItem">
                                        <div id="keteranganItem" class="form-text">Masukan Keterangan item</div>
                                    </div>
                                    <button type="submit" class="btn btn-primary text-light">Simpan</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /.card -->
            </div>

        </div>
        <!-- /.row -->
    </div>
    <!-- /.container-fluid -->
</div>
<!-- /.content -->
</div>
<!-- /.content-wrapper -->

<!-- Main Footer -->
@include('layouts.footer')