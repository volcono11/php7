<?php
session_start();
date_default_timezone_set("Asia/Dubai");
//echo $_SESSION['pr'];// = isset($_REQUEST['pr']) ? $_REQUEST['pr'] : '';
$insert = $update = $delete = "false";

if(false !== strpos($_SESSION['pr'],"I")) $insert = "true" ;
if(false !== strpos($_SESSION['pr'],"U")) $update = "true" ;
if(false !== strpos($_SESSION['pr'],"D")) $delete = "true" ;

require "connection.php";
require "pagingObj.php";
$grid = new MyPHPGrid('frmPage');
$grid->TableName = "tbl_lease";
$grid->formName = "editleaselist.php";
$grid->SyncSession($grid);
$grid->TableNameChild = "";
if($_REQUEST['ID'] != "0") {
             $mode=$_REQUEST['ID'];

             $SQL = " Select *,DATE_FORMAT(fromdate,'%d-%m-%Y') as fromdate,DATE_FORMAT(todate,'%d-%m-%Y') as todate,DATE_FORMAT(moveindate,'%d-%m-%Y') as moveindate,
              DATE_FORMAT(moveoutdate,'%d-%m-%Y') as moveoutdate from tbl_lease where id='".$_REQUEST['ID']."'";
             $SQLRes =  mysqli_query($con,$SQL) or die(mysqli_error()."<br>".$SQL);
              if(mysqli_num_rows($SQLRes)>=1){
                while($loginResultArray   = mysqli_fetch_array($SQLRes)){
                   $saveid=  $loginResultArray['id'];
                   //$leasecode=  $loginResultArray['id'];
                   //$propertyownercode = htmlspecialchars($loginResultArray['id']);
                   $property=  $loginResultArray['property'];
                   $unit=  $loginResultArray['unit'];
                   $fromdate=  $loginResultArray['fromdate'];
                   if(stripos(json_encode($fromdate),'00-00-0000') == true)$fromdate=date('d-m-Y');
                   $todate=  $loginResultArray['todate'];
                   if(stripos(json_encode($todate),'00-00-0000') == true)$todate=date('d-m-Y');
                   $leaseamt=  $loginResultArray['leaseamt'];
                   $noofinstallments=  $loginResultArray['noofinstallments'];
                   $paymentby=  $loginResultArray['paymentby'];
                   $securitydeposite=  $loginResultArray['securitydeposite'];
                   $occupstatus=  $loginResultArray['occupstatus'];
                   $maintenanceby=  $loginResultArray['maintenanceby'];
                   $noofparking=  $loginResultArray['noofparking'];
                   $contractstatus= $loginResultArray['contractstatus'];
                   $moveindate=  $loginResultArray['moveindate'];
                   if(stripos(json_encode($moveindate),'00-00-0000') == true)$moveindate=date('d-m-Y');
                   $moveoutdate=  $loginResultArray['moveoutdate'];
                   if(stripos(json_encode($moveoutdate),'00-00-0000') == true)$moveoutdate=date('d-m-Y');
                   $tenant=  $loginResultArray['tenant'];
                   $parkingnos=  $loginResultArray['parkingnos'];
                   $accesscardno=  $loginResultArray['accesscardno'];
                   $noofkeys=  $loginResultArray['noofkeys'];
                   $ejarino=  $loginResultArray['ejarino'];
                   $electricitymeterno=  $loginResultArray['electricitymeterno'];
                   $watermeterno=  $loginResultArray['watermeterno'];
                   $gasmeterno=  $loginResultArray['gasmeterno'];
                   $municipalityno=  $loginResultArray['municipalityno'];
                   $remarks=  $loginResultArray['remarks'];
                  }
              }
           }else{
              $mode="";
              $saveid =  GetLastSqeID("tbl_lease");
              $tenant="";
              $property= "";
              $unit= "";
              $parkingnos=$accesscardno=$noofkeys=$ejarino=$occupstatus=$securitydeposite=$leaseamt=$noofinstallments= "";
              $electricitymeterno=$watermeterno=$gasmeterno=$municipalityno=$remarks=$noofparking=$paymentby=$maintenanceby="";
              $contractstatus=$todate=$fromdate=$moveindate=$moveoutdate=date('d-m-Y');
              
              
}

