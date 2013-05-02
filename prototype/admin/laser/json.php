<?php

$currentNav = 'orders';
include_once('../../php/config.php');
include_once "../../php/shared/ez_sql_core.php";
include_once "../../php/ez_sql_mysql.php";

$db = new ezSQL_mysql($DB_USERNAME,$DB_PASSWORD,$DB_DATABASE,$DB_HOST);
$shape = $db->get_row("SELECT * FROM shapes WHERE url = '".$_GET['id']."'");

header("Content-Type: application/json");
echo $shape->data;
?>