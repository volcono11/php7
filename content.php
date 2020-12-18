<?php
if(session_id() === "") {
   session_start();
   $sid = session_id();
}else{
   session_id($sid);
}
include "connection.php";
if(isset($_REQUEST['Module'])!=""){
	$usermenurole ="";
	$sql13 = "select * from tbl_usermodule left join in_lookup on in_lookup.lookcode=tbl_usermodule.modulecode where userid='".$_SESSION["SESSuserID"]."' and modulecode='".$_REQUEST['Module']."'";
	$res13 = mysqli_query($con,$sql13);
	while($arr13 = $res13->fetch_array()){
		$usermenurole .= $arr13['usergroup'].",";
	}
	$usermenurole= substr($usermenurole,0,-1) ;
	$_SESSION["usermenurole"] = $usermenurole;
	$_SESSION['usermodulecode'] = $_REQUEST['Module'];
}
else{
	$usermenurole = $usermodule = "";
	$sql13 = "select * from tbl_usermodule left join in_lookup on in_lookup.lookcode=tbl_usermodule.modulecode where userid='".$_SESSION["SESSuserID"]."'  order by tbl_usermodule.id limit 0,1";
	$res13 = mysqli_query($con,$sql13);
	while($arr13 = $res13->fetch_array()){
		$usermenurole .= $arr13['usergroup'].",";
		$usermodule = $arr13['lookcode'].",";
	}
	$usermenurole= substr($usermenurole,0,-1) ;
	$_SESSION['usermodulecode'] = substr($usermodule,0,-1) ;;
	
	//if($_SESSION["SESSuserID"]!="SUPER" && $_SESSION["SESSuserID"]!="dev")
	
    $_SESSION["usermenurole"] = $usermenurole;
	
}
if($_SESSION["role"] == "CLIENT"){
	$staffphoto ="clientphoto/dummyprofile.png";
	$SQL = "Select clientphoto as staffphoto from in_project where projectcode='".$_SESSION["SESSuserID"]."' ";
	             $SQLRes =  mysqli_query($con,$SQL) or die(mysqli_error()."<br>".$SQL);
	              if(mysqli_num_rows($SQLRes)>=1){
	                 while($loginResultArray   = mysqli_fetch_array($SQLRes)){
	                    if($loginResultArray['staffphoto']!=""){
	                      $photo=$loginResultArray['staffphoto'];
	                      $staffphoto ="clientphoto/".$photo."";
	                    }else{
	                      $photo="dummyprofile.png";
	                      $staffphoto ="clientphoto/".$photo."";
	                    }
	                }
	              }	
}
else{
$staffphoto ="staffphoto/dummyprofile.png";
$SQL = "Select empimage as staffphoto,id from in_personalinfo where empid='".$_SESSION["SESSuserID"]."' ";
             $SQLRes =  mysqli_query($con,$SQL) or die(mysqli_error()."<br>".$SQL);
              if(mysqli_num_rows($SQLRes)>=1){
                 while($loginResultArray   = mysqli_fetch_array($SQLRes)){
                    if($loginResultArray['staffphoto']!=""){
                      $photo=$loginResultArray['staffphoto'];
                      //$photo="dummyprofile.png";
                      $staffphoto ="staffphoto/".$photo."";
                    }else{
                      $photo="dummyprofile.png";
                      $staffphoto ="staffphoto/".$photo."";
                    }
                }
              }
              $staffphoto ="staffphoto/dummyprofile.png";
}
$accountsalertdisplay = null; 
$accountsalert = NULL;   
$sub_contract_alerts =''            ;
if(stripos(json_encode($_SESSION['role']),'SUPER') !== false) {
   // echo "found mystring";
}

