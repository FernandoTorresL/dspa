<?php

	session_start();
	// If the session vars aren't set, try to set them with a cookie
	if ( !isset( $_SESSION['user_id'] ) ) {

	    if ( 
	    	isset( $_COOKIE['user_id'] ) && 
	    	isset( $_COOKIE['username'] ) && 
	    	isset( $_COOKIE['nombre'] ) && 	
	    	isset( $_COOKIE['primer_apellido'] ) && 
	    	isset( $_COOKIE['ip_address'] ) && 
	    	isset( $_COOKIE['host'] )
	    	     	) {
	    	
	      $_SESSION['user_id'] 			= $_COOKIE['user_id'];
	      $_SESSION['username'] 		= $_COOKIE['username'];
	      $_SESSION['nombre'] 			= $_COOKIE['nombre'];	
	      $_SESSION['primer_apellido'] 	= $_COOKIE['primer_apellido'];
	      $_SESSION['ip_address']       = $_COOKIE['ip_address'];
          $_SESSION['host']             = $_COOKIE['host'];
          $_SESSION['pass_phrase']		= 0;
	    }
	    
	}
?>

