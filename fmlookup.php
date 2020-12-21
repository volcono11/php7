<?php
session_start();
require "connection.php";
require "pagingObj.php";
include "functions_workflow.php";
/*if($_REQUEST['ps'] == "1") {
  $_REQUEST['cmb_A_projectstore'] = "";
}*/
$_SESSION['objectid'] = isset($_REQUEST['objectid']) ? $_REQUEST['objectid'] : '';
$WF = new WorkFlow($_SESSION['objectid']);
$pagerights = $WF->loadPagerights();

$_SESSION['pr'] = isset($pagerights) ? $pagerights : '';

//$_SESSION['pr'] = isset($_REQUEST['pr']) ? $_REQUEST['pr'] : '';
$pr = $_SESSION['pr'];

$insert = $update = $delete = "false";

if(false !== strpos($_SESSION['pr'],"I")) $insert = "true" ;
if(false !== strpos($_SESSION['pr'],"U")) $update = "true" ;
if(false !== strpos($_SESSION['pr'],"D")) $delete = "true" ;

$CHILDID = isset($_REQUEST['CHILDID']) ? $_REQUEST['CHILDID'] : '';

$mysearch = isset($_REQUEST['mysearch']) ? $_REQUEST['mysearch'] : '';

$frmPage_rowcount = isset($_REQUEST['frmPage_rowcount']) ? $_REQUEST['frmPage_rowcount'] : '';

if($frmPage_rowcount>15){
			$_SESSION["frmPage_rowcount"]=$frmPage_rowcount;
}else{
			$_SESSION["frmPage_rowcount"]="15";
}
$frmPage_startrow = isset($_REQUEST['frmPage_startrow']) ? $_REQUEST['frmPage_startrow'] : '';


$grid = new MyPHPGrid('frmPage');
$grid->formName = "fmlookup.php";
$grid->inpage = $frmPage_startrow;
$grid->TableName = "in_lookup_fmcrm";
$grid->SyncSession($grid);
$formlistname="fmlookup.php";


$selected1 =$selected2 = $selected3 =$selected4 =$selected5 = '';
if($frmPage_rowcount=="") $selected1 ="selected='selected'";
if($frmPage_rowcount=="10") $selected1 ="selected='selected'";
if($frmPage_rowcount=="20") $selected2 ="selected='selected'";
if($frmPage_rowcount=="30") $selected3 ="selected='selected'";
if($frmPage_rowcount=="40") $selected4 ="selected='selected'";
if($frmPage_rowcount=="50") $selected5 ="selected='selected'";


if($CHILDID !='' && isset($_REQUEST['DEL']) =='DELETE'){

     $Del_query="delete from in_lookup_fmcrm where id='". $CHILDID."'";
     $Del_Result = mysqli_query($con,$Del_query)   or die(mysqli_error()."<br>".$Del_query);
     $_REQUEST['CHILDID']="";

}
/*if($_REQUEST['CHILDID'] !='' && $_REQUEST['DEL'] =='POST'){

     $Del_query="update in_lookup_fmcrm set posted='YES' where id='". $_REQUEST['CHILDID']."'";
     $Del_Result = mysqli_query($con,$Del_query)   or die(mysqli_error()."<br>".$Del_query);
     $_REQUEST['CHILDID']="";

}*/
if($frmPage_rowcount==''){
$record_per_page=10;
}else{
$record_per_page = $frmPage_rowcount;
}
$page = '';
if(isset($_REQUEST["page"])!=''){
 $page = $_REQUEST["page"];
}else{
 $page = 1;
}
//echo $page;
$start_from = ($page-1)*$record_per_page;
if($mysearch!=""){
    $addsql = " and (";
    $addsql .= "  looktype like '%".$mysearch."%'";
    $addsql .= ")";
}else{
    $addsql="";
}
$query = "SELECT * from in_lookup_fmcrm where lookname = 'XX' $addsql  order by id DESC LIMIT $start_from, $record_per_page";
$result = mysqli_query($con,$query);

