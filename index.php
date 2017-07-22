<?php
  // Start the session
  require_once('commonfiles/startsession.php');

  require_once('lib/appvars.php');
  require_once('lib/connectBD.php');

  require_once( 'commonfiles/funciones.php');

  // Insert the page header
  $page_title = MM_APPNAME;
  require_once('lib/header.php');

  // Show the navigation menu
  require_once('lib/navmenu.php');
  
?>

<section id="modulos" class="modulos contenedor">
  <div class="contenedor">
    <h2 class="title">Bienvenido al Portal de la División de Soporte a los Procesos de Afiliación</h2>
    <!-- <button>Conoce mas</button> -->
  </div>
</section>
  
<?php
// Make sure the user is logged in before going any further.
  if ( !isset( $_SESSION['id_user'] ) ) {
?>
    <section id="modulos" class="modulos contenedor">
  
      <article class="modulo a"> <!-- guitarra 1 -->
        <img class="derecha" src="images/login.png" alt="Login" width="250"/>
        <div class="contenedor-modulo-a">
          <h4 class="title-a"><a href="login.php">Iniciar sesión</a></h4>
          <ol>
            <li>Ingresa con tu usuario y contraseña.</li>
            <li>Se requiere un usuario autorizado por la administración de este portal para visualizar todas las funciones.</li>
          </ol>
            <a class="button background" href="login.php">Iniciar</a>
        </div>
      </article>

      <article class="modulo b"> <!-- guitarra 2 -->
        <img class="izquierda" src="images/sign_up_256.png" alt="Sign Up" width="250"/>
        <div class="contenedor-modulo-b">
          <h4 class="title-b"><a href="signup.php">Registrar nuevo usuario</a></h4>
          <ol>
            <li>Ingresa una solicitud de usuario.</li>
            <li>Será revisada por la administración de este portal.</li>
          </ol>
          <a class="button background" href="signup.php">Registrar</a>
        </div>
      </article>

    </section>

<?php

  }
  else {
?>
    <section id="modulos" class="modulos contenedor">
  
      <article class="modulo a"> <!-- guitarra 1 -->
        <img class="derecha" src="images/login.png" alt="Login" width="250"/>
        <div class="contenedor-modulo-a">
          <h4 class="title-a"><a href="agregarlote.php">Crear nuevo lote</a></h4>
          <h4 class="title-a"><a href="generatablas.php">Generar tablas</a></h4>

          <h6 class="title-a"><a href="agregarsolicitud.php">Agregar solicitud</a>
            <a class="button background" href="agregarsolicitud.php">Agregar Solicitud</a></h6>
          <h4 class="title-a"><a href="agregarvalija.php">Agregar valija</a></h4>

          <h4 class="title-a"><a href="verDetalleCuentasSINDO.php">Ver Resumen</a>
            <a class="button background" href="verDetalleCuentasSINDO.php">Resumen</a></h4>
          <h4 class="title-a"><a href="buscarsolicitud.php">Buscar solicitud por usuario</a></h4>
          <h4 class="title-a"><a href="verstatuslote.php">Ver estatus lote</a></h4>

          <h4 class="title-a"><a href="leearchivovalijas.php">Leer archivo valijas</a></h4>
          <br>
          <h6 class="title-a"><a href="registrar_sol_usaf.php">Registrar atención a cuentas</a>
            <a class="button background" href="registrar_sol_usaf.php">Registrar</a></h6>
          <!-- <ol>
            <li>Ingresar a Capturar Solicitudes</li>
            <li>Se requiere un usuario autorizado por la administración de este portal para visualizar todas las funciones.</li>
          </ol> -->
            <!-- <a class="button background" href="agregarsolicitud.php">Agregar Solicitud</a> -->
        </div>
      </article>

    </section>

<?php
  }
      // Insert the page footer
      require_once('lib/footer.php');
?>