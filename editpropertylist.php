<?php
session_start();
//$_SESSION['pr'] = isset($_REQUEST['pr']) ? $_REQUEST['pr'] : '';
require "connection.php";
require "pagingObj.php";
//include "functions_workflow.php";
//print_r($_REQUEST);
$grid = new MyPHPGrid('frmPage');
$grid->TableName = "tbl_property";
$grid->formName = "propertylist.php";
$grid->SyncSession($grid);
$grid->TableNameChild = "tbl_propertydetails";

$insert = $update = $delete = "false";

if(false !== strpos($_SESSION['pr'],"I")) $insert = "true" ;
if(false !== strpos($_SESSION['pr'],"U")) $update = "true" ;
if(false !== strpos($_SESSION['pr'],"D")) $delete = "true" ;

$PARENTID = isset($_REQUEST['PARENTID']) ? $_REQUEST['PARENTID'] : '';
$CHILDID = isset($_REQUEST['CHILDID']) ? $_REQUEST['CHILDID'] : '';
$UPDATE = isset($_REQUEST['UPDATE']) ? $_REQUEST['UPDATE'] : '';



$DEL = isset($_REQUEST['DEL']) ? $_REQUEST['DEL'] : '';

$display="none";
if($_REQUEST['ID'] != "0") {
             $mode=$_REQUEST['ID'];
             $saveid=$_REQUEST['ID'];

             $SQL = " Select * from tbl_property where id='".$_REQUEST['ID']."'";
             $SQLRes =  mysqli_query($con,$SQL) or die(mysqli_error()."<br>".$SQL);
              if(mysqli_num_rows($SQLRes)>=1){
                while($loginResultArray   = mysqli_fetch_array($SQLRes)){
                

                   $id = $loginResultArray['id'];
                   $company = $loginResultArray['company'];
                   $ownership = $loginResultArray['ownership'];
                   $propertyowner = $loginResultArray['propertyowner'];
                   $propertytype = $loginResultArray['propertytype'];
                   $propertyname = $loginResultArray['propertyname'];
                   $constructiontype = $loginResultArray['constructiontype'];
                   $noofblocks = $loginResultArray['noofblocks'];
                   $nooffloors = $loginResultArray['nooffloors'];
                   $amenities = $loginResultArray['amenities'];
                   $country = $loginResultArray['country'];
                   $emirate = $loginResultArray['emirate'];
                   $city = $loginResultArray['city'];
                   $latitude = $loginResultArray['latitude'];
                   $longitude = $loginResultArray['longitude'];
                   $photo = $loginResultArray['photo'];
                   $sql1="select count(id) as noofunits from tbl_propertydetails where propertyid='".$_REQUEST['ID']."'";
                   $SQLRes1 =  mysqli_query($con,$sql1) or die(mysqli_error()."<br>".$Sql1);
                   $loginResultArray1   = mysqli_fetch_array($SQLRes1);
                   $noofunits= $loginResultArray1['noofunits'];
                   $foldername = "uploads";
                   
                  }
              }
           }else{
             $mode="";
             $saveid = GetLastSqeID("tbl_property");
             $id="";
             $company = "";
             $propertyname = "";
             $ownership = "";
             $propertytype = "";
             $constructiontype = "";
             $noofblocks = "";
             $nooffloors = "";
             $amenities = "";
             $country = "";
             $emirate = "";
             $city = "";
             $latitude = "";
             $longitude = "";
             $photo = "";
             $noofunits = "";
             $propertyowner="";
             $foldername = "";
}

if(isset($_REQUEST['dr'])=='view'){
   $edit="none";
   $view="inline";
   $title="Viewing : $propertyname";
}else if(isset($_REQUEST['dr'])=='edit'){
      $edit="inline";
      $view="none";
      $title="Editing : $propertyname";
}else{
      $edit="inline";
      $view="none";
      $title="Adding Property";
}
 if($CHILDID !='' && $DEL =='DELETE'){
        mysqli_query($con,"delete from tbl_propertydetails where id='". $CHILDID."'");
        //$CHILDID ="";

}

     $save = "<a href='javascript:editingChildrecord();'><img src='ico/Save-icon.png' title='Save' width='20' height='20'></a>
                                              &nbsp;&nbsp;<a href='?ID=".$_REQUEST['ID']."'><img src='ico/cancel.png' title='Cancel' width='20' height='20'></a>";
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
function getEmirates(cattype){
	xmlHttp=GetXmlHttpObject()
	if (xmlHttp==null)
	{
	         alert ("Browser does not support HTTP Request")
	         return
	}

	var url="combofunctions_company.php?level=AllEmirates&countryid="+cattype;
	xmlHttp.onreadystatechange=stateChangedcombo_emirates
	xmlHttp.open("POST",url,true)
	xmlHttp.send(null)
}
function stateChangedcombo_emirates(){
       if (xmlHttp.readyState==4 || xmlHttp.readyState=="complete")
       {
             var s1 = trim(xmlHttp.responseText);
             document.getElementById('cmb_A_emirate').innerHTML=s1;
       }
}


