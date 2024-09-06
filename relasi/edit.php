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
    $dec_id = decrypt($enc);
    $stmt = $koneksi->prepare('SELECT * FROM relasi_gejala WHERE kode_relasi = :kode');
    $stmt->bindParam(':kode', $dec_id);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_OBJ);

    if ($stmt->rowCount() > 0) {
      include('../partials/header.php');

      $gejala = $koneksi->query('SELECT * FROM gejala')->fetchAll(PDO::FETCH_OBJ);
      $penyakit = $koneksi->query('SELECT * FROM penyakit')->fetchAll(PDO::FETCH_OBJ);

      if(isset($_POST['submit'])) {
        if(!$_POST['gejala'] || !$_POST['penyakit']) {
          header('Location: edit.php?r='.$enc.'&alert=error');
          $_SESSION['message'] = 'Gejala dan penyakit harus diisi';
        } else {
          $ada = False;
          if($_POST['gejala'] != $row->gejala || $_POST['penyakit'] != $row->penyakit) {
            $stmtc = $koneksi->prepare('SELECT * FROM relasi_gejala WHERE penyakit = :penyakit AND gejala = :gejala');
            $stmtc->execute([
              ':penyakit'=> $_POST['penyakit'],
              ':gejala'=> $_POST['gejala']
            ]);
            if($stmtc->rowCount() > 0) {
              header('Location: edit.php?r='.$enc.'&alert=error');
              $_SESSION['message'] = 'Gejala dan penyakit sudah ada direlasi';
              $ada = True;
            }
          }

          if(!$ada) {
            $data = [
              'kode' => $dec_id,
              'gejala' => $_POST['gejala'],
              'penyakit' => $_POST['penyakit'],
            ];

            $stmt1 = $koneksi->prepare('UPDATE relasi_gejala SET gejala=:gejala, penyakit=:penyakit WHERE kode_relasi=:kode');
            $stmt1->execute($data);
            header('Location: index.php?alert=success');
            $_SESSION['message'] = 'Berhasil melakukan edit relasi';
          }
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
                <h2 class="text_primary fw-bold lh-1" style="text-shadow: -2px 0 #fff, 0 2px #fff, 2px 0 #fff, 0 -2px #fff;">Edit Relasi</h2>
                <form method="POST" class="form_glass mt-4 row">
                  <div class="mb-3 mt-3">
                    <label for="gejala" class="form-label">Gejala <span class="text-danger text-sm">*</span></label>
                    <select id="gejala" name="gejala" class="form-select log">
                      <option value="">Pilih gejala</option>
                      <?php foreach($gejala as $g): ?>
                        <option value="<?= $g->kode_gejala ?>" <?=($row->gejala==$g->kode_gejala) ? 'selected' : '';?> ><?= $g->nama_gejala ?></option>
                      <?php endforeach; ?>
                    </select>
                  </div>
                  <div class="mb-3">
                    <label for="penyakit" class="form-label">Penyakit <span class="text-danger text-sm">*</span></label>
                    <select id="penyakit" name="penyakit" class="form-select log">
                      <option value="">Pilih penyakit</option>
                      <?php foreach($penyakit as $p): ?>
                        <option value="<?= $p->kode_penyakit ?>" <?=($row->penyakit==$p->kode_penyakit) ? 'selected' : '';?> ><?= $p->nama_penyakit ?></option>
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
<?php include('../partials/script.php');
    } else {
      header('Location: index.php?alert=error');
      $_SESSION['message'] = 'Relasi tidak ditemukan';
    }
  }
}
