// IG variables
var client_id = "7fda2488dc134143a3b450be59f4dad7";
var access_token;
var urlParams =  window.location.hash;
//split url params to get auth key
urlParams = urlParams.split("=");
access_token = urlParams[1];

var igCallback;

// instagramHours
var followData = [];
var userAData = [];
var userBData = [];
var combinedData = []; /* self + partner */
var colorURL;

function getDistanceFromLatLonInMiles(lat1,lon1,lat2,lon2) {
	function deg2rad(deg) {
	  return deg * (Math.PI/180)
	}
	
  var R = 6371; // Radius of the earth in km
  var dLat = deg2rad(lat2-lat1);  // deg2rad below
  var dLon = deg2rad(lon2-lon1); 
  var a = 
    Math.sin(dLat/2) * Math.sin(dLat/2) +
    Math.cos(deg2rad(lat1)) * Math.cos(deg2rad(lat2)) * 
    Math.sin(dLon/2) * Math.sin(dLon/2)
    ; 
  var c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a)); 
  var d = R * c; // Distance in km
  var d = d * 0.621371; // Convert into miles
  return d;
}

function getLoggedInDetails() {
	var param = {
   	access_token:access_token,
   	count:50
   	};
	var cmdURL = 'https://api.instagram.com/v1/users/self?callback=?';
	$.getJSON(cmdURL, param, onLoggedInDetails);
}

function onLoggedInDetails(data){
	// $("#logged-in-as").append("logged in as <img width=\"30\" src=\""+data.data.profile_picture+"\" /> "+data.data.username+" (<a href=\"http://instagram.com/accounts/logout/\">sign out</a>)");
	$('#self-avatar').attr('src', data.data.profile_picture);
	userA = data.data.username;
}

function getUserFeed(userid, fn){
	var cmdURL;
	var param = {
   	access_token:access_token,
   	count:20
   	};
	nowLoading = true;
	cmdURL = 'https://api.instagram.com/v1/users/'+userid+'/media/recent?callback=?';
	
	if(userid === "self"){
		$.getJSON(cmdURL, param, function(data) {
			onPhotoLoadedMerge(data);
			//if(typeof fn == "function") { fn(); }
		});
	} else if(userid === "colors") {
		cmdURL = 'https://api.instagram.com/v1/users/self/media/recent?callback=?';
		$.getJSON(cmdURL, param, function(data) {
			onPhotoLoadedColor(data);
			//if(typeof fn == "function") { fn(); }
		});
	} else {
		$.getJSON(cmdURL, param, function(data) {
			onPhotoLoaded(data);
			//if(typeof fn == "function") { fn(); }
		});
	}
}

// generate a list of the people this user follows
function getFollowList(cmdURL){
	if (cmdURL == 1) {
		var param = {
    	access_token:access_token,
    	count:50
    };
		cmdURL = "https://api.instagram.com/v1/users/self/follows?callback=?";
		$.getJSON(cmdURL, param, onFollowListLoaded);
	} else {
		$.getJSON(cmdURL+"&callback=?", onFollowListLoaded);
	}
}

function dynamicSort(property) {
    var sortOrder = 1;
    if(property[0] === "-") {
        sortOrder = -1;
        property = property.substr(1, property.length - 1);
    }
    return function (a,b) {
        var result = (a[property] < b[property]) ? -1 : (a[property] > b[property]) ? 1 : 0;
        return result * sortOrder;
    }
}

function onFollowListLoaded(data){
	if(data.meta.code == 200) {
		
		// add new data to our followData array
		followData = followData.concat(data.data);
		
		// if there are still more follows call function again
		if(data.pagination.next_url != null){
			getFollowList(data.pagination.next_url);
		} else {
			
			followData.sort(dynamicSort("username"));
			
			var $el = $("#partner");
			$el.empty(); // remove old options
			$el.append($("<option></option>").attr("value", 0).text("-- following "+followData.length+" people --"));
			
			for(i=0;i<followData.length;i++){
				$el.append($("<option></option>").attr("value", followData[i].id).text(followData[i].username));				
			}
			console.log("Instagram follow list loaded.");
		}
	} else {
		alert(data.meta.error_message);
	}
}
   
function outputToHTML(data){
	
	$("table").remove();
	
	var string = "<tr><td>#</td><td>Username</td><td>Location</td><td>Lat</td><td>Lon</td><td>Lat2</td><td>Lon2</td><td>Distance</td><td>Human Time</td><td>Unix Time</td><td>Caption</td></tr>";

	for(i=0;i<data.length;i++){				
		string += "<tr><td>" + i + "<img width=\"30\" src=\""+data[i].url+"\" /></td><td>@" + data[i].username + "</td><td>" + data[i].location + "</td><td>" + data[i].lat + "</td><td>" + data[i].lon + "</td><td>" + data[i].lat2 + "</td><td>" + data[i].lon2 + "</td><td>" + data[i].dist.toFixed(2) + "m</td><td>" + data[i].human_time + "</td><td>" +data[i].unix_time + "</td>";
		if (data[i].caption!=null) {
			string += "<td>"+data[i].caption+"</td>";
		} else {
			string += "&nbsp;";
		}
		string += "</tr>";
	}
	
	$("body").append("<table>"+string+"</table>");

}
	
function timeConverter(UNIX_timestamp){
	var a = new Date(UNIX_timestamp*1000);
	var months = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
  var month = months[a.getMonth()];
  var date = a.getDate();
  var time = month + " " + date;
  return time;
}

