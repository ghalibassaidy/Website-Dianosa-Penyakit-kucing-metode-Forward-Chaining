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
    $stmt = $koneksi->prepare('SELECT * FROM gejala WHERE kode_gejala = :id');
    $stmt->bindParam(':id', $dec_kode);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_OBJ);
    $idNum = (int)substr($row->kode_penyakit, 1, 2);

    if ($stmt->rowCount() > 0) {
      $stmd = $koneksi->prepare('DELETE FROM gejala WHERE kode_gejala = ?');
      $stmd->execute([$dec_kode]);
      if($stmd->rowCount() > 0) {
        $countData = $koneksi->query('SELECT COUNT(*) FROM gejala')->fetchColumn();
        $kodeChange = $koneksi->query('SELECT kode_gejala FROM gejala')->fetchAll();
        for ($i = $idNum; $i <= $countData; $i++) {
          if (strlen($i) == 1){
            $newKode = 'G0' .$i;
          } else if (strlen($i) == 2){
            $newKode = 'G'.$i;
          }

          $data = [
            'kode' => $kodeChange[$i-1]['kode_gejala'],
            'newKode' => $newKode,
          ];

          $stmt1 = $koneksi->prepare('UPDATE gejala SET kode_gejala=:newKode WHERE kode_gejala=:kode');
          $stmt1->execute($data);
        }
        header('Location: index.php?alert=success');
        $_SESSION['message'] = 'Gejala berhasil dihapus';
      } else {
        header('Location: index.php?alert=error');
        $_SESSION['message'] = 'Gejala gagal dihapus';
      }
    } else {
      header('Location: index.php?alert=error');
      $_SESSION['message'] = 'Gejala tidak ditemukan';
    }
  }
}
