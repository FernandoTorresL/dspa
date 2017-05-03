<?php
  // Start the session
  require_once('startsession.php');

  require_once('appvars.php');
  require_once('connectvars.php');

  // Insert the page header
  $page_title = MM_APPNAME;
  require_once('header.php');

  // Show the navigation menu
  require_once('navmenu.php');

  $error_msg = "";

  // Make sure the user is logged in before going any further.
  if ( !isset( $_SESSION['user_id'] ) ) {
    echo '<p class="error" align="justify">Por favor <a href="login.php">inicia sesión</a> para acceder a esta página.</p>';
    // Insert the page footer
    require_once('footer.php');
    exit();
  }
  require_once( 'funciones.php');
?>
  <section id="main-container">
    <div class="row">

      <div class="col s5">
        <div class="container">
        </div>
      </div>

      <div class="col s2">
        <div class="row">
          <div class="signup-box">
            <form class="signup-form" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
              <div class="input-field">
                <i class="material-icons prefix">view_quilt</i>
                <input type="text" class="validate" name="new_lote" id="new_lote" length="9" value="<?php if ( !empty( $new_lote ) ) echo $new_lote; ?>" />
                <label data-error="Demasiados caracteres" for="new_lote">Nuevo Lote</label>
              </div>
              <div class="input-field">
                <i class="material-icons prefix">comment</i>
                <textarea class="materialize-textarea" class="validate" id="comentario" length="100" name="comentario"><?php if ( !empty( $comentario ) ) echo $comentario; ?></textarea>
                <label data-error="Insuficiente" for="comentario">Comentario</label>
              </div>
              <div class="section" align="center">
                <button class="btn waves-effect waves-light btn-signup" type="submit" name="submit">Crear lote<i class="material-icons right">send</i>
                </button>
              </div>
            </form>
          </div>
        </div>
      </div>

      <div class="col s5">
        <div class="container">
          <div class="row">
              <?php
                $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
                if ( mysqli_connect_errno () ) {
                  printf("Connect failed: %s\n", mysqli_connect_error());
                  return "Falló la conexión a base de datos";
                  require_once('footer.php');
                  exit(); 
                }

                $error_msg = fnConnect( $dbc );

                if ( isset( $_POST['submit'] ) ) {
                  echo '<div class="signup-box">';
                  echo '<div class="container">';
                  // Conectarse a la BD
                  $new_lote = mysqli_real_escape_string($dbc, trim($_POST['new_lote']));
                  $comentario = mysqli_real_escape_string($dbc, trim($_POST['comentario']));
                  //$error = false;

                  if ( !empty( $comentario ) && !empty( $new_lote ) ) {
                    $query = "INSERT INTO ctas_lotes 
                      ( lote_anio, fecha_creacion, fecha_modificacion, comentario, user_id )
                      VALUES 
                      ( '$new_lote', NOW(), NOW(), '$comentario', " . $_SESSION['user_id'] . ")";

                    mysqli_query($dbc, $query);
                    // Confirm success with the user
                    echo '<p><strong>El nuevo lote ' . $new_lote . ' ha sido creado exitosamente.</strong></p>';
                    /*$new_lote = "";
                    $comentario = "";*/
                  } else {
                    $error_msg = 'Debes ingresar todos los datos para registrar el lote. ' . $error_msg;
                  }
                }
                echo '<p class="error" align="justify">' . $error_msg . '</p>';
                echo '</div>';
                echo '</div>';
              ?>
            
          </div>
        </div>
      </div>

    </div>
    
  </section>
  <?php
    // Insert the page footer
    require_once('footer.php');
  ?>
