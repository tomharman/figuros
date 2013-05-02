<?php

  include_once('../php/config.php');
  include_once('includes/validate.php');

  
?>

<html lang="en">
  <head>
    <link rel="stylesheet" href="<?= $ADMIN_PATH ?>/style.css">
    <meta charset="utf-8">
    <title>Coastermatic &mdash; Admin</title>
    <script src="../js/libs/jquery-1.7.2.min.js"></script>
    <script src="../js/libs/lazyload/jquery.lazyload.js"></script> 
    <script src="../js/libs/lazyload/jquery.scrollstop.js"></script> 
    <script type="text/javascript">
	   $(function() {
          $("img.instagram").lazyload({
              event: "scrollstop"
          });
      });
	    
    </script> 
    
    
    
    
  </head>
  <body <? if ($currentNav) { ?> id="<?= $currentNav ?>" <? } ?> >
    <div id="container">
      <div id="header-wrapper">
        <section id="header">
            <h2><a href="dashboard.php"><?= $COASTERMATIC_SERVER ?> Admin</a></h2>
            <nav>
              <ol>
                <li<? if ($currentNav == 'orders') { ?> class="nav-current"<? } ?>><a href="shapes.php">Shapes</a></li>
                <!-- <li<? if ($currentNav == 'giftcodes') { ?> class="nav-current"<? } ?>><a href="giftcodes.php">Gift Codes</a></li> -->
                <li id="nav-logout"><a href="logout.php">Log out</a></li>
              </ol>
            </nav>
        </section>
      </div>
      <section id="content">