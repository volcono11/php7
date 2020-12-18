<?php
session_start();
//$_SESSION['pr'] = isset($_REQUEST['pr']) ? $_REQUEST['pr'] : '';
require "connection.php";
require "pagingObj.php";
require "delete_action.php";
include "functions_workflow.php";

//$_SESSION['pr'] = isset($_REQUEST['pr']) ? $_REQUEST['pr'] : '';
$_SESSION['objectid'] = isset($_REQUEST['objectid']) ? $_REQUEST['objectid'] : '';

$WF = new WorkFlow($_SESSION['objectid']);
$pagerights = $WF->loadPagerights();
$_SESSION['pr'] = isset($pagerights) ? $pagerights : '';

$insert = $update = $delete = "false";
//print_r($_REQUEST);
if(false !== strpos($_SESSION['pr'],"I")) $insert = "true" ;
if(false !== strpos($_SESSION['pr'],"U")) $update = "true" ;
if(false !== strpos($_SESSION['pr'],"D")) $delete = "true" ;

            $PARENTID = isset($_REQUEST['PARENTID']) ? $_REQUEST['PARENTID'] : '';
            $TYPE = isset($_REQUEST['TYPE']) ? $_REQUEST['TYPE'] : '';

            $CHILDID = isset($_REQUEST['CHILDID']) ? $_REQUEST['CHILDID'] : '';

			$DEL = isset($_REQUEST['DEL']) ? $_REQUEST['DEL'] : '';

			$frmPage_startrow = isset($_REQUEST['frmPage_startrow']) ? $_REQUEST['frmPage_startrow'] : '';

			$formlistname = "propertyphotos.php";

			$grid = new MyPHPGrid('frmPage');

			$grid->formName = "propertyphotos.php";

			$grid->inpage = $frmPage_startrow;

			$grid->TableNameChild = "tbl_companylogo";

			$grid->SyncSession($grid);


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
       <script src="bootstrap/js/bootstrap.min.js"></script>
      <link rel="stylesheet" type="text/css" media="screen" href="css/my_styles2.css">
      <link rel="stylesheet" type="text/css" media="screen" href="css/my_styles1.css">


<script language="javascript">

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

function deleteChildrecord(childid){
	alertify.confirm("Are you sure you want to delete ?", function (e) {
         if (e) {
           document.frmChildEdit.action='propertyphotos.php?id1=<?php echo isset($_GET['id1']); ?>&DEL=DELETE&CHILDID='+childid+'&ID='+document.getElementById('txt_A_parentid').value+'&TYPE='+document.getElementById('txt_A_type').value;
           document.frmChildEdit.submit();
         } else {
            return;
         }

       });
}



function updateChildrecord(childid){

    document.frmChildEdit.action='propertyphotos.php?id1=<?php echo isset($_GET['id1']); ?>&CHILDID='+childid+'&ID='+document.getElementById('txt_A_parentid').value+'&TYPE='+document.getElementById('txt_A_type').value;
    document.frmChildEdit.submit();
}


function deletedocChildrecord(childid){

        alertify.confirm("Are you sure you want to delete this attachment ?", function (e) {
         if (e) {
           document.frmChildEdit.action='propertyphotos.php?id1=<?php echo isset($_GET['id1']); ?>&DOCCHILDID='+childid+'&ID='+document.getElementById('txt_A_parentid').value+'&TYPE='+document.getElementById('txt_A_type').value;
           document.frmChildEdit.submit();
         } else {
            return;
         }

       });



}



function editingChildrecord(){
       //alert('ff');
       var txt_A_imagefor=document.getElementById('txt_A_imagefor');
       if(txt_A_imagefor){
          if ((txt_A_imagefor.value==null)||(txt_A_imagefor.value=="")){
               alertify.alert("Enter Photo Name", function () {
               txt_A_imagefor.focus();

          });
             return;
          }
       }

             var childid  = document.getElementById('childid').value ;
             
             //alert(get(document.frmChildEdit));
   document.getElementById('frmChildEdit').action='in_action.php'+get(document.frmChildEdit);
   document.getElementById('frmChildEdit').submit();
   return;


}


