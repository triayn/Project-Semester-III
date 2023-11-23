<?php 
require_once(__DIR__.'/web/koneksi.php');
require_once(__DIR__.'/web/authenticate.php'); 
require_once(__DIR__.'/env.php');
loadEnv();
$db = koneksi::getInstance();
$con = $db->getConnection();
$userAuth = authenticate($_POST,[
      'uri'=>$_SERVER['REQUEST_URI'],
      'method'=>$_SERVER['REQUEST_METHOD'
    ]
],$con);
if($userAuth['status'] == 'success'){
  $userAuth = $userAuth['data'];
  if(!in_array($userAuth['role'],['super admin','admin seniman','admin tempat','admin sewa','admin pentas'])){
      header('Location: /dashboard.php');
  }
}
$tPath = ($_SERVER['APP_ENV'] == 'local') ? '' : $_SERVER['APP_FOLDER'];
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
  <link href="<?php echo $tPath; ?>/public/assets/img/LandingPage/favicon.png" rel="icon">
  <link href="<?php echo $tPath; ?>/public/assets/img/LandingPage/apple-touch-icon.png" rel="apple-touch-icon">

  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Jost:300,300i,400,400i,500,500i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="<?php echo $tPath; ?>/public/assets/vendor/aos/aos.css" rel="stylesheet">
  <link href="<?php echo $tPath; ?>/public/assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="<?php echo $tPath; ?>/public/assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="<?php echo $tPath; ?>/public/assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
  <link href="<?php echo $tPath; ?>/public/assets/vendor/glightbox/css/glightbox.min.css" rel="stylesheet">
  <link href="<?php echo $tPath; ?>/public/assets/vendor/remixicon/remixicon.css" rel="stylesheet">
  <link href="<?php echo $tPath; ?>/public/assets/vendor/swiper/swiper-bundle.min.css" rel="stylesheet">

  <!-- Template Main CSS File -->
  <link href="<?php echo $tPath; ?>/public/assets/css/LandingPage.css" rel="stylesheet">

  <!-- =======================================================
  * Template Name: Arsha - v4.7.1
  * Template URL: https://bootstrapmade.com/arsha-free-bootstrap-html-template-corporate/
  * Author: BootstrapMade.com
  * License: https://bootstrapmade.com/license/
  ======================================================== -->
</head>

