<html lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<link rel="shortcut icon" href="/favicon.ico" type="image/x-icon">
	<link rel="icon" href="/favicon.ico" type="image/x-icon">
	<meta name="format-detection" content="telephone=no" />
	<meta name="apple-mobile-web-app-title" content="Travel Alerts">
	<meta name="viewport" content="initial-scale=1, viewport-fit=cover, minimum-scale=1, width=device-width">
	<meta name="apple-mobile-web-app-capable" content="yes" />
	<meta name="apple-mobile-web-app-status-bar-style" content="black" />
	<link rel="apple-touch-icon" href="apple-touch-icon.png?m=<?php echo filemtime('apple-touch-icon.png'); ?>" />
	<link rel="stylesheet" href="css/styles.css?v=0.962" />
	<link rel="manifest" href="manifest.json">
	<meta name="theme-color" content="#234177"/>

	<title>Travel Alerts</title>
</head>
<body>
<header>
	<div class="widthWrap">
      <h1>Regional Travel Alerts</h1><span class="beta">(beta)</span>
	</div>
</header>
<div id="overlay">
	<div class="spinner element" id="spinner"></div>
</div>
<div class="wrapper">
	<button id="toggle" onclick="toggle();">Back</button>
	<div id="input">
		<button onclick="getData('njtr');">NJTransit Rail</button>
		<button onclick="getData('njtlr');">NJTransit Light Rail</button>
		<button onclick="getData('njtb');">NJTransit Bus</button>
		<button onclick="getData('pabt');">Port Authority Bus Terminal</button>				
		<button onclick="getData('path');">PATH</button>
		<button onclick="getData('mnr');">MTA Metro-North</button>
		<button onclick="getData('lirr');">MTA Long Island Railroad</button>
		<button onclick="getData('subway');">NYC Subway</button>
		<!-- <button onclick="getData('septa');">SEPTA Rail</button> -->
	</div>
	<div id="results"></div>


<div class="push"></div>
</div>
<footer><p class="widthWrap disclaimer">Alert information provided by the associated agency (all rights reserved).
	Information parsed and displayed by dlevine.us</p></footer>
<script type="text/javascript" src="js/main.js"></script>
</body>
</html>