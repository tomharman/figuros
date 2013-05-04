<?

//config
include_once("prototype/php/config.php");
include_once("prototype/php/shared/ez_sql_core.php");
include_once("prototype/php/ez_sql_mysql.php");

// Turn off all error reporting
error_reporting(0);

$db = new ezSQL_mysql($DB_USERNAME,$DB_PASSWORD,$DB_DATABASE,$DB_HOST);

function check_email_address($email) {
  // First, we check that there's one @ symbol, 
  // and that the lengths are right.
  if (!ereg("^[^@]{1,64}@[^@]{1,255}$", $email)) {
    // Email invalid because wrong number of characters 
    // in one section or wrong number of @ symbols.
    return false;
  }
  // Split it into sections to make life easier
  $email_array = explode("@", $email);
  $local_array = explode(".", $email_array[0]);
  for ($i = 0; $i < sizeof($local_array); $i++) {
    if
(!ereg("^(([A-Za-z0-9!#$%&'*+/=?^_`{|}~-][A-Za-z0-9!#$%&
↪'*+/=?^_`{|}~\.-]{0,63})|(\"[^(\\|\")]{0,62}\"))$",
$local_array[$i])) {
      return false;
    }
  }
  // Check if domain is IP. If not, 
  // it should be valid domain name
  if (!ereg("^\[?[0-9\.]+\]?$", $email_array[1])) {
    $domain_array = explode(".", $email_array[1]);
    if (sizeof($domain_array) < 2) {
        return false; // Not enough parts to domain
    }
    for ($i = 0; $i < sizeof($domain_array); $i++) {
      if
(!ereg("^(([A-Za-z0-9][A-Za-z0-9-]{0,61}[A-Za-z0-9])|
↪([A-Za-z0-9]+))$",
$domain_array[$i])) {
        return false;
      }
    }
  }
  return true;
}

$msg = "";

// Post to db
if(isset($_POST["email"])){
  if(check_email_address($_POST["email"]) == true){
    $msg = $_POST["email"] . " added to our mailing list. Thanks, we'll be in touch soon!"; // good email

    // add email to database
    $db->query("INSERT INTO emails (email) VALUES ('".$_POST["email"]."')");
    
  } else {
    $msg = "That doesn't look like an email address. We didn't add it to our mailing list. Please try again.";
  }
}

