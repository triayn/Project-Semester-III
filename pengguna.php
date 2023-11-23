<?php
require_once(__DIR__.'/web/koneksi.php');
require_once(__DIR__.'/web/authenticate.php');
require_once(__DIR__.'/env.php');
loadEnv();
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
  if(!in_array($userAuth['role'],['super admin'])){
    echo "<script>alert('Anda bukan super admin !')</script>";
    echo "<script>window.location.href = '/dashboard.php';</script>";
    exit();
  }
  $tPath = ($_SERVER['APP_ENV'] == 'local') ? '' : $_SERVER['APP_FOLDER'];
  $csrf = $GLOBALS['csrf'];
  $query = mysqli_query($conn, "SELECT id_user, nama_lengkap, no_telpon, jenis_kelamin, DATE_FORMAT(tanggal_lahir, '%d %M %Y') AS tanggal_lahir, tempat_lahir, role, email  FROM users WHERE role = 'masyarakat'");
  if ($query) {
    $users = mysqli_fetch_all($query, MYSQLI_ASSOC);
  } else {
    $users = array();
  }
}
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
  <link href="<?php echo $tPath; ?>/public/img/icon/utama/logo.png" rel="icon">

  <!-- Google Fonts -->
  <!-- <link href="https://fonts.gstatic.com" rel="preconnect"> -->
  <link
    href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Jost:300,300i,400,400i,500,500i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i"
    rel="stylesheet">
  <!-- Vendor CSS Files -->
  <link href="<?php echo $tPath; ?>/public/assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="<?php echo $tPath; ?>/public/assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">

  <!-- Template Main CSS File -->
  <link href="<?php echo $tPath; ?>/public/assets/css/style.css" rel="stylesheet">

</head>

