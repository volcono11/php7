<?php
session_start();
//echo $_SESSION['pr'];// = isset($_REQUEST['pr']) ? $_REQUEST['pr'] : '';
require "connection.php";
require "pagingObj.php";
include "functions_workflow.php";
//print_r($_REQUEST);
$WF = new WorkFlow($_SESSION['objectid']);
$pagerights = $WF->loadPagerights();
$_SESSION['pr'] = isset($pagerights) ? $pagerights : '';
//echo $_SESSION['pr'];

$grid = new MyPHPGrid('frmPage');
$grid->TableName = "tbl_workflow";
$grid->formName = "workflowsetup.php";
$grid->SyncSession($grid);
$grid->TableNameChild = "";

$insert = $update = $delete = "false";

if(false !== strpos($_SESSION['pr'],"I")) $insert = "true" ;
if(false !== strpos($_SESSION['pr'],"U")) $update = "true" ;
if(false !== strpos($_SESSION['pr'],"D")) $delete = "true" ;



if($_REQUEST['ID'] != "0") {
             $mode=$_REQUEST['ID'];

             $SQL = " Select * from tbl_workflow where id='".$_REQUEST['ID']."'";
             $SQLRes =  mysqli_query($con,$SQL) or die(mysqli_error()."<br>".$SQL);
              if(mysqli_num_rows($SQLRes)>=1){
                while($loginResultArray   = mysqli_fetch_array($SQLRes)){
                   $saveid =  $loginResultArray['id'];
                   $workflowname = htmlspecialchars($loginResultArray['workflowname']);
                   $companycode =  $loginResultArray['companycode'];
                   $profitcenter =  $loginResultArray['profitcenter'];
                   $module =  $loginResultArray['module'];
                   $objecttype =  $loginResultArray['objecttype'];
                   $formname =  $loginResultArray['objectid'];
                  }
              }
           }else{
              $mode="";
              $saveid =  GetLastSqeID("tbl_workflow");
              $companycode = $workflowname = $profitcenter = $module = $objecttype = $formname = "";
              
}

