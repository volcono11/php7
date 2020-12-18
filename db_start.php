<!DOCTYPE html>
<html>
   <head>
      <meta charset="utf-8">
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <title>Reradius | Dashboard</title>
      <!-- Tell the browser to be responsive to screen width -->
      <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
      <!-- Bootstrap 3.3.6 -->
      <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.css">

      <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
      <!-- Font Awesome -->
      <link rel="stylesheet" href="css/font-awesome.min.css">
      <link rel="stylesheet" href="css/ionicons.min.css">
      <link rel="stylesheet" href="plugins/select2/select2.min.css">
      <link rel="stylesheet" href="plugins/iCheck/all.css">
      <link rel="stylesheet" href="dist/css/mainStylesChild.css">
      <link rel="stylesheet" href="dist/css/styles.css">
      <link rel="stylesheet" type="text/css" href="childtable_css/style.css" />
      <link rel="stylesheet" href="css/alertify.core.css" />
      <link rel="stylesheet" href="css/alertify.default.css" id="toggleCSS" />
      <link rel="stylesheet" href="bootstrap/css/datepicker.css">
      <script src="js/alertify.min.js"></script>  
<script language="javascript">
function resetform(){
         alertify.confirm("Are you sure you want to continue download ?", function (e) {
         if (e) {
            window.location.href='database-backup.php';
         } else {
            return;
         }

       });

}
</script>
</head>
<body class="hold-transition sidebar-mini">

         <section class="content-header">
              <br>   <h2 class="title">&nbsp;&nbsp;DATABASE BACKUP</h2>
         </section>

         <section class="content" id='content-content-id' style='padding-right:5px;padding-left:5px;margin-top:-15px;' >
                  <div class="nav-tabs-custom" id="nav-tabs-custom-id">
                        <div class="tab-content" id='tab-content-id'>

                              <div class="box-body no-padding" id='box-body-id'>
          <div class='table-responsive'>
              <button class='btn btn-info inputs' style='margin:2px;' type='button'  onclick ='javascript:resetform();'>Download </font>&nbsp;<i class='fa fa-download' aria-hidden='true'></i></button><Br>
         </div>
                        </div>
                     </div>
                  </div>

         </section>

    </body>
</html>



       <script type='text/javascript'>
                $(window).load(function(){
                   boxHeight()

                   $(window).resize(function(){
                     boxHeight();
                   })



                });
                 function boxHeight(){
                    var height = $("#content-wrapper-id",parent.parent.document).height()-132;
                    $('#tab-content-id').height(height);
                    var boxheight = height +60;

                    $('#box-body-id').height(boxheight);
                    $('#box-body-id').slimScroll({
                      height: boxheight+'px'
                    });


                }
</script>


