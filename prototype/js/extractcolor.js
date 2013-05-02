var ctx; 
//var ptx = document.getElementById('palette').getContext('2d'); 
var img;
var ellaborateTemporaryColorArray = [];
// var info = document.getElementById('info');

function parseImg(url, callback){
	
	var returnColors = [];

	var colors = new Array();
	img = new Image();
	
	ctx = document.getElementById('imageHolder').getContext('2d');
	
	img.src = "php/getImage.php?" + url;
	ctx.width = 1000;
	ctx.height = 1000;
	ctx.clearRect(0,0,1000,1000);
	
	img.onload = function(){
		// console.log("hi");
		ctx.width = img.width;
		ctx.height = img.height;
		// once loaded, plop the image on the canvas
		ctx.drawImage( img, 0, 0 );
		// load the image data into an array of pixel data (R, G, B, a)
		var imgd = ctx.getImageData( 0, 0, $('#imageHolder').width(), $('#imageHolder').height() );
		var pix = imgd.data;
		// build an array of each color value and respective number of pixels using it
		for( var i = 0; i < pix.length; i+=4 ){
			var colorString = pix[i] + ',' + pix[i+1] + ',' + pix[i+2] + ',' + pix[i+3];
			if( colors[colorString] ){
				colors[colorString]++;
			}else{
				colors[colorString] = 1;
			}
		}
		// sort the array with greatest count at top
		var sortedColors = sortAssoc(colors);
		var ctr = 0;
		var devVal = 30;
		var usedColors = []; // used to see if close color is already seen
		var isValid = false;
	
		// create a lovely palette of the 5 most used colors
		for( var clr in sortedColors ){
			// weed out colors close to those already seen
			var colorValues = clr.split(',');
			var hexVal = '';
			for( var i = 0; i < 3; i++ ){
				hexVal += decimalToHex(colorValues[i], 2);
			}
			isValid = true;
			for( var usedColor in usedColors ){
				var colorDevTtl = 0;
				var rgbaVals = usedColors[usedColor].split(',');
				for( var i = 0; i <= 3; i++ ){
					colorDevTtl += Math.abs( colorValues[i] - rgbaVals[i] );
				}
				
				if( colorDevTtl / 4 < devVal){
					isValid = false;
					break;
				}
			}
			// make this better to select decent colors
			if(hexVal === "000000"){
				isValid = false;
				// console.log("black!");
			}
			if( isValid ){
				var whtTxtStyle = '';
				if( hexVal == 'ffffff' ){ whtTxtStyle = '; color: #666'; }
				returnColors.push(hexVal);
				usedColors.push( clr );
				ctr++;
			}
			if( ctr > 10 ){
				break;
			}
			
		}

		callback(returnColors);
				
	}
	
	ctx.width = 300;
	ctx.height = 300;
}

// ref: http://bytes.com/topic/javascript/answers/153019-sorting-associative-array-keys-based-values
function sortAssoc(aInput){
	var aTemp = [];
	for (var sKey in aInput)
		aTemp.push([sKey, aInput[sKey]]);
		aTemp.sort(function () {return arguments[1][1] - arguments[0][1]}
	);

	var aOutput = [];
	for (var nIndex = 0; nIndex < aTemp.length; nIndex++){
		aOutput[aTemp[nIndex][0]] = aTemp[nIndex][1];
	}
	return aOutput;
}

// ref: http://stackoverflow.com/questions/57803/how-to-convert-decimal-to-hex-in-javascript
function decimalToHex(d, padding) {
    var hex = Number(d).toString(16);
    padding = typeof (padding) === "undefined" || padding === null ? padding = 2 : padding;

    while (hex.length < padding) {
        hex = "0" + hex;
    }

    return hex;
}