<?php
session_start();
//error_reporting(0);
include "connection.php";
$_SESSION['SESSrepoptsHTML'] ="";

?>

<?php

/*if( $_REQUEST['level']=="showsubmenus"){
	
	$SQLchild = "select tbl_menu.slno,tbl_menu.menu_name,tbl_menu.menu_icon from tbl_menusetup left join tbl_menu on tbl_menusetup.menucode=tbl_menu.menu_code where tbl_menusetup.usergroupid ='".$_REQUEST['usergroupid']."' and tbl_menusetup.parentid='".$_REQUEST['menuid']."'";
	$SQLReschild =  mysqli_query($con,$SQLchild) or die(mysqli_error()."<br>".$SQLchild);
	if(mysqli_num_rows($SQLReschild) == 0){
		$sql = "select * from tbl_menu where parentid=(select menucode from  tbl_menusetup where id='".$_REQUEST['menuid']."')";
		$res = mysqli_query($con,$sql) or die(mysqli_error()."<br>".$sql);
		while($arr = mysqli_fetch_array($res)){
			$childseqID = GetLastSqeID('tbl_menusetup');
			$ins = "insert into tbl_menusetup (id,menucode,rolecode,usergroupid,slno,parentid) values 
			('$childseqID','".$arr['menu_code']."','".$_REQUEST['rolecode']."','".$_REQUEST['usergroupid']."','".$arr['slno']."','".$_REQUEST['menuid']."')";
			mysqli_query($con,$ins);
			
		}
	$SQLReschild =  mysqli_query($con,$SQLchild) or die(mysqli_error()."<br>".$SQLchild);	
	}
		$html =   "<table class='table table-condensed table-bordered' width=100%>
                      <tr>
                      <td class='dvtCellLabel' style='border: 1px solid #ccc;width:5%'><b>Slno</b></td>
                      <td class='dvtCellLabel' style='border: 1px solid #ccc;width:25%'><b>Submenu</b></td>
                      <td class='dvtCellLabel' style='border: 1px solid #ccc;width:10%'><b>Add</b></td>
                      <td class='dvtCellLabel' style='border: 1px solid #ccc;width:10%'><b>Edit</b></td>
                      <td class='dvtCellLabel' style='border: 1px solid #ccc;width:10%'><b>View</b></td>
                      <td class='dvtCellLabel' style='border: 1px solid #ccc;width:10%'><b>Delete</b></td>
                      <td class='dvtCellLabel' style='border: 1px solid #ccc;width:10%'><b>Icon</b></td>
                      </tr> ";
                      $html_data="";
        while ($loginResultArrayChild   = mysqli_fetch_array($SQLReschild)) {
           $html_data.=   "<tr>
                      <td style='border: 1px solid #ccc;'>".$loginResultArrayChild['slno']."</td>
                      <td style='border: 1px solid #ccc;'>".$loginResultArrayChild['menu_name']."</td>
                      <td style='border: 1px solid #ccc;'><input type=checkbox name=add></td>
                      <td style='border: 1px solid #ccc;'><input type=checkbox name=edit></td>
                      <td style='border: 1px solid #ccc;'><input type=checkbox name=view></td>
                      <td style='border: 1px solid #ccc;'><input type=checkbox name=delete></td>
                      <td style='border: 1px solid #ccc;'><i class='".$loginResultArrayChild['menu_icon']."' aria-hidden='true'></i></td>
                      </tr> ";
    
		}
    	echo($html.$html_data);
		
	
}*/
if( $_REQUEST['level']=="PI_Materials"){

    $SQLchild = "SELECT *,in_inventoryline.articlecode FROM in_inventoryline left join in_inventoryhead on in_inventoryhead.id=in_inventoryline.initemid
    where in_inventoryhead.id='".$_REQUEST['childid']."' ";
    $SQLReschild =  mysql_query($SQLchild) or die(mysql_error()."<br>".$SQLchild);
    $html =   "<table class='table table-condensed table-bordered' width=100%>
                      <tr>
                      <td class='dvtCellLabel' style='border: 1px solid #ccc;width:5%'><b>Slno</b></td>
                      <td class='dvtCellLabel' style='border: 1px solid #ccc;width:25%'><b>Material Code</b></td>
                      <td class='dvtCellLabel' style='border: 1px solid #ccc;width:25%'><b>Material Name</b></td>
                      <td class='dvtCellLabel' style='border: 1px solid #ccc;width:25%'><b>Unit</b></td>
                      <td class='dvtCellLabel' style='border: 1px solid #ccc;width:15%'><b>Qty</b></td>
                      </tr> ";
    $slno=1;
    while ($loginResultArrayChild   = mysql_fetch_array($SQLReschild)) {
           $html .=   "<tr>
                      <td style='border: 1px solid #ccc;width:5%'>".$slno++."</td>
                      <td style='border: 1px solid #ccc;width:25%'>".$loginResultArrayChild['articlecode']."</td>
                      <td style='border: 1px solid #ccc;width:25%'>".$loginResultArrayChild['articlename']."</td>
                      <td style='border: 1px solid #ccc;width:25%'>".GetUnitName($loginResultArrayChild['uom'])."</td>
                      <td style='border: 1px solid #ccc;width:15%'>".$loginResultArrayChild['quantity']."</td>
                      </tr> ";
    
    
    }



    $html.=   "</table>";
    echo $html;


   exit;
}
if( $_REQUEST['level']=="PO_details"){

    $SQLchild = "SELECT * FROM in_inventoryline where in_inventoryline.initemid='".$_REQUEST['childid']."' ";
    $SQLReschild =  mysql_query($SQLchild) or die(mysql_error()."<br>".$SQLchild);
    $html =   "<table class='table table-condensed table-bordered' width=100%>
                      <tr>
                      <td class='dvtCellLabel' style='border: 1px solid #ccc;width:5%'><b>Slno</b></td>
                      <td class='dvtCellLabel' style='border: 1px solid #ccc;width:25%'><b>Material</b></td>
                      <td class='dvtCellLabel' style='border: 1px solid #ccc;width:25%'><b>Quantity</b></td>
                      <td class='dvtCellLabel' style='border: 1px solid #ccc;width:25%'><b>Unit</b></td>
                      <td class='dvtCellLabel' style='border: 1px solid #ccc;width:15%'><b>Unit Price</b></td>
                      <td class='dvtCellLabel' style='border: 1px solid #ccc;width:15%'><b>Amount</b></td>
                      </tr> ";
    $slno=1;
    while ($loginResultArrayChild   = mysql_fetch_array($SQLReschild)) {
           $html .=   "<tr>
                      <td style='border: 1px solid #ccc;width:5%'>".$slno++."</td>
                      <td style='border: 1px solid #ccc;width:25%'>".$loginResultArrayChild['articlecode']."</td>
                      <td style='border: 1px solid #ccc;width:25%'>".$loginResultArrayChild['quantity']."</td>
                      <td style='border: 1px solid #ccc;width:25%'>".GetUnitName($loginResultArrayChild['uom'])."</td>
                      <td style='border: 1px solid #ccc;width:15%'>".$loginResultArrayChild['rate']."</td>
                      <td style='border: 1px solid #ccc;width:15%'>".$loginResultArrayChild['linegross']."</td>
                      </tr> ";


    }



    $html.=   "</table>";
    echo $html;


   exit;
}