?>
<!DOCTYPE html>
<html>
 <head>
  <title>Webslesson Tutorial | PHP Pagination with Next Previous First Last page Link</title>
  <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
  <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
  <link rel="stylesheet" href="plugins/select2/select2.min.css">
  <link rel="stylesheet" href="plugins/iCheck/all.css">
  <link rel="stylesheet" href="dist/css/mainStyles.css">
  <link rel="stylesheet" href="dist/css/styles.css">
  <link rel="stylesheet" href="css/alertify.core.css" />
  <link rel="stylesheet" href="css/alertify.default.css" id="toggleCSS" />
  <link rel="stylesheet" href="bootstrap/css/datepicker.css">

  <script src="bootstrap/js/jquery-1.9.1.min.js"></script>
  <script src="bootstrap/js/bootstrap-datepicker.js"></script>
  <script src="plugins/jQuery/jquery-2.2.3.min.js"></script>
  <script src="bootstrap/js/bootstrap.min.js"></script>
  <script src="plugins/slimScroll/jquery.slimscroll.min.js"></script>
  <script src="dist/js/app.js"></script>
  <script src="js/alertify.min.js"></script>
  <script type="text/javascript" src="js/ajax_functions.js"></script>
  <script type="text/javascript" src="js/lib.js"></script>
  <script type="text/javascript" src="js/myjs.js"></script>
  <script src="tablesorter/jquery.tablesorter.min.js"></script>
  <script src="plugins/slimScroll/jquery.slimscroll.js"></script>
  <script src="plugins/slimScroll/jquery.slimscroll.min.js"></script>
  <script src="plugins/select2/select2.full.min.js"></script>
  <script src="plugins/iCheck/icheck.min.js"></script>
 <style>

  n {
   padding:8px 12px;
   border:1px solid #ccc;
   color:#333;

  }
  </style>
