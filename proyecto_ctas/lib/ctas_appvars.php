<?php
  // Define application constants
	define('MM_APPNAME', 'Aplicaciones DSPA');

	// Conectarse a la BD
	//define('MM_DBC', mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
    //$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

	define('MM_UPLOADPATH_VAL', '../files/');
	define('MM_UPLOADPATH_CTASSINDO', '../filesctasSINDO/');
	define('MM_UPLOADPATH_PROFILE', '../imagesprofile/');
	// define('MM_UPLOADPATH_IMG', 'images/');

	define('MM_MAXFILESIZE_VAL', 512000000);      // 500 Mb, 500,000 Kb
	define('MM_MAXFILESIZE_VALIJA', 4194304);      // 4 Mb | 4,096 Kb | 4,194,304 bytes
	//Watch out for the variable upload_max_filesize in php.ini config file
	
	//define('MM_MAXFILESIZE_PROFILE', 32768);      // 32 Kb | 32,768 bytes
	define('MM_MAXFILESIZE_PROFILE', 4194304);      // 32 Kb | 32,768 bytes
	//define('MM_MAXIMGWIDTH_PROFILE', 120);        // 120 pixels
	//define('MM_MAXIMGHEIGHT_PROFILE', 120);       // 120 pixels

	define('MM_MAXIMGWIDTH_PROFILE', 12000);        // 12000 pixels
	define('MM_MAXIMGHEIGHT_PROFILE', 12000);       // 12000 pixels

	/*define('MM_EXPIRE_COOKIE_VAL', (60 * 60 ) );      // expires in 1 hour*/
	//define('MM_EXPIRE_COOKIE_VAL', (60 * 1));      // expires in 1 MINUTE
	define('MM_EXPIRE_COOKIE_VAL', ( 60 * 10 ) );      // expires in 10 MINUTES

  	// define('MM_FILE', '100registros.txt');       // Nombre de archivo
  //define('MM_FILE', 'NOT_ACR_2015_05_02.TXT');       // Nombre de archivo
	//define('MM_CANTIDADREGISTROSESTUDIANTES', 50);      // 4 Mb | 4,096 Kb | 4,194,304 bytes

	define('MM_MAXVALIJAS', 500); //Define el número de valijas a mostrar en el combo en agregarsolicitud.php

	//Variables del Captcha
	define('CAPTCHA_NUMCHARS', 6);  // number of characters in pass-phrase
	define('CAPTCHA_WIDTH', 150);   // width of image. Add more width if increase CAPTCHA_NUMCHARS
	define('CAPTCHA_HEIGHT', 45);   // height of image. Add more height if increase CAPTCHA_NUMCHARS
	define('CAPTCHA_NUMDOTS', 300);   // dots in the image. Add more height if increase CAPTCHA_NUMCHARS
	define('CAPTCHA_NUMLINES', 8);   // lines in the image. Add more height if increase CAPTCHA_NUMCHARS
?>
