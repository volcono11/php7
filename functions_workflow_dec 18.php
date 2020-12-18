<?PHP
@session_start();
if($_SESSION['UserLanguage'] == "arabic") $wf_lable_align = "right";
else
$wf_lable_align = "left";
?>

<script type="text/javascript">
	
function wf_reviserecord(objectid,recordid,tablename,pageurl){
	
	   alertify.confirm("Do You Want To Revise The Record?", function (e) {
         if (e) {
             document.frmEdit.action='edit'+pageurl+'?dr=edit&ID='+recordid+'&action=REVISE'+'&wf_tablename='+tablename+'&objectid='+objectid;
             document.frmEdit.submit();
         } else {
            return ;
         }

       });
	
}

function wf_rejectrecord(objectid,recordid,tablename,pageurl){
	
	   alertify.confirm("Do You Want To Reject The Record?", function (e) {
         if (e) {
             document.frmEdit.action='edit'+pageurl+'?dr=edit&ID='+recordid+'&action=REJECT'+'&wf_tablename='+tablename+'&objectid='+objectid;
             document.frmEdit.submit();
         } else {
            return ;
         }

       });
	
}

function wf_cancelrecord(objectid,recordid,tablename,pageurl){
	
	   alertify.confirm("Do You Want To Cancel The Record?", function (e) {
         if (e) {
             document.frmEdit.action='edit'+pageurl+'?dr=edit&ID='+recordid+'&action=CANCEL'+'&wf_tablename='+tablename+'&objectid='+objectid;
             document.frmEdit.submit();
         } else {
            return ;
         }

       });
	
}

function wf_36001(objectid,recordid,tablename,pageurl){

	   alertify.confirm("Do You Want To Proceed?", function (e) {
         if (e) {
             document.frmEdit.action='edit'+pageurl+'?dr=edit&ID='+recordid+'&action=36001'+'&wf_tablename='+tablename+'&objectid='+objectid;
             document.frmEdit.submit();
         } else {
            return ;
         }
         });

	
}

function wf_36002(objectid,recordid,tablename,pageurl){

	   alertify.confirm("Do You Want To Proceed?", function (e) {
         if (e) {
             document.frmEdit.action='edit'+pageurl+'?dr=edit&ID='+recordid+'&action=36002'+'&wf_tablename='+tablename+'&objectid='+objectid;
             document.frmEdit.submit();
         } else {
            return ;
         }
         });

	
}

function wf_36003(objectid,recordid,tablename,pageurl){

	   alertify.confirm("Do You Want To Proceed?", function (e) {
         if (e) {
             document.frmEdit.action='edit'+pageurl+'?dr=edit&ID='+recordid+'&action=36003'+'&wf_tablename='+tablename+'&objectid='+objectid;
             document.frmEdit.submit();
         } else {
            return ;
         }
         });

	
}
</script>

<?php
date_default_timezone_set('Asia/Dubai');
include("connection.php");
//print_r($_REQUEST);
$WF_ACTION = isset($_REQUEST['action']) ? $_REQUEST['action'] : '';

