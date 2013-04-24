/* SETUP VARIABLES ************************************************/

// Set the default
var access_token;
var dataset;
var userInput = {};
var g; // main g element
var revisedItemCount;

// Canvas
var w = $(window).width();
var h = 800;
var svg, xScale, yScale, turnAngle;
var chartLabelWidth = 100;
var shapeLayer, linesLayer;
var lines;

window.onresize = function(event) {
	w = $(window).width();
}

// Animation speeds
var animationSpeed = 500; // speed of each bar animation
var animationPause = 1000; // pause after shape is formed
//var sr = 500; // speed of transition to radial shape
//var sp = 2000; // speed of pause between transition

/* SETUP FUNCTIONS ************************************************/

// Grab user values from textfields
function loadUserValues(){
	
	var itemCount;
	
	// Make sure itemCount is within range
	if($('.itemcount').val() < 3) { itemCount = 3; }
	else if($('.itemcount').val() > 40) { itemCount = 40; }
	else { itemCount = $('.itemcount').val(); }
	
	userInput = {
						  "itemCount": itemCount,
						  "date": $('.date').val(),
						  "color": $('.color').val()};
	
}

function prepIGData(unpreparedData, callback){
	// used for slicing igData by userItemCount
	var firstTime = true;
	var startFrom = 0;
	var tempData = []; // transitioning from igData to dataset
	revisedItemCount = userInput.itemCount;
	
	// chop everything from the most recent date we're starting from.
	tempData = unpreparedData;
	
	// console.log(unpreparedData);
		
	if (tempData.length <= revisedItemCount) {
		revisedItemCount = tempData.length;
	}
	
	// loop through all igData
	for(i = 0; i < tempData.length; i++){
		
			// if we're at the number of items user wants, remove all remaining items from the array
			if(i >= revisedItemCount && firstTime === true){
				tempData = tempData.slice(0, i);
				firstTime = false;
			}
		
			// if we haven't reached the number of items the user wants keep adding data to stack.
			// NOTE: should this be adding to dataset instead of igData?
			if (i<revisedItemCount) {
				// var m = getDistanceFromLatLonInMiles(tempData[i].lat, tempData[i].lon, userInput.latlon[0], userInput.latlon[1]);
				
				tempData[i].value = tempData[i].dist.toFixed(1);
				tempData[i].name = tempData[i].human_time;
				tempData[i].location = tempData[i].username + " @ " + tempData[i].location;
			}
	}
	
	// console.log(tempData);
	dataset = tempData;
	callback();
}

function fadeOut(selection){
	return selection
		.transition()
		.duration(5000)
		.style("opacity",1)
		.transition()
		.duration(300)
		.style("opacity",0);
}

function drawShape(){

	for(i = 0; i < revisedItemCount; i++){
		var length = yScale(dataset[i].value);
		var x = (Math.cos(turnAngle * i) * length)+w/2;
		var y = (Math.sin(turnAngle * i) * length)+h/2;
		dataset[i].x = x;
		dataset[i].y = y;
	};
	
	var string = "";
	for(i = 0; i < revisedItemCount; i++){
		if(i<=0) { string += "M"; } else { string += "L"; }
		string += dataset[i].x+","+dataset[i].y;
	};
	string += "Z";

	shapeLayer = svg.append("g")
		.attr("transform", "rotate(90)translate("+-w/2+","+-h/2+")")
		.attr("id", "linesLayer");
	
	shapeLayer.append("path")
		.attr("d", string)
		.attr("stroke-width", 1)
		.attr("fill", "none")
	  .attr("stroke", "#"+ userInput.color)
		.style("opacity",0)
		.transition()
		.duration((animationSpeed*revisedItemCount)+animationPause)
		.style("opacity",0)
		.transition()
		.duration(animationPause)
		.style("opacity",1);
	
};

function clearShape(){
	svg.selectAll("g").remove();
	svg.selectAll("#origin").remove();
}

