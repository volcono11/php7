<?php
session_start();
//$_SESSION['pr'] = isset($_REQUEST['pr']) ? $_REQUEST['pr'] : '';
require "connection.php";
require "pagingObj.php";
require "delete_action.php";

$insert = $update = $delete = "false";

if(false !== strpos($_SESSION['pr'],"I")) $insert = "true" ;
if(false !== strpos($_SESSION['pr'],"U")) $update = "true" ;
if(false !== strpos($_SESSION['pr'],"D")) $delete = "true" ;

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

function setprefixval(catval){
	if(catval == '2001'){
		document.getElementById('txt_A_prefixvalue').disabled =false;
	}
	else{
		document.getElementById('txt_A_prefixvalue').value ='';
		document.getElementById('txt_A_prefixvalue').disabled =true;
	}
}

function setsuffixval(catval){
	if(catval == '2001'){
		document.getElementById('txt_A_suffixvalue').disabled =false;
	}
	else{
		document.getElementById('txt_A_suffixvalue').value ='';
		document.getElementById('txt_A_suffixvalue').disabled =true;
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
            if((iKeyCode<=1 && iKeyCode>=7) || (iKeyCode>=9 && iKeyCode<45) || (iKeyCode>=58 && iKeyCode<=255)){
                if (iKeyCode!=13) {
                    alertify.alert('Numbers Only');
                     return false;
                }
            }
            return true;
    }


function fileTypeValidation() { 
            var fileInput =  
                document.getElementById('userfile'); 
              
            var filePath = fileInput.value; 
          
            // Allowing file type 
            var allowedExtensions =  
                    /(\.jpg|\.jpeg|\.png|\.gif)$/i; 
              
            if (!allowedExtensions.exec(filePath)) { 
                alert('Invalid file type'); 
                fileInput.value = ''; 
                return false; 
            }  
            else  
            { 
              
                // Image preview 
                if (fileInput.files && fileInput.files[0]) { 
                    var reader = new FileReader(); 
                    reader.onload = function(e) { 
                        document.getElementById( 
                            'imagePreview').innerHTML =  
                            '<img src="' + e.target.result 
                            + '"/>'; 
                    }; 
                      
                    reader.readAsDataURL(fileInput.files[0]); 
                    return "Success";
                } 
            } 
        } 
        
function Filevalidation(fileid) { 
        const fi = document.getElementById(fileid); 
        // Check if any file is selected. 
        if (fi.files.length > 0) { 
            for (const i = 0; i <= fi.files.length - 1; i++) { 
  
                const fsize = fi.files.item(i).size; 
                const file = Math.round((fsize / 1024)); 
                // The size of the file. 
                if (file > 2048) { 
                    alertify.alert( fi.files.item(i).name +"   "+
                      "File too Big, please select a file less than 2mb");
                    
                } 
                else{
                	var res= fileTypeValidation();
                	if(res == "Success")
				    return;
				}
            } 
        } 
    } 
    
function editingChildrecord(){ 

	var cmb_A_imagefor=document.getElementById('cmb_A_imagefor');
	if(cmb_A_imagefor){
	  if (cmb_A_imagefor.value==""){
	       alertify.alert("Select Logo Type", function () {
	       cmb_A_imagefor.focus();

	  });
	     return;
	  }
	}
   
   Filevalidation('userfile');
   
   document.getElementById('frmChildEdit').action='in_action.php'+get(document.frmChildEdit);
   document.getElementById('frmChildEdit').submit();
   return;
   //var parameter = get(document.frmChildEdit);
   ///insertChildfunction(parameter)

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
      xmlHttp=GetXmlHttpObject()
      if (xmlHttp==null)
      {
             alert ("Browser does not support HTTP Request")
             return
      }


      var url="in_action.php"+parameters;
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
              document.frmChildEdit.action='companylogo.php?PARENTID='+document.getElementById('txt_A_parentid').value;
              document.frmChildEdit.submit();
             });
           }else if(s1.toString() == s3.toString()){
            alertify.alert("Record Updated", function () {
              document.frmChildEdit.action='companylogo.php?PARENTID='+document.getElementById('txt_A_parentid').value;
              document.frmChildEdit.submit();
             });
           }else{
            alertify.alert(s1);
           }

     }

}