</head>
<script type='text/javascript'>
function AllowNumeric1(objEvent){
            var iKeyCode;
            if(window.event){
               iKeyCode = objEvent.keyCode;
            }
            else if(objEvent.which){
                  iKeyCode = objEvent.which;
            }
            //alert(iKeyCode);
            if((iKeyCode<=1 && iKeyCode>=7) || (iKeyCode>=9 && iKeyCode<45) || (iKeyCode>=58 && iKeyCode<=255)){
                if (iKeyCode!=13 && iKeyCode!=97 && iKeyCode!=109 && iKeyCode!=112) {
                    alertify.error('Numbers Only');
                    return false;
                }
            }
            return true;
}
function Hideitem(){
    var tradd=document.getElementById('tradd');
    tradd.style.display="none";
    document.getElementById('addimg').style.display='table-row';

}
function Additem(){
    var tradd=document.getElementById('tradd');
    tradd.style.display="table-row";
    document.getElementById('addimg').style.display='none';

}
</script>
<body>
<section class='content-header' style='margin-top:5px;'>
<h2 class='title'>FM LOOKUP</h2>
</section>
<?php
$entrydata = "<form name='frmEdit' id='frmEdit'  method='post'>
                 <section class='content' id='section-content-id' style='padding-right:5px;padding-left:5px;background-color:#fff;' >
                          <div class='box box-primary' id='box-content-id'  >
                            <div class='box-body no-padding' id='box-body-id' style='overflow:hidden;' >
                             <table class='table table-condensed ' style='margin-top:3px;' border='0'>

                                             <tr>

                                             <td align='left' width='70%'><a href ='javascript:Additem();'  border='0' ><div id='addimg' name='addimg' style='display:none;'><img src='ico/add-1-icon.png'  title='New Entry' width='20' height='20'></div></a></td>
                                                <td  width='25%'>
                                                      <div class='input-group input-group-sm pull-right'>
                                                                  <input type='text' style='padding-left:5px;padding-right:5px;margin-top0px;height:30px;' onkeypress='return filtersublist(event);' name='mysearch' id=mysearch class='form-control pull-right' value='".$mysearch."' placeholder='Search..'>

                                                                  <div class='input-group-btn'>
                                                                    <button type='submit' style='padding-left:5px;padding-right:5px;margin-top:0px;height:30px;' class='btn btn-default' onclick='javascript:if(document.frmEdit) document.frmEdit.submit();'><i class='fa fa-refresh'></i></button>
                                                                  </div>

                                                      </div>
                                                </td>
                                               <td align='right'  width='5%'>

                                                                        <select name='frmPage_rowcount' id='frmPage_rowcount' class='form-control' style='padding-left:5px;padding-right:5px;margin-top:-1px;width:50px;'  onchange='javascript:if(document.frmEdit) document.frmEdit.submit();' >
                                                                        <option  $selected1 value='10'>10</option>
                                                                        <option  $selected2 value='20'>20</option>
                                                                        <option  $selected3 value='30'>30</option>
                                                                        <option  $selected4 value='40'>40</option>
                                                                        <option  $selected5 value='50'>50</option>
                                                                        </select>

                                                </td>
                                                </tr></table>




                            <table id='sortTable' class='table table-condensed tablesorter'>
                            <thead>
                             <tr bgcolor='#438EB9'>
                              <th style='width:20%;border-right:1px solid #ccc;cursor: pointer;'><font style='color:#fff'>Look Code</font></th>
                              <th style='width:60%;border-right:1px solid #ccc;cursor: pointer;'><font style='color:#fff'>Look Type</font></th>
                              <th style='width:20%;border-right:1px solid #ccc;'><font style='color:#fff'>Action</font></th>
                             </tr>
                             ";
         if($CHILDID==''){
                  $SQL   = "SELECT max(id) as id FROM in_lookup_fmcrm";
                  $SQLRes =  mysqli_query($con,$SQL) or die(mysqli_error()."<br>".$SQL);
                  if(mysqli_num_rows($SQLRes)>=1){
                       $loginResultArray   = mysqli_fetch_array($SQLRes);
                       $categorycode = $loginResultArray['id']+1;
                 }

            if($insert == "true")  //add new   
            $entrydata .= " <tr id='tradd' name='tradd'>
                             <td style='border: 1px solid #ccc;'><input type='text' class='form-control txt inputs' disabled id='txt_A_lookcode' name='txt_A_lookcode' onkeypress='return AllowNumeric1(event)'  value='$categorycode'/></td>
                             <td style='border: 1px solid #ccc;'><input type='text' class='form-control txt inputs' id='txt_A_looktype' name='txt_A_looktype'  value='' style='text-transform:uppercase'/></td>
                             <td style='border: 1px solid #ccc;'> <a href ='javascript:editingrecord();'  border='0' ><img src='ico/add-1-icon.png' title='New Entry' width='20' height='20'></a>&nbsp;
                             &nbsp;&nbsp;&nbsp;&nbsp;<a href ='javascript:Hideitem();'  border='0' ><img src='ico/cancel.png' title='Save Entry' width='18' height='18'></a>
                             </td>
                             </tr>";
         }


       $entrydata .= "  </thead>
                             <input type='hidden' name='txt_A_lookname' id='txt_A_lookname' class=textboxcombo value='XX'>
                             <input type='hidden' name='mode' class=textboxcombo id='mode' value='".$CHILDID."'>
                             <input type='hidden' name='modeid' class=textboxcombo id='modeid' value='save'> </thead>
                             <tbody>";

    $page_query = "SELECT * from in_lookup_fmcrm where lookname = 'XX' ORDER BY id DESC";
    $page_result = mysqli_query($con,$page_query);
    $total_rows = mysqli_num_rows($page_result);
    $total_rows=$total_rows-(($page-1)*$record_per_page);
    $i=1;
     while($row = mysqli_fetch_array($result))
     {

       if($row['id']==$CHILDID){
        $colorbg ="#F2F2F2";
        $entrydata .= "<tr><td style='background-color:$colorbg;border: 1px solid #ccc;'><input type='text' class='form-control txt inputs' disabled id='txt_A_lookcode' name='txt_A_lookcode' value='".$row['lookcode']."'/></td>
                       <td style='background-color:$colorbg;border: 1px solid #ccc;'><input type='text' class='form-control txt inputs' id='txt_A_looktype' name='txt_A_looktype' value='".$row['looktype']."' style='text-transform:uppercase'/></td>
                       <td style='border: 1px solid #ccc;'>
                            <a href ='javascript:editingrecord();'  border='0' ><img src='ico/save.png' border='0' title='Update Record' width='18' height='18'></a>&nbsp;&nbsp;&nbsp;
                             <a href ='javascript:refreshrecord();'  border='0' ><img src='ico/back.png' border='0' title='Cancel Editing' width='18' height='18'></a>
                       </td></tr>" ;



       }else{
       $colorbg ="#FFFFFF";


       $entrydata .="<tr id='tr_".$row['id']."'  name='tr_".$row['id']."'>

                      <td style='background-color:$colorbg;border:1px #D2D2D2 solid;'><font>&nbsp;&nbsp;&nbsp;&nbsp;".$row["lookcode"]."</font></td>
                      <td style='background-color:$colorbg;border:1px #D2D2D2 solid;'><font>&nbsp;&nbsp;&nbsp;&nbsp;".$row["looktype"]."</font></td>";


       $entrydata .= "<td style='background-color:$colorbg;border: 1px solid #ccc;'>";
       if($update == "true")
       $entrydata .=" <a href='javascript:updaterecord(\"".$row['id']."\");'  border='0' ><img src='ico/edit.png' title='Edit' width='18' height='18'></a></a>&nbsp;&nbsp;&nbsp;";
       if($delete == "true")
       $entrydata .= "<a href='javascript:deleterecord(\"".$row['id']."\");'  border='0' ><img src='ico/remove.png' title='Delete' width='18' height='18'></a>";
       $entrydata .= "</td>";

       //<a href='javascript:deleterecord(\"".$row['id']."\");'  border='0' ><img src='ico/remove.png' title='Delete' width='18' height='18'></a>&nbsp;&nbsp;&nbsp;

       $entrydata .="</tr> ";

      }
                      $total_rows--;
                      $i++;
     }
     $entrydata .= "</tbody>
                    </table><br>
                    <div align='right'>";

     echo $entrydata;

    $total_records = mysqli_num_rows($page_result);
    $total_pages = ceil($total_records/$record_per_page);
    echo "<n>Page ".$page." of ".$total_pages."</n>";
    $start_loop = $page;
    $difference = $total_pages - $page;
    if($total_records>$record_per_page){
    if($difference <= 5)
    {
     $start_loop = $total_pages - 5;
    }
    $end_loop = $start_loop + 4;
    if($page > 1)
    {
     echo "<a  href='fmlookup.php?pr=$pr&page=1' style='padding:8px 16px;border:1px solid #ccc;color:#333;'   >First</a>";
     echo "<a href='fmlookup.php?pr=$pr&page=".($page - 1)."' style='padding:8px 16px;border:1px solid #ccc;color:#333;'   ><<</a>";
    }
    for($i=$page; $i<=$end_loop; $i++)
    {
     if($i==$page){
       $x=" style='background-color:#438EB9;padding:8px 16px;border:1px solid #ccc;color:#333;'";
       $font="#fff";
     }else{
       $x=" style='padding:8px 16px;border:1px solid #ccc;color:#333;'";
       $font="#000";
     }
     echo "<a $x href='fmlookup.php?pr=$pr&page=".$i."' style='padding:8px 16px;border:1px solid #ccc;color:#333;'   ><font style='font-size: 13px;color:$font'>".$i."</font></a>";
    }
   // echo $page."/".$end_loop;
    if($page <= $end_loop)
    {
     echo "<a href='fmlookup.php?pr=$pr&page=".($page + 1)."' style='padding:8px 16px;border:1px solid #ccc;color:#333;'   >>></a>";
     echo "<a href='fmlookup.php?pr=$pr&page=".$total_pages."'style='padding:8px 16px;border:1px solid #ccc;color:#333;'   >Last</a>";
    }
   }
    echo "<n>Result of ".$start_from."-".$record_per_page." of total ".$total_records." Records</n></div>";

    ?>



