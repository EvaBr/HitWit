<?php
  $in = $_POST['indata'];


  if ($in=="manual"){
    //manually
    echo 'Please enter the number of genes your dataset contains measurements for: ';
    echo '<br> <input type="number" min="1" name="geni" id="numGen"> <br>';
    echo '<br>Please enter your measurements, separated by commas and spaces, e.g. in case of three samples: 100.3, 150.7, 110.0.';
    echo '<br> <textarea name="meritve" id="numMeas" cols="60" rows="6"></textarea><br>';
  } else {
    //with a file
    //echo 'in = '.$in.' <br>';
    echo 'File should contain a data matrix with one dimension standing for genes and the other one for samples.
          Fields have to be separated by tabs or white spaces, but not by commas. Please enter a valid path and file name:';
    echo '<br> <input type="text" name="path" id="path"> <br>';
    echo '<br>
          Does the file contain column names?<br>
                <div class="iradio">
                  <input type="radio" name="colNam" id="colNamY"><label for="colNamY"> Yes </label> &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;
                  <input type="radio" name="colNam" id="colNamN"><label for="colNamN"> No </label>
                </div><br>

          <br>
          Does the file contain row names?
                <div class="iradio">
                  <input type="radio" name="rowNam" id="rowNamY"><label for="rowNamY"> Yes </label> &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;
                  <input type="radio" name="rowNam" id="rowNamN"><label for="rowNamN"> No </label>
                </div><br>

          <br>
          Do the columns stand for different genes or different samples?</li>
                <div class="iradio">
                  <input type="radio" name="genVsam" id="genes"><label for="genes"> Genes </label> <br>
                  <input type="radio" name="genVsam" id="samples"><label for="samples"> Samples </label>
                </div>';
  }
  ?>
