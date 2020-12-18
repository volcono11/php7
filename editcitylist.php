<?php
@session_start();
//$_SESSION['pr'] = isset($_REQUEST['pr']) ? $_REQUEST['pr'] : '';
require "connection.php";
require "pagingObj.php";
include "functions_workflow.php";
//print_r($_REQUEST);
$WF = new WorkFlow($_SESSION['objectid']);
$pagerights = $WF->WorkflowPageRights($_REQUEST['ID'],'tbl_companysetup');
$Action_button = $WF->actionWorkflow($_REQUEST['ID'],'tbl_companysetup');

$_SESSION['pr'] = isset($pagerights) ? $pagerights : '';

$insert = $update = $delete = "false";

if(false !== strpos($_SESSION['pr'],"I")) $insert = "true" ;
if(false !== strpos($_SESSION['pr'],"U")) $update = "true" ;
if(false !== strpos($_SESSION['pr'],"D")) $delete = "true" ;

if($_REQUEST['ID'] != "0") {
             $mode=$_REQUEST['ID'];

             $SQL = " Select * from tbl_city where id='".$_REQUEST['ID']."'";
             $SQLRes =  mysqli_query($con,$SQL) or die(mysqli_error()."<br>".$SQL);
              if(mysqli_num_rows($SQLRes)>=1){
                while($loginResultArray   = mysqli_fetch_array($SQLRes)){
                   $saveid =  $loginResultArray['id'];
                   $city = $loginResultArray['city'];
                   $emirateid = $loginResultArray['emirateid'];
                  }
              }
           }else{
             $mode="";
             $saveid = GetLastSqeID("tbl_city");
             $city = $emirateid = "";
}
if(isset($_REQUEST['dr'])=='view'){
   $edit="none";
   $view="inline";
   $title="Viewing Emirate : $city";
}else if(isset($_REQUEST['dr'])=='edit'){
      $edit="inline";
      $view="none";
      $title="Editing Emirate : $city";
}else{
      $edit="inline";
      $view="none";
      $title="Adding New Emirate";
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
function getborderchange(){

     $('#txt_A_lookcode').css('border-color', '');
     $('#txt_A_looktype').css('border-color', '');

}
function editingrecord(action)
{

       var cmb_A_emirateid=document.getElementById('cmb_A_emirateid');
       if(cmb_A_emirateid){
          if ((cmb_A_emirateid.value==null)||(cmb_A_emirateid.value=="")){
               alertify.alert("Select Emirate", function () {
               cmb_A_emirateid.focus();

          });
             return;
          }
       }
       
       var txt_A_city=document.getElementById('txt_A_city');
       if(txt_A_city){
          if ((txt_A_city.value==null)||(txt_A_city.value=="")){
               alertify.alert("Enter City", function () {
               txt_A_city.focus();

          });
             return;
          }
       }

     //  multiple= $(".select2").val();
       var parameter =get(document.frmEdit);

       insertfunction(parameter,action)
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
                                 window.location.href='editcitylist.php?dr=edit&ID='+document.getElementById('saveid').value;
                                });
                               }else if(s1.toString() == s3.toString()){
                                alertify.alert("Record Updated", function () {
                                window.location.href='editcitylist.php?dr=edit&ID='+document.getElementById('mode').value;

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
                                 window.location.href='editcitylist.php?dr=add&ID=0';
                                });
                               }else if(s1.toString() == s3.toString()){
                                alertify.alert("Record Updated", function () {
                                window.location.href='editcitylist.php?dr=add&ID=0';

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
                                window.location.href='citylist.php?ID=0';
                               }else if(s1.toString() == s3.toString()){
                                alertify.alert("Record Updated", function () {
                                window.location.href='citylist.php';

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

                 <a class="pull-left" href="citylist.php?objectid=<?php echo $_SESSION['objectid']; ?>&&frmPage_rowcount=<?php echo $_SESSION['frmPage_rowcount']; ?>&txtsearch=<?php echo $_SESSION['txtsearch']; ?>&frmPage_startrow=<?php echo $_SESSION['frmPage_startrow'];?>" data-toggle="tooltip" data-placement="right" title="Back to City list"><i class='fa fa-backward'></i></a>
                 <h2 class="title">&nbsp;&nbsp;<?php echo $title; ?></h2>

                 <!--<ol class='breadcrumb'>
                  <li><a href="#"><a href="blank.php" >Admin Setup</a></li>
                  <li><a href="#"><a href="citylist.php?ps=1">emiratelist</a></li>
                  <li class="active"><?php echo $title; ?></li>
                 </ol>-->

         </section>

                <section class="content" id='content-content-id' style='padding-right:5px;padding-left:5px;margin-top:-10px;' >

                  <div class="nav-tabs-custom" id="nav-tabs-custom-id">
                        <ul class="nav nav-tabs">

                           <li class="active"><a href="#personal" data-toggle="tab"><i class="fa fa-desktop" aria-hidden="true"></i>&nbsp; City</a></li>
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
                                                              <td class='dvtCellLabel' style='border: 1px solid #ccc;'>Code:</td>
                                                              <td style='border: 1px solid #ccc;'><input type='text' class='form-control txt inputs' readonly  value='$saveid' ></td>
                                                              <td class='dvtCellLabel' style='border: 1px solid #ccc;'>Emirate:$mandatory</td>
                                                              <td style='border: 1px solid #ccc;'>".GetEmirate($emirateid)."</td>
                                                              <td class='dvtCellLabel' style='border: 1px solid #ccc;'>City:$mandatory</td>
                                                              <td style='border: 1px solid #ccc;'><input type='text' class='form-control txt inputs'  name='txt_A_city' id='txt_A_city'  value='$city' ></td>
                                                            
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
                        $entrydata.="  <button class='btn btn-danger inputs' style='margin-top:-5px;' name='btndanger' type='button'  onclick ='javascript:closeediting(\"citylist.php?ps=1&pr=".$_SESSION['pr']."\");'>Close &nbsp;<i class='fa fa-close' aria-hidden='true'></i></button>
                                        </div>";
                        $entrydata.= "</form>  ";
echo  $entrydata;
?>


                  </div>

        </section>
</body>
</html>
<?php
function GetEmirate($emirateid){
	global $con;
	$CMB = " <select name='cmb_A_emirateid'  id='cmb_A_emirateid' class='form-control select2'>";
	$seqSQL = "select id  as lookcode,emirate as lookname from tbl_states order by emirate";
	$result=mysqli_query($con,$seqSQL) or die(mysqli_error()."<br>".$seqSQL);
	$CMB .= "<option value='' >Select Country</option>";
	while($ARR=mysqli_fetch_array($result)){
		$SEL = "";
		if(strtoupper($emirateid)== strtoupper($ARR['lookcode'])){ $SEL =  "SELECTED";}
               $CMB .= "<option value='".strtoupper($ARR['lookcode'])."' $SEL >".$ARR['lookname']."</option>";
    }
    $CMB .= "</select>";
    return $CMB;
}
function GetLastSqeID($tblName){
	global$con;
                 $query = "LOCK TABLES $tblName WRITE";
                 mysqli_query($con,$query) or die(mysqli_error()."<br>".$query);
                 $seqSQL = "SELECT max(id) as LASTNUMBER FROM $tblName";
                 $result=mysqli_query($con,$seqSQL) or die(mysqli_error()."<br>".$seqSQL);
                 $resulArr=mysqli_fetch_array($result);
                 $updatedSeqID=$resulArr['LASTNUMBER']+1;
                 //$squpdateSQL = "UPDATE in_sequencer SET LASTNUMBER=".$updatedSeqID." WHERE TABLENAME='$tblName'";
                 //mysqli_query($con,$squpdateSQL) or die(mysqli_error()."<br>".$squpdateSQL);
                 $query = "UNLOCK TABLES";
                 mysqli_query($con,$query) or die(mysqli_error()."<br>".$query);
                 return ($updatedSeqID);
}

 ?>
      <script src="jq/jquery-2.1.1.min.js"></script>
      <script src="plugins/jQuery/jquery-2.2.3.min.js"></script>
      <script src="bootstrap/js/bootstrap.min.js"></script>
      <script src="plugins/slimScroll/jquery.slimscroll.js"></script>
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
</script>