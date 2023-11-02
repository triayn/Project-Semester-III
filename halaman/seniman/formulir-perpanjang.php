<?php
require_once('../../web/koneksi.php');
require_once('../../web/authenticate.php');
$database = koneksi::getInstance();
$conn = $database->getConnection();
$userAuth = authenticate($_POST, [
  'uri' => $_SERVER['REQUEST_URI'],
  'method' => $_SERVER['REQUEST_METHOD']
], $conn);
if ($userAuth['status'] == 'error') {
  header('Location: /login.php');
} else {
  $userAuth = $userAuth['data'];
  // if($userAuth['role'] != 'super admin'){
  //   echo "<script>alert('Anda bukan super admin !')</script>";
  //   echo "<script>window.location.href = '/dashboard.php';</script>";
  //   exit();
  // }
}
$csrf = $GLOBALS['csrf'];
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>Disporabudpar - Nganjuk</title>
  <meta content="" name="description">
  <meta content="" name="keywords">

  <!-- Favicons -->
  <link href="/public/assets/img/favicon.png" rel="icon">
  <link href="/public/assets/img/apple-touch-icon.png" rel="apple-touch-icon">

  <!-- Google Fonts -->
  <!-- <link href="https://fonts.gstatic.com" rel="preconnect"> -->
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Jost:300,300i,400,400i,500,500i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">
  <!-- Vendor CSS Files -->
  <link href="/public/assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="/public/assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="/public/assets/vendor/simple-datatables/style.css" rel="stylesheet">


  <!-- Template Main CSS File -->
  <link href="/public/assets/css/nomor-induk.css" rel="stylesheet">

</head>

<body>

  <!-- ======= Header ======= -->
  <header id="header" class="header fixed-top d-flex align-items-center">
    <?php include('../../header.php');
    ?>
  </header><!-- End Header -->

  <!-- ======= Sidebar ======= -->
  <aside id="sidebar" class="sidebar">
    <ul class="sidebar-nav" id="sidebar-nav">
      <?php
      $nav = 'seniman';
      include('../../sidebar.php');
      ?>
    </ul>
  </aside><!-- End Sidebar-->

  <main id="main" class="main">

    <div class="pagetitle">
      <h1>Formulir Pendaftaran</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="../index.php">Beranda</a></li>
          <li class="breadcrumb-item"><a href="menu-utama-nis.php">Nomor Induk Seniman</a></li>
          <li class="breadcrumb-item active">Formulir Pendaftaran</li>
        </ol>
      </nav>
    </div><!-- End Page Title -->
    <section class="section">
      <div class="row">
        <div class="col-lg-12">
          <div class="card">
            <div class="card-body">
              <h5 class="card-title"></h5>

              <!-- Multi Columns Form -->
              <form class="row g-3">
                <div class="col-md-12">
                  <label for="nik" class="form-label">Nomor Induk Kependudukan</label>
                  <input type="text" class="form-control" id="nik" placeholder="Masukkan Nomor Induk Kependudukan">
                </div>
                <div class="col-md-12">
                  <label for="nama_seniman" class="form-label">Nama Lengkap</label>
                  <input type="text" class="form-control" id="nama_seniman" placeholder="Masukkan Nama Lengkap sesuai KTP">
                </div>
                <div class="col-md-12">
                  <label for="nomor_induk" class="form-label">Nomor Induk Seniman Lama</label>
                  <input type="text" class="form-control" id="nomor_induk" placeholder="Masukkan Nomor Induk Seniman Lama">
                </div>
                <div class="col-12">
                  <label for="surat_keterangan" class="form-label">Surat Keterangan </label>
                  <input type="file" class="form-file-input form-control" id="surat_keterangan" >
                </div>
                <div class="col-12">
                  <label for="ktp_seniman" class="form-label">Foto Kartu Tanda Penduduk</label>
                  <input type="file" class="form-file-input form-control" id="ktp_seniman">
                </div>
                <div class="col-12">
                  <label for="pass_foto" class="form-label">Pas Foto 3x4</label>
                  <input type="file" class="form-file-input form-control" id="pass_foto">
                </div>
            </form>

    </section>

    </main><!-- End #main -->

  <!-- ======= Footer ======= -->
  <footer id="footer" class="footer">
    <?php include('../../footer.php');
    ?>
  </footer>

  <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

  <!-- Vendor JS Files -->
  <script src="/public/assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="/public/assets/vendor/simple-datatables/simple-datatables.js"></script>
  <script src="/public/assets/vendor/tinymce/tinymce.min.js"></script>

  <!-- Template Main JS File -->
  <script src="/public/assets/js/main.js"></script>

</body>

</html>