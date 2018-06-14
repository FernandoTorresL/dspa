<?php
  session_start();

  require_once('../lib/appvars.php');

  // Set some important CAPTCHA constants
  /*define('CAPTCHA_NUMCHARS', 6);  // number of characters in pass-phrase
  define('CAPTCHA_WIDTH', 100);   // width of image. Add more width if increase CAPTCHA_NUMCHARS
  define('CAPTCHA_HEIGHT', 30);   // height of image. Add more height if increase CAPTCHA_NUMCHARS
  define('CAPTCHA_NUMDOTS', 100);   // dots in the image. Add more height if increase CAPTCHA_NUMCHARS
  define('CAPTCHA_NUMLINES', 4);   // lines in the image. Add more height if increase CAPTCHA_NUMCHARS*/

  // Generate the random pass-phrase
  $pass_phrase = "";
  for ( $i = 0; $i < CAPTCHA_NUMCHARS; $i++ ) {
    $pass_phrase .= chr( rand( 97, 122 ) );
  }

  // Store the encrypted pass-phrase in a session variable
  $_SESSION['pass_phrase'] = SHA1($pass_phrase);

  // Create the image
  $img = imagecreatetruecolor( CAPTCHA_WIDTH, CAPTCHA_HEIGHT );

  // Set a white background with black text and gray graphics
  $bg_color       = imagecolorallocate($img, 255, 255, 255 );  // white
  $text_color     = imagecolorallocate($img, 0, 150, 136 );    // teal
  $graphic_color  = imagecolorallocate($img, 32, 182, 168 );    // teal+32
  /*$graphic_color  = imagecolorallocate($img, 0, 150, 136 );  // teal*/
  /*$graphic_color  = imagecolorallocate($img, 64, 64, 64 );   // dark gray*/

  // Fill the background
  imagefilledrectangle( $img, 0, 0, CAPTCHA_WIDTH, CAPTCHA_HEIGHT, $bg_color );
  // Draw some random lines
  for ( $i = 0; $i < CAPTCHA_NUMLINES; $i++ ) {
    imageline( $img, 0, rand() % CAPTCHA_HEIGHT, CAPTCHA_WIDTH, rand() % CAPTCHA_HEIGHT, $graphic_color );
  }

  // Sprinkle in some random dots
  for ( $i = 0; $i < CAPTCHA_NUMDOTS; $i++ ) {
    imagesetpixel( $img, rand() % CAPTCHA_WIDTH, rand() % CAPTCHA_HEIGHT, $graphic_color );
  }
// Draw the pass-phrase string
  imagettftext( $img, 25, 10, 25, CAPTCHA_HEIGHT - 5, $text_color, 'C:/xampp/htdocs/dspa_app_DES_MX/fonts/Courier.ttf', $pass_phrase );

  // Output the image as a PNG using a header
  header( "Content-type: image/png" );
  imagepng( $img );

  // Clean up
  imagedestroy( $img );
?>
