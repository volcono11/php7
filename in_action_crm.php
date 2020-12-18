<?php
ob_start();
include "connection.php";
include "pagingObj.php";
include "functions.php";

@session_start();
$_SESSION['lastID']="";

if($_SESSION['SESSuserID']==""){
    echo "Your Browser Session has been expired! Please Login Again...";
    die();
}
//zecho $_SESSION['CurrentObjectName']->TableName;
//echo $_REQUEST['mode'];
$RequestBuffer = $_REQUEST;
if(isset($_REQUEST["ZZZZZZXXXXXX"])){
$RequestBuffer_row = $_REQUEST['ZZZZZZXXXXXX'];
}
else{
$RequestBuffer_row=""	;
}
//print_r($RequestBuffer);
//exit;
/*if ($_REQUEST['modeid']=="save" && $_REQUEST['mode']==""){
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
}*/

$req_mode = isset($_REQUEST['mode']) ? $_REQUEST['mode'] : '';
$req_modeid = isset($_REQUEST['modeid']) ? $_REQUEST['modeid'] : '';

if ($req_modeid=="save" && $req_mode==""){
	
	$myTable =  $_SESSION['CurrentObjectName']->TableName;
	
   	GetInsertStatement($RequestBuffer,$RequestBuffer_row);
   	PostAddConditions($myTable,$RequestBuffer);
   	exit;
}
if ($req_modeid == "save" && $req_mode!=""){
	
	$myTable =  $_SESSION['CurrentObjectName']->TableName;
   	GetUpdateStatement($RequestBuffer,$RequestBuffer_row);
   	PostUpdateConditions($myTable,$RequestBuffer);
   	exit;
}


$req_child = isset($_REQUEST['child']) ? $_REQUEST['child'] : '';
$req_childid = isset($_REQUEST['childid']) ? $_REQUEST['childid'] : '';

if ($req_child=="child" && $req_childid==""){
   	GetInsertChildStatement($RequestBuffer,$RequestBuffer_row);
	PostInsertChildStatement($RequestBuffer,$RequestBuffer_row);
	exit;
}
if ($req_child=="child" && $req_childid!=""){
	GetUpdateChildStatement($RequestBuffer,$RequestBuffer_row);
	PostUpdateChildStatement($RequestBuffer,$RequestBuffer_row);
	exit;
}
//PostUpdateConditions($myTable,$RequestBuffer);
//PostAddConditions($myTable,$RequestBuffer);

function GetUpdateStatement($RequestBuffer,$RequestBuffer_row){
	global $con;
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
                $value   = $Dvalue[2].'-'.$Dvalue[1].'-'.$Dvalue[0];                           //  Build Field Names
                $strSQL = $strSQL . $strFields . "='" . $value . "',";
            }

        }


        $strSQL = "UPDATE " . $_SESSION['CurrentObjectName']->TableName . " SET " . substr($strSQL,0,strlen($strSQL)-1);    //  Removing Last coma
        $strSQL = $strSQL . " WHERE ID='" . $_REQUEST['mode'] ."'";
        //echo $strSQL;exit;
        mysqli_query($con,"SET NAMES 'utf8'");
        $result   = mysqli_query($con,$strSQL)  or die(mysqli_error().$strSQL);
        UserLog($_SESSION['CurrentObjectName']->TableName,$_REQUEST['mode'],$strSQL,"UPDATE");
        //for child
        if($RequestBuffer_row!=""){
             if($_SESSION['CurrentObjectName']->formName=="purchaseindentlist.php" || $_SESSION['CurrentObjectName']->formName=="generalmaterialtransfer.php"){
               $seqSQL = "DELETE FROM ".$_SESSION['CurrentObjectName']->formChildTable." WHERE initemid='".$_SESSION['CurrentObjectName']->formChildTableRecord."'";
               $result=mysqli_query($con,$seqSQL) or die(mysqli_error()."<br>".$seqSQL);
             }
             $seqSQL = "DELETE FROM ".$_SESSION['CurrentObjectName']->formChildTable." WHERE INVHEADID='".$_SESSION['CurrentObjectName']->formChildTableRecord."'";
             $result=mysqli_query($con,$seqSQL) or die(mysqli_error()."<br>".$seqSQL);

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
                     $result   = mysqli_query($con,$strSQLchild)  or die(mysqli_error());
             }
             //end of child
        }

        echo "Record Updated";
   }

function GetInsertStatement($RequestBuffer,$RequestBuffer_row){
	global $con;
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
                $value   = $Dvalue[2].'-'.$Dvalue[1].'-'.$Dvalue[0];                           //  Build Field Names
                $strValues  .= "'" . $value . "',";                          //  If Alpha put quotes
            }
        }

        $strFields = substr($strFields,0,strlen($strFields)-1) ;
        $strValues = substr($strValues,0,strlen($strValues)-1) ;


        $strSQL = "Insert into " . $_SESSION['CurrentObjectName']->TableName . "(ID,".$strFields.") values ('".$seqID."'," . $strValues .")";    //  Removing Last coma
        mysqli_query($con,"SET NAMES 'utf8'");
        $result   = mysqli_query($con,$strSQL)  or die(mysqli_error());

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
                     $result   = mysqli_query($con,$strSQLchild)  or die(mysqli_error());

             }
             //end of child
        }

        echo "Record Saved";
}
function GetInsertChildStatement($RequestBuffer,$RequestBuffer_row){
	global $con;
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

        $formname = $_SESSION['CurrentObjectName']->formName;
        if($formname=="projectestimatesefinal.php"){

           if($_REQUEST['templatename']!=""){

                $SQL   = "SELECT * from in_templateline where docid='".$_REQUEST['templatename']."'";
                $SQLRes =  mysqli_query($con,$SQL) or die(mysqli_error()."<br>".$SQL);
                if(mysqli_num_rows($SQLRes)>=1){
                   while($loginResultArray   = mysqli_fetch_array($SQLRes)){

                        $slno=0;
                        $SQL1   = "Select slno from in_crmline where id='".$_REQUEST['cmb_A_leaddescription']."'";
                        $SQLRes1 =  mysqli_query($con,$SQL1) or die(mysqli_error()."<br>".$SQL1);
                        if(mysqli_num_rows($SQLRes1)>=1){
                            while($loginResultArray1   = mysqli_fetch_array($SQLRes1)){
                                  $slno = $loginResultArray1['slno'];

                            }
                        }
                        $count=0;
                        $SQL2   = "Select max(slno) as lineslno from in_jobscope_crm where leaddescription='".$_REQUEST['cmb_A_leaddescription']."'";
                        $SQLRes2 =  mysqli_query($con,$SQL2) or die(mysqli_error()."<br>".$SQL2);
                        if(mysqli_num_rows($SQLRes2)>=1){
                           while($loginResultArray2   = mysqli_fetch_array($SQLRes2)){
                              $lineslno = $loginResultArray2['lineslno'];
                           }
                        }
                        $count=0;
                        $Htemp = split("-",$lineslno);
                        $count = $Htemp[1]+1;

                        $actslno = $slno."-".$count;
                        $seqNumber1 = GetLastSqeID("in_jobscope_crm");
                        $SQL1 = "insert into in_jobscope_crm(id,docid,estcostgroup,estcostsubgroup,costgroup,costsubgroup,slno,leaddescription,articlecode,description,vatpercent,uom,qty)
                                 values
                                 ('$seqNumber1','".$_REQUEST['txt_A_docid']."','".$_REQUEST['cmb_A_estcostgroup']."','".$_REQUEST['cmb_A_estcostsubgroup']."','".$loginResultArray['category']."','".$loginResultArray['subcategory']."','$actslno','".$_REQUEST['cmb_A_leaddescription']."','".$loginResultArray['articlecode']."','".$loginResultArray['articlename']."','".$loginResultArray['vatpercent']."','".$loginResultArray['uom']."','".$loginResultArray['qty']."')";
                        mysqli_query($con,$SQL1) or die(mysqli_error()."PA-115<br>".$SQL1);
                   }
                }
           }else{
               $strSQL = "Insert into " . $_SESSION['CurrentObjectName']->TableNameChild . "(ID,".$strFields.") values ('".$seqID."'," . $strValues .")";    //  Removing Last coma
               $result   = mysqli_query($con,$strSQL)  or die(mysqli_error());
           }
        }
		
		else if($formname=="addmaterialitem.php"){
			$services_array = $_REQUEST['serviceslist'];
			if(count($services_array)>0){ 
				for($p=0;$p<count($services_array);$p++)	{ 
				  	$seqID = GetLastSqeID($_SESSION['CurrentObjectName']->TableNameChild);
					$strSQL = "Insert into " . $_SESSION['CurrentObjectName']->TableNameChild . "(ID,articlecode,articlename,".$strFields.") values ('".$seqID."','".$services_array[$p]."','".get_Aricle_Name($services_array[$p])."'," . $strValues .")";    //  Removing Last coma
           			$result   = mysqli_query($con,$strSQL)  or die(mysqli_error());
				}
			}
			
		}
		
		else{
           $strSQL = "Insert into " . $_SESSION['CurrentObjectName']->TableNameChild . "(ID,".$strFields.") values ('".$seqID."'," . $strValues .")";    //  Removing Last coma
           $result   = mysqli_query($con,$strSQL)  or die(mysqli_error());
        }

        UserLog($_SESSION['CurrentObjectName']->TableNameChild,$seqID,$strSQL,"INSERT");
        echo "Record Saved";
}



