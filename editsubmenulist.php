<?php
session_start();
if($_SESSION['pr'] == "") {
  $_SESSION['pr'] = $_REQUEST['pr'];
}

require "connection.php";
require "pagingObj.php";


if($_REQUEST['ID'] != "0") {
             $mode=$_REQUEST['ID'];
             $saveid=$_REQUEST['ID'];

             $SQL = " Select * from inmenu where id='".$_REQUEST['ID']."'";
             $SQLRes =  mysqli_query($con,$SQL) or die(mysqli_error()."<br>".$SQL);
              if(mysqli_num_rows($SQLRes)>=1){
                while($loginResultArray   = mysqli_fetch_array($SQLRes)){
                   $id = $loginResultArray['id'];
                   $slno = $loginResultArray['slno'];
                   $name = $loginResultArray['name'];
                   $roles = $loginResultArray['roles'];
                   $url = $loginResultArray['url'];
                   $parentid=$loginResultArray['parentid'];
                   $iconimage = $loginResultArray['iconimage'];     
                  }
              }
           }else{
             $mode="";
             $saveid = GetLastSqeID("inmenu");
             $parentid=$_REQUEST['cmb_lookuplist'];
             $slno = "";
             $name = "";
             $roles = "";
             $url = "";
             $iconimage = "";
}

