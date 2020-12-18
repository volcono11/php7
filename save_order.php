<?php
session_start();

require "connection.php";

$ids = $_POST['ids'];
$arr = explode(',',$ids);
for($i=1;$i<=count($arr);$i++)
{
	$q = "UPDATE tbl_menu SET slno = ".$i." WHERE id = ".$arr[$i-1];
	mysqli_query($con,$q);
}
?>