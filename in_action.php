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
//echo $myTable =  $_SESSION['CurrentObjectName']->TableName;
//echo $_REQUEST['mode'];
$RequestBuffer = $_REQUEST;
//$RequestBuffer_row = $_REQUEST['ZZZZZZXXXXXX'];
if(isset($_REQUEST["ZZZZZZXXXXXX"])){
$RequestBuffer_row = $_REQUEST['ZZZZZZXXXXXX'];
}
else{
$RequestBuffer_row=""	;
}

//print_r($RequestBuffer);
//exit;

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
                $value = str_replace("^@^@^","#",$value);
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
               if($value!="") {
                $Dvalue  = explode('-',$value);
                $value   = $Dvalue[2].'-'.$Dvalue[1].'-'.$Dvalue[0];   
			    }                        //  Build Field Names
                $strSQL = $strSQL . $strFields . "='" . $value . "',";
            }

        }
		
		$formname = $_SESSION['CurrentObjectName']->formName;
		if($formname=="userprivilegessetup.php"){
			$services_array = explode(',',$_REQUEST['menus']);
					$strSQL = "UPDATE " . $_SESSION['CurrentObjectName']->TableName . " SET slno='".$_REQUEST['txt_A_slno']."',remarks='".$_REQUEST['txt_A_remarks']."',usergroup='".$_REQUEST['txt_A_rolecode']."' where id = '".$_REQUEST['mode']."' ";    //  Removing Last coma
           			$result   = mysqli_query($con,$strSQL)  or die(mysqli_error($con));
           			if(count($services_array)>0){ 
           				for($p=0;$p<count($services_array);$p++)	{
           					$childseqID = GetLastSqeID('tbl_menusetup');
           					$strSQL_a = "Insert into tbl_menusetup (id,slno,menucode,usergroupid,status,parentid) values ('".$childseqID."','".$p."','".$services_array[$p]."','".$_REQUEST['mode']."','Active','0')"; 
           					mysqli_query($con,$strSQL_a)  or die(mysqli_error($con)); 
						
						}
					}
		}
		else{
        $strSQL = "UPDATE " . $_SESSION['CurrentObjectName']->TableName . " SET " . substr($strSQL,0,strlen($strSQL)-1);    //  Removing Last coma
        $strSQL = $strSQL . " WHERE ID='" . $_REQUEST['mode'] ."'";
        //echo $strSQL;exit;
        mysqli_query($con,"SET NAMES 'utf8'");
        $result   = mysqli_query($con,$strSQL)  or die(mysqli_error());
		}
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
                $value = str_replace("^@^@^","#",$value);
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
                $chkCount = @count($value);
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
                if($value!=""){
                $Dvalue  = explode('-',$value);
                $value   = $Dvalue[2].'-'.$Dvalue[1].'-'.$Dvalue[0];                           //  Build Field Names
				}
                $strValues  .= "'" . $value . "',";                          //  If Alpha put quotes
            }
        }

        $strFields = substr($strFields,0,strlen($strFields)-1) ;
        $strValues = substr($strValues,0,strlen($strValues)-1) ;
        
        $formname = $_SESSION['CurrentObjectName']->formName;
         if($formname=="userprivilegessetup.php"){
			$services_array = explode(',',$_REQUEST['menus']);
					$strSQL = "Insert into " . $_SESSION['CurrentObjectName']->TableName . "(id,slno,usergroup,remarks) values ('".$seqID."','".$_REQUEST['txt_A_slno']."','".$_REQUEST['txt_A_rolecode']."','".$_REQUEST['txt_A_remarks']."')";    //  Removing Last coma
           			$result   = mysqli_query($con,$strSQL)  or die(mysqli_error($con));
           			if(count($services_array)>0){ 
           				for($p=0;$p<count($services_array);$p++)	{
           					$childseqID = GetLastSqeID('tbl_menusetup');
           					$strSQL_a = "Insert into tbl_menusetup (id,slno,menucode,usergroupid,status,parentid) values ('".$childseqID."','".$p."','".$services_array[$p]."','$seqID','Active','0')"; 
           					mysqli_query($con,$strSQL_a)  or die(mysqli_error($con)); 
						
						}
					}
		}
		else{
        $strSQL = "Insert into " . $_SESSION['CurrentObjectName']->TableName . "(ID,".$strFields.") values ('".$seqID."'," . $strValues .")";    //  Removing Last coma
        mysqli_query($con,"SET NAMES 'utf8'");
        $result   = mysqli_query($con,$strSQL)  or die(mysqli_error());
		}

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
                $value = str_replace("^@^@^","#",$value);
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

        $result   = mysqli_query($con,$strSQL);
        UserLog($_SESSION['CurrentObjectName']->TableNameChild,$seqID,$strSQL,"INSERT");
        $formname = $_SESSION['CurrentObjectName']->formName;

        if($formname!="addstockitems.php"){
          echo "Record Saved";
        }
}



