<?php
session_start();
if($_SESSION['pr'] == "") {
  $_SESSION['pr'] = $_REQUEST['pr'];
}
require "connection.php";
require "pagingObj.php";
include("functions_workflow.php");
function GetLastSqeID($tblName){
       $query = "LOCK TABLES in_sequencer WRITE";
       mysql_query($query) or die(mysql_error()."<br>".$query);
       $seqSQL = "SELECT LASTNUMBER FROM in_sequencer WHERE TABLENAME='$tblName'";
       $result=mysql_query($seqSQL) or die(mysql_error()."<br>".$seqSQL);
       $resulArr=mysql_fetch_array($result);
       $updatedSeqID=$resulArr['LASTNUMBER']+1;
       $squpdateSQL = "UPDATE in_sequencer SET LASTNUMBER=".$updatedSeqID." WHERE TABLENAME='$tblName'";
       mysql_query($squpdateSQL) or die(mysql_error()."<br>".$squpdateSQL);
       $query = "UNLOCK TABLES";
       mysql_query($query) or die(mysql_error()."<br>".$query);
       return ($updatedSeqID);
}
function GetSiteInchargeofbuilding_contract($buildingid,$contractcode){
        $SQL = "Select inchargename,inchargetype from in_incharges where docid='$buildingid'  and jobno='$contractcode' and (inchargetype='SITE INCHARGE') and posted='YES' and in_incharges.inchargestatus='Active' and type='BUILDING'";
    $SQLRes =  mysql_query($SQL) or die(mysql_error()."<br>".$SQL);
    if(mysql_num_rows($SQLRes)==0){
        return "0";
        }else{
        while($loginResultArray   = mysql_fetch_array($SQLRes)){
            $siteincharge = trim($loginResultArray['inchargename']);
        }
        return $siteincharge;
    }
}
function GetAreaIncharge($buildingid){
    $SQL = "select areaincharge from tbl_building where tbl_building.id='$buildingid' and tbl_building.status='Active'";
    $SQLRes =  mysql_query($SQL) or die(mysql_error()."<br>".$SQL);
    if(mysql_num_rows($SQLRes)==0){
        return "0";
        }else{
        while($loginResultArray   = mysql_fetch_array($SQLRes)){
            $areaincharge = trim($loginResultArray['areaincharge']);
        }
        return $areaincharge;
    }
}
function GetTicketTagInfo($buildingid,$contractcode,$assettag,$filed){
        $sql = "select $filed from tbl_contractassettag where buildingid='$buildingid' and contractcode='$contractcode' and assettag='$assettag' ";
        $res = mysql_query($sql);
        $arr = mysql_fetch_array($res);
        return $arr[0];
}
if($_REQUEST['MODE'] =='UPDATE'){

         // to get the site & area incharge
    $buildingid = $_REQUEST['cmb_A_client'];
    $contractcode =  $_REQUEST['cmb_A_contractid'];
    $siteincharge = GetSiteInchargeofbuilding_contract($buildingid,$contractcode);
    $areaincharge = GetAreaIncharge($buildingid);
    $sql_t1 = "select in_project.projectname,in_project.id as projectid,t_activitycenter.id as contractid,tbl_projectcontracts.company,tbl_projectcontracts.division,tbl_projectcontracts.objectcode,t_activitycenter.jobname,in_project.projectcode,in_project.projectcontactno,in_project.projectcontactperson,in_project.email from tbl_projectcontracts left join t_activitycenter on tbl_projectcontracts.contractcode=t_activitycenter.jobno left join in_project on tbl_projectcontracts.projectcode=in_project.projectcode where tbl_projectcontracts.contractstatus='Active'and tbl_projectcontracts.posted='YES' and in_project.status='Active' and t_activitycenter.status='Active'
               and t_activitycenter.jobno='$contractcode'";
        $res_t1 = mysql_query($sql_t1);
        $arr_t1 = mysql_fetch_array($res_t1);
        $projectcode = $arr_t1['projectcode'];
        $jobname = $arr_t1['jobname'];
        $company =  $arr_t1['company'];
        $division =  $arr_t1['division'];
        $objectcode =  $arr_t1['objectcode'];
        $projectcontactperson =  $arr_t1['projectcontactperson'];
        $projectcontactno =  $arr_t1['projectcontactno'];
        $projectemail =  $arr_t1['email'];
        $contractid =  $arr_t1['contractid'];
        $projectid =  $arr_t1['projectid'];
        $projectname =  $arr_t1['projectname'];

        // building info
        $sql_t2 = "select * from tbl_building where id='$buildingid'";
        $res_t2 = mysql_query($sql_t2);
        $arr_t2 = mysql_fetch_array($res_t2);
        $buildingtype = $arr_t2['buildingtype'];
        $buildingcode = $arr_t2['buildingcode'];
        $buildingname = $arr_t2['buildingname'];

    // end of code
    if($siteincharge=="0" || $areaincharge=="0"){
                        echo "<script language='javascript'>
                                alert('Add Incharge to the Property to Schedule Tickets!');
                        </script>";
                    //exit;
        }
        else{

               $sql_a = "select ppmfrom,ppmto from in_ppmscheduleperiod where id= '".$_REQUEST['rowid']."'";
               $res_a = mysql_query($sql_a) or die(mysql_error()."<br>".$sql_a);
               if(mysql_num_rows($res_a)>0) {
                  while($result_a=mysql_fetch_array($res_a)){
                       $ppmfrom= $result_a['ppmfrom'];
                       $ppmto=    $result_a['ppmto'];
                  }
               }

               $sql_a1 = "select * from tbl_ticket_schedule where docdate between '$ppmfrom' and '$ppmto'";
               $res_a1 = mysql_query($sql_a1) or die(mysql_error()."<br>".$sql_a1);
               if(mysql_num_rows($res_a1)>0) {

               $fields_a1=mysql_num_fields($res_a1);
               while($result_a1=mysql_fetch_array($res_a1)){
                               $ppmid = $result_a1['ppmid'];
                               $assetcode = $result_a1['assetcode'];
                               $assetdescription = $result_a1['assetdescription'];
                               $checklistfrequency = $result_a1['checklistfrequency'];
                               $location = GetTicketTagInfo($buildingid,$contractcode,$result_a1['ticketcode'],'location');
                               $brand = GetTicketTagInfo($buildingid,$contractcode,$result_a1['ticketcode'],'brand');
                               $model = GetTicketTagInfo($buildingid,$contractcode,$result_a1['ticketcode'],'model');
                               $floor = GetTicketTagInfo($buildingid,$contractcode,$result_a1['ticketcode'],'floor');
                               $block = GetTicketTagInfo($buildingid,$contractcode,$result_a1['ticketcode'],'block');

                               $SEQID = GetLastSqeID("tbl_ticket");
                               $Ins = "insert into tbl_ticket
                                        (id,ticketno,ticketcode,doctype,docdate,jobno,jobname,locationcode,divisioncode,priority,suserid,ticketstatus,objectcode,objectname,enquirycategory,contactperson,phonecode1,billingemail,startdate,stcheck,contractid,ppmid,paymenttype,propertycode,buildingcode,buildingname,assetcode,assetdescription,checklistfrequency,location,brand,model,floor,block,buildingid,projectid,projectname,areaincharge,scheduleid,tickettype,projectcode)
                                        values
                                        ('$SEQID','".$result_a1['ticketno']."','".$result_a1['ticketcode']."','AMC','".date("Y-m-d")."','$contractcode','$jobname','$company','$division','Low','".$siteincharge."','Open','".$objectcode."','','AMC Enquiry','$projectcontactperson','$projectcontactno','$projectemail','".$result_a1['docdate']."','Waiting for Work Completion','$contractid','$ppmid','Non-Chargeable','$buildingtype','$buildingcode','$buildingname','".$assetcode."','".$assetdescription."','".$checklistfrequency."','".$location."','".$brand."','".$model."','".$floor."','".$block."','$buildingid','$projectid','$projectname','".$areaincharge."','".$result_a1['id']."','".$result_a1['tickettype']."','$projectcode')";
                                mysql_query($Ins);

                               $updSQL =  "update tbl_ticket_schedule set converted='YES' where id ='".$result_a1['id']."'";
                               $updSQLRes =  mysql_query($updSQL) or die(mysql_error()."<br>".$updSQL);

                       }// end of while

               } // end of if

                    $updSQL1 =  "update in_ppmscheduleperiod set posted='YES' where id ='".$_REQUEST['rowid']."'";
                    $updSQLRes1 =  mysql_query($updSQL1) or die(mysql_error()."<br>".$updSQL1);

                $update_contract = "update t_activitycenter set tickets = (select count(*) from tbl_ticket where jobno = '$contractcode' and ticketcode<>'' and doctype='AMC')
                where jobno = '$contractcode'";
                mysql_query($update_contract);

                $alert_message = "You have been assigned for the PPM Tickets of the Property Name: $buildingname";
                $APPROVAL_users = $siteincharge;
                echo SendAlerts("SERVICE","CONTRACT",$APPROVAL_users,$alert_message);
                echo SendSMS("SERVICE","CONTRACT",$APPROVAL_users,$alert_message);
                echo SendEmail("SERVICE","CONTRACT",$APPROVAL_users,$alert_message,$alert_message); // last 2 are subject and message

        }


}
if($_REQUEST['CHILDID'] !='' && $_REQUEST['DEL'] =='DELETE'){

                                    $Del_query="delete from in_ppmscheduleperiod where id='". $_REQUEST['CHILDID']."'";
                                    $Del_Result = mysql_query($Del_query)   or die(mysql_error()."<br>".$Del_query);
                                    $_REQUEST['CHILDID']="";

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
      <script language="javascript">
/*
function getContarctBuilding(catval){
         xmlHttp=GetXmlHttpObject()
      if (xmlHttp==null)
      {
                 alert ("Browser does not support HTTP Request")
                 return
      }
          var buildingid = document.getElementById('txt_A_buildingid').value;
      var url="combofunctions_project.php?level=CONTRACT_BUILDING_DATES&categorytype="+catval+"&buildingid="+buildingid;
      xmlHttp.onreadystatechange=stateChanged_contractdates
      xmlHttp.open("POST",url,true)
      xmlHttp.send(null)
}
function stateChanged_contractdates(){
       if (xmlHttp.readyState==4 || xmlHttp.readyState=="complete")
       {

             var s1 = trim(xmlHttp.responseText);
             var word = s1.split('@@@');
             document.getElementById('txd_A_contractfrom').value=word[0];
             document.getElementById('txd_A_contractto').value=word[1];
       }
}
*/
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
function getDivision(companyId) {

                var strURL="findDivision.php?company="+companyId;
                var req = getXMLHTTP();
                alert(req);
                if (req) {

                        req.onreadystatechange = function() {
                                if (req.readyState == 4) {
                                        // only if "OK"
                                        if (req.status == 200) {
                                                document.getElementById('divisiondiv').innerHTML=req.responseText;
                                                document.getElementById('cmb_A_empdivision').focus();
                                        } else {
                                                alert("Problem while using XMLHTTP:\n" + req.statusText);
                                        }
                                }
                        }
                        req.open("GET", strURL, true);
                        req.send(null);
                }

}
function updateChildrecord(childid){

    document.frmChildEdit.action='ppmschedulelist.php?id1=<?echo $_GET['id1']; ?>&CHILDID='+childid+'&ID='+document.getElementById('txt_A_buildingid').value;
    document.frmChildEdit.submit();
}
function deleteChildrecord(childid){

        alertify.confirm("Are you sure you want to delete ?", function (e) {
         if (e) {
           document.frmChildEdit.action='ppmschedulelist.php?id1=<?echo $_GET['id1']; ?>&DEL=DELETE&CHILDID='+childid+'&ID='+document.getElementById('txt_A_buildingid').value;
           document.frmChildEdit.submit();
         } else {
            return;
         }

       });



}
function canceleditingChildrecord(){

    document.frmChildEdit.action = 'ppmschedulelist.php?id1=<?echo $_GET['id1']; ?>&ID='+document.getElementById('txt_A_buildingid').value;
    document.frmChildEdit.submit();
}
function PostChildrecord(childid,inctype,incname){


         alertify.confirm("Are you sure you want to Post ?", function (e) {
         if (e) {
            document.frmChildEdit.action='ppmschedulelist.php?type='+inctype+'&empid='+incname+'&DEL=FREEZE&POSTID='+childid+'&ID='+document.getElementById('txt_A_buildingid').value;
            document.frmChildEdit.submit();       } else {
            return;
         }

       });


}

function editingChildrecord(){

       var txt_A_contractcode=document.getElementById('cmb_A_contractcode');
       if(txt_A_contractcode){
          if ((txt_A_contractcode.value==null)||(txt_A_contractcode.value=="")){
               alertify.alert("Select Contract No", function () {
               txt_A_contractcode.focus();

          });
             return;
          }
       }

       var txd_A_inchargefrom=document.getElementById('txd_A_ppmfrom');
       if(txd_A_inchargefrom){
          if ((txd_A_inchargefrom.value=="00-00-0000")||(txd_A_inchargefrom.value=="")){
               alertify.alert("Select schedule from date", function () {
               txd_A_inchargefrom.focus();

          });
            return;
          }

       }
       var txd_A_ppmto=document.getElementById('txd_A_ppmto');
       if(txd_A_ppmto){
          if ((txd_A_ppmto.value=="00-00-0000")||(txd_A_ppmto.value=="" )){
               alertify.alert("Select schedule to date", function () {
               txd_A_ppmto.focus();

          });
           return;
          }

       }

             var str1  = document.getElementById("txd_A_ppmto").value;
             var str2  = document.getElementById('txd_A_ppmfrom').value;
             var dt1   = parseInt(str1.substring(0,2),10);
             var mon1  = parseInt(str1.substring(3,5),10);
             var yr1   = parseInt(str1.substring(6,10),10);
             var dt2   = parseInt(str2.substring(0,2),10);
             var mon2  = parseInt(str2.substring(3,5),10);
             var yr2   = parseInt(str2.substring(6,10),10);
             var date1 = new Date(yr1, mon1, dt1);
             var date2 = new Date(yr2, mon2, dt2);

             if(date1 < date2){
                  alertify.alert("To date should not be less than from date");
                  return;
            }


             var revstr1 = str1.split("-").reverse().join("-");
             var revstr2 = str2.split("-").reverse().join("-");

            var startDate = Date.parse(revstr2);
            var endDate = Date.parse(revstr1);
            var timeDiff = endDate - startDate;
            daysDiff = Math.floor(timeDiff / (1000 * 60 * 60 * 24));

      if(daysDiff>=365){
           alertify.alert("Schedule cant be greater than 1 year !!");
           return;
       }
   insertChildfunction(get(document.frmChildEdit))

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
                                  document.frmChildEdit.action='ppmschedulelist.php?ID='+document.getElementById('txt_A_buildingid').value;
                                  document.frmChildEdit.submit();
                                 });
                               }else if(s1.toString() == s3.toString()){
                                alertify.alert("Record Updated", function () {
                                  document.frmChildEdit.action='ppmschedulelist.php?ID='+document.getElementById('txt_A_buildingid').value;
                                  document.frmChildEdit.submit();
                                 });
                               }else{
                                alertify.alert(s1);
                               }

                         }

                   }

