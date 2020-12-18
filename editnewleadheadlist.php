<?php
session_start();
date_default_timezone_set('Asia/Dubai');

require "connection.php";
require "pagingObj1.php";
include("functions_service.php");
include "functions_workflow.php";
//print_r($_REQUEST);
$WF = new WorkFlow($_SESSION['objectid']);
$pagerights = $WF->WorkflowPageRights($_REQUEST['ID'],'in_crmhead');
$Action_button = $WF->actionWorkflow($_REQUEST['ID'],'in_crmhead');

$_SESSION['pr'] = isset($pagerights) ? $pagerights : '';
$insert = $update = $delete = "false";

if(false !== strpos($_SESSION['pr'],"I")) $insert = "true" ;
if(false !== strpos($_SESSION['pr'],"U")) $update = "true" ;
if(false !== strpos($_SESSION['pr'],"D")) $delete = "true" ;


$grid = new MyPHPGrid('frmPage');
$grid->TableName = "in_crmhead";
$grid->formName = "newleadheadlist.php";
$grid->SyncSession($grid);
$grid->TableNameChild = "";



$currentDateTime=date('H:i:s');
$newDateTime = date("g:i A", strtotime($currentDateTime));

$lock = "disabled";
if($_REQUEST['ID'] != "0") {
             $mode=$_REQUEST['ID'];

              $sql_1 = "select count(distinct(buildingcode)) as count_building from tbl_clientserviceproperty where docid='$mode'";
              $res_1 = mysqli_query($con,$sql_1);
              if(mysqli_num_rows($res_1)>=1){
                   $arr_1 = mysqli_fetch_array($res_1);
                   //$propertycount = $arr_1['count_property'];
                   $buildingcount = $arr_1['count_building'];
                   $upsql = "update in_crmhead set buildingcount='$buildingcount' where id='$mode'";
                   mysqli_query($con,$upsql);
              }
             
             
             $SQL = " Select *,DATE_FORMAT(startdate,'%d-%m-%Y') as startdate,DATE_FORMAT(docdate,'%d-%m-%Y') as docdate,DATE_FORMAT(tentativedate,'%d-%m-%Y') as tentativedate,DATE_FORMAT(docapproveddate,'%d-%m-%Y') as docapproveddate from in_crmhead where id='".$_REQUEST["ID"]."'";;
             $SQLRes =  mysqli_query($con,$SQL) or die(mysqli_error()."<br>".$SQL);
              if(mysqli_num_rows($SQLRes)>=1){
                while($loginResultArray   = mysqli_fetch_array($SQLRes)){
                   $mode =  $loginResultArray['id'];
                   $saveid =  $loginResultArray['id'];
				   #### workflow ####
                   $formsendto = $loginResultArray['formsendto'];
				   $approvedby = $loginResultArray['approvedby'];
				   //$approvalrole = $loginResultArray['approvalrole'];
				   $formapprovalcount = $loginResultArray['approvalcount'];
				   ###################
                   $priority=   $loginResultArray['priority'];
                   $enquirycategory =  $loginResultArray['enquirycategory'];
                   $docdate =  $loginResultArray['docdate'];
                   $tentativedate=$loginResultArray['tentativedate'];
                   if($tentativedate=='00-00-0000')$tentativedate="";

                   $docapproveddate=$loginResultArray['docapproveddate'];
                   if($docapproveddate=='00-00-0000')$docapproveddate="";
                   $accountheadcode =  $loginResultArray['objectcode'];
                   $objectname  =  $loginResultArray['objectname'];
                   $enquirysource =  $loginResultArray['leadsource'];
                   $enquirystatus =  $loginResultArray['leadstatus'];
//                   $enquirytype   =  $loginResultArray['enquirytype'];
                   $termsandcondition  =  $loginResultArray['termsandcondition'];
                   $remarks =  $loginResultArray['remarks'];
                   //$currency  =  $loginResultArray['foreigncurrencycode'];
                   //$exchangerate =  $loginResultArray['exchangerate'];
                   $totaldiscount =  $loginResultArray['totallinedisamt'];
                   $totallinenetamout =  $loginResultArray['totallinenetamt'];
                   $userid = $loginResultArray['userid'];
                   $DocNo = $loginResultArray['docno'];
                   $posted = $loginResultArray['posted'];
                   if($posted == "YES") $posted_lock="disabled";
                   else $posted_lock = "";
                   $stcheck = $loginResultArray['stcheck'];
                   $jobname = $loginResultArray['jobname'];
                   $natureofenquiry = $loginResultArray['natureofenquiry'];
                   $suserid = $loginResultArray['suserid'];
                   $enquiryby = $loginResultArray['enquiryby'];
                   $docname = $loginResultArray['docname'];
                   $paymentterms = str_replace("<br/>","\n",$loginResultArray['paymentterms']) ;
                   //$propertyname =$loginResultArray['propertyname'];
                   $buildingname= $loginResultArray['buildingname'];
                   $floordetails= $loginResultArray['floordetails'];
                   $propertycount= $loginResultArray['propertycount'];
                   $buildingcount= $loginResultArray['buildingcount'];
                   $durationtype = $loginResultArray['durationtype'];
                   $startdate = $loginResultArray['startdate'];
                   $durationnos = $loginResultArray['durationnos'];
                   $post_to_sp = $loginResultArray['post_to_sp'];
                   if($post_to_sp=="YES") $post_to_sp_display = "disabled";
                   else $post_to_sp_display="";
                   $company = $loginResultArray['companycode'];
                   $divisioncode = $loginResultArray['divisioncode'];
                   $contactperson = $loginResultArray['contactperson'];
                   $phonecode1 = $loginResultArray['phonecode1'];
                   $phonecode2 = $loginResultArray['phonecode2'];
                   $projectname = $loginResultArray['projectname'];
                   $contractreference = $loginResultArray['contractreference'];
                   $billingemail = $loginResultArray['billingemail'];
                   $billingaddress = $loginResultArray['billingaddress'];
                   $vatnumber = $loginResultArray['vatnumber'];
                   $enquiry_date = $loginResultArray['enquiry_date'];
                   if($startdate=="00-00-0000")$startdate="";
                   if($durationtype==""){
                       $lock="disabled";
                   }else{
                       $lock="";
                   }
                   $enquirythrough = $loginResultArray['enquirythrough'];
                   $externalinfo = $loginResultArray['externalinfo'];
                   $enquirystaff  = $loginResultArray['enquirystaff'];
                   if( $enquirythrough == "Others") {
                       $div_external_display = "block";
                       $div_staff_display = "none";
                       }
                   else if( $enquirythrough == "Sales Staff") {
                       $div_external_display = "none";
                       $div_staff_display = "block";
                       }
                   else {
                       $div_external_display = "none";
                       $div_staff_display = "none";
                       }
                   $converted =  $loginResultArray['converted'];
                   $converted_display = "";
                   if($converted == "YES")
                   $converted_display = "disabled";

                   //$addtotalgrossamt=  $loginResultArray['addtotalgrossamt'];
                   //$secomments=  $loginResultArray['secomments'];
                   $docname2=$loginResultArray['docname2'];
                   $nb = $loginResultArray['nb'];
                   if($stcheck=='Completed' || $stcheck=='SR Received'){
                    $displayatt="table-row" ;
                   }else{
                    $displayatt="none" ;
                   }
                   if($nb!=''){
                    $displayreason="table-row" ;
                   }else{
                    $displayreason="none" ;
                   }
                   $createdon = $loginResultArray['createdon'];
                   $wfstatus = $loginResultArray['wfstatus'];
                   $workflowseq = $loginResultArray['workflowseq'];
                   $createdby = $loginResultArray['createdby'];
                   
                }
              }
           }
           else{
             $displayreason="none" ;
             $createdon = date('Y-m-d H:i:s');
             $displayatt="none";
             $mode="";
             $saveid = GetLastSqeID("in_crmhead");
             $enquirycategory = "AMC Enquiry"; //$_REQUEST['cmb_lookuplist'];
             $company = $_REQUEST['cmb_lookuplist1'];
             $enquiryby = $_SESSION['SESSuserID'];
             //$userid = $_REQUEST['cmb_lookuplist1'];
             //$exchangerate=1;
             $seqNum = $saveid;// GetLastSqeID_new("crmdocno");
             if($enquirycategory=="AMC Enquiry"){
                $Doc_Type = "E".substr($enquirycategory,0,3)."/";
             }
             else{
                $Doc_Type = "E".substr($enquirycategory,0,2)."/";
             }
             $DocNo = $Doc_Type.str_pad($seqNum, 5, '0', STR_PAD_LEFT)."/".date("y");
             $docdate = date("d-m-Y");

             $suserid = $_SESSION['SESSuserID'];
             $enquiryby = $_SESSION['SESSuserID'];
             $accountheadcode = 0;

             $propertycount = 0;
             $buildingcount =0;
             $div_staff_display = "none";
             $div_external_display = "none";
             $enquiry_date = date('Y-m-d H:i:s');
             $stcheck = "New";
             $converted='';
             $posted='';
             $docname = '';
             $docname2 = '';
             $userid = '';
             $post_to_sp_display = '';
             $divisioncode  = '';
             $posted_lock="";
             $objectname="";
             $contactperson="";
             $phonecode1=""; 
             $phonecode2=""; $billingemail=""; $billingaddress="";
             $tentativedate="";$projectname=""; $priority=""; $natureofenquiry="";
             $enquirythrough = ""; $enquirystaff=""; $remarks=""; $nb=""; $vatnumber=""; $externalinfo ="";
             
             $workflowseq = $wfstatus ="";
             $createdby = $_SESSION['SESSuserID'];
             if($WF->checkWorkflow($_SESSION['objectid'],$_REQUEST['ID'],'in_crmhead') == "YES") $workflowseq=1;
             
}


