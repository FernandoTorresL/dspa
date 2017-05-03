<?php
	
	header('Content-Type: text/html; charset=iso-8859-1'); // Para que devuelva correctamente los acentos de los registros

	require_once('appvars.php');
	require_once('connectvars.php');


	$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
	$result = mysqli_query($dbc, "SELECT * FROM grupos ORDER BY descripcion ASC");

	echo '<option value="0">Seleccione Grupo</option>';	

	while ($row = mysqli_fetch_array($result)) {
		echo '<option value="' . $row['id_grupo'] . '">' . $row['descripcion'] . '</option>';
	}
	