function searchPaging(){

       document.frmChildEdit.action='ppmschedulelist.php?id1=<?echo $_GET['id1']; ?>&search='+document.getElementById('txtsearch').value+'&ID='+document.getElementById('txt_A_buildingid').value;
       document.frmChildEdit.submit();
}
function refreshPaging(){

       document.frmChildEdit.action='ppmschedulelist.php?costcenter='+document.getElementById('costcenter').value+'&ID='+document.getElementById('txt_A_buildingid').value;
       document.frmChildEdit.submit();

}
function AddItem(){

            var tr1=document.getElementById('tr1');
            tr1.style.display="table-row";

            var tr2=document.getElementById('tr2');
            tr2.style.display="table-row";

             $('#btnshow').toggle('hide');
             $('#btnsuccess').prop("disabled",false);
             $('#btndanger').prop("disabled",false);


}
function getInchargename(cattype){


      xmlHttp=GetXmlHttpObject()
      if (xmlHttp==null)
      {
                 alert ("Browser does not support HTTP Request")
                 return
      }

      var url="combofunctions_service.php?level=contractdate&categorytype="+cattype;
      xmlHttp.onreadystatechange=stateChangedcombo
      xmlHttp.open("POST",url,true)
      xmlHttp.send(null)

}

function stateChangedcombo(){
       if (xmlHttp.readyState==4 || xmlHttp.readyState=="complete")
       {

             var s1 = trim(xmlHttp.responseText);
             var word = s1.split("^");
             document.getElementById('txd_A_contractfrom').value=word[0];
             document.getElementById('txd_A_contractto').value=word[1];

       }
}
function ScheduleChildrecord(ctr,buildingid,contractid){


 alertify.confirm('Are you sure you want to schedule tickets?', function (e) {
 if (e) {
      $('#overlay').show();
      $('#box-body-id').hide();
      document.frmChildEdit.action="ppmschedulelist.php?MODE=UPDATE&cmb_A_client="+buildingid+"&cmb_A_contractid="+contractid+"&rowid="+ctr+"&ID="+document.getElementById('txt_A_buildingid').value;
      document.frmChildEdit.submit();

   } else {
            return;
         }
     });
}
function stateChangedcomboproperty(){
       if (xmlHttp.readyState==4 || xmlHttp.readyState=="complete")
       {

             var s1 = trim(xmlHttp.responseText);
             document.frmChildEdit.action='ppmschedulelist.php?ID='+document.getElementById('txt_A_buildingid').value;
             document.frmChildEdit.submit();

       }
}
</script>
</head>
<div id='overlay'  style='display:none;border:5px;'>
                               <img src='img/loading_wh.gif' style='margin-top:-5px;'  align='center' />
