<?php
session_start();
//error_reporting(0);
include "connection.php";
$_SESSION['SESSrepoptsHTML'] ="";

$category=$_REQUEST['categorytype'];
?>

<?php
if($_REQUEST['level']=="gallery"){


          echo "<iframe scrolling='no'  src='gallery.php?docid=".$_REQUEST['docid']."'   frameborder='0'  width='800px' height='400px'></iframe>";

}
if($_REQUEST['level']=="assetdescription_post"){
         $CMB = "<select name='cmb_A_assetdescription'  id='cmb_A_assetdescription' class='form-control select'>";
         $CMB .= "<option value=''></option>";
         $SEL = "Select in_asset.categoryname,in_assetdetail.assetid as assetid,in_assetdetail.assetdescription,quantity
         from tbl_serviceasset inner join in_asset on assettype=in_asset.categorycode
         inner join in_assetdetail on tbl_serviceasset.assetdescription=in_assetdetail.assetid where docid
         in (SELECT in_crmhead.id from in_crmhead
         where docno in (Select salesorderno from t_activitycenter where t_activitycenter.id='".$_REQUEST['docid']."'))
         and productcatcode='$category'";
         $RES = mysql_query($SEL);
         if(mysql_num_rows($RES)== 0 ){
         $SEL =  "select assetid,assetdescription from in_assetdetail where productcatcode='".$category."'";
         $RES = mysql_query($SEL);
         }
         while ($ARR = mysql_fetch_array($RES)) {
             $CMB .= "<option value='".$ARR['assetid']."' $SEL >".$ARR['assetdescription']."</option>";
         }
         $CMB .= "</select>";
         echo $CMB;
         exit;
}
if($_REQUEST['level']=="assetdescription"){
         $CMB = "<select name='cmb_A_assetdescription'  id='cmb_A_assetdescription' class='form-control select'>";
         $CMB .= "<option value=''></option>";
         $SEL =  "select assetid,assetdescription from in_assetdetail where productcatcode='".$category."'";
         $RES = mysql_query($SEL);
         while ($ARR = mysql_fetch_array($RES)) {
             $CMB .= "<option value='".$ARR['assetid']."' $SEL >".$ARR['assetdescription']."</option>";
         }
         $CMB .= "</select>";
         echo $CMB;
         exit;
}


