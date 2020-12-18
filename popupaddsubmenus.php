<?php
session_start();
require "connection.php";
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
<style type="text/css">
a.tip {
        position: relative;
}

a.tip span {
        display: none;
        position: absolute;
        top: 20px;
        left: -10px;
        width: 350px;
        height: 150px;
        padding: 5px;
        z-index: 100;
        background: #CCCCCC;
        color: #000000;
        -moz-border-radius: 5px; /* this works only in camino/firefox */
        -webkit-border-radius: 5px; /* this is just for Safari */
}

a:hover.tip {
        font-size: 99%; /* this is just for IE */
}

a:hover.tip span {
        display: block;
}
.tableMHead {
        font-family:Verdana, Arial, Helvetica, sans-serif; font-size:13px; font-weight:bold; background-color:#F0F0F0; padding-left:5px;
}
.mainTable{
        font-family:Verdana, Arial, Helvetica, sans-serif; font-size:11px;
        border-bottom:1px solid #000000;border-top:1px solid #000000;border-left:1px solid #000000;border-right:1px solid #000000;line-height:30px;
}
.combobox {
        font-size:11px; border:1px solid #CCCCCC; font-family:Arial, Helvetica, sans-serif;
}
.tableMRow{
        font-size:12px; font-weight:bold; font-family:Arial, Helvetica, sans-serif; text-align:left; vertical-align:bottom;padding-left:5px;
}
.tableHead {
        font-size:11px; font-weight:bold; vertical-align:text-bottom; border-bottom:1px solid #000000;border-top:1px solid #000000; border-left:1px solid #000000; height:20px; vertical-align:middle;
}
.tableHead_rt {
        font-size:11px; font-weight:bold; vertical-align:text-bottom; border-bottom:1px solid #000000;border-top:1px solid #000000; border-left:1px solid #000000; border-right:1px solid #000000; height:20px; vertical-align:middle;
}
.MainTable{
        font-family:Verdana, Arial, Helvetica, sans-serif;
}
.TableRow {
        font-size:11px; font-weight:normal; border-bottom:1px solid #000000; border-left:1px solid #000000; height:20px;
}
.TableRow_rt{
        font-size:11px; font-weight:normal; border-bottom:1px solid #000000;border-right:1px solid #000000; border-left:1px solid #000000; height:20px;
}

