<?php

//redireciona se não estiver definido a URL
if (!defined('URL')) {
    header("Location: /");
    exit();
  }


  echo "View - Dashboard <br>";
  echo "<a href='". URLADM . "logout/index'>Sair</a>";
?>
