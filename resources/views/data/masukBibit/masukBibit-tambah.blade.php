@include('layouts.header')
<title>Masuk Bibit - Tambah</title>

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
                    <h3>Masuk</h3>
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
                            <form method="GET" action="/masuk-bibit" class="m-3">
                                @csrf
                                <button type="submit" class="btn btn-warning text-dark">Kembali</button>
                            </form>
                        </div>
                        <div class="card-body">
                            <div class="position-relative mb-4">
                                <form method="GET" action="/masukBibit-simpan" class="m-5">
                                    @csrf
                                    <div class="mb-3" style="width: auto;">
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