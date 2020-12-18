<?php
session_start();
//$_SESSION['pr'] = isset($_REQUEST['pr']) ? $_REQUEST['pr'] : '';
require "connection.php";
require "pagingObj.php";

$grid = new MyPHPGrid('frmPage');
$grid->TableName = "tbl_usergroup";
$grid->formName = "userprivilegessetup.php";
$grid->SyncSession($grid);
$grid->TableNameChild = "";

$insert = $update = $delete = "false";

if(false !== strpos($_SESSION['pr'],"I")) $insert = "true" ;
if(false !== strpos($_SESSION['pr'],"U")) $update = "true" ;
if(false !== strpos($_SESSION['pr'],"D")) $delete = "true" ;

if(isset($_REQUEST['DEL']) == "DELETE" && isset($_REQUEST['CHILDID'])!=""){
	$DSQL = " delete from tbl_menusetup where id='".$_REQUEST['CHILDID']."' or parentid=(select menucode from tbl_menusetup where id='".$_REQUEST['CHILDID']."') and tbl_menusetup.usergroupid='".$_REQUEST['ID']."'";
    mysqli_query($con,$DSQL) or die(mysqli_error()."<br>".$DSQL);
}

if($_REQUEST['ID'] != "0") {
             $mode=$_REQUEST['ID'];
             $saveid=$_REQUEST['ID'];

             $SQL = " Select * from tbl_usergroup where id='".$_REQUEST['ID']."'";
             $SQLRes =  mysqli_query($con,$SQL) or die(mysqli_error()."<br>".$SQL);
              if(mysqli_num_rows($SQLRes)>=1){
                while($loginResultArray   = mysqli_fetch_array($SQLRes)){
                   $id = $loginResultArray['id'];
                   $slno = $loginResultArray['slno'];
                   $rolecode = $loginResultArray['usergroup'];
                   $remarks = $loginResultArray['remarks'];
                   $menucode ="";
                   
                   $child_sql = "select * from tbl_menusetup where usergroupid='$mode'";
                   $child_re =  mysqli_query($con,$child_sql) or die(mysqli_error()."<br>".$child_sql);
                   if(mysqli_num_rows($child_re)>=1){
			            while($child_arr   = mysqli_fetch_array($child_re)){
			            	$menucode .= $child_arr['menucode'].",";
						}
						$menucode = substr($menucode,0,strlen($menucode)-1) ;
					}
                   
                  }
              }
           }else{
             $mode="";
             $saveid = GetLastSqeID("tbl_usergroup");
             $id="";
             $slno = "";
             $remarks = $rolecode = $menucode ="";
}

