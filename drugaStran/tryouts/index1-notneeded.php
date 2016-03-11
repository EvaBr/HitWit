<!DOCTYPE html>
<html>
<head>
  <title>Stochastic Profiling - Data</title>

  <script type="text/javascript">
      function changeClass(current){
        //var current = one to close
        var next = current + "1";
        document.getElementById(current).className = "closed";
        document.getElementById(next).className = "open";

        //if current= l11111 .... potem kličeš spodnjo fun.

      }

      $(document).on("change", 'input[name="numPop"]', function() {
        var request = $.ajax({
          url: 'indexDod.php',
          type: "POST",
          data: ({numPops:  $('input[name="numPop"]')}),
          dataType: "html"
        });

        request.done(function(msg) {
          $("#popProbs").html(msg);
        });

        request.fail(function(jqXHR, textStatus) {
          alert( "Request failed: " + textStatus );
        });
      });

      function showit() {
        var request = $.ajax({
          url: 'indexDod.php',
          type: "POST",
          data: ({numPops:  $('input[name="numPop"]')}),
          dataType: "html"
        });

        request.done(function(msg) {
          $("#popProbs").html(msg);
        });

        request.fail(function(jqXHR, textStatus) {
          alert( "Request failed: " + textStatus );
        });
      }

  </script>

  <link rel="stylesheet" type="text/css" href="custom1.css">

</head>

<body>
  <h1>Stochastic Profiling - Generating Data</h1>
  <!-- <h4></h4> -->

  <ol>
    <li class="open" id="l1"> Please choose the model you would like to generate data from:
      <!--  TODO: DODAJ SLIKE IN ENACBE-->
      <table>
        <tr>
          <td> <img src="lnln.png" height="250"> </td>
          <td> <img src="rlnln.png" height="250"> </td>
          <td> <img src="expln.png" height="250"> </td>
          <td> </td>
        </tr>
        <tr>
          <form action="index1.php" method="post">
            <td> <input type="radio" name="model" value="LN-LN" onclick="changeClass('l1');"> LN(&mu;<sub>1</sub>,&sigma;) - LN(&mu;<sub>2</sub>,&sigma;) </td>
            <td> <input type="radio" name="model" value="rLN-LN" onclick="changeClass('l1');"> LN(&mu;<sub>1</sub>,&sigma;<sub>1</sub>) - LN(&mu;<sub>2</sub>,&sigma;<sub>2</sub>) </td>
            <td> <input type="radio" name="model" value="Exp-LN" onclick="changeClass('l1');"> Exp(&lambda;) - LN(&mu;,&sigma;) </td>
            <td> <button type="submit" id="tip" onclick="showit();"> Next </button> </td>
          </form>
        </tr>
      </table>
    </li>

    <br>
    <li class="closed" id="l11"> Please enter the number of different populations you would like to consider:
      <form action="index1.php" method="post">
        <input type="number" onchange="changeClass('l11');" min="1" name="numPop"><br>
      </form>
    </li>

    <br>
    <li class="closed" id="l111"> Please enter the number of stochastic profiling samples you wish to generate:
      <input type="number" onchange="changeClass('l111');" min="1" name="profSam"><br>
    </li>

    <br>
    <li class="closed" id="l1111"> Please enter the number of cells that should enter each sample:
      <input type="number" onchange="changeClass('l1111');" min="1" name="numCells"><br>
    </li>

    <br>
    <li class="closed" id="l11111"> Please enter the number of co-expressed genes you would like to collect in one cluster:
      <input type="number" onchange="changeClass('l11111');" min="1" name="numGen"><br>
    </li>

    <br>
    <li class="closed" id="l111111"> Please enter the probability of each population:
      <!--(FORMULA) TODO: all inputs have to add up to one!! -->
    <div id="popProbs">
    <!--  <?php
        $cnt = 0;
        $in = $_POST['numPops'];
        while($cnt < $in) {
          if (fmod($cnt,5)==0) {
            echo '<br>';
          }
          echo '<input type="number" min="0" max="1" step="any" name="popProb'.$cnt.'">';
          $cnt++;
        }
      ?> -->
    </div>
    </li>

    <br>
    <li class="closed" id="l1111111"> Please enter the log-means for each of the lognormal populations:
      <?php
        $cnt = 0;
        $N = $_POST['numPop'];
        $mod = $_POST['model'];
        if ($mod=="Exp-LN") {
          $N--;
        }
        while($cnt < $N) {
          if (fmod($cnt,5)==0) {
            echo '<br>';
          }
          echo '<input type="number" step="any" name="logMean'.$cnt.'">';
          $cnt++;
        }
      ?>
    </li>


    <br>
    <li class="closed" id="l11111111"> Please enter the log-standard deviations for each population:
      <?php
        $cnt = 0;
        $N = $_POST['numPop'];
        $mod = $_POST['model'];
        if ($mod=="Exp-LN") {
          $N--;
        } elseif ($mod=="LN-LN") { $N = 1; }
        while($cnt < $N) {
          if (fmod($cnt,5)==0) {
            echo '<br>';
          }
          echo '<input type="number" step="any" name="logDev'.$cnt.'">';
          $cnt++;
        }
      ?>
    </li>


    <!-- samo, ce je exp-ln model-->
    <?php
    $model = $_POST['model'];

    switch ($model) {
        case "Exp-LN":
            echo '<br><li class="closed" id="l111111111"> Please enter rate of the exponential distribution of the exponential population:
                  <input type="number" step="any" name="expRate"><br> </li>';
            break;
        default:
            break;
    }
    ?>

    <br>
    <li class="closed" id="l1111111111"> Would you like to write the generated dataset to a file?
      <form action="index1.php" method="post">
        <input type="radio" name="yesno" value="Yes"> Yes
        <input type="radio" name="yesno" value="No"> No
        <button type="submit" id="fileout"> Next </button>
      </form>
    </li>

    <?php
    $data = $_POST['yesno'];
    switch ($data) {
        case "Yes":
            echo '<script> changeClass("l1111111111"); </script>
                  <br><li class="open" id="l11111111111"> Please enter name:
                  <input type="text" name="nameOfFile"><br> </li>';
            break;
        default:
            break;
    }
    ?>

  </ol>
  <br>
  <br>

</body>
</html>
