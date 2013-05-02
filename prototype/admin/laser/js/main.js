var width = 1000,
    height = 10000;

var svg = d3.select("body").append("svg")
    .attr("width", width)
    .attr("height", height);

ready = function(err, data) {
  var max = _.max(data, function(d) {
    return d.value;
  });
  var min = _.min(data, function(d) {
    return d.value;
  })

  console.log("max: "+ max.value + " min: " + min.value);

  var lengthScale = d3.scale.linear()
    .domain([max.value, min.value])
    .range([630, 250]); // should be 630, 315.

  var turnAngle = 2*Math.PI/data.length;

  var cx = 500;
  var cy = 500;

  var thickness = 200;
  var wallx = width * 5 / 8;
  var wally = 50;
  
  _.each(data, function(d, i) {
    var length = lengthScale(d.value);
    var x = Math.cos(turnAngle * i) * length + cx;
    var y = Math.sin(turnAngle * i) * length + cy;

    console.log("a: "+turnAngle * i);
    console.log("("+x+","+y+")");

    // Dot markers
    // svg.append("circle")
    //   .attr("cx", x)
    //   .attr("cy", y)
    //   .attr("r", 2)
    //   .attr("fill", "#000");

    d.x = x;
    d.y = y;

  });

  var walls = _.map(data, function(d, i, list) {
    var pointA = d;
    var pointB;
    if(i<=0) {
      pointB = list[list.length - 1];
    } else {
      pointB = list[i-1];
    }

    return { length : Math.sqrt(Math.pow(pointA.x - pointB.x, 2) + Math.pow(pointA.y - pointB.y, 2)) };
  })

  var line = d3.svg.line()
    .x(function(d) { return d.x; })
    .y(function(d) { return d.y; })
    .interpolate("linear");

  // Draw
  // Top
  svg.append("path")
    .attr("d", tabbedPathGenerator(data))
    .attr("fill", "none")
    .attr("stroke", "#000")
    // .attr("stroke-width", 0.001);
		.attr("stroke-width", 1);


  // Walls
  

  console.log(walls);

  var thisTop = 1000;
  _.each(walls, function(w, i) {
    var height = 6*72;
    wallPaths = wallPanel(w.length, height);

    layer = svg.append("g");

    layer.append("text")
      .text(i)
      .attr("fill", "#ff0000")
      .attr("x", height/2)
      .attr("y", -3) 
      .attr("font-size", 9)
      .attr("text-anchor", "middle")


    layer.selectAll("path")
      .data(wallPaths)
      .enter()
    .append("path")
      .attr("d", function(d) {
        return d.path;
      })
      .attr("fill", "none")
      .attr("stroke", function(d) {
        return d.color;
      })
//      .attr("stroke-width", 0.001);
	.attr("stroke-width", 1);

    layer.attr("transform", "translate(500, "+thisTop+")");

    thisTop += w.length + 18 + 18; // this is a hack to say, add margin from w plus another quarter inch as space between wall pieces.
  })

  svg.attr("height", thisTop);

  /*
  svg.selectAll("rect")
    .data(walls)
    .enter()
    .append("rect")
    .attr("x", wallx)
    .attr("y", function(d) {
      value = thisTop;
      thisTop += d.length;
      return wally + value;
    })
    .attr("width", thickness)
    .attr("height", function(d) {
      return d.length;
    })
    .attr("fill", "none")
    .attr("stroke", "#000")
    .attr("stroke-width", 1);
  */



  svg.append("circle")
    .attr("cx", cx)
    .attr("cy", cy)
    .attr("r", 20)
    .attr("fill", "#ddd");

  data = $("svg").clone().wrap('<p>').parent().html();
  // add it to our localstorage
  localStorage.setItem('data', data);

  // encode the data into base64
  base64 = window.btoa(localStorage.getItem('data'));

  // create an a tag
  var a = $('<a>Save link as&hellip;</a>');
  a.attr("href", 'data:image/svg+xml;base64,' + base64);
  var p = $("<p>").append(a);
  $("body").prepend(p);
}

queue()
  .defer(d3.json, "../laser/json.php?id="+fromPHP)
  .await(ready);