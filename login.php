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

  // Clear the error message
  /*$ip_address_host = "IP:" . $ip_address . "|EQUIPO:" . $host;*/

  $error_msg        = "";
  $ip               = "";
  $ip_address       = GetHostByName( $ip );
  $host             = gethostbyaddr($_SERVER['REMOTE_ADDR']);
  $ip_address_host  = "EQUIPO:" . $host;

  $ResultadoConexion = fnConnectBD( 0,  $ip_address, $ip_address_host, '' );
  if ( !$ResultadoConexion ) {
    // Hubo un error en la conexión a la base de datos;
    printf( " Connect failed: %s", mysqli_connect_error() );
    require_once('./lib/footer.php');
    exit();
  }

  $dbc = mysqli_connect( DB_HOST, DB_USER, DB_PASSWORD, DB_NAME );
  
  // If the user isn't logged in, try to log them in
  if ( !isset( $_SESSION['id_user'] ) ) {

    if ( isset( $_POST['submit'] ) ) {

      // Grab the user-entered log-in data
      $username    =      mysqli_real_escape_string( $dbc, strtoupper( trim($_POST['curp'] ) ) );
      $user_password    = mysqli_real_escape_string( $dbc, trim( $_POST['password'] ) );
      $user_pass_phrase = SHA1( $_POST['verify'] );

      /*require_once( 'commonfiles/funciones.php');*/

      /*$ip = "";
      $ip_address = GetHostByName( $ip );
      $host = gethostbyaddr($_SERVER['REMOTE_ADDR']);
      $ip_address_host = "EQUIPO:" . $host;*/

      /*$_SESSION['ip_address']       = $ip_address;
      $_SESSION['host']             = $host;*/
      /*setcookie('ip_address',       $ip_address,              $tiempo_cookie);
      setcookie('host',             $host,                    $tiempo_cookie);*/

      if ( !empty( $username ) && !empty( $user_password )  
        && ( $_SESSION['pass_phrase'] == $user_pass_phrase ) ) {
        // Look up the username and password in the database
        $query = "SELECT id_user, username, nombre, primer_apellido, id_estatus
                  FROM dspa_usuarios 
                  WHERE username = '$username' 
                  AND password = SHA('$user_password') 
                  AND id_estatus IN ( 0, 1 )";
        /*echo $query;*/
        $data = mysqli_query($dbc, $query);

        if ( mysqli_num_rows( $data ) == 1 ) {

          $row = mysqli_fetch_array( $data );
          if ( $row['id_estatus'] == 0 ) {
            echo '<p class="advertencia">Su usuario y password son correctos, sin embargo, su cuenta no ha sido activada por el administrador de este sitio. Por favor contacte al Administrador del sitio. </p>';
            require_once('lib/footer.php');
            $log = fnGuardaBitacora( 5, 3, $row['id_user'],  $ip_address, 'CURP:' . $row['username'] . '|EQUIPO:' . $host );
            exit(); 
          }
          // The log-in is OK so set the user ID and username session vars (and cookies), and redirect to the home page
          
          $_SESSION['id_user']          = $row['id_user'];
          $_SESSION['username']         = $row['username'];
          $_SESSION['nombre']           = $row['nombre'];
          $_SESSION['primer_apellido']  = $row['primer_apellido'];
          $_SESSION['ip_address']       = $ip_address;
          $_SESSION['host']             = $host;

          $tiempo_cookie = time() + MM_EXPIRE_COOKIE_VAL;

          setcookie('id_user',          $row['id_user'],          $tiempo_cookie);
          setcookie('username',         $row['username'],         $tiempo_cookie);
          setcookie('nombre',           $row['nombre'],           $tiempo_cookie);
          setcookie('primer_apellido',  $row['primer_apellido'],  $tiempo_cookie);
          setcookie('ip_address',       $ip_address,              $tiempo_cookie);
          setcookie('host',             $host,                    $tiempo_cookie);

          $log = fnGuardaBitacora( 5, 1, $_SESSION['id_user'],  $_SESSION['ip_address'], 'CURP:' . $_SESSION['username'] . '|EQUIPO:' . $_SESSION['host'] );
          /*echo "XX-X" . $log;*/

          $home_url = 'http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . '/index.php';
          header('Location: ' . $home_url);
        }
        else {
          // The username/password are incorrect so set an error message
          $error_msg = 'Lo siento, debes capturar un usuario y contraseña válidos para iniciar sesión.';
          /*$log = fnGuardaBitacora( 1, 1, 0,  "00AA99AA99AA99FF", "CURP:" . $username . "|Captcha:" . $user_pass_phrase . "|" );*/
          $ip = "";
/*          echo "IP:" . GetHostByName( $ip );*/
          /*echo $nombre_host;*/
          $log = fnGuardaBitacora( 5, 3, 0,  $ip_address, 'CURP:' . $username . '|Captcha(Ok)|' . $ip_address_host );
          /*echo $log;*/
        }
      }
      else {
        // The username/password weren't entered so set an error message
        $error_msg = 'Para iniciar sesión, debes capturar todos los datos y la frase de verificación (CAPTCHA) exactamente como se muestra.';
        /*$log = fnGuardaBitacora( 5, 3, 0,  "00AA99AA99AA99FF", "|Captcha:" . $user_pass_phrase . "|" );*/
        /*$log = fnGuardaBitacora( 5, 3, 0,  '00AA99AA99AA99FF', 'CURP:' . $username . ' Captcha(Error):' );*/
        $log = fnGuardaBitacora( 5, 3, 0,  $ip_address, 'CURP:' . $username . '|Captcha(Error)|' . $ip_address_host );
        /*echo $log;*/
      }
    }
  }
  
  // If the session var is empty, show any error message and the log-in form; otherwise confirm the log-in
  if ( empty( $_SESSION['id_user'] ) ) {
    echo '<p class="red-text">' . $error_msg . '</p>';
?>

  <div class="contenedor">
    <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
      <h2>Ingresa los datos para iniciar sesión</h2>
      <ul>
        <li>
          <label for="curp">CURP (Usuario)</label>
          <input class="textinput" type="text" required id="curp" name="curp" maxlength="18" placeholder="Escriba su CURP" value="<?php if ( !empty( $username ) ) echo $username; ?>" />
        </li>
        <li>
          <label for="password">Contraseña</label>
          <input class="textinput" type="password" required id="password" name="password" maxlength=20 placeholder="Capture su contraseña" />
        </li>
        <li>
          <label for="verify">Captura la frase</label>
          <input class="textinput" type="text" required id="verify" name="verify" length="6" placeholder="Captura la frase" />
          <img src="commonfiles/captcha.php" alt="Verificación CAPTCHA" />
        </li>
        <li class="buttons">
          <input type="submit" name="submit" value="Iniciar sesión">
          <input type="reset" name="reset" value="Reset">
        </li>
        
        <li>
          <h4 class="center teal-text">¿No tienes cuenta? <a href="signup.php">Registrate aquí</a></h4>
        </li>
      </ul>
    </form>
  </div>

<?php
  }
  else {
    // Confirm the successful log-in
    echo(' <p class="center login teal-text">Ya tienes una sesión como ' . $_SESSION['nombre'] . ' ' . $_SESSION['primer_apellido'] . ' (' . $_SESSION['username'] . '). ¿Deseas <a href="logout.php">cerrar sesión? </a> </p> ');
  }
?>

<?php
  // Insert the page footer
  require_once('lib/footer.php');
?>
