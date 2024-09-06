<?php
if(!@$_SESSION) {
  session_start();
}

include('../partials/header.php');
include('../modules/cek_admin.php');
include('../modules/koneksi.php');
require('../modules/encrypt.php');

$stmD = $koneksi->query('SELECT * FROM gejala');
$data = $stmD->fetchAll(PDO::FETCH_OBJ);

$gNum = $stmD->rowCount();
if ($gNum > 0) {
  $tambahId = $gNum + 1;
  if (strlen($tambahId) == 1){
    $idG = 'G0' .$tambahId;
  } else if (strlen($tambahId) == 2){
    $idG = 'G'.$tambahId;
  }
} else {
  $idG = 'G01';
}

if(isset($_POST['submit'])) {
  if($_SESSION['role'] == 'admin') {
    if(!$_POST['nama'] || !$_POST['bobot']) {
      header('Location: index.php?alert=error');
      $_SESSION['message'] = 'Nama, bobot gejala harus diisi';
    } else {
      $data = [
        'kode' => $idG,
        'nama' => $_POST['nama'],
        'bobot' => $_POST['bobot'],
      ];

      $stmt= $koneksi->prepare('INSERT INTO gejala (kode_gejala, nama_gejala, bobot_gejala) VALUES (:kode, :nama, :bobot)');
      $stmt->execute($data);
      if($stmt->rowCount() > 0) {
        header('Location: index.php?alert=success');
        $_SESSION['message'] = 'Berhasil melakukan penambahan gejala';
      } else {
        header('Location: index.php?alert=error');
        $_SESSION['message'] = 'Gagal melakukan penambahan gejala';
      }
    }
  }
}

?>
  <main>
    <?php include('../partials/navbar.php') ?>
    <!-- Hero -->
    <div class="container-fluid col-xxl-8 hero px-5 py-4 g-4" style="position: relative;">
      <div class="row flex-lg-row-reverse align-items-start mt-5">
        <div class="col-lg-7 mb-5">
          <h2 class="text_primary fw-bold lh-1 mb-4" style="text-shadow: -2px 0 #fff, 0 2px #fff, 2px 0 #fff, 0 -2px #fff;">Daftar Gejala</h2>
          <table id="gejala" class="table nowrap" style="width: 100%;">
            <thead>
              <tr>
                <th scope="col">No</th>
                <th scope="col">Kode</th>
                <th scope="col">Nama</th>
                <th scope="col">Bobot</th>
                <th scope="col"></th>
              </tr>
            </thead>
            <tbody>
              <?php foreach($data as $key => $d): ?>
                <tr>
                  <td scope="row"><?= $key+1 ?></td>
                  <td><?= $d->kode_gejala ?></td>
                  <td><?= $d->nama_gejala ?></td>
                  <td><?= $d->bobot_gejala ?></td>
                  <td>
                    <a href="edit.php?r=<?= encrypt($d->kode_gejala)?>" class="a_primary me-1">Edit</a>
                    <a href="hapus.php?r=<?= encrypt($d->kode_gejala)?>" class="text-danger">Hapus</a>
                  </td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
        <div class="col-lg-5">
          <?php include('../partials/alert.php') ?>
          <h2 class="text_primary fw-bold lh-1" style="text-shadow: -2px 0 #fff, 0 2px #fff, 2px 0 #fff, 0 -2px #fff;">Tambah Gejala</h2>
          <form method="POST" class="form_glass mt-4">
            <div class="mb-3 mt-3">
              <label for="kode" class="form-label">Kode <span class="text-danger text-sm">*</span></label>
              <input type="text" name="kode" class="form-control log" id="kode" readonly disabled value="<?= $idG ?>">
            </div>
            <div class="mb-3">
              <label for="nama" class="form-label">Nama <span class="text-danger text-sm">*</span></label>
              <input type="text" name="nama" class="form-control log" id="nama">
            </div>
            <div class="mb-3">
              <label for="bobot" class="form-label">Bobot <span class="text-danger text-sm">*</span></label>
              <input type="number" class="form-control log" id="bobot" name="bobot" step="0.01">
            </div>
            <div class="btn_submit_left">
              <button type="submit" name="submit" class="ms-auto btn btn_primary transition duration-700 shadow-primary btn-md mt-2 mb-3">Submit</button>
            </div>
          </form>
        </div>
      </div>
    </div>
    <!-- Virus -->
    <?php include('../partials/virus2.php') ?>
  </main>
  <script>
    $(document).ready(function() {
      $('#gejala').DataTable({
        responsive: true
      });
    });
  </script>
<?php include('../partials/script.php') ?>
