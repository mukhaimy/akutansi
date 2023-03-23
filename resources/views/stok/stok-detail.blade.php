@include('layouts.header')
<title>Stok - Detail</title>

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
                    <h3>Stok Detail</h3>
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
                                    <form method="POST" action="/stokDetail-import" enctype="multipart/form-data">
                                        @csrf
                                        <input type="file" name="file" class="form-control" required>
                                        <br>
                                        <button type="submit">import</button>
                                    </form>
                                </div>
                                @endif

                                <div class="p-2">
                                    <form method="GET" action="/stok" class="mb-3">
                                        @csrf
                                        <button type="submit" class="btn btn-warning text-dark">Kembali</button>
                                    </form>
                                    @if (auth()->user()->role=="admin")
                                    <form method="GET" action="/stok-detail-tambah">
                                        <input type="hidden" name="stokId" value="{{$stokId}}" />
                                        <button type="submit" class="btn btn-primary text-light">Tambah</button>
                                    </form>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="position-relative mb-4">
                                <div>
                                    <br>
                                    <hr>
                                    @foreach($details as $value)
                                    <div class="ms-5 me-5">
                                        <form method="GET" action="/stok-detail-update/{{$value->id}}">
                                            @csrf
                                            <div class="mb-3">
                                                <label for="keteranganBibit" class="form-label">Keterangan Bibit :</label>
                                                <select class="form-control" name="keteranganBibitId" id="keteranganBibit" aria-describedby="keteranganBibitItem" required>
                                                    <option selected="true" class="bg-success text-white" style="display: none" value="{{$value->keterangan_bibits->id}}">{{$value->keterangan_bibits->nama}}</option>
                                                    @foreach ($keteranganBibits as $valueKeteranganBibit)
                                                    <option value="{{$valueKeteranganBibit->id}}">{{$valueKeteranganBibit->nama}}</option>
                                                    @endforeach
                                                </select>
                                                <div id="keteranganBibitItem" class="form-text">Pilih Keterangan Bibit</div>
                                            </div>
                                            <div class="mb-3">
                                                <label for="kuantitas" class="form-label">Kuantitas Bibit :</label>
                                                <input type="number" class="form-control" id="kuantitas" name="kuantitas" aria-describedby="kuantitasItem" value="{{$value->kuantitas}}" required>
                                                <div id="kuantitasItem" class="form-text">Masukan Kuantitas Bibit</div>
                                            </div>
                                            <input type="hidden" name="stokId" value="{{$value->stok_id}}" />
                                            <button type="submit" class="btn btn-primary text-light">Simpan</button>
                                        </form>
                                    </div>
                                    @endforeach
                                    <hr>
                                    <br>
                                </div>

                                <table id="tbl_exporttable_to_xls" class="table-responsive table table-striped">
                                    <thead>
                                        <tr>
                                            <th scope="col">No</th>
                                            <th style="display: none;" scope="col">Stok_id</th>
                                            <th scope="col">Keterangan</th>
                                            <th scope="col">Kuantitas</th>
                                            <th scope="col">Created_at</th>
                                            <th scope="col">Updated_at</th>
                                            @if (auth()->user()->role=="admin")
                                            <th scope="col">Edit</th>
                                            <th scope="col">Hapus</th>
                                            @endif
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($perdetails as $value)
                                        <tr>
                                            <td scope="row">{{$loop->iteration}}</td>
                                            <td style="display: none;">{{$value->stok_id}}</td>
                                            <td>{{$value->keterangan_bibits->nama}}</td>
                                            <td>{{$value->kuantitas}}</td>
                                            <td>{{$value->created_at}}</td>
                                            <td>{{$value->updated_at}}</td>
                                            @if (auth()->user()->role=="admin")
                                            <td>
                                                <form method="GET" action="/stok-detail-edit/{{$value->id}}">
                                                    @csrf
                                                    <button type="submit" class="btn btn-success text-light">Edit</button>
                                                </form>
                                            </td>
                                            <td>
                                                <form method="GET" action="/stok-detail-hapus/{{$value->id}}">
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
            XLSX.writeFile(wb, fn || ('stok-detail.' + (type || 'xlsx')));
    }
</script>

<!-- Main Footer -->
@include('layouts.footer')