date_default_timezone_set("Asia/Dubai");

   if(stripos(json_encode($_SESSION['role']),'OPERATIONS MANAGER') == true || stripos(json_encode($_SESSION['role']),'SALES') == true || stripos(json_encode($_SESSION['role']),'SERVICE') == true || stripos(json_encode($_SESSION['role']),'SITE INCHARGE') == true || stripos(json_encode($_SESSION['role']),'PURCHASE') == true || stripos(json_encode($_SESSION['role']),'FINANCE MANAGER') == true || stripos(json_encode($_SESSION['role']),'FRONT OFFICE CLERK') == true || stripos(json_encode($_SESSION['role']),'FACILITY MANAGER') == true || stripos(json_encode($_SESSION['role']),'HR MANAGER') == true){
     // $SQLacc2 = " SELECT COUNT(*) as count FROM tbl_alerts WHERE sendto='".$_SESSION['SESSuserID']."' order by id desc";
     // inactivate the contract after the enddate
     echo InactiveContarct($con);
     // end of code
     
     // inactivate the subcontract after the enddate
     echo InactiveSubContarct($con);
     // end of code
     
      $SQL = "SELECT count(*) as count
FROM t_activitycenter WHERE DATEDIFF(if((t_activitycenter.extendedto='' or t_activitycenter.extendedto='0000-00-00'),t_activitycenter.expenddate,
t_activitycenter.extendedto),NOW())<=30 and t_activitycenter.activitycenter='CONTRACT' and status='Active'";
      $SQLRes =  mysqli_query($con,$SQL) or die(mysqli_error()."<br>".$SQL);
      if(mysqli_num_rows($SQLRes)>=1){
        while($loginResultArray   = mysqli_fetch_array($SQLRes)){
            $contract_alerts=$loginResultArray['count'];

        }
      }
      
    if(stripos(json_encode($_SESSION['role']),'SERVICE COORDINATOR') == true) {
      $SQL_2 = "SELECT count(*) as count FROM tbl_subcontract WHERE DATEDIFF(tbl_subcontract.subcontractenddate,NOW())<=30  and contractstatus<>'Expired'";
      $SQLRes_2 =  mysqli_query($con,$SQL_2) or die(mysqli_error()."<br>".$SQL_2);
      if(mysqli_num_rows($SQLRes_2)>=1){
        while($loginResultArray_2  = mysqli_fetch_array($SQLRes_2)){
             $sub_contract_alerts=$loginResultArray_2['count'];

        }
      }
	}
      
      $SQLacc2 = " SELECT count(*) as count FROM tbl_alerts WHERE sendto='".$_SESSION['SESSuserID']."' and  viewedby not like '%".$_SESSION['SESSuserID']."%' order by id desc";   // date_format(senddate,'%Y-%m-%d')='".date('Y-m-d')."'
      $SQLResacc2 =  mysqli_query($con,$SQLacc2) or die(mysqli_error()."<br>".$SQLacc2);
      if(mysqli_num_rows($SQLResacc2)>=1){
        while($loginResultArrayacc2   = mysqli_fetch_array($SQLResacc2)){
              $accountsalert =$loginResultArrayacc2['count'];
        }
      }
      
      if($accountsalert>0 || $contract_alerts >0 || $sub_contract_alerts >0){
      	     $total_alerts = $accountsalert+$contract_alerts+$sub_contract_alerts;
             $accountsalertdisplay="<span class='badge badge-important' id='alerthid'>".$total_alerts."</span>";
      }else{
             $accountsalertdisplay="";
      }
    }else if(stripos(json_encode($_SESSION['role']),'SUPERVISOR') == true){

      $SQLacc2 = " SELECT COUNT(*) as count FROM tbl_ticket WHERE requeststatus='Open' and DATE_FORMAT(requestdate,'%Y-%m-%d')<= '".date('Y-m-d')."' and servicestaffid='".$_SESSION['SESSuserID']."'";
      $SQLResacc2 =  mysqli_query($con,$SQLacc2) or die(mysqli_error()."<br>".$SQLacc2);
      if(mysqli_num_rows($SQLResacc2)>=1){
        while($loginResultArrayacc2   = mysqli_fetch_array($SQLResacc2)){
              $accountsalert =$loginResultArrayacc2['count'];


        }
      }


           if($accountsalert>0){
             $accountsalertdisplay="<span class='badge badge-important' id='alerthid'>$accountsalert</span> ";
           }else{
             $accountsalertdisplay="";
           }

    }

      $SQL = "SELECT count(*) as count FROM tbl_documents WHERE DATEDIFF(expirydate,NOW())<=30 AND expirydate <>'0000-00-00'  ORDER BY expirydate";
      $SQLRes =  mysqli_query($con,$SQL) or die(mysqli_error()."<br>".$SQL);
      if(mysqli_num_rows($SQLRes)>=1){
        while($loginResultArray   = mysqli_fetch_array($SQLRes)){
            $docalert=$loginResultArray['count'];

        }
      }



      $SQL2 = "Select count(*) as count from in_personalinfo where DATE_FORMAT(empdob,'%d-%m')='".date('d-m')."' and (empstatus='Active' or empstatus='Probation')";
      $SQLRes2 =  mysqli_query($con,$SQL2) or die(mysqli_error()."<br>".$SQL2);
      if(mysqli_num_rows($SQLRes2)>=1){
        while($loginResultArray2   = mysqli_fetch_array($SQLRes2)){
            $birthalert=$loginResultArray2['count'];

        }
      }
            $notification= $birthalert+$docalert+$accountsalert;

           if($notification>0){
             $notificationdisplay="<span class='badge badge-important' id='alerthid'>$notification</span> ";
           }else{
             $notificationdisplay="";
           }
