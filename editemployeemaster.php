<?php
session_start();
if($_SESSION['pr'] == "") {
  $_SESSION['pr'] = $_REQUEST['pr'];
}
//include("FusionCharts.php");
require "connection.php";
require "pagingObj.php";
$_REQUEST['ID'] = isset($_REQUEST['ID']) ? $_REQUEST['ID'] : '';
$_REQUEST['dr'] = isset($_REQUEST['dr']) ? $_REQUEST['dr'] : '';
$_REQUEST['POST'] = isset($_REQUEST['POST']) ? $_REQUEST['POST'] : '';

          $grid = new MyPHPGrid('frmPage');
          $grid->TableName = "in_personalinfo";
          $grid->formName = "employeemaster.php";
          $grid->SyncSession($grid);

             $display="none";
             $displaydate="none";

if($_REQUEST['ID'] != "0") {
             $mode=$_REQUEST['ID'];
             $saveid=$_REQUEST['ID'];
             $SQL = " Select *,DATE_FORMAT(empdob,'%d-%m-%Y') as empdob,DATE_FORMAT(empconfirmation,'%d-%m-%Y') as empconfirmation,DATE_FORMAT(empdateofjoin,'%d-%m-%Y') as dateofjoin,
                      DATE_FORMAT(probationenddate,'%d-%m-%Y') as probationenddate,
                      DATE_FORMAT(empinactivedate,'%d-%m-%Y') as empinactivedate,
                      DATE_FORMAT(empnoticedate,'%d-%m-%Y') as empnoticedate,
                      DATE_FORMAT(empworkpermitcanceldate,'%d-%m-%Y') as empworkpermitcanceldate,
                      DATE_FORMAT(empidcanceldate,'%d-%m-%Y') as empidcanceldate,
                      DATE_FORMAT(empinsurancecanceldate,'%d-%m-%Y') as empinsurancecanceldate,
                      DATE_FORMAT(emplastworkingdate,'%d-%m-%Y') as emplastworkingdate,DATE_FORMAT(empvisacanceldate,'%d-%m-%Y') as empvisacanceldate,DATE_FORMAT(empexitdate,'%d-%m-%Y') as empexitdate from in_personalinfo where id='".$_REQUEST['ID']."'";
             mysqli_query($con,"SET NAMES 'utf8'");
             $SQLRes =  mysqli_query($con,$SQL) or die(mysqli_error()."<br>".$SQL);
              if(mysqli_num_rows($SQLRes)>=1){
                while($loginResultArray   = mysqli_fetch_array($SQLRes)){
                   $empid = $loginResultArray['empid'];
                   $empfirstename = $loginResultArray['empfirstename'];
                   $emplastename = $loginResultArray['emplastename'];
                   $emplabourname = $loginResultArray['emplabourname'];
                   $empgender = $loginResultArray['empgender'];
                   $empnationality = $loginResultArray['empnationality'];
                   $empmaritalstatus = $loginResultArray['empmaritalstatus'];
                   $empdob = $loginResultArray['empdob'];
                   if($empdob=='00-00-0000')$empdob="";
                   $emprelegion = $loginResultArray['emprelegion'];
                   $empaname = $loginResultArray['empaname'];
                   $empeducation = $loginResultArray['empeducation'];
                   $empskills = $loginResultArray['empskills'];
                   $emplocaladdress = $loginResultArray['emplocaladdress'];
                   $emplocaltel = $loginResultArray['emplocaltel'];
                   $emplocalmobile = $loginResultArray['emplocalmobile'];
                   $empalternativeno=  $loginResultArray['empalternativeno'];
                   $emppermenantaddress = $loginResultArray['emppermenantaddress'];
                   $emphomecountrytel = $loginResultArray['emphomecountrytel'];
                   $emhomecountrymobile = $loginResultArray['emhomecountrymobile'];
                   $emppersonalemail = $loginResultArray['emppersonalemail'];
                   $empimage =    $loginResultArray['empimage'];
                   $empcompany=$loginResultArray['empcompany'];
                   $empsponsercompany=$loginResultArray['empsponsercompany'];
                   $empdivision=$loginResultArray['empdivision'];
                   $empdepartment=$loginResultArray['empdepartment'];
                   $emptype= $loginResultArray['emptype'];
                   $empcategory= $loginResultArray['empcategory'];
                   $empdesignation= $loginResultArray['empdesignation'];
                   $emplabourdesignation= $loginResultArray['emplabourdesignation'];
                   $empgrade=$loginResultArray['empgrade'];
                   $empdateofjoin=$loginResultArray['dateofjoin'];
                   if($empdateofjoin=='00-00-0000')$empdateofjoin="";
                   $emppassportno= $loginResultArray['emppassportno'];
                   $empprobation=$loginResultArray['empprobation'];
                   $empconfirmation= $loginResultArray['empconfirmation'];
                   if($empconfirmation=='00-00-0000')$empconfirmation="";
                   $empworklocation= $loginResultArray['empworklocation'];
                   $empreportingofficer= $loginResultArray['empreportingofficer'];
                   $empworktel= $loginResultArray['empworktel'];
                   $empworkmobile= $loginResultArray['empworkmobile'];
                   $empworkemail=$loginResultArray['empworkemail'];
                   $empaccommodationby=$loginResultArray['empaccommodationby'];
                   $empaccommodation=$loginResultArray['empaccommodation'];
                   $emproomnumber= $loginResultArray['emproomnumber'];
                   $emprecruitementsource= $loginResultArray['emprecruitementsource'];
                   $emprecruitedthrough= $loginResultArray['emprecruitedthrough'];
                   $empstatus= $loginResultArray['empstatus'];
                   $empleavepackage= $loginResultArray['empleavepackage'];
                   $emplastworkingdate =$loginResultArray['emplastworkingdate'];
                   if($emplastworkingdate=='00-00-0000')$emplastworkingdate="";
                   $empvisacanceldate =$loginResultArray['empvisacanceldate'];
                   if($empvisacanceldate=='00-00-0000')$empvisacanceldate="";
                   $empexitdate=  $loginResultArray['empexitdate'];
                   if($empexitdate=='00-00-0000')$empexitdate="";
                   $empinactivedate=  $loginResultArray['empinactivedate'];
                   if($empinactivedate=='00-00-0000')$empinactivedate="";
                   $empnoticedate= $loginResultArray['empnoticedate'];
                   if($empnoticedate=='00-00-0000')$empnoticedate="";
                   $empworkpermitcanceldate= $loginResultArray['empworkpermitcanceldate'];
                   if($empworkpermitcanceldate=='00-00-0000')$empworkpermitcanceldate="";
                   $empinsurancecanceldate= $loginResultArray['empinsurancecanceldate'];
                   if($empinsurancecanceldate=='00-00-0000')$empinsurancecanceldate="";
                   $empidcanceldate =$loginResultArray['empidcanceldate'];
                   if($empidcanceldate=='00-00-0000')$empidcanceldate="";
                   $emplaborcardno=$loginResultArray['emplaborcardno'];
                   $role = $loginResultArray['rolecode'];
                 //  $roomnumber = $loginResultArray['roomnumber'];
                   $molpersonalno = $loginResultArray['molpersonalno'];
                   $immigrationuidno = $loginResultArray['immigrationuidno'];
                   $sales = $loginResultArray['sales'];
                   $shareto = $loginResultArray['shareto'];
                   $assigntask = $loginResultArray['assigntask'];
                   $probationenddate = date('d-m-Y', strtotime($loginResultArray['empdateofjoin']."+".$empprobation." days"));
                   $empvisatype=$loginResultArray['empvisatype'];
                   $passportwith=$loginResultArray['passportwith'];
                   $servicetechnician=$loginResultArray['servicetechnician'];
                  }
              }
           }else{
             $mode="";
             $saveid = GetLastSqeID("in_personalinfo");
             $empsponsercompany=$_REQUEST['cmb_lookuplist1'];
             $assigntask="No";
             $prefix=getprefix($empsponsercompany);
             $empid =$prefix.GetLastSqeIDrefid($empsponsercompany);
             $empgrade=GetLastSqeID("in_personalinfo");
             $emproomnumber=$servicetechnician=$empaccommodationby=$empaccommodation=$empaccommodationby=$shareto=$sales=$empskills=$empvisatype=$emprecruitedthrough=$emprecruitementsource=$empworktel=$empworkemail=$emppersonalemail=$empeducation=$emhomecountrymobile=$empworkmobile=$empworklocation=$empalternativeno=$emphomecountrytel=$emplocalmobile=$emplocaltel=$emppermenantaddress=$emplocaladdress=$passportwith=$emppassportno=$empreportingofficer=$role=$empstatus=$empprobation=$molpersonalno=$immigrationuidno=$emplaborcardno=$emplabourdesignation=$empfirstename=$empdesignation=$emplastename=$empcategory=$emptype=$emplabourname=$empdepartment=$empaname=$empgender=$empcompany=$empdivision=$empmaritalstatus=$empnationality="";
             $empconfirmation=$empdob=$empnoticedate=$empinactivedate=$empvisacanceldate=$empworkpermitcanceldate=$empidcanceldate=$empexitdate=$probationenddate=$empdateofjoin=date('d-m-Y');

           }
           if(isset($empstatus))
                  if($empstatus=="Active"){
                       $display="none";
                       $displaydate="none" ;
                  }
                   else if($empstatus=='Resigned' || $empstatus=='Terminated' || $empstatus=='Absconded' || $empstatus=='End of contract'){
                        $display="table-row";
                        $displaydate="table-row";
                  }else{
                        $display="table-row";
                        $displaydate="none";
                  }
     $empimage = isset($empimage) ? $empimage : '';

       if($empimage<>''){
          $download="<a  href='download.php?ID=".$_REQUEST['ID']."&folder=staffphoto&filename=".$empimage."' ><i class='fa fa-download' data-toggle='tooltip' data-placement='bottom' title='Download' aria-hidden='true'></i></a>&nbsp;&nbsp;";
       }else{
          $download ="&nbsp;&nbsp;&nbsp;" ;
       }
         if($empimage==''){
           $empimage =  "dummyprofile.png";
         }else{
           $empimage=$empimage;
         }

