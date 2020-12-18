<?php
session_start();
date_default_timezone_set('Asia/Dubai');
if($_SESSION['pr'] == "") {
  $_SESSION['pr'] = $_REQUEST['pr'];
}

require "connection.php";
require "pagingObj.php";
include("functions_service.php");
include("functions_workflow.php");

$grid = new MyPHPGrid('frmPage');
$grid->TableName = "tbl_ticket";
$grid->formName = "calloutticket.php";
$grid->SyncSession($grid);
$grid->TableNameChild = "in_crmtasks";

$currentDateTime=date('H:i:s');
$newDateTime = date("g:i A", strtotime($currentDateTime));
//print_r($_REQUEST);
if($_REQUEST['POST'] =='REVISE'){

		$SQL3 = "select ticketno as docno,suserid,enquiryno from tbl_ticket where id='".$_REQUEST['POSTID']."'";
        $RES3 =  mysql_query($SQL3);
        $ARR3 = mysql_fetch_array($RES3);
		$alert_message = "AMC COT Ticket: ".$ARR3['docno']." Revised by Manager";
		$APPROVAL_users = $ARR3['suserid'];
		
		echo SendAlerts("SERVICE","JOB",$APPROVAL_users,$alert_message);
		echo SendSMS("SERVICE","JOB",$APPROVAL_users,$alert_message);
		echo SendEmail("SERVICE","JOB",$APPROVAL_users,$alert_message,$alert_message); // last 2 are subject and message

        $SQL4 = "UPDATE tbl_ticket SET stcheck='(AMC COT) Completion Revised by Manager',approvalcount=0,posted='NO',post_to_om='NO',
        formsend_date='',post_to_si_date='".date('Y-m-d H:i:s')."' where id='".$_REQUEST['POSTID']."'";
        mysql_query($SQL4);
        
        $SQL5 = "UPDATE in_crmhead SET stcheck='(AMC COT) Completion Revised by Manager' where docno='".$ARR3['enquiryno']."'";
        mysql_query($SQL5);

}


if($_REQUEST['POST'] =='SCREVISE'){
        $status = '(AMC COT) Complaint Revised By SC';
		$SQL3 = "select ticketno as docno,suserid,enquiryno from tbl_ticket where id='".$_REQUEST['POSTID']."'";
        $RES3 =  mysql_query($SQL3);
        $ARR3 = mysql_fetch_array($RES3);
		$alert_message = "AMC COT Ticket: ".$ARR3['docno']." has been Revised by Service Coordinator";
		$APPROVAL_users = $ARR3['suserid'];

		echo SendAlerts("SERVICE","JOB",$APPROVAL_users,$alert_message);
		echo SendSMS("SERVICE","JOB",$APPROVAL_users,$alert_message);
		echo SendEmail("SERVICE","JOB",$APPROVAL_users,$alert_message,$alert_message); // last 2 are subject and message

        $SQL4 = "UPDATE tbl_ticket SET stcheck='$status',post_to_sc='NO',post_to_sc_date='',posted='NO',
        post_to_si_date='".date('Y-m-d H:i:s')."' where id='".$_REQUEST['POSTID']."'";
        mysql_query($SQL4);

        $SQL5 = "UPDATE in_crmhead SET stcheck='$status' where docno='".$ARR3['enquiryno']."'";
        mysql_query($SQL5);

}

if($_REQUEST['POST'] =='SENDTOSC'){
	###workflow###
	$APPROVAL_users = "";
    $checkworkflow = checkforWorkflow("FM","QUOTATION");
    $status = '(AMC COT) Completion under review by Service Coordinator';
	if($checkworkflow == "YES"){
		$Wf_arr = explode("@",GetWorkFlow("FM","QUOTATION"));

		$SQL3 = "select ticketno as docno,enquiryno,servicestaff from tbl_ticket where id='".$_REQUEST['POSTID']."'";
        $RES3 =  mysql_query($SQL3);
        $ARR3 = mysql_fetch_array($RES3);
        $APPROVAL_users = $ARR3['servicestaff'];
		$alert_message = "You got a AMC COT to Approve! DOCNO : ".$ARR3['docno'];
		echo SendAlerts("FM","QUOTATION",$APPROVAL_users,$alert_message);
		echo SendSMS("FM","QUOTATION",$APPROVAL_users,$alert_message);
		echo SendEmail("FM","QUOTATION",$APPROVAL_users,$alert_message,$alert_message); // last 2 are subject and message

		$SQL5 = "UPDATE in_crmhead SET stcheck='$status' where docno='".$ARR3['enquiryno']."'";
        mysql_query($SQL5);
	}
   ### end of workflow###
   $Post_query="Update tbl_ticket set posted='YES',stcheck='$status',post_to_sc='YES',post_to_sc_date='".date('Y-m-d H:i:s')."',post_to_si_date='' where id='". $_REQUEST['POSTID']."'";
   $Post_Result = mysql_query($Post_query)   or die(mysql_error()."<br>".$Post_query);


}

if($_REQUEST['POST'] =='SAE'){
	###workflow###
	$APPROVAL_users = "";
    $checkworkflow = checkforWorkflow("FM","QUOTATION");
    $status='(AMC COT) Completion waiting for Manager Approval';
	if($checkworkflow == "YES"){
		$Wf_arr = explode("@",GetWorkFlow("FM","QUOTATION"));
		$APPROVAL_users = $Wf_arr[1];
    }
		$SQL3 = "select ticketno as docno,enquiryno from tbl_ticket where id='".$_REQUEST['POSTID']."'";
        $RES3 =  mysql_query($SQL3);
        $ARR3 = mysql_fetch_array($RES3);
		$alert_message = "You got a AMC COT to Approve! DOCNO : ".$ARR3['docno'];
		echo SendAlerts("FM","QUOTATION",$APPROVAL_users,$alert_message);
		echo SendSMS("FM","QUOTATION",$APPROVAL_users,$alert_message);
		echo SendEmail("FM","QUOTATION",$APPROVAL_users,$alert_message,$alert_message); // last 2 are subject and message

		$SQL5 = "UPDATE in_crmhead SET stcheck='$status' where docno='".$ARR3['enquiryno']."'";
        mysql_query($SQL5);

   ### end of workflow###
   $Post_query="Update tbl_ticket set post_to_om='YES',stcheck='$status',formsendto='".$APPROVAL_users."',post_to_sc_date='',formsend_date='".date('Y-m-d H:i:s')."' where id='". $_REQUEST['POSTID']."'";
   $Post_Result = mysql_query($Post_query)   or die(mysql_error()."<br>".$Post_query);
   

}
if($_REQUEST['POST'] =='APPROVE'){  // post callout to service coordinator
          $SQL4 = "UPDATE tbl_ticket SET stcheck='Waiting for Inspection Details',post_to_si='YES' where id='".$_REQUEST['POSTID']."'";
          mysql_query($SQL4);
          $SQL3 = "select ticketno as docno,userid,suserid from tbl_ticket where id='".$_REQUEST['POSTID']."'";
          $RES3 =  mysql_query($SQL3);
        $ARR3 = mysql_fetch_array($RES3);
        $alert_message = "You have a new Enquiry: ".$ARR3['docno']." to process";
		//$alert_message = "ENQUIRY NO: ".$ARR3['docno']." has been Approved";
		$APPROVAL_users = $ARR3['userid'];  //.",".$ARR3['suserid']
		echo SendAlerts("FM","QUOTATION",$APPROVAL_users,$alert_message);
		echo SendSMS("FM","QUOTATION",$APPROVAL_users,$alert_message);
		echo SendEmail("FM","QUOTATION",$APPROVAL_users,$alert_message,$alert_message); // last 2 are subject and message
}
if($_REQUEST['POST'] =='REJECTED'){
          $SQL4 = "UPDATE tbl_ticket SET stcheck='Waiting for an Update',approvalcount=0,posted='NO',formsendto='',signatory='' where id='".$_REQUEST['POSTID']."'";
          mysql_query($SQL4);
          $SQL3 = "select ticketno as docno,userid,suserid from tbl_ticket where id='".$_REQUEST['POSTID']."'";
        $RES3 =  mysql_query($SQL3);
        $ARR3 = mysql_fetch_array($RES3);
        $alert_message = "Call Out Request: ".$ARR3['docno']." has been Rejected";
		$APPROVAL_users = $ARR3['userid'];  //.",".$ARR3['suserid']
		echo SendAlerts("FM","QUOTATION",$APPROVAL_users,$alert_message);
		echo SendSMS("FM","QUOTATION",$APPROVAL_users,$alert_message);
		echo SendEmail("FM","QUOTATION",$APPROVAL_users,$alert_message,$alert_message); // last 2 are subject and message
}
if($_REQUEST['POST'] =='APPROVEIT'){
	###workflow###
	$checkworkflow = checkforWorkflow("FM","QUOTATION");
	if($checkworkflow == "YES"){
		$Wf_arr = explode("@",GetWorkFlow("FM","QUOTATION"));
		$APPROVALBY    =  $Wf_arr[3];
		$APPROVALCOUNT    =  $Wf_arr[2];

	     $SQL2 = "UPDATE tbl_ticket SET stcheck='Waiting for Approval', approvalcount=approvalcount+1,approvedby=concat(approvedby,'".$_SESSION['SESSuserID'].",') where id='".$_REQUEST['POSTID']."'";
         mysql_query($SQL2);
        $SQL3 = "select approvalcount from tbl_ticket where id='".$_REQUEST['POSTID']."'";
        $RES3 =  mysql_query($SQL3);
        $ARR3 = mysql_fetch_array($RES3);
        if( $ARR3['approvalcount'] == $APPROVALCOUNT ){
          $SQL4 = "UPDATE tbl_ticket SET stcheck='Approved',converted='YES' where id='".$_REQUEST['POSTID']."'";
          mysql_query($SQL4);

		$SQL3 = "select ticketno as docno,userid,suserid from tbl_ticket where id='".$_REQUEST['POSTID']."'";
        $RES3 =  mysql_query($SQL3);
        $ARR3 = mysql_fetch_array($RES3);
        $alert_message = "You have a new Enquiry: ".$ARR3['docno']." to process";
		//$alert_message = "ENQUIRY NO: ".$ARR3['docno']." has been Approved";
		$APPROVAL_users = $ARR3['userid'];  //.",".$ARR3['suserid']
		echo SendAlerts("FM","QUOTATION",$APPROVAL_users,$alert_message);
		echo SendSMS("FM","QUOTATION",$APPROVAL_users,$alert_message);
		echo SendEmail("FM","QUOTATION",$APPROVAL_users,$alert_message,$alert_message); // last 2 are subject and message
	    }
	}
	else{ // No workflow
    $Post_query="Update tbl_ticket set stcheck='Approved',converted='YES' where id='". $_REQUEST['POSTID']."'";
    $Post_Result = mysql_query($Post_query)   or die(mysql_error()."<br>".$Post_query);
	}
}

if($_REQUEST['POST'] =='CANCEL'){

   $Post_query="Update tbl_ticket set posted='YES',converted='YES',stcheck='Cancelled',docapproveddate='".date('Y-m-d')."',nb='".$_REQUEST['reason']."' where id='". $_REQUEST['POSTID']."'";
   $Post_Result = mysql_query($Post_query)   or die(mysql_error()."<br>".$Post_query);

		$SQL3 = "select ticketno as docno,userid,post_to_si from tbl_ticket where id='".$_REQUEST['POSTID']."'";
        $RES3 =  mysql_query($SQL3);
        $ARR3 = mysql_fetch_array($RES3);
        if($ARR3['post_to_si']=="YES"){
		$alert_message = " ENQUIRY NO: ".$ARR3['docno']." has been Cancelled";
		$APPROVAL_users = $ARR3['userid'];
		echo SendAlerts("FM","QUOTATION",$APPROVAL_users,$alert_message);
		echo SendSMS("FM","QUOTATION",$APPROVAL_users,$alert_message);
        echo SendEmail("FM","QUOTATION",$APPROVAL_users,$alert_message,$alert_message); // last 2 are subject and message
        }
}

