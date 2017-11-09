<html lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<link rel="shortcut icon" href="/favicon.ico" type="image/x-icon">
	<link rel="icon" href="/favicon.ico" type="image/x-icon">
	<meta charset="utf-8" />
	<meta name="format-detection" content="telephone=no" />
	<meta name="apple-mobile-web-app-title" content="Travel Alerts">
	<meta name="viewport" content="initial-scale=1, viewport-fit=cover, minimum-scale=1, width=device-width">
	<meta name="apple-mobile-web-app-capable" content="yes" />
	<meta name="apple-mobile-web-app-status-bar-style" content="black" />
	<link rel="apple-touch-icon" href="apple-touch-icon.png?m=<?php echo filemtime('apple-touch-icon.png'); ?>" />
	<link rel="stylesheet" href="css/styles.css?v=0.96" />
	<link rel="manifest" href="manifest.json">
	<meta name="theme-color" content="#234177"/>

	<!-- DO NOT CACHE (for development)
	<meta http-equiv="cache-control" content="max-age=0" />
	<meta http-equiv="cache-control" content="no-cache" />
	<meta http-equiv="expires" content="0" />
	<meta http-equiv="expires" content="Tue, 01 Jan 1980 1:00:00 GMT" />
	<meta http-equiv="pragma" content="no-cache" />
	-->
	<title>Travel Alerts</title>
	<script>
	function getData(strUser) {
		document.getElementById("overlay").style.display = "block";
		var e = document.getElementById("agencyDropdown");
		var value = e.options[e.selectedIndex].value;
		if(!value)value="";
		var xhttp = new XMLHttpRequest();
		//Remove all children
		var oldContent = document.getElementById("results");
		while (oldContent.firstChild) {
		    oldContent.removeChild(oldContent.firstChild);
		}
		//Prepare the Ajax request
		xhttp.onreadystatechange = function() {
		  	if (this.readyState == 4 && this.status == 200) {
		   		var myObj = JSON.parse(this.responseText);
		   		for (var i = 0, l = Object.keys(myObj).length; i < l; i++) {
		   			var title = myObj[i][1];
		   			var desc = myObj[i][2];
		   			var link = myObj[i][3];
		   			var pubDate = myObj[i][4];
		   			var agency = myObj[i][5];
		   			var abrv = myObj[i][5];

		   			var newContent = document.createElement('div');
		   			newContent.className = 'updateItem' + ' ' + abrv; //Leave the space after the classname or they all mush together
		   			newContent.innerHTML = "<div class='title'>" + title + "<span class='agency'>" + agency + "&nbsp;</span></div><div class='status'>" + desc + "<br><a href='" + link + "' id='infoLink" + i + "' target='_blank'>More Information</a></div><div class='pubDate'>" + pubDate + "</div>";
		   			document.getElementById('results').appendChild(newContent);
		   			if(link==""||link==null) document.getElementById("infoLink" + i).style.display = "none";
		   			document.getElementById("overlay").style.display = "none";
		   		}
		  	}
		};
		xhttp.open("GET", "includes/accessData.php?q=" + value, true);
		xhttp.send();
	}
	</script>
</head>
<body>
<header>
	<div class="widthWrap">
		<h1>Regional Travel Alerts</h1>
	</div>
</header>
<div id="overlay">
	<div class="spinner element" id="spinner"></div>
</div>
<div class="wrapper">
	<span class="out-selector"><span class="plain-selector">
		<form>
			<label for="agencyDropdown">Select Agency</label>
			<select id="agencyDropdown" name="agencyDropdown" onchange="getData();" class="dropbtn">
				<option value="" selected disabled>Filter By Agency</option>
				<!-- <option value="all">Show All</option> -->
				<option value="njtr">NJTransit Rail</option>
				<option value="njtb">NJTransit Bus</option>
				<option value="njtlr">NJTransit Light Rail</option>
				<option value="path">PATH</option>
				<option value="mnr">MTA Metro-North</option>
				<option value="lirr">MTA Long Island Railroad</option>
				<option value="subway">NYC Subway</option>
				<!-- <option value="btt">Bridges and Tunnels</option> -->
			</select>
		</form>
	</span></span>
	<div id="results"></div>


<div class="push"></div>
</div>
<footer><p class="widthWrap disclaimer">Alert information provided by the associated agency (all rights reserved).
	Information parsed and displayed by dlevine.us</p></footer>
</body>
</html>