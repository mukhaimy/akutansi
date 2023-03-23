@include('layouts.header')
<title>Data</title>

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
                    <h1 class="m-0">Data</h1>
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

                        </div>
                        <div class="card-body">
                            <div class="position-relative mb-4">
                                <table class="table-responsive table table-borderless">
                                    <tbody>
                                        <tr>
                                            <td>
                                                <form method="GEt" action="/barang">
                                                    @csrf
                                                    <button type="submit" class="btn btn-dark btn-lg">Barang</button>
                                                </form>
                                            </td>
                                            <td>
                                                <form method="GET" action="/tahun">
                                                    @csrf
                                                    <button type="submit" class="btn btn-dark btn-lg">Tahun</button>
                                                </form>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <form method="GET" action="/satuan">
                                                    @csrf
                                                    <button type="submit" class="btn btn-dark btn-lg">Satuan</button>
                                                </form>
                                            </td>

                                            <td>
                                                <form method="GET" action="/lokasi">
                                                    @csrf
                                                    <button type="submit" class="btn btn-dark btn-lg">Lokasi</button>
                                                </form>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <form method="GET" action="/transaksi">
                                                    @csrf
                                                    <button type="submit" class="btn btn-dark btn-lg">Transaksi</button>
                                                </form>
                                            </td>
                                            <td>
                                                <form method="GET" action="/varietas">
                                                    @csrf
                                                    <button type="submit" class="btn btn-dark btn-lg">Varietas</button>
                                                </form>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <form method="GET" action="/keterangan-bibit">
                                                    @csrf
                                                    <button type="submit" class="btn btn-dark btn-lg">Keterangan Bibit</button>
                                                </form>
                                            </td>
                                            <td>
                                                <form method="GET" action="/masuk-bibit">
                                                    @csrf
                                                    <button type="submit" class="btn btn-dark btn-lg">Masuk Bibit</button>
                                                </form>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <form method="GET" action="/jenis">
                                                    @csrf
                                                    <button type="submit" class="btn btn-dark btn-lg">Jenis</button>
                                                </form>
                                            </td>
                                            <td>
                                                <form method="GET" action="/user">
                                                    @csrf
                                                    <button type="submit" class="btn btn-dark btn-lg">User</button>
                                                </form>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
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