<?php
$items = array();
$lineName;
$duplicate = 0;
$yesterday = date('M d, Y', time() - 60 * 60 * 24);
$results=array();

//Get Items from the NJT RSS Feed
function njtr($abrv,$feedUrl){
	global $yesterday, $items, $duplicate, $n;
	$alertCheck = array();
	resetTable($abrv);
	foreach ($feedUrl->channel->item as $element) {
		$postDate = date('M d, Y h:i:s A', strtotime($element->pubDate));
		if (strpos($postDate, $yesterday) !== false || strpos($postDate, date('M d, Y')) !== false) { //If it's published yesterday or today
			if(strpos($element->description, "train #") !== false || strpos($element->description, "bus") !== false){ //If it contains the words "train #" or "bus"
				$alerts = array(
					'title' => sanitizeSql(findLine($element->description)),
					'link' =>  sanitizeSql($element->link),
					'description' => sanitizeSql($element->description),
					'pubDate' => sanitizeSql($postDate),
					'agency' => sanitizeSql("NJT Rail"),
					'abrv' => $abrv
				);
				dupCheck($alerts, $alertCheck);	
			}
		}
	}
	updateTime($abrv);
}

//Get Items from the NJT Light Rail RSS Feed
function njtlr($abrv,$feedUrl){
	global $yesterday, $items, $duplicate, $n;
	$alertCheck = array();
	resetTable($abrv);
	foreach ($feedUrl->channel->item as $element) {
		$postDate = date('M d, Y h:i:s A', strtotime($element->pubDate));
		if (strpos($element->pubDate, $yesterday) !== false || strpos($element->pubDate, date('M d, Y')) !== false) {
			$alerts = array(
				'title' => "NJTransit Light Rail",
				'link' =>  sanitizeSql($element->link),
				'description' => sanitizeSql($element->description),
				'pubDate' => sanitizeSql($postDate),
				'agency' => sanitizeSql("NJT Light Rail"),
				'abrv' => $abrv
			);
			dupCheck($alerts, $alertCheck);	
		}
	}
	updateTime($abrv);
}

//Get Items from the NJT Bus Feed
function njtb($abrv,$feedUrl){
	global $yesterday, $items, $duplicate, $n;
	$alertCheck = array();
	resetTable($abrv);
	foreach ($feedUrl->channel->item as $element) {
		$postDate = date('M d, Y h:i:s A', strtotime($element->pubDate));
		if(strpos($element->description, "Bus Route") !== false || strpos($element->description, "detour") !== false){
			$alerts = array(
				'title' => sanitizeSql(substr($element->title, 0, strpos($element->title, "-"))),
				'link' =>  sanitizeSql($element->link),
				'description' => sanitizeSql(strip_tags($element->description)),
				'pubDate' => sanitizeSql($postDate),
				'agency' => sanitizeSql("NJT Bus"),
				'abrv' => $abrv
			);
			dupCheck($alerts, $alertCheck);					
		}
	}
	updateTime($abrv);
}

//Get Items from the PATH RSS Feed
function path($abrv,$feedUrl){
	global $yesterday, $items, $duplicate, $n;
	$alertCheck = array();
	resetTable($abrv);
	foreach ($feedUrl->channel->item as $element) {
		$postDate = date('M d, Y h:i:s A', strtotime($element->pubDate));
		if (strpos($postDate, date('M d, Y')) !== false) {
			$alerts = array(
				'title' => 'PATH Alert',
				'link' =>  sanitizeSql(""),
				'description' => sanitizeSql($element->description),
				'pubDate' => sanitizeSql($postDate),
				'agency' => sanitizeSql('PATH'),
				'abrv' => $abrv
			);
			dupCheck($alerts, $alertCheck);
		}
	}
	updateTime($abrv);
}

