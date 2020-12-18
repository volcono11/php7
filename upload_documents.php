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
           document.frmChildEdit.action='upload_documents.php?entitytype='+document.getElementById('txt_A_entitytype').value+'&id1=<?php echo isset($_GET['id1']); ?>&DEL=DELETE&CHILDID='+childid+'&ID='+document.getElementById('txt_A_docid').value;
           document.frmChildEdit.submit();
         } else {
            return;
         }

       });
}



function updateChildrecord(childid){

    document.frmChildEdit.action='upload_documents.php?entitytype='+document.getElementById('txt_A_entitytype').value+'&id1=<?php echo isset($_GET['id1']); ?>&CHILDID='+childid+'&ID='+document.getElementById('txt_A_docid').value;
    document.frmChildEdit.submit();
}


function deletedocChildrecord(childid){

        alertify.confirm("Are you sure you want to delete this attachment ?", function (e) {
         if (e) {
           document.frmChildEdit.action='upload_documents.php?entitytype='+document.getElementById('txt_A_entitytype').value+'&id1=<?php echo isset($_GET['id1']); ?>&DOCCHILDID='+childid+'&ID='+document.getElementById('txt_A_docid').value;
           document.frmChildEdit.submit();
         } else {
            return;
         }

       });



}

function addSlot(){
                var slotval = document.getElementById('selectslots').value;
                var spanid = document.getElementById('ulslots');
               if(slotval >1)
               {
                       spanid.innerHTML = '';
                       for(var i=1;i<slotval;i++)
                        {
                       spanid.innerHTML += '<br><input name="userfile'+(i)+'" id="userfile'+(i)+'" type="file"  class="textboxcombo" value="" />';
                              // alert('<br><input name="userfile'+(i)+'" id="userfile'+(i)+'" type="file" size="50" />');
                        }
               }
          else
             spanid.innerHTML = '';
}

function editingChildrecord(){
       var cmb_A_documenttype=document.getElementById('cmb_A_documenttype');
       if(cmb_A_documenttype){
          if ((cmb_A_documenttype.value==null)||(cmb_A_documenttype.value=="")){
               alertify.alert("Select Document Type", function () {
               cmb_A_documenttype.focus();

          });
             return;
          }
       }
       var cmb_A_issuestatus=document.getElementById('cmb_A_issuestatus');
       if(cmb_A_issuestatus.value=='Yes') {
                            var txd_A_issuedate=document.getElementById('txd_A_issuedate');
                            if(txd_A_issuedate){
                               if ((txd_A_issuedate.value==null)||(txd_A_issuedate.value=="")){
                                    alertify.alert("Select Issue Date", function () {
                                    txd_A_issuedate.focus();

                               });
                                  return;
                               }
                            }
       }
       var cmb_A_expirystatus=document.getElementById('cmb_A_expirystatus');
       if(cmb_A_expirystatus.value=='Yes'){
       var txd_A_expirydate=document.getElementById('txd_A_expirydate');
       if(txd_A_expirydate){
          if ((txd_A_expirydate.value==null)||(txd_A_expirydate.value=="")){
               alertify.alert("Select Expiry Date", function () {
               txd_A_expirydate.focus();

          });
             return;
          }
       }
      }
             var str1  = document.getElementById("txd_A_issuedate").value;
             var str2  = document.getElementById("txd_A_expirydate").value;
                 if(str2!="00-00-0000" || str2!=""){
                     var dt1   = parseInt(str1.substring(0,2),10);
                     var mon1  = parseInt(str1.substring(3,5),10);
                     var yr1   = parseInt(str1.substring(6,10),10);
                     var dt2   = parseInt(str2.substring(0,2),10);
                     var mon2  = parseInt(str2.substring(3,5),10);
                     var yr2   = parseInt(str2.substring(6,10),10);
                     var date1 = new Date(yr1, mon1, dt1);
                     var date2 = new Date(yr2, mon2, dt2);

                     var datediff=((date2-date1)/(1000*60*60*24))+1;

                     if(date2 < date1){
                          alertify.alert("Issue date cannot be less than Expiry date");
                          return;
                    }
                 }

             var childid  = document.getElementById('childid').value ;
             var slotval = document.getElementById('selectslots').value;
             var y;
   document.getElementById('frmChildEdit').action='in_action.php'+get(document.frmChildEdit);
   document.getElementById('frmChildEdit').submit();
   return;
   //insertChildfunction(get(document.frmChildEdit))
}

                  /*function GetXmlHttpObject()
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
                   }*/
                  /* function insertChildfunction(parameters)
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
                                  document.frmChildEdit.action='upload_documents.php?entitytype='+document.getElementById('txt_A_entitytype').value+'&id1=<?php echo $_GET['id1']; ?>&ID='+document.getElementById('txt_A_docid').value;
                                  document.frmChildEdit.submit();
                                 });
                               }else if(s1.toString() == s3.toString()){
                                alertify.alert("Record Updated", function () {
                                  document.frmChildEdit.action='upload_documents.php?entitytype='+document.getElementById('txt_A_entitytype').value+'&id1=<?php echo $_GET['id1']; ?>&ID='+document.getElementById('txt_A_docid').value;
                                  document.frmChildEdit.submit();
                                 });
                               }else{
                                alertify.alert(s1);
                               }

                         }

                   }*/


