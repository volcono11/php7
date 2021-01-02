<?php
session_start();

require "connection.php";
require "pagingObj.php";
include "functions_workflow.php";

$_SESSION['objectid'] = isset($_REQUEST['objectid']) ? $_REQUEST['objectid'] : '';

$WF = new WorkFlow($_SESSION['objectid']);
$pagerights = $WF->loadPagerights();
$_SESSION['pr'] = isset($pagerights) ? $pagerights : '';

$cmb_lookuplist = "";
$cmb_lookuplist1 = "";


$d_sql = "select companycode,companyname,count(*) as count from tbl_companysetup group by companycode";
$d_res = mysqli_query($con,$d_sql) or die(mysqli_error($con)."<br>".$d_sql);
$dash_board ="";
$colors = array('bg-aqua','bg-green','bg-blue');
$i=0;
while($d_arr = mysqli_fetch_array($d_res)){
	$dash_board .= "<button type='button' class='btn ".$colors[$i]."' onclick=javscript:refreshPaging2('newleadheadlist.php?objectid=".$_SESSION['objectid']."&companycode=".$d_arr['companycode']."');>  ".$d_arr['companyname']." <span class='badge'>".$d_arr['count']."</span></button>&nbsp;";
	
	$i++;
}

// top combo setup
$cmb_lookuplist = isset($_REQUEST['cmb_lookuplist']) ? $_REQUEST['cmb_lookuplist'] : 'Current Status';
$d_companycode = isset($_REQUEST['companycode']) ? $_REQUEST['companycode'] : '';
$cmb_lookuplist1 = isset($_REQUEST['cmb_lookuplist1']) ? $_REQUEST['cmb_lookuplist1'] : 'Select';

// print_r($_REQUEST);   
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
        <link rel="stylesheet" href="plugins/select2/select2.min.css">
        <link rel="stylesheet" href="css/alertify.core.css" />
        <link rel="stylesheet" href="css/alertify.default.css" id="toggleCSS" />
        <script src="js/alertify.min.js"></script>

</head>
<body class="hold-transition sidebar-mini">
<section class='content-header' style='margin-top:10px;'>
<h2 class='title'>AMC ENQUIRY</h2>
</section>
<!--<pre>
<?php echo $dash_board;?>
</pre>-->
<?php
	if($cmb_lookuplist1 != "") {
	  $_SESSION['lookcode1'] = $cmb_lookuplist1;
	}

	$insert = $update = $delete = "false";

	if(false !== strpos($_SESSION['pr'],"I")) $insert = "true" ;
	if(false !== strpos($_SESSION['pr'],"U")) $update = "true" ;
	if(false !== strpos($_SESSION['pr'],"D")) $delete = "true" ;

	if($cmb_lookuplist != "") {
	  $_SESSION['lookcode'] = $cmb_lookuplist;
	}

	$topcombo = "<span style='float:left;margin-top:5px;'>Company&nbsp;</span>";
	$topcombo .= "<label class='select'>
	<select name='cmb_lookuplist1' id='cmb_lookuplist1' class='form-control select2' style='padding-left:5px;padding-right:5px;margin-top:-5px;height:30px;weight:210px;font-weight:normal;' onchange=javascript:refreshPaging2('newleadheadlist.php?objectid=".$_SESSION['objectid']."');>";

	$topcombo .= "<option value='Select' selected='selected'>All</option>";
	$SQL1 =  "select companycode,companyname from tbl_companysetup where companycode <>'' order by companyname";
	$SQLRes1 =  mysqli_query($con,$SQL1) or die(mysqli_error()."<br>".$SQL1);
	if(mysqli_num_rows($SQLRes1)>=1){
		 while($loginResultArray1   = mysqli_fetch_array($SQLRes1)){
			   if($_SESSION['lookcode1']==$loginResultArray1['companycode']){
			    $topcombo .= "<option value='".$loginResultArray1['companycode']."' selected='selected'>".$loginResultArray1['companyname']."</option>";
			   }else{
			    $topcombo .= "<option value='".$loginResultArray1['companycode']."'>".$loginResultArray1['companyname']."</option>";
			   }
		 }
	}
	$topcombo .= "</select></label>";
	
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

	$topcombo1 = "<span style='float:left;margin-top:5px;'>&nbsp;Status&nbsp;</span>";
	$topcombo1 .= "<label class='select'>

	            <select name='cmb_lookuplist' id='cmb_lookuplist'
	                    class='form-control select2' style='padding-left:5px;padding-right:5px;margin-top:-5px;height:30px;width:225px;font-weight:normal;'
	                    onchange=javascript:refreshPaging('newleadheadlist.php');>";

	$topcombo1 .= "<option value='Current Status' selected='selected'>Current Status</option>";

	$selected='';
	 $selected2='';
	if($_SESSION['lookcode']=='Select')
	{
	 $selected='selected';
	}
	else if($_SESSION['lookcode']=='Pending Status')
	{
	 $selected2='selected';
	}
	else
	
	$topcombo1 .= "<option value='Pending Status' $selected2>Pending Status</option>";
	$topcombo1 .= "<option value='Select' $selected>All</option>";

	$SQL =  "SELECT statusname FROM tbl_status WHERE statusname='Enquiry Open' ORDER BY id";
	$SQLRes =  mysqli_query($con,$SQL) or die(mysqli_error()."<br>".$SQL);
	if(mysqli_num_rows($SQLRes)>=1){
		 while($loginResultArray   = mysqli_fetch_array($SQLRes)){
			  if($_SESSION['lookcode']==$loginResultArray['statusname']){
			      $topcombo1 .= "<option value='".$loginResultArray['statusname']."' selected='selected'>".$loginResultArray['statusname']."</option>";
			   }else{
			      $topcombo1 .= "<option value='".$loginResultArray['statusname']."'>".$loginResultArray['statusname']."</option>";
			   }
		 }
	}
	$topcombo1 .= "</select></label>";

	$id_field = 'in_crmhead.id';


    	$orderby =  'in_crmhead.enquiry_date desc,in_crmhead.posted_date desc,in_crmhead.id';
		$fieldNames = array('','company','Enq Date','Tent. Sub dt','Project Name','Customer Name','Nature of Enq.','stcheck');
		$fieldSizes = array('',12,4,6,6,12,12,12,7,8,8,10,3);
		$fieldAlign = array('','left','left','left','left','left','left','left','left','left','left','left','left');
		$docate = "DATE_FORMAT(docdate,'%d-%m-%y') as docdate";
		$tentativedate = "DATE_FORMAT(tentativedate,'%d-%m-%y') as tentativedate";
		$stcheck="in_crmhead.stcheck as stcheck" ;
		$jobno="(SELECT li.jobno FROM t_activitycenter li left join in_crmhead ch on li.salesorderno=ch.docno where ch.doctype='ORDER' and ch.parentdocno=ch.quotereference) as jobno";
		$type = "SUBSTRING_INDEX(in_crmhead.enquirycategory, ' ', 1) as enquirycategory";
		$name = "SUBSTRING_INDEX(A.username, ' ', 1) as username";
		$quotedocno= "(select if(max(ch.version)=0,ch.docno,concat(ch.docno,'-V',max(ch.version))) from in_crmhead as ch where ch.parentdocno=in_crmhead.docno) as quotedoc ";
		$quotevalueXX= "(select totalgrossamt from in_crmhead as ch where ch.parentdocno=in_crmhead.docno and id=
