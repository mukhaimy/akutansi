<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Mahcota Bumi</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="AdminLTE-3.2.0/plugins/fontawesome-free/css/all.min.css">
  <!-- icheck bootstrap -->
  <link rel="stylesheet" href="AdminLTE-3.2.0/plugins/icheck-bootstrap/icheck-bootstrap.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="AdminLTE-3.2.0/dist/css/adminlte.min.css">
</head>

<body class="hold-transition login-page">
  <div class="login-box">
    <!-- /.login-logo -->
    <div class="card card-outline card-primary">
      <div class="card-header text-center">
        <p class="h1"><b>Selamat Datang</b></p>
      </div>
      <div class="card-body">
        <form action="post-login" method="post">
          @csrf
          <div class="form-floating mb-3">
            <label for="floatingInput">Username</label>
            <input type="text" class="form-control" id="floatingInput" name="email" placeholder="name@example.com">
          </div>
          <div class="form-floating">
            <label for="floatingPassword">Password</label>
            <input type="password" class="form-control" id="floatingPassword" name="password" placeholder="Password">
          </div>
          <br>
          <button class="w-100 btn btn-lg btn-primary" type="submit">LOGIN</button>
          <p class="mt-5 mb-3 text-muted text-center">&copy; 2023</p>
        </form>
        <!-- /.social-auth-links -->
      </div>
      <!-- /.card-body -->
    </div>
    <!-- /.card -->
  </div>
  <!-- /.login-box -->

  <!-- jQuery -->
  <script src="AdminLTE-3.2.0/plugins/jquery/jquery.min.js"></script>
  <!-- Bootstrap 4 -->
  <script src="AdminLTE-3.2.0/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
  <!-- AdminLTE App -->
  <script src="AdminLTE-3.2.0/dist/js/adminlte.min.js"></script>
</body>

</html>