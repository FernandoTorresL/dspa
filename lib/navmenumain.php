<section id="portada" class="portada background">

    <header id="header" class="header contenedor">
        <figure class="logotipo">
            <img src="images/logo-imss-vector.png" width="75" height="60" alt="IMSS logo">
        </figure>

        <nav class="menu">
            <ul>
                <li>
                    <a href="index.php">Inicio</a>
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
                            <a href="signup.php">Registrar usuario</a>
                        </li>
                <?php
                    }
                ?>
            </ul>
        </nav>
    </header>
