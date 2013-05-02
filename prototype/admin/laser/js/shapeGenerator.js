var rotateBy = function(a) {
  return function(d) {
    var x = d.x;
    var y = d.y;

    // Subtract midpoints, so that midpoint is translated to origin
    // and add it in the end again
    point = {}
    point.x = x*Math.cos(a) - y*Math.sin(a);
    point.y = x*Math.sin(a) + y*Math.cos(a);

    return point;
  }
}

var generateATab = function(l) {
  var margin = 14.364; // Tab Margin from side.
  var thickness = 1.13;
  var overhang = 1.22;
  var height = 5.63;
  var width = 9;

  if(!l) {
    return [
      { x:0, y:0},
      { x:margin, y:0},
      { x:margin, y:-thickness},
      { x:margin - overhang, y: -thickness},
      { x:margin,y: -height},
      { x:margin + width, y: -height},
      { x:margin + width + overhang, y: -thickness},
      { x:margin + width, y: -thickness},
      { x:margin + width, y:0}
    ]  
  } else {
    return [
      { x:l - margin - width, y:0},
      { x:l - margin - width, y: -thickness},
      { x:l - margin - width - overhang, y: -thickness},
      { x:l - width - margin, y: -height},
      { x:l - margin,y: -height},
      { x:l - margin + overhang, y: -thickness},
      { x:l - margin, y: -thickness},
      { x:l - margin, y: 0},
      //{ x:l, y:0}
    ]
  }

}

var generateTabsBtwn = function(d1, d2) {
  var tab = generateATab();

  var dx = d1.x - d2.x;
  var dy = d1.y - d2.y;
  
  var dist = Math.sqrt(Math.pow(dx,2) + Math.pow(dy,2));
  var theta = Math.atan2(-dy, -dx);
  console.log("theta: "+theta/Math.PI*180);
  
  tab = tab.concat(generateATab(dist));

  // Rotate
  tab = _.map(tab, rotateBy(theta));

  // Translate
  tab = _.map(tab, function(d) {
    return { x: d.x + d1.x, y: d.y + d1.y }
  });

  return tab;
}

var tabbedPathGenerator = function(points) {
  if(points.length < 3) { return; }

  var list = [];

  _.each(points, function(p, i) {
    var d1 = p;
    var d2;
    
    if(i+1<points.length) {
      d2 = points[i+1];
    } else {
      d2 = points[0];
    }

    list = list.concat(generateTabsBtwn(d1, d2));
  });

  var string = "";
  _.each(list, function(d, i) {
    
    if(i<=0) { string += "M"; } else { string += "L"; }
    
    string += d.x+","+d.y;

  });
  string += "Z";

  return string;
  
}

wallPanel = function(l, h) {
  var paths = [];
  var points = [];
  
  if(!h) {
    h = 6*72;  
  }
  
  var margin = 9; // .125 inches
  var holeWidth = 9.72;
  var holeHeight = 6.74;
  var tabHeight = 7;
  var tabInset = 5;
  var topInset = 2;

  var flapHeight = 18;
  var flapInset = 6.74;
  var flapCutWidth = 8;
  var flapCutDepth = 4;

  var toSVGstring = function(points) {
    var string = "";
    _.each(points, function(d, i) {
      if(i<=0) { string += "M"; } else { string += "L"; }
      string += d.x+","+d.y;
    });
    return string;
  }

  points.push({ x: (h-flapCutWidth)/2, y: -flapHeight });

  points.push({ x: flapInset, y: -flapHeight });  

  points.push({ x: flapInset, y: 0 });

  points.push({ x: 0, y: 0 }); // Top Left of Wall

  // Tab 1
  tab1 = [
    { x: 0, y: margin },
    { x: -tabHeight, y: margin+tabInset-topInset },
    { x: -tabHeight, y: margin+tabInset+holeWidth+topInset },
    { x: 0, y: margin+2*tabInset+holeWidth}
  ];

  points = points.concat(tab1);

  // Hole 1
  hole1 = [
    { x: 0, y: margin+tabInset },
    { x: holeHeight, y: margin+tabInset },
    { x: holeHeight, y: margin+tabInset+holeWidth },
    { x: 0, y: margin+tabInset+holeWidth },
  ]

  string = toSVGstring(hole1);
  string += "Z";
  paths.push({
    path: string,
    color: "#000"
  });

  // Tab 2
  tab2 = [
    { x: 0, y: l-margin-2*tabInset-holeWidth },
    { x: -tabHeight, y: l-margin-tabInset-holeWidth-topInset },
    { x: -tabHeight, y: l-margin-tabInset+topInset },
    { x: 0, y: l-margin },    
  ];

  points = points.concat(tab2);

  // Hole 2
  hole2 = [
    { x: 0, y: l-margin-tabInset },
    { x: holeHeight, y: l-margin-tabInset },
    { x: holeHeight, y: l-margin-tabInset-holeWidth },
    { x: 0, y: l-margin-tabInset-holeWidth },
  ]

  string = toSVGstring(hole2);
  string += "Z";
  paths.push({
    path: string,
    color: "#000"
  });

  // Bottom Edge
  points.push({ x: 0, y: l});

  points.push({ x: h, y: l});

  // Tab 3
  tab3 = [
    { x: h, y: l-margin },    
    { x: h+tabHeight, y: l-margin-tabInset+topInset },
    { x: h+tabHeight, y: l-margin-tabInset-holeWidth-topInset },
    { x: h, y: l-margin-2*tabInset-holeWidth },
  ];

  points = points.concat(tab3);

  // Hole 3
  hole3 = [
    { x: h, y: l-margin-tabInset },
    { x: h-holeHeight, y: l-margin-tabInset },
    { x: h-holeHeight, y: l-margin-tabInset-holeWidth },
    { x: h, y: l-margin-tabInset-holeWidth },
  ]

  string = toSVGstring(hole3);
  string += "Z";
  paths.push({
    path: string,
    color: "#000"
  });

  // Tab 4
  tab4 = [
    { x: h, y: margin+2*tabInset+holeWidth},
    { x: h+tabHeight, y: margin+tabInset+holeWidth+topInset },
    { x: h+tabHeight, y: margin+tabInset-topInset },
    { x: h, y: margin },
  ];

  points = points.concat(tab4);

  // Hole 4
  hole4 = [
    { x: h, y: margin+tabInset },
    { x: h-holeHeight, y: margin+tabInset },
    { x: h-holeHeight, y: margin+tabInset+holeWidth },
    { x: h, y: margin+tabInset+holeWidth },
  ]

  string = toSVGstring(hole4);
  string += "Z";
  paths.push({
    path: string,
    color: "#000"
  });

  points.push({ x: h, y: 0});

  points.push({ x: h - flapInset, y: 0 });

  points.push({ x: h - flapInset, y: -flapHeight });

  points.push({ x: (h+flapCutWidth)/2, y: -flapHeight });

  string = toSVGstring(points);

  paths.push({ path : string, color : "#000000" });

  // Score Line
  scoreLine = [
    { x: flapInset, y: 0 },
    { x: h - flapInset, y: 0 }
  ]
  string = toSVGstring(scoreLine);
  paths.push({
    path : string,
    color : "#ff0000"
  })

  // Score Angle
  scoreAngle = [
    { x: (h-flapCutWidth)/2, y: -flapHeight },
    { x: h/2, y: -flapHeight+flapCutDepth },
    { x: (h+flapCutWidth)/2, y: -flapHeight }
  ]
  string = toSVGstring(scoreAngle);
  paths.push({
    path : string,
    color : "#ff0000"
  })

  return paths;
}


