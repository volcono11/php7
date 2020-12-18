<?php
  include("connection.php");
  include ("class.phpmailer.php");
  include ("class.smtp.php");

function GetUpload($RequestFile,$seqID,$PostedUser){
       $result=0;
       if($RequestFile['uf']['name']!=""){
          ///////// File Upload /////////////////////////////////
          $realPath=realpath("./uploads");

          //$uploaddir = '/var/www/html/rinke/uploads/';
          $uploaddir = $realPath."/";
          $uploadfile = $uploaddir . $RequestFile['uf']['name'];
          $FileName = $seqID.'_'.$RequestFile['uf']['name'];
          $temp3=$uploaddir.$seqID.'_'.$RequestFile['uf']['name'];//  echo $temp3;exit;
          #if($_FILES['uf']['size'] > 2000000){echo $_FILES['uf']['size']; exit;}
          if($RequestFile['uf']['size']!=0){
             if (@move_uploaded_file($RequestFile['uf']['tmp_name'], $temp3)) {
                  return "YES*%*".$FileName;
             }
             else {
                  return "NO";
             }
          }
          else{
             return "NO";
          }
       }
}

 function GetWord($word){
        //return $word;    // Dictionary suspended                                             //  Temporarly suspended
        $temp = $_SESSION["dictionary"][strtoupper($word)];                   //  Get the word
        if ($temp=="" and $word != ''){
            $strSQL = "INSERT INTO tbl_dictionary (wordKey,word1,word2) values ('" . $word ."','" . $word . "','" . $word . "')";
            $result = @mysqli_query($con,$strSQL) ; //die(mysqli_error()."<br>".$strSQL);                            //  Forcing the execution to avoid duplicate error display
            $temp =   $word ; // "[" . $word . "]";                                //  If the word is empty fill with word with delimiters
            $_SESSION["dictionary"][trim($word)] = trim($word);
        }
        return $temp;                                               //  Just return
 }
 /*====>
        * Attachement array should be of this format
          $Arr = array('0'=>array('0'=>"100256_Track2.gif",'1'=>"Track2.gif"),
             '1'=>array('0'=>"100255_Track1.gif",'1'=>"Track1.gif"));
        * Calling Function Should be as follows
         Send_External_Mail('Shyam','admin@intel.com','arumilli9999@yahoo.com','This is Test Subject','Nothing',$Arr,'xx@yahoo.com,yyy@yahoo.com');
 <=====*/
 FUNCTION Send_External_Mail($From_Name,$From,$To_Arr,$Subject,$Body,$Attachment_file,$CC){

            //return "SUCCESS";
           // if(count($_SESSION['SMPT']) < 1) return "FAILED";
            #if(strtolower($_SESSION['SMPT']['ENABLEMAIL']) == 'no') return "FAILED";

            if($From=="")      $From      = $SmtpArray['SMTP']['WEBMASTEREMAIL'];//"webmaster@flightticketing.com";
            if($From_Name=="") $From_Name = $SmtpArray['SMTP']['WEBMASTEREMAIL'];

            $mail = new PHPMailer();

            /*
            $mail->IsSMTP();
            $mail->Host     = "smtp.qatar.net.qa";//"mail.b2bsoftech.com";   //209.61.192.218
            $mail->Port     = 25;# $SmtpArray['SMTP']['PORT'];//26;
            $mail->SMTPAuth =  true;
            $mail->Username = "erpradius@classicalpalace.com.qa";
            $mail->Password = "erp";
            $mail->From     = $From;
            $mail->FromName = $From_Name;
            $mail->SetLanguage("en", 'language/');


            $mail = new PHPMailer();
            $mail->IsSMTP();
            $mail->Host = "cpsdubai.com";//"mail.b2bsoftech.com"; //209.61.192.218
            $mail->Port = 25;# $SmtpArray['SMTP']['PORT'];//26;
            $mail->SMTPAuth = true;
            $mail->Username = "av";
            $mail->Password = "Software321";
            $mail->From = $From;
            $mail->FromName = $From_Name;
            $mail->SetLanguage("en", 'language/');   */

            $mail = new PHPMailer();
            $mail->IsSMTP();
            $mail->Host = "smtp.office365.com__";
            $mail->Port = 587;
            $mail->SMTPAuth = true;
            $mail->SMTPSecure = "tls";
            $mail->Username = "info@kemos.ae";
            $mail->Password = "Tat115687";
            $mail->From = $From;
            $mail->FromName = $From_Name;
            $mail->SetLanguage("en", 'language/');
            /*==>
             To Email array
            <==*/
            for($i=0; $i < count($To_Arr) ;$i++){
               $mail->AddAddress($To_Arr[$i]);
            }

            $mail->IsHTML(true);
                        //$mail->AddEmbeddedImage('logo.gif', 'http://localhost/catchmytrip/management/images/logo.gif','logo.gif');

            $mail->Subject  =  $Subject;
            $mail->Body     =   $Body;

            if($CC <> ''){
               $CC =$CC.","."php.intigen@gmail.com";
               $CCTemp = explode(',',$CC);
                 for($i=0;$i<count($CCTemp);$i++){
                       $mail->AddCC($CCTemp[$i],$CCTemp[$i]);
                 }
            }
            //echo  $CC;

            $Attachment_Arr[0] = $_SERVER["DOCUMENT_ROOT"].dirname($_SERVER["PHP_SELF"])."/pdf/".$Attachment_file.".pdf";

            for($i=0; $i < count($Attachment_Arr) ;$i++){
               $mail->AddAttachment($Attachment_Arr[$i]);
            }
           //print_r($mail);
            #print_r($Attachment_Arr);exit;
          if(!$mail->Send()){
                 #  return "FAILED";
                   echo "Mailer Error: " . $mail->ErrorInfo;
          }else{
                   return "SUCCESS";
          }


 }
