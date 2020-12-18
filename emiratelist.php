<?php
session_start();
require "connection.php";
require "pagingObj.php";

$_SESSION['pr'] = isset($_REQUEST['pr']) ? $_REQUEST['pr'] : '';

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
        <script src="plugins/jquery/bootstrap.bundle.js"></script>
</head>




<body class="hold-transition sidebar-mini">
<section class='content-header'>
<h2 class='title'>EMIRATE / STATE</h2>
</section>

          <?php


/*          $findBycode = isset($_POST['txtlookcode']) ? $_POST['txtlookcode'] : '';
          $findBytype = isset($_POST['txtlooktype']) ? $_POST['txtlooktype'] : '';
*/
		$frmPage_rowcount = isset($_REQUEST['frmPage_rowcount']) ? $_REQUEST['frmPage_rowcount'] : '';
		if($frmPage_rowcount>15){
			$_SESSION["frmPage_rowcount"]=$frmPage_rowcount;
		}else{
			$_SESSION["frmPage_rowcount"]="15";
		}
		
		$frmPage_startrow = isset($_REQUEST['frmPage_startrow']) ? $_REQUEST['frmPage_startrow'] : '';
		
		$findBysearch = isset($_REQUEST['txtsearch']) ? $_REQUEST['txtsearch'] : '';
    	$_SESSION["txtsearch"]= $findBysearch;
    	
    	$insert = $update = $delete = "false";

		if(false !== strpos($_SESSION['pr'],"I")) $insert = "true" ;
		if(false !== strpos($_SESSION['pr'],"U")) $update = "true" ;
		if(false !== strpos($_SESSION['pr'],"D")) $delete = "true" ;

		$id_field = 'tbl_states.id';
		$fieldNames = array('','Country','Emirate');
		$fieldSizes = array('','50','50');
		$fieldAlign = array('','left','left');
		$fields = array('tbl_country.country', 'tbl_states.emirate');
		$table = 'tbl_states left join tbl_country on tbl_country.id=tbl_states.countryid';
		$formlistname = "emiratelist.php";
		$formeditlist= "editemiratelist.php";

        $buttons = array('insert' => $insert,'edit' => $update, 'delete' => $delete, 'view' => true,
                          'keys' => array('id'));



        $arguments = array('id_field' => $id_field, 'fieldNames' => $fieldNames, 'fieldSizes' => $fieldSizes,
                          'fieldAlign' => $fieldAlign, 'fields' => $fields, 'table' => $table,'buttons' => $buttons,
                          'formlistname'=>$formlistname,
                          'formeditlist'=>$formeditlist,'selectedlistingpage'=>$frmPage_startrow);

        $clause = " (tbl_country.country LIKE '%$findBysearch%'
                     or tbl_states.emirate LIKE '%$findBysearch%')";

         // echo $clause;

		$grid = new MyPHPGrid('frmPage');

		$grid->drawGrid($arguments, $clause, $con);


		//$grid->editFields = array("lookcode,lookname,looktype,companycode");

		$grid->editRecord = $update;

		//$grid->TabsNames = array("lookuplist Details:1:6");

		$grid->TableName = "tbl_states";

		$grid->formName = "emiratelist.php";

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