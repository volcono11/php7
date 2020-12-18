function redirect(url, txt) {
    document.location.href = url;
}
function clearparty()
{
document.getElementById('txt_A_objectcode').value="";
document.getElementById('D_txt_A_objectcode').value="";
}
function uploadfile() {

        var o=document.getElementById('uploadedfile');
        var fileAndPath = o.value;
        var lastPathDelimiter = fileAndPath.lastIndexOf("\\");
        var fileNameOnly = fileAndPath.substring(lastPathDelimiter+1);
        if(fileNameOnly.substr(fileNameOnly.length-3,3).toLowerCase()=='pdf'){
                window.document.uploader.submit();
        }else{
                alert('Select jpg file only!');
                return;
        }
}

function divShowHide(var1){
        for (i=0;i<6;i++){                                                   //  For practicality max of 6 tabs are assumed
            divName = 'DIVTAB' + i;                                          //  Prepare the div tag name
            tdName  = 'EDITTAB' + i;                                         //  Prepare the edit TD name
            o       = window.document.getElementById(divName);               //  Get the DIV tag object
            o2      = window.document.getElementById(tdName);                //  get the TD object
            if(o){
                if (i==var1){                                                    //  The specific tab must be made visible
                    o.style.display = 'block';
                    o2.style.backgroundImage = 'url(img/tab_s.png)';    //      Change the TD back ground Image to selected
                } else {                                                         //  Other tabs mist be invisible
                    o.style.display = 'none';
                    o2.style.backgroundImage = 'url(img/tab.png)';      //      Change the TD back ground Image to normal
                }
            }
        }
    }


function checknum(e){

        var unicode=e.charCode? e.charCode : e.keyCode
        // if (unicode!=8||unicode!=9)
        if (unicode<8||unicode>9 && unicode!=46)
            {
            //if the key isn't the backspace key or tab key (which we should allow)
            if (unicode<48||unicode>57) //if not a number
            return false //disable key press
        }
     //    if(input.value.indexOf('.')==-1) document.getElementById('fldprice').value=input.value+'.00';

}



function LTrim(value) {
	if(value){
		var re = /\s*((\S+\s*)*)/;
		return value.replace(re, "$1");
	}
}
function RTrim(value) {
	if(value){
       var re = /((\s*\S+)*)\s*/;
       return value.replace(re, "$1");
    }
}
function trim(value) {
   return LTrim(RTrim(value));
}

function get(obj) {
      var getstr = "?";

      for (i=0; i<obj.elements.length; i++) {

         if (trim(obj.elements[i].type) == "text") {
             var strold = obj.elements[i].value;
             var strnew = strold.replace(/\&/g,"^^^");
             getstr += obj.elements[i].name + "=" + strnew + "&";
         }

         if (trim(obj.elements[i].type) == "hidden") {
             var strold = obj.elements[i].value;
             var strnew = strold.replace(/\&/g,"^^^");
             getstr += obj.elements[i].name + "=" + strnew + "&";
         }

         if (trim(obj.elements[i].type) == "checkbox") {
             if(a != trim(obj.elements[i].id)) {
                    objname = trim(obj.elements[i].id);
                    var a=trim(obj.elements[i].id);
                    getstr += obj.elements[i].id + "=";

                       var nus=obj.elements[objname].length;

                       if(nus){
                                 for (var ii=0; ii < obj.elements[objname].length; ii++)
                                 {
                                     if(obj.elements[objname][ii].checked){
                                        getstr += obj.elements[objname][ii].value + ",";
                                     }
                                 }
                       }else{
                          getstr += obj.elements[objname].checked + ",";
                       }
                     getstr = getstr.substr(0,getstr.length-1) + ",";

             }

            getstr = getstr.substr(0,getstr.length-1) + "&";
         }
         if (trim(obj.elements[i].type) == "select-one") {
             var strold1 = obj.elements[i].options[obj.elements[i].selectedIndex].value;
             var strnew1 = strold1.replace(/\&/g,"^^^");
             getstr += obj.elements[i].name + "=" + strnew1 + "&";
         }

         if (trim(obj.elements[i].type) == "password") {
             getstr += obj.elements[i].name + "=" + obj.elements[i].value + "&";
         }
         if (trim(obj.elements[i].type) == "file") {
             getstr += obj.elements[i].name + "=" + obj.elements[i].value + "&";
         }
         if (trim(obj.elements[i].type) == "textarea") {
             var textdata = obj.elements[i].value;
             textdata = textdata.replace('<br />','');
             getstr += obj.elements[i].name + "=" + textdata.replace(/\n\r?/g, '<br />') + "&";
         }

      }

      return getstr;
   }