//Get Items from the PABT RSS Feed
function pabt($abrv,$feedUrl){
	global $yesterday, $items, $duplicate, $n;
	$alertCheck = array();
	resetTable($abrv);
	foreach ($feedUrl->channel->item as $element) {
		$postDate = date('M d, Y h:i:s A', strtotime($element->pubDate));
		if (strpos($postDate, date('M d, Y')) !== false) {
			$alerts = array(
				'title' => 'Port Authority Bus Terminal Alert',
				'link' =>  sanitizeSql(""),
				'description' => str_replace("PA", "Port Authority", sanitizeSql($element->description)),
				'pubDate' => sanitizeSql($postDate),
				'agency' => sanitizeSql("PABT"),
				'abrv' => $abrv
			);
			dupCheck($alerts, $alertCheck);
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
			$alerts = array(
				'title' => sanitizeSql($element->name . " Line"),
				'link' =>  sanitizeSql(""),
				'description' => sanitizeSql(strip_tags($element->text, '<br>, <a>')),
				'pubDate' => sanitizeSql(date('M d, Y h:i:s A', strtotime(strip_tags($element->Date . $element->Time)))),
				'agency' => sanitizeSql("Metro-North"),
				'abrv' => $abrv
			);

			updateRecord($alerts['title'], $alerts['description'], $alerts['link'] ,$alerts['pubDate'], $alerts['agency'], $alerts['abrv']);
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
			$alerts = array(
				'title' => sanitizeSql($element->name . " Line"),
				'link' =>  sanitizeSql(""),
				'description' => sanitizeSql(strip_tags($element->text, '<p>, <br>, <a>')),
				'pubDate' => sanitizeSql(date('M d, Y h:i:s A', strtotime(strip_tags($element->Date . $element->Time)))),
				'agency' => sanitizeSql("Long Island Railroad"),
				'abrv' => $abrv
			);

			updateRecord($alerts['title'], $alerts['description'], $alerts['link'] ,$alerts['pubDate'], $alerts['agency'], $alerts['abrv']);
		}
	}
	updateTime($abrv);
}

//Get Items from the MTA RSS Feed
function subway($abrv, $feedUrl){
	global $yesterday, $items, $duplicate, $n;
	resetTable($abrv);
	foreach ($feedUrl->subway->line as $element) {
		if(strpos($element->status, "GOOD SERVICE") === false){
			$description = sanitizeSql(strip_tags($element->text, '<p>, <span>, <br>'));
			$description = preg_replace('/\s+/', ' ', $description);
			$description = preg_replace('#(<br\s?/?>)+#', '<br>', $description);

			$alerts = array(
				'title' => sanitizeSql($element->name . " Line"),
				'link' =>  sanitizeSql(""),
				'description' => $description,
				'pubDate' => sanitizeSql(date('M d, Y h:i:s A', strtotime(strip_tags($element->Date . $element->Time)))),
				'agency' => sanitizeSql("NYCTA Subway"),
				'abrv' => $abrv
			);

			updateRecord($alerts['title'], $alerts['description'], $alerts['link'] ,$alerts['pubDate'], $alerts['agency'], $alerts['abrv']);
		}
	}
	updateTime($abrv);
}

//Get Items from the Septa feed (This is a work in progress)
function septa($abrv, $feedUrl){
	global $yesterday, $items, $duplicate, $n;
	resetTable($abrv);
	foreach ($feedUrl as $element) {
		if($element->current_message != ""){
			
			// $alerts = array(
			// 	'title' => sanitizeSql($element->name . " Line"),
			// 	'link' =>  sanitizeSql(""),
			// 	'description' => $description,
			// 	'pubDate' => sanitizeSql(date('M d, Y h:i:s A', strtotime(strip_tags($element->Date . $element->Time)))),
			// 	'agency' => sanitizeSql("NYCTA Subway"),
			// 	'abrv' => $abrv
			// );
			print_r($alerts); exit();
			updateRecord($alerts['title'], $alerts['description'], $alerts['link'] ,$alerts['pubDate'], $alerts['agency'], $alerts['abrv']);
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
		$lineName = "North Jersey Coast Line";
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

function dupCheck($alerts, &$alertCheck){
	$duplicate = 0;
	foreach ($alertCheck as $key => $value) {
		if($alertCheck[$key][1] == $alerts['description']){$duplicate = 1;}
	}

	if($duplicate !== 1){
		updateRecord($alerts['title'], $alerts['description'], $alerts['link'] ,$alerts['pubDate'], $alerts['agency'], $alerts['abrv']);
		array_push($alertCheck, array($alerts['title'], $alerts['description'], $alerts['link'], $alerts['pubDate'], $alerts['agency']));
	}
}

?>