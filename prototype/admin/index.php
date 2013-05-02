<?php
session_start();
?>
<html lang="en">
  <head>
    <link rel="stylesheet" href="style.css">
    <title>Figuros</title>
    <meta charset="utf-8" />
  </head>
  <body class="signin">
    <section>
      <? if (isset($_SESSION['flash_error'])) { ?><div class="errors"><?= $_SESSION['flash_error']; ?></div><? } ?>
      <form method="post" action="_signin.php">
        <label for="username">Username <input type="text" type="email" name="username" /></label>
        <label for="password">Password <input type="password" name="password" /></label>
        <input type="submit" class="btn" value="Sign in" />
      </form>
    </section>
  </body>
</html>
<? $_SESSION['flash_error'] = ""; ?>