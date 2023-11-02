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
  if($userAuth['role'] != 'super admin'){
    echo "<script>alert('Anda bukan super admin !')</script>";
    echo "<script>window.location.href = '/dashboard.php';</script>";
    exit();
  }
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
  <link href="/public/assets/img/landing-page/favicon.png" rel="icon">
    <link href="/public/assets/img/landing-page/apple-touch-icon.png" rel="apple-touch-icon">

  <!-- Google Fonts -->
  <!-- <link href="https://fonts.gstatic.com" rel="preconnect"> -->
  <link
    href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Jost:300,300i,400,400i,500,500i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i"
    rel="stylesheet">
  <!-- Vendor CSS Files -->
  <link href="/public/assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="/public/assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="/public/assets/vendor/simple-datatables/style.css" rel="stylesheet">


  <!-- Template Main CSS File -->
  <link href="/public/assets/css/event.css" rel="stylesheet">

</head>

<body>
  <script>
		var csrfToken = "<?php echo $csrf ?>";
    var email = "<?php echo $userAuth['email'] ?>";
    var idUser = "<?php echo $userAuth['id_user'] ?>";
    var number = "<?php echo $userAuth['number'] ?>";
    var role = "<?php echo $userAuth['role'] ?>";
	</script>
  <!-- ======= Header ======= -->
  <header id="header" class="header fixed-top d-flex align-items-center">
    <?php include('../../header.php');
    ?>
  </header><!-- End Header -->

  <!-- ======= Sidebar ======= -->
  <aside id="sidebar" class="sidebar">
    <ul class="sidebar-nav" id="sidebar-nav">
      <?php 
        $nav = 'event';
        include('../../sidebar.php');
      ?>
    </ul>
  </aside><!-- End Sidebar-->

  <main id="main" class="main">

    <div class="pagetitle">
      <h1>Verifikasi Pengajuan</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="/dashboard.php">Beranda</a></li>
          <li class="breadcrumb-item"><a href="/event.php">Kelola Event</a></li>
          <li class="breadcrumb-item active">Verifikasi Pengajuan</li>
        </ol>
      </nav>
    </div><!-- End Page Title -->

    <section class="section">
      <div class="row">
        <div class="col-lg-12">

          <div class="card">
            <div class="card-body">
              <h5 class="card-title"></h5>

              <table class="table datatable">
              <thead>
              <tr>
                    <th>No</th>
                    <th>Nama Pengirim</th>
                    <th>Nama Event</th>
                    <th>Tanggal Pengajuan</th>
                    <th>Status</th>
                    <th>Aksi</th>
                  </tr>
                  </thead>
                  <tbody>
                  <?php
                    $query = mysqli_query($conn, "SELECT id_event, nama_pengirim, nama_event, tanggal_awal, tanggal_akhir, status FROM events INNER JOIN detail_events ON events.id_detail = detail_events.id_detail WHERE status = 'diajukan' OR status = 'proses' ORDER BY id_event DESC");
                      $no = 1;
                      while ($event = mysqli_fetch_array($query)) {
                    ?>
                      <tr>
                          <td><?php echo $no; ?></td>
                          <td><?php echo $event['nama_pengirim']?></td>
                          <td><?php echo $event['nama_event']?></td>
                          <td><?php echo $event['tanggal_awal']?></td>
                          <td>
                            <?php if($event['status'] == 'diajukan'){ ?>
                              <span class="badge bg-proses">Diajukan</span>
                            <?php }else if($event['status'] == 'proses'){ ?>
                              <span class="badge bg-terima">Diproses</span>

                            <?php } ?>
                          </td>
                          <td>
                            <a href="/halaman/event/detail_event.php?id_event=<?= $event['id_event'] ?>"  class="btn btn-lihat"><i class="bi bi-eye-fill"></i>  Lihat</a>
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

  <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i
      class="bi bi-arrow-up-short"></i></a>

  <!-- Vendor JS Files -->
  <script src="/public/assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="/public/assets/vendor/simple-datatables/simple-datatables.js"></script>
  <script src="/public/assets/vendor/tinymce/tinymce.min.js"></script>

  <!-- Template Main JS File --> 
  <script src="/public/assets/js/main.js"></script>

</body>

</html>