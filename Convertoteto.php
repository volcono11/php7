<?php
    ob_start();
    session_start();
    include "connection.php";
    include("functions_service.php");
    require "pagingObj_crm.php";
    include("functions_workflow.php");
    date_default_timezone_set("Asia/Dubai");

    if ($_REQUEST['lid']!="") {
       $headid           =  $_REQUEST['lid'];
       $parenttype       =  $_REQUEST['parenttype'];
       $doctype          =  $_REQUEST['doctype'];
       $vouchordoc       =  $_REQUEST['vouchordoc'];

    }
    if($doctype=="QUOTE"){
    
    $updateQuoteuser = "update in_crmhead set userid='".$_REQUEST['cmb_A_userid']."' where id='".$headid."'";
    mysql_query($updateQuoteuser) or die(mysql_error()."<br>".$updateQuoteuser);
    
    // if enquiry cancelled by sales Manager same time
    $sql = mysql_query("select stcheck from in_crmhead  where id='".$headid."'");
    $arr = mysql_fetch_array($sql);
    $head_stcheck = $arr['stcheck'];
    if($head_stcheck == "Enquiry Cancelled")
    exit;
    
    }
function getticketno($parentdocno){
   $SQL = "Select parentdocno from in_crmhead where docno='".$parentdocno."'";
   $SQLRes =  mysql_query($SQL) or die(mysql_error()."<br>".$SQL);
   if(mysql_num_rows($SQLRes)>=1){
      while($loginResultArray   = mysql_fetch_array($SQLRes)){
        $DocNo =  $loginResultArray['parentdocno'];
      }
    }
    return  $DocNo;
}

    if($doctype=="ORDER"){

             $Headsql="select *,totalgrossamt,objectcode,parentdocno from in_crmhead where id='$headid'";
             $SQLRes1 =  mysql_query($Headsql) or die(mysql_error()."<br>".$Headsql);
             while($loginResultArray1   = mysql_fetch_array($SQLRes1)){
                       $ticketno=getticketno($loginResultArray1['parentdocno']);

                       $Post_query2="Update in_crmhead set leadstatus='Closed',stcheck='Client Approved',clientstatus='Client Approved' where docno='".$loginResultArray1['parentdocno']."'";
                       $Post_Result2 = mysql_query($Post_query2)   or die(mysql_error()."<br>".$Post_query2);

                       // if($loginResultArray1['enquirycategory'] == "AMC Enquiry")   {  // for AMC
                       
                       $category=9;
                       $SQL1   = "SELECT right(accountheadcode,4)*1 as count FROM in_accounthead WHERE postinggroupcode='$category' order by right(accountheadcode,4)*1 desc limit 0,1 ";
                       $Res1 =  mysql_query($SQL1) or die(mysql_error()."<br>".$SQL1);
                       if(mysql_num_rows($Res1)>=1){
                       $Array1   = mysql_fetch_array($Res1);

                           $count =  $Array1['count']+1 ;
                       }
                       $countzeros = str_pad($count, 5, "0", STR_PAD_LEFT);
                       $SQL   = "SELECT groupcode FROM in_accountgroup WHERE id='".$category."' ";
                       $SQLRes =  mysql_query($SQL) or die(mysql_error()."<br>".$SQL);
                       if(mysql_num_rows($SQLRes)>=1){
                          $loginResultArray   = mysql_fetch_array($SQLRes);
                               $groupcode=  $loginResultArray['groupcode'];
                               $str = substr($groupcode, 0, -5);
                               $ledgercode=$str.$countzeros;
                       }
                        $Post_query1="Update in_businessobject set quotes=quotes+1,quotesvalue=quotesvalue+".$loginResultArray1['totalgrossamt']." where objectcode='".$loginResultArray1['objectcode']."'";
                        $Post_Result1 = mysql_query($Post_query1)   or die(mysql_error()."<br>".$Post_query1);

                       $checkAccount   = "SELECT * FROM in_businessobject WHERE  objectcode='".$loginResultArray1['objectcode']."'";
                       $checkAccResult = mysql_query($checkAccount) or die(mysql_error()."<br>".$checkAccount);
                       if(mysql_num_rows($checkAccResult)>=1){
                            while($checkAccArray   = mysql_fetch_array($checkAccResult)){

                                  $SQL5 = "Select * from in_accounthead where (accountheadname='".$checkAccArray['objectname']."' or objectcode='".$checkAccArray['objectcode']."') and postinggroupcode='$category'";
                                  $SQLRes5 =  mysql_query($SQL5) or die(mysql_error()."<br>".$SQL5);
                                  if(mysql_num_rows($SQLRes5)==0){

                                              $clientcode = $ledgercode;
                                              $seqID = GetLastSqeID_current("in_accounthead");
                                              $insAccountSQL = "INSERT INTO in_accounthead
                                                                VALUES('$seqID','$category','$ledgercode','".$checkAccArray['objectname']."','".$checkAccArray['objectcode']."','".$checkAccArray['objectname']."','0','','','Party',
                                                                       '','','','','','','','Yes','No','$count','Active','".$checkAccArray['contactperson']."','".$checkAccArray['billingemail']."','".$checkAccArray['billingfax']."',
                                                                       '".$checkAccArray['phonecode1']."','".$checkAccArray['phonecode2']."','".$checkAccArray['billingaddress1']."',
                                                                       '','','No','".$checkAccArray['website']."','".$checkAccArray['vatid']."','','','','','','','','','','')";
                                              mysql_query($insAccountSQL) or die(mysql_error()."<br>".$insAccountSQL);

                                              $squpdateSQL = "UPDATE in_sequencer SET LASTNUMBER=LASTNUMBER+1 WHERE TABLENAME='in_accounthead'";
                                              mysql_query($squpdateSQL) or die(mysql_error()."<br>".$squpdateSQL);

                                              $squpdateSQL3 = "UPDATE in_businessobject SET accountheadcode='$ledgercode',objecttype='Customer',eccno='".$ledgercode."' WHERE objectcode='".$checkAccArray['objectcode']."'";
                                              mysql_query($squpdateSQL3) or die(mysql_error()."<br>".$squpdateSQL3);
                                  }else{
                                              $checkAccArray5   = mysql_fetch_array($SQLRes5);
                                              $clientcode = $checkAccArray5['accountheadcode'];
                                  }
                            }
                       }

                  //  }// end of code for amc customers
             }

    }

    //********head insertion starts here*********/
    $seqNumber=GetLastSqeID('in_crmhead');        // new Quotation ID

    if($doctype=="QUOTE"){

       $Headsql="select enquirycategory,docno,userid,if(enquirycategory='OT Enquiry','OT','EMG COT') as msg_enquirycategory from in_crmhead where id='$headid'";
       $HeadResult = mysql_query($Headsql) or die(mysql_error()."<br>".$Headsql);
       $result=mysql_fetch_array($HeadResult);
       $EnquiryCategory = $result['enquirycategory'];
       $APPROVAL_users = $result['userid'];
       $msg_enquirycategory = $result['msg_enquirycategory'];
       $DocNo = "Q2/".str_pad(GetLastSqeID_crm('Q2_quote'), 5, '0', STR_PAD_LEFT)."/".date('y');
       
       $parentdoctype = "LEAD";

       //service Job
       $sql_a="select * from tbl_servicejob where invheadid='$headid' and formtype='CRM' order by id";
       $res_a = mysql_query($sql_a) or die(mysql_error()."<br>".$sql_a);
       $fields=mysql_num_fields($res_a);
       while($result=mysql_fetch_array($res_a)){
       $old_sjob_id = $result['id'];
       $insertsql_a   = "insert into tbl_servicejob";
       $fieldnames  = "";
       $fieldvalues = "";

       for ($i = 0; $i < $fields; $i++)
       {
            $fieldnames=$fieldnames.mysql_field_name($res_a, $i) . ",";
       }
       $fieldnames1  =  explode(",",$fieldnames);
       $servicejobID = GetLastSqeID("tbl_servicejob");
       for($j = 0;$j < count($fieldnames1)-1; $j++)
       {          //  Update specific fields with applicable data
           if($fieldnames1[$j]=='id')
              $fieldvalues=$fieldvalues."'".$servicejobID."',";
              else if($fieldnames1[$j]=='invheadid')
                  $fieldvalues=$fieldvalues."'$seqNumber',";
              else
                 $fieldvalues=$fieldvalues."'". $result[$j]."',";
             // to insert materials for the service scope

       }
       $fieldnames="(".substr($fieldnames,0,-1).")";
       $fieldvalues="(".substr($fieldvalues,0,-1).")";

       $insertsql_a=$insertsql_a.$fieldnames."values".$fieldvalues;
       mysql_query($insertsql_a) or die("Already Converted Duplicate Entry");
       
       InsertServiceJobLine($headid,$old_sjob_id,$seqNumber,$servicejobID,'MATERIAL');
       InsertServiceJobLine($headid,$old_sjob_id,$seqNumber,$servicejobID,'MANPOWER');

       }
       
       // end of service jobs

       $SQL = "Select * from in_termsandcondition where type='QUOTE' and enquirycategory='$EnquiryCategory'";
             $SQLRes =  mysql_query($SQL) or die(mysql_error()."<br>".$SQL);
             if(mysql_num_rows($SQLRes)>=1){
                  while($loginResultArray   = mysql_fetch_array($SQLRes)){
                      $terms = $loginResultArray['termscondition'];

                    }
             }

    }   // End of Quotation
    
    if($doctype=="ORDER"){

       $Headsql="select enquirycategory,docno,docdate,userid,if(enquirycategory='OT Enquiry','OT','EMG COT') as msg_enquirycategory from in_crmhead where id='$headid'";
       $HeadResult = mysql_query($Headsql) or die(mysql_error()."<br>".$Headsql);
       $result=mysql_fetch_array($HeadResult);
       $EnquiryCategory = $result['enquirycategory'];
       $APPROVAL_users = $result['userid'];
       $msg_enquirycategory = $result['msg_enquirycategory'];

       if($EnquiryCategory =='EMG CALLOUT') $EnquiryCategory = substr($result['enquirycategory'],0,3);
       else $EnquiryCategory = substr($result['enquirycategory'],0,2);
       $DocNo = "SO$EnquiryCategory/".str_pad(GetLastSqeID_crm('Q2_salesorder'), 5, '0', STR_PAD_LEFT)."/".date('y');
       $QuotationDate = $result['docdate'];
       $parentdoctype = "QUOTE";
       
       //service Job
       $sql_a="select * from tbl_servicejob where invheadid='$headid' and formtype='CRM' order by id";
       $res_a = mysql_query($sql_a) or die(mysql_error()."<br>".$sql_a);
       $fields=mysql_num_fields($res_a);
       while($result=mysql_fetch_array($res_a)){
       $old_sjob_id = $result['id'];
       
       $insertsql_a   = "insert into tbl_servicejob";
       $fieldnames  = "";
       $fieldvalues = "";

       for ($i = 0; $i < $fields; $i++)
       {
            $fieldnames=$fieldnames.mysql_field_name($res_a, $i) . ",";
       }
       $fieldnames1  =  explode(",",$fieldnames);
       $servicejobID = GetLastSqeID("tbl_servicejob");
       for($j = 0;$j < count($fieldnames1)-1; $j++)
       {          //  Update specific fields with applicable data

           if($fieldnames1[$j]=='id')
              $fieldvalues=$fieldvalues."'".$servicejobID."',";
              else if($fieldnames1[$j]=='invheadid')
                  $fieldvalues=$fieldvalues."'$seqNumber',";
              else
                 $fieldvalues=$fieldvalues."'". $result[$j]."',";
             // to insert materials for the service scope

       }
       $fieldnames="(".substr($fieldnames,0,-1).")";
       $fieldvalues="(".substr($fieldvalues,0,-1).")";

       $insertsql_a=$insertsql_a.$fieldnames."values".$fieldvalues;
       mysql_query($insertsql_a) or die("Already Converted Duplicate Entry");
       
       InsertServiceJobLine($headid,$old_sjob_id,$seqNumber,$servicejobID,'MATERIAL');
       InsertServiceJobLine($headid,$old_sjob_id,$seqNumber,$servicejobID,'MANPOWER');

       }

       // end of service jobs
       $terms = ""; // sales terms
    }
    $docDate = date("Y-m-d");
    $insertsql   = "insert into in_crmhead";
    $fieldnames  = "";
    $fieldvalues = "";
    $status      = "Active";
    $posted      = "No";


    $Headsql="select * from in_crmhead where id='$headid'";
    $HeadResult = mysql_query($Headsql) or die(mysql_error()."<br>".$Headsql);
    $fields=mysql_num_fields($HeadResult);
    $result=mysql_fetch_array($HeadResult);
    $jobno = $result['jobno'];
    for ($i = 0; $i < $fields; $i++)
    {
        $fieldnames=$fieldnames.mysql_field_name($HeadResult, $i) . ",";
    }
    $fieldnames1  =  explode(",",$fieldnames);
    for($j = 0;$j < count($fieldnames1)-1; $j++)
    {          //  Update specific fields with applicable data
        if($fieldnames1[$j]=='id')
            $fieldvalues=$fieldvalues."'".$seqNumber."',";
            else if($fieldnames1[$j]=='doctype')
                  $fieldvalues=$fieldvalues."'". $doctype."',";
            else if($fieldnames1[$j]=='stcheck')
                  $fieldvalues=$fieldvalues."'Open',";
            else if($fieldnames1[$j]=='parentdoctype')
                  $fieldvalues=$fieldvalues."'$parentdoctype',";
            else if($fieldnames1[$j]=='docno')
                 $fieldvalues=$fieldvalues."'". $DocNo."',";
            else if($fieldnames1[$j]=='invheadid')
                 $fieldvalues=$fieldvalues."'". $headid."',";
            else if($fieldnames1[$j]=='status')
                 $fieldvalues=$fieldvalues."'". $status."',";
            else if($fieldnames1[$j]=='parentdocno')
                 $fieldvalues=$fieldvalues."'". $result['docno'] ."',";
            else if($fieldnames1[$j]=='totalquotevalue')
                 $fieldvalues=$fieldvalues."'". $result['totalgrossamt'] ."',";
            else if($fieldnames1[$j]=='posted')
                 $fieldvalues=$fieldvalues."'NO',";
            else if($fieldnames1[$j]=='estimationstatus')
                 $fieldvalues=$fieldvalues."'Open',";
            else if($fieldnames1[$j]=='paymentterms')
                 $fieldvalues=$fieldvalues."'".$terms."',";
            else
                 $fieldvalues=$fieldvalues."'". $result[$j]."',";
    }
    $fieldnames="(".substr($fieldnames,0,-1).")";
    $fieldvalues="(".substr($fieldvalues,0,-1).")";
    $insertsql=$insertsql.$fieldnames."values".$fieldvalues;
    mysql_query($insertsql) or die("Already Converted Duplicate Entry");


    //for line
    $Headsql="select * from in_crmline where invheadid='$headid' and formtype='CRM' order by id";
    $HeadResult = mysql_query($Headsql) or die(mysql_error()."<br>".$Headsql);
    $fields=mysql_num_fields($HeadResult);
    $slno=1;
    while($result=mysql_fetch_array($HeadResult)){
         $old_crmline_id = $result['id'];
         $seqNumberline=GetLastSqeID('in_crmline');
         $parentparentdocno = $result['parentdocno'];
         $parentparentparentdocno = getparentparentdocno($result['parentdocno']);
         $insertsql   = "insert into in_crmline";
         $fieldnames  = "";
         $fieldvalues = "";

         for ($i = 0; $i < $fields; $i++)
         {
             $fieldnames=$fieldnames.mysql_field_name($HeadResult, $i) . ",";
         }
         $fieldnames1  =  explode(",",$fieldnames);
         for($j = 0;$j < count($fieldnames1)-1; $j++)
         {

                         if($fieldnames1[$j]=='id')
                              $fieldvalues=$fieldvalues."'". $seqNumberline."',";
                         else if($fieldnames1[$j]=='parentdocno')
                              $fieldvalues=$fieldvalues."'". $DocNo."',";
                         else if($fieldnames1[$j]=='parentparentdocno')
                              $fieldvalues=$fieldvalues."'". $parentparentdocno."',";
                         else if($fieldnames1[$j]=='parentparentparentdocno')
                              $fieldvalues=$fieldvalues."'". $parentparentparentdocno."',";
                         else if($fieldnames1[$j]=='invheadid')
                              $fieldvalues=$fieldvalues."'". $seqNumber."',";
                         else if($fieldnames1[$j]=='doctype')
                              $fieldvalues=$fieldvalues."'". $doctype ."',";
                         else if($fieldnames1[$j]=='parenttype')
                              $fieldvalues=$fieldvalues."'". $parenttype."',";
                         else if($fieldnames1[$j]=='qoutelinegross')
                              $fieldvalues=$fieldvalues."'". $result['linegross']."',";
                         else if($fieldnames1[$j]=='slno')
                              $fieldvalues=$fieldvalues."'". $slno."',";
                         else
                              $fieldvalues=$fieldvalues."'". $result[$j]."',";

                         // to insert manpower for that service

         }

         $fieldnames="(".substr($fieldnames,0,-1).")";
         $fieldvalues="(".substr($fieldvalues,0,-1).")";
         $insertsql=$insertsql.$fieldnames."values".$fieldvalues;
         mysql_query($insertsql) or die(mysql_error()."<br>".$insertsql);
         //InsertServiceJobLine($headid,$old_crmline_id,$seqNumber,$seqNumberline,'MANPOWER');
         $slno++;
    }
    echo "Converted to ". $doctype . " with Doc No: ".$DocNo ;

    if($doctype=="QUOTE"){
       $statecheck = "Waiting for (Q2) preparation";
       
              /* // for EMG COT to Quote
               $SQL1   = "Select * from in_crmhead where id='".$headid."'";
               $SQLRes1 =  mysql_query($SQL1) or die(mysql_error()."<br>".$SQL1);
               if(mysql_num_rows($SQLRes1)>=1){
                    while($loginResultArray1   = mysql_fetch_array($SQLRes1)){
                          $objectcode=getcustomerbjcode($loginResultArray1['objectcode']);
                          $enquirycategory=$loginResultArray1['enquirycategory'];
                    }
                }
                if( $enquirycategory=="EMG CALLOUT"){
                $mySQL   = "UPDATE in_crmhead set objectcode='$objectcode' where id='$seqNumber'";
                $SQLRes =  mysql_query($SQL) or die(mysql_error()."<br>".$SQL);
                }      */

       //code for Email Communication
               $SQL1   = "Select * from in_crmhead where id='".$headid."'";
               $SQLRes1 =  mysql_query($SQL1) or die(mysql_error()."<br>".$SQL1);
               if(mysql_num_rows($SQLRes1)>=1){
                    while($loginResultArray1   = mysql_fetch_array($SQLRes1)){
                          $enqdocno = $loginResultArray1['docno'];
                          $jobname=$loginResultArray1['jobname'];
                          //$jobno=$loginResultArray1['jobno'];
                          $objectname=getcustomname($loginResultArray1['objectcode']);
                          $addtotalgrossamt= $loginResultArray1['addtotalgrossamt'];
                          $totallinenetamt=$loginResultArray1['totallinenetamt'];
                          $purchaseofficer=$loginResultArray1['purchaseofficer'];

                              if($addtotalgrossamt>0){
                                $totalprofit= $totallinenetamt-$addtotalgrossamt;
                              }else{
                                $totalprofit=0;
                              }
                    }
               }



               $SMTPsql = "SELECT concat(in_personalinfo.empfirstename,' ',in_personalinfo.emplastename) as username,in_personalinfo.empworkemail as email FROM in_personalinfo WHERE empid='".$purchaseofficer."'";
               $SMTPResult = mysql_query($SMTPsql) or die(mysql_error()."<br>".$SMTPsql);
               while($SMTParr =  mysql_fetch_array($SMTPResult)){
                     $head_email = $SMTParr['email'];
                     $head_name =  $SMTParr['username'];
               }

               $SMTPsql2 = "SELECT concat(in_personalinfo.empfirstename,' ',in_personalinfo.emplastename) as username,in_personalinfo.empworkemail as email FROM in_personalinfo WHERE empid='".$_SESSION['SESSuserID']."'";
               $SMTPResult2 = mysql_query($SMTPsql2) or die(mysql_error()."<br>".$SMTPsql2);
               while($SMTParr2 =  mysql_fetch_array($SMTPResult2)){
                     $frommail = $SMTParr2['email'];
                     $fromname = $SMTParr2['username'];
               }


    }else if($doctype=="ORDER"){
       $statecheck = "Waiting for (SO) details";
    }else{
       $statecheck = "Pending";
    }



    $SQL   = "UPDATE in_crmhead set stcheck='$statecheck', posted='YES',converted='YES',docapproveddate='".date('Y-m-d')."',posted_date='',post_to_sp_date='',convertedby='".$_SESSION['SESSuserID']."' where id='$headid'";
    $SQLRes =  mysql_query($SQL) or die(mysql_error()."<br>".$SQL);

    if($doctype=="ORDER"){
        $SQL8   = "UPDATE in_crmhead set stcheck='$statecheck',docdate='".$docDate."',clientstatus='',paymentterms='' where id='$seqNumber'";
       $SQLRes8 =  mysql_query($SQL8) or die(mysql_error()."<br>".$SQL8); //docdate='".$QuotationDate."',,objectcode='$clientcode'
       
       $SQL9   = "UPDATE in_crmhead set stcheck='$statecheck' where id='".getParentDocid($headid)."'";
       $SQLRes9 =  mysql_query($SQL9) or die(mysql_error()."<br>".$SQL9);
    }
    else if($doctype=="QUOTE"){
        $SQL8   = "UPDATE in_crmhead set stcheck='$statecheck',quotereference='$DocNo' where id='$seqNumber'";
        $SQLRes8 =  mysql_query($SQL8) or die(mysql_error()."<br>".$SQL8);
    }
    $SQL9   = "UPDATE in_crmhead set formsendto='',approvedby='',approvalcount=0,converted='NO',post_to_sp_date='".date('Y-m-d H:i:s')."',
    posted_date='',formsend_date='',parentdocid='$headid',createdon='".date('Y-m-d H:i:s')."',convertedby='' where id='$seqNumber'";
    mysql_query($SQL9);
    
    $alert_message = $statecheck.",$msg_enquirycategory Docno : ".$DocNo;
    echo SendAlerts("FM","SALES ORDER",$APPROVAL_users,$alert_message);
    echo SendSMS("FM","SALES ORDER",$APPROVAL_users,$alert_message);
    echo SendEmail("FM","SALES ORDER",$APPROVAL_users,$alert_message,$alert_message);
    
    if($jobno!="") {    // for EMG ticket
             $SQL10 = "update tbl_ticket set stcheck='$statecheck' where jobno='".$jobno."'";
             mysql_query($SQL10) or die(mysql_error()."<br>".$SQL10);
    
             $SQL2   = "UPDATE t_activitycenter set status='$statecheck' where jobno='$jobno' ";
             mysql_query($SQL2) or die(mysql_error()."<br>".$SQL2);
    }
