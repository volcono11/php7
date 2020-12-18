<?php
session_start();
if($_SESSION['pr'] == "") {
  $_SESSION['pr'] = $_REQUEST['pr'];
}

date_default_timezone_set('Asia/Dubai');
require "connection.php";
require "pagingObj_pro.php";
include("functions_workflow.php");
//require "mail_pro.php";

//print_r($_REQUEST);

if($_REQUEST['approve']=="SFA"){
###workflow###
    $APPROVAL_users = "";
    $status = "Waiting for (PR) Purchase Coordinator Approval"; //  Purchase Coordinator
    $checkworkflow = checkforWorkflow("INVENTORY","PURCHASE INDENT");
	if($checkworkflow == "YES"){
		$SQL3 = "select docno,purchasesendto ,jobtype,jobno from in_inventoryhead where id='".$_REQUEST['ID']."'";
        $RES3 =  mysql_query($SQL3);
        $ARR3 = mysql_fetch_array($RES3);
        $jobtype=$ARR3['jobtype'];
        $jobno=$ARR3['jobno'];
        $APPROVAL_users = $ARR3['purchasesendto'];
		$alert_message = "PRNO: ".$ARR3['docno'].", Waiting for (PR) Purchase Coordinator Approval! ";
		echo SendAlerts("INVENTORY","PURCHASE INDENT",$APPROVAL_users,$alert_message);
		echo SendSMS("INVENTORY","PURCHASE INDENT",$APPROVAL_users,$alert_message);
		echo SendEmail("INVENTORY","PURCHASE INDENT",$APPROVAL_users,$alert_message,$alert_message); // last 2 are subject and message
		
		/*if($jobtype == "AMC"){  // updating values
						$Jobarr = mysql_fetch_array(mysql_query("select hardthreshold,softthreshold,generalthreshold from t_activitycenter where jobno='$jobno'"));
				   		$hardthreshold = $Jobarr['hardthreshold'];
				   		$softthreshold = $Jobarr['softthreshold'];
				   		$generalthreshold = $Jobarr['generalthreshold'];
				   		//$Jobarr = mysql_fetch_array(mysql_query("select sum(hardthreshold) as hardthreshold,sum(softthreshold) as softthreshold,sum(generalthreshold) as generalthreshold from in_ where jobno='$jobno'"));
	}*/
	
		$pqy="Update in_inventoryhead set sitesendforapproval='$status',posted='YES',post_to_pc_date='".date('Y-m-d H:i:s')."',created_date='',subcontractvalue='". $_REQUEST['salevalue']."'  where id='". $_REQUEST['ID']."'";//,hardthreshold='$hardthreshold',softthreshold='$softthreshold',generalthreshold='$generalthreshold'
        mysql_query($pqy);
	}
   ### end of workflow###
	else{ // No workflow
        $SQL1 = "UPDATE in_inventoryhead SET sitesendforapproval='$status',posted='YES',post_to_pc_date='".date('Y-m-d H:i:s')."',created_date='' where id='".$_REQUEST['ID']."'";
        mysql_query($SQL1) or die(mysql_error()."PA-115<br>".$SQL1);
	}
	
				   
}

if($_REQUEST['POST']=="OMREVISE"){
###workflow###
    $APPROVAL_users = "";
    $status = "(PR) Revised by Manager";
    $checkworkflow = checkforWorkflow("INVENTORY","PURCHASE INDENT");
	if($checkworkflow == "YES"){
		$SQL3 = "select docno,requestedby,purchasesendto from in_inventoryhead where id='".$_REQUEST['ID']."'";
        $RES3 =  mysql_query($SQL3);
        $ARR3 = mysql_fetch_array($RES3);
        $APPROVAL_users = $ARR3['purchasesendto'];
		$alert_message = "PRNO: ".$ARR3['docno'].", (PR) Revised by Manager";
		echo SendAlerts("INVENTORY","PURCHASE INDENT",$APPROVAL_users,$alert_message);
		echo SendSMS("INVENTORY","PURCHASE INDENT",$APPROVAL_users,$alert_message);
		echo SendEmail("INVENTORY","PURCHASE INDENT",$APPROVAL_users,$alert_message,$alert_message); // last 2 are subject and message
		$pqy="Update in_inventoryhead set sitesendforapproval='$status',post_to_pm='NO',post_to_pm_date='',post_to_pc_date='".date('Y-m-d H:i:s')."'  where id='". $_REQUEST['ID']."'";
        mysql_query($pqy);
	}
   ### end of workflow###
	else{ // No workflow
        $SQL1 = "UPDATE in_inventoryhead SET sitesendforapproval='$status' where id='".$_REQUEST['ID']."'";
        mysql_query($SQL1) or die(mysql_error()."PA-115<br>".$SQL1);
	}
}

if($_REQUEST['POST']=="SCREVISE"){
###workflow###
    $APPROVAL_users = "";
    $status = "(PR) Revised by Purchase Coordinator";
    $checkworkflow = checkforWorkflow("INVENTORY","PURCHASE INDENT");
	if($checkworkflow == "YES"){
		$SQL3 = "select docno,requestedby from in_inventoryhead where id='".$_REQUEST['ID']."'";
        $RES3 =  mysql_query($SQL3);
        $ARR3 = mysql_fetch_array($RES3);
        $APPROVAL_users = $ARR3['requestedby'];
		$alert_message = "PRNO: ".$ARR3['docno'].", (PR) Revised by Purchase Coordinator";
		echo SendAlerts("INVENTORY","PURCHASE INDENT",$APPROVAL_users,$alert_message);
		echo SendSMS("INVENTORY","PURCHASE INDENT",$APPROVAL_users,$alert_message);
		echo SendEmail("INVENTORY","PURCHASE INDENT",$APPROVAL_users,$alert_message,$alert_message); // last 2 are subject and message
		$pqy="Update in_inventoryhead set sitesendforapproval='$status',posted='NO',post_to_pc='NO',post_to_pc_date='',created_date='".date('Y-m-d H:i:s')."'  where id='". $_REQUEST['ID']."'";
        mysql_query($pqy);
	}
   ### end of workflow###
	else{ // No workflow
        $SQL1 = "UPDATE in_inventoryhead SET sitesendforapproval='$status' where id='".$_REQUEST['ID']."'";
        mysql_query($SQL1) or die(mysql_error()."PA-115<br>".$SQL1);
	}
}

if($_REQUEST['POST']=="SCREJECT"){
###workflow###
    $APPROVAL_users = "";
    $status = "(PR) Rejected by Purchase Coordinator";
    $checkworkflow = checkforWorkflow("INVENTORY","PURCHASE INDENT");
	if($checkworkflow == "YES"){
		$SQL3 = "select docno,purchasesendto,requestedby from in_inventoryhead where id='".$_REQUEST['ID']."'";
        $RES3 =  mysql_query($SQL3);
        $ARR3 = mysql_fetch_array($RES3);
        $APPROVAL_users = $ARR3['requestedby'];
		$alert_message = "Purchase Request No : ".$ARR3['docno']." Rejected by Purchase Coordinator";
		echo SendAlerts("INVENTORY","PURCHASE INDENT",$APPROVAL_users,$alert_message);
		echo SendSMS("INVENTORY","PURCHASE INDENT",$APPROVAL_users,$alert_message);
		echo SendEmail("INVENTORY","PURCHASE INDENT",$APPROVAL_users,$alert_message,$alert_message); // last 2 are subject and message
		$pqy="Update in_inventoryhead set sitesendforapproval='$status',post_to_si='YES',post_to_si_date='".date('Y-m-d H:i:s')."',post_to_pc_date='',
        nb='".$_REQUEST['reason']."',converted='YES'  where id='". $_REQUEST['ID']."'";
        mysql_query($pqy);
	}
   ### end of workflow###
	else{ // No workflow

  $SQL1 = "UPDATE in_inventoryhead SET sitesendforapproval='$status',post_to_si='YES',post_to_si_date='".date('Y-m-d H:i:s')."',post_to_pc_date=''
  ,nb='".$_REQUEST['reason']."',converted='YES' where id='".$_REQUEST['ID']."'";
  mysql_query($SQL1) or die(mysql_error()."PA-115<br>".$SQL1);
	}
}
if($_REQUEST['POST']=="OMREJECT"){
###workflow###
    $APPROVAL_users = "";
    $status = "(PR) Rejected by Manager";
    $checkworkflow = checkforWorkflow("INVENTORY","PURCHASE INDENT");
	if($checkworkflow == "YES"){
		$SQL3 = "select docno,purchasesendto,requestedby,formsendto from in_inventoryhead where id='".$_REQUEST['ID']."'";
        $RES3 =  mysql_query($SQL3);
        $ARR3 = mysql_fetch_array($RES3);
        $APPROVAL_users = $ARR3['requestedby'].",".$ARR3['purchasesendto'];
		$alert_message = "Purchase Request No : ".$ARR3['docno']." Rejected by Manager";
		echo SendAlerts("INVENTORY","PURCHASE INDENT",$APPROVAL_users,$alert_message);
		echo SendSMS("INVENTORY","PURCHASE INDENT",$APPROVAL_users,$alert_message);
		echo SendEmail("INVENTORY","PURCHASE INDENT",$APPROVAL_users,$alert_message,$alert_message); // last 2 are subject and message
		$pqy="Update in_inventoryhead set sitesendforapproval='$status',post_to_si='YES',post_to_pm_date='',nb='".$_REQUEST['reason']."',converted='YES'  where id='". $_REQUEST['ID']."'";
        mysql_query($pqy);
	}
   ### end of workflow###
	else{ // No workflow

  $SQL1 = "UPDATE in_inventoryhead SET sitesendforapproval='$status',post_to_pm_date='',post_to_si='YES',nb='".$_REQUEST['reason']."',converted='YES' where id='".$_REQUEST['ID']."'";
  mysql_query($SQL1) or die(mysql_error()."PA-115<br>".$SQL1);
	}
}


if($_REQUEST['POST']=="SCAPPROVE"){
###workflow###
    $APPROVAL_users = "";
    $checkworkflow = checkforWorkflow("INVENTORY","PURCHASE INDENT");
    $status = "Waiting for (PR) Manager Approval";
	if($checkworkflow == "YES"){
		$Wf_arr = explode("@",GetWorkFlow("INVENTORY","PURCHASE INDENT"));
		$APPROVAL_users = $Wf_arr[1];  //print_r($_REQUEST); exit;
		/************Field Approval*******************/
	/*	$WF_type = $Wf_arr[4];
		if($WF_type == "FIELD APPROVAL"){
           $get_PI_Qty = getPurchaseQty($_REQUEST['saveid']);
           $Wf_arr_field = explode("@",Get_WF_Puchase("INVENTORY","PURCHASE INDENT",$_REQUEST['cmb_A_store'],$get_PI_Qty));
           $APPROVAL_users = $Wf_arr_field[1];
           $APPROVALBY    =  $Wf_arr_field[3];
		   $APPROVALCOUNT  =  $Wf_arr_field[2];
		}
		/************************************************/
		$SQL3 = "select docno from in_inventoryhead where id='".$_REQUEST['ID']."'";
        $RES3 =  mysql_query($SQL3);
        $ARR3 = mysql_fetch_array($RES3);
		$alert_message = "PRNo : ".$ARR3['docno'].",Waiting for Manager Approval";
		echo SendAlerts("INVENTORY","PURCHASE INDENT",$APPROVAL_users,$alert_message);
		echo SendSMS("INVENTORY","PURCHASE INDENT",$APPROVAL_users,$alert_message);
		echo SendEmail("INVENTORY","PURCHASE INDENT",$APPROVAL_users,$alert_message,$alert_message); // last 2 are subject and message
		$pqy="Update in_inventoryhead set sitesendforapproval='$status',formsendto='".$APPROVAL_users."',posted='YES',post_to_pm='YES',post_to_pm_date='".date('Y-m-d H:i:s')."',post_to_pc_date='' where id='". $_REQUEST['ID']."'";
        mysql_query($pqy);
	}
   ### end of workflow###
	else{ // No workflow

  $SQL1 = "UPDATE in_inventoryhead SET sitesendforapproval='$status',posted='YES' where id='".$_REQUEST['ID']."'";
  mysql_query($SQL1) or die(mysql_error()."PA-115<br>".$SQL1);
	}
}

if($_REQUEST['attdelete']!=""){
  $SQL1 = "UPDATE in_inventoryhead SET userfile='' where userfile='".$_REQUEST['attdelete']."'";
  mysql_query($SQL1) or die(mysql_error()."PA-115<br>".$SQL1);
  unlink("procurement/mir/".$_REQUEST['attdelete']);
}

if($_REQUEST['POST']=="OMAPPROVE"){
 ###workflow###
	$checkworkflow = checkforWorkflow("INVENTORY","PURCHASE INDENT");
	$status = 'Waiting for PO preparation by Purchase Coordinator';
	if($checkworkflow == "YES"){
		$Wf_arr = explode("@",GetWorkFlow("INVENTORY","PURCHASE INDENT"));
		$APPROVALBY    =  $Wf_arr[3];
		$APPROVALCOUNT    =  $Wf_arr[2];
		 /************Field Approval*******************/
	/*	$WF_type = $Wf_arr[4];
		if($WF_type == "FIELD APPROVAL"){
           $get_PI_Qty = getPurchaseQty($_REQUEST['saveid']);
           $Wf_arr_field = explode("@",Get_WF_Puchase("INVENTORY","PURCHASE INDENT",$_REQUEST['cmb_A_store'],$get_PI_Qty));
           $APPROVAL_users = $Wf_arr_field[1];
           $APPROVALBY    =  $Wf_arr_field[3];
		   $APPROVALCOUNT  =  $Wf_arr_field[2];
		}
		/************************************************/
	     $SQL2 = "UPDATE in_inventoryhead SET approvalcount=approvalcount+1,approvedby=concat(approvedby,'".$_SESSION['SESSuserID'].",') where id='".$_REQUEST['ID']."'";
         mysql_query($SQL2);
        $SQL3 = "select approvalcount from in_inventoryhead where id='".$_REQUEST['ID']."'";
        $RES3 =  mysql_query($SQL3);
        $ARR3 = mysql_fetch_array($RES3);
        if( $ARR3['approvalcount'] == $APPROVALCOUNT ){
          $SQL4 = "UPDATE in_inventoryhead SET sitesendforapproval='$status',post_to_pm_date='',post_to_pc='YES',post_to_pc_date='".date('Y-m-d H:i:s')."',signatory='".$_SESSION['SESSuserID']."' where id='".$_REQUEST['ID']."'";
          mysql_query($SQL4);

		$SQL3 = "select docno,purchasesendto from in_inventoryhead where id='".$_REQUEST['ID']."'";
        $RES3 =  mysql_query($SQL3);
        $ARR3 = mysql_fetch_array($RES3);
		$alert_message = " PRNO : ".$ARR3['docno'].", Waiting for PO preparation";
		$APPROVAL_users = $ARR3['purchasesendto'];
		echo SendAlerts("INVENTORY","PURCHASE INDENT",$APPROVAL_users,$alert_message);
		echo SendSMS("INVENTORY","PURCHASE INDENT",$APPROVAL_users,$alert_message);
		echo SendEmail("INVENTORY","PURCHASE INDENT",$APPROVAL_users,$alert_message,$alert_message); // last 2 are subject and message

	    }
	}
  ########################################
  else{
  $SQL1 = "UPDATE in_inventoryhead SET sitesendforapproval='$status',post_to_pm_date='',post_to_pm='YES',post_to_pm_date='".date('Y-m-d H:i:s')."' where id='".$_REQUEST['ID']."'";
  mysql_query($SQL1) or die(mysql_error()."PA-115<br>".$SQL1);
  }
}
if($_REQUEST['approveit']=="cancelit"){
  $SQL1 = "UPDATE in_inventoryhead SET sitesendforapproval='REJECTED' where id='".$_REQUEST['ID']."'";
  mysql_query($SQL1) or die(mysql_error()."PA-115<br>".$SQL1);

        $SQL3 = "select docno,userid from in_inventoryhead where id='".$_REQUEST['ID']."'";
        $RES3 =  mysql_query($SQL3);
        $ARR3 = mysql_fetch_array($RES3);
		$alert_message = " Material request : ".$ARR3['docno']." has beed Cancelled";
		$APPROVAL_users = $ARR3['userid'];
		echo SendAlerts("INVENTORY","PURCHASE INDENT",$APPROVAL_users,$alert_message);
		echo SendSMS("INVENTORY","PURCHASE INDENT",$APPROVAL_users,$alert_message);
		echo SendEmail("INVENTORY","PURCHASE INDENT",$APPROVAL_users,$alert_message,$alert_message); // last 2 are subject and message
}

