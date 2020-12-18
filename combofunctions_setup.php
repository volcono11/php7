<?php
@session_start();
include "connection.php";

if($_REQUEST['level']=="documents"){ // to display images or text doc in div popup

    if($_REQUEST['docname']=="")$_REQUEST['docname']="invalid.jpg";
	$foldername = $_REQUEST['foldername'];

    if($_REQUEST['ext']!=""){
       if($_REQUEST['ext']=="docx" || $_REQUEST['ext']=="xlsx"){
          echo "<iframe src='http://docs.google.com/gview?url=$foldername/".$_REQUEST['docname']."&embedded=true' width='800px' height='600px' frameborder='0' ></iframe>";
       }elseif($_REQUEST['ext']=="dwg"){
        echo "<iframe src='//sharecad.org/cadframe/load?url=http://radiustest.cpsdubai.com/KEMOS/$foldername/".$_REQUEST['docname']."' width='800px' height='600px'  frameborder='0'></iframe>";

       }else{
          echo "<iframe src='$foldername/".$_REQUEST['docname']."' width='800px' height='500px'   frameborder='0'></iframe>";
       }

    }else{
       echo "";
    }

}


if($_REQUEST['level']=="tablefields"){ 

		$mycontrol = "";
		if($_REQUEST['table']!=""){
			$SEL =  "select fieldname from tbl_objectdbfiled where objectid = '".$_REQUEST['table']."' order by id";
	        $RES = mysqli_query($con,$SEL);
	        while($ARR = mysqli_fetch_array($RES)){
			//$mycontrol .= "<option value='".$ARR_1['fieldname']."' >".$ARR_1['fieldname']."</option>";
			$mycontrol = $ARR['fieldname']; // on 1 filed only we need  validation
			}
		}
		echo $mycontrol;
		exit;
		
}

if($_REQUEST['level']=="usersofthegroup"){ 

		$mycontrol = "<option value='' >Select</option>";
		if($_REQUEST['usergroup']!=""){
			$SEL =  "select userid,username from in_user where rolecode like '%".$_REQUEST['usergroup']."%'";
	        $RES = mysqli_query($con,$SEL);
	        while($ARR = mysqli_fetch_array($RES)){
			$mycontrol .= "<input type='checkbox' class='minimal inputs' id='userlist' name='userlist[]' value='".$ARR['userid']."'/>&nbsp;" . $ARR['username']. "<br>";
			}
		}
		echo $mycontrol;
		exit;
		
}

if($_REQUEST['level']=="formsfromobjectlist"){ 
		$SQL = "select id,objectname from tbl_objectmaster where objecttype='".$_REQUEST['objecttype']."' order by id";
		$RES_1 = mysqli_query($con,$SQL);
		$no_of_rec = mysqli_num_rows($RES_1);
		$CMB = "<option value='' >Select</option>";
		if($no_of_rec>0)
		while ($ARR_1 = mysqli_fetch_array($RES_1)) {
		 $CMB .= "<option value='".$ARR_1['id']."' >".$ARR_1['objectname']."</option>";
		}
		echo $CMB;
		exit;
}

if($_REQUEST['level']=="divisionofcompany"){ 
		$SQL = "select id,divisionname from tbl_division where companycode='".$_REQUEST['companycode']."' order by id";
		$RES_1 = mysqli_query($con,$SQL);
		$no_of_rec = mysqli_num_rows($RES_1);
		$CMB = "<option value='' >Select</option>";
		if($no_of_rec>0)
		while ($ARR_1 = mysqli_fetch_array($RES_1)) {
		 $CMB .= "<option value='".$ARR_1['id']."' >".$ARR_1['divisionname']."</option>";
		}
		echo $CMB;
		exit;
}
if($_REQUEST['level']=="addmenu_checkbox"){ 
		$SQL = "select menu_code,menu_name from tbl_menu where parentid='0' order by slno";
		/*$SQL = "select menu_code,menu_name from tbl_menu where parentid='0'
		and parentgroup=(select id from in_asset where categorycode='".$category."') and not EXISTS
		( select in_crmline.articlecode as categorycode,in_crmline.articlename as categoryname from in_crmline where in_crmline.buildingcode='".$_REQUEST['propertycode']."' and in_crmline.formtype='".$_REQUEST['txt_A_formtype']."' and
		in_crmline.invheadid='".$_REQUEST['invheadid']."' and in_crmline.category='".$category."' and in_asset.categorycode=in_crmline.articlecode

		) order by categorycode";*/
		$RES_1 = mysqli_query($con,$SQL);
		$CMB = "";
		$no_of_rec = mysqli_num_rows($RES_1);
		if($no_of_rec>0)
		while ($ARR_1 = mysqli_fetch_array($RES_1)) {
		 $CMB .= "<input type='checkbox' class='minimal inputs' id='menulist' name='menulist[]' value='".$ARR_1['menu_code']."'/>&nbsp;" . $ARR_1['menu_name']. "&nbsp;&nbsp;&nbsp;";
		}
		echo $CMB;
		exit;
}

function GetLastSqeID($tblName){
       $query = "LOCK TABLES in_sequencer WRITE";
       mysql_query($query) or die(mysql_error()."<br>".$query);
       $seqSQL = "SELECT LASTNUMBER FROM in_sequencer WHERE TABLENAME='$tblName'";
       $result=mysql_query($seqSQL) or die(mysql_error()."<br>".$seqSQL);
       $resulArr=mysql_fetch_array($result);
       $updatedSeqID=$resulArr['LASTNUMBER']+1;
       $squpdateSQL = "UPDATE in_sequencer SET LASTNUMBER=".$updatedSeqID." WHERE TABLENAME='$tblName'";
       mysql_query($squpdateSQL) or die(mysql_error()."<br>".$squpdateSQL);
       $query = "UNLOCK TABLES";
       mysql_query($query) or die(mysql_error()."<br>".$query);
       return ($updatedSeqID);
}
?>
