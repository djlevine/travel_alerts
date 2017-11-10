<?php
/*These things happen if other things are broken*/
function TbExists($table){
  $table = sanitizeSql($table);
  $sql = "SHOW TABLES LIKE '$table'";
  $result = db_query($sql, "searching database for table: $table");
  $table_exists = mysqli_num_rows($result);
  if(!$table_exists){
    $sql = array(
              "CREATE TABLE `$table` (
              `ID` int(4) NOT NULL AUTO_INCREMENT,
              `Title` varchar(35) DEFAULT NULL,
              `Description` text(500) DEFAULT NULL,
              `Link` varchar(255) DEFAULT NULL,
              `PubDate` varchar(35) DEFAULT NULL,
              `Agency` varchar(12) DEFAULT NULL,
              `Abrv` varchar(4) DEFAULT NULL,
              PRIMARY KEY (`ID`)
            ) ENGINE=MyISAM DEFAULT CHARSET=latin1;",
            "ALTER TABLE updated ADD $table int(35);",
            );
    foreach ($sql as $query) {
      db_query($query, "Update SQL tables for new agency");
    }
  } else array_push($GLOBALS['debugging'], "table '$table' exists!");
}

function Err($error, $agency){
    global $results;
    $email = "mailto:dlevine@dlevine.us?subject=Travel Alerts Error!&body=I received an error in Travel Alerts. Error: $agency:$error";
    if($error>0){
    $feedErr = array('$error', 'Connection Error!', 'The following information is available, but may not be current.', $email, date('M d, Y h:i:s A',time()), "Error code: $agency:$error", "error");
    array_unshift($results, $feedErr);
  }
  return $results;
}
/*This code is the real deal*/
function db_query($query, $debug){
  $conn = db();
  $result = mysqli_query($conn,$query);
  
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

// function updateRecord($alertData){
//  foreach ($alertData as $key => $value) {
//     sanitizeSql($value);
//     $alertData[$key] = "'$value'";
//   }
//   $values  = implode(", ", $alertData);

// 	$sql = "INSERT INTO njtb (Title, Description, Link, PubDate, Agency, Abrv) VALUES ($values)";
//   echo "$sql";exit();
//  	db_query($sql, "$sql");//"create the new $agency record in table $abrv");
// }

function updateRecord($title, $description, $link, $pubDate, $agency, $abrv){
  $sql = "INSERT INTO $abrv (Title, Description, Link, PubDate, Agency, Abrv) VALUES ('$title', '$description', '$link', '$pubDate', '$agency', '$abrv')";
  db_query($sql, "create the new $agency record in table $abrv");
}

function getData($agency, $error){
  global $results;
  $conn = db();
 	$sql = "SELECT * FROM $agency";
  $sql = db_query($sql, "retrieve records from server");
  
	while($row = $sql->fetch_array(MYSQLI_NUM)){
		array_push($results, $row);
	}
  
  if(empty($results)){
    $sql = "SELECT $agency FROM updated where ID='0'";
    $lastUpdate = mysqli_fetch_array(db_query($sql, "get last update time. append to results."));
		$noAlerts = array('1', 'There are no alerts at this time', 'There are currently no travel alerts for this agency.', null, date('M d, Y h:i:s A', $lastUpdate[$agency]), '', $agency);
    array_push($results, $noAlerts);
	}
  Err($error, $agency);
  mysqli_close($conn);
  
	//Merge the debugging results into the output.
  $debugging = array(0 => array('1', 'Debugging Data', implode("<br>", $GLOBALS['debugging']), date('M d, Y h:i:s A',time()), 'dlevine.us', 'None'));
  //Uncomment line to display debugging results
	//$results = array_merge($debugging, $results);
	echo json_encode($results, JSON_FORCE_OBJECT);
}
      

?>