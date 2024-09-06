<?php
if(!@$_SESSION) {
  session_start();
}

$path = explode('/', $_SERVER['SCRIPT_NAME'])[2];
?>
<div class="fixed-top navbar_simple mt-4">
  <div class="float-end d-none d-md-inline-flex mx-5 menu1">
    <a href="/<?= explode('/', $_SERVER['REQUEST_URI'])[1]?>/index.php" class="me-2 <?php echo ($path == 'index.php' ? 'active' : '') ?>">Home</a>
    <div class="divide"></div>
    <a href="/<?= explode('/', $_SERVER['REQUEST_URI'])[1]?>/dashboard.php" class="me-2 ms-2 <?php echo ($path == 'dashboard.php' ? 'active' : '') ?>">Dashboard</a>
    <div class="divide"></div>
    <?php if($_SESSION['role'] == 'admin') { ?>
      <a href="/<?= explode('/', $_SERVER['REQUEST_URI'])[1]?>/penyakit/index.php" class="ms-2 me-2 <?php echo ($path == 'penyakit' ? 'active' : '') ?>">Penyakit</a>
      <div class="divide"></div>
      <a href="/<?= explode('/', $_SERVER['REQUEST_URI'])[1]?>/gejala/index.php" class="ms-2 me-2 <?php echo ($path == 'gejala' ? 'active' : '') ?>">Gejala</a>
      <div class="divide"></div>
      <a href="/<?= explode('/', $_SERVER['REQUEST_URI'])[1]?>/relasi/index.php" class="ms-2 me-2 <?php echo ($path == 'relasi' ? 'active' : '') ?>">Relasi</a>
      <div class="divide"></div>
      <a href="/<?= explode('/', $_SERVER['REQUEST_URI'])[1]?>/riwayat.php" class="ms-2 me-2 <?php echo ($path == 'riwayat.php' ? 'active' : '') ?>">Riwayat</a>
      <div class="divide"></div>
      <a href="/<?= explode('/', $_SERVER['REQUEST_URI'])[1]?>/user/index.php" class="ms-2 me-2 <?php echo ($path == 'user' ? 'active' : '') ?>">Pengguna</a>
    <?php } else { ?>
      <a href="/<?= explode('/', $_SERVER['REQUEST_URI'])[1]?>/diagnosa.php" class="ms-2 me-2 <?php echo ($path == 'diagnosa.php' ? 'active' : '') ?>">Diagnosa</a>
      <div class="divide"></div>
      <a href="/<?= explode('/', $_SERVER['REQUEST_URI'])[1]?>/riwayat.php" class="ms-2 me-2 <?php echo ($path == 'riwayat.php' ? 'active' : '') ?>">Riwayat</a>
    <?php } ?>
    <div class="divide"></div>
    <a href="/<?= explode('/', $_SERVER['REQUEST_URI'])[1]?>/profil.php" class="ms-2 me-2">Profil</a>
    <div class="divide"></div>
    <a href="/<?= explode('/', $_SERVER['REQUEST_URI'])[1]?>/keluar.php" class="ms-2">Keluar</a>
  </div>
  <div class="d-block d-md-none float-end mx-5 menu2">
    <div class="dropdown">
      <button class="btn btn_primary" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-three-dots-vertical" viewBox="0 0 16 16">
          <path d="M9.5 13a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0zm0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0zm0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0z"/>
        </svg>
      </button>
      <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
        <li><a href="/<?= explode('/', $_SERVER['REQUEST_URI'])[1]?>/index.php" class="dropdown-item <?php echo ($path == 'index.php' ? 'active' : '') ?>">Home</a></li>
        <li><a href="/<?= explode('/', $_SERVER['REQUEST_URI'])[1]?>/dashboard.php" class="dropdown-item <?php echo ($path == 'dashboard.php' ? 'active' : '') ?>">Dashboard</a></li>
        <?php if($_SESSION['role'] == 'admin') { ?>
          <li><a href="/<?= explode('/', $_SERVER['REQUEST_URI'])[1]?>/penyakit/index.php" class="dropdown-item <?php echo ($path == 'penyakit' ? 'active' : '') ?>">Penyakit</a></li>
          <li><a href="/<?= explode('/', $_SERVER['REQUEST_URI'])[1]?>/gejala/index.php" class="dropdown-item <?php echo ($path == 'gejala' ? 'active' : '') ?>">Gejala</a></li>
          <li><a href="/<?= explode('/', $_SERVER['REQUEST_URI'])[1]?>/relasi/index.php" class="dropdown-item <?php echo ($path == 'relasi' ? 'active' : '') ?>">Relasi</a></li>
          <li><a href="/<?= explode('/', $_SERVER['REQUEST_URI'])[1]?>/riwayat.php" class="dropdown-item <?php echo ($path == 'riwayat.php' ? 'active' : '') ?>">Riwayat</a></li>
          <li><a href="/<?= explode('/', $_SERVER['REQUEST_URI'])[1]?>/user/index.php" class="dropdown-item <?php echo ($path == 'user' ? 'active' : '') ?>">Pengguna</a></li>
        <?php } else { ?>
          <li><a href="/<?= explode('/', $_SERVER['REQUEST_URI'])[1]?>/diagnosa.php" class="dropdown-item <?php echo ($path == 'diagnosa.php' ? 'active' : '') ?>">Diagnosa</a></li>
          <li><a href="/<?= explode('/', $_SERVER['REQUEST_URI'])[1]?>/riwayat.php" class="dropdown-item <?php echo ($path == 'riwayat.php' ? 'active' : '') ?>">Riwayat</a></li>
        <?php } ?>
        <li><a href="/<?= explode('/', $_SERVER['REQUEST_URI'])[1]?>/profil.php" class="dropdown-item">Profil</a></li>
        <li><a href="/<?= explode('/', $_SERVER['REQUEST_URI'])[1]?>/keluar.php" class="dropdown-item">Keluar</a></li>
      </ul>
    </div>
  </div>
</div>