$lock = "disabled";
if($_REQUEST['ID'] != "0") {
             $mode=$_REQUEST['ID'];

             $SQL = " Select *,DATE_FORMAT(startdate,'%d-%m-%Y') as startdate,DATE_FORMAT(enddate,'%d-%m-%Y') as enddate,DATE_FORMAT(docdate,'%d-%m-%Y') as docdate,DATE_FORMAT(docapproveddate,'%d-%m-%Y') as docapproveddate from tbl_ticket where id='".$_REQUEST["ID"]."'";;
             $SQLRes =  mysql_query($SQL) or die(mysql_error()."<br>".$SQL);
              if(mysql_num_rows($SQLRes)>=1){
                while($loginResultArray   = mysql_fetch_array($SQLRes)){
                   $mode =  $loginResultArray['id'];
				   #### workflow ####
                   $formsendto = $loginResultArray['formsendto'];
				   $approvedby = $loginResultArray['approvedby'];
				   $approvalrole = $loginResultArray['approvalrole'];
				   $formapprovalcount = $loginResultArray['approvalcount'];
				   ###################
                   $priority=   $loginResultArray['priority'];
                   $enquirycategory =  $loginResultArray['enquirycategory'];
                   $docdate =  $loginResultArray['docdate'];
                   $tentativedate=$loginResultArray['tentativedate'];
                   if($tentativedate=='00-00-0000')$tentativedate="";

                   $docapproveddate=$loginResultArray['docapproveddate'];
                   if($docapproveddate=='00-00-0000')$docapproveddate="";

                   $enddate=$loginResultArray['enddate'];
                   if($enddate=='00-00-0000') $enddate = "";//$enddate=date('d-m-Y');
                   $accountheadcode =  $loginResultArray['objectcode'];
                   $objectname =$loginResultArray['objectname'];
                   $propertyno = $loginResultArray['propertyno'];
                   $customname  =  $loginResultArray['objectname'];
                   $enquirysource =  $loginResultArray['leadsource'];
                   $enquirystatus =  $loginResultArray['leadstatus'];
                   $enquirytype   =  $loginResultArray['enquirytype'];
                   $termsandcondition  =  $loginResultArray['termsandcondition'];
                   $remarks =  $loginResultArray['remarks'];
                   $currency  =  $loginResultArray['foreigncurrencycode'];
                   $exchangerate =  $loginResultArray['exchangerate'];
                   $totaldiscount =  $loginResultArray['totallinedisamt'];
                   $totallinenetamout =  $loginResultArray['totallinenetamt'];
                   $userid = $loginResultArray['userid'];
                   $DocNo = $loginResultArray['ticketno'];
                   $posted = $loginResultArray['posted'];
                   if($posted == "YES") $posted_lock="disabled";
                   else $posted_lock = "";
                   $stcheck = $loginResultArray['stcheck'];
                   $jobname = $loginResultArray['jobname'];
                   $natureofenquiry = $loginResultArray['natureofenquiry'];
                   $enquiryby = $loginResultArray['enquiryby'];
                   $suserid = $loginResultArray['suserid'];
                   $areaincharge = $loginResultArray['areaincharge'];
                   $docname = $loginResultArray['docname'];
                   $paymentterms = str_replace("<br/>","\n",$loginResultArray['paymentterms']) ;
                   $propertycode =$loginResultArray['propertycode'];
                   $buildingname= $loginResultArray['buildingname'];
                   $buildingcode= $loginResultArray['buildingcode'];
                   $floordetails= $loginResultArray['floordetails'];
                   $propertycount= $loginResultArray['propertycount'];
                   $buildingcount= $loginResultArray['buildingcount'];
                   $durationtype = $loginResultArray['durationtype'];
                   $startdate = $loginResultArray['startdate'];
                   $durationnos = $loginResultArray['durationnos'];
                   $post_to_sp = $loginResultArray['post_to_sp'];
                   $post_to_om = $loginResultArray['post_to_om'];
                   $post_to_si = $loginResultArray['post_to_si'];
                   if($post_to_si=="YES") $post_to_si_display = "disabled";
                   else $post_to_si_display="";
                   $company = $loginResultArray['company'];
                   $divisioncode = $loginResultArray['divisioncode'];
                   $contactperson = $loginResultArray['contactperson'];
                   $phonecode1 = $loginResultArray['phonecode1'];
                   $phonecode2 = $loginResultArray['phonecode2'];
                   $projectname = $loginResultArray['projectname'];
                   $contractreference = $loginResultArray['contractreference'];
                   $billingemail = $loginResultArray['billingemail'];
                   if($startdate=="00-00-0000")$startdate="";
                   if($durationtype==""){
                       $lock="disabled";
                   }else{
                       $lock="";
                   }
                   $enquirythrough = $loginResultArray['enquirythrough'];
                   $externalinfo = $loginResultArray['externalinfo'];
                   $enquirystaff  = $loginResultArray['enquirystaff'];
                   if( $enquirythrough == "Others") {
                       $div_external_display = "block";
                       $div_staff_display = "none";
                       }
                   else if($enquirythrough == "Sales Staff") {
                       $div_external_display = "none";
                       $div_staff_display = "block";
                       }
                   else {
                       $div_external_display = "none";
                       $div_staff_display = "none";
                       }
                   $converted =  $loginResultArray['converted'];
                   $addtotalgrossamt=  $loginResultArray['addtotalgrossamt'];
                   $secomments=  $loginResultArray['secomments'];
                   $docname2=$loginResultArray['docname2'];
                   $nb = $loginResultArray['nb'];
                   if($stcheck=='Completed' || $stcheck=='SR Received'){
                    $displayatt="table-row" ;
                   }else{
                    $displayatt="none" ;
                   }
                   if($nb!=''){
                    $displayreason="table-row" ;
                   }else{
                    $displayreason="none" ;
                   }
                   $servicejob =  $loginResultArray['servicejob'];
                   $mySQL = "SELECT * FROM in_crmline WHERE invheadid='".$_REQUEST['ID']."' and  servicejob='$servicejob'";
                   $myRes = mysql_query($mySQL);
                   if(mysql_num_rows($myRes)>=1){
                           $servicelock = "disabled";
                   }
                   else $servicelock = "";
                   $worksubject = $loginResultArray['worksubject'];
                   $calloutreferenceid =  $loginResultArray['calloutreferenceid'];
                   $calloutjobnoreference =  $loginResultArray['calloutjobnoreference'];
                  // $jobno = $loginResultArray['jobno'];
                   $starttime = $loginResultArray['starttime'];
                   $endtime = $loginResultArray['endtime'];
                   $siteincharge = $loginResultArray['siteincharge'];
                   $underamc =  $loginResultArray['underamc'];
                   $technician = $loginResultArray['technician'];
                   $calloutstatus = $loginResultArray['calloutstatus'];
                   $post_to_sc = $loginResultArray['post_to_sc'];
                   $servicestaff = $loginResultArray['servicestaff'];

                   /*if($calloutstatus=="Convert to OT" || $calloutstatus=="Convert to AMC COT")
                   $hide_mandatory ="none";
                   else  */
                   $hide_mandatory ="block";
                   $post_to_sc = $loginResultArray['post_to_sc'];
                   
                   if( $underamc == "No") {
                       $div_addcustomername = "block";
                       $div_projectname_1 ='none';
                       $div_projectname_2 ='block';
                       $div_property_1 = 'none';
                       $div_property_2 = 'block';
                       $div_propertyno_1 = 'none';
                       $div_propertyno_2 = 'block';
                       $div_propertyname_1 = 'none';
                       $div_propertyname_2 = 'block';
                       $div_floor_1 = 'none';
                       $div_floor_2 = 'block';
                   }
                   else  {
                       $div_addcustomername = "none";
                       $div_projectname_2 ='none';
                       $div_projectname_1 = 'block';
                       $div_property_2 = 'none';
                       $div_property_1 = 'block';
                       $div_propertyno_1 = 'block';
                       $div_propertyno_2 = 'none';
                       $div_propertyname_2 = 'none';
                       $div_propertyname_1 = 'block';
                       $display_floordetails = 'disabled';
                       $div_floor_2 = 'none';
                       $div_floor_1 = 'block';
                   }
                }
              }
           }else{
             $displayreason="none" ;
             $displayatt="none";
             $mode="";
             $saveid = GetLastSqeID("tbl_ticket");
             //$enquirycategory = $_REQUEST['cmb_lookuplist'];
             $enquirycategory = "AMC CALLOUT";
             $company = $_REQUEST['cmb_lookuplist1'];
             $exchangerate=1;
             $seqNum = GetLastSqeID_new("ticket");
             //$Doc_Type = "C".substr($enquirycategory,0,3)."/";
             //$DocNo = $Doc_Type.str_pad($seqNum, 5, '0', STR_PAD_LEFT)."/".date("Y");
             $DocNo = "CM-".$seqNum;
             $docdate = date("d-m-Y");
             $enquiryby = $_SESSION['SESSuserID'];
             $accountheadcode = 0;
             if($_REQUEST['partycode']!=""){
                $accountheadcode= $_REQUEST['partycode'];
                $customname = $_REQUEST['partyname'];
                $paymentterms = $_REQUEST['payterms'];
             }
             $propertycount = 0;
             $buildingcount =0;
             $div_staff_display = "none";
             $div_external_display = "none";
             $div_addcustomername = "none";
             $div_projectname_2 ='none';
             $div_property_2 = 'none';
             $div_propertyno_2 = 'none';
             $div_propertyname_2 = 'none';
             $div_floor_2 = 'none';
}

if($_REQUEST['dr']=='view'){
      $edit="none";
      $view="inline";
      $title="$enquirycategory : $DocNo";
}else if($_REQUEST['dr']=='edit'){
      $edit="inline";
      $view="none";
      $title="$enquirycategory : $DocNo";
}else{
      $edit="inline";
      $view="none";
      $title="$enquirycategory";
}

?>
<!DOCTYPE html>
<html>
   <head>
      <meta charset="utf-8">
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <title>Reradius | Dashboard</title>
      <!-- Tell the browser to be responsive to screen width -->
      <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
      <!-- Bootstrap 3.3.6 -->
      <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.css">

      <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
      <!-- Font Awesome -->
      <link rel="stylesheet" href="css/font-awesome.min.css">
      <link rel="stylesheet" href="css/ionicons.min.css">
      <link rel="stylesheet" href="plugins/select2/select2.min.css">
      <link rel="stylesheet" href="plugins/iCheck/all.css">
      <link rel="stylesheet" href="dist/css/mainStyles.css">
      <link rel="stylesheet" href="dist/css/styles.css">
      <link rel="stylesheet" type="text/css" href="childtable_css/style.css" />
      <link rel="stylesheet" href="css/alertify.core.css" />
      <link rel="stylesheet" href="css/alertify.default.css" id="toggleCSS" />
      <link rel="stylesheet" href="bootstrap/css/datepicker.css">
      <script src="js/html5shiv.min.js"></script>
      <script src="js/respond.min.js"></script>
      <script src="js/alertify.min.js"></script>
      <script src="bootstrap/js/jquery-1.9.1.min.js"></script>
      <script src="bootstrap/js/bootstrap-datepicker.js"></script>
      <script type="text/javascript" src="js/ajax_functions.js"></script>
      <script type="text/javascript" src="js/lib.js"></script>
      <script type="text/javascript" src="js/injs.js"></script>
      <script type="text/javascript" src="js/myjs.js"></script>
      <script type="text/javascript" src="js/timepicker.js"></script>
      <link rel="stylesheet" type="text/css" media="screen" href="css/my_styles2.css">
      <link rel="stylesheet" type="text/css" media="screen" href="css/my_styles1.css">
<style type="text/css">
#overlay
{
        position:absolute;
        width:100%;
        height:100%;
}
#overlay img {
    display: block;
    margin-left: auto;
    margin-right: auto;
}
</style>
<script src="js/alertify.min.js"></script>
<script type="text/javascript" src="js/ajax_functions.js"></script>
<script type="text/javascript" src="js/lib.js"></script>
<script type="text/javascript" src="js/injs.js"></script>
<script type="text/javascript" src="js/myjs.js"></script>
<script language="javascript">
function ShowMandatory(cattype) {
         if(cattype == 'Convert to OT' || cattype == 'Convert to AMC COT'){
                    var y  = document.getElementsByClassName('hide_mandatory');
                    for (i = 0; i < y.length; i++) {
                    y[i].style.display = "none";
                    }
         }
         else{
                    var y  = document.getElementsByClassName('hide_mandatory');
                    for (i = 0; i < y.length; i++) {
                    y[i].style.display = "block";
                    }
         }
}

function showUnderAMC_fields(catval){
     /*    if(catval=='No'){
            document.getElementById('div_floor_1').style.display ='none';
            document.getElementById('div_floor_2').style.display ='block';
            document.getElementById('div_addcustomername').style.display ='block';
            document.getElementById('div_projectname_1').style.display ='none';
            document.getElementById('div_projectname_2').style.display ='block';
            document.getElementById('div_property_1').style.display ='none';
            document.getElementById('div_property_2').style.display ='block';
            document.getElementById('div_propertyno_1').style.display ='none';
            document.getElementById('div_propertyno_2').style.display ='block';
            document.getElementById('div_propertyname_1').style.display ='none';
            document.getElementById('div_propertyname_2').style.display ='block';

         }
         else{
             document.getElementById('div_addcustomername').style.display ='none';
             document.getElementById('div_projectname_1').style.display ='block';
             document.getElementById('div_projectname_2').style.display ='none';
             document.getElementById('div_property_1').style.display ='block';
             document.getElementById('div_property_2').style.display ='none';
             document.getElementById('div_propertyno_1').style.display ='block';
             document.getElementById('div_propertyno_2').style.display ='none';
             document.getElementById('div_propertyname_1').style.display ='block';
             document.getElementById('div_propertyname_2').style.display ='none';
             document.getElementById('div_floor_1').style.display ='block';
            document.getElementById('div_floor_2').style.display ='none';
         }    */
}
function getDivision(cattype){

      xmlHttp=GetXmlHttpObject()
      if (xmlHttp==null)
      {
                 alert ("Browser does not support HTTP Request")
                 return
      }

      var url="combofunctions_crm2.php?level=divcenter&categorytype="+cattype;
      xmlHttp.onreadystatechange=stateChangedcombo8
      xmlHttp.open("POST",url,true)
      xmlHttp.send(null)

}

function stateChangedcombo8(){
       if (xmlHttp.readyState==4 || xmlHttp.readyState=="complete")
       {
             var s1 = trim(xmlHttp.responseText);
             document.getElementById('cmb_A_divisioncode').innerHTML=s1;
       }
}
function AllowNumeric1(objEvent){
            var iKeyCode;
            if(window.event){
               iKeyCode = objEvent.keyCode;
            }
            else if(objEvent.which){
                  iKeyCode = objEvent.which;
            }

             if((iKeyCode<=1 && iKeyCode>=7) || (iKeyCode>=9 && iKeyCode<45 && iKeyCode!=17) || (iKeyCode>=58 && iKeyCode<=255 && iKeyCode!=118)){
                if (iKeyCode!=13) {
                    alertify.error('Numbers Only');
                     return false;
                }
            }
            return true;

}
function Postrecord(postid){
    alertify.confirm("Are you sure you want to Post ?", function (e) {
         if (e) {
           $('#overlay').show();
           $('#personal').hide();
           document.frmEdit.action='editcalloutticket.php?POST=FREEZE&POSTID='+postid+'&ID='+document.getElementById('mode').value+'&dr=edit';
           document.frmEdit.submit();
         } else {
            return;
         }

    });
}

function sendapproval_enquiry(postid){
     var cmb_A_calloutstatus=document.getElementById('cmb_A_calloutstatus');
     if(cmb_A_calloutstatus){
             if ((cmb_A_calloutstatus.value=="Select")||(cmb_A_calloutstatus.value=="")){
                  alertify.alert("Select Callout status", function () {
                  cmb_A_calloutstatus.focus();

             });
                return;
             }
    }
   var calloutstatus = cmb_A_calloutstatus.value;

   if(calloutstatus == "(AMC COT) Complaint closed")  {
    var covtitle = "Are you sure you want to sent for approval ?";
     alertify.confirm(covtitle, function (e) {
         if (e) {

           document.frmEdit.action='editcalloutticket.php?POST=SENDTOSC&POSTID='+postid+'&ID='+document.getElementById('mode').value+'&dr=edit';
           document.frmEdit.submit();
           
           
         } else {
            return;
         }

    });
    
    }
    else
    {
            if(calloutstatus == "Convert to OT")
            var covtitle = "Are you sure you want to convert into OT Enquiry ?";
            else
            var covtitle = "Are you sure you want to convert into EMG COT Enquiry ?";
          var encategory= document.getElementById('cmb_A_enquirycategory').value;
          
          alertify.confirm(covtitle, function (e) {
         if (e) {
           $('#overlay').show();
           $('#personal').hide();
           convertfunction('parenttype=AMC CALLOUT&doctype=LEAD&lid='+postid+'&TYPE='+encategory+'&calloutstatus='+calloutstatus);
         } else {
            return;
         }

        });
    
    }
}
function sendapproval_manager(postid){
    alertify.confirm("Are you sure you want to sent for approval ?", function (e) {
         if (e) {

           document.frmEdit.action='editcalloutticket.php?POST=SAE&POSTID='+postid+'&ID='+document.getElementById('mode').value+'&dr=edit';
           document.frmEdit.submit();
         } else {
            return;
         }

    });
}
function approveit(postid){

       alertify.confirm("Are you sure you want to approve ?", function (e) {
         if (e) {
           document.frmEdit.action='editcalloutticket.php?POST=APPROVEIT&POSTID='+postid+'&ID='+document.getElementById('mode').value+'&dr=edit';
           document.frmEdit.submit();
         } else {
            return;
         }

    });
}
function approve(postid){

       alertify.confirm("Are you sure you want to approve ?", function (e) {
         if (e) {
           document.frmEdit.action='editcalloutticket.php?POST=APPROVE&POSTID='+postid+'&ID='+document.getElementById('mode').value+'&dr=edit';
           document.frmEdit.submit();
         } else {
            return;
         }

    });
}

function AddItem(){

            document.getElementById('childid').value="";
            var tr2=document.getElementById('tr2');
            tr2.style.display="table-row";
            var tr22=document.getElementById('tr22');
            tr22.style.display="table-row";
            var tr222=document.getElementById('tr222');
            tr222.style.display="table-row";

}
function converttoEMG_or_OT(id){

   var encategory= document.getElementById('cmb_A_enquirycategory').value;
   var calloutstatus= document.getElementById('cmb_A_calloutstatus').value;
   if(calloutstatus == "Convert to OT")
   var covtitle = "Are you sure you want to convert into OT Enquiry ?";
   else if(calloutstatus == "(AMC COT) Complaint closed")
   var covtitle = "Are you sure you want to Approve ?";
   else
   var covtitle = "Are you sure you want to convert into EMG COT Enquiry ?";

   alertify.confirm(covtitle, function (e) {
         if (e) {
           $('#overlay').show();
           $('#personal').hide();
           convertfunction('parenttype=AMC CALLOUT&doctype=LEAD&lid='+id+'&TYPE='+encategory+'&calloutstatus='+calloutstatus);
         } else {
            return;
         }

    });
}
function backtopage(id){

    window.location.href="editcrmcontactlist.php?dr=edit&ID="+id;
}
function popupcustomer(){

                 var search = window.document.frmEdit.txt_A_objectname.value;
                 var v1 ='transpopupleadcrm.php?film=filmReceipt&id=0&s='+search;
                 window.open(v1,'name',' left=0,height=370,width=950,left=200,top=300,resizable=no,scrollbars=yes,toolbar=no,status=yes');
}
function clearcustomer(){
         document.getElementById('txt_A_objectcode').value="";
         document.getElementById('txt_A_objectname').value="";
         document.getElementById('txa_A_paymentterms').value="";

}
function updateChildrecord(childid){

    document.frmEdit.action='editcalloutticket.php?CHILDID='+childid+'&ID='+document.getElementById('txt_A_enquiryid').value;
    document.frmEdit.submit();
}
function PosttoSalesPerson(){
         document.frmChildEdit.action = 'editcalloutticket.php?POST=PTSP&ID='+document.getElementById('txt_A_enquiryid').value;
         document.frmChildEdit.submit();

}
function canceleditingChildrecord(){

    document.frmChildEdit.action = 'editcalloutticket.php?ID='+document.getElementById('txt_A_enquiryid').value;
    document.frmChildEdit.submit();
}
function deleteChildrecord(childid){

         alertify.confirm("Are you sure you want to delete ?", function (e) {
         if (e) {
           document.frmChildEdit.action='editcalloutticket.php?dr=edit&DEL=DELETE&CHILDID='+childid+'&ID='+document.getElementById('txt_A_enquiryid').value;
           document.frmChildEdit.submit();
         } else {
            return;
         }

       });

}
function postChildrecord(childid){

         alertify.confirm("Are you sure you want to post ?", function (e) {
         if (e) {

           document.frmChildEdit.action='editcalloutticket.php?dr=edit&POST=POSTCHILD&POSTCHILDID='+childid+'&ID='+document.getElementById('txt_A_enquiryid').value;
           document.frmChildEdit.submit();
         } else {
            return;
         }

       });

}
function Cancelrecord(postid){
   var tr1=document.getElementById('tr1');
   tr1.style.display="table-row";
      var txt_A_nb=document.getElementById('txt_A_nb');
       if(txt_A_nb){
             if ((txt_A_nb.value==null)||(txt_A_nb.value=="")){
                  alertify.alert("Enter reason for cancel", function () {
                  txt_A_nb.focus();

             });
                return;
             }
       }
    alertify.confirm("Are you sure you want to cancel this enquiry?", function (e) {
         if (e) {
           $('#overlay').show();
           $('#personal').hide();
           document.frmEdit.action='editcalloutticket.php?POST=CANCEL&reason='+txt_A_nb.value+'&POSTID='+postid+'&ID='+document.getElementById('mode').value;
           document.frmEdit.submit();
         } else {
            return;
         }

    });
}

