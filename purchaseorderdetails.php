<?php
session_start();
if($_SESSION['pr'] == "") {
  $_SESSION['pr'] = $_REQUEST['pr'];
}
require "connection.php";
require "pagingObj.php";
//print_r($_REQUEST);
//echo  $_REQUEST['INITEMID'];
$SQL1 = "select purchasetype,jobtype from in_inventoryhead where id='".$_REQUEST['INITEMID']."'";
$RES1 = mysql_query($SQL1);
$ARR1 = mysql_fetch_array($RES1);

$parent_purchasetype = $ARR1['purchasetype'];
$parent_jobtype = $ARR1['jobtype'];


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

function myFunction() {
   window.location.href='purchaseorderdetails.php?ID=<?php echo $_REQUEST['INITEMID'];?>&search='+document.getElementById('txtsearch').value+'&jobno=<?php echo $_REQUEST['jobno'];?>&INITEMID=<?php echo $_REQUEST['INITEMID'];?>';
   return false;
}
function print1(poid){
  var url = 'docreport3_1.php?rid=2020&id='+poid;
  window.open(url,'location=yes,height=570,width=520,scrollbars=yes,status=yes');
}
var xmlHttp;
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
function Showrecord(childid){
      xmlHttp=GetXmlHttpObject()
      if (xmlHttp==null)
      {
                 alert ("Browser does not support HTTP Request")
                 return
      }

      var url="combofunctions_showdetails.php?level=PO_details&childid="+childid;
      xmlHttp.onreadystatechange=stateChangedcomboref
      xmlHttp.open("POST",url,true)
      xmlHttp.send(null)
}
function stateChangedcomboref(){

       if (xmlHttp.readyState==4 || xmlHttp.readyState=="complete")
       {
             var s1 = trim(xmlHttp.responseText);
             document.getElementById('propertypopupdiv').innerHTML=s1;

       }
}

</script>
   </head>
<section class="content" id='content-content-id' style='padding-right:5px;padding-left:5px;' >
   <div class="box-body" id='box-body-id' style='background-color:#ffffff;'>
          <div class='table-responsive'>
<?
    $jobno = $_REQUEST['jobno'];