function PostInsertChildStatement($RequestBuffer,$RequestBuffer_row){
	global $con;
       $formname = $_SESSION['CurrentObjectName']->formName;
       
       if($formname=="otcompletionreport.php" || $formname=="completionreport.php"){
       
                  $temp= "OTCJ".$_SESSION['lastID']."A$$$";
                  $target_path = $target_path.basename($_FILES['userfile']['name']);
                  $target_path = "uploads/";
                  $target_path = $target_path .$temp. basename( $_FILES['userfile']['name']);
                  if($_FILES['userfile']['name']) $fileName= $temp.$_FILES['userfile']['name'];
                  if (file_exists("uploads/".$fileName)){
                   echo "<center><STRONG>Sorry!!" .$fileName . " already exists.</center></STRONG>";
                  }else{
                   move_uploaded_file($_FILES['userfile']['tmp_name'], $target_path);
                  }
                  
                  $temp= "OTCJ".$_SESSION['lastID']."B$$$";
                  $target_path = $target_path.basename($_FILES['userfile1']['name']);
                  $target_path = "uploads/";
                  $target_path = $target_path .$temp. basename( $_FILES['userfile1']['name']);
                  if($_FILES['userfile1']['name']) $fileName2= $temp.$_FILES['userfile1']['name'];
                  if (file_exists("uploads/".$fileName2)){
                   echo "<center><STRONG>Sorry!!" .$fileName2 . " already exists.</center></STRONG>";
                  }else{
                   move_uploaded_file($_FILES['userfile1']['tmp_name'], $target_path);
                  }


                  
                  if($fileName!='') {
                  $SQL1 = "UPDATE ".$_SESSION['CurrentObjectName']->TableNameChild." SET docname='".$fileName."' where id=".$_SESSION['lastID'];
                  mysqli_query($con,$SQL1) or die(mysqli_error()."PA-115<br>".$SQL1);
                  }
                  if($fileName2!='') {
                  $SQL1 = "UPDATE ".$_SESSION['CurrentObjectName']->TableNameChild." SET docname2='".$fileName2."' where id=".$_SESSION['lastID'];
                  mysqli_query($con,$SQL1) or die(mysqli_error()."PA-115<br>".$SQL1);
                  }

                if($formname=="otcompletionreport.php")header("location:otcompletionreport.php?ID=".$_REQUEST['txt_A_invheadid']."&txt_A_formtype=".$_REQUEST['txt_A_formtype']."");
                if($formname=="completionreport.php")header("location:completionreport.php?ID=".$_REQUEST['txt_A_invheadid']."&txt_A_formtype=".$_REQUEST['txt_A_formtype']."");
       
       }
       if($formname=="otcombinedjob.php" || $formname=="otmaterialdeliveryjob.php" || $formname=="otsubcontractjob.php" || $formname=="otvariationjob.php"){

                  $temp= "OTCJ".$_SESSION['lastID']."$$$";
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
                  mysqli_query($con,$SQL1) or die(mysqli_error()."PA-115<br>".$SQL1);
                  }

                if($formname=="otcombinedjob.php")header("location:otcombinedjob.php?ID=".$_REQUEST['txt_A_invheadid']."&txt_A_formtype=".$_REQUEST['txt_A_formtype']);
                if($formname=="otmaterialdeliveryjob.php")header("location:otmaterialdeliveryjob.php?ID=".$_REQUEST['txt_A_invheadid']."&txt_A_formtype=".$_REQUEST['txt_A_formtype']);
                if($formname=="otsubcontractjob.php")header("location:otsubcontractjob.php?ID=".$_REQUEST['txt_A_invheadid']."&txt_A_formtype=".$_REQUEST['txt_A_formtype']);
                if($formname=="otvariationjob.php")header("location:otvariationjob.php?ID=".$_REQUEST['txt_A_invheadid']."&txt_A_formtype=".$_REQUEST['txt_A_formtype']);

       }
       
       if($formname=="serviceassets.php"){

                  $charset="ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";
                  $length=5;
                  for($i=0; $i <=$length; $i++){
                   $rand =rand() % strlen($charset);
                   $temp=  substr($charset,$rand,3);
                  }
                  $temp= "ASSET".$_SESSION['lastID']."$$$";
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
                  mysqli_query($con,$SQL1) or die(mysqli_error()."PA-115<br>".$SQL1);
                  }

                if($formname=="serviceassets.php")header("location:serviceassets.php?ID=".$_REQUEST['txt_A_docid']."&formtype=".$_REQUEST['txt_A_formtype']);

       }
       
       if($formname=="manpowerforservice.php"){

                  $charset="ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";
                  $length=5;
                  for($i=0; $i <=$length; $i++){
                   $rand =rand() % strlen($charset);
                   $temp=  substr($charset,$rand,3);
                  }
                  $temp= "MAN".$_SESSION['lastID']."$$$";
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
                  mysqli_query($con,$SQL1) or die(mysqli_error()."PA-115<br>".$SQL1);
                  }

                if($formname=="manpowerforservice.php")header("location:manpowerforservice.php?ID=".$_REQUEST['txt_A_docid']."");

       }
       
       if($formname=="annexure.php"){
          for($i=0 ;$i < $_REQUEST['selectslots']; $i++){
                                    $imgName = "";
                                    if($i==0){

                                      $seqNumber1 = GetLastSqeID("tbl_attachments");
                                      //$target_path = $target_path.basename($_FILES['userfile']['name']);
                                      $target_path = "uploads/";
                                      $temp = "ANEX".$seqNumber1."$$$";
                                      $target_path = $target_path .$temp. basename( $_FILES['userfile']['name']);

                                      if($_FILES['userfile']['name']) {
                                      $imgName= $temp.$_FILES['userfile']['name'];

                                       move_uploaded_file($_FILES['userfile']['tmp_name'], $target_path);

                                      $SQL1 = "insert into tbl_attachments(id,docid,docname,userid,doctype,remarks,invheadid)
                                               values('$seqNumber1','".$_SESSION['lastID']."','".$imgName ."','".$_SESSION['SESSuserID']."','".$_REQUEST['LEVEL']."','".$_REQUEST['remarks']."','".$_REQUEST['txt_A_invheadid']."') ";
                                      mysqli_query($con,$SQL1) or die(mysqli_error()."PA-115<br>".$SQL1);
									  }

                                    }else{
                                      $field='userfile'.$i;
                                      $remarks = 'remarks'.$i;
                                      $seqNumber1 = GetLastSqeID("tbl_attachments");
                                      //$target_path = $target_path.basename($_FILES[$field]['name']);
                                      $target_path = "uploads/";
                                      $temp = "ANEX".$seqNumber1."$$$";
                                      $target_path = $target_path .$temp. basename($_FILES[$field]['name']);

                                      if($_FILES[$field]['name']) {
                                      	$imgName= $temp.$_FILES[$field]['name'];

                                       move_uploaded_file($_FILES[$field]['tmp_name'], $target_path);


                                      $SQL1 = "insert into tbl_attachments(id,docid,docname,userid,doctype,remarks,invheadid)
                                               values('$seqNumber1','".$_SESSION['lastID']."','".$imgName ."','".$_SESSION['SESSuserID']."','".$_REQUEST['LEVEL']."','".$_REQUEST[$remarks]."','".$_REQUEST['txt_A_invheadid']."') ";
                                      mysqli_query($con,$SQL1) or die(mysqli_error()."PA-115<br>".$SQL1);
										}

                                    }
                                }
             if($formname=="annexure.php")header("location:annexure.php?ID=".$_REQUEST['txt_A_invheadid']."&LEVEL=".$_REQUEST['LEVEL']."");

         }
         
       if($formname=="servicepropertylist.php"){

                  $charset="ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";
                  $length=5;
                  for($i=0; $i <=$length; $i++){
                   $rand =rand() % strlen($charset);
                   $temp=  substr($charset,$rand,3);
                  }
                  $temp= "PROP".$_SESSION['lastID']."$$$";
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
                  mysqli_query($con,$SQL1) or die(mysqli_error()."PA-115<br>".$SQL1);
                  }

                if($formname=="servicepropertylist.php")header("location:servicepropertylist.php?Client=".$_REQUEST['txt_A_objectcode']."&ID=".$_REQUEST['txt_A_docid']."");

       }
       
       if($formname=="contactactivity.php"){
                   $seqID = GetLastSqeID("in_crmvisit");

               if($_REQUEST['cmb_A_status']=="Happened" && $_REQUEST['txd_A_followupdate']!=""){

                   $Dvalue  = explode('-',$_REQUEST['txd_A_followupdate']);
                   $value   = $Dvalue[2].'-'.$Dvalue[1].'-'.$Dvalue[0];

                   $SQL2        = "Insert into in_crmvisit(
                                  id,activitytype,objectcode,objectname,visitdate,
                                  eventtype,priority,location,status,action,companycode,locationcode,userid)
                                  values (".$seqID.",'".$_REQUEST['txt_A_activitytype']."','".$_REQUEST['txt_A_objectcode']."','".$_REQUEST['txt_A_objectname']."','".$value."',
                                  '".$_REQUEST['cmb_A_eventtype']."','".$_REQUEST['cmb_A_priority']."','".$_REQUEST['txt_A_location']."','Open','".$_REQUEST['txa_A_followupdetail']."'
                                  ,'".$_SESSION['SESScompanycode']."','".$_SESSION['SESSUserLocation']."','".$_SESSION['SESSuserID']."')";

                   $SQLRes     =  mysqli_query($con,$SQL2) or die($SQL2);
               }
       }
       if($formname=="eventtelecall.php"){

               $seqID = GetLastSqeID("in_crmvisit");
               if($_REQUEST['cmb_A_status']=="Happened" && $_REQUEST['txd_A_followupdate']!=""){

                   $Dvalue  = explode('-',$_REQUEST['txd_A_followupdate']);
                   $value   = $Dvalue[2].'-'.$Dvalue[1].'-'.$Dvalue[0];

                   if($_REQUEST['cmb_A_assignto']!=""){
                      $userid=$_REQUEST['cmb_A_assignto'];
                   }else{
                      $userid=$_SESSION['SESSuserID'];
                   }
                   $SQL2        = "Insert into in_crmvisit(
                                  id,docid,activitytype,objectcode,objectname,visitdate,
                                  eventtype,priority,location,status,action,companycode,locationcode,userid)
                                  values (".$seqID.",'".$_REQUEST['txt_A_docid']."','".$_REQUEST['txt_A_activitytype']."','".$_REQUEST['txt_A_objectcode']."','".$_REQUEST['txt_A_objectname']."','".$value."',
                                  '".$_REQUEST['cmb_A_eventtype']."','".$_REQUEST['cmb_A_priority']."','".$_REQUEST['txt_A_location']."','Open','".$_REQUEST['txa_A_followupdetail']."'
                                  ,'".$_SESSION['SESScompanycode']."','".$_SESSION['SESSUserLocation']."','".$userid."')";

                   $SQLRes     =  mysqli_query($con,$SQL2) or die($SQL2);
               }
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
                  mysqli_query($con,$SQL1) or die(mysqli_error()."PA-115<br>".$SQL1);
                  }

                if($formname=="addmaterialitem.php")header("location:addmaterialitem.php?ID=".$_REQUEST['txt_A_invheadid']."&txt_A_formtype=".$_REQUEST['txt_A_formtype']);

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
                  mysqli_query($con,$SQL1) or die(mysqli_error()."PA-115<br>".$SQL1);
                  }

                if($formname=="addmaterialitemquote.php")header("location:addmaterialitemquote.php?ID=".$_REQUEST['txt_A_invheadid']."");

       }

       if($formname=="crmcontactperson.php"){

                   $seqID = GetLastSqeID("in_businessobjectdetails");
                   if($_REQUEST['cmb_A_primaryone']=="Yes"){
                      $updatesql1 = "update in_businessobjectdetails set primaryone='No' where businessobjectid='".$_REQUEST['txt_A_businessobjectid']."'
                                  and id<>'".$_SESSION['lastID']."'";
                      $result   = mysqli_query($con,$updatesql1)  or die(mysqli_error()."<br>".$updatesql1);
                   }
       }
       if($formname=="applicantinterview.php"){
             header("location:applicantinterview.php?ID=".$_REQUEST['txt_A_requisitionid']."");
       }
       if($formname=="emp_leavepackage.php"  ){
                   $SQL2 = "UPDATE e_leavepackage SET empleavepackstatus='Inactive' where id <> '".$_SESSION['lastID']."' and staffid='".$RequestBuffer['txt_A_staffid']."'";
                   mysqli_query($con,$SQL2) or die(mysqli_error()."PA-115<br>".$SQL2);

                   $RES_44 = mysqli_query($con,"select id from e_leavepackage where staffid='".$RequestBuffer['txt_A_staffid']."'");
                   if(mysqli_num_rows($RES_44) >1){
                                      $SQL_33 = "select id from e_leavepackage where id =(Select max(id) as docid  from e_leavepackage where staffid='".$RequestBuffer['txt_A_staffid']."' and id <> '".$_SESSION['lastID']."')";
                                      $RES_33 = mysqli_query($con,$SQL_33);
                                      $ARR_33 = mysqli_fetch_array($RES_33);
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
                                         mysqli_query($con,$UP_33) or die(mysqli_error()."PA-115<br>".$UP_33);
                  }
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
                                      mysqli_query($con,$SQL1) or die(mysqli_error()."PA-115<br>".$SQL1);

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
                                      mysqli_query($con,$SQL1) or die(mysqli_error()."PA-115<br>".$SQL1);

                                    }
                                }
             if($formname=="emp_documents.php")header("location:emp_documents.php?entitytype=".$_REQUEST['txt_A_entitytype']."&ID=".$_REQUEST['txt_A_staffid']."");

         }
      /*if($formname=="emp_leave.php"){


                  $SQL   = "Select datediff(leaveto,leavefrom) as days from e_leave  WHERE id='".$_SESSION['lastID']."'";
                  $SQLRes =  mysqli_query($con,$SQL) or die(mysqli_error()."<br>".$SQL);
                  if(mysqli_num_rows($SQLRes)>=1){
                   $loginResultArray   = mysqli_fetch_array($SQLRes);
                   $days= $loginResultArray['days']+1;

                  }
                        $SQL   = "SELECT LASTNUMBER AS LASTNUMBER  FROM in_sequencer WHERE TABLENAME='e_leave'";
                        $SQLRes =  mysqli_query($con,$SQL) or die(mysqli_error()."<br>".$SQL);
                        if(mysqli_num_rows($SQLRes)>=1){
                          while($loginResultArray   = mysqli_fetch_array($SQLRes)){
                             $instructionid=$loginResultArray['LASTNUMBER']+1;
                          }
                        }

                $SQL2 = "UPDATE e_leave SET daysallowed=".$days." where id=".$_SESSION['lastID'];
                mysqli_query($con,$SQL2) or die(mysqli_error()."PA-115<br>".$SQL2);

                $squpdateSQL = "UPDATE in_sequencer SET LASTNUMBER=".$instructionid." WHERE TABLENAME='e_leave'";
                mysqli_query($con,$squpdateSQL) or die(mysqli_error()."<br>".$squpdateSQL);

                 //if($formname=="emp_leave.php")header("location:emp_leave.php?ID=".$_REQUEST['txt_A_staffid']."");
         }*/
         if($formname=="emp_leave.php"){


                  $SQL   = "Select availedfrom,availedto,datediff(availedto,availedfrom) as days from e_leave  WHERE id='".$_SESSION['lastID']."'";
                  $SQLRes =  mysqli_query($con,$SQL) or die(mysqli_error()."<br>".$SQL);
                  if(mysqli_num_rows($SQLRes)>=1){
                   $loginResultArray   = mysqli_fetch_array($SQLRes);
                   $days= $loginResultArray['days']+1;
                   $availedfrom=$loginResultArray['availedfrom'];
                   $availedto= $loginResultArray['availedto'];

                  }
                        $SQL   = "SELECT LASTNUMBER AS LASTNUMBER  FROM in_sequencer WHERE TABLENAME='e_leave'";
                        $SQLRes =  mysqli_query($con,$SQL) or die(mysqli_error()."<br>".$SQL);
                        if(mysqli_num_rows($SQLRes)>=1){
                          while($loginResultArray   = mysqli_fetch_array($SQLRes)){
                             $instructionid=$loginResultArray['LASTNUMBER']+1;
                          }
                        }

                $SQL2 = "UPDATE e_leave SET daysallowed='".$days."',daysapproved='".$days."',daysavailed='".$days."',
                         leavefrom='".$availedfrom."',leaveto='".$availedto."',approvedfrom='".$availedfrom."',approvedto='".$availedto."',
                         hr_user_post='YES',hr_user_postdate='".date('Y-m-d')."',hrstatus='APPROVED',daysaviledstatus='YES' where id=".$_SESSION['lastID'];
                mysqli_query($con,$SQL2) or die(mysqli_error()."PA-115<br>".$SQL2);

                $squpdateSQL = "UPDATE in_sequencer SET LASTNUMBER=".$instructionid." WHERE TABLENAME='e_leave'";
                mysqli_query($con,$squpdateSQL) or die(mysqli_error()."<br>".$squpdateSQL);

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
                                      mysqli_query($con,$SQL1) or die(mysqli_error()."PA-115<br>".$SQL1);

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
                                      mysqli_query($con,$SQL1) or die(mysqli_error()."PA-115<br>".$SQL1);

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
                  mysqli_query($con,$SQL1) or die(mysqli_error()."PA-115<br>".$SQL1);
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
                  mysqli_query($con,$SQL1) or die(mysqli_error()."PA-115<br>".$SQL1);
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
                        mysqli_query($con,$SQL1) or die(mysqli_error()."PA-115<br>".$SQL1);
                  }

                if($formname=="applicantapplied.php")header("location:applicantapplied.php?ID=".$_REQUEST['txt_A_requisitionid']."");

         }

          if($formname=="emp_salary.php"  ){
                if($RequestBuffer['txt_A_scaleid']=="CUSTOM"){
                   $seqID = GetLastSqeID("e_payscale");
                   $SQL   = "insert into e_payscale value('".$seqID."','".$seqID."','CUSTOM','C".$RequestBuffer['txt_A_staffid']."','".$_SESSION['CURRDATE']."','YES','YES','".$_SESSION['SESScompanycode']."','".$_SESSION['SESSUserLocation']."')";
                   $SQLRes =  mysqli_query($con,$SQL) or die(mysqli_error()."<br>".$SQL);

                   $SQL2 = "UPDATE e_salary SET scaleid=".$seqID.",customtype='YES' where id=".$_SESSION['lastID'];
                   mysqli_query($con,$SQL2) or die(mysqli_error()."PA-115<br>".$SQL2);
                }

                  $SQL2 = "UPDATE e_salary SET scalestatus='Inactive' where id <> '".$_SESSION['lastID']."' and staffid='".$RequestBuffer['txt_A_staffid']."'";
                   mysqli_query($con,$SQL2) or die(mysqli_error()."PA-115<br>".$SQL2);

                   $RES_44 = mysqli_query($con,"select id from e_salary where staffid='".$RequestBuffer['txt_A_staffid']."'");
                   if(mysqli_num_rows($RES_44) >1){
                                      $SQL_33 = "select id from e_salary where id =(Select max(id) as docid  from e_salary where staffid='".$RequestBuffer['txt_A_staffid']."' and id <> '".$_SESSION['lastID']."')";
                                      $RES_33 = mysqli_query($con,$SQL_33);
                                      $ARR_33 = mysqli_fetch_array($RES_33);
                                      if($RequestBuffer['cmb_A_frommonth']=='01'){
                                        $addsql= "tomonth='12',toyear=".($RequestBuffer['cmb_A_fromyear']-1);
                                      }else{
                                         $xy= $RequestBuffer['cmb_A_frommonth']-1;
                                         $month     =sprintf('%02d', $xy);
                                         $addsql=" tomonth='".$month."' ,toyear=".$RequestBuffer['cmb_A_fromyear'];
                                      }
                                         $UP_33 = "update e_salary set $addsql where id='".$ARR_33['id']."'";
                                         mysqli_query($con,$UP_33) or die(mysqli_error()."PA-115<br>".$UP_33);
                  }
         }

}
function PostUpdateChildStatement($RequestBuffer,$RequestBuffer_row){
	global $con;

         $formname = $_SESSION['CurrentObjectName']->formName;
         
         if($formname=="otcompletionreport.php" || $formname=="completionreport.php") {
                  $temp= "OTCJ".$_REQUEST['childid']."A$$$";
                  $target_path = $target_path.basename($_FILES['userfile']['name']);
                  $target_path = "uploads/";
                  $target_path = $target_path .$temp. basename( $_FILES['userfile']['name']);
                  if($_FILES['userfile']['name']) $fileName= $temp.$_FILES['userfile']['name'];
                  if (file_exists("uploads/".$fileName)){
                   echo "<center><STRONG>Sorry!!" .$fileName . " already exists.</center></STRONG>";
                  }else{
                   move_uploaded_file($_FILES['userfile']['tmp_name'], $target_path);
                  }

                  $temp= "OTCJ".$_REQUEST['childid']."B$$$";
                  $target_path = $target_path.basename($_FILES['userfile1']['name']);
                  $target_path = "uploads/";
                  $target_path = $target_path .$temp. basename( $_FILES['userfile1']['name']);
                  if($_FILES['userfile1']['name']) $fileName2= $temp.$_FILES['userfile1']['name'];
                  if (file_exists("uploads/".$fileName2)){
                   echo "<center><STRONG>Sorry!!" .$fileName2 . " already exists.</center></STRONG>";
                  }else{
                   move_uploaded_file($_FILES['userfile1']['tmp_name'], $target_path);
                  }
                  
                  if($fileName!='') {
                  $SQL1 = "UPDATE ".$_SESSION['CurrentObjectName']->TableNameChild." SET docname='".$fileName."' where id=".$_REQUEST['childid'];
                  mysqli_query($con,$SQL1) or die(mysqli_error()."PA-115<br>".$SQL1);
                  }
                  if($fileName2!='') {
                  $SQL1 = "UPDATE ".$_SESSION['CurrentObjectName']->TableNameChild." SET docname2='".$fileName2."' where id=".$_REQUEST['childid'];
                  mysqli_query($con,$SQL1) or die(mysqli_error()."PA-115<br>".$SQL1);
                  }

                if($formname=="otcompletionreport.php")header("location:otcompletionreport.php?ID=".$_REQUEST['txt_A_invheadid']."&txt_A_formtype=".$_REQUEST['txt_A_formtype']."");
                if($formname=="completionreport.php")header("location:completionreport.php?ID=".$_REQUEST['txt_A_invheadid']."&txt_A_formtype=".$_REQUEST['txt_A_formtype']."");
         
         }
         
         if($formname=="otcombinedjob.php" || $formname=="otmaterialdeliveryjob.php" || $formname=="otsubcontractjob.php" || $formname=="otvariationjob.php"){

                  $temp= "OTCJ".$_REQUEST['childid']."$$$";
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
                  mysqli_query($con,$SQL1) or die(mysqli_error()."PA-115<br>".$SQL1);
                  }
                  /*
                  if($formname=="otsubcontractjob.php") {
                   $SQL1 = "update tbl_servicejobline set materialprice = '".$_REQUEST['txt_A_price']."',materialqty='".$_REQUEST['txt_A_quantity']."' where invheadid='".$_REQUEST['txt_A_invheadid']."' and initemid ='".$_REQUEST['childid']."'";
                   mysqli_query($con,$SQL1) or die(mysqli_error()."PA-115<br>".$SQL1);
                  }
                  if($formname=="otcombinedjob.php" && $_REQUEST['cmb_A_combinedjobtype'] == "SUBCONTRACTOR JOB") {
                   mysqli_query($con,"delete from tbl_servicejobline where invheadid='".$_REQUEST['txt_A_invheadid']."' and initemid ='".$_REQUEST['childid']."' and formtype='".$_REQUEST['txt_A_formtype']."'");
                   $seqNumber1 = GetLastSqeID("tbl_servicejobline");
                   $SQL1 = "insert into tbl_servicejobline(id,invheadid,initemid,materialprice,type,material,specifications,materialqty,unit,formtype) values
                   ('$seqNumber1','".$_REQUEST['txt_A_invheadid']."','".$_REQUEST['childid']."','".$_REQUEST['txt_A_price']."','MATERIAL','NA','NA','".$_REQUEST['txt_A_quantity']."','NA','".$_REQUEST['txt_A_formtype']."')";
                   mysqli_query($con,$SQL1) or die(mysqli_error()."PA-115<br>".$SQL1);
                  }   */
                  
                if($formname=="otcombinedjob.php") header("location:otcombinedjob.php?ID=".$_REQUEST['txt_A_invheadid']."&txt_A_formtype=".$_REQUEST['txt_A_formtype']);
                if($formname=="otmaterialdeliveryjob.php")header("location:otmaterialdeliveryjob.php?ID=".$_REQUEST['txt_A_invheadid']."&txt_A_formtype=".$_REQUEST['txt_A_formtype']);
                if($formname=="otsubcontractjob.php")header("location:otsubcontractjob.php?ID=".$_REQUEST['txt_A_invheadid']."&txt_A_formtype=".$_REQUEST['txt_A_formtype']);
                if($formname=="otvariationjob.php")header("location:otvariationjob.php?ID=".$_REQUEST['txt_A_invheadid']."&txt_A_formtype=".$_REQUEST['txt_A_formtype']);

       }
       
         if($formname=="serviceassets.php"){

                  $charset="ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";
                  $length=5;
                  for($i=0; $i <=$length; $i++){
                   $rand =rand() % strlen($charset);
                   $temp=  substr($charset,$rand,3);
                  }
                  $temp= "ASSET".$_REQUEST['childid']."$$$";
                  $target_path = $target_path.basename($_FILES['userfile']['name']);
                  $target_path = "uploads/";
                  $target_path = $target_path .$temp. basename( $_FILES['userfile']['name']);
                  if($_FILES['userfile']['name']) $fileName= $temp.$_FILES['userfile']['name'];
                  if($fileName!=""){
                  if (file_exists("uploads/".$fileName)){
                   echo "<center><STRONG>Sorry!!" .$fileName . " already exists.</center></STRONG>";
                  }else{
                   move_uploaded_file($_FILES['userfile']['tmp_name'], $target_path);
                  }
                  }
                  //echo $fileName."<br>";
                  if($fileName!='') {
                  $SQL1 = "UPDATE ".$_SESSION['CurrentObjectName']->TableNameChild." SET docname='".$fileName."' where id=".$_REQUEST['childid'];
                  mysqli_query($con,$SQL1) or die(mysqli_error()."PA-115<br>".$SQL1);
                  }

                if($formname=="serviceassets.php")header("location:serviceassets.php?ID=".$_REQUEST['txt_A_docid']."&formtype=".$_REQUEST['txt_A_formtype']);

       }
       
         if($formname=="manpowerforservice.php"){

                  $charset="ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";
                  $length=5;
                  for($i=0; $i <=$length; $i++){
                   $rand =rand() % strlen($charset);
                   $temp=  substr($charset,$rand,3);
                  }
                  $temp= "MAN".$_REQUEST['childid']."$$$";
                  $target_path = $target_path.basename($_FILES['userfile']['name']);
                  $target_path = "uploads/";
                  $target_path = $target_path .$temp. basename( $_FILES['userfile']['name']);
                  if($_FILES['userfile']['name']) $fileName= $temp.$_FILES['userfile']['name'];
                  if($fileName!=""){
                  if (file_exists("uploads/".$fileName)){
                   echo "<center><STRONG>Sorry!!" .$fileName . " already exists.</center></STRONG>";
                  }else{
                   move_uploaded_file($_FILES['userfile']['tmp_name'], $target_path);
                  }
                  }
                  //echo $fileName."<br>";
                  if($fileName!='') {
                  $SQL1 = "UPDATE ".$_SESSION['CurrentObjectName']->TableNameChild." SET docname='".$fileName."' where id=".$_REQUEST['childid'];
                  mysqli_query($con,$SQL1) or die(mysqli_error()."PA-115<br>".$SQL1);
                  }

                if($formname=="manpowerforservice.php")header("location:manpowerforservice.php?ID=".$_REQUEST['txt_A_docid']."");

       }

         
         if($formname=="annexure.php"){
          for($i=0 ;$i < $_REQUEST['selectslots']; $i++){
                                    $imgName = "";
                                    if($i==0){

                                      $seqNumber1 = GetLastSqeID("tbl_attachments");
                                      //$target_path = $target_path.basename($_FILES['userfile']['name']);
                                      $target_path = "uploads/";
                                      $temp = "ANEX".$seqNumber1."$$$";
                                      $target_path = $target_path .$temp. basename( $_FILES['userfile']['name']);

                                      if($_FILES['userfile']['name']) {
                                      	$imgName= $temp.$_FILES['userfile']['name'];

                                       move_uploaded_file($_FILES['userfile']['tmp_name'], $target_path);

                                      $SQL1 = "insert into tbl_attachments(id,docid,docname,userid,doctype,remarks,invheadid)
                                               values('$seqNumber1','".$_REQUEST['childid']."','".$imgName ."','".$_SESSION['SESSuserID']."','".$_REQUEST['LEVEL']."','".$_REQUEST['remarks']."','".$_REQUEST['txt_A_invheadid']."') ";
                                      mysqli_query($con,$SQL1) or die(mysqli_error()."PA-115<br>".$SQL1);
									   }

                                    }else{
                                      $field='userfile'.$i;
                                      $remarks = 'remarks'.$i;
                                      $seqNumber1 = GetLastSqeID("tbl_attachments");
                                     // $target_path = $target_path.basename($_FILES[$field]['name']);
                                      $target_path = "uploads/";
                                      $temp = "ANEX".$seqNumber1."$$$";
                                      $target_path = $target_path .$temp. basename( $_FILES[$field]['name']);

                                      if($_FILES[$field]['name']) {
                                      	
                                      	$imgName= $temp.$_FILES[$field]['name'];


                                       move_uploaded_file($_FILES[$field]['tmp_name'], $target_path);

                                      $SQL1 = "insert into tbl_attachments(id,docid,docname,userid,doctype,remarks,invheadid)
                                               values('$seqNumber1','".$_REQUEST['childid']."','".$imgName ."','".$_SESSION['SESSuserID']."','".$_REQUEST['LEVEL']."','".$_REQUEST[$remarks]."','".$_REQUEST['txt_A_invheadid']."') ";
                                      mysqli_query($con,$SQL1) or die(mysqli_error()."PA-115<br>".$SQL1);
										}
                                    }
                                }
             if($formname=="annexure.php")header("location:annexure.php?ID=".$_REQUEST['txt_A_invheadid']."&LEVEL=".$_REQUEST['LEVEL']."");

         }
         
         if($formname=="servicepropertylist.php"){

                  $charset="ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";
                  $length=5;
                  for($i=0; $i <=$length; $i++){
                   $rand =rand() % strlen($charset);
                   $temp=  substr($charset,$rand,3);
                  }
                  $temp= "PROP".$_REQUEST['childid']."$$$";
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
                  mysqli_query($con,$SQL1) or die(mysqli_error()."PA-115<br>".$SQL1);
                  }

                if($formname=="servicepropertylist.php")header("location:servicepropertylist.php?Client=".$_REQUEST['txt_A_objectcode']."&ID=".$_REQUEST['txt_A_docid']."");

       }
         if($formname=="eventtelecall.php"){

               $seqID = GetLastSqeID("in_crmvisit");
               if($_REQUEST['cmb_A_status']=="Happened" && $_REQUEST['txd_A_followupdate']!=""){

                   $Dvalue  = explode('-',$_REQUEST['txd_A_followupdate']);
                   $value   = $Dvalue[2].'-'.$Dvalue[1].'-'.$Dvalue[0];

                   $deletesql1 = "update in_crmvisit set status='Happened' where id='".$_REQUEST['childid']."'";
                   $result   = mysqli_query($con,$deletesql1)  or die(mysqli_error()."<br>".$deletesql1);

                   if($_REQUEST['cmb_A_assignto']!=""){
                      $userid=$_REQUEST['cmb_A_assignto'];
                   }else{
                      $userid=$_SESSION['SESSuserID'];
                   }
                   $SQL2        = "Insert into in_crmvisit(
                                  id,docid,activitytype,objectcode,objectname,visitdate,
                                  eventtype,priority,location,status,action,companycode,locationcode,userid)
                                  values (".$seqID.",'".$_REQUEST['txt_A_docid']."','".$_REQUEST['txt_A_activitytype']."','".$_REQUEST['txt_A_objectcode']."','".$_REQUEST['txt_A_objectname']."','".$value."',
                                  '".$_REQUEST['cmb_A_eventtype']."','".$_REQUEST['cmb_A_priority']."','".$_REQUEST['txt_A_location']."','Open','".$_REQUEST['txa_A_followupdetail']."'
                                  ,'".$_SESSION['SESScompanycode']."','".$_SESSION['SESSUserLocation']."','".$userid."')";

                   $SQLRes     =  mysqli_query($con,$SQL2) or die($SQL2);
               }
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
                  mysqli_query($con,$SQL1) or die(mysqli_error()."PA-115<br>".$SQL1);
                  }

                if($formname=="addmaterialitem.php")header("location:addmaterialitem.php?ID=".$_REQUEST['txt_A_invheadid']."&txt_A_formtype=".$_REQUEST['txt_A_formtype']);

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
                  mysqli_query($con,$SQL1) or die(mysqli_error()."PA-115<br>".$SQL1);
                  }

                if($formname=="addmaterialitemquote.php")header("location:addmaterialitemquote.php?ID=".$_REQUEST['txt_A_invheadid']."");

       }

       if($formname=="crmcontactperson.php"){

               if($_REQUEST['cmb_A_primaryone']=="Yes"){
                   $deletesql1 = "update in_businessobjectdetails set primaryone='No' where businessobjectid='".$_REQUEST['txt_A_businessobjectid']."'
                                  and id<>'".$_REQUEST['childid']."'";
                   $result   = mysqli_query($con,$deletesql1)  or die(mysqli_error()."<br>".$deletesql1);
               }

       }

       if($formname=="contactactivity.php"){
                   $seqID = GetLastSqeID("in_crmvisit");

               if($_REQUEST['cmb_A_status']=="Happened" && $_REQUEST['txd_A_followupdate']!=""){

                   $Dvalue  = explode('-',$_REQUEST['txd_A_followupdate']);
                   $value   = $Dvalue[2].'-'.$Dvalue[1].'-'.$Dvalue[0];

                   $SQL2        = "Insert into in_crmvisit(
                                  id,activitytype,objectcode,objectname,visitdate,fromhour,
                                  eventtype,priority,location,status,action,companycode,locationcode,userid)
                                  values (".$seqID.",'".$_REQUEST['txt_A_activitytype']."','".$_REQUEST['txt_A_objectcode']."','".$_REQUEST['txt_A_objectname']."','".$value."','".$_REQUEST['txt_A_fromhour']."',
                                  '".$_REQUEST['cmb_A_eventtype']."','".$_REQUEST['cmb_A_priority']."','".$_REQUEST['txt_A_location']."','Open','".$_REQUEST['txa_A_followupdetail']."'
                                  ,'".$_SESSION['SESScompanycode']."','".$_SESSION['SESSUserLocation']."','".$_SESSION['SESSuserID']."')";

                   $SQLRes     =  mysqli_query($con,$SQL2) or die($SQL2);
               }
         }
         if($formname=="applicantinterview.php"){
             header("location:applicantinterview.php?ID=".$_REQUEST['txt_A_requisitionid']."");
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
                                      mysqli_query($con,$SQL1) or die(mysqli_error()."PA-115<br>".$SQL1);

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
                                      mysqli_query($con,$SQL1) or die(mysqli_error()."PA-115<br>".$SQL1);

                                    }
                                }
             if($formname=="emp_documents.php")header("location:emp_documents.php?entitytype=".$_REQUEST['txt_A_entitytype']."&ID=".$_REQUEST['txt_A_staffid']."");

         }
         if(  $formname=="editemp_leaverequest.php"){


                  $SQL   = "Select datediff(leaveto,leavefrom) as days from e_leave  WHERE id='".$_REQUEST['childid']."'";
                  $SQLRes =  mysqli_query($con,$SQL) or die(mysqli_error()."<br>".$SQL);
                  if(mysqli_num_rows($SQLRes)>=1){
                   $loginResultArray   = mysqli_fetch_array($SQLRes);
                   $days= $loginResultArray['days']+1;

                  }


                $SQL2 = "UPDATE e_leave SET daysallowed=".$days." where id=".$_REQUEST['childid'];
                mysqli_query($con,$SQL2) or die(mysqli_error()."PA-115<br>".$SQL2);


               //  if($formname=="emp_leave.php")header("location:emp_leave.php?ID=".$_REQUEST['txt_A_staffid']."");
         }
         if($formname=="emp_leave.php"){
              $SQL   = "Select availedfrom,availedto,datediff(availedto,availedfrom) as days from e_leave  WHERE id='".$_REQUEST['childid']."'";
              $SQLRes =  mysqli_query($con,$SQL) or die(mysqli_error()."<br>".$SQL);
                  if(mysqli_num_rows($SQLRes)>=1){
                   $loginResultArray   = mysqli_fetch_array($SQLRes);
                     $days= $loginResultArray['days']+1;
                     $availedfrom= $loginResultArray['availedfrom'];
                     $availedto=  $loginResultArray['availedto'];
                  }
                $SQL2 = "UPDATE e_leave SET daysallowed='".$days."',daysapproved='".$days."',daysavailed='".$days."',
                         leavefrom='".$availedfrom."',leaveto='".$availedto."',approvedfrom='".$availedfrom."',approvedto='".$availedto."'
                         where id=".$_REQUEST['childid'];
                mysqli_query($con,$SQL2) or die(mysqli_error()."PA-115<br>".$SQL2);

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
                  mysqli_query($con,$SQL1) or die(mysqli_error()."PA-115<br>".$SQL1);
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
                  mysqli_query($con,$SQL1) or die(mysqli_error()."PA-115<br>".$SQL1);
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
                        mysqli_query($con,$SQL1) or die(mysqli_error()."PA-115<br>".$SQL1);
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
                  mysqli_query($con,$SQL1) or die(mysqli_error()."PA-115<br>".$SQL1);
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
                                      mysqli_query($con,$SQL1) or die(mysqli_error()."PA-115<br>".$SQL1);

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
                                      mysqli_query($con,$SQL1) or die(mysqli_error()."PA-115<br>".$SQL1);

                                    }
                                }
             if($formname=="contractamendments.php")header("location:contractamendments.php?ID=".$_REQUEST['txt_A_docid']."");

         }
}
function GetUpdateChildStatement($RequestBuffer,$RequestBuffer_row){
	global $con;
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
        $result   = mysqli_query($con,$strSQL)  or die(mysqli_error());

        UserLog($_SESSION['CurrentObjectName']->TableNameChild,$_REQUEST['childid'],$strSQL,"UPDATE");
        echo "Record Updated";
   }
    function UTrim($str1){
        return trim(strtoupper($str1));
    }
    function PostAddConditions($tblName,$RequestBuffer){
    	global $con;

        $formname = $_SESSION['CurrentObjectName']->formName;
        if($formname=="crmcontactlist.php"){

            $temp= "CUSTOMER".$_SESSION['lastID']."A$$$";
            $target_path = "uploads/";
            $target_path = $target_path.$temp . basename( $_FILES['tradelicense']['name']);
          	if($_FILES['tradelicense']['name']) $fileName= $temp.$_FILES['tradelicense']['name'];

          	if (file_exists("uploads/".$fileName)){
           		echo "<center><STRONG>Sorry!!" .$fileName . " already exists.</center></STRONG>";
          	}
          	else{
          		 move_uploaded_file($_FILES['tradelicense']['tmp_name'], $target_path);
          	}
          	if(basename($_FILES['tradelicense']['name'])){
          		$SQL1 = "UPDATE ".$_SESSION['CurrentObjectName']->TableName." SET tradelicense='".$fileName."' where id=".$_SESSION['lastID'];
          		mysqli_query($con,$SQL1) or die(mysqli_error()."PA-115<br>".$SQL1);
         	}
         	
         	
         	$temp= "CUSTOMER".$_SESSION['lastID']."B$$$";
            $target_path = "uploads/";
            $target_path = $target_path.$temp . basename( $_FILES['vatupload']['name']);
          	if($_FILES['vatupload']['name']) $fileName= $temp.$_FILES['vatupload']['name'];

          	if (file_exists("uploads/".$fileName)){
           		echo "<center><STRONG>Sorry!!" .$fileName . " already exists.</center></STRONG>";
          	}
          	else{
          		 move_uploaded_file($_FILES['vatupload']['tmp_name'], $target_path);
          	}
          	if(basename($_FILES['vatupload']['name'])){
          		$SQL1 = "UPDATE ".$_SESSION['CurrentObjectName']->TableName." SET vatupload='".$fileName."' where id=".$_SESSION['lastID'];
          		mysqli_query($con,$SQL1) or die(mysqli_error()."PA-115<br>".$SQL1);
         	}

            if($_REQUEST['action']=='save'){
            	if($formname=="crmcontactlist.php")header("location:editcrmcontactlist.php?txtsearch=".$_REQUEST['searchvalue']."&frmPage_rowcount==".$_REQUEST['frmPage_rowcount']."&dr=edit&ID=".$_SESSION['lastID']."");
        	}elseif($_REQUEST['action']=='savenew'){
                if($formname=="crmcontactlist.php")header("location:editcrmcontactlist.php?txtsearch=".$_REQUEST['searchvalue']."&frmPage_rowcount==".$_REQUEST['frmPage_rowcount']."&dr=add&ID=0");
            }
            elseif($_REQUEST['action']=='saveclose'){
                if($formname=="crmcontactlist.php")header("location:crmcontactlist.php");
            }
        }






        if($formname=="crmcontactlist.php"){
                   // to add direct customers , and account ledger
                   if($RequestBuffer['cmb_A_objecttype'] == "Customer"){
                   $category=9;
                   $SQL1   = "SELECT right(accountheadcode,4)*1 as count FROM in_accounthead WHERE postinggroupcode='$category' order by right(accountheadcode,4)*1 desc limit 0,1 ";
                   $Res1 =  mysqli_query($con,$SQL1) or die(mysqli_error()."<br>".$SQL1);

                   if(mysqli_num_rows($Res1)>=1){
                       $Array1   = mysqli_fetch_array($Res1);
                           $count =  $Array1['count']+1 ;
                           }
                       $countzeros = str_pad($count, 5, "0", STR_PAD_LEFT);
                       $SQL   = "SELECT groupcode FROM in_accountgroup WHERE id='".$category."' ";
                       $SQLRes =  mysqli_query($con,$SQL) or die(mysqli_error()."<br>".$SQL);
                       if(mysqli_num_rows($SQLRes)>=1){
                          $loginResultArray   = mysqli_fetch_array($SQLRes);
                               $groupcode=  $loginResultArray['groupcode'];
                               $str = substr($groupcode, 0, -5);
                               $ledgercode=$str.$countzeros;
                       }
                    $SQL5 = "Select * from in_accounthead where (accountheadname='".$_REQUEST['txt_A_objectname']."' or objectcode='".$RequestBuffer['txt_A_objectcode']."') and postinggroupcode='$category'";
                                  $SQLRes5 =  mysqli_query($con,$SQL5) or die(mysqli_error()."<br>".$SQL5);
                                  if(mysqli_num_rows($SQLRes5)==0){

                                              $clientcode = $ledgercode;
                                              $seqID = GetLastSqeID_current("in_accounthead");
                                              $insAccountSQL = "INSERT INTO in_accounthead
                                                                VALUES('$seqID','$category','$ledgercode','".$_REQUEST['txt_A_objectname']."','".$RequestBuffer['txt_A_objectcode']."','".$_REQUEST['txt_A_objectname']."','0','','','Party',
                                                                       '','','','','','','','Yes','No','$count','Active','".$_REQUEST['contactperson']."','".$_REQUEST['billingemail']."','".$_REQUEST['billingfax']."',
                                                                       '".$_REQUEST['phonecode1']."','".$_REQUEST['phonecode2']."','".$_REQUEST['billingaddress1']."',
                                                                       '','','No','".$_REQUEST['website']."','".$_REQUEST['vatid']."','','','','','','','','','','')";
                                              mysqli_query($con,$insAccountSQL) or die(mysqli_error()."<br>".$insAccountSQL);

                                              $squpdateSQL = "UPDATE in_sequencer SET LASTNUMBER=LASTNUMBER+1 WHERE TABLENAME='in_accounthead'";
                                              mysqli_query($con,$squpdateSQL) or die(mysqli_error()."<br>".$squpdateSQL);

                                              $squpdateSQL33 = "UPDATE in_businessobject SET accountheadcode='$ledgercode',objecttype='Customer',eccno='".$ledgercode."' WHERE objectcode='".$RequestBuffer['txt_A_objectcode']."'";
                                              mysqli_query($con,$squpdateSQL33) or die(mysqli_error()."<br>".$squpdateSQL33);
                                  }

                                  }
                   //
                        $seqNumber1 = GetLastSqeID("in_businessobjectdetails");
                        $SQL1 = "insert into in_businessobjectdetails(id,businessobjectid,contactname,phone,primaryone) values('$seqNumber1','".$_SESSION['lastID']."','".$RequestBuffer['txt_A_contactperson']."','".$RequestBuffer['txt_A_phonecode1']."','YES')";
                        mysqli_query($con,$SQL1) or die(mysqli_error()."PA-115<br>".$SQL1);

        }
        if($formname=="buildingmaster.php"){

            $temp= "BUILDING".$_SESSION['lastID']."A$$$";
            $target_path = "building/";
            $target_path = $target_path.$temp . basename( $_FILES['docname']['name']);
          	if($_FILES['docname']['name']) $fileName= $temp.$_FILES['docname']['name'];

          	if (file_exists("uploads/".$fileName)){
           		echo "<center><STRONG>Sorry!!" .$fileName . " already exists.</center></STRONG>";
          	}
          	else{
          		 move_uploaded_file($_FILES['docname']['tmp_name'], $target_path);
          	}
          	if(basename($_FILES['docname']['name'])){
          		$SQL1 = "UPDATE ".$_SESSION['CurrentObjectName']->TableName." SET docname='".$fileName."' where id=".$_SESSION['lastID'];
          		mysqli_query($con,$SQL1) or die(mysqli_error()."PA-115<br>".$SQL1);
         	}



            if($_REQUEST['action']=='save'){
            	if($formname=="buildingmaster.php")header("location:editbuildingmaster.php?txtsearch=".$_REQUEST['searchvalue']."&frmPage_rowcount==".$_REQUEST['frmPage_rowcount']."&dr=edit&ID=".$_SESSION['lastID']."");
        	}elseif($_REQUEST['action']=='savenew'){
                if($formname=="buildingmaster.php")header("location:editbuildingmaster.php?txtsearch=".$_REQUEST['searchvalue']."&frmPage_rowcount==".$_REQUEST['frmPage_rowcount']."&dr=add&ID=0");
            }
            elseif($_REQUEST['action']=='saveclose'){
                if($formname=="buildingmaster.php")header("location:buildingmaster.php");
            }
        }

        if($formname=="crmleadvisitlistcontact.php"){

                                  $charset="ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";
                                  $length=5;
                                  for($i=0; $i <=$length; $i++){
                                    $rand =rand() % strlen($charset);
                                    $temp=  substr($charset,$rand,3);
                                  }
                                  $target_path = "uploads/";
                                  $target_path = $target_path.$temp . basename( $_FILES['userfile']['name']);
                                  if($_FILES['userfile']['name']) $fileName= $temp.$_FILES['userfile']['name'];

                                  if (file_exists("uploads/".$fileName)){
                                   echo "<center><STRONG>Sorry!!" .$fileName . " already exists.</center></STRONG>";
                                  }else{
                                   move_uploaded_file($_FILES['userfile']['tmp_name'], $target_path);
                                  }
                                  if(basename($_FILES['userfile']['name'])){
                                  $SQL1 = "UPDATE ".$_SESSION['CurrentObjectName']->TableName." SET docname='".$fileName."' where id=".$_SESSION['lastID'];
                                  mysqli_query($con,$SQL1) or die(mysqli_error()."PA-115<br>".$SQL1);
                                 }
                                 if($_REQUEST['action']=='save'){
                                   if($formname=="crmleadvisitlistcontact.php")header("location:editcrmleadvisitlistcontact.php?dr=edit&ID=".$_SESSION['lastID']."");
                                 }elseif($_REQUEST['action']=='savenew'){
                                   if($formname=="crmleadvisitlistcontact.php")header("location:editcrmleadvisitlistcontact.php?dr=add&ID=0");
                                 }
                                 elseif($_REQUEST['action']=='saveclose'){
                                   if($formname=="crmleadvisitlistcontact.php")header("location:crmleadvisitlistcontact.php");
                                 }
        }
        if($formname=="forwardleadlist.php"){
                                if($_REQUEST['action']=='save'){
                                   if($formname=="forwardleadlist.php")header("location:editforwardleadlist.php?txtsearch=".$_REQUEST['searchvalue']."&frmPage_rowcount==".$_REQUEST['frmPage_rowcount']."&dr=edit&ID=".$_SESSION['lastID']."");
                                 }elseif($_REQUEST['action']=='savenew'){
                                   if($formname=="forwardleadlist.php")header("location:editforwardleadlist.php?txtsearch=".$_REQUEST['searchvalue']."&frmPage_rowcount==".$_REQUEST['frmPage_rowcount']."&dr=add&ID=0");
                                 }
                                 elseif($_REQUEST['action']=='saveclose'){
                                   if($formname=="forwardleadlist.php")header("location:forwardleadlist.php");
                                 }
        }
        if($formname=="calloutrequest.php"){
                                  $squpdateSQL = "UPDATE in_sequencer_crm SET LASTNUMBER=LASTNUMBER+1 WHERE TABLENAME='AMCCOT_enquiry'";
                                  mysqli_query($con,$squpdateSQL) or die(mysqli_error()."<br>".$squpdateSQL);

                                  $SQL1   = "SELECT LASTNUMBER from in_sequencer_crm where TABLENAME='AMCCOT_enquiry'";
                                  $SQLRes1 =  mysqli_query($con,$SQL1) or die(mysqli_error()."<br>".$SQL1);
                                  if(mysqli_num_rows($SQLRes1)>=1){
                                       while($loginResultArray1   = mysqli_fetch_array($SQLRes1)){
                                             $seqdocid = $loginResultArray1['LASTNUMBER'];
                                       }
                                  }

                                  $squpdateSQL1 = "UPDATE in_crmhead SET docno='EACOT/".str_pad($seqdocid, 5, '0', STR_PAD_LEFT)."/".date("y")."' WHERE id='".$_SESSION['lastID']."'";
                                  mysqli_query($con,$squpdateSQL1) or die(mysqli_error()."<br>".$squpdateSQL1);

                                  $temp= "TICK".$_SESSION['lastID']."$$$";
                                  $target_path = "uploads/";
                                  $target_path = $target_path.$temp . basename( $_FILES['userfile']['name']);
                                  if($_FILES['userfile']['name']) $fileName= $temp.$_FILES['userfile']['name'];

                                  if (file_exists("uploads/".$fileName)){
                                   echo "<center><STRONG>Sorry!!" .$fileName . " already exists.</center></STRONG>";
                                  }else{
                                   move_uploaded_file($_FILES['userfile']['tmp_name'], $target_path);
                                  }
                                  if(basename($_FILES['userfile']['name'])){
                                  $SQL1 = "UPDATE ".$_SESSION['CurrentObjectName']->TableName." SET docname='".$fileName."' where id=".$_SESSION['lastID'];
                                  mysqli_query($con,$SQL1) or die(mysqli_error()."PA-115<br>".$SQL1);
                                 }

                                 if($_REQUEST['action']=='save'){
                                   if($formname=="calloutrequest.php")header("location:editcalloutrequest.php?txtsearch=".$_REQUEST['searchvalue']."&frmPage_rowcount==".$_REQUEST['frmPage_rowcount']."&dr=edit&ID=".$_SESSION['lastID']."");
                                 }elseif($_REQUEST['action']=='savenew'){
                                   if($formname=="calloutrequest.php")header("location:editcalloutrequest.php?txtsearch=".$_REQUEST['searchvalue']."&frmPage_rowcount==".$_REQUEST['frmPage_rowcount']."&dr=add&ID=0");
                                 }
                                 elseif($_REQUEST['action']=='saveclose'){
                                   if($formname=="calloutrequest.php")header("location:calloutrequest.php");
                                 }
        }
        if($formname=="emgcalloutrequest.php"){
                                  $squpdateSQL = "UPDATE in_sequencer_crm SET LASTNUMBER=LASTNUMBER+1 WHERE TABLENAME='EMG_enquiry'";
                                  mysqli_query($con,$squpdateSQL) or die(mysqli_error()."<br>".$squpdateSQL);

                                  $SQL1   = "SELECT LASTNUMBER from in_sequencer_crm where TABLENAME='EMG_enquiry'";
                                  $SQLRes1 =  mysqli_query($con,$SQL1) or die(mysqli_error()."<br>".$SQL1);
                                  if(mysqli_num_rows($SQLRes1)>=1){
                                       while($loginResultArray1   = mysqli_fetch_array($SQLRes1)){
                                             $seqdocid = $loginResultArray1['LASTNUMBER'];
                                       }
                                  }

                                  $squpdateSQL1 = "UPDATE in_crmhead SET docno='EEMG/".str_pad($seqdocid, 5, '0', STR_PAD_LEFT)."/".date("y")."' WHERE id='".$_SESSION['lastID']."'";
                                   mysqli_query($con,$squpdateSQL1) or die(mysqli_error()."<br>".$squpdateSQL1);

                                  $temp= "TICK".$_SESSION['lastID']."$$$";
                                  $target_path = "uploads/";
                                  $target_path = $target_path.$temp . basename( $_FILES['userfile']['name']);
                                  if($_FILES['userfile']['name']) $fileName= $temp.$_FILES['userfile']['name'];

                                  if (file_exists("uploads/".$fileName)){
                                   echo "<center><STRONG>Sorry!!" .$fileName . " already exists.</center></STRONG>";
                                  }else{
                                   move_uploaded_file($_FILES['userfile']['tmp_name'], $target_path);
                                  }
                                  if(basename($_FILES['userfile']['name'])){
                                  $SQL1 = "UPDATE ".$_SESSION['CurrentObjectName']->TableName." SET docname='".$fileName."' where id=".$_SESSION['lastID'];
                                  mysqli_query($con,$SQL1) or die(mysqli_error()."PA-115<br>".$SQL1);
                                 }

                                 if($_REQUEST['action']=='save'){
                                   if($formname=="emgcalloutrequest.php")header("location:editemgcalloutrequest.php?txtsearch=".$_REQUEST['searchvalue']."&frmPage_rowcount==".$_REQUEST['frmPage_rowcount']."&dr=edit&ID=".$_SESSION['lastID']."");
                                 }elseif($_REQUEST['action']=='savenew'){
                                   if($formname=="emgcalloutrequest.php")header("location:editemgcalloutrequest.php?txtsearch=".$_REQUEST['searchvalue']."&frmPage_rowcount==".$_REQUEST['frmPage_rowcount']."&dr=add&ID=0");
                                 }
                                 elseif($_REQUEST['action']=='saveclose'){
                                   if($formname=="emgcalloutrequest.php")header("location:emgcalloutrequest.php");
                                 }
        }
        if($formname=="otleadheadlist.php"){
                                  $squpdateSQL = "UPDATE in_sequencer_crm SET LASTNUMBER=LASTNUMBER+1 WHERE TABLENAME='OT_enquiry'";
                                  mysqli_query($con,$squpdateSQL) or die(mysqli_error()."<br>".$squpdateSQL);

                                  $SQL1   = "SELECT LASTNUMBER from in_sequencer_crm where TABLENAME='OT_enquiry'";
                                  $SQLRes1 =  mysqli_query($con,$SQL1) or die(mysqli_error()."<br>".$SQL1);
                                  if(mysqli_num_rows($SQLRes1)>=1){
                                       while($loginResultArray1   = mysqli_fetch_array($SQLRes1)){
                                             $seqdocid = $loginResultArray1['LASTNUMBER'];
                                       }
                                  }

                                  $squpdateSQL1 = "UPDATE in_crmhead SET docno=concat('E',left(enquirycategory,2),'/".str_pad($seqdocid, 5, '0', STR_PAD_LEFT)."/".date("y")."') WHERE id='".$_SESSION['lastID']."'";
                                  mysqli_query($con,$squpdateSQL1) or die(mysqli_error()."<br>".$squpdateSQL1);

                                  $charset="ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";
                                  $length=5;
                                  for($i=0; $i <=$length; $i++){
                                    $rand =rand() % strlen($charset);
                                    $temp=  substr($charset,$rand,3);
                                  }

                                  $temp= "CRM".$_SESSION['lastID']."$$$";
                                  $target_path = "uploads/";
                                  $target_path = $target_path.$temp . basename( $_FILES['userfile']['name']);
                                  if($_FILES['userfile']['name']) $fileName= $temp.$_FILES['userfile']['name'];

                                  if (file_exists("uploads/".$fileName)){
                                   echo "<center><STRONG>Sorry!!" .$fileName . " already exists.</center></STRONG>";
                                  }else{
                                   move_uploaded_file($_FILES['userfile']['tmp_name'], $target_path);
                                  }
                                  if(basename($_FILES['userfile']['name'])){
                                  $SQL1 = "UPDATE ".$_SESSION['CurrentObjectName']->TableName." SET docname='".$fileName."' where id=".$_SESSION['lastID'];
                                  mysqli_query($con,$SQL1) or die(mysqli_error()."PA-115<br>".$SQL1);


                                 }

                                 if($_REQUEST['action']=='save'){
                                   if($formname=="otleadheadlist.php")header("location:editotleadheadlist.php?txtsearch=".$_REQUEST['searchvalue']."&frmPage_rowcount==".$_REQUEST['frmPage_rowcount']."&dr=edit&ID=".$_SESSION['lastID']."");
                                 }elseif($_REQUEST['action']=='savenew'){
                                   if($formname=="otleadheadlist.php")header("location:editotleadheadlist.php?txtsearch=".$_REQUEST['searchvalue']."&frmPage_rowcount==".$_REQUEST['frmPage_rowcount']."&dr=add&ID=0");
                                 }
                                 elseif($_REQUEST['action']=='saveclose'){
                                   if($formname=="otleadheadlist.php")header("location:otleadheadlist.php");
                                 }
        }
        if($formname=="newleadheadlist.php"){
                                  $squpdateSQL = "UPDATE in_sequencer_crm SET LASTNUMBER=LASTNUMBER+1 WHERE TABLENAME='crmdocno'";
                                  mysqli_query($con,$squpdateSQL) or die(mysqli_error()."<br>".$squpdateSQL);

                                  $SQL1   = "SELECT LASTNUMBER from in_sequencer_crm where TABLENAME='crmdocno'";
                                  $SQLRes1 =  mysqli_query($con,$SQL1) or die(mysqli_error()."<br>".$SQL1);
                                  if(mysqli_num_rows($SQLRes1)>=1){
                                       while($loginResultArray1   = mysqli_fetch_array($SQLRes1)){
                                             $seqdocid = $loginResultArray1['LASTNUMBER'];
                                       }
                                  }

                                  if($_REQUEST['cmb_A_enquirycategory'] == "AMC Enquiry"){
                                  $squpdateSQL1 = "UPDATE in_crmhead SET docno=concat('E',left(enquirycategory,3),'/".str_pad($seqdocid, 5, '0', STR_PAD_LEFT)."/".date("y")."') WHERE id='".$_SESSION['lastID']."'";
                                  }
                                  else{
                                  $squpdateSQL1 = "UPDATE in_crmhead SET docno=concat('E',left(enquirycategory,2),'/".str_pad($seqdocid, 5, '0', STR_PAD_LEFT)."/".date("y")."') WHERE id='".$_SESSION['lastID']."'";
                                  }
                                  mysqli_query($con,$squpdateSQL1) or die(mysqli_error()."<br>".$squpdateSQL1);

                                  $charset="ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";
                                  $length=5;
                                  for($i=0; $i <=$length; $i++){
                                    $rand =rand() % strlen($charset);
                                    $temp=  substr($charset,$rand,3);
                                  }
                                  
                                  $temp= "CRM".$_SESSION['lastID']."$$$";
                                  $target_path = "uploads/";
                                  $target_path = $target_path.$temp . basename( $_FILES['userfile']['name']);
                                  if($_FILES['userfile']['name']) $fileName= $temp.$_FILES['userfile']['name'];

                                  if (file_exists("uploads/".$fileName)){
                                   echo "<center><STRONG>Sorry!!" .$fileName . " already exists.</center></STRONG>";
                                  }else{
                                   move_uploaded_file($_FILES['userfile']['tmp_name'], $target_path);
                                  }
                                  if(basename($_FILES['userfile']['name'])){
                                  $SQL1 = "UPDATE ".$_SESSION['CurrentObjectName']->TableName." SET docname='".$fileName."' where id=".$_SESSION['lastID'];
                                  mysqli_query($con,$SQL1) or die(mysqli_error()."PA-115<br>".$SQL1);
                                 }
                                 
                                 if($_REQUEST['action']=='save'){
                                   if($formname=="newleadheadlist.php")header("location:editnewleadheadlist.php?txtsearch=".$_REQUEST['searchvalue']."&frmPage_rowcount==".$_REQUEST['frmPage_rowcount']."&dr=edit&ID=".$_SESSION['lastID']."");
                                 }elseif($_REQUEST['action']=='savenew'){
                                   if($formname=="newleadheadlist.php")header("location:editnewleadheadlist.php?txtsearch=".$_REQUEST['searchvalue']."&frmPage_rowcount==".$_REQUEST['frmPage_rowcount']."&dr=add&ID=0");
                                 }
                                 elseif($_REQUEST['action']=='saveclose'){
                                   if($formname=="newleadheadlist.php")header("location:newleadheadlist.php");
                                 }
        }

        if($formname=="crmleadvisitlistcontact.php"){

               $seqID = GetLastSqeID("in_crmvisit");
               if($_REQUEST['cmb_A_status']=="Happened" && $_REQUEST['txd_A_followupdate']!=""){

                   $Dvalue  = explode('-',$_REQUEST['txd_A_followupdate']);
                   $value   = $Dvalue[2].'-'.$Dvalue[1].'-'.$Dvalue[0];

                   $SQL2        ="Insert into in_crmvisit(
                                  id,activitytype,objectcode,objectname,visitdate,
                                  eventtype,priority,location,status,action,companycode,locationcode,userid)
                                  values (".$seqID.",'".$_REQUEST['cmb_A_activitytype']."','".$_REQUEST['txt_A_objectcode']."','".$_REQUEST['txt_A_objectname']."','".$value."',
                                  '".$_REQUEST['cmb_A_eventtype']."','".$_REQUEST['cmb_A_priority']."','".$_REQUEST['txt_A_location']."','Open','".$_REQUEST['txa_A_followupdetail']."'
                                  ,'','','".$_SESSION['SESSuserID']."')";

                   $SQLRes     =  mysqli_query($con,$SQL2) or die($SQL2);
               }
        }
        if($formname=="maineventshead.php"){

            $dateextend = date('Y-m-d', strtotime($RequestBuffer['txd_A_todate']. "+ ".$RequestBuffer['txt_A_adddays']." days"));
            $squpdateSQL = "UPDATE in_crmeventhead SET dateextend='$dateextend' WHERE id='".$_SESSION['lastID']."'";
            mysqli_query($con,$squpdateSQL) or die(mysqli_error()."<br>".$squpdateSQL);

            if($_REQUEST['action']=='save'){
                if($formname=="maineventshead.php")header("location:editmaineventshead.php?dr=edit&ID=".$_SESSION['lastID']."");
            }elseif($_REQUEST['action']=='savenew'){
                if($formname=="maineventshead.php")header("location:editmaineventshead.php?dr=add&ID=0");
            }elseif($_REQUEST['action']=='saveclose'){
                if($formname=="maineventshead.php")header("location:maineventshead.php");
            }
        }

        if($formname=="salesorderheadlist.php"){

           $squpdateSQL = "UPDATE in_sequencer_crm SET LASTNUMBER=LASTNUMBER+1 WHERE TABLENAME='crmdocno'";
           mysqli_query($con,$squpdateSQL) or die(mysqli_error()."<br>".$squpdateSQL);

           $SQL1   = "SELECT LASTNUMBER from in_sequencer_crm where TABLENAME='crmdocno'";
           $SQLRes1 =  mysqli_query($con,$SQL1) or die(mysqli_error()."<br>".$SQL1);
           if(mysqli_num_rows($SQLRes1)>=1){
              while($loginResultArray1   = mysqli_fetch_array($SQLRes1)){
                    $seqdocid = $loginResultArray1['LASTNUMBER'];
              }
           }
           if($_REQUEST['cmb_A_enquirycategory'] == "AMC Enquiry"){
           $squpdateSQL1 = "UPDATE in_crmhead SET docno=concat('SO',left(enquirycategory,3),'-".str_pad($seqdocid, 5, '0', STR_PAD_LEFT)."') WHERE id='".$_SESSION['lastID']."'";
           }
           else{
           $squpdateSQL1 = "UPDATE in_crmhead SET docno=concat('SO',left(enquirycategory,2),'-".str_pad($seqdocid, 5, '0', STR_PAD_LEFT)."') WHERE id='".$_SESSION['lastID']."'";
           }
           mysqli_query($con,$squpdateSQL1) or die(mysqli_error()."<br>".$squpdateSQL1);
           
                                       //// update startdate and enddate
                                 if($_REQUEST['txd_A_startdate']!="" && $_REQUEST['txd_A_startdate']!="00-00-0000"){
                                    if($_REQUEST['cmb_A_durationtype'] !="" && $_REQUEST['txt_A_durationnos']!=""){
                                       $startdate_arr = explode('-',$_REQUEST['txd_A_startdate']);
                                       $date = $startdate_arr[2]."-".$startdate_arr[1]."-".$startdate_arr[0];
                                       $duration = $_REQUEST['txt_A_durationnos'];
                                        if($_REQUEST['cmb_A_durationtype']=="Days"){
                                           $enddate = date("Y-m-d", strtotime($date. " + $duration days"));
                                       }
                                        else if($_REQUEST['cmb_A_durationtype']=="Months"){
                                           $enddate = date("Y-m-d", strtotime($date. " + $duration months"));
                                       }
                                       else  if($_REQUEST['cmb_A_durationtype']=="Years"){
                                           $enddate = date("Y-m-d", strtotime($date. " + $duration years"));
                                       }
                                       $enddate = date("Y-m-d", strtotime($enddate. " -1 day"));
                                       $upsql ="update in_crmhead set enddate='$enddate' where id=".$_SESSION['lastID'];
                                       mysqli_query($con,$upsql);
                                    }
                                 }
                                  $temp="";
                                  $fileName = "";
                                  $temp= "CRM".$_SESSION['lastID']."A$$$";
                                  $target_path = "uploads/";
                                  $target_path = $target_path.$temp . basename( $_FILES['userfile']['name']);
                                  if($_FILES['userfile']['name']) $fileName= $temp.$_FILES['userfile']['name'];

                                  if (file_exists("uploads/".$fileName)){
                                   echo "<center><STRONG>Sorry!!" .$fileName . " already exists.</center></STRONG>";
                                  }else{
                                   move_uploaded_file($_FILES['userfile']['tmp_name'], $target_path);
                                  }
                                  if(basename($_FILES['userfile']['name'])){
                                  $SQL1 = "UPDATE ".$_SESSION['CurrentObjectName']->TableName." SET docname='".$fileName."' where id=".$_SESSION['lastID'];
                                  mysqli_query($con,$SQL1) or die(mysqli_error()."PA-115<br>".$SQL1);
                                  }
                                  
                                  $temp="";
                                  $fileName = "";
                                  $temp= "CRM".$_SESSION['lastID']."B$$$";
                                  $target_path = "uploads/";
                                  $target_path = $target_path.$temp . basename( $_FILES['userfile1']['name']);
                                  if($_FILES['userfile1']['name']) $fileName= $temp.$_FILES['userfile1']['name'];

                                  if (file_exists("uploads/".$fileName)){
                                   echo "<center><STRONG>Sorry!!" .$fileName . " already exists.</center></STRONG>";
                                  }else{
                                   move_uploaded_file($_FILES['userfile1']['tmp_name'], $target_path);
                                  }
                                  if(basename($_FILES['userfile1']['name'])){
                                  $SQL1 = "UPDATE ".$_SESSION['CurrentObjectName']->TableName." SET proposaldoc='".$fileName."' where id=".$_SESSION['lastID'];
                                  mysqli_query($con,$SQL1) or die(mysqli_error()."PA-115<br>".$SQL1);
                                  }

                                  $temp="";
                                  $fileName = "";
                                  $temp= "CRM".$_SESSION['lastID']."C$$$";
                                  $target_path = "uploads/";
                                  $target_path = $target_path.$temp . basename( $_FILES['userfile2']['name']);
                                  if($_FILES['userfile2']['name']) $fileName= $temp.$_FILES['userfile2']['name'];

                                  if (file_exists("uploads/".$fileName)){
                                   echo "<center><STRONG>Sorry!!" .$fileName . " already exists.</center></STRONG>";
                                  }else{
                                   move_uploaded_file($_FILES['userfile2']['tmp_name'], $target_path);
                                  }
                                  if(basename($_FILES['userfile2']['name'])){
                                  $SQL1 = "UPDATE ".$_SESSION['CurrentObjectName']->TableName." SET vatdocname='".$fileName."' where id=".$_SESSION['lastID'];
                                  mysqli_query($con,$SQL1) or die(mysqli_error()."PA-115<br>".$SQL1);
                                  }


                                  if($_REQUEST['action']=='save'){
                                   if($formname=="salesorderheadlist.php")header("location:editsalesorderheadlist.php?txtsearch=".$_REQUEST['searchvalue']."&frmPage_rowcount==".$_REQUEST['frmPage_rowcount']."&dr=edit&ID=".$_SESSION['lastID']."");
                                 }elseif($_REQUEST['action']=='savenew'){
                                   if($formname=="salesorderheadlist.php")header("location:editsalesorderheadlist.php?txtsearch=".$_REQUEST['searchvalue']."&frmPage_rowcount==".$_REQUEST['frmPage_rowcount']."&dr=add&ID=0");
                                 }
                                 elseif($_REQUEST['action']=='saveclose'){
                                   if($formname=="salesorderheadlist.php")header("location:salesorderheadlist.php");
                                 }
        }
        if($formname=="crmteam.php"){

            for($ii=0 ;$ii < count($RequestBuffer['ckk_A_assignedto']); $ii++){
               $SQL   = "SELECT username from in_user where userid='".$RequestBuffer['ckk_A_assignedto'][$ii]."'";
               $SQLRes =  mysqli_query($con,$SQL) or die(mysqli_error()."<br>".$SQL);
               if(mysqli_num_rows($SQLRes)>=1){
                  while($loginResultArray   = mysqli_fetch_array($SQLRes)){
                        $username .= $loginResultArray['username'].",";
                  }
               }
            }
            $username = substr($username,0,strlen($username)-1) ;
            $SQL   = "update in_crmteam set nameassignedto='$username' where id='".$_SESSION['lastID']."'";
            $SQLRes =  mysqli_query($con,$SQL) or die(mysqli_error()."<br>".$SQL);

            $totalsum=0;
            for($ii=1 ;$ii <=12; $ii++){
                $totalsum += $RequestBuffer['txt_A_'."_".$ii];
            }
            $SQL   = "update in_crmteam set yearlytarget='$totalsum' where id='".$_SESSION['lastID']."'";
            $SQLRes =  mysqli_query($con,$SQL) or die(mysqli_error()."<br>".$SQL);

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
                  $RES = mysqli_query($con,$SEL);
                  if(mysqli_num_rows($RES)>=1){
                  $ARR = mysqli_fetch_array($RES);
                       $empcategory = $ARR['empcategory'];
                  }
                  $SQL   = "select factor from in_kpientry where in_kpientry.kpistafftype='$empcategory' order by serialnumber";
                  $SQLRes =  mysqli_query($con,$SQL) or die(mysqli_error()."<br>".$SQL);
                  if(mysqli_num_rows($SQLRes)>=1){
                     while($loginResultArray   = mysqli_fetch_array($SQLRes)){

                        $seqNumber1 = GetLastSqeID("e_appraisalfactors");
                        $SQL1 = "insert into e_appraisalfactors(id,appraisalid,factors) values('$seqNumber1','".$_SESSION['lastID']."','".$loginResultArray['factor']."')";
                        mysqli_query($con,$SQL1) or die(mysqli_error()."PA-115<br>".$SQL1);
                     }
                  }
        }
        if($formname=="profitcenterlist.php"){
           if($RequestBuffer['txd_A_actenddate']!="" && $RequestBuffer['txd_A_actenddate']!="00-00-0000"){
                  $enddate = date("Y-m-d",strtotime($RequestBuffer['txd_A_actenddate']));
                  $months = $RequestBuffer['txt_A_liabilityperiod'];
                  if($months=="")$months=0;
                  $Duedate = date('Y-m-d', strtotime("+".$months." months", strtotime($enddate)));

                  $squpdateSQL = "UPDATE t_activitycenter SET retreleasedate='$Duedate' WHERE id='".$_SESSION['lastID']."'";
                  mysqli_query($con,$squpdateSQL) or die(mysqli_error()."<br>".$squpdateSQL);
           }else{
                  $squpdateSQL = "UPDATE t_activitycenter SET retreleasedate='' WHERE id='".$_SESSION['lastID']."'";
                  mysqli_query($con,$squpdateSQL) or die(mysqli_error()."<br>".$squpdateSQL);
           }
           $squpdateSQL = "UPDATE t_activitycenter SET projectstore='66003' WHERE projectstore=''";
           mysqli_query($con,$squpdateSQL) or die(mysqli_error()."<br>".$squpdateSQL);
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
                                      mysqli_query($con,$SQL1) or die(mysqli_error()."PA-115<br>".$SQL1);

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
                                      mysqli_query($con,$SQL1) or die(mysqli_error()."PA-115<br>".$SQL1);

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
                                  mysqli_query($con,$SQL1) or die(mysqli_error()."PA-115<br>".$SQL1);
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
                                      mysqli_query($con,$SQL1) or die(mysqli_error()."PA-115<br>".$SQL1);
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
                                      mysqli_query($con,$SQL1) or die(mysqli_error()."PA-115<br>".$SQL1);
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
                                      mysqli_query($con,$SQL1) or die(mysqli_error()."PA-115<br>".$SQL1);
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
                                      mysqli_query($con,$SQL1) or die(mysqli_error()."PA-115<br>".$SQL1);
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
            $SQLRes =  mysqli_query($con,$SQL) or die(mysqli_error()."<br>".$SQL);

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
               $SQLRes =  mysqli_query($con,$SQL) or die(mysqli_error()."<br>".$SQL);
               if(mysqli_num_rows($SQLRes)>=1){
                  while($loginResultArray   = mysqli_fetch_array($SQLRes)){
                        $custercompany .= $loginResultArray['jobname'].",";
                  }
               }
            }
            $custercompany = substr($custercompany,0,strlen($custercompany)-1) ;
            $SQL   = "update in_companycluster set companyname='$custercompany' where id='".$_SESSION['lastID']."'";
            $SQLRes =  mysqli_query($con,$SQL) or die(mysqli_error()."<br>".$SQL);
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
            mysqli_query($con,$squpdateSQL) or die(mysqli_error()."<br>".$squpdateSQL);
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
            mysqli_query($con,$squpdateSQL) or die(mysqli_error()."<br>".$squpdateSQL);
         }

         if($formname=="lookup.php"){
            $squpdateSQL = "UPDATE in_sequencer SET LASTNUMBER=LASTNUMBER+1 WHERE TABLENAME='looktypee'";
            mysqli_query($con,$squpdateSQL) or die(mysqli_error()."<br>".$squpdateSQL);
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
                  $SQLRes =  mysqli_query($con,$SQL) or die(mysqli_error()."<br>".$SQL);
                  if(mysqli_num_rows($SQLRes)>=1){
                   $loginResultArray   = mysqli_fetch_array($SQLRes);
                   $days= $loginResultArray['days']+1;
                  }
                        $SQL   = "SELECT LASTNUMBER AS LASTNUMBER  FROM in_sequencer WHERE TABLENAME='e_leave'";
                        $SQLRes =  mysqli_query($con,$SQL) or die(mysqli_error()."<br>".$SQL);
                        if(mysqli_num_rows($SQLRes)>=1){
                          while($loginResultArray   = mysqli_fetch_array($SQLRes)){
                             $instructionid=$loginResultArray['LASTNUMBER']+1;
                          }
                        }

                $SQL2 = "UPDATE e_leave SET daysallowed=".$days." where id=".$_SESSION['lastID'];
                mysqli_query($con,$SQL2) or die(mysqli_error()."PA-115<br>".$SQL2);



                $squpdateSQL = "UPDATE in_sequencer SET LASTNUMBER=".$instructionid." WHERE TABLENAME='e_leave'";
                mysqli_query($con,$squpdateSQL) or die(mysqli_error()."<br>".$squpdateSQL);

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
                                  mysqli_query($con,$SQL1) or die(mysqli_error()."PA-115<br>".$SQL1);
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
                                  mysqli_query($con,$SQL1) or die(mysqli_error()."PA-115<br>".$SQL1);
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
                                  mysqli_query($con,$SQL1) or die(mysqli_error()."PA-115<br>".$SQL1);
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
            mysqli_query($con,$squpdateSQL) or die(mysqli_error()."<br>".$squpdateSQL);



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
                                  mysqli_query($con,$SQL1) or die(mysqli_error()."PA-115<br>".$SQL1);
                                 }

                                 //$squpdateSQL = "UPDATE in_personalinfo SET rolecode=concat('EMPLOYEE,',rolecode) WHERE id=".$_SESSION['lastID'];
                                 //mysqli_query($con,$squpdateSQL) or die(mysqli_error()."<br>".$squpdateSQL);

                                 $SQL   = "SELECT rolecode FROM in_personalinfo WHERE id=".$_SESSION['lastID'];
                                 $SQLRes =  mysqli_query($con,$SQL) or die(mysqli_error()."<br>".$SQL);
                                 if(mysqli_num_rows($SQLRes)>=1){
                                   while($loginResultArray   = mysqli_fetch_array($SQLRes)){
                                    $savedrole=$loginResultArray['rolecode'];
                                   }
                                 }
                                 $seqID = GetLastSqeID("in_user");
                                 $insAccountSQL = "INSERT INTO in_user(ID,userid,username,rolecode,acclocationcode,pwd,email,status)
                                                   VALUES('$seqID','".$RequestBuffer['txt_A_empid']."','".$_REQUEST['txt_A_empfirstename']." ".$RequestBuffer['txt_A_emplastename']."','".$savedrole."','".$RequestBuffer['cmb_A_empcompany']."','123','".$RequestBuffer['txt_A_empworkemail']."','ACTIVE')";
                                 mysqli_query($con,$insAccountSQL) or die(mysqli_error()."<br>".$insAccountSQL);

                                 $squpdateSQL1 = "UPDATE in_personalinfo SET empreportingofficer='0' WHERE id='".$_SESSION['lastID']."' and empdesignation='17001'";
                                 mysqli_query($con,$squpdateSQL1) or die(mysqli_error()."<br>".$squpdateSQL1);

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
            mysqli_query($con,$squpdateSQL) or die(mysqli_error()."<br>".$squpdateSQL);

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
                                  mysqli_query($con,$SQL1) or die(mysqli_error()."PA-115<br>".$SQL1);
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
    	global $con;

          $formname = $_SESSION['CurrentObjectName']->formName;

          if($formname=="crmcontactlist.php"){
                                  $temp=""; $fileName = "";
                                  $temp= "CUSTOMER".$_REQUEST['mode']."A$$$";
                                  $target_path = "uploads/";
                                  $target_path = $target_path.$temp . basename( $_FILES['tradelicense']['name']);
                                  if($_FILES['tradelicense']['name']) $fileName= $temp.$_FILES['tradelicense']['name'];

                                  if (file_exists("uploads/".$fileName)){
                                   echo "<center><STRONG>Sorry!!" .$fileName . " already exists.</center></STRONG>";
                                  }else{
                                   move_uploaded_file($_FILES['tradelicense']['tmp_name'], $target_path);
                                  }
                                  if(basename($_FILES['tradelicense']['name'])){
                                  $SQL1 = "UPDATE ".$_SESSION['CurrentObjectName']->TableName." SET tradelicense='".$fileName."' where id=".$_REQUEST['mode'];
                                  mysqli_query($con,$SQL1) or die(mysqli_error()."PA-115<br>".$SQL1);
                                  }
                                  
                                  $temp=""; $fileName = "";
                                  $temp= "CUSTOMER".$_REQUEST['mode']."B$$$";
                                  $target_path = "uploads/";
                                  $target_path = $target_path.$temp . basename( $_FILES['vatupload']['name']);
                                  if($_FILES['vatupload']['name']) $fileName= $temp.$_FILES['vatupload']['name'];

                                  if (file_exists("uploads/".$fileName)){
                                   echo "<center><STRONG>Sorry!!" .$fileName . " already exists.</center></STRONG>";
                                  }else{
                                   move_uploaded_file($_FILES['vatupload']['tmp_name'], $target_path);
                                  }
                                  if(basename($_FILES['vatupload']['name'])){
                                  $SQL1 = "UPDATE ".$_SESSION['CurrentObjectName']->TableName." SET vatupload='".$fileName."' where id=".$_REQUEST['mode'];
                                  mysqli_query($con,$SQL1) or die(mysqli_error()."PA-115<br>".$SQL1);
                                  }
                        if($_REQUEST['action']=='save'){
            	        if($formname=="crmcontactlist.php")header("location:editcrmcontactlist.php?txtsearch=".$_REQUEST['searchvalue']."&frmPage_rowcount==".$_REQUEST['frmPage_rowcount']."&dr=edit&ID=".$_REQUEST['mode']."");
        	            }elseif($_REQUEST['action']=='savenew'){
                        if($formname=="crmcontactlist.php")header("location:editcrmcontactlist.php?txtsearch=".$_REQUEST['searchvalue']."&frmPage_rowcount==".$_REQUEST['frmPage_rowcount']."&dr=add&ID=0");
                        }
                        elseif($_REQUEST['action']=='saveclose'){
                        if($formname=="crmcontactlist.php")header("location:crmcontactlist.php");
                         }
                                  
                                  
                                  
                                  

        }
        if($formname=="buildingmaster.php"){
                                  $temp=""; $fileName = "";
                                  $temp= "BUILDING".$_REQUEST['mode']."A$$$";
                                  $target_path = "building/";
                                  $target_path = $target_path.$temp . basename( $_FILES['docname']['name']);
                                  if($_FILES['docname']['name']) $fileName= $temp.$_FILES['docname']['name'];

                                  if (file_exists("uploads/".$fileName)){
                                   echo "<center><STRONG>Sorry!!" .$fileName . " already exists.</center></STRONG>";
                                  }else{
                                   move_uploaded_file($_FILES['docname']['tmp_name'], $target_path);
                                  }
                                  if(basename($_FILES['docname']['name'])){
                                  $SQL1 = "UPDATE ".$_SESSION['CurrentObjectName']->TableName." SET docname='".$fileName."' where id=".$_REQUEST['mode'];
                                  mysqli_query($con,$SQL1) or die(mysqli_error()."PA-115<br>".$SQL1);
                                  }


                        if($_REQUEST['action']=='save'){
            	        if($formname=="buildingmaster.php")header("location:editbuildingmaster.php?txtsearch=".$_REQUEST['searchvalue']."&frmPage_rowcount==".$_REQUEST['frmPage_rowcount']."&dr=edit&ID=".$_REQUEST['mode']."");
        	            }elseif($_REQUEST['action']=='savenew'){
                        if($formname=="buildingmaster.php")header("location:editbuildingmaster.php?txtsearch=".$_REQUEST['searchvalue']."&frmPage_rowcount==".$_REQUEST['frmPage_rowcount']."&dr=add&ID=0");
                        }
                        elseif($_REQUEST['action']=='saveclose'){
                        if($formname=="buildingmaster.php")header("location:buildingmaster.php");
                         }





        }


          
          
          
          
          
          
          if($formname=="forwardleadlist.php"){
                                if($_REQUEST['action']=='save'){
                                   if($formname=="forwardleadlist.php")header("location:editforwardleadlist.php?txtsearch=".$_REQUEST['searchvalue']."&frmPage_rowcount==".$_REQUEST['frmPage_rowcount']."&dr=edit&ID=".$_REQUEST['mode']."");
                                 }elseif($_REQUEST['action']=='savenew'){
                                   if($formname=="forwardleadlist.php")header("location:editforwardleadlist.php?txtsearch=".$_REQUEST['searchvalue']."&frmPage_rowcount==".$_REQUEST['frmPage_rowcount']."&dr=add&ID=0");
                                 }
                                 elseif($_REQUEST['action']=='saveclose'){
                                   if($formname=="forwardleadlist.php")header("location:forwardleadlist.php");
                                 }
        }
