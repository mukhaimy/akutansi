@include('layouts.header')
<title>Stok</title>

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
                            <div class="d-flex justify-content-between align-self-center">
                                <div class="p-2">
                                    <button onclick="ExportToExcel('xlsx')">export ke excel</button>
                                </div>
                                @if (auth()->user()->role=="admin")
                                <div class="p-2">
                                    <form method="POST" action="/stok-import" enctype="multipart/form-data">
                                        @csrf
                                        <input type="file" name="file" class="form-control" required>
                                        <br>
                                        <button type="submit">import</button>
                                    </form>
                                </div>
                                @endif
                                <div class="p-2">
                                    @if (auth()->user()->role=="admin")
                                    <form method="GET" action="/stok-tambah">
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
                                    @foreach($stoks as $value)
                                    <div class="ms-5 me-5">
                                        <form method="GET" action="/stok-update/{{$value->id}}">
                                            @csrf
                                            <div class="mb-3">
                                                <label for="lokasi" class="form-label">Lokasi :</label>
                                                <select class="form-control" name="lokasiId" id="lokasi" aria-describedby="lokasiItem" required>
                                                    <option selected="true" class="bg-success text-white" style="display: none" value="{{$value->lokasies->id}}">{{$value->lokasies->nama}}</option>
                                                    @foreach ($lokasies as $valueLokasi)
                                                    <option value="{{$valueLokasi->id}}">{{$valueLokasi->nama}}</option>
                                                    @endforeach
                                                </select>
                                                <div id="lokasiItem" class="form-text">Pilih Lokasi</div>
                                            </div>
                                            <div class="mb-3">
                                                <label for="varietas" class="form-label">Varietas :</label>
                                                <select class="form-control" name="varietasId" id="varietas" aria-describedby="varietasItem" required>
                                                    <option selected="true" class="bg-success text-white" style="display: none" value="{{$value->varietases->id}}">{{$value->varietases->nama}}</option>
                                                    @foreach ($varietases as $valueVarietas)
                                                    <option value="{{$valueVarietas->id}}">{{$valueVarietas->nama}}</option>
                                                    @endforeach
                                                </select>
                                                <div id="varietasItem" class="form-text">Pilih Varietas</div>
                                            </div>
                                            <div class="mb-3">
                                                <label for="masuk" class="form-label">Bibit Masuk :</label>
                                                <input type="datetime-local" class="form-control" value="{{$value->masukBibits->masuk}}" id="masuk" name="masuk" aria-describedby="masukItem" required>
                                                <div id="masukItem" class="form-text">Masukan Kapan bibit masuk</div>
                                            </div>
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
                                            <th scope="col">Lokasi</th>
                                            <th scope="col">Varietas</th>
                                            <th scope="col">Penyedia</th>
                                            <th scope="col">Sejak Masuk</th>
                                            <th scope="col">Bagus</th>
                                            <th scope="col">Karantina</th>
                                            <th scope="col">Mati</th>
                                            <th scope="col">Lain - lain</th>
                                            <th scope="col">Jumlah</th>
                                            <th scope="col">Detail</th>
                                            @if (auth()->user()->role=="admin")
                                            <th scope="col">Edit</th>
                                            <th scope="col">Hapus</th>
                                            @endif
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <div class="d-flex flex-row mb-3">
                                                <div class="p-2">
                                                    <form method="GET" action="/stok/cari">
                                                        <div class="input-group">
                                                            <input type="search" name="cari" class="form-control rounded" placeholder="nama lokasi ..." aria-label="Search" aria-describedby="search-addon" />
                                                            <button type="submit" class="btn btn-outline-primary">cari</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </tr>
                                        @foreach ($perstoks as $value)
                                        <tr>
                                            <td scope="row">{{$loop->iteration}}</td>
                                            <td>{{$value->lokasies->nama}}</td>
                                            <td>{{$value->varietases->nama}}</td>
                                            <td>{{$value->varietases->penyedia}}</td>
                                            <td>{{$value->masukBibits->selisih->format('%m bulan %d hari')}}</td>
                                            <td>{{$value->bagus}}</td>
                                            <td>{{$value->karantina}}</td>
                                            <td>{{$value->mati}}</td>
                                            <td>{{$value->lain}}</td>
                                            <td>{{$value->jumlah}}</td>
                                            <td>
                                                <form method="GET" action="/stok-detail/{{$value->id}}">
                                                    @csrf
                                                    <button type="submit" class="btn btn-dark text-light">Detail</button>
                                                </form>
                                            </td>
                                            @if (auth()->user()->role=="admin")
                                            <td>
                                                <form method="GET" action="/stok-edit/{{$value->id}}">
                                                    @csrf
                                                    <button type="submit" class="btn btn-success text-light">Edit</button>
                                                </form>
                                            </td>
                                            <td>
                                                <form method="GET" action="/stok-hapus/{{$value->id}}">
                                                    @csrf
                                                    <button type="submit" class="btn btn-danger text-light">Hapus</button>
                                                </form>
                                            </td>
                                            @endif
                                        </tr>
                                        @endforeach
                                        <tr>
                                            <th scope="col"></th>
                                            <th scope="col"></th>
                                            <th scope="col"></th>
                                            <th scope="col"></th>
                                            <th scope="col"></th>
                                            <th scope="col">Total Bagus</th>
                                            <th scope="col">Total Karantina</th>
                                            <th scope="col">Total Mati</th>
                                            <th scope="col">Total Lain - Lain</th>
                                            <th scope="col">Total Jumlah</th>
                                            <th scope="col"></th>
                                            @if (auth()->user()->role=="admin")
                                            <th scope="col"></th>
                                            <th scope="col"></th>
                                            @endif
                                        </tr>
                                        <tr>
                                            <td scope="row"></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td>{{$totalBagus}}</td>
                                            <td>{{$totalKarantina}}</td>
                                            <td>{{$totalMati}}</td>
                                            <td>{{$totalLain}}</td>
                                            <td>{{$totalSemua}}</td>
                                            @if (auth()->user()->role=="admin")
                                            <td></td>
                                            <td></td>
                                            @endif
                                        </tr>
                                    </tbody>
                                </table>
                                {{ $perstoks->links('pagination::bootstrap-5') }}
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
            XLSX.writeFile(wb, fn || ('stok.' + (type || 'xlsx')));
    }
</script>

<!-- Main Footer -->
@include('layouts.footer')