<?php
//error_reporting(0);
@session_start();

include "in_providers.php";
//print_r($_REQUEST);
class MyPHPGrid
{
        //values for sorting and filtering
        private $sortid = '';
        private $sortdir = 'DESC';
        private $fid = '';
        private $fvalue = '';

        //values for page navigation
        private $startrow = 0;
        private $startpage = 1;
        private $currentpage = 1;
        private $endpage = 15;
        private $rowcount = 15;

        //form name
        private $name = '';
        private $formlistname = '';
        private $formeditlist = '';
        //field names for sorting and filtering
        private $_sortid = '';
        private $_sortdir = '';
        private $_fid = '';
        private $_fvalue = '';

        //field names for page navigation
        private $_startrow = '';
        private $_startpage = '';
        private $_currentpage = '';
        private $_endpage = '';
        private $_rowcount = '';
        private $_orderby = '';
        private $_gopage = '';

        private $_topcombo1 = '';
        private $_topcombo2 = '';
        private $_topcombo3 = '';
        
        function __construct($name)
        {
                $this->name = $name;
                //$this->formlistname = $formlistname;
               // $this->formeditlist = $formeditlist;
                //$this->orderby = $name . '_orderby';

                $this->_sortid = $name . '_sortid';
                $this->_sortdir = $name . '_sortdir';
                $this->_fid = $name . '_fid';
                $this->_fvalue = $name . '_fvalue';
                $this->_rowcount = $name . '_rowcount';

                $this->_startrow = $name . '_startrow';
                $this->_startpage = $name . '_startpage';
                $this->_currentpage = $name . '_currentpage';
                $this->_endpage = $name . '_endpage';

        }