if( $_REQUEST['level']=="contractassetdetails"){

    $SQLchild = "select *,in_assetdetail.assetdescription from in_ppmassets
    left join in_asset on in_asset.categorycode=in_ppmassets.assettype
    left join in_assetdetail on in_assetdetail.assetid=in_ppmassets.assetdescription
    where in_ppmassets.id='".$_REQUEST['childid']."' ";
    $SQLReschild =  mysql_query($SQLchild) or die(mysql_error()."<br>".$SQLchild);
    $loginResultArrayChild   = mysql_fetch_array($SQLReschild);

    $html =   "<table class='table table-condensed table-bordered' width=100%>
                      <tr>
                      <td class='dvtCellLabel' style='border: 1px solid #ccc;width:25%'>Asset Code</td>
                      <td style='border: 1px solid #ccc;width:25%'>".$loginResultArrayChild['assetcode']."</td>
                      <td class='dvtCellLabel' style='border: 1px solid #ccc;width:25%'>Property</td>
                      <td style='border: 1px solid #ccc;width:25%'>".$loginResultArrayChild['building']."</td>
                      </tr>
                      <tr>
                      <td class='dvtCellLabel' style='border: 1px solid #ccc;'>Floor</td>
                      <td style='border: 1px solid #ccc;'>".$loginResultArrayChild['floor']."</td>
                      <td class='dvtCellLabel' style='border: 1px solid #ccc;'>Location</td>
                      <td style='border: 1px solid #ccc;'>".$loginResultArrayChild['location']."</td>
                      </tr>
                      <tr>
                      <td class='dvtCellLabel' style='border: 1px solid #ccc;'>Service</td>
                      <td style='border: 1px solid #ccc;'>".$loginResultArrayChild['categoryname']."</td>
                      <td class='dvtCellLabel' style='border: 1px solid #ccc;'>Asset</span></td>
                      <td style='border: 1px solid #ccc;'>".$loginResultArrayChild['assetdescription']."</td>
                      </tr>
                      <tr>
                      <td class='dvtCellLabel' style='border: 1px solid #ccc;'>Brand</td>
                      <td style='border: 1px solid #ccc;'>".$loginResultArrayChild['brand']."</td>
                      <td class='dvtCellLabel' style='border: 1px solid #ccc;'>Model</td>
                      <td style='border: 1px solid #ccc;'>".$loginResultArrayChild['model']."</td>
                      </tr>";
;

    $html.=   "</table>";
    echo $html;


   exit;
}