</div>
<section class="content" id='content-content-id' style='padding-right:5px;padding-left:5px;' >
   <div class="box-body" id='box-body-id' style='background-color:#ffffff;'>
          <div class='table-responsive'>
<?

         $buildingid=$_REQUEST['ID'];

         $formlistname = "ppmschedulelist.php";

         $arguments = array('id_field' => $id_field, 'fieldNames' => $fieldNames, 'fieldSizes' => $fieldSizes,
                          'fieldAlign' => $fieldAlign, 'fields' => $fields, 'table' => $table,'buttons' => $buttons,
                          'formlistname'=>$formlistname,
                          'selectedlistingpage'=>$_REQUEST['frmPage_startrow']);

         $grid = new MyPHPGrid('frmPage');

         $grid->formName = "ppmschedulelist.php";

         $grid->inpage = $_REQUEST['frmPage_startrow'];

         $grid->TableNameChild = "in_ppmscheduleperiod";

         $grid->SyncSession($grid);

         $contractcode=$_REQUEST['cmb_A_contractcode'];
         if($contractcode!=''){
            $disable="";
         }else{
            $disable="disabled";
         }



                                                  $SQL1   = "Select DATE_FORMAT(min(ppmfrom),'%d-%m-%Y') as ppmfrom,DATE_FORMAT(max(ppmto),'%d-%m-%Y') as ppmto from in_ppmschedule where contractcode='".$contractcode."' ";
                                                  $SQLRes1 =  mysql_query($SQL1) or die(mysql_error()."<br>".$SQL1);
                                                  if(mysql_num_rows($SQLRes1)>=1){
                                                     while($loginResultArray1   = mysql_fetch_array($SQLRes1)){
                                                         $ppmfrom = $loginResultArray1['ppmfrom'];
                                                         $ppmto = $loginResultArray1['ppmto'];

                                                     }
                                                  }

                                                  $SQL1   = "SELECT DATE_FORMAT(MAX(ppmto),'%d-%m-%Y') as ppmfrom FROM in_ppmscheduleperiod WHERE contractcode='$contractcode' ";
                                                  $SQLRes1 =  mysql_query($SQL1) or die(mysql_error()."<br>".$SQL1);
                                                  if(mysql_num_rows($SQLRes1)>=1){
                                                     while($loginResultArray1   = mysql_fetch_array($SQLRes1)){
                                                       if($loginResultArray1['ppmfrom']!="" && $loginResultArray1['ppmfrom']!=null){
                                                         $ppmfrom = date('d-m-Y',strtotime($loginResultArray1['ppmfrom'].'+ 1 days'));
                                                         $ppmto = $ppmto;
                                                       }else{
                                                         $ppmfrom=$ppmfrom;
                                                         $ppmto = $ppmto;
                                                       }

                                                     }
                                                  }




                          $SQL1   = "SELECT id FROM in_ppmscheduleperiod WHERE contractcode='$contractcode' and posted='NO'";
                          $SQLRes1 =  mysql_query($SQL1) or die(mysql_error()."<br>".$SQL1);
                          if(mysql_num_rows($SQLRes1)==0){
                               $saveicon=" <a href='javascript:editingChildrecord();'><img src='ico/save.png' title='Save' width='20' height='20'></a>
                                           <a href='?ID=".$_REQUEST['ID']."'><img src='ico/cancel.png' title='Cancel' width='20' height='20'></a>";
                          }else{
                               $saveicon="<font color='red'><b>Please post below record for create new schedule.</b></font>";
                          }







                                 // echo "&nbsp;&nbsp;<button class='btn btn-success' id='btnshow'  type='button' onClick='AddItem();'>Add New <i class='fa fa-plus' aria-hidden='true'></i></button>";
         if($_REQUEST['ACT']!='dashboard'){
                                  $entrydata .= "<div class='table-responsive no-padding'>
                                                  <form name='frmChildEdit' method='post' id='frmChildEdit' enctype='multipart/form-data' autocomplete='off'>
                                                    <table class='table table-bordered table-condensed table-fixed  table-responsive' style='table-layout:fixed'>
                                                            <tr>
                                                              <td class='dvtCellLabel' style='border: 1px solid #ccc;'> Contract:<span class='mandatory'>&nbsp;*</span></td>
                                                              <td style='border: 1px solid #ccc;'>".GetContract($contractcode,$buildingid)."</td>

                                                              <td class='dvtCellLabel' style='border: 1px solid #ccc;'>Schedule From:</td>
                                                              <td style='border: 1px solid #ccc;'><input type='text' class='form-control txt' $disable data-date-start-date='$ppmfrom' data-date-end-date='$ppmto' data-provide='datepicker' maxlength=10  onkeypress='return AllowNumeric1(event)'  name='txd_A_ppmfrom' id='txd_A_ppmfrom'   value='$contractfrom' placeholder='dd-mm-yyyy' ></td>
                                                              <td class='dvtCellLabel' style='border: 1px solid #ccc;'>Schedule To:</td>
                                                              <td style='border: 1px solid #ccc;'><input type='text' class='form-control txt' $disable data-date-start-date='$ppmfrom' data-date-end-date='$ppmto' data-provide='datepicker' maxlength=10  onkeypress='return AllowNumeric1(event)'  name='txd_A_ppmto' id='txd_A_ppmto'   value='$contractto' placeholder='dd-mm-yyyy' ></td>

                                                             </tr>

                                                            <tr>
                                                              <td class='dvtCellLabel' style='border: 1px solid #ccc;'>Remarks:</td>
                                                              <td style='border: 1px solid #ccc;' colspan=3><input type='text'  class='form-control txt txt' id='txt_A_remarks' name='txt_A_remarks'    value='$remarks'></td>
                                                              <td align=left style='border: 1px solid #ccc;' colspan=2>
                                                                 $saveicon
                                                                     <input type='hidden' class=textboxcombo name='txt_A_userid' id='txt_A_userid' value='".$_SESSION['SESSuserID']."'>
                                                                     <input type='hidden' class=textboxcombo name='txt_A_buildingid' id='txt_A_buildingid' value='".$buildingid."'>

                                                                     <input type=hidden id=child name=child value='child'>
                                                                     <input type=hidden id=childid name=childid value='".$_REQUEST['CHILDID']."'>

                                                                  </td>
                                                               </tr>
                                                            </table>
                                                            </form>

                                                 </div><br>";
if(BuildingStatus($buildingid)!='Inactive' && stripos(json_encode(BuildingAreaIncharge($buildingid)),$_SESSION['SESSuserID']) == true) {
        echo $entrydata;
}

}
  $start1=0;
  $limit1=6;
          if($_GET['id1']){
             $id1=$_GET['id1'];
             $start1=($id1-1)*$limit1;
          }else{
             $id1=1;
          }
  $addsql="";
  if($_REQUEST['search']!=""){
     $addsql = " and (";
     $addsql .= " frequency like '%".$_REQUEST['search']."%'";
     $addsql .= " or remarks like '%".$_REQUEST['search']."%'";
     $addsql .= ")";
  }