// Main functions
// initialise
// transition (a function that has no side-effects) make this just a way to show when data is updated. if there is no change in the data, the transition should have no visual effect

// Draw the actual chart
function init(){
	
	//Create SVG element, define the middle of the canvas as the origin
	svg = d3.select("body")
				.append("svg")
				.attr("width", w)
				.attr("height", h)
				.append("g")
		    .attr("transform", "translate(" + w / 2 + "," + h/2 + ")rotate(180)");

}

function setScales(){
	
	// Scale
	// xScale = d3.scale.linear()
	// 	                     .domain([0, revisedItemCount-1])
	// 	                     .range([(w/revisedItemCount)/2, w-(w/revisedItemCount/2)]);
	
	// console.log(dataset);
	var dMax = d3.max(dataset, function(d) { return d.dist; });
	var dMin = d3.min(dataset, function(d) { return d.dist; });
	
	yScale = d3.scale.linear()
             .domain([dMin, dMax])
             .range([h/8, h/2.4]);

}

function update(data) {

	// console.log("update called");

	// clear canvas if shapes already there
	clearShape();
	loadUserValues();
	prepIGData(data, setScales);

	// draw origin
	svg.append("circle")
		.attr("cx", 0)
		.attr("cy", 0)
		.attr("r", 3)
		.attr("id","origin")
		.attr("fill", function(){ return "#"+ userInput.color;});

	// Calculate turn angle	
	turnAngle = 2*Math.PI/revisedItemCount;
			
	drawShape();
	
  // One cell for each element
	linesLayer = svg.append("g").attr("id", "linesLayer");
	lines = linesLayer.selectAll("g.line")
		.data(dataset).enter()
    .append("g")
		.attr("class","line")
		.attr("transform", function(d, i) { return "rotate("+(i*turnAngle*(360/(2*Math.PI)))+")"; })
		.each(function(d,i) {
			
			d.components = {};
		
			d.components.rect = d3.select(this).append("rect")
			  .attr("y", 0)
			  .attr("width", 1)
			  .attr("fill", "#"+ userInput.color)
			  .attr("height", 0);
			
			d.components.labels = d3.select(this).append("g")
				.attr("class", "labels")
				.attr("transform", function() { 
					if(i < revisedItemCount/2) {
						var dist = yScale(d.value) + 5;
						return "rotate(90)translate("+dist+",-3)";
					} else {
						var dist = yScale(d.value) + 5;
						dist = -dist;
						return "rotate(270)translate("+dist+",-3)";
					}
				});

			d.components.labels.title = d3.select(this).select(".labels")
				.append("text")
				.attr("class","title")
				.attr("text-anchor", function(){
					if(i < revisedItemCount/2) {
						return "start";
					} else {
						return "end";
					};
				})
				.text(function(d) { 
					return d.name + " – " + d.value+" m";
				});
				
			d.components.labels.detail = d3.select(this).select(".labels")
				.append("text")
				.attr("text-anchor", function(){
					if(i < revisedItemCount/2) {
						return "start";
					} else {
						return "end";
					}
				})
				.text(function() {
					return d.location;
				})
				.attr("y", 12);

			// animation
			function fadeOut(selection) {
			  return selection
			      .style("opacity", 1)
			      .style("display", "block")
				    .transition()
						.duration(50)
			      .style("opacity", 0);
			}
			
			d.components.rect
				.transition()
			  .delay(i * animationSpeed)
				.duration(animationSpeed)
				.attr("height", yScale(d.value));
			
			d.components.labels.title
				.style("opacity",0)
				.transition()	
				.delay(i * animationSpeed + animationSpeed)
				.style("opacity",1);
			
			d.components.labels.detail
				.style("opacity",0)
				.transition()	
				.delay(i * animationSpeed + animationSpeed)
				.style("opacity",1);
			
			d.components.rect
				.transition()
				.delay((revisedItemCount * animationSpeed) + animationPause)
				.call(fadeOut)
				
			d.components.labels
				.transition()
				.delay((revisedItemCount * animationSpeed) + animationPause)
				.call(fadeOut)

		});
		
		d3.selectAll("#origin")
			.transition()
			.delay((animationSpeed*revisedItemCount)+animationPause)
			.remove();
}

