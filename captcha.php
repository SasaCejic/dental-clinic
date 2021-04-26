<?php
// Adapted for The Art of Web: www.the-art-of-web.com
  // Please acknowledge use of this code by including this header.

  // initialise image with dimensions of 120 x 30 pixels
  $slika = @imagecreatetruecolor(120, 30) or die("Greska pri pravljenju slike");

  // set background to white and allocate drawing colours
  $pozadina = imagecolorallocate($slika, 0xFF, 0xFF, 0xFF);
  imagefill($slika, 0, 0, $pozadina);
  $bojaLinija = imagecolorallocate($slika, 0xCC, 0xCC, 0xCC);
  $bojaTeksta = imagecolorallocate($slika, 0x33, 0x33, 0x33);

  // draw random lines on canvas
  for($i=0; $i < 6; $i++) {
    imagesetthickness($slika, rand(1,3));
    imageline($slika, 0, rand(0,30), 120, rand(0,30), $bojaLinija);
  }

  session_start();

  // add random digits to canvas
  $nasumicanBroj = '';
  for($x = 15; $x <= 95; $x += 20) {
    $nasumicanBroj .= ($broj = rand(0, 9));
    imagechar($slika, rand(3, 5), $x, rand(2, 14), $broj, $bojaTeksta);
  }

  // record digits in session variable
  $_SESSION['nasumicanBroj'] = $nasumicanBroj;

  // display image and clean up
  header('Content-type: image/png');
  imagepng($slika);
  imagedestroy($slika);
?>