########################################
if($formname=="supplierpaymentdetails.php"){

                                  $temp=""; $fileName = "";
                                  $temp= "SUPPLIER".$_REQUEST['mode']."$$$";
                                  $target_path = "uploads/";
                                  $target_path = $target_path.$temp . basename( $_FILES['creditupload']['name']);
                                  if($_FILES['creditupload']['name']) $fileName= $temp.$_FILES['creditupload']['name'];

                                  if (file_exists("uploads/".$fileName)){
                                   echo "<center><STRONG>Sorry!!" .$fileName . " already exists.</center></STRONG>";
                                  }else{
                                   move_uploaded_file($_FILES['creditupload']['tmp_name'], $target_path);
                                  }
                                  if(basename($_FILES['creditupload']['name'])){
                                  $SQL1 = "UPDATE ".$_SESSION['CurrentObjectName']->TableName." SET creditupload='".$fileName."' where id=".$_REQUEST['mode'];
                                  mysqli_query($con,$SQL1) or die(mysqli_error()."PA-115<br>".$SQL1);
                                  }

                                if($formname=="supplierpaymentdetails.php")header("location:supplierpaymentdetails.php?INITEMID=".$_REQUEST['mode']."&page_mode=".$_REQUEST['page_mode']);

         }        
########################################
if($formname=="otsopaymentterms.php"){

                                  $temp=""; $fileName = "";
                                  $temp= "CRM".$_REQUEST['mode']."E$$$";
                                  $target_path = "uploads/";
                                  $target_path = $target_path.$temp . basename( $_FILES['receiptupload']['name']);
                                  if($_FILES['receiptupload']['name']) $fileName= $temp.$_FILES['receiptupload']['name'];

                                  if (file_exists("uploads/".$fileName)){
                                   echo "<center><STRONG>Sorry!!" .$fileName . " already exists.</center></STRONG>";
                                  }else{
                                   move_uploaded_file($_FILES['receiptupload']['tmp_name'], $target_path);
                                  }
                                  if(basename($_FILES['receiptupload']['name'])){
                                  $SQL1 = "UPDATE ".$_SESSION['CurrentObjectName']->TableName." SET receiptupload='".$fileName."' where id=".$_REQUEST['mode'];
                                  mysqli_query($con,$SQL1) or die(mysqli_error()."PA-115<br>".$SQL1);
                                  }

                                  $temp="";  $fileName = "";
                                  $temp= "CRM".$_REQUEST['mode']."F$$$";
                                  $target_path = "uploads/";
                                  $target_path = $target_path.$temp . basename( $_FILES['invoiceupload']['name']);
                                  if($_FILES['invoiceupload']['name']) $fileName= $temp.$_FILES['invoiceupload']['name'];

                                  if (file_exists("uploads/".$fileName)){
                                   echo "<center><STRONG>Sorry!!" .$fileName . " already exists.</center></STRONG>";
                                  }else{
                                   move_uploaded_file($_FILES['invoiceupload']['tmp_name'], $target_path);
                                  }
                                  if(basename($_FILES['invoiceupload']['name'])){
                                  $SQL1 = "UPDATE ".$_SESSION['CurrentObjectName']->TableName." SET invoiceupload='".$fileName."' where id=".$_REQUEST['mode'];
                                  mysqli_query($con,$SQL1) or die(mysqli_error()."PA-115<br>".$SQL1);
                                  }
                                  
                                  $temp=""; $fileName = "";
                                  $temp= "CRM".$_REQUEST['mode']."G$$$";
                                  $target_path = "uploads/";
                                  $target_path = $target_path.$temp . basename( $_FILES['advinvoiceupload']['name']);
                                  if($_FILES['advinvoiceupload']['name']) $fileName= $temp.$_FILES['advinvoiceupload']['name'];

                                  if (file_exists("uploads/".$fileName)){
                                   echo "<center><STRONG>Sorry!!" .$fileName . " already exists.</center></STRONG>";
                                  }else{
                                   move_uploaded_file($_FILES['advinvoiceupload']['tmp_name'], $target_path);
                                  }
                                  if(basename($_FILES['advinvoiceupload']['name'])){
                                  $SQL1 = "UPDATE ".$_SESSION['CurrentObjectName']->TableName." SET advinvoiceupload='".$fileName."' where id=".$_REQUEST['mode'];
                                  mysqli_query($con,$SQL1) or die(mysqli_error()."PA-115<br>".$SQL1);
                                  }

                                  $temp="";  $fileName = "";
                                  $temp= "CRM".$_REQUEST['mode']."H$$$";
                                  $target_path = "uploads/";
                                  $target_path = $target_path.$temp . basename( $_FILES['advreceiptupload']['name']);
                                  if($_FILES['advreceiptupload']['name']) $fileName= $temp.$_FILES['advreceiptupload']['name'];

                                  if (file_exists("uploads/".$fileName)){
                                   echo "<center><STRONG>Sorry!!" .$fileName . " already exists.</center></STRONG>";
                                  }else{
                                   move_uploaded_file($_FILES['advreceiptupload']['tmp_name'], $target_path);
                                  }
                                  if(basename($_FILES['advreceiptupload']['name'])){
                                  $SQL1 = "UPDATE ".$_SESSION['CurrentObjectName']->TableName." SET advreceiptupload='".$fileName."' where id=".$_REQUEST['mode'];
                                  mysqli_query($con,$SQL1) or die(mysqli_error()."PA-115<br>".$SQL1);
                                  }

                                  /// end snormal upload
          for($i=0 ;$i < $_REQUEST['selectslots']; $i++){
                                    $imgName = "";
                                    if($i==0){

                                      $target_path = "uploads/";
                                      $temp = "CRM".$_REQUEST['mode']."I$$$";
                                      $target_path = $target_path .$temp. basename( $_FILES['balreceiptupload']['name']);

                                      if($_FILES['balreceiptupload']['name']) $imgName= $temp.$_FILES['balreceiptupload']['name'];

                                       move_uploaded_file($_FILES['balreceiptupload']['tmp_name'], $target_path);
                                      if($imgName!="") {
                                      $SQL1 = "UPDATE ".$_SESSION['CurrentObjectName']->TableName." SET balreceiptupload='".$imgName."' where id=".$_REQUEST['mode'];
                                      mysqli_query($con,$SQL1) or die(mysqli_error()."PA-115<br>".$SQL1);
                                      }

                                    }else{
                                      $field='balreceiptupload'.$i;
                                      $target_path = "uploads/";
                                      $temp = "CRM".$_REQUEST['mode']."I$$$";
                                      $target_path = $target_path .$temp. basename( $_FILES[$field]['name']);

                                      if($_FILES[$field]['name']) $imgName= $temp.$_FILES[$field]['name'];
                                       move_uploaded_file($_FILES[$field]['tmp_name'], $target_path);
                                      if($imgName!="") {
                                      $SQL1 = "UPDATE ".$_SESSION['CurrentObjectName']->TableName." SET balreceiptupload$i='".$imgName."' where id=".$_REQUEST['mode'];
                                      mysqli_query($con,$SQL1) or die(mysqli_error()."PA-115<br>".$SQL1);
                                      }

                                    }
                                }

                   for($i=0 ;$i < $_REQUEST['selectslots2']; $i++){
                                    $imgName = "";
                                    if($i==0){

                                      $target_path = "uploads/";
                                      $temp = "CRM".$_REQUEST['mode']."J$$$";
                                      $target_path = $target_path .$temp. basename( $_FILES['balinvoiceupload']['name']);

                                      if($_FILES['balinvoiceupload']['name']) $imgName= $temp.$_FILES['balinvoiceupload']['name'];

                                       move_uploaded_file($_FILES['balinvoiceupload']['tmp_name'], $target_path);
                                      if($imgName!="") {
                                      $SQL1 = "UPDATE ".$_SESSION['CurrentObjectName']->TableName." SET balinvoiceupload='".$imgName."' where id=".$_REQUEST['mode'];
                                      mysqli_query($con,$SQL1) or die(mysqli_error()."PA-115<br>".$SQL1);
                                      }
                                    }else{
                                      $field='balinvoiceupload'.$i;
                                      $target_path = "uploads/";
                                      $temp = "CRM".$_REQUEST['mode']."J$$$";
                                      $target_path = $target_path .$temp. basename( $_FILES[$field]['name']);

                                      if($_FILES[$field]['name']) $imgName= $temp.$_FILES[$field]['name'];


                                       move_uploaded_file($_FILES[$field]['tmp_name'], $target_path);
                                      if($imgName!="") {
                                      $SQL1 = "UPDATE ".$_SESSION['CurrentObjectName']->TableName." SET balinvoiceupload$i='".$imgName."' where id=".$_REQUEST['mode'];
                                      mysqli_query($con,$SQL1) or die(mysqli_error()."PA-115<br>".$SQL1);
                                      }
                                    }
                                }
                                // end of adv uploads
             if($formname=="otsopaymentterms.php")header("location:otsopaymentterms.php?INITEMID=".$_REQUEST['mode']."");

         }
         
