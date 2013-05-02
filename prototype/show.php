<?

require_once('php/config.php'); 
include_once "php/shared/ez_sql_core.php";
include_once "php/ez_sql_mysql.php";

$db = new ezSQL_mysql($DB_USERNAME,$DB_PASSWORD,$DB_DATABASE,$DB_HOST);

$shape = $db->get_row("SELECT * FROM shapes WHERE url = '".$_GET['id']."'");

?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <title>figuros</title>
    <meta charset="utf-8" />
    <link rel="stylesheet" href="css/global.css" />
    <link rel="icon" type="image/png" href="i/favicon.png" />
    <link href='http://fonts.googleapis.com/css?family=Raleway:100,200,300,700' rel='stylesheet' type='text/css'>
  </head>
  <body class="generate show">
    <header>
      <section id="container">
        <h1><a href="/">figuros</a></h1>
        <h4><a href="http://instagram.com/<?= $shape->userA ?>"><?= $shape->userA ?></a> &amp; <a href="http://instagram.com/<?= $shape->userB ?>"><?= $shape->userB ?></a></h4>
        <div id="actions">
          <p><a id="make-btn" href="https://instagram.com/oauth/authorize/?client_id=7fda2488dc134143a3b450be59f4dad7&amp;redirect_uri=http://figuros.com/generate.php&amp;response_type=token">Make your own</a></p>
          <ul>
            <li class="share-twitter"><a href="#">Twitter</a></li>
            <li class="share-facebook"><a href="#">Facebook</a></li>
            <li class="share-vine"><a href="#">Vine</a></li>
          </ul>
        </div>
      </section>
    </header>
    <p style="color: #000"><?= $shape->data ?></p>
    <!-- <img id="instructions" src="i/instructions.png" width="990" height="400" alt="Instructions" /> -->
    <!-- <canvas id="imageHolder" width="300" height="300" style="display: none"></canvas> -->
  </body>
  <script src="js/libs/jquery-1.9.1.js"></script>
  <script src="js/libs/jquery.tmpl.min.js"></script>
  <script src="js/libs/jquery.json-2.3.min.js"></script>
  <script src="js/libs/validate.min.js"></script>
  <script src="js/libs/d3.min.js"></script>
  <script src="js/main.js"></script>
</html>