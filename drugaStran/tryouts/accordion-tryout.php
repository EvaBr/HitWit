<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Stochastic Profiling - ML</title>
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
<link rel="stylesheet" type="text/css" href="custom.css">
<script>
$(document).ready(function(){
$(function() {
  $("#accordion").accordion();
  $($("#accordion > h3")[1]).addClass("ui-state-disabled");
  $($("#accordion > h3")[2]).addClass("ui-state-disabled");
});

$("#accordion").on("accordionbeforeactivate", function() {
  return !arguments[1].newHeader.hasClass("ui-state-disabled");
});

$("#next1, #next2").on('click', function() {
  var index = $("#accordion").accordion('option', 'active');
  index++;

  $($("#accordion > h3")[index]).removeClass("ui-state-disabled");
  $("#accordion").accordion("option", "active", index);
  $($("#accordion > h3")[index - 1]).addClass("ui-state-disabled");
});

});
</script>
</head>
<body>

<div id="accordion">
  <h3>Section 1</h3>
  <div>
    <p> To je moj gumb.
      <button type="button" id="next1" style="float: right;"> Next </button>
      <br>
    </p>
  </div>
  <h3>Section 2</h3>
  <div>
    <p>
      Druga sekcija.
      <button type="button" id="next2" style="float: right;"> Next </button>
    </p>
  </div>
  <h3>
    Section 3
    </h3>
  <div>
    <p>
      Tretja sekcijaaaa.
    </p>
  </div>
</div>

</body>
</html>