################################################################
if($formname=="otjobpaymentterms.php"){

                                  $temp=""; $fileName = "";
                                  $temp= "CRMJ".$_REQUEST['mode']."E$$$";
                                  $target_path = "uploads/";
                                  $target_path = $target_path.$temp . basename( $_FILES['receiptupload']['name']);
                                  if($_FILES['receiptupload']['name']) $fileName= $temp.$_FILES['receiptupload']['name'];

                                  if (file_exists("uploads/".$fileName)){
                                   echo "<center><STRONG>Sorry!!" .$fileName . " already exists.</center></STRONG>";
                                  }else{
                                   move_uploaded_file($_FILES['receiptupload']['tmp_name'], $target_path);
                                  }
                                  if(basename($_FILES['receiptupload']['name'])){
                                  $SQL1 = "UPDATE ".$_SESSION['CurrentObjectName']->TableName." SET receiptupload='".$fileName."' where id=".$_REQUEST['mode'];
                                  mysqli_query($con,$SQL1) or die(mysqli_error()."PA-115<br>".$SQL1);
                                  }

                                  $temp="";  $fileName = "";
                                  $temp= "CRMJ".$_REQUEST['mode']."F$$$";
                                  $target_path = "uploads/";
                                  $target_path = $target_path.$temp . basename( $_FILES['invoiceupload']['name']);
                                  if($_FILES['invoiceupload']['name']) $fileName= $temp.$_FILES['invoiceupload']['name'];

                                  if (file_exists("uploads/".$fileName)){
                                   echo "<center><STRONG>Sorry!!" .$fileName . " already exists.</center></STRONG>";
                                  }else{
                                   move_uploaded_file($_FILES['invoiceupload']['tmp_name'], $target_path);
                                  }
                                  if(basename($_FILES['invoiceupload']['name'])){
                                  $SQL1 = "UPDATE ".$_SESSION['CurrentObjectName']->TableName." SET invoiceupload='".$fileName."' where id=".$_REQUEST['mode'];
                                  mysqli_query($con,$SQL1) or die(mysqli_error()."PA-115<br>".$SQL1);
                                  }

                                  $temp=""; $fileName = "";
                                  $temp= "CRM".$_REQUEST['mode']."G$$$";
                                  $target_path = "uploads/";
                                  $target_path = $target_path.$temp . basename( $_FILES['advinvoiceupload']['name']);
                                  if($_FILES['advinvoiceupload']['name']) $fileName= $temp.$_FILES['advinvoiceupload']['name'];

                                  if (file_exists("uploads/".$fileName)){
                                   echo "<center><STRONG>Sorry!!" .$fileName . " already exists.</center></STRONG>";
                                  }else{
                                   move_uploaded_file($_FILES['advinvoiceupload']['tmp_name'], $target_path);
                                  }
                                  if(basename($_FILES['advinvoiceupload']['name'])){
                                  $SQL1 = "UPDATE ".$_SESSION['CurrentObjectName']->TableName." SET advinvoiceupload='".$fileName."' where id=".$_REQUEST['mode'];
                                  mysqli_query($con,$SQL1) or die(mysqli_error()."PA-115<br>".$SQL1);
                                  }

                                  $temp="";  $fileName = "";
                                  $temp= "CRMJ".$_REQUEST['mode']."H$$$";
                                  $target_path = "uploads/";
                                  $target_path = $target_path.$temp . basename( $_FILES['advreceiptupload']['name']);
                                  if($_FILES['advreceiptupload']['name']) $fileName= $temp.$_FILES['advreceiptupload']['name'];

                                  if (file_exists("uploads/".$fileName)){
                                   echo "<center><STRONG>Sorry!!" .$fileName . " already exists.</center></STRONG>";
                                  }else{
                                   move_uploaded_file($_FILES['advreceiptupload']['tmp_name'], $target_path);
                                  }
                                  if(basename($_FILES['advreceiptupload']['name'])){
                                  $SQL1 = "UPDATE ".$_SESSION['CurrentObjectName']->TableName." SET advreceiptupload='".$fileName."' where id=".$_REQUEST['mode'];
                                  mysqli_query($con,$SQL1) or die(mysqli_error()."PA-115<br>".$SQL1);
                                  }

                                  /// end snormal upload
          for($i=0 ;$i < $_REQUEST['selectslots']; $i++){
                                    $imgName = "";
                                    if($i==0){

                                      $target_path = "uploads/";
                                      $temp = "CRMJ".$_REQUEST['mode']."I$$$";
                                      $target_path = $target_path .$temp. basename( $_FILES['balreceiptupload']['name']);

                                      if($_FILES['balreceiptupload']['name']) $imgName= $temp.$_FILES['balreceiptupload']['name'];

                                       move_uploaded_file($_FILES['balreceiptupload']['tmp_name'], $target_path);
                                      if($imgName!="") {
                                      $SQL1 = "UPDATE ".$_SESSION['CurrentObjectName']->TableName." SET balreceiptupload='".$imgName."' where id=".$_REQUEST['mode'];
                                      mysqli_query($con,$SQL1) or die(mysqli_error()."PA-115<br>".$SQL1);
                                      }

                                    }else{
                                      $field='balreceiptupload'.$i;
                                      $target_path = "uploads/";
                                      $temp = "CRMJ".$_REQUEST['mode']."I$$$";
                                      $target_path = $target_path .$temp. basename( $_FILES[$field]['name']);

                                      if($_FILES[$field]['name']) $imgName= $temp.$_FILES[$field]['name'];
                                       move_uploaded_file($_FILES[$field]['tmp_name'], $target_path);
                                      if($imgName!="") {
                                      $SQL1 = "UPDATE ".$_SESSION['CurrentObjectName']->TableName." SET balreceiptupload$i='".$imgName."' where id=".$_REQUEST['mode'];
                                      mysqli_query($con,$SQL1) or die(mysqli_error()."PA-115<br>".$SQL1);
                                      }

                                    }
                                }

                   for($i=0 ;$i < $_REQUEST['selectslots2']; $i++){
                                    $imgName = "";
                                    if($i==0){

                                      $target_path = "uploads/";
                                      $temp = "CRMJ".$_REQUEST['mode']."J$$$";
                                      $target_path = $target_path .$temp. basename( $_FILES['balinvoiceupload']['name']);

                                      if($_FILES['balinvoiceupload']['name']) $imgName= $temp.$_FILES['balinvoiceupload']['name'];

                                       move_uploaded_file($_FILES['balinvoiceupload']['tmp_name'], $target_path);
                                      if($imgName!="") {
                                      $SQL1 = "UPDATE ".$_SESSION['CurrentObjectName']->TableName." SET balinvoiceupload='".$imgName."' where id=".$_REQUEST['mode'];
                                      mysqli_query($con,$SQL1) or die(mysqli_error()."PA-115<br>".$SQL1);
                                      }
                                    }else{
                                      $field='balinvoiceupload'.$i;
                                      $target_path = "uploads/";
                                      $temp = "CRMJ".$_REQUEST['mode']."J$$$";
                                      $target_path = $target_path .$temp. basename( $_FILES[$field]['name']);

                                      if($_FILES[$field]['name']) $imgName= $temp.$_FILES[$field]['name'];


                                       move_uploaded_file($_FILES[$field]['tmp_name'], $target_path);
                                      if($imgName!="") {
                                      $SQL1 = "UPDATE ".$_SESSION['CurrentObjectName']->TableName." SET balinvoiceupload$i='".$imgName."' where id=".$_REQUEST['mode'];
                                      mysqli_query($con,$SQL1) or die(mysqli_error()."PA-115<br>".$SQL1);
                                      }
                                    }
                                }
                                // end of adv uploads
             if($formname=="otjobpaymentterms.php")header("location:otjobpaymentterms.php?INITEMID=".$_REQUEST['mode']."");

         }
