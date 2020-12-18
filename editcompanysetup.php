<?php
session_start();

require "connection.php";
require "pagingObj.php";
include "functions_workflow.php";
//print_r($_REQUEST);
$WF = new WorkFlow($_SESSION['objectid']);
$pagerights = $WF->WorkflowPageRights($_REQUEST['ID'],'tbl_companysetup');
$Action_button = $WF->actionWorkflow($_REQUEST['ID'],'tbl_companysetup');

$_SESSION['pr'] = isset($pagerights) ? $pagerights : '';
//echo $_SESSION['pr'];
$insert = $update = $delete = "false";

if(false !== strpos($_SESSION['pr'],"I")) $insert = "true" ;
if(false !== strpos($_SESSION['pr'],"U")) $update = "true" ;
if(false !== strpos($_SESSION['pr'],"D")) $delete = "true" ;

$grid = new MyPHPGrid('frmPage');
$grid->TableName = "tbl_companysetup";
$grid->formName = "companysetup.php";
$grid->SyncSession($grid);
$grid->TableNameChild = "";


if($_REQUEST['ID'] != "0") {
             $saveid = $mode=$_REQUEST['ID'];

             $SQL = " Select *,DATE_FORMAT(accountstartsfrom,'%d-%m-%Y') as accountstartsfrom from tbl_companysetup where id='".$_REQUEST['ID']."'";
             $SQLRes =  mysqli_query($con,$SQL) or die(mysqli_error()."<br>".$SQL);
              if(mysqli_num_rows($SQLRes)>=1){
                while($loginResultArray   = mysqli_fetch_array($SQLRes)){
                   $companycode = $loginResultArray['companycode'];
                   $companyname = $loginResultArray['companyname'];
                   $companynameinarabic = $loginResultArray['companynameinarabic'];
                   $country = $loginResultArray['countryid'];
                   $emirate = $loginResultArray['emirateid'];
                   $city = $loginResultArray['cityid'];
                   $address = $loginResultArray['address'];
                   $telephone = $loginResultArray['telephone'];
                   $fax = $loginResultArray['fax'];
                   $email = $loginResultArray['email'];
                   $web = $loginResultArray['web'];
                   $facebook = $loginResultArray['facebook'];
                   $instagram = $loginResultArray['instagram'];
                   $linkedin = $loginResultArray['linkedin'];
                   $whatsapp = $loginResultArray['whatsapp'];
                   $molid = $loginResultArray['molid'];
                   $immigrationid = $loginResultArray['immigrationid'];
                   $prefix = $loginResultArray['prefix'];
                   $financialyearto = $loginResultArray['financialyearto'];
                   $financialyearfrom = $loginResultArray['financialyearfrom'];
                   $accountstartsfrom = $loginResultArray['accountstartsfrom'];
                   $currency = $loginResultArray['currency'];
                   $currencyfraction = $loginResultArray['currencyfraction'];
                   $currencysymbol = $loginResultArray['currencysymbol'];
                   $decimals = $loginResultArray['decimals'];
                   $logo1 = $loginResultArray['logo1'];
                   $logo2 = $loginResultArray['logo2'];
                   $logo3 = $loginResultArray['logo3'];
                   $wfstatus = $loginResultArray['wfstatus'];
                   $foldername = "uploads";
                   $workflowseq = $loginResultArray['workflowseq'];
                   $createdby = $loginResultArray['createdby'];
                   
                   
                  }// end of while loop
              }// end of if statement
           }else{
             $mode=$companycode=$companyname=$docname=$dwld="";
             $saveid = GetLastSqeID("tbl_companysetup");
             $companynameinarabic = $country = $emirate = $city = $address = $telephone = $fax = $email = $web = $facebook = $instagram = $linkedin = $whatsapp = $molid= $immigrationid= $prefix=  $foldername= $logo1 = $financialyearfrom= $financialyearto= $accountstartsfrom= $currency = $currencyfraction= $currencysymbol= $decimals = $logo1 = $logo2 = $logo3 = $workflowseq = $wfstatus ="";
             $createdby = $_SESSION['SESSuserID'];
             if($WF->checkWorkflow($_SESSION['objectid'],$_REQUEST['ID'],'tbl_companysetup') == "YES") $workflowseq=1;
}