function getrow(obj) {
      var getstr = "ZZZZZZXXXXXX=";
      for(i=1;i<51;i++)
      {
              var row_id=obj.elements['row_'+i];
              if(row_id){
                 if(row_id.value=="1"){

                        //getstr += i + "@";

                        var excisepercent_1=obj.elements['excisepercent_'+i];
                        var strold = excisepercent_1.value;
                        var strnew = strold.replace(/\&/g,"^^^");
                        getstr += excisepercent_1.name + ":" + strnew + ",";


                        var exciseamt_1=obj.elements['exciseamt_'+i];
                        var strold = exciseamt_1.value;
                        var strnew = strold.replace(/\&/g,"^^^");
                        getstr += exciseamt_1.name + ":" + strnew + ",";

                        var edcessecxisepercent_=obj.elements['edcessecxisepercent_'+i];
                        var strold = edcessecxisepercent_.value;
                        var strnew = strold.replace(/\&/g,"^^^");
                        getstr += edcessecxisepercent_.name + ":" + strnew + ",";

                        var edcessexciseamt_=obj.elements['edcessexciseamt_'+i];
                        var strold = edcessexciseamt_.value;
                        var strnew = strold.replace(/\&/g,"^^^");
                        getstr += edcessexciseamt_.name + ":" + strnew + ",";

                        var octroipercent_=obj.elements['octroipercent_'+i];
                        var strold = octroipercent_.value;
                        var strnew = strold.replace(/\&/g,"^^^");
                        getstr += octroipercent_.name + ":" + strnew + ",";

                        var octroiamt_=obj.elements['octroiamt_'+i];
                        var strold = octroiamt_.value;
                        var strnew = strold.replace(/\&/g,"^^^");
                        getstr += octroiamt_.name + ":" + strnew + ",";

                        var salestaxpercent_=obj.elements['salestaxpercent_'+i];
                        var strold = salestaxpercent_.value;
                        var strnew = strold.replace(/\&/g,"^^^");
                        getstr += salestaxpercent_.name + ":" + strnew + ",";

                        var salestaxamt_1=obj.elements['salestaxamt_'+i];
                        var strold = salestaxamt_1.value;
                        var strnew = strold.replace(/\&/g,"^^^");
                        getstr += salestaxamt_1.name + ":" + strnew + ",";

                        var servicetaxpercent_1=obj.elements['servicetaxpercent_'+i];
                        var strold = servicetaxpercent_1.value;
                        var strnew = strold.replace(/\&/g,"^^^");
                        getstr += servicetaxpercent_1.name + ":" + strnew + ",";

                        var servicetaxamt_1=obj.elements['servicetaxamt_'+i];
                        var strold = servicetaxamt_1.value;
                        var strnew = strold.replace(/\&/g,"^^^");
                        getstr += servicetaxamt_1.name + ":" + strnew + ",";

                        var vatpercent_1=obj.elements['vatpercent_'+i];
                        var strold = vatpercent_1.value;
                        var strnew = strold.replace(/\&/g,"^^^");
                        getstr += vatpercent_1.name + ":" + strnew + ",";

                        var vatamt_1=obj.elements['vatamt_'+i];
                        var strold = vatamt_1.value;
                        var strnew = strold.replace(/\&/g,"^^^");
                        getstr += vatamt_1.name + ":" + strnew + ",";

                        var turnovertaxpercent_1=obj.elements['turnovertaxpercent_'+i];
                        var strold = turnovertaxpercent_1.value;
                        var strnew = strold.replace(/\&/g,"^^^");
                        getstr += turnovertaxpercent_1.name + ":" + strnew + ",";

                        var turnovertaxamt_1=obj.elements['turnovertaxamt_'+i];
                        var strold = turnovertaxamt_1.value;
                        var strnew = strold.replace(/\&/g,"^^^");
                        getstr += turnovertaxamt_1.name + ":" + strnew + ",";


                        var quantity_1=obj.elements['quantity_'+i];
                        var strold = quantity_1.value;
                        var strnew = strold.replace(/\&/g,"^^^");
                        getstr += quantity_1.name + ":" + strnew + ",";

                        var rate_1=obj.elements['rate_'+i];
                        var strold = rate_1.value;
                        var strnew = strold.replace(/\&/g,"^^^");
                        getstr += rate_1.name + ":" + strnew + ",";

                        var uom_1=obj.elements['uom_'+i];
                        var strold = uom_1.value;
                        var strnew = strold.replace(/\&/g,"^^^");
                        getstr += uom_1.name + ":" + strnew + ",";

                        var discount_1=obj.elements['discount_'+i];
                        var strold = discount_1.value;
                        var strnew = strold.replace(/\&/g,"^^^");
                        getstr += discount_1.name + ":" + strnew + ",";

                        var total_1=obj.elements['total_'+i];
                        var strold = total_1.value;
                        var strnew = strold.replace(/\&/g,"^^^");
                        getstr += total_1.name + ":" + strnew + ",";

                        var linegross_1=obj.elements['linegross_'+i];
                        var strold = linegross_1.value;
                        var strnew = strold.replace(/\&/g,"^^^");
                        getstr += linegross_1.name + ":" + strnew + ",";



                        var articlecode_1=obj.elements['articlecode_'+i];
                        if ((articlecode_1.value==null)||(articlecode_1.value=="")){
                        var articlecode_1=obj.elements['articlename_'+i];
                        }
                        var strold = articlecode_1.value;
                        var strnew = strold.replace(/\&/g,"^^^");
                        getstr += articlecode_1.name + ":" + strnew + "=";



                 }
              }else{
              break;
              }
      }
      return getstr;
}
function getrowforpo(obj) {

      var getstr = "ZZZZZZXXXXXX=";
      for(i=1;i<51;i++)
      {
              var row_id=obj.elements['row_'+i];
              if(row_id){
                 if(row_id.value=="1"){

                        //getstr += i + "@";

                        var excisepercent_1=obj.elements['excisepercent_'+i];
                        var strold = excisepercent_1.value;
                        var strnew = strold.replace(/\&/g,"^^^");
                        getstr += excisepercent_1.name + ":" + strnew + ",";


                        var exciseamt_1=obj.elements['exciseamt_'+i];
                        var strold = exciseamt_1.value;
                        var strnew = strold.replace(/\&/g,"^^^");
                        getstr += exciseamt_1.name + ":" + strnew + ",";

                        var edcessecxisepercent_=obj.elements['edcessecxisepercent_'+i];
                        var strold = edcessecxisepercent_.value;
                        var strnew = strold.replace(/\&/g,"^^^");
                        getstr += edcessecxisepercent_.name + ":" + strnew + ",";

                        var edcessexciseamt_=obj.elements['edcessexciseamt_'+i];
                        var strold = edcessexciseamt_.value;
                        var strnew = strold.replace(/\&/g,"^^^");
                        getstr += edcessexciseamt_.name + ":" + strnew + ",";

                        var octroipercent_=obj.elements['octroipercent_'+i];
                        var strold = octroipercent_.value;
                        var strnew = strold.replace(/\&/g,"^^^");
                        getstr += octroipercent_.name + ":" + strnew + ",";

                        var octroiamt_=obj.elements['octroiamt_'+i];
                        var strold = octroiamt_.value;
                        var strnew = strold.replace(/\&/g,"^^^");
                        getstr += octroiamt_.name + ":" + strnew + ",";

                        var salestaxpercent_=obj.elements['salestaxpercent_'+i];
                        var strold = salestaxpercent_.value;
                        var strnew = strold.replace(/\&/g,"^^^");
                        getstr += salestaxpercent_.name + ":" + strnew + ",";

                        var salestaxamt_1=obj.elements['salestaxamt_'+i];
                        var strold = salestaxamt_1.value;
                        var strnew = strold.replace(/\&/g,"^^^");
                        getstr += salestaxamt_1.name + ":" + strnew + ",";

                        var servicetaxpercent_1=obj.elements['servicetaxpercent_'+i];
                        var strold = servicetaxpercent_1.value;
                        var strnew = strold.replace(/\&/g,"^^^");
                        getstr += servicetaxpercent_1.name + ":" + strnew + ",";

                        var servicetaxamt_1=obj.elements['servicetaxamt_'+i];
                        var strold = servicetaxamt_1.value;
                        var strnew = strold.replace(/\&/g,"^^^");
                        getstr += servicetaxamt_1.name + ":" + strnew + ",";

                        var vatpercent_1=obj.elements['vatpercent_'+i];
                        var strold = vatpercent_1.value;
                        var strnew = strold.replace(/\&/g,"^^^");
                        getstr += vatpercent_1.name + ":" + strnew + ",";

                        var vatamt_1=obj.elements['vatamt_'+i];
                        var strold = vatamt_1.value;
                        var strnew = strold.replace(/\&/g,"^^^");
                        getstr += vatamt_1.name + ":" + strnew + ",";

                        var turnovertaxpercent_1=obj.elements['turnovertaxpercent_'+i];
                        var strold = turnovertaxpercent_1.value;
                        var strnew = strold.replace(/\&/g,"^^^");
                        getstr += turnovertaxpercent_1.name + ":" + strnew + ",";

                        var turnovertaxamt_1=obj.elements['turnovertaxamt_'+i];
                        var strold = turnovertaxamt_1.value;
                        var strnew = strold.replace(/\&/g,"^^^");
                        getstr += turnovertaxamt_1.name + ":" + strnew + ",";

                        var quantity_1=obj.elements['quantity_'+i];
                        var strold = quantity_1.value;
                        var strnew = strold.replace(/\&/g,"^^^");
                        getstr += quantity_1.name + ":" + strnew + ",";

                        var description_1=obj.elements['description_'+i];
                        var strold = description_1.value;
                        strold=strold.replace(",","#");
                        var strnew = strold.replace(/\&/g,"^^^");
                        getstr += description_1.name + ":" + strnew + ",";

                        var rate_1=obj.elements['rate_'+i];
                        var strold = rate_1.value;
                        var strnew = strold.replace(/\&/g,"^^^");
                        getstr += rate_1.name + ":" + strnew + ",";

                        var total_1=obj.elements['total_'+i];
                        var strold = total_1.value;
                        var strnew = strold.replace(/\&/g,"^^^");
                        getstr += total_1.name + ":" + strnew + ",";

                        var uom_1=obj.elements['uom_'+i];
                        var strold = uom_1.value;
                        var strnew = strold.replace(/\&/g,"^^^");
                        getstr += uom_1.name + ":" + strnew + ",";

                        var discount_1=obj.elements['discount_'+i];
                        var strold = discount_1.value;
                        var strnew = strold.replace(/\&/g,"^^^");
                        getstr += discount_1.name + ":" + strnew + ",";


                        var linegross_1=obj.elements['linegross_'+i];
                        var strold = linegross_1.value;
                        var strnew = strold.replace(/\&/g,"^^^");
                        getstr += linegross_1.name + ":" + strnew + ",";

                        var chk_1=obj.elements['chk_'+i];
                        var strold = chk_1.getAttribute('uid');
                        var strnew = strold.replace(/\&/g,"^^^");
                        getstr +=  "id:" + strnew + "=";

                 }
              }else{
              break;
              }
      }
      return getstr;
}


