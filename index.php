<!DOCTYPE html>
<html lang="en">
  <head>
    <title>figuros – the light of your life</title>
    <meta charset="utf-8" />
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
      <h2>Information going here soon.</h2>
    </section>
    <footer>
      <ul>
        <li><a href="#">hello@figuros.com</a></li>
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
  					min: position.top-25,
  					max: position.top + $(this).height(),
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
  	</script>
  </body>
</html>