@include('layouts.header')
<title>Stok - Detail - Tambah</title>

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
                            <form method="GET" action="/stok-detail/{{$stokId}}" class="m-3">
                                @csrf
                                <button type="submit" class="btn btn-warning text-dark">Kembali</button>
                            </form>
                        </div>
                        <div class="card-body">
                            <div class="position-relative mb-4">
                                <form method="GET" action="/stok-detail-simpan" class="m-5">
                                    @csrf
                                    <div class="mb-3">
                                        <label for="keteranganBibit" class="form-label">Keterangan Bibit :</label>
                                        <select class="form-control" name="keteranganBibitId" id="keteranganBibit" aria-describedby="keteranganBibitItem" required>
                                            @foreach ($keteranganBibits as $valueKeteranganBibit)
                                            <option value="{{$valueKeteranganBibit->id}}">{{$valueKeteranganBibit->nama}}</option>
                                            @endforeach
                                        </select>
                                        <div id="keteranganBibitItem" class="form-text">Pilih Keterangan Bibit</div>
                                    </div>
                                    <div class="mb-3">
                                        <label for="kuantitas" class="form-label">Kuantitas Bibit :</label>
                                        <input type="number" class="form-control" id="kuantitas" name="kuantitas" aria-describedby="kuantitasItem" required>
                                        <div id="kuantitasItem" class="form-text">Masukan Kuantitas Bibit</div>
                                    </div>
                                    <input type="hidden" name="stokId" value="{{$stokId}}" />
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