function getrowforgrn(obj) {

      var getstr = "ZZZZZZXXXXXX=";
      for(i=1;i<51;i++)
      {

              var row_id=obj.elements['row_'+i];
              if(row_id){
                 if(row_id.value=="1"){

                        //getstr += i + "@";

                        var excisepercent_1=obj.elements['excisepercent_'+i];
                        var strold = excisepercent_1.value;
                        var strnew = strold.replace(/\&/g,"^^^");
                        getstr += excisepercent_1.name + ":" + strnew + ",";


                        var exciseamt_1=obj.elements['exciseamt_'+i];
                        var strold = exciseamt_1.value;
                        var strnew = strold.replace(/\&/g,"^^^");
                        getstr += exciseamt_1.name + ":" + strnew + ",";

                        var edcessecxisepercent_=obj.elements['edcessecxisepercent_'+i];
                        var strold = edcessecxisepercent_.value;
                        var strnew = strold.replace(/\&/g,"^^^");
                        getstr += edcessecxisepercent_.name + ":" + strnew + ",";

                        var edcessexciseamt_=obj.elements['edcessexciseamt_'+i];
                        var strold = edcessexciseamt_.value;
                        var strnew = strold.replace(/\&/g,"^^^");
                        getstr += edcessexciseamt_.name + ":" + strnew + ",";

                        var octroipercent_=obj.elements['octroipercent_'+i];
                        var strold = octroipercent_.value;
                        var strnew = strold.replace(/\&/g,"^^^");
                        getstr += octroipercent_.name + ":" + strnew + ",";

                        var octroiamt_=obj.elements['octroiamt_'+i];
                        var strold = octroiamt_.value;
                        var strnew = strold.replace(/\&/g,"^^^");
                        getstr += octroiamt_.name + ":" + strnew + ",";

                        var salestaxpercent_=obj.elements['salestaxpercent_'+i];
                        var strold = salestaxpercent_.value;
                        var strnew = strold.replace(/\&/g,"^^^");
                        getstr += salestaxpercent_.name + ":" + strnew + ",";

                        var salestaxamt_1=obj.elements['salestaxamt_'+i];
                        var strold = salestaxamt_1.value;
                        var strnew = strold.replace(/\&/g,"^^^");
                        getstr += salestaxamt_1.name + ":" + strnew + ",";

                        var servicetaxpercent_1=obj.elements['servicetaxpercent_'+i];
                        var strold = servicetaxpercent_1.value;
                        var strnew = strold.replace(/\&/g,"^^^");
                        getstr += servicetaxpercent_1.name + ":" + strnew + ",";

                        var servicetaxamt_1=obj.elements['servicetaxamt_'+i];
                        var strold = servicetaxamt_1.value;
                        var strnew = strold.replace(/\&/g,"^^^");
                        getstr += servicetaxamt_1.name + ":" + strnew + ",";

                        var vatpercent_1=obj.elements['vatpercent_'+i];
                        var strold = vatpercent_1.value;
                        var strnew = strold.replace(/\&/g,"^^^");
                        getstr += vatpercent_1.name + ":" + strnew + ",";

                        var vatamt_1=obj.elements['vatamt_'+i];
                        var strold = vatamt_1.value;
                        var strnew = strold.replace(/\&/g,"^^^");
                        getstr += vatamt_1.name + ":" + strnew + ",";

                        var turnovertaxpercent_1=obj.elements['turnovertaxpercent_'+i];
                        var strold = turnovertaxpercent_1.value;
                        var strnew = strold.replace(/\&/g,"^^^");
                        getstr += turnovertaxpercent_1.name + ":" + strnew + ",";

                        var turnovertaxamt_1=obj.elements['turnovertaxamt_'+i];
                        var strold = turnovertaxamt_1.value;
                        var strnew = strold.replace(/\&/g,"^^^");
                        getstr += turnovertaxamt_1.name + ":" + strnew + ",";

                        var quantity_1=obj.elements['quantity_'+i];
                        var strold = quantity_1.value;
                        var strnew = strold.replace(/\&/g,"^^^");
                        getstr += quantity_1.name + ":" + strnew + ",";

                        var rate_1=obj.elements['rate_'+i];
                        var strold = rate_1.value;
                        var strnew = strold.replace(/\&/g,"^^^");
                        getstr += rate_1.name + ":" + strnew + ",";

                        var acceptedqty_=obj.elements['acceptedqty_'+i];
                        var strold = acceptedqty_.value;
                        var strnew = strold.replace(/\&/g,"^^^");
                        getstr += acceptedqty_.name + ":" + strnew + ",";

                        var unitrate_=obj.elements['unitrate_'+i];
                        var strold = unitrate_.value;
                        var strnew = strold.replace(/\&/g,"^^^");
                        getstr += unitrate_.name + ":" + strnew + ",";

                        var total_1=obj.elements['total_'+i];
                        var strold = total_1.value;
                        var strnew = strold.replace(/\&/g,"^^^");
                        getstr += total_1.name + ":" + strnew + ",";

                        var discount_1=obj.elements['discount_'+i];
                        var strold = discount_1.value;
                        var strnew = strold.replace(/\&/g,"^^^");
                        getstr += discount_1.name + ":" + strnew + ",";


                        var linegross_1=obj.elements['linegross_'+i];
                        var strold = linegross_1.value;
                        var strnew = strold.replace(/\&/g,"^^^");
                        getstr += linegross_1.name + ":" + strnew + ",";

                        var transportation_=obj.elements['transportation_'+i];
                        var strold = transportation_.value;
                        var strnew = strold.replace(/\&/g,"^^^");
                        getstr += transportation_.name + ":" + strnew + ",";

                        var clearance_=obj.elements['clearance_'+i];
                        var strold = clearance_.value;
                        var strnew = strold.replace(/\&/g,"^^^");
                        getstr += clearance_.name + ":" + strnew + ",";

                        var miscellaneous_=obj.elements['miscellaneous_'+i];
                        var strold = miscellaneous_.value;
                        var strnew = strold.replace(/\&/g,"^^^");
                        getstr += miscellaneous_.name + ":" + strnew + ",";

                        var demurrage_=obj.elements['demurrage_'+i];
                        var strold = demurrage_.value;
                        var strnew = strold.replace(/\&/g,"^^^");
                        getstr += demurrage_.name + ":" + strnew + ",";

                        var othcharges_=obj.elements['othcharges_'+i];
                        var strold = othcharges_.value;
                        var strnew = strold.replace(/\&/g,"^^^");
                        getstr += othcharges_.name + ":" + strnew + ",";

                        var chk_1=obj.elements['chk_'+i];
                        var strold = chk_1.getAttribute('uid');
                        var strnew = strold.replace(/\&/g,"^^^");
                        getstr +=  "id:" + strnew + "=";


                 }


              }else{
              break;
              }
      }
      return getstr;
}


