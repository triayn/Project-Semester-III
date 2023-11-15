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
    if ($userAuth['role'] != 'super admin') {
        echo "<script>alert('Anda bukan super admin !')</script>";
        echo "<script>window.location.href = '/dashboard.php';</script>";
        exit();
    }
}
$csrf = $GLOBALS['csrf'];
if (isset($_GET['id_sewa']) && !empty($_GET['id_sewa'])) {
    $id  = $_GET['id_sewa'];
    $sql = mysqli_query($conn, "SELECT id_sewa, nik_sewa, nama_peminjam, nama_tempat, deskripsi_sewa_tempat, nama_kegiatan_sewa, jumlah_peserta, instansi, DATE_FORMAT(tgl_awal_peminjaman, '%d %M %Y') AS tanggal_awal, DATE_FORMAT(tgl_akhir_peminjaman, '%d %M %Y') AS tanggal_akhir, status, catatan FROM sewa_tempat WHERE id_sewa = '$id'");
    $sewa = mysqli_fetch_assoc($sql);
} else {
    header('Location: /tempat.php');
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
    <link href="/public/assets/img/landing-page/favicon.png" rel="icon">
    <link href="/public/assets/img/landing-page/apple-touch-icon.png" rel="apple-touch-icon">

    <!-- Google Fonts -->
    <!-- <link href="https://fonts.gstatic.com" rel="preconnect"> -->
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Jost:300,300i,400,400i,500,500i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">
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
            <h1>Detail Data Tempat</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/dashboard.php">Beranda</a></li>
                    <li class="breadcrumb-item"><a href="/tempat.php">Kelola Tempat</a></li>
                    <?php if ($sewa['status'] == 'diajukan' || $sewa['status'] == 'proses') { ?>
                        <li class="breadcrumb-item"><a href="/halaman/tempat/pengajuan.php">Verifikasi Peminjaman</a></li>
                    <?php } else if ($sewa['status'] == 'diterima' || $sewa['status'] == 'ditolak') { ?>
                        <li class="breadcrumb-item"><a href="/halaman/tempat/riwayat.php">Riwayat Peminjaman</a></li>
                    <?php } ?>
                    <li class="breadcrumb-item active">Detail Data Tempat</li>
                </ol>
            </nav>
        </div><!-- End Page Title -->
        <section class="section dashboard">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="card-body d-flex justify-content-center align-items-center">
                                <h5 class="card-title mt-0 mb-0">Peminjaman Tempat</h5>
                            </div>
                            <form class="row g-3" method="POST" action="proses-tambah-tempat.php">
                                <div class="col-md-12">
                                    <label for="nama_peminjam" class="form-label">Nama Lengkap</label>
                                    <input type="text" class="form-control" id="nama_peminjam" readonly value="<?php echo $sewa['nama_peminjam'] ?>">
                                </div>
                                <div class="col-md-12">
                                    <label for="nik_sewa" class="form-label">No. KTP</label>
                                    <input type="text" class="form-control" id="nik_sewa" readonly value="<?php echo $sewa['nik_sewa'] ?>">
                                </div>
                                <div class="col-md-12">
                                    <label for="instansi" class="form-label">Instansi</label>
                                    <input type="text" class="form-control" id="instansi" readonly value="<?php echo $sewa['instansi'] ?>">
                                </div>
                                <div class="col-md-12">
                                    <label for="nama_kegiatan_sewa" class="form-label">Nama Kegiatan</label>
                                    <input type="text" class="form-control" id="nama_kegiatan_sewa" readonly value="<?php echo $sewa['nama_kegiatan_sewa']; ?>">
                                </div>
                                <div class="col-md-9">
                                    <label for="nama_organisasi" class="form-label">Nama Tempat</label>
                                    <input type="text" class="form-control" id="nama_organisasi"  readonly value="<?php echo $sewa['nama_tempat'] ?>">
                                </div>
                                <div class="col-md-3">
                                    <label for="jumlah_peserta" class="form-label">Jumlah Peserta</label>
                                    <input type="number" class="form-control" id="jumlah_peserta" readonly value="<?php echo $sewa['jumlah_peserta'] ?>">
                                </div>
                                <div class="col-md-6">
                                    <label for="inputDate" class="form-label">Tanggal Awal</label>
                                    <input type="text" class="form-control" id="tgl_awal_peminjaman"  readonly value="<?php echo $sewa['tanggal_awal'] ?>">
                                </div>
                                <div class="col-md-6">
                                    <label for="inputDate" class="form-label">Tanggal Akhir</label>
                                    <input type="text" class="form-control" id="tgl_akhir_peminjaman"  readonly value="<?php echo $sewa['tanggal_akhir'] ?>">
                                </div>
                                <div class="col-md-6">
                                    <label for="waktu_awal_peminjaman" class="form-label">Waktu Awal</label>
                                    <input type="time" class="form-control" id="waktu_awal_peminjaman">
                                </div>
                                <div class="col-md-6">
                                    <label for="waktu_akhir_peminjaman" class="form-label">Waktu Akhir</label>
                                    <input type="time" class="form-control" id="waktu_akhir_peminjaman">
                                </div>
                                <div class="col-md-12 ">
                                    <label for="deskripsi_sewa_tempat" class="form-label">Deskripsi Kegiatan</label>
                                    <textarea class="form-control" id="deskripsi_sewa_tempat" style="height: 100px;" readonly><?php echo $sewa['deskripsi_sewa_tempat'] ?></textarea>
                                </div>
                                <div class="col-12">
                                    <label for="surat_keterangan" class="form-label">Surat Keterangan</label>
                                    <input type="file" class="form-file-input form-control" id="surat_keterangan">
                                </div>
                                <div class="text-center">
                                            <a href="/halaman/tempat/status_peminjaman.php" class="btn btn-info"><i>kembali</i></a>
                                            <?php if ($sewa['status'] == 'diajukan') { ?>
                                                <a href="/halaman/tempat/edit_detail_tempat.php?id_tempat=<?= $id ?>" class="btn btn-tambah">Proses</a>
                                            <?php } else if ($sewa['status'] == 'proses') { ?>
                                                <button type="button" class="btn btn-tambah">Setuju
                                                </button>
                                                <button type="button" class="btn btn-tolak">Tolak
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