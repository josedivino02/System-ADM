<?php
//redireciona se não estiver definido a URL
if (!defined('URL')) {
    header("Location: /");
    exit();
  }
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <title>ESTRUTURA MVC ADM</title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <link rel="stylesheet" href="<?php echo URLADM ?>app/adm/assets/css/css.css">
    <link rel="stylesheet" href="<?php echo URLADM ?>app/adm/assets/css/bootstrap.min.css">

    <link rel="shortcut icon" href="<?php echo URLADM ?>app/adm/assets/img/icon/mulher.ico">
</head>

  <script type="text/javascript" >
          var URL = "<?php echo URLSTS?>";
          var URLADM = "<?php echo URLADM ?>";
  </script>
<body>