###############################################################
 //////////////
        if($formname=="otquoteheadlist.php"){
        
                                  $charset="ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";
                                  $length=5;
                                  for($i=0; $i <=$length; $i++){
                                    $rand =rand() % strlen($charset);
                                    $temp=  substr($charset,$rand,3);
                                  }
                                  
                                  $temp="";
                                  $temp= "CRM".$_REQUEST['mode']."$$$";
                                  
                                  $target_path = "uploads/";
                                  $target_path = $target_path.$temp . basename( $_FILES['userfile']['name']);
                                  if($_FILES['userfile']['name']) $fileName= $temp.$_FILES['userfile']['name'];

                                  if (file_exists("uploads/".$fileName)){
                                      echo "<center><STRONG>Sorry!!" .$fileName . " already exists.</center></STRONG>";
                                  }else{
                                      move_uploaded_file($_FILES['userfile']['tmp_name'], $target_path);
                                  }
                                  if(basename($_FILES['userfile']['name'])){
                                     $SQL1 = "UPDATE ".$_SESSION['CurrentObjectName']->TableName." SET docname='".$fileName."' where id=".$_REQUEST['mode'];
                                     mysqli_query($con,$SQL1) or die(mysqli_error()."PA-115<br>".$SQL1);
                                  }

                                 if($_REQUEST['action']=='save'){
                                   if($formname=="otquoteheadlist.php")header("location:editotquoteheadlist.php?txtsearch=".$_REQUEST['searchvalue']."&frmPage_rowcount==".$_REQUEST['frmPage_rowcount']."&dr=edit&ID=".$_REQUEST['mode']."");
                                 }elseif($_REQUEST['action']=='savenew'){
                                   if($formname=="otquoteheadlist.php")header("location:editotquoteheadlist.php?txtsearch=".$_REQUEST['searchvalue']."&frmPage_rowcount==".$_REQUEST['frmPage_rowcount']."&dr=add&ID=0");
                                 }
                                 elseif($_REQUEST['action']=='saveclose'){
                                   if($formname=="otquoteheadlist.php")header("location:otquoteheadlist.php");
                                 }
          }// end of otquotation
          if($formname=="emgquoteheadlist.php"){

                                  $charset="ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";
                                  $length=5;
                                  for($i=0; $i <=$length; $i++){
                                    $rand =rand() % strlen($charset);
                                    $temp=  substr($charset,$rand,3);
                                  }

                                  $temp="";
                                  $temp= "CRM".$_REQUEST['mode']."$$$";

                                  $target_path = "uploads/";
                                  $target_path = $target_path.$temp . basename( $_FILES['userfile']['name']);
                                  if($_FILES['userfile']['name']) $fileName= $temp.$_FILES['userfile']['name'];

                                  if (file_exists("uploads/".$fileName)){
                                      echo "<center><STRONG>Sorry!!" .$fileName . " already exists.</center></STRONG>";
                                  }else{
                                      move_uploaded_file($_FILES['userfile']['tmp_name'], $target_path);
                                  }
                                  if(basename($_FILES['userfile']['name'])){
                                     $SQL1 = "UPDATE ".$_SESSION['CurrentObjectName']->TableName." SET docname='".$fileName."' where id=".$_REQUEST['mode'];
                                     mysqli_query($con,$SQL1) or die(mysqli_error()."PA-115<br>".$SQL1);
                                  }

                                 if($_REQUEST['action']=='save'){
                                   if($formname=="emgquoteheadlist.php")header("location:editemgquoteheadlist.php?txtsearch=".$_REQUEST['searchvalue']."&frmPage_rowcount==".$_REQUEST['frmPage_rowcount']."&dr=edit&ID=".$_REQUEST['mode']."");
                                 }elseif($_REQUEST['action']=='savenew'){
                                   if($formname=="emgquoteheadlist.php")header("location:editemgquoteheadlist.php?txtsearch=".$_REQUEST['searchvalue']."&frmPage_rowcount==".$_REQUEST['frmPage_rowcount']."&dr=add&ID=0");
                                 }
                                 elseif($_REQUEST['action']=='saveclose'){
                                   if($formname=="emgquoteheadlist.php")header("location:emgquoteheadlist.php");
                                 }
          }// end of otquotation
          if($formname=="quoteheadlist.php"){

                                /*  $charset="ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";
                                  $length=5;
                                  for($i=0; $i <=$length; $i++){
                                    $rand =rand() % strlen($charset);
                                    $temp=  substr($charset,$rand,3);
                                  }
                                  
                                  $temp="";
                                  $temp= "CRM_AMC_a".$_REQUEST['mode']."$$$";
                                  
                                  $target_path = "uploads/";
                                  $target_path = $target_path.$temp . basename( $_FILES['userfile']['name']);
                                  if($_FILES['userfile']['name']) $fileName= $temp.$_FILES['userfile']['name'];

                                  if (file_exists("uploads/".$fileName)){
                                      echo "<center><STRONG>Sorry!!" .$fileName . " already exists.</center></STRONG>";
                                  }else{
                                      move_uploaded_file($_FILES['userfile']['tmp_name'], $target_path);
                                  }
                                  if(basename($_FILES['userfile']['name'])){
                                     $SQL1 = "UPDATE ".$_SESSION['CurrentObjectName']->TableName." SET docname='".$fileName."' where id=".$_REQUEST['mode'];
                                     mysqli_query($con,$SQL1) or die(mysqli_error()."PA-115<br>".$SQL1);
                                  }*/
/*
                                  $charset="ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";
                                  $length=5;
                                  for($i=0; $i <=$length; $i++){
                                    $rand =rand() % strlen($charset);
                                    $temp=  substr($charset,$rand,3);
                                  }*/
                                  $temp="";
                                  $temp= "CRM_AMC_b".$_REQUEST['mode']."$$$";
                                  $target_path = "uploads/";
                                  $target_path = $target_path.$temp . basename( $_FILES['userfile1']['name']);
                                  if($_FILES['userfile1']['name']) $fileName= $temp.$_FILES['userfile1']['name'];

                                  if (file_exists("uploads/".$fileName)){
                                      echo "<center><STRONG>Sorry!!" .$fileName . " already exists.</center></STRONG>";
                                  }else{
                                      move_uploaded_file($_FILES['userfile1']['tmp_name'], $target_path);
                                  }
                                  if(basename($_FILES['userfile1']['name'])){
                                     $SQL1 = "UPDATE ".$_SESSION['CurrentObjectName']->TableName." SET proposaldoc='".$fileName."' where id=".$_REQUEST['mode'];
                                     mysqli_query($con,$SQL1) or die(mysqli_error()."PA-115<br>".$SQL1);
                                  }
/*
                                  $charset="ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";
                                  $length=5;
                                  for($i=0; $i <=$length; $i++){
                                    $rand =rand() % strlen($charset);
                                    $temp=  substr($charset,$rand,3);
                                  }
                                  $temp="";
                                  $temp= "CRM_AMC_c".$_REQUEST['mode']."$$$";
                                  $target_path = "uploads/";
                                  $target_path = $target_path.$temp . basename( $_FILES['userfile2']['name']);
                                  if($_FILES['userfile2']['name']) $fileName= $temp.$_FILES['userfile2']['name'];

                                  if (file_exists("uploads/".$fileName)){
                                      echo "<center><STRONG>Sorry!!" .$fileName . " already exists.</center></STRONG>";
                                  }else{
                                      move_uploaded_file($_FILES['userfile2']['tmp_name'], $target_path);
                                  }
                                  if(basename($_FILES['userfile2']['name'])){
                                     $SQL1 = "UPDATE ".$_SESSION['CurrentObjectName']->TableName." SET docname2='".$fileName."' where id=".$_REQUEST['mode'];
                                     mysqli_query($con,$SQL1) or die(mysqli_error()."PA-115<br>".$SQL1);
                                  }*/
                                  //// update startdate and enddate
                               /*  if($_REQUEST['txd_A_startdate']!="" && $_REQUEST['txd_A_startdate']!="00-00-0000"){
                                    if($_REQUEST['cmb_A_durationtype'] !="" && $_REQUEST['txt_A_durationnos']!=""){
                                       $startdate_arr = explode('-',$_REQUEST['txd_A_startdate']);
                                       $date = $startdate_arr[2]."-".$startdate_arr[1]."-".$startdate_arr[0];
                                       $duration = $_REQUEST['txt_A_durationnos'];
                                        if($_REQUEST['cmb_A_durationtype']=="Days"){
                                           $enddate = date("Y-m-d", strtotime($date. " + $duration days"));
                                       }
                                        else if($_REQUEST['cmb_A_durationtype']=="Months"){
                                           $enddate = date("Y-m-d", strtotime($date. " + $duration months"));
                                       }
                                       else  if($_REQUEST['cmb_A_durationtype']=="Years"){
                                           $enddate = $enddate = date("Y-m-d", strtotime($date. " + $duration years"));
                                       }
                                       $upsql ="update in_crmhead set enddate='$enddate' where id=".$_REQUEST['mode'];
                                       mysqli_query($con,$upsql);
                                    }
                                 }*/

                                 if($_REQUEST['action']=='save'){
                                   if($formname=="quoteheadlist.php")header("location:editquoteheadlist.php?txtsearch=".$_REQUEST['searchvalue']."&frmPage_rowcount==".$_REQUEST['frmPage_rowcount']."&dr=edit&ID=".$_REQUEST['mode']."");
                                 }elseif($_REQUEST['action']=='savenew'){
                                   if($formname=="quoteheadlist.php")header("location:editquoteheadlist.php?txtsearch=".$_REQUEST['searchvalue']."&frmPage_rowcount==".$_REQUEST['frmPage_rowcount']."&dr=add&ID=0");
                                 }
                                 elseif($_REQUEST['action']=='saveclose'){
                                   if($formname=="quoteheadlist.php")header("location:quoteheadlist.php");
                                 }
          }
          if($formname=="crmleadvisitlistcontact.php"){

                                  $charset="ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";
                                  $length=5;
                                  for($i=0; $i <=$length; $i++){
                                    $rand =rand() % strlen($charset);
                                    $temp=  substr($charset,$rand,3);
                                  }
                                  $target_path = "uploads/";
                                  $target_path = $target_path.$temp . basename( $_FILES['userfile']['name']);
                                  if($_FILES['userfile']['name']) $fileName= $temp.$_FILES['userfile']['name'];

                                  if (file_exists("uploads/".$fileName)){
                                   echo "<center><STRONG>Sorry!!" .$fileName . " already exists.</center></STRONG>";
                                  }else{
                                   move_uploaded_file($_FILES['userfile']['tmp_name'], $target_path);
                                  }
                                  if(basename($_FILES['userfile']['name'])){
                                  $SQL1 = "UPDATE ".$_SESSION['CurrentObjectName']->TableName." SET docname='".$fileName."' where id=".$_REQUEST['mode'];
                                  mysqli_query($con,$SQL1) or die(mysqli_error()."PA-115<br>".$SQL1);
                                 }
                                 if($_REQUEST['action']=='save'){
                                   if($formname=="crmleadvisitlistcontact.php")header("location:editcrmleadvisitlistcontact.php?dr=edit&ID=".$_REQUEST['mode']."");
                                 }elseif($_REQUEST['action']=='savenew'){
                                   if($formname=="crmleadvisitlistcontact.php")header("location:editcrmleadvisitlistcontact.php?dr=add&ID=0");
                                 }
                                 elseif($_REQUEST['action']=='saveclose'){
                                   if($formname=="crmleadvisitlistcontact.php")header("location:crmleadvisitlistcontact.php");
                                 }
         }
         if($formname=="salesorderheadlist.php"){

                                 // update startdate and enddate
                                 if($_REQUEST['txd_A_startdate']!="" && $_REQUEST['txd_A_startdate']!="00-00-0000"){
                                    if($_REQUEST['cmb_A_durationtype'] !="" && $_REQUEST['txt_A_durationnos']!=""){
                                       $startdate_arr = explode('-',$_REQUEST['txd_A_startdate']);
                                       $date = $startdate_arr[2]."-".$startdate_arr[1]."-".$startdate_arr[0];
                                       $duration = $_REQUEST['txt_A_durationnos'];
                                        if($_REQUEST['cmb_A_durationtype']=="Days"){
                                           $enddate = date("Y-m-d", strtotime($date. " + $duration days"));
                                       }
                                        else if($_REQUEST['cmb_A_durationtype']=="Months"){
                                           $enddate = date("Y-m-d", strtotime($date. " + $duration months"));
                                       }
                                       else  if($_REQUEST['cmb_A_durationtype']=="Years"){
                                           $enddate = date("Y-m-d", strtotime($date. " + $duration years"));
                                       }
                                       $enddate = date("Y-m-d", strtotime($enddate. " -1 day"));
                                       $upsql ="update in_crmhead set enddate='$enddate' where id=".$_REQUEST['mode'];
                                       mysqli_query($con,$upsql);
                                    }
                                 }

                                  $temp="";
                                  $temp= "CRM".$_REQUEST['mode']."A$$$";
                                  $target_path = "uploads/";
                                  $target_path = $target_path.$temp . basename( $_FILES['userfile']['name']);
                                  if($_FILES['userfile']['name']) $fileName= $temp.$_FILES['userfile']['name'];

                                  if (file_exists("uploads/".$fileName)){
                                   echo "<center><STRONG>Sorry!!" .$fileName . " already exists.</center></STRONG>";
                                  }else{
                                   move_uploaded_file($_FILES['userfile']['tmp_name'], $target_path);
                                  }
                                  if(basename($_FILES['userfile']['name'])){
                                  $SQL1 = "UPDATE ".$_SESSION['CurrentObjectName']->TableName." SET docname1='".$fileName."' where id=".$_REQUEST['mode'];
                                  mysqli_query($con,$SQL1) or die(mysqli_error()."PA-115<br>".$SQL1);
                                  }

                                  $temp="";
                                  $temp= "CRM".$_REQUEST['mode']."B$$$";
                                  $target_path = "uploads/";
                                  $target_path = $target_path.$temp . basename( $_FILES['userfile1']['name']);
                                  if($_FILES['userfile1']['name']) $fileName= $temp.$_FILES['userfile1']['name'];

                                  if (file_exists("uploads/".$fileName)){
                                   echo "<center><STRONG>Sorry!!" .$fileName . " already exists.</center></STRONG>";
                                  }else{
                                   move_uploaded_file($_FILES['userfile1']['tmp_name'], $target_path);
                                  }
                                  if(basename($_FILES['userfile1']['name'])){
                                  $SQL1 = "UPDATE ".$_SESSION['CurrentObjectName']->TableName." SET proposaldoc='".$fileName."' where id=".$_REQUEST['mode'];
                                  mysqli_query($con,$SQL1) or die(mysqli_error()."PA-115<br>".$SQL1);
                                  }
                                  
                                  $temp="";
                                  $temp= "CRM".$_REQUEST['mode']."C$$$";
                                  $target_path = "uploads/";
                                  $target_path = $target_path.$temp . basename( $_FILES['userfile2']['name']);
                                  if($_FILES['userfile2']['name']) $fileName= $temp.$_FILES['userfile2']['name'];

                                  if (file_exists("uploads/".$fileName)){
                                   echo "<center><STRONG>Sorry!!" .$fileName . " already exists.</center></STRONG>";
                                  }else{
                                   move_uploaded_file($_FILES['userfile2']['tmp_name'], $target_path);
                                  }
                                  if(basename($_FILES['userfile2']['name'])){
                                  $SQL1 = "UPDATE ".$_SESSION['CurrentObjectName']->TableName." SET vatdocname='".$fileName."' where id=".$_REQUEST['mode'];
                                  mysqli_query($con,$SQL1) or die(mysqli_error()."PA-115<br>".$SQL1);
                                  }


                                 if($_REQUEST['action']=='save'){
                                   if($formname=="salesorderheadlist.php")header("location:editsalesorderheadlist.php?txtsearch=".$_REQUEST['searchvalue']."&frmPage_rowcount==".$_REQUEST['frmPage_rowcount']."&dr=edit&ID=".$_REQUEST['mode']."");
                                 }elseif($_REQUEST['action']=='savenew'){
                                   if($formname=="salesorderheadlist.php")header("location:editsalesorderheadlist.php?txtsearch=".$_REQUEST['searchvalue']."&frmPage_rowcount==".$_REQUEST['frmPage_rowcount']."&dr=add&ID=0");
                                 }
                                 elseif($_REQUEST['action']=='saveclose'){
                                   if($formname=="salesorderheadlist.php")header("location:salesorderheadlist.php");
                                 }
         }
         if($formname=="otsalesorderheadlist.php"){

                                  $temp="";
                                  $temp= "CRM".$_REQUEST['mode']."A$$$";
                                  $target_path = "uploads/";
                                  $target_path = $target_path.$temp . basename( $_FILES['userfile']['name']);
                                  if($_FILES['userfile']['name']) $fileName= $temp.$_FILES['userfile']['name'];

                                  if (file_exists("uploads/".$fileName)){
                                   echo "<center><STRONG>Sorry!!" .$fileName . " already exists.</center></STRONG>";
                                  }else{
                                   move_uploaded_file($_FILES['userfile']['tmp_name'], $target_path);
                                  }
                                  if(basename($_FILES['userfile']['name'])){
                                  $SQL1 = "UPDATE ".$_SESSION['CurrentObjectName']->TableName." SET docname1='".$fileName."' where id=".$_REQUEST['mode'];
                                  mysqli_query($con,$SQL1) or die(mysqli_error()."PA-115<br>".$SQL1);
                                  }

                                  $temp="";
                                  $temp= "CRM".$_REQUEST['mode']."B$$$";
                                  $target_path = "uploads/";
                                  $target_path = $target_path.$temp . basename( $_FILES['userfile1']['name']);
                                  if($_FILES['userfile1']['name']) $fileName= $temp.$_FILES['userfile1']['name'];

                                  if (file_exists("uploads/".$fileName)){
                                   echo "<center><STRONG>Sorry!!" .$fileName . " already exists.</center></STRONG>";
                                  }else{
                                   move_uploaded_file($_FILES['userfile1']['tmp_name'], $target_path);
                                  }
                                  if(basename($_FILES['userfile1']['name'])){
                                  $SQL1 = "UPDATE ".$_SESSION['CurrentObjectName']->TableName." SET proposaldoc='".$fileName."' where id=".$_REQUEST['mode'];
                                  mysqli_query($con,$SQL1) or die(mysqli_error()."PA-115<br>".$SQL1);
                                  }

                                  $temp="";
                                  $temp= "CRM".$_REQUEST['mode']."C$$$";
                                  $target_path = "uploads/";
                                  $target_path = $target_path.$temp . basename( $_FILES['userfile2']['name']);
                                  if($_FILES['userfile2']['name']) $fileName= $temp.$_FILES['userfile2']['name'];

                                  if (file_exists("uploads/".$fileName)){
                                   echo "<center><STRONG>Sorry!!" .$fileName . " already exists.</center></STRONG>";
                                  }else{
                                   move_uploaded_file($_FILES['userfile2']['tmp_name'], $target_path);
                                  }
                                  if(basename($_FILES['userfile2']['name'])){
                                  $SQL1 = "UPDATE ".$_SESSION['CurrentObjectName']->TableName." SET vatdocname='".$fileName."' where id=".$_REQUEST['mode'];
                                  mysqli_query($con,$SQL1) or die(mysqli_error()."PA-115<br>".$SQL1);
                                  }


                                 if($_REQUEST['action']=='save'){
                                   if($formname=="otsalesorderheadlist.php")header("location:editotsalesorderheadlist.php?txtsearch=".$_REQUEST['searchvalue']."&frmPage_rowcount==".$_REQUEST['frmPage_rowcount']."&dr=edit&ID=".$_REQUEST['mode']."");
                                 }elseif($_REQUEST['action']=='savenew'){
                                   if($formname=="otsalesorderheadlist.php")header("location:editotsalesorderheadlist.php?txtsearch=".$_REQUEST['searchvalue']."&frmPage_rowcount==".$_REQUEST['frmPage_rowcount']."&dr=add&ID=0");
                                 }
                                 elseif($_REQUEST['action']=='saveclose'){
                                   if($formname=="otsalesorderheadlist.php")header("location:otsalesorderheadlist.php");
                                 }
         }
         
         if($formname=="joblist.php"){

                                  $temp="";
                                  $temp= "JOB".$_REQUEST['mode']."A$$$";
                                  $target_path = "uploads/";
                                  $target_path = $target_path.$temp . basename( $_FILES['userfile']['name']);
                                  if($_FILES['userfile']['name']) $fileName= $temp.$_FILES['userfile']['name'];

                                  if (file_exists("uploads/".$fileName)){
                                   echo "<center><STRONG>Sorry!!" .$fileName . " already exists.</center></STRONG>";
                                  }else{
                                   move_uploaded_file($_FILES['userfile']['tmp_name'], $target_path);
                                  }
                                  if(basename($_FILES['userfile']['name'])){
                                  $SQL1 = "UPDATE ".$_SESSION['CurrentObjectName']->TableName." SET approvaldocumnet='".$fileName."' where id=".$_REQUEST['mode'];
                                  mysqli_query($con,$SQL1) or die(mysqli_error()."PA-115<br>".$SQL1);
                                  }


                                 if($_REQUEST['action']=='save'){
                                   if($formname=="joblist.php")header("location:editjoblist.php?txtsearch=".$_REQUEST['searchvalue']."&frmPage_rowcount==".$_REQUEST['frmPage_rowcount']."&dr=edit&ID=".$_REQUEST['mode']."");
                                 }elseif($_REQUEST['action']=='savenew'){
                                   if($formname=="joblist.php")header("location:editjoblist.php?txtsearch=".$_REQUEST['searchvalue']."&frmPage_rowcount==".$_REQUEST['frmPage_rowcount']."&dr=add&ID=0");
                                 }
                                 elseif($_REQUEST['action']=='saveclose'){
                                   if($formname=="joblist.php")header("location:joblist.php");
                                 }
         }
         
         if($formname=="emgsalesorderheadlist.php"){

                                  $temp="";
                                  $temp= "CRM".$_REQUEST['mode']."A$$$";
                                  $target_path = "uploads/";
                                  $target_path = $target_path.$temp . basename( $_FILES['userfile']['name']);
                                  if($_FILES['userfile']['name']) $fileName= $temp.$_FILES['userfile']['name'];

                                  if (file_exists("uploads/".$fileName)){
                                   echo "<center><STRONG>Sorry!!" .$fileName . " already exists.</center></STRONG>";
                                  }else{
                                   move_uploaded_file($_FILES['userfile']['tmp_name'], $target_path);
                                  }
                                  if(basename($_FILES['userfile']['name'])){
                                  $SQL1 = "UPDATE ".$_SESSION['CurrentObjectName']->TableName." SET docname1='".$fileName."' where id=".$_REQUEST['mode'];
                                  mysqli_query($con,$SQL1) or die(mysqli_error()."PA-115<br>".$SQL1);
                                  }

                                  $temp="";
                                  $temp= "CRM".$_REQUEST['mode']."B$$$";
                                  $target_path = "uploads/";
                                  $target_path = $target_path.$temp . basename( $_FILES['userfile1']['name']);
                                  if($_FILES['userfile1']['name']) $fileName= $temp.$_FILES['userfile1']['name'];

                                  if (file_exists("uploads/".$fileName)){
                                   echo "<center><STRONG>Sorry!!" .$fileName . " already exists.</center></STRONG>";
                                  }else{
                                   move_uploaded_file($_FILES['userfile1']['tmp_name'], $target_path);
                                  }
                                  if(basename($_FILES['userfile1']['name'])){
                                  $SQL1 = "UPDATE ".$_SESSION['CurrentObjectName']->TableName." SET proposaldoc='".$fileName."' where id=".$_REQUEST['mode'];
                                  mysqli_query($con,$SQL1) or die(mysqli_error()."PA-115<br>".$SQL1);
                                  }

                                  $temp="";
                                  $temp= "CRM".$_REQUEST['mode']."C$$$";
                                  $target_path = "uploads/";
                                  $target_path = $target_path.$temp . basename( $_FILES['userfile2']['name']);
                                  if($_FILES['userfile2']['name']) $fileName= $temp.$_FILES['userfile2']['name'];

                                  if (file_exists("uploads/".$fileName)){
                                   echo "<center><STRONG>Sorry!!" .$fileName . " already exists.</center></STRONG>";
                                  }else{
                                   move_uploaded_file($_FILES['userfile2']['tmp_name'], $target_path);
                                  }
                                  if(basename($_FILES['userfile2']['name'])){
                                  $SQL1 = "UPDATE ".$_SESSION['CurrentObjectName']->TableName." SET vatdocname='".$fileName."' where id=".$_REQUEST['mode'];
                                  mysqli_query($con,$SQL1) or die(mysqli_error()."PA-115<br>".$SQL1);
                                  }


                                 if($_REQUEST['action']=='save'){
                                   if($formname=="emgsalesorderheadlist.php")header("location:editemgsalesorderheadlist.php?txtsearch=".$_REQUEST['searchvalue']."&frmPage_rowcount==".$_REQUEST['frmPage_rowcount']."&dr=edit&ID=".$_REQUEST['mode']."");
                                 }elseif($_REQUEST['action']=='savenew'){
                                   if($formname=="emgsalesorderheadlist.php")header("location:editemgsalesorderheadlist.php?txtsearch=".$_REQUEST['searchvalue']."&frmPage_rowcount==".$_REQUEST['frmPage_rowcount']."&dr=add&ID=0");
                                 }
                                 elseif($_REQUEST['action']=='saveclose'){
                                   if($formname=="emgsalesorderheadlist.php")header("location:emgsalesorderheadlist.php");
                                 }
         }
         if($formname=="newleadheadlist.php"){

                                  $charset="ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";
                                  $length=5;
                                  for($i=0; $i <=$length; $i++){
                                    $rand =rand() % strlen($charset);
                                    $temp=  substr($charset,$rand,3);
                                  }
                                  $temp= "CRM".$_REQUEST['mode']."$$$";
                                  $target_path = "uploads/";
                                  $target_path = $target_path.$temp . basename( $_FILES['userfile']['name']);
                                  if($_FILES['userfile']['name']) $fileName= $temp.$_FILES['userfile']['name'];

                                  if (file_exists("uploads/".$fileName)){
                                   echo "<center><STRONG>Sorry!!" .$fileName . " already exists.</center></STRONG>";
                                  }else{
                                   move_uploaded_file($_FILES['userfile']['tmp_name'], $target_path);
                                  }
                                  if(basename($_FILES['userfile']['name'])){
                                  $SQL1 = "UPDATE ".$_SESSION['CurrentObjectName']->TableName." SET docname='".$fileName."' where id=".$_REQUEST['mode'];
                                  mysqli_query($con,$SQL1) or die(mysqli_error()."PA-115<br>".$SQL1);
                                 }
                                 
                                 // update startdate and enddate
                                 if($_REQUEST['txd_A_startdate']!="" && $_REQUEST['txd_A_startdate']!="00-00-0000"){
                                    if($_REQUEST['cmb_A_durationtype'] !="" && $_REQUEST['txt_A_durationnos']!=""){
                                       $startdate_arr = explode('-',$_REQUEST['txd_A_startdate']);
                                       $date = $startdate_arr[2]."-".$startdate_arr[1]."-".$startdate_arr[0];
                                       $duration = $_REQUEST['txt_A_durationnos'];
                                        if($_REQUEST['cmb_A_durationtype']=="Days"){
                                           $enddate = date("Y-m-d", strtotime($date. " + $duration days"));
                                       }
                                        else if($_REQUEST['cmb_A_durationtype']=="Months"){
                                           $enddate = date("Y-m-d", strtotime($date. " + $duration months"));
                                       }
                                       else  if($_REQUEST['cmb_A_durationtype']=="Years"){
                                           $enddate = $enddate = date("Y-m-d", strtotime($date. " + $duration years"));
                                       }
                                       $upsql ="update in_crmhead set enddate='$enddate' where id=".$_REQUEST['mode'];
                                       mysqli_query($con,$upsql);
                                    }
                                 }
                                 
                                 if($_REQUEST['action']=='save'){
                                   if($formname=="newleadheadlist.php")header("location:editnewleadheadlist.php?txtsearch=".$_REQUEST['searchvalue']."&frmPage_rowcount==".$_REQUEST['frmPage_rowcount']."&dr=edit&ID=".$_REQUEST['mode']."");
                                 }elseif($_REQUEST['action']=='savenew'){
                                   if($formname=="newleadheadlist.php")header("location:editnewleadheadlist.php?txtsearch=".$_REQUEST['searchvalue']."&frmPage_rowcount==".$_REQUEST['frmPage_rowcount']."&dr=add&ID=0");
                                 }
                                 elseif($_REQUEST['action']=='saveclose'){
                                   if($formname=="newleadheadlist.php")header("location:newleadheadlist.php");
                                 }
         }
         if($formname=="calloutrequest.php"){

                                  $temp= "CRM".$_REQUEST['mode']."$$$";
                                  $target_path = "uploads/";
                                  $target_path = $target_path.$temp . basename( $_FILES['userfile']['name']);
                                  if($_FILES['userfile']['name']) $fileName= $temp.$_FILES['userfile']['name'];

                                  if (file_exists("uploads/".$fileName)){
                                   echo "<center><STRONG>Sorry!!" .$fileName . " already exists.</center></STRONG>";
                                  }else{
                                   move_uploaded_file($_FILES['userfile']['tmp_name'], $target_path);
                                  }
                                  if(basename($_FILES['userfile']['name'])){
                                  $SQL1 = "UPDATE ".$_SESSION['CurrentObjectName']->TableName." SET docname='".$fileName."' where id=".$_REQUEST['mode'];
                                  mysqli_query($con,$SQL1) or die(mysqli_error()."PA-115<br>".$SQL1);
                                 }


                                 if($_REQUEST['action']=='save'){
                                   if($formname=="calloutrequest.php")header("location:editcalloutrequest.php?txtsearch=".$_REQUEST['searchvalue']."&frmPage_rowcount==".$_REQUEST['frmPage_rowcount']."&dr=edit&ID=".$_REQUEST['mode']."");
                                 }elseif($_REQUEST['action']=='savenew'){
                                   if($formname=="calloutrequest.php")header("location:editcalloutrequest.php?txtsearch=".$_REQUEST['searchvalue']."&frmPage_rowcount==".$_REQUEST['frmPage_rowcount']."&dr=add&ID=0");
                                 }
                                 elseif($_REQUEST['action']=='saveclose'){
                                   if($formname=="calloutrequest.php")header("location:calloutrequest.php");
                                 }
         }
         if($formname=="emgcalloutrequest.php"){

                                  $temp= "CRM".$_REQUEST['mode']."$$$";
                                  $target_path = "uploads/";
                                  $target_path = $target_path.$temp . basename( $_FILES['userfile']['name']);
                                  if($_FILES['userfile']['name']) $fileName= $temp.$_FILES['userfile']['name'];

                                  if (file_exists("uploads/".$fileName)){
                                   echo "<center><STRONG>Sorry!!" .$fileName . " already exists.</center></STRONG>";
                                  }else{
                                   move_uploaded_file($_FILES['userfile']['tmp_name'], $target_path);
                                  }
                                  if(basename($_FILES['userfile']['name'])){
                                  $SQL1 = "UPDATE ".$_SESSION['CurrentObjectName']->TableName." SET docname='".$fileName."' where id=".$_REQUEST['mode'];
                                  mysqli_query($con,$SQL1) or die(mysqli_error()."PA-115<br>".$SQL1);
                                 }


                                 if($_REQUEST['action']=='save'){
                                   if($formname=="emgcalloutrequest.php")header("location:editemgcalloutrequest.php?txtsearch=".$_REQUEST['searchvalue']."&frmPage_rowcount==".$_REQUEST['frmPage_rowcount']."&dr=edit&ID=".$_REQUEST['mode']."");
                                 }elseif($_REQUEST['action']=='savenew'){
                                   if($formname=="emgcalloutrequest.php")header("location:editemgcalloutrequest.php?txtsearch=".$_REQUEST['searchvalue']."&frmPage_rowcount==".$_REQUEST['frmPage_rowcount']."&dr=add&ID=0");
                                 }
                                 elseif($_REQUEST['action']=='saveclose'){
                                   if($formname=="emgcalloutrequest.php")header("location:emgcalloutrequest.php");
                                 }
         }
         if($formname=="emgticket.php"){

                                  $temp= "EMGT".$_REQUEST['mode']."$$$";
                                  $target_path = "uploads/";
                                  $target_path = $target_path.$temp . basename( $_FILES['userfile']['name']);
                                  if($_FILES['userfile']['name']) $fileName= $temp.$_FILES['userfile']['name'];

                                  if (file_exists("uploads/".$fileName)){
                                   echo "<center><STRONG>Sorry!!" .$fileName . " already exists.</center></STRONG>";
                                  }else{
                                   move_uploaded_file($_FILES['userfile']['tmp_name'], $target_path);
                                  }
                                  if(basename($_FILES['userfile']['name'])){
                                  $SQL1 = "UPDATE ".$_SESSION['CurrentObjectName']->TableName." SET docname='".$fileName."' where id=".$_REQUEST['mode'];
                                  mysqli_query($con,$SQL1) or die(mysqli_error()."PA-115<br>".$SQL1);
                                 }


                                 if($_REQUEST['action']=='save'){
                                   if($formname=="emgticket.php")header("location:editemgticket.php?txtsearch=".$_REQUEST['searchvalue']."&frmPage_rowcount==".$_REQUEST['frmPage_rowcount']."&dr=edit&ID=".$_REQUEST['mode']."");
                                 }elseif($_REQUEST['action']=='savenew'){
                                   if($formname=="emgticket.php")header("location:editemgticket.php?txtsearch=".$_REQUEST['searchvalue']."&frmPage_rowcount==".$_REQUEST['frmPage_rowcount']."&dr=add&ID=0");
                                 }
                                 elseif($_REQUEST['action']=='saveclose'){
                                   if($formname=="emgticket.php")header("location:emgticket.php");
                                 }
         }
         if($formname=="calloutticket.php"){

                                  $temp= "AMCCOTT".$_REQUEST['mode']."$$$";
                                  $target_path = "uploads/";
                                  $target_path = $target_path.$temp . basename( $_FILES['userfile']['name']);
                                  if($_FILES['userfile']['name']) $fileName= $temp.$_FILES['userfile']['name'];

                                  if (file_exists("uploads/".$fileName)){
                                   echo "<center><STRONG>Sorry!!" .$fileName . " already exists.</center></STRONG>";
                                  }else{
                                   move_uploaded_file($_FILES['userfile']['tmp_name'], $target_path);
                                  }
                                  if(basename($_FILES['userfile']['name'])){
                                  $SQL1 = "UPDATE ".$_SESSION['CurrentObjectName']->TableName." SET docname='".$fileName."' where id=".$_REQUEST['mode'];
                                  mysqli_query($con,$SQL1) or die(mysqli_error()."PA-115<br>".$SQL1);
                                 }


                                 if($_REQUEST['action']=='save'){
                                   if($formname=="calloutticket.php")header("location:editcalloutticket.php?txtsearch=".$_REQUEST['searchvalue']."&frmPage_rowcount==".$_REQUEST['frmPage_rowcount']."&dr=edit&ID=".$_REQUEST['mode']."");
                                 }elseif($_REQUEST['action']=='savenew'){
                                   if($formname=="calloutticket.php")header("location:editcalloutticket.php?txtsearch=".$_REQUEST['searchvalue']."&frmPage_rowcount==".$_REQUEST['frmPage_rowcount']."&dr=add&ID=0");
                                 }
                                 elseif($_REQUEST['action']=='saveclose'){
                                   if($formname=="calloutticket.php")header("location:calloutticket.php");
                                 }
         }
         if($formname=="otleadheadlist.php"){

                                  $charset="ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";
                                  $length=5;
                                  for($i=0; $i <=$length; $i++){
                                    $rand =rand() % strlen($charset);
                                    $temp=  substr($charset,$rand,3);
                                  }
                                  $temp= "CRM".$_REQUEST['mode']."$$$";
                                  $target_path = "uploads/";
                                  $target_path = $target_path.$temp . basename( $_FILES['userfile']['name']);
                                  if($_FILES['userfile']['name']) $fileName= $temp.$_FILES['userfile']['name'];

                                  if (file_exists("uploads/".$fileName)){
                                   echo "<center><STRONG>Sorry!!" .$fileName . " already exists.</center></STRONG>";
                                  }else{
                                   move_uploaded_file($_FILES['userfile']['tmp_name'], $target_path);
                                  }
                                  if(basename($_FILES['userfile']['name'])){
                                  $SQL1 = "UPDATE ".$_SESSION['CurrentObjectName']->TableName." SET docname='".$fileName."' where id=".$_REQUEST['mode'];
                                  mysqli_query($con,$SQL1) or die(mysqli_error()."PA-115<br>".$SQL1);
                                 }


                                 if($_REQUEST['action']=='save'){
                                   if($formname=="otleadheadlist.php")header("location:editotleadheadlist.php?txtsearch=".$_REQUEST['searchvalue']."&frmPage_rowcount==".$_REQUEST['frmPage_rowcount']."&dr=edit&ID=".$_REQUEST['mode']."");
                                 }elseif($_REQUEST['action']=='savenew'){
                                   if($formname=="otleadheadlist.php")header("location:editotleadheadlist.php?txtsearch=".$_REQUEST['searchvalue']."&frmPage_rowcount==".$_REQUEST['frmPage_rowcount']."&dr=add&ID=0");
                                 }
                                 elseif($_REQUEST['action']=='saveclose'){
                                   if($formname=="otleadheadlist.php")header("location:otleadheadlist.php");
                                 }
         }
         if($formname=="maineventshead.php"){

            $dateextend = date('Y-m-d', strtotime($RequestBuffer['txd_A_todate']. "+ ".$RequestBuffer['txt_A_adddays']." days"));
            $squpdateSQL = "UPDATE in_crmeventhead SET dateextend='$dateextend' WHERE id='".$_REQUEST['mode']."'";
            mysqli_query($con,$squpdateSQL) or die(mysqli_error()."<br>".$squpdateSQL);

            if($_REQUEST['action']=='save'){
                if($formname=="maineventshead.php")header("location:editmaineventshead.php?dr=edit&ID=".$_REQUEST['mode']."");
            }elseif($_REQUEST['action']=='savenew'){
                if($formname=="maineventshead.php")header("location:editmaineventshead.php?dr=add&ID=0");
            }elseif($_REQUEST['action']=='saveclose'){
                if($formname=="maineventshead.php")header("location:maineventshead.php");
            }
        }
        if($formname=="crmleadvisitlistcontact.php"){
                   $seqID = GetLastSqeID("in_crmvisit");

               if($_REQUEST['cmb_A_status']=="Happened" && $_REQUEST['txd_A_followupdate']!=""){

                   $Dvalue  = explode('-',$_REQUEST['txd_A_followupdate']);
                   $value   = $Dvalue[2].'-'.$Dvalue[1].'-'.$Dvalue[0];

                   $SQL2        ="Insert into in_crmvisit(
                                  id,activitytype,objectcode,objectname,visitdate,
                                  eventtype,priority,location,status,action,companycode,locationcode,userid)
                                  values (".$seqID.",'".$_REQUEST['cmb_A_activitytype']."','".$_REQUEST['txt_A_objectcode']."','".$_REQUEST['txt_A_objectname']."','".$value."',
                                  '".$_REQUEST['cmb_A_eventtype']."','".$_REQUEST['cmb_A_priority']."','".$_REQUEST['txt_A_location']."','Open','".$_REQUEST['txa_A_followupdetail']."'
                                  ,'','','".$_SESSION['SESSuserID']."')";

                   $SQLRes     =  mysqli_query($con,$SQL2) or die($SQL2);
               }
        }
        if($formname=="crmcontactlist.php"){
                                     // to add direct customers , and account ledger
if($RequestBuffer['cmb_A_objecttype'] == "Customer"){
                   $category=9;
                   $SQL1   = "SELECT right(accountheadcode,4)*1 as count FROM in_accounthead WHERE postinggroupcode='$category' order by right(accountheadcode,4)*1 desc limit 0,1 ";
                   $Res1 =  mysqli_query($con,$SQL1) or die(mysqli_error()."<br>".$SQL1);

                   if(mysqli_num_rows($Res1)>=1){
                       $Array1   = mysqli_fetch_array($Res1);
                           $count =  $Array1['count']+1 ;
                           }
                       $countzeros = str_pad($count, 5, "0", STR_PAD_LEFT);
                       $SQL   = "SELECT groupcode FROM in_accountgroup WHERE id='".$category."' ";
                       $SQLRes =  mysqli_query($con,$SQL) or die(mysqli_error()."<br>".$SQL);
                       if(mysqli_num_rows($SQLRes)>=1){
                          $loginResultArray   = mysqli_fetch_array($SQLRes);
                               $groupcode=  $loginResultArray['groupcode'];
                               $str = substr($groupcode, 0, -5);
                               $ledgercode=$str.$countzeros;
                       }
                    $SQL5 = "Select * from in_accounthead where (accountheadname='".$_REQUEST['txt_A_objectname']."' or objectcode='".$RequestBuffer['txt_A_objectcode']."') and postinggroupcode='$category'";
                                  $SQLRes5 =  mysqli_query($con,$SQL5) or die(mysqli_error()."<br>".$SQL5);
                                  if(mysqli_num_rows($SQLRes5)==0){

                                              $clientcode = $ledgercode;
                                              $seqID = GetLastSqeID_current("in_accounthead");
                                              $insAccountSQL = "INSERT INTO in_accounthead
                                                                VALUES('$seqID','$category','$ledgercode','".$_REQUEST['txt_A_objectname']."','".$RequestBuffer['txt_A_objectcode']."','".$_REQUEST['txt_A_objectname']."','0','','','Party',
                                                                       '','','','','','','','Yes','No','$count','Active','".$_REQUEST['contactperson']."','".$_REQUEST['billingemail']."','".$_REQUEST['billingfax']."',
                                                                       '".$_REQUEST['phonecode1']."','".$_REQUEST['phonecode2']."','".$_REQUEST['billingaddress1']."',
                                                                       '','','No','".$_REQUEST['website']."','".$_REQUEST['vatid']."','','')";
                                              mysqli_query($con,$insAccountSQL) or die(mysqli_error()."<br>".$insAccountSQL);

                                              $squpdateSQL = "UPDATE in_sequencer SET LASTNUMBER=LASTNUMBER+1 WHERE TABLENAME='in_accounthead'";
                                              mysqli_query($con,$squpdateSQL) or die(mysqli_error()."<br>".$squpdateSQL);

                                              $squpdateSQL33 = "UPDATE in_businessobject SET accountheadcode='$ledgercode',objecttype='Customer',eccno='".$ledgercode."' WHERE objectcode='".$RequestBuffer['txt_A_objectcode']."'";
                                              mysqli_query($con,$squpdateSQL33) or die(mysqli_error()."<br>".$squpdateSQL33);
                                  }

}
                   //
                   if($RequestBuffer['cmb_A_fwduserid']!='' && $RequestBuffer['cmb_A_fwduserid']!='Select') {
                      $SQL2 = "UPDATE in_businessobject SET fwdstatus='FORWARDED' where id=".$_REQUEST['mode'];
                      mysqli_query($con,$SQL2) or die(mysqli_error()."PA-115<br>".$SQL2);
                   }
                   if($RequestBuffer['cmb_A_shareuserid']!='') {
                      $SQL2 = "UPDATE in_businessobject SET teleaction='SHARED' where id=".$_REQUEST['mode'];
                      mysqli_query($con,$SQL2) or die(mysqli_error()."PA-115<br>".$SQL2);
                   }

                   $objectname = str_replace("^^^","&",$_REQUEST['txt_A_objectname']);
                  /* $SQL   = "SELECT accountheadcode from in_accounthead where objectcode='".$RequestBuffer['txt_A_objectcode']."'";
                   $SQLRes =  mysqli_query($con,$SQL) or die(mysqli_error()."<br>".$SQL);
                   if(mysqli_num_rows($SQLRes)>=1){
                      while($loginResultArray   = mysqli_fetch_array($SQLRes)){

                            //$squpdateSQL = "UPDATE in_crmhead SET objectname='".$objectname."' WHERE objectcode='".$loginResultArray['accountheadcode']."' and doctype='ORDER'";
                           // mysqli_query($con,$squpdateSQL) or die(mysqli_error()."<br>".$squpdateSQL);
                      }
                   }  */
                 //  $squpdateSQL1 = "UPDATE in_crmhead SET objectname='".$objectname."' WHERE objectcode='".$_REQUEST['txt_A_objectcode']."'";
                 //  mysqli_query($con,$squpdateSQL1) or die(mysqli_error()."<br>".$squpdateSQL1);

                   $squpdateSQL2 = "UPDATE in_accounthead SET objectname='".$objectname."',accountheadname='".$objectname."' WHERE objectcode='".$_REQUEST['txt_A_objectcode']."'";
                   mysqli_query($con,$squpdateSQL2) or die(mysqli_error()."<br>".$squpdateSQL2);

                   $SQL   = "SELECT id from in_businessobjectdetails where contactname='".$RequestBuffer['txt_A_contactperson']."' and businessobjectid='".$_REQUEST['mode']."'";
                   $SQLRes =  mysqli_query($con,$SQL) or die(mysqli_error()."<br>".$SQL);
                   if(mysqli_num_rows($SQLRes)==0){
                        $seqNumber1 = GetLastSqeID("in_businessobjectdetails");
                        $SQL1 = "insert into in_businessobjectdetails(id,businessobjectid,contactname,phone) values('$seqNumber1','".$_REQUEST['mode']."','".$RequestBuffer['txt_A_contactperson']."','".$RequestBuffer['txt_A_phonecode1']."')";
                        mysqli_query($con,$SQL1) or die(mysqli_error()."PA-115<br>".$SQL1);
                   }else{

                        $squpdateSQL11 = "UPDATE in_businessobjectdetails SET phone='".$_REQUEST['txt_A_phonecode1']."' WHERE contactname='".$_REQUEST['txt_A_contactperson']."' and businessobjectid='".$_REQUEST['mode']."'";
                        mysqli_query($con,$squpdateSQL11) or die(mysqli_error()."<br>".$squpdateSQL11);
                   }




        }
        if($formname=="crmteam.php"){

            for($ii=0 ;$ii < count($RequestBuffer['ckk_A_assignedto']); $ii++){
               $SQL   = "SELECT username from in_user where userid='".$RequestBuffer['ckk_A_assignedto'][$ii]."'";
               $SQLRes =  mysqli_query($con,$SQL) or die(mysqli_error()."<br>".$SQL);
               if(mysqli_num_rows($SQLRes)>=1){
                  while($loginResultArray   = mysqli_fetch_array($SQLRes)){
                        $username .= $loginResultArray['username'].",";
                  }
               }
            }
            $username = substr($username,0,strlen($username)-1) ;
            $SQL   = "update in_crmteam set nameassignedto='$username' where id='".$_REQUEST['mode']."'";
            $SQLRes =  mysqli_query($con,$SQL) or die(mysqli_error()."<br>".$SQL);

            $totalsum=0;
            for($ii=1 ;$ii <=12; $ii++){
                $totalsum += $RequestBuffer['txt_A_'."_".$ii];
            }
            $SQL   = "update in_crmteam set yearlytarget='$totalsum' where id='".$_REQUEST['mode']."'";
            $SQLRes =  mysqli_query($con,$SQL) or die(mysqli_error()."<br>".$SQL);

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
           if($RequestBuffer['txd_A_actenddate']!="" && $RequestBuffer['txd_A_actenddate']!="00-00-0000"){
                  $enddate = date("Y-m-d",strtotime($RequestBuffer['txd_A_actenddate']));
                  $months = $RequestBuffer['txt_A_liabilityperiod'];
                  if($months=="")$months=0;
                  $Duedate = date('Y-m-d', strtotime("+".$months." months", strtotime($enddate)));

                  $squpdateSQL = "UPDATE t_activitycenter SET retreleasedate='$Duedate' WHERE id='".$_REQUEST['mode']."'";
                  mysqli_query($con,$squpdateSQL) or die(mysqli_error()."<br>".$squpdateSQL);
           }else{
                  $squpdateSQL = "UPDATE t_activitycenter SET retreleasedate='' WHERE id='".$_REQUEST['mode']."'";
                  mysqli_query($con,$squpdateSQL) or die(mysqli_error()."<br>".$squpdateSQL);
           }
           $squpdateSQL = "UPDATE t_activitycenter SET projectstore='66003' WHERE projectstore=''";
           mysqli_query($con,$squpdateSQL) or die(mysqli_error()."<br>".$squpdateSQL);
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
                                      mysqli_query($con,$SQL1) or die(mysqli_error()."PA-115<br>".$SQL1);

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
                                      mysqli_query($con,$SQL1) or die(mysqli_error()."PA-115<br>".$SQL1);

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
                                  mysqli_query($con,$SQL1) or die(mysqli_error()."PA-115<br>".$SQL1);
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
                                      mysqli_query($con,$SQL1) or die(mysqli_error()."PA-115<br>".$SQL1);
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
                                      mysqli_query($con,$SQL1) or die(mysqli_error()."PA-115<br>".$SQL1);
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
                                      mysqli_query($con,$SQL1) or die(mysqli_error()."PA-115<br>".$SQL1);
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
                                      mysqli_query($con,$SQL1) or die(mysqli_error()."PA-115<br>".$SQL1);
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
            $SQLRes =  mysqli_query($con,$SQL) or die(mysqli_error()."<br>".$SQL);

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
               $SQLRes =  mysqli_query($con,$SQL) or die(mysqli_error()."<br>".$SQL);
               if(mysqli_num_rows($SQLRes)>=1){
                  while($loginResultArray   = mysqli_fetch_array($SQLRes)){
                        $custercompany .= $loginResultArray['jobname'].",";
                  }
               }
            }
            $custercompany = substr($custercompany,0,strlen($custercompany)-1) ;
            $SQL   = "update in_companycluster set companyname='$custercompany' where id='".$_REQUEST['mode']."'";
            $SQLRes =  mysqli_query($con,$SQL) or die(mysqli_error()."<br>".$SQL);
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
                  $SQLRes =  mysqli_query($con,$SQL) or die(mysqli_error()."<br>".$SQL);
                  if(mysqli_num_rows($SQLRes)>=1){
                   $loginResultArray   = mysqli_fetch_array($SQLRes);
                   $days= $loginResultArray['days']+1;
                  }

                 $SQL2 = "UPDATE e_leave SET daysallowed=".$days." where id=".$_REQUEST['mode'];
                 mysqli_query($con,$SQL2) or die(mysqli_error()."PA-115<br>".$SQL2);


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
                                  mysqli_query($con,$SQL1) or die(mysqli_error()."PA-115<br>".$SQL1);
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
                                  mysqli_query($con,$SQL1) or die(mysqli_error()."PA-115<br>".$SQL1);
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
                                 //mysqli_query($con,$squpdateSQL) or die(mysqli_error()."<br>".$squpdateSQL);

                                 $SQL   = "SELECT rolecode,userid FROM in_user WHERE id=".$_REQUEST['mode'];
                                 $SQLRes =  mysqli_query($con,$SQL) or die(mysqli_error()."<br>".$SQL);
                                 if(mysqli_num_rows($SQLRes)>=1){
                                   while($loginResultArray   = mysqli_fetch_array($SQLRes)){
                                    $updatedrole=$loginResultArray['rolecode'];
                                    $userid=$loginResultArray['userid'];
                                   }
                                 }
                                 $squpdateSQL = "UPDATE in_personalinfo SET rolecode='$updatedrole' WHERE empid='".$userid."'";
                                 mysqli_query($con,$squpdateSQL) or die(mysqli_error()."<br>".$squpdateSQL);

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
            mysqli_query($con,$squpdateSQL1) or die(mysqli_error()."<br>".$squpdateSQL1);

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
                                  mysqli_query($con,$SQL1) or die(mysqli_error()."PA-115<br>".$SQL1);
                                 }

                                 //$squpdateSQL = "UPDATE in_personalinfo SET rolecode=concat('EMPLOYEE,',rolecode) WHERE id=".$_REQUEST['mode'];
                                 //mysqli_query($con,$squpdateSQL) or die(mysqli_error()."<br>".$squpdateSQL);

                                 $SQL   = "SELECT rolecode FROM in_personalinfo WHERE id=".$_REQUEST['mode'];
                                 $SQLRes =  mysqli_query($con,$SQL) or die(mysqli_error()."<br>".$SQL);
                                 if(mysqli_num_rows($SQLRes)>=1){
                                   while($loginResultArray   = mysqli_fetch_array($SQLRes)){
                                    $updatedrole=$loginResultArray['rolecode'];
                                   }
                                 }
                                 $SQL   = "SELECT acclocationcode,locationcode FROM in_user WHERE userid='".$RequestBuffer['txt_A_empid']."'";
                                 $SQLRes =  mysqli_query($con,$SQL) or die(mysqli_error()."<br>".$SQL);
                                 if(mysqli_num_rows($SQLRes)>=1){
                                   while($loginResultArray   = mysqli_fetch_array($SQLRes)){

                                    $acclocationcode=$loginResultArray['acclocationcode'];
                                    $locationcode=$loginResultArray['locationcode'];

                                    $locationcode = str_replace($acclocationcode,$RequestBuffer['cmb_A_empcompany'],$locationcode);

                                   }
                                 }
                                 $insAccountSQL = "update in_user set username='".$RequestBuffer['txt_A_empfirstename']." ".$RequestBuffer['txt_A_emplastename']."',
                                                   rolecode='".$updatedrole."',acclocationcode='".$RequestBuffer['cmb_A_empcompany']."',locationcode='$locationcode'
                                                   where userid='".$RequestBuffer['txt_A_empid']."'";
                                 mysqli_query($con,$insAccountSQL) or die(mysqli_error()."<br>".$insAccountSQL);


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
                                  mysqli_query($con,$SQL1) or die(mysqli_error()."PA-115<br>".$SQL1);
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

