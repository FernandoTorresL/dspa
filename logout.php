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

  // Clear the error message
  /*$ip_address_host = "IP:" . $ip_address . "|EQUIPO:" . $host;*/

  $error_msg        = "";
  $ip               = "";
  $ip_address       = GetHostByName( $ip );
  $host             = gethostbyaddr($_SERVER['REMOTE_ADDR']);
  $ip_address_host  = "|EQUIPO:" . $host;

  $ResultadoConexion = fnConnectBD( 0,  $ip_address, $ip_address_host, '' );
  if ( !$ResultadoConexion ) {
    // Hubo un error en la conexión a la base de datos;
    printf( " Connect failed: %s", mysqli_connect_error() );
    require_once('./lib/footer.php');
    exit();
  }

  $dbc = mysqli_connect( DB_HOST, DB_USER, DB_PASSWORD, DB_NAME );

  if ( isset( $_SESSION['id_user'] ) ) {
    
    $log = fnGuardaBitacora( 5, 2, $_SESSION['id_user'],  $ip_address, 'CURP:' . $_SESSION['username'] . $ip_address_host );
    
    // Delete the session vars by clearing the $_SESSION array
    $_SESSION = array();

    // Delete the session cookie by setting its expiration to an hour ago (3600)
    if ( isset( $_COOKIE[session_name()] ) ) {
      setcookie( session_name(), '', time() - 3600 );
    }

    // Destroy the session
    session_destroy();
  }

  // Delete the user ID and username cookies by setting their expirations to an hour ago (3600)
  setcookie('id_user',          '', time() - 3600);
  setcookie('username',         '', time() - 3600);
  setcookie('nombre',           '', time() - 3600);
  setcookie('primer_apellido',  '', time() - 3600);
  setcookie('ip_address',       '', time() - 3600);
  setcookie('host',             '', time() - 3600);

  // Redirect to the home page
  $home_url = 'http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . '/index.php';
  header('Location: ' . $home_url);
?>