if($WF_ACTION =="36001"){ // approve button 
    $wf_table = $_REQUEST['wf_tablename'];
    $sql_1 = "select tbl_workflowline.*,$wf_table.companycode from tbl_workflowline left join ".$_REQUEST['wf_tablename']." on ".$_REQUEST['wf_tablename'].".workflowseq = tbl_workflowline.sequencer right join tbl_workflow on tbl_workflow.id=tbl_workflowline.parentid where ".$_REQUEST['wf_tablename'].".id='".$_REQUEST['ID']."' and objectid=".$_REQUEST['objectid']." and tbl_workflow.companycode=$wf_table.companycode and tbl_workflow.profitcenter=$wf_table.divisioncode and tbl_workflow.module ='".$_SESSION['usermodulecode']."' ";
	$res_1 = mysqli_query($con,$sql_1);
	$wf_status = $wf_sequencer = $sendsms = $noalerts= $processend= $companycode ="";
	if($res_1->num_rows>0){
		$arr_1 = $res_1->fetch_array();
		$wf_status = $arr_1['status'];
		$sendsms = $arr_1['sms'];
		$inweb = $arr_1['inweb'];
		$firebase= $arr_1['firebase'];
		$email = $arr_1['email'];
		$noalerts = $arr_1['noalerts'];
		$wf_sequencer = $arr_1['sequencer'];
		$processend = $arr_1['processend'];
		$companycode = $arr_1['companycode'];
		
	}
	
	$wfusers = " ,wfusers= if(wfusers like '%".$_SESSION['SESSuserID']."%',wfusers,if(wfusers='','".$_SESSION['SESSuserID']."',CONCAT(wfusers,',".$_SESSION['SESSuserID']."')))";
	$wfusers = "";
	
	$alertsto = "";
	if($noalerts == "false")
	$alertsto=" ,alertsto= if(alertsto like '%".$_SESSION['SESSuserID']."%',alertsto,if(alertsto='','".$_SESSION['SESSuserID']."',CONCAT(alertsto,',".$_SESSION['SESSuserID']."')))";
	
    if($processend == "true") $workflowseq = "(workflowseq+100)"; // 100 : end of process - actulaflow ends
	else $workflowseq = "(workflowseq+1)";
	
	$up_sql = "update ".$_REQUEST['wf_tablename']." set wfstatus=(select status from tbl_workflowline right join tbl_workflow on tbl_workflow.id=tbl_workflowline.parentid
where action='$WF_ACTION' and objectid=".$_REQUEST['objectid']." and tbl_workflowline.sequencer=$wf_sequencer and tbl_workflow.companycode=$wf_table.companycode and tbl_workflow.profitcenter=$wf_table.divisioncode and tbl_workflow.module ='".$_SESSION['usermodulecode']."'), workflowseq=$workflowseq,lastupdatedat='".date('Y-m-d H:i:s')."',approvedby='".$_SESSION['SESSuserID']."' $alertsto $wfusers where id='".$_REQUEST['ID']."'";
	mysqli_query($con,$up_sql);
	
	SendGroupAlerts($_REQUEST['ID'],$wf_status,$_REQUEST['wf_tablename'],$sendsms,$inweb,$firebase);
	
}

if($WF_ACTION =="36001" || $WF_ACTION =="36002" || $WF_ACTION =="36003"){ // Forward
    $wf_table = $_REQUEST['wf_tablename'];
	$sql_1 = "select tbl_workflowline.*,$wf_table.companycode from tbl_workflowline left join ".$_REQUEST['wf_tablename']." on ".$_REQUEST['wf_tablename'].".workflowseq = tbl_workflowline.sequencer right join tbl_workflow on tbl_workflow.id=tbl_workflowline.parentid where ".$_REQUEST['wf_tablename'].".id='".$_REQUEST['ID']."' and objectid=".$_REQUEST['objectid']." and tbl_workflow.companycode=$wf_table.companycode and tbl_workflow.profitcenter=$wf_table.divisioncode and tbl_workflow.module ='".$_SESSION['usermodulecode']."' ";
	
	$res_1 = mysqli_query($con,$sql_1);
	$wf_status = $wf_sequencer = $sendsms = $noalerts= $processend= $companycode ="";
	if($res_1->num_rows>0){
		$arr_1 = $res_1->fetch_array();
		$wf_status = $arr_1['status'];
		$sendsms = $arr_1['sms'];
		$inweb = $arr_1['inweb'];
		$firebase= $arr_1['firebase'];
		$email = $arr_1['email'];
		$noalerts = $arr_1['noalerts'];
		$wf_sequencer = $arr_1['sequencer'];
		$processend = $arr_1['processend'];
		$companycode = $arr_1['companycode'];
		
	}
	
	if($processend == "true") $workflowseq = "(workflowseq+100)"; // 100 : end of process - actulaflow ends
	else $workflowseq = "(workflowseq+1)";
	
	$wfusers = " ,wfusers= if(wfusers like '%".$_SESSION['SESSuserID']."%',wfusers,if(wfusers='','".$_SESSION['SESSuserID']."',CONCAT(wfusers,',".$_SESSION['SESSuserID']."')))";
	$wfusers = GetWFNextUser();
	
	$alertsto = "";
	if($noalerts == "false")
	$alertsto=" ,alertsto= if(alertsto like '%".$_SESSION['SESSuserID']."%',alertsto,if(alertsto='','".$_SESSION['SESSuserID']."',CONCAT(alertsto,',".$_SESSION['SESSuserID']."')))";
	
	$up_sql = "update ".$_REQUEST['wf_tablename']." set wfstatus=(select status from tbl_workflowline right join tbl_workflow on tbl_workflow.id=tbl_workflowline.parentid
where action='$WF_ACTION' and objectid=".$_REQUEST['objectid']." and tbl_workflowline.sequencer=$wf_sequencer and tbl_workflow.companycode=$wf_table.companycode and tbl_workflow.profitcenter=$wf_table.divisioncode and tbl_workflow.module ='".$_SESSION['usermodulecode']."'), workflowseq=$workflowseq,lastupdatedat='".date('Y-m-d H:i:s')."' $alertsto $wfusers where id='".$_REQUEST['ID']."'";
	mysqli_query($con,$up_sql);
	
	SendGroupAlerts($_REQUEST['ID'],$wf_status,$_REQUEST['wf_tablename'],$sendsms,$inweb,$firebase);
	
}

