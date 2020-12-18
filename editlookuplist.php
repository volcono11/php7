<?php
session_start();
//$_SESSION['pr'] = isset($_REQUEST['pr']) ? $_REQUEST['pr'] : '';

require "connection.php";
require "pagingObj.php";
require "functions_workflow.php";

$insert = $update = $delete = "false";

if(false !== strpos($_SESSION['pr'],"I")) $insert = "true" ;
if(false !== strpos($_SESSION['pr'],"U")) $update = "true" ;
if(false !== strpos($_SESSION['pr'],"D")) $delete = "true" ;



if(isset($_REQUEST['POST'])=='POST'){
     $sql1 = "update in_lookup set posted='YES' where id='".$_REQUEST['POSTID']."'";
     mysqli_query($con,$sql1) or die(mysqli_error()."PA-115<br>".$sql1);
}
if($_REQUEST['ID'] != "0") {
             $mode=$_REQUEST['ID'];

             $SQL = " Select * from in_lookup where id='".$_REQUEST['ID']."'";
             $SQLRes =  mysqli_query($con,$SQL) or die(mysqli_error()."<br>".$SQL);
              if(mysqli_num_rows($SQLRes)>=1){
                while($loginResultArray   = mysqli_fetch_array($SQLRes)){
                   $saveid =$loginResultArray['id'];
                   $slno = $loginResultArray['slno'];
                   $lookcode = $loginResultArray['lookcode'];
                   $lookname = $loginResultArray['lookname'];
                   $looktype=  $loginResultArray['looktype'];
                   $shortname=  $loginResultArray['shortname'];
                   $posted=  $loginResultArray['posted'];
                  }
              }
}else{
             $mode="";
             $saveid = GetLastSqeID("in_lookup");
             $looktype= $_REQUEST['cmb_lookuplist'];
             $SQL = " Select lookcode from in_lookup where looktype='".$looktype."' and lookname='XX'";
             $SQLRes =  mysqli_query($con,$SQL) or die(mysqli_error()."<br>".$SQL);
             while($loginResultArray   = mysqli_fetch_array($SQLRes)){
                   $looktypeid =$loginResultArray['lookcode'];
             }
             $lookcode =GetLastSqeIDlookcode('lookcode',$looktype,$looktypeid);
             $lookname = $shortname= $posted = $slno = "";
}
if(isset($_REQUEST['dr'])=='view'){
      $edit="none";
      $view="inline";
      $title="Viewing Lookup Value : $lookname";
}else if(isset($_REQUEST['dr'])=='edit'){
      $edit="inline";
      $view="none";
      $title="Editing Lookup Value : $lookname";
}else{
      $edit="inline";
      $view="none";
      $title="Adding New Lookup Value";
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
function borderchange(){
   $('#txt_A_slno').css('border-color', '');
   $('#txt_A_lookcode').css('border-color', '');
   $('#txt_A_lookname').css('border-color', '');

}
function PostRecord(postid){

alertify.confirm("Are you sure you want to post ?", function (e) {
         if (e) {
           document.frmEdit.action='editlookuplist.php?POST=POST&POSTID='+postid+'&ID='+document.getElementById('mode').value;
           document.frmEdit.submit();
         } else {
            return;
         }

       });

}
function editingrecord(action)
{

       var txt_A_slno=document.getElementById('txt_A_slno');
       if(txt_A_slno){
          if ((txt_A_slno.value==null)||(txt_A_slno.value=="")){
               alertify.alert("Enter Slno", function () {
               txt_A_slno.focus();

          });
             return;
          }
       }
       var txt_A_lookcode=document.getElementById('txt_A_lookcode');
       if(txt_A_lookcode){
          if ((txt_A_lookcode.value==null)||(txt_A_lookcode.value=="")){
               alertify.alert("Enter Look Code", function () {
               txt_A_lookcode.focus();

          });
             return;
          }
       }
       var txt_A_lookname=document.getElementById('txt_A_lookname');
       if(txt_A_lookname){
          if ((txt_A_lookname.value==null)||(txt_A_lookname.value=="")){
               alertify.alert("Enter Look Name", function () {
               txt_A_lookname.focus();

          });
             return;
          }
       }
       document.getElementById('btnsuccess').disabled=true;
       document.getElementById('btninfo').disabled=true;
       document.getElementById('btnwarning').disabled=true;
       document.getElementById('btndanger').disabled=true;

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
                                 alertify.alert("Record Saved");
                                 window.location.href='editlookuplist.php?dr=edit&cmb_lookuplist='+document.getElementById('txt_A_looktype').value+'&ID='+document.getElementById('saveid').value;

                               }else if(s1.toString() == s3.toString()){
                                alertify.alert("Record Updated");
                                window.location.href='editlookuplist.php?cmb_lookuplist='+document.getElementById('txt_A_looktype').value+'&dr=edit&ID='+document.getElementById('mode').value;
                                document.getElementById('txt_A_lookcode').focus();

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
                                 window.location.href='editlookuplist.php?cmb_lookuplist='+document.getElementById('txt_A_looktype').value+'&dr=add&ID=0';

                               }else if(s1.toString() == s3.toString()){
                                alertify.alert("Record Updated");
                                window.location.href='editlookuplist.php?cmb_lookuplist='+document.getElementById('txt_A_looktype').value+'&dr=add&ID=0';


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
                                window.location.href='lookuplist.php?ID=0';
                               }else if(s1.toString() == s3.toString()){
                                alertify.alert("Record Updated");
                                window.location.href='lookuplist.php?ID='+document.getElementById('mode').value;

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

                 <a class="pull-left" href="lookuplist.php?ps=1&pr=<?php echo $_SESSION['pr'];?>" data-toggle="tooltip" data-placement="right" title="Back to lookup"><i class='fa fa-backward'></i></a>
                 <h2 class="title">&nbsp;&nbsp;<?php echo $title; ?></h2>

                 <ol class='breadcrumb'>
                  <li><a href="#"><a href="blank.php" >Admin Setup</a></li>
                  <li><a href="#"><a href="lookuplist.php?ps=1">Lookup Value</a></li>
                  <li class="active"><?php echo $title; ?></li>
                 </ol>

         </section>

                <section class="content" id='content-content-id' style='padding-right:5px;padding-left:5px;margin-top:-10px;' >

                  <div class="nav-tabs-custom" id="nav-tabs-custom-id">
                        <ul class="nav nav-tabs">

                           <li class="active"><a href="#personal" data-toggle="tab"><i class="fa fa-desktop" aria-hidden="true"></i>&nbsp; Lookup Value</a></li>
                       </ul>

                        <div class="tab-content" id='tab-content-id'>
                          <div class="tab-pane active" id="personal">
                              <div class="box-body" id='box-body-id'>
                                   <div class='table-responsive'>
<?php
        $postico = "";
      if($posted!="YES"){
           $saveico="<button class='btn btn-success inputs' style='margin-top:-5px;' id='btnsuccess' name='btnsuccess' type='button'  onclick ='javascript:editingrecord(\"save\");'>Save </font>&nbsp;<i class='fa fa-save' aria-hidden='true'></i></button>
                     <button class='btn btn-info inputs' style='margin-top:-5px;' id='btninfo' name='btninfo' type='button' onclick ='javascript:editingrecord(\"savenew\");'>Save & New &nbsp; <i class='fa fa-save' aria-hidden='true'></i>&nbsp;&nbsp;<i class='fa fa-plus' aria-hidden='true'></i></button>
                     <button class='btn btn-warning inputs' style='margin-top:-5px;' id='btnwarning' name='btnwarning' type='button'  onclick ='javascript:editingrecord(\"saveclose\");'>Save & Close &nbsp;<i class='fa fa-save' aria-hidden='true'></i>&nbsp;&nbsp;<i class='fa fa-close' aria-hidden='true'></i></button>
                     <button class='btn btn-danger inputs' style='margin-top:-5px;' id='btndanger' name='btndanger' type='button'  onclick ='javascript:closeediting(\"lookuplist.php\");'>Close &nbsp;<i class='fa fa-close' aria-hidden='true'></i></button>";
           if($_REQUEST['ID']!="0"){
              $postico="<button class='btn btn-info inputs' style='margin-top:-5px;' id='btninfo' name='btninfo' type='button' onclick ='javascript:PostRecord(".$_REQUEST['ID'].");'>Post Record &nbsp; <i class='fa fa-lock' aria-hidden='true'></i></button>";
           }
      }else{
           $saveico="<button class='btn btn-danger inputs' style='margin-top:-5px;' id='btndanger' name='btndanger' type='button'  onclick ='javascript:closeediting(\"lookuplist.php?ps=1&pr=".$_SESSION['pr']."\");'>Close &nbsp;<i class='fa fa-close' aria-hidden='true'></i></button>";
      }


       $entrydata = "<form name='frmEdit' method='post' id='frmEdit' enctype='multipart/form-data'>

                                  <table class='table table-bordered table-condensed table-fixed  table-responsive' style='table-layout:fixed'>
                                                            <tr>
                                                               <td class='dvtCellLabel' style='border: 1px solid #ccc;'>Sl No:<span class='mandatory'>&nbsp;*</span></td>
                                                              <td style='border: 1px solid #ccc;'><input type='text' class='form-control txt inputs' name='txt_A_slno' onkeypress='return AllowNumeric1(event);' id='txt_A_slno'  value='$slno'></td>
                                                              <td class='dvtCellLabel' style='border: 1px solid #ccc;'>Look Name:<span class='mandatory'>&nbsp;*</span></td>
                                                              <td style='border: 1px solid #ccc;'><input type='text' class='form-control txt inputs'  name='txt_A_lookname' id='txt_A_lookname'  value='$lookname' >
                                                              <!-- <td class='dvtCellLabel' style='border: 1px solid #ccc;'>Short Name:</td>
                                                              <td style='border: 1px solid #ccc;'><input type='text' class='form-control txt inputs'  name='txt_A_shortname' id='txt_A_shortname'  value='$shortname' > -->

                                                               <input type='hidden' class='form-control txt inputs' readonly   name='txt_A_lookcode' id='txt_A_lookcode'  value='$lookcode'>
                                                               <input type='hidden' name='mode' class=textboxcombo id='mode' value='$mode'>
                                                               <input type='hidden' name='modeid' class=textboxcombo id='modeid' value='save'>
                                                               <input type='hidden' name='saveid' class=textboxcombo id='saveid' value='$saveid'>
                                                               <input type='hidden' name='txt_A_looktype' id='txt_A_looktype' value='".$looktype."'>
                                                              </td>
                                                             </tr>
                                                            </table>

                                             </div>
                                             </div>

                                        <div class='box-footer' style='border-top:1px #D2D2D2 solid;'>
                                          $saveico $postico
                                        </div>
                                        </form> ";

          echo  $entrydata;
?>


                  </div>

        </section>
</body>
</html>
<?php
function GetProjectName($_selprojectname) {
//echo $relation1."tyty";
global $con;
                     $CMB = "
                                    <select  name='cmb_A_looktype'  id='cmb_A_looktype'>
                                    <option>Select</option>";
         $SEL =  "select looktype from in_lookup order by looktype";
         $RES = mysqli_query($con,$SEL);
         while ($ARR = mysqli_fetch_array($RES)) {
            $SEL = "";
            if(strtoupper($_selprojectname) == strtoupper($ARR['looktype'])){ $SEL =  "SELECTED";}
            $CMB .= "<option value='".$ARR['looktype']."'  $SEL>".$ARR['looktype']."</option>";

         }
                      $CMB .= " </select> ";
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
function GetLastSqeIDlookcode($tblName,$looktype,$looktypeid){
	global $con;

                  $SQL = "Select id,lookcode from in_lookup WHERE looktype='".$looktype."' and lookname<>'XX'";
                  $SQLRes =  mysqli_query($con,$SQL) or die(mysqli_error()."<br>".$SQL);
                  if(mysqli_num_rows($SQLRes)==0){
                       $loginResultArray   = mysqli_fetch_array($SQLRes);
                       $loginResultArray['lookcode'];
                       $catgencode = $looktypeid."001";
                  }else{
                        $SQL1 = "Select max(lookcode) as count from in_lookup WHERE looktype='".$looktype."' and lookname<>'XX'";
                        $SQLRes1 =  mysqli_query($con,$SQL1) or die(mysqli_error()."<br>".$SQL1);
                        if(mysqli_num_rows($SQLRes1)>=1){
                           while($loginResultArray1   = mysqli_fetch_array($SQLRes1)){
                             $catgencode = $loginResultArray1['count']+1;
                           }
                        }
                  }


                  return $catgencode;
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
<script type='text/javascript'>

function loadpage(i){
   if(i==2){
       document.frmEdit.action='editlocation.php?ID='+document.getElementById('mode').value;
       document.frmEdit.submit();
   }
   if(i==3){
   var frame= document.getElementById('frame3');
   frame.src='emp_documents1.php?entitytype=Company&ID='+<?php echo $_REQUEST['ID']; ?>;
   frame.load();
   }
}

</script>