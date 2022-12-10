<?php

namespace Adm\Controllers;
//redireciona se não estiver definido a URL
if (!defined('URL')) {
    header("Location: /");
    exit();
  }

//=------------------------------------------------------------------------------------------------------------
class Colors
{
    private $data;
    private $id;

//=------------------------------------------------------------------------------------------------------------
    //carrega a dashboard após o Login
    public function index()
    {

        $this->data = "<div class='alert alert-success'>USE100: Dashboard Bem vindo!</div>";
    }

//=------------------------------------------------------------------------------------------------------------
    public function listColors()
    {
        $this->data = filter_input_array(INPUT_POST, FILTER_DEFAULT);
        $sendAplication = new \Adm\Models\AdmColor();
        $sendAplication->listColors($this->data);
    }

//=------------------------------------------------------------------------------------------------------------
}