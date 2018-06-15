<?php
  // Start the session
    require_once('lib/appvars.php');
    require_once('commonfiles/startsession.php');

    require_once('lib/connectBD.php');

    require_once( 'commonfiles/funciones.php');

    // Insert the page header
    $page_title = MM_APPNAME;
    require_once('lib/header.php');

    // Show the navigation menu
    require_once('lib/navmenumain.php');
?>
    <div class="contenedor">
        <h1 class="titulo">Aplicaciones DSPA</h1>
        <h3 class="titulo-a">Portal de la División de Soporte a los Procesos de Afiliación</h3>
<!--        <a class="button" href="#intro">Conoce más</a>-->
        <!-- <a class="button" href="#login">Inicia sesión</a>  -->
    </div>
    </section>
  
<?php
// Make sure the user is logged in before going any further.
  if ( !isset( $_SESSION['id_user'] ) ) {
?>
    <section id="modulos" class="modulos contenedor">
          <article class="modulo">
              <img class="img-derecha" src="images/login.png" alt="Inicia sesión" width="250"/>
              <div class="contenedor-modulo-a">
                  <h3>¿Ya tienes una cuenta? <a href="login.php">Inicia sesión</a></h3>
                  <ul>
                      <li>Ingresa con tu CURP y contraseña</li>
                      <li>Los módulos visibles dependerán del perfil/permisos otorgados al usuario</li>
                  </ul>
              </div>
          </article>

          <article class="modulo">
              <img class="img-izquierda" src="images/sign_up_256.png" alt="Registrate aquí" width="250"/>
              <div class="contenedor-modulo-b">
                  <h3>¿Aún no tienes cuenta? <a href="signup.php">Registrate aquí</a></h3>
                  <ul>
                      <li>Sólo necesitas tu CURP y correo electrónico</li>
                      <li>Tu solicitud será revisada por los administradores de este portal</li>
                  </ul>
                  <!-- <a class="button background" href="signup.php">Registrar</a> -->
              </div>
            </article>
      </section>

<?php

  }
  else {
?>

    <section id="modulos" class="modulos contenedor">
        <article class="modulo">
<!--            <img class="img-derecha" src="images/login.png" alt="Inicia sesión" width="250"/>-->
            <div class="contenedor-modulo-a">
                <h2>Módulos disponibles</h2>
                <h3>Ver inventario</h3>
                <ul>
                    <li><a href="verinventario.php?id_delegacion=39">Ciudad de México Norte</a></li>
                    <li><a href="verinventario.php?id_delegacion=40">Ciudad de México Sur</a></li>
                    <li><a href="verinventario.php?id_delegacion=15">México (Zona Oriente)</a></li>
                    <li><a href="verinventario.php?id_delegacion=21">Oaxaca</a></li>
                    <li><a href="verinventario.php?id_delegacion=28">Tabasco</a></li>
                    <li><a href="verinventario.php?id_delegacion=29">Tamaulipas</a></li>
                </ul>
                <h3><a href="agregarvalija.php">Agregar valija</a></h3>
                <h3><a href="agregarsolicitud.php">Agregar solicitud</a></h3>
<!--                <h3><a href="versolicitudes.php">Ver solicitudes</a></h3>-->
<!--                <h3><a href="buscarsolicitud.php">Buscar solicitud por usuario</a></h3>-->
                <br>
                <h3><a href="">Resumen delegacional </a><em>(Próximamente...)</em></h3>
            </div>
        </article>
      </section>

<?php
  }
      // Insert the page footer
      require_once('lib/footer.php');
?>