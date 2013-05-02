<?php
	//Report all errors
	ini_set("display_errors", "1");
	error_reporting(E_ALL); 

	//config
	include_once("config.php");
		
	/**********************************************************************
	*  ezSQL initialisation for mySQL
	*/

	// Include ezSQL core
	include_once "shared/ez_sql_core.php";

	// Include ezSQL database specific component
	include_once "ez_sql_mysql.php";

	// Initialise database object and establish a connection
	// at the same time - db_user / db_password / db_name / db_host
		
	$db = new ezSQL_mysql($DB_USERNAME,$DB_PASSWORD,$DB_DATABASE,$DB_HOST);
	
	$myjson = stripslashes($_REQUEST['data']);
  $shape = json_decode($myjson, true);
	
	$rawData = json_encode($shape['data'], true);
	$rawData = addslashes($rawData);


function generateURL(){
  
  global $db;
  
  $chars = "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
  $res = "";
  $length = 4; // of URL

  for ($x = 0; $x < 1; $x++) {
    for ($i = 0; $i < $length; $i++) {
        $res .= $chars[mt_rand(0, strlen($chars)-1)];
    }

    // check to see if this exists
    $urlCount = $db->get_var("SELECT COUNT(*) FROM shapes WHERE url = '". $res . "'");
     
    // if url already exists in database then loop around again
    if ($urlCount != 0){ $x--; }
  }

  return $res;
}


  // if there is a userB
  $db->query("INSERT INTO shapes (data, color, itemCount, userA, userB, url) VALUES ('".$rawData."','".$shape['color']."',".$shape['itemCount'].",'".$shape['userA']."','".$shape['userB']."','".generateURL()."')");

  // echo "added to db!";

  echo 1;

  // create confirmation + preview urls
  // $confirmation_url = "$ROOT_URL/confirmation/?id=$public_id";
  // $preview_url = "$ROOT_URL/preview/?id=$public_id";
  // 
  // //return confirmation_url
  // logToFile("orderLogs/$id.txt", $confirmation_url);
  // echo $confirmation_url;



	/**********************************************************************/


  // function createOrder($order){
  //  global $db, $ROOT_URL;
  //  
  //  $fname = $order['delivery']['fname'];
  //  $lname = $order['delivery']['lname'];
  //  $email = $order['delivery']['email'];
  //  $phone = $order['delivery']['phone'];
  //  $address = $order['delivery']['address'];
  //  $address2 = $order['delivery']['address2'];
  //  $city = $order['delivery']['city'];
  //  $state = $order['delivery']['state'];
  //  $zip = $order['delivery']['zip'];
  //  $country = $order['delivery']['country'];
  //  
  //  $coasterPrice = $order['coasterCost']['baseCost'];
  //  $shipping = $order['coasterCost']['shipping'];
  //  $gcode= $order['coasterCost']['giftCode'];
  //  $discount = $order['coasterCost']['discount'];
  //  $totalCost = $order['coasterCost']['totalPrice'];
  //  $originalCost = $order['coasterCost']['baseCost'] + $order['coasterCost']['shipping'];
  //    
  //  $image_1 = $order['images'][0]['standard_resolution']['url'];
  //  $image_2 = $order['images'][1]['standard_resolution']['url'];
  //  $image_3 = $order['images'][2]['standard_resolution']['url'];
  //  $image_4 = $order['images'][3]['standard_resolution']['url'];
  //  
  //  $thumbnail_1 = $order['images'][0]['thumbnail']['url'];
  //  $thumbnail_2 = $order['images'][1]['thumbnail']['url'];
  //  $thumbnail_3 = $order['images'][2]['thumbnail']['url'];
  //  $thumbnail_4 = $order['images'][3]['thumbnail']['url'];
  //  
  //  $ig_username = $order['userInfo']['username'];
  //  $ig_id = $order['userInfo']['id'];  
  //  
  //  $ig_auth = $order['access_token'];
  // 
  //  $stripeToken = $order['delivery']['stripeToken'];
  //  
  //  if ($stripeToken){
  //    // ask stripe for a customer id, cause we'll charge them when we send their order to Coasterworks
  //    $customer = Stripe_Customer::create(array(
  //    "card" => $stripeToken,
  //    "description" => $email)
  //    );
  //    $stripe_id = $customer->id;
  //  } else{
  //    $stripe_id = "";
  //  }
  //  
  //  $db->query("INSERT INTO customerOrders (id, fname, lname, email, phone, address, address2, city, state, zip, country, image_1, image_2, image_3, image_4, thumbnail_1, thumbnail_2, thumbnail_3, thumbnail_4, stripe_id, ig_auth, ig_username, ig_id, coasterPrice, shipping, giftCode, discount, totalCost, originalCost) VALUES (NULL, '".$fname."','".$lname."','".$email."','".$phone."','".$address."','".$address2."','".$city."','".$state."','".$zip."','".$country."','".$image_1."','".$image_2."','".$image_3."','".$image_4."','".$thumbnail_1."','".$thumbnail_2."','".$thumbnail_3."','".$thumbnail_4."','".$stripe_id."','".$ig_auth."','".$ig_username."','".$ig_id."','".$coasterPrice."','".$shipping."','".$gcode."','".$discount."','".$totalCost."','".$originalCost."')");
  // 
  //  // create public_id
  //  $id = $db->insert_id;
  //  $public_id = rand(0, 100000000);
  //  $db->query("INSERT INTO publicids (id, public_id) VALUES ($id, $public_id)");
  //  
  //  // create confirmation + preview urls
  //  $confirmation_url = "$ROOT_URL/confirmation/?id=$public_id";
  //  $preview_url = "$ROOT_URL/preview/?id=$public_id";
  // 
  //  
  //  //return confirmation_url
  //  echo $confirmation_url;
  // }
  // 
  // 
  // //--------- pull in json - parse into variables
  // //$order = json_decode($_REQUEST['data'], true);
  // 
  // $myjson = stripslashes($_REQUEST['data']);
  // $order = json_decode($myjson, true);
  // 
  // $gcode = $order['coasterCost']['giftCode'];
  // 
  // //if giftcode, check gift code, update giftcode db
  // if ($gcode) {
  //  $discount = $order['coasterCost']['discount'];
  //  $totalCost = $order['coasterCost']['totalPrice'];
  //  $coasterPrice = $order['coasterCost']['baseCost'];
  //  $shipping = $order['coasterCost']['shipping'];
  //  $originalCost = $coasterPrice + $shipping;
  //  
  //  $codeInfo = $db->get_row("SELECT * FROM `giftCodes` WHERE giftCode ='". $gcode . "'");
  // 
  //  $gValueDB = $codeInfo->valueCurrent;
  //  
  //  
  //  //if discount provided + value in db are equal, process the order
  //  if ($gValueDB = $order['coasterCost']['discount']){
  //    createOrder($order);
  //    
  //    $multiUse = $codeInfo->multiUse;
  //    $useCount = $codeInfo->useCount;
  //    
  //    if ($multiUse){
  //      //update use count
  //      $useCount = $useCount + 1;
  //          
  //      //update gcode in db    
  //      $db->query("UPDATE `giftCodes` SET useCount = $useCount, dateLastUsed = CURDATE() WHERE giftCode ='". $gcode . "'");
  //      
  //    } else { //its a normal giftcode
  //      
  //      //update gcode value
  //      if ($totalCost < 0){
  //        $newValue = Math.abs($totalCost); 
  //      } else {
  //        $newValue = 0;
  //      }
  //      //update gcode in db    
  //      $db->query("UPDATE `giftCodes` SET valueCurrent = $newValue, dateLastUsed = CURDATE() WHERE giftCode ='". $gcode . "'");
  // 
  //    }
  //    
  //  } else {
  //    echo "invalid gift code";
  //  }
  // 
  // // else, process order
  // } else {
  //  createOrder($order);
  // }


	
	?>