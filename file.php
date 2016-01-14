<html>
<head>
<script>
function onUploadClick(event) {
    //var file_data = event.target.files;
    alert("hulala");
    //var file_data = document.getElementById('path').value;
    var file_data = $('#path').prop('files')[0];
    var form_data = new FormData();
    form_data.append('file', file_data);
    alert(form_data);
          $.ajax({
            type: 'post',
            url: 'uploadFile.php',
            dataType: 'json',  // what to expect back from the PHP script, if anything
            cache: false,
            contentType: false,
            processData: false,
            data: form_data,
            success: function (msg) {
              var upload_result = JSON.parse(msg);
              switch (upload_result){
                case 01: alert("The file extension is not .txt."); break;
                case 10: alert("The file is bigger than 5 mb."); break;
                case 00: alert("We couldn't upload the file."); break;
                default: document.getElementById('filename_sql').value = upload_result; $("#success").fadeIn();
              };

            }
              });
    /*$.ajax({
                url: 'uploadFile.php', // point to server-side PHP script
                dataType: 'html',  // what to expect back from the PHP script, if anything
                //cache: false,
                //contentType: false,
                //processData: false,
                data: form_data,
                type: 'post',
                success: function(php_script_response){
                    alert("haha");
                    alert(php_script_response); // display response from the PHP script, if any
                }
     });*/
}
</script>
</head>
<body></body>
</html>

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
    echo 'File should be of txt format and contain a data matrix with one dimension standing for genes and the other one for samples.
          Fields have to be separated by tabs or white spaces, but not by commas. Please upload the file:';
    echo '<br> <input type="file" id="path"> <button type="button" id="fileSubmit" style="float: right;" onClick="onUploadClick();">Upload</button>
    <div id="success" style="display: none;"><img style="width: 32px" src="success.png"></div><br>';
    echo '<br>
          Does the file contain column names?<br>
                <div class="iradio">
                  <input type="radio" name="colNam" id="colNamY" value="yes"><label for="colNamY"> Yes </label> &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;
                  <input type="radio" name="colNam" id="colNamN" value="no"><label for="colNamN"> No </label>
                </div><br>

          <br>
          Does the file contain row names?
                <div class="iradio">
                  <input type="radio" name="rowNam" id="rowNamY" value="yes"><label for="rowNamY"> Yes </label> &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;
                  <input type="radio" name="rowNam" id="rowNamN" value="no"><label for="rowNamN"> No </label>
                </div><br>

          <br>
          Do the columns stand for different genes or different samples?</li>
                <div class="iradio">
                  <input type="radio" name="genVsam" id="genes" value="genes"><label for="genes"> Genes </label> <br>
                  <input type="radio" name="genVsam" id="samples" value="samples"><label for="samples"> Samples </label>
                </div>';
  }
  ?>
