<?php

  // Start the session
  require_once( 'startsession.php' );

  require_once( 'appvars.php' );
  require_once( 'connectvars.php' );
  
  // Insert the page header
  $page_title = 'Gestión Cuentas SINDO - Ver Solicitud';
  require_once( 'header.php' );
  
  // Show the navigation menu
  require_once( 'navmenu.php' );
  require_once( 'funciones.php');

  //$error_msg = "";

  // Make sure the user is logged in before going any further.
  if ( !isset( $_SESSION['user_id'] ) ) {
    echo '<p class="error">Por favor <a href="login.php">inicia sesión</a> para acceder a esta página.</p>';
    require_once('footer.php');
    exit();
  } else {
    // Connect to the database
    $dbc = mysqli_connect( DB_HOST, DB_USER, DB_PASSWORD, DB_NAME );

    if ( mysqli_connect_errno () ) {
      printf("Connect failed: %s\n", mysqli_connect_error());
      return "Falló la conexión a base de datos";
      require_once('footer.php');
      exit(); 
    }
    $error_msg = fnConnect( $dbc );
  }

  $query = "SELECT 
    ctas_solicitudes.id_solicitud, ctas_solicitudes.id_valija, 
    ctas_solicitudes.fecha_captura_ca, ctas_solicitudes.fecha_solicitud_del, ctas_solicitudes.fecha_modificacion, ctas_solicitudes.id_lote,
    ctas_solicitudes.delegacion, ctas_solicitudes.subdelegacion, 
    ctas_solicitudes.nombre, ctas_solicitudes.primer_apellido, ctas_solicitudes.segundo_apellido, 
    ctas_solicitudes.matricula, ctas_solicitudes.curp, ctas_solicitudes.curp_correcta, ctas_solicitudes.cargo, ctas_solicitudes.usuario, 
    ctas_solicitudes.id_movimiento, ctas_solicitudes.id_grupo_actual, ctas_solicitudes.id_grupo_nuevo, 
    ctas_solicitudes.comentario, ctas_solicitudes.id_causarechazo, ctas_solicitudes.archivo,
    CONCAT(ctas_usuarios.first_name, ' ', ctas_usuarios.first_last_name) AS creada_por
    FROM ctas_solicitudes, ctas_grupos grupos1, ctas_grupos grupos2, ctas_usuarios
    WHERE ctas_solicitudes.id_grupo_nuevo= grupos1.id_grupo
    AND   ctas_solicitudes.id_grupo_actual= grupos2.id_grupo
    AND   ctas_solicitudes.user_id = ctas_usuarios.user_id ";

  if ( !isset( $_GET['id_solicitud'] ) ) {
    $query = $query . "AND ctas_solicitudes.id_solicitud = '" . $_SESSION['id_solicitud'] . "'";
    
  } else {
    $query = $query . "AND ctas_solicitudes.id_solicitud = '" . $_GET['id_solicitud'] . "'";
  }

  $data = mysqli_query( $dbc, $query );

  if ( mysqli_num_rows( $data ) == 1 ) {
    // The user row was found so display the user data
    $row = mysqli_fetch_array($data);

  ?>

    <section id="main-container">
      <div class="row">

        <div class="col s2">
          <div class="signup-box">
            <div class="container">
              <?php
                  if (!isset($_GET['user_id']) || ($_SESSION['user_id'] == $_GET['user_id'])) 
                    echo '<p>¿Deseas editar esta <a href="editarsolicitud.php?id_solicitud=' . $row['id_solicitud'] . '">solicitud</a>?</p>';
              ?>
            </div>
          </div>
        </div>

        <div class="col s5">
          <div class="signup-box">
            <div class="container">
              <div class="input-field">

                <i class="material-icons prefix">view_quilt</i>
                <select disabled id="cmbLotes" name="cmbLotes">
                <?php
                  $query = "SELECT ctas_lotes.id_lote AS id_lote2,
                             ctas_lotes.lote_anio 
                            FROM ctas_lotes 
                            WHERE ctas_lotes.id_lote = " . $row['id_lote'];
                  $result = mysqli_query( $dbc, $query );

                  while ( $row2 = mysqli_fetch_array( $result ) )
                      echo '<option value="' . $row2['id_lote2'] . '" selected>' . $row2['lote_anio'] . '</option>';
                    ?>
                </select>
                <label>Número de Lote</label>
              </div>

              <div class="input-field">
                <i class="material-icons prefix">description</i>
                <select disabled id="cmbValijas" name="cmbValijas">
                  <!-- <option value="0">Seleccione # de Valija/Oficio</option> -->
                  <?php
                    $query = "SELECT ctas_valijas.id_valija AS id_valija2, 
                                ctas_valijas.delegacion AS num_del, 
                                ctas_delegaciones.descripcion AS delegacion_descripcion, 
                                ctas_valijas.num_oficio_del,
                                ctas_valijas.num_oficio_ca, 
                                ctas_valijas.user_id
                              FROM ctas_valijas, ctas_delegaciones 
                              WHERE ctas_valijas.delegacion = ctas_delegaciones.delegacion 
                              AND ctas_valijas.id_valija = " . $row['id_valija'];
                    /*echo $query;*/
                    $result = mysqli_query( $dbc, $query );
                    while ( $row2 = mysqli_fetch_array( $result ) )
                      /*echo '<option value="0">Seleccione # de Valija/Oficio</option>';*/
                      echo '<option value="' . $row2['id_valija2'] . '" selected>' . $row2['num_oficio_ca'] . ': ' . $row2['num_del'] . '-' . $row2['delegacion_descripcion'] . '</option>';
                  ?>
                </select>
                <label>Número de Valija/Oficio</label>
              </div>

              <label for="fecha_solicitud_del">Fecha solicitud:</label>
              <div class="input-field">
                <i class="material-icons prefix">today</i>
                <input disabled type="text" id="fecha_solicitud_del" name="fecha_solicitud_del" value="<?php if ( !empty( $row['fecha_solicitud_del'] ) ) echo $row['fecha_solicitud_del']; ?>"/>
              </div>

              <div class="input-field">
                <i class="material-icons prefix">view_list</i>
                <select disabled id="cmbtipomovimiento" name="cmbtipomovimiento">
                  <?php
                    $query = "SELECT * 
                              FROM ctas_movimientos
                              WHERE id_movimiento = " . $row['id_movimiento'];
                    $result = mysqli_query( $dbc, $query );
                    while ( $row2 = mysqli_fetch_array( $result ) )
                      echo '<option value="' . $row2['id_movimiento'] . '" ' . fntipomovimientoSelect( $row2['id_movimiento'] ) . '>' . $row2['descripcion'] . '</option>';
                  ?>
                </select>
                <label for="cmbtipomovimiento">Tipo de Movimiento</label>
              </div>

              <div class="input-field">
                <i class="large material-icons prefix">business</i>
                <select disabled id="cmbDelegaciones" name="cmbDelegaciones" >
                  <?php
                    $query = "SELECT * 
                              FROM ctas_delegaciones 
                              WHERE delegacion = " . $row['delegacion'];
                    $result = mysqli_query( $dbc, $query );
                    while ( $row2 = mysqli_fetch_array( $result ) )
                      echo '<option value="' . $row2['delegacion'] . '" selected>' . $row2['delegacion'] . ' - ' . $row2['descripcion'] . '</option>';
                  ?>
                </select>
                <label>Delegación IMSS</label>
              </div>

              <div class="input-field">
                <i class="material-icons prefix">store</i>
                <select disabled class="active validate" id="cmbSubdelegaciones" name="cmbSubdelegaciones" >
                  <?php
                    $query = "SELECT * 
                              FROM ctas_subdelegaciones 
                              WHERE delegacion = " . $row['delegacion'] . " AND subdelegacion = " . $row['subdelegacion'];
                    $result = mysqli_query( $dbc, $query );
                    while ( $row2 = mysqli_fetch_array( $result ) )
                      echo '<option value="' . $row2['subdelegacion'] . '" selected>' . $row2['subdelegacion'] . ' - ' . $row2['descripcion'] . '</option>';
                  ?>
                </select>
              </div>

              <div class="input-field">
                <i class="material-icons prefix">perm_identity</i>
                <input type="text" disabled class="active validate" name="primer_apellido" id="primer_apellido" length="32" value="<?php if ( !empty( $row['primer_apellido'] ) ) echo $row['primer_apellido']; ?>"/>
                <label data-error="Error" for="primer_apellido">Primer apellido</label>
              </div>

              <div class="input-field">
                <i class="material-icons prefix">perm_identity</i>
                <input type="text" disabled class="active validate" name="segundo_apellido" id="segundo_apellido" length="32" value="<?php if ( !empty( $row['segundo_apellido'] ) ) echo $row['segundo_apellido']; ?>"/>
                <label data-error="Error" for="segundo_apellido">Segundo apellido</label>
              </div>

              <div class="input-field">
                <i class="material-icons prefix">perm_identity</i>
                <input type="text" required disabled class="active validate" name="nombre" id="nombre" length="32" value="<?php if ( !empty( $row['nombre'] ) ) echo $row['nombre']; ?>"/>
                <label data-error="Error" for="nombre">Nombre(s)</label>
              </div>


            </div>
          </div>
        </div>

        <div class="col s5">
          <div class="signup-box">
            <div class="container">

              <div class="input-field">
                <i class="material-icons prefix">assignment_ind</i>
                <input type="text" required disabled class="active validate" name="matricula" id="matricula" length="32" value='<?php if ( !empty( $row['matricula'] ) ) echo $row['matricula']; ?>'/>
                <label data-error="Error" for="matricula">Matrícula</label>
              </div>
              
              <div class="input-field">
                <!-- <div class="section"> -->
                  <i class="material-icons prefix">account_circle</i>
                  <input type="text" required disabled class="active validate" name="curp" id="curp" length="18" value="<?php if ( !empty( $row['curp'] ) ) echo $row['curp']; ?>" />
                  <label data-error="Error" for="curp">CURP</label>
              </div>

              <div class="input-field">
                <i class="material-icons prefix">assignment</i>
                <input type="text" required disabled class="active validate" name="usuario" id="usuario" length="7" value="<?php if ( !empty( $row['usuario'] ) ) echo $row['usuario']; ?>" />
                <label data-error="Error" for="usuario">Usuario</label>
              </div>

              <div class="input-field">
                <i class="material-icons prefix">label_outline</i>
                <select disabled id="cmbgpoactual" class="active validate" name="cmbgpoactual" >
                  <!-- <option value="0">Seleccione Grupo Actual</option> -->
                  <?php
                    $query = "SELECT * 
                              FROM ctas_grupos 
                              WHERE id_grupo = " . $row['id_grupo_actual'];
                    $result = mysqli_query( $dbc, $query );
                    while ( $row2 = mysqli_fetch_array( $result ) )
                      echo '<option value="' . $row2['id_grupo'] . '" selected>' . $row2['descripcion'] . '</option>';
                  ?>
                </select>
                <label>Grupo Actual</label>
              </div>

              <div class="input-field">
                <i class="material-icons prefix">label</i>
                <select disabled id="cmbgponuevo" name="cmbgponuevo" >
                  <?php
                    $query = "SELECT * 
                              FROM ctas_grupos 
                              WHERE id_grupo = " . $row['id_grupo_nuevo'];
                    $result = mysqli_query( $dbc, $query );
                    while ( $row2 = mysqli_fetch_array( $result ) )
                      echo '<option value="' . $row2['id_grupo'] . '" selected>' . $row2['descripcion'] . '</option>';
                  ?>
                </select>
                <label>Grupo Nuevo</label>
              </div>

              <div class="input-field">
                <i class="material-icons prefix">report_problem</i>
                <select disabled id="cmbcausarechazo" name="cmbcausarechazo" >
                  <?php
                    $query = "SELECT * 
                                                    FROM ctas_causasrechazo
                                                    WHERE id_causarechazo = " . $row['id_causarechazo'];
                    $result = mysqli_query( $dbc, $query );
                    while ( $row2 = mysqli_fetch_array( $result ) )
                      echo '<option value="' . $row2['id_causarechazo'] . '" selected>' . $row2['id_causarechazo'] . ' - ' . $row2['descripcion'] . '</option>';
                  ?>
                </select>
                <label>Causa de Rechazo</label>
              </div>
                  
              <div class="input-field">
                <i class="material-icons prefix">comment</i>
                <textarea disabled class="materialize-textarea" class="validate" id="comentario" length="256" name="comentario"><?php if ( !empty( $row['comentario'] ) ) echo $row['comentario']; ?></textarea>
                <label data-error="Error" for="comentario">Comentario</label>
              </div>

              <div>
                <i class="material-icons prefix">description</i>
                <label data-error="Error" for="usuario">Archivo</label>
                <div class="section" align="right">
                  <?php 
                    if ( !empty( $row['archivo'] ) ) 
                      echo '<a href="' . MM_UPLOADPATH_CTASSINDO . '\\' . $row['archivo'] . '"  target="_new">' . $row['archivo'] . '</a>';
                    else echo '(Vacío)';
                  ?>
                </div>
              </div>

              <div class="input-field">
                <i class="material-icons prefix">contact</i>
                <input disabled type="text" required class="active validate" name="user_id" id="user_id" length="50" value="<?php if ( !empty( $row['creada_por'] ) ) echo $row['creada_por']; ?>" />
                <label data-error="Error" for="user_id">Modificada/Capturada por:</label>
              </div>

              <label for="fecha_modificacion">Fecha Modificación:</label>
                <div class="input-field">
                  <i class="material-icons prefix">today</i>
                  <input disabled type="text" id="fecha_modificacion" name="fecha_modificacion" value="<?php if ( !empty( $row['fecha_modificacion'] ) ) echo $row['fecha_modificacion']; ?>"/>
                </div>
           </div>
          </div>
        </div>

      </div>
    
  </section>
  <?php
  }
    // Insert the page footer
    require_once('footer.php');
  ?>
