<?php
if(!@$_SESSION) {
  session_start();
}

include('partials/header.php');
include('modules/cek_login.php');
include('modules/koneksi.php');
require('modules/encrypt.php');

$_SESSION['diagnosa']=False;
if($_SESSION['role'] == 'admin') {
  $stmt = $koneksi->prepare('SELECT * FROM diagnosa');
  $stmt->execute();
  $data = $stmt->fetchAll(PDO::FETCH_OBJ);
} else {
  $id = $_SESSION['id'];
  $stmt = $koneksi->prepare('SELECT * FROM diagnosa WHERE user = ?');
  $stmt->execute([$id]);
  $data = $stmt->fetchAll(PDO::FETCH_OBJ);
}

?>
  <main>
    <?php include('partials/navbar.php') ?>
    <!-- Hero -->
    <div class="container-fluid col-xxl-8 hero px-5 py-4 g-4" style="position: relative;">
      <div class="row flex-lg-row-reverse align-items-center mt-5">
        <div class="col-lg-10 mb-5">
          <h2 class="text_primary fw-bold lh-1 mb-4" style="text-shadow: -2px 0 #fff, 0 2px #fff, 2px 0 #fff, 0 -2px #fff;">Riwayat Diagnosa <?= $_SESSION['role'] == 'admin' ? 'User' : '' ?></h2>
          <table id="riwayat" class="table nowrap" style="width: 100%;">
            <thead>
              <tr>
                <th scope="col">No</th>
                <?php if($_SESSION['role'] == 'admin'): ?>
                  <th scope="col">Nama</th>
                <?php endif; ?>
                <th scope="col">Penyakit</th>
                <th scope="col">Persentase</th>
                <th scope="col">Tanggal diagnosa</th>
                <th scope="col"></th>
              </tr>
            </thead>
            <tbody>
              <?php foreach($data as $key => $d): ?>
                <tr>
                  <td scope="row"><?= $key+1 ?></td>
                  <?php if($_SESSION['role'] == 'admin'):
                    $stmU = $koneksi->prepare('SELECT nama FROM users WHERE user_id = :id');
                    $stmU->bindParam(':id', $d->user);
                    $stmU->execute();
                    $user = $stmU->fetch(PDO::FETCH_OBJ);
                  ?>
                    <th scope="col"><?= $user->nama ?></th>
                  <?php endif; ?>
                  <td><?= $d->penyakit ?></td>
                  <td><?= $d->persentase ?></td>
                  <td><?= $d->tanggal_diagnosa ?></td>
                  <td>
                    <a href="/<?= explode('/', $_SERVER['REQUEST_URI'])[1]?>/cetak.php?kode=<?= encrypt($d->kode_diagnosa)?>" class="a_primary me-1">Cetak</a>
                  </td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
    <!-- Virus -->
    <?php include('partials/virus2.php') ?>
  </main>
  <script>
    $(document).ready(function() {
      $('#riwayat').DataTable({
        responsive: true
      });
    });
  </script>
<?php include('partials/script.php') ?>
