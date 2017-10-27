<?php
$items = array();
$lineName;
$duplicate = 0;
$yesterday = date('M d, Y', time() - 60 * 60 * 24);
$today= date('M d, Y');

//Get Items from the NJT RSS Feed
function njtr($abrv,$njtFeed){
	global $yesterday, $today, $items, $duplicate, $n;
	resetTable($abrv);
	foreach ($njtFeed->channel->item as $element) {
		if (strpos($element->pubDate, $yesterday) !== false || strpos($element->pubDate, $today) !== false) { //If it's published yesterday or today
			if(strpos($element->description, "train #") !== false || strpos($element->description, "bus") !== false){ //If it contains the words "train #" or "bus"
				foreach ($items as $value) {
					if ((string)$element->description == $value["desc"]){ //Check if the feed is a duplicate
						$duplicate = 1; // Don't remember why I used a duplicate flag variable instead of an else
					}
				}
				if($duplicate !== 1){
					$title = sanitizeSql(findLine($element->description));
					$description = sanitizeSql((string)$element->description);
					$pubDate = sanitizeSql((string)$element->pubDate);
					$agency = sanitizeSql("NJT Rail");
					
					updateRecord($title, $description, $pubDate, $agency, $abrv);
				}
				$duplicate = 0; // Reset the duplicate flag
			}
		}
	}
	updateTime($abrv);
}

//Get Items from the NJT Light Rail RSS Feed
function njtlr($abrv,$njtLRFeed){
	global $yesterday, $today, $items, $duplicate, $n;
	resetTable($abrv);
	foreach ($njtLRFeed->channel->item as $element) {
		if (strpos($element->pubDate, $yesterday) !== false || strpos($element->pubDate, $today) !== false) {
			foreach ($items as $value) {
				if ((string)$element->description == $value["desc"]){
					$duplicate = 1;
				}
			}
			if($duplicate !== 1){ //If it's not a duplicate write it to our $items array
				$title = "NJTransit Light Rail";
				$description = sanitizeSql((string)$element->description);
				$pubDate = sanitizeSql((string)$element->pubDate);
				$agency = sanitizeSql("NJT Light Rail");
				
				updateRecord($title, $description, $pubDate, $agency, $abrv);
			}
			$duplicate = 0;
		}
	}
	updateTime($abrv);
}

//Get Items from the PATH RSS Feed
function path($abrv,$pathFeed){
	global $yesterday, $today, $items, $duplicate, $n;
	resetTable($abrv);
	foreach ($pathFeed->channel->item as $element) {
		$postDate = date('M d, Y h:i:s A', strtotime($element->pubDate));
		if (strpos($postDate, $today) !== false) {
			//if(strpos($element->description, "line") !== false){
				foreach ($items as $value) {
					if ((string)$element->description == $value["desc"]){
						$duplicate = 1;
					}
				}
				if($duplicate !== 1){
				$title = "PATH Alert";
				$description = sanitizeSql((string)$element->description);
				$pubDate = sanitizeSql($postDate);
				$agency = sanitizeSql("PATH");
				
				updateRecord($title, $description, $pubDate, $agency, $abrv);
				}
				$duplicate = 0;
			//}
		}
	}
	updateTime($abrv);
}

//Get Items from the MTA Metro-North RSS Feed
function mnr($abrv, $mtaFeed){
	global $yesterday, $today, $items, $duplicate, $n;
	resetTable($abrv);
	foreach ($mtaFeed->MetroNorth->line as $element) {
		if(strpos($element->name, "Pascack Valley") !== false || strpos($element->name, "Port Jervis") !== false || strpos($element->status, "GOOD SERVICE") !== false){
				//If it operates west of the Hudson it falls under NJT (contracted as Metro-North)
				//If it's good service we're not interested for now
				//MTA removes the duplicates for us, no reason to check
		}
		else {
			$title = sanitizeSql((string)$element->name . " Line");
			$description = sanitizeSql(strip_tags((string)$element->text, '<p>, <br>, <a>'));
			$pubDate = sanitizeSql(date('M d, Y h:i:s A', strtotime(strip_tags((string)$element->Date . (string)$element->Time))));
			$agency = sanitizeSql("Metro-North");
			updateRecord($title, $description, $pubDate, $agency, $abrv);
		}
	}
	updateTime($abrv);
}

//Get Items from the MTA Long Island Railroad RSS Feed
function lirr($abrv, $mtaFeed){
	global $yesterday, $today, $items, $duplicate, $n;
	resetTable($abrv);
	foreach ($mtaFeed->LIRR->line as $element) {
		if(strpos($element->status, "GOOD SERVICE") === false){
			$title = sanitizeSql((string)$element->name . " Line");
			$description = sanitizeSql(strip_tags((string)$element->text, '<p>, <br>, <a>'));
			$pubDate = sanitizeSql(date('M d, Y h:i:s A', strtotime(strip_tags((string)$element->Date . (string)$element->Time))));
			$agency = sanitizeSql("Long Island Railroad");
			updateRecord($title, $description, $pubDate, $agency, $abrv);
		}
	}
	updateTime($abrv);
}

//For New Jersey Transit - Determine which line the alert pertains to
function findLine($line){
	preg_match('/(NEC)|(NJCL)|(M&E)|(MBPJ)|(ACRL)|(PVL)|(RVL)|(MOBO)/', $line, $matches);
	if(!isset($matches[0]))$matches[0] = "NJT";
	switch($matches[0]){
	case 'NEC':
		$lineName = "North East Corridor";
	break;
	case 'NJCL':
		$lineName = "New Jersey Coast Line";
	break;
	case 'M&E':
		$lineName = "Morris and Essex";
	break;
	case 'MBPJ':
   case 'MBML':
		$lineName = "Main/Bergen/Port Jervis Lines";
	break;	
	case 'ACRL':
		$lineName = "Atlantic City Rail Line";
	break;
	case 'PVL':
		$lineName = "Pascack Valley Line";
	break;
	case 'RVL':
		$lineName = "Raritan Valley Line";
	break;
	case 'MOBO':
		$lineName = "Montclair Boonton Line";
	break;
	default:
		$lineName = "NJT Rail Alert";
	break;
	}
	return $lineName;
}

?>