function updateChildrecord(childid){

    document.frmChildEdit.action='companylogo.php?CHILDID='+childid+'&PARENTID='+document.getElementById('txt_A_parentid').value;
    document.frmChildEdit.submit();
}

function deleteChildrecord(childid){

        alertify.confirm("Are you sure you want to delete ? ", function (e) {
         if (e) {
           document.frmChildEdit.action='companylogo.php?DEL=DELETE&CHILDID='+childid+'&PARENTID='+document.getElementById('txt_A_parentid').value;
           document.frmChildEdit.submit();
         } else {
            return;
         }

       });

}

function blockSpecialChar(e) {
            var k = e.keyCode;
            return (k!=39);
}

</script>
</head>
<section class="content" id='content-content-id' style='padding-right:5px;padding-left:5px;' >
   <div class="box-body" id='box-body-id' style='background-color:#ffffff;'>
          <div class='table-responsive'>
<?php 

$PARENTID = isset($_REQUEST['PARENTID']) ? $_REQUEST['PARENTID'] : '';

$CHILDID = isset($_REQUEST['CHILDID']) ? $_REQUEST['CHILDID'] : '';

$DEL = isset($_REQUEST['DEL']) ? $_REQUEST['DEL'] : '';

$frmPage_startrow = isset($_REQUEST['frmPage_startrow']) ? $_REQUEST['frmPage_startrow'] : '';

    $formlistname = "companylogo.php";

    $grid = new MyPHPGrid('frmPage');

    $grid->formName = "companylogo.php";

    $grid->inpage = $frmPage_startrow;

    $grid->TableNameChild = "tbl_companylogo";

    $grid->SyncSession($grid);



if($CHILDID !='' && $DEL =='DELETE'){
	    $DEL_ARR = mysqli_fetch_array(mysqli_query($con,"select * from tbl_companylogo where id='". $CHILDID."'"));
	    @unlink("uploads/".$DEL_ARR['docname']);
	    $Del_query = "delete from tbl_companylogo where id='". $CHILDID."'";
        mysqli_query($con,$Del_query);
        UserLog("tbl_companylogo",$CHILDID,$Del_query,"DELETE");
        $CHILDID ="";

}
                                  
        $sql_1 = "select * from tbl_companylogo where id='".$CHILDID."'";
        $res_1 = mysqli_query($con,$sql_1);
                                  
		if(mysqli_num_rows($res_1)>=1){
		    $arr_1 = mysqli_fetch_array($res_1);
		    $parentid=$arr_1['parentid'];
	        $docname=$arr_1['docname'];
	        $imagefor= $arr_1['imagefor'];
	        $image = "<img src='uploads/$docname' />";
		}
		else{
			$parentid = $docname = $imagefor = $image ="";
		}
        $foldername = "uploads";
                      
        $no_of_rows = mysqli_num_rows(mysqli_query($con,"select * from tbl_companylogo where parentid='".$PARENTID."'"));
        $mandatory = "<span class='mandatory'>&nbsp;*</span>";
		
		$Save_button = "";
		if(($insert == "true" && $CHILDID =="") || ($update == "true" && $CHILDID !=""))
        $Save_button = "<a href='javascript:editingChildrecord();'><img src='ico/save.png' title='Save' width='20' height='20'></a>&nbsp;&nbsp;<a href='?PARENTID=".$PARENTID."'><img src='ico/cancel.png' title='Cancel' width='20' height='20'></a>";
        
        $entrydata = "<div class='table-responsive no-padding'>
            <form name='frmChildEdit' method='post' id='frmChildEdit' autocomplete='off' enctype='multipart/form-data'>
                <table class='table table-bordered table-condensed table-fixed  table-responsive' style='table-layout:fixed'>
                    <tr>
                        <td class='dvtCellLabel' style='border: 1px solid #ccc;width:13%;'>Logo For $mandatory</td>
                        <td style='border: 1px solid #ccc;'>".GetPrintHead($imagefor)."</td>
                        <td class='dvtCellLabel' style='border: 1px solid #ccc;width:13%;'>Upload file $mandatory</td>
                        <td style='border: 1px solid #ccc;'>
                        <input type='hidden' name='MAX_FILE_SIZE'><input name='userfile' class='btn-file' style='width:210px' type='file' id='userfile' onchange='Filevalidation(\"userfile\")'>
                        ".getUpFileName($docname,$foldername)."
                        </td>
                        <td style='border: 1px solid #fff;width:10%;'>
                        ".$Save_button."
                        <input type='hidden' class=textboxcombo name='txt_A_parentid' id='txt_A_parentid' value='".$PARENTID."'>
                        <input type=hidden id=child name=child value='child'>
                        <input type=hidden id=childid name=childid value='".$CHILDID."'>
                        <input type=hidden id='txt_A_type' name='txt_A_type' value='COMPANYLOGO'>
                        </td>
                    </tr>
                    <tr>
                    <td colspan=5 style='border: 1px solid #fff;width:10%;'>
                    <div id='imagePreview'>$image</div>
                    </td>
                    </tr>
                </table>
            </form>

        </div>";

