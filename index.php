<?php

session_start(); //inicia a sessão
ob_start(); //buffer de saida para redirecionamento

//acessando paginas internas pela index
define('URL', true);

//Seta o autoload
require './vendor/autoload.php';

//carrega classe controller (construct)
$url = new Core\configController();
//carregamento da paginas (controler)
$url->loadPage();
        