Function Getmailbody($projectcode,$fromemail,$fromname,$subject,$bodymessage){
      $html="<TABLE WIDTH='500' ALIGN='center' HEIGHT='500'  cellpadding=0 cellspacing=0 style='border:2px solid #3C8DBC;'>
                   <TR HEIGHT='40' style='background-color:#3C8DBC;color:#FFFFFF;text-align:center;font-size:2.5em;font-weight:bold;'>
                    <TD COLSPAN=2 style='padding-top:10px; padding-bottom:10px; font-family:Arial, Helvetica, sans-serif; font-stretch:ultra-condensed;'>Radius-Live</TD>
                   </TR>
                                   <TR>
                    <TD colspan=2 style='padding-top:20px;'>&nbsp;</TD>
                   </TR>";
                                   if ($projectcode != "" )
                                   $html .="<TR>
                                <TD width='20%' style='FONT-SIZE: 12px; BORDER-BOTTOM: #FFFFFF 1px solid; HEIGHT: 25px;FONT-COLOR: #000000; VALIGN: Bottom;padding-bottom: 5px;
                                        padding-left:2px; font-weight:bold; FONT-FAMILY: Tahoma;'>&nbsp;Project Code </td><TD width='80%' class='msgform_data'>".$projectcode."</TD>
                   </TR>";
                                   $html .= "<TR>
                       <TD width='20%' style='FONT-SIZE: 12px; BORDER-BOTTOM: #FFFFFF 1px solid; HEIGHT: 25px;FONT-COLOR: #000000; VALIGN: Bottom;padding-bottom: 5px;
                                        padding-left:2px; font-weight:bold; FONT-FAMILY: Tahoma;'>&nbsp;From</TD>
                       <TD width='80%' style='FONT-SIZE: 12px; BORDER-BOTTOM: #FFFFFF 1px solid; HEIGHT: 25px;FONT-COLOR: #000000; VALIGN: Bottom;padding-bottom: 3px;
                                                        padding-left:2px; font-weight:normal; FONT-FAMILY: Tahoma;'>".$fromname ."</TD>
                       </TR>
                       <TR  HEIGHT='15%' valign='top'>
                        <TD style='FONT-SIZE: 12px; BORDER-BOTTOM: #FFFFFF 1px solid; HEIGHT: 25px;FONT-COLOR: #000000; VALIGN: Bottom;padding-bottom: 5px;
                                        padding-left:2px; font-weight:bold; FONT-FAMILY: Tahoma;'>&nbsp;Subject</TD>
                        <TD style='FONT-SIZE: 12px; BORDER-BOTTOM: #FFFFFF 1px solid; HEIGHT: 25px;FONT-COLOR: #000000; VALIGN: Bottom;padding-bottom: 3px;
                                                        padding-left:2px; font-weight:normal; FONT-FAMILY: Tahoma;'>".$subject."</TD>
                       </TR>
                       <TR  HEIGHT='1%' valign='top'>
                        <TD style='FONT-SIZE: 12px; BORDER-BOTTOM: #FFFFFF 1px solid; HEIGHT: 1px;FONT-COLOR: #000000; VALIGN: Bottom;padding-bottom: 5px;
                                        padding-left:2px; font-weight:bold; FONT-FAMILY: Tahoma;'>&nbsp;Message</TD>
                       </TR>
                       <TR  HEIGHT='5%' valign='top'>
                        <TD colspan=2 style='FONT-SIZE: 12px; BORDER-BOTTOM: #FFFFFF 1px solid; HEIGHT: 5px;FONT-COLOR: #000000; VALIGN: Bottom;padding-bottom: 5px;
                                        padding-left:2px; font-weight:bold; FONT-FAMILY: Tahoma;'>".$bodymessage."</TD>
                        </TR>
                       <TR  HEIGHT='80%' valign='bottom'>
                        <TD colspan=2 align='left' style='FONT-SIZE: 12px; BORDER-BOTTOM: #FFFFFF 1px solid; HEIGHT: 25px;FONT-COLOR: #000000; VALIGN: Bottom;padding-bottom: 5px;
                                        padding-left:2px; font-weight:bold; FONT-FAMILY: Tahoma;'>&nbsp;Regards,<br>
                                                &nbsp;".$fromname."<br>
                                                </TD>
                      </TR>
                      <tr><td colspan=2>&nbsp;</td></tr>
                     </TABLE>";

return $html;  #exit;
}
Function Getnotificationmail($projectcode,$fromemail,$fromname,$subject,$bodymessage){
      $html="<TABLE WIDTH='500' ALIGN='center' HEIGHT='500'  cellpadding=0 cellspacing=0 style='border:2px solid #3C8DBC;'>
                   <TR HEIGHT='40' style='background-color:#3C8DBC;color:#FFFFFF;text-align:center;font-size:2.5em;font-weight:bold;'>
                    <TD COLSPAN=2 style='padding-top:10px; padding-bottom:10px; font-family:Arial, Helvetica, sans-serif; font-stretch:ultra-condensed;'>Radius</TD>
                   </TR>
                                   <TR>
                    <TD colspan=2 style='padding-top:20px;'>&nbsp;</TD>
                   </TR>";
                                   if ($projectcode != "" )

                                   $html .= "<TR>
                       <TD width='20%' style='FONT-SIZE: 12px; BORDER-BOTTOM: #FFFFFF 1px solid; HEIGHT: 25px;FONT-COLOR: #000000; VALIGN: Bottom;padding-bottom: 5px;
                                        padding-left:2px; font-weight:bold; FONT-FAMILY: Tahoma;'>&nbsp;From</TD>
                       <TD width='80%' style='FONT-SIZE: 12px; BORDER-BOTTOM: #FFFFFF 1px solid; HEIGHT: 25px;FONT-COLOR: #000000; VALIGN: Bottom;padding-bottom: 3px;
                                                        padding-left:2px; font-weight:normal; FONT-FAMILY: Tahoma;'>".$fromname ."</TD>
                       </TR>
                       <TR  HEIGHT='15%' valign='top'>
                        <TD style='FONT-SIZE: 12px; BORDER-BOTTOM: #FFFFFF 1px solid; HEIGHT: 25px;FONT-COLOR: #000000; VALIGN: Bottom;padding-bottom: 5px;
                                        padding-left:2px; font-weight:bold; FONT-FAMILY: Tahoma;'>&nbsp;Subject</TD>
                        <TD style='FONT-SIZE: 12px; BORDER-BOTTOM: #FFFFFF 1px solid; HEIGHT: 25px;FONT-COLOR: #000000; VALIGN: Bottom;padding-bottom: 3px;
                                                        padding-left:2px; font-weight:normal; FONT-FAMILY: Tahoma;'>".$subject."</TD>
                       </TR>
                       <TR  HEIGHT='1%' valign='top'>
                        <TD style='FONT-SIZE: 12px; BORDER-BOTTOM: #FFFFFF 1px solid; HEIGHT: 1px;FONT-COLOR: #000000; VALIGN: Bottom;padding-bottom: 5px;
                                        padding-left:2px; font-weight:bold; FONT-FAMILY: Tahoma;'>&nbsp;Message</TD>
                       </TR>
                       <TR  HEIGHT='5%' valign='top'>
                        <TD colspan=2 style='FONT-SIZE: 12px; BORDER-BOTTOM: #FFFFFF 1px solid; HEIGHT: 5px;FONT-COLOR: #000000; VALIGN: Bottom;padding-bottom: 5px;
                                        padding-left:2px; font-weight:bold; FONT-FAMILY: Tahoma;'>".$bodymessage."</TD>
                        </TR>
                       <TR  HEIGHT='80%' valign='bottom'>
                        <TD colspan=2 align='left' style='FONT-SIZE: 12px; BORDER-BOTTOM: #FFFFFF 1px solid; HEIGHT: 25px;FONT-COLOR: #000000; VALIGN: Bottom;padding-bottom: 5px;
                                        padding-left:2px; font-weight:bold; FONT-FAMILY: Tahoma;'>&nbsp;Regards,<br>
                                                &nbsp;RADIUS<br><br>&nbsp;(System generated message)<br>&nbsp;
                                                </TD>
                      </TR>
                      <tr><td colspan=2>&nbsp;</td></tr>
                     </TABLE>";

return $html;  #exit;
}