if(isset($_REQUEST['dr'])=='view'){
   $edit="none";
   $view="inline";
   $title="Viewing : ".$workflowname."";
}else if(isset($_REQUEST['dr'])=='edit'){
      $edit="inline";
      $view="none";
      $title="Editing : ".$workflowname."";
}else{
      $edit="inline";
      $view="none";
      $title="Adding New Workflow";
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

      <link rel="stylesheet" type="text/css" media="screen" href="css/my_styles2.css">
      <link rel="stylesheet" type="text/css" media="screen" href="css/my_styles1.css">
<script language="javascript">

function getForms(cattype){
      xmlHttp=GetXmlHttpObject()
      if (xmlHttp==null)
      {
                 alert ("Browser does not support HTTP Request")
                 return
      }

      var url="combofunctions_setup.php?level=formsfromobjectlist&objecttype="+cattype; 
      xmlHttp.onreadystatechange=stateChangedcombo_2
      xmlHttp.open("POST",url,true)
      xmlHttp.send(null)
}
function stateChangedcombo_2(){
       if (xmlHttp.readyState==4 || xmlHttp.readyState=="complete")
       {
             var s1 = trim(xmlHttp.responseText); 
             document.getElementById('cmb_A_objectid').innerHTML=s1;
       }
}
function getDivisionofcompany(cattype){
      xmlHttp=GetXmlHttpObject()
      if (xmlHttp==null)
      {
                 alert ("Browser does not support HTTP Request")
                 return
      }

      var url="combofunctions_setup.php?level=divisionofcompany&companycode="+cattype; 
      xmlHttp.onreadystatechange=stateChangedcombo_1
      xmlHttp.open("POST",url,true)
      xmlHttp.send(null)
}
function stateChangedcombo_1(){
       if (xmlHttp.readyState==4 || xmlHttp.readyState=="complete")
       {
             var s1 = trim(xmlHttp.responseText); 
             document.getElementById('cmb_A_profitcenter').innerHTML=s1;
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
function borderchange(){

   $('#txt_A_TABLENAME').css('border-color', '');
   $('#txt_A_LASTNUMBER').css('border-color', '');

}
function editingrecord(action)
{

       var txt_A_workflowname=document.getElementById('txt_A_workflowname');
       if(txt_A_workflowname){
          if ((txt_A_workflowname.value==null)||(txt_A_workflowname.value=="")){
               alertify.alert("Enter Workflow Name", function () {
               txt_A_workflowname.focus();

          });
             return;
          }
       }
       var cmb_A_companycode=document.getElementById('cmb_A_companycode');
       if(cmb_A_companycode){
          if ((cmb_A_companycode.value==null)||(cmb_A_companycode.value=="")){
               alertify.alert("Select Company", function () {
               cmb_A_companycode.focus();

          });
             return;
          }
       }
       
       var cmb_A_profitcenter=document.getElementById('cmb_A_profitcenter');
       if(cmb_A_profitcenter){
          if ((cmb_A_profitcenter.value==null)||(cmb_A_profitcenter.value=="")){
               alertify.alert("Select Profit Center", function () {
               cmb_A_profitcenter.focus();

          });
             return;
          }
       }
       
       var cmb_A_profitcenter=document.getElementById('cmb_A_module');
       if(cmb_A_profitcenter){
          if ((cmb_A_profitcenter.value==null)||(cmb_A_profitcenter.value=="")){
               alertify.alert("Select Module", function () {
               cmb_A_profitcenter.focus();

          });
             return;
          }
       }
       
       var cmb_A_profitcenter=document.getElementById('cmb_A_objecttype');
       if(cmb_A_profitcenter){
          if ((cmb_A_profitcenter.value==null)||(cmb_A_profitcenter.value=="")){
               alertify.alert("Select Object Type", function () {
               cmb_A_profitcenter.focus();

          });
             return;
          }
       }
       
       var cmb_A_profitcenter=document.getElementById('cmb_A_objectid');
       if(cmb_A_profitcenter){
          if ((cmb_A_profitcenter.value==null)||(cmb_A_profitcenter.value=="")){
               alertify.alert("Select Formname", function () {
               cmb_A_profitcenter.focus();

          });
             return;
          }
       }


       insertfunction(get(document.frmEdit),action)
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


                          var url="in_action.php"+parameters
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
                                 window.location.href='editworkflowsetup.php?dr=edit&ID='+document.getElementById('saveid').value;
                                });
                               }else if(s1.toString() == s3.toString()){
                                alertify.alert("Record Updated", function () {
                                window.location.href='editworkflowsetup.php?dr=edit&ID='+document.getElementById('mode').value;

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
                                 window.location.href='editworkflowsetup.php?dr=add&ID=0';
                                });
                               }else if(s1.toString() == s3.toString()){
                                alertify.alert("Record Updated", function () {
                                window.location.href='editworkflowsetup.php?dr=add&ID=0';

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
                                window.location.href='workflowsetup.php?ID=0';
                               }else if(s1.toString() == s3.toString()){
                                alertify.alert("Record Updated", function () {
                                window.location.href='workflowsetup.php';

                               });


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
</script>
</head>
<body class="hold-transition sidebar-mini">

         <section class="content-header">

                 <a class="pull-left" href="workflowsetup.php?objectid=<?php echo $_SESSION['objectid']; ?>&pr=<?php echo $_SESSION['pr']; ?>&frmPage_rowcount=<?php echo $_SESSION['frmPage_rowcount']; ?>&txtsearch=<?php echo $_SESSION['txtsearch']; ?>&frmPage_startrow=<?php echo $_SESSION['frmPage_startrow'];?>" data-toggle="tooltip" data-placement="right" title="Back to Home"><i class='fa fa-backward'></i></a>
                 <h2 class="title">&nbsp;&nbsp;<?php echo $title; ?></h2>
<!--
                 <ol class='breadcrumb'>
                  <li><a href="#"><a href="blank.php" >Admin Setup</a></li>
                  <li><a href="#"><a href="workflowsetup.php?ps=1">Sequencer</a></li>
                  <li class="active"><?php echo $title; ?></li>
                 </ol>-->

         </section>

                <section class="content" id='content-content-id' style='padding-right:5px;padding-left:5px;margin-top:-10px;' >

                  <div class="nav-tabs-custom" id="nav-tabs-custom-id">
                        <ul class="nav nav-tabs">

                           <li class="active"><a href="#personal" data-toggle="tab" onclick="javascript:loadpage(2);"><i class="fa fa-desktop" aria-hidden="true"></i>&nbsp; Workflow</a></li>
                           <?php
                           if($_REQUEST['ID'] != "0") {
                           	?>
                           <li><a href="#setup"   onclick='javascript:loadpage(3);' data-toggle="tab"><i class="fa fa-cogs" aria-hidden="true"></i> &nbsp;Setup</a></li>
                           <?php }
                           ?>
                       </ul>

                        <div class="tab-content" id='tab-content-id'>
                          <div class="tab-pane active" id="personal">
                              <div class="box-body no-padding" id='box-body-id'>
                                   <div class='table-responsive no-padding'>

<?php
			$mandatory = "<span class='mandatory'>&nbsp;*</span>";
            $entrydata = "<form name='frmEdit' method='post' id='frmEdit' enctype='multipart/form-data'>

                                 <table class='table table-bordered table-condensed table-fixed  table-responsive' style='table-layout:fixed'>
                                                            <tr>
                                                              
                                                              <td class='dvtCellLabel' style='border: 1px solid #ccc;'> Workflow Name:$mandatory</td>
                                                              <td style='border: 1px solid #ccc;'><input type='text' class='form-control txt inputs'  name='txt_A_workflowname' id='txt_A_workflowname'  value='$workflowname' ></td>
                                                              <td class='dvtCellLabel' style='border: 1px solid #ccc;'>Company : $mandatory</td>
                                                              <td style='border: 1px solid #ccc;'>".GetCompany($companycode)."</td>
                                                              <td class='dvtCellLabel' style='border: 1px solid #ccc;'> Profit Center:$mandatory</td>
                                                              <td style='border: 1px solid #ccc;'>".GetProfitCenter($profitcenter,$companycode)."</td>
                                                              </tr>
                                                              <tr>
                                                              <td class='dvtCellLabel' style='border: 1px solid #ccc;'> Module :$mandatory</td>
                                                              <td style='border: 1px solid #ccc;'>".GetModules($module)."</td>
                                                              <td class='dvtCellLabel' style='border: 1px solid #ccc;'> Object Type:$mandatory</td>
                                                              <td style='border: 1px solid #ccc;'>".GetObjectType($objecttype)."</td>
                                                              <td class='dvtCellLabel' style='border: 1px solid #ccc;'> Form Name:$mandatory</td>
                                                              <td style='border: 1px solid #ccc;'>
                                                              	".GetFormName($formname,$objecttype)."
                                                              </td>
                                                              
                                                              </tr>

                                                                <input type='hidden' name='mode' class=textboxcombo id='mode' value='$mode'>
                                                                <input type='hidden' name='modeid' class=textboxcombo id='modeid' value='save'>
                                                                <input type='hidden' name='saveid' class=textboxcombo id='saveid' value='$saveid'>
                                                               </td>
                                                              </tr>
                                                            </table>

                                              </div>
                                             </div>";
						
                       $entrydata.=  "<div class='box-footer' style='border-top:1px #D2D2D2 solid;'>";
                       if($update == "true" || ($insert=="true" && $_REQUEST['ID']==0))
                       $entrydata.="  <button class='btn btn-success inputs' style='margin-top:-5px;' name='btnsuccess' type='button'  onclick ='javascript:editingrecord(\"save\");'>Save </font>&nbsp;<i class='fa fa-save' aria-hidden='true'></i></button>";
                       if( ($insert=="true" && $_REQUEST['ID']==0) || ($update == "true" && $insert=="true"))
                       $entrydata.="  <button class='btn btn-info inputs' style='margin-top:-5px;' name='btninfo' type='button' onclick ='javascript:editingrecord(\"savenew\");'>Save & New &nbsp; <i class='fa fa-save' aria-hidden='true'></i>&nbsp;&nbsp;<i class='fa fa-plus' aria-hidden='true'></i></button>";
                       
                       if($update == "true" || ($insert=="true" && $_REQUEST['ID']==0))
                        $entrydata.="   <button class='btn btn-warning inputs' style='margin-top:-5px;' name='btnwarning' type='button'  onclick ='javascript:editingrecord(\"saveclose\");'>Save & Close &nbsp;<i class='fa fa-save' aria-hidden='true'></i>&nbsp;&nbsp;<i class='fa fa-close' aria-hidden='true'></i></button>";
                        
                        $entrydata.="  <button class='btn btn-danger inputs' style='margin-top:-5px;' name='btndanger' type='button'  onclick ='javascript:closeediting(\"workflowsetup.php?ps=1&pr=".$_SESSION['pr']."\");'>Close &nbsp;<i class='fa fa-close' aria-hidden='true'></i></button>";
                        $entrydata.="</div>";
                        $entrydata.= "</form> ";

echo  $entrydata;

?>

        </div>
        <div class="tab-pane" id="setup">
                  	<iframe id="frame3" name="frame3" scrolling="no" onload='this.width=screen.width;this.height=screen.height;'  frameborder="0" style="position: relative; width: 100%; "></iframe>
                 </div>

</section>
<?php

function GetFormName($formname,$objecttype){
	global $con;
        $CMB = "<select name='cmb_A_objectid' id='cmb_A_objectid' class='form-control select2' >  ";
        $CMB .= "<option value=''>Select</option>";
        $SQL = "select id,objectname from tbl_objectmaster where objecttype='".$objecttype."' order by id";
		$RES_1 = mysqli_query($con,$SQL);
		while ($ARR_1 = mysqli_fetch_array($RES_1)) {
			$SEL = "";
                if(strtoupper($formname) == strtoupper($ARR_1['id'])){ $SEL =  "SELECTED";}
		 	$CMB .= "<option value='".$ARR_1['id']."' $SEL>".$ARR_1['objectname']."</option>";
		}
        $CMB .= "</select>";
        return $CMB;	
}

function GetObjectType($objecttype){
	global $con;
    $CMB = " <select name='cmb_A_objecttype'  id='cmb_A_objecttype' class='form-control select' onChange='javascript:getForms(this.value);'>";    
    $CMB .= "<option value=''>Select</option>";
	$SEL =  "Select lookcode,lookname from in_lookup where looktype='OBJECT TYPE' and lookname<>'XX'";
	$RES = mysqli_query($con,$SEL);
	while ($ARR = mysqli_fetch_array($RES)) {
	   $SEL = "";
	   if($objecttype == $ARR['lookcode']){ $SEL =  "SELECTED";}
	   $CMB .= "<option value='".trim($ARR['lookcode'])."' $SEL >".trim($ARR['lookname'])."</option>";
	}
	$CMB .= "</select>";
	return $CMB;
}

function GetModules($module){
	global $con;
         $CMB = "<select name='cmb_A_module' id='cmb_A_module' class='form-control select2' >  ";
         $CMB .= "<option value=''>Select</option>";
         $seqSQL = "select lookcode ,lookname from in_lookup where looktype='Modules'  and lookname<>'XX' order by slno";
         $RES = mysqli_query($con,$seqSQL);
         while ($ARR = mysqli_fetch_array($RES)) {
                $SEL = "";
                if(strtoupper($module) == strtoupper($ARR['lookcode'])){ $SEL =  "SELECTED";}
                $CMB .= "<option value='".$ARR['lookcode']."' $SEL >".$ARR['lookname']."</option>";
         }
         $CMB .= "</select>";
         return $CMB;	
}
function GetProfitCenter($profitcenter,$companycode){
	global $con;
        $CMB = "<select name='cmb_A_profitcenter' id='cmb_A_profitcenter' class='form-control select2' >  ";
        $CMB .= "<option value=''>Select</option>";
        $SQL = "select id,divisionname from tbl_division where companycode='".$companycode."' order by id";
		$RES_1 = mysqli_query($con,$SQL);
		while ($ARR_1 = mysqli_fetch_array($RES_1)) {
			$SEL = "";
                if(strtoupper($profitcenter) == strtoupper($ARR_1['id'])){ $SEL =  "SELECTED";}
		 	$CMB .= "<option value='".$ARR_1['id']."' $SEL>".$ARR_1['divisionname']."</option>";
		}
        $CMB .= "</select>";
        return $CMB;	
}
function GetCompany($companycode){
	global $con;
         $CMB = "<select name='cmb_A_companycode' id='cmb_A_companycode' class='form-control select2' onChange = 'javascript:getDivisionofcompany(this.value)'>  ";
         $CMB .= "<option value=''>Select</option>";
         $SEL =  "select companycode,companyname from tbl_companysetup ";
         $RES = mysqli_query($con,$SEL);
         while ($ARR = mysqli_fetch_array($RES)) {
                $SEL = "";
                if(strtoupper($companycode) == strtoupper($ARR['companycode'])){ $SEL =  "SELECTED";}
                $CMB .= "<option value='".$ARR['companycode']."' $SEL >".$ARR['companyname']."</option>";
         }
         $CMB .= "</select>";
         return $CMB;	
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
      <script src="jq/jquery-2.1.1.min.js"></script>
      <script src="plugins/jQuery/jquery-2.2.3.min.js"></script>
      <script src="bootstrap/js/bootstrap.min.js"></script>
      <script src="plugins/slimScroll/jquery.slimscroll.js"></script>
      <script src="plugins/select2/select2.full.min.js"></script>
      <script src="plugins/iCheck/icheck.min.js"></script>
      <script src="plugins/jqueryValidate/jquery.validate.js"></script>
      <script src="dist/js/app.js"></script>
      <script type="text/javascript" src="js/jquery-1.8.0.js"></script>

    <script type='text/javascript'>
                $(window).load(function(){
                   boxHeight()
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
</script>
<script type='text/javascript'>

function loadpage(i){
   if(i==2){
       document.frmEdit.action='editworkflowsetup.php?ID='+document.getElementById('mode').value;
       document.frmEdit.submit();
   }
   if(i==3){
   var frame= document.getElementById('frame3');
   frame.src='editworkflowline.php?PARENTID='+<?php echo $_REQUEST['ID']; ?>;
   frame.load();
   }
}

</script>