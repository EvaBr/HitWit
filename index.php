<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Stochastic Profiling - ML</title>
  <link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
  <script src="//code.jquery.com/jquery-1.10.2.js"></script>
  <script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
  <script type="text/javascript" src="jquery-1.11.3-jquery.min.js"></script>
<!--  <link rel="stylesheet" href="/resources/demos/style.css"> -->
  <script type="text/javascript"
    src="https://cdn.mathjax.org/mathjax/latest/MathJax.js?config=TeX-AMS-MML_HTMLorMML">
  </script>
  <script type="text/x-mathjax-config">
    MathJax.Hub.Config({
        "HTML-CSS": { scale: 70, linebreaks: { automatic: true } },
        displayAlign: "center",
        TeX: { extensions: ["color.js"] }});
</script>
<link rel="stylesheet" type="text/css" href="custom.css">

<script>
$(document).ready(function(){
  $(function() {
    $( "#accordion" ).accordion({
        fillSpace: true,
        heightStyle: "content",
        header: "h3",
        //collapsible: true
    });
    $( $( "#accordion > h3" )[1] ).addClass( "ui-state-disabled" );
    $( $( "#accordion > h3" )[2] ).addClass( "ui-state-disabled" );
    $( $( "#accordion > h3" )[3] ).addClass( "ui-state-disabled" );
  });


  $( "#accordion" ).on( "accordionbeforeactivate", function (){
    return ! arguments[1].newHeader.hasClass( "ui-state-disabled" );
  });


  $(function() {
    $( "#radio" ).buttonset();
  });

  //function nextSection(), onclick za gumb next
  $("#next1, #next2, #next3").click(function(){
    var index = $("#accordion").accordion('option','active');
    var cannotContinue = checkFirst(index);

    if (cannotContinue && index != 0) {
      alert("Please answer all open questions before continuing!");
    } else if (!cannotContinue) {
      index++;

      $( $( "#accordion > h3" )[index] ).removeClass( "ui-state-disabled" );
      $("#accordion").accordion("option", "active", index);
      $( $( "#accordion > h3" )[index-1] ).addClass( "ui-state-disabled" );
    }
  });

  // By clicking Next 2 button, send the textarea to checkStrings.php
//  $("#next1").click(function(){ // When you click on button #next1...


  //function prevSection(), onclick za gumb previous
  $("#prev2, #prev3, #prev4").on('click',function(){
    var index = $("#accordion").accordion('option', 'active');
    //alert('By returning to section ' + index + ', all progress in current section will be lost!');
    index--;

    $( $( "#accordion > h3" )[index] ).removeClass( "ui-state-disabled" );
    $("#accordion").accordion("option", "active", index);
    $( $( "#accordion > h3" )[index+1] ).addClass( "ui-state-disabled" );
  });

//  function saveCheckData() {
//        var vv =  $('input:radio[name="model"]:checked').val();
//        $('#second').attr('data-enabled', 'true');
//  }
//  $(document).on("click", 'input:radio[name="colNam"]', function(){
//        $('#fourth').attr('data-enabled', 'true');
//  });
});

function checkFirst(kder){ //returns true, if some data is missing. in that case, user is to be alerted.
  //document.getElementById('id').value;
  var result = 0;
  switch(kder) {
    case 0:
      var wayofinp = $("input[name=inp]:checked").val();
      if (wayofinp == "manual") {
        //var numGenes = document.getElementById('numGen').value;
        //var measures = document.getElementById('numMeas').value;
        var meritve = document.getElementById('numMeas').value; // capture the #meritve textfield
        result = (document.getElementById('numGen').value && meritve);   //(!(numGenes && measures));

        if (!result){
          alert("Please answer all open questions before continuing!");
        } else {
          $.ajax({ /* And send the info var to checkstrings.php */
              type: "POST",
              url: 'checkStrings.php',
              async: false,
              data: {meritve: meritve},
              success: function (msg) {
                  // Show data from php file.
                  var result_ajax = JSON.parse(msg);
                  //$("#submit_return").html(result_ajax);
                  if (result_ajax == 0){
                    alert("You did not follow the suggested pattern for measurements or you have entered a non-numeric character. Please correct that and resubmit.");
                    result = 0;
                    //$("#submit_return").html(result);
                  }
              }
            });
        }
      } else if (wayofinp == "file") {
        var filepath = document.getElementById('path').value;
        var colNames = $("input[name=colNam]:checked").val();
        var rowNames = $("input[name=rowNam]:checked").val();
        var genOrSam = $("input[name=genVsam]:checked").val();
        result = filepath && colNames && rowNames && genOrSam;

        if (!result){
          alert("Please answer all open questions before continuing!");
        }

      } else {
        result = 0;
        alert("Please choose means of data input!");
      }
      break;
    case 1:
      result = $("input[name=model]:checked").val();
      break;
    case 2:
      result = document.getElementById('populations').value;
      break;
    /*case 3:    THIS IS FOR THE SUBMIT BUTTON! AND THUS DONE IN ONSUBMISSION();
      result = document.getElementById('cellspersample').value;
      break;*/
    default:
      break;
  };
  return (!result);
};


