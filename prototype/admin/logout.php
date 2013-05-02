<?php
  include_once('../php/config.php');
  session_start();
  unset($_SESSION['username']);
  unset($_SESSION['signed_in']);
  // session_destroy();
  $_SESSION['flash_error'] = "Logged out";
  header("Location: " . $ADMIN_PATH . "/index.php");
  exit;
?>