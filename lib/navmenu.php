<header id="header" class="header background">
  <div class="contenedoramplio">
    <div class="columna01">
      <figure class="logotipo02">
        <img src="images/logo-imss-vector.png" width="75" height="60" alt="IMSS logo">
      </figure>
      <h2 class="tituloh2">Aplicaciones DSPA</h2>
      <!-- logotipo+titulo -->
    </div>

    <div class="columna02">
      <nav class="menu">
        <ul>
          <li>
            <a href="index.html">Inicio</a>
          </li>

          <?php //Si ya ha iniciado sesión ...
            /*if (isset($_SESSION['username'])) */
            if ( isset($_SESSION['username']) && !empty($_SESSION['username']) ) 
            {
          ?>
              <li>
                <a href="logout.php">Cerrar Sesión ( <?php echo $_SESSION['username'] ?> ) </a>
              </li>
          <?php
            }
            else 
            {
          ?>
              <li>
                <a href="login.php">Iniciar sesión</a>
              </li>
              <li>
                <a href="signup.php">Registrar nuevo usuario</a>
              </li>
          <?php
            }
          ?>
        </ul>

      </nav>
      <!-- Menú -->
    </div>
</header>