$rows1=mysql_num_rows(mysql_query("SELECT * FROM in_ppmscheduleperiod where buildingid='".$buildingid."' $addsql"));

 echo "<div class='box' style='border:0px;padding:0px;'>

       <div class='box-tools pull-right '>
            <ul class='pagination pagination-sm no-padding pull-right'>";

                $total1=ceil($rows1/$limit1);
                for($i=1;$i<=$total1;$i++){
                    if($i==$id1) {
                       echo "<li class='active' ><a href='' style='padding-top:0px;padding-bottom:0px;padding-left:.5em;padding-right:.5em;'>".$i."</a></li>";
                    }else {
                       echo "<li><a href='?ID=".$_REQUEST['ID']."&id1=".$i."' style='padding-top:0px;padding-bottom:0px;padding-left:.5em;padding-right:.5em;'>".$i."</a></li>"; }
                    }
       echo "</ul>
       </div>
       </div>";


$sql = "SELECT in_ppmscheduleperiod.*
        ,DATE_FORMAT(ppmfrom,'%d-%m-%Y') as ppmfrom,DATE_FORMAT(ppmto,'%d-%m-%Y') as ppmto
        FROM in_ppmscheduleperiod where in_ppmscheduleperiod.buildingid='".$buildingid."' order by id desc";
