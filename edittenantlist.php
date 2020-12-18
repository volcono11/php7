<?php
session_start();
//echo $_SESSION['pr'];// = isset($_REQUEST['pr']) ? $_REQUEST['pr'] : '';
$insert = $update = $delete = "false";

if(false !== strpos($_SESSION['pr'],"I")) $insert = "true" ;
if(false !== strpos($_SESSION['pr'],"U")) $update = "true" ;
if(false !== strpos($_SESSION['pr'],"D")) $delete = "true" ;

require "connection.php";
require "pagingObj.php";
$grid = new MyPHPGrid('frmPage');
$grid->TableName = "tbl_tenant";
$grid->formName = "edittenantlist.php";
$grid->SyncSession($grid);
$grid->TableNameChild = "";
if($_REQUEST['ID'] != "0") {
             $mode=$_REQUEST['ID'];

             $SQL = " Select * from tbl_tenant where id='".$_REQUEST['ID']."'";
             $SQLRes =  mysqli_query($con,$SQL) or die(mysqli_error()."<br>".$SQL);
              if(mysqli_num_rows($SQLRes)>=1){
                while($loginResultArray   = mysqli_fetch_array($SQLRes)){
                   $saveid=  $loginResultArray['id'];
                   //$propertyownercode = htmlspecialchars($loginResultArray['id']);
                   $tenanttype=  $loginResultArray['tenanttype'];
                   $company=  $loginResultArray['company'];
                   $trnno=  $loginResultArray['trnno'];
                   $contactno=  $loginResultArray['contactno'];
                   $tenantname=  $loginResultArray['tenantname'];
                   $nationality=  $loginResultArray['nationality'];
                   $emiratesid=  $loginResultArray['emiratesid'];
                   $passportno=  $loginResultArray['passportno'];
                   $mobileno=  $loginResultArray['mobileno'];
                   $email=  $loginResultArray['email'];
                   $address=  $loginResultArray['address'];
                   $status=  $loginResultArray['status'];
                   $contactperson= $loginResultArray['contactperson'];
                   if($tenanttype=='83001')
                   {
                    $display="none";
                    $displayrow="table-row";
                    $displaycell="table-cell";
                    }

                    else
                    {
                    $display="table-cell";
                    $displayrow="none";
                    $displaycell="none";
                     }
                }
              }
           }else{
              $mode="";
              $saveid =  GetLastSqeID("tbl_tenant");
              $tenanttype = "";
              $company= "";
              $trnno= "";
              $contactno= "";
              $tenantname= "";
              $email=$nationality= "";
              $emiratesid=$passportno=$mobileno=$address=$status=$contactperson= "";
              
              
}

