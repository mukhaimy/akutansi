@include('layouts.header')
<title>Varietas - Tambah</title>

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
                    <h3>Varietas</h3>
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
                            <form method="GET" action="/varietas" class="m-3">
                                @csrf
                                <button type="submit" class="btn btn-warning text-dark">Kembali</button>
                            </form>
                        </div>
                        <div class="card-body">
                            <div class="position-relative mb-4">
                                <form method="GET" action="/varietas-simpan" class="m-5">
                                    @csrf
                                    <div class="mb-3">
                                        <label for="varietas" class="form-label">Varietas</label>
                                        <input type="text" class="form-control" id="varietas" name="varietas" aria-describedby="varietasItem" required>
                                        <div id="varietasItem" class="form-text">Masukan varietas item</div>
                                    </div>
                                    <div class="mb-3">
                                        <label for="penyedia" class="form-label">Penyedia</label>
                                        <input type="text" class="form-control" id="penyedia" name="penyedia" aria-describedby="penyediaItem" required>
                                        <div id="penyediaItem" class="form-text">Masukan penyedia item</div>
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