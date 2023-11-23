<?php
require_once(__DIR__.'/../web/koneksi.php');
require_once(__DIR__.'/../web/authenticate.php');
require_once(__DIR__.'/../env.php');
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
  if(!in_array($userAuth['role'],['super admin','admin seniman','admin pentas'])){
    echo "<script>alert('Anda bukan admin seniman !')</script>";
    echo "<script>window.location.href = '/dashboard.php';</script>";
    exit();
  }
  $tPath = ($_SERVER['APP_ENV'] == 'local') ? '' : $_SERVER['APP_FOLDER'];
  $csrf = $GLOBALS['csrf'];
  if (isset($_GET['id_pentas']) && !empty($_GET['id_pentas'])) {
    $id  = $_GET['id_pentas'];
    $sql = mysqli_query($conn, "SELECT id_advis, nomor_induk, nama_advis, alamat_advis, deskripsi_advis, DATE_FORMAT(tgl_awal, '%d %M %Y') AS tanggal_awal, DATE_FORMAT(tgl_selesai, '%d %M %Y') AS tanggal_selesai, tempat_advis, status, catatan FROM surat_advis WHERE id_advis = '$id' LIMIT 1");
    $pentas = mysqli_fetch_assoc($sql);
  }else{
    header('Location: /pentas.php');
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
    const domain = window.location.protocol + '//' + window.location.hostname +":"+window.location.port;
	  var csrfToken = "<?php echo $csrf ?>";
    var email = "<?php echo $userAuth['email'] ?>";
    var idUser = "<?php echo $userAuth['id_user'] ?>";
    var number = "<?php echo $userAuth['number'] ?>";
    var role = "<?php echo $userAuth['role'] ?>";
    var idPentas = "<?php echo $id ?>";
	</script>
  <!-- ======= Header ======= -->
  <header id="header" class="header fixed-top d-flex align-items-center">

    <?php
    include(__DIR__.'/../header.php');
    ?>

  </header><!-- End Header -->

  <!-- ======= Sidebar ======= -->
  <aside id="sidebar" class="sidebar">

    <ul class="sidebar-nav" id="sidebar-nav">
        <?php
        $nav = 'seniman';
        include(__DIR__.'/../sidebar.php');
        ?>
    </ul>

  </aside><!-- End Sidebar-->

  <main id="main" class="main">

  <div class="pagetitle">
      <h1>Detail Pentas</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="/dashboard.php">Beranda</a></li>
          <li class="breadcrumb-item"><a href="/pentas.php">Kelola Pentas</a></li>
          <?php if($pentas['status'] == 'diajukan' || $pentas['status'] == 'proses'){ ?>
            <li class="breadcrumb-item"><a href="/pentas/pengajuan.php">Pengajuan Pentas</a></li>
          <?php }else if($pentas['status'] == 'diterima' || $pentas['status'] == 'ditolak'){ ?>
            <li class="breadcrumb-item"><a href="/pentas/riwayat.php">Riwayat Pengajuan Pentas</a></li>
          <?php } ?>
          <li class="breadcrumb-item active">Detail pentas</li>
        </ol>
      </nav>
    </div><!-- End Page Title -->

    <section class="section">
      <div class="row">
        <div class="col-lg-12">

          <div class="card">
            <div class="card-body">
            <h5 class="card-title  mt-3 mb-4"><strong>
                  SURAT ADVIS
                  <br>
                  <u>PENYELENGGARAAN PERTUNJUKAN KESENIAN</u>
                </strong>
              </h5>

              <form method="POST" action="../users/proses-tambah-user.php" >
                <div class="col-md-12">
                    <label for="nama_seniman" class="form-label">Nama</label>
                    <input type="text" class="form-control" name="nama" value="<?php echo $pentas['nama_advis'] ?>" readonly>
                </div>
                <br>
                  <div class="col-md-12 ">
                    <label for="alamat_seniman" class="form-label">Alamat</label>
                    <textarea class="form-control" id="alamat_seniman" style="height: 100px;" readonly><?php echo $pentas['alamat_advis'] ?></textarea>
                  </div>
                  <br>
                  <div class="col-md-12">
                    <label for="no_telpon" class="form-label">Untuk Pentas</label>
                    <input type="text" class="form-control" name="phone"  value="<?php echo $pentas['deskripsi_advis'] ?>" readonly>
                  </div>
                  <br>
                  <div class="col-md-12">
                    <label for="tanggal" class="form-label">Tanggal </label>
                    <input type="date" class="form-control" name="tanggalL" value="<?php echo $pentas['tanggal_awal'] ?>" readonly>
                  </div>
                  <br>
                  <div class="col-md-12">
                    <label for="nama_organisasi" class="form-label">Bertempat Di</label>
                    <input type="text" class="form-control" name="tempatL" value="<?php echo $pentas['tempat_advis'] ?>" readonly>
                  </div>
                <div class="row mb-3 justify-content-end">
                  <div class="col-sm-10 text-end">
                    <?php if ($pentas['status'] == 'diajukan' || $pentas['status'] == 'proses') { ?>
                        <a href="/pentas/pengajuan.php" class="btn btn-info"><i>kembali</i></a>
                    <?php } else if ($pentas['status'] == 'diterima' || $pentas['status'] == 'ditolak') { ?>
                            <a href="/pentas/riwayat.php" class="btn btn-info"><i>kembali</i></a>
                    <?php } ?>
                    <?php if ($pentas['status'] == 'diajukan') { ?>
                        <button type="button" class="btn btn-success"
                            onclick="openProses(<?php echo $pentas['id_advis'] ?>)">
                            <i class="bi bi-edit-fill">Proses</i>
                        </button>
                    <?php } else if ($pentas['status'] == 'proses') { ?>
                        <button type="button" class="btn btn-success"
                            onclick="openSetuju(<?php echo $pentas['id_advis'] ?>)">
                            <i class="bi bi-check-circle">Setuju</i>
                        </button>
                        <button type="button" class="btn btn-danger"
                            onclick="openTolak(<?php echo $pentas['id_advis'] ?>)">
                            <i class="bi bi-x-circle">Tolak</i>
                        </button>
                    <?php } ?>
                  </div>
                </div>
              </form>
            </div>
          </div>

        </div>
      </div>
    </section>

  </main>
  <!-- End #main -->
  <!-- start modal proses -->
  <div class="modal fade" id="modalProses" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Konfirmasi proses pentas</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Apakah Anda yakin ingin memproses data pentas ?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <form action="/web/pentas/pentas.php" id="prosesForm" method="POST">
                        <input type="hidden" name="_method" value="PUT">
                        <input type="hidden" name="id_user" value="<?php echo $userAuth['id_user'] ?>">
                        <input type="hidden" name="id_pentas" id="inpPentasP">
                        <input type="hidden" name="keterangan" value="proses">
                        <button type="submit" class="btn btn-success">Proses</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- end modal proses -->

    <!-- start modal setuju -->
    <div class="modal fade" id="modalSetuju" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Konfirmasi setuju pentas</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Apakah Anda yakin ingin menyetujui pentas ?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <form action="/web/pentas/pentas.php" id="prosesForm" method="POST">
                        <input type="hidden" name="_method" value="PUT">
                        <input type="hidden" name="id_user" value="<?php echo $userAuth['id_user'] ?>">
                        <input type="hidden" name="id_pentas" id="inpPentasS">
                        <input type="hidden" name="keterangan" value="diterima">
                        <button type="submit" class="btn btn-success">Setuju</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- end modal setuju -->

    <!-- start modal tolak -->
    <div class="modal fade" id="modalTolak" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Konfirmasi tolak pentas</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Apakah Anda yakin ingin menolak pentas ?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <form action="/web/pentas/pentas.php" id="prosesForm" method="POST">
                        <input type="hidden" name="_method" value="PUT">
                        <input type="hidden" name="id_user" value="<?php echo $userAuth['id_user'] ?>">
                        <input type="hidden" name="id_pentas" id="inpPentasT">
                        <input type="hidden" name="catatan" value="terserah">
                        <input type="hidden" name="keterangan" value="ditolak">
                        <button type="submit" class="btn btn-success">Tolak</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- end modal tolak -->
  <!-- ======= Footer ======= -->
  <footer id="footer" class="footer">
    <div class="copyright">
      &copy; Copyright <strong><span>Huffle Puff</span></strong>. All Rights Reserved
    </div>
  </footer>

    <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i
        class="bi bi-arrow-up-short"></i></a>

    <!-- Vendor JS Files -->
    <script src="<?php echo $tPath; ?>/public/assets/vendor/apexcharts/apexcharts.min.js"></script>
    <script src="<?php echo $tPath; ?>/public/assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Template Main JS File -->
    <script src="<?php echo $tPath; ?>/public/assets/js/main.js"></script>
    <script>
      var modalProses = document.getElementById('modalProses');
      var modalSetuju = document.getElementById('modalSetuju');
      var modalTolak = document.getElementById('modalTolak');
      var inpPentasP = document.getElementById('inpPentasP');
      var inpPentasS = document.getElementById('inpPentasS');
      var inpPentasT = document.getElementById('inpPentasT');
      function openProses(dataU,) {
          inpPentasP.value = dataU;
          var myModal = new bootstrap.Modal(modalProses);
          myModal.show();
      }
      function openSetuju(dataU) {
          inpPentasS.value = dataU;
          var myModal = new bootstrap.Modal(modalSetuju);
          myModal.show();
      }
      function openTolak(dataU) {
          inpPentasT.value = dataU;
          var myModal = new bootstrap.Modal(modalTolak);
          myModal.show();
      }
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
      //preview data
      function preview(desc){
            if (desc != 'surat'){
                console.log('invalid description');
                return;
            }
            var xhr = new XMLHttpRequest();
            var requestBody = {
                email: email,
                id_pentas:idPentas,
                item:'pentas',
                deskripsi:desc
            };
            //open the request
            xhr.open('POST',domain+"/preview.php")
            xhr.setRequestHeader('X-CSRF-TOKEN', csrfToken);
            xhr.setRequestHeader('Content-Type', 'application/json');
            //send the form data
            xhr.send(JSON.stringify(requestBody));
            xhr.onreadystatechange = function() {
                if (xhr.readyState == XMLHttpRequest.DONE) {
                    if (xhr.status === 200 || xhr.status === 300 || xhr.status === 302) {
                        var response = JSON.parse(xhr.responseText);
                        window.location.href = response.data;
                    } else {
                        var response = xhr.responseText;
                        console.log('errorrr '+response);
                    }
                }
            }
        }
        //preview data
        function download(desc){
            if (desc != 'surat'){
                console.log('invalid description');
                return;
            }
            var xhr = new XMLHttpRequest();
            var requestBody = {
                email: email,
                id_pentas:idPentas,
                item:'pentas',
                deskripsi:desc
            };
            //open the request
            xhr.open('POST',domain+"/download.php")
            xhr.setRequestHeader('X-CSRF-TOKEN', csrfToken);
            xhr.setRequestHeader('Content-Type', 'application/json');
            xhr.responseType = 'blob';
            // send the form data
            xhr.send(JSON.stringify(requestBody));
            xhr.onreadystatechange = function () {
                if (xhr.readyState == XMLHttpRequest.DONE) {
                    if (xhr.status === 200) {
                        var blob = xhr.response;
                        var contentDisposition = xhr.getResponseHeader('Content-Disposition');
                        var match = contentDisposition.match(/filename="(.+\..+?)"/);
                        if (match) {
                            var filename = match[1];
                            var link = document.createElement('a');
                            link.href = window.URL.createObjectURL(blob);
                            link.download = filename;
                            link.click();
                        } else {
                            console.log('Invalid content-disposition header');
                        }
                    } else {
                        var response = xhr.responseText;
                        console.log('errorrr ' + response);
                    }
                }
            };
        }
    </script>
</body>

</html>