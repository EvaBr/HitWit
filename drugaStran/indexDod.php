<?php
  $cnt = 0;
  $in = $_POST['numPops'];
  while($cnt < $in) {
    if (fmod($cnt,5)==0) {
      echo '<br>';
    }
    echo '<input type="number" min="0" max="1" step="any" name="popProb'.$cnt.'">';
    $cnt++;
  }
?>
