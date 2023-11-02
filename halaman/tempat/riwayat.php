<?php
require_once('../../web/koneksi.php');
require_once('../../web/authenticate.php');
$database = koneksi::getInstance();
$conn = $database->getConnection();
$userAuth = authenticate($_POST,[
  'uri'=>$_SERVER['REQUEST_URI'],
  'method'=>$_SERVER['REQUEST_METHOD'
  ]
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
  <link href="/public/assets/css/tempat.css" rel="stylesheet">
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
        $nav = 'tempat';
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
        <li class="breadcrumb-item"><a href="/tempat.php">Kelola Tempat</a></li>
        <li class="breadcrumb-item active">Riwayat sewa tempat</li>
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
                    <th scope="col">No</th>
                    <th scope="col">Nama Peminjam</th>
                    <th scope="col">Nama Tempat</th>
                    <th scope="col">Tanggal Pengajuan</th>
                    <th scope="col">Status</th>
                    <th scope="col">Keterangan</th>
                    <th scope="col">Aksi</th>
                  </tr>
                </thead>
                <tbody>
                <?php
                    $query = mysqli_query($conn, "SELECT id_sewa, nama_peminjam, nama_tempat, tgl_awal_peminjaman, tgl_akhir_peminjaman, status, catatan FROM sewa_tempat WHERE status = 'diterima' OR status = 'ditolak' ORDER BY id_sewa DESC");
                    $no = 1;
                    while ($sewa = mysqli_fetch_array($query)) {
                    ?>
                  <tr>
                    <td><?php echo $no; ?></td>
                    <td><?php echo $sewa['nama_peminjam']; ?></td>
                    <td><?php echo $sewa['nama_tempat']; ?></td>
                    <td><?php echo $sewa['tgl_awal_peminjaman']; ?></td>
                    <td>
                      <?php if($sewa['status'] == 'diterima'){ ?>
                        <span class="badge bg-terima"><i class="bi bi-check-circle-fill"></i>  Disetujui</span>
                      <?php }else if($sewa['status'] == 'ditolak'){ ?>
                        <span class="badge bg-tolak"><i class="bi bi-x-circle-fill"></i>   Ditolak </span>
                      <?php } ?>
                    </td>
                    <td><?php echo $sewa['catatan']?></td>
                    <td>
                      <a href="/halaman/tempat/detail_sewa.php?id_sewa=<?= $sewa['id_sewa'] ?>" class="btn btn-lihat"><i class="bi bi-eye-fill"></i>  Lihat</a>
                    </td>
                  </tr>
                  <?php 
                  $no++;
                  } ?>
                  <!-- <tr>
                    <th scope="row">2</th>
                    <td>Puji Utami</td>
                    <td>Siraman Sedudo</td>
                    <td>1 Oktober 2023</td>
                    <td>
                      <button type="button" class="btn btn-danger">
                        <i class="bi bi-x-circle">Tolak</i>
                      </button>
                    </td>
                    <td></td>
                  </tr> -->
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