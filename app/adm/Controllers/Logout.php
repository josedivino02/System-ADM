<?php

namespace Adm\Controllers;
//redireciona se não estiver definido a URL
if (!defined('URL')) {
    header("Location: /");
    exit();
  }

//=------------------------------------------------------------------------------------------------------------
//Destroi a sessão do usuario
class Logout
{
    public function index()
    {
        //Seta variaveis para destruir
        unset($_SESSION['usuario_id'], $_SESSION['usuario_nome'], $_SESSION['usuario_nick'], $_SESSION['usuario_password'], $_SESSION['usuario_image']);
        //mensagem e redirecionamento
        $_SESSION['msg'] = "<div class='alert alert-success'>LOG100: Logout realizado com sucesso!</div>";
        
        $urlRedirect = URLADM ."login/index";
        header("Location: $urlRedirect");
    }
}