?>
<!DOCTYPE html>
<html lang="en">
        <head>
                <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
                <meta charset="utf-8" />
                <title>Radius 7</title>

                <meta name="description" content="" />
                <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />

                <!-- bootstrap & fontawesome -->
                <link rel="stylesheet" href="assets/css/bootstrap.min.css" />
                <link rel="stylesheet" href="assets/font-awesome/4.5.0/css/font-awesome.min.css" />

                <!-- page specific plugin styles -->

                <!-- text fonts -->
                <link rel="stylesheet" href="assets/css/fonts.googleapis.com.css" />

                <!-- ace styles -->
                <link rel="stylesheet" href="assets/css/ace.min.css" class="ace-main-stylesheet" id="main-ace-style" />

                <!--[if lte IE 9]>
                        <link rel="stylesheet" href="assets/css/ace-part2.min.css" class="ace-main-stylesheet" />
                <![endif]-->
                <link rel="stylesheet" href="assets/css/ace-skins.min.css" />
                <link rel="stylesheet" href="assets/css/ace-rtl.min.css" />

                <!--[if lte IE 9]>
                  <link rel="stylesheet" href="assets/css/ace-ie.min.css" />
                <![endif]-->

                <!-- inline styles related to this page -->

                <!-- ace settings handler -->
                <script src="assets/js/ace-extra.min.js"></script>


                <script src="js/jquery-1.3.2.js"></script>
                <link rel="stylesheet" href="css/alertify.core.css" />
                <link rel="stylesheet" href="css/alertify.default.css" id="toggleCSS" />
                <link rel="stylesheet" href="bootstrap/css/datepicker.css">
                <script src="js/html5shiv.min.js"></script>
                <script src="js/respond.min.js"></script>
                <script src="js/alertify.min.js"></script>

        </head>

        <body class="no-skin">
                <div id="navbar" class="navbar navbar-fixed-top">
                        <div class="navbar-container ace-save-state" id="navbar-container">
                                <button type="button" class="navbar-toggle menu-toggler pull-left" id="menu-toggler" data-target="#sidebar">
                                        <span class="sr-only">Toggle sidebar</span>

                                        <span class="icon-bar"></span>

                                        <span class="icon-bar"></span>

                                        <span class="icon-bar"></span>
                                </button>

                                <div class="navbar-header pull-left">
                                    <img style='padding-top:3px;' align='left' src="img/newlogo1.png" />
                                </div>
                                
                                <div class="navbar-buttons navbar-header pull-right" role="navigation">
                                <ul class="nav ace-nav"> 
                              <?php   if(stripos(json_encode($_SESSION['role']),'HR MANAGER') == true) {   ?>



                                           <li class="blue dropdown-modal">
                                                        <a data-toggle="dropdown" class="dropdown-toggle" href="#"  onclick='alerthidemain();'>
                                                                <i class="ace-icon fa fa-bell icon-animated-bell" style="color: #FFFFFF"></i>
                                                                <?php echo $notificationdisplay;?>
                                                        </a>

                                                        <ul class="dropdown-menu-right dropdown-navbar navbar-pink dropdown-menu dropdown-caret dropdown-close">
                                                                <li class="dropdown-header">
                                                                        <i class="ace-icon fa fa-exclamation-triangle"></i>
                                                                        You have <?php echo $notification;?> HR notification.
                                                                </li>

                                                                <li class="dropdown-content">
                                                                        <ul class="dropdown-menu dropdown-navbar navbar-pink">
                                                                                 <li>
                                                                                        <a href="alertdetails.php?page=birth" target="main" onclick='alerthide1();'>
                                                                                                <div class="clearfix">
                                                                                                        <span class="pull-left">
                                                                                                                <i class="btn btn-xs no-hover btn-info fa fa-gift"></i>
                                                                                                                Todays Birthday
                                                                                                        </span>
                                                                                                        <span class="pull-right badge badge-info" id='alerthid1'><?php echo $birthalert;?></span>
                                                                                                </div>
                                                                                        </a>
                                                                                </li>






                                                                                <li>
                                                                                        <a href="alertdetails.php?page=doc" target="main">
                                                                                                <div class="clearfix">
                                                                                                        <span class="pull-left">
                                                                                                                <i class="btn btn-xs no-hover btn-pink fa fa-book"></i>
                                                                                                                Document Expiry in 30 days
                                                                                                        </span>
                                                                                                        <span class="pull-right badge badge-info"><?php echo $docalert; ?></span>
                                                                                                </div>
                                                                                        </a>
                                                                                </li>
                                                               <li class="dropdown-header">
                                                                        <div class='clearfix'>
                                                                        <span class='pull-left' style='width:20px;'>
                                                                        <i class="ace-icon fa fa-exclamation-triangle"></i>&nbsp;&nbsp;   </span>

                                                                        <span class='pull-left'>
                                                                      <a href='myalerts.php?type=ALERTS' target='main'> 
                                                                            Purchase Alerts <span id="msgdiv1"><?php echo $accountsalert;?></span>&nbsp; 
                                                                      </a>
                                                                        </span>

                                                                        </div>
                                                                </li>
                                                                        </ul>
                                                                </li>


                                                        </ul>
                                                </li>
                                                 <?php } ?>
                                                <li class="light-blue dropdown-modal">
                                                        <a data-toggle="dropdown" href="#" class="dropdown-toggle">
                                                                <img class="nav-user-photo" src="<?php echo $staffphoto;?>" alt="<?php echo $_SESSION['username'];?>" />
                                                                
                                                                       <font color="#FFFFFF"> <small>Welcome,</small>
                                                                        <?php echo $_SESSION['username'];?>
                                                                

                                                                <i class="ace-icon fa fa-caret-down"></i></font>
                                                        </a>

                                                        <ul class="user-menu dropdown-menu-right dropdown-menu dropdown-yellow2 dropdown-caret dropdown-close">

                                                                <li>
                                                                        <a href="roleuserlist.php" target='main' >
                                                                                <i class="ace-icon fa fa-user"></i>
                                                                                My Account
                                                                        </a>
                                                                </li>

                                                                <li class="divider"></li>

                                                                <li>
                                                                        <a href="index.php">
                                                                                <i class="ace-icon fa fa-power-off"></i>
                                                                                Logout
                                                                        </a>
                                                                </li>
                                                        </ul>
                                                </li>
                                        </ul>
                                </div>
                        </div>
                        <div class="navbar-buttons navbar-header pull-right" role="navigation">
                                        <ul class="nav ace-nav">
										<li class="nav-item"> <!-- Modules-->
        										<a data-toggle="dropdown" href="#" class="dropdown-toggle">
        										Modules&nbsp; <i class="fa fa-cubes"></i></a>
        										<ul class="user-menu dropdown-menu-right dropdown-menu dropdown-info dropdown-caret dropdown-close">
        										<?php
        										$sql12 = "select * from tbl_usermodule left join in_lookup on in_lookup.lookcode=tbl_usermodule.modulecode where userid='".$_SESSION["SESSuserID"]."'";
        										$res12 = mysqli_query($con,$sql12);
        										$num_rows = mysqli_num_rows($res12);
        										$ii=1;
        										while($arr12 = $res12->fetch_array()) {
        											
												?>
													<li>
                                                        <a href="content.php?Module=<?php echo $arr12['modulecode'];?>" target="_parent">
                                                                <!--<i class="ace-icon fa fa-user"></i>-->
                                                                <?php echo $arr12['lookname'];
                                                                ?>
                                                        </a>
                                                    </li>
                                                    
												<?php
												
												if($num_rows!=$ii)
												echo "<li class='divider'></li>"	;
												$ii++;
												}
        										?>
                                                        </ul>
        										
      									</li>
                                                    <li class="blue dropdown-modal">
                                                     <?php    if(stripos(json_encode($_SESSION['role']),'HR') == false){ ?>
                                                        <a data-toggle="dropdown" class="dropdown-toggle" href="#" onclick='alerthidemain();' title='Notification'>
                                                                <i class="ace-icon fa fa-bell icon-animated-bell" style="color: #FFFFFF"></i>
                                                                <span class="badge badge-important" id="msgdiv"><?php echo $accountsalertdisplay;?></span>
                                                                <script type="text/javascript">window.onload = msgrefresh();</script>
                                                               
                                                        </a>
                                                      <?php }?>
                                                      <?php    if(stripos(json_encode($_SESSION['role']),'OPERATIONS MANAGER') == true || stripos(json_encode($_SESSION['role']),'SALES') == true || stripos(json_encode($_SESSION['role']),'SERVICE') == true || stripos(json_encode($_SESSION['role']),'SITE INCHARGE') == true || stripos(json_encode($_SESSION['role']),'PURCHASE') == true || stripos(json_encode($_SESSION['role']),'FINANCE MANAGER') == true || stripos(json_encode($_SESSION['role']),'FRONT OFFICE CLERK') == true || stripos(json_encode($_SESSION['role']),'FACILITY MANAGER') == true){ ?>
                                                        <ul class="dropdown-menu-left dropdown-navbar  navbar-green dropdown-menu dropdown-caret dropdown-close" >
                                                           <li class="dropdown-header">
                                                                        <div class='clearfix'>
                                                                        <span class='pull-left' style='width:25px;'>
                                                                        <i class="btn-xs no-hover btn-success fa fa-exclamation-triangle"></i>&nbsp; </span>

                                                                        <span class='pull-left'>
                                                                      <a href='myalerts.php?type=ALERTS' target='main'> 
                                                                            You have <span id="msgdiv1"><?php echo $accountsalert;?></span>&nbsp;Notification(s) 
                                                                      </a>
                                                                        </span>

                                                                        </div>
                                                                </li>
                                                               <?php if(stripos(json_encode($_SESSION['role']),'FRONT OFFICE CLERK') != true && $contract_alerts>0) {?>
                                                                <li class="dropdown-header">
                                                                        <div class='clearfix'>
                                                                        <span class='pull-left' style='width:25px;'>
                                                                        <i class="btn-xs no-hover btn-danger fa fa-clock-o"></i>&nbsp; </span>

                                                                        <span class='pull-left'>
                                                                      		<a href='myalerts.php?type=CONTRACT_EXPIRY' target='main'> 
                                                                            Contract Expires in 30 days : <span id="msgdiv1"><?php echo $contract_alerts;?></span> 
                                                                      		</a>
                                                                        </span>

                                                                        </div>
                                                                </li>
                                                                <?php }?>
                                                                
                                                                <?php if(stripos(json_encode($_SESSION['role']),'SERVICE COORDINATOR') == true && $sub_contract_alerts>0) {?>
                                                                <li class="dropdown-header">
                                                                        <div class='clearfix'>
                                                                        <span class='pull-left' style='width:25px;'>
                                                                        <i class="btn-xs no-hover btn-warning fa fa-clock-o"></i>&nbsp;</span>

                                                                        <span class='pull-left'>
                                                                      <a href='myalerts.php?type=SUBCONTRACT_EXPIRY' target='main'> 
                                                                            Sub-Cont. Expires in 30 days : <span id="msgdiv2"><?php echo $sub_contract_alerts;?></span> 
                                                                      </a>
                                                                        </span>

                                                                        </div>
                                                                </li>
                                                                <?php }?>


                                                          <?php
                                                                                                                           $SQLacc2 = " SELECT jobno,propertycode FROM t_activitycenter WHERE jobthrough='Client' and jobstatus='Requested'";
                                                                                                                           $SQLResacc2 =  mysqli_query($con,$SQLacc2) or die(mysqli_error()."<br>".$SQLacc2);
                                                                                                                           if(mysqli_num_rows($SQLResacc2)>=1){
                                                                                                                           $i=1;
                                                                                                                             while($loginResultArrayacc2   = mysqli_fetch_array($SQLResacc2)){

                                                                                                                                  echo "          <li class='dropdown-content'>
                                                                                                                                                                                            <ul class='dropdown-menu dropdown-navbar navbar-pink'>
                                                                                                                                                                                                     <li>
                                                                                                                                                                                                            <a href='onetimejoblist.php?txtsearch=".$loginResultArrayacc2['jobno']."' target='main'>
                                                                                                                                                                                                                    <div class='clearfix'>
                                                                                                                                                                                                                            <span class='pull-left'>
                                                                                                                                                                                                                              $i) ".$loginResultArrayacc2['jobno']."-".$loginResultArrayacc2['propertyname']."
                                                                                                                                                                                                                            </span>

                                                                                                                                                                                                                    </div>
                                                                                                                                                                                                            </a>
                                                                                                                                                                                                    </li>
                                                                   </ul>
                                                           </li>" ;
          $i++;
                                                                                                                             }
                                                                                                                           }
                                                                                                                  }else if(stripos(json_encode($_SESSION['role']),'SUPERVISOR') == true){
                                                                                                               ?>

                                                                                                              <ul class="dropdown-menu-right dropdown-navbar navbar-blue dropdown-menu dropdown-caret dropdown-close">
                                                                                                              <li class="dropdown-header">
                                                                                                                      <i class="ace-icon fa fa-exclamation-triangle"></i>
                                                                                                                      You have <?php echo $accountsalert." ".$status;?> open tickets till today
                                                                                                              </li>

                                                                                                        <?php
                                                                                                                          $SQLacc2 = " SELECT ticketno,buildingname FROM tbl_ticket WHERE requeststatus='Open' and DATE_FORMAT(requestdate,'%Y-%m-%d')<= '".date('Y-m-d')."' and servicestaffid='".$_SESSION['SESSuserID']."'";
                                                                                                                           $SQLResacc2 =  mysqli_query($con,$SQLacc2) or die(mysqli_error()."<br>".$SQLacc2);
                                                                                                                           if(mysqli_num_rows($SQLResacc2)>=1){
                                                                                                                           $i=1;
                                                                                                                             while($loginResultArrayacc2   = mysqli_fetch_array($SQLResacc2)){

                                                                                                                                  echo "          <li class='dropdown-content'>
                                                                                                                                                                                            <ul class='dropdown-menu dropdown-navbar navbar-pink'>
                                                                                                                                                                                                     <li>
                                                                                                                                                                                                            <a href='newticketlist.php?txtsearch=".$loginResultArrayacc2['ticketno']."' target='main'>
                                                                                                                                                                                                                    <div class='clearfix'>
                                                                                                                                                                                                                            <span class='pull-left'>
                                                                                                                                                                                                                              $i) ".$loginResultArrayacc2['ticketno']."-".$loginResultArrayacc2['buildingname']."
                                                                                                                                                                                                                            </span>

                                                                                                                                                                                                                    </div>
                                                                                                                                                                                                            </a>
                                                                                                                                                                                                    </li>


                                                                                                                                                                                            </ul>
                                                                                                                                                                                    </li>" ;
                                                                                                                                   $i++;
                                                                                                                             }
                                                                                                                           }
                                                                                                                  }
                                                                                                        ?>
                                                   </li>
                                                   
                                         </ul>
                        </div>
				
                </div>
                
                <?php
                          if(stripos(json_encode($_SESSION['role']),'SALES') !== false){
                             $dashboardpage="dashboardall.php";
                          }else if(stripos(json_encode($_SESSION['role']),'OPERATIONS MANAGER') == true){
                          	 $dashboardpage="dashboardall.php";
                          }else if(stripos(json_encode($_SESSION['role']),'SERVICE') !== false){
                             $dashboardpage="dashboardall.php";
                          }else if(stripos(json_encode($_SESSION['role']),'SITE INCHARGE') !== false){
                             $dashboardpage="dashboardall.php";
                          }else if(stripos(json_encode($_SESSION['role']),'PURCHASE') !== false){
                             $dashboardpage="dashboardall.php";
                          }else if(stripos(json_encode($_SESSION['role']),'FACILITY MANAGER') !== false){
                             $dashboardpage="dashboardall.php";
                          }else if(stripos(json_encode($_SESSION['role']),'FINANCE MANAGER') !== false){
                             $dashboardpage="dashboardall.php";
                          }else if(stripos(json_encode($_SESSION['role']),'FRONT OFFICE') !== false){
                             $dashboardpage="dashboardall.php";
                          }
                          else if(stripos(json_encode($_SESSION['role']),'HR') == true) {
                             $dashboardpage="dashboardgraphshrm.php";
                          }elseif(stripos(json_encode($_SESSION['role']),'CLIENT') == true) {
                             $dashboardpage="dashboard_client.php";
                          }else{
                             $dashboardpage="blank.php?ps=1&pr=D,I,U";
                          }

                ?>
                
                <div class="main-container ace-save-state" id="main-container">
                        <div id="sidebar" class="sidebar sidebar-fixed">
                                <ul class="nav nav-list ">
                                <?php
                                $MENUNAME = 'Dashboard';
                                /*if(stripos(json_encode($_SESSION['role']),'1001') == true || stripos(json_encode($_SESSION['role']),'100') == true) {
                             		$dashboardpage="objectlist.php?ps=1&pr=I,D,U";
                             		$MENUNAME ='Object List';
                          		}*/
                                ?>
                                    <!--  <li id='m_0_0' name='id='m_0_0'  >
                                                <a href="<?php echo $dashboardpage;?>" target='main' onclick='javascript:getsidesidemenu(0,0);'>
                                                        <i class="menu-icon fa fa-tachometer"></i>
                                                        <span class="menu-text"> <?php echo $MENUNAME;?> </span>
                                                </a>
                                                <b class="arrow"></b>
                                        </li>-->
                                       

                                        <?php
                                          $str = "";
                                          
                                          $menu_sql = "select tbl_menusetup.menucode as code,tbl_menu.menu_name as name,tbl_menu.menu_icon as iconimage from tbl_menusetup right join tbl_menu on  tbl_menusetup.menucode=tbl_menu.menu_code  where tbl_menusetup.parentid=0 and tbl_menusetup.usergroupid in (".$_SESSION['usermenurole'].") group by tbl_menusetup.menucode order by tbl_menu.slno";//('101001','101002')
                                          //echo $_SESSION['usermenurole'];
                                          $menu_res = mysqli_query($con,$menu_sql);
                                          $x=1;
                                          while($menu_arr = mysqli_fetch_array($menu_res)){
                                          	$str .= "<li id='m_".$x."_0' name='id='m_".$x."_0' class=''>";
                                          	$str .= "<a href='#' class='dropdown-toggle'>";
                                            $str .= "<i class='menu-icon ".$menu_arr['iconimage']."'></i>";
                                            $str .= "<span class='menu-text'>".GetDictionary($menu_arr['name'])."</span>";
                                            if($menu_arr['name']!="Dashboard"){
                                                $str .= "<b class='arrow fa fa-angle-down'></b>";
                                            }
                                            $str .= "</a>";
                                           $smenu_sql = "select tbl_menu.objectid,tbl_menu.menu_url as url,tbl_menu.menu_name as name,tbl_menu.menu_icon as iconimage,sum(if(viewdata='true',1,0)) as viewdata,sum(if(adddata='true',1,0)) as adddata,sum(if(editdata='true',1,0)) as editdata,sum(if(deletedata='true',1,0)) as deletedata from tbl_menusetup right join tbl_menu on tbl_menusetup.menucode=tbl_menu.menu_code where tbl_menusetup.parentid='".$menu_arr['code']."' and tbl_menusetup.usergroupid in (".$_SESSION['usermenurole'].") group by tbl_menusetup.menucode order by tbl_menu.slno";
                                            $smenu_res = mysqli_query($con,$smenu_sql);
                                            $y=1;
                                            if(mysqli_num_rows($smenu_res)>=1){
                                            	
                                                $str .= "<b class='arrow'></b>";
                                                $str .= "<ul class='submenu'>";
                                                          while($strDataString3 = mysqli_fetch_array($smenu_res)){
                                                          	   $viewdata_prev = "";
                                                          	   if($strDataString3['viewdata'] >0) $viewdata_prev = "YES";
                                                          	   
                                                          	   $adddata_prev = "";
                                                          	   if($strDataString3['adddata'] >0) $adddata_prev = "I,";
                                                          	   
                                                          	   $editdata_prev = "";
                                                          	   if($strDataString3['editdata'] >0) $editdata_prev = "U,";
                                                          	   
                                                          	   $deletedata_prev = "";
                                                          	   if($strDataString3['deletedata'] >0) $deletedata_prev = "D";
                                                          	   if($viewdata_prev== "YES"){
                                                          	   	
		                                                      	   $url = $strDataString3['url']."?objectid=".$strDataString3['objectid']."&ps=1&pr=".$adddata_prev.$editdata_prev.$deletedata_prev;
		                                                           $str .= "<li id='m_".$x."_".$y."' name='id='m_".$x."_".$y."' class=''>";
		                                                           $str .= "<a href='".$url."' target='main' onclick='javascript:getsidesidemenu(\"".$x."\",\"".$y."\");'>";
		                                                           $str .= "<i class='ace-icon fa ".$strDataString3['iconimage']."'></i>&nbsp;&nbsp;";
		                                                           $str .= GetDictionary($strDataString3['name']);
		                                                           $str .= "</a>";
		                                                           $str .= "</li>";
                                                               	   ++$y;
															   }
                                                          }
                                                $str .= "</ul>";
                                                
                                            	
											}	
											$str .= "<input type=hidden name=submenu_".$x." id=submenu_".$x." value='".$y."'></li>";
                                              ++$x;
										  	
										  }
                                          $str .= " <input type=hidden name=mainmenu id=mainmenu value='".$x."'>";
                                          echo $str;

