<?

// Global vars + functions
$UPLOAD_DIR = 'uploads/';
$ROOT_URL = 'http://192.168.250.94/~me/figuros.local/';
$ROOT_URL = 'http://figuros.com/';

// Instagram
$IG_CLIENT_ID = "7fda2488dc134143a3b450be59f4dad7";
$IG_CLIENT_SECRET = "f82688dd5a98403197001340b2df9481";
$IG_WEBSITE_URI = "http://figuros.com/";
$IG_REDIRECT_URI = "http://dev.harmantom.com/figuros/generate.php";

$DB_USERNAME = "figuros";
$DB_PASSWORD = "q2FTUuk6";
$DB_DATABASE = "http://mysql.figuros.com";
$DB_HOST = "figuros";

function generateURL(){
  
  $chars = "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
  $res = "";
  $length = 4; // of URL

  for ($x = 0; $x < 1; $x++) {
    for ($i = 0; $i < $length; $i++) {
        $res .= $chars[mt_rand(0, strlen($chars)-1)];
    }
    $tempDir = $UPLOAD_DIR . $res . '/';
    // If folder already exists go around loop again
    if(file_exists($tempDir)){
      $i--;
    }
  }

  return $res;
}

?>