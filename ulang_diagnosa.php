<?php
include('modules/cek_login.php');
session_start();
$_SESSION['diagnosa']=False;
header('Location: diagnosa.php');