function InactiveContarct($con){
	$SQL = "SELECT jobno,expstartdate,expenddate,extendedto,if((t_activitycenter.extendedto='' or t_activitycenter.extendedto='0000-00-00'),t_activitycenter.expenddate,
t_activitycenter.extendedto) as enddate,DATEDIFF(if((t_activitycenter.extendedto='' or t_activitycenter.extendedto='0000-00-00'),t_activitycenter.expenddate,
t_activitycenter.extendedto),NOW()) as diff,status
FROM t_activitycenter WHERE DATEDIFF(if((t_activitycenter.extendedto='' or t_activitycenter.extendedto='0000-00-00'),t_activitycenter.expenddate,
t_activitycenter.extendedto),NOW())<=0 and t_activitycenter.activitycenter='CONTRACT' and status='Active' ";
	$RES = mysqli_query($con,$SQL);
	if(mysqli_num_rows($RES)>0) {
		while($ARR = mysqli_fetch_array($RES)){
			mysqli_query($con,"update t_activitycenter set status='Inactive' where jobno='".$ARR['jobno']."'");
		}
	}
	
}
function InactiveSubContarct($con){
	$SQL = "SELECT id,subcontractstartdate,subcontractenddate FROM tbl_subcontract 
	WHERE DATEDIFF(subcontractenddate,NOW())<=0 and (sitesendforapproval!='Inactive' and sitesendforapproval!='(PO) Rejected by Purchase Coordinator')";
	$RES = mysqli_query($con,$SQL);
	if(mysqli_num_rows($RES)>0) {
		while($ARR = mysqli_fetch_array($RES)){
			mysqli_query($con,"update tbl_subcontract set sitesendforapproval='Inactive',contractstatus='Expired' where id='".$ARR['id']."'");
		}
	}
	
}
                                        ?>


                                </ul><!-- /.nav-list -->

                                <div class="sidebar-toggle sidebar-collapse" id="sidebar-collapse">
                                        <i id="sidebar-toggle-icon" class="ace-icon fa fa-angle-double-left ace-save-state" data-icon1="ace-icon fa fa-angle-double-left" data-icon2="ace-icon fa fa-angle-double-right"></i>
                                </div>
                        </div>

                        <div class="main-content " >
                                <div class="main-content-inner">
                                        <div class="page-content" id="content-wrapper-id" style="padding:0px;margin:0px;overflow-x:hidden;overflow-y:hidden;">

                                                <iframe id="main" name="main" src="<?php echo $dashboardpage;?>" scrolling="no" frameborder="0" style="position: relative; width: 100%;"></iframe>

                                        </div>
                                </div>
                        </div>

                        <div class="footer footer-fixed" style="padding:0px;margin:0px;">
                                <div class="footer-inner" style="padding:0px;margin:0px;">
                                        <div class="footer-content"


                                                       <span class="bigger-120">
                                                        <span class="purple bolder">Radius</span>
                                                        &copy; 2018-2019
                                                       </span>



                                        </div>
                                </div>
                        </div>

                        <a href="#" id="btn-scroll-up" class="btn-scroll-up btn btn-sm btn-inverse">
                                <i class="ace-icon fa fa-angle-double-up icon-only bigger-110"></i>
                        </a>


                </div><!-- /.main-container -->

                <!-- basic scripts -->

                <!--[if !IE]> -->
                <script src="assets/js/jquery-2.1.4.min.js"></script>

                <!-- <![endif]-->

                <!--[if IE]>
