<?php

	session_start();
	// If the session vars aren't set, try to set them with a cookie
	if ( !isset( $_SESSION[MM_APPVER . 'user_id'] ) ) {

	    if ( 
	    	isset( $_COOKIE[MM_APPVER . 'user_id'] ) && 
	    	isset( $_COOKIE[MM_APPVER . 'username'] ) && 
	    	isset( $_COOKIE[MM_APPVER . 'nombre'] ) && 	
	    	isset( $_COOKIE[MM_APPVER . 'primer_apellido'] ) && 
	    	isset( $_COOKIE[MM_APPVER . 'ip_address'] ) && 
	    	isset( $_COOKIE[MM_APPVER . 'host'] )
	    	     	) {
	    	
	      $_SESSION[MM_APPVER . 'user_id'] 			= $_COOKIE[MM_APPVER . 'user_id'];
	      $_SESSION[MM_APPVER . 'username'] 		= $_COOKIE[MM_APPVER . 'username'];
	      $_SESSION[MM_APPVER . 'nombre'] 			= $_COOKIE[MM_APPVER . 'nombre'];	
	      $_SESSION[MM_APPVER . 'primer_apellido'] 	= $_COOKIE[MM_APPVER . 'primer_apellido'];
	      $_SESSION[MM_APPVER . 'ip_address']       = $_COOKIE[MM_APPVER . 'ip_address'];
          $_SESSION[MM_APPVER . 'host']             = $_COOKIE[MM_APPVER . 'host'];
	    }
	    
	}
?>