if(($insert == "true" && $CHILDID =="") || ($update == "true"))
echo $entrydata;

$start1=0;
$limit1=6;
      if(isset($_GET['id1'])){
         $id1=$_GET['id1'];
         $start1=($id1-1)*$limit1;
      }else{
         $id1=1;
      }
$addsql="";
if(isset($_REQUEST['search'])!=""){
	/*$addsql = " and (";
	$addsql .= " reasonforrevision like '%".$_REQUEST['search']."%'";
	$addsql .= ")";*/
}

$list_sql = "select tbl_companylogo.*,in_lookup.lookname as imageforname from tbl_companylogo left join in_lookup on  in_lookup.lookcode =tbl_companylogo.imagefor where tbl_companylogo.parentid='".$PARENTID."' and tbl_companylogo.type='COMPANYLOGO' $addsql";
$rows1=mysqli_num_rows(mysqli_query($con,$list_sql));

$p_rows=mysqli_num_rows(mysqli_query($con,$list_sql));

echo "<div class='box' style='border:0px;padding:0px;'>

       <div class='box-tools pull-right '>
            <ul class='pagination pagination-sm no-padding pull-right'>";

                $total1=ceil($rows1/$limit1);
                for($i=1;$i<=$total1;$i++){
                    if($i==$id1) {
                       echo "<li class='active' ><a href='' style='padding-top:0px;padding-bottom:0px;padding-left:.5em;padding-right:.5em;'>".$i."</a></li>";
                    }else {
                       echo "<li><a href='?PARENTID=".$PARENTID."&id1=".$i."' style='padding-top:0px;padding-bottom:0px;padding-left:.5em;padding-right:.5em;'>".$i."</a></li>"; }
                    }
       echo "</ul>
       </div>
       </div>";


$sql = $list_sql. " LIMIT $start1, $limit1";
$result = mysqli_query($con,$sql) or die(mysqli_error());
        $entrydatatable = "<table class='table table-bordered table-condensed table-fixed table-striped table-responsive' style='border:1px #2F3C43;'>";
        $entrydatatable.="<thead><tr>";
        $entrydatatable.= "<th class='bg-light-blue' style='width:5%;text-align:left;border-bottom:1px #2F3C43 solid;border-top:1px #fff solid;color:#FFFFFF'>Slno </th>";
        $entrydatatable.= "<th class='bg-light-blue' style='width:15%;text-align:left;border-bottom:1px #2F3C43 solid;border-top:1px #fff solid;color:#FFFFFF'>Logo for</th>";
        $entrydatatable.= "<th class='bg-light-blue' style='width:10%;text-align:left;border-bottom:1px #2F3C43 solid;border-top:1px #fff solid;color:#FFFFFF'>Logo</th>";
               
        $entrydatatable.= "<th class='bg-light-blue' style='width:5%;text-align:left;border-bottom:1px #2F3C43 solid;border-top:1px #fff solid;color:#FFFFFF'>Edit</th>";
        $entrydatatable.= "<th class='bg-light-blue' style='width:5%;text-align:left;border-bottom:1px #2F3C43 solid;border-top:1px #fff solid;color:#FFFFFF'>Del</th>";
        
        $entrydatatable.= "</tr></thead><tbody>";
        
        
        $slno = 0;
		while($loginResultArrayChild   = mysqli_fetch_array($result)){

        	$colorbg ='#FFFFFF';
        	$colorfc ='#5A5A5A';
       
        	$entrydatatable.= "<tr>";
        	$entrydatatable.= "<td style='background-color:$colorbg;color:$colorfc;border:1px #ccc solid;'>".++$slno. "</td>";
        	$entrydatatable.= "<td style='background-color:$colorbg;color:$colorfc;border:1px #ccc solid;'>" . $loginResultArrayChild['imageforname']. "</td>";
        	$entrydatatable.= "<td style='background-color:$colorbg;color:$colorfc;border:1px #ccc solid;'>" . getUpFileName($loginResultArrayChild['docname'],$foldername). "</td>";
        	if($update == "true")
        	$entrydatatable.= "<td style='background-color:$colorbg;color:$colorfc;border:1px #ccc solid;' align='center'><a href='javascript:updateChildrecord(\"".$loginResultArrayChild['id']."\");'><img src='ico/edit.png' title='Update' width='16' height='16'></a></td>";
        	else
        	$entrydatatable.= "<td style='background-color:$colorbg;color:$colorfc;border:1px #ccc solid;' align='center'></td>";
        	if($delete == "true")
            $entrydatatable.=" <td style='background-color:$colorbg;color:$colorfc;border:1px #ccc solid;' align='center'><a href='javascript:deleteChildrecord(\"".$loginResultArrayChild['id']."\");'><img src='ico/delete.ico' title='Remove' width='16' height='16'></a></td>";
            else
            $entrydatatable.=" <td style='background-color:$colorbg;color:$colorfc;border:1px #ccc solid;' align='center'></td>";

        	

        	$entrydatatable.= "</tr>";
}

