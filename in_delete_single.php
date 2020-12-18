<?php
ob_start();
include "connection.php";
include "pagingObj.php";

@session_start();

$formname = $_SESSION['CurrentObjectName']->formName;
/*print_r($_REQUEST);
exit;*/

    $Htemp     = explode(',',$_REQUEST['del']);

    for($i=0 ;$i < count($Htemp); $i++){
    	    if($formname=="menulist.php"){
    			
                // delete main menus and submenus 
                $deletesql_child = "delete from ".$_SESSION['CurrentObjectName']->TableName." where parentid='".$Htemp[$i]."'";
                $result   = mysqli_query($con,$deletesql_child)  or die(mysqli_error()."<br>".$deletesql_child);
                
                $deletesql = "delete from ".$_SESSION['CurrentObjectName']->TableName." where id='".$Htemp[$i]."'";
                $result   = mysqli_query($con,$deletesql)  or die(mysqli_error()."<br>".$deletesql);
                   
                UserLog($_SESSION['CurrentObjectName']->TableName,$Htemp[$i],$deletesql_child,"DELETE");
                UserLog("tbl_menu",$Htemp[$i],$deletesql,"DELETE");
    			
			}
			if($formname=="workflowsetup.php"){
    			
                // delete main menus and submenus 
                $deletesql_child = "delete from tbl_workflowline where parentid='".$Htemp[$i]."'";
                $result   = mysqli_query($con,$deletesql_child)  or die(mysqli_error()."<br>".$deletesql_child);
                
                $deletesql = "delete from ".$_SESSION['CurrentObjectName']->TableName." where id='".$Htemp[$i]."'";
                $result   = mysqli_query($con,$deletesql)  or die(mysqli_error()."<br>".$deletesql);
                   
                UserLog($_SESSION['CurrentObjectName']->TableName,$Htemp[$i],$deletesql_child,"DELETE");
                UserLog("tbl_workflowline",$Htemp[$i],$deletesql,"DELETE");
    			
			}
    		else if($formname=="userprivilegessetup.php"){
    			
                // delete main menus and submenus defined for the usergroup
                $deletesql_child = "delete from tbl_menusetup where usergroupid='".$Htemp[$i]."'";
                $result   = mysqli_query($con,$deletesql_child)  or die(mysqli_error()."<br>".$deletesql_child);
                
                $deletesql = "delete from ".$_SESSION['CurrentObjectName']->TableName." where id='".$Htemp[$i]."'";
                $result   = mysqli_query($con,$deletesql)  or die(mysqli_error()."<br>".$deletesql);
                   
                UserLog($_SESSION['CurrentObjectName']->TableName,$Htemp[$i],$deletesql_child,"DELETE");
                UserLog("tbl_menusetup",$Htemp[$i],$deletesql,"DELETE");
    			
			}
    	
               else if($formname=="purchaseindentlist.php"){
                  /* $SQL   = "SELECT invheadid,quantity from in_inventoryline where initemid='".$Htemp[$i]."'";
                   $SQLRes =  mysqli_query($con,$SQL) or die(mysqli_error()."<br>".$SQL);
                   if(mysqli_num_rows($SQLRes)>=1){
                          while($loginResultArray   = mysqli_fetch_array($SQLRes)){
                              $updatesql = "update in_inventoryline set purindentpickedquantity=purindentpickedquantity-".$loginResultArray['quantity']." where id='".$loginResultArray['invheadid']."'";
                              $result   = mysqli_query($con,$updatesql)  or die(mysqli_error()."<br>".$updatesql);
                          }
                   }*/
                   $deletesql = "delete from in_inventoryline where initemid='".$Htemp[$i]."'";
                   $result   = mysqli_query($con,$deletesql)  or die(mysqli_error()."<br>".$deletesql);
                   $deletesql = "delete from ".$_SESSION['CurrentObjectName']->TableName." where id='".$Htemp[$i]."'";
                   $result   = mysqli_query($con,$deletesql)  or die(mysqli_error()."<br>".$deletesql);
               }
               else if($formname=="purchaseorderlist.php"){
                   $SQL   = "SELECT invheadid,quantity from in_inventoryline where initemid='".$Htemp[$i]."'";
                   $SQLRes =  mysqli_query($con,$SQL) or die(mysqli_error()."<br>".$SQL);
                   if(mysqli_num_rows($SQLRes)>=1){
                          while($loginResultArray   = mysqli_fetch_array($SQLRes)){
                              $updatesql = "update in_inventoryline set purchaseorderpickedquantity=purchaseorderpickedquantity-".$loginResultArray['quantity']." where id='".$loginResultArray['invheadid']."'";
                              $result   = mysqli_query($con,$updatesql)  or die(mysqli_error()."<br>".$updatesql);
                          }
                   }
                   $deletesql = "delete from in_inventoryline where initemid='".$Htemp[$i]."'";
                   $result   = mysqli_query($con,$deletesql)  or die(mysqli_error()."<br>".$deletesql);
                   $deletesql = "delete from ".$_SESSION['CurrentObjectName']->TableName." where id='".$Htemp[$i]."'";
                   $result   = mysqli_query($con,$deletesql)  or die(mysqli_error()."<br>".$deletesql);
               }else if($formname=="grncostinglist.php"){
                   $SQL   = "SELECT invheadid,quantity from in_inventoryline where initemid='".$Htemp[$i]."'";
                   $SQLRes =  mysqli_query($con,$SQL) or die(mysqli_error()."<br>".$SQL);
                   if(mysqli_num_rows($SQLRes)>=1){
                          while($loginResultArray   = mysqli_fetch_array($SQLRes)){
                              $updatesql = "update in_inventoryline set grnpickedquantity=grnpickedquantity-".$loginResultArray['quantity']." where id='".$loginResultArray['invheadid']."'";
                              $result   = mysqli_query($con,$updatesql)  or die(mysqli_error()."<br>".$updatesql);
                          }
                   }
                   $deletesql = "delete from in_inventoryline where initemid='".$Htemp[$i]."'";
                   $result   = mysqli_query($con,$deletesql)  or die(mysqli_error()."<br>".$deletesql);
                   $deletesql = "delete from ".$_SESSION['CurrentObjectName']->TableName." where id='".$Htemp[$i]."'";
                   $result   = mysqli_query($con,$deletesql)  or die(mysqli_error()."<br>".$deletesql);
               }else if($formname=="articlelist.php"){
                   $deletesql = "delete from ".$_SESSION['CurrentObjectName']->TableName." where id='".$Htemp[$i]."'";
                   $result   = mysqli_query($con,$deletesql)  or die(mysqli_error()."<br>".$deletesql);

               }else{
                   $deletesql = "delete from ".$_SESSION['CurrentObjectName']->TableName." where id='".$Htemp[$i]."'";
                   $result   = mysqli_query($con,$deletesql)  or die(mysqli_error()."<br>".$deletesql);
                   UserLog($_SESSION['CurrentObjectName']->TableName,$Htemp[$i],$deletesql,"DELETE");
               }
               
               
               
            }


            
