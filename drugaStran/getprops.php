<?php
  $model = $_POST['mod'];
  $numberpop = $_POST['pops'];
  $which = $_POST['req'];
  $cnt = 0;

  $numbermean = $numberpop;
  $numberstd = $numberpop;
  if ($model=="expln") {
    $numbermean--;
    $numberstd--;
  } elseif ($model=="lnln") {
    $numberstd = 1;
  }

  switch ($which) {
    case 'std':
      while($cnt < $numberstd) {
        if (fmod($cnt,5)==0) {
          echo '<br>';
        }
        echo '<input type="number" step="0.001" min="0" name="stdDev'.$cnt.'" id="stdDev'.$cnt.'">';
        $cnt++;
      }
      break;
    case 'logm':
      while($cnt < $numbermean) {
        if (fmod($cnt,5)==0) {
          echo '<br>';
        }
        echo '<input type="number" step="0.001" min="0" name="logMean'.$cnt.'" id="logMean'.$cnt.'">';
        $cnt++;
      }
      break;
    case 'prob':
      while($cnt < $numberpop) {
        if (fmod($cnt,5)==0) {
          echo '<br>';
        }
        echo '<input type="number" min="0.001" max="1" step="0.001" name="popProb'.$cnt.'" id="popProb'.$cnt.'">';
        $cnt++;
      }
      break;
    default:
      break;
  }
 ?>
