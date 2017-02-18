
<nav class="blue-grey darken-3" role="navigation">
  <div class="nav-wrapper container">
    <!-- <a href="#" class="brand-logo">Logo</a> -->
    <a id="logo-container" href="#" class="brand-logo">
      <div class="container">
        <img src="images/logoIMSStransparente2.png" />
      </div>

    </a>
    <ul class="right hide-on-med-and-down">
    <?php

    //Si ha iniciado sesión ...
    if ( isset( $_SESSION['username'] ) ) {
    ?>
        <li><a href="index.php">Inicio</a></li>
        <li><a href="./proyecto_saiia/indexSAIIA.php">Proyecto SAIIA</a></li>
        <li><a href="indexCuentasSINDO.php">Claves Usuario</a></li>
        <li><a href="logout.php">Cerrar Sesión (<?php if ( !empty( $_SESSION['username'] ) ) echo $_SESSION['username'] ?>)</a></li>
        <!-- <li><a href="">Herramientas</a></li> -->
        <!-- <li><a class="dropdown-button" href="#!" data-activates="dropdown1">Opciones de sesión<i class="material-icons right">arrow_drop_down</i></a></li> -->
    <?php
      }
      else {
    ?>
        <li><a href="index.php">Home</a></li>
        <li><a href="login.php">Iniciar sesión</a></li>
        <li><a href="signup.php">Registrar nuevo usuario</a></li>
    <?php
      }
    ?>
    </ul>
  </div>
</nav>


