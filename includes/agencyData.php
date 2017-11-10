<?php
$items = array();
$lineName;
$duplicate = 0;
$yesterday = date('M d, Y', time() - 60 * 60 * 24);
$results=array();

//Get Items from the NJT RSS Feed
function njtr($abrv,$feedUrl){
	global $yesterday, $items, $duplicate, $n;
	resetTable($abrv);
	foreach ($feedUrl->channel->item as $element) {
		if (strpos($element->pubDate, $yesterday) !== false || strpos($element->pubDate, date('M d, Y')) !== false) { //If it's published yesterday or today
			if(strpos($element->description, "train #") !== false || strpos($element->description, "bus") !== false){ //If it contains the words "train #" or "bus"
				foreach ($items as $value) {
					if ($element->description == $value["desc"]){ //Check if the feed is a duplicate
						$duplicate = 1; // Don't remember why I used a duplicate flag variable instead of an else
					}
				}
				if($duplicate !== 1){
					// $alertData = array(
					// 	'title' => sanitizeSql(findLine($element->description)),
					// 	'description' => sanitizeSql($element->description),
					// 	'link' => sanitizeSql($element->link),
					// 	'pubDate' => sanitizeSql($element->pubDate),
					// 	'agency' => sanitizeSql("NJT Rail")
					// );
					// updateRecord($alertData);

					$title = sanitizeSql(findLine($element->description));
					$description = sanitizeSql($element->description);
					$link =  sanitizeSql($element->link);
					$pubDate = sanitizeSql($element->pubDate);
					$agency = sanitizeSql("NJT Rail");
					
					updateRecord($title, $description, $link ,$pubDate, $agency, $abrv);
				}
				$duplicate = 0; // Reset the duplicate flag
			}
		}
	}
	updateTime($abrv);
}

//Get Items from the NJT Bus Feed
function njtb($abrv,$feedUrl){
	global $yesterday, $items, $duplicate, $n;
	resetTable($abrv);
	foreach ($feedUrl->channel->item as $element) {
		//if (strpos($element->pubDate, $yesterday) !== false || strpos($element->pubDate, date('M d, Y')) !== false) {
			if(strpos($element->description, "Bus Route") !== false || strpos($element->description, "detour") !== false){
				foreach ($items as $value) {
					if ($element->description == $value["desc"]){ //Check if the feed is a duplicate
						$duplicate = 1;
					}
				}
				if($duplicate !== 1){
					$title = $element->title;
					$title = substr($title, 0, strpos($title, "-"));
					$title = sanitizeSql($title);
					$description = sanitizeSql(strip_tags($element->description));
					$link =  sanitizeSql($element->link);
					$pubDate = sanitizeSql($element->pubDate);
					$agency = sanitizeSql("NJT Bus");
					
					updateRecord($title, $description, $link ,$pubDate, $agency, $abrv);
				}
				$duplicate = 0; // Reset the duplicate flag
			}
		//}
	}
	updateTime($abrv);
}

//Get Items from the NJT Light Rail RSS Feed
function njtlr($abrv,$feedUrl){
	global $yesterday, $items, $duplicate, $n;
	resetTable($abrv);
	foreach ($feedUrl->channel->item as $element) {
		if (strpos($element->pubDate, $yesterday) !== false || strpos($element->pubDate, date('M d, Y')) !== false) {
			foreach ($items as $value) {
				if ($element->description == $value["desc"]){
					$duplicate = 1;
				}
			}
			if($duplicate !== 1){ //If it's not a duplicate write it to our $items array
				$title = "NJTransit Light Rail";
				$description = sanitizeSql($element->description);
				$link =  sanitizeSql($element->link);
				$pubDate = sanitizeSql($element->pubDate);
				$agency = sanitizeSql("NJT Light Rail");
				
				updateRecord($title, $description, $link ,$pubDate, $agency, $abrv);
			}
			$duplicate = 0;
		}
	}
	updateTime($abrv);
}

//Get Items from the PATH RSS Feed
function path($abrv,$feedUrl){
	global $yesterday, $items, $duplicate, $n;
	resetTable($abrv);
	foreach ($feedUrl->channel->item as $element) {
		$postDate = date('M d, Y h:i:s A', strtotime($element->pubDate));
		if (strpos($postDate, date('M d, Y')) !== false) {
			//if(strpos($element->description, "line") !== false){
				foreach ($items as $value) {
					if ($element->description == $value["desc"]){
						$duplicate = 1;
					}
				}
				if($duplicate !== 1){
				$title = "PATH Alert";
				$description = sanitizeSql($element->description);
				$link =  sanitizeSql("");
				$pubDate = sanitizeSql($postDate);
				$agency = sanitizeSql("PATH");
				
				updateRecord($title, $description, $link ,$pubDate, $agency, $abrv);
				}
				$duplicate = 0;
			//}
		}
	}
	updateTime($abrv);
}

//Get Items from the MTA Metro-North RSS Feed
function mnr($abrv, $feedUrl){
	global $yesterday, $items, $duplicate, $n;
	resetTable($abrv);
	foreach ($feedUrl->MetroNorth->line as $element) {
		if(strpos($element->name, "Pascack Valley") !== false || strpos($element->name, "Port Jervis") !== false || strpos($element->status, "GOOD SERVICE") !== false){
				//If it operates west of the Hudson it falls under NJT (contracted as Metro-North)
				//If it's good service we're not interested for now
				//MTA removes the duplicates for us, no reason to check
		}
		else {
			$title = sanitizeSql($element->name . " Line");
			$description = sanitizeSql(strip_tags($element->text, '<br>, <a>'));
			$link =  sanitizeSql("");
			$pubDate = sanitizeSql(date('M d, Y h:i:s A', strtotime(strip_tags($element->Date . $element->Time))));
			$agency = sanitizeSql("Metro-North");
			updateRecord($title, $description, $link ,$pubDate, $agency, $abrv);
		}
	}
	updateTime($abrv);
}

//Get Items from the MTA Long Island Railroad RSS Feed
function lirr($abrv, $feedUrl){
	global $yesterday, $items, $duplicate, $n;
	resetTable($abrv);
	foreach ($feedUrl->LIRR->line as $element) {
		if(strpos($element->status, "GOOD SERVICE") === false){
			$title = sanitizeSql($element->name . " Line");
			$description = sanitizeSql(strip_tags($element->text, '<p>, <br>, <a>'));
			$link =  sanitizeSql("");
			$pubDate = sanitizeSql(date('M d, Y h:i:s A', strtotime(strip_tags($element->Date . $element->Time))));
			$agency = sanitizeSql("Long Island Railroad");
			updateRecord($title, $description, $link ,$pubDate, $agency, $abrv);
		}
	}
	updateTime($abrv);
}

//Get Items from the MTA Long Island Railroad RSS Feed
function subway($abrv, $feedUrl){
	global $yesterday, $items, $duplicate, $n;
	resetTable($abrv);
	foreach ($feedUrl->subway->line as $element) {
		if(strpos($element->status, "GOOD SERVICE") === false){
			$title = sanitizeSql($element->name . " Line");
			$description = sanitizeSql(strip_tags($element->text, '<p>, <span>, <br>'));
			$description = preg_replace('/\s+/', ' ', $description);
			$description = preg_replace('#(<br\s?/?>)+#', '<br>', $description);
			$link =  sanitizeSql("");
			$pubDate = sanitizeSql(date('M d, Y h:i:s A', strtotime(strip_tags($element->Date . $element->Time))));
			$agency = sanitizeSql("NYCTA Subway");
			updateRecord($title, $description, $link ,$pubDate, $agency, $abrv);
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