</style>
<script language="javascript">
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
                         //alert(parameters);
                          xmlHttp=GetXmlHttpObject()
                          if (xmlHttp==null)
                          {
                                 alert ("Browser does not support HTTP Request")
                                 return
                          }


                          var url="in_action.php"+parameters
                          xmlHttp.onreadystatechange=stateChangedchild
                          xmlHttp.open("POST",url,true)
                          xmlHttp.send(null)

                   }
                   function stateChangedchild()
                   {

                         if (xmlHttp.readyState==4 || xmlHttp.readyState=="complete")
                         {
                               var s1 = trim(xmlHttp.responseText);
                               //alert(s1);
                               var s2 = "Record Saved";
                               var s3 = "Record Updated";
                               if(s1.toString() == s2.toString()){
                                 alertify.alert("Record Saved", function () {
                                  document.frmChildEdit.action='popupaddmanpower.php?ID='+document.getElementById('txt_A_inheadid').value;
                                  document.frmChildEdit.submit();
                                 });
                               }else if(s1.toString() == s3.toString()){
                                alertify.alert("Record Updated", function () {
                                  document.frmChildEdit.action='popupaddmanpower.php?ID='+document.getElementById('txt_A_inheadid').value;
                                  document.frmChildEdit.submit();
                                 });
                               }else{
                                alertify.alert(s1);
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


function saverecord(){
	document.frmChildEdit.action="popupaddsubmenus.php"+get(document.frmChildEdit)+"MODE=SAVE&usergroupid="+document.getElementById('usergroupid').value+"&menuid="+document.getElementById('menuid').value+"&rolecode="+document.getElementById('rolecode').value;;
    document.getElementById('frmChildEdit').submit();
}

</script>
</head>
<body class="hold-transition sidebar-mini" style="background-color: #fff;">
      <section class="content" id='content-content-id' style='padding-right:5px;padding-left:5px;margin-top:-10px;'>

                  <div class="nav-tabs-custom" id="nav-tabs-custom-id">

                        <div class="tab-content" id='tab-content-id'>
                          <div class="tab-pane active" id="personal">
                              <div class="box-body no-padding" id='box-body-id'>
                                   <div class='table-responsive no-padding'>
<?php
#print_r($_REQUEST);
if(isset($_REQUEST['MODE']) == "SAVE"){
	
	$rows = isset($_REQUEST['rows']) ? $_REQUEST['rows'] : '0';
	if($rows > 0){
		for($i=1;$i<=$rows;$i++){
			$table_id = "tblid_".$i;
			$add_data = "add_".$i;
			$view_data = "view_".$i;
			$edit_data = "edit_".$i;
			$delete_data = "delete_".$i;
			
			$SQL3   = "update tbl_menusetup set adddata='".$_REQUEST[$add_data]."',
	              viewdata='".$_REQUEST[$view_data]."',
	              editdata='".$_REQUEST[$edit_data]."',
	              deletedata='".$_REQUEST[$delete_data]."',
	              status='Active'
	              where id='".$_REQUEST[$table_id]."'";
	    	mysqli_query($con,$SQL3) or die(mysqli_error()."<br>".$SQL3);
		}
	}
}


	$SQLchild = "select tbl_menusetup.*,tbl_menu.slno,tbl_menu.menu_name,tbl_menu.menu_icon from tbl_menusetup right join tbl_menu on tbl_menusetup.menucode=tbl_menu.menu_code left join tbl_usergroup on tbl_usergroup.id = usergroupid
where tbl_usergroup.id  ='".$_REQUEST['usergroupid']."' and tbl_menusetup.parentid='".$_REQUEST['menuid']."' order by tbl_menu.slno";
	$SQLReschild =  mysqli_query($con,$SQLchild) or die(mysqli_error()."<br>".$SQLchild);
	if(mysqli_num_rows($SQLReschild) == 0){
		$sql = "select * from tbl_menu where parentid=(select menucode from  tbl_menusetup where menucode='".$_REQUEST['menuid']."' and usergroupid='".$_REQUEST['usergroupid']."')";
		$res = mysqli_query($con,$sql) or die(mysqli_error()."<br>".$sql);
		while($arr = mysqli_fetch_array($res)){
			$childseqID = GetLastSqeID('tbl_menusetup');
			$ins = "insert into tbl_menusetup (id,menucode,usergroupid,slno,parentid) values 
			('$childseqID','".$arr['menu_code']."','".$_REQUEST['usergroupid']."','".$arr['slno']."','".$_REQUEST['menuid']."')";
			mysqli_query($con,$ins);
			
		}
	$SQLReschild =  mysqli_query($con,$SQLchild) or die(mysqli_error()."<br>".$SQLchild);	
	}
	else{
		$sql2 = "select A.menu_code as chiddata,B.menu_code as pdata from (select menu_code from tbl_menusetup 
		right join tbl_menu on tbl_menusetup.menucode=tbl_menu.menu_code left join tbl_usergroup on tbl_usergroup.id = usergroupid where tbl_usergroup.id ='".$_REQUEST['usergroupid']."' and tbl_menusetup.parentid='".$_REQUEST['menuid']."') as A
		right join
		(select menu_code from tbl_menu where parentid='".$_REQUEST['menuid']."') as B
		on B.menu_code= A.menu_code";
		$res2 = mysqli_query($con,$sql2) or die(mysqli_error()."<br>".$sql2);
		while($arr2 = mysqli_fetch_array($res2)){
			if($arr2['chiddata'] == ""){
			$sql = "select * from tbl_menu where menu_code='".$arr2['pdata']."'";
			$res = mysqli_query($con,$sql) or die(mysqli_error()."<br>".$sql);
			while($arr = mysqli_fetch_array($res)){	
				$childseqID = GetLastSqeID('tbl_menusetup');
				$ins = "insert into tbl_menusetup (id,menucode,usergroupid,slno,parentid) values 
				('$childseqID','".$arr['menu_code']."','".$_REQUEST['usergroupid']."','".$arr['slno']."','".$_REQUEST['menuid']."')";
				mysqli_query($con,$ins);
			}
			}
			
		}
		$SQLReschild =  mysqli_query($con,$SQLchild) or die(mysqli_error()."<br>".$SQLchild);
		
	}
		if($update == "true")
		$Save_button="<tr>
					  <td style='border: 1px solid #fff;border-bottom: 1px solid #ccc;' align='right' colspan=7><button class='btn btn-success inputs' id='savebtn' name='savebtn' type='button' onclick ='javascript:saverecord();' >Save All&nbsp;</button></td>
					  </tr>";
					  
		else{
			$Save_button = "";
		}
		$html =   "<form name='frmChildEdit'  method='post' id='frmChildEdit' enctype='multipart/form-data'>
		<input type='hidden' id='rolecode' value='".$_REQUEST['rolecode']."'/>
		<input type='hidden' id='usergroupid' value='".$_REQUEST['usergroupid']."'/>
		<input type='hidden' id='menuid' value='".$_REQUEST['menuid']."'/>
		<table class='table table-condensed table-bordered' width=100%>
					  ".$Save_button."
                      <tr>
                      <td class='dvtCellLabel' style='border: 1px solid #ccc;width:5%'><b>Slno</b></td>
                      <td class='dvtCellLabel' style='border: 1px solid #ccc;width:25%'><b>Submenu</b></td>
                      <td class='dvtCellLabel' style='border: 1px solid #ccc;width:10%' align='center'><b>Add</b></td>
                      <td class='dvtCellLabel' style='border: 1px solid #ccc;width:10%' align='center'><b>Edit</b></td>
                      <td class='dvtCellLabel' style='border: 1px solid #ccc;width:10%' align='center'><b>View</b></td>
                      <td class='dvtCellLabel' style='border: 1px solid #ccc;width:10%' align='center'><b>Delete</b></td>
                      <td class='dvtCellLabel' style='border: 1px solid #ccc;width:10%' align='center'><b>Icon</b></td>
                      </tr> ";
                      $html_data="";
        $rows = mysqli_num_rows($SQLReschild);
        $i=1;              ;
        while ($loginResultArrayChild   = mysqli_fetch_array($SQLReschild)) {
        	if($loginResultArrayChild['adddata'] == "true")	 $add_data = "checked";
        	else $add_data = "";
        	
        	if($loginResultArrayChild['editdata'] == "true")	 $edit_data = "checked";
        	else $edit_data = "";
        	
        	if($loginResultArrayChild['viewdata'] == "true")	 $view_data = "checked";
        	else $view_data = "";
        	
        	if($loginResultArrayChild['deletedata'] == "true")	 $delete_data = "checked";
        	else $delete_data = "";
        	//for($i=0;$i<20;$i++)
           $html_data.=   "<tr>
                      <td style='border: 1px solid #ccc;'>".$loginResultArrayChild['slno']."</td>
                      <td style='border: 1px solid #ccc;'>".$loginResultArrayChild['menu_name']."
                      <input type='hidden' id='tblid_".$i."' name='tblid_".$i."' value='".$loginResultArrayChild['id']."'/>
                      </td>
                      <td style='border: 1px solid #ccc;' align='center'><input type=checkbox id='add_".$i."' $add_data></td>
                      <td style='border: 1px solid #ccc;' align='center'><input type=checkbox id='edit_".$i."' $edit_data></td>
                      <td style='border: 1px solid #ccc;' align='center'><input type=checkbox id='view_".$i."' $view_data></td>
                      <td style='border: 1px solid #ccc;' align='center'><input type=checkbox id='delete_".$i."' $delete_data></td>
                      <td style='border: 1px solid #ccc;' align='center'><i class='".$loginResultArrayChild['menu_icon']."' aria-hidden='true'></i></td>
                      </tr> ";
                      $i++;
    
		}
    	echo($html.$html_data."<input type='hidden' id='rows' name='rows' value='".$rows."'/></form>");
    	
   

function getManpowerDesignationName($code) {
         $SEL = "select categorycode,categoryname from tbl_manpowercategory where categorycode='$code'";
         $RES = mysqli_query($con,$SEL);
         $ARR = mysqli_fetch_array($RES);
         return $ARR['categoryname'];
}
function getCatName($code) {
         $SEL = "select * from tbl_manpowercategory where posted='Yes' and categorycode='$code'";
         $RES = mysqli_query($con,$SEL);
         $ARR = mysqli_fetch_array($RES);
         return $ARR['categoryname'];
}
function GetManpowerDesignation($categen,$categorycode) {
         $CMB = " <select name='cmb_A_designation'  id='cmb_A_designation' class='form-control select' $lock >";
         $CMB .= "<option value=''>Select</option>";
         $SQL = "select categorycode,categoryname from tbl_manpowercategory
         where catgencode='$categen' and tbl_manpowercategory.posted='YES' and tbl_manpowercategory.catgencode<>'XX'";
         $RES = mysqli_query($con,$SQL);
         while ($ARR = mysqli_fetch_array($RES)) {
                $SEL = "";
                if($categorycode == $ARR['categorycode']){ $SEL =  "SELECTED";}
                $CMB .= "<option value='".$ARR['categorycode']."' $SEL >".$ARR['categoryname']."</option>";
         }
         $CMB .= "</select>";
         return $CMB;
}
function GetManpowerCategory($manpowercategory) {
         $CMB = " <select name='cmb_A_manpowercategory'  id='cmb_A_manpowercategory' class='form-control select' $lock onchange='getDesignation(this.value);'>";
         $CMB .= "<option value=''>Select</option>";
         $SQL =  "select * from tbl_manpowercategory where posted='Yes' and catgencode='XX' order by categoryname";
         $RES = mysqli_query($con,$SQL);
         while ($ARR = mysqli_fetch_array($RES)) {
                $SEL = "";
                if($manpowercategory == $ARR['categorycode']){ $SEL =  "SELECTED";}
                $CMB .= "<option value='".$ARR['categorycode']."' $SEL >".$ARR['categoryname']."</option>";
         }
         $CMB .= "</select>";
         return $CMB;
}

function GetWorkunit($workunit) {
         $CMB = " <select name='cmb_A_workunit'  id='cmb_A_workunit' class='form-control select'>";
         // $CMB .= "<option value=''>Select</option>";
         $SQL =  "select lookcode,lookname from in_lookup_head where looktype='WORK UNIT' and lookname<>'YY'";
         $RES = mysqli_query($con,$SQL);
         while ($ARR = mysqli_fetch_array($RES)) {
                $SEL = "";
                if($workunit == $ARR['lookcode']){ $SEL =  "SELECTED";}
                $CMB .= "<option value='".$ARR['lookcode']."' $SEL >".$ARR['lookname']."</option>";
         }
         $CMB .= "</select>";
         return $CMB;
}

?>
</div>
</div>
</div>
</div>
</div>

</section>
<?php
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
                    var boxheight = 310;

                    $('#box-body-id').slimScroll({
                      height: boxheight +'px',
                      wheelStep: 100,
                      alwaysVisible: true

                    });


                }
</script>
