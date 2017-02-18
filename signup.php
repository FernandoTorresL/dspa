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

  if ( !isset( $_SESSION['user_id'] ) ) {
    if ( isset( $_POST['submit'] ) ) {

      // Grab the user-entered log-in data
      $username   = mysqli_real_escape_string( $dbc, strtoupper( trim($_POST['username'] ) ) );
      $password1  = mysqli_real_escape_string( $dbc, trim( $_POST['password1'] ) );
      $password2  = mysqli_real_escape_string( $dbc, trim( $_POST['password2'] ) );
      
      // Check the CAPTCHA pass-phrase for verification
      $user_pass_phrase = SHA1( $_POST['verify'] );
      
      if ( $_SESSION['pass_phrase'] == $user_pass_phrase ) {

        if ( !empty( $username ) && !empty( $password1 ) && !empty( $password2 ) && ( $password1 == $password2 ) ) {
          // Make sure someone isn't already registered using this username
          $query = "SELECT * FROM ctas_usuarios WHERE username = '$username';";
          $data = mysqli_query( $dbc, $query );
          
          if ( mysqli_num_rows( $data ) == 0 ) {
            // The username is unique, so insert the data into the database
            $query = "INSERT INTO ctas_usuarios ( username, password, join_date ) VALUES ( '$username', SHA('$password1'), NOW() );";
            mysqli_query($dbc, $query);
            
            // Confirm success with the user
            echo '<h5 class="green-text">La nueva cuenta  ' . $username . '. ha sido creada exitosamente. 
              Ahora está listo para <a href="login.php">iniciar sesión</a></h5>';
            // Insert the page footer
            mysqli_close($dbc);
            require_once('lib/footer.php');
            exit();
          }
          else {
            // An account already exists for this username, so display an error message
            $error_msg = 'Ya existe una cuenta para este usuario ' . $username . '. Por favor utiliza una diferente o
              puedes intentar <a href="login.php">iniciar sesión';
            $username = "";
          }
        }
        else {
          $error_msg = 'Debes ingresar todos los datos para registrarte, incluyendo la contraseña deseada dos veces.';
        }
      }
      else {
        $error_msg = 'Por favor, captura la frase de verificación (CAPTCHA) exactamente como se muestra.';
      }
    }
  }
  mysqli_close($dbc);

// If the session var is empty, show any error message and the log-in form; otherwise confirm the log-in
  if ( empty( $_SESSION['user_id'] ) ) {
    echo '<h5 class="red-text">' . $error_msg . '</h5>';
?>

    <section id="main-container">
    <div class="row">

      <div class="col s4">
        <div class="container">
          <img src="images/sign_up_256.png" />
          <h6 class="center teal-text">Ingresa todos los datos para registrarte</h6>
        </div>
      </div>

      <div class="col s4">
        <div class="row">
          <div class="signup-box">

            <form class="signup-form" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">

              <div class="section">

                <i class="small material-icons prefix teal-text">account_circle</i>
                <div class="input-field teal-text">
                  <input type="text" required class="active validate" length="18" name="username" id=username  value="<?php if ( !empty( $user_username ) ) echo $user_username; ?>" />
                  <label data-error="Error al capturar CURP" for="curp">CURP</label>
                </div>

                <i class="small material-icons prefix teal-text">vpn_key</i>
                <div class="input-field teal-text">
                  <input type="password" required class="active validate" minlength=6 maxlength=12 id="password1" name="password1" />
                  <label data-error="Error al capturar contraseña" for="password1">Contraseña (entre 6 y 12 caracteres)</label>
                </div>

                <div class="input-field teal-text">
                  <input required class="active validate" id="password2" type="password" name="password2" />
                  <label data-error="Error al repetir la contraseña" for="password2">Repite la contraseña</label>
                </div>

                <i class="small material-icons prefix teal-text">dialpad</i> 
                  <img align="right" src="commonfiles/captcha.php" alt="Verificación CAPTCHA" />

                <div class="input-field teal-text">
                  <input type="text" required class="active validate" length="6" id="verify" name="verify" />
                  <label data-error="Error capturar CAPTCHA" for="verify">Captura la frase (CAPTCHA)</label>
                </div>

                <div class="input-field center">
                  <button class="btn waves-effect waves-light btn-signup center white-text teal" type="submit" name="submit">Registra Solicitud de Usuario
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
            <h6 class="center teal-text">¿Ya tienes cuenta? <a href="login.php">Ingresa aquí</a></h6>
          </div>
        </div>
      </div>

    </div>
    </section>

<?php
  }
  else {
    // Confirm the successful log-in
    echo('<h5 class="center teal-text">Ya tienes sesión activa como ' . $_SESSION['first_name'] . ' ' . $_SESSION['first_last_name'] . '( ' . $_SESSION['username'] . ').  <a href="login.php">Ingresa aquí</a></h5>');
  }

  // Insert the page footer
  require_once('lib/footer.php');
?>
