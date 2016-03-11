<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Stochastic Profiling - Data</title>
  <link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
  <script src="//code.jquery.com/jquery-1.10.2.js"></script>
  <script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>

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
<link rel="stylesheet" type="text/css" href="custom2.css">


<script>
$(document).ready(function(){
  $(function() {
    $( "#accordion" ).accordion({
        fillSpace: true,
        heightStyle: "content",
        header: "h3",
    });
    $( $( "#accordion > h3" )[1 ] ).addClass( "ui-state-disabled" );
    $( $( "#accordion > h3" )[2 ] ).addClass( "ui-state-disabled" );
    $( $( "#accordion > h3" )[3 ] ).addClass( "ui-state-disabled" );
    $( $( "#accordion > h3" )[4 ] ).addClass( "ui-state-disabled" );
    $( $( "#accordion > h3" )[5 ] ).addClass( "ui-state-disabled" );
    $( $( "#accordion > h3" )[6 ] ).addClass( "ui-state-disabled" );
    $( $( "#accordion > h3" )[7 ] ).addClass( "ui-state-disabled" );
    $( $( "#accordion > h3" )[8 ] ).addClass( "ui-state-disabled" );
  });


  $( "#accordion" ).on( "accordionbeforeactivate", function (){
    return ! arguments[1].newHeader.hasClass( "ui-state-disabled" );
  });


  $(function() {
    $( "#radio" ).buttonset();
  });


  //function nextSection(), onclick za gumb next
  $("#next1, #next2, #next3, #next4, #next5, #next6, #next7, #next8").click(function(){
    var index = $("#accordion").accordion('option','active');
    var cannotContinue = checkFirst(index);

    if (cannotContinue) {
      alert("Please answer all open questions properly before continuing!");
    } else {
      index++;
      if (index==2) {
        popProperties();
      }

      $( $( "#accordion > h3" )[index] ).removeClass( "ui-state-disabled" );
      $("#accordion").accordion("option", "active", index);
      $( $( "#accordion > h3" )[index-1] ).addClass( "ui-state-disabled" );

    }
  });


  //function prevSection(), onclick za gumb previous
  $("#prev2, #prev3, #prev4, #prev5, #prev6, #prev7, #prev8, #prev9").on('click',function(){
    var index = $("#accordion").accordion('option', 'active');
    index--;
    if (index==1) {
      alert("If you return to the previous section, current progress might be lost!");
    }
    $( $( "#accordion > h3" )[index] ).removeClass( "ui-state-disabled" );
    $("#accordion").accordion("option", "active", index);
    $( $( "#accordion > h3" )[index+1] ).addClass( "ui-state-disabled" );
  });
});

function checkFirst(kder){ //returns true, if some data is missing. in that case, user is to be alerted.
  var result = 1;
  switch(kder) {
    case 0:
      result = $("input[name=model]:checked").val();
      break;
    case 1:
      result = document.getElementById('populations').value;
      break;
    case 2:
      result = document.getElementById('samples').value;
      break;
    case 3:
      result = document.getElementById('cellspersample').value;
      break;
    case 4:
      result = document.getElementById('cluster').value;
      break;
    case 5:
      var popz = document.getElementById('populations').value;
      var count = 0;
      var sum = 0;
      while (count<popz) {
        result = result && $( "input[name='popProb"+count+"']" ).val();
        sum = sum + Number($( "input[name='popProb"+count+"']" ).val());
        count++;
      }
      // alert("sum= "+sum+"");
      result = result && (Math.abs(sum-1)<0.001);
      break;
    case 6:
      var mudel = $("input[name=model]:checked").val();
      if (mudel=="expln" && !document.getElementById('rate').value) {
        result = 0;
      } else {
        var popz = document.getElementById('populations').value - (mudel=="expln");
        var count = 0;
        while (count<popz) {
          result = result && $( "input[name='logMean"+count+"']" ).val();
          count++;
        }
      }
      break;
    case 7:
      var mudel = $("input[name=model]:checked").val();
      var popz = (mudel!="lnln")*(document.getElementById('populations').value - (mudel=="expln") - 1) + 1;
      var count = 0;
      while (count<popz) {
        result = result && $( "input[name='stdDev"+count+"']" ).val();
        count++;
      }
      break;
/*    case 8:  //This happens ONSUBMIT!!!! So it should be done in that function.
      var wayofout = $("input[name='filename']:checked").val();
      if (wayofout == "yes") {
        result = document.getElementById('filename').value;
      } else if (wayofout!="no") {
        result = 0;
      }
      break; */
    default:
      break;
  };
  return (!result);
};


function dataOutput() {
        var request = $.ajax({
          url: 'outputfile.php',
          type: "POST",
          data: ({out: $('input:radio[name="writetofile"]:checked').val()}),
          dataType: "html"
        });

        request.done(function(msg) {
          $("#fileName").html(msg);
        });

        request.fail(function(jqXHR, textStatus) {
          alert( "Request failed: " + textStatus );
        });
};

