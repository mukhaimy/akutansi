@include('layouts.header')
<title>Inventory</title>

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
                            <div class="d-flex justify-content-between align-self-center">
                                <div class="p-2">
                                    <button onclick="ExportToExcel('xlsx')">export ke excel</button>
                                </div>
                                @if (auth()->user()->role=="admin")
                                <div class="p-2">
                                    <form method="POST" action="/inventory-import" enctype="multipart/form-data">
                                        @csrf
                                        <input type="file" name="file" class="form-control" required>
                                        <br>
                                        <button type="submit">import</button>
                                    </form>
                                </div>
                                @endif
                                <div class="p-2">
                                    @if (auth()->user()->role=="admin")
                                    <form method="GET" action="/inventory-tambah">
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
                                            <th scope="col-2">Id Inventory </th>
                                            <th scope="col-2">Lokasi </th>
                                            <th scope="col-2">Nama </th>
                                            <th scope="col-2">Jenis </th>
                                            <th scope="col-2">Satuan </th>
                                            <th scope="col">Kuantitas </th>
                                            <th scope="col">Keterangan </th>
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
                                            @foreach($inventories as $value)
                                            <div class="ms-5 me-5">
                                                <form method="GET" action="/inventory-update/{{$value->id}}">
                                                    @csrf
                                                    <div class="mb-3">
                                                        <h4>Editing</h4>
                                                    </div>
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
                                                        <input type="text" class="form-control" id="nama" name="nama" aria-describedby="namaItem" value="{{$value->nama}}">
                                                        <div id="namaItem" class="form-text">Masukan nama item</div>
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
                                                        <input type="text" class="form-control" id="kuantitas" name="kuantitas" aria-describedby="kuantitasItem" value="{{$value->kuantitas}}">
                                                        <div id="kuantitasItem" class="form-text">Masukan kuantitas item</div>
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
                                        </tr>
                                        @foreach ($perinventories as $value)
                                        <tr>
                                            <td scope="row">{{$loop->iteration}}</td>
                                            <td>{{$value->id}}</td>
                                            <td>{{$value->lokasies->nama}}</td>
                                            <td>{{$value->nama}}</td>
                                            <td>{{$value->jenises->nama}}</td>
                                            <td>{{$value->satuans->nama}}</td>
                                            <td>{{$value->kuantitas}}</td>
                                            <td class="text-wrap">{{$value->keterangan}}</td>
                                            @if (auth()->user()->role=="admin")
                                            <td>
                                                <form method="GET" action="/inventory-edit/{{$value->id}}">
                                                    @csrf
                                                    <button type="submit" class="btn btn-success text-light">Edit</button>
                                                </form>
                                            </td>
                                            <td>
                                                <form method="GET" action="/inventory-hapus/{{$value->id}}">
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
            XLSX.writeFile(wb, fn || ('inventory.' + (type || 'xlsx')));
    }
</script>

<!-- Main Footer -->
@include('layouts.footer')