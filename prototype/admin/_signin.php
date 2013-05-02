<?php

  include_once("../php/config.php");
  
  // clear out any existing session that may exist
  session_start();
  session_destroy();
  session_start();
  
  $saltedInput = $_POST['password'] . $ADMIN_SALT;

  if ($ADMIN_USER === $_POST['username'] && $ADMIN_PASSWORD === hash('sha256', $saltedInput)) {
    $_SESSION['signed_in'] = true;
    $_SESSION['username'] = $_POST['username'];
    header("Location: " . $ADMIN_PATH . "/shapes.php");
  } elseif ($ADMIN_SECONDARY_USER === $_POST['username'] && $ADMIN_SECONDARY_PASSWORD === hash('sha256', $saltedInput)) {
    $_SESSION['signed_in'] = true;
    $_SESSION['username'] = $_POST['username'];
    header("Location: " . $ADMIN_PATH . "/shapes.php");
  
  } else {
    $_SESSION['flash_error'] = "Invalid username or password";
    $_SESSION['signed_in'] = false;
    $_SESSION['username'] = null;
    header("Location: " . $ADMIN_PATH . "/shapes.php");
  }
?>