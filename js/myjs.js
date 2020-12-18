function closePage(){
   document.frmPage.action='blank.php';
   document.frmPage.submit();
}
function refreshPaging2(formObj){
        document.frmPage.txtsearch.value = "";
        document.frmPage.frmPage_fid.value = "";
        document.frmPage.frmPage_fvalue.value = "";
        document.frmPage.frmPage_startrow.value = 0;
        document.frmPage.frmPage_startpage.value = 1;
        document.frmPage.frmPage_currentpage.value = 1;
        document.frmPage.frmPage_endpage.value = 10;
        document.frmPage.frmPage_rowcount.value = 10;

        var cmb_lookuplist=document.getElementById('cmb_lookuplist');
        if(cmb_lookuplist){
          cmb_lookuplist.selectedIndex='Select';
        }

        document.frmPage.action=formObj+'&ID='+'';
        document.frmPage.submit();
}
function refreshPagingwithDashboard(formObj){
       
		document.frmPage.frmPage_fid.value = "";
			
        document.frmPage.txtsearch.value = "";
        document.frmPage.frmPage_fvalue.value = "";
        document.frmPage.frmPage_startrow.value = 0;
		
        document.frmPage.frmPage_startpage.value = 1;
			
        document.frmPage.frmPage_currentpage.value = 1;
		
        document.frmPage.frmPage_endpage.value = 10;
        document.frmPage.frmPage_rowcount.value = 10;
	
	
        document.frmPage.action=formObj+'&ID='+'';
        document.frmPage.submit();
}
function refreshPaging(formObj){
       
		document.frmPage.frmPage_fid.value = "";
			
        document.frmPage.txtsearch.value = "";
        document.frmPage.frmPage_fvalue.value = "";
        document.frmPage.frmPage_startrow.value = 0;
		
        document.frmPage.frmPage_startpage.value = 1;
			
        document.frmPage.frmPage_currentpage.value = 1;
		
        document.frmPage.frmPage_endpage.value = 10;
        document.frmPage.frmPage_rowcount.value = 10;
	
	
        document.frmPage.action=formObj+'?ID='+'';
        document.frmPage.submit();
}
function refreshPagingCount(formObj){
                document.frmPage.frmPage_fid.value = "";
                document.frmPage.frmPage_fvalue.value = "";
                //alert('in');
				document.frmPage.frmPage_startrow.value = 0;
                document.frmPage.frmPage_startpage.value = 1;
                document.frmPage.frmPage_currentpage.value = 1;
                document.frmPage.frmPage_endpage.value = frmPage.elements('frmPage_rowcount').value;
                document.frmPage.frmPage_rowcount.value = frmPage.elements('frmPage_rowcount').value;
                document.frmPage.action=formObj+'?frmPage_rowcount='+frmPage.elements('frmPage_rowcount').value+'&ID='+'';
                document.frmPage.submit();
}

function searchPaging(formObj){
        document.frmPage.frmPage_fid.value = "";
        document.frmPage.frmPage_fvalue.value = "";
        document.frmPage.frmPage_startrow.value = 0;
        document.frmPage.frmPage_startpage.value = 1;
        document.frmPage.frmPage_currentpage.value = 1;
        document.frmPage.frmPage_endpage.value = 10;
        document.frmPage.frmPage_rowcount.value = 10;
        document.frmPage.action=formObj+'?txtsearch'+document.frmPage.txtsearch.value+'&ID='+'';
        document.frmPage.submit();
}

function editRecord(editId,editId2,formObj,editId3)
{
      
		
		document.frmPage.action=formObj+'?dr='+editId3+'&ID='+editId;
        document.frmPage.submit();
		
}
function checkall(frm){
     
	  if(frmPage.chklistcheck.checked == false){
       for (i=0; i<frmPage.elements.length; i++) {
              if (frmPage.elements[i].type == "checkbox" || frmPage.elements[i].name != "chklistcheck" ) {
                 frmPage.elements[i].checked = false;
              }
         }
      }else{
          for (i=0; i<frmPage.elements.length; i++) {
              if (frmPage.elements[i].type == "checkbox" || frmPage.elements[i].name != "chklistcheck" ) {
                 frmPage.elements[i].checked = true;
              }
         }
      }
    }
