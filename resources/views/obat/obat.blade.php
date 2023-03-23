@include('layouts.header')
<title>Obat</title>

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
                    <h3>Obat</h3>
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
                                    <form method="POST" action="/obat-import" enctype="multipart/form-data">
                                        @csrf
                                        <input type="file" name="file" class="form-control" required>
                                        <br>
                                        <button type="submit">import</button>
                                    </form>
                                </div>
                                @endif
                                <div class="p-2">
                                    @if (auth()->user()->role=="admin")
                                    <form method="GET" action="/obat-tambah">
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
                                    @foreach($obats as $value)
                                    <div class="ms-5 me-5">
                                        <form method="GET" action="/obat-update/{{$value->id}}">
                                            @csrf
                                            <div class="mb-3">
                                                <label for="tahun" class="form-label">Tahun :</label>
                                                <select class="form-control" name="tahunId" id="tahun" aria-describedby="tahunItem" required>
                                                    <option selected="true" class="bg-success text-white" style="display: none" value="{{$tahunId}}">{{$tahunNama}}</option>
                                                    @foreach ($tahuns as $valueTahun)
                                                    <option value="{{$valueTahun->id}}">{{$valueTahun->nama}}</option>
                                                    @endforeach
                                                </select>
                                                <div id="tahunItem" class="form-text">Pilih Tahun</div>
                                            </div>
                                            <div class="mb-3">
                                                <label for="bulan" class="form-label">Bulan :</label>
                                                <select class="form-control" name="bulanId" id="bulan" aria-describedby="bulanItem" required>
                                                    <option selected="true" class="bg-success text-white" style="display: none" value="{{$bulanId}}">{{$bulanNama}}</option>
                                                    @foreach ($bulans as $valueBulan)
                                                    <option value="{{$valueBulan->id}}">{{$valueBulan->nama}}</option>
                                                    @endforeach
                                                </select>
                                                <div id="bulanItem" class="form-text">Pilih Bulan</div>
                                            </div>
                                            <div class="mb-3">
                                                <label for="hari" class="form-label">Hari :</label>
                                                <select class="form-control" name="hariId" id="hari" aria-describedby="hariItem" required>
                                                    <option selected="true" class="bg-success text-white" style="display: none" value="{{$hariId}}">{{$hariNama}}</option>
                                                    @foreach ($haries as $valueHari)
                                                    <option value="{{$valueHari->id}}">{{$valueHari->nama}}</option>
                                                    @endforeach
                                                </select>
                                                <div id="hariItem" class="form-text">Pilih Hari</div>
                                            </div>
                                            <div class="mb-3">
                                                <label for="lokasi" class="form-label">Lokasi :</label>
                                                <select class="form-control" name="lokasiId" id="lokasi" aria-describedby="lokasiItem" required>
                                                    <option selected="true" class="bg-success text-white" style="display: none" value="{{$lokasiId}}">{{$lokasiNama}}</option>
                                                    @foreach ($lokasies as $valueLokasi)
                                                    <option value="{{$valueLokasi->id}}">{{$valueLokasi->nama}}</option>
                                                    @endforeach
                                                </select>
                                                <div id="lokasiItem" class="form-text">Pilih Lokasi</div>
                                            </div>
                                            <div class="mb-3">
                                                <label for="barang" class="form-label">Barang</label>
                                                <input type="text" id="search" name="search" class="form-control" value="{{$barangNama}}" aria-describedby="barangItem" required />
                                                <div id="barangItem" class="form-text">Pilih barang item</div>
                                            </div>
                                            <script src="https://code.jquery.com/jquery-3.6.3.min.js" integrity="sha256-pvPw+upLPUjgMXY0G+8O0xUf+/Im1MZjXxxgOcBQBXU=" crossorigin="anonymous"></script>
                                            <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-3-typeahead/4.0.2/bootstrap3-typeahead.min.js" integrity="sha512-HWlJyU4ut5HkEj0QsK/IxBCY55n5ZpskyjVlAoV9Z7XQwwkqXoYdCIC93/htL3Gu5H3R4an/S0h2NXfbZk3g7w==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
                                            </script>
                                            <script type="text/javascript">
                                                var route = "{{ url('autocomplete-search') }}";
                                                $('#search').typeahead({
                                                    source: function(query, process) {
                                                        return $.get(route, {
                                                            query: query
                                                        }, function(data) {
                                                            return process(data);
                                                        });
                                                    }
                                                });
                                            </script>
                                            <div class="mb-3">
                                                <label for="satuan" class="form-label">Satuan :</label>
                                                <select class="form-control" name="satuanId" id="satuan" aria-describedby="satuanItem" required>
                                                    <option selected="true" class="bg-success text-white" style="display: none" value="{{$satuanId}}">{{$satuanNama}}</option>
                                                    @foreach ($satuans as $valueSatuan)
                                                    <option value="{{$valueSatuan->id}}">{{$valueSatuan->nama}}</option>
                                                    @endforeach
                                                </select>
                                                <div id="satuanItem" class="form-text">Pilih Satuan</div>
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
                                            <th scope="col">Tahun</th>
                                            <th scope="col">Bulan</th>
                                            <th scope="col">Hari</th>
                                            <th scope="col">Lokasi</th>
                                            <th scope="col">Barang</th>
                                            <th scope="col">Satuan</th>
                                            @if (auth()->user()->role=="admin")
                                            <th scope="col">Edit</th>
                                            <th scope="col">Hapus</th>
                                            @endif
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($perobats as $value)
                                        <tr>
                                            <td scope="row">{{$loop->iteration}}</td>
                                            <td>{{$value->tahuns->nama}}</td>
                                            <td>{{$value->bulans->nama}}</td>
                                            <td>{{$value->haries->nama}}</td>
                                            <td>{{$value->lokasies->nama}}</td>
                                            <td>{{$value->barangs->nama}}</td>
                                            <td>{{$value->satuans->nama}}</td>
                                            @if (auth()->user()->role=="admin")
                                            <td>
                                                <form method="GET" action="/obat-edit/{{$value->id}}">
                                                    @csrf
                                                    <button type="submit" class="btn btn-success text-light">Edit</button>
                                                </form>
                                            </td>
                                            <td>
                                                <form method="GET" action="/obat-hapus/{{$value->id}}">
                                                    @csrf
                                                    <button type="submit" class="btn btn-danger text-light">Hapus</button>
                                                </form>
                                            </td>
                                            @endif
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                {{ $perobats->links('pagination::bootstrap-5') }}
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