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
    $idNum = (int)substr($row->kode_penyakit, 1, 2);

    if ($stmt->rowCount() > 0) {
      $stmd = $koneksi->prepare('DELETE FROM penyakit WHERE kode_penyakit = ?');
      $stmd->execute([$dec_kode]);
      if($stmd->rowCount() > 0) {
        $countData = $koneksi->query('SELECT COUNT(*) FROM penyakit')->fetchColumn();
        $kodeChange = $koneksi->query('SELECT kode_penyakit FROM penyakit')->fetchAll();
        for ($i = $idNum; $i <= $countData; $i++) {
          if (strlen($i) == 1){
            $newKode = 'P0' .$i;
          } else if (strlen($i) == 2){
            $newKode = 'P'.$i;
          }

          $data = [
            'kode' => $kodeChange[$i-1]['kode_penyakit'],
            'newKode' => $newKode,
          ];

          $stmt1 = $koneksi->prepare('UPDATE penyakit SET kode_penyakit=:newKode WHERE kode_penyakit=:kode');
          $stmt1->execute($data);
        }


        header('Location: index.php?alert=success');
        $_SESSION['message'] = 'Penyakit berhasil dihapus';
      } else {
        header('Location: index.php?alert=error');
        $_SESSION['message'] = 'Penyakit gagal dihapus';
      }
    } else {
      header('Location: index.php?alert=error');
      $_SESSION['message'] = 'Penyakit tidak ditemukan';
    }
  }
}
