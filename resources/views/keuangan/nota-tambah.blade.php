@include('layouts.header')
<title>Nota - Tambah</title>

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
                    <h3>Pernota</h3>
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
                            <form method="GET" action="/nota" class="m-3">
                                @csrf
                                <button type="submit" class="btn btn-warning text-dark">Kembali</button>
                            </form>
                        </div>
                        <div class="card-body">
                            <div class="position-relative mb-4">
                                <form method="GET" action="/nota-simpan" class="m-5">
                                    <div class="mb-3">
                                        <label for="tahun" class="form-label">Tahun :</label>
                                        <select class="form-control" name="tahunId" id="tahun" aria-describedby="tahunItem" required>
                                            <option selected="true" class="bg-success text-white" style="display: none" value="{{$tahunId}}">{{$tahunNama}}</option>
                                            @foreach ($tahuns as $valueTahun)
                                            <option value="{{$valueTahun->id}}">{{$valueTahun->nama}}</option>
                                            @endforeach
                                        </select>
                                        <div id="tahunItem" class="form-text">Pilih tahun</div>
                                    </div>
                                    <div class="mb-3">
                                        <label for="bulan" class="form-label">Bulan :</label>
                                        <select class="form-control" name="bulanId" id="bulan" aria-describedby="bulanItem" required>
                                            <option selected="true" class="bg-success text-white" style="display: none" value="{{$bulanId}}">{{$bulanNama}}</option>
                                            @foreach ($bulans as $valueBulan)
                                            <option value="{{$valueBulan->id}}">{{$valueBulan->nama}}</option>
                                            @endforeach
                                        </select>
                                        <div id="bulanItem" class="form-text">Pilih bulan</div>
                                    </div>
                                    <div class="mb-3">
                                        <label for="hari" class="form-label">Hari :</label>
                                        <select class="form-control" name="hariId" id="hari" aria-describedby="hariItem" required>
                                            <option selected="true" class="bg-success text-white" style="display: none" value="{{$hariId}}">{{$hariNama}}</option>
                                            @foreach ($haries as $valueHari)
                                            <option value="{{$valueHari->id}}">{{$valueHari->nama}}</option>
                                            @endforeach
                                        </select>
                                        <div id="hariItem" class="form-text">Pilih hari</div>
                                    </div>
                                    <div class="mb-3">
                                        <label for="lokasi" class="form-label">Lokasi :</label>
                                        <select class="form-control" name="lokasiId" id="lokasi" aria-describedby="lokasiItem" required>
                                            @foreach ($lokasies as $valueLokasi)
                                            <option value="{{$valueLokasi->id}}">{{$valueLokasi->nama}}</option>
                                            @endforeach
                                        </select>
                                        <div id="barangItem" class="form-text">Pilih lokasi</div>
                                    </div>
                                    <div class="mb-3">
                                        <label for="nama" class="form-label">Nama Nota :</label>
                                        <input type="text" class="form-control" id="nama" name="nama" aria-describedby="namaItem" required>
                                        <div id="namaItem" class="form-text">Masukan nama item</div>
                                    </div>
                                    <div class="mb-3">
                                        <label for="keterangan" class="form-label">Keterangan :</label>
                                        <input type="text" class="form-control" id="keterangan" name="keterangan" aria-describedby="keteranganItem">
                                        <div id="keteranganItem" class="form-text">Masukan keterangan item</div>
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