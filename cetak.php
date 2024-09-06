<?php
include('modules/cek_login.php');
include('modules/koneksi.php');
require('modules/encrypt.php');

if(!@$_SESSION) {
  session_start();
}

if($_GET['kode'] != '') {
  $enc = $_GET['kode'];
  $dec_kode = decrypt($enc);
  $stmt = $koneksi->prepare('SELECT * FROM diagnosa WHERE kode_diagnosa = :kode');
  $stmt->bindParam(':kode', $dec_kode);
  $stmt->execute();
  $row = $stmt->fetch(PDO::FETCH_OBJ);
  $date = new DateTime($row->tanggal_diagnosa);
  $resultDate = $date->format('j F, Y');

  $stmU = $koneksi->prepare('SELECT nama FROM users WHERE user_id = :id');
  $stmU->bindParam(':id', $row->user);
  $stmU->execute();
  $user = $stmU->fetch(PDO::FETCH_OBJ);

  $gejala = explode(',', $row->gejala);
  $penyakit = explode(',', $row->penyakit);
?>
  <table cellpadding="0" cellspacing="0" style="border-collapse: collapse; margin: 0 auto;" width="60%">
    <tr style="border-bottom: 3px solid #000;">
      <td colspan="2" style="padding: 15px; text-align: center;">Hasil Diagnosa Klinik Kucing</td>
    </tr>
    <tr>
      <td style="padding: 15px; padding-right: 40px;">Atas nama <b><?= $user->nama ?></b></td>
      <td style="padding: 15px; float: right;">Tanggal <b><?= $resultDate ?></b></td>
    </tr>
    <tr style="border: solid thin; text-align: center; font-weight: bold;">
      <td style="border: solid thin; padding: 7px;">No</td>
      <td style="border: solid thin; padding: 7px;">Gejala</td>
    </tr>
    <?php
      foreach ($gejala as $i => $g) {
        $stmG = $koneksi->prepare('SELECT nama_gejala FROM gejala WHERE kode_gejala = :kode');
        $stmG->bindParam(':kode', $g);
        $stmG->execute();
        $resultGejala = $stmG->fetch(PDO::FETCH_OBJ);
    ?>
      <tr style="border: solid thin;">
        <td style="border: solid thin; text-align: center; padding: 7px;"><?= ++$i ?></td>
        <td style="border: solid thin; padding: 7px; padding-left: 5px;"><?= $resultGejala->nama_gejala ?></td>
      </tr>
    <?php } ?>
    <tr>
      <td style="padding-top: 15px;"></td>
    </tr>
    <tr>
      <td colspan="2" style="padding: 15px; border: solid thin; font-weight: bold; text-align: center;">Hasil diagnosa</td>
    </tr>
    <tr>
      <td colspan="2" style="padding: 10px; border: solid thin; text-align: center;"><?= $row->persentase ?></td>
    </tr>
    <?php
      foreach ($penyakit as $i => $p) {
        $stmG = $koneksi->prepare('SELECT * FROM penyakit WHERE kode_penyakit = :kode');
        $stmG->bindParam(':kode', $p);
        $stmG->execute();
        $resultPenyakit = $stmG->fetch(PDO::FETCH_OBJ);
    ?>
      <tr>
        <td colspan="2" style="padding-bottom: 15px; padding-top: 20px; text-align: center">Diagnosa Penyakit <?= ++$i ?></td>
      </tr>
      <tr style="border: solid thin; text-align: center; font-weight: bold;">
        <td style="border: solid thin; padding: 7px;">Penyakit</td>
        <td style="border: solid thin; padding: 7px;">Keterangan</td>
      </tr>
      <tr style="border: solid thin;">
        <td style="border: solid thin; text-align:center; padding: 7px;"><?= $resultPenyakit->nama_penyakit ?></td>
        <td style="border: solid thin; padding: 7px; padding-left: 5px; padding-right: 10px;"><?= $resultPenyakit->keterangan_penyakit ?></td>
      </tr>
      <tr style="border: solid thin; font-weight: bold;">
        <td colspan="2" style="padding: 7px; padding-left: 10px; padding-right: 10px; text-align: center;">Solusi</td>
      </tr>
      <tr style="border: solid thin;">
        <td colspan="2" style="padding: 7px; padding-left: 10px; padding-right: 10px;"><?= $resultPenyakit->solusi_penyakit ?></td>
      </tr>
    <?php } ?>
  </table>
  <script>
		window.print();
	</script>
<?php
}
