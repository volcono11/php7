<?php
@session_start();
require "connection.php";
require "pagingObj.php";
if($_SESSION['pr'] == "") {
  $_SESSION['pr'] = $_REQUEST['pr'];
}
if(isset($_SESSION['lookcode'])){
	$cmb_lookuplist = $_SESSION['lookcode'];
}
if(isset($_REQUEST["ps"])){
    if($_REQUEST['ps'] == "1") {
   		$cmb_lookuplist = "Select";
	}
}
else{
	if(isset($_REQUEST["cmb_lookuplist"]))
	$cmb_lookuplist = $_REQUEST["cmb_lookuplist"];
}


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<head>
        <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
        <!-- Font Awesome -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
        <!-- Ionicons -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
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
        <script src="js/alertify.min.js"></script>
</head>
<body class="hold-transition sidebar-mini">
<section class='content-header'>
<h2 class='title'>SUB MENU</h2>
</section>

<?php

		if($cmb_lookuplist != "Select") {
			$_SESSION['lookcode'] = $cmb_lookuplist;
			$selected = '';
		}
		else{
			$selected = 'selected';
			$_SESSION['lookcode'] = '';
		}
		
		if(isset($_REQUEST["frmPage_startrow"])){
        	$frmPage_startrow= $_REQUEST["frmPage_startrow"];
    	}else{
        	$frmPage_startrow='';
    	}
	
		if(isset($_REQUEST["frmPage_rowcount"])){
        	$frmPage_rowcount= $_REQUEST["frmPage_rowcount"];
    	}else{
        	$frmPage_rowcount='';
    	}
                
		if($frmPage_rowcount>15){
			$_SESSION["frmPage_rowcount"]=$frmPage_rowcount;
		}else{
			$_SESSION["frmPage_rowcount"]="15";
		}
		
		if(isset($_REQUEST["txtsearch"])){
	        $findBysearch=$_REQUEST['txtsearch'];
		    
	    }else{
	        $findBysearch='';
	    }
	    $_SESSION["txtsearch"]= $findBysearch;
    
		
		$findBycode = isset($_POST['txtlookcode']) ? $_POST['txtlookcode'] : '';
		$findBytype = isset($_POST['txtlooktype']) ? $_POST['txtlooktype'] : '';

		if(false !== strpos($_SESSION['pr'],"I")) $insert = "true" ;
		if(false !== strpos($_SESSION['pr'],"U")) $update = "true" ;
		if(false !== strpos($_SESSION['pr'],"D")) $delete = "true" ;

		$id_field = 'id';
		$fieldNames = array('', 'Sl No','Menu Name','Role','URL');
		$fieldSizes = array('',5,15,50,30);
		$fieldAlign = array('','left','left','left','left');

		$fields = array( 'inmenu.slno', 'inmenu.name','inmenu.roles','inmenu.url');
		$table = 'inmenu';
		$formlistname = "submenulist.php";
		$formeditlist= "editsubmenulist.php";

          

        $topcombo = "<span style='float:left'>Main Menu :&nbsp;&nbsp;</span>";
        $topcombo .= "<label class='select'><select name='cmb_lookuplist' id='cmb_lookuplist' class='form-control select' style='padding-left:5px;padding-right:5px;margin-top:-5px;height:30px;font-weight:normal;' onchange=javascript:refreshPaging('submenulist.php');>";
          
        $topcombo .= "<option value='Select' $selected>Select</option>";
          
        $SQL =  "SELECT id,name FROM inmenu WHERE parentid='0' order by name";
        $SQLRes =  mysqli_query($con,$SQL) or die(mysqli_error()."<br>".$SQL);
        if(mysqli_num_rows($SQLRes)>=1){
            while($loginResultArray   = mysqli_fetch_array($SQLRes)){

            	if($_SESSION["lookcode"]==$loginResultArray['id']){
                	$topcombo .= "<option value='".$loginResultArray['id']."' selected='selected'>".$loginResultArray['name']."</option>";
               	}else{
                	$topcombo .= "<option value='".$loginResultArray['id']."'>".$loginResultArray['name']."</option>";
               	}
            }
        }
        $topcombo .= "</select></label>";

        $buttons = array('insert' => $insert,'edit' => $update, 'delete' => $delete, 'view' => true,'keys' => array('id'));

		$arguments = array('id_field' => $id_field, 'fieldNames' => $fieldNames, 'fieldSizes' => $fieldSizes,
	    'fieldAlign' => $fieldAlign, 'fields' => $fields, 'table' => $table,'buttons' => $buttons,'topcombo'=>$topcombo,
	    'topcomboselected'=>$_SESSION['lookcode'],'formlistname'=>$formlistname,'formeditlist'=>$formeditlist,
	    'selectedlistingpage'=>$frmPage_startrow,'selectedrowcount'=>$_SESSION['frmPage_rowcount']);
	        
	               

    
        $clause = " (inmenu.name LIKE '%$findBysearch%') and inmenu.parentid<>'0' and inmenu.parentid='".$_SESSION["lookcode"]."'";

          //echo $clause."<br><br><br>";

		$grid = new MyPHPGrid('frmPage');

		$grid->drawGrid($arguments, $clause, $con);

		$grid->editFields = array("slno,name,roles,parentid,url");

		$grid->editRecord = $update;

		$grid->TabsNames = array("Sub Menu Details:1:6");

		$grid->TableName = "inmenu";

		$grid->formName = "submenulist.php";

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
		    var boxheight = height - 128;
		    $('#box-body-id').height(boxheight);

		    boxheight = boxheight-14;
		    $('#box-body-id-rows').slimScroll({
		      height: boxheight +'px',
		      wheelStep: 100,
		      alwaysVisible: true

		    });
		}
	</script>