function cancleeditingnew(){

    document.frmEdit.action='calloutticket.php?frmPage_startrow='+document.getElementById('recordstartrow').value+'&frmPage_rowcount='+document.getElementById('recordperpage').value+'&txtsearch='+document.getElementById('searchvalue').value;
    document.frmEdit.submit();

}
function completequote(docid){
   var tr11=document.getElementById('tr11');
   tr11.style.display="table-row";
      var userfile3=document.getElementById('userfile3');
       if(userfile3){
             if ((userfile3.value==null)||(userfile3.value=="")){
                  alertify.alert("Attachment required!", function () {
                  userfile3.focus();

             });
                return;
             }
       }
        alertify.confirm("Are you sure you want to complete the enquiry?", function (e) {
         if (e) {
           $('#overlay').show();
           $('#personal').hide();
           document.getElementById('frmEdit').action='editcalloutticket.php?MODE=COMPLETE&ID='+docid;
           document.getElementById('frmEdit').submit();
         } else {
            return;
         }

    });




}

function editingrecord(action)
{
       var cmb_A_objectcode=document.getElementById('cmb_A_objectcode');
       if(cmb_A_objectcode){
             if ((cmb_A_objectcode.value==null)||(cmb_A_objectcode.value=="")){
                  alertify.alert("Select Customer Name", function () {
                  cmb_A_objectcode.focus();

             });
                return;
             }
       }
       
       var cmb_A_projectname=document.getElementById('cmb_A_projectname');
       if(cmb_A_projectname){
             if ((cmb_A_projectname.value==null)||(cmb_A_projectname.value=="")){
                  alertify.alert("Select Project name", function () {
                  cmb_A_projectname.focus();

             });
                return;
             }
       }

       var txd_A_docdate=document.getElementById('txd_A_docdate');
       if(txd_A_docdate){
          if ((txd_A_docdate.value=="00-00-0000")||(txd_A_docdate.value=="")){
               alertify.alert("Enter Complaint Received Date ", function () {
               txd_A_docdate.focus();

          });
             return;
          }
       }

       var txt_A_contactperson=document.getElementById('txt_A_contactperson');
       if(txt_A_contactperson){
             if ((txt_A_contactperson.value==null)||(txt_A_contactperson.value=="")){
                  alertify.alert("Enter Contact Person", function () {
                  txt_A_contactperson.focus();

             });
                return;
             }
       }

       var txt_A_phonecode1=document.getElementById('txt_A_phonecode1');
       if(txt_A_phonecode1){
             if ((txt_A_phonecode1.value==null)||(txt_A_phonecode1.value=="")){
                  alertify.alert("Enter Contact Number 1", function () {
                  txt_A_phonecode1.focus();

             });
                return;
             }
       }

       var cmb_A_propertycode=document.getElementById('cmb_A_propertycode');
             if(cmb_A_propertycode){
             if ((cmb_A_propertycode.value=="Select")||(cmb_A_propertycode.value=="")){
                  alertify.alert("Enter Property Type", function () {
                  cmb_A_propertycode.focus();

             });
                return;
             }
       }

       var cmb_A_buildingcode=document.getElementById('cmb_A_buildingcode');
             if(cmb_A_buildingcode){
             if ((cmb_A_buildingcode.value=="Select")||(cmb_A_buildingcode.value=="")){
                  alertify.alert("Enter Property Name", function () {
                  cmb_A_buildingcode.focus();

             });
                return;
             }
       }
       /*
       var txt_A_natureofenquiry=document.getElementById('txt_A_natureofenquiry');
       if(txt_A_natureofenquiry){
             if ((txt_A_natureofenquiry.value==null)||(txt_A_natureofenquiry.value=="")){
                  alertify.alert("Enter Nature of Enquiry", function () {
                  txt_A_natureofenquiry.focus();

             });
                return;
             }
       }
       */

       var txt_A_jobname=document.getElementById('txt_A_jobname');
       if(txt_A_jobname){
             if ((txt_A_jobname.value==null)||(txt_A_jobname.value=="")){
                  alertify.alert("Enter Work Details", function () {
                  txt_A_jobname.focus();

             });
                return;
             }
       }

       var cmb_A_suserid=document.getElementById('cmb_A_suserid');
           if(cmb_A_suserid){
             if ((cmb_A_suserid.value=="Select")||(cmb_A_suserid.value=="")){
                  alertify.alert("Select Site Incharge", function () {
                  cmb_A_suserid.focus();

             });
                return;
             }
       }

       var cmb_A_servicejob=document.getElementById('cmb_A_servicejob');
           if(cmb_A_servicejob){
             if ((cmb_A_servicejob.value=="Select")||(cmb_A_servicejob.value=="")){
                  alertify.alert("Select Service Type", function () {
                  cmb_A_servicejob.focus();

             });
                return;
             }
       }

       var txt_A_worksubject=document.getElementById('txt_A_worksubject');
           if(txt_A_worksubject){
             if (txt_A_worksubject.value==""){
                  alertify.alert("Enter Work Subject", function () {
                  txt_A_worksubject.focus();

             });
                return;
             }
       }
       

       var txt_A_starttime=document.getElementById('txt_A_starttime');
       if(txt_A_starttime){
          if ((txt_A_starttime.value==null)||(txt_A_starttime.value=="")){
               alertify.alert("Enter Start time", function () {
               txt_A_starttime.focus();

          });
             return "false";
          }

       }

       var txt_A_endtime=document.getElementById('txt_A_endtime');
       if(txt_A_endtime){
          if ((txt_A_endtime.value==null)||(txt_A_endtime.value=="")){
               alertify.alert("Enter End time", function () {
               txt_A_endtime.focus();

          });
             return "false";
          }

       }

       var cmb_A_technician=document.getElementById('cmb_A_technician');
       if(cmb_A_technician){
             if ((cmb_A_technician.value==null)||(cmb_A_technician.value=="")){
                  alertify.alert("Select Technician", function () {
                  cmb_A_technician.focus();

             });
                return;
             }
       }



       var txd_A_closeddate=document.getElementById('txd_A_enddate');
       if(txd_A_closeddate){
          if ((txd_A_closeddate.value=="00-00-0000")||(txd_A_closeddate.value=="")){
               alertify.alert("Enter Complaint Closed Date ", function () {
               txd_A_closeddate.focus();

          });
             return;
          }
       }

       var x1 = document.getElementsByClassName('btn btn-success inputs');
       var i;
       for (i = 0; i < x1.length; i++) {
       x1[i].disabled = true;
       }
       //document.getElementById('btnwarning').disabled=true;
       document.getElementById('btndanger').disabled=true;

       document.getElementById('frmEdit').action='in_action_crm.php?action='+action;
       document.getElementById('frmEdit').submit();
       return;
}
                   var xmlHttp
                   function convertfunction(parameters)
                   {
                          // alert(parameters);
                          xmlHttp=GetXmlHttpObject()
                          if (xmlHttp==null)
                          {
                                 alert ("Browser does not support HTTP Request")
                                 return
                          }


                          var url="Convert_to_ot.php?"+parameters
                          xmlHttp.onreadystatechange=stateChanged
                          xmlHttp.open("POST",url,true)
                          xmlHttp.send(null)
                   }
                   function stateChanged()
                   {
                         if (xmlHttp.readyState==4 || xmlHttp.readyState=="complete")
                         {
                                var s1 = trim(xmlHttp.responseText);
                                //alert(s1);
                               // document.frmEdit.action='editcalloutticket.php?frmPage_startrow='+document.getElementById('recordstartrow').value+'&frmPage_rowcount='+document.getElementById('recordperpage').value+'&txtsearch='+document.getElementById('searchvalue').value+'&dr=edit&ID='+document.getElementById('mode').value;
                               document.frmEdit.action='calloutticket.php';
                                document.frmEdit.submit();

                         }
                   }
                   function insertfunction(parameters,action)
                   {

                          xmlHttp=GetXmlHttpObject()
                          if (xmlHttp==null)
                          {
                                 alert ("Browser does not support HTTP Request")
                                 return
                          }


                          var url="in_action_crm.php"+parameters
                          if(action=='save'){

                            xmlHttp.onreadystatechange=stateChangedsave
                          }
                          if(action=='savenew'){
                            xmlHttp.onreadystatechange=stateChangedsavenew
                          }
                          if(action=='saveclose'){

                            xmlHttp.onreadystatechange=stateChangedsaveclose
                          }
                          xmlHttp.open("POST",url,true)
                          xmlHttp.send(null)
                   }
                   function stateChangedsave()
                   {
                         if (xmlHttp.readyState==4 || xmlHttp.readyState=="complete")
                         {
                               var s1 = trim(xmlHttp.responseText);

                               var s2 = "Record Saved";
                               var s3 = "Record Updated";
                               if(s1.toString() == s2.toString()){
                                 alertify.alert("Record Saved", function () {
                                 window.location.href='editcalloutticket.php?frmPage_startrow='+document.getElementById('recordstartrow').value+'&frmPage_rowcount='+document.getElementById('recordperpage').value+'&txtsearch='+document.getElementById('searchvalue').value+'&dr=edit&ID='+document.getElementById('saveid').value;
                                });
                               }else if(s1.toString() == s3.toString()){
                                alertify.alert("Record Updated", function () {
                                window.location.href='editcalloutticket.php?frmPage_startrow='+document.getElementById('recordstartrow').value+'&frmPage_rowcount='+document.getElementById('recordperpage').value+'&txtsearch='+document.getElementById('searchvalue').value+'&dr=edit&ID='+document.getElementById('mode').value;

                               });


                               }else{
                                alertify.error(s1);
                               }
                         }
                   }
                    function stateChangedsavenew()
                   {
                         if (xmlHttp.readyState==4 || xmlHttp.readyState=="complete")
                         {
                               var s1 = trim(xmlHttp.responseText);

                               var s2 = "Record Saved";
                               var s3 = "Record Updated";
                               if(s1.toString() == s2.toString()){
                                 alertify.alert("Record Saved", function () {
                                 window.location.href='editcalloutticket.php?frmPage_startrow='+document.getElementById('recordstartrow').value+'&frmPage_rowcount='+document.getElementById('recordperpage').value+'&txtsearch='+document.getElementById('searchvalue').value+'&dr=add&ID=0';
                                });
                               }else if(s1.toString() == s3.toString()){
                                alertify.alert("Record Updated", function () {
                                window.location.href='editcalloutticket.php?frmPage_startrow='+document.getElementById('recordstartrow').value+'&frmPage_rowcount='+document.getElementById('recordperpage').value+'&txtsearch='+document.getElementById('searchvalue').value+'&dr=add&ID=0';

                               });


                               }else{
                                alertify.error(s1);
                               }
                         }
                   }
                    function stateChangedsaveclose()
                   {

                         if (xmlHttp.readyState==4 || xmlHttp.readyState=="complete")
                         {
                               var s1 = trim(xmlHttp.responseText);

                               var s2 = "Record Saved";
                               var s3 = "Record Updated";
                               if(s1.toString() == s2.toString()){
                                alertify.alert('Record Saved');
                                window.location.href='calloutticket.php?ID=0';
                               }else if(s1.toString() == s3.toString()){
                                alertify.alert('Record Updated');
                                window.location.href='calloutticket.php';
                               }else{
                                alertify.error(s1);
                               }
                         }
                   }
                  function GetXmlHttpObject()
                   {
                   var xmlHttp=null;
                   try
                   {
                   // Firefox, Opera 8.0+, Safari
                   xmlHttp=new XMLHttpRequest();
                   }
                   catch (e)
                   {
                   //Internet Explorer
                   try
                   {
                   xmlHttp=new ActiveXObject("Msxml2.XMLHTTP");
                   }
                   catch (e)
                   {
                   xmlHttp=new ActiveXObject("Microsoft.XMLHTTP");
                   }
                   }
                   return xmlHttp;
                   }


function loadframe(ext,docname){

                var strURL="combofunctions_pro.php?level=purchasedoc&docname="+docname+"&ext="+ext;

                var req = getXMLHTTP();

                if (req) {

                        req.onreadystatechange = function() {
                                if (req.readyState == 4) {
                                        // only if "OK"
                                        if (req.status == 200) {

                                                document.getElementById('popupdiv').innerHTML=req.responseText;
                                        } else {
                                                alert("Problem while using XMLHTTP:\n" + req.statusText);
                                        }
                                }
                        }
                        req.open("GET", strURL, true);
                        req.send(null);
                }

}
function getXMLHTTP() { //fuction to return the xml http object
                var xmlhttp=false;
                try{
                        xmlhttp=new XMLHttpRequest();
                }
                catch(e)        {
                        try{
                                xmlhttp= new ActiveXObject("Microsoft.XMLHTTP");
                        }
                        catch(e){
                                try{
                                xmlhttp = new ActiveXObject("Msxml2.XMLHTTP");
                                }
                                catch(e1){
                                        xmlhttp=false;
                                }
                        }
                }

                return xmlhttp;
}
function updatedate(postid){
      var txd_A_deliverydate=document.getElementById('txd_A_deliverydate');
       if(txd_A_deliverydate){
          if ((txd_A_deliverydate.value=="")||(txd_A_deliverydate.value==null)){
               alertify.alert("Enter Expected Closing Date", function () {
               txd_A_deliverydate.focus();

          });
             return;
          }
       }
      alertify.confirm("Are you sure you want to update closing date ?", function (e) {
         if (e) {
           document.frmEdit.action='editcalloutticket.php?POST=UPDATEDATE&closedate='+txd_A_deliverydate.value+'&POSTID='+postid+'&ID='+document.getElementById('mode').value;
           document.frmEdit.submit();
         } else {
            return;
         }

    });
}

