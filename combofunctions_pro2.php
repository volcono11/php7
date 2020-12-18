<?php
session_start();
//error_reporting(0);
include "connection.php";
$category=$_REQUEST['categorytype'];
?>

<?php
if($_REQUEST['level']=="SalesValueforSubcontract") {
	$sql = "select in_crmline.total from in_crmhead ,in_crmline 
where in_crmhead.jobno='".$_REQUEST['jobno']."' and in_crmhead.doctype='QUOTE' and in_crmline.invheadid=in_crmhead.id and in_crmline.articlecode ='".$category."'";
	$res = mysql_query($sql);
	$arr = mysql_fetch_array($res);
	
	echo $arr['total'];
	exit;
}

if($_REQUEST['level']=="PurchaseSubcategory"){
        // $CMB = " <select name='cmb_A_purchasesubcategory'  id='cmb_A_purchasesubcategory' class='form-control select' $disabled>";
         $CMB = "<option value=''></option>";
         
         if(stripos(json_encode($_SESSION['role']),'SITE INCHARGE') == true) {
		 		 $addsql = " and lookcode!='Facade Cleaning Services' and lookcode!='HK Manpower Supply' ";
		 }
		 if(stripos(json_encode($_SESSION['role']),'SALES COORDINATOR') == true && $_REQUEST['purchasetype']=="Subcontractor Purchase") {
		 		 $addsql = " and (lookcode!='Cleaning Consumables' and lookcode!='HK Equipments & Machineries' and lookcode!='Hygiene Consumables' and lookcode!='Hygiene Material Supply' ) ";
		 }
		 
		 if($category == "SPECIALIZED SERVICES"){
		 	$SEL =  "select * from in_asset where parentgroup='10154' order by id desc";
	        $RES = mysql_query($SEL);
	        while ($ARR = mysql_fetch_array($RES)) {
	            $CMB .= "<option value='".$ARR['categorycode']."' >".$ARR['categoryname']."</option>";
	        }	
		 }
		 else {
	         $SEL =  "select lookcode,lookname from in_lookup_head where looktype='$category' and lookname<>'YY' 
	         $addsql order by slno";
	         $RES = mysql_query($SEL);
	          while ($ARR = mysql_fetch_array($RES)) {
	             $CMB .= "<option value='".$ARR['lookcode']."' >".$ARR['lookname']."</option>";
	          }
		 }
        //  $CMB .= "</select>";
        echo $CMB;
        
         exit;    
}

if($_REQUEST['level']=="PurchaseSubcategory_report"){
         $CMB = "<option value=''></option>";
         
		 if($category == "SPECIALIZED SERVICES"){
		 	$SEL =  "select * from in_asset where parentgroup='10154' order by id desc";
	        $RES = mysql_query($SEL);
	        while ($ARR = mysql_fetch_array($RES)) {
	            $CMB .= "<option value='".$ARR['categorycode']."' >".$ARR['categoryname']."</option>";
	        }	
		 }
		 else {
	         $SEL =  "select lookcode,lookname from in_lookup_head where looktype='$category' and lookname<>'YY' 
	         order by slno";
	         $RES = mysql_query($SEL);
	          while ($ARR = mysql_fetch_array($RES)) {
	             $CMB .= "<option value='".$ARR['lookcode']."' >".$ARR['lookname']."</option>";
	          }
		 }
        echo $CMB;
        
         exit;    
}

/*if($_REQUEST['level']=="Projectfloorinfo"){
    
    $psql = "SELECT tbl_clientserviceproperty.remarks,tbl_clientbuilding.buildingname FROM tbl_clientserviceproperty inner join tbl_clientproperty on tbl_clientproperty.propertycode=tbl_clientserviceproperty.propertycode left join tbl_clientbuilding on tbl_clientbuilding.buildingshortname = tbl_clientserviceproperty.buildingcode where docid=(select in_crmhead.id from in_crmhead left join t_activitycenter on in_crmhead.docno=salesorderno where t_activitycenter.jobno='".$_REQUEST['jobno']."') and tbl_clientserviceproperty.propertycode='".$_REQUEST['propertytype']."' and tbl_clientserviceproperty.buildingcode = '$category'";
    $pres = mysql_query($psql);
	$parr = mysql_fetch_array($pres);	
	echo $parr['remarks']."@@@".$parr['buildingname'];
	exit;    
}*/

if($_REQUEST['level']=="buildingincharge"){
         $SEL =  "select buildingcode,buildingname,id from tbl_building where buildingtype='".$_REQUEST['buildingtype']."' and buildingcode='".$_REQUEST['buildingcode']."'";//and status!='Active' 
         $RES = mysql_query($SEL);
         $ARR = mysql_fetch_array($RES);
         
         $SQL_a = "select in_incharges.inchargename,in_user.username,in_incharges.mobile1 from in_incharges left join in_user on in_user.userid=in_incharges.inchargename where in_incharges.jobno='".$_REQUEST['jobno']."' and in_incharges.inchargetype='Site Incharge' and in_incharges.type='BUILDING' and in_incharges.docid='".$ARR['id']."' and in_incharges.inchargestatus='Active' and in_incharges.posted='YES'"; // for property- conatract :  one inchareg is active
         $RES_a = mysql_query($SQL_a);

         while($ARR_a = mysql_fetch_array($RES_a)){
         	$siteincharge_mobile = $ARR_a['mobile1'];
         	$CMB .= "<option value='".$ARR_a['inchargename']."' >".$ARR_a['username']."</option>";       	
		 }
         echo $ARR['buildingcode']."@@@".$ARR['buildingname']."@@@".$CMB."@@@".$siteincharge_mobile;
         exit;
}


