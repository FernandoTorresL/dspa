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

<section id="modulos" class="modulos contenedor">
  <div class="contenedor">
    <h2 class="title">Bienvenido al Portal de la División de Soporte a los Procesos de Afiliación</h2>
    <!-- <button>Conoce mas</button> -->
  </div>
</section>
  
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
  // Insert the page footer
  require_once('lib/footer.php');
?>
