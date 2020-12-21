<?php
@session_start();
require "connection.php";
require "pagingObj.php";
include "functions_workflow.php";
$WF = new WorkFlow($_SESSION['objectid']);
$pagerights = $WF->loadPagerights();
$_SESSION['pr'] = isset($pagerights) ? $pagerights : '';
//echo $_SESSION['pr'];
$insert = $update = $delete = "false";
$insert = $update = $delete = "false";

$grid = new MyPHPGrid('frmPage');
$grid->TableName = "tbl_objectmaster";
$grid->formName = "objectlist.php";
$grid->SyncSession($grid);
$grid->TableNameChild = "";

if(false !== strpos($_SESSION['pr'],"I")) $insert = "true" ;
if(false !== strpos($_SESSION['pr'],"U")) $update = "true" ;
if(false !== strpos($_SESSION['pr'],"D")) $delete = "true" ;

if($_REQUEST['ID'] != "0") {
             $mode=$_REQUEST['ID'];

             $SQL = " Select * from tbl_objectmaster where id='".$_REQUEST['ID']."'";
             $SQLRes =  mysqli_query($con,$SQL) or die(mysqli_error()."<br>".$SQL);
              if(mysqli_num_rows($SQLRes)>=1){
                while($loginResultArray   = mysqli_fetch_array($SQLRes)){
                   $saveid =  $loginResultArray['id'];
                   $createdon = $loginResultArray['createdon'];
                   $author =  $loginResultArray['author'];
                   $objecttype =  $loginResultArray['objecttype'];
                   $showinmenu =  $loginResultArray['showinmenu'];
                   $objectname =    $loginResultArray['objectname'];
                   $notes =    $loginResultArray['notes'];
                   $menuname =    $loginResultArray['menuname'];
                   $url =    $loginResultArray['url'];
                   $description =    $loginResultArray['description'];
                   $iconname =    $loginResultArray['iconname'];
                   $lastupdatedon = date('Y-m-d H:i:s');
                   
                   if($showinmenu == "2001"){
						$tr1 ="table-row";
                        $tr2 = "table-row";
				   }
				   else{
				   		$tr1 ="none";
                        $tr2 = "none";
				   }
                  }
              }
           }else{
             $mode="";
             $saveid =GetLastSqeID('tbl_objectmaster');
             $createdon =  $lastupdatedon = date('Y-m-d H:i:s');
             $author = $objecttype = $objectname = $showinmenu = $notes =  $menuname= $description = $url = $iconname = "";
             $tr1 ="none";
             $tr2 = "none";
}
if(isset($_REQUEST['dr'])=='view'){
   $edit="none";
   $view="inline";
   $title="Viewing Object : $objectname";
}else if(isset($_REQUEST['dr'])=='edit'){
      $edit="inline";
      $view="none";
      $title="Editing Object : $objectname";
}else{
      $edit="inline";
      $view="none";
      $title="Adding New Object";
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
function getShowinMenuFields(cattype){
		if(cattype == "2001"){
			document.getElementById('tr1').style.display = 'table-row';
            document.getElementById('tr2').style.display = 'table-row';	
            document.getElementById('txt_A_menuname').value = '';	
            document.getElementById('txt_A_url').value = '';	
            document.getElementById('txt_A_iconname').value = '';	
            document.getElementById('txt_A_description').value = '';	
		}
		else{
			document.getElementById('tr1').style.display = 'none';
            document.getElementById('tr2').style.display = 'none';
		}
}
function editingrecord(action)
{

       var cmb_A_author=document.getElementById('cmb_A_author');
       if(cmb_A_author){
          if ((cmb_A_author.value==null)||(cmb_A_author.value=="")){
               alertify.alert("Select Author", function () {
               cmb_A_author.focus();

          });
             return;
          }
       }
       
       var cmb_A_author=document.getElementById('cmb_A_objecttype');
       if(cmb_A_author){
          if ((cmb_A_author.value==null)||(cmb_A_author.value=="")){
               alertify.alert("Select Object Type", function () {
               cmb_A_author.focus();

          });
             return;
          }
       }
       
       
       var cmb_A_author=document.getElementById('txt_A_objectname');
       if(cmb_A_author){
          if ((cmb_A_author.value==null)||(cmb_A_author.value=="")){
               alertify.alert("Enter Object Name", function () {
               cmb_A_author.focus();

          });
             return;
          }
       }
       
       var cmb_A_showinmenu=document.getElementById('cmb_A_showinmenu');
       if(cmb_A_showinmenu){
       	  if ((cmb_A_showinmenu.value==null)||(cmb_A_showinmenu.value=="")){
               alertify.alert("Select show in Menu", function () {
               cmb_A_showinmenu.focus();

          });
             return;
          }
          if(cmb_A_showinmenu.value == "2001"){
          	
			   var cmb_A_author=document.getElementById('txt_A_menuname');
		       if(cmb_A_author){
		          if ((cmb_A_author.value==null)||(cmb_A_author.value=="")){
		               alertify.alert("Enter Menu Name", function () {
		               cmb_A_author.focus();

		          });
		             return;
		          }
		       }
		       var cmb_A_author=document.getElementById('txt_A_url');
		       if(cmb_A_author){
		          if ((cmb_A_author.value==null)||(cmb_A_author.value=="")){
		               alertify.alert("Enter Menu Url", function () {
		               cmb_A_author.focus();

		          });
		             return;
		          }
		       }
		       var cmb_A_author=document.getElementById('txt_A_iconname');
		       if(cmb_A_author){
		          if ((cmb_A_author.value==null)||(cmb_A_author.value=="")){
		               alertify.alert("Enter Icon Name", function () {
		               cmb_A_author.focus();

		          });
		             return;
		          }
		       }
		  	
		  	
		  }
       }
       
       
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
                                 window.location.href='editobjectlist.php?dr=edit&ID='+document.getElementById('saveid').value;
                                });
                               }else if(s1.toString() == s3.toString()){
                               	
                               	
 							    alertify.alert("Record Updated", function () {
                                window.location.href='editobjectlist.php?dr=edit&ID='+document.getElementById('mode').value;

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
                                 window.location.href='editobjectlist.php?dr=add&ID=0';
                                });
                               }else if(s1.toString() == s3.toString()){
                                alertify.alert("Record Updated", function () {
                                window.location.href='editobjectlist.php?dr=add&ID=0';

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
                                window.location.href='objectlist.php?ID=0';
                               }else if(s1.toString() == s3.toString()){
                                alertify.alert("Record Updated", function () {
                                window.location.href='objectlist.php';

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

                 <a class="pull-left" href="objectlist.php?objectid=<?php echo $_SESSION['objectid']; ?>&txtsearch=<?php echo $_SESSION['txtsearch']; ?>" data-toggle="tooltip" data-placement="right" title="Back to Company Setup"><i class='fa fa-backward'></i></a>
                 <h2 class="title">&nbsp;&nbsp;<?php echo $title; ?></h2>

                 <!--<ol class='breadcrumb'>
                  <li><a href="#"><a href="blank.php" >Admin Setup</a></li>
                  <li><a href="#"><a href="objectlist.php?ps=1">Object</a></li>
                  <li class="active"><?php echo $title; ?></li>
                 </ol>-->

         </section>

                <section class="content" id='content-content-id' style='padding-right:5px;padding-left:5px;margin-top:-10px;' >

                  <div class="nav-tabs-custom" id="nav-tabs-custom-id">
                        <ul class="nav nav-tabs">

                           <li class="active"><a href="#personal" onclick='javascript:loadpage(2);' data-toggle="tab"><i class="fa fa-desktop" aria-hidden="true"></i>&nbsp; Object</a></li>
                           <?php 
                           if($_REQUEST['ID']!='0'){
                           ?>
                           <li><a href="#revision" onclick='javascript:loadpage(3);' data-toggle="tab"><i class="fa fa-history" aria-hidden="true"></i>&nbsp; History</a></li>
                           <?php
                              if($objecttype=='4003'){
                            ?>
                           <li><a href="#db" onclick='javascript:loadpage(4);' data-toggle="tab"><i class="fa fa-table" aria-hidden="true"></i>&nbsp; Data Fileds</a></li>
                           <?php
							  }
                           }
                           ?>
                       </ul>

                        <div class="tab-content" id='tab-content-id'>
                          <div class="tab-pane active" id="personal">
                              <div class="box-body no-padding" id='box-body-id'>
                                   <div class='table-responsive no-padding'>
<?php 
		$mandatory = "<span class='mandatory'>&nbsp;*</span>";

        $entrydata = "<form name='frmEdit' method='post' id='frmEdit' enctype='multipart/form-data'>
                        <table class='table table-bordered table-condensed table-fixed  table-responsive' style='table-layout:fixed;'>
                        <tr >
                          <td class='CellLabel'>Author $mandatory</td>
                          <td class='CellData'>".GetAuthor($author)."</td>
                          <td class='CellLabel' >Object Type $mandatory</td>
                          <td class='CellData'>".GetObjectType($objecttype)."</td>
                          <td class='CellLabel' >Object Name $mandatory</td>
                          <td class='CellData'><input type='text' class='form-control txt' name='txt_A_objectname' id='txt_A_objectname' value='$objectname' ></td>
                        </tr>
                        <tr>
                        	<td class='CellLabel' >Notes</td>
                          	<td  colspan=3 class='CellData'><input type='text' class='form-control txt inputs' name='txt_A_notes' id='txt_A_notes' value='$notes' placeholder='Enter Note'></td>
                          	<td class='CellLabel' >Show in Menu $mandatory</td>
                          <td class='CellData'>".GetShowinMenu($showinmenu)."</td>
                        </tr>
                        <tr id='tr1' style='display:$tr1;'>
                        	<td class='CellLabel' >Menu Name $mandatory</td>
                          	<td class='CellData'><input type='text' class='form-control' name='txt_A_menuname' id='txt_A_menuname' value='$menuname' placeholder='Enter Menu Name'></td>
                          	<td class='CellLabel' >Url $mandatory</td>
                          <td class='CellData'><input type='text' class='form-control txt inputs' name='txt_A_url' id='txt_A_url' value='$url' placeholder='Enter Url'></td>
                          <td class='CellLabel' >Icon $mandatory</td>
                          <td class='CellData'><input type='text' class='form-control txt inputs' name='txt_A_iconname' id='txt_A_iconname' value='$iconname' placeholder='Enter Icon Name'></td>
                        </tr>
                        <tr id='tr2' style='display:$tr2;'>
                        	<td class='CellLabel' >Description</td>
                          	<td colspan=5 class='CellData'><input type='text' class='form-control txt inputs' name='txt_A_description' id='txt_A_description' value='$description' placeholder='Enter Desc.'></td>
						</tr>
                            <input type='hidden' name='mode' class=textboxcombo id='mode' value='$mode'>
                            <input type='hidden' name='txt_A_createdon' id='txt_A_createdon' value='$createdon'>
                            <input type='hidden' name='txt_A_lastupdatedon' id='txt_A_lastupdatedon' value='$lastupdatedon'>
                            <input type='hidden' name='modeid' class=textboxcombo id='modeid' value='save'>
                            <input type='hidden' name='saveid' class=textboxcombo id='saveid' value='$saveid'>
                        
                        </table>

                  </div>
                 </div>";

                       $entrydata.=  "<div class='box-footer' style='border-top:1px #D2D2D2 solid;'>";
                       if($update == "true" || $insert=="true")
                       $entrydata.="  <button class='btn btn-success inputs' style='margin-top:-5px;' name='btnsuccess' type='button'  onclick ='javascript:editingrecord(\"save\");'>Save </font>&nbsp;<i class='fa fa-save' aria-hidden='true'></i></button>";
                       if($insert=="true")
                       $entrydata.="  <button class='btn btn-info inputs' style='margin-top:-5px;' name='btninfo' type='button' onclick ='javascript:editingrecord(\"savenew\");'>Save & New &nbsp; <i class='fa fa-save' aria-hidden='true'></i>&nbsp;&nbsp;<i class='fa fa-plus' aria-hidden='true'></i></button>";
                       if($update == "true" || $insert=="true")
                        $entrydata.="   <button class='btn btn-warning inputs' style='margin-top:-5px;' name='btnwarning' type='button'  onclick ='javascript:editingrecord(\"saveclose\");'>Save & Close &nbsp;<i class='fa fa-save' aria-hidden='true'></i>&nbsp;&nbsp;<i class='fa fa-close' aria-hidden='true'></i></button>";
                        $entrydata.="  <button class='btn btn-danger inputs' style='margin-top:-5px;' name='btndanger' type='button'  onclick ='javascript:closeediting(\"objectlist.php?ps=1&objectid=".$_SESSION['objectid']."\");'>Close &nbsp;<i class='fa fa-close' aria-hidden='true'></i></button>";
                        $entrydata.="  <button class='btn btn-primary inputs' style='margin-top:-5px;' name='btndanger' type='button'  onclick ='javascript:closeediting(\"objectlist.php?ps=1&objectid=".$_SESSION['objectid']."&frmPage_rowcount=".$_SESSION['frmPage_rowcount']."&txtsearch=".$_SESSION['txtsearch']."&frmPage_startrow=".$_SESSION['frmPage_startrow']."\");'>Back &nbsp;<i class='fa fa-backward' aria-hidden='true'></i></button>";
                         $entrydata.=" </div>";
                        $entrydata.= "</form>  ";
echo  $entrydata;
?>


                  </div>
    <div class="tab-pane" id="revision">
		<iframe id="frame3" name="frame3" scrolling="no" onload='this.width=screen.width;this.height=screen.height;'  frameborder="0" style="position: relative; width: 100%; "></iframe>
	</div> 
	<div class="tab-pane" id="db">
		<iframe id="frame4" name="frame4" scrolling="no" onload='this.width=screen.width;this.height=screen.height;'  frameborder="0" style="position: relative; width: 100%; "></iframe>
	</div>

        </section>
</body>
</html>
<?php
function GetAuthor($roles){
	global $con;
    $CMB = " <select name='cmb_A_author'  id='cmb_A_author' class='form-control select'>";    
    $CMB .= "<option value=''>Select</option>";
	$SEL =  "Select lookcode,lookname from in_lookup where looktype='DEVELOPER' and lookname<>'XX'";
	$RES = mysqli_query($con,$SEL);
	while ($ARR = mysqli_fetch_array($RES)) {
	   $SEL = "";
	   if($roles == $ARR['lookcode']){ $SEL =  "SELECTED";}
	   $CMB .= "<option value='".trim($ARR['lookcode'])."' $SEL >".trim($ARR['lookname'])."</option>";
	}
	$CMB .= "</select>";
	return $CMB;
}

function GetShowinMenu($showinmenu){
	global $con;
    $CMB = " <select name='cmb_A_showinmenu'  id='cmb_A_showinmenu' class='form-control select' onChange='getShowinMenuFields(this.value)'>";    
    $CMB .= "<option value=''>Select</option>";
	$SEL =  "Select lookcode,lookname from in_lookup where looktype='YESNO' and lookname<>'XX'";
	$RES = mysqli_query($con,$SEL);
	while ($ARR = mysqli_fetch_array($RES)) {
	   $SEL = "";
	   if($showinmenu == $ARR['lookcode']){ $SEL =  "SELECTED";}
	   $CMB .= "<option value='".trim($ARR['lookcode'])."' $SEL >".trim($ARR['lookname'])."</option>";
	}
	$CMB .= "</select>";
	return $CMB;
}
function GetObjectType($objecttype){
	global $con;
    $CMB = " <select name='cmb_A_objecttype'  id='cmb_A_objecttype' class='form-control select'>";    
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
function loadpage(i){
   if(i==2){
       document.frmEdit.action='editobjectlist.php?dr=edit&ID='+document.getElementById('mode').value;
       document.frmEdit.submit();
   }
   if(i==3){
   var frame= document.getElementById('frame3');
   frame.src='object_history.php?PARENTID=<?php echo $_REQUEST['ID']; ?>';
   frame.load();
   }
   if(i==4){
   var frame= document.getElementById('frame4');
   frame.src='object_dbfileds.php?PARENTID=<?php echo $_REQUEST['ID']; ?>';
   frame.load();
   }
}                  
</script>