if($_REQUEST['ID'] != "0") {
             $mode=$_REQUEST['ID'];
             $SQL = " Select *,DATE_FORMAT(docdate,'%d-%m-%Y') as docdate,DATE_FORMAT(deliverydate,'%d-%m-%Y') as deliverydate,DATE_FORMAT(expstartdate,'%d-%m-%Y') as expstartdate,DATE_FORMAT(expenddate,'%d-%m-%Y') as expenddate
                      from in_inventoryhead where id='".$_REQUEST['ID']."'";;
             $SQLRes =  mysql_query($SQL) or die(mysql_error()."<br>".$SQL);
              if(mysql_num_rows($SQLRes)>=1){
                while($loginResultArray   = mysql_fetch_array($SQLRes)){
                   $mode =  $loginResultArray['id'];
                   $jobtype = $loginResultArray['jobtype'];
				   #### workflow ####
                   $formsendto = $loginResultArray['formsendto'];
				   $approvedby = $loginResultArray['approvedby'];
				   $approvalrole = $loginResultArray['approvalrole'];
				   $formapprovalcount = $loginResultArray['approvalcount'];
				   ###################
				   $purchasesendto =  $loginResultArray['purchasesendto'];
				   $converted =  $loginResultArray['converted'];
				   $post_to_pc =  $loginResultArray['post_to_pc'];
                   $post_to_si =  $loginResultArray['post_to_si'];
				   $post_to_pm =  $loginResultArray['post_to_pm'];
				   $created_date = $loginResultArray['created_date'];
				   $createdon = $loginResultArray['createdon'];
				   $post_to_pm = $loginResultArray['post_to_pm'];
                   $docno = $loginResultArray['docno'];
                   $doctype= $loginResultArray['doctype'];
                   $jobno = $loginResultArray['jobno'];
                   $projectcode = $loginResultArray['projectcode'];
                   $propertycode = $loginResultArray['propertytype'];
                   $buildingcode = $loginResultArray['buildingcode'];
                   $extendeddays = $loginResultArray['extendeddays'];
                   $company = $loginResultArray['company'];
                   $division = $loginResultArray['division'];
                   if($extendeddays!="") $dis_extendeddays = 'display';
                   else $dis_extendeddays = 'none';
                   $docdate=  $loginResultArray['docdate'];
                   if($docdate=='00-00-0000')$docdate="";
                   $deliverydate= $loginResultArray['deliverydate'];
                   if($deliverydate=='00-00-0000')$deliverydate="";
                   $store= $loginResultArray['store'];
                   $priority = $loginResultArray['priority'];
                   $remarks = $loginResultArray['remarks'];
                   $requestedby = $loginResultArray['requestedby'];
                   $parentdocno = $loginResultArray['parentdocno'];
                   $parentdoctype = $loginResultArray['parentdoctype'];
                   $disable = "";
                   if($parentdoctype == "MI-INDENT"){
                      $disable = "disabled";
                   }

                   $nb = $loginResultArray['nb'];
                   if($nb!=''){
                    $displayreason="table-row" ;
                   }else{
                    $displayreason="none" ;
                   }

                   $_SESSION['parentdoctype'] = $parentdoctype;
                   $Form_approvals = $loginResultArray['approvedby'];
                   $sitesendforapproval = $loginResultArray['sitesendforapproval'];
                   $_SESSION['sitesendforapproval']=$sitesendforapproval;

                   $checkuserid  = $loginResultArray['userid'];
                   $_SESSION['itemsuserid']=$checkuserid;
                   $posted= $loginResultArray['posted'];
                   $projectname=$loginResultArray['projectname'];
                   $propertytype=$loginResultArray['propertytype'];
                   $propertyno=$loginResultArray['propertyno'];
                   $location=$loginResultArray['location'];
                   $siteincharge=$loginResultArray['siteincharge'];
                   $siteinchargecontactno=$loginResultArray['siteinchargecontactno'];
                   $quoteno=$loginResultArray['quoteno'];
                   $quoteversion=$loginResultArray['quoteversion'];
                   if($quoteversion == "0") $quotation =  $quoteno;
                   else $quotation = $quoteno.'-V'.$quoteversion;
                   $MyArr1=mysql_fetch_array(mysql_query("SELECT MAX(id) as id FROM in_crmhead WHERE docno='$quoteno' and version='$quoteversion'"));
                   $parentdocid= $MyArr1['id'];
                   $jobordervalue=$loginResultArray['jobordervalue'];
                   $subcontractvalue=$loginResultArray['subcontractvalue'];
                   $purchasetype=$loginResultArray['purchasetype'];
                   $purchaserequestvalue=$loginResultArray['purchaserequestvalue'];
                   $pidocname = $loginResultArray['pidocname'];
                   $sp_supplier1_name = $loginResultArray['sp_supplier1_name'];
                   $scp_supplier1_name =  $loginResultArray['scp_supplier1_name'];
                   $cp_bill_docname =  $loginResultArray['cp_bill_docname'];
                   $propertyno =  $loginResultArray['propertyno'];
                   $buildingname =  $loginResultArray['buildingname'];
                   $purchasecategory = $loginResultArray['purchasecategory'];
                   $purchasesubcategory = $loginResultArray['purchasesubcategory']; 
                   $purchasecategorytext = $loginResultArray['purchasecategorytext'];
                   $hardthreshold = $loginResultArray['hardthreshold'];
				   $softthreshold = $loginResultArray['softthreshold'];
				   $generalthreshold = $loginResultArray['generalthreshold'];
                   
                   if($purchasecategory == "GENERAL" || $purchasecategory == "Others" || ($purchasecategory == "HARD SERVICES" && $purchasetype =="Subcontractor Purchase" )) {
                      $div_psc_display = "none";
                      $div_psc2_display = "block";
                   }
                   else{
                       $div_psc_display = "block";
                       $div_psc2_display = "none";
                   }
                   
                   /*if($jobtype == "AMC") $jobLable = "Contract No";
                   else if($jobtype == "NA") $jobLable = "";
     		       else $jobLable = "Job No";*/
                   if($jobtype == "AMC"){  // updating values
						//$Jobarr = mysql_fetch_array(mysql_query("select DATE_FORMAT(expstartdate,'%d-%m-%Y') as expstartdate,DATE_FORMAT(expenddate,'%d-%m-%Y') as expenddate,durationtype,duration from t_activitycenter where jobno='$jobno'"));
                        $durationtype = $loginResultArray['durationtype'];
                        $duration = $loginResultArray['duration'];
                        $expstartdate = $loginResultArray['expstartdate'];
                        $expenddate = $loginResultArray['expenddate'];
	               }
	               
	               if($jobtype == "AMC") {
                   	//$jobLable = "Contract No";
                   	//Calc threshold values
                   	$tsql = "select (sum(totalgrossamt)-sum(totalvatamt)) as purchasemade,purchasecategory from in_inventoryhead where jobno='$jobno' and doctype='PURCHASEORDER' and (sitesendforapproval='(PO)Released to Supplier & Waiting for Delivery Note' or sitesendforapproval='(PO) Completed') group by purchasecategory order by id desc";
                   	$tres = mysql_query($tsql);
                   	while($tarr = mysql_fetch_array($tres)){
                   	if($tarr['purchasecategory'] == "HARD SERVICES") $totalhardpurchase = $tarr['purchasemade'];
                   	if($tarr['purchasecategory'] == "SOFT SERVICES") $totalsoftpurchase = $tarr['purchasemade'];
                   	if($tarr['purchasecategory'] == "GENERAL") $totalgeneralpurchase = $tarr['purchasemade'];
					}
			       }
			       
			       if($jobtype=="OT"){
						$sql9 = "select materialexpencess,manpowerexpencess from t_activitycenter where jobno='$jobno'";
						$res9 = mysql_query($sql9);
						$arr9 = mysql_fetch_array($res9);
						$materialexpencess = $arr9['materialexpencess'];
				   }
				   
				   if($jobtype=="AMC" && $subcontractvalue=="" && $purchasetype=="Subcontractor Purchase" && $purchasecategory=="SPECIALIZED SERVICES" ){
				   		$sql = "select in_crmline.total from in_crmhead ,in_crmline 
where in_crmhead.jobno='".$jobno."' and in_crmhead.doctype='QUOTE' and in_crmline.invheadid=in_crmhead.id and in_crmline.articlecode ='".$purchasesubcategory."'";
						$res = mysql_query($sql);
						$arr = mysql_fetch_array($res);
	
						$subcontractvalue = $arr['total'];	
				   	
				   }
				   $date_lock="disabled";
                   $post_to_pc_lock = "";
                   $displayquote="true";
                   if($posted == "YES") {
                      $post_to_pc_lock = "disabled";
                      $post_readonly = "readonly";
                   }
                   $serviceoffered = $loginResultArray['serviceoffered'];
                   //$empid = $loginResultArray['empid'];
                   $empid = $siteincharge;
                   $contactphone = $loginResultArray['contactphone'];
                   $contactdetails = $loginResultArray['contactdetails'];
                   $_REQUEST['dr']="edit";
                 }
              }


}else{
             $mode="";
             $docno="PR-".str_pad(GetLastSqeID('PI'), 5, '0', STR_PAD_LEFT);
             $checkuserid = $_SESSION['SESSuserID'];
             $sitesendforapproval= "PR Created";
             $parentdoctype = "PUR-INDENT";
             $img="";
             $_SESSION['sitesendforapproval']=$sitesendforapproval;
             $_SESSION['itemsuserid']="";
             $requestedby = $_SESSION['SESSuserID'];
             $jobno = "";
             $created_date = date('Y-m-d H:i:s');
             $createdon = date('Y-m-d H:i:s');
             $displayreason="none" ;
             $displayquote="none";
             $jobtype = $_REQUEST['cmb_lookuplist'];
             $propertyno = 'NA';
            /* if($jobtype == "AMC") $jobLable = "Contract No";
             else if($jobtype == "NA") $jobLable = "";
             else $jobLable = "Job No";*/
             $div_psc_display = "block";
             $div_psc2_display = "none";
             if(stripos(json_encode($_SESSION['role']),'HR MANAGER') == true || stripos(json_encode($_SESSION['role']),'FRONT OFFICE CLERK') == true) 
             $empid = $_SESSION['SESSuserID'];
             $contactphone = getContactDetails($empid,'empworkmobile');
             $contactdetails = getContactDetails($empid,'emplocaladdress');
             if($jobtype == "AMC" && stripos(json_encode($_SESSION['role']),'SALES COORDINATOR') == true)
             $purchasetype = "Subcontractor Purchase";
             if($purchasetype != "Subcontractor Purchase"){
			 	$puchasetype_lock = "readonly";
			 }
             //$store = GetstoreName();
}
 if($_REQUEST['ticketmaterial']=='yes'){ 
             $mode="";
             $docno="PR-".str_pad(GetLastSqeID('PI'), 5, '0', STR_PAD_LEFT);
             $checkuserid = $_SESSION['SESSuserID'];
             $sitesendforapproval= "PR Created";
             $parentdoctype = "PUR-INDENT";
             $img="";
             $_SESSION['sitesendforapproval']=$sitesendforapproval;
             $_SESSION['itemsuserid']="";
             $requestedby = $_SESSION['SESSuserID'];
             $jobno = $_REQUEST['jobno'];
             $req_job_type = $_REQUEST['jobtype'];
             $created_date = date('Y-m-d H:i:s');
             $createdon = date('Y-m-d H:i:s');
             $displayreason="none" ;
             if($req_job_type=='AMC'){
			 $TSQL = "select t_activitycenter.id,in_project.projectname,tbl_projectcontracts.projectcode,t_activitycenter.quoteno,t_activitycenter.quoteversion,jobvalue,clientcode,salesorderno,DATE_FORMAT(t_activitycenter.expstartdate,'%d-%m-%Y') as expstartdate,if((t_activitycenter.extendedto='' or t_activitycenter.extendedto='0000-00-00'),DATE_FORMAT(t_activitycenter.expenddate,'%d-%m-%Y'),DATE_FORMAT(t_activitycenter.extendedto,'%d-%m-%Y')) as expenddate,t_activitycenter.duration,t_activitycenter.durationtype,t_activitycenter.extendedperiod,t_activitycenter.jobtype as jobtype,tbl_projectcontracts.company,tbl_projectcontracts.division,t_activitycenter.hardthreshold,t_activitycenter.softthreshold,t_activitycenter.generalthreshold
from t_activitycenter left join tbl_projectcontracts on t_activitycenter.jobno=tbl_projectcontracts.contractcode 
left join in_project on  in_project.projectcode=tbl_projectcontracts.projectcode
where t_activitycenter.jobno='$jobno' and tbl_projectcontracts.contractstatus='Active' and tbl_projectcontracts.posted='YES' and in_project.status='Active'";	
			 }
			 else{
             $TSQL="select t_activitycenter.projectname as projectname,t_activitycenter.projectcode as projectcode,
             t_activitycenter.propertycode as propertytype,t_activitycenter.buildingcode,t_activitycenter.propertyno as propertyno,t_activitycenter.floordetails as location,t_activitycenter.company,t_activitycenter.division,
             t_activitycenter.buildingname as buildingname,t_activitycenter.quoteno as quoteno,t_activitycenter.quoteversion as quoteversion,t_activitycenter.jobvalue as jobvalue,in_incharges.inchargename as siteincharge,
             in_incharges.mobile1 as siteinchargecontactno,t_activitycenter.jobtype as jobtype from t_activitycenter left join in_incharges on t_activitycenter.jobno=in_incharges.jobno
             where t_activitycenter.jobno='$jobno'  and in_incharges.inchargestatus='Active' and in_incharges.type='JOB'" ;
			}
			// echo $TSQL;
             $TRES = mysql_query($TSQL);
             $TARR = mysql_fetch_array($TRES);
             $jobtype= $TARR['jobtype'];
             /*if($jobtype == "AMC") $jobLable = "Contract No";
             else $jobLable = "Job No";*/
             $projectname= $TARR['projectname'];
             $buildingcode = $TARR['buildingcode'];
             $buildingname = $TARR['buildingname'];
             $projectcode= $TARR['projectcode'];
             $propertytype= $TARR['propertytype'];
             $propertyno= $TARR['propertyno'];
             $durationtype = $TARR['durationtype'];
             $duration = $TARR['duration'];
             $expstartdate = $TARR['expstartdate'];
             $expenddate = $TARR['expenddate'];
             $extendeddays = $TARR['extendedperiod'];
             $company = $TARR['company'];
             $division = $TARR['division'];
             if($extendeddays!="") $dis_extendeddays = 'display';
                   else $dis_extendeddays = 'none';
             if($propertyno=="") $propertyno = 'NA';
             $location= $TARR['location'];
             $siteincharge= $TARR['siteincharge'];
             $siteinchargecontactno= $TARR['siteinchargecontactno'];
             $quoteno=$TARR['quoteno'];
             $quoteversion=$TARR['quoteversion'];
             if($jobtype == "AMC" && stripos(json_encode($_SESSION['role']),'SALES COORDINATOR') == true)
             $purchasetype = "Subcontractor Purchase";
             
             if($quoteversion == "0") $quotation =  $quoteno;
             else $quotation = $quoteno.'-V'.$quoteversion;
             $MyArr1=mysql_fetch_array(mysql_query("SELECT MAX(id) as id FROM in_crmhead WHERE docno='$quoteno' and version='$quoteversion'"));
             $parentdocid= $MyArr1['id'];
             //if($purchasetype!='Subcontractor Purchase')
             $jobordervalue=$TARR['jobvalue'];
             $hardthreshold = $TARR['hardthreshold'];
			 $softthreshold = $TARR['softthreshold'];
			 $generalthreshold = $TARR['generalthreshold'];
             $disable="disabled";
             $displayquote="true";
             $div_psc_display = "block";
             $div_psc2_display = "none";
             
 }
