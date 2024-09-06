<?php
include('partials/header.php');
if(!@$_SESSION) {
  session_start();
}
if(isset($_SESSION['diagnosa'])) {
  $_SESSION['diagnosa']=False;
}
?>
  <main>
    <!-- Virus -->
    <?php include('partials/virus.php') ?>
    <!-- Hero -->
    <div class="container-fluid col-xxl-8 hero" style="position: relative;">
      <div class="row flex-lg-row-reverse align-items-center">
        <div class="col-12 col-sm-8 col-lg-6 h-100 hero-img"></div>
        <div class="col-lg-6 px-5 py-5 g-5">
          <h1 class="display-5 text_primary fw-bold lh-1 mb-3">Kucing Sakit?<p class="mt-2">Cek Disini</p></h1>
          <p class="lead text_primary">Discover signs of common cat diseases to ensure your feline friend stays healthy and happy.</p>
          <div class="d-grid gap-2 d-sm-flex justify-content-md-start">
            <?php
            if (!isset($_SESSION['loggedIn'])) {
              ?>
              <a href="daftar.php" class="btn btn_primary transition duration-700 shadow-primary btn-md me-md-2">Daftar</a>
              <a href="masuk.php" class="btn btn-md px-4 btn_hero_2">Masuk</a>
              <?php
            } else {
              ?>
              <a href="dashboard.php" class="btn btn_primary transition duration-700 shadow-primary btn-md me-md-2">Dashboard</a>
              <?php
            }
            ?>
          </div>
        </div>
      </div>
    </div>
  </main>
<?php include('partials/script.php') ?>
