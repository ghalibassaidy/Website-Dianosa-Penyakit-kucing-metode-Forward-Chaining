<?php
if(!@$_SESSION) {
  session_start();
}

if(!$_SESSION["loggedIn"]) {
  $url = explode('/', $_SERVER['REQUEST_URI'])[1];
  header("Location: $url/masuk.php?alert=error");
  $_SESSION["message"] = "Harus login terlebih dahulu";
}else if($_SESSION["role"] != "admin") {
  $url = explode('/', $_SERVER['REQUEST_URI'])[1];
  header("Location: /$url/dashboard.php?alert=warning");
  $_SESSION["message"] = "Halaman khusus admin";
}
