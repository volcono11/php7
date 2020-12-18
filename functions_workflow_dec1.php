<script type="text/javascript">
	
function rejectrecord(objectid,recordid,tablename,pageurl){
	
	   alertify.confirm("Do You Want To Reject The Record?", function (e) {
         if (e) {
             document.frmEdit.action='edit'+pageurl+'?dr=edit&ID='+recordid+'&action=REJECT'+'&wf_tablename='+tablename+'&objectid='+objectid;
             document.frmEdit.submit();
         } else {
            return ;
         }

       });
	
}

function cancelrecord(objectid,recordid,tablename,pageurl){
	
	   alertify.confirm("Do You Want To Reject The Record?", function (e) {
         if (e) {
             document.frmEdit.action='edit'+pageurl+'?dr=edit&ID='+recordid+'&action=CANCEL'+'&wf_tablename='+tablename+'&objectid='+objectid;
             document.frmEdit.submit();
         } else {
            return ;
         }

       });
	
}

function ac_36001(objectid,recordid,tablename,pageurl){

	   alertify.confirm("Do You Want To Proceed?", function (e) {
         if (e) {
             document.frmEdit.action='edit'+pageurl+'?dr=edit&ID='+recordid+'&action=36001'+'&wf_tablename='+tablename+'&objectid='+objectid;
             document.frmEdit.submit();
         } else {
            return ;
         }
         });

	
}

function ac_36002(objectid,recordid,tablename,pageurl){

	   alertify.confirm("Do You Want To Proceed?", function (e) {
         if (e) {
             document.frmEdit.action='edit'+pageurl+'?dr=edit&ID='+recordid+'&action=36002'+'&wf_tablename='+tablename+'&objectid='+objectid;
             document.frmEdit.submit();
         } else {
            return ;
         }
         });

	
}

