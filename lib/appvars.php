<?php
  // Define application constants
  define('MM_APPNAME', 'Aplicaciones DSPA');

  define('MM_UPLOADPATH_PROFILE', '../imagesprofile/');
  
  //define('MM_MAXFILESIZE_PROFILE', 32768);      // 32 Kb | 32,768 bytes
  define('MM_MAXFILESIZE_PROFILE', 4194304);      // 32 Kb | 32,768 bytes

  //define('MM_MAXIMGWIDTH_PROFILE', 120);        // 120 pixels
  //define('MM_MAXIMGHEIGHT_PROFILE', 120);       // 120 pixels
  define('MM_MAXIMGWIDTH_PROFILE', 12000);        // 12000 pixels
  define('MM_MAXIMGHEIGHT_PROFILE', 12000);       // 12000 pixels

  /*define('MM_EXPIRE_COOKIE_VAL', (60 * 60 ) );      // expires in 1 hour*/
  //define('MM_EXPIRE_COOKIE_VAL', (60 * 1));      // expires in 1 MINUTE
  define('MM_EXPIRE_COOKIE_VAL', ( 60 * 10 ) );      // expires in 10 MINUTES

  //Variables del Captcha
  define('CAPTCHA_NUMCHARS', 6);  // number of characters in pass-phrase
  define('CAPTCHA_WIDTH', 150);   // width of image. Add more width if increase CAPTCHA_NUMCHARS
  define('CAPTCHA_HEIGHT', 45);   // height of image. Add more height if increase CAPTCHA_NUMCHARS
  define('CAPTCHA_NUMDOTS', 300);   // dots in the image. Add more height if increase CAPTCHA_NUMCHARS
  define('CAPTCHA_NUMLINES', 8);   // lines in the image. Add more height if increase CAPTCHA_NUMCHARS
?>