(select max(id) from in_crmhead as ch1 where ch1.parentdocno=in_crmhead.docno)) as quotevalue";
		$quotevalue= "in_crmhead.quotevalue";
		$taskpending="(SELECT count(*) FROM in_crmtasks li WHERE li.enquiryid=in_crmhead.id and status <> 'Completed') as pendingtasks"  ;
		$objectname = 'projectname';
		$fields = array('in_crmhead.companycode',$docate,$tentativedate,'in_crmhead.projectname',$objectname,'in_crmhead.natureofenquiry','wfstatus');
	
	$table = 'in_crmhead';

	$formlistname = "newleadheadlist.php";
	$formeditlist= "editnewleadheadlist.php";

	$buttons = array('insert' => $insert,'edit' => $update, 'delete' => $delete, 'view' => true,
	              'keys' => array('id'));
	
	$topcombo2='';
	$arguments = array('id_field' => $id_field, 'fieldNames' => $fieldNames, 'fieldSizes' => $fieldSizes,'orderby'=>$orderby,
	                 'fieldAlign' => $fieldAlign, 'fields' => $fields, 'table' => $table,'buttons' => $buttons,
	                 'topcombo1'=>$topcombo,'topcombo2'=>$topcombo2,'topcombo'=>$topcombo1,'topcomboselected'=>$_SESSION['lookcode1'],'formlistname'=>$formlistname,
	                 'formeditlist'=>$formeditlist,'selectedlistingpage'=>$frmPage_startrow,'selectedrowcount'=>$_SESSION['frmPage_rowcount']);

    if(isset($_REQUEST["txtsearch"])){
        $findBysearch=$_REQUEST['txtsearch'];
	    
    }else{
        $findBysearch='';
    }
    $_SESSION["txtsearch"]= $findBysearch;
    
	$addsql1= "";
	$addsql="";

	$addsql = "and in_crmhead.enquirycategory = 'AMC Enquiry'";

	if($_SESSION['lookcode']=="Current Status" && $_SESSION['lookcode']!=""){
	$addsql.= " and (in_crmhead.stcheck ='Enquiry Open' or in_crmhead.stcheck ='Waiting for Inspection details')";

	}
	if($_SESSION['lookcode']=="Pending Status" && $_SESSION['lookcode']!=""){
	   $addsql.= " and ((in_crmhead.stcheck ='Enquiry Open' and in_crmhead.enquiryby='".$_SESSION['SESSuserID']."') or
	                     (in_crmhead.stcheck ='Waiting for Inspection details' and in_crmhead.userid='".$_SESSION['SESSuserID']."'))";

	}
	if($_SESSION['lookcode']!="Select" && $_SESSION['lookcode']!="" && ($_SESSION['lookcode']!="Current Status" && $_SESSION['lookcode']!="Pending Status")){
	$addsql.= " and in_crmhead.stcheck='".$_SESSION['lookcode']."' ";
	}
	if($_SESSION['lookcode1']!="Select" && $_SESSION['lookcode1']!=""){
	$addsql1 = "and in_crmhead.company='".$_SESSION['lookcode1']."'";
	}

	$clause= " in_crmhead.createdby='".$_SESSION['SESSuserID']."' or in_crmhead.wfusers like '%".$_SESSION['SESSuserID']."%'";
	// echo $clause."<br><br><br>";
	$grid = new MyPHPGrid('frmPage');
	$grid->drawGrid($arguments, $clause, $con);
	$grid->editRecord = $update;
	$grid->TableName = "in_crmhead";
	$grid->formName = "newleadheadlist.php";
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
                   boxHeight();
                   $(".select2").select2();
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
