@include('layouts.header')
<title>Dashboard</title>

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
        <div class="col-sm-6">
          <h1 class="m-0">Dashboard</h1>
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
              <div class="d-flex justify-content-between">
                <h3 class="card-title">Keuangan</h3>
              </div>
            </div>
            <div class="card-body">
              <div class="position-relative mb-4">
                <canvas id="keuanganChart44" height="200"></canvas>
              </div>
            </div>
          </div>
        </div>
        <!-- /.card -->
      </div>

      <div class="row">
        <!-- /.col-md-6 -->
        <div class="col-lg-12">
          <div class="card">
            <div class="card-header border-0">
              <div class="d-flex justify-content-between">
                <h3 class="card-title">Bibit</h3>
              </div>
            </div>
            @for ($i = 1; $i <= $lokasiLength; $i++) <div class="card-body">
              <h5>{{$lokasiNama[$i]}}</h5>
              <div class="position-relative mb-4">
                <canvas id="bibitChart{{$i}}" height="200"></canvas>
              </div>
          </div>
          @endfor
        </div>
      </div>
      <!-- /.card -->
    </div>

    <div class="row">
      <!-- /.col-md-6 -->
      <div class="col-lg-12">
        <div class="card">
          <div class="card-header border-0">
            <div class="d-flex justify-content-between">
              <h3 class="card-title">Inventory</h3>
            </div>
          </div>
          <div class="card-body">
            <div class="position-relative mb-4">
              <canvas id="inventoryChart" height="200"></canvas>
            </div>
          </div>
        </div>
      </div>
      <!-- /.card -->
    </div>

    <div class="row">
      <!-- /.col-md-6 -->
      <div class="col-lg-12">
        <div class="card">
          <div class="card-header border-0">
            <div class="d-flex justify-content-between">
              <h3 class="card-title">Obat</h3>
            </div>
          </div>
          @for ($i = 1; $i <= $satuanNamaObatLength; $i++) <div class="card-body">
            <h5>{{$satuanNamaObat[$i-1]}}</h5>
            <div class="position-relative mb-4">
              <canvas id="obatChart{{$i}}" height="200"></canvas>
            </div>
        </div>
        @endfor
      </div>
    </div>
    <!-- /.card -->
  </div>


