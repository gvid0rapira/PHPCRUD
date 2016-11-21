<?php
session_start();
$old_user = $_SESSION['user'];
unset($_SESSION['user']);
session_destroy();
require 'login.php';
?>
