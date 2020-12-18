<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<title>
      RADIUS-ERP
</title>
        <script type="text/javascript" src="js/ajax_functions.js"></script>
        <script src="assetss/js/jquery-2.0.2.min.js"></script>
        <script src="assetss/js/jquery-1.8.2.min.js"></script>
        <script src="assetss/js/supersized.3.2.7.min.js"></script>
        <script src="assetss/js/supersized-init.js"></script>
        <script src="assetss/js/scripts.js"></script>
        <script src="assetss/js/html5.js"></script>
        <script type="text/javascript" src="js/lib.js"></script>
        <script src="./colorbox/jquery.min.js"></script>
        <script src="./colorbox/jquery.colorbox.js"></script>


        <link rel="stylesheet" type="text/css" href="./css/colorbox.css" />
        <link rel="stylesheet" href="assetss/css/reset.css">
        <link rel="stylesheet" href="assetss/css/supersized.css">
        <link rel="stylesheet" href="assetss/css/style.css">
        <link rel="stylesheet" href="assetss/css/bootstrap.min.css">
        <link rel="stylesheet" href="assetss/css/base.css">
        <link rel="stylesheet" href="assetss/css/login-page.css">
        <link rel="stylesheet" href="icomoon/style.css">
        <link rel="stylesheet" href="assetss/css/radiustheme.css">
        <link rel="stylesheet" href="css/template_css.css" type="text/css" media="all" />


        <link rel="stylesheet" href="css/font-awesome.min.css">
        <link rel="stylesheet" href="css/ionicons.min.css">

        <link rel="stylesheet" href="dist/css/mainStyles.css">
        <link rel="stylesheet" href="dist/css/styles.css">
        <link rel="stylesheet" href="css/alertify.core.css" />
        <link rel="stylesheet" href="css/alertify.default.css" id="toggleCSS" />

        <script type="text/javascript" src="js/ajax_functions.js"></script>
        <script type="text/javascript" src="js/lib.js"></script>
        <script src="js/jquery.min.js"></script>
        <script src="js/alertify.min.js"></script>
        <script src="js/html5shiv.min.js"></script>
        <script src="js/respond.min.js"></script>
        <script src="plugins/jQuery/jquery-2.2.3.min.js"></script>
        <script src="bootstrap/js/bootstrap.min.js"></script>
<script type="text/javascript">
/* <![CDATA[ */
        function setFocus() {
                document.login.auth_user_id.select();
                document.login.auth_user_id.focus();
        }
    function lsetautocoff() {
        var form = document.login;
        form.auth_user_id.setAttribute("autocomplete", "off");
        form.auth_user_id.value = '';
        form.auth_password.setAttribute("autocomplete", "off");
        form.auth_password.value = '';
    }
             // document.onkeyup = KeyCheck;
              function KeyCheck()
              {
                 var KeyID = event.keyCode;
                 switch(KeyID)
                 {
                    case 13:
                    submitLoginPage();
                    break;

                 }
              }
/* ]]> */
</script>

<!--<script>
    $(function ()
    {
        $(".example4").colorbox({iframe:true, innerWidth:800, innerHeight:400});
        $(document).bind('cbox_closed', function(){


});
    })

</script>-->
<style type="text/css">
 .topright{
   position:absolute;
   top:0;
   right:0;
  }
  .topleft{
   position:absolute;
   bottom:0;
   left:0;
  }
