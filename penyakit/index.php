<?php
if(!@$_SESSION) {
  session_start();
}

include('../partials/header.php');
include('../modules/cek_admin.php');
include('../modules/koneksi.php');
require('../modules/encrypt.php');

$stmD = $koneksi->query('SELECT * FROM penyakit');
$data = $stmD->fetchAll(PDO::FETCH_OBJ);

$pNum = $stmD->rowCount();
if ($pNum > 0) {
  $tambahId = $pNum + 1;
  if (strlen($tambahId) == 1){
    $idP = 'P0' .$tambahId;
  } else if (strlen($tambahId) == 2){
    $idP = 'P'.$tambahId;
  }
} else {
  $idP = 'P01';
}

if(isset($_POST['submit'])) {
  if($_SESSION['role'] == 'admin') {
    if(!$_POST['nama'] || !$_POST['keterangan'] || !$_POST['solusi']) {
      header('Location: index.php?alert=error');
      $_SESSION['message'] = 'Nama, keterangan, solusi penyakit harus diisi';
    } else {
      $data = [
        'kode' => $idP,
        'nama' => $_POST['nama'],
        'keterangan' => $_POST['keterangan'],
        'solusi' => $_POST['solusi'],
      ];

      $stmt= $koneksi->prepare('INSERT INTO penyakit (kode_penyakit, nama_penyakit, keterangan_penyakit, solusi_penyakit) VALUES (:kode, :nama, :keterangan, :solusi)');
      $stmt->execute($data);
      if($stmt->rowCount() > 0) {
        header('Location: index.php?alert=success');
        $_SESSION['message'] = 'Berhasil melakukan penambahan penyakit';
      } else {
        header('Location: index.php?alert=error');
        $_SESSION['message'] = 'Gagal melakukan penambahan penyakit';
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
          <h2 class="text_primary fw-bold lh-1 mb-4" style="text-shadow: -2px 0 #fff, 0 2px #fff, 2px 0 #fff, 0 -2px #fff;">Daftar Penyakit</h2>
          <table id="penyakit" class="table nowrap" style="width: 100%;">
            <thead>
              <tr>
                <th scope="col">No</th>
                <th scope="col">Kode</th>
                <th scope="col">Nama</th>
                <th scope="col">Keterangan</th>
                <th scope="col">Solusi</th>
                <th scope="col"></th>
              </tr>
            </thead>
            <tbody>
              <?php foreach($data as $key => $d): ?>
                <tr>
                  <td scope="row"><?= $key+1 ?></td>
                  <td><?= $d->kode_penyakit ?></td>
                  <td><?= $d->nama_penyakit ?></td>
                  <td><?= $d->keterangan_penyakit ?></td>
                  <td><?= $d->solusi_penyakit ?></td>
                  <td>
                    <a href="edit.php?r=<?= encrypt($d->kode_penyakit)?>" class="a_primary me-1">Edit</a>
                    <a href="hapus.php?r=<?= encrypt($d->kode_penyakit)?>" class="text-danger">Hapus</a>
                  </td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
        <div class="col-lg-5">
          <?php include('../partials/alert.php') ?>
          <h2 class="text_primary fw-bold lh-1" style="text-shadow: -2px 0 #fff, 0 2px #fff, 2px 0 #fff, 0 -2px #fff;">Tambah Penyakit</h2>
          <form method="POST" class="form_glass mt-4">
            <div class="mb-3 mt-3">
              <label for="kode" class="form-label">Kode <span class="text-danger text-sm">*</span></label>
              <input type="text" name="kode" class="form-control log" id="kode" readonly disabled value="<?= $idP ?>">
            </div>
            <div class="mb-3">
              <label for="nama" class="form-label">Nama <span class="text-danger text-sm">*</span></label>
              <input type="text" name="nama" class="form-control log" id="nama">
            </div>
            <div class="mb-3">
              <label for="keterangan" class="form-label">Keterangan <span class="text-danger text-sm">*</span></label>
              <textarea class="form-control log" id="keterangan" name="keterangan" rows="3"></textarea>
            </div>
            <div class="mb-3">
              <label for="keterangan" class="form-label">Solusi Penyakit <span class="text-danger text-sm">*</span></label>
              <textarea class="form-control log" id="solusi" name="solusi" rows="5"></textarea>
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
      $('#penyakit').DataTable({
        responsive: true
      });
    });
  </script>
<?php include('../partials/script.php') ?>
