<?php
include('modules/cek_login.php');
session_start();
session_unset();
session_destroy();
header('Location: index.php');
