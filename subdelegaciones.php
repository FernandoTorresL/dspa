<?php

	header('Content-Type: text/html; charset=iso-8859-1'); // Para que devuelva correctamente los acentos de los registros
	
	require_once('appvars.php');
	require_once('connectvars.php');

	function subdelegacionSelected( $var_subdelegacion )
	{
		if ( empty( $_POST['cmbSubdelegacionesII'] ) ) {
			return "";
		}
		else if ( $_POST['cmbSubdelegacionesII'] == $var_subdelegacion) {
			return "selected";
		}
	}

	$id_delegacion = $_GET['param_id'];

	$dbc = mysqli_connect( DB_HOST, DB_USER, DB_PASSWORD, DB_NAME );

	$result = "SELECT * FROM subdelegaciones 
									WHERE delegacion = $id_delegacion
									ORDER BY subdelegacion";
	//echo "subdelegaciones3.php: ";
	//echo $result;
	$result = mysqli_query( $dbc, "SELECT * FROM subdelegaciones 
									WHERE delegacion = $id_delegacion
									ORDER BY subdelegacion");

	while( $row = mysqli_fetch_array( $result ) ) {
		echo '<option value="' . $row['subdelegacion'] . '" ' . subdelegacionSelected( $row['subdelegacion'] ) . '>' . $row['subdelegacion'] . ' - ' . $row['descripcion'] . '</option>';
	}