if($WF_ACTION =="36003xxx"){ // Reference
    $wf_table = $_REQUEST['wf_tablename'];
	$sql_1 = "select tbl_workflowline.*,$wf_table.companycode from tbl_workflowline left join ".$_REQUEST['wf_tablename']." on ".$_REQUEST['wf_tablename'].".workflowseq = tbl_workflowline.sequencer right join tbl_workflow on tbl_workflow.id=tbl_workflowline.parentid where ".$_REQUEST['wf_tablename'].".id='".$_REQUEST['ID']."' and objectid=".$_REQUEST['objectid']." and tbl_workflow.companycode=$wf_table.companycode and tbl_workflow.profitcenter=$wf_table.divisioncode and tbl_workflow.module ='".$_SESSION['usermodulecode']."' ";
	
	$res_1 = mysqli_query($con,$sql_1);
	$wf_status = $wf_sequencer = $sendsms = $noalerts= $processend= $companycode ="";
	if($res_1->num_rows>0){
		$arr_1 = $res_1->fetch_array();
		$wf_status = $arr_1['status'];
		$sendsms = $arr_1['sms'];
		$inweb = $arr_1['inweb'];
		$firebase= $arr_1['firebase'];
		$email = $arr_1['email'];
		$noalerts = $arr_1['noalerts'];
		$wf_sequencer = $arr_1['sequencer'];
		$processend = $arr_1['processend'];
		$companycode = $arr_1['companycode'];
		
	}
	
	if($processend == "true") $workflowseq = "(workflowseq+100)"; // 100 : end of process - actulaflow ends
	else $workflowseq = "(workflowseq+1)";
	
	$wfusers = " ,wfusers= if(wfusers like '%".$_SESSION['SESSuserID']."%',wfusers,if(wfusers='','".$_SESSION['SESSuserID']."',CONCAT(wfusers,',".$_SESSION['SESSuserID']."')))";
	$wfusers = "";
	
	$alertsto = "";
	if($noalerts == "false")
	$alertsto=" ,alertsto= if(alertsto like '%".$_SESSION['SESSuserID']."%',alertsto,if(alertsto='','".$_SESSION['SESSuserID']."',CONCAT(alertsto,',".$_SESSION['SESSuserID']."')))";

	$up_sql = "update ".$_REQUEST['wf_tablename']." set wfstatus=(select status from tbl_workflowline right join tbl_workflow on tbl_workflow.id=tbl_workflowline.parentid
where action='$WF_ACTION' and objectid=".$_REQUEST['objectid']." and tbl_workflowline.sequencer=$wf_sequencer and tbl_workflow.companycode=$wf_table.companycode and tbl_workflow.profitcenter=$wf_table.divisioncode and tbl_workflow.module ='".$_SESSION['usermodulecode']."'), workflowseq=workflowseq+1,lastupdatedat='".date('Y-m-d H:i:s')."' $alertsto $wfusers  where id='".$_REQUEST['ID']."'";
	mysqli_query($con,$up_sql);
	
}