<body>

  <!-- ======= Header ======= -->
  <header id="header" class="fixed-top ">
    <div class="container d-flex align-items-center">
      <h1 class="logo me-auto"><a href="/home.php">DISPORABUDPAR</a></h1>
      <nav id="navbar" class="navbar">
        <ul>
          <li><a class="nav-link scrollto active" href="/home.php#hero">Beranda</a></li>
          <li><a class="nav-link scrollto" href="/home.php#event">Event</a></li>
          <li><a class="nav-link scrollto" href="/home.php#about">Informasi</a></li>
          <li><a class="nav-link scrollto" href="/home.php#layanan">Layanan</a></li>
          <li><a class="nav-link   scrollto" href="/home.php#profil">Profil</a></li>
          <li><a class="getstarted scrollto" href="/login.php">Masuk</a></li>
        </ul>
        <i class="bi bi-list mobile-nav-toggle"></i>
      </nav>
    </div>
  </header>

  <main id="main">

    <section>
      <div class="container">
        <div class="section-title">
          <br>
          <h1>LAYANAN NOMOR INDUK SENIMAN</h1>
          <p>
            Dengan adanya layanan ini anda dapat melakukan pendaftaran nomor induk seniman atau organisasi dan memperpanjang kartu nomor induk anda.
          </p>
        </div>
      </div>
    </section>

    <section id="services" class="services section-bg">
      <div class="container" data-aos="fade-up">

        <div class="section-title">
          <h2>PERSYARATAN</h2>
          <br>
        </div>
        <div class="row">
          <div class="col-xl-3 col-md-6 d-flex align-items-stretch" data-aos="zoom-in" data-aos-delay="100">
            <div class="icon-box">
              <div class="icon"><i class="bx bx-file"></i></div>
              <h4><a href="">Kartu Induk Seniman (Baru)</a></h4>
              <div class="portfolio-info">
                <ul>
                  <li>Surat keterangan dari desa setempat</li>
                  <li>Fotocopy KTP</li>
                  <li>Pas foto ukuran 3x4 berwarna terbaru (jumlah 2 lembar)</li>
                  <li>Tidak boleh diwakilkan</li>
                </ul>
              </div>
            </div>
          </div>

          <div class="col-xl-3 col-md-6 d-flex align-items-stretch mt-4 mt-md-0" data-aos="zoom-in" data-aos-delay="200">
            <div class="icon-box">
              <div class="icon"><i class="bx bx-file"></i></div>
              <h4><a href="">Kartu Induk Organisasi Seni (Baru)</a></h4>
              <ul>
                <li>Surat keterangan dari desa setempat</li>
                <li>Fotocopy KTP ketua dan KTP anggota</li>
                <li>Pas foto pimpinan ukuran 3x4 berwarna terbaru (jumlah 2 lembar)</li>
                <li>Tidak boleh diwakilkan</li>
              </ul>
            </div>
          </div>

          <div class="col-xl-3 col-md-6 d-flex align-items-stretch mt-4 mt-xl-0" data-aos="zoom-in" data-aos-delay="300">
            <div class="icon-box">
              <div class="icon"><i class="bx bx-file"></i></div>
              <h4><a href="">Kartu Induk Seniman (Perpanjang)</a></h4>
              <ul>
                <li>Surat keterangan dari desa setempat</li>
                <li>Fotocopy KTP</li>
                <li>Pas foto ukuran 3x4 berwarna terbaru (jumlah 2 lembar)</li>
                <li>Tidak boleh diwakilkan</li>
              </ul>
            </div>
          </div>

          <div class="col-xl-3 col-md-6 d-flex align-items-stretch mt-4 mt-xl-0" data-aos="zoom-in" data-aos-delay="400">
            <div class="icon-box">
              <div class="icon"><i class="bx bx-file"></i></div>
              <h4><a href="">Kartu Induk Organisasi Seni (Perpanjang)</a></h4>
              <ul>
                <li>Surat keterangan dari desa setempat</li>
                <li>Fotocopy KTP ketua dan KTP anggota</li>
                <li>Pas foto pimpinan ukuran 3x4 berwarna terbaru (jumlah 2 lembar)</li>
                <li>Tidak boleh diwakilkan</li>
              </ul>
            </div>
          </div>

        </div>

      </div>
    </section>

    <section id="" class="skills">
      <div class="container" data-aos="fade-up">
  
        <div class="row">
          <div class="col-lg-6 d-flex align-items-center" data-aos="fade-right" data-aos-delay="100">
            <img src="<?php echo $tPath; ?>/public/assets/img/LandingPage/prosedur.png" class="img-fluid" alt="">
          </div>
          <div class="col-lg-6 pt-4 pt-lg-0 content" data-aos="fade-left" data-aos-delay="100">
            <br>
            <br>
            <h3><strong>PROSEDUR</strong></h3>
            <br>
            <p class="fst-italic">
              <ol>
                <li> Mengajukan permohonan melalui aplikasi android.</li>
                <li> Verivfikasi dan validasi dokumen oleh petugas</li>
                <li> Pengentrian data</li>
                <li> Penerbitan Kartu Induk Seniman/Organisasi</li>
                <li> Verifikasi dan validasi hasil entri oleh Kasi/Kabid </li>
                <li> Penandatanganan oleh Kepala Dinas</li>
                <li> Penyerahan Kartu Induk Seniman/Organisasi </li>
              </ol>
            </p>
          </div>
        </div>
      </div>
    </section>

    <section id="why-us" class="why-us section-bg">
      <div class="container-fluid" data-aos="fade-up">

        <div class="row">

          <div class="col-lg-7 d-flex flex-column justify-content-center align-items-stretch  order-2 order-lg-1">

            <div class="content">
              <h3><strong>KETENTUAN UMUM</strong></h3>
            </div>

            <div class="accordion-list">
              <ul>
                <li>
                  <a data-bs-toggle="collapse" class="collapse" data-bs-target="#accordion-list-1"><span>01</span><i class="bx bx-chevron-down icon-show"></i><i class="bx bx-chevron-up icon-close"></i></a>
                  <div id="accordion-list-1" class="collapse show" data-bs-parent=".accordion-list">
                    <p>
                      Kartu ini berlaku sampai dengan tanggal 31 Desember tahun berjalan dan pada tahun selanjutnya WAJIB diperbaharui.
                    </p>
                  </div>
                </li>

                <li>
                  <a data-bs-toggle="collapse" data-bs-target="#accordion-list-2" class="collapsed"><span>02</span><i class="bx bx-chevron-down icon-show"></i><i class="bx bx-chevron-up icon-close"></i></a>
                  <div id="accordion-list-2" class="collapse show" data-bs-parent=".accordion-list">
                    <p>
                      Bagi Seniman/Organisasi Kesenian yang pentas harus memiliki KARTU INDUK SENIMAN / ORGANISASI KESENIAN yang berlaku.  
                    </p>
                  </div>
                </li>

                <li>
                  <a data-bs-toggle="collapse" data-bs-target="#accordion-list-3" class="collapsed"><span>03</span> <i class="bx bx-chevron-down icon-show"></i><i class="bx bx-chevron-up icon-close"></i></a>
                  <div id="accordion-list-3" class="collapse show" data-bs-parent=".accordion-list">
                    <p>
                      Bagi Pemegang kartu ini, apabila pentas di daerah Kabupaten Nganjuk harus melampirkan fotokopi KARTU INDUK SENIMAN/ ORGANISASI KESENIAN pada permohonan surat ijin ke MUSPIKA dan ADVIS dari DINAS PORABUDPAR.  
                    </p>
                  </div>
                </li>
                <li>
                  <a data-bs-toggle="collapse" class="collapse" data-bs-target="#accordion-list-1"><span>04</span><i class="bx bx-chevron-down icon-show"></i><i class="bx bx-chevron-up icon-close"></i></a>
                  <div id="accordion-list-1" class="collapse show" data-bs-parent=".accordion-list">
                    <p>
                      Bagi Seniman/Organisasi Kesenian yang pentas di Luar Kabupaten Nganjuk harus mendapatkan REKOMENDASI dari DINAS PORABUDPAR Kabupaten Nganjuk.  
                    </p>
                  </div>
                </li>

                <li>
                  <a data-bs-toggle="collapse" data-bs-target="#accordion-list-2" class="collapsed"><span>05</span><i class="bx bx-chevron-down icon-show"></i><i class="bx bx-chevron-up icon-close"></i></a>
                  <div id="accordion-list-2" class="collapse show" data-bs-parent=".accordion-list">
                    <p>
                      Pemegang Kartu Induk WAJIB mentaati dan melaksanakan segala ketentuan dan peraturan perundang-undangan yang berlaku. 
                    </p>
                  </div>
                </li>

                <li>
                  <a data-bs-toggle="collapse" data-bs-target="#accordion-list-3" class="collapsed"><span>06</span> <i class="bx bx-chevron-down icon-show"></i><i class="bx bx-chevron-up icon-close"></i></a>
                  <div id="accordion-list-3" class="collapse show" data-bs-parent=".accordion-list">
                    <p>
                      Pengurusan KARTU INDUK SENIMAN dan ORGANISASI KESENIAN  GRATIS  
                    </p>
                  </div>
                </li>
              </ul>
            </div>

          </div>

          <div class="col-lg-5 align-items-stretch order-1 order-lg-2 img" style='background-image: url("<?php echo $tPath; ?>/public/assets/img/LandingPage/hero3.png");' data-aos="zoom-in" data-aos-delay="150">&nbsp;</div>
        </div>

      </div>
    </section>

  </main><!-- End #main -->

  <!-- ======= Footer ======= -->
  <footer id="footer">
    <div class="footer-top">
      <div class="container">
        <div class="row">

          <div class="col-lg-8 col-md-6 footer-contact">
            <h3>DISPORABUDPAR</h3>
            <p>
              Kabupaten Nganjuk <br>
              Jawa Timur<br>
              64419 <br><br>
              
              
            </p>
          </div>

          <div class="col-lg-4 col-md-6 footer-links">
            <h4>Kontak</h4>
            <div class="social-links mt-3">
              <a href="#" class="twitter"><i class="bx bi-envelope"></i></a>
              <strong>diporabudpar@gmail.com</strong> <br><br>
              <a href="#" class="facebook"><i class="bx bi-phone"></i></a>
              <strong>+62 8729166615</strong> <br><br>
              <a href="#" class="instagram"><i class="bx bxl-instagram"></i></a>
              <strong>@disporabudpar.nganjuk </strong>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="container footer-bottom clearfix">
      <div class="copyright">
        &copy; Copyright <strong><span>HufflePuff</span></strong>. All Rights Reserved
      </div>
      <div class="credits">
        <!-- All the links in the footer should remain intact. -->
        <!-- You can delete the links only if you purchased the pro version. -->
        <!-- Licensing information: https://bootstrapmade.com/license/ -->
        <!-- Purchase the pro version with working PHP/AJAX contact form: https://bootstrapmade.com/arsha-free-bootstrap-html-template-corporate/ -->
        Designed by <a href="https://bootstrapmade.com/">BootstrapMade</a>
      </div>
    </div>
  </footer>
  <!-- End Footer -->

  <div id="preloader"></div>
  <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

  <!-- Vendor JS Files -->
  <script src="<?php echo $tPath; ?>/public/assets/vendor/aos/aos.js"></script>
  <script src="<?php echo $tPath; ?>/public/assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="<?php echo $tPath; ?>/public/assets/vendor/glightbox/js/glightbox.min.js"></script>
  <script src="<?php echo $tPath; ?>/public/assets/vendor/isotope-layout/isotope.pkgd.min.js"></script>
  <script src="<?php echo $tPath; ?>/public/assets/vendor/swiper/swiper-bundle.min.js"></script>
  <script src="<?php echo $tPath; ?>/public/assets/vendor/waypoints/noframework.waypoints.js"></script>
  <script src="<?php echo $tPath; ?>/public/assets/vendor/php-email-form/validate.js"></script>

  <!-- Template Main JS File -->
  <script src="<?php echo $tPath; ?>/public/assets/js/LandingPage.js"></script>

</body>

</html>