if( $_REQUEST['level']=="ImageGallery_1"){

    $SQLchild = "select tbl_completionreport.* from tbl_completionreport left join in_crmhead
    on tbl_completionreport.invheadid=in_crmhead.id where tbl_completionreport.id='".$_REQUEST['childid']."'";

    $SQLReschild =  mysql_query($SQLchild) or die(mysql_error()."<br>".$SQLchild);
    $loginResultArrayChild   = mysql_fetch_array($SQLReschild);


    $html =   "<div class='row'>
                    <div class='column'>
                         <img src='uploads/".$loginResultArrayChild['docname']."'' alt='Before' style='width:100%;'>
                    </div>
                    <div class='column'>
                         <img src='uploads/".$loginResultArrayChild['docname2']."'' alt='After' style='width:100%;'>
                    </div>
              </div>";

    echo $html;


   exit;
}

if( $_REQUEST['level']=="propertydetails"){

    $SQLchild = "select * from tbl_clientserviceproperty ,tbl_clientbuilding where tbl_clientserviceproperty.id='".$_REQUEST['childid']."'and tbl_clientserviceproperty.buildingcode=tbl_clientbuilding.buildingshortname";
    $SQLReschild =  mysql_query($SQLchild) or die(mysql_error()."<br>".$SQLchild);
    $loginResultArrayChild   = mysql_fetch_array($SQLReschild);

    $docname  =  $loginResultArrayChild['docname'] ;
    $str = explode("$$$",$docname);
                                  $str = substr($docname,(strlen($str[0])+3));
                                  if($docname!=""){
                                      $ext = strtolower(pathinfo($docname, PATHINFO_EXTENSION));
                                      $dwld = $str."&nbsp;&nbsp;<a href='#' onclick='loadframe(\"".$ext."\",\"".$docname."\");' data-toggle='modal' data-target='#myModal'><i class='fa fa-eye' data-toggle='tooltip' data-placement='right' title='View' aria-hidden='true'></i></a>
                                                &nbsp;&nbsp;<a  href='download.php?folder=uploads&filename=".$docname."'><i class='fa fa-download' data-toggle='tooltip' data-placement='right' title='Download' aria-hidden='true'></i></a>";
                                  }else{
                                      $dwld = "";
                                  }

    $html =   "<table class='table table-condensed table-bordered' width=100%>
                      <tr>
                      <td class='dvtCellLabel' style='border: 1px solid #ccc;width:25%'>Project Name</td>
                      <td style='border: 1px solid #ccc;width:25%'>".$loginResultArrayChild['projectname']."</td>
                      <td class='dvtCellLabel' style='border: 1px solid #ccc;width:25%'>Property Type<span class='mandatory'></span></td>
                      <td style='border: 1px solid #ccc;width:25%'>".$loginResultArrayChild['propertycode']."</td>
                      </tr>
                      <tr>
                      <td class='dvtCellLabel' style='border: 1px solid #ccc;'>Property</td>
                      <td style='border: 1px solid #ccc;'>".$loginResultArrayChild['buildingname']."</td>
                      <td class='dvtCellLabel' style='border: 1px solid #ccc;'>Floor Details<span class='mandatory'> </span></td>
                      <td style='border: 1px solid #ccc;'>".$loginResultArrayChild['remarks']."</td>
                      </tr>
                      <tr>
                      <td class='dvtCellLabel' style='border: 1px solid #ccc;'>Attachments</td>
                      <td style='border: 1px solid #ccc;'>".$dwld."</td>
                      </tr>";
;

    $html.=   "</table>";
    echo $html;


   exit;
}
if( $_REQUEST['level']=="servicedetails"){

    $SQLchild = "select * from in_crmline left join tbl_clientbuilding on tbl_clientbuilding.buildingshortname= in_crmline.buildingcode inner join in_asset on in_crmline.category=in_asset.categorycode where in_crmline.id='".$_REQUEST['childid']."'";
    $SQLReschild =  mysql_query($SQLchild) or die(mysql_error()."<br>".$SQLchild);
    $loginResultArrayChild   = mysql_fetch_array($SQLReschild);


    $html =   "<table class='table table-condensed table-bordered' width=100%>

                       <tr>
                      <td class='dvtCellLabel' style='border: 1px solid #ccc;width:20%'>Property<span class='mandatory'></span></td>
                      <td style='border: 1px solid #ccc;'>".$loginResultArrayChild['buildingname']."</td>
                      </tr>
                      <tr>
                      <td class='dvtCellLabel' style='border: 1px solid #ccc;'>Service Category</td>
                      <td style='border: 1px solid #ccc;'>".$loginResultArrayChild['categoryname']."</td>
                      </tr>
                      <tr>
                      <td class='dvtCellLabel' style='border: 1px solid #ccc;'>Services<span class='mandatory'> </span></td>
                      <td style='border: 1px solid #ccc;'>".$loginResultArrayChild['articlename']."</td>
                      </tr>
                      <tr>
                      <td class='dvtCellLabel' style='border: 1px solid #ccc;'>Sub Contract</td>
                      <td style='border: 1px solid #ccc;'>".$loginResultArrayChild['subcontractor']."</td>
                      </tr>
                      <tr>
                      <td class='dvtCellLabel' style='border: 1px solid #ccc;'>Remarks</td>
                      <td style='border: 1px solid #ccc;'>".$loginResultArrayChild['description']."</td>
                      </tr>";
;

    $html.=   "</table>";
    echo $html;


   exit;
}