function UserLog($tblName,$tableseqID,$tablestrSQL,$actiontype){
	
	global $con;
        $seqID = GetLastSqeID("in_userlog");
        $datetime=date("Y/m/d h:i:s a", time());
        $seqSQL = "insert into in_userlog values(".$seqID.",'".$datetime."','".$_SESSION['SESSuserID'] ."','".$_SERVER['REMOTE_ADDR']."','".$tblName."','".$tableseqID."','".$actiontype."','".str_replace("'","''",$tablestrSQL)."','".$_SESSION['SESSUserLocation']."','".$_SESSION['SESScompanycode']."')";
        $result=mysqli_query($con,$seqSQL) or die(mysqli_error()."<br>".$seqSQL);



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
    function GetLastSqeID_current($tblName){
    	global $con;
                 $seqSQL = "SELECT LASTNUMBER FROM in_sequencer WHERE TABLENAME='$tblName'";
                 $result=mysqli_query($con,$seqSQL) or die(mysqli_error()."<br>".$seqSQL);
                 $resulArr=mysqli_fetch_array($result);
                 $updatedSeqID=$resulArr['LASTNUMBER']+1;
                 //$squpdateSQL = "UPDATE in_sequencer SET LASTNUMBER=".$updatedSeqID." WHERE TABLENAME='$tblName'";
                 //mysqli_query($con,$squpdateSQL) or die(mysqli_error()."<br>".$squpdateSQL);
                 $query = "UNLOCK TABLES";
                 mysqli_query($con,$query) or die(mysqli_error()."<br>".$query);
                 return ($updatedSeqID);
    }
    function get_Aricle_Name($code){ 
    
		$res = mysqli_query($con,"select categoryname from in_asset where categorycode='$code'");
		$arr = mysqli_fetch_array($res);
		return $arr['categoryname'];
	}
ob_end_flush();
?>