function getCities(cattype){
	xmlHttp=GetXmlHttpObject()
	if (xmlHttp==null)
	{
	         alert ("Browser does not support HTTP Request")
	         return
	}

	var url="combofunctions_company.php?level=AllCities&emirateid="+cattype;
	xmlHttp.onreadystatechange=stateChangedcombo_city
	xmlHttp.open("POST",url,true)
	xmlHttp.send(null)
}
function stateChangedcombo_city(){
       if (xmlHttp.readyState==4 || xmlHttp.readyState=="complete")
       {
             var s1 = trim(xmlHttp.responseText);
             document.getElementById('cmb_A_city').innerHTML=s1;
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

       var txt_A_propertyname=document.getElementById('txt_A_propertyname');
       if(txt_A_propertyname){
          if ((txt_A_propertyname.value==null)||(txt_A_propertyname.value=="")){
               alertify.alert("Enter Property Name", function () {
               txt_A_propertyname.focus();

          });
             return;
          }
       }
       var cmb_A_constructiontype=document.getElementById('cmb_A_constructiontype');
       if(cmb_A_constructiontype){
          if ((cmb_A_constructiontype.value==null)||(cmb_A_constructiontype.value=="")){
               alertify.alert("Enter Construction Type", function () {
               cmb_A_constructiontype.focus();

          });
             return;
          }
       }
       var cmb_A_propertyowner=document.getElementById('cmb_A_propertyowner');
       if(cmb_A_propertyowner){
          if ((cmb_A_propertyowner.value==null)||(cmb_A_propertyowner.value=="")){
               alertify.alert("Enter Property Owner", function () {
               cmb_A_propertyowner.focus();

          });
             return;
          }
       }
       var cmb_A_ownership=document.getElementById('cmb_A_ownership');
       if(cmb_A_ownership){
          if ((cmb_A_ownership.value==null)||(cmb_A_ownership.value=="")){
               alertify.alert("Enter Ownership", function () {
               cmb_A_ownership.focus();

          });
             return;
          }
       }
       var cmb_A_country=document.getElementById('cmb_A_country');
       if(cmb_A_country){
          if ((cmb_A_country.value==null)||(cmb_A_country.value=="")){
               alertify.alert("Enter Country", function () {
               cmb_A_country.focus();

          });
             return;
          }
       }
       var cmb_A_emirate=document.getElementById('cmb_A_emirate');
       if(cmb_A_emirate){
          if ((cmb_A_emirate.value==null)||(cmb_A_emirate.value=="")){
               alertify.alert("Enter Emirate", function () {
               cmb_A_emirate.focus();

          });
             return;
          }
       }
       var cmb_A_city=document.getElementById('cmb_A_city');
       if(cmb_A_city){
          if ((cmb_A_city.value==null)||(cmb_A_city.value=="")){
               alertify.alert("Enter City", function () {
               cmb_A_city.focus();

          });
             return;
          }
       }
       chks = document.getElementsByName('amenities[]');

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
		       alertify.alert("Select Amenities", function () {
		       amenities.focus();

		  });
		     return;
		  }
		}
	   menus =menus.slice(0,-1)	;
       
       
       var parameter =get(document.frmEdit)+'txt_A_amenities='+menus;

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
                                 window.location.href='editpropertylist.php?dr=edit&ID='+document.getElementById('saveid').value;
                                });
                               }else if(s1.toString() == s3.toString()){
                                alertify.alert("Record Updated", function () {
                                window.location.href='editpropertylist.php?dr=edit&ID='+document.getElementById('mode').value;

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
                                 window.location.href='editpropertylist.php?dr=add&ID=0';
                                });
                               }else if(s1.toString() == s3.toString()){
                                alertify.alert("Record Updated", function () {
                                window.location.href='editpropertylist.php?dr=add&ID=0';

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
                                window.location.href='propertylist.php?ID=0&pr=<?php echo $_SESSION["pr"]; ?>';
                               }else if(s1.toString() == s3.toString()){
                                alertify.alert("Record Updated", function () {
                                window.location.href='propertylist.php?pr=<?php echo $_SESSION["pr"]; ?>';

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
                   
                   
function Filevalidation(fileid) {
        const fi = document.getElementById(fileid);
        // Check if any file is selected.
        if (fi.files.length > 0) {
            for (const i = 0; i <= fi.files.length - 1; i++) {

                const fsize = fi.files.item(i).size;
                const file = Math.round((fsize / 1024));
                // The size of the file.
                if (file > 2048) {
                    alertify.alert( fi.files.item(i).name +"   "+
                      "File too Big, please select a file less than 2mb");

                }
                else{
					return;
				}
            }
        }
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
              document.frmChildEdit.action='editpropertylist.php?ID='+document.getElementById('txt_A_propertyid').value;
              document.frmChildEdit.submit();
             });
           }else if(s1.toString() == s3.toString()){
            alertify.alert("Record Updated", function () {
              document.frmChildEdit.action='editpropertylist.php?ID='+document.getElementById('txt_A_propertyid').value;
              document.frmChildEdit.submit();
             });
           }else{
            alertify.alert(s1);
           }

     }

}
function AddItem(){

         var tr1=document.getElementById('tr1');
         tr1.style.display="table-row";
         var tr2=document.getElementById('tr2');
         tr2.style.display="table-row";
         $('#btnshow').toggle('hide');
         $('#btnwarning').toggle('hide');
         $('#btnsuccess').toggle('hide');
}
function updateChildrecord(childid){
    document.frmChildEdit.action='popupaddpropertydetails.php?UPDATE=UPDATE&CHILDID='+childid+'&PARENTID='+document.getElementById('txt_A_propertyid').value;
    document.frmChildEdit.submit();

}
function deleteChildrecord(childid){

        alertify.confirm("Are you sure you want to delete ?", function (e) {
         if (e) {
           document.frmChildEdit.action='editpropertylist.php?DEL=DELETE&CHILDID='+childid+'&ID='+document.getElementById('txt_A_propertyid').value;
           document.frmChildEdit.submit();
         } else {
            return;
         }

       });

}
function popupaddpropertydetails(parentid,childid,mode){

$('#myModal46').modal({backdrop: 'static', keyboard: false});
var v1 ="popuppropertylist.php?PARENTID="+parentid+'&CHILDID='+childid+"&MODE="+mode;
document.getElementById('myframenew2').src=v1;

}
</script>
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

                 <a class="pull-left" href="propertylist.php?ps=1&pr=<?php echo $_SESSION['pr'];?>" data-toggle="tooltip" data-placement="right" title="Back to Menu Setup"><i class='fa fa-backward'></i></a>
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
                           <li class="active"><a href="#personal" onclick='javascript:loadpage(2);' data-toggle="tab"><i class="fa fa-desktop" aria-hidden="true"></i>&nbsp; Property Details</a></li>
                           <?php
                           if($_REQUEST['ID'] != "0") {
                           	?>
                           <li><a href="#ownership"   onclick='javascript:loadpage(3);' data-toggle="tab"><i class="fa fa-list" aria-hidden="true"></i>&nbsp; Ownership</a></li>
                           <li><a href="#documents"   onclick='javascript:loadpage(4);' data-toggle="tab"><i class="fa fa-list" aria-hidden="true"></i>&nbsp; Documents</a></li>
                           <li><a href="#photos"   onclick='javascript:loadpage(5);' data-toggle="tab"><i class="fa fa-list" aria-hidden="true"></i>&nbsp; Photos</a></li>
                           <?php }
                           ?>
                        </ul>





                        <div class="tab-content" id='tab-content-id'>
                        <div class="tab-pane" id="ownership">
		                <iframe id="frame3" name="frame3" scrolling="no" onload='this.width=screen.width;this.height=screen.height;'frameborder="0" style="position: relative; width: 100%; "></iframe>
                        </div>
                        <div class="tab-pane" id="documents">
		                <iframe id="frame4" name="frame4" scrolling="no" onload='this.width=screen.width;this.height=screen.height;'frameborder="0" style="position: relative; width: 100%; "></iframe>
                        </div>
                        <div class="tab-pane" id="photos">
		                <iframe id="frame5" name="frame5" scrolling="no" onload='this.width=screen.width;this.height=screen.height;'frameborder="0" style="position: relative; width: 100%; "></iframe>
                        </div>
                        <div class="tab-pane active" id="personal">
                        <div class="box-body no-padding" id='box-body-id'>
                      <!--  <div class='table-responsive no-padding'> -->

                               
                               




<?php


         if($CHILDID !='' && $UPDATE=='UPDATE')
         {
          $display="table-row";
          $sql_1 = "select * from tbl_propertydetails where id='".$CHILDID."'";
          $res_1 = mysqli_query($con,$sql_1);

	//	if(mysqli_num_rows($res_1)>=1){
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



		}
		else{
			$maxrent=$minrent=$unitstatus=$unitarea=$petsallowed=$unitfeatures=$unitviews=$furnishing=$actype=$unitnumber=$unitname=$floor=$block=$unittype=$propertyid ="";

		}




		$mandatory = "<span class='mandatory'>&nbsp;*</span>";
		$modesave='SAVE';
        $add = "<a href='javascript:popupaddpropertydetails(\"".$_REQUEST['ID']."\",\"".$CHILDID."\",\"".$modesave."\");'><i class='fa fa-plus' aria-hidden='true' title='Add Unit'></i></a>";
        $entrydata  = " <form name='frmEdit' method='post' id='frmEdit' enctype='multipart/form-data'>
                             <table class='table table-bordered table-condensed table-fixed  table-responsive' style='table-layout:fixed'>
                                                            <tr>
                                                            <td class='dvtCellLabel' style='border: 1px solid #ccc;'>Company:</td>
                                                              <td style='border: 1px solid #ccc;'>".GetCompany($company)."</td>
                                                              <td class='dvtCellLabel' style='border: 1px solid #ccc;'>Ownership:$mandatory </td>
                                                        	  <td style='border: 1px solid #ccc;'>".GetOwnership($ownership)."</td>
                                                           	  <td class='dvtCellLabel' style='border: 1px solid #ccc;'>Construction Type$mandatory:</td>
                                                              <td style='border: 1px solid #ccc;'>".GetConstructionType($constructiontype)."</td>
                                                            </tr>
                                                              <td class='dvtCellLabel' style='border: 1px solid #ccc;'>Property Name$mandatory:</td>
                                                              <td style='width:20%;border: 1px solid #ccc;'><input type='text' class='form-control txt'  name='txt_A_propertyname' id='txt_A_propertyname'  value='$propertyname' ></td>
                                                              <!-- <td class='dvtCellLabel' style='border: 1px solid #ccc;'>Property Owner:$mandatory</td>
                                                              <td style='border: 1px solid #ccc;'>".GetPropertyOwner($propertyowner)."</td>-->
                                                              <td class='dvtCellLabel' style='border: 1px solid #ccc;'>No:Of Blocks</td>
                                                              <td style='border: 1px solid #ccc;'><input type='text' onkeypress='return AllowNumeric1(event)' class='form-control txt'  name='txt_A_noofblocks' id='txt_A_noofblocks'  value='$noofblocks' ></td>
                                                        	  <td class='dvtCellLabel' style='border: 1px solid #ccc;'>No:Of Floors </td>
                                                           	  <td style='border: 1px solid #ccc;'><input type='text' onkeypress='return AllowNumeric1(event)' class='form-control txt'  name='txt_A_nooffloors' id='txt_A_nooffloors'  value='$nooffloors' ></td>
                                                            </tr>
                                                            <tr>

                                                              <td class='dvtCellLabel' style='border: 1px solid #ccc;'>Amenities$mandatory:</td>
                                                              <td style='border: 1px solid #ccc;' colspan=5>".GetAmenities($amenities)."</td>
                                                            </tr>
                                                            <tr>
                                                            </tr>
                                                            <tr>
                                                                <td class='dvtCellLabel' style='border: 1px solid #ccc;'>Country:$mandatory</td>
                                                              	<td style='border: 1px solid #ccc;'>
                                                              		".GetCountry($country)."
                                                              	</td>
                                                                <td class='dvtCellLabel' style='border: 1px solid #ccc;'>Emirate:$mandatory</td>
                                                              	<td style='border: 1px solid #ccc;'>
                                                              		".GetEmirate($emirate,$country)."
                                                              	</td>
                                                                <td class='dvtCellLabel' style='border: 1px solid #ccc;'>City:$mandatory</td>
                                                                <td style='border: 1px solid #ccc;'>
                                                              	".GetCity($city,$emirate)."
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                            	<td class='dvtCellLabel' style='border: 1px solid #ccc;'>Latitude</td>
                                                              <td style='border: 1px solid #ccc;'>
                                                               <input type='text' class='form-control txt'  name='txt_A_latitude' id='txt_A_latitude'  value='$latitude' >
                                                              </td>
                                                            	<td class='dvtCellLabel' style='border: 1px solid #ccc;'>Longitude</td>
                                                              	<td style='border: 1px solid #ccc;'>
                                                               <input type='text' class='form-control txt'  name='txt_A_longitude' id='txt_A_longitude'  value='$longitude' >
                                                              	</td>
                                                               <!-- <td class='dvtCellLabel' style='border: 1px solid #ccc;'>Photo</td>
                                                              <td style='border: 1px solid #ccc;'>
                                                              <input type='hidden' name='MAX_FILE_SIZE'><input name='userfile' class='btn-file' style='width:210px' type='file' id='userfile' onchange='Filevalidation(\"userfile\")'>
                                                               ".getUpFileName($photo,$foldername)."</td> -->
                                                               <td class='dvtCellLabel' style='border: 1px solid #ccc;'>No:Of Units </td>
                                                              <td style='border: 1px solid #ccc;'><input type='text' readonly class='form-control txt'  name='txt_A_noofunits' id='txt_A_noofunits'  value='$noofunits' ></td>
                                                            </tr>

                                                              	<td style='text-align:right;'colspan=6>
                                                              	<button class='btn btn-success inputs' style='margin-top:-1px;' name='btnsuccess' type='button'  onclick ='javascript:editingrecord(\"save\");'>Save </font>&nbsp;<i class='fas fa-save' aria-hidden='true'></i></button>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$add
                                                              	</td>
                                                                <input type='hidden' name='mode' class=textboxcombo id='mode' value='$mode'>
                                                                <input type='hidden' name='modeid' class=textboxcombo id='modeid' value='save'>
                                                                <input type='hidden' name='saveid' class=textboxcombo id='saveid' value='$saveid'>
                                                            
                                                    </table>
                                                    


                                    ";




                  $entrydata.=" </form> " ;


             $entrydatatable ="<form name='frmChildEdit' method='post' id='frmChildEdit' enctype='multipart/form-data'>";
             $entrydatatable .= "<div style='height:420px;;width:100%;padding:0px;border:1px solid #fff;'>";

             $entrydatatable .= "<table class='table table-bordered table-condensed table-fixed table-striped table-responsive' style='border:1px #2F3C43;table-layout:fixed;'>";
             $entrydatatable.="<thead><tr>";
             $entrydatatable.= "<th class='bg-light-blue' style='width:5%;text-align:left;border-bottom:1px #2F3C43 solid;border-top:1px #fff solid;color:#FFFFFF'>SL:No </th>";
             $entrydatatable.= "<th class='bg-light-blue' style='width:10%;text-align:left;border-bottom:1px #2F3C43 solid;border-top:1px #fff solid;color:#FFFFFF'>Unit Name$mandatory</th>";
             $entrydatatable.= "<th class='bg-light-blue' style='width:10%;text-align:left;border-bottom:1px #2F3C43 solid;border-top:1px #fff solid;color:#FFFFFF'>Unit Type$mandatory</th>";
             $entrydatatable.= "<th class='bg-light-blue' style='width:10%;text-align:left;border-bottom:1px #2F3C43 solid;border-top:1px #fff solid;color:#FFFFFF'>Unit No$mandatory</th>";
             $entrydatatable.= "<th class='bg-light-blue' style='width:10%;text-align:left;border-bottom:1px #2F3C43 solid;border-top:1px #fff solid;color:#FFFFFF'>Block</th>";
             $entrydatatable.= "<th class='bg-light-blue' style='width:10%;text-align:left;border-bottom:1px #2F3C43 solid;border-top:1px #fff solid;color:#FFFFFF'>Floor Type</th>";
             $entrydatatable.= "<th class='bg-light-blue' style='width:10%;text-align:left;border-bottom:1px #2F3C43 solid;border-top:1px #fff solid;color:#FFFFFF'>AC Type</th>";
   //          $entrydatatable.= "<th class='bg-light-blue' style='width:6%;text-align:left;border-bottom:1px #2F3C43 solid;border-top:1px #fff solid;color:#FFFFFF'>Furnishing</th>";
             $entrydatatable.= "<th class='bg-light-blue' style='width:10%;text-align:left;border-bottom:1px #2F3C43 solid;border-top:1px #fff solid;color:#FFFFFF'>Unit Views</th>";
              $entrydatatable.= "<th class='bg-light-blue' style='width:6%;text-align:left;border-bottom:1px #2F3C43 solid;border-top:1px #fff solid;color:#FFFFFF'>Pets Allowed</th>";
             $entrydatatable.= "<th class='bg-light-blue' style='width:10%;text-align:left;border-bottom:1px #2F3C43 solid;border-top:1px #fff solid;color:#FFFFFF'>Unit Area</th>";
             $entrydatatable.= "<th class='bg-light-blue' style='width:10%;text-align:left;border-bottom:1px #2F3C43 solid;border-top:1px #fff solid;color:#FFFFFF'>Unit Status$mandatory</th>";
             $entrydatatable.= "<th class='bg-light-blue' style='width:10%;text-align:left;border-bottom:1px #2F3C43 solid;border-top:1px #fff solid;color:#FFFFFF'>Action</th>";

             $modeupdate='UPDATE';
            $sql = "Select * from tbl_propertydetails where propertyid='".$_REQUEST['ID']."'";
              //$sql = $sql. " LIMIT $start1, $limit1";
              $result = mysqli_query($con,$sql) or die(mysqli_error());
              $slno=0;

        while($loginResultArrayChild   = mysqli_fetch_array($result)){
              $slno++;
             $entrydatatable.= "<tr>";
             $entrydatatable.= "<td style='width:5%;text-align:left;border-top:0px #fff solid;'>".$slno."</td>";
             $entrydatatable.= "<td style='width:10%;text-align:left;border-top:0px #fff solid;'>".$loginResultArrayChild['unitname']."</td>";
             $entrydatatable.= "<td style='width:10%;text-align:left;border-top:0px #fff solid;'>" . GetUnitTypeName($loginResultArrayChild['unittype']). "</td>";
             $entrydatatable.= "<td style='width:10%;text-align:left;border-top:0px #fff solid;'>".$loginResultArrayChild['unitnumber']."</td>";
             $entrydatatable.= "<td style='width:10%;text-align:left;border-top:0px #fff solid;'>" . GetBlockName($loginResultArrayChild['block']). "</td>";
             $entrydatatable.= "<td style='width:10%;text-align:left;border-top:0px #fff solid;'>" . GetFloorName($loginResultArrayChild['floor']). "</td>";
             $entrydatatable.= "<td style='width:10%;text-align:left;border-top:0px #fff solid;'>" . GetAcTypeName($loginResultArrayChild['actype']). "</td>";
            // $entrydatatable.= "<td style='width:6%;text-align:left;border-top:0px #fff solid;'>" . GetComboName($loginResultArrayChild['furnishing'],'tbl_furnishingtype','furnishingtype'). " </td>";
             $entrydatatable.= "<td style='width:10%;text-align:left;border-top:0px #fff solid;'>" . GetUnitViewName($loginResultArrayChild['unitview']). "</td>";
             $entrydatatable.= "<td style='width:10%;text-align:left;border-top:0px #fff solid;'>" . GetLookName($loginResultArrayChild['petsallowed']). "</td>";
             $entrydatatable.= "<td style='width:10%;text-align:left;border-top:0px #fff solid;'>" . $loginResultArrayChild['unitarea']. "</td>";
             $entrydatatable.= "<td style='width:10%;text-align:left;border-top:0px #fff solid;'>" . GetLookName($loginResultArrayChild['unitstatus']). "</td>";

             $entrydatatable.= "<td style='width:10%;text-align:left;border-top:0px #fff solid;'><a href='javascript:popupaddpropertydetails(\"".$_REQUEST['ID']."\",\"".$loginResultArrayChild['id']."\",\"".$modeupdate."\");'><img src='ico/edit.png' title='Update' width='16' height='16'></a>&nbsp;<a href='javascript:deleteChildrecord(\"".$loginResultArrayChild['id']."\");'><img src='ico/delete.ico' title='Remove' width='16' height='16'></a></td>";
             //$entrydatatable.= "<td style='width:6%;text-align:left;border-top:0px #fff solid;'></td>";

            }
            $entrydatatable.= " <input type='hidden' class=textboxcombo name='txt_A_propertyid' id='txt_A_propertyid' value='".$_REQUEST['ID']."'>
                        <input type=hidden id=child name=child value='child'>
                        <input type=hidden id=childid name=childid value='".$CHILDID."'></tbody></table></form>";
           $entrydatatable.= "</div>";
      echo $entrydata;

      echo $entrydatatable;
?>

                 </div>



		          
		          
                 <div id="myModal46" class="modal fade" >
    <!--<div class="modal-dialog" style="align:left;width:950px"> -->
        <div class="modal-content" >
            <div class="modal-header" style='height:40px;'>
                <span aria-hidden='true' style="float: left"><h4 id="modalTitle" class="modal-title">Add Property Info</h4></span><button type='button' class='close' data-dismiss='modal' aria-label='Close'>&times;</button>
            </div>
            <iframe id="myframenew2" name="myframenew2" scrolling="no"  src=""   frameborder="0" style="position: relative; width: 100%;height:350px;"></iframe>
            <div class="modal-footer" >
                <!--<button class='btn btn-danger inputs' id='closebtn' name='closebtn' type='button'  >Close&nbsp;</button>-->
           </div>
        </div>
    </div>
  </div>

        </section>
        
</body>
</html>
<?php

function GetPropertyOwner($propertyowner){

	global $con;
	$CMB = " <div class='font-awesome'><select name='cmb_A_propertyowner'  id='cmb_A_propertyowner' class='form-control fa'>";

	$seqSQL = "select id ,ownername from tbl_propertyowner";
	$result=mysqli_query($con,$seqSQL) or die(mysqli_error()."<br>".$seqSQL);
	$CMB .= "<option value=''>Select</option>";
	while($ARR=mysqli_fetch_array($result)){
		$SEL = "";
		if(strtoupper($propertyowner)== strtoupper($ARR['id'])){ $SEL =  "SELECTED";}
               $CMB .= "<option class='fa' value='".$ARR['id']."' $SEL >".$ARR['ownername']."</option>";
    }
    $CMB .= "</select></div>";

    return $CMB;
}
function GetUnitType($unittype){
	global $con;
    $CMB = " <select name='cmb_A_unittype'  id='cmb_A_unittype' class='form-control select'>";
    $CMB .= "<option value=''>Select</option>";
	$SEL =  "Select lookcode,lookname from in_lookup where looktype='UNIT TYPE' and lookname<>'XX'";
	$RES = mysqli_query($con,$SEL);
	while ($ARR = mysqli_fetch_array($RES)) {
    $SEL = "";
	   if($unittype == $ARR['lookcode']){ $SEL =  "SELECTED";}
    $CMB .= "<option value='".trim($ARR['lookcode'])."' $SEL >".trim($ARR['lookname'])."</option>";
	}
 $CMB .= "</select>";
	return $CMB;
}
function GetEmirate($emirate,$country){
	global $con;
	$CMB = " <select name='cmb_A_emirate'  id='cmb_A_emirate' class='form-control select2' onChange='getCities(this.value)'>";
	$seqSQL = "select id  as lookcode,emirate as lookname from tbl_states where countryid='$country' order by emirate";
	$result=mysqli_query($con,$seqSQL) or die(mysqli_error()."<br>".$seqSQL);
	$CMB .= "<option value='' >Select Emirate</option>";
	while($ARR=mysqli_fetch_array($result)){
		$SEL = "";
		if(strtoupper($emirate)== strtoupper($ARR['lookcode'])){ $SEL =  "SELECTED";}
               $CMB .= "<option value='".strtoupper($ARR['lookcode'])."' $SEL >".$ARR['lookname']."</option>";
    }
    $CMB .= "</select>";
    return $CMB;
}
function GetCompany($company){

	global $con;
	$CMB = " <div class='font-awesome'><select name='cmb_A_company'  id='cmb_A_company' class='form-control fa'>";
	$CMB .= "<option>Select</option>";
	$seqSQL = "select id ,companyname from tbl_companysetup";
	$result=mysqli_query($con,$seqSQL) or die(mysqli_error()."<br>".$seqSQL);
	while($ARR=mysqli_fetch_array($result)){
		$SEL = "";
		if(strtoupper($company)== strtoupper($ARR['id'])){ $SEL =  "SELECTED";}
               $CMB .= "<option class='fa' value='".$ARR['id']."' $SEL >".$ARR['companyname']."</option>";
    }
    $CMB .= "</select></div>";

    return $CMB;
}
function GetCity($country,$emirate){
	global $con;
	$CMB = " <select name='cmb_A_city'  id='cmb_A_city' class='form-control select2'>";
	$seqSQL = "select id  as lookcode,city as lookname from tbl_city where emirateid='$emirate' order by city";
	$result=mysqli_query($con,$seqSQL) or die(mysqli_error()."<br>".$seqSQL);
	$CMB .= "<option value='' >Select City</option>";
	while($ARR=mysqli_fetch_array($result)){
		$SEL = "";
		if(strtoupper($country)== strtoupper($ARR['lookcode'])){ $SEL =  "SELECTED";}
               $CMB .= "<option value='".strtoupper($ARR['lookcode'])."' $SEL >".$ARR['lookname']."</option>";
    }
    $CMB .= "</select>";
    return $CMB;
}
function GetCountry($country){
	global $con;
	$CMB = " <select name='cmb_A_country'  id='cmb_A_country' class='form-control select2' onChange='getEmirates(this.value)'>";
	$seqSQL = "select id  as lookcode,country as lookname from tbl_country order by country";
	$result=mysqli_query($con,$seqSQL) or die(mysqli_error()."<br>".$seqSQL);
	$CMB .= "<option value='' >Select Country</option>";
	while($ARR=mysqli_fetch_array($result)){
		$SEL = "";
		if(strtoupper($country)== strtoupper($ARR['lookcode'])){ $SEL =  "SELECTED";}
               $CMB .= "<option value='".strtoupper($ARR['lookcode'])."' $SEL >".$ARR['lookname']."</option>";
    }
    $CMB .= "</select>";
    return $CMB;
}
function GetConstructionType($constructiontype){
	global $con;
	$CMB = " <select name='cmb_A_constructiontype'  id='cmb_A_constructiontype' class='form-control' >";
	
	$seqSQL = "select id ,constructiontype from tbl_constructiontype";
	$result=mysqli_query($con,$seqSQL) or die(mysqli_error()."<br>".$seqSQL);
	$CMB .= "<option value='' >Select</option>";
	while($ARR=mysqli_fetch_array($result)){
		$SEL = "";
		if(strtoupper($constructiontype)== strtoupper($ARR['id'])){ $SEL =  "SELECTED";}
               $CMB .= "<option value='".strtoupper($ARR['id'])."' $SEL >".$ARR['constructiontype']."</option>";
    }
    $CMB .= "</select>";
    return $CMB;
}
function GetAmenities($amenities){
         global $con;
		$mycontrol = "<div id='divcheckbox'  class='form-group' style='max-height:100px;'>";
		$menucode_arr = "'".str_replace(",","','",$amenities)."'";

			$SEL =  "select id,amenity  from tbl_amenities ";
	        $RES = mysqli_query($con,$SEL);
	        while($ARR = mysqli_fetch_array($RES)){
	        	if (strpos("-," . $amenities.",",",".$ARR['id'].",")>0)
	        	{ $SEL =  "checked";}
	        	else $SEL = '';
				$mycontrol .= "<input type='checkbox' class='minimal inputs' id='amenities' $SEL  name='amenities[]' value='".$ARR['id']."'/>&nbsp;" . $ARR['amenity']. "&nbsp;";

		}
    	return $mycontrol."</div>";
}

function GetOwnership($ownership){
	global $con;
	$CMB = " <select name='cmb_A_ownership'  id='cmb_A_ownership' class='form-control' >";

	$seqSQL = "select lookcode ,lookname from in_lookup where looktype='OWNERSHIP' and lookname<>'XX'";
	$result=mysqli_query($con,$seqSQL) or die(mysqli_error()."<br>".$seqSQL);
	$CMB .= "<option value='' >Select </option>";
	while($ARR=mysqli_fetch_array($result)){
		$SEL = "";
		if(strtoupper($ownership)== strtoupper($ARR['lookcode'])){ $SEL =  "SELECTED";}
               $CMB .= "<option value='".strtoupper($ARR['lookcode'])."' $SEL >".$ARR['lookname']."</option>";
    }
    $CMB .= "</select>";
    return $CMB;
}
function GetUnitStatus($unitstatus){
	global $con;
    $CMB = " <select name='cmb_A_unitstatus'  id='cmb_A_unitstatus' class='form-control select'>";
    $CMB .= "<option value=''>Select</option>";
	$SEL =  "Select lookcode,lookname from in_lookup where looktype='UNIT STATUS' and lookname<>'XX'";
	$RES = mysqli_query($con,$SEL);
	while ($ARR = mysqli_fetch_array($RES)) {
	   $SEL = "";
	   if($unitstatus == $ARR['lookcode']){ $SEL =  "SELECTED";}
	   $CMB .= "<option value='".trim($ARR['lookcode'])."' $SEL >".trim($ARR['lookname'])."</option>";
	}
	$CMB .= "</select>";
	return $CMB;
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
function GetUnitfeatures($unitfeatures){
         global $con;
		$mycontrol = "<div id='divcheckbox'  class='form-group' style='max-height:100px;overflow-y:scroll;'>";
		$menucode_arr = "'".str_replace(",","','",$unitfeatures)."'";

			$SEL =  "select lookcode,lookname from in_lookup where looktype = 'UNIT FEATURES' and lookname<>'XX'";
	        $RES = mysqli_query($con,$SEL);
	        while($ARR = mysqli_fetch_array($RES)){
	        	if (strpos("-," . $unitfeatures.",",",".$ARR['lookcode'].",")>0)
	        	{ $SEL =  "checked";}
	        	else $SEL = '';
				$mycontrol .= "<input type='checkbox' class='minimal inputs' id='unitfeatures' $SEL  name='unitfeatures[]' value='".$ARR['lookcode']."'/>&nbsp;" . $ARR['lookname']. "";

		}
    	return $mycontrol."</div>";
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
function GetLookName($lookcode){
	global $con;
	if($lookcode!='')
	{
	$SEL =  "Select lookname from in_lookup where lookcode=".$lookcode."";
	$RES = mysqli_query($con,$SEL);
	$ARR = mysqli_fetch_array($RES);
    $lookname= $ARR['lookname'];
    }
    else
    $lookname='';
	return $lookname;
}
function GetUnitTypeName($unittype){
	global $con;
	if($unittype!='')
	{
	$SEL =  "Select unitname from tbl_unittype where unitname=".$unittype."";
	$RES = mysqli_query($con,$SEL);
	$ARR = mysqli_fetch_array($RES);
    $unitname= $ARR['unitname'];
    }
    else
    $unitname='';
	return $unitname;
}
function GetBlockName($blockid){
	global $con;
	if($blockid!='')
	{
	$SEL =  "Select blockname from tbl_blocks where id=".$blockid."";
	$RES = mysqli_query($con,$SEL);
	$ARR = mysqli_fetch_array($RES);
    $blockname= $ARR['blockname'];
    }
    else
    $blockname='';
	return $blockname;
}
function GetUnitViewName($unitview){
	global $con;
	if($unitview!='')
	{
	$SEL =  "Select unitview from tbl_unitviews where id=".$unitview."";
	$RES = mysqli_query($con,$SEL);
	$ARR = mysqli_fetch_array($RES);
    $unitview= $ARR['unitview'];
    }
    else
    $unitview='';
	return $unitview;
}
function GetFloorName($floorid){
	global $con;
	if($floorid!='')
	{
	$SEL =  "Select floorname from tbl_floors where id=".$floorid."";
	$RES = mysqli_query($con,$SEL);
	$ARR = mysqli_fetch_array($RES);
    $floorname= $ARR['floorname'];
    }
    else
    $floorname='';
	return $floorname;
}
function GetAcTypeName($actypeid){
	global $con;
	if($actypeid!='')
	{
	$SEL =  "Select actype from tbl_actype where id=".$actypeid."";
	$RES = mysqli_query($con,$SEL);
	$ARR = mysqli_fetch_array($RES);
    $actype= $ARR['actype'];
    }
    else
    $actype='';
	return $actype;
}


function GetBlocks($block){
	global $con;
    $CMB = " <select name='cmb_A_block'  id='cmb_A_block' class='form-control select'>";
    $CMB .= "<option value=''>Select</option>";
	$SEL =  "Select lookcode,lookname from in_lookup where looktype='BLOCKS' and lookname<>'XX'";
	$RES = mysqli_query($con,$SEL);
	while ($ARR = mysqli_fetch_array($RES)) {
	   $SEL = "";
	   if($block == $ARR['lookcode']){ $SEL =  "SELECTED";}
	   $CMB .= "<option value='".trim($ARR['lookcode'])."' $SEL >".trim($ARR['lookname'])."</option>";
	}
	$CMB .= "</select>";
	return $CMB;
}
function GetFloors($floor){
	global $con;
    $CMB = " <select name='cmb_A_floor'  id='cmb_A_floor' class='form-control select'>";
    $CMB .= "<option value=''>Select</option>";
	$SEL =  "Select lookcode,lookname from in_lookup where looktype='FLOORS' and lookname<>'XX'";
	$RES = mysqli_query($con,$SEL);
	while ($ARR = mysqli_fetch_array($RES)) {
	   $SEL = "";
	   if($floor == $ARR['lookcode']){ $SEL =  "SELECTED";}
	   $CMB .= "<option value='".trim($ARR['lookcode'])."' $SEL >".trim($ARR['lookname'])."</option>";
	}
	$CMB .= "</select>";
	return $CMB;
}
function GetAcType($actype){
	global $con;
    $CMB = " <select name='cmb_A_actype'  id='cmb_A_actype' class='form-control select'>";
    $CMB .= "<option value=''>Select</option>";
	$SEL =  "Select lookcode,lookname from in_lookup where looktype='AC TYPE' and lookname<>'XX'";
	$RES = mysqli_query($con,$SEL);
	while ($ARR = mysqli_fetch_array($RES)) {
	   $SEL = "";
	   if($actype == $ARR['lookcode']){ $SEL =  "SELECTED";}
	   $CMB .= "<option value='".trim($ARR['lookcode'])."' $SEL >".trim($ARR['lookname'])."</option>";
	}
	$CMB .= "</select>";
	return $CMB;
}
function GetUnitViews($unitview){
	global $con;
    $CMB = " <select name='cmb_A_unitview'  id='cmb_A_unitview' class='form-control select'>";
    $CMB .= "<option value=''>Select</option>";
	$SEL =  "Select lookcode,lookname from in_lookup where looktype='UNIT VIEWS' and lookname<>'XX'";
	$RES = mysqli_query($con,$SEL);
	while ($ARR = mysqli_fetch_array($RES)) {
	   $SEL = "";
	   if($unitview == $ARR['lookcode']){ $SEL =  "SELECTED";}
	   $CMB .= "<option value='".trim($ARR['lookcode'])."' $SEL >".trim($ARR['lookname'])."</option>";
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
//alert(i);
   if(i==2){
       document.frmEdit.action='editpropertylist.php?dr=edit&ID='+document.getElementById('saveid').value;
       document.frmEdit.submit();
   }
    if(i==3){
   var frame= document.getElementById('frame3');
   frame.src='ownership.php?PARENTID=<?php echo $_REQUEST['ID']; ?>';
   frame.load();
   }
    if(i==4){
   var frame= document.getElementById('frame4');
   frame.src='upload_documents.php?entitytype=PROPERTY_DOCUMENTS&ID=<?php echo $_REQUEST['ID']; ?>';
   frame.load();
   }
    if(i==5){
   var frame= document.getElementById('frame5');
   frame.src='propertyphotos.php?ID=0&TYPE=PROPERTYPHOTOS&PARENTID=<?php echo $_REQUEST['ID']; ?>';
   frame.load();
   }
}
</script>