function getFloorDetails(cattype){
       xmlHttp=GetXmlHttpObject()
      if (xmlHttp==null)
      {
                 alert ("Browser does not support HTTP Request")
                 return
      }

      var url="combofunctions_crm2.php?level=floorinfo&calloutreferenceid="+document.getElementById('txt_A_calloutreferenceid').value+"&propertycode="+document.getElementById('cmb_A_propertycode').value+"&buildingcode="+cattype;
      xmlHttp.onreadystatechange=stateChanged1122
      xmlHttp.open("POST",url,true)
      xmlHttp.send(null)
}
function stateChanged1122(){
       if (xmlHttp.readyState==4 || xmlHttp.readyState=="complete")
       {

             var s1 = trim(xmlHttp.responseText);
             var word = s1.split('@@@@');
             document.getElementById('txt_A_floordetails').value=word[0];
             document.getElementById('txt_A_buildingname').value=word[1];
             document.getElementById('lablebuildingcode').value=document.getElementById('cmb_A_buildingcode').value;
       }
}
function getbuilding(cattype){
       xmlHttp=GetXmlHttpObject()
      if (xmlHttp==null)
      {
                 alert ("Browser does not support HTTP Request")
                 return
      }

      var url="combofunctions_crm2.php?level=getbuildingInfo_CO&calloutreferenceid="+document.getElementById('txt_A_calloutreferenceid').value+"&propertycode="+cattype;
      xmlHttp.onreadystatechange=stateChangedcombobuilding
      xmlHttp.open("POST",url,true)
      xmlHttp.send(null)
}
function stateChangedcombobuilding(){
       if (xmlHttp.readyState==4 || xmlHttp.readyState=="complete")
       {

             var s1 = trim(xmlHttp.responseText);
             document.getElementById('cmb_A_buildingcode').innerHTML=s1;

       }
}
function getCustomerInfo(catval){
      xmlHttp=GetXmlHttpObject()
      if (xmlHttp==null)
      {
                 alert ("Browser does not support HTTP Request")
                 return
      }
      var division = document.getElementById('cmb_A_divisioncode').value;
      var company = document.getElementById('txt_A_company').value;
      var url="combofunctions_crm2.php?level=Customer_project&categorytype="+catval+"&division="+division+"&company="+company;
      xmlHttp.onreadystatechange=stateChangedProperty
      xmlHttp.open("POST",url,true)
      xmlHttp.send(null)
}
function stateChangedProperty(){
       if (xmlHttp.readyState==4 || xmlHttp.readyState=="complete")
       {

             var s1 = trim(xmlHttp.responseText);
             document.getElementById('cmb_A_projectname').innerHTML=s1;

       }
}

function getContactDetailsoofProject(catval){
      xmlHttp=GetXmlHttpObject()
      if (xmlHttp==null)
      {
                 alert ("Browser does not support HTTP Request")
                 return
      }
      var client = document.getElementById('cmb_A_objectcode').value;
      var division = document.getElementById('cmb_A_divisioncode').value;
      var company = document.getElementById('txt_A_company').value;
      var url="combofunctions_crm2.php?level=project_contactdetails&categorytype="+catval+"&division="+division+"&company="+company+"&client="+client;
      xmlHttp.onreadystatechange=stateChangedProjectContact
      xmlHttp.open("POST",url,true)
      xmlHttp.send(null)
}
function stateChangedProjectContact(){
       if (xmlHttp.readyState==4 || xmlHttp.readyState=="complete")
       {

             var s1 = trim(xmlHttp.responseText);
             var word = s1.split('***');
             document.getElementById('txt_A_contactperson').value=word[0];
             document.getElementById('txt_A_phonecode1').value=word[1];
             document.getElementById('txt_A_phonecode2').value=word[2];
             document.getElementById('txt_A_billingemail').value=word[3];
             document.getElementById('cmb_A_propertycode').innerHTML=word[4];
             document.getElementById('txt_A_calloutreferenceid').value=word[5];
             document.getElementById('txt_A_calloutjobnoreference').value=word[6];
            // document.getElementById('txt_A_jobno').value=word[6];
       }
}


function getDurationNos(cattype){
       if(cattype!=""){
          document.getElementById('txt_A_durationnos').disabled=false;
       }else{
          document.getElementById('txt_A_durationnos').value="";
          document.getElementById('txt_A_durationnos').disabled=true;
       }

}
function getEnquiry_Through(cattype) {
         if(cattype.value== "Others"){
              document.getElementById('div_enquirythrough').style.display="block";
              document.getElementById('div_enquirythrough2').style.display="block";
             // document.getElementById('div_enquirythrough3').style.display="none";
             // document.getElementById('div_enquirythrough4').style.display="none";
         }
         else{
              document.getElementById('div_enquirythrough').style.display="none";
              document.getElementById('div_enquirythrough2').style.display="none";
             // document.getElementById('div_enquirythrough3').style.display="none";
             // document.getElementById('div_enquirythrough4').style.display="none";
         }
}
function print1(showprice){
  var url = 'doccotreport1.php?rid=999&showprice='+showprice+'&id='+document.getElementById('mode').value+'&txt_A_formtype=TICKET';
  window.open(url,'location=yes,height=570,width=520,scrollbars=yes,status=yes');
}
function managerreject(postid,parentdocno){
       alertify.confirm("Are you sure you want to Reject ?", function (e) {
         if (e) {
           document.frmEdit.action='editcalloutticket.php?POST=REJECTED&POSTID='+postid+'&ID='+document.getElementById('mode').value;
           document.frmEdit.submit();
         } else {
            return;
         }

    });
}
function managerrevise(postid){
    alertify.confirm("Are you sure you want to Revise?", function (e) {
         if (e) {
           $('#overlay').show();
           $('#personal').hide();
           document.frmEdit.action='editcalloutticket.php?POST=REVISE&POSTID='+postid+'&ID='+document.getElementById('mode').value;
           document.frmEdit.submit();
         } else {
            return;
         }

    });
}
function screvise(postid){
    alertify.confirm("Are you sure you want to Revise?", function (e) {
         if (e) {
           $('#overlay').show();
           $('#personal').hide();
           document.frmEdit.action='editcalloutticket.php?POST=SCREVISE&POSTID='+postid+'&ID='+document.getElementById('mode').value;
           document.frmEdit.submit();
         } else {
            return;
         }

    });
}
</script>
</head>
 <body class="hold-transition sidebar-mini" >

         <section class="content-header">

                 <a class="pull-left" href="calloutticket.php?frmPage_rowcount=<?echo $_SESSION['frmPage_rowcount']; ?>&txtsearch=<?echo $_SESSION['txtsearch']; ?>&frmPage_startrow=<?echo $_SESSION['frmPage_startrow'];?>"  data-toggle="tooltip" data-placement="right" title="Back to Enquiry"><i class='fa fa-backward'></i></a>
                 <h2 class="title">&nbsp;&nbsp;<?echo $title; ?></h2>

                 <ol class='breadcrumb'>
                  <li><a href="#"><a href="blank.php" >My Network</a></li>
                  <li><a href="#"><a href="calloutticket.php?ps=1">Enquiry</a></li>
                  <li class="active"><?echo $title; ?></li>
                 </ol>

         </section>

                <section class="content" id='content-content-id' style='padding-right:5px;padding-left:5px;margin-top:-10px;'>

                  <div class="nav-tabs-custom" id="nav-tabs-custom-id">
                        <ul class="nav nav-tabs">
                           <li class="active"><a href="#personal" onclick='javascript:loadpage(2);' data-toggle="tab"><i class="fa fa-desktop" aria-hidden="true"></i>&nbsp; Ticket</a></li>
                           <?
                                  if(getunreadmsg($_REQUEST['ID'])>0){
                                  $unreadmsg="<span><font color='red'><b>".getunreadmsg($_REQUEST['ID'])."</b></font></span>&nbsp;";
                                  }else{
                                  $unreadmsg="";
                                  }

                           if($_REQUEST['ID']!=0 ) { 
                             if(($post_to_si=='YES' && $_SESSION['SESSuserID']==$areaincharge)|| ($post_to_si=='YES' && $_SESSION['SESSuserID']==$suserid) || ( (stripos(json_encode($_SESSION['role']),'OPERATIONS MANAGER') == true || stripos(json_encode($_SESSION['role']),'SERVICE COORDINATOR') == true || stripos(json_encode($_SESSION['role']),'CLIENT') == true))) {   //stripos(json_encode($_SESSION['role']),'SERVICE COORDINATOR') == true && $posted == "YES" &&
                                   echo " <li><a href='#Services' onclick='javascript:loadpage(3);' data-toggle='tab'><i class='fa fa-cogs' aria-hidden='true'></i> Services</a></li>";
                                   echo " <li><a href='#Materialjob' onclick='javascript:loadpage(14);' data-toggle='tab'><i class='fa fa-file' aria-hidden='true'></i> Service Report</a></li>";

                             }
                           }
                           ?>
                           <?if($_REQUEST['ID']!=0){ ?>
                           <li><a href="#communication"   onclick='javascript:loadpage(4);' data-toggle="tab"><i class="fa fa-wechat" aria-hidden="true"></i>&nbsp; Notes <? echo $unreadmsg;?></a></li>
                          <li><a href="#documents" data-toggle="tab" onclick='javascript:loadpage(20);'><i class="fa fa-folder-open" aria-hidden="true"></i> Documents</a></li>
                           <? } ?>
                       </ul>

                        <div class="tab-content" id='tab-content-id'>
                          <div class="tab-pane active" id="personal">
                              <div class="box-body no-padding" id='box-body-id'>
                                   <div class='table-responsive no-padding' style='overflow:hidden;'>