<script src="assets/js/jquery-1.11.3.min.js"></script>
<![endif]-->
                <script type="text/javascript">
                        if('ontouchstart' in document.documentElement) document.write("<script src='assets/js/jquery.mobile.custom.min.js'>"+"<"+"/script>");
                </script>
                <script src="assets/js/bootstrap.min.js"></script>

                <!-- page specific plugin scripts -->

                <!-- ace scripts -->
                <script src="assets/js/ace-elements.min.js"></script>
                <script src="assets/js/ace.min.js"></script>

                <!-- inline scripts related to this page -->
        </body>
</html>
<script type="text/javascript">

                  function getsidesidemenu(x,y){
                             for(i=1;i<=document.getElementById('mainmenu').value;i++){
                               if(document.getElementById('m_'+i+'_0')){
                                  document.getElementById('m_'+i+'_0').className = '';

                                  for(j=1;j<=document.getElementById('submenu_'+x).value;j++){
                                      if(document.getElementById('m_'+i+'_'+j)){
                                         document.getElementById('m_'+i+'_'+j).className = '';
                                      }
                                  }
                               }
                             }
                             <!-- active open highlight-->
                             document.getElementById('m_'+x+'_0').className  = 'active';
                             document.getElementById('m_'+x+'_'+y).className  = 'active highlight';



                   }


