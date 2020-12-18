<?php
session_start();
require "connection.php";
require "pagingObj.php";
if($_REQUEST['ps'] == "1") {
  $_REQUEST['catgencode'] = "";
}

$grid = new MyPHPGrid('frmPage');
$grid->formName = "newmaterialsubcategory.php";
$grid->inpage = $_REQUEST['frmPage_startrow'];
$grid->TableName = "in_productcategory";
$grid->SyncSession($grid);
$formlistname="newmaterialsubcategory.php";

if($_REQUEST["frmPage_rowcount"]=="") $selected1 ="selected='selected'";
if($_REQUEST["frmPage_rowcount"]=="10") $selected1 ="selected='selected'";
if($_REQUEST["frmPage_rowcount"]=="20") $selected2 ="selected='selected'";
if($_REQUEST["frmPage_rowcount"]=="30") $selected3 ="selected='selected'";
if($_REQUEST["frmPage_rowcount"]=="40") $selected4 ="selected='selected'";
if($_REQUEST["frmPage_rowcount"]=="50") $selected5 ="selected='selected'";


if($_REQUEST['CHILDID'] !='' && $_REQUEST['DEL'] =='DELETE'){

     $Del_query="delete from in_productcategory where id='". $_REQUEST['CHILDID']."'";
     $Del_Result = mysql_query($Del_query)   or die(mysql_error()."<br>".$Del_query);
     $_REQUEST['CHILDID']="";

}
if($_REQUEST['CHILDID'] !='' && $_REQUEST['DEL'] =='POST'){

     $Del_query="update in_productcategory set posted='YES' where id='". $_REQUEST['CHILDID']."'";
     $Del_Result = mysql_query($Del_query)   or die(mysql_error()."<br>".$Del_query);
     $_REQUEST['CHILDID']="";

}
if($_REQUEST["frmPage_rowcount"]==''){
$record_per_page=10;
}else{
$record_per_page = $_REQUEST["frmPage_rowcount"];
}
$page = '';
if($_REQUEST["page"]!=''){
 $page = $_REQUEST["page"];
}else{
 $page = 1;
}
//echo $page;
$start_from = ($page-1)*$record_per_page;
if($_REQUEST['catgencode']!=""){
  $addsql .= " and catgencode = '".$_REQUEST['catgencode']."'" ;
}
if($_REQUEST['mysearch']!=""){
    $addsql .= " and (";
    $addsql .= "  categorycode like '%".$_REQUEST['mysearch']."%'";
    $addsql .= " or categoryname like '%".$_REQUEST['mysearch']."%'";
    $addsql .= ")";
}
$query = "SELECT * from in_productcategory where catgencode <>'XX' $addsql  order by id DESC LIMIT $start_from, $record_per_page";
$result = mysql_query( $query);

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
</script>
<body>
<section class='content-header' style='margin-top:5px;'>
<h2 class='title'>MATERIAL CATEGORY</h2>
</section>
<?