function ac_36005(objectid,recordid,tablename,pageurl){

	   alertify.confirm("Do You Want To Proceed?", function (e) {
         if (e) {
             document.frmEdit.action='edit'+pageurl+'?dr=edit&ID='+recordid+'&action=36005'+'&wf_tablename='+tablename+'&objectid='+objectid;
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

if($WF_ACTION =="36001"){ // created and approved
    	$sql_1 = "select tbl_workflowline.* from tbl_workflowline left join ".$_REQUEST['wf_tablename']." on ".$_REQUEST['wf_tablename'].".workflowseq = tbl_workflowline.sequencer right join tbl_workflow on tbl_workflow.id=tbl_workflowline.parentid where ".$_REQUEST['wf_tablename'].".id='".$_REQUEST['ID']."' and objectid=".$_REQUEST['objectid']."";
	$res_1 = mysqli_query($con,$sql_1);
	$reject_status = $wf_sequencer = $sendsms = "";
	if($res_1->num_rows>0){
		$arr_1 = $res_1->fetch_array();
		$reject_status = $arr_1['rejectstatus'];
		$sendsms = $arr_1['sms'];
		$noalerts = $arr_1['noalerts'];
		$wf_sequencer = $arr_1['sequencer'];
		
		if($sendsms == 'true')
		SendSMS($_REQUEST['ID'],$reject_status,$_REQUEST['wf_tablename']);
		  
	}
	$alertsto = "";
	if($noalerts == "false")
	$alertsto=" ,alertsto= if(alertsto like '%".$_SESSION['SESSuserID']."%',alertsto,if(alertsto='','".$_SESSION['SESSuserID']."',CONCAT(alertsto,',".$_SESSION['SESSuserID']."')))";
	
	$up_sql = "update ".$_REQUEST['wf_tablename']." set wfstatus=(select status from tbl_workflowline right join tbl_workflow on tbl_workflow.id=tbl_workflowline.parentid
where action='$WF_ACTION' and objectid=".$_REQUEST['objectid']."), workflowseq=workflowseq+1,lastupdatedat='".date('Y-m-d H:i:s')."',approvedby='".$_SESSION['SESSuserID']."' $alertsto where id='".$_REQUEST['ID']."'";
	mysqli_query($con,$up_sql);
	
}

if($WF_ACTION =="36002"){ // created and forward
	
	$sql_1 = "select tbl_workflowline.* from tbl_workflowline left join ".$_REQUEST['wf_tablename']." on ".$_REQUEST['wf_tablename'].".workflowseq = tbl_workflowline.sequencer right join tbl_workflow on tbl_workflow.id=tbl_workflowline.parentid where ".$_REQUEST['wf_tablename'].".id='".$_REQUEST['ID']."' and objectid=".$_REQUEST['objectid']."";
	$res_1 = mysqli_query($con,$sql_1);
	$reject_status = $wf_sequencer = $sendsms = "";
	if($res_1->num_rows>0){
		$arr_1 = $res_1->fetch_array();
		$reject_status = $arr_1['rejectstatus'];
		$sendsms = $arr_1['sms'];
		$noalerts = $arr_1['noalerts'];
		$wf_sequencer = $arr_1['sequencer'];
		
		if($sendsms == 'true')
		SendSMS($_REQUEST['ID'],$reject_status,$_REQUEST['wf_tablename']);
		  
	}
	$alertsto = "";
	if($noalerts == "false")
	$alertsto=" ,alertsto= if(alertsto like '%".$_SESSION['SESSuserID']."%',alertsto,if(alertsto='','".$_SESSION['SESSuserID']."',CONCAT(alertsto,',".$_SESSION['SESSuserID']."')))";
	
	
	$up_sql = "update ".$_REQUEST['wf_tablename']." set wfstatus=(select status from tbl_workflowline right join tbl_workflow on tbl_workflow.id=tbl_workflowline.parentid
where action='$WF_ACTION' and objectid=".$_REQUEST['objectid']."), workflowseq=workflowseq+1,lastupdatedat='".date('Y-m-d H:i:s')."' $alertsto  where id='".$_REQUEST['ID']."'";
	mysqli_query($con,$up_sql);
	
}

if($WF_ACTION =="36003"){ // created and reference

	$up_sql = "update ".$_REQUEST['wf_tablename']." set wfstatus=(select status from tbl_workflowline right join tbl_workflow on tbl_workflow.id=tbl_workflowline.parentid
where action='$WF_ACTION' and objectid=".$_REQUEST['objectid']."), workflowseq=workflowseq+1,lastupdatedat='".date('Y-m-d H:i:s')."'  where id='".$_REQUEST['ID']."'";
	mysqli_query($con,$up_sql);
	
}

if($WF_ACTION =="36005"){ // forward to next workflow

	$sql_1 = "select tbl_workflowline.* from tbl_workflowline left join ".$_REQUEST['wf_tablename']." on ".$_REQUEST['wf_tablename'].".workflowseq = tbl_workflowline.sequencer right join tbl_workflow on tbl_workflow.id=tbl_workflowline.parentid where ".$_REQUEST['wf_tablename'].".id='".$_REQUEST['ID']."' and objectid=".$_REQUEST['objectid']."";
	$res_1 = mysqli_query($con,$sql_1);
	$reject_status = $wf_sequencer = $sendsms = "";
	if($res_1->num_rows>0){
		$arr_1 = $res_1->fetch_array();
		$reject_status = $arr_1['rejectstatus'];
		$sendsms = $arr_1['sms'];
		$noalerts = $arr_1['noalerts'];
		$wf_sequencer = $arr_1['sequencer'];
		
		if($sendsms == 'true')
		SendSMS($_REQUEST['ID'],$reject_status,$_REQUEST['wf_tablename']);
		  
	}
	$alertsto = "";
	if($noalerts == "false")
	$alertsto=" ,alertsto= if(alertsto like '%".$_SESSION['SESSuserID']."%',alertsto,if(alertsto='','".$_SESSION['SESSuserID']."',CONCAT(alertsto,',".$_SESSION['SESSuserID']."')))";
	
	$up_sql = "update ".$_REQUEST['wf_tablename']." set wfstatus=
	(select status from tbl_workflowline right join tbl_workflow on tbl_workflow.id=tbl_workflowline.parentid
	left join  tbl_companysetup on tbl_companysetup.workflowseq =tbl_workflowline.sequencer
	where action='$WF_ACTION' and objectid=".$_REQUEST['objectid']." and tbl_companysetup.id='".$_REQUEST['ID']."'), 
	workflowseq=workflowseq+1,lastupdatedat='".date('Y-m-d H:i:s')."' $alertsto where id='".$_REQUEST['ID']."'";
	mysqli_query($con,$up_sql);
	
}

if($WF_ACTION =="REJECT"){
	$sql_1 = "select tbl_workflowline.* from tbl_workflowline left join ".$_REQUEST['wf_tablename']." on ".$_REQUEST['wf_tablename'].".workflowseq = tbl_workflowline.sequencer right join tbl_workflow on tbl_workflow.id=tbl_workflowline.parentid where ".$_REQUEST['wf_tablename'].".id='".$_REQUEST['ID']."' and objectid=".$_REQUEST['objectid']."";
	$res_1 = mysqli_query($con,$sql_1);
	$reject_status = $wf_sequencer = $sendsms = "";
	if($res_1->num_rows>0){
		$arr_1 = $res_1->fetch_array();
		$reject_status = $arr_1['rejectstatus'];
		$sendsms = $arr_1['sms'];
		$inweb = $arr_1['inweb'];
		$noalerts = $arr_1['noalerts'];
		$wf_sequencer = $arr_1['sequencer'];
		
		if($sendsms == 'true')
		SendSMS($_REQUEST['ID'],$reject_status,$_REQUEST['wf_tablename']);
		if($inweb == 'true')
		SendAlerts($_REQUEST['ID'],$reject_status,$_REQUEST['wf_tablename']);
		  
	}
	$alertsto = "";
	if($noalerts == "false")
	$alertsto=" ,alertsto= if(alertsto like '%".$_SESSION['SESSuserID']."%',alertsto,if(alertsto='','".$_SESSION['SESSuserID']."',CONCAT(alertsto,',".$_SESSION['SESSuserID']."')))";

	$up_sql = "update ".$_REQUEST['wf_tablename']." set wfstatus='".$reject_status."', workflowseq=100,rejectedby='".$_SESSION['SESSuserID']."',lastupdatedat='".date('Y-m-d H:i:s')."' $alertsto  where id='".$_REQUEST['ID']."'";
	//mysqli_query($con,$up_sql);
	
	
}

if($WF_ACTION =="CANCEL"){
	
	$sql_1 = "select tbl_workflowline.* from tbl_workflowline left join ".$_REQUEST['wf_tablename']." on ".$_REQUEST['wf_tablename'].".workflowseq = tbl_workflowline.sequencer right join tbl_workflow on tbl_workflow.id=tbl_workflowline.parentid where ".$_REQUEST['wf_tablename'].".id='".$_REQUEST['ID']."' and objectid=".$_REQUEST['objectid']."";
	$res_1 = mysqli_query($con,$sql_1);
	$cancel_status = $wf_sequencer = $sendsms = "";
	if($res_1->num_rows>0){
		$arr_1 = $res_1->fetch_array();
		$cancel_status = $arr_1['cancel_status'];
		$sendsms = $arr_1['sms'];
		$inweb = $arr_1['inweb'];
		$noalerts = $arr_1['noalerts'];
		$wf_sequencer = $arr_1['sequencer'];
		
		if($sendsms == 'true')
		SendSMS($_REQUEST['objectid'],$cancel_status,$wf_sequencer);
		if($inweb == 'true')
		SendAlerts($_REQUEST['objectid'],$cancel_status,$wf_sequencer);
		  
	}
	$alertsto = "";
	if($noalerts == "false")
	$alertsto=" ,alertsto= if(alertsto like '%".$_SESSION['SESSuserID']."%',alertsto,if(alertsto='','".$_SESSION['SESSuserID']."',CONCAT(alertsto,',".$_SESSION['SESSuserID']."')))";
	
	$up_sql = "update ".$_REQUEST['wf_tablename']." set wfstatus='$cancel_status', workflowseq=100,cancelledby='".$_SESSION['SESSuserID']."',lastupdatedat='".date('Y-m-d H:i:s')."' $alertsto where id='".$_REQUEST['ID']."'";
	mysqli_query($con,$up_sql);
	
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
  		
  		$WF_exist  = $this->checkWorkflow($this->objectid);
  				
		if($WF_exist == "NO"){
			return $this->loadPagerights();
		}
		else if($WF_exist == "YES"){
			
			$New_button = "";
			if( $recordid == '0' ){ // for new record with workflow
				$sql = "select tbl_workflowline.* from tbl_workflow left  join tbl_workflowline on tbl_workflowline.parentid=tbl_workflow.id where tbl_workflow.objectid='".$this->objectid."' and tbl_workflowline.usergroup in (".$_SESSION['usermenurole'].") and users like '%".$_SESSION['SESSuserID']."%' order by tbl_workflowline.sequencer limit 0,1";
				$res = mysqli_query($con,$sql);
    			if( $res->num_rows>0){
    				$arr = $res->fetch_array();
					if($arr['action']== '36001' || $arr['action']== '36002' ||$arr['action']== '36003'){
						$New_button = $this->loadPagerights(); //"I,U,D";  // @seq : 1, we didefine the user vcan create or not.	
					}
				}
				
			}
			else{
				
				    $seq1 = "select workflowseq from $tablename where id='$recordid'"	 ;
					$res1 = mysqli_query($con,$seq1);
					$arr1 = $res1->fetch_array();
					
					$workflowseq = $arr1['workflowseq'];
					$sql = "select tbl_workflowline.* from tbl_workflow left  join tbl_workflowline on tbl_workflowline.parentid=tbl_workflow.id where tbl_workflow.objectid='".$this->objectid."' and tbl_workflowline.usergroup in (".$_SESSION['usermenurole'].") and users like '%".$_SESSION['SESSuserID']."%' and tbl_workflowline.sequencer='".$workflowseq."' limit 0,1";
					$res = mysqli_query($con,$sql);
					$arr = $res->fetch_array();
					if($arr['action']== '36001' || $arr['action']== '36002' ||$arr['action']== '36003'){
						$New_button = $this->loadPagerights(); //"I,U,D";  // @seq : 1, we defined the user can create or not.	
					}
					else{
						$New_button = "";
					}
				
			}
			return $New_button;
			
		}
  		
		}

	public function checkWorkflow($objectid) {
  		global $con;
  		
  		$sql = "select tbl_workflowline.* from tbl_workflow left  join tbl_workflowline on tbl_workflowline.parentid=tbl_workflow.id where tbl_workflow.objectid='".$objectid."' and tbl_workflowline.usergroup in (".$_SESSION['usermenurole'].") and users like '%".$_SESSION['SESSuserID']."%' order by tbl_workflowline.sequencer";
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
  		
  		$WF_exist = $this->checkWorkflow($this->objectid);
		$Action_button = "";
		if($WF_exist == "YES"){
			
			if( $recordid != '0' ){ // for existing record with workflow
				
				    $seq1 = "select workflowseq from $tablename where id='$recordid'"	 ;
					$res1 = mysqli_query($con,$seq1);
					$arr1 = $res1->fetch_array();
					
					$workflowseq = $arr1['workflowseq'];
					$sql = "select tbl_workflowline.* from tbl_workflow left  join tbl_workflowline on tbl_workflowline.parentid=tbl_workflow.id where tbl_workflow.objectid='".$this->objectid."' and tbl_workflowline.usergroup in (".$_SESSION['usermenurole'].") and users like '%".$_SESSION['SESSuserID']."%' and tbl_workflowline.sequencer='".$workflowseq."' limit 0,1";
					$res = mysqli_query($con,$sql);
					$arr = $res->fetch_array();
					    
					if($arr['canreject'] == "2001")
					    $Action_button .= "<button class='btn btn-danger inputs' style='margin-top:-5px;float:right;margin-right:2px;' name='btnsuccess' type='button' onclick='javascript:rejectrecord(\"".$this->objectid." \",$recordid,\"$tablename\",\"".$this->pageurl." \");'> Reject </font>&nbsp;<i class='fa fa-ban' aria-hidden='true'></i></button>";
					
					if($arr['cancancel'] == "2001")
					    $Action_button .= "<button class='btn btn-warning inputs' style='margin-top:-5px;float:right;margin-right:2px;' name='btnsuccess' type='button' onclick='javascript:cancelrecord(\"".$this->objectid." \",$recordid,\"$tablename\",\"".$this->pageurl." \");'> Cancel </font>&nbsp;<i class='fa fa-close' aria-hidden='true'></i></button>";
					
					if($arr['textonbutton']!="")
						$Action_button .= "<button class='btn btn-success inputs' style='margin-top:-5px;float:right;margin-right:2px;' name='btnsuccess' type='button' onclick='javascript:ac_".$arr['action']."(\"".$this->objectid." \",$recordid,\"$tablename\",\"".$this->pageurl." \" );'>  ".$arr['textonbutton']." </font>&nbsp;<i class='fa fa-check' aria-hidden='true'></i></button>";
					// sms email other are pending 
					// send these info in hiiden data and read in edit page
					// if some of actions ghas cancel and reject we can call same function but has to upfdate those fileds in database in that table
				
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

?>