if($_REQUEST['level']=="trainer"){

         $CMB = "<select name='cmb_A_trainer'  id='cmb_A_trainer' class='form-control select'>";
         $CMB .= "<option value=''></option>";
         $SEL =  "select id,trainername from in_trainers where institutename='$category' order by trainername";
         $RES = mysql_query($SEL);
         while ($ARR = mysql_fetch_array($RES)) {
             $CMB .= "<option value='".$ARR['id']."' $SEL >".$ARR['trainername']."</option>";
         }
         $CMB .= "</select>";
         echo $CMB;
         exit;
}
if($_REQUEST['level']=="costgroup"){

         $CMB = "<select name='shiftname_".$_REQUEST['slno']."'  id='shiftname_".$_REQUEST['slno']."' style='width:100px;'>";
         $CMB .= "<option value=''>Select</option>";
         $SEL =  "select categorycode,categoryname from in_costgroup,in_jobscope where
                  in_costgroup.categorycode=in_jobscope.costsubgroup and catgencode != 'XX' and docid='$category' group by categorycode  order by categoryname";
         $RES = mysql_query($SEL);
          while ($ARR = mysql_fetch_array($RES)) {
             $CMB .= "<option value='".$ARR['categorycode']."' $SEL >".$ARR['categoryname']."</option>";

          }
         $CMB .= "</select>";
         echo $CMB;
         exit;
}
if($_REQUEST['level']=="50"){

      $SEL   =  "select lookname,description from in_kpientry,in_lookup where in_lookup.lookcode=in_kpientry.factor order by in_kpientry.serialnumber";
      $Result = mysql_query($SEL)   or die(mysql_error()."<br>".$SEL);
      $Category_tbl =   "<br><table class='table table-bordered table-condensed table-fixed table-striped table-responsive' style='border:1px #2F3C43;'>";
          if(mysql_num_rows($Result)>=1){
          $ii=0;
           while($VouchArray=mysql_fetch_array($Result)){

                 $Category_tbl .= "<tr>";
                 $Category_tbl .= "<td  style='border: 1px solid #ccc;width:35%;'><b>".$VouchArray['lookname']."</b></td>";
                 $Category_tbl .= "<td  style='border: 1px solid #ccc;width:65%;'>".$VouchArray['description']."</td>";
                 $Category_tbl .= "</tr>";
                 ++$ii;
           }
    }
    $Category_tbl .="</table>";
    echo $Category_tbl;
}
if($_REQUEST['level']=="divcenter"){
         global $con;
         $CMB = "<select name='cmb_A_empdivision'  id='cmb_A_empdivision' class='form-control txt'>";
         $CMB .= "<option value=''></option>";
         $SEL =  "select id,divisionname from tbl_division order by divisionname";
         $RES = mysqli_query($con,$SEL);
          while ($ARR = mysqli_fetch_array($RES)) {
             $CMB .= "<option value='".$ARR['id']."' $SEL >".$ARR['divisionname']."</option>";
          }
         $CMB .= "</select>";

        /* $CMB2 = "<select name='cmb_A_empworklocation'  id='cmb_A_empworklocation' class='form-control txt'>";
         $CMB2 .= "<option value=''></option>";
         $SEL =  "select jobno,jobname from t_activitycenter where locationcode='$category' order by jobname";
         $RES = mysqli_query($con,$SEL);
          while ($ARR = mysqli_fetch_array($RES)) {
             $CMB2 .= "<option value='".$ARR['jobno']."' $SEL >".$ARR['jobname']."</option>";
          }
         $CMB2 .= "</select>";

         echo $CMB."#".$CMB2;*/
         echo $CMB;
         exit;
}
if($_REQUEST['level']=="incharge"){

         $type = strtoupper($category);
         $CMB = "<select name='cmb_A_inchargename'  id='cmb_A_inchargename' class='form-control select'>";
         $CMB .= "<option value=''></option>";
         $SEL =  "select empid,concat(empfirstename,' ',emplastename) as name from in_personalinfo where empstatus='Active' and
         (rolecode like '%".$type."%') order by empfirstename";
         $RES = mysql_query($SEL);
         while ($ARR = mysql_fetch_array($RES)) {
             $CMB .= "<option value='".$ARR['empid']."' $SEL >".$ARR['empid']." - ".$ARR['name']."</option>";

         }
         $CMB .= "</select>";
         echo $CMB;
         exit;
}
function getdiffindays($startDate, $endDate){

                  $SQL   = "Select datediff('$endDate','$startDate') as days";
                  $SQLRes =  mysql_query($SQL) or die(mysql_error()."<br>".$SQL);
                  if(mysql_num_rows($SQLRes)>=1){
                   $loginResultArray   = mysql_fetch_array($SQLRes);
                   $days= $loginResultArray['days']+1;
                  }
                  return  $days;

}
function days_in_month($month, $year)
          {
          // calculate number of days in a month
           return $month == 2 ? ($year % 4 ? 28 : ($year % 100 ? 29 : ($year % 400 ? 28 : 29))) : (($month - 1) % 7 % 2 ? 30 : 31);
          }