if( $_REQUEST['level']=="serviceassetdetails"){

    $SQLchild="Select tbl_serviceasset.unit,tbl_serviceasset.ppmtype,in_asset.categoryname,in_assetdetail.assetdescription,quantity,tbl_serviceasset.docname,
    tbl_clientbuilding.buildingname from tbl_serviceasset left join tbl_clientbuilding on tbl_clientbuilding.buildingshortname= tbl_serviceasset.buildingcode
    inner join in_asset on tbl_serviceasset.assettype=in_asset.categorycode inner join in_assetdetail on tbl_serviceasset.assetdescription=in_assetdetail.assetid
    where tbl_serviceasset.id='".$_REQUEST['childid']."'";

    $SQLReschild =  mysql_query($SQLchild) or die(mysql_error()."<br>".$SQLchild);
    $loginResultArrayChild   = mysql_fetch_array($SQLReschild);

    $docname  =  $loginResultArrayChild['docname'] ;
    $str = explode("$$$",$docname);
                                  $str = substr($docname,(strlen($str[0])+3));
                                  if($docname!=""){
                                      $ext = strtolower(pathinfo($docname, PATHINFO_EXTENSION));
                                      $dwld = $str."&nbsp;&nbsp;<a href='#' onclick='loadframe(\"".$ext."\",\"".$docname."\");' data-toggle='modal' data-target='#myModal'><i class='fa fa-eye' data-toggle='tooltip' data-placement='right' title='View' aria-hidden='true'></i></a>
                                                &nbsp;&nbsp;<a  href='download.php?folder=uploads&filename=".$docname."'><i class='fa fa-download' data-toggle='tooltip' data-placement='right' title='Download' aria-hidden='true'></i></a>";
                                  }else{
                                      $dwld = "";
                                  }

    $html =   "<table class='table table-list table-bordered wrap-tbl' width=100%>

                       <tr>
                      <td class='dvtCellLabel' style='border: 1px solid #ccc;width:20%;'>Property<span class='mandatory'></span></td>
                      <td style='border: 1px solid #ccc;'>".$loginResultArrayChild['buildingname']."</td>
                      </tr>
                      <tr>
                      <td class='dvtCellLabel' style='border: 1px solid #ccc;'>Services</td>
                      <td style='border: 1px solid #ccc;'>".$loginResultArrayChild['categoryname']."</td>
                      </tr>
                      <tr>
                      <td class='dvtCellLabel' style='border: 1px solid #ccc;'>Asset Type<span class='mandatory'> </span></td>
                      <td style='border: 1px solid #ccc;'>".$loginResultArrayChild['assetdescription']."</td>
                      </tr>
                      <tr>
                      <td class='dvtCellLabel' style='border: 1px solid #ccc;'>Frequency</td>
                      <td style='border: 1px solid #ccc;'>".$loginResultArrayChild['ppmtype']."</td>
                      </tr>
                      <tr>
                      <td class='dvtCellLabel' style='border: 1px solid #ccc;'>Unit</td>
                      <td style='border: 1px solid #ccc;'>".GetUnitName($loginResultArrayChild['unit'])."</td>
                      </tr>
                      <tr>
                      <td class='dvtCellLabel' style='border: 1px solid #ccc;'>Quantity</td>
                      <td style='border: 1px solid #ccc;'>".$loginResultArrayChild['quantity']."</td>
                      </tr>
                      <tr>
                      <td class='dvtCellLabel' style='border: 1px solid #ccc;'>Attachment</td>
                      <td style='border: 1px solid #ccc;'>".$dwld."</td>
                      </tr>";
;

    $html.=   "</table>";
    echo $html;


   exit;
}

 if( $_REQUEST['level']=="manpowerdetails"){

    $SQLchild="Select tbl_manpowerforservice.*,buildingname,tbl_manpowercategory.categoryname,tbl_manpowerforservice.docname,tbl_manpowerforservice.remarks,B.categoryname as designation,tbl_manpowercategory.categoryname,in_asset.categoryname as manpowercategory from tbl_manpowerforservice
left join tbl_clientbuilding on tbl_clientbuilding.buildingshortname= tbl_manpowerforservice.buildingcode inner join in_asset on tbl_manpowerforservice.assettype=in_asset.categorycode
inner join tbl_manpowercategory on tbl_manpowerforservice.manpowercategorycode=tbl_manpowercategory.categorycode inner join tbl_manpowercategory as B on tbl_manpowerforservice.designation=B.categorycode
where tbl_manpowerforservice.id='".$_REQUEST['childid']."'";

    $SQLReschild =  mysql_query($SQLchild) or die(mysql_error()."<br>".$SQLchild);
    $loginResultArrayChild   = mysql_fetch_array($SQLReschild);
    
    $docname  =  $loginResultArrayChild['docname'] ;
    $str = explode("$$$",$docname);
                                  $str = substr($docname,(strlen($str[0])+3));
                                  if($docname!=""){
                                      $ext = strtolower(pathinfo($docname, PATHINFO_EXTENSION));
                                      $dwld = $str."&nbsp;&nbsp;<a href='#' onclick='loadframe(\"".$ext."\",\"".$docname."\");' data-toggle='modal' data-target='#myModal'><i class='fa fa-eye' data-toggle='tooltip' data-placement='right' title='View' aria-hidden='true'></i></a>
                                                &nbsp;&nbsp;<a  href='download.php?folder=uploads&filename=".$docname."'><i class='fa fa-download' data-toggle='tooltip' data-placement='right' title='Download' aria-hidden='true'></i></a>";
                                  }else{
                                      $dwld = "";
                                  }



    $html =   "<table class='table table-list table-bordered  wrap-tbl' width=100%>
                       <tr>
                      <td class='dvtCellLabel' style='border: 1px solid #ccc;width:20%;'>Property<span class='mandatory'></span></td>
                      <td style='border: 1px solid #ccc;'>".$loginResultArrayChild['buildingname']."</td>
                      <td class='dvtCellLabel' style='border: 1px solid #ccc;width:20%;'>Services</td>
                      <td style='border: 1px solid #ccc;'>".$loginResultArrayChild['manpowercategory']."</td>
                      </tr>
                      <tr>
                      <td class='dvtCellLabel' style='border: 1px solid #ccc;'>Manpower Category<span class='mandatory'> </span></td>
                      <td style='border: 1px solid #ccc;'>".$loginResultArrayChild['categoryname']."</td>
                      <td class='dvtCellLabel' style='border: 1px solid #ccc;'>Manpower</td>
                      <td style='border: 1px solid #ccc;'>".$loginResultArrayChild['designation']."</td>
                      </tr>
                      <tr>
                      <td class='dvtCellLabel' style='border: 1px solid #ccc;'>No Of Persons</td>
                      <td style='border: 1px solid #ccc;'>".$loginResultArrayChild['quantity']."</td>
                      <td class='dvtCellLabel' style='border: 1px solid #ccc;'>Attachments</td>
                      <td style='border: 1px solid #ccc;'>".$dwld."</td>
                      </tr>
                      <tr>
                      <td class='dvtCellLabel' style='border: 1px solid #ccc;'>Remarks</td>
                      <td style='border: 1px solid #ccc;' colspan=3>".$loginResultArrayChild['remarks']."</td>
                      </tr>";
;

    $html.=   "</table>";
    echo $html;


   exit;
}



function GetUnitName($unit) {
         $SEL =  "select lookcode,lookname from in_lookup where looktype='UOM' and lookcode='$unit'";
         $RES = mysql_query($SEL);
         $ARR = mysql_fetch_array($RES);
         return $ARR['lookname'];

}
function GetLastSqeID($tblName){
	global $con;
       $query = "LOCK TABLES in_sequencer WRITE";
       mysqli_query($con,$query) or die(mysqli_error()."<br>".$query);
       $seqSQL = "SELECT LASTNUMBER FROM in_sequencer WHERE TABLENAME='$tblName'";
       $result=mysqli_query($con,$seqSQL) or die(mysqli_error()."<br>".$seqSQL);
       $resulArr=mysqli_fetch_array($result);
       $updatedSeqID=$resulArr['LASTNUMBER']+1;
       $squpdateSQL = "UPDATE in_sequencer SET LASTNUMBER=".$updatedSeqID." WHERE TABLENAME='$tblName'";
       mysqli_query($con,$squpdateSQL) or die(mysqli_error()."<br>".$squpdateSQL);
       $query = "UNLOCK TABLES";
       mysqli_query($con,$query) or die(mysqli_error()."<br>".$query);
       return ($updatedSeqID);
}
?>

