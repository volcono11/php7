<?php
    ob_start();
    session_start();
    include "connection.php";
       //print_r($_REQUEST);
       
   /* if($_REQUEST['TYPE']=='WORKFLOW_SETUP'){
			$addsql  = "";
			if($_REQUEST['childid']!=""){
			 $addsql = " and id<>'".$_REQUEST['childid']."'";
			}

			$SEL = " Select * from tbl_workflowline where parentid='".$_REQUEST['txt_A_parentid']."' and buildingcode='".$_REQUEST['cmb_A_buildingcode']."' and category='".$_REQUEST['cmb_A_category']."' $addsql";
			$dis = mysql_query($SEL);
			if(mysql_num_rows($dis)>=1){
			      echo "Yes";
			}else{
			      echo "No";
			}
        }
       */
    /*if($_REQUEST['TYPE']=='PROJECTBUILDING'){
             $addsql2  = "";
            if($_REQUEST['childid']!=""){
                 $addsql2 = " and  id <>'".$_REQUEST['childid']."'";
             }
             
            $SEL = " select * from tbl_projectbuilding where buildingid='".$_REQUEST['cmb_A_buildingid']."' and buildingstatus='Active' and  ( projectcode<>'".$_REQUEST['txt_A_projectcode']."' or posted!='YES') $addsql2";
           	$dis = mysql_query($SEL);
           	
           	if(mysql_num_rows($dis)>=1){
                echo "Yes";
           	}else{
                echo "No";
           	}
    }
       if($_REQUEST['TYPE']=='CONTRACTINCHARGES'){
             $addsql2  = "";
            if($_REQUEST['childid']!=""){
                 $addsql2 = " and  id <>'".$_REQUEST['childid']."'";
             }
             
            $SEL = " select * from in_incharges where jobno='".$_REQUEST['txt_A_jobno']."' and inchargetype='".$_REQUEST['cmb_A_inchargetype']."' and  inchargename='".$_REQUEST['cmb_A_inchargename']."' and inchargedesignation='".$_REQUEST['cmb_A_inchargedesignation']."' and type='".$_REQUEST['txt_A_type']."' $addsql2";
           	$dis = mysql_query($SEL);
           	
           	if(mysql_num_rows($dis)>=1){
                echo "Yes";
           	}else{
                echo "No";
           	}
           	
        }
       
       if($_REQUEST['TYPE']=='PROJECTCONTRACT'){
             $addsql2  = "";
            if($_REQUEST['childid']!=""){
                 $addsql2 = " and  id <>'".$_REQUEST['childid']."'";
             }
             
            $SEL = " Select * from tbl_projectcontracts where contractcode='".$_REQUEST['cmb_A_contractcode']."'  and  ( projectcode<>'".$_REQUEST['txt_A_projectcode']."' or posted!='YES') $addsql2"; //and contractstatus='Active'
           	$dis = mysql_query($SEL);
           	
           	if(mysql_num_rows($dis)>=1){
                echo "Yes";
           	}else{
                echo "No";
           	}
        }*/
       /*
            if($_REQUEST['TYPE']=='PROJECTCONTRACT'){
             $addsql2  = "";
            if($_REQUEST['childid']!=""){
                 $addsql2 = " and  id <>'".$_REQUEST['childid']."'";
             }
            $sql = "Select * from tbl_projectcontracts where projectcode='".$_REQUEST['txt_A_projectcode']."' and contractstatus='Active' "; //and posted='YES'
            $res = mysql_query($sql);
            $pcount = mysql_num_rows($res);
            if($pcount == 0) { // if loop
	            $SEL = " Select * from tbl_projectcontracts where contractcode='".$_REQUEST['cmb_A_contractcode']."' and contractstatus='Active' and ( projectcode<>'".$_REQUEST['txt_A_projectcode']."' or posted!='YES') $addsql2";
	           	$dis = mysql_query($SEL);
	           	
	           	if(mysql_num_rows($dis)>=1){
	                echo "Yes";
	           	}else{
	                echo "No";
	           	}
			} // end of if
			else{
				while($parr = mysql_fetch_array($res)) {
					if($parr['contractcode'] == $_REQUEST['cmb_A_contractcode']) {
						$SEL = " Select * from tbl_projectcontracts where contractcode='".$_REQUEST['cmb_A_contractcode']."' and contractstatus='Active' and ( projectcode<>'".$_REQUEST['txt_A_projectcode']."' or posted!='YES') $addsql2";
			           	$dis = mysql_query($SEL);
			           	
			           	if(mysql_num_rows($dis)>=1){
			                echo "Yes";
			           	}else{
			                echo "No";
			           	}
					} 	
					else{
						echo "Yes";
					}
				}
			}
        }
        */
       
      /* if($_REQUEST['TYPE']=='BUILDINGSERVICEASSET'){
             $addsql  = "";
             if($_REQUEST['childid']!=""){
                 $addsql = " and id<>'".$_REQUEST['childid']."'";
             }
           //if($_REQUEST['cmb_A_buildingcode']!="" && $_REQUEST['cmb_A_assettype']!="" && $_REQUEST['cmb_A_assetdescription']!=""){
              $SEL = " Select * from tbl_buildingasset where buildingid='".$_REQUEST['txt_A_buildingid']."'
              and servicetype='".$_REQUEST['cmb_A_servicetype']."' and assetdescription='".$_REQUEST['cmb_A_assetdescription']."' $addsql";
           //}
           $dis = mysql_query($SEL);
           if(mysql_num_rows($dis)>=1){
                  echo "Yes";
           }else{
                  echo "No";
           }
        }
       
       if($_REQUEST['TYPE']=='AddManpowertoJob'){
             $addsql  = "";
             if($_REQUEST['updateid']!="" && $_REQUEST['MODE']=='UPDATE'){
                 $addsql = " and id<>'".$_REQUEST['updateid']."'";
             }

           $SEL = " Select * from tbl_servicejobline where invheadid='".$_REQUEST['txt_A_invheadid']."' and initemid='".$_REQUEST['txt_A_initemid']."'
                       and designation='".$_REQUEST['cmb_A_designation']."' and manpowercategory='".$_REQUEST['cmb_A_manpowercategory']."' and type='MANPOWER' and formtype='".$_REQUEST['txt_A_formtype']."' $addsql";
           $dis = mysql_query($SEL);
           if(mysql_num_rows($dis)>=1){
                  echo "Yes";
           }else{
                  echo "No";
           }
        }
       
       if($_REQUEST['TYPE']=='SERVICEMANPOWER'){
             $addsql  = "";
             if($_REQUEST['childid']!=""){
                 $addsql = " and id<>'".$_REQUEST['childid']."'";
             }
           //if($_REQUEST['cmb_A_buildingcode']!="" && $_REQUEST['cmb_A_assettype']!="" && $_REQUEST['cmb_A_assetdescription']!=""){
              $SEL = " Select * from tbl_manpowerforservice where assettype='".$_REQUEST['cmb_A_assettype']."' and buildingcode='".$_REQUEST['cmb_A_buildingcode']."'
                     and designation='".$_REQUEST['cmb_A_designation']."' and docid='".$_REQUEST['txt_A_docid']."' $addsql";
           //}
           $dis = mysql_query($SEL);
           if(mysql_num_rows($dis)>=1){
                  echo "Yes";
           }else{
                  echo "No";
           }
        }

        if($_REQUEST['TYPE']=='otmaterialdeliveryjob'){
             $addsql  = "";
             if($_REQUEST['childid']!=""){
                 $addsql = " and id<>'".$_REQUEST['childid']."'";
             }
           //if($_REQUEST['cmb_A_buildingcode']!="" && $_REQUEST['cmb_A_assettype']!="" && $_REQUEST['cmb_A_assetdescription']!=""){
              $SEL = " Select * from tbl_servicejob where category='".$_REQUEST['cmb_A_category']."' and service='".$_REQUEST['cmb_A_service']."'
                       and invheadid='".$_REQUEST['txt_A_invheadid']."' and otservicetype='MATERIALDELIVERYJOB' and formtype='".$_REQUEST['txt_A_formtype']."' $addsql";
           //}
           $dis = mysql_query($SEL);
           if(mysql_num_rows($dis)>=1){
                  echo "Yes";
           }else{
                  echo "No";
           }
        }
        if($_REQUEST['TYPE']=='otsubcontractjob'){
             $addsql  = "";
             if($_REQUEST['childid']!=""){
                 $addsql = " and id<>'".$_REQUEST['childid']."'";
             }
           //if($_REQUEST['cmb_A_buildingcode']!="" && $_REQUEST['cmb_A_assettype']!="" && $_REQUEST['cmb_A_assetdescription']!=""){
              $SEL = " Select * from tbl_servicejob where category='".$_REQUEST['cmb_A_category']."' and service='".$_REQUEST['cmb_A_service']."'
                       and invheadid='".$_REQUEST['txt_A_invheadid']."' and otservicetype='SUBCONTRACTOR JOB' and formtype='".$_REQUEST['txt_A_formtype']."' $addsql";
           //}
           $dis = mysql_query($SEL);
           if(mysql_num_rows($dis)>=1){
                  echo "Yes";
           }else{
                  echo "No";
           }
        }
        
       if($_REQUEST['TYPE']=='SERVICEPROPERTY'){
             $addsql  = "";
             if($_REQUEST['childid']!=""){
                 $addsql = " and id<>'".$_REQUEST['childid']."'";
             }
           //if($_REQUEST['cmb_A_buildingcode']!="" && $_REQUEST['cmb_A_assettype']!="" && $_REQUEST['cmb_A_assetdescription']!=""){
              $SEL = " Select * from tbl_clientserviceproperty where objectcode='".$_REQUEST['txt_A_objectcode']."' and buildingcode='".$_REQUEST['cmb_A_buildingcode']."'
                     and propertycode='".$_REQUEST['cmb_A_propertycode']."' and docid='".$_REQUEST['txt_A_docid']."' $addsql";
           //}
           $dis = mysql_query($SEL);
           if(mysql_num_rows($dis)>=1){
                  echo "Yes";
           }else{
                  echo "No";
           }
        }
       
        if($_REQUEST['TYPE']=='SERVICE'){
             $addsql  = "";
             if($_REQUEST['childid']!=""){
                 $addsql = " and id<>'".$_REQUEST['childid']."'";
             }
           //if($_REQUEST['cmb_A_buildingcode']!="" && $_REQUEST['cmb_A_assettype']!="" && $_REQUEST['cmb_A_assetdescription']!=""){
              $SEL = " Select * from in_crmline where invheadid='".$_REQUEST['txt_A_invheadid']."' and buildingcode='".$_REQUEST['cmb_A_buildingcode']."'
                     and articlecode='".$_REQUEST['cmb_A_articlecode']."' and category='".$_REQUEST['cmb_A_category']."' and formtype='".$_REQUEST['txt_A_formtype']."' $addsql";
           //}
           $dis = mysql_query($SEL);
           if(mysql_num_rows($dis)>=1){
                  echo "Yes";
           }else{
                  echo "No";
           }
        }
        
        if($_REQUEST['TYPE']=='SERVICEFROMCOSTING'){
             $addsql  = "";
             if($_REQUEST['mode']!=""){
                 $addsql = " and id<>'".$_REQUEST['mode']."'";
             }
           //if($_REQUEST['cmb_A_buildingcode']!="" && $_REQUEST['cmb_A_assettype']!="" && $_REQUEST['cmb_A_assetdescription']!=""){
              $SEL = " Select * from in_crmline where invheadid='".$_REQUEST['INITEMID']."' and buildingcode='".$_REQUEST['cmb_A_buildingcode']."'
                     and articlecode='".$_REQUEST['cmb_A_articlecode']."' and category='".$_REQUEST['cmb_A_category']."' and formtype='".$_REQUEST['txt_A_formtype']."' $addsql";
           //}
           $dis = mysql_query($SEL);
           if(mysql_num_rows($dis)>=1){
                  echo "Yes";
           }else{
                  echo "No";
           }
        }
        if($_REQUEST['TYPE']=='OTSERVICE'){
             $addsql  = "";
             if($_REQUEST['childid']!=""){
                 $addsql = " and id<>'".$_REQUEST['childid']."'";
             }
           //if($_REQUEST['cmb_A_buildingcode']!="" && $_REQUEST['cmb_A_assettype']!="" && $_REQUEST['cmb_A_assetdescription']!=""){
              $SEL = " Select * from in_crmline where invheadid='".$_REQUEST['txt_A_invheadid']."'
                     and articlecode='".$_REQUEST['cmb_A_articlecode']."' and servicejob='".$_REQUEST['cmb_A_servicejob']."' and formtype='".$_REQUEST['txt_A_formtype']."' $addsql";
           //}
           $dis = mysql_query($SEL);
           if(mysql_num_rows($dis)>=1){
                  echo "Yes";
           }else{
                  echo "No";
           }
        }
        
        if($_REQUEST['TYPE']=='SERVICEASSET'){
             $addsql  = "";
             if($_REQUEST['childid']!=""){
                 $addsql = " and id<>'".$_REQUEST['childid']."'";
             }
           //if($_REQUEST['cmb_A_buildingcode']!="" && $_REQUEST['cmb_A_assettype']!="" && $_REQUEST['cmb_A_assetdescription']!=""){
              $SEL = " Select * from tbl_serviceasset where docid='".$_REQUEST['txt_A_docid']."' and buildingcode='".$_REQUEST['cmb_A_buildingcode']."'
                     and assettype='".$_REQUEST['cmb_A_assettype']."' and assetdescription='".$_REQUEST['cmb_A_assetdescription']."' $addsql";
           //}
           $dis = mysql_query($SEL);
           if(mysql_num_rows($dis)>=1){
                  echo "Yes";
           }else{
                  echo "No";
           }
        }
*/