</div>
<!-- /.col-md-6 -->
</div>
<!-- /.row -->
</div>
<!-- /.container-fluid -->
</div>
<!-- /.content -->
</div>
<!-- /.content-wrapper -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script type="text/javascript">
  var a = <?php echo json_encode($debit); ?>;
  var b = <?php echo json_encode($kredit); ?>;
  var c = <?php echo json_encode($saldo); ?>;
  const ctx = document.getElementById('keuanganChart44').getContext('2d');
  const myChart = new Chart(ctx, {
    type: 'line',
    data: {
      labels: [
        'Januari',
        'Februari',
        'Maret',
        'April',
        'Mei',
        'Juni',
        'Juli',
        'Agustus',
        'September',
        'Oktober',
        'November',
        'Desember'
      ],
      datasets: [{
        label: 'debit',
        data: a,
        backgroundColor: ['rgba(0, 255, 0, 0.5)', ],
        borderColor: ['rgba(0, 255, 0, 1)', ],
        borderWidth: 2,
      }, {
        label: 'kredit',
        data: b,
        backgroundColor: ['rgba(255, 0, 0, 0.5)', ],
        borderColor: ['rgba(255, 0, 0, 1)', ],
        borderWidth: 2,
      }, {
        label: 'Saldo',
        data: c,
        backgroundColor: ['rgba(0, 0, 255, 0.5)', ],
        borderColor: ['rgba(0, 0, 255, 1)', ],
        borderWidth: 2,
      }, ]
    },
    options: {
      maintainAspectRatio: false,
      scales: {
        x: {
          title: {
            display: false,
            text: 'Bulan'
          }
        },
        y: {
          title: {
            display: false,
            text: 'Rp'
          }
        }
      }
    }
  });

  var bibitLabels = <?php echo json_encode($bibitLabels); ?>;
  var dataBibitArr = <?php echo json_encode($dataBibitArr); ?>;
  var lokasiNama = <?php echo json_encode($lokasiNama); ?>;
  var lokasiLength = Object.keys(lokasiNama).length;

  var bibits = [];
  for (let i = 1; i <= lokasiLength; i++) {
    bibits[i - 1] = 'bibitChart' + i;
  }
  // console.log(bibits);
  // console.log(lokasiNama);
  // console.log(dataBibitArr[lokasiNama[1]]);
  var bibitChartElements = [];
  for (let i = 1; i <= lokasiLength; i++) {
    const myBibitChart = new Chart(document.getElementById(bibits[i - 1]).getContext('2d'), {
      type: 'bar',
      data: {
        labels: bibitLabels,
        datasets: dataBibitArr[lokasiNama[i]],
      },
      options: {
        plugins: {
          tooltip: {
            mode: 'index'
          }
        },
        maintainAspectRatio: false,
        scales: {
          x: {
            title: {
              display: false,
              text: 'varietas'
            }
          },
          y: {
            title: {
              display: false,
              text: 'quantitas'
            }
          }
        }
      }
    });
  }

  var dataInventoryArr = <?php echo json_encode($dataInventoryArr); ?>;
  var inventoryLabels = <?php echo json_encode($inventoryLabels); ?>;
  const barchart2 = document.getElementById('inventoryChart').getContext('2d');
  const myBarChart2 = new Chart(barchart2, {
    type: 'bar',
    data: {
      labels: inventoryLabels,
      datasets: dataInventoryArr,
    },
    options: {
      maintainAspectRatio: false,
    }
  });


  var satuanNamaObat = <?php echo json_encode($satuanNamaObat); ?>;
  var satuanNamaObatLength = <?php echo json_encode($satuanNamaObatLength); ?>;
  var dataObatArr = <?php echo json_encode($dataObatArr); ?>;
  var obatLabels = <?php echo json_encode($obatLabels); ?>;
  console.log(obatLabels[satuanNamaObat[0]])
  console.log(dataObatArr[satuanNamaObat[0]])

  var obats = [];
  for (let i = 1; i <= satuanNamaObatLength; i++) {
    obats[i - 1] = 'obatChart' + i;
  }

  for (let i = 1; i <= satuanNamaObatLength; i++) {
    const myObatChart = new Chart(document.getElementById(obats[i - 1]).getContext('2d'), {
      type: 'line',
      data: {
        labels: obatLabels[satuanNamaObat[i - 1]],
        datasets: dataObatArr[satuanNamaObat[i - 1]]
      },
      options: {
        maintainAspectRatio: false,
        scales: {
          x: {
            title: {
              display: false,
              text: 'Tanggal'
            }
          },
          y: {
            title: {
              display: false,
              text: satuanNamaObat[i - 1]
            }
          }
        }
      }
    });
  }



  // const obatChart = document.getElementById('obatChart').getContext('2d');
  // const myObatChart = new Chart(obatChart, {
  //   type: 'line',
  //   data: {
  //     labels: obatLabels,
  //     datasets: [{
  //       label: 'Obat 1',
  //       data: [{
  //         x: '2023',
  //         y: 0
  //       }],
  //       backgroundColor: ['rgba(255, 0, 0, 0.5)', ],
  //       borderColor: ['rgba(255, 0, 0, 1)', ],
  //       borderWidth: 2,
  //     }, {
  //       label: 'Obat 2',
  //       data: [{
  //         x: '2023-3-21',
  //         y: 1
  //       }, {
  //         x: '2023-3-22',
  //         y: 1
  //       }, {
  //         x: '2023-3-23',
  //         y: 1
  //       }],
  //       backgroundColor: ['rgba(0, 255, 0, 0.5)', ],
  //       borderColor: ['rgba(0, 255, 0, 1)', ],
  //       borderWidth: 2,
  //     }]
  //   },
  //   options: {
  //     maintainAspectRatio: false,
  //     scales: {
  //       x: {
  //         title: {
  //           display: false,
  //           text: 'Tanggal'
  //         }
  //       },
  //       y: {
  //         title: {
  //           display: false,
  //           text: 'Liter'
  //         }
  //       }
  //     }
  //   }
  // });
</script>

<!-- Main Footer -->
@include('layouts.footer')