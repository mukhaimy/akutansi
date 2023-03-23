@include('layouts.header')
<title>Nota</title>

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
                            <div class="d-flex justify-content-between align-self-center">
                                <div class="p-2">
                                    <button onclick="ExportToExcel('xlsx')">export ke excel</button>
                                </div>
                                @if (auth()->user()->role=="admin")
                                <div class="p-2">
                                    <form method="POST" action="/nota-import" enctype="multipart/form-data">
                                        @csrf
                                        <input type="file" name="file" class="form-control" required>
                                        <br>
                                        <button type="submit">import</button>
                                    </form>
                                </div>
                                @endif
                                <div class="p-2">
                                    @if (auth()->user()->role=="admin")
                                    <form method="GET" action="/nota-tambah">
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
                                            <th scope="col-2">No </th>
                                            <th scope="col-2">Id Nota</th>
                                            <th scope="col-2">Tahun </th>
                                            <th scope="col-2">Bulan </th>
                                            <th scope="col-2">Hari </th>
                                            <th scope="col">Lokasi </th>
                                            <th scope="col">Nama </th>
                                            <th scope="col">Keterangan </th>
                                            <th scope="col">Debit </th>
                                            <th scope="col">Kredit </th>
                                            <th scope="col">Saldo </th>
                                            <th scope="col">Detail </th>
                                            @if (auth()->user()->role=="admin")
                                            <th scope="col">Edit </th>
                                            <th scope="col">Hapus </th>
                                            @endif
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <br>
                                        <hr>
                                        <tr>
                                            @foreach($notas as $value)
                                            <div class="ms-5 me-5">
                                                <form method="GET" action="/nota-update/{{$value->id}}">
                                                    @csrf
                                                    <div class="mb-3">
                                                        <h4>Editing</h4>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="nama" class="form-label">Nama</label>
                                                        <input type="text" class="form-control" id="nama" name="nama" aria-describedby="namaItem" value="{{$value->nama}}">
                                                        <div id="namaItem" class="form-text">Masukan nama item</div>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="keterangan" class="form-label">Keterangan</label>
                                                        <input type="text" class="form-control" id="keterangan" name="keterangan" aria-describedby="keteranganItem" value="{{$value->keterangan}}">
                                                        <div id="keteranganItem" class="form-text">Masukan keterangan item</div>
                                                    </div>
                                                    <button type="submit" class="btn btn-primary text-light">Update</button>
                                                </form>
                                            </div>
                                            @endforeach
                                        </tr>
                                        <hr>
                                        <br>
                                        <tr>
                                            <div class="d-flex flex-row mb-3">
                                                <div class="p-2">
                                                    <form method="GET" action="/pertahun">
                                                        @csrf
                                                        <button type="submit" class="btn btn-secondary text-light">Pertahun</button>
                                                    </form>
                                                </div>
                                                <div class="p-2">
                                                    <form method="GET" action="/perbulan">
                                                        @csrf
                                                        <button type="submit" class="btn btn-secondary text-light">Perbulan</button>
                                                    </form>
                                                </div>
                                            </div>
                                            <div class="d-flex flex-row mb-3">
                                                <div class="p-2">
                                                    <form method="GET" action="/perhari">
                                                        @csrf
                                                        <button type="submit" class="btn btn-secondary text-light">Perhari</button>
                                                    </form>
                                                </div>
                                                <div class="p-2">
                                                    <form method="GET" action="/perlokasi">
                                                        @csrf
                                                        <button type="submit" class="btn btn-secondary text-light">Perlokasi</button>
                                                    </form>
                                                </div>
                                            </div>
                                            <div class="d-flex flex-row mb-3">
                                                <div class="p-2">
                                                    <form method="GET" action="/rekap">
                                                        @csrf
                                                        <button type="submit" class="btn btn-secondary text-light">Rekap</button>
                                                    </form>
                                                </div>
                                            </div>
                                        </tr>
                                        @foreach ($pernotas as $value)
                                        <tr>
                                            <td scope="row">{{$loop->iteration}}</td>
                                            <td>{{$value->id}}</td>
                                            <td>{{$value->tahuns->nama}}</td>
                                            <td>{{$value->bulans->nama}}</td>
                                            <td>{{$value->haries->nama}}</td>
                                            <td>{{$value->lokasies->nama}}</td>
                                            <td>{{$value->nama}}</td>
                                            <td class="text-wrap">{{$value->keterangan}}</td>
                                            <td>
                                                @php
                                                echo "Rp ".number_format($value->debit);
                                                @endphp
                                            </td>
                                            <td>
                                                @php
                                                echo "Rp ".number_format($value->kredit);
                                                @endphp
                                            </td>
                                            <td>
                                                @php
                                                echo "Rp ".number_format($value->saldo);
                                                @endphp
                                            </td>
                                            <td>
                                                <form method="GET" action="/detail/{{$value->id}}">
                                                    @csrf
                                                    <button type="submit" class="btn btn-dark text-light">Detail</button>
                                                </form>
                                            </td>
                                            @if (auth()->user()->role=="admin")
                                            <td>
                                                <form method="GET" action="/nota-edit/{{$value->id}}">
                                                    @csrf
                                                    <button type="submit" class="btn btn-success text-light">Edit</button>
                                                </form>
                                            </td>
                                            <td>
                                                <form method="GET" action="/nota-hapus/{{$value->id}}">
                                                    @csrf
                                                    <button type="submit" class="btn btn-danger text-light">Hapus</button>
                                                </form>
                                            </td>
                                            @endif
                                        </tr>
                                        @endforeach
                                        <tr>
                                            <td scope="row"></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td>Total saldo :</td>
                                            <td>
                                                @php
                                                echo "Rp ".number_format($totalSaldo);
                                                @endphp
                                            </td>
                                            <td></td>
                                            <td></td>
                                            @if (auth()->user()->role=="admin")
                                            <td></td>
                                            <td></td>
                                            @endif
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
            XLSX.writeFile(wb, fn || ('pernota.' + (type || 'xlsx')));
    }
</script>

<!-- Main Footer -->
@include('layouts.footer')