$sql = $sql. " $addsql LIMIT $start1, $limit1";

$result = mysql_query($sql) or die(mysql_error());

        $entrydatatable = "<table class='table table-bordered table-condensed table-fixed table-striped table-responsive' style='border:1px #2F3C43;'>";
        $entrydatatable.="<thead><tr>";

          //  $entrydatatable.= "<th class='bg-light-blue' style='width:15%;text-align:left;border-bottom:1px #2F3C43 solid;border-top:1px #fff solid;color:#FFFFFF' >Job No</th>";
        $entrydatatable.= "<th class='bg-light-blue' style='width:20%;text-align:left;border-bottom:1px #2F3C43 solid;border-top:1px #fff solid;color:#FFFFFF' >Contract</th>";
        //$entrydatatable.= "<th class='bg-light-blue' style='width:15%;text-align:left;border-bottom:1px #2F3C43 solid;border-top:1px #fff solid;color:#FFFFFF' >Contract From</th>";
       // $entrydatatable.= "<th class='bg-light-blue' style='width:15%;text-align:left;border-bottom:1px #2F3C43 solid;border-top:1px #fff solid;color:#FFFFFF' >Contract To</th>";
        $entrydatatable.= "<th class='bg-light-blue' style='width:10%;text-align:left;border-bottom:1px #2F3C43 solid;border-top:1px #fff solid;color:#FFFFFF' >Schedule From</th>";
        $entrydatatable.= "<th class='bg-light-blue' style='width:10%;text-align:left;border-bottom:1px #2F3C43 solid;border-top:1px #fff solid;color:#FFFFFF' >Schedule To</th>";
        $entrydatatable.= "<th class='bg-light-blue' style='width:15%;text-align:left;border-bottom:1px #2F3C43 solid;border-top:1px #fff solid;color:#FFFFFF' >Remarks</th>";
        if($_REQUEST['ACT']!='dashboard' && stripos(json_encode(BuildingAreaIncharge($buildingid)),$_SESSION['SESSuserID']) == true){
       // $entrydatatable.= "<th class='bg-light-blue' style='width:5%;text-align:left;border-bottom:1px #2F3C43 solid;border-top:1px #fff solid;color:#FFFFFF' >Edit</th>";
        $entrydatatable.= "<th class='bg-light-blue' style='width:5%;text-align:left;border-bottom:1px #2F3C43 solid;border-top:1px #fff solid;color:#FFFFFF' >Remove</th>";
        $entrydatatable .="<th class='bg-light-blue' style='width:5%;text-align:left;border-bottom:1px #2F3C43 solid;border-top:1px #fff solid;color:#FFFFFF' >Post</th>\n";
        }

        $entrydatatable.= "</tr></thead><tbody>";

