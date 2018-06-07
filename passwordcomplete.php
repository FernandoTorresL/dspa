<?php
  require_once('lib/appvars.php');

  // Start the session
  require_once('commonfiles/startsession.php');

  require_once('lib/connectBD.php');

  require_once('commonfiles/funciones.php');

  // Insert the page header
  $page_title = MM_APPNAME;
  require_once('lib/header.php');

  // Show the navigation menu
  require_once('lib/navmenu.php');
?>

<div class="contenedor">
<!--    <form method="post" action="/dspa_app_DES_MX/login.php">-->
      <h2>Recuperar contraseña</h2>
      <ul>
        <li>
            <p>Si existe una cuenta asociada, en breve recibirás un correo electrónico
                con las instrucciones para cambiar tu contraseña.</p>
            <br/ >
            <p>Si no recibes ningún correo electrónico, por favor verifica que el correo electrónico
                sea el que te corresponde; también revisa tu carpeta de 'Correo electrónico no deseado' o 'SPAM'.</p>
        </li>
      </ul>
<!--    </form>-->
  </div>

<?php
  // Insert the page footer
  require_once('lib/footer.php');
?>