FUNCTION GET_REPORT_INFO($RepID,$what){

    $sql="SELECT rname FROM tbl_reports WHERE id = '$RepID'";
    $result=mysqli_query($con,$sql) or die ("<h3>Error</h3><p><b>".mysqli_error()."</b></p>");
    $row=mysqli_fetch_array($result);
    if($what == 'NAME') return $row['rname'];
}

function Getlookname($lookcode,$type){
      $LookSQL = "select lookname from tbl_lookup where looktype='$type'
                  and lookcode='$lookcode' and lookname<>'XX'";
      $LookRes = mysqli_query($con,$LookSQL) or die(mysqli_error().'<br>8989'.$LookSQL);
      $LookArr = mysqli_fetch_array($LookRes);

      return $LookArr['lookname'];
}
FUNCTION GET_PRINT_OWNER(){

         $str = "<DIV name='generated-info' id='generated-info' style='display:none;width:100%;'>
                      <TABLE border='0' cellpadding='0' cellspacing='0' width='100%'>
                          <TR>
                              <TD  class='HeadCol-LAlign' width='15%'>User</TD>
                              <TD  class='HeadCol-LAlign' width='35%'>".$_SESSION['UserName']."</TD>
                              <TD  class='HeadCol-LAlign' width='15%'>Date</TD>
                              <TD  class='HeadCol-LAlign' width='35%'>".date('d-m-Y')."</TD>
                          </TR>
                      </TABLE>
                  </DIV>";

         return $str;
}

