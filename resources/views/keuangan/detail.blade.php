@include('layouts.header')
<title>Detail</title>

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
                    <h3>detail</h3>
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
                                    Tahun :
                                    {{$tahunNama}}
                                    <br>
                                    Bulan :
                                    {{$bulanNama}}
                                    <br>
                                    Hari :
                                    {{$hariNama}}
                                    <br>
                                    Lokasi :
                                    {{$lokasiNama}}
                                    <br>
                                    Nota :
                                    {{$notaNama}}
                                </div>
                            </div>
                            <div class="d-flex justify-content-between align-self-center">

                                <div class="p-2">
                                    <button onclick="ExportToExcel('xlsx')">export ke excel</button>
                                </div>
                                @if (auth()->user()->role=="admin")
                                <div class="p-2">
                                    <form method="POST" action="/detail-import" enctype="multipart/form-data">
                                        @csrf
                                        <input type="file" name="file" class="form-control" required>
                                        <br>
                                        <button type="submit">import</button>
                                    </form>
                                </div>
                                @endif
                                <div class="p-2">
                                    <form method="GET" action="/nota" class="mb-3">
                                        @csrf
                                        <button type="submit" class="btn btn-warning text-dark">Kembali</button>
                                    </form>
                                    @if (auth()->user()->role=="admin")
                                    <form method="GET" action="/detail-tambah">
                                        @csrf
                                        <input type="hidden" name="notaId" value="{{$notaId}}" />
                                        <button type="submit" class="btn btn-primary text-light">Tambah</button>
                                    </form>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="position-relative mb-4">
                                <table id="tbl_exporttable_to_xls" class="table-responsive table  table-striped">
                                    <thead>
                                        <tr>
                                            <th scope="col">No </th>
                                            <th scope="col">Id Detail </th>
                                            <th scope="col">Barang </th>
                                            <th scope="col">Transaksi </th>
                                            <th scope="col">Kuantitas </th>
                                            <th scope="col">Satuan </th>
                                            <th scope="col">Harga </th>
                                            <th scope="col">Saldo </th>
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
                                            @foreach($details as $value)
                                            <div class="ms-5 me-5">
                                                <form method="GET" action="/detail-update/{{$value->id}}">
                                                    @csrf
                                                    <div class="mb-3">
                                                        <h4>Editing</h4>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="barang" class="form-label">Barang :</label>
                                                        <select class="form-control" name="barangId" id="barang" aria-describedby="barangItem">
                                                            <option selected="true" class="bg-success text-white" style="display: none" value="{{$value->barangs->id}}">{{$value->barangs->nama}}</option>
                                                            @foreach ($barangs as $valueBarang)
                                                            <option value="{{$valueBarang->id}}">{{$valueBarang->nama}}</option>
                                                            @endforeach
                                                        </select>
                                                        <div id="barangItem" class="form-text">Pilih barang item</div>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="transaksi" class="form-label">Transaksi :</label>
                                                        <select class="form-control" name="transaksiId" id="transaksi" aria-describedby="transaksiItem">
                                                            <option selected="true" class="bg-success text-white" style="display: none" value="{{$value->transaksies->id}}">{{$value->transaksies->nama}}</option>
                                                            @foreach ($transaksies as $valueTransaksi)
                                                            <option value="{{$valueTransaksi->id}}">{{$valueTransaksi->nama}}</option>
                                                            @endforeach
                                                        </select>
                                                        <div id="transkasiItem" class="form-text">Pilih barang item</div>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="kuantitas" class="form-label">Kuantitas</label>
                                                        <input type="number" class="form-control" id="kuantitas" name="kuantitas" aria-describedby="kuantitasItem" value="{{$value->kuantitas}}" required>
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
                                        @foreach ($perdetails as $value)
                                        <tr>
                                            <td scope="row">{{$loop->iteration}}</td>
                                            <td>{{$value->id}}</td>
                                            <td>{{$value->barangs->nama}}</td>
                                            <td>{{$value->transaksies->nama}}</td>
                                            <td>{{$value->kuantitas}}</td>
                                            <td>{{$value->satuans->nama}}</td>
                                            <td>
                                                @php
                                                echo "Rp ".number_format($value->hargas->harga);
                                                @endphp
                                            </td>
                                            <td>
                                                @php
                                                echo "Rp ".number_format($value->saldo);
                                                @endphp
                                            </td>
                                            <td>{{$value->keterangan}}</td>
                                            @if (auth()->user()->role=="admin")
                                            <td>
                                                <form method="GET" action="/detail-edit/{{$value->id}}">
                                                    @csrf
                                                    <button type="submit" class="btn btn-success text-light">Edit</button>
                                                </form>
                                            </td>
                                            <td>
                                                <form method="GET" action="/detail-hapus/{{$value->id}}">
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

                                            <td>Total saldo :</td>
                                            <td>
                                                @php
                                                echo "Rp ".number_format($totalSaldo);
                                                @endphp
                                            </td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            @if (auth()->user()->role=="admin")

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
            XLSX.writeFile(wb, fn || ('perdetail {{$hariNama}} {{$bulanNama}} {{$tahunNama}} {{$lokasiNama}} {{$notaNama}}.' + (type || 'xlsx')));
    }
</script>

<!-- Main Footer -->
@include('layouts.footer')