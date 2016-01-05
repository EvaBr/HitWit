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
    index++;

    $( $( "#accordion > h3" )[index] ).removeClass( "ui-state-disabled" );
    $("#accordion").accordion("option", "active", index);
    $( $( "#accordion > h3" )[index-1] ).addClass( "ui-state-disabled" );
  });


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

<script>
      $(function () {

        $('form').on('submit', function (e) {

          e.preventDefault();

          if (!document.getElementById('cellspersample').value) {
            alert('Please answer all open questions before submitting.');
          } else {

          $.ajax({
            type: 'post',
            url: 'writeToDb.php',
            data: $('form').serialize(),
            success: function (msg) {
              alert('form was submitted');
              $("#submit_return").html(msg); // Show data from php file.
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
<em>stochprofML</em>.

For more details, please visit the <a href="index_stochprof.php">Stochastic Profiling Webpage</a>.
For questions and suggestions, please contact Christiane Fuchs (link my email here, but has to be encrypted, otherwise link to my webpage).</p>

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
            <tr><td class="modelbox"> \(\definecolor{yellow}{RGB}{255, 255, 0} \definecolor{blue}{RGB}{0, 0, 255}
                                      X \sim \begin{array}{ll} LN(\mu_1, \sigma^2), & \text{if cell is of }\color{yellow} \text{type I} \\
                                                                        Exp(\lambda), & \text{if cell is of }\color{blue} \text{type II} \end{array}\)<br>
                  <img src="lnln.png" height="200" width="200" value="LN-LN"/></td></tr>
          </table>
          <input type="radio" name="model" value="lnln" id="lnln">
        </label>

        <label for="rlnln">
          <table>
            <tr><td> rLN-LN </td></tr>
            <tr><td class="modelbox">\(\definecolor{yellow}{RGB}{255, 255, 0} \definecolor{blue}{RGB}{0, 0, 255}
                                      X \sim \begin{array}{ll} LN(\mu_1, \sigma_1^2), & \text{if cell is of }\color{yellow} \text{type I} \\
                                                                 LN(\mu_2, \sigma_2^2), & \text{if cell is of }\color{blue} \text{type II} \end{array}\)<br>

                  <img src="rlnln.png" height="200" width="200" value="rLN-LN"/></td></tr>
          </table>
          <input type="radio" name="model" id="rlnln" value="rlnln">
        </label>

        <label for="expln">
          <table>
            <tr><td> Exp-LN </td></tr>
            <tr><td class="modelbox">\(\definecolor{yellow}{RGB}{255, 255, 0} \definecolor{blue}{RGB}{0, 0, 255}
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
    <br> <input type="text" name="cellspersample" id="cellspersample"> <br><br>
    <input type="submit" name="main_submit" style="float: right;" value="Submit">
    <button type="button" id="prev4" style="float: right;"> Previous </button>
    <br>
  </p>
</div>
</div>
</form>
</div>
<br><br><br>
<div id="submit_return"> </div> <br>
</body>
</html>
