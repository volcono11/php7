<?php
@session_start();
include "connection.php";
?>

<?php

if($_REQUEST['level']=="AllCities"){

		$CMB = "<option value=''>Select City</option>";

		$SEL =  "select id  as lookcode,city as lookname from tbl_city where emirateid='".$_REQUEST['emirateid']."' order by city";
		$RES = mysqli_query($con,$SEL);
		while ($ARR = mysqli_fetch_array($RES)) {
		 	$CMB .= "<option value='".$ARR['lookcode']."' >".$ARR['lookname']."</option>";
		}
		
		echo $CMB;
		exit;    
}

if($_REQUEST['level']=="AllEmirates"){

		$CMB = "<option value=''>Select Emirates</option>";

		$SEL =  "select id  as lookcode,emirate as lookname from tbl_states where countryid='".$_REQUEST['countryid']."' order by emirate";
		$RES = mysqli_query($con,$SEL);
		while ($ARR = mysqli_fetch_array($RES)) {
		 	$CMB .= "<option value='".$ARR['lookcode']."' >".$ARR['lookname']."</option>";
		}
		
		echo $CMB;
		exit;    
}

?>