if(isset($_REQUEST['dr'])=='view'){
      $edit="none";
      $view="inline";
      $title="$enquirycategory : $DocNo";
}else if(isset($_REQUEST['dr'])=='edit'){
      $edit="inline";
      $view="none";
      $title="$enquirycategory : $DocNo";
}else{
      $edit="inline";
      $view="none";
      $title="$enquirycategory";
}


if(isset($_REQUEST['txtsearch'])){
	$txtsearch =$_REQUEST['txtsearch'];
}
else{
	$txtsearch='';
}

if(isset($_REQUEST['frmPage_rowcount '])){
	$frmPage_rowcount  =$_REQUEST['frmPage_rowcount '];
}
else{
	$frmPage_rowcount ='';
}

if(isset($_SESSION['frmPage_startrow  '])){
	$frmPage_startrow   =$_SESSION['frmPage_startrow  '];
}
else{
	$frmPage_startrow ='';
}

$saveico = '';
$backbutton= '';
$cancelicon= '';
$postico= '';
$approve= '';
$quoteico= '';
$confirmico= '';




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
<style type="text/css">
#overlay
{
        position:absolute;
        width:100%;
        height:100%;
}
#overlay img {
    display: block;
    margin-left: auto;
    margin-right: auto;
}
</style>
<script src="js/alertify.min.js"></script>
<script type="text/javascript" src="js/ajax_functions.js"></script>
<script type="text/javascript" src="js/lib.js"></script>
<script type="text/javascript" src="js/injs.js"></script>
<script type="text/javascript" src="js/myjs.js"></script>
<script language="javascript">
function getDivision(cattype){

      xmlHttp=GetXmlHttpObject()
      if (xmlHttp==null)
      {
                 alert ("Browser does not support HTTP Request")
                 return
      }

      var url="combofunctions_crm2.php?level=divcenter&categorytype="+cattype;
      xmlHttp.onreadystatechange=stateChangedcombo8
      xmlHttp.open("POST",url,true)
      xmlHttp.send(null)

}

function stateChangedcombo8(){
       if (xmlHttp.readyState==4 || xmlHttp.readyState=="complete")
       {
             var s1 = trim(xmlHttp.responseText);
             document.getElementById('cmb_A_divisioncode').innerHTML=s1;
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

             if((iKeyCode<=1 && iKeyCode>=7) || (iKeyCode>=9 && iKeyCode<45 && iKeyCode!=17) || (iKeyCode>=58 && iKeyCode<=255 && iKeyCode!=118)){
                if (iKeyCode!=13) {
                    alertify.error('Numbers Only');
                     return false;
                }
            }
            return true;

}
function Postrecord(postid){
    alertify.confirm("Are you sure you want to Post ?", function (e) {
         if (e) {
           $('#overlay').show();
           $('#personal').hide();
           document.frmEdit.action='editnewleadheadlist.php?POST=FREEZE&POSTID='+postid+'&ID='+document.getElementById('mode').value;
           document.frmEdit.submit();
         } else {
            return;
         }

    });
}

function sendapproval_enquiry(postid){
    alertify.confirm("Are you sure you want to sent for approval ?", function (e) {
         if (e) {

           document.frmEdit.action='editnewleadheadlist.php?POST=SAE&POSTID='+postid+'&ID='+document.getElementById('mode').value;
           document.frmEdit.submit();
         } else {
            return;
         }

    });
}
function approveit(postid){

       alertify.confirm("Are you sure you want to approve ?", function (e) {
         if (e) {
           document.frmEdit.action='editnewleadheadlist.php?POST=APPROVEIT&POSTID='+postid+'&ID='+document.getElementById('mode').value+'&dr=edit';
           document.frmEdit.submit();
         } else {
            return;
         }

    });
}

function AddItem(){

            document.getElementById('childid').value="";
            var tr2=document.getElementById('tr2');
            tr2.style.display="table-row";
            var tr22=document.getElementById('tr22');
            tr22.style.display="table-row";
            var tr222=document.getElementById('tr222');
            tr222.style.display="table-row";

}
function converttoquote(id){

   var encategory= document.getElementById('cmb_A_enquirycategory').value;
   if(encategory=="Project Enquiry"){
      var covtitle = "Are you sure you want to send for Estimation ?";
   }else{
      var covtitle = "Are you sure you want to convert Enquiry to Quote ?";
   }

   alertify.confirm(covtitle, function (e) {
         if (e) {
           $('#overlay').show();
           $('#personal').hide();
           convertfunction('parenttype=LEAD&doctype=QUOTE&lid='+id+'&TYPE='+encategory);
         } else {
            return;
         }

    });
}
function backtopage(id){

    window.location.href="editcrmcontactlist.php?dr=edit&ID="+id;
}
function popupcustomer(){

                 var search = window.document.frmEdit.txt_A_objectname.value;
                 var v1 ='transpopupleadcrm.php?film=filmReceipt&id=0&s='+search;
                 window.open(v1,'name',' left=0,height=370,width=950,left=200,top=300,resizable=no,scrollbars=yes,toolbar=no,status=yes');
}
function clearcustomer(){
         document.getElementById('txt_A_objectcode').value="";
         document.getElementById('txt_A_objectname').value="";
         document.getElementById('txa_A_paymentterms').value="";

}
function updateChildrecord(childid){

    document.frmEdit.action='editnewleadheadlist.php?CHILDID='+childid+'&ID='+document.getElementById('txt_A_enquiryid').value;
    document.frmEdit.submit();
}
function PosttoSalesPerson(){
         document.frmChildEdit.action = 'editnewleadheadlist.php?POST=PTSP&ID='+document.getElementById('txt_A_enquiryid').value;
         document.frmChildEdit.submit();

}
function canceleditingChildrecord(){

    document.frmChildEdit.action = 'editnewleadheadlist.php?ID='+document.getElementById('txt_A_enquiryid').value;
    document.frmChildEdit.submit();
}
function deleteChildrecord(childid){

         alertify.confirm("Are you sure you want to delete ?", function (e) {
         if (e) {
           document.frmChildEdit.action='editnewleadheadlist.php?dr=edit&DEL=DELETE&CHILDID='+childid+'&ID='+document.getElementById('txt_A_enquiryid').value;
           document.frmChildEdit.submit();
         } else {
            return;
         }

       });

}
function postChildrecord(childid){

         alertify.confirm("Are you sure you want to post ?", function (e) {
         if (e) {

           document.frmChildEdit.action='editnewleadheadlist.php?dr=edit&POST=POSTCHILD&POSTCHILDID='+childid+'&ID='+document.getElementById('txt_A_enquiryid').value;
           document.frmChildEdit.submit();
         } else {
            return;
         }

       });

}
function Cancelrecord(postid){
   var tr1=document.getElementById('tr1');
   tr1.style.display="table-row";
      var txt_A_nb=document.getElementById('txt_A_nb');
       if(txt_A_nb){
             if ((txt_A_nb.value==null)||(txt_A_nb.value=="")){
                  alertify.alert("Enter reason for cancel", function () {
                  txt_A_nb.focus();

             });
                return;
             }
       }
    alertify.confirm("Are you sure you want to cancel this enquiry?", function (e) {
         if (e) {
           $('#overlay').show();
           $('#personal').hide();
           document.frmEdit.action='editnewleadheadlist.php?POST=CANCEL&reason='+txt_A_nb.value+'&POSTID='+postid+'&ID='+document.getElementById('mode').value;
           document.frmEdit.submit();
         } else {
            return;
         }

    });
}
function editingChildrecord(){

       var txd_A_taskdate=document.getElementById('txd_A_taskdate');
       if(txd_A_taskdate){
          if ((txd_A_taskdate.value=="")||(txd_A_taskdate.value==null)){
               alertify.alert("Enter task date", function () {
               txd_A_taskdate.focus();

          });
             return;
          }
       }

       var txt_A_title=document.getElementById('txt_A_title');
       if(txt_A_title){
          if ((txt_A_title.value=="")||(txt_A_title.value==null)){
               alertify.alert("Enter Title", function () {
               txt_A_title.focus();

          });
             return;
          }
       }
       var cmb_A_userid=document.getElementById('cmb_A_userid');
       if(cmb_A_userid){
          if ((cmb_A_userid.value=="")||(cmb_A_userid.value==null)){
               alertify.alert("Select assigned to", function () {
               cmb_A_userid.focus();

          });
             return;
          }
       }
    insertChildfunction(get(document.frmChildEdit))
    return;

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
                               var s2 = "Record Saved";
                               var s3 = "Record Updated";
                               if(s1.toString() == s2.toString()){
                                 alertify.alert("Record Saved", function () {
                                  document.frmChildEdit.action='editnewleadheadlist.php?ID='+document.getElementById('txt_A_enquiryid').value;
                                  document.frmChildEdit.submit();
                                 });
                               }else if(s1.toString() == s3.toString()){
                                alertify.alert("Record Updated", function () {
                                  document.frmChildEdit.action='editnewleadheadlist.php?ID='+document.getElementById('txt_A_enquiryid').value;
                                  document.frmChildEdit.submit();
                                 });
                               }else{
                                alertify.alert(s1);
                               }

                         }

                   }
function cancleeditingnew(){

    document.frmEdit.action='newleadheadlist.php?frmPage_startrow='+document.getElementById('recordstartrow').value+'&frmPage_rowcount='+document.getElementById('recordperpage').value+'&txtsearch='+document.getElementById('searchvalue').value;
    document.frmEdit.submit();

}
function completequote(docid){
   var tr11=document.getElementById('tr11');
   tr11.style.display="table-row";
      var userfile3=document.getElementById('userfile3');
       if(userfile3){
             if ((userfile3.value==null)||(userfile3.value=="")){
                  alertify.alert("Attachment required!", function () {
                  userfile3.focus();

             });
                return;
             }
       }
        alertify.confirm("Are you sure you want to complete the enquiry?", function (e) {
         if (e) {
           $('#overlay').show();
           $('#personal').hide();
           document.getElementById('frmEdit').action='editnewleadheadlist.php?MODE=COMPLETE&ID='+docid;
           document.getElementById('frmEdit').submit();
         } else {
            return;
         }

    });




}