function filtersublist(objEvent){
                     var iKeyCode;
                     if(window.event){
                        iKeyCode = objEvent.keyCode;
                     }else if(objEvent.which){
                           iKeyCode = objEvent.which;
                     }
                     if (iKeyCode==13) {
                         window.location.href='propertyphotos.php?ID=<?php echo $_REQUEST['ID'];?>&search='+document.getElementById('txtsearch').value+'&TYPE='+document.getElementById('txt_A_type').value;
                         return false;
                     }
}
function loadframe(ext,docname){

                var strURL="combofunctions_setup.php?level=documents&foldername=uploads&docname="+docname+"&ext="+ext;

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

</script>
<style >
	.container .gallery a img {
  float: left;
  width: 20%;
  height: auto;
  border: 2px solid #fff;
  -webkit-transition: -webkit-transform .15s ease;
  -moz-transition: -moz-transform .15s ease;
  -o-transition: -o-transform .15s ease;
  -ms-transition: -ms-transform .15s ease;
  transition: transform .15s ease;
  position: relative;
}

.container .gallery a:hover img {
  -webkit-transform: scale(1.05);
  -moz-transform: scale(1.05);
  -o-transform: scale(1.05);
  -ms-transform: scale(1.05);
  transform: scale(1.05);
  z-index: 5;
}

.clear {
  clear: both;
  float: none;
  width: 100%;
}
</style>
</head>
<body>




<section class="content" id='content-content-id' style='padding-right:5px;padding-left:5px;' >
   <div class="box-body" id='box-body-id' style='background-color:#ffffff;'>
          <div class='table-responsive'>
                  <?php


			$display="none";
			if($CHILDID !='' && $DEL !='DELETE'){
					$display="table-row";
					$disable="";
					$disable1="";
			        
			        $SEL12 = "Select * from tbl_companylogo where id ='".$CHILDID."'";
			        $dis12 = mysqli_query($con,$SEL12);
			        while ($arr12 = mysqli_fetch_array($dis12)) {
			                                       $imagefor=$arr12['imagefor'];
			                                       $photo= $arr12['docname'];
                                                   $PARENTID= $arr12['parentid'];
			        }
            }else{


	                $imagefor = $photo= "";
	        }

            if($CHILDID !='' && $DEL =='DELETE'){

                    $Del_query="delete from tbl_companylogo where id='".$CHILDID."'";
                    echo UserLog("tbl_companylogo",$CHILDID,$Del_query,"DELETE");
                    $Del_Result = mysqli_query($con,$Del_query)   or die(mysqli_error()."<br>".$Del_query);

            }

            $entrydata = "<div class='table-responsive no-padding' style='overflow:hidden;'><form name='frmChildEdit' method='post' id='frmChildEdit' enctype='multipart/form-data' autocomplete='off'>
                    <table class='table table-bordered table-condensed table-fixed  table-responsive' style='table-layout:fixed'>
                            <tr>
                              <td class='dvtCellLabel' style='border: 1px solid #ccc;'>Photo Name :<span class='mandatory'>&nbsp;*</span></td>
                              <td style='border: 1px solid #ccc;'><input type='text' name='txt_A_imagefor' class='form-control txt' id='txt_A_imagefor' value='$imagefor'></td>




                               <td class='dvtCellLabel' style='border: 1px solid #ccc;' >Photo Upld:</td>
                             <td style='border: 1px solid #ccc;'>
                             <input type='hidden' name='MAX_FILE_SIZE'><input name='userfile' class='btn-file' style='width:210px' type='file' id='userfile' onchange='Filevalidation(\"userfile\")'>
                             ".getUpFileName($photo)."</td>
                              <td style='border: 1px solid #ccc;'><a href='javascript:editingChildrecord();'><img src='ico/save.png' title='Save' width='20' height='20'></a>
                                    &nbsp;&nbsp;<a href='?ID=0&TYPE=$TYPE&PARENTID=".$PARENTID."'><img src='ico/cancel.png' title='Cancel' width='20' height='20'></a>
                                     <input type='hidden' class=textboxcombo name='txt_A_parentid' id='txt_A_parentid' value='".$PARENTID."'>
                                     <input type=hidden id=child name=child value='child'>
                                     <input type=hidden id='txt_A_type' name='txt_A_type' value='$TYPE'>
                                     <input type=hidden id=childid name=childid value='".$CHILDID."'>

                              </td>
                             </tr>
                           <!--  <tr>
                             <td style='border: 0px solid #ccc;'align='center' colspan=5>
                              ".getSlideImg($PARENTID,$TYPE)."
                              </td>
                             </tr>-->
                            <tr>
                             <td>
                             
                             
                             <div id='myCarousel' class='carousel slide' data-ride='carousel' style='width:100%'>
  
  <ol class='carousel-indicators'>
    <li data-target='#myCarousel' data-slide-to='0' class='active'></li>
    <li data-target='#myCarousel' data-slide-to='1'></li>
    <li data-target='#myCarousel' data-slide-to='2'></li>
  </ol>


  <div class='carousel-inner' style='height:150px;'>
    <div class='item active'>
      <img src='ico/pers-pac-member-join.png' alt='Los Angeles'>
    </div>

    <div class='item'>
      <img src='ico/Approve_icon.jpg' alt='Chicago'>
    </div>

    <div class='item'>
      <img src='ico/calendar_1.png' alt='New York'>
    </div>
  </div>

  <a class='left carousel-control' href='#myCarousel' data-slide='prev'>
    <span class='glyphicon glyphicon-chevron-left'></span>
    <span class='sr-only'>Previous</span>
  </a>
  <a class='right carousel-control' href='#myCarousel' data-slide='next'>
    <span class='glyphicon glyphicon-chevron-right'></span>
    <span class='sr-only'>Next</span>
  </a>
</div>
                             </td>
                             </tr>
                             
                             <tr>
                             <td>
                              
                             
                             </td>
                             </tr>
                            </table>
                            </form>

                    </div>";
                                                 

		echo $entrydata;

         //pagination
         $start1=0;
         $limit1=10;
         $id1 = isset($_GET['id1']) ? $_GET['id1'] : '';
         
         if($id1!="" ){
                 $id1=$_GET['id1'];
                 $start1=($id1-1)*$limit1;
                 
         }else{
                 $id1=1;
         }
         $addsql="";

		$mysearch = isset($_REQUEST['search']) ? $_REQUEST['search'] : '';
         if($mysearch!=""){
          $addsql = " and (";
          $addsql .= " imagefor like '%".$_REQUEST['search']."%'";
          $addsql .= " or docname like '%".$_REQUEST['search']."%'";
          $addsql .= ")";
         }
         
         $rows1=mysqli_num_rows(mysqli_query($con,"SELECT * FROM tbl_companylogo where parentid='".$PARENTID."' and type='$TYPE' $addsql"));

         echo "<div class='box' style='border:0px;padding:0px;'>
                <div class='box-tools pull-left '>
                     <input type='text' name='txtsearch' onkeypress='return filtersublist(event);' id=txtsearch class='form-control' style='height:24px;border: 1px solid #ccc;width:200px;' placeholder='Search..' value=".$mysearch.">
                </div>
                <div class='box-tools pull-left '>
                     &nbsp;<a href='?ID=".$PARENTID."'><img src='ico/refresh.ico'></a>
                </div>
                <div class='box-tools pull-right '>
                <ul class='pagination pagination-sm no-padding pull-right'>";

                 $total1=ceil($rows1/$limit1);
                 for($i=1;$i<=$total1;$i++)
                 {
                 if($i==$id1) { echo "<li class='active' ><a href='' style='padding-top:0px;padding-bottom:0px;padding-left:.5em;padding-right:.5em;'>".$i."</a></li>"; }
                 else { echo "<li><a href='?ID=".$_REQUEST['ID']."&id1=".$i."' style='padding-top:0px;padding-bottom:0px;padding-left:.5em;padding-right:.5em;'>".$i."</a></li>"; }
                 }
         echo "</ul>
                 </div>
                 </div>";



                 $sql = "SELECT * FROM tbl_companylogo where parentid='".$PARENTID."' and type='$TYPE' $addsql order by tbl_companylogo.id LIMIT $start1, $limit1";

                 $result = mysqli_query($con,$sql) or die(mysqli_error());

                 $entrydatatable = "<table class='table table-bordered table-condensed table-fixed table-striped table-responsive' style='border:1px #2F3C43;'>";

		$display_frow = 1;
         //LOOP TABLE ROWS
	     while($loginResultArrayChild   = mysqli_fetch_array($result)){
	     	if($display_frow == 1){
	     		$entrydatatable.="<thead><tr>";
                 $entrydatatable.= "<th class='bg-light-blue' style='width:5%;text-align:left;border-bottom:1px #2F3C43 solid;border-top:1px #fff solid;color:#FFFFFF' >Edit</th>";
                 $entrydatatable.= "<th class='bg-light-blue' style='width:5%;text-align:left;border-bottom:1px #2F3C43 solid;border-top:1px #fff solid;color:#FFFFFF' >Remove</th>";
                 $entrydatatable.= "<th class='bg-light-blue' style='width:12%;text-align:left;border-bottom:1px #2F3C43 solid;border-top:1px #fff solid;color:#FFFFFF' >Photo name</th>";
                 $entrydatatable.= "<th class='bg-light-blue' style='width:12%;text-align:left;border-bottom:1px #2F3C43 solid;border-top:1px #fff solid;color:#FFFFFF' >Photo</th>";

	     		
	     		$display_frow++;
				
			}

	              if($CHILDID==$loginResultArrayChild['id']){
	                  $colorbg ="#F1F1F1";
	                  $colorfc ="#000000";
	              }else{
	                  $colorbg ='#FFFFFF';
	                  $colorfc ="#5A5A5A";
	              }
	             $entrydatatable.= "<tr>";
	             $entrydatatable.= "<td style='background-color:$colorbg;color:$colorfc;border:1px #ccc solid;' align='center'><a href='javascript:updateChildrecord(\"".$loginResultArrayChild['id']."\");'><img src='ico/edit.png' title='Update' width='16' height='16'></a></td>
	                                <td style='background-color:$colorbg;color:$colorfc;border:1px #ccc solid;' align='center'><a href='javascript:deleteChildrecord(\"".$loginResultArrayChild['id']."\");'><img src='ico/remove.png' title='Remove' width='16' height='16'></a></td>";

	             $entrydatatable.= "<td style='background-color:$colorbg;color:$colorfc;border:1px #ccc solid;'>" . $loginResultArrayChild['imagefor'] . "</td>";
	             $entrydatatable.= "<td style='background-color:$colorbg;color:$colorfc;border:1px #ccc solid;'>" . getattachments($loginResultArrayChild['id']) . "</td>";
	             $entrydatatable.= "</tr>";

	     }

         //END TABLE
         $entrydatatable.= "</tbody></table>";
         
         echo $entrydatatable;
    ?>



    
    
    
    </div>
<div class='gallery' style="border: 1px solid #000;">
 
  <?php 
  // Image extensions
  $image_extensions = array('png','jpg','jpeg','gif');//'png','jpg','jpeg','gif'

  $photores=(mysqli_query($con,"SELECT * FROM tbl_companylogo where parentid='".$PARENTID."' and type='$TYPE' $addsql order by id desc"));
  while($photoarray = $photores->fetch_array()){
  	  $file = $photoarray['docname'];
  	  $photoname = $photoarray['imagefor'];
  	  // Thumbnail image path
      $thumbnail_path = 'uploads/'.$file;
  	  $image_path = 'uploads/'.$file;
 
      $thumbnail_ext = pathinfo($thumbnail_path, PATHINFO_EXTENSION);
      $image_ext = pathinfo($image_path, PATHINFO_EXTENSION);
  ?>
  <!--<a href='<?php echo $image_path; ?>'>-->

  		<a href='#' onclick='loadframe("<?php echo $image_ext; ?>","<?php echo $file; ?>");' data-toggle='modal' data-target='#myModal'>
  <img src='<?php echo $thumbnail_path; ?>' alt='' title='<?php echo $photoname; ?>' border="1" style="min-height: 50px; max-height: 130px; border: 1px solid #000;"/>
  </a>

  
  <?php
  }
  
 ?>
 <a class='left carousel-control' href='#myCarousel' data-slide='prev'>
    <span class='glyphicon glyphicon-chevron-left'></span>
    <span class='sr-only'>Previous</span>
  </a>
  <a class='right carousel-control' href='#myCarousel' data-slide='next'>
    <span class='glyphicon glyphicon-chevron-right'></span>
    <span class='sr-only'>Next</span>
  </a>
 </div>

</div>
</section>
</body>
</html>
<div class='modal fade' id='myModal' tabindex='-1' role='dialog' aria-labelledby='myModalLabel' style="width:900px; margin:0 auto;">
         <div class='modal-dialog' role='document'  style="width:900px; margin:0 auto;" align=center>
            <div class='modal-content' style='width:900px; '>
                 <div class='modal-header' style='height:40px;' >
                  <button type='button' class='close' data-dismiss='modal' aria-label='Close'><span aria-hidden='true'>&times;</span></button>
                  <h3 style='margin-top:-5px;'>Property Photo</h3>
                 </div>
                 <div class='modal-body lg' id='popupdiv' name='popupdiv' >
                 </div>
            </div>
         </div>
</div>
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
function GetContractStatus($id){
		global $con;
		$ARR = mysqli_fetch_array(mysqli_query($con,"select status from t_activitycenter where id='$id'"));
		return $ARR['status'];
}
function getSlideImg($id,$TYPE){
		global $con;
	$img="<div class='carousel slide' style='max-width:100px'> ";
	$SEL =  "select docname from tbl_companylogo where parentid='$id' and type='$TYPE' ";
    $RES = mysqli_query($con,$SEL);
    while ($ARR = mysqli_fetch_array($RES)) {
        $img.="<img class='mySlides' src='uploads/".$ARR['docname']."' style='width:100%;align:center;'>";

   }
      $img.="</div>";
		return $img;
}
function GetIssue($expirystatus){
		global $con;
         $CMB = "<select name='cmb_A_issuestatus' class='form-control txt' id='cmb_A_issuestatus'  style='width:60px;' onChange='getDateIssue(this.value)'>";
         $SEL =  "select lookcode,lookname from in_lookup_head where looktype='YESNO' and lookname<>'YY' order by lookname";
         $RES = mysqli_query($con,$SEL);
         while ($ARR = mysqli_fetch_array($RES)) {
                $SEL = "";
                if(strtoupper($expirystatus) == strtoupper($ARR['lookcode'])){ $SEL =  "SELECTED";}
                $CMB .= "<option value='".$ARR['lookcode']."' $SEL >".$ARR['lookname']."</option>";
         }
         $CMB .= "</select>";
         return $CMB;
}
function GetExpiry($expirystatus){
		global $con;
         $CMB = "<select name='cmb_A_expirystatus' class='form-control txt' id='cmb_A_expirystatus'  style='width:60px;' onChange='getDateExpiry(this.value)'>";
         $SEL =  "select lookcode,lookname from in_lookup_head where looktype='YESNO' and lookname<>'YY' order by lookname";
         $RES = mysqli_query($con,$SEL);
         while ($ARR = mysqli_fetch_array($RES)) {
                $SEL = "";
                if(strtoupper($expirystatus) == strtoupper($ARR['lookcode'])){ $SEL =  "SELECTED";}
                $CMB .= "<option value='".$ARR['lookcode']."' $SEL >".$ARR['lookname']."</option>";
         }
         $CMB .= "</select>";
         return $CMB;
}



function getattachments($id){
	global $con;
     $SEL =  "select * from tbl_companylogo where id='".$id."'";
     $RES = mysqli_query($con,$SEL);
     $childslno=1;
	$entrydatatable = "";
      while ($ARR = mysqli_fetch_array($RES)) {

          if($ARR['docname']!=""){
          	$docname =str_replace(" ","%20",$ARR['docname']);
          	$docname_arr = explode("$$$",$ARR['docname']);
            $docname_ = $docname_arr[1];
            $ext = strtolower(pathinfo($ARR['docname'], PATHINFO_EXTENSION));
            $str = $docname;
            $delte_atatchemt = '';
           // $delte_atatchemt = "<a href='javascript:deletedocChildrecord(\"".$ARR['id']."\");'><img src='ico/delete.ico' title='Delete Attachment' width='16' height='16'></a>";
            $entrydatatable.= "$childslno)".$docname_." &nbsp;&nbsp;&nbsp;&nbsp;
                                 <a href='#' onclick='loadframe(\"".$ext."\",\"".$docname."\");' data-toggle='modal' data-target='#myModal'><i class='fa fa-eye' data-toggle='tooltip' data-placement='right' title='View' aria-hidden='true'></i></a>
                                 &nbsp;&nbsp;&nbsp;&nbsp;<a  href='download.php?folder=uploads&filename=".$docname."'><i class='fa fa-download' data-toggle='tooltip' data-placement='right' title='Download' aria-hidden='true'></i></a>
                                 &nbsp;&nbsp;&nbsp;&nbsp;$delte_atatchemt<br> ";



          $childslno++;
         }
     }
     $entrydatatable = substr($entrydatatable,0,strlen($entrydatatable)-3) ;

     return $entrydatatable;
}
function getUpFileName($photo){
                 if($photo!=""){
                            $str = explode("$$$",$photo);
                            $str = substr($photo,(strlen($str[0])+3));

                            $actdocname1= str_replace(" ","%20",$photo);
                            $ext = strtolower(pathinfo($photo, PATHINFO_EXTENSION));
                            $invoiceupload_dwl = "<a href='uploads/$photo' target='_blank'><i class='fa fa-eye' data-toggle='tooltip' data-placement='right' title='View' aria-hidden='true'></i></a>
                                     &nbsp;&nbsp;".$str."<br>
                                     <a href='download.php?folder=uploads&filename=".$photo."'><i class='fa fa-download' data-toggle='tooltip' data-placement='right' title='Download' aria-hidden='true'></i></a>
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
