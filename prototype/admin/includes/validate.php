<?php
  include_once('../php/config.php');
  session_start();
  if (!$_SESSION['signed_in']) {
    $_SESSION['flash_error'] = "Please sign in";
    header("Location: " . $ADMIN_PATH . "/index.php");
    exit; // IMPORTANT: Be sure to exit here!
  }
?>