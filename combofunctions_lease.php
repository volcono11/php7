<?php
@session_start();
include "connection.php";
?>

<?php

if($_REQUEST['level']=="Units"){

		$CMB = "<option value=''>Select Unit</option>";

		$SEL =  "select id  as lookcode,unitname as lookname from tbl_propertydetails where propertyid='".$_REQUEST['propertyidid']."' order by id";
		$RES = mysqli_query($con,$SEL);
		while ($ARR = mysqli_fetch_array($RES)) {
		 	$CMB .= "<option value='".$ARR['lookcode']."' >".$ARR['lookname']."</option>";
		}
		
		echo $CMB;
		exit;    
}


?>

