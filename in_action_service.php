<?php
ob_start();
include "connection.php";
include "pagingObj.php";
include "functions.php";

session_start();
$_SESSION['lastID']="";

if($_SESSION['SESSuserID']==""){
    echo "Your Browser Session has been expired! Please Login Again...";
    die();
}
//echo $_SESSION['CurrentObjectName']->TableName;
//echo $_REQUEST['mode'];
$RequestBuffer = $_REQUEST;
$RequestBuffer_row = $_REQUEST['ZZZZZZXXXXXX'];
//print_r($RequestBuffer);
//exit;
if ($_REQUEST['modeid']=="save" && $_REQUEST['mode']==""){
   GetInsertStatement($RequestBuffer,$RequestBuffer_row);
   PostAddConditions($myTable,$RequestBuffer);
   exit;
}
if ($_REQUEST['modeid']=="save" && $_REQUEST['mode']!=""){
   GetUpdateStatement($RequestBuffer,$RequestBuffer_row);
   PostUpdateConditions($myTable,$RequestBuffer);
   exit;
}

if ($_REQUEST['child']=="child" && $_REQUEST['childid']==""){
   GetInsertChildStatement($RequestBuffer,$RequestBuffer_row);
   PostInsertChildStatement($RequestBuffer,$RequestBuffer_row);
   exit;
}
if ($_REQUEST['child']=="child" && $_REQUEST['childid']!=""){
   GetUpdateChildStatement($RequestBuffer,$RequestBuffer_row);
   PostUpdateChildStatement($RequestBuffer,$RequestBuffer_row);
   exit;
}
//PostUpdateConditions($myTable,$RequestBuffer);
//PostAddConditions($myTable,$RequestBuffer);

function GetUpdateStatement($RequestBuffer,$RequestBuffer_row){
        $strSQL    = "";
        $strFields = "";
        $strValues = "";
        $lstValues = "";

        foreach($RequestBuffer as $key => $value){
            $tempControlType = UTrim(substr($key,0,3));

            if (($tempControlType  == UTrim('txt')) or ($tempControlType == UTrim('pwd')) or ($tempControlType == UTrim('hid')) or ($tempControlType == UTrim('cmb')) or ($tempControlType == UTrim('txa')) or ($tempControlType == UTrim('txr'))) {

                $tempFieldName     = substr($key,6);
                $tempFieldType     = UTrim(substr($key,4,1));                  //  Get the type "Alpha Numeric A,N
                $value         = str_replace("'","''",$value);
                $value = str_replace("^^^","&",$value);
                if($tempControlType == UTrim('cmb')){
                 if($value=="^^^")$value = str_replace("^^^","&",$value);
                 if($value=="9999")$value = str_replace("9999","",$value);
                }

                $strFields         = $tempFieldName;                           //  Build Field Names
                if ($tempFieldType == UTrim("A") or $tempFieldType == UTrim("A") or $tempFieldType == UTrim("U")){                             //  Check Field type Alpha or numeric
                    $strValues  = "'" . $value . "'";                          //  If Alpha put quotes
                }else{
                    if($value=="")$value=0;
                    $strValues = $value ;                                      //  If numeric, just take as it is
                }

                $strSQL = $strSQL . $strFields . "=" . $strValues . ",";
            }
            if($tempControlType == UTrim("CHK")){                              // If Control Type is CHECKBOX in scrolling div
                $tempFieldName     = substr($key,6);
                $strFields         = $tempFieldName;
                //print_r($value);
                $value1="";
                if (is_array($value)){

                 foreach ($value as $value_) {
                          $value1 .=  $value_ .",";
                 }
                 $value = substr($value1,0,strlen($value1)-1) ;
                }else{
                $value         = str_replace("'","''",$value);
                $value         = str_replace("999","&",$value);

                }
                $strSQL = $strSQL . $strFields . "='" . $value . "',";
            }

            if($tempControlType == UTrim("CKK")){                              // If Control Type is CHECKBOX in scrolling div
                $tempFieldName="";
                $chkData ="";

                $tempFieldName = substr($key,6);
                $chkData = $RequestBuffer[$key];
                $chkCount = count($RequestBuffer[$key]);
                if($chkCount!=0){
                   $CHKIDS="";
                   for($i = 0;$i < $chkCount;$i ++){ // Building ID's string to delete
                       $CHKIDS = $CHKIDS .$chkData[$i] . ",";
                   }

                   $CHKIDS = substr($CHKIDS,0,strlen($CHKIDS)-1) ;
                   $strFields = $tempFieldName ;
                   $strValues = "'" . $CHKIDS . "'";
                }else{
                   $strFields = $tempFieldName ;
                   $strValues = "'" . $chkData . "'";
                }
                $strSQL = $strSQL . $strFields . "=" . $strValues . ",";

            }
            if($tempControlType == UTrim("txd")){                              // If Control Type is CHECKBOX in scrolling div
                $tempFieldName     = substr($key,6);
                $strFields         = $tempFieldName;
                $value         = str_replace("'","''",$value);
               // $value         = str_replace("^^^","&",$value);
                $Dvalue  = explode('-',$value);
                $value   = $Dvalue[2].'-'.$Dvalue[1].'-'.$Dvalue[0];
                $valuearr=explode(' ',$Dvalue[2]);
                $value= $valuearr[0]."-".$Dvalue[1]."-".$Dvalue[0]." ".$valuearr[1];                        //  Build Field Names
                $strSQL = $strSQL . $strFields . "='" . $value . "',";
            }

        }
      /*  if($_SESSION['CurrentObjectName']->formName=="newticketlist.php"){
          $SQL1   = "Select requeststatus from tbl_ticket where id='".$_REQUEST['mode']."'";
          $SQLRes1 =  mysql_query($SQL1) or die(mysql_error()."<br>".$SQL1);
               if(mysql_num_rows($SQLRes1)>=1){
                    while($loginResultArray1   = mysql_fetch_array($SQLRes1)){
                        if($loginResultArray1['requeststatus']!=$_REQUEST['cmb_A_requeststatus']) {
                               $sedID3=GetLastSqeID("tbl_actionlog");
                               $Date  = explode('-',$_REQUEST['statusdate']);
                               $datevalue   = $Date[2].'-'.$Date[1].'-'.$Date[0];
                               $sql13 = "insert into tbl_actionlog(id,ticketid,actiontype,notes,actiondate,fromtime,staff) values ('$sedID3','".$_REQUEST['mode']."','".$_REQUEST['cmb_A_requeststatus']."','".$loginResultArray1['requeststatus']."','".$datevalue."','".date('H:i:s')."','".$_SESSION['SESSuserID']."')";
                               mysql_query($sql13) or die(mysql_error()."PA-115<br>".$sql13);

                        }
                    }
                }
            } */


        $strSQL = "UPDATE " . $_SESSION['CurrentObjectName']->TableName . " SET " . substr($strSQL,0,strlen($strSQL)-1);    //  Removing Last coma
        $strSQL = $strSQL . " WHERE ID='" . $_REQUEST['mode'] ."'";
        //echo $_REQUEST['cmb_A_requeststatus'];exit;
        mysql_query("SET NAMES 'utf8'");
        $result   = mysql_query($strSQL)  or die(mysql_error());
        UserLog($_SESSION['CurrentObjectName']->TableName,$_REQUEST['mode'],$strSQL,"UPDATE");
        //for child
        if($RequestBuffer_row!=""){
             if($_SESSION['CurrentObjectName']->formName=="purchaseindentlist.php" || $_SESSION['CurrentObjectName']->formName=="generalmaterialtransfer.php"){
               $seqSQL = "DELETE FROM ".$_SESSION['CurrentObjectName']->formChildTable." WHERE initemid='".$_SESSION['CurrentObjectName']->formChildTableRecord."'";
               $result=mysql_query($seqSQL) or die(mysql_error()."<br>".$seqSQL);
             }
             $seqSQL = "DELETE FROM ".$_SESSION['CurrentObjectName']->formChildTable." WHERE INVHEADID='".$_SESSION['CurrentObjectName']->formChildTableRecord."'";
             $result=mysql_query($seqSQL) or die(mysql_error()."<br>".$seqSQL);

             $Hiddtemp     = split(',',$_SESSION['CurrentObjectName']->formChildHiddenFields);
                     for($y=0 ;$y < count($Hiddtemp); $y++){
                         $HiddtempCount     = split(':',$Hiddtemp[$y]);
                         $tempFieldName1 .= $HiddtempCount[0].",";
                         $tempFieldvalue1 .="'". $HiddtempCount[1]."',";

             }

             $Htemp     = split("=",$RequestBuffer_row);
             for($i=0 ;$i < count($Htemp)-1; $i++){
                     $tempFieldName ="";
                     $tempFieldvalue ="";

                     $Htemp1     = split(",",$Htemp[$i]);
                     for($ii=0 ;$ii < count($Htemp1); $ii++){
                       $Htemp2     = split(":",$Htemp1[$ii]);
                        $tempFieldName     .= $Htemp2[0].",";
                        if($Htemp2[1]=="")$Htemp2[1]="0";
                        if($Htemp2[0]=="articlecode"){
                          $tempFieldvalue    .= "'" . $Htemp2[1]."',";
                        }else if($Htemp2[0]=="uom"){
                          $tempFieldvalue    .= "'" . $Htemp2[1]."',";
                        }else if($Htemp2[0]=="description"){
                          $tempFieldvalue    .= "'" . $Htemp2[1]."',";
                        }else if($Htemp2[0]=="projectcode"){
                          $tempFieldvalue    .= "'" . $Htemp2[1]."',";
                        }else if($Htemp2[0]=="costcenter"){
                          $tempFieldvalue    .= "'" . $Htemp2[1]."',";
                        }else if($Htemp2[0]=="mrid"){
                          $tempFieldvalue    .= "'" . $Htemp2[1]."',";
                        }else if($Htemp2[0]=="mrdate"){
                          $tempFieldvalue    .= "'" . $Htemp2[1]."',";
                        }else if($Htemp2[0]=="userid"){
                          $tempFieldvalue    .= "'" . $Htemp2[1]."',";
                        }else{
                          $tempFieldvalue    .= $Htemp2[1].",";
                        }


                     }

                     $tempFieldName = substr($tempFieldName,0,strlen($tempFieldName)-1) ;
                     $tempFieldvalue = substr($tempFieldvalue,0,strlen($tempFieldvalue)-1) ;
                     if($_SESSION['CurrentObjectName']->TableName=="in_itemhead"){
                      $linkid="initemid";
                     }else{
                      $linkid="invheadid";
                     }
                     $seqID1 = GetLastSqeID($_SESSION['CurrentObjectName']->formChildTable);
                     $strSQLchild = "insert into ". $_SESSION['CurrentObjectName']->formChildTable . "(ID,$linkid,$tempFieldName1$tempFieldName) values ('".$seqID1."','".$_SESSION['CurrentObjectName']->formChildTableRecord."',$tempFieldvalue1$tempFieldvalue)";
                     $result   = mysql_query($strSQLchild)  or die(mysql_error());
             }
             //end of child
        }

        echo "Record Updated";
   }

function GetInsertStatement($RequestBuffer,$RequestBuffer_row){
        $strSQL    = "";
        $strFields = "";
        $strValues = "";
        $lstValues = "";
        $seqID = GetLastSqeID($_SESSION['CurrentObjectName']->TableName);
        $_SESSION['lastID'] = $seqID;

        foreach($RequestBuffer as $key => $value){
            $tempControlType = UTrim(substr($key,0,3));

            if (($tempControlType  == UTrim('txt')) or ($tempControlType == UTrim('pwd')) or ($tempControlType == UTrim('hid')) or ($tempControlType == UTrim('cmb')) or ($tempControlType == UTrim('txa')) or ($tempControlType == UTrim('txr'))) {
                $tempFieldName     = substr($key,6);
                $tempFieldType     = UTrim(substr($key,4,1));                  //  Get the type "Alpha Numeric A,N
                $value         = str_replace("'","''",$value);
                $value = str_replace("^^^","&",$value);
                if($tempControlType == UTrim('cmb')){
                  if($value=="^^^")$value = str_replace("^^^","&",$value);
                  if($value=="9999")$value = str_replace("9999","",$value);
                }
                $strFields         .= $tempFieldName.",";                           //  Build Field Names
                if ($tempFieldType == UTrim("A") or $tempFieldType == UTrim("A") or $tempFieldType == UTrim("U")){                             //  Check Field type Alpha or numeric
                    $strValues  .= "'" . $value . "',";                          //  If Alpha put quotes
                }else{
                    if($value=="")$value=0;
                    $strValues .= $value . ",";                          //  If Alpha put quotes                                     //  If numeric, just take as it is
                }
            }
            if($tempControlType == UTrim("CHK")){                              // If Control Type is CHECKBOX in scrolling div
                $tempFieldName     = substr($key,6);
                $tempFieldType     = UTrim(substr($key,4,1));                  //  Get the type "Alpha Numeric A,N
                $value         = str_replace("'","''",$value);
                $value         = str_replace("999","&",$value);
                                         //  Build Field Names
                $chkCount = count($value);
                if($chkCount!=0){
                                $CHKIDS="";
                                for($i = 0;$i < $chkCount;$i ++){                              //  Building ID's string to delete
                                    $CHKIDS = $CHKIDS .$value[$i] . ",";
                                }
                                $CHKIDS = substr($CHKIDS,0,strlen($CHKIDS)-1) ;
                                $strFields         .= $tempFieldName. ",";
                                $strValues         .= "'" . $CHKIDS . "',";
                }else{
                     $strFields         = $tempFieldName ;
                     $strValues         = "'" . $value . "'";
                }
            }
            if($tempControlType == UTrim("CKK")){                              // If Control Type is CHECKBOX in scrolling div
                $tempFieldName="";
                $chkData ="";

                $tempFieldName     = substr($key,6);

                $chkData  = $RequestBuffer[$key];
                $chkCount = count($RequestBuffer[$key]);
                if($chkCount!=0){
                                $CHKIDS="";
                                for($i = 0;$i < $chkCount;$i ++){                              //  Building ID's string to delete
                                    $CHKIDS = $CHKIDS .$chkData[$i] . ",";
                                }
                                $CHKIDS = substr($CHKIDS,0,strlen($CHKIDS)-1) ;
                                $strFields         .= $tempFieldName. ",";
                                $strValues         .= "'" . $CHKIDS . "',";
                }else{
                     $strFields         = $tempFieldName ;
                     $strValues         = "'" . $chkData . "'";
                }
            }
            if($tempControlType == UTrim("txd")){                              // If Control Type is CHECKBOX in scrolling div
                $tempFieldName     = substr($key,6);
                $tempFieldType     = UTrim(substr($key,4,1));                  //  Get the type "Alpha Numeric A,N
                $value         = str_replace("'","''",$value);
                //$value         = str_replace("^^^","&",$value);
                $strFields         .= $tempFieldName.",";
                $Dvalue  = explode('-',$value);
                $value   = $Dvalue[2].'-'.$Dvalue[1].'-'.$Dvalue[0];
                $valuearr=explode(' ',$Dvalue[2]);
                $value= $valuearr[0]."-".$Dvalue[1]."-".$Dvalue[0]." ".$valuearr[1];                        //  Build Field Names
                $strValues  .= "'" . $value . "',";                          //  If Alpha put quotes
            }
        }

        $strFields = substr($strFields,0,strlen($strFields)-1) ;
        $strValues = substr($strValues,0,strlen($strValues)-1) ;


        $strSQL = "Insert into " . $_SESSION['CurrentObjectName']->TableName . "(ID,".$strFields.") values ('".$seqID."'," . $strValues .")";    //  Removing Last coma
        mysql_query("SET NAMES 'utf8'");
        $result   = mysql_query($strSQL)  or die(mysql_error());

        UserLog($_SESSION['CurrentObjectName']->TableName,$seqID,$strSQL,"INSERT");


        if($RequestBuffer_row!=""){
             //for child
             $Hiddtemp     = split(',',$_SESSION['CurrentObjectName']->formChildHiddenFields);
                     for($y=0 ;$y < count($Hiddtemp); $y++){
                         $HiddtempCount     = split(':',$Hiddtemp[$y]);
                         $tempFieldName1 .= $HiddtempCount[0].",";
                         $tempFieldvalue1 .="'". $HiddtempCount[1]."',";

             }

             $Htemp     = split("=",$RequestBuffer_row);
             for($i=0 ;$i < count($Htemp)-1; $i++){
                     $tempFieldName ="";
                     $tempFieldvalue ="";

                     $Htemp1     = split(",",$Htemp[$i]);
                     for($ii=0 ;$ii < count($Htemp1); $ii++){
                       $Htemp2     = split(":",$Htemp1[$ii]);
                        $tempFieldName     .= $Htemp2[0].",";
                        if($Htemp2[1]=="")$Htemp2[1]="0";
                        if($Htemp2[0]=="articlecode"){
                          $tempFieldvalue    .= "'" . $Htemp2[1]."',";
                        }else if($Htemp2[0]=="uom" || $Htemp2[0]=="description"){
                          $tempFieldvalue    .= "'" . $Htemp2[1]."',";
                        }else{
                          $tempFieldvalue    .= $Htemp2[1].",";
                        }


                     }

                     $tempFieldName = substr($tempFieldName,0,strlen($tempFieldName)-1) ;
                     $tempFieldvalue = substr($tempFieldvalue,0,strlen($tempFieldvalue)-1) ;
                     $linkid="invheadid";
                     if($_SESSION['CurrentObjectName']->TableName=="in_itemhead")$linkid="initemid";
                     $seqID1 = GetLastSqeID($_SESSION['CurrentObjectName']->formChildTable);
                     $strSQLchild = "insert into ". $_SESSION['CurrentObjectName']->formChildTable . "(ID,$linkid,$tempFieldName1$tempFieldName) values ('".$seqID1."','".$seqID."',$tempFieldvalue1$tempFieldvalue)";
                     $result   = mysql_query($strSQLchild)  or die(mysql_error());

             }
             //end of child
        }

        echo "Record Saved";
}
function GetInsertChildStatement($RequestBuffer,$RequestBuffer_row){
        $strSQL    = "";
        $strFields = "";
        $strValues = "";
        $lstValues = "";


        $seqID = GetLastSqeID($_SESSION['CurrentObjectName']->TableNameChild);

        $_SESSION['lastID'] = $seqID;
        foreach($RequestBuffer as $key => $value){
            $tempControlType = UTrim(substr($key,0,3));

            if (($tempControlType  == UTrim('txt')) or ($tempControlType == UTrim('pwd')) or ($tempControlType == UTrim('hid')) or ($tempControlType == UTrim('cmb')) or ($tempControlType == UTrim('txa'))  or ($tempControlType == UTrim('txr'))) {
                $tempFieldName     = substr($key,6);
                $tempFieldType     = UTrim(substr($key,4,1));                  //  Get the type "Alpha Numeric A,N
                $value         = str_replace("'","''",$value);
                $value = str_replace("^^^","&",$value);
                if($tempControlType == UTrim('cmb')){
                   if($value=="^^^")$value = str_replace("^^^","&",$value);
                   if($value=="9999")$value = str_replace("9999","",$value);
                }
                $strFields         .= $tempFieldName.",";                           //  Build Field Names
                if ($tempFieldType == UTrim("A") or $tempFieldType == UTrim("A") or $tempFieldType == UTrim("U")){                             //  Check Field type Alpha or numeric
                    $strValues  .= "'" . $value . "',";                          //  If Alpha put quotes
                }else{
                    if($value=="")$value=0;
                    $strValues .= $value . ",";                          //  If Alpha put quotes                                     //  If numeric, just take as it is
                }
            }
            if($tempControlType == UTrim("CHK")){                              // If Control Type is CHECKBOX in scrolling div
                $tempFieldName     = substr($key,6);
                $tempFieldType     = UTrim(substr($key,4,1));                  //  Get the type "Alpha Numeric A,N
                $value         = str_replace("'","''",$value);
                $value         = str_replace("^^^","&",$value);
                $strFields         .= $tempFieldName.",";                           //  Build Field Names
                $strValues  .= "'" . $value . "',";


                                  //  If Alpha put quotes
            }
              if($tempControlType == UTrim("CKK")){                              // If Control Type is CHECKBOX in scrolling div
                $tempFieldName="";
                $chkData ="";

                $tempFieldName     = substr($key,6);

                $chkData  = $RequestBuffer[$key];
                $chkCount = count($RequestBuffer[$key]);
                if($chkCount!=0){
                                $CHKIDS="";
                                for($i = 0;$i < $chkCount;$i ++){                              //  Building ID's string to delete
                                    $CHKIDS = $CHKIDS .$chkData[$i] . ",";
                                }
                                $CHKIDS = substr($CHKIDS,0,strlen($CHKIDS)-2) ;
                                $strFields         .= $tempFieldName. ",";
                                $strValues         .= "'" . $CHKIDS . "',";
                }else{
                     $strFields         = $tempFieldName ;
                     $strValues         = "'" . $chkData . "'";
                }
            }
            if($tempControlType == UTrim("txd")){                              // If Control Type is CHECKBOX in scrolling div
                $tempFieldName     = substr($key,6);
                $tempFieldType     = UTrim(substr($key,4,1));                  //  Get the type "Alpha Numeric A,N
                $value         = str_replace("'","''",$value);
               // $value         = str_replace("^^^","&",$value);
                $strFields         .= $tempFieldName.",";
                $Dvalue  = explode('-',$value);
                $value   = $Dvalue[2].'-'.$Dvalue[1].'-'.$Dvalue[0];                           //  Build Field Names
                $strValues  .= "'" . $value . "',";                          //  If Alpha put quotes
            }
        }

        $strFields = substr($strFields,0,strlen($strFields)-1) ;
        $strValues = substr($strValues,0,strlen($strValues)-1) ;


        $strSQL = "Insert into " . $_SESSION['CurrentObjectName']->TableNameChild . "(ID,".$strFields.") values ('".$seqID."'," . $strValues .")";    //  Removing Last coma

        $result   = mysql_query($strSQL)  or die(mysql_error());
        UserLog($_SESSION['CurrentObjectName']->TableNameChild,$seqID,$strSQL,"INSERT");
        echo "Record Saved";
}



