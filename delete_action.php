<?php
ob_start();
include "connection.php";
function UserLog($tblName,$tableseqID,$tablestrSQL,$actiontype){
		global $con;
        $seqID = GetLastSqeID("in_userlog");
        $datetime=date("Y/m/d h:i:s a", time());

        $seqSQL = "insert into in_userlog values(".$seqID.",'".$datetime."','".$_SESSION['SESSuserID'] ."','".$_SERVER['REMOTE_ADDR']."','".$tblName."','".$tableseqID."','".$actiontype."','".str_replace("'","''",$tablestrSQL)."','".$_SESSION['SESSUserLocation']."','".$_SESSION['SESScompanycode']."')";
        $result=mysqli_query($con,$seqSQL) or die(mysqli_error()."<br>".$seqSQL);
}
function GetLastSqeID($tblName){
	global $con;
       $query = "LOCK TABLES $tblName WRITE";
       mysqli_query($con,$query) or die(mysqli_error()."<br>".$query);
       $seqSQL = "SELECT max(id) as LASTNUMBER FROM $tblName";
       $result=mysqli_query($con,$seqSQL) or die(mysqli_error()."<br>".$seqSQL);
       $resulArr=mysqli_fetch_array($result);
       $updatedSeqID=$resulArr['LASTNUMBER']+1;
       $query = "UNLOCK TABLES";
       mysqli_query($con,$query) or die(mysqli_error()."<br>".$query);
       return ($updatedSeqID);
}
?>