if($_REQUEST['level']=="monthdays"){
   $noofdays = days_in_month($category,$_REQUEST['year']);

   if($category==01){
    $frommonth=12;
    $fromyear=$_REQUEST['year']-1;
  }else{
     $frommonth=$category-1;
  if(strlen($frommonth)<>2)$frommonth = "0".$frommonth;
     $fromyear=$_REQUEST['year'];
  }

           $Fromdate = $fromyear."-".$frommonth."-26" ;
           $Todate= $_REQUEST['year']."-".$category."-25";


  $days=getdiffindays($Fromdate,$Todate);
   echo $days;
   exit;
}
if($_REQUEST['level']=="lookcode"){
         $saveid = GetLastSqeID("testlookcode");
         $lookcode= $category."".$saveid;
         $CMB = "<input type='text' class='form-control' name='txt_A_lookcode' id='txt_A_lookcode'  value='$lookcode'>";
         echo $CMB;
         exit;
}
if($_REQUEST['level']=="retention"){
         if($category=='No'){
            $disable="disabled";
         }else{
            $disable="";
         }
         $CMB = "<input type='text' class='form-control txt' onblur='javascript:cal4Payamt();' $disable  onkeypress='return AllowNumeric1(event);' maxlength=5 name='txt_A_retentionpercent' id='txt_A_retentionpercent'  value='' >";
         echo $CMB;
         exit;
}
if($_REQUEST['level']=="expirydate"){
         if($category=='No'){
            $disable="disabled";
         }else{
            $disable="";
         }
         $CMB = "<input type='text' class='form-control txt' $disable  data-provide='datepicker' maxlength=10  onkeypress='return AllowNumeric1(event)'  name='txd_A_expirydate' id='txd_A_expirydate'   value='' placeholder='dd-mm-yyyy' >";
         echo $CMB;
         exit;
}
if($_REQUEST['level']=="level"){

         $CMB = "<select name='cmb_A_namelevel'  id='cmb_A_namelevel' class='form-control select2'>";
         $CMB .= "<option value=''></option>";
         if($category=="Yes"){
           $SEL =  "select empid,concat(empfirstename,' ',emplastename) as name,empdesignation from in_personalinfo order by empfirstename";
         }else{
           $SEL =  "select empid,concat(empfirstename,' ',emplastename) as name,empdesignation from in_personalinfo where id='XXXXX' order by empfirstename";
         }
         $RES = mysql_query($SEL);
          while ($ARR = mysql_fetch_array($RES)) {
             $CMB .= "<option value='".$ARR['empid']."' $SEL >".$ARR['name']."&nbsp;&nbsp;:&nbsp;&nbsp;".getlookname($ARR['empdesignation'])."</option>";

          }
         $CMB .= "</select>";
         echo $CMB;
         exit;
}
if($_REQUEST['level']=="Accomodation"){

         $CMB = "<select name='cmb_A_empaccommodation' id='cmb_A_empaccommodation' class='form-control select'>";
         $CMB .= "<option value=''></option>";
         if($category=="22002"){
           $SEL =  "select id,accomodationname,accomodationtype from in_accomodationdetails where status='Active' order by accomodationname";
         }else{
           $SEL =  "select id,accomodationname,accomodationtype from in_accomodationdetails where status='XXX' order by accomodationname";
         }
         $RES = mysql_query($SEL);
          while ($ARR = mysql_fetch_array($RES)) {
             $CMB .= "<option value='".$ARR['id']."' $SEL >".$ARR['accomodationname']." - ".getlookname($ARR['accomodationtype'])."</option>";

          }
         $CMB .= "</select>";
         echo $CMB;
         exit;
}
if($_REQUEST['level']=="center"){

         $CMB = "<select name='cmb_A_department'  id='cmb_A_department' class='form-control select'>";
         $CMB .= "<option value=''></option>";
         $SEL =  "select jobno,jobname from t_activitycenter where locationcode='$category' order by jobname";
         $RES = mysql_query($SEL);
          while ($ARR = mysql_fetch_array($RES)) {
             $CMB .= "<option value='".$ARR['jobno']."' $SEL >".$ARR['jobname']."</option>";

          }
         $CMB .= "</select>";
         echo $CMB;
         exit;
}
if($_REQUEST['level']=="1"){

         $CMB = "<select name='cmb_A_empdivision'  id='cmb_A_empdivision' class='form-control txt'>";
         $CMB .= "<option value=''></option>";
         $SEL =  "select code,division,in_lookup.lookname from in_locationdivision,in_lookup where in_locationdivision.division= in_lookup.lookcode and
                  locationcode='$category'   order by lookname";
         $RES = mysql_query($SEL);
          while ($ARR = mysql_fetch_array($RES)) {
             $CMB .= "<option value='".$ARR['division']."' $SEL >".$ARR['lookname']."</option>";

          }
         $CMB .= "</select>";
         echo $CMB;
         exit;
}
if($_REQUEST['level']=="2"){

         $CMB = "<select name='cmb_A_postdivision'  id='cmb_A_postdivision' class='form-control txt'>";
         $CMB .= "<option value=''></option>";
         $SEL =  "select code,division,in_lookup.lookname from in_locationdivision,in_lookup where in_locationdivision.division= in_lookup.lookcode and
                  locationcode='$category'   order by lookname";
         $RES = mysql_query($SEL);
          while ($ARR = mysql_fetch_array($RES)) {
             $CMB .= "<option value='".$ARR['division']."' $SEL >".$ARR['lookname']."</option>";

          }
         $CMB .= "</select>";
         echo $CMB;
         exit;
}
if($_REQUEST['level']=="gadget"){
         $SEL =  "select lookname from in_lookup where lookcode='$category'";
         $RES = mysql_query($SEL);
         while ($ARR = mysql_fetch_array($RES)) {
                $gadcat = $ARR['lookname'];
         }
         $CMB = " <select name='cmb_A_gadgetitem'  id='cmb_A_gadgetitem' class='form-control txt' >";
         $CMB .= "<option value=''></option>";
         $SEL =  "select lookcode,lookname from in_lookup where  looktype='".$gadcat."'  and lookname<>'XX' order by id";
         $RES = mysql_query($SEL);
         while ($ARR = mysql_fetch_array($RES)) {
                $SEL = "";
                $CMB .= "<option value='".$ARR['lookcode']."' $SEL >".$ARR['lookname']."</option>";
         }
         $CMB .= "</select>";
         echo $CMB;
         exit;
}
if($_REQUEST['level']=="3"){
         $CMB = " <select name='cmb_A_subcategory'  id='cmb_A_subcategory' class='form-control txt' >";
         $CMB .= "<option value=''></option>";
         $SEL =  "select lookcode,lookname from in_lookup where  looktype='".$category."'  and lookname<>'XX' order by id";
         $RES = mysql_query($SEL);
         while ($ARR = mysql_fetch_array($RES)) {
                $SEL = "";
                $CMB .= "<option value='".$ARR['lookcode']."' $SEL >".$ARR['lookname']."</option>";
         }
         $CMB .= "</select>";
         echo $CMB;
         exit;
}
if($_REQUEST['level']=="4"){
        $diff=($_REQUEST['from']-1)*15;
           $html =   "<table class='table table-list table-bordered table-striped wrap-tbl' width=100%>

                       <th width=10% class='tableMHead'><b>Id</b></th>
                       <th width=30% class='tableMHead'><b>Name</b></th>
                       <th width=15% class='tableMHead'><b>Designation</b></th>
                       <th width=15% class='tableMHead'><b>Department</b></th>
                       <th width=25% class='tableMHead'><b>Expiry Date</b>

                       </th>
                       </tr>";


      if($_REQUEST['from']==0){
      $SEL = " select in_personalinfo.*,DATE_FORMAT(expirydate,'%d-%m-%Y') as expirydate  from e_documents,in_personalinfo where  e_documents.staffid=in_personalinfo.empid  and empstatus= 'ACTIVE' and documenttype='".$_REQUEST['type']."'  and   expirydate < '".date('Y-m-d')."'";
      }else{
      $SEL = " select in_personalinfo.*,DATE_FORMAT(expirydate,'%d-%m-%Y') as expirydate  from e_documents,in_personalinfo where  e_documents.staffid=in_personalinfo.empid  and empstatus= 'ACTIVE' and documenttype='".$_REQUEST['type']."'  and  expirydate between DATE_ADD(now(),INTERVAL $diff DAY)  and DATE_ADD(now(),INTERVAL ".$_REQUEST['to']." DAY)";
      }
      $DResult = mysql_query($SEL)   or die(mysql_error()."<br>".$RSQL);
       if(mysql_num_rows($DResult)>=1){
           while($VouchArray=mysql_fetch_array($DResult)){

                           $html.=   "<TR>

                                       <TD class='TableRow' align='left' border='1'>".$VouchArray['empid']."</TD>
                                       <TD class='TableRow' align='left' border='1'>".$VouchArray['empfirstename']."</TD>

                                        <TD class='TableRow' align='left' border='1'>".$VouchArray['empdesignation']."</TD>
                                        <TD class='TableRow' align='left' border='1'>".$VouchArray['empdepartment']."</TD>

                                       <TD class='TableRow' align='left' border='1'>".$VouchArray['expirydate']."</TD>

                                       </tr>";


        }

    }
    $html .= "<tr id=tr1 id=tr1><td colspan=5 align=right><a href='Export_To_Excel.php'><img src='img/excel.gif' /></a>
                        &nbsp;&nbsp;<a href='javascript:printData();'><img src='img/print.gif' /></a></td></table>";

   echo $html;

   $_SESSION['SESSrepoptsHTML'] =$html;
   exit;
}
if($_REQUEST['level']=="5"){

           $html =   "<table class='table table-list table-bordered table-striped wrap-tbl' width=100%>

                       <th width=20% class='tableMHead'><b>Assigned By</b></th>
                       <th width=15% class='tableMHead'><b>Time</b></th>
                       <th width=15% class='tableMHead'><b>Priority</b></th>
                       <th width=20% class='tableMHead'><b>Description</b></th>
                       <th width=20% class='tableMHead'><b>Status</b></th>
                       </th>
                       </tr>";
      $RSQL = "select in_crmtasks.*,empfirstename from in_crmtasks,in_personalinfo where in_personalinfo.empid=in_crmtasks.assignedto and month(taskdate)='".$_REQUEST['month']."'
                 and year(taskdate)='".$_REQUEST['year']."' and assignedto='".$_SESSION['SESSuserID']."' and day(taskdate)='".$_REQUEST['day']."'";

      $DResult = mysql_query($RSQL)   or die(mysql_error()."<br>".$RSQL);
       if(mysql_num_rows($DResult)>=1){
           while($VouchArray=mysql_fetch_array($DResult)){

                           $html.=   "<TR>

                                       <TD class='TableRow' align='left' border='1'>".$VouchArray['empfirstename']."</TD>
                                       <TD class='TableRow' align='left' border='1'>".$VouchArray['taskhh']."</TD>

                                        <TD class='TableRow' align='left' border='1'>".$VouchArray['priority']."</TD>


                                       <TD class='TableRow' align='left' border='1'>".$VouchArray['description']."</TD>
                                       <TD class='TableRow' align='left' border='1'>".$VouchArray['status']."</TD>

                                       </tr>";


        }

    }
    $html.=   "</table>";
    echo $html;

   $_SESSION['SESSrepoptsHTML'] =$html;
   exit;
}
if($_REQUEST['level']=="8"){

           $html =   "<table class='table table-list table-bordered table-striped wrap-tbl' width=100%>


                       <th width=30% class='tableMHead'><b>Time</b></th>
                       <th width=15% class='tableMHead'><b>Priority</b></th>
                       <th width=35% class='tableMHead'><b>Description</b></th>


                       </th>
                       </tr>";
      $RSQL = "select * from in_crmtasks where month(taskdate)='".$_REQUEST['month']."'  and day(taskdate)='".$_REQUEST['day']."'
                 and year(taskdate)='".$_REQUEST['year']."' and userid='".$_SESSION['SESSuserID']."' and assignedto is null";

      $DResult = mysql_query($RSQL)   or die(mysql_error()."<br>".$RSQL);
       if(mysql_num_rows($DResult)>=1){
           while($VouchArray=mysql_fetch_array($DResult)){

                           $html.=   "<TR>


                                       <TD class='TableRow' align='left' border='1'>".$VouchArray['taskhh']."</TD>

                                        <TD class='TableRow' align='left' border='1'>".$VouchArray['priority']."</TD>

                                       <TD class='TableRow' align='left' border='1'>".$VouchArray['description']."</TD>

                                       </tr>";


        }

    }
    $html.=   "</table>";
    echo $html;

   $_SESSION['SESSrepoptsHTML'] =$html;
   exit;
}
if($_REQUEST['level']=="6"){

      $SEL   =  "select leavecode,leavename,paytype,timefrom,timeto from t_leavetypes order by leavecode";
      $Result = mysql_query($SEL)   or die(mysql_error()."<br>".$SEL);
      $Category_tbl =   "<br><table width=100% class='table-list' border='1'>";
          if(mysql_num_rows($Result)>=1){
          $ii=0;
           while($VouchArray=mysql_fetch_array($Result)){
           if($VouchArray['timefrom']!=''){
              $time= "<br>".$VouchArray['timefrom']." to ".$VouchArray['timeto'];
           }else{
              $time="";
           }
               if($ii==0 || $ii==3 || $ii==6 || $ii==9 || $ii==12 || $ii==15 || $ii==18){
                 $Category_tbl .= "<tr>";
               }
                 $Category_tbl .= "<TD align='left'><b>".$VouchArray['leavecode']."</b>&nbsp;:&nbsp;".$VouchArray['leavename']."-".$VouchArray['paytype']."$time</TD>";


               if($ii==2 || $ii==5 || $ii==8 || $ii==11 || $ii==14 || $ii==17 || $ii==20){
                 $Category_tbl .= "</tr>";
               }
               ++$ii;
           }
    }
    $Category_tbl .="</table>";
    echo $Category_tbl;
}
if($_REQUEST['level']=="7"){

           $html =   "<table class='table table-list table-bordered table-striped wrap-tbl' width=100%>


                       <th width=40% class='tableMHead'><b>Leave type</b></th>
                       <th width=15% class='tableMHead'><b>Nos</b></th>
                       <th width=15% class='tableMHead'><b>Period(Months)</b>
                       <th width=15% class='tableMHead'><b>Calculation Type</b>
                       <th width=15% class='tableMHead'><b>Eligible After</b></th>


                       </th>
                       </tr>";
      $RSQL = "SELECT in_staffleavescaleitem.*,t_leavetypes.leavename,t_leavetypes.paytype FROM in_staffleavescaleitem,t_leavetypes where
              in_staffleavescaleitem.leavetype=t_leavetypes.leavecode and  scaleid='".$_REQUEST['package']."'";
      $DResult = mysql_query($RSQL)   or die(mysql_error()."<br>".$RSQL);
       if(mysql_num_rows($DResult)>=1){
           while($VouchArray=mysql_fetch_array($DResult)){

                           $html.=   "<TR>

                                       <TD class='TableRow' align='left' border='1'>".$VouchArray['leavename'] . "-" .$VouchArray['paytype'] . "</TD>
                                       <TD class='TableRow' align='left' border='1'>".$VouchArray['nodays']."</TD>

                                        <TD class='TableRow' align='left' border='1'>".$VouchArray['period']."</TD>
                                        <TD class='TableRow' align='left' border='1'>".$VouchArray['calctype']."</TD>

                                       <TD class='TableRow' align='left' border='1'>".$VouchArray['eligiblefrom']."</TD>

                                       </tr>";


        }

    }
    $html.=   "</table>";
    echo $html;


   exit;
}
if($_REQUEST['level']=="9"){

           $html =   "<table class='table table-list table-bordered table-striped wrap-tbl' width=100%>

                       <th width=10% class='tableMHead'><b>Slno</b></th>
                       <th width=30% class='tableMHead'><b>Employee</b></th>
                       <th width=20% class='tableMHead'><b>Designation</b></th>
                       <th width=20% class='tableMHead'><b>Department</b>
                       <th width=20% class='tableMHead'><b>Contact No</b>

                       </th>
                       </tr>";
      $RSQL = "SELECT concat(empfirstename,' ',emplastename) as name,empdesignation,empdepartment,empworkmobile from in_personalinfo where empstatus='Active'";
      $DResult = mysql_query($RSQL)   or die(mysql_error()."<br>".$RSQL);
       if(mysql_num_rows($DResult)>=1){
           $slno=1;
           while($VouchArray=mysql_fetch_array($DResult)){

                           $html.=   "<TR>
                                       <TD class='TableRow' align='left' border='1'>".$slno . "</TD>
                                       <TD class='TableRow' align='left' border='1'>".$VouchArray['name'] . "</TD>
                                       <TD class='TableRow' align='left' border='1'>".getlookname($VouchArray['empdesignation'])."</TD>

                                        <TD class='TableRow' align='left' border='1'>".getlookname($VouchArray['empdepartment'])."</TD>
                                        <TD class='TableRow' align='left' border='1'>".$VouchArray['empworkmobile']."</TD>

                                       </tr>";

                        $slno++;
        }

    }
    $html.=   "</table>";
    echo $html;


   exit;
}