if($WF_ACTION =="REVISE"){
	$wf_table = $_REQUEST['wf_tablename'];
	$sql_1 = "select tbl_workflowline.*,$wf_table.companycode from tbl_workflowline left join ".$_REQUEST['wf_tablename']." on ".$_REQUEST['wf_tablename'].".workflowseq = tbl_workflowline.sequencer right join tbl_workflow on tbl_workflow.id=tbl_workflowline.parentid where ".$_REQUEST['wf_tablename'].".id='".$_REQUEST['ID']."' and objectid=".$_REQUEST['objectid']." and tbl_workflow.companycode=$wf_table.companycode and tbl_workflow.profitcenter=$wf_table.divisioncode and tbl_workflow.module ='".$_SESSION['usermodulecode']."' ";
	//$sql_1 = "select tbl_workflowline.* from tbl_workflowline left join ".$_REQUEST['wf_tablename']." on ".$_REQUEST['wf_tablename'].".workflowseq = tbl_workflowline.sequencer right join tbl_workflow on tbl_workflow.id=tbl_workflowline.parentid where ".$_REQUEST['wf_tablename'].".id='".$_REQUEST['ID']."' and objectid=".$_REQUEST['objectid']."";
	$res_1 = mysqli_query($con,$sql_1);
	$reject_status = $wf_sequencer = $sendsms = $noalerts= $processend= $companycode ="";
	if($res_1->num_rows>0){
		$arr_1 = $res_1->fetch_array();
		$revise_status = $arr_1['revisestatus'];
		$sendsms = $arr_1['sms'];
		$inweb = $arr_1['inweb'];
		$firebase= $arr_1['firebase'];
		$email = $arr_1['email'];
		$noalerts = $arr_1['noalerts'];
		$wf_sequencer = $arr_1['sequencer'];
		$processend = $arr_1['processend'];
		$companycode = $arr_1['companycode'];
		
	}
	
	$wfusers = " ,wfusers= if(wfusers like '%".$_SESSION['SESSuserID']."%',wfusers,if(wfusers='','".$_SESSION['SESSuserID']."',CONCAT(wfusers,',".$_SESSION['SESSuserID']."')))";
	$wfusers = "";
	
	$alertsto = "";
	if($noalerts == "false")
	$alertsto=" ,alertsto= if(alertsto like '%".$_SESSION['SESSuserID']."%',alertsto,if(alertsto='','".$_SESSION['SESSuserID']."',CONCAT(alertsto,',".$_SESSION['SESSuserID']."')))";
	
	$up_sql = "update ".$_REQUEST['wf_tablename']." set wfstatus='".$revise_status."', workflowseq=(workflowseq-1),lastupdatedat='".date('Y-m-d H:i:s')."' $alertsto $wfusers  where id='".$_REQUEST['ID']."'";
	mysqli_query($con,$up_sql);
	
	SendGroupAlerts($_REQUEST['ID'],$revise_status,$_REQUEST['wf_tablename'],$sendsms,$inweb,$firebase);
	
	
}

if($WF_ACTION =="REJECT"){
	$wf_table = $_REQUEST['wf_tablename'];
	$sql_1 = "select tbl_workflowline.*,$wf_table.companycode from tbl_workflowline left join ".$_REQUEST['wf_tablename']." on ".$_REQUEST['wf_tablename'].".workflowseq = tbl_workflowline.sequencer right join tbl_workflow on tbl_workflow.id=tbl_workflowline.parentid where ".$_REQUEST['wf_tablename'].".id='".$_REQUEST['ID']."' and objectid=".$_REQUEST['objectid']." and tbl_workflow.companycode=$wf_table.companycode and tbl_workflow.profitcenter=$wf_table.divisioncode and tbl_workflow.module ='".$_SESSION['usermodulecode']."' ";
	//$sql_1 = "select tbl_workflowline.* from tbl_workflowline left join ".$_REQUEST['wf_tablename']." on ".$_REQUEST['wf_tablename'].".workflowseq = tbl_workflowline.sequencer right join tbl_workflow on tbl_workflow.id=tbl_workflowline.parentid where ".$_REQUEST['wf_tablename'].".id='".$_REQUEST['ID']."' and objectid=".$_REQUEST['objectid']."";
	$res_1 = mysqli_query($con,$sql_1);
	$reject_status = $wf_sequencer = $sendsms = $noalerts= $processend= $companycode ="";
	if($res_1->num_rows>0){
		$arr_1 = $res_1->fetch_array();
		$reject_status = $arr_1['rejectstatus'];
		$sendsms = $arr_1['sms'];
		$inweb = $arr_1['inweb'];
		$firebase= $arr_1['firebase'];
		$email = $arr_1['email'];
		$noalerts = $arr_1['noalerts'];
		$wf_sequencer = $arr_1['sequencer'];
		$processend = $arr_1['processend'];
		$companycode = $arr_1['companycode'];
		
	}
	
	$wfusers = " ,wfusers= if(wfusers like '%".$_SESSION['SESSuserID']."%',wfusers,if(wfusers='','".$_SESSION['SESSuserID']."',CONCAT(wfusers,',".$_SESSION['SESSuserID']."')))";
	$wfusers = "";
	
	$alertsto = "";
	if($noalerts == "false")
	$alertsto=" ,alertsto= if(alertsto like '%".$_SESSION['SESSuserID']."%',alertsto,if(alertsto='','".$_SESSION['SESSuserID']."',CONCAT(alertsto,',".$_SESSION['SESSuserID']."')))";
	
	$up_sql = "update ".$_REQUEST['wf_tablename']." set wfstatus='".$reject_status."', workflowseq=(workflowseq+100),rejectedby='".$_SESSION['SESSuserID']."',lastupdatedat='".date('Y-m-d H:i:s')."' $alertsto $wfusers  where id='".$_REQUEST['ID']."'";
	mysqli_query($con,$up_sql);
	
	SendGroupAlerts($_REQUEST['ID'],$reject_status,$_REQUEST['wf_tablename'],$sendsms,$inweb,$firebase);
	
}