function getrownotax(obj) {

      var getstr = "ZZZZZZXXXXXX=";
      for(i=1;i<51;i++)
      {
              var row_id=obj.elements['row_'+i];
              if(row_id){
                 if(row_id.value=="1"){

                        //getstr += i + "@";

                        var quantity_1=obj.elements['quantity_'+i];
                        var strold = quantity_1.value;
                        var strnew = strold.replace(/\&/g,"^^^");
                        getstr += quantity_1.name + ":" + strnew + ",";

                        var rate_1=obj.elements['rate_'+i];
                        var strold = rate_1.value;
                        var strnew = strold.replace(/\&/g,"^^^");
                        getstr += rate_1.name + ":" + strnew + ",";

                        var discount_1=obj.elements['discount_'+i];
                        var strold = discount_1.value;
                        var strnew = strold.replace(/\&/g,"^^^");
                        getstr += discount_1.name + ":" + strnew + ",";

                        var total_1=obj.elements['total_'+i];
                        var strold = total_1.value;
                        var strnew = strold.replace(/\&/g,"^^^");
                        getstr += total_1.name + ":" + strnew + ",";

                        var articlecode_1=obj.elements['articlecode_'+i];
                        if ((articlecode_1.value==null)||(articlecode_1.value=="")){
                        var articlecode_1=obj.elements['articlename_'+i];
                        }
                        var strold = articlecode_1.value;
                        var strnew = strold.replace(/\&/g,"^^^");
                        getstr += articlecode_1.name + ":" + strnew + "=";



                 }
              }else{
              break;
              }
      }
      return getstr;
}
function getrowforissues(obj) {

      var getstr = "ZZZZZZXXXXXX=";
      for(i=1;i<51;i++)
      {
              var row_id=obj.elements['row_'+i];
              if(row_id){
                 if(row_id.value=="1"){

                        //getstr += i + "@";

                        var quantity_1=obj.elements['quantity_'+i];
                        var strold = quantity_1.value;
                        var strnew = strold.replace(/\&/g,"^^^");
                        getstr += quantity_1.name + ":" + strnew + ",";

                        var uom_1=obj.elements['uom_'+i];
                        var strold = uom_1.value;
                        var strnew = strold.replace(/\&/g,"^^^");
                        getstr += uom_1.name + ":" + strnew + ",";

                        var projectcode_1=obj.elements['projectcode_'+i];
                        var strold = projectcode_1.value;
                        var strnew = strold.replace(/\&/g,"^^^");
                        getstr += projectcode_1.name + ":" + strnew + ",";

                        var costcenter_1=obj.elements['costcenter_'+i];
                        var strold = costcenter_1.value;
                        var strnew = strold.replace(/\&/g,"^^^");
                        getstr += costcenter_1.name + ":" + strnew + ",";

                        var mrid_1=obj.elements['mrid_'+i];
                        var strold = mrid_1.value;
                        var strnew = strold.replace(/\&/g,"^^^");
                        getstr += mrid_1.name + ":" + strnew + ",";

                        var mrdate_1=obj.elements['mrdate_'+i];
                        var strold = mrdate_1.value;
                        var strnew = strold.replace(/\&/g,"^^^");
                        getstr += mrdate_1.name + ":" + strnew + ",";

                        var userid_1=obj.elements['userid_'+i];
                        var strold = userid_1.value;
                        var strnew = strold.replace(/\&/g,"^^^");
                        getstr += userid_1.name + ":" + strnew + ",";



                        var articlecode_1=obj.elements['articlecode_'+i];
                        if ((articlecode_1.value==null)||(articlecode_1.value=="")){
                        var articlecode_1=obj.elements['articlename_'+i];
                        }
                        var strold = articlecode_1.value;
                        var strnew = strold.replace(/\&/g,"^^^");
                        getstr += articlecode_1.name + ":" + strnew + "=";



                 }
              }else{
              break;
              }
      }
      return getstr;
}