function filtersublist(objEvent){
                     var iKeyCode;
                     if(window.event){
                        iKeyCode = objEvent.keyCode;
                     }else if(objEvent.which){
                           iKeyCode = objEvent.which;
                     }
                     if (iKeyCode==13) {
                         window.location.href='upload_documents.php?entitytype='+document.getElementById('txt_A_entitytype').value+'&ID=<?php echo $_REQUEST['ID'];?>&search='+document.getElementById('txtsearch').value;
                         return false;
                     }
}

function getDateExpiry(cattype){

 if(cattype=='No'){
  document.getElementById('txd_A_expirydate').disabled=true;
  document.getElementById('txt_A_alertbefore').disabled=true;
  document.getElementById('txd_A_expirydate').value="";
 }else{
  document.getElementById('txd_A_expirydate').disabled=false;
  document.getElementById('txt_A_alertbefore').disabled=false;
 }
}
function getDateIssue(cattype){

 if(cattype=='No'){
  document.getElementById('txd_A_issuedate').disabled=true;
  document.getElementById('txd_A_issuedate').value="";
 }else{
  document.getElementById('txd_A_issuedate').disabled=false;

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
</head>
<body>
<section class="content" id='content-content-id' style='padding-right:5px;padding-left:5px;' >
   <div class="box-body" id='box-body-id' style='background-color:#ffffff;'>
          <div class='table-responsive'>
                  <?php
			
			$PARENTID = isset($_REQUEST['ID']) ? $_REQUEST['ID'] : '';
			
			$contractstatus = GetContractStatus($PARENTID);

			$entitytype = isset($_REQUEST['entitytype']) ? $_REQUEST['entitytype'] : '';
			
			$CHILDID = isset($_REQUEST['CHILDID']) ? $_REQUEST['CHILDID'] : '';

			$DOCCHILDID = isset($_REQUEST['DOCCHILDID']) ? $_REQUEST['DOCCHILDID'] : '';

			$DEL = isset($_REQUEST['DEL']) ? $_REQUEST['DEL'] : '';

			$frmPage_startrow = isset($_REQUEST['frmPage_startrow']) ? $_REQUEST['frmPage_startrow'] : '';

			$formlistname = "upload_documents.php";

			$grid = new MyPHPGrid('frmPage');

			$grid->formName = "upload_documents.php";

			$grid->inpage = $frmPage_startrow;

			$grid->TableNameChild = "tbl_documents";

			$grid->SyncSession($grid);


			$display="none";
			if($CHILDID !='' && $DEL !='DELETE'){
					$display="table-row";
					$disable="";
					$disable1="";
			        
			        $SEL12 = "Select *,DATE_FORMAT(issuedate,'%d-%m-%Y') as issuedate,DATE_FORMAT(expirydate,'%d-%m-%Y') as expirydate from tbl_documents where id ='".$CHILDID."' and entitytype='".$entitytype."'";
			        $dis12 = mysqli_query($con,$SEL12);
			        while ($arr12 = mysqli_fetch_array($dis12)) {
			                                       $entitytype=$arr12['entitytype'];
			                                       $documenttype= $arr12['documenttype'];
			                                       $documentreference=$arr12['documentreference'];
			                                       $issuedate= $arr12['issuedate'];
			                                       $expirydate= $arr12['expirydate'];
			                                       $remarks= $arr12['remarks'];
			                                       if($issuedate=='00-00-0000')$issuedate="";
			                                       if($expirydate=='00-00-0000')$expirydate="";
			                                       $expirystatus= $arr12['expirystatus'];
			                                       if($expirystatus=="No")$disable="disabled";
			                                       $issuestatus = $arr12['issuestatus'];
			                                       if($issuestatus=="No")$disable1="disabled";
			                                       $docstatus  = $arr12['docstatus'];
			                                       $createdby  = $arr12['createdby'];
			        }
            }else{
	                $disable="disabled";
	                $disable1="disabled";
	                $createdby =$_SESSION['SESSuserID'];
	                $documenttype = $documentreference = $issuestatus = $expirydate = $remarks = $issuedate=$expirystatus= $docstatus = "";
	        }

            if($CHILDID !='' && $DEL =='DELETE'){

                    $DEL_SQL = "select * from tbl_attachments where docid='".$CHILDID."' and doctype='".$entitytype."'";
                    $DEL_RES = mysqli_query($con,$DEL_SQL);
                    if(mysqli_num_rows($DEL_RES)){
                    while($DEL_ARR = mysqli_fetch_array($DEL_RES)){
						unlink("uploads/".$DEL_ARR['docname']);
					}
					}
                    $Del_query="delete from tbl_attachments where docid='".$CHILDID."'  and doctype='".$entitytype."'";
                    echo UserLog("tbl_attachments",$CHILDID,$Del_query,"DELETE");
                    $Del_Result = mysqli_query($con,$Del_query)   or die(mysqli_error()."<br>".$Del_query);
                    
                    $Del_query="delete from tbl_documents where id='". $CHILDID."' and entitytype='".$entitytype."'";
                    echo UserLog("tbl_documents",$CHILDID,$Del_query,"DELETE");
                    $Del_Result = mysqli_query($con,$Del_query)   or die(mysqli_error()."<br>".$Del_query);
                    //$CHILDID="";

            }

            if($DOCCHILDID !=''){
					$DEL_SQL = "select * from tbl_attachments where id='".$DOCCHILDID."'";
                    $DEL_RES = mysqli_query($con,$DEL_SQL);
                    if(mysqli_num_rows($DEL_RES)){
                    while($DEL_ARR = mysqli_fetch_array($DEL_RES)){
						unlink("uploads/".$DEL_ARR['docname']);
					}
					}
					
                    $Del_query="delete from tbl_attachments where id='". $_REQUEST['DOCCHILDID']."'";
                    echo UserLog("tbl_attachments",$CHILDID,$Del_query,"DELETE");
                    $Del_Result = mysqli_query($con,$Del_query)   or die(mysqli_error()."<br>".$Del_query);
                    $_REQUEST['DOCCHILDID']="";

            }
            
            $Save_button = "";
			if(($insert == "true" && $CHILDID =="") || ($update == "true" && $CHILDID !=""))
            $Save_button = "<a href='javascript:editingChildrecord();'><img src='ico/save.png' title='Save' width='20' height='20'></a>
                                    &nbsp;&nbsp;<a href='?entitytype=".$entitytype."&ID=".$PARENTID."'><img src='ico/cancel.png' title='Cancel' width='20' height='20'></a>";


            $entrydata = "<div class='table-responsive no-padding' style='overflow:hidden;'><form name='frmChildEdit' method='post' id='frmChildEdit' enctype='multipart/form-data' autocomplete='off'>
                    <table class='table table-bordered table-condensed table-fixed  table-responsive' style='table-layout:fixed'>
                            <tr>
                              <td class='dvtCellLabel' style='border: 1px solid #ccc;'>Document Type :<span class='mandatory'>&nbsp;*</span></td>
                              <td style='border: 1px solid #ccc;'>".GetDocument($documenttype,$entitytype)."</td>
                              <td class='dvtCellLabel' style='border: 1px solid #ccc;'>Document Ref:</td>
                              <td style='border: 1px solid #ccc;'><input type='text' name='txt_A_documentreference' class='form-control txt' id='txt_A_documentreference' value='$documentreference'></td>


                              <td class='dvtCellLabel' style='border: 1px solid #ccc;'>Issue Date:<span class='mandatory'>&nbsp;*</span></td>
                              <td style='border: 1px solid #ccc;'>
                              <span style='float:left;'>".GetIssue($issuestatus)." </span>
                              <span style='float:left;'><input type='text' $disable1 class='form-control txt' data-provide='datepicker' maxlength=10 style='width:100px;'  onkeypress='return AllowNumeric1(event)'   name='txd_A_issuedate' id='txd_A_issuedate'   value='$issuedate' placeholder='dd-mm-yyyy' ></span>     </td>



                            </tr>
                            <tr>
                              <td class='dvtCellLabel' style='border: 1px solid #ccc;'>Expiry Date :</td>
                              <td style='border: 1px solid #ccc;'><span style='float:left;'>".GetExpiry($expirystatus)."</span>
                              <span style='float:left;'><input type='text' $disable class='form-control txt' style='width:100px;'  data-provide='datepicker' maxlength=10  onkeypress='return AllowNumeric1(event)'  name='txd_A_expirydate' id='txd_A_expirydate'   value='$expirydate' placeholder='dd-mm-yyyy' ></span></td>
                              <td class='dvtCellLabel' style='border: 1px solid #ccc;'>Status :</td>
                              <td style='border: 1px solid #ccc;'>".GetStatus($docstatus)."</td>

                               <td class='dvtCellLabel' style='border: 1px solid #ccc;' >Doc Upload:
                                  <span style='float:right'>
                                   <select name='selectslots' id='selectslots' class='form-control txt' onchange='javascript:addSlot();' style='width:40px;' >
                                         <option value='1' selected='selected' >1</option>
                                         <option value='2'>2</option>
                                         <option value='3'>3</option>
                                         <option value='4'>4</option>
                                         <option value='5'>5</option>
                                         <option value='6'>6</option>
                                         <option value='7'>7</option>
                                         <option value='8'>8</option>
                                         <option value='9'>9</option>
                                         <option value='10'>10</option>
                                   </select>
                                  </span>
                              </td>
                              <td style='border: 1px solid #ccc;'><input type='hidden' name='MAX_FILE_SIZE'><input type='file' name='userfile' id='userfile'><span id='ulslots'></span>  </td>
                            </tr>
                            <tr>
                              <td class='dvtCellLabel' style='border: 1px solid #ccc;'>Remarks:</td>
                              <td style='border: 1px solid #ccc;' colspan=4><input type='text' class='form-control txt'  id='txt_A_remarks' name='txt_A_remarks' value='$remarks'></td>
                              <td style='border: 1px solid #ccc;'>
                                     $Save_button
                                     <input type='hidden' name='txt_A_entitytype' readonly class='form-control txt' id='txt_A_entitytype' value='".$entitytype."'>
                                     <input type='hidden' name='txt_A_locationcode' class=textboxcombo  id='txt_A_locationcode' value='".$_SESSION['SESSUserLocation']."'></td>
                                     <input type='hidden' name='txt_A_companycode' class=textboxcombo id='txt_A_companycode' value='".$_SESSION['SESScompanycode']."'>
                                     <input type='hidden' class=textboxcombo name='txt_A_docid' id='txt_A_docid' value='".$PARENTID."'>
                                     <input type='hidden' class=textboxcombo name='txt_A_createdby' id='txt_A_createdby' value='".$createdby."'>
                                     <input type=hidden id=child name=child value='child'>
                                     <input type=hidden id=childid name=childid value='".$CHILDID."'>
                              </td>
                             </tr>
                            </table>
                            </form>

                    </div>";
        if(($insert == "true" && $CHILDID =="") || ($update == "true"))                                          
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
          $addsql .= " lookname like '%".$_REQUEST['search']."%'";
          $addsql .= " or documentreference like '%".$_REQUEST['search']."%'";
          $addsql .= " or tbl_documents.remarks like '%".$_REQUEST['search']."%'";
          $addsql .= " or DATE_FORMAT(issuedate,'%d-%m-%Y') like '%".$_REQUEST['search']."%'";
          $addsql .= " or DATE_FORMAT(expirydate,'%d-%m-%Y') like '%".$_REQUEST['search']."%'";
          $addsql .= ")";
         }
         
         $rows1=mysqli_num_rows(mysqli_query($con,"SELECT *,DATE_FORMAT(issuedate,'%d-%m-%Y') as issuedate,DATE_FORMAT(expirydate,'%d-%m-%Y') as expirydate FROM tbl_documents,in_lookup where tbl_documents.documenttype=in_lookup.lookcode and docid='".$PARENTID."' and entitytype='".$entitytype."' $addsql"));

         echo "<div class='box' style='border:0px;padding:0px;'>
                <div class='box-tools pull-left '>
                     <input type='text' name='txtsearch' onkeypress='return filtersublist(event);' id=txtsearch class='form-control' style='height:24px;border: 1px solid #ccc;width:200px;' placeholder='Search..' value=".$mysearch.">
                </div>
                <div class='box-tools pull-left '>
                     &nbsp;<a href='?entitytype=".$entitytype."&ID=".$PARENTID."'><img src='ico/refresh.ico'></a>
                </div>
                <div class='box-tools pull-right '>
                <ul class='pagination pagination-sm no-padding pull-right'>";

                 $total1=ceil($rows1/$limit1);
                 for($i=1;$i<=$total1;$i++)
                 {
                 if($i==$id1) { echo "<li class='active' ><a href='' style='padding-top:0px;padding-bottom:0px;padding-left:.5em;padding-right:.5em;'>".$i."</a></li>"; }
                 else { echo "<li><a href='?entitytype=".$entitytype."&ID=".$_REQUEST['ID']."&id1=".$i."' style='padding-top:0px;padding-bottom:0px;padding-left:.5em;padding-right:.5em;'>".$i."</a></li>"; }
                 }
         echo "</ul>
                 </div>
                 </div>";



                 $sql = "SELECT tbl_documents.id as id,entitytype,lookname,documentreference,DATE_FORMAT(issuedate,'%d-%m-%Y') as issuedate,DATE_FORMAT(expirydate,'%d-%m-%Y') as expirydate,tbl_documents.remarks,tbl_documents.createdby
                         FROM tbl_documents,in_lookup where tbl_documents.documenttype=in_lookup.lookcode and tbl_documents.docid='".$PARENTID."' and entitytype='".$entitytype."' $addsql order by tbl_documents.id LIMIT $start1, $limit1";

                 $result = mysqli_query($con,$sql) or die(mysqli_error());

                 $entrydatatable = "<table class='table table-bordered table-condensed table-fixed table-striped table-responsive' style='border:1px #2F3C43;'>";
/*                 $entrydatatable.="<thead><tr>";
                 
if(( $entitytype=="Profitcenter Documents" && strtoupper($contractstatus)!='INACTIVE') || 
($entitytype=="Ticket Documents" && stripos(json_encode($_SESSION['role']),'SITE INCHARGE') == true) || ($entitytype=="Supplier Documents" && stripos(json_encode($_SESSION['role']),'SERVICE COORDINATOR') == true) ||
($entitytype=="Purchase Documents" && stripos(json_encode($_SESSION['role']),'SALES COORDINATOR') == true)) {
                 $entrydatatable.= "<th class='bg-light-blue' style='width:5%;text-align:left;border-bottom:1px #2F3C43 solid;border-top:1px #fff solid;color:#FFFFFF' >Edit</th>";
                 $entrydatatable.= "<th class='bg-light-blue' style='width:5%;text-align:left;border-bottom:1px #2F3C43 solid;border-top:1px #fff solid;color:#FFFFFF' >Remove</th>";
}

	
                // $entrydatatable.= "<th class='bg-light-blue' style='width:15%;text-align:left;border-bottom:1px #2F3C43 solid;border-top:1px #fff solid;color:#FFFFFF' >Entity Type</th>";
                 $entrydatatable.= "<th class='bg-light-blue' style='width:12%;text-align:left;border-bottom:1px #2F3C43 solid;border-top:1px #fff solid;color:#FFFFFF' >Document Type</th>";
                 $entrydatatable.= "<th class='bg-light-blue' style='width:12%;text-align:left;border-bottom:1px #2F3C43 solid;border-top:1px #fff solid;color:#FFFFFF' >Doc Reference</th>";
                 $entrydatatable.= "<th class='bg-light-blue' style='width:10%;text-align:left;border-bottom:1px #2F3C43 solid;border-top:1px #fff solid;color:#FFFFFF' >Issue Date</th>";
                 $entrydatatable.= "<th class='bg-light-blue' style='width:10%;text-align:left;border-bottom:1px #2F3C43 solid;border-top:1px #fff solid;color:#FFFFFF' >Expiry Date</th>";
                 $entrydatatable.= "<th class='bg-light-blue' style='width:20%;text-align:left;border-bottom:1px #2F3C43 solid;border-top:1px #fff solid;color:#FFFFFF' >Remarks</th>";
                 $entrydatatable.= "<th class='bg-light-blue' style='width:24%;text-align:left;border-bottom:1px #2F3C43 solid;border-top:1px #fff solid;color:#FFFFFF' >Documents</th>";


                 $entrydatatable.= "</tr></thead><tbody>";*/
		$display_frow = 1;
         //LOOP TABLE ROWS
	     while($loginResultArrayChild   = mysqli_fetch_array($result)){
	     	if($display_frow == 1){
	     		$entrydatatable.="<thead><tr>";
                 
				if($loginResultArrayChild['createdby'] == $_SESSION['SESSuserID']) {
                 $entrydatatable.= "<th class='bg-light-blue' style='width:5%;text-align:left;border-bottom:1px #2F3C43 solid;border-top:1px #fff solid;color:#FFFFFF' >Edit</th>";
                 $entrydatatable.= "<th class='bg-light-blue' style='width:5%;text-align:left;border-bottom:1px #2F3C43 solid;border-top:1px #fff solid;color:#FFFFFF' >Remove</th>";
				}

                 $entrydatatable.= "<th class='bg-light-blue' style='width:12%;text-align:left;border-bottom:1px #2F3C43 solid;border-top:1px #fff solid;color:#FFFFFF' >Document Type</th>";
                 $entrydatatable.= "<th class='bg-light-blue' style='width:12%;text-align:left;border-bottom:1px #2F3C43 solid;border-top:1px #fff solid;color:#FFFFFF' >Doc Reference</th>";
                 $entrydatatable.= "<th class='bg-light-blue' style='width:10%;text-align:left;border-bottom:1px #2F3C43 solid;border-top:1px #fff solid;color:#FFFFFF' >Issue Date</th>";
                 $entrydatatable.= "<th class='bg-light-blue' style='width:10%;text-align:left;border-bottom:1px #2F3C43 solid;border-top:1px #fff solid;color:#FFFFFF' >Expiry Date</th>";
                 $entrydatatable.= "<th class='bg-light-blue' style='width:20%;text-align:left;border-bottom:1px #2F3C43 solid;border-top:1px #fff solid;color:#FFFFFF' >Remarks</th>";
                 $entrydatatable.= "<th class='bg-light-blue' style='width:24%;text-align:left;border-bottom:1px #2F3C43 solid;border-top:1px #fff solid;color:#FFFFFF' >Documents</th>";
	     		
	     		$display_frow++;
				
			}

	              if($CHILDID==$loginResultArrayChild['id']){
	                  $colorbg ="#F1F1F1";
	                  $colorfc ="#000000";
	              }else{
	                  $colorbg ='#FFFFFF';
	                  $colorfc ="#5A5A5A";
	              }
	             if($loginResultArrayChild['issuedate']=="00-00-0000")$loginResultArrayChild['issuedate']="";
	             if($loginResultArrayChild['expirydate']=="00-00-0000")$loginResultArrayChild['expirydate']="";
	             $entrydatatable.= "<tr>";
	          

            if($update == "true" && $loginResultArrayChild['createdby'] == $_SESSION['SESSuserID'])
        	$entrydatatable.= "<td style='background-color:$colorbg;color:$colorfc;border:1px #ccc solid;' align='center'><a href='javascript:updateChildrecord(\"".$loginResultArrayChild['id']."\");'><img src='ico/edit.png' title='Update' width='16' height='16'></a></td>";
        	else
        	$entrydatatable.= "<td style='background-color:$colorbg;color:$colorfc;border:1px #ccc solid;' align='center'></td>";
        	if($delete == "true" && $loginResultArrayChild['createdby'] == $_SESSION['SESSuserID'])
            $entrydatatable.=" <td style='background-color:$colorbg;color:$colorfc;border:1px #ccc solid;' align='center'><a href='javascript:deleteChildrecord(\"".$loginResultArrayChild['id']."\");'><img src='ico/delete.ico' title='Remove' width='16' height='16'></a></td>";
            else
            $entrydatatable.=" <td style='background-color:$colorbg;color:$colorfc;border:1px #ccc solid;' align='center'></td>";
            
				/*if($loginResultArrayChild['createdby'] == $_SESSION['SESSuserID']){
	             $entrydatatable.= "<td style='background-color:$colorbg;color:$colorfc;border:1px #ccc solid;' align='center'><a href='javascript:updateChildrecord(\"".$loginResultArrayChild['id']."\");'><img src='ico/edit.png' title='Update' width='16' height='16'></a></td>
	                                <td style='background-color:$colorbg;color:$colorfc;border:1px #ccc solid;' align='center'><a href='javascript:deleteChildrecord(\"".$loginResultArrayChild['id']."\");'><img src='ico/remove.png' title='Remove' width='16' height='16'></a></td>";
				}*/
				
	             
	             $entrydatatable.= "<td style='background-color:$colorbg;color:$colorfc;border:1px #ccc solid;'>" . $loginResultArrayChild['lookname'] . "</td>";
	             $entrydatatable.= "<td style='background-color:$colorbg;color:$colorfc;border:1px #ccc solid;'>" . $loginResultArrayChild['documentreference'] . "</td>";
	             $entrydatatable.= "<td style='background-color:$colorbg;color:$colorfc;border:1px #ccc solid;'>" . $loginResultArrayChild['issuedate'] . "</td>";
	             $entrydatatable.= "<td style='background-color:$colorbg;color:$colorfc;border:1px #ccc solid;'>" . $loginResultArrayChild['expirydate'] . "</td>";
	             $entrydatatable.= "<td style='background-color:$colorbg;color:$colorfc;border:1px #ccc solid;'>" . $loginResultArrayChild['remarks'] . "</td>";
	             $entrydatatable.= "<td style='background-color:$colorbg;color:$colorfc;border:1px #ccc solid;'>" . getattachments($loginResultArrayChild['id'],$entitytype,$loginResultArrayChild['createdby']) . "</td>";
	             $entrydatatable.= "</tr>";

	     }

         //END TABLE
         $entrydatatable.= "</tbody></table>";
         
         echo $entrydatatable;
    ?>
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
                  <h3 style='margin-top:-5px;'>Document</h3>
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
function GetStatus($entitytype){
		global $con;
         $CMB = "<select name='cmb_A_docstatus' class='form-control txt' id='cmb_A_docstatus'>";
         //$CMB .= "<option value=''></option>";
         $SEL =  "select lookcode,lookname from in_lookup where looktype='STATUS' and lookname<>'XX' order by id";
         $RES = mysqli_query($con,$SEL);
         while ($ARR = mysqli_fetch_array($RES)) {
                $SEL = "";
                if(strtoupper($entitytype) == strtoupper($ARR['lookcode'])){ $SEL =  "SELECTED";}
                $CMB .= "<option value='".$ARR['lookcode']."' $SEL >".$ARR['lookname']."</option>";
         }
         $CMB .= "</select>";
         return $CMB;
}
function GetEntity($entitytype){
		global $con;
         $CMB = "<select name='cmb_A_entitytype' class='form-control txt' id='cmb_A_entitytype'>";
         $CMB .= "<option value=''></option>";
         $SEL =  "select lookcode,lookname from in_lookup where looktype='ENTITY TYPE' and lookname<>'XX' order by id";
         $RES = mysqli_query($con,$SEL);
         while ($ARR = mysqli_fetch_array($RES)) {
                $SEL = "";
                if(strtoupper($entitytype) == strtoupper($ARR['lookcode'])){ $SEL =  "SELECTED";}
                $CMB .= "<option value='".$ARR['lookcode']."' $SEL >".$ARR['lookname']."</option>";
         }
         $CMB .= "</select>";
         return $CMB;
}
function GetDocument($documenttype,$entitytype){
		global $con;
         //echo $looktype;
         $CMB = "<select name='cmb_A_documenttype' class='form-control txt' id='cmb_A_documenttype'>";
         $CMB .= "<option value=''>Select Document</option>";
         $addsql = "";
         $SEL =  "select lookcode,lookname from in_lookup where looktype='$entitytype' and lookname<>'XX' $addsql order by lookname";
        // $SEL =  "select lookcode,lookname from in_lookup where looktype='DOCUMENT_TYPES' and lookname<>'XX' $addsql order by lookname";
         $RES = mysqli_query($con,$SEL);
         while ($ARR = mysqli_fetch_array($RES)) {
                $SEL = "";
                if(strtoupper($documenttype) == strtoupper($ARR['lookcode'])){ $SEL =  "SELECTED";}
                $CMB .= "<option value='".$ARR['lookcode']."' $SEL >".$ARR['lookname']."</option>";
         }
         $CMB .= "</select>";
         return $CMB;
}
function getattachments($id,$entitytype,$createdby){
	global $con;
     $SEL =  "select * from tbl_attachments where docid='".$id."' and doctype='$entitytype'";
     $RES = mysqli_query($con,$SEL);
     $childslno=1;
	$entrydatatable = "";
      while ($ARR = mysqli_fetch_array($RES)) {

          if($ARR['docname']!=""){
          	$docname =str_replace(" ","%20",$ARR['docname']); 
          	$docname_arr = explode("$$$",$ARR['docname']);
            $docname_ = $docname_arr[1];
            $ext = strtolower(pathinfo($ARR['docname'], PATHINFO_EXTENSION));

            if($entitytype=='PO Documents'){
            if(stripos(json_encode($_SESSION['role']),'PURCHASE MANAGER') == true){
             $entrydatatable.= "$childslno)".$ARR['docname']." &nbsp;&nbsp;<a href='#' onclick='loadframe(\"".$ext."\",\"".$docname."\");' data-toggle='modal' data-target='#myModal'><i class='fa fa-eye' data-toggle='tooltip' data-placement='right' title='View' aria-hidden='true'></i></a>  &nbsp;&nbsp;&nbsp;&nbsp;<a  href='download.php?folder=uploads&filename=".$docname."'><i class='fa fa-download' data-toggle='tooltip' data-placement='right' title='Download' aria-hidden='true'></i></a>
                                   &nbsp;&nbsp;&nbsp;&nbsp;<a href='javascript:deletedocChildrecord(\"".$ARR['id']."\");'><img src='ico/delete.ico' title='Delete Attachment' width='16' height='16'></a><br> ";
            }else{

             $entrydatatable.= "$childslno)".$docname." &nbsp;&nbsp;<a href='#' onclick='loadframe(\"".$ext."\",\"".$docname."\");' data-toggle='modal' data-target='#myModal'><i class='fa fa-eye' data-toggle='tooltip' data-placement='right' title='View' aria-hidden='true'></i></a>
                                 &nbsp;&nbsp;&nbsp;&nbsp;<a  href='download.php?folder=uploads&filename=".$docname."'><i class='fa fa-download' data-toggle='tooltip' data-placement='right' title='Download' aria-hidden='true'></i></a>
                                    ";


            }
           } 
			else{
              $docname= $ARR['docname'];
              $str = $docname;
              $delte_atatchemt = '';
              if($createdby == $_SESSION['SESSuserID']){
			  	 $delte_atatchemt = "<a href='javascript:deletedocChildrecord(\"".$ARR['id']."\");'><img src='ico/delete.ico' title='Delete Attachment' width='16' height='16'></a>";
			  }
              $entrydatatable.= "$childslno)".$docname_." &nbsp;&nbsp;&nbsp;&nbsp;
                                 <a href='#' onclick='loadframe(\"".$ext."\",\"".$ARR['docname']."\");' data-toggle='modal' data-target='#myModal'><i class='fa fa-eye' data-toggle='tooltip' data-placement='right' title='View' aria-hidden='true'></i></a>
                                 &nbsp;&nbsp;&nbsp;&nbsp;<a  href='download.php?folder=uploads&filename=".$ARR['docname']."'><i class='fa fa-download' data-toggle='tooltip' data-placement='right' title='Download' aria-hidden='true'></i></a>
                                 &nbsp;&nbsp;&nbsp;&nbsp;$delte_atatchemt<br> ";

           }

          $childslno++;
         }
     }
     $entrydatatable = substr($entrydatatable,0,strlen($entrydatatable)-3) ;

     return $entrydatatable;
}
?>
