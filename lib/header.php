<!doctype html>

	<html lang="en">

		<head>
			<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
		  	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1.0"/>

			<?php
				echo '<title>' . MM_APPNAME . '</title>';
				header('Content-Type: text/html; charset=utf-8');
			?>

			<!--Import Google Icon Font-->
			<link href="http://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
		  	<!-- <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet"> -->

		  	<link type="text/css" rel="stylesheet" href="css/style.css" 			media="screen,projection"/>
			<!--Import materialize.css-->
			<!--La siguiente línea debe usarse en DESARROLLO SOLAMENTE -->
			<link href="css/materialize.css" type="text/css" rel="stylesheet" media="screen,projection"/>
			<!--La siguiente línea debe usarse en Producción para reducir tiempo de carga -->
			<!-- <link type="text/css" rel="stylesheet" href="css/materialize.min.css"  	media="screen,projection"/> -->
			

		  	<!--Let browser know website is optimized for mobile-->
      		<meta name="viewport" content="width=device-width, initial-scale=1.0"/>

		</head>

		<body>
			<!--Import jQuery before materialize.js-->
    	  	<script type="text/javascript" src="https://code.jquery.com/jquery-2.1.1.min.js"></script>
	  		<script src="http://code.jquery.com/jquery-1.11.3.min.js"></script>
	      	<script type="text/javascript" src="js/materialize.min.js"></script>


		    <script type="text/javascript">

				$(document).ready(function() {

				    $('select').material_select();
				    $('#cmbSubdelegaciones').material_select();

					/*$('.datepicker').pickadate( {
					    selectMonths: true, // Creates a dropdown to control month
					    selectYears: 15,  // Creates a dropdown of 15 years to control year
					    format: 'dd/mm/yyyy',
						formatSubmit: 'yyyy/mm/dd'
					  });*/
				  });

				$('document').ready(function() {

					$('.dropdown-button').dropdown();

			        $( '#cmbDelegaciones' ).change( 
			        	function() {
							var id = $('#cmbDelegaciones').val();
							$.get('subdelegaciones.php', { param_id:id } )
							.done( 	function( data ) {
								$( '#cmbSubdelegaciones' ).html( data );
								$('select').material_select();
							} )
						} 
					)
				} )

			</script>