?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <title>figuros – the light of your life</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <link rel="stylesheet" href="css/global.css" />
    <link rel="icon" type="image/png" href="i/favicon.png" />
    <link href='http://fonts.googleapis.com/css?family=Raleway:100,200,300,700&family=Lato:100italic,300italic' rel='stylesheet' type='text/css' />
      
    <!-- HTML5 shim, for IE6-8 support of HTML elements -->
    <!--[if lt IE 9]>
      <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->
  	<script type="text/javascript" src="js/jquery-1.9.1.js"></script>
  	<script type="text/javascript" src="js/jquery-scrollspy.js"></script>
  	
  </head>
  <body id="figuros">
    <? if($msg!=""){ ?><div class="notice"><?=$msg?></div><? } ?>
    <nav>
      <ol>
        <li id="nav-zero"><a href="#zero">zero</a></li>
        <li id="nav-one"><a href="#one">one</a></li>
        <li id="nav-two"><a href="#two">two</a></li>
        <li id="nav-three"><a href="#three">three</a></li>
        <li id="nav-four"><a href="#four">four</a></li>
        <li id="nav-five"><a href="#five">five</a></li>
        <li id="nav-six"><a href="#six">six</a></li>
        <li id="nav-seven"><a href="#seven">seven</a></li>
        <li id="nav-eight"><a href="#eight">eight</a></li>
      </ol>
    </nav>
    <section id="zero">
      <h1>Figuros</h1>
      <h2><span>Figuros</span> is not just a lamp.<br /> It’s your lamp.<br /> Based on your life.</h2>
    </section>
    <section id="one">
      <h2>Each one is different because every relationship is – with your friends, lovers and favourite celebrities.</h2>
    </section>
    <section id="two">
      <h2>It works through the Instagram accounts of you and someone you follow, creating a unique shape based on the locations of your last 20 photos*.</h2>
      <p>*In order to work, both of you must have "Add to your Photo Map" switched on in at least one of your last 20 photos.</p>
    </section>
    <section id="three">
      <h2>The closer you are physically to your partner, the spikier pattern you’ll get and the further apart you are, the smoother. It’s that simple.</h2>
    </section>
    <section id="four">
      <h2>Position them to show off the shape, the data, or the mystery.</h2>
    </section>
    <section id="five">
      <h2>The lamps are lasercut from wood, translucent Perspex and thick colored card, ready to take pride of place in your home – and your heart.</h2>
    </section>
    <section id="six">
      <h2>For most of us, clutter is something we’d rather cut out. Just look at the way iPods wiped out CD racks and Kindles gave cobwebs to bookshelves. It’s these technologies that are building a slicker, more personal story for each and every one of us – making the physical objects we use every day more meaningful to our lives.</h2>
    </section>
    <section id="seven">
      <h2>Figuros does just that. It was made to explore the way we consume possessions and make the ones we do, more meaningful. So don’t be surprised when your friends’ first response is, “it’s so you”.</h2>
    </section>
    <section id="eight">
      <h2>Like something you see?</h2>
      <div class="next">
        <h3>Be the first to know when we launch</h3>
        <form action="index.php" method="post">
          <fieldset>
            <label for="email">Enter your email</label>
            <input type="text" id="email" name="email" />
            <input type="submit" value="Submit" />
          </fieldset>
        </form>
      </div>
      <div class="next">
        <h3>Turn a relationship into a shape</h3>
        <p id="get-started-button"><a href="/prototype/">Play with the prototype</a></p>
      </div>
      <div class="next">
        <h3>Spare a tweet</h3>
        <ul>
          <li><a href="http://www.twitter.com/home?status=The%20Light%20of%20Your%20Life.%20Designed%20with%20data.%20Made%20with%20lasers%3A%20http%3A%2F%2Ffiguros.com%20%40figuros">“The Light of Your Life. Designed with data. Made with lasers: figuros.com @figuros”</a></li>
          <li><a href="http://www.twitter.com/home?status=.%40figuros%20are%20custom%20laser%20cut%20lamps%20created%20from%20the%20distance%20between%20two%20people%20over%20time%3A%20figuros.com">“.@figuros are custom laser cut lamps created from the distance between two people over time: figuros.com”</a></li>
        </ul>
      </div>
    </section>
    <footer>
      <ul>
        <li><a href="mailto:hi@figuros.com">hi@figuros.com</a></li>
        <li><a href="http://twitter.com/figuros">@figuros</a></li>
      </ul>
    </footer>
    <script type="text/javascript">
     
    $(document).ready(function() {
  			$('section').each(function(i) {
  				var position = $(this).position();
  				// console.log(position);
  				//          console.log('min: ' + position.top + ' / max: ' + parseInt(position.top + $(this).height()));
  				$(this).scrollspy({
  					min: position.top - 25,
  					max: position.top + $(this).height() - 1,
  					onEnter: function(element, position) {
              // if(console) console.log('entering ' +  element.id);
  						$("#nav-"+element.id).addClass('current');
  					},
  					onLeave: function(element, position) {
  					  // if(console) console.log('leaving ' +  element.id);
  					  $("#nav-"+element.id).removeClass('current');
  					}
  				});
  			});
  		});
  		
  		$("li#nav-zero a").click(function() {
          event.preventDefault();
           $('html, body').animate({
               scrollTop: $("section#zero").offset().top -25
           }, 500);
       });
       
        $("li#nav-one a").click(function() {
          event.preventDefault();
          $('html, body').animate({
            scrollTop: $("section#one").offset().top - 25
          }, 500);
        });
        $("li#nav-two a").click(function() {
          event.preventDefault();
          $('html, body').animate({
            scrollTop: $("section#two").offset().top - 25
          }, 500);
        });
        $("li#nav-three a").click(function() {
          event.preventDefault();
          $('html, body').animate({
            scrollTop: $("section#three").offset().top - 25
          }, 500);
        });
        $("li#nav-four a").click(function() {
          event.preventDefault();
          $('html, body').animate({
            scrollTop: $("section#four").offset().top - 25
          }, 500);
        });
        $("li#nav-five a").click(function() {
          event.preventDefault();
          $('html, body').animate({
            scrollTop: $("section#five").offset().top - 25
          }, 500);
        });
        $("li#nav-six a").click(function() {
          event.preventDefault();
          $('html, body').animate({
            scrollTop: $("section#six").offset().top - 25
          }, 500);
        });
        $("li#nav-seven a").click(function() {
          event.preventDefault();
          $('html, body').animate({
            scrollTop: $("section#seven").offset().top - 25
          }, 500);
        });
        $("li#nav-eight a").click(function() {
          event.preventDefault();
          $('html, body').animate({
            scrollTop: $("section#eight").offset().top - 25
          }, 500);
        });
        
  	</script>
  </body>
</html>