if($_REQUEST['dr']=='view'){
      $edit="none";
      $view="inline";
      $title="Viewing Purchase request : $docno";
}else if($_REQUEST['dr']=='edit'){
      $edit="inline";
      $view="none";
      $title="Editing Purchase request :  $docno";
}else{
      $edit="inline";
      $view="none";
      $title="Adding Purchase request";
}

          $grid = new MyPHPGrid('frmPage');
          $grid->TableName = 'in_inventoryhead';
          $grid->formName = 'purchaseindentlist.php';
          $grid->inpage = $_REQUEST['frmPage_startrow'];
          $grid->SyncSession($grid);
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
function print(){
  var url = 'docreport2.php?rid=2020&id='+document.getElementById('saveid').value;
  window.open(url,'_blank','location=yes,height=570,width=520,scrollbars=yes,status=yes');
}
function printso(parentdocid){
  var url = 'docreport_ot.php?rid=1000&showprice=Yes&id='+parentdocid+'&txt_A_formtype=CRM';
  window.open(url,'location=yes,height=570,width=520,scrollbars=yes,status=yes');
}
function printso1(parentdocid){
  var url = 'docreport_co.php?rid=1000&showprice=Yes&id='+parentdocid+'&txt_A_formtype=CRM';
  window.open(url,'location=yes,height=570,width=520,scrollbars=yes,status=yes');
}
function getSalesValueforSubcontract(catval){ 
xmlHttp=GetXmlHttpObject()
	if (xmlHttp==null)
      {
                 alert ("Browser does not support HTTP Request")
                 return
      }
    var purchase_type = document.getElementById('cmb_A_purchasetype').value; 
    var purchase_catefory = document.getElementById('cmb_A_purchasecategory').value;
    if(document.getElementById('txt_A_subcontractvalue'))
    document.getElementById('txt_A_subcontractvalue').value = ""; 
    var jobno=document.getElementById('cmb_A_jobno').value;
    if(purchase_type=="Subcontractor Purchase" && purchase_catefory=="SPECIALIZED SERVICES"	){
    	document.getElementById('txt_A_subcontractvalue').value = "";
    	url="combofunctions_pro2.php?level=SalesValueforSubcontract&categorytype="+catval+"&purchasetype="+purchase_type+"&jobno="+jobno;
		xmlHttp.onreadystatechange=stateChangedcombo_SalesValueforSubcontract
		xmlHttp.open("POST",url,true)
	    xmlHttp.send(null)	
		
	}
}
function stateChangedcombo_SalesValueforSubcontract(){
       if (xmlHttp.readyState==4 || xmlHttp.readyState=="complete"){

            var s1 = trim(xmlHttp.responseText);
             document.getElementById('txt_A_subcontractvalue').value = s1;
       }
}
function getPurchaseSubcategory(catval) {
xmlHttp=GetXmlHttpObject()
      if (xmlHttp==null)
      {
                 alert ("Browser does not support HTTP Request")
                 return
      }
    var purchase_type = document.getElementById('cmb_A_purchasetype').value;
    if(document.getElementById('txt_A_purchasecategorytext')) 
    document.getElementById('txt_A_purchasecategorytext').value='';
    if(document.getElementById('cmb_A_purchasesubcategory'))
    document.getElementById('cmb_A_purchasesubcategory').value=''; 
    
    if(catval == "GENERAL" || catval == "Others"){
		document.getElementById('div_psc').style.display="none";
		document.getElementById('div_psc2').style.display="block";		
	}
	else if(catval == "HARD SERVICES" && purchase_type=="Subcontractor Purchase"){
		if(document.getElementById('txt_A_subcontractvalue'))
        document.getElementById('txt_A_subcontractvalue').value = "";
		document.getElementById('div_psc').style.display="none";
		document.getElementById('div_psc2').style.display="block";		
	}
	else{
		document.getElementById('div_psc').style.display="block";
		document.getElementById('div_psc2').style.display="none";			
    var url="combofunctions_pro2.php?level=PurchaseSubcategory&categorytype="+catval+"&purchasetype="+purchase_type;
	xmlHttp.onreadystatechange=stateChangedcombo_PurchaseSubcategory
	xmlHttp.open("POST",url,true)
    xmlHttp.send(null)	
	}
	
}
function stateChangedcombo_PurchaseSubcategory(){
       if (xmlHttp.readyState==4 || xmlHttp.readyState=="complete"){

            var s1 = trim(xmlHttp.responseText);
             document.getElementById('cmb_A_purchasesubcategory').innerHTML = s1;
       }
}