</script>
<script type='text/javascript'>

                $(window).load(function(){
                   boxHeight()

                   $(window).resize(function(){
                     boxHeight();
                   })
                });
                function boxHeight(){
                     var height = $(this).height() - ($("#navbar").height()+30);
                     $('#content-wrapper-id').height(height);
                     $('#main').height(height);

                }

</script>
<script type="text/javascript">
      function session_checking()
      {
          $.post( "session.php", function( data ) {
              if(data == "-1")
              {
                  alertify.alert("Your session has been expired! Please login again.");
                  window.location.href='index.php';
              }
          });
      }

      var validateSession = setInterval(session_checking, 3000);
      setInterval(msgrefresh,5000);
function alerthidemain(){

   document.getElementById("alerthid").style.visibility = "hidden";

}
var xmlHttp;
    function GetXmlHttpObject()
                   {
                   var xmlHttp=null;
                   try
                   {
                   // Firefox, Opera 8.0+, Safari
                   xmlHttp=new XMLHttpRequest();
                   }
                   catch (e)
                   {
                   //Internet Explorer
                   try
                   {
                   xmlHttp=new ActiveXObject("Msxml2.XMLHTTP");
                   }
                   catch (e)
                   {
                   xmlHttp=new ActiveXObject("Microsoft.XMLHTTP");
                   }
                   }
                   return xmlHttp;
                   }
