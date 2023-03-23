@include('layouts.header')
<title>Keterangan Bibit</title>

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
                    <h3>Keterangan Bibit</h3>
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
                            <div class="d-flex justify-content-between align-self-center">
                                <div class="p-2">
                                    <button onclick="ExportToExcel('xlsx')">export ke excel</button>
                                </div>
                                @if (auth()->user()->role=="admin")
                                <div class="p-2">
                                    <form method="POST" action="/keteranganBibit-import" enctype="multipart/form-data">
                                        @csrf
                                        <input type="file" name="file" class="form-control" required>
                                        <br>
                                        <button type="submit">import</button>
                                    </form>
                                </div>
                                @endif
                                <div class="p-2">
                                    <form method="GET" action="/data" class="mb-3">
                                        @csrf
                                        <button type="submit" class="btn btn-warning text-dark">Kembali</button>
                                    </form>
                                    @if (auth()->user()->role=="admin")
                                    <form method="GET" action="/keteranganBibit-tambah">
                                        <button type="submit" class="btn btn-primary text-light">Tambah</button>
                                    </form>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="position-relative mb-4">
                                <table id="tbl_exporttable_to_xls" class="table-responsive table table-striped">
                                    <thead>
                                        <tr>
                                            <th scope="col">No</th>
                                            <th scope="col">Nama</th>
                                            <th scope="col">Created</th>
                                            <th scope="col">Updated</th>
                                            @if (auth()->user()->role=="admin")
                                            <th scope="col">Edit</th>
                                            <th scope="col">Hapus</th>
                                            @endif
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <br>
                                        <hr>
                                        <tr>
                                            @foreach($keteranganBibits as $value)
                                            <div class="ms-5 me-5">
                                                <form method="GET" action="/keteranganBibit-update/{{$value->id}}">
                                                    @csrf
                                                    <div class="mb-3">
                                                        <h4>Editing</h4>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="keteranganBibit" class="form-label">Keterangan Bibit</label>
                                                        <input type="text" class="form-control" id="keterangangBibit" name="keteranganBibit" aria-describedby="keteranganBibitItem" value="{{$value->nama}}" required>
                                                        <div id="keteranganBibitItem" class="form-text">Masukan keterangan bibit item</div>
                                                    </div>
                                                    <button type="submit" class="btn btn-primary text-light">Update</button>
                                                </form>
                                            </div>
                                            @endforeach
                                        </tr>
                                        <hr>
                                        <br>
                                        @foreach ($perketeranganBibits as $value)
                                        <tr>
                                            <td scope="row">{{$loop->iteration}}</td>
                                            <td>{{$value->nama}}</td>
                                            <td>{{$value->created_at}}</td>
                                            <td>{{$value->updated_at}}</td>
                                            @if (auth()->user()->role=="admin")
                                            <td>
                                                <form method="GET" action="/keteranganBibit-edit/{{$value->id}}">
                                                    @csrf
                                                    <button type="submit" class="btn btn-success text-light">Edit</button>
                                                </form>
                                            </td>
                                            <td>
                                                <form method="GET" action="/keteranganBibit-hapus/{{$value->id}}">
                                                    @csrf
                                                    <button type="submit" class="btn btn-danger text-light">Hapus</button>
                                                </form>
                                            </td>
                                            @endif
                                        </tr>
                                        @endforeach
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

<script>
    function ExportToExcel(type, fn, dl) {
        var elt = document.getElementById('tbl_exporttable_to_xls');
        var wb = XLSX.utils.table_to_book(elt, {
            sheet: "sheet1"
        });
        return dl ?
            XLSX.write(wb, {
                bookType: type,
                bookSST: true,
                type: 'base64'
            }) :
            XLSX.writeFile(wb, fn || ('keterangan bibit.' + (type || 'xlsx')));
    }
</script>

<!-- Main Footer -->
@include('layouts.footer')