function dataInput() {
//      $.post('file.php',{ indata: $('input:radio[name='input']:checked').val() }, function() {
//          $("#podatki").php();
//        });
//      }
        //var vv =  "radio1"; //$("input[name='inp']", '#radijo').val();
        var request = $.ajax({
          url: 'file.php',
          type: "POST",
          data: ({indata:  $('input:radio[name="inp"]:checked').val()}), //({indata: vv })
          dataType: "html"
        });

        request.done(function(msg) {
          $("#podatki").html(msg);
        });

        request.fail(function(jqXHR, textStatus) {
          alert( "Request failed: " + textStatus );
        });
};

//  function postandnext() {
    //post-ano je ze, preveri da je vse in potegni dol ter shrani v bazo. daj alert da bodo rezultati programa poslani na mail (al kaj?).
//    alert('Program is now running on submitted data. When finished, the results will be sent to your email.');
//    return 1;
//  }

</script>

<!-- Intermediate file upload button -->


<!-- FINAL SUBMIT button -->
<script>
      $(function () {

        $('form').on('submit', function (e) {

          e.preventDefault();

          if (!(document.getElementById('cellspersample').value && document.getElementById('email').value) ) {
            alert('Please answer all open questions before submitting.');
          } else {

          // Prepare additional data to be sent.
          var indata = $('input:radio[name="inp"]:checked').val();
          if ($('input:radio[name="inp"]:checked').val() == 'file'){ // Only add these vars if you use file method.
            var filename_sql = document.getElementById('filename_sql').value;
            var path = document.getElementById('path').value;
          }

          // Append additional data to be sent.
          var formData = $('form').serializeArray();
          formData.push({ name: 'filename_sql', value: filename_sql });
          formData.push({ name: 'path', value: path })
          formData.push({ name: 'indata', value: indata })

          $.ajax({
            type: 'post',
            url: 'writeToDb.php',
            data: formData,
            dataType: 'html',
            success: function (msg) {
              //alert('form was submitted');
              $( "form" ).fadeOut( "slow", function(){
                $("#submit_return").html(msg); // Show data from php file.
                $("#helmholtz_logo").fadeIn();
              });
            }
          });
        };

        });

      });
</script>

</head>
<body>

<h1 class="naslov"> Stochastic Profiling </h1>
<p class="uvod">
Even when appearing perfectly homogeneous on a morphological basis, tissues can be
substantially heterogeneous in single-cell molecular expression. As such heterogeneities
might govern the regulation of cell fate, one is interested in quantifying them in a given
tissue. In this project, we infer single-cell regulatory states from expression measurements
taken from small groups of cells. This averaging-and-deconvolution approach allows to
quantify single-cell regulatory heterogeneities while avoiding the measurement noise of
global single-cell techniques. <br>

This webtool allows you to generate synthetic data from our stochastic profiling models
as well as analyze your own data files. It serves as an interface to the R package
<em><a class="urls" href="https://cran.r-project.org/web/packages/stochprofML/index.html">stochprofML</a></em>.

For more details, please visit the <a class="urls" href="stochprofdata.php">Stochastic Profiling Webpage</a>.
For questions and suggestions, please contact <a class="urls" href="https://www.helmholtz-muenchen.de/icb/institute/staff/staff/ma/2448/index.html" >Christiane Fuchs.</a></p>
<div name="hula" value="1" />
<div class="wrapper">
<form name="mainform"> <!--onsubmit="return postandnext()"-->
<div id="accordion">
  <h3>1. Data Input</h3>
  <div>
    <p>
      How would you like to input your data? <br>
      <!--<form id="radijo"> -->
        <div id="radio">
          <input type="radio" id="radio1" name="inp" value="manual" onclick="dataInput();"><label for="radio1"> Enter manually </label>  <!--onclick="dataInput();"-->
          <input type="radio" id="radio2" name="inp" value="file"   onclick="dataInput();"><label for="radio2"> Read from file </label>
        </div> <br>
        <div id="podatki"> </div> <br>
      <!--</form>
      <form id ="sub" name="subm" method="post" onsubmit="return postandnext()">-->
        <button type="button" id="next1" style="float: right;" nextSec="second"> Next </button><br>
      <!--/form-->
    </p>
  </div>
  <h3>2. Choice of a Model</h3>
