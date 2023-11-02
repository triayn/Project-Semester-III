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
  <link href="/public/assets/img/favicon.png" rel="icon">
  <link href="/public/assets/img/apple-touch-icon.png" rel="apple-touch-icon">

  <!-- Google Fonts -->
  <!-- <link href="https://fonts.gstatic.com" rel="preconnect"> -->
  <link
    href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Jost:300,300i,400,400i,500,500i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i"
    rel="stylesheet">
  <!-- Vendor CSS Files -->
  <!-- <link href="/public/assets/vendor/bootstrap/css/bootstrap.css" rel="stylesheet"> -->

  <link href="/public/assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="/public/assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="/public/assets/vendor/simple-datatables/style.css" rel="stylesheet">
  <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

  <!-- Template Main CSS File -->
  <link href="/public/assets/css/nomor-induk.css" rel="stylesheet">
  <style>
    .ui-datepicker-calendar {
      display: none;
    }
  </style>

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
      <h1>Riwayat Pengajuan</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="/dashboard.php">Beranda</a></li>
          <li class="breadcrumb-item"><a href="/seniman.php">Kelola Seniman</a></li>
          <li class="breadcrumb-item active">Riwayat Pengajuan</li>
        </ol>
      </nav>
    </div><!-- End Page Title -->

    <section class="section">
      <div class="row">
        <div class="col-lg-12">

          <div class="card">
            <div class="card-body">
              <h5 class="card-title"></h5>
<!-- Year Picker -->
<div class="form-group">
      <label for="yearpicker">Select Year:</label>
      <input type="text" id="yearpicker" class="form-control" />
    </div>
    
    <!-- Month Picker -->
    <div class="form-group">
      <label for="monthpicker">Select Month:</label>
      <input type="text" id="monthpicker" class="form-control" />
    </div>
  </div>
              <table class="table datatable">
                <thead>
                  <tr>
                    <th scope="col">No</th>
                    <th scope="col">Nama Seniman</th>
                    <th scope="col">Tanggal Pengajuan</th>
                    <th scope="col">Status</th>
                    <th scope="col">Keterangan</th>
                    <th scope="col">Aksi</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                      $query = mysqli_query($conn, "SELECT id_seniman, nama_seniman, DATE_FORMAT(tgl_pembuatan, '%d %M %Y') AS tanggal, status, catatan FROM seniman WHERE status = 'diterima' OR status = 'ditolak' ORDER BY id_seniman DESC");
                      $no = 1;
                      while ($seniman = mysqli_fetch_array($query)) {
                  ?>
                    <tr>
                      <td><?php echo $no?></td>
                      <td><?php echo $seniman['nama_seniman']?></td>
                      <td><?php echo $seniman['tanggal']?></td>
                      <td>
                        <?php if($seniman['status'] == 'diterima'){ ?>
                          <span class="badge bg-terima"><i class="bi bi-check-circle-fill"></i>  Disetujui</span>
                        <?php }else if($seniman['status'] == 'ditolak'){ ?>
                          <span class="badge bg-tolak"><i class="bi bi-x-circle-fill"></i>   Ditolak </span>
                        <?php } ?>
                      </td>
                      <td><?php echo $seniman['catatan']?></td>
                      <td>
                        <a href="/halaman/seniman/detail_seniman.php?id_seniman=<?= $seniman['id_seniman'] ?>" class="btn btn-lihat"><i class="bi bi-eye-fill"></i>  Lihat</a>
                      </td>
                    </tr>
                  <?php $no++;
                  } ?>
                </tbody>
              </table>

            </div>
          </div>

        </div>
      </div>
    </section>

  </main><!-- End #main -->

  <!-- ======= Footer ======= -->
  <footer id="footer" class="footer">
    <?php include('../../footer.php');
    ?>
  </footer>
  <!-- </footer> -->

  <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i
      class="bi bi-arrow-up-short"></i></a>

  <!-- Vendor JS Files -->
  <script src="/public/assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="/public/assets/vendor/simple-datatables/simple-datatables.js"></script>
  <script src="/public/assets/vendor/tinymce/tinymce.min.js"></script>
  <script>
    $(function () {
      // Year Picker
      $("#yearpicker").datepicker({
        changeMonth: false,
        changeYear: true,
        showButtonPanel: true,
        dateFormat: 'yy',
        onClose: function (dateText, inst) {
          var year = $("#ui-datepicker-div .ui-datepicker-year :selected").val();
          $(this).datepicker('setDate', new Date(year, 0, 1));
        }
      });

      // Month Picker
      $("#monthpicker").datepicker({
        changeMonth: true,
        changeYear: true,
        showButtonPanel: true,
        dateFormat: 'MM yy',
        onClose: function (dateText, inst) {
          var year = $("#ui-datepicker-div .ui-datepicker-year :selected").val();
          var month = $("#ui-datepicker-div .ui-datepicker-month :selected").val();
          $(this).datepicker('setDate', new Date(year, month, 1));
        }
      });
    });
  </script>

  <!-- Template Main JS File -->
  <script src="/public/assets/js/main.js"></script>

</body>

</html>