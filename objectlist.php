<?php
session_start();
require "connection.php";
require "pagingObj.php";
include "functions_workflow.php";

$_SESSION['objectid'] = isset($_REQUEST['objectid']) ? $_REQUEST['objectid'] : '';

$WF = new WorkFlow($_SESSION['objectid']);
$pagerights = $WF->loadPagerights();
$_SESSION['pr'] = isset($pagerights) ? $pagerights : '';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<head>
        <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
        <!-- Font Awesome -->
        <!--<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">-->
        <link rel="stylesheet" href="assets/font-awesome/4.5.0/css/font-awesome.min.css">
        <!-- Ionicons -->
        <!--<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">-->
        <!-- Theme style -->
        <link rel="stylesheet" href="dist/css/mainStyles.css">
        <link rel="stylesheet" href="dist/css/styles.css">


        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->
        <script src="plugins/jQuery/jquery-2.2.3.min.js"></script>
        <script src="bootstrap/js/bootstrap.min.js"></script>
        <script src="plugins/slimScroll/jquery.slimscroll.min.js"></script>
        <script src="dist/js/app.js"></script>
        <script type="text/javascript" src="js/ajax_functions.js"></script>
        <script type="text/javascript" src="js/lib.js"></script>
        <script type="text/javascript" src="js/myjs.js"></script>

         <link rel="stylesheet" href="css/alertify.core.css" />
        <link rel="stylesheet" href="css/alertify.default.css" id="toggleCSS" />
        
         <!--<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">-->
  <!--<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>-->
  <!--<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>-->
  <!--<script src="plugins/jquery/jquery.min.js"></script>-->
  <script src="plugins/jquery/bootstrap.bundle.js"></script>
  <!--<script src="dist/js/2adminlte.min.js"></script>-->
 

        <script src="js/alertify.min.js"></script>
</head>
<?php

$d_sql = "select lookname,objecttype,count(*) as count from tbl_objectmaster left join in_lookup on lookcode=objecttype group by objecttype";
$d_res = mysqli_query($con,$d_sql);
$Form_type = $Tbl_type = 0;
$dash_board ="";
$colors = array('bg-aqua','bg-green','bg-blue');
$i=0;
$array_type = array();
while($d_arr = mysqli_fetch_array($d_res)){
	$dash_board .= "<button type='button' class='btn ".$colors[$i]."' onclick=javscript:refreshPagingwithDashboard('objectlist.php?pr=".$_SESSION['pr']."&cmb_objectlist=".$d_arr['objecttype']."');>  ".$d_arr['lookname']." <span class='badge'>".$d_arr['count']."</span></button>&nbsp;";
	
	$array_type[$i] = array($d_arr['lookname'] => $d_arr['count']);
	$i++;
}
//print_r($array_type[0]['Forms']);
//print_r($array_type[0]);
?>


<body class="hold-transition sidebar-mini">
<section class='content-header'>
<h2 class='title'>OBJECT MASTER</h2>
</section>
<!--<pre>
<?php echo $dash_board;?>
</pre>-->

<!-- Info boxes -->
        <div class="row">
          <div class="col-12 col-sm-6 col-md-3">
            <div class="box">
              <span class="info-box-icon bg-light-blue-gradient " style="height:55px;"><i class="fa fa-cog"></i></span>

              <div class="info-box-content" onclick="javscript:refreshPagingwithDashboard('objectlist.php?pr=<?php echo $_SESSION['pr']; ?>&cmb_objectlist=4001');" >
                <span class="info-box-text">Forms</span>
                <span class="info-box-number">
                  <?php print $array_type[0]['Forms'];?>
                </span>
              </div>
              <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
          </div>
          <!-- /.col -->
          <div class="col-12 col-sm-6 col-md-3">
            <div class="box mb-3">
              <span class="info-box-icon bg-maroon" style="height:55px;"><i class="fa fa-thumbs-up"></i></span>

              <div class="info-box-content" onclick="javscript:refreshPagingwithDashboard('objectlist.php?pr=<?php echo $_SESSION['pr']; ?>&cmb_objectlist=4002');">
                <span class="info-box-text">Reports</span>
                <span class="info-box-number"><?php print $array_type[1]['Reports'];?></span>
              </div>
              <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
          </div>
          <!-- /.col -->

          <!-- fix for small devices only -->
        <!--  <div class="clearfix hidden-md-up"></div>-->

          <div class="col-12 col-sm-6 col-md-3" >
            <div class="box mb-3">
              <span class="info-box-icon bg-green elevation-1" style="height:55px;"><i class="fa fa-shopping-cart"></i></span>

              <div class="info-box-content" onclick="javscript:refreshPagingwithDashboard('objectlist.php?pr=<?php echo $_SESSION['pr']; ?>&cmb_objectlist=4003');">
                <span class="info-box-text">Tables</span>
                <span class="info-box-number"><?php print $array_type[2]['Table'];?></span>
              </div>
              <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
          </div>
          <!-- /.col -->
        <!-- /* <div class="col-12 col-sm-6 col-md-3">
            <div class="box mb-3">
              <span class="info-box-icon bg-danger" style="height:55px;"><i class="fa fa-users"></i></span>

              <div class="info-box-content">
                <span class="info-box-text">New Members</span>
                <span class="info-box-number">2,000</span>
              </div>
            </div>
          </div>*/-->
          <!-- /.col -->
        </div>
        <!-- /.row -->