function getrowforpurchaseindent(obj) {

      var getstr = "ZZZZZZXXXXXX=";
      for(i=1;i<51;i++)
      {
              var row_id=obj.elements['row_'+i];
              if(row_id){
                 if(row_id.value=="1"){

                        //getstr += i + "@";

                        var quantity_1=obj.elements['quantity_'+i];
                        var strold = quantity_1.value;
                        var strnew = strold.replace(/\&/g,"^^^");
                        getstr += quantity_1.name + ":" + strnew + ",";

                        var actualquantity_1=obj.elements['actualquantity_'+i];
                        if(actualquantity_1){
                                                var strold = actualquantity_1.value;
                        var strnew = strold.replace(/\&/g,"^^^");
                        getstr += actualquantity_1.name + ":" + strnew + ",";
                                                }

                        var uom_1=obj.elements['uom_'+i];
                        var strold = uom_1.value;
                        var strnew = strold.replace(/\&/g,"^^^");
                        getstr += uom_1.name + ":" + strnew + ",";

                        var description_1=obj.elements['description_'+i];
                        var strold = description_1.value;
                        var strnew = strold.replace(/\&/g,"^^^");
                        getstr += description_1.name + ":" + strnew + ",";

                        var invheadid_1=obj.elements['invheadid_'+i];
                        if(invheadid_1){
                        var strold = invheadid_1.value;
                        var strnew = strold.replace(/\&/g,"^^^");
                        getstr += invheadid_1.name + ":" + strnew + ",";
                        }

                                                var projectcode_1=obj.elements['projectcode_'+i];
                        if(projectcode_1){
                        var strold = projectcode_1.value;
                        var strnew = strold.replace(/\&/g,"^^^");
                        getstr += projectcode_1.name + ":" + strnew + ",";
                        }

                        var costcenter_1=obj.elements['costcenter_'+i];
                        if(costcenter_1){
                        var strold = costcenter_1.value;
                        var strnew = strold.replace(/\&/g,"^^^");
                        getstr += costcenter_1.name + ":" + strnew + ",";
                        }

                        var mrid_1=obj.elements['mrid_'+i];
                        if(mrid_1){
                        var strold = mrid_1.value;
                        var strnew = strold.replace(/\&/g,"^^^");
                        getstr += mrid_1.name + ":" + strnew + ",";
                        }

                        var mrdate_1=obj.elements['mrdate_'+i];
                        if(mrdate_1){
                        var strold = mrdate_1.value;
                        var strnew = strold.replace(/\&/g,"^^^");
                        getstr += mrdate_1.name + ":" + strnew + ",";
                        }

                        var userid_1=obj.elements['userid_'+i];
                        if(userid_1){
                        var strold = userid_1.value;
                        var strnew = strold.replace(/\&/g,"^^^");
                        getstr += userid_1.name + ":" + strnew + ",";
                        }

                                                var articlecode_1=obj.elements['articlecode_'+i];
                        if ((articlecode_1.value==null)||(articlecode_1.value=="")){
                        var articlecode_1=obj.elements['articlename_'+i];
                        }
                        var strold = articlecode_1.value;
                        var strnew = strold.replace(/\&/g,"^^^");
                        getstr += articlecode_1.name + ":" + strnew + "=";



                 }
              }else{
              break;
              }
      }
      return getstr;
}


