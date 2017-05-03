<!DOCTYPE html>
<html lang="en">

  <head>

    <?php
      echo '<title>' . MM_APPNAME . '</title>';
    ?>

    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
    <link href="https://fonts.googleapis.com/css?family=Montserrat|Roboto" rel="stylesheet">
    <link href="css/dspa_app.css" type="text/css" rel="stylesheet" media="screen,projection"/>

    <script src="http://code.jquery.com/jquery-1.11.3.min.js"></script>
    <script type="text/javascript">
      $("document").ready(function() {
        $( "#cmbDelegaciones" ).change(function(){
          var id = $("#cmbDelegaciones").val();
          $.get('http://mto02447wsftl08/dspa/commonfiles/subdelegaciones.php', {param_id:id})
          .done(function(data){
            //alert($("#cmbDelegaciones").val());
            $("#cmbSubdelegaciones").html(data);
          })
        })
      })
    </script>

  </head>

  <body>