<?php


          /*$findBycode = isset($_POST['txtlookcode']) ? $_POST['txtlookcode'] : '';
          $findBytype = isset($_POST['txtlooktype']) ? $_POST['txtlooktype'] : '';*/
          
        $frmPage_rowcount = isset($_REQUEST['frmPage_rowcount']) ? $_REQUEST['frmPage_rowcount'] : '';
		if($frmPage_rowcount>15){
			$_SESSION["frmPage_rowcount"]=$frmPage_rowcount;
		}else{
			$_SESSION["frmPage_rowcount"]="15";
		}
		
		$frmPage_startrow = isset($_REQUEST['frmPage_startrow']) ? $_REQUEST['frmPage_startrow'] : '';
		if($frmPage_startrow>1){
			$_SESSION["frmPage_startrow"]=$frmPage_startrow;
		}else{
			$_SESSION["frmPage_startrow"]="0";
		}
		
		$_SESSION['lookcode'] = isset($_REQUEST['cmb_objectlist']) ? $_REQUEST['cmb_objectlist'] : '';
		
		$findBysearch = isset($_REQUEST['txtsearch']) ? $_REQUEST['txtsearch'] : '';
    	$_SESSION["txtsearch"]= $findBysearch;
    	
    	$insert = $update = $delete = "true";

          if(false !== strpos($_SESSION['pr'],"I")) $insert = "true" ;
          if(false !== strpos($_SESSION['pr'],"U")) $update = "true" ;
          if(false !== strpos($_SESSION['pr'],"D")) $delete = "true" ;

          $id_field = 'tbl_objectmaster.id';
          $fieldNames = array('','Date','Author','Object Type','Object Name','Show','Menu');
          $fieldSizes = array('',15,15,10,20,10,25);
          $fieldAlign = array('','left','left','left','left','left','left');
          $fields = array('tbl_objectmaster.createdon', 'A.lookname as author', 'B.lookname as objecttype', 'tbl_objectmaster.objectname', 'C.lookname as showmenu', 'tbl_objectmaster.menuname');
          $table = 'tbl_objectmaster left join in_lookup  as A on A.lookcode=tbl_objectmaster.author left join in_lookup  as B on B.lookcode=tbl_objectmaster.objecttype left join in_lookup  as C on C.lookcode=tbl_objectmaster.showinmenu';
          $formlistname = "objectlist.php";
          $formeditlist= "editobjectlist.php";

          $buttons = array('insert' => $insert,'edit' => $update, 'delete' => $delete, 'view' => true,
                          'keys' => array('id'));

          $arguments = array('id_field' => $id_field, 'fieldNames' => $fieldNames, 'fieldSizes' => $fieldSizes,
                          'fieldAlign' => $fieldAlign, 'fields' => $fields, 'table' => $table,'buttons' => $buttons,
                          'formlistname'=>$formlistname,
                          'formeditlist'=>$formeditlist,'selectedlistingpage'=>$frmPage_startrow);
        
        $addsql = ""                  ;
        if($_SESSION['lookcode']!=""){
			$addsql = " and objecttype = '".$_SESSION['lookcode']."'";
		}

          $clause = " (tbl_objectmaster.createdon LIKE '%$findBysearch%' or B.lookname LIKE '%$findBysearch%' or tbl_objectmaster.objectname LIKE '%$findBysearch%' or A.lookname LIKE '%$findBysearch%' or tbl_objectmaster.menuname LIKE '%$findBysearch%' ) $addsql";

         // echo $clause;

          $grid = new MyPHPGrid('frmPage');

          $grid->drawGrid($arguments, $clause, $con);

          //$grid->editFields = array("lookcode,lookname,looktype");

          $grid->editRecord = $update;

          $grid->TabsNames = array("objectlist Details:1:6");

          $grid->TableName = "tbl_objectmaster";

          $grid->formName = "objectlist.php";

          $grid->inpage = $frmPage_startrow;

          $grid->SyncSession($grid);
          ?>
          </body>
</html>
      <script src="jq/jquery-2.1.1.min.js"></script>
      <script src="plugins/jQuery/jquery-2.2.3.min.js"></script>
      <script src="bootstrap/js/bootstrap.min.js"></script>
      <script src="plugins/slimScroll/jquery.slimscroll.js"></script>
      <script src="plugins/select2/select2.full.min.js"></script>
      <script src="plugins/iCheck/icheck.min.js"></script>
      <script src="plugins/jqueryValidate/jquery.validate.js"></script>
      <script src="dist/js/app.js"></script>
      <script type="text/javascript" src="js/jquery-1.8.0.js"></script>


    <script type='text/javascript'>
                $(window).load(function(){
                   boxHeight()
                   $(window).resize(function(){
                     boxHeight();
                   })

                });
                function boxHeight(){
                    var height = $("#content-wrapper-id",parent.document).height();
                    $('#section-content-id').height(height);
                    var boxheight = height - 195;
                    $('#box-body-id').height(boxheight);

                    boxheight = boxheight-14;
                    $('#box-body-id-rows').slimScroll({
                      height: boxheight +'px',
                      wheelStep: 100,
                      alwaysVisible: true

                    });
                }
     </script>