$slno=1;
while($loginResultArrayChild   = mysql_fetch_array($result)){

         if($_REQUEST['CHILDID']==$loginResultArrayChild['id']){
            $colorbg ="#F1F1F1";
            $colorfc ="#000000";
         }else{
            $colorbg ='#FFFFFF';
            $colorfc ="#5A5A5A";
         }


        $entrydatatable.= "<tr>";


        $entrydatatable.= "<td style='background-color:$colorbg;color:$colorfc;border:1px #ccc solid;'>" . $loginResultArrayChild['contractcode'] . "</td>";
       // $entrydatatable.= "<td style='background-color:$colorbg;color:$colorfc;border:1px #ccc solid;'>" . $loginResultArrayChild['contractfrom'] . "</td>";
       // $entrydatatable.= "<td style='background-color:$colorbg;color:$colorfc;border:1px #ccc solid;'>" . $loginResultArrayChild['contractto']."</td>";
        $entrydatatable.= "<td style='background-color:$colorbg;color:$colorfc;border:1px #ccc solid;'>" . $loginResultArrayChild['ppmfrom'] . "</td>";
        $entrydatatable.= "<td style='background-color:$colorbg;color:$colorfc;border:1px #ccc solid;'>" . $loginResultArrayChild['ppmto']."</td>";
        $entrydatatable.= "<td style='background-color:$colorbg;color:$colorfc;border:1px #ccc solid;'>" . $loginResultArrayChild['remarks'] ."</td>";
        if($_REQUEST['ACT']!='dashboard' && stripos(json_encode(BuildingAreaIncharge($buildingid)),$_SESSION['SESSuserID']) == true){
        if($loginResultArrayChild['posted']<>'YES'){

         $entrydatatable.= "<td style='background-color:$colorbg;color:$colorfc;border:1px #ccc solid;' align='center'><a href='javascript:deleteChildrecord(\"".$loginResultArrayChild['id']."\");'><img src='ico/remove.png' title='Remove' width='16' height='16'></a></td>";
         $entrydatatable.= " <td style='background-color:$colorbg;color:$colorfc;border:1px #ccc solid;' align='center'><a href='javascript:ScheduleChildrecord(\"".$loginResultArrayChild['id']."\",\"".$loginResultArrayChild['buildingid']."\",\"".$loginResultArrayChild['contractcode']."\");'><img src='ico/stamp_icon.jpg' id='postimg' name='postimg' title='Post' width='20' height='20'></a></td>";
       }else{
        $entrydatatable .="<td style='background-color:$colorbg;color:$colorfc;border:1px #ccc solid;' align='center' colspan=3>Posted</a></td>";

       }
       }
        $entrydatatable.= "</tr>";
        $slno++;
}

