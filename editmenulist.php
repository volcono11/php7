<?php
session_start();
//$_SESSION['pr'] = isset($_REQUEST['pr']) ? $_REQUEST['pr'] : '';
require "connection.php";
require "pagingObj.php";

$grid = new MyPHPGrid('frmPage');
$grid->TableName = "tbl_menu";
$grid->formName = "menulist.php";
$grid->SyncSession($grid);
$grid->TableNameChild = "";

$insert = $update = $delete = "false";

if(false !== strpos($_SESSION['pr'],"I")) $insert = "true" ;
if(false !== strpos($_SESSION['pr'],"U")) $update = "true" ;
if(false !== strpos($_SESSION['pr'],"D")) $delete = "true" ;

if($_REQUEST['ID'] != "0") {
             $mode=$_REQUEST['ID'];
             $saveid=$_REQUEST['ID'];

             $SQL = " Select * from tbl_menu where id='".$_REQUEST['ID']."'";
             $SQLRes =  mysqli_query($con,$SQL) or die(mysqli_error()."<br>".$SQL);
              if(mysqli_num_rows($SQLRes)>=1){
                while($loginResultArray   = mysqli_fetch_array($SQLRes)){
                   $id = $loginResultArray['id'];
                   $slno = $loginResultArray['slno'];
                   $menu_name = $loginResultArray['menu_name'];
                   $menu_code = $loginResultArray['menu_code'];
                   $menu_icon = $loginResultArray['menu_icon'];
                   $modulecode = $loginResultArray['modulecode'];
                   $objecttype = $loginResultArray['objecttype'];
                  }
              }
           }else{
             $mode="";
             $saveid = GetLastSqeID("tbl_menu");
             $id="";
             $slno = "";
             $menu_name = "";
             $menu_code = "";
             $menu_icon = $modulecode = $objecttype = "";
}

