<!DOCTYPE html>
<html>
	<head>

		<!--
		<link href='https://fonts.googleapis.com/css?family=Montserrat|Oleo+Script' rel='stylesheet' type='text/css'>
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.97.6/css/materialize.min.css">
		<link rel="stylesheet" type="text/css" href="stylesheet/app.css">

		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
		-->
		
		<meta charset="utf8" />
		
		<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no" />
		<link rel="stylesheet" type="text/css" href="style.css" />
		
		<?php
			echo '<title>' . $page_title . '</title>';
			header('Content-Type: text/html; charset=utf-8');
		?>
		
		<script src="http://code.jquery.com/jquery-1.11.3.min.js"></script>
	    <script type="text/javascript">
	    	$("document").ready(function() {
		        $( "#cmbDelegaciones" ).change( 
		        	function() {
						var id = $("#cmbDelegaciones").val();
						$.get('subdelegaciones.php', { param_id:id } )
						.done( 	function( data ) {
								$( "#cmbSubdelegaciones" ).html( data );
							} )
				} )
			} )
	    </script>
	</head>

	<body>

		<?php
			echo '<h3>' . $page_title . '</h3>';
		?>