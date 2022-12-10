<?php

namespace Adm\Controllers;
//redireciona se não estiver definido a URL
if (!defined('URL')) {
    header("Location: /");
    exit();
  }

//=------------------------------------------------------------------------------------------------------------
class Dashboard
{
    private $data;

//=------------------------------------------------------------------------------------------------------------
    //carrega a dashboard após o Login
    public function index()
    {

        $this->data = "<div class='alert alert-success'>USE100: Dashboard Bem vindo!</div>";

        //carrega a view da dashboard
        $carregarView = new \Core\ConfigView("adm/Views/dashboard/dashboard", $this->data);
        $carregarView->renderizarLogado();
    }
}