if($formname=="articlelist.php"){
  header("Location:".$_REQUEST['returnpage']."?cmb_lookuplist1=".$_REQUEST['cmb_lookuplist1']."&cmb_lookuplist=".$_REQUEST['cmb_lookuplist']."&frmPage_startpage=".$_REQUEST['frmPage_startpage']."&frmPage_currentpage=".$_REQUEST['frmPage_currentpage']."&frmPage_endpage=".$_REQUEST['frmPage_endpage']."&frmPage_rowcount=".$_REQUEST['frmPage_rowcount']."&frmPage_startrow=".$_REQUEST['frmPage_startrow']."");
}else{
  header("Location:".$_REQUEST['returnpage']."?id=");
}

function UserLog($tblName,$tableseqID,$tablestrSQL,$actiontype){
	global $con;
        $seqID = GetLastSqeID2("in_userlog");
        $datetime=date("Y/m/d h:i:s a", time());
        $seqSQL = "insert into in_userlog values(".$seqID.",'".$datetime."','".$_SESSION['SESSuserID'] ."','".$_SERVER['REMOTE_ADDR']."','".$tblName."','".$tableseqID."','".$actiontype."','".str_replace("'","''",$tablestrSQL)."','".$_SESSION['SESSUserLocation']."','".$_SESSION['SESScompanycode']."')";
        $result=mysqli_query($con,$seqSQL) or die(mysqli_error()."<br>".$seqSQL);
}

function GetLastSqeID2($tblName){
	global $con;
       $query = "LOCK TABLES $tblName WRITE";
       mysqli_query($con,$query) or die(mysqli_error()."<br>".$query);
       $seqSQL = "SELECT max(id) as LASTNUMBER FROM $tblName";
       $result=mysqli_query($con,$seqSQL) or die(mysqli_error()."<br>".$seqSQL);
       $resulArr=mysqli_fetch_array($result);
       $updatedSeqID=$resulArr['LASTNUMBER']+1;
       $query = "UNLOCK TABLES";
       mysqli_query($con,$query) or die(mysqli_error()."<br>".$query);
       return ($updatedSeqID);
}

ob_end_flush();
?>
