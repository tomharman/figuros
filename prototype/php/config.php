<?

date_default_timezone_set('America/New_York');

// Global vars + functions
$UPLOAD_DIR = 'uploads/';
// $ROOT_URL = 'http://192.168.250.94/~me/figuros.local/';
// $ROOT_URL = 'http://figuros.com/';

$DB_USERNAME = $_SERVER['DB_USERNAME'];
$DB_PASSWORD = $_SERVER['DB_PASSWORD'];
$DB_DATABASE = $_SERVER['DB_DATABASE'];
$DB_HOST = $_SERVER['DB_HOST'];

$ADMIN_PATH = "http://" . $_SERVER['SERVER_NAME'] . "/prototype/admin";
$ADMIN_USER = "figuros";
$ADMIN_PASSWORD = "8f0ea67be2b15227fe291c1c04b59c73b95fd6fcca42659025e9cec051ded3d7"; // SHA-256(password.salt)
$ADMIN_SALT = "68b1282b91de2c054c36629cb8dd447f12f096d3e3c587978dc2248444633483";

?>