var hexDigits = new Array
        ("0","1","2","3","4","5","6","7","8","9","a","b","c","d","e","f"); 

//Function to convert hex format to a rgb color
function rgb2hex(rgb) {
 rgb = rgb.match(/^rgb\((\d+),\s*(\d+),\s*(\d+)\)$/);
 return "#" + hex(rgb[1]) + hex(rgb[2]) + hex(rgb[3]);
}

function hex(x) {
  return isNaN(x) ? "00" : hexDigits[(x - x % 16) / 16] + hexDigits[x % 16];
 }

function setupColors(colors){
	var numOfColors = 4;
	
	if(colors.length >= numOfColors){
		for(i=0;i<4;i++){
			if(i===0){
				$('#color-'+i).css("background-color", "#" + colors[i]).css("border", "#fff solid 1px");
				$('#color').val(colors[i]);
			} else {
				$('#color-'+i).css("background-color", "#" + colors[i]).css("border", "#b6142f solid 1px");
			}
		}
	} else {
		alert('less than 2 colors in this image');
	}
}


// ------------------ Summary screen + submit to coaster-base  -----------------------

		$('#make-btn').click(function(){
			event.preventDefault();
			
			//post to the database!!
			// $(this).attr("disabled", "disabled").html("Loading...");
			console.log("make has been pressed");
			console.log(combinedData);
			
			var obj = { "color" : "#918", "text" : "hello" };
			
			// add color, username, etc
			
//			 var dataString = $.toJSON(obj);
			
			
//			var command = { type: "whatever" };

			$.ajax({
			    url:'php/dataInsert.php',
			    context:$(this),
			    type:'POST',
			    data: obj,
			    success: function(data){
			       command = {};
						 alert(data);
//			       window.location='php/dataInsert.php';
			    }
			});
			
			//			var dataString = JSON.stringify(combinedData);
			
			// var myJSONText = JSON.stringify(myObject, replacer);
			
			// $.post('php/dataInsert.php', {data: dataString}, function(response){	
			// 	if (response.indexOf("confirmation") > -1){
			// 		warning = false;
			// 		window.location = response;
			//  	} else {
			//  		alert("So sorry! Looks like there's been a problem.");
			// 		// window.location = $ROOT;
			// 	}
			// });
		});

/* MAKE MAGIC HAPPEN ************************************************/

$(function(){
	
	init();
	
	// initialize IG
	igInit();
	
	// Load new values if user updates select aka partner
	$('select').change(function(){
		// reset combined data
		combinedData = [];
		milesApart = [];
		igUpdate(function(){update(combinedData)});
	});
	
	$('.color-options li a').click(function(){
		var bgCol = $(this).css("background-color");
		$('.color-options li a').css("border-color", "#b6142f");
		$(this).css("border","#fff solid 1px");
		$('#color-output').css("background-color", bgCol);
		$('#color').val(rgb2hex(bgCol).substr(1));
		igUpdate(function(){update(combinedData)});
	});
	
	// jquery ui
	// CHANGE: max is hardcoded but should be set by length of dataset.
	$( "#slider-item" ).slider({
		value: 10,
	  min: 3,
	  max: 40,
	  step: 1,
	  slide: $.debounce(function( event, ui ) {
	    $( "#amount" ).val(ui.value);
		 	$( "#amount-label" ).text(ui.value);			
		 	combinedData = [];
			milesApart = [];
			igUpdate(function(){update(combinedData)});
	  }, 200)
	});
	$( "#amount" ).val( $( "#slider-item" ).slider( "value" ) );
	$('#slider').draggable();
	
});
