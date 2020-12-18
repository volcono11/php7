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

function setprefixval(catval){
	if(catval == '2001'){
		document.getElementById('txt_A_prefixvalue').disabled =false;
	}
	else{
		document.getElementById('txt_A_prefixvalue').value ='';
		document.getElementById('txt_A_prefixvalue').disabled =true;
	}
}

function setsuffixval(catval){
	if(catval == '2001'){
		document.getElementById('txt_A_suffixvalue').disabled =false;
	}
	else{
		document.getElementById('txt_A_suffixvalue').value ='';
		document.getElementById('txt_A_suffixvalue').disabled =true;
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

	var txt_A_owner=document.getElementById('cmb_A_owner');
	if(txt_A_owner){
	  if (txt_A_owner.value==""){
	       alertify.alert("Select Owner", function () {
	       txt_A_owner.focus();

	  });
	     return;
	  }
	}

	var txt_A_shareamt=document.getElementById('txt_A_shareamt');
	if(txt_A_shareamt){
	  if (txt_A_shareamt.value==""){
	       alertify.alert("Enter Share Amount", function () {
	       txt_A_shareamt.focus();

	  });
	     return;
	  }
	}
	
	var cmb_A_status=document.getElementById('cmb_A_status');
	if(cmb_A_status){
	  if (cmb_A_status.value==""){
	       alertify.alert("Select Status ", function () {
	       cmb_A_status.focus();

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
              document.frmChildEdit.action='ownership.php?PARENTID='+document.getElementById('txt_A_propertyid').value;
              document.frmChildEdit.submit();
             });
           }else if(s1.toString() == s3.toString()){
            alertify.alert("Record Updated", function () {
              document.frmChildEdit.action='ownership.php?PARENTID='+document.getElementById('txt_A_propertyid').value;
              document.frmChildEdit.submit();
             });
           }else{
            alertify.alert(s1);
           }

     }

}

function updateChildrecord(childid){

    document.frmChildEdit.action='ownership.php?CHILDID='+childid+'&PARENTID='+document.getElementById('txt_A_propertyid').value;
    document.frmChildEdit.submit();
}

function deleteChildrecord(childid){

        alertify.confirm("Are you sure you want to delete ? ", function (e) {
         if (e) {
           document.frmChildEdit.action='ownership.php?DEL=DELETE&CHILDID='+childid+'&PARENTID='+document.getElementById('txt_A_propertyid').value;
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
<section class="content" id='content-content-id' style='padding-right:5px;padding-left:5px;' >
   <div class="box-body" id='box-body-id' style='background-color:#ffffff;'>
          <div class='table-responsive'>
<?php 

$PARENTID = isset($_REQUEST['PARENTID']) ? $_REQUEST['PARENTID'] : '';

$CHILDID = isset($_REQUEST['CHILDID']) ? $_REQUEST['CHILDID'] : '';

$DEL = isset($_REQUEST['DEL']) ? $_REQUEST['DEL'] : '';

$frmPage_startrow = isset($_REQUEST['frmPage_startrow']) ? $_REQUEST['frmPage_startrow'] : '';

    $formlistname = "ownership.php";

    $grid = new MyPHPGrid('frmPage');

    $grid->formName = "ownership.php";

    $grid->inpage = $frmPage_startrow;

    $grid->TableNameChild = "tbl_ownership";

    $grid->SyncSession($grid);



if($CHILDID !='' && $DEL =='DELETE'){
        mysqli_query($con,"delete from tbl_ownership where id='". $CHILDID."'");
        $CHILDID ="";

}
                                  
        $sql_1 = "select *,DATE_FORMAT(fromdate,'%d-%m-%Y') as fromdate,DATE_FORMAT(todate,'%d-%m-%Y') as todate from tbl_ownership where id='".$CHILDID."'";
        $res_1 = mysqli_query($con,$sql_1);
                                  
		if(mysqli_num_rows($res_1)>=1){
		    $arr_1 = mysqli_fetch_array($res_1);

	        $propertyid=$arr_1['propertyid'];
	    	$owner= $arr_1['owner'];
	        $shareamt= $arr_1['shareamt'];
	        $shareper= $arr_1['shareper'];
            $fromdate=  $arr_1['fromdate'];
            if(stripos(json_encode($fromdate),'00-00-0000') == true)$fromdate=date('d-m-Y');
            $todate=  $arr_1['todate'];
            if(stripos(json_encode($todate),'00-00-0000') == true)$todate=date('d-m-Y');
	        $status= $arr_1['status'];

		}
		else{
			$propertyid = $owner = $shareamt = $shareper = $status= "";
			$todate=$fromdate=date('d-m-Y');
		}
           
/*    if($CHILDID !='' && $DEL !='DELETE'){
		$display="table-row";
	       $SEL12 = "Select * from tbl_sequencer where id ='".$CHILDID."'";
	        $dis12 = mysqli_query($con,$SEL12);
	        
	        while ($arr12 = mysqli_fetch_array($dis12)) {
	                $companyid=$arr12['companyid'];
			        $document=$arr12['document'];
			    	$prefix= $arr12['prefix'];
			        $prefixvalue= $arr12['prefixvalue'];
			        $suffix= $arr12['suffix'];
			        $suffixvalue= $arr12['suffixvalue'];
			        $startingnumber= $arr12['startingnumber'];
			        $renumber= $arr12['renumber'];
	     }
	}*/                       
        $no_of_rows = mysqli_num_rows(mysqli_query($con,"select * from tbl_ownership where propertyid='".$PARENTID."'"));
        $mandatory = "<span class='mandatory'>&nbsp;*</span>";
		
		$Save_button = "";
		if(($insert == "true" && $CHILDID =="") || ($update == "true" && $CHILDID !=""))
        $Save_button = "<a href='javascript:editingChildrecord();'><img src='ico/save.png' title='Save' width='20' height='20'></a>&nbsp;&nbsp;<a href='?PARENTID=".$PARENTID."'><img src='ico/cancel.png' title='Cancel' width='20' height='20'></a>";
        
        $entrydata = "<div class='table-responsive no-padding'>
            <form name='frmChildEdit' method='post' id='frmChildEdit' autocomplete='off' enctype='multipart/form-data'>
                <table class='table table-bordered table-condensed table-fixed  table-responsive' style='table-layout:fixed'>
                    <tr>
                        <td class='dvtCellLabel' style='border: 1px solid #ccc;width:13%;'>Owner $mandatory</td>
                        <td style='border: 1px solid #ccc;'>".GetOwner($owner)."</td>
                        <td class='dvtCellLabel' style='border: 1px solid #ccc;width:13%;'>Share Amount $mandatory</td>
                        <td style='border: 1px solid #ccc;'>
                        <input type='text' class='form-control txt' name='txt_A_shareamt' id='txt_A_shareamt' value='$shareamt' onkeypress='return AllowNumeric1(event);'></td>
                        <td class='dvtCellLabel' style='border: 1px solid #ccc;' >Share(%)</td>
                        <td style='border: 1px solid #ccc;'>
                        <input type='text' class='form-control txt' name='txt_A_shareper' id='txt_A_shareper' value='$shareper'>
                        </td>
                    </tr>
                    <tr>
                     <td class='dvtCellLabel' style='border: 1px solid #ccc;'>From Date:</td>
                     <td style='width:20%;border: 1px solid #ccc;'><input type='text' class='form-control txt' data-provide='datepicker' maxlength=10 onkeypress='return AllowNumeric1(event)' name='txd_A_fromdate' id='txd_A_fromdate'   value='$fromdate' placeholder='dd-mm-yyyy' ></td>
                     <td class='dvtCellLabel' style='border: 1px solid #ccc;'>To Date:</td>
                     <td style='width:20%;border: 1px solid #ccc;'><input type='text' class='form-control txt' data-provide='datepicker' maxlength=10 onkeypress='return AllowNumeric1(event)' name='txd_A_todate' id='txd_A_todate'   value='$todate' placeholder='dd-mm-yyyy' ></td>
                    <td class='dvtCellLabel' style='border: 1px solid #ccc;'>Status:$mandatory</td>
                    <td style='width:20%;border: 1px solid #ccc;'>".GetStatus($status)."</td>

                    </tr>
                    <tr>

                       
                        <td style='border: 1px solid #fff;width:6%;text-align:right;'colspan=6>
                        ".$Save_button."
                        <input type='hidden' class=textboxcombo name='txt_A_propertyid' id='txt_A_propertyid' value='".$PARENTID."'>
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
$limit1=6;
      if(isset($_GET['id1'])){
         $id1=$_GET['id1'];
         $start1=($id1-1)*$limit1;
      }else{
         $id1=1;
      }
$addsql="";
if(isset($_REQUEST['search'])!=""){
	/*$addsql = " and (";
	$addsql .= " reasonforrevision like '%".$_REQUEST['search']."%'";
	$addsql .= ")";*/
}

 $list_sql = "select tbl_ownership.*,DATE_FORMAT(tbl_ownership.fromdate,'%d-%m-%Y') as fromdate,DATE_FORMAT(tbl_ownership.todate,'%d-%m-%Y') as todate, A.ownername as ownername,B.lookname as status from tbl_ownership left join tbl_propertyowner as A on A.id=tbl_ownership.owner left join in_lookup as B on B.lookcode=tbl_ownership.status where tbl_ownership.propertyid='".$PARENTID."' $addsql";
$rows1=mysqli_num_rows(mysqli_query($con,$list_sql));

$p_rows=mysqli_num_rows(mysqli_query($con,$list_sql));

echo "<div class='box' style='border:0px;padding:0px;'>

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
       </div>";


$sql = $list_sql. " LIMIT $start1, $limit1";
$result = mysqli_query($con,$sql) or die(mysqli_error());
        $entrydatatable = "<table class='table table-bordered table-condensed table-fixed table-striped table-responsive' style='border:1px #2F3C43;'>";
        $entrydatatable.="<thead><tr>";
        $entrydatatable.= "<th class='bg-light-blue' style='width:5%;text-align:left;border-bottom:1px #2F3C43 solid;border-top:1px #fff solid;color:#FFFFFF'>Slno </th>";
        $entrydatatable.= "<th class='bg-light-blue' style='width:15%;text-align:left;border-bottom:1px #2F3C43 solid;border-top:1px #fff solid;color:#FFFFFF'>Owner</th>";
        $entrydatatable.= "<th class='bg-light-blue' style='width:10%;text-align:left;border-bottom:1px #2F3C43 solid;border-top:1px #fff solid;color:#FFFFFF'>Share Amount</th>";
        $entrydatatable.= "<th class='bg-light-blue' style='width:15%;text-align:left;border-bottom:1px #2F3C43 solid;border-top:1px #fff solid;color:#FFFFFF'>Share Percentage</th>";
        $entrydatatable.= "<th class='bg-light-blue' style='width:10%;text-align:left;border-bottom:1px #2F3C43 solid;border-top:1px #fff solid;color:#FFFFFF'>From Date</th>";
        $entrydatatable.= "<th class='bg-light-blue' style='width:15%;text-align:left;border-bottom:1px #2F3C43 solid;border-top:1px #fff solid;color:#FFFFFF'>To Date Val</th>";
        $entrydatatable.= "<th class='bg-light-blue' style='width:10%;text-align:left;border-bottom:1px #2F3C43 solid;border-top:1px #fff solid;color:#FFFFFF'>Satatus</th>";

        
        $entrydatatable.= "<th class='bg-light-blue' style='width:5%;text-align:left;border-bottom:1px #2F3C43 solid;border-top:1px #fff solid;color:#FFFFFF'>Edit</th>";
        $entrydatatable.= "<th class='bg-light-blue' style='width:5%;text-align:left;border-bottom:1px #2F3C43 solid;border-top:1px #fff solid;color:#FFFFFF'>Del</th>";
        
        $entrydatatable.= "</tr></thead><tbody>";
        
        
        $slno = 0;
		while($loginResultArrayChild   = mysqli_fetch_array($result)){

        	$colorbg ='#FFFFFF';
        	$colorfc ='#5A5A5A';
       
        	$entrydatatable.= "<tr>";
        	$entrydatatable.= "<td style='background-color:$colorbg;color:$colorfc;border:1px #ccc solid;'>".++$slno. "</td>";
        	$entrydatatable.= "<td style='background-color:$colorbg;color:$colorfc;border:1px #ccc solid;'>" . $loginResultArrayChild['ownername']. "</td>";
        	$entrydatatable.= "<td style='background-color:$colorbg;color:$colorfc;border:1px #ccc solid;'>" . $loginResultArrayChild['shareamt']. "</td>";
        	$entrydatatable.= "<td style='background-color:$colorbg;color:$colorfc;border:1px #ccc solid;'>" . $loginResultArrayChild['shareper']. "</td>";
        	$entrydatatable.= "<td style='background-color:$colorbg;color:$colorfc;border:1px #ccc solid;'>" . $loginResultArrayChild['fromdate']."</td>";
        	$entrydatatable.= "<td style='background-color:$colorbg;color:$colorfc;border:1px #ccc solid;'>" . $loginResultArrayChild['todate']."</td>";
        	$entrydatatable.= "<td style='background-color:$colorbg;color:$colorfc;border:1px #ccc solid;'>" . $loginResultArrayChild['status']."</td>";
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

$entrydatatable.= "</tbody></table>";
echo $entrydatatable;


?>       </div>

   </body>
</html>

      <script src="jq/jquery-2.1.1.min.js"></script>
      <script src="plugins/jQuery/jquery-2.2.3.min.js"></script>
      <script src="bootstrap/js/bootstrap.min.js"></script>
      <script src="plugins/slimScroll/jquery.slimscroll.js"></script>
      <script src="plugins/select2/select2.full.min.js"></script>
      <script src="plugins/jqueryValidate/jquery.validate.js"></script>
      <script src="dist/js/app.js"></script>
      <script type="text/javascript" src="js/jquery-1.8.0.js"></script>
      <script src="plugins/slimScroll/jquery.slimscroll.min.js"></script>
       <script type='text/javascript'>
                $(window).load(function(){
                   boxHeight()
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
<script type='text/javascript'>
$(function(){
  $('#menuname').select2().on('change', function(e) {
        var optionSelected = $("option:selected", this);
        var valueSelected = this.value;
        var str = valueSelected.split(":");
        $("#txt_A_menu_name").val(str[0]);
        $("#txt_A_menu_url").val(str[1]);
        $("#txt_A_menu_icon").val(str[2]);
  });
});
</script>
<?php
function GetOwner($owner){
	global $con;
    $CMB = " <select name='cmb_A_owner'  id='cmb_A_owner' class='form-control select2'>";
    $CMB .= "<option value=''>Select</option>";
	$SEL =  "Select id,ownername from tbl_propertyowner";
	$RES = mysqli_query($con,$SEL);
	while ($ARR = mysqli_fetch_array($RES)) {
	   $SEL = "";
	   if($owner == $ARR['id']){ $SEL =  "SELECTED";}
	   $CMB .= "<option value='".trim($ARR['id'])."' $SEL >".trim($ARR['ownername'])."</option>";
	}
	$CMB .= "</select>";
	return $CMB;
}
function GetSequencerData($roles){
	global $con;
    $CMB = " <select name='cmb_A_document'  id='cmb_A_document' class='form-control select2'>";    
    $CMB .= "<option value=''>Select</option>";
	$SEL =  "Select lookcode,lookname from in_lookup where looktype='SEQUENCER' and lookname<>'XX'";
	$RES = mysqli_query($con,$SEL);
	while ($ARR = mysqli_fetch_array($RES)) {
	   $SEL = "";
	   if($roles == $ARR['lookcode']){ $SEL =  "SELECTED";}
	   $CMB .= "<option value='".trim($ARR['lookcode'])."' $SEL >".trim($ARR['lookname'])."</option>";
	}
	$CMB .= "</select>";
	return $CMB;
}

function GetYesNo($fieldname,$yesno,$onchange){
	global $con;
    $CMB = " <select name='$fieldname'  id='$fieldname' class='form-control select' $onchange>";    
    $CMB .= "<option value=''>Select</option>";
	$SEL =  "Select lookcode,lookname from in_lookup where looktype='YESNO' and lookname<>'XX'";
	$RES = mysqli_query($con,$SEL);
	while ($ARR = mysqli_fetch_array($RES)) {
	   $SEL = "";
	   if($yesno == $ARR['lookcode']){ $SEL =  "SELECTED";}
	   $CMB .= "<option value='".trim($ARR['lookcode'])."' $SEL >".trim($ARR['lookname'])."</option>";
	}
	$CMB .= "</select>";
	return $CMB;
}
function GetStatus($status){
	global $con;
    $CMB = " <select name='cmb_A_status'  id='cmb_A_status' class='form-control select'>";
    $CMB .= "<option value=''>Select</option>";
	$SEL =  "Select lookcode,lookname from in_lookup where looktype='STATUS' and lookname<>'XX'";
	$RES = mysqli_query($con,$SEL);
	while ($ARR = mysqli_fetch_array($RES)) {
	   $SEL = "";
	   if($status == $ARR['lookcode']){ $SEL =  "SELECTED";}
	   $CMB .= "<option value='".trim($ARR['lookcode'])."' $SEL >".trim($ARR['lookname'])."</option>";
	}
	$CMB .= "</select>";
	return $CMB;
}

?>
