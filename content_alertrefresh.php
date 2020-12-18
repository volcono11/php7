<?php
session_start();
//error_reporting(0);
include "connection.php";
date_default_timezone_set("Asia/Dubai");

if($_REQUEST["level"]=="alertcount"){
	$sub_contract_alerts = NULL;
	$accountsalert = NULL;
	$total_messages = NULL;

    if(stripos(json_encode($_SESSION['role']),'SALES') == true || stripos(json_encode($_SESSION['role']),'SERVICE') == true || stripos(json_encode($_SESSION['role']),'SITE INCHARGE') == true || stripos(json_encode($_SESSION['role']),'PUCHASE') == true || stripos(json_encode($_SESSION['role']),'FINANCE MANAGER') == true || stripos(json_encode($_SESSION['role']),'FRONT OFFICE CLERK') == true || stripos(json_encode($_SESSION['role']),'FACILITY MANAGER') == true){
     // $SQLacc2 = " SELECT COUNT(*) as count FROM tbl_alerts WHERE sendto='".$_SESSION['SESSuserID']."' order by id desc";
      $SQLacc2 = " SELECT count(*) as count FROM tbl_alerts WHERE sendto='".$_SESSION['SESSuserID']."' and  viewedby not like '%".$_SESSION['SESSuserID']."%' order by id desc";   // date_format(senddate,'%Y-%m-%d')='".date('Y-m-d')."'
      $SQLResacc2 =  mysqli_query($con,$SQLacc2) or die(mysqli_error()."<br>".$SQLacc2);
      if(mysqli_num_rows($SQLResacc2)>=1){
        while($loginResultArrayacc2   = mysqli_fetch_array($SQLResacc2)){
              $accountsalert =$loginResultArrayacc2['count'];
        }
      }

    }
    
    
    if(stripos(json_encode($_SESSION['role']),'SERVICE COORDINATOR') == true) {
      $SQL = "SELECT count(*) as count FROM tbl_subcontract WHERE DATEDIFF(tbl_subcontract.subcontractenddate,NOW())<=30  and contractstatus<>'Expired'";
      $SQLRes =  mysqli_query($con,$SQL) or die(mysqli_error()."<br>".$SQL);
      if(mysqli_num_rows($SQLRes)>=1){
        while($loginResultArray   = mysqli_fetch_array($SQLRes)){
            $sub_contract_alerts=$loginResultArray['count'];

        }
      }
	}
	
	$total_messages = $accountsalert+$sub_contract_alerts;
              echo $total_messages."@@@".$accountsalert."@@@".$sub_contract_alerts;
              exit;
}

?>


