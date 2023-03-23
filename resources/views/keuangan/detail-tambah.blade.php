@include('layouts.header')
<title>Detail - Tambah</title>
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
                            <form method="GET" action="/detail/{{$notaId}}" class="m-3">
                                @csrf
                                <button type="submit" class="btn btn-warning text-dark">Kembali</button>
                            </form>
                        </div>
                        <div class="card-body">
                            <div class="position-relative mb-4">
                                <form method="GET" action="/detail-simpan" class="m-5">
                                    @csrf
                                    <div class="mb-3">
                                        <label for="barang" class="form-label">Barang</label>
                                        <input type="text" id="search" name="search" class="form-control" aria-describedby="barangItem" required />
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
                                        <label for="Satuan" class="form-label">Satuan</label>
                                        <select class="form-control" name="satuanId" id="satuan" aria-describedby="satuanItem">
                                            @foreach ($satuans as $valueSatuan)
                                            <option value="{{$valueSatuan->id}}">{{$valueSatuan->nama}}</option>
                                            @endforeach
                                        </select>
                                        <div id="satuanItem" class="form-text">Pilih satuan item</div>
                                    </div>
                                    <div class="mb-3">
                                        <label for="harga" class="form-label">Harga</label>
                                        <input type="number" step="0.001" class="form-control" id="harga" name="harga" aria-describedby="hargaItem" required>
                                        <div id="hargaItem" class="form-text">Masukan harga item</div>
                                    </div>
                                    <div class="mb-3">
                                        <label for="Transaksi" class="form-label">Transaksi</label>
                                        <select class="form-control" name="transaksiId" id="transaksi" aria-describedby="transaksiItem">
                                            @foreach ($transaksies as $valueTransaksi)
                                            <option value="{{$valueTransaksi->id}}">{{$valueTransaksi->nama}}</option>
                                            @endforeach
                                        </select>
                                        <div id="transkasiItem" class="form-text">Pilih transaksi item</div>
                                    </div>
                                    <div class="mb-3">
                                        <label for="kuantitas" class="form-label">Kuantitas</label>
                                        <input type="number" step="0.001" class="form-control" id="kuantitas" name="kuantitas" aria-describedby="kuantitasItem" required>
                                        <div id="kuantitasItem" class="form-text">Masukan kuantitas item</div>
                                    </div>
                                    <div class="mb-3">
                                        <label for="keterangan" class="form-label">Keterangan</label>
                                        <input type="text" class="form-control" id="keterangan" name="keterangan" aria-describedby="keteranganItem">
                                        <div id="keteranganItem" class="form-text">Masukan keterangan item</div>
                                    </div>
                                    <input type="hidden" name="notaId" value="{{$notaId}}" />
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