function getrowforstock(obj) {

      var getstr = "ZZZZZZXXXXXX=";
      for(i=1;i<51;i++)
      {
              var row_id=obj.elements['row_'+i];
              if(row_id){
                 if(row_id.value=="1"){

                        //getstr += i + "@";

                        var quantity_1=obj.elements['quantity_'+i];
                        var strold = quantity_1.value;
                        var strnew = strold.replace(/\&/g,"^^^");
                        getstr += quantity_1.name + ":" + strnew + ",";

                        var articlecode_1=obj.elements['articlecode_'+i];
                        if ((articlecode_1.value==null)||(articlecode_1.value=="")){
                        var articlecode_1=obj.elements['articlename_'+i];
                        }
                        var strold = articlecode_1.value;
                        var strnew = strold.replace(/\&/g,"^^^");
                        getstr += articlecode_1.name + ":" + strnew + "#";



                 }
              }else{
              break;
              }
      }
      return getstr;
}
function getselectedcheck(obj) {

      var getstr = "?Mod=Delete&Values=";
      for (i=0; i<obj.elements.length; i++) {
         if (trim(obj.elements[i].type) == "checkbox") {
           if(obj.elements[i].checked == true){
              getstr += obj.elements[i].name + ",";
           }
         }
      }
      return trim(getstr);
   }

   function getonecheck(obj) {
      var getstr = "?Mod=Edit&Values=";
      for (i=0; i<obj.elements.length; i++) {
         if (trim(obj.elements[i].type) == "checkbox") {
           if(obj.elements[i].checked == true){
              getstr += obj.elements[i].name + ",";
           }
         }
      }
      return trim(getstr);
   }