if($WF_ACTION =="CANCEL"){
	$wf_table = $_REQUEST['wf_tablename'];
	$sql_1 = "select tbl_workflowline.*,$wf_table.companycode from tbl_workflowline left join ".$_REQUEST['wf_tablename']." on ".$_REQUEST['wf_tablename'].".workflowseq = tbl_workflowline.sequencer right join tbl_workflow on tbl_workflow.id=tbl_workflowline.parentid where ".$_REQUEST['wf_tablename'].".id='".$_REQUEST['ID']."' and objectid=".$_REQUEST['objectid']." and tbl_workflow.companycode=$wf_table.companycode and tbl_workflow.profitcenter=$wf_table.divisioncode and tbl_workflow.module ='".$_SESSION['usermodulecode']."' ";
	
	//$sql_1 = "select tbl_workflowline.* from tbl_workflowline left join ".$_REQUEST['wf_tablename']." on ".$_REQUEST['wf_tablename'].".workflowseq = tbl_workflowline.sequencer right join tbl_workflow on tbl_workflow.id=tbl_workflowline.parentid where ".$_REQUEST['wf_tablename'].".id='".$_REQUEST['ID']."' and objectid=".$_REQUEST['objectid']."";
	$res_1 = mysqli_query($con,$sql_1);
	$cancel_status = $wf_sequencer = $sendsms = $noalerts= $processend= $companycode ="";
	if($res_1->num_rows>0){
		$arr_1 = $res_1->fetch_array();
		$cancel_status = $arr_1['cancelstatus'];
		$sendsms = $arr_1['sms'];
		$inweb = $arr_1['inweb'];
		$firebase= $arr_1['firebase'];
		$email = $arr_1['email'];
		$noalerts = $arr_1['noalerts'];
		$wf_sequencer = $arr_1['sequencer'];
		$processend = $arr_1['processend'];
		$companycode = $arr_1['companycode'];
		  
	}
	
	$wfusers = " ,wfusers= if(wfusers like '%".$_SESSION['SESSuserID']."%',wfusers,if(wfusers='','".$_SESSION['SESSuserID']."',CONCAT(wfusers,',".$_SESSION['SESSuserID']."')))";
	$wfusers = "";
	
	$alertsto = "";
	if($noalerts == "false")
	$alertsto=" ,alertsto= if(alertsto like '%".$_SESSION['SESSuserID']."%',alertsto,if(alertsto='','".$_SESSION['SESSuserID']."',CONCAT(alertsto,',".$_SESSION['SESSuserID']."')))";
	
	$up_sql = "update ".$_REQUEST['wf_tablename']." set wfstatus='$cancel_status', workflowseq=(workflowseq+100),cancelledby='".$_SESSION['SESSuserID']."',lastupdatedat='".date('Y-m-d H:i:s')."' $alertsto $wfusers where id='".$_REQUEST['ID']."'";
	mysqli_query($con,$up_sql);
	
	SendGroupAlerts($_REQUEST['ID'],$cancel_status,$_REQUEST['wf_tablename'],$sendsms,$inweb,$firebase);
	
}

// new Work flow : nov 6th 2020
class WorkFlow{
	
	public function __construct($objectid) {
		global $con;
    	$this->objectid = $objectid;
    	$obj_sql = "select * from tbl_objectmaster where id='$objectid'";
    	$obj_res = mysqli_query($con,$obj_sql);
    	$obj_arr = $obj_res->fetch_array();
    	$this->pageurl = $obj_arr['url'];
    	
    	
  	}

