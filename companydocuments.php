<?php
session_start();
/*if($_SESSION['pr'] == "") {
  $_SESSION['pr'] = $_REQUEST['pr'];
}*/
require "connection.php";
//require "pagingObj.php";
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

<!----------------------------------->
<style type="text/css">
::-webkit-scrollbar {
  width: 10px;
  height: 10px;
}

/* Track */
::-webkit-scrollbar-track {
  background: #c6c6c6; 
}
 
/* Handle */
::-webkit-scrollbar-thumb {
  background: #6c9abf; 
  border-radius: 15px;
}

::-webkit-scrollbar-corner { 
/*background: rgba(0,0,0,0.5);*/
  background: #969696;  
}

/* Handle on hover */
::-webkit-scrollbar-thumb:hover {
  background: #555; 
}
/* Style the tab */
.tab {
  overflow: hidden;
  border: 1px solid #fff;
  background-color: #fff;
}

/* Style the buttons that are used to open the tab content */
.tab button {
  background-color: inherit;
  float: left;
  border: none;
  outline: none;
  cursor: pointer;
  padding: 14px 16px;
  transition: 0.3s;
}
/* Change background color of buttons on hover */
.tab button:hover {
  background-color: #fff;
}

/* Create an active/current tablink class */
.tab button.active {
  background-color: #fff;
}

/* Style the tab content */
.tabcontent {
  display: none;
  padding: 6px 12px;
  border: 1px solid #ccc;
 // border-top: none;
}
</style>
<script language="javascript">
function openFolder(evt, folder_id) {

  var i, tabcontent, tablinks;

  // Get all elements with class="tabcontent" and hide them
  tabcontent = document.getElementsByClassName("tabcontent");
  for (i = 0; i < tabcontent.length; i++) {
    tabcontent[i].style.display = "none";
  }

  // Get all elements with class="tablinks" and remove the class "active"
  tablinks = document.getElementsByClassName("tablinks");
  for (i = 0; i < tablinks.length; i++) {
    tablinks[i].className = tablinks[i].className.replace(" active", "");
  }

  folder_ = document.getElementsByClassName("fa fa-folder-open-o fa-3x");
  for (j = 0; j < folder_.length; j++) {
    folder_[j].className = folder_[j].className.replace("fa fa-folder-open-o fa-3x", "fa fa-folder fa-3x");
  }

  // Show the current tab, and add an "active" class to the button that opened the tab

  document.getElementById(folder_id).style.display = "block";
  if(document.getElementById('folder_'+folder_id).className == "fa fa-folder-open-o fa-3x")
  document.getElementById('folder_'+folder_id).className = "fa fa-folder fa-3x";
  else if(document.getElementById('folder_'+folder_id).className == "fa fa-folder fa-3x")
  document.getElementById('folder_'+folder_id).className= "fa fa-folder-open-o fa-3x";
  evt.currentTarget.className += " active";
}



</script>
<!-------------->
</head>
<body>
   <section class="content" id='content-content-id' style='padding-right:5px;padding-left:5px;' >
   <div class="box-body" id='box-body-id' style='background-color:#ffffff;'>
          <div class='table-responsive'>
<?php
//print_r($_REQUEST);
$id=$_REQUEST['ID'];
$entitytype = $_REQUEST['entitytype'];
$CompanyFolder = array();
$JobFolder = array();
$Folder = array();
$TicketFolder = array();
$entrydatatable = "";
//print_r($_REQUEST);

/*if($entitytype == "COMPANY_DOCUMENTS"){
	
	
}*/


// get upload docs 
$sql = "select * from tbl_documents where entitytype='$entitytype' and docid='".$id."' order by tbl_documents.id ";
$result = mysqli_query($con,$sql) or die(mysqli_error());
$JobFolder_upload = array();
while($loginResultArrayChild   = mysqli_fetch_array($result)){
      if($loginResultArrayChild['id'] !="") {
             $JobFolder_upload[] .= $loginResultArrayChild['id'];

      }
}
$tbl_rows_a = count($JobFolder_upload);
$s1=0;
$display2 = "";
for($j1 = 0; $j1 < $tbl_rows_a; $j1++){
        if($JobFolder_upload[$s1] != ""){
           $display2 .= getattachments($JobFolder_upload[$s1],$entitytype)."<br>";
        }
        $s1++;
}
$tabcontent = "";
if($display2!="") {//@$JobFolder_upload[0]!="" 
	
	$entrydatatable.= "<button class='tablinks' onclick='openFolder(event, ".$JobFolder_upload[0].")'>
	                  <i class='fa fa-folder fa-3x' aria-hidden='true' id='folder_".$JobFolder_upload[0]."' style='color:#520771'></i><br>Uploads
	                        </button>";
	$tabcontent .= " <div id='".$JobFolder_upload[0]."' class='tabcontent' style=' overflow: auto; height:350px;'>
	                        $display2
	                     </div>";
}
// end of code

$entrydatatable .= "</div>".$tabcontent;



echo $entrydatatable;








function getattachments($id,$entitytype){
	global$con;
	$colorfc = "blue";
	$SEL =  "select * from tbl_attachments where docid='".$id."' and doctype='$entitytype' order by id";
	$RES = mysqli_query($con,$SEL);
	$entrydatatable ="";
	if($RES->num_rows>0){
      $Attachments = array();
      while ($ARR = mysqli_fetch_array($RES)) {
          if($ARR['docname']!=""){
            $Attachments[] .= $ARR['docname'];
         }
     }

     $s=0;
     $entrydatatable = "<table  width=100% >";

     for($j = 0; $j < count($Attachments) ; $j++){
        $entrydatatable.= "<tr>";
        for($i = 0; $i < 6; $i++){
        if(@$Attachments[$s] != ""){
        $docname_arr = explode("$$$",$Attachments[$s]); 	
        $str = $docname_arr[1];
        $ext = strtolower(pathinfo($Attachments[$s], PATHINFO_EXTENSION));

        $dwld = "$str&nbsp;&nbsp;<a href='uploads/".$Attachments[$s]."' target='_blank'><i class='fa fa-eye' data-toggle='tooltip' data-placement='right' title='View' aria-hidden='true'></i></a>
        &nbsp;&nbsp;<a  href='download.php?folder=uploads&filename=".$Attachments[$s]."'><i class='fa fa-download' data-toggle='tooltip' data-placement='right' title='Download' aria-hidden='true'></i></a>";

        $display = "<i class='fa fa-file-text fa-3x' aria-hidden='true'></i><br>".$dwld;
        }
        else $display = "";
        
        $entrydatatable.= "<td style='background-color:#fff;color:$colorfc;border:1px #fff solid; width:15%; text-align:center;'>" . $display . "</td>";
        $s++;
        }
        $entrydatatable.= "</tr>";
      }
      $entrydatatable.= "</table>";
	}
      return   $entrydatatable;
}
?>
</div>
</div>

<div class='modal fade' id='myModal' tabindex='-1' role='dialog' aria-labelledby='myModalLabel' style=" margin:0 auto;">
         <div class='modal-dialog' role='document'  style="width:900px; margin:0 auto;" align=center>
            <div class='modal-content' style='width:900px; '>
                 <div class='modal-header' style='height:40px;' >
                  <button type='button' class='close' data-dismiss='modal' aria-label='Close'><span aria-hidden='true'>&times;</span></button>
                  <h3 style='margin-top:-5px;'>Attachment</h3>
                 </div>
                 <div class='modal-body lg' id='popupdiv' name='popupdiv' >
                 </div>
            </div>
         </div>
</div>

</body>
</html>


