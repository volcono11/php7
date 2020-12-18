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
     
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
	<!--<link rel="stylesheet" type="text/css" href="bootstrap/dist/css/bootstrap.css">-->
	<script type="text/javascript" src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
	<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
	
      <script language="javascript">


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

	var txt_A_slno=document.getElementById('txt_A_slno');
	if(txt_A_slno){
	  if (txt_A_slno.value==""){
	       alertify.alert("Enter Slno", function () {
	       txt_A_slno.focus();

	  });
	     return;
	  }
	}

	var txt_A_menu_name=document.getElementById('txt_A_menu_name');
	if(txt_A_menu_name){
	  if (txt_A_menu_name.value==""){
	       alertify.alert("Enter Menu Name", function () {
	       txt_A_menu_name.focus();

	  });
	     return;
	  }
	}
	
	var txt_A_menu_url=document.getElementById('txt_A_menu_url');
	if(txt_A_menu_url){
	  if (txt_A_menu_url.value==""){
	       alertify.alert("Enter Menu Url", function () {
	       txt_A_menu_url.focus();

	  });
	     return;
	  }
	}
	
   var parameter = get(document.frmChildEdit);
   insertChildfunction(parameter)

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
              document.frmChildEdit.action='addsubmenus.php?PARENTID='+document.getElementById('txt_A_parentid').value;
              document.frmChildEdit.submit();
             });
           }else if(s1.toString() == s3.toString()){
            alertify.alert("Record Updated", function () {
              document.frmChildEdit.action='addsubmenus.php?PARENTID='+document.getElementById('txt_A_parentid').value;
              document.frmChildEdit.submit();
             });
           }else{
            alertify.alert(s1);
           }

     }

}

function updateChildrecord(childid){

    document.frmChildEdit.action='addsubmenus.php?CHILDID='+childid+'&PARENTID='+document.getElementById('txt_A_parentid').value;
    document.frmChildEdit.submit();
}

