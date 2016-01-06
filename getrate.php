<?php
  $model = $_POST['mod'];

  if ($model=="expln") {
    echo 'Please enter the rate of the exponential distribution for the exponential population:<br>';
    echo '<input type="number" step="0.001" in="rate" name="rate" >';
  }

 ?>
