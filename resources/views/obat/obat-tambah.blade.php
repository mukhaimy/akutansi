@include('layouts.header')
<title>Obat - Tambah</title>

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
                            <form method="GET" action="/obat" class="m-3">
                                @csrf
                                <button type="submit" class="btn btn-warning text-dark">Kembali</button>
                            </form>
                        </div>
                        <div class="card-body">
                            <div class="position-relative mb-4">
                                <form method="GET" action="/obat-simpan" class="m-5">
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
                                            @foreach ($lokasies as $valueLokasi)
                                            <option value="{{$valueLokasi->id}}">{{$valueLokasi->nama}}</option>
                                            @endforeach
                                        </select>
                                        <div id="lokasiItem" class="form-text">Pilih Lokasi</div>
                                    </div>
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
                                        <label for="satuan" class="form-label">Satuan :</label>
                                        <select class="form-control" name="satuanId" id="satuan" aria-describedby="satuanItem" required>
                                            @foreach ($satuans as $valueSatuan)
                                            <option value="{{$valueSatuan->id}}">{{$valueSatuan->nama}}</option>
                                            @endforeach
                                        </select>
                                        <div id="satuanItem" class="form-text">Pilih Satuan</div>
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