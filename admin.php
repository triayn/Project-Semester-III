<?php
require_once('web/koneksi.php');
require_once('web/authenticate.php');
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
  if ($userAuth['role'] != 'super admin') {
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
  <link href="/public/assets/img/LandingPage/favicon.png" rel="icon">
  <link href="/public/assets/img/LandingPage/apple-touch-icon.png" rel="apple-touch-icon">

  <!-- Google Fonts -->
  <!-- <link href="https://fonts.gstatic.com" rel="preconnect"> -->
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Jost:300,300i,400,400i,500,500i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">
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

    <?php
    include('header.php');
    ?>

  </header><!-- End Header -->

  <!-- ======= Sidebar ======= -->
  <aside id="sidebar" class="sidebar">
    <ul class="sidebar-nav" id="sidebar-nav">
      <?php
      $nav = 'admin';
      include('sidebar.php');
      ?>
    </ul>

  </aside><!-- End Sidebar-->

  <main id="main" class="main">

    <div class="pagetitle">
      <h1>Kelola Admin</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="/dashboard.php">Beranda</a></li>
          <li class="breadcrumb-item active">Kelola Admin</li>
        </ol>
      </nav>
    </div><!-- End Page Title -->

    <section class="section">
      <div class="row">
        <div class="col-lg-12">

          <div class="card">
            <div class="card-body">
              <h4 class="card-title"></h4>
              <a href="/halaman/admin/tambah.php">
                <button type="button" class="btn btn-primary">
                  <i class="bi bi-person-plus-fill"></i> Tambah Admin
                </button>
              </a>
              <!-- <button type="button" class="btn btn-primary" href="formulir-baru.php"><i class="bi bi-person-plus-fill"></i> Tambahkan data seniman</button> -->

              <table class="table datatable">
                <thead>
                  <tr>
                    <th>NO</th>
                    <th>Nama Pengguna</th>
                    <th>No Telpon</th>
                    <th>Role Admin</th>
                    <th>Email</th>
                    <th>Aksi</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                  $query = mysqli_query($conn, "SELECT id_user, nama_lengkap, no_telpon, jenis_kelamin, DATE_FORMAT(tanggal_lahir, '%d %M %Y') AS tanggal_lahir, tempat_lahir, role, email  FROM users WHERE role != 'masyarakat'");
                  $no = 1;
                  while ($users = mysqli_fetch_array($query)) {
                  ?>
                    <tr>
                      <td><?php echo $no ?></td>
                      <td><?php echo $users['nama_lengkap'] ?></td>
                      <td><?php echo $users['no_telpon'] ?></td>
                      <td><?php echo $users['role'] ?></td>
                      <td><?php echo $users['email'] ?></td>
                      <td>
                        <!-- <a href="/halaman/admin/edit.php?id_user=<?= $users['id_user'] ?>" class="btn btn-lihat"><i class="bi bi-eye-fill"></i> Lihat</a> -->
                        <button type="button" class="btn btn-lihat" data-bs-toggle="modal" data-bs-target="#editModal"><i class="bi bi-eye-fill"></i>Lihat</button>
                        <a href="/halaman/admin/edit.php?id_user=<?= $users['id_user'] ?>" class="btn btn-edit"><i class="bi bi-pencil-fill"></i> Edit</a>
                        <button type="button" class="btn btn-hapus"><i class="bi bi-trash-fill"></i> Hapus</button>
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

    <!-- Modal -->
    <div class="modal fade bd-example-modal-sm-12" id="editModal" tabindex="-1">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title"><strong>Detail Admin</strong></h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
              <form method="" action="" enctype="" style="padding: 4px; padding-left: 4;">
                  <input type="hidden" name="_method" value="PUT">
                  <input type="hidden" name="id_admin" value="<?php echo $userAuth['id_user']; ?>">
                  <input type="hidden" name="id_user" value="<?php echo $users['id_user']; ?>">
                  <input type="hidden" name="csrf_token" value="<?php echo $csrf?>">
                    <div class="row mb-4">
                      <label for="inputText" class="col-sm-2 col-form-label">Nama Lengkap</label>
                      <div class="col-sm-10">
                      <input type="text" class="form-control" name="nama" placeholder="Nama Lengkap" readonly>
                      </div>
                    </div>
                    <div class="row mb-4">
                      <label for="inputText" class="col-sm-2 col-form-label">No Handphone</label>
                      <div class="col-sm-10">
                      <input type="text" class="form-control" name="phone" placeholder="No Handphone" readonly>
                      </div>
                    </div>
                    <div class="row mb-4">
                      <label for="inputText" class="col-sm-2 col-form-label">Jenis Kelamin</label>
                      <div class="col-sm-10">
                      <input type="text" class="form-control" name="phone" placeholder="Jenis Kelamin" readonly>
                      </div>
                    </div>
                    <div class="row mb-4">
                      <label for="inputText" class="col-sm-2 col-form-label">Tempat / Tanggal Lahir</label>
                      <div class="col-sm-10">
                      <input type="text" class="form-control" name="phone" placeholder="Tempat / Tanggal Lahir" readonly>
                      </div>
                    </div>
                    <div class="row mb-4">
                      <label for="inputText" class="col-sm-2 col-form-label">Role</label>
                      <div class="col-sm-10">
                      <input type="text" class="form-control" name="phone" placeholder="Role" readonly>
                      </div>
                    </div>
                    <div class="row mb-4">
                      <label for="inputEmail" class="col-sm-2 col-form-label">Email</label>
                      <div class="col-sm-10">
                        <input type="email" class="form-control" name='email' placeholder="Email" readonly>
                      </div>
                    </div>
                    <div class="row mb-4">
                      <label for="inputPassword" class="col-sm-2 col-form-label">Password</label>
                      <div class="col-sm-10">
                        <input type="password" class="form-control" name='pass' placeholder="Password" readonly>
                      </div>
                    </div>
              </form>
          <div class="modal-footer">
            <button type="cancel" class="btn btn-tambah" data-bs-dismiss="modal">Kembali</button>
          </div>
        </div>
      </div>
    </div>

  </main><!-- End #main -->

  <!-- ======= Footer ======= -->
  <footer id="footer" class="footer">
    <?php include('footer.php');
    ?>
  </footer>

  <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

  <!-- Vendor JS Files -->
  <script src="/public/assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="/public/assets/vendor/simple-datatables/simple-datatables.js"></script>
  <script src="/public/assets/vendor/tinymce/tinymce.min.js"></script>

  <!-- Template Main JS File -->
  <script src="/public/assets/js/admin/main.js"></script>

</body>

</html>