</style>
<SCRIPT LANGUAGE="JavaScript">

                    function submitkey(evt){
                    var thispage;
                    evt = (evt) ? evt : ((window.event) ? event : null);
                    if(window.event){
                              iKeyCode = evt.keyCode;
                      }
                      else if(evt.which){
                          iKeyCode = evt.which;
                      }
                      if(iKeyCode==13){
                        submitLoginPage();
                      }
                    }

                    function submitLoginPage(){
                        var auth_user_id=document.getElementById('auth_user_id');
                        if ((auth_user_id.value==null)||(auth_user_id.value=="")){
                        alertify.error("Enter USER ID");
                        return;
                        }
                        var p=document.getElementById('auth_password');
                        if(p.value==''){
                        alertify.error('Enter PASSWORD');
                        return;
                        }

                     loginuser(get(document.login))
                  }
                   var xmlHttp
                  function loginuser(parameters)
                   {
                          //alert(parameters);
                          xmlHttp=GetXmlHttpObject()
                          if (xmlHttp==null)
                          {
                                 alert ("Browser does not support HTTP Request")
                                 return
                          }


                          var url="loginaction.php"+parameters
                          xmlHttp.onreadystatechange=stateChanged
                          xmlHttp.open("POST",url,true)
                          xmlHttp.send(null)
                   }
                   function stateChanged()
                   {
                         if (xmlHttp.readyState==4 || xmlHttp.readyState=="complete")
                         {
                         	
                               var s1 = trim(xmlHttp.responseText);
                               var s2 = "Sorry. Your password did not match the username.";
                               //alert(s1);
                               if(s1.toString() == s2.toString()){
                                alertify.error('Invalid User ID or Password !');
                               }else{
                                alertify.success('Login Success');
                                var MyWin = self.window;
                                MyWin.opener = self.window;
                                MyWin.location.href = 'content.php';

                               }
                         }
                   }
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



</SCRIPT>

</head>
<SCRIPT type="text/javascript">
     window.history.forward();
     function noBack() {
       window.history.forward();
     }
  function changeHashOnLoad() {
     window.location.href += "#";
     setTimeout("changeHashAgain()", "50");
}

function changeHashAgain() {
  window.location.href += "1";
}

var storedHash = window.location.hash;
window.setInterval(function () {
    if (window.location.hash != storedHash) {
         window.location.hash = storedHash;
    }
}, 50);
</SCRIPT>
<style>
<style type="text/css">
.topcorner{
   position:absolute;
   top:0;
   right:0;
  }
</style>
<!--<img style="float:left; padding-left:10px;padding-top:10px;" src="img/logo.png">-->

<body class="login">
<br><br><br>
   <div class="logo">


   </div>
<div class="content">
<FORM method="post" name="login" id="login" action=index.php>

<img src="img/newlogo1.png" align="center"><br> <br>

         <div class="form-group">
            <label class="form-label visible-ie8 visible-ie9">Username</label>
            <div class="input-icon">
               <i class="icon-user"></i>
               <input class="form-control placeholder-no-fix required" type="text"  autocomplete="off" placeholder="USER ID" name="auth_user_id" onkeyup='KeyCheck();' id="auth_user_id" value="<?php echo (isset($_COOKIE['username']))?$_COOKIE['username']:'';?>"/>
            </div>
         </div>
         <div class="form-group">
            <label class="form-label visible-ie8 visible-ie9">Password</label>
            <div class="input-icon">
               <i class="icon-key"></i>
               <input class="form-control placeholder-no-fix required" type="password"  onkeyup='KeyCheck();' autocomplete="off" placeholder="PASSWORD" name="auth_password" id="auth_password"  value="<?php echo (isset($_COOKIE['password']))?$_COOKIE['password']:'';?>"/>
            </div>
         </div>


           <button class="btn btn-primary btn-block" id="linkadd" name="linkadd" style='width:100%;' type=button name=Login value=" Login " onclick="javascript:submitLoginPage();" >
             <i class='fa fa-sign-in' aria-hidden='true'></i> &nbsp; Login
            </button>


      </form>
      <!-- END LOGIN FORM -->
   </div>

     <div class="flags">
       <font color='#FFFFFF'>By logging into Radius ERP you agree to our </font><u><a href='http://www.cpsdubai.com' target="_blank"><font color='#FFFFFF'>Terms and Conditions</a></u> <br> &copy; 2019 CPS All Rights Reserved.</font>
     </div>
    <!--   <div class='topleft'><img style="padding-left:10px;padding-bottom:10px;"  src="img/10.png">  </div>-->

</body>
</html>
