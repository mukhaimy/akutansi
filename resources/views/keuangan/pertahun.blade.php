@include('layouts.header')
<title>Pertahun - Rekap</title>

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
                    <h3>Pertahun</h3>
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
                                    <button onclick="ExportToExcel('xlsx')">export ke excel</button>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="position-relative mb-4">
                                <table id="tbl_exporttable_to_xls" class="table-responsive table table-striped">
                                    <thead>
                                        <tr>
                                            <th scope="col">No</th>
                                            <th scope="col">Tahun</th>
                                            <th scope="col">Debit</th>
                                            <th scope="col">Kredit</th>
                                            <th scope="col">Saldo</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($pertahuns as $value)
                                        <tr>
                                            <td scope="row">{{$loop->iteration}}</td>
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
                                            <td></td>
                                            <td></td>
                                            <td>Total saldo :</td>
                                            <td>
                                                @php
                                                echo "Rp ".number_format($totalSaldo);
                                                @endphp
                                            </td>
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
            XLSX.writeFile(wb, fn || ('pertahun.' + (type || 'xlsx')));
    }
</script>

<!-- Main Footer -->
@include('layouts.footer')