/*    $sqlpo = "SELECT *,t_activitycenter.jobtype,in_inventoryhead.parentdocno as parentdocno,in_inventoryhead.id as id FROM in_inventoryhead inner join t_activitycenter WHERE t_activitycenter.jobno=in_inventoryhead.jobno and in_inventoryhead.doctype='PURCHASEORDER' and in_inventoryhead.id='".$invid."'";*/
         
   $start1=0;
   $limit1=70;
   if($_GET['id1']){
   $id1=$_GET['id1'];
   $start1=($id1-1)*$limit1;
   }else{
   $id1=1;
   }
   
  $addsql="";
  if($_REQUEST['search']!=""){
     $addsql = " and (";
     $addsql .= " in_inventoryhead.docno like '%".$_REQUEST['search']."%'";
     $addsql .= " or in_inventoryhead.sitesendforapproval like '%".$_REQUEST['search']."%'";
     $addsql .= " or in_inventoryhead.purchasecategory like '%".$_REQUEST['search']."%'";
     $addsql .= " or in_inventoryhead.purchasesubcategory like '%".$_REQUEST['search']."%'";
     $addsql .= " or in_inventoryhead.purchasecategorytext like '%".$_REQUEST['search']."%'";
     $addsql .= " or lookname like '%".$_REQUEST['search']."%'";
     $addsql .= " or (in_inventoryhead.totalgrossamt-in_inventoryhead.totalvatamt) like '%".$_REQUEST['search']."%'";
     $addsql .= " or (select categoryname from in_asset as A where A.categorycode=in_inventoryhead.purchasesubcategory 
and A.categoryname is not null and in_inventoryhead.purchasecategory='SPECIALIZED SERVICES') like '%".$_REQUEST['search']."%'";
     $addsql .= ")";
  }
  
  if(stripos(json_encode($_SESSION['role']),'SITE INCHARGE') == true){
  	$addsql_1 =" and requestedby='".$_SESSION['SESSuserID']."' ";	
  }
  if(stripos(json_encode($_SESSION['role']),'SALES COORDINATOR') == true){
  	$addsql_1 =" and requestedby='".$_SESSION['SESSuserID']."' ";	
  }
  if(stripos(json_encode($_SESSION['role']),'PURCHASE COORDINATOR') == true){
  	$addsql_1 =" "; // " and requestedby='".$_SESSION['SESSuserID']."' ";	
  }	
  
  if($parent_jobtype=='AMC' && $parent_purchasetype == 'Subcontractor Purchase') 
  $addsql_1 .= " and jobtype='AMC' and purchasetype = 'Subcontractor Purchase'";
  else if($parent_jobtype=='AMC' && $parent_purchasetype != 'Subcontractor Purchase') 
  $addsql_1 .= " and purchasetype <>'Subcontractor Purchase'";
  
  //$rows1=mysql_num_rows(mysql_query("SELECT in_inventoryhead.docno,in_inventoryhead.sitesendforapproval FROM in_inventoryhead WHERE in_inventoryhead.jobno='".$jobno."' and in_inventoryhead.doctype='PURCHASEORDER'".$addsql_1.$addsql.$orderBy));
  $rows1=mysql_num_rows(mysql_query("SELECT in_inventoryhead.*,lookname,(totalgrossamt-totalvatamt) as totalpreviousval FROM in_inventoryhead left join in_lookup_head on in_inventoryhead.purchasetype=in_lookup_head.lookcode WHERE in_lookup_head.looktype='PURCHASE REQUEST TYPE' and doctype='PURCHASEORDER' and jobno='".$jobno."' and (sitesendforapproval ='(PO)Released to Supplier & Waiting for Delivery Note' or sitesendforapproval='(PO) Completed') $addsql_1 ".$addsql.$orderBy));


 echo "<div class='box' style='border:0px;padding:0px;'>
        <div>

        </div>
       <div class='box-tools pull-left '>

            <input type='search' name='txtsearch' onsearch='myFunction()' id=txtsearch class='form-control' style='height:24px;border: 1px solid #ccc;width:200px;' placeholder='Search..' value=".$_REQUEST['search'].">

       </div>
       <div class='box-tools pull-right '>

            <ul class='pagination pagination-sm no-padding pull-right'>";

                $total1=ceil($rows1/$limit1);
                for($i=1;$i<=$total1;$i++){
                   // echo $i;
                   // echo $id1;
                    
                    if($i==$id1) {
                       echo "<li class='active' ><a href='' style='padding-top:0px;padding-bottom:0px;padding-left:.5em;padding-right:.5em;'>".$i."</a></li>";
                    }else {
                       echo "<a href='?invid=".$invid."&ID=".$_REQUEST['ID']."&id1=".$i."' style='padding-top:0px;padding-bottom:0px;padding-left:.5em;padding-right:.5em;'>".$i."</a></li>"; }
                    }
       echo "</ul>
       </div>
       </div>";

$orderBy = " order by in_inventoryhead.id desc "; //LIMIT $start1, $limit1

        $entrydatatable = "<table class='table table-bordered table-condensed table-fixed table-striped table-responsive' style='border:1px #2F3C43;'>";
        $entrydatatable.="<thead><tr>";
        $entrydatatable.= "<th class='bg-light-blue' style='width:5%;text-align:left;border-bottom:1px #2F3C43 solid;border-top:1px #fff solid;color:#FFFFFF'>Slno </th>";
        $entrydatatable.= "<th class='bg-light-blue' style='width:10%;text-align:left;border-bottom:1px #2F3C43 solid;border-top:1px #fff solid;color:#FFFFFF'>Previous PO Nos</th>";
        $entrydatatable.= "<th class='bg-light-blue' style='width:8%;text-align:left;border-bottom:1px #2F3C43 solid;border-top:1px #fff solid;color:#FFFFFF'>Purchase Type</th>";
        $entrydatatable.= "<th class='bg-light-blue' style='width:15%;text-align:left;border-bottom:1px #2F3C43 solid;border-top:1px #fff solid;color:#FFFFFF'>Purchase Category</th>";
        if($parent_jobtype == 'AMC')
        $entrydatatable.= "<th class='bg-light-blue' style='width:20%;text-align:left;border-bottom:1px #2F3C43 solid;border-top:1px #fff solid;color:#FFFFFF'>Purchase Sub Category</th>";
        $entrydatatable.= "<th class='bg-light-blue' style='width:20%;text-align:left;border-bottom:1px #2F3C43 solid;border-top:1px #fff solid;color:#FFFFFF'>Previous Purchased Amount</th>";
        $entrydatatable.= "<th class='bg-light-blue' style='width:20%;text-align:left;border-bottom:1px #2F3C43 solid;border-top:1px #fff solid;color:#FFFFFF'>Status</th>";
        $entrydatatable.= "<th class='bg-light-blue' style='width:4%;text-align:left;border-bottom:1px #2F3C43 solid;border-top:1px #fff solid;color:#FFFFFF'>View LPO</th>";

        $entrydatatable.= "</tr></thead><tbody>";