$entrydata .= "<form name='frmEdit' id='frmEdit'  method='post'>
                 <section class='content' id='section-content-id' style='padding-right:5px;padding-left:5px;background-color:#fff;' >
                          <div class='box box-primary' id='box-content-id'  >
                            <div class='box-body no-padding' id='box-body-id' style='overflow:hidden;' >
                             <table class='table table-condensed ' style='margin-top:3px;' border='0'>

                                             <tr>
                                             <td width='40%'>".GetGroup($_REQUEST['catgencode'])."</td>
                                             <td width='30%'></td>
                                                <td  width='25%'>
                                                      <div class='input-group input-group-sm pull-right'>
                                                                  <input type='text' style='padding-left:5px;padding-right:5px;margin-top0px;height:30px;' onkeypress='return filtersublist(event);' name='mysearch' id=mysearch class='form-control pull-right' value='".$_REQUEST['mysearch']."' placeholder='Search..'>

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
                              <th style='width:20%;border-right:1px solid #ccc;cursor: pointer;'><font style='color:#fff'>Group Name</font></th>
                              <th style='width:20%;border-right:1px solid #ccc;cursor: pointer;'><font style='color:#fff'>Category Code</font></th>
                              <th style='width:40%;border-right:1px solid #ccc;cursor: pointer;'><font style='color:#fff'>Category Name</font></th>
                              <th style='width:10%;border-right:1px solid #ccc;cursor: pointer;'><font style='color:#fff'>Sub Level</font></th>
                              <th style='width:10%;border-right:1px solid #ccc;'><font style='color:#fff'>Action</font></th>
                             </tr>
                             ";
         if($_REQUEST['CHILDID']=='' && $_REQUEST['catgencode'] !=''){
                  $SQL   = "SELECT max(categorycode) as categorycode FROM in_productcategory WHERE
                          catgencode='".$_REQUEST["catgencode"]."' and catgencode<>'XX' order by id";
                  $SQLRes =  mysql_query($SQL) or die(mysql_error()."<br>".$SQL);
                  if(mysql_num_rows($SQLRes)>=1){
                       $loginResultArray   = mysql_fetch_array($SQLRes);
                       $catgencodes = substr($loginResultArray['categorycode'], -3, strlen($loginResultArray['categorycode']));

                       $catgencodesl =  $catgencodes*1+1 ;
                      // echo $loginResultArray['categorycode']."/".$catgencodes;
                       $catgencodesl = str_pad($catgencodesl, 3, "0", STR_PAD_LEFT);
                       $categorycode = $_REQUEST["catgencode"].$catgencodesl;
                  }
            $entrydata .= " <tr>
                             <td style='background-color:$colorbg;border: 1px solid #ccc;'>".GetGroupedit($_REQUEST['catgencode'])."</td>
                             <td style='border: 1px solid #ccc;'><input type='text' class='form-control txt inputs' disabled id='txt_A_categorycode' name='txt_A_categorycode' onkeypress='return AllowNumeric1(event)'  value='$categorycode'/></td>
                             <td style='border: 1px solid #ccc;'><input type='text' class='form-control txt inputs' id='txt_A_categoryname' name='txt_A_categoryname'  value=''/></td>
                             <td style='background-color:$colorbg;border: 1px solid #ccc;'>".GetYesno($_REQUEST['cmb_A_sublevel'])."</td>
                             <td style='border: 1px solid #ccc;'> <a href ='javascript:editingrecord();'  border='0' ><img src='ico/add-1-icon.png' title='New Entry' width='20' height='20'></a>&nbsp;</td>
                             </tr>";



        }
     $entrydata .= "  </thead>

                             <input type='hidden' name='mode' class=textboxcombo id='mode' value='".$_REQUEST['CHILDID']."'>
                             <input type='hidden' name='modeid' class=textboxcombo id='modeid' value='save'> </thead>
                             <tbody>";

    $page_query = "SELECT * from in_productcategory where catgencode <>'XX' $addsql  ORDER BY id DESC";
    $page_result = mysql_query($page_query);
    $total_rows = mysql_num_rows($page_result);
    $total_rows=$total_rows-(($page-1)*$record_per_page);
    $i=1;
     while($row = mysql_fetch_array($result))
     {


       if($row['id']==$_REQUEST['CHILDID']){
        $colorbg ="#F2F2F2";
        $entrydata .= "<td style='background-color:$colorbg;border: 1px solid #ccc;'>".GetGroupedit($row['catgencode'])."</td>
                       <td style='background-color:$colorbg;border: 1px solid #ccc;'><input type='text' class='form-control txt inputs' id='txt_A_categorycode' name='txt_A_categorycode' value='".$row['categorycode']."'/></td>
                       <td style='background-color:$colorbg;border: 1px solid #ccc;'><input type='text' class='form-control txt inputs' id='txt_A_categoryname' name='txt_A_categoryname' value='".$row['categoryname']."'/></td>
                       <td style='background-color:$colorbg;border: 1px solid #ccc;'>".GetYesno($row['sublevel'])."</td>
                       <td style='border: 1px solid #ccc;'>

                             <a href ='javascript:editingrecord();'  border='0' ><img src='ico/save.png' border='0' title='Update Record' width='18' height='18'></a>&nbsp;&nbsp;&nbsp;
                             <a href ='javascript:refreshrecord();'  border='0' ><img src='ico/back.png' border='0' title='Cancel Editing' width='18' height='18'></a>
                       </td>" ;



       }else{
       $colorbg ="#FFFFFF";


       $entrydata .="<tr id='tr_".$row['id']."'  name='tr_".$row['id']."'>
                      <td style='background-color:$colorbg;border:1px #D2D2D2 solid;'><font style='color:$fontcolr'>".getgroupname($row["catgencode"])."</font></td>
                      <td style='background-color:$colorbg;border:1px #D2D2D2 solid;'><font style='color:$fontcolr'>&nbsp;&nbsp;".$row["categorycode"]."</font></td>
                      <td style='background-color:$colorbg;border:1px #D2D2D2 solid;'><font style='color:$fontcolr'>&nbsp;&nbsp;&nbsp;&nbsp;".$row["categoryname"]."</font></td>
                      <td style='background-color:$colorbg;border:1px #D2D2D2 solid;'><font style='color:$fontcolr'>&nbsp;&nbsp;&nbsp;&nbsp;".$row["sublevel"]."</font></td>";


       $entrydata .="<td style='background-color:$colorbg;border: 1px solid #ccc;'>
                      <a href='javascript:updaterecord(\"".$row['id']."\");'  border='0' ><img src='ico/edit.png' title='Edit' width='18' height='18'></a></a>&nbsp;&nbsp;&nbsp;


                      </td>";

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

    $total_records = mysql_num_rows($page_result);
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
     echo "<a  href='newmaterialsubcategory.php?catgencode=".$_REQUEST['catgencode']."&code=".$_REQUEST['code']."&name=".$_REQUEST['name']."&page=1' style='padding:8px 16px;border:1px solid #ccc;color:#333;'   >First</a>";
     echo "<a href='newmaterialsubcategory.php?catgencode=".$_REQUEST['catgencode']."&code=".$_REQUEST['code']."&name=".$_REQUEST['name']."&page=".($page - 1)."' style='padding:8px 16px;border:1px solid #ccc;color:#333;'   ><<</a>";
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
     echo "<a $x href='newmaterialsubcategory.php?catgencode=".$_REQUEST['catgencode']."&code=".$_REQUEST['code']."&name=".$_REQUEST['name']."&page=".$i."' style='padding:8px 16px;border:1px solid #ccc;color:#333;'   ><font style='font-size: 13px;color:$font'>".$i."</font></a>";
    }
   // echo $page."/".$end_loop;
    if($page <= $end_loop)
    {
     echo "<a href='newmaterialsubcategory.php?catgencode=".$_REQUEST['catgencode']."&code=".$_REQUEST['code']."&name=".$_REQUEST['name']."&page=".($page + 1)."' style='padding:8px 16px;border:1px solid #ccc;color:#333;'   >>></a>";
     echo "<a href='newmaterialsubcategory.php?catgencode=".$_REQUEST['catgencode']."&code=".$_REQUEST['code']."&name=".$_REQUEST['name']."&page=".$total_pages."'style='padding:8px 16px;border:1px solid #ccc;color:#333;'   >Last</a>";
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
<?
function getgroupname($year){
        $SEL =  "select categoryname from in_productcategory where categorycode='$year' ";
         $RES = mysql_query($SEL);
         while ($ARR = mysql_fetch_array($RES)) {
                 $CMB = $ARR['categoryname'];
         }

         return $CMB;

}
function GetGroup($year){
         $CMB .= "<span style='margin-top:5px;float:left'>Select Group :&nbsp;&nbsp;</span>";
         $CMB .= "<select name='catgencode' class='form-control select' id='catgencode' style='padding-left:5px;padding-right:5px;margin-top:1px;width:50%;'  onchange='javascript:refreshrecord();'>";
         $CMB .= "<option value=''>Select</option>";
         $SEL =  "select categorycode,categoryname from in_productcategory where sublevel='Yes' order by id";
         $RES = mysql_query($SEL);
         while ($ARR = mysql_fetch_array($RES)) {
                $SEL = "";
                if(strtoupper($year) == strtoupper($ARR['categorycode'])){ $SEL =  "SELECTED";}
                $CMB .= "<option value='".$ARR['categorycode']."' $SEL >".$ARR['categoryname']."</option>";
         }
         $CMB .= "</select>";
         return $CMB;

}
function GetGroupedit($year){
        // $CMB .= "<span style='margin-top:5px;float:left'>Select Group :&nbsp;&nbsp;</span>";
         $CMB .= "<select name='cmb_A_catgencode' class='form-control select' id='cmb_A_catgencode' style='padding-left:5px;padding-right:5px;margin-top:1px;width:50%;'  onchange='javascript:refreshrecord();'>";
         //$CMB .= "<option value=''>Select</option>";
         $SEL =  "select categorycode,categoryname from in_productcategory where categorycode='$year' order by id";
         $RES = mysql_query($SEL);
         while ($ARR = mysql_fetch_array($RES)) {
                $SEL = "";
                if(strtoupper($year) == strtoupper($ARR['categorycode'])){ $SEL =  "SELECTED";}
                $CMB .= "<option value='".$ARR['categorycode']."' $SEL >".$ARR['categoryname']."</option>";
         }
         $CMB .= "</select>";
         return $CMB;

}
function GetYesno($year){
        // $CMB .= "<span style='margin-top:5px;float:left'>ame :&nbsp;&nbsp;</span>";
         $CMB .= "<select name='cmb_A_sublevel' class='form-control select' id='cmb_A_sublevel' style='padding-left:5px;padding-right:5px;margin-top:1px;width:80%;'  >";
         //$CMB .= "<option value=''>Select</option>";
         $SEL =  "select lookcode,lookname from in_lookup where looktype='YESNO' and lookcode<> 'XX' order by id";
         $RES = mysql_query($SEL);
         while ($ARR = mysql_fetch_array($RES)) {
                $SEL = "";
                if(strtoupper($year) == strtoupper($ARR['lookcode'])){ $SEL =  "SELECTED";}
                $CMB .= "<option value='".$ARR['lookcode']."' $SEL >".$ARR['lookname']."</option>";
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
           document.frmEdit.action='newmaterialsubcategory.php?CHILDID='+childid+'&mysearch='+document.getElementById('mysearch').value+'&page='+<?echo $page; ?>;
           document.frmEdit.submit();
}
function updaterecord(childid){

    document.frmEdit.action='newmaterialsubcategory.php?mysearch='+document.getElementById('mysearch').value+'&CHILDID='+childid+'&page='+<?echo $page; ?>;
    document.frmEdit.submit();
}
function deleterecord(childid){

        alertify.confirm("Are you sure you want to delete ?", function (e) {
         if (e) {
           document.frmEdit.action='newmaterialsubcategory.php?DEL=DELETE&CHILDID='+childid+'&page='+<?echo $page; ?>;
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
           document.frmEdit.action='newmaterialsubcategory.php?DEL=POST&CHILDID='+childid+'&page='+<?echo $page; ?>;
           alertify.alert("Record Posted");
           window.setTimeout(function() { document.frmEdit.submit(); }, 800);
         } else {
            return;
         }

       });
}
function editingrecord(){


       var cmb_A_catgencode=document.getElementById('cmb_A_catgencode');
       if(cmb_A_catgencode){
          if ((cmb_A_catgencode.value==null)||(cmb_A_catgencode.value=="")){
               alertify.alert("Select Group", function () {
               cmb_A_catgencode.focus();

          });
             return;
          }
       }
       var txt_A_stock=document.getElementById('txt_A_categoryname');
       if(txt_A_stock){
          if ((txt_A_stock.value==null)||(txt_A_stock.value=="")){
               alertify.alert("Enter Category Name", function () {
               txt_A_stock.focus();

          });
             return;
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
                                 document.frmEdit.action='newmaterialsubcategory.php?page='+<?echo $page; ?>;
                                 window.setTimeout(function() { document.frmEdit.submit(); }, 800);

                               }else if(s1.toString() == s3.toString()){
                                alertify.success("Record Updated");
                                document.frmEdit.action='newmaterialsubcategory.php?dr=edit&ID='+document.getElementById('mode').value+'&page='+<?echo $page; ?>;
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
                         window.location.href='newmaterialsubcategory.php?mysearch='+document.getElementById('mysearch').value+'&page='+<?echo $page; ?>;
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