function PostInsertChildStatement($RequestBuffer,$RequestBuffer_row){
	global $con;
    $formname = $_SESSION['CurrentObjectName']->formName;
    // oct 15 2020, new application code add here
    if($formname=="upload_documents.php"){
          for($i=0 ;$i < $_REQUEST['selectslots']; $i++){
                                    if($i==0){

                                      $seqNumber1 = GetLastSqeID("tbl_attachments");
                                      $temp= $seqNumber1.date('dmyHis')."$$$"; 
                                      $target_path = basename($_FILES['userfile']['name']);
                                      $target_path = "uploads/";
                                      $target_path = $target_path .$temp. basename( $_FILES['userfile']['name']);
                                      
                                      if($_FILES['userfile']['name']) $imgName= $temp.$_FILES['userfile']['name'];

                                       move_uploaded_file($_FILES['userfile']['tmp_name'], $target_path);

                                       $SQL1 = "insert into tbl_attachments(id,docid,docname,doctype)
                                               values('$seqNumber1','".$_SESSION['lastID']."','".$imgName ."','".$_REQUEST['txt_A_entitytype']."') ";
                                      if($imgName!="")
                                      mysqli_query($con,$SQL1) or die(mysqli_error()."PA-115<br>".$SQL1);

                                    }else{
                                      $field='userfile'.$i;
                                      $seqNumber1 = GetLastSqeID("tbl_attachments");
                                      $temp= $seqNumber1.date('dmyHis')."$$$"; 
                                      $target_path = basename($_FILES[$field]['name']);
                                      $target_path = "uploads/";
                                      $target_path = $target_path .$temp. basename( $_FILES[$field]['name']);
                                      if($_FILES[$field]['name']) $imgName= $temp.$_FILES[$field]['name'];

                                       move_uploaded_file($_FILES[$field]['tmp_name'], $target_path);

									
                                      $SQL1 = "insert into tbl_attachments(id,docid,docname,doctype)
                                               values('$seqNumber1','".$_SESSION['lastID']."','".$imgName ."','".$_REQUEST['txt_A_entitytype']."') ";
                                      if($imgName!="")
                                      mysqli_query($con,$SQL1) or die(mysqli_error()."PA-115<br>".$SQL1);

                                    }
                                }
             if($formname=="upload_documents.php")header("location:upload_documents.php?entitytype=".$_REQUEST['txt_A_entitytype']."&ID=".$_REQUEST['txt_A_docid']."");

         }
         
    if($formname=="companylogo.php"){
	    	    
	    	    $temp= "CL".$_SESSION['lastID'].date('dmyHis')."A$$$";
            	$target_path = basename($_FILES['userfile']['name']);
                $target_path = "uploads/";
                $target_path = $target_path .$temp. basename( $_FILES['userfile']['name']);
                if($_FILES['userfile']['name']) $fileName= $temp.$_FILES['userfile']['name'];
                if (file_exists("uploads/".$fileName)){
                   echo "<center><STRONG>Sorry!!" .$fileName . " already exists.</center></STRONG>";
                }else{
                   move_uploaded_file($_FILES['userfile']['tmp_name'], $target_path);
                }
                                
                if($fileName!='') {
                  $SQL1 = "UPDATE ".$_SESSION['CurrentObjectName']->TableNameChild." SET docname='".$fileName."' where id=".$_SESSION['lastID'];
                  mysqli_query($con,$SQL1) or die(mysqli_error()."PA-115<br>".$SQL1);
                }
                
               	if($formname=="companylogo.php") header("location:companylogo.php?PARENTID=".$_REQUEST['txt_A_parentid']."&TYPE=".$_REQUEST['txt_A_type']);
             	
                	    
	    }
	
	if($formname=="propertyphotos.php"){
	    	    
	    	    $temp= "PP".$_SESSION['lastID'].date('dmyHis')."A$$$";
            	$target_path = basename($_FILES['userfile']['name']);
                $target_path = "uploads/";
                $target_path = $target_path .$temp. basename( $_FILES['userfile']['name']);
                if($_FILES['userfile']['name']) $fileName= $temp.$_FILES['userfile']['name'];
                if (file_exists("uploads/".$fileName)){
                   echo "<center><STRONG>Sorry!!" .$fileName . " already exists.</center></STRONG>";
                }else{
                   move_uploaded_file($_FILES['userfile']['tmp_name'], $target_path);
                }
                                
                if($fileName!='') {
                  $SQL1 = "UPDATE ".$_SESSION['CurrentObjectName']->TableNameChild." SET docname='".$fileName."' where id=".$_SESSION['lastID'];
                  mysqli_query($con,$SQL1) or die(mysqli_error()."PA-115<br>".$SQL1);
                }
                
               	if($formname=="propertyphotos.php") header("location:propertyphotos.php?ID=0&PARENTID=".$_REQUEST['txt_A_parentid']."&TYPE=".$_REQUEST['txt_A_type']);
             	
                	    
	    }    
    // end of PostInsertChildStatement code 

}
function PostUpdateChildStatement($RequestBuffer,$RequestBuffer_row){
	
	global $con;

    $formname = $_SESSION['CurrentObjectName']->formName;
         
    if($formname=="companylogo.php"){
	    	    
	    	    $temp= "CL".$_REQUEST['childid'].date('dmyHis')."A$$$";
            	$target_path = basename($_FILES['userfile']['name']);
                $target_path = "uploads/";
                $target_path = $target_path .$temp. basename( $_FILES['userfile']['name']);
                if($_FILES['userfile']['name']) $fileName= $temp.$_FILES['userfile']['name'];
                if (file_exists("uploads/".$fileName)){
                   echo "<center><STRONG>Sorry!!" .$fileName . " already exists.</center></STRONG>";
                }else{
                   move_uploaded_file($_FILES['userfile']['tmp_name'], $target_path);
                }
                                
                if($fileName!='') {
                  $SQL1 = "UPDATE ".$_SESSION['CurrentObjectName']->TableNameChild." SET docname='".$fileName."' where id=".$_REQUEST['childid'];
                  mysqli_query($con,$SQL1) or die(mysqli_error()."PA-115<br>".$SQL1);
                }
                
               	if($formname=="companylogo.php") header("location:companylogo.php?PARENTID=".$_REQUEST['txt_A_parentid']."&TYPE=".$_REQUEST['txt_A_type']);
             	
                	    
	    }
     
    if($formname=="propertyphotos.php"){
	    	    
	    	    $temp= "PP".$_REQUEST['childid'].date('dmyHis')."A$$$";
            	$target_path = basename($_FILES['userfile']['name']);
                $target_path = "uploads/";
                $target_path = $target_path .$temp. basename( $_FILES['userfile']['name']);
                if($_FILES['userfile']['name']) $fileName= $temp.$_FILES['userfile']['name'];
                if (file_exists("uploads/".$fileName)){
                   echo "<center><STRONG>Sorry!!" .$fileName . " already exists.</center></STRONG>";
                }else{
                   move_uploaded_file($_FILES['userfile']['tmp_name'], $target_path);
                }
                                
                if($fileName!='') {
                  $SQL1 = "UPDATE ".$_SESSION['CurrentObjectName']->TableNameChild." SET docname='".$fileName."' where id=".$_REQUEST['childid'];
                  mysqli_query($con,$SQL1) or die(mysqli_error()."PA-115<br>".$SQL1);
                }
                
               	if($formname=="propertyphotos.php") header("location:propertyphotos.php?ID=0&PARENTID=".$_REQUEST['txt_A_parentid']."&TYPE=".$_REQUEST['txt_A_type']);
             	
                	    
	    }
	        
    if($formname=="upload_documents.php"){

          for($i=0 ;$i < $_REQUEST['selectslots']; $i++){
                                    if($i==0){

                                      $seqNumber1 = GetLastSqeID("tbl_attachments");
                                      $temp= $seqNumber1.date('dmyHis')."$$$"; 
                                      $target_path = basename($_FILES['userfile']['name']);
                                      $target_path = "uploads/";
                                      $target_path = $target_path .$temp. basename( $_FILES['userfile']['name']);
                                      if($_FILES['userfile']['name']) $imgName= $temp.$_FILES['userfile']['name'];

                                       move_uploaded_file($_FILES['userfile']['tmp_name'], $target_path);

                                      $SQL1 = "insert into tbl_attachments(id,docid,docname,doctype)
                                               values('$seqNumber1','".$_REQUEST['childid']."','".$imgName ."','".$_REQUEST['txt_A_entitytype']."') ";
                                      if($imgName!="")
                                      mysqli_query($con,$SQL1) or die(mysqli_error()."PA-115<br>".$SQL1);

                                    }else{
                                      $field='userfile'.$i;
                                      $seqNumber1 = GetLastSqeID("tbl_attachments");
                                      $temp= $seqNumber1.date('dmyHis')."$$$"; 
                                      $target_path = basename($_FILES[$field]['name']);
                                      $target_path = "uploads/";
                                      $target_path = $target_path .$temp. basename( $_FILES[$field]['name']);
                                      if($_FILES[$field]['name']) $imgName= $temp.$_FILES[$field]['name'];

                                       move_uploaded_file($_FILES[$field]['tmp_name'], $target_path);


                                      $SQL1 = "insert into tbl_attachments(id,docid,docname,doctype)
                                               values('$seqNumber1','".$_REQUEST['childid']."','".$imgName ."','".$_REQUEST['txt_A_entitytype']."') ";
                                      if($imgName!="")
                                      mysqli_query($con,$SQL1) or die(mysqli_error()."PA-115<br>".$SQL1);

                                    }
                                }
             if($formname=="upload_documents.php")header("location:upload_documents.php?entitytype=".$_REQUEST['txt_A_entitytype']."&ID=".$_REQUEST['txt_A_docid']."");

         }
         
    // end of PostUpdateChildStatement code 

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
                $value = str_replace("^@^@^","#",$value);
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
        //echo $strSQL;exit;
        UserLog($_SESSION['CurrentObjectName']->TableNameChild,$_REQUEST['childid'],$strSQL,"UPDATE");
        $formname = $_SESSION['CurrentObjectName']->formName;
        if($formname!="addstockitems.php"){
          echo "Record Updated";
        }

   }