  	public function WorkflowPageRights($recordid,$tablename) {
  		global $con;
  		
  		$WF_exist  = $this->checkWorkflow($this->objectid,$recordid,$tablename);
  				
		if($WF_exist == "NO"){
			return $this->loadPagerights();
		}
		else if($WF_exist == "YES"){

			$New_button = "";
			if( $recordid == '0' ){ // for new record with workflow
				$sql = "select tbl_workflowline.* from tbl_workflow left  join tbl_workflowline on tbl_workflowline.parentid=tbl_workflow.id where tbl_workflow.objectid='".$this->objectid."' and tbl_workflowline.usergroup in (".$_SESSION['usermenurole'].") and users like '%".$_SESSION['SESSuserID']."%' and tbl_workflow.module='".$_SESSION['usermodulecode']."' order by tbl_workflowline.sequencer limit 0,1";
				// add company , profit cneter and module filter also.
				$res = mysqli_query($con,$sql);
    			if( $res->num_rows>0){
    				$arr = $res->fetch_array();
					if($arr['processstart']== 'true'){
						$New_button = $this->loadPagerights(); //"I,U,D";  // @seq : 1, we didefine the user vcan create or not.	
					}
				}
				
			}
			else{
				
				    $seq1 = "select workflowseq,companycode,divisioncode from $tablename where id='$recordid'"	 ;
					$res1 = mysqli_query($con,$seq1);
					$arr1 = $res1->fetch_array();
					
					$workflowseq = $arr1['workflowseq'];
					$sql = "select tbl_workflowline.* from tbl_workflow left  join tbl_workflowline on tbl_workflowline.parentid=tbl_workflow.id where tbl_workflow.objectid='".$this->objectid."' and tbl_workflowline.usergroup in (".$_SESSION['usermenurole'].") and users like '%".$_SESSION['SESSuserID']."%' and tbl_workflowline.sequencer='".$workflowseq."' and tbl_workflow.companycode='".$arr1['companycode']."' and tbl_workflow.profitcenter='".$arr1['divisioncode']."' and tbl_workflow.module='".$_SESSION['usermodulecode']."' limit 0,1";
					$res = mysqli_query($con,$sql);
					$arr = $res->fetch_array();
					if($arr['processstart']== 'true'){
						$New_button = $this->loadPagerights(); //"I,U,D";  // @seq : 1, we defined the user can create or not.	
					}
					else{
						$New_button = "";
					}
				
			}
			return $New_button;
			
		}
  		
		}

	public function checkWorkflow($objectid,$recordid,$tablename) {
  		global $con;
  		
  		if($recordid!="0"){
	        $seq1 = "select workflowseq,companycode,divisioncode from $tablename where id='$recordid'";
			$res1 = mysqli_query($con,$seq1);
			$arr1 = $res1->fetch_array();
			
			$addsql = " and tbl_workflow.companycode='".$arr1['companycode']."' and tbl_workflow.profitcenter='".$arr1['divisioncode']."'";
		}
		else{
			$addsql = "";
		}
  		
  		$sql = "select tbl_workflowline.* from tbl_workflow left  join tbl_workflowline on tbl_workflowline.parentid=tbl_workflow.id where tbl_workflow.objectid='".$objectid."' and tbl_workflowline.usergroup in (".$_SESSION['usermenurole'].") and users like '%".$_SESSION['SESSuserID']."%' $addsql and tbl_workflow.module='".$_SESSION['usermodulecode']."' order by tbl_workflowline.sequencer";
    	$res = mysqli_query($con,$sql);
    	if($res->num_rows>0){
    		$WF_exist = "YES";
		} 
		else{
			 // no workflow, so bring page rights
		    $WF_exist = "NO";
			
		}
		return $WF_exist;
	}
	
	
    public function actionWorkflow($recordid,$tablename) {
  		global $con;
  		
  		$WF_exist = $this->checkWorkflow($this->objectid,$recordid,$tablename);
		$Action_button = "";
		if($WF_exist == "YES"){
			
			if( $recordid != '0' ){ // for existing record with workflow
				
				    $seq1 = "select workflowseq,companycode,divisioncode from $tablename where id='$recordid'"	 ;
					$res1 = mysqli_query($con,$seq1);
					$arr1 = $res1->fetch_array();
					
					$workflowseq = $arr1['workflowseq'];
					$sql = "select tbl_workflowline.* from tbl_workflow left  join tbl_workflowline on tbl_workflowline.parentid=tbl_workflow.id where tbl_workflow.objectid='".$this->objectid."' and tbl_workflowline.usergroup in (".$_SESSION['usermenurole'].") and users like '%".$_SESSION['SESSuserID']."%' and tbl_workflowline.sequencer='".$workflowseq."' and tbl_workflow.companycode='".$arr1['companycode']."' and tbl_workflow.profitcenter='".$arr1['divisioncode']."' and tbl_workflow.module='".$_SESSION['usermodulecode']."' limit 0,1";
					$res = mysqli_query($con,$sql);
					$arr = $res->fetch_array();
					
					$approvalmode = $arr['approvalmode'];
					$wf_disable = "";
					if($approvalmode == "33002"){
						// field values to validate
						$wf_valuefrom = $arr['valuefrom'];
						$wf_valueto = $arr['valueto'];
						$wf_fieldname = $arr['fieldname'];
						$wf_tablename = GetWorlflowTable($arr['tableid']);
						$vres = mysqli_query($con,"select $wf_fieldname from $wf_tablename where id='".$recordid."'");
						$varr = mysqli_fetch_array($vres);
						$wf_fieldValue = $varr[$wf_fieldname] ;
						if($wf_fieldValue == "") $wf_disable = "disabled";
						if($wf_fieldValue < $wf_valuefrom) $wf_disable = "disabled";
						if($wf_fieldValue > $wf_valueto) $wf_disable = "disabled";
						
					}
					
					if($arr['canreject'] == "2001")
					    $Action_button .= "<button class='btn btn-danger inputs' style='margin-top:-5px;float:right;margin-right:2px;' name='btnsuccess' type='button' onclick='javascript:wf_rejectrecord(\"".$this->objectid." \",$recordid,\"$tablename\",\"".$this->pageurl." \");'> Reject </font>&nbsp;<i class='fa fa-ban' aria-hidden='true'></i></button>";
					
					if($arr['cancancel'] == "2001")
					    $Action_button .= "<button class='btn btn-warning inputs' style='margin-top:-5px;float:right;margin-right:2px;' name='btnsuccess' type='button' onclick='javascript:wf_cancelrecord(\"".$this->objectid." \",$recordid,\"$tablename\",\"".$this->pageurl." \");'> Cancel </font>&nbsp;<i class='fa fa-close' aria-hidden='true'></i></button>";
					    
					if($arr['canrevise'] == "2001")
					    $Action_button .= "<button class='btn btn-info inputs' style='margin-top:-5px;float:right;margin-right:2px;' name='btnsuccess' type='button' onclick='javascript:wf_reviserecord(\"".$this->objectid." \",$recordid,\"$tablename\",\"".$this->pageurl." \");'> Revise </font>&nbsp;<i class='fa fa-history' aria-hidden='true'></i></button>";
					
					if($arr['textonbutton']!="")
						$Action_button .= "<button class='btn btn-success inputs' style='margin-top:-5px;float:right;margin-right:2px;' name='btnsuccess' type='button' onclick='javascript:wf_".$arr['action']."(\"".$this->objectid." \",$recordid,\"$tablename\",\"".$this->pageurl." \" );' $wf_disable>  ".$arr['textonbutton']." </font>&nbsp;<i class='fa fa-check' aria-hidden='true'></i></button>";
						echo $arr['action'];

			}
			
			
		}
  		return $Action_button;
	}
	
