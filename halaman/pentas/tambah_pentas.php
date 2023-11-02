<?php
require_once('../../web/koneksi.php');
require_once('../../web/authenticate.php');
$database = koneksi::getInstance();
$conn = $database->getConnection();
$userAuth = authenticate($_POST,[
  'uri'=>$_SERVER['REQUEST_URI'],
  'method'=>$_SERVER['REQUEST_METHOD']
],$conn);
if($userAuth['status'] == 'error'){
	header('Location: /login.php');
}else{
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
  <link href="/public/assets/img/LandingPage/favicon.png" rel="icon">
    <link href="/public/assets/img/LandingPage/apple-touch-icon.png" rel="apple-touch-icon">

  <!-- Google Fonts -->
  <!-- <link href="https://fonts.gstatic.com" rel="preconnect"> -->
  <link
    href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Jost:300,300i,400,400i,500,500i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i"
    rel="stylesheet">
  <!-- Vendor CSS Files -->
  <link href="/public/assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="/public/assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">

  <!-- Template Main CSS File -->
  <link href="/public/assets/css/pentas.css" rel="stylesheet">

</head>

<body>

  <!-- ======= Header ======= -->
  <header id="header" class="header fixed-top d-flex align-items-center">

    <?php
    include('../../header.php');
    ?>

  </header><!-- End Header -->

  <!-- ======= Sidebar ======= -->
  <aside id="sidebar" class="sidebar">

    <ul class="sidebar-nav" id="sidebar-nav">
        <?php
        $nav = 'pentas';
        include('../../sidebar.php');
        ?>
    </ul>

  </aside><!-- End Sidebar-->

  <main id="main" class="main">

  <div class="pagetitle">
      <h1>Pengajuan Surat Advis</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="index.html"></a>Kelola Surat Advis</li>
          <li class="breadcrumb-item">Pengajuan</li>
          <!-- <li class="breadcrumb-item active">Data</li> -->
        </ol>
      </nav>
    </div>
  
  <section class="section">
      <div class="row">
        <div class="col-lg-12">

          <div class="card">
            <div class="card-body">
              <h4 class="card-title">Pengajuan Surat Advis</h4>
              <table class="table datatable">
                <thead>
                  <tr>
                    <th>No</th>
                    <th>Nomor Induk</th>
                    <th>Nama Pemohon</th>
                    <th>Tanggal</th>
                    <th>Aksi</th>
                  </tr>
                </thead>
                    <tr>
                        <td>1. </td>
                        <td>KRW / 007 /411.302 / 2023</td>
                        <td>Puji utami</td>
                        <td>12 Oktober 2023</td>
                    </tr>
                    <tr>
                        <td>2. </td>
                        <td>KRW / 007 /411.302 / 2023</td>
                        <td>Puji utami</td>
                        <td>12 Oktober 2023</td>
                    </tr>
                <tbody>
                </tbody>
              </table>
              <!-- End Table with stripped rows -->

            </div>
          </div>

        </div>
      </div>
  </section>    

  </main>
  <!-- End #main -->

  <!-- ======= Footer ======= -->
  <footer id="footer" class="footer">
        <?php include('../../footer.php');
        ?>
    </footer>

    <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i
        class="bi bi-arrow-up-short"></i></a>

    <!-- Vendor JS Files -->
    <script src="/public/assets/vendor/apexcharts/apexcharts.min.js"></script>
    <script src="/public/assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Template Main JS File -->
    <script src="/public/assets/js/admin/main.js"></script>
    <script>
      document.addEventListener('DOMContentLoaded', function () {
        var currentPageURL = window.location.href;
        var menuLinks = document.querySelectorAll('.nav-link');
        menuLinks.forEach(function (menuLink) {
          var menuLinkURL = menuLink.getAttribute('href');
          if (currentPageURL === menuLinkURL) {
            menuLink.parentElement.classList.add('active');
          }
        });
      });

    </script>
</body>

</html>