if(isset($_REQUEST['dr'])=='view'){
   $edit="none";
   $view="inline";
   $title="Viewing : ".$saveid."";
}else if(isset($_REQUEST['dr'])=='edit'){
      $edit="inline";
      $view="none";
      $title="Editing : ".$saveid."";
}else{
      $edit="inline";
      $view="none";
      $title="Adding New Lease";
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
function HideColumns(cattype){
//alert(cattype);
if(cattype== '83002'){
var tr2=document.getElementById('tr2');
tr2.style.display="none";
var tr3=document.getElementById('tr3');
tr3.style.display="none";
}
if(cattype=='83001')
{
document.getElementById('tr2').style.display = 'table-row';
document.getElementById('tr3').style.display = 'block';
}
}
function getUnits(cattype){
	xmlHttp=GetXmlHttpObject()
	if (xmlHttp==null)
	{
	         alert ("Browser does not support HTTP Request")
	         return
	}

	var url="combofunctions_lease.php?level=Units&propertyidid="+cattype;
	xmlHttp.onreadystatechange=stateChangedcombo_units
	xmlHttp.open("POST",url,true)
	xmlHttp.send(null)
}
function stateChangedcombo_units(){
       if (xmlHttp.readyState==4 || xmlHttp.readyState=="complete")
       {
             var s1 = trim(xmlHttp.responseText);
             document.getElementById('cmb_A_unit').innerHTML=s1;
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

   $('#txt_A_TABLENAME').css('border-color', '');
   $('#txt_A_LASTNUMBER').css('border-color', '');

}
function editingrecord(action)
{

       var cmb_A_tenant=document.getElementById('cmb_A_tenant');
       if(cmb_A_tenant){
          if ((cmb_A_tenant.value==null)||(cmb_A_tenant.value=="")){
               alertify.alert("Enter Tenant Name", function () {
               cmb_A_tenant.focus();

          });
             return;
          }
       }
       var cmb_A_property=document.getElementById('cmb_A_property');
       if(cmb_A_property){
          if ((cmb_A_property.value==null)||(cmb_A_property.value=="")){
               alertify.alert("Select Building Name", function () {
               cmb_A_property.focus();

          });
             return;
          }
       }
       
       var cmb_A_unit=document.getElementById('cmb_A_unit');
       if(cmb_A_unit){
          if ((cmb_A_unit.value==null)||(cmb_A_unit.value=="")){
               alertify.alert("Select Unit", function () {
               cmb_A_unit.focus();

          });
             return;
          }
       }
       var txd_A_from=document.getElementById('txd_A_from');
       if(txd_A_from){
          if ((txd_A_from.value==null)||(txd_A_from.value=="")){
               alertify.alert("Select From Date", function () {
               txd_A_from.focus();

          });
             return;
          }
       }
       
       var txd_A_to=document.getElementById('txd_A_to');
       if(txd_A_to){
          if ((txd_A_to.value==null)||(txd_A_to.value=="")){
               alertify.alert("Select To Date", function () {
               txd_A_to.focus();

          });
             return;
          }
       }





       insertfunction(get(document.frmEdit),action)
}
                   var xmlHttp
                   function insertfunction(parameters,action)
                   {
                        // alert(parameters);
                          xmlHttp=GetXmlHttpObject()
                          if (xmlHttp==null)
                          {
                                 alert ("Browser does not support HTTP Request")
                                 return
                          }


                          var url="in_action.php"+parameters
                         // alert(url);
                          if(action=='save'){

                            xmlHttp.onreadystatechange=stateChangedsave
                          }
                          if(action=='savenew'){
                            xmlHttp.onreadystatechange=stateChangedsavenew
                          }
                          if(action=='SaveClose'){

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
                                alert(s1);
                               var s2 = "Record Saved";
                               var s3 = "Record Updated";
                               if(s1.toString() == s2.toString()){
                                 alertify.alert("Record Saved", function () {
                                 window.location.href='editleaselist.php?dr=edit&ID='+document.getElementById('saveid').value;
                                });
                               }else if(s1.toString() == s3.toString()){
                                alertify.alert("Record Updated", function () {
                                window.location.href='editleaselist.php?dr=edit&ID='+document.getElementById('mode').value;

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
                                 window.location.href='editleaselist.php?dr=add&ID=0';
                                });
                               }else if(s1.toString() == s3.toString()){
                                alertify.alert("Record Updated", function () {
                                window.location.href='editleaselist.php?dr=add&ID=0';

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
                               //alert(s1);
                               var s2 = "Record Saved";
                               var s3 = "Record Updated";
                               if(s1.toString() == s2.toString()){
                                alertify.alert('Record Saved');
                                window.location.href='leaselist.php?ID=0&pr=<?php echo $_SESSION["pr"]; ?>';
                               }else if(s1.toString() == s3.toString()){
                                alertify.alert("Record Updated", function () {
                                window.location.href='leaselist.php?ID=0&pr=<?php echo $_SESSION["pr"]; ?>';

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

                 <a class="pull-left" href="leaselist.php?ps=1&pr=<?php echo $_SESSION['pr']; ?>&frmPage_rowcount=<?php echo $_SESSION['frmPage_rowcount']; ?>&txtsearch=<?php echo $_SESSION['txtsearch']; ?>&frmPage_startrow=<?php echo $_SESSION['frmPage_startrow'];?>" data-toggle="tooltip" data-placement="right" title="Back to Home"><i class='fa fa-backward'></i></a>
                 <h2 class="title">&nbsp;&nbsp;<?php echo $title; ?></h2>
<!--
                 <ol class='breadcrumb'>
                  <li><a href="#"><a href="blank.php" >Admin Setup</a></li>
                  <li><a href="#"><a href="iconlist.php?ps=1">Sequencer</a></li>
                  <li class="active"><?php echo $title; ?></li>
                 </ol>-->

         </section>

                <section class="content" id='content-content-id' style='padding-right:5px;padding-left:5px;margin-top:-10px;' >

                  <div class="nav-tabs-custom" id="nav-tabs-custom-id">
                        <ul class="nav nav-tabs">
                            <li class="active"><a href="#personal" onclick='javascript:loadpage(2);' data-toggle="tab"><i class="fa fa-desktop" aria-hidden="true"></i>&nbsp; Tenancy Info </a></li>
                           <li><a href="#financial" onclick='javascript:loadpage(4);' data-toggle="tab"><i class="fa fa-folder-open" aria-hidden="true"></i>&nbsp; Financial Info(s)</a></li>
                           <li><a href="#receipt" onclick='javascript:loadpage(5);' data-toggle="tab"><i class="fa fa-folder-open" aria-hidden="true"></i>&nbsp; Receipts Info(s)</a></li>
                           <li><a href="#documents" onclick='javascript:loadpage(3);' data-toggle="tab"><i class="fa fa-folder-open" aria-hidden="true"></i>&nbsp;Rental Document(s)</a></li>
                           <li><a href="#cheque" onclick='javascript:loadpage(6);' data-toggle="tab"><i class="fa fa-folder-open" aria-hidden="true"></i>&nbsp;Cheque Info(s)</a></li>
                       </ul>

                        <div class="tab-content" id='tab-content-id'>
                          <div class="tab-pane active" id="personal">
                              <div class="box-body no-padding" id='box-body-id'>
                                   <div class='table-responsive no-padding'>

<?php
            $mandatory = "<span class='mandatory'>&nbsp;*</span>";
            $entrydata = "<form name='frmEdit' method='post' id='frmEdit' enctype='multipart/form-data'>

                                 <table class='table table-bordered table-condensed table-fixed  table-responsive' style='table-layout:fixed'>
                                                            <tr>
                                                            <th class='bg-light-blue' style='width:6%;text-align:left;border-bottom:1px #2F3C43 solid;border-top:1px #fff solid;color:#FFFFFF' colspan=6>Tenancy Details</th>
                                                            </tr>
                                                            <tr>
                                                              <td class='dvtCellLabel' style='border: 1px solid #ccc;'> Lease Reference:$mandatory</td>
                                                              <td style='width:20%;border: 1px solid #ccc;'>$saveid
                                                              <td class='dvtCellLabel' style='border: 1px solid #ccc;'> Tenant:$mandatory</td>
                                                              <td style='width:20%;border: 1px solid #ccc;'>".GetTenantName($tenant)."
                                                              <td class='dvtCellLabel' style='border: 1px solid #ccc;'> Buiding:$mandatory</td>
                                                               <td id='tr3' style='width:20%;border: 1px solid #ccc;'>".GetProperty($property)."


                                                              </tr>
                                                              <tr id='tr2'>
                                                              <td id='tr3' class='dvtCellLabel' style='border: 1px solid #ccc;'>Unit:$mandatory</td>
                                                              <td style='width:20%;border: 1px solid #ccc;'>".GetUnitName($unit,$property)."</td>
                                                              <td class='dvtCellLabel' style='border: 1px solid #ccc;'>Lease From:$mandatory</td>
                                                              <td style='width:20%;border: 1px solid #ccc;'><input type='text' class='form-control txt' data-provide='datepicker' maxlength=10 onkeypress='return AllowNumeric1(event)' name='txd_A_fromdate' id='txd_A_fromdate'   value='$fromdate' placeholder='dd-mm-yyyy' ></td>
                                                               <td class='dvtCellLabel' style='border: 1px solid #ccc;'>Lease To:$mandatory</td>
                                                              <td style='width:20%;border: 1px solid #ccc;'><input type='text' class='form-control txt' data-provide='datepicker' maxlength=10 onkeypress='return AllowNumeric1(event)' name='txd_A_todate' id='txd_A_todate'   value='$todate' placeholder='dd-mm-yyyy' ></td>
                                                              </tr>
                                                              <tr>
                                                              <td class='dvtCellLabel' style='border: 1px solid #ccc;'>Lease Amount:</td>
                                                              <td style='width:20%;border: 1px solid #ccc;'><input type='text'  class='form-control txt' onkeypress='return AllowNumeric1(event)'   name='txt_A_leaseamt' id='txt_A_leaseamt'  value='$leaseamt' ></td>
                                                              <td class='dvtCellLabel' style='border: 1px solid #ccc;'>No Of Instalments:</td>
                                                              <td style='width:20%;border: 1px solid #ccc;'>".GetNoOfInstalments($noofinstallments)."</td>
                                                              <td class='dvtCellLabel' style='border: 1px solid #ccc;'>Maintenance By:</td>
                                                              <td style='width:20%;border: 1px solid #ccc;'>".GetMaintenanceBy($maintenanceby )."</td>
                                                              </tr>
                                                              <td class='dvtCellLabel' style='border: 1px solid #ccc;'>Utility Payment By:</td>
                                                              <td style='width:20%;border: 1px solid #ccc;'>".GetPaymentBy($paymentby )."</td>

                                                              <td class='dvtCellLabel' style='border: 1px solid #ccc;'>Move In Date:</td>
                                                              <td style='width:20%;border: 1px solid #ccc;'><input type='text' class='form-control txt' data-provide='datepicker' maxlength=10 onkeypress='return AllowNumeric1(event)' name='txd_A_moveindate' id='txd_A_moveindate'   value='$moveindate' placeholder='dd-mm-yyyy' ></td>
                                                              <td class='dvtCellLabel' style='border: 1px solid #ccc;'>Move Out Date:</td>
                                                              <td style='width:20%;border: 1px solid #ccc;'><input type='text' class='form-control txt' data-provide='datepicker' maxlength=10 onkeypress='return AllowNumeric1(event)' name='txd_A_moveoutdate' id='txd_A_moveoutdate'   value='$moveoutdate' placeholder='dd-mm-yyyy' ></td>
                                                              </tr>
                                                              <tr>
                                                              <td class='dvtCellLabel' style='border: 1px solid #ccc;'>Security Deposit:</td>
                                                              <td style='width:20%;border: 1px solid #ccc;'><input type='text'  class='form-control txt' onkeypress='return AllowNumeric1(event)'  name='txt_A_securitydeposite' id='txt_A_securitydeposite'  value='$securitydeposite' ></td>
                                                              <td class='dvtCellLabel' style='border: 1px solid #ccc;'>Occupancy Status:</td>
                                                              <td style='width:20%;border: 1px solid #ccc;'>".GetOccupancyStatus($occupstatus )."</td>
                                                              <td class='dvtCellLabel' style='border: 1px solid #ccc;'>Contract Status:</td>
                                                              <td style='width:20%;border: 1px solid #ccc;'>".GetContractStatus($contractstatus )."</td>

                                                              </tr>
                                                              <tr>
                                                              <td class='dvtCellLabel' style='border: 1px solid #ccc;'>Remarks:</td>
                                                              <td style='width:20%;border: 1px solid #ccc;' colspan=5><input type='text'  class='form-control txt' name='txt_A_remarks' id='txt_A_remarks'  value='$remarks' ></td>
                                                              </tr>
                                                              <th class='bg-light-blue' style='width:6%;text-align:left;border-bottom:1px #2F3C43 solid;border-top:1px #fff solid;color:#FFFFFF' colspan=6>Additional Information</th>
                                                               <tr>
                                                               <td class='dvtCellLabel' style='border: 1px solid #ccc;'>No Of Parking:</td>
                                                               <td style='width:20%;border: 1px solid #ccc;'><input type='text'  class='form-control txt' onkeypress='return AllowNumeric1(event)'  name='txt_A_noofparking' id='txt_A_noofparking'  value='$noofparking' ></td>
                                                               <td class='dvtCellLabel' style='border: 1px solid #ccc;'>Parking Nos:</td>
                                                               <td style='width:20%;border: 1px solid #ccc;'colspan=3><input type='text'  class='form-control txt' name='txt_A_parkingnos' id='txt_A_parkingnos'  value='$parkingnos' ></td>
                                                               </tr>

                                                               <tr>
                                                               <td class='dvtCellLabel' style='border: 1px solid #ccc;'>Access Card No:</td>
                                                               <td style='width:20%;border: 1px solid #ccc;'><input type='text'  class='form-control txt' onkeypress='return AllowNumeric1(event)'  name='txt_A_accesscardno' id='txt_A_accesscardno'  value='$accesscardno' ></td>
                                                               <td class='dvtCellLabel' style='border: 1px solid #ccc;'>No Of keys:</td>
                                                               <td style='width:20%;border: 1px solid #ccc;'><input type='text'  class='form-control txt' onkeypress='return AllowNumeric1(event)'  name='txt_A_noofkeys' id='txt_A_noofkeys'  value='$noofkeys' ></td>
                                                               <td class='dvtCellLabel' style='border: 1px solid #ccc;'>Ejari No:</td>
                                                               <td style='width:20%;border: 1px solid #ccc;'><input type='text'  class='form-control txt' onkeypress='return AllowNumeric1(event)'  name='txt_A_ejarino' id='txt_A_ejarino'  value='$ejarino' ></td>
                                                               </tr>
                                                               <tr>
                                                               <td class='dvtCellLabel' style='border: 1px solid #ccc;'>Elictricity Meter No:</td>
                                                               <td style='width:20%;border: 1px solid #ccc;'><input type='text'  class='form-control txt' onkeypress='return AllowNumeric1(event)'  name='txt_A_electricitymeterno' id='txt_A_electricitymeterno'  value='$electricitymeterno' ></td>
                                                               <td class='dvtCellLabel' style='border: 1px solid #ccc;'>Water Meter No:</td>
                                                               <td style='width:20%;border: 1px solid #ccc;'><input type='text'  class='form-control txt' onkeypress='return AllowNumeric1(event)'  name='txt_A_watermeterno' id='txt_A_watermeterno'  value='$watermeterno' ></td>
                                                               <td class='dvtCellLabel' style='border: 1px solid #ccc;'>Gas Meter No:</td>
                                                               <td style='width:20%;border: 1px solid #ccc;'><input type='text'  class='form-control txt' onkeypress='return AllowNumeric1(event)'  name='txt_A_gasmeterno' id='txt_A_gasmeterno'  value='$gasmeterno' ></td>
                                                               </tr>
                                                               <tr>
                                                               <td class='dvtCellLabel' style='border: 1px solid #ccc;'>Municipality No:</td>
                                                               <td style='width:20%;border: 1px solid #ccc;'><input type='text'  class='form-control txt' onkeypress='return AllowNumeric1(event)'  name='txt_A_municipalityno' id='txt_A_municipalityno'  value='$municipalityno' ></td>
                                                               <input type='hidden' name='mode' class=textboxcombo id='mode' value='$mode'>
                                                              <input type='hidden' name='modeid' class=textboxcombo id='modeid' value='save'>
                                                              <input type='hidden' name='saveid' class=textboxcombo id='saveid' value='$saveid'>
                                                               </tr>
                                                            </table>

                                              </div>
                                             </div>";
						
                       $entrydata.=  "<div class='box-footer' style='border-top:1px #D2D2D2 solid;'>";
                       if($update == "true" || ($insert=="true" && $_REQUEST['ID']==0))
                       $entrydata.="  <button class='btn btn-success inputs' style='margin-top:-5px;' name='btnsuccess' type='button'  onclick ='javascript:editingrecord(\"save\");'>Save </font>&nbsp;<i class='fa fa-save' aria-hidden='true'></i></button>";
                       if(($insert=="true" && $_REQUEST['ID']==0) || ($update == "true" && $insert=="true"))
                       $entrydata.="  <button class='btn btn-info inputs' style='margin-top:-5px;' name='btninfo' type='button' onclick ='javascript:editingrecord(\"savenew\");'>Save & New &nbsp; <i class='fa fa-save' aria-hidden='true'></i>&nbsp;&nbsp;<i class='fa fa-plus' aria-hidden='true'></i></button>";
                       
                       if($update == "true" || ($insert=="true" && $_REQUEST['ID']==0))
                        $entrydata.="   <button class='btn btn-warning inputs' style='margin-top:-5px;' name='btnwarning' type='button'  onclick ='javascript:editingrecord(\"SaveClose\");'>Save & Close &nbsp;<i class='fa fa-save' aria-hidden='true'></i>&nbsp;&nbsp;<i class='fa fa-close' aria-hidden='true'></i></button>";
                        
                        $entrydata.="  <button class='btn btn-danger inputs' style='margin-top:-5px;' name='btndanger' type='button'  onclick ='javascript:closeediting(\"leaselist.php?ps=1&pr=".$_SESSION['pr']."\");'>Close &nbsp;<i class='fa fa-close' aria-hidden='true'></i></button>";
                        $entrydata.="  <button class='btn btn-primary inputs' style='margin-top:-5px;' name='btndanger' type='button'  onclick ='javascript:closeediting(\"leaselist.php?ps=1&pr=".$_SESSION['pr']."&frmPage_rowcount=".$_SESSION['frmPage_rowcount']."&txtsearch=".$_SESSION['txtsearch']."&frmPage_startrow=".$_SESSION['frmPage_startrow']."\");'>Back &nbsp;<i class='fa fa-backward' aria-hidden='true'></i></button>";

                        $entrydata.="</div>";
                        $entrydata.= "</form> ";

echo  $entrydata;

?>

                           </div>


        <div class="tab-pane" id="financial">
		<iframe id="frame4" name="frame4" scrolling="no" onload='this.width=screen.width;this.height=screen.height;'  frameborder="0" style="position: relative; width: 100%; "></iframe>
		</div>
         <div class="tab-pane" id="documents">
		<iframe id="frame3" name="frame3" scrolling="no" onload='this.width=screen.width;this.height=screen.height;'  frameborder="0" style="position: relative; width: 100%; "></iframe>
		</div>
		<div class="tab-pane" id="receipt">
		<iframe id="frame5" name="frame5" scrolling="no" onload='this.width=screen.width;this.height=screen.height;'  frameborder="0" style="position: relative; width: 100%; "></iframe>
		</div>
		<div class="tab-pane" id="cheque">
		<iframe id="frame6" name="frame6" scrolling="no" onload='this.width=screen.width;this.height=screen.height;'  frameborder="0" style="position: relative; width: 100%; "></iframe>
		</div>
        </div>
        </div>
</section>
<?php
function GetTenantName($tenant){
	global $con;
	$CMB = " <select name='cmb_A_tenant'  id='cmb_A_tenant' class='form-control'>";
    $CMB .= "<option value=''>Select</option>";
	$seqSQL = "select id ,tenantname from tbl_tenant";
	$result=mysqli_query($con,$seqSQL) or die(mysqli_error()."<br>".$seqSQL);
	while($ARR=mysqli_fetch_array($result)){
		$SEL = "";
		if(strtoupper($tenant)== strtoupper($ARR['id'])){ $SEL =  "SELECTED";}
               $CMB .= "<option value='".strtoupper($ARR['id'])."' $SEL >".$ARR['tenantname']."</option>";
    }
    $CMB .= "</select>";
    return $CMB;
}
function GetProperty($property){
	global $con;
	$CMB = " <select name='cmb_A_property'  id='cmb_A_property' class='form-control' onChange='getUnits(this.value)'>";
    $CMB .= "<option value=''>Select</option>";
	$seqSQL = "select id ,propertyname from tbl_property";
	$result=mysqli_query($con,$seqSQL) or die(mysqli_error()."<br>".$seqSQL);
	while($ARR=mysqli_fetch_array($result)){
		$SEL = "";
		if(strtoupper($property)== strtoupper($ARR['id'])){ $SEL =  "SELECTED";}
               $CMB .= "<option value='".strtoupper($ARR['id'])."' $SEL >".$ARR['propertyname']."</option>";
    }
    $CMB .= "</select>";
    return $CMB;
}
function GetUnitName($unittype,$property){
	global $con;
    $CMB = " <select name='cmb_A_unit'  id='cmb_A_unit' class='form-control select'>";
    $CMB .= "<option value=''>Select</option>";
	$SEL =  "Select id as lookcode,unitname as lookname from tbl_propertydetails where propertyid='$property' order by id";
	$RES = mysqli_query($con,$SEL);
	while ($ARR = mysqli_fetch_array($RES)) {
	   $SEL = "";
	   if($unittype == $ARR['lookcode']){ $SEL =  "SELECTED";}
	   $CMB .= "<option value='".trim($ARR['lookcode'])."' $SEL >".trim($ARR['lookname'])."</option>";
	}
	$CMB .= "</select>";
	return $CMB;
}
function GetNoOfInstalments($noofinstallments){
	global $con;
    $CMB = " <select name='cmb_A_noofinstallments'  id='cmb_A_noofinstallments' class='form-control select'>";
    $CMB .= "<option value=''>Select</option>";
	$SEL =  "Select lookcode,lookname from in_lookup where looktype='MONTH NOS' and lookname<>'XX'";
	$RES = mysqli_query($con,$SEL);
	while ($ARR = mysqli_fetch_array($RES)) {
	   $SEL = "";
	   if($noofinstallments == $ARR['lookcode']){ $SEL =  "SELECTED";}
	   $CMB .= "<option value='".trim($ARR['lookcode'])."' $SEL >".trim($ARR['lookname'])."</option>";
	}
	$CMB .= "</select>";
	return $CMB;
}
function GetPaymentBy($paymentby){
	global $con;
    $CMB = " <select name='cmb_A_paymentby'  id='cmb_A_paymentby' class='form-control select'>";
    $CMB .= "<option value=''>Select</option>";
	$SEL =  "Select lookcode,lookname from in_lookup where looktype='PAYMENT BY' and lookname<>'XX' and lookname<>'LandLord'";
	$RES = mysqli_query($con,$SEL);
	while ($ARR = mysqli_fetch_array($RES)) {
	   $SEL = "";
	   if($paymentby == $ARR['lookcode']){ $SEL =  "SELECTED";}
	   $CMB .= "<option value='".trim($ARR['lookcode'])."' $SEL >".trim($ARR['lookname'])."</option>";
	}
	$CMB .= "</select>";
	return $CMB;
}
function GetMaintenanceBy($maintenanceby){
	global $con;
    $CMB = " <select name='cmb_A_maintenanceby'  id='cmb_A_maintenanceby' class='form-control select'>";
    $CMB .= "<option value=''>Select</option>";
	$SEL =  "Select lookcode,lookname from in_lookup where looktype='PAYMENT BY' and lookname<>'XX'";
	$RES = mysqli_query($con,$SEL);
	while ($ARR = mysqli_fetch_array($RES)) {
	   $SEL = "";
	   if($maintenanceby == $ARR['lookcode']){ $SEL =  "SELECTED";}
	   $CMB .= "<option value='".trim($ARR['lookcode'])."' $SEL >".trim($ARR['lookname'])."</option>";
	}
	$CMB .= "</select>";
	return $CMB;
}
function GetTenantStatus($tenantstatus){
	global $con;
    $CMB = " <select name='cmb_A_status'  id='cmb_A_status' class='form-control select'>";
    $CMB .= "<option value=''>Select</option>";
	$SEL =  "Select lookcode,lookname from in_lookup where looktype='STATUS' and lookname<>'XX'";
	$RES = mysqli_query($con,$SEL);
	while ($ARR = mysqli_fetch_array($RES)) {
	   $SEL = "";
	   if($tenantstatus == $ARR['lookcode']){ $SEL =  "SELECTED";}
	   $CMB .= "<option value='".trim($ARR['lookcode'])."' $SEL >".trim($ARR['lookname'])."</option>";
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
		if(strtoupper($company)== strtoupper($ARR['companyname'])){ $SEL =  "SELECTED";}
               $CMB .= "<option class='fa' value='".$ARR['id']."' $SEL >".$ARR['companyname']."</option>";
    }
    $CMB .= "</select></div>";

    return $CMB;
}
function GetOccupancyStatus($status){
	global $con;
    $CMB = " <select name='cmb_A_occupstatus'  id='cmb_A_occupstatus' class='form-control select'>";
    $CMB .= "<option value=''>Select</option>";
	$SEL =  "Select lookcode,lookname from in_lookup where looktype='OCCUPANCY STATUS' and lookname<>'XX'";
	$RES = mysqli_query($con,$SEL);
	while ($ARR = mysqli_fetch_array($RES)) {
	   $SEL = "";
	   if($status == $ARR['lookcode']){ $SEL =  "SELECTED";}
	   $CMB .= "<option value='".trim($ARR['lookcode'])."' $SEL >".trim($ARR['lookname'])."</option>";
	}
	$CMB .= "</select>";
	return $CMB;
}
function GetContractStatus($status){
	global $con;
    $CMB = " <select name='cmb_A_contractstatus'  id='cmb_A_contractstatus' class='form-control select'>";
    $CMB .= "<option value=''>Select</option>";
	$SEL =  "Select lookcode,lookname from in_lookup where looktype='CONTRACT STATUS' and lookname<>'XX'";
	$RES = mysqli_query($con,$SEL);
	while ($ARR = mysqli_fetch_array($RES)) {
	   $SEL = "";
	   if($status == $ARR['lookcode']){ $SEL =  "SELECTED";}
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
</script>
<script type='text/javascript'>

function loadpage(i){
   if(i==2){
       document.frmEdit.action='editleaselist.php?dr=edit&ID='+document.getElementById('mode').value;
       document.frmEdit.submit();
   }
   if(i==4){
       var frame= document.getElementById('frame4');
       frame.src='financialinfo.php?PARENTID=<?php echo $_REQUEST['ID']; ?>';
       frame.load();
   }
   if(i==3){
   var frame= document.getElementById('frame3');
   frame.src='upload_documents.php?entitytype=PROPERTY_DOCUMENTS&ID=<?php echo $_REQUEST['ID']; ?>';
   frame.load();
   }
   if(i==5){
   var frame= document.getElementById('frame5');
   frame.src='receiptinfo.php?PARENTID=<?php echo $_REQUEST['ID']; ?>';
   frame.load();
   }
   if(i==6){
   var frame= document.getElementById('frame6');
   frame.src='chequeinfo.php?PARENTID=<?php echo $_REQUEST['ID']; ?>';
   frame.load();
   }
}

</script>