function currentURL() {
         $pageURL = 'http';
         if ($_SERVER["HTTPS"] == "on") {$pageURL .= "s";}
         $pageURL .= "://";
         if ($_SERVER["SERVER_PORT"] != "80") {
          $pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
         } else {
          $pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
         }

            $File_name = substr($_SERVER["SCRIPT_NAME"],strrpos($_SERVER["SCRIPT_NAME"],"/")+1);
        $File_pos = strpos($pageURL,$File_name);
            return substr($pageURL,0,$File_pos);

}
function GET_MOBILENO($rolecode,$RequestNo,$companycode)  {
                         $Where = "";
                         if($rolecode == "LOCAL HR") $Where = " AND companycode='$companycode' ";
                         $Sql = "select mobile from tbl_user where rolecode = '".$rolecode."' ".$Where;
                         $Res = mysqli_query($con,$Sql) or die(mysqli_error().$Sql);
                         $Arr = mysqli_fetch_array($Res);
                         return $Arr['mobile'];
}

Function GetRequestmail($requestno,$username,$Cname,$doj,$Help_line){
      //$nameArr = explode('*',$Name);
          $sql = "select title,concat(firstname,' ',middlename,' ',lastname) as name,passportno,contactnumber from tbl_passenger where reqno='".$requestno."'";
          $res = mysqli_query($con,$sql);
          $Count = 0;
          $TBL = "<table  width=100% style='border-bottom:1px solid #7A2900;border-top:1px solid #7A2900;border-right:1px solid #7A2900;border-left:1px solid #7A2900;' >
                                  <tr style='color:#EEEEEE;font-weight:bold;' align='center' bgcolor='#7A2900'><td colspan=4>Passenger Details</td>
                                </tr>
                                <tr style='color:#7A2900;font-weight:bold;' bgcolor='#eeeeee'>
                                  <td>Title</td><td>Name</td><td>Passport number</td><td>Contact number</td>
                                </tr>";
          while($arr = mysqli_fetch_array($res)) {
                          $TBL .= "<tr style='color:#000000;'>
                                                <td>".$arr['title']."</td><td>".$arr['name']."</td><td>".$arr['passportno']."</td><td>".$arr['contactnumber']."</td>
                                         </tr>";
                          $Count ++;
          }
          $TBL .= "</table>";

      $html="<table width='100%' border='0'>
               <tr>
                <td></td>
                <td></td>
                <td></td>
               </tr>
               <tr>
               <td></td>
                <td>
                <br>
                <TABLE BORDER='o' WIDTH='500' ALIGN='center' HEIGHT='500'  style='border:2px solid #7A2900;' cellpadding=0 cellspacing=0>
               <TR HEIGHT='70' style='background-color:#7A2900;color:#FFFFFF;text-align:center;font-size:1.5em;font-weight:bold;'>
               <TD>Catch My Trip</TD>
               </TR>
               <TR style='background-color:#E7E7DE;color:#7A2900;vertical-align:top'>
               <TD>
                <TABLE BORDER='0' WIDTH='100%' ALIGN='center' cellpadding=5 cellspacing=1 style='font-size:0.95em; '>
                        <TR   valign='top'>
                         <TD width='30%' style='background-color:#E7E7DE;color:#7A2900;font-weight:bold;'>REQUEST NO:</TD>
                         <TD width='30%' style='color:#7A2900;font-weight:bold;'>$requestno</TD>
                         <TD width='40%'>&nbsp;</TD>
                        </TR>
                        <TR  valign='top'>
                                <TD width='30%' style='color:#7A2900;font-weight:bold;'>Name</TD>
                                <TD colspan=2>$username</TD>
                        </TR>
                        <TR valign='top'>
                                <TD width='30%' style='color:#7A2900;font-weight:bold;'>Company Name</TD>
                                <TD  colspan=2>$Cname</TD>
                        </TR>
                        <TR  valign='top'>
                                <TD width='30%' style='color:#7A2900;font-weight:bold;'>Date Of Journey</TD>
                                <TD width='30%'>$doj</TD>
                                <TD width='40%'>&nbsp;</TD>
                        </TR>
                                                <TR   valign='top'>
                                <TD width='30%' style='color:#7A2900;font-weight:bold;'>No.of Passengers</TD>
                                <TD width='30%'>$Count</TD>
                                <TD width='40%'>&nbsp;</TD>
                        </TR>
                                                <TR  valign='top'>
                                <TD width='30%' style='color:#7A2900;font-weight:bold;' colspan=3>".$TBL."</TD>
                        </TR>
                                                <TR  valign='top'>
                                <TD width='30%' style='color:#7A2900;' colspan=3>$Help_line</TD>

                        </TR>

                                                <TR  valign='bottom'>
                                <TD width='30%' style='color:#7A2900;font-weight:bold;' colspan=3 align='left'>Regards,<br>CATCH MY TRIP TEAM<br>(System generated message)</TD>
                        </TR>

                </TABLE>
              </TD>
           </TR>
           <TR  HEIGHT='30' style='background-color:#7A2900;color:#FFFFFF;text-align:center;font-weight:bold;'>
            <TD>&nbsp;</TD>
           </TR>
      </TABLE>
        </td>
          <td></td>
     </tr>
     <tr>
      <td></td>
      <td></td>
      <td></td>
     </tr>
</table>";
return $html;

}


        ###################################### Email Functionality #######################################
        ### ADDED BY : PAVANI
        ### DATE ON : 25 MAY 2012
        function GetEmailAddress($UserID) {
                        $SQL = "select rolecode,email,ccmail from in_user where userid='$UserID' order by username";
                        $RES = mysqli_query($con,$SQL);
                        $ARR = mysqli_fetch_array($RES);#
                        return $ARR['rolecode']."***".$ARR['email']."***".$ARR['ccmail'];
        }
        ########################################## END OF CODE ##############################################
?>