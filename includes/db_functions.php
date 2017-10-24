<?php
function db_query($query, $debug){
  	$conn = db();
	// Query the database
    $result = mysqli_query($conn,$query);
  	//if(!isset($debug){$debug="in the matrix!";}
  
      if(!$result){// !== true) {
          array_push($GLOBALS['debugging'], "Error: $debug");
      } else {
          array_push($GLOBALS['debugging'], "Success: $debug");
      }
    return $result;
}

function sanitizeSql($string){
	$conn = db();
	$string = mysqli_real_escape_string($conn, $string);
	return $string;
}

function resetTable($agency){
  	db_query("DELETE FROM $agency", "reset table $agency");
  	db_query("ALTER TABLE $agency AUTO_INCREMENT = 0", "reset a_i");
}

//A separate table is maintained for recording the
//time since the data was updated
function updateTime($abrv){
	$time = time();
	$sql = "UPDATE updated set $abrv='$time' where ID='0'";
  	db_query($sql, "update the time record");
}

function updateRecord($title, $description, $pubDate, $agency, $abrv){
	$sql = "INSERT INTO $abrv (Title, Description, PubDate, Agency, Abrv) VALUES ('$title', '$description', '$pubDate', '$agency', '$abrv')";
 	db_query($sql, "create the new $agency record in table $abrv");
}

function getData($agency, $error){
	$results=array();
  	$conn = db();
 	$sql = "SELECT * FROM $agency";
  	$sql = db_query($sql, "retrieve records from server");
  
	while($row = $sql->fetch_array(MYSQLI_NUM)){
		array_push($results, $row);
	}
  
   	if(empty($results)){
      	$sql = "SELECT $agency FROM updated where ID='0'";
      	$lastUpdate = mysqli_fetch_array(db_query($sql, "get last update time for error message"));
		$noAlerts = array('1', 'There are no alerts at this time', 'There are currently no travel alerts for this agency.', date('M d, Y h:i:s A', $lastUpdate[$agency]), '', $agency);
      	array_push($results, $noAlerts);
	}

    if($error==1){
    	$feedErr = array('1', 'Connection Error!', 'The following information is available, but may not be current.', date('M d, Y h:i:s A',time()), "Error code: $agency$error", "error");
      	array_unshift($results, $feedErr);
    }
 
	if(mysqli_close($conn)) array_push($GLOBALS['debugging'], "Closed SQL connection");
  
	//Merge the debugging results into the output.
  	$debugging = array(0 => array('1', 'Debugging Data', implode("<br>", $GLOBALS['debugging']), date('M d, Y h:i:s A',time()), 'dlevine.us', 'None'));
  	//Uncomment line to display debugging results
	$results = array_merge($debugging, $results);
	echo json_encode($results, JSON_FORCE_OBJECT);
}
       

?>