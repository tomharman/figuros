<?php

  $currentNav = 'dashboard';
  include_once('includes/header.php');

  /**********************************************************************
  *  ezSQL initialisation for mySQL
  */

  // Include ezSQL core
  include_once "../php/shared/ez_sql_core.php";

  // Include ezSQL database specific component
  include_once "../php/ez_sql_mysql.php";

  // Initialise database object and establish a connection
  // at the same time - db_user / db_password / db_name / db_host
	$db = new ezSQL_mysql($DB_USERNAME,$DB_PASSWORD,$DB_DATABASE,$DB_HOST);

  /**********************************************************************/

  
  $earliestDate = strtotime("2012-04-13");

  $unprocessedOrders = $db->get_var("SELECT count(*) FROM customerOrders WHERE order_status = 'unprocessed'");
  $totalOrders = $db->get_var("SELECT count(*) FROM customerOrders");
  $age = floor((time() - $earliestDate)/(60*60*24));
  
    
  function busiestDay($db, $age){
    
    $selectedDate = date("Y-m-d", time());
    $busiestCount = 0;
    $busiestDate = 0;
    
    for ($i = 1; $i <= $age; $i++) {
      $count = $db->get_var("SELECT COUNT(*) FROM customerOrders WHERE date > \"$selectedDate 00:00:00\" AND date < \"$selectedDate 23:59:59\"");
      //echo $selectedDate . ":" . $count . "<br />";
      if ($count > $busiestCount) {
        $busiestDate = $selectedDate;
        $busiestCount = $count;
      }
      $selectedDate = date("Y-m-d", time() - (86400*$i));
    }
    
    echo "<em>" . $busiestCount. "</em><small>orders</small><br /> <span>".date("M d, Y", strtotime("$busiestDate")) ."</span>";
  }
  
  function makeChart($db){
    
    $selectedDate = date("Y-m-d", time());
    $previousMonth = 0;
    $spacingBetweenDays = 50;
    $numOfDays = 23;
    $monthCount = 1;

    
    echo "<ul class=\"barGraph\">";
    
    for ($i = 0; $i <= $numOfDays; $i++) {
      $count = $db->get_var("SELECT COUNT(*) FROM customerOrders WHERE date > \"$selectedDate 00:00:00\" AND date < \"$selectedDate 23:59:59\"");
      
      if($count>10){
        $height = 150; // prevent chart becoming unreadable        
      } else {
        $height = $count * 15;
      }
      
      $left = $i * $spacingBetweenDays;
      $monthFlag = false;
          
      echo "<li class=\"p1\" style=\"height: ".$height."px; right: ".$left."px;\"><span>".$count."</span></li><li class=\"label\" style=\"right: ".$left."px;\">";
      
      // Make number bold if on a weekend
      if(date("N", strtotime($selectedDate)) == 6 || date("N", strtotime($selectedDate)) == 7){
        echo "<strong>".date("d", strtotime($selectedDate))."</strong>";
      } else {
        echo date("d", strtotime($selectedDate));
      }
      
      echo "</li>";
      
      $selectedDate = date("Y-m-d", time() - (86400*($i+1)));
      
      if(date("m", strtotime("$selectedDate")) != $previousMonth){
        if($monthCount==1){
          echo "<li class=\"month\" style=\"left:0\">".strtoupper(date("M",mktime(0,0,0,date("m", strtotime("$selectedDate")),0,0)))."</li>";
        } else {
          echo "<li class=\"month\" style=\"right:".$left."\">".strtoupper(date("M",mktime(0,0,0,date("m", strtotime("$selectedDate"))+2,0,0)))."</li>";
        } 
        $previousMonth = date("m", strtotime("$selectedDate"));
        $monthCount++;
      }

    }
    
    echo "</ul>";
  }
  
?>
  <h1>Vital stats</h1>
  
  <?= makeChart($db); ?>
  
  <dl>
    <div>
      <dt>Unprocessed Orders</dt>
      <dd><em><? if($unprocessedOrders > 0) {?><a href="orders.php"><?= $unprocessedOrders ?></a><?} else {?>0<?}?></em></dd>
    </div>
    <div>
      <dt>Total Orders</dt>
      <dd><em><?= $totalOrders ?></em></dd>
    </div>
    <div>
      <dt>Average Orders</dt>
      <dd><em><?= round(($totalOrders/$age), 1) ?></em><br /><span>Per day</span</dd>
    </div>
    <div>
      <dt>Days Old</dt>
      <dd><em><?= $age ?></em><br /> <span>Since launching April 13, 2012</span></dd>
    </div>
    <div>
      <dt>Busiest Day</dt>
      <dd><?=busiestDay($db,$age);?></dd>
    </div>
  </dl>
  