$entrydatatable.= "</tbody></table>";
echo $entrydatatable;


?>       </div>

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
<script type='text/javascript'>
$(function(){
  $('#menuname').select2().on('change', function(e) {
        var optionSelected = $("option:selected", this);
        var valueSelected = this.value;
        var str = valueSelected.split(":");
        $("#txt_A_menu_name").val(str[0]);
        $("#txt_A_menu_url").val(str[1]);
        $("#txt_A_menu_icon").val(str[2]);
  });
});
</script>
<?php

function GetPrintHead($renumber){
	global $con;
    $CMB = " <select name='cmb_A_imagefor'  id='cmb_A_imagefor' class='form-control select'>";    
    $CMB .= "<option value=''>Select</option>";
	$SEL =  "Select lookcode,lookname from in_lookup where looktype='PRINTHEAD TYPE' and lookname<>'XX'";
	$RES = mysqli_query($con,$SEL);
	while ($ARR = mysqli_fetch_array($RES)) {
	   $SEL = "";
	   if($renumber == $ARR['lookcode']){ $SEL =  "SELECTED";}
	   $CMB .= "<option value='".trim($ARR['lookcode'])."' $SEL >".trim($ARR['lookname'])."</option>";
	}
	$CMB .= "</select>";
	return $CMB;
}

function getUpFileName($invoiceupload,$foldername){
                 if($invoiceupload!=""){
                            $str = explode("$$$",$invoiceupload);
                            $str = substr($invoiceupload,(strlen($str[0])+3));

                            $actdocname1= str_replace(" ","%20",$invoiceupload);
                            $ext = strtolower(pathinfo($invoiceupload, PATHINFO_EXTENSION));
                            $invoiceupload_dwl = "<a href='$foldername/$invoiceupload' target='_blank'><i class='fa fa-eye' data-toggle='tooltip' data-placement='right' title='View' aria-hidden='true'></i></a>
                                     &nbsp;&nbsp;".$str."<br>
                                     <a href='download.php?folder=$foldername&filename=".$invoiceupload."'><i class='fa fa-download' data-toggle='tooltip' data-placement='right' title='Download' aria-hidden='true'></i></a>
                                      &nbsp;&nbsp;".$str;
                            /*$invoiceupload_dwlxxxx = $str."<a href='#' onclick='loadframe(\"".$ext."\",\"".$invoiceupload."\",\"".$foldername."\");' data-toggle='modal' data-target='#myModal'><i class='fa fa-eye' data-toggle='tooltip' data-placement='right' title='View' aria-hidden='true'></i></a>
                                      &nbsp;&nbsp;<a  href='download.php?folder=$foldername&filename=".$invoiceupload."'><i class='fa fa-download' data-toggle='tooltip' data-placement='right' title='Download' aria-hidden='true'></i></a>
                                      &nbsp;&nbsp;";*/

                }else{
                            $invoiceupload_dwl = "";
                }
                return $invoiceupload_dwl;
}
?>