<br></div</div></section></form>
 </body>
</html>
<script type='text/javascript'>
$(function(){
  $('#articlename').select2().on('change', function(e) {
        var optionSelected = $("option:selected", this);
        var valueSelected = this.value;
        var str = valueSelected.split(":");
        $("#txt_A_articlecode").val(str[0]);
        $("#txt_A_uom").val(str[1]);
        $("#txt_A_uomcode").val(str[1]);
        $("#txt_A_articlename").val(str[4]);
  });
});
</script>
<script>
      $(document).ready(function()  {
        $("#txt_A_stock").blur(function()  {
          $("#txt_A_opstock").val($("#txt_A_stock").val());
        });
      });
</script>
<?php
function GetStore($year){
         $CMB .= "<span style='margin-top:5px;float:left'>Store Name :&nbsp;&nbsp;</span>";
         $CMB .= "<select name='cmb_A_projectstore' class='form-control select' id='cmb_A_projectstore' style='padding-left:5px;padding-right:5px;margin-top:1px;width:50%;'  onchange='javascript:refreshrecord();'>";
         $CMB .= "<option value=''>Select</option>";
         $SEL =  "select name from in_store order by id";
         $RES = mysqli_query($con,$SEL);
         while ($ARR = mysqli_fetch_array($RES)) {
                $SEL = "";
                if(strtoupper($year) == strtoupper($ARR['name'])){ $SEL =  "SELECTED";}
                $CMB .= "<option value='".$ARR['name']."' $SEL >".$ARR['name']."</option>";
         }
         $CMB .= "</select>";
         return $CMB;

}

