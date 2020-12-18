<?php
session_start();
//$_SESSION['pr'] = isset($_REQUEST['pr']) ? $_REQUEST['pr'] : '';
require "connection.php";
require "pagingObj.php";
//print_r($_REQUEST);
$insert = $update = $delete = "false";

if(false !== strpos($_SESSION['pr'],"I")) $insert = "true" ;
if(false !== strpos($_SESSION['pr'],"U")) $update = "true" ;
if(false !== strpos($_SESSION['pr'],"D")) $delete = "true" ;

$PARENTID = isset($_REQUEST['PARENTID']) ? $_REQUEST['PARENTID'] : '';

$CHILDID = isset($_REQUEST['CHILDID']) ? $_REQUEST['CHILDID'] : '';

$DEL = isset($_REQUEST['DEL']) ? $_REQUEST['DEL'] : '';

$frmPage_startrow = isset($_REQUEST['frmPage_startrow']) ? $_REQUEST['frmPage_startrow'] : '';

    $formlistname = "editpropertylist.php";

    $grid = new MyPHPGrid('frmPage');

    $grid->formName = "popuppropertylist.php";

    $grid->inpage = $frmPage_startrow;

    $grid->TableNameChild = "tbl_propertydetails";

    $grid->SyncSession($grid);
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


    var txt_A_unitname=document.getElementById('txt_A_unitname');
	if(txt_A_unitname){
	  if (txt_A_unitname.value==""){
	       alertify.alert("Enter Unit Name", function () {
	       txt_A_unitname.focus();

	  });
	     return;
	  }
	}

    var cmb_A_unittype=document.getElementById('cmb_A_unittype');
	if(cmb_A_unittype){
	  if (cmb_A_unittype.value==""){
	       alertify.alert("Enter Unit Type", function () {
	       cmb_A_unittype.focus();

	  });
	     return;
	  }
	}



	var txt_A_unitnumber=document.getElementById('txt_A_unitnumber');
	if(txt_A_unitnumber){
	  if (txt_A_unitnumber.value==""){
	       alertify.alert("Enter Unit No", function () {
	       txt_A_unitnumber.focus();

	  });
	     return;
	  }
	}

chks = document.getElementsByName('unitfeatures[]');

		var checkboxvalidation ='NO';
		var unitfeatures = "";
		for (i = 0; i < chks.length; i++){
		if (chks[i].checked){
		    checkboxvalidation = 'YES';
		    unitfeatures += chks[i].value+',';

		}

		}
		if(chks){
		  if (checkboxvalidation=='NO'){
		       alertify.alert("Select Unit Features", function () {
		       unitfeatures.focus();

		  });
		     return;
		  }
		}
	   unitfeatures =unitfeatures.slice(0,-1)	;
	   
  var cmb_A_unitstatus=document.getElementById('cmb_A_unitstatus');
	if(cmb_A_unitstatus){
	  if (cmb_A_unitstatus.value==""){
	       alertify.alert("Select Unit Status", function () {
	       cmb_A_unitstatus.focus();

	  });
	     return;
	  }
	}

   var parameter = get(document.frmChildEdit)+'txt_A_unitfeatures='+unitfeatures;
  // alert(parameter);
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
     // alert(parameters);
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
          // alert(s1);
           var s2 = "Record Saved";
           var s3 = "Record Updated";
           if(s1.toString() == s2.toString()){
             alertify.alert("Record Saved", function () {
              document.frmChildEdit.action='popuppropertylist.php?CHILDID='+document.getElementById('childid').value+'&PARENTID='+document.getElementById('txt_A_propertyid').value;
              document.frmChildEdit.submit();
             });
           }else if(s1.toString() == s3.toString()){
            alertify.alert("Record Updated", function () {
              document.frmChildEdit.action='popuppropertylist.php?CHILDID='+document.getElementById('childid').value+'&PARENTID='+document.getElementById('txt_A_propertyid').value;
              document.frmChildEdit.submit();
             });
           }else{
            alertify.alert(s1);
           }

     }

}

function updateChildrecord(childid){

    document.frmChildEdit.action='editpropertylistinfo.php?CHILDID='+childid+'&PARENTID='+document.getElementById('txt_A_propertyid').value;
    document.frmChildEdit.submit();
}