<body>
  <script>
		var csrfToken = "<?php echo $csrf ?>";
    var email = "<?php echo $userAuth['email'] ?>";
    var idUser = "<?php echo $userAuth['id_user'] ?>";
    var number = "<?php echo $userAuth['number'] ?>";
    var role = "<?php echo $userAuth['role'] ?>";
    var dataUsers = <?php echo json_encode($users); ?>;
	</script>
  <!-- ======= Header ======= -->
  <header id="header" class="header fixed-top d-flex align-items-center">

    <?php
    include(__DIR__.'/header.php');
    ?>

  </header><!-- End Header -->

  <!-- ======= Sidebar ======= -->
  <aside id="sidebar" class="sidebar">

    <ul class="sidebar-nav" id="sidebar-nav">
        <?php
        $nav = 'pengguna';
        include(__DIR__.'/sidebar.php');
        ?>
    </ul>

  </aside><!-- End Sidebar-->

  <main id="main" class="main">

  <div class="pagetitle">
      <h1>Kelola Pengguna</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="/dashboard.php">Beranda</a></li>
          <li class="breadcrumb-item active">Kelola Pengguna</li>
        </ol>
      </nav>
  </div><!-- End Page Title -->
  
  <section class="section">
      <div class="row">
        <div class="col-lg-12">

          <div class="card">
            <div class="card-body">
              <h4 class="card-title">Data Pengguna</h4>
              <!-- Table with stripped rows -->
              <table class="table">
                <thead>
                  <tr>
                    <th>NO</th>
                    <th>Nama Pengguna</th>
                    <th>No Telpon</th>
                    <th>Jenis Kelamin</th>
                    <th>Tanggal Lahir</th>
                    <th>Tempat Lahir</th>
                    <!-- <th>Role</th> -->
                    <th>Email</th>
                    <th>keterangan</th>
                  </tr>
                </thead>
                <tbody>
                <?php
                    // $query = mysqli_query($conn, "SELECT id_user, nama_lengkap, no_telpon, jenis_kelamin, DATE_FORMAT(tanggal_lahir, '%d %M %Y') AS tanggal_lahir, tempat_lahir, role, email  FROM users WHERE role = 'masyarakat'");
                    $no = 1;
                    foreach($users as $user) {
                    ?>
                      <tr>
                        <td><?php echo $no?></td>
                        <td><?php echo $user['nama_lengkap'] ?></td>
                        <td><?php echo $user['no_telpon'] ?></td>
                        <td><?php echo $user['jenis_kelamin'] ?></td>
                        <td><?php echo $user['tanggal_lahir'] ?></td>
                        <td><?php echo $user['tempat_lahir'] ?></td>
                        <!-- <td><?php //echo $user['role'] ?></td> -->
                        <td><?php echo $user['email'] ?></td>
                        <td>
                          <button type="button" class="btn btn-lihat" onclick="openDetail(<?php echo $user['id_user']?>)"> <i class="bi bi-eye-fill">Lihat</i></button>
                          <a href="/user/form-edit-user.php?id_user=<?= $user['id_user'] ?>" class="btn btn-info"><i class="bi bi-pencil-square"></i>edit</a>
                          <button type="button" class="btn btn-danger" onclick="openDelete(<?php echo $user['id_user']?>)"> <i class="bi bi-trash-fill">Hapus</i></button>
                        </td>
                      </tr>
                    <?php $no++;
                  } ?>
                </tbody>
              </table>
              <!-- End Table with stripped rows -->

            </div>
          </div>

        </div>
      </div>
  </section>    
  <!-- start modal detail -->
  <div class="modal fade" id="modalDetail" tabindex="-1">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title"><strong>Detail Pengguna</strong></h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
              <form method="" action="" enctype="" style="padding: 4px; padding-left: 4;">
                  <!-- <input type="hidden" name="csrf_token" value="<?php // echo $csrf?>"> -->
                    <div class="row mb-4">
                      <label for="inputText" class="col-sm-2 col-form-label">Nama Lengkap</label>
                      <div class="col-sm-10">
                      <input type="text" class="form-control" id="inpNamaDetail" name="nama" placeholder="Nama Lengkap" readonly>
                      </div>
                    </div>
                    <div class="row mb-4">
                      <label for="inputText" class="col-sm-2 col-form-label">No Handphone</label>
                      <div class="col-sm-10">
                      <input type="text" class="form-control"id="inpPhoneDetail" name="phone" placeholder="No Handphone" readonly>
                      </div>
                    </div>
                    <div class="row mb-4">
                      <label for="inputText" class="col-sm-2 col-form-label">Jenis Kelamin</label>
                      <div class="col-sm-10">
                      <input type="text" class="form-control" id="inpKelaminDetail" name="phone" placeholder="Jenis Kelamin" readonly>
                      </div>
                    </div>
                    <div class="row mb-4">
                      <label for="inputText" class="col-sm-2 col-form-label">Tempat / Tanggal Lahir</label>
                      <div class="col-sm-10">
                      <input type="text" class="form-control" id="inpTTLDetail" name="phone" placeholder="Tempat / Tanggal Lahir" readonly>
                      </div>
                    </div>
                    <div class="row mb-4">
                      <label for="inputEmail" class="col-sm-2 col-form-label">Email</label>
                      <div class="col-sm-10">
                        <input type="email" class="form-control" id="inpEmailDetail" name='email' placeholder="Email" readonly>
                      </div>
                    </div>
              </form>
          <div class="modal-footer">
            <button type="cancel" class="btn btn-tambah" data-bs-dismiss="modal">Kembali</button>
          </div>
        </div>
      </div>
    </div>
    <!-- end modal detail -->
  <!-- start modal delete -->
  <div class="modal fade" id="modalDelete" tabindex="-1">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Konfirmasi hapus pengguna</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          Apakah Anda yakin ingin menghapus pengguna ?
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
          <form action="/web/User.php" id="deleteForm" method="POST">
            <input type="hidden" name="_method" value="DELETE">
            <input type="hidden" name="id_admin" value="<?php echo $userAuth['id_user'] ?>">
            <input type="hidden" name="id_user" id="inpUserDelete">
            <button type="submit" class="btn btn-success" name="hapusUser">Hapus</button>
          </form>
        </div>
      </div>
    </div>
  </div>
  <!-- end modal delete -->
  </main>
  <!-- End #main -->

  <!-- ======= Footer ======= -->
  <footer id="footer" class="footer">
    <div class="copyright">
      &copy; Copyright <strong><span>Huffle Puff</span></strong>. All Rights Reserved
    </div>
  </footer>

    <a href="#" class="back-to-top d-flex align-items-center justify-content-center">
      <i class="bi bi-arrow-up-short"></i>
    </a>
    <script>
        var modalDetail = document.getElementById('modalDetail');
        var inpNamaDetail = document.getElementById('inpNamaDetail');
        var inpPhoneDetail = document.getElementById('inpPhoneDetail');
        var inpKelaminDetail = document.getElementById('inpKelaminDetail');
        var inpTTLDetail = document.getElementById('inpTTLDetail');
        var inpEmailDetail = document.getElementById('inpEmailDetail');
        var modalDelete = document.getElementById('modalDelete');
        var inpUserDelete = document.getElementById('inpUserDelete');
        function openDetail(dataU){
          dataUsers.forEach((dataUser)=>{
            if(dataUser.id_user == dataU){
              inpNamaDetail.value = dataUser['nama_lengkap'];
              inpPhoneDetail.value = dataUser['no_telpon'];
              inpKelaminDetail.value = dataUser['jenis_kelamin'];
              inpTTLDetail.value = dataUser['tempat_lahir']+'/'+dataUser['tanggal_lahir'];
              inpEmailDetail.value = dataUser['email'];
            }
          });
          var myModal = new bootstrap.Modal(modalDetail);
          myModal.show();
        }
        function openDelete(dataU){
          inpUserDelete.value = dataU;
          var myModal = new bootstrap.Modal(modalDelete);
          myModal.show();
        }
    </script>
        <!-- Vendor JS Files -->
    <script src="..<?php echo $tPath; ?>/public/assets/vendor/apexcharts/apexcharts.min.js"></script>
    <script src="..<?php echo $tPath; ?>/public/assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="<?php echo $tPath; ?>/public/assets/vendor/tinymce/tinymce.min.js"></script>
    <!-- Template Main JS File -->
        <script src="<?php echo $tPath; ?>/public/assets/js/admin/main.js"></script>
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