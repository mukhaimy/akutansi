@include('layouts.header')
<title>Stok - Tambah</title>

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
                    <h3>Stok</h3>
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
                            <form method="GET" action="/stok" class="m-3">
                                @csrf
                                <button type="submit" class="btn btn-warning text-dark">Kembali</button>
                            </form>
                        </div>
                        <div class="card-body">
                            <div class="position-relative mb-4">
                                <form method="GET" action="/stok-simpan" class="m-5">
                                    @csrf
                                    <div class="mb-3">
                                        <label for="lokasi" class="form-label">Lokasi :</label>
                                        <select class="form-control" name="lokasiId" id="lokasi" aria-describedby="lokasiItem" required>
                                            @foreach ($lokasies as $valueLokasi)
                                            <option value="{{$valueLokasi->id}}">{{$valueLokasi->nama}}</option>
                                            @endforeach
                                        </select>
                                        <div id="lokasiItem" class="form-text">Pilih Lokasi</div>
                                    </div>
                                    <div class="mb-3">
                                        <label for="varietas" class="form-label">Varietas :</label>
                                        <select class="form-control" name="varietasId" id="varietas" aria-describedby="varietasItem" required>
                                            @foreach ($varietases as $valueVarietas)
                                            <option value="{{$valueVarietas->id}}">{{$valueVarietas->nama}}</option>
                                            @endforeach
                                        </select>
                                        <div id="varietasItem" class="form-text">Pilih Varietas</div>
                                    </div>
                                    <div class="mb-3" style="width: 20%;">
                                        <label for="masuk" class="form-label">Bibit Masuk :</label>
                                        <input type="datetime-local" class="form-control" value="{{ now() }}" id="masuk" name="masuk" aria-describedby="masukItem" required>
                                        <div id="masukItem" class="form-text">Masukan Kapan bibit masuk</div>
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