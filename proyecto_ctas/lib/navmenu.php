<section id="portada2" class="portada2 background">
  
  <header id="header" class="header contenedor">

    <figure class="logotipo"> <!-- logotipo -->
      <img src="images/logo-imss-vector.png" width="75" height="60" alt="IMSS logo">
    </figure>
    <nav class="menu"> <!-- menu -->
      <div class="title">
        <?php
          echo MM_APPNAME ;
        ?>
      </div>
      <ul>
        <!-- <li> -->
        <!-- </li> -->
        <li>
          <a href="../index.php">Home</a>
        </li>

        <?php
          //Si ya ha iniciado sesión ...
          if ( isset( $_SESSION['username'] ) ) {
        ?>
            <!-- <li>
              <a href="./proyecto_saiia/indexSAIIA.php">Proyecto SAIIA</a>
            </li> -->
            <?php
              //Si ya ha iniciado sesión ...
              if ( $_SESSION['username'] == 'TOLF781016HDFRGR01' ) {
            ?>
                <li>
                  <a href="./proyecto_ctas/indexCuentasSINDO.php">Claves Usuario</a>
                </li>
            <?php
              }
            ?>

            <li>
              <a href="../logout.php">Cerrar Sesión (<?php if ( !empty( $_SESSION['username'] ) ) echo $_SESSION['username'] ?>)</a>
            </li>
        <?php
          }
          else {
        ?>
            <li>
              <a href="../login.php">Iniciar sesión</a>
            </li>
            <li>
              <a href="../signup.php">Registrar nuevo usuario</a>
            </li>
        <?php
          }
        ?>

      </ul>

    </nav>
  </header>

</section>