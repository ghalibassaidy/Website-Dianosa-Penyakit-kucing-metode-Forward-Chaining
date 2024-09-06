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
    $dec_user = decrypt($enc);
    $stmt = $koneksi->prepare('SELECT * FROM users WHERE user_id = :id');
    $stmt->bindParam(':id', $dec_user);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_OBJ);

    if ($stmt->rowCount() > 0) {
      $stmd = $koneksi->prepare('DELETE FROM users WHERE user_id = ?');
      $stmd->execute([$dec_user]);
      if($stmd->rowCount() > 0) {
        header('Location: index.php?alert=success');
        $_SESSION['message'] = 'User berhasil dihapus';
      } else {
        header('Location: index.php?alert=error');
        $_SESSION['message'] = 'User gagal dihapus';
      }
    } else {
      header('Location: index.php?alert=error');
      $_SESSION['message'] = 'User tidak ditemukan';
    }
  }
}