function deleteChildrecord(childid){

        alertify.confirm("Are you sure you want to delete ? ", function (e) {
         if (e) {
           document.frmChildEdit.action='editpropertylistinfo.php?DEL=DELETE&CHILDID='+childid+'&PARENTID='+document.getElementById('txt_A_propertyid').value;
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
<body class="hold-transition sidebar-mini">
<section class="content-header">

                <!-- <a class="pull-left" href="popuppropertylist.php?pr=<?php echo $_SESSION['pr']; ?>&frmPage_rowcount=<?php echo $_SESSION['frmPage_rowcount']; ?>&txtsearch=<?php echo $_SESSION['txtsearch']; ?>&frmPage_startrow=<?php echo $_SESSION['frmPage_startrow'];?>" data-toggle="tooltip" data-placement="right" title="Back to Group Setup"><i class='fa fa-backward'></i></a>
                 <h2 class="title"><?php echo $title; ?></h2>

                <!-- <ol class='breadcrumb'>
                  <li><a href="#"><a href="blank.php" >Admin Setup</a></li>
                  <li><a href="#"><a href="companysetup.php?ps=1">Group</a></li>
                  <li class="active"><?php echo $title; ?></li>
                 </ol>-->

         </section>

<section class="content" id='content-content-id' style='padding-right:5px;padding-left:5px;margin-top:-10px;' >

 <div class="nav-tabs-custom" id="nav-tabs-custom-id">
                         <ul class="nav nav-tabs">
                           <li class="active"><a href="#unit" onclick='javascript:loadpage(2);' data-toggle="tab"><i class="fa fa-desktop" aria-hidden="true"></i>&nbsp; Unit Details</a></li>
                           <li><a href="#furniture"   onclick='javascript:loadpage(3);' data-toggle="tab"><i class="fa fa-list" aria-hidden="true"></i>&nbsp; Furniture & Fixtures</a></li>
                           <li><a href="#documents"   onclick='javascript:loadpage(4);' data-toggle="tab"><i class="fa fa-list" aria-hidden="true"></i>&nbsp;  Documents</a></li>
                           <li><a href="#photos"   onclick='javascript:loadpage(5);' data-toggle="tab"><i class="fa fa-list" aria-hidden="true"></i>&nbsp; Photos</a></li>

                        </ul>
                        <div class="tab-content" id='tab-content-id'>
                        <div class="tab-pane active" id="unit">
                        <div class="box-body no-padding" id='box-body-id'>
                        <div class='table-responsive no-padding'>

<?php

if($CHILDID !='' && $DEL =='DELETE'){
        mysqli_query($con,"delete from tbl_propertydetails where id='". $CHILDID."'");
        $CHILDID ="";

}

        $sql_1 = "select * from tbl_propertydetails where id='".$CHILDID."'";
        $res_1 = mysqli_query($con,$sql_1);

		if(mysqli_num_rows($res_1)>=1){
		    $arr_1 = mysqli_fetch_array($res_1);
		    $unittype=$arr_1['unittype'];
            $block=$arr_1['block'];
		    $floor=$arr_1['floor'];
		    $unitnumber=$arr_1['unitnumber'];
		    $unitname=$arr_1['unitname'];
		    $unitviews=$arr_1['unitview'];
		    $unitfeatures=$arr_1['unitfeatures'];
		    $actype=$arr_1['actype'];
		    $furnishing=$arr_1['furnishing'];
		    $petsallowed=$arr_1['petsallowed'];
		    $unitarea=$arr_1['unitarea'];
		    $unitstatus=$arr_1['unitstatus'];
		    $minrent=$arr_1['minrent'];
		    $maxrent=$arr_1['maxrent'];
            $photo=$arr_1['photo'];
            $foldername='uploads';


		}
		else{
			$foldername=$photo=$maxrent=$minrent=$unitstatus=$unitarea=$petsallowed=$unitfeatures=$unitviews=$furnishing=$actype=$unitnumber=$unitname=$floor=$block=$unittype=$propertyid ="";

		}

        $no_of_rows = mysqli_num_rows(mysqli_query($con,"select * from tbl_propertydetails where propertyid='".$PARENTID."'"));
        $mandatory = "<span class='mandatory'>&nbsp;*</span>";

		$Save_button = "";
		if(($insert == "true" && $CHILDID =="") || ($update == "true" && $CHILDID !=""))
        $Save_button = "<a href='javascript:editingChildrecord();'><img src='ico/save.png' title='Save' width='20' height='20'></a>&nbsp;&nbsp;<a href='?PARENTID=".$PARENTID."&CHILDID=".$CHILDID."'><img src='ico/cancel.png' title='Cancel' width='20' height='20'></a>";

       // $entrydata = "<div class='table-responsive no-padding'>
          $entrydata = "<form name='frmChildEdit' method='post' id='frmChildEdit' autocomplete='off' enctype='multipart/form-data'>
                <table class='table table-bordered table-condensed table-fixed  table-responsive' style='table-layout:fixed'>
                    <tr>
                        <td class='dvtCellLabel' style='border: 1px solid #ccc;width:13%;'>Unit Name $mandatory</td>
                        <td style='border: 1px solid #ccc;' >
                        <input type='text' class='form-control txt' name='txt_A_unitname' id='txt_A_unitname' value='$unitname'>
                        <td class='dvtCellLabel' style='border: 1px solid #ccc;width:13%;'>Unit Type $mandatory</td>
                        <td style='border: 1px solid #ccc;'>".GetUnitType($unittype)."</td>
                        <td class='dvtCellLabel' style='border: 1px solid #ccc;' >Unit No $mandatory</td>
                        <td style='border: 1px solid #ccc;'>
                        <input type='text' class='form-control txt' name='txt_A_unitnumber' id='txt_A_unitnumber' value='$unitnumber' onkeypress='return AllowNumeric1(event);'>

                    </tr>
                    <tr>
                        <td class='dvtCellLabel' style='border: 1px solid #ccc;width:13%;'>Blocks</td>
                        <td style='border: 1px solid #ccc;'>
                        ".GetBlocks($block)."
                        </td>
                        <td class='dvtCellLabel' style='border: 1px solid #ccc;' >Floors</td>
                        <td style='border: 1px solid #ccc;'>
                        ".GetFloors($floor)."

                        </td>

                        <td class='dvtCellLabel' style='border: 1px solid #ccc;' >AC Type </td>
                        <td style='border: 1px solid #ccc;'>
                        ".GetAcType($actype)."
                        </td>
                    </tr>
                    <tr>
                        <td class='dvtCellLabel' style='border: 1px solid #ccc;width:13%;' >Furnishing</td>
                        <td style='border: 1px solid #ccc;'>
                        	".GetFurnishing($furnishing)."
                        </td>
                        <td class='dvtCellLabel' style='border: 1px solid #ccc;width:13%;' >Unit Views</td>
                        <td style='border: 1px solid #ccc;'>
                        	".GetUnitViews($unitviews)."
                        </td>
                        <td class='dvtCellLabel' style='border: 1px solid #ccc;width:13%;' >Unit Features$mandatory</td>
                        <td style='border: 1px solid #ccc;'>
                        	".GetUnitFeatures($unitfeatures)."
                        </td>
                      </tr>
                      <tr>
                       <td class='dvtCellLabel' style='border: 1px solid #ccc;width:13%;' >Pets Allowed</td>
                        <td style='border: 1px solid #ccc;'>
                        	".GetPetsAllowed($petsallowed)."
                        </td>
                        <td class='dvtCellLabel' style='border: 1px solid #ccc;width:13%;'>Unit Area SQF </td>
                        <td style='border: 1px solid #ccc;' >
                        <input type='text' class='form-control txt' name='txt_A_unitarea' id='txt_A_unitarea' value='$unitarea'>
                        <td class='dvtCellLabel' style='border: 1px solid #ccc;width:13%;' >Unit Status$mandatory</td>
                        <td style='border: 1px solid #ccc;'>
                        	".GetUnitStatus($unitstatus)."
                        </td>
                        </tr>
                        <tr>
                         <td class='dvtCellLabel' style='border: 1px solid #ccc;width:13%;'>Min Rent </td>
                        <td style='border: 1px solid #ccc;' >
                        <input type='text' onkeypress='return AllowNumeric1(event);' class='form-control txt' name='txt_A_minrent' id='txt_A_minrent' value='$minrent'>
                         <td class='dvtCellLabel' style='border: 1px solid #ccc;width:13%;'>Max Rent </td>
                        <td style='border: 1px solid #ccc;' >
                        <input type='text' onkeypress='return AllowNumeric1(event);' class='form-control txt' name='txt_A_maxrent' id='txt_A_maxrent' value='$maxrent'>


                        <td style='border: 0px solid #ccc;text-align:right;'colspan=2 >
                        ".$Save_button."
                        <input type='hidden' class=textboxcombo name='txt_A_propertyid' id='txt_A_propertyid' value='".$PARENTID."'>
                        <input type=hidden id=child name=child value='child'>
                        <input type=hidden id=childid name=childid value='".$CHILDID."'>
                        </td>
                    </tr>
                </table> </div></div> </form>";

if(($insert == "true" && $CHILDID =="") || ($update == "true"))
echo $entrydata;



?>


 </div>
<div class="tab-pane" id="furniture">
<iframe id="frame3" name="frame3" scrolling="no" onload='this.width=screen.width;this.height=screen.height;'frameborder="0" style="position: relative; width: 100%; "></iframe>
</div>

<div class="tab-pane" id="documents">
<iframe id="frame4" name="frame4" scrolling="no" onload='this.width=screen.width;this.height=screen.height;'frameborder="0" style="position: relative; width: 100%; "></iframe>
 </div>

 <div class="tab-pane" id="photos">
 <iframe id="frame5" name="frame5" scrolling="no" onload='this.width=screen.width;this.height=screen.height;'frameborder="0" style="position: relative; width: 100%; "></iframe>
 </div>

  </div>
  </div>


</section>


<?php
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
function getUpFileName($invoiceupload,$foldername){
                 if($invoiceupload!=""){
                            $str = explode("$$$",$invoiceupload);
                            $str = substr($invoiceupload,(strlen($str[0])+3));

                            $actdocname1= str_replace(" ","%20",$invoiceupload);
                            $ext = strtolower(pathinfo($invoiceupload, PATHINFO_EXTENSION));
                            $invoiceupload_dwl = "<a href='$foldername/$invoiceupload' target='_blank'><i class='fa fa-eye' data-toggle='tooltip' data-placement='right' title='View' aria-hidden='true'></i></a>
                                     &nbsp;&nbsp;".$str."<br>
                                     <a href='download.php?folder=$foldername&filename=".$invoiceupload."'><i class='fa fa-download' data-toggle='tooltip' data-placement='right' title='Download' aria-hidden='true'></i></a>
                                      &nbsp;&nbsp;".$str;
                            /*$invoiceupload_dwlxxxx = $str."<a href='#' onclick='loadframe(\"".$ext."\",\"".$invoiceupload."\",\"".$foldername."\");' data-toggle='modal' data-target='#myModal'><i class='fa fa-eye' data-toggle='tooltip' data-placement='right' title='View' aria-hidden='true'></i></a>
                                      &nbsp;&nbsp;<a  href='download.php?folder=$foldername&filename=".$invoiceupload."'><i class='fa fa-download' data-toggle='tooltip' data-placement='right' title='Download' aria-hidden='true'></i></a>
                                      &nbsp;&nbsp;";*/

                }else{
                            $invoiceupload_dwl = "";
                }
                return $invoiceupload_dwl;
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
function GetRenumber($renumber){
	global $con;
    $CMB = " <select name='cmb_A_renumber'  id='cmb_A_renumber' class='form-control select'>";
    $CMB .= "<option value=''>Select</option>";
	$SEL =  "Select lookcode,lookname from in_lookup where looktype='RENUMBER' and lookname<>'XX'";
	$RES = mysqli_query($con,$SEL);
	while ($ARR = mysqli_fetch_array($RES)) {
	   $SEL = "";
	   if($renumber == $ARR['lookcode']){ $SEL =  "SELECTED";}
	   $CMB .= "<option value='".trim($ARR['lookcode'])."' $SEL >".trim($ARR['lookname'])."</option>";
	}
	$CMB .= "</select>";
	return $CMB;
}
function GetUnitType($unittype){
	global $con;
    $CMB = " <select name='cmb_A_unittype'  id='cmb_A_unittype' class='form-control select'>";
    $CMB .= "<option value=''>Select</option>";
	$SEL =  "Select id,unitname from tbl_unittype";
	$RES = mysqli_query($con,$SEL);
	while ($ARR = mysqli_fetch_array($RES)) {
	   $SEL = "";
	   if($unittype == $ARR['id']){ $SEL =  "SELECTED";}
	   $CMB .= "<option value='".trim($ARR['id'])."' $SEL >".trim($ARR['unitname'])."</option>";
	}
	$CMB .= "</select>";
	return $CMB;
}
function GetBlocks($block){
	global $con;
    $CMB = " <select name='cmb_A_block'  id='cmb_A_block' class='form-control select'>";
    $CMB .= "<option value=''>Select</option>";
	$SEL =  "Select id,blockname from tbl_blocks";
	$RES = mysqli_query($con,$SEL);
	while ($ARR = mysqli_fetch_array($RES)) {
	   $SEL = "";
	   if($block == $ARR['id']){ $SEL =  "SELECTED";}
	   $CMB .= "<option value='".trim($ARR['id'])."' $SEL >".trim($ARR['blockname'])."</option>";
	}
	$CMB .= "</select>";
	return $CMB;
}
function GetAcType($actype){
	global $con;
    $CMB = " <select name='cmb_A_actype'  id='cmb_A_actype' class='form-control select'>";
    $CMB .= "<option value=''>Select</option>";
	$SEL =  "Select id,actype from tbl_actype";
	$RES = mysqli_query($con,$SEL);
	while ($ARR = mysqli_fetch_array($RES)) {
	   $SEL = "";
	   if($actype == $ARR['id']){ $SEL =  "SELECTED";}
	   $CMB .= "<option value='".trim($ARR['id'])."' $SEL >".trim($ARR['actype'])."</option>";
	}
	$CMB .= "</select>";
	return $CMB;
}
function GetFloors($floor){
	global $con;
    $CMB = " <select name='cmb_A_floor'  id='cmb_A_floor' class='form-control select'>";
    $CMB .= "<option value=''>Select</option>";
	$SEL =  "Select id,floorname from tbl_floors";
	$RES = mysqli_query($con,$SEL);
	while ($ARR = mysqli_fetch_array($RES)) {
	   $SEL = "";
	   if($floor == $ARR['id']){ $SEL =  "SELECTED";}
	   $CMB .= "<option value='".trim($ARR['id'])."' $SEL >".trim($ARR['floorname'])."</option>";
	}
	$CMB .= "</select>";
	return $CMB;
}
function GetFurnishing($furnishing){
	global $con;
    $CMB = " <select name='cmb_A_furnishing'  id='cmb_A_furnishing' class='form-control select'>";
    $CMB .= "<option value=''>Select</option>";
	$SEL =  "Select lookcode,lookname from in_lookup where looktype='FURNISHING TYPE' and lookname<>'XX'";
	$RES = mysqli_query($con,$SEL);
	while ($ARR = mysqli_fetch_array($RES)) {
	   $SEL = "";
	   if($furnishing == $ARR['lookcode']){ $SEL =  "SELECTED";}
	   $CMB .= "<option value='".trim($ARR['lookcode'])."' $SEL >".trim($ARR['lookname'])."</option>";
	}
	$CMB .= "</select>";
	return $CMB;
}
function GetUnitViews($unitview){
	global $con;
    $CMB = " <select name='cmb_A_unitview'  id='cmb_A_unitview' class='form-control select'>";
    $CMB .= "<option value=''>Select</option>";
	$SEL =  "Select id,unitview from tbl_unitviews";
	$RES = mysqli_query($con,$SEL);
	while ($ARR = mysqli_fetch_array($RES)) {
	   $SEL = "";
	   if($unitview == $ARR['id']){ $SEL =  "SELECTED";}
	   $CMB .= "<option value='".trim($ARR['id'])."' $SEL >".trim($ARR['unitview'])."</option>";
	}
	$CMB .= "</select>";
	return $CMB;
}
/*function GetUnitfeatures($unitfeatures){
	global $con;
    $CMB = " <select name='cmb_A_unitfeatures'  id='cmb_A_unitfeatures' class='form-control select'>";
    $CMB .= "<option value=''>Select</option>";
	$SEL =  "Select lookcode,lookname from in_lookup where looktype='UNIT FEATURES' and lookname<>'XX'";
	$RES = mysqli_query($con,$SEL);
	while ($ARR = mysqli_fetch_array($RES)) {
	   $SEL = "";
	   if($unitfeatures == $ARR['lookcode']){ $SEL =  "SELECTED";}
	   $CMB .= "<option value='".trim($ARR['lookcode'])."' $SEL >".trim($ARR['lookname'])."</option>";
	}
	$CMB .= "</select>";
	return $CMB;
} */
function GetUnitfeatures($unitfeatures){
         global $con;
		$mycontrol = "<div id='divcheckbox'  class='form-group' style='max-height:100px;overflow-y:scroll;'>";
		$menucode_arr = "'".str_replace(",","','",$unitfeatures)."'";

			$SEL =  "select id,unitfeatures from tbl_unitfeatures";
	        $RES = mysqli_query($con,$SEL);
	        while($ARR = mysqli_fetch_array($RES)){
	        	if (strpos("-," . $unitfeatures.",",",".$ARR['id'].",")>0)
	        	{ $SEL =  "checked";}
	        	else $SEL = '';
				$mycontrol .= "<input type='checkbox' class='minimal inputs' id='unitfeatures' $SEL  name='unitfeatures[]' value='".$ARR['id']."'/>&nbsp;" . $ARR['unitfeatures']. "<br>";

		}
    	return $mycontrol."</div>";
}
function GetPetsAllowed($petsallowed){
	global $con;
    $CMB = " <select name='cmb_A_petsallowed'  id='cmb_A_petsallowed' class='form-control select'>";
    $CMB .= "<option value=''>Select</option>";
	$SEL =  "Select lookcode,lookname from in_lookup where looktype='YESNO' and lookname<>'XX'";
	$RES = mysqli_query($con,$SEL);
	while ($ARR = mysqli_fetch_array($RES)) {
	   $SEL = "";
	   if($petsallowed == $ARR['lookcode']){ $SEL =  "SELECTED";}
	   $CMB .= "<option value='".trim($ARR['lookcode'])."' $SEL >".trim($ARR['lookname'])."</option>";
	}
	$CMB .= "</select>";
	return $CMB;
}
function GetUnitStatus($unitstatus){
	global $con;
    $CMB = " <select name='cmb_A_unitstatus'  id='cmb_A_unitstatus' class='form-control select'>";
    $CMB .= "<option value=''>Select</option>";
	$SEL =  "Select lookcode,lookname from in_lookup where looktype='UNIT STATUS'";
	$RES = mysqli_query($con,$SEL);
	while ($ARR = mysqli_fetch_array($RES)) {
	   $SEL = "";
	   if($unitstatus == $ARR['lookcode']){ $SEL =  "SELECTED";}
	   $CMB .= "<option value='".trim($ARR['lookcode'])."' $SEL >".trim($ARR['lookname'])."</option>";
	}
	$CMB .= "</select>";
	return $CMB;
}
function GetLookName($lookcode){
	global $con;
	$SEL =  "Select lookname from in_lookup where lookcode=".$lookcode."";
	$RES = mysqli_query($con,$SEL);
	$ARR = mysqli_fetch_array($RES);
    $lookname= $ARR['lookname'];
	return $lookname;
}


?>
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
function loadpage(i){
    alert(i);
   if(i==2){
       document.frmEdit.action='popuppropertylist.php?dr=edit&CHILDID='+document.getElementById('childid').value&parentid='+document.getElementById('txt_A_propertyid').value;;
       document.frmEdit.submit();
   }
    if(i==3){
   var frame= document.getElementById('frame3');
   frame.src='furniture.php?PARENTID=<?php echo $_REQUEST['CHILDID']; ?>';
   frame.load();
   }
    if(i==4){
   var frame= document.getElementById('frame4');
   frame.src='upload_documents.php?entitytype=COMPANY_DOCUMENTS&ID=<?php echo $_REQUEST['parentid']; ?>';
   frame.load();
   }
    if(i==5){
   var frame= document.getElementById('frame5');
   frame.src='propertyphotos.php?ID=<?php echo $$CHILDID; ?>';
   frame.load();
   }
}
</script>


