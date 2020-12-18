<?php
include "connection.php";
class Providers extends MyPHPGrid
{
    var $editFields;
    var $editheaderfield;

    function  Providers(){
        return(true);
    }

    function GetHTMLEditForm($object,$id,$editheader){



            $strFieldsdList = "ID";
            $intEditFieldCount = count($object->editFields);
            for ($i = 0 ; $i < $intEditFieldCount ; $i++){                          // Preparing the field name list separated by commas
                 $strFieldsdList = $strFieldsdList . ", " . $object->editFields[$i] ;
            }
            $SQL = "SELECT " . $strFieldsdList . " FROM ". $object->TableName . " WHERE ID='$id'";    // Standard SQL
            //echo $SQL;
            $SQLResult = mysql_query($SQL) or die(mysql_error()."<br>".$loginSQL);
            $SQLResultArray    = mysql_num_rows($SQLResult);
            if($SQLResultArray==0){
               return "Error in Sql Edit Fields";
               exit();
            }

            $xx=0;
            while($loginResultArray = mysql_fetch_array($SQLResult)) {
                 // echo  $loginResultArray['companyname'];

                foreach($object->fields as $key => $attribute){
                      $ControlType  = strtoupper($object->fields[$key]["CONTROL"]);
                      $mycontrol = "";
                      $mandatory = "";
                      $javascript = "";

                      if ($ControlType == "TXT"){
                        if ($this->UTrim($object->fields[$key]["ALPNUM"]) == $this->UTrim("N")) $javascript = "onKeyPress='return AllowNumeric(event)'";
                        if ($this->UTrim($object->fields[$key]["ALPNUM"]) == $this->UTrim("A")) $javascript = "onKeyPress='return AllowAlpha(event)'";
                        if ($this->UTrim($object->fields[$key]["ALPNUM"]) == $this->UTrim("U")) $javascript = "onKeyPress='return AllowAlphaUpp(event)'";
                        if ($this->UTrim($object->fields[$key]["MANDATORY"]) == "1") $mandatory = "<font color='red'>*</font>&nbsp;";

                        $fieldvalue=$loginResultArray[$object->fields[$key]["CONTROLNAME"]];

                        $mycontrol = "<td  class='dvtCellLabel'>
                                     ".$mandatory.$object->fields[$key]["LABLE"]."
                                     </td>
                                     <td  class='dvtCellInfo'>

                                     <input id='txt_".$object->fields[$key]["ALPNUM"]."_".$object->fields[$key]["CONTROLNAME"]."' name='txt_".$object->fields[$key]["ALPNUM"]."_".$object->fields[$key]["CONTROLNAME"]."' type='text' $javascript
                                     maxlength='".$object->fields[$key]["MaxLength"]."' class='detailedViewTextBox'
                                     size='".$object->fields[$key]["CONTROLWIDTH"]."' value='".$fieldvalue."' />
                                     </td>";
                      }
                      if ($ControlType == "TXTA"){
                        if ($this->UTrim($object->fields[$key]["ALPNUM"]) == $this->UTrim("N")) $javascript = "onKeyPress='return AllowNumeric(event)'";
                        if ($this->UTrim($object->fields[$key]["ALPNUM"]) == $this->UTrim("A")) $javascript = "onKeyPress='return AllowAlpha(event)'";
                        if ($this->UTrim($object->fields[$key]["ALPNUM"]) == $this->UTrim("U")) $javascript = "onKeyPress='return AllowAlphaUpp(event)'";
                        if ($this->UTrim($object->fields[$key]["MANDATORY"]) == "1") $mandatory = "<font color='red'>*</font>&nbsp;";
                        if ($object->fields[$key]["VALUE"]!=""){
                            $fieldvalue=$object->fields[$key]["VALUE"];
                        }else{
                            $fieldvalue=$loginResultArray[$object->fields[$key]["CONTROLNAME"]];
                        }

                        $mycontrol = "<td class='dvtCellLabelhid'>
                                     ".$mandatory.$object->fields[$key]["LABLE"]."
                                     </td>
                                     <td  class='dvtCellInfohid'>

                                     <input id='txt_".$object->fields[$key]["ALPNUM"]."_".$object->fields[$key]["CONTROLNAME"]."' name='txt_".$object->fields[$key]["ALPNUM"]."_".$object->fields[$key]["CONTROLNAME"]."' type='text' $javascript
                                     maxlength=".$object->fields[$key]["MaxLength"]." class='detailedViewTextBoxSmall'
                                     size=".$object->fields[$key]["CONTROLWIDTH"]." value='".$fieldvalue."' />
                                     </td>";
                      }
                      if ($ControlType == "PWD"){
                        if ($this->UTrim($object->fields[$key]["MANDATORY"]) == "1") $mandatory = "<font color='red'>*</font>&nbsp;";
                        if ($object->fields[$key]["VALUE"]!=""){
                            $fieldvalue=$object->fields[$key]["VALUE"];
                        }else{
                            $fieldvalue=$loginResultArray[$object->fields[$key]["CONTROLNAME"]];
                        }

                        $mycontrol = "<td  class='dvtCellLabel'>
                                     ".$mandatory.$object->fields[$key]["LABLE"]."
                                     </td>
                                     <td   class='dvtCellInfo'>

                                     <input id='pwd_".$object->fields[$key]["ALPNUM"]."_".$object->fields[$key]["CONTROLNAME"]."' name='pwd_".$object->fields[$key]["ALPNUM"]."_".$object->fields[$key]["CONTROLNAME"]."' type='password' $javascript
                                     maxlength='".$object->fields[$key]["MaxLength"]."' class='detailedViewTextBox'
                                     size='".$object->fields[$key]["CONTROLWIDTH"]."' value='".$fieldvalue."' />
                                     </td>";
                      }
                      if ($ControlType == "HID"){
                        if ($object->fields[$key]["VALUE"]!=""){
                            $fieldvalue=$object->fields[$key]["VALUE"];
                        }else{
                            $fieldvalue=$loginResultArray[$object->fields[$key]["NAME"]];
                        }

                        $mycontrol = "<td  class='dvtCellLabel1'>
                                     </td>
                                     <td class='dvtCellInfo1'>
                                     <input id='hid_".$object->fields[$key]["ALPNUM"]."_".$object->fields[$key]["NAME"]."' name='hid_".$object->fields[$key]["ALPNUM"]."_".$object->fields[$key]["NAME"]."' type='hidden' $javascript
                                     maxlength='".$object->fields[$key]["MaxLength"]."' class='detailedViewTextBox'
                                     size='".$object->fields[$key]["CONTROLWIDTH"]."' value='".$fieldvalue."' />
                                     </td>";
                      }
                      if ($ControlType == "TXR"){

                        if ($this->UTrim($object->fields[$key]["ALPNUM"]) == $this->UTrim("N")) $javascript = "onKeyPress='return AllowNumeric(event)'";
                        if ($this->UTrim($object->fields[$key]["ALPNUM"]) == $this->UTrim("A")) $javascript = "onKeyPress='return AllowAlpha(event)'";
                        if ($this->UTrim($object->fields[$key]["ALPNUM"]) == $this->UTrim("U")) $javascript = "onKeyPress='return AllowAlphaUpp(event)'";
                        if ($this->UTrim($object->fields[$key]["MANDATORY"]) == "1") $mandatory = "<font color='red'>*</font>&nbsp;";
                        $fieldvalue=$loginResultArray[$object->fields[$key]["CONTROLNAME"]];

                        $mycontrol = "<td  class='dvtCellLabel'>
                                     ".$mandatory.$object->fields[$key]["LABLE"]."
                                     </td>
                                     <td  class='dvtCellInfo'>
                                     <input id='txr_".$object->fields[$key]["ALPNUM"]."_".$object->fields[$key]["CONTROLNAME"]."' name='txr_".$object->fields[$key]["ALPNUM"]."_".$object->fields[$key]["CONTROLNAME"]."' type='text'
                                     maxlength=".$object->fields[$key]["MaxLength"]." class='detailedViewTextBox' readonly='readonly'
                                     size=".$object->fields[$key]["CONTROLWIDTH"]." value='".$fieldvalue."' />
                                     </td>";
                      }
                      if ($ControlType == "TXRA"){

                        if ($this->UTrim($object->fields[$key]["ALPNUM"]) == $this->UTrim("N")) $javascript = "onKeyPress='return AllowNumeric(event)'";
                        if ($this->UTrim($object->fields[$key]["ALPNUM"]) == $this->UTrim("A")) $javascript = "onKeyPress='return AllowAlpha(event)'";
                        if ($this->UTrim($object->fields[$key]["ALPNUM"]) == $this->UTrim("U")) $javascript = "onKeyPress='return AllowAlphaUpp(event)'";
                        if ($this->UTrim($object->fields[$key]["MANDATORY"]) == "1") $mandatory = "<font color='red'>*</font>&nbsp;";
                        if ($object->fields[$key]["VALUE"]!=""){
                            $fieldvalue=$object->fields[$key]["VALUE"];
                        }else{
                            $fieldvalue=$loginResultArray[$object->fields[$key]["CONTROLNAME"]];
                        }

                        $mycontrol = "<td  class='dvtCellLabelhid'>
                                     ".$mandatory.$object->fields[$key]["LABLE"]."
                                     </td>
                                     <td  class='dvtCellInfohid'>

                                     <input id='txr_".$object->fields[$key]["ALPNUM"]."_".$object->fields[$key]["CONTROLNAME"]."' name='txr_".$object->fields[$key]["ALPNUM"]."_".$object->fields[$key]["CONTROLNAME"]."' type='text'
                                     maxlength=".$object->fields[$key]["MaxLength"]." class='detailedViewTextBoxSmall' readonly='readonly'
                                     size=".$object->fields[$key]["CONTROLWIDTH"]." value='".$fieldvalue."' />
                                     </td>";
                      }
                      if ($ControlType == "TXD"){
                        if ($object->fields[$key]["VALUE"]!=""){
                            $fieldvalue=$object->fields[$key]["VALUE"];
                        }else{
                            $fieldvalue=$loginResultArray[$object->fields[$key]["CONTROLNAME"]];
                        }
                        if($fieldvalue==""){
                        $fieldvalue =date('d-m-Y');
                        }else{
                        $Dvalue  = explode('-',$fieldvalue);
                        $fieldvalue   = $Dvalue[2].'-'.$Dvalue[1].'-'.$Dvalue[0];
                        }

                        if ($this->UTrim($object->fields[$key]["MANDATORY"]) == "1") $mandatory = "<font color='red'>*</font>&nbsp;";
                        $tempControlName = "txd_".$object->fields[$key]["ALPNUM"]."_".$object->fields[$key]["CONTROLNAME"]."";
                        $href = "'displayCalendar(&quot;$tempControlName&quot;,&quot;dd-mm-yyyy&quot;,this);'";
                        $dateImages="&nbsp;<input type='button' class=calenimg value='&nbsp;&nbsp;' onclick=$href>";
                        $mycontrol = "<td  class='dvtCellLabel'>
                                     ".$mandatory.$object->fields[$key]["LABLE"]."&nbsp;
                                     </td>
                                     <td   class='dvtCellInfo'>
                                     <input id='txd_".$object->fields[$key]["ALPNUM"]."_".$object->fields[$key]["CONTROLNAME"]."' name='txd_".$object->fields[$key]["ALPNUM"]."_".$object->fields[$key]["CONTROLNAME"]."' type='text'
                                     maxlength='".$object->fields[$key]["MaxLength"]."' class='detailedViewDateBox'
                                     size='".$object->fields[$key]["CONTROLWIDTH"]."' value='".$fieldvalue."'
                                     onKeyUp='dateck(this);' onBlur='check(this$DatePara);' onKeyPress='AllowNumericOnly1(this);'/>
                                     $dateImages
                                     </td>";
                      }

                      if ($ControlType == "TTT"){
                        if ($object->fields[$key]["VALUE"]!=""){
                            $fieldvalue=$object->fields[$key]["VALUE"];
                        }else{
                            $fieldvalue=$loginResultArray[$object->fields[$key]["CONTROLNAME"]];
                        }
                        if ($this->UTrim($object->fields[$key]["MANDATORY"]) == "1") $mandatory = "<font color='red'>*</font>&nbsp;";
                        $tempControlName = "tim_".$object->fields[$key]["ALPNUM"]."_".$object->fields[$key]["CONTROLNAME"]."";
                        //$href = "'displayCalendar(&quot;$tempControlName&quot;,&quot;dd-mm-yyyy&quot;,this);'";
                        //$dateImages="&nbsp;<input type='button' class=calenimg value='&nbsp;&nbsp;' onclick=$href>";
                        $mycontrol = "<td  class='dvtCellLabel'>
                                     ".$mandatory.$object->fields[$key]["LABLE"]."&nbsp;
                                     </td>
                                     <td  class='dvtCellInfo'>

                                     <input id='txt_".$object->fields[$key]["ALPNUM"]."_".$object->fields[$key]["CONTROLNAME"]."' name='txt_".$object->fields[$key]["ALPNUM"]."_".$object->fields[$key]["CONTROLNAME"]."' type='text'
                                     maxlength='".$object->fields[$key]["MaxLength"]."' class='detailedViewDateBox'
                                     size='".$object->fields[$key]["CONTROLWIDTH"]."' value='".$fieldvalue."'
                                     ONBLUR='validateDatePicker(this)'/>
                                     </td>";
                      }
                      if ($ControlType == "TXA"){
                        if ($this->UTrim($object->fields[$key]["MANDATORY"]) == "1") $mandatory = "<font color='red'>*</font>&nbsp;";
                        if ($object->fields[$key]["VALUE"]!=""){
                            $fieldvalue=$object->fields[$key]["VALUE"];
                        }else{
                            $fieldvalue=$loginResultArray[$object->fields[$key]["CONTROLNAME"]];
                        }

                        $mycontrol = "<td class='dvtCellLabel'>
                                     ".$mandatory.$object->fields[$key]["LABLE"]."
                                     </td>
                                     <td  class='dvtCellInfo'>
                                     <textarea  rows='1' cols='30'  id='txa_A_".$object->fields[$key]["CONTROLNAME"]."' name='txa_A_".$object->fields[$key]["CONTROLNAME"]."'
                                     maxlength=".$object->fields[$key]["MaxLength"]." class='detailedViewTextArea'
                                     size=".$object->fields[$key]["CONTROLWIDTH"]." />".str_replace("<br />","",$fieldvalue)."</textarea>
                                     </td>";
                      }
                      if ($ControlType == "TXAB"){
                        if ($this->UTrim($object->fields[$key]["MANDATORY"]) == "1") $mandatory = "<font color='red'>*</font>&nbsp;";
                        if ($object->fields[$key]["VALUE"]!=""){
                            $fieldvalue=$object->fields[$key]["VALUE"];
                        }else{
                            $fieldvalue=$loginResultArray[$object->fields[$key]["CONTROLNAME"]];
                        }

                        $mycontrol = "<td class='dvtCellLabel'>
                                     ".$mandatory.$object->fields[$key]["LABLE"]."
                                     </td>
                                     <td class='dvtCellInfo'>
                                     <textarea  rows='1' cols='30'  id='txa_A_".$object->fields[$key]["CONTROLNAME"]."' name='txa_A_".$object->fields[$key]["CONTROLNAME"]."'
                                     maxlength=".$object->fields[$key]["MaxLength"]." class='detailedViewTextAreaBig'
                                     size=".$object->fields[$key]["CONTROLWIDTH"]." />".str_replace("<br />","",$fieldvalue)."</textarea>
                                     </td>";
                      }
                      if ($ControlType == "CHK"){
                        $tempControlName = "chk_" . "A_" . $object->fields[$key]["CONTROLNAME"];
                        if ($this->UTrim($object->fields[$key]["MANDATORY"]) == "1") $mandatory = "<font color='red'>*</font>&nbsp;";
                        if ($object->fields[$key]["VALUE"]!=""){
                            $fieldvalue=$object->fields[$key]["VALUE"];
                        }else{
                            $fieldvalue=$loginResultArray[$object->fields[$key]["CONTROLNAME"]];
                        }

                        $mycontrol = "<td class='dvtCellLabel'>
                                     ".$mandatory.$object->fields[$key]["LABLE"]."
                                     </td>
                                     <td  class='dvtCellInfo'>";
                                      //delvelop data
                                      $mycontrol .= "<div style='border:solid 1px #c6c6c6;padding:4px;width:230px;height:".$object->fields[$key]["Height"]."px;overflow:auto;'>";
                                      $SQLsub1   = "select ".$object->fields[$key]["BaseFields"]." from ".$object->fields[$key]["BASETABLE"]." where ".$object->fields[$key]["WHERE"]."";
                                      $SQLResSub1 =  mysql_query($SQLsub1) or die(mysql_error()."<br>".$SQLsub1);

                                      if(mysql_num_rows($SQLResSub1)>=1){
                                        while($loginResultArraySub1   = mysql_fetch_array($SQLResSub1)){
                                           $selected = "";
                                           if (strpos("-," . $fieldvalue.",",",".$loginResultArraySub1[0].",")>0) {     //  Setting the selected item if this matches with the value parameter
                                               $selected = " checked ";
                                           }

                                           $mycontrol .= "<input type='checkbox' id='".$tempControlName."' name='".$tempControlName."' value='".$loginResultArraySub1[0]."'".$selected."/>&nbsp;" . htmlspecialchars(trim($loginResultArraySub1[1])) . "<br>\n";
                                           ++$xx;
                                        }
                                      }
                                   $mycontrol .= "</div>";
                       $mycontrol .=  "</td>";
                      }
                      if ($ControlType == "CKK"){
                        $tempControlName = "ckk_" . "A_" . $object->fields[$key]["CONTROLNAME"];
                        if ($this->UTrim($object->fields[$key]["MANDATORY"]) == "1") $mandatory = "<font color='red'>*</font>&nbsp;";
                        if ($object->fields[$key]["VALUE"]!=""){
                            $fieldvalue=$object->fields[$key]["VALUE"];
                        }else{
                            $fieldvalue=$loginResultArray[$object->fields[$key]["CONTROLNAME"]];
                        }

                        $mycontrol = "<td class='dvtCellLabel'>
                                     ".$mandatory.$object->fields[$key]["LABLE"]."
                                     </td>
                                     <td  class='dvtCellInfo'>";
                                      //delvelop data
                                      $mycontrol .= "<div style='border:solid 1px #c6c6c6;padding:4px;width:230px;height:".$object->fields[$key]["Height"]."px;overflow:auto;'>";
                                      $SQLsub1   = "select ".$object->fields[$key]["BaseFields"]." from ".$object->fields[$key]["BASETABLE"]." where ".$object->fields[$key]["WHERE"]."";
                                      $SQLResSub1 =  mysql_query($SQLsub1) or die(mysql_error()."<br>".$SQLsub1);

                                      if(mysql_num_rows($SQLResSub1)>=1){
                                        while($loginResultArraySub1   = mysql_fetch_array($SQLResSub1)){
                                           $selected = "";
                                           if (strpos("-," . $fieldvalue.",",",".$loginResultArraySub1[0].",")>0) {     //  Setting the selected item if this matches with the value parameter
                                               $selected = " checked ";
                                           }

                                           $mycontrol .= "<input type='checkbox' id='".$tempControlName."' name='".$tempControlName."' value='".$loginResultArraySub1[0]."'".$selected."/>&nbsp;" . htmlspecialchars(trim($loginResultArraySub1[1])) . "<br>\n";
                                           ++$xx;
                                        }
                                      }
                                   $mycontrol .= "</div>";
                       $mycontrol .=  "</td>";
                      }
                      if ($ControlType == "PPP"){
                        $tempControlName = "txt_".$object->fields[$key]["ALPNUM"]."_".$object->fields[$key]["CONTROLNAME"]."";
                        if ($this->UTrim($object->fields[$key]["ALPNUM"]) == $this->UTrim("N")) $javascript = "onKeyPress='return AllowNumeric(event)'";
                        if ($this->UTrim($object->fields[$key]["ALPNUM"]) == $this->UTrim("A")) $javascript = "onKeyPress='return AllowAlpha(event)'";
                        if ($this->UTrim($object->fields[$key]["ALPNUM"]) == $this->UTrim("U")) $javascript = "onKeyPress='return AllowAlphaUpp(event)'";
                        if ($this->UTrim($object->fields[$key]["MANDATORY"]) == "1") $mandatory = "<font color='red'>*</font>&nbsp;";
                        if ($object->fields[$key]["VALUE"]!=""){
                            $fieldvalue=$object->fields[$key]["VALUE"];
                        }else{
                            $fieldvalue=$loginResultArray[$object->fields[$key]["CONTROLNAME"]];
                        }
                        $img = "&nbsp;<a href=javascript:popupsel('".$object->fields[$key]["phpfile"].".php','".$tempControlName."','Show','".$this->selectedID."')><img src='images/getdata.gif' border=0 alt='Click to pick ". $this->fields[$attribute]["LABLE"]."(s)'></a>" ;
                        $bottomname="<input type=text class='detailedViewPppBoxBottom' size=37 readonly name ='D_".$tempControlName."' id='D_".$tempControlName."'>";
                        $mycontrol = "<td class='dvtCellLabel'>
                                     ".$mandatory.$object->fields[$key]["LABLE"]."
                                     </td>
                                     <td  class='dvtCellInfo'>
                                     <input class='detailedViewPppBox' value='".$fieldvalue."' id='txt_".$object->fields[$key]["ALPNUM"]."_".$object->fields[$key]["CONTROLNAME"]."' name='txt_".$object->fields[$key]["ALPNUM"]."_".$object->fields[$key]["CONTROLNAME"]."' type='text' $javascript
                                     onChange='javascript:dataIsDirty=1'   onblur = 'javascript:popupsel(\"".$object->fields[$key]["phpfile"].".php\",\"".$tempControlName."\",\"\")'
                                     maxlength=".$object->fields[$key]["MaxLength"]."
                                     size=".$object->fields[$key]["CONTROLWIDTH"]." value='".$object->fields[$key]["DATA"]."'/>&nbsp;$img
                                     <a href=javascript:clearparty();><img src='images/snew.gif' border=0></a>&nbsp;&nbsp;<Br>$bottomname
                                     </td>";

                      }
                      if ($ControlType == "CMB"){
                        if ($this->UTrim($object->fields[$key]["MANDATORY"]) == "1") $mandatory = "<font color='red'>*</font>&nbsp;";
                        if ($object->fields[$key]["VALUE"]!=""){
                            $fieldvalue=$object->fields[$key]["VALUE"];
                        }else{
                            $fieldvalue=$loginResultArray[$object->fields[$key]["CONTROLNAME"]];
                        }
                        $img1="";
                        if($object->fields[$key]["phpfile"]!=""){
                           $tempControlName = "cmb_A_".$object->fields[$key]["CONTROLNAME"]."";
                           $img1 = "&nbsp;<a href=javascript:popupsel('".$object->fields[$key]["phpfile"].".php','".$tempControlName."','Show','".$this->selectedID."')><img src='images/file.png' border=0 alt='Click to enter New record'></a>" ;
                        }
                        $addjavascript="";
                        if($object->fields[$key]["addjavascript"]!=""){
                           $addjavascript = "onchange='".$object->fields[$key]["addjavascript"]."'";
                        }
                        $mycontrol = "<td  class='dvtCellLabel'>
                                     ".$mandatory.$object->fields[$key]["LABLE"]."
                                     </td>
                                     <td   class='dvtCellInfo'>";
                                      //delvelop data
                                      if($object->fields[$key]["DISABLE"]=="YES"){ $visible = "disabled";}
                                      else{$visible ="";}
                                      $mycontrol .= "<select id='cmb_A_".$object->fields[$key]["CONTROLNAME"]."' name='cmb_A_".$object->fields[$key]["CONTROLNAME"]."' $addjavascript class='detailedViewComboBox' size='1' $visible>";
                                      if($object->fields[$key]["BLANK"]=="YES") $mycontrol .= "<option value='Select'>Select</option>";
                                      if($object->fields[$key]["BLANK2"]=="YES") $mycontrol .= "<option value='CP WAREHOUSE'>CP Warehouse</option>";
                                      $SQLsub   = "select ".$object->fields[$key]["BaseFields"]." from ".$object->fields[$key]["BASETABLE"]." where ".$object->fields[$key]["WHERE"]." order by ".$object->fields[$key]["BaseOrder"]."";
                                      $SQLResSub =  mysql_query($SQLsub) or die(mysql_error()."<br>".$SQLsub);
                                      if(mysql_num_rows($SQLResSub)>=1){
                                        while($loginResultArraySub   = mysql_fetch_array($SQLResSub)){
                                          if($loginResultArraySub[0]==$fieldvalue){
                                           $mycontrol .= "<option value='".$loginResultArraySub[0]."' selected='selected'>".$loginResultArraySub[1]."</option>";
                                          }else{
                                           $mycontrol .= "<option value='".$loginResultArraySub[0]."'>".$loginResultArraySub[1]."</option>";
                                          }
                                        }
                                      }
                                   $mycontrol .= "</select>";
                       $mycontrol .=  "&nbsp;".$img1."</td>";
                      }


                      $mycontrolarray[] .= $mycontrol;


                }
            }
                              if($object->ActionsToDo_0){
                                     $htmledit_1 .= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                                     $htmledit_1 .= $object->ActionsToDo_0;
                                     //$htmledit_1 .= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                                     $htmledit_1 .= $object->ActionsToDo_1;
                                     //$htmledit_1 .= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                                     $htmledit_1 .= $object->ActionsToDo_2;
                                     //$htmledit_1 .= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                                     $htmledit_1 .= $object->ActionsToDo_3;
                                     //$htmledit_1 .= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                               }
                           $editing="";
                           if($object->editRecord=="true"){
                             $editing="&nbsp;&nbsp;<a href ='javascript:editingrecord();'><img src='images/save.gif' title='Save New Record' alt='Save New Record' width=20px height=20px border=0></a>";
                           }
                           if($object->formName!="productissueindentlistinc.php"){
                              $htmledit_ .="<Br><form name='frmEdit' method='post' id='frmEdit'>
                              <input type='hidden' name='modeid' class=textboxcombo id='modeid' value='save'>
                              <input type='hidden' id=frmPage_startrow name=frmPage_startrow value='".$object->inpage."'>
                              <div class='divAppboxHeader'>".$editheader."
                              $editing
                              &nbsp;&nbsp;<a href ='javascript:cancleediting(\"".$object->formName."\");'><img src='ico/Button-Cancel-icon.png' title='Cancel New Record' alt='Cancel New Record' width=20px height=20px border=0></a>
                              $htmledit_1
                              <input type='hidden' id='mode' name='mode' value='".$id."'>
                              </div>
                              <div class='divAppbox'>";
                           }else{
                           $htmledit_ .="<Br><form name='frmEdit' method='post' id='frmEdit'>
                              <input type='hidden' id=frmPage_startrow name=frmPage_startrow value='".$object->inpage."'>
                              <div class='divAppbox'>";
                           }

                                $Htemp     = split(',',$object->TabsNames[0]);
                                for($i=0 ;$i < count($Htemp); $i++){
                                     $HtempCount     = split(':',$Htemp[$i]);
                                     if(strtoupper($HtempCount[0])!="TAXES"){
                                        $htmledit_ .= "<table><tr><td class='divAppboxsubheaderframe'>".$HtempCount[0]."</td></tr></table>";
                                     }else{
                                        $htmledit_ .= "<div style='DISPLAY: none' id='div_Taxes'>";
                                        $htmledit_ .= "<table><tr><td class='divAppboxsubheaderframe'>".$HtempCount[0]."</td></tr></table>";
                                     }

                                     $htmledit_ .= "<table>";
                                     if(strtoupper($HtempCount[0])!="TAXES"){
                                               $y=20;
                                               for($z=$HtempCount[1] ;$z <= $HtempCount[2]; $z=$z+3){
                                                 if($y==20 || $y==40 || $y==60 || $y==80 || $y==100)$htmledit_ .= "<tr>";
                                                 if($z<=$HtempCount[2]) $htmledit_ .= $mycontrolarray[$z];
                                                 if(($z+1)<=$HtempCount[2]) $htmledit_ .= $mycontrolarray[$z+1];
                                                 if(($z+2)<=$HtempCount[2]) $htmledit_ .= $mycontrolarray[$z+2];
                                                 $htmledit_ .= "</tr>";
                                                 $y=$y+20;
                                               }
                                           }else{
                                               $y=20;
                                               for($z=$HtempCount[1] ;$z <= $HtempCount[2]; $z=$z+8){
                                                 if($y==20 || $y==40 || $y==60 || $y==80)$htmledit_ .= "<tr>";
                                                 if($z<=$HtempCount[2]) $htmledit_ .= $mycontrolarray[$z];
                                                 if(($z+1)<=$HtempCount[2]) $htmledit_ .= $mycontrolarray[$z+1];
                                                 if(($z+2)<=$HtempCount[2]) $htmledit_ .= $mycontrolarray[$z+2];
                                                 if(($z+3)<=$HtempCount[2]) $htmledit_ .= $mycontrolarray[$z+3];
                                                 if(($z+4)<=$HtempCount[2]) $htmledit_ .= $mycontrolarray[$z+4];
                                                 if(($z+5)<=$HtempCount[2]) $htmledit_ .= $mycontrolarray[$z+5];
                                                 if(($z+6)<=$HtempCount[2]) $htmledit_ .= $mycontrolarray[$z+6];
                                                 if(($z+7)<=$HtempCount[2]) $htmledit_ .= $mycontrolarray[$z+7];

                                                 $htmledit_ .= "</tr>";
                                                 $y=$y+20;
                                               }
                                            }
                                     if(strtoupper($HtempCount[0])!="TAXES"){
                                        $htmledit_ .="</table>";
                                     }else{
                                        $htmledit_ .="</table>";
                                        $htmledit_ .= "</div>";
                                     }

                                }

                                if($object->formChildTable){
                                     $htmledit_ .= "<Br>";
                                     $htmledit_ .= $this->ChildRowRecords($object,$htmledit);
                                }
           $htmledit_ .="</div>
                         </form>";

           return $htmledit_;
    }

    function GetHTMLInsertForm($object,$editheader){

            $intEditFieldCount = count($object->editFields);
            for ($i = 0 ; $i < $intEditFieldCount ; $i++){                          // Preparing the field name list separated by commas
                 $strFieldsdList = $strFieldsdList . ", " . $object->editFields[$i] ;
            }
            $htmledit = $this->OpenHeaderData($editheader,$id,"true");
            foreach($object->fields as $key => $attribute){
                      $ControlType  = strtoupper($object->fields[$key]["CONTROL"]);
                      $mycontrol = "";
                      $mandatory = "";
                      $javascript = "";

                      if ($ControlType == "TXT"){
                        if ($this->UTrim($object->fields[$key]["ALPNUM"]) == $this->UTrim("N")) $javascript = "onKeyPress='return AllowNumeric(event)'";
                        if ($this->UTrim($object->fields[$key]["ALPNUM"]) == $this->UTrim("A")) $javascript = "onKeyPress='return AllowAlpha(event)'";
                        if ($this->UTrim($object->fields[$key]["ALPNUM"]) == $this->UTrim("U")) $javascript = "onKeyPress='return AllowAlphaUpp(event)'";
                        if ($this->UTrim($object->fields[$key]["MANDATORY"]) == "1") $mandatory = "<font color='red'>*</font>&nbsp;";
                        if ($object->fields[$key]["VALUE"]!=""){
                            $fieldvalue=$object->fields[$key]["VALUE"];
                        }else{
                            $fieldvalue=$loginResultArray[$object->fields[$key]["CONTROLNAME"]];
                        }

                        $mycontrol = "<td class='dvtCellLabel'>
                                     ".$mandatory.$object->fields[$key]["LABLE"]."
                                     </td>
                                     <td  class='dvtCellInfo'>
                                     <input id='txt_".$object->fields[$key]["ALPNUM"]."_".$object->fields[$key]["CONTROLNAME"]."' name='txt_".$object->fields[$key]["ALPNUM"]."_".$object->fields[$key]["CONTROLNAME"]."' type='text' $javascript
                                     maxlength=".$object->fields[$key]["MaxLength"]." class='detailedViewTextBox'
                                     size=".$object->fields[$key]["CONTROLWIDTH"]." value='".$object->fields[$key]["DATA"]."' />
                                     </td>";
                      }
                      if ($ControlType == "TXTA"){
                        if ($this->UTrim($object->fields[$key]["ALPNUM"]) == $this->UTrim("N")) $javascript = "onKeyPress='return AllowNumeric(event)'";
                        if ($this->UTrim($object->fields[$key]["ALPNUM"]) == $this->UTrim("A")) $javascript = "onKeyPress='return AllowAlpha(event)'";
                        if ($this->UTrim($object->fields[$key]["ALPNUM"]) == $this->UTrim("U")) $javascript = "onKeyPress='return AllowAlphaUpp(event)'";
                        if ($this->UTrim($object->fields[$key]["MANDATORY"]) == "1") $mandatory = "<font color='red'>*</font>&nbsp;";
                        if ($object->fields[$key]["VALUE"]!=""){
                            $fieldvalue=$object->fields[$key]["VALUE"];
                        }else{
                            $fieldvalue=$loginResultArray[$object->fields[$key]["CONTROLNAME"]];
                        }

                        $mycontrol = "<td class='dvtCellLabelhid'>
                                     ".$mandatory.$object->fields[$key]["LABLE"]."
                                     </td>
                                     <td  class='dvtCellInfohid'>

                                     <input id='txt_".$object->fields[$key]["ALPNUM"]."_".$object->fields[$key]["CONTROLNAME"]."' name='txt_".$object->fields[$key]["ALPNUM"]."_".$object->fields[$key]["CONTROLNAME"]."' type='text' $javascript
                                     maxlength=".$object->fields[$key]["MaxLength"]." class='detailedViewTextBoxSmall'
                                     size=".$object->fields[$key]["CONTROLWIDTH"]." value='".$object->fields[$key]["DATA"]."' />
                                     </td>";
                      }
                      if ($ControlType == "PWD"){
                        if ($this->UTrim($object->fields[$key]["MANDATORY"]) == "1") $mandatory = "<font color='red'>*</font>&nbsp;";
                        if ($object->fields[$key]["VALUE"]!=""){
                            $fieldvalue=$object->fields[$key]["VALUE"];
                        }else{
                            $fieldvalue=$loginResultArray[$object->fields[$key]["CONTROLNAME"]];
                        }

                        $mycontrol = "<td  class='dvtCellLabel'>
                                     ".$mandatory.$object->fields[$key]["LABLE"]."
                                     </td>
                                     <td  class='dvtCellInfo'>
                                     <input id='pwd_".$object->fields[$key]["ALPNUM"]."_".$object->fields[$key]["CONTROLNAME"]."' name='pwd_".$object->fields[$key]["ALPNUM"]."_".$object->fields[$key]["CONTROLNAME"]."' type='password' $javascript
                                     maxlength=".$object->fields[$key]["MaxLength"]." class='detailedViewTextBox'
                                     size=".$object->fields[$key]["CONTROLWIDTH"]." value='".$object->fields[$key]["DATA"]."' />
                                     </td>";
                      }
                      if ($ControlType == "HID"){
                        if ($object->fields[$key]["VALUE"]!=""){
                            $fieldvalue=$object->fields[$key]["VALUE"];
                        }else{
                            $fieldvalue=$loginResultArray[$object->fields[$key]["CONTROLNAME"]];
                        }

                        $mycontrol = "<td class='dvtCellLabel1'>
                                     </td>
                                     <td class='dvtCellInfo1'>
                                     <input id='hid_".$object->fields[$key]["ALPNUM"]."_".$object->fields[$key]["NAME"]."' name='hid_".$object->fields[$key]["ALPNUM"]."_".$object->fields[$key]["NAME"]."' type='hidden' $javascript
                                     maxlength=".$object->fields[$key]["MaxLength"]." class='detailedViewTextBox'
                                     size=".$object->fields[$key]["CONTROLWIDTH"]." value='".$object->fields[$key]["DATA"]."' />
                                     </td>";
                      }
                      if ($ControlType == "TXR"){

                        if ($this->UTrim($object->fields[$key]["ALPNUM"]) == $this->UTrim("N")) $javascript = "onKeyPress='return AllowNumeric(event)'";
                        if ($this->UTrim($object->fields[$key]["ALPNUM"]) == $this->UTrim("A")) $javascript = "onKeyPress='return AllowAlpha(event)'";
                        if ($this->UTrim($object->fields[$key]["ALPNUM"]) == $this->UTrim("U")) $javascript = "onKeyPress='return AllowAlphaUpp(event)'";
                        if ($this->UTrim($object->fields[$key]["MANDATORY"]) == "1") $mandatory = "<font color='red'>*</font>&nbsp;";
                        if ($object->fields[$key]["VALUE"]!=""){
                            $fieldvalue=$object->fields[$key]["VALUE"];
                        }else{
                            $fieldvalue=$loginResultArray[$object->fields[$key]["CONTROLNAME"]];
                        }

                        $mycontrol = "<td  class='dvtCellLabel'>
                                     ".$mandatory.$object->fields[$key]["LABLE"]."
                                     </td>
                                     <td  class='dvtCellInfo'>
                                     <input id='txr_".$object->fields[$key]["ALPNUM"]."_".$object->fields[$key]["CONTROLNAME"]."' name='txr_".$object->fields[$key]["ALPNUM"]."_".$object->fields[$key]["CONTROLNAME"]."' type='text'
                                     maxlength=".$object->fields[$key]["MaxLength"]." class='detailedViewTextBox' readonly='readonly'
                                     size=".$object->fields[$key]["CONTROLWIDTH"]." value='".$fieldvalue."' />
                                     </td>";
                      }
                      if ($ControlType == "TXRA"){

                        if ($this->UTrim($object->fields[$key]["ALPNUM"]) == $this->UTrim("N")) $javascript = "onKeyPress='return AllowNumeric(event)'";
                        if ($this->UTrim($object->fields[$key]["ALPNUM"]) == $this->UTrim("A")) $javascript = "onKeyPress='return AllowAlpha(event)'";
                        if ($this->UTrim($object->fields[$key]["ALPNUM"]) == $this->UTrim("U")) $javascript = "onKeyPress='return AllowAlphaUpp(event)'";
                        if ($this->UTrim($object->fields[$key]["MANDATORY"]) == "1") $mandatory = "<font color='red'>*</font>&nbsp;";
                        if ($object->fields[$key]["VALUE"]!=""){
                            $fieldvalue=$object->fields[$key]["VALUE"];
                        }else{
                            $fieldvalue=$loginResultArray[$object->fields[$key]["CONTROLNAME"]];
                        }

                        $mycontrol = "<td  class='dvtCellLabelhid'>
                                     ".$mandatory.$object->fields[$key]["LABLE"]."
                                     </td>
                                     <td  class='dvtCellInfohid'>

                                     <input id='txr_".$object->fields[$key]["ALPNUM"]."_".$object->fields[$key]["CONTROLNAME"]."' name='txr_".$object->fields[$key]["ALPNUM"]."_".$object->fields[$key]["CONTROLNAME"]."' type='text'
                                     maxlength=".$object->fields[$key]["MaxLength"]." class='detailedViewTextBoxSmall' readonly='readonly'
                                     size=".$object->fields[$key]["CONTROLWIDTH"]." value='".$fieldvalue."' />
                                     </td>";
                      }
                      if ($ControlType == "TXD"){
                        $fieldvalue=date('d-m-Y');
                        if ($this->UTrim($object->fields[$key]["MANDATORY"]) == "1") $mandatory = "<font color='red'>*</font>&nbsp;";
                        $tempControlName = "txd_".$object->fields[$key]["ALPNUM"]."_".$object->fields[$key]["CONTROLNAME"]."";
                        $href = "'displayCalendar(&quot;$tempControlName&quot;,&quot;dd-mm-yyyy&quot;,this);'";
                        $dateImages="&nbsp;<input type='button' class=calenimg value='&nbsp;&nbsp;' onclick=$href>";
                        $mycontrol = "<td  class='dvtCellLabel'>
                                     ".$mandatory.$object->fields[$key]["LABLE"]."&nbsp;
                                     </td>
                                     <td  class='dvtCellInfo'>
                                     <input id='txd_".$object->fields[$key]["ALPNUM"]."_".$object->fields[$key]["CONTROLNAME"]."' name='txd_".$object->fields[$key]["ALPNUM"]."_".$object->fields[$key]["CONTROLNAME"]."' type='text'
                                     maxlength='".$object->fields[$key]["MaxLength"]."' class='detailedViewDateBox'
                                     size='".$object->fields[$key]["CONTROLWIDTH"]."' value='".$fieldvalue."'
                                     onKeyUp='dateck(this);' onBlur='check(this$DatePara);' onKeyPress='AllowNumericOnly1(this);'/>
                                     $dateImages
                                     </td>";
                      }
                      if ($ControlType == "TTT"){
                        $fieldvalue="";
                        if ($this->UTrim($object->fields[$key]["MANDATORY"]) == "1") $mandatory = "<font color='red'>*</font>&nbsp;";
                        $tempControlName = "tim_".$object->fields[$key]["ALPNUM"]."_".$object->fields[$key]["CONTROLNAME"]."";
                        //$href = "'displayCalendar(&quot;$tempControlName&quot;,&quot;dd-mm-yyyy&quot;,this);'";
                        //$dateImages="&nbsp;<input type='button' class=calenimg value='&nbsp;&nbsp;' onclick=$href>";
                        $mycontrol = "<td class='dvtCellLabel'>
                                     ".$mandatory.$object->fields[$key]["LABLE"]."&nbsp;
                                     </td>
                                     <td  class='dvtCellInfo'>
                                     <input id='txt_".$object->fields[$key]["ALPNUM"]."_".$object->fields[$key]["CONTROLNAME"]."' name='txt_".$object->fields[$key]["ALPNUM"]."_".$object->fields[$key]["CONTROLNAME"]."' type='text'
                                     maxlength='".$object->fields[$key]["MaxLength"]."' class='detailedViewDateBox'
                                     size='".$object->fields[$key]["CONTROLWIDTH"]."' value='".$fieldvalue."'
                                     ONBLUR='validateDatePicker(this)'/>
                                     </td>";
                      }
                      if ($ControlType == "TXA"){
                        if ($this->UTrim($object->fields[$key]["MANDATORY"]) == "1") $mandatory = "<font color='red'>*</font>&nbsp;";
                        if ($object->fields[$key]["VALUE"]!=""){
                            $fieldvalue=$object->fields[$key]["VALUE"];
                        }else{
                            $fieldvalue=$loginResultArray[$object->fields[$key]["CONTROLNAME"]];
                        }

                        $mycontrol = "<td class='dvtCellLabel'>
                                     ".$mandatory.$object->fields[$key]["LABLE"]."
                                     </td>
                                     <td  class='dvtCellInfo'>
                                     <textarea  rows='1' cols='30'  id='txa_A_".$object->fields[$key]["CONTROLNAME"]."' name='txa_A_".$object->fields[$key]["CONTROLNAME"]."'
                                     maxlength=".$object->fields[$key]["MaxLength"]." class='detailedViewTextArea'
                                     size=".$object->fields[$key]["CONTROLWIDTH"]." />".$fieldvalue."</textarea>
                                     </td>";
                      }
                      if ($ControlType == "TXAB"){
                        if ($this->UTrim($object->fields[$key]["MANDATORY"]) == "1") $mandatory = "<font color='red'>*</font>&nbsp;";
                        if ($object->fields[$key]["VALUE"]!=""){
                            $fieldvalue=$object->fields[$key]["VALUE"];
                        }else{
                            $fieldvalue=$loginResultArray[$object->fields[$key]["CONTROLNAME"]];
                        }

                        $mycontrol = "<td class='dvtCellLabel'>
                                     ".$mandatory.$object->fields[$key]["LABLE"]."
                                     </td>
                                     <td class='dvtCellInfo'>
                                     <textarea  rows='1' cols='30'  id='txa_A_".$object->fields[$key]["CONTROLNAME"]."' name='txa_A_".$object->fields[$key]["CONTROLNAME"]."'
                                     maxlength=".$object->fields[$key]["MaxLength"]." class='detailedViewTextAreaBig'
                                     size=".$object->fields[$key]["CONTROLWIDTH"]." />".$fieldvalue."</textarea>
                                     </td>";
                      }
                      if ($ControlType == "CHK"){
                        $tempControlName = "chk_" . "A_" . $object->fields[$key]["CONTROLNAME"];
                        if ($this->UTrim($object->fields[$key]["MANDATORY"]) == "1") $mandatory = "<font color='red'>*</font>&nbsp;";
                        if ($object->fields[$key]["VALUE"]!=""){
                            $fieldvalue=$object->fields[$key]["VALUE"];
                        }else{
                            $fieldvalue=$loginResultArray[$object->fields[$key]["CONTROLNAME"]];
                        }

                        $mycontrol = "<td class='dvtCellLabel'>
                                     ".$mandatory.$object->fields[$key]["LABLE"]."
                                     </td>
                                     <td  class='dvtCellInfo'>";
                                      //delvelop data
                                      $mycontrol .= "<div style='border:solid 1px #c6c6c6;padding:4px;width:230px;height:".$object->fields[$key]["Height"]."px;overflow:auto;'>";
                                      $SQLsub1   = "select ".$object->fields[$key]["BaseFields"]." from ".$object->fields[$key]["BASETABLE"]." where ".$object->fields[$key]["WHERE"]."";
                                      $SQLResSub1 =  mysql_query($SQLsub1) or die(mysql_error()."<br>".$SQLsub1);

                                      if(mysql_num_rows($SQLResSub1)>=1){
                                        while($loginResultArraySub1   = mysql_fetch_array($SQLResSub1)){
                                           $mycontrol .= "<input type='checkbox'   value='".$loginResultArraySub1[0] . "' id='".$tempControlName . "' name ='".$tempControlName."'". $selected . ">&nbsp;" . htmlspecialchars(trim($loginResultArraySub1[1])) . "</option><br>\n";
                                           ++$xx;
                                        }
                                      }
                                   $mycontrol .= "</div>";
                       $mycontrol .=  "</td>";
                      }
                      if ($ControlType == "CKK"){
                        $tempControlName = "ckk_" . "A_" . $object->fields[$key]["CONTROLNAME"];
                        if ($this->UTrim($object->fields[$key]["MANDATORY"]) == "1") $mandatory = "<font color='red'>*</font>&nbsp;";
                        if ($object->fields[$key]["VALUE"]!=""){
                            $fieldvalue=$object->fields[$key]["VALUE"];
                        }else{
                            $fieldvalue=$loginResultArray[$object->fields[$key]["CONTROLNAME"]];
                        }

                        $mycontrol = "<td class='dvtCellLabel'>
                                     ".$mandatory.$object->fields[$key]["LABLE"]."
                                     </td>
                                     <td  class='dvtCellInfo'>";
                                      //delvelop data
                                      $mycontrol .= "<div style='border:solid 1px #c6c6c6;padding:4px;width:230px;height:".$object->fields[$key]["Height"]."px;overflow:auto;'>";
                                      $SQLsub1   = "select ".$object->fields[$key]["BaseFields"]." from ".$object->fields[$key]["BASETABLE"]." where ".$object->fields[$key]["WHERE"]."";
                                      $SQLResSub1 =  mysql_query($SQLsub1) or die(mysql_error()."<br>".$SQLsub1);

                                      if(mysql_num_rows($SQLResSub1)>=1){
                                        while($loginResultArraySub1   = mysql_fetch_array($SQLResSub1)){
                                           $mycontrol .= "<input type='checkbox'   value='".$loginResultArraySub1[0] . "' id='".$tempControlName . "' name ='".$tempControlName."'". $selected . ">&nbsp;" . htmlspecialchars(trim($loginResultArraySub1[1])) . "</option><br>\n";
                                           ++$xx;
                                        }
                                      }
                                   $mycontrol .= "</div>";
                       $mycontrol .=  "</td>";
                      }
                      if ($ControlType == "PPP"){
                        $tempControlName = "txt_".$object->fields[$key]["ALPNUM"]."_".$object->fields[$key]["CONTROLNAME"]."";
                        if ($this->UTrim($object->fields[$key]["ALPNUM"]) == $this->UTrim("N")) $javascript = "onKeyPress='return AllowNumeric(event)'";
                        if ($this->UTrim($object->fields[$key]["ALPNUM"]) == $this->UTrim("A")) $javascript = "onKeyPress='return AllowAlpha(event)'";
                        if ($this->UTrim($object->fields[$key]["ALPNUM"]) == $this->UTrim("U")) $javascript = "onKeyPress='return AllowAlphaUpp(event)'";
                        if ($this->UTrim($object->fields[$key]["MANDATORY"]) == "1") $mandatory = "<font color='red'>*</font>&nbsp;";
                        if ($object->fields[$key]["VALUE"]!=""){
                            $fieldvalue=$object->fields[$key]["VALUE"];
                        }else{
                            $fieldvalue=$loginResultArray[$object->fields[$key]["CONTROLNAME"]];
                        }
                        $img = "&nbsp;<a href=javascript:popupsel('".$object->fields[$key]["phpfile"].".php','".$tempControlName."','Show','".$this->selectedID."')><img src='images/getdata.gif' border=0 alt='Click to pick ". $this->fields[$attribute]["LABLE"]."(s)'></a>" ;
                        $bottomname="<input type=text class='detailedViewPppBoxBottom' size=37 readonly name='D_".$tempControlName."' id='D_".$tempControlName."'>";
                        $mycontrol = "<td class='dvtCellLabel'>
                                     ".$mandatory.$object->fields[$key]["LABLE"]."
                                     </td>
                                     <td  class='dvtCellInfo'>
                                     <input class='detailedViewPppBox' id='txt_".$object->fields[$key]["ALPNUM"]."_".$object->fields[$key]["CONTROLNAME"]."' name='txt_".$object->fields[$key]["ALPNUM"]."_".$object->fields[$key]["CONTROLNAME"]."' type='text' $javascript
                                     onChange='javascript:dataIsDirty=1'   onblur = 'javascript:popupsel(\"".$object->fields[$key]["phpfile"].".php\",\"".$tempControlName."\",\"\")'
                                     maxlength=".$object->fields[$key]["MaxLength"]."
                                     size=".$object->fields[$key]["CONTROLWIDTH"]." value='".$object->fields[$key]["DATA"]."'/>&nbsp;$img
                                     <a href=javascript:clearparty();><img src='images/snew.gif' border=0></a>&nbsp;&nbsp;<Br>$bottomname
                                     </td>";

                      }
                      if ($ControlType == "CMB"){
                        if ($this->UTrim($object->fields[$key]["MANDATORY"]) == "1") $mandatory = "<font color='red'>*</font>&nbsp;";
                        if ($object->fields[$key]["VALUE"]!=""){
                            $fieldvalue=$object->fields[$key]["VALUE"];
                        }else{
                            $fieldvalue=$loginResultArray[$object->fields[$key]["CONTROLNAME"]];
                        }
                        $img1="";

                        if($object->fields[$key]["phpfile"]!=""){
                           $tempControlName = "cmb_A_".$object->fields[$key]["CONTROLNAME"]."";
                           $img1 = "&nbsp;<a href=javascript:popupsel('".$object->fields[$key]["phpfile"].".php','".$tempControlName."','Show','".$this->selectedID."')><img src='images/file.png' border=0 alt='Click to enter New record'></a>" ;
                        }
                        $addjavascript="";
                        if($object->fields[$key]["addjavascript"]!=""){
                           $addjavascript = "onchange='".$object->fields[$key]["addjavascript"]."'";
                        }
                        $mycontrol = "<td  class='dvtCellLabel'>
                                     ".$mandatory.$object->fields[$key]["LABLE"]."
                                     </td>
                                     <td  class='dvtCellInfo'>";
                                      //delvelop data
                                      if($object->fields[$key]["DISABLE"]=="YES"){ $visible = "disabled";}
                                      else{$visible ="";}
                                      $mycontrol .= "<select id='cmb_A_".$object->fields[$key]["CONTROLNAME"]."' name='cmb_A_".$object->fields[$key]["CONTROLNAME"]."' $addjavascript class='detailedViewComboBox' size='1' $visible>";
                                      if($object->fields[$key]["BLANK"]=="YES") $mycontrol .= "<option value='Select'>Select</option>";
                                      if($object->fields[$key]["BLANK2"]=="YES") $mycontrol .= "<option value='CP WAREHOUSE'>CP Warehouse</option>";
                                      $SQLsub   = "select ".$object->fields[$key]["BaseFields"]." from ".$object->fields[$key]["BASETABLE"]." where ".$object->fields[$key]["WHERE"]." order by ".$object->fields[$key]["BaseOrder"]."";
                                      $SQLResSub =  mysql_query($SQLsub) or die(mysql_error()."<br>".$SQLsub);
                                      if(mysql_num_rows($SQLResSub)>=1){
                                        while($loginResultArraySub   = mysql_fetch_array($SQLResSub)){
                                          if($loginResultArraySub[0]==$fieldvalue){
                                           $mycontrol .= "<option value='".$loginResultArraySub[0]."' selected='selected'>".$loginResultArraySub[1]."</option>";
                                          }else{
                                           $mycontrol .= "<option value='".$loginResultArraySub[0]."'>".$loginResultArraySub[1]."</option>";
                                          }
                                        }
                                      }
                                   $mycontrol .= "</select>";
                       $mycontrol .=  "&nbsp;".$img1."</td>";
                      }



                      $mycontrolarray[] .= $mycontrol;


                }

            //print_r($mycontrolarray[1]);



            $htmledit_ .="<Br><form name='frmEdit' method='post' id='frmEdit'>
                         <input type='hidden' name='modeid' class=textboxcombo id='modeid' value='save'>
                         <div class='divAppboxHeader'>".$editheader."
                         &nbsp;&nbsp;<a href ='javascript:editingrecord();'><img src='images/save.gif' title='Save New Record' alt='Save New Record' width=20px height=20px border=0></a>
                         &nbsp;&nbsp;<a href ='javascript:cancleediting(\"".$object->formName."\");'><img src='ico/Button-Cancel-icon.png' title='Cancel New Record' alt='Cancel New Record' width=20px height=20px border=0></a>
                         <input type='hidden' id='mode' name='mode' value='".$id."'>
                         </div>
                         <div class='divAppbox'>";
                                $Htemp     = split(',',$object->TabsNames[0]);
                                for($i=0 ;$i < count($Htemp); $i++){
                                     $HtempCount     = split(':',$Htemp[$i]);
                                     if(strtoupper($HtempCount[0])!="TAXES"){
                                        $htmledit_ .= "<table><tr><td class='divAppboxsubheaderframe'>".$HtempCount[0]."</td></tr></table>";
                                     }else{
                                        $htmledit_ .= "<div style='DISPLAY: none' id='div_Taxes'>";
                                        $htmledit_ .= "<table><tr><td class='divAppboxsubheaderframe'>".$HtempCount[0]."</td></tr></table>";
                                     }

                                     $htmledit_ .= "<table>";
                                     if(strtoupper($HtempCount[0])!="TAXES"){
                                               $y=20;
                                               for($z=$HtempCount[1] ;$z <= $HtempCount[2]; $z=$z+3){
                                                 if($y==20 || $y==40 || $y==60 || $y==80 || $y==100)$htmledit_ .= "<tr>";
                                                 if($z<=$HtempCount[2]) $htmledit_ .= $mycontrolarray[$z];
                                                 if(($z+1)<=$HtempCount[2]) $htmledit_ .= $mycontrolarray[$z+1];
                                                 if(($z+2)<=$HtempCount[2]) $htmledit_ .= $mycontrolarray[$z+2];
                                                 $htmledit_ .= "</tr>";
                                                 $y=$y+20;
                                               }
                                           }else{
                                               $y=20;
                                               for($z=$HtempCount[1] ;$z <= $HtempCount[2]; $z=$z+8){
                                                 if($y==20 || $y==40 || $y==60 || $y==80)$htmledit_ .= "<tr>";
                                                 if($z<=$HtempCount[2]) $htmledit_ .= $mycontrolarray[$z];
                                                 if(($z+1)<=$HtempCount[2]) $htmledit_ .= $mycontrolarray[$z+1];
                                                 if(($z+2)<=$HtempCount[2]) $htmledit_ .= $mycontrolarray[$z+2];
                                                 if(($z+3)<=$HtempCount[2]) $htmledit_ .= $mycontrolarray[$z+3];
                                                 if(($z+4)<=$HtempCount[2]) $htmledit_ .= $mycontrolarray[$z+4];
                                                 if(($z+5)<=$HtempCount[2]) $htmledit_ .= $mycontrolarray[$z+5];
                                                 if(($z+6)<=$HtempCount[2]) $htmledit_ .= $mycontrolarray[$z+6];
                                                 if(($z+7)<=$HtempCount[2]) $htmledit_ .= $mycontrolarray[$z+7];

                                                 $htmledit_ .= "</tr>";
                                                 $y=$y+20;
                                               }
                                            }
                                     if(strtoupper($HtempCount[0])!="TAXES"){
                                        $htmledit_ .="</table>";
                                     }else{
                                        $htmledit_ .="</table>";
                                        $htmledit_ .= "</div>";
                                     }

                                }
                                if($object->formChildTable){
                                     $htmledit_ .= "<Br>";
                                     $htmledit_ .= $this->ChildRowRecords($object,$htmledit);
                                }
           $htmledit_ .="</div>
                         </form>";

           return $htmledit_;
    }
    function GetHTMLInsertForm_($object){

            echo $object->entrydata;
            

    }
    function GetHTMLEditForm_($object,$editheader){
             echo $object->entrydata;
             echo $object->entrychilddata;
             echo $object->entrychilddatagrid;

    }
    Function UTrim($str1){
         return trim(strtoupper($str1));
    }

    Function OpenHeaderData($objectname,$id,$editrecord){
    $editbutton="";

    if($editrecord=="true"){
       $editbutton = "<input class='crmbutton small save' title='Save [F2]' value='  Save [F2]  ' onclick='javascript:editingrecord();' type='button' name='update' />";
    }
    $htmledit = "<form name='frmEdit' method='post' id='frmEdit'>
                 <table cellSpacing='0' cellPadding='0' width='95%' align='center' border='0'>
                                        <tr>
                                                <td>
                                                <table class='small' cellSpacing='0' cellPadding='3' width='100%' border='0'>
                                                        <tr>
                                                                <td class='dvtTabCache' style='WIDTH: 7px' noWrap>
                                                                &nbsp;</td>
                                                                <td class='dvtSelectedCell' noWrap align='middle'>
                                                                ".$objectname."</td>
                                                                <td class='dvtTabCache' style='WIDTH: 10px'>
                                                                <input type='hidden' id='mode' name='mode' value='".$id."'>
                                                                 ".$editbutton."
                                                                </td>
                                                                <td class='dvtTabCache' style='WIDTH: 30%'>
                                                                <input class='crmbutton small cancel' title='Cancel [F3]' value='  Cancel [F3]  ' type='button' onclick='javascript:cancleediting();' name='cancel' />
                                                                </td>
                                                                <td class='dvtTabCache' style='WIDTH: 100%;FONT-WEIGHT: bolder;COLOR: #990000;' align=right>
                                                                &nbsp;
                                                                </td>
                                                        </tr>
                                                </table>
                                                </td>
                                        </tr>
                                        <tr>
                                                <td vAlign='top' align='left'>
                                                <table class='dvtContentSpace' cellSpacing='0' cellPadding='3' width='100%' border='0'>
                                                        <tr>
                                                                <td style='PADDING-RIGHT: 10px; PADDING-LEFT: 10px; PADDING-BOTTOM: 10px; PADDING-TOP: 10px; BORDER-RIGHT-WIDTH: 1px; BORDER-RIGHT-COLOR: #cccccc' align='left' width='80%'>
                                                                 ";

    return $htmledit;
    }

    Function TabHeaders($tabname,$from,$to,$mycontrolarray){
          //print_r($tabname."<Br>");
          $htmledit = "<div style='display:inline' id='div_$tabname'>
                       <table class='small' cellSpacing='0' cellPadding='0' width='100%' border='0'>
                      <tr>
                      <td class='detailedViewHeader' colSpan='4'>
                      <b>".$tabname."</b>
                      </td>
                      </tr><br>";
                       for($i=$from ;$i <= $to; $i=$i+2){

                         if($i!=$to){
                           //echo $i."<Br>";
                           $ii=$i+1;
                           //echo $ii."<Br>";
                           $htmledit .= "<tr style='HEIGHT: 20px'>";
                           $htmledit .= $mycontrolarray[$i];
                           $htmledit .= $mycontrolarray[$ii];
                           $htmledit .="</tr>";

                         }else{
                           //echo $i."<Br>";
                           $htmledit .= "<tr style='HEIGHT: 20px'>";
                           $htmledit .= $mycontrolarray[$i];
                           $htmledit .="</tr>";

                         }

                         //$htmledit .= "<tr style='HEIGHT: 20px'>";
                         //$htmledit .= $mycontrolarray[$i];
                         //$htmledit .= $mycontrolarray[$i+1];
                         //$htmledit .="</tr>";

                       }
                      $htmledit .="</table></div>";
                     // exit;
         return $htmledit;
    }



    Function CloseHeaderData(){
    $htmledit = "</td></tr></table></td></tr></table></form>";
    return $htmledit;
    }


    function ChildRowRecords($object,$htmledit){

      if($object->formChildRowsType=="rowsnotax"){
      $htmledit =  "
                           <iframe id='subid' name='subid' width='100%' height=700px src='rowsnotax.php' FRAMEBORDER=No FRAMESPACING=0 BORDER=0 scrolling=no>

                           </iframe>";
      }else if($object->formChildRowsType=="rowsforissues"){
       $htmledit =  "
                           <iframe id='subid' name='subid' width='100%' height=700px src='rowsforissues.php' FRAMEBORDER=No FRAMESPACING=0 BORDER=0 scrolling=no>

                           </iframe>";
      }else if($object->formChildRowsType=="rowsforissuesconvertinc"){
       $htmledit =  "
                           <iframe id='subid' name='subid' width='100%' height=700px src='rowsforissuesconvertinc.php' FRAMEBORDER=No FRAMESPACING=0 BORDER=0 scrolling=no>

                           </iframe>";
      }else if($object->formChildRowsType=="rowsforissuesconvert"){
       $htmledit =  "
                           <iframe id='subid' name='subid' width='100%' height=800px src='rowsforissuesconvert.php' FRAMEBORDER=No FRAMESPACING=0 BORDER=0 scrolling=no>

                           </iframe>";
      }else if($object->formChildRowsType=="rowsforatrissuesandpurchaseind"){
       $htmledit =  "
                           <iframe id='subid' name='subid' width='100%' height=700px src='rowsforatrissuesandpurchaseind.php' FRAMEBORDER=No FRAMESPACING=0 BORDER=0 scrolling=no>

                           </iframe>";
      }else if($object->formChildRowsType=="porows"){
       $htmledit =  "
                           <iframe id='subid' name='subid' width='100%' height=700px src='porows.php' FRAMEBORDER=No FRAMESPACING=0 BORDER=0 scrolling=no>

                           </iframe>";
      }else if($object->formChildRowsType=="rowsforstocktransferorderrequest"){
       $htmledit =  "
                           <iframe id='subid' name='subid' width='100%' height=700px src='rowsforstocktransferorderrequest.php' FRAMEBORDER=No FRAMESPACING=0 BORDER=0 scrolling=no>

                           </iframe>";
      }else if($object->formChildRowsType=="rowsforstocktransferorderreceipt"){
       $htmledit =  "
                           <iframe id='subid' name='subid' width='100%' height=700px src='rowsforstocktransferorderreceipt.php' FRAMEBORDER=No FRAMESPACING=0 BORDER=0 scrolling=no>

                           </iframe>";
      }else if($object->formChildRowsType=="rowsforgeneraltransfer"){
       $htmledit =  "
                           <iframe id='subid' name='subid' width='100%' height=700px src='rowsforgeneraltransfer.php' FRAMEBORDER=No FRAMESPACING=0 BORDER=0 scrolling=no>

                           </iframe>";
      }else if($object->formChildRowsType=="grnrows"){
       $htmledit =  "
                           <iframe id='subid' name='subid' width='100%' height=700px src='grnrows.php' FRAMEBORDER=No FRAMESPACING=0 BORDER=0 scrolling=no>

                           </iframe>";
      }else if($object->formChildRowsType=="invoicerows"){
       $htmledit =  "
                           <iframe id='subid' name='subid' width='100%' height=700px src='invoicerows.php' FRAMEBORDER=No FRAMESPACING=0 BORDER=0 scrolling=no>

                           </iframe>";
      }else{
       $htmledit =  "<table class='small' border='0' cellSpacing='0' cellPadding='0' width='100%'>
                     <tr>
                         <td colSpan='4'>
                           <iframe id='subid' name='subid' width='100%' height=450px src='rows.php' FRAMEBORDER=No FRAMESPACING=0 BORDER=0 scrolling=no>

                           </iframe>";

      }
      return $htmledit;
    }

}
?>