<?

                         // to check all other tabs filled
                         $SQL   = "SELECT count(*) as count,posted from in_crmline where invheadid='".$_REQUEST['ID']."' group by posted";
                         $SQLRes =  mysql_query($SQL) or die(mysql_error()."<br>".$SQL);
                         if(mysql_num_rows($SQLRes)==1){
                              while($loginResultArray   = mysql_fetch_array($SQLRes)){
                                    if($loginResultArray['posted'] == "YES")
                                    $itemcount = $loginResultArray['count'];
                              }
                         }

                         // to check all other tabs filled
                         $SQL   = "SELECT count(*) as count from tbl_completionreport where invheadid='".$_REQUEST['ID']."' group by posted";
                         $SQLRes =  mysql_query($SQL) or die(mysql_error()."<br>".$SQL);
                         if(mysql_num_rows($SQLRes)==1){
                              while($loginResultArray   = mysql_fetch_array($SQLRes)){
                                    if($loginResultArray['count'] >= 1)
                                    $servicejobcount = $loginResultArray['count'];
                              }
                         }

                         if($servicejobcount>0 && $itemcount>0 ){ 
                            $Tab_validation = 'YES';
                            if( $starttime!="" && $endtime!="") {
                            $print1 ="<button class='btn btn-primary inputs' style='margin-top:-5px;margin-left:4px;float:right' name='btndanger' type='button' onclick ='javascript:print1(\"Yes\");'>Service Report &nbsp;<i class='glyphicon glyphicon-print' aria-hidden='true'></i></button>";
							}
                         }
                         else $Tab_validation = 'NO';

                         $SQL2   = "SELECT enquiryapproval from in_location where locationcode='".$_SESSION['SESSUserLocation']."'";
                         $SQLRes2 =  mysql_query($SQL2) or die(mysql_error()."<br>".$SQL2);
                         if(mysql_num_rows($SQLRes2)>=1){
                              while($loginResultArray2   = mysql_fetch_array($SQLRes2)){
                                    $enquiryapproval = $loginResultArray2['enquiryapproval'];
                              }
                         }
                         
                         $cancelicon =  "";

                        if(($_SESSION['SESSuserID']==$suserid && stripos(json_encode($_SESSION['role']),'SITE INCHARGE') !== false) && $posted!='YES' && strtoupper($post_to_si) == "YES" && $calloutstatus!="" ) {
                            $approve = "<button class='btn btn-success inputs' style='margin-top:-5px;margin-left:4px;float:right;' name='btnsuccess' type='button' onclick ='javascript:sendapproval_enquiry(\"".$_REQUEST['ID']."\");'>$calloutstatus &nbsp;<i class='fa fa-share-square' aria-hidden='true'></i></button>";
                        }
                        if($post_to_sc == "YES" && $post_to_om != "YES"&&  stripos(json_encode($_SESSION['role']),'SERVICE COORDINATOR') !== false && $_SESSION['SESSuserID']==$servicestaff){
                            $approve = "<button class='btn btn-warning inputs' style='margin-top:-5px;float:right;margin-left:4px;' id='btnsuccess' name='btnsuccess' type='button'  onclick ='javascript:screvise(\"".$_REQUEST['ID']."\");'>Revise </font>&nbsp;<i class='fa fa-history' aria-hidden='true'></i></button>";
                            $approve .= "<button class='btn btn-success inputs' style='margin-top:-5px;margin-left:4px;float:right;' name='btnsuccess' type='button' onclick ='javascript:sendapproval_manager(\"".$_REQUEST['ID']."\");'>Send for Manager Approval &nbsp;<i class='fa fa-share-square' aria-hidden='true'></i></button>";
                        }

                        if($converted!="YES" ){
                            // if($Tab_validation == "YES" ) {
								######################## Work Flow #############################
								$checkworkflow = checkforWorkflow("FM","QUOTATION");
								if($checkworkflow == "YES"){
						        $Wf_arr = explode("@",GetWorkFlow("FM","QUOTATION"));
	                            $APPROVAL_users_arr = explode(",",$formsendto);// explode(",",$Wf_arr[1]);
							    $APPROVAL_user1 = $APPROVAL_users_arr[0];
								$APPROVAL_user2 = $APPROVAL_users_arr[1];
								$APPROVALBY    =  $Wf_arr[3];
								$WF_APPROVALCOUNT  =  $Wf_arr[2];
								//echo $APPROVALBY;

								if($APPROVALBY == "SELF" && strtoupper($stcheck) ==strtoupper("(AMC COT) Completion waiting for Manager Approval")){
								        if(stripos(json_encode($_SESSION['role']),'SITE INCHARGE') == true){
                                               $cancelicon = "<button class='btn btn-danger inputs' style='margin-top:-5px;margin-left:4px;float:right' name='btnsuccess' type='button'  onclick ='javascript:Cancelrecord(\"".$_REQUEST['ID']."\");'>Cancel </font>&nbsp;<i class='fa fa-close' aria-hidden='true'></i></button>&nbsp;";
										       $approve = "<button class='btn btn-info inputs' style='margin-top:-5px;float:right;margin-left:4px;' id='btnsuccess' name='btnsuccess' type='button'  onclick ='javascript:managerrevise(\"".$_REQUEST['ID']."\");'>Revise </font>&nbsp;<i class='fa fa-history' aria-hidden='true'></i></button>";
                                               $approve .= "<button class='btn btn-success inputs' style='margin-top:-5px;margin-left:4px;float:right;' name='btnsuccess' type='button' onclick ='javascript:approveit(\"".$_REQUEST['ID']."\");'>Approve &nbsp;<i class='fa fa-check' aria-hidden='true'></i></button>";
                                         }
                                         else{
                                               $approve = "";
                                               $cancelicon = "";
                                         }
								}
								else{
									 if((stripos(json_encode($formsendto),$_SESSION['SESSuserID']) !== false) && (strtoupper($stcheck) ==strtoupper("(AMC COT) Completion waiting for Manager Approval") )) {
                                                        // $approve = "<button class='btn btn-info inputs' style='margin-top:-5px;float:right;margin-left:4px;' id='btnsuccess' name='btnsuccess' type='button'  onclick ='javascript:managerreject(\"".$_REQUEST['ID']."\");'>Reject </font>&nbsp;<i class='fa fa-history' aria-hidden='true'></i></button>";
                                                        $approve = "<button class='btn btn-warning inputs' style='margin-top:-5px;float:right;margin-left:4px;' id='btnsuccess' name='btnsuccess' type='button'  onclick ='javascript:managerrevise(\"".$_REQUEST['ID']."\");'>Revise </font>&nbsp;<i class='fa fa-history' aria-hidden='true'></i></button>";
                                                        if($calloutstatus == "(AMC COT) Complaint closed")
                                                        $approve .= "<button class='btn btn-success inputs' style='margin-top:-5px;margin-left:4px;float:right;' name='btnsuccess' type='button' onclick ='javascript:converttoEMG_or_OT(\"".$_REQUEST['ID']."\");'>Approve &nbsp;<i class='fa fa-check' aria-hidden='true'></i></button>";
                                                        if($calloutstatus == "Convert to OT")
                                                        $approve .= "<button class='btn btn-success inputs' style='margin-top:-5px;margin-left:4px;float:right;' name='btnsuccess' id='btnsuccess' type='button' onclick ='javascript:converttoEMG_or_OT(\"".$_REQUEST['ID']."\");'> Approve &nbsp;<i class='fa fa-share-square' aria-hidden='true'></i></button>";
                                                        if($calloutstatus == "Convert to EMG COT")
                                                        $approve.= "<button class='btn btn-success inputs' style='margin-top:-5px;margin-left:4px;float:right;' name='btnsuccess' type='button' onclick ='javascript:converttoEMG_or_OT(\"".$_REQUEST['ID']."\");'>Approve &nbsp;<i class='fa fa-share-square' aria-hidden='true'></i></button>";
                                                        $cancelicon = "<button class='btn btn-danger inputs' style='margin-top:-5px;margin-left:4px;float:right' name='btnsuccess' type='button'  onclick ='javascript:Cancelrecord(\"".$_REQUEST['ID']."\");'>Cancel </font>&nbsp;<i class='fa fa-close' aria-hidden='true'></i></button>&nbsp;";
                                     }


								//}

								if($approvedby != "") {

								$formsendto_arr = (explode(",",$formsendto));
								$approvedby_arr = (explode(",",$approvedby));
                                $RESULT_arr = array_intersect($approvedby_arr,$formsendto_arr);

                                        for($k=0;$k<count($RESULT_arr);$k++)
                                        {
                                        if(($_SESSION['SESSuserID'] == $RESULT_arr[$k])) {
                                           $approve = "";
                                           $cancelicon = "";
                                        }
                                        }

							    }

								if($WF_APPROVALCOUNT == $formapprovalcount && $stcheck=="Approved" && stripos(json_encode($_SESSION['role']),'SITE INCHARGE') !== false && $_SESSION['SESSuserID']==$suserid){
                                        $quoteico = "<button class='btn btn-danger inputs' style='margin-top:-5px;'  id='btndanger' name='btndanger' type='button'  onclick ='javascript:cancleediting(\"calloutticket.php\");'>Close &nbsp;<i class='fa fa-close' aria-hidden='true'></i></button>";
                                        $quoteico = "<button class='btn btn-success inputs' style='margin-top:-5px;margin-left:4px;float:right;' name='btnsuccess' id='btnsuccess' type='button' > Convert to EMG CO &nbsp;<i class='fa fa-share-square' aria-hidden='true'></i></button>";
                                        $quoteico .= "<button class='btn btn-success inputs' style='margin-top:-5px;margin-left:4px;float:right;' name='btnsuccess' type='button' onclick ='javascript:converttoOT(\"".$_REQUEST['ID']."\");'>Convert to OT &nbsp;<i class='fa fa-share-square' aria-hidden='true'></i></button>";
                                        $cancelicon = "";
								}
								}
								}
								else{ // No workflow
									 if($posted!="YES" && stripos(json_encode($_SESSION['role']),'SITE INCHARGE') !== false && $_SESSION['SESSuserID']==$suserid) {
										$approve = "";
                                        $quoteico = "<button class='btn btn-danger inputs' style='margin-top:-5px;'  id='btndanger' name='btndanger' type='button'  onclick ='javascript:cancleediting(\"calloutticket.php\");'>Close &nbsp;<i class='fa fa-close' aria-hidden='true'></i></button>";
                                        $quoteico = "<button class='btn btn-success inputs' style='margin-top:-5px;margin-left:4px;float:right;' name='btnsuccess' id='btnsuccess' type='button' > Convert to EMG CO &nbsp;<i class='fa fa-share-square' aria-hidden='true'></i></button>";
                                        $quoteico .= "<button class='btn btn-success inputs' style='margin-top:-5px;margin-left:4px;float:right;' name='btnsuccess' type='button' onclick ='javascript:converttoOT(\"".$_REQUEST['ID']."\");'>Convert to OT &nbsp;<i class='fa fa-share-square' aria-hidden='true'></i></button>";
                                        $cancelicon = "";
									 }
								}



								########################## End of code ##############################
                            // }


                             if(strtoupper($posted) !="YES" && $_SESSION['SESSuserID']==$suserid && stripos(json_encode($_SESSION['role']),'SITE INCHARGE') == true)
                                        $saveico ="<button class='btn btn-success inputs' style='margin-top:-5px;' id='btnsuccess' name='btnsuccess' type='button'  onclick ='javascript:editingrecord(\"save\");'>Save </font>&nbsp;<i class='fa fa-save' aria-hidden='true'></i></button>
                                        <button class='btn btn-danger inputs' style='margin-top:-5px;'  id='btndanger' name='btndanger' type='button'  onclick ='javascript:cancleediting(\"calloutticket.php\");'>Close &nbsp;<i class='fa fa-close' aria-hidden='true'></i></button>";
                             else {
                                        $saveico = "<button class='btn btn-danger inputs' style='margin-top:-5px;'  id='btndanger' name='btndanger' type='button'  onclick ='javascript:cancleediting(\"calloutticket.php\");'>Close &nbsp;<i class='fa fa-close' aria-hidden='true'></i></button>";
							            $cancelicon = "";
                                  }

                         }else {
                             $saveico = "<button class='btn btn-danger inputs' style='margin-top:-5px;' name='btndanger' type='button'  onclick ='javascript:cancleediting(\"calloutticket.php\");'>Close &nbsp;<i class='fa fa-close' aria-hidden='true'></i></button>";
                         }

                         $str = explode("$$$",$docname);
                         $str = substr($docname,(strlen($str[0])+3));
                         //$str = substr($docname, $strlen);  echo
                         if($docname!=""){
                            $actdocname= str_replace(" ","%20",$docname);
                            $ext = strtolower(pathinfo($docname, PATHINFO_EXTENSION));

                            $dwld = $str."&nbsp;&nbsp;<a href='#' onclick='loadframe(\"".$ext."\",\"".$docname."\");' data-toggle='modal' data-target='#myModal'><i class='fa fa-eye' data-toggle='tooltip' data-placement='right' title='View' aria-hidden='true'></i></a>
                                         &nbsp;&nbsp;<a  href='download.php?folder=uploads&filename=".$docname."'><i class='fa fa-download' data-toggle='tooltip' data-placement='right' title='Download' aria-hidden='true'></i></a>";
                         }else{
                            $dwld = "";
                         }


                         $mfgimg="<a href=javascript:popupmodel();  data-target='#myModal33' href='#'   style='float:right'><i class='fa fa-plus' data-toggle='tooltip' data-placement='right' title='Add Property Type' aria-hidden='true'></i></a>" ;
                         $modelimg="<a href=javascript:popupmodeladd();   style='float:right' data-target='#myModal44'> <i class='fa fa-plus' data-toggle='tooltip' data-placement='right'  title='Add Building' aria-hidden='true'></i></a>";
                         $addClient="<a href=javascript:popupmodeladdclient();  style='float:right' data-target='#myModal45'> <i class='fa fa-plus' data-toggle='tooltip' data-placement='right'  title='Add Customer' aria-hidden='true'></i></a>";
                         $str2 = substr($docname2, 3);

                          if($docname2!=""){

                            $actdocname2= str_replace(" ","%20",$docname2);
                            $ext = strtolower(pathinfo($docname2, PATHINFO_EXTENSION));

                            $dwld2 = "<a href='#' onclick='loadframe(\"".$ext."\",\"".$docname2."\");' data-toggle='modal' data-target='#myModal'><i class='fa fa-eye' data-toggle='tooltip' data-placement='right' title='View' aria-hidden='true'></i></a>
                                      &nbsp;&nbsp;&nbsp;&nbsp;<a  href='download.php?folder=uploads&filename=".$docname2."'><i class='fa fa-download' data-toggle='tooltip' data-placement='right' title='Download' aria-hidden='true'></i></a>
                                      ";
                         }else{
                            $dwld2 = "";
                         }
                          $enquiry_tr = "";
                         if((stripos(json_encode($_SESSION['role']),'CLIENT') == true || stripos(json_encode($_SESSION['role']),'OPERATIONS MANAGER') == true || stripos(json_encode($_SESSION['role']),'SERVICE COORDINATOR') == true || stripos(json_encode($_SESSION['role']),'SITE INCHARGE') == true || stripos(json_encode($_SESSION['role']),'FACILITY MANAGER') == true) && $enquiryby!='') {   // $_REQUEST['ID']!= "0" && ($stcheck=="Open" )&& $stcheck!="Sent for approval"
                                $enquiry_tr = "<tr><td class='dvtCellLabel' style='border: 1px solid #ccc;'>Enquiry By :</td>
                                <td style='border: 1px solid #ccc;'> ".getusername($enquiryby)."</td>
                                <td class='dvtCellLabel' style='border: 1px solid #ccc;'>Service Co. :</td>
                                <td style='border: 1px solid #ccc;'> ".getServiceCordinator($servicestaff)."</td>
                                <td class='dvtCellLabel' style='border: 1px solid #ccc;'>Area Incharge<span class='mandatory'>&nbsp;*</span></td>
        <td style='border: 1px solid #ccc;'>".getAreaIncharge($areaincharge,'disabled')."</td>
                                </tr>
                               ";
                         }
                         
$callout_td = "
<tr>
    <td class='dvtCellLabel' style='border: 1px solid #ccc;'>Ticket Number</td>
    <td style='border: 1px solid #ccc;height:33px;'><b>$DocNo<b></td>
    <td class='dvtCellLabel' style='border: 1px solid #ccc;'>Project Name<span class='mandatory'>&nbsp;*</span></td>
    <td style='border: 1px solid #ccc;' colspan=1>
    <input type='text' $post_to_si_display class='form-control txt' name='txt_A_projectname' id='txt_A_projectname' value='$projectname' >
    </td>
    <td class='dvtCellLabel' style='border: 1px solid #ccc;'>Status</td>
    <td style='border: 1px solid #ccc;height:33px;'><b>$stcheck<b></td>
</tr>
<tr>
    <td class='dvtCellLabel' style='border: 1px solid #ccc;'>Contact Person<span class='mandatory'>&nbsp;*</span></td>
    <td style='border: 1px solid #ccc;' colspan=1><input type='text' $post_to_si_display class='form-control txt' name='txt_A_contactperson' id='txt_A_contactperson' value='$contactperson' ></td>
    <td class='dvtCellLabel' style='border: 1px solid #ccc;'>Contact No 1<span class='mandatory'>&nbsp;*</span></td>
    <td style='border: 1px solid #ccc;' colspan=1><input type='text' $post_to_si_display  onkeypress='return AllowNumeric1(event)' class='form-control txt' name='txt_A_phonecode1' id='txt_A_phonecode1' value='$phonecode1' ></td>
    <td class='dvtCellLabel' style='border: 1px solid #ccc;'>Email ID<span class='mandatory'>&nbsp;*</span></td>
    <td style='border: 1px solid #ccc;'><input type='text' class='form-control txt'  name='txt_A_billingemail' id='txt_A_billingemail' $post_to_si_display value='$billingemail'></td>
</tr>
<tr>
    <td class='dvtCellLabel' style='border: 1px solid #ccc;'>
    <span style='float:left;border: 0px solid #ccc;'>Cust. Name <span class='mandatory'>*</span></span>
    <td style='border: 1px solid #ccc;'>
    <input type='text' $post_to_si_display class='form-control txt' name='txt_A_objectname' id='txt_A_objectname' value='$objectname' >
    </td>
    <td class='dvtCellLabel' style='border: 1px solid #ccc;'>Company <span class='mandatory'>&nbsp;*</span></td>
	<td style='border: 1px solid #ccc;'>
    ".GetCompany($company,$post_to_si_display)."</td>
    <td class='dvtCellLabel' style='border: 1px solid #ccc;'>Division <span class='mandatory'>&nbsp;*</span></td>
    <td style='border: 1px solid #ccc;' id=getdivision name=getdivision>".GetDivision($company,$divisioncode,$post_to_si_display)." </td>
    
</tr>
<tr>
    <td class='dvtCellLabel' style='border: 1px solid #ccc;'>Property Type<span class='mandatory'>&nbsp;*</span></td>
    <td style='border: 1px solid #ccc;'>
    ".GetAllProperty($propertycode,$projectname,$post_to_si_display)."
    </td>
    <td class='dvtCellLabel' style='border: 1px solid #ccc;'> Property Name <span class='mandatory'>&nbsp;*</span></td>
    <td style='border: 1px solid #ccc;'>
    <input type='text' name='txt_A_buildingname' id='txt_A_buildingname' readonly value='$buildingname' class='form-control txt'/>
    </td>
    <td class='dvtCellLabel' style='border: 1px solid #ccc;'> Property No</td>
    <td style='border: 1px solid #ccc;'>
   <input type='text' readonly class='form-control txt'  value='$propertyno' >
    <input type='hidden' $post_to_si_display class='form-control txt' name='txt_A_buildingcode' id='txt_A_buildingcode' value='$buildingcode' >
    </td>
</tr>
<tr>
    <td class='dvtCellLabel' style='border: 1px solid #ccc;'>Location</td>
    <td style='border: 1px solid #ccc;' colspan=1><input type='text' readonly class='form-control txt' name='txt_A_floordetails' id='txt_A_floordetails' value='$floordetails' ></td>
    <td class='dvtCellLabel' style='border: 1px solid #ccc;'>Call Out Type</td>
    <td style='border: 1px solid #ccc;'>".GetEnquiryCategory($enquirycategory,$post_to_si_display)."</td>
    <td class='dvtCellLabel' style='border: 1px solid #ccc;'>Priority</td>
    <td style='border: 1px solid #ccc;'>".GetPriority($priority,$post_to_si_display)."</td>
