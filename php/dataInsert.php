<?php


	ini_set("display_errors", "1");
	error_reporting(E_ALL); 
	include_once("global.php");
	include_once("shared/ez_sql_core.php");
	include_once("ez_sql_mysql.php");
	$db = new ezSQL_mysql($DB_USERNAME,$DB_PASSWORD,$DB_DATABASE,$DB_HOST);

  // generateURL();
  $testLabel = "something";
  
  function test(){
	  $db->query("INSERT INTO shapes (id, url) VALUES (NULL, '".$testLabel."')");
  }
  
  test();
  
	// function createRecord(){
	//     
	//     $db->query("INSERT INTO shapes (id, url) VALUES (NULL, '".test."')");
	//     
	  
		// global $db, $ROOT_URL;
		
    // print_r($data);
    // 
    // $fname = $order['delivery']['fname'];
    // $lname = $order['delivery']['lname'];
    // $email = $order['delivery']['email'];
    // $phone = $order['delivery']['phone'];
    // $address = $order['delivery']['address'];
    // $address2 = $order['delivery']['address2'];
    // $city = $order['delivery']['city'];
    // $state = $order['delivery']['state'];
    // $zip = $order['delivery']['zip'];
    // $country = $order['delivery']['country'];
    // 
    // $coasterPrice = $order['coasterCost']['baseCost'];
    // $shipping = $order['coasterCost']['shipping'];
    // $gcode= $order['coasterCost']['giftCode'];
    // $discount = $order['coasterCost']['discount'];
    // $totalCost = $order['coasterCost']['totalPrice'];
    // $originalCost = $order['coasterCost']['baseCost'] + $order['coasterCost']['shipping'];
    //  
    // $image_1 = $order['images'][0]['standard_resolution']['url'];
    // $image_2 = $order['images'][1]['standard_resolution']['url'];
    // $image_3 = $order['images'][2]['standard_resolution']['url'];
    // $image_4 = $order['images'][3]['standard_resolution']['url'];
    // 
    // $thumbnail_1 = $order['images'][0]['thumbnail']['url'];
    // $thumbnail_2 = $order['images'][1]['thumbnail']['url'];
    // $thumbnail_3 = $order['images'][2]['thumbnail']['url'];
    // $thumbnail_4 = $order['images'][3]['thumbnail']['url'];
    // 
    // $ig_username = $order['userInfo']['username'];
    // $ig_id = $order['userInfo']['id']; 
    // 
    // $ig_auth = $order['access_token']; 
    // 
    //  
    // $db->query("INSERT INTO customerOrders (id, fname, lname, email, phone, address, address2, city, state, zip, country, image_1, image_2, image_3, image_4, thumbnail_1, thumbnail_2, thumbnail_3, thumbnail_4, stripe_id, ig_auth, ig_username, ig_id, coasterPrice, shipping, giftCode, discount, totalCost, originalCost) VALUES (NULL, '".$fname."','".$lname."','".$email."','".$phone."','".$address."','".$address2."','".$city."','".$state."','".$zip."','".$country."','".$image_1."','".$image_2."','".$image_3."','".$image_4."','".$thumbnail_1."','".$thumbnail_2."','".$thumbnail_3."','".$thumbnail_4."','".$stripe_id."','".$ig_auth."','".$ig_username."','".$ig_id."','".$coasterPrice."','".$shipping."','".$gcode."','".$discount."','".$totalCost."','".$originalCost."')");
	
		// create public_id
    // $id = $db->insert_id;
    // $public_id = rand(0, 100000000);
    // $db->query("INSERT INTO publicids (id, public_id) VALUES ($id, $public_id)");
		
		// create confirmation + preview urls
    // $confirmation_url = "$ROOT_URL/confirmation/?id=$public_id";
    // $preview_url = "$ROOT_URL/preview/?id=$public_id";
	
		//return confirmation_url
		// echo $confirmation_url;
  // }

	//--------- pull in json - parse into variables
  // $data = json_decode($_REQUEST['data'], true);
  // echo "YAY";
  // 
  // $data = stripslashes($_REQUEST['data']);
  // $data = json_decode($myjson, true);
  // createRecord();

?>