        function drawGrid($arguments, $clause, &$con)
        {
                $id = $arguments['id_field'];
                $fieldNames = $arguments['fieldNames'];
                $fieldSizes = $arguments['fieldSizes'];
                $fieldAlign = $arguments['fieldAlign'];
                $fields = $arguments['fields'];
                $table = $arguments['table'];
                $buttons = $arguments['buttons'];
                
                if(isset($arguments['topcombo']))
                $topcombo = $arguments['topcombo'];
                else
                $topcombo = '';
                
                if(isset($arguments['topcombo1']))
                $topcombo1 = $arguments['topcombo1'];
                else
                $topcombo1 = '';
                
                if(isset($arguments['topcombo2']))
                $topcombo2 = $arguments['topcombo2'];
                else
                $topcombo2 = '';
                
                if(isset($arguments['topcomboselected']))
                $topcomboselected = $arguments['topcomboselected'];
                else
                $topcomboselected = '';
                
                if(isset($arguments['selectedrowcount']))
                $selectedrowcount = $arguments['selectedrowcount'];
                else
                $selectedrowcount = '';
                
                
                $formlistname = $arguments['formlistname'];
                $formeditlist = $arguments['formeditlist'];
                $buttons = $arguments['buttons'];
                
                $selectedlistingpage = $arguments['selectedlistingpage'];
                
                
                if(isset($arguments['orderby']))
                $orderby = $arguments['orderby'];
                else
                $orderby = '';
                

                $columns = '';

                foreach ($fields as &$field)
                         $columns .= $field . ', ';

                $columns = rtrim($columns,', ');

                if($orderby != "")
                $this->sortid = $orderby;
                else
                $this->sortid = $id;

                if($selectedlistingpage=="")$selectedlistingpage=0;
                if($selectedrowcount=="")$selectedrowcount=15;

                $this->startrow = isset($_POST[$this->_startrow]) ? $_POST[$this->_startrow]: $selectedlistingpage;
                $this->rowcount = isset($_POST[$this->_rowcount]) ? $_POST[$this->_rowcount]: $selectedrowcount;
                $this->startpage = isset($_POST[$this->_startpage]) ? $_POST[$this->_startpage] : 1;
                $this->currentpage = isset($_POST[$this->_currentpage]) ? $_POST[$this->_currentpage] : 1;
                $this->endpage = isset($_POST[$this->_endpage]) ? $_POST[$this->_endpage] : 15;



                if (isset($_POST[$this->_fid]))
                {
                        $this->fid = $_POST[$this->_fid];
                        $this->fvalue = $_POST[$this->_fvalue];

                        if (strlen($this->fid) > 0)
                        {
                                if (strlen($clause) > 0)
                                        $clause .= " AND ";
                                if($this->fid=="accountheadname"){
                                 $this->fid =  "in_accounthead.accountheadname";
                                }
                                $clause .= $this->fid . " = '" . $this->fvalue . "'";
                        }
                }

                $sqlqry1 = "SELECT count($id) AS totalrec FROM $table";
               $sqlqry = "SELECT $id,$columns FROM $table";
                //PRINT_R($fields);
               // echo $sqlqry1 ;

                if (strlen($clause) > 0)
                {
                        $sqlqry .= " WHERE " . $clause;
                        $sqlqry1 .= " WHERE " . $clause;
                }

                $result = mysqli_query($con,$sqlqry1);


                //echo $sqlqry1;
                //exit;
                if (!$result)
                        die(mysqli_error());

                $row = mysqli_fetch_row($result);
                //echo print_r($row);
                $totalrec = $row[0];

                if (isset($_POST[$this->_sortid]))
                {
                        //echo $this->name;
                        $this->sortid = $_POST[$this->_sortid];

                        if (isset($_POST[$this->_sortdir]))
                        {
                                $this->sortdir = $_POST[$this->_sortdir];
                        }
                                                     $sort_elemets = count(explode(",",$this->sortid));
                                                         if($sort_elemets==1)
                             $sqlqry .= " ORDER BY abs(" . $this->sortid . ") " . $this->sortdir . "," . $this->sortid . " " . $this->sortdir;
                             else
                             $sqlqry .= " ORDER BY " . $this->sortid . " " . $this->sortdir;


                }else{
                                                $sort_elemets = count(explode(",",$this->sortid));
                                                if($sort_elemets==1)
                        $sqlqry .= " ORDER BY abs(" . $this->sortid .") DESC, " . $this->sortid ." DESC";
                        else
                        $sqlqry .= " ORDER BY " . $this->sortid ." DESC";
                }

                $sqlqry .= ' LIMIT ' . $this->startrow . ',' . $this->rowcount;

 //              echo $sqlqry."<br><br><br>";
                //exit;
                mysqli_query($con,"SET NAMES 'utf8'");
                $result = mysqli_query($con,$sqlqry);

                if(isset($_REQUEST["frmPage_rowcount"])){
                   $frmPage_rowcount= $_REQUEST["frmPage_rowcount"];
                }else{
                   $frmPage_rowcount='';
                }
                $selected1 = "";
                $selected2 = "";
                $selected3 = "";
                $selected4 = "";

                if($frmPage_rowcount=="") $selected1 ="selected='selected'";
                if($frmPage_rowcount=="15") $selected1 ="selected='selected'";
                if($frmPage_rowcount=="30") $selected2 ="selected='selected'";
                if($frmPage_rowcount=="45") $selected3 ="selected='selected'";
                if($frmPage_rowcount=="60") $selected4 ="selected='selected'";


               echo "<form name='frmPage' id='frmPage'  method='post'>
                <section class='content' id='section-content-id' style='padding-right:5px;padding-left:5px;margin-top:-10px;' >

                          <div class='box box-primary' id='box-content-id'  >
                            <div class='box-header with-border' id='box-header-id' style='height:40px;border-bottom:1px #D2D2D2 solid;' >
                              <span style='float:left;margin-top:0px;'>";
                      if($topcombo!=""){

                            echo "<table><tr><td>";

                            echo $topcombo1;
                            echo "</td><td>";
                            echo $topcombo;
                            echo "</td><td>";
                            echo $topcombo2;

                            echo "</td><td>";
                          // echo $topcomboselected;
                      if($topcomboselected!="Select" && $topcomboselected!=""){
							
                             if($buttons["insert"]=="true"){
                                echo "&nbsp;&nbsp;<button class='btn btn-success' style='padding-left:5px;padding-right:5px;margin-top:-5px;height:30px;' type='button' onclick ='javascript:newrecord(\"".$formeditlist."\");'>Add New <i class='fa fa-plus' aria-hidden='true'></i></button>";
                             }
                             if($buttons["delete"]=="true"){
                                echo "&nbsp;&nbsp;<button class='btn btn-danger' style='padding-left:5px;padding-right:5px;margin-top:-5px;height:30px;' type='button'  onclick ='javascript:deleterecord_pro(\"".$formlistname."?pr=".$_SESSION['pr']."\");'>Delete <i class='fa fa-trash' aria-hidden='true'></i></button>";
                             }
                          }
                          echo "</td></table>";
                     }else{
                          echo "<table><tr><td >";
                          $addnew_li = '';
                          $delete_li = '';
                          $addnew_btn = $delete_btn = '';
                          if($buttons["delete"]=="true"){
                          	 $delete_li = " <li><a href='javascript:deleterecord_pro(\"".$formlistname."?pr=".$_SESSION['pr']."\");'><i class='fa fa-trash'></i>Delete</a></li>";
                                $delete_btn = "&nbsp;&nbsp;<button class='btn btn-danger' style='padding-left:5px;padding-right:5px;margin-top:-3px;height:30px;' type='button'  onclick ='javascript:deleterecord_pro(\"".$formlistname."?pr=".$_SESSION['pr']."\");'>Delete <i class='fa fa-trash' aria-hidden='true'></i></button>";
                          }
                          if($buttons["insert"]=="true"){
                          	 $addnew_li=" <li><a href='javascript:newrecord(\"".$formeditlist."\");'><i class='fa fa-plus'></i>Add New</a></li>";
                               $addnew_btn = "&nbsp;&nbsp;<button class='btn btn-success' style='padding-left:5px;padding-right:5px;margin-top:-3px;height:30px;' type='button' onclick ='javascript:newrecord(\"".$formeditlist."\");'>Add New <i class='fa fa-plus' aria-hidden='true'></i></button>";
                          }
                          if($buttons["delete"]=="true" && $buttons["insert"]=="true"){
                          echo '<div class="dropdown">
  <button class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown">Action
  <span class="caret"></span></button>
  <ul class="dropdown-menu dropdown-light-blue">
    '.$addnew_li.'
    '.$delete_li.'
  </ul>
</div>';
						 }
						 else if(($buttons["delete"]=="true" && $buttons["insert"]=="false") || ($buttons["delete"]=="false" && $buttons["insert"]=="true")){
						 	echo $addnew_btn; echo $delete_btn;
						 }
                          echo "</td></tr></table>";
                     }

 				if(isset($_REQUEST["txtsearch"])){
                   $txtsearch= $_REQUEST["txtsearch"];
                }else{
                   $txtsearch='';
                }
                    echo  "</span><div class='box-tools pull-right '>
                                    <div class='table-responsive'>
                                          <table class='table table-condensed smart-form' style='margin-top:-5px;'>
                                             <tr>
                                                <td>
                                                      <div class='input-group input-group-sm' style='width: 250px;'>
                                                                  <input type='text' name='txtsearch' id=txtsearch class='form-control pull-right' value='".$txtsearch."' placeholder='Search..'>

                                                                  <div class='input-group-btn'>
                                                                    <button type='submit' class='btn btn-default' onclick='javascript:searchPaging(\"".$formlistname."?pr=".$_SESSION['pr']."\");'><i class='fa fa-search'></i></button>
                                                                    <button type='submit' class='btn btn-default' onclick='javascript:refreshPaging(\"".$formlistname."?pr=".$_SESSION['pr']."\");'><i class='fa fa-refresh'></i></button>
                                                                  </div>

                                                      </div>
                                                </td>
                                                <td>

                                                                        <input id='$this->_fid' name='$this->_fid' type='hidden' value='$this->fid' />
                                                                        <input id='$this->_fvalue' name='$this->_fvalue' type='hidden' value='$this->fvalue' />
                                                                        <input id='$this->_sortid' name='$this->_sortid' type='hidden' value='$this->sortid' />
                                                                        <input id='$this->_sortdir' name='$this->_sortdir' type='hidden' value='$this->sortdir' />
                                                                        <input id='$this->_startrow' name='$this->_startrow' type='hidden' value='$this->startrow' />
                                                                        <input id='$this->_startpage' name='$this->_startpage' type='hidden' value='$this->startpage' />
                                                                        <input id='$this->_currentpage' name='$this->_currentpage' type='hidden' value='$this->currentpage' />
                                                                        <input id='$this->_endpage' name='$this->_endpage' type='hidden' value='$this->endpage' />
                                                                        <input id='frmPage_rowcount' name='frmPage_rowcount' type='hidden' value='".$frmPage_rowcount."' />
                                                                        <span style='float:left;padding-top:4px;'>Show </span>


                                                </td>
                                                <td>

                                                                        <select name='frmPage_rowcount' id='frmPage_rowcount' class='form-control' style='padding-left:5px;padding-right:5px;margin-top:-1px;width:50px;' onchange='javascript:document.frmPage.action=\"$formlistname?pr=".$_SESSION['pr']."\";document.frmPage.submit();' >
                                                                        <option  $selected1 value='15'>15</option>
                                                                        <option  $selected2 value='30'>30</option>
                                                                        <option  $selected3 value='45'>45</option>
                                                                        <option  $selected4 value='60'>60</option>
                                                                        </select>

                                                </td>
                                                <td>
                                                   <span style='float:right;padding-top:4px;'>Entries </span>
                                                </td>
                                             </tr>
                                           </table>
                                   </div>
                              </div>

                            </div>

                            <div class='box-body no-padding' id='box-body-id' style='overflow:hidden;float:left;' >

                                 <table class='table table-bordered table-condensed table-hover table-fixed' style='table-layout:fixed;'>
                                      <thead >
                                         <tr >";
                                           //echo $buttons['delete'];
                                           if ($buttons['delete']=="true")
                                           {
                                                  echo "<th class='bg-light-blue' style='width:2%;text-align:left;border-bottom:1px #2F3C43 solid;border-top:1px #fff solid;color:#FFFFFF'>";
                                                  echo "<input name='chklistcheck' id='chklistcheck' type='checkbox' onclick='javascript:checkall(\"frmPage\");'/>";
                                                  echo "</th>";
                                           }
                                           if ($buttons['delete']=="false")
                                           {
                                                  echo "<th class='bg-light-blue' style='width:2%;align:left;border-bottom:1px #2F3C43 solid;border-top:1px #fff solid;color:#FFFFFF'>";
                                                  echo "<input name='chklistcheck' id='chklistcheck' disabled type='checkbox' onclick='javascript:checkall(\"frmPage\");'/>";
                                                  echo "</th>";
                                           }
                                                  /*echo "<th class='bg-light-blue' style='width:2%;text-align:left;border-bottom:1px #2F3C43 solid;border-top:1px #fff solid;color:#FFFFFF'>";
                                                  echo "&nbsp;";
                                                  echo "</th>";*/
                                           $k = 0;

                                           while ($col = mysqli_fetch_field($result))
                                           {
                                                   if($k!=0){
                                                   echo "<th class='bg-light-blue' style='text-align:left;width:".$fieldSizes[$k]."%;border-bottom:1px #2F3C43 solid;border-top:1px #fff solid;color:#FFFFFF'>";

                                                   if($this->sortid=="id"){
                                                       echo "<a href='javascript:void(0);' onClick='javascript:document.$this->name.$this->_sortid.value=\"" . trim($col->name) . "\";
                                                             if (document.$this->name.$this->_sortdir.value == \"DESC\") document.$this->name.$this->_sortdir.value=\"ASC\";
                                                             else document.$this->name.$this->_sortdir.value=\"DESC\";document.$this->name.submit();'>
                                                             <font style='font-size: 13px;color:#fff300'>".$fieldNames[$k]."</font>";


                                                   }else{
                                                         if(trim($col->name)==$this->sortid){
                                                         if($this->sortdir=="ASC") $sort = "pull-right fa fa-sort-alpha-asc'";
                                                         if($this->sortdir=="DESC") $sort = "pull-right fa fa-sort-alpha-desc";
                                                          echo "<a href='javascript:void(0);' onClick='javascript:document.$this->name.$this->_sortid.value=\"" . trim($col->name) . "\";
                                                          if (document.$this->name.$this->_sortdir.value == \"DESC\") document.$this->name.$this->_sortdir.value=\"ASC\";
                                                          else document.$this->name.$this->_sortdir.value=\"DESC\";document.$this->name.submit();'>
                                                          <font style='font-size: 13px;color:#fff300'>".$fieldNames[$k]."</font>
                                                          </a><i class='$sort' aria-hidden='true'></i>";
                                                         }else{

                                                          echo "<a href='javascript:void(0);' onClick='javascript:document.$this->name.$this->_sortid.value=\"" . trim($col->name) . "\";
                                                               if (document.$this->name.$this->_sortdir.value == \"DESC\") document.$this->name.$this->_sortdir.value=\"ASC\";
                                                               else document.$this->name.$this->_sortdir.value=\"DESC\";document.$this->name.submit();'>
                                                               <font style='font-size: 13px;color:#fff300'>".$fieldNames[$k]."</font>";

                                                         }

                                                   }

                                                   echo "</th>";
                                                   }
                                                   $k++;
                                           }

                           echo          "</tr>
                                      </thead>
                                      </table>

                               <div id='box-body-id-rows' class='table-responsive' style='overflow:hidden;'>
                                      <table class='table table-bordered table-condensed table-striped' style='table-layout:fixed;'>
                                      <tbody>";
                                         if ($result)
                                         {
                                                 $l = 1;
                                                 while ($row = mysqli_fetch_array($result))
                                                 {
                                                         $i = 1;
                                                         $color = (($l % 2) == 0) ? 'TableRow' : 'TableRow';

                                                         echo "<tr>";
                                                         $l++;

                                                         $keys = $buttons['keys'];
                                                         $keyvalues = '';
                                                         foreach($keys as $val)
                                                         { 
                                                                 $keyvalues .= "\"$row[$val]\", ";
                                                         }
                                                         $keyvalues = rtrim($keyvalues, ", ");

                                                         //for setting row highlight
                                                         
                                                          if(isset($_REQUEST["ID"])){
														        $tableid= $_REQUEST["ID"];
														    }else{
														        $tableid='';
														    }
    
                                                          if($tableid==$row[0]){
                                                                  $colorbg ="#000000";
                                                                  $colorfc ="#FFFFFF";
                                                          }else{
                                                                  $colorbg =$color;
                                                                  $colorfc ="#000000";
                                                          }

                                                         if ($buttons['delete']=="true"){
                                                                 echo "<td style='width:2%;border:1px #D2D2D2 solid;'>";
                                                                 if(($formlistname=="emgcalloutrequest.php" || $formlistname=="calloutrequest.php" || $formlistname=="otleadheadlist.php" || $formlistname=="newleadheadlist.php" || $formlistname=="salesorderheadlist.php" || $formlistname=="otsalesorderheadlist.php" || $formlistname=="emgsalesorderheadlist.php" || $formlistname=="quoteheadlist.php" || $formlistname=="otquoteheadlist.php" || $formlistname=="emgquoteheadlist.php") && ($row['stcheck']!="Enquiry Open")  ){    //|| getEnqUserid($row['id'])!=$_SESSION['SESSuserID'])
                                                                   echo "<span class='glyphicon glyphicon-lock' style='color:#502771'></span>";
                                                                 }else if(($formlistname=="menulist.php") && $row['id']=="1" ){
                                                                   echo "<span class='glyphicon glyphicon-lock' style='color:#502771'></span>";
                                                                 }else if($formlistname=="crmcontactlist.php" && getUserid($row['id'])!=$_SESSION['SESSuserID']){
                                                                   echo "<span class='glyphicon glyphicon-lock' style='color:#502771'></span>";
                                                                 }else if($formlistname=="crmteam.php" && getTeamUserid($row['id'])!=$_SESSION['SESSuserID']){
                                                                   echo "<span class='glyphicon glyphicon-lock' style='color:#502771'></span>";
                                                                 }else if($formlistname=="crmleadvisitlistcontact.php" && getActivityUserid($row['id'])!=$_SESSION['SESSuserID']){
                                                                   echo "<span class='glyphicon glyphicon-lock' style='color:#502771'></span>";
                                                                 }else if(($formlistname=="manpowercategory.php" || $formlistname=="manpower.php" || $formlistname=="manpowerdesignation.php") && $row['posted']=="Yes"){
                                                                   echo "<span class='glyphicon glyphicon-lock' style='color:#502771'></span>";
                                                                 }else{
                                                                   echo "<input type='checkbox' name=Chk_Del_".$row[0]." id=Chk_Del_".$row[0]."  />";
                                                                 }
                                                                 echo "</td>";
                                                         }else{
                                                                 echo "<td style='width:2%;border:1px #D2D2D2 solid;'>";
                                                                 echo "<span class='glyphicon glyphicon-lock' style='color:#502771'></span>";
                                                                 echo "</td>";
                                                         }

                                                                 /*echo "<td style='width:2%;border:1px #D2D2D2 solid;'>";
                                                                 echo "<a href='javascript:void(0);' onclick='javascript:editRecord($keyvalues,\"\",\"".$formeditlist."\",\"edit\");return false;'>x<span class='glyphicon glyphicon-pencil'></span></a>";
                                                                 echo "</td>";*/

                                                         if($formlistname=="emgcalloutrequest.php" || $formlistname=="calloutrequest.php" || $formlistname=="otleadheadlist.php" || $formlistname=="newleadheadlist.php" || $formlistname=="quoteheadlist.php" || $formlistname=="otquoteheadlist.php" || $formlistname=="salesorderheadlist.php" || $formlistname=="emgsalesorderheadlist.php" || $formlistname=="otsalesorderheadlist.php" || $formlistname=="emgquoteheadlist.php"){
                                                             $coordinator=checksuserid($con,$row['id']);
                                                             $color="black";
                                                             if($coordinator!=""){
                                                                $color="green";
                                                             }
                                                         }
                                                         if($formlistname=="assigntask.php"){

                                                            if($row['status']=="Pending"){
                                                                  if(strtotime($row['taskdate'])<strtotime(date('d-m-Y'))){
                                                                     $color = "#D35400";
                                                                  }
                                                            }else if($row['status']=="Completed"){
                                                               $color="green";
                                                            }else{
                                                               $color="black";
                                                            }

                                                         }

                                                         while ($i < mysqli_num_fields($result))
                                                         {


                                                            $rowvalue =  $row[$i];
                                                            $getpriority=getticketpriority($con,$row[0]);
                                                            $getClientType = getClientType($con,$row[0]);

                                                            if($formlistname=="crmcontactlist.php" && $getClientType == "Lead"){
                                                                   $fontcolor='red';
                                                            }
                                                            else if($formlistname=="crmcontactlist.php" && $getClientType == "Customer"){
                                                                   $fontcolor='green';
                                                            }
                                                            else if($formlistname=="newleadheadlist.php" || $formlistname=="quoteheadlist.php" || $formlistname=="salesorderheadlist.php" || $formlistname=="calloutrequest.php" || $formlistname=="emgcalloutrequest.php" || $formlistname=="emgsalesorderheadlist.php" || $formlistname=="emgquoteheadlist.php" || $formlistname=="otleadheadlist.php" || $formlistname=="otquoteheadlist.php" || $formlistname=="otsalesorderheadlist.php"){
                                                                    $fontcolor=getFontColorBasedOnStatus($con,$row[0]);
                                                                    //if($formlistname=="salesorderheadlist.php") {
                                                                                                                                //                   $fontcolor=getFontColorBasedOnContractDocStatus($row[0]);
                                                                                                                                //}

                                                            }
                                                            else{
                                                                  $fontcolor='#000000';
                                                            }
															$mysql_field_name = mysqli_fetch_field_direct($result,$i)->name;
                                                            if($mysql_field_name=="stcheck") {
                                                                   $Status_Arr =  explode('@@',getRowStatus($con,$row[$i]));
                                                                   $statusname =  $Status_Arr[0];
                                                                   $imagefile  =  $Status_Arr[1];

                                                                   $username = getUserNameBasedOnStatus($con,$statusname,$row[0]);
                                                                   $Title = $statusname;
                                                                   if($username!="") $Title = $statusname." : ".$username;
                                                            }
                                                            if($mysql_field_name=="stcheck" && $rowvalue=="Open" && ($formlistname=="calloutrequest.php" || $formlistname=="emgcalloutrequest.php" || $formlistname=="calloutrequest.php" || $formlistname=="otleadheadlist.php" || $formlistname=="newleadheadlist.php" || $formlistname=="quoteheadlist.php" || $formlistname=="otquoteheadlist.php" || $formlistname=="salesorderheadlist.php" || $formlistname=="otsalesorderheadlist.php" || $formlistname=="emgquoteheadlist.php" || $formlistname=="emgsalesorderheadlist.php")){
                                                                   echo "<td style='border:1px #D2D2D2 solid;font-weight:400;text-align:".$fieldAlign[$i].";width:".$fieldSizes[$i]."%;'>";
                                                                   echo "<img src='img/open.png' title='$rowvalue'  data-toggle='tooltip' data-placement='left' width='16' height='16'>";
                                                                   echo "</td>";
                                                            }
                                                            else if($mysql_field_name=="stcheck" && strtoupper($rowvalue)==strtoupper($statusname) && ($formlistname=="calloutrequest.php" || $formlistname=="emgcalloutrequest.php" || $formlistname=="calloutrequest.php" || $formlistname=="otleadheadlist.php" || $formlistname=="newleadheadlist.php" || $formlistname=="quoteheadlist.php" || $formlistname=="otquoteheadlist.php" || $formlistname=="salesorderheadlist.php" || $formlistname=="otsalesorderheadlist.php" || $formlistname=="emgquoteheadlist.php" || $formlistname=="emgsalesorderheadlist.php")){
                                                                   echo "<td style='border:1px #D2D2D2 solid;font-weight:400;text-align:".$fieldAlign[$i].";width:".$fieldSizes[$i]."%;'>";
                                                                   echo "<img src='img/$imagefile' title='$Title'  data-toggle='tooltip' data-placement='left' width='16' height='16'>";
                                                                   echo "</td>";
                                                            }
                                                            else if($mysql_field_name=="docno" && ($formlistname=="emgcalloutrequest.php" || $formlistname=="calloutrequest.php" || $formlistname=="otleadheadlist.php" || $formlistname=="newleadheadlist.php" || $formlistname=="quoteheadlist.php" || $formlistname=="otquoteheadlist.php" || $formlistname=="emgquoteheadlist.php" || $formlistname=="salesorderheadlist.php" || $formlistname=="otsalesorderheadlist.php" || $formlistname=="emgsalesorderheadlist.php") ){

                                                               if(getunreadmsg($con,$rowvalue)>0){
                                                                $unreadmsg="<span class='label label-danger'>".getunreadmsg($con,$rowvalue)."</span>&nbsp;";
                                                               }else{
                                                                $unreadmsg="";
                                                               }
                                                               echo "<td style='border:1px #D2D2D2 solid;font-weight:400;text-align:".$fieldAlign[$i].";width:".$fieldSizes[$i]."%;'>";
                                                               echo "$unreadmsg&nbsp;<font color='$fontcolor'>$rowvalue</font>";
                                                               echo "</td>";
                                                             }
                                                             else{
                                                                echo "<td style='border:1px #D2D2D2 solid;word-wrap: break-word;font-weight:400;text-align:".$fieldAlign[$i].";width:".$fieldSizes[$i]."%;color:$color;'>";
                                                                echo "<a href='javascript:void(0);' onclick='javascript:editRecord($keyvalues,\"\",\"".$formeditlist."\",\"edit\");return false;'>$rowvalue</a>";
                                                               // echo "<font color='$fontcolor'>$rowvalue</font>";
                                                                echo "</td>";

                                                             }
                                                               $i++;
                                                         }
                                                           echo "</tr>";



                                                 }
                                         }
                       echo              "</tbody>
                                 </table>


                              </div>
                            </div>

                            <div class='box-footer' id='box-footer-id' style='border-top:1px #D2D2D2 solid;'>
                                <div class='box-tools pull-right' style='margin-top:-5px;'>&nbsp;";
                                
                                if(isset($_REQUEST["txtgopage"])){
							        $txtgopage= $_REQUEST["txtgopage"];
							    }else{
							        $txtgopage='';
							    }
    							$pagenumber='';
    							$gostartrow ='';
    							$gostartpage = '';
    							$goendpage = '';
    							
                                if($txtgopage!=''){
                                   $gostartrow= ($txtgopage-1)*10;
                                   $gostartpage= 51;
                                   $goendpage= 60;
                                }


                                  $this->navigationLinks($formlistname,$totalrec, $this->rowcount, $this->startpage, $this->currentpage, $this->endpage,
                                                  $this->sortid, $this->sortdir, $this->fid, $this->fvalue,$pagenumber,$gostartrow,$gostartpage,$goendpage,$this->startrow);


                     echo  "</div>
                            </div>


                </section></form>";
        }
        private function navigationLinks($formlistname,$totalrec, $rowcount, $startpage, $currentpage, $endpage, $sortid, $sortdir, $fid, $fvalue,$pagenumber,$gostartrow,$gostartpage,$goendpage,$startrow)
        {

                $pagecount = ceil($totalrec / $rowcount);
                $start = ($startpage - 1) * $rowcount;
                echo "<ul class='pagination pagination-sm no-margin pull-right'>";
                if ($currentpage > 1)
                {
                        $fcurrentpage = $currentpage - 1;
                        $fstartpage = $startpage - 10;
                        $fendpage = $endpage - 15;

                        echo "<li><a href='#' onclick='javascript:document.$this->name.$this->_startrow.value=\"$start\";
                                        document.$this->name.$this->_startpage.value=\"$fstartpage\";document.$this->name.$this->_currentpage.value=\"$fcurrentpage\";
                                        document.$this->name.$this->_endpage.value=\"$fendpage\";document.frmPage.action=\"$formlistname?pr=".$_SESSION['pr']."\";document.$this->name.submit();'><span aria-hidden='true'>&laquo;</span></a></li>";
                }
                echo "</ul>";

                $count = 1;
                $startfrom = $startpage;
                $totalpages = 10;
                
                if(isset($_REQUEST["frmPage_rowcount"])){
                   $frmPage_rowcount= $_REQUEST["frmPage_rowcount"];
                }else{
                   $frmPage_rowcount='';
                }
                
                if($frmPage_rowcount==''){
                   $pagerecords=15;
                }else{
                   $pagerecords=$frmPage_rowcount;
                }
                
                if ($pagecount <= 15)
                        $totalpages = $pagecount;
                else if (ceil($pagecount / $totalpages) == $currentpage)
                        $totalpages = $pagecount % $totalpages;
                $pageno= round(($this->startrow + $this->rowcount)/$pagerecords);

                echo "<div class='info-pages pull-right'>Result ".$startrow . "-" . ($startrow + $rowcount)." of total " . $totalrec . " Records</div>"  ;

                echo "<ul class='pagination pagination-sm no-margin pull-right'>";
                while ($count <= $totalpages)
                {

                        if($pageno==$startfrom){
                          $class='active' ;
                        }else{
                          $class='' ;
                        }
                      if($pageno>0){
                        if($startfrom==$pagenumber){
                          echo "<li class='$class'><a href='#' onclick='javascript:document.$this->name.$this->_startrow.value=\"$start\";
                                        document.$this->name.$this->_startpage.value=\"$startpage\";document.$this->name.$this->_currentpage.value=\"$currentpage\";
                                        document.$this->name.$this->_endpage.value=\"$endpage\";document.frmPage.action=\"$formlistname?pr=".$_SESSION['pr']."\";document.$this->name.submit();'>&nbsp;<B><font color='000000' style='text-decoration:underline'>$pagenumber</font></B>&nbsp;</a></li>";

                        }else{
                          echo "<li class='$class'><a href='#' onclick='javascript:document.$this->name.$this->_startrow.value=\"$start\";
                                        document.$this->name.$this->_startpage.value=\"$startpage\";document.$this->name.$this->_currentpage.value=\"$currentpage\";
                                        document.$this->name.$this->_endpage.value=\"$endpage\";document.frmPage.action=\"$formlistname?pr=".$_SESSION['pr']."\";document.$this->name.submit();'>&nbsp;$startfrom&nbsp;</a></li>";

                        }
                      }
                        $count += 1;
                        $startfrom += 1;
                        $start += $rowcount;



                }

                if (($totalpages * $currentpage) < $pagecount && $totalpages == 10)
                {
                        $lcurrentpage = $currentpage + 1;
                        $lstartpage = $endpage + 1;
                        $lendpage = $endpage + 10;

                       echo "<li><a href='#'  onclick='javascript:document.$this->name.$this->_startrow.value=\"$start\";
                                        document.$this->name.$this->_startpage.value=\"$lstartpage\";document.$this->name.$this->_currentpage.value=\"$lcurrentpage\";
                                        document.$this->name.$this->_endpage.value=\"$lendpage\";document.frmPage.action=\"$formlistname?pr=".$_SESSION['pr']."\";document.$this->name.submit();'><span aria-hidden='true'>&raquo;</span></a></li>";
                }

                echo "</ul>";

                   echo "<div class='info-pages'>Page $pageno of $pagecount</div>";






        }
        private function makecombobox($seldata,$SQLquery,$table,$where,$lablename){
             $comboout .= $lablename.":&nbsp;";
             $comboout .= "<select name='cmbtopcombo1' id='cmbtopcombo1' class=topcombo onchange='javascript:if(document.frmPage) document.frmPage.submit();' size='1'>";
             $comboout .= "<option value='9999'>Select</option>";
             if($where!="")$where = $where ." and";
             if($table=="m_lookup"){
                $SQL   = "SELECT ".$SQLquery." FROM ".$table." WHERE ".$where."
                          companycode='".$_SESSION["SESScompanycode"]."' order by id";
             }else{
                $SQL   = "SELECT ".$SQLquery." FROM ".$table." WHERE ".$where."
                          locationcode='".$_SESSION["SESSUserLocation"]."' and companycode='".$_SESSION["SESScompanycode"]."' order by id";
             }
             //echo $SQL;
             $SQLRes =  mysqli_query($con,$SQL) or die(mysqli_error()."<br>".$SQL);
                     if(mysqli_num_rows($SQLRes)>=1){
                       while($loginResultArray   = mysqli_fetch_array($SQLRes)){
                        if($seldata==$loginResultArray[0]){
                          $comboout .= "<option value='".$loginResultArray[0]."' selected='selected'>".$loginResultArray[1]."</option>";
                         }else{
                          $comboout .= "<option value='".$loginResultArray[0]."'>".$loginResultArray[1]."</option>";
                         }
                       }
                     }
              $comboout .= "</select>";
              $comboout .= "&nbsp;";
              echo $comboout;
          }
          function SyncSession($object){
                   $_SESSION['CurrentObjectName'] =$object;
          }
}
function checkissueid($id){
             $delete ="NO";
             $SQL = "Select userid from in_articlehead where id ='$id'";
             $SQLRes =  mysqli_query($con,$SQL) or die(mysqli_error()."<br>".$SQL);
             while($loginResultArray   = mysqli_fetch_array($SQLRes)){
                 if($_SESSION['SESSuserID']==$loginResultArray['userid']){
                   $delete="YES";
                 }
             }
             return $delete;
}
function checkmrid($id){
             $delete ="NO";
             $SQL = "Select userid from in_itemhead where id ='$id'";
             $SQLRes =  mysqli_query($con,$SQL) or die(mysqli_error()."<br>".$SQL);
             while($loginResultArray   = mysqli_fetch_array($SQLRes)){
                 if($_SESSION['SESSuserID']==$loginResultArray['userid']){
                   $delete="YES";
                 }
             }
             return $delete;
}
function checksuserid($con,$id){

             $SQL = "Select suserid from in_crmhead where id ='$id'";
             $SQLRes =  mysqli_query($con,$SQL) or die(mysqli_error()."<br>".$SQL);
             while($loginResultArray   = mysqli_fetch_array($SQLRes)){
                   $sco = $loginResultArray['suserid'];
             }
             return $sco;
}
function getUserid($id){

             $SQL = "Select userid from in_businessobject where id ='$id'";
             $SQLRes =  mysqli_query($con,$SQL) or die(mysqli_error()."<br>".$SQL);
             while($loginResultArray   = mysqli_fetch_array($SQLRes)){
                   $userid = $loginResultArray['userid'];
             }
             return $userid;
}
function getEnqUserid($id){

             $SQL = "Select userid from in_crmhead where id ='$id'";
             $SQLRes =  mysqli_query($con,$SQL) or die(mysqli_error()."<br>".$SQL);
             while($loginResultArray   = mysqli_fetch_array($SQLRes)){
                   $userid = $loginResultArray['userid'];
             }
             return $userid;
}
function getActivityUserid($id){

             $SQL = "Select userid from in_crmvisit where id ='$id'";
             $SQLRes =  mysqli_query($con,$SQL) or die(mysqli_error()."<br>".$SQL);
             while($loginResultArray   = mysqli_fetch_array($SQLRes)){
                   $userid = $loginResultArray['userid'];
             }
             return $userid;
}
function getTeamUserid($id){

             $SQL = "Select userid from in_crmteam where id ='$id'";
             $SQLRes =  mysqli_query($con,$SQL) or die(mysqli_error()."<br>".$SQL);
             while($loginResultArray   = mysqli_fetch_array($SQLRes)){
                   $userid = $loginResultArray['userid'];
             }
             return $userid;
}
function getunreadmsg($con,$id){
      $SQL = "Select count(*) as count from tbl_message,in_crmhead where in_crmhead.id=tbl_message.ticketno and
      tbl_message.viewedby not like '%".$_SESSION['SESSuserID']."%' and in_crmhead.docno='$id' and tbl_message.formtype='CRM'";
      $SQLRes =  mysqli_query($con,$SQL) or die(mysqli_error()."<br>".$SQL);
      if(mysqli_num_rows($SQLRes)>=1){
        while($loginResultArray   = mysqli_fetch_array($SQLRes)){
            $count=$loginResultArray['count'];
        }
      }
      return $count;
}
function getticketpriority($con,$id){
	$priority = "";
      $SQL = "Select priority from in_crmhead where id ='$id'";
      $SQLRes =  mysqli_query($con,$SQL) or die(mysqli_error()."<br>".$SQL);
      if(mysqli_num_rows($SQLRes)>=1){
        while($loginResultArray   = mysqli_fetch_array($SQLRes)){
            $priority=$loginResultArray['priority'];
            //$requeststatus= $loginResultArray['requeststatus'];

        }
}
return $priority;
}
function getClientType($con,$id){
      $SQL = "Select objecttype from in_businessobject where id ='$id'";
      $SQLRes =  mysqli_query($con,$SQL) or die(mysqli_error()."<br>".$SQL);
      if(mysqli_num_rows($SQLRes)>=1){
        while($loginResultArray   = mysqli_fetch_array($SQLRes)){
            $objecttype=$loginResultArray['objecttype'];
      }
	  }
	  else {
	  	$objecttype = '';
	  }
	  return $objecttype;
}
function getRowStatus($con,$statusname){
      $SQL = "select statusname,imagename from tbl_status where statusname = '$statusname'";
      $SQLRes =  mysqli_query($con,$SQL) or die(mysqli_error()."<br>".$SQL);
      if(mysqli_num_rows($SQLRes)>=1){
            $loginResultArray   = mysqli_fetch_array($SQLRes);
            $statusname = trim($loginResultArray['statusname']);
            $imgname = trim($loginResultArray['imagename']);
      }

return $statusname.'@@'.$imgname;
}
function getUserNameBasedOnStatus($con,$statusname,$id) {
         $SQL = "select doctype, enquiryby,userid, suserid,fmuser,formsendto,servicestaff,enquirycategory from in_crmhead where id='$id' ";
         $RES = mysqli_query($con,$SQL) or die(mysqli_error()."<br>".$SQL);
         $ARR   = mysqli_fetch_array($RES);
         $user = "";
         if(strtoupper($statusname) == "ENQUIRY OPEN" && ($ARR['doctype']=='LEAD' || $ARR['doctype']=='EMG CALLOUT' || $ARR['doctype']=='AMC CALLOUT')) $user = GetUserName_forStatus($con,$ARR['enquiryby']);
         else if(strtoupper($statusname) == strtoupper("Waiting for Inspection Details") && $ARR['doctype']=='LEAD' && $ARR['enquirycategory']=='AMC Enquiry') $user = GetUserName_forStatus($con,$ARR['userid']);
         else if(strtoupper($statusname) == strtoupper("Waiting for (Q1) preparation") && ($ARR['doctype']=='LEAD' || $ARR['doctype']=='QUOTE')) $user = GetUserName_forStatus($con,$ARR['userid']);
         else if( (strtoupper($statusname) == strtoupper("Waiting for (Q1) Manager approval") || strtoupper($statusname) == strtoupper("(Q1) Approved by Manager") || strtoupper($statusname) == strtoupper("(Q1) Cancelled by Manager"))&& ($ARR['doctype']=='QUOTE')) $user = GetUserName_forStatus($con,$ARR['formsendto']);
         else if(strtoupper($statusname) == strtoupper("Waiting for (SO) details") && ($ARR['doctype']=='QUOTE' || $ARR['doctype']=='ORDER')) $user = GetUserName_forStatus($con,$ARR['userid']);
         else if(strtoupper($statusname) == strtoupper("Waiting for (SO) Manager approval") && ($ARR['doctype']=='ORDER')) $user = GetUserName_forStatus($con,$ARR['formsendto']);
         else if(strtoupper($statusname) == strtoupper("Waiting for (SO) Accounts Response") && ($ARR['doctype']=='ORDER')) $user = GetUserName_forStatus($con,$ARR['fmuser']);
         else if(strtoupper($statusname) == strtoupper("Contract Creation under process") && ($ARR['doctype']=='ORDER')) $user = GetUserName_forStatus($con,$ARR['servicestaff']);

         else if(strtoupper($statusname) == strtoupper("Waiting for Inspection Details") && ($ARR['doctype']=='LEAD' && $ARR['enquirycategory']=='OT Enquiry')) $user = GetUserName_forStatus($con,$ARR['suserid']);
         else if(strtoupper($statusname) == strtoupper("Waiting for (Q2) preparation") && ($ARR['doctype']=='LEAD' || $ARR['doctype']=='QUOTE')) $user = GetUserName_forStatus($con,$ARR['userid']);
         else if( (strtoupper($statusname) == strtoupper("Waiting for (Q2) Manager approval") || strtoupper($statusname) == strtoupper("(Q2) Approved by Manager") || strtoupper($statusname) == strtoupper("(Q2) Revised by Manager") || strtoupper($statusname) == strtoupper("(Q2) Rejected by Manager"))&& ($ARR['doctype']=='QUOTE')) $user = GetUserName_forStatus($con,$ARR['formsendto']);
         else if(  strtoupper($statusname) == strtoupper("(SO) Rejected by Manager")&& ($ARR['doctype']=='ORDER')) $user = GetUserName_forStatus($con,$ARR['formsendto']);
         else if(strtoupper($statusname) == strtoupper("Waiting for payment update") && ($ARR['doctype']=='ORDER')) $user = GetUserName_forStatus($con,$ARR['fmuser']);
         else if(strtoupper($statusname) == strtoupper("Job Order created and waiting for job assigning") && ($ARR['doctype']=='ORDER')) $user = GetUserName_forStatus($con,$ARR['servicestaff']);
         else if(strtoupper($statusname) == strtoupper("Waiting for (EMG ENQ) Manager approval"))  $user = GetUserName_forStatus($con,$ARR['formsendto']);
         else if(strtoupper($statusname) == strtoupper("Waiting for (EMG ENQ) Accounts Response")) $user = GetUserName_forStatus($con,$ARR['fmuser']);
         else if(strtoupper($statusname) == strtoupper("Waiting for Accounts to Update Job Order")) $user = GetUserName_forStatus($con,$ARR['fmuser']);
         return $user;
}

function GetUserName_forStatus($con,$userid) {
         $SQL = "select SUBSTRING_INDEX(in_user.username, ' ', 1) as  username from in_user where userid='$userid' ";
         $RES = mysqli_query($con,$SQL) or die(mysqli_error()."<br>".$SQL);
         $ARR   = mysqli_fetch_array($RES);
         return $ARR['username'];
}
function getFontColorBasedOnContractDocStatus($con,$id){
                 $SQL = "select documentstatus from in_crmhead where id='$id' ";
         $RES = mysqli_query($con,$SQL) or die(mysqli_error()."<br>".$SQL);
         $ARR   = mysqli_fetch_array($RES);
         if(strtoupper($ARR['documentstatus']) != "SIGNED COPY RECEIVED")
         $font = 'red';
         else $font = getFontColorBasedOnStatus($con,$id);
         return $font;
}
function getFontColorBasedOnStatus($con,$id){
         $SQL = "select doctype, enquiryby,userid, suserid,fmuser,formsendto,servicestaff,enquirycategory,stcheck,posted from in_crmhead where id='$id' ";
         $RES = mysqli_query($con,$SQL) or die(mysqli_error()."<br>".$SQL);
         $ARR   = mysqli_fetch_array($RES);
         $statusname = $ARR['stcheck'];
         $font = '#000';
         if(strtoupper($statusname) == "ENQUIRY OPEN" && ($ARR['doctype']=='LEAD' || $ARR['doctype']=='EMG CALLOUT' || $ARR['doctype']=='AMC CALLOUT') && $ARR['enquiryby']== $_SESSION['SESSuserID'] && $ARR['posted']!='YES') $font = 'red';
         else if(strtoupper($statusname) == strtoupper("Waiting for Inspection Details") && $ARR['doctype']=='LEAD' && $ARR['enquirycategory']=='OT Enquiry' && $ARR['suserid']== $_SESSION['SESSuserID']) $font = 'red';
         else if( (strtoupper($statusname) == strtoupper("Waiting for (Q2) preparation") || strtoupper($statusname) == strtoupper("(Q2) Approved by Manager") || strtoupper($statusname) == strtoupper("(Q2) Revised by Manager") )&&  $ARR['doctype']=='QUOTE' && $ARR['userid']== $_SESSION['SESSuserID'] ) $font = 'red';
         else if( strtoupper($statusname) == strtoupper("Waiting for (Q2) Manager approval") && $ARR['doctype']=='QUOTE' && (stripos(json_encode($ARR['formsendto']),$_SESSION['SESSuserID']) == true)) $font = 'red';
         else if( strtoupper($statusname) == strtoupper("Waiting for (SO) details") && $ARR['doctype']=='ORDER' && (stripos(json_encode($ARR['userid']),$_SESSION['SESSuserID']) == true)) $font = 'red';
         else if( strtoupper($statusname) == strtoupper("Waiting for (SO) Manager approval") && $ARR['doctype']=='ORDER' && (stripos(json_encode($ARR['formsendto']),$_SESSION['SESSuserID']) == true)) $font = 'red';
         else if( strtoupper($statusname) == strtoupper("(SO) Revised by Manager") && $ARR['doctype']=='ORDER' && (stripos(json_encode($ARR['userid']),$_SESSION['SESSuserID']) == true)) $font = 'red';
         else if( strtoupper($statusname) == strtoupper("Waiting for payment update") && $ARR['doctype']=='ORDER' && (stripos(json_encode($ARR['fmuser']),$_SESSION['SESSuserID']) == true)) $font = 'red';
         else if(strtoupper($statusname) == strtoupper("Waiting for (EMG ENQ) Manager approval")  && (stripos(json_encode($ARR['formsendto']),$_SESSION['SESSuserID']) == true)) $font = 'red';
         else if(strtoupper($statusname) == strtoupper("(EMG ENQ) Revised by Manager") && (stripos(json_encode($ARR['enquiryby']),$_SESSION['SESSuserID']) == true)) $font = 'red';
         else if(strtoupper($statusname) == strtoupper("Waiting for (EMG ENQ) Accounts Response") && (stripos(json_encode($ARR['fmuser']),$_SESSION['SESSuserID']) == true)) $font = 'red';
         else if(strtoupper($statusname) == strtoupper("Waiting for Accounts to Update Job Order") && (stripos(json_encode($ARR['fmuser']),$_SESSION['SESSuserID']) == true)) $font = 'red';

         else if(strtoupper($statusname) == strtoupper("Waiting for Inspection Details") && $ARR['doctype']=='LEAD' && $ARR['enquirycategory']=='AMC Enquiry' && $ARR['userid']== $_SESSION['SESSuserID']) $font = 'red';
         else if((strtoupper($statusname) == strtoupper("Waiting for (Q1) preparation") || strtoupper($statusname) == strtoupper("(Q1) Approved by Manager") || strtoupper($statusname) == strtoupper("(Q1) Revised by Manager")) &&  $ARR['doctype']=='QUOTE' && $ARR['userid']== $_SESSION['SESSuserID']) $font = 'red';
         else if( strtoupper($statusname) == strtoupper("Waiting for (Q1) Manager approval") && $ARR['doctype']=='QUOTE'  && (stripos(json_encode($ARR['formsendto']),$_SESSION['SESSuserID']) == true)) $font = 'red';
         else if( strtoupper($statusname) == strtoupper("Waiting for (SO) Accounts Response") && $ARR['doctype']=='ORDER' && (stripos(json_encode($ARR['fmuser']),$_SESSION['SESSuserID']) == true)) $font = 'red';
         return $font;
}
?>