</tr>";
                       
                         $serviceTab_tr = "";
                         $tech_td = "<td style='border: 1px solid #ccc;' colspan=2>&nbsp;</td>";
                         if((stripos(json_encode($_SESSION['role']),'CLIENT') == true || stripos(json_encode($_SESSION['role']),'SITE INCHARGE') == true || stripos(json_encode($_SESSION['role']),'OPERATIONS MANAGER') == true || stripos(json_encode($_SESSION['role']),'SERVICE COORDINATOR') == true) && $Tab_validation=="YES"){
                         $serviceTab_tr = "<tr>
                                            <td class='dvtCellLabel' style='border: 1px solid #ccc;'><span style='float:left;'>Start time :</span><span class='hide_mandatory' style='float:left;display:$hide_mandatory;'>&nbsp;<font color='red'>*</font></span></td>
                                            <td style='border: 1px solid #ccc;' colspan=1>
                                            <input type='text' class='form-control txt' name='txt_A_starttime' id='txt_A_starttime' $posted_lock onkeypress='return AllowNumeric1(event)' onblur='validateDatePicker(this)' value='".$starttime."' ></td>
                                            <td class='dvtCellLabel' style='border: 1px solid #ccc;'><span style='float:left;'>End Time :</span><span class='hide_mandatory' style='float:left;display:$hide_mandatory;'>&nbsp;<font color='red'>*</font></span></td>
                                            <td style='border: 1px solid #ccc;'>
                                                <input type='text' class='form-control txt' name='txt_A_endtime' id='txt_A_endtime' $posted_lock onkeypress='return AllowNumeric1(event)' onblur='validateDatePicker(this)'  value='".$endtime."' >
                                            </td>

                                        </tr>";
                         $tech_td = "<td class='dvtCellLabel' style='border: 1px solid #ccc;'><b>COT Status</b>:<span class='mandatory'>&nbsp;*</span></td>
                                     <td style='border: 1px solid #ccc;'>".GetCOTStatus($calloutstatus,$posted_lock,$Tab_validation)."</td>
                                     <td class='dvtCellLabel' style='border: 1px solid #ccc;'><span style='float:left;'>Technician Name:</span><span class='hide_mandatory' style='float:left;display:$hide_mandatory;'>&nbsp;<font color='red'>*</font></span></td>
                                     <td style='border: 1px solid #ccc;'>".GetWorkers($technician,$posted_lock)."</td>";
                         }


                         $entrydata .= "<form name='frmEdit' method='post' id='frmEdit' enctype='multipart/form-data' autocomplete='off'>
                                         <table class='table table-bordered table-condensed table-fixed  table-responsive' style='table-layout:fixed'>
                                                         $callout_td
                                                        <tr>
                                                            <td class='dvtCellLabel' style='border: 1px solid #ccc;'>Complaint Recvd<span class='mandatory'>&nbsp;*</span></td>
                                                            <td style='border: 1px solid #ccc;'>
                                                            <input type='text' class='form-control txt'  data-provide='datepicker' maxlength=10 $post_to_si_display onkeypress='return AllowNumeric_date(event)' name='txd_A_docdate' id='txd_A_docdate'   value='$docdate' placeholder='dd-mm-yyyy' ></td>
                                                            <td class='dvtCellLabel' style='border: 1px solid #ccc;'>Work Details<span class='mandatory'>&nbsp;*</span></td>
                                                            <td style='border: 1px solid #ccc;' colspan=3><input type='text' $post_to_si_display class='form-control txt' name='txt_A_jobname' id='txt_A_jobname' value='$jobname' ></td>

                                                        </tr>
                                                        $enquiry_tr
                                                        <tr>
                                                             <td class='dvtCellLabel' style='border: 1px solid #ccc;'>Inspc.Assign To &nbsp;<span class='mandatory'>&nbsp;*</span></td>
                                                             <td style='border: 1px solid #ccc;'> ".getSiteIncharge($suserid,$post_to_si_display)."</td>
                                                            <td class='dvtCellLabel' style='border: 1px solid #ccc;'>Complaint through</td>
                                                             <td style='border: 1px solid #ccc;'> ".GetEnquiryThrough($enquirythrough,$post_to_si_display)."</td>
                                                             <td class='dvtCellLabel' style='border: 1px solid #ccc;'>
                                                             <div id='div_enquirythrough' style='display:$div_external_display;'>If any:&nbsp;</div>
                                                             </td>
                                                             <td style='border: 1px solid #ccc;' colspan=1>
                                                             <div id='div_enquirythrough2' style='display:$div_external_display;'><input type='text' class='form-control txt' $post_to_si_display name='txt_A_externalinfo' id='txt_A_externalinfo' value='$externalinfo' ></div>
                                                             </td>
                                                         </tr>
                                                         ".$serviceTab.$serviceTab_tr."
                                                         <tr>
                                            <td class='dvtCellLabel' style='border: 1px solid #ccc;'>Complaint Closed<span class='mandatory'>&nbsp;*</span></td>
                                            <td style='border: 1px solid #ccc;'>
                                            <input type='text' class='form-control txt'$posted_lock data-provide='datepicker' maxlength=10  onkeypress='return AllowNumeric_date(event)' name='txd_A_enddate' id='txd_A_enddate'   value='$enddate' placeholder='dd-mm-yyyy' ><input type='hidden' class='form-control txt' $posted_lock data-provide='datepicker' maxlength=10  onkeypress='return AllowNumeric_date(event)' name='txd_A_startdate' id='txd_A_startdate'   value='$docdate' placeholder='dd-mm-yyyy' >
                                            	
                                            </td>

                                            ".$tech_td."
                                        </tr>
                                                         <tr>
                                                             <td class='dvtCellLabel' style='border: 1px solid #ccc;'>Upload Service Report:</td>
                                                            <td style='border: 1px solid #ccc;' colspan=1><input type='hidden' name='MAX_FILE_SIZE'><input type='file' $posted_lock name='userfile' class='upload'  id='userfile'>
                                                            $dwld
                                                             </td>
                                                            <td class='dvtCellLabel' style='border: 1px solid #ccc;'>Remarks :</td>
                                                            <td style='border: 1px solid #ccc;' colspan=3><input type='text' class='form-control txt' $posted_lock name='txa_A_remarks' id='txa_A_remarks' value='$remarks' ></td>
                                                          </tr>
                                                               <input type='hidden' name='txt_A_calloutreferenceid' id='txt_A_calloutreferenceid' value='$calloutreferenceid'/>
                                                               <input type='hidden' name='txt_A_calloutjobnoreference' id='txt_A_calloutjobnoreference' value='$calloutjobnoreference'/>
                                                              
                                                               <input type='hidden' name='ticketno' class=textboxcombo id='ticketno' value='$DocNo'>
                                                              <!-- <input type='hidden' class='form-control txt' name='txt_A_company' id='txt_A_company' value='$company' >-->
                                                               <input type='hidden' name='txt_A_enquiryby' class=textboxcombo id='txt_A_enquiryby' value='$enquiryby'>
                                                               <input type='hidden' name='txt_A_doctype' class=textboxcombo id='txt_A_doctype' value='$enquirycategory'>
                                                               <input type='hidden' id='txt_A_locationcode' name='txt_A_locationcode' value='".$_SESSION['SESSUserLocation']."'>
                                                               <input type='hidden' name='mode' class=textboxcombo id='mode' value='$mode'>
                                                               <input type='hidden' name='modeid' class=textboxcombo id='modeid' value='save'>
                                                               <input type='hidden' name='saveid' class=textboxcombo id='saveid' value='$saveid'>
                                                               <input type='hidden' name='searchvalue' class=textboxcombo id='searchvalue' value='".$_REQUEST['txtsearch']."'>
                                                               <input type='hidden' name='recordperpage' class=textboxcombo id='recordperpage' value='".$_REQUEST['frmPage_rowcount']."'>
                                                               <input type='hidden' name='recordstartrow' class=textboxcombo id='recordstartrow' value='".$_SESSION['frmPage_startrow']."'>
                                                </table>


                                        </form> ";
                             $display="none";
                            if($_REQUEST['CHILDID'] !='' && $_REQUEST['DEL'] =='DELETE'){
                                    $Del_query="delete from in_crmtasks where id='". $_REQUEST['CHILDID']."'";
                                    $Del_Result = mysql_query($Del_query)   or die(mysql_error()."<br>".$Del_query);
                                    $_REQUEST['CHILDID']="";

                            }
                     $entrydata .= "   </div>
                                       </div>
                                        <div class='box-footer' style='border-top:1px #D2D2D2 solid;'>
                                        $saveico
                                        $backbutton
                                        $cancelicon
                                        $postico
										$approve
                                        $quoteico
                                        $confirmico
                                        $print1
                                        </div>";   //$completeico

echo  $entrydata;
?>

                           </div>

                             <div class="tab-pane" id="Services">
                              <iframe id="frame3" name="frame3" scrolling="no" onload='this.width=screen.width;this.height=screen.height;'  frameborder="0" style="position: relative; width: 100%; "></iframe>
                             </div>

                             <div class="tab-pane" id="Combinedjob">
                              <iframe id="frame13" name="frame13" scrolling="no" onload='this.width=screen.width;this.height=screen.height;'  frameborder="0" style="position: relative; width: 100%; "></iframe>
                             </div>
                             <div class="tab-pane" id="communication">
                                      <iframe id="frame4" name="frame4" scrolling="no" onload='this.width=screen.width;this.height=screen.height;'  frameborder="0" style="position: relative; width: 100%; "></iframe>
                             </div>

                             <div class="tab-pane" id="documents">
                                      <iframe id="frame20" name="frame20" scrolling="no" onload='this.width=screen.width;this.height=screen.height;'  frameborder="0" style="position: relative; width: 100%; "></iframe>
                             </div>
                             <div class="tab-pane" id="Materialjob">
                                      <iframe id="frame14" name="frame14" scrolling="no" onload='this.width=screen.width;this.height=screen.height;'  frameborder="0" style="position: relative; width: 100%; "></iframe>
                             </div>
                             <div class="tab-pane" id="Subjob">
                                      <iframe id="frame15" name="frame15" scrolling="no" onload='this.width=screen.width;this.height=screen.height;'  frameborder="0" style="position: relative; width: 100%; "></iframe>
                             </div>
                             <div class="tab-pane" id="Variationjob">
                                      <iframe id="frame16" name="frame16" scrolling="no" onload='this.width=screen.width;this.height=screen.height;'  frameborder="0" style="position: relative; width: 100%; "></iframe>
                             </div>
                       </div>
                  </div>

        </section>
</body>
<div class='modal fade' id='myModal' tabindex='-1' role='dialog' aria-labelledby='myModalLabel' style=" margin:0 auto;">
         <div class='modal-dialog' role='document'  style="width:900px; margin:0 auto;" align=center>
            <div class='modal-content' style='width:900px; '>
                 <div class='modal-header' style='height:40px;' >
                  <button type='button' class='close' data-dismiss='modal' aria-label='Close'><span aria-hidden='true'>&times;</span></button>
                  <h3 style='margin-top:-5px;'>Attachment</h3>
                 </div>
                 <div class='modal-body lg' id='popupdiv' name='popupdiv' >
                 </div>
            </div>
         </div>
</div>

<div id="myModal45" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 id="modalTitle" class="modal-title">Add Customer</h4>
            </div>
            <div id="modalBody" class="modal-body">
               <table class="table table-condensed table-bordered" >
                <tr >
                       <td>Client Name:</td>
                       <td><input type='text' name='txtclientname' style="border: 1px solid #ccc;"   class='form-control txt' id='txtclientname' value=''> </td>
               </tr>
               <tr >
                       <td>Short Name:</td>
                       <td><input type='text' name='txtshortname' class='form-control txt' maxlength=3 style='border: 1px solid #ccc; text-transform:uppercase' id='txtshortname' value=''></td>
               </tr>
               <tr >
                       <td>Contact No:</td>
                       <td><input type='text' name='txtclientno' style="border: 1px solid #ccc;"   class='form-control txt' id='txtclientno' value='' onkeypress='return AllowNumeric1(event)'> </td>
               </tr>
               <tr >
                       <td>Contact Address:</td>
                       <td><input type='text' name='txtclientaddress' style="border: 1px solid #ccc;"   class='form-control txt' id='txtclientaddress' value=''> </td>
               </tr>

               </table>


            </div>
            <div class="modal-footer">
                <button class='btn btn-success inputs' id='btnsuccess' name='btnsuccess' type='button'  onclick ='javascript:postaddclient();'>Save &nbsp;</button>
           </div>
        </div>
    </div>
  </div>

<div id="myModal33" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 id="modalTitle" class="modal-title">Add Property Type</h4>
            </div>
            <div id="modalBody" class="modal-body">
               <table class="table table-condensed table-bordered" >
                <tr >
                       <td>Property Type:</td>
                       <td><input type='text' name='txtmanufacturer' style="border: 1px solid #ccc;"   class='form-control txt' id='txtmanufacturer' value=''> </td>
               </tr>

               </table>


            </div>
            <div class="modal-footer">
                <button class='btn btn-success inputs' id='btnsuccess' name='btnsuccess' type='button'  onclick ='javascript:postmfg();'>Save &nbsp;</button>
           </div>
        </div>
    </div>
  </div>

   <div id="myModal44" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 id="modalTitle" class="modal-title">Add Building</h4>
            </div>
            <div id="modalBody" class="modal-body">
               <table class="table table-condensed table-bordered" >
                <tr >
                       <td>Building Name:</td>
                       <td><input type='text' name='txtmodel' style="border: 1px solid #ccc;"   class='form-control txt' id='txtmodel' value=''> </td>
               </tr>
               <tr >
                       <td>Makani ID:</td>
                       <td><input type='text' name='txtmakaniid' style="border: 1px solid #ccc;"   class='form-control txt' id='txtmakaniid' value=''> </td>
               </tr>

               </table>


            </div>
            <div class="modal-footer">
                <button class='btn btn-success inputs' id='btnsuccess' name='btnsuccess' type='button'  onclick ='javascript:postmodel();'>Save &nbsp;</button>
           </div>
        </div>
    </div>
  </div>

