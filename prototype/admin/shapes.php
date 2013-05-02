<?php
	
	$currentNav = 'orders';
	include_once('includes/header.php');
	include_once('../php/config.php');
	
	include_once "../php/shared/ez_sql_core.php";
	include_once "../php/ez_sql_mysql.php";

  $db = new ezSQL_mysql($DB_USERNAME,$DB_PASSWORD,$DB_DATABASE,$DB_HOST);
  
  $shapes = $db->get_results("SELECT * FROM shapes ORDER BY time DESC");

?>
    
  <h1><?=count($shapes)?> Shapes</h1>
    
  <? if (count($shapes) > 0) { ?>
    <table>
      <thead>
        <tr>
          <th>ID</th>
          <th class="col-date">Date</th>
          <th class="col-time">&nbsp;</th>
          <th class="col-color">Color</th>
          <th class="col-count">Count</th>
          <th class="col-userA">User A</th>
          <th class="col-userB">User B</th>
          <th class="col-url">URL</th>
          <th class="col-url">&nbsp;</th>
          <th class="col-data">Data</th>
        </tr>
      </thead>
      <tbody>
        <? foreach ( $shapes as $shape ) { ?>
          <tr>
            <td><?= $shape->id ?></td>
            <td><?= date_format(date_create($shape->time), 'M d') ?></td>
            <td><span><?= date_format(date_create($shape->time), 'G:i') ?></span></td>
            <td><em style="background: #<?=$shape->color?>"><?= $shape->color ?></em></td>
            <td class="row-count"><?= $shape->itemCount ?></td>
            <td><a href="http://instagram.com/<?= $shape->userA ?>">@<?= $shape->userA ?></a></td>
            <td><a href="http://instagram.com/<?= $shape->userB ?>">@<?= $shape->userB ?></a></td>
            <td><?= $shape->url ?></td>
            <td><a href="laser/index.php?id=<?= $shape->url ?>">Lasers!</a></td>
            <td class="row-data"><span><?= $shape->data ?></span></td>
          </tr>
        <? } ?>
      </tbody>
    </table>
  <? } ?>

<?

  include_once('includes/footer.php');

?>