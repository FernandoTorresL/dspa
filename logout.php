<?php
  // If the user is logged in, delete the session vars to log them out
  session_start();

  $user_id = 0;
  if ( isset( $_SESSION['user_id'] ) )
    $user_id = $_SESSION['user_id'];
  

  $ip = "";
  $ip_address = GetHostByName( $ip );
  $host = gethostbyaddr($_SERVER['REMOTE_ADDR']);
  $ip_address_host = "EQUIPO:" . $host;


  if ( isset( $_SESSION['user_id'] ) ) {
    
    require_once( 'lib/connectvars.php' );
    require_once( 'commonfiles/funciones.php');
    $log = fnGuardaBitacora( 5, 2, $user_id,  $ip_address, $ip_address_host );
    
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
  setcookie('user_id', '', time() - 3600);
  setcookie('username', '', time() - 3600);
  setcookie('first_name', '', time() - 3600);
  setcookie('first_last_name', '', time() - 3600);
  setcookie('ip_address', '', time() - 3600);
  setcookie('host', '', time() - 3600);

  // Redirect to the home page
  $home_url = 'http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . '/index.php';
  header('Location: ' . $home_url);
?>
