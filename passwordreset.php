<?php
  require_once('lib/appvars.php');

  // Start the session
  require_once('commonfiles/startsession.php');

  require_once('lib/connectBD.php');

  require_once('commonfiles/funciones.php');

  // Insert the page header
  $page_title = MM_APPNAME;
  require_once('lib/header.php');

  // Show the navigation menu
  require_once('lib/navmenu.php');

  // Clear the error message
  $error_msg        = "";
  $ip               = "";
  $host             = gethostbyaddr($_SERVER['REMOTE_ADDR']);
  $ip_address       = $host;
  $ip_address_host  = "EQUIPO:" . $host;

  $ResultadoConexion = fnConnectBD( 0,  $ip_address, $ip_address_host, '' );
  if ( !$ResultadoConexion ) {
    // Hubo un error en la conexión a la base de datos;
    printf( " Connect failed: %s", mysqli_connect_error() );
    require_once('./lib/footer.php');
    exit();
  }

  $dbc = mysqli_connect( DB_HOST, DB_USER, DB_PASSWORD, DB_NAME );
  if ( isset( $_POST['submit'] ) ) {

      // Grab the user-entered log-in data
    $emailpwdreset  = mysqli_real_escape_string( $dbc, trim($_POST['emailpwdreset'] ) );

    // The username/password weren't entered so set an error message
    $log = fnGuardaBitacora( 5, 7, 0,  $ip_address, 'email:' . $emailpwdreset . '|Pwd Reset|' . $ip_address_host );
    echo $log;
    $home_url = 'http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . '/passwordcomplete.php';
      header('Location: ' . $home_url);

  }

?>

<div class="contenedor">
    <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
      <h2>Recupera tu contraseña</h2>
      <ul>
        <li>
          <label for="emailpwdreset">Correo electrónico</label>
          <input class="textinput" type="email" id="emailpwdreset" required name="emailpwdreset" value="">
        </li>
        <li class="buttons">
          <input class="button" type="submit" name="submit" value="Recuperar contraseña">
<!--           -->
        </li>
<!--          <li>-->
<!--              <h4 class="center teal-text"><a href="./password/reset/">¿Olvidaste tu contraseña?</a></h4>-->
<!--          </li>-->
<!--        <li>-->
<!--          <h4 class="center teal-text">¿Aún no tienes cuenta? <a href="signup.php">Regístrate aquí</a></h4>-->
<!--        </li>-->
      </ul>
    </form>
  </div>

<?php
  // Insert the page footer
  require_once('lib/footer.php');
?>

