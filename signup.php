<?php
  require_once('lib/appvars.php');

  // Start the session
  require_once('commonfiles/startsession.php');

  require_once('lib/connectBD.php');

  require_once( 'commonfiles/funciones.php');
  require_once( 'commonfiles/validaciones.php');

  $page_title = fnTituloPag(1);

  require_once('lib/header.php');

  // Show the navigation menu
  require_once('lib/navmenu.php');

  // Clear the error message
  $error_msg = "";
  $output_form = 'yes';

  /*$ip_address_host = "IP:" . $ip_address . "|EQUIPO:" . $host;*/

  $error_msg        = "";
  $ip               = "";
  $ip_address       = GetHostByName( $ip );
  $host             = gethostbyaddr($_SERVER['REMOTE_ADDR']);
  $ip_address_host  = "EQUIPO:" . $host;

  // Connect to the database
  $ResultadoConexion = fnConnectBD( 0,  $ip_address, $ip_address_host, 'Conn-SignUp' );
  if ( !$ResultadoConexion ) {
    // Hubo un error en la conexión a la base de datos;
    printf( " Connect failed: %s", mysqli_connect_error() );
    require_once('lib/footer.php');
    exit();
  }

  $dbc = mysqli_connect( DB_HOST, DB_USER, DB_PASSWORD, DB_NAME );

  if ( !isset( $_SESSION[MM_APPNAME .'id_user'] ) ) {
    if ( isset( $_POST['submit'] ) ) {

      // Grab the user-entered log-in data
      $username   =           mysqli_real_escape_string( $dbc, strtoupper( trim($_POST['curp'] ) ) );
      $cmbDelegaciones =      mysqli_real_escape_string( $dbc, trim( $_POST['cmbDelegaciones'] ) );
      $cmbSubdelegaciones =   mysqli_real_escape_string( $dbc, trim( $_POST['cmbSubdelegaciones'] ) );
      $cmbPuesto =            mysqli_real_escape_string( $dbc, trim( $_POST['cmbPuesto'] ) );
      $email =                mysqli_real_escape_string( $dbc, trim( $_POST['email'] ) );
      $password1  =           mysqli_real_escape_string( $dbc, trim( $_POST['password1'] ) );
      $password2  =           mysqli_real_escape_string( $dbc, trim( $_POST['password2'] ) );
      
      // Check the CAPTCHA pass-phrase for verification
      $user_pass_phrase = SHA1( $_POST['verify'] );
      
      if ( $_SESSION['pass_phrase'] == $user_pass_phrase ) {

        if ( !empty( $username ) && !empty( $password1 ) && !empty( $password2 ) && ( $password1 == $password2 ) ) {
          // Make sure someone isn't already registered using this username
          $query = "SELECT * FROM dspa_usuarios WHERE username = '$username';";
          /*echo $query;*/
          $data = mysqli_query( $dbc, $query );
          
          if ( mysqli_num_rows( $data ) == 0 ) {
            // The username is unique, so insert the data into the database
            /*$query = "INSERT INTO dspa_usuarios ( username, password, join_date ) VALUES ( '$username', SHA('$password1'), NOW() );";*/
            $query = "INSERT INTO dspa_usuarios 
                        ( id_user, username, password, delegacion, subdelegacion, id_puesto, fecha_ini, email, fecha_registro, picture, id_estatus ) 
                      VALUES 
                        ( NULL, '$username', SHA('$password1'), '$cmbDelegaciones', '$cmbSubdelegaciones', '$cmbPuesto', NOW(), '$email', NOW(), NULL, '0' )";
            mysqli_query($dbc, $query);

            $query = "SELECT LAST_INSERT_ID()";
            /*$result = mysqli_query( $dbc, $query );*/
            $data = mysqli_query( $dbc, $query );
            if ( mysqli_num_rows( $data ) == 1 ) {
              // The user row was found so display the user data
              $row = mysqli_fetch_array($data);

              $id_user_nuevo = $row['LAST_INSERT_ID()'];
              $log = fnGuardaBitacora( 1, 20, $id_user_nuevo,  $ip_address, 'id_user:' . $id_user_nuevo . '|CURP:' . $username . '|EQUIPO:' . $ip_address_host );

              // Confirm success with the user
              echo '<h3 class="green-text">La nueva cuenta  ' . $username . '. ha sido creada exitosamente. 
                Ahora está listo para <a href="login.php">Iniciar sesión</a></h3>';
              // Insert the page footer
              mysqli_close($dbc);
              require_once('lib/footer.php');
              exit();
            }
            else {
              echo '<p class="error"><strong>El registro no ha podido realizarse. Contacta al administrador.</strong></p>';
            }
          }
          else {
            // An account already exists for this username, so display an error message
            $error_msg = 'Ya existe una cuenta para este usuario ' . $username . '. Utiliza una diferente o
              <a href="login.php">Inicia sesión';
            $username = "";
          }
        }
        else {
          $error_msg = 'La contraseña debe ser idéntica en ambos campos';
        }
      }
      else {
        $error_msg = 'Captura todos los caracteres de la imagen (CAPTCHA) exactamente como se muestran';
      }
    }
  }
  /*mysqli_close($dbc);*/