if(isset($_REQUEST['dr'])=='view'){
   $edit="none";
   $view="inline";
   $title="Viewing Sub Menu : $name";
}else if(isset($_REQUEST['dr'])=='edit'){
      $edit="inline";
      $view="none";
      $title="Editing Sub Menu : $name";
}else{
      $edit="inline";
      $view="none";
      $title="Adding Sub Menu";
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

function editingrecord(action)
{

       var txt_A_slno=document.getElementById('txt_A_slno');
       if(txt_A_slno){
          if ((txt_A_slno.value==null)||(txt_A_slno.value=="")){
               alertify.alert("Enter Sl No", function () {
               txt_A_slno.focus();

          });
             return;
          }
       }
       var txt_A_name=document.getElementById('txt_A_name');
       if(txt_A_name){
          if ((txt_A_name.value==null)||(txt_A_name.value=="")){
               alertify.alert("Enter Sub Menu Name", function () {
               txt_A_name.focus();

          });
             return;
          }
       }
       var txt_A_url=document.getElementById('txt_A_url');
       if(txt_A_url){
          if ((txt_A_url.value==null)||(txt_A_url.value=="")){
               alertify.alert("Enter URL", function () {
               txt_A_url.focus();

          });
             return;
          }
       }
       var parameter =get(document.frmEdit);
       document.getElementById('frmEdit').action='in_action.php?action='+action;
       document.getElementById('frmEdit').submit();
       return;
       multiple= $(".select2").val();


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
                                 window.location.href='editsubmenulist.php?dr=edit&cmb_lookuplist='+document.getElementById('txt_A_parentid').value+'&ID='+document.getElementById('saveid').value;
                                });
                               }else if(s1.toString() == s3.toString()){
                                alertify.alert("Record Updated", function () {
                                window.location.href='editsubmenulist.php?cmb_lookuplist='+document.getElementById('txt_A_parentid').value+'&dr=edit&ID='+document.getElementById('mode').value;

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
                                 window.location.href='editsubmenulist.php?dr=add&cmb_lookuplist='+document.getElementById('txt_A_parentid').value+'&ID=0';
                                });
                               }else if(s1.toString() == s3.toString()){
                                alertify.alert("Record Updated", function () {
                                window.location.href='editsubmenulist.php?cmb_lookuplist='+document.getElementById('txt_A_parentid').value+'&dr=add&ID=0';

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
                                window.location.href='submenulist.php?ID=0';
                               }else if(s1.toString() == s3.toString()){
                                alertify.alert("Record Updated", function () {
                                window.location.href='submenulist.php?ID='+document.getElementById('mode').value;

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

                 <a class="pull-left" href="submenulist.php?cmb_lookuplist=<?echo $parentid; ?>" data-toggle="tooltip" data-placement="right" title="Back to Sub Menu"><i class='fa fa-backward'></i></a>
                 <h2 class="title">&nbsp;&nbsp;<?echo $title; ?></h2>

                 <ol class='breadcrumb'>
                  <li><a href="#"><a href="blank.php" >Admin Setup</a></li>
                  <li><a href="#"><a href="submenulist.php?ps=1">Sub Menu</a></li>
                  <li class="active"><?echo $title; ?></li>
                 </ol>

    </section>
<section class="content" id='content-content-id' style='padding-right:5px;padding-left:5px;margin-top:-10px;' >

                  <div class="nav-tabs-custom" id="nav-tabs-custom-id">
                        <ul class="nav nav-tabs">
                           <li class="active"><a href="#personal" data-toggle="tab"><i class="fa fa-desktop" aria-hidden="true"></i>&nbsp; Sub Menu</a></li>
                        </ul>

                        <div class="tab-content" id='tab-content-id'>
                           <div class="tab-pane active" id="personal">
                             <div class="box-body no-padding" id='box-body-id'>
                                   <div class='table-responsive no-padding'>

<?

       $entrydata  = " <form name='frmEdit' method='post' id='frmEdit' enctype='multipart/form-data'>
                        <table class='table table-bordered table-condensed table-fixed  table-responsive' style='table-layout:fixed'>

                        <tr>
                            <td class='dvtCellLabel' style='border: 1px solid #ccc;'>Sl No:</td>
                            <td style='width:10%;border: 1px solid #ccc;'><input type='text' class='form-control txt'  onkeypress='return AllowNumeric1(event)'  name='txt_A_slno' id='txt_A_slno'  value='$slno' ></td>
                            <td class='dvtCellLabel' style='border: 1px solid #ccc;'>Sub Menu Name:</td>
                            <td style='width:20%;border: 1px solid #ccc;'><input type='text' class='form-control txt inputs' name='txt_A_name' id='txt_A_name'  value='$name' ></td>
                            <td class='dvtCellLabel' style='border: 1px solid #ccc;'>URL:</td>
                            <td style='border: 1px solid #ccc;'><input type='text' class='form-control txt inputs' name='txt_A_url' id='txt_A_url'  value='$url' >
                        </tr>
                        <tr>

                            <td class='dvtCellLabel' style='border: 1px solid #ccc;'>Roles:</td>
                            <td colspan=5 style='border: 1px solid #ccc;'>".GetRoles($roles)."</td>
                        </tr>
                        <tr>
                            <td class='dvtCellLabel' style='border: 1px solid #ccc;'>Icon Image:</td>
                            <td style='border: 1px solid #ccc;' colspan=5><input type='text' class='form-control txt inputs' name='txt_A_iconimage' id='txt_A_iconimage'  value='$iconimage' >

                               <input type='hidden' name='mode' class=textboxcombo id='mode' value='$mode'>
                               <input type='hidden' name='modeid' class=textboxcombo id='modeid' value='save'>
                               <input type='hidden' name='saveid' class=textboxcombo id='saveid' value='$saveid'>
                               <input type='hidden' name='txt_A_parentid' id='txt_A_parentid' value='".$parentid."'>
                            </td>
                        </tr>

                        </table>

                                    </div>
                                    </div>

                                        <div class='box-footer' style='border-top:1px #D2D2D2 solid;'>
                                        <button class='btn btn-success inputs' style='margin-top:-5px;' name='btnsuccess' type='button'  onclick ='javascript:editingrecord(\"save\");'>Save </font>&nbsp;<i class='fa fa-save' aria-hidden='true'></i></button>
                                        <button class='btn btn-info inputs' style='margin-top:-5px;' name='btninfo' type='button' onclick ='javascript:editingrecord(\"savenew\");'>Save & New &nbsp; <i class='fa fa-save' aria-hidden='true'></i>&nbsp;&nbsp;<i class='fa fa-plus' aria-hidden='true'></i></button>
                                        <button class='btn btn-warning inputs' style='margin-top:-5px;' name='btnwarning' type='button'  onclick ='javascript:editingrecord(\"saveclose\");'>Save & Close &nbsp;<i class='fa fa-save' aria-hidden='true'></i>&nbsp;&nbsp;<i class='fa fa-close' aria-hidden='true'></i></button>
                                        <button class='btn btn-danger inputs' style='margin-top:-5px;' name='btndanger' type='button'  onclick ='javascript:cancleediting(\"submenulist.php\");'>Close &nbsp;<i class='fa fa-close' aria-hidden='true'></i></button>
                                        </div>
                                        </form> " ;


echo $entrydata;
?>

                  </div>

        </section>
</body>
</html>
<?php
function getmenuname($id){
	global $con;
         $SEL =  "select name from inmenu where id='$id'";
         $RES = mysqli_query($con,$SEL);
         while ($ARR = mysqli_fetch_array($RES)) {
            $menuname = $ARR['name'];
         }
        return $menuname;
}

function GetRoles($roles) {
	global $con;
         $mycontrol = "<div class='divcheckbox'>";
                                      $SQLsub1   = "select lookcode,lookname from in_lookup_head where looktype='ROLES' and lookname<>'YY' order by id";
                                      $SQLResSub1 =  mysqli_query($con,$SQLsub1) or die(mysqli_error()."<br>".$SQLsub1);

                                      if(mysqli_num_rows($SQLResSub1)>=1){
                                        while($loginResultArraySub1   = mysqli_fetch_array($SQLResSub1)){
                                           $selected = "";
                                           if (strpos("-," . $roles.",",",".$loginResultArraySub1[0].",")>0) {     //  Setting the selected item if this matches with the value parameter
                                               $selected = " checked ";
                                           }

                                           $mycontrol .= "<input type='checkbox'  id='chk_A_roles' name='chk_A_roles[]' value='".$loginResultArraySub1[0]."'".$selected."/>&nbsp;&nbsp;" . $loginResultArraySub1[1]. "&nbsp;&nbsp;";

                                        }
                                      }
           $mycontrol .= "</div>";

           return $mycontrol;
}
function GetLastSqeID($tblName){
	global $con;
                 $query = "LOCK TABLES in_sequencer WRITE";
                 mysqli_query($con,$query) or die(mysqli_error()."<br>".$query);
                 $seqSQL = "SELECT LASTNUMBER FROM in_sequencer WHERE TABLENAME='$tblName'";
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
                   boxHeight()
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