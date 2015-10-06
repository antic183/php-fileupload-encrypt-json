<?php
require 'vendor/autoload.php';
require_once './Classes/Fileupload/CsvUploader.php';
?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8">
    <title>File Upload: Example CSV-File</title>
    <style>img{padding-top:50px; float:left; margin-right:30px;}</style>
  </head>
  <body>
    <div style="width:50%; margin:20px auto;">
      <h3>File Upload: Example CSV-File</h3>
      <form action='<?php echo $_SERVER["SCRIPT_NAME"]; ?>' method='POST' enctype='multipart/form-data' >
        <input type='file' name='csv' />
        <input type='submit' value='Hochladen' name='ok'/>
      </form>

      <?php
      echo isset($Fehler_meldung) ? $Fehler_meldung . "<hr/><br/>" : "";

      if (isset($_FILES['csv']) && isset($_POST['ok']) && !empty($_FILES['csv'])) {
        $upload = new Fileupload\CsvUploader('csv', 'csv');
        $upload->startUpload();
      }
      ?>
    </div>

  </body>
</html>