function onPhotoLoadedColor(data) {

	var randomPhotoColor = Math.floor((Math.random()*20)+1);
	
 	//if meta code = all good
  if(data.meta.code == 200) {

		colorURL = data.data[randomPhotoColor].images.thumbnail.url;
		parseImg(colorURL, setupColors);
    
	} else {
     alert(data.meta.error_message);
  }
}
	
function onPhotoLoaded(data) {

 	//if meta code = all good
  if(data.meta.code == 200) {

		// if theere are photos
		if(data.data.length > 0) {
		
			for(i=0;i<data.data.length;i++){
			
				userAData[i] = {};

				if (data.data[i].created_time!=null) {
					userAData[i].unix_time = data.data[i].created_time;
					userAData[i].human_time = timeConverter(data.data[i].created_time);
				}
			
				if (data.data[i].caption!=null) {
					userAData[i].caption = data.data[i].caption.text;
				}
			
				if (data.data[i].location!=null) {
					if (data.data[i].location.name!=null) {
						userAData[i].location = data.data[i].location.name;
					}
					if (data.data[i].location.latitude!=null) {
						userAData[i].lat = data.data[i].location.latitude;
					}
					if (data.data[i].location.longitude!=null) {
						userAData[i].lon = data.data[i].location.longitude;
					}
				}

				userAData[i].url = data.data[i].images.thumbnail.url;
				userAData[i].username = data.data[i].user.username;				

			}

			// console.log(data);
			$('#partner-avatar').attr('src', data.data[0].user.profile_picture)
			getUserFeed("self");
    
		} else {
      alert(data.meta.error_message);
    }
 	}
}

function onPhotoLoadedMerge(data) {

  //if meta code = all good
  if(data.meta.code == 200) {

		// if theere are photos
		if(data.data.length > 0) {
			
			for(i=0;i<data.data.length;i++){
				
				userBData[i] = {};

				if (data.data[i].created_time!=null) {
					userBData[i].unix_time = data.data[i].created_time;
					userBData[i].human_time = timeConverter(data.data[i].created_time);
				}
				
				if (data.data[i].caption!=null) {
					userBData[i].caption = data.data[i].caption.text;
				}
				
				if (data.data[i].location!=null) {
					if (data.data[i].location.name!=null) {
						userBData[i].location = data.data[i].location.name;
					}
					if (data.data[i].location.latitude!=null) {
						userBData[i].lat = data.data[i].location.latitude;
					}
					if (data.data[i].location.longitude!=null) {
						userBData[i].lon = data.data[i].location.longitude;
					}
				}

				userBData[i].url = data.data[i].images.thumbnail.url;
				userBData[i].username = data.data[i].user.username;				

			}
			
			combinedData = userAData;
			combinedData = combinedData.concat(userBData);
			combinedData.sort(dynamicSort("-unix_time"));
			calculateDistances(combinedData);
			
		} else {
      alert(data.meta.error_message);
    }
	}
}
	
function calculateDistances(data){
	
	var tempUserA = userAData[0].username;
	var tempUserB = userBData[0].username;
	
	var tempLatA, tempLonA, tempLatB, tempLonB;
	var finishedA = false;
	var finishedB = false;
	
	for(i=0;i<userAData.length;i++){
		if(finishedA === false){
			if(userAData[0].lat != null){
				tempLatA = userAData[0].lat;
				tempLonA = userAData[0].lon;
				finishedA = true;
			}
		}
	}
	
	for(i=0;i<userBData.length;i++){
		if(finishedB === false){
			if(userBData[0].lat != null){
				tempLatB = userBData[0].lat;
				tempLonB = userBData[0].lon;
				finishedB = true;
			}
		}
	}
	
	if(finishedA === false || finishedB === false){
		alert("Error: This user has no location data attached to their photos. Atleast one location from each user is required.");
		throw new Error('Atleast one location from each user is required.');
	}
	
	// Loop through combined data adding the distance of the other person each time.
	for(i=0;i<data.length;i++){
		if(data[i].username === tempUserA){
			data[i].lat2 = tempLatB;
			data[i].lon2 = tempLonB;
			if(data[i].lat != null){
				tempLatA = data[i].lat;
				tempLonA = data[i].lon;
			} else {
				// if lat & lon are undefined, set them to the last set location
				data[i].lat = tempLatA;
				data[i].lon = tempLonA;
			}
		} else {
			data[i].lat2 = tempLatA;
			data[i].lon2 = tempLonA;
			if(data[i].lat != null){
				tempLatB = data[i].lat;
				tempLonB = data[i].lon;
			} else {
				// if lat & lon are undefined, set them to the last set location
				data[i].lat = tempLatB;
				data[i].lon = tempLonB;
			}
		}
	}
	
	// Loop through both locations and calculate distances between them.
	for(i=0;i<data.length;i++){
		data[i].dist = getDistanceFromLatLonInMiles(data[i].lat,data[i].lon,data[i].lat2,data[i].lon2);
	}
	
	combinedData = data;
	update(combinedData);
}

function igCallback(){
	igCallback();
}

function igInit(){
	// load instagram data before user chooses someone
	getFollowList(1);
	getLoggedInDetails();
	getUserFeed("colors");
}

function igUpdate(callback){
	// output feeds for logged in user and their selected partner
	if (callback) {
	  igCallback = callback;
	}
	getUserFeed($('#partner').find(":selected").val(), callback);
}