</html>
<?php
function GetClientProjects($accountheadcode,$projectname,$company,$divisioncode,$posted_lock) {
         $CMB = "<select name='cmb_A_projectname' id='cmb_A_projectname' $posted_lock class='form-control select2' onChange='getContactDetailsoofProject(this.value)'>  ";
         $CMB .= "<option value=''>Select</option>";
         $SEL =  "select projectname from in_crmhead where enquirycategory='AMC Enquiry' and doctype='ORDER' and converted='YES' and
         objectcode='$accountheadcode' and company='$company' and divisioncode='$divisioncode'";
         $RES = mysql_query($SEL);
         while ($ARR = mysql_fetch_array($RES)) {
                $SEL = "";
                if(strtoupper($projectname) == strtoupper($ARR['projectname'])){ $SEL =  "SELECTED";}
                $CMB .= "<option value='".$ARR['projectname']."' $SEL >".$ARR['projectname']."</option>";
         }
         $CMB .= "</select>";
         return $CMB;
}
function GetAllProperty($property,$projectname,$lock){
         $CMB = "<select name='cmb_A_propertycode' $lock id='cmb_A_propertycode' class='form-control select' onChange='getbuilding(this.value)'>  ";
         $CMB .= "<option value=''>Select</option>";
         $SEL =  "select propertycode,propertyname from tbl_clientproperty where status='Active' and posted='YES'";
         $RES = mysql_query($SEL);
         while ($ARR = mysql_fetch_array($RES)) {
                $SEL = "";
                if(strtoupper($property) == strtoupper($ARR['propertycode'])){ $SEL =  "SELECTED";}
                $CMB .= "<option value='".$ARR['propertycode']."' $SEL >".$ARR['propertyname']."</option>";
         }
         $CMB .= "</select>";
         return $CMB;
}
function GetProperty($property,$calloutreferenceid,$projectname,$posted_lock){
         $CMB = "<select name='cmb_A_propertycode' id='cmb_A_propertycode' class='form-control select' $posted_lock onChange='getbuilding(this.value)'>  ";
         $CMB .= "<option value=''>Select</option>";
         $SEL =  "select distinct(propertycode) as propertycode from tbl_clientserviceproperty where projectname ='$projectname' and docid='$calloutreferenceid'";
         $RES = mysql_query($SEL);
         while ($ARR = mysql_fetch_array($RES)) {
                $SEL = "";
                if(strtoupper($property) == strtoupper($ARR['propertycode'])){ $SEL =  "SELECTED";}
                $CMB .= "<option value='".$ARR['propertycode']."' $SEL >".$ARR['propertycode']."</option>";
         }
         $CMB .= "</select>";
         return $CMB;
}
function GetContractReference($company,$division,$accountheadcode,$contractreference,$posted_lock){
         //if($empcompany=='')$empcompany="02001";
         $CMB = " <select name='cmb_A_contractreference'  class='form-control select' $posted_lock id='cmb_A_contractreference'>";
         $CMB .= "<option value=''>Select</option>";
         $SEL =  "select jobno from t_activitycenter inner join in_businessobject on t_activitycenter.clientname=in_businessobject.accountheadcode
         where  activitycenter='CONTRACT' and t_activitycenter.division='".$division."' and t_activitycenter.company='".$company."'
         and in_businessobject.objectcode='$accountheadcode' and t_activitycenter.status='OPEN'";
         $RES = mysql_query($SEL);
         while ($ARR = mysql_fetch_array($RES)) {
                $SEL = "";
                if(strtoupper($contractreference) == strtoupper($ARR['jobno'])){ $SEL =  "SELECTED";}
                $CMB .= "<option value='".$ARR['jobno']."' $SEL >".$ARR['jobno']."</option>";
         }
         $CMB .= "</select></div>";
         return $CMB;
}
function GetDivision($company,$division,$post_to_si_display){
         //if($empcompany=='')$empcompany="02001";
         $CMB = " <select name='cmb_A_divisioncode'  class='form-control select' $post_to_si_display id='cmb_A_divisioncode'>";
         //$CMB .= "<option value=''>Select</option>";
         $SEL =  "select code,division,in_lookup.lookname from in_locationdivision,in_lookup where in_locationdivision.division= in_lookup.lookcode and
                  locationcode='$company' and type='9002' order by lookname";
         $RES = mysql_query($SEL);
         while ($ARR = mysql_fetch_array($RES)) {
                $SEL = "";
                if(strtoupper($division) == strtoupper($ARR['division'])){ $SEL =  "SELECTED";}
                $CMB .= "<option value='".$ARR['division']."' $SEL >".$ARR['lookname']."</option>";
         }
         $CMB .= "</select></div>";
         return $CMB;
}
function GetCompany($empstatus,$post_to_si_display){
         $CMB = "<select name='cmb_A_company' $post_to_si_display  class='form-control select' id='cmb_A_company'>";
         $CMB .= "<option value=''>Select</option>";
         $SEL =  "select locationcode,cy_ename from in_location where companycode <>'01' order by cy_ename";
         $RES = mysql_query($SEL);
         while ($ARR = mysql_fetch_array($RES)) {
                $SEL = "";
                if(strtoupper($empstatus) == strtoupper($ARR['locationcode'])){ $SEL =  "SELECTED";}
                $CMB .= "<option value='".$ARR['locationcode']."' $SEL >".$ARR['cy_ename']."</option>";
         }
         $CMB .= "</select>";
         return $CMB;
}
function GetBuildings($calloutreferenceid,$property,$building,$posted_lock){
         $CMB = "<select name='cmb_A_buildingcode' id='cmb_A_buildingcode' $posted_lock class='form-control select' onChange='getFloorDetails(this.value)'>  ";
         $CMB .= "<option value=''>Select</option>";
         $SEL =  "select tbl_clientserviceproperty.buildingcode,tbl_clientbuilding.buildingname
         from tbl_clientserviceproperty left join  tbl_clientbuilding
         on tbl_clientserviceproperty.buildingcode=tbl_clientbuilding.buildingshortname
         where tbl_clientserviceproperty.propertycode='".$property."' and tbl_clientserviceproperty.docid='".$calloutreferenceid."'
         order by tbl_clientserviceproperty.id";
         $RES = mysql_query($SEL);
         while ($ARR = mysql_fetch_array($RES)) {
                $SEL = "";
                if(strtoupper($building) == strtoupper($ARR['buildingcode'])){ $SEL =  "SELECTED";}
                $CMB .= "<option value='".$ARR['buildingcode']."' $SEL >".$ARR['buildingname']."</option>";
         }
         $CMB .= "</select>";
         return $CMB;
}
function GetAccounthead($accountheadcode,$lock,$enquirycategory){

         $CMB = " <select name='cmb_A_objectcode'  id='cmb_A_objectcode' class='form-control select2' $lock onchange='getCustomerInfo(this.value);'>";
         $CMB .= "<option value=''></option>";
         $clause ="accountheadcode <> '' and objecttype='Customer' ";
         $SEL = "Select accountheadcode,objectname from in_businessobject  where id<>'' and $clause order by objectname";
         $RES = mysql_query($SEL);
         while ($ARR = mysql_fetch_array($RES)) {
            $SEL = "";
               if($accountheadcode == $ARR['accountheadcode']){ $SEL =  "SELECTED";}
               $CMB .= "<option value='".$ARR['accountheadcode']."' $SEL >".$ARR['objectname']."</option>";
         }
         $CMB .= "</select>";
         return $CMB;
}
function GetPriority($priority,$post_to_si_display){

         $CMB = "<select name='cmb_A_priority' class='form-control select' $post_to_si_display id='cmb_A_priority'>";
        // $CMB .= "<option value=''></option>";
         $SEL =  "select lookcode,lookname from in_lookup_head where looktype='CRMPRIORITY' and lookname<>'YY' order by slno";
         $RES = mysql_query($SEL);
         while ($ARR = mysql_fetch_array($RES)) {
                $SEL = "";
                if(strtoupper($priority) == strtoupper($ARR['lookcode'])){ $SEL =  "SELECTED";}
                $CMB .= "<option value='".$ARR['lookcode']."' $SEL >".$ARR['lookname']."</option>";
         }
         $CMB .= "</select>";
         return $CMB;
}
function GetTaskstatus($status){

         $CMB = "<select name='cmb_A_status'  class='form-control select' id='cmb_A_status'>";
         //$CMB .= "<option value=''></option>";
         $SEL =  "select lookcode,lookname from in_lookup_head where looktype='CRM TASK STATUS' and lookname='Open' and lookname<>'YY' order by id";
         $RES = mysql_query($SEL);
         while ($ARR = mysql_fetch_array($RES)) {
                $SEL = "";
                if(strtoupper($status) == strtoupper($ARR['lookcode'])){ $SEL =  "SELECTED";}
                $CMB .= "<option value='".$ARR['lookcode']."' $SEL >".$ARR['lookname']."</option>";
         }
         $CMB .= "</select>";
         return $CMB;
}
function GetAssignto($userid,$enquirytype){

         $CMB = " <select name='cmb_A_assignedto'  id='cmb_A_assignedto' class='form-control select2' style='width:100%;'>";
         $CMB .= "<option value=''></option>";
         $SEL   = "select incharges from in_locationdivision where code='".$enquirytype."'  order by id";
         $RES = mysql_query($SEL);
         while ($ARR = mysql_fetch_array($RES)) {

                $Htemp     = split(",",$ARR['incharges']);
               for($ii=0 ;$ii < count($Htemp); $ii++){
               if($userid==$Htemp[$ii]){
                $CMB .= "<option value='".$Htemp[$ii]."' selected='selected'>".$Htemp[$ii]."-".getusername($Htemp[$ii])."</option>";
               }else{
                $CMB .= "<option value='".$Htemp[$ii]."'>".$Htemp[$ii]."-".getusername($Htemp[$ii])."</option>";
               }
             }
               // if($userid == $ARR['userid']){ $SEL =  "SELECTED";}
               // $CMB .= "<option value='".$ARR['userid']."' $SEL >".$ARR['userid']." - ".$ARR['username']."</option>";
         }
         $CMB .= "</select>";
         return $CMB;
}
function getusername($id){
       $SEL123 = "select username as name from in_user where userid='$id'";
       $RES123 = mysql_query($SEL123);
        while ($ARR123 = mysql_fetch_array($RES123)) {
                $name = $ARR123['name'];
        }

       return $name;
}
function GetEnquiryCategory($enquirycategory,$post_to_si_display) {

         $CMB = " <select name='cmb_A_enquirycategory' id='cmb_A_enquirycategory' $post_to_si_display class='form-control select'>";
         //$CMB .= "<option value=''></option>";
         $SEL =  "select lookcode,lookname from in_lookup_head where looktype='ENQUIRY CATEGORY' and lookcode= '$enquirycategory' order by slno";
         $RES = mysql_query($SEL);
         while ($ARR = mysql_fetch_array($RES)) {
                $SEL = "";
                if($enquirycategory == $ARR['lookcode']){ $SEL =  "SELECTED";}
                $CMB .= "<option value='".$ARR['lookcode']."' $SEL >".$ARR['lookname']."</option>";
         }
         $CMB .= "</select>";
         return $CMB;
}
function GetEnquirySource($enqtype) {

         $CMB = " <select name='cmb_A_leadsource'  id='cmb_A_leadsource' class='form-control select' >";
         $CMB .= "<option value=''>None</option>";
         $SEL =  "select lookcode,lookname from in_lookup where looktype='LEAD SOURCE' and lookname<>'XX' order by slno";
         $RES = mysql_query($SEL);
         while ($ARR = mysql_fetch_array($RES)) {
                $SEL = "";
                if($enqtype == $ARR['lookcode']){ $SEL =  "SELECTED";}
                $CMB .= "<option value='".$ARR['lookcode']."' $SEL >".$ARR['lookname']."</option>";
         }
         $CMB .= "</select>";
         return $CMB;
}
function GetEnquiryStatus($enqtype) {

         $CMB = " <select name='cmb_A_leadstatus'  id='cmb_A_leadstatus' class='form-control select' >";
         //$CMB .= "<option value='Select'>Select</option>";
         $SEL =  "select lookcode,lookname from in_lookup_head where looktype='LEAD STATUS' and lookname<>'YY' order by slno";
         $RES = mysql_query($SEL);
         while ($ARR = mysql_fetch_array($RES)) {
                $SEL = "";

                if($enqtype == $ARR['lookcode']){ $SEL =  "SELECTED";}
                $CMB .= "<option value='".$ARR['lookcode']."' $SEL >".$ARR['lookname']."</option>";
         }
         $CMB .= "</select>";
         return $CMB;
}

function GetEnquiryType($enqtype,$post_to_si_display) {

         $CMB = " <select name='cmb_A_enquirytype'  id='cmb_A_enquirytype' class='form-control select' $post_to_si_display>";
        // $CMB .= "<option value=''></option>";
         $SEL =  "select lookname,in_locationdivision.code from in_locationdivision,in_lookup where in_lookup.lookcode=in_locationdivision.division
                  and in_locationdivision.type='PROFIT DIVISION' order by lookname";
         $RES = mysql_query($SEL);
         while ($ARR = mysql_fetch_array($RES)) {
                $SEL = "";
                if($enqtype == $ARR['code']){ $SEL =  "SELECTED";}
                $CMB .= "<option value='".$ARR['code']."' $SEL >".$ARR['lookname']."</option>";
         }
         $CMB .= "</select>";
         return $CMB;
}

function GetLastSqeID($tblName){
                 $query = "LOCK TABLES in_sequencer WRITE";
                 mysql_query($query) or die(mysql_error()."<br>".$query);
                 $seqSQL = "SELECT LASTNUMBER FROM in_sequencer WHERE TABLENAME='$tblName'";
                 $result=mysql_query($seqSQL) or die(mysql_error()."<br>".$seqSQL);
                 $resulArr=mysql_fetch_array($result);
                 $updatedSeqID=$resulArr['LASTNUMBER']+1;
                 //$squpdateSQL = "UPDATE in_sequencer SET LASTNUMBER=".$updatedSeqID." WHERE TABLENAME='$tblName'";
                 //mysql_query($squpdateSQL) or die(mysql_error()."<br>".$squpdateSQL);
                 $query = "UNLOCK TABLES";
                 mysql_query($query) or die(mysql_error()."<br>".$query);
                 return ($updatedSeqID);
}
function GetLastSqeID_new($tblName){
                 $query = "LOCK TABLES in_sequencer_crm WRITE";
                 mysql_query($query) or die(mysql_error()."<br>".$query);
                 $seqSQL = "SELECT LASTNUMBER FROM in_sequencer_crm WHERE TABLENAME='$tblName'";
                 $result=mysql_query($seqSQL) or die(mysql_error()."<br>".$seqSQL);
                 $resulArr=mysql_fetch_array($result);
                 $updatedSeqID=$resulArr['LASTNUMBER']+1;
                 //$squpdateSQL = "UPDATE in_sequencer SET LASTNUMBER=".$updatedSeqID." WHERE TABLENAME='$tblName'";
                 //mysql_query($squpdateSQL) or die(mysql_error()."<br>".$squpdateSQL);
                 $query = "UNLOCK TABLES";
                 mysql_query($query) or die(mysql_error()."<br>".$query);
                 return ($updatedSeqID);
}
function getunreadmsg($id){
      $SQL = "Select count(*) as count from tbl_message,tbl_ticket where tbl_ticket.id=tbl_message.ticketno and tbl_message.viewedby not like '%".$_SESSION['SESSuserID']."%' and tbl_ticket.id='$id'";
      $SQLRes =  mysql_query($SQL) or die(mysql_error()."<br>".$SQL);
      if(mysql_num_rows($SQLRes)>=1){
        while($loginResultArray   = mysql_fetch_array($SQLRes)){
            $count=$loginResultArray['count'];


        }
      }
      return $count;
}

function GetDurationType($durationtype) {
         $CMB = " <select name='cmb_A_durationtype'  id='cmb_A_durationtype' class='form-control select' onChange='getDurationNos(this.value)'>";
         $CMB .= "<option value=''>Select</option>";
         $SEL =  "select lookcode,lookname from in_lookup_head where looktype='DURATION TYPE' and lookname<>'YY' order by slno";
         $RES = mysql_query($SEL);
         while ($ARR = mysql_fetch_array($RES)) {
               $SEL = "";
               if($durationtype == $ARR['lookcode']){ $SEL =  "SELECTED";}
               $CMB .= "<option value='".trim($ARR['lookcode'])."' $SEL >".trim($ARR['lookname'])."</option>";
         }
         $CMB .= "</select>";
         return $CMB;
}
function GetEnquiryThrough ($enquirythrough,$post_to_si_display){
        $CMB = " <select name='cmb_A_enquirythrough'  id='cmb_A_enquirythrough' $post_to_si_display class='form-control select' onChange='getEnquiry_Through(this);'>";    //getEnquiryThrough(this.value)
         $CMB .= "<option value=''>Select</option>";
         $SEL =  "select lookcode,lookname from in_lookup_head where looktype='ENQUIRY THROUGH' and lookname<>'YY' order by slno";
         $RES = mysql_query($SEL);
         while ($ARR = mysql_fetch_array($RES)) {
               $SEL = "";
               if($enquirythrough == $ARR['lookcode']){ $SEL =  "SELECTED";}
               $CMB .= "<option value='".trim($ARR['lookcode'])."' $SEL >".trim($ARR['lookname'])."</option>";
         }
         $CMB .= "</select>";
         return $CMB;

}
function getServicePerson($userid,$post_to_si_display) {     // service coordinator for OT
        $CMB = " <select name='cmb_A_suserid'  id='cmb_A_suserid' $post_to_si_display class='form-control select'>";    //getEnquiryThrough(this.value)
         $CMB .= "<option value=''>Select</option>";
         if(stripos(json_encode($_SESSION['role']),'SERVICE COORDINATOR') == true ) $addsql = " and userid='".$_SESSION['SESSuserID']."'";
         if(stripos(json_encode($_SESSION['role']),'SALES COORDINATOR') !== false) $addsql = "";
         $SEL =  "Select userid,username from in_user where rolecode like '%SERVICE COORDINATOR%' and status='ACTIVE' $addsql";//and acclocationcode='".$_SESSION['SESSUserLocation']."'";
         $RES = mysql_query($SEL);
         while ($ARR = mysql_fetch_array($RES)) {
               $SEL = "";
               if($userid == $ARR['userid']){ $SEL =  "SELECTED";}
               $CMB .= "<option value='".trim($ARR['userid'])."' $SEL >".trim($ARR['userid'])." - ".trim($ARR['username'])."</option>";
         }
         $CMB .= "</select>";
         return $CMB;

}

function getSalesPerson($userid,$post_to_si_display) {     // service coordinator for OT
        $CMB = " <select name='cmb_A_userid'  id='cmb_A_userid' $post_to_si_display class='form-control select'>";    //getEnquiryThrough(this.value)
         $CMB .= "<option value=''>Select</option>";
         if(stripos(json_encode($_SESSION['role']),'SALES PERSON') !== false) $addsql = " and userid='".$_SESSION['SESSuserID']."'";
         else $addsql = "";
         $SEL =  "Select userid,username from in_user where rolecode like '%SALES PERSON%' and status='ACTIVE' $addsql";//and acclocationcode='".$_SESSION['SESSUserLocation']."'";
         $RES = mysql_query($SEL);
         while ($ARR = mysql_fetch_array($RES)) {
               $SEL = "";
               if($userid == $ARR['userid']){ $SEL =  "SELECTED";}
               $CMB .= "<option value='".trim($ARR['userid'])."' $SEL >".trim($ARR['userid'])." - ".trim($ARR['username'])."</option>";
         }
         $CMB .= "</select>";
         return $CMB;
}

