<?php

namespace Adm\Controllers;
//redireciona se não estiver definido a URL
if (!defined('URL')) {
    header("Location: /");
    exit();
  }

//=------------------------------------------------------------------------------------------------------------
class Erro
{
    public function index()
    {
        echo 'pagina e erro';
    }
}
