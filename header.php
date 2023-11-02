    <div class="d-flex align-items-center justify-content-between">
      <a href="/home.php" class="logo d-flex align-items-center">
        <!-- <img src="assets/img/logo.png" alt=""> -->
        <span class="d-none d-lg-block">Nganjuk Elok</span>
      </a>
      <i class="bi bi-list toggle-sidebar-btn" id="btn"> </i>
    </div>

    <nav class="header-nav ms-auto">
      <ul class="d-flex align-items-center">

        <li class="nav-item dropdown pe-3">

          <a class="nav-link nav-profile d-flex align-items-center pe-0" href="#" data-bs-toggle="dropdown">
            <img src="/public/assets/img/ava.jpg" alt="Profile" class="rounded-circle">
            <span class="d-none d-md-block dropdown-toggle ps-2">Huffle Puff</span>
          </a><!-- End Profile Iamge Icon -->

          <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow profile">
            <li class="dropdown-header">
              <h6>Huffle Puff</h6>
              <span>Super Admin</span>
            </li>
            <li>
              <hr class="dropdown-divider">
            </li>

            <li >
              <!-- <a class="dropdown-item d-flex align-items-center" href="/profile.php?id_user=<?php //$userAuth['id_user']?>"> -->
              <a class="dropdown-item d-flex align-items-center" href="/profile.php<?php //$userAuth['id_user']?>">
                <i class="bi bi-person"></i>
                <span>Akun</span>
              </a>
            </li>
            <li>
              <hr class="dropdown-divider">
            </li>

            <li class="dropdown-item d-flex align-items-center" onclick="logout()">
              <a href="#">
                <i class="bi bi-box-arrow-right"></i>
                <span>Keluar</span>
              </a>
            </li>

          </ul><!-- End Profile Dropdown Items -->
        </li><!-- End Profile Nav -->

      </ul>
    </nav>
    <script src="/public/js/utama/logout.js"></script>