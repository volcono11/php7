<?php
if(session_id() === "") {
   session_start();
   $sid = session_id();
}else{
   session_id($sid);
}
include "connection.php";
    $_SESSION["debug"]           = -1;
    $_SESSION['SESSuserID']           = "";
    $_SESSION['UserName']         = "";
    $_SESSION['role']             = "";
    $_SESSION['langid']           = '1';
    $_SESSION['SESSLocationCode'] = "";
    $_SESSION['UserLanguage'] = "";
    $_SESSION['SESScompanycode'] = "";
    $_SESSION['UserLocation']     = "";
    $_SESSION['FSTARTDATE']       = "";
    $_SESSION['FENDDATE']         = "";
    $_SESSION['CURRDATE']         = date('d-m-Y');
    $_SESSION['ACTUALSOID']       ="";

if($_REQUEST['auth_user_id']!=""||$_REQUEST['auth_password']!=""){
      /*if($_REQUEST['auth_remember_login']=="true"){
       setcookie("userid",$_REQUEST['auth_user_id']);
       setcookie("password",$_REQUEST['auth_password']);

      }*/


    $loginSQL    = "SELECT * from in_user where
                    userid='".$_REQUEST['auth_user_id']."' and pwd='".$_REQUEST['auth_password']."' and status='ACTIVE'";
    $loginResult = mysqli_query($con,$loginSQL) or die(mysqli_error()."<br>".$loginSQL);
    $resArray    = mysqli_num_rows($loginResult);
    if($resArray==0){
       echo "Sorry. Your password did not match the username.";
       exit();
    }
    $roles = "";
    $userroles = "";
    while($loginResultArray = mysqli_fetch_array($loginResult)) {
          $_SESSION['SESSuserID'] = $loginResultArray['userid'];
          $_SESSION['role'] = $loginResultArray['rolecode'];
          $_SESSION['username'] = $loginResultArray['username'];
          $_SESSION['SESSUserLocation'] = $loginResultArray['acclocationcode'];
          $_SESSION['UserLanguage'] = $loginResultArray['userlanguage'];
          if($loginResultArray['rolecode']!=''){
          $Htemp1     = explode(",",$loginResultArray['rolecode']);
          for($ii=0 ;$ii < count($Htemp1); $ii++){
             $roles .= "roles like '%".$Htemp1[$ii]."%' or ";
             $rolesarr[$ii]= $Htemp1[$ii];
             $userroles .= "'".$Htemp1[$ii]."',";
          }
          $_SESSION['menurole'] = substr($roles,0,strlen($roles)-3) ;
          $_SESSION['usermenurole'] = substr($userroles,0,strlen($userroles)-1) ;
         }else{
           $_SESSION['menurole']="roles='0'";
           $_SESSION['usermenurole'] = "0";
         }
   }
   $logoSQL = "select companylogo from in_companysetup as A, in_location as B where locationcode='".$_SESSION['SESSUserLocation']."'
              and A.companycode=B.companycode  ";
   $logoRES = mysqli_query($con,$logoSQL);
   $logoARR = mysqli_fetch_array($logoRES);
   $_SESSION['reportlogo'] = "companylogo/".$logoARR['companylogo'];

   echo "success";

}
?>