<?

  // KPIs

  $numOfFridays = 15;
  $oneDay = 86400;
  $oneWeek = $oneDay*6;
  
  function fetchKPIData($sql, $format){
    global $numOfFridays, $oneDay, $oneWeek, $db;
    for ($i = $numOfFridays*7; $i >= 0; $i--) {
      if( date("w", time() - ($oneDay*($i+1)) ) == 5 ) {
        $selectedDateEnd = date("Y-m-d", time() - ($oneDay * ($i+1)));
        $selectedDateStart = date("Y-m-d", time() - ($oneDay * ($i+1) + $oneWeek));
        if($format == "giftCodes"){ 
          $query = $sql . "dateCreation > \"".$selectedDateStart." 00:00:00\" AND dateCreation < \"".$selectedDateEnd." 23:59:59\"";
        } else {
          $query = $sql . "date > \"".$selectedDateStart." 00:00:00\" AND date < \"".$selectedDateEnd." 23:59:59\"";
        }
        $count = $db->get_var($query);
        if($format == "dollars"){ 
          echo "<td>$".$count."</td>";
        } else {
          echo "<td>".$count."</td>";
        }
      }
    }
  }

?>  
  
  
  <h3>KPIs</h3>
  <table id="kpi">
    <thead>
      <tr>
        <th class="row-label">w/e</th>
        <? for ($i = $numOfFridays*7; $i >= 0; $i--) {
            if( date("w", time() - ($oneDay*($i+1)) ) == 5 ) {
              $selectedDateEnd = date("M d", time() - ($oneDay * ($i+1)));  ?>

              <th><?=$selectedDateEnd?></th>

        <?  }
          } ?>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td class="row-label">Gross Revenue</td>
        <? fetchKPIData("SELECT SUM(totalCost) FROM customerOrders WHERE ", "dollars"); ?>
      </tr>
      <tr>
        <td class="row-label">Net Revenue (Approx)</td>
        <?
        // A very basic net rev calculation excluding flash sales income, reduced cost bulk orders and packaging / sticker costs
        // ADD: Stripe charge, which is 2.9% + 30 cents of every total cost
        // ADD: Analytic API to grab visitor data
        // ADD: ZenDesk Customer Support tickets per week
        for ($i = $numOfFridays*7; $i >= 0; $i--) {
          if( date("w", time() - ($oneDay*($i+1)) ) == 5 ) {
            $selectedDateEnd = date("Y-m-d", time() - ($oneDay * ($i+1)));
            $selectedDateStart = date("Y-m-d", time() - ($oneDay * ($i+1) + $oneWeek));            
            $orderCount = $db->get_var("SELECT COUNT(*) FROM customerOrders WHERE date > \"".$selectedDateStart." 00:00:00\" AND date < \"".$selectedDateEnd." 23:59:59\"");
            $grossRevenue = $db->get_var("SELECT SUM(totalCost) FROM customerOrders WHERE date > \"".$selectedDateStart." 00:00:00\" AND date < \"".$selectedDateEnd." 23:59:59\"");
            $shipping = $db->get_var("SELECT SUM(shipping) FROM customerOrders WHERE date > \"".$selectedDateStart." 00:00:00\" AND date < \"".$selectedDateEnd." 23:59:59\"");
            $unitCost = 7.2;
            $netRevenue = $grossRevenue - ($orderCount*$unitCost) - $shipping;
            echo "<td>$".floor($netRevenue)."</td>";
          }
        }
        
        ?>
      </tr>
      <tr>
        <td class="row-label">Orders</td>
        <? fetchKPIData("SELECT COUNT(*) FROM customerOrders WHERE ", ""); ?>
      </tr>
      <tr>
        <td class="row-label">Giftcodes Purchased</td>
        <? fetchKPIData("SELECT COUNT(*) FROM giftCodes WHERE createdBy != 'Admin' AND ", "giftCodes"); ?>
      </tr>
      <tr>
        <td class="row-label">$31+ Giftcodes Redeemed</td>
        <? fetchKPIData("SELECT COUNT(*) FROM customerOrders WHERE discount >= 31 AND ", ""); ?>
      </tr>
      <tr>
        <td class="row-label">$25 Giftcodes Redeemed</td>
        <? fetchKPIData("SELECT COUNT(*) FROM customerOrders WHERE discount = 25 AND ", ""); ?>
      </tr>
      <tr>
        <td class="row-label">&lt; $25 Giftcodes Redeemed</td>
        <? fetchKPIData("SELECT COUNT(*) FROM customerOrders WHERE discount < 24 AND ", ""); ?>
      </tr>
      <tr>
        <td class="row-label">Countries</td>
        <? fetchKPIData("SELECT COUNT(DISTINCT country) FROM customerOrders WHERE ", ""); ?>
      </tr>
    </tbody>
  </table>

<?php

  include_once('includes/footer.php');

?>