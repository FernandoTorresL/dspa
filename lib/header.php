<!DOCTYPE html>
<html>
   <head>
      <meta charset="utf-8"/>
      <?php
        echo '<title>' . $page_title . MM_APPNAME . '</title>';
      ?>
      <link href='https://fonts.googleapis.com/css?family=Montserrat:400,700|Roboto' rel='stylesheet' type='text/css'>
      <link rel="stylesheet" href="css/dspa.css"/>
      <script src="http://code.jquery.com/jquery-1.11.3.min.js"></script>
      <script src="scripts/utils.js" type="text/javascript"></script>
      <script src="scripts/enroll.js" type="text/javascript"></script> 
      <script type="text/javascript">
      $('document').ready(function() {
        $( '#cmbDelegaciones' ).change(function(){
          var id = $('#cmbDelegaciones').val();
          $.get('./commonfiles/subdelegaciones.php', {param_id:id})
          .done(function(data){
            //alert($("#cmbDelegaciones").val());
            $('#cmbSubdelegaciones').html(data);
          })
        })
      })
    </script>
   </head>

   <body>
    