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
      $stmd = $koneksi->prepare('DELETE FROM relasi_gejala WHERE kode_relasi = ?');
      $stmd->execute([$dec_id]);
      if($stmd->rowCount() > 0) {
        header('Location: index.php?alert=success');
        $_SESSION['message'] = 'Relasi berhasil dihapus';
      } else {
        header('Location: index.php?alert=error');
        $_SESSION['message'] = 'Relasi gagal dihapus';
      }
    } else {
      header('Location: index.php?alert=error');
      $_SESSION['message'] = 'Relasi tidak ditemukan';
    }
  }
}