$entrydatatable.= "</tbody></table>";
echo $entrydatatable;


?>


      </div>

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
<?php


function GetContract($contractcode,$buildngid){
         $CMB = "<select name='cmb_A_contractcode'  id='cmb_A_contractcode'  class='form-control select2 no-padding' style='width:100%' onchange='javascript:if(document.frmChildEdit) document.frmChildEdit.submit();'>"; // onChange='getContarctBuilding(this.value)'
         $CMB .= "<option value=''></option>";
         $SEL =  "select t_activitycenter.jobno from t_activitycenter,tbl_projectbuilding,in_ppmschedule where activitycenter='CONTRACT' and t_activitycenter.status<>'INACTIVE'
                  and tbl_projectbuilding.buildingid='$buildngid' and tbl_projectbuilding.buildingstatus='Active'
                  and tbl_projectbuilding.contractcode=t_activitycenter.jobno and tbl_projectbuilding.contractcode=in_ppmschedule.contractcode and in_ppmschedule.posted='YES' group by in_ppmschedule.contractcode";
         $RES = mysql_query($SEL);
         while ($ARR = mysql_fetch_array($RES)) {
               $SEL = "";
               if($contractcode==$ARR['jobno']){ $SEL =  "SELECTED";}
               $CMB .= "<option value='".$ARR['jobno']."' $SEL >".$ARR['jobno']."</option>";
         }
         $CMB .= "</select>";
         return $CMB;
}
function BuildingStatus($buildingid){
        // master table
         $sql_b = "select status from tbl_building where id='$buildingid'";
         $res_b = mysql_query($sql_b);
         $arr_b = mysql_fetch_array($res_b);
         return $arr_b['status'];
}
function BuildingAreaIncharge($buildingid){
        // master table
         $sql_b = "select areaincharge from tbl_building where id='$buildingid'";
         $res_b = mysql_query($sql_b);
         $arr_b = mysql_fetch_array($res_b);
         return $arr_b['areaincharge'];
}
?>
