@include('layouts.header')
<title>Perlokasi - Rekap</title>

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
                    <h3>Perlokasi</h3>
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
                                    <form method="GET" action="/nota">
                                        @csrf
                                        <button type="submit" class="btn btn-warning text-dark">kembali</button>
                                    </form>
                                    <br>
                                    <button class="mb-3" onclick="ExportToExcel('xlsx')">export ke excel</button>
                                    <form method="GET" action="/perlokasi">
                                        <div class="row">
                                            <div class="col mb-3">
                                                <label for="tahun" class="form-label">Pilih Tahun :</label>
                                                <select class="form-control" name="tahunId" id="tahun" aria-describedby="tahunItem">
                                                    <option selected="true" class="bg-success text-white" style="display: none" value="{{$tahunId}}">{{$tahunNama}}</option>
                                                    @foreach ($pertahuns as $valueTahun)
                                                    <option value="{{$valueTahun->id}}">{{$valueTahun->nama}}</option>
                                                    @endforeach
                                                    <option class="bg-warning text-dark" value="{{null}}">kosong</option>
                                                </select>
                                            </div>
                                            <div class="col mb-3">
                                                <label for="bulan" class="form-label">Pilih Bulan :</label>
                                                <select class="form-control" name="bulanId" id="bulan" aria-describedby="bulanItem">
                                                    <option selected="true" class="bg-success text-white" style="display: none" value="{{$bulanId}}">{{$bulanNama}}</option>
                                                    @foreach ($perbulans as $valueBulan)
                                                    <option value="{{$valueBulan->id}}">{{$valueBulan->nama}}</option>
                                                    @endforeach
                                                    <option class="bg-warning text-dark" value="{{null}}">kosong</option>
                                                </select>
                                            </div>
                                            <div class="col mb-3">
                                                <label for="hari" class="form-label">Pilih Hari :</label>
                                                <select class="form-control" name="hariId" id="hari" aria-describedby="hariItem">
                                                    <option selected="true" class="bg-success text-white" style="display: none" value="{{$hariId}}">{{$hariNama}}</option>
                                                    @foreach ($perharies as $valueHari)
                                                    <option value="{{$valueHari->id}}">{{$valueHari->nama}}</option>
                                                    @endforeach
                                                    <option class="bg-warning text-dark" value="{{null}}">kosong</option>
                                                </select>
                                            </div>
                                            <button type="submit" class="btn btn-primary text-light">proses</button>
                                        </div>
                                    </form>
                                </div>
                                <div class="p-2">

                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="position-relative mb-4">
                                <table id="tbl_exporttable_to_xls" class="table-responsive table table-striped">
                                    <thead>
                                        <tr>
                                            <th scope="col">No</th>
                                            <th style="display: none;" scope="col">Tahun</th>
                                            <th style="display: none;" scope="col">Bulan</th>
                                            <th style="display: none;" scope="col">Hari</th>
                                            <th scope="col">Lokasi</th>
                                            <th scope="col">Debit</th>
                                            <th scope="col">Kredit</th>
                                            <th scope="col">Saldo</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($perlokasies as $value)
                                        <tr>
                                            <td scope="row">{{$loop->iteration}}</td>
                                            <td style="display: none;">{{$tahunNama}}</td>
                                            <td style="display: none;">{{$bulanNama}}</td>
                                            <td style="display: none;">{{$hariNama}}</td>
                                            <td>{{$value->nama}}</td>
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
                                        </tr>
                                        @endforeach
                                        <tr>
                                            <td scope="row"></td>
                                            <td style="display: none;"></td>
                                            <td style="display: none;"></td>
                                            <td style="display: none;"></td>
                                            <td></td>
                                            <td></td>
                                            <td>Total Saldo :</td>
                                            <td>
                                                @php
                                                echo "Rp ".number_format($totalSaldo);
                                                @endphp
                                            </td>
                                            <td></td>
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
            XLSX.writeFile(wb, fn || ('perlokasi {{$hariNama}} {{$bulanNama}} {{$tahunNama}}.' + (type || 'xlsx')));
    }
</script>

<!-- Main Footer -->
@include('layouts.footer')