function echeck(str) {
                var at="@"
                var dot="."
                var lat=str.indexOf(at)
                var lstr=str.length
                var ldot=str.indexOf(dot)

                if (str.indexOf(at)==-1){
                   alert("Invalid E-Mail Address")
                   return false;
                }

                if (str.indexOf(at)==-1 || str.indexOf(at)==0 || str.indexOf(at)==lstr){
                   alert("Invalid E-Mail Address")
                   return false;
                }

                if (str.indexOf(dot)==-1 || str.indexOf(dot)==0 || str.indexOf(dot)==lstr){
                    alert("Invalid E-Mail Address")
                    return false;
                }

                 if (str.indexOf(at,(lat+1))!=-1){
                    alert("Invalid E-Mail Address")
                    return false;
                 }

                 if (str.substring(lat-1,lat)==dot || str.substring(lat+1,lat+2)==dot){
                    alert("Invalid E-Mail Address")
                    return false;
                 }

                 if (str.indexOf(dot,(lat+2))==-1){
                    alert("Invalid E-Mail Address")
                    return false;
                 }

                 if (str.indexOf(" ")!=-1){
                    alert("Invalid E-Mail Address")
                    return false;
                 }
                 //(str.substring(ldot+1,ldot+4)==null)||
                 if (str.substring(ldot+1,ldot+4)==""){
                    alert("Invalid E-Mail Address");
                        return false;
                  }

                  return true;
        }



function submitNewRegistration(){

        document.location.href = 'registration.php';
}




  function xx(){
          alert('xx');
  }



  function sortTable(id, col, rev) {
                switch(col)
                {
                case 0:
                  document.getElementById('col').value="1";
                  break;
                case 1:
                  document.getElementById('col').value="2";
                  break;
                case 2:
                  document.getElementById('col').value="3";
                  break;
                case 3:
                  document.getElementById('col').value="4";
                  break;
                case 4:
                  document.getElementById('col').value="5";
                  break;

                default:
                   document.getElementById('col').value="0";
                }

                if(document.getElementById('desc_'+document.getElementById('col').value).value=="ASC"){
                  document.getElementById('desc_'+document.getElementById('col').value).value="DESC";
                }else{
                  document.getElementById('desc_'+document.getElementById('col').value).value="ASC";
                }

        return false;
  }