function newrecord(formObj)
{
       document.frmPage.action=formObj+'?ID=0';
       document.frmPage.submit();
}
function cancleediting(formObj)
{
       document.frmEdit.action=formObj+'?ID='+'';
       document.frmEdit.submit();
}
function closeediting(formObj)
{
       document.frmEdit.action=formObj;
       document.frmEdit.submit();
}
function deleterecord(formObj)
{
      var getstr = "";
      var str ="";
      var selectedchk="XXX";
      for (i=0; i<frmPage.elements.length; i++) {
                 if (frmPage.elements[i].type == "checkbox"){
                     if(frmPage.elements[i].checked){
                        selectedchk="true";
                     }
                 }
             }
      if(selectedchk=="XXX"){
            alert("Please Click on the Checkbox to Delete");
            return;
      }
                

         alertify.confirm("Do You Want To Delete Seleted Record?", function (e) {
         if (e) {
			 for (i=0; i<frmPage.elements.length; i++) {
                if (frmPage.elements[i].type == "checkbox") {
                      if(frmPage.elements[i].checked){
                      str = frmPage.elements[i].name;
                      getstr += str.substr(8,str.length) + ",";
                }
               }

             }
             document.frmPage.action='in_delete.php?returnpage='+formObj+'&del='+getstr.substr(0,getstr.length-1);
             document.frmPage.submit();
         } else {
            return ;
         }

       });
}
function deleterecord_single(formObj,tblid)
{
         alertify.confirm("Do You Want To Delete The Record?", function (e) {
         if (e) {
             document.frmPage.action='in_delete_single.php?returnpage='+formObj+'&del='+tblid;
             document.frmPage.submit();
         } else {
            return ;
         }

       });
}
function deleterecord_pro(formObj)
{
      var getstr = "";
      var str ="";
      var selectedchk="XXX";
      for (i=0; i<frmPage.elements.length; i++) {
                 if (frmPage.elements[i].type == "checkbox"){
                     if(frmPage.elements[i].checked){
                        selectedchk="true";
                     }
                 }
             }
      if(selectedchk=="XXX"){
            alert("Please Click on the Checkbox to Delete");
            return;
      }
                

         alertify.confirm("Do You Want To Delete Seleted Record?", function (e) {
         if (e) {
			 for (i=0; i<frmPage.elements.length; i++) {
                if (frmPage.elements[i].type == "checkbox") {
                      if(frmPage.elements[i].checked){
                      str = frmPage.elements[i].name;
                      getstr += str.substr(8,str.length) + ",";
                }
               }

             }
             document.frmPage.action='in_delete_pro.php?returnpage='+formObj+'&del='+getstr.substr(0,getstr.length-1);
             document.frmPage.submit();
         } else {
            return ;
         }

       });
}
// Java script Function to change the Case to Upper
    function SearchAllowAlphaUpp(objEvent){
            var iKeyCode;
            if(window.event){
               iKeyCode = objEvent.keyCode;
            }
            else if(objEvent.which){
                iKeyCode = objEvent.which;
            }

            if((iKeyCode>=14 && iKeyCode<=31) || (iKeyCode>=33 && iKeyCode<37) ||
               (iKeyCode>=33 && iKeyCode<=36) || (iKeyCode>=42 && iKeyCode<44) ||
               (iKeyCode>=91 && iKeyCode<=94) ||
               (iKeyCode==96) ||(iKeyCode>=123 && iKeyCode<=255)){
                      alert('Alpha Only');
                      return false;
            }
            if((iKeyCode==13) ){
                window.document.frmPage.submit();
            }
            return true;
    }