function msgrefresh(){

      xmlHttp=GetXmlHttpObject()
      if (xmlHttp==null)
      {
                 alert ("Browser does not support HTTP Request")
                 return
      }
      var url="content_alertrefresh.php?level=alertcount";
      xmlHttp.onreadystatechange=stateChangedcombo
      xmlHttp.open("POST",url,true)
      xmlHttp.send(null)

}
function stateChangedcombo(){

       if (xmlHttp.readyState==4 || xmlHttp.readyState=="complete"){

             var s1 = xmlHttp.responseText;
             var word = s1.split('@@@');
             document.getElementById('msgdiv').innerHTML=word[0];
             document.getElementById('msgdiv1').innerHTML=word[1];
             document.getElementById('msgdiv2').innerHTML=word[2];
       }
}
</script>
<?php
function GetDictionary($lable){
	global $con;
	$newlable = "";
	$sql = "select * from tbl_languagedictionary where objectid='Menus' and lable='".$lable."'";
	$res = mysqli_query($con,$sql);
	$rowcount=mysqli_num_rows($res);
	if($rowcount > 0){
		$arr = $res->fetch_array();
		$language = $_SESSION['UserLanguage'];
		if($language != "")
		$newlable = $arr[$language];
		else 
		$newlable = $lable;
	}
	else{
		$insql = "insert into tbl_languagedictionary (objectid,lable,english,german,arabic) values ('Menus','$lable','$lable en','$lable gr','$lable ar')";
		mysqli_query($con,$insql);
		//GetWfDictionary($lable);
		$newlable = $lable;
		
	}
	
	return $newlable;

}
?>