function PostInsertChildStatement($RequestBuffer,$RequestBuffer_row){
       $formname = $_SESSION['CurrentObjectName']->formName;

       if($formname=="addrequisitionitems_service.php"){
         header("location:addrequisitionitems_service.php?INITEMID=".$_REQUEST['txt_A_initemid']."");
       }
       if($formname=="addserviceitems.php" || $formname=="addserviceitemsformr.php" || $formname=="serviceslist.php"){

                  $charset="ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";
                  $length=5;
                  for($i=0; $i <=$length; $i++){
                   $rand =rand() % strlen($charset);
                   $temp=  substr($charset,$rand,3);
                  }
                  $target_path = $target_path.basename($_FILES['userfile']['name']);
                  $target_path = "uploads/";
                  $target_path = $target_path .$temp. basename( $_FILES['userfile']['name']);
                  if($_FILES['userfile']['name']) $fileName= $temp.$_FILES['userfile']['name'];
                  if (file_exists("uploads/".$fileName)){
                   echo "<center><STRONG>Sorry!!" .$fileName . " already exists.</center></STRONG>";
                  }else{
                   move_uploaded_file($_FILES['userfile']['tmp_name'], $target_path);
                  }
                  //echo $fileName."<br>";
                  if($fileName!='') {
                  $SQL1 = "UPDATE ".$_SESSION['CurrentObjectName']->TableNameChild." SET docname='".$fileName."' where id=".$_SESSION['lastID'];
                  mysql_query($SQL1) or die(mysql_error()."PA-115<br>".$SQL1);
                  }

                if($formname=="addserviceitems.php")header("location:addserviceitems.php?ID=".$_REQUEST['txt_A_parentid']."");
                if($formname=="addserviceitemsformr.php")header("location:addserviceitemsformr.php?ticketid=".$_REQUEST['txt_A_parentid']."&ID=".$_REQUEST['txt_A_quoteno']."");
                if($formname=="serviceslist.php")header("location:editserviceslist.php?ID=".$_REQUEST['txt_A_serviceid']."");
       }
       if($formname=="addmaterialitem.php"){

                  $charset="ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";
                  $length=5;
                  for($i=0; $i <=$length; $i++){
                   $rand =rand() % strlen($charset);
                   $temp=  substr($charset,$rand,3);
                  }
                  $target_path = $target_path.basename($_FILES['userfile']['name']);
                  $target_path = "uploads/";
                  $target_path = $target_path .$temp. basename( $_FILES['userfile']['name']);
                  if($_FILES['userfile']['name']) $fileName= $temp.$_FILES['userfile']['name'];
                  if (file_exists("uploads/".$fileName)){
                   echo "<center><STRONG>Sorry!!" .$fileName . " already exists.</center></STRONG>";
                  }else{
                   move_uploaded_file($_FILES['userfile']['tmp_name'], $target_path);
                  }
                  //echo $fileName."<br>";
                  if($fileName!='') {
                  $SQL1 = "UPDATE ".$_SESSION['CurrentObjectName']->TableNameChild." SET docname='".$fileName."' where id=".$_SESSION['lastID'];
                  mysql_query($SQL1) or die(mysql_error()."PA-115<br>".$SQL1);
                  }

                if($formname=="addmaterialitem.php")header("location:addmaterialitem.php?ID=".$_REQUEST['txt_A_invheadid']."");

       }
       if($formname=="addmaterialitem_service.php"){

                  $charset="ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";
                  $length=5;
                  for($i=0; $i <=$length; $i++){
                   $rand =rand() % strlen($charset);
                   $temp=  substr($charset,$rand,3);
                  }
                  $target_path = $target_path.basename($_FILES['userfile']['name']);
                  $target_path = "uploads/";
                  $target_path = $target_path .$temp. basename( $_FILES['userfile']['name']);
                  if($_FILES['userfile']['name']) $fileName= $temp.$_FILES['userfile']['name'];
                  if (file_exists("uploads/".$fileName)){
                   echo "<center><STRONG>Sorry!!" .$fileName . " already exists.</center></STRONG>";
                  }else{
                   move_uploaded_file($_FILES['userfile']['tmp_name'], $target_path);
                  }
                  //echo $fileName."<br>";
                  if($fileName!='') {
                  $SQL1 = "UPDATE ".$_SESSION['CurrentObjectName']->TableNameChild." SET docname='".$fileName."' where id=".$_SESSION['lastID'];
                  mysql_query($SQL1) or die(mysql_error()."PA-115<br>".$SQL1);
                  }

                if($formname=="addmaterialitem_service.php")header("location:addmaterialitem_service.php?ID=".$_REQUEST['txt_A_invheadid']."");

       }
       if($formname=="addmaterialitemquote.php"){

                  $charset="ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";
                  $length=5;
                  for($i=0; $i <=$length; $i++){
                   $rand =rand() % strlen($charset);
                   $temp=  substr($charset,$rand,3);
                  }
                  $target_path = $target_path.basename($_FILES['userfile']['name']);
                  $target_path = "uploads/";
                  $target_path = $target_path .$temp. basename( $_FILES['userfile']['name']);
                  if($_FILES['userfile']['name']) $fileName= $temp.$_FILES['userfile']['name'];
                  if (file_exists("uploads/".$fileName)){
                   echo "<center><STRONG>Sorry!!" .$fileName . " already exists.</center></STRONG>";
                  }else{
                   move_uploaded_file($_FILES['userfile']['tmp_name'], $target_path);
                  }
                  //echo $fileName."<br>";
                  if($fileName!='') {
                  $SQL1 = "UPDATE ".$_SESSION['CurrentObjectName']->TableNameChild." SET docname='".$fileName."' where id=".$_SESSION['lastID'];
                  mysql_query($SQL1) or die(mysql_error()."PA-115<br>".$SQL1);
                  }

                if($formname=="addmaterialitemquote.php")header("location:addmaterialitemquote.php?ID=".$_REQUEST['txt_A_invheadid']."");

       }
       if($formname=="annexure.php"){

                  $charset="ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";
                  $length=5;
                  for($i=0; $i <=$length; $i++){
                   $rand =rand() % strlen($charset);
                   $temp=  substr($charset,$rand,3);
                  }
                  $target_path = $target_path.basename($_FILES['userfile']['name']);
                  $target_path = "uploads/";
                  $target_path = $target_path .$temp. basename( $_FILES['userfile']['name']);
                  if($_FILES['userfile']['name']) $fileName= $temp.$_FILES['userfile']['name'];
                  if (file_exists("uploads/".$fileName)){
                   echo "<center><STRONG>Sorry!!" .$fileName . " already exists.</center></STRONG>";
                  }else{
                   move_uploaded_file($_FILES['userfile']['tmp_name'], $target_path);
                  }
                  //echo $fileName."<br>";
                  if($fileName!='') {
                  $SQL1 = "UPDATE ".$_SESSION['CurrentObjectName']->TableNameChild." SET docname='".$fileName."' where id=".$_SESSION['lastID'];
                  mysql_query($SQL1) or die(mysql_error()."PA-115<br>".$SQL1);
                  }

                if($formname=="annexure.php")header("location:annexure.php?ID=".$_REQUEST['txt_A_invheadid']."");

       }
       if($formname=="crmcontactperson.php"){

                   $seqID = GetLastSqeID("in_businessobjectdetails");
                   if($_REQUEST['cmb_A_primaryone']=="Yes"){
                      $updatesql1 = "update in_businessobjectdetails set primaryone='No' where businessobjectid='".$_REQUEST['txt_A_businessobjectid']."'
                                  and id<>'".$_SESSION['lastID']."'";
                      $result   = mysql_query($updatesql1)  or die(mysql_error()."<br>".$updatesql1);
                   }
       }
       if($formname=="applicantinterview.php"){
             header("location:applicantinterview.php?ID=".$_REQUEST['txt_A_requisitionid']."");
       }
       if($formname=="emp_leavepackage.php"  ){
                   $SQL2 = "UPDATE e_leavepackage SET empleavepackstatus='Inactive' where id <> '".$_SESSION['lastID']."' and staffid='".$RequestBuffer['txt_A_staffid']."'";
                   mysql_query($SQL2) or die(mysql_error()."PA-115<br>".$SQL2);

                   $RES_44 = mysql_query("select id from e_leavepackage where staffid='".$RequestBuffer['txt_A_staffid']."'");
                   if(mysql_num_rows($RES_44) >1){
                                      $SQL_33 = "select id from e_leavepackage where id =(Select max(id) as docid  from e_leavepackage where staffid='".$RequestBuffer['txt_A_staffid']."' and id <> '".$_SESSION['lastID']."')";
                                      $RES_33 = mysql_query($SQL_33);
                                      $ARR_33 = mysql_fetch_array($RES_33);
                                      $effectivefrom=strtotime($RequestBuffer['txd_A_effectivefrom']);
                                      $effectiveto=date('Y-m-d', strtotime('-1 day', strtotime($RequestBuffer['txd_A_effectivefrom']))) ;
                                  /*  $frommonth=date("m",$effectivefrom);
                                      $fromyear= date("Y",$effectivefrom);
                                    if($frommonth=='01'){
                                        $addsql= "tomonth='12',toyear=".($fromyear-1);
                                      }else{
                                         $xy= $frommonth-1;
                                         $month     =sprintf('%02d', $xy);
                                         $addsql=" tomonth='".$month."' ,toyear=".$fromyear;
                                      } */
                                         $UP_33 = "update e_leavepackage set effectiveto='".$effectiveto."' where id='".$ARR_33['id']."'";
                                         mysql_query($UP_33) or die(mysql_error()."PA-115<br>".$UP_33);
                  }
         }
  if($formname=="upload_documents.php"){
          for($i=0 ;$i < $_REQUEST['selectslots']; $i++){
                                    if($i==0){

                                      $seqNumber1 = GetLastSqeID("e_attachments");
                                      $temp= $seqNumber1.date('dmyHis')."A$$$"; 
                                      $target_path = basename($_FILES['userfile']['name']);
                                      $target_path = "uploads/";
                                      $target_path = $target_path .$temp. basename( $_FILES['userfile']['name']);
                                      
                                      if($_FILES['userfile']['name']) $imgName= $temp.$_FILES['userfile']['name'];

                                       move_uploaded_file($_FILES['userfile']['tmp_name'], $target_path);

                                      $SQL1 = "insert into e_attachments(id,docid,docname,doctype)
                                               values('$seqNumber1','".$_SESSION['lastID']."','".$imgName ."','".$_REQUEST['txt_A_entitytype']."') ";
                                      mysql_query($SQL1) or die(mysql_error()."PA-115<br>".$SQL1);

                                    }else{
                                      $field='userfile'.$i;
                                      $seqNumber1 = GetLastSqeID("e_attachments");
                                      $temp= $seqNumber1.date('dmyHis')."A$$$"; 
                                      $target_path = basename($_FILES[$field]['name']);
                                      $target_path = "uploads/";
                                      $target_path = $target_path .$temp. basename( $_FILES[$field]['name']);
                                      if($_FILES[$field]['name']) $imgName= $temp.$_FILES[$field]['name'];

                                       move_uploaded_file($_FILES[$field]['tmp_name'], $target_path);


                                      $SQL1 = "insert into e_attachments(id,docid,docname,doctype)
                                               values('$seqNumber1','".$_SESSION['lastID']."','".$imgName ."','".$_REQUEST['txt_A_entitytype']."') ";
                                      mysqli_query($SQL1) or die(mysqli_error()."PA-115<br>".$SQL1);

                                    }
                                }
             if($formname=="upload_documents.php")header("location:upload_documents.php?entitytype=".$_REQUEST['txt_A_entitytype']."&ID=".$_REQUEST['txt_A_staffid']."");

         }
       if($formname=="emp_documents.php"){
          for($i=0 ;$i < $_REQUEST['selectslots']; $i++){
                                    if($i==0){

                                      $seqNumber1 = GetLastSqeID("e_attachments");
                                      $target_path = $target_path.basename($_FILES['userfile']['name']);
                                      $target_path = "uploads/";
                                      $target_path = $target_path .$seqNumber1."_". basename( $_FILES['userfile']['name']);
                                      if($_FILES['userfile']['name']) $imgName= $temp.$_FILES['userfile']['name'];

                                       move_uploaded_file($_FILES['userfile']['tmp_name'], $target_path);

                                      $SQL1 = "insert into e_attachments(id,docid,docname)
                                               values('$seqNumber1','".$_SESSION['lastID']."','".$imgName ."') ";
                                      mysql_query($SQL1) or die(mysql_error()."PA-115<br>".$SQL1);

                                    }else{
                                      $field='userfile'.$i;
                                      $seqNumber1 = GetLastSqeID("e_attachments");
                                      $target_path = $target_path.basename($_FILES[$field]['name']);
                                      $target_path = "uploads/";
                                      $target_path = $target_path .$seqNumber1."_". basename( $_FILES[$field]['name']);
                                      if($_FILES[$field]['name']) $imgName= $temp.$_FILES[$field]['name'];

                                       move_uploaded_file($_FILES[$field]['tmp_name'], $target_path);


                                      $SQL1 = "insert into e_attachments(id,docid,docname)
                                               values('$seqNumber1','".$_SESSION['lastID']."','".$imgName ."') ";
                                      mysql_query($SQL1) or die(mysql_error()."PA-115<br>".$SQL1);

                                    }
                                }
             if($formname=="emp_documents.php")header("location:emp_documents.php?entitytype=".$_REQUEST['txt_A_entitytype']."&ID=".$_REQUEST['txt_A_staffid']."");

         }
      /*if($formname=="emp_leave.php"){


                  $SQL   = "Select datediff(leaveto,leavefrom) as days from e_leave  WHERE id='".$_SESSION['lastID']."'";
                  $SQLRes =  mysql_query($SQL) or die(mysql_error()."<br>".$SQL);
                  if(mysql_num_rows($SQLRes)>=1){
                   $loginResultArray   = mysql_fetch_array($SQLRes);
                   $days= $loginResultArray['days']+1;

                  }
                        $SQL   = "SELECT LASTNUMBER AS LASTNUMBER  FROM in_sequencer WHERE TABLENAME='e_leave'";
                        $SQLRes =  mysql_query($SQL) or die(mysql_error()."<br>".$SQL);
                        if(mysql_num_rows($SQLRes)>=1){
                          while($loginResultArray   = mysql_fetch_array($SQLRes)){
                             $instructionid=$loginResultArray['LASTNUMBER']+1;
                          }
                        }

                $SQL2 = "UPDATE e_leave SET daysallowed=".$days." where id=".$_SESSION['lastID'];
                mysql_query($SQL2) or die(mysql_error()."PA-115<br>".$SQL2);

                $squpdateSQL = "UPDATE in_sequencer SET LASTNUMBER=".$instructionid." WHERE TABLENAME='e_leave'";
                mysql_query($squpdateSQL) or die(mysql_error()."<br>".$squpdateSQL);

                 //if($formname=="emp_leave.php")header("location:emp_leave.php?ID=".$_REQUEST['txt_A_staffid']."");
         }*/
         if($formname=="emp_leave.php"){


                  $SQL   = "Select availedfrom,availedto,datediff(availedto,availedfrom) as days from e_leave  WHERE id='".$_SESSION['lastID']."'";
                  $SQLRes =  mysql_query($SQL) or die(mysql_error()."<br>".$SQL);
                  if(mysql_num_rows($SQLRes)>=1){
                   $loginResultArray   = mysql_fetch_array($SQLRes);
                   $days= $loginResultArray['days']+1;
                   $availedfrom=$loginResultArray['availedfrom'];
                   $availedto= $loginResultArray['availedto'];

                  }
                        $SQL   = "SELECT LASTNUMBER AS LASTNUMBER  FROM in_sequencer WHERE TABLENAME='e_leave'";
                        $SQLRes =  mysql_query($SQL) or die(mysql_error()."<br>".$SQL);
                        if(mysql_num_rows($SQLRes)>=1){
                          while($loginResultArray   = mysql_fetch_array($SQLRes)){
                             $instructionid=$loginResultArray['LASTNUMBER']+1;
                          }
                        }

                $SQL2 = "UPDATE e_leave SET daysallowed='".$days."',daysapproved='".$days."',daysavailed='".$days."',
                         leavefrom='".$availedfrom."',leaveto='".$availedto."',approvedfrom='".$availedfrom."',approvedto='".$availedto."',
                         hr_user_post='YES',hr_user_postdate='".date('Y-m-d')."',hrstatus='APPROVED',daysaviledstatus='YES' where id=".$_SESSION['lastID'];
                mysql_query($SQL2) or die(mysql_error()."PA-115<br>".$SQL2);

                $squpdateSQL = "UPDATE in_sequencer SET LASTNUMBER=".$instructionid." WHERE TABLENAME='e_leave'";
                mysql_query($squpdateSQL) or die(mysql_error()."<br>".$squpdateSQL);

                 //if($formname=="emp_leave.php")header("location:emp_leave.php?ID=".$_REQUEST['txt_A_staffid']."");
         }

         if($formname=="contractamendments.php"){
          for($i=0 ;$i < $_REQUEST['selectslots']; $i++){
                                    if($i==0){

                                      $seqNumber1 = GetLastSqeID("in_contractamendments");
                                      $target_path = $target_path.basename($_FILES['userfile']['name']);
                                      $target_path = "documents/";
                                      $target_path = $target_path .$seqNumber1."_". basename( $_FILES['userfile']['name']);
                                      if($_FILES['userfile']['name']) $imgName= $temp.$_FILES['userfile']['name'];

                                       move_uploaded_file($_FILES['userfile']['tmp_name'], $target_path);

                                      $SQL1 = "insert into in_contractamendments(id,docid,docname)
                                               values('$seqNumber1','".$_SESSION['lastID']."','".$imgName ."') ";
                                      mysql_query($SQL1) or die(mysql_error()."PA-115<br>".$SQL1);

                                    }else{
                                      $field='userfile'.$i;
                                      $seqNumber1 = GetLastSqeID("in_contractamendments");
                                      $target_path = $target_path.basename($_FILES[$field]['name']);
                                      $target_path = "documents/";
                                      $target_path = $target_path .$seqNumber1."_". basename( $_FILES[$field]['name']);
                                      if($_FILES[$field]['name']) $imgName= $temp.$_FILES[$field]['name'];

                                       move_uploaded_file($_FILES[$field]['tmp_name'], $target_path);


                                      $SQL1 = "insert into in_contractamendments(id,docid,docname)
                                               values('$seqNumber1','".$_SESSION['lastID']."','".$imgName ."') ";
                                      mysql_query($SQL1) or die(mysql_error()."PA-115<br>".$SQL1);

                                    }
                                }
             if($formname=="contractamendments.php")header("location:contractamendments.php?ID=".$_REQUEST['txt_A_docid']."");

         }
         if($formname=="applicantselected.php"){

                  $charset="ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";
                  $length=5;
                  for($i=0; $i <=$length; $i++){
                   $rand =rand() % strlen($charset);
                   $temp=  substr($charset,$rand,3);
                  }
                  $target_path = $target_path.basename($_FILES['userfile']['name']);
                  $target_path = "documents/";
                  $target_path = $target_path .$temp. basename( $_FILES['userfile']['name']);
                  if($_FILES['userfile']['name']) $fileName= $temp.$_FILES['userfile']['name'];
                  if (file_exists("documents/".$fileName)){
                   echo "<center><STRONG>Sorry!!" .$fileName . " already exists.</center></STRONG>";
                  }else{
                   move_uploaded_file($_FILES['userfile']['tmp_name'], $target_path);
                  }
                  //echo $fileName."<br>";
                  if($fileName!='') {
                  $SQL1 = "UPDATE ".$_SESSION['CurrentObjectName']->TableNameChild." SET offerupload='".$fileName."' where id=".$_SESSION['lastID'];
                  mysql_query($SQL1) or die(mysql_error()."PA-115<br>".$SQL1);
                  }

                if($formname=="applicantselected.php")header("location:applicantselected.php?ID=".$_REQUEST['txt_A_requisitionid']."");

         }
         if($formname=="applicantapplied.php"){

                  $charset="ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";
                  $length=5;
                  for($i=0; $i <=$length; $i++){
                   $rand =rand() % strlen($charset);
                   $temp=  substr($charset,$rand,3);
                  }
                  $target_path = $target_path.basename($_FILES['userfile1']['name']);
                  $target_path = "documents/";
                  $target_path = $target_path .$temp. basename( $_FILES['userfile1']['name']);
                  if($_FILES['userfile1']['name']) $fileName= $temp.$_FILES['userfile1']['name'];
                  if (file_exists("documents/".$fileName)){
                   echo "<center><STRONG>Sorry!!" .$fileName . " already exists.</center></STRONG>";
                  }else{
                   move_uploaded_file($_FILES['userfile1']['tmp_name'], $target_path);
                  }
                  //echo $fileName."<br>";
                  if($fileName!='') {
                  $SQL1 = "UPDATE ".$_SESSION['CurrentObjectName']->TableNameChild." SET docname='".$fileName."' where id=".$_SESSION['lastID'];
                  mysql_query($SQL1) or die(mysql_error()."PA-115<br>".$SQL1);
                  }
                  if($formname=="applicantapplied.php"){
                        $target_path = $target_path.basename($_FILES['userfile']['name']);
                        $target_path = "staffphoto/";
                        $target_path = $target_path .$temp. basename( $_FILES['userfile']['name']);
                        if($_FILES['userfile']['name']) $fileName= $temp.$_FILES['userfile']['name'];
                        if (file_exists("staffphoto/".$fileName)){
                         echo "<center><STRONG>Sorry!!" .$fileName . " already exists.</center></STRONG>";
                        }else{
                         move_uploaded_file($_FILES['userfile']['tmp_name'], $target_path);
                        }
                        $SQL1 = "UPDATE ".$_SESSION['CurrentObjectName']->TableNameChild." SET photoname='".$fileName."' where id=".$_SESSION['lastID'];
                        mysql_query($SQL1) or die(mysql_error()."PA-115<br>".$SQL1);
                  }

                if($formname=="applicantapplied.php")header("location:applicantapplied.php?ID=".$_REQUEST['txt_A_requisitionid']."");

         }

          if($formname=="emp_salary.php"  ){
                if($RequestBuffer['txt_A_scaleid']=="CUSTOM"){
                   $seqID = GetLastSqeID("e_payscale");
                   $SQL   = "insert into e_payscale value('".$seqID."','".$seqID."','CUSTOM','C".$RequestBuffer['txt_A_staffid']."','".$_SESSION['CURRDATE']."','YES','YES','".$_SESSION['SESScompanycode']."','".$_SESSION['SESSUserLocation']."')";
                   $SQLRes =  mysql_query($SQL) or die(mysql_error()."<br>".$SQL);

                   $SQL2 = "UPDATE e_salary SET scaleid=".$seqID.",customtype='YES' where id=".$_SESSION['lastID'];
                   mysql_query($SQL2) or die(mysql_error()."PA-115<br>".$SQL2);
                }

                  $SQL2 = "UPDATE e_salary SET scalestatus='Inactive' where id <> '".$_SESSION['lastID']."' and staffid='".$RequestBuffer['txt_A_staffid']."'";
                   mysql_query($SQL2) or die(mysql_error()."PA-115<br>".$SQL2);

                   $RES_44 = mysql_query("select id from e_salary where staffid='".$RequestBuffer['txt_A_staffid']."'");
                   if(mysql_num_rows($RES_44) >1){
                                      $SQL_33 = "select id from e_salary where id =(Select max(id) as docid  from e_salary where staffid='".$RequestBuffer['txt_A_staffid']."' and id <> '".$_SESSION['lastID']."')";
                                      $RES_33 = mysql_query($SQL_33);
                                      $ARR_33 = mysql_fetch_array($RES_33);
                                      if($RequestBuffer['cmb_A_frommonth']=='01'){
                                        $addsql= "tomonth='12',toyear=".($RequestBuffer['cmb_A_fromyear']-1);
                                      }else{
                                         $xy= $RequestBuffer['cmb_A_frommonth']-1;
                                         $month     =sprintf('%02d', $xy);
                                         $addsql=" tomonth='".$month."' ,toyear=".$RequestBuffer['cmb_A_fromyear'];
                                      }
                                         $UP_33 = "update e_salary set $addsql where id='".$ARR_33['id']."'";
                                         mysql_query($UP_33) or die(mysql_error()."PA-115<br>".$UP_33);
                  }
         }

}
function PostUpdateChildStatement($RequestBuffer,$RequestBuffer_row){

         $formname = $_SESSION['CurrentObjectName']->formName;
         if($formname=="addrequisitionitems_service.php"){
          header("location:addrequisitionitems_service.php?INITEMID=".$_REQUEST['txt_A_initemid']."");
         }
          if($formname=="addserviceitems.php" || $formname=="addserviceitemsformr.php"){

                  $charset="ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";
                  $length=5;
                  for($i=0; $i <=$length; $i++){
                   $rand =rand() % strlen($charset);
                   $temp=  substr($charset,$rand,3);
                  }
                  $target_path = $target_path.basename($_FILES['userfile']['name']);
                  $target_path = "uploads/";
                  $target_path = $target_path .$temp. basename( $_FILES['userfile']['name']);
                  if($_FILES['userfile']['name']) $fileName= $temp.$_FILES['userfile']['name'];
                  if (file_exists("uploads/".$fileName)){
                   echo "<center><STRONG>Sorry!!" .$fileName . " already exists.</center></STRONG>";
                  }else{
                   move_uploaded_file($_FILES['userfile']['tmp_name'], $target_path);
                  }
                  //echo $fileName."<br>";
                  if($fileName!='') {
                  $SQL1 = "UPDATE ".$_SESSION['CurrentObjectName']->TableNameChild." SET docname='".$fileName."' where id=".$_REQUEST['childid'];
                  mysql_query($SQL1) or die(mysql_error()."PA-115<br>".$SQL1);
                  }

                if($formname=="addserviceitems.php")header("location:addserviceitems.php?ID=".$_REQUEST['txt_A_parentid']."");
                if($formname=="addserviceitemsformr.php")header("location:addserviceitemsformr.php?ticketid=".$_REQUEST['txt_A_parentid']."&ID=".$_REQUEST['txt_A_quoteno']."");

       }
         if($formname=="addmaterialitem_service.php"){

                  $charset="ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";
                  $length=5;
                  for($i=0; $i <=$length; $i++){
                   $rand =rand() % strlen($charset);
                   $temp=  substr($charset,$rand,3);
                  }
                  $target_path = $target_path.basename($_FILES['userfile']['name']);
                  $target_path = "uploads/";
                  $target_path = $target_path .$temp. basename( $_FILES['userfile']['name']);
                  if($_FILES['userfile']['name']) $fileName= $temp.$_FILES['userfile']['name'];
                  if (file_exists("uploads/".$fileName)){
                   echo "<center><STRONG>Sorry!!" .$fileName . " already exists.</center></STRONG>";
                  }else{
                   move_uploaded_file($_FILES['userfile']['tmp_name'], $target_path);
                  }
                  //echo $fileName."<br>";
                  if($fileName!='') {
                  $SQL1 = "UPDATE ".$_SESSION['CurrentObjectName']->TableNameChild." SET docname='".$fileName."' where id=".$_REQUEST['childid'];
                  mysql_query($SQL1) or die(mysql_error()."PA-115<br>".$SQL1);
                  }

                if($formname=="addmaterialitem_service.php")header("location:addmaterialitem_service.php?ID=".$_REQUEST['txt_A_invheadid']."");

       }
         if($formname=="addmaterialitem.php"){

                  $charset="ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";
                  $length=5;
                  for($i=0; $i <=$length; $i++){
                   $rand =rand() % strlen($charset);
                   $temp=  substr($charset,$rand,3);
                  }
                  $target_path = $target_path.basename($_FILES['userfile']['name']);
                  $target_path = "uploads/";
                  $target_path = $target_path .$temp. basename( $_FILES['userfile']['name']);
                  if($_FILES['userfile']['name']) $fileName= $temp.$_FILES['userfile']['name'];
                  if (file_exists("uploads/".$fileName)){
                   echo "<center><STRONG>Sorry!!" .$fileName . " already exists.</center></STRONG>";
                  }else{
                   move_uploaded_file($_FILES['userfile']['tmp_name'], $target_path);
                  }
                  //echo $fileName."<br>";
                  if($fileName!='') {
                  $SQL1 = "UPDATE ".$_SESSION['CurrentObjectName']->TableNameChild." SET docname='".$fileName."' where id=".$_REQUEST['childid'];
                  mysql_query($SQL1) or die(mysql_error()."PA-115<br>".$SQL1);
                  }

                if($formname=="addmaterialitem.php")header("location:addmaterialitem.php?ID=".$_REQUEST['txt_A_invheadid']."");

       }
       if($formname=="addmaterialitemquote.php"){

                  $charset="ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";
                  $length=5;
                  for($i=0; $i <=$length; $i++){
                   $rand =rand() % strlen($charset);
                   $temp=  substr($charset,$rand,3);
                  }
                  $target_path = $target_path.basename($_FILES['userfile']['name']);
                  $target_path = "uploads/";
                  $target_path = $target_path .$temp. basename( $_FILES['userfile']['name']);
                  if($_FILES['userfile']['name']) $fileName= $temp.$_FILES['userfile']['name'];
                  if (file_exists("uploads/".$fileName)){
                   echo "<center><STRONG>Sorry!!" .$fileName . " already exists.</center></STRONG>";
                  }else{
                   move_uploaded_file($_FILES['userfile']['tmp_name'], $target_path);
                  }
                  //echo $fileName."<br>";
                  if($fileName!='') {
                  $SQL1 = "UPDATE ".$_SESSION['CurrentObjectName']->TableNameChild." SET docname='".$fileName."' where id=".$_REQUEST['childid'];
                  mysql_query($SQL1) or die(mysql_error()."PA-115<br>".$SQL1);
                  }

                if($formname=="addmaterialitemquote.php")header("location:addmaterialitemquote.php?ID=".$_REQUEST['txt_A_invheadid']."");

       }
       if($formname=="annexure.php"){

                  $charset="ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";
                  $length=5;
                  for($i=0; $i <=$length; $i++){
                   $rand =rand() % strlen($charset);
                   $temp=  substr($charset,$rand,3);
                  }
                  $target_path = $target_path.basename($_FILES['userfile']['name']);
                  $target_path = "uploads/";
                  $target_path = $target_path .$temp. basename( $_FILES['userfile']['name']);
                  if($_FILES['userfile']['name']) $fileName= $temp.$_FILES['userfile']['name'];
                  if (file_exists("uploads/".$fileName)){
                   echo "<center><STRONG>Sorry!!" .$fileName . " already exists.</center></STRONG>";
                  }else{
                   move_uploaded_file($_FILES['userfile']['tmp_name'], $target_path);
                  }
                  //echo $fileName."<br>";
                  if($fileName!='') {
                  $SQL1 = "UPDATE ".$_SESSION['CurrentObjectName']->TableNameChild." SET docname='".$fileName."' where id=".$_REQUEST['childid'];
                  mysql_query($SQL1) or die(mysql_error()."PA-115<br>".$SQL1);
                  }

                if($formname=="annexure.php")header("location:annexure.php?ID=".$_REQUEST['txt_A_invheadid']."");

       }
       if($formname=="crmcontactperson.php"){

               if($_REQUEST['cmb_A_primaryone']=="Yes"){
                   $deletesql1 = "update in_businessobjectdetails set primaryone='No' where businessobjectid='".$_REQUEST['txt_A_businessobjectid']."'
                                  and id<>'".$_REQUEST['childid']."'";
                   $result   = mysql_query($deletesql1)  or die(mysql_error()."<br>".$deletesql1);
               }

       }

       if($formname=="contactactivity.php"){
                   $seqID = GetLastSqeID("in_crmvisit");

               if($_REQUEST['cmb_A_status']=="Happened" && $_REQUEST['txd_A_followupdate']!=""){

                   $Dvalue  = explode('-',$_REQUEST['txd_A_followupdate']);
                   $value   = $Dvalue[2].'-'.$Dvalue[1].'-'.$Dvalue[0];

                   $deletesql1 = "update in_crmvisit set status='Happened' where objectcode='".$_REQUEST['txt_A_objectcode']."'";
                   $result   = mysql_query($deletesql1)  or die(mysql_error()."<br>".$deletesql1);

                   $SQL2        = "Insert into in_crmvisit(
                                  id,activitytype,objectcode,objectname,visitdate,
                                  eventtype,priority,location,status,action,companycode,locationcode,userid)
                                  values (".$seqID.",'".$_REQUEST['txt_A_activitytype']."','".$_REQUEST['txt_A_objectcode']."','".$_REQUEST['txt_A_objectname']."','".$value."',
                                  '".$_REQUEST['cmb_A_eventtype']."','".$_REQUEST['cmb_A_priority']."','".$_REQUEST['txt_A_location']."','Open','".$_REQUEST['txa_A_followupdetail']."'
                                  ,'".$_SESSION['SESScompanycode']."','".$_SESSION['SESSUserLocation']."','".$_SESSION['SESSuserID']."')";

                   $SQLRes     =  mysql_query($SQL2) or die($SQL2);
               }
         }
         if($formname=="applicantinterview.php"){
             header("location:applicantinterview.php?ID=".$_REQUEST['txt_A_requisitionid']."");
         }
          if($formname=="upload_documents.php"){

          for($i=0 ;$i < $_REQUEST['selectslots']; $i++){
                                    if($i==0){

                                      $seqNumber1 = GetLastSqeID("e_attachments");
                                      $temp =$seqNumber1."$$$"; 
                                      $target_path = $target_path.basename($_FILES['userfile']['name']);
                                      $target_path = "uploads/";
                                      $target_path = $target_path .$temp. basename( $_FILES['userfile']['name']);
                                      if($_FILES['userfile']['name']) $imgName= $temp.$_FILES['userfile']['name'];

                                       move_uploaded_file($_FILES['userfile']['tmp_name'], $target_path);

                                      $SQL1 = "insert into e_attachments(id,docid,docname,doctype)
                                               values('$seqNumber1','".$_REQUEST['childid']."','".$imgName ."','".$_REQUEST['txt_A_entitytype']."') ";
                                      mysql_query($SQL1) or die(mysql_error()."PA-115<br>".$SQL1);

                                    }else{
                                      $field='userfile'.$i;
                                      $seqNumber1 = GetLastSqeID("e_attachments");
                                      $temp =$seqNumber1."$$$"; 
                                      $target_path = $target_path.basename($_FILES[$field]['name']);
                                      $target_path = "uploads/";
                                      $target_path = $target_path .$temp. basename( $_FILES[$field]['name']);
                                      if($_FILES[$field]['name']) $imgName= $temp.$_FILES[$field]['name'];

                                       move_uploaded_file($_FILES[$field]['tmp_name'], $target_path);


                                      $SQL1 = "insert into e_attachments(id,docid,docname,doctype)
                                               values('$seqNumber1','".$_REQUEST['childid']."','".$imgName ."','".$_REQUEST['txt_A_entitytype']."') ";
                                      mysql_query($SQL1) or die(mysql_error()."PA-115<br>".$SQL1);

                                    }
                                }
             if($formname=="upload_documents.php")header("location:upload_documents.php?entitytype=".$_REQUEST['txt_A_entitytype']."&ID=".$_REQUEST['txt_A_staffid']."");

         }
         
         if($formname=="emp_documents.php"){

          for($i=0 ;$i < $_REQUEST['selectslots']; $i++){
                                    if($i==0){

                                      $seqNumber1 = GetLastSqeID("e_attachments");
                                      $target_path = $target_path.basename($_FILES['userfile']['name']);
                                      $target_path = "uploads/";
                                      $target_path = $target_path .$seqNumber1."_". basename( $_FILES['userfile']['name']);
                                      if($_FILES['userfile']['name']) $imgName= $temp.$_FILES['userfile']['name'];

                                       move_uploaded_file($_FILES['userfile']['tmp_name'], $target_path);

                                      $SQL1 = "insert into e_attachments(id,docid,docname)
                                               values('$seqNumber1','".$_REQUEST['childid']."','".$imgName ."') ";
                                      mysql_query($SQL1) or die(mysql_error()."PA-115<br>".$SQL1);

                                    }else{
                                      $field='userfile'.$i;
                                      $seqNumber1 = GetLastSqeID("e_attachments");
                                      $target_path = $target_path.basename($_FILES[$field]['name']);
                                      $target_path = "uploads/";
                                      $target_path = $target_path .$seqNumber1."_". basename( $_FILES[$field]['name']);
                                      if($_FILES[$field]['name']) $imgName= $temp.$_FILES[$field]['name'];

                                       move_uploaded_file($_FILES[$field]['tmp_name'], $target_path);


                                      $SQL1 = "insert into e_attachments(id,docid,docname)
                                               values('$seqNumber1','".$_REQUEST['childid']."','".$imgName ."') ";
                                      mysql_query($SQL1) or die(mysql_error()."PA-115<br>".$SQL1);

                                    }
                                }
             if($formname=="emp_documents.php")header("location:emp_documents.php?entitytype=".$_REQUEST['txt_A_entitytype']."&ID=".$_REQUEST['txt_A_staffid']."");

         }
         if(  $formname=="editemp_leaverequest.php"){


                  $SQL   = "Select datediff(leaveto,leavefrom) as days from e_leave  WHERE id='".$_REQUEST['childid']."'";
                  $SQLRes =  mysql_query($SQL) or die(mysql_error()."<br>".$SQL);
                  if(mysql_num_rows($SQLRes)>=1){
                   $loginResultArray   = mysql_fetch_array($SQLRes);
                   $days= $loginResultArray['days']+1;

                  }


                $SQL2 = "UPDATE e_leave SET daysallowed=".$days." where id=".$_REQUEST['childid'];
                mysql_query($SQL2) or die(mysql_error()."PA-115<br>".$SQL2);


               //  if($formname=="emp_leave.php")header("location:emp_leave.php?ID=".$_REQUEST['txt_A_staffid']."");
         }
         if($formname=="emp_leave.php"){
              $SQL   = "Select availedfrom,availedto,datediff(availedto,availedfrom) as days from e_leave  WHERE id='".$_REQUEST['childid']."'";
              $SQLRes =  mysql_query($SQL) or die(mysql_error()."<br>".$SQL);
                  if(mysql_num_rows($SQLRes)>=1){
                   $loginResultArray   = mysql_fetch_array($SQLRes);
                     $days= $loginResultArray['days']+1;
                     $availedfrom= $loginResultArray['availedfrom'];
                     $availedto=  $loginResultArray['availedto'];
                  }
                $SQL2 = "UPDATE e_leave SET daysallowed='".$days."',daysapproved='".$days."',daysavailed='".$days."',
                         leavefrom='".$availedfrom."',leaveto='".$availedto."',approvedfrom='".$availedfrom."',approvedto='".$availedto."'
                         where id=".$_REQUEST['childid'];
                mysql_query($SQL2) or die(mysql_error()."PA-115<br>".$SQL2);

         }
         if($formname=="applicantselected.php"){

                  $charset="ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";
                  $length=5;
                  for($i=0; $i <=$length; $i++){
                   $rand =rand() % strlen($charset);
                   $temp=  substr($charset,$rand,3);
                  }
                  $target_path = $target_path.basename($_FILES['userfile']['name']);
                  $target_path = "documents/";
                  $target_path = $target_path .$temp. basename( $_FILES['userfile']['name']);
                  if($_FILES['userfile']['name']) $fileName= $temp.$_FILES['userfile']['name'];
                  if (file_exists("documents/".$fileName)){
                   echo "<center><STRONG>Sorry!!" .$fileName . " already exists.</center></STRONG>";
                  }else{
                   move_uploaded_file($_FILES['userfile']['tmp_name'], $target_path);
                  }
                  //echo $fileName."<br>";
                  if($fileName!='') {
                  $SQL1 = "UPDATE ".$_SESSION['CurrentObjectName']->TableNameChild." SET offerupload='".$fileName."' where id=".$_REQUEST['childid'];
                  mysql_query($SQL1) or die(mysql_error()."PA-115<br>".$SQL1);
                  }

                if($formname=="applicantselected.php")header("location:applicantselected.php?ID=".$_REQUEST['txt_A_requisitionid']."");

         }
         if($formname=="applicantapplied.php"){

                  $charset="ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";
                  $length=5;
                  for($i=0; $i <=$length; $i++){
                   $rand =rand() % strlen($charset);
                   $temp=  substr($charset,$rand,3);
                  }
                  $target_path = $target_path.basename($_FILES['userfile1']['name']);
                  $target_path = "documents/";
                  $target_path = $target_path .$temp. basename( $_FILES['userfile1']['name']);
                  if($_FILES['userfile1']['name']) $fileName= $temp.$_FILES['userfile1']['name'];
                  if (file_exists("documents/".$fileName)){
                   echo "<center><STRONG>Sorry!!" .$fileName . " already exists.</center></STRONG>";
                  }else{
                   move_uploaded_file($_FILES['userfile1']['tmp_name'], $target_path);
                  }
                  if($fileName!='') {
                  $SQL1 = "UPDATE ".$_SESSION['CurrentObjectName']->TableNameChild." SET docname='".$fileName."' where id=".$_REQUEST['childid'];
                  mysql_query($SQL1) or die(mysql_error()."PA-115<br>".$SQL1);
                  }


                  if($formname=="applicantapplied.php"){
                        $target_path = $target_path.basename($_FILES['userfile']['name']);
                        $target_path = "staffphoto/";
                        $target_path = $target_path .$temp. basename( $_FILES['userfile']['name']);
                        if($_FILES['userfile']['name']) $fileName= $temp.$_FILES['userfile']['name'];
                        if (file_exists("staffphoto/".$fileName)){
                         echo "<center><STRONG>Sorry!!" .$fileName . " already exists.</center></STRONG>";
                        }else{
                         move_uploaded_file($_FILES['userfile']['tmp_name'], $target_path);
                        }
                        $SQL1 = "UPDATE ".$_SESSION['CurrentObjectName']->TableNameChild." SET photoname='".$fileName."' where id=".$_REQUEST['childid'];
                        mysql_query($SQL1) or die(mysql_error()."PA-115<br>".$SQL1);
                  }

                if($formname=="applicantapplied.php")header("location:applicantapplied.php?ID=".$_REQUEST['txt_A_requisitionid']."");

         }
         if($formname=="trainingcompletion.php"){

                  $charset="ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";
                  $length=5;
                  for($i=0; $i <=$length; $i++){
                   $rand =rand() % strlen($charset);
                   $temp=  substr($charset,$rand,3);
                  }
                  $target_path = $target_path.basename($_FILES['userfile']['name']);
                  $target_path = "documents/";
                  $target_path = $target_path .$temp. basename( $_FILES['userfile']['name']);
                  if($_FILES['userfile']['name']) $fileName= $temp.$_FILES['userfile']['name'];
                  if (file_exists("documents/".$fileName)){
                   echo "<center><STRONG>Sorry!!" .$fileName . " already exists.</center></STRONG>";
                  }else{
                   move_uploaded_file($_FILES['userfile']['tmp_name'], $target_path);
                  }
                  if($fileName!='') {
                  $SQL1 = "UPDATE ".$_SESSION['CurrentObjectName']->TableNameChild." SET docname='".$fileName."' where id=".$_REQUEST['childid'];
                  mysql_query($SQL1) or die(mysql_error()."PA-115<br>".$SQL1);
                  }

                if($formname=="trainingcompletion.php")header("location:edittrainingcompletion.php?dr=edit&ID=".$_REQUEST['BID']."");

         }
         if($formname=="contractamendments.php"){

          for($i=0 ;$i < $_REQUEST['selectslots']; $i++){
                                    if($i==0){

                                      $seqNumber1 = GetLastSqeID("in_contractamendments");
                                      $target_path = $target_path.basename($_FILES['userfile']['name']);
                                      $target_path = "uploads/";
                                      $target_path = $target_path .$seqNumber1."_". basename( $_FILES['userfile']['name']);
                                      if($_FILES['userfile']['name']) $imgName= $temp.$_FILES['userfile']['name'];

                                       move_uploaded_file($_FILES['userfile']['tmp_name'], $target_path);

                                      $SQL1 = "insert into in_contractamendments(id,docid,docname)
                                               values('$seqNumber1','".$_REQUEST['childid']."','".$imgName ."') ";
                                      mysql_query($SQL1) or die(mysql_error()."PA-115<br>".$SQL1);

                                    }else{
                                      $field='userfile'.$i;
                                      $seqNumber1 = GetLastSqeID("in_contractamendments");
                                      $target_path = $target_path.basename($_FILES[$field]['name']);
                                      $target_path = "uploads/";
                                      $target_path = $target_path .$seqNumber1."_". basename( $_FILES[$field]['name']);
                                      if($_FILES[$field]['name']) $imgName= $temp.$_FILES[$field]['name'];

                                       move_uploaded_file($_FILES[$field]['tmp_name'], $target_path);


                                      $SQL1 = "insert into in_contractamendments(id,docid,docname)
                                               values('$seqNumber1','".$_REQUEST['childid']."','".$imgName ."') ";
                                      mysql_query($SQL1) or die(mysql_error()."PA-115<br>".$SQL1);

                                    }
                                }
             if($formname=="contractamendments.php")header("location:contractamendments.php?ID=".$_REQUEST['txt_A_docid']."");

         }
}
function GetUpdateChildStatement($RequestBuffer,$RequestBuffer_row){
        $strSQL    = "";
        $strFields = "";
        $strValues = "";
        $lstValues = "";

        foreach($RequestBuffer as $key => $value){
            $tempControlType = UTrim(substr($key,0,3));

            if (($tempControlType  == UTrim('txt')) or ($tempControlType == UTrim('pwd')) or ($tempControlType == UTrim('hid')) or ($tempControlType == UTrim('cmb')) or ($tempControlType == UTrim('txa'))  or ($tempControlType == UTrim('txr'))) {

                $tempFieldName     = substr($key,6);
                $tempFieldType     = UTrim(substr($key,4,1));                  //  Get the type "Alpha Numeric A,N
                $value         = str_replace("'","''",$value);
                $value = str_replace("^^^","&",$value);
                if($tempControlType == UTrim('cmb')){
                  if($value=="^^^")$value = str_replace("^^^","&",$value);
                  if($value=="9999")$value = str_replace("9999","",$value);
                }
                $strFields         = $tempFieldName;                           //  Build Field Names
                if ($tempFieldType == UTrim("A") or $tempFieldType == UTrim("A") or $tempFieldType == UTrim("U")){                             //  Check Field type Alpha or numeric
                    $strValues  = "'" . $value . "'";                          //  If Alpha put quotes
                }else{
                    if($value=="")$value=0;
                    $strValues = $value ;                                      //  If numeric, just take as it is
                }
                $strSQL = $strSQL . $strFields . "=" . $strValues . ",";
            }
            if($tempControlType == UTrim("CHK")){                              // If Control Type is CHECKBOX in scrolling div
                $tempFieldName     = substr($key,6);
                $strFields         = $tempFieldName;
                $value         = str_replace("'","''",$value);
                $value         = str_replace("^^^","&",$value);
                $strSQL = $strSQL . $strFields . "='" . $value . "',";
            }
             if($tempControlType == UTrim("CKK")){                              // If Control Type is CHECKBOX in scrolling div
                $tempFieldName="";
                $chkData ="";

                $tempFieldName = substr($key,6);
                $chkData = $RequestBuffer[$key];
                $chkCount = count($RequestBuffer[$key]);
                if($chkCount!=0){
                   $CHKIDS="";
                   for($i = 0;$i < $chkCount;$i ++){ // Building ID's string to delete
                       $CHKIDS = $CHKIDS .$chkData[$i] . ",";
                   }

                   $CHKIDS = substr($CHKIDS,0,strlen($CHKIDS)-1) ;
                   $strFields = $tempFieldName ;
                   $strValues = "'" . $CHKIDS . "'";
                }else{
                   $strFields = $tempFieldName ;
                   $strValues = "'" . $chkData . "'";
                }
                $strSQL = $strSQL . $strFields . "=" . $strValues . ",";

            }
            if($tempControlType == UTrim("txd")){                              // If Control Type is CHECKBOX in scrolling div
                $tempFieldName     = substr($key,6);
                $strFields         = $tempFieldName;
                $value         = str_replace("'","''",$value);
                //$value         = str_replace("^^^","&",$value);
                $Dvalue  = explode('-',$value);
                $value   = $Dvalue[2].'-'.$Dvalue[1].'-'.$Dvalue[0];                           //  Build Field Names
                $strSQL = $strSQL . $strFields . "='" . $value . "',";
            }

        }

        $strSQL = "UPDATE " . $_SESSION['CurrentObjectName']->TableNameChild . " SET " . substr($strSQL,0,strlen($strSQL)-1);    //  Removing Last coma
        $strSQL = $strSQL . " WHERE ID='" . $_REQUEST['childid'] ."'";
        $result   = mysql_query($strSQL)  or die(mysql_error());

        UserLog($_SESSION['CurrentObjectName']->TableNameChild,$_REQUEST['childid'],$strSQL,"UPDATE");
        echo "Record Updated";
   }
    function UTrim($str1){
        return trim(strtoupper($str1));
    }
    function PostAddConditions($tblName,$RequestBuffer){

        $formname = $_SESSION['CurrentObjectName']->formName;

        if($formname=="newleadheadlist_service.php"){
           $squpdateSQL = "UPDATE in_sequencer_crm SET LASTNUMBER=LASTNUMBER+1 WHERE TABLENAME='enquirylist'";
           mysql_query($squpdateSQL) or die(mysql_error()."<br>".$squpdateSQL);
        }
         if($formname=="partyledgers.php"){
               $SQL   = "SELECT * from in_businessobject where (objectname='".$RequestBuffer['txt_A_accountheadname']."' or phonecode1='".$RequestBuffer['txt_A_telephone']."') ";
               $SQLRes =  mysql_query($SQL) or die(mysql_error()."<br>".$SQL);
               if(mysql_num_rows($SQLRes)==0){

                  $sedID=GetLastSqeID("in_businessobject");

                  $sql1 = "insert into in_businessobject(id,objectcode,objectname,segmenttype,objecttype,userid,billingaddress1,phonecode1,contactperson) values ('$sedID','$sedID','".$RequestBuffer['txt_A_accountheadname']."','IT/Telecom/Networking','Lead','".$_SESSION['SESSuserID']."','".$RequestBuffer['txt_A_address']."','".$RequestBuffer['txt_A_telephone']."','".$RequestBuffer['txt_A_contactperson']."')";
                  mysql_query($sql1) or die(mysql_error()."PA-115<br>".$sql1);

                  $sql11 = "update in_accounthead set objectcode='$sedID' where id='".$_SESSION['lastID']."'";
                  mysql_query($sql11) or die(mysql_error()."PA-115<br>".$sql11);

              }
          }

         if($formname=="projectmaterialrequisition_service.php"){
                                  $DocNo="MR-".GetProID('MIR');
                                  $SQL1 = "UPDATE ".$_SESSION['CurrentObjectName']->TableName." SET docno='".$DocNo."' where id=".$_SESSION['lastID'];
                                  mysql_query($SQL1) or die(mysql_error()."PA-115<br>".$SQL1);

                                  $target_path = "procurement/mir/";
                                  $target_path = $target_path.$_REQUEST['mode']."_".basename( $_FILES['userfile']['name']);
                                  if($_FILES['userfile']['name']) $fileName= $_SESSION['lastID']."_".$_FILES['userfile']['name'];

                                  if (file_exists("procurement/mir/".$fileName)){
                                   echo "<center><STRONG>Sorry!!" .$fileName . " already exists.</center></STRONG>";
                                  }else{
                                   move_uploaded_file($_FILES['userfile']['tmp_name'], $target_path);
                                  }
                                  if(basename($_FILES['userfile']['name'])){
                                     $SQL1 = "UPDATE ".$_SESSION['CurrentObjectName']->TableName." SET userfile='".$fileName."' where id=".$_SESSION['lastID'];
                                     mysql_query($SQL1) or die(mysql_error()."PA-115<br>".$SQL1);
                                  }
                                  if($_REQUEST['action']=='save'){
                                   if($formname=="projectmaterialrequisition_service.php")header("location:editprojectmaterialrequisition_service.php?dr=edit&ID=".$_SESSION['lastID']."");
                                  }elseif($_REQUEST['action']=='savenew'){
                                   if($formname=="projectmaterialrequisition_service.php")header("location:editprojectmaterialrequisition_service.php?dr=add&ID=0");
                                  }elseif($_REQUEST['action']=='saveclose'){
                                   if($formname=="projectmaterialrequisition_service.php")header("location:projectmaterialrequisition_service.php");
                                  }
        }

                 if($formname=="newticketlist.php" || $formname=="newassetlist.php"){


                                  for($i=0 ;$i < $_REQUEST['selectslots']; $i++){
                                    if($i==0){
                                      $imgName1="";
                                      $seqNumber1 = GetLastSqeID("e_attachments");
                                      $target_path = $target_path.basename($_FILES['userfile']['name']);
                                      $target_path = "servicedocs/";
                                      $target_path = $target_path . basename( $_FILES['userfile']['name']);
                                      if($_FILES['userfile']['name']) $imgName1 = $temp.$_FILES['userfile']['name'];

                                       move_uploaded_file($_FILES['userfile']['tmp_name'], $target_path);
                                      if($imgName1!=""){
                                      $SQL1 = "insert into e_attachments(id,docid,docname,userid)
                                               values('$seqNumber1','".$_SESSION['lastID']."','".$imgName1 ."','".$_SESSION['SESSuserID']."') ";
                                      mysql_query($SQL1) or die(mysql_error()."PA-115<br>".$SQL1);
                                      }

                                    }else{
                                      $imgName1="";
                                      $field='userfile'.$i;
                                      $seqNumber1 = GetLastSqeID("e_attachments");
                                      $target_path = $target_path.basename($_FILES[$field]['name']);
                                      $target_path = "servicedocs/";
                                      $target_path = $target_path . basename( $_FILES[$field]['name']);
                                      if($_FILES[$field]['name']) $imgName1 = $temp.$_FILES[$field]['name'];

                                       move_uploaded_file($_FILES[$field]['tmp_name'], $target_path);

                                      if($imgName1."_".$i!=""){
                                      $SQL1 = "insert into e_attachments(id,docid,docname,userid)
                                               values('$seqNumber1','".$_SESSION['lastID']."','".$imgName1."','".$_SESSION['SESSuserID']."') ";
                                      mysql_query($SQL1) or die(mysql_error()."PA-115<br>".$SQL1);
                                      }
                                    }
                                }
                           if($formname=="newticketlist.php"){
                                if($_REQUEST['action']=='save'){
                                   if($formname=="newticketlist.php")header("location:editnewticketlist.php?dr=edit&ID=".$_SESSION['lastID']."");
                                }elseif($_REQUEST['action']=='savenew'){
                                   if($formname=="newticketlist.php")header("location:editnewticketlist.php?dr=add&ID=0");
                                }elseif($_REQUEST['action']=='saveclose'){
                                   if($formname=="newticketlist.php")header("location:newticketlist.php");
                                }
                           }else{

                                if($_REQUEST['action']=='save'){
                                   if($formname=="newassetlist.php")header("location:editnewassetlist.php?dr=edit&ID=".$_SESSION['lastID']."");
                                }elseif($_REQUEST['action']=='savenew'){
                                   if($formname=="newassetlist.php")header("location:editnewassetlist.php?dr=add&ID=0");
                                }elseif($_REQUEST['action']=='saveclose'){
                                   if($formname=="newassetlist.php")header("location:newassetlist.php");
                                }

                           }
        }




        if($formname=="newticketlist.php" || $formname=="emailrequestlist.php"){

                 $seqSQL = "SELECT LASTNUMBER FROM in_sequencer_crm WHERE TABLENAME='ticket'";
                 $result=mysql_query($seqSQL) or die(mysql_error()."<br>".$seqSQL);
                 $resulArr=mysql_fetch_array($result);
                 $updatedSeqID=$resulArr['LASTNUMBER']+1;

                 if($RequestBuffer['cmb_A_assetcode']==''){
                   $DocNo="CM-".$updatedSeqID;
                  }else{
                   $DocNo="PM-".$updatedSeqID;
                 }

            $SQL1 = "UPDATE tbl_ticket SET ticketno='".$DocNo."' where id=".$_SESSION['lastID'];
            mysql_query($SQL1) or die(mysql_error()."PA-115<br>".$SQL1);

           $squpdateSQL = "UPDATE in_sequencer_crm SET LASTNUMBER=LASTNUMBER+1 WHERE TABLENAME='ticket'";
           mysql_query($squpdateSQL) or die(mysql_error()."<br>".$squpdateSQL);

         if($formname=="emailrequestlist.php"){

            $SQL1 = "UPDATE tbl_emails SET ticket='YES' where id=".$_REQUEST['emailid'];
            mysql_query($SQL1) or die(mysql_error()."PA-115<br>".$SQL1);

         }

        }
         if($formname=="serviceslist.php"){

             $squpdateSQL = "UPDATE in_sequencer_crm SET LASTNUMBER=LASTNUMBER+1 WHERE TABLENAME='service'";
             mysql_query($squpdateSQL) or die(mysql_error()."<br>".$squpdateSQL);


            if($_REQUEST['action']=='save'){
                if($formname=="servceslist.php")header("location:editservceslist.php?dr=edit&ID=".$_SESSION['lastID']."");
            }elseif($_REQUEST['action']=='savenew'){
                if($formname=="servceslist.php")header("location:editservceslist.php?dr=add&ID=0");
            }elseif($_REQUEST['action']=='saveclose'){
                if($formname=="servceslist.php")header("location:servceslist.php");
            }
        }
        if($formname=="newleadheadlist.php"){
           $squpdateSQL = "UPDATE in_sequencer_crm SET LASTNUMBER=LASTNUMBER+1 WHERE TABLENAME='enquirylist'";
           mysql_query($squpdateSQL) or die(mysql_error()."<br>".$squpdateSQL);
        }
        if($formname=="quoteheadlist.php"){
           $squpdateSQL = "UPDATE in_sequencer_crm SET LASTNUMBER=LASTNUMBER+1 WHERE TABLENAME='quotelist'";
           mysql_query($squpdateSQL) or die(mysql_error()."<br>".$squpdateSQL);
        }
        if($formname=="crmteam.php"){

            for($ii=0 ;$ii < count($RequestBuffer['ckk_A_assignedto']); $ii++){
               $SQL   = "SELECT username from in_user where userid='".$RequestBuffer['ckk_A_assignedto'][$ii]."'";
               $SQLRes =  mysql_query($SQL) or die(mysql_error()."<br>".$SQL);
               if(mysql_num_rows($SQLRes)>=1){
                  while($loginResultArray   = mysql_fetch_array($SQLRes)){
                        $username .= $loginResultArray['username'].",";
                  }
               }
            }
            $username = substr($username,0,strlen($username)-1) ;
            $SQL   = "update in_crmteam set nameassignedto='$username' where id='".$_SESSION['lastID']."'";
            $SQLRes =  mysql_query($SQL) or die(mysql_error()."<br>".$SQL);

            if($_REQUEST['action']=='save'){
                if($formname=="crmteam.php")header("location:editcrmteam.php?dr=edit&ID=".$_SESSION['lastID']."");
            }elseif($_REQUEST['action']=='savenew'){
                if($formname=="crmteam.php")header("location:editcrmteam.php?dr=add&ID=0");
            }elseif($_REQUEST['action']=='saveclose'){
                if($formname=="crmteam.php")header("location:crmteam.php");
            }
        }

        if($formname=="trainingprogramme.php"){

            if($_REQUEST['action']=='save'){
                if($formname=="trainingprogramme.php")header("location:edittrainingprogramme.php?dr=edit&ID=".$_SESSION['lastID']."");
            }elseif($_REQUEST['action']=='savenew'){
                if($formname=="trainingprogramme.php")header("location:edittrainingprogramme.php?dr=add&ID=0");
            }elseif($_REQUEST['action']=='saveclose'){
                if($formname=="trainingprogramme.php")header("location:trainingprogramme.php");
            }
        }
        if($formname=="emp_appraisalrequest.php" ){

                  $SEL =  "select empcategory from in_personalinfo where empid='".$RequestBuffer['cmb_A_empid']."'";
                  $RES = mysql_query($SEL);
                  if(mysql_num_rows($RES)>=1){
                  $ARR = mysql_fetch_array($RES);
                       $empcategory = $ARR['empcategory'];
                  }
                  $SQL   = "select factor from in_kpientry where in_kpientry.kpistafftype='$empcategory' order by serialnumber";
                  $SQLRes =  mysql_query($SQL) or die(mysql_error()."<br>".$SQL);
                  if(mysql_num_rows($SQLRes)>=1){
                     while($loginResultArray   = mysql_fetch_array($SQLRes)){

                        $seqNumber1 = GetLastSqeID("e_appraisalfactors");
                        $SQL1 = "insert into e_appraisalfactors(id,appraisalid,factors) values('$seqNumber1','".$_SESSION['lastID']."','".$loginResultArray['factor']."')";
                        mysql_query($SQL1) or die(mysql_error()."PA-115<br>".$SQL1);
                     }
                  }
        }
        if($formname=="profitcenterlist.php"){
           $MyArr = mysql_fetch_array(mysql_query("select id as parenttableid from in_crmhead where docno='".$RequestBuffer['txt_A_salesorderno']."'"));
           $parenttableid = $MyArr['parenttableid'];
           $squpdateSQL = "UPDATE in_crmhead SET documentstatus='".$RequestBuffer['cmb_A_documentstatus']."' WHERE id='$parenttableid'";
           mysql_query($squpdateSQL) or die(mysql_error()."<br>".$squpdateSQL);
        }
        if($formname=="contractamendments.php"){
          for($i=0 ;$i < $_REQUEST['selectslots']; $i++){
                                    if($i==0){

                                      $seqNumber1 = GetLastSqeID("in_contractamendments");
                                      $target_path = $target_path.basename($_FILES['userfile']['name']);
                                      $target_path = "documents/";
                                      $target_path = $target_path .$seqNumber1."_". basename( $_FILES['userfile']['name']);
                                      if($_FILES['userfile']['name']) $imgName= $temp.$_FILES['userfile']['name'];

                                       move_uploaded_file($_FILES['userfile']['tmp_name'], $target_path);

                                      $SQL1 = "insert into in_contractamendments(id,docid,docname)
                                               values('$seqNumber1','".$_SESSION['lastID']."','".$imgName ."') ";
                                      mysql_query($SQL1) or die(mysql_error()."PA-115<br>".$SQL1);

                                    }else{
                                      $field='userfile'.$i;
                                      $seqNumber1 = GetLastSqeID("in_contractamendments");
                                      $target_path = $target_path.basename($_FILES[$field]['name']);
                                      $target_path = "documents/";
                                      $target_path = $target_path .$seqNumber1."_". basename( $_FILES[$field]['name']);
                                      if($_FILES[$field]['name']) $imgName= $temp.$_FILES[$field]['name'];

                                       move_uploaded_file($_FILES[$field]['tmp_name'], $target_path);


                                      $SQL1 = "insert into in_contractamendments(id,docid,docname)
                                               values('$seqNumber1','".$_SESSION['lastID']."','".$imgName ."') ";
                                      mysql_query($SQL1) or die(mysql_error()."PA-115<br>".$SQL1);

                                    }
                                }
             if($formname=="contractamendments.php")header("location:contractamendments.php?ID=".$_REQUEST['txt_A_docid']."");

         }

          if($formname=="storelist.php"){

                                  $charset="ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";
                                  $length=5;
                                  for($i=0; $i <=$length; $i++){
                                    $rand =rand() % strlen($charset);
                                    $temp=  substr($charset,$rand,3);
                                  }
                                  $target_path = "Stores/";
                                  $target_path = $target_path.$temp . basename( $_FILES['userfile']['name']);
                                  if($_FILES['userfile']['name']) $fileName= $temp.$_FILES['userfile']['name'];

                                  if (file_exists("Stores/".$fileName)){
                                   echo "<center><STRONG>Sorry!!" .$fileName . " already exists.</center></STRONG>";
                                  }else{
                                   move_uploaded_file($_FILES['userfile']['tmp_name'], $target_path);
                                  }
                                  if(basename($_FILES['userfile']['name'])){
                                  $SQL1 = "UPDATE ".$_SESSION['CurrentObjectName']->TableName." SET docname='".$fileName."' where id=".$_SESSION['lastID'];
                                  mysql_query($SQL1) or die(mysql_error()."PA-115<br>".$SQL1);
                                 }
                                 if($_REQUEST['action']=='save'){
                                   if($formname=="storelist.php")header("location:editstorelist.php?dr=edit&ID=".$_SESSION['lastID']."");
                                 }elseif($_REQUEST['action']=='savenew'){
                                   if($formname=="storelist.php")header("location:editstorelist.php?dr=add&ID=0");
                                 }
                                 elseif($_REQUEST['action']=='saveclose'){
                                   if($formname=="storelist.php")header("location:storelist.php");
                                 }
        }
        if($formname=="articlelist.php"){


                                  for($i=0 ;$i < $_REQUEST['selectslots']; $i++){
                                    if($i==0){

                                      $seqNumber1 = GetLastSqeID("in_articleupload");
                                      $target_path = $target_path.basename($_FILES['userfile']['name']);
                                      $target_path = "uploads/articles/";
                                      $target_path = $target_path .$seqNumber1."_". basename( $_FILES['userfile']['name']);
                                      if($_FILES['userfile']['name']) $imgName1 = $temp.$_FILES['userfile']['name'];

                                       move_uploaded_file($_FILES['userfile']['tmp_name'], $target_path);
                                      if($imgName1!=""){
                                      $SQL1 = "insert into in_articleupload(id,docid,docname,type)
                                               values('$seqNumber1','".$_SESSION['lastID']."','".$imgName1 ."','ARTICLE') ";
                                      mysql_query($SQL1) or die(mysql_error()."PA-115<br>".$SQL1);
                                      }

                                    }else{
                                      $field='userfile'.$i;
                                      $seqNumber1 = GetLastSqeID("in_articleupload");
                                      $target_path = $target_path.basename($_FILES[$field]['name']);
                                      $target_path = "uploads/articles/";
                                      $target_path = $target_path .$seqNumber1."_". basename( $_FILES[$field]['name']);
                                      if($_FILES[$field]['name']) $imgName1."_".$i = $temp.$_FILES[$field]['name'];

                                       move_uploaded_file($_FILES[$field]['tmp_name'], $target_path);

                                      if($imgName1."_".$i!=""){
                                      $SQL1 = "insert into in_articleupload(id,docid,docname,type)
                                               values('$seqNumber1','".$_SESSION['lastID']."','".$imgName1."_".$i ."','ARTICLE') ";
                                      mysql_query($SQL1) or die(mysql_error()."PA-115<br>".$SQL1);
                                      }
                                    }
                                }

                                for($j=0 ;$j < $_REQUEST['selectslots_tech']; $j++){
                                    if($j==0){

                                      $seqNumber1 = GetLastSqeID("in_articleupload");
                                      $target_path = $target_path.basename($_FILES['userfile_tech']['name']);
                                      $target_path = "uploads/articles/";
                                      $target_path = $target_path .$seqNumber1."_". basename( $_FILES['userfile_tech']['name']);
                                      if($_FILES['userfile_tech']['name']) $imgName2= $temp.$_FILES['userfile_tech']['name'];

                                       move_uploaded_file($_FILES['userfile_tech']['tmp_name'], $target_path);
                                      if($imgName2!=""){
                                      $SQL1 = "insert into in_articleupload(id,docid,docname,type)
                                               values('$seqNumber1','".$_SESSION['lastID']."','".$imgName2 ."','DESCRIPTION') ";
                                      mysql_query($SQL1) or die(mysql_error()."PA-115<br>".$SQL1);
                                      }

                                    }else{
                                      $field='userfile_tech'.$j;
                                      $seqNumber1 = GetLastSqeID("in_articleupload");
                                      $target_path = $target_path.basename($_FILES[$field]['name']);
                                      $target_path = "uploads/articles/";
                                      $target_path = $target_path .$seqNumber1."_". basename( $_FILES[$field]['name']);
                                      if($_FILES[$field]['name']) $imgName2."_".$j= $temp.$_FILES[$field]['name'];

                                       move_uploaded_file($_FILES[$field]['tmp_name'], $target_path);

                                      if($imgName2."_".$j!=""){
                                      $SQL1 = "insert into in_articleupload(id,docid,docname,type)
                                               values('$seqNumber1','".$_SESSION['lastID']."','".$imgName2."_".$j ."','DESCRIPTION') ";
                                      mysql_query($SQL1) or die(mysql_error()."PA-115<br>".$SQL1);
                                      }
                                    }
                                }
                                if($_REQUEST['action']=='save'){
                                   if($formname=="articlelist.php")header("location:editarticlelist.php?dr=edit&ID=".$_SESSION['lastID']."");
                                }elseif($_REQUEST['action']=='savenew'){
                                   if($formname=="articlelist.php")header("location:editarticlelist.php?dr=add&ID=0");
                                }
                                elseif($_REQUEST['action']=='saveclose'){
                                   if($formname=="articlelist.php")header("location:articlelist.php");
                                }

        }
         if($formname=="companyshifts.php"){
            $fromtime24hours = date("H:i", strtotime($RequestBuffer['txt_A_fromtime']));
            $toexplode     = explode(":",$RequestBuffer['txt_A_totime']);
            if($toexplode[0]==0){
              $RequestBuffer['txt_A_totime'] = str_Replace('am','',$RequestBuffer['txt_A_totime']);
            }
            $totime24hours = date("H:i", strtotime($RequestBuffer['txt_A_totime']));
            $totalhoursdifference = get_time_difference($fromtime24hours, $totime24hours);
            $Htemp1     = explode(":",$totalhoursdifference);

            $totalhoursinminutes = $Htemp1[0]*60 + $Htemp1[1];
            $totalhours = convertToHoursMins($totalhoursinminutes, '%02d:%02d');

            $Htemp     = explode(".",$RequestBuffer['txt_A_breaktime']);
            $totalbreaktimeinminutes = $Htemp[0]*60 + $Htemp[1];

            $workhoursinminutes =  $totalhoursinminutes*1-$totalbreaktimeinminutes*1;
            $workhours = convertToHoursMins($workhoursinminutes, '%02d:%02d');
            $SQL   = "update in_companyshift set workhours='$workhours' where id='".$_SESSION['lastID']."'";
            $SQLRes =  mysql_query($SQL) or die(mysql_error()."<br>".$SQL);

            if($_REQUEST['action']=='save'){
                if($formname=="companyshifts.php")header("location:editcompanyshifts.php?dr=edit&ID=".$_REQUEST['mode']."");
            }elseif($_REQUEST['action']=='savenew'){
                if($formname=="companyshifts.php")header("location:editcompanyshifts.php?dr=add&ID=0");
            }elseif($_REQUEST['action']=='saveclose'){
                if($formname=="companyshifts.php")header("location:companyshifts.php");
            }
         }
         if($formname=="companycluster.php"){
            for($ii=0 ;$ii < count($RequestBuffer['chk_A_company']); $ii++){
               $SQL   = "SELECT jobname from t_activitycenter where id='".$RequestBuffer['chk_A_company'][$ii]."'";
               $SQLRes =  mysql_query($SQL) or die(mysql_error()."<br>".$SQL);
               if(mysql_num_rows($SQLRes)>=1){
                  while($loginResultArray   = mysql_fetch_array($SQLRes)){
                        $custercompany .= $loginResultArray['jobname'].",";
                  }
               }
            }
            $custercompany = substr($custercompany,0,strlen($custercompany)-1) ;
            $SQL   = "update in_companycluster set companyname='$custercompany' where id='".$_SESSION['lastID']."'";
            $SQLRes =  mysql_query($SQL) or die(mysql_error()."<br>".$SQL);
            if($_REQUEST['action']=='save'){
                if($formname=="companycluster.php")header("location:editcompanycluster.php?dr=edit&ID=".$_SESSION['lastID']."");
            }elseif($_REQUEST['action']=='savenew'){
                if($formname=="companycluster.php")header("location:editcompanycluster.php?dr=add&ID=0");
            }elseif($_REQUEST['action']=='saveclose'){
                if($formname=="companycluster.php")header("location:companycluster.php");
            }
         }

         if($formname=="submenulist.php"){
            if($_REQUEST['action']=='save'){
                if($formname=="submenulist.php")header("location:editsubmenulist.php?dr=edit&cmb_lookuplist=".$_REQUEST['txt_A_parentid']."&ID=".$_SESSION['lastID']."");
            }elseif($_REQUEST['action']=='savenew'){
                if($formname=="submenulist.php")header("location:editsubmenulist.php?dr=add&cmb_lookuplist=".$_REQUEST['txt_A_parentid']."&ID=0");
            }elseif($_REQUEST['action']=='saveclose'){
                if($formname=="submenulist.php")header("location:submenulist.php?cmb_lookuplist=".$_REQUEST['txt_A_parentid']."");
            }
         }
         if($formname=="mainmenulist.php"){
            if($_REQUEST['action']=='save'){
                if($formname=="mainmenulist.php")header("location:editmainmenulist.php?dr=edit&ID=".$_SESSION['lastID']."");
            }elseif($_REQUEST['action']=='savenew'){
                if($formname=="mainmenulist.php")header("location:editmainmenulist.php?dr=add&ID=0");
            }elseif($_REQUEST['action']=='saveclose'){
                if($formname=="mainmenulist.php")header("location:mainmenulist.php");
            }
         }
         if($formname=="leavemaster.php"){
            if($_REQUEST['action']=='save'){
                if($formname=="leavemaster.php")header("location:editleavemaster.php?dr=edit&ID=".$_SESSION['lastID']."");
            }elseif($_REQUEST['action']=='savenew'){
                if($formname=="leavemaster.php")header("location:editleavemaster.php?dr=add&ID=0");
            }elseif($_REQUEST['action']=='saveclose'){
                if($formname=="leavemaster.php")header("location:leavemaster.php");
            }
         }
         if($formname=="lookup.php"){
            $squpdateSQL = "UPDATE in_sequencer SET LASTNUMBER=LASTNUMBER+1 WHERE TABLENAME='looktype'";
            mysql_query($squpdateSQL) or die(mysql_error()."<br>".$squpdateSQL);
         }
         if($formname=="bulletinboard.php"){
            if($_REQUEST['action']=='save'){
                if($formname=="bulletinboard.php")header("location:editbulletinboard.php?dr=edit&ID=".$_SESSION['lastID']."");
            }elseif($_REQUEST['action']=='savenew'){
                if($formname=="bulletinboard.php")header("location:editbulletinboard.php?dr=add&ID=0");
            }elseif($_REQUEST['action']=='saveclose'){
                if($formname=="bulletinboard.php")header("location:bulletinboard.php");
            }
         }

         if($formname=="editnewuserdetails.php"){
            if($_REQUEST['action']=='save'){
                if($formname=="editnewuserdetails.php")header("location:editnewuserdetails.php?dr=edit&ID=".$_SESSION['lastID']."");
            }elseif($_REQUEST['action']=='savenew'){
                if($formname=="editnewuserdetails.php")header("location:editnewuserdetails.php?dr=add&ID=0");
            }elseif($_REQUEST['action']=='saveclose'){
                if($formname=="editnewuserdetails.php")header("location:editnewuserdetails.php");
            }
         }
         if($formname=="lookuplist.php"){
            $squpdateSQL = "UPDATE in_sequencer SET LASTNUMBER=LASTNUMBER+1 WHERE TABLENAME='lookcode'";
            mysql_query($squpdateSQL) or die(mysql_error()."<br>".$squpdateSQL);
         }

         if($formname=="lookup.php"){
            $squpdateSQL = "UPDATE in_sequencer SET LASTNUMBER=LASTNUMBER+1 WHERE TABLENAME='looktypee'";
            mysql_query($squpdateSQL) or die(mysql_error()."<br>".$squpdateSQL);
         }
         if($formname=="jobrequest.php"){

            if($_REQUEST['action']=='save'){
                    if($formname=="jobrequest.php")header("location:editjobrequest.php?dr=edit&ID=".$_SESSION['lastID']."");
                }elseif($_REQUEST['action']=='savenew'){
                    if($formname=="jobrequest.php")header("location:editjobrequest.php?dr=add&ID=0");
                }elseif($_REQUEST['action']=='saveclose'){
                    if($formname=="jobrequest.php")header("location:jobrequest.php");
            }
         }
         if($formname=="jobrequest_hr.php"){

            if($_REQUEST['action']=='save'){
                    if($formname=="jobrequest_hr.php")header("location:editjobrequest_hr.php?dr=edit&ID=".$_SESSION['lastID']."");
                }elseif($_REQUEST['action']=='savenew'){
                    if($formname=="jobrequest_hr.php")header("location:editjobrequest_hr.php?dr=add&ID=0");
                }elseif($_REQUEST['action']=='saveclose'){
                    if($formname=="jobrequest_hr.php")header("location:jobrequest_hr.php");
            }
         }
         if($formname=="hrmanpowerrequisition.php"){

            if($_REQUEST['action']=='save'){
                    if($formname=="hrmanpowerrequisition.php")header("location:edithrmanpowerrequisition.php?dr=edit&ID=".$_SESSION['lastID']."");
                }elseif($_REQUEST['action']=='savenew'){
                    if($formname=="hrmanpowerrequisition.php")header("location:edithrmanpowerrequisition.php?dr=add&ID=0");
                }elseif($_REQUEST['action']=='saveclose'){
                    if($formname=="hrmanpowerrequisition.php")header("location:hrmanpowerrequisition.php");
            }
         }

           if($formname=="emp_leaverequest.php" ){


                  $SQL   = "Select datediff(leaveto,leavefrom) as days,leavefrom,leaveto from e_leave  WHERE id='".$_SESSION['lastID']."'";
                  $SQLRes =  mysql_query($SQL) or die(mysql_error()."<br>".$SQL);
                  if(mysql_num_rows($SQLRes)>=1){
                   $loginResultArray   = mysql_fetch_array($SQLRes);
                   $days= $loginResultArray['days']+1;
                  }
                        $SQL   = "SELECT LASTNUMBER AS LASTNUMBER  FROM in_sequencer WHERE TABLENAME='e_leave'";
                        $SQLRes =  mysql_query($SQL) or die(mysql_error()."<br>".$SQL);
                        if(mysql_num_rows($SQLRes)>=1){
                          while($loginResultArray   = mysql_fetch_array($SQLRes)){
                             $instructionid=$loginResultArray['LASTNUMBER']+1;
                          }
                        }

                $SQL2 = "UPDATE e_leave SET daysallowed=".$days." where id=".$_SESSION['lastID'];
                mysql_query($SQL2) or die(mysql_error()."PA-115<br>".$SQL2);



                $squpdateSQL = "UPDATE in_sequencer SET LASTNUMBER=".$instructionid." WHERE TABLENAME='e_leave'";
                mysql_query($squpdateSQL) or die(mysql_error()."<br>".$squpdateSQL);

                 //if($formname=="emp_leave.php")header("location:emp_leave.php?ID=".$_REQUEST['txt_A_staffid']."");
         }

          if($formname=="companysetup.php"){

                                  $charset="ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";
                                  $length=5;
                                  for($i=0; $i <=$length; $i++){
                                    $rand =rand() % strlen($charset);
                                    $temp=  substr($charset,$rand,3);
                                  }
                                  $target_path = "logo/";
                                  $target_path = $target_path.$temp . basename( $_FILES['userfile']['name']);
                                  if($_FILES['userfile']['name']) $fileName= $temp.$_FILES['userfile']['name'];

                                  if (file_exists("logo/".$fileName)){
                                   echo "<center><STRONG>Sorry!!" .$fileName . " already exists.</center></STRONG>";
                                  }else{
                                   move_uploaded_file($_FILES['userfile']['tmp_name'], $target_path);
                                  }
                                  if(basename($_FILES['userfile']['name'])){
                                  $SQL1 = "UPDATE ".$_SESSION['CurrentObjectName']->TableName." SET companylogo='".$fileName."' where id=".$_SESSION['lastID'];
                                  mysql_query($SQL1) or die(mysql_error()."PA-115<br>".$SQL1);
                                 }
                                 if($_REQUEST['action']=='save'){
                                   if($formname=="companysetup.php")header("location:editcompanysetup.php?dr=edit&ID=".$_SESSION['lastID']."");
                                 }elseif($_REQUEST['action']=='savenew'){
                                   if($formname=="companysetup.php")header("location:editcompanysetup.php?dr=add&ID=0");
                                 }
                                 elseif($_REQUEST['action']=='saveclose'){
                                   if($formname=="companysetup.php")header("location:companysetup.php");
                                 }
                   }
          if($formname=="mydocrequest.php"){

                                 if($_REQUEST['action']=='save'){
                                   if($formname=="mydocrequest.php")header("location:editmydocrequest.php?dr=edit&ID=".$_SESSION['lastID']."");
                                 }elseif($_REQUEST['action']=='savenew'){
                                   if($formname=="mydocrequest.php")header("location:editmydocrequest.php?dr=add&ID=0");
                                 }
                                 elseif($_REQUEST['action']=='saveclose'){
                                   if($formname=="mydocrequest.php")header("location:mydocrequest.php");
                                 }
          }
          if($formname=="mydocrequest_hr.php"){

                                  $charset="ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";
                                  $length=5;
                                  for($i=0; $i <=$length; $i++){
                                    $rand =rand() % strlen($charset);
                                    $temp=  substr($charset,$rand,3);
                                  }
                                  $target_path = "documents/";
                                  $target_path = $target_path.$temp . basename( $_FILES['userfile']['name']);
                                  if($_FILES['userfile']['name']) $fileName= $temp.$_FILES['userfile']['name'];

                                  if (file_exists("documents/".$fileName)){
                                   echo "<center><STRONG>Sorry!!" .$fileName . " already exists.</center></STRONG>";
                                  }else{
                                   move_uploaded_file($_FILES['userfile']['tmp_name'], $target_path);
                                  }
                                  if(basename($_FILES['userfile']['name'])){
                                  $SQL1 = "UPDATE ".$_SESSION['CurrentObjectName']->TableName." SET docname='".$fileName."' where id=".$_SESSION['lastID'];
                                  mysql_query($SQL1) or die(mysql_error()."PA-115<br>".$SQL1);
                                 }

                                 if($_REQUEST['action']=='save'){
                                   if($formname=="mydocrequest_hr.php")header("location:editmydocrequest_hr.php?dr=edit&ID=".$_SESSION['lastID']."");
                                 }elseif($_REQUEST['action']=='savenew'){
                                   if($formname=="mydocrequest_hr.php")header("location:editmydocrequest_hr.php?dr=add&ID=0");
                                 }
                                 elseif($_REQUEST['action']=='saveclose'){
                                   if($formname=="mydocrequest_hr.php")header("location:mydocrequest_hr.php");
                                 }
          }
         if($formname=="newuserdetails.php"){

                                  $charset="ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";
                                  $length=5;
                                  for($i=0; $i <=$length; $i++){
                                    $rand =rand() % strlen($charset);
                                    $temp=  substr($charset,$rand,3);
                                  }
                                  $target_path = "staffphoto/";
                                  $target_path = $target_path.$temp . basename( $_FILES['userfile']['name']);
                                  if($_FILES['userfile']['name']) $fileName= $temp.$_FILES['userfile']['name'];

                                  if (file_exists("staffphoto/".$fileName)){
                                   echo "<center><STRONG>Sorry!!" .$fileName . " already exists.</center></STRONG>";
                                  }else{
                                   move_uploaded_file($_FILES['userfile']['tmp_name'], $target_path);
                                  }
                                  if(basename($_FILES['userfile']['name'])){
                                  $SQL1 = "UPDATE ".$_SESSION['CurrentObjectName']->TableName." SET userimg='".$fileName."' where id=".$_SESSION['lastID'];
                                  mysql_query($SQL1) or die(mysql_error()."PA-115<br>".$SQL1);
                                 }
                                 if($_REQUEST['action']=='save'){
                                   if($formname=="newuserdetails.php")header("location:editnewuserdetails.php?dr=edit&ID=".$_SESSION['lastID']."&cmb_lookuplist=".$_REQUEST['txt_A_companycode']."");
                                 }elseif($_REQUEST['action']=='savenew'){
                                   if($formname=="newuserdetails.php")header("location:editnewuserdetails.php?dr=add&ID=0&cmb_lookuplist=".$_REQUEST['txt_A_companycode']."");
                                 }
                                 elseif($_REQUEST['action']=='saveclose'){
                                   if($formname=="newuserdetails.php")header("location:newuserdetails.php");
                                 }
                   }
         if($formname=="employeemaster.php"){
            $squpdateSQL = "UPDATE in_location SET lastnumber=lastnumber+1 WHERE locationcode='".$_REQUEST['cmb_A_empsponsercompany']."'";
            mysql_query($squpdateSQL) or die(mysql_error()."<br>".$squpdateSQL);



                                  $charset="ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";
                                  $length=5;
                                  for($i=0; $i <=$length; $i++){
                                    $rand =rand() % strlen($charset);
                                    $temp=  substr($charset,$rand,3);
                                  }
                                  $target_path = "staffphoto/";
                                  $target_path = $target_path.$temp . basename( $_FILES['userfile']['name']);
                                  if($_FILES['userfile']['name']) $fileName= $temp.$_FILES['userfile']['name'];

                                  if (file_exists("staffphoto/".$fileName)){
                                   echo "<center><STRONG>Sorry!!" .$fileName . " already exists.</center></STRONG>";
                                  }else{
                                   move_uploaded_file($_FILES['userfile']['tmp_name'], $target_path);
                                  }
                                  if(basename($_FILES['userfile']['name'])){
                                  $SQL1 = "UPDATE ".$_SESSION['CurrentObjectName']->TableName." SET empimage='".$fileName."' where id=".$_SESSION['lastID'];
                                  mysql_query($SQL1) or die(mysql_error()."PA-115<br>".$SQL1);
                                 }

                                 //$squpdateSQL = "UPDATE in_personalinfo SET rolecode=concat('EMPLOYEE,',rolecode) WHERE id=".$_SESSION['lastID'];
                                 //mysql_query($squpdateSQL) or die(mysql_error()."<br>".$squpdateSQL);

                                 $SQL   = "SELECT rolecode FROM in_personalinfo WHERE id=".$_SESSION['lastID'];
                                 $SQLRes =  mysql_query($SQL) or die(mysql_error()."<br>".$SQL);
                                 if(mysql_num_rows($SQLRes)>=1){
                                   while($loginResultArray   = mysql_fetch_array($SQLRes)){
                                    $savedrole=$loginResultArray['rolecode'];
                                   }
                                 }
                                 $seqID = GetLastSqeID("in_user");
                                 $insAccountSQL = "INSERT INTO in_user(ID,userid,username,rolecode,acclocationcode,pwd,email,status)
                                                   VALUES('$seqID','".$RequestBuffer['txt_A_empid']."','".$_REQUEST['txt_A_empfirstename']." ".$RequestBuffer['txt_A_emplastename']."','".$savedrole."','".$RequestBuffer['cmb_A_empcompany']."','123','".$RequestBuffer['txt_A_empworkemail']."','ACTIVE')";
                                 mysql_query($insAccountSQL) or die(mysql_error()."<br>".$insAccountSQL);

                                 $squpdateSQL1 = "UPDATE in_personalinfo SET empreportingofficer='0' WHERE id='".$_SESSION['lastID']."' and empdesignation='17001'";
                                 mysql_query($squpdateSQL1) or die(mysql_error()."<br>".$squpdateSQL1);

                                 if($_REQUEST['action']=='save'){
                                   if($formname=="employeemaster.php")header("location:editemployeemaster.php?cmb_lookuplist=".$_REQUEST['cmb_A_empsponsercompany']."&dr=edit&ID=".$_SESSION['lastID']."");
                                 }elseif($_REQUEST['action']=='savenew'){
                                   if($formname=="employeemaster.php")header("location:editemployeemaster.php?cmb_lookuplist=".$_REQUEST['cmb_A_empsponsercompany']."&dr=add&ID=0");
                                 }
                                 elseif($_REQUEST['action']=='saveclose'){
                                   if($formname=="employeemaster.php")header("location:employeemaster.php");
                                 }


          }
          if($formname=="businessobjectlist.php"){

            $squpdateSQL = "UPDATE in_sequencer SET LASTNUMBER=LASTNUMBER+1 WHERE TABLENAME='".$_REQUEST['txt_A_objecttype']."'";
            mysql_query($squpdateSQL) or die(mysql_error()."<br>".$squpdateSQL);

                                  $charset="ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";
                                  $length=5;
                                  for($i=0; $i <=$length; $i++){
                                    $rand =rand() % strlen($charset);
                                    $temp=  substr($charset,$rand,3);
                                  }
                                  $target_path = "businesscards/";
                                  $target_path = $target_path.$temp . basename( $_FILES['userfile']['name']);
                                  if($_FILES['userfile']['name']) $fileName= $temp.$_FILES['userfile']['name'];

                                  if (file_exists("businesscards/".$fileName)){
                                   echo "<center><STRONG>Sorry!!" .$fileName . " already exists.</center></STRONG>";
                                  }else{
                                   move_uploaded_file($_FILES['userfile']['tmp_name'], $target_path);
                                  }
                                  if(basename($_FILES['userfile']['name'])){
                                  $SQL1 = "UPDATE ".$_SESSION['CurrentObjectName']->TableName." SET docname='".$fileName."' where id=".$_SESSION['lastID'];
                                  mysql_query($SQL1) or die(mysql_error()."PA-115<br>".$SQL1);
                                 }
                                 if($_REQUEST['action']=='save'){
                                   if($formname=="businessobjectlist.php")header("location:editbusinessobjectlist.php?dr=edit&cmb_lookuplist=".$_REQUEST['txt_A_objecttype']."&ID=".$_SESSION['lastID']."");
                                 }elseif($_REQUEST['action']=='savenew'){
                                   if($formname=="businessobjectlist.php")header("location:editbusinessobjectlist.php?dr=add&cmb_lookuplist=".$_REQUEST['txt_A_objecttype']."&ID=0");
                                 }
                                 elseif($_REQUEST['action']=='saveclose'){
                                   if($formname=="businessobjectlist.php")header("location:businessobjectlist.php");
                                 }


        }
    }

    function PostUpdateConditions($tblName,$RequestBuffer){

        $formname = $_SESSION['CurrentObjectName']->formName;


           if($formname=="projectmaterialrequisition_service.php"){


                                  $target_path = "procurement/mir/";
                                  $target_path = $target_path.$_REQUEST['mode']."_". basename( $_FILES['userfile']['name']);
                                  if($_FILES['userfile']['name']) $fileName= $_REQUEST['mode']."_".$_FILES['userfile']['name'];

                                  if (file_exists("Articles/".$fileName)){
                                   echo "<center><STRONG>Sorry!!" .$fileName . " already exists.</center></STRONG>";
                                  }else{
                                   move_uploaded_file($_FILES['userfile']['tmp_name'], $target_path);
                                  }
                                  if(basename($_FILES['userfile']['name'])){
                                    $SQL1 = "UPDATE ".$_SESSION['CurrentObjectName']->TableName." SET userfile='".$fileName."' where id=".$_REQUEST['mode'];
                                    mysql_query($SQL1) or die(mysql_error()."PA-115<br>".$SQL1);
                                  }
                                  if($_REQUEST['action']=='save'){
                                    if($formname=="projectmaterialrequisition_service.php")header("location:editprojectmaterialrequisition_service.php?dr=edit&ID=".$_REQUEST['mode']."");
                                  }elseif($_REQUEST['action']=='savenew'){
                                    if($formname=="projectmaterialrequisition_service.php")header("location:editprojectmaterialrequisition_service.php?dr=add&ID=0");
                                  }elseif($_REQUEST['action']=='saveclose'){
                                   if($formname=="projectmaterialrequisition_service.php")header("location:projectmaterialrequisition_service.php");
                                  }
        }
        if($formname=="serviceslist.php"){


            if($_REQUEST['action']=='save'){
                if($formname=="serviceslist.php")header("location:editserviceslist.php?dr=edit&ID=".$_REQUEST['mode']."");
            }elseif($_REQUEST['action']=='savenew'){
                if($formname=="serviceslist.php")header("location:editserviceslist.php?dr=add&ID=0");
            }elseif($_REQUEST['action']=='saveclose'){
                if($formname=="serviceslist.php")header("location:serviceslist.php");
            }
        }
        if($formname=="newticketlist.php" || $formname=="newassetlist.php"){

                                  for($i=0 ;$i < $_REQUEST['selectslots']; $i++){


                                    if($i==0){
                                      $imgName1="";
                                      $seqNumber1 = GetLastSqeID("e_attachments");
                                      $target_path = $target_path.basename($_FILES['userfile']['name']);
                                      $target_path = "servicedocs/";
                                      $target_path = $target_path . basename( $_FILES['userfile']['name']);
                                      if($_FILES['userfile']['name']) $imgName1 = $temp.$_FILES['userfile']['name'];

                                      move_uploaded_file($_FILES['userfile']['tmp_name'], $target_path);
                                      if($imgName1!=""){
                                      $SQL1 = "insert into e_attachments(id,docid,docname,userid)
                                               values('$seqNumber1','".$_REQUEST['mode']."','".$imgName1 ."','".$_SESSION['SESSuserID']."') ";
                                      mysql_query($SQL1) or die(mysql_error()."PA-115<br>".$SQL1);
                                      }

                                    }else{
                                      $imgName1="";
                                      $field='userfile'.$i;
                                      $seqNumber1 = GetLastSqeID("e_attachments");
                                      $target_path = $target_path.basename($_FILES[$field]['name']);
                                      $target_path = "servicedocs/";
                                      $target_path = $target_path . basename( $_FILES[$field]['name']);
                                      if($_FILES[$field]['name']) $imgName1 = $temp.$_FILES[$field]['name'];

                                      move_uploaded_file($_FILES[$field]['tmp_name'], $target_path);

                                      if($imgName1!=""){
                                      $SQL1 = "insert into e_attachments(id,docid,docname,userid)
                                               values('$seqNumber1','".$_REQUEST['mode']."','".$imgName1 ."','".$_SESSION['SESSuserID']."') ";
                                      mysql_query($SQL1) or die(mysql_error()."PA-115<br>".$SQL1);
                                      }

                                    }
                                    //echo $SQL1;exit;
                                }
                       if($formname=="newticketlist.php"){
                               if($_REQUEST['action']=='save'){
                                   if($formname=="newticketlist.php")header("location:editnewticketlist.php?dr=edit&ID=".$_REQUEST['mode']."");
                                }elseif($_REQUEST['action']=='savenew'){
                                   if($formname=="newticketlist.php")header("location:editnewticketlist.php?dr=add&ID=0");
                                }
                                elseif($_REQUEST['action']=='saveclose'){
                                   if($formname=="newticketlist.php")header("location:newticketlist.php");
                                }
                       }else{

                               if($_REQUEST['action']=='save'){
                                   if($formname=="newassetlist.php")header("location:editnewassetlist.php?dr=edit&ID=".$_REQUEST['mode']."");
                                }elseif($_REQUEST['action']=='savenew'){
                                   if($formname=="newassetlist.php")header("location:editnewassetlist.php?dr=add&ID=0");
                                }
                                elseif($_REQUEST['action']=='saveclose'){
                                   if($formname=="newassetlist.php")header("location:newassetlist.php");
                                }

                       }
        }
         if($formname=="crmleadvisitlistcontact.php"){
                   $seqID = GetLastSqeID("in_crmvisit");

               if($_REQUEST['cmb_A_status']=="Happened" && $_REQUEST['txd_A_followupdate']!=""){

                   $Dvalue  = explode('-',$_REQUEST['txd_A_followupdate']);
                   $value   = $Dvalue[2].'-'.$Dvalue[1].'-'.$Dvalue[0];

                   $deletesql1 = "update in_crmvisit set status='Happened' where objectcode='".$_REQUEST['txt_A_objectcode']."'";
                   $result   = mysql_query($deletesql1)  or die(mysql_error()."<br>".$deletesql1);

                   $SQL2        = "Insert into in_crmvisit(
                                  id,activitytype,objectcode,objectname,visitdate,
                                  eventtype,priority,location,status,action,companycode,locationcode,userid)
                                  values (".$seqID.",'".$_REQUEST['cmb_A_activitytype']."','".$_REQUEST['txt_A_objectcode']."','".$_REQUEST['txt_A_objectname']."','".$value."',
                                  '".$_REQUEST['cmb_A_eventtype']."','".$_REQUEST['cmb_A_priority']."','".$_REQUEST['txt_A_location']."','Open','".$_REQUEST['txa_A_followupdetail']."'
                                  ,'','','".$_SESSION['SESSuserID']."')";

                   $SQLRes     =  mysql_query($SQL2) or die($SQL2);
               }
        }
        if($formname=="crmcontactlist.php"){

                   if($RequestBuffer['cmb_A_fwduserid']!='' && $RequestBuffer['cmb_A_fwduserid']!='Select') {
                      $SQL2 = "UPDATE in_businessobject SET fwdstatus='FORWARDED' where id=".$_REQUEST['mode'];
                      mysql_query($SQL2) or die(mysql_error()."PA-115<br>".$SQL2);
                   }
                   if($RequestBuffer['cmb_A_shareuserid']!='') {
                      $SQL2 = "UPDATE in_businessobject SET teleaction='SHARED' where id=".$_REQUEST['mode'];
                      mysql_query($SQL2) or die(mysql_error()."PA-115<br>".$SQL2);
                   }
        }
        if($formname=="crmteam.php"){

            for($ii=0 ;$ii < count($RequestBuffer['ckk_A_assignedto']); $ii++){
               $SQL   = "SELECT username from in_user where userid='".$RequestBuffer['ckk_A_assignedto'][$ii]."'";
               $SQLRes =  mysql_query($SQL) or die(mysql_error()."<br>".$SQL);
               if(mysql_num_rows($SQLRes)>=1){
                  while($loginResultArray   = mysql_fetch_array($SQLRes)){
                        $username .= $loginResultArray['username'].",";
                  }
               }
            }
            $username = substr($username,0,strlen($username)-1) ;
            $SQL   = "update in_crmteam set nameassignedto='$username' where id='".$_REQUEST['mode']."'";
            $SQLRes =  mysql_query($SQL) or die(mysql_error()."<br>".$SQL);

            if($_REQUEST['action']=='save'){
                if($formname=="crmteam.php")header("location:editcrmteam.php?dr=edit&ID=".$_REQUEST['mode']."");
            }elseif($_REQUEST['action']=='savenew'){
                if($formname=="crmteam.php")header("location:editcrmteam.php?dr=add&ID=0");
            }elseif($_REQUEST['action']=='saveclose'){
                if($formname=="crmteam.php")header("location:crmteam.php");
            }
        }
        if($formname=="trainingprogramme.php"){

            if($_REQUEST['action']=='save'){
                if($formname=="trainingprogramme.php")header("location:edittrainingprogramme.php?dr=edit&ID=".$_REQUEST['mode']."");
            }elseif($_REQUEST['action']=='savenew'){
                if($formname=="trainingprogramme.php")header("location:edittrainingprogramme.php?dr=add&ID=0");
            }elseif($_REQUEST['action']=='saveclose'){
                if($formname=="trainingprogramme.php")header("location:trainingprogramme.php");
            }
        }
        if($formname=="profitcenterlist.php"){
           $MyArr = mysql_fetch_array(mysql_query("select id as parenttableid from in_crmhead where docno='".$RequestBuffer['txt_A_salesorderno']."'"));
           $parenttableid = $MyArr['parenttableid'];
           $squpdateSQL = "UPDATE in_crmhead SET documentstatus='".$RequestBuffer['cmb_A_documentstatus']."' WHERE id='$parenttableid'";
           mysql_query($squpdateSQL) or die(mysql_error()."<br>".$squpdateSQL);
        
	  	  $temp="";
          $temp= "PRC".$_REQUEST['mode']."A$$$";
          $target_path = "uploads/";
          $target_path = $target_path.$temp . basename( $_FILES['userfile']['name']);
          if($_FILES['userfile']['name']) $fileName= $temp.$_FILES['userfile']['name'];

          if (file_exists("uploads/".$fileName)){
           echo "<center><STRONG>Sorry!!!" .$fileName . " already exists.</center></STRONG>";
          }else{
           move_uploaded_file($_FILES['userfile']['tmp_name'], $target_path);
          }
          if(basename($_FILES['userfile']['name'])){
          $SQL1 = "UPDATE ".$_SESSION['CurrentObjectName']->TableName." SET approvaldocumnet='".$fileName."' where id=".$_REQUEST['mode'];
          mysql_query($SQL1) or die(mysql_error()."PA-115<br>".$SQL1);
          }

          $temp="";
          $temp= "PRC".$_REQUEST['mode']."B$$$";
          $target_path = "uploads/";
          $target_path = $target_path.$temp . basename( $_FILES['userfile1']['name']);
          if($_FILES['userfile1']['name']) $fileName= $temp.$_FILES['userfile1']['name'];

          if (file_exists("uploads/".$fileName)){
           echo "<center><STRONG>Sorry!!" .$fileName . " already exists.</center></STRONG>";
          }else{
           move_uploaded_file($_FILES['userfile1']['tmp_name'], $target_path);
          }
          if(basename($_FILES['userfile1']['name'])){
          $SQL1 = "UPDATE ".$_SESSION['CurrentObjectName']->TableName." SET contractdoc='".$fileName."' where id=".$_REQUEST['mode'];
          mysql_query($SQL1) or die(mysql_error()."PA-115<br>".$SQL1);
          }
        
		if($_REQUEST['action']=='save'){
			if($formname=="profitcenterlist.php")header("location:editprofitcenterlist.php?txtsearch=".$_REQUEST['searchvalue']."&frmPage_rowcount==".$_REQUEST['frmPage_rowcount']."&dr=edit&ID=".$_REQUEST['mode']."");
			}
		elseif($_REQUEST['action']=='savenew'){
			if($formname=="profitcenterlist.php")header("location:editprofitcenterlist.php?txtsearch=".$_REQUEST['searchvalue']."&frmPage_rowcount==".$_REQUEST['frmPage_rowcount']."&dr=add&ID=0");
			}
		elseif($_REQUEST['action']=='saveclose'){
			if($formname=="profitcenterlist.php")header("location:profitcenterlist.php");
			}
        
        }
        if($formname=="contractamendments.php"){

          for($i=0 ;$i < $_REQUEST['selectslots']; $i++){
                                    if($i==0){

                                      $seqNumber1 = GetLastSqeID("in_contractamendments");
                                      $target_path = $target_path.basename($_FILES['userfile']['name']);
                                      $target_path = "uploads/";
                                      $target_path = $target_path .$seqNumber1."_". basename( $_FILES['userfile']['name']);
                                      if($_FILES['userfile']['name']) $imgName= $temp.$_FILES['userfile']['name'];

                                       move_uploaded_file($_FILES['userfile']['tmp_name'], $target_path);

                                      $SQL1 = "insert into in_contractamendments(id,docid,docname)
                                               values('$seqNumber1','".$_REQUEST['childid']."','".$imgName ."') ";
                                      mysql_query($SQL1) or die(mysql_error()."PA-115<br>".$SQL1);

                                    }else{
                                      $field='userfile'.$i;
                                      $seqNumber1 = GetLastSqeID("in_contractamendments");
                                      $target_path = $target_path.basename($_FILES[$field]['name']);
                                      $target_path = "uploads/";
                                      $target_path = $target_path .$seqNumber1."_". basename( $_FILES[$field]['name']);
                                      if($_FILES[$field]['name']) $imgName= $temp.$_FILES[$field]['name'];

                                       move_uploaded_file($_FILES[$field]['tmp_name'], $target_path);


                                      $SQL1 = "insert into in_contractamendments(id,docid,docname)
                                               values('$seqNumber1','".$_REQUEST['childid']."','".$imgName ."') ";
                                      mysql_query($SQL1) or die(mysql_error()."PA-115<br>".$SQL1);

                                    }
                                }
             if($formname=="contractamendments.php")header("location:contractamendments.php?ID=".$_REQUEST['txt_A_docid']."");

         }
          if($formname=="storelist.php"){

                                  $charset="ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";
                                  $length=5;
                                  for($i=0; $i <=$length; $i++){
                                    $rand =rand() % strlen($charset);
                                    $temp=  substr($charset,$rand,3);
                                  }
                                  $target_path = "Stores/";
                                  $target_path = $target_path.$temp . basename( $_FILES['userfile']['name']);
                                  if($_FILES['userfile']['name']) $fileName= $temp.$_FILES['userfile']['name'];

                                  if (file_exists("Stores/".$fileName)){
                                   echo "<center><STRONG>Sorry!!" .$fileName . " already exists.</center></STRONG>";
                                  }else{
                                   move_uploaded_file($_FILES['userfile']['tmp_name'], $target_path);
                                  }
                                  if(basename($_FILES['userfile']['name'])){
                                  $SQL1 = "UPDATE ".$_SESSION['CurrentObjectName']->TableName." SET docname='".$fileName."' where id=".$_REQUEST['mode'];
                                  mysql_query($SQL1) or die(mysql_error()."PA-115<br>".$SQL1);
                                 }
                                 if($_REQUEST['action']=='save'){
                                   if($formname=="storelist.php")header("location:editstorelist.php?dr=edit&ID=".$_REQUEST['mode']."");
                                 }elseif($_REQUEST['action']=='savenew'){
                                   if($formname=="storelist.php")header("location:editstorelist.php?dr=add&ID=0");
                                 }
                                 elseif($_REQUEST['action']=='saveclose'){
                                   if($formname=="storelist.php")header("location:storelist.php");
                                 }
        }
        if($formname=="articlelist.php"){

                                for($i=0 ;$i < $_REQUEST['selectslots']; $i++){
                                    if($i==0){

                                      $seqNumber1 = GetLastSqeID("in_articleupload");
                                      $target_path = $target_path.basename($_FILES['userfile']['name']);
                                      $target_path = "uploads/articles/";
                                      $target_path = $target_path .$seqNumber1."_". basename( $_FILES['userfile']['name']);
                                      if($_FILES['userfile']['name']) $imgName1 = $temp.$_FILES['userfile']['name'];

                                       move_uploaded_file($_FILES['userfile']['tmp_name'], $target_path);
                                      if($imgName1!=""){
                                      $SQL1 = "insert into in_articleupload(id,docid,docname,type)
                                               values('$seqNumber1','".$_REQUEST['mode']."','".$imgName1 ."','ARTICLE') ";
                                      mysql_query($SQL1) or die(mysql_error()."PA-115<br>".$SQL1);
                                      }

                                    }else{
                                      $field='userfile'.$i;
                                      $seqNumber1 = GetLastSqeID("in_articleupload");
                                      $target_path = $target_path.basename($_FILES[$field]['name']);
                                      $target_path = "uploads/articles/";
                                      $target_path = $target_path .$seqNumber1."_". basename( $_FILES[$field]['name']);
                                      if($_FILES[$field]['name']) $imgName1."_".$i = $temp.$_FILES[$field]['name'];

                                       move_uploaded_file($_FILES[$field]['tmp_name'], $target_path);

                                      if($imgName1."_".$i!=""){
                                      $SQL1 = "insert into in_articleupload(id,docid,docname,type)
                                               values('$seqNumber1','".$_REQUEST['mode']."','".$imgName1."_".$i ."','ARTICLE') ";
                                      mysql_query($SQL1) or die(mysql_error()."PA-115<br>".$SQL1);
                                      }
                                    }
                                }

                                for($j=0 ;$j < $_REQUEST['selectslots_tech']; $j++){
                                    if($j==0){

                                      $seqNumber1 = GetLastSqeID("in_articleupload");
                                      $target_path = $target_path.basename($_FILES['userfile_tech']['name']);
                                      $target_path = "uploads/articles/";
                                      $target_path = $target_path .$seqNumber1."_". basename( $_FILES['userfile_tech']['name']);
                                      if($_FILES['userfile_tech']['name']) $imgName2= $temp.$_FILES['userfile_tech']['name'];

                                       move_uploaded_file($_FILES['userfile_tech']['tmp_name'], $target_path);
                                      if($imgName2!=""){
                                      $SQL1 = "insert into in_articleupload(id,docid,docname,type)
                                               values('$seqNumber1','".$_REQUEST['mode']."','".$imgName2 ."','DESCRIPTION') ";
                                      mysql_query($SQL1) or die(mysql_error()."PA-115<br>".$SQL1);
                                      }

                                    }else{
                                      $field='userfile_tech'.$j;
                                      $seqNumber1 = GetLastSqeID("in_articleupload");
                                      $target_path = $target_path.basename($_FILES[$field]['name']);
                                      $target_path = "uploads/articles/";
                                      $target_path = $target_path .$seqNumber1."_". basename( $_FILES[$field]['name']);
                                      if($_FILES[$field]['name']) $imgName2."_".$j= $temp.$_FILES[$field]['name'];

                                       move_uploaded_file($_FILES[$field]['tmp_name'], $target_path);

                                      if($imgName2."_".$j!=""){
                                      $SQL1 = "insert into in_articleupload(id,docid,docname,type)
                                               values('$seqNumber1','".$_REQUEST['mode']."','".$imgName2."_".$j ."','DESCRIPTION') ";
                                      mysql_query($SQL1) or die(mysql_error()."PA-115<br>".$SQL1);
                                      }
                                    }
                                }

                                if($_REQUEST['action']=='save'){
                                   if($formname=="articlelist.php")header("location:editarticlelist.php?dr=edit&ID=".$_REQUEST['mode']."");
                                }elseif($_REQUEST['action']=='savenew'){
                                   if($formname=="articlelist.php")header("location:editarticlelist.php?dr=add&ID=0");
                                }
                                elseif($_REQUEST['action']=='saveclose'){
                                   if($formname=="articlelist.php")header("location:articlelist.php");
                                }
        }
         if($formname=="companyshifts.php"){
            $fromtime24hours = date("H:i", strtotime($RequestBuffer['txt_A_fromtime']));
            $toexplode     = explode(":",$RequestBuffer['txt_A_totime']);
            if($toexplode[0]==0){
              $RequestBuffer['txt_A_totime'] = str_Replace('am','',$RequestBuffer['txt_A_totime']);
            }
            $totime24hours = date("H:i", strtotime($RequestBuffer['txt_A_totime']));
            $totalhoursdifference = get_time_difference($fromtime24hours, $totime24hours);
            $Htemp1     = explode(":",$totalhoursdifference);

            $totalhoursinminutes = $Htemp1[0]*60 + $Htemp1[1];
            $totalhours = convertToHoursMins($totalhoursinminutes, '%02d:%02d');
            //echo $totalhours;
            $Htemp     = explode(".",$RequestBuffer['txt_A_breaktime']);
            $totalbreaktimeinminutes = $Htemp[0]*60 + $Htemp[1];

            $workhoursinminutes =  $totalhoursinminutes*1-$totalbreaktimeinminutes*1;
            $workhours = convertToHoursMins($workhoursinminutes, '%02d:%02d');
            //echo $workhours;

            $SQL   = "update in_companyshift set workhours='$workhours' where id='".$_REQUEST['mode']."'";
            $SQLRes =  mysql_query($SQL) or die(mysql_error()."<br>".$SQL);

            if($_REQUEST['action']=='save'){
                if($formname=="companyshifts.php")header("location:editcompanyshifts.php?dr=edit&ID=".$_REQUEST['mode']."");
            }elseif($_REQUEST['action']=='savenew'){
                if($formname=="companyshifts.php")header("location:editcompanyshifts.php?dr=add&ID=0");
            }elseif($_REQUEST['action']=='saveclose'){
                if($formname=="companyshifts.php")header("location:companyshifts.php");
            }
         }
         if($formname=="companycluster.php"){
            for($ii=0 ;$ii < count($RequestBuffer['chk_A_company']); $ii++){
               $SQL   = "SELECT jobname from t_activitycenter where id='".$RequestBuffer['chk_A_company'][$ii]."'";
               $SQLRes =  mysql_query($SQL) or die(mysql_error()."<br>".$SQL);
               if(mysql_num_rows($SQLRes)>=1){
                  while($loginResultArray   = mysql_fetch_array($SQLRes)){
                        $custercompany .= $loginResultArray['jobname'].",";
                  }
               }
            }
            $custercompany = substr($custercompany,0,strlen($custercompany)-1) ;
            $SQL   = "update in_companycluster set companyname='$custercompany' where id='".$_REQUEST['mode']."'";
            $SQLRes =  mysql_query($SQL) or die(mysql_error()."<br>".$SQL);
            if($_REQUEST['action']=='save'){
                if($formname=="companycluster.php")header("location:editcompanycluster.php?dr=edit&ID=".$_REQUEST['mode']."");
            }elseif($_REQUEST['action']=='savenew'){
                if($formname=="companycluster.php")header("location:editcompanycluster.php?dr=add&ID=0");
            }elseif($_REQUEST['action']=='saveclose'){
                if($formname=="companycluster.php")header("location:companycluster.php");
            }
         }
         if($formname=="submenulist.php"){
            if($_REQUEST['action']=='save'){
                if($formname=="submenulist.php")header("location:editsubmenulist.php?dr=edit&cmb_lookuplist=".$_REQUEST['txt_A_parentid']."&ID=".$_REQUEST['mode']."");
            }elseif($_REQUEST['action']=='savenew'){
                if($formname=="submenulist.php")header("location:editsubmenulist.php?dr=add&cmb_lookuplist=".$_REQUEST['txt_A_parentid']."&ID=0");
            }elseif($_REQUEST['action']=='saveclose'){
                if($formname=="submenulist.php")header("location:submenulist.php?cmb_lookuplist=".$_REQUEST['txt_A_parentid']."");
            }
         }
         if($formname=="mainmenulist.php"){
            if($_REQUEST['action']=='save'){
                if($formname=="mainmenulist.php")header("location:editmainmenulist.php?dr=edit&ID=".$_REQUEST['mode']."");
            }elseif($_REQUEST['action']=='savenew'){
                if($formname=="mainmenulist.php")header("location:editmainmenulist.php?dr=add&ID=0");
            }elseif($_REQUEST['action']=='saveclose'){
                if($formname=="mainmenulist.php")header("location:mainmenulist.php");
            }
         }
         if($formname=="leavemaster.php"){
            if($_REQUEST['action']=='save'){
                if($formname=="leavemaster.php")header("location:editleavemaster.php?dr=edit&ID=".$_REQUEST['mode']."");
            }elseif($_REQUEST['action']=='savenew'){
                if($formname=="leavemaster.php")header("location:editleavemaster.php?dr=add&ID=0");
            }elseif($_REQUEST['action']=='saveclose'){
                if($formname=="leavemaster.php")header("location:leavemaster.php");
            }
         }
         if($formname=="bulletinboard.php"){
            if($_REQUEST['action']=='save'){
                if($formname=="bulletinboard.php")header("location:editbulletinboard.php?dr=edit&ID=".$_REQUEST['mode']."");
            }elseif($_REQUEST['action']=='savenew'){
                if($formname=="bulletinboard.php")header("location:editbulletinboard.php?dr=add&ID=0");
            }elseif($_REQUEST['action']=='saveclose'){
                if($formname=="bulletinboard.php")header("location:bulletinboard.php");
            }
         }
         if($formname=="jobrequest.php"){

            if($_REQUEST['action']=='save'){
                    if($formname=="jobrequest.php")header("location:editjobrequest.php?dr=edit&ID=".$_REQUEST['mode']."");
                }elseif($_REQUEST['action']=='savenew'){
                    if($formname=="jobrequest.php")header("location:editjobrequest.php?dr=add&ID=0");
                }elseif($_REQUEST['action']=='saveclose'){
                    if($formname=="jobrequest.php")header("location:jobrequest.php");
            }
         }
         if($formname=="jobrequest_hr.php"){

            if($_REQUEST['action']=='save'){
                    if($formname=="jobrequest_hr.php")header("location:editjobrequest_hr.php?dr=edit&ID=".$_REQUEST['mode']."");
                }elseif($_REQUEST['action']=='savenew'){
                    if($formname=="jobrequest_hr.php")header("location:editjobrequest_hr.php?dr=add&ID=0");
                }elseif($_REQUEST['action']=='saveclose'){
                    if($formname=="jobrequest_hr.php")header("location:jobrequest_hr.php");
            }
         }
         if($formname=="hrmanpowerrequisition.php"){

            if($_REQUEST['action']=='save'){
                    if($formname=="hrmanpowerrequisition.php")header("location:edithrmanpowerrequisition.php?dr=edit&ID=".$_REQUEST['mode']."");
                }elseif($_REQUEST['action']=='savenew'){
                    if($formname=="hrmanpowerrequisition.php")header("location:edithrmanpowerrequisition.php?dr=add&ID=0");
                }elseif($_REQUEST['action']=='saveclose'){
                    if($formname=="hrmanpowerrequisition.php")header("location:hrmanpowerrequisition.php");
            }
         }
        if($formname=="emp_leaverequest.php" ){


                  $SQL   = "Select datediff(leaveto,leavefrom) as days,datediff(approvedto,approvedfrom) as daysapproved from e_leave  WHERE id='".$_REQUEST['mode']."'";
                  $SQLRes =  mysql_query($SQL) or die(mysql_error()."<br>".$SQL);
                  if(mysql_num_rows($SQLRes)>=1){
                   $loginResultArray   = mysql_fetch_array($SQLRes);
                   $days= $loginResultArray['days']+1;
                  }

                 $SQL2 = "UPDATE e_leave SET daysallowed=".$days." where id=".$_REQUEST['mode'];
                 mysql_query($SQL2) or die(mysql_error()."PA-115<br>".$SQL2);


                 //if($formname=="emp_leave.php")header("location:emp_leave.php?ID=".$_REQUEST['txt_A_staffid']."");
         }

                    if($formname=="companysetup.php"){

                                  $charset="ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";
                                  $length=5;
                                  for($i=0; $i <=$length; $i++){
                                    $rand =rand() % strlen($charset);
                                    $temp=  substr($charset,$rand,3);
                                  }
                                  $target_path = "logo/";
                                  $target_path = $target_path.$temp . basename( $_FILES['userfile']['name']);
                                  if($_FILES['userfile']['name']) $fileName= $temp.$_FILES['userfile']['name'];

                                  if (file_exists("logo/".$fileName)){
                                   echo "<center><STRONG>Sorry!!" .$fileName . " already exists.</center></STRONG>";
                                  }else{
                                   move_uploaded_file($_FILES['userfile']['tmp_name'], $target_path);
                                  }
                                  if(basename($_FILES['userfile']['name'])){
                                  $SQL1 = "UPDATE ".$_SESSION['CurrentObjectName']->TableName." SET companylogo='".$fileName."' where id=".$_REQUEST['mode'];
                                  mysql_query($SQL1) or die(mysql_error()."PA-115<br>".$SQL1);
                                 }
                                 if($_REQUEST['action']=='save'){
                                   if($formname=="companysetup.php")header("location:editcompanysetup.php?dr=edit&ID=".$_REQUEST['mode']."");
                                 }elseif($_REQUEST['action']=='savenew'){
                                   if($formname=="companysetup.php")header("location:editcompanysetup.php?dr=add&ID=0");
                                 }
                                 elseif($_REQUEST['action']=='saveclose'){
                                   if($formname=="companysetup.php")header("location:companysetup.php");
                                 }
                   }
                   if($formname=="mydocrequest.php"){

                                 if($_REQUEST['action']=='save'){
                                   if($formname=="mydocrequest.php")header("location:editmydocrequest.php?dr=edit&ID=".$_REQUEST['mode']."");
                                 }elseif($_REQUEST['action']=='savenew'){
                                   if($formname=="mydocrequest.php")header("location:editmydocrequest.php?dr=add&ID=0");
                                 }
                                 elseif($_REQUEST['action']=='saveclose'){
                                   if($formname=="mydocrequest.php")header("location:mydocrequest.php");
                                 }
                   }
                   if($formname=="mydocrequest_hr.php"){

                                  $charset="ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";
                                  $length=5;
                                  for($i=0; $i <=$length; $i++){
                                    $rand =rand() % strlen($charset);
                                    $temp=  substr($charset,$rand,3);
                                  }
                                  $target_path = "documents/";
                                  $target_path = $target_path.$temp . basename( $_FILES['userfile']['name']);
                                  if($_FILES['userfile']['name']) $fileName= $temp.$_FILES['userfile']['name'];

                                  if (file_exists("documents/".$fileName)){
                                   echo "<center><STRONG>Sorry!!" .$fileName . " already exists.</center></STRONG>";
                                  }else{
                                   move_uploaded_file($_FILES['userfile']['tmp_name'], $target_path);
                                  }
                                  if(basename($_FILES['userfile']['name'])){
                                  $SQL1 = "UPDATE ".$_SESSION['CurrentObjectName']->TableName." SET docname='".$fileName."' where id=".$_REQUEST['mode'];
                                  mysql_query($SQL1) or die(mysql_error()."PA-115<br>".$SQL1);
                                 }

                                 if($_REQUEST['action']=='save'){
                                   if($formname=="mydocrequest_hr.php")header("location:editmydocrequest_hr.php?dr=edit&ID=".$_REQUEST['mode']."");
                                 }elseif($_REQUEST['action']=='savenew'){
                                   if($formname=="mydocrequest_hr.php")header("location:editmydocrequest_hr.php?dr=add&ID=0");
                                 }
                                 elseif($_REQUEST['action']=='saveclose'){
                                   if($formname=="mydocrequest_hr.php")header("location:mydocrequest_hr.php");
                                 }
                   }

                   if($formname=="newuserdetails.php"){

                                 //$squpdateSQL = "UPDATE in_user SET rolecode=concat('EMPLOYEE,',rolecode) WHERE id=".$_REQUEST['mode'];
                                 //mysql_query($squpdateSQL) or die(mysql_error()."<br>".$squpdateSQL);

                                 $SQL   = "SELECT rolecode,userid FROM in_user WHERE id=".$_REQUEST['mode'];
                                 $SQLRes =  mysql_query($SQL) or die(mysql_error()."<br>".$SQL);
                                 if(mysql_num_rows($SQLRes)>=1){
                                   while($loginResultArray   = mysql_fetch_array($SQLRes)){
                                    $updatedrole=$loginResultArray['rolecode'];
                                    $userid=$loginResultArray['userid'];
                                   }
                                 }
                                 $squpdateSQL = "UPDATE in_personalinfo SET rolecode='$updatedrole' WHERE empid='".$userid."'";
                                 mysql_query($squpdateSQL) or die(mysql_error()."<br>".$squpdateSQL);

                                 if($_REQUEST['action']=='save'){
                                   if($formname=="newuserdetails.php")header("location:editnewuserdetails.php?dr=edit&ID=".$_REQUEST['mode']."&cmb_lookuplist=".$_REQUEST['txt_A_companycode']."");
                                 }elseif($_REQUEST['action']=='savenew'){
                                   if($formname=="newuserdetails.php")header("location:editnewuserdetails.php?dr=add&ID=0&cmb_lookuplist=".$_REQUEST['txt_A_companycode']."");
                                 }
                                 elseif($_REQUEST['action']=='saveclose'){
                                   if($formname=="newuserdetails.php")header("location:newuserdetails.php");
                                 }
                   }

                   if($formname=="employeemaster.php"){

            $squpdateSQL1 = "UPDATE in_personalinfo SET empreportingofficer='0' WHERE id='".$_REQUEST['mode']."' and empdesignation='17001'";
            mysql_query($squpdateSQL1) or die(mysql_error()."<br>".$squpdateSQL1);

                                  $charset="ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";
                                  $length=5;
                                  for($i=0; $i <=$length; $i++){
                                    $rand =rand() % strlen($charset);
                                    $temp=  substr($charset,$rand,3);
                                  }
                                  $target_path = "staffphoto/";
                                  $target_path = $target_path.$temp . basename( $_FILES['userfile']['name']);
                                  if($_FILES['userfile']['name']) $fileName= $temp.$_FILES['userfile']['name'];

                                  if (file_exists("staffphoto/".$fileName)){
                                   echo "<center><STRONG>Sorry!!" .$fileName . " already exists.</center></STRONG>";
                                  }else{
                                   move_uploaded_file($_FILES['userfile']['tmp_name'], $target_path);
                                  }
                                  if(basename($_FILES['userfile']['name'])){
                                  $SQL1 = "UPDATE ".$_SESSION['CurrentObjectName']->TableName." SET empimage='".$fileName."' where id=".$_REQUEST['mode'];
                                  mysql_query($SQL1) or die(mysql_error()."PA-115<br>".$SQL1);
                                 }

                                 //$squpdateSQL = "UPDATE in_personalinfo SET rolecode=concat('EMPLOYEE,',rolecode) WHERE id=".$_REQUEST['mode'];
                                 //mysql_query($squpdateSQL) or die(mysql_error()."<br>".$squpdateSQL);

                                 $SQL   = "SELECT rolecode FROM in_personalinfo WHERE id=".$_REQUEST['mode'];
                                 $SQLRes =  mysql_query($SQL) or die(mysql_error()."<br>".$SQL);
                                 if(mysql_num_rows($SQLRes)>=1){
                                   while($loginResultArray   = mysql_fetch_array($SQLRes)){
                                    $updatedrole=$loginResultArray['rolecode'];
                                   }
                                 }
                                 $SQL   = "SELECT acclocationcode,locationcode FROM in_user WHERE userid='".$RequestBuffer['txt_A_empid']."'";
                                 $SQLRes =  mysql_query($SQL) or die(mysql_error()."<br>".$SQL);
                                 if(mysql_num_rows($SQLRes)>=1){
                                   while($loginResultArray   = mysql_fetch_array($SQLRes)){

                                    $acclocationcode=$loginResultArray['acclocationcode'];
                                    $locationcode=$loginResultArray['locationcode'];

                                    $locationcode = str_replace($acclocationcode,$RequestBuffer['cmb_A_empcompany'],$locationcode);

                                   }
                                 }
                                 $insAccountSQL = "update in_user set username='".$RequestBuffer['txt_A_empfirstename']." ".$RequestBuffer['txt_A_emplastename']."',
                                                   rolecode='".$updatedrole."',acclocationcode='".$RequestBuffer['cmb_A_empcompany']."',locationcode='$locationcode'
                                                   where userid='".$RequestBuffer['txt_A_empid']."'";
                                 mysql_query($insAccountSQL) or die(mysql_error()."<br>".$insAccountSQL);


                                 if($_REQUEST['action']=='save'){
                                   if($formname=="employeemaster.php")header("location:editemployeemaster.php?cmb_lookuplist=".$_REQUEST['cmb_A_empsponsercompany']."&dr=edit&ID=".$_REQUEST['mode']."");
                                 }elseif($_REQUEST['action']=='savenew'){
                                   if($formname=="employeemaster.php")header("location:editemployeemaster.php?cmb_lookuplist=".$_REQUEST['cmb_A_empsponsercompany']."&dr=add&ID=0");
                                 }
                                 elseif($_REQUEST['action']=='saveclose'){
                                   if($formname=="employeemaster.php")header("location:employeemaster.php");
                                 }


                    }
                    if($formname=="businessobjectlist.php"){

                                  $charset="ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";
                                  $length=5;
                                  for($i=0; $i <=$length; $i++){
                                    $rand =rand() % strlen($charset);
                                    $temp=  substr($charset,$rand,3);
                                  }
                                  $target_path = "businesscards/";
                                  $target_path = $target_path.$temp . basename( $_FILES['userfile']['name']);
                                  if($_FILES['userfile']['name']) $fileName= $temp.$_FILES['userfile']['name'];

                                  if (file_exists("businesscards/".$fileName)){
                                   echo "<center><STRONG>Sorry!!" .$fileName . " already exists.</center></STRONG>";
                                  }else{
                                   move_uploaded_file($_FILES['userfile']['tmp_name'], $target_path);
                                  }
                                  if(basename($_FILES['userfile']['name'])){
                                  $SQL1 = "UPDATE ".$_SESSION['CurrentObjectName']->TableName." SET docname='".$fileName."' where id=".$_REQUEST['mode'];
                                  mysql_query($SQL1) or die(mysql_error()."PA-115<br>".$SQL1);
                                 }
                                 if($_REQUEST['action']=='save'){
                                   if($formname=="businessobjectlist.php")header("location:editbusinessobjectlist.php?dr=edit&cmb_lookuplist=".$_REQUEST['txt_A_objecttype']."&ID=".$_REQUEST['mode']."");
                                 }elseif($_REQUEST['action']=='savenew'){
                                   if($formname=="businessobjectlist.php")header("location:editbusinessobjectlist.php?dr=add&cmb_lookuplist=".$_REQUEST['txt_A_objecttype']."&ID=0");
                                 }
                                 elseif($_REQUEST['action']=='saveclose'){
                                   if($formname=="businessobjectlist.php")header("location:businessobjectlist.php");
                                 }


                    }
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