if(isset($_REQUEST['dr'])=='view'){
   $edit="none";
   $view="inline";
   $title="Viewing Main Menu :".GetRoleName($rolecode);
}else if(isset($_REQUEST['dr'])=='edit'){
      $edit="inline";
      $view="none";
      $title="Editing Main Menu : ".GetRoleName($rolecode);
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
       /*var txt_A_name=document.getElementById('cmb_A_menucode');
       if(txt_A_name){
          if ((txt_A_name.value==null)||(txt_A_name.value=="")){
               alertify.alert("Select Menu", function () {
               txt_A_name.focus();

          });
             return;
          }
       }*/
       
       var txt_A_name=document.getElementById('txt_A_rolecode');
       if(txt_A_name){
          if ((txt_A_name.value==null)||(txt_A_name.value=="")){
               alertify.alert("Enter User Grp", function () {
               txt_A_name.focus();

          });
             return;
          }
       }
       
       /*var txt_A_module=document.getElementById('cmb_A_modulecode');
       if(txt_A_module){
          if ((txt_A_module.value=="Select")||(txt_A_module.value=="")){
               alertify.alert("Select Module", function () {
               txt_A_module.focus();

          });
             return;
          }
       }*/

        chks = document.getElementsByName('menulist[]');
               
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
		       alertify.alert("Select Main Menus", function () {
		       menulist.focus();

		  });
		     return;
		  }
		}
	   //menus= $('#menulist').val();
	   menus =menus.slice(0,-1)	;
       var parameter =get(document.frmEdit)+'&menus='+menus; 
       //alert(parameter);
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
             window.location.href='edituserprivilegessetup.php?dr=edit&ID='+document.getElementById('saveid').value;
           // });
           }else if(s1.toString() == s3.toString()){
           // alertify.alert("Record Updated", function () {
            window.location.href='edituserprivilegessetup.php?dr=edit&ID='+document.getElementById('mode').value;

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
             window.location.href='edituserprivilegessetup.php?dr=add&ID=0';
            });
           }else if(s1.toString() == s3.toString()){
            alertify.alert("Record Updated", function () {
            window.location.href='edituserprivilegessetup.php?dr=add&ID=0';

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
            window.location.href='userprivilegessetup.php?ID=0';
           }else if(s1.toString() == s3.toString()){
            alertify.alert("Record Updated", function () {
            window.location.href='userprivilegessetup.php';

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
function getMenus(cattype){
      xmlHttp=GetXmlHttpObject()
      if (xmlHttp==null)
      {
                 alert ("Browser does not support HTTP Request")
                 return
      }

      var rolecode= document.getElementById('cmb_A_rolecode').value;
      var url="combofunctions_setup.php?level=addmenu_checkbox&roleccode="+rolecode;
      xmlHttp.onreadystatechange=stateChangedcombo_1
      xmlHttp.open("POST",url,true)
      xmlHttp.send(null)
}
function stateChangedcombo_1(){
       if (xmlHttp.readyState==4 || xmlHttp.readyState=="complete")
       {
             var s1 = trim(xmlHttp.responseText); 
             document.getElementById('divcheckbox').innerHTML=s1;
       }
}  
function deleteChildrecord(childid){
        alertify.confirm("Are you sure you want to delete ?", function (e) {
         if (e) {
           document.frmEdit.action='edituserprivilegessetup.php?ID='+document.getElementById('mode').value+'&DEL=DELETE&CHILDID='+childid;
           document.frmEdit.submit();
         } else {
            return;
         }
       });
}   


function popupmodelsubmenu(parentid,childid,rolecode){
$('#myModal46').modal({backdrop: 'static', keyboard: false});
var v1 ="popupaddsubmenus.php?usergroupid="+parentid+"&menuid="+childid+"&rolecode="+rolecode;
document.getElementById('myframenew2').src=v1;
}


</script>

<script src="js/bootstrap.min.js"></script>
<script>
    $(document).ready(function(){
        // Close modal on button click
        $("#closebtn").click(function(){
            $("#myModal46").modal('hide');
        });
    });
</script>

</head>
<body class="hold-transition sidebar-mini">

    <section class="content-header">

                 <a class="pull-left" href="userprivilegessetup.php?ps=1&pr=<?php echo $_SESSION['pr'];?>" data-toggle="tooltip" data-placement="right" title="Back to Menu Setup"><i class='fa fa-backward'></i></a>
                 <h2 class="title">&nbsp;&nbsp;<?php echo $title; ?></h2>

                 <!--<ol class='breadcrumb'>
                  <li><a href="#"><a href="blank.php" >Menu Setup</a></li>
                  <li><a href="#"><a href="userprivilegessetup.php?ps=1">Main Menu</a></li>
                  <li class="active"><?php echo $title; ?></li>
                 </ol>-->

    </section>
<section class="content" id='content-content-id' style='padding-right:5px;padding-left:5px;margin-top:-10px;' >

                  <div class="nav-tabs-custom" id="nav-tabs-custom-id">
                        <ul class="nav nav-tabs">
                           <li class="active"><a href="#personal" data-toggle="tab"><i class="fa fa-desktop" aria-hidden="true"></i>&nbsp; Privileges</a></li>
                        </ul>

                        <div class="tab-content" id='tab-content-id'>
                           <div class="tab-pane active" id="personal">
                             <div class="box-body no-padding" id='box-body-id'>
                                   <div class='table-responsive no-padding'>

<?php

       $entrydata  = " <form name='frmEdit' method='post' id='frmEdit' enctype='multipart/form-data'>
                             <table class='table table-bordered table-condensed table-fixed  table-responsive' style='table-layout:fixed'>
                <tr>
                  <td class='dvtCellLabel' style='border: 1px solid #ccc;'>Sl No:</td>
                  <td style='border: 1px solid #ccc;width:15%;'><input type='text' class='form-control txt'  onkeypress='return AllowNumeric1(event)'  name='txt_A_slno' id='txt_A_slno'  value='$slno' ></td>
                  <td class='dvtCellLabel' style='border: 1px solid #ccc;'>User Group</td>
                  <td style='border: 1px solid #ccc;'>
                  	<input type='text' class='form-control txt' name='txt_A_rolecode' id='txt_A_rolecode' value='$rolecode' >
                  </td>
                  <td class='dvtCellLabel' style='border: 1px solid #ccc;'>Note</td>
                  <td style='border: 1px solid #ccc;'>
                  	<input type='text' class='form-control txt' name='txt_A_remarks' id='txt_A_remarks' value='$remarks' >
                  </td>
                 </tr>
                 <tr>
                   <td class='dvtCellLabel' style='border: 1px solid #ccc;'>Menu Name:</td>
                   <td style='border: 1px solid #ccc;' colspan=3>
                  	".GetMainMenu($menucode)."
                   </td>
                   <input type='hidden' name='mode' class=textboxcombo id='mode' value='$mode'>
                   <input type='hidden' name='modeid' class=textboxcombo id='modeid' value='save'>
                   <input type='hidden' name='saveid' class=textboxcombo id='saveid' value='$saveid'>
                </tr>
                
        </table>

    ";
    echo $entrydata;
    echo "<br>";
   /* echo "<div class='box' style='border:0px;padding:0px;'>
            <div class='box-tools pull-left '>
                <input type='search' name='txtsearch' onsearch='myFunction()' id=txtsearch class='form-control' style='height:24px;border: 1px solid #ccc;width:200px;' placeholder='Search..' value=".$_REQUEST['search'].">
            </div>

            <div class='box-tools pull-right '>
             </div>
             </div>";*/
			 $sql = "select tbl_menu.menu_code,tbl_menusetup.id,tbl_menu.slno,tbl_menusetup.menucode,tbl_menu.menu_name,tbl_menu.menu_icon,tbl_menu.modulecode,in_lookup.lookname as modulename from tbl_menusetup  left  join tbl_menu on tbl_menu.menu_code=tbl_menusetup.menucode left join in_lookup on in_lookup.lookcode=tbl_menu.modulecode left join tbl_usergroup on tbl_usergroup.id = usergroupid
where tbl_usergroup.id='$mode' and in_lookup.looktype='Modules'";//

             $result = mysqli_query($con,$sql) or die(mysql_error());
             if(mysqli_num_rows($result)>0){
             $entrydatatable = "<table class='table table-bordered table-condensed table-fixed table-striped table-responsive' style='border:1px #2F3C43;'>";
             $entrydatatable.="<thead><tr><th colspan='6'>Main Menus of User group : ".$rolecode."</th></tr><tr>";
             $entrydatatable.= "<th class='bg-light-blue' style='width:5%;text-align:center;border-bottom:1px #2F3C43 solid;border-top:1px #fff solid;color:#FFFFFF' >Sl No</th>";
             $entrydatatable.= "<th class='bg-light-blue' style='text-align:center;border-bottom:1px #2F3C43 solid;border-top:1px #fff solid;color:#FFFFFF' >Menu</th>";
             $entrydatatable.= "<th class='bg-light-blue' style='text-align:center;border-bottom:1px #2F3C43 solid;border-top:1px #fff solid;color:#FFFFFF' >Module</th>";
             $entrydatatable.= "<th class='bg-light-blue' style='text-align:center;border-bottom:1px #2F3C43 solid;border-top:1px #fff solid;color:#FFFFFF' >Icon</th>";
             $entrydatatable.= "<th class='bg-light-blue' style='text-align:center;border-bottom:1px #2F3C43 solid;border-top:1px #fff solid;color:#FFFFFF' >View/ Edit</th>";
             $entrydatatable.= "<th class='bg-light-blue' style='text-align:center;border-bottom:1px #2F3C43 solid;border-top:1px #fff solid;color:#FFFFFF' >Delete</th>";
             $entrydatatable.= "</tr></thead><tbody>";
             $slno =1;
             while($loginResultArrayChild   = mysqli_fetch_array($result)){
                      $colorbg ='#FFFFFF';
                      $colorfc ="#5A5A5A";
                      $icon= $loginResultArrayChild['menu_icon'];
                      $entrydatatable.= "<td style='background-color:$colorbg;color:$colorfc;border:1px #ccc solid;'>" . $loginResultArrayChild['slno'] . "</td>";
                      $entrydatatable.= "<td style='background-color:$colorbg;color:$colorfc;border:1px #ccc solid;'>" . $loginResultArrayChild['menu_name'] . "</td>";
                      $entrydatatable.= "<td style='background-color:$colorbg;color:$colorfc;border:1px #ccc solid;'>" . $loginResultArrayChild['modulename'] . "</td>";
                      $entrydatatable.= "<td align=center style='background-color:$colorbg;color:$colorfc;border:1px #ccc solid;'><i class='$icon' aria-hidden='true'></i></td>";
                      $entrydatatable.= "<td style='background-color:$colorbg;color:$colorfc;border:1px #ccc solid;' align='center'><a href='javascript:popupmodelsubmenu(\"".$mode."\",\"".$loginResultArrayChild['menu_code']."\",\"".$rolecode."\");'><i class='fa fa-eye' aria-hidden='true' title='Add Menu'></i></a>";
                      if($delete == "true")
                      $entrydatatable.= "<td style='background-color:$colorbg;color:$colorfc;border:1px #ccc solid;' align='center'><a href='javascript:deleteChildrecord(\"".$loginResultArrayChild['id']."\");'><img src='ico/remove.png' title='Remove' width='16' height='16'></a></td>";
                      else{
                      	$entrydatatable.= "<td style='background-color:$colorbg;color:$colorfc;border:1px #ccc solid;' align='center'></td>";					  	
					  }
                     $entrydatatable.= "</tr>";
                     ++$slno;

             }
             $entrydatatable.= "</tbody></table>";
             echo $entrydatatable;      
			}       

     echo "</div>
    </div>" ;
echo  "<div class='box-footer' style='border-top:1px #D2D2D2 solid;'>";
                       if($update == "true")
                       echo "  <button class='btn btn-success inputs' style='margin-top:-5px;' name='btnsuccess' type='button'  onclick ='javascript:editingrecord(\"save\");'>Save </font>&nbsp;<i class='fa fa-save' aria-hidden='true'></i></button>";
                       if($insert=="true" && $_REQUEST['ID']=='0')
                       echo "  <button class='btn btn-info inputs' style='margin-top:-5px;' name='btninfo' type='button' onclick ='javascript:editingrecord(\"savenew\");'>Save & New &nbsp; <i class='fa fa-save' aria-hidden='true'></i>&nbsp;&nbsp;<i class='fa fa-plus' aria-hidden='true'></i></button>";
                       if($update == "true" || ($insert=="true" && $_REQUEST['ID']=='0') )
                        echo "   <button class='btn btn-warning inputs' style='margin-top:-5px;' name='btnwarning' type='button'  onclick ='javascript:editingrecord(\"saveclose\");'>Save & Close &nbsp;<i class='fa fa-save' aria-hidden='true'></i>&nbsp;&nbsp;<i class='fa fa-close' aria-hidden='true'></i></button>";
                        echo "  <button class='btn btn-danger inputs' style='margin-top:-5px;' name='btndanger' type='button'  onclick ='javascript:closeediting(\"userprivilegessetup.php?ps=1&pr=".$_SESSION['pr']."\");'>Close &nbsp;<i class='fa fa-close' aria-hidden='true'></i></button>
                                        </div>";
                  echo " </form> " ;
      
             
           
?>

                  </div>

        </section>
        <div class='modal fade' id='myModal1' tabindex='-1' role='dialog' aria-labelledby='myModalLabel'>
         <div class='modal-dialog' role='document' style="align:left;width:800px;">
            <div class='modal-content'>
                 <div class='modal-header' style='height:40px;'>
                  <button type='button' class='close' data-dismiss='modal' aria-label='Close'><span aria-hidden='true'>&times;</span></button>

                 <h3 style='margin-top:-5px;'>Sub Menus</h3>

                 </div>
                  <div class='modal-body' id='propertypopupdiv' name='propertypopupdiv'> </div>

            </div>
         </div>
      </div>
      
      <div id="myModal46" class="modal fade" >
    <div class="modal-dialog" style="align:left;width:950px">
        <div class="modal-content" >
            <div class="modal-header" style='height:40px;'>
                <span aria-hidden='true' style="float: left"><h4 id="modalTitle" class="modal-title">Add Sub Menu Details</h4></span><button type='button' class='close' data-dismiss='modal' aria-label='Close'>&times;</button>
            </div>
            <iframe id="myframenew2" name="myframenew2" scrolling="no"  src=""   frameborder="0" style="position: relative; width: 100%;height:350px;"></iframe>
            <div class="modal-footer" >
                <!--<button class='btn btn-danger inputs' id='closebtn' name='closebtn' type='button'  >Close&nbsp;</button>-->
           </div>
        </div>
    </div>
  </div>
  
</body>
</html>
<?php
function GetMainMenu($menucode){
	global $con;
	$mycontrol = "<div id='divcheckbox'>";
	//if($menucode!=""){
		$menucode_arr = "'".str_replace(",","','",$menucode)."'";
		global $con;
		$SEL = "select menu_name,menu_code from tbl_menu where menu_code not in ($menucode_arr) and parentid='0'"; //main menus
        $RES = mysqli_query($con,$SEL);
        while($ARR = mysqli_fetch_array($RES)){
		$mycontrol .= "<input type='checkbox' class='minimal inputs' id='menulist' name='menulist[]' value='".$ARR['menu_code']."'/>&nbsp;" . $ARR['menu_name']. "&nbsp;&nbsp;&nbsp;";
		}
	//}
    return $mycontrol."</div>";
}
function getMenuName($code) {
		global $con;
		$SEL = "select menu_name from tbl_menu where menu_code='$code'";
        $RES = mysqli_query($con,$SEL);
        $ARR = mysqli_fetch_array($RES);
        return $ARR['menu_name'];
}
function GetModules($modulecode) {
	global $con;
	$CMB = " <select name='cmb_A_modulecode'  id='cmb_A_modulecode' class='form-control'>";
	$seqSQL = "select lookcode ,lookname from in_lookup where looktype='Modules'  and lookname<>'XX' order by slno";
	$result=mysqli_query($con,$seqSQL) or die(mysqli_error()."<br>".$seqSQL);
	$CMB .= "<option value='' >Select Role</option>";
	while($ARR=mysqli_fetch_array($result)){
		$SEL = "";
		if(strtoupper($modulecode)== strtoupper($ARR['lookcode'])){ $SEL =  "SELECTED";}
               $CMB .= "<option value='".strtoupper($ARR['lookcode'])."' $SEL >".$ARR['lookname']."</option>";
    }
    $CMB .= "</select>";
    return $CMB;
}
function GetRoles($rolecode) {
	global $con;
	$CMB = " <select name='cmb_A_rolecode'  id='cmb_A_rolecode' class='form-control' onChange='getMenus(this.value)'>";
	$seqSQL = "select lookcode ,lookname from in_lookup where looktype='Roles'  and lookname<>'XX' order by slno";
	$result=mysqli_query($con,$seqSQL) or die(mysqli_error()."<br>".$seqSQL);
	$CMB .= "<option value='' >Select Role</option>";
	while($ARR=mysqli_fetch_array($result)){
		$SEL = "";
		if(strtoupper($rolecode)== strtoupper($ARR['lookcode'])){ $SEL =  "SELECTED";}
               $CMB .= "<option value='".strtoupper($ARR['lookcode'])."' $SEL >".$ARR['lookname']."</option>";
    }
    $CMB .= "</select>";
    return $CMB;
}
function GetRoleName($rolecode) {
	global $con;
	$seqSQL = "select lookcode ,lookname from in_lookup where looktype='Roles'  and lookcode='$rolecode'";
	$result=mysqli_query($con,$seqSQL) or die(mysqli_error()."<br>".$seqSQL);
	$ARR=mysqli_fetch_array($result);
    return $ARR['lookname'];
}


function GetStatus($status) {
	global $con;
	$CMB = " <select name='cmb_A_status'  id='cmb_A_status' class='form-control' >";
	$seqSQL = "select lookcode ,lookname from in_lookup_head where looktype='PERMISSION'  and lookname<>'YY' order by slno";
	$result=mysqli_query($con,$seqSQL) or die(mysqli_error()."<br>".$seqSQL);
	while($ARR=mysqli_fetch_array($result)){
		$SEL = "";
		if(strtoupper($status)== strtoupper($ARR['lookcode'])){ $SEL =  "SELECTED";}
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
</script>