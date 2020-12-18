<?php
session_start();
//$_SESSION['pr'] = isset($_REQUEST['pr']) ? $_REQUEST['pr'] : '';
require "connection.php";
require "pagingObj.php";

$insert = $update = $delete = "false";

if(false !== strpos($_SESSION['pr'],"I")) $insert = "true" ;
if(false !== strpos($_SESSION['pr'],"U")) $update = "true" ;
if(false !== strpos($_SESSION['pr'],"D")) $delete = "true" ;

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
      <link rel="stylesheet" href="dist/css/mainStylesChild.css">
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

      <link rel="stylesheet" type="text/css" media="screen" href="css/my_styles2.css">
      <link rel="stylesheet" type="text/css" media="screen" href="css/my_styles1.css">
     
      <script language="javascript">

function showData(catval){
	if(catval == "33002")//Field Approval
	{
		document.getElementById('tr1').style.display = 'table-row';
	}
	else{
		document.getElementById('tr1').style.display = 'none';
	}
}
function getuserofgroup(cattype){
      xmlHttp=GetXmlHttpObject()
      if (xmlHttp==null)
      {
                 alert ("Browser does not support HTTP Request")
                 return
      }

      var url="combofunctions_setup.php?level=usersofthegroup&usergroup="+cattype; 
      xmlHttp.onreadystatechange=stateChangedcombo_2
      xmlHttp.open("POST",url,true)
      xmlHttp.send(null)
}
function stateChangedcombo_2(){
       if (xmlHttp.readyState==4 || xmlHttp.readyState=="complete")
       {
             var s1 = trim(xmlHttp.responseText);  
             document.getElementById('divcheckbox').innerHTML=s1;
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
            if((iKeyCode<=1 && iKeyCode>=7) || (iKeyCode>=9 && iKeyCode<45) || (iKeyCode>=58 && iKeyCode<=255)){
                if (iKeyCode!=13) {
                    alertify.alert('Numbers Only');
                     return false;
                }
            }
            return true;
    }

function editingChildrecord(){ 

	var cmb_A_usergroup=document.getElementById('cmb_A_usergroup');
	if(cmb_A_usergroup){
	  if (cmb_A_usergroup.value==""){
	       alertify.alert("Select User Group", function () {
	       cmb_A_usergroup.focus();

	  });
	     return;
	  }
	}
	
	var cmb_A_action=document.getElementById('cmb_A_action');
	if(cmb_A_action){
	  if (cmb_A_action.value==""){
	       alertify.alert("Select Action", function () {
	       cmb_A_action.focus();

	  });
	     return;
	  }
	}
	
	var cmb_A_approvalmode=document.getElementById('cmb_A_approvalmode');
	if(cmb_A_approvalmode){
	  if (cmb_A_approvalmode.value==""){
	       alertify.alert("Select Approval Type", function () {
	       cmb_A_approvalmode.focus();

	  });
	     return;
	  }
	}
	
	if(cmb_A_approvalmode.value == "33002")//Field Approval
	{
		var txt_A_fieldname=document.getElementById('txt_A_fieldname');
		if(txt_A_fieldname){
		  if (txt_A_fieldname.value==""){
		       alertify.alert("Enter Field name", function () {
		       txt_A_fieldname.focus();

		  });
		     return;
		  }
		}
		
		var txt_A_valuefrom=document.getElementById('txt_A_valuefrom');
		if(txt_A_valuefrom){
		  if (txt_A_valuefrom.value==""){
		       alertify.alert("Enter Value From", function () {
		       txt_A_valuefrom.focus();

		  });
		     return;
		  }
		}
		
		var txt_A_valueto=document.getElementById('txt_A_valueto');
		if(txt_A_valueto){
		  if (txt_A_valueto.value==""){
		       alertify.alert("Enter Value To", function () {
		       txt_A_valueto.focus();

		  });
		     return;
		  }
		}
		
	}
	

	
	chks = document.getElementsByName('userlist[]');
               
		var checkboxvalidation ='NO';
		var menus = "";
		for (i = 0; i < chks.length; i++){
		if (chks[i].checked){
		    checkboxvalidation = 'YES';
		    menus += chks[i].value+',';
		   
		}
 
		}
		if(chks){
		  if (checkboxvalidation=='NO'){
		       alertify.alert("SelectUsers", function () {
		       userlist.focus();

		  });
		     return;
		  }
		}
	   menus =menus.slice(0,-1)	;
	   
   var cmb_A_status=document.getElementById('cmb_A_status');
	if(cmb_A_status){
	  if (cmb_A_status.value==""){
	       alertify.alert("Select Status", function () {
	       cmb_A_status.focus();

	  });
	     return;
	  }
	}
	
	
       var parameter =get(document.frmChildEdit)+'txt_A_users='+menus;
   
   //checkforDulicate(parameter);    
   insertChildfunction(parameter)

}

function checkforDulicate(parameter){
         xmlHttp=GetXmlHttpObject()
                          if (xmlHttp==null)
                          {
                                 alert ("Browser does not support HTTP Request")
                                 return
                          }
                          var url='checkforduplicaterecords.php'+parameter+'TYPE=WORKFLOW_SETUP';
                          xmlHttp.onreadystatechange=stateChangedcombo81
                          xmlHttp.open("POST",url,true)
                          xmlHttp.send(null)
}

function stateChangedcombo81(){
       if (xmlHttp.readyState==4 || xmlHttp.readyState=="complete")
       {
             var s1 = trim(xmlHttp.responseText);
             
             if(s1=='Yes'){
               alertify.alert("Duplicate Entry!!");
               return;
             }
             else{
                insertChildfunction(parameter)
                return;
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

function insertChildfunction(parameters)
{
      xmlHttp=GetXmlHttpObject()
      if (xmlHttp==null)
      {
             alert ("Browser does not support HTTP Request")
             return
      }


      var url="in_action.php"+parameters;
      xmlHttp.onreadystatechange=stateChangedchild
      xmlHttp.open("POST",url,true)
      xmlHttp.send(null)

}
function stateChangedchild()
{

     if (xmlHttp.readyState==4 || xmlHttp.readyState=="complete")
     {
           var s1 = trim(xmlHttp.responseText);
           var s2 = "Record Saved";
           var s3 = "Record Updated";
           if(s1.toString() == s2.toString()){
             alertify.alert("Record Saved", function () {
              document.frmChildEdit.action='editworkflowline.php?PARENTID='+document.getElementById('txt_A_parentid').value;
              document.frmChildEdit.submit();
             });
           }else if(s1.toString() == s3.toString()){
            alertify.alert("Record Updated", function () {
              document.frmChildEdit.action='editworkflowline.php?PARENTID='+document.getElementById('txt_A_parentid').value;
              document.frmChildEdit.submit();
             });
           }else{
            alertify.alert(s1);
           }

     }

}

function updateChildrecord(childid){

    document.frmChildEdit.action='editworkflowline.php?CHILDID='+childid+'&PARENTID='+document.getElementById('txt_A_parentid').value;
    document.frmChildEdit.submit();
}

function deleteChildrecord(childid){

        alertify.confirm("Are you sure you want to delete ? ", function (e) {
         if (e) {
           document.frmChildEdit.action='editworkflowline.php?DEL=DELETE&CHILDID='+childid+'&PARENTID='+document.getElementById('txt_A_parentid').value;
           document.frmChildEdit.submit();
         } else {
            return;
         }

       });

}

function blockSpecialChar(e) {
            var k = e.keyCode;
            return (k!=39);
}

function calldisable(thisval,disabled_filedid){ 
	if(thisval == "2001"){
		document.getElementById(disabled_filedid).disabled = false;
	}
	else{
		document.getElementById(disabled_filedid).value='';
		document.getElementById(disabled_filedid).disabled= true ;
	}
	
}

</script>
</head>
<body >
<section class="content" id='content-content-id' style='padding-right:5px;padding-left:5px;' >
   <div class="box-body" id='box-body-id' style='background-color:#fff;'>
          <div class='table-responsive' >
<?php

$PARENTID = isset($_REQUEST['PARENTID']) ? $_REQUEST['PARENTID'] : '';

$CHILDID = isset($_REQUEST['CHILDID']) ? $_REQUEST['CHILDID'] : '';

$DEL = isset($_REQUEST['DEL']) ? $_REQUEST['DEL'] : '';

$frmPage_startrow = isset($_REQUEST['frmPage_startrow']) ? $_REQUEST['frmPage_startrow'] : '';

    $formlistname = "editworkflowline.php";

    $grid = new MyPHPGrid('frmPage');

    $grid->formName = "editworkflowline.php";

    $grid->inpage = $frmPage_startrow;

    $grid->TableNameChild = "tbl_workflowline";

    $grid->SyncSession($grid);

/*	if($CHILDID !='' && $DEL !='DELETE'){
	        $SEL12 = "Select * from tbl_workflowline where id ='".$CHILDID."'";
	        $dis12 = mysqli_query($con,$SEL12);
	        
	        while ($arr12 = mysqli_fetch_array($dis12)) {
	               $sequencer=$arr12['sequencer'];
	               $usergroup= $arr12['usergroup'];
	               $users= $arr12['users'];
	               $action= $arr12['action'];
	               $textonbutton= $arr12['textonbutton'];
	               $fieldname= $arr12['fieldname'];
	               $valuefrom= $arr12['valuefrom'];
	               $valueto= $arr12['valueto'];
	               $approvalmode= $arr12['approvalmode'];
	               $inweb= $arr12['inweb'];
				   $firebase= $arr12['firebase'];
				   $sms= $arr12['sms'];
				   $email= $arr12['email'];
				   $noalets= $arr12['noalerts'];
				   $cancancel = $arr12['cancancel'];
					$canreject = $arr12['canreject'];
					$canedit = $arr12['canedit'];
					$status = $arr12['status'];
	               $PARENTID = $arr12['parentid'];
	               $tableid = $arr12['tableid'];
	               if($approvalmode == "33002")$display_filedvalue = "table-row";
				   else $display_filedvalue = "none";
	     }
	}*/

if($CHILDID !='' && $DEL =='DELETE'){
        mysqli_query($con,"delete from tbl_workflowline where id='". $CHILDID."'");
        $CHILDID ="";

}
                                  
        $sql_1 = "select * from tbl_workflowline where id='".$CHILDID."'";
        $res_1 = mysqli_query($con,$sql_1);
                                  
		if(mysqli_num_rows($res_1)>=1){
		    $arr_1 = mysqli_fetch_array($res_1);
			$sequencer=$arr_1['sequencer'];
			$usergroup= $arr_1['usergroup'];
			$users= $arr_1['users'];
			$action= $arr_1['action'];
			$textonbutton= $arr_1['textonbutton'];
			$fieldname= $arr_1['fieldname'];
			$valuefrom= $arr_1['valuefrom'];
			$valueto= $arr_1['valueto'];
			$approvalmode= $arr_1['approvalmode'];
			$inweb= $arr_1['inweb'];
			$firebase= $arr_1['firebase'];
			$sms= $arr_1['sms'];
			$email= $arr_1['email'];
			$noalets= $arr_1['noalerts'];
			$PARENTID = $arr_1['parentid'];
			$cancancel = $arr_1['cancancel'];
			$canreject = $arr_1['canreject'];
			$canedit = $arr_1['canedit'];
			$status = $arr_1['status'];
			
			if($approvalmode == "33002")$display_filedvalue = "table-row";
			else $display_filedvalue = "none";
			
			if($cancancel == "2001") $cancel_status_disabled = "";
			else $cancel_status_disabled = "disabled";
			
			if($canreject == "2001") $reject_status_disabled = "";
			else $reject_status_disabled = "disabled";
		}
		else{
			$textonbutton = $fieldname = $approvalmode = $valuefrom = $valueto = $inweb = $firebase = $sms = $email = $noalets = $cancancel = $canreject = $canedit =$status = $tableid ="";
			$sequencer = GetLastSqeIDofTable('tbl_workflowline',$PARENTID);
			$usergroup = "";
			$users="";
			$action="";
			$display_filedvalue = "none";
			$cancel_status_disabled = "disabled";
			$reject_status_disabled = "disabled";
		}
		
		$inweb_checked = ($inweb == "true") ? 'checked' : '';
		$firebase_checked = ($firebase == "true") ? 'checked' : '';
		$sms_checked = ($sms == "true") ? 'checked' : '';
		$email_checked = ($email == "true") ? 'checked' : '';
		$noalets_checked = ($noalets == "true") ? 'checked' : '';
                                  
        $no_of_rows = mysqli_num_rows(mysqli_query($con,"select * from tbl_workflowline where parentid='".$PARENTID."'"));
        $mandatory = "<span class='mandatory'>&nbsp;*</span>";
		
		$Save_button = "";
		if(($insert == "true" && $CHILDID =="") || ($update == "true" && $CHILDID !=""))
        $Save_button = "<a href='javascript:editingChildrecord();'><img src='ico/save.png' title='Save' width='20' height='20'></a><a href='?PARENTID=".$PARENTID."'><img src='ico/cancel.png' title='Cancel' width='20' height='20'></a>";
        
        $entrydata = "<div class='table-responsive no-padding' >
            <form name='frmChildEdit' method='post' id='frmChildEdit' autocomplete='off' enctype='multipart/form-data'>
                <table class='table table-bordered table-condensed table-fixed  table-responsive' style='table-layout:fixed'>
                    <tr>
                        <td class='dvtCellLabel' style='border: 1px solid #ccc;width:13%;'>Sequencer $mandatory</td>
                        <td style='border: 1px solid #ccc;'><input type='text' onkeypress='return AllowNumeric1(event)' class='form-control txt' name='txt_A_sequencer' id='txt_A_sequencer' value='$sequencer' readonly></td>
                        <td class='dvtCellLabel' style='border: 1px solid #ccc;width:13%;'>User Group $mandatory</td>
                        <td style='border: 1px solid #ccc;'>
                        ".GetUserGroup($usergroup)."
                        </td>
                        <td class='dvtCellLabel' style='border: 1px solid #ccc;width:13%;'>Users $mandatory</td>
                        <td style='border: 1px solid #ccc;'>
                        ".GetUsers($usergroup,$users)."
                        </td>
                    </tr>
                    <tr>
                        <td class='dvtCellLabel' style='border: 1px solid #ccc;width:13%;'>Actions $mandatory</td>
                        <td style='border: 1px solid #ccc;'>".GetWorkflowAction($sequencer,$action,$PARENTID)."</td>
                        <td class='dvtCellLabel' style='border: 1px solid #ccc;width:13%;'>Text on Button $mandatory</td>
                        <td style='border: 1px solid #ccc;'>
                        <input type='text' class='form-control txt' name='txt_A_textonbutton' id='txt_A_textonbutton' value='$textonbutton'>
                        </td>
                        <td class='dvtCellLabel' style='border: 1px solid #ccc;width:13%;'>Approval Mode $mandatory</td>
                        <td style='border: 1px solid #ccc;'>
                        ".GetApprovalMode($approvalmode)."
                        </td>
                    </tr>
                    <tr id='tr1' style='display:$display_filedvalue'>
                    	<td class='dvtCellLabel' style='border: 1px solid #ccc;width:13%;'>Field Name $mandatory</td>
                        <td style='border: 1px solid #ccc;'><input type='text' class='form-control txt' name='txt_A_fieldname' id='txt_A_fieldname' value='$fieldname'></td>
                        <td class='dvtCellLabel' style='border: 1px solid #ccc;width:13%;'>Value From $mandatory</td>
                        <td style='border: 1px solid #ccc;'><input type='text' class='form-control txt' name='txt_A_valuefrom' id='txt_A_valuefrom' value='$valuefrom'></td>
                        <td class='dvtCellLabel' style='border: 1px solid #ccc;width:13%;'>Value To $mandatory</td>
                        <td style='border: 1px solid #ccc;'><input type='text' class='form-control txt' name='txt_A_valueto' id='txt_A_valueto' value='$valueto'></td>
                        
                    </tr>
                    <tr>
                    	<td class='dvtCellLabel' style='border: 1px solid #ccc;width:13%;'>Can Cancel $mandatory</td>
                    	<td style='border: 1px solid #ccc;'>".GetYesNo($cancancel,'cmb_A_cancancel','onchange=javascript:calldisable(this.value,\'cmb_A_cancelstatus\')')."</td>
                    	<td class='dvtCellLabel' style='border: 1px solid #ccc;width:13%;'>Cancel Status$mandatory</td>                    	                <td style='border: 1px solid #ccc;'>".GetStatus('cmb_A_cancelstatus',$status,$cancel_status_disabled)."</td>
                    </tr>
                    	<td class='dvtCellLabel' style='border: 1px solid #ccc;width:13%;'>Can Reject $mandatory</td>
                    	<td style='border: 1px solid #ccc;'>".GetYesNo($canreject,'cmb_A_canreject','onchange=javascript:calldisable(this.value,\'cmb_A_rejectstatus\')')."</td> 
                    	<td class='dvtCellLabel' style='border: 1px solid #ccc;width:13%;'>Reject Status$mandatory</td>
                    	<td style='border: 1px solid #ccc;'>".GetStatus('cmb_A_rejectstatus',$status,$reject_status_disabled)."</td> 
                     </tr>	
                    	<td class='dvtCellLabel' style='border: 1px solid #ccc;width:13%;'>Can Edit $mandatory</td>
                    	<td style='border: 1px solid #ccc;'>".GetYesNo($canedit,'cmb_A_canedit','')."</td>
                    	<td class='dvtCellLabel' style='border: 1px solid #ccc;width:13%;'>Table Name $mandatory</td> 
                    	<td style='border: 1px solid #ccc;'><input type='text' class='form-control txt' name='txt_A_tableid' id='txt_A_tableid' value='$tableid'></td>
                    	<td class='dvtCellLabel' style='border: 1px solid #ccc;width:13%;'>Field Name $mandatory</td>
                    	<td style='border: 1px solid #ccc;'><input type='text' class='form-control txt' name='txt_A_fieldname' id='txt_A_fieldname' value='$fieldname'></td>
                    </tr>
                    <tr>
                    	<td class='dvtCellLabel' style='border: 1px solid #ccc;width:13%;'>Status $mandatory</td>
                    	<td style='border: 1px solid #ccc;'>".GetStatus('cmb_A_status',$status,'')."</td> 
                    	<td class='dvtCellLabel' style='border: 1px solid #ccc;width:13%;'>Alerts $mandatory</td>
                    	<td style='border: 1px solid #ccc;' colspan=3>
                    	<input type='checkbox' name='txt_A_inweb' id='txt_A_inweb'  class='minimal inputs' $inweb_checked />&nbsp; &nbsp;In Web &nbsp; &nbsp;
                    	<input type='checkbox' name='txt_A_firebase' id='txt_A_firebase' class='minimal inputs' $firebase_checked/>&nbsp; &nbsp;Firebase &nbsp; &nbsp;
                    	<input type='checkbox' name='txt_A_sms' id='txt_A_sms' class='minimal inputs' $sms_checked/>&nbsp; &nbsp;SMS &nbsp; &nbsp;
                    	<input type='checkbox' name='txt_A_email' id='txt_A_email' class='minimal inputs' $email_checked/>&nbsp; &nbsp;Email &nbsp; &nbsp;
                    	<input type='checkbox' name='txt_A_noalerts' id='txt_A_noalerts' class='minimal inputs' $noalets_checked/>&nbsp; &nbsp;None &nbsp; &nbsp;
                    	
                    	</td>
                    </tr>
                    <tr>
                        <td colspan=5 style='border: 1px solid #fff;' >&nbsp;
                        </td>                   
                        <td style='border: 1px solid #fff; ' align=right>
                        ".$Save_button."
                        <input type='hidden' class=textboxcombo name='txt_A_parentid' id='txt_A_parentid' value='".$PARENTID."'>
                        <input type=hidden id=child name=child value='child'>
                        <input type=hidden id=childid name=childid value='".$CHILDID."'>
                        </td>
                    </tr>
                </table>
            </form>

        </div>";

if(($insert == "true" && $CHILDID =="") || ($update == "true"))
echo $entrydata;

$start1=0;
$limit1=1000;
      /*if(isset($_GET['id1'])){
         $id1=$_GET['id1'];
         $start1=($id1-1)*$limit1;
      }else{
         $id1=1;
      }*/
      $id1 = isset($_GET['id1']) ? $_GET['id1'] : '';
         
         if($id1!="" ){
                 $id1=$_GET['id1'];
                 $start1=($id1-1)*$limit1;
                 
         }else{
                 $id1=1;
         }
$addsql="";
if(isset($_REQUEST['search'])!=""){
	$addsql = " and (";
	$addsql .= " menu_name like '%".$_REQUEST['search']."%'";
	$addsql .= ")";
}

$list_sql = "SELECT tbl_workflowline.*,tbl_usergroup.usergroup as rolename,A.lookname as lookname,B.lookname as type FROM tbl_workflowline left join tbl_usergroup on tbl_workflowline.usergroup=tbl_usergroup.id left join in_lookup as A on A.lookcode = tbl_workflowline.action left join in_lookup as B on B.lookcode=tbl_workflowline.approvalmode
where tbl_workflowline.parentid='".$PARENTID."' $addsql order by id";//
$rows1=mysqli_num_rows(mysqli_query($con,$list_sql));

$p_rows=mysqli_num_rows(mysqli_query($con,$list_sql));

/*echo "<div class='box' style='border:0px;padding:0px;'>

       <div class='box-tools pull-right '>
            <ul class='pagination pagination-sm no-padding pull-right'>";

                $total1=ceil($rows1/$limit1);
                for($i=1;$i<=$total1;$i++){
                    if($i==$id1) {
                       echo "<li class='active' ><a href='' style='padding-top:0px;padding-bottom:0px;padding-left:.5em;padding-right:.5em;'>".$i."</a></li>";
                    }else {
                       echo "<li><a href='?PARENTID=".$PARENTID."&id1=".$i."' style='padding-top:0px;padding-bottom:0px;padding-left:.5em;padding-right:.5em;'>".$i."</a></li>"; }
                    }
       echo "</ul>
       </div>
       </div>";*/


$sql = $list_sql. " LIMIT $start1, $limit1";
$result = mysqli_query($con,$sql) or die(mysqli_error());
        $entrydatatable = "<table class='table table-bordered table-condensed table-fixed table-striped table-responsive' style='border:1px #2F3C43;'>";
        $entrydatatable.="<thead><tr>";
        $entrydatatable.= "<th class='bg-light-blue' style='width:5%;text-align:left;border-bottom:1px #2F3C43 solid;border-top:1px #fff solid;color:#FFFFFF'>Seq. </th>";
        $entrydatatable.= "<th class='bg-light-blue' style='width:15%;text-align:left;border-bottom:1px #2F3C43 solid;border-top:1px #fff solid;color:#FFFFFF'>User Group</th>";
        $entrydatatable.= "<th class='bg-light-blue' style='width:20%;text-align:left;border-bottom:1px #2F3C43 solid;border-top:1px #fff solid;color:#FFFFFF'>Users</th>";
        $entrydatatable.= "<th class='bg-light-blue' style='width:15%;text-align:left;border-bottom:1px #2F3C43 solid;border-top:1px #fff solid;color:#FFFFFF'>Action</th>";
        $entrydatatable.= "<th class='bg-light-blue' style='width:15%;text-align:left;border-bottom:1px #2F3C43 solid;border-top:1px #fff solid;color:#FFFFFF'>Text on Button</th>";
        $entrydatatable.= "<th class='bg-light-blue' style='width:15%;text-align:left;border-bottom:1px #2F3C43 solid;border-top:1px #fff solid;color:#FFFFFF'>Approval Type</th>";

        
        $entrydatatable.= "<th class='bg-light-blue' style='width:5%;text-align:left;border-bottom:1px #2F3C43 solid;border-top:1px #fff solid;color:#FFFFFF'>Edit</th>";
        $entrydatatable.= "<th class='bg-light-blue' style='width:5%;text-align:left;border-bottom:1px #2F3C43 solid;border-top:1px #fff solid;color:#FFFFFF'>Del</th>";
        
        $entrydatatable.= "</tr></thead>
        <tbody  class='sortable'>";
        
		while($loginResultArrayChild   = mysqli_fetch_array($result)){

        	$colorbg ='#FFFFFF';
        	$colorfc ='#5A5A5A';
        	
        	$entrydatatable.= "<tr id='".$loginResultArrayChild['id']."'>";
        	$entrydatatable.= "<td style='background-color:$colorbg;color:$colorfc;border:1px #ccc solid;'>" . $loginResultArrayChild['sequencer']. "</td>";
        	$entrydatatable.= "<td style='background-color:$colorbg;color:$colorfc;border:1px #ccc solid;'>" . $loginResultArrayChild['rolename'] . "</td>";
        	$entrydatatable.= "<td style='background-color:$colorbg;color:$colorfc;border:1px #ccc solid;'>" . $loginResultArrayChild['users']. "</td>";
        	$entrydatatable.= "<td style='background-color:$colorbg;color:$colorfc;border:1px #ccc solid;'>" . $loginResultArrayChild['lookname']."</td>";
        	$entrydatatable.= "<td style='background-color:$colorbg;color:$colorfc;border:1px #ccc solid;'>" . $loginResultArrayChild['textonbutton']."</td>";
        	$entrydatatable.= "<td style='background-color:$colorbg;color:$colorfc;border:1px #ccc solid;'>" . $loginResultArrayChild['type']."</td>";
        	if($update == "true")
        	$entrydatatable.= "<td style='background-color:$colorbg;color:$colorfc;border:1px #ccc solid;' align='center'><a href='javascript:updateChildrecord(\"".$loginResultArrayChild['id']."\");'><img src='ico/edit.png' title='Update' width='16' height='16'></a></td>";
        	else
        	$entrydatatable.= "<td style='background-color:$colorbg;color:$colorfc;border:1px #ccc solid;' align='center'></td>";
        	if($delete == "true")
            $entrydatatable.=" <td style='background-color:$colorbg;color:$colorfc;border:1px #ccc solid;' align='center'><a href='javascript:deleteChildrecord(\"".$loginResultArrayChild['id']."\");'><img src='ico/delete.ico' title='Remove' width='16' height='16'></a></td>";
            else
            $entrydatatable.=" <td style='background-color:$colorbg;color:$colorfc;border:1px #ccc solid;' align='center'></td>";

        	

        	$entrydatatable.= "</tr>";
}

$entrydatatable.= "</tbody></table></div>";
echo $entrydatatable;




?>       </div>

</div>
   </body>
</html>

      <script src="jq/jquery-2.1.1.min.js"></script>
      <script src="plugins/jQuery/jquery-2.2.3.min.js"></script>
      <script src="bootstrap/js/bootstrap.min.js"></script>
      <script src="plugins/select2/select2.full.min.js"></script>
      <script src="plugins/jqueryValidate/jquery.validate.js"></script>
      <script src="dist/js/app.js"></script>
      <script type="text/javascript" src="js/jquery-1.8.0.js"></script>
      <script src="plugins/slimScroll/jquery.slimscroll.min.js"></script>
       <script type='text/javascript'>
       

$(window).load(function(){
                   boxHeight()
                   $(".select2").select2();
                   $(window).resize(function(){
                     boxHeight();
                   })

                });
              function boxHeight(){
                    var height = $("#content-wrapper-id",parent.parent.document).height()-132;
                    $('#tab-content-id').height(height);
                    var boxheight = height +10;

                    $('#box-body-id').height(boxheight);
                    $('#box-body-id').slimScroll({
                      height: boxheight+'px'
                    });
                }              

</script>
<?php
function GetWorkflowAction($sequencer,$action,$PARENTID){
		 global $con;
		 $CMB = "<select name='cmb_A_action' id='cmb_A_action' class='form-control select'>  ";
         $CMB .= "<option value=''>Select</option>";
         if($sequencer!='1'){
         	/*if($action=="") 
         	$addsql1 = "and lookcode <>(select action from tbl_workflowline where parentid='$PARENTID' order by id desc limit 0,1)";
         	else*/ 
         	$addsql1 ="and lookcode <>(select action from tbl_workflowline where parentid='$PARENTID' and sequencer=".($sequencer-1)." order by id) ";
		 	$addsql = "  and (lookcode <>'36001' and lookcode<>'36002' and lookcode<>'36003' $addsql1)";
		 	$SEL =  "select lookcode,lookname from in_lookup where looktype='WORKFLOW ACTIONS' and lookname<>'XX' $addsql order by id";
		 }
		 else {
		 	$SEL =  "select lookcode,lookname from in_lookup where looktype='WORKFLOW ACTIONS' and lookname<>'XX' order by id";
		 }
         
         $RES = mysqli_query($con,$SEL);
         while ($ARR_1 = mysqli_fetch_array($RES)) { 
         		$SEL = "";
                if(strtoupper($action) == strtoupper($ARR_1['lookcode'])){ $SEL =  "SELECTED";}
                $CMB .= "<option value='".$ARR_1['lookcode']."' $SEL >".$ARR_1['lookname']."</option>";
         }
         return $CMB;
}
function GetUsers($usergroup,$users){
         global $con;
		$mycontrol = "<div id='divcheckbox'  class='form-group' style='max-height:100px;overflow-y:scroll;'>";
		$menucode_arr = "'".str_replace(",","','",$users)."'"; 
		if($usergroup!=""){
			$SEL =  "select userid,username from in_user where rolecode like '%$usergroup%'";
	        $RES = mysqli_query($con,$SEL);
	        while($ARR = mysqli_fetch_array($RES)){
	        	if (strpos("-," . $users.",",",".$ARR['userid'].",")>0) 
	        	{ $SEL =  "checked";}
	        	else $SEL = '';
				$mycontrol .= "<input type='checkbox' class='minimal inputs' id='userlist' $SEL  name='userlist[]' value='".$ARR['userid']."'/>&nbsp;" . $ARR['username']. "<br>";
			}
		}
    	return $mycontrol."</div>";
}
function GetApprovalMode($approvalmode){
		global $con;
         $CMB = "<select name='cmb_A_approvalmode' id='cmb_A_approvalmode' class='form-control select' onChange='javascript:showData(this.value);'>  ";
         $CMB .= "<option value=''>Select</option>";
         $SEL =  "select lookcode,lookname from in_lookup where looktype='APPROVAL MODE' and lookname<>'XX' ";
         $RES = mysqli_query($con,$SEL);
         while ($ARR = mysqli_fetch_array($RES)) {
                $SEL = "";
                if(strtoupper($approvalmode) == strtoupper($ARR['lookcode'])){ $SEL =  "SELECTED";}
                $CMB .= "<option value='".$ARR['lookcode']."' $SEL >".$ARR['lookname']."</option>";
         }
         $CMB .= "</select>";
         return $CMB;
}

function GetUserGroup($usergroup){
		global $con;
         $CMB = "<select name='cmb_A_usergroup' id='cmb_A_usergroup' class='form-control select2' onChange = 'javascript:getuserofgroup(this.value)'>  ";
         $CMB .= "<option value=''>Select</option>";
         $SEL =  "select id,usergroup from tbl_usergroup order by slno";
         $RES = mysqli_query($con,$SEL);
         while ($ARR = mysqli_fetch_array($RES)) {
                $SEL = "";
                if(strtoupper($usergroup) == strtoupper($ARR['id'])){ $SEL =  "SELECTED";}
                $CMB .= "<option value='".$ARR['id']."' $SEL >".$ARR['usergroup']."</option>";
         }
         $CMB .= "</select>";
         return $CMB;
}
function GetLastSqeIDofTable($tblName,$parentid){
	global $con;
	$query = "LOCK TABLES $tblName WRITE";
	mysqli_query($con,$query) or die(mysqli_error()."<br>".$query);
	$seqSQL = "SELECT max(sequencer) as LASTNUMBER FROM $tblName where parentid = '$parentid'";
	$result=mysqli_query($con,$seqSQL) or die(mysqli_error()."<br>".$seqSQL);
	$resulArr=mysqli_fetch_array($result);
	$updatedSeqID=$resulArr['LASTNUMBER']+1;
	$query = "UNLOCK TABLES";
	mysqli_query($con,$query) or die(mysqli_error()."<br>".$query);
	return ($updatedSeqID);
}
function GetYesNo($yesno,$fieldname,$onchange){
	global $con;
    $CMB = " <select name='$fieldname'  id='$fieldname' class='form-control select' $onchange>";    
    //$CMB .= "<option value=''>Select</option>";
	$SEL =  "Select lookcode,lookname from in_lookup where looktype='YESNO' and lookname<>'XX' order by id desc";
	$RES = mysqli_query($con,$SEL);
	while ($ARR = mysqli_fetch_array($RES)) {
	   $SEL = "";
	   if($yesno == $ARR['lookcode']){ $SEL =  "SELECTED";}
	   $CMB .= "<option value='".trim($ARR['lookcode'])."' $SEL >".trim($ARR['lookname'])."</option>";
	}
	$CMB .= "</select>";
	return $CMB;
}
function GetStatus($fieldname,$status,$lock){
	global $con;
    $CMB = " <select name='$fieldname'  id='$fieldname' class='form-control select' $lock>";    
    $CMB .= "<option value=''>Select</option>";
	$SEL =  "Select id,statusname from tbl_status order by id desc";
	$RES = mysqli_query($con,$SEL);
	while ($ARR = mysqli_fetch_array($RES)) {
	   $SEL = "";
	   if($status == $ARR['id']){ $SEL =  "SELECTED";}
	   $CMB .= "<option value='".trim($ARR['id'])."' $SEL >".trim($ARR['statusname'])."</option>";
	}
	$CMB .= "</select>";
	return $CMB;
}
?>