function editingrecord(action)
{
	
	
       var cmb_A_objectcode=document.getElementById('cmb_A_objectcode');
       if(cmb_A_objectcode){
             if ((cmb_A_objectcode.value==null)||(cmb_A_objectcode.value=="")){
                  alertify.alert("Select Customer Name", function () {
                  cmb_A_objectcode.focus();

             });
                return;
             }
       }

       var txd_A_docdate=document.getElementById('txd_A_docdate');
       if(txd_A_docdate){
          if ((txd_A_docdate.value=="00-00-0000")||(txd_A_docdate.value=="")){
               alertify.alert("Select Enquiry Date", function () {
               txd_A_docdate.focus();

          });
             return;
          }
       }

       var txt_A_contactperson=document.getElementById('txt_A_contactperson');
       if(txt_A_contactperson){
             if ((txt_A_contactperson.value==null)||(txt_A_contactperson.value=="")){
                  alertify.alert("Enter Contact Person", function () {
                  txt_A_contactperson.focus();

             });
                return;
             }
       }

       var txt_A_phonecode1=document.getElementById('txt_A_phonecode1');
       if(txt_A_phonecode1){
             if ((txt_A_phonecode1.value==null)||(txt_A_phonecode1.value=="")){
                  alertify.alert("Enter Contact Number 1", function () {
                  txt_A_phonecode1.focus();

             });
                return;
             }
       }

       var txt_A_billingemail=document.getElementById('txt_A_billingemail');
       if(txt_A_billingemail){
             if ((txt_A_billingemail.value==null)||(txt_A_billingemail.value=="")){
                  alertify.alert("Enter Email ID", function () {
                  txt_A_billingemail.focus();

             });
                return;
             }
       }

       var txd_A_tentativedate=document.getElementById('txd_A_tentativedate');
       if(txd_A_tentativedate){
          if ((txd_A_tentativedate.value=="00-00-0000")||(txd_A_tentativedate.value=="")){
               alertify.alert("Select Tentative Date", function () {
               txd_A_tentativedate.focus();

          });
             return;
          }
       }

       var txt_A_projectname=document.getElementById('txt_A_projectname');
             if(txt_A_projectname){
             if ((txt_A_projectname.value==null)||(txt_A_projectname.value=="")){
                  alertify.alert("Enter Project Name", function () {
                  txt_A_projectname.focus();

             });
                return;
             }
       }
       var cmb_A_divisioncode=document.getElementById('cmb_A_divisioncode');
             if(cmb_A_divisioncode){
             if ((cmb_A_divisioncode.value==null)||(cmb_A_divisioncode.value=="")){
                  alertify.alert("Select Profitcenter", function () {
                  cmb_A_divisioncode.focus();

             });
                return;
             }
       }

       var txt_A_natureofenquiry=document.getElementById('txt_A_natureofenquiry');
       if(txt_A_natureofenquiry){
             if ((txt_A_natureofenquiry.value==null)||(txt_A_natureofenquiry.value=="")){
                  alertify.alert("Enter Nature of Enquiry", function () {
                  txt_A_natureofenquiry.focus();

             });
                return;
             }
       }

       var txt_A_jobname=document.getElementById('txt_A_jobname');
       if(txt_A_jobname){
             if ((txt_A_jobname.value==null)||(txt_A_jobname.value=="")){
                  alertify.alert("Enter Required Services", function () {
                  txt_A_jobname.focus();

             });
                return;
             }
       }
        var txd_A_startdate=document.getElementById('txd_A_startdate');
       if(txd_A_startdate){
          if ((txd_A_startdate.value=="00-00-0000")||(txd_A_startdate.value=="")){
               alertify.alert("Enter Start Date", function () {
               txd_A_startdate.focus();

          });
             return;
          }
       }

       var cmb_A_userid=document.getElementById('cmb_A_userid');
           if(cmb_A_userid){
             if ((cmb_A_userid.value=="Select")||(cmb_A_userid.value=="")){
                  alertify.alert("Select Inspection Assign to", function () {
                  cmb_A_userid.focus();

             });
                return;
             }
       }
       /*document.getElementById('btnsuccess').disabled=true;
       document.getElementById('btnwarning').disabled=true;
       document.getElementById('btndanger').disabled=true;*/
		insertfunction(get(document.frmEdit),action);
        return;
      /* document.getElementById('frmEdit').action='in_action.php?action='+action;
       document.getElementById('frmEdit').submit();
       return;*/
}
                   var xmlHttp
                   function convertfunction(parameters)
                   {
                          //alert(parameters);
                          xmlHttp=GetXmlHttpObject()
                          if (xmlHttp==null)
                          {
                                 alert ("Browser does not support HTTP Request")
                                 return
                          }


                          var url="Convertto.php?"+parameters
                          xmlHttp.onreadystatechange=stateChanged
                          xmlHttp.open("POST",url,true)
                          xmlHttp.send(null)
                   }
                   function stateChanged()
                   {
                         if (xmlHttp.readyState==4 || xmlHttp.readyState=="complete")
                         {
                                var s1 = trim(xmlHttp.responseText);
                                //alert(s1);
                                document.frmEdit.action='editnewleadheadlist.php?frmPage_startrow='+document.getElementById('recordstartrow').value+'&frmPage_rowcount='+document.getElementById('recordperpage').value+'&txtsearch='+document.getElementById('searchvalue').value+'&dr=edit&ID='+document.getElementById('mode').value;
                                document.frmEdit.submit();

                         }
                   }
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
                                 window.location.href='editnewleadheadlist.php?frmPage_startrow='+document.getElementById('recordstartrow').value+'&frmPage_rowcount='+document.getElementById('recordperpage').value+'&txtsearch='+document.getElementById('searchvalue').value+'&dr=edit&ID='+document.getElementById('saveid').value;
                                });
                               }else if(s1.toString() == s3.toString()){
                                alertify.alert("Record Updated", function () {
                                window.location.href='editnewleadheadlist.php?frmPage_startrow='+document.getElementById('recordstartrow').value+'&frmPage_rowcount='+document.getElementById('recordperpage').value+'&txtsearch='+document.getElementById('searchvalue').value+'&dr=edit&ID='+document.getElementById('mode').value;

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
                                 window.location.href='editnewleadheadlist.php?frmPage_startrow='+document.getElementById('recordstartrow').value+'&frmPage_rowcount='+document.getElementById('recordperpage').value+'&txtsearch='+document.getElementById('searchvalue').value+'&dr=add&ID=0';
                                });
                               }else if(s1.toString() == s3.toString()){
                                alertify.alert("Record Updated", function () {
                                window.location.href='editnewleadheadlist.php?frmPage_startrow='+document.getElementById('recordstartrow').value+'&frmPage_rowcount='+document.getElementById('recordperpage').value+'&txtsearch='+document.getElementById('searchvalue').value+'&dr=add&ID=0';

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
                                window.location.href='newleadheadlist.php?ID=0';
                               }else if(s1.toString() == s3.toString()){
                                alertify.alert('Record Updated');
                                window.location.href='newleadheadlist.php';
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


function loadframe(ext,docname){

                var strURL="combofunctions_pro.php?level=purchasedoc&docname="+docname+"&ext="+ext;

                var req = getXMLHTTP();

                if (req) {

                        req.onreadystatechange = function() {
                                if (req.readyState == 4) {
                                        // only if "OK"
                                        if (req.status == 200) {

                                                document.getElementById('popupdiv').innerHTML=req.responseText;
                                        } else {
                                                alert("Problem while using XMLHTTP:\n" + req.statusText);
                                        }
                                }
                        }
                        req.open("GET", strURL, true);
                        req.send(null);
                }

}
function getXMLHTTP() { //fuction to return the xml http object
                var xmlhttp=false;
                try{
                        xmlhttp=new XMLHttpRequest();
                }
                catch(e)        {
                        try{
                                xmlhttp= new ActiveXObject("Microsoft.XMLHTTP");
                        }
                        catch(e){
                                try{
                                xmlhttp = new ActiveXObject("Msxml2.XMLHTTP");
                                }
                                catch(e1){
                                        xmlhttp=false;
                                }
                        }
                }

                return xmlhttp;
}
function updatedate(postid){
      var txd_A_deliverydate=document.getElementById('txd_A_deliverydate');
       if(txd_A_deliverydate){
          if ((txd_A_deliverydate.value=="")||(txd_A_deliverydate.value==null)){
               alertify.alert("Enter Expected Closing Date", function () {
               txd_A_deliverydate.focus();

          });
             return;
          }
       }
      alertify.confirm("Are you sure you want to update closing date ?", function (e) {
         if (e) {
           document.frmEdit.action='editnewleadheadlist.php?POST=UPDATEDATE&closedate='+txd_A_deliverydate.value+'&POSTID='+postid+'&ID='+document.getElementById('mode').value;
           document.frmEdit.submit();
         } else {
            return;
         }

    });
}
function getbuilding(cattype){
       xmlHttp=GetXmlHttpObject()
      if (xmlHttp==null)
      {
                 alert ("Browser does not support HTTP Request")
                 return
      }

      var url="combofunctions_project.php?level=getbuilding&categorytype="+document.getElementById('cmb_A_objectcode').value+"&propertycode="+cattype;
      xmlHttp.onreadystatechange=stateChangedcombobuilding
      xmlHttp.open("POST",url,true)
      xmlHttp.send(null)
}
function stateChangedcombobuilding(){
       if (xmlHttp.readyState==4 || xmlHttp.readyState=="complete")
       {

             var s1 = trim(xmlHttp.responseText);
             document.getElementById('cmb_A_buildingname').innerHTML=s1;

       }
}
function getClientDetails(catval){
      xmlHttp=GetXmlHttpObject()
      if (xmlHttp==null)
      {
                 alert ("Browser does not support HTTP Request")
                 return
      }
      var division = document.getElementById('cmb_A_divisioncode').value;
      var company = document.getElementById('txt_A_companycode').value;
      var url="combofunctions_crm2.php?level=contactdetails&categorytype="+catval+"&division="+division+"&company="+company;
      xmlHttp.onreadystatechange=stateChangedContacts
      xmlHttp.open("POST",url,true)
      xmlHttp.send(null)
}
function stateChangedContacts(){
       if (xmlHttp.readyState==4 || xmlHttp.readyState=="complete")
       {

             var s1 = trim(xmlHttp.responseText);
             var word = s1.split('***');
             document.getElementById('txt_A_contactperson').value=word[0];
             document.getElementById('txt_A_phonecode1').value=word[1];
             document.getElementById('txt_A_phonecode2').value=word[2];
             document.getElementById('txt_A_billingemail').value=word[3];
             document.getElementById('txt_A_billingaddress').value=word[4];
             document.getElementById('txt_A_vatnumber').value=word[5];
             document.getElementById('txt_A_objectname').value=word[6];

       }
}
function getDurationNos(cattype){
       if(cattype!=""){
          document.getElementById('txt_A_durationnos').disabled=false;
       }else{
          document.getElementById('txt_A_durationnos').value="";
          document.getElementById('txt_A_durationnos').disabled=true;
       }

}
function getEnquiry_Through(cattype) {
         if(cattype.value== "Others"){
              document.getElementById('div_enquirythrough').style.display="block";
              document.getElementById('div_enquirythrough2').style.display="block";
              document.getElementById('div_enquirythrough3').style.display="none";
              document.getElementById('div_enquirythrough4').style.display="none";
         }
         else if(cattype.value== "Sales Staff"){
              document.getElementById('div_enquirythrough').style.display="none";
              document.getElementById('div_enquirythrough2').style.display="none";
              document.getElementById('div_enquirythrough3').style.display="block";
              document.getElementById('div_enquirythrough4').style.display="block";
         }
         else{
              document.getElementById('div_enquirythrough').style.display="none";
              document.getElementById('div_enquirythrough2').style.display="none";
              document.getElementById('div_enquirythrough3').style.display="none";
              document.getElementById('div_enquirythrough4').style.display="none";
         }
}
function blockSpecialChar(e) {
            var k = e.keyCode;
            return (k!=39);
}
</script>
</head>
 <body class="hold-transition sidebar-mini" oncontextmenu="return false;">

         <section class="content-header"  style='margin-top:10px;'>

                 <a class="pull-left" href="newleadheadlist.phppr=<?php echo $_SESSION['pr']; ?>&frmPage_rowcount=<?php echo $_SESSION['frmPage_rowcount']; ?>&txtsearch=<?php echo $_SESSION['txtsearch']; ?>&frmPage_startrow=<?php echo $_SESSION['frmPage_startrow'];?>"  data-toggle="tooltip" data-placement="right" title="Back to Enquiry"><i class='fa fa-backward'></i></a>
                 <h2 class="title">&nbsp;&nbsp;<?php echo $title; ?></h2>

         </section>

                <section class="content" id='content-content-id' style='padding-right:5px;padding-left:5px;margin-top:-10px;'>

                  <div class="nav-tabs-custom" id="nav-tabs-custom-id">
                        <ul class="nav nav-tabs">
                           <li class="active"><a href="#personal" onclick='javascript:loadpage(2);' data-toggle="tab"><i class="fa fa-desktop" aria-hidden="true"></i>&nbsp; Enquiry</a></li>
                           <?php
                                  if(getunreadmsg($con,$_REQUEST['ID'])>0){
                                  $unreadmsg="<span><font color='red'><b>".getunreadmsg($con,$_REQUEST['ID'])."</b></font></span>&nbsp;";
                                  }else{
                                  $unreadmsg="";
                                  }

                           if($_REQUEST['ID']!=0) {
                             if(((stripos(json_encode($_SESSION['role']),'SALES PERSON') == true && $_SESSION['SESSuserID']== $userid) || stripos(json_encode($_SESSION['role']),'SALES MANAGER') == true || stripos(json_encode($_SESSION['role']),'SALES COORDINATOR') == true) && $jobname!='') {
                                   echo "<li><a href='#Property' onclick='javascript:loadpage(11);' data-toggle='tab'><i class='fa fa-building' aria-hidden='true'></i> Property </a></li>";
                               /*echo" <li><a href='#Services' onclick='javascript:loadpage(3);' data-toggle='tab'><i class='fa fa-cogs' aria-hidden='true'></i> Services</a></li>";
                                   echo "<li><a href='#manpower' data-toggle='tab' onclick='javascript:loadpage(9);'><i class='fa fa-users' aria-hidden='true'></i> Manpower</a></li>";
                                   echo "<li><a href='#scope' data-toggle='tab' onclick='javascript:loadpage(8);'><i class='fa fa-feed' aria-hidden='true'></i> Assets</a></li>";
                                   echo "<li><a href='#Activity'   onclick='javascript:loadpage(14);' data-toggle='tab'><i class='fa fa-tasks' aria-hidden='true'></i>&nbsp; Activity</a></li>";*/
                                   echo " <li><a href='#annexure'   onclick='javascript:loadpage(5);' data-toggle='tab'><i class='fa fa-search' aria-hidden='true'></i> Inspection details</a></li>";
                             }
                           }
                           ?>
                           <?php if($_REQUEST['ID']!=0){ ?>
                           <li><a href="#communication"   onclick='javascript:loadpage(4);' data-toggle="tab"><i class="fa fa-wechat" aria-hidden="true"></i>&nbsp; Notes <?php echo $unreadmsg;?></a></li>
                           <li><a href="#documents"   onclick='javascript:loadpage(20);' data-toggle="tab"><i class="fa fa-folder-open" aria-hidden="true"></i>&nbsp; Documents</a></li>
                           <?php } ?>
                       </ul>

                        <div class="tab-content" id='tab-content-id'>
                         <!-- <div class="tab-pane active" id="personal">-->
                              <div class="box-body no-padding" id='box-body-id'>
                                   <div class='table-responsive no-padding' style='overflow:hidden;'>
<?php

                         $SQL12   = "SELECT count(*) as count,posted from tbl_clientserviceproperty where docid='".$_REQUEST['ID']."' group by posted";
                         $SQLRes12 =  mysqli_query($con,$SQL12) or die(mysqli_error()."<br>".$SQL12);
                         if(mysqli_num_rows($SQLRes12)==1){
                              while($loginResultArray12   = mysqli_fetch_array($SQLRes12)){
                                    if($loginResultArray12['posted'] == "YES")
                                    $property_itemcount = $loginResultArray12['count'];
                              }
                         }

                         $SQL15   = "SELECT count(*) as count from in_translineannexure where invheadid='".$_REQUEST['ID']."'";
                         $SQLRes15 =  mysqli_query($con,$SQL15) or die(mysqli_error()."<br>".$SQL15);
                         if(mysqli_num_rows($SQLRes15)>=1){
                              while($loginResultArray15   = mysqli_fetch_array($SQLRes15)){
                                    $annex_count = $loginResultArray15['count'];
                              }
                         }

                         if($annex_count >0 && $property_itemcount>0){
                            $Tab_validation = 'YES';
                         }
                         else $Tab_validation = 'NO';
						
						 $quotedocno = "";
                         $SQL1   = "SELECT docno from in_crmhead where parentdocno='$DocNo'";
                         $SQLRes1 =  mysqli_query($con,$SQL1) or die(mysqli_error()."<br>".$SQL1);
                         if(mysqli_num_rows($SQLRes1)>=1){
                              while($loginResultArray1   = mysqli_fetch_array($SQLRes1)){
                                    $quotedocno = $loginResultArray1['docno'];
                              }
                         }

                        /* $SQL2   = "SELECT enquiryapproval from in_location where locationcode='".$_SESSION['SESSUserLocation']."'";
                         $SQLRes2 =  mysqli_query($con,$SQL2) or die(mysqli_error()."<br>".$SQL2);
                         if(mysqli_num_rows($SQLRes2)>=1){
                              while($loginResultArray2   = mysqli_fetch_array($SQLRes2)){
                                    $enquiryapproval = $loginResultArray2['enquiryapproval'];
                              }
                         }*/



                         $str = explode("$$$",$docname);
                         $str = substr($docname,(strlen($str[0])+3));
                         //$str = substr($docname, $strlen);  echo
                         if($docname!=""){
                            $actdocname= str_replace(" ","%20",$docname);
                            $ext = strtolower(pathinfo($docname, PATHINFO_EXTENSION));

                            $dwld = $str."&nbsp;&nbsp;<a href='#' onclick='loadframe(\"".$ext."\",\"".$docname."\");' data-toggle='modal' data-target='#myModal'><i class='fa fa-eye' data-toggle='tooltip' data-placement='right' title='View' aria-hidden='true'></i></a>
                                         &nbsp;&nbsp;<a  href='download.php?folder=uploads&filename=".$docname."'><i class='fa fa-download' data-toggle='tooltip' data-placement='right' title='Download' aria-hidden='true'></i></a>";
                         }else{
                            $dwld = "";
                         }

                         $mfgimg="<a href=javascript:popupmodel();  data-target='#myModal33' href='#'   style='float:right'><i class='fa fa-plus' data-toggle='tooltip' data-placement='right' title='Add Property' aria-hidden='true'></i></a>" ;
                         $modelimg="<a href=javascript:popupmodeladd();   style='float:right' data-target='#myModal44'> <i class='fa fa-plus' data-toggle='tooltip' data-placement='right'  title='Add Building' aria-hidden='true'></i></a>";
                         
                         $addClient = '';
                         if($posted!='YES')
                         $addClient="<a href=javascript:popupmodeladdclient();  style='float:right' data-target='#myModal45'> <i class='fa fa-plus' data-toggle='tooltip' data-placement='right'  title='Add Customer' aria-hidden='true'></i></a>";
                         $str2 = substr($docname2, 3);

                         $entrydata = "<form name='frmEdit' method='post' id='frmEdit' enctype='multipart/form-data' autocomplete='off'>
                                         <table class='table table-bordered table-condensed table-fixed  table-responsive' style='table-layout:fixed'>
                                                       
                                                         <tr>
                                                            <td class='dvtCellLabel' style='border: 1px solid #ccc;'>Contact Person<span class='mandatory'>&nbsp;*</span></td>
                                                            <td style='border: 1px solid #ccc;' colspan=1><input type='text' $post_to_sp_display class='form-control txt' name='txt_A_contactperson' id='txt_A_contactperson' value='$contactperson' ></td>
                                                            <td class='dvtCellLabel' style='border: 1px solid #ccc;'>Contact No 1<span class='mandatory'>&nbsp;*</span></td>
                                                            <td style='border: 1px solid #ccc;' colspan=1><input type='text' $post_to_sp_display  onkeypress='return AllowNumeric1(event)' class='form-control txt' name='txt_A_phonecode1' id='txt_A_phonecode1' value='$phonecode1' ></td>
                                                            <td class='dvtCellLabel' style='border: 1px solid #ccc;'>Contact No 2</td>
                                                            <td style='border: 1px solid #ccc;' colspan=1><input type='text' $post_to_sp_display onkeypress='return AllowNumeric1(event)' class='form-control txt' name='txt_A_phonecode2' id='txt_A_phonecode2' value='$phonecode2' ></td>
                                                          </tr>
                                                         <tr>
                                                            <td class='dvtCellLabel' style='border: 1px solid #ccc;'>Email ID<span class='mandatory'>&nbsp;*</span></td>
                                                            <td style='border: 1px solid #ccc;'><input type='text' class='form-control txt'  name='txt_A_billingemail' id='txt_A_billingemail' $post_to_sp_display value='$billingemail' autocomplete='off'></td>
                                                            <td class='dvtCellLabel' style='border: 1px solid #ccc;'>Enquiry Date <span class='mandatory'>&nbsp;*</span></td>
                                                            <td style='border: 1px solid #ccc;'><input type='text' $post_to_sp_display class='form-control txt' data-provide='datepicker' maxlength=10  onkeypress='return AllowNumeric1(event)'  onclick='borderchange();'  name='txd_A_docdate' id='txd_A_docdate'   value='$docdate' placeholder='dd-mm-yyyy' ></td>
                                                            <td class='dvtCellLabel' style='border: 1px solid #ccc;'>Tentative Sub Dt.<span class='mandatory'>&nbsp;*</span></td>
                                                            <td style='border: 1px solid #ccc;'>
                                                            <input type='text' class='form-control txt' $post_to_sp_display data-provide='datepicker' maxlength=10  onkeypress='return AllowNumeric_date(event)'    name='txd_A_tentativedate' id='txd_A_tentativedate'   value='$tentativedate' placeholder='dd-mm-yyyy' ></td>
                                                        </tr>
                                                        <tr>
                                                            <td class='dvtCellLabel' style='border: 1px solid #ccc;'> Project Name <span class='mandatory'>&nbsp;*</span></td>
                                                            <td style='border: 1px solid #ccc;'><input type='text' $post_to_sp_display class='form-control txt' name='txt_A_projectname' id='txt_A_projectname' value='$projectname' onkeypress='return blockSpecialChar(event);'></td>
                                                        <td class='dvtCellLabel' style='border: 1px solid #ccc;'> ProfitCenter<span class='mandatory'>&nbsp;*</span></td>
                                                        <td>".GetDivision($company,$divisioncode)."</td>
                                                        </tr>
                                                        <tr>
                                                            <td class='dvtCellLabel' style='border: 1px solid #ccc;'>Nature of Enq.<span class='mandatory'>&nbsp;*</span></td>
                                                            <td style='border: 1px solid #ccc;' colspan=5><input type='text' $post_to_sp_display class='form-control txt' onkeypress='return blockSpecialChar(event);' name='txt_A_natureofenquiry' id='txt_A_natureofenquiry' value='$natureofenquiry' ></td>

                                                        </tr>
                                                        <tr>
                                                             <td class='dvtCellLabel' style='border: 1px solid #ccc;'>Enquiry through &nbsp;</td>
                                                             <td style='border: 1px solid #ccc;'> ".GetEnquiryThrough($enquirythrough,$post_to_sp_display)."</td>
                                                             <td class='dvtCellLabel' style='border: 1px solid #ccc;'>
                                                             <div id='div_enquirythrough' style='display:$div_external_display;'>If any:&nbsp;</div>
                                                             <div id='div_enquirythrough3' style='display:$div_staff_display;'>Staff Name:&nbsp;</div>
                                                             </td>
                                                             <td style='border: 1px solid #ccc;' colspan=1>
                                                             <div id='div_enquirythrough2' style='display:$div_external_display;'><input type='text' class='form-control txt' $post_to_sp_display name='txt_A_externalinfo' id='txt_A_externalinfo' value='$externalinfo' ></div>
                                                             <div id='div_enquirythrough4' style='display:$div_staff_display;'>".getSalesStaff($enquirystaff,$post_to_sp_display)."</div>
                                                             </td>
                                                             <td class='dvtCellLabel' style='border: 1px solid #ccc;'>Attachment :</td>
                                                            <td style='border: 1px solid #ccc;' colspan=1><input type='hidden' name='MAX_FILE_SIZE'><input type='file' $post_to_sp_display name='userfile' class='upload'  id='userfile'>
                                                            $dwld
                                                             </td>
                                                          </tr>
                                                          <tr>
                                                            <td class='dvtCellLabel' style='border: 1px solid #ccc;'>Remarks :</td>
                                                            <td style='border: 1px solid #ccc;' colspan=3><input type='text'  $post_to_sp_display class='form-control txt' onkeypress='return blockSpecialChar(event);' name='txa_A_remarks' id='txa_A_remarks' value='$remarks' ></td>
                                                            <td class='dvtCellLabel' style='border: 1px solid #ccc;'>Status :</td>
                                                            <td style='border: 1px solid #ccc;'><b>".GetWorlflowFStatus($wfstatus)."</b></td>
                                                          </tr>

                                                          <tr id='tr1' name='tr1' style='display:$displayreason'>
                                                               <td class='dvtCellLabel' style='border: 1px solid #ccc;'>Cancel Reason :</td>
                                                               <td style='border: 1px solid #ccc;' colspan=5>
                                                               <input type='text' name='txt_A_nb'  class='form-control txt' id='txt_A_nb'  value='$nb' onkeypress='return blockSpecialChar(event);'>
                                                          </tr>
                                                               <input type='hidden' name='docno' class=textboxcombo id='docno' value='$DocNo'>
                                                               <input type='hidden' class='form-control txt' name='txt_A_companycode' readonly id='txt_A_companycode' value='$company' >
                                                               <!--<input type='hidden' name='txt_A_suserid' class=textboxcombo id='txt_A_suserid' value='$suserid'> -->
                                                               <input type='hidden' name='txt_A_doctype' class=textboxcombo id='txt_A_doctype' value='LEAD'>
                                                               <input type='hidden' name='txt_A_enquiryby' class=textboxcombo id='txt_A_enquiryby' value='$enquiryby'>
                                                               <input type='hidden' name='txt_A_enquiry_date'  id='txt_A_enquiry_date' value='$enquiry_date'>
                                                               <input type='hidden' id='txt_A_parentdoctype' name='txt_A_parentdoctype' value='ORDER'>
                                                               <input type='hidden' id='txt_A_locationcode' name='txt_A_locationcode' value='".$_SESSION['SESSUserLocation']."'>
                                                               <input type='hidden' name='mode' class=textboxcombo id='mode' value='$mode'>
                                                               <input type='hidden' name='modeid' class=textboxcombo id='modeid' value='save'>
                                                               <input type='hidden' name='saveid' class=textboxcombo id='saveid' value='$saveid'>
                                                               <input type='hidden' name='txt_A_enquiryby' class=textboxcombo id='txt_A_enquiryby' value='$enquiryby'>
                                                               <input type='hidden' name='searchvalue' class=textboxcombo id='searchvalue' value='".$txtsearch."'>
                                                               <input type='hidden' name='recordperpage' class=textboxcombo id='recordperpage' value='".$frmPage_rowcount ."'>
                                                               <input type='hidden' name='recordstartrow' class=textboxcombo id='recordstartrow' value='".$frmPage_startrow."'>
                                                               <input type='hidden' name='txt_A_billingaddress' class=textboxcombo id='txt_A_billingaddress' value='$billingaddress'>
                                                               <input type='hidden' name='txt_A_vatnumber' class=textboxcombo id='txt_A_vatnumber' value='$vatnumber'>
                                                               <input type='hidden' name='txt_A_createdon'  id='txt_A_createdon' value='$createdon'>
                                                               <input type='hidden' name='txt_A_workflowseq' class=textboxcombo id='txt_A_workflowseq' value='$workflowseq'>
                    <input type='hidden' name='txt_A_createdby' class=textboxcombo id='txt_A_createdby' value='$createdby'>

                                                </table>


                                        </form> ";
                             $display="none";
                             if(isset($_REQUEST['CHILDID']) !='' && isset($_REQUEST['DEL']) !='DELETE'){
                                    $display="table-row";
                                    $SEL12 = " Select *,DATE_FORMAT(taskdate,'%d-%m-%Y') as taskdate,DATE_FORMAT(taskdateto,'%d-%m-%Y') as taskdateto,DATE_FORMAT(expecteddate,'%d-%m-%Y') as expecteddate from in_crmtasks where id='".$_REQUEST['CHILDID']."' ";
                                    $dis12 = mysqli_query($con,$SEL12);
                                    while ($arr12 = mysqli_fetch_array($dis12)) {
                                           $taskdate= $arr12['taskdate'];
                                           $taskdateto= $arr12['taskdateto'];
                                           $description= $arr12['title'];
                                           $assignedto = $arr12['assignedto'];
                                           $assigneenote= $arr12['description'];
                                           $expecteddate= $arr12['expecteddate'];
                                           $taskstatus= $arr12['status'];
                                           $taskhh=  $arr12['taskhh'];
                                           $taskhhto=  $arr12['taskhhto'];
                                           $createdon=  date('d-m-Y H:i:s');
                                           $userid = $arr12['userid'];
                                    }


                            }else{
                                $createdon=date('d-m-Y H:i:s');
                                $userid= $_SESSION['SESSuserID'];
                                $taskdate= date('d-m-Y');
                                $taskdateto= date('d-m-Y');
                            }


                            if(isset($_REQUEST['CHILDID']) !='' && isset($_REQUEST['DEL']) =='DELETE'){
                                    $Del_query="delete from in_crmtasks where id='". $_REQUEST['CHILDID']."'";
                                    $Del_Result = mysqli_query($con,$Del_query)   or die(mysqli_error()."<br>".$Del_query);
                                    $_REQUEST['CHILDID']="";

                            }

                    // $entrydata .= "   </div> ";
                                      
                                        /*<div class='box-footer' style='border-top:1px #D2D2D2 solid;'>
                                        $saveico
                                        $backbutton
                                        $cancelicon
                                        $postico
										$approve
                                        $quoteico
                                        $confirmico
                                        </div>";*/   //$completeico
                                        
                                        $entrydata.=  "<div class='box-footer' style='border-top:1px #D2D2D2 solid;'>";
                       if($update == "true" || ($insert=="true" && $_REQUEST['ID']==0))
                       $entrydata.="  <button class='btn btn-success inputs' style='margin-top:-5px;' name='btnsuccess' type='button'  onclick ='javascript:editingrecord(\"save\");'>Save </font>&nbsp;<i class='fa fa-save' aria-hidden='true'></i></button>";
                       if( ($insert=="true" && $_REQUEST['ID']==0) || ($update == "true" && $insert=="true"))
                       $entrydata.="  <button class='btn btn-info inputs' style='margin-top:-5px;' name='btninfo' type='button' onclick ='javascript:editingrecord(\"savenew\");'>Save & New &nbsp; <i class='fa fa-save' aria-hidden='true'></i>&nbsp;&nbsp;<i class='fa fa-plus' aria-hidden='true'></i></button>";
                       if($update == "true" || ($insert=="true" && $_REQUEST['ID']==0))
                        $entrydata.="   <button class='btn btn-warning inputs' style='margin-top:-5px;' name='btnwarning' type='button'  onclick ='javascript:editingrecord(\"saveclose\");'>Save & Close &nbsp;<i class='fa fa-save' aria-hidden='true'></i>&nbsp;&nbsp;<i class='fa fa-close' aria-hidden='true'></i></button>";
                        $entrydata.="  <button class='btn btn-danger inputs' style='margin-top:-5px;' name='btndanger' type='button'  onclick ='javascript:closeediting(\"countrylist.php?ps=1&pr=".$_SESSION['pr']."\");'>Close &nbsp;<i class='fa fa-close' aria-hidden='true'></i></button>";
                        $entrydata.= $Action_button;
                        $entrydata.= " </div>";
                        $entrydata.= "</form>  ";

echo  $entrydata;
?>
</div>
        </div>
                       </div>
                  </div>
        
        </section>
</body>


</html>
<?php
function GetDivision($companycode,$division){
	     global $con;
         $CMB = " <select name='cmb_A_divisioncode'  class='form-control select' id='cmb_A_divisioncode'>";
        $CMB .= "<option value=''>Select</option>";
        $SQL = "select id,divisionname from tbl_division where companycode='".$companycode."' order by id";
		$RES_1 = mysqli_query($con,$SQL);
		while ($ARR_1 = mysqli_fetch_array($RES_1)) {
			$SEL = "";
                if(strtoupper($division) == strtoupper($ARR_1['id'])){ $SEL =  "SELECTED";}
		 	$CMB .= "<option value='".$ARR_1['id']."' $SEL>".$ARR_1['divisionname']."</option>";
		}
        $CMB .= "</select>";
        return $CMB;
}
function GetCompany($empstatus){
         $CMB = "<select name='cmb_A_company'   class='form-control select' id='cmb_A_company' onChange='getDivision(this.value)'>";
         $CMB .= "<option value=''>Select</option>";
         $SEL =  "select locationcode,cy_ename from in_location where companycode <>'01' order by cy_ename";
         $RES = mysqli_query($con,$SEL);
         while ($ARR = mysqli_fetch_array($RES)) {
                $SEL = "";
                if(strtoupper($empstatus) == strtoupper($ARR['locationcode'])){ $SEL =  "SELECTED";}
                $CMB .= "<option value='".$ARR['locationcode']."' $SEL >".$ARR['cy_ename']."</option>";
         }
         $CMB .= "</select>";
         return $CMB;
}
function GetProperty($property,$clientcode){
         $CMB = "<select name='cmb_A_propertyname' id='cmb_A_propertyname' class='form-control select' onChange='getbuilding(this.value)'>  ";
         $CMB .= "<option value=''>Select</option>";
         $SEL =  "select propertycode,propertyname from tbl_clientproperty where businessobjectid='$clientcode' and status ='ACTIVE'";
         $RES = mysqli_query($SEL);
         while ($ARR = mysqli_fetch_array($RES)) {
                $SEL = "";
                if(strtoupper($property) == strtoupper($ARR['propertycode'])){ $SEL =  "SELECTED";}
                $CMB .= "<option value='".$ARR['propertycode']."' $SEL >".$ARR['propertyname']."</option>";
         }
         $CMB .= "</select>";
         return $CMB;
}
function GetBuildings($businessobjectid,$property,$building){
         $CMB = "<select name='cmb_A_buildingname' id='cmb_A_buildingname' class='form-control select'>  ";
         $CMB .= "<option value=''>Select</option>";
         $SEL =  "select buildingcode,buildingname from tbl_clientbuilding where businessobjectid='$businessobjectid' and propertycode='$property' and status ='ACTIVE'";
         $RES = mysqli_query($SEL);
         while ($ARR = mysqli_fetch_array($RES)) {
                $SEL = "";
                if(strtoupper($building) == strtoupper($ARR['buildingcode'])){ $SEL =  "SELECTED";}
                $CMB .= "<option value='".$ARR['buildingcode']."' $SEL >".$ARR['buildingname']."</option>";
         }
         $CMB .= "</select>";
         return $CMB;
}
function GetAccounthead($accountheadcode,$lock){
		global $con;
         $CMB = " <select name='cmb_A_objectcode'  id='cmb_A_objectcode' class='form-control select2' $lock onchange='getClientDetails(this.value);'>";
         $CMB .= "<option value=''></option>";
         $clause ="objectname <> ''";

         $SEL = "Select objectcode,objectname from in_businessobject  where id<>'' and $clause order by objectname";
         $RES = mysqli_query($con,$SEL);

         while ($ARR = mysqli_fetch_array($RES)) {
            $SEL = "";
               if($accountheadcode == $ARR['objectcode']){ $SEL =  "SELECTED";}
               $CMB .= "<option value='".$ARR['objectcode']."' $SEL >".$ARR['objectname']."</option>";
         }
         $CMB .= "</select>";
         return $CMB;
}
function GetPriority($priority,$post_to_sp_display){
		global $con;
         $CMB = "<select name='cmb_A_priority' class='form-control select' $post_to_sp_display id='cmb_A_priority'>";
        // $CMB .= "<option value=''></option>";
         $SEL =  "select lookcode,lookname from in_lookup_head where looktype='CRMPRIORITY' and lookname<>'YY' order by slno";
         $RES = mysqli_query($con,$SEL);
         while ($ARR = mysqli_fetch_array($RES)) {
                $SEL = "";
                if(strtoupper($priority) == strtoupper($ARR['lookcode'])){ $SEL =  "SELECTED";}
                $CMB .= "<option value='".$ARR['lookcode']."' $SEL >".$ARR['lookname']."</option>";
         }
         $CMB .= "</select>";
         return $CMB;
}
function GetTaskstatus($status){
		global $con;
         $CMB = "<select name='cmb_A_status'  class='form-control select' id='cmb_A_status'>";
         //$CMB .= "<option value=''></option>";
         $SEL =  "select lookcode,lookname from in_lookup_head where looktype='CRM TASK STATUS' and lookname='Open' and lookname<>'YY' order by id";
         $RES = mysqli_query($con,$SEL);
         while ($ARR = mysqli_fetch_array($RES)) {
                $SEL = "";
                if(strtoupper($status) == strtoupper($ARR['lookcode'])){ $SEL =  "SELECTED";}
                $CMB .= "<option value='".$ARR['lookcode']."' $SEL >".$ARR['lookname']."</option>";
         }
         $CMB .= "</select>";
         return $CMB;
}
function GetAssignto($userid,$enquirytype){
		global $con;
         $CMB = " <select name='cmb_A_assignedto'  id='cmb_A_assignedto' class='form-control select2' style='width:100%;'>";
         $CMB .= "<option value=''></option>";
         $SEL   = "select incharges from in_locationdivision where code='".$enquirytype."'  order by id";
         $RES = mysqli_query($con,$SEL);
         while ($ARR = mysqli_fetch_array($RES)) {

                $Htemp     = split(",",$ARR['incharges']);
               for($ii=0 ;$ii < count($Htemp); $ii++){
               if($userid==$Htemp[$ii]){
                $CMB .= "<option value='".$Htemp[$ii]."' selected='selected'>".$Htemp[$ii]."-".getusername($Htemp[$ii])."</option>";
               }else{
                $CMB .= "<option value='".$Htemp[$ii]."'>".$Htemp[$ii]."-".getusername($Htemp[$ii])."</option>";
               }
             }
               // if($userid == $ARR['userid']){ $SEL =  "SELECTED";}
               // $CMB .= "<option value='".$ARR['userid']."' $SEL >".$ARR['userid']." - ".$ARR['username']."</option>";
         }
         $CMB .= "</select>";
         return $CMB;
}
function getusername($con,$id){
       $SEL123 = "select username as name from in_user where userid='$id'";
       $RES123 = mysqli_query($con,$SEL123);
        while ($ARR123 = mysqli_fetch_array($RES123)) {
                $name = $ARR123['name'];
        }

       return $name;
}
function GetEnquiryCategory($enquirycategory,$post_to_sp_display) {
		global $con;
         $CMB = " <select name='cmb_A_enquirycategory' id='cmb_A_enquirycategory' $post_to_sp_display class='form-control select'>";
         //$CMB .= "<option value=''></option>";
         $SEL =  "select lookcode,lookname from in_lookup_head where looktype='ENQUIRY CATEGORY' and lookcode= '$enquirycategory' order by slno";
         $RES = mysqli_query($con,$SEL);
         while ($ARR = mysqli_fetch_array($RES)) {
                $SEL = "";
                if($enquirycategory == $ARR['lookcode']){ $SEL =  "SELECTED";}
                $CMB .= "<option value='".$ARR['lookcode']."' $SEL >".$ARR['lookname']."</option>";
         }
         $CMB .= "</select>";
         return $CMB;
}
function GetEnquirySource($enqtype) {

         $CMB = " <select name='cmb_A_leadsource'  id='cmb_A_leadsource' class='form-control select' >";
         $CMB .= "<option value=''>None</option>";
         $SEL =  "select lookcode,lookname from in_lookup where looktype='LEAD SOURCE' and lookname<>'XX' order by slno";
         $RES = mysqli_query($SEL);
         while ($ARR = mysqli_fetch_array($RES)) {
                $SEL = "";
                if($enqtype == $ARR['lookcode']){ $SEL =  "SELECTED";}
                $CMB .= "<option value='".$ARR['lookcode']."' $SEL >".$ARR['lookname']."</option>";
         }
         $CMB .= "</select>";
         return $CMB;
}
function GetEnquiryStatus($enqtype) {

         $CMB = " <select name='cmb_A_leadstatus'  id='cmb_A_leadstatus' class='form-control select' >";
         //$CMB .= "<option value='Select'>Select</option>";
         $SEL =  "select lookcode,lookname from in_lookup_head where looktype='LEAD STATUS' and lookname<>'YY' order by slno";
         $RES = mysqli_query($SEL);
         while ($ARR = mysqli_fetch_array($RES)) {
                $SEL = "";

                if($enqtype == $ARR['lookcode']){ $SEL =  "SELECTED";}
                $CMB .= "<option value='".$ARR['lookcode']."' $SEL >".$ARR['lookname']."</option>";
         }
         $CMB .= "</select>";
         return $CMB;
}

function GetEnquiryType($enqtype,$post_to_sp_display) {

         $CMB = " <select name='cmb_A_enquirytype'  id='cmb_A_enquirytype' class='form-control select' >";
        // $CMB .= "<option value=''></option>";
         $SEL =  "select lookname,in_locationdivision.code from in_locationdivision,in_lookup where in_lookup.lookcode=in_locationdivision.division
                  and in_locationdivision.type='PROFIT DIVISION' order by lookname";
         $RES = mysqli_query($SEL);
         while ($ARR = mysqli_fetch_array($RES)) {
                $SEL = "";
                if($enqtype == $ARR['code']){ $SEL =  "SELECTED";}
                $CMB .= "<option value='".$ARR['code']."' $SEL >".$ARR['lookname']."</option>";
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
function GetLastSqeID_new($tblName){
	global $con;
                 $query = "LOCK TABLES in_sequencer WRITE";
                 mysqli_query($con,$query) or die(mysqli_error()."<br>".$query);
                 $seqSQL = "SELECT LASTNUMBER FROM in_sequencer WHERE TABLENAME='$tblName'";
                 $result=mysqli_query($con,$seqSQL) or die(mysqli_error()."<br>".$seqSQL);
                 $resulArr=mysqli_fetch_array($result);
                 $updatedSeqID=$resulArr['LASTNUMBER']+1;
                 //$squpdateSQL = "UPDATE in_sequencer SET LASTNUMBER=".$updatedSeqID." WHERE TABLENAME='$tblName'";
                 //mysqli_query($squpdateSQL) or die(mysqli_error()."<br>".$squpdateSQL);
                 $query = "UNLOCK TABLES";
                 mysqli_query($con,$query) or die(mysqli_error()."<br>".$query);
                 return ($updatedSeqID);
}
function getunreadmsg($con,$id){
      $SQL = "Select count(*) as count from tbl_message,in_crmhead where in_crmhead.id=tbl_message.ticketno and tbl_message.viewedby not like '%".$_SESSION['SESSuserID']."%' and in_crmhead.id='$id'";
      $SQLRes =  mysqli_query($con,$SQL) or die(mysqli_error()."<br>".$SQL);
      if(mysqli_num_rows($SQLRes)>=1){
        while($loginResultArray   = mysqli_fetch_array($SQLRes)){
            $count=$loginResultArray['count'];


        }
      }
      return $count;
}

function GetDurationType($durationtype) {
         $CMB = " <select name='cmb_A_durationtype'  id='cmb_A_durationtype' class='form-control select' onChange='getDurationNos(this.value)'>";
         $CMB .= "<option value=''>Select</option>";
         $SEL =  "select lookcode,lookname from in_lookup_head where looktype='DURATION TYPE' and lookname<>'YY' order by slno";
         $RES = mysqli_query($SEL);
         while ($ARR = mysqli_fetch_array($RES)) {
               $SEL = "";
               if($durationtype == $ARR['lookcode']){ $SEL =  "SELECTED";}
               $CMB .= "<option value='".trim($ARR['lookcode'])."' $SEL >".trim($ARR['lookname'])."</option>";
         }
         $CMB .= "</select>";
         return $CMB;
}
function GetEnquiryThrough ($enquirythrough,$post_to_sp_display){
	global $con;
        $CMB = " <select name='cmb_A_enquirythrough'  id='cmb_A_enquirythrough' $post_to_sp_display class='form-control select' onChange='getEnquiry_Through(this);'>";    //getEnquiryThrough(this.value)
         $CMB .= "<option value=''>Select</option>";
         $SEL =  "select lookcode,lookname from in_lookup_head where looktype='ENQUIRY THROUGH' and lookname<>'YY' order by slno";
         $RES = mysqli_query($con,$SEL);
         while ($ARR = mysqli_fetch_array($RES)) {
               $SEL = "";
               if($enquirythrough == $ARR['lookcode']){ $SEL =  "SELECTED";}
               $CMB .= "<option value='".trim($ARR['lookcode'])."' $SEL >".trim($ARR['lookname'])."</option>";
         }
         $CMB .= "</select>";
         return $CMB;

}
function getSalesPerson($con,$userid,$post_to_sp_display) {
         $CMB = " <select name='cmb_A_userid'  id='cmb_A_userid' $post_to_sp_display class='form-control select2'>";    //getEnquiryThrough(this.value)
         $CMB .= "<option value=''>Select</option>";
         $SEL =  "Select userid,username from in_user where rolecode like '%SALES PERSON%' and status='ACTIVE' ";//and acclocationcode='".$_SESSION['SESSUserLocation']."'";
         $RES = mysqli_query($con,$SEL);
         while ($ARR = mysqli_fetch_array($RES)) {
               $SEL = "";
               if($userid == $ARR['userid']){ $SEL =  "SELECTED";}
               $CMB .= "<option value='".trim($ARR['userid'])."' $SEL >".trim($ARR['userid'])." - ".trim($ARR['username'])."</option>";
         }
         $CMB .= "</select>";
         return $CMB;

}
function getSalesStaff($enquirystaff,$post_to_sp_display) {
	global $con;
        $CMB = " <select name='cmb_A_enquirystaff'  id='cmb_A_enquirystaff' $post_to_sp_display class='form-control select'>";    //getEnquiryThrough(this.value)
         $CMB .= "<option value=''>Select</option>";
         $SEL =  "Select userid,username from in_user where rolecode like '%SALE%' and status='ACTIVE' and acclocationcode='".$_SESSION['SESSUserLocation']."'";
         $RES = mysqli_query($con,$SEL);
         while ($ARR = mysqli_fetch_array($RES)) {
               $SEL = "";
               if($enquirystaff == $ARR['userid']){ $SEL =  "SELECTED";}
               $CMB .= "<option value='".trim($ARR['userid'])."' $SEL >".trim($ARR['userid'])." - ".trim($ARR['username'])."</option>";
         }
         $CMB .= "</select>";
         return $CMB;

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
                   boxHeight();
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
       document.frmEdit.action='editnewleadheadlist.php?dr=edit&ID='+document.getElementById('mode').value;
       document.frmEdit.submit();
   }
   if(i==3){
   var frame= document.getElementById('frame3');
   frame.src='addmaterialitem.php?doctype=LEAD&ID=<?php echo $_REQUEST['ID']; ?>&txt_A_formtype=CRM';
   frame.load();
   }
   if(i==5){
   var frame= document.getElementById('frame5');
   frame.src='annexure.php?ID=<?php echo $_REQUEST['ID']; ?>&LEVEL=LEAD';
   frame.load();
   }
   if(i==4){
   $("span").html("");
   var frame= document.getElementById('frame4');
   frame.src='communication.php?formtype=CRM&ID=<?php echo $_REQUEST['ID']; ?>';
   frame.load();
   }
   if(i==7){
   var frame= document.getElementById('frame7');
   frame.src='ppm.php?ID='+<?php echo $_REQUEST['ID']; ?>;
   frame.load();
   }
   if(i==8){
   var frame= document.getElementById('frame8');
   frame.src='serviceassets.php?formtype=ENQUIRY&ID='+<?php echo $_REQUEST['ID']; ?>;
   frame.load();
   }
   if(i==9){
   var frame= document.getElementById('frame9');
   frame.src='manpowerforservice.php?txt_A_formtype=CRM&ID='+<?php echo $_REQUEST['ID']; ?>;
   frame.load();
   }
   if(i==12){
   var frame= document.getElementById('frame12');
   frame.src='schedule.php?ID='+<?php echo $_REQUEST['ID']; ?>;
   frame.load();
   }
   if(i==11){
   var frame= document.getElementById('frame11');
   frame.src='servicepropertylist.php?Client='+<?php echo $accountheadcode; ?>+'&ID='+<?php echo $_REQUEST['ID']; ?>;
   frame.load();
   }
   if(i==20){
   var frame= document.getElementById('frame20');
   frame.src='documents.php?txt_A_formtype=CRM&cid=<?php echo $_REQUEST['ID']; ?>';
   frame.load();
   }
   if(i==14){
   var frame= document.getElementById('frame14');
   frame.src='contactactivity.php?txt_A_docid=<?php echo $_REQUEST['ID']; ?>&posted=<?php echo $post_to_sp; ?>';
   frame.load();
   }
}
function postaddclient()
{
        var txtclientname=document.getElementById('txtclientname');
        if(txtclientname){
               if ((txtclientname.value==null)||(txtclientname.value=="")){
                   alertify.alert("Enter Customer Name", function () {
                  txtclientname.focus();

               });
               return;
            }
        }
        var txtshortname=document.getElementById('txtshortname');
        if(txtshortname){
               if ((txtshortname.value==null)||(txtshortname.value=="")){
                   alertify.alert("Enter Short Name", function () {
                  txtshortname.focus();

               });
               return;
            }
        }
        var txtclientno=document.getElementById('txtclientno');
        if(txtclientno){
               if ((txtclientno.value==null)||(txtclientno.value=="")){
                   alertify.alert("Enter Contact No", function () {
                  txtclientno.focus();

               });
               return;
            }
        }
        var txtclientaddress=document.getElementById('txtclientaddress');
        if(txtclientaddress){
               if ((txtclientaddress.value==null)||(txtclientaddress.value=="")){
                   alertify.alert("Enter Customer Address", function () {
                  txtclientaddress.focus();

               });
               return;
            }
        }

                          xmlHttp=GetXmlHttpObject()
                          if (xmlHttp==null)
                          {
                                 alert ("Browser does not support HTTP Request")
                                 return
                          }


                          var url='combofunctions_service.php?level=addClient&txtclientname='+txtclientname.value+'&txtshortname='+txtshortname.value+'&txtclientaddress='+txtclientaddress.value+'&txtclientno='+txtclientno.value;
                          xmlHttp.onreadystatechange=stateChangedaddclient
                          xmlHttp.open("POST",url,true)
                          xmlHttp.send(null)

}
function stateChangedaddclient()
{

   var html = $.ajax({
        type: "POST",
        url: "combofunctions_service.php",
        data: "level=popupmfg_client",
        async: false
    }).responseText;
    if(html){
        $("#cmb_A_objectcode").html(html);

    }
   if (xmlHttp.readyState==4 || xmlHttp.readyState=="complete")
   {
      var s1 = trim(xmlHttp.responseText);
      document.getElementById('cmb_A_objectcode').value=s1;
      $('#myModal45').modal('hide');
   }
}

function postmfg()
{
        var txtmanufacturer=document.getElementById('txtmanufacturer');
        if(txtmanufacturer){
               if ((txtmanufacturer.value==null)||(txtmanufacturer.value=="")){
                   alertify.alert("Enter Property Name", function () {
                  txtmanufacturer.focus();

               });
               return;
            }
        }

                          xmlHttp=GetXmlHttpObject()
                          if (xmlHttp==null)
                          {
                                 alert ("Browser does not support HTTP Request")
                                 return
                          }


                          var url='combofunctions_service.php?level=addProperty&txtmanufacturer='+txtmanufacturer.value+'&client='+document.getElementById('cmb_A_objectcode').value;
                          xmlHttp.onreadystatechange=stateChangedchildmfg
                          xmlHttp.open("POST",url,true)
                          xmlHttp.send(null)

}
function stateChangedchildmfg()
{

   var html = $.ajax({
        type: "POST",
        url: "combofunctions_service.php",
        data: "level=popupmfg2&client="+document.getElementById('cmb_A_objectcode').value,
        async: false
    }).responseText;
    if(html){
        $("#cmb_A_propertyname").html(html);

    }
   if (xmlHttp.readyState==4 || xmlHttp.readyState=="complete")
   {
      var s1 = trim(xmlHttp.responseText);
      document.getElementById('cmb_A_propertyname').value=s1;
      $('#myModal33').modal('hide');
   }
}
function popupmodeladdclient(){
      document.getElementById('txtclientname').value='';
      $('#myModal45').modal()
}
function popupmodel(){

      var cmb_A_objectcode=document.getElementById('cmb_A_objectcode');
        if(cmb_A_objectcode){
               if ((cmb_A_objectcode.value==null)||(cmb_A_objectcode.value=="")){
                   alertify.alert("Select Client", function () {
                  cmb_A_objectcode.focus();

               });
               return;
            }
      }

      document.getElementById('txtmanufacturer').value='';
      $('#myModal33').modal()
}
function popupmodeladd(){
       document.getElementById('txtmodel').value='';
       var cmb_A_propertyname=document.getElementById('cmb_A_propertyname');
        if(cmb_A_propertyname){
               if ((cmb_A_propertyname.value==null)||(cmb_A_propertyname.value=="")){
                   alertify.alert("Select Property", function () {
                  cmb_A_propertyname.focus();

               });
               return;
            }
        }
      $('#myModal44').modal()
}
function postmodel() {

        var txtmodel=document.getElementById('txtmodel');
        if(txtmodel){
               if ((txtmodel.value==null)||(txtmodel.value=="")){
                   alertify.alert("Enter Building name", function () {
                  txtmodel.focus();

               });
               return;
            }
        }
        var txtmakaniid=document.getElementById('txtmakaniid');
        if(txtmakaniid){
               if ((txtmakaniid.value==null)||(txtmakaniid.value=="")){
                   alertify.alert("Enter Makani ID", function () {
                  txtmakaniid.focus();

               });
               return;
            }
        }
        var cmb_A_propertyname=document.getElementById('cmb_A_propertyname');
        var cmb_A_objectcode=document.getElementById('cmb_A_objectcode');

                          xmlHttp=GetXmlHttpObject()
                          if (xmlHttp==null)
                          {
                                 alert ("Browser does not support HTTP Request")
                                 return
                          }


                          var url='combofunctions_service.php?level=addBuilding&txtmakaniid='+txtmakaniid.value+'&txtmodel='+txtmodel.value+'&txtmfg='+cmb_A_propertyname.value+'&client='+cmb_A_objectcode.value;
                          //alert(url);
                          xmlHttp.onreadystatechange=stateChangedchildmodel
                          xmlHttp.open("POST",url,true)
                          xmlHttp.send(null)

}
function stateChangedchildmodel()
{
   var cmb_A_propertyname=document.getElementById('cmb_A_propertyname');
   var cmb_A_objectcode=document.getElementById('cmb_A_objectcode');
   var html = $.ajax({
        type: "POST",
        url: "combofunctions_service.php",
        data: "level=popupmodel2&mfg="+cmb_A_propertyname.value+"&client="+cmb_A_objectcode.value,
        async: false
    }).responseText;
    if(html){
        $("#cmb_A_buildingname").html(html);

    }
   if (xmlHttp.readyState==4 || xmlHttp.readyState=="complete")
   {
      var s1 = trim(xmlHttp.responseText);
      document.getElementById('cmb_A_buildingname').value=s1;
      $('#myModal44').modal('hide');
   }
}
function displaymodel(cattype){


      xmlHttp=GetXmlHttpObject()
      if (xmlHttp==null)
      {
                 alert ("Browser does not support HTTP Request")
                 return
      }

      var url="combofunctions_service.php?level=showmodel&categorytype="+cattype.value;
      xmlHttp.onreadystatechange=stateChangedcombo11
      xmlHttp.open("POST",url,true)
      xmlHttp.send(null)

}

function stateChangedcombo11(){
       if (xmlHttp.readyState==4 || xmlHttp.readyState=="complete"){

             var s1 = trim(xmlHttp.responseText);
             document.getElementById('showmodel').innerHTML=s1;
       }
}
</script>
