<?php

function db () {
  $servername = "localhost";
  $username = "username";
  $password = "password";
  $dbname = "travelalerts";

  static $conn;
  $conn = mysqli_connect ($servername, $username, $password, $dbname);
  
    if (!$conn) {
      array_push($GLOBALS['debugging'], "Connecting to Database");
      exit;
    }

    if (!mysqli_num_rows(mysqli_query($conn,"SHOW DATABASES LIKE '$dbname'"))) {
      $sql = "CREATE DATABASE myDB";
      if(mysqli_query($conn, $sql))){}
      else{ array_push($GLOBALS['debugging'], "Could not create database $dbname"); }
    }

    return $conn;
}

?>