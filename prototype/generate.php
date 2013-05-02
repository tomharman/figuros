<? require_once('php/config.php'); ?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <title>figuros</title>
    <meta charset="utf-8" />
    <link rel="stylesheet" href="css/global.css" />
    <link rel="icon" type="image/png" href="i/favicon.png" />
    <link href='http://fonts.googleapis.com/css?family=Raleway:100,200,300,700' rel='stylesheet' type='text/css'>
  </head>
  <body class="generate">
    <header>
      <section id="container">
        <h1><a href="/">figuros</a></h1>
        <form action="#" method="post" id="controls">
          <fieldset>
            <span class="field-people">
              <a href="#" title="logout"><img id="self-avatar" src="i/avatar-default.png" width="30" /></a>
              <em>&amp;</em>
              <img id="partner-avatar" class="partner" src="i/avatar-default.png" width="30" height="30" />
            </span>
            <span class="field-location">
              <!-- <label for="latlon">Lat/Lon</label>
                <input id="latlon" name="latlon" class="latlon" value="40.747061887,-74.00519371" type="text" /> -->
              
              <label for="partner">select a partner</label>
              <select id="partner" class="target">
                <option value="option1" selected="selected">Loading...</option>
              </select>
              
            </span>
            <span class="field-items">
              <label id="amount-label" for="itemcount">10</label>
              <input name="itemcount" class="itemcount" id="amount" type="hidden" />
              <div id="slider-item" class="slider"></div>
            </span>
            <span class="field-color">
              <!-- <label for="color">c</label> -->
              <ul class="color-options">
                <li><a id="color-0">1</a></li>
                <li><a id="color-1">2</a></li>
                <li><a id="color-2">3</a></li>
                <li><a id="color-3">4</a></li>
              </ul>
              <input id="color" name="color" class="color" value="f21b3f" type="hidden" />
              <div id="slider-color" class="slider"></div>
              <!-- <img src="i/icon-color.png" width="30" height="32" alt="Icon Color" /> -->
            </span>
          </fieldset>
        </form>
        <div id="actions">
          <p><a id="make-btn" href="#">Make</a></p>
          <ul>
            <li class="share-twitter"><a href="#">Twitter</a></li>
            <li class="share-facebook"><a href="#">Facebook</a></li>
            <li class="share-vine"><a href="#">Vine</a></li>
          </ul>          
        </div>
      </section>
    </header>
    <img id="instructions" src="i/instructions.png" width="990" height="400" alt="Instructions" />
    <canvas id="imageHolder" width="300" height="300" style="display: none"></canvas>
  </body>
  <script src="js/libs/jquery-1.9.1.js"></script>
  <script src="js/libs/jquery.tmpl.min.js"></script>
  <script src="js/libs/jquery.json-2.3.min.js"></script>
  <script src="js/libs/validate.min.js"></script>
  <script src="js/libs/d3.min.js"></script>
	<script src="js/libs/jquery-ui-1.10.2.custom.js"></script>
	<script src="js/libs/touchpunch.js"></script>
	<script src="js/libs/jquery.debounce-1.0.5.js"></script>
  <script src="js/instagram.js"></script>
  <script src="js/extractcolor.js"></script>
  <script src="js/main.js"></script>
</html>