function deleteChildrecord(childid){

        alertify.confirm("Are you sure you want to delete ? ", function (e) {
         if (e) {
           document.frmChildEdit.action='addsubmenus.php?DEL=DELETE&CHILDID='+childid+'&PARENTID='+document.getElementById('txt_A_parentid').value;
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

    $formlistname = "addsubmenu.php";

    $grid = new MyPHPGrid('frmPage');

    $grid->formName = "addsubmenus.php";

    $grid->inpage = $frmPage_startrow;

    $grid->TableNameChild = "tbl_menu";

    $grid->SyncSession($grid);

/*	if($CHILDID !='' && $DEL !='DELETE'){
	$display="table-row";
	        $SEL12 = "Select * from tbl_menu where id ='".$CHILDID."'";
	        $dis12 = mysqli_query($con,$SEL12);
	        
	        while ($arr12 = mysqli_fetch_array($dis12)) {
	               $slno=$arr12['slno'];
	               $menu_code=$arr12['menu_code'];
	               $menu_icon= $arr12['menu_icon'];
	               $menu_name= $arr12['menu_name'];
	               $menu_url= $arr12['menu_url'];
	               $PARENTID = $arr12['parentid'];
	               $objectid = $arr12['objectid'];
	     }
	}*/

if($CHILDID !='' && $DEL =='DELETE'){
	    mysqli_query($con,"delete from tbl_menusetup where menucode=(select menu_code from tbl_menu where id='$CHILDID')");
        mysqli_query($con,"delete from tbl_menu where id='". $CHILDID."'");
        
        $CHILDID ="";

}
                                  
        $sql_1 = "select * from tbl_menu where id='".$CHILDID."'";
        $res_1 = mysqli_query($con,$sql_1);
                                  
		if(mysqli_num_rows($res_1)>=1){
		     $arr_1 = mysqli_fetch_array($res_1);
		     $slno = $arr_1['slno'];
		     $menu_code = $arr_1['menu_code'];
		     $menu_name = $arr_1['menu_name'];
		     $menu_icon = $arr_1['menu_icon'];
		     $menu_url = $arr_1['menu_url'];
		     $objectid = $arr_1['objectid'];
		     $objecttype = $arr_1['objecttype'];
		}
		else{
			$menu_icon = "";
			$menu_url = "";
			$menu_name = "";
			$menu_code="";
			$slno="";
			$objectid = $objecttype = "";
		}
                                  
        $no_of_rows = mysqli_num_rows(mysqli_query($con,"select * from tbl_menu where parentid='".$PARENTID."'"));
        $mandatory = "<span class='mandatory'>&nbsp;*</span>";
		
		$Save_button = "";
		if(($insert == "true" && $CHILDID =="") || ($update == "true" && $CHILDID !=""))
        $Save_button = "<a href='javascript:editingChildrecord();'><img src='ico/save.png' title='Save' width='20' height='20'></a><a href='?PARENTID=".$PARENTID."'><img src='ico/cancel.png' title='Cancel' width='20' height='20'></a>";
        
        $entrydata = "<div class='table-responsive no-padding' >
            <form name='frmChildEdit' method='post' id='frmChildEdit' autocomplete='off' enctype='multipart/form-data'>
                <table class='table table-bordered table-condensed table-fixed  table-responsive' style='table-layout:fixed'>
                    <tr>
                        <td class='dvtCellLabel' style='border: 1px solid #ccc;width:13%;'>Slno $mandatory</td>
                        <td style='border: 1px solid #ccc;'><input type='text' onkeypress='return AllowNumeric1(event)' class='form-control txt' name='txt_A_slno' id='txt_A_slno' value='$slno' ></td>
                        <td class='dvtCellLabel' style='border: 1px solid #ccc;width:13%;'>Menu Name $mandatory</td>
                        <td style='border: 1px solid #ccc;'>
                        ".GetSubMenus($menu_name)."
                        <input type='hidden' class='form-control txt' name='txt_A_menu_name' id='txt_A_menu_name' value='$menu_name' ></td>
                        <td style='border: 1px solid #fff;'>&nbsp;</td>
                    </tr>
                    <tr>
                        <td class='dvtCellLabel' style='border: 1px solid #ccc;width:13%;'>Url $mandatory</td>
                        <td style='border: 1px solid #ccc;'><input type='text' class='form-control txt' name='txt_A_menu_url' id='txt_A_menu_url' value='$menu_url' readonly></td> 
                        <td class='dvtCellLabel' style='border: 1px solid #ccc;width:13%;'>Icon</td>
                        <td style='border: 1px solid #ccc;'><input type='text' class='form-control txt' name='txt_A_menu_icon' id='txt_A_menu_icon' value='$menu_icon' readonly></td>                   
                       
                        <td style='border: 1px solid #fff;width:6%;'>
                        ".$Save_button."
                        <input type='hidden' class=textboxcombo name='txt_A_parentid' id='txt_A_parentid' value='".$PARENTID."'>
                        <input type='hidden' class=textboxcombo name='txt_A_menu_code' id='txt_A_menu_code' value='".$menu_code."'>
                        <input type='hidden' class=textboxcombo name='txt_A_objectid' id='txt_A_objectid' value='".$objectid."'>
                        <input type='hidden' class=textboxcombo name='txt_A_objecttype' id='txt_A_objecttype' value='".$objecttype."'>
                                                                     
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

$list_sql = "SELECT * FROM tbl_menu where parentid='".$PARENTID."'  $addsql order by slno";//
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
        $entrydatatable.= "<th class='bg-light-blue' style='width:5%;text-align:left;border-bottom:1px #2F3C43 solid;border-top:1px #fff solid;color:#FFFFFF'>Slno </th>";
        $entrydatatable.= "<th class='bg-light-blue' style='width:15%;text-align:left;border-bottom:1px #2F3C43 solid;border-top:1px #fff solid;color:#FFFFFF'>Code</th>";
        $entrydatatable.= "<th class='bg-light-blue' style='width:20%;text-align:left;border-bottom:1px #2F3C43 solid;border-top:1px #fff solid;color:#FFFFFF'>Name</th>";
        $entrydatatable.= "<th class='bg-light-blue' style='width:35%;text-align:left;border-bottom:1px #2F3C43 solid;border-top:1px #fff solid;color:#FFFFFF'>Url</th>";
        $entrydatatable.= "<th class='bg-light-blue' style='width:15%;text-align:left;border-bottom:1px #2F3C43 solid;border-top:1px #fff solid;color:#FFFFFF'>Icon</th>";

        
        $entrydatatable.= "<th class='bg-light-blue' style='width:5%;text-align:left;border-bottom:1px #2F3C43 solid;border-top:1px #fff solid;color:#FFFFFF'>Edit</th>";
        $entrydatatable.= "<th class='bg-light-blue' style='width:5%;text-align:left;border-bottom:1px #2F3C43 solid;border-top:1px #fff solid;color:#FFFFFF'>Del</th>";
        
        $entrydatatable.= "</tr></thead>
        <tbody  class='sortable'>";
        
		while($loginResultArrayChild   = mysqli_fetch_array($result)){

        	$colorbg ='#FFFFFF';
        	$colorfc ='#5A5A5A';
        	$menu_code = $loginResultArrayChild['menu_code'];
        	if($menu_code == ""){
        		$menu_code = $PARENTID.'_'.$loginResultArrayChild['id'];
				$up_sql = "update tbl_menu set menu_code='".$menu_code."' where id='".$loginResultArrayChild['id']."'";
				mysqli_query($con,$up_sql);
			}
        	$entrydatatable.= "<tr id='".$loginResultArrayChild['id']."'>";
        	$entrydatatable.= "<td style='background-color:$colorbg;color:$colorfc;border:1px #ccc solid;'>" . $loginResultArrayChild['slno']. "</td>";
        	$entrydatatable.= "<td style='background-color:$colorbg;color:$colorfc;border:1px #ccc solid;'>" . $menu_code . "</td>";
        	$entrydatatable.= "<td style='background-color:$colorbg;color:$colorfc;border:1px #ccc solid;'>" . $loginResultArrayChild['menu_name']. "</td>";
        	$entrydatatable.= "<td style='background-color:$colorbg;color:$colorfc;border:1px #ccc solid;'>" . $loginResultArrayChild['menu_url']."</td>";
        	$entrydatatable.= "<td style='background-color:$colorbg;color:$colorfc;border:1px #ccc solid;' align='center'><i class='".$loginResultArrayChild['menu_icon']."' /></td>";
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
     <!-- <script src="plugins/jQuery/jquery-2.2.3.min.js"></script>-->
      <script src="bootstrap/js/bootstrap.min.js"></script>
      <script src="plugins/select2/select2.full.min.js"></script>
      <script src="plugins/jqueryValidate/jquery.validate.js"></script>
      <script src="dist/js/app.js"></script>
      <script type="text/javascript" src="js/jquery-1.8.0.js"></script>
      <script src="plugins/slimScroll/jquery.slimscroll.min.js"></script>
       <script type='text/javascript'>
       
       	$(function(){
		$('.sortable').sortable({
			stop:function()
			{
				var ids = '';
				$('.sortable tr').each(function(){
					id = $(this).attr('id');
					if(ids=='')
					{
						ids = id;
					}
					else
					{
						ids = ids+','+id;
					}
				})
				$.ajax({
					url:'save_order.php',
					data:'ids='+ids,
					type:'post',
					success:function()
					{
						alert('Order saved successfully');
						document.frmChildEdit.action='addsubmenus.php?PARENTID='+document.getElementById('txt_A_parentid').value;
              document.frmChildEdit.submit();
					}
				})
			}
		});
	});
	
$(window).on("load", function(){
   $.ready.then(function(){
      // Both ready and loaded
       boxHeight();
   });
})     

function boxHeight(){
                    var height = $("#content-wrapper-id",parent.parent.document).height()-132;
                    $('#tab-content-id').height(height);
                    var boxheight = height +10;

                    $('#box-body-id').height(boxheight);
                    $('#box-body-id').slimScroll({
                      height: boxheight+'px'
                    });
                }              
                /*$(window).load(function(){
                   boxHeight();
                   $(".select2").select2();
                   $(window).resize(function(){
                     boxHeight();
                   })

                });
              function boxHeight(){
                    var height = $("#content-wrapper-id",parent.parent.document).height()-132;
                    alert(height);
                    $('#tab-content-id').height(height);
                    var boxheight = height +10;

                    $('#box-body-id').height(boxheight);
                    $('#box-body-id').slimScroll({
                      height: boxheight+'px'
                    });
                }*/

</script>
<script type='text/javascript'>

$(function(){
  $('#menuname').select2().on('change', function(e) {
        var optionSelected = $("option:selected", this);
        var valueSelected = this.value;
        var str = valueSelected.split(":");
        $("#txt_A_menu_name").val(str[0]);
        $("#txt_A_menu_url").val(str[1]);
        $("#txt_A_menu_icon").val(str[2]);
        $("#txt_A_objectid").val(str[3]);
        $("#txt_A_objecttype").val(str[4]);
  });
});
</script>
<?php
function GetSubMenus($menu_name){
	global $con;
         $CMB = "<select name='menuname' id='menuname' class='form-control select2' >  ";
         $CMB .= "<option value=''>Select</option>";
         $SEL =  "select menuname,url,iconname,id,objecttype from tbl_objectmaster where showinmenu ='2001' and objecttype='4001' order by menuname";
         $RES = mysqli_query($con,$SEL);
         while ($ARR = mysqli_fetch_array($RES)) {
                $SEL = "";
                if(strtoupper($menu_name) == strtoupper($ARR['menuname'])){ $SEL =  "SELECTED";}
                $CMB .= "<option value='".$ARR['menuname'].":".$ARR['url'].":".$ARR['iconname'].":".$ARR['id'].":".$ARR['objecttype']."' $SEL >".$ARR['menuname']."</option>";
         }
         $CMB .= "</select>";
         return $CMB;
}

?>