function UTrim($str1){
        return trim(strtoupper($str1));
}

function PostAddConditions($tblName,$RequestBuffer){
    	// sep 29 2020 ( write new code here)
    	global $con;

       	$formname = $_SESSION['CurrentObjectName']->formName;
       	
       	if($formname=="menulist.php"){ // updating menu_code
       		
       		$update_sql = "update tbl_menu set menu_code ='".$_SESSION['lastID']."',parentid='0' where id='".$_SESSION['lastID']."'";
       		mysqli_query($con,$update_sql);
            
            if(isset($_REQUEST['action'])=='save'){
                if($formname=="menulist.php")header("location:editmenulist.php?dr=edit&ID=".$_SESSION['lastID']."");
            }elseif(isset($_REQUEST['action'])=='savenew'){
                if($formname=="menulist.php")header("location:editmenulist.php?dr=add&ID=0");
            }elseif(isset($_REQUEST['action'])=='saveclose'){
                if($formname=="menulist.php")header("location:menulist.php");
            }
        }

        // end of code
        

       /* if($formname=="companysetup.php"){

              	$temp= "CS".$_SESSION['lastID'].date('dmyHis')."A$$$";
            	$target_path = $target_path.basename($_FILES['userfile']['name']);
                $target_path = "uploads/";
                $target_path = $target_path .$temp. basename( $_FILES['userfile']['name']);
                if($_FILES['userfile']['name']) $fileName= $temp.$_FILES['userfile']['name'];
                if (file_exists("uploads/".$fileName)){
                   echo "<center><STRONG>Sorry!!" .$fileName . " already exists.</center></STRONG>";
                }else{
                   move_uploaded_file($_FILES['userfile']['tmp_name'], $target_path);
                }
                  
                $temp= "CS".$_SESSION['lastID'].date('dmyHis')."B$$$";
                $target_path = $target_path.basename($_FILES['userfile1']['name']);
                $target_path = "uploads/";
                $target_path = $target_path .$temp. basename( $_FILES['userfile1']['name']);
                if($_FILES['userfile1']['name']) $fileName2= $temp.$_FILES['userfile1']['name'];
                if (file_exists("uploads/".$fileName2)){
                   echo "<center><STRONG>Sorry!!" .$fileName2 . " already exists.</center></STRONG>";
                }else{
                   move_uploaded_file($_FILES['userfile1']['tmp_name'], $target_path);
                }
                
                $temp= "CS".$_SESSION['lastID'].date('dmyHis')."C$$$";
                $target_path = $target_path.basename($_FILES['userfile2']['name']);
                $target_path = "uploads/";
                $target_path = $target_path .$temp. basename( $_FILES['userfile2']['name']);
                if($_FILES['userfile2']['name']) $fileName3= $temp.$_FILES['userfile2']['name'];
                if (file_exists("uploads/".$fileName3)){
                   echo "<center><STRONG>Sorry!!" .$fileName3 . " already exists.</center></STRONG>";
                }else{
                   move_uploaded_file($_FILES['userfile2']['tmp_name'], $target_path);
                }

                  
                if($fileName!='') {
                  $SQL1 = "UPDATE ".$_SESSION['CurrentObjectName']->TableName." SET logo1='".$fileName."' where id=".$_SESSION['lastID'];
                  mysqli_query($con,$SQL1) or die(mysqli_error()."PA-115<br>".$SQL1);
                }
                if($fileName2!='') {
                  $SQL2 = "UPDATE ".$_SESSION['CurrentObjectName']->TableName." SET logo2='".$fileName2."' where id=".$_SESSION['lastID'];
                  mysqli_query($con,$SQL2) or die(mysqli_error()."PA-115<br>".$SQL2);
                }
                if($fileName3!='') {
                  $SQL3 = "UPDATE ".$_SESSION['CurrentObjectName']->TableName." SET logo3='".$fileName3."' where id=".$_SESSION['lastID'];
                  mysqli_query($con,$SQL3) or die(mysqli_error()."PA-115<br>".$SQL3);
                }
             
             
             	if($_REQUEST['action']=='save'){
               		if($formname=="companysetup.php")header("location:editcompanysetup.php?dr=edit&ID=".$_SESSION['lastID']."&objectid=".$_SESSION['objectid']."");
             	}elseif($_REQUEST['action']=='savenew'){
               		if($formname=="companysetup.php")header("location:editcompanysetup.php?dr=add&ID=0&objectid=".$_SESSION['objectid']."");
             	}
             	elseif($_REQUEST['action']=='saveclose'){
               		if($formname=="companysetup.php")header("location:companysetup.php?objectid=".$_SESSION['objectid']."");
             	}
       }*/
    
    }

    function PostUpdateConditions($tblName,$RequestBuffer){
    	global $con;

        $formname = $_SESSION['CurrentObjectName']->formName;
        if($formname=="workflow.php"){
            if($_REQUEST['action']=='save'){
                if($formname=="workflow.php")header("location:workflow.php");
            }elseif($_REQUEST['action']=='savenew'){
                if($formname=="workflow.php")header("location:workflow.php");
            }elseif($_REQUEST['action']=='saveclose'){
                if($formname=="workflow.php")header("location:workflow.php");
            }
         }
		/*if($formname=="companysetup.php"){

              	$temp= "CS".$_REQUEST['mode'].date('dmyHis')."A$$$";
            	$target_path = $target_path.basename($_FILES['userfile']['name']);
                $target_path = "uploads/";
                $target_path = $target_path .$temp. basename( $_FILES['userfile']['name']);
                if($_FILES['userfile']['name']) $fileName= $temp.$_FILES['userfile']['name'];
                if (file_exists("uploads/".$fileName)){
                   echo "<center><STRONG>Sorry!!" .$fileName . " already exists.</center></STRONG>";
                }else{
                   move_uploaded_file($_FILES['userfile']['tmp_name'], $target_path);
                }
                  
                $temp= "CS".$_REQUEST['mode'].date('dmyHis')."B$$$";
                $target_path = $target_path.basename($_FILES['userfile1']['name']);
                $target_path = "uploads/";
                $target_path = $target_path .$temp. basename( $_FILES['userfile1']['name']);
                if($_FILES['userfile1']['name']) $fileName2= $temp.$_FILES['userfile1']['name'];
                if (file_exists("uploads/".$fileName2)){
                   echo "<center><STRONG>Sorry!!" .$fileName2 . " already exists.</center></STRONG>";
                }else{
                   move_uploaded_file($_FILES['userfile1']['tmp_name'], $target_path);
                }
                
                $temp= "CS".$_REQUEST['mode'].date('dmyHis')."C$$$";
                $target_path = $target_path.basename($_FILES['userfile2']['name']);
                $target_path = "uploads/";
                $target_path = $target_path .$temp. basename( $_FILES['userfile2']['name']);
                if($_FILES['userfile2']['name']) $fileName3= $temp.$_FILES['userfile2']['name'];
                if (file_exists("uploads/".$fileName3)){
                   echo "<center><STRONG>Sorry!!" .$fileName3 . " already exists.</center></STRONG>";
                }else{
                   move_uploaded_file($_FILES['userfile2']['tmp_name'], $target_path);
                }

                  
                if($fileName!='') {
                  $SQL1 = "UPDATE ".$_SESSION['CurrentObjectName']->TableName." SET logo1='".$fileName."' where id=".$_REQUEST['mode'];
                  mysqli_query($con,$SQL1) or die(mysqli_error()."PA-115<br>".$SQL1);
                }
                if($fileName2!='') {
                  $SQL2 = "UPDATE ".$_SESSION['CurrentObjectName']->TableName." SET logo2='".$fileName2."' where id=".$_REQUEST['mode'];
                  mysqli_query($con,$SQL2) or die(mysqli_error()."PA-115<br>".$SQL2);
                }
                if($fileName3!='') {
                  $SQL3 = "UPDATE ".$_SESSION['CurrentObjectName']->TableName." SET logo3='".$fileName3."' where id=".$_REQUEST['mode'];
                  mysqli_query($con,$SQL3) or die(mysqli_error()."PA-115<br>".$SQL3);
                }
             
             
             	if($_REQUEST['action']=='save'){
               		if($formname=="companysetup.php")header("location:editcompanysetup.php?dr=edit&ID=".$_REQUEST['mode']."&objectid=".$_SESSION['objectid']."");
             	}elseif($_REQUEST['action']=='savenew'){
               		if($formname=="companysetup.php")header("location:editcompanysetup.php?dr=add&ID=0&objectid=".$_SESSION['objectid']."");
             	}
             	elseif($_REQUEST['action']=='saveclose'){
               		if($formname=="companysetup.php")header("location:companysetup.php&objectid=".$_SESSION['objectid']."");
             	}
       }*/
       
       
      }