?>

<?php
function getcustomerbjcode($code){
         $SEL =  "select objectcode from in_businessobject where accountheadcode='$code'";
         $RES = mysql_query($SEL);
         while ($ARR = mysql_fetch_array($RES)) {
               $name = $ARR['objectcode'];
         }
         return $name;
}

function getcustomname($code){
         $SEL =  "select objectname from in_businessobject where objectcode='$code'";
         $RES = mysql_query($SEL);
         while ($ARR = mysql_fetch_array($RES)) {
               $name = $ARR['objectname'];
         }
         return $name;
}

function getparentparentdocno($docno){
       $SQL1   = "SELECT parentdocno  FROM in_crmhead WHERE docno='".$docno."'";
       $Res1 =  mysql_query($SQL1) or die(mysql_error()."<br>".$SQL1);
       if(mysql_num_rows($Res1)>=1){
       $Array1 = mysql_fetch_array($Res1);
          $parentdocno =  $Array1['parentdocno'] ;
       }
       return $parentdocno;
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

function GetLastSqeID_current($tblName){
                 $seqSQL = "SELECT LASTNUMBER FROM in_sequencer WHERE TABLENAME='$tblName'";
                 $result=mysql_query($seqSQL) or die(mysql_error()."<br>".$seqSQL);
                 $resulArr=mysql_fetch_array($result);
                 $updatedSeqID=$resulArr['LASTNUMBER']+1;
                 //$squpdateSQL = "UPDATE in_sequencer SET LASTNUMBER=".$updatedSeqID." WHERE TABLENAME='$tblName'";
                 //mysql_query($squpdateSQL) or die(mysql_error()."<br>".$squpdateSQL);
                 $query = "UNLOCK TABLES";
                 mysql_query($query) or die(mysql_error()."<br>".$query);
                 return ($updatedSeqID);
}
function InsertServiceJobLine($headid,$line_id,$seqNumber,$chidid,$type){

       $sql_a1 = "select * from tbl_servicejobline where invheadid='$headid' and formtype='CRM' and initemid='$line_id' and type='".$type."'";
       $res_a1 = mysql_query($sql_a1) or die(mysql_error()."<br>".$sql_a1);
       if(mysql_num_rows($res_a1)>0) {
       
       $fields_a1=mysql_num_fields($res_a1);
       while($result_a1=mysql_fetch_array($res_a1)){

       $insertsql_a1   = "insert into tbl_servicejobline";
       $fieldnames  = "";
       $fieldvalues = "";

       for ($i = 0; $i < $fields_a1; $i++)
       {
            $fieldnames=$fieldnames.mysql_field_name($res_a1, $i) . ",";
       }
       $fieldnames1  =  explode(",",$fieldnames);
       for($j = 0;$j < count($fieldnames1)-1; $j++)
       {          //  Update specific fields with applicable data
           if($fieldnames1[$j]=='id')
              $fieldvalues=$fieldvalues."'".GetLastSqeID("tbl_servicejobline")."',";
              else if($fieldnames1[$j]=='invheadid')
                  $fieldvalues=$fieldvalues."'$seqNumber',";
              else if($fieldnames1[$j]=='initemid')
                  $fieldvalues=$fieldvalues."'$chidid',";
              else
                 $fieldvalues=$fieldvalues."'". $result_a1[$j]."',";
       }
       $fieldnames="(".substr($fieldnames,0,-1).")";
       $fieldvalues="(".substr($fieldvalues,0,-1).")";

       $insertsql_a1=$insertsql_a1.$fieldnames."values".$fieldvalues;
       mysql_query($insertsql_a1) or die("Already Converted Duplicate Entry");

       }

       }
}

function getParentDocid($headid){
   $SQL = "Select parentdocid from in_crmhead where id='".$headid."'";
   $SQLRes =  mysql_query($SQL) or die(mysql_error()."<br>".$SQL);
   if(mysql_num_rows($SQLRes)>=1){
      while($loginResultArray   = mysql_fetch_array($SQLRes)){
        $DocID =  $loginResultArray['parentdocid'];
      }
    }
    return  $DocID;
}
function GetLastSqeID_crm($tblName){
                 $query = "LOCK TABLES in_sequencer_crm WRITE";
                 mysql_query($query) or die(mysql_error()."<br>".$query);
                 $seqSQL = "SELECT LASTNUMBER FROM in_sequencer_crm WHERE TABLENAME='$tblName'";
                 $result=mysql_query($seqSQL) or die(mysql_error()."<br>".$seqSQL);
                 $resulArr=mysql_fetch_array($result);
                 $updatedSeqID=$resulArr['LASTNUMBER']+1;
                 $squpdateSQL = "UPDATE in_sequencer_crm SET LASTNUMBER=".$updatedSeqID." WHERE TABLENAME='$tblName'";
                 mysql_query($squpdateSQL) or die(mysql_error()."<br>".$squpdateSQL);
                 $query = "UNLOCK TABLES";
                 mysql_query($query) or die(mysql_error()."<br>".$query);
                 return ($updatedSeqID);
}
?>
