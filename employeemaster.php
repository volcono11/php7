<?php
session_start();
if($_SESSION['pr'] == "") {
  $_SESSION['pr'] = $_REQUEST['pr'];
}
require "connection.php";
require "pagingObj.php";
$insert = $update = $delete = "false";
if(false !== strpos($_SESSION['pr'],"I")) $insert = "true" ;
if(false !== strpos($_SESSION['pr'],"U")) $update = "true" ;
if(false !== strpos($_SESSION['pr'],"D")) $delete = "true" ;
$_REQUEST['ps'] = isset($_REQUEST['ps']) ? $_REQUEST['ps'] : '';

if($_REQUEST['ps'] == "1") {
  $_SESSION['lookcode'] = "Select";
  $_SESSION['lookcode1'] = "Select";
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<head>
        <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
        <!-- Font Awesome -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
        <!-- Ionicons -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
        <!-- Theme style -->
        <link rel="stylesheet" href="dist/css/mainStyles.css">
        <link rel="stylesheet" href="dist/css/styles.css">


        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->
        <script src="plugins/jQuery/jquery-2.2.3.min.js"></script>
        <script src="bootstrap/js/bootstrap.min.js"></script>
        <script src="plugins/slimScroll/jquery.slimscroll.min.js"></script>
        <script src="dist/js/app.js"></script>
        <script type="text/javascript" src="js/ajax_functions.js"></script>
        <script type="text/javascript" src="js/lib.js"></script>
        <script type="text/javascript" src="js/myjs.js"></script>

        <link rel="stylesheet" href="css/alertify.core.css" />
        <link rel="stylesheet" href="css/alertify.default.css" id="toggleCSS" />
        <script src="js/alertify.min.js"></script>


</head>




<body class="hold-transition sidebar-collapse sidebar-mini">
<section class='content-header'>
<h2 class='title'>EMPLOYEE MASTER</h2>
</section>
          <?php


          $findBycode = isset($_POST['txtlookcode']) ? $_POST['txtlookcode'] : '';
          $findBytype = isset($_POST['txtlooktype']) ? $_POST['txtlooktype'] : '';
          
          
          $_REQUEST['cmb_lookuplist'] = isset($_REQUEST['cmb_lookuplist']) ? $_REQUEST['cmb_lookuplist'] : '';
          $_REQUEST['cmb_lookuplist1'] = isset($_REQUEST['cmb_lookuplist1']) ? $_REQUEST['cmb_lookuplist1'] : '';
          $_REQUEST['frmPage_startrow'] = isset($_REQUEST['frmPage_startrow']) ? $_REQUEST['frmPage_startrow'] : '';
          $_REQUEST['frmPage_rowcount'] = isset($_REQUEST['frmPage_rowcount']) ? $_REQUEST['frmPage_rowcount'] : '';
          $_REQUEST['txtsearch'] = isset($_REQUEST['txtsearch']) ? $_REQUEST['txtsearch'] : '';

          
          


          if($_REQUEST['cmb_lookuplist'] != "") {
              $_SESSION['lookcode'] = $_REQUEST['cmb_lookuplist'];
          }
          if($_REQUEST['cmb_lookuplist1'] != "") {
              $_SESSION['lookcode1'] = $_REQUEST['cmb_lookuplist1'];
          }

          if($_REQUEST["frmPage_startrow"]>1){
            $_SESSION["frmPage_startrow"]=$_REQUEST["frmPage_startrow"];
          }else{
            $_SESSION["frmPage_startrow"]="0";
          }

          if($_REQUEST["frmPage_rowcount"]>15){
            $_SESSION["frmPage_rowcount"]=$_REQUEST["frmPage_rowcount"];
          }else{
            $_SESSION["frmPage_rowcount"]="15";
          }

          $topcombo = "&nbsp;&nbsp; Status: ";
          $topcombo .= "<label class='select'><select name='cmb_lookuplist' id='cmb_lookuplist'
                          class='form-control select' style='padding-left:5px;padding-right:5px;margin-top:-5px;height:30px;font-weight:normal;'
                          onchange=javascript:refreshPaging('employeemaster.php');>";

         $topcombo .= "<option value='Select' selected='selected'>All</option>";
         $SQL1 =  "select lookcode,lookname from in_lookup where looktype='EMPLOYEE STATUS' and lookname<>'XX' order by id";
         $SQLRes1 =  mysqli_query($con,$SQL1) or die(mysqli_error()."<br>".$SQL1);
         if(mysqli_num_rows($SQLRes1)>=1){
             while($loginResultArray1   = mysqli_fetch_array($SQLRes1)){
               if($_SESSION['lookcode']==$loginResultArray1['lookcode']){
                $topcombo .= "<option value='".$loginResultArray1['lookcode']."' selected='selected'>".$loginResultArray1['lookname']."</option>";
               }else{
                $topcombo .= "<option value='".$loginResultArray1['lookcode']."'>".$loginResultArray1['lookname']."</option>";
               }
             }
         }
         $topcombo .= "</select></label>";

          $topcombo1 = "<span style='float:left'>Sponsor Company :&nbsp;&nbsp;</span>";
          $topcombo1 .= "<label class='select'><select name='cmb_lookuplist1' id='cmb_lookuplist1' class='form-control select2' style='padding-left:5px;padding-right:5px;margin-top:-5px;height:30px;font-weight:normal;' onchange=javascript:refreshPaging('employeemaster.php');>";
          $topcombo1 .= "<option value='Select' selected='selected'>Select</option>";
          $SQL =  "select companycode,companyname from tbl_companysetup order by companyname";
          $SQLRes =  mysqli_query($con,$SQL) or die(mysqli_error()."<br>".$SQL);
           if(mysqli_num_rows($SQLRes)>=1){
             while($loginResultArray   = mysqli_fetch_array($SQLRes)){

               if($_SESSION["lookcode1"]==$loginResultArray['companycode']){
                $topcombo1 .= "<option value='".$loginResultArray['companycode']."' selected='selected'>".$loginResultArray['companyname']."</option>";
               }else{
                $topcombo1 .= "<option value='".$loginResultArray['companycode']."'>".$loginResultArray['companyname']."</option>";
               }
             }
           }
          $topcombo1 .= "</select></label>";

          $id_field = 'p.id';
          $fieldNames = array('','EMP ID','USER ID','Employee Name','Designation','Department','DOJ','Sponsor Company','Status');
          $fieldSizes = array('',7,7,16,14,10,8,18,10);

          $fieldAlign = array('','left','left','left','left','left','left','left','left','left');
          $staffname = "concat(p.empfirstename,' ',p.emplastename) as name";
          $sponser = "empsponsercompany";// a.companyname as sponser";
          $company = " b.companyname as company";
          $doj ="DATE_FORMAT(p.empdateofjoin,'%d-%m-%Y') as empdateofjoin";
          $empstatus="empstatus";//"if((p.empstatus='Active' or p.empstatus='Probation' or p.empstatus='On Leave'),'Current',p.empstatus)";
          $fields = array('p.empgrade','p.empid',$staffname,'empdesignation', 'empdepartment', $doj,$sponser,$empstatus);
          $tablexxx = 'in_personalinfo as p INNER JOIN tbl_designation as la ON
                    (p.empdesignation=la.id) INNER JOIN tbl_department as lb ON(p.empdepartment=lb.id)INNER JOIN tbl_companysetup as b on (p.empcompany=b.companycode)INNER JOIN tbl_companysetup as a on (p.empsponsercompany=a.companycode)
                    ';
          $table = " in_personalinfo as p ";
          $formlistname = "employeemaster.php";
          $formeditlist= "editemployeemaster.php";


          $buttons = array('insert' => $insert,'edit' => $update, 'delete' => $delete, 'view' => true,
                          'keys' => array('id'));



          $arguments = array('id_field' => $id_field, 'fieldNames' => $fieldNames, 'fieldSizes' => $fieldSizes,
                          'fieldAlign' => $fieldAlign, 'fields' => $fields, 'table' => $table,'buttons' => $buttons,
                          'formlistname'=>$formlistname,'topcombo'=>$topcombo,'topcombo1'=>$topcombo1,'topcomboselected'=>$_SESSION["lookcode"],
                          'topcomboselected1'=>$_SESSION["lookcode1"],'formeditlist'=>$formeditlist,'selectedlistingpage'=>$_SESSION['frmPage_startrow'],'selectedrowcount'=>$_SESSION['frmPage_rowcount']);


           $findBysearch="";
          if($_REQUEST["txtsearch"]!="" ){
             $findBysearch=$_REQUEST['txtsearch'];
             $_SESSION["txtsearch"]= $findBysearch;

          }else{
             $_SESSION["txtsearch"]="";
          }

         /* if($_SESSION["lookcode"]!="Select" && $_SESSION["lookcode1"]!="Select"){
          $clause = "empsponsercompany='".$_SESSION["lookcode1"]."' and empstatus='".$_SESSION["lookcode"]."' and (p.empfirstename LIKE '%$findBysearch%' or p.emplastename LIKE '%$findBysearch%'
                     or p.empgrade LIKE '%$findBysearch%' or p.empid LIKE '%$findBysearch%' or la.designationname LIKE '%$findBysearch%' or lb.departmentname LIKE '%$findBysearch%'  or p.passportwith LIKE '%$findBysearch%' or p.emppassportno LIKE '%$findBysearch%'  or $empstatus LIKE '%$findBysearch%')";
          }elseif($_SESSION["lookcode"]=="Select" && $_SESSION["lookcode1"]!="Select"){
          $clause = "empsponsercompany='".$_SESSION["lookcode1"]."' and (p.empfirstename LIKE '%$findBysearch%' or p.emplastename LIKE '%$findBysearch%'
                     or p.empgrade LIKE '%$findBysearch%' or p.empid LIKE '%$findBysearch%' or la.designationname LIKE '%$findBysearch%' or lb.departmentname LIKE '%$findBysearch%'  or p.passportwith LIKE '%$findBysearch%' or p.emppassportno LIKE '%$findBysearch%'  or $empstatus LIKE '%$findBysearch%')";
          }elseif($_SESSION["lookcode"]!="Select" && $_SESSION["lookcode1"]=="Select"){
          $clause = "empstatus='".$_SESSION["lookcode"]."' and  (p.empfirstename LIKE '%$findBysearch%' or p.emplastename LIKE '%$findBysearch%'
                     or p.empgrade LIKE '%$findBysearch%' or p.empid LIKE '%$findBysearch%' or la.designationname LIKE '%$findBysearch%' or lb.departmentname LIKE '%$findBysearch%'  or p.passportwith LIKE '%$findBysearch%' or p.emppassportno LIKE '%$findBysearch%'  or $empstatus LIKE '%$findBysearch%')";
          }else{
          $clause = "(p.empfirstename LIKE '%$findBysearch%' or p.emplastename LIKE '%$findBysearch%'
                      or p.empgrade LIKE '%$findBysearch%'  or p.empid LIKE '%$findBysearch%' or la.designationname LIKE '%$findBysearch%' or lb.departmentname LIKE '%$findBysearch%'  or p.passportwith LIKE '%$findBysearch%' or p.emppassportno LIKE '%$findBysearch%'  or $empstatus LIKE '%$findBysearch%')";
          }*/
         // echo $clause."<br><br><br><br>";
		  $clause = " p.empid LIKE '%$findBysearch%' ";
          $grid = new MyPHPGrid('frmPage');

          $grid->drawGrid($arguments, $clause, $con);


          $grid->editFields = array("empid,empfirstename,emplastename,emplabourname,emplocaltel,emppersonalemail");

          $grid->editRecord = $update;

          $grid->TabsNames = array("Employee Details");

          $grid->TableName = "in_personalinfo";

          $grid->formName = "employeemaster.php";

          $grid->inpage = $_REQUEST['frmPage_startrow'];

          $grid->SyncSession($grid);
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
                   $(window).resize(function(){
                     boxHeight();
                   })

                });
                function boxHeight(){
                    var height = $("#content-wrapper-id",parent.document).height();
                    $('#section-content-id').height(height);
                    var boxheight = height - 128;
                    $('#box-body-id').height(boxheight);

                    boxheight = boxheight-14;
                    $('#box-body-id-rows').slimScroll({
                      height: boxheight +'px',
                      wheelStep: 100,
                      alwaysVisible: true

                    });


                }




     </script>
