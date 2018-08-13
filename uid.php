<?php
function generateUUID($length) {
  $random = '';
  for ($i = 0; $i < $length; $i++) {
    $random .= rand(0, 1) ? rand(0, 9) : chr(rand(ord('a'), ord('z')));
  }
  return $random;
}
echo generateUUID(7);
?>