if(isset($_REQUEST['dr'])=='view'){
   $edit="none";
   $view="inline";
   $title="Viewing Main Menu : $menu_name";
}else if(isset($_REQUEST['dr'])=='edit'){
      $edit="inline";
      $view="none";
      $title="Editing Main Menu : $menu_name";
}else{
      $edit="inline";
      $view="none";
      $title="Adding Main Menu";
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
   $('#txt_A_name').css('border-color', '');
   $('#cmb_A_status').css('border-color', '');
   $('#cmb_A_currency').css('border-color', '');
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
       var txt_A_name=document.getElementById('txt_A_menu_name');
       if(txt_A_name){
          if ((txt_A_name.value==null)||(txt_A_name.value=="")){
               alertify.alert("Enter Menu Name", function () {
               txt_A_name.focus();

          });
             return;
          }
       }

       var parameter =get(document.frmEdit);
       insertfunction(parameter,action)
}
                   var xmlHttp
                   function insertfunction(parameters,action)
                   {
                          //alert(parameters);
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
                                // alertify.alert("Record Saved", function () {
                                 window.location.href='editmenulist.php?dr=edit&ID='+document.getElementById('saveid').value;
                               // });
                               }else if(s1.toString() == s3.toString()){
                               // alertify.alert("Record Updated", function () {
                                window.location.href='editmenulist.php?dr=edit&ID='+document.getElementById('mode').value;

                              // });


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
                                 window.location.href='editmenulist.php?dr=add&ID=0';
                                });
                               }else if(s1.toString() == s3.toString()){
                                alertify.alert("Record Updated", function () {
                                window.location.href='editmenulist.php?dr=add&ID=0';

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
                                window.location.href='menulist.php?ID=0';
                               }else if(s1.toString() == s3.toString()){
                                alertify.alert("Record Updated", function () {
                                window.location.href='menulist.php';

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

                 <a class="pull-left" href="menulist.php?ps=1&pr=<?php echo $_SESSION['pr'];?>" data-toggle="tooltip" data-placement="right" title="Back to Menu Setup"><i class='fa fa-backward'></i></a>
                 <h2 class="title">&nbsp;&nbsp;<?php echo $title; ?></h2>

                 <!--<ol class='breadcrumb'>
                  <li><a href="#"><a href="blank.php" >Menu Setup</a></li>
                  <li><a href="#"><a href="menulist.php?ps=1">Main Menu</a></li>
                  <li class="active"><?echo $title; ?></li>
                 </ol>-->

    </section>
<section class="content" id='content-content-id' style='padding-right:5px;padding-left:5px;margin-top:-10px;' >

                  <div class="nav-tabs-custom" id="nav-tabs-custom-id">
                        <ul class="nav nav-tabs">
                           <li class="active"><a href="#personal" onclick='javascript:loadpage(2);' data-toggle="tab"><i class="fa fa-desktop" aria-hidden="true"></i>&nbsp; Main Menu</a></li>
                           <?php
                           if($_REQUEST['ID'] != "0") {
                           	?>
                           <li><a href="#submenu"   onclick='javascript:loadpage(3);' data-toggle="tab"><i class="fa fa-list" aria-hidden="true"></i>&nbsp; Sub Menu(s)</a></li>
                           <?php }
                           ?>
                        </ul>

                        <div class="tab-content" id='tab-content-id'>
                           <div class="tab-pane active" id="personal">
                             <div class="box-body no-padding" id='box-body-id'>
                                   <div class='table-responsive no-padding'>

<?php
		$mandatory = "<span class='mandatory'>&nbsp;*</span>";

        $entrydata  = " <form name='frmEdit' method='post' id='frmEdit' enctype='multipart/form-data'>
                             <table class='table table-bordered table-condensed table-fixed  table-responsive' style='table-layout:fixed'>
                                                            <tr>
                                                              <td class='dvtCellLabel' style='border: 1px solid #ccc;'>Sl No:</td>
                                                              <td style='width:20%;border: 1px solid #ccc;'><input type='text' class='form-control txt'  onkeypress='return AllowNumeric1(event)'  name='txt_A_slno' id='txt_A_slno'  value='$slno' ></td>
                                                              <td class='dvtCellLabel' style='border: 1px solid #ccc;'>Menu Name $mandatory</td>
                                                              <td style='border: 1px solid #ccc;'><input type='text' class='form-control txt inputs' name='txt_A_menu_name' id='txt_A_menu_name'  value='".$menu_name."' ></td>
                                                              <td class='dvtCellLabel' style='border: 1px solid #ccc;'>Module $mandatory</td>
                                                              <td style='border: 1px solid #ccc;'>
                                                              	".GetModule($modulecode)."
                                                              </td>
                                                            </tr>
                                                            <tr>
                                                            	<td class='dvtCellLabel' style='border: 1px solid #ccc;'>Object Type $mandatory</td>
                                                              <td style='border: 1px solid #ccc;'>
                                                              	".GetObjectType($objecttype)."
                                                              </td>
                                                            	<td class='dvtCellLabel' style='border: 1px solid #ccc;'>Icon Image </td>
                                                              	<td style='border: 1px solid #ccc;'>
                                                              		".GetIcons($menu_icon)."
                                                              	</td>
                                                            </tr>
                                                                <input type='hidden' name='mode' class=textboxcombo id='mode' value='$mode'>
                                                                <input type='hidden' name='modeid' class=textboxcombo id='modeid' value='save'>
                                                                <input type='hidden' name='saveid' class=textboxcombo id='saveid' value='$saveid'>
                                                            
                                                    </table>

                                    </div>
                                    </div>";

                       $entrydata.=  "<div class='box-footer' style='border-top:1px #D2D2D2 solid;'>";
                       if($update == "true" || ($insert=="true" && $_REQUEST['ID']=='0' ))
                       $entrydata.="  <button class='btn btn-success inputs' style='margin-top:-5px;' name='btnsuccess' type='button'  onclick ='javascript:editingrecord(\"save\");'>Save </font>&nbsp;<i class='fa fa-save' aria-hidden='true'></i></button>";
                       if( ($insert=="true" && $_REQUEST['ID']==0) || ($update == "true" && $insert=="true"))
                       $entrydata.="  <button class='btn btn-info inputs' style='margin-top:-5px;' name='btninfo' type='button' onclick ='javascript:editingrecord(\"savenew\");'>Save & New &nbsp; <i class='fa fa-save' aria-hidden='true'></i>&nbsp;&nbsp;<i class='fa fa-plus' aria-hidden='true'></i></button>";
                       if($update == "true" || ($insert=="true" && $_REQUEST['ID']=='0' ))
                        $entrydata.="   <button class='btn btn-warning inputs' style='margin-top:-5px;' name='btnwarning' type='button'  onclick ='javascript:editingrecord(\"saveclose\");'>Save & Close &nbsp;<i class='fa fa-save' aria-hidden='true'></i>&nbsp;&nbsp;<i class='fa fa-close' aria-hidden='true'></i></button>";
                        $entrydata.="  <button class='btn btn-danger inputs' style='margin-top:-5px;' name='btndanger' type='button'  onclick ='javascript:closeediting(\"menulist.php?ps=1&pr=".$_SESSION['pr']."\");'>Close &nbsp;<i class='fa fa-close' aria-hidden='true'></i></button>
                                        </div>";
                  $entrydata.=" </form> " ;


      echo $entrydata;
?>

                  </div>
                  
                  <div class="tab-pane" id="submenu">
                  	<iframe id="frame3" name="frame3" scrolling="no" onload='this.width=screen.width;this.height=screen.height;'  frameborder="0" style="position: relative; width: 100%; "></iframe>
                 </div>

        </section>
</body>
</html>
<?php
function GetIcons($menu_icon){
	
	global $con;
	$CMB = " <div class='font-awesome'><select name='cmb_A_menu_icon'  id='cmb_A_menu_icon' class='form-control fa'>";
	$CMB .= "<option>Select</option>";
	$seqSQL = "select iconcode ,iconname from tbl_icons order by 2";
	$result=mysqli_query($con,$seqSQL) or die(mysqli_error()."<br>".$seqSQL);
	while($ARR=mysqli_fetch_array($result)){
		$SEL = "";
		if(strtoupper($menu_icon)== strtoupper($ARR['iconname'])){ $SEL =  "SELECTED";}
               $CMB .= "<option class='fa' value='".$ARR['iconname']."' $SEL >".$ARR['iconcode']."- ".$ARR['iconname']."</option>";
    }
    $CMB .= "</select></div>";

    return $CMB;
}
function GetObjectType($modulecode){
	global $con;
	$CMB = " <select name='cmb_A_objecttype'  id='cmb_A_objecttype' class='form-control' >";
	
	$seqSQL = "select lookcode ,lookname from in_lookup where lookname<>'XX' and looktype='Object Type' order by slno";
	$result=mysqli_query($con,$seqSQL) or die(mysqli_error()."<br>".$seqSQL);
	while($ARR=mysqli_fetch_array($result)){
		$SEL = "";
		if(strtoupper($modulecode)== strtoupper($ARR['lookcode'])){ $SEL =  "SELECTED";}
               $CMB .= "<option value='".strtoupper($ARR['lookcode'])."' $SEL >".$ARR['lookname']."</option>";
    }
    $CMB .= "</select>";
    return $CMB;
}
function GetModule($modulecode){
	global $con;
	$CMB = " <select name='cmb_A_modulecode'  id='cmb_A_modulecode' class='form-control' >";
	
	$seqSQL = "select lookcode ,lookname from in_lookup where lookname<>'XX' and looktype='Modules' order by slno";
	$result=mysqli_query($con,$seqSQL) or die(mysqli_error()."<br>".$seqSQL);
	while($ARR=mysqli_fetch_array($result)){
		$SEL = "";
		if(strtoupper($modulecode)== strtoupper($ARR['lookcode'])){ $SEL =  "SELECTED";}
               $CMB .= "<option value='".strtoupper($ARR['lookcode'])."' $SEL >".$ARR['lookname']."</option>";
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
       document.frmEdit.action='editmenulist.php?dr=edit&ID='+document.getElementById('mode').value;
       document.frmEdit.submit();
   }
   if(i==3){
   var frame= document.getElementById('frame3');
   frame.src='addsubmenus.php?PARENTID='+document.getElementById('mode').value;
   frame.load();
   }
}
</script>