// If the session var is empty, show any error message and the log-in form; otherwise confirm the log-in
  if ( empty( $_SESSION[MM_APPNAME .'id_user'] ) ) {
    echo '<h3 class="red-text">' . $error_msg . '</h3>';
?>
  
    <div class="contenedor">
      <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
          <h2>Registro de nuevo usuario</h2>
          <ul class="campos">
          <li>
            <label for="curp">CURP</label>
            <input class="textinput" type="text" required id="curp" name="curp" maxlength="18" value="<?php if ( !empty( $username ) ) echo $username; ?>" />
          </li>
          <li>
            <label for="cmbDelegaciones">Delegación IMSS</label>
            <select class="textinput" id="cmbDelegaciones" name="cmbDelegaciones">
              <option value="0" style="color:gray">Seleccione Delegación</option>
              <?php
                $result = mysqli_query( $dbc, "SELECT delegacion AS id_delegacion,
                                                      RIGHT(CONCAT('0',delegacion),2) AS num_delegacion, 
                                                      descripcion
                                                FROM dspa_delegaciones 
                                                WHERE activo = 1 
                                                ORDER BY 1" );
                while ( $row = mysqli_fetch_array( $result ) )
                  echo '<option value="' . $row['id_delegacion'] . '" ' . fntdelegacionSelect( $row['id_delegacion'] ) . '>' . $row['num_delegacion'] . ' - ' . $row['descripcion'] . '</option>';
              ?>
            </select>
          </li>
          <li>
            <label for="cmbSubdelegaciones">Subdelegación IMSS</label>
            <select class="textinput" id="cmbSubdelegaciones" name="cmbSubdelegaciones">
              <option value="-1" >Seleccione Subdelegación</option>
              <?php
                if ( !empty( $_POST['cmbSubdelegaciones'] ) || $_POST['cmbSubdelegaciones'] == "0" )
                $result = mysqli_query( $dbc, "SELECT * 
                                                FROM dspa_subdelegaciones 
                                                WHERE delegacion = " . $_POST['cmbDelegaciones'] . " ORDER BY subdelegacion" );
                while ( $row = mysqli_fetch_array( $result ) )
                  echo '<option value="' . $row['subdelegacion'] . '" ' . fntsubdelegacionSelect( $row['subdelegacion'] ) . '>' . $row['subdelegacion'] . ' - ' . $row['descripcion'] . '</option>';
              ?>
            </select>
          </li>
          <li>
            <label for="cmbPuesto">Puesto</label>
            <select class="textinput" id="cmbPuesto" name="cmbPuesto">
              <option value="0">Seleccione Puesto</option>
              <?php
                $result = mysqli_query( $dbc, "SELECT * 
                                                FROM dspa_puestos
                                                ORDER BY id_puesto" );
                while ( $row = mysqli_fetch_array( $result ) )
                  echo '<option value="' . $row['id_puesto'] . '" ' . fntPuestoSelect( $row['id_puesto'] ) . '>' . $row['id_puesto'] . ' - ' . $row['descripcion'] . '</option>';
              ?>
            </select>
          </li>
          <li>
            <label for="email">Correo electrónico</label>
            <input class="textinput" type="email" required id="email" name="email" length="100" value="<?php if ( !empty( $email ) ) echo $email; ?>" />
          </li>
          <li>
            <label for="password1">Contraseña</label>
            <input class="textinput" type="password" required id="password1" name="password1" maxlength=20 />
          </li>
          <li>
            <label for="password2">Confirmar contraseña</label>
            <input class="textinput" type="password" required id="password2" name="password2" maxlength=20 />
          </li>
            <li>
                <label for="verify">Escribe los caracteres que veas en la imagen</label>
                <input class="textinput" type="text" required id="verify" name="verify" length="6" />
                <!--          <img src="commonfiles/captcha.php" alt="Verificación CAPTCHA" />-->
            </li>
            <li>
                <img id="imgverify" src="commonfiles/captcha.php" alt="Verificación CAPTCHA" />
            </li>
            <li class="buttons">
                <input class="button" type="submit" name="submit" value="Registrarse">
            </li>
        </ul>

          <!--            <input class="button" type="reset" name="reset" value="Reset">-->

            <h4 class="center teal-text">¿Ya tienes cuenta? <a href="login.php">Inicia sesión</a></h4>
          
      </form>
    </div>

<?php
  }
  else {
    // Confirm the successful log-in
    echo('<h3 class="center teal-text">Ya tienes sesión activa como ' . $_SESSION[MM_APPNAME .'nombre'] . ' ' . $_SESSION[MM_APPNAME .'primer_apellido'] . ' ( ' . $_SESSION[MM_APPNAME .'username'] . ').  <a href="index.php">Regresa a HOME</a></h3>');
  }

  // Insert the page footer
  require_once('lib/footer.php');
?>
