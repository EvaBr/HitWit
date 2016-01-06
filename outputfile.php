<?php
  $outf = $_POST['out'];
  if ($outf == "yes") {
    echo 'Please enter a preffered file name, without suffix: <br>';
    echo '<input type="text" id="filename" name="filename">';
  } /*else {
    echo ' ';
  }*/
//  echo '<br>After submission, the generated data will be sent to your e-mail.';
?>