function UserLog($tblName,$tableseqID,$tablestrSQL,$actiontype){

        $seqID = GetLastSqeID("in_userlog");
        $datetime=date("Y/m/d h:i:s a", time());
        $seqSQL = "insert into in_userlog values(".$seqID.",'".$datetime."','".$_SESSION['SESSuserID'] ."','".$_SERVER['REMOTE_ADDR']."','".$tblName."','".$tableseqID."','".$actiontype."','".str_replace("'","''",$tablestrSQL)."','".$_SESSION['SESSUserLocation']."','".$_SESSION['SESScompanycode']."')";
        $result=mysql_query($seqSQL) or die(mysql_error()."<br>".$seqSQL);



}
function convertToHoursMins($time, $format = '%02d:%02d') {
    if ($time < 1) {
        return;
    }
    $hours = floor($time / 60);
    $minutes = ($time % 60);
    return sprintf($format, $hours, $minutes);
}
function get_time_difference($time1, $time2) {
    $time1 = strtotime("1980-01-01 $time1");
    $time2 = strtotime("1980-01-01 $time2");

    if ($time2 < $time1) {
       $time2 += 86400;
    }

    return date("H:i", strtotime("1980-01-01 00:00:00") + ($time2 - $time1));
}
function GetProID($type){
                 $query = "LOCK TABLES in_sequencer_pro WRITE";
                 mysql_query($query) or die(mysql_error()."<br>".$query);
                 $seqSQL = "SELECT LASTNUMBER FROM in_sequencer_pro WHERE TABLENAME='$type'";
                 $result=mysql_query($seqSQL) or die(mysql_error()."<br>".$seqSQL);
                 $resulArr=mysql_fetch_array($result);
                 $updatedSeqID=$resulArr['LASTNUMBER']+1;
                 $seqSQL1 = "UPDATE in_sequencer_pro SET LASTNUMBER=LASTNUMBER+1 WHERE TABLENAME='$type'";
                 $result=mysql_query($seqSQL1) or die(mysql_error()."<br>".$seqSQL);
                 $query = "UNLOCK TABLES";
                 mysql_query($query) or die(mysql_error()."<br>".$query);
                 return ($updatedSeqID);
}
ob_end_flush();
?>
