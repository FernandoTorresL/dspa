<?php
  // Start the session
  require_once('commonfiles/startsession.php');

  require_once('lib/appvars.php');
  require_once('lib/connectvars.php');

  // Insert the page header
  $page_title = MM_APPNAME;
  require_once('lib/header.php');

  // Show the navigation menu
  require_once('lib/navmenu.php');

  // Clear the error message
  $error_msg = "";

  // Connect to the database
  $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
  
  /* check connection */
  if ( mysqli_connect_errno() ) {
      printf( "Connect failed: %s\n", mysqli_connect_error() );
      exit();
  }
    
  /* change character set to utf8 */
  if ( !$dbc->set_charset( "utf8" ) ) {
      printf( "Error loading character set utf8: %s\n", $dbc->error );
  }
  else {
      /*printf( "Current character set: %s\n", $dbc->character_set_name() );*/
  }  

  // If the user isn't logged in, try to log them in
  if ( !isset( $_SESSION['user_id'] ) ) {

    if ( isset( $_POST['submit'] ) ) {

      // Grab the user-entered log-in data
      $user_username    = mysqli_real_escape_string( $dbc, strtoupper( trim($_POST['username'] ) ) );
      $user_password    = mysqli_real_escape_string( $dbc, trim( $_POST['password'] ) );
      $user_pass_phrase = SHA1( $_POST['verify'] );

      require_once( 'commonfiles/funciones.php');

      $ip = "";
      $ip_address = GetHostByName( $ip );
      $host = gethostbyaddr($_SERVER['REMOTE_ADDR']);
      /*$ip_address_host = "IP:" . $ip_address . "|EQUIPO:" . $host;*/
      $ip_address_host = "EQUIPO:" . $host;

      /*$_SESSION['ip_address']       = $ip_address;
      $_SESSION['host']             = $host;*/
      /*setcookie('ip_address',       $ip_address,              $tiempo_cookie);
      setcookie('host',             $host,                    $tiempo_cookie);*/

      if ( !empty( $user_username ) && !empty( $user_password )  
        && ( $_SESSION['pass_phrase'] == $user_pass_phrase ) ) {
        // Look up the username and password in the database
        $query = "SELECT user_id, username, first_name, first_last_name FROM ctas_usuarios WHERE username = '$user_username' AND password = SHA('$user_password');";
        /*echo $query;*/
        $data = mysqli_query($dbc, $query);

        if ( mysqli_num_rows( $data ) == 1 ) {
          // The log-in is OK so set the user ID and username session vars (and cookies), and redirect to the home page
          $row = mysqli_fetch_array( $data );
          
          $_SESSION['user_id']          = $row['user_id'];
          $_SESSION['username']         = $row['username'];
          $_SESSION['first_name']       = $row['first_name'];
          $_SESSION['first_last_name']  = $row['first_last_name'];
          $_SESSION['ip_address']       = $ip_address;
          $_SESSION['host']             = $host;

          $tiempo_cookie = time() + MM_EXPIRE_COOKIE_VAL;

          setcookie('user_id',          $row['user_id'],          $tiempo_cookie);
          setcookie('username',         $row['username'],         $tiempo_cookie);
          setcookie('first_name',       $row['first_name'],       $tiempo_cookie);
          setcookie('first_last_name',  $row['first_last_name'],  $tiempo_cookie);
          setcookie('ip_address',       $ip_address,              $tiempo_cookie);
          setcookie('host',             $host,                    $tiempo_cookie);

          $log = fnGuardaBitacora( 5, 1, $_SESSION['user_id'],  $_SESSION['ip_address'], 'EQUIPO:' . $_SESSION['host'] );
          /*echo "XX-X" . $log;*/

          $home_url = 'http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . '/index.php';
          header('Location: ' . $home_url);
        }
        else {
          // The username/password are incorrect so set an error message
          $error_msg = 'Lo siento, debes capturar un usuario y contraseña válidos para iniciar sesión.';
          /*$log = fnGuardaBitacora( 1, 1, 0,  "00AA99AA99AA99FF", "CURP:" . $user_username . "|Captcha:" . $user_pass_phrase . "|" );*/
          $ip = "";
/*          echo "IP:" . GetHostByName( $ip );*/
          /*echo $nombre_host;*/
          $log = fnGuardaBitacora( 5, 3, 0,  $ip_address, 'CURP:' . $user_username . '|Captcha(Ok)|' . $ip_address_host );
        }
      }
      else {
        // The username/password weren't entered so set an error message
        $error_msg = 'Para iniciar sesión, debes capturar todos los datos y la frase de verificación (CAPTCHA) exactamente como se muestra.';
        /*$log = fnGuardaBitacora( 5, 3, 0,  "00AA99AA99AA99FF", "|Captcha:" . $user_pass_phrase . "|" );*/
        /*$log = fnGuardaBitacora( 5, 3, 0,  '00AA99AA99AA99FF', 'CURP:' . $user_username . ' Captcha(Error):' );*/
        $log = fnGuardaBitacora( 5, 3, 0,  $ip_address, 'CURP:' . $user_username . '|Captcha(Error)|' . $ip_address_host );
      }
    }
  }
  
  // If the session var is empty, show any error message and the log-in form; otherwise confirm the log-in
  if ( empty( $_SESSION['user_id'] ) ) {
    echo '<h5 class="red-text">' . $error_msg . '</h5>';
?>

  <section id="main-container">
    <div class="row">

      <div class="col s4">
        <div class="container">
          <img class="iphone" src="images/login.png" />
          <h6 class="bold center teal-text">Ingresa los datos para iniciar sesión</h6>
        </div>
      </div>

      <div class="col s4">
        <div class="row">
          <div class="signup-box">

            <form class="signup-form" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
              
              <div class="section">

                <i class="small material-icons prefix teal-text">account_circle</i>
                <div class="input-field teal-text">
                  <input type="text" required class="active validate teal-text" length="18" name="username" id=username value="<?php if ( !empty( $user_username ) ) echo $user_username; ?>" />
                  <label data-error="Error al capturar CURP" for="curp">CURP</label>
                </div>

                <i class="small material-icons prefix teal-text">vpn_key</i>
                <div class="input-field teal-text">
                  <input type="password" required class="active validate" minlength=6 maxlength=12 id="password" name="password" />
                  <label data-error="Error al capturar contraseña" for="password1">Contraseña (entre 6 y 12 caracteres)</label>
                </div>

                <i class="small material-icons prefix teal-text">dialpad</i>
                  <img align="right" src="./commonfiles/captcha.php" alt="Verificación CAPTCHA" />
                <div class="input-field teal-text">
                  <input type="text" required class="active validate" length="6" id="verify" name="verify" />
                  <label data-error="Error capturar CAPTCHA" for="verify">Captura la frase (CAPTCHA)</label>
                </div>

                <div class="input-field center">
                  <button class="btn waves-effect waves-light btn-signup center white-text teal" type="submit" name="submit">Iniciar sesión
                    <i class="material-icons right white-text">send</i>
                  </button>
                </div>

              </div>

            </form>

          </div>
        </div>
      </div>

      <div class="col s4">
        <div class="container">
          <div class="login-box">
            <h6 class= "center teal-text">¿No tienes cuenta? <a href="signup.php">Regístrate aquí</a></h6>
          </div>
        </div>
      </div>

    </div>
  </section>

<?php
  }
  else {
    // Confirm the successful log-in
    echo(' <p class="center login teal-text">Ya tienes una sesión como ' . $_SESSION['first_name'] . ' ' . $_SESSION['first_last_name'] . ' (' . $_SESSION['username'] . '). ¿Deseas <a href="logout.php">cerrar sesión? </a> </p> ');
  }
?>

<?php
  // Insert the page footer
  require_once('./lib/footer.php');
?>