<div>
  <p><div class = "modeli">
  There are three stochastic profiling models: The
     <em>lognormal-lognormal (LN-LN)</em> model assumes that each cell is from a
     mixture of one or more lognormal distributions with different
     log-means but identical log-standard deviations. In the <em>relaxed
     lognormal-lognormal (rLN-LN)</em> model, the log-standard deviations
     are not necessarily identical. The <em>exponential-lognormal (EXP-LN)</em>
     model considers the mixture of zero, one or more lognormal
     distributions and one exponential distribution. The graphics and formulas
     below cover the case of two populations.<br>
</div> <br>
  Please choose the model you would like to estimate:
    <div class="iradioModel">
        <label for="lnln">
          <table>
            <tr><td> LN-LN </td></tr>
            <tr><td class="modelbox"> \(\definecolor{yellow}{RGB}{255, 210, 0} \definecolor{blue}{RGB}{0, 0, 255}
                                      X \sim \begin{array}{ll} LN(\mu_1, \sigma^2), & \text{if cell is of }\color{yellow} \text{type I} \\
                                                                        Exp(\lambda), & \text{if cell is of }\color{blue} \text{type II} \end{array}\)<br>
                  <img src="lnln.png" height="200" width="200" value="LN-LN"/></td></tr>
          </table>
          <input type="radio" name="model" value="lnln" id="lnln">
        </label>

        <label for="rlnln">
          <table>
            <tr><td> rLN-LN </td></tr>
            <tr><td class="modelbox">\(\definecolor{yellow}{RGB}{255, 210, 0} \definecolor{blue}{RGB}{0, 0, 255}
                                      X \sim \begin{array}{ll} LN(\mu_1, \sigma_1^2), & \text{if cell is of }\color{yellow} \text{type I} \\
                                                                 LN(\mu_2, \sigma_2^2), & \text{if cell is of }\color{blue} \text{type II} \end{array}\)<br>

                  <img src="rlnln.png" height="200" width="200" value="rLN-LN"/></td></tr>
          </table>
          <input type="radio" name="model" id="rlnln" value="rlnln">
        </label>

        <label for="expln">
          <table>
            <tr><td> Exp-LN </td></tr>
            <tr><td class="modelbox">\(\definecolor{yellow}{RGB}{255, 210, 0} \definecolor{blue}{RGB}{0, 0, 255}
                                      X \sim \begin{array}{ll} LN(\mu, \sigma^2), & \text{if cell is of }\color{yellow} \text{type I} \\
                                                                      Exp(\lambda), & \text{if cell is of }\color{blue} \text{type II} \end{array}\)<br>
                  <img src="expln.png" height="200" width="200" value="Exp-LN" /></td></tr>
          </table>
          <input type="radio" name="model" id="expln" value="expln">
        </label>
    </div> <br>
    <button type="button" id="next2" style="float: right;"> Next </button>
    <button type="button" id="prev2" style="float: right;"> Previous </button><br>
  </p>
</div>
<h3>3. Number of Populations</h3>
<div>
  <p>
    Please enter the number of different populations you would like to estimate:
    <br> <input type="number" name="populations" min="1" id="populations" > <br><br>
    <button type="button" id="next3" style="float: right;"> Next </button>
    <button type="button" id="prev3" style="float: right;"> Previous </button><br>
  </p>
</div>
<h3>4. Cells per Sample</h3>
<div>
  <p>
    Please enter the number of cells that entered each sample:
    <br> <input type="number" min="1" name="cellspersample" id="cellspersample"> <br><br>
    Please enter your e-mail address, to which you would like the results to be sent:
    <br> <input type="email" id="email" name="email"><br><br>
    <input type="submit" name="main_submit" style="float: right;" value="Submit">
    <button type="button" id="prev4" style="float: right;"> Previous </button>
    <br>
  </p>
</div>
</div>
<!-- File's unique ID to write to SQL --> <div id="filename_sql" name="filename_sql" style="display: none;"></div>
</form>
</div>
<br><br><br>
<div style="display: none; margin-left: 2.5em; width: 67%;" id="helmholtz_logo"><img src="helmholtz_logo.png"></div>
<div id="submit_return"></div>
</body>
</html>
