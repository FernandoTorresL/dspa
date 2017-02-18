<?php
  // Start the session
  require_once('commonfiles/startsession.php');

  require_once('lib/appvars.php');
  require_once('lib/connectvars.php');

require_once( 'commonfiles/funciones.php');

  // Insert the page header
  $page_title = MM_APPNAME;
  require_once('lib/header.php');

  // Show the navigation menu
  require_once('lib/navmenu.php');
  
?>
<!-- <p class="center teal-text text-green darken-4">Hola</p> -->
  <div class="section no-pad-bot" id="index-banner">
    <div class="container">
      <div class="row center">
      
        <h1 class="header center teal-text text-green darken-4">
          <?php
            echo $page_title;
          ?>
        </h1>
        <h5 class="header col s12 teal-text text-green darken-4">Bienvenidos al portal de la División de Soporte a los Procesos de Afiliación</h5>
      </div>
    </div>
  </div>


  <div class="container">
    <!-- <div class="section"> -->
      <!--   Icon Section   -->
      <div class="row">

        <!-- <div class="col s12 m4">
          <div class="icon-block">
            <h2 class="center teal-text text-blue-grey"><i class="medium material-icons">play_for_work</i></h2>
            <p class="center">
              <a href="./resources/ChromeStandaloneSetup.zip" id="download-button" class="btn waves-effect waves darken-4 blue-grey">Descarga</a>
            </p>
            <p class="darken-4">Este portal se visualiza mejor en el navegador Google Chrome.</p>
          </div>
        </div> -->
        
        <div class="col s12 m6">
          <div class="icon-block">
            <a href="login.php" target="_blank">
              <h2 class="center teal-text text-blue-grey"><i class="medium material-icons">supervisor_account</i></h2>
              <!-- <span class="new badge yellow black-text" ></span> -->
              <h5 class="center">Iniciar sesión</h5>
            </a>
              <p class="darken-4">Ingresa con tu usuario y contraseña. Se requiere un usuario autorizado por la administración de este portal para visualizar todas las funciones.</p>
          </div>
        </div>

        <div class="col s12 m6">
          <div class="icon-block">
            <a href="signup.php" target="_blank">
            <h2 class="center teal-text text-blue-grey"><i class="medium material-icons">mode_edit</i></h2>
              <h5 class="center">Registrar nuevo usuario</h5>
            </a>
              <p class="darken-4">Ingresa una solicitud de usuario que será revisada por la administración de este portal.</p>
          </div>
        </div>
      </div>

    <!-- </div> -->
    <!-- <div class="section">
    </div> -->
  </div>

<?php
  // Insert the page footer
  require_once('lib/footer.php');
?>