if($_REQUEST['level']=="getProjectBuilding"){
    
    $psql = "select distinct(tbl_projectbuilding.buildingid) as buildingid,tbl_building.buildingcode,tbl_building.buildingname from tbl_projectbuilding left join tbl_building on tbl_building.id=tbl_projectbuilding.buildingid 
where tbl_projectbuilding.buildingstatus='Active' and tbl_projectbuilding.posted='YES' and projectcode='".$_REQUEST['projectcode']."' and tbl_projectbuilding.buildingtype='".$category."' order by tbl_building.id";
	$pres = mysql_query($psql);
	$CMB = "<option value=''></option>";
	while($parr = mysql_fetch_array($pres)){
		$CMB .= "<option value='".$parr['buildingcode']."' >".$parr['buildingname']."</option>";
	}
	
	echo $CMB;
	exit;    
}

if($_REQUEST['level']=="ProjectDetailsofcontract"){
	
	$sql = "select t_activitycenter.id,in_project.projectname,tbl_projectcontracts.projectcode,t_activitycenter.quoteno,t_activitycenter.quoteversion,jobvalue,clientcode,salesorderno,DATE_FORMAT(t_activitycenter.expstartdate,'%d-%m-%Y') as expstartdate,if((t_activitycenter.extendedto='' or t_activitycenter.extendedto='0000-00-00'),DATE_FORMAT(t_activitycenter.expenddate,'%d-%m-%Y'),DATE_FORMAT(t_activitycenter.extendedto,'%d-%m-%Y')) as expenddate,t_activitycenter.duration,t_activitycenter.durationtype,t_activitycenter.extendedperiod,tbl_projectcontracts.company,tbl_projectcontracts.division,t_activitycenter.hardthreshold,t_activitycenter.softthreshold,t_activitycenter.generalthreshold
from t_activitycenter left join tbl_projectcontracts on t_activitycenter.jobno=tbl_projectcontracts.contractcode 
left join in_project on  in_project.projectcode=tbl_projectcontracts.projectcode
where t_activitycenter.jobno='$category' and tbl_projectcontracts.contractstatus='Active' and tbl_projectcontracts.posted='YES' and in_project.status='Active'";
	$res = mysql_query($sql);
	$arr = mysql_fetch_array($res);

    $psql = "select distinct(tbl_projectbuilding.buildingtype) as propertycode,tbl_clientproperty.propertyname from tbl_projectbuilding left join tbl_clientproperty on tbl_clientproperty.propertycode=tbl_projectbuilding.buildingtype where tbl_projectbuilding.buildingstatus='Active' and projectcode='".$arr['projectcode']."' order by tbl_clientproperty.id";
	$pres = mysql_query($psql);
	$CMB .= "<option value=''></option>";
	while($parr = mysql_fetch_array($pres)){
		$CMB .= "<option value='".$parr['propertycode']."' >".$parr['propertyname']."</option>";
	}
	
	echo $arr['projectname']."@@@".$arr['quoteno']."@@@".$arr['quoteversion']."@@@".$arr['jobvalue']."@@@".$CMB."@@@".$arr['inchargename']."@@@".$arr['mobile1']."@@@".$arr['expstartdate']."@@@".$arr['expenddate']."@@@".$arr['durationtype']."@@@".$arr['duration']."@@@".$arr['projectcode']."@@@".$arr['extendedperiod']."@@@".$arr['company']."@@@".$arr['division']."@@@".$arr['hardthreshold']."@@@".$arr['softthreshold']."@@@".$arr['generalthreshold'];
	exit;    
}

if($_REQUEST['level']=="ProjectDetailsofjob"){
	
    $sql = "select t_activitycenter.projectname as projectname,t_activitycenter.projectcode as projectcode,in_user.username,t_activitycenter.propertycode as propertytype,t_activitycenter.buildingcode, t_activitycenter.propertyno as propertyno,t_activitycenter.floordetails as location,t_activitycenter.buildingname as propertyname,t_activitycenter.quoteno,t_activitycenter.quoteversion,jobvalue,in_incharges.inchargename,in_incharges.mobile1,t_activitycenter.company,t_activitycenter.division from t_activitycenter left join in_incharges on t_activitycenter.jobno=in_incharges.jobno left join in_user on in_user.userid=in_incharges.inchargename where t_activitycenter.jobno='$category' and in_incharges.inchargestatus='Active' and in_incharges.type='JOB' and in_incharges.inchargetype = 'SITE INCHARGE' ";   
    
	$res = mysql_query($sql);
	$arr = mysql_fetch_array($res);
	
	$CMB = "<option value='".$arr['inchargename']."' >".$arr['username']."</option>";
	
	//$arr['projectname'];
	echo $arr['projectname']."@@@".$arr['propertytype']."@@@".$arr['propertyno']."@@@".$arr['location']."@@@".$arr['quoteno']."@@@".$arr['quoteversion']."@@@".$CMB."@@@".$arr['jobvalue']."@@@".$arr['mobile1']."@@@".$arr['projectcode']."@@@".$arr['buildingcode']."@@@".$arr['propertyname']."@@@".$arr['company']."@@@".$arr['division'];
	exit;    
}

?>