function getSalesStaff($enquirystaff,$post_to_si_display) {
        $CMB = " <select name='cmb_A_enquirystaff'  id='cmb_A_enquirystaff' $post_to_si_display class='form-control select'>";    //getEnquiryThrough(this.value)
         $CMB .= "<option value=''>Select</option>";
         $SEL =  "Select userid,username from in_user where rolecode like '%SALE%' and status='ACTIVE' and acclocationcode='".$_SESSION['SESSUserLocation']."'";
         $RES = mysql_query($SEL);
         while ($ARR = mysql_fetch_array($RES)) {
               $SEL = "";
               if($enquirystaff == $ARR['userid']){ $SEL =  "SELECTED";}
               $CMB .= "<option value='".trim($ARR['userid'])."' $SEL >".trim($ARR['userid'])." - ".trim($ARR['username'])."</option>";
         }
         $CMB .= "</select>";
         return $CMB;

}
function GetOTServiceType($servicejob,$lock){
         $CMB = " <select name='cmb_A_servicejob' id='cmb_A_servicejob' $lock class='form-control select' style='width:100%;'>";
         $CMB .= "<option value=''></option>";
         $SEL = "SELECT lookcode,lookname FROM in_lookup_head WHERE looktype='OT SERVICES TYPE' and lookname <>'YY' order by lookname";
         $RES = mysql_query($SEL);
         while ($ARR = mysql_fetch_array($RES)) {
               $selected = "";
               if($servicejob == $ARR['lookcode']){ $selected =  "SELECTED";}
               $CMB .= "<option value='".trim($ARR['lookcode'])."' $selected >".trim($ARR['lookname'])."</option>";
         }
         $CMB .= "</select>";
         return $CMB;
}
function GetYesNo($underamc) {
         $CMB = " <select name='cmb_A_underamc'  id='cmb_A_underamc'   class='form-control select' onchange='showUnderAMC_fields(this.value);'>";
         $SEL =  "select lookcode,lookname from in_lookup_head where looktype='YESNO' and lookname<>'YY' order by slno";
         $RES = mysql_query($SEL);
         while ($ARR = mysql_fetch_array($RES)) {
               $SEL = "";
               if($ARR['lookcode']==$underamc){ $SEL =  "SELECTED";}
               $CMB .= "<option value='".$ARR['lookcode']."' $SEL >".$ARR['lookname']."</option>";
         }
         $CMB .= "</select>";
         return $CMB;
}
function GetDivisionForTextBox($company){
         $SEL =  "select code,division,in_lookup.lookname from in_locationdivision,in_lookup where in_locationdivision.division= in_lookup.lookcode and
                  locationcode='$company' and type='9002' order by lookname";
         $RES = mysql_query($SEL);
         $ARR = mysql_fetch_array($RES);

         return $ARR['division'];
}
 ?>
      <script src="jq/jquery-2.1.1.min.js"></script>
      <script src="plugins/jQuery/jquery-2.2.3.min.js"></script>
      <script src="bootstrap/js/bootstrap.min.js"></script>
      <script src="plugins/slimScroll/jquery.slimscroll.js"></script>
      <script src="plugins/select2/select2.full.min.js"></script>
      <script src="plugins/iCheck/icheck.min.js"></script>
      <script src="plugins/jqueryValidate/jquery.validate.js"></script>
      <script src="dist/js/app.js"></script>
      <script type="text/javascript" src="js/jquery-1.8.0.js"></script>
       <script src="plugins/jQuery/jquery-2.2.3.min.js"></script>
      <script src="bootstrap/js/bootstrap.min.js"></script>
      <script src="plugins/slimScroll/jquery.slimscroll.min.js"></script>
      <script src="plugins/select2/select2.full.min.js"></script>
      <script src="plugins/iCheck/icheck.min.js"></script>
      <script src="plugins/jqueryValidate/jquery.validate.js"></script>
      <script src="dist/js/app.js"></script>

    <script type='text/javascript'>
                $(window).load(function(){
                   boxHeight();
                   $(".select2").select2();
                   $(window).resize(function(){
                     boxHeight();
                   })

                });
                function boxHeight(){
                    $("body",parent.document).addClass('sidebar-collapse').trigger('collapsed.pushMenu');
                    var height = $("#content-wrapper-id",parent.document).height();
                    $('#tab-content-id').height(height);
                    var boxheight = height - 125;
                    $('#box-body-id').height(boxheight);

                    boxheight = boxheight-14;
                    $('#box-body-id').slimScroll({
                      height: boxheight +'px',
                      wheelStep: 100,
                      alwaysVisible: true

                    });


                }
function loadpage(i){
   if(i==2){
       document.frmEdit.action='editcalloutticket.php?dr=edit&ID='+document.getElementById('mode').value;
       document.frmEdit.submit();
   }
   if(i==3){
   var frame= document.getElementById('frame3');
   frame.src='otservicestype.php?doctype=LEAD&ID=<?echo $_REQUEST['ID']; ?>&txt_A_formtype=TICKET';
   frame.load();
   }
   if(i==4){
   $("span").html("");
   var frame= document.getElementById('frame4');
   frame.src='communication.php?ID=<?echo $_REQUEST['ID']; ?>';
   frame.load();
   }
   if(i==20){
   var frame= document.getElementById('frame20');
   //frame.src='documents.php?cid=<?echo $_REQUEST['ID']; ?> ';
   frame.src='jobdocuments.php?tickettype=AMCCOT&entitytype=Ticket Documents&ID=<?echo $_REQUEST['ID'];?>&txt_A_formtype=TICKET';
   frame.load();
   }
   if(i==13){
   var frame= document.getElementById('frame13');
   frame.src='otcombinedjob.php?ID=<?echo $_REQUEST['ID']; ?> ';
   frame.load();
   }
   if(i==14){
   var frame= document.getElementById('frame14');
   frame.src='completionreport.php?ID=<?echo $_REQUEST['ID']; ?>&txt_A_formtype=TICKET';
   frame.load();
   }
   if(i==15){
   var frame= document.getElementById('frame15');
   frame.src='otsubcontractjob.php?ID=<?echo $_REQUEST['ID']; ?> ';
   frame.load();
   }
   if(i==16){
   var frame= document.getElementById('frame16');
   frame.src='otvariationjob.php?ID=<?echo $_REQUEST['ID']; ?> ';
   frame.load();
   }

}
function postaddclient()
{
        var txtclientname=document.getElementById('txtclientname');
        if(txtclientname){
               if ((txtclientname.value==null)||(txtclientname.value=="")){
                   alertify.alert("Enter Client Name", function () {
                  txtclientname.focus();

               });
               return;
            }
        }
        var txtshortname=document.getElementById('txtshortname');
        if(txtshortname){
               if ((txtshortname.value==null)||(txtshortname.value=="")){
                   alertify.alert("Enter Short Name", function () {
                  txtshortname.focus();

               });
               return;
            }
        }
        var txtclientno=document.getElementById('txtclientno');
        if(txtclientno){
               if ((txtclientno.value==null)||(txtclientno.value=="")){
                   alertify.alert("Enter Client No", function () {
                  txtclientno.focus();

               });
               return;
            }
        }
        var txtclientaddress=document.getElementById('txtclientaddress');
        if(txtclientaddress){
               if ((txtclientaddress.value==null)||(txtclientaddress.value=="")){
                   alertify.alert("Enter Client Address", function () {
                  txtclientaddress.focus();

               });
               return;
            }
        }

                          xmlHttp=GetXmlHttpObject()
                          if (xmlHttp==null)
                          {
                                 alert ("Browser does not support HTTP Request")
                                 return
                          }


                          var url='combofunctions_service.php?level=addClientwithAccount&txtclientname='+txtclientname.value+'&txtshortname='+txtshortname.value+'&txtclientaddress='+txtclientaddress.value+'&txtclientno='+txtclientno.value;
                          xmlHttp.onreadystatechange=stateChangedaddclient
                          xmlHttp.open("POST",url,true)
                          xmlHttp.send(null)

}
function stateChangedaddclient()
{

   var html = $.ajax({
        type: "POST",
        url: "combofunctions_service.php",
        data: "level=popupmfg_client_withacc",
        async: false
    }).responseText;
    if(html){
        $("#cmb_A_objectcode").html(html);

    }
   if (xmlHttp.readyState==4 || xmlHttp.readyState=="complete")
   {
      var s1 = trim(xmlHttp.responseText);
      document.getElementById('cmb_A_objectcode').value=s1;
      $('#myModal45').modal('hide');
   }
}

function postmfg()
{
        var txtmanufacturer=document.getElementById('txtmanufacturer');
        if(txtmanufacturer){
               if ((txtmanufacturer.value==null)||(txtmanufacturer.value=="")){
                   alertify.alert("Enter Property Type", function () {
                  txtmanufacturer.focus();

               });
               return;
            }
        }

        xmlHttp=GetXmlHttpObject()
        if (xmlHttp==null)
        {
           alert ("Browser does not support HTTP Request")
           return
        }

        var url='combofunctions_service.php?level=addProperty&txtmanufacturer='+txtmanufacturer.value+'&client='+document.getElementById('cmb_A_objectcode').value;
        xmlHttp.onreadystatechange=stateChangedchildmfg
        xmlHttp.open("POST",url,true)
        xmlHttp.send(null)

}
function stateChangedchildmfg()
{

   var html = $.ajax({
        type: "POST",
        url: "combofunctions_service.php",
        data: "level=popupmfg2&client="+document.getElementById('cmb_A_objectcode').value,
        async: false
    }).responseText;
    if(html){
        $("#cmb_A_propertycode").html(html);

    }
   if (xmlHttp.readyState==4 || xmlHttp.readyState=="complete")
   {
      var s1 = trim(xmlHttp.responseText);
      document.getElementById('cmb_A_propertycode').value=s1;
      $('#myModal33').modal('hide');
   }
}

function popupmodeladdclient(){
      document.getElementById('txtclientname').value='';
      $('#myModal45').modal()
}
function popupmodel(){
      document.getElementById('txtmanufacturer').value='';
      $('#myModal33').modal()
}
function popupmodeladd(){
       document.getElementById('txtmodel').value='';
       var cmb_A_propertyname=document.getElementById('cmb_A_propertyname');
        if(cmb_A_propertyname){
               if ((cmb_A_propertyname.value==null)||(cmb_A_propertyname.value=="")){
                   alertify.alert("Select Property", function () {
                  cmb_A_propertyname.focus();

               });
               return;
            }
        }
      $('#myModal44').modal()
}
function postmodel() {

        var txtmodel=document.getElementById('txtmodel');
        if(txtmodel){
               if ((txtmodel.value==null)||(txtmodel.value=="")){
                   alertify.alert("Enter Building name", function () {
                  txtmodel.focus();

               });
               return;
            }
        }
        var txtmakaniid=document.getElementById('txtmakaniid');
        if(txtmakaniid){
               if ((txtmakaniid.value==null)||(txtmakaniid.value=="")){
                   alertify.alert("Enter Makani ID", function () {
                  txtmakaniid.focus();

               });
               return;
            }
        }
        var cmb_A_propertyname=document.getElementById('cmb_A_propertyname');
        var cmb_A_objectcode=document.getElementById('cmb_A_objectcode');

                          xmlHttp=GetXmlHttpObject()
                          if (xmlHttp==null)
                          {
                                 alert ("Browser does not support HTTP Request")
                                 return
                          }


                          var url='combofunctions_service.php?level=addBuilding&txtmakaniid='+txtmakaniid.value+'&txtmodel='+txtmodel.value+'&txtmfg='+cmb_A_propertyname.value+'&client='+cmb_A_objectcode.value;
                          //alert(url);
                          xmlHttp.onreadystatechange=stateChangedchildmodel
                          xmlHttp.open("POST",url,true)
                          xmlHttp.send(null)

}
function stateChangedchildmodel()
{
   var cmb_A_propertyname=document.getElementById('cmb_A_propertyname');
   var cmb_A_objectcode=document.getElementById('cmb_A_objectcode');
   var html = $.ajax({
        type: "POST",
        url: "combofunctions_service.php",
        data: "level=popupmodel2&mfg="+cmb_A_propertyname.value+"&client="+cmb_A_objectcode.value,
        async: false
    }).responseText;
    if(html){
        $("#cmb_A_buildingcode").html(html);

    }
   if (xmlHttp.readyState==4 || xmlHttp.readyState=="complete")
   {
      var s1 = trim(xmlHttp.responseText);
      document.getElementById('cmb_A_buildingcode').value=s1;
      $('#myModal44').modal('hide');
   }
}
function displaymodel(cattype){


      xmlHttp=GetXmlHttpObject()
      if (xmlHttp==null)
      {
                 alert ("Browser does not support HTTP Request")
                 return
      }

      var url="combofunctions_service.php?level=showmodel&categorytype="+cattype.value;
      xmlHttp.onreadystatechange=stateChangedcombo11
      xmlHttp.open("POST",url,true)
      xmlHttp.send(null)

}

function stateChangedcombo11(){
       if (xmlHttp.readyState==4 || xmlHttp.readyState=="complete"){

             var s1 = trim(xmlHttp.responseText);
             document.getElementById('showmodel').innerHTML=s1;
       }
}
</script>
<?
function GetWorkers($userid,$posted_lock){
        // echo $users;
         $CMB = "<select id='cmb_A_technician' name='cmb_A_technician' $posted_lock  class='form-control select2' style='width:100%;'>";
         $CMB .= "<option value=''>Select</option>";
         $SEL =  "Select userid,username from in_user where rolecode like '%SERVICE TECHNICIAN%' and status='ACTIVE' $addsql";//and acclocationcode='".$_SESSION['SESSUserLocation']."'";
         $RES = mysql_query($SEL);
         while ($ARR = mysql_fetch_array($RES)) {
               $SEL = "";
               if($userid == $ARR['userid']){ $SEL =  "SELECTED";}
               $CMB .= "<option value='".trim($ARR['userid'])."' $SEL >".trim($ARR['userid'])." - ".trim($ARR['username'])."</option>";
         }
         $CMB .= "</select>";
         return $CMB;
}
function GetCOTStatus($calloutstatus,$posted_lock,$Tab_validation) {
         $CMB = "<select id='cmb_A_calloutstatus' name='cmb_A_calloutstatus' $posted_lock class='form-control select' style='width:100%;' >";     // onchange='ShowMandatory(this.value);'
         $CMB .= "<option value=''>Select</option>";
         if($Tab_validation == "YES")
         $SQL =  "Select lookcode,lookname from in_lookup_head where looktype='CALLOUT STATUS' and lookname<>'YY' and lookcode not like 'Request for%'
         and lookcode<>'Convert to AMC COT' order by slno";//and acclocationcode='".$_SESSION['SESSUserLocation']."'";
         else
         $SQL =  "Select lookcode,lookname from in_lookup_head where looktype='CALLOUT STATUS' and lookname<>'YY' and lookcode not like 'Request for%'
         and lookcode<>'Convert to AMC COT'  and lookcode<>'(AMC COT) Complaint closed' order by slno";
         
         $RES = mysql_query($SQL);
         while ($ARR = mysql_fetch_array($RES)) {
               $SEL = "";
               if($calloutstatus == $ARR['lookcode']){ $SEL =  "SELECTED";}
               $CMB .= "<option value='".trim($ARR['lookcode'])."' $SEL >".$ARR['lookname']."</option>";
         }
         $CMB .= "</select>";
         return $CMB;
}
function getSiteIncharge($userid,$post_to_si_display) {     // Site incharge for OT
        $CMB = " <select name='cmb_A_suserid'  id='cmb_A_suserid' $post_to_si_display class='form-control select'>";    //getEnquiryThrough(this.value)
         $CMB .= "<option value=''>Select</option>";
         if(stripos(json_encode($_SESSION['role']),'SITE INCHARGE') == false ) $addsql = "";
         else $addsql = "and userid='".$_SESSION['SESSuserID']."'";
         $SEL =  "Select userid,username from in_user where rolecode like '%SITE INCHARGE%' and status='ACTIVE' $addsql";//and acclocationcode='".$_SESSION['SESSUserLocation']."'";
         $RES = mysql_query($SEL);
         while ($ARR = mysql_fetch_array($RES)) {
               $SEL = "";
               if($userid == $ARR['userid']){ $SEL =  "SELECTED";}
               $CMB .= "<option value='".trim($ARR['userid'])."' $SEL >".trim($ARR['userid'])." - ".trim($ARR['username'])."</option>";
         }
         $CMB .= "</select>";
         return $CMB;

}
function getServiceCordinator($userid){
         if($userid=='') $userid="UFM027";
         $CMB = " <select name='cmb_A_servicestaff'  id='cmb_A_servicestaff' class='form-control select'>";    //getEnquiryThrough(this.value)
         $CMB .= "<option value=''>Select</option>";
         $SEL =  "Select userid,username from in_user where rolecode like '%SERVICE COORDINATOR%' and status='ACTIVE' "; //and acclocationcode='".$company."'
         $RES = mysql_query($SEL);
         while ($ARR = mysql_fetch_array($RES)) {
               $SEL = "";
               if($userid == $ARR['userid']){ $SEL =  "SELECTED";}
               $CMB .= "<option value='".trim($ARR['userid'])."' $SEL >".trim($ARR['userid'])." - ".trim($ARR['username'])."</option>";
         }
         $CMB .= "</select>";
         return $CMB;

}
function getAreaIncharge($userid,$post_to_sp_display) {     // Area incharge for OT
        
        $CMB = " <select name='cmb_A_areaincharge'  id='cmb_A_areaincharge' $post_to_sp_display class='form-control select2'>";    //getEnquiryThrough(this.value)
         $CMB .= "<option value=''>Select</option>";
         if(stripos(json_encode($_SESSION['role']),'FACILITY MANAGER') == false ) $addsql = "";
         else $addsql = "and userid='".$_SESSION['SESSuserID']."'";
         $SEL =  "Select userid,username from in_user where rolecode like '%FACILITY MANAGER%' and status='ACTIVE' $addsql";//and acclocationcode='".$_SESSION['SESSUserLocation']."'";
         $RES = mysql_query($SEL);
         while ($ARR = mysql_fetch_array($RES)) {
               $SEL = "";
               if($userid == $ARR['userid']){ $SEL =  "SELECTED";}
               $CMB .= "<option value='".trim($ARR['userid'])."' $SEL >".trim($ARR['userid'])." - ".trim($ARR['username'])."</option>";
         }
         $CMB .= "</select>";
         return $CMB;

}
?>
