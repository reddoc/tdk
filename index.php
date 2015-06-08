<?php

$kelime = "";
$sozluk = "gts"; //güncel Türkçe sözlük

if(isset($_POST['kelime']) && isset($_POST['sozluk'])){
  $kelime = strtolower($_POST['kelime']);
  $sozluk = strtolower($_POST['sozluk']);
  
  require_once './curl.class.php';
  require_once './word.class.php';
  require_once './simple_html_dom.php';
  
  //db connection
  $dbh = new mysqli('127.0.0.1', 'reddoc_tdk', 'tdk', 'reddoc_tdk');
  $dbh->query("set names utf8;");
  
  $obj   = new word($dbh);
  $anlam = $obj->get_meaning($kelime, $sozluk);
}

?>
<!DOCTYPE html>
<html>
  <head>
    <meta name="viewport" content="width=device-width">
    <title>TDK - Kelime Sorgulama</title>
    <style>
      .row {
        width: 100%;
        border: 0px;
        margin: 0px;
        padding: 0px;
      }
      
      input {
        width: 95%;
        margin-top: 2px;
      }
      
      body {
        position: absolute;
        width: 100%;
        padding: 0px;
        margin: 0px;
      }
      
      .center {
        text-align: center;
      }
    </style>
  </head>
  <body>
    <div class="row">
      <div class="row">
        <form action="" method="POST">
          <div class="row center"><input type="text" name="kelime" value="<?php echo $kelime; ?>"></div>
          <div class="row center"><input type="submit" value="Sorgula"></div>
          <input type="hidden" value="<?php echo $sozluk;?>" name="sozluk">
        </form>
      </div>
      <div class="row">
        <?php echo $anlam==""&&$kelime!=""?'Sonuç bulunamadı.':$anlam; ?>
      </div>
    </div>
  </body>
</html>