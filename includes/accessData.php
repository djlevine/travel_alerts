<?php
$debugging = array();
$agency = isset($_GET['q']) ? $_GET['q'] : "none";
include_once 'db.php';
include_once 'db_functions.php';
include_once 'agencyData.php';


$agencyFeeds = array(
    "njtr" => "http://www.njtransit.com/rss/RailAdvisories_feed.xml",
    "njtb" => "http://www.njtransit.com/rss/BusAdvisories_feed.xml",
    "njtlr" => "http://www.njtransit.com/rss/LightRailAdvisories_feed.xml",
    "path" => "http://rss.paalerts.com/rss.aspx?path",
    "mnr" => "http://web.mta.info/status/serviceStatus.txt",
    "lirr" => "http://web.mta.info/status/serviceStatus.txt",
    "subway" => "http://web.mta.info/status/serviceStatus.txt"
);

if (isset($agency) && $agency !== null){
	if (!function_exists($agency)) echo json_encode(Err('404', $agency), JSON_FORCE_OBJECT);
	if(isset($agencyFeeds[$agency])){
		TbExists($agency);
		isUptoDate($agency, $agencyFeeds[$agency]);
	}else {
      array_push($GLOBALS['debugging'], "Feed not available");
    }
}

function isUptoDate($agency, $agencyFeed){
	$conn = db();
	$pTime = time() - 600;
	$sql = $conn->query("SELECT $agency FROM updated where ID = '0'");

	while($row = $sql->fetch_array(MYSQLI_NUM)){
		$cTime = $row[0];
	}
	if(!isset($cTime)){
		$cTime = 1;
	}
	
	if ($cTime <= $pTime) {
    //if ($pTime == $pTime) {//For Debugging
		$header_response = get_headers($agencyFeed, 1);
		//Check if the feed exists
		if (strpos( $header_response[0], "200") == true ){
			$agency($agency, simplexml_load_file($agencyFeed));
          	getData($agency,0);
		} else {
         	getData($agency,1);
		}
	}else{ 
		getData($agency,0);
    }
}

?>