?>
<script>
$(document).ready(function() {
$("#sortTable tr").click(function(){
   $(this).addClass('selected').siblings().removeClass('selected');
   var value=$(this).find('td:first').html();

});
$("#sortTable").tablesorter();
}
);

function newrecord(){
             var tradd=document.getElementById('tradd');
             tradd.style.display="table-row";
             document.getElementById('txt_A_name').value='';
             document.getElementById('txt_A_price').value='';
}
function refreshrecord(){
           var childid='';
           document.frmEdit.action='fmlookup.php?pr=<?php echo $pr;?>&CHILDID='+childid+'&mysearch='+document.getElementById('mysearch').value+'&page='+<?php echo $page; ?>;
           document.frmEdit.submit();
}
function updaterecord(childid){

    document.frmEdit.action='fmlookup.php?pr=<?php echo $pr;?>&mysearch='+document.getElementById('mysearch').value+'&CHILDID='+childid+'&page='+<?php echo $page; ?>;
   document.frmEdit.submit();
}
function deleterecord(childid){

        alertify.confirm("Are you sure you want to delete ?", function (e) {
         if (e) {
           document.frmEdit.action='fmlookup.php?pr=<?php echo $pr;?>&DEL=DELETE&CHILDID='+childid+'&page='+<?php echo $page; ?>;
           alertify.error("Record Deleted");
           window.setTimeout(function() { document.frmEdit.submit(); }, 800);
         } else {
            return;
         }

       });
}
function postrecord(childid){

        alertify.confirm("Are you sure you want to Post ?", function (e) {
         if (e) {
           document.frmEdit.action='fmlookup.php?pr=<?php echo $pr;?>&DEL=POST&CHILDID='+childid+'&page='+<?php echo $page; ?>;
           alertify.alert("Record Posted");
           window.setTimeout(function() { document.frmEdit.submit(); }, 800);
         } else {
            return;
         }

       });
}
function editingrecord(){

       var txt_A_looktype=document.getElementById('txt_A_looktype');
       if(txt_A_looktype){
          if ((txt_A_looktype.value==null)||(txt_A_looktype.value=="")){
               alertify.alert("Enter Look Type", function () {
               txt_A_looktype.focus();

          });
             return;
          }
          else{
		  	txt_A_looktype.value = txt_A_looktype.value.toUpperCase();
		  }
       }

       var parameter =get(document.frmEdit);

       insertfunction(parameter)
}
                   var xmlHttp
                   function insertfunction(parameters)
                   {

                          xmlHttp=GetXmlHttpObject()
                          if (xmlHttp==null)
                          {
                                 alert ("Browser does not support HTTP Request")
                                 return
                          }


                          var url="in_action.php"+parameters
                          xmlHttp.onreadystatechange=stateChangedsave
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
                                alertify.success("Record Saved");
                                 document.frmEdit.action='fmlookup.php?pr=<?php echo $pr;?>&page='+<?php echo $page; ?>;
                                 window.setTimeout(function() { document.frmEdit.submit(); }, 800);

                               }else if(s1.toString() == s3.toString()){
                                alertify.success("Record Updated");
                                document.frmEdit.action='fmlookup.php?pr=<?php echo $pr;?>&dr=edit&ID='+document.getElementById('mode').value+'&page='+<?php echo $page; ?>;
                                window.setTimeout(function() { document.frmEdit.submit(); }, 800);
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
function filtersublist(objEvent){
                     var iKeyCode;
                     if(window.event){
                        iKeyCode = objEvent.keyCode;
                     }else if(objEvent.which){
                           iKeyCode = objEvent.which;
                     }
                     if (iKeyCode==13) {
                         window.location.href='fmlookup.php?pr=<?php echo $pr;?>&mysearch='+document.getElementById('mysearch').value+'&page='+<?php echo $page; ?>;
                         return false;
                     }
}
function getlookcode(catname){
  document.getElementById('txt_A_lookcode').value=  catname.value;
}
 $(window).load(function(){
                   boxHeight()
                   $(window).resize(function(){
                     boxHeight();
                   })

                });
                function boxHeight(){
                    var height = $("#content-wrapper-id",parent.document).height();
                    $('#section-content-id').height(height);
                    var boxheight = height;


                    boxheight = boxheight-80;
                    $('#box-body-id').slimScroll({
                      height: boxheight +'px',
                      wheelStep: 100,
                      alwaysVisible: true

                    });


                }

</script>