<?php
	
	header('Content-Type: text/html; charset=iso-8859-1'); // Para que devuelva correctamente los acentos de los registros

	require_once('appvars.php');
	require_once('connectvars.php');


	$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
	$result = mysqli_query($dbc, "SELECT * FROM movimientos ORDER BY 1 ASC");

	echo '<option value="0">Seleccione Tipo de movimiento</option>';	

	while ($row = mysqli_fetch_array($result)) {
		echo '<option value="' . $row['id_movimiento'] . '">' . $row['descripcion'] . '</option>';
	}
	