if(isset($_REQUEST['dr'])=='view'){
   $edit="none";
   $view="readonly";
   $title="Viewing Group : $companyname";
}else if(isset($_REQUEST['dr'])=='edit'){
      $edit="inline";
      $view="";
      $title="Editing Group : $companyname";
}else{
      $edit="inline";
      $view="";
      $title="Adding New Group";
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
      <script type="text/javascript" src="js/timepicker.js"></script>
      <link rel="stylesheet" type="text/css" media="screen" href="css/my_styles2.css">
      <link rel="stylesheet" type="text/css" media="screen" href="css/my_styles1.css">
      <script src="plugins/inputmask/jquery.inputmask.bundle.min.js"></script>

<script language="javascript">
$(document).ready(function(){
$("#txt_A_fax").inputmask({"mask": "(999) 999-9999"});
});
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
             document.getElementById('cmb_A_emirateid').innerHTML=s1;
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
             document.getElementById('cmb_A_cityid').innerHTML=s1;
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

   $('#txt_A_companycode').css('border-color', '');
   $('#txt_A_companyname').css('border-color', '');
   $('#cmb_A_currency').css('border-color', '');
}
function editingrecord(action)
{

       var txt_A_companycode=document.getElementById('txt_A_companycode');
       if(txt_A_companycode){
          if ((txt_A_companycode.value==null)||(txt_A_companycode.value=="")){
               alertify.alert("Enter Company Code", function () {
               txt_A_companycode.focus();

          });
             return;
          }
       }
       
       var txt_A_companyname=document.getElementById('txt_A_companyname');
       if(txt_A_companyname){
          if ((txt_A_companyname.value==null)||(txt_A_companyname.value=="")){
               alertify.alert("Enter Company Name", function () {
               txt_A_companyname.focus();

          });
             return;
          }
       }
       
      /* var txt_A_companyname=document.getElementById('txt_A_companynameinarabic');
       if(txt_A_companyname){
          if ((txt_A_companyname.value==null)||(txt_A_companyname.value=="")){
               alertify.alert("Enter Company Name (in Arabic)", function () {
               txt_A_companyname.focus();

          });
             return;
          }
       }*/
       
      // Filevalidation('userfile');
      // Filevalidation('userfile1');
      // Filevalidation('userfile2');
       
     /* $('#overlay').show();
      $('#box-body-id').hide();*/
      /*if(document.getElementById('mode').value==null){

              document.getElementById('frmEdit').action='in_action.php'+get(document.frmEdit)+'action='+action;
              document.getElementById('frmEdit').submit();
              

       }else{

              document.getElementById('frmEdit').action='in_action.php'+get(document.frmEdit)+'action='+action;
              document.getElementById('frmEdit').submit();

       }
       return;*/
       insertfunction(get(document.frmEdit),action);
       return;
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
                                 window.location.href='editcompanysetup.php?dr=edit&ID='+document.getElementById('saveid').value+'&objectid=<?php echo $_SESSION["objectid"]; ?>';
                                });
                               }else if(s1.toString() == s3.toString()){
                                alertify.alert("Record Updated", function () {
                                window.location.href='editcompanysetup.php?dr=edit&ID='+document.getElementById('mode').value;

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
                                 window.location.href='editcompanysetup.php?dr=add&ID=0';
                                });
                               }else if(s1.toString() == s3.toString()){
                                alertify.alert("Record Updated", function () {
                                window.location.href='editcompanysetup.php?dr=add&ID=0';

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
                                window.location.href='companysetup.php?ID=0';
                               }else if(s1.toString() == s3.toString()){
                                alertify.alert('Record Updatedcxc');
                                window.location.href='companysetup.php';
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
</script>
<!--<style type="text/css">
#overlay
{
        position:absolute;
        width:auto;
        height:auto%;
}
#overlay img {
    display: block;
    margin-left: auto;
    margin-right: auto;
}
</style>-->
</head>
 <body class="hold-transition sidebar-mini">
<!--<div id='overlay'  style='display:none;border:5px;'>
    <img src='img/loading_wh.gif' style='margin-top:-5px;'  align='center' />
</div>-->
         <section class="content-header">

                 <a class="pull-left" href="companysetup.php?objectid=<?php echo $_SESSION['objectid']; ?>&pr=<?php echo $_SESSION['pr']; ?>&frmPage_rowcount=<?php echo $_SESSION['frmPage_rowcount']; ?>&txtsearch=<?php echo $_SESSION['txtsearch']; ?>&frmPage_startrow=<?php echo $_SESSION['frmPage_startrow'];?>" data-toggle="tooltip" data-placement="right" title="Back to Group Setup"><i class='fa fa-backward'></i></a>
                 <h2 class="title"><?php echo "&nbsp;&nbsp;".$title; ?></h2>

                <!-- <ol class='breadcrumb'>
                  <li><a href="#"><a href="blank.php" >Admin Setup</a></li>
                  <li><a href="#"><a href="companysetup.php?ps=1">Group</a></li>
                  <li class="active"><?php echo $title; ?></li>
                 </ol>-->

         </section>

         <section class="content" id='content-content-id' style='padding-right:5px;padding-left:5px;margin-top:-10px;' >

                  <div class="nav-tabs-custom" id="nav-tabs-custom-id">
                        <ul class="nav nav-tabs">
                           <li class="active"><a href="#personal" onclick='javascript:loadpage(2);' data-toggle="tab"><i class="fa fa-desktop" aria-hidden="true"></i>&nbsp; Company</a></li>
                           <?php 
                           if($_REQUEST['ID']!='0') {
						   ?>
                           <li><a href="#uploads" onclick='javascript:loadpage(3);' data-toggle="tab"><i class="fa fa-upload" aria-hidden="true"></i>&nbsp; Upload docs</a></li>
                           <li><a href="#sequencer" onclick='javascript:loadpage(4);' data-toggle="tab"><i class="fa fa-list-ol" aria-hidden="true"></i>&nbsp; Sequencer</a></li>
                           <li><a href="#logos" onclick='javascript:loadpage(5);' data-toggle="tab"><i class="fa fa-file-image-o" aria-hidden="true"></i>&nbsp; Logos</a></li>
                           <li><a href="#documents" onclick='javascript:loadpage(6);' data-toggle="tab"><i class="fa fa-folder-open" aria-hidden="true"></i>&nbsp; Documents</a></li>
                           <?php
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
            <table class='table table-bordered table-condensed table-fixed table-responsive' style='table-layout:fixed'>
                <tr>
                	<td class='dvtCellLabel' style='border: 1px solid #ccc;text-align:$wf_lable_align;'>".GetWfDictionary('Company Code').":$mandatory</td>
                    <td style='border: 1px solid #ccc;'><input type='text' class='form-control' onkeypress='return AllowNumeric1(event);' onkeyup='borderchange();'  name='txt_A_companycode' id='txt_A_companycode'  value='$companycode'></td>
                    <td class='dvtCellLabel' style='border: 1px solid #ccc;text-align:$wf_lable_align;'>".GetWfDictionary('Company Name').":$mandatory</td>
                    <td style='border: 1px solid #ccc;'><input type='text' class='form-control' name='txt_A_companyname' id='txt_A_companyname'  value='$companyname' ></td>
                    <td class='dvtCellLabel' style='border: 1px solid #ccc;'> Name (in Arabic):$mandatory</td>
                    <td style='border: 1px solid #ccc;'><input type='text' class='form-control' name='txt_A_companynameinarabic' id='txt_A_companynameinarabic'  value='$companynameinarabic' ></td>                
                </tr>
                <tr>
                	<td class='dvtCellLabel' style='border: 1px solid #ccc;'>Country:$mandatory</td>
                    <td style='border: 1px solid #ccc;'>".GetCountry($country)."</td>
                    <td class='dvtCellLabel' style='border: 1px solid #ccc;'>Emirate:$mandatory</td>
                    <td style='border: 1px solid #ccc;'>".GetEmirate($emirate,$country)."</td>
                    <td class='dvtCellLabel' style='border: 1px solid #ccc;'>City:$mandatory</td>
                    <td style='border: 1px solid #ccc;'>".GetCity($city,$emirate)."</td>                
                </tr> 
                <tr>
                	<td class='dvtCellLabel' style='border: 1px solid #ccc;'>Address:$mandatory</td>
                    <td style='border: 1px solid #ccc;' colspan=3><input type='text' class='form-control' name='txt_A_address' id='txt_A_address'  value='$address' ></td>
                    <td class='dvtCellLabel' style='border: 1px solid #ccc;'>Telephone:$mandatory</td>
                    <td style='border: 1px solid #ccc;'><input type='text' class='form-control' onkeypress='return AllowNumeric1(event);' onkeyup='borderchange();'  name='txt_A_telephone' id='txt_A_telephone'  value='$telephone'></td>
                </tr> 
                <tr>
                	<td class='dvtCellLabel' style='border: 1px solid #ccc;'>Fax:</td>
                    <td style='border: 1px solid #ccc;'><input type='text' class='form-control' onkeypress='return AllowNumeric1(event);' onkeyup='borderchange();'  name='txt_A_fax' id='txt_A_fax'  value='$fax'></td>
                    <td class='dvtCellLabel' style='border: 1px solid #ccc;'>Email:</td>
                    <td style='border: 1px solid #ccc;'><input type='text' class='form-control' onkeyup='borderchange();'  name='txt_A_email' id='txt_A_email'  value='$email'></td>
                    <td class='dvtCellLabel' style='border: 1px solid #ccc;'>Web:</td>
                    <td style='border: 1px solid #ccc;'><input type='text' class='form-control' onkeyup='borderchange();'  name='txt_A_web' id='txt_A_web'  value='$web'></td>                
                </tr>
                <tr>
                	<td class='dvtCellLabel' style='border: 1px solid #ccc;'>Facebook:</td>
                    <td style='border: 1px solid #ccc;'><input type='text' class='form-control' onkeyup='borderchange();'  name='txt_A_facebook' id='txt_A_facebook'  value='$facebook'></td>
                    <td class='dvtCellLabel' style='border: 1px solid #ccc;'>Instagram:</td>
                    <td style='border: 1px solid #ccc;'><input type='text' class='form-control' onkeyup='borderchange();'  name='txt_A_instagram' id='txt_A_instagram'  value='$instagram'></td>
                    <td class='dvtCellLabel' style='border: 1px solid #ccc;'>Linked In:</td>
                    <td style='border: 1px solid #ccc;'><input type='text' class='form-control' onkeyup='borderchange();'  name='txt_A_linkedin' id='txt_A_linkedin'  value='$linkedin'></td>                
                </tr>
                <tr>
                	<td class='dvtCellLabel' style='border: 1px solid #ccc;'>Whatsapp:</td>
                    <td style='border: 1px solid #ccc;'><input type='text' class='form-control' onkeypress='return AllowNumeric1(event);' onkeyup='borderchange();'  name='txt_A_whatsapp' id='txt_A_whatsapp'  value='$whatsapp'></td>
                    <td class='dvtCellLabel' style='border: 1px solid #ccc;'>Mol Id:</td>
                    <td style='border: 1px solid #ccc;'><input type='text' class='form-control' onkeyup='borderchange();'  name='txt_A_molid' id='txt_A_molid'  value='$molid'></td>
                    <td class='dvtCellLabel' style='border: 1px solid #ccc;'>Immigration Id:</td>
                    <td style='border: 1px solid #ccc;'><input type='text' class='form-control' onkeyup='borderchange();'  name='txt_A_immigrationid' id='txt_A_immigrationid'  value='$immigrationid'></td>                
                </tr>
                <tr>
                	<!--<td class='dvtCellLabel' style='border: 1px solid #ccc;'>Prefix:</td>
                    <td style='border: 1px solid #ccc;'><input type='text' class='form-control' onkeyup='borderchange();'  name='txt_A_prefix' id='txt_A_prefix'  value='$prefix'></td>-->
                     <td class='dvtCellLabel' style='border: 1px solid #ccc;'>Financial Yr from:</td>
                    <td style='border: 1px solid #ccc;'>".GetMonths('cmb_A_financialyearfrom',$financialyearfrom)."</td>
                    <td class='dvtCellLabel' style='border: 1px solid #ccc;'>Financial Yr To:</td>
                    <td style='border: 1px solid #ccc;'>".GetMonths('cmb_A_financialyearto',$financialyearto)."</td>           
                </tr>
                <tr>
                    <td class='dvtCellLabel' style='border: 1px solid #ccc;'>Acc.St from:</td>
                    <td style='border: 1px solid #ccc;'><input type='text' class='form-control txt' data-provide='datepicker' maxlength=10  onkeypress='return AllowNumeric1(event)'  onclick='borderchange();'  name='txd_A_accountstartsfrom' id='txd_A_accountstartsfrom'   value='$accountstartsfrom' placeholder='dd-mm-yyyy' ></td>
                    <td class='dvtCellLabel' style='border: 1px solid #ccc;'>Currency:</td>
                    <td style='border: 1px solid #ccc;'>".GetCurrency($currency)."</td>
                    <td class='dvtCellLabel' style='border: 1px solid #ccc;'>Currency Fraction:</td>
                    <td style='border: 1px solid #ccc;'>".GetCurrencyFraction($currencyfraction)."</td>
                                    
                </tr>
                <tr>
                    <td class='dvtCellLabel' style='border: 1px solid #ccc;'>Decimal:</td>
                    <td style='border: 1px solid #ccc;'>".GetDecimals($decimals)."</td>
                    <td class='dvtCellLabel' style='border: 1px solid #ccc;'>Currency Symbol:</td>
                    <td style='border: 1px solid #ccc;'><input type='text' class='form-control' onkeyup='borderchange();'  name='txt_A_currencysymbol' id='txt_A_currencysymbol'  value='$currencysymbol'></td>
                    <td class='dvtCellLabel' style='border: 1px solid #ccc;'>Status:</td>
                    <td style='border: 1px solid #ccc;'><input type='text' class='form-control' readonly value='".GetWfStatus($wfstatus)."'></td>
                </tr>
               <!-- <tr>
                    <td class='dvtCellLabel' style='border: 1px solid #ccc;'>Logo (1):</td>
                    <td style='border: 1px solid #ccc;'>
                    <input type='hidden' name='MAX_FILE_SIZE'><input name='userfile' class='btn-file' style='width:210px' type='file' id='userfile' onchange='Filevalidation(\"userfile\")'>
                    ".getUpFileName($logo1,$foldername)."</td>
                    <td class='dvtCellLabel' style='border: 1px solid #ccc;'>Logo (2):</td>
                    <td style='border: 1px solid #ccc;'>
                    <input type='hidden' name='MAX_FILE_SIZE'><input name='userfile1' class='btn-file' style='width:210px' type='file' id='userfile1' onchange='Filevalidation(\"userfile1\")'>
                    ".getUpFileName($logo2,$foldername)."</td>
                    <td class='dvtCellLabel' style='border: 1px solid #ccc;'>Logo (3):</td>
                    <td style='border: 1px solid #ccc;'>
                    <input type='hidden' name='MAX_FILE_SIZE'><input name='userfile2' class='btn-file' style='width:210px' type='file' id='userfile2' onchange='Filevalidation(\"userfile2\")'>
                    ".getUpFileName($logo3,$foldername)."</td>
                   
                	
                </tr>-->
                
                <!--<tr>
                    <td class='dvtCellLabel' style='border: 1px solid #ccc;'>Photo (1):</td>
                    <td style='border: 1px solid #ccc;'>
                    <input type='hidden' name='MAX_FILE_SIZE'><input name='userfile3' class='btn-file' style='width:210px' type='file' id='userfile3' onchange='Filevalidation(\"userfile3\")'>
                    ".getUpFileName($logo1,$foldername)."</td>
                    <td class='dvtCellLabel' style='border: 1px solid #ccc;'>Photo (2):</td>
                    <td style='border: 1px solid #ccc;'>
                    <input type='hidden' name='MAX_FILE_SIZE'><input name='userfile4' class='btn-file' style='width:210px' type='file' id='userfile4' onchange='Filevalidation(\"userfile4\")'>
                    ".getUpFileName($logo2,$foldername)."</td>
                    <td class='dvtCellLabel' style='border: 1px solid #ccc;'>Photo (3):</td>
                    <td style='border: 1px solid #ccc;'>
                    <input type='hidden' name='MAX_FILE_SIZE'><input name='userfile5' class='btn-file' style='width:210px' type='file' id='userfile5' onchange='Filevalidation(\"userfile5\")'>
                    ".getUpFileName($logo3,$foldername)."</td>
                   
                	
                </tr>-->
                 	<input type='hidden' name='mode' class=textboxcombo id='mode' value='$mode'>
                    <input type='hidden' name='modeid' class=textboxcombo id='modeid' value='save'>
                    <input type='hidden' name='saveid' class=textboxcombo id='saveid' value='$saveid'>
                    <input type='hidden' name='txt_A_workflowseq' class=textboxcombo id='txt_A_workflowseq' value='$workflowseq'>
                    <input type='hidden' name='txt_A_createdby' class=textboxcombo id='txt_A_createdby' value='$createdby'>

            </table>



                                              </div>
                                             </div>";

                       $entrydata.=  "<div class='box-footer' style='border-top:1px #D2D2D2 solid;'>";
                       if($update == "true" || ($insert=="true" && $_REQUEST['ID']==0))
                       $entrydata.="  <button class='btn btn-success inputs' style='margin-top:-5px;' name='btnsuccess' type='button'  onclick ='javascript:editingrecord(\"save\");'>Save </font>&nbsp;<i class='fa fa-save' aria-hidden='true'></i></button>";
                       if(($insert=="true" && $_REQUEST['ID']==0) || ($update == "true" && $insert=="true"))
                       $entrydata.="  <button class='btn btn-info inputs' style='margin-top:-5px;' name='btninfo' type='button' onclick ='javascript:editingrecord(\"savenew\");'>Save & New &nbsp; <i class='fa fa-save' aria-hidden='true'></i>&nbsp;&nbsp;<i class='fa fa-plus' aria-hidden='true'></i></button>";                       
                       if($update == "true" || ($insert=="true" && $_REQUEST['ID']==0))
                        $entrydata.="   <button class='btn btn-warning inputs' style='margin-top:-5px;' name='btnwarning' type='button'  onclick ='javascript:editingrecord(\"saveclose\");'>Save & Close &nbsp;<i class='fa fa-save' aria-hidden='true'></i>&nbsp;&nbsp;<i class='fa fa-close' aria-hidden='true'></i></button>";
                        $entrydata.="  <button class='btn btn-danger inputs' style='margin-top:-5px;' name='btndanger' type='button'  onclick ='javascript:closeediting(\"companysetup.php?ps=1&objectid=".$_SESSION['objectid']."\");'>Close &nbsp;<i class='fa fa-close' aria-hidden='true'></i></button>";
                        $entrydata.="  <button class='btn btn-primary inputs' style='margin-top:-5px;' name='btnprimary' type='button'  onclick ='javascript:closeediting(\"companysetup.php?ps=1&objectid=".$_SESSION['objectid']."&frmPage_rowcount=".$_SESSION['frmPage_rowcount']."&txtsearch=".$_SESSION['txtsearch']."&frmPage_startrow=".$_SESSION['frmPage_startrow']."\");'>Back &nbsp;<i class='fa fa-backward' aria-hidden='true'></i></button>";
                        $entrydata.= $Action_button;
                        $entrydata.=" </div>";
;
                        $entrydata.="  </form> ";

          echo  $entrydata;

?>   
	
	</div>
		<div class="tab-pane" id="uploads">
		<iframe id="frame3" name="frame3" scrolling="no" onload='this.width=screen.width;this.height=screen.height;'  frameborder="0" style="position: relative; width: 100%; "></iframe>
		</div>
		<div class="tab-pane" id="sequencer">
		<iframe id="frame4" name="frame4" scrolling="no" onload='this.width=screen.width;this.height=screen.height;'  frameborder="0" style="position: relative; width: 100%; "></iframe>
		</div>
		<div class="tab-pane" id="logos">
		<iframe id="frame5" name="frame5" scrolling="no" onload='this.width=screen.width;this.height=screen.height;'  frameborder="0" style="position: relative; width: 100%; "></iframe>
		</div>
		<div class="tab-pane" id="documents">
		<iframe id="frame6" name="frame6" scrolling="no" onload='this.width=screen.width;this.height=screen.height;'  frameborder="0" style="position: relative; width: 100%; "></iframe>
		</div>
</div>
</div>
</section>
<?php

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


function GetCity($countryid,$emirateid){
	global $con;
	$CMB = " <select name='cmb_A_cityid'  id='cmb_A_cityid' class='form-control select2'>";
	$seqSQL = "select id  as lookcode,city as lookname from tbl_city where emirateid='$emirateid' order by city";
	$result=mysqli_query($con,$seqSQL) or die(mysqli_error()."<br>".$seqSQL);
	$CMB .= "<option value='' >Select City</option>";
	while($ARR=mysqli_fetch_array($result)){
		$SEL = "";
		if(strtoupper($countryid)== strtoupper($ARR['lookcode'])){ $SEL =  "SELECTED";}
               $CMB .= "<option value='".strtoupper($ARR['lookcode'])."' $SEL >".$ARR['lookname']."</option>";
    }
    $CMB .= "</select>";
    return $CMB;
}

function GetCountry($countryid){
	global $con;
	$CMB = " <select name='cmb_A_countryid'  id='cmb_A_countryid' class='form-control select2' onChange='getEmirates(this.value)'>";
	$seqSQL = "select id  as lookcode,country as lookname from tbl_country order by country";
	$result=mysqli_query($con,$seqSQL) or die(mysqli_error()."<br>".$seqSQL);
	$CMB .= "<option value='' >Select Country</option>";
	while($ARR=mysqli_fetch_array($result)){
		$SEL = "";
		if(strtoupper($countryid)== strtoupper($ARR['lookcode'])){ $SEL =  "SELECTED";}
               $CMB .= "<option value='".strtoupper($ARR['lookcode'])."' $SEL >".$ARR['lookname']."</option>";
    }
    $CMB .= "</select>";
    return $CMB;
}

function GetEmirate($emirateid,$countryid){
	global $con;
	$CMB = " <select name='cmb_A_emirateid'  id='cmb_A_emirateid' class='form-control select2' onChange='getCities(this.value)'>";
	$seqSQL = "select id  as lookcode,emirate as lookname from tbl_states where countryid='$countryid' order by emirate";
	$result=mysqli_query($con,$seqSQL) or die(mysqli_error()."<br>".$seqSQL);
	$CMB .= "<option value='' >Select Emirate</option>";
	while($ARR=mysqli_fetch_array($result)){
		$SEL = "";
		if(strtoupper($emirateid)== strtoupper($ARR['lookcode'])){ $SEL =  "SELECTED";}
               $CMB .= "<option value='".strtoupper($ARR['lookcode'])."' $SEL >".$ARR['lookname']."</option>";
    }
    $CMB .= "</select>";
    return $CMB;
}
function GetDecimals($decimals){
	global $con;
	$CMB = "<select name='cmb_A_decimals'  id='cmb_A_decimals' class='form-control select2' >";
	$seqSQL = "select lookcode,lookname from in_lookup where looktype='DECIMALS' and lookname<>'XX' order by id";
	$result=mysqli_query($con,$seqSQL) or die(mysqli_error()."<br>".$seqSQL);
	$CMB .= "<option value='' >Select</option>";
	while($ARR=mysqli_fetch_array($result)){
		$SEL = "";
		if(strtoupper($decimals)== strtoupper($ARR['lookcode'])){ $SEL =  "SELECTED";}
               $CMB .= "<option value='".strtoupper($ARR['lookcode'])."' $SEL >".$ARR['lookname']."</option>";
    }
    $CMB .= "</select>";
    return $CMB;
}
function GetCurrency($currency){
	global $con;
	$CMB = "<select name='cmb_A_currency'  id='cmb_A_currency' class='form-control select2' >";
	$seqSQL = "select lookcode,lookname from in_lookup where looktype='CURRENCY' and lookname<>'XX' order by id";
	$result=mysqli_query($con,$seqSQL) or die(mysqli_error()."<br>".$seqSQL);
	$CMB .= "<option value='' >Select Currency</option>";
	while($ARR=mysqli_fetch_array($result)){
		$SEL = "";
		if(strtoupper($currency)== strtoupper($ARR['lookcode'])){ $SEL =  "SELECTED";}
               $CMB .= "<option value='".strtoupper($ARR['lookcode'])."' $SEL >".$ARR['lookname']."</option>";
    }
    $CMB .= "</select>";
    return $CMB;
}
function GetCurrencyFraction($currencyfraction){
	global $con;
	$CMB = "<select name='cmb_A_currencyfraction'  id='cmb_A_currencyfraction' class='form-control select2' >";
	$seqSQL = "select lookcode,lookname from in_lookup where looktype='CURRENCY FRACTION' and lookname<>'XX' order by id";
	$result=mysqli_query($con,$seqSQL) or die(mysqli_error()."<br>".$seqSQL);
	$CMB .= "<option value='' >Select</option>";
	while($ARR=mysqli_fetch_array($result)){
		$SEL = "";
		if(strtoupper($currencyfraction)== strtoupper($ARR['lookcode'])){ $SEL =  "SELECTED";}
               $CMB .= "<option value='".strtoupper($ARR['lookcode'])."' $SEL >".$ARR['lookname']."</option>";
    }
    $CMB .= "</select>";
    return $CMB;
}
function GetMonths($fieldname,$month){
	global $con;
	$CMB = " <select name='$fieldname'  id='$fieldname' class='form-control select2' > ";
	$seqSQL = "select lookcode,lookname from in_lookup where looktype='MONTHS' and lookname<>'XX' order by id";
	$result=mysqli_query($con,$seqSQL) or die(mysqli_error()."<br>".$seqSQL);
	$CMB .= "<option value='' >Select</option>";
	while($ARR=mysqli_fetch_array($result)){
		$SEL = "";
		if(strtoupper($month)== strtoupper($ARR['lookcode'])){ $SEL =  "SELECTED";}
               $CMB .= "<option value='".strtoupper($ARR['lookcode'])."' $SEL >".$ARR['lookname']."</option>";
    }
    $CMB .= "</select>";
    return $CMB;
}
function GetWfStatus($status){
	global $con;
	$seqSQL = "select statusname from tbl_status where id='$status'";
	$result=mysqli_query($con,$seqSQL) or die(mysqli_error()."<br>".$seqSQL);
	if($result->num_rows==1){
	$ARR=$result->fetch_array();
    return $ARR['statusname'];
	}
	else return $status;
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
</body>
</html>
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
       document.frmEdit.action='editcompanysetup.php?dr=edit&ID='+document.getElementById('mode').value;
       document.frmEdit.submit();
   }
   if(i==3){
   var frame= document.getElementById('frame3');
   frame.src='upload_documents.php?entitytype=COMPANY_DOCUMENTS&ID=<?php echo $_REQUEST['ID']; ?>';
   frame.load();
   }
   if(i==4){
   var frame= document.getElementById('frame4');
   frame.src='companysequencer.php?PARENTID=<?php echo $_REQUEST['ID']; ?>';
   frame.load();
   }
   if(i==5){
   var frame= document.getElementById('frame5');
   frame.src='companylogo.php?PARENTID=<?php echo $_REQUEST['ID']; ?>';
   frame.load();
   }
   if(i==6){
   var frame= document.getElementById('frame6');
   frame.src='companydocuments.php?entitytype=COMPANY_DOCUMENTS&ID=<?php echo $_REQUEST['ID']; ?>';
   frame.load();
   }

}
            
</script>