	public function loadPagerights() {
  		global $con;
    	$smenu_sql = "select tbl_menu.objectid,tbl_menu.menu_url as url,tbl_menu.menu_name as name,tbl_menu.menu_icon as iconimage,sum(if(viewdata='true',1,0)) as viewdata,sum(if(adddata='true',1,0)) as adddata,sum(if(editdata='true',1,0)) as editdata,sum(if(deletedata='true',1,0)) as deletedata from tbl_menusetup left join tbl_menu on tbl_menusetup.menucode=tbl_menu.menu_code where tbl_menusetup.usergroupid in (".$_SESSION['usermenurole'].") and tbl_menu.objectid='".$this->objectid."' group by tbl_menusetup.menucode order by tbl_menu.slno";
			$res = mysqli_query($con,$smenu_sql);
	    	if($res->num_rows>0){
	    		while($strDataString3 = $res->fetch_array()){
					/*$viewdata_prev = "";
					if($strDataString3['viewdata'] >0) $viewdata_prev = "YES";*/

					$adddata_prev = "";
					if($strDataString3['adddata'] >0) $adddata_prev = "I,";

					$editdata_prev = "";
					if($strDataString3['editdata'] >0) $editdata_prev = "U,";

					$deletedata_prev = "";
					if($strDataString3['deletedata'] >0) $deletedata_prev = "D";
					
					return $adddata_prev.$editdata_prev.$deletedata_prev;
				
				}
			}	
	}
}
// end of work flow