if($_REQUEST['dr']=='view'){
   $edit="none";
   $view="inline";
   $title="Viewing Employee :".$empid." - ".$empfirstename."";
}else if($_REQUEST['dr']=='edit'){
      $edit="inline";
      $view="none";
      $title="Editing Employee : ".$empid." - ".$empfirstename."";
}else{
      $edit="inline";
      $view="none";
      $title="Adding New Employee";
}
if($_REQUEST['POST']=='RESET'){

             $sql1 = "update in_user set oldpwd=pwd,pwd='123' where userid='".$_REQUEST['userid']."'";
             mysqli_query($sql1) or die(mysqli_error()."PA-115<br>".$sql1);

             $sql1 = "update in_personalinfo set empinsurancecanceldate='".date('Y-m-d')."' where empid='".$_REQUEST['userid']."'";
             mysqli_query($sql1) or die(mysqli_error()."PA-115<br>".$sql1);
            echo "<center><font color='green'> ".$_REQUEST['userid']." password has been reset successfully! </center>";
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

<style>
.fileUpload {
    position: relative;
    overflow: hidden;
    margin: 0px;

    width:80px;
    height:26px;
}
.fileUpload input.upload {
    position: absolute;
    top: 0;
    right: 0;
    margin: 0;
    padding: 0;
    font-size: 16px;
    cursor: pointer;
    opacity: 0;
    filter: alpha(opacity=0);
}
</style>
<script language="javascript">
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
function getDivision(cattype){

      xmlHttp=GetXmlHttpObject()
      if (xmlHttp==null)
      {
                 alert ("Browser does not support HTTP Request")
                 return
      }

      var url="combofunctions_hr.php?level=divcenter&categorytype="+cattype;
      xmlHttp.onreadystatechange=stateChangedcombo8
      xmlHttp.open("POST",url,true)
      xmlHttp.send(null)

}

function stateChangedcombo8(){
       if (xmlHttp.readyState==4 || xmlHttp.readyState=="complete")
       {

             var s1 = trim(xmlHttp.responseText);
             var res = s1.split("#");
             document.getElementById('getdivision').innerHTML=res[0];
             document.getElementById('getcenter').innerHTML=res[1];

       }
}

function displayrejoin(){

   var cmb_A_empstatus=document.getElementById('cmb_A_empstatus');
        if(cmb_A_empstatus){
        var value = cmb_A_empstatus.options[cmb_A_empstatus.selectedIndex].value;
        }
 if(value=='Terminated' ||  value=='Resigned' ||  value=='Absconded' || value=='End of contract'){
            var tr1=document.getElementById('tr1');
            tr1.style.display="table-row";
            var tr2=document.getElementById('tr2');
            tr2.style.display="table-row";
            document.getElementById('txd_A_empinactivedate').value="";

   }else{
       var tr1=document.getElementById('tr1');
       tr1.style.display="none";
       var tr2=document.getElementById('tr2');
       tr2.style.display="none";
       document.getElementById('txd_A_empinactivedate').value="";
       document.getElementById('txd_A_empvisacanceldate').value="";
       document.getElementById('txd_A_empexitdate').value="";
       document.getElementById('txd_A_empnoticedate').value="";
       document.getElementById('txd_A_empworkpermitcanceldate').value="";
       document.getElementById('txd_A_empidcanceldate').value="";
   }
}
function getAccomodation(cattype){


      xmlHttp=GetXmlHttpObject()
      if (xmlHttp==null)
      {
                 alert ("Browser does not support HTTP Request")
                 return
      }

      var url="combofunctions_hr.php?level=Accomodation&categorytype="+cattype;
      xmlHttp.onreadystatechange=stateChangedcombo
      xmlHttp.open("POST",url,true)
      xmlHttp.send(null)

}

function stateChangedcombo(){
       if (xmlHttp.readyState==4 || xmlHttp.readyState=="complete")
       {

             var s1 = trim(xmlHttp.responseText);
             document.getElementById('getacc').innerHTML=s1;

       }
}
function resetpassword(postid){


                   alertify.confirm("Are you sure you want to reset the password of employee "+postid+" ?", function (e) {
                            if (e) {

                              document.frmEdit.action='editemployeemaster.php?POST=RESET&userid='+postid+'&cmb_lookuplist1='+document.getElementById('cmb_A_empsponsercompany').value+'&dr=edit&ID='+document.getElementById('mode').value;
                              document.frmEdit.submit();
                            } else {
                               return;
                            }

                          });

}
function editingrecord(action)
{

       var txt_A_empid=document.getElementById('txt_A_empid');
       if(txt_A_empid){
          if ((txt_A_empid.value==null)||(txt_A_empid.value=="")){
               alertify.alert("Enter Employee ID", function () {
               txt_A_empid.focus();

          });
             return;
          }
       }

       var txt_A_empfirstename=document.getElementById('txt_A_empfirstename');
       if(txt_A_empfirstename){
          if ((txt_A_empfirstename.value==null)||(txt_A_empfirstename.value=="")){
               alertify.alert("Enter First Name", function () {
               txt_A_empfirstename.focus();

          });
             return;
          }
       }
       var txt_A_emplastename=document.getElementById('txt_A_emplastename');
       if(txt_A_emplastename){
          if ((txt_A_emplastename.value==null)||(txt_A_emplastename.value=="")){
               alertify.alert("Enter Last Name", function () {
               txt_A_emplastename.focus();

          });
             return;
          }
       }
       var txt_A_emplabourname=document.getElementById('txt_A_emplabourname');
       if(txt_A_emplabourname){
          if ((txt_A_emplabourname.value==null)||(txt_A_emplabourname.value=="")){
               alertify.alert("Enter Labour Name", function () {
               txt_A_emplabourname.focus();

          });
             return;
          }
       }
       var cmb_A_empsponsercompany=document.getElementById('cmb_A_empsponsercompany');
       if(cmb_A_empsponsercompany){
          if ((cmb_A_empsponsercompany.value==null)||(cmb_A_empsponsercompany.value=="")){
               alertify.alert("Select Sponser Company", function () {
               cmb_A_empsponsercompany.focus();

          });
             return;
          }
       }
       var cmb_A_empcompany=document.getElementById('cmb_A_empcompany');
       if(cmb_A_empcompany){
          if ((cmb_A_empcompany.value==null)||(cmb_A_empcompany.value=="")){
               alertify.alert("Select Company", function () {
               cmb_A_empcompany.focus();

          });
             return;
          }
       }
       var cmb_A_empdivision=document.getElementById('cmb_A_empdivision');
       if(cmb_A_empdivision){
          if ((cmb_A_empdivision.value==null)||(cmb_A_empdivision.value=="")){
               alertify.alert("Select Division", function () {
               cmb_A_empdivision.focus();

          });
             return;
          }
       }
        var cmb_A_empdesignation=document.getElementById('cmb_A_empdesignation');
       if(cmb_A_empdesignation){
          if ((cmb_A_empdesignation.value==null)||(cmb_A_empdesignation.value=="")){
               alertify.alert("Select Designation", function () {
               cmb_A_empdesignation.focus();

          });
             return;
          }
       }
       var cmb_A_empdepartment=document.getElementById('cmb_A_empdepartment');
       if(cmb_A_empdepartment){
          if ((cmb_A_empdepartment.value==null)||(cmb_A_empdepartment.value=="")){
               alertify.alert("Select Department", function () {
               cmb_A_empdepartment.focus();

          });
             return;
          }
       }

       var txd_A_empdateofjoin=document.getElementById('txd_A_empdateofjoin');
       if(txd_A_empdateofjoin){
          if ((txd_A_empdateofjoin.value=="00-00-0000")||(txd_A_empdateofjoin.value=="")){
               alertify.alert("Select DOJ", function () {
                txd_A_empdateofjoin.focus();

          });
             return;
          }
       }

      /* var cmb_A_empstatus=document.getElementById('cmb_A_empstatus');
       if(cmb_A_empstatus){
          if ((cmb_A_empstatus.value==null)||(cmb_A_empstatus.value=="")){
               alertify.alert("Select status", function () {
                cmb_A_empstatus.focus();

          });
             return;
          }
       }

       if(cmb_A_empstatus.value!="Active" && cmb_A_empstatus.value!="On Leave"  && cmb_A_empstatus.value!="Probation"){
          var txd_A_empinactivedate=document.getElementById('txd_A_empinactivedate');
          if(txd_A_empinactivedate){
             if ((txd_A_empinactivedate.value=="")||(txd_A_empinactivedate.value=="00-00-0000")){
                  alertify.alert("Select Last Working Day", function () {
                  txd_A_empinactivedate.focus();

                  });
                  return;
                  }
             }
       }



        education= $('#ckk_A_empeducation.select2').val();
        skills= $('#ckk_A_empskills.select2').val();
        if(skills==''||skills==null)
        {
         alertify.alert("Select Skills ", function () {
                  //ckk_A_empskills.focus();

                  });
                  return;
        }
        if(education==''||education==null)
        {
         alertify.alert("Select Education ", function () {
                  //ckk_A_empeducation.focus();

                  });
                  return;
        }*/

        var parameter = get(document.frmEdit)+'action='+action;//+'&ckk_A_empeducation='+education+'&ckk_A_empskills='+skills;
       // alert(parameter);

       if(document.getElementById('mode').value==null){
              document.getElementById('frmEdit').action='in_action.php'+parameter;
              document.getElementById('frmEdit').submit();

       }else{
              document.getElementById('frmEdit').action='in_action.php'+parameter;
              document.getElementById('frmEdit').submit();

       }
       return;

       insertfunction(get(document.frmEdit),action)
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
                                 window.location.href='editemployeemaster.php?cmb_lookuplist1='+document.getElementById('cmb_A_empcompany').value+'&dr=edit&ID='+document.getElementById('saveid').value;
                                });
                               }else if(s1.toString() == s3.toString()){
                                alertify.alert("Record Updated", function () {
                                window.location.href='editemployeemaster.php?cmb_lookuplist1='+document.getElementById('cmb_A_empcompany').value+'&dr=edit&ID='+document.getElementById('mode').value;

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
                                 window.location.href='editemployeemaster.php?dr=add&cmb_lookuplist1='+document.getElementById('cmb_A_empcompany').value+'&ID=0';
                                });
                               }else if(s1.toString() == s3.toString()){
                                alertify.alert("Record Updated", function () {
                                window.location.href='editemployeemaster.php?cmb_lookuplist1='+document.getElementById('cmb_A_empcompany').value+'&dr=add&ID=0';

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
                                window.location.href='employeemaster.php?ID=0';
                               }else if(s1.toString() == s3.toString()){
                                alertify.alert("Record Updated", function () {
                                window.location.href='employeemaster.php';

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

                 <a class="pull-left" href="employeemaster.php?objectid=<?echo $_SESSION['objectid']; ?>&ps=1&pr=I,U,D&txtsearch=<?echo $_SESSION['txtsearch']; ?>" data-toggle="tooltip" data-placement="right" title="Back to Employee Master"><i class='fa fa-backward'></i></a>
                 <h2 class="title">&nbsp;&nbsp;<?echo $title; ?></h2>

                 <ol class='breadcrumb'>
                  <li><a href="#"><a href="blank.php" >HR</a></li>
                  <li><a href="#"><a href="employeemaster.php?ps=1">EMPLOYEE MASTER</a></li>
                  <li class="active"><?echo $title; ?></li>

                </ol>

         </section>


         <section class="content" id='content-content-id' style='padding-right:5px;padding-left:5px;margin-top:-10px;' >

                  <div class="nav-tabs-custom" id="nav-tabs-custom-id">
                        <ul class="nav nav-tabs">

                           <li class="active"><a href="#personal" onclick='javascript:loadpage(2);' data-toggle="tab"><i class="fa fa-user" aria-hidden="true"></i> Personnel</a></li>
                           
                           <?php
                           if($_REQUEST['ID']!='0'){
						   	  echo "<li><a href='#usermodule'  onclick='javascript:loadpage(3);' data-toggle='tab'><i class='fa fa-group' aria-hidden='true'></i> Module(s)</a></li>";
						   }
                           
                           ?>							
                        <!-- <?php if($_REQUEST['ID']!=0 && (stripos(json_encode($_SESSION['role']),'HR MANAGER') !== false || stripos(json_encode($_SESSION['role']),'PAYROLL MANAGER') !== false)){ ?>
                           <li><a href="#kithkin"   onclick='javascript:loadpage(3);' data-toggle="tab"><i class="fa fa-group" aria-hidden="true"></i> Kith & Kin</a></li>
                           <li><a href="#experience" data-toggle="tab" onclick='javascript:loadpage(4);'><i class="fa fa-briefcase" aria-hidden="true"></i> Experience</a></li>
                           <li><a href="#salary" data-toggle="tab" onclick='javascript:loadpage(5);'><i class="fa fa-dollar" aria-hidden="true"></i> Salary</a></li>
                           <li><a href="#benefits" data-toggle="tab" onclick='javascript:loadpage(6);'><i class="fa fa-database" aria-hidden="true"></i> Benefit</a></li>
                           <li><a href="#postingpromotion" data-toggle="tab" onclick='javascript:loadpage(7);'><i class="fa fa-arrow-up" aria-hidden="true"></i> Postings</a></li>
                           <li><a href="#documents" data-toggle="tab" onclick='javascript:loadpage(8);'><i class="fa fa-file" aria-hidden="true"></i> Document</a></li>
                           <li><a href="#gadgets" data-toggle="tab" onclick='javascript:loadpage(9);'><i class="fa fa-archive" aria-hidden="true"></i> Gadget</a></li>
                           <li><a href="#leavepackage" data-toggle="tab" onclick='javascript:loadpage(10);'><i class="fa fa-cog" aria-hidden="true"></i> L Package</a></li>
                           <li><a href="#leave" data-toggle="tab" onclick='javascript:loadpage(11);'><i class="fa fa-user-times" aria-hidden="true"></i> Leaves</a></li>
                           <li><a href="#addded" data-toggle="tab" onclick='javascript:loadpage(12);'><i class="fa fa-money" aria-hidden="true"></i> Adv/Ded</a></li>
                         <?php  } ?>  -->
                       </ul>

                        <div class="tab-content" id='tab-content-id'>
                           <div class="tab-pane active" id="personal">
                           <div class="box-body no-padding" id='box-body-id'>
                                   <div class='table-responsive no-padding'>
<?php
                         $entrydata = "<form name='frmEdit' method='post' id='frmEdit' enctype='multipart/form-data'>

                                          <div class='profile-header' style='background-color:#F9F9F9;border-top: 1px solid #ccc;border-left: 1px solid #ccc;border-right: 1px solid #ccc;height:92px;'>
                                                    <div class='col-lg-6 col-md-6'>
                                                          <div class='profile-pic'>
                                                             <a href='staffphoto/".$empimage."' target='_blank'><img src='staffphoto/".$empimage."' width='100px' height='80px'/></a>

                                                             $download

                                                          </div>
                                                          <div class='profile-details' style='display:$edit'>
                                                             <p><label>Employee Id<span class='mandatory'>&nbsp;*</span></label>:&nbsp<span><input type='text' class='txt-name' name='txt_A_empgrade' id='txt_A_empgrade' value='$empgrade' /></span></p>
                                                             <p><label>First Name<span class='mandatory'>&nbsp;*</span></label>:&nbsp<span><input type='text' class='txt-name' name='txt_A_empfirstename' id='txt_A_empfirstename' value='$empfirstename' /></span></p>
                                                             <p><label>Last Name <span class='mandatory'>&nbsp;*</span></label>:&nbsp<span><input type='text' class='txt-name' name='txt_A_emplastename' id='txt_A_emplastename' value='$emplastename' /></span></p>


                                                          </div>
                                                          <div class='profile-details' style='display:$view'>
                                                             <p><label>Emp Id</label>:&nbsp<span>$empid</span></p>
                                                             <p><label>First Name</label>:&nbsp<span>$empfirstename</span></p>
                                                             <p><label>Last Name </label>:&nbsp<span>$emplastename</span></p>

                                                          </div>
                                                   </div>";
               /*  if($_REQUEST['ID']!=0){

                         $entrydata .= "            <div class='col-lg-3 col-md-3' >

                                                    <div style='margin-top:0px;' style='height:90px;'>
                                                       ".renderChartHTML("flash/Column2D.swf?ChartNoDataText=No Data Exists", "", GetGrapthXMLForLeaves($empid), "myNext",300,82, false)."
                                                    </div>

                                                  </div>

                                                  <div class='col-lg-3 col-md-3' >

                                                     <div style='margin-top:0px;' style='height:90px;'>
                                                        ".renderChartHTML("flash/Column2D.swf?ChartNoDataText=No Data Exists", "", GetGrapthXMLForSalary($empid), "myNext",300,82, false)."
                                                      </div>

                                                  </div> ";
                   }  */

                        $entrydata .= "</div>


                                          <table class='table table-bordered table-condensed table-fixed  table-responsive' style='table-layout:fixed'>
                                                            <tr>
                                                              <td class='dvtCellLabel' style='border: 1px solid #ccc;'>User Id: <span class='mandatory'>&nbsp;*</span></td>
                                                              <td style='border: 1px solid #ccc;'><input type='hidden'  name='txt_A_empid' id='txt_A_empid' value='$empid'><b>$empid</b></td>

                                                              <td class='dvtCellLabel' style='border: 1px solid #ccc;'>Labour Name: <span class='mandatory'>&nbsp;*</span></td>
                                                              <td style='border: 1px solid #ccc;'><input type='text' class='form-control txt'   name='txt_A_emplabourname' id='txt_A_emplabourname'  value='$emplabourname'></td>
                                                              <td class='dvtCellLabel' style='border: 1px solid #ccc;'>Arabic Name:</td>
                                                              <td style='border: 1px solid #ccc;'><input type='text' class='form-control txt'  name='txt_A_empaname' id='txt_A_empaname'  value='$empaname' ></td>

                                                              </tr>
                                                              <tr>
                                                                <td class='dvtCellLabel' style='border: 1px solid #ccc;'>Gender:</td>
                                                                <td style='border: 1px solid #ccc;'>".GetGender($empgender)."</td>

                                                                <td class='dvtCellLabel' style='border: 1px solid #ccc;'>Date of birth:</td>
                                                                <td style='border: 1px solid #ccc;'><input type='text' class='form-control txt' data-provide='datepicker' maxlength=10  onkeypress='return AllowNumeric1(event)'   name='txd_A_empdob' id='txd_A_empdob'   value='$empdob' placeholder='dd-mm-yyyy' ></td>
                                                                <td class='dvtCellLabel' style='border: 1px solid #ccc;'>Nationality</td>
                                                                <td style='border: 1px solid #ccc;'>".GetNationality($empnationality)."</td>
                                                            </tr>
                                                            <tr>
                                                               <td class='dvtCellLabel' style='border: 1px solid #ccc;'>Sponsor Company: <span class='mandatory'>&nbsp;*</span></td>
                                                              <td style='border: 1px solid #ccc;'>".GetSponserCompany($empsponsercompany)."</td>
                                                              <td class='dvtCellLabel' style='border: 1px solid #ccc;'>Working Company: <span class='mandatory'>&nbsp;*</span></td>
                                                              <td style='border: 1px solid #ccc;'>".GetCompany($empcompany)."</td>
                                                              <td class='dvtCellLabel' style='border: 1px solid #ccc;'>Division: <span class='mandatory'>&nbsp;*</span></td>
                                                              <td style='border: 1px solid #ccc;' id=getdivision name=getdivision>".GetDivision($empcompany,$empdivision)."</td>

                                                            </tr>
                                                            <tr>
                                                              <td class='dvtCellLabel' style='border: 1px solid #ccc;'>Marital Status:</td>
                                                              <td style='border: 1px solid #ccc;'>".GetMaritalstatus($empmaritalstatus)."</td>

                                                              <td class='dvtCellLabel' style='border: 1px solid #ccc;'>Department:<span class='mandatory'>&nbsp;*</span></td>
                                                              <td style='border: 1px solid #ccc;'>".GetDepartment($empdepartment)."</td>
                                                              <td class='dvtCellLabel' style='border: 1px solid #ccc;'>Employee Type:</td>
                                                              <td style='border: 1px solid #ccc;'>".GetEmptype($emptype)."</td>
                                                            </tr>
                                                            <tr>
                                                              <td class='dvtCellLabel' style='border: 1px solid #ccc;'>Employee Category:</td>
                                                              <td style='border: 1px solid #ccc;'>".GetEmpCategory($empcategory)."</td>
                                                              <td class='dvtCellLabel' style='border: 1px solid #ccc;'>Emp. Designation :<span class='mandatory'>&nbsp;*</span></td>
                                                              <td style='border: 1px solid #ccc;'>".GetEmpDesignation($empdesignation)."</td>
                                                              <td class='dvtCellLabel' style='border: 1px solid #ccc;'>Labour Designation</td>
                                                              <td style='border: 1px solid #ccc;'>".GetEmpLabourDesignation($emplabourdesignation)."</td>
                                                            </tr>
                                                            <tr>
                                                              <td class='dvtCellLabel' style='border: 1px solid #ccc;'>Emirates ID:</td>
                                                              <td style='border: 1px solid #ccc;'><input type='text' class='form-control txt'  name='txt_A_emplaborcardno' id='txt_A_emplaborcardno'  value='$emplaborcardno'></td>

                                                              <td class='dvtCellLabel' style='border: 1px solid #ccc;'>Immigration UID No:</td>
                                                              <td style='border: 1px solid #ccc;'><input type='text' class='form-control txt'  name='txt_A_immigrationuidno' id='txt_A_immigrationuidno'  value='$immigrationuidno'></td>
                                                              <td class='dvtCellLabel' style='border: 1px solid #ccc;'>MOL Personal No:</td>
                                                              <td style='border: 1px solid #ccc;'><input type='text' class='form-control txt'  name='txt_A_molpersonalno' id='txt_A_molpersonalno'  value='$molpersonalno'></td>
                                                            </tr>
                                                            <tr>
                                                               <td class='dvtCellLabel' style='border: 1px solid #ccc;'>Photo Upload:</td>
                                                               <td style='border: 1px solid #ccc;'><input type='hidden' name='MAX_FILE_SIZE'><input type='file' name='userfile' class='upload'  id='userfile'></td>
                                                               <td class='dvtCellLabel' style='border: 1px solid #ccc;'>Date of join :<span class='mandatory'>&nbsp;*</span></td>
                                                               <td style='border: 1px solid #ccc;'><input type='text' class='form-control txt' data-provide='datepicker' maxlength=10  onkeypress='return AllowNumeric1(event)'   name='txd_A_empdateofjoin' id='txd_A_empdateofjoin'   value='$empdateofjoin' placeholder='dd-mm-yyyy' ></td>
                                                               <td class='dvtCellLabel' style='border: 1px solid #ccc;'>Probation (Days):</td>
                                                               <td style='border: 1px solid #ccc;'><input type='text' class='form-control txt'  onkeypress='return AllowNumeric1(event);' name='txt_A_empprobation' id='txt_A_empprobation'  value='$empprobation'></td>
                                                            </tr>

                                                           <tr  id=tr1 name=tr1 style='display:$displaydate'>
                                                            <td class='dvtCellLabel' style='border: 1px solid #ccc;'>Notice Date:</td>
                                                            <td style='border: 1px solid #ccc;'><input type='text' class='form-control txt' data-provide='datepicker' maxlength=10  onkeypress='return AllowNumeric1(event)'   name='txd_A_empnoticedate' id='txd_A_empnoticedate'   value='$empnoticedate' placeholder='dd-mm-yyyy' ></td>
                                                            <td class='dvtCellLabel' style='border: 1px solid #ccc;'>Last Working Day:<span class='mandatory'>&nbsp;*</span></td>
                                                            <td style='border: 1px solid #ccc;'><input type='text' class='form-control txt' data-provide='datepicker' maxlength=10  onkeypress='return AllowNumeric1(event)'   name='txd_A_empinactivedate' id='txd_A_empinactivedate'   value='$empinactivedate' placeholder='dd-mm-yyyy' ></td>
                                                            <td class='dvtCellLabel' style='border: 1px solid #ccc;'>Visa Cancelled on :</td>
                                                            <td style='border: 1px solid #ccc;'><input type='text' class='form-control txt' data-provide='datepicker' maxlength=10  onkeypress='return AllowNumeric1(event)'   name='txd_A_empvisacanceldate' id='txd_A_empvisacanceldate'   value='$empvisacanceldate' placeholder='dd-mm-yyyy' ></td>

                                                           </tr>
                                                           <tr  id=tr2 name=tr2 style='display:$displaydate'>
                                                            <td class='dvtCellLabel' style='border: 1px solid #ccc;'>Contract Cancelled on :</td>
                                                            <td style='border: 1px solid #ccc;'><input type='text' class='form-control txt' data-provide='datepicker' maxlength=10  onkeypress='return AllowNumeric1(event)'   name='txd_A_empworkpermitcanceldate' id='txd_A_empworkpermitcanceldate'   value='$empworkpermitcanceldate' placeholder='dd-mm-yyyy' ></td>
                                                            <td class='dvtCellLabel' style='border: 1px solid #ccc;'>EID Cancelled on :</td>
                                                            <td style='border: 1px solid #ccc;'><input type='text' class='form-control txt' data-provide='datepicker' maxlength=10  onkeypress='return AllowNumeric1(event)'   name='txd_A_empidcanceldate' id='txd_A_empidcanceldate'   value='$empidcanceldate' placeholder='dd-mm-yyyy' ></td>
                                                            <td class='dvtCellLabel' style='border: 1px solid #ccc;'>Exit Date:</td>
                                                            <td style='border: 1px solid #ccc;'><input type='text' class='form-control txt' data-provide='datepicker' maxlength=10  onkeypress='return AllowNumeric1(event)'   name='txd_A_empexitdate' id='txd_A_empexitdate'   value='$empexitdate' placeholder='dd-mm-yyyy' >
                                                           </tr>
                                                           <tr>
                                                              <td class='dvtCellLabel' style='border: 1px solid #ccc;'>Probation End Date :</td>
                                                              <td style='border: 1px solid #ccc;'><input type='text' class='form-control txt' data-provide='datepicker' maxlength=10 disabled onkeypress='return AllowNumeric1(event)'   name='txd_A_probationenddate' id='txd_A_probationenddate'   value='$probationenddate'></td>
                                                              <td class='dvtCellLabel' style='border: 1px solid #ccc;'>Confirmation Date:</td>
                                                              <td style='border: 1px solid #ccc;'><input type='text' class='form-control txt' data-provide='datepicker' maxlength=10  onkeypress='return AllowNumeric1(event)'   name='txd_A_empconfirmation' id='txd_A_empconfirmation'   value='$empconfirmation' placeholder='dd-mm-yyyy' ></td>
                                                              <td class='dvtCellLabel' style='border: 1px solid #ccc;'>Employee Status : <span class='mandatory'>&nbsp;*</span></td>
                                                              <td style='border: 1px solid #ccc;'>".GetEmpStatus($empstatus)."</td>
                                                           </tr>
                                                           <!-- <tr>
                                                              <td class='dvtCellLabel' style='border: 1px solid #ccc;'>Roles:</td>
                                                              <td style='border: 1px solid #ccc;' colspan='5'>".GetRoles($role)."</td>

                                                            </tr> -->
                                                                    <tr>
                                                              <td class='dvtCellLabel' style='border: 1px solid #ccc;'>Reporting Officer:</td>
                                                              <td style='border: 1px solid #ccc;'>".GetReportingofficer($empreportingofficer,$mode)."</td>
                                                              <td class='dvtCellLabel' style='border: 1px solid #ccc;'>Passport No:</td>
                                                              <td style='border: 1px solid #ccc;'><input type='text' class='form-control txt'  name='txt_A_emppassportno' id='txt_A_emppassportno'  value='$emppassportno'></td>
                                                              <td class='dvtCellLabel' style='border: 1px solid #ccc;'>Passport With :</td>
                                                              <td style='border: 1px solid #ccc;'>".GetPassportwith($passportwith)."</td>
                                                           </tr>
                                                           <tr>
                                                              <td class='dvtCellLabel' style='border: 1px solid #ccc;'>Local Address:</td>
                                                              <td style='border: 1px solid #ccc;' colspan='5'><input type='text'  class='form-control txt'  id='txt_A_emplocaladdress' name='txt_A_emplocaladdress'    value='$emplocaladdress'></td>
                                                           </tr>
                                                           <tr>
                                                              <td class='dvtCellLabel' style='border: 1px solid #ccc;'>Permanent Address:</td>
                                                              <td style='border: 1px solid #ccc;' colspan='5'><input type='text'  class='form-control txt'  id='txt_A_emppermenantaddress' name='txt_A_emppermenantaddress'    value='$emppermenantaddress'></td>

                                                            </tr>
                                                            <tr>
                                                              <td class='dvtCellLabel' style='border: 1px solid #ccc;'>Local Telephone:</td>
                                                              <td style='border: 1px solid #ccc;'><input type='text' class='form-control txt' onkeypress='return AllowNumeric1(event);'  name='txt_A_emplocaltel' id='txt_A_emplocaltel'  value='$emplocaltel' ></td>
                                                              <td class='dvtCellLabel' style='border: 1px solid #ccc;'>Local Mobile:</td>
                                                              <td style='border: 1px solid #ccc;'><input type='text' class='form-control txt' onkeypress='return AllowNumeric1(event);'  name='txt_A_emplocalmobile' id='txt_A_emplocalmobile'  value='$emplocalmobile' ></td>
                                                              <td class='dvtCellLabel' style='border: 1px solid #ccc;'>Local Alternative No:</td>
                                                              <td style='border: 1px solid #ccc;'><input type='text' class='form-control txt' onkeypress='return AllowNumeric1(event);'  name='txt_A_empalternativeno' id='txt_A_empalternativeno'  value='$empalternativeno' ></td>

                                                            </tr>
                                                            <tr>
                                                              <td class='dvtCellLabel' style='border: 1px solid #ccc;'>Home Country Tel :</td>
                                                              <td style='border: 1px solid #ccc;'><input type='text'  class='form-control txt' onkeypress='return AllowNumeric1(event);'  name='txt_A_emphomecountrytel' id='txt_A_emphomecountrytel'  value='$emphomecountrytel' ></td>
                                                              <td class='dvtCellLabel' style='border: 1px solid #ccc;'>Home Country Mobile:</td>
                                                              <td style='border: 1px solid #ccc;'><input type='text'  class='form-control txt'  onkeypress='return AllowNumeric1(event);'  name='txt_A_emhomecountrymobile' id='txt_A_emhomecountrymobile'  value='$emhomecountrymobile'></td>
                                                              <td class='dvtCellLabel' style='border: 1px solid #ccc;'>Work Mobile :</td>
                                                              <td style='border: 1px solid #ccc;'><input type='text' class='form-control txt'  onkeypress='return AllowNumeric1(event)' name='txt_A_empworkmobile' id='txt_A_empworkmobile'  value='$empworkmobile'></td>

                                                             </tr>

                                                            <tr>
                                                               <td class='dvtCellLabel' style='border: 1px solid #ccc;'>Work Location :</td>
                                                              <td style='border: 1px solid #ccc;' id=getcenter name=getcenter>".GetWorkLocation($empcompany,$empworklocation)."</td>
                                                              <td class='dvtCellLabel' style='border: 1px solid #ccc;'>Work Telephone :</td>
                                                              <td style='border: 1px solid #ccc;'><input type='text' class='form-control txt'  onkeypress='return AllowNumeric1(event)' name='txt_A_empworktel' id='txt_A_empworktel'  value='$empworktel'></td>
                                                              <td class='dvtCellLabel' style='border: 1px solid #ccc;'>Work Email:</td>
                                                              <td style='border: 1px solid #ccc;'><input type='text' class='form-control txt'  name='txt_A_empworkemail' id='txt_A_empworkemail'  value='$empworkemail'></td>

                                                           </tr>
                                                            <tr>


                                                              <td class='dvtCellLabel' style='border: 1px solid #ccc;'>Personal Email:</td>
                                                              <td style='border: 1px solid #ccc;'><input type='text'  class='form-control txt'   name='txt_A_emppersonalemail' id='txt_A_emppersonalemail'  value='$emppersonalemail'></td>

                                                              <td class='dvtCellLabel' style='border: 1px solid #ccc;'>Education:</td>
                                                              <td style='border: 1px solid #ccc;' colspan='2'>".GetEducation($empeducation)."</td>
                                                            </tr>
                                                            <tr>
                                                              <td class='dvtCellLabel' style='border: 1px solid #ccc;'>Recruitment Source :</td>
                                                              <td style='border: 1px solid #ccc;'>".GetRecruitmentSource($emprecruitementsource)."</td>
                                                              <td class='dvtCellLabel' style='border: 1px solid #ccc;'>Recruitment Through:</td>
                                                              <td style='border: 1px solid #ccc;'><input type='text' class='form-control txt'  name='txt_A_emprecruitedthrough' id='txt_A_emprecruitedthrough'  value='$emprecruitedthrough'></td>
                                                              <td class='dvtCellLabel' style='border: 1px solid #ccc;'>Visa Type:</td>
                                                              <td colspan='1' style='border: 1px solid #ccc;'>".GetVisatype($empvisatype)."</td>
                                                            </tr>
                                                            <tr>


                                                              <td class='dvtCellLabel' style='border: 1px solid #ccc;'>Skills</td>
                                                              <td colspan='1'style='border: 1px solid #ccc;'> ".GetSkills($empskills)." </td>
                                                              <td class='dvtCellLabel' style='border: 1px solid #ccc;'>Sales :</td>
                                                              <td style='border: 1px solid #ccc;'>".GetSalesStatus($sales)."</td>
                                                              <td class='dvtCellLabel' style='border: 1px solid #ccc;'>Punching :</td>
                                                              <td style='border: 1px solid #ccc;'>".GetAssignTask($shareto)."</td>
                                                            </tr>
                                                            <tr>
                                                               <td class='dvtCellLabel' style='border: 1px solid #ccc;'>Accomodation By :</td>
                                                               <td style='border: 1px solid #ccc;'>".GetAccBy($empaccommodationby)."</td>
                                                               <td class='dvtCellLabel' style='border: 1px solid #ccc;'>Accomodation:</td>
                                                               <td style='border: 1px solid #ccc;' id=getacc name=getacc>".GetAccType($empaccommodationby,$empaccommodation)."</td>
                                                               <td class='dvtCellLabel' style='border: 1px solid #ccc;'>Room No:</td>
                                                               <td style='border: 1px solid #ccc;'><input type='text' class='form-control txt'  name='txt_A_emproomnumber' id='txt_A_emproomnumber'  value='$emproomnumber'></td>

                                                            </tr>
                                                            </tr>
                                                            	<td class='dvtCellLabel' style='border: 1px solid #ccc;'>Service Technician :</td>
                                                               <td style='border: 1px solid #ccc;'>".GetServiceTech($servicetechnician)."</td>
                                                            <tr>
                                                            <tr style='display:none;'>
                                                               <input type='hidden' name='txt_A_empfrom' class=textboxcombo id='txt_A_empfrom' value='Own'>
                                                               <input type='hidden' name='mode' class=textboxcombo id='mode' value='$mode'>
                                                               <input type='hidden' name='modeid' class=textboxcombo id='modeid' value='save'>
                                                               <input type='hidden' name='saveid' class=textboxcombo id='saveid' value='$saveid'>
                                                                <input type='hidden' name='searchvalue' class=textboxcombo id='searchvalue' value='".$_SESSION['txtsearch']."'>
                                                                <input type='hidden' name='recordperpage' class=textboxcombo id='recordperpage' value='".$_SESSION['frmPage_rowcount']."'>
                                                                <input type='hidden' name='recordstartrow' class=textboxcombo id='recordstartrow' value='".$_SESSION['frmPage_startrow']."'>
                                                           </tr>
                                                     </table>
                                             </div>
                                             </div>

                                        <div class='box-footer' style='border-top:1px #D2D2D2 solid;'>
                                        <button class='btn btn-success inputs' style='margin-top:-5px;' name='btnsuccess' type='button'  onclick ='javascript:editingrecord(\"save\");'>Save </font>&nbsp;<i class='fa fa-save' aria-hidden='true'></i></button>";
                      if($_REQUEST['ID']=="0") {
                        $entrydata .= "&nbsp;<button class='btn btn-info inputs' style='margin-top:-5px;' name='btninfo' type='button' onclick ='javascript:editingrecord(\"savenew\");'>Save & New &nbsp; <i class='fa fa-save' aria-hidden='true'></i>&nbsp;&nbsp;<i class='fa fa-plus' aria-hidden='true'></i></button>";
                      }
                      if(stripos(json_encode($_SESSION['role']),'HR MANAGER') == true) {
                        $entrydata .= "&nbsp;<button class='btn btn-primary inputs' style='margin-top:-5px;float:right;'  id='btninfo' name='btninfo' type='button' onclick ='javascript:resetpassword(\"".$empid."\");'>Reset Password &nbsp;<i class='fa fa-refresh' aria-hidden='true'></i></button>";
                      }
                        $entrydata .= "&nbsp;<button class='btn btn-warning inputs' style='margin-top:-5px;' name='btnwarning' type='button'  onclick ='javascript:editingrecord(\"saveclose\");'>Save & Close &nbsp;<i class='fa fa-save' aria-hidden='true'></i>&nbsp;&nbsp;<i class='fa fa-close' aria-hidden='true'></i></button>
                                        <button class='btn btn-danger inputs' style='margin-top:-5px;' name='btndanger' type='button'  onclick ='javascript:cancleediting(\"employeemaster.php\");'>Close &nbsp;<i class='fa fa-close' aria-hidden='true'></i></button>

                                        </div>
                                        </form> ";

          echo  $entrydata;
 //echo $empid;
?>

                           </div>
                           <div class="tab-pane" id="usermodule">
                              <iframe id="frame3" name="frame3" scrolling="no" onload='this.width=screen.width;this.height=screen.height;'  frameborder="0" style="position: relative; width: 100%; "></iframe>
                          </div>
                           
                          <!-- <div class="tab-pane" id="kithkin">
                              <iframe id="frame3" name="frame3" scrolling="no" onload='this.width=screen.width;this.height=screen.height;'  frameborder="0" style="position: relative; width: 100%; "></iframe>

                          </div>

                          <div class="tab-pane" id="experience">
                            <iframe id="frame4" name="frame4" scrolling="no"   onload='this.width=screen.width;this.height=screen.height;'  frameborder="0" style="position: relative; width: 100%;"></iframe>
                          </div>

                          <div class="tab-pane" id="salary">
                            <iframe id="frame5" name="frame5" scrolling="no"   onload='this.width=screen.width;this.height=screen.height;'  frameborder="0" style="position: relative; width: 100%;"></iframe>
                          </div>

                          <div class="tab-pane" id="benefits">
                            <iframe id="frame6" name="frame6" scrolling="no"   onload='this.width=screen.width;this.height=screen.height;'  frameborder="0" style="position: relative; width: 100%;"></iframe>
                          </div>

                          <div class="tab-pane" id="postingpromotion">
                            <iframe id="frame7" name="frame7" scrolling="no"   onload='this.width=screen.width;this.height=screen.height;'  frameborder="0" style="position: relative; width: 100%;"></iframe>
                          </div>

                          <div class="tab-pane" id="documents">
                            <iframe id="frame8" name="frame8" scrolling="no"   onload='this.width=screen.width;this.height=screen.height;'  frameborder="0" style="position: relative; width: 100%;"></iframe>
                          </div>

                          <div class="tab-pane" id="gadgets">
                            <iframe id="frame9" name="frame9" scrolling="no"   onload='this.width=screen.width;this.height=screen.height;'  frameborder="0" style="position: relative; width: 100%;"></iframe>
                          </div>

                          <div class="tab-pane" id="leavepackage">
                            <iframe id="frame10" name="frame10" scrolling="no"   onload='this.width=screen.width;this.height=screen.height;'  frameborder="0" style="position: relative; width: 100%;"></iframe>
                          </div>

                          <div class="tab-pane" id="leave">
                            <iframe id="frame11" name="frame11" scrolling="no"   onload='this.width=screen.width;this.height=screen.height;'  frameborder="0" style="position: relative; width: 100%;"></iframe>
                          </div>
                          <div class="tab-pane" id="addded">
                            <iframe id="frame12" name="frame12" scrolling="no"   onload='this.width=screen.width;this.height=screen.height;'  frameborder="0" style="position: relative; width: 100%;"></iframe>
                          </div>  -->
                       </div>
                  </div>

        </section>
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

                    var frameheight = height - 110;
                    $('#frame3').height(frameheight);

                    boxheight = boxheight-24;
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
       document.frmEdit.action='editemployeemaster.php?dr=edit&ID='+document.getElementById('mode').value;
       document.frmEdit.submit();
   }
   if(i==3){
   var frame= document.getElementById('frame3');
   frame.src='emp_modules.php?PARENTID=<?php echo $empid; ?>';
   frame.load();
   }
   /*if(i==3){
   var frame= document.getElementById('frame3');
   frame.src='emp_kithkins.php?ID=<?echo $empid; ?>';
   frame.load();
   }
   if(i==4){
   var frame= document.getElementById('frame4');
   frame.src='emp_experience.php?ID=<?echo $empid; ?>';
   frame.load();
   }
   if(i==5){
   var frame= document.getElementById('frame5');
   frame.src='emp_salary.php?ID=<?echo $empid; ?>';
   frame.load();
   }
   if(i==6){
   var frame= document.getElementById('frame6');
   frame.src='emp_benefits.php?ID=<?echo $empid; ?>';
   frame.load();
   }
   if(i==7){
   var frame= document.getElementById('frame7');
   frame.src='emp_postingpromotion.php?ID=<?echo $empid; ?>';
   frame.load();
   }
   if(i==8){
   var frame= document.getElementById('frame8');
   frame.src='emp_documents.php?entitytype=Employee Documents&ID=<?echo $empid; ?>';
   frame.load();
   }
   if(i==9){
   var frame= document.getElementById('frame9');
   frame.src='emp_gadgets.php?ID=<?echo $empid; ?>';
   frame.load();
   }
   if(i==10){
   var frame= document.getElementById('frame10');
   frame.src='emp_leavepackage.php?ID=<?echo $empid; ?>';
   frame.load();
   }
   if(i==11){
   var frame= document.getElementById('frame11');
   frame.src='emp_leave.php?packageid=<?echo $empleavepackage; ?>&ID=<?echo $empid; ?>';
   frame.load();
   }
   if(i==12){
   var frame= document.getElementById('frame12');
   frame.src='staffadvance.php?ID=<?echo $empid; ?>';
   frame.load();
   } */
}

</script>
<?php
function GetPassportwith($passportwith){
         global $con;
         $CMB = "<select name='cmb_A_passportwith' id='cmb_A_passportwith' class='form-control select'>";
         $CMB .= "<option value=''></option>";
         $SEL =  "select lookcode,lookname from in_lookup where looktype='PASSPORT WITH' and lookname<>'XX' order by lookname";
         $RES = mysqli_query($con,$SEL);
         while ($ARR = mysqli_fetch_array($RES)) {
                $SEL = "";
                if(strtoupper($passportwith) == strtoupper($ARR['lookcode'])){ $SEL =  "SELECTED";}
                $CMB .= "<option value='".$ARR['lookcode']."' $SEL >".$ARR['lookname']."</option>";
         }
         $CMB .= "</select>";
         return $CMB;
}
function GetVisatype($empstatus){
         global $con;
         $CMB = "<select name='cmb_A_empvisatype' id='cmb_A_empvisatype' class='form-control select'>";
         $CMB .= "<option value=''></option>";
         $SEL =  "select lookcode,lookname from in_lookup where looktype='VISA TYPE' and lookname<>'XX' order by lookname";
         $RES = mysqli_query($con,$SEL);
         while ($ARR = mysqli_fetch_array($RES)) {
                $SEL = "";
                if(strtoupper($empstatus) == strtoupper($ARR['lookcode'])){ $SEL =  "SELECTED";}
                $CMB .= "<option value='".$ARR['lookcode']."' $SEL >".$ARR['lookname']."</option>";
         }
         $CMB .= "</select>";
         return $CMB;
}
function GetRoles($roles) {
          global $con;

         $slno=1;
        // $notinarray = "'EMPLOYEE','REPORTING OFFICER','PAYROLL MANAGER','ACCOUNTANT','FINANCE MANAGER','PURCHASE MANAGER','SALES MANAGER','STORE MANAGER','SERVICE INCHARGE','SUPERVISOR INCHARGE','HR CLERK','FRONT OFFICE CLERK','SALES COORDINATOR','GENERAL MANAGER','SERVICE TECHNICIAN'";
         $notinarray = "'EMPLOYEE','REPORTING OFFICER','PAYROLL MANAGER','ACCOUNTANT','FINANCE MANAGER','PURCHASE MANAGER','PURCHASE COORDINATOR','SALES MANAGER','STORE MANAGER','SITE INCHARGE','SERVICE COORDINATOR','SUPERVISOR','HR CLERK','FRONT OFFICE CLERK','SALES COORDINATOR','SALES PERSON','GENERAL MANAGER','SERVICE TECHNICIAN','STORE KEEPER','OPERATIONS MANAGER','HR MANAGER','FACILITY MANAGER'";
         $mycontrol = "<div class='divcheckbox'>";
                                      if($_SESSION['role']=="SUPER"){
                                      $SQLsub1   = "select lookcode,lookname from in_lookup_head where looktype='ROLES' and lookname<>'YY' and lookname='ADMIN' order by id";
                                      }else{
                                      $SQLsub1   = "select lookcode,lookname from in_lookup_head where looktype='ROLES' and lookname<>'YY' and lookname  in ($notinarray) order by lookname";
                                      }
                                      //echo $SQLsub1;
                                      $SQLResSub1 =  mysqli_query($con,$SQLsub1) or die(mysqli_error()."<br>".$SQLsub1);

                                      if(mysqli_num_rows($SQLResSub1)>=1){
                                        while($loginResultArraySub1   = mysqli_fetch_array($SQLResSub1)){
                                           $selected = "";
                                           $sfont = "";
                                           $cfont = "";
                                           if (strpos("-," . $roles.",",",".$loginResultArraySub1[0].",")>0) {     //  Setting the selected item if this matches with the value parameter
                                               $selected = " checked ";
                                               $sfont = "<font color='red'>";
                                               $cfont = "</font>";

                                           }
                                           if($slno==7 || $slno==13 || $slno==19 || $slno==25 || $slno==30 || $slno==49 || $slno==58 || $slno==66){
                                              $mycontrol .= "<br>";
                                           }
                                           if($loginResultArraySub1[0]=="EMPLOYEE"){
                                             $rolename="SELF SERVICE";
                                           }else{
                                             $disable="";
                                             $rolename=$loginResultArraySub1[0];
                                           }

                                           $mycontrol .= "<input type='checkbox' class='minimal inputs' id='chk_A_rolecode' $disable name='chk_A_rolecode[]' value='".$loginResultArraySub1[0]."'".$selected."/>&nbsp;$sfont" . $rolename. "$cfont&nbsp;&nbsp;&nbsp;";
                                           ++$slno;
                                        }

                                      }
           $mycontrol .= "</div>";

           return $mycontrol;
}
function GetRoles_($roles) {
//echo $roles."tyty";
         global $con;
         $CMB = "<select id='ckk_A_rolecode' name='ckk_A_rolecode[]' class='select2' multiple='multiple'>";
         if($_SESSION['role']=="SUPER"){
         $SEL   = "select lookcode,lookname from in_lookup_head where looktype='ROLES' and lookname<>'YY' and lookname='ADMIN' order by id";
         }else{
         $SEL   = "select lookcode,lookname from in_lookup_head where looktype='ROLES' and lookname<>'YY' and lookname not in ('ADMIN','SUPER') order by id";
         }
         $RES = mysqli_query($SEL);
         while ($ARR = mysqli_fetch_array($RES)) {
            $SEL = "";
            $selected = "";
            if (strpos("-," . $roles.",",",".$ARR[0].",")>0) {     //  Setting the selected item if this matches with the value parameter
                $selected = " selected ";
            }
           if($ARR['lookcode']=="EMPLOYEE"){
              $CMB .= "<option value='".$ARR['lookcode']."'   selected>".$ARR['lookname']."</option>";
           }else{
              $CMB .= "<option value='".$ARR['lookcode']."'  $selected>".$ARR['lookname']."</option>";
           }
         }
                      $CMB .= " </select> ";
        return $CMB;
}
 function GetGrapthXMLForLeaves($id) {
                 global $con;
                  $strXML  = "<graph bgColor ='#EAEAEA' yAxisName='Leaves' > ";
                  $SQL = " select count(*) as count,leavecategory from e_leave where staffid='$id' group by leavecategory order by count desc";
                  $colors    = array("008ED6","8E468E","588526","B3AA00","9D080D","A186BE","AFD8F8","F6BD0F","8BBA00","FF8E46","008E8E","D64646");
                  $SQLResult = mysqli_query($SQL) or die (mysqli_error().$SQL);
                  $i =0;
                  if(mysqli_num_rows($SQLResult)>0){
                     while($SQLArray = mysqli_fetch_array($SQLResult)){

                          $val = $SQLArray['count'];
                          $dis = $colors[$i];
                          $strXML  .= "<set name='".$SQLArray['leavecategory']."' value='$val' color='$dis' />";
                          ++$i;
                     }
                  }
                   $strXML  .= "</graph>";
                   return $strXML;


 }
 function GetGrapthXMLForSalary($id) {
 global $con;
                  $strXML  = "<graph bgColor ='#EAEAEA' yAxisName='Salary'> ";
                  $j=0;
                  for($i=2016;$i<=2020;$i++){

                  $SQL =  "select sum(e_payscaleitem.amount) as amount from e_salary,e_payscaleitem where e_salary.scaleid=e_payscaleitem.scaleid
                          and e_payscaleitem.category='MONTHLY PAY ELEMENT' and  e_salary.staffid='$id' and fromyear='$i'";
                  $colors    = array("AFD8F8","F6BD0F","8BBA00","FF8E46","008E8E","D64646","8E468E","588526","B3AA00","008ED6","9D080D","A186BE");
                  $SQLResult = mysqli_query($SQL) or die (mysqli_error().$SQL);
                  $SQLArray = mysqli_fetch_array($SQLResult);

                          $val = $SQLArray['amount'];
                          $dis = $colors[$j];
                          $strXML  .= "<set name='".$i."' value='$val' color='$dis' />";
                          ++$j;

                  }
                   $strXML  .= "</graph>";
                   return $strXML;


}

function GetAssignTask($assigntask){
         global $con;
         $CMB = "<select name='cmb_A_shareto' id='cmb_A_shareto' class='form-control select'>";
         $SEL =  "select lookcode,lookname from in_lookup_head where looktype='YESNO' and lookname<>'YY' order by lookname desc";
         $RES = mysqli_query($con,$SEL);
         while ($ARR = mysqli_fetch_array($RES)) {
                $SEL = "";
                if(strtoupper($assigntask) == strtoupper($ARR['lookcode'])){ $SEL =  "SELECTED";}
                $CMB .= "<option value='".$ARR['lookcode']."' $SEL >".$ARR['lookname']."</option>";
         }
         $CMB .= "</select>";
         return $CMB;
}
function GetSalesStatus($sales){
         global $con;
         $CMB = "<select name='cmb_A_sales' id='cmb_A_sales' class='form-control select'>";
         $SEL =  "select lookcode,lookname from in_lookup_head where looktype='YESNO' and lookname<>'YY' order by lookname";
         $RES = mysqli_query($con,$SEL);
         while ($ARR = mysqli_fetch_array($RES)) {
                $SEL = "";
                if(strtoupper($sales) == strtoupper($ARR['lookcode'])){ $SEL =  "SELECTED";}
                $CMB .= "<option value='".$ARR['lookcode']."' $SEL >".$ARR['lookname']."</option>";
         }
         $CMB .= "</select>";
         return $CMB;
}
function GetGender($empstatus){
          global $con;
         $CMB = "<select name='cmb_A_empgender' id='cmb_A_empgender' class='form-control select'>";
         $CMB .= "<option value=''></option>";
         $SEL =  "select lookcode,lookname from in_lookup where looktype='GENDER' and lookname<>'XX' order by id";
         $RES = mysqli_query($con,$SEL);
         while ($ARR = mysqli_fetch_array($RES)) {
                $SEL = "";
                if(strtoupper($empstatus) == strtoupper($ARR['lookcode'])){ $SEL =  "SELECTED";}
                $CMB .= "<option value='".$ARR['lookcode']."' $SEL >".$ARR['lookname']."</option>";
         }
         $CMB .= "</select>";
         return $CMB;
}
function GetReportingofficer($empstatus,$mode){
         global $con;
         $CMB = "<select name='cmb_A_empreportingofficer' id='cmb_A_empreportingofficer' class='form-control select2'>";
         $CMB .= "<option value=''></option>";


                     $SEL1 =  "select empid,concat(empfirstename,' ',emplastename) as name from in_personalinfo where  rolecode like '%REPORTING OFFICER%' order by empfirstename";
                     $RES1 = mysqli_query($con,$SEL1);
                     while ($ARR1 = mysqli_fetch_array($RES1)) {
                            $SEL = "";
                            if(strtoupper($empstatus) == strtoupper($ARR1['empid'])){ $SEL =  "SELECTED";}
                            $CMB .= "<option value='".$ARR1['empid']."' $SEL >".$ARR1['empid']." - ".$ARR1['name']."</option>";
                     }

         $CMB .= "</select>";
         return $CMB;
}
function GetNationality($empstatus){
         global $con;
         $CMB = "<select name='cmb_A_empnationality' id='cmb_A_empnationality' class='form-control select'>";
         $CMB .= "<option value=''></option>";
         $SEL =  "select nationality from tbl_country order by nationality";
         $RES = mysqli_query($con,$SEL);
         while ($ARR = mysqli_fetch_array($RES)) {
                $SEL = "";
                if(strtoupper($empstatus) == strtoupper($ARR['nationality'])){ $SEL =  "SELECTED";}
                $CMB .= "<option value='".$ARR['nationality']."' $SEL >".$ARR['nationality']."</option>";
         }
         $CMB .= "</select>";
         return $CMB;
}
function GetRelegion($empstatus){
         global $con;
         $CMB = "<select name='cmb_A_emprelegion' id='cmb_A_emprelegion' class='form-control select'>";
         $CMB .= "<option value=''></option>";
         $SEL =  "select lookcode,lookname from in_lookup where looktype='RELIGION' and lookname<>'XX' order by lookname";
         $RES = mysqli_query($SEL);
         while ($ARR = mysqli_fetch_array($RES)) {
                $SEL = "";
                if(strtoupper($empstatus) == strtoupper($ARR['lookcode'])){ $SEL =  "SELECTED";}
                $CMB .= "<option value='".$ARR['lookcode']."' $SEL >".$ARR['lookname']."</option>";
         }
         $CMB .= "</select>";
         return $CMB;
}
function GetMaritalstatus($empstatus){
         global $con;
         $CMB = "<select name='cmb_A_empmaritalstatus' id='cmb_A_empmaritalstatus' class='form-control select'>";
         $CMB .= "<option value=''></option>";
         $SEL =  "select lookcode,lookname from in_lookup where looktype='MARITAL STATUS' and lookname<>'XX' order by lookname";
         $RES = mysqli_query($con,$SEL);
         while ($ARR = mysqli_fetch_array($RES)) {
                $SEL = "";
                if(strtoupper($empstatus) == strtoupper($ARR['lookcode'])){ $SEL =  "SELECTED";}
                $CMB .= "<option value='".$ARR['lookcode']."' $SEL >".$ARR['lookname']."</option>";
         }
         $CMB .= "</select>";
         return $CMB;
}
function GetLeavePackage($package){
          global $con;
         $CMB = "<select name='cmb_A_empleavepackage'  id='cmb_A_empleavepackage' class='form-control select'>";
         $CMB .= "<option value=''></option>";
         $SEL =  "select id,leavepackage from in_staffleavescale order by leavepackage";
         $RES = mysqli_query($SEL);
         while ($ARR = mysqli_fetch_array($RES)) {
                $SEL = "";
                if(strtoupper($package) == strtoupper($ARR['id'])){ $SEL =  "SELECTED";}
                $CMB .= "<option value='".$ARR['id']."' $SEL >".$ARR['leavepackage']."</option>";
         }
         $CMB .= "</select>";
         return $CMB;
}
function GetEmpStatus($empstatus){
         global $con;
         $CMB = "<select name='cmb_A_empstatus'  id='cmb_A_empstatus' onchange='displayrejoin();' class='form-control select'>";
        // $CMB .= "<option value=''></option>";
         $SEL =  "select lookcode,lookname from in_lookup where looktype='EMPLOYEE STATUS' and lookname<>'XX' order by id";
         $RES = mysqli_query($con,$SEL);
         while ($ARR = mysqli_fetch_array($RES)) {
                $SEL = "";
                if(strtoupper($empstatus) == strtoupper($ARR['lookcode'])){ $SEL =  "SELECTED";}
                $CMB .= "<option value='".$ARR['lookcode']."' $SEL >".$ARR['lookname']."</option>";
         }
         $CMB .= "</select>";
         return $CMB;
}
function GetWorkLocation($empcompany,$empworklocation){
         global $con;
         $CMB = "<select name='cmb_A_empworklocation' id='cmb_A_empworklocation' class='form-control select2'>";
         $CMB .= "<option value=''></option>";
         $SEL =  "select projectcode,projectname from in_project  order by projectname";
         $RES = mysqli_query($con,$SEL);
         while ($ARR = mysqli_fetch_array($RES)) {
                $SEL = "";
                if(strtoupper($empworklocation) == strtoupper($ARR['projectcode'])){ $SEL =  "SELECTED";}
                $CMB .= "<option value='".$ARR['projectcode']."' $SEL >".$ARR['projectname']."</option>";
         }
         $CMB .= "</select>";
         return $CMB;
}
function GetRecruitmentSource($emprecruitementsource){
         global $con;
         $CMB = "<select name='cmb_A_emprecruitementsource' id='cmb_A_emprecruitementsource' class='form-control select'>";
         $CMB .= "<option value=''></option>";
         $SEL =  "select lookcode,lookname from in_lookup where looktype='RECRUITMENT SOURCE' and lookname<>'XX' order by lookname";
         $RES = mysqli_query($con,$SEL);
         while ($ARR = mysqli_fetch_array($RES)) {
                $SEL = "";
                if(strtoupper($emprecruitementsource) == strtoupper($ARR['lookcode'])){ $SEL =  "SELECTED";}
                $CMB .= "<option value='".$ARR['lookcode']."' $SEL >".$ARR['lookname']."</option>";
         }
         $CMB .= "</select>";
         return $CMB;
}
function GetAccBy($empaccommodationby){
         global $con;
         $CMB = "<select name='cmb_A_empaccommodationby' id='cmb_A_empaccommodationby' class='form-control select' onChange='getAccomodation(this.value)'>";
         $CMB .= "<option value=''></option>";
         $SEL =  "select lookcode,lookname from in_lookup where looktype='ACCOMODATION BY' and lookname<>'XX' order by lookname";
         $RES = mysqli_query($con,$SEL);
         while ($ARR = mysqli_fetch_array($RES)) {
                $SEL = "";
                if(strtoupper($empaccommodationby) == strtoupper($ARR['lookcode'])){ $SEL =  "SELECTED";}
                $CMB .= "<option value='".$ARR['lookcode']."' $SEL >".$ARR['lookname']."</option>";
         }
         $CMB .= "</select>";
         return $CMB;
}
function GetAccType($empaccommodationby,$empaccommodation){
         global $con;
         $CMB = "<select name='cmb_A_empaccommodation' id='cmb_A_empaccommodation' class='form-control select'>";
         $CMB .= "<option value=''></option>";
         if($empaccommodationby=="22002"){
          $SEL =  "select id,accomodationname,accomodationtype from in_accomodationdetails where status='Active' order by accomodationname";
         }else{
          $SEL =  "select id,accomodationname,accomodationtype from in_accomodationdetails where status='XXXX' order by accomodationname";
         }

         $RES = mysqli_query($con,$SEL);
         while ($ARR = mysqli_fetch_array($RES)) {
                $SEL = "";
                if(strtoupper($empaccommodation) == strtoupper($ARR['id'])){ $SEL =  "SELECTED";}
                $CMB .= "<option value='".$ARR['id']."' $SEL >".$ARR['accomodationname']." - ".getlookname($ARR['accomodationtype'])."</option>";
         }
         $CMB .= "</select>";
         return $CMB;
}
function getlookname($id){
          global $con;
         $SEL =  "select lookname from in_lookup where lookcode='$id'";
         $RES = mysqli_query($con,$SEL);
         while ($ARR = mysqli_fetch_array($RES)) {
                $CMB = $ARR['lookname'];
         }
         return $CMB;
}

function GetEmpGrade($empgrade){
         global $con;
         $CMB = "<select name='cmb_A_empgrade' id='cmb_A_empgrade' class='form-control select'>";
         $CMB .= "<option value=''></option>";
         $SEL =  "select lookcode,lookname from in_lookup where looktype='EMPLOYEE GRADE' and lookname<>'XX' order by lookname";
         $RES = mysqli_query($con,$SEL);
         while ($ARR = mysqli_fetch_array($RES)) {
                $SEL = "";
                if(strtoupper($empgrade) == strtoupper($ARR['lookcode'])){ $SEL =  "SELECTED";}
                $CMB .= "<option value='".$ARR['lookcode']."' $SEL >".$ARR['lookname']."</option>";
         }
         $CMB .= "</select>";
         return $CMB;
}
function GetEmpLabourDesignation($emplabourdesignation){
         global $con;
         $CMB = "<select name='cmb_A_emplabourdesignation' id='cmb_A_emplabourdesignation' class='form-control select'>";
         $CMB .= "<option value=''></option>";
         $SEL =  "select id,designationname from tbl_labourdesignation order by designationname";
         $RES = mysqli_query($con,$SEL);
         while ($ARR = mysqli_fetch_array($RES)) {
                $SEL = "";
                if(strtoupper($emplabourdesignation) == strtoupper($ARR['id'])){ $SEL =  "SELECTED";}
                $CMB .= "<option value='".$ARR['id']."' $SEL >".$ARR['designationname']."</option>";
         }
         $CMB .= "</select>";
         return $CMB;
}
function GetDepartment($empdepartment){
         global $con;
         $CMB = "<select name='cmb_A_empdepartment' id='cmb_A_empdepartment' class='form-control select'>";
         $CMB .= "<option value=''></option>";
         $SEL =  "select id,departmentname from tbl_department order by departmentname";
         $RES = mysqli_query($con,$SEL);
         while ($ARR = mysqli_fetch_array($RES)) {
                $SEL = "";
                if(strtoupper($empdepartment) == strtoupper($ARR['id'])){ $SEL =  "SELECTED";}
                $CMB .= "<option value='".$ARR['id']."' $SEL >".$ARR['departmentname']."</option>";
         }
         $CMB .= "</select>";
         return $CMB;
}
function GetSponserCompany($empcompany) {
         global $con;
         $CMB = "<select name='cmb_A_empsponsercompany' class='form-control select'  id='cmb_A_empsponsercompany' >";
         //$CMB .= "<option value=''></option>";
         $SEL =  "select companycode,companyname from tbl_companysetup order by companyname";
         $RES = mysqli_query($con,$SEL);
         while ($ARR = mysqli_fetch_array($RES)) {
                $SEL = "";
                if(strtoupper($empcompany) == strtoupper($ARR['companycode'])){ $SEL =  "SELECTED";}
                $CMB .= "<option value='".$ARR['companycode']."' $SEL >".$ARR['companyname']."</option>";
         }
         $CMB .= "</select>";
         return $CMB;
}
function GetCompany($empstatus){
         global $con;
         $CMB = "<select name='cmb_A_empcompany'class='form-control select' id='cmb_A_empcompany' onChange='getDivision(this.value)'>";
         $CMB .= "<option value=''></option>";
         $SEL =  "select companycode,companyname from tbl_companysetup order by companyname";
         $RES = mysqli_query($con,$SEL);
         while ($ARR = mysqli_fetch_array($RES)) {
                $SEL = "";
                if(strtoupper($empstatus) == strtoupper($ARR['companycode'])){ $SEL =  "SELECTED";}
                $CMB .= "<option value='".$ARR['companycode']."' $SEL >".$ARR['companyname']."</option>";
         }
         $CMB .= "</select>";
         return $CMB;
}

function GetDivision($empcompany,$empdivision){
         global $con;
         //if($empcompany=='')$empcompany="02001";
         $CMB = " <select name='cmb_A_empdivision'  class='form-control select'  id='cmb_A_empdivision'>";
         $CMB .= "<option value=''></option>";
         $SEL =  "select divisionname,id from tbl_division where companycode='".$empcompany."'";
         $RES = mysqli_query($con,$SEL);
         while ($ARR = mysqli_fetch_array($RES)) {
                $SEL = "";
                if(strtoupper($empdivision) == strtoupper($ARR['id'])){ $SEL =  "SELECTED";}
                $CMB .= "<option value='".$ARR['id']."' $SEL >".$ARR['divisionname']."</option>";
         }
         $CMB .= "</select></div>";
         return $CMB;
}
function GetEmptype($emptype){
//echo $status."tyty";
         global $con;
         $CMB = " <select name='cmb_A_emptype' class='form-control select' id='cmb_A_emptype'>";
         $CMB .= "<option value='Select'></option>";
         $SEL =  "select id,employeetypename from tbl_employeetype order by employeetypename";
         $RES = mysqli_query($con,$SEL);
         while ($ARR = mysqli_fetch_array($RES)) {
                $SEL = "";
                if(strtoupper($emptype) == strtoupper($ARR['id'])){ $SEL =  "SELECTED";}
                $CMB .= "<option value='".$ARR['id']."' $SEL >".$ARR['employeetypename']."</option>";
         }
         $CMB .= "</select>";
         return $CMB;
}
function GetEmpCategory($empcategory){
//echo $status."tyty";
         global $con;
         $CMB = " <select name='cmb_A_empcategory'  id='cmb_A_empcategory' class='form-control select'>";
         $CMB .= "<option value='Select'></option>";
         $SEL =  "select id,empcategoryname from tbl_employeecategory order by empcategoryname";
         $RES = mysqli_query($con,$SEL);
         while ($ARR = mysqli_fetch_array($RES)) {
                $SEL = "";
               // echo $relation1. "--". $ARR['lookcode']."<br>";
                if(strtoupper($empcategory) == strtoupper($ARR['id'])){ $SEL =  "SELECTED";}
                $CMB .= "<option value='".$ARR['id']."' $SEL >".$ARR['empcategoryname']."</option>";
         }
         $CMB .= "</select>";
         return $CMB;
}
function GetEmpDesignation($empdesignation) {
//echo $status."tyty";
         global $con;
         $CMB = " <select name='cmb_A_empdesignation' id='cmb_A_empdesignation' class='form-control select'>";
         $CMB .= "<option value=''></option>";
         $SEL =  "select id,designationname from tbl_designation order by designationname";
         $RES = mysqli_query($con,$SEL);
         while ($ARR = mysqli_fetch_array($RES)) {
                $SEL = "";
               // echo $relation1. "--". $ARR['lookcode']."<br>";
                if(strtoupper($empdesignation) == strtoupper($ARR['id'])){ $SEL =  "SELECTED";}
                $CMB .= "<option value='".$ARR['id']."' $SEL >".$ARR['designationname']."</option>";
         }
         $CMB .= "</select>";
         return $CMB;
}
function GetEducation($roles) {
//echo $roles."tyty";
         global $con;
         $CMB = "<select id='ckk_A_empeducation' name='ckk_A_empeducation[]'  class='select2' multiple='multiple' style='width:100%;'>";
         $SEL =  "select id,education from tbl_education order by id";
         $RES = mysqli_query($con,$SEL);
         while ($ARR = mysqli_fetch_array($RES)) {
            $SEL = "";
            $selected = "";
            if (strpos("-," . $roles.",",",".$ARR[0].",")>0) {     //  Setting the selected item if this matches with the value parameter
                $selected = " selected ";
            }
            $CMB .= "<option value='".$ARR['id']."'  $selected>".$ARR['education']."</option>";

         }
                      $CMB .= " </select> ";
        return $CMB;
}
function GetEducation_new($e_country) {
         global $con;
         $slno=1;
         $mycontrol .= "<div class='divcheckbox'>";
                                      $SQLsub1   = "select id,education from tbl_education where order by id";
                                      $SQLResSub1 =  mysqli_query($con,$SQLsub1) or die(mysqli_error()."<br>".$SQLsub1);

                                      if(mysqli_num_rows($SQLResSub1)>=1){
                                        while($loginResultArraySub1   = mysqli_fetch_array($SQLResSub1)){
                                           $selected = "";
                                           if (strpos("-," . $e_country.",",",".$loginResultArraySub1[0].",")>0) {     //  Setting the selected item if this matches with the value parameter
                                               $selected = " checked ";
                                           }
                                           if($slno==9 || $slno==17 || $slno==25 || $slno==33 || $slno==41 || $slno==49 || $slno==58 || $slno==66){
                                              $mycontrol .= "<br>";
                                           }
                                           $mycontrol .= "<input type='checkbox' class='minimal inputs' id='chk_A_empeducation' name='chk_A_empeducation[]' value='".$loginResultArraySub1[0]."'".$selected."/>&nbsp;&nbsp;" . $loginResultArraySub1[1]. "&nbsp;&nbsp;";
                                           ++$slno;
                                        }
                                      }
           $mycontrol .= "</div>";

           return $mycontrol;
}
function GetSkills($roles) {
//echo $roles."tyty";
         global $con;
         $CMB = "<select id='ckk_A_empskills' name='ckk_A_empskills[]' class='select2' multiple='multiple' style='width:100%;-webkit-appearance: none;'>";
         $SEL =  "select id,skillname from tbl_skills order by id";
         $RES = mysqli_query($con,$SEL);
         while ($ARR = mysqli_fetch_array($RES)) {
            $SEL = "";
            $selected = "";
            if (strpos("-," . $roles.",",",".$ARR[0].",")>0) {     //  Setting the selected item if this matches with the value parameter
                $selected = " selected ";
            }
            $CMB .= "<option value='".$ARR['id']."'  $selected>".$ARR['skillname']."</option>";

         }
                      $CMB .= " </select> ";
        return $CMB;
}
function GetSkills_new($roles) {
         global $con;
         $slno=1;
         $mycontrol .= "<div class='divcheckbox'>";
                                      $SQLsub1 =  "select id,skillname from tbl_skills order by id";
                                      $SQLResSub1 =  mysqli_query($con,$SQLsub1) or die(mysqli_error()."<br>".$SQLsub1);

                                      if(mysqli_num_rows($SQLResSub1)>=1){
                                        while($loginResultArraySub1   = mysqli_fetch_array($SQLResSub1)){
                                           $selected = "";
                                           if (strpos("-," . $roles.",",",".$loginResultArraySub1[0].",")>0) {     //  Setting the selected item if this matches with the value parameter
                                               $selected = " checked ";
                                           }
                                           if($slno==9 || $slno==17 || $slno==25 || $slno==33 || $slno==41 || $slno==49 || $slno==58 || $slno==66){
                                              $mycontrol .= "<br>";
                                           }
                                           $mycontrol .= "<input type='checkbox' class='minimal inputs' id='ckk_A_empskills' name='ckk_A_empskills[]' value='".$loginResultArraySub1[0]."'".$selected."/>&nbsp;&nbsp;" . $loginResultArraySub1[1]. "&nbsp;&nbsp;";
                                           ++$slno;
                                        }
                                      }
           $mycontrol .= "</div>";

           return $mycontrol;

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

function GetLastSqeIDrefid($companycode){
                  global $con;
                  $SQL   = "SELECT max(id) as lastnumber  FROM in_personalinfo WHERE empcompany='".$companycode."'";
                  $SQLRes =  mysqli_query($con,$SQL) or die(mysqli_error()."<br>".$SQL);
                  if(mysqli_num_rows($SQLRes)>=1){
                       $loginResultArray   = mysqli_fetch_array($SQLRes);
                       $catgencode =  $loginResultArray['lastnumber']+1 ;
                       $catgencode = str_pad($catgencode, 3, "0", STR_PAD_LEFT);
                  }
                  return $catgencode;
}
function getprefix($company){
            global $con;
           echo $SQL = " Select prefix from tbl_companysetup where companycode ='".$company."'";
            $SQLRes =  mysqli_query($con,$SQL) or die(mysqli_error()."<br>".$SQL);
              if(mysqli_num_rows($SQLRes)>=1){
                while($loginResultArray   = mysqli_fetch_array($SQLRes)){
                  $prefix = $loginResultArray['prefix'];
                }
              }
         return $prefix ;

}
function GetServiceTech($empdesignation) {
         global $con;
         $CMB = " <select name='cmb_A_servicetechnician' id='cmb_A_servicetechnician' class='form-control select'>";
        /// $CMB .= "<option value=''></option>";
         $SEL =  "select lookcode,lookname from in_lookup_head where looktype='YESNO' and lookname<>'YY' order by lookname";
         $RES = mysqli_query($con,$SEL);
         while ($ARR = mysqli_fetch_array($RES)) {
                $SEL = "";
                if(strtoupper($empdesignation) == strtoupper($ARR['lookcode'])){ $SEL =  "SELECTED";}
                $CMB .= "<option value='".$ARR['lookcode']."' $SEL >".$ARR['lookname']."</option>";
         }
         $CMB .= "</select>";
         return $CMB;
}
?>
