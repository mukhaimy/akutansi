  <!-- Navbar -->
  <nav class="main-header navbar navbar-expand navbar-white navbar-light">
      <!-- Left navbar links -->
      <ul class="navbar-nav">
          <li class="nav-item">
              <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
          </li>
          <li class="nav-item d-none d-sm-inline-block">
              <a href="{{ url('dashboard') }}" class="nav-link">Dashboard</a>
          </li>
          <li class="nav-item d-none d-sm-inline-block">
              <a href="{{ url('nota') }}" class="nav-link">Keuangan</a>
          </li>
          <li class="nav-item d-none d-sm-inline-block">
              <a href="{{ url('stok') }}" class="nav-link">Stok</a>
          </li>
          <li class="nav-item d-none d-sm-inline-block">
              <a href="{{ url('inventory') }}" class="nav-link">Inventory</a>
          </li>
          <li class="nav-item d-none d-sm-inline-block">
              <a href="{{ url('obat') }}" class="nav-link">Obat</a>
          </li>
          <li class="nav-item d-none d-sm-inline-block">
              <a href="{{ url('data') }}" class="nav-link">Data</a>
          </li>
      </ul>

      <!-- Right navbar links -->
      <ul class="navbar-nav ml-auto">
          <li class="nav-item">
              <a class="nav-link" aria-current="page" href="{{ url('logout') }}">Logout</a>
          </li>
          <span class="navbar-text me-4">
              {{auth()->user()->name}} | {{auth()->user()->role}}
          </span>
      </ul>
  </nav>
  <!-- /.navbar -->