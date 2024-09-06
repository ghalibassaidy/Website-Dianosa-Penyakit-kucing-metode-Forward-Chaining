<?php
if(!@$_SESSION) {
  session_start();
}

include('../partials/header.php');
include('../modules/cek_admin.php');
include('../modules/koneksi.php');
require('../modules/encrypt.php');

$data = $koneksi->query('SELECT * FROM relasi_gejala JOIN gejala ON relasi_gejala.gejala = gejala.kode_gejala JOIN penyakit ON relasi_gejala.penyakit = penyakit.kode_penyakit')->fetchAll(PDO::FETCH_OBJ);
$gejala = $koneksi->query('SELECT * FROM gejala')->fetchAll(PDO::FETCH_OBJ);
$penyakit = $koneksi->query('SELECT * FROM penyakit')->fetchAll(PDO::FETCH_OBJ);

$stmD = $koneksi->query('SELECT * FROM relasi_gejala');
$rNum = $stmD->rowCount();
if ($rNum > 0) {
  $tambahId = $rNum + 1;
  if (strlen($tambahId) == 1){
    $idR = 'R0' .$tambahId;
  } else if (strlen($tambahId) == 2){
    $idR = 'R'.$tambahId;
  }
} else {
  $idR = 'R01';
}

if(isset($_POST['submit'])) {
  if($_SESSION['role'] == 'admin') {
    if(!$_POST['gejala'] || !$_POST['penyakit']) {
      header('Location: index.php?alert=error');
      $_SESSION['message'] = 'Gejala, penyakit harus diisi';
    } else {
      $stmtc = $koneksi->prepare('SELECT * FROM relasi_gejala WHERE penyakit = :penyakit AND gejala = :gejala');
      $stmtc->execute([
        ':penyakit'=> $_POST['penyakit'],
        ':gejala'=> $_POST['gejala']
      ]);
      if($stmtc->rowCount() > 0) {
        header('Location: index.php?alert=error');
        $_SESSION['message'] = 'Gejala dan penyakit sudah direlasi';
      } else {
        $data = [
          'kode' => $idR,
          'gejala' => $_POST['gejala'],
          'penyakit' => $_POST['penyakit'],
        ];

        $stmt= $koneksi->prepare('INSERT INTO relasi_gejala (id, gejala, penyakit) VALUES (:kode, :gejala, :penyakit)');
        $stmt->execute($data);
        if($stmt->rowCount() > 0) {
          header('Location: index.php?alert=success');
          $_SESSION['message'] = 'Berhasil melakukan penambahan relasi';
        } else {
          header('Location: index.php?alert=error');
          $_SESSION['message'] = 'Gagal melakukan penambahan relasi';
        }
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
          <h2 class="text_primary fw-bold lh-1 mb-4" style="text-shadow: -2px 0 #fff, 0 2px #fff, 2px 0 #fff, 0 -2px #fff;">Daftar Relasi</h2>
          <table id="relasi" class="table nowrap" style="width: 100%;">
            <thead>
              <tr>
                <th scope="col">No</th>
                <th scope="col">Kode</th>
                <th scope="col">Gejala</th>
                <th scope="col">Penyakit</th>
                <th scope="col">Bobot</th>
                <th scope="col"></th>
              </tr>
            </thead>
            <tbody>
            <?php foreach($data as $key => $d): ?>
    <tr>
        <td scope="row"><?= $key+1 ?></td>
        <td><?= isset($d->kode_relasi) ? $d->kode_relasi : 'N/A' ?></td>
        <td><?= isset($d->nama_gejala) ? $d->nama_gejala : 'N/A' ?></td>
        <td><?= isset($d->nama_penyakit) ? $d->nama_penyakit : 'N/A' ?></td>
        <td><?= isset($d->bobot_gejala) ? $d->bobot_gejala : 'N/A' ?></td>
        <td>
            <a href="edit.php?r=<?= isset($d->kode_relasi) ? encrypt($d->kode_relasi) : '#' ?>" class="a_primary me-1">Edit</a>
            <a href="hapus.php?r=<?= isset($d->kode_relasi) ? encrypt($d->kode_relasi) : '#' ?>" class="text-danger">Hapus</a>
        </td>
    </tr>
<?php endforeach; ?>

            </tbody>
          </table>
        </div>
        <div class="col-lg-5">
          <?php include('../partials/alert.php') ?>
          <h2 class="text_primary fw-bold lh-1" style="text-shadow: -2px 0 #fff, 0 2px #fff, 2px 0 #fff, 0 -2px #fff;">Tambah Relasi</h2>
          <form method="POST" class="form_glass mt-4">
            <div class="mb-3 mt-3">
              <label for="kode_relasi" class="form-label">Kode <span class="text-danger text-sm">*</span></label>
              <input type="text" name="kode_relasi" class="form-control log" id="kode_relasi" readonly disabled value="<?= $idR ?>">
            </div>
            <div class="mb-3">
              <label for="gejala" class="form-label">Gejala <span class="text-danger text-sm">*</span></label>
              <select id="gejala" name="gejala" class="form-select log">
                <option value="">Pilih gejala</option>
                <?php foreach($gejala as $g): ?>
                  <option value="<?= $g->kode_gejala ?>"><?= $g->nama_gejala ?></option>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="mb-3">
              <label for="penyakit" class="form-label">Penyakit <span class="text-danger text-sm">*</span></label>
              <select id="penyakit" name="penyakit" class="form-select log">
                <option value="">Pilih penyakit</option>
                <?php foreach($penyakit as $p): ?>
                  <option value="<?= $p->kode_penyakit ?>"><?= $p->nama_penyakit ?></option>
                <?php endforeach; ?>
              </select>
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
      $('#relasi').DataTable({
        responsive: true
      });
    });
  </script>
<?php include('../partials/script.php') ?>