function populationRate() {
        var requestRate = $.ajax({
          url: 'getrate.php',
          type: "POST",
          data: ({mod: $('input:radio[name="model"]:checked').val()}),
          dataType: "html"
        });

        requestRate.done(function(msg) {
          $("#popRate").html(msg);
        });

        requestRate.fail(function(jqXHR, textStatus) {
          alert( "Request failed: " + textStatus );
        });
  };

  function popProperties(whichone) {
        var requestStd = $.ajax({
          url: 'getprops.php',
          type: "POST",
          data: ({mod: $('input:radio[name="model"]:checked').val(), pops: document.getElementById('populations').value, req: "std"}), //({indata: vv })
          dataType: "html"
        });

        requestStd.done(function(msg) {
          $("#popStddev").html(msg);
        });

        requestStd.fail(function(jqXHR, textStatus) {
          alert( "Request failed: " + textStatus );
        });


        var requestLogm = $.ajax({
          url: 'getprops.php',
          type: "POST",
          data: ({mod: $('input:radio[name="model"]:checked').val(), pops: document.getElementById('populations').value, req: "logm"}), //({indata: vv })
          dataType: "html"
        });

        requestLogm.done(function(msg) {
          $("#popLogmeans").html(msg);
        });

        requestLogm.fail(function(jqXHR, textStatus) {
          alert( "Request failed: " + textStatus );
        });


        var requestPopprob = $.ajax({
          url: 'getprops.php',
          type: "POST",
          data: ({mod: $('input:radio[name="model"]:checked').val(), pops: document.getElementById('populations').value, req: "prob"}), //({indata: vv })
          dataType: "html"
        });

        requestPopprob.done(function(msg) {
          $("#popProbabilities").html(msg);
        });

        requestPopprob.fail(function(jqXHR, textStatus) {
          alert( "Request failed: " + textStatus );
        });

};

</script>

<!-- FINAL SUBMIT button -->
<script>
      $(function () {

        $('form').on('submit', function (e) {

          e.preventDefault();

          var wayofout = $("input[name='filename']:checked").val();
          if (!(/*document.getElementById('cellspersample').value &&*/document.getElementById('email').value) ) {
            alert('Please provide your email address before submitting.');
          } else if (!(wayofout == "yes" || wayofout!="no")) {
            alert('Please answer all open questions before submitting.');
          } else {



          var formData = $('form').serializeArray();
          $.ajax({
            type: 'post',
            url: 'writeToDb2.php',
            data: formData,
            dataType: 'html',
            success: function (msg) {
              //alert('form was submitted');
              $( "form" ).fadeOut( "slow", function(){
                $("#submit_return").html(msg); // Show data from php file.
                $("#aftersub").fadeIn();
              });
            }
          });
        };

        });

      });
</script>


</head>
<body>
<h1 class="naslov"> Stochastic Profiling - Generating Data</h1>

<div class="wrapper">
<form id ="sub" name="subm" method="post" > <!--onsubmit="return postandnext()"-->
<div id="accordion">
<h3>1. Choice of a Model</h3>
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
 <br>
  Please choose the model you would like to generate data from: </div>
  <center>
    <div class="iradioModel">
        <label for="lnln">
          <table>
            <tr><td> LN-LN </td></tr>
            <tr><td class="modelbox"> \(\definecolor{yellow}{RGB}{255, 210, 0} \definecolor{blue}{RGB}{0, 0, 255}
                                      X \sim \begin{array}{ll} LN(\mu_1, \sigma^2), & \text{if cell is of }\color{yellow} \text{type I} \\
                                                                        Exp(\lambda), & \text{if cell is of }\color{blue} \text{type II} \end{array}\)<br>
                  <img src="lnln.png" height="200" width="200" value="LN-LN"/></td></tr>
          </table>
          <input type="radio" name="model" id="lnln" value="lnln" onclick="populationRate();">
        </label>

        <label for="rlnln">
          <table>
            <tr><td> rLN-LN </td></tr>
            <tr><td class="modelbox">\(\definecolor{yellow}{RGB}{255, 210, 0} \definecolor{blue}{RGB}{0, 0, 255}
                                      X \sim \begin{array}{ll} LN(\mu_1, \sigma_1^2), & \text{if cell is of }\color{yellow} \text{type I} \\
                                                                 LN(\mu_2, \sigma_2^2), & \text{if cell is of }\color{blue} \text{type II} \end{array}\)<br>

                  <img src="rlnln.png" height="200" width="200" value="rLN-LN"/></td></tr>
          </table>
          <input type="radio" name="model" id="rlnln" value="rlnln" onclick="populationRate();">
        </label>

        <label for="expln">
          <table>
            <tr><td> Exp-LN </td></tr>
            <tr><td class="modelbox">\(\definecolor{yellow}{RGB}{255, 210, 0} \definecolor{blue}{RGB}{0, 0, 255}
                                      X \sim \begin{array}{ll} LN(\mu, \sigma^2), & \text{if cell is of }\color{yellow} \text{type I} \\
                                                                      Exp(\lambda), & \text{if cell is of }\color{blue} \text{type II} \end{array}\)<br>
                  <img src="expln.png" height="200" width="200" value="Exp-LN" /></td></tr>
          </table>
          <input type="radio" name="model" id="expln" value="expln" onclick="populationRate();">
        </label>
    </div> </center> <br>
    <button type="button" id="next1" style="float: right;"> Next </button>
    <!-- <button type="button" id="prev1" style="float: right;"> Previous </button> --> <br>
  </p>
