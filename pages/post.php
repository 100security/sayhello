<?php
$date = date('dMYHis');
$imageData=$_POST['cat'];

if (!empty($imageData)) {
  $filteredData=substr($imageData, strpos($imageData, ",")+1);
  $unencodedData=base64_decode($filteredData);
  $fp = fopen( '../logs/log.log', 'wb' );
  fwrite( $fp, $unencodedData);
  fclose( $fp );
}
exit();
?>
