<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="dashboard" class="text-decoration-none brand-link">
        <img src="/AdminLTE-3.2.0/dist/img/office.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
        <span class="brand-text font-weight-light">Mahcota Bumi</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user panel (optional) -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                <img src="/AdminLTE-3.2.0/dist/img/user.png" class="img-circle elevation-2" alt="User Image">
            </div>
            <div class="info text-light">
                {{auth()->user()->name}} | {{auth()->user()->role}}
            </div>
        </div>

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <li class="nav-item">
                    <a href="{{ url('dashboard') }}" class="nav-link">
                        <p>Dashboard</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ url('nota') }}" class="nav-link">
                        <p>Keuangan</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ url('stok') }}" class="nav-link">
                        <p>Stok</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ url('inventory') }}" class="nav-link">
                        <p>Inventory</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ url('obat') }}" class="nav-link">
                        <p>Obat</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ url('data') }}" class="nav-link">
                        <p>Data</p>
                    </a>
                </li>
                <br>
                <li class="nav-item">
                    <a href="{{ url('logout') }}" class="nav-link">
                        <p>Logout</p>
                    </a>
                </li>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>