function getBuildings(catval) {
xmlHttp=GetXmlHttpObject()
      if (xmlHttp==null)
      {
                 alert ("Browser does not support HTTP Request")
                 return
      }
    
    var projectcode=document.getElementById('txt_A_projectcode').value;
    url="combofunctions_pro2.php?level=getProjectBuilding&categorytype="+catval+"&projectcode="+projectcode;
	xmlHttp.onreadystatechange=stateChangedcombo_Projectbuilding
	xmlHttp.open("POST",url,true)
    xmlHttp.send(null)	
	
}
function stateChangedcombo_Projectbuilding(){
       if (xmlHttp.readyState==4 || xmlHttp.readyState=="complete"){

            var s1 = trim(xmlHttp.responseText);
             document.getElementById('buildingcode').innerHTML = s1;
       }
}
function getBuildingIncharge(catval){ 
	  xmlHttp=GetXmlHttpObject()
      if (xmlHttp==null)
      {
                 alert ("Browser does not support HTTP Request")
                 return
      }
     // document.getElementById('txt_A_floordetails').value = '';
      document.getElementById('txt_A_buildingcode').value = '';
      document.getElementById('txt_A_buildingname').value = '';
      var jobno = document.getElementById('cmb_A_jobno').value ;
      var buildingtype = document.getElementById('cmb_A_propertytype').value;
      var url="combofunctions_pro2.php?level=buildingincharge&buildingcode="+catval+"&buildingtype="+buildingtype+"&jobno="+jobno;
      xmlHttp.onreadystatechange=stateChangedfloordetails
      xmlHttp.open("POST",url,true)
      xmlHttp.send(null)
	
}
function stateChangedfloordetails(){
       if (xmlHttp.readyState==4 || xmlHttp.readyState=="complete")
       {
             var s1 = trim(xmlHttp.responseText);
             var word = s1.split('@@@'); // alert(s1);
             document.getElementById('txt_A_buildingcode').value = word[0];
             document.getElementById('txt_A_buildingname').value=word[1];
             document.getElementById('cmb_A_siteincharge').innerHTML=word[2];
             document.getElementById('txt_A_siteinchargecontactno').value=word[3];
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
                        if (iKeyCode == 45) {
                alertify.error('Minus value not allowed');
                return false;
             }
            if((iKeyCode<=1 && iKeyCode>=7) || (iKeyCode>=9 && iKeyCode<45) || (iKeyCode>=58 && iKeyCode<=255)){
                if (iKeyCode!=13) {
                    alertify.error('Numbers Only');
                     return false;
                }
            }
            return true;

}
function getProjectDetails(jobno){
	xmlHttp=GetXmlHttpObject()
      if (xmlHttp==null)
      {
                 alert ("Browser does not support HTTP Request")
                 return
      }
    
    var jobtype=document.getElementById('txt_A_jobtype').value;
    var url;
    if(jobtype == "AMC") {
		url="combofunctions_pro2.php?level=ProjectDetailsofcontract&categorytype="+jobno+"&jobtype="+document.getElementById('txt_A_jobtype').value;
		xmlHttp.onreadystatechange=stateChangedcombo_ProjectDetailsofcontract
	}
    else {
    	url="combofunctions_pro2.php?level=ProjectDetailsofjob&categorytype="+jobno+"&jobtype="+document.getElementById('txt_A_jobtype').value;
    	xmlHttp.onreadystatechange=stateChangedcombo_ProjectDetailsofjob
	}
    
    xmlHttp.open("POST",url,true)
    xmlHttp.send(null)
}
function stateChangedcombo_ProjectDetailsofcontract(){
       if (xmlHttp.readyState==4 || xmlHttp.readyState=="complete"){
			var purchasetype = document.getElementById('cmb_A_purchasetype').value;
            var s1 = trim(xmlHttp.responseText); 
            var word = s1.split('@@@'); 
            document.getElementById('txt_A_projectname').value = word[0];
			document.getElementById('txt_A_quoteno').value = word[1];
			document.getElementById('txt_A_quoteversion').value = word[2];
			if(word[2]=='0'){
			   document.getElementById('quotation').value = word[1];
			}
			else  document.getElementById('quotation').value = word[1]+'-V'+word[2];
			//if(purchasetype!="Subcontractor Purchase")
			document.getElementById('txt_A_jobordervalue').value = word[3];
						
	        document.getElementById('cmb_A_propertytype').innerHTML = word[4];
	        document.getElementById('cmb_A_siteincharge').value = word[5];
	        document.getElementById('txt_A_siteinchargecontactno').value = word[6];
	        document.getElementById('txd_A_expstartdate').value = word[7];
	        document.getElementById('txd_A_expenddate').value = word[8];
	        document.getElementById('txt_A_durationtype').value = word[9];
	        document.getElementById('txt_A_duration').value = word[10];
	        document.getElementById('txt_A_projectcode').value = word[11];
	        document.getElementById('txt_A_extendeddays').value = word[12];
	        document.getElementById('txt_A_company').value = word[13];
			document.getElementById('txt_A_division').value = word[14];
			document.getElementById('txt_A_hardthreshold').value = word[15];
			document.getElementById('txt_A_softthreshold').value = word[16];
			document.getElementById('txt_A_generalthreshold').value = word[17];
	        if(word[12]=="" || word[12]==null){
				document.getElementById('dis_extendeddays').style.display = 'none';
			}
			else{
				document.getElementById('dis_extendeddays').style.display = 'block';
			}
			
       }
}

function stateChangedcombo_ProjectDetailsofjob(){
       if (xmlHttp.readyState==4 || xmlHttp.readyState=="complete"){

            var s1 = trim(xmlHttp.responseText); 
            var word = s1.split('@@@');
            document.getElementById('txt_A_projectname').value = word[0];
			document.getElementById('txt_A_propertytype').value = word[1];
			document.getElementById('txt_A_propertyno').value = word[2];
			document.getElementById('txt_A_location').value = word[3];
			document.getElementById('txt_A_quoteno').value = word[4];
			document.getElementById('txt_A_quoteversion').value = word[5];
			if(word[5]=='0'){
			   document.getElementById('quotation').value = word[4];
			}
			else { 
			document.getElementById('quotation').value = word[4]+'-V'+word[5];
				}
			document.getElementById('cmb_A_siteincharge').innerHTML = word[6];
			/*var elmnt = document.getElementById('cmb_A_siteincharge');alert(word[6]);
			for(var i=0; i < elmnt.options.length; i++)
			  {
			    if(elmnt.options[i].value === word[6]) { alert(elmnt.options[i].value);
			      elmnt.selectedIndex = i;
			      break;
			    }
			  }*/

			document.getElementById('txt_A_jobordervalue').value = word[7];
			document.getElementById('txt_A_siteinchargecontactno').value = word[8];
			document.getElementById('txt_A_projectcode').value = word[9];
			document.getElementById('txt_A_buildingcode').value = word[10];
			document.getElementById('txt_A_buildingname').value = word[11];
			document.getElementById('txt_A_company').value = word[12];
			document.getElementById('txt_A_division').value = word[13];
			
       }
}
function getStorejobs(cattype){

      xmlHttp=GetXmlHttpObject()
      if (xmlHttp==null)
      {
                 alert ("Browser does not support HTTP Request")
                 return
      }

      var url="combofunctions_pro.php?level=CCDetails&categorytype="+cattype;
      xmlHttp.onreadystatechange=stateChangedcombo
      xmlHttp.open("POST",url,true)
      xmlHttp.send(null)

}

function stateChangedcombo(){
       if (xmlHttp.readyState==4 || xmlHttp.readyState=="complete"){

             var s1 = trim(xmlHttp.responseText);
             document.getElementById('cmb_A_jobno').innerHTML=s1;

       }
}
function approveit(){
      var checkpo=document.getElementById('checkpo_XX');
       if(checkpo){
          if ((checkpo.value==null)||(checkpo.value=="")){
               alertify.alert("Save/Assign Task to PO", function () {
               checkpo.focus();

          });
             return;
          }
       }
      alertify.confirm("Are you sure you want to Approve For PO?", function (e) {
         if (e) {
           document.getElementById('frmEdit').action='editpurchaseindentlist.php?approveit=approveit&dr=edit&ID='+document.getElementById('saveid').value;
           document.getElementById('frmEdit').submit();
         } else {
            return;
         }

       });

}
function OMReject(){
var tr1=document.getElementById('tr1');
   tr1.style.display="table-row";
      var txt_A_nb=document.getElementById('txt_A_nb');
       if(txt_A_nb){
             if ((txt_A_nb.value==null)||(txt_A_nb.value=="")){
                  alertify.alert("Enter reason for reject", function () {
                  txt_A_nb.focus();

             });
                return;
             }
}
       alertify.confirm("Are you sure you want to Reject?", function (e) {
         if (e) {
           document.getElementById('frmEdit').action='editpurchaseindentlist.php?POST=OMREJECT&dr=edit&ID='+document.getElementById('saveid').value+'&reason='+txt_A_nb.value;
           document.getElementById('frmEdit').submit();
         } else {
            return;
         }

       });

}
function OMRevise(){
       alertify.confirm("Are you sure you want to Revise?", function (e) {
         if (e) {
           document.getElementById('frmEdit').action='editpurchaseindentlist.php?POST=OMREVISE&dr=edit&ID='+document.getElementById('saveid').value;
           document.getElementById('frmEdit').submit();
         } else {
            return;
         }

       });

}
function OMApprove(){

       alertify.confirm("Are you sure you want to Approve?", function (e) {
         if (e) {
           document.getElementById('frmEdit').action='editpurchaseindentlist.php?POST=OMAPPROVE&dr=edit&ID='+document.getElementById('saveid').value;
           document.getElementById('frmEdit').submit();
         } else {
            return;
         }

       });

}
function SCApprove(){

       alertify.confirm("Are you sure you want to Send for Manager Approval?", function (e) {
         if (e) {
           document.getElementById('frmEdit').action='editpurchaseindentlist.php?POST=SCAPPROVE&dr=edit&ID='+document.getElementById('saveid').value;
           document.getElementById('frmEdit').submit();
         } else {
            return;
         }

       });

}
function SCReject(){
var tr1=document.getElementById('tr1');
   tr1.style.display="table-row";
      var txt_A_nb=document.getElementById('txt_A_nb');
       if(txt_A_nb){
             if ((txt_A_nb.value==null)||(txt_A_nb.value=="")){
                  alertify.alert("Enter reason for reject", function () {
                  txt_A_nb.focus();

             });
                return;
             }
}
       alertify.confirm("Are you sure you want to Reject?", function (e) {
         if (e) {
           document.getElementById('frmEdit').action='editpurchaseindentlist.php?POST=SCREJECT&reason='+txt_A_nb.value+'&dr=edit&ID='+document.getElementById('saveid').value;
           document.getElementById('frmEdit').submit();
         } else {
            return;
         }

       });

}
function SCRevise(){
       alertify.confirm("Are you sure you want to Revise?", function (e) {
         if (e) {
           document.getElementById('frmEdit').action='editpurchaseindentlist.php?POST=SCREVISE&dr=edit&ID='+document.getElementById('saveid').value;
           document.getElementById('frmEdit').submit();
         } else {
            return;
         }

       });

}
function Senforapproval(){
	var salevalue='';
	var txt_A_subcontractvalue=document.getElementById('txt_A_subcontractvalue');
       if(txt_A_subcontractvalue){
          if ((txt_A_subcontractvalue.value=="" || txt_A_subcontractvalue.value=="0" || txt_A_subcontractvalue.value=="0.00")){
               alertify.alert("Enter Sales Value", function () {
               txt_A_subcontractvalue.focus();

          });
             return;
          }
          salevalue = txt_A_subcontractvalue.value;
    }

       alertify.confirm("Are you sure you want to Send For PC Approval?", function (e) {
         if (e) {
           document.getElementById('frmEdit').action='editpurchaseindentlist.php?approve=SFA&dr=edit&ID='+document.getElementById('saveid').value+'&salevalue='+salevalue;
           document.getElementById('frmEdit').submit();
         } else {
            return;
         }

       });

}
function approve(){

       alertify.confirm("Are you sure you want to Send For Approval?", function (e) {
         if (e) {
           document.getElementById('frmEdit').action='editpurchaseindentlist.php?approve=approve&dr=edit&ID='+document.getElementById('saveid').value;
           document.getElementById('frmEdit').submit();
         } else {
            return;
         }

       });

}
function cancelit(){
      alertify.confirm("Are you sure you want to Reject PI?", function (e) {
         if (e) {
           document.getElementById('frmEdit').action='editpurchaseindentlist.php?approveit=cancelit&dr=edit&ID='+document.getElementById('saveid').value;
           document.getElementById('frmEdit').submit();
         } else {
            return;
         }

       });

}
function deleteattachment(obj){
   document.getElementById('frmEdit').action='editpurchaseindentlist.php?attdelete='+obj+'&dr=edit&ID='+document.getElementById('saveid').value;
   document.getElementById('frmEdit').submit();
}
function editingrecord(action){


var txt_A_jobtype=document.getElementById('txt_A_jobtype');

if(txt_A_jobtype.value=="NA") {   
    
    var txt_A_contactphone=document.getElementById('txt_A_contactphone');
       if(txt_A_contactphone){
          if ((txt_A_contactphone.value==null)||(txt_A_contactphone.value=="")){
               alertify.alert("Enter Contract No", function () {
               txt_A_contactphone.focus();

          });
             return;
          }
       }
	
	var txt_A_contactdetails=document.getElementById('txt_A_contactdetails');
       if(txt_A_contactdetails){
          if ((txt_A_contactdetails.value==null)||(txt_A_contactdetails.value=="")){
               alertify.alert("Enter Address", function () {
               txt_A_contactdetails.focus();

          });
             return;
          }
       }
}

else if(txt_A_jobtype.value=="AMC") {

       var cmb_A_jobno=document.getElementById('cmb_A_jobno');
       if(cmb_A_jobno){
          if ((cmb_A_jobno.value=="Select")||(cmb_A_jobno.value=="")){
               alertify.alert("Select Contract No", function () {
               cmb_A_jobno.focus();

          });
             return;
          }
       }

} // end of amc type
else{

       var cmb_A_jobno=document.getElementById('cmb_A_jobno');
       if(cmb_A_jobno){
          if ((cmb_A_jobno.value=="Select")||(cmb_A_jobno.value=="")){
               alertify.alert("Select Job No", function () {
               cmb_A_jobno.focus();

          });
             return;
          }
       }

}  // end of not amc jobtype
       


        var txt_A_projectname=document.getElementById('txt_A_projectname');
       if(txt_A_projectname){
          if ((txt_A_projectname.value==null)||(txt_A_projectname.value=="")){
               alertify.alert("Enter Project Name", function () {
               txt_A_projectname.focus();

          });
             return;
          }
       }

if(txt_A_jobtype.value=="AMC") {

      var cmb_A_propertytype=document.getElementById('cmb_A_propertytype');
       if(cmb_A_propertytype){
          if ((cmb_A_propertytype.value=="Select")||(cmb_A_propertytype.value=="")){
               alertify.alert("Select Property Type", function () {
               cmb_A_propertytype.focus();

          });
             return;
          }
       }
       
       var txt_A_buildingcode=document.getElementById('txt_A_buildingcode');
       if(txt_A_buildingcode){
          if ((txt_A_buildingcode.value=="Select")||(txt_A_buildingcode.value=="")){
               alertify.alert("Select Property Name", function () {
               txt_A_buildingcode.focus();

          });
             return;
          }
       }

       var cmb_A_propertyno=document.getElementById('cmb_A_propertyno');
       if(cmb_A_propertyno){
          if ((cmb_A_propertyno.value=="Select")||(cmb_A_propertyno.value=="")){
               alertify.alert("Select Building", function () {
               cmb_A_propertyno.focus();

          });
             return;
          }
       }

} // end of amc type

else if(txt_A_jobtype.value=="OT" || txt_A_jobtype.value=="EMG") {

       var txt_A_propertytype=document.getElementById('txt_A_propertytype');
       if(txt_A_propertytype){
          if ((txt_A_propertytype.value==null)||(txt_A_propertytype.value=="")){
               alertify.alert("Enter Property Type", function () {
               txt_A_propertytype.focus();

          });
             return;
          }
       }


}  // end of not amc jobtype
       

       var txt_A_location=document.getElementById('txt_A_location');
       if(txt_A_location){
          if ((txt_A_location.value==null)||(txt_A_location.value=="")){
               alertify.alert("Enter Location", function () {
               txt_A_location.focus();

          });
             return;
          }
       }
       var cmb_A_siteincharge=document.getElementById('cmb_A_siteincharge');
       if(cmb_A_siteincharge){
          if ((cmb_A_siteincharge.value==null)||(cmb_A_siteincharge.value=="")){
               alertify.alert("Enter Site In Charge", function () {
               cmb_A_siteincharge.focus();

          });
             return;
          }
       }

       var txt_A_siteinchargecontactno=document.getElementById('txt_A_siteinchargecontactno');
       if(txt_A_siteinchargecontactno){
          if ((txt_A_siteinchargecontactno.value==null)||(txt_A_siteinchargecontactno.value=="")){
               alertify.alert("Enter Contact no.", function () {
               txt_A_siteinchargecontactno.focus();

          });
             return;
          }
       }

       var cmb_A_purchasetype=document.getElementById('cmb_A_purchasetype');
       if(cmb_A_purchasetype){
          if ((cmb_A_purchasetype.value==null)||(cmb_A_purchasetype.value=="")){
               alertify.alert("Select Purchase type.", function () {
               cmb_A_purchasetype.focus();

          });
             return;
          }
       }
       
       var cmb_A_purchasecategory=document.getElementById('cmb_A_purchasecategory');
       if(cmb_A_purchasecategory){
          if ((cmb_A_purchasecategory.value==null)||(cmb_A_purchasecategory.value=="")){
               alertify.alert("Select Purchase Category.", function () {
               cmb_A_purchasecategory.focus();

          });
             return;
          }
       }

    if(cmb_A_purchasecategory){
    	   
       if(cmb_A_purchasecategory.value == "GENERAL") {
       	
       	var txt_A_purchasecategorytext=document.getElementById('txt_A_purchasecategorytext');
       if(txt_A_purchasecategorytext){
          if ((txt_A_purchasecategorytext.value==null)||(txt_A_purchasecategorytext.value=="")){
               alertify.alert("Enter Subcategory.", function () {
               txt_A_purchasecategorytext.focus();

          });
             return;
          }
       }
       }
       
       else if((cmb_A_purchasecategory.value == "HARD SERVICES" && cmb_A_purchasetype.value=="Subcontractor Purchase")) {
       	
       	var txt_A_purchasecategorytext=document.getElementById('txt_A_purchasecategorytext');
       if(txt_A_purchasecategorytext){
          if ((txt_A_purchasecategorytext.value==null)||(txt_A_purchasecategorytext.value=="")){
               alertify.alert("Enter Subcategory.", function () {
               txt_A_purchasecategorytext.focus();

          });
             return;
          }
       }
       }
       
       else{
       
       var cmb_A_purchasesubcategory=document.getElementById('cmb_A_purchasesubcategory');
       if(cmb_A_purchasesubcategory){
          if ((cmb_A_purchasesubcategory.value==null)||(cmb_A_purchasesubcategory.value=="")){
               alertify.alert("Select Purchase Subcategory.", function () {
               cmb_A_purchasesubcategory.focus();

          });
             return;
          }
       }
	}
	}

	if(txt_A_jobtype.value=="AMC") {
	
	var txt_A_jobordervalue=document.getElementById('txt_A_jobordervalue');
       if(txt_A_jobordervalue){
          if ((txt_A_jobordervalue.value=="")){
               alertify.alert("Enter Contract Value", function () {
               txt_A_jobordervalue.focus();

          });
             return;
          }
    }
    
	var txt_A_subcontractvalue=document.getElementById('txt_A_subcontractvalue');
       if(txt_A_subcontractvalue){
          if ((txt_A_subcontractvalue.value=="")){
               alertify.alert("Enter Sales Value", function () {
               txt_A_subcontractvalue.focus();

          });
             return;
          }
    }
    
	}
       
    if(txt_A_jobtype.value=="AMC") {
    	if(cmb_A_purchasetype.value!="Subcontractor Purchase"){
    		var txt_A_hardthreshold=document.getElementById('txt_A_hardthreshold');
    		if(txt_A_hardthreshold){
	    		if(cmb_A_purchasecategory.value == "HARD SERVICES" && (txt_A_hardthreshold.value=="0.00" || txt_A_hardthreshold.value=="")) {
					alertify.alert("You cannot request for Hard Services");
					cmb_A_purchasecategory.focus();
					return;
				}
			}
			
			var txt_A_softthreshold=document.getElementById('txt_A_softthreshold');
    		if(txt_A_softthreshold){
	    		if(cmb_A_purchasecategory.value == "SOFT SERVICES" && (txt_A_softthreshold.value=="0.00" || txt_A_softthreshold.value=="")) {
					alertify.alert("You cannot request for Soft Services");
					cmb_A_purchasecategory.focus();
					return;
				}
			}
			
			var txt_A_generalthreshold=document.getElementById('txt_A_generalthreshold');
    		if(txt_A_generalthreshold){
	    		if(cmb_A_purchasecategory.value == "GENERAL" && (txt_A_generalthreshold.value=="0.00" || txt_A_generalthreshold.value=="")) {
					alertify.alert("You cannot request for General Services");
					cmb_A_purchasecategory.focus();
					return;
				}
			}
			
		}
    	
	}
     var txt_A_serviceoffered=document.getElementById('txt_A_serviceoffered');
       if(txt_A_serviceoffered){
          if ((txt_A_serviceoffered.value==null)||(txt_A_serviceoffered.value=="")){
               alertify.alert("Enter Service Offered", function () {
               txt_A_serviceoffered.focus();

          });
             return;
          }
       }
	
      
       var cmb_A_purchasesendto=document.getElementById('cmb_A_purchasesendto');
       if(cmb_A_purchasesendto){
          if ((cmb_A_purchasesendto.value==null)||(cmb_A_purchasesendto.value=="")){
               alertify.alert("Enter Purchase Co.", function () {
               cmb_A_purchasesendto.focus();

          });
             return;
          }
       }
       
       document.getElementById('btnsuccess').disabled=true;
       if( document.getElementById('btnwarning'))
       document.getElementById('btnwarning').disabled=true;
       if( document.getElementById('btndanger'))
       document.getElementById('btndanger').disabled=true;
       
       if(document.getElementById('mode').value==null){
              document.getElementById('frmEdit').action='in_action_pro.php'+get(document.frmEdit)+'action='+action;
              document.getElementById('frmEdit').submit();

       }else{
              document.getElementById('frmEdit').action='in_action_pro.php'+get(document.frmEdit)+'action='+action;
              document.getElementById('frmEdit').submit();

       }
       return;
       //insertfunction(get(document.frmEdit),action)
}
                   var xmlHttp
                   function insertfunction(parameters,action)
                   {

                          xmlHttp=GetXmlHttpObject()
                          if (xmlHttp==null)
                          {
                                 alert ("Browser does not support HTTP Request")
                                 return
                          }


                          var url="in_action_inventory.php"+parameters
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
                                 alertify.alert("Record Saved");
                                 window.location.href='editpurchaseindentlist.php?dr=edit&ID='+document.getElementById('saveid').value;

                               }else if(s1.toString() == s3.toString()){
                                alertify.alert("Record Updated");
                                window.location.href='editpurchaseindentlist.php?dr=edit&ID='+document.getElementById('mode').value;

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
                                 alertify.alert("Record Saved");
                                 window.location.href='editpurchaseindentlist.php?dr=add&ID=0';

                               }else if(s1.toString() == s3.toString()){
                                alertify.alert("Record Updated");
                                window.location.href='editpurchaseindentlist.php?dr=add&ID=0';

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
                                window.location.href='purchaseindentlist.php?ID=0';
                               }else if(s1.toString() == s3.toString()){
                                alertify.alert("Record Updated");
                                window.location.href='purchaseindentlist.php';

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

                var strURL="combofunctions_pro.php?level=purchasedoc2&docname="+docname+"&ext="+ext;

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
function CreatePO() {

                          if( document.getElementById('btnsuccess')) document.getElementById('btnsuccess').disabled=true;
                          if( document.getElementById('btndanger')) document.getElementById('btndanger').disabled=true;

                          xmlHttp=GetXmlHttpObject()
                          if (xmlHttp==null)
                          {
                                 alert ("Browser does not support HTTP Request")
                                 return
                          }

                          var url="Convert_to_po.php?headid="+document.getElementById('mode').value;
                          xmlHttp.onreadystatechange=stateChanged_PO
                          xmlHttp.open("POST",url,true)
                          xmlHttp.send(null)
}
                   function stateChanged_PO()
                   {
                         if (xmlHttp.readyState==4 || xmlHttp.readyState=="complete")
                         {
                                var s1 = trim(xmlHttp.responseText);
                               // document.frmEdit.action='editpurchaseindentlist.php?frmPage_startrow='+document.getElementById('recordstartrow').value+'&frmPage_rowcount='+document.getElementById('recordperpage').value+'&txtsearch='+document.getElementById('searchvalue').value+'&dr=edit&ID='+document.getElementById('mode').value;
                                document.frmEdit.action='purchaseindentlist.php?'
                                document.frmEdit.submit();

                         }
                   }
</script>
</head>
 <body class="hold-transition sidebar-mini" oncontextmenu="return false;" >

         <section class="content-header">

                 <a class="pull-left" href="purchaseindentlist.php?frmPage_rowcount=<?echo $_SESSION['frmPage_rowcount']; ?>&txtsearch=<?echo $_SESSION['txtsearch']; ?>" data-toggle="tooltip" data-placement="right" title="Back to Purchase Order"><i class='fa fa-backward'></i></a>
                 <h2 class="title">&nbsp;&nbsp;<?echo $title; ?></h2>
         </section>

                <section class="content" id='content-content-id' style='padding-right:5px;padding-left:5px;margin-top:-10px;'>

                  <div class="nav-tabs-custom" id="nav-tabs-custom-id">
                        <ul class="nav nav-tabs">
                           <li class="active"><a href="#personal" onclick='javascript:loadpage(2);' data-toggle="tab"><i class="fa fa-file-text" aria-hidden="true"></i>&nbsp; PR Details</a></li>

                           <?if($_REQUEST['ID']!=0){
                           if(getunreadmsg($docno)>0){
                                  $unreadmsg="<span><font color='red'><b>".getunreadmsg($docno)."</b></font></span>&nbsp;";
                                  }else{
                                  $unreadmsg="";
                                  }
                           
                           
                                if($purchasetype!="") {
                                echo " <li><a href='#purchasetype' onclick='javascript:loadpage(3);' data-toggle='tab'><i class='fa fa-user' aria-hidden='true'></i> $purchasetype</a></li>";
                                }
                              if(stripos(json_encode($_SESSION['role']),'PURCHASE') == true || stripos(json_encode($_SESSION['role']),'OPERATIONS MANAGER') == true || stripos(json_encode($_SESSION['role']),'FINANCE MANAGER') == true || stripos(json_encode($_SESSION['role']),'SITE INCHARGE') == true ) {
                              echo  "<li><a href='#polists' onclick='javascript:loadpage(6);' data-toggle='tab'><i class='fa fa-file-text' aria-hidden='true'></i>&nbsp; Prev.PO Lists</a></li>";
                              }
                           }
                           ?>

<li><a href="#communication"   onclick='javascript:loadpage(4);' data-toggle="tab"><i class="fa fa-wechat" aria-hidden="true"></i>&nbsp; Notes <? echo $unreadmsg;?></a></li>
                           <li><a href="#documents"   onclick='javascript:loadpage(5);' data-toggle="tab"><i class="fa fa-folder-open" aria-hidden="true"></i>&nbsp; Documents</a></li>

                       </ul>

                        <div class="tab-content" id='tab-content-id'>
                          <div class="tab-pane active" id="personal">
                              <div class="box-body no-padding" id='box-body-id'>
                                   <div class='table-responsive no-padding' style='overflow:hidden;'>
<?
    $itemspresent = "";
    $supplierspresent = "";
    
    if($_REQUEST['ID']!=""){
        if(($purchasetype == "Supplier Purchase" && $sp_supplier1_name!="") || ($purchasetype == "Cash Purchase" && $cp_bill_docname!="") || ($purchasetype == "Subcontractor Purchase" && $sp_supplier1_name!="")){//$scp_supplier1_name
            $supplierspresent = "YES";
            $itemspresent="YES";
        }
    }
	
//(stripos(json_encode($_SESSION['role']),'SITE INCHARGE') == true || stripos(json_encode($_SESSION['role']),'HR') == true || stripos(json_encode($_SESSION['role']),'SALES COORDINATOR') == true || stripos(json_encode($_SESSION['role']),'FRONT OFFICE CLERK') == true)
    if( ($requestedby == $_SESSION['SESSuserID']) && $posted!="YES") {
		$Approval_Person = "<tr>
		<td class='dvtCellLabel' style='border: 1px solid #ccc;'>Purchase Co. :</td>
		<td style='border: 1px solid #ccc;'> ".getPurchaseCordinator($purchasesendto)."
		</td></tr>";
	}
	if((stripos(json_encode($_SESSION['role']),'OPERATIONS MANAGER') == true || stripos(json_encode($_SESSION['role']),'PURCHASE COORDINATOR') == true || stripos(json_encode($_SESSION['role']),'PURCHASE MANAGER') == true || stripos(json_encode($_SESSION['role']),'FINANCE MANAGER') == true) && $posted=="YES") {
        $servicecoordinator_tr = "<tr>
                <td class='dvtCellLabel' style='border: 1px solid #ccc;'>Remarks :</td>
                <td style='border: 1px solid #ccc;' colspan=3>
                <input type='text'  class='form-control txt' name='txt_A_remarks' id='txt_A_remarks' value='$remarks' ></td>
                <td class='dvtCellLabel' style='border: 1px solid #ccc;'>Upload :&nbsp;</td>
		        <td style='border: 1px solid #ccc;'>
                <input type='hidden' name='MAX_FILE_SIZE'><input type='file' name='pidocname' id='pidocname' style='width:90%'>".getUpFileName($pidocname)."</td>
            </tr>";

	}

    if($jobtype == "OT" || $jobtype == "EMG") {
    	
    if($_REQUEST['ID']!="" && $_REQUEST['ID']!="0" && $parentdocid!="")	 { 
		    $buttonquote="<button class='btn btn-primary inputs' style='margin:-1px;margin-right:2px;float:right;' type='button' onclick ='javascript:printso($parentdocid);'><i class='glyphicon glyphicon-print' aria-hidden='true'></i></button>";	
	}	

    $jobval_lable = "Job Order Val";
    $TotalPurchaseMade = getTotalPurchaseMade($jobno,$jobtype,$purchasetype);
        
    if($sitesendforapproval=="PR Created") {
        $jobno_td = GetJob($jobno,$disable,$jobtype);
    }
	else{
		$jobno_td = "<input type='text' class='form-control txt' name='cmb_A_jobno' id='cmb_A_jobno' readonly value='".$jobno."' >";
	}
	
	$job_td = "<td class='dvtCellLabel' style='border: 1px solid #ccc;'>Job No :<span class='mandatory'>&nbsp;*</span></td>
			   <td style = 'border: 1px solid #ccc;'> ".$jobno_td."</td >";
	
	if(stripos(json_encode($_SESSION['role']),'SITE INCHARGE')!= true){
		$materialexpencess_tr = "<tr><td class='dvtCellLabel' style='border: 1px solid #ccc;'>
		<b>Material Expenses:</b></td>
		<td style = 'border: 1px solid #ccc;'><input type='text' readonly class='form-control txt' value='$materialexpencess' ></td ></tr>";
	}
	
	$HTML_table = "<tr>
				<td class='dvtCellLabel' style='border: 1px solid #ccc;'>PR Docno :</td>
				<td style='border: 1px solid #ccc;'>
	            <input type='text' readonly class='form-control txt' name='txt_A_docno' id='txt_A_docno' value='$docno' ></td>
	            <td class='dvtCellLabel' style='border: 1px solid #ccc;'>PR Date :</td>
	            <td style='border: 1px solid #ccc;'>
	            <div class='input-group'  >
	            ".MrDate($docdate,$date_lock)."
	            <span class='input-group-addon'><i class='fa fa-calendar bigger-110' ></i></span>
				</div>
				</td>
				$job_td
			</tr>
	<tr>
	   			<td class='dvtCellLabel' style='border: 1px solid #ccc;'>Project Name :<span class='mandatory'>&nbsp;*</span></td>
				<td style = 'border: 1px solid #ccc;'>
				<input type='text' readonly class='form-control txt' name='txt_A_projectname' id='txt_A_projectname' value='$projectname' >
				</td >
				<td class = 'dvtCellLabel' style = 'border: 1px solid #ccc;'>Property Type :<span class='mandatory'>&nbsp;*</span></td >
				<td style = 'border: 1px solid #ccc;'>
				<input type='text' readonly class='form-control txt' name='txt_A_propertytype' id='txt_A_propertytype' value='$propertytype' >
				</td >
				<td class = 'dvtCellLabel' style = 'border: 1px solid #ccc;'>Property No :</td >
				<td style = 'border: 1px solid #ccc;'>
				<input type='text' readonly class='form-control txt' name='txt_A_propertyno' id='txt_A_propertyno' value='$propertyno' >
				<input type='hidden' readonly class='form-control txt' name='txt_A_buildingname' id='txt_A_buildingname' value='$buildingname' >
			    <input type='hidden' readonly class='form-control txt' name='txt_A_buildingcode' id='txt_A_buildingcode' value='$buildingcode' >
				</td >
			</tr >
			<tr>
	   			<td class='dvtCellLabel' style='border: 1px solid #ccc;'>Location :<span class='mandatory'>&nbsp;*</span></td>
				<td style = 'border: 1px solid #ccc;'>
				<input type='text' readonly class='form-control txt' name='txt_A_location' id='txt_A_location' value='$location' >
				</td >
				<td class = 'dvtCellLabel' style = 'border: 1px solid #ccc;'>Site Incharge :<span class='mandatory'>&nbsp;*</span></td >
				<td style = 'border: 1px solid #ccc;'>
				".GetSiteIncharge($jobtype,$jobno,$buildingcode,$siteincharge,$post_to_pc_lock)."
				</td >
				<td class = 'dvtCellLabel' style = 'border: 1px solid #ccc;'>Contact No :<span class='mandatory'>&nbsp;* </span></td >
				<td style = 'border: 1px solid #ccc;'>
				<input type='text' class='form-control txt' name='txt_A_siteinchargecontactno' id='txt_A_siteinchargecontactno' value='$siteinchargecontactno' $post_to_pc_lock>
				</td >
			</tr >
			<tr> 
				<td class = 'dvtCellLabel' style = 'border: 1px solid #ccc;'>Quote :</td >
				<td style = 'border: 1px solid #ccc;'>
				<input type='hidden' readonly class='form-control txt' name='txt_A_quoteno' id='txt_A_quoteno' value='$quoteno' >
				<!--<button class='btn btn-primary inputs' style='margin:-1px;margin-right:2px;float:right;display:$displayquote;' type='button' onclick ='javascript:printso1($parentdocid);'><i class='glyphicon glyphicon-print' aria-hidden='true'></i></button> -->
                  $buttonquote
                 <input type='text' readonly class='form-control txt' name='quotation' id='quotation' value='$quotation'  style='width:150px;' >
                 <input type='hidden' readonly class='form-control txt' name='txt_A_quoteversion' id='txt_A_quoteversion' value='$quoteversion' >
				</td >
				<td class = 'dvtCellLabel' style = 'border: 1px solid #ccc;'><b>$jobval_lable:</b></td >
				<td style = 'border: 1px solid #ccc;'>
				<input type='text' readonly class='form-control txt' name='txt_A_jobordervalue' id='txt_A_jobordervalue' value='$jobordervalue' >
				</td >
				<td class = 'dvtCellLabel' style = 'border: 1px solid #ccc;'><b>Total Purchase Made</b>:</td >
				<td style = 'border: 1px solid #ccc;'>
				<input type='text' readonly class='form-control txt' value='$TotalPurchaseMade' >
				</td >
			</tr >
			$materialexpencess_tr
			<tr>
			 	<td class='dvtCellLabel' style='border: 1px solid #ccc;'>Purchase Type :<span class='mandatory'>&nbsp;*</span></td>
				<td style = 'border: 1px solid #ccc;'> ".GetPurchaseType($jobtype,$purchasetype,$post_to_pc_lock,$requestedby)."</td >
				<td class='dvtCellLabel' style='border: 1px solid #ccc;' nowrap>Purchase Category<span class='mandatory'>&nbsp;*</span></td>
				<td style = 'border: 1px solid #ccc;'> ".GetPurchaseCategory($jobtype,$purchasecategory,$post_to_pc_lock)."</td >
				<td class = 'dvtCellLabel' style = 'border: 1px solid #ccc;'>Requested By :</td >
				<td style = 'border: 1px solid #ccc;'>
				<input type='hidden' class='form-control txt' readonly name='txt_A_requestedby' id='txt_A_requestedby' value='".$requestedby."' />
				".GetUserName($requestedby)."</td >				
			</tr >";
			
    }

    if($jobtype == "AMC") {
    	$jobval_lable = "Contract Value ";
       	$TotalPurchaseMade = getTotalPurchaseMade($jobno,$jobtype,$purchasetype);
       	
       	if($sitesendforapproval=="PR Created") {
        $jobno_td = GetJob($jobno,$disable,$jobtype);
	    }
		else{
			$jobno_td = "<input type='text' class='form-control txt' name='cmb_A_jobno' id='cmb_A_jobno' readonly value='".$jobno."' >";
		}
		$job_td = "<td class='dvtCellLabel' style='border: 1px solid #ccc;'>Contract No :<span class='mandatory'>&nbsp;*</span></td>
			   <td style = 'border: 1px solid #ccc;'> ".$jobno_td."</td >";
		if($_REQUEST['ID']!="" && $_REQUEST['ID']!="0" && $parentdocid!="")	 { 
       		$buttonquote="<button class='btn btn-primary inputs' style='margin:-1px;margin-right:2px;float:right;' type='button' onclick ='javascript:printso1($parentdocid);'><i class='glyphicon glyphicon-print' aria-hidden='true'></i></button>";
		}
       
      if(stripos(json_encode($_SESSION['role']),'PURCHASE') == true|| stripos(json_encode($_SESSION['role']),'OPERATIONS MANAGER') == true || stripos(json_encode($_SESSION['role']),'FINANCE MANAGER') == true || stripos(json_encode($_SESSION['role']),'SALES COORDINATOR') == true ){
       //if($_REQUEST['ID'] == '0' || $serviceoffered!="") {
      if($purchasetype =="Subcontractor Purchase"){
       if($_REQUEST['ID']!="0" && $_REQUEST['ID']!="")	{
       $salevalue_td = "<td class='dvtCellLabel' style='border: 1px solid #ccc;'><b>Sale Value</b><span class='mandatory'>&nbsp;*</span></td>
				<td style = 'border: 1px solid #ccc;'>
				<input type='text' class='form-control txt' name='txt_A_subcontractvalue' id='txt_A_subcontractvalue' value='$subcontractvalue' $post_to_pc_lock onkeypress='return AllowNumeric1(event)'>
				</td >";
		}
			
       $serviceoffered_tr = "<tr>
       			$salevalue_td
	   			<td class='dvtCellLabel' style='border: 1px solid #ccc;'>Service Offered<span class='mandatory'>&nbsp;*</span></td>
				<td style = 'border: 1px solid #ccc;' colspan=3>
				<input type='text' class='form-control txt' name='txt_A_serviceoffered' id='txt_A_serviceoffered' value='$serviceoffered' $post_to_pc_lock>
				</td >
                </tr>";
       }
      
       $HTML_table_2 = "
           <tr>
	   			<td class='dvtCellLabel' style='border: 1px solid #ccc;'>Contract Start Date<span class='mandatory'>&nbsp;*</span></td>
				<td style = 'border: 1px solid #ccc;'>
				<input type='text' readonly class='form-control txt' name='txd_A_expstartdate' id='txd_A_expstartdate' value='$expstartdate' >
				</td >
				<td class = 'dvtCellLabel' style = 'border: 1px solid #ccc;'>Contract End Date<span class='mandatory'>&nbsp;*</span></td >
				<td style = 'border: 1px solid #ccc;'>
                <input type='text' readonly class='form-control txt' name='txd_A_expenddate' id='txd_A_expenddate'  value='$expenddate' >
				</td >
				<td class='dvtCellLabel' style='border: 1px solid #ccc;'>Contract Period  <span class='mandatory'>&nbsp;*</span></td>
                <td style='border: 1px solid #ccc;ccc;'>
                <span style='float:left;border: 0px solid #ccc;width:20%;'>
                <input type='text' readonly class='form-control txt' name='txt_A_duration' id='txt_A_duration'  value='$duration' >
                </span>
                <span style='float:left;border: 0px solid #ccc;width:75%;'>
	                <span style='float:left;border: 0px solid #ccc;width:40%;'>
	                <input type='text' readonly class='form-control txt' name='txt_A_durationtype' id='txt_A_durationtype'  value='$durationtype' ></span>
	                <div id='dis_extendeddays' style='float:right;border: 0px solid #ccc;width:55%;display:$dis_extendeddays;'>
	                   <span style='float:left;border: 0px solid #ccc;width:45%;'>
	                    <input type='text' readonly class='form-control txt' name='txt_A_extendeddays' id='txt_A_extendeddays'  value='$extendeddays' > </span>
	                 <span style='float:right;border: 0px solid #ccc;width:55%;'>+Days</span>
	                 </div>
                </span>
                
                </td>
			</tr >
            <tr> 
				<td class = 'dvtCellLabel' style = 'border: 1px solid #ccc;'>Quote :</td >
				<td style = 'border: 1px solid #ccc;'>
				<input type='hidden' readonly class='form-control txt' name='txt_A_quoteno' id='txt_A_quoteno' value='$quoteno' >
				<!--<button class='btn btn-primary inputs' style='margin:-1px;margin-right:2px;float:right;display:$displayquote;' type='button' onclick ='javascript:printso1($parentdocid);'><i class='glyphicon glyphicon-print' aria-hidden='true'></i></button> -->
                  $buttonquote
                 <input type='text' readonly class='form-control txt' name='quotation' id='quotation' value='$quotation'  style='width:150px;' >
                 <input type='hidden' readonly class='form-control txt' name='txt_A_quoteversion' id='txt_A_quoteversion' value='$quoteversion' >
				</td >
				<td class = 'dvtCellLabel' style = 'border: 1px solid #ccc;'>$jobval_lable:<span class='mandatory'>&nbsp;*</span></td >
				<td style = 'border: 1px solid #ccc;'>
				<input type='text' readonly class='form-control txt' name='txt_A_jobordervalue' id='txt_A_jobordervalue' value='$jobordervalue' >
				</td >
				<td class = 'dvtCellLabel' style = 'border: 1px solid #ccc;'><b>Total Purchase Made</b>:</td >
				<td style = 'border: 1px solid #ccc;'>
				<input type='text' readonly class='form-control txt' value='$TotalPurchaseMade' >
				</td >
			</tr >";
			
       }
       
       if(stripos(json_encode($_SESSION['role']),'SITE INCHARGE') == true ){
       $HTML_table_2 = "<input type='hidden' readonly class='form-control txt' name='txd_A_expstartdate' id='txd_A_expstartdate' value='$expstartdate' >
				     <input type='hidden' readonly class='form-control txt' name='txd_A_expenddate' id='txd_A_expenddate'  value='$expenddate' >
                     <input type='hidden' readonly class='form-control txt' name='txt_A_duration' id='txt_A_duration'  value='$duration' >
                     <input type='hidden' readonly class='form-control txt' name='txt_A_durationtype' id='txt_A_durationtype'  value='$durationtype' >
                     <input type='hidden' readonly class='form-control txt' name='txt_A_jobordervalue' id='txt_A_jobordervalue' value='$jobordervalue' >
                     <input type='hidden' readonly class='form-control txt' name='quotation' id='quotation' value='$quotation'  style='width:150px;' >
                      <input type='hidden' readonly class='form-control txt' name='txt_A_quoteversion' id='txt_A_quoteversion' value='$quoteversion' >
                      <input type='hidden' readonly class='form-control txt' name='txt_A_quoteno' id='txt_A_quoteno' value='$quoteno' >
                      <input type='hidden' readonly class='form-control txt' name='txt_A_extendeddays' id='txt_A_extendeddays' value='$extendeddays' >
                      <input type='hidden' readonly class='form-control txt' name='txt_A_hardthreshold' id='txt_A_hardthreshold' value='$hardthreshold' >
                      <input type='hidden' readonly class='form-control txt' name='txt_A_softthreshold' id='txt_A_softthreshold' value='$softthreshold' >
                      <input type='hidden' readonly class='form-control txt' name='txt_A_generalthreshold' id='txt_A_generalthreshold' value='$generalthreshold' >
                    
                </td>

			</tr >";
       }
        
        $HTML_table = "<tr>
				<td class='dvtCellLabel' style='border: 1px solid #ccc;'>PR Docno :</td>
				<td style='border: 1px solid #ccc;'>
	            <input type='text' readonly class='form-control txt' name='txt_A_docno' id='txt_A_docno' value='$docno' ></td>
	            <td class='dvtCellLabel' style='border: 1px solid #ccc;'>PR Date :</td>
	            <td style='border: 1px solid #ccc;'>
	            <div class='input-group'  >
	            ".MrDate($docdate,$date_lock)."
	            <span class='input-group-addon'><i class='fa fa-calendar bigger-110' ></i></span>
				</div>
				</td>
				$job_td
			</tr>";
		$HTML_table .= $HTML_table_2;	
        $HTML_table .= "<tr>
	   			<td class='dvtCellLabel' style='border: 1px solid #ccc;'>Project Name :<span class='mandatory'>&nbsp;*</span></td>
				<td style = 'border: 1px solid #ccc;'>
				<input type='text' readonly class='form-control txt' name='txt_A_projectname' id='txt_A_projectname' value='$projectname' >
				</td >
				<td class='dvtCellLabel' style= 'border: 1px solid #ccc;'>Property Type :<span class='mandatory'>&nbsp;*</span></td >
				<td style = 'border: 1px solid #ccc;'>".GetPropertyType($projectcode,$propertytype,$post_to_pc_lock)."
				</td >
				<td class='dvtCellLabel' style='border: 1px solid #ccc;'> Property Name <span class='mandatory'>&nbsp;*</span></td>
			    <td style='border: 1px solid #ccc;'>".GetBuildings($projectcode,$propertycode,$buildingcode,$post_to_pc_lock)."
			    <input type='hidden' name='txt_A_buildingname' id='txt_A_buildingname' value='$buildingname' >
			    <input type='hidden' name='txt_A_buildingcode' id='txt_A_buildingcode' value='$buildingcode' >
			    </td>
			</tr >
			
			<tr>
			    <td class = 'dvtCellLabel' style = 'border: 1px solid #ccc;'>Property No :<span class='mandatory'>&nbsp;*</span></td >
				<td style = 'border: 1px solid #ccc;'>
				<input type='text' readonly class='form-control txt' name='txt_A_propertyno' id='txt_A_propertyno' value='$propertyno' >
				</td >
	   			<td class='dvtCellLabel' style='border: 1px solid #ccc;'>Location :<span class='mandatory'>&nbsp;*</span></td>
				<td style = 'border: 1px solid #ccc;'>
				<input type='text' $post_to_pc_lock class='form-control txt' name='txt_A_location' id='txt_A_location' value='$location' >
				</td >

				<td class = 'dvtCellLabel' style = 'border: 1px solid #ccc;'>Site Incharge :<span class='mandatory'>&nbsp;*</span></td >
				<td style = 'border: 1px solid #ccc;'>
				".GetSiteIncharge($jobtype,$jobno,$buildingcode,$siteincharge,$post_to_pc_lock)."
				</td >
            </tr>
			<tr>
				<td class = 'dvtCellLabel' style = 'border: 1px solid #ccc;'>Contact No :<span class='mandatory'>&nbsp;* </span></td >
				<td style = 'border: 1px solid #ccc;'>
				<input type='text' class='form-control txt' name='txt_A_siteinchargecontactno' id='txt_A_siteinchargecontactno' value='$siteinchargecontactno' $post_to_pc_lock>
				</td >
				<td class='dvtCellLabel' style='border: 1px solid #ccc;'>Purchase Type :<span class='mandatory'>&nbsp;*</span></td>
				<td style = 'border: 1px solid #ccc;'> ".GetPurchaseType($jobtype,$purchasetype,$post_to_pc_lock,$requestedby)."</td >
				<td class = 'dvtCellLabel' style = 'border: 1px solid #ccc;'>Requested By :</td >
				<td style = 'border: 1px solid #ccc;'>
				<input type='hidden' class='form-control txt' readonly name='txt_A_requestedby' id='txt_A_requestedby' value='".$requestedby."' />
				".GetUserName($requestedby)."</td >
			</tr >
			<tr>
	   			<td class='dvtCellLabel' style='border: 1px solid #ccc;' nowrap>Purchase Category<span class='mandatory'>&nbsp;*</span></td>
				<td style = 'border: 1px solid #ccc;'>".GetAMCPurchaseCategory($jobtype,$purchasecategory,$post_to_pc_lock,$purchasetype)."</td >
				<td class = 'dvtCellLabel' style = 'border: 1px solid #ccc;'>Sub Category: <span class='mandatory'>&nbsp;*</span></td >
				<td style = 'border: 1px solid #ccc;'>
				<div id='div_psc' style='display:$div_psc_display;'>
				".GetAMCPurchaseSubCategory($purchasecategory,$purchasesubcategory,$post_to_pc_lock)."
				</div>
                <div id='div_psc2' style='display:$div_psc2_display;'>
				<input type='text' class='form-control txt' $post_to_pc_lock name='txt_A_purchasecategorytext' id='txt_A_purchasecategorytext' value='$purchasecategorytext' ></td >
                </div>	
			</tr > ".$serviceoffered_tr;
	
	if((stripos(json_encode($_SESSION['role']),'PURCHASE') == true || stripos(json_encode($_SESSION['role']),'OPERATIONS MANAGER') == true || stripos(json_encode($_SESSION['role']),'FINANCE MANAGER') == true)  && $purchasetype!='Subcontractor Purchase' && $posted=='YES'){
		$HTML_table .= "<tr>
	   			<td class='dvtCellLabel' style='border: 1px solid #ccc;'>HARD Threshold :<span class='mandatory'>&nbsp;*</span></td>
				<td style = 'border: 1px solid #ccc;'>
				<input type='text' readonly class='form-control txt' name='txt_A_hardthreshold' id='txt_A_hardthreshold' value='$hardthreshold' >
				</td >
				<td class='dvtCellLabel' style='border: 1px solid #ccc;'>SOFT Threshold :<span class='mandatory'>&nbsp;*</span></td>
				<td style = 'border: 1px solid #ccc;'>
				<input type='text' readonly class='form-control txt' name='txt_A_softthreshold' id='txt_A_softthreshold' value='$softthreshold' >
				</td >
				<td class='dvtCellLabel' style='border: 1px solid #ccc;'>GENERAL Threshold :<span class='mandatory'>&nbsp;*</span></td>
				<td style = 'border: 1px solid #ccc;'>
				<input type='text' readonly class='form-control txt' name='txt_A_generalthreshold' id='txt_A_generalthreshold' value='$generalthreshold' >
				</td >				
			</tr >
			<tr>
	   			<td class='dvtCellLabel' style='border: 1px solid #ccc;'>Total HARD Purchase made :<span class='mandatory'>&nbsp;*</span></td>
				<td style = 'border: 1px solid #ccc;'>
				<input type='text' readonly class='form-control txt' name='totalhardpurchase' id='totalhardpurchase' value='$totalhardpurchase' >
				</td >
				<td class='dvtCellLabel' style='border: 1px solid #ccc;'>Total SOFT Purchase made :<span class='mandatory'>&nbsp;*</span></td>
				<td style = 'border: 1px solid #ccc;'>
				<input type='text' readonly class='form-control txt' name='totalsoftpurchase' id='totalsoftpurchase' value='$totalsoftpurchase' >
				</td >
				<td class='dvtCellLabel' style='border: 1px solid #ccc;'>Total GENERAL Purchase made:<span class='mandatory'>&nbsp;*</span></td>
				<td style = 'border: 1px solid #ccc;'>
				<input type='text' readonly class='form-control txt' name='totalgeneralpurchase' id='totalgeneralpurchase' value='$totalgeneralpurchase' >
				</td >				
			</tr >
			<tr>
	   			<td class='dvtCellLabel' style='border: 1px solid #ccc;'><b>Eligible for HARD purchase</b> :<span class='mandatory'>&nbsp;*</span></td>
				<td style = 'border: 1px solid #ccc;'>
				<input type='text' readonly class='form-control txt' value='".($hardthreshold-$totalhardpurchase)."' >
				</td >
				<td class='dvtCellLabel' style='border: 1px solid #ccc;'><b>Eligible for SOFT purchase</b> :<span class='mandatory'>&nbsp;*</span></td>
				<td style = 'border: 1px solid #ccc;'>
				<input type='text' readonly class='form-control txt' value='".($softthreshold-$totalsoftpurchase)."' >
				</td >
				<td class='dvtCellLabel' style='border: 1px solid #ccc;'><b>Eligible for GENERAL purchase</b>:<span class='mandatory'>&nbsp;*</span></td>
				<td style = 'border: 1px solid #ccc;'>
				<input type='text' readonly class='form-control txt'  value='".($generalthreshold-$totalgeneralpurchase)."' >
				</td >				
			</tr >";
	}

	
    }  

	if($jobtype == "NA") {  
	    
	    $HTML_table = "<tr>
				<td class='dvtCellLabel' style='border: 1px solid #ccc;'>PR Docno :</td>
				<td style='border: 1px solid #ccc;'>
	            <input type='text' readonly class='form-control txt' name='txt_A_docno' id='txt_A_docno' value='$docno' ></td>
	            <td class='dvtCellLabel' style='border: 1px solid #ccc;'>PR Date :</td>
	            <td style='border: 1px solid #ccc;'>
	            <div class='input-group'  >
	            ".MrDate($docdate,$date_lock)."
	            <span class='input-group-addon'><i class='fa fa-calendar bigger-110' ></i></span>
				</div>
				</td>
				<td class='dvtCellLabel' style='border: 1px solid #ccc;'>Project Name :<span class='mandatory'>&nbsp;*</span></td>
				<td style = 'border: 1px solid #ccc;'>
				<input type='text' readonly class='form-control txt' name='txt_A_projectname' id='txt_A_projectname' value='UCWF' >
				<input type='hidden' readonly class='form-control txt' name='txt_A_jobtype' id='txt_A_jobtype' value='$jobtype' >
				<input type='hidden' readonly class='form-control txt' name='txt_A_jobno' id='txt_A_jobno' value='UCWF' >
				</td >
			</tr>
	    <tr>
	   			
				<td class = 'dvtCellLabel' style = 'border: 1px solid #ccc;'>Person Name :<span class='mandatory'>&nbsp;*</span></td >
				<td style = 'border: 1px solid #ccc;'>".GetUserName($empid)."
				<input type='hidden' class='form-control txt' readonly name='txt_A_siteincharge' id='txt_A_siteincharge' value='".$empid."' />
				<input type='hidden' class='form-control txt' readonly name='txt_A_requestedby' id='txt_A_requestedby' value='".$requestedby."' />
				</td >
				<td class='dvtCellLabel' style='border: 1px solid #ccc;'> Contact No <span class='mandatory'>&nbsp;*</span></td>
			    <td style='border: 1px solid #ccc;'>
			    <input type='text' $post_to_pc_lock class='form-control txt' name='txt_A_siteinchargecontactno' id='txt_A_siteinchargecontactno' onkeypress='return AllowNumeric1(event)' value='$siteinchargecontactno' >
			    </td>				
			</tr >
			<tr>
	   			<td class='dvtCellLabel' style='border: 1px solid #ccc;'>Address :<span class='mandatory'>&nbsp;*</span></td>
				<td style = 'border: 1px solid #ccc;' colspan=5>
				<input type='text' class='form-control txt' name='txt_A_location' id='txt_A_location' value='$location' autocomplete='false' $post_to_pc_lock>
				</td >
			</tr>
			<tr>									
			    <td class='dvtCellLabel' style='border: 1px solid #ccc;'>Purchase Type :<span class='mandatory'>&nbsp;*</span></td>
				<td style = 'border: 1px solid #ccc;'> ".GetPurchaseType($jobtype,$purchasetype,$post_to_pc_lock,$requestedby)."</td >
				<td class='dvtCellLabel' style='border: 1px solid #ccc;' nowrap>Purchase Category<span class='mandatory'>&nbsp;*</span></td>
				<td style = 'border: 1px solid #ccc;'> ".GetPurchaseCategory($jobtype,$purchasecategory,$post_to_pc_lock)."</td >
				<td style = 'border: 1px solid #ccc;' colspan=2>
				<div id='div_psc' style='display:$div_psc_display;'>
				&nbsp;
                </div>
                <div id='div_psc2' style='display:$div_psc2_display;'>
				<input type='text' class='form-control txt' $posted_lock name='txt_A_purchasecategorytext' id='txt_A_purchasecategorytext' value='$purchasecategorytext' placeholder='if any...' $post_to_pc_lock></td >
                </div>			
				
			</tr >$Approval_Person";
	}
	
	if($jobtype !="NA"){
		
			if($Approval_Person!=""){
				$HTML_table.= $Approval_Person;
			}
	}

    // form starts from here
    $entrydata .= "<form name='frmEdit' method='post' id='frmEdit' enctype='multipart/form-data' autocomplete='off'>
		<table class='table table-bordered table-condensed table-fixed  table-responsive' style='table-layout:fixed'>
			$HTML_table           
        	<tr id='tr1' name='tr1' style='display:$displayreason'>
                <td class='dvtCellLabel' style='border: 1px solid #ccc;'>Reason :<span class='mandatory'>&nbsp;*</span></td>
                <td style='border: 1px solid #ccc;' colspan=5>
                <input type='text' name='txt_A_nb'  class='form-control txt' id='txt_A_nb'  value='$nb'>
				</td>
            </tr>
            $servicecoordinator_tr
            
            <tr>
                                                             <td class='dvtCellLabel' style='border: 1px solid #ccc;'>Status :</td>
                                                            <td style='border: 1px solid #ccc;' colspan=1><b>$sitesendforapproval</b>
                                                             </td>
                                                            
                                                          </tr>



        <input type='hidden' readonly class='form-control txt' name='txt_A_sitesendforapproval' id='txt_A_sitesendforapproval' value='$sitesendforapproval' >
        <input type='hidden' name='txt_A_createdon'  id='txt_A_createdon' value='$createdon'>
        <input type='hidden' name='txt_A_jobtype'  id='txt_A_jobtype' value='$jobtype'>
        <input type='hidden' name='txt_A_projectcode'  id='txt_A_projectcode' value='$projectcode'>
        <input type='hidden' name='txt_A_created_date'  id='txt_A_created_date' value='$created_date'>
        <input type='hidden' readonly class='form-control txt' name='checkpo' id='checkpo' value='".$purchaseofficer."' >
        <input type='hidden' readonly class='form-control txt' name='txt_A_userid' id='txt_A_userid' value='".$checkuserid."' >
        <input type='hidden' readonly class='form-control txt' name='txt_A_doctype' id='txt_A_doctype' value='PUR-INDENT' >
        <input type='hidden' readonly class='form-control txt' name='txt_A_parentdoctype' id='txt_A_parentdoctype' value='$parentdoctype' >
        <input type='hidden' class='form-control txt' name='txt_A_company' id='txt_A_company' value='$company' >
        <input type='hidden' class='form-control txt' name='txt_A_division' id='txt_A_division' value='$division' >
        <input type='hidden' name='mode' class=textboxcombo id='mode' value='$mode'>
        <input type='hidden' name='modeid' class=textboxcombo id='modeid' value='save'>
        <input type='hidden' name='saveid' class=textboxcombo id='saveid' value='".$_REQUEST['ID']."'>
        <!--  <input type='hidden' name='txt_A_locationcode' class=textboxcombo id='txt_A_locationcode' value='".$_SESSION['SESSUserLocation']."'> -->
    ";

	$cancel = "";
    $approve= "";
	$Close_button ="<button class='btn btn-danger inputs ' style='margin-top:-5px;margin-left:4px;' name='btndanger' id='btndanger' type='button'  onclick ='javascript:cancleediting(\"purchaseindentlist.php\");'>Close &nbsp;<i class='fa fa-close' aria-hidden='true'></i></button>";
    $Save_button = "";

/*	if(($_REQUEST['ID']!="" && $itemspresent=='YES') ){   //|| ($sitesendforapproval=='SEND FOR APPROVAL' || $sitesendforapproval=='APPROVED')
        $Print_button ="<button class='btn btn-primary inputs' style='margin-top:-5px;margin-right:4px;float:right' name='btndanger' type='button' onclick ='javascript:print();'>Print &nbsp;<i class='glyphicon glyphicon-print' aria-hidden='true'></i></button>";
    } */
/*
    if(stripos(json_encode($_SESSION['role']),'SITE INCHARGE') !== false && $posted!='YES' && $post_to_pc!='YES'){
        $Save_button ="<button class='btn btn-success inputs' style='margin-top:-5px;' name='btnsuccess' type='button'  onclick ='javascript:editingrecord(\"save\");'>Save </font>&nbsp;<i class='fa fa-save' aria-hidden='true'></i></button>";
        if($itemspresent=="YES" && $supplierspresent=="YES")  // lineitems
		$approve ="<button class='btn btn-success inputs' style='margin-top:-5px;float:right' name='btnsuccess' type='button'  onclick ='javascript:Senforapproval();'>Send For SC Approval</font>&nbsp;<i class='fa fa-share' aria-hidden='true'></i></button>&nbsp;";
    }*/
    
    if($posted!='YES' && $post_to_pc!='YES' && $converted!='YES' && ($_REQUEST['ID']=='0' || $requestedby == $_SESSION['SESSuserID']) ){
        $Save_button ="<button class='btn btn-success inputs' style='margin-top:-5px;' id='btnsuccess' type='button'  onclick ='javascript:editingrecord(\"save\");'>Save </font>&nbsp;<i class='fa fa-save' aria-hidden='true'></i></button>";
        if($itemspresent=="YES" && $supplierspresent=="YES" && $converted!='YES')  // lineitems
		$approve ="<button class='btn btn-success inputs' style='margin-top:-5px;float:right' name='btnsuccess' type='button'  onclick ='javascript:Senforapproval();'>Send For PC Approval</font>&nbsp;<i class='fa fa-share' aria-hidden='true'></i></button>&nbsp;";
    }

    if(stripos(json_encode($_SESSION['role']),'PURCHASE COORDINATOR') == true && $posted=='YES' && $post_to_pc!='YES' && $post_to_pm != "YES" && $post_to_si != "YES" && $converted!='YES'){
        $Save_button ="<button class='btn btn-success inputs' style='margin-top:-5px;' id='btnsuccess' type='button'  onclick ='javascript:editingrecord(\"save\");'>Save </font>&nbsp;<i class='fa fa-save' aria-hidden='true'></i></button>";
        $approve ="<button class='btn btn-danger inputs' style='margin-top:-5px;margin-left:5px;float:right' name='btnsuccess' type='button'  onclick ='javascript:SCReject();'>Reject</font>&nbsp;<i class='fa fa-ban' aria-hidden='true'></i></button>&nbsp;";
        $approve .="<button class='btn btn-warning inputs' style='margin-top:-5px;margin-left:5px;float:right' name='btnsuccess' type='button'  onclick ='javascript:SCRevise();'>Revise</font>&nbsp;<i class='fa fa-history' aria-hidden='true'></i></button>&nbsp;";
        $approve .="<button class='btn btn-success inputs' style='margin-top:-5px;float:right' name='btnsuccess' type='button'  onclick ='javascript:SCApprove();'>Send For Manager Approval</font>&nbsp;<i class='fa fa-share' aria-hidden='true'></i></button>&nbsp;";
    }

    if((stripos(json_encode($_SESSION['role']),'OPERATIONS MANAGER') !== false || stripos(json_encode($_SESSION['role']),'PURCHASE MANAGER') !== false ) && $posted=='YES' && $post_to_pm=='YES' && $post_to_pc != "YES" && $post_to_si != "YES" && $converted!='YES'){
        $Save_button ="<button class='btn btn-success inputs' style='margin-top:-5px;' id='btnsuccess' type='button'  onclick ='javascript:editingrecord(\"save\");'>Save </font>&nbsp;<i class='fa fa-save' aria-hidden='true'></i></button>";
        $approve ="<button class='btn btn-danger inputs' style='margin-top:-5px;margin-left:5px;float:right' name='btnsuccess' type='button'  onclick ='javascript:OMReject();'>Reject</font>&nbsp;<i class='fa fa-ban' aria-hidden='true'></i></button>&nbsp;";
        $approve .="<button class='btn btn-warning inputs' style='margin-top:-5px;margin-left:5px;float:right' name='btnsuccess' type='button'  onclick ='javascript:OMRevise();'>Revise</font>&nbsp;<i class='fa fa-history' aria-hidden='true'></i></button>&nbsp;";
        $approve .="<button class='btn btn-success inputs' style='margin-top:-5px;float:right' name='btnsuccess' type='button'  onclick ='javascript:OMApprove();'>Approve</font>&nbsp;<i class='fa fa-share' aria-hidden='true'></i></button>&nbsp;";
    }
    // craeting po by PURCHSE COORDINATOR
    if(stripos(json_encode($_SESSION['role']),'PURCHASE COORDINATOR') !== false && $posted=='YES' && $post_to_pc=='YES' && $converted!='YES'){
        $approve ="<button class='btn btn-success inputs' style='margin-top:-5px;float:right' name='btnsuccess' id='btnsuccess' type='button'  onclick ='javascript:CreatePO();'>Create Purchase Order</font>&nbsp;<i class='fa fa-share' aria-hidden='true'></i></button>&nbsp;";
    }


                                        if($sitesendforapproval=='APPROVED' || $sitesendforapproval=='REJECTED') {
                                           $Save_button = "";
                                        }
						######################## Work Flow #############################
						 $checkworkflow = checkforWorkflow("INVENTORY","PURCHASE INDENT");
						 if($checkworkflow == "YES" && $converted!='YES'){
                            $Wf_arr = explode("@",GetWorkFlow("INVENTORY","PURCHASE INDENT"));
							$APPROVAL_users_arr = explode(",",$formsendto);// explode(",",$Wf_arr[1]);
							$APPROVAL_user1 = $APPROVAL_users_arr[0];
							$APPROVAL_user2 = $APPROVAL_users_arr[1];
							$APPROVALBY    =  $Wf_arr[3];
							$WF_APPROVALCOUNT  =  $Wf_arr[2];
							 //echo $sitesendforapproval;
							// ******************* FIELD APPROVAL  ***********************//
							/*		$WF_type = $Wf_arr[4];
									if($WF_type == "FIELD APPROVAL"){
                                       $get_PI_Qty = getPurchaseQty($_REQUEST['saveid']);
									   $Wf_arr_field = explode("@",Get_WF_Puchase("INVENTORY","PURCHASE INDENT",$store,$get_PI_Qty));
									    $APPROVAL_users_arr = explode(",",$formsendto);  //print_r($Wf_arr_field);
										$APPROVAL_user1 = $APPROVAL_users_arr[0];
										$APPROVAL_user2 = $APPROVAL_users_arr[1];
										$APPROVALBY    =  $Wf_arr_field[3];
										$WF_APPROVALCOUNT  =  $Wf_arr_field[2];
									}
									// ******************************************/
							if($APPROVALBY == "SELF" && $sitesendforapproval =="CREATED" && stripos(json_encode($_SESSION['role']),'SITE INCHARGE') == true){
									    $approve ="<button class='btn btn-success inputs' style='margin-top:-5px;float:right' name='btnsuccess' type='button'  onclick ='javascript:approveit();'>Approve </font>&nbsp;<i class='fa fa-share' aria-hidden='true'></i></button>&nbsp;";
                                        $cancel = "<button class='btn btn-warning inputs' style='margin-top:-5px;margin-right:4px;float:right' name='btnsuccess' type='button'  onclick ='javascript:cancelit();'>REJECT </font>&nbsp;<i class='fa fa-close' aria-hidden='true'></i></button>&nbsp;";
							}
							else{
									   if( $sitesendforapproval =="SEND FOR APPROVAL" && (stripos(json_encode($formsendto),$_SESSION['SESSuserID']) !== false)) {

										$approve ="<button class='btn btn-success inputs' style='margin-top:-5px;float:right' name='btnsuccess' type='button'  onclick ='javascript:approveit();'>Approve </font>&nbsp;<i class='fa fa-share' aria-hidden='true'></i></button>&nbsp;";
                                        $cancel = "<button class='btn btn-warning inputs' style='margin-top:-5px;margin-right:4px;float:right' name='btnsuccess' type='button'  onclick ='javascript:cancelit();'>REJECT </font>&nbsp;<i class='fa fa-close' aria-hidden='true'></i></button>&nbsp;";

                                        }


       	}
								if($approvedby != "") {

								$formsendto_arr = (explode(",",$formsendto));
								$approvedby_arr = (explode(",",$approvedby));
                                $RESULT_arr = array_intersect($approvedby_arr,$formsendto_arr);

                                        for($k=0;$k<count($RESULT_arr);$k++)
                                        {
                                        if(($_SESSION['SESSuserID'] == $RESULT_arr[$k])) {
                                           $approve = "";
                                           $cancel = "";
                                        }
                                        }
							    }

								if($WF_APPROVALCOUNT == $formapprovalcount && $sitesendforapproval =="SEND FOR APPROVAL" && stripos(json_encode($_SESSION['role']),'SITE INCHARGE') !== false){
									$approve = "";
                                    $cancel = "";
								}


						 }
						 else{  // No workflow
						    if($converted!='YES') {
							$approve ="<button class='btn btn-success inputs' style='margin-top:-5px;float:right' name='btnsuccess' type='button'  onclick ='javascript:approveit();'>Approve </font>&nbsp;<i class='fa fa-share' aria-hidden='true'></i></button>&nbsp;";
                            $cancel = "<button class='btn btn-warning inputs' style='margin-top:-5px;margin-right:4px;float:right' name='btnsuccess' type='button'  onclick ='javascript:cancelit();'>REJECT </font>&nbsp;<i class='fa fa-close' aria-hidden='true'></i></button>&nbsp;";
							}
							if($sitesendforapproval =="APPROVED"  || stripos(json_encode($_SESSION['role']),'SITE INCHARGE') != true)  {
							         $approve = "";
                                     $cancel = "";
				            }
						 }

                       //  $entrydata.="$Save_button $Close_button $approve $cancel</div>";

                     $entrydata .= "</table></form>";
                    echo $entrydata .= "   </div>
                                       </div>
                                        <div class='box-footer' style='border-top:1px #D2D2D2 solid;'>
                                        $Save_button $Close_button $approve $cancel $Print_button
                                        </div>";
                                        

?>

                           </div>

                             <div class="tab-pane" id="purchasetype">
                              <iframe id="frame3" name="frame3" scrolling="no" onload='this.width=screen.width;this.height=screen.height;'  frameborder="0" style="position: relative; width: 100%; "></iframe>
                             </div>
                             
                             <div class="tab-pane" id="communication">
                                      <iframe id="frame4" name="frame4" scrolling="no" onload='this.width=screen.width;this.height=screen.height;'  frameborder="0" style="position: relative; width: 100%; "></iframe>
                             </div>
                             <div class="tab-pane" id="documents">
                                      <iframe id="frame5" name="frame5" scrolling="no" onload='this.width=screen.width;this.height=screen.height;'  frameborder="0" style="position: relative; width: 100%; "></iframe>
                             </div>
                             <div class="tab-pane" id="polists">
                                      <iframe id="frame6" name="frame6" scrolling="no" onload='this.width=screen.width;this.height=screen.height;'  frameborder="0" style="position: relative; width: 100%; "></iframe>
                             </div>


                       </div>
                  </div>

        </section>
        
</body>
<div class='modal fade' id='myModal' tabindex='-1' role='dialog' aria-labelledby='myModalLabel'>
         <div class='modal-dialog' role='document' style="align:left;width:800px;">
            <div class='modal-content'>
                 <div class='modal-header' style='height:40px;'>
                  <button type='button' class='close' data-dismiss='modal' aria-label='Close'><span aria-hidden='true'>&times;</span></button>

                 <h3 style='margin-top:-5px;'>Attachement</h3>

                 </div>
                  <div class='modal-body' id='popupdiv' name='popupdiv'> </div>

            </div>
         </div>
</div>


</html>

<?php
function getTotalPurchaseMade($jobno,$jobtype,$purchasetype){
	if($jobtype=='AMC' && $purchasetype == 'Subcontractor Purchase') 
    $addsql_1 .= " and jobtype='AMC' and purchasetype = 'Subcontractor Purchase'";
    else if($jobtype=='AMC' && $purchasetype != 'Subcontractor Purchase') 
    $addsql_1 .= " and purchasetype <>'Subcontractor Purchase'";
	
	$SEL = "SELECT (SUM(totalgrossamt)-SUM(totalvatamt)) AS total FROM in_inventoryhead AS ch WHERE ch.jobno='$jobno' and (ch.sitesendforapproval='(PO)Released to Supplier & Waiting for Delivery Note' or ch.sitesendforapproval='(PO) Completed') $addsql_1";
	$RES = mysql_query($SEL);
    $ARR = mysql_fetch_array($RES);
    return $ARR['total'];
}
function GetUserName($UserId) {
         $SEL =  "select username from in_user where userid='$UserId' ";
         $RES = mysql_query($SEL);
         $ARR = mysql_fetch_array($RES);
         return $ARR['username'];
}
function GetLastSqeID($type){
                 $query = "LOCK TABLES in_sequencer_pro WRITE";
                 mysql_query($query) or die(mysql_error()."<br>".$query);
                 $seqSQL = "SELECT LASTNUMBER FROM in_sequencer_pro WHERE TABLENAME='$type'";
                 $result=mysql_query($seqSQL) or die(mysql_error()."<br>".$seqSQL);
                 $resulArr=mysql_fetch_array($result);
                 $updatedSeqID=$resulArr['LASTNUMBER']+1;
                 $query = "UNLOCK TABLES";
                 mysql_query($query) or die(mysql_error()."<br>".$query);
                 return ($updatedSeqID);
}

function GetStore($store) {

         $CMB = "<select name='cmb_A_store' id='cmb_A_store' $disabled class='form-control select2' onChange='getStorejobs(this.value)'>";
         $CMB .= "<option value=''></option>";
         $addsql="";
         $SQL =  "select name,address from in_store  order by id";  // where locationcode='".$_SESSION['SESSUserLocation']."'
         //echo $SEL;
         $RES = mysql_query($SQL);
         while ($ARR = mysql_fetch_array($RES)) {
                $SEL = "";
                if($store == $ARR['name']){ $SEL =  "SELECTED";}
                $CMB .= "<option value='".$ARR['name']."' $SEL >".$ARR['name']."</option>";
         }
         $CMB .= "</select>";
         return $CMB;
}

function GetstoreName() {
         $SEL =  "select name,address from in_store  order by id"; // where locationcode='".$_SESSION['SESSUserLocation']."'
         $RES = mysql_query($SEL);
         $ARR = mysql_fetch_array($RES);
         return $ARR['name'];
}

function MRDate($docdate,$date_lock){
 if($docdate=="")$docdate=$_SESSION['CURRDATE'];
 $tdate="<input type='text' class='form-control txt' data-provide='datepicker' maxlength=10  onkeypress='return AllowNumeric1(event)'
          name='txd_A_docdate' id='txd_A_docdate'  onkeyup='dateck(this)' onchange='checkdate(this);' value='$docdate' placeholder='dd-mm-yyyy' $date_lock>";
 return $tdate;
}

function DelvDate($deliverydate){
 if($deliverydate=="")$deliverydate=$_SESSION['CURRDATE'];
 $tdate="<input type='text' class='form-control txt' data-provide='datepicker' maxlength=10  onkeypress='return AllowNumeric1(event)'
          name='txd_A_deliverydate' id='txd_A_deliverydate'  onkeyup='dateck(this)' onchange='checkdate(this);' value='$deliverydate' placeholder='dd-mm-yyyy' >";
 return $tdate;
}

function GetPriority($priority) {
         $CMB = "<select name='cmb_A_priority' id='cmb_A_priority' class='form-control select'>";
         //$CMB .= "<option value=''></option>";
         $SEL =  "select lookcode,lookname from in_lookup where looktype='REQUISITION PRIORITY' and lookname<>'XX' order by lookname";
         $RES = mysql_query($SEL);
         while ($ARR = mysql_fetch_array($RES)) {
                $SEL = "";
                if(strtoupper($priority) == strtoupper($ARR['lookcode'])){ $SEL =  "SELECTED";}
                $CMB .= "<option value='".$ARR['lookcode']."' $SEL >".$ARR['lookname']."</option>";
         }
         $CMB .= "</select>";
         return $CMB;
}

function GetJob($jobno,$disable,$jobtype) {
		 if(stripos(json_encode($_SESSION['role']),'SITE INCHARGE') == true ) {
		 	$addSql = " and siteincharge like'%".$_SESSION['SESSuserID']."%' ";
		 }	 
         $SEL = "";
         $CMB = "<select name='cmb_A_jobno'  class='form-control select2' $disable id='cmb_A_jobno' onchange='javascript:getProjectDetails(this.value);'>";
         $CMB .= "<option value=''></option>";
         $SQL =  "select jobno,jobname from t_activitycenter where jobtype='$jobtype' and  ((activitycenter='CONTRACT' and status='Active') or
         (activitycenter='JOB' and (status like '%Waiting for job completion' ))or
         (activitycenter='JOB' and (status like '(EMG Ticket) Revised by Service Coordinator' ))or
         (activitycenter='JOB' and (status like '(OT Ticket) Revised by Service Coordinator' ))or
         (activitycenter='JOB' and (status like '%EMG Job Created and Waiting for Job Completion'))) $addSql order by id desc"; // and locationcode='".$_SESSION['SESSUserLocation']."'
         $RES = mysql_query($SQL);
         while ($ARR = mysql_fetch_array($RES)) {
                $SEL = "";
                if(strtoupper($jobno) == strtoupper($ARR['jobno'])){ $SEL =  "SELECTED";}
                $CMB .= "<option value='".$ARR['jobno']."' $SEL >".$ARR['jobno']."</option>";
         }
         $CMB .= "</select>";
         return $CMB;

}
function getPurchaseQty($docid){
         $SQL =  "select sum(quantity) as quantity from in_inventoryline where  initemid='$docid' group by initemid";
         $RES = mysql_query($SQL);
         $ARR = mysql_fetch_array($RES);
         return $ARR['quantity'];
}
function getPurchaseCordinator($userid){
        // if($userid=='') $userid="UFM027";
         $CMB = " <select name='cmb_A_purchasesendto'  id='cmb_A_purchasesendto' class='form-control select'>";    //getEnquiryThrough(this.value)
        // $CMB .= "<option value=''>Select</option>";
         $SEL =  "Select userid,username from in_user where rolecode like '%PURCHASE COORDINATOR%' and status='ACTIVE' "; //and acclocationcode='".$company."'
         $RES = mysql_query($SEL);
         while ($ARR = mysql_fetch_array($RES)) {
               $SEL = "";
               if($userid == $ARR['userid']){ $SEL =  "SELECTED";}
               $CMB .= "<option value='".trim($ARR['userid'])."' $SEL >".trim($ARR['userid'])." - ".trim($ARR['username'])."</option>";
         }
         $CMB .= "</select>";
         return $CMB;

}
function getOperationsManager($userid){
         $SEL =  "Select userid,username from in_user where rolecode like '%OPERATIONS MANAGER%' and status='ACTIVE' "; //and acclocationcode='".$company."'
         $RES = mysql_query($SEL);
         $ARR = mysql_fetch_array($RES);
         return $ARR['userid'];
}
function GetAMCPurchaseSubCategory($purchasecategory,$purchasubsecategory,$disabled){
	     if($_REQUEST['ID']!="0")
	     $onchange = "onchange='getSalesValueforSubcontract(this.value);'";
	     
		 $CMB = " <select name='cmb_A_purchasesubcategory'  id='cmb_A_purchasesubcategory' class='form-control select2' $disabled style='width:100%;' $onchange>";    //
         $CMB .= "<option value=''>Select</option>";
         if($purchasecategory == "SPECIALIZED SERVICES"){
		 	$SEL =  "select categorycode as lookcode,categoryname as lookname from in_asset where parentgroup='10154' order by id desc";
		 }
		 else
         $SEL =  "Select lookcode,lookname from in_lookup_head where looktype='$purchasecategory' and lookname<>'YY' order by slno";
         $RES = mysql_query($SEL);
         while ($ARR = mysql_fetch_array($RES)) {
               $SELECTED = "";
               if($purchasubsecategory == $ARR['lookcode']){ $SELECTED =  "SELECTED";}
               $CMB .= "<option value='".trim($ARR['lookcode'])."' $SELECTED >".trim($ARR['lookname'])."</option>";
         }
         $CMB .= "</select>";
         return $CMB;
}
function GetPurchaseCategory($jobtype,$purchasecategory,$disabled){
	     if($jobtype == "NA"){
	     	if(stripos(json_encode($_SESSION['role']),'HR MANAGER') == true) {
		 		$addsql = " and modulename like '%HR%'";
			}
			if(stripos(json_encode($_SESSION['role']),'FRONT OFFICE CLERK') == true) {
		 		$addsql = " and modulename like '%HELP DESK%'";
			}
		 }
		 /*else if($jobtype == "AMC"){
		 	if(stripos(json_encode($_SESSION['role']),'SITE INCHARGE') == true) {
		 		 $addsql = " and modulename like '%CRM%' and  lookcode!='SPECIALIZED SERVICES' and lookcode!='SECURITY SERVICES' ";
			}
		    else $addsql = " and modulename like '%CRM%'";
	     }
	     */else if($jobtype == "OT" || $jobtype =='EMG'){
		 	if(stripos(json_encode($_SESSION['role']),'SITE INCHARGE') == true) {
		 		 $addsql = " and modulename like '%CRM%' and lookcode!='SECURITY SERVICES' ";
			}
		    else $addsql = " and modulename like '%CRM%'";
	     }
	     else $addsql = " and modulename like '%CRM%'";
	     
		 $CMB = " <select name='cmb_A_purchasecategory'  id='cmb_A_purchasecategory' class='form-control select' $disabled onchange='javascript:getPurchaseSubcategory(this.value)'>";    //getEnquiryThrough(this.value)
         $CMB .= "<option value=''>Select</option>";
         $SEL =  "Select lookcode,lookname from in_lookup_head where looktype='PURCHASE CATEGORY' and lookname<>'YY' $addsql order by slno";
         $RES = mysql_query($SEL);
         while ($ARR = mysql_fetch_array($RES)) {
               $SELECTED = "";
               if($purchasecategory == $ARR['lookcode']){ $SELECTED =  "SELECTED";}
               $CMB .= "<option value='".trim($ARR['lookcode'])."' $SELECTED >".trim($ARR['lookname'])."</option>";
         }
         $CMB .= "</select>";
         return $CMB;
}
function GetAMCPurchaseCategory($jobtype,$purchasecategory,$disabled,$purchasetype){
	    
	     if($purchasetype == "Subcontractor Purchase" && stripos(json_encode($_SESSION['role']),'SALES COORDINATOR') == true)
	     $addsql = " and modulename like '%CRM%' and lookcode!='GENERAL'";
	     if(stripos(json_encode($_SESSION['role']),'SITE INCHARGE') == true) {
		 		 $addsql = " and modulename like '%CRM%' and lookcode!='SECURITY SERVICES' and lookcode!='SPECIALIZED SERVICES' ";
		 }
	     
		 $CMB = " <select name='cmb_A_purchasecategory'  id='cmb_A_purchasecategory' class='form-control select' $disabled onchange='javascript:getPurchaseSubcategory(this.value)'>";    //getEnquiryThrough(this.value)
         $CMB .= "<option value=''>Select</option>";
         $SEL =  "Select lookcode,lookname from in_lookup_head where looktype='PURCHASE CATEGORY' and lookname<>'YY' $addsql order by slno";
         $RES = mysql_query($SEL);
         while ($ARR = mysql_fetch_array($RES)) {
               $SELECTED = "";
               if($purchasecategory == $ARR['lookcode']){ $SELECTED =  "SELECTED";}
               $CMB .= "<option value='".trim($ARR['lookcode'])."' $SELECTED >".trim($ARR['lookname'])."</option>";
         }
         $CMB .= "</select>";
         return $CMB;
}
function GetPurchaseType($jobtype,$purchasetype,$disabled,$requestedby){
	    $CMB = " <select name='cmb_A_purchasetype'  id='cmb_A_purchasetype' class='form-control select' $disabled>"; 
	    
         if($jobtype=="AMC")
         {
             $addsql = "";
             if(stripos(json_encode($_SESSION['role']),'SITE INCHARGE') == true) {
             $addsql = " and lookcode<>'Subcontractor Purchase'";
             $CMB .= "<option value=''>Select</option>";
             }
             if(stripos(json_encode($_SESSION['role']),'SALES COORDINATOR') == true && ($_REQUEST["ID"]=="0" || $requestedby==$_SESSION['SESSuserID']) ) {
             $addsql = " and lookcode='Subcontractor Purchase'";
             }
         }
         if($jobtype=="NA"){
		 	$addsql = " and lookcode='Supplier Purchase'";
		 }
		 if($jobtype!="NA" && $jobtype!="AMC"){
         	$CMB .= "<option value=''>Select</option>";
		 }
         $SEL =  "Select lookcode,lookname from in_lookup_head where looktype='PURCHASE REQUEST TYPE' and lookname<>'YY' $addsql order by slno";
         $RES = mysql_query($SEL);
         while ($ARR = mysql_fetch_array($RES)) {
               $SELECTED = "";
               if($purchasetype == $ARR['lookcode']){ $SELECTED =  "SELECTED";}
               $CMB .= "<option value='".trim($ARR['lookcode'])."' $SELECTED >".trim($ARR['lookcode'])."(".trim($ARR['lookname']).")</option>";
         }
         $CMB .= "</select>";
         return $CMB;
}
function getUpFileName($invoiceupload){
                 if($invoiceupload!=""){
                            $str = explode("$$$",$invoiceupload);
                            $str = substr($invoiceupload,(strlen($str[0])+3));

                            $actdocname1= str_replace(" ","%20",$invoiceupload);
                            $ext = strtolower(pathinfo($invoiceupload, PATHINFO_EXTENSION));

                            $invoiceupload_dwl = $str."<a href='#' onclick='loadframe(\"".$ext."\",\"".$invoiceupload."\");' data-toggle='modal' data-target='#myModal'><i class='fa fa-eye' data-toggle='tooltip' data-placement='right' title='View' aria-hidden='true'></i></a>
                                      &nbsp;&nbsp;<a  href='download.php?folder=procurement&filename=".$invoiceupload."'><i class='fa fa-download' data-toggle='tooltip' data-placement='right' title='Download' aria-hidden='true'></i></a>
                                      &nbsp;&nbsp;";
                }else{
                            $invoiceupload_dwl = "";
                }
                return $invoiceupload_dwl;
}
function GetPropertyType($projectcode,$propertytype,$lock) {
	     $CMB = "<select name='cmb_A_propertytype'  id='cmb_A_propertytype' $lock class='form-control select' onchange='javascript:getBuildings(this.value)'>";
         $CMB .= "<option value=''></option>";
         $SEL =  " select distinct(tbl_projectbuilding.buildingtype) as propertycode,tbl_clientproperty.propertyname from tbl_projectbuilding left join tbl_clientproperty on tbl_clientproperty.propertycode=tbl_projectbuilding.buildingtype where tbl_projectbuilding.buildingstatus='Active' and projectcode='".$projectcode."' order by tbl_clientproperty.id";
         $RES = mysql_query($SEL);
          while ($ARR = mysql_fetch_array($RES)) {
          	 $SELECT = "";
                if(strtoupper($propertytype) == strtoupper($ARR['propertycode'])){ $SELECT =  "SELECTED";}
             $CMB .= "<option value='".$ARR['propertycode']."' $SELECT>".$ARR['propertycode']."</option>";
          }
         $CMB .= "</select>";
         return $CMB;	
	
}
function GetBuildings($projectcode,$propertycode,$buildingcode,$posted_lock) {
		 $CMB = " <select name='buildingcode' class='form-control select' id='buildingcode' $posted_lock  onChange='getBuildingIncharge(this.value);'>";
         $CMB .= "<option value=''></option>";
         $SQL =  "select distinct(tbl_projectbuilding.buildingid) as buildingid,tbl_building.buildingcode,tbl_building.buildingname from tbl_projectbuilding left join tbl_building on tbl_building.id=tbl_projectbuilding.buildingid 
where tbl_projectbuilding.buildingstatus='Active' and tbl_projectbuilding.posted='YES' and projectcode='".$projectcode."' and tbl_projectbuilding.buildingtype='".$propertycode."' order by tbl_building.id";
         $RES = mysql_query($SQL);
         while ($ARR = mysql_fetch_array($RES)) {
                $SELECT = "";
                if(strtoupper($buildingcode) == strtoupper($ARR['buildingcode'])){ $SELECT =  "SELECTED";}
                $CMB .= "<option value='".$ARR['buildingcode']."' $SELECT >".$ARR['buildingname']."</option>";
         }
         $CMB .= "</select>";
         return $CMB;	
}
/*function GetBuildings($jobno,$propertycode,$building,$posted_lock){
         $CMB = "<select name='cmb_A_propertyno' id='cmb_A_propertyno' $posted_lock class='form-control select' onChange='getFloorDetails(this.value)'>  ";
         $CMB .= "<option value=''></option>";
         $SELxx =  "select tbl_clientserviceproperty.buildingcode,tbl_clientbuilding.buildingname
         from tbl_clientserviceproperty left join  tbl_clientbuilding
         on tbl_clientserviceproperty.buildingcode=tbl_clientbuilding.buildingshortname
         where tbl_clientserviceproperty.propertycode='".$property."' and tbl_clientserviceproperty.buildingcode='".$building."' and
         tbl_clientserviceproperty.docid=(select in_crmhead.id from in_crmhead left join t_activitycenter on in_crmhead.docno=salesorderno
         where  t_activitycenter.jobno='".$jobno."')
         order by tbl_clientserviceproperty.id";
         $SEL = "select distinct(tbl_projectbuilding.buildingid) as buildingid,tbl_building.buildingcode,tbl_building.buildingname from tbl_projectbuilding left join tbl_building on tbl_building.id=tbl_projectbuilding.buildingid 
where tbl_projectbuilding.buildingstatus='Active' and tbl_projectbuilding.posted='YES' and projectcode='".$projectcode."' and tbl_projectbuilding.buildingtype='".$propertycode."' order by tbl_building.id";
         $RES = mysql_query($SEL);
         while ($ARR = mysql_fetch_array($RES)) {
                $SEL = "";
                if(strtoupper($building) == strtoupper($ARR['buildingcode'])){ $SEL =  "SELECTED";}
                $CMB .= "<option value='".$ARR['buildingcode']."' $SEL >".$ARR['buildingname']."</option>";
         }
         $CMB .= "</select>";
         return $CMB;
}
*/
function GetSiteIncharge($jobtype,$jobno,$buildingcode,$siteincharge,$post_to_pc_lock){
         $CMB = "<select name='cmb_A_siteincharge' id='cmb_A_siteincharge' class='form-control select2' $post_to_pc_lock>  ";
         $CMB .= "<option value=''></option>";
         if($jobtype=='AMC')
         $SQL=  "select in_incharges.inchargename as userid,in_user.username,in_incharges.mobile1 from in_incharges left join in_user on in_user.userid=in_incharges.inchargename left join tbl_building on tbl_building.id=in_incharges.docid where in_incharges.jobno='".$jobno."' and in_incharges.inchargetype='Site Incharge' and in_incharges.type='BUILDING' and tbl_building.buildingcode='".$buildingcode."' and in_incharges.inchargestatus='Active' and in_incharges.posted='YES'";  // where userid='$siteincharge'   where rolecode like '%SITE INCHARGE%'
         else{
		 $SQL="select in_incharges.inchargename as userid,in_user.username from in_incharges left join in_user on in_user.userid=in_incharges.inchargename where in_incharges.jobno='".$jobno."' and in_incharges.inchargetype='Site Incharge' and in_incharges.type='JOB' and in_incharges.inchargestatus='Active' and in_incharges.posted='YES'";		 
		 }
         $RES = mysql_query($SQL);
         while ($ARR = mysql_fetch_array($RES)) {
                $SEL = "";
                if(strtoupper($siteincharge) == strtoupper($ARR['userid'])){ $SEL =  "SELECTED";}
                $CMB .= "<option value='".$ARR['userid']."' $SEL >".$ARR['username']."</option>";
         }
         $CMB .= "</select>";
         return $CMB;	
	
}
function GetDurationType($durationtype,$lock) {
         $CMB = " <select name='durationtype' $lock id='durationtype' class='form-control select' onChange='getDurationNos(this.value)'>";
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
function getContactDetails($userid,$fieldname) {
		$SEL =  "select $fieldname from in_personalinfo where empid='$userid'";
        $RES = mysql_query($SEL);
        $ARR = mysql_fetch_array($RES);
        return $ARR[0]	;
	
}
function getunreadmsg($id){
      $myid=mysql_fetch_array(mysql_query("SELECT parentdocno,doctype FROM in_inventoryhead WHERE docno='$id'"));
      $parentdocno=$myid['parentdocno'];
      $doctype=$myid['doctype'];
      if($doctype=='PURCHASEORDER')
      {
      $myid=mysql_fetch_array(mysql_query("SELECT * FROM in_inventoryhead WHERE docno='$parentdocno'"));
      $tableid=$myid['id'];
      $SQL="Select count(*) as count from tbl_message,in_inventoryhead where tbl_message.ticketno ='$tableid' and
      tbl_message.viewedby not like '%".$_SESSION['SESSuserID']."%' and in_inventoryhead.docno='$id'  and tbl_message.formtype='PURCHASE'";
      }
      else
      {
      $SQL = "Select count(*) as count from tbl_message,in_inventoryhead where in_inventoryhead.id=tbl_message.ticketno and
      tbl_message.viewedby not like '%".$_SESSION['SESSuserID']."%' and in_inventoryhead.docno='$id'  and tbl_message.formtype='PURCHASE'";
      }

      $SQLRes =  mysql_query($SQL) or die(mysql_error()."<br>".$SQL);
      if(mysql_num_rows($SQLRes)>=1){
        while($loginResultArray   = mysql_fetch_array($SQLRes)){
            $count=$loginResultArray['count'];
        }
      }
      return $count;
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
       document.frmEdit.action='editpurchaseindentlist.php?dr=edit&ID='+document.getElementById('mode').value;
       document.frmEdit.submit();
   }
   if(i==3){
   var frame= document.getElementById('frame3');
   frame.src='purchasetype.php?ID=<?echo $_REQUEST['ID']; ?>';
   frame.load();
   }
   if(i==4){
   $("span").html("");
   var frame= document.getElementById('frame4');
   frame.src='communication.php?formtype=PURCHASE&ID=<?echo $_REQUEST['ID']; ?>';
   frame.load();
   } 
   if(i==5){
   var frame= document.getElementById('frame5');
   frame.src='purchasedocuments.php?txt_A_formtype=PURCHASE&cid=<?echo $_REQUEST['ID']; ?> ';
   frame.load();
   }
   if(i==6){
   var frame= document.getElementById('frame6');
   frame.src='purchaseorderdetails.php?INITEMID=<?echo $_REQUEST['ID']; ?>&jobno=<?echo $jobno; ?>';
   frame.load();
   }

}
</script>