$slno = 1;
//echo $jobno;
$sql1 = "SELECT in_inventoryhead.*,lookname,(totalgrossamt-totalvatamt) as totalpreviousval FROM in_inventoryhead left join in_lookup_head on in_inventoryhead.purchasetype=in_lookup_head.lookcode WHERE in_lookup_head.looktype='PURCHASE REQUEST TYPE' and doctype='PURCHASEORDER' and jobno='".$jobno."' and (sitesendforapproval ='(PO)Released to Supplier & Waiting for Delivery Note' or sitesendforapproval='(PO) Completed') $addsql_1 ".$addsql.$orderBy;
$res1 = mysql_query($sql1) or die(mysql_error());
if(mysql_num_rows($res1)>0) {
while($loginResultArrayChild = mysql_fetch_array($res1)){
		$purchasecategory = $loginResultArrayChild['purchasecategory'];
		$purchasetype = $loginResultArrayChild['purchasetype'];
		$purchasesubcategory =  $loginResultArrayChild['purchasesubcategory'];
		if($purchasecategory == "HARD SERVICES" && $purchasetype =="Subcontractor Purchase")
		$purchasesubcategory =  $loginResultArrayChild['purchasecategorytext'];
		if($purchasecategory == "SPECIALIZED SERVICES")
		$purchasesubcategory = getPurchaseSubcategory($loginResultArrayChild['purchasesubcategory']);
		if($purchasecategory == "GENERAL" || $purchasecategory =="Others")
		$purchasesubcategory =  $loginResultArrayChild['purchasecategorytext'];
         
        $docdate = $loginResultArrayChild['docdate'];
        $docdate = date("d-m-Y", strtotime($docdate));
        $entrydatatable.= "<tr>";
        $entrydatatable.= "<td style='background-color:$colorbg;color:$colorfc;border:1px #ccc solid;'>" . $slno++. "</td>";
        $entrydatatable.= "<td style='background-color:$colorbg;color:$colorfc;border:1px #ccc solid;'>" . $loginResultArrayChild['docno'] . "</td>";
        $entrydatatable.= "<td style='background-color:$colorbg;color:$colorfc;border:1px #ccc solid;' align=left>" . $loginResultArrayChild['lookname'] . "</td>";
        $entrydatatable.= "<td style='background-color:$colorbg;color:$colorfc;border:1px #ccc solid;' align=left>" . $loginResultArrayChild['purchasecategory'] . "</td>";
        if($loginResultArrayChild['jobtype'] == 'AMC')
        $entrydatatable.= "<td style='background-color:$colorbg;color:$colorfc;border:1px #ccc solid;' align=left>" . $purchasesubcategory. "</td>";
        $entrydatatable.= "<td style='background-color:$colorbg;color:$colorfc;border:1px #ccc solid;' align=right>" . $loginResultArrayChild['totalpreviousval'] . "</td>";
        $entrydatatable.= "<td style='background-color:$colorbg;color:$colorfc;border:1px #ccc solid;'>" . $loginResultArrayChild['sitesendforapproval'] . "</td>";
        $lpoview= "<button  class='btn btn-primary inputs' style='margin-top:-1px;margin-left:2px;margin-right:2px;float:right' name='btndanger' type='button' onclick ='javascript:print1(".$loginResultArrayChild['id'].")'>&nbsp;<i class='glyphicon glyphicon-print' aria-hidden='true'></i></button></td>";
        $entrydatatable.= "<td style='background-color:$colorbg;color:$colorfc;border:1px #ccc solid;' align=center> $lpoview </td>";
        $entrydatatable.= "</tr>";


}
$tarr = mysql_fetch_array(mysql_query("SELECT SUM(totalgrossamt)-SUM(totalvatamt) AS totalpurchase FROM in_inventoryhead AS ch WHERE ch.jobno='$jobno' and (ch.sitesendforapproval='(PO)Released to Supplier & Waiting for Delivery Note' or ch.sitesendforapproval='(PO) Completed')$addsql_1 "));
if($parent_jobtype=='AMC') $colspan_val = 5;
else $colspan_val=4;
$entrydatatable.= "<tr><td style='background-color:$colorbg;color:$colorfc;border:1px #ccc solid;' align=right colspan=$colspan_val><b>Total Purchase Made</b> : </td>
<td style='background-color:$colorbg;color:$colorfc;border:1px #ccc solid;' align=right><b>".number_format($tarr['totalpurchase'],2)."</b> </td>
<td style='background-color:$colorbg;color:$colorfc;border:1px #ccc solid;' align=right colspan=2>&nbsp; </td></tr>";
}
$entrydatatable.= "<input type='hidden' name='jobno' id='jobno' value='".$jobno."'></tbody></table>";