</div>
<h3>2. Number of Populations</h3>
<div>
  <p>
    Please enter the number of different populations you would like to consider:
    <br> <input type="number" name="populations" min="1" id="populations"> <br><br>
    <button type="button" id="next2" style="float: right;"> Next </button>
    <button type="button" id="prev2" style="float: right;"> Previous </button><br>
  </p>
</div>
<h3>3. Number of Samples</h3>
<div>
  <p>
    Please enter the number of stochastic profiling samples you wish to generate:
    <br> <input type="number" name="samples" min="1" id="samples"> <br><br>
    <button type="button" id="next3" style="float: right;"> Next </button>
    <button type="button" id="prev3" style="float: right;"> Previous </button><br>
  </p>
</div>
<h3>4. Cells per Sample</h3>
<div>
  <p>
    Please enter the number of cells that should enter each sample:
    <br> <input type="text" name="cellspersample" id="cellspersample"> <br><br>
    <button type="button" id="next4" style="float: right;"> Next </button>
    <button type="button" id="prev4" style="float: right;"> Previous </button><br>
  </p>
</div>
<h3>5. Co-expressed Genes</h3>
<div>
  <p>
    Please enter the number of co-expressed genes you would like to collect in one cluster:
    <br> <input type="number" name="cluster" min="1" id="cluster"> <br><br>
    <button type="button" id="next5" style="float: right;"> Next </button>
    <button type="button" id="prev5" style="float: right;"> Previous </button><br>
  </p>
</div>
<h3>6. Population Probabilities</h3>
<div>
  <p>
    Please enter the probability of each population:
    <!-- FORMULA? -->
    <div id="popProbabilities"> </div> <br><br>
    <button type="button" id="next6" style="float: right;"> Next </button>
    <button type="button" id="prev6" style="float: right;"> Previous </button><br>
  </p>
</div>
<h3>7. Distribution properties - Log Means and Rate</h3>
<div>
  <p>
    Please enter the log-means for each of the lognormal populations:
    <div id="popLogmeans"> </div> <br><br>
    <!-- Please enter the rate of the exponential distribution for the exponential population: -->
    <br><div id="popRate"> </div> <br><br>
    <button type="button" id="next7" style="float: right;"> Next </button>
    <button type="button" id="prev7" style="float: right;"> Previous </button><br>
  </p>
</div>
<h3>8. Log-Standard Deviations</h3>
<div>
  <p>
    Please enter the log-standard deviation for each population:
    <div id="popStddev"> </div> <br><br>
    <button type="button" id="next8" style="float: right;"> Next </button>
    <button type="button" id="prev8" style="float: right;"> Previous </button><br>
  </p>
</div>
<h3>9. Output Dataset</h3>
<div>
  <p>
    When the data is created, it will be automatically submitted for analysis, of which results you will
    receive by email.  Would you also like to write the generated dataset to file for download?
    <br>
    <div id="radio">
      <input type="radio" id="radio1" name="writetofile" value="yes" onclick="dataOutput();"><label for="radio1"> Yes</label>
      <input type="radio" id="radio2" name="writetofile" value="no"  onclick="dataOutput();"><label for="radio2"> No </label>
    </div>
    <br><div id="fileName"> </div> <br><br>
    Please enter your e-mail address, to which you would like the results to be sent:
    <br> <input type="email" id="email" name="email"><br><br>
    <button type="submit" id="submitaj" style="float: right;"> Submit </button>
    <button type="button" id="prev9" style="float: right;"> Previous </button><br>
  </p>
</div>
</div>
</form>
</div>
<br><br><br>
<!--center-->
<table id="aftersub" align="center" class="aftersub">
  <tr><td>
<div style="width: 77%;" id="helmholtz_logo"><img src="logo_institut.svg"></div> <br> <!--margin-left: 14.8em; -->
<div id="submit_return"></div><!--/center-->
  </td></tr>
</table>
</body>
</html>
