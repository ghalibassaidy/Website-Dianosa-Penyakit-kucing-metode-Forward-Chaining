<?php
include('../modules/cek_admin.php');
include('../modules/koneksi.php');
require('../modules/encrypt.php');

if(!@$_SESSION) {
  session_start();
}

if($_SESSION['role'] == 'admin') {
  if($_GET['r'] != '') {
    $enc = $_GET['r'];
    $dec_kode = decrypt($enc);
    $stmt = $koneksi->prepare('SELECT * FROM penyakit WHERE kode_penyakit = :id');
    $stmt->bindParam(':id', $dec_kode);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_OBJ);

    if ($stmt->rowCount() > 0) {
      include('../partials/header.php');

      if(isset($_POST['submit'])) {
        if(!$_POST['nama'] || !$_POST['keterangan'] || !$_POST['solusi']) {
          header('Location: edit.php?r='.$enc.'&alert=error');
          $_SESSION['message'] = 'Nama, keterangan, solusi penyakit harus diisi';
        } else {
          $data = [
            'kode' => $dec_kode,
            'nama' => $_POST['nama'],
            'keterangan' => $_POST['keterangan'],
            'solusi' => $_POST['solusi']
          ];

          $stmt1 = $koneksi->prepare('UPDATE penyakit SET nama_penyakit=:nama, keterangan_penyakit=:keterangan, solusi_penyakit=:solusi WHERE kode_penyakit=:kode');
          $stmt1->execute($data);
          header('Location: index.php?alert=success');
          $_SESSION['message'] = 'Berhasil melakukan edit penyakit';
        }
      }
    ?>
        <main>
          <?php include('../partials/navbar.php') ?>
          <!-- Hero -->
          <div class="container-fluid col-xxl-8 hero px-5 py-4 g-4" style="position: relative;">
            <div class="row flex-lg-row-reverse align-items-center mt-5">
              <div class="col-lg-9 pt-2">
                <?php include('../partials/alert.php') ?>
                <h2 class="text_primary fw-bold lh-1" style="text-shadow: -2px 0 #fff, 0 2px #fff, 2px 0 #fff, 0 -2px #fff;">Edit Penyakit</h2>
                <form method="POST" class="form_glass mt-4 row">
                  <div class="mb-3 mt-3">
                    <label for="nama" class="form-label">Nama <span class="text-danger text-sm">*</span></label>
                    <input type="text" name="nama" class="form-control log" id="nama" value="<?= $row->nama_penyakit ?>">
                  </div>
                  <div class="mt-3 mb-3">
                    <label for="keterangan" class="form-label">Keterangan <span class="text-danger text-sm">*</span></label>
                    <textarea class="form-control log" id="keterangan" name="keterangan" rows="3"><?= $row->keterangan_penyakit ?></textarea>
                  </div>
                  <div class="mt-3 mb-3">
                    <label for="solusi" class="form-label">Solusi <span class="text-danger text-sm">*</span></label>
                    <textarea class="form-control log" id="solusi" name="solusi" rows="5"><?= $row->solusi_penyakit ?></textarea>
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
<?php include('../partials/script.php');
    } else {
      header('Location: index.php?alert=error');
      $_SESSION['message'] = 'Penyakit tidak ditemukan';
    }
  }
}