if(isset($_REQUEST['dr'])=='view'){
   $edit="none";
   $view="inline";
   $title="Viewing : ".$tenantname."";
}else if(isset($_REQUEST['dr'])=='edit'){
      $edit="inline";
      $view="none";
      $title="Editing : ".$tenantname."";
}else{
      $edit="inline";
      $view="none";
      $title="Adding New Tenant";
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
var tr2=document.getElementById('tr2');
var tr3=document.getElementById('tr3');
var tr4=document.getElementById('tr4');
var tr5=document.getElementById('tr5');
var tr6=document.getElementById('tr6');
if(cattype== '83002'){
tr2.style.display="none";
tr3.style.display="none";
tr4.style.display="none";
tr5.style.display="table-cell";
tr6.style.display="table-cell";
}
if(cattype=='83001')
{

tr5.style.display="none";
tr6.style.display="none";
tr2.style.display = 'table-row';
tr3.style.display = 'table-cell';
tr4.style.display = 'table-cell';
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

       var cmb_A_tenanttype=document.getElementById('cmb_A_tenanttype');
       if(cmb_A_tenanttype){
          if ((cmb_A_tenanttype.value==null)||(cmb_A_tenanttype.value=="")){
               alertify.alert("Enter Tenant Type", function () {
               cmb_A_tenanttype.focus();

          });
             return;
          }
       }
       var txt_A_tenantname=document.getElementById('txt_A_tenantname');
       if(txt_A_tenantname){
          if ((txt_A_tenantname.value==null)||(txt_A_tenantname.value=="")){
               alertify.alert("Enter Tenant Name", function () {
               txt_A_tenantname.focus();

          });
             return;
          }
       }
       
       var cmb_A_status=document.getElementById('cmb_A_status');
       if(cmb_A_status){
          if ((cmb_A_status.value==null)||(cmb_A_status.value=="")){
               alertify.alert("Enter Status", function () {
               cmb_A_status.focus();

          });
             return;
          }
       }




       insertfunction(get(document.frmEdit),action)
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
                                //alert(s1);
                               var s2 = "Record Saved";
                               var s3 = "Record Updated";
                               if(s1.toString() == s2.toString()){
                                 alertify.alert("Record Saved", function () {
                                 window.location.href='edittenantlist.php?dr=edit&ID='+document.getElementById('saveid').value;
                                });
                               }else if(s1.toString() == s3.toString()){
                                alertify.alert("Record Updated", function () {
                                window.location.href='edittenantlist.php?dr=edit&ID='+document.getElementById('mode').value;

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
                                 window.location.href='edittenantlist.php?dr=add&ID=0';
                                });
                               }else if(s1.toString() == s3.toString()){
                                alertify.alert("Record Updated", function () {
                                window.location.href='edittenantlist.php?dr=add&ID=0';

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
                                window.location.href='tenantlist.php?ID=0&pr=<?php echo $_SESSION["pr"]; ?>';
                               }else if(s1.toString() == s3.toString()){
                                alertify.alert("Record Updated", function () {
                                window.location.href='tenantlist.php?ID=0&pr=<?php echo $_SESSION["pr"]; ?>';

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

                 <a class="pull-left" href="tenantlist.php?pr=<?php echo $_SESSION['pr']; ?>&frmPage_rowcount=<?php echo $_SESSION['frmPage_rowcount']; ?>&txtsearch=<?php echo $_SESSION['txtsearch']; ?>&frmPage_startrow=<?php echo $_SESSION['frmPage_startrow'];?>" data-toggle="tooltip" data-placement="right" title="Back to Home"><i class='fa fa-backward'></i></a>
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
                            <li class="active"><a href="#personal" onclick='javascript:loadpage(2);' data-toggle="tab"><i class="fa fa-desktop" aria-hidden="true"></i>&nbsp; Tenant Details</a></li>

                           <li><a href="#documents" onclick='javascript:loadpage(3);' data-toggle="tab"><i class="fa fa-folder-open" aria-hidden="true"></i>&nbsp; Document(s)</a></li>
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

                                                              <td class='dvtCellLabel' style='border: 1px solid #ccc;'> Tenant Type:$mandatory</td>
                                                              <td style='width:20%;border: 1px solid #ccc;'>".GetTenantType($tenanttype)."
                                                              <td id='tr5' class='dvtCellLabel' style='border: 1px solid #ccc;display:$display;'> Tenant Name:$mandatory</td>
                                                              <td id='tr6' style='width:20%;border: 1px solid #ccc;display:$display;'><input type='text' class='form-control txt'   name='txt_A_tenantname' id='txt_A_tenantname'  value='$tenantname' >
                                                              <td  id = 'tr4'class='dvtCellLabel' style='border: 1px solid #ccc;display:$displaycell'> Company Name:</td>
                                                               <td id='tr3' style='width:20%;border: 1px solid #ccc;display:$displaycell'>".GetCompany($company)."


                                                              </tr>
                                                              <tr id='tr2' style='display:$displayrow'>
                                                              <td id='tr3' class='dvtCellLabel' style='border: 1px solid #ccc;'>Contact Person:</td>
                                                              <td style='width:20%;border: 1px solid #ccc;'><input type='text' class='form-control txt'   name='txt_A_contactperson' id='txt_A_contactperson'  value='$contactperson' ></td>
                                                              <td class='dvtCellLabel' style='border: 1px solid #ccc;'>Contact No:</td>
                                                              <td style='width:20%;border: 1px solid #ccc;'><input type='text' onkeypress='return AllowNumeric1(event)' class='form-control txt'   name='txt_A_contactno' id='txt_A_contactno'  value='$contactno' ></td>
                                                               <td class='dvtCellLabel' style='border: 1px solid #ccc;'>TRN No</td>
                                                              <td style='width:20%;border: 1px solid #ccc;'><input type='text' onkeypress='return AllowNumeric1(event)' class='form-control txt'   name='txt_A_trnno' id='txt_A_trnno'  value='$trnno' ></td>
                                                              </tr>
                                                              <tr>
                                                              <td class='dvtCellLabel' style='border: 1px solid #ccc;'>Address:</td>
                                                              <td style='width:20%;border: 1px solid #ccc;'colspan=3><input type='text'  class='form-control txt'   name='txt_A_address' id='txt_A_address'  value='$address' ></td>
                                                              <td class='dvtCellLabel' style='border: 1px solid #ccc;'>Mobile:</td>
                                                              <td style='width:20%;border: 1px solid #ccc;'><input type='text' onkeypress='return AllowNumeric1(event)' class='form-control txt'   name='txt_A_mobileno' id='txt_A_mobileno'  value='$mobileno' ></td>
                                                              </tr>

                                                              <td class='dvtCellLabel' style='border: 1px solid #ccc;'>Email:</td>
                                                              <td style='width:20%;border: 1px solid #ccc;'><input type='text' class='form-control txt'   name='txt_A_email' id='txt_A_email'  value='$email' ></td>
                                                              <td class='dvtCellLabel' style='border: 1px solid #ccc;'>Status:$mandatory</td>
                                                              <td style='width:20%;border: 1px solid #ccc;'>".GetTenantStatus($status)."</td>
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
                        
                        $entrydata.="  <button class='btn btn-danger inputs' style='margin-top:-5px;' name='btndanger' type='button'  onclick ='javascript:closeediting(\"tenantlist.php?ps=1&pr=".$_SESSION['pr']."\");'>Close &nbsp;<i class='fa fa-close' aria-hidden='true'></i></button>";
                        $entrydata.="  <button class='btn btn-primary inputs' style='margin-top:-5px;' name='btndanger' type='button'  onclick ='javascript:closeediting(\"tenantlist.php?ps=1&pr=".$_SESSION['pr']."&frmPage_rowcount=".$_SESSION['frmPage_rowcount']."&txtsearch=".$_SESSION['txtsearch']."&frmPage_startrow=".$_SESSION['frmPage_startrow']."\");'>Back &nbsp;<i class='fa fa-backward' aria-hidden='true'></i></button>";

                        $entrydata.="</div>";
                        $entrydata.= "</form> ";

echo  $entrydata;

?>

                           </div>

		<div class="tab-pane" id="documents">
		<iframe id="frame3" name="frame3" scrolling="no" onload='this.width=screen.width;this.height=screen.height;'  frameborder="0" style="position: relative; width: 100%; "></iframe>
		</div>



</section>
<?php
/*function GetTenantType($tenanttype){
	global $con;
	$CMB = " <select name='cmb_A_tenanttype'  id='cmb_A_tenanttype' class='form-control' onChange='HideColumns(this.value)' >";
    $CMB .= "<option value=''>Select</option>";
	$seqSQL = "select id ,tenanttype from tbl_tenanttype";
	$result=mysqli_query($con,$seqSQL) or die(mysqli_error()."<br>".$seqSQL);
	while($ARR=mysqli_fetch_array($result)){
		$SEL = "";
		if(strtoupper($tenanttype)== strtoupper($ARR['id'])){ $SEL =  "SELECTED";}
               $CMB .= "<option value='".strtoupper($ARR['id'])."' $SEL >".$ARR['tenanttype']."</option>";
    }
    $CMB .= "</select>";
    return $CMB;
} */
function GetTenantType($tenanttype){
	global $con;
    $CMB = " <select name='cmb_A_tenanttype'  id='cmb_A_tenanttype' class='form-control' onChange='HideColumns(this.value)'>";
    $CMB .= "<option value=''>Select</option>";
	$SEL =  "Select lookcode,lookname from in_lookup where looktype='OWNER TYPE' and lookname<>'XX'";
	$RES = mysqli_query($con,$SEL);
	while ($ARR = mysqli_fetch_array($RES)) {
	   $SEL = "";
	   if($tenanttype == $ARR['lookcode']){ $SEL =  "SELECTED";}
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
       document.frmEdit.action='edittenantlist.php?dr=edit&ID='+document.getElementById('mode').value;
       document.frmEdit.submit();
   }
   if(i==3){
   var frame= document.getElementById('frame3');
   frame.src='upload_documents.php?entitytype=PROPERTY_DOCUMENTS&ID=<?php echo $_REQUEST['ID']; ?>';
   frame.load();
   }
}

</script>