if($_REQUEST['level']=="10"){

        $timesheetdateArr=explode('-',$_REQUEST['timesheetdate']);
        $mydate = $timesheetdateArr[2]."-".$timesheetdateArr[1]."-".$timesheetdateArr[0];

         $CMB = "<select name='cmb_A_shift'  id='cmb_A_shift' class='form-control txt'>";
         $CMB .= "<option value=''>Select</option>";
         $SEL =  "select distinct shiftname,concat(fromtime,'-',totime) as shifttime from in_rosterplanning where
                  '$mydate' between fromdate and todate and locationcode='".$category."'  and shiftname is not null  order by shiftname";
         $RES = mysql_query($SEL);
          while ($ARR = mysql_fetch_array($RES)) {
             if($ARR['shiftname']!=''){
             $CMB .= "<option value='".$ARR['shiftname']."' $SEL >".$ARR['shiftname']."-".$ARR['shifttime']."</option>";
             }
          }
         $CMB .= "</select>";
         echo $CMB;
         exit;
}
if($_REQUEST['level']=="11"){

           $html =   "<table class='table table-list table-bordered table-striped wrap-tbl' width=100%>


                       <th width=30% class='tableMHead'><b>Empid</b></th>
                       <th width=15% class='tableMHead'><b>Emp Name</b></th>
                       <th width=35% class='tableMHead'><b>Designation</b></th>


                       </th>
                       </tr>";
      $RSQL = "select * from in_personalinfo where month(empdob)='".$_REQUEST['month']."'  and day(empdob)='".$_REQUEST['day']."'";

      $DResult = mysql_query($RSQL)   or die(mysql_error()."<br>".$RSQL);
       if(mysql_num_rows($DResult)>=1){
           while($VouchArray=mysql_fetch_array($DResult)){

                           $html.=   "<TR>


                                       <TD class='TableRow' align='left' border='1'>".$VouchArray['empid']."</TD>

                                        <TD class='TableRow' align='left' border='1'>".$VouchArray['empfirstename']."</TD>

                                       <TD class='TableRow' align='left' border='1'>".getlookname($VouchArray['empdesignation'])."</TD>

                                       </tr>";


        }

    }
    $html.=   "</table>";
    echo $html;

   $_SESSION['SESSrepoptsHTML'] =$html;
   exit;
}
function GetLastSqeID($tblName){
                 $query = "LOCK TABLES in_sequencer WRITE";
                 mysql_query($query) or die(mysql_error()."<br>".$query);
                 $seqSQL = "SELECT LASTNUMBER FROM in_sequencer WHERE TABLENAME='$tblName'";
                 $result=mysql_query($seqSQL) or die(mysql_error()."<br>".$seqSQL);
                 $resulArr=mysql_fetch_array($result);
                 $updatedSeqID=$resulArr['LASTNUMBER']+1;
                 //$squpdateSQL = "UPDATE in_sequencer SET LASTNUMBER=".$updatedSeqID." WHERE TABLENAME='$tblName'";
                 //mysql_query($squpdateSQL) or die(mysql_error()."<br>".$squpdateSQL);
                 $query = "UNLOCK TABLES";
                 mysql_query($query) or die(mysql_error()."<br>".$query);
                 return ($updatedSeqID);
}
function getlookname($empid){

            $SQL = " Select in_lookup.lookname from in_lookup where lookcode ='".$empid."'";
            $SQLRes =  mysql_query($SQL) or die(mysql_error()."<br>".$SQL);
              if(mysql_num_rows($SQLRes)>=1){
                while($loginResultArray   = mysql_fetch_array($SQLRes)){
                  $id = $loginResultArray['lookname'];
                }
              }
         return $id ;
}
?>

