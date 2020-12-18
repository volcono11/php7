<?php

//  Establish Connection
$host = "127.0.0.1"; // Host name 192.168.64.87
$user = "root"; // DataBase USer name
$password = ""; // Ntk31Aus // Password
$database = "ucwflive2"; // Database name
$database = "php7db"; // Database name
$auditRequired = 0; // Set to 0 to stop audit trial
date_default_timezone_set('Asia/Dubai');
	global $con;
    $con = mysqli_connect($host,$user,$password);
    if(! $con) die("Couldnot connect to mysql:".mysqli_error());
    $con->select_db($database) or die ("Could not open the database $database:".mysqli_error());
?>