function SendGroupAlerts($recordid,$status,$tablename,$sms,$inweb,$firebase){
	global $con;
	$SQL = "select alertsto from $tablename where id='$recordid'";
	$RES = mysqli_query($con,$SQL);
	if($RES->num_rows>0){
		while($ARR = $RES->fetch_array()){
			$user_arr = explode(',',$ARR['alertsto']);
			for($i=0;$i<count($user_arr);$i++){
				$insql = "insert into tbl_groupalerts(id,subject,sendto,sendby,senddate,mobilenumber,sms,inweb,firebase) values 	
				('".GetTableID('tbl_groupalerts')."','".GetWorlflowFStatus($status)."','".$user_arr[$i]."','".$_SESSION['SESSuserID']."','".date('Y-m-d H:i:s')."','".GetMobileno($user_arr[$i])."','$sms','$inweb','$firebase')";
				mysqli_query($con,$insql);
			  }
		}	// end of while loop
	}
	
}
function SendSMS($recordid,$status,$tablename){
	global $con;
	$SQL = "select alertsto from $tablename where id='$recordid'";
	$RES = mysqli_query($con,$SQL);
	if($RES->num_rows>0){
		while($ARR = $RES->fetch_array()){
			$user_arr = explode(',',$ARR['alertsto']);
			for($i=0;$i<count($user_arr);$i++){
				$insql = "insert into tbl_sms(id,subject,sendto,sendby,senddate,mobilenumber) values 	
				('".GetTableID('tbl_sms')."','".GetWorlflowFStatus($status)."','".$user_arr[$i]."','".$_SESSION['SESSuserID']."','".date('Y-m-d H:i:s')."','".GetMobileno($user_arr[$i])."')";
				mysqli_query($con,$insql);
			  }
		}	// end of while loop
	}
	
	/*$WF = new WorkFlow($objectid);
	$WF_exist  = $WF->checkWorkflow($objectid);
  				
	if($WF_exist == "YES"){
		//echo $sms_sql = "select tbl_workflowline.* from tbl_workflow left join tbl_workflowline on tbl_workflow.id = tbl_workflowline.parentid where tbl_workflow.objectid='".$objectid."' and  tbl_workflowline.sequencer<".$wf_sequencer."";
		$sms_sql = "select tbl_workflowline.* from tbl_workflow left join tbl_workflowline on tbl_workflow.id = tbl_workflowline.parentid where tbl_workflow.objectid='".$objectid."' and  tbl_workflowline.sequencer = ".$wf_sequencer."";
		$sms_res = mysqli_query($con,$sms_sql);
		if($sms_res->num_rows>0)
		while($sms_arr = $sms_res->fetch_array()){
			//echo $sms_arr[0];
		
		}
	}
	else{
		return;
	}*/
}

function SendAlerts($recordid,$status,$tablename) {
	
	global $con;
	$SQL = "select alertsto from $tablename where id='$recordid'";
	$RES = mysqli_query($con,$SQL);
	if($RES->num_rows>0){
		while($ARR = $RES->fetch_array()){
			$user_arr = explode(',',$ARR['alertsto']);
			for($i=0;$i<count($user_arr);$i++){
				$insql = "insert into tbl_alerts(id,subject,sendto,sendby,senddate,posted) values 	
				('".GetTableID('tbl_alerts')."','".GetWorlflowFStatus($status)."','".$user_arr[$i]."','".$_SESSION['SESSuserID']."','".date('Y-m-d H:i:s')."','YES')";
				mysqli_query($con,$insql);
			  }
		}	// end of while loop
	}
	
}

function GetMobileno($userid){
	global $con;
	$res = mysqli_query($con,"select empworkmobile from in_personalinfo where empid='$userid'");
	if(mysqli_num_rows($res) >0){
		$arr = mysqli_fetch_array($res);
		return $arr[0];
	}else return false;
	
}
function GetTableID($tblName){
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
function GetWorlflowFStatus($statusid){
	global $con;
	$res = mysqli_query($con,"select statusname from tbl_status where id='$statusid'");
	if(mysqli_num_rows($res) >0){
		$arr = mysqli_fetch_array($res);
		return $arr['statusname'];
	}
	else return $statusid;

}
function GetWorlflowTable($tableid){
	global $con;
	$res = mysqli_query($con,"select objectname from tbl_objectmaster where id='$tableid'");
	if(mysqli_num_rows($res) >0){
		$arr = mysqli_fetch_array($res);
		return $arr['objectname'];
	}
	else return $tableid;

}

function GetWfDictionary($lable){
	global $con;
	$newlable = "";
	$sql = "select * from tbl_languagedictionary where objectid='".$_SESSION['objectid']."' and lable='".$lable."'";
	$res = mysqli_query($con,$sql);
	$rowcount=mysqli_num_rows($res);
	if($rowcount > 0){
		$arr = $res->fetch_array();
		$language = $_SESSION['UserLanguage'];
		if($language != "")
		$newlable = $arr[$language];
		else 
		$newlable = $lable;
	}
	else{
		$insql = "insert into tbl_languagedictionary (objectid,lable,english,german,arabic) values ('".$_SESSION['objectid']."','$lable','$lable en','$lable gr','$lable ar')";
		mysqli_query($con,$insql);
		//GetWfDictionary($lable);
		$newlable = $lable;
		
	}
	
	return $newlable;

}

?>