function GetLastSqeID($tblName){
	global $con;
       $query = "LOCK TABLES $tblName WRITE";
       mysqli_query($con,$query) or die(mysqli_error()."<br>".$query);
       $seqSQL = "SELECT max(id) as LASTNUMBER FROM $tblName";
       $result=mysqli_query($con,$seqSQL) or die(mysqli_error()."<br>".$seqSQL);
       $resulArr=mysqli_fetch_array($result);
       $updatedSeqID = $resulArr['LASTNUMBER']+1;
       $query = "UNLOCK TABLES";
       mysqli_query($con,$query) or die(mysqli_error()."<br>".$query);
       return ($updatedSeqID);
}
/*function GetLastSqeID($tblName){
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
}*/
function GetLastSqeID_1($tblName){
	global $con;
       $seqSQL = "SELECT LASTNUMBER FROM in_sequencer WHERE TABLENAME='$tblName'";
       $result=mysqli_query($con,$seqSQL) or die(mysqli_error()."<br>".$seqSQL);
       $resulArr=mysqli_fetch_array($result);
       $updatedSeqID=$resulArr['LASTNUMBER']+1;
       $squpdateSQL = "UPDATE in_sequencer SET LASTNUMBER=".$updatedSeqID." WHERE TABLENAME='$tblName'";
       mysqli_query($con,$squpdateSQL) or die(mysqli_error()."<br>".$squpdateSQL);

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

ob_end_flush();
?>