$entrydatatable.= "</tbody></table>";
echo $entrydatatable;

function getPI_article_details($docno){
         $SQL = "SELECT *,in_inventoryline.articlecode FROM in_inventoryline left join  in_inventoryhead on in_inventoryhead.id=in_inventoryline.initemid
         where in_inventoryhead.docno='".$docno."'";
         $RES = mysql_query($SQL);
         $Status = "";
         if(mysql_num_rows($RES)>=1){
                  $IMG2 = "";
                  while($ARR = mysql_fetch_array($RES)){
                        $IMG2 [] = getPIdetails($docno,$ARR['articlecode']);
                        $qty += $ARR['quantity'];
                  }
                  $Status = $docno."{".$qty."} :";
                  for($i = 0; $i<count($IMG2); $i++){
                         $Status .= $IMG2 [$i];
                  }
        return substr($Status,0,-1);                                                     }
}
function getPIdetails($docno,$articlecode){
            $str = "";
           $SQL = "Select docno,quantity,sitesendforapproval  from in_inventoryhead inner join in_inventoryline
           on in_inventoryhead.id=in_inventoryline.initemid
           where in_inventoryline.doctype='PURCHASEORDER' and
           in_inventoryline.parentdocno='$docno' and  in_inventoryline.articlecode ='$articlecode' ";
             $SQLRes =  mysql_query($SQL) or die(mysql_error()."<br>".$SQL);
             while($loginResultArray   = mysql_fetch_array($SQLRes)){
                   $str .= $loginResultArray['docno']."{".number_format($loginResultArray['quantity'])."} - ".$loginResultArray['sitesendforapproval'].",";
                   if($loginResultArray['sitesendforapproval'] == "APPROVED"){
                       $str.=getPOdetails($loginResultArray['docno'],$articlecode);
                   }
             }
             return $str;
}
?>
<div class='modal fade' id='myModal1' tabindex='-1' role='dialog' aria-labelledby='myModalLabel'>
         <div class='modal-dialog' role='document' style="align:left;width:800px;">
            <div class='modal-content'>
                 <div class='modal-header' style='height:40px;'>
                  <button type='button' class='close' data-dismiss='modal' aria-label='Close'><span aria-hidden='true'>&times;</span></button>
                 <h3 style='margin-top:-5px;'>Materials</h3>
                 </div>
                  <div class='modal-body' id='propertypopupdiv' name='propertypopupdiv'> </div>

            </div>
         </div>
</div>
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
                   $(".select2").select2();
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
function GetUnitName($unit) {
         $SEL =  "select lookcode,lookname from in_lookup where looktype='UOM' and lookcode='$unit'";
         $RES = mysql_query($SEL);
         $ARR = mysql_fetch_array($RES);
         return $ARR['lookname'];

}
function getPurchaseSubcategory($code){
             $SQL1="select categorycode,categoryname from in_asset where categorycode='$code' order by id desc" ;
             $result1=mysql_query($SQL1) or die(mysql_error().$SQL1);
             $res=mysql_fetch_array($result1);
             return $res['categoryname'];
    } 
?>
