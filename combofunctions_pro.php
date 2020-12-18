<?php
session_start();
//error_reporting(0);
include "connection.php";
$category = isset($_REQUEST['categorytype']) ? $_REQUEST['categorytype'] : '';
?>

<?php
if($_REQUEST['level']=="SADDRESS"){
         $SEL =  "select name,address from in_store where name='$category' and locationcode='".$_SESSION['SESSUserLocation']."' ";
         $RES = mysql_query($SEL);
         $ARR = mysql_fetch_array($RES);
         $CMB = $ARR['address'];
         echo $CMB;
         exit;
}
if($_REQUEST['level']=="articlename"){

             $SQL = "select * from in_articles where articlename='".$_REQUEST['articlename']."' and locationcode='".$_SESSION['SESSUserLocation']."'";
             $SQLRes =  mysql_query($SQL) or die(mysql_error()."<br>".$SQL);
             if(mysql_num_rows($SQLRes)>=1){
                echo "YES";
             }
             exit;
}
if($_REQUEST['level']=="documents"){

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

if($_REQUEST['level']=="purchasedoc"){

    if($_REQUEST['docname']=="")$_REQUEST['docname']="invalid.jpg";

   // $docname= str_replace(" ","%20",$_REQUEST['docname']);
   //  echo $_REQUEST['docname'];
    if($_REQUEST['ext']!=""){
       if($_REQUEST['ext']=="docx" || $_REQUEST['ext']=="xlsx"){
          echo "<iframe src='http://docs.google.com/gview?url=uploads/".$_REQUEST['docname']."&embedded=true' width='800px' height='600px' frameborder='0' ></iframe>";
       }elseif($_REQUEST['ext']=="dwg"){
        echo "<iframe src='//sharecad.org/cadframe/load?url=http://radiustest.cpsdubai.com/KEMOS/uploads/".$_REQUEST['docname']."' width='800px' height='600px'  frameborder='0'></iframe>";

       }else{
          echo "<iframe src='uploads/".$_REQUEST['docname']."' width='800px' height='500px'   frameborder='0'></iframe>";
       }

    }else{
       echo "";
    }

}
if($_REQUEST['level']=="purchasedoc2"){

    if($_REQUEST['docname']=="")$_REQUEST['docname']="invalid.jpg";

   // $docname= str_replace(" ","%20",$_REQUEST['docname']);
   //  echo $_REQUEST['docname'];
    if($_REQUEST['ext']!=""){
       if($_REQUEST['ext']=="docx" || $_REQUEST['ext']=="xlsx"){
          echo "<iframe src='http://docs.google.com/gview?url=procurement/".$_REQUEST['docname']."&embedded=true' width='800px' height='600px' frameborder='0' ></iframe>";
       }elseif($_REQUEST['ext']=="dwg"){
        echo "<iframe src='//sharecad.org/cadframe/load?url=http://radiustest.cpsdubai.com/KEMOS/uploads/".$_REQUEST['docname']."' width='800px' height='600px'  frameborder='0'></iframe>";

       }else{
          echo "<iframe src='procurement/".$_REQUEST['docname']."' width='800px' height='500px'   frameborder='0'></iframe>";
       }

    }else{
       echo "";
    }

}
if($_REQUEST['level']=="SUPPLIERSTOCKAVAILABILITY"){

         $CMB = "<select name='cmb_A_availabilitytype'  id='cmb_A_availabilitytype' class='form-control select'>";
         $CMB .= "<option value=''></option>";
         $SEL =  "select lookcode,lookname from in_lookup_head where looktype='$category' and lookname<>'YY' order by slno";
         $RES = mysql_query($SEL);
          while ($ARR = mysql_fetch_array($RES)) {
             $CMB .= "<option value='".$ARR['lookcode']."' >".$ARR['lookname']."</option>";
          }
         $CMB .= "</select>";
         echo $CMB;
         exit;
}
if($_REQUEST['level']=="availtype"){

         $CMB = "<select name='cmb_A_availabilitytype'  id='cmb_A_availabilitytype' class='form-control select'>";
         $CMB .= "<option value=''></option>";
         $SEL =  "select lookcode,lookname from in_lookup_head where looktype='$category' and lookname<>'YY' order by slno";
         $RES = mysql_query($SEL);
          while ($ARR = mysql_fetch_array($RES)) {
             $CMB .= "<option value='".$ARR['lookcode']."' >".$ARR['lookname']."</option>";
          }
         $CMB .= "</select>";
         echo $CMB;
         exit;
}
if($_REQUEST['level']=="activity"){

         $CMB = "<select name='cmb_A_costsubgroup'  id='cmb_A_costsubgroup' class='form-control select'>";
         $CMB .= "<option value=''></option>";
         $SEL =  "select categorycode,categoryname from in_costgroup where catgencode='$category' order by categoryname";
         $RES = mysql_query($SEL);
          while ($ARR = mysql_fetch_array($RES)) {
             $CMB .= "<option value='".$ARR['categorycode']."' $SEL >".$ARR['categoryname']."</option>";
          }
         $CMB .= "</select>";
         echo $CMB;
         exit;
}
if($_REQUEST['level']=="subcategory"){

         $CMB = "<select name='cmb_A_subcategory'  id='cmb_A_subcategory' class='form-control select'>";
         $CMB .= "<option value=''></option>";
         $SEL =  "select categorycode,categoryname from in_productcategory where catgencode='$category' order by categoryname";
         $RES = mysql_query($SEL);
          while ($ARR = mysql_fetch_array($RES)) {
             $CMB .= "<option value='".$ARR['categorycode']."' $SEL >".$ARR['categoryname']."</option>";
          }
         $CMB .= "</select>";
         echo $CMB;
         exit;
}
if($_REQUEST['level']=="LPODETAILS"){

            $SQL = "Select jobno,objectcode,store from in_inventoryhead where docno ='".$category."'";
            $SQLRes =  mysql_query($SQL) or die(mysql_error()."<br>".$SQL);
            if(mysql_num_rows($SQLRes)>=1){
                while($loginResultArray   = mysql_fetch_array($SQLRes)){
                  $LPODETAILS = $loginResultArray['jobno']."#".$loginResultArray['objectcode']."#".getobjectname($loginResultArray['objectcode'])."#".$loginResultArray['store'];
                }
            }
            echo $LPODETAILS ;
}
if($_REQUEST['level']=="CCDetails"){
            $SQL = "Select jobno,jobname from t_activitycenter where store='".$category."' and locationcode='".$_SESSION['SESSUserLocation']."' order by id";

            $SQLRes =  mysql_query($SQL) or die(mysql_error()."<br>".$SQL);

            //$CMB .= "<option value=''></option>";
            while ($ARR = mysql_fetch_array($SQLRes)) {
                   $CMB .= "<option value='".$ARR['jobno']."'  >".$ARR['jobno']."</option>";
            }
            $CMB .= "</select>";
            echo $CMB;
}
if($_REQUEST['level']=="MRDETAILS"){
           $SQL = "Select projectstore,t_activitycenter.locationcode,b.division,
                    a.lookname as projectstorename,in_location.cy_ename,t_activitycenter.jobname,
                    buildingname,propertyname,floordetails
                    from t_activitycenter
                    inner join in_lookup as a on t_activitycenter.projectstore = a.lookcode
                    inner join in_location on t_activitycenter.locationcode = in_location.locationcode
                    inner join in_locationdivision as b on t_activitycenter.division = b.code
                    where jobno ='".$category."'";

            $SQLRes =  mysql_query($SQL) or die(mysql_error()."<br>".$SQL);
            if(mysql_num_rows($SQLRes)>=1){
                while($loginResultArray   = mysql_fetch_array($SQLRes)){
                  $MRDETAILS = $loginResultArray['projectstore']."#".$loginResultArray['locationcode']."#".$loginResultArray['division']."#".$loginResultArray['projectstorename']."#".$loginResultArray['cy_ename']."#".getdivisionname($loginResultArray['division'])."#".$loginResultArray['jobname']."#".$projectincharge;
                  $MRDETAILS = $MRDETAILS ."#". $loginResultArray['propertyname']."#".getpropertyname($loginResultArray['propertyname'])."#".$loginResultArray['buildingname']."#".getbuildingname($loginResultArray['buildingname'])."#".$loginResultArray['floordetails'];

                }
            }

            echo $MRDETAILS;
}
function getpropertyname($divisioncode) {
            $SQL = "select type from tbl_properties where code='$divisioncode'";
            $SQLRes =  mysql_query($SQL) or die(mysql_error()."<br>".$SQL);
              if(mysql_num_rows($SQLRes)>=1){
                while($loginResultArray   = mysql_fetch_array($SQLRes)){
                  $type = $loginResultArray['type'];
                }
              }
             return $type ;
}
function getbuildingname($divisioncode) {
            $SQL = "select name from tbl_properties where code='$divisioncode'";
            $SQLRes =  mysql_query($SQL) or die(mysql_error()."<br>".$SQL);
              if(mysql_num_rows($SQLRes)>=1){
                while($loginResultArray   = mysql_fetch_array($SQLRes)){
                  $name = $loginResultArray['name'];
                }
              }
             return $name ;
}

function getdivisionname($divisioncode) {
            $SQL = " Select in_lookup.lookname from in_lookup where lookcode ='".$divisioncode."'";
            $SQLRes =  mysql_query($SQL) or die(mysql_error()."<br>".$SQL);
              if(mysql_num_rows($SQLRes)>=1){
                while($loginResultArray   = mysql_fetch_array($SQLRes)){
                  $id = $loginResultArray['lookname'];
                }
              }
             return $id ;
}
function getobjectname($objectcode) {
            $SQL = "select accountheadcode,accountheadname from in_accounthead where
                    ledgertype='Party' and left(accountheadcode,1)='2' and accountheadcode='".$objectcode."'";
            $SQLRes =  mysql_query($SQL) or die(mysql_error()."<br>".$SQL);
              if(mysql_num_rows($SQLRes)>=1){
                while($loginResultArray   = mysql_fetch_array($SQLRes)){
                  $accountheadname = $loginResultArray['accountheadname'];
                }
              }
             return $accountheadname ;
}

?>

